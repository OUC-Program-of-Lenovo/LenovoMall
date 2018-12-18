<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class Challenge extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('challenge_model');
        $this->load->config('email');
        $this->load->helper('string');
        $this->load->library('email');
        $this->load->library('session');
        $this->load->helper('email');
        $this->load->helper('url');
    }


    public function is_overdue($alive_time)
    {
        return (time() > $alive_time);
    }


    public function is_logined()
    {
        if($this->session->user_id === NULL){
            return false;
        }else{
            $session_alive_time = $this->session->session_alive_time;
            if($this->is_overdue($session_alive_time)){
                return false;
            }else{
                return true;
            }
        }
    }

    public function get_all_challenges()
    {
        if($this->is_logined() === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Please login first!',
            )));
        }

        $user_id = $this->session->user_id;
        $challenges_data = $this->challenge_model->get_all_challenges($user_id);
        if(count($challenges_data) === 0){
            die(json_encode(array(
                'status' => 0,
                'message' => 'No challenge!',
            )));
        }else{
            echo json_encode(array(
                    'status' => 1,
                    'message' => $challenges_data,
            ));
        }
    }

    public function get_challenge_info()
    {
        if($this->is_logined() === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Please login first!',
            )));
        }
        $user_id = $this->session->user_id;
        $challenge_id = intval($this->uri->segment(3));
        $challenge_info = $this->challenge_model->get_challenge_info($challenge_id,$user_id);
        if($challenge_info === NULL){
            die(json_encode(array(
                'status' => 0,
                'message' => 'No challenge!',
            )));
        }else{
            echo json_encode(array(
                    'status' => 1,
                    'message' => $challenge_info,
            ));
            // update visit times
            $this->update_visit_times($challenge_id);
        }
    }

    public function update_visit_times($challenge_id)
    {
        $this->challenge_model->update_visit_times($challenge_id);
    }

    public function get_type_challenges()
    {
        if($this->is_logined() === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Please login first!',
            )));
        }
        $user_id = $this->session->user_id;
        $type = strval($this->uri->segment(3));
        $current_type = $this->complete_challenge_type($type);
        $challenges_data = $this->challenge_model->get_type_challenges($user_id, $current_type);
        if (count($challenges_data) === 0){
            die(json_encode(array(
                'status' => 0,
                'message' => 'No challenge!',
            )));
        }else{
            echo json_encode(array(
                    'status' => 1,
                    'message' => $challenges_data,
            ));
        }
    }

    public function get_encrypted_flag($flag)
    {
        return md5($flag);
    }

    public function is_admin()
    {
        if($this->is_logined() === true && intval($this->session->usertype) === 1){
            return true;
        }
        return false;
    }

    public function complete_challenge_type($type)
    {
        switch ($type) {
            case 'web':
            case 'pwn':
            case 'misc':
            case 'stego':
            case 'reverse':
            case 'crypto':
            case 'forensics':
                $current_type = $type;
                break;
            case 'all':
                $current_type = '*';
                break;
            default:
                $current_type = 'other';
                break;
        }
        return $current_type;
    }

    public function check_bad_chars($word, $bad_chars)
    {
        $default_bad_chars = '';
        $default_bad_chars .= $bad_chars;
        for ($i=0; $i < strlen($word); $i++) {
            for ($j=0; $j < strlen($default_bad_chars); $j++) {
                if ($word[$i] === $bad_chars[$j]){
                    return false;
                }
            }
        }
        return true;
    }

    public function check_challenge_bad_chars($password)
    {
        return $this->check_bad_chars($password, '');
    }

    public function check_challenge_type($type)
    {
        switch ($type) {
            case 'web':
            case 'pwn':
            case 'misc':
            case 'stego':
            case 'reverse':
            case 'crypto':
            case 'forensics':
            case 'other':
            case 'all':
                return true;
            default:
               return false;
        }
    }

    public function create_challenge()
    {
        if($this->is_logined() === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Please login first!',
            )));
        }

        if($this->is_admin() === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'You don\'t have permission to access this!',
            )));
        }

        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        $this->form_validation->set_rules('flag', 'Flag', 'trim|required');
        $this->form_validation->set_rules('score', 'Score', 'trim|required');
        $this->form_validation->set_rules('type', 'Type', 'trim|required');
        $this->form_validation->set_rules('fixing', 'Fixing', 'trim|required');

        if ($this->form_validation->run() === FALSE)
        {
            die(json_encode(array(
                'status' => 0,
                'message' => 'Form validation failed!',
            )));
        }

        $author_id = $this->session->user_id;
        $challenge_info = array(
            'name' => $this->input->post('name'),
            'description' => $this->input->post('description'),
            'flag' => $this->get_encrypted_flag($this->input->post('flag')),
            'flag_text' => $this->input->post('flag'),
            'score' => $this->input->post('score'),
            'type' => $this->complete_challenge_type($this->input->post('type')),
            'online_time' => time(),
            'visit_times' => 0,
            'fixing' => intval($this->input->post('fixing')),
            'resource' => $this->input->post('resource'),
            'author_id' => $author_id,
        );

        if($this->check_challenge_bad_chars($challenge_info['name']) === false){
             die(json_encode(array(
                'status' => 0,
                'message' => 'Invalid challenge name!'
            )));
        }elseif($this->check_challenge_bad_chars($challenge_info['description']) === false){
             die(json_encode(array(
                'status' => 0,
                'message' => 'Invalid description!'
            )));
        }elseif($this->check_challenge_bad_chars($challenge_info['resource']) === false){
             die(json_encode(array(
                'status' => 0,
                'message' => 'Invalid resource!'
            )));
        }elseif($this->check_challenge_bad_chars($challenge_info['flag_text']) === false){
             die(json_encode(array(
                'status' => 0,
                'message' => 'Invalid flag!'
            )));
        }elseif($this->check_challenge_type($challenge_info['type']) === false){
             die(json_encode(array(
                'status' => 0,
                'message' => 'Invalid type!'
            )));
        }elseif(!is_numeric($challenge_info['score']) ){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Invalid score! Only numbers are allowed!'
            )));
        }elseif ($challenge_info['score'] < 0) {
            die(json_encode(array(
                'status' => 0,
                'message' => 'Invalid score! Only positive numbers are allowed!'
            )));
        }elseif ($challenge_info['score'] > 999999) {
            die(json_encode(array(
                'status' => 0,
                'message' => 'Invalid score! Score should be less 1000000!'
            )));
        }

        if($this->challenge_model->create_challenge($challenge_info) === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Create challenge failed! Please contact admin!',
            )));
        }

        echo json_encode(array(
            'status' => 1,
            'message' => 'Create challenge success!',
        ));
    }


    public function delete_challenge()
    {
        if($this->is_logined() === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Please login first!',
            )));
        }

        if($this->is_admin() === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'You don\'t have permission to access this!',
            )));
        }

        $challenge_id = intval($this->uri->segment(4));

        if($this->challenge_model->is_challenge_existed($challenge_id) === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Challenge not exists!',
            )));
        }

        $users_id = $this->challenge_model->get_challenge_solved_user_id($challenge_id);
        if($users_id !== NULL){
            $challenge_score = $this->challenge_model->get_challenge_socre($challenge_id);
            foreach ($users_id as $user_id) {
                $user_score = $this->user_model->get_score_by_user_id($user_id);
                $this->user_model->set_score_by_user_id($user_id, $user_score - intval($challenge_score));
            }
            if($this->challenge_model->delete_challenge($challenge_id) === false){
                die(json_encode(array(
                    'status' => 0,
                    'message' => 'Delete challenge failed! Please contact admin!',
                )));
            }
        }

        if($this->challenge_model->delete_challenge($challenge_id) === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Delete challenge failed! Please contact admin!',
            )));
        }

        echo json_encode(array(
            'status' => 1,
            'message' => 'Delete challenge success!',
        ));
    }

    public function fix_challenge()
    {
        if($this->is_logined() === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Please login first!',
            )));
        }

        if($this->is_admin() === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'You don\'t have permission to access this!',
            )));
        }

        $challenge_id = intval($this->uri->segment(4));

        if($this->challenge_model->is_challenge_existed($challenge_id) === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'No challenge!',
            )));
        }

        if($this->challenge_model->fix_challenge($challenge_id) === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Offline challenge failed! Please contact admin!',
            )));
        }

        echo json_encode(array(
            'status' => 1,
            'message' => 'Offline challenge success!',
        ));
    }


    public function fixed_challenge()
    {
        if($this->is_logined() === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Please login first!',
            )));
        }

        if($this->is_admin() === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'You don\'t have permission to access this!',
            )));
        }

        $challenge_id = intval($this->uri->segment(4));

        if($this->challenge_model->is_challenge_existed($challenge_id) === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'No challenge!',
            )));
        }

        if($this->challenge_model->fixed_challenge($challenge_id) === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Online challenge failed! Please contact admin!',
            )));
        }

        echo json_encode(array(
            'status' => 1,
            'message' => 'Online challenge success!',
        ));
    }

    public function update_challenge()
    {
        if($this->is_logined() === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Please login first!',
            )));
        }

        if($this->is_admin() === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'You don\'t have permission to access this!',
            )));
        }

        $challenge_id = intval($this->uri->segment(4));

        if($this->challenge_model->is_challenge_existed($challenge_id) === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Challenge not exists!',
            )));
        }

        if($this->challenge_model->is_challenge_online($challenge_id) === true){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Please offline this challenge before update it!',
            )));
        }

        $challenge_info = array();
        if(strlen($this->input->post('name')) > 0){
            $challenge_info['name'] = $this->input->post('name');
        }else {
            die(json_encode(array(
                'status' => 0,
                'message' => 'Invalid challenge name!'
            )));
        }
        if(strlen($this->input->post('description')) > 0){
            $challenge_info['description'] = $this->input->post('description');
        }else {
            die(json_encode(array(
                'status' => 0,
                'message' => 'Invalid description!'
            )));
        }
        if(strlen($this->input->post('flag')) > 0){
            $challenge_info['flag'] = $this->get_encrypted_flag($this->input->post('flag'));
             $challenge_info['flag_text'] = $this->input->post('flag');
        }else {
            die(json_encode(array(
                'status' => 0,
                'message' => 'Invalid flag!'
            )));
        }
        if(strlen($this->input->post('score')) > 0){
            $challenge_info['score'] = $this->input->post('score');
            if(!is_numeric($challenge_info['score'])){
                die(json_encode(array(
                    'status' => 0,
                    'message' => 'Invalid score! Only numbers are allowed!'
                )));
            }elseif ($challenge_info['score'] < 0) {
                die(json_encode(array(
                    'status' => 0,
                    'message' => 'Invalid score! Only positive numbers are allowed!'
                )));
            }elseif ($challenge_info['score'] > 999999) {
                die(json_encode(array(
                    'status' => 0,
                    'message' => 'Invalid score! Score should be less 1000000!'
                )));
            }
        }else {
            die(json_encode(array(
                'status' => 0,
                'message' => 'Invalid score!'
            )));
        }
        if(strlen($this->input->post('type')) > 0){
            $challenge_info['type'] = $this->complete_challenge_type($this->input->post('type'));
        }else {
            die(json_encode(array(
                'status' => 0,
                'message' => 'Invalid type!'
            )));
        }
        if(strlen($this->input->post('resource')) > 0){
            $challenge_info['resource'] = $this->input->post('resource');
        }else {
            die(json_encode(array(
                'status' => 0,
                'message' => 'Invalid resource!'
            )));
        }

        if($this->check_challenge_bad_chars($challenge_info['name']) === false){
             die(json_encode(array(
                'status' => 0,
                'message' => 'Invalid challenge name!'
            )));
        }elseif($this->check_challenge_bad_chars($challenge_info['description']) === false){
             die(json_encode(array(
                'status' => 0,
                'message' => 'Invalid description!'
            )));
        }elseif($this->check_challenge_bad_chars($challenge_info['resource']) === false){
             die(json_encode(array(
                'status' => 0,
                'message' => 'Invalid resource!'
            )));
        }elseif($this->check_challenge_bad_chars($challenge_info['flag_text']) === false){
             die(json_encode(array(
                'status' => 0,
                'message' => 'Invalid flag!'
            )));
        }elseif($this->check_challenge_type($challenge_info['type']) === false){
             die(json_encode(array(
                'status' => 0,
                'message' => 'Invalid type!'
            )));
        }elseif(!is_numeric($challenge_info['score'])){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Invalid score! Only numbers are allowed!'
            )));
        }elseif ($challenge_info['score'] < 0) {
            die(json_encode(array(
                'status' => 0,
                'message' => 'Invalid score! Only positive numbers are allowed!'
            )));
        }elseif ($challenge_info['score'] > 999999) {
            die(json_encode(array(
                'status' => 0,
                'message' => 'Invalid score! Score should be less 1000000!'
            )));
        }

        if($this->challenge_model->update_challenge($challenge_id, $challenge_info) === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Update challenge failed! Please contact admin!',
            )));
        }

        echo json_encode(array(
            'status' => 1,
            'message' => 'Update challenge success!',
        ));
    }

    public function is_flag_current($current_flag, $flag)
    {
        return ($this->get_encrypted_flag($flag) === $current_flag);
    }

    public function submit()
    {
        if($this->is_logined() === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Please login first!',
            )));
        }

        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('challenge_id', 'Challenge ID', 'trim|required');
        $this->form_validation->set_rules('flag', 'Flag', 'trim|required');

        if ($this->form_validation->run() === FALSE)
        {
            die(json_encode(array(
                'status' => 0,
                'message' => 'Form validation failed!',
            )));
        }

        $intervals = 10;
        if($this->is_brute_force($intervals) === true){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Please DO NOT violently guess flag. Submit once every 10 seconds!',
            )));
        }

        $challenge_id = intval($this->input->post('challenge_id'));
        $flag = $this->input->post('flag');

        if($this->challenge_model->is_challenge_existed($challenge_id) === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'No challenge!',
            )));
        }

        $user_id = $this->session->user_id;

        if($this->challenge_model->is_solved_by_user_id($challenge_id, $user_id) === true){
            die(json_encode(array(
                'status' => 0,
                'message' => 'You have solved this challenge!',
            )));
        }

        $challenge_info = $this->challenge_model->get_challenge_info_full($challenge_id, $user_id);

        $current_flag = $challenge_info['flag'];
        $is_current = $this->is_flag_current($current_flag, $flag);

        $submit_info = array(
            'challenge_id' => $challenge_id,
            'user_id' => $user_id,
            'flag' => $flag,
            'submit_time' => time(),
            'is_current' => $is_current,
        );

        $this->challenge_model->insert_submit_log($submit_info);


        if($is_current === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Invalid flag!',
            )));
        }

        echo json_encode(array(
            'status' => 1,
            'message' => 'Correct flag!',
        ));

        // update user score
        $user_score = $this->user_model->get_score_by_user_id($user_id);
        $this->user_model->set_score_by_user_id($user_id, $user_score + intval($challenge_info['score']));

    }

    public function progress(){
        if($this->is_logined()){
            $offset_time = 60 * 60 * 12; // 12 hours
            echo json_encode($this->challenge_model->get_progress($offset_time));
        }else{
            echo '';
        }
    }

    public function is_brute_force($intervals)
    {
        $user_id = $this->session->user_id;
        $last_submit_time = $this->user_model->get_last_submit_time($user_id);
        $time = time();
        return (($time - $last_submit_time) < $intervals);
    }

    public function progress_qqbot()
    {
        $offset_time = 60 * 60 * 12; // 12 hours
        echo json_encode($this->challenge_model->get_progress($offset_time));
    }


    public function new_progress_qqbot()
    {
        $offset_time = 30; // 30 s
        echo json_encode($this->challenge_model->get_progress($offset_time));
    }

    public function get_all_challenges_info()
    {
        if($this->is_logined() === false || $this->is_admin() === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'You don\'t have permission to access this!'
            )));
        }
        $challenge_info = $this->challenge_model->get_all_challenges_info();

        echo json_encode(array(
                'status' => 1,
                'value' => $challenge_info
            ));
    }

    function check_challenge_name_existed()
    {
        if($this->is_logined() === false || $this->is_admin() === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'You don\'t have permission to access this!'
            )));
        }
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('challenge_name', 'Challenge_name', 'trim|required');
        if ($this->form_validation->run() === FALSE)
        {
            die(json_encode(array(
                'status' => 0,
                'message' => 'Please enter challenge name!',
            )));
        }
        $challenge_name = $this->input->post('challenge_name');

        if($this->challenge_model->is_challenge_name_existed($challenge_name) === true){
            die(json_encode(array(
                'status' => 0,
                'message' => 'This challenge name already exists!',
            )));
        }else{
            die(json_encode(array(
                'status' => 1,
                'message' => 'This challenge name is available!',
            )));
        }
    }
}