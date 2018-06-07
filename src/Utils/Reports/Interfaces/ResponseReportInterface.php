<?php

namespace \App\Utils\Report\Interfaces;

interface ResponseReportInterface
{
    /**
     * Set a array with error messages
     */
    public function setErrorMessage(array $messages): self;

    /**
     * Get a array with errors
     */
    public function getError(): ?array;

    /**
     * Set the body of report and a success message
     *
     * @var body [string] String with report
     * @var message [array] Array with message
     */
    public function setResponse(string $body, array $message);

    /**
     * Return the string with report
     */
    public function getBodyReport(): string;

    /**
     * Return the array messsage
     */
    public function getMessage(): array;
}
