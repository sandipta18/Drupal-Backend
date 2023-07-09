<?php

namespace Drupal\routingsystem\Routing;

use Drupal\Core\Routing\RouteSubscriberBase;
use Symfony\Component\Routing\RouteCollection;

/**
 * Services to alter route
 */
class AlterRoute extends RouteSubscriberBase
{

  /**
   * {@inheritDoc}
   */
  public function alterRoutes(RouteCollection $collection)
  {
    // $route = $collection->get('routingsystem.route');
    // if ($route) {
    //   $route->setPath('/route2/changed');
    //   $route->setRequirement('_role','administrator');
    // }
  }
}
