<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_url_reports extends CI_Migration {

  public function up()
  {
    /* CREATE DATA */
    $this->_create_url_reports_table();

    $fields = array(
      'has_reported_problems' => array(
        'type'          => "TINYINT",
        'default'       => 0
      )
    );
    $this->dbforge->add_column('service_urls', $fields);


  }

  public function down()
  {
    $this->dbforge->drop_column('service_urls','has_reported_problems');
    $this->dbforge->drop_table('url_reports');
  }

  function _create_url_reports_table() {
    $this->dbforge->add_field('id');
    $this->dbforge->add_field(array(
      'url_id' => array(
        'type'        => 'INT',
        'constraint'  => 11
      ),
      'report_type' => array(
        'type'        => "ENUM('broken','wrong_url','other')",
        'default'     => 'wrong_url'
      ),
      'status' => array(
        'type'        => "ENUM('open','superseded','closed')",
        'default'     => 'open'
      ),
      'notes' => array(
        'type'        => "TEXT"
      ),
      'alternative_url' => array(
        'type'        => "VARCHAR",
        'constraint'  => 255
      ),
    ));
    $this->dbforge->add_field('created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
    $this->dbforge->create_table('url_reports');

  }

}