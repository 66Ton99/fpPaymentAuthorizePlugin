<?php

/**
 * Authorize actions.
 *
 * @package    fpPayment
 * @subpackage Authorize
 * @author     Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
class fpPaymentAuthorizeActionsBase extends sfActions
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
    $method = $this->getContext()->getUser()->getAttribute('paymentMethod',
                                                           sfConfig::get('fp_payment_main_ns', 'fpPaymentNS'));
    $formClass = sfConfig::get('fp_payment_authorize_form_class', 'fpPaymentAuthorizeRequestForm');
    $form = new $formClass();
    if (sfRequest::POST == $request->getMethod()) {
      $form->bind($request->getParameter($form->getName()));
      if ($form->isValid()) {
        $values = $form->getValues();
        $values['x_exp_date'] = $values['exp_month'] . $values['exp_year'];
        unset($values['exp_month']);
        unset($values['exp_year']);
        $this->getContext()->getUser()->setAttribute('paymentValues',
                                                     $values,
                                                     sfConfig::get('fp_payment_main_ns', 'fpPaymentNS'));
        $this->redirect('@fpPaymentPlugin_orderReview');
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