<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Build_base extends CI_Migration {

  public function up()
  {
    /* CREATE DATA */
    $this->_create_local_authorities_table();
    $this->_create_local_services_table();
    $this->_create_service_urls_table();
    $this->_create_service_check_queue_table();
    $this->_create_imports_table();

  }

  public function down()
  {
    $this->dbforge->drop_table('local_authorities');
    $this->dbforge->drop_table('local_services');
    $this->dbforge->drop_table('service_urls');
    $this->dbforge->drop_table('service_check_queue');
    $this->dbforge->drop_table('imports');
  }

  function _create_local_authorities_table() {

    $this->dbforge->add_field('id');
    $this->dbforge->add_field(array(
      'snac' => array(
        'type'          => 'VARCHAR',
        'constraint'    => 4
      ),
      'name' => array(
        'type'          => 'VARCHAR',
        'constraint'    => 255
      ),
      'homepage_url' => array(
        'type'          => 'VARCHAR',
        'constraint'    => 255
      ),
      'contact_url' => array(
        'type'          => 'VARCHAR',
        'constraint'    => 255
      ),
      'postcode' => array(
        'type'          => 'VARCHAR',
        'constraint'    => 10
      ),
      'type' => array(
        'type'          => 'VARCHAR',
        'constraint'    => 5
      )
    ));
    $this->dbforge->add_field('created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
    $this->dbforge->create_table('local_authorities');
  }

  function _create_local_services_table() {
    $this->dbforge->add_field('id');
    $this->dbforge->add_field(array(
      'description' => array(
        'type'          => 'VARCHAR',
        'constraint'    => 255,
      ),
      'provided_district' => array(
        'type'          => 'TINYINT',
        'constraint'    => 1,
        'default'       => 0
      ),
      'provided_county' => array(
        'type'          => 'TINYINT',
        'constraint'    => 1,
        'default'       => 0
      ),
      'provided_unitary' => array(
        'type'          => 'TINYINT',
        'constraint'    => 1,
        'default'       => 0
      )
    ));
    $this->dbforge->add_field('created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
    $this->dbforge->create_table('local_services');
  }

  function _create_service_urls_table() {
    $this->dbforge->add_field('id');
    $this->dbforge->add_field(array(
      'snac' => array(
        'type'          => 'VARCHAR',
        'constraint'    => 4,
      ),
      'lgsl' => array(
        'type'          => 'INT',
        'constraint'    => 11
      ),
      'lgil' => array(
        'type'          => 'INT',
        'constraint'    => 11
      ),
      'url' => array(
        'type'          => 'VARCHAR',
        'constraint'    => 255
      ),
      'domain' => array(
        'type'          => 'VARCHAR',
        'constraint'    => 255
      ),
      'http_status' => array(
        'type'          => "INT",
        'constraint'    => 11,
        'default'       => 0
      ),
      'content_looks_like' => array(
        'type'          => "INT",
        'constraint'    => 11,
        'default'       => 0
      ),
      'can_404' => array(
        'type'          => 'TINYINT',
        'constraint'    => 1,
        'default'       => 1
      ),
      'last_tested' => array(
        'type'          => 'TIMESTAMP',
        'default'       => '0000-00-00 00:00:00'
      )
    ));
    $this->dbforge->add_field('created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
    $this->dbforge->create_table('service_urls');
  }

  function _create_service_check_queue_table() {
    $this->dbforge->add_field('id');
    $this->dbforge->add_field(array(
      'lgsl' => array(
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
    $this->dbforge->create_table('service_check_queue');
  }

  function _create_imports_table() {
    $this->dbforge->add_field('id');
    $this->dbforge->add_field(array(
      'service' => array(
        'type'          => "ENUM('services','service_urls','authorities')",
        'default'       => 'service_urls'
      )
    ));
    $this->dbforge->add_field('created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
    $this->dbforge->create_table('imports');
  }

}