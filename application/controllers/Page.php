<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Page extends CI_Controller {

		public function __construct(){
        parent::__construct();
        $this->load->model('Categories_model');
    }

    public function index($slug){
		$page = $this->Settings_model->getPageBySlug($slug);
		if($page == NULL){
			redirect(base_url() . '404');
		}else{
			$data['title'] = $page['title'] . ' - ' . $this->config->item('app_name');
			$data['css'] = 'page';
			$data['page'] = $page;
			$this->load->view('templates/header', $data);
			$this->load->view('templates/navbar');
			$this->load->view('page/page', $page);
			$this->load->view('templates/footer_notmpl');
		}
    }


}
