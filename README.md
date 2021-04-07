# Prestashop 1.7 module for options on order #

This module allows you to add steps in the Prestashop 1.7 checkout tunnel and to offer a configurable form.
[You can make a donation to support the development of free modules by clicking on this link](https://www.paypal.com/donate?hosted_button_id=3CM3XREMKTMSE)
[Module can be downloaded for free on our website](https://www.team-ever.com/prestashop-module-options-tunnel-de-commande/)

### Module configuration ###

To add a simple field, go to the "Order options" tab, then "Form fields". Click the + button to add a field.
Start by specifying a title for the field, a description that will display in the order tunnel below this field.
You can create the following types of fields:
- text
- textarea
- dropdown
- checkbox
- radio button
- number
- E-mail
- phone
- password
- Url
- date
- hour

### Manage of the quantities of options in stock ###

For each item, you can manage the available quantities. If you decide to manage the available quantities, then upon validation of the order, the stock of the option selected by the customer will be decremented. On cancellation, however, the stock will return to its measure, whether it is a number field (such as requesting a number of covers) or even a text field.

In the Prestashop checkout tunnel, the module will not show the form elements that are no longer in stock. This rule can be completely ignored by leaving the settings of the fields and options which concern stock management at "No". In this way, the stock can be managed on some fields as well, but not on others.


