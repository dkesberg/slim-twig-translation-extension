# Slim Twig Translation Extension

This repository provides a twig extension class for the twig view parser. 
The class adds a translate helper function  for the use in twig templates.
The translator function tries to call the trans() function of an 
Illuminate\Translation\Translator object in the slim container. 

## How to install

#### Using [Composer](http://getcomposer.org/)

Create a composer.json file in your project root:
    
```json
{
    "require": {
        "dkesberg/slim-twig-translation-extension": "v0.1.0"
    }
}
```

Then run the following composer command:

```bash
$ php composer.phar install
```

## How to use

### Slim

Set up your twig views as described in the [SlimViews Repository](https://github.com/codeguy/Slim-Views).
Add the extension to your parser extensions.

```php
$view->parserExtensions = array(
    new \Dkesberg\Slim\Twig\Extension\TranslationExtension(),
);
```

### Twig template

In your twig template you would write:

```
  {{ translate('mails.salutation.male') }}
```
  
You can also use the shorthand:

```
  {{ _('mails.salutation.male') }}
```

### Adding Illuminate/Translation/Translator to slim

Simple injection:

```php
use Illuminate\Translation\Translator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;

$translator = new Translator(new FileLoader(new Filesystem(), __DIR__ . '/lang'), 'en');
$translator->setFallback('en');
$app->translator = $translator;
```

Singleton ressource:

```php
use Illuminate\Translation\Translator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;

$app->container->singleton('translator', function() {
  return new Translator(new FileLoader(new Filesystem(), __DIR__ . '/lang'), 'en');
});
$app->translator->setFallback('en');
```

Using slim hooks and singleton:

```php
use Illuminate\Translation\Translator;
use Illuminate\Filesystem\Filesystem;
use Illuminate\Translation\FileLoader;

// detect language and set translator
$app->hook('slim.before', function() use ($app) {
  $env = $app->environment();
  
  // Extract locale
  $locale = Locale::acceptFromHttp($env['HTTP_ACCEPT_LANGUAGE']);
  $app->locale = substr($locale,0,2);

  // Set translator instance
  $app->container->singleton('translator', function($app) {
    return new Translator(new FileLoader(new Filesystem(), __DIR__ . '/lang'), $app->locale);
  });
  $app->translator->setFallback('en');
});
```

### Language files

I am wrapping the Illuminate\Translation\Translator. The language files for the translator use the same structure used in the Laravel framework. For more information see also: http://laravel.com/docs/4.2/localization

In the lang directory there should be a subdirectory for each language.
```
/lang
       /en
            mails.php
            validation.php
       /de
            mails.php
            validation.php
```

The language files simply return an array of keyed strings.

```php
<?php

return array(
  'salutation' => array(
    'male'    => 'Dear Mr.',
    'female'  => 'Dear Mrs.',
    'unknown' => 'Dear Sir or Madam'
  )
)
```

The first part of the string passed to the translate function is the name of the language file, the second part is the key you want to retrieve. The second part uses the dot notation for multidimensional arrays.

```php
{{ _('mails.salutation.male') }}
```
