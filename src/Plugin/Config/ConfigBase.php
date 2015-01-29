<?php

/**
 * @file
 * Contains \Drupal\plug_config\Plugin\Config\ConfigBase.
 */

namespace Drupal\plug_config\Plugin\Config;


abstract class ConfigBase extends \Entity implements ConfigInterface {

  public static function schema() {
    $config_schema = static::configSchema();
    $config_schema['fields']['status'] = array(
      'description' => 'The exportable status of the entity.',
      'type' => 'int',
      'not null' => TRUE,
      'default' => 0x01,
      'size' => 'tiny',
    );
    $config_schema['fields']['module'] = array(
      'description' => 'The name of the providing module if the entity has been defined in code.',
      'type' => 'varchar',
      'length' => 255,
      'not null' => FALSE,
    );
    return $config_schema;
  }

}