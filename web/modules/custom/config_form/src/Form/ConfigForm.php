<?php

namespace Drupal\config_form\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Config form to take user information .
 *
 * @internal
 */
class ConfigForm extends ConfigFormBase {

  /**
   * Class Property of messenger interface.
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * The injected dependancy is assigned to the corresponding class propery.
   *
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   Class property of messenger interface.
   */
  public function __construct(MessengerInterface $messenger) {
    $this->messenger = $messenger;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('messenger')
    );
  }

  /**
   * {@inheritDoc}
   */
  public function getFormId() {
    return 'config_form';
  }

  /**
   * {@inheritDoc}
   */
  protected function getEditableConfigNames() {
    return [
      'config_form.settings',
    ];
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    // Get the configuration values from the YAML file.
    $config = $this->config('config_form.settings');
    $config_values = $config->get();

    $form['error'] = [
      '#type'      => 'markup',
      '#markup'    => '<div id="error-message"></div>',
    ];
    $form['full_name'] = [
      '#title'    => $this->t('Full Name'),
      '#type'     => 'textfield',
      '#required' => TRUE,
      '#size'     => 30,
      '#default_value' => $config_values['name'],
    ];
    $form['phone_number'] = [
      '#title'    => $this->t('Phone Number'),
      '#type'     => 'number',
      '#required' => TRUE,
      '#size'     => 10,
      '#suffix'   => '<span id="phone_error">',
      '#default_value' => $config_values['phone_number'],
    ];
    $form['email'] = [
      '#title'    => $this->t('Email'),
      '#type'     => 'email',
      '#required' => TRUE,
      '#default_value' => $config_values['email'],
    ];
    $form['gender'] = [
      '#type'     => 'radios',
      '#title'    => $this->t('Gender'),
      '#options'  => [
        'male'    => $this->t('Male'),
        'female'  => $this->t('Female'),
      ],
      '#required' => TRUE,
      '#default_value' => $config_values['gender'],
    ];
    $form['subscribe'] = [
      '#type'     => 'radios',
      '#title'    => $this->t('Subscribe'),
      '#required' => TRUE,
      '#options'  => [
        'yes'     => $this->t('Yes'),
        'no'      => $this->t('No'),
      ],
      '#attributes' => [
        'id' => 'conditional_field',
      ],
      '#default_value' => $config_values['subscription'],
    ];
    $form['subscribe_message'] = [
      '#type'     => 'textfield',
      '#title'    => $this->t('Why Not'),
      '#states'   => [
        'visible' => [
          ':input[id="conditional_field"]' => ['value' => 'no'],
        ],
      ],
      '#default_value' => $config_values['subscription_message'],
    ];
    $form['action']['submit'] = [
      '#type'      => 'submit',
      '#value'     => $this->t('submit'),
      '#ajax'      => [
        'callback' => '::submitDataAjax',
      ],
    ];
    $form['success'] = [
      '#type'     => 'markup',
      '#markup'   => '<div class="success"></div>',
    ];
    return $form;
  }

  /**
   * {@inheritDoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $this->messenger()->addMessage($this->validate($form_state));
  }

  /**
   * This function is used to facilitate form submission without page refresh .
   *
   * @param array $form
   *   This array contains all the form field in an associative array format.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   This array holds the current state of the form with input data.
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   *   Ajax response based on the validation.
   */
  public function submitDataAjax(array &$form, FormStateInterface $form_state) {
    $ajax_response = new AjaxResponse();
    $output = $this->validate($form_state);
    if ($output === TRUE) {
      // If validation is succesful showing the success message.
      $message = $this->t('Thanks for submitting the form');
      $ajax_response->addCommand(new CssCommand('.success', ['color' => 'green']));
      $ajax_response->addCommand(new HtmlCommand('.success', $message));
      $ajax_response->addCommand(new CssCommand('#error-message', ['display' => 'none']));
    }
    else {
      // If validation failed showing the error message.
      $ajax_response->addCommand(new CssCommand('#error-message', ['color' => 'red']));
      $ajax_response->addCommand(new HtmlCommand('#error-message', $output));
    }
    // Deleting all messages after process is completed.
    $this->messenger()->deleteAll();
    return $ajax_response;
  }

  /**
   * This function is used to facilitate the validation of data entered by user.
   *
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Holds the form state along with the input data.
   *
   * @return mixed
   *   On succesful validation return boolean value
   *   else return error message.
   */
  public function validate(FormStateInterface $form_state) {
    $phone_number = $form_state->getValue('phone_number');
    $email = $form_state->getValue('email');
    $config = $this->config('config_form.settings');
    $config_values = $config->get();
    $allowed_domains = $config_values['allowed_domains'];
    // Exploding the array so now we have the data in this format
    // if email = 'example.com'
    // Array ( [0] => example [1] => gmail.com )
    $parts = explode('@', $email);
    // Pop function removes the last element from an array.
    $domain = array_pop($parts);
    $error_message = '';
    switch (true) {
      case (!filter_var($email, FILTER_VALIDATE_EMAIL)):
        $error_message = 'Invalid Email Format';
        break;
      case (!in_array($domain, $allowed_domains)):
        $error_message = 'Email ID should belong to gmail, yahoo or outlook';
        break;
      case (!preg_match("/^[+]?[1-9][0-9]{9,14}$/", $phone_number) or strlen($phone_number) > 10):
        $error_message = 'Invalid Phone Number';
        break;
      case (empty($form_state->getValue('full_name'))):
        $error_message = 'Name should not be empty';
        break;
      case (empty($form_state->getValue('gender'))):
        $error_message = 'Gender should not be empty';
        break;
      case (empty($form_state->getValue('subscribe'))):
        $error_message = 'Please mention whether you want to subscribe or not';
        break;
      case ($form_state->getValue('subscribe') === 'no' && empty($form_state->getValue('subscribe_message'))):
        $error_message = 'Get updates over email?';
        break;
      default:
        return TRUE;
    }
    if ($error_message) {
      return $this->t($error_message);
    }
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('config_form.settings');
    $config->set('name', $form_state->getValue('full_name'));
    $config->set('phone_number', $form_state->getValue('phone_number'));
    $config->set('email', $form_state->getValue('email'));
    $config->set('gender', $form_state->getValue('gender'));
    $config->set('subscription', $form_state->getValue('subscribe'));
    $config->set('subscription_message', $form_state->getValue('subscribe_message'));
    $config->save();
    parent::submitForm($form, $form_state);
  }

}
