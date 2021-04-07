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

require_once _PS_MODULE_DIR_.'everpsorderoptions/models/EverpsorderoptionsField.php';
require_once _PS_MODULE_DIR_.'everpsorderoptions/models/EverpsorderoptionsOption.php';

class AdminEverPsOptionsOptionController extends ModuleAdminController
{
    private $html;

    public function __construct()
    {
        $this->bootstrap = true;
        $this->lang = true;
        $this->table = 'everpsorderoptions_option';
        $this->className = 'EverpsorderoptionsOption';
        $this->context = Context::getContext();
        $this->identifier = "id_everpsorderoptions_option";
        $this->module_name = 'everpsorderoptions';
        $this->isSeven = Tools::version_compare(_PS_VERSION_, '1.7', '>=') ? true : false;

        $this->context->smarty->assign(array(
            'everpsorderoptions_dir' => _MODULE_DIR_ . '/everpsorderoptions/'
        ));

        $this->bulk_actions = array(
            'delete' => array(
                'text' => $this->l('Delete selected'),
                'confirm' => $this->l('Delete selected items?')
            ),
        );

        $this->_select = 'cl.option_title AS cfg_title, fl.field_title AS field_title';

        $this->_join =
            'LEFT JOIN `'._DB_PREFIX_.'everpsorderoptions_option_lang` cl
                ON (
                    cl.`id_everpsorderoptions_option` = a.`id_everpsorderoptions_option`
                    AND cl.`id_lang` = '.(int)$this->context->language->id.'
                )
            LEFT JOIN `'._DB_PREFIX_.'everpsorderoptions_field_lang` fl
                ON (
                    fl.`id_everpsorderoptions_field` = a.`id_field`
                    AND fl.`id_lang` = '.(int)$this->context->language->id.'
                )';

        $this->_where = 'AND a.id_shop ='.(int)$this->context->shop->id;

        $this->fields_list = array(
            'id_everpsorderoptions_option' => array(
                'title' => $this->l('ID'),
                'align' => 'left',
                'width' => 'auto'
            ),
            'field_title' => array(
                'title' => $this->l('Field title'),
                'align' => 'left',
                'width' => 'auto',
                'havingFilter' => true,
                'filter_key' => 'fl!field_title'
            ),
            'cfg_title' => array(
                'title' => $this->l('Option title'),
                'align' => 'left',
                'width' => 'auto',
                'havingFilter' => true,
                'filter_key' => 'cl!field_title'
            ),
            'manage_quantity' => array(
                'title' => $this->l('Stock management'),
                'type' => 'bool',
                'active' => 'manage_quantity',
                'orderby' => false,
                'class' => 'fixed-width-sm'
            ),
            'quantity' => array(
                'title' => $this->l('Quantity'),
                'align' => 'left',
                'width' => 'auto'
            ),
            'position' => array(
                'title' => $this->l('Position'),
                'align' => 'left',
                'width' => 'auto'
            ),
            'active' => array(
                'title' => $this->l('Status'),
                'type' => 'bool',
                'active' => 'status',
                'orderby' => false,
                'class' => 'fixed-width-sm'
            ),
        );

        $this->colorOnBackground = true;

        parent::__construct();
    }

    public function setMedia($isNewTheme = false)
    {
        parent::setMedia($isNewTheme);

        $this->addCSS(_PS_MODULE_DIR_.'everpsorderoptions/views/css/ever.css');
    }

    protected function l($string, $class = null, $addslashes = false, $htmlentities = true)
    {
        if ($this->isSeven) {
            return Context::getContext()->getTranslator()->trans($string);
        }

        return parent::l($string, $class, $addslashes, $htmlentities);
    }

    /**
     * Gestion de la toolbar
     */
    public function initPageHeaderToolbar()
    {
        //Bouton d'ajout
        $this->page_header_toolbar_btn['new'] = array(
            'href' => self::$currentIndex . '&add' . $this->table . '&token=' . $this->token,
            'desc' => $this->module->l('Add new element'),
            'icon' => 'process-icon-new'
        );
        parent::initPageHeaderToolbar();
    }

