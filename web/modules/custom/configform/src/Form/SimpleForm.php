<?php

namespace Drupal\configform\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Form\FormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Messenger\MessengerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\RequestStack;

/**
 * Implements simpleform.
 */
class SimpleForm extends FormBase {


  /**
   * Instance of MessengerInterface .
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * Constructs a new SimpleForm object.
   *
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger service.
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   The request stack.
   */
  public function __construct(MessengerInterface $messenger, RequestStack $request_stack) {
    $this->messenger = $messenger;
    $this->requestStack = $request_stack;
  }

  /**
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   Dependancy injector Interface Container.
   *
   * @return object
   *   Returns newly created instance of the class.
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('messenger'),
      $container->get('request_stack')
    );
  }

  /**
   * Generating Unique Form ID.
   *
   * @return string
   *   Unique Form ID.
   */
  public function getFormId() {
    return 'config_form_id';
  }

  /**
   * This function will faciliate building the form.
   *
   * @param array $form
   *   It contains all the fields in an associative array format.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Holds the current state of the form.
   *
   * @return array
   *   Array containg form data along with fields.
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['element'] = [
      '#type'   => 'markup',
      '#markup' => "<div class='success'></div>",
    ];

    $form['email'] = [
      '#title'       => t('Email Address'),
      '#type'        => 'email',
      '#required'    => TRUE,
      '#size'        => 25,
      '#description' => 'User Email Field',
    ];

    $form['name'] = [
      '#title'       => t('Name'),
      '#type'        => 'textfield',
      '#required'    => TRUE,
      '#size'        => 25,
      '#description' => 'User Name Field',
    ];

    $form['password'] = [
      '#type'     => 'password',
      '#title'    => t('Password'),
      '#required' => TRUE,
    ];

    $form['submit'] = [
      '#title'      => 'submit',
      '#type'       => 'submit',
      '#value'      => $this->t('submit'),
      '#ajax'       => [
        'callback'  => '::submitData',
      ],
    ];

    return $form;
  }

  /**
   * This function facilitates submissions of form without browser refresh .
   *
   * @param array $form
   *   It conains all the fields in an associative array format.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   It holds the current state of the form along with the data.
   *
   * @return Response
   *   Ajax Response.
   */
  public function submitData(array &$form, FormStateInterface $form_state) {
    $ajax_response = new AjaxResponse();
    $values = $form_state->getValues();
    \Drupal::database()->insert('configform_example')->fields([
      'email'    => $values['email'],
      'name'     => $values['name'],
      'password' => md5($values['password']),
    ])->execute();
    $ajax_response->addCommand(new HtmlCommand('.success', 'Form Submitted Successfully'));
    return $ajax_response;
  }

  /**
   * This functions stores the data inside a database.
   *
   * @param array $form
   *   It contains the fields in an associative array format.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   It holds the current state of the form.
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    // Checking whether the form is being submitted with ajax previously.
    $request = $this->requestStack->getCurrentRequest();
    if (!$request->isXmlHttpRequest()) {
      // This is the regular form submission,so saving the data to the database.
      $this->submitData($form, $form_state);
      $this->messenger->addMessage($this->t('Form submitted successfully'));
    }
  }

}
