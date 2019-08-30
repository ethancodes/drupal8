<?php

namespace Drupal\magazine_issue_pages\Controller;

use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Drupal\Core\Controller\ControllerBase;
use Drupal\magazine_issue_pages\Routing\MagazineIssuePagesRoutes;

class MagazineIssuePagesController extends ControllerBase {
  
  /**
    we're expecting a URL that looks like /foo/from-our-readers
    so what we're going to try to do here is
    see if there's an Issue with the URL "foo"
    if not, return a 404
    if there is, we have a Node ID
    
    so we're going to load a View
    that would be "Issue - From Our Readers"
    and pass it the Node ID as a Contextual Filter
    and barf out the markup
    */
  public function from_our_readers($issue_nid = FALSE) {
    
    if (!$issue_nid) {
      throw new NotFoundHttpException();
    }
    
    $view = \Drupal\views\Views::getView('issue_from_our_readers');
    $view->setArguments([$issue_nid]);
    $view->execute();
    $rendered = $view->render();
    return $rendered;
    
  }
  
  public function at_rensselaer($issue_nid = FALSE) {

    if (!$issue_nid) {
      throw new NotFoundHttpException();
    }
    
    $view = \Drupal\views\Views::getView('issue_at_rensselaer');
    $view->setArguments([$issue_nid]);
    $view->execute();
    $rendered = $view->render();
    return $rendered;
    
  }
  
  public function features($issue_nid = FALSE) {

    if (!$issue_nid) {
      throw new NotFoundHttpException();
    }
    
    $view = \Drupal\views\Views::getView('issue_features');
    $view->setArguments([$issue_nid]);
    $view->execute();
    $rendered = $view->render();
    return $rendered;
    
  }  
  
}