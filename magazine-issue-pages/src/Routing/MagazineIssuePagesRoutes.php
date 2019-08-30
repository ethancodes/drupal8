<?php

namespace Drupal\magazine_issue_pages\Routing;

use Symfony\Component\Routing\Route;

class MagazineIssuePagesRoutes {
  
  public function routes() {
    
    $routes = [];
    
    // set up some shortcuts because i'm lazy
    $route_module = 'magazine_issue_pages';
    $controller_path = '\Drupal\magazine_issue_pages\Controller\MagazineIssuePagesController';
    
    $issues = $this->get_issues();
    
    foreach ($issues as $nid => $issue) {
      
      // FROM OUR READERS
      $path = $issue['url'] . '/from-our-readers';
      $def = [
        '_controller' => $controller_path . '::from_our_readers',
        '_title' => 'From Our Readers for ' . $issue['name'],
        'issue_nid' => $nid
      ];
      $perms = [
        '_permission' => 'access content'
      ];
      
      $route_slug = 'issue' . $nid . 'from-our-readers';
      $routes[$route_module . '.' . $route_slug] = new Route(
        $path,
        $def,
        $perms
      );
      
      
      // AT RENSSELAER
      $path = $issue['url'] . '/at-rensselaer';
      $def = [
        '_controller' => $controller_path . '::at_rensselaer',
        '_title' => 'At Rensselaer for ' . $issue['name'],
        'issue_nid' => $nid
      ];
      
      $route_slug = 'issue' . $nid . 'at-rensselaer';
      $routes[$route_module . '.' . $route_slug] = new Route(
        $path,
        $def,
        $perms
      );
      
      
      // FEATURES
      $path = $issue['url'] . '/features';
      $def = [
        '_controller' => $controller_path . '::features',
        '_title' => 'Features for ' . $issue['name'],
        'issue_nid' => $nid
      ];
      
      $route_slug = 'issue' . $nid . 'features';
      $routes[$route_module . '.' . $route_slug] = new Route(
        $path,
        $def,
        $perms
      );
      
    }
    
    
    return $routes;
    
  }
  
  
  /**
    get all the slugs for Issues
    */
  public function get_issues() {
    
    $issues = [];
    
    $q  = 'SELECT nid, title FROM {node_field_data} ';
    $q .= 'WHERE type = :t ';
    $q .= 'AND status = :s ';
    $q .= 'ORDER BY created DESC ';
    $args = [
      ':t' => 'issue',
      ':s' => 1
    ];
    
    $connection = \Drupal::database();
    $result = $connection->query($q, $args);
    if ($result) {
      
      $am = \Drupal::service('path.alias_manager');
      
      while ($row = $result->fetchAssoc()) {
        
        $d = [
          'id' => $row['nid'],
          'name' => $row['title'],
          'url' => $am->getAliasByPath('/node/'.$row['nid'])
        ];
        $issues[$row['nid']] = $d;
      }      
    }
    
    return $issues;
    
  }
  
}