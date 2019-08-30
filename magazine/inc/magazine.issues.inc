<?php

/**
  functions for Issues
  */


use Drupal\node\Entity\Node;
use Drupal\Component\Utility\SortArray;


function get_issue_footer($node) {
  
  $columns = [];
  
  $columns['column_1'] = _get_paragraph_text($node, 'field_footer_column');
  $columns['column_2'] = _get_paragraph_text($node, 'field_footer_column', 1);
  $columns['column_3'] = _get_paragraph_text($node, 'field_footer_column', 2);
  
  return $columns;
  
}


/**
  build a mini-menu for the Issue Nav and In Every Issue
  this is based on a Drupal Menu
  the Issue Nav always uses the most recent published Issue
  the IEI uses the Menu for THAT Issue
  so if you don't give me an Issue node I'll look up the most recent publised
  */
function get_issue_nav_menu($node = FALSE) {
  
  $nav = [];
  
  $menu_link_manager = \Drupal::service('plugin.manager.menu.link');
  $menu_link = $menu_link_manager->loadLinksByRoute(
    'entity.node.canonical', 
    array('node' => $node->id())
  );
  
  // get me the name of this menu
  $menu_name = FALSE;
  $issue_menu_item_id = FALSE;
  if (is_array($menu_link) && count($menu_link)) {
    $menu_link = current($menu_link);
    $menu_name = $menu_link->getMenuName();
    $issue_menu_item_id = $menu_link->getPluginId();
  }
  if (!$menu_name) return $nav;
  
  $menu_storage = \Drupal::entityTypeManager()->getStorage('menu_link_content');
  $menu_links = $menu_storage->loadByProperties([
    'menu_name' => $menu_name
  ]);
  if (empty($menu_links)) return $nav;
  
  /*
    alright so we have an array of all the links in this menu
    but they aren't in a safe order
    so the first thing i'm going to do is sort them by weight
  */
  $menu_links_too = [];
  foreach ($menu_links as $mlid => $menu_link) {
    $menu_item = [
      'mlid' => $menu_link->getPluginId(),
      'plid' => $menu_link->getParentId(),
      'title' => $menu_link->getTitle(),
      'url' => $menu_link->getUrlObject()->toString(),
      'weight' => $menu_link->getWeight()
    ];
    $menu_links_too[] = $menu_item;
  }
  usort($menu_links_too, [SortArray::class, 'sortByWeightElement']);
  
  /*
    okay now i'm going to make two passes through the links
    the first will set up the pages under this Issue Node
    the second will set up pages under those pages
  */
  
  foreach ($menu_links_too as $menu_link) {
    
    if ($menu_link['plid'] != $issue_menu_item_id) continue;
    $menu_link['children'] = [];
    $nav[$menu_link['mlid']] = $menu_link;
    
  }
  
  foreach ($menu_links_too as $menu_link) {

    if (!array_key_exists($menu_link['plid'], $nav)) continue;    
    $nav[$menu_link['plid']]['children'][] = $menu_link;
    
  }
    
  return $nav;
  
}


/**
  given a node id or node
  get the field_issue
  which is a content ref
  that should point to an issue
  return that node
  */
function get_parent_issue($nid, $node = FALSE) {
  
  if (!$node) {
    $node = Node::load($nid);
  }
  $foo = $node->get('field_issue')->get(0);
  if ($foo) {
    $target_id = $foo->target_id;
    if ($target_id) {
      $issue_node = Node::load($target_id);
      if ($issue_node->getType() == 'issue') return $issue_node;
    }
  }
  
  return FALSE;
  
}

/**
  what is the most recent
  published
  issue
  */
function get_current_issue() {
  
  $q  = 'SELECT nid, title ';
  $q .= 'FROM {node_field_data} ';
  $q .= 'WHERE type = :t ';
  $q .= 'AND status = :s ';
  $q .= 'ORDER BY created DESC LIMIT 1';
  $args = [
    ':t' => 'issue',
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
  everything that goes in the issue nav, not just the nav/menu
  */
function get_issue_nav() {
  
  $issue_nav = [];
  
  // find out the current issue is
  $current_issue = get_current_issue();
  if (!$current_issue) return $issue_nav;

  $issue_node = Node::load($current_issue['nid']);
  $issue_nav['title'] = $issue_node->getTitle();
  $issue_nav['url'] = $issue_node->toUrl()->toString();
  
  // load the cover image
  $cover_image = _get_paragraph_image($issue_node, 'field_cover_thumbnail');
  if ($cover_image) {
    $issue_nav['cover'] = _image_uri_to_style_url(
      $cover_image['uri'],
      'cover_290_x_364'
    );
  }
  
  // load the menu
  $issue_nav['menu'] = get_issue_nav_menu($issue_node);
  
  return $issue_nav;
  
}
