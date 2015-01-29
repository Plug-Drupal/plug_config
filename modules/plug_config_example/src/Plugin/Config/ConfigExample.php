<?php

namespace Drupal\plug_config_example\Plugin\Config;


use Drupal\plug_config\Plugin\Config\ConfigBase;

/**
 * Class ConfigExample
 * @package Drupal\plug_config_example\Plugin\Config
 *
 * @Config(
 *   id = "config_example",
 *   label = "Config Example",
 *   controller_class = "\Drupal\plug_config\Plugin\Config\DefaultConfigController",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "name" = "machine_name"
 *   },
 *   admin_ui = {
 *     "controller class" = "\Drupal\plug_config\Plugin\Config\DefaultConfigUIController",
 *     "path" = "admin/plug-config-example/config",
 *   },
 * )
 */
class ConfigExample extends ConfigBase {

  public static function configSchema() {
    return array(
      'description' => 'Stores all search servers created through the Search API.',
      'fields' => array(
        'id' => array(
          'description' => 'The primary identifier for a server.',
          'type' => 'serial',
          'unsigned' => TRUE,
          'not null' => TRUE,
        ),
        'name' => array(
          'description' => 'The displayed name for a server.',
          'type' => 'varchar',
          'length' => 50,
          'not null' => TRUE,
        ),
        'machine_name' => array(
          'description' => 'The machine name for a server.',
          'type' => 'varchar',
          'length' => 50,
          'not null' => TRUE,
        ),
      ),
      'unique keys' => array(
        'machine_name' => array('machine_name'),
      ),
      'primary key' => array('id'),
    );
  }

  function isLocked() {
    return isset($this->status) && empty($this->is_new) && (($this->status & ENTITY_IN_CODE) || ($this->status & ENTITY_FIXED));
  }

  public static function accessCallback($op, $entity_type, $entity = NULL, $account = NULL) {
    return TRUE;
  }

  public static function form($form, &$form_state, $config_entity, $op = 'edit') {
    $entity_info = $config_entity->entityInfo();
    if ($op == 'clone') {
      $config_entity->name .= ' (cloned)';
      $config_entity->machine_name = '';
    }

    $form['name'] = array(
      '#title' => t('Label'),
      '#type' => 'textfield',
      '#default_value' => $config_entity->name,
      '#description' => t('The human-readable name of this Contract type.'),
      '#required' => TRUE,
      '#size' => 30,
    );

    // Machine-readable type name.
    $form['machine_name'] = array(
      '#type' => 'machine_name',
      '#default_value' => isset($config_entity->machine_name) ? $config_entity->machine_name : '',
      '#maxlength' => 32,
      '#disabled' => $config_entity->isLocked() && $op != 'clone',
      '#machine_name' => array(
        'exists' => 'plug_config_validate_machine_name',
        'source' => array('name'),
      ),
      '#description' => t('A unique machine-readable name for this Contract type. It must only contain lowercase letters, numbers, and underscores.'),
    );

    $form['actions'] = array('#type' => 'actions');
    $form['actions']['submit'] = array(
      '#type' => 'submit',
      '#value' => t('Save @type', array('@type' => $entity_info['label'])),
      '#weight' => 40,
    );

    if (!$config_entity->isLocked() && $op != 'add' && $op != 'clone') {
      $form['actions']['delete'] = array(
        '#type' => 'submit',
        '#value' => t('Delete @type', array('@type' => $entity_info['label'])),
        '#weight' => 45,
        '#limit_validation_errors' => array(),
        '#submit' => array('config_example_form_submit_delete'),
      );
    }
    return $form;
  }

  public static function formSubmit($form, &$form_state) {
    $config_example = entity_ui_form_submit_build_entity($form, $form_state);
    $entity_info = $config_example->entityInfo();

    // Save and go back.
    entity_save($config_example->entityType(), $config_example);

    if ($form_state['op'] == 'edit') {
      drupal_set_message(t('@label @type updated', array('@label' => $entity_info['label'], '@type' => $config_example->name)));
    }
    else {
      drupal_set_message(t('@label @type created', array('@label' => $entity_info['label'], '@type' => $config_example->name)));
    }

    if (isset($entity_info['admin ui']['path'])) {
      // Redirect user back to list of Contract types.
      $form_state['redirect'] = $entity_info['admin ui']['path'];
    }
  }
}