<?php

/**
  the vocabularies we want to use
  */
function get_vocabularies() {
  
  $vocabs = [
    'news_categories' => 'News Categories',
    'global_challenges' => 'Global Challenges',
    'research_thrusts' => 'Research Thrusts'
  ];
  
  return $vocabs;
  
}


/**
  get all the titles+tids in a vocabulary
  */
function get_terms($vid) {
  
  $terms = [];
  
  $tree = \Drupal::entityTypeManager()
    ->getStorage('taxonomy_term')
    ->loadTree($vid);
  foreach ($tree as $t) {
    $terms[$t->tid] = $t->name;
  }
  
  return $terms;
  
}


/**
  get the number of published nodes in a particular term
  */
function get_number_of_nodes_in($tid) {
  
  $q  = 'SELECT COUNT(nid) AS c FROM taxonomy_index ';
  $q .= 'WHERE status = :s ';
  $q .= 'AND tid = :t ';
  $args = [
    ':s' => 1,
    ':t' => $tid
  ];
  
  $connection = \Drupal::database();
  $result = $connection->query($q, $args);
  if ($result) {
    $row = $result->fetchAssoc();
    return $row['c'];
  }
  return 0;
  
}


/**
  so this function is going to put everything together so that
  we can display this on the front end
  */
function get_categories_for_display() {

  $categories = [];
  
  $vocabs = get_vocabularies();
  foreach ($vocabs as $vid => $vname) {
    
    $this_vocab = [
      'terms' => []
    ];
    
    $terms = get_terms($vid);
    foreach ($terms as $tid => $tname) {
      
      $num = get_number_of_nodes_in($tid);
      if ($num) {
        $this_vocab['terms'][$tid] = $tname;
      }
      
    }
    
    if (count($this_vocab['terms'])) {
      $this_vocab['name'] = $vname;
      $categories[$vid] = $this_vocab;
    }
    
  }
  
  return $categories;
  
}