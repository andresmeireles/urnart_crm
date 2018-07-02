<?php

namespace App\Utils\Form;

use App\Utils\Form\Parameters;
use App\Utils\Form\Interfaces\ResponseFormInterface;
use App\Utils\Validation\ValidatorJson; 
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\HttpFoundation\Request;
use Knp\Snappy\Pdf;

class Form 
{
    private $FormFactory;
    private $formName;

    function __construct(string $FormName)
    {
        $this->formName = $FormName;
        $FormNameToLower = strtolower($FormName);

        if (file_exists(__DIR__.'/formList.yaml')) {
            $Form = Yaml::parse(file_get_contents(__DIR__.'/formList.yaml'));
            
            if (!array_key_exists($FormNameToLower, $Form)) {
                throw new \Exception('Form '. $FormNameToLower.' not found.');
            }

            return $this->FormFactory = new $Form[$FormNameToLower]();
        }

        throw new \Exception('List of Forms not found, create a FormList.yaml with reoprt names in Forms folder.'); 
    }

    public function create(array $parameters)
    {
        foreach ($parameters as $key => $value) { 
            if (is_array($value)) {
                $parameters[$key] = array_map(function ($parameter) {
                    $result = ltrim(trim($parameter));
                    return $result;
                }, $value);
                continue;   
            }

            $parameters[$key] = ltrim(trim($value));
        }

        $formResponse = $this->FormFactory->createForm(new Parameters($parameters));

        return $formResponse;
    }

    public function save(CreateFormInterface $Form): bool
    {
          
    }

    public function fail()
    {
        if (ValidatorJson::getErrors()) {
            return true;
        }

        return false;
    }

    public function getMessage()
    {
        return ValidatorJson::getErrors();
    }

    public function show(ResponseFormInterface $Form): string
    {
        $pdf = new Pdf(__DIR__.'\..\..\..\vendor\wemersonjanuario\wkhtmltopdf-windows\bin\64bit\wkhtmltopdf.exe');
        echo $Form->getBodyForm();
        //header('Content-Type: application/pdf');
        //echo $pdf->getOutputFromHTML($Form->getBodyForm());
        die();
    } 

    public function createAndShow(array $parameters)
    {
        $Form = $this->create($parameters);
        $this->show($Form);
    }
}
