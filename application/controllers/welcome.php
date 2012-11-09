<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends CI_Controller {

  public function index()
  {

    $this->load->view('templates/header');
    $this->load->view('main');
    $this->load->view('templates/footer');

  }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */