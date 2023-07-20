<?php

namespace Drupal\customblock\Plugin\Block;

use Drupal\Core\Block\BlockBase;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

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
   * Account Interface Manager.
   *
   * @var Account Interface account
   */
  protected $account;

  /**
   * Constructs AccountInterface object.
   *
   * @param array $configuration
   *   Holds information about the block that includes id,label.
   * @param string $plugin_id
   *   Holds the plugin ID.
   * @param mixed $plugin_definition
   *   Holds information about the plugin that includes category,id,class.
   * @param \Drupal\Core\Session\AccountInterface $account
   *   Holds information about the account that requested access.
   */
  public function __construct(array $configuration, $plugin_id, $plugin_definition, AccountInterface $account) {
    parent::__construct($configuration, $plugin_id, $plugin_definition);
    $this->account = $account;
  }

  /**
   * {@inheritDoc}
   */
  public static function create(ContainerInterface $container, array $configuration, $plugin_id, $plugin_definition) {
    return new static(
    $configuration,
    $plugin_id,
    $plugin_definition,
    $container->get('current_user')
    );
  }

  /**
   * It displays the overview block.
   *
   * @return array
   *   Renderable Array
   */
  public function build() {
    $role = $this->account->getRoles();
    return [
      '#type' => 'markup',
      '#markup' => $this->t('hello @role', [
        '@role' => implode(',', $role),
      ]),
    ];
  }

}
