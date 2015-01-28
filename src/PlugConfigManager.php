<?php

/**
 * @file
 * Contains \Drupal\plug_field\PlugFieldManager.
 */

namespace Drupal\plug_config;

use Drupal\Core\Plugin\DefaultPluginManager;
use Drupal\plug\Util\Module;

class PlugConfigManager extends DefaultPluginManager {

  /**
   * Constructs PlugConfigManager.
   *
   * @param \Traversable $namespaces
   *   An object that implements \Traversable which contains the root paths
   *   keyed by the corresponding namespace to look for plugin implementations.
   * @param \DrupalCacheInterface $cache_backend
   *   Cache backend instance to use.
   */
  public function __construct(\Traversable $namespaces, \DrupalCacheInterface $cache_backend) {
    parent::__construct('Plugin/Config', $namespaces, 'Drupal\plug_config\Plugin\Config\ConfigInterface', '\Drupal\plug_config\Annotation\Config');
    $this->setCacheBackend($cache_backend, 'config_plugins');
    $this->alterInfo('config_plugin');
  }

  /**
   * PlugConfigManagerBase factory method.
   *
   * @param string $bin
   *   The cache bin for the plugin manager.
   *
   * @return \Drupal\Component\Plugin\PluginManagerInterface
   *   The created manager.
   */
  public static function create($bin = 'cache') {
    return new static(Module::getNamespaces(), _cache_get_object($bin));
  }

  /**
   * PlugConfigManagerBase pseudo service.
   *
   * @param string $bin
   *   The cache bin for the plugin manager.
   *
   * @return \Drupal\Component\Plugin\PluginManagerInterface
   *   The created manager.
   */
  public static function get($bin = 'cache') {
    $manager = &drupal_static(get_called_class() . __FUNCTION__);
    if (!isset($manager)) {
      $manager = static::create($bin);
    }
    return $manager;
  }

  /**
   * Finds plugin definitions.
   *
   * @return array
   *   List of definitions to store in cache.
   */
  protected function findDefinitions() {
    // Convert "field_types" key to "field types", given that annotations don't
    // allow spaces and add default settings.
    return array_map(function($definition) {
      foreach ($definition as $property_name => &$item) {
        if (($new_property = preg_replace('/_/', ' ', $property_name)) && $new_property != $property_name) {
          $definition[$new_property] = $definition[$property_name];
          unset($definition[$property_name]);
        }
        if (is_array($item)) {
          foreach ($item as $item_id => $nested_item) {
            if (($new_property = preg_replace('/_/', ' ', $item_id)) && $new_property != $item_id) {
              $item[$new_property] = $item[$item_id];
              unset($item[$item_id]);
            }
          }
        }
      }
      return $definition + array(
        'controller class' => 'EntityAPIControllerExportable',
        'base table' => $definition['id'],
        'module' => $definition['provider'],
        'entity class' => $definition['class'],
        'uri callback' => array($definition['class'], 'uriCallback'),
        'access callback' => array($definition['class'], 'accessCallback'),
        'exportable' => TRUE,
      );
    }, parent::findDefinitions());
  }

}
