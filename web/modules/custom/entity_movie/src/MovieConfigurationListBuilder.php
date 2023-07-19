<?php

namespace Drupal\entity_movie;

use Drupal;
use Drupal\Core\Entity\EntityInterface;
use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Config\Entity\ConfigEntityListBuilder;
/**
 * Provides a listing of movie configurations.
 */
class MovieConfigurationListBuilder extends ConfigEntityListBuilder {

  /**
   * {@inheritdoc}
   */
  public function buildHeader() {
    $header['label'] = $this->t('Award Name');
    $header['movie'] = $this->t('Movie');
    return $header + parent::buildHeader();
  }

  /**
   * {@inheritdoc}
   */
  public function buildRow(EntityInterface $entity) {
    /** @var \Drupal\entity_movie\MovieConfigurationInterface $entity */
    $entityTypeManager = \Drupal::entityTypeManager();
    $id = $entity->get('movie')[0]['target_id'];
    $movie_name = $entityTypeManager->getStorage('node')->load($id)->get('title')->value;
    $row['label'] = $entity->label();
    $row['movie'] = $movie_name;
    return $row + parent::buildRow($entity);
  }

}
