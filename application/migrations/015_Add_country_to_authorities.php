<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_country_to_authorities extends CI_Migration {

  public function up()
  {
    $fields = array(
      'country' => array(
        'type'          => "VARCHAR(20)",
        'default'       => ''
      )
    );
    $this->dbforge->add_column('local_authorities', $fields);

  }

  public function down()
  {
    $this->dbforge->drop_column('local_authorities','country');
  }

}
