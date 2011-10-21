<?php

/**
 * Authorize Context
 *
 * @package    fpPayment
 * @subpackage Authorize
 * @author		 Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
class fpPaymentAuthorizeContext extends fpPaymentMethodContext
{
  
  const NAME = 'Authorize';
  
  /**
   * Model
   *
   * @var fpPaymentAuthorize
   */
  protected $model;
  
  /**
   * Constructor
   *
   * @return void
   */
  public function __construct()
  {
    $this->getContext()->getDispatcher()->connect('fp_payment_order.after_create', array($this, 'afterOrderCreate'));
    $this->getContext()->getDispatcher()->connect('fp_payment_order.after_create',
      array(fpPaymentAuthorizeTable::getInstance(), 'afterOrderCreate'));
    $this->getContext()->getDispatcher()->connect('fp_payment.after_process', array($this, 'afterProcess'));
    parent::__construct();
  }
  
	/**
   * (non-PHPdoc)
   * @see fpPaymentMethodContext::renderInfoPage()
   */
  public function renderInfoPage(sfAction &$action, sfRequest $request) {
    $action->forward(sfConfig::get('fp_payment_authorize_page_info_module', 'fpPaymentAuthorize'),
                     sfConfig::get('fp_payment_authorize_page_info_action', 'info'));
  }
  
  /**
   * Set model
   *
   * @param fpPaymentAuthorize $model
   *
   * @return fpAuthorizeContext
   */
  public function setModel(fpPaymentAuthorize $model)
  {
    $this->model = $model;
    return $this;
  }
  
  /**
   * Get model
   *
   * @return fpPaymentAuthorize
   */
  public function getModel()
  {
    return $this->model;
  }
  
  /**
   * After order create
   *
   * @param sfEvent $event - Keys: user, user, values, context
   *
   * @return viod
   */
  public function afterOrderCreate(sfEvent $event)
  {
    $order = $event['context']->getOrderModel();
    $event['values']['x_customer_ip'] = $_SERVER['REMOTE_ADDR'];
    $event['values']['x_invoice_num'] = $order->getId();
    $event['values']['x_amount'] = $order->getSum();
    $order->setType(static::NAME);
    $order->setStatus(fpPaymentOrderStatusEnum::IN_PROCESS);
    $order->save();
  }
  
  /**
   * After procces
   *
   * @param sfEvent $event - Key: context
   *
   * @return void
   */
  public function afterProcess(sfEvent $event)
  {
    $ipn = $event['context']->getAuthorize()->getIpn();
    $event['context']->getAuthorize()->getModel()->addResponce(implode('|', $ipn->getResponse()));
    $order = $event['context']->getOrderModel();
    if ($ipn->hasErrors()) {
      $order->setStatus(fpPaymentOrderStatusEnum::FAIL);
    } else {
      $order->setStatus(fpPaymentOrderStatusEnum::SUCCESS);
    }
    $order->setType(static::NAME);
    $order->save();
  }
  
  /**
   * (non-PHPdoc)
   * @see fpPaymentMethodContext::renderSuccessPage()
   */
  public function renderSuccessPage(sfAction &$action, sfRequest $request) {
    
    if ($this->getIpn()->hasErrors()) {
      return $action->redirect('@fpPaymentPlugin_error?method=' . $request->getParameter('method'));
    }
    $action->forward(sfConfig::get('fp_payment_authorize_page_success_module', 'fpPaymentAuthorize'),
                     sfConfig::get('fp_payment_authorize_page_success_action', 'success'));
  }
  
  /**
   * (non-PHPdoc)
   * @see fpPaymentMethodContext::renderErrorPage()
   */
  public function renderErrorPage(sfAction &$action, sfRequest $request) {
    $action->forward(sfConfig::get('fp_payment_authorize_page_error_module', 'fpPaymentAuthorize'),
                     sfConfig::get('fp_payment_authorize_page_error_action', 'error'));
  }
  
}
