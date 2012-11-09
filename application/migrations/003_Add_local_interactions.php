<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_local_interactions extends CI_Migration {

  public function up()
  {
    /* CREATE DATA */
    $this->_create_local_interactions_table();
    $this->_import_local_interaction_data();

  }

  public function down()
  {
    $this->dbforge->drop_table('local_interactions');
  }

  function _create_local_interactions_table() {

    $this->dbforge->add_field('id');
    $this->dbforge->add_field(array(
      'id' => array(
        'type'        => 'INT',
        'constraint'  => 11,
        'default'     => 0
      ),
      'name' => array(
        'type'        => 'VARCHAR',
        'constraint'  => 255,
        'default'     => ''
      ),
      'shortname' => array(
        'type'        => "VARCHAR",
        'constraint'  => 30,
        'default'     => 0,
      )
    ));
    $this->dbforge->add_field('created_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP');
    $this->dbforge->create_table('local_interactions');
  }

  function _import_local_interaction_data() {

    $data = array(
      array(
        'id'        => 0,
        'name'      => 'Applications for service',
        'shortname' => 'Apply'
      ),
      array(
        'id'        => 1,
        'name'      => 'Booking venues, resources and courses',
        'shortname' => 'Book'
      ),
      array(
        'id'  =>  2,
        'name'  =>  "Collecting revenue",
        'shortname' =>  'Pay'
      ),
      array(
        'id'  =>  3,
        'name'  =>  "Consultation",
        'shortname' =>  'Consultation'
      ),
      array(
        'id'  =>  4,
        'name'  =>  "Paying for goods and services",
        'shortname' =>  'Goods/services'
      ),
      array(
        'id'  =>  5,
        'name'  =>  "Procurement",
        'shortname' =>  'Procurement'
      ),
      array(
        'id'  =>  6,
        'name'  =>  "Providing access to community, professionals or business networks",
        'shortname' =>  'Community access'
      ),
      array(
        'id'  =>  7,
        'name'  =>  "Providing benefits and grants",
        'shortname' =>  'Benefits/grants'
      ),
      array(
        'id'  =>  8,
        'name'  =>  "Providing information",
        'shortname' =>  'Info'
      ),
      array(
        'id'  =>  9,
        'name'  =>  "Regulation",
        'shortname' =>  'Regulation'
      ),
      array(
        'id'  =>  10,
        'name'  =>  "Appealing",
        'shortname' =>  'Appeal'
      ),
      array(
        'id'  =>  11,
        'name'  =>  "Changing circumstances",
        'shortname' =>  'Change circumstances'
      ),
      array(
        'id'  =>  12,
        'name'  =>  "Status checking",
        'shortname' =>  'Check status'
      ),
      array(
        'id'  =>  13,
        'name'  =>  "Complaining",
        'shortname' =>  'Complain'
      ),
      array(
        'id'  =>  14,
        'name'  =>  "Renewing",
        'shortname' =>  'Renew'
      ),
      array(
        'id'  =>  15,
        'name'  =>  "Repairing",
        'shortname' =>  'Repair'
      ),
      array(
        'id'  =>  16,
        'name'  =>  "Replacing",
        'shortname' =>  'Replace'
      ),
      array(
        'id'  =>  17,
        'name'  =>  "Reporting",
        'shortname' =>  'Report'
      ),
      array(
        'id'  =>  20,
        'name'  =>  "Entitlement/eligibility checking",
        'shortname' =>  'Check eligibility'
      ),
      array(
        'id'  =>  21,
        'name'  =>  "Appointment booking",
        'shortname' =>  'Book appointment'
      ),
      array(
        'id'  =>  22,
        'name'  =>  "Venue/facility booking",
        'shortname' =>  'Book venue'
      ),
      array(
        'id'  =>  23,
        'name'  =>  "Reserving a resource",
        'shortname' =>  'Reserve'
      ),
      array(
        'id'  =>  24,
        'name'  =>  "Course booking",
        'shortname' =>  'Book course'
      ),
      array(
        'id'  =>  25,
        'name'  =>  "Statutory consultation",
        'shortname' =>  'Statutory consultation'
      ),
      array(
        'id'  =>  26,
        'name'  =>  "Democratic consultation",
        'shortname' =>  'Democratic consultation'
      ),
      array(
        'id'  =>  27,
        'name'  =>  "Customer surveys",
        'shortname' =>  'Survey'
      ),
      array(
        'id'  =>  28,
        'name'  =>  "Issuing (licence, consent or permit)",
        'shortname' =>  'Licence'
      ),
      array(
        'id'  =>  29,
        'name'  =>  "Enforcement",
        'shortname' =>  'Enforcement'
      ),
      array(
        'id'  =>  30,
        'name'  =>  "Application for exemption",
        'shortname' =>  'Apply for exemption'
      ),
      array(
        'id'  =>  31,
        'name'  =>  "Notifying of incidents or instances",
        'shortname' =>  'Notify'
      ),
      array(
        'id'  =>  32,
        'name'  =>  "Providing periodic returns",
        'shortname' =>  'Provide return'
      ),
      array(
        'id'  =>  33,
        'name'  =>  "Tell us once",
        'shortname' =>  'Tell us once'
      ),
      array(
        'id'  =>  34,
        'name'  =>  "Locate",
        'shortname' =>  'Locate'
      )
    );
    $this->db->insert_batch('local_interactions',$data);

  }

}