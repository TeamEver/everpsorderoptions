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

use Symfony\Component\Translation\TranslatorInterface;

class EverCheckoutStep extends AbstractCheckoutStep
{
    protected $module;
    protected $everdata;

    public function __construct(
        Context $context,
        TranslatorInterface $translator,
        Everpsorderoptions $module
    )
    {
        parent::__construct($context, $translator);
        $this->context = $context;
        $this->translator = $translator;
        $this->module = $module;
        $title = Configuration::get(
            'EVERPSOPTIONS_TITLE',
            (int) Context::getContext()->language->id
        );
        $this->setTitle(
            htmlspecialchars_decode($title)
        );
    }


    /**
     * Récupération des données à persister
     *
     * @return array
     */
    public function getDataToPersist()
    {
        return [
            'everdata' => $this->everdata,
            'evermessage' => Configuration::get(
                'EVERPSOPTIONS_MSG',
                (int) Context::getContext()->language->id
            )
        ];
    }

    /**
     * Restoration des données persistées
     *
     * @param array $data
     * @return $this|AbstractCheckoutStep
     */
    public function restorePersistedData(array $data)
    {
        foreach ($data as $key => $value) {
            $this->everdata = $data['everdata'];
        }

        return $this;
    }

    /**
     * Traitement de la requête ( ie = Variables Posts du checkout )
     * @param array $requestParameters
     * @return $this
     */
    public function handleRequest(array $requestParameters = array())
    {
        if (isset($requestParameters['submitCustomStep'])) {
            foreach ($requestParameters as $key => $value) {
                if ($this->orderFormStartsWith($key, 'everpsorderoptions')
                    || $this->orderFormStartsWith($key, 'everpsorderoptionsfield')
                ) {
                    $this->everdata[$key] = $value;
                }
            }
            $this->setComplete(true);
            $this->setNextStepAsCurrent();
        }

        return $this;
    }

    /**
     * Affichage de la step
     *
     * @param array $extraParams
     * @return string
     */
    public function render(array $extraParams = [])
    {
        $fields = EverpsorderoptionsField::getFieldsAndOptions(
            (int)Context::getContext()->shop->id,
            (int)Context::getContext()->language->id,
            false,
            false
        );
        foreach ($fields as $key => $value) {
            if ($value->has_options) {
                foreach ($value->options as $option_key => $option_value) {
                    if ((bool)$value->manage_quantity
                        && (int)$value->quantity <= 0
                    ) {
                        unset($value->options[$key]);
                    }
                }
            } else {
                if ((bool)$value->manage_quantity
                    && (int)$value->quantity <= 0
                ) {
                    unset($fields[$key]);
                }
            }
        }
        $defaultParams = array(
            'identifier' => 'everpsorderoptions',
            'position' => 3,
            'title' => $this->getTitle(),
            'step_is_complete' => (int) $this->isComplete(),
            'step_is_reachable' => (int) $this->isReachable(),
            'step_is_current' => (int) $this->isCurrent(),
            'fields' => $fields,
            'everdata' => $this->everdata,
            'evermessage' => Configuration::get(
                'EVERPSOPTIONS_MSG',
                (int) Context::getContext()->language->id
            )
        );

        $this->context->smarty->assign($defaultParams);
        return $this->module->display(
            _PS_MODULE_DIR_ . $this->module->name,
            'views/templates/hook/everCheckoutStep.tpl'
        );
    }

    public function orderFormStartsWith($haystack, $needle)
    {
        $needle = $needle.'_';
        $length = Tools::strlen($needle);

        if (Tools::substr($haystack, 0, $length) === $needle) {
            return true;
        } else {
            return false;
        }
    }
}
