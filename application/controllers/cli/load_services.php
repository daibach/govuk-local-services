<?php
/**
 * Load Services Controller
 * This controller is designed to run from the php cli.
 * It downloads the GOV.UK local services CSV and
 * imports new services into the database
 *
 * @package GOV.UK Local Services
 * @subpackage Controllers/Crons
 * @category Load Services
 * @author Dafydd Vaughan (@dafyddbach)
 * @link http://www.dafyddvaughan.co.uk
 */
class Load_services extends CI_Controller {

  function __construct()
  {
    parent::__construct();
    $this->load->model('imports_model','imports');
    $this->load->model('services_model','services');
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

    return $this->imports->create('services');

  }

  function _download_file($import_id) {

    $timestamp = date('Ymd-His');
    $filename = "services-{$timestamp}-{$import_id}.csv";

    $ch = curl_init('https://raw.github.com/alphagov/publisher/master/data/local_services.csv');
    $fp = fopen("./assets/importfiles/{$filename}", 'wb');
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, true);
    curl_exec($ch);
    curl_close($ch);
    fclose($fp);

    return $filename;

  }

  function _are_file_md5s_different($filename, $import_id) {

    $last_import_md5 = $this->imports->get_latest_file_md5('services');
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

    $existing_service = $this->services->find_by_lgsl($row_contents[0]);
    $providing_tiers = $this->_identify_providing_tiers($row_contents);

    if($existing_service) {
      $this->services->update(
        $row_contents[0],
        $row_contents[1],
        $providing_tiers['district'],
        $providing_tiers['county'],
        $providing_tiers['unitary']
      );
    } else {
      $newservice = $this->services->create(
        $row_contents[0],
        $row_contents[1],
        $providing_tiers['district'],
        $providing_tiers['county'],
        $providing_tiers['unitary']
      );
      $this->services->request_url_checks_for_service($newservice);
    }

  }

  function _identify_providing_tiers($row_contents) {

    $tier = $row_contents[2];
    $data = array(
      'district' => 0,
      'county' => 0,
      'unitary' => 0
    );

    if(strpos($tier,'district') !== FALSE) $data['district'] = 1;
    if(strpos($tier,'county') !== FALSE) $data['county'] = 1;
    if(strpos($tier,'unitary') !== FALSE) $data['unitary'] = 1;
    if(strpos($tier,'all') !== FALSE) {
      $data['district'] = 1;
      $data['county'] = 1;
      $data['unitary'] = 1;
    }

    return $data;

  }



}

/* End of file load_services.php */
/* Location: ./application/controllers/crons/load_services.php */
