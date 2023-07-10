<?php

namespace Drupal\routing\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountInterface;
use Drupal\user\Entity\User;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller to display the username .
 */
class RoutingController extends ControllerBase {

  /**
   * Class property for current user .
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $account;

  /**
   * Constructs an account interface object .
   *
   * @param \Drupal\Core\Session\AccountInterface $account
   *   Holds details about the user account .
   */
  public function __construct(AccountInterface $account) {
    $this->account = $account;
  }

  /**
   * This function will be used to retrieve data associated with the account .
   *
   * @param \Symfony\Component\DependencyInjection\ContainerInterface $container
   *   Instance of dependany injection container .
   *
   * @return object
   *   The created object
   */
  public static function create(ContainerInterface $container) {
    // Instantiates the form class .
    return new static(
      $container->get('current_user')
    );
  }

  /**
   * Prints the overview page .
   *
   * @return array
   *   Renderable array .
   */
  public function myRoute() {
    $user_name = $this->account->getDisplayName();
    $user = User::load($this->account->id());
    return [
      '#type' => 'markup',
      '#markup' => t(
        'Hello @userName',
        ['@userName' => $user_name],
      ),
      '#cache' => [
        'tags' => $user->getCacheTags(),
      ],
    ];
  }

}
