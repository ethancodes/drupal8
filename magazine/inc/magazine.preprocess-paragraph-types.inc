<?php

/**
  preprocess paragraph types
  */

use Drupal\paragraphs\Entity\Paragraph;

/**
  set up variables for the Basic Text Paragraph Type
  */
function _preprocess_paragraph_basic_text($paragraph) {
  
  $p = [];  
  $p['text'] = _get_paragraph_text($paragraph, 'field_text');
  return $p;
  
}

/**
  set up variables for the Image Paragraph Type
  */
function _preprocess_paragraph_image($paragraph) {
  
  $p = [];
  $p['width'] = _get_paragraph_text($paragraph, 'field_width');
  $p['image'] = _get_paragraph_image($paragraph, 'field_image');
  return $p;
  
}

/**
  set up variables for the Text + Image Paragraph Type
  */
function _preprocess_paragraph_text_image($paragraph) {
  
  $p = [];
  
  $p['align'] = _get_paragraph_text($paragraph, 'field_image_alignment');
  $p['image'] = _get_paragraph_image($paragraph, 'field_image');
  $p['text'] = _get_paragraph_text($paragraph, 'field_text');
  $p['caption'] = _get_paragraph_text($paragraph, 'field_image_caption');
  
  return $p;
  
}

/**
  set up variables for the Embedded Audio Paragraph Type
  @TODO
  */
function _preprocess_paragraph_embedded_audio($paragraph) {
  
  $p = [];
  return $p;
  
}

/**
  set up variables for the Embedded Video Paragraph Type
  @TODO
  */
function _preprocess_paragraph_embedded_video($paragraph) {
  
  $p = [];
  return $p;
  
}

/**
  set up variables for the Notable Alumni Paragraph Type
  */
function _preprocess_paragraph_notable_alumni($paragraph) {
  
  $p = [];
  $p['image'] = _get_paragraph_image($paragraph, 'field_image');
  $p['text'] = _get_paragraph_text($paragraph, 'field_text');  
  return $p;
  
}
