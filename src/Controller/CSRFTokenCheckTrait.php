<?php declare(strict_types = 1);

/**
 *
 */
namespace App\Controller;

use App\Utils\Exceptions\CustomException;

/**
 * Trait CsrfTokenCheck
 * @package App\Controller
 */
trait CSRFTokenCheckTrait
{
    /**
     * @param string $encodedCSRFToken
     * @param string $validPhrase
     * @return bool
     * @throws CustomException
     */
    public function isEncodedCSRFTokenValidPhrase(string $encodedCSRFToken, string $validPhrase): bool
    {
        if (!$this->isCsrfTokenValid($validPhrase, $encodedCSRFToken)) {
            throw new CustomException("Token incorreto.");
        }

        return true;
    }
}
