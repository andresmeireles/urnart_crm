<?php

namespace App\Utils\Form\Interfaces;

interface ResponseFormInterface
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
     * Set the body of Form and a success message
     *
     * @var body [string] String with Form
     * @var message [array] Array with message
     */
    public function setResponse(string $body, ?array $message): self;

    /**
     * Return the string with Form
     */
    public function getBodyForm();

    /**
     * Return the array messsage
     */
    public function getMessage();


    public function redirectError(string $route, array $message);
}
