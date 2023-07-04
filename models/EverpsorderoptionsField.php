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

require_once _PS_MODULE_DIR_.'everpsorderoptions/models/EverpsorderoptionsOption.php';

class EverpsorderoptionsField extends ObjectModel
{
    public $type;
    public $id_shop;
    public $is_required;
    public $active;
    public $manage_quantity;
    public $quantity;
    public $position;
    public $field_title;
    public $field_description;

    public static $definition = array(
        'table' => 'everpsorderoptions_field',
        'primary' => 'id_everpsorderoptions_field',
        'multilang' => true,
        'fields' => array(
            'id_shop' => array(
                'type' => self::TYPE_INT,
                'lang' => false,
                'validate' => 'isUnsignedInt',
                'required' => false
            ),
            'type' => array(
                'type' => self::TYPE_HTML,
                'lang' => false,
                'validate' => 'isGenericName',
                'required' => true
            ),
            'is_required' => array(
                'type' => self::TYPE_BOOL,
                'lang' => false,
                'validate' => 'isBool'
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
            // lang fields
            'field_title' => array(
                'type' => self::TYPE_HTML,
                'lang' => true,
                'validate' => 'isCleanHtml'
            ),
            'field_description' => array(
                'type' => self::TYPE_HTML,
                'lang' => true,
                'validate' => 'isCleanHtml'
            ),
        )
    );

    public static function getOptionsFields($id_shop, $id_lang, $where_option = false, $quantity = true)
    {
        $sql = new DbQuery;
        $sql->select('*');
        $sql->from(
            'everpsorderoptions_field',
            'ef'
        );
        $sql->leftJoin(
            'everpsorderoptions_field_lang',
            'efl',
            'ef.id_everpsorderoptions_field = efl.id_everpsorderoptions_field'
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
        if ($where_option) {
            $sql->where(
                'ef.type = "select"
                OR ef.type = "checkbox"
                OR ef.type = "radio"'
            );
        }
        if ($quantity) {
            $sql->where(
                'ef.manage_quantity = 1'
            );
            $sql->where(
                'ef.quantity > 0'
            );
            $sql->where(
                'ef.type != "select"'
            );
            $sql->where(
                'ef.type != "checkbox"'
            );
            $sql->where(
                'ef.type != "radio"'
            );
        }
        $sql->orderBy(
            'position ASC'
        );
        $all_fields = Db::getInstance()->executeS($sql);
        return $all_fields;
    }

    public static function getFieldsAndOptions($id_shop, $id_lang, $where_option = false, $quantity = true)
    {
        $sql = new DbQuery;
        $sql->select('ef.id_everpsorderoptions_field');
        $sql->from(
            'everpsorderoptions_field',
            'ef'
        );
        $sql->leftJoin(
            'everpsorderoptions_field_lang',
            'efl',
            'ef.id_everpsorderoptions_field = efl.id_everpsorderoptions_field'
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
        if ($where_option) {
            $sql->where(
                'ef.type = "select"
                OR ef.type = "checkbox"
                OR ef.type = "radio"'
            );
        }
        if ($quantity) {
            $sql->where(
                'ef.manage_quantity = 1'
            );
            $sql->where(
                'ef.quantity > 0'
            );
            $sql->where(
                'ef.type != "select"'
            );
            $sql->where(
                'ef.type != "checkbox"'
            );
            $sql->where(
                'ef.type != "radio"'
            );
        }
        $sql->orderBy(
            'position ASC'
        );
        $all_fields = Db::getInstance()->executeS($sql);
        $fields = [];
        foreach ($all_fields as $field_arr) {
            $field = new self(
                (int)$field_arr['id_everpsorderoptions_field'],
                (int)$id_lang,
                (int)$id_shop
            );
            $hasOptions = EverpsorderoptionsOption::fieldHasOptions(
                (int)$field->id,
                (int)Context::getContext()->shop->id,
                $quantity
            );
            if ($hasOptions > 0) {
                $field_options = EverpsorderoptionsOption::getFieldOptions(
                    (int)$field->id,
                    (int)Context::getContext()->shop->id,
                    (int)Context::getContext()->language->id,
                    $quantity
                );
                $field->has_options = true;
                $field->options = $field_options;
            } else {
                $field->has_options = false;
            }
            $fields[] = $field;
        }
        return $fields;
    }
}
