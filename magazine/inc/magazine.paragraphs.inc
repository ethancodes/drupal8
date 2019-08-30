<?php

/**
  functions for handling paragraph types
  */


use Drupal\paragraphs\Entity\Paragraph;
use Drupal\taxonomy\Entity\Term;


function _get_paragraph_text($p, $field_name, $delta = 0) {
  $foo = $p->get($field_name)->get($delta);
  if ($foo) {
    return $foo->value;
  }
  return '';
}

function _get_paragraph_image($p, $field_name = 'field_media') {
  
  $foo = $p->get($field_name);
  if ($foo->entity) {
    $uri = $foo->entity->get('field_media_image')->entity->uri->value;
    $alt = $foo->entity->get('field_media_image')->alt;
    if ($uri) {
      return [
        'uri' => $uri,
        'src' => file_create_url($uri),
        'alt' => $alt
      ];
    }
  }
  
}

/**
  if your media field accepts multiple values
  */
function _get_paragraph_images($p, $field_name = 'field_media') {
  
  $imgs = [];
  
  $foo = $p->get($field_name);
  foreach ($foo as $bar) {
    if ($bar->entity) {
      $uri = $bar->entity->get('field_media_image')->entity->uri->value;
      $alt = $bar->entity->get('field_media_image')->alt;
      if ($uri) {
        $imgs[] = [
          'uri' => $uri,
          'src' => file_create_url($uri),
          'alt' => $alt
        ];
      }
    }
  }
  
  return $imgs;
  
}

function _get_paragraph_links($p, $field_name = 'field_link') {
  
  $links = [];
  
  $foo = $p->get($field_name);
  foreach ($foo as $f) {
    $links[] = [
      'uri' => $f->uri,
      'title' => $f->title
    ];
  }
  
  return $links;
  
}

function _get_paragraph_terms($p, $field_name) {
  
  $terms = [];
  
  $foo = $p->get($field_name);
  foreach ($foo as $f) {
    $term = Term::load($f->target_id);
    $terms[] = [
      'tid' => $f->target_id,
      'name' => $term->getName()
    ];
  }
  
  return $terms;
  
}

function _get_paragraph_entity_reference($p, $field_name) {
  
  $ef = [];
  
  $foo = $p->get($field_name)->get(0);
  if ($foo) {
    $ef['target_id'] = $foo->target_id;
    $ef['url'] = _uri2url('internal:/node/' . $foo->target_id);
  }
  
  return $ef;
  
}