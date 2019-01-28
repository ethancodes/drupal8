<?php

namespace Drupal\morningmail\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Mail\MailManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Language\LanguageManagerInterface;
use Drupal\views\Views;

/**
 * Controller for Morning Mail
 */
class MorningMailController extends ControllerBase {

  protected $mailManager;
  protected $languageManager;
  public function __construct(MailManagerInterface $mail_manager, LanguageManagerInterface $language_manager) {
    $this->mailManager = $mail_manager;
    $this->languageManager = $language_manager;
  }
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.mail'),
      $container->get('language_manager')
    );
  }

  private function is_remote_addr_ok($remote_addr) {
    
    if ($remote_addr == 'IPV6') return TRUE;
    if ($remote_addr == 'IPV4') return TRUE;
    
    return FALSE;
    
  }  
  
  public function test_collect($d) {
    
    echo __FUNCTION__;
    echo '<pre>'; var_dump($d); echo '</pre>';
    $collection = MorningMailController::collect($d);
    echo '<pre>'; var_dump($collection); echo '</pre>'; 
    exit;
    
  }  
  
  public function test_email($d) {

    $ref = $_SERVER['REMOTE_ADDR'];
    if (!$this->is_remote_addr_ok($ref)) {
      \Drupal::logger('morningmail')->notice('test_email attempt from ' . $ref);
      exit;
    }
    
    $to = 'test@rpi.edu';
    $from = 'morningmail@rpi.edu';

    $params = [
      'format' => 'text/html',
      'charset' => 'UTF-8',
      'subject' => '[TEST] ' . MorningMailController::buildSubject($d),
      'articles' => MorningMailController::collect($d)
    ];

    if (count($params['articles']) == 0) {
      \Drupal::logger('morningmail')->notice('No articles to send.');
      exit;
    }

    $language_code = $this->languageManager->getDefaultLanguage()->getId();
    
    $result = $this->mailManager->mail(
      'morningmail', 
      'go', 
      $to, 
      $language_code, 
      $params, 
      $from,
      TRUE
    );

    var_dump($result);
    
    exit;
  
  }
  
  
  public function send_email() {
  
    $ref = $_SERVER['REMOTE_ADDR'];
    if (!$this->is_remote_addr_ok($ref)) {
      \Drupal::logger('morningmail')->notice('send_email attempt from ' . $ref);
      exit;
    }
    
    $to = 'morningmail@rpi.edu';
    $from = 'morningmail@rpi.edu';

    $params = [
      'format' => 'text/html',
      'charset' => 'UTF-8',
      'subject' => MorningMailController::buildSubject(FALSE),
      'articles' => MorningMailController::collect(FALSE)
    ];

    if (count($params['articles']) == 0) {
      \Drupal::logger('morningmail')->notice('No articles to send.');
      exit;
    }

    $language_code = $this->languageManager->getDefaultLanguage()->getId();
    
    $result = $this->mailManager->mail(
      'morningmail', 
      'go', 
      $to, 
      $language_code, 
      $params, 
      $from,
      TRUE
    );

    var_dump($result);
    
    exit;
  
  }  
  
  
  /**
    * for testing/dev purposes
    * this function will take anything "new"
    * and set the pubdate field
    */
  public function mark_pubdates() {
    
    // what's our pubdate going to be?
    $pubdate = date('Y-m-d');
    echo '<pre>'; echo $pubdate; echo '</pre>';
    
    // get everything "new"
    $cutoff = strtotime('-36 hours');
    $nodes = [];
    $q  = 'SELECT nid, title FROM {node_field_data} ';
    $q .= 'WHERE created > :cutoff ';
    $args = [':cutoff' => $cutoff];
    $result = db_query($q, $args);
    while ($r = $result->fetchAssoc()) {
      $nodes[$r['nid']] = $r['title'];
    }
    echo '<pre>'; var_dump($nodes); echo '</pre>';
    
    // loop through those
    foreach ($nodes as $id => $title) {
      echo $title . ' (' . $id . ') ';
      
      $node = \Drupal\node\Entity\Node::load($id);
      
      $node_pubdate = $node->get('field_publish_date')->getString();
      echo $node_pubdate . ' ';
      if ($node_pubdate == '') {
        // set the pubdate
        echo 'SETTING';
        $node->set('field_publish_date', $pubdate);
        $node->save();
      }
      echo '<br />';
      
    }
        
    
    exit;
      
  }
  
  
  public function collect($d = FALSE) {
    
    if (!$d) $d = date('Ymd');
    
    $collection = [];
    
    $args = [ $d ];
    $view = Views::getView('preview');
    if (is_object($view)) {
      $view->setArguments($args);
      $view->setDisplay('page_1');
      $view->preExecute();
      $view->execute();
      
      foreach ($view->result as $key => $value) {
        $entity = $value->_entity;
        $title  = $entity->get('title')->getValue()[0]['value'];
        $body   = $entity->get('body')->getValue()[0]['value'];
        $rurl   = $entity->get('field_remote_url')->getValue()[0]['value'];
       
        $title = strip_tags($title); 
        $body  = MorningMailController::handleBody($body);
        
        $collection[] = [
          'title' => $title,
          'text'  => $body,
          'url'   => $rurl,
        ];
      }      
      
    }
    
    return $collection;
    
  }
  
  public function handleBody($b) {
    
    $b = strip_tags($b, '<a>');
    $b = preg_replace('/\s+/', ' ', $b);
    
    $b = \Drupal\Component\Utility\Unicode::truncate($b, 600, TRUE, TRUE);
    
    return $b;
    
  }
  
  public function buildSubject($d) {
    
    if (!$d) $d = date('Ymd');
    
    // $d should look like YYYYMMDD
    $subject  = 'Morning Mail: ';
    $subject .= substr($d, 4, 2) . '-';
    $subject .= substr($d, 6, 2) . '-';
    $subject .= substr($d, 0, 4);
    return $subject;
    
  }
  
}
