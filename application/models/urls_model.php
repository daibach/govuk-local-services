<?php

/**
 * URLs Model
 *
 * @package GOVUK Local Services
 * @subpackage Models
 * @category URLs Model
 * @author Dafydd Vaughan (@dafyddbach)
 * @link http://www.dafyddvaughan.co.uk
 */
class Urls_model extends CI_Model {

  /**
   * Constructor
   *
   * @access public
   */
  function __construct()
  {
      parent::__construct();

  }

  function create($snac,$lgsl,$lgil,$url,$import_id=0) {

    $url_info = parse_url($url);
    $data = array(
      'snac'    => $snac,
      'lgsl'    => $lgsl,
      'lgil'    => $lgil,
      'url'     => $url,
      'domain'  => $url_info['host'],
      'imported_on' => $import_id
    );
    $this->db->insert('service_urls',$data);

    return $this->db->insert_id();

  }

  function update($id,$url,$existing_url,$import_id=0) {

    $url_info = parse_url($url);
    $data = array(
      'url'                   => $url,
      'domain'                => $url_info['host'],
      'http_status'           => 0,
      'content_looks_like'    => 0,
      'can_404'               => 0,
      'last_tested'           => '0000-00-00 00:00:00',
      'overall_status'        => 'unknown',
      'imported_on'           => $import_id,
      'has_reported_problems' => 0
    );
    $this->db->where('id',$id);
    $this->db->update('service_urls',$data);

    $data = array(
      'url_id'    => $id,
      'original'  => $existing_url,
      'new'       => $url,
      'imported_on' => $import_id
    );
    $this->db->insert('url_history',$data);

    $data = array(
      'status'    => 'superseded'
    );
    $this->db->where('url_id',$id);
    $this->db->where('status','open');
    $this->db->update('url_reports',$data);

  }

  function request_check($id,$requested_by='') {

    $data = array(
      'url_id'        => $id
    );

    if($requested_by != '') {
      $data['requested_by'] = $requested_by;
    }

    $this->db->insert('url_status_check_queue',$data);

  }

  function report($id,$type,$notes,$alternative_url) {

    $data = array(
      'url_id'          => $id,
      'report_type'     => $type,
      'status'          => 'open',
      'notes'           => $notes,
      'alternative_url' => $alternative_url
    );
    $this->db->insert('url_reports',$data);

    $data = array(
      'overall_status'  => 'warning',
      'has_reported_problems' => TRUE
    );
    $this->db->where('id',$id);
    $this->db->update('service_urls',$data);

  }

  function find($id) {
    $this->db->where('id',$id);
    $query = $this->db->get('service_urls');
    if($query->num_rows() > 0) {
      return $query->row();
    } else {
      return FALSE;
    }
  }

  function find_info($id) {
    $this->db->select('service_urls.*, local_services.description, '.
      'local_services.provided_district, local_services.provided_county, '.
      'local_services.provided_unitary, '.
      'local_interactions.name as interaction_name, '.
      'local_authorities.name as authority_name, ' .
      'local_authorities.country as authority_country, '.
      'local_authorities.type as authority_type');
    $this->db->where('service_urls.id',$id);
    $this->db->join('local_services','local_services.id=service_urls.lgsl');
    $this->db->join('local_interactions','local_interactions.id=service_urls.lgil');
    $this->db->join('local_authorities','local_authorities.snac=service_urls.snac');
    $query = $this->db->get('service_urls');
    if($query->num_rows() > 0) {
      return $query->row();
    } else {
      return FALSE;
    }
  }


  function get_url_for_lgil($snac,$lgsl,$lgil) {

    $this->db->where('snac', $snac);
    $this->db->where('lgsl', $lgsl);
    $this->db->where('lgil', $lgil);

    $query = $this->db->get('service_urls');
    if($query->num_rows() > 0) {
      return $query->row();
    } else {
      return FALSE;
    }

  }

  function get_urls_for_lgsl($lgsl) {

    $this->db->where('lgsl',$lgsl);
    $this->db->order_by('snac');
    $query = $this->db->get('service_urls');

    if($query->num_rows() > 0) {
      return $query->result();
    } else {
      return FALSE;
    }

  }

  function get_urls_for_import($import) {

    $this->db->where('imported_on',$import);
    $this->db->order_by('lgsl');
    $query = $this->db->get('service_urls');

    if($query->num_rows() > 0) {
      return $query->result();
    } else {
      return FALSE;
    }

  }

  function get_urls_to_status_check($timestamp) {
    $this->db->select('service_urls.*');
    $this->db->where('locked',0);
    $this->db->join('url_status_check_queue','url_status_check_queue.url_id=service_urls.id');
    $this->db->order_by('url_status_check_queue.created_date, lgil, lgsl, snac');
    $query = $this->db->get('service_urls',10);

    if($query->num_rows() > 0) {
      $rows = $query->result();

      $urls_to_test = array();
      foreach($rows as $r) {
        array_push($urls_to_test,$r->id);
      }

      $this->db->where_in('url_id',$urls_to_test);
      $this->db->update('url_status_check_queue',array('locked'=>$timestamp));

      return $rows;
    } else {
      return FALSE;
    }

  }

