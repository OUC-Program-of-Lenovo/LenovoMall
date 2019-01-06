<?php

class items_model extends CI_Model
{

    public function __construct()
    {
        $this->load->database();
    }

    //查询
    public function get_items($id = -1)
    {
        if ($id === -1) {
            $query = $this->db->get('items');
            return $query->result_array();
        }
        $query = $this->db->get_where('items', array('item_id' => $id));
        return $query->row_array();
    }

    //插入
    public function insert_item($item)
    {
        return $this->db->insert('items', $item);
    }

    //删除,通过id删除item（商品）
    public function deleteItemById($item_id)
    {
        return $this->db->delete('items', array('item_id' => $item_id));
    }

    //更新,通过id修改商品的名字
    public function updateItemById($item_id)
    {
        $name = $this->input->post('name');
        return $this->db
            ->set(array('name' => $name))
            ->where(array('item_id' => $item_id))
            ->update('items');

    }

    //更新，通过id改变商品的剩余量(surplus)
    public function changeItemSurplus($item_id, $surplus)
    {
        return $this->db
            ->set(array('surplus' => $surplus))
            ->where(array('item_id' => $item_id))
            ->update('items');
    }


    /************************************************购物车中商品的操作*****************************************************************/

    //购物车中查询,通过用户ID获得物品数组
    public function getItemsByUserId($user_id)
    {
        $queryUser = $this->db->get_where('users', array('user_id' => $user_id));
        $result=$queryUser->row_array();
        $queryIds = $result['shopping_cart'];
        $queryIdsFinal = explode('|', $queryIds);
        $value = array();
        for ($i = 1; $i < count($queryIdsFinal) - 1; $i++) {
                if (array_key_exists($queryIdsFinal[$i], $value) === true) {
                    $value[$queryIdsFinal[$i]]++;
                } else {
                    $value[$queryIdsFinal[$i]] = 1;
                }

            }
        $query = array();
        for ($i = 1; $i < count($queryIdsFinal)-1; $i++) {
            if (array_key_exists($queryIdsFinal[$i], $query) === false) {
                $query[intval($queryIdsFinal[$i])] = $this->db->get_where(
                    'items', array('item_id' => intval($queryIdsFinal[$i]))
                )->row_array();
                $query[intval($queryIdsFinal[$i])]['num'] = $value[intval($queryIdsFinal[$i])];
            }
        }
        return $query;
    }

    //将商品加入购物车
    public function putItemIntoCart($user_id, $item_id)
    {
        $queryUser = $this->db->get_where('users', array('user_id' => $user_id));
        $queryIds = $queryUser['shopping_cart'];
        $queryIdsFinal = $queryIds . '|' . $item_id;
        return $this->db->
        set(array('shopping_cart' => $queryIdsFinal))
            ->where(array('user_id' => $user_id))
            ->update('users');
    }

    //将商品从购物车中删除
    public function deleteItemfromCart($user_id, $item_id)
    {
        $queryUser = $this->db->get_where('users', array('user_id' => $user_id));
        $queryIds = $queryUser['shopping_cart'];
        $pos1 = strpos($queryIds, strval($item_id));
        $str = strstr($queryIds, strval($item_id));
        $pos2 = strpos($str, '|');
        $str1 = substr($queryIds, 0, $pos1 - 1);
        $str2 = substr($queryIds, $pos2 + 1);
        $finalIds = $str1 . $str2;
        return $this->db->
        set(array('shopping_cart' => $finalIds))
            ->where(array('user_id' => $user_id))
            ->update('users');

    }

    public function get_number_by_item_id($item_id)
    {
        $query = $this->db
            ->where('item_id', $item_id)
            ->get('items');
        $result = $query->row_array();
        return intval($result['number']);
    }
}


?>
