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

namespace ice\Messages;

use ice\Settings\Settings;
use pechkin\pechkin;

class Message
{

    const TYPES = [
        'email' //TODO SMS, PUSH, Telegram, etc
    ];

    public $types;
    public $settings;

    /**
     * Message constructor.
     *
     * @param Settings $settings
     * @param string|string[] $types
     */
    public function __construct(Settings $settings, $types = 'email')
    {

        if (empty($types)) return false;

        if (!is_array($types)) {
            $types = explode(',', $types);
        }

        foreach ($types as $type) {
            if (!in_array($type, self::TYPES)) {
                return false;
            }
        }

        $this->types = $types;
        $this->settings = $settings;

    }

    /**
     * Формирование email заголовка: "name <email>"
     *
     * @param $email
     * @param $name
     * @return string
     */
    public static function makeTo($email, $name): string
    {
        if($name == '') return $email;
        return $name.' <'.$email.'>';
    }

    /**
     * Отсылка сообщения
     *
     * @param string $to
     * @param string $toName
     * @param string $subject
     * @param string $message
     * @param array $attachments
     * @return bool
     */
    public function send($to, $toName = '', $subject, $message, $attachments = [])
    {
        if (count($this->types) == 0) return false;
        $errors = [];

        foreach ($this->types as $type) {
            switch ($type) {
                case 'email':
                    if(!$this->sendEmail(self::makeTo($to, $toName), $subject, $message, $attachments)) {
                        $errors[] = 'Ошибка отправки email';
                    };
                    break;
            }
        }

        if($errors) return false;
        return true;
    }

    /**
     * Отсылка email транспорт
     *
     * @param $to
     * @param $subject
     * @param $message
     * @param array $attachments
     * @return bool
     */
    private function sendEmail($to, $subject, $message, $attachments = [])
    {
        $mail = new pechkin(
            $this->settings->email->smtp,
            $this->settings->email->port,
            $this->settings->email->mail,
            $this->settings->email->pass,
            'ssl',
            60,
            false
        );

        if (is_array($attachments) && count($attachments) > 0) {
            foreach ($attachments as $attachment) {
                $mail->addAttachment($attachment);
            }
        }

        return $mail->send(SELF::makeTo($this->settings->email->mail, $this->settings->email->signature), $to, $subject, $message);
    }

}