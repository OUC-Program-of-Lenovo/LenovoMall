<?php
class order extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('order_model');
        $this->load->helper('url');
        $this->load->model('User_model');//可能还需要一个通过user_id获取默认收货地址的函数，和一个通过user_id获取电话号码的函数
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
/////////////////////////
///session是这么用mie？///
/////////////////////////

//1.生成订单
    public function create_order($item_id){
        $data=array(
            'order_id'=>0,
            'user_id'=>$this->session->user_id,
            'item_id'=>$item_id,
            'amount'=>1,
            'rcv_address'=>$this->user_model->get_rcv_phone_by_user_id($user_id)，
            'rcv_phone'=>$this->user_model->get_rcv_address_by_user_id($user_id)，
            'rcv_name'=>$this->input->post('rcv_name'),
            'postscript'=>"无备注",
            'order_state'=>0,
            'time'=>time(),
        );

        return $this->db->insert_order($data);
    }

//2.修改rcv_address
    public function modify_adress(){
        $order_id =>$this->session->order_id;
        $this->order_model->update_rcv_address($order_id);
    }

//3.修改rcv_phone
    public function modify_phone(){
        $order_id = $this->session->order_id;
        $this->order_model->update_rcv_phone($order_id);
    }

//4.修改rcv_name
    public function modify_adress(){
        $order_id = $this->session->order_id;
        $this->order_model->update_rcv_name($order_id);
    }

//5.修改post_script
    public function modify_post_script(){
        $order_id = $this->session->order_id;
        $this->order_model->update_post_script($order_id);
    }

//6.修改amount
    public function modify_adress(){
        $order_id = $this->session->order_id;
        $this->order_model->update_amount($order_id);
    }


//7.确认订单
    public function order_confirm(){
        $order_id = $this->session->order_id;
        $status = 1;
        $this->order_model->change_order_status($order_id,$status);
    }
//8.取消订单
    public function order_cancel(){
        $order_id = $this->session->order_id;
        $status = 2;
        $this->order_model->change_order_status($order_id,$status);
    }

//9.发货
    public function apply_for_refund(){
        $order_id = $this->session->order_id;
        $status = 3;
        $this->order_model->change_order_status($order_id,$status);
    }
//10.确认收货
    public function apply_for_refund(){
        $order_id = $this->session->order_id;
        $status = 4;
        $this->order_model->change_order_status($order_id,$status);
    }

//11.用户申请退款
    public function apply_for_refund(){
        $order_id = $this->session->order_id;
        $status = 5;
        $this->order_model->change_order_status($order_id,$status);
    }

//12.管理员处理退款申请
    public function refund(){
        $order_id = $this->session->order_id;
        $status = 6;
        $this->order_model->change_order_status($order_id,$status);
    } 

//13.显示所有订单

    public function order(){
        $data['order'] =>$this->order_model->get_order();
        echo json_encode(array('order'=>$data['order']));
    }

//14.查询:根据id进行查询

    public function find_order_by_id($order_id){
        $data['order'] = $this->items_model->get_order($item_id);
        if (empty($data['order'])) {
            echo json_encode(array(
                'message' => 'Not found!'
            ));
        } else {
                echo json_encode(array(
                    'order' => $data['order']
                ));
        }
    }

 // 15.set session: order_id

     public function set_session_by_order_id($order_id){
        // set session
        $this->session->set_orderdata($order_id);
    }  
    


?>
