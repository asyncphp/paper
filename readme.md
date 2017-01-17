# Paper

Hassle-free HTML to PDF conversion abstraction library.

## Installation

```
composer require asyncphp/paper
```

For best results, you should also install [Prince](https://www.princexml.com) and [WKHTMLtoDPF](http://wkhtmltopdf.org). To run the tests, you'll also need to install [diff-pdf](https://github.com/vslavik/diff-pdf). If you don't have the latter installed, you'll not be able to run the tests. If you don't have the former installed, the relevant tests will be skipped, and drivers unusable.

## Usage

You can use any of the drivers directly:

```php
use AsyncInterop\Loop;
use AsyncPHP\Paper\Driver\DomDriver;
use AsyncPHP\Paper\Driver\PrinceDriver;
use AsyncPHP\Paper\Driver\WebkitDriver;

$dom = new DomDriver();
$prince = new PrinceDriver($binary = "/path/to/prince", $tempPath = "/path/to/temp");
$webkit = new WebkitDriver($binary = "/path/to/wkhtmltopdf", $tempPath = "/path/to/temp");

Loop::execute(Amp\wrap(function() use ($dom) {
    yield $dom
        ->html($sample)
        ->size("A4")
        ->orientation("portrait")
        ->dpi(300)
        ->render();
}));
```

However, it's must easier to use the factory, to create pre-configured drivers:

```php
use AsyncPHP\Paper\Factory;

$config = [
    "driver" => "dom",

    "dom" => [
        "options" => [
            "fontDir" => __DIR__ . "/fonts",
            // https://github.com/dompdf/dompdf/blob/master/src/Options.php
        ],
    ],

    "prince" => [
        "binary" => "/opt/prince/bin/prince",
        "tempPath" => __DIR__,
        "options" => [
            "--no-compress",
            "--http-timeout" => 10,
            // https://www.princexml.com/doc/command-line/#command-line
        ],
    ],

    "webkit" => [
        "binary" => "/usr/local/bin/wkhtmltopdf",
        "tempPath" => __DIR__,
        "options" => [
            "--grayscale",
            "--javascript-delay" => 500,
            // http://wkhtmltopdf.org/usage/wkhtmltopdf.txt
        ],
    ],
];

$factory = new Factory();
$driver = $factory->createDriver($config);

yield $driver->html("hello world")->render();
```

Paper takes an async-first approach. Operations, like rendering PDF files, are particularly suited to parallel processing architecture. You may be stuck rending PDF files in a synchronous architecture, in which case you can use the `SyncDriver` decorator:

```php
$driver = new SyncDriver(new DomDriver());
$driver->html("hello world")->render();

// ...or with the factory

$factory = new Factory();

$driver = $factory->createDriver([
    "driver" => "dom",
    "sync" => true,
]);

$driver->html("hello world")->render();
```

## Drivers

Here's a list of the drivers to currently support:

### [DOMPDF](http://dompdf.github.io)

* Requires command-line utilities: **no**
* Supports modern CSS: **no**
* Supports modern JS: **no**
* Produces vector files: **yes**
* Open + free: **yes**

### [WKHTMLtoDPF](http://wkhtmltopdf.org)

* Requires command-line utilities: **yes**
* Supports modern CSS: **yes**
* Supports modern JS: **yes**
* Produces vector files: **yes**
* Open + free: **yes**

### [Prince](https://www.princexml.com)

* Requires command-line utilities: **yes**
* Supports modern CSS: **yes**
* Supports modern JS: **yes**
* Produces vector files: **yes**
* Open + free: **no**

## Roadmap

* Setters for default margin
* Setters for header HTML
* Setters for footer HTML
* More drivers (especially [DocRaptor](https://docraptor.com) – a SaaS version of Prince)

## Versioning

This library follows [Semver](http://semver.org). According to Semver, you will be able to upgrade to any minor or patch version of this library without any breaking changes to the public API. Semver also requires that we clearly define the public API for this library.

All methods, with `public` visibility, are part of the public API. All other methods are not part of the public API. Where possible, we'll try to keep `protected` methods backwards-compatible in minor/patch versions, but if you're overriding methods then please test your work before upgrading.
