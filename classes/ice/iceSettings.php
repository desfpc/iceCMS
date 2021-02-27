<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * Settings Class
 *
 */

namespace ice;

use ice\DB\DB;

class iceSettings {
    public $db;
    public $email;
    public $sms;
    public $template;
    public $errors;
    public $site;
    public $cache;
    public $path;
    public $routes;
    public $secret;
    public $dev;

    public function save($adminPath = 'admin'){

        $filePath = $this->path.'/settings/settings.php';


        //boolean значения
        if($this->site->redirect_to_primary_domain === true){
            $redirect_to_primary_domain = 'true';
        }
        else {
            $redirect_to_primary_domain = 'false';
        }
        if($this->site->language_subdomain === true){
            $language_subdomain = 'true';
        }
        else {
            $language_subdomain = 'false';
        }
        if($this->cache->use_redis === true){
            $use_redis = 'true';
        }
        else {
            $use_redis = 'false';
        }


        $fileContent = "<?php
\$setup=[];

//общие настройки
\$setup['path']='".$this->path."';

//настройки шаблонизатора (папка с шаблонами)
\$setup['template']='".$this->template."';

//настройка релиз/разработка
\$setup['dev']=".$this->dev.";

//уникальный секрет
\$setup['secret']='".$this->secret."';

//настройки БД
\$setup['db']=[];
\$setup['db']['type']='".$this->db->type."';
\$setup['db']['name']='".$this->db->name."';
\$setup['db']['host']='".$this->db->host."';
\$setup['db']['port']='".$this->db->port."';
\$setup['db']['login']='".$this->db->login."';
\$setup['db']['pass']='".$this->db->pass."';
\$setup['db']['encoding']='".$this->db->encoding."';

//настройки системы рассылки
\$setup['email']=[];
\$setup['email']['mail']='".$this->email->mail."';
\$setup['email']['port']='".$this->email->port."';
\$setup['email']['signature']='".$this->email->signature."';
\$setup['email']['pass']='".$this->email->pass."';
\$setup['email']['smtp']='".$this->email->smtp."';

//настройки сайта
\$setup['site']=[];
\$setup['site']['title']='".$this->site->title."';
\$setup['site']['primary_domain'] = '".$this->site->primary_domain."';
\$setup['site']['redirect_to_primary_domain'] = $redirect_to_primary_domain;
\$setup['site']['language_subdomain'] = $language_subdomain;

//настройки кэширования
\$setup['cache']=[];
\$setup['cache']['use_redis']=$use_redis;
\$setup['cache']['redis_host']='".$this->cache->redis_host."';
\$setup['cache']['redis_port']=".$this->cache->redis_port.";

//роутинг для ЧПУ модулей //TODO сделать возможность передачи ЧПУ переменных
\$setup['routes'] = [];";

        //автоматическая генерация роутов для модулей
        $connection = new DB($this);
        if(isset($connection->errors) && $connection->errors->flag == 1)
        {
            return false;
        }

        $query = 'SELECT name, secure FROM modules ORDER BY secure ASC, name ASC';
        if($res = $connection->query($query)){
            foreach ($res as $row){

                $lowerName = mb_strtolower($row['name'], 'UTF8');

                //административные модули
                if($row['secure'] == 1){
                    $fileContent.='
$setup[\'routes\'][\''.$adminPath.'/'.$lowerName.'\'] = \''.$row['name'].'\';';
                }
                //открытые модули кроме materials
                elseif($row['name'] != 'materials') {
                    $fileContent.='
$setup[\'routes\'][\''.$lowerName.'\'] = \''.$row['name'].'\';';
                }
            }
        }

        //заносим текущие роуты
        if(count($this->routes) > 0){
            foreach ($this->routes as $key=>$value){
                $fileContent.="
\$setup['routes']['$key'] = '$value';";
            }
        }

        if(file_put_contents($filePath, $fileContent)){
            return true;
        }

        return false;
    }

    public function __construct($setup)
    {

        $this->errors = new \stdClass();
        $this->errors->flag=0;
        $this->errors->text='Настройки не загружались';

        try
        {

            $settingsvalues=[];

            $settingsvalues['path']=1;
            $settingsvalues['template']=1;
            $settingsvalues['dev']=1;
            $settingsvalues['secret']=1;

            $settingsvalues['db']['type']=1;
            $settingsvalues['db']['name']=1;
            $settingsvalues['db']['host']=1;
            $settingsvalues['db']['port']=1;
            $settingsvalues['db']['login']=1;
            $settingsvalues['db']['pass']=1;
            $settingsvalues['db']['encoding']=1;

            $settingsvalues['email']['mail']=1;
            $settingsvalues['email']['port']=1;
            $settingsvalues['email']['signature']=1;
            $settingsvalues['email']['pass']=1;
            $settingsvalues['email']['smtp']=1;

            $settingsvalues['site']['title']=1;
            $settingsvalues['site']['primary_domain']=1;
            $settingsvalues['site']['redirect_to_primary_domain']=1;
            $settingsvalues['site']['language_subdomain']=1;

            $settingsvalues['cache']['use_redis']=1;
            $settingsvalues['cache']['redis_host']=1;
            $settingsvalues['cache']['redis_port']=1;

            $settingsvalues['routes']=[];

            foreach ($settingsvalues as $key => $value)
            {
                if(is_array($value))
                {
                    $paramname=$key;
                    if(count($value) > 0){

                        if(!isset($setup[$paramname]) && !is_array($setup[$paramname]))
                        {
                            throw new \Exception('Ошибка файла настроек - нет необходимого поля либо оно не является массивом: '.$paramname);
                        }

                        $this->$paramname = new \stdClass();

                        foreach ($setup[$paramname] as $key2 => $value2)
                        {
                            $paramname2=$key2;

                            /*if($value2 == 1)
                            {
                                if(!isset($setup[$paramname][$paramname2]))
                                {
                                    throw new \Exception('Ошибка файла настроек - нет необходимого поля: '.$paramname.'-'.$paramname2);
                                }
                            }*/

                            $this->$paramname->$paramname2 = $setup[$paramname][$paramname2];

                        }
                    }
                    else {

                        $this->$paramname = $setup[$paramname];

                    }

                }
                else
                {
                    $paramname=$key;

                    if($value == 1)
                    {
                        if(!isset($setup[$paramname]))
                        {
                            throw new \Exception('Ошибка файла настроек - нет необходимого поля: '.$paramname);
                        }
                    }

                    $this->$paramname = $setup[$paramname];

                }
            }

            $this->errors->flag=0;
            $this->errors->text='Настройки загружены';

        }
        catch (\Throwable $t)
        {
            $this->errors->flag=1;
            $this->errors->text='Не удалось загрузить настройки: '.$t->getMessage();
        }

    }

}