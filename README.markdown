# fpPaymentAuthorizePlugin

It depends on fpPaymentPlugin

You have to enable "fpPaymentAuthorize" module

_settings.yml_

    all:
      .settings:
        enabled_modules:
          - fpPaymentAuthorize
    

You have to create fp_payment_authorize.yml file in to yours config folder and configure it.

_fp_payment_authorize.yml_

    all:
      form_hidden_fields:
        x_login: 'API Login'
        x_tran_key: 'Transaction Key'
        