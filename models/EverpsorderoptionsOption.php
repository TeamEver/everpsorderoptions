<?php
/**
 * 2019-2021 Team Ever
 *
 * NOTICE OF LICENSE
 *
 * This source file is subject to the Academic Free License (AFL 3.0)
 * that is bundled with this package in the file LICENSE.txt.
 * It is also available through the world-wide-web at this URL:
 * http://opensource.org/licenses/afl-3.0.php
 * If you did not receive a copy of the license and are unable to
 * obtain it through the world-wide-web, please send an email
 * to license@prestashop.com so we can send you a copy immediately.
 *
 *  @author    Team Ever <https://www.team-ever.com/>
 *  @copyright 2019-2021 Team Ever
 *  @license   http://opensource.org/licenses/afl-3.0.php  Academic Free License (AFL 3.0)
 */

if (!defined('_PS_VERSION_')) {
    exit;
}

class EverpsorderoptionsOption extends ObjectModel
{
    public $id_field;
    public $id_shop;
    public $active;
    public $manage_quantity;
    public $quantity;
    public $position;
    public $option_title;
    public $option_value;

    public static $definition = array(
        'table' => 'everpsorderoptions_option',
        'primary' => 'id_everpsorderoptions_option',
        'multilang' => true,
        'fields' => array(
            'id_shop' => array(
                'type' => self::TYPE_INT,
                'lang' => false,
                'validate' => 'isUnsignedInt',
                'required' => false
            ),
            'id_field' => array(
                'type' => self::TYPE_INT,
                'lang' => false,
                'validate' => 'isUnsignedInt',
                'required' => true
            ),
            'manage_quantity' => array(
                'type' => self::TYPE_BOOL,
                'lang' => false,
                'validate' => 'isBool'
            ),
            'quantity' => array(
                'type' => self::TYPE_INT,
                'lang' => false,
                'validate' => 'isUnsignedInt'
            ),
            'position' => array(
                'type' => self::TYPE_INT,
                'lang' => false,
                'validate' => 'isUnsignedInt'
            ),
            'active' => array(
                'type' => self::TYPE_BOOL,
                'lang' => false,
                'validate' => 'isBool'
            ),
            // lang options
            'option_title' => array(
                'type' => self::TYPE_HTML,
                'lang' => true,
                'validate' => 'isCleanHtml'
            ),
            'option_value' => array(
                'type' => self::TYPE_HTML,
                'lang' => true,
                'validate' => 'isCleanHtml'
            ),
        )
    );

    public static function getFullOptions($id_shop, $id_lang, $quantity = true)
    {
        $sql = new DbQuery;
        $sql->select('*');
        $sql->from(
            'everpsorderoptions_option',
            'ef'
        );
        $sql->leftJoin(
            'everpsorderoptions_option_lang',
            'efl',
            'ef.id_everpsorderoptions_option = efl.id_everpsorderoptions_option'
        );
        $sql->where(
            'ef.id_shop = '.(int)$id_shop
        );
        $sql->where(
            'efl.id_lang = '.(int)$id_lang
        );
        $sql->where(
            'ef.active = 1'
        );
        if ($quantity) {
            $sql->where(
                'ef.manage_quantity = 1'
            );
            $sql->where(
                'ef.quantity > 0'
            );
        }
        $sql->orderBy(
            'position ASC'
        );
        return Db::getInstance()->executeS($sql);
    }

    public static function fieldHasOptions($id_field, $id_shop, $quantity = false)
    {
        $sql = new DbQuery;
        $sql->select('COUNT(*)');
        $sql->from(
            'everpsorderoptions_option'
        );
        $sql->where(
            'id_field = '.(int)$id_field
        );
        $sql->where(
            'id_shop = '.(int)$id_shop
        );
        if ($quantity) {
            $sql->where(
                'manage_quantity = 1'
            );
            $sql->where(
                'quantity > 0'
            );
        }
        $count = Db::getInstance()->getValue($sql);
        return (int)$count;
    }

    public static function getFieldOptions($id_field, $id_shop, $id_lang, $quantity = true)
    {
        $sql = new DbQuery;
        $sql->select('ef.id_everpsorderoptions_option');
        $sql->from(
            'everpsorderoptions_option',
            'ef'
        );
        $sql->leftJoin(
            'everpsorderoptions_option_lang',
            'efl',
            'ef.id_everpsorderoptions_option = efl.id_everpsorderoptions_option'
        );
        $sql->where(
            'ef.id_field = '.(int)$id_field
        );
        $sql->where(
            'ef.id_shop = '.(int)$id_shop
        );
        $sql->where(
            'efl.id_lang = '.(int)$id_lang
        );
        $sql->where(
            'ef.active = 1'
        );
        if ($quantity) {
            $sql->where(
                'ef.manage_quantity = 1'
            );
            $sql->where(
                'ef.quantity > 0'
            );
        }
        $sql->orderBy(
            'position ASC'
        );
        $options_array = Db::getInstance()->executeS($sql);
        $field_options = [];
        foreach ($options_array as $option_array) {
            $field_option = new self(
                (int)$option_array['id_everpsorderoptions_option'],
                (int)$id_lang,
                (int)$id_shop
            );
            $front_value = array(
                'option_value' => $field_option->option_value
            );
            $field_option->front_value = serialize($front_value);
            $field_options[] = $field_option;
        }
        return $field_options;
    }

    public static function getObjByOptionName($option_value)
    {
        $sql = new DbQuery;
        $sql->select('id_everpsorderoptions_option');
        $sql->from(
            'everpsorderoptions_option_lang'
        );
        $sql->where(
            'option_value = "'.pSQL($option_value).'"'
        );
        $option = new self(
            (int)Db::getInstance()->getValue($sql)
        );
        if (Validate::isLoadedObject($option)) {
            return $option;
        }
        return false;
    }
}
