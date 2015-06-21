<?php
/**
 * Load Authorities Controller
 * This controller is designed to run from the php cli.
 * It downloads the Local Directgov authorities CSV and
 * imports new authorities into the database
 *
 * @package GOV.UK Local Services
 * @subpackage Controllers/Crons
 * @category Load Authorities
 * @author Dafydd Vaughan (@dafyddbach)
 * @link http://www.dafyddvaughan.co.uk
 */
class Load_authorities extends CI_Controller {

  function __construct()
  {
    parent::__construct();
    $this->load->model('imports_model','imports');
    $this->load->model('authorities_model','authorities');
  }

  function index() {

    if(ENVIRONMENT=='development' || $this->input->is_cli_request()) {

      $import_id = $this->_register_new_import();
      $filename = $this->_download_file($import_id);

      if($this->_are_file_md5s_different($filename, $import_id)) {
        $this->_process_csv($filename,$import_id);
      }

    } else {
      die();
    }

  }

  function identify_types() {

    if(ENVIRONMENT=='development' || $this->input->is_cli_request()) {

      $authorities = $this->authorities->all();
      foreach($authorities as $a) {

        $atype = 'CTY';

        if(strlen($a->snac) > 2) {
          $atype = $this->_get_info_from_mapit($a->snac);
        } else {
          $atype = 'CTY';
        }

        $data = array('type'=>$atype);
        $this->db->where('id',$a->id);
        $this->db->update('local_authorities',$data);
        sleep(0.25);

      }

    } else {
      die();
    }

  }

  function _get_info_from_mapit($snac) {

    $url = "http://mapit.mysociety.org/area/${snac}.json";

    try {
      $file = file_get_contents($url);

      if ($file === false) {
        return false;
      } else {
        $json = json_decode($file);
        return $json->type;
      }

    } catch (Exception $e) {
      return false;
    }

  }

  function _register_new_import() {

    return $this->imports->create('authorities');

  }

  function _download_file($import_id) {

    $timestamp = date('Ymd-His');
    $filename = "authorities-{$timestamp}-{$import_id}.csv";

    $ch = curl_init('http://local.direct.gov.uk/Data/local_authority_contact_details.csv');
    $fp = fopen("./assets/importfiles/{$filename}", 'wb');
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_exec($ch);
    curl_close($ch);
    fclose($fp);

    return $filename;

  }

  function _are_file_md5s_different($filename, $import_id) {

    $last_import_md5 = $this->imports->get_latest_file_md5('authorities');
    $this_import_md5 = md5_file("./assets/importfiles/{$filename}");

    $this->imports->store_md5_and_filename($import_id, $this_import_md5, $filename);

    if($last_import_md5 != $this_import_md5) {
      return true;
    } else {
      return false;
    }

  }

  function _process_csv($filename,$import_id) {

    $row = 1;
    $file_handle = fopen("./assets/importfiles/{$filename}",'r');
    while (($row_contents = fgetcsv($file_handle)) !== false) {
      //ignore first row
      if($row > 1) {
        $this->_process_row($row_contents,$import_id);
      }
      $row++;
    }
    fclose($file_handle);

  }

  function _process_row($row_contents,$import_id) {

    $existing_authority = $this->authorities->find_by_snac($row_contents[3]);

    if($existing_authority) {
      if($this->_authority_changed($existing_authority,$row_contents[1])) {
          $this->_handle_changes($existing_authority,$row_contents,$import_id);
      }
    } else {
      //$snac,$name,$homepage,$contact,$postcode
      $this->authorities->create(
        $row_contents[3],
        $row_contents[0],
        $row_contents[1],
        $row_contents[2],
        $row_contents[9]
      );
    }

  }

  function _handle_changes($existing_authority,$import_rows,$import_id) {

    //$id,$name,$homepage,$contact,$postcode
    $this->authorities->update(
      $existing_authority->id,
      $import_rows[0],
      $import_rows[1],
      $import_rows[2],
      $import_rows[9]
    );

  }

  function _authority_changed($existing_authority,$current_authority) {

    if($existing_authority->name != $current_authority[1] ||
      $existing_authority->homepage_url != $current_authority[2] ||
      $existing_authority->contact_url != $current_authority[3] ||
      $existing_authority->postcode != $current_authority[9])
    {
      return true;
    } else {
      return false;
    }
  }


}

/* End of file load_authorities.php */
/* Location: ./application/controllers/crons/load_authorities.php */