  function complete_url_status_check($url_id) {
    $this->db->where('url_id',$url_id);
    $this->db->delete('url_status_check_queue');
  }

  function store_url_status($url_id,$url,$http_status,$looks_like,$jumbled_url,$jumbled_http_status) {

    $data = array(
      'url_id'              => $url_id,
      'normal_url'          => $url,
      'http_status'         => $http_status,
      'looks_like'          => $looks_like,
      'jumbled_url'         => $jumbled_url,
      'jumbled_http_status' => $jumbled_http_status
    );
    $this->db->insert('url_check_history',$data);

    $data = array(
      'http_status' => $http_status,
      'content_looks_like' => 0,
      'can_404' => 0,
      'last_tested' => date('Y-m-d H:i:s'),
      'overall_status' => 'ok'
    );
    if($http_status != 200 && $http_status != 301 && $http_status != 302) {
      $data['overall_status'] = 'warning';
    }
    $data['content_looks_like'] = $looks_like;
    if($looks_like > 0 && $looks_like != 200) {
      $data['overall_status'] = 'warning';
    }
    if($jumbled_http_status == 404) {
      $data['can_404'] = TRUE;
    }
    $this->db->where('id',$url_id);
    $this->db->update('service_urls',$data);

  }

  function get_url_status_for_lgsl($lgsl) {
    $this->db->select(
      'local_authorities.snac, local_authorities.name as authority, ' .
      'local_authorities.country, '.
      'local_authorities.type, urls.lgsl, urls.lgil, ' .
      'interactions.shortname as interaction_short, ' .
      'interactions.name as interaction, urls.id as url_id, urls.url, ' .
      'urls.http_status, urls.content_looks_like, urls.can_404, ' .
      'urls.last_tested, urls.overall_status'
    );
    $this->db->join('service_urls urls','urls.snac=local_authorities.snac','inner');
    $this->db->join('local_interactions interactions','interactions.id=urls.lgil');
    $this->db->where('urls.lgsl',$lgsl);
    $this->db->order_by('urls.lgil, local_authorities.name');

    $query = $this->db->get('local_authorities');

    if($query->num_rows() > 0) {
      return $query->result();
    } else {
      return FALSE;
    }

  }


  function get_url_status_for_snac($snac) {
    $this->db->select(
      'local_services.description as service, local_services.provided_district, ' .
      'local_services.provided_county, local_services.provided_unitary, ' .
      'urls.lgsl, urls.lgil, interactions.shortname as interaction_short, ' .
      'interactions.name as interaction, urls.id as url_id, urls.url, ' .
      'urls.http_status, urls.content_looks_like, urls.can_404, ' .
      'urls.last_tested, urls.overall_status'
    );
    $this->db->join('service_urls urls','urls.lgsl=local_services.id','inner');
    $this->db->join('local_interactions interactions','interactions.id=urls.lgil');
    $this->db->where('urls.snac',$snac);
    $this->db->order_by('urls.lgil, urls.lgsl');

    $query = $this->db->get('local_services');

    if($query->num_rows() > 0) {
      return $query->result();
    } else {
      return FALSE;
    }

  }

  function get_check_history($id) {

    $this->db->where('url_id',$id);
    $this->db->order_by('created_date','desc');
    $query = $this->db->get('url_check_history');

    if($query->num_rows() > 0) {
      return $query->result();
    } else {
      return FALSE;
    }
  }

  function get_history($id) {
    $this->db->where('url_id',$id);
    $this->db->order_by('created_date','desc');
    $query = $this->db->get('url_history');

    if($query->num_rows() > 0) {
      return $query->result();
    } else {
      return FALSE;
    }
  }

  function get_reports($id) {
    $this->db->where('url_id',$id);
    $this->db->order_by('created_date','desc');
    $query = $this->db->get('url_reports');

    if($query->num_rows() > 0) {
      return $query->result();
    } else {
      return FALSE;
    }
  }


  function get_problem_urls() {

    $this->db->select('service_urls.*, local_services.description as service, '.
      'local_services.provided_district, local_services.provided_county, '.
      'local_services.provided_unitary, local_interactions.name as interaction, '.
      'local_interactions.shortname as interaction_short, '.
      'local_authorities.name as authority, '.
      'local_authorities.country as authority_country, '.
      'local_authorities.type as authority_type');
    $this->db->join('local_services','local_services.id=service_urls.lgsl','inner');
    $this->db->join('local_interactions','local_interactions.id=service_urls.lgil','inner');
    $this->db->join('local_authorities','local_authorities.snac=service_urls.snac','inner');
    $this->db->where('overall_status','warning');
    $this->db->order_by('local_authorities.name, service_urls.lgsl, service_urls.lgil');

    $query = $this->db->get('service_urls');
    if($query->num_rows() > 0) {
      return $query->result();
    } else {
      return FALSE;
    }

  }

}
/* End of file urls_model.php */
/* Location: ./application/models/urls_model.php */