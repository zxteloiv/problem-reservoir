<?php
class ProblemOp extends CI_Model {

    const DELETE_PID_NOT_FOUND = 5001;
    const DELETION_FAILED = 5002;

    function __construct() {
        parent::__construct();
        $this->db = $this->load->database('prs', true);
        $this->load->helper('utils');
    }

    function getProblemByID($pid){
        $results = $this->db->query('select * from problems where '.
            'problem_id = "'.  $this->db->escape($pid) . '"'
        );

        // nothing in the database
        if ($results === false || $results->num_rows() < 1) {
            return array();
        }

        $problems = array();
        foreach ($results->result_array() as $problem) {
            $problems[$problem['problem_id']] = $problem;
        }
        return $problems;
    }

    function createProblem($param) {
        $data = array();
        foreach ($param as $key => $val) {
            if (!empty($val)) continue;
            $data[$key] = $val;
        }
        $data = array(
            'course' => $param['course'],
            'chapter' => $param['chapter'],
            'points' => $param['points'],
            'difficulty' => $param['difficulty'],
            'description' => $param['description'],
            'create_time' => date('Y-m-d H:i:s'),
            'modify_time' => date('Y-m-d H:i:s'),
            'content' => $param['content'],
            'keypoints' => $param['keypoints']
        );

        return $this->db->insert('problems', $data);
    }

    function updateProblemByID($pid, $param) {
    }

    function doDeletion($param) {
        if (!isset($param['pid'])) {
            echo outputError(self::DELETE_PID_NOT_FOUND, 'unknown key to delete');
            return;
        }
        $pid = $param['pid'];

        $this->db->where('problem_id', $pid, true);
        $rtn = $this->db->delete('problems');

        if ($rtn && $this->db->affected_rows() > 0) {
            echo json_encode(array(
                'errno' => 0,
                'errmsg' => 'delete successfully'
            ));
        } else {
            echo outputError(self::DELETION_FAILED, 'failed to execute deletion');
        }

        return;
    }
}
