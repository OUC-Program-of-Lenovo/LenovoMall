<?php if ( ! defined('BASEPATH')) exit('No direct script access allowed');

class Upload extends CI_Controller {
    function upload($field, $filename)
    {
        $config['upload_path'] = '../../html/upload/images/avatar/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['file_name'] = $filename;
        $config['file_ext_tolower'] = true;
        $config['overwrite'] = true;
        $config['max_width'] = 1024;
        $config['max_height'] = 768;
        $config['max_size'] = '102400';
        $this->load->library('upload', $config);

        return $this->upload->do_upload($field);
    }
}
?>