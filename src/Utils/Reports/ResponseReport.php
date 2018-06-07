<?php 

namespace App\Utils\Reports;

use App\Utils\Reports\Interfaces\ResponseReportInterface;

class ResponseReport implements ResponseReportInterface
{
    private $bodyReport;

    private $message;

    function __construct(string $body = null, array $message = null)
    {
        $this->bodyReport = $body;
        $this->message = $message;
    }

    public function setResponse(string $body, ?array $message = null): ResponseReportInterface
    {
        $this->bodyReport = $body;
        $this->message = $message;
        if (!$message) {
            $this->message = array('0' => 'Sucesso');
        }

        return $this;
    } 

    public function getBodyReport(): string
    {
        return $this->bodyReport;
    }

    public function getMessage(): array
    {
        return $this->message;
    }

    public function setErrorMessage(array $messages): self 
    {
        $this->message = $erroMessage;

        return $this;
    }

    public function getError(): ?array
    {
        return $this->message;
    }
}
