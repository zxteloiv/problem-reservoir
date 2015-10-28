<?php
class Problem extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->db = $this->load->database('prs', true);
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
}
