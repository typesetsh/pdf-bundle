<?php

declare(strict_types=1);

namespace Typesetsh\PdfBundle\Controller;

use Symfony\Bundle\FrameworkBundle;
use Symfony\Component\HttpFoundation\HeaderUtils;
use Typesetsh\PdfBundle;
use Typesetsh\PdfBundle\PdfGenerator;

abstract class AbstractController extends FrameworkBundle\Controller\AbstractController
{
    public function pdf(string $view, array $parameters = [], $fileName = '', $disposition = HeaderUtils::DISPOSITION_ATTACHMENT)
    {
        $content = $this->renderView($view, $parameters);

        /** @var PdfGenerator $pdfGenerator */
        $pdfGenerator = $this->container->get('typesetsh.pdf');
        $result = $pdfGenerator->render($content);

        $disposition = HeaderUtils::makeDisposition($disposition, $fileName);

        $response = new PdfBundle\Http\Response($result);
        $response->headers->set('Content-Disposition', $disposition);

        return $response;
    }

    public static function getSubscribedServices()
    {
        $services = parent::getSubscribedServices();
        $services['typesetsh.pdf'] = '?'.PdfGenerator::class;

        return $services;
    }
}
