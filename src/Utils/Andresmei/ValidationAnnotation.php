<?php declare(strict_types = 1);

namespace App\Utils\Andresmei;
use Respect\Validation\Exceptions\NestedValidationException;
use Respect\Validation\Validator as v;

/**
 * Class ValidationAnnotation
 * @package App\Utils\Andresmei
 * @Annotation
 * @Target({"PROPERTY"})
 */
final class ValidationAnnotation
{
    /**
     * @var array
     */
    private $rules;

    /**
     * @var array|null
     */
    private $error;

    /**
     * @var array|null
     */
    private $allErrorMessages;

    public function __construct(array $rules)
    {
        $listOfRules = is_array($rules['value']) ? $rules['value'] : [$rules['value']] ;
        $this->rules = $listOfRules;
    }

    /**
     * @param array $parameter
     */
    public function executeValidationInParameter(array $parameter): void
    {
        foreach ($this->rules as $rule) {
            $nameWithoutParenthesis = $this->clearFunctionName($rule);
            $ruleToTest = $this->getFunctionParameters($rule) !== '' ?
                v::{$nameWithoutParenthesis}($this->getFunctionParameters($rule)) :
                v::{$nameWithoutParenthesis}();
            $this->validateParamByRule($parameter, $ruleToTest);
        }
    }

    /**
     * @param $param
     * @param v $rule
     */
    private function validateParamByRule(array $param, v $rule): void
    {
        $parameterkey = array_key_first($param);
        try {
            $rule->setName($parameterkey)->assert($param[$parameterkey]);
        } catch (NestedValidationException $err) {
            $err->findMessages([
                'numeric' => '{{name}} precisa ser um número.',
                'positive' => '{{name}} precisa ser positivo.',
                'notEmpty' => '{{name}} não pode ser vazio.',
                'notBlank' => '{{name}} não pode estar em branco.'
            ]);
            $this->error = $err->getMessages();
            $this->allErrorMessages[] = $err->getMessages();
        }
    }

    /**
     * @param $function
     * @return string
     */
    public function getFunctionParameters($function): string
    {
        if (!strpos($function, '(')) {
            return '';
        }

        $openParenthesis = strpos($function, '(');
        $closeParenthesis = strrpos($function, ')');
        $funcParametersBody = substr($function, $openParenthesis + 1, ($closeParenthesis - $openParenthesis) - 1);

        return $funcParametersBody;
    }

    /**[
     * @param string $unclearFuncName
     * @return string
     */
    public function clearFunctionName(string $unclearFuncName): string
    {
        $parameterWithParenthesis = sprintf(
            '(%s)',
            $this->getFunctionParameters($unclearFuncName)
        );

        return str_replace($parameterWithParenthesis, '', $unclearFuncName);
    }

    /**
     * @return null|array
     */
    public function getValidationErrors(): ?array
    {
        return $this->error;
    }

    /**
     * @return array
     */
    public function getAllValidationErrors(): ?array
    {
        return $this->allErrorMessages;
    }
}
