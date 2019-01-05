<?php
class item extends CI_Controller {
    public function __construct()
    {
        parent::__construct();
        $this->load->model('items_model');
        $this->load->helper('url_helper');
    }
    /****************************************************管理员**************************************************************************/
    /*
                 * 查询  显示所有商品
     */
    public function items(){
        $data['items']=$this->items_model->get_items();
        echo json_encode(array('items'=>$data['items']));
    }
    /*
              * 查询  根据id进行查询
    */
    public function finditemById($id){
        $data['item']=$this->items_model->get_items($id);
        if (empty($data['item']))
        {
            echo json_encode(array('status'=>-1));
        }
        else echo json_encode(array('status'=>1,'item'=>$data['item']));
    }
    
   
    
    //delete管理员通过item_id删除商品
    public function deleteItemById($item_id){
        $this->items_model->deleteItemById($item_id);
    }
    //insert管理员插入一个商品
    public function set_item(){
       $this->items_model->insert_item();
    }
    //update管理员通过item_id修改商品名称
    public function update_itemNameById($item_id){
       $this->items_model->updateItemById($item_id);
    }
    //商品被购买后surplus（剩余量）-1
    public function surplusDecrease($item_id){
        $item=$this->items_model->get_items($item_id);
        $surplus=--$item['surplus'];
        $this->items_model->changeItemSurplus($item_id,$surplus);
    }
    /****************************************************购物车******************************************************************************/
    /*
     * 购物车中显示商品
     *参数： user_id
     * */
    public function get_itemsInCart(){
        $data=$this->Items_model->getItemsByUserId($this->session->uer_id);
        if(Empty($data)){
            echo json_encode(array('status'=>-1));
        }
        else{
            echo json_encode(array('status'=>1,'items'=>$data));
        }
    }
    /*
     * 购物车中删除商品
     * 参数：user_id,item_id*/
    public function deleteItemInCart($item_id){
        return $this->Items_model->deleteItemfromCart($this->session->uer_id,$item_id);
    }
    /*
     * 购物车中增加商品
     * 参数：user_id,item_id*/
    public function insertItemInCart($item_id){
        return $this->Items_model->putItemIntoCart($this->session->uer_id,$item_id);
    }
    
}
?>
