<?php
/**
 * ReassurancePlusClass.php
 *
 * Represents the pending product comments class
 *
 * @author    Samar Al Khalil
 * @copyright Copyright (c)
 * @license   License (if applicable)
 * @category  Classes
 * @package   ReassurancePlus
 * @subpackage Classes
 */
class ReassurancePlusClass extends ObjectModel
{
    public $id_reassurance;
    public $title;
    public $description;
    public $image;

    public static $definition = [
        'table' => 'reassurance_plus',
        'primary' => 'id_reassurance',
        'fields' => [
            'title' => ['type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true],
            'description' => ['type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'required' => true],
            'image' => ['type' => self::TYPE_STRING, 'validate' => 'isString', 'required' => true],
        ],
    ];
}
