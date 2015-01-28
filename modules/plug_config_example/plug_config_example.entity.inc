<?php

/**
 * Generates the Contract type editing form.
 */
function config_example_form($form, &$form_state, $config_example, $op = 'edit') {

  if ($op == 'clone') {
    $config_example->label .= ' (cloned)';
    $config_example->type = '';
  }

  $form['label'] = array(
    '#title' => t('Label'),
    '#type' => 'textfield',
    '#default_value' => $config_example->label,
    '#description' => t('The human-readable name of this Contract type.'),
    '#required' => TRUE,
    '#size' => 30,
  );

  // Machine-readable type name.
  $form['machine_name'] = array(
    '#type' => 'machine_name',
    '#default_value' => isset($config_example->type) ? $config_example->type : '',
    '#maxlength' => 32,
    '#disabled' => $config_example->isLocked() && $op != 'clone',
    '#machine_name' => array(
      'exists' => 'config_example_load',
      'source' => array('label'),
    ),
    '#description' => t('A unique machine-readable name for this Contract type. It must only contain lowercase letters, numbers, and underscores.'),
  );

  $form['actions'] = array('#type' => 'actions');
  $form['actions']['submit'] = array(
    '#type' => 'submit',
    '#value' => t('Save Contract type'),
    '#weight' => 40,
  );

  if (!$config_example->isLocked() && $op != 'add' && $op != 'clone') {
    $form['actions']['delete'] = array(
      '#type' => 'submit',
      '#value' => t('Delete Contract type'),
      '#weight' => 45,
      '#limit_validation_errors' => array(),
      '#submit' => array('config_example_form_submit_delete'),
    );
  }
  return $form;
}

/**
 * Submit handler for creating/editing Contract type.
 */
function config_example_form_submit(&$form, &$form_state) {
  $config_example = entity_ui_form_submit_build_entity($form, $form_state);
  // Save and go back.
  config_example_save($config_example);

  if ($form_state['op'] == 'edit') {
    drupal_set_message(t('Contract type @type updated', array('@type' => $config_example->type)));
  }
  else {
    drupal_set_message(t('Contract type @type created', array('@type' => $config_example->type)));
  }

  // Redirect user back to list of Contract types.
  $form_state['redirect'] = 'admin/structure/contract-types';
}

/**
 * Submit handler for deleting Contract type.
 */
function config_example_form_submit_delete(&$form, &$form_state) {
  $form_state['redirect'] = 'admin/structure/contract-types/' . $form_state['config_example']->type . '/delete';
}

/**
 * Contract type delete form.
 */
function config_example_form_delete_confirm($form, &$form_state, $config_example) {
  $form_state['config_example'] = $config_example;
  // Always provide entity id in the same form key as in the entity edit form.
  $form['config_example_id'] = array('#type' => 'value', '#value' => entity_id('config_example', $config_example));
  return confirm_form($form,
    t('Are you sure you want to delete Contract type %title?', array('%title' => entity_label('config_example_type', $config_example))),
    'admin/structure/contract-types/' . entity_id('config_example', $config_example),
    t('This action cannot be undone.'),
    t('Delete'),
    t('Cancel')
  );
}

/**
 * Contract type delete form submit handler.
 */
function config_example_form_delete_confirm_submit($form, &$form_state) {
  $config_example = $form_state['config_example'];
  config_example_delete($config_example);

  watchdog('config_example', '@type: deleted %title.', array('@type' => $config_example->type, '%title' => $config_example->label));
  drupal_set_message(t('@type %title has been deleted.', array('@type' => $config_example->type, '%title' => $config_example->label)));

  $form_state['redirect'] = 'admin/structure/contract-types';
}
