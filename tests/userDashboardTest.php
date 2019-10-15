<?php
namespace Tests;

use StatonLab\TripalTestSuite\DBTransaction;
use StatonLab\TripalTestSuite\TripalTestCase;

class userDashboardTest extends TripalTestCase {
  // Uncomment to auto start and rollback db transactions per test method.
  use DBTransaction;

  /**
   * Tests tripal_hq_imports_tripal_hq_user_dashboard_alter().
   */
  public function testUserDashboard() {
    module_load_include('inc', 'tripal_hq', 'includes/tripal_hq_user_dashboard.form');

    // Add a submission for better testing.
    $data = serialize([1,2,3]);
    global $user;
    db_insert('tripal_hq_importer_submission')
      ->fields([
        'uid' => $user->uid,
        'nid' => 1,
        'class' => 'GFF3Importer',
        'data' => $data,
        'status' => 'pending',
        'created_at' => time(),
        'updated_at' => time(),
      ])->execute();

    // We run the Tripal HQ user dashboard which should trigger our alter fn.
    $form = [];
    $form_state = [];
    $form = tripal_hq_user_dashboard_form($form, $form_state);
    $this->assertArrayHasKey('my_importer_submissions', $form,
      "Our submissions table is not in the page.");

  }
}
