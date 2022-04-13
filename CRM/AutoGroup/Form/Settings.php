<?php

use CRM_AutoGroup_ExtensionUtil as E;

/**
 * Form controller class
 *
 * @see https://wiki.civicrm.org/confluence/display/CRMDOC/QuickForm+Reference
 */
class CRM_AutoGroup_Form_Settings extends CRM_Admin_Form_Setting {
  /**
   * @var array This is required to select which settings defined in the
   * *.setting.php files are used in this form.
   */
  protected $_settings = [
    'autogroup_groups_to_copy' => CRM_Core_BAO_Setting::SYSTEM_PREFERENCES_NAME,
  ];

  /**
   * Build the form object.
   */
  public function buildQuickForm() {

    parent::buildQuickForm();
    // Without this the default template does nothing.
    $this->assign('elementNames', array_keys($this->_settings));

    // Override the autogroup_groups_to_copy as we need to add groups as the options.
    $e = $this->getElement('autogroup_groups_to_copy');
    $e->setMultiple(TRUE);
    $e->setSize(12);

    // Get list of groups (we exclude mailing or access groups).
    $groups = \Civi\Api4\Group::get()
      ->addSelect('title')
      ->addWhere('group_type', 'IS EMPTY')
      ->addOrderBy('title', 'ASC')
      ->execute();
    foreach ($groups as $group) {
      $e->addOption($group['title'], $group['id']);
    }

  }

}
