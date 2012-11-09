<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_url_status_field extends CI_Migration {

  public function up()
  {
    $fields = array(
      'overall_status' => array(
        'type'          => "ENUM('unknown','ok','warning')",
        'default'       => 'unknown'
      )
    );
    $this->dbforge->add_column('service_urls', $fields);

  }

  public function down()
  {
    $this->dbforge->drop_column('service_urls','overall_status');
  }

}