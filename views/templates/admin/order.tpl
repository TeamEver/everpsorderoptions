{*
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
*}
<div class="panel everheader">
    <div class="panel-heading">
        <i class="icon icon-smile"></i> {l s='Ordered Options' mod='everpsorderoptions'}
    </div>
    <div class="panel-body">
        <div class="col-md-2">
            <img id="everlogo" src="{$everimg_dir|escape:'htmlall':'UTF-8'}/logo.png" style="max-width: 120px;">
        </div>
        <div class="col-md-10">
            <div class="table-responsive">
                <table id="everpsorderoptions" class="display responsive nowrap dataTable no-footer dtr-inline collapsed table">
                    <thead>
                        <tr>
                            <th>{l s='Field' mod='everpsprocatalog'}</th>
                            <th>{l s='Value' mod='everpsprocatalog'}</th>
                        </tr>
                    </thead>
                    <tbody>
                    {foreach from=$everoptions item=field}
                    <tr>
                        <td class="option_name">
                            {$field->field_title|escape:'htmlall':'UTF-8'}
                        </td>
                        <td class="option_value">
                            {$field->field_value|escape:'htmlall':'UTF-8'}
                        </td>
                    </tr>
                    {/foreach}
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
