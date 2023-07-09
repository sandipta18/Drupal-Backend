<?php

namespace Drupal\colorfield\Plugin\Field\FieldType;

use Drupal\Component\Utility\Random;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Defines the 'rgb_color' field type.
 *
 * @FieldType(
 *   id = "rgb_color",
 *   label = @Translation("RGB Color"),
 *   category = @Translation("General"),
 *   default_widget = "rgb_color_hex",
 *   default_formatter = "rgb_color_static_text"
 * )
 *
 */
class RgbColorItem extends FieldItemBase {

  /**
   * {@inheritDoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
     $properties['hex_code'] = DataDefinition::create('string')->
     setLabel(t('Hex Code'));

     $properties['red'] = DataDefinition::create('integer')->
     setLabel(t('Red'));

     $properties['green'] = DataDefinition::create('integer')->
     setLabel(t('Green'));

     $properties['blue'] = DataDefinition::create('integer')->
     setLabel(t('Blue'));

     return $properties;
  }


  /**
   * {@inheritDoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
     $columns = [
       'hex_code' => [
        'type'    => 'varchar',
        'length'  => 255,
       ],
       'red' => [
        'type' => 'int',
        'size' => 'normal',
       ],
      'green' => [
        'type' => 'int',
        'size' => 'normal',
      ],
      'blue' => [
        'type' => 'int',
        'size' => 'normal',
      ],
     ];
     $schema = [
      'columns' => $columns,
     ];

     return $schema;
  }

}
