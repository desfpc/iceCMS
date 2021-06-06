<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * Store Request Class
 *
 */

namespace ice\Models;

use ice\DB\DB;

class StoreRequest extends Obj
{
    /**
     * StoreRequest constructor. (подменяем создание объекта - прописываем железно целевую таблицу)
     *
     * @param DB $DB
     * @param null $id
     * @param null $settings
     */
    public function __construct(DB $DB, $id = null, $settings = null)
    {
        $this->doConstruct($DB, 'store_requests', $id, $settings);
    }

    /**
     * @inheritDoc
     */
    public function fullRecord()
    {
        $this->getUser();
        $this->getGoods();
    }

    /**
     * Get request user
     */
    private function getUser() {
        $user = new User($this->DB);
        $user->getRecord($this->params['user_id']);
        $this->params['user'] = $user->params;
    }

    /**
     * Get request Goods
     */
    private function getGoods() {
        $query = 'SELECT * FROM store_request_goods WHERE request_id = '.$this->params['id'];
        if ($res = $this->DB->query($query)) {
            $goodsIds = '';
            $goodsBuyParams = [];
            foreach ($res as $row) {
                if($goodsIds != '') {
                    $goodsIds .= ',';
                }
                $goodsIds.=$row['good_id'];
                $goodsBuyParams[$row['good_id']] = $row;
            }

            //выборка списка товаров в заказе
            $conditions = [
                [
                    'string' => false,
                    'type' => 'IN',
                    'col' => 'id',
                    'val' => $goodsIds
                ]
            ];
            //сортировка результата запроса
            $sort = [
                ['col' => 'material_type_name', 'sort' => 'ASC'],
                ['col' => 'name', 'sort' => 'ASC']
            ];
            $goods = new MatList($this->DB, $conditions, $sort, 1, 1000);
            $this->params['goods'] = $goods->getRecords();
            $this->params['goodsBuyParams'] = $goodsBuyParams;
        }
    }
}