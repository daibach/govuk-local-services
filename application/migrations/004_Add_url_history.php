<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_url_history extends CI_Migration {

  public function up()
  {
    /* CREATE DATA */
    $this->_create_url_history_table();

  }

  public function down()
  {
    $this->dbforge->drop_table('url_history');
  }

  function _create_url_history_table() {
    $this->dbforge->add_field('id');
    $this->dbforge->add_field(array(
      'url_id' => array(
        'type'         => 'INT',
        'constraint'   => 11
      ),
      'original' => array(
        'type'         => "VARCHAR",
        'constraint'   => 255
      ),
      'new' => array(
        'type'         => "VARCHAR",
        'constraint'   => 255
      ),
    ));
    $this->dbforge->add_field('created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
    $this->dbforge->create_table('url_history');

  }

}