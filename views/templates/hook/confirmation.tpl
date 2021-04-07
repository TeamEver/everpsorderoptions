{*
* Project : everpsorderoptions
* @author Team EVER
* @copyright Team EVER
* @license   Tous droits réservés / Le droit d'auteur s'applique (All rights reserved / French copyright law applies)
* @link https://www.team-ever.com
*}
<div class="panel everheader">
    <div class="panel-heading">
        <h3> {l s='Ordered Options' mod='everpsorderoptions'}</h3>
    </div>
    <div class="panel-body">
        <div class="col-md-12">
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
