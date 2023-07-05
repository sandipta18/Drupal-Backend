<?php

namespace Drupal\field\Plugin\Field\FieldType;

use Drupal\Core\Field\FieldItemBase;
use Drupal\Component\Utility\Random;
use Drupal\Core\Field\FieldDefinitionInterface;
use Drupal\Core\Field\FieldStorageDefinitionInterface;
use Drupal\Core\TypedData\DataDefinition;
use Drupal\entity_test\FieldStorageDefinition;

/**
 * Define the custom field type
 * @FieldType(
 *   id = "custom_field_type"
 *   label = @Translation("Custom Field Type")
 *   description = @Translation("Desc for Custom Field Type")
 *   category = @Translation("Text),
 * )
 */
class CustomFieldType extends FieldItemBase {

  public static function schema(FieldStorageDefinitionInterface $field_definition) {
    return [
      'columns' => [
        'value' => 'varchar',
        'length' => 255,

      ],
    ];
    
  }

}
