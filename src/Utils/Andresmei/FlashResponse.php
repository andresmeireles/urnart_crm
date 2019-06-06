<?php
declare(strict_types=1);

namespace App\Utils\Andresmei;

class FlashResponse
{
    /**
     * HTTP STATUS CODE
     *
     * @var integer
     */
    protected $http_code = 200;

    /**
     * TYPE OF RESPONSE ['error', 'success']
     *
     * @var string
     */
    protected $type = 'success';

    /**
     * RESPONSE MESSAGE
     *
     * @var string
     */
    protected $message;

    /**
     * Retorna aray com parametros para flash message do symfony
     *
     * @param  int          $http_code codigo HTTP
     * @param  string       $type      Tipo de responsta, success, warning, error etc...
     * @param  string|null  $message   Corpo da messagem
     *
     * @return array
     */
    public function __construct(int $http_code, string $type, ?string $message)
    {
        $this->http_code = $http_code;
        $this->type = $type;
        $this->message = $message ?? 'sucesso';
        $this->nonStaticResponse($http_code, $type, $message);
    }

    /**
     * Retorna aray com parametros para flash message do symfony
     *
     * @param  int|null    $http_code codigo HTTP
     * @param  string|null $type      Tipo de responsta, success, warning, error etc...
     * @param  string|null $message   Corpo da messagem
     *
     * @return array
     */
    public function nonStaticResponse(int $http_code = null, string $type = null, string $message = null): array
    {
        return [
            'http_code' => $http_code ?? $this->http_code,
            'type' => $type ?? $this->type,
            'message' => $message ?? $this->message
        ];
    }

    public function getHttpCode(): int
    {
        return $this->http_code;
    }

    public function getType(): string
    {
        return $this->type;
    }

    public function getMessage(): string
    {
        return $this->message;
    }
}
