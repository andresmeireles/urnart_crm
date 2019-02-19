<?php
/**
 * 
 */
namespace App\Utils\Andresmei;

/**
 * Simple class for response inspired in php.net suggestion for stdClass in
 * http://php.net/manual/pt_BR/reserved.classes.php
 * 
 */
class StdResponse extends \stdClass
{
    public function __call($key,$params){
        if(!isset($this->{$key})) throw new \Exception("Call to undefined method ".get_class($this)."::".$key."()");
        $subject = $this->{$key};
        call_user_func_array($subject,$params);
    }
}