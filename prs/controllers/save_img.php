<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Save_img extends CI_Controller {

	public function index() {
        $this->load->model('SaveImg');
        $this->SaveImg->do_the_job($this->input->post());
	}

}
