<?php

namespace Drupal\routing\Controller;

use Drupal\user\Entity\User;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Cache\Context\CacheContextInterface;
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
   * @param AccountInterface $account
   *   Holds details about the user account .
   */
  public function __construct(AccountInterface $account) {
    $this->account = $account;
  }

  /**
   * This function will be used to retrieve data associated with account
   * with key 'current_user' .
   *
   * @param ContainerInterface $container
   *   Instance of dependany injection container .
   *
   * @return object
   */
  public static function create(ContainerInterface $container) {
    //Instantiates the form class .
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
