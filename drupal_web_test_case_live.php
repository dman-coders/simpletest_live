<?php
/**
 * @file
 * Drop-in stub for DrupalWebTestCase that does NOT do the clean-room database.
 */

/**
 * Class DrupalWebTestCase.
 */
class DrupalWebTestCaseLive extends DrupalWebTestCase {

  protected $originalPrefix;

  /**
   * Implements setUp().
   *
   * @inheritdoc
   */
  public function setUp() {

    $modules = func_get_args();
    if (isset($modules[0]) && is_array($modules[0])) {
      $modules = $modules[0];
    }

    // FOR TESTING TESTING -
    // Use the current live DB, not simpletests fake one!
    // Skip the normal method by refusing to call parent::setup() at all.
    // If this goes wrong it will nuke your current site!
    // It is pretty sure to leave trash behind (dummy users etc)
    // as there are limited per-test cleanups.
    // Simpletest assumes you will discard the db, but we are avoiding that.
    // Based on
    // http://www.trellon.com/content/blog/forcing-simpletest-use-live-database
    //
    // Things to know: When doing this, if you end up with an error logged,
    // that can stick around and trigger fatal complaints on subsequent tests!
    // Curiously, the error is logged in your files/simpletest/simpletest.log
    // DELETE THAT FILE, and flush watchdog to prevent this frustrating issue.

    // Skip most simpletest bootstrap creation steps.
    // Do the other bits that are needed though, to pretend we are OK.
    $this->originalPrefix = $GLOBALS['db_prefix'];
    $this->databasePrefix = 'stub';
    $test_info = &$GLOBALS['drupal_test_info'];
    $test_info['test_run_id'] = $this->databasePrefix;
    $this->prepareEnvironment();
    if ($modules) {
      $success = module_enable($modules, TRUE);
      $this->assertTrue($success, t('Enabled modules: %modules', array('%modules' => implode(', ', $modules))));
    }
    $this->resetAll();
    $this->setup = TRUE;
    $this->pass('Using the LIVE database, not a temporary one.', 'Debug');
  }

  /**
   * Remove some traces of this test run.
   *
   * Normally only the parent::tearDown() runs and destroys the current
   * database and environment.
   * We do not do that if running hot.
   */
  public function tearDown() {
    // There may be some settings that were in setUp or prepareEnvironment
    // that need to be reset back.
    $this->databasePrefix = $this->originalPrefix;
    // Reset public files directory.
    $GLOBALS['conf']['file_public_path'] = $this->originalFileDirectory;
  }
}
