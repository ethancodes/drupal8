<?php

namespace Drupal\genericcontactform\Controller;

class GenericContactFormController {

  protected function getModuleName() {
    return 'genericcontactform';
  }
  
  public static function loadsub($sid) {
  
    $q  = 'SELECT sid, name, email, color, message ';
    $q .= 'FROM {genericcontactform} ';
    $q .= 'WHERE sid = :sid ';
    $connection = \Drupal::database();
    $query = $connection->query($q, array(':sid' => $sid));
    $result = $query->fetchAll();
    
    if ($result) {
      return current($result);
    } else {
      return FALSE;
    }
  
  }
  
  public function adminlist() {
  
    $adminlist = '';
  
    // first we need to get a list of all the records in the table
    $q  = 'SELECT sid, name, email, color, message ';
    $q .= 'FROM {genericcontactform} ';
    $q .= 'ORDER BY sid DESC ';
    $connection = \Drupal::database();
    $query = $connection->query($q);
    $result = $query->fetchAll();
    
    if ($result) {
    
      // loop through results so that we can display them
      foreach ($result as $row) {
        $adminlist .= '<li>';
        $adminlist .= $row->name . ' (' . $row->email . ') ';
        $adminlist .= $row->message;
        $adminlist .= '</li>';
      }
    
    } else {
    
      $adminlist = '<li>No records at this time.</li>';
    
    }
        
  
    // return a render array
    return [
      '#markup' => '<ul>' . $adminlist . '</ul>',
    ];
    
  
  }

}
