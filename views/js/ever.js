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
$(document).ready(function() {
    // Default settings
    var defaultType = $('#type').find('option:selected').val();
    if (defaultType == 'radio' || defaultType == 'select' || defaultType == 'checkbox') {
        $('#quantity').parent().parent().hide();
        $('#manage_quantity_on').parent().parent().parent().hide();
    } else {
        $('#quantity').parent().parent().show();
        $('#manage_quantity_on').parent().parent().parent().show();
    }
    // User actions
    $('label[for=manage_quantity_on]').click(function(){
        $('#quantity').parent().parent().slideDown();
    });
    $('label[for=manage_quantity_off]').click(function(){
        $('#quantity').parent().parent().slideUp();
    });
    $('#type').change(function() {
        var $option = $(this).find('option:selected');
        var value = $option.val();
        if (value == 'radio' || value == 'select' || value == 'checkbox') {
            $('#quantity').parent().parent().hide();
            $('#manage_quantity_on').parent().parent().parent().hide();
        } else {
            $('#quantity').parent().parent().show();
            $('#manage_quantity_on').parent().parent().parent().show();
        }
    });
});
