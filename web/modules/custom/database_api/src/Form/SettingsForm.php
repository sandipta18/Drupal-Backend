<?php

namespace Drupal\database_api\Form;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Messenger\MessengerInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Configure Database api settings for this site.
 */
class SettingsForm extends ConfigFormBase {

  /**
   * Entity Manager Interface object.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The Messenger Service.
   *
   * @var \Drupall\Core\Messenger\MessengerInterface
   */
  protected $messenger;

  /**
   * Constructs new EntityManager object.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entityTypeManager
   *   Entity Manager Interface object.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The Messenger Service.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager,
  MessengerInterface $messenger) {
    $this->entityTypeManager = $entityTypeManager;
    $this->messenger = $messenger;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('messenger'),
    );
  }

  /**
   * {@inheritdoc}
   */
  public function getFormId() {
    return 'database_api_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames() {
    return ['database_api.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state) {
    $form['term'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Enter Term'),
      '#default_value' => $this->config('database_api.settings')->get('term'),
    ];
    $form['submit'] = [
      '#type'  => 'submit',
      '#value' => $this->t('Get Details'),
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function validateForm(array &$form, FormStateInterface $form_state) {
    $term_name = $form_state->getValue('term');
    $term = $this->entityTypeManager
      ->getStorage('taxonomy_term')
      ->loadByProperties(['name' => $term_name]);
    if (empty($term)) {
      $form_state->setErrorByName('term', $this->t('Taxonomy term doesnot exists'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $term_name = $form_state->getValue('term');
    // Load the terms based on the name provided.
    $terms = $this->entityTypeManager
      ->getStorage('taxonomy_term')
      ->loadByProperties(['name' => $term_name]);
    if (!empty($terms)) {
      // If there are multiple terms with the same name, get the first one.
      $term = reset($terms);
      $term_id = $term->id();
      $term_uuid = $term->uuid();
      $term_info = $this->entityTypeManager->getStorage('node')
        ->loadByProperties(['field_tags' => $term_id]);
      foreach ($term_info as $node) {
        $term_name = $node->getTitle();
        $term_url = $node->toUrl();
        $term_url = Link::fromTextAndUrl($term_name, $term_url)->toString();
      }
      $output = [
        $this->t('The ID of the Term: @id', ['@id' => $term_id]),
        $this->t('UUID of the Term: @uuid', ['@uuid' => $term_uuid]),
        $this->t('Node Title: @term_name', ['@term_name' => $term_name]),
        $this->t('Node Url is : @term_url', ['@term_url' => $term_url]),
      ];
      foreach ($output as $data) {
        $this->messenger->addMessage($data);
      }
      parent::submitForm($form, $form_state);
    }
    else {
      // Handle the case when the term does not exist.
      $this->messenger->addMessage($this->t('The taxonomy term "%term" does not exist.', ['%term' => $term_name]), 'error');
    }
  }

}
