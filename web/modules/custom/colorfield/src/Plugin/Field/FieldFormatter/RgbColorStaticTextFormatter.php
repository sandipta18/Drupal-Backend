<?php

namespace Drupal\colorfield\Plugin\Field\FieldFormatter;

use Attribute;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

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
class RGBColorStaticTextFormatter extends FormatterBase
{

  /**
   * {@inheritdoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode)
  {
    $elements = [];

    foreach ($items as $delta => $item) {

      if($items->hex_code) {
        $colorCode = $items->hex_code;
        $elements[$delta] = [
          '#type'   => 'markup',
          '#markup' => $colorCode,
        ];
      } else {
        $red = $item->red;
        $green = $item->green;
        $blue = $item->blue;
        $colorCode = 'rgb(' . $red . ' , ' . $green . ' , ' . $blue . ')';
        $elements[$delta] = [
          '#type'   => 'markup',
          '#markup' => $colorCode,
        ];
      }
    }

    return $elements;
  }


}
