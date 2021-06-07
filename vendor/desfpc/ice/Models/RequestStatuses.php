<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * Request Statuses Class
 *
 */

namespace ice\Models;

/**
 * Class RequestStatuses
 * @package ice\Models
 */
class RequestStatuses extends Enum
{
    /**
     * RequestSatatuses constructor.
     */
    public function __construct()
    {
        parent::__construct([
            'created' => 'создан',
            'in_work' => 'в работе',
            'ready' => 'готов к выдаче',
            'completed' => 'выполнен',
            'cancelled' => 'отменен'
        ],
        [
            'created' => ['in_work', 'ready', 'completed', 'cancelled'],
            'in_work' => ['ready', 'completed', 'cancelled'],
            'ready' => ['completed', 'cancelled'],
            'cancelled' => ['created']
        ],
        [
            'created' => '#ffaaaa',
            'in_work' => '#ffffaa',
            'ready' => '#aaffff',
            'completed' => '#aaffaa',
            'cancelled' => '#aaaaaa'
        ],
        [
            'created' => 'btn-light',
            'in_work' => 'btn-warning',
            'ready' => 'btn-info',
            'completed' => 'btn-success',
            'cancelled' => 'btn-danger'
        ],
        [
            'created' => 'shopping_cart',
            'in_work' => 'work',
            'ready' => 'local_shipping',
            'completed' => 'done',
            'cancelled' => 'delete'
        ]
        );
    }
}