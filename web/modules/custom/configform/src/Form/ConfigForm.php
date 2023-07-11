<?php

namespace Drupal\configform\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Form\ConfigFormBase;
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
   * Generating a unique form id.
   *
   * @return string
   *   Unique form id
   */
  public function getFormId() {
    return 'config_form';
  }

  /**
   * This function is used to declare the type of form.
   *
   * @return array
   *   It stores the config form
   */
  protected function getEditableConfigNames() {
    return [
      'config.settings',
    ];
  }

  /**
   * This function will faciliate building the form.
   *
   * @param array $form
   *   This array contains all the fields in an associative array format.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   This variable stores the current state of the form.
   *
   * @return array
   *   Array containing all the form field and data
   */
  public function buildForm(array $form, FormFormStateInterface $form_state) {
    $form['error'] = [
      '#type'      => 'markup',
      '#markup'    => '<div id="error-message"></div>',
    ];
    $form['FullName'] = [
      '#title'    => $this->t('Full Name'),
      '#type'     => 'textfield',
      '#required' => TRUE,
      '#size'     => 30,
    ];
    $form['PhoneNumber'] = [
      '#title'    => $this->t('Phone Number'),
      '#type'     => 'number',
      '#required' => TRUE,
      '#size'     => 10,
      '#suffix'   => '<span id="phone_error">',
    ];
    $form['Email'] = [
      '#title'    => $this->t('Email'),
      '#type'     => 'email',
      '#required' => TRUE,
    ];
    $form['Gender'] = [
      '#type'     => 'radios',
      '#title'    => $this->t('Gender'),
      '#options'  => [
        'male'    => $this->t('Male'),
        'female'  => $this->t('Female'),
      ],
    ];
    $form['Subscribe'] = [
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
   * This function is used to perform validation on the data entered by user.
   *
   * @param array $form
   *   This array contains all the form field in an associative array format.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   This array holds the current state of the form with input data.
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
   *   Ajax response based on the validation
   */
  public function submitDataAjax(array &$form, FormFormStateInterface $form_state) {

    // Initializing the response.
    $ajax_response = new AjaxResponse();

    // Calling the validate function to validate data.
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
   *   else return error message
   */
  public function validate(FormFormStateInterface $form_state) {

    // Storing the phone number in a variable.
    $phoneNumber = $form_state->getValue('PhoneNumber');

    // Storing the email id in a variable.
    $email = $form_state->getValue('Email');

    // Listing the available domains.
    $allowedDomains = ['gmail.com', 'yahoo.com', 'outlook.com'];

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
    elseif (!in_array($domain, $allowedDomains)) {

      return $this->t('Email ID should belong to gmail, yahoo or outlook');
    }
    elseif (!preg_match("/^[+]?[1-9][0-9]{9,14}$/", $phoneNumber) or strlen($phoneNumber) > 10) {

      return $this->t('Invalid Phone Number');
    }
    elseif (empty($form_state->getValue('FullName'))) {

      return $this->t('Gender should not be empty');
    }
    elseif (empty($form_state->getValue('Gender'))) {

      return $this->t('Gender should not be empty');
    }
    elseif (empty($form_state->getValue('Subscribe'))) {

      return $this->t('Please mention whether you want to subscribe or not');
    }
    $subscribe_value = $form_state->getValue('Subscribe');
    if ($subscribe_value === 'no' && empty($form_state->getValue('subscribe_message'))) {
      return $this->t('Please give a reason for not subscribing');
    }

    return TRUE;
  }

}
