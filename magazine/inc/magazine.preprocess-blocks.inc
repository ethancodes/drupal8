<?php

/**
  preprocess blocks
  */

function _preprocess_block_classnotesactionbar(&$variables) {
  $variables['class_notes_years'] = get_class_notes_years();
}