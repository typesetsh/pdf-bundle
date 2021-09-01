<?php

declare(strict_types=1);

namespace Typesetsh\PdfBundle;

use Typesetsh\HtmlToPdf;
use Typesetsh\Result;
use Typesetsh;

class PdfGenerator
{
    /** @var HtmlToPdf */
    private $htmlToPdf;

    /** @var Typesetsh\UriResolver */
    private $uriResolver;

    /** @var string */
    private $version;

    /**
     * @param array<string, Typesetsh\UriResolver\Scheme> $schemes
     */
    public function __construct(array $schemes = [], string $baseUri = null, string $version = '1.6')
    {
        $this->htmlToPdf = new Typesetsh\HtmlToPdf();
        $this->uriResolver = new Typesetsh\UriResolver($schemes, $baseUri);
        $this->version = $version;
    }

    /**
     * Render single HTML file as PDF
     */
    public function render(string $html): Result
    {
        $result = $this->htmlToPdf->render($html, $this->uriResolver);
        $result->version=$this->version;

        return $result;
    }

    /**
     * Render multiple HTML files as a single PDF.
     *
     * @param non-empty-list<string> $html
     */
    public function renderMultiple(array $html): Result
    {
        $result = $this->htmlToPdf->renderMultiple($html, $this->uriResolver);
        $result->version=$this->version;

        return $result;
    }
}
