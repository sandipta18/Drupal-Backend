<?php

namespace Drupal\colorfield\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;

/**
 * Plugin implementation of the 'rgb_color_static_text' formatter.
 *
 * @FieldFormatter(
 *   id = "rgb_color_static_text",
 *   label = @Translation("RGB Color Static Text"),
 *   field_types = {
 *     "rgb_color"
 *   }
 * )
 */
class RgbColorStaticTextFormatter extends RgbColorFormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];
    foreach ($items as $delta => $item) {
      if ($item->color_code) {
        $color = $this->colorInfo($items, $delta);
        $elements[$delta] = [
          '#type'   => 'markup',
          '#markup' => $color,
        ];
      }
    }

    return $elements;
  }

}
