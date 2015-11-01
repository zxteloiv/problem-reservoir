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

    public function search() {
        if (isset($this->post['pid'])) {
            $this->byID($this->post['pid']);
            return;
        }

        $this->ProblemOp->doQuery($this->post);
    }

    public function byID($pid) {
        $data = $this->ProblemOp->getProblemByID($pid);
        if (empty($data)) {
            echo json_encode(array('errno' => 0, 'errmsg' => 'success',
                'data' => array(), 'pageid' => 0, 'pagenum' => 0));
            return;
        }

        echo json_encode(array('errno' => 0, 'errmsg' => 'success',
            'data' => $data, 'pageid' => 1, 'pagenum' => 1
        ));
    }

    public function byIDs($list) {
        echo json_encode(array('errno' => 233, 'errmsg' => 'not implemented'));
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
