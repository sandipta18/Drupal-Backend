<?php

namespace Drupal\database_api\Form;

use Drupal\Core\Entity\EntityFieldManager;
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
   * The Entity Type Manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * The Entity Field Manager.
   *
   * @var \Drupal\Core\Entity\EntityFieldManager
   */
  protected $entityFieldManager;

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
   *   The Entity Manager.
   * @param \Drupal\Core\Messenger\MessengerInterface $messenger
   *   The Messenger Service.
   * @param \Drupal\Core\Entity\EntityFieldManager $entityFieldManager
   *   The Entity Field Manager.
   */
  public function __construct(EntityTypeManagerInterface $entityTypeManager,
  MessengerInterface $messenger,
  EntityFieldManager $entityFieldManager) {
    $this->entityTypeManager = $entityTypeManager;
    $this->messenger = $messenger;
    $this->entityFieldManager = $entityFieldManager;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('entity_type.manager'),
      $container->get('messenger'),
      $container->get('entity_field.manager'),
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
      '#type' => 'entity_autocomplete',
      '#target_type' => 'taxonomy_term',
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
      ->loadByProperties(['tid' => $term_name]);
    if (empty($term)) {
      $form_state->setErrorByName('term', $this->t('Taxonomy term doesnot exists'));
    }
  }

  /**
   * {@inheritdoc}
   */
  public function submitForm(array &$form, FormStateInterface $form_state) {
    $term_details = [];
    $term_parent_details = [];
    $term_name = $form_state->getValue('term');
    // Load the terms based on the name provided.
    $terms = $this->entityTypeManager
      ->getStorage('taxonomy_term')
      ->loadByProperties(['tid' => $term_name]);
    if (!empty($terms)) {
      foreach ($terms as $term) {
        $term_id = $term->id();
        $term_uuid = $term->uuid();
        $term_details = [
          $this->t('Term ID: @type', ['@type' => $term_id]),
          $this->t('The UUID of the Term: @id', ['@id' => $term_uuid]),
        ];
        // Get a list of all content types.
        $content_types = $this->entityTypeManager
          ->getStorage('node_type')
          ->loadMultiple();

        foreach ($content_types as $type_id => $content_type) {
          $type_label = $content_type->label();

          // Load field definitions for the current content type.
          $field_definitions = $this->entityFieldManager->getFieldDefinitions('node', $type_id);
          foreach ($field_definitions as $field_name => $field_definition) {
            if ($field_definition->getType() === 'entity_reference' && $field_definition->getFieldStorageDefinition()->getSetting('target_type') === 'taxonomy_term') {
              // Load nodes associated with the term and the current field.
              $node_storage = $this->entityTypeManager->getStorage('node');
              $query = $node_storage->getQuery()
                ->condition($field_name, $term_id)
              // Only published nodes.
                ->condition('status', 1)
                ->accessCheck(TRUE);
              $node_ids = $query->execute();
              $nodes = $node_storage->loadMultiple($node_ids);
              foreach ($nodes as $node) {
                $term_name = $node->getTitle();
                $term_url = $node->toUrl();
                $term_url = Link::fromTextAndUrl($term_name, $term_url)->toString();
                $term_parent_details[] = [
                  $this->t('Content Type: @type', ['@type' => $type_label]),
                  $this->t('Node Title: @term_name', ['@term_name' => $term_name]),
                  $this->t('Node Url is: @term_url', ['@term_url' => $term_url]),
                ];
              }
            }
          }
        }
      }
      foreach ($term_details as $data) {
        $this->messenger->addMessage($data);
      }
      if ($term_parent_details) {
        foreach ($term_parent_details as $details) {
          foreach ($details as $data) {
            $this->messenger->addMessage($data);
          }
        }
      }
      parent::submitForm($form, $form_state);
    }
    else {
      // Handle the case when the term does not exist.
      $this->messenger->addMessage($this->t('The taxonomy term "%term" does not exist.', ['%term' => $term_name]), 'error');
    }

  }

}
