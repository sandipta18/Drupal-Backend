<?php

namespace Drupal\database_api\Form;

use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Link;
use Drupal\Core\Url;

/**
 * Configure Database api settings for this site.
 */
class SettingsForm extends ConfigFormBase {

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
    $term = \Drupal::entityTypeManager()
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
    $terms = \Drupal::entityTypeManager()
      ->getStorage('taxonomy_term')
      ->loadByProperties(['name' => $term_name]);
    if (!empty($terms)) {
      // If there are multiple terms with the same name, get the first one.
      $term = reset($terms);
      $term_id = $term->id();
      $term_uuid = $term->uuid();
      // Get nodes using the term by querying all fields and all content types.
      $query = \Drupal::entityQuery('node')
      // Published nodes only.
        ->condition('status', 1);
      $or_group = $query->orConditionGroup();
      // Load all fields of all content types and check if they reference taxonomy terms.
      $fieldStorageDefinitions = \Drupal::entityTypeManager()->getStorage('field_storage_config')->loadMultiple();
      foreach ($fieldStorageDefinitions as $fieldStorageDefinition) {
        if ($fieldStorageDefinition instanceof FieldStorageDefinitionInterface) {
          $type = $fieldStorageDefinition->getType();
          if ($type === 'entity_reference') {
            $settings = $fieldStorageDefinition->getSettings();
            if (isset($settings['target_type']) && $settings['target_type'] === 'taxonomy_term') {
              $or_group->condition($fieldStorageDefinition->getName() . '.target_id', $term_id);
            }
          }
        }
      }
      $query->condition($or_group);
      // Set explicit access checking to FALSE for this query.
      $query->accessCheck(FALSE);
      $nids = $query->execute();
      $matching_nodes = \Drupal::entityTypeManager()->getStorage('node')->loadMultiple($nids);
      // Display the results.
      if (!empty($matching_nodes)) {
        $output = [
          $this->t('The ID of the Term: @id', ['@id' => $term_id]),
          $this->t('UUID of the Term: @uuid', ['@uuid' => $term_uuid]),
          $this->t('Node Title(s) of the nodes which use the mentioned term:'),
        ];
        foreach ($matching_nodes as $node) {
          $url = Url::fromRoute('entity.node.canonical', ['node' => $node->id()]);
          $link = Link::fromTextAndUrl($node->label(), $url)->toString();
          $output[] = $this->t('@link', ['@link' => $link]);
        }
        foreach ($matching_nodes as $node) {
          $output[] = $node->label();
        }
        \Drupal::messenger()->addMessage(['#markup' => implode('<br>', $output)], 'status');
      }
      else {

        \Drupal::messenger()->addMessage($this->t('No nodes found that use the taxonomy term "%term".', ['%term' => $term_name]), 'warning');
      }
    }
    else {
      // Handle the case when the term does not exist.
      \Drupal::messenger()->addMessage($this->t('The taxonomy term "%term" does not exist.', ['%term' => $term_name]), 'error');
    }
    parent::submitForm($form, $form_state);
  }

}
