<?php

namespace Drupal\colorfield\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'RGB Color Static Text' formatter.
 *
 * @FieldFormatter(
 *   id = "rgb_color_static_text",
 *   label = @Translation("RGB Color Static Text"),
 *   field_types = {
 *     "rgb_color"
 *   }
 * )
 */
class RgbColorStaticTextFormatter extends FormatterBase {

  public function viewElements(FieldItemListInterface $items, $langcode)
  {
    $elements = [];

    foreach ($items as $delta => $item) {
      $colorCode = $item->hex_code;
      $backgroundColor = '#' . $colorCode;

      $elements[$delta] = [
        '#type' => 'item',
        '#markup' => $colorCode,
        '#attributes' => [
          'style' => "background-color: $backgroundColor;",
        ],
      ];
    }

    return $elements;
  }

}
