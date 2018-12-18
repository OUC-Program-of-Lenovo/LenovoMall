<?php
defined('BASEPATH') OR exit('No direct script access allowed');
class User extends CI_Controller {

    public function __construct()
    {
        parent::__construct();
        $this->load->model('user_model');
        $this->load->config('email');
        $this->load->helper('string');
        $this->load->library('email');
        $this->load->library('session');
        $this->load->helper('email');
        $this->load->helper('url');
    }

    /* Check length */
    public function check_length($word, $min, $max)
    {
        $length = strlen($word);
        if ($length > $max || $length < $min){
            return false;
        }
        return true;
    }

    public function check_username_length($username)
    {
        return $this->check_length($username, 4, 16);
    }

    public function check_password_length($password)
    {
        return $this->check_length($password, 6, 16);
    }

    public function check_student_id_length($student_id)
    {
        return $this->check_length($student_id, 11, 11);
    }

    /* Check bad chars */
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

    public function check_username_bad_chars($username)
    {
        return $this->check_bad_chars($username, '`~!@#$%^&*()_+-=[]\\{}|:";\'<>?,./');
    }

    public function check_password_bad_chars($password)
    {
        return $this->check_bad_chars($password, '');
    }

    /* Special check */
    public function check_grade($grade)
    {
        if(strlen($grade) !== 16 || !is_numeric(substr($grade, 0, 4))){
            return false;
        }
        return true;
    }

    public function check_email($email)
    {
        return valid_email($email);
    }

    /* Check existed */
    public function do_check_username_existed($username)
    {
        return $this->user_model->is_username_existed($username);
    }

    public function do_check_user_id_existed($user_id)
    {
        return $this->user_model->is_user_id_existed($user_id);
    }

    public function do_check_email_existed($email)
    {
        return $this->user_model->is_email_existed($email);
    }

    /* Send email */
    public function send_email($subject, $content, $target)
    {
        $this->email->from('admin@srpopty.cn', 'admin');
        $this->email->to($target);
        $this->email->subject($subject);
        $this->email->message($content);
        if( $this->email->send())
            return true;
        else
            return false;
    }

    public function send_active_code($active_code, $target)
    {
        $subject = '[No Reply] Blue Whale Exercise OJ Register Email';
        $content = "Thank you for registering this website!\nyou can activate your account by visiting the following link, which is valid for 2 hours.\nYour active code : http://".$_SERVER['SERVER_NAME'].":".$_SERVER["SERVER_PORT"]."/user/active/".$active_code."\nHave a nice day!";
        return $this->send_email($subject, $content, $target);
    }

    public function send_reset_code($reset_code, $target)
    {
        $subject = '[No Reply] Blue Whale Exercise OJ Reset Password Email';
        $content = "You can reset your password by visiting the following link, which is valid for 2 hours.\nYour reset code : http://".$_SERVER['SERVER_NAME'].":".$_SERVER["SERVER_PORT"]."/user/verify/".$reset_code."\nHave a nice day!";
        return $this->send_email($subject, $content, $target);
    }

    /* Password */
    public function get_encrypted_password($password, $salt)
    {
        return md5(md5($password.$salt));
    }

    /* Salt */
    public function get_salt()
    {
        return random_string('alnum', 16);
    }

    /* Active code */
    public function get_active_code()
    {
        return random_string('alnum', 32);
    }

    /* Reset password */
    public function get_reset_code()
    {
        return random_string('alnum', 32);
    }

    /* Check existed */
    public function check_email_existed()
    {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('email', 'Email', 'trim|required');

        if ($this->form_validation->run() === FALSE)
        {
             die(json_encode(array(
                'status' => 0,
                'message' => 'Please enter your email!',
            )));
        }
        $email = $this->input->post('email');

        if($this->do_check_email_existed($email) === true){
            die(json_encode(array(
                'status' => 0,
                'message' => 'This email is already exists!',
            )));
        }else{
            die(json_encode(array(
                'status' => 1,
                'message' => 'This email is available!',
            )));
        }
    }

    public function check_username_existed()
    {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        if ($this->form_validation->run() === FALSE)
        {
            die(json_encode(array(
                'status' => 0,
                'message' => 'Please enter your username!',
            )));
        }
        $username = $this->input->post('username');

        if($this->do_check_username_existed($username) === true){
            die(json_encode(array(
                'status' => 0,
                'message' => 'This username already exists!',
            )));
        }else{
            die(json_encode(array(
                'status' => 1,
                'message' => 'This username is available!',
            )));
        }
    }

