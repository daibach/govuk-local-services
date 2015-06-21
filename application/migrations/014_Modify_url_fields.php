<?php defined('BASEPATH') OR exit('No direct script access allowed');

class Migration_modify_url_fields extends CI_Migration {

  public function up()
  {
    $this->_modify_fields('TEXT');
  }

  public function down()
  {
    $this->_modify_fields('VARCHAR(255)');
  }

  function _modify_fields($type) {
    $this->_modify_service_urls($type);
    $this->_modify_check_history($type);
    $this->_modify_url_history($type);
    $this->_modify_reports($type);
  }

  function _modify_service_urls($type) {
    $fields = array(
      'url' => array(
        'type'          => $type
      )
    );
    $this->dbforge->modify_column('service_urls', $fields);
  }

  function _modify_check_history($type) {
    $fields = array(
      'normal_url' => array(
        'type'          => $type
      ),
      'jumbled_url' => array(
        'type'          => $type
      )
    );
    $this->dbforge->modify_column('url_check_history', $fields);
  }

  function _modify_url_history($type) {
    $fields = array(
      'original' => array(
        'type'          => $type
      ),
      'new' => array(
        'type'          => $type
      )
    );
    $this->dbforge->modify_column('url_history', $fields);
  }

  function _modify_reports($type) {
    $fields = array(
      'alternative_url' => array(
        'type'          => $type
      )
    );
    $this->dbforge->modify_column('url_reports', $fields);
  }

}
