<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Service_urls extends GOVUK_Controller {

  function __construct()
  {
    parent::__construct();
    $this->load->model('urls_model','urls');
    $this->load->model('services_model','services');
  }

  public function index()
  {

    show_404(current_url());

  }

  public function history($id) {

    $url = $this->urls->find_info($id);

    if(!$url) { show_404(current_url()); die(); }

    $page_data = array(
      'page_title'    => "URL $id - Service URLs",
      'breadcrumbs'   => array(
        array('title'=>'Service URLs','link'=>'services'),
        array('title'=>"URL $id",'link'=>array('urls','history',$id))
      )
    );

    $data = array(
      'url' => $url,
      'urlchecks' => $this->urls->get_check_history($id),
      'urlhistory' => $this->urls->get_history($id)
    );

    $this->load->view('templates/header', $page_data);
    $this->load->view('service_urls/history', $data);
    $this->load->view('templates/footer');
  }
}

/* End of file services.php */
/* Location: ./application/controllers/services.php */