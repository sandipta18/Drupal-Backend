<?php

/**
 * @file
 * Displas a simple message to the user based on permission provided in the
 * .routing.yml file
 */

namespace Drupal\routingsystem\Controller;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Controller\ControllerBase as ControllerControllerBase;

/**
 * Controller for page route
 */
class RoutingSystem extends ControllerControllerBase {

  /**
   * Class property for current user
   * @var \Drupal\Core\Session\AccountInterface;
   */
  protected $account;

  /**
   * {@inheritDoc}
   * @param AccountInterface $account
   */
  public function __construct(AccountInterface $account) {
     $this->account = $account;
  }

  /**
   * {@inheritDoc}
   * @param ContainerInterface $container
   * @return object
   */
  public static function create(ContainerInterface $container)
  {
    //Instantiates the form class
    return new static(
      $container->get('current_user')
    );
  }
  /**
   * Prints the overview page
   * @return array
   *  Returns render array
   */
  public function customRoute() {
    $pageAccess = $this->account->hasPermission('routingsystem.permission');
    if($pageAccess) {
    return [
     '#markup' => 'You have access to this page'
    ];
  }

  }

}
