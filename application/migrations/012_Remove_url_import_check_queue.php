<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Remove_url_import_check_queue extends CI_Migration {

  public function up()
  {
    /* CREATE DATA */
    $this->_remove_url_import_check_queue_table();
  }

  public function down() {
    $this->dbforge->add_field('id');
    $this->dbforge->add_field(array(
      'import' => array(
        'type'          => 'INT',
        'constraint'    => 11,
      ),
      'locked' => array(
        'type'          => 'INT',
        'constraint'    => 11,
        'default'       => 0
      )
    ));
    $this->dbforge->add_field('created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
    $this->dbforge->create_table('url_import_check_queue');
  }

  function _remove_url_import_check_queue_table()
  {
    $this->dbforge->drop_table('url_import_check_queue');
  }

}