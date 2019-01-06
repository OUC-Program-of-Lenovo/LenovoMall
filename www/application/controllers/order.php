<?php
class order extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('order_model');
        $this->load->model('items_model');
        $this->load->helper('url');
        $this->load->model('user_model');//可能还需要一个通过user_id获取电话号码的函数
        $this->load->library('session');
    }
////功能：
//1.生成订单
//2.修改订单
//3.确认订单：status:0->1
//4.取消订单：status:0->2 
//5.发货：status:1->3
//6.收货：status:3->4
//7.用户申请退款：status:3->5||4->5
//8.管理员处理退款申请：status:5->6
//9.查询全部订单
//10.通过id查询订单
//11.删除订单
//12.把order_id存到session中
//13.通过user_id获得它所有的订单
//14.把购物车里不同种类的电脑都生成一个订单
/////////////////////////
///session是这么用mie？///
/////////////////////////
    /**
     * Check admin
     * @return bool
     */
    public function is_admin()
    {
        return ($this->session->user_type === '1');
    }

    /**
     * Check overdue
     * @param $alive_time : int
     * @return bool
     */
    public function is_overdue($alive_time)
    {
        return (time() > $alive_time);
    }

    /**
     * Check login
     * @return bool
     */
    public function is_logined()
    {
        if ($this->session->user_id === NULL) {
            return false;
        } else {
            $session_alive_time = $this->session->session_alive_time;
            if ($this->is_overdue($session_alive_time)) {
                return false;
            } else {
                return true;
            }
        }
    }
//1.生成订单
    public function create_order($item_id, $amount)
    {
        $odrer_data = $this->user_model->get_order_data_by_user_id($this->session->user_id);
        $data = array(
            'user_id' => $this->session->user_id,
            'item_id' => $item_id,
            'amount' => $amount,
            'rcv_address' => $odrer_data['rcv_address'],
            'rcv_phone' => $odrer_data['phone'],
            'rcv_name' => $odrer_data['rcv_name'],
            'status' => 0,
            'time' => time(),
        );
        return $this->order_model->insert_order($data);
    }

//2.修改rcv_address
    public function modify_adress()
    {
        $order_id = $this->session->order_id;
        $this->order_model->update_rcv_address($order_id);
        if ($this->order_model->update_rcv_address($order_id)->run() === FALSE) {
            die(json_encode(array(
                'status' => 0,
                'message' => 'Change adress failed!'
            )));
        } else echo json_encode(array(
            'status' => 1,
            'message' => 'Change successfully!'
        ));
    }
//3.修改rcv_phone
    public function modify_phone()
    {
        $order_id = $this->session->order_id;
        $this->order_model->update_rcv_phone($order_id);
        if ($this->order_model->update_rcv_phone($order_id)->run() === FALSE) {
            die(json_encode(array(
                'status' => 0,
                'message' => 'Change phone failed!'
            )));
        } else echo json_encode(array(
            'status' => 1,
            'message' => 'Change successfully!'
        ));
    }
//4.修改rcv_name
    public function modify_name()
    {
        $order_id = $this->session->order_id;
        $this->order_model->update_rcv_name($order_id);
        if ($this->order_model->update_rcv_name($order_id)->run() === FALSE) {
            die(json_encode(array(
                'status' => 0,
                'message' => 'Change name failed!'
            )));
        } else echo json_encode(array(
            'status' => 1,
            'message' => 'Change successfully!'
        ));
    }
//5.修改post_script
    /*    public function modify_post_script(){
            $order_id = $this->session->order_id;
            $this->order_model->update_post_script($order_id);
            if ($this->order_model->update_rcv_post_script($order_id)->run() === FALSE) {
                die(json_encode(array(
                    'status' => 0,
                    'message' => 'Change postscript failed!'
                )));
            }
            else echo json_encode(array(
                'status' => 1,
                'message' => 'Change successfully!'
            ));
        }
    */
//6.修改amount
    public function modify_amount()
    {
        $order_id = $this->session->order_id;
        $this->order_model->update_amount($order_id);
        if ($this->order_model->update_amount($order_id)->run() === FALSE) {
            die(json_encode(array(
                'status' => 0,
                'message' => 'Change amount failed!'
            )));
        } else echo json_encode(array(
            'status' => 1,
            'message' => 'Change successfully!'
        ));
    }
//7.确认订单
    public function order_confirm()
    {
        $order_id = $this->session->order_id;
        $status = 1;
        $this->order_model->change_order_status($order_id, $status);
        if ($this->order_model->change_order_status($order_id, $status)->run() === FALSE) {
            die(json_encode(array(
                'status' => 0,
                'message' => 'Confirm failed!'
            )));
        } else echo json_encode(array(
            'status' => 1,
            'message' => 'Confirm successfully!'
        ));
    }
