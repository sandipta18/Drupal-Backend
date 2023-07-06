<?php

namespace Drupal\colorfield\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Plugin implementation of the 'RGB Color Static Background' formatter.
 *
 * @FieldFormatter(
 *   id = "rgb_color_static_background",
 *   label = @Translation("RGB Color Static Background"),
 *   field_types = {
 *     "rgb_color"
 *   }
 * )
 */
class RgbColorStaticBackgroundFormatter extends FormatterBase {

  public function viewElements(FieldItemListInterface $items, $langcode)
  {
    $elements = [];

    foreach ($items as $delta => $item) {
      $elements[$delta] = [
        '#type' => 'item',
        '#markup' => $item->hex_code,
      ];
    }

    return $elements;
  }

}
