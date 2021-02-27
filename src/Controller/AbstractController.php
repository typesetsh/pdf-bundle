<?php

declare(strict_types=1);

namespace Typesetsh\PdfBundle\Controller;

use Symfony\Bundle\FrameworkBundle;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Typesetsh\PdfBundle;
use Twig;

abstract class AbstractController extends FrameworkBundle\Controller\AbstractController
{
    /**
     * @param string|Twig\TemplateWrapper $viewName
     * @param array $parameters
     * @param string $fileName
     * @param string $disposition
     *
     * @return PdfBundle\Http\Response
     */
    public function pdf(
        $viewName,
        array $parameters = [],
        string $fileName = '',
        string $disposition = HeaderUtils::DISPOSITION_ATTACHMENT
    ): PdfBundle\Http\Response
    {
        return $this->container->get('typesetsh.twig_pdf')->render($viewName, $parameters, $fileName, $disposition);
    }

    public static function getSubscribedServices(): array
    {
        $services = parent::getSubscribedServices();
        $services['typesetsh.twig_pdf'] = '?'.PdfBundle\Service\TwigPdf::class;

        return $services;
    }
}
