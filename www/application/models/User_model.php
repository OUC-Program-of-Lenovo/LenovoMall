<?php

class User_model extends CI_Model
{
    public function __construct()
    {
        $this->load->database();
    }

    /**
     * Get one user info by user id
     * @param $user_id: int
     * @return array
     */
    public function get_user_info($user_id)
    {
        $query = $this->db
            ->where('user_id', $user_id)
            ->get('users');
        return $query->row_array();
    }

    /**
     * Check user actived
     * @param $user_id: int
     * @return bool
     */
    public function is_user_actived($user_id)
    {
        $query = $this->db
            ->where('user_id', $user_id)
            ->get('users');
        $result = $query->row_array();
        return (intval($result['actived']) === 1);
    }

    /**
     * Get user id by email
     * @param $email: string
     * @return int
     */
    public function get_user_id_by_email($email)
    {
        $query = $this->db
            ->where('email', $email)
            ->get('users');
        $result = $query->row_array();
        return intval($result['user_id']);
    }

    /**
     * Get user id by username
     * @param $username: string
     * @return int
     */
    public function get_user_id_by_username($username)
    {
        $query = $this->db
            ->where('username', $username)
            ->get('users');
        $result = $query->row_array();
        return intval($result['user_id']);
    }

    /**
     * Get username by user id
     * @param $user_id: int
     * @return string
     */
    public function get_username_by_user_id($user_id)
    {
        $query = $this->db
            ->select('username')
            ->where('user_id', $user_id)
            ->get('users');
        $result = $query->row_array()['username'];
        return $result;
    }

    /**
     * Get user id by active code
     * @param $active_code: string
     * @return int
     */
    public function get_user_id_by_active_code($active_code)
    {
        $query = $this->db
            ->where('active_code', $active_code)
            ->get('users');
        $result = $query->row_array();
        return intval($result['user_id']);
    }

    /**
     * Get user cart by user id
     * @param $user_id: int
     * @return string: cart which looks like '1|2|5|7|1|3|5|'
     */
    public function get_user_cart($user_id)
    {
        $query = $this->db
            ->where('user_id', $user_id)
            ->get('users');
        $result = $query->row_array();
        return $result['shopping_cart'];
    }

    /**
     * Update user cart
     * @param $user_id: int
     * @param $cart: string which looks like '1|2|5|7|88|1|'
     */
    public function update_user_cart($user_id, $cart)
    {
        $this->db
            ->set(array('shopping_cart' => $cart))
            ->where('user_id', $user_id)
            ->update('users');
    }

    /**
     * Check if user was baned
     * @param $user_id: int
     * @return bool
     */
    public function is_user_baned($user_id)
    {
        $query = $this->db
            ->where('user_id', $user_id)
            ->get('users');
        $result = $query->row_array();
        return (intval($result['ban']) === 1);
    }

    /**
     *  Update user phone, avatar, real name
     * @param $user_id: int
     * @param $phone: string
     */
    public function update_user_info($user_id, $phone, $receiver, $address)
    {
        $this->db
            ->set(array(
                'phone' => $phone,
                'rcv_name' => $receiver,
                'rcv_address' => $address
            ))
            ->where('user_id', $user_id)
            ->update('users');
    }

    /**
     * Check if email has existed
     * @param $email: string
     * @return bool
     */
    public function is_email_existed($email)
    {
        $query = $this->db->get_where('users', array('email' => $email));
        return ($query->num_rows() > 0);
    }

    /**
     * Check if active code has existed
     * @param $active_code: string
     * @return bool
     */
    public function is_active_code_existed($active_code)
    {
        $query = $this->db->get_where('users', array('active_code' => $active_code));
        return ($query->num_rows() > 0);
    }

    /**
     * Check if username has existed
     * @param $username: string
     * @return bool
     */
    public function is_username_existed($username)
    {
        $query = $this->db->get_where('users', array('username' => $username));
        return ($query->num_rows() > 0);
    }

