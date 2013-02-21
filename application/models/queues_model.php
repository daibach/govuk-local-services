<?php

/**
 * Queues Model
 *
 * @package GOVUK Local Services
 * @subpackage Models
 * @category Queues Model
 * @author Dafydd Vaughan (@dafyddbach)
 * @link http://www.dafyddvaughan.co.uk
 */
class Queues_model extends CI_Model {

  /**
   * Constructor
   *
   * @access public
   */
  function __construct()
  {
      parent::__construct();

  }

  function service_check_queue() {
    $this->db->order_by('locked','desc');
    $this->db->join('local_services','local_services.id=service_check_queue.lgsl');
    $query = $this->db->get('service_check_queue');
    if($query->num_rows > 0) {
      return $query->result();
    } else {
      return array();
    }
  }

  function url_status_check_queue() {
    $this->db->select('url_status_check_queue.*, service_urls.lgsl, service_urls.lgil, service_urls.snac, local_authorities.name');
    $this->db->order_by('locked','desc');
    $this->db->join('service_urls','service_urls.id=url_status_check_queue.url_id');
    $this->db->join('local_authorities','local_authorities.snac=service_urls.snac');
    $query = $this->db->get('url_status_check_queue');
    if($query->num_rows > 0) {
      return $query->result();
    } else {
      return array();
    }
  }

  function url_import_check_queue() {
    $this->db->order_by('locked','desc');
    $this->db->join('imports','imports.id=url_import_check_queue.import');
    $query = $this->db->get('url_import_check_queue');
    if($query->num_rows > 0) {
      return $query->result();
    } else {
      return array();
    }
  }


}
/* End of file queues_model.php */
/* Location: ./application/models/queues_model.php */