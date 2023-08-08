<?php

namespace Drupal\routing_system\Controller;

use Drupal\Core\Access\AccessResult;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller for page route.
 */
class RoutingSystemController extends ControllerBase {

  /**
   * Class property for current user.
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $account;

  /**
   * Constructs an Account Interface Object.
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   Hold details about the account.
   */
  public function __construct(AccountInterface $account) {
    $this->account = $account;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container) {
    return new static(
      $container->get('current_user')
    );
  }

  /**
   * Prints the overview page.
   *
   * @return array
   *   Returns renderable array.
   */
  public function homeRoute() {
    // Fetching the user name of the currently.
    $user_name = $this->account->getDisplayName();
    return [
      '#type'   => 'markup',
      '#markup' => $this->t('Hello @user', [
        '@user' => $user_name,
      ]),
    ];
  }

  /**
   * This function will be used to determine whether the access request should
   * be accepted or denied.
   *
   * @return object
   *   Instance of the access result class.
   */
  public function customAccess() {
    return $this->account->hasPermission('routingsystem.permission')
    ? AccessResult::allowed()
    : AccessResult::forbidden();
  }

}
