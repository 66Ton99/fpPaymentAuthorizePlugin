<?php

/**
 * fpPaymentAuthorizePlugin configuration
 *
 * @package    fpPayment
 * @subpackage Authorize
 * @author 	   Ton Sharp <Forma-PRO@66ton99.org.ua>
 */
class fpPaymentAuthorizePluginConfiguration extends sfPluginConfiguration
{
  public static $reg = false;
  
  /**
   * (non-PHPdoc)
   * @see sfPluginConfiguration::setup()
   */
  public function setup()
  {
    if (!self::$reg) {
      self::$reg = true;
      $this->dispatcher->connect('context.load_factories', array($this, 'listenToContextLoadFactories'));
    }
  }
  
  public function listenToContextLoadFactories(sfEvent $event)
  {
    //$context = $event->getSubject();
    $configFiles = $this->configuration->getConfigPaths('config/fp_payment_authorize.yml');
    $config = sfDefineEnvironmentConfigHandler::getConfiguration($configFiles);
    
    foreach ($config as $name => $value) {
      sfConfig::set("fp_payment_authorize_{$name}", $value);  
    }

    fpPaymentContext::getInstance()->addPaymentMethod(array(fpPaymentAuthorizeContext::NAME => fpPaymentAuthorizeContext::NAME));
  }
}