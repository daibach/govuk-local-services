<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Authorities extends GOVUK_Controller {

  function __construct()
  {
    parent::__construct();
    $this->load->model('services_model','services');
    $this->load->model('urls_model','urls');
    $this->load->model('authorities_model','authorities');
  }

  public function index()
  {

    $page_data = array(
      'page_title'    => 'Local Authority List',
      'breadcrumbs'   => array(
        array('title'=>'Local Authority List','link'=>'authorities')
      )
    );

    $data = array(
      'authorities' => $this->authorities->all()
    );

    $this->load->view('templates/header', $page_data);
    $this->load->view('authorities/list', $data);
    $this->load->view('templates/footer');

  }

  public function view($snac) {

    $authority = $this->authorities->find_by_snac($snac);

    if(!$authority) { show_404(current_url()); die(); }

    $page_data = array(
      'page_title'    => $authority->snac." ".$authority->name." - Local Authority List",
      'breadcrumbs'   => array(
        array('title'=>'Local Authority List','link'=>'authorities'),
        array('title'=>$authority->snac." ".$authority->name,'link'=>array('authorities',$snac))
      )
    );

    $data = array(
      'authority' => $authority,
      'urls' => $this->urls->get_url_status_for_snac($snac),
      'missing_services' => $this->authorities->find_missing_by_snac($snac)
    );

    $this->load->view('templates/header', $page_data);
    $this->load->view('authorities/view', $data);
    $this->load->view('templates/footer');
  }
}
/* End of file authorities.php */
/* Location: ./application/controllers/authorities.php */