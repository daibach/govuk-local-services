<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Service_urls extends GOVUK_Controller {

  function __construct() {
    parent::__construct();
    $this->load->model('urls_model','urls');
    $this->load->model('services_model','services');
  }

  public function index() {

    show_404(current_url());

  }

  public function report($id) {
    $url = $this->urls->find_info($id);

    if(!$url) { show_404(current_url()); die(); }

    $page_data = array(
      'page_title'    => "Report URL $id - Service URLs",
      'breadcrumbs'   => array(
        array('title'=>'Service URLs','link'=>'services'),
        array('title'=>"URL $id",'link'=>array('service-urls','history',$id)),
        array('title'=>"Report Problem",'link'=>array('service-urls','report',$id))
      )
    );

    $data = array(
      'url' => $url
    );

    $this->load->view('templates/header', $page_data);
    $this->load->view('service_urls/report', $data);
    $this->load->view('templates/footer');
  }

  public function history($id) {
    $this->_do_cache();

    $url = $this->urls->find_info($id);

    if(!$url) { show_404(current_url()); die(); }

    $page_data = array(
      'page_title'    => "URL $id - Service URLs",
      'breadcrumbs'   => array(
        array('title'=>'Service URLs','link'=>'services'),
        array('title'=>"URL $id",'link'=>array('service-urls','history',$id))
      )
    );

    $data = array(
      'url' => $url,
      'urlchecks' => $this->urls->get_check_history($id),
      'urlhistory' => $this->urls->get_history($id),
      'reports' => $this->urls->get_reports($id),
    );

    $this->load->view('templates/header', $page_data);
    $this->load->view('service_urls/history', $data);
    $this->load->view('templates/footer');
  }

  public function problem_urls() {
    $this->_do_cache();

    $page_data = array(
      'page_title'    => "Problem URLs - Service URLs",
      'breadcrumbs'   => array(
        array('title'=>'Service URLs','link'=>'services'),
        array('title'=>"Problem URLs",'link'=>array('service-urls','problem-list'))
      )
    );

    $data = array(
      'problem_urls' => $this->urls->get_problem_urls()
    );

    $this->load->view('templates/header', $page_data);
    $this->load->view('service_urls/problems', $data);
    $this->load->view('templates/footer');
  }

  public function log_report($id) {
    $this->load->library('form_validation');

    $url = $this->urls->find_info($id);
    if(!$url) { show_404(current_url()); die(); }

    //validate form
    $this->form_validation->set_error_delimiters('<li>', '</li>');
    $this->form_validation->set_rules('inputProblemType','type of problem','trim|required|callback_validate_problem_type|xss_clean');
    $this->form_validation->set_rules('inputNotes','notes','trim|xss_clean');
    $this->form_validation->set_rules('inputAlternativeURL','alternative url', 'trim|xss_clean|prep_url');

    if ($this->form_validation->run() == FALSE) {
      //form validation has failed. show the form again
      $this->report($id);
    } else {

      $this->urls->report(
        $url->id,
        $this->input->post('inputProblemType',TRUE),
        $this->input->post('inputNotes',TRUE),
        $this->input->post('inputAlternativeURL',TRUE)
      );

      $this->session->set_flashdata('report_success', 'The new report has been received. Thanks.');
      redirect(site_url(array('service-urls','history',$url->id)));

    }
  }

  function validate_problem_type($str) {
    switch($str) {
      case "broken":
      case "wrong_url":
      case "other":
        return TRUE;
        break;
      default:
        $this->form_validation->set_message("validate_problem_type",'The %s field is invalid');
        return FALSE;
    }
  }

}

/* End of file services.php */
/* Location: ./application/controllers/services.php */