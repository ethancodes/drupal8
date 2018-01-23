<?php

namespace Drupal\articletab\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\articletab\Controller\ArticleTabController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;


class ArticleTabEdit extends FormBase {


  public function getFormId() {
    return 'articletab_edit';
  }


  public function buildForm(array $form, FormStateInterface $form_state) {

    $pathinfo = \Drupal::request()->getpathInfo();
    $pathinfo = explode('/', $pathinfo);
    $nid = $pathinfo[2];
    $sub = ArticleTabController::loadnode($nid);
    $sss = TRUE;
    
    if ($sub === FALSE) {
      $sub->fv = '';
      $sss = FALSE;
    }
    
    
    $form['fv'] = [
      '#type' => 'textfield',
      '#title' => 'Field Value',
      '#required' => TRUE,
      '#default_value' => $sub->fv
    ];
    
    $form['nid'] = [
      '#type' => 'hidden',
      '#value' => $nid
    ];
    $form['sss'] = [
      '#type' => 'hidden',
      '#value' => $sss
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => 'Submit',
    ];
    
    return $form;
  }


  public function validateForm(array &$form, FormStateInterface $form_state) {  
  
  }


  public function submitForm(array &$form, FormStateInterface $form_state) {

    $nid = $form_state->getValue('nid');
    $sss = $form_state->getValue('sss');
    $fv  = $form_state->getValue('fv');

    $connection = \Drupal::database();
    
    if ($sss) {

      // already exists, just update    
      $foo = $connection->update('articletab')
        ->fields(['fv' => $fv])
        ->condition('nid', $nid)
        ->execute();
      
    } else {

      // doesn't exist, insert
      $foo = $connection->insert('articletab')
        ->fields([
          'nid' => $nid,
          'fv' => $fv
          ])
        ->execute();
      
    }

    drupal_set_message('Updated!');  

  }

}
