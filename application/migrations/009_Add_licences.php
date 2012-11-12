<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_Add_licences extends CI_Migration {

  public function up()
  {
    /* CREATE DATA */
    $this->_create_licence_table();
    $this->_alter_imports_table("ENUM('services','service_urls','authorities','licences')");
  }

  public function down()
  {
    $this->dbforge->drop_table('licences');
    $this->_remove_licence_imports();
    $this->_alter_imports_table("ENUM('services','service_urls','authorities')");
  }

  function _create_licence_table() {
    $this->dbforge->add_field('id');
    $this->dbforge->add_field(array(
      'licence_identifier' => array(
        'type'          => 'VARCHAR',
        'constraint'    => 15,
      ),
      'name' => array(
        'type'          => 'VARCHAR',
        'constraint'    => 255
      ),
      'description' => array(
        'type'          => 'VARCHAR',
        'constraint'    => 255
      ),
      'slug' => array(
        'type'          => 'VARCHAR',
        'constraint'    => 255
      ),
      'transaction_url' => array(
        'type'          => 'VARCHAR',
        'constraint'    => 255
      ),
      'licence_type' => array(
        'type'          => "ENUM(
          'unknown',
          'non-local',
          'licence-app-comp',
          'licence-app-local')",
        'default'       => 'unknown'
      ),
      'overall_status' => array(
        'type'          => "ENUM('unknown','ok','warning')",
        'default'       => 'unknown'
      )
    ));
    $this->dbforge->add_field('created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
    $this->dbforge->create_table('licences');
  }

  function _alter_imports_table($enum_definition) {
    $fields = array(
      'service'   => array(
        'type' => $enum_definition
      )
    );
    $this->dbforge->modify_column('imports',$fields);
  }

  function _remove_licence_imports() {
    $this->db->where('service','licences');
    $this->db->delete('imports');
  }

}