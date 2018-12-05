<?php
declare(strict_types=1);

namespace App\Utils\Andresmei;

use Symfony\Component\Security\Core\Exception\InvalidCsrfTokenException;


class CsrfToken
{
    /**
     * strng of hash token;
     *
     * @var string
     */
    private $csrfToken;

    /**
     * Non hashed string
     *
     * @var string
     */
    private $csrfTokenString;

    public function __construct(string $csrfTokenString = null)
    {
        if (!is_null($csrfTokenString)) {
            $this->csrfTokenString = $csrfTokenString;
            $this->csrfToken = (string) password_hash($csrfTokenString, PASSWORD_DEFAULT);
        }
    }

    public function __toString(): string
    {
        return $this->csrfToken;
    }

    public function setCsrfToken(string $tokenString): void
    {
        $this->csrfTokenString = $tokenString;
        $this->csrfToken = (string) password_hash($this->csrfTokenString, PASSWORD_DEFAULT);
    }

    public function getCsrfToken(): string
    {
        return $this->csrfToken;
    }

    public function getCsrfTokenString(): string
    {
        return $this->csrfTokenString;
    }

    public function isValid(string $csrfTokenString, ?string $csrfToken = null): bool
    {
        $validatorToken = $csrfToken ?? $this->csrfToken;
        if (empty($validatorToken)) {
            throw new InvalidCsrfTokenException('Token não enviado, para funcionar, favor enviar token');
        }
        $isValid = password_verify($csrfTokenString, $validatorToken);
        if (!$isValid) {
            return false;
        }

        return true;
    }
}