<?php
/**
 * TranslationExtension adds a translate function for twig templates in a slim framework application.
 *
 * TranslationExtension expects an instance of a translator class injected into the slim app and tries 
 * to call the "trans()" function on the translator class.
 *
 * Example usage:
 * {{ translate('male') }}
 * {{ _('male') }}
 * 
 * @author Daniel Kesberg <dkesberg@gmail.com>
 * @copyright 2014 Daniel Kesberg
 * @package Dkesberg\Slim\Twig\Extension
 * 
 * MIT LICENSE
 *
 * Permission is hereby granted, free of charge, to any person obtaining
 * a copy of this software and associated documentation files (the
 * "Software"), to deal in the Software without restriction, including
 * without limitation the rights to use, copy, modify, merge, publish,
 * distribute, sublicense, and/or sell copies of the Software, and to
 * permit persons to whom the Software is furnished to do so, subject to
 * the following conditions:
 *
 * The above copyright notice and this permission notice shall be
 * included in all copies or substantial portions of the Software.
 *
 * THE SOFTWARE IS PROVIDED "AS IS", WITHOUT WARRANTY OF ANY KIND,
 * EXPRESS OR IMPLIED, INCLUDING BUT NOT LIMITED TO THE WARRANTIES OF
 * MERCHANTABILITY, FITNESS FOR A PARTICULAR PURPOSE AND
 * NONINFRINGEMENT. IN NO EVENT SHALL THE AUTHORS OR COPYRIGHT HOLDERS BE
 * LIABLE FOR ANY CLAIM, DAMAGES OR OTHER LIABILITY, WHETHER IN AN ACTION
 * OF CONTRACT, TORT OR OTHERWISE, ARISING FROM, OUT OF OR IN CONNECTION
 * WITH THE SOFTWARE OR THE USE OR OTHER DEALINGS IN THE SOFTWARE.
 */
namespace Dkesberg\Slim\Twig\Extension;

use Slim\Slim;

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
      throw new \Exception('No translate method found in translator class.');
    }    
    return $slim->translator->trans($name);
  }
}
