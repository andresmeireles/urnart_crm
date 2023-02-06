<?php declare(strict_types = 1);

namespace App\Utils\Andresmei;

final class ObjectResponse
{
    /**
     * @var int
     */
    private $httpCode;

    /**
     * @var string
     */
    private $message;

    /**
     * @var string
     */
    private $responseType;

    public function __construct(
        int $httpCode = 200,
        ?string $message = null,
        string $responseType = 'success'
    ) {
        $this->httpCode = $httpCode;
        $this->message = $message ?? '';
        $this->responseType = $responseType;
    }

    /**
     * @return  int
     */
    public function getHttpCode(): int
    {
        return $this->httpCode;
    }

    /**
     * @return  string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * @return  string
     */
    public function getResponseType(): string
    {
        return $this->responseType;
    }
}
