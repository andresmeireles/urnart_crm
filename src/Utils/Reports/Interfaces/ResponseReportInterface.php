<?php

namespace App\Utils\Reports\Interfaces;

interface ResponseReportInterface
{
    /**
     * Set a array with error messages
     */
    public function setErrorMessage(array $messages);

    /**
     * Get a array with errors
     */
    public function getError();

    /**
     * Set the body of report and a success message
     *
     * @var body [string] String with report
     * @var message [array] Array with message
     */
    public function setResponse(string $body, ?array $message): self;

    /**
     * Return the string with report
     */
    public function getBodyReport();

    /**
     * Return the array messsage
     */
    public function getMessage();
}
