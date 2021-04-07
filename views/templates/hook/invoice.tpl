{*
* Project : everpsorderoptions
* @author Team EVER
* @copyright Team EVER
* @license   Tous droits réservés / Le droit d'auteur s'applique (All rights reserved / French copyright law applies)
* @link https://www.team-ever.com
*}
<div class="panel everheader">
    <div class="panel-body">
        <div class="col-md-12">
            <div class="table-responsive">
                <table>
                    <tr class="center small bold center">
                        <td>{l s='Ordered Options' mod='everpsorderoptions'}</td>
                    </tr>
                </table>
                <table id="everpsorderoptions" class="display responsive nowrap dataTable no-footer dtr-inline collapsed table">
                    <thead>
                        <tr class="center small grey bold center">
                            <th>{l s='Field' mod='everpsorderoptions'}</th>
                            <th>{l s='Value' mod='everpsorderoptions'}</th>
                        </tr>
                    </thead>
                    <tbody>
                    {foreach from=$everoptions item=field}
                    <tr>
                        <td class="option_name center small">
                            {$field->field_title|escape:'htmlall':'UTF-8'}
                        </td>
                        <td class="option_value center small">
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
