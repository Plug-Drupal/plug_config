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
 *   controller_class = "\Drupal\plug_config_example\Plugin\Config\ConfigExampleController",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "label",
 *     "name" = "name"
 *   },
 *   admin_ui = {
 *     "path" = "admin/plug-config-example/config",
 *     "file" = "plug_config_example.entity.inc",
 *   },
 * )
 */
class ConfigExample extends ConfigBase {

  public static function schema() {
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
        'status' => array(
          'description' => 'The exportable status of the entity.',
          'type' => 'int',
          'not null' => TRUE,
          'default' => 0x01,
          'size' => 'tiny',
        ),
        'module' => array(
          'description' => 'The name of the providing module if the entity has been defined in code.',
          'type' => 'varchar',
          'length' => 255,
          'not null' => FALSE,
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

  public static function uriCallback($entity) {
    return array(
      'path' => 'config_example/' . $entity->id,
    );
  }

  public static function accessCallback($op, $entity_type, $entity = NULL, $account = NULL) {
    return TRUE;
  }
}