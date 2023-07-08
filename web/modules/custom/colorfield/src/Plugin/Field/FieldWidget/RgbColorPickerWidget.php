<?php

namespace Drupal\colorfield\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines the 'rgb_color_picker' field widget.
 *
 * @FieldWidget(
 *   id = "rgb_color_picker",
 *   label = @Translation("RGB Color Picker"),
 *   field_types = {"rgb_color"},
 * )
 */
class RgbColorPickerWidget extends WidgetBase {

  /**
   * {@inheritDoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element['hex_code'] = [
      '#type' => 'color',
      '#title' => $this->t('Color Picker'),
      '#default_value' => isset($items[$delta]->hex_code) ? $items[$delta]
      ->hex_code : NULL,
      '#required' => $element['#required'],
    ];

    return $element;
  }

}
