<?php 

namespace App\Utils\Andresmei;

use App\Utils\GenericContainer;

class Form extends GenericContainer
{
	protected $templateFolder = 'print/forms/';
	protected $allowedTypes = ['pdf', 'show'];

	public function returnSelectedFromType(string $type, string $formName, array $data): array
	{
		if (!in_array($type, $this->allowedTypes)) {
			throw new \Exception(sprintf('Tipo %s não é um tipo valido', $type));
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
		$clonedFields = [];
		foreach ($data as $key => $value) {
		    if (is_array($value)) {
		        $clonedFields[] = $value;
            }
        }
		$parsedTemplate = $this->twig->render($file, array(	
			'data' => $data,
            'prod' => $clonedFields,
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
		$pdf = $this->pdf->getOutput($parsedTemplate);
		
		return array(	
			'template' => $pdf
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
