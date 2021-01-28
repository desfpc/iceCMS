<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var \iceRender $this
 */

$this->headers = new iceHeaderBuilder();
$this->headers->standartHeaders();
$this->headers->addHeader('Content-Type: application/json');

$this->moduleData=new stdClass();

$this->getRequestValues(array('action'));

switch ($this->values->action)
{
    //получаем список материалов по типу
    case 'getmats':

        $this->getRequestValues(['type']);

        $type = (int)$this->values->type;

        $query="SELECT id, name FROM materials WHERE material_type_id = $type AND status_id = 1 ORDER BY name ASC";

        if(!$res=$this->DB->query($query))
        {
            $out=false;
        }
        else
        {
            foreach ($res as $row)
            {
                $out[] = [
                    'id' => $row['id'],
                    'name' => $row['name']
                ];
            }
        }

        $this->moduleData->res=['types' => $out];

        break;

    //получаем тип материала по наименованию или буквенному идентификатору
    case 'getmattype':

        $this->getRequestValues(['query']);

        $escaped_query=$this->DB->mysqli->real_escape_string($this->values->query);

        $query="SELECT * FROM material_types 
        WHERE LOWER(name) LIKE LOWER('%".$escaped_query."%')";

        if(!$res=$this->DB->query($query))
        {
            $out=false;
        }
        else
        {
            foreach ($res as $row)
            {
                $out[] = [
                    'id' => $row['id'],
                    'name' => $row['name']
                ];
                //$out[]=array($row['id'] => $row['name']);
            }
        }

        $this->moduleData->res=['types' => $out];

        break;
}


//всегда выводим false, если результата нет после обработки
if(!isset($this->moduleData->res))
{
    $this->moduleData->res=false;
}

die(json_encode($this->moduleData->res));