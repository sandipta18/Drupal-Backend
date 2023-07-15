<?php

namespace Drupal\colorfield\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldDefinition;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;
use Drupal\Core\Session\AccountInterface;
use Symfony\Component\DependencyInjection\ContainerInterface;

class RgbWidgetBase extends WidgetBase implements ContainerFactoryPluginInterface {

  /**
   * Account Interface object.
   *
   * @var Drupal\Core\Session\AccountInterface
   */
  protected $account;

  /**
   * @param string $plugin_id
   *   The plugin id of the widget.
   * @param mixed $plugin_definition
   *   The plugin definition of the widget.
   * @param FieldDefinitionInterface $field_definition
   *   Field associated widget definition.
   * @param array $settings
   *   Widget settings.
   * @param array $third_party_settings
   *   Third party settings.
   * @param AccountInterface $account
   *   Stores user related information.
   */
  public function __construct($plugin_id, $plugin_definition, FieldDefinitionInterface $field_definition, array $settings, array $third_party_settings, AccountInterface $account ) {
    parent::__construct($plugin_id, $plugin_definition, $field_definition, $settings, $third_party_settings);
    $this->account = $account;
  }

  /**
   * {@inheritDoc}
   */
  public static function create (ContainerInterface $container, array $configuration , $plugin_id, $plugin_definition) {
    return new static(
      $plugin_id,
      $plugin_definition,
      $configuration['field_definition'],
      $configuration['settings'],
      $configuration['third_party_settings'],
      $container->get('current_user')
    );
  }
  /**
   * {@inheritDoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    // No implementation needed .
  }

  public function userAdmin () {
    if(in_array('administrator', $this->account->getRoles())) {
      return TRUE;
    }
    return FALSE;
  }


}
