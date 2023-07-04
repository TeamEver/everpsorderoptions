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

$sql = [];

// Form fields
$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'everpsorderoptions_field` (
    `id_everpsorderoptions_field` int(11) NOT NULL AUTO_INCREMENT,
    `id_shop` int(10) unsigned DEFAULT 1,
    `type` varchar(255) NOT NULL,
    `is_required` int(10) DEFAULT NULL,
    `manage_quantity` int(10) DEFAULT NULL,
    `quantity` int(10) DEFAULT NULL,
    `position` int(10) DEFAULT NULL,
    `active` int(10) DEFAULT NULL,
    PRIMARY KEY (`id_everpsorderoptions_field`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'everpsorderoptions_field_lang` (
    `id_everpsorderoptions_field` int(11) NOT NULL AUTO_INCREMENT,
    `id_lang` int(10) unsigned NOT NULL,
    `field_title` varchar(255) NOT NULL,
    `field_description` text NOT NULL,
    PRIMARY KEY (`id_everpsorderoptions_field`, `id_lang`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'everpsorderoptions_option` (
    `id_everpsorderoptions_option` int(11) NOT NULL AUTO_INCREMENT,
    `id_shop` int(10) unsigned DEFAULT 1,
    `id_field` int(10) unsigned NOT NULL,
    `manage_quantity` int(10) DEFAULT NULL,
    `quantity` int(10) DEFAULT NULL,
    `position` int(10) DEFAULT NULL,
    `active` int(10) DEFAULT NULL,
    PRIMARY KEY (`id_everpsorderoptions_option`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

$sql[] = 'CREATE TABLE IF NOT EXISTS `' . _DB_PREFIX_ . 'everpsorderoptions_option_lang` (
    `id_everpsorderoptions_option` int(11) NOT NULL AUTO_INCREMENT,
    `id_lang` int(10) unsigned NOT NULL,
    `option_title` varchar(255) NOT NULL,
    `option_value` varchar(255) NOT NULL,
    PRIMARY KEY (`id_everpsorderoptions_option`, `id_lang`)
) ENGINE=' . _MYSQL_ENGINE_ . ' DEFAULT CHARSET=utf8;';

foreach ($sql as $query) {
    if (Db::getInstance()->execute($query) == false) {
        return false;
    }
}
