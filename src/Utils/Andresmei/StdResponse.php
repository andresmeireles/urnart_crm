<?php declare(strict_types = 1);

namespace App\Utils\Andresmei;

/**
 * Simple class for response inspired in php.net suggestion for stdClass in
 * http://php.net/manual/pt_BR/reserved.classes.php
 */
final class StdResponse extends \stdClass
{
    public function __call($key, $params)
    {
        if (! isset($this->{$key})) {
            throw new \Exception(sprintf('Call undefined param %s()', $key));
        }
        $subject = $this->{$key};
        call_user_func_array($subject, $params);
    }
}
