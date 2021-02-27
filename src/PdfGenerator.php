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

    /**
     * @param array<string, Typesetsh\UriResolver\Scheme> $schemes
     */
    public function __construct(array $schemes = [], string $baseUri = null)
    {
        $this->htmlToPdf = new Typesetsh\HtmlToPdf();
        $this->uriResolver = new Typesetsh\UriResolver($schemes, $baseUri);
    }

    /**
     * Render single HTML file as PDF
     */
    public function render(string $html): Result
    {
        return $this->htmlToPdf->render($html, $this->uriResolver);
    }

    /**
     * Render multiple HTML files as a single PDF.
     *
     * @param non-empty-list<string> $html
     */
    public function renderMultiple(array $html): Result
    {
        return $this->htmlToPdf->renderMultiple($html, $this->uriResolver);
    }
}
