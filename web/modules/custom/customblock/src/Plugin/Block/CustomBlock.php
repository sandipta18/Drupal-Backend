<?php

namespace Drupal\customblock\Plugin\Block;

use Symfony\Component\DependencyInjection\ContainerInterface;
use Drupal\Core\Session\AccountInterface;
use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
 * Provides "Welcome User Role" block.
 *
 * @Block(
 *   id = "welcome_user_role_block",
 *   admin_label = @Translation("Welcome User"),
 *   category = @Translation("Custom block for hello world")
 * )
 */
class CustomBlock extends BlockBase implements ContainerFactoryPluginInterface {

  /**
   * @var Account Interface $account
   */
  protected $account;


  /**
   * @param array $configuration
   *   Holds information about the block that includes id,label
   * @param string $plugin_id
   *   Holds the plugin ID
   * @param mixed $plugin_definition
   *   Holds information about the plugin that includes category,id,class
   * @param AccountInterface $account
   *   Holds information about the account that requested access
   *
   */

  public function __construct(array $configuration, $plugin_id, $plugin_definition, AccountInterface $account) {
    Parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->account = $account;
  }

  /**
   * @param ContainerInterface $container
   *   Dependency Injector Interface Container
   * @param array $configuration
   *   Holds information about the block that includes id,label
   * @param string $plugin_id
   *   Holds the plugin ID
   * @param mixed $plugin_definition
   *   Holds information about the plugin that includes category,id,class
   *
   */
 public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
  return new static (
    $configuration,
    $plugin_id,
    $plugin_definition,
    $container->get('current_user')
  );
 }

  /**
   * It displays the overview page
   * 
   * @return array
   *   Renderable Array
   */
  public function build() {
    $role = $this->account->getRoles();
    return [
      '#type' => 'markup',
      '#markup' => $this->t('hello @role',[
         '@role' => implode(',',$role)
      ]),
    ];
  }
}
