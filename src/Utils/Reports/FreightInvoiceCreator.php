<?php 

namespace App\Utils\Reports;

use App\Utils\Report\Interfaces\ReportInterface;

class FreightInvoiceCreator implements ReportInterface
{
	/**
	 * String with the report to be formated, rendered and/or saved
	 * 
	 * @var string
	 */
	private $report;

	/**
	 * List of errors
	 * 
	 * @var array
	 */
	private $errors;

	public function create(): bool 
	{

	}

	public function save(): self
	{

	}

	public function show(): self
	{

	}

	public function getMessage(): string
	{

	}
}