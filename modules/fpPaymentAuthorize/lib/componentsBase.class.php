<?php

/**
 * Authorize components
 *
 * @package    fpPayment
 * @subpackage Authorize
 * @author     Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
class fpPaymentAuthorizeComponentsBase extends sfComponents
{
  
  /**
   * Form
   *
   * @return void
   */
  public function executeForm()
  {
    $formClass = sfConfig::get('fp_payment_authorize_form_class', 'fpPaymentAuthorizeRequestForm');
    $this->form = new $formClass();
    if (sfRequest::POST == $this->getRequest()->getMethod()) {
      $this->form->bind($this->getRequest()->getParameter($this->form->getName()));
      $this->form->isValid();
    }
  }
}
