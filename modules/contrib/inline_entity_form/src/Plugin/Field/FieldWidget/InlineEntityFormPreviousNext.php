<?php

/**
 * @file
 * Contains \Drupal\inline_entity_form\Plugin\Field\FieldWidget\InlineEntityFormPreviousNext.
 */

namespace Drupal\inline_entity_form\Plugin\Field\FieldWidget;

use Drupal\Core\Field\FieldItemListInterface;
use Drupal\Core\Form\FormStateInterface;
use Drupal\Core\Render\Element;

/**
 * Complex inline widget.
 *
 * @FieldWidget(
 *   id = "inline_entity_form_previous_next",
 *   label = @Translation("Inline entity form - Previous/Next"),
 *   field_types = {
 *     "entity_reference"
 *   },
 *   multiple_values = true
 * )
 */
class InlineEntityFormPreviousNext extends InlineEntityFormComplex {

  /**
   * {@inheritdoc}
   */
  public function formElement(FieldItemListInterface $items, $delta, array $element, array &$form, FormStateInterface $form_state) {
    $element = parent::formElement($items, $delta, $element, $form, $form_state);

    if (!$this->canBuildForm($form_state)) {
      return $element;
    }

    $entities = $form_state->get(['inline_entity_form', $this->getIefId(), 'entities']);
    foreach ($entities as $key => $value) {
      if (!empty($value['form']) && $value['form'] == 'edit') {
        if ($key < count($entities) - 1) {
          array_push($element['entities'][$key]['form']['inline_entity_form']['#process'], [get_class($this), 'buildNextFormActions']);
        }
        if ($key != 0) {
          array_push($element['entities'][$key]['form']['inline_entity_form']['#process'], [get_class($this), 'buildPreviousFormActions']);
        }
        array_push($element['entities'][$key]['form']['inline_entity_form']['#process'], [get_class($this), 'renameFormActions']);
      }
    }

    return $element;
  }

  /**
   * Adds previous button to the inline entity form.
   *
   * @param array $element
   *   Form array structure.
   */
  public static function buildPreviousFormActions($element) {
    $actions = $element['actions'];
    unset($element['actions']);
    $element['actions']['ief_edit_previous'] = [
      '#type'  => 'submit',
      '#value' => t('Previous'),
      '#name' => 'ief-edit-submit-' . $element['#ief_row_delta'],
      '#limit_validation_errors' => [$element['#parents']],
      '#attributes' => ['class' => ['ief-entity-submit']],
      '#ajax' => [
        'callback' => 'inline_entity_form_get_element',
        'wrapper'  => 'inline-entity-form-' . $element['#ief_id'],
      ],
      '#submit' => [
        [ '\Drupal\inline_entity_form\Element\InlineEntityForm', 'triggerIefSubmit'],
        'inline_entity_form_close_child_forms',
        'inline_entity_form_close_form',
        'inline_entity_form_open_row_form',
      ],
      '#ief_row_delta' => $element['#ief_row_delta'] - 1,
      '#ief_row_form' => 'edit',
    ];
    $element['actions'] = array_merge($element['actions'], $actions);

    return $element;
  }

  /**
   * Adds next button to the inline entity form.
   *
   * @param array $element
   *   Form array structure.
   */
  public static function buildNextFormActions($element) {
    $actions = $element['actions'];
    unset($element['actions']);
    $element['actions']['ief_edit_next'] = [
      '#type'  => 'submit',
      '#value' => t('Next'),
      '#name' => 'ief-edit-submit-' . $element['#ief_row_delta'],
      '#limit_validation_errors' => [$element['#parents']],
      '#attributes' => ['class' => ['ief-entity-submit']],
      '#ajax' => [
        'callback' => 'inline_entity_form_get_element',
        'wrapper'  => 'inline-entity-form-' . $element['#ief_id'],
      ],
      '#submit' => [
        [ '\Drupal\inline_entity_form\Element\InlineEntityForm', 'triggerIefSubmit'],
        'inline_entity_form_close_child_forms',
        'inline_entity_form_close_form',
        'inline_entity_form_open_row_form',
      ],
      '#ief_row_delta' => $element['#ief_row_delta'] + 1,
      '#ief_row_form' => 'edit',
    ];
    $element['actions'] = array_merge($element['actions'], $actions);

    return $element;
  }

  /**
   * Rename action buttons.
   *
   * @param array $element
   *   Form array structure.
   */
  public static function renameFormActions($element) {
    if (!empty($element['actions']['ief_edit_save'])) {
      $element['actions']['ief_edit_save']['#value'] = t('Finish');
    }

    return $element;
  }

}
