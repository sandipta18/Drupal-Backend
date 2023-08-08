<?php

namespace Drupal\menu_api\Form;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Ajax\CssCommand;
use Drupal\Core\Ajax\HtmlCommand;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Ajax\AjaxResponse as AjaxAjaxResponse;
use Drupal\Core\Cache\CacheTagsInvalidatorInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configure menu_api settings for this site.
 */
class MovieForm extends ConfigFormBase {

  /**
   * Cache Tag Invalidator Service.
   *
   * @var \Drupal\Core\Cache\CacheTagsInvalidatorInterface
   */
  protected $cache_tags_invalidator;

  /**
   * Constructs a MovieForm Object.
   *
   * @param CacheTagsInvalidatorInterface $cache_tags_invalidator
   *   Holds the CacheTagsInvalidatorInterface service.
   */
  public function __construct(CacheTagsInvalidatorInterface $cache_tags_invalidator) {
    $this->cache_tags_invalidator = $cache_tags_invalidator;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('cache_tags.invalidator'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'menu_api_movie';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['menu_api.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['budget'] = [
      '#type'  => 'number',
      '#title' => $this->t('Enter Budget'),
      '#default_value' => $this->config('menu_api.settings')->get('budget'),
    ];
    $form['action']['submit'] = [
      '#type'  => 'submit',
      '#value' => $this->t('Submit'),
      '#ajax'  => [
        'callback' => '::submitFormAjax',
      ],
    ];
    $form['error'] = [
      '#type'      => 'markup',
      '#markup'    => '<div id="error"></div>',
    ];
    $form['success'] = [
      '#type'      => 'markup',
      '#markup'    => '<div id="success"></div>',
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $this->messenger()->addMessage($this->validate($form_state));
  }

  /**
   * Custom ajax form submit handler.
   *
   * @param array $form
   *   Holds all the form fields in an associative array format.
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Holds the current state of the form.
   *
   * @return \Drupal\Core\Ajax\AjaxResponse
   *   Ajax Response based on input.
   */
  public function submitFormAjax(array $form, FormStateInterface $form_state) {
    $response = new AjaxAjaxResponse();
    $output = $this->validate($form_state);
    // From the validate function we are getting either boolean value TRUE, or
    // a string containing error message. So we have to check explicitly here
    // whether the value returned in TRUE.
    if ($output === TRUE) {
      $message = $this->t('Thanks for submitting the form');
      $response->addCommand(new CssCommand('#success', ['color' => 'green']));
      $response->addCommand(new HtmlCommand('#success', $message));
      $response->addCommand(new CssCommand('#error', ['display' => 'none']));
    }
    else {
      $response->addCommand(new CssCommand('#error', ['color' => 'red']));
      $response->addCommand(new HtmlCommand('#error', $output));
    }
    return $response;
  }

  /**
   * Ajax validation for form data.
   *
   * @param \Drupal\Core\Form\FormStateInterface $form_state
   *   Stores the current form state.
   *
   * @return mixed
   *   If validation is succesful returns boolean value else returns error
   *   message.
   */
  public function validate(FormStateInterface $form_state) {
    $budget = $form_state->getValue('budget');
    return is_numeric($budget) ? TRUE : $this->t('Enter Numeric data');
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $this->config('menu_api.settings')
      ->set('budget', $form_state->getValue('budget'))
      ->save();
    $this->cache_tags_invalidator->invalidateTags(['node_list']);
    parent::submitForm($form, $form_state);
  }

}
