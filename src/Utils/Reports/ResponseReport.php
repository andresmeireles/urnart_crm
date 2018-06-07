<?php 

namespace \App\Utils\Reports;

class ResponseReport implements ResponseReportInterface
{
    private $report;

    private $message;

    function __construct(string $body = null, array $message = null)
    {
        $this->bodyReport = $body;
        $this->message = $message;
    }

    public function setResponse(string $body, array $message)
    {
        $this->bodyReport = $body;
        $this->message = $message;
        if (!$message) {
            $this->message = array('0' => 'Sucesso');
        }
    } 

    public function getBodyReport(): string
    {
        return $this->bodyReport;
    }

    public function getMessage(): array
    {
        return $this->message;
    }

    public function setErrorMessage(array $erroMessage): self 
    {
        $this->message = $erroMessage;

        return $this;
    }

    public function getError(): ?array
    {
        return $this->message;
    }
}
