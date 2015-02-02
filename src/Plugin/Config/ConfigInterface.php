<?php

/**
 * @file
 * Contains \Drupal\plug_config\Plugin\Config\ConfigInterface.
 */

namespace Drupal\plug_config\Plugin\Config;


interface ConfigInterface {

  /**
   * Retrieves the config entity database schema.
   *
   * @return array
   *   The config entity schema.
   */
  public static function schema();

  /**
   * Checks if the access to the entity.
   *
   * @param string $op
   *   The op to perform on the entity.
   * @param string $entity_type
   *   The entity type of the entity accessing to.
   * @param \stdClass|null $entity
   *   The entity checking access to. Default to NULL.
   * @param \stdClass|null $account
   *   The user account. Default to NULL.
   *
   * @return bool
   *   Boolean indicating if $account is granted to perform $op.
   */
  public static function accessCallback($op, $entity_type, $entity = NULL, $account = NULL);

  /**
   * Generates the entity creation/edition form.
   *
   * @param array $form
   *   The basic form array.
   * @param array $form_state
   *   The form state array.
   * @param \Drupal\plug_config\Plugin\Config\ConfigInterface $config_entity
   *   The entity is being added/edited on this form.
   * @param string $op
   *   The operation to perform on the entity. Can be 'edit', 'add' or 'clone'.
   *
   * @return array
   *   Form structure to define a new entity.
   */
  public static function form($form, &$form_state, ConfigInterface $config_entity, $op = 'edit');

  /**
   * Submit callback for the entity form.
   *
   * @param array $form
   *   The basic form array.
   * @param array $form_state
   *   The form state array.
   */
  public static function formSubmit($form, &$form_state);

}