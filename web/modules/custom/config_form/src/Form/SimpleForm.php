<?php

namespace Drupal\config_form\Form;

use Drupal\Core\Ajax\AjaxResponse;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Database\Connection;
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
   * MessengerService .
   *
   * @var \Drupal\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * Instance of Request Stack.
   *
   * @var \Symfony\Component\HttpFoundation\RequestStack
   */
  protected $requestStack;

  /**
   * Database Connection Handler.
   *
   * @var \Drupal\Core\Database\Connection
   */
  protected $con;

  /**
   * Constructs a new SimpleForm object.
   *
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The messenger service.
   * @param \Symfony\Component\HttpFoundation\RequestStack $request_stack
   *   The request stack.
   * @param \Drupal\Core\Database\Connection $con
   *   The database connection handler.
   */
  public function __construct(MessengerInterface $messenger,
  RequestStack $request_stack,
  Connection $con) {
    $this->con = $con;
    $this->messenger = $messenger;
    $this->requestStack = $request_stack;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('messenger'),
      $container->get('request_stack'),
      $container->get('database'),
    );
  }

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
      '#type'   => 'markup',
      '#markup' => "<div class='success'></div>",
    ];

    $form['email'] = [
      '#title'       => $this->t('Email Address'),
      '#type'        => 'email',
      '#required'    => TRUE,
      '#size'        => 25,
      '#description' => $this->t('User Email Field'),
    ];

    $form['name'] = [
      '#title'       => $this->t('Name'),
      '#type'        => 'textfield',
      '#required'    => TRUE,
      '#size'        => 25,
      '#description' => $this->t('User Name Field'),
    ];

    $form['password'] = [
      '#type'     => 'password',
      '#title'    => $this->t('Password'),
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
   *   It contains all the fields in an associative array format.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   It holds the current state of the form along with the data.
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   *   Ajax Response.
   */
  public function submitData(array &$form, FormStateInterface $form_state) {
    $ajax_response = new AjaxResponse();
    $values = $form_state->getValues();
    $query = $this->con->insert('configform_example');
    // Specify the fields that the query will insert into.
    $query->fields([
      'email',
      'name',
      'password',
    ]);
    // Set the values of the fields we selected.
    $query->values([
      $values['email'],
      $values['name'],
      md5($values['password']),
    ]);
    // Execute the $query.
    $query->execute();
    $ajax_response->addCommand(new HtmlCommand('.success', 'Form Submitted Successfully'));
    return $ajax_response;
  }

  /**
   * {@inheritDoc}
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
