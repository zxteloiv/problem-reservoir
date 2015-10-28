<?php
class Image extends CI_Model {

    function __construct() {
        parent::__construct();
        $this->db = $this->load->database('prs', true);
    }

    function getImageByID($img_id){
        $results = $this->db->query('select * from img_attachment where '.
            'img_id = "'.  $this->db->escape($img_id) . '"'
        );

        // nothing in the database
        if ($results === false || $results->num_rows() < 1) {
            return array();
        }

        $images = array();
        foreach ($results->result_array() as $image) {
            $images[$image['problem_id']] = $image;
        }
        return $problems;
    }

    function getImgAttrByID($img_id) {
        $results = $this->db->query(
            'select img_id, img_name, img_type, img_size, create_time' .
            'from img_attachment where ' .
            'img_id = "'.  $this->db->escape($img_id) . '"'
        );

        if ($results == false || $results->num_rows() != 1) {
            return array();
        }

        return $results->row_array();
    }

    function getRawImageByID($img_id) {
        $sql = 'select img from img_attachment where img_id = '.
            $this->db->escape($img_id);
        $results = $this->db->query($sql);

        if ($results == false || $results->num_rows() != 1) {
            return null;
        }

        return $results->row()->img;
    }

}
