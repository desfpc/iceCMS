<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * Material Class
 *
 */

namespace ice\Models;

use ice\DB\DB;
use ice\Helpers\Strings;
use visualijoper\visualijoper;

/**
 * Class Mat
 * @package ice\Models
 */
class Mat extends Obj
{

    public $files = [];
    public $extraValues = [];

    /**
     * Mat constructor.
     *
     * @param DB $DB
     * @param null|int $id
     * @param null|array $settings
     */
    public function __construct(DB $DB, ?int $id = null, ?array $settings = null)
    {
        $this->doConstruct($DB, 'materials', $id, $settings);
    }

    /**
     * Price Formatter
     *
     * @param $price
     * @return string
     */
    public static function price($price)
    {

        return number_format($price, 2, '<small>,', '&nbsp;') . '&nbsp;₽</small>';

    }

    /**
     * @param int $id id статуса материала
     * @return string
     */
    public static function statusIcon(int $id): string
    {
        $id = (int)$id;
        switch ($id) {
            case 0:
                return '<i class="material-icons md-16 md-grey">visibility_off</i>';
                break;
            case 1:
                return '<i class="material-icons md-16 md-green">visibility</i>';
                break;
            case 2:
                return '<i class="material-icons md-16 md-red">delete</i>';
                break;
        }
    }

    /**
     * @param int $id id статуса материала
     * @return string
     */
    public static function statusName(int $id): string
    {
        $id = (int)$id;
        switch ($id) {
            case 0:
                return 'скрытый';
                break;
            case 1:
                return 'актывный';
                break;
            case 2:
                return 'архивный';
                break;
        }
    }

    /**
     * @inheritDoc
     */
    public function fullRecord()
    {
        $this->getMatTypeData();
        $this->getUserName();
        $this->getFiles();
        $this->getExtraValues();
    }

    /**
     * Формирование данных типа материала
     */
    public function getMatTypeData()
    {
        $query = 'SELECT name, template_list, template_item, template_admin FROM material_types WHERE id = ' . $this->params['material_type_id'];
        if ($res = $this->DB->query($query)) {
            $this->params['material_type_name'] = $res[0]['name'];

            //проверяем идентификаторы шаблонов и устанавливаем дефолтные значения
            if ($res[0]['template_list'] == '') {
                $res[0]['template_list'] = 2; //по дефолту шаблон списка новостей
            }
            if ($res[0]['template_item'] == '') {
                $res[0]['template_item'] = 3; //по дефолту шаблон детализации новости
            }
            if ($res[0]['template_admin'] == '') {
                $res[0]['template_admin'] = 4; //по дефолту шаблон редактирования новости
            }

            $listTemplate = new template($this->DB, $res[0]['template_list']);
            $listTemplate->getRecord($res[0]['template_list']);

            $itemTemplate = new template($this->DB, $res[0]['template_item']);
            $itemTemplate->getRecord($res[0]['template_item']);

            $adminTemplate = new template($this->DB, $res[0]['template_admin']);
            $adminTemplate->getRecord($res[0]['template_admin']);

            //заносим шаблоны в свойства материала
            $this->params['templates'] = [
                'template_list' => [
                    'id' => $res[0]['template_list'],
                    'name' => $listTemplate->params['name'],
                    'filename' => $listTemplate->params['filename']
                ],
                'template_item' => [
                    'id' => $res[0]['template_item'],
                    'name' => $itemTemplate->params['name'],
                    'filename' => $itemTemplate->params['filename']
                ],
                'template_admin' => [
                    'id' => $res[0]['template_admin'],
                    'name' => $adminTemplate->params['name'],
                    'filename' => $adminTemplate->params['filename']
                ]
            ];
        }
    }

    /**
     * Формирование имя автора
     */
    public function getUserName()
    {
        $query = 'SELECT full_name FROM users WHERE id = ' . $this->params['user_id'];
        if ($res = $this->DB->query($query)) {
            $this->params['user_name'] = $res[0]['full_name'];
        }
    }

    /**
     * Формирование списка файлов
     */
    public function getFiles()
    {
        //получение списка файлов материала (прямой запрос, так как есть обязательная связка и нам надо поле индивидуальной сортировки)
        $query = 'SELECT f.*, m.ordernum 
        FROM files f, material_files m 
        WHERE f.id = m.file_id AND m.material_id = ' . $this->params['id'] . '
        ORDER BY m.ordernum ASC, f.id ASC';

        if ($res = $this->DB->query($query)) {
            if (count($res) > 0) {
                $this->files = $res;
            }
        }
    }

    /**
     * Получение дополнительных параметров
     */
    public function getExtraValues()
    {
        $conditions = [];
        $sort = [];

        $conditions[] = [
            'string' => false,
            'type' => '=',
            'col' => 'material_id',
            'val' => $this->id
        ];

        $sort[] = ['col' => 'param_name', 'sort' => 'ASC'];

        $extraValues = new MatExtraValuesList($this->DB, $conditions, $sort, 1, 100);
        $this->extraValues = $extraValues->getRecords();
    }

    /**
     * Формирование параметров материала из REQUEST значений
     *
     * @param $values
     * @return array
     */
    public function paramsFromValues($values)
    {
        $params = $this->params;
        foreach ($this->cols as $col) {
            $valueName = $col['Field'];
            if ($valueName != 'id') {
                if (isset($values->$valueName) && $values->$valueName !== '') {
                    $params[$col['Field']] = $values->$valueName;
                } //обьявляем дефолтные значения некоторых переменных
                else {
                    switch ($valueName) {
                        case 'id_char':
                            $params[$col['Field']] = Strings::makeCharId($values->name);
                            break;
                        default:
                            if (!isset($params[$col['Field']])) {
                                $params[$col['Field']] = null;
                            }
                            break;
                    }
                }
            }
        }
        return $params;
    }

    /**
     * Get Full Material URL
     *
     * @param array<string, mixed> $params
     * @param array{types: array, childs: array} $types Material Types By Id (types) and Tree (childs)
     * @return string
     */
    public static function GetUrl(array $params, array $types): string
    {
        $matType=$types['types'][$params['material_type_id']];
        $url = '';
        self::typesTree($matType['id'], $types, $url);
        $url = $url.$params['id_char'];
        return '/'.$url;
    }

    /**
     * Обход дерева типов для постройки URL
     *
     * @param $type
     * @param $types
     * @param $url
     */
    private static function typesTree($type, $types, &$url) {
        $url = $types['types'][$type]['id_char'].'/'.$url;
        if($types['types'][$type]['parent_id'] != 0) {
            self::typesTree($types['types'][$type]['parent_id'], $types, $url);
        }
    }

}