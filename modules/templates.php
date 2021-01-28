<?php
/**
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 * @var \iceRender $this
 */

//секурность
if(!$this->moduleAccess())
{
    return;
};

$this->moduleData=new stdClass();

$this->moduleData->title=$this->settings->site->title;
$this->moduleData->H1='Шаблоны материалов';
$this->moduleData->errors=[];
$this->moduleData->success=[];

$this->getRequestValues(['mode','filename','name','type','content','id']);

switch($this->values->mode){
    //добавление нового шаблона
    case 'add':

        $error = false;

        if($this->values->filename == '') {
            $this->moduleData->errors[] = 'Не заполнено название файла шаблона';
            $error = true;
        }
        if($this->values->name == '') {
            $this->moduleData->errors[] = 'Не заполнено название шаблона';
            $error = true;
        }
        if($this->values->type == '' || !in_array($this->values->type,[1,2,3])) {
            $this->moduleData->errors[] = 'Не верный тип шаблона';
            $error = true;
        }

        if(!$error){

            //params[$col['Field']]
            $template = new iceTemplate($this->DB);
            if($template->createRecord([
                'filename' => $this->values->filename,
                'name' => $this->values->name,
                'type' => $this->values->type,
                'content' => $this->values->content
            ])){
                $this->moduleData->success[] = 'Шаблон <strong>'.$this->values->name.'</strong> успешно создан';

                $this->values->filename = '';
                $this->values->name = '';
                $this->values->type = '';
                $this->values->content = '';
                
            }
            else {
                $this->moduleData->errors[] = 'Не удалось сохранить шаблон';
            }

        }

        break;

    //изменение шаблона
    case 'edit':

        //если есть POST данные - меняем
        if($_SERVER['REQUEST_METHOD'] === 'POST') {

            $error = false;

            if($this->values->id == '') {
                $this->moduleData->errors[] = 'Не передан идентификатор изменяемого шаблона';
                $error = true;
            }
            if($this->values->filename == '') {
                $this->moduleData->errors[] = 'Не заполнено название файла шаблона';
                $error = true;
            }
            if($this->values->name == '') {
                $this->moduleData->errors[] = 'Не заполнено название шаблона';
                $error = true;
            }
            if($this->values->type == '' || !in_array($this->values->type,[1,2,3])) {
                $this->moduleData->errors[] = 'Не верный тип шаблона';
                $error = true;
            }

            if(!$error){

                //params[$col['Field']]
                $template = new iceTemplate($this->DB, $this->values->id);
                //$template->getRecord($this->values->id);
                if($template->updateRecord([
                    'filename' => $this->values->filename,
                    'name' => $this->values->name,
                    'type' => $this->values->type,
                    'content' => $this->values->content
                ])){
                    $this->moduleData->success[] = 'Шаблон <strong>'.$this->values->name.'</strong> успешно изменен';
                }
                else {
                    $this->moduleData->errors[] = 'Не удалось изменить шаблон';
                }

                $this->values->filename = '';
                $this->values->name = '';
                $this->values->type = '';
                $this->values->content = '';

            }

        }

        //получаем данные шаблона для вывода
        $this->moduleData->editTemplate = new iceTemplate($this->DB, $this->values->id);
        $this->moduleData->editTemplate->getRecord($this->values->id);

        break;

    //TODO - удаление шаблона
    case 'delete':

        break;
}

//получение списка шаблонов
$templates = new iceTemplateList($this->DB, null, [['col' => 'name', 'sort' => 'ASC']]);
$templates = $templates->getRecords();
//добавляем в список шаблонов данные о типах материалов и названия типов
foreach ($templates as $template) {
    $templateObj = new iceTemplate($this->DB, $template['id']);
    $templateObj->getRecord($template['id']);
    $this->moduleData->templates[]=$templateObj->params;
}