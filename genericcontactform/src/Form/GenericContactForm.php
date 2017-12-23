<?php

namespace Drupal\genericcontactform\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

class GenericContactForm extends FormBase {


  public function getFormId() {
    return 'genericcontactform_displayform';
  }


  public function buildForm(array $form, FormStateInterface $form_state) {

    $form['description'] = [
      '#type' => 'item',
      '#markup' => 'What can I do for you?'
    ];
    
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => 'Name',
      '#required' => TRUE
    ];
    
    $form['email'] = [
      '#type' => 'email',
      '#title' => 'Email',
      '#required' => TRUE
    ];
    
    $form['color'] = [
      '#type' => 'select',
      '#title' => 'What is your favorite color?',
      '#options' => [
        'red' => 'Red',
        'green' => 'Green',
        'blue' => 'Blue'
      ],
#      '#required' => TRUE
      '#empty_option' => '- None -',
      '#empty_value' => 'none'
    ];
    
    $form['message'] = [
      '#type' => 'textarea',
      '#title' => 'Message',
      '#required' => TRUE
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
    ];

    return $form;
  }


  public function validateForm(array &$form, FormStateInterface $form_state) {
  
    $msg = $form_state->getValue('message');
    $p = strpos(strtolower($msg), 'hello');
    if ($p !== FALSE) {
      $form_state->setErrorByName('message', 'You said hello.');
    }
  
  }


  public function submitForm(array &$form, FormStateInterface $form_state) {

    /*
      so i want to do two things
      i want to store all of this to the table
      and then i want to send an email notification to the admin
    */
    
    drupal_set_message('Okay.');

  }

}
