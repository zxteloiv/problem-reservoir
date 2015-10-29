<?php
class ProblemOp extends CI_Model {

    const DELETE_PID_NOT_FOUND = 5001;
    const DELETION_FAILED = 5002;
    const EMPTY_PROBLEM_PARAM = 5003;
    const INSERTION_FAILED = 5004;
    const EMPTY_UPDATE_ID = 5005;

    function __construct() {
        parent::__construct();
        $this->db = $this->load->database('prs', true);
        $this->load->helper('utils');
        date_default_timezone_set('Asia/Shanghai');
    }

    function getProblemByID($pid) {
        $results = $this->db->query('select * from problems where '.
            'problem_id = "'.  $this->db->escape($pid) . '"'
        );

        // nothing in the database
        if ($results === false || $results->num_rows() < 1) {
            return array();
        }

        $problems = array();
        foreach ($results->result_array() as $problem) {
            //$problems[$problem['problem_id']] = $problem;
            $problems[] = $problem;
        }
        return $problems;
    }

    function doCreation($param) {
        if (empty($param['content'])) {
            echo outputError(self::EMPTY_PROBLEM_PARAM, 'problem content is empty');
            return;
        }

        $this->set_val_if_not_empty($param, 'content');
        $this->set_val_if_not_empty($param, 'course');
        $this->set_val_if_not_empty($param, 'chapter');
        $this->set_val_if_not_empty($param, 'difficulty');
        $this->set_val_if_not_empty($param, 'description');
        $this->set_val_if_not_empty($param, 'points');
        $this->set_val_if_not_empty($param, 'keypoints');
        $this->db->set('create_time', date('Y-m-d H:i:s'));
        $this->db->set('modify_time', date('Y-m-d H:i:s'));

        $rtn = $this->db->insert('problems');
        if ($rtn) {
            $pid = $this->db->insert_id();
            echo json_encode(array('errno' => 0, 'errmsg' => 'create success',
                'pid' => $pid));
        } else {
            echo outputError(self::INSERTION_FAILED, 'failed to create');
        }
        return;
    }

    function copy_key_if_not_empty($src, &$dst, $key) {
        $this->copy_key_if($src, $dst, $key, !empty($src[$key]));
    }

    function copy_key_if($src, &$dst, $key, $pred) {
        if ($pred) $dst[$key] = $src[$key];
    }

    function doUpdating($param) {
        if (empty($param['pid'])) {
            echo outputError(self::EMPTY_UPDATE_ID, 'problem id is empty');
            return;
        }

        $this->set_val_if_not_empty($param, 'content');
        $this->set_val_if_not_empty($param, 'course');
        $this->set_val_if_not_empty($param, 'chapter');
        $this->set_val_if_not_empty($param, 'difficulty');
        $this->set_val_if_not_empty($param, 'description');
        $this->set_val_if_not_empty($param, 'points');
        $this->set_val_if_not_empty($param, 'keypoints');

        $this->db->set('modify_time', date('Y-m-d H:i:s'));
        $this->db->where('problem_id', $param['pid'], true);

        $rtn = $this->db->update('problems');
        if ($rtn) {
            echo json_encode(array('errno' => 0, 'errmsg' => 'update success',
                'affected_rows' => $this->db->affected_rows()
            ));
        } else {
            echo outputError(self::UPDATE_FAILED, 'failed to update');
        }

        return;
    }

    function set_val_if_not_empty($param, $key) {
        if (!empty($param[$key])) {
            $this->db->set($key, $param[$key], true);
        }
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
            echo json_encode(array( 'errno' => 0, 'errmsg' => 'delete success'));
        } else {
            echo outputError(self::DELETION_FAILED, 'failed to execute deletion');
        }

        return;
    }
}
