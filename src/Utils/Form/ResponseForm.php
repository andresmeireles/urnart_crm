<?php declare(strict_types = 1);

namespace App\Utils\Form;

use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\Session\Session;

class ResponseForm
{
    /**
     * @var string|null
     */
    private $bodyForm;

    /**
     * @var string
     */
    private $orientation;

    /**
     * @var array|null
     */
    private $message;

    public function __construct(?string $body = null, string $orientation = 'Portrait', ?array $message = null)
    {
        $this->orientation = $orientation;
        $this->bodyForm = $body;
        $this->message = $message ?? ['Sucesso'];
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
        $session = new Session();
        $session->getFlashBag()->set('error', $message);

        return new RedirectResponse('/form/' . $route);
    }
}