    /**
     * Check if user id has existed
     * @param $user_id: int
     * @return bool
     */
    public function is_user_id_existed($user_id)
    {
        $query = $this->db->get_where('users', array('user_id' => $user_id));
        return ($query->num_rows() > 0);
    }

    /**
     * Get all users info
     * @return array
     */
    public function get_all_user_info()
    {
        $query = $this->db->get('users');
        return $query->result();
    }

    /**
     * Update user avatar by user id
     * @param $user_id: int
     * @param $avatar: string. avatar file name.
     * @return bool
     */
    public function update_user_avatar($user_id, $avatar)
    {
        return $this->db
            ->set(array('avatar' => $avatar))
            ->where('user_id', $user_id)
            ->update('users');
    }

    /**
     * Get user personal info by user id
     * @param $user_id: int
     * @return array
     */
    public function get_personal_info($user_id)
    {
        $this->db
            ->select(
                'username, password, email,
                 phone, avatar, rcv_name, rcv_address'
            );
        $query = $this->db->get_where('users', array('user_id' => $user_id));
        return $query->row_array();
    }

    /**
     * Create one user by user info
     * @param $user_info
     * User info should include:
     *   username: string <= 16
     *   password: string == 32
     *   salt: string < 5
     *   user_type: 0 or 1 or 2 or 3
     *   phone: string == 11
     *   avatar: string <= 32
     *   email: string <= 50
     *   regist_time: string <= 10
     *   regist_ip: string <= 15
     *   actived: 0 or 1
     *   actived_code: string <= 32
     * @return bool
     */
    public function register($user_info)
    {
        return $this->db->insert('users', $user_info);
    }

    public function login($user_id, $login_time, $login_ip)
    {
        return $this->db
            ->set(array(
                'login_time' => $login_time,
                'login_ip' => $login_ip))
            ->where('user_id', $user_id)
            ->update('users');
    }

    /**
     * Active one user by user id
     * @param $user_id: int
     * @return bool
     */
    public function active_user($user_id)
    {
        return $this->db
            ->set('actived', '1')
            ->where('user_id', $user_id)
            ->update('users');
    }

    /**
     * Destroy user active code by user id
     * @param $user_id: int
     * @return bool
     */
    public function destroy_active_code($user_id)
    {
        return $this->db
            ->set('active_code', '')
            ->where('user_id', $user_id)
            ->update('users');
    }

    /**
     * Set one user active status by user id
     * @param $user_id: int
     * @param $status: bool
     * @return bool
     */
    public function set_actived($user_id, $status)
    {
        $val = '0';
        if ($status === true) {
            $val = '1';
        }
        return $this->db
            ->set('actived', $val)
            ->where('user_id', $user_id)
            ->update('users');
    }

    /**
     * Set one user to be admin bt status and user id
     * @param $user_id: int
     * @param $status: bool
     * @return bool
     */
    public function set_admin($user_id, $status)
    {
        $val = '0';
        if ($status === true) {
            $val = '1';
        }
        return $this->db
            ->set('user_type', $val)
            ->where('user_id', $user_id)
            ->update('users');
    }

    /**
     * Delete ont user by user id
     * @param $user_id: int
     * @return bool
     */
    public function delete_user($user_id)
    {
        return $this->db->delete('users', array('user_id' => $user_id));
    }

    /**
     * Insert captcha information to database
     * @param $data: array
     */
    public function insert_captcha($data)
    {
        $this->db->query(
            $this->db->insert_string('captcha', $data)
        );
    }

    /**
     * Delete old captchas which has expiated
     * @param $expiration: int
     */
    public function delete_captcha($expiration)
    {
        $this->db
            ->where('captcha_time < ', $expiration)
            ->delete('captcha');
    }

    /**
     * Check if captcha has existed
     * @param $data: array
     * @return bool
     */
    public function is_captcha_existed($data)
    {
        $sql = 'SELECT COUNT(*) AS count FROM captcha WHERE word = ? AND ip_address = ? AND captcha_time > ?';
        $query = $this->db->query($sql, $data);
        $raw = $query->row();
        return ($raw->count > 0);
    }
}