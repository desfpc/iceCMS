<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * Request Payment Methods Class
 *
 */

namespace ice\Models;

/**
 * Class RequestStatuses
 * @package ice\Models
 */
class RequestPayments extends Enum
{
    /**
     * RequestSatatuses constructor.
     */
    public function __construct()
    {
        parent::__construct([
            'on_delivery' => 'При получении'
        ],[]);
    }
}