<?php

namespace Drupal\mytests\Controller;

#use Drupal\lazyconfig\

class MyTestsController {

  protected function getModuleName() {
    return 'mytests';
  }
  
  
  public function lazyconfig() {
  
    $cfg = lazyconfig_get('mytests');
    $cfgstr = MyTestsController::array_to_string($cfg);
    
    return [
      '#markup' => '<pre>' . $cfgstr . '</pre>'
    ];
  
  }
  
  
  
  private function array_to_string($a, $depth = 0) {
  
    if (!is_array($a)) return $a;
  
    $s = '';
    
    foreach ($a as $k => $v) {
    
      $s .= str_repeat('  ', $depth);
      $s .= $k . ': ';
    
      if (is_array($v)) {
        
        $s .= chr(10);
        $s .= MyTestsController::array_to_string($v, $depth + 1);
        
      } else {
        
        /* not an array */
        $s .= $v . chr(10);
        
      }
    
    }
    
    return $s;
  
  }
  

}
