<?php declare(strict_types=1);

namespace Typesetsh\PdfBundle;

use typesetsh\HtmlToPdf;
use typesetsh\Resource;

class PdfGenerator
{
    /** @var HtmlToPdf */
    private $htmlToPdf;

    /** @var Resource\Cache */
    private $resourceCache;

    /** @var string[] */
    public $allowedDirectories  = [];

    /** @var bool  */
    public $allowHttp = false;

    /** @var string */
    public $baseUri = '';

    /** @var int */
    public $downloadLimit = 1024;

    /** @var int */
    public $timeout = 5;


    public function __construct($cacheDir = '')
    {
        $this->htmlToPdf = new HtmlToPdf();
        $this->resourceCache = new Resource\Cache($cacheDir);
    }


    public function render(string $html): \typesetsh\Result
    {
        $result = $this->htmlToPdf->render($html, function ($path, $base = null) {
            return $this->resolveUri($path, $base);
        });

        return $result;
    }

    /**
     * Any attempt to include and load a external resources (css, img,...) goes through
     * this function.
     */
    private function resolveUri(string $uri, ?string $base): string
    {
        $base = $base ?: $this->baseUri;
        if ($uri[0] === '/') {
            $uri = $base . $uri;
        }

        if (strpos($uri, 'https://') === 0 || strpos($uri, 'http://')) {
            return $this->resolveHttpUri($uri);
        }

        return $this->resolveLocalUri($uri);
    }


    /**
     * Only allow access to the whitelisted directories
     */
    private function resolveLocalUri(string $uri): string
    {
        $uri = realpath($uri);
        if ($uri) {
            foreach ($this->allowedDirectories as $directory) {
                if (strpos($uri, $directory) === 0) {
                    return $uri;
                }
            }
        }

        return '';
    }


    /**
     * Only allow fetching http resources if enabled
     */
    private function resolveHttpUri(string $uri): string
    {
        if (!$this->allowHttp) {
            return '';
        }

        $this->resourceCache->downloadLimit = $this->downloadLimit;
        $this->resourceCache->timeout = $this->timeout;

        return $this->resourceCache->fetch($uri);
    }
}
