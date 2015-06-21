<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_add_md5_and_filename_to_import extends CI_Migration {

  public function up()
  {
    $fields = array(
      'filemd5' => array(
        'type'          => "CHAR(32)",
        'default'       => ''
      ),
      'filename' => array(
        'type'          => "VARCHAR(50)",
        'default'       => ''
      ),
    );
    $this->dbforge->add_column('imports', $fields);

  }

  public function down()
  {
    $this->dbforge->drop_column('imports','filemd5');
    $this->dbforge->drop_column('imports','filename');
  }

}
