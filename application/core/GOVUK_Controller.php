<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

/**
 * Extended and customised CI_Controller
 *
 * @package GovUK
 * @subpackage Core
 * @category Controller
 * @author Dafydd Vaughan (@dafyddbach)
 * @link http://www.dafyddvaughan.co.uk
 */
class GOVUK_Controller extends CI_Controller {

  function __construct() {
    parent::__construct();
    $this->load->library('session');
    session_start();
    if (SITE_MAINTENANCE) {
      include('maintenance.php');
      exit;
    }
  }

  function _do_cache() {
    if(SITE_CACHE) {
      $this->output->cache(SITE_CACHE_TIME);
    }
  }

}
