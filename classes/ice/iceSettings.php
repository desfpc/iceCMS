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


class iceSettings {
    public $db;
    public $email;
    public $sms;
    public $template;
    public $errors;
    public $site;
    public $cache;
    public $path;

    public function __construct($setup)
    {

        $this->errors = new \stdClass();
        $this->errors->flag=0;
        $this->errors->text='Настройки не загружались';

        try
        {

            $settingsvalues=array();

            $settingsvalues['path']=1;
            $settingsvalues['template']=1;
            $settingsvalues['dev']=1;

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

            foreach ($settingsvalues as $key => $value)
            {
                if(is_array($value))
                {
                    $paramname=$key;

                    if(!isset($setup[$paramname]) && !is_array($setup[$paramname]))
                    {
                        throw new \Exception('Ошибка файла настроек - нет необходимого поля либо оно не является массивом: '.$paramname);
                    }

                    $this->$paramname = new \stdClass();

                    foreach ($setup[$paramname] as $key2 => $value2)
                    {
                        $paramname2=$key2;

                        if($value2 == 1)
                        {
                            if(!isset($setup[$paramname][$paramname2]))
                            {
                                throw new \Exception('Ошибка файла настроек - нет необходимого поля: '.$paramname.'-'.$paramname2);
                            }
                        }

                        $this->$paramname->$paramname2 = $setup[$paramname][$paramname2];

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