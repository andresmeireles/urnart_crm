<?php

namespace App\Config;

/**
 * Exception para quando tentado acessar as configurações com a casse config não iniciada ou removida
 */
class NotFoundParameterException extends \Exception
{
}