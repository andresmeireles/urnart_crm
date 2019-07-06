<?php declare(strict_types = 1);

namespace App\Utils\Andresmei;

final class FlashResponse
{
    /**
     * @var int
     */
    private $http_code = 200;

    /**
     * @var string
     */
    private $type = 'success';

    /**
     * @var string
     */
    private $message;

    public function __construct(int $http_code, string $type, ?string $message)
    {
        $this->http_code = $http_code;
        $this->type = $type;
        $this->message = $message ?? 'sucesso';
        $this->nonStaticResponse($http_code, $type, $message);
    }

    /**
     * @param int|null $http_code
     * @param string|null $type
     * @param string|null $message
     * @return array
     */
    public function nonStaticResponse(?int $http_code = null, ?string $type = null, ?string $message = null): array
    {
        return [
            'http_code' => $http_code ?? $this->http_code,
            'type' => $type ?? $this->type,
            'message' => $message ?? $this->message,
        ];
    }

    /**
     * @return int
     */
    public function getHttpCode(): int
    {
        return $this->http_code;
    }

    /**
     * @return string
     */
    public function getType(): string
    {
        return $this->type;
    }

    /**
     * @return string
     */
    public function getMessage(): string
    {
        return $this->message;
    }
}
