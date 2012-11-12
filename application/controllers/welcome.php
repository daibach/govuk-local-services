<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Welcome extends GOVUK_Controller {

  public function index()
  {

    $this->_do_cache();
    $this->load->view('templates/header');
    $this->load->view('main');
    $this->load->view('templates/footer');

  }

  public function check_queues() {

    $this->load->model('queues_model','queues');

    $page_data = array(
      'page_title'    => 'Check Queue Status',
      'breadcrumbs'   => array(
        array('title'=>'Check Queue Status','link'=>'check-queues')
      )
    );

    $data = array(
      'service_check_queue' => $this->queues->service_check_queue(),
      'import_check_queue' => $this->queues->url_import_check_queue()
    );

    $this->load->view('templates/header',$page_data);
    $this->load->view('queues',$data);
    $this->load->view('templates/footer');

  }
}

/* End of file welcome.php */
/* Location: ./application/controllers/welcome.php */