<?php

/**
 * Authorities Model
 *
 * @package GOVUK Local Services
 * @subpackage Models
 * @category Authorities Model
 * @author Dafydd Vaughan (@dafyddbach)
 * @link http://www.dafyddvaughan.co.uk
 */
class Authorities_model extends CI_Model {

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
    $query = $this->db->get('local_authorities');
    if($query->num_rows > 0) {
      return $query->result();
    } else {
      return array();
    }

  }

  function create($snac,$name,$homepage,$contact,$postcode) {

    $data = array(
      'snac'=>$snac,
      'name'=>$name,
      'homepage_url'=>$homepage,
      'contact_url'=>$contact,
      'postcode'=>$postcode
    );

    $this->db->insert('local_authorities',$data);

    return $this->db->insert_id();

  }

  function update($id,$name,$homepage,$contact,$postcode) {

    $data = array(
      'name'=>$name,
      'homepage_url'=>$homepage,
      'contact_url'=>$contact,
      'postcode'=>$postcode
    );

    $this->db->where('id',$id);
    $this->db->update('local_authorities',$data);

  }

  function find_by_snac($snac) {

    $this->db->where('snac',$snac);
    $query = $this->db->get('local_authorities');
    if($query->num_rows() > 0) {
      return $query->row();
    } else {
      return FALSE;
    }

  }

  function find_missing_by_lgsl($lgsl) {

    $sql = "SELECT * FROM ".DB_TBPREFIX."local_authorities WHERE snac NOT IN (select distinct(snac) from ".DB_TBPREFIX."service_urls urls where urls.lgsl=$lgsl)";
    $query = $this->db->query($sql);
    if($query->num_rows() > 0) {
      return $query->result();
    } else {
      return FALSE;
    }

  }

  function find_missing_by_snac($snac) {

    $sql = "SELECT * FROM ".DB_TBPREFIX."local_services WHERE id NOT IN (select distinct(lgsl) from ".DB_TBPREFIX."service_urls urls where urls.snac='$snac')";
    $query = $this->db->query($sql);
    if($query->num_rows() > 0) {
      return $query->result();
    } else {
      return FALSE;
    }

  }

}
/* End of file authorities_model.php */
/* Location: ./application/models/authorities_model.php */