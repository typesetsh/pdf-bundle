<?php declare(strict_types=1);

namespace Typesetsh\PdfBundle\Http;

use Symfony\Component\HttpFoundation;
use typesetsh\Result;
use jsiefer\IO;

class Response extends HttpFoundation\Response
{
    /** @var Result */
    private $result;

    public function __construct(Result $result, int $status = 200, array $headers = [])
    {
        $headers += [
            'Content-Type' => 'application/pdf',
        ];

        parent::__construct('', $status, $headers);
        $this->result = $result;
    }

    public function sendContent()
    {
        if (!$this->isSuccessful()) {
            return parent::sendContent();
        }

        // @todo https://gitlab.com/jsiefer/issues.typeset.sh/issues/21
        //$destination = new IO\Resource('php://output', 'wb');
        $destination = new IO\Memory();

        $this->result->save($destination);

        echo $destination->rewind()->read();

        return $this;
    }

    public function getResult(): Result
    {
        return $this->result;
    }

    public function setResult(Result $result): void
    {
        $this->result = $result;
    }
}
