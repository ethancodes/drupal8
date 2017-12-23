<?php

namespace Drupal\genericcontactform\Form;

use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Mail\MailManagerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Language\LanguageManagerInterface;

class GenericContactForm extends FormBase {

  protected $mailManager;
  protected $languageManager;

  public function __construct(MailManagerInterface $mail_manager, LanguageManagerInterface $language_manager) {
    $this->mailManager = $mail_manager;
    $this->languageManager = $language_manager;
  }

  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('plugin.manager.mail'),
      $container->get('language_manager')
    );
  }



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
    
    $fsname = $form_state->getValue('name');
    $fsemail = $form_state->getValue('email');
    $fscolor = $form_state->getValue('color');
    $fsmessage = $form_state->getValue('message');
    
    
    //
    // store all of this in the table
    $connection = \Drupal::database();
    $result = $connection->insert('genericcontactform')
      ->fields([
        'name' => $fsname,
        'email' => $fsemail,
        'color' => $fscolor,
        'message' => $fsmessage
        ])
      ->execute();
    
    
    //
    // now send an email notification
    $module = 'genericcontactform';
    $key = 'form_submit_notification';
    
    $admin_email = $this->config('system.site')->get('mail');
    $from = $fsemail;

    $params = $form_state->getValues();
    $language_code = $this->languageManager->getDefaultLanguage()->getId();
    // fwiw, usually ^this returns 'en' but hey, whatever
    $send_now = TRUE;
    $result = $this->mailManager->mail($module, $key, $to, $language_code, $params, $from, $send_now);
    
    
    //
    // and we're done
    drupal_set_message('Thanks!');

  }

}
