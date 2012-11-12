<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Licences extends GOVUK_Controller {

  function __construct() {
    parent::__construct();
    $this->load->model('licences_model','licences');
  }

  public function index() {
    $this->_do_cache();

    $page_data = array(
      'page_title'    => 'Licence List',
      'breadcrumbs'   => array(
        array('title'=>'Licence List','link'=>'licences')
      )
    );

    $data = array(
      'licences' => $this->licences->all()
    );

    $this->load->view('templates/header', $page_data);
    $this->load->view('licences/list', $data);
    $this->load->view('templates/footer');

  }

  public function problem_licences() {
    $this->_do_cache();

    $page_data = array(
      'page_title'    => 'Problem Licences',
      'breadcrumbs'   => array(
        array('title'=>'Licence List','link'=>'licences'),
        array('title'=>'Problem Licences','link'=>'licences/problem-licences')
      )
    );

    $data = array(
      'licences' => $this->licences->where_problems()
    );

    $this->load->view('templates/header', $page_data);
    $this->load->view('licences/problems', $data);
    $this->load->view('templates/footer');

  }

}

/* End of file services.php */
/* Location: ./application/controllers/services.php */