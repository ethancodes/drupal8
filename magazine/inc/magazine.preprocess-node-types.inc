<?php

/**
  preprocess node types
  */


use Drupal\paragraphs\Entity\Paragraph;


/**
  set up variables for Home Page Content Type
  */
function _preprocess_node_home_page($node) {
  
  $n = [];
  
  $n['art'] = _get_paragraph_text($node, 'field_art');
  $n['featured_article'] = _get_paragraph_entity_reference(
    $node, 
    'field_featured_article');
  $n['ad'] = _get_paragraph_image($node, 'field_ad');
  
  $links = _get_paragraph_links($node, 'field_ad_url');
  $link = current($links);
  $n['ad_url'] = _uri2url($link['uri']);
  
  $from_our_readers = [];
  $foo = $node->get('field_from_our_readers');
  foreach ($foo as $p) {
    $from_our_readers[] = get_from_our_readers($p->target_id);
  }
  $n['from_our_readers'] = $from_our_readers;
  
  $n['snapshots'] = get_snapshots();

  return $n;
  
}

/**
  set up variables for Issue Content Type
  */
function _preprocess_node_issue($node) {
  
  $n = [];
  
  $hero = [];
  $hero['image'] = _get_paragraph_image($node, 'field_hero_image');
  $hero['text'] = _get_paragraph_text($node, 'field_hero_text');
  $hero['feature'] = _get_paragraph_entity_reference($node, 'field_hero_feature');
  $n['hero'] = $hero;
  
  $n['in_every_issue'] = get_issue_nav_menu($node);
  
  $notable_alumni = [];
  $foo = $node->get('field_notable_alumni');
  foreach ($foo as $p) {
    $p = Paragraph::load($p->target_id);
    $bar = _preprocess_paragraph_notable_alumni($p);
    if ($bar['image']['uri']) {
      $bar['image']['alum_210_x_158'] = _image_uri_to_style_url(
        $bar['image']['uri'],
        'alum_210_x_158'
      );
    }
    if ($bar['text'] || $bar['image']) {
      $notable_alumni[] = $bar;
    }
  }
  $n['notable_alumni'] = $notable_alumni;
  
  
  $class_notes = [];
  $class_notes['call_out'] = _get_paragraph_text(
    $node,
    'field_class_notes_call_out'
  );
  $class_notes['years'] = get_class_notes_years();
  $n['class_notes'] = $class_notes;
  
  
  $n['snapshot_text'] = _get_paragraph_text($node, 'field_snapshot_text');
  $snapshots = _get_paragraph_images($node, 'field_snapshot_image');
  $n['snapshot_images'] = $snapshots;
  
  return $n;
  
}


/**
  set up variables for Advanced Page Content Type
  */
function _preprocess_node_advanced_page($node) {
  
  $n = [];
  
  $n['title'] = $node->getTitle();
  $n['subtitle'] = _get_paragraph_text($node, 'field_subtitle');
  $n['byline'] = _get_paragraph_text($node, 'field_byline');
  
  $n['page_type'] = _get_paragraph_terms($node, 'field_page_type');
  
  // categories
  $n['news_categories'] = _get_paragraph_terms($node, 'field_news_categories');
  $n['global_challenges'] = _get_paragraph_terms($node, 'field_global_challenges');
  $n['research_thrusts'] = _get_paragraph_terms($node, 'field_research_thrusts');
  
  // images
  $n['images'] = _get_paragraph_images($node, 'field_images');
  
  // author info
  $author = [];
  $u = $node->getOwner();
  $author['name'] = $u->getDisplayName();
  $author['email'] = $u->getEmail();
  if (!$u->get('user_picture')->isEmpty()) {
    $photo_uri = $u->get('user_picture')->entity->getFileUri();
    $author['photo'] = _image_uri_to_style_url($photo_uri, 'thumbnail');
  }
  $n['author'] = $author;
  
  return $n;
  
}


/**
  set up variables for Basic Pages
  */
function _preprocess_node_page($node) {
  
  $n = [];
  
  $slug = $node->toUrl()->toString();
  //$n['slug'] = $slug;
  if ($slug == '/categories') {
    $n['categories'] = get_categories_for_display();
  }
  
  return $n;
  
}