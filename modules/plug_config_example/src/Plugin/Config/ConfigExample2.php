<?php

namespace Drupal\plug_config_example\Plugin\Config;


/**
 * Class ConfigExample2
 * @package Drupal\plug_config_example\Plugin\Config
 *
 * @Config(
 *   id = "config_examples2",
 *   label = "Config Example 2",
 *   controller_class = "\Drupal\plug_config\Plugin\Config\DefaultConfigController",
 *   entity_keys = {
 *     "id" = "id",
 *     "label" = "name",
 *     "name" = "machine_name"
 *   },
 *   admin_ui = {
 *     "controller class" = "\Drupal\plug_config\Plugin\Config\DefaultConfigUIController",
 *     "path" = "admin/plug-config-example2/config",
 *   },
 * )
 */
class ConfigExample2 extends ConfigExample {

}