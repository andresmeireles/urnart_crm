<?php 

namespace App\Utils\Andresmei;

use App\Utils\GenericContainer;

class Form extends GenericContainer
{
	protected $templateFolder = 'print/forms/';
	protected $allowedTypes = ['print', 'show'];

	public function returnSelectedFromType(string $type, string $formName, array $data): array
	{
		if (!in_array($type, $this->allowedTypes)) {
			return sprintf('Tipo %s não é um tipo valido', $type);
		}
		$result = $this->$type($formName, $data);
		return $result;
	}

	public function setCustomTemplateFolder(string $dir): self
	{
		if (!is_dir($dir)) {
			throw new \Exception("{$dir} não é um diretorio.");
		}
	}

	public function show(string $formName, array $data): array
	{
		$file = $this->checkFileExistence($formName);
		$parsedTemplate = $this->twig->render($file, array(
			'data' => $data
		));
		return array(	
			'template' => $parsedTemplate
		);
	}

	public function pdf(string $formName, array $data): array
	{
		$file = $this->checkFileExistence($formName);
		$parsedTemplate = $this->twig->render($file, array(
			'data' => $data
		));
		$this->pdf->generateFromHtml($parsedTemplate);
		return array(	
			'template' => $parsedTemplate
		);
	}

	private function checkFileExistence(string $formName): string
	{
		$completeFilePath = $this->templateFolder.'/'.$formName.'.html.twig';
		if (!file_exists(__DIR__.'/../../../templates/'.$completeFilePath)) {
			throw new \Exception("{$formName} não existe em {$this->templateFolder}. Caminho {$completeFilePath}");
		}
		return $completeFilePath;
	}
}