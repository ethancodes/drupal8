<?php
/**
 * @file
 * theme-settings.php
 *
 * Provides theme settings for Bootstrap Barrio based themes when admin theme is not.
 *
 * @see ./includes/settings.inc
 */

use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Url;

/**
 * Implements hook_form_FORM_ID_alter().
 */
function papercut_form_system_theme_settings_alter(&$form, FormStateInterface $form_state, $form_id = NULL) {

  // General "alters" use a form id. Settings should not be set here. The only
  // thing useful about this is if you need to alter the form for the running
  // theme and *not* the theme setting.
  // @see http://drupal.org/node/943212
  if (isset($form_id)) {
    return;
  }
  
  // Header
  $form['papercut_header'] = array(
    '#type' => 'details',
    '#title' => t('Header'),
    '#group' => 'bootstrap',
  );

  // Header Background Color
  $form['papercut_header']['background_color'] = array(
    '#type' => 'details',
    '#title' => t('Background Color'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['papercut_header']['background_color']['papercut_header_background_color'] = array(
    '#type' => 'select',
    '#title' => t('Background Color'),
    '#default_value' => theme_get_setting('papercut_header_background_color'),
    '#options' => array(
      'red' => t('Red'),
      'gray' => t('Gray'),
    ),
  );
  
  // Header Nav
  $form['papercut_header']['nav'] = array(
    '#type' => 'details',
    '#title' => t('Nav'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['papercut_header']['nav']['papercut_header_nav'] = array(
    '#type' => 'select',
    '#title' => t('Nav'),
    '#default_value' => theme_get_setting('papercut_header_nav'),
    '#options' => array(
      0 => t('No nav'),
      1 => t('Full nav'),
      2 => t('Search only')
    ),
  );

  
  // Footer
  $form['papercut_footer'] = array(
    '#type' => 'details',
    '#title' => t('Footer'),
    '#group' => 'bootstrap',
  );

  // Footer Size
  $form['papercut_footer']['footer_size'] = array(
    '#type' => 'details',
    '#title' => t('Footer Size'),
    '#collapsible' => TRUE,
    '#collapsed' => TRUE,
  );
  $form['papercut_footer']['footer_size']['papercut_footer_size'] = array(
    '#type' => 'select',
    '#title' => t('Footer Size'),
    '#default_value' => theme_get_setting('papercut_footer_size'),
    '#options' => array(
      '1' => t('Small'),
      '2' => t('Large'),
    ),
  );  
  
  
}