<?php

namespace Drupal\genericcontactform\Controller;

class GenericContactFormController {

  protected function getModuleName() {
    return 'genericcontactform';
  }
  
  public function adminlist() {
  
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
