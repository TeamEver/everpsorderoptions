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
require_once _PS_MODULE_DIR_.'everpsorderoptions/models/EverCheckoutStep.php';
require_once _PS_MODULE_DIR_ . 'everpsorderoptions/everpsorderoptions.php';

class OrderController extends OrderControllerCore
{

    protected function bootstrap()
    {
        $translator = $this->getTranslator();

        $session = $this->getCheckoutSession();
        
        $customCheckout = Module::getInstanceByName('everpsorderoptions');

        $this->checkoutProcess = new CheckoutProcess(
            $this->context,
            $session
        );

        $this->checkoutProcess
            ->addStep(new CheckoutPersonalInformationStep(
                $this->context,
                $translator,
                $this->makeLoginForm(),
                $this->makeCustomerForm()
            ))
            ->addStep(new CheckoutAddressesStep(
                $this->context,
                $translator,
                $this->makeAddressForm()
            ));

        if ((int)Configuration::get('EVERPSOPTIONS_POSITION') == 1) {
            $this->checkoutProcess
                ->addStep(new EverCheckoutStep(
                        $this->context,
                        $this->getTranslator(),
                        $customCheckout
                    )
                );
        }

        if (!$this->context->cart->isVirtualCart()) {
            $checkoutDeliveryStep = new CheckoutDeliveryStep(
                $this->context,
                $translator
            );

            $checkoutDeliveryStep
                ->setRecyclablePackAllowed((bool) Configuration::get('PS_RECYCLABLE_PACK'))
                ->setGiftAllowed((bool) Configuration::get('PS_GIFT_WRAPPING'))
                ->setIncludeTaxes(
                    !Product::getTaxCalculationMethod((int) $this->context->cart->id_customer)
                    && (int) Configuration::get('PS_TAX')
                )
                ->setDisplayTaxesLabel((Configuration::get('PS_TAX') && !Configuration::get('AEUC_LABEL_TAX_INC_EXC')))
                ->setGiftCost(
                    $this->context->cart->getGiftWrappingPrice(
                        $checkoutDeliveryStep->getIncludeTaxes()
                    )
                );

            $this->checkoutProcess->addStep($checkoutDeliveryStep);
        }

        if ((int)Configuration::get('EVERPSOPTIONS_POSITION') == 2) {
            $this->checkoutProcess
                ->addStep(new EverCheckoutStep(
                        $this->context,
                        $this->getTranslator(),
                        $customCheckout
                    )
                );
        }

        $this->checkoutProcess
            ->addStep(new CheckoutPaymentStep(
                $this->context,
                $translator,
                new PaymentOptionsFinder(),
                new ConditionsToApproveFinder(
                    $this->context,
                    $translator
                )
            ));

    }

}
