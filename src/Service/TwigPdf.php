<?php

declare(strict_types=1);

namespace Typesetsh\PdfBundle\Service;

use Symfony\Component\HttpFoundation\HeaderUtils;
use Twig;
use Typesetsh\PdfBundle;

class TwigPdf
{
    /** @var Twig\Environment */
    private $twig;

    /** @var PdfBundle\PdfGenerator */
    private $pdfGenerator;

    public function __construct(Twig\Environment $twig, PdfBundle\PdfGenerator $pdfGenerator)
    {
        $this->twig = $twig;
        $this->pdfGenerator = $pdfGenerator;
    }

    /**
     * Returns a rendered view as pdf.
     *
     * @param string|Twig\TemplateWrapper $name The template name
     * @param array $parameters
     * @param string $fileName
     * @param string $disposition
     *
     * @return PdfBundle\Http\Response
     * @throws Twig\Error\LoaderError
     * @throws Twig\Error\RuntimeError
     * @throws Twig\Error\SyntaxError
     */
    public function render(
        $name,
        array $parameters = [],
        $fileName = '',
        $disposition = HeaderUtils::DISPOSITION_ATTACHMENT
    ): PdfBundle\Http\Response
    {
        $content = $this->twig->render($name, $parameters);

        $result = $this->pdfGenerator->render($content);

        $disposition = HeaderUtils::makeDisposition($disposition, $fileName);

        $response = new PdfBundle\Http\Response($result);
        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }
}
