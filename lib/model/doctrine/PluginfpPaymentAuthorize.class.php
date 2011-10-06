<?php

/**
 * PluginfpPaymentAuthorize
 * 
 * @package    fpPayment
 * @subpackage Authorize
 * @author     Ton Sharp <Forma-PRO@66ton99.org.ua>
 * @version    SVN: $Id: Builder.php 7490 2010-03-29 19:53:27Z jwage $
 */
abstract class PluginfpPaymentAuthorize extends BasefpPaymentAuthorize
{
  const NAME = 'Authorize';
  
  /**
   * Add responce to the log
   *
   * @param string $response
   *
   * @return fpPaymentAuthorize
   */
  public function addResponce($response)
  {
    $this->setResponse($response);
    $this->save();
    return $this;
  }
}