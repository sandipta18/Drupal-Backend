<?php

namespace Drupal\colorfield\Plugin\Field\FieldFormatter;

use Drupal\Component\Serialization\Json;
use Drupal\Component\Utility\Color;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\FormatterBase;

/**
 * Formatter Base.
 */
class RgbColorFormatterBase extends FormatterBase {

  /**
   * This function returns the color in proper format.
   *
   * @param FieldItemInterface $items
   *   Holds the value of the color_code.
   * @param mixed $delta
   *   Holds the indexes.
   *
   * @return string
   *   Value of color.
   */
  public function colorInfo($items, $delta) {
    $color = $items[$delta]->color_code;
    if (!Color::validateHex($items[$delta]->color_code)) {
      $value = Json::decode($items[$delta]->color_code);
      $color = 'rgb(' . $value['r'] . ',' . $value['g'] . ',' . $value['b'] . ')';
    }
    else {
      if (!strpos($color, '#') === 0) {
        $color = '#' . $color;
      }
    }

    return $color;
  }

  /**
   * {@inheritDoc}
   */
  public function viewElements(FieldItemListInterface $items, $langcode) {
    // No implementation required here.
  }

}
