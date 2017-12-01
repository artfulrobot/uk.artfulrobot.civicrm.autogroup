<?php

use CRM_AutoGroup_ExtensionUtil as E;
use Civi\Test\HeadlessInterface;
use Civi\Test\HookInterface;
use Civi\Test\TransactionalInterface;

/**
 * Tests expected functionality.
 *
 * Tips:
 *  - With HookInterface, you may implement CiviCRM hooks directly in the test class.
 *    Simply create corresponding functions (e.g. "hook_civicrm_post(...)" or similar).
 *  - With TransactionalInterface, any data changes made by setUp() or test****() functions will
 *    rollback automatically -- as long as you don't manipulate schema or truncate tables.
 *    If this test needs to manipulate schema or truncate tables, then either:
 *       a. Do all that using setupHeadless() and Civi\Test.
 *       b. Disable TransactionalInterface, and handle all setup/teardown yourself.
 *
 * @group headless
 */
class AutoGroupTest extends \PHPUnit_Framework_TestCase implements HeadlessInterface, HookInterface, TransactionalInterface {
  public $fixtures = [
    'contacts' => [
      'staff' => [
        'first_name' => 'Wilma',
        'last_name' => 'Staff',
        'contact_type' => 'Individual',
      ],
      'new_person' => [
        'first_name' => 'Betty',
        'last_name' => 'Added',
        'contact_type' => 'Individual',
      ],
    ],
    'groups' => [
      'addable1' => [
        'title' => 'Addable group 1',
      ],
      'notaddable' => [
        'title' => 'Not addable',
      ],
      'addable2' => [
        'title' => 'Addable group 2',
      ],
    ]
  ];
  public $contacts = [];
  public $groups = [];
  public function setUpHeadless() {
    // Civi\Test has many helpers, like install(), uninstall(), sql(), and sqlFile().
    // See: https://github.com/civicrm/org.civicrm.testapalooza/blob/master/civi-test.md
    return \Civi\Test::headless()
      ->installMe(__DIR__)
      ->apply();
  }

  public function setUp() {
    parent::setUp();
    // Create user contact.
    $result = civicrm_api3('Contact', 'create', $this->fixtures['contacts']['staff']);
    $this->fixtures['contacts']['staff']['contact_id'] = $result['id'];

    // Create groups; add 'staff' contact in to all of them.
    foreach ($this->fixtures['groups'] as $key => $group) {
      $result = civicrm_api3('Group', 'create', $group);
      $this->fixtures['groups'][$key]['group_id'] = $result['id'];
      civicrm_api3('GroupContact', 'create', [
        'group_id' => $result['id'],
        'contact_id' => $this->fixtures['contacts']['staff']['contact_id'],
        'status' => 'Added',
      ]);
    }

    // Make our extension believe 'staff' is logged in.
    CRM_AutoGroup::singleton()->setLoggedInContactID($this->fixtures['contacts']['staff']['contact_id']);

  }

  public function tearDown() {
    parent::tearDown();
  }

  /**
   * Test that the correct groups are copied.
   */
  public function testAddingIndividualCopiesCorrectGroups() {

    // Configure the extension.
    $groups = Civi::settings()->set('autogroup_groups_to_copy', [
      $this->fixtures['groups']['addable1']['group_id'],
      $this->fixtures['groups']['addable2']['group_id'],
    ]);


    $result = civicrm_api3('Contact', 'create', $this->fixtures['contacts']['new_person']);
    $this->fixtures['contacts']['new_person']['contact_id'] = $result['id'];

    foreach (['addable1', 'addable2'] as $_) {
      $this->assertContactInGroup($result['id'], $this->fixtures['groups'][$_]['group_id']);
    }
    foreach (['notaddable'] as $_) {
      $this->assertContactNotInGroup($result['id'], $this->fixtures['groups'][$_]['group_id']);
    }
  }

  /**
   * Example: Test that no groups are added when unconfigured.
   */
  public function testUnconfiguredExtensionDoesNothing() {

    $result = civicrm_api3('Contact', 'create', $this->fixtures['contacts']['new_person']);
    $this->fixtures['contacts']['new_person']['contact_id'] = $result['id'];

    foreach ($this->fixtures['groups'] as $_ => $g) {
      $this->assertContactNotInGroup($result['id'], $this->fixtures['groups'][$_]['group_id']);
    }
  }

  /**
   * Configure extension to add the addable groups, but remove the staff contact from one group.
   */
  public function testOnlyAddsAddedGroups() {
    // Configure the extension.
    $groups = Civi::settings()->set('autogroup_groups_to_copy', [
      $this->fixtures['groups']['addable1']['group_id'],
      $this->fixtures['groups']['addable2']['group_id'],
    ]);

    // Remove the staff contact from one of the groups.
    $result = civicrm_api3('GroupContact', 'create', [
      'contact_id' => $this->fixtures['contacts']['staff']['contact_id'],
      'group_id' => $this->fixtures['groups']['addable2']['group_id'],
      'status' => 'Removed',
    ]);

    $result = civicrm_api3('Contact', 'create', $this->fixtures['contacts']['new_person']);
    $this->fixtures['contacts']['new_person']['contact_id'] = $result['id'];

    foreach (['addable1'] as $_) {
      $this->assertContactInGroup($result['id'], $this->fixtures['groups'][$_]['group_id']);
    }
    foreach (['addable2', 'notaddable'] as $_) {
      $this->assertContactNotInGroup($result['id'], $this->fixtures['groups'][$_]['group_id']);
    }
  }

  /**
   * Assert contact is in group.
   *
   * @param int $contact_id
   * @param int $group_id
   */
  protected function assertContactInGroup($contact_id, $group_id) {
    $result = civicrm_api3('Contact', 'get', [
      'id' => $contact_id,
      'group' => $group_id,
      'return' => 'id',
    ]);
    $this->assertEquals(1, $result['count']);
  }
  /**
   * Assert contact is not in group.
   *
   * @param int $contact_id
   * @param int $group_id
   */
  protected function assertContactNotInGroup($contact_id, $group_id) {
    $result = civicrm_api3('Contact', 'get', [
      'id' => $contact_id,
      'group' => $group_id,
      'return' => 'id',
    ]);
    $this->assertEquals(0, $result['count']);
  }
}
