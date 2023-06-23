<?php

namespace Drupal\configform\Form;

use Drupal\Core\Form\FormBase;
use Drupall\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Entity\Message;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface as FormFormStateInterface;

class ConfigForm extends ConfigFormBase {


  public function getFormId()
  {
    return 'config_form';
  }

  /**
   * {@inheritDoc}
   */
  protected function getEditableConfigNames() {
     return [
      'config.settings'
     ];
  }


  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormFormStateInterface $form_state) {

     $form = parent::buildForm($form,$form_state);
     $config = $this->config('config.settings');
      $form['FullName'] = array(
       '#title' => t('Full Name'),
       '#type' => 'textfield',
       '#required' => TRUE,
       '#size' => 30
     );

     $form['PhoneNumber'] = array(
       '#title' => t('Phone Number'),
       '#type' => 'tel',
       '#required' => TRUE,
       '#size' => 12
     );

     $form['Email'] = array(
       '#title' => t('Email'),
       '#type' => 'email',
       '#required' => TRUE
     );

     $form['Gender'] = array(
       '#type' => 'radios',
       '#title' => t('Gender'),
       '#options' => array(
         t('Male'),
         t('Female')
       )
     );

     return $form;
  }

  public function validateForm(array &$form, FormFormStateInterface $form_state) {
    $formField = $form_state->getValues();
    $phoneNumber = trim($formField['PhoneNumber']);
    $email = trim($formField['Email']);
    $allowedDomains = [
     'gmail.com',
     'yahoo.com',
     'outlook.com'
    ];
    if(filter_var($email,FILTER_VALIDATE_EMAIL)) {
      $parts = explode('@',$email);
      $domain = array_pop($parts);
      if(!in_array($domain,$allowedDomains)) {
        $form_state->setErrorByName('Email',$this->t('Allowed domains are gmail,yahoo and outlook'));
      }
    }

    if(!preg_match("/^[+]?[1-9][0-9]{9,14}$/",$phoneNumber) or strlen($phoneNumber) > 10) {
      $form_state->setErrorByName($phoneNumber,$this->t('Enter Valid Number'));
    }

  }

  /**
   *{@inheritDoc}
   */
  public function submitForm(array &$form, FormFormStateInterface $form_state) {
    $config = $this->config('config.settings');
    $config->set('custom.name',$form_state->getValue('FullName'));
    $config->set('custom.email',$form_state->getValue('email'));
    $config->set('custom.number',$form_state->getValue('PhoneNumber'));
    $config->set('custom.gender', $form_state->getValue('Gender'));
    $config->save();
    \Drupal::messenger()->addMessage(t('Submitted Succesfully'));
    return parent::submitForm($form,$form_state);


  }

}

