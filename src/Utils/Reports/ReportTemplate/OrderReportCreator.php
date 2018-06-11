<?php 

namespace App\Utils\Reports\ReportTemplate;

use \App\Utils\Reports\ResponseReport;
use \App\Utils\Reports\Interfaces\ResponseReportInterface;
use \App\Utils\Reports\Interfaces\CreateReportInterface;
use \App\Utils\Validation\ValidatorJson;
use \Respect\Validation\Validator as v;

class OrderReportCreator implements CreateReportInterface
{
	private $error;

	public function createReport(array $params): ResponseReportInterface
	{
		$response = new ResponseReport();
	}

	private function validation(array $params): bool
	{
		if (ValidatorJson::getErrors()) {
			return false;
		}

		return true;
	}

	public function getMessage(): array
	{

	}
}