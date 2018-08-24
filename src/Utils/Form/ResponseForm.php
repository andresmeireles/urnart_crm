<?php 

namespace App\Utils\Form;

use App\Utils\Form\Interfaces\ResponseFormInterface;
use Symfony\Component\HttpFoundation\RedirectResponse;
//use Symfony\Component\HttpFoundation\Session\Session;

class ResponseForm implements ResponseFormInterface
{
    private $bodyForm;
    private $orientation;
    private $message;

    function __construct(string $body = null, string $orientation = 'Portrait' ,array $message = null)
    {
        $this->orientation = $orientation;

        if ($body != null) {
            $this->setResponse($body, $message);
        }
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

    public function getBodyForm(): ?string
    {
        return $this->bodyForm;
    }

    public function getMessage(): array
    {
        return $this->message;
    }

    public function getOrientation(): string 
    {
        return $this->orientation;
    }

    public function setErrorMessage(array $messages): ResponseFormInterface 
    {
        $this->message = $messages;

        return $this;
    }

    public function getError(): ?array
    {
        return $this->message;
    }

    public function redirectError(string $route, array $message)
    {
        $session = new Session;
        $session->getFlashBag()->set('error', $message);
        return new RedirectResponse('/form/'.$route);
    }
}
