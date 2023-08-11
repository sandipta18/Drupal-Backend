<?php

namespace Drupal\database_api;

use Drupal\Core\Entity\EntityTypeManagerInterface;
use Drupal\Core\Extension\ModuleUninstallValidatorInterface;
use Drupal\Core\StringTranslation\StringTranslationTrait;
use Drupal\Core\StringTranslation\TranslationInterface;

/**
 * Prevents database_api module from being uninstalled under certain conditions.
 *
 * These conditions are when any event nodes exist.
 */
class EventUninstallValidator implements ModuleUninstallValidatorInterface {

  use StringTranslationTrait;

  /**
   * The entity type manager.
   *
   * @var \Drupal\Core\Entity\EntityTypeManagerInterface
   */
  protected $entityTypeManager;

  /**
   * Constructs a new MovieUninstallValidator.
   *
   * @param \Drupal\Core\Entity\EntityTypeManagerInterface $entity_type_manager
   *   The entity type manager.
   * @param \Drupal\Core\StringTranslation\TranslationInterface $string_translation
   *   The string translation service.
   */
  public function __construct(EntityTypeManagerInterface $entity_type_manager, TranslationInterface $string_translation) {
    $this->entityTypeManager = $entity_type_manager;
    $this->stringTranslation = $string_translation;
  }

  /**
   * {@inheritdoc}
   */
  public function validate($module) {
    $reasons = [];
    if ($module == 'database_api' && $this->hasNodes() != 0) {
      $reasons[] = $this->t('To uninstall module, delete all content that is
      part of event content type');
    }
    return $reasons;
  }

  /**
   * Determines if there is any movie nodes posted by user or not.
   *
   * @param \Drupal\user\UserInterface $data
   *   Post Owner.
   *
   * @return string
   *   Count of number of posts.
   */
  protected function hasNodes($data = NULL) {
    $nodes = $this->entityTypeManager->getStorage('node')->getQuery()
      ->condition('type', 'event')
      ->accessCheck(TRUE)
      ->condition('status', 1);
    if ($data) {
      $nodes->condition('uid', $data->id());
    }
    return $nodes->count()
      ->execute();
  }

}
