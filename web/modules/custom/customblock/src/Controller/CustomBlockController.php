<?php

namespace Drupal\customblock\Controller;

use Drupal\Component\Plugin\PluginManagerInterface;
use Drupal\Core\Controller\ControllerBase;
use Symfony\Component\DependencyInjection\ContainerInterface;

class CustomBlockController extends ControllerBase {

  /**
   * @var PluginManagerInterface $pluginManager
   *   Class property for PluginManagerInterface
   */
  protected $pluginManager;

  /**
   * The injected dependency is assigned to the corresponding class propery
   *
   * @param PluginManagerInterface $pluginManager
   *   Class property for PluginManagerInterface
   */
  public function __construct(PluginManagerInterface $pluginManager) {
    $this->pluginManager = $pluginManager;
  }

  /**
   * Creating an instance of the class using dependancy injection
   *
   * @param ContainerInterface $container
   *   Dependency Injector Interface Container
   *
   * @return object
   *   Returns newly created instance of the class
   */
  public static function create(ContainerInterface $container) {
    return new static (
      $container->get('plugin.manager.block')
    );
  }

  /**
   * Displays the overview page
   *
   * @return array
   *   Renderable array
   */
  public function customBlock() {
    // Loading the custom block plugin information in a local variable
    $plugin_manager = $this->pluginManager;
    // Creating an instance of the block by sending the id of the block as
    // argument
    $block_plugin = $plugin_manager->createInstance('welcome_user_role_block');
    // calling the build method to generate a renderable array representing
    // block content
    $built_block = $block_plugin->build();
    return $built_block;
  }
}
