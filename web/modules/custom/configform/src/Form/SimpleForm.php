<?php

namespace Drupal\configform\Form;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Base form for taking user information.
 *
 * @internal
 */
class SimpleForm extends FormBase {

  /**
   * {@inheritDoc}
   */
  public function getFormId() {
     return 'config_form_id';
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {

     $form['email'] = array(
      '#title' => t('Email Address'),
      '#type' => 'email',
      '#required' => TRUE,
      '#size' => 25,
      '#description' => 'User Email Field'
     );

     $form['name'] = array(
       '#title' => t('Name'),
       '#type' => 'textfield',
       '#required' => TRUE,
       '#size' => 25,
       '#description' => 'User Name Field'
     );

      $form['password'] = array(
       '#type' => 'password',
       '#title' => t('Password'),
       '#required' => TRUE
      );

     $form['submit'] = array(
       '#title' => 'submit',
       '#type' => 'submit',
       '#value' => t('submit')
     );

     return $form;
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    \Drupal::messenger()->addMessage(t('Submitted Succesfully'));
  }
}
