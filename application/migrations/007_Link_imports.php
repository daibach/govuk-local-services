<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_link_imports extends CI_Migration {

  public function up()
  {
    //add import id to url history
    $fields = array(
      'imported_on' => array(
        'type'          => 'INT',
        'constraint'    => 11,
        'default'       => 0
      )
    );
    $this->dbforge->add_column('url_history', $fields);

    //add import id to service urls
    $fields = array(
      'imported_on' => array(
        'type'          => 'INT',
        'constraint'    => 11,
        'default'       => 0
      )
    );
    $this->dbforge->add_column('service_urls', $fields);

  }

  public function down()
  {
    $this->dbforge->drop_column('url_history','import_id');
    $this->dbforge->drop_column('service_urls','import_id');
  }

}