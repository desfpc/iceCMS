<?php
/**
 * iceCMS v0.1
 * Created by Sergey Peshalov https://github.com/desfpc
 * PHP framework and CMS based on it.
 * https://github.com/desfpc/iceCMS
 *
 * Enum Abstract Class
 *
 */

namespace ice\Models;

/**
 * Class Enum
 * @package ice\Models
 */
abstract class Enum
{
    /**
     * @var array<string, string> возможные варианты
     */
    protected array $enums = [];

    /**
     * @var array<string, string[]> возможные переходы из одного enum на другие
     */
    protected array $actions = [];

    /**
     * @var null|array<string, string> цвет для значения Enum
     */
    protected ?array $colors = [];

    /**
     * Enum constructor.
     *
     * @param array<string, string> $enums
     * @param array<string, string[]> $actions
     */
    public function __construct(array $enums, array $actions, ?array $colors = null)
    {
        $this->enums = $enums;
        $this->actions = $actions;
        $this->colors = $colors;
    }

    /**
     * Return Enum Name
     *
     * @param string $enum
     * @return string
     */
    public function GetName(string $enum): string
    {
        //TODO use Translator class
        return $this->enums[$enum];
    }

    /**
     * Return String Array of actions(from enum to enum array)
     *
     * @param string $enum
     * @return false|string[]
     */
    public function GetActions(string $enum): ?array
    {
        if(!empty($this->actions[$enum])) {
            return $this->actions[$enum];
        }
        return false;
    }

    /**
     * Return Enum Color
     *
     * @param string $enum
     * @return false|string
     */
    public function GetColor(string $enum): ?string
    {
        if(!empty($this->colors[$enum])) {
            return $this->colors[$enum];
        }
        return false;
    }
}