<?php
/**
 * Migrations Controller
 * This controller is designed to run from the php cli
 *
 * @package MazzOps
 * @subpackage Controllers/Crons
 * @category Migrations
 * @author Dafydd Vaughan (@dafyddbach)
 * @link http://www.dafyddvaughan.co.uk
 */
class Migrations extends CI_Controller {

  function __construct()
  {
    parent::__construct();
  }

  function index() {

    $CI =& get_instance();
    $CI->db = $this->load->database('admin',true);

    if(ENVIRONMENT=='development' || $this->input->is_cli_request()) {

      try {
        $this->load->library('migration');

        if ( ! $this->migration->current())
        {
          show_error($this->migration->error_string());
          echo 'An error occurred: ', $this->migration->error_string(), "\n";
        } else {
          echo 'Migration complete - database is now at version ', DB_VERSION, ".\n";
        }

      } catch (Exception $e) {
        echo 'An error occurred: ',  $e->getMessage(), "\n";
      }
    } else {
      show_404(current_url());
    }

  }

}

/* End of file migrations.php */
/* Location: ./application/controllers/crons/migrations.php */