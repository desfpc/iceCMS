<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * Sending messages (email, sms, push, etc...) Class
 *
 */

namespace ice;

use pechkin\pechkin;

class iceMessage {

    const TYPES = [
        'email' //TODO SMS, PUSH, Telegram, etc
    ];

    public $types;
    public $settings;

    public function __construct(iceSettings $settings, $types = ['email']){

        if(empty($types)) return false;

        if(!is_array($types)){
            $types = explode(',',$types);
        }

        foreach ($types as $type){
            if(!in_array($type, self::TYPES)){
                return false;
            }
        }

        $this->types = $types;
        $this->settings = $settings;

    }

    //send message
    public function send($to, $subject, $message, $attachments=[]){

        if(count($this->types) == 0) return false;

        foreach($this->types as $type){

            switch ($type){
                case 'email':
                    $this->sendEmail($to, $subject, $message, $attachments);
                    break;
            }
        }
    }

    //send email
    public function sendEmail($to, $subject, $message, $attachments=[]){
        $mail = new pechkin(
            $this->settings->email->smtp,
            $this->settings->email->port,
            $this->settings->email->mail,
            $this->settings->email->pass,
            'ssl',
            60,
            false
        );

        if(is_array($attachments) && count($attachments) > 0){
            foreach ($attachments as $attachment){
                $mail->addAttachment($attachment);
            }
        }

        return $mail->send($this->settings->email->mail, $to, $subject, $message);
    }

    //TODO create TO string from email and name strings
    public static function makeTo($email, $name){

    }

}