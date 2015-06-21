<?php
/**
 * Load URLs Controller
 * This controller is designed to run from the php cli.
 * It downloads the Local Directgov services CSV and
 * imports new urls into the database
 *
 * @package GOV.UK Local Services
 * @subpackage Controllers/Crons
 * @category Load URLs
 * @author Dafydd Vaughan (@dafyddbach)
 * @link http://www.dafyddvaughan.co.uk
 */
class Load_urls extends CI_Controller {

  function __construct()
  {
    parent::__construct();
    $this->load->model('imports_model','imports');
    $this->load->model('services_model','services');
    $this->load->model('urls_model','urls');
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

  function _register_new_import() {
    return $this->imports->create('service_urls');
  }

  function _download_file($import_id) {

    $timestamp = date('Ymd-His');
    $filename = "urls-{$timestamp}-{$import_id}.csv";

    $ch = curl_init('http://local.direct.gov.uk/Data/local_authority_service_details.csv');
    $fp = fopen("./assets/importfiles/{$filename}", 'wb');
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_exec($ch);
    curl_close($ch);
    fclose($fp);

    return $filename;

  }

  function _are_file_md5s_different($filename, $import_id) {

    $last_import_md5 = $this->imports->get_latest_file_md5('service_urls');
    $this_import_md5 = md5_file("./assets/importfiles/{$filename}");

    $this->imports->store_md5_and_filename($import_id, $this_import_md5, $filename);

    if($last_import_md5 != $this_import_md5) {
      return true;
    } else {
      return false;
    }

  }

  function _process_csv($filename,$import_id) {

    $this->db->save_queries = false;

    $row = 1;

    $file_handle = fopen("./assets/importfiles/{$filename}",'r');
    while (($row_contents = fgetcsv($file_handle)) !== false) {
      //ignore first row
      if($row > 1) {
        $this->_process_row($row_contents,$import_id);
        if($row % 20 == 0) {
          sleep(0.25);
        }
      }
      $row++;
    }
    fclose($file_handle);

    $this->db->save_queries = true;

  }

  function _process_row($row_contents,$import_id) {

    $snac_field = 1;
    $lgsl_field = 4;
    $lgil_field = 5;
    $url_field  = 6;

    if($row_contents[$snac_field] != '') {

    $existing_url = $this->urls->get_url_for_lgil($row_contents[$snac_field], $row_contents[$lgsl_field], $row_contents[$lgil_field]);
    if($existing_url) {

      if($existing_url->url != $row_contents[$url_field]) {
        $this->urls->update($existing_url->id,$row_contents[$url_field],$existing_url->url,$import_id);
        $this->urls->request_check($existing_url->id,'url_import');
      }

    } else {

      //skip licensing URLs
      if(strtolower($row_contents[$url_field]) != "x") {
        $url_id = $this->urls->create(
          $row_contents[$snac_field],
          $row_contents[$lgsl_field],
          $row_contents[$lgil_field],
          $row_contents[$url_field],
          $import_id
        );
        $this->urls->request_check($url_id,'url_import');
      }

    }
    }


  }

}

/* End of file load_urls.php */
/* Location: ./application/controllers/crons/load_urls.php */
