<?php

/**
 * Imports Model
 *
 * @package GOVUK Local Services
 * @subpackage Models
 * @category Imports Model
 * @author Dafydd Vaughan (@dafyddbach)
 * @link http://www.dafyddvaughan.co.uk
 */
class Imports_model extends CI_Model {

  /**
   * Constructor
   *
   * @access public
   */
  function __construct()
  {
      parent::__construct();
  }

  function create($service) {

    $data = array(
      'service'=>$service
    );
    $this->db->insert('imports',$data);
    return $this->db->insert_id();

  }

  function queue_url_check($import) {
    $this->db->insert('url_import_check_queue',array('import'=>$import));
  }

  function get_imports_to_url_check($timestamp) {

    $data = array(
      'locked' => $timestamp
    );
    $this->db->limit(1);
    $this->db->update('url_import_check_queue',$data);

    $this->db->select('import');
    $this->db->where('locked',$timestamp);
    $query = $this->db->get('url_import_check_queue');

    if($query->num_rows() > 0) {
      return $query->result();
    } else {
      return FALSE;
    }
  }

  function complete_url_import_check($timestamp, $import) {
    $this->db->where('locked',$timestamp);
    $this->db->where('import',$import);
    $this->db->delete('url_import_check_queue');
  }

}
/* End of file imports_model.php */
/* Location: ./application/models/imports_model.php */