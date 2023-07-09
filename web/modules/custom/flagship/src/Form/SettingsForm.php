<?php

namespace Drupal\flagship\Form;

use Drupal\Core\Cache\Cache;
use Drupal\Core\Form\ConfigFormBase;
use Drupal\Core\Form\FormStateInterface;

/**
 * Configure flagship settings for this site.
 */
class SettingsForm extends ConfigFormBase
{

  /**
   * {@inheritdoc}
   */
  public function getFormId()
  {
    return 'flagship_settings';
  }

  /**
   * {@inheritdoc}
   */
  protected function getEditableConfigNames()
  {
    return ['flagship.settings'];
  }

  /**
   * {@inheritdoc}
   */
  public function buildForm(array $form, FormStateInterface $form_state)
  {
    $config = $this->config('flagship.settings');
    $groupData =  $form_state->get('groupData');
    if (empty($groupData)) {
      $groupData = $config->get('groupData') ?: [];
      $form_state->set('groupData', $groupData);
    }
    if (empty($groupData)) {
      $this->addGroup($form,$form_state);
    }
    $form['groups'] = [
      '#title' => $this->t('Groups'),
      '#type' => 'fieldset',
      '#prefix' => '<div id="groups">',
      '#suffix' => '</div>',

    ];
    $form['groups']['groupData'] = [
      '#type'   => 'table',
      '#header' => [
        $this->t('Parent Group'),
        $this->t('Label 1'),
        $this->t('Value 1'),
        $this->t('Label 2'),
        $this->t('Value 2'),
        $this->t('Remove')
      ],
    ];

    $groupData = $form_state->get('groupData');
    foreach ($groupData as $index => $group) {

      $form['groups']['groupData'][$index]['parent_group'] = [
        '#type' => 'textfield',
        '#default_value' => $group['parent_group'] ?? '',
      ];

      $form['groups']['groupData'][$index]['label_1'] = [
        '#type' => 'textfield',
        '#default_value' => $group['label_1'] ?? '',
      ];

      $form['groups']['groupData'][$index]['value_1'] = [
        '#type' => 'textfield',
        '#default_value' => $group['value_1'] ?? '',
      ];
      $form['groups']['groupData'][$index]['label_2'] = [
        '#type' => 'textfield',
        '#default_value' => $group['label_2'] ?? '',
      ];
      $form['groups']['groupData'][$index]['value_2'] = [
        '#type' => 'textfield',
        '#default_value' => $group['value_2'] ?? '',
      ];

      $form['groups']['groupData'][$index]['remove'] = [
        '#type' => 'submit',
        '#value' => $this->t('Remove'),
        '#submit' => ['::removeGroup'],
        '#attributes' => [
           'remove_info' => $index,
        ],
        // '#ajax' => [
        //   '#callback' => '::ajaxCallback',
        //   '#wrapper' => 'groups-wrapper'
        // ]
      ];
    }

    $form['groups']['add'] = [
      '#type' => 'submit',
      '#value' => $this->t('Add'),
      '#submit' => ['::addGroup'],
      // '#ajax' => [
      //   '#callback' => '::ajaxCallback',
      //   '#wrapper' => 'groups-wrapper'
      // ]
    ];


    return parent::buildForm($form, $form_state);
    // return $form;
  }

  public function ajaxCallback(array &$form, FormStateInterface $form_state)
  {
    return $form['groups'];
  }

  public function addGroup(array &$form, FormStateInterface $form_state)
  {
    $groupData = $form_state->get('groupData');
    $groupData[] = [
      'parent_group' => '',
      'label_1' => '',
      'value_1' => '',
      'label_2' => '',
      'value_2' => ''
    ];
    $form_state->set('groupData', $groupData);
    $form_state->setRebuild(TRUE);
  }

  public function removeGroup(array &$form, FormStateInterface $form_state)
  {
    $requestedElement = $form_state->getTriggeringElement();
    $indexToDelete = $requestedElement['#attributes']['remove_info'];
    $groupData = $form_state->get('groupData');
    if(isset($groupData[$indexToDelete])) {
      unset($groupData[$indexToDelete]);
      $form_state->set('groupData',$groupData);
      $form_state->setRebuild(TRUE);
    }

  }

  public function submitForm(array &$form, FormStateInterface $form_state)
  {
    $config = $this->config('flagship.settings');
    $groupData = $form_state->getValue('groupData');
    $config->set('groupData', $groupData)->save();
    parent::submitForm($form, $form_state);
  }
}
