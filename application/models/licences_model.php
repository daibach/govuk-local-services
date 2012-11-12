<?php

/**
 * Licences Model
 *
 * @package GOVUK Local Services
 * @subpackage Models
 * @category Licences Model
 * @author Dafydd Vaughan (@dafyddbach)
 * @link http://www.dafyddvaughan.co.uk
 */
class Licences_model extends CI_Model {

  /**
   * Constructor
   *
   * @access public
   */
  function __construct()
  {
      parent::__construct();

  }

  function all() {

    $query = $this->db->get('licences');
    if($query->num_rows > 0) {
      return $query->result();
    } else {
      return array();
    }

  }

  function create($slug) {

    $data = array(
      'slug'=>$slug
    );

    $this->db->insert('licences',$data);

    return $this->db->insert_id();

  }

  function update($id,$licence_identifier,$name,$description,$url,$type,$status) {
    $data = array(
      'licence_identifier'  => $licence_identifier,
      'name'                => $name,
      'description'         => $description,
      'transaction_url'     => $url,
      'licence_type'        => $type,
      'overall_status'      => $status
    );
    $this->db->where('id',$id);
    $this->db->update('licences',$data);
  }

  function find_by_slug($slug) {

    $this->db->where('slug',$slug);
    $query = $this->db->get('licences');
    if($query->num_rows() > 0) {
      return $query->row();
    } else {
      return FALSE;
    }

  }

  function where_problems() {
    $this->db->where('overall_status','warning');
    $query = $this->db->get('licences');
    if($query->num_rows > 0) {
      return $query->result();
    } else {
      return array();
    }
  }


}
/* End of file licences_model.php */
/* Location: ./application/models/licences_model.php */