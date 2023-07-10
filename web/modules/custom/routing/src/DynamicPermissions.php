<?php

namespace Drupal\routing;

use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Provides dynamic permissions for nodes of different types .
 */
class DynamicPermissions {

  use StringTranslationTrait;

  /**
   * Returns an array of node type permissions .
   *
   * @return array
   *   The node type permissions .
   */
  public function getPermissions() {
    $permissions = [];
    //Generating 5 permmissions .
    $count = 1;
    while ($count <= 5) {
      $permissions += [
        "dynamic permission $count" => [
          'title' => t('Sample dynamic permission @number', ['@number' => $count]),
          'description' => 'This is a sample permission generated dynamically.'
        ],
      ];
      $count++;
    }
    return $permissions;
  }
}
