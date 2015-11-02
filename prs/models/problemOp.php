<?php
class ProblemOp extends CI_Model {

    const DELETE_PID_NOT_FOUND = 5001;
    const DELETION_FAILED = 5002;
    const EMPTY_PROBLEM_PARAM = 5003;
    const INSERTION_FAILED = 5004;
    const EMPTY_UPDATE_ID = 5005;
    const CONTENT_JSON_INVALID = 5006;

    const PAGE_PROBLEM_SIZE = 10;

    function __construct() {
        parent::__construct();
        $this->db = $this->load->database('prs', true);
        $this->load->helper('utils');
        date_default_timezone_set('Asia/Shanghai');
    }

    function getProblemByID($pid) {
        $sql = 'select * from problems where '.
            'problem_id = '.  $this->db->escape($pid);

        $results = $this->db->query($sql);

        // nothing in the database
        if ($results === false || $results->num_rows() < 1) {
            return array();
        }

        $problems = array();
        foreach ($results->result_array() as $problem) {
            //$problems[$problem['problem_id']] = $problem;
            $problem['pid'] = $problem['problem_id'];
            unset($problem['problem_id']); 
            $problem['content'] = json_decode($problem['content']);
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

    function doQuery($param) {
        if (!empty($param['course'])) {
            $this->db->where('course', $param['course'], true);
        }
        if (!empty($param['chapter'])) {
            $this->db->where('chapter', $param['chapter'], true);
        }
        if (!empty($param['keypoints'])) {
            $this->db->where('keypoints', $param['keypoints'], true);
        }
        if (!empty($param['difficulty'])) {
            $this->db->where('difficulty', $param['difficulty'], true);
        }
        if (!empty($param['points'])) {
            $this->db->where('points', $param['points'], true);
        }
        if (!empty($param['description'])) {
            $this->db->like('description', $param['description'], 'both', true);
        }

        $num = $this->db->count_all_results('problems', false);
        $pagenum = intval($num / self::PAGE_PROBLEM_SIZE) + 1;

        // page num starts from 1
        $page = intval(empty($param['page']) ? 1 : $param['page']);
        $offset = ($page - 1) * self::PAGE_PROBLEM_SIZE;
        if ($offset < 0) $offset = 0;

        $this->db->select('*');
        $query = $this->db->get(null, self::PAGE_PROBLEM_SIZE, $offset);

        $data = array();
        foreach ($query->result() as $row) {
            $content = json_decode($row->content, true);
            if ($content === null) {
                echo outputError(self::CONTENT_JSON_INVALID, 'content has illegal json');
                return;
            }

            $data[] = array(
                'pid' => $row->problem_id,
                'points' => $row->points,
                'course' => $row->course,
                'chapter' => $row->chapter,
                'keypoints' => $row->keypoints,
                'difficulty' => $row->difficulty,
                'description' => $row->description,
                'content' => $content,
                'create_time' => $row->create_time,
                'modify_time' => $row->modify_time
            );
        }

        $rtn = array(
            'errno' => 0,
            'errmsg' => 'success',
            'data' => $data,
            'pagenum' => $pagenum,
            'pageid' => $page
        );

        echo json_encode($rtn);
    }

    function doDeletion($param) {
        if (!isset($param['pid'])) {
            echo outputError(self::DELETE_PID_NOT_FOUND, 'unknown key to delete');
            return;
        }
        $pid = $param['pid'];

        $this->db->where('problem_id', $pid, true);
        $rtn = $this->db->delete('problems');
        // it may be wiser to delete attachments later on in maintaining process

        if ($rtn && $this->db->affected_rows() > 0) {
            echo json_encode(array( 'errno' => 0, 'errmsg' => 'delete success'));
        } else {
            echo outputError(self::DELETION_FAILED, 'failed to execute deletion');
        }

        return;
    }
}
