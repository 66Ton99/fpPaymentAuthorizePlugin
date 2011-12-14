<?php

/**
 * fpPaymentAuthorizeIpn test case
 *
 * @package    fpPayment
 * @subpackage Base
 * @author     Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
class fpPaymentAuthorizeIpnTestCase extends sfBasePhpunitTestCase 
{
  
  protected $options = array(
    'x_login' => '54PB5egZ',
    'x_tran_key' => '48V258vr55AE8tcg',
    'x_card_num' => '370000000000002',
    'x_exp_date' => '1212',
    'x_card_code' => '123',
    'x_Description' => 'test',
    'x_invoice_num' => '1',
    'x_Amount' => '1',
  );
  
  /**
   * @test
   */
  public function construct_and_getData()
  {
    $obj = new fpPaymentAuthorizeIpn($this->options);
    $this->assertEquals($obj->getData(), array(
        'x_card_num' => '370000000000002',
        'x_exp_date' => '1212',
        'x_card_code' => '123',
        'x_address' => '',
        'x_zip' => '',
        'x_email' => '',
        'x_cust_id' => '0',
        'x_first_name' => '',
        'x_last_name' => '',
        'x_country' => '',
        'x_state' => '',
        'x_city' => '',
        'x_phone' => '',
        'x_customer_ip' => '',
        'x_Description' => 'test',
        'x_invoice_num' => '1',
        'x_Amount' => '1',
        'x_Method' => 'CC',
        'x_Type' => 'AUTH_CAPTURE',
        'x_Delim_Data' => 'true',
        'x_Delim_Char' => '|',
        'x_Relay_Response' => 'FALSE',
        'x_Version' => '3.1',
        'x_Email_Customer' => 'TRUE',
        'x_login' => '54PB5egZ',
        'x_tran_key' => '48V258vr55AE8tcg'
      ));
  }
  
	/**
   * @test
   */
  public function process()
  {
    sfConfig::set('fp_payment_authorize_form_url', 'test.authorize.net');
    $options = $this->options;
    $options['x_invoice_num'] = time();
    $stub = $this->getMock('fpPaymentAuthorizeIpn', array('getLoger'), array($options));

    $stub->expects($this->any())
         ->method('getLoger')
         ->will($this->returnValue(new fpPaymentTestNullObject()));
    $stub->process();
    if (true === $stub->hasErrors()) {
      $this->fail(print_r($stub->getErrors(), true));
    }
  }
}