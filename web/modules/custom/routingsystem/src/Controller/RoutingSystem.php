<?php

namespace Drupal\routingsystem\Controller;

use Drupal\Core\Access\AccessResult;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Controller\ControllerBase;

/**
 * Controller for page route
 */
class RoutingSystem extends ControllerBase {

  /**
   * Class property for current user.
   *
   * @var \Drupal\Core\Session\AccountInterface;
   */
  protected $account;

  /**
   * Constructs an Account Interface Object
   *
   * @param AccountInterface $account
   *   Hold details about the account
   */
  public function __construct(AccountInterface $account) {
     $this->account = $account;
  }

  /**
   * This function will be used to retrieve the data associated with the service
   * with key 'current user'
   *
   * @param ContainerInterface $container
   *   Instance of the dependancy injection container
   *
   * @return object
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_user')
    );
  }
  /**
   * Prints the overview page
   *
   * @return array
   *   Returns renderable array
   */
  public function homeRoute() {
    // Fetching the user name of the currently
    $userName = $this->account->getDisplayName();
    return [
      '#type'   => 'markup',
      '#markup' => $this->t('Hello @user',[
        '@user' =>$userName
      ])
    ];
  }


  /**
   * This function will be used to determine whether the access request should
   * be accepted or denied
   *
   * @return object
   *   Instance of the access result class
   */
  public function customAccess () {
    return ($this->account->hasPermission('routingsystem.permission')) ?
    AccessResult::allowed() :  AccessResult::forbidden();
  }

}
