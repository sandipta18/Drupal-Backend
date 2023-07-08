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
      $red = $item->red;
      $green = $item->green;
      $blue = $item->blue;

      $backgroundColor = $this->componentToHex($red, $green, $blue);

      $attributes = new Attribute();
      $attributes->setAttribute('style', 'background-color: ' . $backgroundColor);

      $elements[$delta] = [
        '#type' => 'html_tag',
        '#tag' => 'div',
        '#value' => $backgroundColor,
        '#attributes' => $attributes->toArray(),
      ];
    }

    return $elements;
  }

  /**
   * Convert RGB color components to hexadecimal representation.
   *
   * @param int $red
   *   The red component value.
   * @param int $green
   *   The green component value.
   * @param int $blue
   *   The blue component value.
   *
   * @return string
   *   The hexadecimal representation of the RGB color.
   */
  protected function componentToHex($red, $green, $blue)
  {
    $redHex = str_pad(dechex($red), 2, '0', STR_PAD_LEFT);
    $greenHex = str_pad(dechex($green), 2, '0', STR_PAD_LEFT);
    $blueHex = str_pad(dechex($blue), 2, '0', STR_PAD_LEFT);

    return '#' . $redHex . $greenHex . $blueHex;
  }
}
