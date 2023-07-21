<?php

namespace Drupal\colorfield\Plugin\Field\FieldWidget;

use Drupal\Component\Serialization\Json;
use Drupal\Component\Utility\Color;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
 * Defines the 'rgb_color_hex' field widget.
 *
 * @FieldWidget(
 *   id = "rgb_color_hex",
 *   label = @Translation("RGB Color Hex"),
 *   field_types = {"rgb_color"},
 * )
 */
class RgbColorHexWidget extends RgbWidgetBase implements ContainerFactoryPluginInterface {

  /**
   * {@inheritDoc}
   */
  public function formElement(FieldItemListInterface $items,
  $delta,
  array $element,
  array &$form,
  FormStateInterface $form_state) {
    // Onld admin user can access.
    if ($this->userAdmin()) {

      $color = $items[$delta]->color_code;
      if (!Color::validateHex($color) && $color) {
        $raw_data = Json::decode($items[$delta]->color_code);
        $color = Color::rgbToHex($raw_data);
      }
      $element['color_code'] = [
        '#type' => 'textfield',
        '#title' => $this->t('Hex Code'),
        '#default_value' => $color ?? NULL,
        '#size' => 8,
      ];

      return $element;
    }
  }

  /**
   * {@inheritDoc}
   */
  public function massageFormValues(array $values, array $form, FormStateInterface $form_state) {
    foreach ($values as $delta => $value) {
      if (!Color::validateHex($value['color_code'])) {
        $form_state->setErrorByName('color_code', 'Hex Value is not valid');
      }
    }
    return $values;
  }

}
