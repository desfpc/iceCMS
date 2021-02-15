<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * Material Class
 *
 */

namespace ice;

use ice\Helpers\Strings;

class iceMat extends iceObject {

    public $files = [];
    public $extraValues = [];

    public static function price($price){

        return number_format($price, 2, '<small>,', '&nbsp;').'&nbsp;₽</small>';

    }

    public function getExtraValues(){

        $conditions = [];
        $sort = [];

        $conditions[] = [
            'string' => false,
            'type' => '=',
            'col' => 'material_id',
            'val' => $this->id
        ];

        $sort[] = ['col' => 'param_name', 'sort' => 'ASC'];

        $extraValues = new iceMatExtraValuesList($this->DB, $conditions, $sort, 1, 100);
        $this->extraValues = $extraValues->getRecords();

    }

    public function getMatTypeData(){
        $query='SELECT name, template_list, template_item, template_admin FROM material_types WHERE id = '.$this->params['material_type_id'];
        if($res=$this->DB->query($query))
        {
            $this->params['material_type_name']=$res[0]['name'];

            //проверяем идентификаторы шаблонов и устанавливаем дефолтные значения
            if($res[0]['template_list'] == ''){
                $res[0]['template_list'] = 2; //по дефолту шаблон списка новостей
            }
            if($res[0]['template_item'] == ''){
                $res[0]['template_item'] = 3; //по дефолту шаблон детализации новости
            }
            if($res[0]['template_admin'] == ''){
                $res[0]['template_admin'] = 4; //по дефолту шаблон редактирования новости
            }

            $listTemplate = new iceTemplate($this->DB, $res[0]['template_list']);
            $listTemplate->getRecord($res[0]['template_list']);

            $itemTemplate = new iceTemplate($this->DB, $res[0]['template_item']);
            $itemTemplate->getRecord($res[0]['template_item']);

            $adminTemplate = new iceTemplate($this->DB, $res[0]['template_admin']);
            $adminTemplate->getRecord($res[0]['template_admin']);

            //заносим шаблоны в свойства материала
            $this->params['templates'] = [
                'template_list' => [
                    'id' => $res[0]['template_list'],
                    'name' => $listTemplate->params['name'],
                    'filename' => $listTemplate->params['filename']
                ],
                'template_item' => [
                    'id' => $res[0]['template_item'],
                    'name' => $itemTemplate->params['name'],
                    'filename' => $itemTemplate->params['filename']
                ],
                'template_admin' => [
                    'id' => $res[0]['template_admin'],
                    'name' => $adminTemplate->params['name'],
                    'filename' => $adminTemplate->params['filename']
                ]
            ];
        }
    }
    public function getUserName(){
        $query='SELECT full_name FROM users WHERE id = '.$this->params['user_id'];
        if($res=$this->DB->query($query))
        {
            $this->params['user_name']=$res[0]['full_name'];
        }
    }

    public function getFiles(){

        //получение списка файлов материала (прямой запрос, так как есть обязательная связка и нам надо поле индивидуальной сортировки)
        $query = 'SELECT f.*, m.ordernum 
        FROM files f, material_files m 
        WHERE f.id = m.file_id AND m.material_id = '.$this->params['id'].'
        ORDER BY m.ordernum ASC, f.id ASC';

        if($res=$this->DB->query($query))
        {
            if(count($res) > 0){
                $this->files = $res;
            }
        }

    }

    //расширяем стиандартный метод - к полям БД добавляем связанные данные
    public function fullRecord(){
        $this->getMatTypeData();
        $this->getUserName();
        $this->getFiles();
        $this->getExtraValues();
    }

    //функция дает иконку статуса материала
    public static function statusIcon($id){
        $id = (int)$id;
        switch ($id){
            case 0:
                return '<i class="material-icons md-16 md-grey">visibility_off</i>';
                break;
            case 1:
                return '<i class="material-icons md-16 md-green">visibility</i>';
                break;
            case 2:
                return '<i class="material-icons md-16 md-red">delete</i>';
                break;
        }
    }

    //функция дает название статуса материала TODO языки
    public static function statusName($id){
        $id = (int)$id;
        switch ($id){
            case 0:
                return 'скрытый';
                break;
            case 1:
                return 'актывный';
                break;
            case 2:
                return 'архивный';
                break;
        }
    }

    //подменяем создание объекта - прописываем железно целевую таблицу
    public function __construct(iceDB $DB, $id=null, $settings=null)
    {
        $this->doConstruct($DB, 'materials', $id, $settings);
    }

    //формирование списка параметров для редактирования/добавления запись из $this->values
    public function paramsFromValues($values) {

        $params = $this->params;
        foreach ($this->cols as $col)
        {
            $valueName = $col['Field'];
            if($valueName != 'id') {
                if(isset($values->$valueName) && $values->$valueName !== ''){
                    $params[$col['Field']] = $values->$valueName;
                }
                //обьявляем дефолтные значения некоторых переменных
                else {
                    switch ($valueName){
                        case 'id_char':
                            $params[$col['Field']] = Strings::makeCharId($values->name);
                            break;
                        default:
                            if(!isset($params[$col['Field']])){
                                $params[$col['Field']] = null;
                            }
                            break;
                    }
                }
            }
        }

        return $params;

    }

}