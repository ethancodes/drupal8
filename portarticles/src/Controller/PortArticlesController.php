<?php

namespace Drupal\portarticles\Controller;

use Drupal\node\Entity\Node;

class PortArticlesController {

  protected function getModuleName() {
    return 'portarticles';
  }
  
  
  public static function export() {
  
    $output = [];
  
    $q  = 'SELECT n.nid, nfd.title, nfd.created, nb.body_value ';
    $q .= 'FROM {node} AS n ';
    $q .= 'LEFT JOIN {node_field_data} AS nfd ON n.nid = nfd.nid ';
    $q .= 'LEFT JOIN {node__body} AS nb ON n.nid = nb.entity_id ';
    $q .= 'WHERE n.type = :type ';
    $q .= 'AND nfd.status = 1 ';          // only query PUBLISHED articles
    $q .= 'ORDER BY nfd.created DESC ';
    
    $connection = \Drupal::database();
    $query = $connection->query($q, array(':type' => 'article'));
    $result = $query->fetchAll();
    
    if ($result) {
      $output = $result;      
    }
    
    
    // convert to json and output
    echo json_encode($output);
    exit;
  
  }
  


  public static function import() {
  
    $p = drupal_get_path('module', 'portarticles');
    $json = file_get_contents($p . '/demo.json');
    var_dump($json);
    
    $articles = json_decode($json);
    var_dump($articles);
    
    if ($articles) {
      foreach ($articles as $a) {
      
        // create a node for this article
        $node = Node::create([
          'type' => 'article',
          'title' => $a->title,
          'created' => $a->created,
          'changed' => $a->created,
          'body' => $a->body_value
        ]);
        $node->setPublished(FALSE);
        $node->save();
        echo 'Created as Node ID ' . $node->id() . '<br />';
        
      }
    }
    
    exit;
    
  }

}
