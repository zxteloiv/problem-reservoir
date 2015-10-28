<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class Img extends CI_Controller {

	public function index($img_id) {
        return $this->loadImg($img_id);
	}

    public function save() {
        date_default_timezone_set('Asia/Shanghai');
        $this->load->model('SaveImg');
        $this->SaveImg->do_the_job($this->input->post());
    }

    public function loadImg($img_id) {
        $this->load->model('Image');
        $img_content = $this->Image->getRawImageByID($img_id);

        if ($img_content === null) {
            echo 'nullimg';
            return;
        }

        header('Content-Type: image/jpeg');
        echo $img_content;
        return;
    }

    public function attr($img_id) {
        $this->load->model('Image');
        $attr = $this->Image->getImgAttrByID($img_id);
        $attr['errno'] = 0;
        $attr['errmsg'] = '';
        echo json_encode($attr);
        return;
    }
}
