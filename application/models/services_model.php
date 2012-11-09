<?php

/**
 * Services Model
 *
 * @package GOVUK Local Services
 * @subpackage Models
 * @category Services Model
 * @author Dafydd Vaughan (@dafyddbach)
 * @link http://www.dafyddvaughan.co.uk
 */
class Services_model extends CI_Model {

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
    $query = $this->db->get('local_services');
    if($query->num_rows > 0) {
      return $query->result();
    } else {
      return array();
    }

  }

  function create($lgsl,$description,$provided_district,$provided_county,$provided_unitary) {

    $data = array(
      'id'=>$lgsl,
      'description'=>$description,
      'provided_district'=>$provided_district,
      'provided_county'=>$provided_county,
      'provided_unitary'=>$provided_unitary
    );

    $this->db->insert('local_services',$data);

    return $this->db->insert_id();

  }

  function update($lgsl,$description,$provided_district,$provided_county,$provided_unitary) {

    $data = array(
      'description'=>$description,
      'provided_district'=>$provided_district,
      'provided_county'=>$provided_county,
      'provided_unitary'=>$provided_unitary
    );

    $this->db->where('id',$lgsl);
    $this->db->update('local_services',$data);

  }

  function find_by_lgsl($lgsl) {

    $this->db->where('id',$lgsl);
    $query = $this->db->get('local_services');
    if($query->num_rows() > 0) {
      return $query->row();
    } else {
      return FALSE;
    }

  }

  function request_url_checks_for_service($lgsl) {

    $data = array('lgsl'=>$lgsl);
    $this->db->insert('service_check_queue',$data);

  }

  function get_services_to_url_check($timestamp) {
    $this->db->select('lgsl');
    $this->db->where('locked',0);
    $this->db->order_by('created_date,lgsl');
    $query = $this->db->get('service_check_queue',5);

    if($query->num_rows() > 0) {
      $rows = $query->result();

      $lgsls_to_test = array();
      foreach($rows as $r) {
        array_push($lgsls_to_test,$r->lgsl);
      }

      $this->db->where_in('lgsl',$lgsls_to_test);
      $this->db->update('service_check_queue',array('locked'=>$timestamp));

      return $rows;
    } else {
      return FALSE;
    }

  }

  function complete_service_url_check($timestamp,$lgsl) {
    $this->db->where('locked',$timestamp);
    $this->db->where('lgsl',$lgsl);
    $this->db->delete('service_check_queue');
  }

}
/* End of file services_model.php */
/* Location: ./application/models/services_model.php */