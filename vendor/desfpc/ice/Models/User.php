<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * User Class
 *
 */

namespace ice\Models;

use ice\DB\DB;
use ice\Helpers\Strings;
use ice\Settings\Settings;

/**
 * Class User
 * @package ice\Models
 */
class User extends Obj
{

    //TODO вынести в класс переводчик
    public static $labels = [
        'id' => 'ID',
        'login_email' => 'Email (логин)',
        'login_phone' => 'Телефон (логин)',
        'nik_name' => 'Ник',
        'full_name' => 'ФИО',
        'passcode' => 'Код подтверждения',
        'status_id' => 'ID статуса',
        'status' => 'Статус',
        'password' => 'Пароль',
        'date_add' => 'Дата создания',
        'contacts' => 'Контакты',
        'user_state' => 'Состояние',
        'user_role' => 'Роль',
        'sex' => 'Пол'
    ];

    /**
     * User constructor
     *
     * @param DB $DB
     * @param null $id
     * @param Settings|null $settings
     */
    public function __construct(DB $DB, $id = null, ?Settings $settings = null)
    {
        $this->doConstruct($DB, 'users', $id, $settings);
    }

    /**
     * Get User by Email
     *
     * @param DB $DB
     * @param string $email
     * @param Settings|null $settings
     * @return User|null
     */
    public static function getByEmail(DB $DB, string $email, ?Settings $settings = null): ?User
    {
        //получение пользователя
        $query = 'SELECT id FROM users WHERE status_id = 1 AND login_email = \'' . $DB->mysqli->real_escape_string($email) . '\'';
        if ($res = $DB->query($query)) {
            if (count($res) > 0) {
                return (new User($DB, $res[0]['id'], $settings));
            }
        }
        return null;
    }

    /**
     * Set random password to User
     *
     * @return bool|string
     */
    public function setRandomPass()
    {
        $newPass = Strings::randomPassword(8);
        $passHash = password_hash($newPass, PASSWORD_DEFAULT);
        if (!$this->params) {
            $this->getRecord();
        }

        $query = 'UPDATE users SET password = \'' . $passHash . '\' WHERE id = ' . $this->id;
        if ($res = $this->DB->query($query)) {
            $this->uncacheRecord();
            return $newPass;
        }
        return false;
    }

    /**
     * Register new User
     *
     * @param array<string, mixed> $params
     * @return bool
     */
    public function registerUser(array $params): bool
    {
        //проверяем необходимые параметры
        if (isset($params['login_email']) && isset($params['password_input'])) {

            //проверяем существующих пользюков с такими email и tel
            $query = 'SELECT count(id) cid FROM users WHERE login_email = ' . "'" . $params['login_email'] . "'";

            if (!is_null($params['login_phone'])) {
                $query .= ' OR login_phone = ' . "'" . $params['login_phone'] . "'";
            }

            if ($res = $this->DB->query($query)) {
                if (count($res) > 0) {
                    if ($res[0]['cid'] > 0) {
                        $this->errors[] = 'Пользователь с таким email или телефоном уже существует';
                        return false;
                    }
                }
            }

            //генерируем хэш пароля
            $params['password'] = password_hash($params['password_input'], PASSWORD_DEFAULT);

            //пробуем сохранить пользюка
            if ($this->createRecord($params)) {
                $this->errors[] = 'Не удалось зарегистрировать пользователя';
                return true;
            }
        }
        return false;
    }

    /**
     * User Authorization
     *
     * @param ?string $pass
     * @param ?string $email
     * @return false|int
     */
    public function authorizeUser(?string $pass, ?string $email)
    {
        if ((is_null($pass) || $pass == '') && (is_null($email) || $email == '')) {
            //еси еть php сессия
            if (isset($_SESSION['authorize'])) {
                if ($_SESSION['authorize']) {
                    $this->id = $_SESSION['authorize'];
                    if ($this->getRecord($this->id))
                        return ($this->id);
                }
            } else {
                $_SESSION['authorize'] = false;
            }
            return false;
        }

        //проверяем запись пользователя
        $query = 'SELECT * FROM users WHERE status_id = 1 AND login_email = \'' . $this->DB->mysqli->real_escape_string($email) . '\'';

        //visualijoper\visualijoper::visualijop($query);

        if ($res = $this->DB->query($query)) {
            if (count($res) > 0) {
                $user = $res[0];

                //проверяем пароль
                if ($ver = password_verify($pass, $user['password'])) {
                    $this->id = $user['id'];
                    $this->getRecord($this->id);

                    $_SESSION['authorize'] = $this->id;

                    return ($this->id);
                }
            }
        }
        return false;
    }

    /**
     * Deauthorize User
     *
     * @return bool
     */
    public function deauthorizeUser(): bool
    {
        unset($_SESSION['authorize']);
        $this->id = null;
        return true;
    }

    /**
     * Disable User
     *
     * @return bool
     */
    public function disableUser(): bool
    {
        return $this->changeUserStatus(2);
    }

    /**
     * Change User Status
     *
     * @param int $status
     * @return bool
     */
    public function changeUserStatus(int $status): bool
    {
        if ($this->isGotten) {
            $query = 'UPDATE users SET status_id = ' . $status . ' WHERE id = ' . $this->params['id'];
            if ($res = $this->DB->query($query)) {
                $this->uncacheRecord();

                //TODO удаление сессии пользователя при отключении

                return true;
            }
        }
        return false;
    }

    /**
     * Enable User
     *
     * @return bool
     */
    public function enableUser(): bool
    {
        return $this->changeUserStatus(1);
    }

    /**
     * More actions for getting full User record
     *
     * @return void
     */
    public function fullRecord()
    {
        $this->getRole();
    }

    /**
     * Get User Role
     *
     * @return void
     */
    public function getRole()
    {
        $query = 'SELECT * from user_roles WHERE id = ' . $this->params['user_role'];
        if ($res = $this->DB->query($query)) {
            $this->params['role'] = $res[0];
        }
    }
}