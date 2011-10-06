<?php

/**
 * Authorize IPN class
 *
 * @package    fpPayment
 * @subpackage Authorize
 * @author     Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
class fpPaymentAuthorizeIpn extends fpPaymentIpnBase
{
  
  protected $url = '';
  protected $messages = array();
    
  protected $formFields = array(
    // card info:
    'credit_card' => '',
    'exp_date' => '', // Credit card year
    'cvv' => '', // Security code
    // user info:
    'x_address' => '',
    'x_zip' => '',
    'x_email' => '',
    'x_cust_id' => 0,
    'x_first_name' => '',
    'x_last_name' => '',
    'x_country' => '',
    'x_state' => '',
    'x_city' => '',
    'x_phone' => '',
    'x_customer_ip' => '',
    // product info:,
    'x_Description' => '',
    'x_invoice_num' => 0,
    'x_Amount' => 0,
  );
  
  protected $formHiddenFields = array(
    'x_Method' => 'CC',
    'x_Type' => 'AUTH_CAPTURE',
    'x_Delim_Data' => 'true',
    'x_Delim_Char' => '|',
    'x_Relay_Response' => 'FALSE',
    'x_Version' => '3.1',
    'x_Email_Customer' => 'TRUE',
//    x_Test_Request' => 'TRUE',
    // required:,
    'x_login' => '',
    'x_tran_key' => '',
  );
  
  /**
   * Constructor
   *
   * @return void
   */
  public function __construct()
  {
    parent::__construct();
    $this->url = 'https://' . sfConfig::get('fp_payment_authorize_form_url', 'secure.authorize.net') . 
                    sfConfig::get('fp_payment_authorize_form_url_path', '/gateway/transact.dll');
    $data = sfConfig::get('fp_payment_authorize_form_fields', $this->formFields);
    $data = array_merge($data, sfConfig::get('fp_payment_authorize_form_hidden_fields', $this->formHiddenFields));
    parent::setData($data);
  }
  
  /**
   * Enter description here ...
   *
   * @param string $key
   * @param unknown_type $val
   *
   * @return AuthorizeIpn
   */
  public function setDataParam($key, $val)
  {
    $this->data[$key] = $val;
    return $this;
  }
  
  /**
   * Responce delimeter
   *
   * @return string
   */
  protected function getDelimeter()
  {
    return empty($this->data['x_Delim_Char'])?'|':$this->data['x_Delim_Char'];
  }
  
  /**
   * Process
   * 
   * @param string $url
   *
   * @return AuthorizeIpn
   */
  public function process()
  {
    $this->curl = new fpPaymentCurl($this->url);
    fpPaymentContext::getInstance()
      ->getAuthorize()
      ->getLoger()
      ->addArray($this->data, 'Send data to ' . $this->url . $this->curl->prepareRequest($this->data));
    $response = $this->curl->sendPostRequest($this->data);
    
    if ($this->curl->hasErrors()) {
      $response .= $this->errors[] = 'Curl errors: ' . implode("\n", $this->curl->getErrors());
    }
    
    fpPaymentContext::getInstance()->getAuthorize()->getLoger()->add($response);
    
    //parse response
    $this->response = explode($this->getDelimeter(), $response);

    switch ($this->response['0']) {
      case 1:
        //approved
//        $this->errors[] = $this->response[3];
        break;
        
      case 2:
      case 3:
        //error - get error text
        $this->errors[] = $this->response[3];
        break;
        
      default:
        if (!empty($this->response[3])) {
          $this->errors[] = $this->response[3];
        } else {
          $this->errors[] = 'Internal server error';
        }
        break;
    }
    
    return $this;
  }
}