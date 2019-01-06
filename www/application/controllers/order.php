<?php
class order extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('order_model');
        $this->load->helper('url');
        $this->load->model('User_model');//可能还需要一个通过user_id获取电话号码的函数
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
            'rcv_address'=>$this->input->post('rcv_address')，
            'rcv_phone'=>$this->user_model->get_rcv_address_by_user_id($user_id)，
            'rcv_name'=>$this->input->post('rcv_name'),
//            'postscript'=>"无备注",
            'status'=>0,
            'time'=>time(),
        );

        return $this->order_model->insert_order($data);
    }

//2.修改rcv_address
    public function modify_adress(){
        $order_id =>$this->session->order_id;
        $this->order_model->update_rcv_address($order_id);
        if ($this->order_model->update_rcv_address($order_id)->run() === FALSE) {
            die(json_encode(array(
                'status' => 0,
                'message' => 'Change adress failed!'
            )));
        }
        else echo json_encode(array(
            'status' => 1,
            'message' => 'Change successfully!'
        ));
    }

//3.修改rcv_phone
    public function modify_phone(){
        $order_id = $this->session->order_id;
        $this->order_model->update_rcv_phone($order_id);
        if ($this->order_model->update_rcv_phone($order_id)->run() === FALSE) {
            die(json_encode(array(
                'status' => 0,
                'message' => 'Change phone failed!'
            )));
        }
        else echo json_encode(array(
            'status' => 1,
            'message' => 'Change successfully!'
        ));
    }

//4.修改rcv_name
    public function modify_adress(){
        $order_id = $this->session->order_id;
        $this->order_model->update_rcv_name($order_id);
        if ($this->order_model->update_rcv_name($order_id)->run() === FALSE) {
            die(json_encode(array(
                'status' => 0,
                'message' => 'Change name failed!'
            )));
        }
        else echo json_encode(array(
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
    public function modify_adress(){
        $order_id = $this->session->order_id;
        $this->order_model->update_amount($order_id);
        if ($this->order_model->update_amount($order_id)->run() === FALSE) {
            die(json_encode(array(
                'status' => 0,
                'message' => 'Change amount failed!'
            )));
        }
        else echo json_encode(array(
            'status' => 1,
            'message' => 'Change successfully!'
        ));
    }


//7.确认订单
    public function order_confirm(){
        $order_id = $this->session->order_id;
        $status = 1;
        $this->order_model->change_order_status($order_id,$status);
        if ($this->order_model->change_order_status($order_id,$status)->run() === FALSE) {
            die(json_encode(array(
                'status' => 0,
                'message' => 'Confirm failed!'
            )));
        }
        else echo json_encode(array(
            'status' => 1,
            'message' => 'Confirm successfully!'
        ));        
    }
//8.取消订单
    public function order_cancel(){
        $order_id = $this->session->order_id;
        $status = 2;
        $this->order_model->change_order_status($order_id,$status);
        if ($this->order_model->change_order_status($order_id,$status)->run() === FALSE) {
            die(json_encode(array(
                'status' => 0,
                'message' => 'Cancel failed!'
            )));
        }
        else echo json_encode(array(
            'status' => 1,
            'message' => 'Cancel successfully!'
        ));
    }

//9.发货
    public function delivering_order(){
        $order_id = $this->session->order_id;
        $status = 3;
        $this->order_model->change_order_status($order_id,$status);
        if ($this->order_model->change_order_status($order_id,$status)->run() === FALSE) {
            die(json_encode(array(
                'status' => 0,
                'message' => 'deliver failed!'
            )));
        }
        else echo json_encode(array(
            'status' => 1,
            'message' => 'deliver successfully!'
        ));
    }
//10.确认收货
    public function delivered_order(){
        $order_id = $this->session->order_id;
        $status = 4;
        $this->order_model->change_order_status($order_id,$status);
    	if ($this->order_model->change_order_status($order_id,$status)->run() === FALSE) {
            die(json_encode(array(
                'status' => 0,
                'message' => 'Confirm receipt failed!'
            )));
        }
        else echo json_encode(array(
            'status' => 1,
            'message' => 'Confirm receipt successfully!'
        ));
    }

//11.用户申请退款
    public function apply_for_refund(){
        $order_id = $this->session->order_id;
        $status = 5;
        $this->order_model->change_order_status($order_id,$status);
        if ($this->order_model->change_order_status($order_id,$status)->run() === FALSE) {
            die(json_encode(array(
                'status' => 0,
                'message' => 'apply failed!'
            )));
        }
        else echo json_encode(array(
            'status' => 1,
            'message' => 'apply successfully!'
        ));
    }

//12.管理员处理退款申请
    public function refund(){
        $order_id = $this->session->order_id;
        $status = 6;
        $this->order_model->change_order_status($order_id,$status);
        if ($this->order_model->change_order_status($order_id,$status)->run() === FALSE) {
            die(json_encode(array(
                'status' => 0,
                'message' => 'Refund failed!'
            )));
        }
        else echo json_encode(array(
            'status' => 1,
            'message' => 'Renfund successfully!'
        ));        
    } 

//13.显示所有订单

    public function order_all(){
        $data['order'] =>$this->order_model->get_order();
                echo json_encode(array(
            'status' => 1,
            'items' => $data['items']
        ));

    }

//14.查询:根据id进行查询

    public function find_order_by_id($order_id){
        $data['order'] = $this->items_model->get_order($item_id);
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

     public function set_session_by_order_id($order_id){
        // set session
        $this->session->set_userdata($order_id);
        
    }  
    


?>
