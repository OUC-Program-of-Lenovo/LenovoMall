<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Page extends CI_Controller {
	/**
	 * Index Page for this controller.
	 *
	 * Maps to the following URL
	 * 		http://example.com/index.php/welcome
	 *	- or -
	 * 		http://example.com/index.php/welcome/index
	 *	- or -
	 * Since this controller is set as the default controller in
	 * config/routes.php, it's displayed at http://example.com/
	 *
	 * So any other public methods not prefixed with an underscore will
	 * map to /index.php/welcome/<method_name>
	 * @see https://codeigniter.com/user_guide/general/urls.html
	 */

	public function __construct()
	{
	    parent::__construct();
	    $this->load->model('user_model');
	    $this->load->config('email');
	    $this->load->helper('string');
	    $this->load->library('email');
	    $this->load->library('session');
	    $this->load->helper('email');
	    $this->load->helper('url');
	}

	public function index()
	{
		if ($this->is_logined() === false){
			$this->load->view('/templates/header');
			$this->load->view('/slide_bar/header');
			$this->load->view('/slide_bar/content_visitor');
			$this->load->view('/content');
			$this->load->view('/slide_bar/footer');
			$this->load->view('/templates/footer');
		}else if($this->is_admin() === false){
			$this->load->view('/templates/header');
			$this->load->view('/slide_bar/header');
			$this->load->view('/slide_bar/content_user');
			$this->load->view('/content');
			$this->load->view('/slide_bar/footer');
			$this->load->view('/templates/footer');
		}else {
			$this->load->view('/templates/header');
			$this->load->view('/slide_bar/header');
			$this->load->view('/slide_bar/content_admin');
			$this->load->view('/content');
			$this->load->view('/slide_bar/footer');
			$this->load->view('/templates/footer');
		}
	}

	public function is_logined()
	{
	    return (
	        $this->session->user_id !== NULL &&
            $this->is_overdue($this->session->session_alive_time) === false
        );
	}

	public function is_overdue($alive_time)
	{
	    return (time() > $alive_time);
	}

	public function is_admin()
	{
	    return (intval($this->session->user_type) === 1);
	}
}