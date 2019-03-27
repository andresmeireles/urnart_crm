<?php
declare(strict_types=1);

namespace App\Utils\Andresmei;

class ObjectResponse
{
    /**
     * Response HTTP code
     *
     * @var int
     */
    private $httpCode;

    /**
     * Response message for requisition
     *
     * @var string
     */
    private $message;

    /**
     * For notifications, type of response [success, warning, danger] to decorate the notification css
     *
     * @var string
     */
    private $responseType;

    public function __construct(int $httpCode = 200, ?string $message = null, string $responseType = 'success')
    {
        $this->httpCode = $httpCode;
        $this->message = $message ?? '';
        $this->responseType = $responseType;
    }
    
    /**
     * Get response HTTP code
     *
     * @return  int
     */
    public function getHttpCode(): int
    {
        return $this->httpCode;
    }

    /**
     * Get response message for requisition
     *
     * @return  string
     */
    public function getMessage(): string
    {
        return $this->message;
    }

    /**
     * Get for notifications, type of response [success, warning, danger] to decorate the notification css
     *
     * @return  string
     */
    public function getResponseType(): string
    {
        return $this->responseType;
    }
}
