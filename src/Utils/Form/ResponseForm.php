<?php 

namespace App\Utils\Form;

use App\Utils\Form\Interfaces\ResponseFormInterface;

class ResponseForm implements ResponseFormInterface
{
    private $bodyForm;

    private $message;

    function __construct(string $body = null, array $message = null)
    {
        $this->bodyForm = $body;
        $this->message = $message;
    }

    public function setResponse(string $body, ?array $message = null): ResponseFormInterface
    {
        $this->bodyForm = $body;
        $this->message = $message;
        if (!$message) {
            $this->message = array('0' => 'Sucesso');
        }

        return $this;
    } 

    public function getBodyForm(): string
    {
        return $this->bodyForm;
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
