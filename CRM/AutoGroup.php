<?php
/**
 * @file
 * The main do-ing code for AutoGroup.
 */
class CRM_AutoGroup
{
  /** @var int|NULL Used in testing.*/
  protected $logged_in_contact_id_override;
  /** @var CRM_AutoGroup */
  protected static $singleton;
  /** Access only via singleton() */
  protected function __construct() {}
  /**
   * Access the object
   */
  public static function singleton() {
    if (!isset(static::$singleton)) {
      static::$singleton = new static();
    }
    return static::$singleton;
  }
  /**
   * Returns the logged in contact Id, allowing for tests to override.
   *
   * @return int
   */
  public function getLoggedInContactID() {
    if (isset($this->logged_in_contact_id_override)) {
      // The test is faking this, just return what we've got.
      return $this->logged_in_contact_id_override;
    }
    return CRM_Core_Session::singleton()->getLoggedInContactID();
  }
  /**
   * Set the logged in contact id, for tests.
   *
   * @param int|NULL contact ID. If NULL, the actual logged in contact id will
   * be returned from getLoggedInContactID()
   * @return CRM_AutoGroup
   */
  public function setLoggedInContactID($contact_id) {
    $this->logged_in_contact_id_override = $contact_id;
    return $this;
  }
}
