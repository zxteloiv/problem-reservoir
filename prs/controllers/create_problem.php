<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Create_problem extends CI_Controller {

	public function index() {
        $this->get = $this->input->get();
        $this->post = $this->input->post();
        $this->file = $_FILES;
        
        return;
	}
}