<?php

namespace Drupal\colorfield\Plugin\Field\FieldFormatter;

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
      $red = $item->red;
      $green = $item->green;
      $blue = $item->blue;

      $redHex = $this->componentToHex($red);
      $greenHex = $this->componentToHex($green);
      $blueHex = $this->componentToHex($blue);

      $colorCode = '#' . $redHex . $greenHex . $blueHex;

      $elements[$delta] = [
        '#type' => 'item',
        '#markup' => $colorCode,
      ];
    }

    return $elements;
  }

  /**
   * Convert an RGB color component to a two-digit hexadecimal representation.
   *
   * @param int $component
   *   The RGB color component value.
   *
   * @return string
   *   The two-digit hexadecimal representation of the component.
   */
  protected function componentToHex($component)
  {
    $hex = dechex($component);
    return str_pad($hex, 2, '0', STR_PAD_LEFT);
  }
}
