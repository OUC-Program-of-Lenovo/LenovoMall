<?php

/*orders数据表：
  `order_id` int(11)  AUTO_INCREMENT,
  `user_id` int(11) NOT NULL,
  `item_id` int(11) NOT NULL,
  `amount` int(11) NOT NULL,
  `rcv_address` varchar(64) NOT NULL,
  `rcv_name` varchar(16) NOT NULL,
  `rcv_phone` varchar(11) NOT NULL,
**`snd_address` varchar(64) NOT NULL,
**`snd_name` varchar(16) NOT NULL,
**`snd_phone` varchar(11) NOT NULL,
**`post_script` string default:"无备注"
  `status` int(11) NOT NULL(default 0:待确认，1：待付款，2.已取消，3.待发货，4.待收货，5.待退款，6.已退款)
  `time` varchar(11) NOT NULL,
*/

class order_model extends CI_Model {
    
    public function __construct()
    {
        $this->load->database();
    }

/*操作：
1.生成订单
2.修改订单（rcv_address,amount,rcv_name,rcv_phone,postscript）
4.显示全部历史订单
5.通过order_id进行查询订单
6.通过order_id更新status
7.删除订单
8.通过user_id查询用户所有订单
*/


//1.新增订单
    public function insert_order($order)
    {
        return $this->db->insert('order', $order);
    }

//2.修改rcv_address
    public function update_rcv_address($order_id){
        return  $this->db;
        $rcv_address = $this->input->post('rcv_address');
        ->set(array('rcv_address'=>$rcv_address))
        ->where(array('order_id'=>$order_id))
        ->update('order');
    }

//3.修改rcv_phone
    public function update_rcv_phone($order_id){
         return  $this->db;
         $rcv_phone = $this->input->post('rcv_phone');
        ->set(array('rcv_phone'=>$rcv_phone))
        ->where(array('order_id'=>$order_id))
        ->update('order');
    }

//4.修改amount
    public function update_amount($order_id){
        return  $this->db;
        $amount = $this->input->post('amount');        
        ->set(array('amount'=>$amount))
        ->where(array('order_id'=>$order_id))
        ->update('order');
    }

//5.修改post_script
/*    public function update_post_script($order_id){
        return  $this->db;
        $post_script = $this->input->post('post_script');        
        ->set(array('post_script'=>$post_script))
        ->where(array('order_id'=>$order_id))
        ->update('order');
    }
*/
//6.修改rcv_name
    public function update_rcv_name($order_id){
        return  $this->db;
        $rcv_name = $this->input->post('rcv_name');        
        ->set(array('rcv_name'=>$rcv_name))
        ->where(array('order_id'=>$order_id))
        ->update('order');
    }

//7.通过order_id查询订单
    public function get_order($id=-1)
    {
        if($id==-1){
            
        $query = $this->db->get('order');
        
            return $query->result_array();
        }
        
            $query=$this->db->get_where('order',array('order_id'=>$id));
            return $query->row_array();
        
    }


//8.通过order_id改变订单的状态（status）
    public function change_order_status($order_id,$status){
        return  $this->db
        ->set(array('status'=>$status))
        ->where(array('order_id'=>$order_id))
        ->update('order');

    }

//9.通过id，删除历史订单
    public function delete_order_by_order_id($order_id){
        return $this->db->delete('order',array('order_id'=>$order_id));
    }
    
//10. 通过user_id查询用户所有订单   
    public function get_all_order($user_id)
    {
        $query = $this->db->get_where('order', array('user_id' => $user_id));
        return $query->row_array();
    }


    
 ?>
