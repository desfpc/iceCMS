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
     * @var array<string, string>|null сss класс кнопки
     */
    protected ?array $btnClasses = [];

    /**
     * @var array<string, string>|null иконка
     */
    protected ?array $icons = [];

    /**
     * Enum constructor.
     *
     * @param array<string, string> $enums
     * @param array<string, string[]> $actions
     * @param array<string, string>|null $colors
     * @param array<string, string>|null $btnClasses
     * @param array<string, string>|null $icons
     */
    public function __construct(array $enums, array $actions, ?array $colors = null, ?array $btnClasses = null, ?array $icons = null)
    {
        $this->enums = $enums;
        $this->actions = $actions;
        $this->colors = $colors;
        $this->btnClasses = $btnClasses;
        $this->icons = $icons;
    }

    /**
     * Return Enums array
     *
     * @return string[]
     */
    public function getList()
    {
        return $this->enums;
    }

    /**
     * Return Enum Name
     *
     * @param string $enum
     * @return string
     */
    public function getName(string $enum): string
    {
        //TODO use Translator class
        return $this->enums[$enum];
    }

    /**
     * Return String Array of actions(from enum to enum array)
     *
     * @param string $enum
     * @return bool|string[]
     */
    public function getActions(string $enum)
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
    public function getColor(string $enum): ?string
    {
        if(!empty($this->colors[$enum])) {
            return $this->colors[$enum];
        }
        return false;
    }

    /**
     * Return Enum Btn Class
     *
     * @param string $enum
     * @return string|null
     */
    public function getBtnClass(string $enum): ?string
    {
        if(!empty($this->btnClasses[$enum])) {
            return $this->btnClasses[$enum];
        }
        return false;
    }

    /**
     * Return Icon
     *
     * @param string $enum
     * @return string|null
     */
    public function getIcon(string $enum): ?string
    {
        if(!empty($this->icons[$enum])) {
            return $this->icons[$enum];
        }
        return false;
    }
}