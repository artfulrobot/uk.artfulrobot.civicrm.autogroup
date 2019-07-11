<?php

require_once 'autogroup.civix.php';
use CRM_Autogroup_ExtensionUtil as E;

/**
 * Implements hook_civicrm_config().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_config
 */
function autogroup_civicrm_config(&$config) {
  _autogroup_civix_civicrm_config($config);
}

/**
 * Implements hook_civicrm_xmlMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_xmlMenu
 */
function autogroup_civicrm_xmlMenu(&$files) {
  _autogroup_civix_civicrm_xmlMenu($files);
}

/**
 * Implements hook_civicrm_install().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_install
 */
function autogroup_civicrm_install() {
  _autogroup_civix_civicrm_install();
}

/**
 * Implements hook_civicrm_postInstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_postInstall
 */
function autogroup_civicrm_postInstall() {
  _autogroup_civix_civicrm_postInstall();
}

/**
 * Implements hook_civicrm_uninstall().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_uninstall
 */
function autogroup_civicrm_uninstall() {
  _autogroup_civix_civicrm_uninstall();
}

/**
 * Implements hook_civicrm_enable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_enable
 */
function autogroup_civicrm_enable() {
  _autogroup_civix_civicrm_enable();
}

/**
 * Implements hook_civicrm_disable().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_disable
 */
function autogroup_civicrm_disable() {
  _autogroup_civix_civicrm_disable();
}

/**
 * Implements hook_civicrm_upgrade().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_upgrade
 */
function autogroup_civicrm_upgrade($op, CRM_Queue_Queue $queue = NULL) {
  return _autogroup_civix_civicrm_upgrade($op, $queue);
}

/**
 * Implements hook_civicrm_managed().
 *
 * Generate a list of entities to create/deactivate/delete when this module
 * is installed, disabled, uninstalled.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_managed
 */
function autogroup_civicrm_managed(&$entities) {
  _autogroup_civix_civicrm_managed($entities);
}

/**
 * Implements hook_civicrm_caseTypes().
 *
 * Generate a list of case-types.
 *
 * Note: This hook only runs in CiviCRM 4.4+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_caseTypes
 */
function autogroup_civicrm_caseTypes(&$caseTypes) {
  _autogroup_civix_civicrm_caseTypes($caseTypes);
}

/**
 * Implements hook_civicrm_angularModules().
 *
 * Generate a list of Angular modules.
 *
 * Note: This hook only runs in CiviCRM 4.5+. It may
 * use features only available in v4.6+.
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_angularModules
 */
function autogroup_civicrm_angularModules(&$angularModules) {
  _autogroup_civix_civicrm_angularModules($angularModules);
}

/**
 * Implements hook_civicrm_alterSettingsFolders().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_alterSettingsFolders
 */
function autogroup_civicrm_alterSettingsFolders(&$metaDataFolders = NULL) {
  _autogroup_civix_civicrm_alterSettingsFolders($metaDataFolders);
}

/**
 * Ensure newly created contacts are added to the same groups as the creator.
 */
function autogroup_civicrm_post($op, $objectName, $objectId, &$objectRef) {
  if ($op === 'create' && in_array($objectName, ['Individual', 'Organization', 'Household'])) {

    // Which groups do we consider for adding?
    $groups = Civi::settings()->get('autogroup_groups_to_copy');
    if (empty($groups)) {
      // Not configured.
      return;
    }

    // Who is the current user?
    $my_contact_id = CRM_AutoGroup::singleton()->getLoggedInContactID();
    if (!$my_contact_id) {
      // Hmmm. Not logged in? Do nothing.
      return;
    }

    // Which of these groups is the current user in?
    foreach ($groups as $group_id) {
      $result = civicrm_api3('Contact', 'get', ['group' => $group_id, 'id' => $my_contact_id, 'return' => 'id']);
      if ($result['count']) {
        // Add the newly created contact into this group.
        civicrm_api3('GroupContact', 'create', ['contact_id' => $objectId, 'group_id' => $group_id, 'status' => 'Added']);
      }
    }
  }
}
// --- Functions below this ship commented out. Uncomment as required. ---

/**
 * Implements hook_civicrm_preProcess().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_preProcess
 *
function autogroup_civicrm_preProcess($formName, &$form) {

} // */

/**
 * Implements hook_civicrm_navigationMenu().
 *
 * @link http://wiki.civicrm.org/confluence/display/CRMDOC/hook_civicrm_navigationMenu
 *
function autogroup_civicrm_navigationMenu(&$menu) {
  _autogroup_civix_insert_navigation_menu($menu, NULL, array(
    'label' => E::ts('The Page'),
    'name' => 'the_page',
    'url' => 'civicrm/the-page',
    'permission' => 'access CiviReport,access CiviContribute',
    'operator' => 'OR',
    'separator' => 0,
  ));
  _autogroup_civix_navigationMenu($menu);
} // */
