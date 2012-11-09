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

}
/* End of file imports_model.php */
/* Location: ./application/models/imports_model.php */