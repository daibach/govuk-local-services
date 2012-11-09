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
      'imported_on'           => $import_id
    );
    $this->db->where('id',$id);
    $this->db->update('service_urls',$data);

    $data = array(
      'url_id'    => $id,
      'original'  => $existing_url,
      'new'       => $url,
      'imported_on' => $import_id
    );
    $this->db->insert('url_history');

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
    $this->db->select('service_urls.*, local_services.*, '.
      'local_interactions.name as interaction_name, '.
      'local_authorities.name as authority_name, ' .
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
    if($http_status != 200) {
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

  /*function get_batch_for_testing($batch_size,$snac='') {

    $this->db->limit($batch_size);
    $this->db->order_by('last_tested','asc');
    $this->db->order_by('snac','asc');
    $this->db->order_by('lgsl','asc');
    $this->db->order_by('lgil','asc');
    $this->db->where_in('snac',array('42UE','00AZ','00CQ','11UF','26','00BC','34UF','47UC','32UC','38UF','00AE','00FN','00AQ','42UB','00ML','34UD','00AL','29','45UD','29UQ','00BK','12','31UB','00BQ','00AM','00AA','12UE','00MG','00BB','00GG','00AF','45','21','34UE','00HH','24','00AP','44UC','44','00AS','21UF','00AW','00AR','17','00JA','00AS','00CZ','41UD','00BF','00AG'));
    if($snac != '') {
      $this->db->where('snac',$snac);
    } else {
      $this->db->where('snac !=','');
    }

    $query = $this->db->get('service_urls');
    if($query->num_rows() > 0) {
      return $query->result();
    } else {
      return array();
    }

  }

  function store_new_url_check($url_id,$url_type,$normal_url_status,$test_url_status,$test_url,$content_status) {

    $data = array(
      array(
       'url_id'     => $url_id,
       'url_type'   => $url_type,
       'check_type' => 'http',
       'status'     => $normal_url_status,
       'checked_url'=>''
      ),
      array(
       'url_id'     => $url_id,
       'url_type'   => $url_type,
       'check_type' => '404_validator',
       'status'     => $test_url_status,
       'checked_url'=> $test_url
      ),
      array(
       'url_id'     => $url_id,
       'url_type'   => $url_type,
       'check_type' => 'content',
       'status'     => $content_status,
       'checked_url'=>''
      )
    );
    $this->db->insert_batch('url_checks',$data);

    if($url_type=='service_url') {
      $data = array(
       'http_status'          => $normal_url_status,
       'content_status'       => $content_status,
       '404_validator_status' => $test_url_status,
       'last_tested'          => date('Y-m-d H:i:s')
      );
      $this->db->where('id',$url_id);
      $this->db->update('service_urls',$data);
    }

  }

  function get_provided_urls_for_snac($snac) {

    $this->db->select('local_services.id as lgsl, local_services.description, su.id as url_id, su.lgil, su.url, su.http_status, su.content_status, su.404_validator_status as validator_status, su.last_tested');
    $this->db->join('service_urls su','su.lgsl=local_services.id');
    $this->db->where('su.snac',$snac);
    $this->db->order_by('local_services.id');
    $this->db->order_by('su.lgil');

    $query = $this->db->get('local_services');

    if($query->num_rows() > 0) {
      return $query->result();
    } else {
      return array();
    }

  }

  function get_missing_lgsls_for_snac($snac) {
    $prefix = DB_TBPREFIX;
    $this->db->where("id NOT IN (select distinct(lgsl) from ${prefix}service_urls where snac='${snac}')");
    $query = $this->db->get('local_services');

    if($query->num_rows() > 0) {
      return $query->result();
    } else {
      return array();
    }


  }

  function total_lgsls($type='') {

    $this->db->where('in_govuk',1);
    if($type=='DIS') {
      $this->db->where('provided_district',1);
    } elseif($type=='CTY') {
      $this->db->where('provided_county',1);
    }
    return $this->db->count_all_results('local_services');
  }

  function get_action_lgsls_count_for_snac($snac,$type='') {

    $this->db->select('local_services.id');
    $this->db->join('service_urls su','su.lgsl=local_services.id');
    $this->db->where('su.snac',$snac);
    $this->db->where('su.lgil !=',8);
    $this->db->where('local_services.in_govuk',1);

    if($type == 'DIS') {
      $this->db->where('local_services.provided_district',1);
    } elseif($type=='CTY') {
      $this->db->where('local_services.provided_county',1);
    } else {
      $this->db->where('local_services.provided_unitary',1);
    }

    return $this->db->count_all_results('local_services');
  }

  function get_present_lgsls_count_for_snac($snac,$type='') {

    $this->db->select('local_services.id');
    $this->db->join('service_urls su','su.lgsl=local_services.id');
    $this->db->where('su.snac',$snac);
    $this->db->where('local_services.in_govuk',1);

    if($type == 'DIS') {
      $this->db->where('local_services.provided_district',1);
    } elseif($type=='CTY') {
      $this->db->where('local_services.provided_county',1);
    } else {
      $this->db->where('local_services.provided_unitary',1);
    }

    $this->db->group_by('local_services.id');
    $query = $this->db->get('local_services');
    return $query->num_rows();
  }

  function get_total_urls_count_for_snac($snac) {

    $this->db->select('local_services.id');
    $this->db->join('service_urls su','su.lgsl=local_services.id');
    $this->db->where('su.snac',$snac);

    return $this->db->count_all_results('local_services');

  }

  function get_url_http_status_counts_for_snac($snac) {

    $this->db->select('http_status, count(*) as cnt');
    $this->db->join('service_urls su','su.lgsl=local_services.id');
    $this->db->where('su.snac',$snac);
    $this->db->group_by('http_status');
    $query = $this->db->get('local_services');
    if($query->num_rows() > 0) {
      return $query->result();
    } else {
      return array();
    }

  }

  function get_url_content_status_counts_for_snac($snac) {

    $this->db->select('content_status, count(*) as cnt');
    $this->db->join('service_urls su','su.lgsl=local_services.id');
    $this->db->where('su.snac',$snac);
    $this->db->group_by('content_status');
    $query = $this->db->get('local_services');
    if($query->num_rows() > 0) {
      return $query->result();
    } else {
      return array();
    }

  }


  function get_url_validator_status_counts_for_snac($snac) {

    $this->db->select('404_validator_status as validator_status, count(*) as cnt');
    $this->db->join('service_urls su','su.lgsl=local_services.id');
    $this->db->where('su.snac',$snac);
    $this->db->group_by('validator_status');
    $query = $this->db->get('local_services');
    if($query->num_rows() > 0) {
      return $query->result();
    } else {
      return array();
    }

  }*/

}
/* End of file urls_model.php */
/* Location: ./application/models/urls_model.php */