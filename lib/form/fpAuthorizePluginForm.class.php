<?php

/**
 * Authorize Plugin Form
 * 
 * @package    fpPayment
 * @subpackage Authorize
 * @author     Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
class fpPaymentAuthorizeRequestForm extends BaseForm
{
  
  /**
   * (non-PHPdoc)
   * @see sfForm::setup()
   */
  public function setup()
  {
    $cardTypes = array(
      'Visa' => 'Visa', 
      'Mastercard' => 'Mastercard', 
      'American Express' => 'American Express',
      'Discover' => 'Discover'
    );
    $years = array();
    foreach (range(date('Y'), date('Y')+25) as $year) {
      $years[$year] = $year;
    }
    $months = array();
    foreach (range(1, 12) as $month) {
      $months[$month] = $month;
    }
    
    $this->setWidgets(array(
//      'cardtype'    => new sfWidgetFormSelect(array('label' => 'Card type', 'choices' => $cardTypes)),
      'x_card_num' => new sfWidgetFormInput(array('label' => 'Card number'), array('maxlength' => 16)),
      'exp_year'    => new sfWidgetFormSelect(array('label' => 'Expiration year', 'choices' => $years)),
      'exp_month'    => new sfWidgetFormSelect(array('label' => 'Expiration month', 'choices' => $months)),
      'x_card_code'         => new sfWidgetFormInput(array('label' => 'CVV'), array('maxlength' => 4)),
    ));
    
    $this->setValidators(array(
//      'cardtype' => new sfValidatorChoice(array('choices' => array_keys($cardTypes))),
      'x_card_num' => new sfValidatorRegex(array('min_length' => 13, 'max_length' => 16, 'pattern' => '/^\d{1,}$/')),
      'exp_year' => new sfValidatorChoice(array('choices' => array_keys($years))),
      'exp_month' => new sfValidatorChoice(array('choices' => array_keys($months))),
      'x_card_code' => new sfValidatorRegex(array('max_length' => 4, 'pattern' => '/^\d{1,}$/')),
    ));
    
    $widgetSchema = $this->getWidgetSchema();
    $widgetSchema->setNameFormat(get_class($this) . '[' . $widgetSchema->getNameFormat() . ']');
  }
}