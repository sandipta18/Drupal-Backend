<?php

namespace Drupal\colorfield\Plugin\Field\FieldWidget;

use Drupal\Component\Serialization\Json;
use Drupal\Component\Utility\Color;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
 * Defines the 'rgb_color_component' field widget.
 *
 * @FieldWidget(
 *   id = "rgb_color_component",
 *   label = @Translation("RGB Color Component"),
 *   field_types = {"rgb_color"},
 * )
 */
class RgbColorComponentWidget extends RgbWidgetBase implements ContainerFactoryPluginInterface {

  /**
   * {@inheritDoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    // Onld admin user can access.
    if ($this->userAdmin()) {

      if (!Color::validateHex($items[$delta]->color_code)) {
        $values = Json::decode($items[$delta]->color_code ?? '');
      }
      else {
        $values = Color::hexToRgb($items[$delta]->color_code);
        $values['r'] = $values['red'];
        $values['g'] = $values['green'];
        $values['b'] = $values['blue'];

      }
      $element['color_code']['r'] = [
        '#type' => 'number',
        '#title' => $this->t('Red'),
        '#default_value' => isset($items[$delta]->color_code) ? $values['r'] : NULL,
      ];

      $element['color_code']['g'] = [
        '#type' => 'number',
        '#title' => $this->t('Green'),
        '#default_value' => isset($items[$delta]->color_code) ? $values['g'] : NULL,
      ];

      $element['color_code']['b'] = [
        '#type' => 'number',
        '#title' => $this->t('Blue'),
        '#default_value' => isset($items[$delta]->color_code) ? $values['b'] : NULL,
      ];
      return $element;
    }
  }

  /**
   * {@inheritDoc}
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    foreach ($values as $delta => $value) {
      $rgb = [
        $value['color_code']['r'],
        $value['color_code']['g'],
        $value['color_code']['b'],
      ];
      if ($value['color_code']['r'] === '' && $value['color_code']['g'] === '' && $value['color_code']['b'] === '') {
        $values[$delta]['color_code'] = NULL;
      }
      elseif (!Color::validateHex(Color::rgbToHex($rgb))) {
        $form_state->setErrorByName($this->fieldDefinition->getName(),
        $this->t('Invalid RGB value for field @field'));
      }
      else {
        $values[$delta]['color_code'] = Json::encode($value['color_code']);
      }
    }
    return $values;
  }

}
