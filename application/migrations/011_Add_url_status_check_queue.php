<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_url_status_check_queue extends CI_Migration {

  public function up()
  {
    /* CREATE DATA */
    $this->_create_url_status_check_queue_table();
  }

  public function down()
  {
    $this->dbforge->drop_table('url_status_check_queue');
  }

  function _create_url_status_check_queue_table() {
    $this->dbforge->add_field('id');
    $this->dbforge->add_field(array(
      'url_id' => array(
        'type'          => 'INT',
        'constraint'    => 11,
      ),
      'requested_by' => array(
        'type'        => "ENUM('url_import', 'service_import', 'regular_check', 'url_spotcheck', 'service_spotcheck', 'other')",
        'default'     => 'other'
      ),
      'locked' => array(
        'type'          => 'INT',
        'constraint'    => 11,
        'default'       => 0
      )
    ));
    $this->dbforge->add_field('created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
    $this->dbforge->create_table('url_status_check_queue');
  }

}