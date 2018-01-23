<?php

namespace Drupal\articletab\Controller;

use Drupal\node\Entity\Node;
use Drupal\Core\Access\AccessResult;


class ArticleTabController {

  protected function getModuleName() {
    return 'articletab';
  }
  
  
  /*
    this is our very own function to determine whether or not you can see the Article Tab
    two criteria must be met
    - the node must be of type 'article'
    - you must have the 'access Article Tab' permission
  */
  public function articleTabAccess($nid = 0, $debug = FALSE) {
    
    if ($debug) {
      echo 'articleTabAccess(' . $nid . ')<br />';
    }
    
    $account = \Drupal::currentUser();
    if ($debug) {
      echo 'user id: ' . $account->Id() . '<br />';
    }

    $access = AccessResult::allowedIfHasPermission($account, 'access Article Tab');
    if ($debug) {
      echo 'has permission? ';
      var_dump($account->hasPermission('access Article Tab'));
      echo '<br />';
    }
    
    
    if (!$nid) {
      $pathinfo = \Drupal::request()->getpathInfo();
      $pathinfo = explode('/', $pathinfo);
      $nid = $pathinfo[2];
    }
    
    if ($debug) {
      echo 'nid: ' . $nid . '<br />';
    }      
    
    if ($nid) {
      $node = Node::load($nid);
      if ($debug) {
        echo 'node type: ' . $node->getType() . '<br />';
      }
      $access = $access->andIf(AccessResult::allowedIf($node->getType() == 'article'));
    }
    
    return $access;
  
  }
  
  
  /*
    helper function so that we can debug articleTabAccess()
  */
  public static function articleTabTest($nid = 0) {
  
    $access = ArticleTabController::articleTabAccess($nid, TRUE);
    var_dump($access);
    exit;
  
  }
  
  
  public static function loadnode($nid) {
  
    $q  = 'SELECT fv ';
    $q .= 'FROM {articletab} ';
    $q .= 'WHERE nid = :nid ';
    $connection = \Drupal::database();
    $query = $connection->query($q, array(':nid' => $nid));
    $result = $query->fetchAll();
    
    if ($result) {
      return current($result);
    } else {
      return FALSE;
    }
  
  }

}