    public function renderList()
    {
        $this->html = '';

        $this->addRowAction('edit');
        $this->addRowAction('delete');

        $this->toolbar_title = $this->l('Options form options');
        if (Tools::isSubmit('status'.$this->table)) {
            $db = Db::getInstance();
            if ($id_everpsorderoptions_option = (int)Tools::getValue($this->identifier)) {
                $update = $db->execute(
                    'UPDATE `'._DB_PREFIX_.'everpsorderoptions_option`
                    SET `active` = (1 - `active`)
                    WHERE `id_everpsorderoptions_option` = '.(int)$id_everpsorderoptions_option.' LIMIT 1'
                );
            }
            if (isset($update) && $update) {
                $this->redirect_after = self::$currentIndex.'&conf=5&token='.$this->token;
            } else {
                $this->errors[] = $this->l('An error occurred while updating the status.');
            }
        }

        $lists = parent::renderList();

        $this->html .= $this->context->smarty->fetch(
            _PS_MODULE_DIR_ . '/everpsorderoptions/views/templates/admin/header.tpl'
        );
        $blog_instance = Module::getInstanceByName($this->module_name);
        if ($blog_instance->checkLatestEverModuleVersion($this->module_name, $blog_instance->version)) {
            $this->html .= $this->context->smarty->fetch(
                _PS_MODULE_DIR_
                .'/'
                .$this->module_name
                .'/views/templates/admin/upgrade.tpl'
            );
        }
        if (count($this->errors)) {
            foreach ($this->errors as $error) {
                $this->html .= Tools::displayError($error);
            }
        }
        $this->html .= $lists;
        $this->html .= $this->context->smarty->fetch(
            _PS_MODULE_DIR_ . '/everpsorderoptions/views/templates/admin/footer.tpl'
        );

        return $this->html;
    }

