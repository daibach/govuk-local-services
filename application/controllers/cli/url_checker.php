<?php
/**
 * URL Checker Controller
 * This controller is designed to run from the php cli.
 * It collects a selection of URLs from the database and
 * determines if it is a 404 or 200 status code
 *
 * @package GOV.UK Local Services
 * @subpackage Controllers/Crons
 * @category URL Checker
 * @author Dafydd Vaughan (@dafyddbach)
 * @link http://www.dafyddvaughan.co.uk
 */
class Url_checker extends CI_Controller {

  function __construct()
  {
    parent::__construct();
    $this->load->model('services_model','services');
    $this->load->model('urls_model','urls');
  }


  function index() {
    $this->check_from_service_queue();
  }

  function check_from_service_queue() {
    if(ENVIRONMENT=='development' || $this->input->is_cli_request()) {

      $timestamp = time();
      $local_services_to_test = $this->services->get_services_to_url_check($timestamp);

      if($local_services_to_test) {
        foreach($local_services_to_test as $serv) {
          $this->_test_service($serv->lgsl);
          $this->services->complete_service_url_check($timestamp,$serv->lgsl);
        }
      }

    }
  }

  function check_from_status_queue() {
    if(ENVIRONMENT=='development' || $this->input->is_cli_request()) {

      $timestamp = time();

      $urls_to_test = $this->urls->get_urls_to_status_check($timestamp);
      if($urls_to_test) {
        foreach($urls_to_test as $url) {
          $this->_test_url($url);
          $this->urls->complete_url_status_check($url->id);
        }
      }

    }
  }

  function spotcheck_lgsl($lgsl) {
    if(ENVIRONMENT=='development' || $this->input->is_cli_request()) {
      $this->_test_service($lgsl);
    }
  }

  function spotcheck_url($urlid) {
    if(ENVIRONMENT=='development' || $this->input->is_cli_request()) {
      $url = $this->urls->find($urlid);
      $this->_test_url($url);
    }
  }

  function trigger_full_service_recheck() {
    $local_services = $this->services->all();
    if($local_services) {
      foreach($local_services as $s) {
        $this->services->request_url_checks_for_service($s->id);
      }
    }
  }

  function _test_service($lgsl) {

    $urls_to_test = $this->urls->get_urls_for_lgsl($lgsl);
    if($urls_to_test) {
      foreach($urls_to_test as $url) {
        $this->_test_url($url);
      }
    }

  }

