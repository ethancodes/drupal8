<?php

/**
  functions for garbage on the Home Page
  */


use Drupal\node\Entity\Node;
use Drupal\paragraphs\Entity\Paragraph;


/**
  get me the current home page
  */
function get_current_homepage() {
  
  $q  = 'SELECT nid, title ';
  $q .= 'FROM {node_field_data} ';
  $q .= 'WHERE type = :t ';
  $q .= 'AND status = :s ';
  $q .= 'ORDER BY created DESC LIMIT 1';
  $args = [
    ':t' => 'home_page',
    ':s' => 1
  ];
  
  $connection = \Drupal::database();
  
  $result = $connection->query($q, $args);
  if ($result) {
    $row = $result->fetchAssoc();
    if ($row) return $row;
  }
  
  return FALSE;
    
}


/**
  get the four At Rensselaer articles
  specified in a particular Home Page
  if you don't give me a nid I'll just use the current home page
  */
function get_at_rensselaer($nid = FALSE) {
  
  $articles = [];
  
  if (!$nid) {
    $hp = get_current_homepage();
    $nid = $hp['nid'];
  }
  
  $hp = Node::load($nid);
  if (!$hp) return $articles;
  
  $foo = $hp->get('field_at_rensselaer');
  foreach ($foo as $p) {
    $arnode = Node::load($p->target_id);
    $articles[] = [
      'title' => $arnode->getTitle(),
      'url' => $arnode->toUrl()->toString(),
      'image' => _get_first_image($arnode, 'image_xs')
    ];
  }
  
  return $articles;
  
}

/**
  given a node id
  return all the info for display on the home page
  */
function get_from_our_readers($nid) {
  
  $f = [
    'title' => '',
    'text' => '',
    'image' => '',
  ];
  
  $n = Node::load($nid);
  
  $f['title'] = $n->getTitle();
  $f['image'] = _get_first_image($n);
  $f['text'] = _get_first_text($n);
  
  return $f;
  
}


/**
  get the snapshots for the home page
  this all comes from a view, so just go there
  there are two templates for this view as well
  */
function get_snapshots() {
  
  $view = \Drupal\views\Views::getView('home_page_snapshots');
  $view->execute();
  $rendered = $view->render();
  $rendered = \Drupal::service('renderer')->render($rendered);
  return (string)$rendered;
  
}


function _get_first_image($n, $image_style = 'thumbnail') {
  
  $images = _get_paragraph_images($n, 'field_images');
  if (count($images)) {
    $i = current($images); // only the first one
    return _image_uri_to_style_url($i['uri'], $image_style);
  }  
  
  return '';
  
}

function _get_first_text($n) {
  
  $paragraphs = $n->get('field_paragraphs');
  foreach ($paragraphs as $p) {
    $sub_p = Paragraph::load($p->target_id);
    if ($sub_p->getType() == 'basic_text') {
      return _get_paragraph_text($sub_p, 'field_text');
    }
  }
  
  return '';
  
}