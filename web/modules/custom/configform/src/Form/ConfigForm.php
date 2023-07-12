<?php

namespace Drupal\configform\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Form\FormStateInterface as FormFormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface as DependencyInjectionContainerInterface;

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
  public static function create(DependencyInjectionContainerInterface $container) {
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
      'config.settings',
    ];
  }

  /**
   * {@inheritDoc}
   */
  public function buildForm(array $form, FormFormStateInterface $form_state) {
    $form['error'] = [
      '#type'      => 'markup',
      '#markup'    => '<div id="error-message"></div>',
    ];
    $form['full_name'] = [
      '#title'    => $this->t('Full Name'),
      '#type'     => 'textfield',
      '#required' => TRUE,
      '#size'     => 30,
    ];
    $form['phone_number'] = [
      '#title'    => $this->t('Phone Number'),
      '#type'     => 'number',
      '#required' => TRUE,
      '#size'     => 10,
      '#suffix'   => '<span id="phone_error">',
    ];
    $form['email'] = [
      '#title'    => $this->t('Email'),
      '#type'     => 'email',
      '#required' => TRUE,
    ];
    $form['gender'] = [
      '#type'     => 'radios',
      '#title'    => $this->t('Gender'),
      '#options'  => [
        'male'    => $this->t('Male'),
        'female'  => $this->t('Female'),
      ],
      '#required' => TRUE,
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
      '#default_value' => 'yes',
    ];
    $form['subscribe_message'] = [
      '#type'     => 'textfield',
      '#title'    => $this->t('Why Not'),
      '#states'   => [
        'visible' => [
          ':input[id="conditional_field"]' => ['value' => 'no'],
        ],
      ],
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
  public function validateForm(array &$form, FormFormStateInterface $form_state) {
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
   * @return Response
   *   Ajax response based on the validation.
   */
  public function submitDataAjax(array &$form, FormFormStateInterface $form_state) {
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
      // If validatiion failed showing the error message.
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
  public function validate(FormFormStateInterface $form_state) {
    $phone_number = $form_state->getValue('phone_number');
    $email = $form_state->getValue('email');
    $allowed_domains = ['gmail.com', 'yahoo.com', 'outlook.com'];
    // Exploding the array so now we have the data in this format
    // if email = 'example.com'
    // Array ( [0] => example [1] => gmail.com )
    $parts = explode('@', $email);
    // Pop function removes the last element from an array.
    $domain = array_pop($parts);
    // Validating based on conditions.
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

      return $this->t('Invalid Email Format');
    }
    elseif (!in_array($domain, $allowed_domains)) {

      return $this->t('Email ID should belong to gmail, yahoo or outlook');
    }
    elseif (!preg_match("/^[+]?[1-9][0-9]{9,14}$/", $phone_number) or strlen($phone_number) > 10) {

      return $this->t('Invalid Phone Number');
    }
    elseif (empty($form_state->getValue('full_name'))) {

      return $this->t('Gender should not be empty');
    }
    elseif (empty($form_state->getValue('gender'))) {

      return $this->t('Gender should not be empty');
    }
    elseif (empty($form_state->getValue('subscribe'))) {

      return $this->t('Please mention whether you want to subscribe or not');
    }
    $subscribe_value = $form_state->getValue('subscribe');
    if ($subscribe_value === 'no' && empty($form_state->getValue('subscribe_message'))) {
      return $this->t('Please give a reason for not subscribing');
    }

    return TRUE;
  }

  /**
   * {@inheritDoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $config = $this->config('config.settings');
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
