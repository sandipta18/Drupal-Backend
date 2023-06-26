<?php

namespace Drupal\configform\Form;

use Drupal\Core\Form\FormBase;
use Drupall\Core\Form\FormStateInterface;
use Drupal\Core\Database\Database;
use Drupal\Core\Entity\Message;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Form\FormStateInterface as FormFormStateInterface;

/**
 * Config form to take user information .
 *
 * @internal
 */
class ConfigForm extends ConfigFormBase
{

  /**
   * This variable will be used to display the error message
   *
   * @var string $errorMessage
   */
  private string $errorMessage = "";

  /**
   * This variable will be used to mark the validation of phone number
   *
   * @var boolean $phoneValidate
   */
  private bool $phoneValidate = TRUE;

  /**
   * This variable will be used to mark the validation of Email ID
   *
   * @var boolean $emailValidate
   */
  public bool $emailValidate = TRUE;

  /**
   * {@inheritDoc}
   */
  public function getFormId()
  {
    return 'config_form';
  }

  /**
   * {@inheritDoc}
   */
  protected function getEditableConfigNames()
  {
    return [
      'config.settings'
    ];
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormFormStateInterface $form_state)
  {
    $config = $this->config('config.settings');
    $form['error'] = array(
      '#type' => 'markup',
      '#markup' => '<div id="error-message"></div>',
    );
    $form['FullName'] = array(
      '#title' => t('Full Name'),
      '#type' => 'textfield',
      '#required' => TRUE,
      '#size' => 30
    );
    $form['PhoneNumber'] = array(
      '#title' => t('Phone Number'),
      '#type' => 'number',
      '#required' => TRUE,
      '#size' => 10,
      '#ajax' => array(
        'callback' => [$this, 'validateNumberCallback'],
        'event' => 'change'
      )
    );
    $form['Email'] = array(
      '#title' => t('Email'),
      '#type' => 'email',
      '#required' => TRUE,
      '#ajax' => [
        'callback' => [$this, 'validateEmailCallback'],
        'event' => 'change'
      ]
    );
    $form['Gender'] = array(
      '#type' => 'radios',
      '#title' => t('Gender'),
      '#options' => array(
        t('Male'),
        t('Female')
      )
    );
    $form['Submit'] = array(
      '#title' => 'submit',
      '#type' => 'submit',
      '#value' => $this->t('Submit'),
      '#ajax' => array(
        'callback' => '::submitData',
      )
    );
    $form['success'] = array(
      '#type' => 'markup',
      '#markup' => '<div class="success"></div>',
    );
    return $form;
  }

  /**
   * This function is used to facilitate client side validation of the Email ID
   * entered by user .
   *
   * @param array $form
   * @param FormFormStateInterface $form_state
   *
   * @return array
   */
  public function validateEmailCallback(array &$form, FormFormStateInterface $form_state)
  {
    $response = new AjaxResponse();
    $email = $form_state->getValue('Email');
    $allowedDomains = ['gmail.com', 'yahoo.com', 'outlook.com'];
    if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
      $parts = explode('@', $email);
      $domain = array_pop($parts);
      if (!in_array($domain, $allowedDomains)) {
        $response->addCommand(new HtmlCommand('.success', 'Allowed domains are gmail, yahoo, and outlook'));
        $this->errorMessage = 'Allowed domains are gmail, yahoo, and outlook';
        $this->emailValidate = FALSE;
      }
    } else {
      $response->addCommand(new HtmlCommand('#error-message', 'Invalid email format'));
      $this->errorMessage = 'Invalid Email Format';
      $this->emailValidate = FALSE;
    }
    return $response;
  }

  /**
   * This function is used to facilitate cliend side validation of the Phone
   * Number entered by user
   *
   * @param array $form
   * @param FormFormStateInterface $form_state
   *
   * @return array
   */
  public function validateNumberCallback(array &$form, FormFormStateInterface $form_state)
  {
    $response = new AjaxResponse();
    $number = $form_state->getValue('PhoneNumber');
    if (!preg_match("/^[+]?[1-9][0-9]{9,14}$/", $number) or strlen($number) > 10) {
      $response->addCommand(new HtmlCommand('#error-message', 'Invalid Phone Number Format'));
      $this->phoneValidate =  FALSE;
      $this->errorMessage = 'Invalid Phone Number';
    }
    return $response;
  }

  /**
   * This function is used to facilitate form submission without page refresh .
   *
   * @param array $form
   * @param FormFormStateInterface $form_state
   *
   * @return array
   */
  public function submitData(array &$form, FormFormStateInterface $form_state)
  {
    $ajax_response = new AjaxResponse();
    $ajax_response->addCommand(new HtmlCommand('.success', 'Form Submitted Succesfully thanks'));
    return $ajax_response;
  }

  /**
   * {@inheritDoc}
   */
  public function validateForm(array &$form, FormFormStateInterface $form_state)
  {
    $validateEmail = $this->validateEmailCallback($form, $form_state);
    $validatePhone = $this->validateNumberCallback($form, $form_state);
    if ($this->emailValidate == FALSE) {
      $form_state->setErrorByName('Email', $this->t('Allowed domains are gmail,yahoo and outlook'));
    }
    if ($this->phoneValidate == FALSE) {
      $form_state->setErrorByName('PhoneNumber', $this->t('Invalid Phone Number'));
    }
  }

  /**
   *{@inheritDoc}
   */
  public function submitForm(array &$form, FormFormStateInterface $form_state)
  {
    $config = $this->config('config.settings');
    $config->set('custom.name', $form_state->getValue('FullName'));
    $config->set('custom.email', $form_state->getValue('Email'));
    $config->set('custom.number', $form_state->getValue('PhoneNumber'));
    $config->set('custom.gender', $form_state->getValue('Gender'));
    \Drupal::messenger()->addMessage($this->t('Form Submitted Succesfully'));
    $config->save();
  }
}
