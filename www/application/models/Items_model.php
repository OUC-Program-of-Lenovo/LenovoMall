<?php
class News_model extends CI_Model {
    
    public function __construct()
    {
        $this->load->database();
    }
    //查询
    public function get_items($id = -1)
    {
        if($id==-1){
            
        $query=$this->db->get('items');
        
            return $query->result_array();
        }
        
            $query=$this->db->get_where('items',array('item_id'=>$id));
            return $query->row_array();
        
    }
    //购物车中查询商品
    public function getItemByUserId($user_id,$status=1){
        $query=$this->db->get_where('',array('id'=>$user_id));
        if()
    }
    //插入
    public function insert_item($number,$name,$prive,$amount,$album,$size,$description,$add_time,$active,$weight,$brief_des){
        $data=array('item_id'=>0,'number'=>$this->input->post('number') ,'name'=>$this->input->post('name'),'price'=>$this->input->post('price'),
            'model'=>'unknown','amount'=>$this->input->post('amount'),'surplus'=>$this->input->post('amount'),
            'type'=>'gamenote','avatar'=>'unknown','album'=>$this->input->post('album'),'size'=>$this->input->post('size'),
            'description'=>$this->input->post('description'),'add_time'=>$this->input->post('add_time'),
            'active'=>$this->input->post('active'),'weight'=>$this->input->post('weight'),'keywords'=>'unknown',
            'brief_des'=>$this->input->post('brief_des'),'remask'=>'unknown'
        );
        return $this->db->insert('items',$data);
    }
    //删除,通过id删除item（商品）
    public function deleteItemById($item_id){
        return $this->db->delete('items',array('item_id'=>$item_id));
    }
    //更新,通过id修改商品的名字
    public function updateItemById($item_id){
        $name=$this->input->post('name');
        return $this->db
        ->set(array('name'=>$name))
        ->where(array('item_id'=>$item_id))
        ->update('items');
        
    }
    //更新，通过id改变商品的剩余量(surplus)
    public function changeItemSurplus($item_id,$surplus){
        return  $this->db
        ->set(array('surplus'=>$surplus))
        ->where(array('item_id'=>$item_id))
        ->update('items');
    }
    
}
    
    
?>
