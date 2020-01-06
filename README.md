TypesetshBundle
===============

This bundle is simple wrapper for the [typeset.sh](https://typeset.sh/) pdf engine.

Typeset.sh is a css/html layout engine that allows to render as PDF. 

## Installation

Open a command console, enter your project directory and execute the following command to download the latest stable version of this bundle:

```
composer require typesetsh/pdf-bundle
```


### Enable the Bundle

Then, enable the bundle by adding it to the list of registered bundles 
in `config/bundles.php` file of your project:

```php
return [
    // [...]
    \Typesetsh\PdfBundle\TypesetshPdfBundle => ['all' => true],
];
```

## Usage

Render a view as pdf. You can use the abstract controller to render a twig view as pdf.

```php
<?php

namespace App\Controller;

use Symfony\Component\HttpFoundation\HeaderUtils;
use Typesetsh\PdfBundle;


class TestController extends PdfBundle\Controller\AbstractController
{
    public function printOrder()
    {
        $order = new \stdClass();

        return $this->pdf(
            'pdf/invoice.html.twig', 
            ['order' => $order,], 
            'test.pdf', 
            HeaderUtils::DISPOSITION_INLINE
        );
    }
}
```


Alternative you can also use dependency injection to retrieve the pdf generation and do with it as you wish.

```php
class PrintService
{
    /** @var PdfBundle\PdfGenerator */
    private $pdfGenerator;

    public function __construct(PdfBundle\PdfGenerator $pdfGenerator)
    {
        $this->pdfGenerator = $pdfGenerator;
    }

    public function execute()
    {
        $html = '...';

        $result = $this->pdfGenerator->render($html);
        $result->toFile('./somefile.pdf');
        $result->asString();
        
    }
}
```


## Configuration

``` yaml
# /config/typesetsh_pdf.yaml

typesetsh_pdf:
  # List of allowed directories to load external resources (css, images, fonts,...)
  allowed_directories:
    - '%kernel.project_dir%/public'

  # Default base dir - Used when view URIs use relative path /
  base_dir: '%kernel.project_dir%/public'

  http:
    # Allow to download external resources via http(s)
    allow: false
    # Cache dir fir files downloaded via http(s)
    cache_dir: '%kernel.cache_dir%/typesetsh'
    # Maximum file size in bytes to download via http(s)
    download_limit: 1048576
    # Timeout in seconds when downloading files
    timeout: 5

```

## Security

Make sure to only whitelist the directories that do not contain any sensitive information
to prevent leaking any data. If your templates are escaped correctly this should
never be an issue, yet it's always good to ignore any resource that is not
whitelisted in `allowed_directories`.

By default only the `/public` directory is allowed. If you like to add any other directory
you must whitelist it first.



## HTML template example

Many CSS features are supported. See [typeset.sh](https://typeset.sh/) for more details and demos.

```html
<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>test</title>
        <style>
            @page {
                size: A4;
                margin: 10mm 20mm;
            }
            @font-face {
                font-family: "my font";
                src: url("/fonts/my-font.otf");
                font-weight: 400;
            }
            @font-face {
                font-family: "my font";
                src: url("/fonts/my-font.otf");
                font-weight: 800;
            }
            html {
                font-family: "my font", serif;
                font-size: 12pt;
            }
        </style>
    </head>
    <body>
        <h1>Your lucky number is {{ number }}</h1>
        <p>Some text....</p>
    </body>
</html>

```


## License

This bundle is under the [MIT license](LICENSE).

However, it requires a version of [typeset.sh](https://typeset.sh/) to work.
