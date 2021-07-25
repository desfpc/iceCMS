<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * Authorization Class
 * TODO Token authorization
 *
 */

namespace ice\Web;

use ice\DB\DB;
use ice\Models\User;

class Authorize
{
    /** @var bool authorized flag */
    public $autorized = false;
    /** @var null|User authorized User obj */
    public ?User $user = null;
    /** @var null|string[] errors array */
    public $errors = null;
    /** @var bool|int|string */
    public $secure = false;

    /**
     * Authorize class constructor
     *
     * @param DB $DB
     * @param null|string $login
     * @param null|string $pass
     */
    public function __construct(DB $DB, $login = null, $pass = null)
    {
        $this->doAuthorize($DB, $login, $pass);
    }

    /**
     * Authorization method
     *
     * @param $DB
     * @param string $login
     * @param string $pass
     * @return bool
     */
    public function doAuthorize($DB, ?string $login, ?string $pass): bool
    {
        $user = new User($DB);
        if ($user->authorizeUser($pass, $login)) {
            $this->user = $user;
            $this->autorized = true;
            $this->secure = $user->params['role']['secure'];
            return true;
        }

        $this->errors = ['Не верное сочетание логина и пароля. Забыли пароль? <a href="/password-recovery">Восстановить</a>.'];
        return false;

    }

    /**
     * DeAuthorization method
     *
     * @return void
     */
    public function deAuthorize()
    {
        if (!is_null($this->user)) {
            $this->user->deauthorizeUser();
        }
        $this->user = null;
        $this->autorized = false;
    }

}