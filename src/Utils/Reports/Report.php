<?php

namespace App\Utils\Reports;

use \Knp\Snappy\Pdf;

use App\Utils\Reports\Interfaces\ResponseReportInterface;

class Report 
{
    private $reportFactory;

    private $message;

    function __construct($reportName)
    {
         $reportNameToLower = strtolower($reportName);

        if (file_exists(__DIR__.'/reportList.yaml')) {
            $report = yaml_parse_file(__DIR__.'\reportList.yaml');

            if (!array_key_exists($reportNameToLower, $report)) {
                throw new \Exception('Report '. $reportNameToLower.' not found.');
            }

            return $this->reportFactory = new $report[$reportNameToLower]();
        }

        throw new \Exception('List of reports not found, create a reportList.yaml with reoprt names in Reports folder.'); 
    }

    public function create(array $parameters): ResponseReportInterface
    {
        foreach ($parameters as $parameter) {
            $param[] = array_map(function ($parameter) {
                $result = ltrim(trim($parameter));
                return $result;
            }, $parameter);
        }

        return $this->reportFactory->createReport($param);
    }

    public function save(CreateReportInterface $report): bool
    {
          
    }

    public function show(ResponseReportInterface $report): void
    {
        $pdf = new Pdf(__DIR__.'\..\..\..\vendor\wemersonjanuario\wkhtmltopdf-windows\bin\64bit\wkhtmltopdf.exe');

        header('Content-Type: application/pdf');
        echo $pdf->getOutputFromHtml($report->getBodyReport());
    } 

    public function createAndShow(array $parameters)
    {
        $report = $this->create($parameters);
        $this->show($report);
    }
}
