<?php

namespace App\Utils\Form;

use App\Utils\Form\Parameters;
use App\Utils\Form\Interfaces\ResponseFormInterface;
use App\Utils\Validation\ValidatorJson;
use Symfony\Component\Yaml\Yaml;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Knp\Snappy\Pdf;

class Form
{
    private $FormFactory;
    private $formName;

    public function __construct(string $FormName)
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

        if ($this->fail()) {
            return ValidatorJson::getErrors();
        }

        return $formResponse;
    }

    public function fail()
    {
        if (ValidatorJson::getErrors()) {
            return true;
        }

        return false;
    }

    public function save(CreateFormInterface $Form): bool
    {
    }

    public function getMessage()
    {
        return ValidatorJson::getErrors();
    }

    public function show(ResponseFormInterface $Form): string
    {
        // windows
        $pdf = new Pdf(__DIR__.'\..\..\..\vendor\wemersonjanuario\wkhtmltopdf-windows\bin\64bit\wkhtmltopdf.exe');
        
        // linux
        //$pdf = new Pdf(__DIR__.'/../../../vendor/h4cc/wkhtmltopdf-amd64/bin/wkhtmltopdf-amd64');
        $html = $Form->getBodyForm();

        file_put_contents(__DIR__.'/../../../public/form/bill.html', $html);

        $orientation = $Form->getOrientation();

        $pdf->setOption('orientation', $orientation);
        
        $pdf->setOption('encoding', 'UTF-8');
        $pdf->setOption('page-height', '297.0');
        $pdf->setOption('page-width', '210.0');
        if (file_exists(__DIR__.'/../../../public/form/bill.pdf')) {
            //chmod(__DIR__.'/../../../public/form/bill.pdf', 777);
            unlink(__DIR__.'/../../../public/form/bill.pdf');
        }
        $pdf->generateFromHtml($html, __DIR__.'/../../../public/form/bill.pdf');
        
        return new Response(201);
    }

    public function createAndShow(array $parameters)
    {
        $Form = $this->create($parameters);
        $this->show($Form);
    }
}
