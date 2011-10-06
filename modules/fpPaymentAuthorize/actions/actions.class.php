<?php

/**
 * Authorize actions.
 *
 * @package    fpPayment
 * @subpackage Authorize
 * @author     Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
class fpPaymentAuthorizeActions extends sfActions
{
  
  /**
   * Fist step choose payment
   *
   * @param sfWebRequest $request
   *
   * @return void
   */
  public function executeInfo(sfWebRequest $request)
  {
    $type = $request->getParameter('type');
    $formClass = sfConfig::get('fp_payment_authorize_form_class', 'fpPaymentAuthorizeRequestForm');
    $this->form = new $formClass();
    $this->form->setDefault('type', $type);
    if (sfRequest::POST == $request->getMethod()) {
      $this->form->bind($request->getParameter($this->form->getName()));
      if ($this->form->isValid()) {
        $values = $this->form->getValues();
        $values['x_exp_date'] = $values['exp_month'] . $values['exp_year'];
        unset($values['exp_month']);
        unset($values['exp_year']);
        $ipn = fpPaymentContext::getInstance()
          ->getAuthorize()
          ->doProcess($values)
          ->getIpn();
        if ($ipn->hasErrors()) {
          return $this->redirect('@fpPaymentPlugin_error?type=' . $type);
        } else {
          return $this->redirect('@fpPaymentPlugin_success?type=' . $type);
        }
      }
    }
  }
  
  /**
   * Error step
   *
   * @param sfWebRequest $request
   *
   * @return void
   */
  public function executeError(sfWebRequest $request)
  {
    
  }
  
}