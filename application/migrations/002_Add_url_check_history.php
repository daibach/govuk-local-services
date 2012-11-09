<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_url_check_history extends CI_Migration {

  public function up()
  {
    /* CREATE DATA */
    $this->_create_url_check_history_table();

  }

  public function down()
  {
    $this->dbforge->drop_table('url_check_history');
  }

  function _create_url_check_history_table() {

    $this->dbforge->add_field('id');
    $this->dbforge->add_field(array(
      'url_id' => array(
        'type'        => 'INT',
        'constraint'  => 11,
        'default'     => 0
      ),
      'normal_url' => array(
        'type'        => 'VARCHAR',
        'constraint'  => 255,
        'default'     => ''
      ),
      'http_status' => array(
        'type'        => "INT",
        'constraint'  => 11,
        'default'     => 0,
      ),
      'looks_like' => array(
        'type'        => "INT",
        'constraint'  => 11,
        'default'     => 0,
      ),
      'jumbled_url' => array(
        'type'        => 'VARCHAR',
        'constraint'  => 255,
        'default'     => ''
      ),
      'jumbled_http_status' => array(
        'type'        => "INT",
        'constraint'  => 11,
        'default'     => 0,
      )
    ));
    $this->dbforge->add_field('created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
    $this->dbforge->create_table('url_check_history');
  }

}