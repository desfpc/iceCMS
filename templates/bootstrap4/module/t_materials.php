<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var ice\Web\Render $this
 */

$template_folder=$this->settings->path.'/templates/'.$this->settings->template.'';

//подключаем стили и скрипты
include_once ($template_folder.'/partial/t_jsandcss.php');
//$this->styles->addStyle('');
//$this->jscripts->addScript('');

//js document.load
include_once ($template_folder.'/partial/t_jsreadyglobal.php');
$this->jsready.='

    $(".form-group").has("input#regLogin").hide();

';

//список особых страниц для прорисовки другой шапки
$typesSettings = [
    2 => 't_header_mat-main.php'
];

if(key_exists($this->moduleData->mtype->id, $typesSettings)){
    $header = $typesSettings[$this->moduleData->mtype->id];
}
else {
    $header = 't_header_mat.php';
}

include_once ($template_folder.'/partial/'.$header);


        //определяем шаблон для вывода (материала или типа материала)
        $loadtemplate = null;

$num = count($this->parser->mtypes) - 1;
$mtype = $this->parser->mtypes[$num];

        //тип материала
        if(is_null($this->parser->material)){
            //krumo($mtype);
            if(!isset($mtype['template_list_name']) || is_null($mtype['template_list_name']) || $mtype['template_list_name'] == ''){
                $mtype['template_list_name'] = 'news';
            }
            $loadtemplate = $template_folder.'/mtype/'.$mtype['template_list_name'].'.php';
        }
        //материал
        else {

            if(!isset($mtype['template_item_name']) || is_null($mtype['template_item_name']) || $mtype['template_item_name'] == ''){
                $mtype['template_item_name'] = 'news_item';
            }
            $loadtemplate = $template_folder.'/material/'.$mtype['template_item_name'].'.php';

        }

        if(!is_null($loadtemplate)){
            include_once ($loadtemplate);
        }

        ?>
    </div>
<?php include_once ($template_folder.'/partial/t_footer.php');