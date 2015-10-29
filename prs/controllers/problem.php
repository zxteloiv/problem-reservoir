<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Problem extends CI_Controller {

    function __construct() {
        parent::__construct();
        $this->get = $this->input->get();
        $this->post = $this->input->post();
        $this->load->model('ProblemOp');
    }

	public function index() {
        return;
	}

    public function byID($pid) {
        $data = $this->ProblemOp->getProblemByID($pid);
        echo json_encode(array('errno' => 0, 'errmsg' => 'success',
            'data' => array($data)
        ));
        return;
    }

    public function byIDs($list) {
        echo urldecode($list);
    }

    public function create() {
        $this->ProblemOp->doCreation($this->post);
    }

    public function update() {
        $this->ProblemOp->doUpdating($this->post);
    }

    public function del() {
        $this->ProblemOp->doDeletion($this->post);
    }
}
