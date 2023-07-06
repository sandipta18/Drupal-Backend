<?php

namespace Drupal\fieldapi\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Defines the "custom field type"
 *
 * @FieldType(
 *   id = "custom_field_type",
 *   label = @Translation("Custom Field Type"),
 *   description = @Translation("Description for Custom Field Type"),
 *   category = @Translation("Text"),
 *   default_formatter = "custom_field_formatter",
 *   default_widget = "custom_field_widget",
 *
 * )
 */
class CustomFieldType extends FieldItemBase {

  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
      'columns' => [
        'value' => [
          'type'   => 'varchar',
          'length' =>  $field_definition->getSetting('length')

        ],
      ],
    ];
  }

  public static function defaultStorageSettings() {
    return [
      'length' => 255,

    ] + parent::defaultStorageSettings();
  }

  public function storageSettingsForm(array &$form, FormStateInterface $form_state, $has_data) {
    $element = [];
    $element['length'] = [
      '#type' => 'number',
      '#title' => t('Length of your text'),
      '#required' => TRUE,
      '#default_value' => $this->getSetting('length')
    ];
    return $element;
  }

   public static function defaultFieldSettings()  {
     return [
       'moreinfo' => "More info default value",

     ] + parent::defaultFieldSettings();
   }

   public function fieldSettingsForm(array $form, FormStateInterface $form_state) {
      $element = [];
      $element['moreinfo'] = [
        '#type' => 'textfield',
        '#title' => 'More information about this field',
        '#required' => TRUE,
        '#default_value' => $this->getSetting('moreinfo'),
      ];
      return $element;
   }

   public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
     $properties['value'] = DataDefinition::create('string')->setLabel(t("Name"));
     return $properties;
  }


}
