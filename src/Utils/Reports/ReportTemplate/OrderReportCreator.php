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

	/**
	 * Create report and save on database
	 * @param  array  $params parameters for report
	 * @return ResponseReportInterface
	 */
	public function createReport(array $params): ResponseReportInterface
	{
		$response = new ResponseReport();

		$reportBody = $this->createBodyReport($params);

		return $response->setResponse($reportBody)
	}

	public function getMessage(): array
	{

	}

	private function validation(array $params): bool
	{
		if (ValidatorJson::getErrors()) {
			return false;
		}

		return true;
	}

	private function createBodyReport(array $parameter): string
	{

	}
}