<?php

/**
 * PluginfpPaymentAuthorizeTable
 * 
 * @package    fpPayment
 * @subpackage Authorize
 * @author     Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
abstract class PluginfpPaymentAuthorizeTable extends Doctrine_Table
{
  
  /**
   * Model name
   *
   * @var string
   */
  const MODEL_NAME = 'fpPaymentAuthorize';
  
	/**
   * Return singleton
   *
   * @return fpPaymentAuthorizeTable
   */
  public static function getInstance() {
    return Doctrine_Core::getTable(static::MODEL_NAME);
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
    $values = $event['values'];
    $class = static::MODEL_NAME;
    $model = new $class();
    if (($order = $event['context']->getOrderModel()) && is_object($order)) {
      $model->setOrderId($order->getId());
    }
    if (!empty($values['cardtype'])) {
      $model->setCardtype($values['cardtype']);
    }
    $saveCardNumbers = sfConfig::get('fp_payment_authorize_save_card_numbers', 4);
    $model->setCardnumber(substr($values['x_card_num'], strlen($values['x_card_num']) - $saveCardNumbers, $saveCardNumbers));
    $model->save();
    $event['context']->getAuthorize()->setModel($model);
  }
}