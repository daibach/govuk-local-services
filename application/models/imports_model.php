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

  function store_md5_and_filename($id, $md5, $filename) {

    $data = array(
      'filemd5'   =>$md5,
      'filename'  =>$filename
    );
    $this->db->where('id',$id);
    $this->db->update('imports',$data);

  }

  function get_latest_file_md5($service) {

    $this->db->where('filemd5 !=','');
    $this->db->where('service',$service);
    $this->db->order_by('id','desc');
    $query = $this->db->get('imports');
    if($query->num_rows() > 0) {
      $row = $query->row();
      return $row->filemd5;
    } else {
      return 0;
    }

  }

}
/* End of file imports_model.php */
/* Location: ./application/models/imports_model.php */
