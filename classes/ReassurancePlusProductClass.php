<?php
/**
 * ReassurancePlusProductClass.php
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
class ReassurancePlusProductClass extends ObjectModel
{
    public $id_reassurance;
    public $title;
    public $description;
    public $image;
    public $id_product;

    public static $definition = [
        'table' => 'reassurance_product',
        'primary' => 'id_reassurance',
        'multilang' => false,
        'fields' => [
            'title' => ['type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'required' => true],
            'description' => ['type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'required' => true],
            'image' => ['type' => self::TYPE_STRING, 'validate' => 'isCleanHtml', 'required' => true],
            'id_product' => ['type' => self::TYPE_INT, 'validate' => 'isUnsignedId', 'required' => true],
        ],
    ];
}