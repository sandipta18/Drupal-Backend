<?php

namespace Drupal\colorfield\Plugin\Field\FieldFormatter;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;
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
class RgbColorStaticBackgroundFormatter extends FormatterBase
{

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode)
  {
    $elements = [];

    foreach ($items as $delta => $item) {
      if ($item->hex_code) {
        $colorCode = $item->hex_code;
        $attributes = new Attribute();
        $attributes->setAttribute('style', 'background-color: ' . $colorCode);      
        $elements[$delta] = [
          '#type' => 'html_tag',
          '#tag' => 'div',
          '#value' => $colorCode,
          '#attributes' => $attributes->toArray(),
        ];
      } else {
        $red = $item->red;
        $green = $item->green;
        $blue = $item->blue;
        $colorCode = 'rgb(' . $red . ' , ' . $green . ' , ' . $blue . ')';
        $attributes = new Attribute();
        $attributes->setAttribute('style', 'background-color: ' . $colorCode);
        $elements[$delta] = [
          '#type' => 'html_tag',
          '#tag' => 'div',
          '#value' => $colorCode,
          '#attributes' => $attributes->toArray(),
        ];
      }
    }

    return $elements;
  }
}