    public function renderForm()
    {
        if (Context::getContext()->shop->getContext() != Shop::CONTEXT_SHOP && Shop::isFeatureActive()) {
            $this->errors[] = $this->l('You have to select a shop before creating or editing new elements.');
        }

        $formFields = EverpsorderoptionsField::getOptionsFields(
            (int)$this->context->shop->id,
            (int)$this->context->language->id,
            true,
            false
        );

        if (!$formFields) {
            $this->errors[] = $this->l('You have to create at least one dropdown field.');
        }

        if (count($this->errors)) {
            return false;
        }

        $this->fields_form = array(
            'submit' => array(
                'name' => 'save',
                'title' => $this->l('Save'),
                'class' => 'button pull-right'
            ),
            'buttons' => array(
                'save-and-stay' => array(
                    'title' => $this->l('Save and stay'),
                    'name' => 'submitAdd'.$this->table.'AndStay',
                    'type' => 'submit',
                    'class' => 'btn btn-default pull-right',
                    'icon' => 'process-icon-save'
                ),
            ),
            'input' => array(
                array(
                    'type' => 'text',
                    'label' => $this->l('Option title'),
                    'desc' => $this->l('Will be shown on front-office'),
                    'hint' => $this->l('Fully required'),
                    'required' => true,
                    'name' => 'option_title',
                    'lang' => true
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Option value'),
                    'desc' => $this->l('Will be used as default value'),
                    'hint' => $this->l('Fully required'),
                    'required' => true,
                    'name' => 'option_value',
                    'lang' => true
                ),
                array(
                    'type' => 'select',
                    'label' => 'Form field',
                    'desc' => 'Please choose field form',
                    'hint' => 'Fully required',
                    'name' => 'id_field',
                    'identifier' => 'name',
                    'required' => true,
                    'options' => array(
                        'query' => $formFields,
                        'id' => 'id_everpsorderoptions_field',
                        'name' => 'field_title',
                    ),
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Manage quantity'),
                    'desc' => $this->l('Manage field quantity in stock'),
                    'hint' => $this->l('Set to "No" to not manage field stock'),
                    'name' => 'manage_quantity',
                    'bool' => true,
                    'lang' => false,
                    'values' => array(
                        array(
                            'id'    => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Use stock')
                        ),
                        array(
                            'id'    => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Don\'t use stock')
                        )
                    )
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Position'),
                    'desc' => $this->l('Field position'),
                    'hint' => $this->l('Will set option position on form'),
                    'required' => false,
                    'name' => 'position',
                    'lang' => false
                ),
                array(
                    'type' => 'text',
                    'label' => $this->l('Quantity'),
                    'desc' => $this->l('If stock equals zero, field will be disabled'),
                    'hint' => $this->l('Option will be removed if it has no quantity'),
                    'required' => true,
                    'name' => 'quantity',
                    'lang' => false
                ),
                array(
                    'type' => 'switch',
                    'label' => $this->l('Active'),
                    'name' => 'active',
                    'bool' => true,
                    'lang' => false,
                    'values' => array(
                        array(
                            'id'    => 'active_on',
                            'value' => 1,
                            'label' => $this->l('Activate')
                        ),
                        array(
                            'id'    => 'active_off',
                            'value' => 0,
                            'label' => $this->l('Desactivate')
                        )
                    )
                )
            )
        );
        $form = $this->context->smarty->fetch(
            _PS_MODULE_DIR_ . '/everpsorderoptions/views/templates/admin/header.tpl'
        );
        $form .= parent::renderForm();
        $form .= $this->context->smarty->fetch(
            _PS_MODULE_DIR_ . '/everpsorderoptions/views/templates/admin/footer.tpl'
        );
        return $form;
    }

    public function postProcess()
    {
        if (Tools::isSubmit('save') || Tools::isSubmit('submitAdd'.$this->table.'AndStay')) {
            if (Tools::getValue('id_everpsorderoptions_option')) {
                $formOption = new EverpsorderoptionsOption(
                    (int)Tools::getValue('id_everpsorderoptions_option')
                );
            } else {
                $formOption = new EverpsorderoptionsOption();
            }
            foreach (Language::getLanguages(false) as $language) {
                if (!Tools::getIsset('option_title_'.$language['id_lang'])
                    || !Validate::isGenericName(
                        Tools::getValue(
                            'option_title_'.$language['id_lang']
                        )
                    )
                ) {
                    $this->errors[] = $this->l('Title is not valid for lang ').$language['id_lang'];
                } else {
                    $formOption->option_title[$language['id_lang']] = Tools::getValue(
                        'option_title_'.$language['id_lang']
                    );
                }
                if (Tools::getValue('option_value_'.$language['id_lang'])
                    && !Validate::isCleanHtml(
                        Tools::getValue(
                            'option_value_'.$language['id_lang']
                        )
                    )
                ) {
                    $this->errors[] = $this->l('Value is not valid for lang ').$language['id_lang'];
                } else {
                    $formOption->option_value[$language['id_lang']] = Tools::getValue(
                        'option_value_'.$language['id_lang']
                    );
                }
            }

            if (!Tools::getIsset('id_field')
                || !Validate::isUnsignedInt(Tools::getValue('id_field'))
            ) {
                 $this->errors[] = $this->l('Form field is invalid');
            }
            if (Tools::getValue('manage_quantity')
                && !Validate::isBool(Tools::getValue('manage_quantity'))
            ) {
                 $this->errors[] = $this->l('Manage quantity is invalid');
            }
            if (Tools::getValue('quantity')
                && !Validate::isUnsignedInt(Tools::getValue('quantity'))
            ) {
                 $this->errors[] = $this->l('Quantity is invalid');
            }
            if (Tools::getValue('position')
                && !Validate::isUnsignedInt(Tools::getValue('position'))
            ) {
                 $this->errors[] = $this->l('Position is invalid');
            }
            if (Tools::getValue('active')
                && !Validate::isBool(Tools::getValue('active'))
            ) {
                 $this->errors[] = $this->l('Active is invalid');
            }
            $formOption->id_field = (int)Tools::getValue('id_field');
            $formOption->checked = (int)Tools::getValue('checked');
            $formOption->id_shop = (int)$this->context->shop->id;
            $formOption->quantity = (int)Tools::getValue('quantity');
            $formOption->position = (int)Tools::getValue('position');
            $formOption->manage_quantity = (bool)Tools::getValue('manage_quantity');
            $formOption->active = Tools::getValue('active');

            if (!count($this->errors)) {
                if ($formOption->save()) {
                    Tools::clearSmartyCache();
                    if (Tools::isSubmit('save')) {
                        Tools::redirectAdmin(self::$currentIndex.'&conf=4&token='.$this->token);
                    }
                }
            }
        }

        if (Tools::isSubmit('deleteeverpsorderoptions_option')) {
            $everObj = new EverpsorderoptionsOption(
                (int)Tools::getValue('id_everpsorderoptions_option')
            );

            if (!$everObj->delete()) {
                $this->errors[] = $this->l('An error has occurred : Can\'t delete the current object');
            }
        }

        if (Tools::isSubmit('submitBulkdeleteever_contacts')) {
            $this->processBulkDelete();
        }
        return parent::postProcess();
    }

    protected function processBulkDelete()
    {
        foreach (Tools::getValue($this->table.'Box') as $idEverObj) {
            $everObj = new EverpsorderoptionsOption((int)$idEverObj);
            if (!$everObj->delete()) {
                $this->errors[] = $this->l('An error has occurred: Can\'t delete the current object');
            }
        }
    }

    protected function processBulkDisable()
    {
        foreach (Tools::getValue($this->table.'Box') as $idEverObj) {
            $everObj = new EverpsorderoptionsOption((int)$idEverObj);
            if ($everObj->active) {
                $everObj->active = false;
            }
            if (!$everObj->save()) {
                $this->errors[] = $this->l('An error has occurred: Can\'t delete the current object');
            }
        }
    }

    protected function processBulkEnable()
    {
        foreach (Tools::getValue($this->table.'Box') as $idEverObj) {
            $everObj = new EverpsorderoptionsOption((int)$idEverObj);
            if (!$everObj->active) {
                $everObj->active = true;
            }
            if (!$everObj->save()) {
                $this->errors[] = $this->l('An error has occurred: Can\'t delete the current object');
            }
        }
    }

    protected function displayError($message, $description = false)
    {
        /**
         * Set error message and description for the template.
         */
        array_push($this->errors, $this->module->l($message), $description);

        return $this->setTemplate('error.tpl');
    }
}
