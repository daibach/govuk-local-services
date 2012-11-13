<?php
/**
 * Load Licences Controller
 * This controller is designed to run from the php cli.
 * It downloads the GOV.UK licence slugs CSV and
 * imports new licences into the database
 *
 * @package GOV.UK Local Services
 * @subpackage Controllers/Crons
 * @category Load Licences
 * @author Dafydd Vaughan (@dafyddbach)
 * @link http://www.dafyddvaughan.co.uk
 */
class Load_licences extends CI_Controller {

  function __construct()
  {
    parent::__construct();
    $this->load->model('imports_model','imports');
    $this->load->model('licences_model','licences');
  }

  function index() {

    if(ENVIRONMENT=='development' || $this->input->is_cli_request()) {

      $import_id = $this->_register_new_import();
      $filename = $this->_download_file($import_id);
      $this->_process_csv($filename,$import_id);
      $this->reimport_licence_details();

    } else {
      die();
    }

  }

  function _register_new_import() {

    return $this->imports->create('licences');

  }

  function _download_file($import_id) {

    $timestamp = date('Ymd-His');
    $filename = "licences-{$timestamp}-{$import_id}.csv";

    $ch = curl_init('https://raw.github.com/daibach/govuk-local-services/master/data/licence-slugs.csv');
    $fp = fopen("./assets/importfiles/{$filename}", 'wb');
    curl_setopt($ch, CURLOPT_FILE, $fp);
    curl_setopt($ch, CURLOPT_HEADER, 0);
    curl_exec($ch);
    curl_close($ch);
    fclose($fp);

    return $filename;

  }

  function _process_csv($filename,$import_id) {

    $file_handle = fopen("./assets/importfiles/{$filename}",'r');
    while (($row_contents = fgetcsv($file_handle)) !== false) {
      //ignore first row
      $this->_process_row($row_contents,$import_id);
    }
    fclose($file_handle);

  }

  function _process_row($row_contents,$import_id) {

    $existing_licence = $this->licences->find_by_slug($row_contents[0]);

    if(!$existing_licence) {
      $this->licences->create($row_contents[0]);
    }

  }

  function reimport_licence_details() {
    $licences = $this->licences->all();

    $this->db->save_queries = false;

    foreach($licences as $licence) {
      $details = $this->_process_licence_information($licence->slug);
      if($details) {

        $this->licences->update(
          $licence->id,
          $details['identifier'],
          $details['name'],
          $details['description'],
          $details['transaction_url'],
          $details['licence_type'],
          $details['overall_status']
        );
      }
      sleep(0.25);
    }

    $this->db->save_queries = true;
  }

  function _process_licence_information($slug) {
    $api_base = "https://www.gov.uk/api/";
    $licence_content = $this->_fetch_url($api_base.$slug.".json");

    $licence_details = array(
      'identifier' => '',
      'name' => '',
      'description' => '',
      'transaction_url' => '',
      'licence_type' => 'unknown',
      'overall_status' => 'unknown'
    );

    try {
      $json = json_decode($licence_content['content']);
      $details = $json->details;

      $licence_details['identifier'] = $details->licence_identifier;
      $licence_details['name'] = $json->title;
      $licence_details['description'] = $details->licence_short_description;

      if(array_key_exists('licence',$details)) {
        $licence_app_info = $json->details->licence;
        if ($licence_app_info->location_specific) {
          $licence_details['licence_type'] = 'licence-app-local';
          $licence_details['overall_status'] = 'ok';
        } else {
          $licence_details['licence_type'] = 'licence-app-comp';
          $licence_details['overall_status'] = 'ok';
        }
        if($details->continuation_link != '') {
          $licence_details['transaction_url'] = $details->continuation_link;
          $licence_details['overall_status'] = 'warning';
        }
      } else {
        $licence_details['transaction_url'] = $details->continuation_link;
        $licence_details['licence_type'] = 'non-local';

        if($licence_details['transaction_url'] == '') {
          $licence_details['overall_status'] = 'warning';
        } else {
          $licence_details['overall_status'] = 'ok';
        }
      }

      if(strpos($licence_details['identifier'],'-') === false) {
        $licence_details['overall_status'] = 'warning';
      }
    } catch (Exception $e) {
      return false;
    }
    return $licence_details;
  }

  function _fetch_url($url) {

    $useragent = APP_USER_AGENT;

    $ch = curl_init();
    curl_setopt_array($ch, array(CURLOPT_HEADER => FALSE,
      CURLOPT_RETURNTRANSFER => TRUE,
      CURLOPT_FAILONERROR => TRUE,
      CURLOPT_COOKIESESSION => TRUE,
      CURLOPT_FOLLOWLOCATION => TRUE,
      CURLOPT_COOKIEJAR => "/dev/null",
      CURLOPT_CONNECTTIMEOUT => 7,
      CURLOPT_TIMEOUT => 14,
      CURLOPT_POST => FALSE,
      CURLOPT_USERAGENT => $useragent,
      CURLOPT_URL => $url));


    $html = curl_exec($ch);
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);

    curl_close($ch);

    return array(
      'content'     => $html,
      'http_status' => $http_status
    );

  }

}

/* End of file load_licences.php */
/* Location: ./application/controllers/crons/load_licences.php */