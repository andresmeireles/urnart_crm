<?php

namespace App\Utils\Reports;

use \Knp\Snappy\Pdf;

class Report 
{
    private $report;

    private $reportFactory;

    private $message;

    function __construct($reportName)
    {
        $reportName = strtolower($reportName);

        switch ($reportName) {
            case 'tag':
                $this->reportFactory = new \App\Utils\Reports\ReportTemplate\TagReportCreator();
                break;
            case 'freight':
                $this->reportFactory = new \App\Utils\Reports\ReportTemplate\FreisghtInvoiceCreator();
                break;
            default:
                throw new \Exception('Algo deu errado');
                break; 
        }

        return $this->reportFactory; 
    }

    public function create(array $parameters): CreateReportInterface
    {
        foreach ($parameters as $parameter) {
            $param = array_map(function ($parameter) {
                $result = ltrim(trim($parameter));
                return $result;
            }, $parameter);
        }

        $this->report = $this->reportFactory->createReport($param);

        return $this->report;
    }

    public function save(CreateReportInterface $report): bool
    {
          
    }

    public function show(CreateReportInterface $report): void
    {
        $pdf = new Pdf(__DIR__.'/../../')
    } 

    public function createAndShow(array $parameters)
    {
        $this->create($parameters);
        $this->show($this->report);
    }
}
