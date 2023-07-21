<?php

namespace Drupal\colorfield\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Template\Attribute;

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
class RgbColorStaticBackgroundFormatter extends RgbColorFormatterBase {

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    $elements = [];

    foreach ($items as $delta => $item) {
      if ($item->color_code) {
        $color = $this->colorInfo($items, $delta);
        $attributes = new Attribute();
        $attributes->setAttribute('style', 'background-color: ' . $color);
        $elements[$delta] = [
          '#type' => 'html_tag',
          '#tag' => 'div',
          '#value' => $color,
          '#attributes' => $attributes->toArray(),
        ];
      }
    }

    return $elements;
  }

}
