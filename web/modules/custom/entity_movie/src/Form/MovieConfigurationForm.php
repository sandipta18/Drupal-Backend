<?php

namespace Drupal\entity_movie\Form;

use Drupal\Core\Entity\EntityForm;
use Drupal\Core\Form\FormStateInterface;

/**
 * Movie Configuration form.
 *
 * @property \Drupal\entity_movie\MovieConfigurationInterface $entity
 */
class MovieConfigurationForm extends EntityForm {

  /**
   * {@inheritdoc}
   */
  public function form(array $form, FormStateInterface $form_state) {
    $form = parent::form($form, $form_state);
    $form['label'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Award Name'),
      '#maxlength' => 255,
      '#default_value' => $this->entity->label(),
      '#description' => $this->t('Award Name.'),
      '#required' => TRUE,
    ];
    $form['id'] = [
      '#type' => 'machine_name',
      '#default_value' => $this->entity->id(),
      '#machine_name' => [
        'exists' => '\Drupal\entity_movie\Entity\MovieConfiguration::load',
      ],
      '#disabled' => !$this->entity->isNew(),
    ];
    $id = $this->entity->get('movie')[0]['target_id'] ?? '';
    $form['movie'] = [
      '#type' => 'entity_autocomplete',
      '#title' => $this->t('Movie Name'),
      '#target_type' => 'node',
      '#selection_settings' => ['target_bundles' => ['movie_info']],
      '#tags' => TRUE,
      '#size' => 30,
      '#maxlength' => 1024,
      '#default_value' => $this->entityTypeManager->getStorage('node')->load($id ?? ''),
      '#persist' => TRUE,
    ];
    $default_year = $this->entity->get('year');
    $form['year'] = [
      '#type' => 'number',
      '#title' => $this->t('Year'),
      '#default_value' => $default_year,
      '#description' => $this->t('Year of the award.'),
      '#required' => TRUE,
      '#persist' => TRUE,
    ];

    return $form;
  }

  /**
   * {@inheritdoc}
   */
  public function save(array $form, FormStateInterface $form_state) {
    $result = parent::save($form, $form_state);
    $message_args = ['%label' => $this->entity->label()];
    $message = $result == SAVED_NEW
      ? $this->t('Created new movie configuration %label.', $message_args)
      : $this->t('Updated movie configuration %label.', $message_args);
    $this->messenger()->addStatus($message);
    $form_state->setRedirectUrl($this->entity->toUrl('collection'));
    return $result;
  }

}