  function _test_url($url_info) {

    $normal_url_results = $this->_fetch_url($url_info->url);

    $jumbled_url = $this->_jumble_url($url_info->url);
    $jumbled_url_results = $this->_fetch_url($jumbled_url);

    $this->urls->store_url_status(
      $url_info->id,
      $url_info->url,
      $normal_url_results['http_status'],
      $normal_url_results['analysis'],
      $jumbled_url,
      $jumbled_url_results['http_status']
    );


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
      CURLOPT_CONNECTTIMEOUT => 14,
      CURLOPT_TIMEOUT => 21,
      CURLOPT_POST => FALSE,
      CURLOPT_SSL_VERIFYPEER => false,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_USERAGENT => $useragent,
      CURLOPT_URL => $url));


    $html = curl_exec($ch);
    $http_status = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    $curl_error = curl_errno($ch);

    curl_close($ch);

    if($curl_error == 28) {
      $http_status = 598;
    }

    return array(
      'content'     => $html,
      'http_status' => $http_status,
      'curl_error'  => $curl_error,
      'analysis'    => $this->_analyse_html_for_404($html)
    );

  }

  function _analyse_html_for_404($html) {

    //could this possibly be a 404
    if($html != "") {

      if(
        preg_match("/cannot be found/i",$html) ||
        preg_match("/error 404/i",$html) ||
        preg_match("/not found/i",$html) ||
        preg_match("/ 404[< ] /i",$html) ||
        preg_match("/<title>.*?homepage.*?<\/title>/i",$html)
      ) {
        return 404; //think this is a 404
      } else {
        return 200; //simple check suggests not 404
      }

    } else {
      return 500; //completely empty - link doesn't work!
    }
  }

  function _jumble_url($url) {

    $url_parts = parse_url($url);
    $jumbled_url = "";

    if(array_key_exists('path',$url_parts)) {
      //if url doesn't have a query string
      if(! array_key_exists('query',$url_parts)) {

        $jumbled_url = $this->_jumble_url_with_no_querystring($url_parts);

      } else {

        $jumbled_url = $this->_jumble_url_with_querystring($url_parts);

      }
    } else {

      //url doesn't have a path, generate a random url
      $url_parts['path'] = '/'.random_string('alnum',16);
      $jumbled_url = $this->_build_url($url_parts);

    }

    if ($jumbled_url === $url) {
      $jumbled_url = $url.random_string('alnum',5);
    }

    return $jumbled_url;

  }


  function _jumble_url_with_no_querystring($url_info) {

    $url_path_to_play_with = $url_info['path'];
    $url_pieces = explode('/',$url_path_to_play_with);
    $num_of_pieces = sizeof($url_pieces)-1;

    //if the url ends in a slash
    if($url_pieces[$num_of_pieces] === '') {
      $piece_to_play_with = $url_pieces[$num_of_pieces-1];
      $url_pieces[$num_of_pieces-1] = $this->_do_jumble($piece_to_play_with);
    } else {
      $piece_to_play_with = $url_pieces[$num_of_pieces];
      $url_pieces[$num_of_pieces] = $this->_do_jumble($piece_to_play_with);
    }

    $jumbled_url_path = implode('/',$url_pieces);
    $url_info['path'] = $jumbled_url_path;
    return $this->_build_url($url_info);

  }

  function _jumble_url_with_querystring($url_info) {

    $new_query = $url_info['query'];

    $query_pieces = explode('&',$url_info['query']);
    foreach($query_pieces as $piece) {

      $elements = explode('=',$piece);

      if(sizeof($elements)>1) {
        if($this->_ends_with($elements[0],'id') || $this->_ends_with($elements[0],'page')) {
          $new_query = str_replace($elements[1], date('YmdHis0'), $url_info['query']);
        }
      }

    }

    //if we didn't find something above, try some other
    //strange changes
    if($url_info['query'] === $new_query) {

      if(preg_match('/\d/', $url_info['query'])) {
        //there is something with numbers here, lets try messing up the
        //numbers a bit and run a check on that
       $new_query= preg_replace("/[0-9]+/", date('YmdHis0'), $url_info['query']);

      } else {
        //i don't think this query string determines what is on the page
        //so fall back to changing the url structure instead
        return $this->_jumble_url_with_no_querystring($url_info);
      }

    }
    $url_info['query'] = $new_query;

    return $this->_build_url($url_info);

  }

  function _do_jumble($string) {

    $jumble = explode('.',$string);
    $jumble[0] = strrev($jumble[0]);
    if(strlen($jumble[0]) < 4) { $jumble[0] .= random_string('alnum',5); }
    $jumble = implode('.',$jumble);

    return $jumble;

  }


  function _build_url($url_parts) {

    $new_url = "";
    $new_url .= $url_parts['scheme'].'://';
    $new_url .= $url_parts['host'];
    $new_url .= $url_parts['path'];
    if(array_key_exists('query',$url_parts)) {
      $new_url .= '?'.$url_parts['query'];
    }
    if(array_key_exists('fragment',$url_parts)) {
      $new_url .= '#'.$url_parts['fragment'];
    }

    return $new_url;

  }

  function _ends_with($string, $test) {
    $string = strtolower($string);
    $strlen = strlen($string);
    $testlen = strlen($test);
    if ($testlen > $strlen) return false;
    return substr_compare($string, $test, -$testlen) === 0;
  }

}
/* End of file url_checker.php */
/* Location: ./application/controllers/crons/url_checker.php */
