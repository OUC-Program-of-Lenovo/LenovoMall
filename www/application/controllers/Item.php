<?php

class item extends CI_Controller
{
    public function __construct()
    {
        parent::__construct();
        $this->load->model('items_model');
        $this->load->library('session');
        $this->load->helper('url_helper');
    }

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

    /****************************************************管理员**************************************************************************/
    /**
     * 查询  显示所有商品
     */
    public function items()
    {
        $data['items'] = $this->items_model->get_items();
        echo json_encode(array(
            'status' => 1,
            'items' => $data['items']
        ));
    }

    /**
     * 查询  根据id进行查询
     */
    public function finditemById($item_id)
    {
        $data['item'] = $this->items_model->get_items($item_id);
        if (empty($data['item'])) {
            echo json_encode(array(
                'status' => 0,
                'message' => 'Not found!'
            ));
        } else {
                echo json_encode(array(
                    'status' => 1,
                    'item' => $data['item']
                ));
        }
    }

    //delete管理员通过item_id删除商品
    public function deleteItemById($item_id)
    {
        if ($this->is_logined() === false || $this->is_admin() === false) {
            die(json_encode(array(
                'status' => 0,
                'message' => 'You don\'t have permission to access this!'
            )));
        }

        $this->items_model->deleteItemById($item_id);
    }

    //insert管理员插入一个商品
    public function set_item()
    {
        if ($this->is_logined() === false || $this->is_admin() === false) {
            die(json_encode(array(
                'status' => 0,
                'message' => 'You don\'t have permission to access this!'
            )));
        }

        $this->load->helper('form');
        $this->load->library('form_validation');

        $this->form_validation->set_rules('number', 'Number', 'trim|required');
        $this->form_validation->set_rules('name', 'Name', 'trim|required');
        $this->form_validation->set_rules('price', 'Price', 'trim|required');
        $this->form_validation->set_rules('amount', 'Amount', 'trim|required');
        $this->form_validation->set_rules('type', 'Type', 'trim|required');
        $this->form_validation->set_rules('size', 'Size', 'trim|required');
        $this->form_validation->set_rules('description', 'Description', 'trim|required');
        $this->form_validation->set_rules('active', 'Active', 'trim|required');

        if ($this->form_validation->run() === FALSE) {
            die(json_encode(array(
                'status' => 0,
                'message' => 'Form validation failed!'
            )));
        }

        $filename = md5(md5($this->input->post('name')));
        $ext = strtolower(pathinfo($_FILES["avatar"]["name"], PATHINFO_EXTENSION));
        /* Upload config */
        $config['upload_path'] = '../html/upload/images/picture/';
        $config['allowed_types'] = 'gif|jpg|png';
        $config['file_name'] = $filename;
        $config['file_ext_tolower'] = true;
        $config['overwrite'] = true;
        $config['max_width'] = 1024;
        $config['max_height'] = 768;
        $config['max_size'] = '102400';
        $this->load->library('upload', $config);

        if (!$this->upload->do_upload('avatar')) {
            die(json_encode(array(
                'status' => 0,
                'message' => 'Upload avatar failed! Please check image format, only jpg, png and gif are allowed!'
            )));
        }

        $item = array(
            'number' => $this->input->post('number'),
            'name' => $this->input->post('name'),
            'price' => $this->input->post('price'),
            'amount' => $this->input->post('amount'),
            'type' => $this->input->post('type'),
            'avatar' => $filename.'.'.$ext,
            'size' => $this->input->post('size'),
            'description' => $this->input->post('description'),
            'add_time' => time(),
            'active' => $this->input->post('active')
        );

        if ($this->items_model->insert_item($item) === false) {
            die(json_encode(array(
                'status' => 0,
                'message' => 'Failed to add item!'
            )));
        }

        echo json_encode(array(
            'status' => 1,
            'message' => 'Add item successfully!'
        ));
    }

    //update管理员通过item_id修改商品名称
    public function update_itemNameById($item_id)
    {
        if ($this->is_logined() === false || $this->is_admin() === false) {
        die(json_encode(array(
            'status' => 0,
            'message' => 'You don\'t have permission to access this!'
        )));
    }
        $this->items_model->updateItemById($item_id);
    }

    //商品被购买后surplus（剩余量）-1
    public function surplusDecrease($item_id)
    {
        $item = $this->items_model->get_items($item_id);
        $surplus = --$item['surplus'];
        $this->items_model->changeItemSurplus($item_id, $surplus);
    }

    /****************************************************购物车******************************************************************************/
    /*
     * 购物车中显示商品
     *参数： user_id
     * */
    public function get_itemsInCart()
    {
        $data = $this->items_model->getItemsByUserId($this->session->user_id);
        if (Empty($data)) {
            echo json_encode(array(
                'status' => 0,
                'message' => 'Item not found!'
            ));
        } else {
            echo json_encode(array(
                'status' => 1,
                'items' => $data
            ));
        }
    }

    /*
     * 购物车中删除商品
     * 参数：user_id,item_id*/
    public function deleteItemInCart($item_id)
    {
        return $this->items_model->deleteItemfromCart($this->session->uer_id, $item_id);
    }

    /*
     * 购物车中增加商品
     * 参数：user_id,item_id*/
    public function insertItemInCart($item_id)
    {
        return $this->items_model->putItemIntoCart($this->session->uer_id, $item_id);
    }


    /**
     * [API] Get item number by item id
     * Permission:
     *      Administrator
     * Method:
     *      Get
     * Param:
     *      segment 4
     * Return(Json):
     *      status: 1 or 0
     *      value/message
     */
    public function get_item_number_by_item_id()
    {
        if ($this->is_logined() === false || $this->is_admin() === false) {
            die(json_encode(array(
                'status' => 0,
                'message' => 'You don\'t have permission to access this!'
            )));
        }
        $item_id = $this->uri->segment(4);
        $number = $this->item_model->get_item_number_by_item_id($item_id);
        if($number === NULL)
        {
            die(json_encode(array(
                'status' => 0,
                'message' => 'Item id does not exists!'
            )));
        }
        echo json_encode(array(
            'status' => 1,
            'value' => $number
        ));
    }
}

?>
