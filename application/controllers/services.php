<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Services extends GOVUK_Controller {

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
      'page_title'    => 'Service List',
      'breadcrumbs'   => array(
        array('title'=>'Service List','link'=>'services')
      )
    );

    $data = array(
      'services' => $this->services->all()
    );

    $this->load->view('templates/header', $page_data);
    $this->load->view('services/list', $data);
    $this->load->view('templates/footer');

  }

  public function view($lgsl) {

    $service = $this->services->find_by_lgsl($lgsl);

    if(!$service) { show_404(current_url()); die(); }

    $page_data = array(
      'page_title'    => "LGSL $lgsl - Service List",
      'breadcrumbs'   => array(
        array('title'=>'Service List','link'=>'services'),
        array('title'=>"LGSL $lgsl",'link'=>array('services','view',$lgsl))
      )
    );

    $data = array(
      'service' => $service,
      'urls' => $this->urls->get_url_status_for_lgsl($lgsl),
      'missing_authorities' => $this->authorities->find_missing_by_lgsl($lgsl)
    );

    $this->load->view('templates/header', $page_data);
    $this->load->view('services/view', $data);
    $this->load->view('templates/footer');
  }
}

/* End of file services.php */
/* Location: ./application/controllers/services.php */