<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Welcome extends CI_Controller {

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
	 * @see http://codeigniter.com/user_guide/general/urls.html
	 */
	public function index()
	{
        $data = array();
        $data['title'] = '首页 - 题库系统';
        $data['active_title'] = 'home';

		$this->load->view('templates/header', $data);

		$this->load->view('welcome_message', $data);

        $this->load->model('ProblemOp');
        $val = $this->ProblemOp->getProblemByID(8);

        $debug['msg'] = var_export($val, true);
        $this->load->view('debug', $debug);

		$this->load->view('templates/footer', $data);
	}
}
