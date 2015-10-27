<?php
class SaveImg extends CI_Model {

    const INPUT_KEY_NOT_SET = 4001;
    const FILE_COUNT_INCONSISTENT = 4002;
    const FILE_KEY_NOT_SET = 4003;
    const FILE_JSON_NOT_VALID = 4004;
    const FILE_SIZE_INCONSISTENT = 4005;
    const DB_INSERTION_FAILURE = 4006;

    function __construct() {
        parent::__construct();
        $this->db = $this->load->database('prs', true);
    }

    function do_the_job($param) {
        $this->post = $param;

        // 1. decode param and check if it's valid
        $rtn = $this->decodeFilesParam();
        if (!$rtn) { return; }

        $rtn = $this->isValidParam($this->post);
        if (!$rtn) { return; }

        // 2. iterate over file
        $img_queue = array(); // mutable var, will be passed as reference later
        $rtn = $this->putImgToQueue($img_queue);
        if (!$rtn) { return; }

        // 3. save all files
        $this->db = $this->load->database('prs', true);
        $rtn = $this->insertImgs($img_queue);
        if ($rtn) {
            $this->outputSuccess($img_queue);
            return;
        }

        fastcgi_finish_request();

        // user interaction ends here
        // -----------------------------------------------------------------
        // background task starts here

        $this->rollbackInsertedImg($files);
        return;
    }

    function outputSuccess($img_queue) {
        $rtn = array();
        $rtn['errno'] = 0;
        $rtn['errmsg'] = 'success';
        $rtn['idmap'] = array();
        for ($i = 0; $i < count($img_queue); $i++) {
            $rtn['idmap'][] = array(
                'name' => $img_queue[$i]['name'],
                'id' => $img_queue[$i]['saved_id'],
            );
        }

        $json_str = json_encode($rtn);
        echo $json_str;
        return;
    }

    function rollbackInsertedImg($files) {
        foreach ($files as $file) {
            if ($file['saved_id'] > 0) {
                $this->db->delete('img_attachment',
                    array('img_id' => $file['saved_id']));
            }
        }
    }

    function insertImgs(&$files) {
        $this->db = $this->load->database('prs', true);
        foreach ($files as &$file) {
            $data = array(
                'img_name' => $file['name'],
                'img_size' => $file['size'],
                'img_type' => $file['type'],
                'create_time' => date('Y-m-d H:i:s'),
                'img' => $file['content']
            );
            $rtn = $this->db->insert('img_attachment', $data, true);
            if (!$rtn) {
                $this->outputError(self::DB_INSERTION_FAILURE, 'failed to insert db');
                return false;
            }

            $file['saved_id'] = $this->db->insert_id();
        }

        return true;
    }

    function outputError($errno, $errmsg) {
        $rtn = array(
            'errno' => $errno,
            'errmsg' => $errmsg
        );
        echo json_encode($rtn);
    }

    function decodeFilesParam() {
        if (!isset($this->post['files'])) {
            $this->outputError(self::INPUT_KEY_NOT_SET, 'input key missing');
            return false;
        }

        $decoded_files = json_decode($this->post['files'], true);
        if ($decoded_files === null) {
            $this->outputError(self::FILE_JSON_NOT_VALID,
                'file json is not valid');

            return false;
        }

        $this->post['files'] = $decoded_files;
        return true;
    }

    function putImgToQueue(&$img_queue) {
        foreach ($this->post['files'] as $file) {
            if (!$this->isValidFile($file)) {
                return false;
            }

            $content = base64_decode($file['content']);
            
            /* different base64 routine may result different decoded binary
            if (strlen($content) != intval($file['size'])) {
                $this->outputError(self::FILE_SIZE_INCONSISTENT,
                    'file is incomplete');
                return false;
            }
             */

            // add to the file queue
            $img_queue[] = array(
                'name' => $file['name'],
                'size' => $file['size'],
                'type' => $file['type'],
                'saved_id' => 0,
                'content' => $content
            );
        }

        return true;
    }

    function isValidFile($file) {
        if (!(isset($file['name']) && isset($file['size']) && isset($file['type'])
            && isset($file['content']))) {
            $this->outputError(self::FILE_KEY_NOT_SET, 'file param is not set correctly');
            return false;
        }

        return true;
    }

    function isValidParam($param) {
        if (!isset($param['filecount']) || !isset($param['files'])) {
            $this->outputError(self::INPUT_KEY_NOT_SET, 'param is not valid');
            return false;
        }

        if (intval($param['filecount']) != count($param['files'])) {
            $this->outputError(self::FILE_COUNT_INCONSISTENT, 'file count inconsistent');
            return false;
        }

        return true;
    }
}
