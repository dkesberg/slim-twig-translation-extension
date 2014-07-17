<?php

namespace Dkesberg\Slim\Twig\Extension;

use Slim\Slim;

/**
 * Class TranslatorExtension 
 * TranslatorExtension adds a translate function for twig templates in a slim framework application.
 * 
 * TranslatorExtension expects an instance of a translator class injected into the slim app and tries to call
 * 
 * Example usage:
 * {{ translate('male') }} 
 * {{ _('male') }}
 * 
 * @package     Application\Helper\Twig
 */
class TranslationExtension extends \Twig_Extension
{
  public function getName()
  {
    return 'translate';
  }

  public function getFunctions()
  {
    return array(
      new \Twig_SimpleFunction('translate', array($this, 'translate')),
      new \Twig_SimpleFunction('_', array($this, 'translate'))
    );
  }

  public function translate($name, $appName = 'default')
  { 
    $slim = Slim::getInstance($appName);
    if (!$slim->translator) {
      throw new \Exception('No translator class found.');
    }
    if (!method_exists($slim->translator, 'trans')) {
      throw new \Exception('No trans method found in translator class.');
    }    
    return $slim->translator->trans($name);
  }
}
