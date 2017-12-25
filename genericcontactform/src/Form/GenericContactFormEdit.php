<?php

namespace Drupal\genericcontactform\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\genericcontactform\Controller\GenericContactFormController;
use Symfony\Component\HttpKernel\Exception\AccessDeniedHttpException;


class GenericContactFormEdit extends FormBase {


  public function getFormId() {
    return 'genericcontactform_adminedit';
  }


  public function buildForm(array $form, FormStateInterface $form_state) {

    $pathinfo = \Drupal::request()->getpathInfo();
    $pathinfo = explode('/', $pathinfo);
    $sid = end($pathinfo);
    $sub = GenericContactFormController::loadsub($sid);
    
    if ($sub === FALSE) {
      throw new AccessDeniedHttpException();
    }
    
    
    $form['name'] = [
      '#type' => 'textfield',
      '#title' => 'Name',
      '#required' => TRUE,
      '#default_value' => $sub->name
    ];
    
    $form['email'] = [
      '#type' => 'email',
      '#title' => 'Email',
      '#required' => TRUE,
      '#default_value' => $sub->email
    ];
    
    $form['color'] = [
      '#type' => 'select',
      '#title' => 'Favorite color?',
      '#options' => [
        'red' => 'Red',
        'green' => 'Green',
        'blue' => 'Blue'
      ],
      '#empty_option' => '- None -',
      '#empty_value' => 'none',
      '#default_value' => $sub->color
    ];
    
    $form['message'] = [
      '#type' => 'textarea',
      '#title' => 'Message',
      '#required' => TRUE,
      '#default_value' => $sub->message
    ];
    
    $form['sid'] = [
      '#type' => 'hidden',
      '#value' => $sid
    ];

    $form['actions'] = [
      '#type' => 'actions',
    ];

    $form['actions']['submit'] = [
      '#type' => 'submit',
      '#value' => 'Submit',
    ];
    
    $form['actions']['delete'] = [
      '#type' => 'submit',
      '#value' => 'Delete',
      '#attributes' => [
        'onclick' => 'return confirm("Are you sure you want to delete this?");'
      ]
    ];

    return $form;
  }


  public function validateForm(array &$form, FormStateInterface $form_state) {  
  
  }


  public function submitForm(array &$form, FormStateInterface $form_state) {

    $sid = $form_state->getValue('sid');
    $fsname = $form_state->getValue('name');
    $fsemail = $form_state->getValue('email');
    $fscolor = $form_state->getValue('color');
    $fsmessage = $form_state->getValue('message');

    $connection = \Drupal::database();
    
    $op = $form_state->getValue('op');

    if ($op == 'Delete') {
    
      $foo = $connection->delete('genericcontactform')
        ->condition('sid', $sid)
        ->execute();
      drupal_set_message('Deleted.');
      $form_state->setRedirect('genericcontactform_adminlist');
    
    } else {
    
      $foo = $connection->update('genericcontactform')
        ->fields([
          'name' => $fsname,
          'email' => $fsemail,
          'color' => $fscolor,
          'message' => $fsmessage
          ])
        ->condition('sid', $sid)
        ->execute();
      drupal_set_message('Updated!');
    
    }

  }

}
