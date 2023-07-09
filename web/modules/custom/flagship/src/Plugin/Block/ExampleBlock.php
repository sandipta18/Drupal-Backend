<?php

namespace Drupal\flagship\Plugin\Block;

use Drupal\Core\Block\BlockBase;

/**
 * Provides an example block.
 *
 * @Block(
 *   id = "flagship_example",
 *   admin_label = @Translation("Example"),
 *   category = @Translation("flagship")
 * )
 */
class ExampleBlock extends BlockBase {

  /**
   * {@inheritdoc}
   */
  public function build() {
    $data = \Drupal::config('flagship.settings')->get('groupData');
    return [
      '#theme' => 'custom-theme',
      '#title' => 'Flagship Events',
      '#data' => $data,
      '#attached' => [
        'library' => [
          'flagship/custom_theme',
        ]
      ]
    ];

  }

}
