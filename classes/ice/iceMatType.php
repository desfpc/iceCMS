<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * Material Type Class
 *
 */

namespace ice;

class iceMatType extends iceObject {

    public $url;
    public $extraParams;

    //формируем URL типа материала
    public function getURL(){
        $url = '';
        if(isset($this->params['parents'])){
            $parents = $this->params['parents'];
            foreach ($parents as $parent){
                $url.='/'.$parent['id_char'];
            }
        }

        if($this->params['id_char'] != 'main'){
            return $url.'/'.$this->params['id_char'];
        }
        return $url;
    }

    //получаем родительские записи
    public function getParents(){
        $query='WITH RECURSIVE ptypes AS (
	
	SELECT t.* FROM 
	material_types t WHERE t.id = '.$this->id.'

	UNION ALL

	SELECT tt.* FROM
	material_types tt, ptypes p
	WHERE tt.id = p.parent_id
	)

SELECT * FROM ptypes WHERE id <> '.$this->id.' ORDER BY parent_id ASC;';

        if($res=$this->DB->query($query))
        {
            $this->params['parents']=$res;
        }

    }

    //получаем список дочерних разделов
    public function getChilds(){

        $query='WITH RECURSIVE ptypes AS (
	
	SELECT t.* FROM 
	material_types t WHERE t.id = '.$this->id.'

	UNION ALL

	SELECT tt.* FROM
	material_types tt, ptypes p
	WHERE tt.parent_id = p.id

	)

SELECT * FROM ptypes WHERE id <> '.$this->id.';';

        if($res=$this->DB->query($query))
        {
            $this->params['childs']=$res;
        }

    }

    //получаем связанные файлы с типом материалов
    public function getFiles(){

        $query='SELECT f.*, mt.ordernum 
        FROM files f, mtype_files mt 
        WHERE f.id = mt.file_id AND mt.mtype_id = '.$this->id.'
        ORDER BY mt.ordernum ASC, f.date_event DESC';
        if($res=$this->DB->query($query))
        {
            $this->params['files']=$res;
        }

    }

    //расширяем стандартный метод - удаление кэшей у связанных сущностей
    public function fullUncacheRecord(){

        //удаляем кэши родительских типов
        if(isset($this->params['parents']))
        {
            foreach ($this->params['parents'] as $mtype)
            {
                $this->uncacheRecord($mtype['id']);
            }
        }

        //удаляем кэши дочерних типов
        if(isset($this->params['childs']))
        {
            foreach ($this->params['childs'] as $mtype)
            {
                $this->uncacheRecord($mtype['id']);
            }
        }

        //TODO удаляем кэши связаных файлов

    }

    //получение экстра-полей
    public function getExtraParams(){

        $conditions = [];
        $sort = [];

        $conditions[] = [
            'string' => false,
            'type' => '=',
            'col' => 'mtype_id',
            'val' => $this->id
        ];

        $sort[] = ['col' => 'name', 'sort' => 'ASC'];

        $this->extraParams = new iceMatExtraParamsList($this->DB, $conditions, $sort, 1, 100);
        $this->extraParams = $this->extraParams->getRecords();
    }

    //расширяем стандартный метод - к полям БД добавляем связанные данные
    public function fullRecord(){

        //экстра-поля
        $this->getExtraParams();

        //дерево родительских типов материалов
        $this->getParents();

        //дерево дочерних типов материалов
        $this->getChilds();

        //связанные файлы с типом материала
        $this->getFiles();

        //полный URL
        $this->url = $this->getURL();
    }

    //TODO расширяем стандартный метод кэширования - удаление кэшей связанных файлов и типов материалов

    //подменяем создание объекта - прописываем железно целевую таблицу
    public function __construct(iceDB $DB, $id=null, $settings=null)
    {
        $this->doConstruct($DB, 'material_types', $id, $settings);
    }

}