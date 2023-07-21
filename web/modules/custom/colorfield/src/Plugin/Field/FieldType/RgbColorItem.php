<?php

namespace Drupal\colorfield\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;

/**
 * Defines the 'rgb_color' field type.
 *
 * @FieldType(
 *   id = "rgb_color",
 *   label = @Translation("Color"),
 *   category = @Translation("General"),
 *   default_widget = "rgb_color_hex",
 *   default_formatter = "rgb_color_static_text"
 * )
 */
class RgbColorItem extends FieldItemBase {

  /**
   * {@inheritDoc}
   */
  public static function propertyDefinitions(FieldStorageDefinitionInterface $field_definition) {
    $properties['color_code'] = DataDefinition::create('string')
      ->setLabel(t('Color Code'));
    return $properties;
  }

  /**
   * {@inheritDoc}
   */
  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    $columns = [
      'color_code' => [
        'type'    => 'varchar',
        'length'  => 255,
      ],
    ];
    $schema = [
      'columns' => $columns,
    ];

    return $schema;
  }

}
