<?php

namespace Drupal\colorfield\Plugin\Field\FieldWidget;

use Drupal\Component\Serialization\Json;
use Drupal\Component\Utility\Color;
use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Plugin\ContainerFactoryPluginInterface;

/**
 * Defines the 'rgb_color_picker' field widget.
 *
 * @FieldWidget(
 *   id = "rgb_color_picker",
 *   label = @Translation("RGB Color Picker"),
 *   field_types = {"rgb_color"},
 * )
 */
class RgbColorPickerWidget extends RgbWidgetBase implements ContainerFactoryPluginInterface {

  /**
   * {@inheritDoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    // Onld admin user can access.
    if ($this->userAdmin()) {
      $color = $items[$delta]->color_code ?? NULL;
      if (!Color::validateHex($color) && $color) {
        $raw_data = Json::decode($items[$delta]->color_code);
        $rgb['red'] = $raw_data['r'];
        $rgb['green'] = $raw_data['g'];
        $rgb['blue'] = $raw_data['b'];
        $color = Color::rgbToHex($rgb);
      }
      $element['color_code'] = [
        '#type' => 'color',
        '#title' => $this->t('Color Picker'),
        '#default_value' => $color ?? NULL,
      ];

      return $element;
    }
  }

}
