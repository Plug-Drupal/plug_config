<?php

/**
 * @file
 * Contains class \Drupal\twisters_contract\Entity\Contract\ContractTypeUIController.
 */

namespace Drupal\twisters_contract\Entity\Contract;

class ContractTypeUIController extends \EntityDefaultUIController {

  /**
   * {@inheritdoc}
   */
  public function hook_menu() {
    $items = parent::hook_menu();
    $items[$this->path]['description'] = 'Manage Contract types.';
    return $items;
  }

}
