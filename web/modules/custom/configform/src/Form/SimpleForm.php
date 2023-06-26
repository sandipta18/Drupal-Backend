<?php

namespace Drupal\configform\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;

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
    $form['element'] = [
      '#type' => 'markup',
      '#markup' => "<div class='success'></div>"
    ];

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
       '#value' => $this->t('submit'),
       '#ajax' => [
          'callback' => '::submitData',
       ]
     );

     return $form;
  }


  /**
   * This function facilitates submissions of form without browser refresh .
   *
   * @param array $form
   * @param FormStateInterface $form_state
   *
   * @return array
   */
  public function submitData(array &$form , FormStateInterface $form_state) {
    $ajax_response = new AjaxResponse();
    $ajax_response->addCommand(new HtmlCommand('.success','Form Submitted Succesfully'));
    return ($ajax_response);
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    \Drupal::messenger()->addMessage(t('Submitted Succesfully'));
    $values = $form_state->getValues();
    \Drupal::database()->insert('configform_example')->fields([
      'email' => $values['email'],
      'name' =>  $values['name'],
      'password' => md5($values['password'])
    ])->execute();
  }

}