//8.取消订单
    public function order_cancel()
    {
        $order_id = $this->session->order_id;
        $status = 2;
        $this->order_model->change_order_status($order_id, $status);
        if ($this->order_model->change_order_status($order_id, $status)->run() === FALSE) {
            die(json_encode(array(
                'status' => 0,
                'message' => 'Cancel failed!'
            )));
        } else echo json_encode(array(
            'status' => 1,
            'message' => 'Cancel successfully!'
        ));
    }
//9.发货
    public function delivering_order()
    {
        $order_id = $this->session->order_id;
        $status = 3;
        $this->order_model->change_order_status($order_id, $status);
        if ($this->order_model->change_order_status($order_id, $status)->run() === FALSE) {
            die(json_encode(array(
                'status' => 0,
                'message' => 'deliver failed!'
            )));
        } else echo json_encode(array(
            'status' => 1,
            'message' => 'deliver successfully!'
        ));
    }
//10.确认收货
    public function delivered_order()
    {
        $order_id = $this->session->order_id;
        $status = 4;
        $this->order_model->change_order_status($order_id, $status);
        if ($this->order_model->change_order_status($order_id, $status)->run() === FALSE) {
            die(json_encode(array(
                'status' => 0,
                'message' => 'Confirm receipt failed!'
            )));
        } else echo json_encode(array(
            'status' => 1,
            'message' => 'Confirm receipt successfully!'
        ));
    }
//11.用户申请退款
    public function apply_for_refund()
    {
        $order_id = $this->session->order_id;
        $status = 5;
        $this->order_model->change_order_status($order_id, $status);
        if ($this->order_model->change_order_status($order_id, $status)->run() === FALSE) {
            die(json_encode(array(
                'status' => 0,
                'message' => 'apply failed!'
            )));
        } else echo json_encode(array(
            'status' => 1,
            'message' => 'apply successfully!'
        ));
    }
//12.管理员处理退款申请
    public function refund()
    {
        $order_id = $this->session->order_id;
        $status = 6;
        $this->order_model->change_order_status($order_id, $status);
        if ($this->order_model->change_order_status($order_id, $status)->run() === FALSE) {
            die(json_encode(array(
                'status' => 0,
                'message' => 'Refund failed!'
            )));
        } else echo json_encode(array(
            'status' => 1,
            'message' => 'Renfund successfully!'
        ));
    }
//13.显示所有订单
    public function order_all()
    {
        if ($this->is_logined() === false || $this->is_admin() === false) {
            die(json_encode(array(
                'status' => 0,
                'message' => 'You don\'t have permission to access this!'
            )));
        }
        $data['order'] = $this->order_model->get_order();
        echo json_encode(array(
            'status' => 1,
            'items' => $data['order']
        ));
    }
//14.查询:根据id进行查询
    public function find_order_by_id($order_id)
    {
        $data['order'] = $this->items_model->get_order($order_id);
        if (empty($data['order'])) {
            echo json_encode(array(
                'status' => 0,
                'message' => 'Not found!'
            ));
        } else {
            echo json_encode(array(
                'status' => 1,
                'order' => $data['order']
            ));
        }
    }
    // 15.set session: order_id
    public function set_session_by_order_id($order_id)
    {
        // set session
        $this->session->set_userdata($order_id);
    }
//16.通过order_id删除订单
    public function delete($order_id)
    {
        if ($this->is_logined() === false || $this->is_admin() === false) {
            die(json_encode(array(
                'status' => 0,
                'message' => 'You don\'t have permission to access this!'
            )));
        }
        $this->order_model->delete_order_by_order_id($order_id);
    }

//17.通过user_id获得它所有的订单
	public function get_all_order()
    {
        $user_id = $this->session->user_id;
        $orders = $this->order_model->get_all_order($user_id);
        if ($orders == FALSE) {
            die(json_encode(array(
                'status' => 0,
                'message' => 'get orders failed!'
            )));
        } else {
            echo json_encode(array(
                'status' => 1,
                'value' => $orders
            ));
        }
    }



//18.把购物车里每种电脑都建立出一个订单:
    public function order_all_from_cart(){
        $user_id = $this->session->user_id;
        $data = $this->items_model->getItemsByUserId($user_id);
        foreach ($data as $key => $value)
        {
            $item_id = $value['item_id'];
            $amount = $value['num'];
            $this->create_order($item_id, $amount);
        }

        $this->user_model->clean_cart($user_id);
    }
}
?>
