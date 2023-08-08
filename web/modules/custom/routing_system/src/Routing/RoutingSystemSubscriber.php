<?php

namespace Drupal\routing_system\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Service to alter route.
 */
class RoutingSystemSubscriber extends RouteSubscriberBase {

  /**
   * {@inheritdoc}
   */
  protected function alterRoutes(RouteCollection $collection) {
    if ($route = $collection->get('routing_system.route')) {
      $route->setRequirement('_role', 'administrator');
    }
  }

}
