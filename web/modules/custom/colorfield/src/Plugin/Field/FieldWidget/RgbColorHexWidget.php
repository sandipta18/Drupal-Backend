<?php

namespace Drupal\colorfield\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines the 'rgb_color_hex' field widget.
 *
 * @FieldWidget(
 *   id = "rgb_color_hex",
 *   label = @Translation("RGB Color Hex"),
 *   field_types = {"rgb_color"},
 * )
 */
class RgbColorHexWidget extends WidgetBase {

  /**
   * {@inheritDoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
     $element['hex_code'] = [
      '#type' => 'textfield',
      '#title' => $this->t('Hex Code'),
      '#default_value' => isset($items[$delta]->hex_code) ? $items[$delta]->hex_code : NULL,
      '#size' => 255,
      '#maxlength' => 255,
     ];

     return $element;
  }

}