    public function register()
    {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('email', 'Email', 'trim|required');
        $this->form_validation->set_rules('real_name', 'Real_name', 'trim|required');
        $this->form_validation->set_rules('student_id', 'Student_id', 'trim|required');
        $this->form_validation->set_rules('grade', 'Grade', 'trim|required');
        $this->form_validation->set_rules('phone', 'Phone', 'trim|required');
        $this->form_validation->set_rules('captcha', 'Captcha', 'trim|required');

        if ($this->form_validation->run() === FALSE){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Form validation failed!'
            )));
        }

        /* Post data */
        $captcha = intval($this->input->post('captcha'));
        $user_info = array(
            'username' => $this->input->post('username'),
            'password' => $this->input->post('password'),
            'email' => $this->input->post('email'),
            'real_name' => $this->input->post('real_name'),
            'student_id' => $this->input->post('student_id'),
            'grade' => $this->input->post('grade'),
            'phone' => $this->input->post('phone'),
            'qq' => $this->input->post('qq'),
            'wechat' => htmlspecialchars($this->input->post('wechat'))
        );

        /* Form validation */
        if($this->verify_captcha($captcha) === false){
              die(json_encode(array(
                'status' => 0,
                'message' => 'Wrong captcha!'
            )));
        }elseif($this->check_username_length($user_info['username']) === false){
             die(json_encode(array(
                'status' => 0,
                'message' => 'Username must be equal 4 or more and equal 16 or less characters!'
            )));
        }elseif($this->check_username_bad_chars($user_info['username']) === false){
             die(json_encode(array(
                'status' => 0,
                'message' => 'Invalid username! Only allowed letters and numbers, Please do not use special characters in username!'
            )));
        }elseif($this->do_check_username_existed($user_info['username']) === true){
             die(json_encode(array(
                'status' => 0,
                'message' => 'This username already exists!'
            )));
        }elseif($this->check_password_length($user_info['password']) === false){
             die(json_encode(array(
                'status' => 0,
                'message' => 'Password must be equal 6 or more and equal 16 or less characters!'
            )));
        }elseif($this->check_password_bad_chars($user_info['password']) === false){
             die(json_encode(array(
                'status' => 0,
                'message' => 'Invalid password! Please do not use special characters in password!'
            )));
        }elseif($this->check_email($user_info['email']) === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Invalid email format!'
            )));
        }elseif($this->do_check_email_existed($user_info['email']) === true){
            die(json_encode(array(
                'status' => 0,
                'message' => 'This email is already exists!'
            )));
        }elseif($this->check_student_id_length($user_info['student_id']) === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Invalid Student ID length!'
            )));
        }elseif(!is_numeric($user_info['student_id'])){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Invalid Student ID! Only numbers are allowed!'
            )));
        }elseif($this->check_grade($user_info['grade']) === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Invalid grade format!'
            )));
        }elseif($this->check_student_id_length($user_info['phone']) === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Invalid phone length!'
            )));
        }elseif(!is_numeric($user_info['phone'])){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Invalid phone! Only numbers are allowed!'
            )));
        }elseif(strlen($user_info['qq']) !== 0) {
            if(!is_numeric($user_info['qq'])){
                die(json_encode(array(
                'status' => 0,
                'message' => 'Invalid QQ numbers! Only numbers are allowed!'
            )));
            }
        }

        /* Register */
        $user_info = $this->complete_user_info($user_info);

        /* Into database */
        if($this->user_model->register($user_info) === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Failed to register! Please contact admin!'
            )));
        }

        /* Send active code */
        if($this->send_active_code($user_info['active_code'], $user_info['email']) === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Activation code failed to be sent! Please contact admin!'
            )));
        }

        echo json_encode(array(
            'status' => 1,
            'message' => 'Register successfully! Please  activate your account by login your email ('.$this->get_masked_email($user_info['email']).') and clicking the activation link in the activation email!',
        ));
    }

    /* Improve other necessary user information */
    public function complete_user_info($user_info)
    {
        $time = time();

        $user_info['salt'] = $this->get_salt();
        $user_info['password'] = $this->get_encrypted_password($user_info['password'], $user_info['salt']);

        $user_info['score'] = 0;

        $user_info['registe_time'] = $time;
        $user_info['registe_ip'] = $this->input->ip_address();

        $user_info['usertype'] = 0;

        $user_info['active_code'] = $this->get_active_code();
        $user_info['active_code_alive_time'] = $time + $this->config->item('sess_expiration');
        $user_info['actived'] = 0;

        return $user_info;
    }

    /* Status judge */
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

    public function is_overdue($alive_time)
    {
        return (time() > $alive_time);
    }

    public function is_user_actived($user_id)
    {
        return $this->user_model->is_user_actived($user_id);
    }

    public function is_baned()
    {
        return $this->user_model->is_user_baned($this->session->user_id);
    }

    public function login()
    {
        if ($this->is_logined()){
            echo json_encode(array(
                'status' => 1,
                'message' => 'Welcome back!',
            ));
            return;
        }

        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('username', 'Username', 'trim|required');
        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('captcha', 'Captcha', 'trim|required');

        if ($this->form_validation->run() === FALSE)
        {
           die(json_encode(array(
                'status' => 0,
                'message' => 'Form validation failed!'
            )));
        }

        /* Post data */
        $captcha = intval($this->input->post('captcha'));
        $user_info = array(
            'username' => $this->input->post('username'),
            'password' => $this->input->post('password'),
        );

        if($this->user_model->is_user_baned($this->user_model->get_user_id_by_username($user_info['username']))){
            die(json_encode(array(
                'status' => 0,
                'message' => 'You are not allowed login! Please contact admin.'
            )));
        }

        /* Form validation */
        if($this->verify_captcha($captcha) === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Wrong captcha!'
            )));
        }elseif($this->check_username_length($user_info['username']) === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Username must be equal 4 or more and equal 16 or less characters!'
            )));
        }elseif($this->check_username_bad_chars($user_info['username']) === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Invalid username! Only allowed letters and numbers, please do not use special characters in username!'
            )));
        }elseif($this->check_password_length($user_info['password']) === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Password must be equal 6 or more and equal 16 or less characters!'
            )));
        }elseif($this->check_password_bad_chars($user_info['password']) === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Invalid password! Please do not use special characters in password!'
            )));
        }

        /* Whether username exists */
        if($this->do_check_username_existed($user_info['username']) === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Username does not exist!'
            )));
        }

        $user_info['user_id'] = $this->user_model->get_user_id_by_username($user_info['username']);

        if($this->do_login($user_info['user_id'], $user_info['password']) === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Login failed!'
            )));
        }

        if($this->is_user_actived($user_info['user_id']) === false){
           die(json_encode(array(
                'status' => 0,
                'message' => 'Please activate your account!'
            )));
        }

        echo json_encode(array(
            'status' => 1,
            'message' => 'Login success!',
        ));

        /* set session */
        $this->set_session_by_username($user_info['username']);
    }

    public function set_session_by_username($username)
    {
        // get user_id
        $user_id = $this->user_model->get_user_id_by_username($username);
        // set session
        $this->set_session_by_user_id($user_id);
    }

    public function set_session_by_user_id($user_id)
    {
        $user_info = $this->user_model->get_user_info($user_id);
        // set session
        $data = array(
            'user_id' => $user_id,
            'username' => $user_info['username'],
            'email' => $user_info['email'],
            'score' => $user_info['score'],
            'usertype' => $user_info['usertype'],
            'session_alive_time' => (time() + $this->config->item('sess_expiration')),
        );
        $this->session->set_userdata($data);
    }

    public function do_login($user_id, $password)
    {
        $user_info = $this->user_model->get_user_info($user_id);
        $current_password = $user_info['password'];
        $salt = $user_info['salt'];
        $encrypted_password = $this->get_encrypted_password($password, $salt);
        return ($encrypted_password === $current_password);
    }

    public function active()
    {
        $active_code = $this->uri->segment(3);

        if($this->user_model->is_active_code_existed($active_code) === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Invalid activation code!',
            )));
        }

        $user_id = $this->user_model->get_user_id_by_active_code($active_code);
        $user_info = $this->user_model->get_user_info($user_id);
        $active_code_alive_time = $user_info['active_code_alive_time'];

        if ($this->is_overdue($active_code_alive_time) === true){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Your activation code has expired! Please contact admin!',
            )));
        }

        if ($this->is_user_actived($user_id) === true){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Your account has been activated! Please do not re-activate!',
            )));
        }

        if($this->user_model->active_user($user_id) === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Activation failed! Please contact the admin!',
            )));
        }

        echo json_encode(array(
            'status' => 1,
            'message' => 'Activation success!',
        ));

        // destory active code
        $this->destory_active_code($user_id);

        /* set session
        $this->set_session_by_user_id($user_id);

        /* TODO 暂时先重定向到 /usr/login */
        header("Location: /");
    }

    public function verify_captcha($captcha)
    {
        // First, delete old captchas
        $expiration = time() - 7200; // Two hour limit
        $this->db->where('captcha_time < ', $expiration)
            ->delete('captcha');
        // Then see if a captcha exists:
        $sql = 'SELECT COUNT(*) AS count FROM captcha WHERE word = ? AND ip_address = ? AND captcha_time > ?';
        $binds = array($captcha, $this->input->ip_address(), $expiration);
        $query = $this->db->query($sql, $binds);
        $row = $query->row();
        if ($row->count > 0)
        {
            return true;
        }else{
            return false;
        }
    }

    public function check_captcha_current()
    {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('captcha', 'Captcha', 'required');

        if ($this->form_validation->run() === FALSE)
        {
            die(json_encode(array(
                'status' => 0,
                'message' => 'Form validation failed!'
            )));
        }
        $captcha = intval($this->input->post('captcha'));


        // First, delete old captchas
        $expiration = time() - 7200; // Two hour limit
        $this->db->where('captcha_time < ', $expiration)
            ->delete('captcha');
        // Then see if a captcha exists:
        $sql = 'SELECT COUNT(*) AS count FROM captcha WHERE word = ? AND ip_address = ? AND captcha_time > ?';
        $binds = array($captcha, $this->input->ip_address(), $expiration);
        $query = $this->db->query($sql, $binds);
        $row = $query->row();
        if ($row->count > 0)
        {
            echo json_encode(array(
                'status' => 1,
                'message' => 'Correct captcha!',
            ));
        }else{
            die(json_encode(array(
                'status' => 0,
                'message' => 'Wrong captcha!',
            )));
        }
    }

    public function get_masked_email($email)
    {
        $mail_parts = explode("@", $email);
        $length = strlen($mail_parts[0]);
        $show = floor($length/2);
        $hide = $length - $show;
        $replace = str_repeat("*", $hide);
        return substr_replace ( $mail_parts[0] , $replace , $show, $hide ) . "@" . substr_replace($mail_parts[1], "**", 0, 2);
    }

    public function forget()
    {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('email', 'Email', 'required');
        $this->form_validation->set_rules('captcha', 'Captcha', 'required');

        if ($this->form_validation->run() === FALSE)
        {
            die(json_encode(array(
                'status' => 0,
                'message' => 'Form validation failed!',
            )));
        }

        /* Post data */
        $captcha = intval($this->input->post('captcha'));
        $reset_data = array(
            'email' => $this->input->post('email'),
        );

        /* Check captcha */
        if($this->verify_captcha($captcha) === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Wrong captcha!',
            )));
        }

        /* Check email */
        if($this->check_email($reset_data['email']) === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Invalid email format!',
            )));
        }

        if($this->do_check_email_existed($reset_data['email']) === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'This email does not have any account!',
            )));
        }

        if($this->user_model->is_user_actived($this->user_model->get_user_id_by_email($reset_data['email'])) === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'This email is not actived!',
            )));
        }

        $reset_data = $this->complete_reset_data($reset_data);

        /* Forget */
        if($this->do_forget($reset_data) === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Failed to reset password! Please contact admin!',
            )));
        }

        if($this->send_reset_code($reset_data['reset_code'], $reset_data['email']) === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Failed to send reset code by email! Please contact admin!',
            )));
        }

        echo json_encode(array(
            'status' => 1,
            'message' => 'Reset code email send successfully! Please login to your email address and click the reset link to reset your password!',
        ));
    }

    public function complete_reset_data($reset_data)
    {
        $time = time();
        $reset_data['user_id'] = $this->user_model->get_user_id_by_email($reset_data['email']);
        $reset_data['reset_code'] = $this->get_reset_code();
        $reset_data['reset_code_alive_time'] = $time + $this->config->item('sess_expiration');
        $reset_data['verified'] = 0;
        return $reset_data;
    }

    public function do_forget($reset_data)
    {
        return $this->user_model->forget_password($reset_data);
    }

    public function verify_reset_code()
    {
        $reset_code = $this->uri->segment(3);
        if($this->user_model->is_reset_code_existed($reset_code) === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Invalid reset code!',
            )));
        }

        $reset_code_info = $this->user_model->get_reset_code_code_info($reset_code);

        $email = $reset_code_info['email'];
        $current_reset_code = $reset_code_info['reset_code'];
        $reset_code_alive_time = intval($reset_code_info['reset_code_alive_time']);

        if($this->is_overdue($reset_code_alive_time)){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Reset code has expired! Please re-apply for the new reset code!',
            )));
        }

        // 这里直接将 reset_code 返回 , 前端收到以后添加到表单隐藏域中
        // 在发送新密码的时候需要第二次进行验证
        // 或者其实用 Cookie 应该也行
        // echo json_encode(array(
        //     'status' => 1,
        //     'message' => $reset_code,
        // ));
        // 这里直接加载view (不太好吧...)
        // 加载到主页 , 然后带上  reset_code
        $this->load->view('/templates/header');
        $this->load->view('/slide_bar/header');
        $this->load->view('/slide_bar/content_visitor.php');
        $this->load->view('/home/content');
        $this->load->view('/slide_bar/footer');
        $this->load->view('/templates/footer');
        $this->load->view('/reset/reset', array('reset_code'=>$reset_code));
    }

    public function reset()
    {
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('password', 'Password', 'trim|required');
        $this->form_validation->set_rules('reset_code', 'Captcha', 'trim|required');

        if ($this->form_validation->run() === FALSE)
        {
            die(json_encode(array(
                'status' => 0,
                'message' => 'Form validation failed!',
            )));
        }

        /* Post data */
        $new_password = $this->input->post('password');
        $reset_code = $this->input->post('reset_code');

        if($this->user_model->is_reset_code_existed($reset_code) === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Invalid reset code!',
            )));
        }

        $reset_code_alive_time = $this->user_model->get_reset_code_alive_time($reset_code);

        if($this->is_overdue($reset_code_alive_time)){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Reset code has expired!',
            )));
        }

        if($this->user_model->is_reset_code_used($reset_code) === true){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Reset code has been used!',
            )));
        }

        /* Password length */
        if($this->check_password_length($new_password) === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Password must be equal 6 or more and equal 16 or less characters!',
            )));
        }

        if($this->check_password_bad_chars($new_password) === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Invalid password! Please do not use special characters in password!',
            )));
        }

        $user_id = $this->user_model->get_user_id_by_reset_code($reset_code);
        $new_salt = $this->get_salt();
        if($this->do_reset($user_id, $new_password, $new_salt) === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Reset password failed! Please contact admin!',
            )));
        }
        echo json_encode(array(
            'status' => 1,
            'message' => 'Reset password success!',
        ));

        // destory reset_code
        $this->destory_reset_code($user_id);

        // set session
        // $this->set_session_by_user_id($user_id);
    }

    public function do_reset($user_id, $new_password, $new_salt)
    {
        $encrypted_password = $this->get_encrypted_password($new_password, $new_salt);
        $new_password_data = array(
            'password' => $encrypted_password,
            'salt' => $new_salt,
        );
        return $this->user_model->do_reset_password($user_id, $new_password_data);
    }

    public function destory_reset_code($user_id)
    {
        $this->user_model->destory_reset_code($user_id);
    }

    public function destory_active_code($user_id)
    {
        $this->user_model->destory_active_code($user_id);
    }

    public function get_captcha()
    {
        $this->load->helper('captcha');
        $this->load->helper("form");
        $vals = array(
            'img_path'  => './assets/captcha/',
            'img_url'   => '/assets/captcha/',
            'font_path' => './assets/fonts/texb.ttf',
            'img_width' => 180,
            'img_height' => 50,
            'word_length' => 4,
            'font_size' => 24,
            'pool' => '0123456789',
        );

        $cap = create_captcha($vals);
        $data = array(
            'captcha_time'  => $cap['time'],
            'ip_address'    => $this->input->ip_address(),
            'word'      => $cap['word']
        );
        $query = $this->db->insert_string('captcha', $data);
        $this->db->query($query);

        echo json_encode(array(
            'status' => 1,
            'message' => $cap['image'],
        ));
    }

    public function logout()
    {
        $this->session->sess_destroy();
        header('Location:/');
        die();
    }

    public function is_admin()
    {
        return ($this->session->usertype === '1');
    }

    public function score()
    {
        if($this->is_logined()){
            $score_data = $this->user_model->get_all_scores();
            echo json_encode(array(
                'status' => 1,
                'message' => $score_data,
            ));
        }else{
            $this->session->sess_destroy();
            header('Location:/');
        }
    }

    public function rank_qqbot()
    {
        $top = 10;
        $score_data = $this->user_model->get_rank($top);
        echo json_encode($score_data);
    }

    public function get_personal_information(){
        if($this->is_logined() === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'You don\'t have permission to access this! Please login first!'
            )));
        }else{
            $user_info = $this->user_model->get_personal_info($this->session->user_id);
            $user_info['rank'] = $this->user_model->get_rank_by_username($user_info['username']);
            echo json_encode(array(
                'status' => 1,
                'value' => $user_info
            ));
        }
    }

    public function update_user_info(){
        if($this->is_logined() === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'You don\'t have permission to access this! Please login first!'
            )));
        }
        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('phone', 'Phone', 'trim|required');
        if ($this->form_validation->run() === FALSE)
        {
           die(json_encode(array(
                'status' => 0,
                'message' => 'Form validation failed!'
            )));
        }

        /* Post data */
        $phone = $this->input->post('phone');
        $qq = $this->input->post('qq');
        $wechat = htmlspecialchars($this->input->post('wechat'));

        /* Form validation */
        if($this->check_student_id_length($phone) === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Invalid phone length!'
            )));
        }elseif(!is_numeric($phone)){
            die(json_encode(array(
                'status' => 0,
                'message' => 'Invalid phone! Only numbers are allowed!'
            )));
        }elseif(strlen($qq) !== 0) {
            if(!is_numeric($qq)){
                die(json_encode(array(
                'status' => 0,
                'message' => 'Invalid QQ numbers! Only numbers are allowed!'
            )));
            }
        }
        $this->user_model->update_user_info($this->session->user_id, $phone, $qq, $wechat);
        echo json_encode(array(
            'status' => 1,
            'message' => 'Update success!',
        ));
    }

    public function get_all_user_info() {
        if($this->is_logined() === false || $this->is_admin() === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'You don\'t have permission to access this!'
            )));
        }else{
            $all_user_info = $this->user_model->get_all_user_info();
            echo json_encode(array(
                'status' => 1,
                'value' => $all_user_info
            ));
        }
    }

    public function delete_user(){
        if($this->is_logined() === false || $this->is_admin() === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'You don\'t have permission to access this!'
            )));
        }

        $this->load->helper('form');
        $this->load->library('form_validation');

        $user_id = intval($this->uri->segment(4));
        if($this->do_check_user_id_existed($user_id) === false)
        {
            die(json_encode(array(
                'status' => 0,
                'message' => 'User ID doesn\'t exists!'
            )));
        }

        $this->user_model->delete_user($user_id);
        echo json_encode(array(
            'status' => 1,
            'message' => 'Update success!',
        ));
    }

    public function update_status(){
        if($this->is_logined() === false || $this->is_admin() === false){
            die(json_encode(array(
                'status' => 0,
                'message' => 'You don\'t have permission to access this!'
            )));
        }

        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('type', 'Type', 'trim|required');
        $this->form_validation->set_rules('value', 'Value', 'trim|required');

        if ($this->form_validation->run() === FALSE)
        {
           die(json_encode(array(
                'status' => 0,
                'message' => 'Form validation failed!'
            )));
        }

        /* Post data */
        $type = $this->input->post('type');
        $value = intval($this->input->post('value'));
        $user_id = intval($this->uri->segment(4));

        /* Form validation */
        if($this->do_check_user_id_existed($user_id) === false)
        {
            die(json_encode(array(
                'status' => 0,
                'message' => 'User ID doesn\'t exists!'
            )));
        }elseif($value !== 0 && $value !== 1)
        {
            die(json_encode(array(
                'status' => 0,
                'message' => 'Invalid value!'
            )));
        }elseif($type !== 'actived' && $type !== 'usertype' && $type !== 'ban' )
        {
            die(json_encode(array(
                'status' => 0,
                'message' => 'Invalid type!'
            )));
        }

        $status = ($value === 1)?true:false;

        if($type === 'actived'){
            $this->user_model->set_actived($user_id, $status);
        }elseif ($type === 'usertype'){
            $this->user_model->set_admin($user_id, $status);
        }elseif($type === 'ban'){
            $this->user_model->ban_user($user_id, $status);
        }
        echo json_encode(array(
            'status' => 1,
            'message' => 'Update success!',
        ));
    }
}
