<?php

/**
  functions for Class Notes
  */

/**
  get years from published class notes
  */
function get_class_notes_years() {
  
  $years = [];
  
  $q  = 'SELECT DISTINCT nfy.field_year_value ';
  $q .= 'FROM {node_field_data} nfd, ';
  $q .= '{node__field_year} nfy ';
  $q .= 'WHERE type = :t ';
  $q .= 'AND nfd.nid = nfy.entity_id ';
  $q .= 'AND status = 1 ';
  $q .= 'ORDER BY nfy.field_year_value';
  $args = [
    ':t' => 'class_note'
  ];
  
  $connection = \Drupal::database();
  
  $result = $connection->query($q, $args);
  if ($result) {
    while ($row = $result->fetchAssoc()) {
      $years[] = $row['field_year_value'];
    }
  }
  
  return $years;  
  
}

