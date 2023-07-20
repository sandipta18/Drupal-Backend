<?php

namespace Drupal\routingsystem\Controller;

use Drupal\Core\Controller\ControllerBase as ControllerControllerBase;
use Drupal\Core\StringTranslation\StringTranslationTrait;

/**
 * Controller for page route.
 */
class MultidataRoute extends ControllerControllerBase {

  /**
   * Returns the overview page.
   *
   * @param int $data
   *   Accepting dynamic parameter from url.
   *
   * @return array
   *   A render array to display the hello message.
   */
  public function multiRoute($data) {
    return [
      '#type'   => 'markup',
      '#markup' => $this->t('It is node @value', [
        '@value' => $data,
      ]),
    ];
  }

}
