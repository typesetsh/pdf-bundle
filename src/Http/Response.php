<?php

declare(strict_types=1);

namespace Typesetsh\PdfBundle\Http;

use Typesetsh\IO;
use Typesetsh;
use Symfony\Component\HttpFoundation;

class Response extends HttpFoundation\Response
{
    /**
     * @var Typesetsh\Result
     */
    private $result;

    public function __construct(Typesetsh\Result $result, int $status = 200, array $headers = [])
    {
        $headers += [
            'Content-Type' => 'application/pdf',
        ];

        parent::__construct('', $status, $headers);
        $this->result = $result;
    }

    public function sendContent(): static
    {
        if (!$this->isSuccessful()) {
            return parent::sendContent();
        }

        $destination = new IO\Memory();
        $this->result->save($destination);

        echo $destination->rewind()->read();

        return $this;
    }

    public function getResult(): Typesetsh\Result
    {
        return $this->result;
    }

    public function setResult(Typesetsh\Result $result): void
    {
        $this->result = $result;
    }
}
