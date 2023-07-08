<?php

namespace Drupal\colorfield\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Field\WidgetBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Defines the 'rgb_color_component' field widget.
 *
 * @FieldWidget(
 *   id = "rgb_color_component",
 *   label = @Translation("RGB Color Component"),
 *   field_types = {"rgb_color"},
 * )
 */
class RgbColorComponentWidget extends WidgetBase {

  /**
   * {@inheritDoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element['red'] = [
      '#type' => 'number',
      '#title' => $this->t('Red'),
      '#default_value' => isset($items[$delta]->red) ? $items[$delta]->red : NULL,
      '#min' => 0,
      '#max' => 255,
    ];

    $element['green'] = [
      '#type' => 'number',
      '#title' => $this->t('Green'),
      '#default_value' => isset($items[$delta]->green) ? $items[$delta]->green : NULL,
      '#min' => 0,
      '#max' => 255,
    ];

    $element['blue'] = [
      '#type' => 'number',
      '#title' => $this->t('Blue'),
      '#default_value' => isset($items[$delta]->blue) ? $items[$delta]->blue : NULL,
      '#min' => 0,
      '#max' => 255,
    ];

    return $element;
  }

 }


