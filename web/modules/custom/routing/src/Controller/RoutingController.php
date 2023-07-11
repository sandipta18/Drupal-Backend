<?php

namespace Drupal\routing\Controller;

use Drupal\Core\Controller\ControllerBase;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

/**
 * Controller to display the username
 */
class RoutingController extends ControllerBase {

  /**
   * Class property for current user
   *
   * @var \Drupal\Core\Session\AccountInterface
   */
  protected $account;

  /**
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
  public static function create(ContainerInterface $container) {
    //Instantiates the form class
    return new static(
      $container->get('current_user')
    );
  }
  /**
   * This function displays the name of the user in a custom page
   * with routing context /route with the help of a renderable array
   * that it returns
   * @return array
   */
  public function myRoute() {
    $userName = $this->account->getDisplayName();
    return [
      '#type' => 'markup',
      '#markup' => $this->t('Hello @userName', [
        '@userName' => $userName,
      ]),
    ];
  }
}
