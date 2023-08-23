<?php

namespace Drupal\javascript_api\Controller;

use Drupal\Core\Controller\ControllerBase;

/**
 * Returns responses for javascript_api routes.
 */
class JavascriptApiController extends ControllerBase {

  /**
   * Builds the response.
   */
  public function build() {

    $build['content'] = [
      '#type' => 'item',
      '#markup' => $this->t('It works!'),
      '#attached' => [
        'library' => 'javascript_api/javascript_api_basic',
      ],
    ];

    return $build;
  }

}
