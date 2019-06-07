<?php declare(strict_types = 1);

namespace App\Utils\Form;

use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;
use Symfony\Component\HttpFoundation\RedirectResponse;

class ResponseForm
{
    private $bodyForm;
    private $orientation;
    private $message;

    public function __construct(string $body = null, string $orientation = 'Portrait', array $message = null)
    {
        $this->orientation = $orientation;
        if ($body != null) {
            $this->setResponse($body, $message);
        }
    }

    public function setResponse(string $body, ?array $message = null): self
    {
        $this->bodyForm = $body;
        $this->message = $message;
        if (!$message) {
            $this->message = ['0' => 'Sucesso'];
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

    public function newErrorMessage(array $messages): self
    {
        $this->message = $messages;

        return $this;
    }

    public function getError(): ?array
    {
        return $this->message;
    }

    public function redirectError(string $route, array $message): Response
    {
        $session = new Session;
        $session->getFlashBag()->set('error', $message);

        return new RedirectResponse('/form/' . $route);
    }
}
