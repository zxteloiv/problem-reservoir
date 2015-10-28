<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Problem extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->get = $this->input->get();
        $this->post = $this->input->post();
    }

	public function index() {
        
        return;
	}

    public function byID($pid) {
        return;
    }

    public function byIDs($list) {
        echo urldecode($list);
    }

    public function create() {
    }

    public function del() {
        $this->load->model('ProblemOp');
        $this->ProblemOp->doDeletion($this->post);
    }
}
