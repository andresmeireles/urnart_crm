<?php

namespace App\Utils\Andresmei;

use App\Config\NonStaticConfig;
use App\Utils\Exceptions\BinaryNotFoundException;
use App\Utils\GenericContainer;
use Doctrine\ORM\EntityManagerInterface;
use Twig\Environment;

class Form extends GenericContainer
{
    /**
     * String com caminhos dos formularios para impressão
     *
     * @var string
     */
    protected $templateFolder = 'print/forms/';
    /**
     * tipos de funções
     *
     * @var array
     */
    protected $allowedTypes = ['pdf', 'show'];
    /**
     * Arquivo parseado para impressão
     *
     * @var string
     */
    protected $parsedFile;
    protected $config;

    protected $nonStaticConfig;

    public function __construct(EntityManagerInterface $entityManager, Environment $twig, NonStaticConfig $config)
    {
        $this->nonStaticConfig = $config;
        parent::__construct($entityManager, $twig);
    }

    public function returnSelectedFromType(string $type, string $formName, array $data): ?array
    {
        if (!in_array($type, $this->allowedTypes)) {
            throw new \Exception(sprintf('Tipo %s não é um tipo valido', $type));
        }
        $template = $type === 'pdf' ? sprintf('%sPdf', $formName) : $formName;
        return $this->$type($template, $data);
    }

    public function setCustomTemplateFolder(string $dir): self
    {
        if (!is_dir($dir)) {
            throw new \Exception("{$dir} não é um diretorio.");
        }

        return $this;
    }

    public function show(string $formName, array $data): array
    {
        $this->setParsedFile($formName, $data);
        return [
            'template' => $this->parsedFile
        ];
    }

    /**
     * Recebe parametros para criação de html e conversão para pdf.
     *
     * @param string $formName Nome do formulario
     * @param array  $data informações do formulário
     *
     * @return array array associativo com parametros [pdf_path] com caminho do pdf gerado e [type] com tipo de mensagem
     */
    public function pdf(string $formName, array $data): array
    {
        exec('wkhtmltopdf --version', $opt, $result);
        if ($result === 1) {
            throw new BinaryNotFoundException(
                'wkhtmltopdf não esta instaldado no sistama ou não está no PATH do sistema operacional.'
            );
        }
        $this->setParsedFile($formName, $data);

        if (!file_exists(__DIR__.'/../../../public/reportBuilder') &&
            !is_dir(__DIR__.'/../../../public/reportBuilder')) {
            mkdir(__DIR__.'/../../../public/reportBuilder');
            file_put_contents(__DIR__.'/../../../public/reportBuilder/.gitignore', '/*');
        }

        $htmlReportFile = 'reportBuilder/report.html';
        $pdfDir= 'reportBuilder/report.pdf';
        file_put_contents($htmlReportFile, $this->parsedFile);
        exec("wkhtmltopdf $htmlReportFile $pdfDir", $opt, $result);
        if ($result === 1) {
            throw new \Exception('Conversão não ocorrida. Por Algum erro no wkhtmltopdf.');
        }
        unset($result);
        unset($opt);
        unlink($htmlReportFile);
        return [
            'pdf_path' => $pdfDir,
            'type' => 'success',
        ];
    }

    private function checkFileExistence(string $formName): string
    {
        $completeFilePath = $this->templateFolder.'/'.$formName.'.html.twig';
        if (!file_exists(__DIR__.'/../../../templates/'.$completeFilePath)) {
            throw new \Exception(
                sprintf(
                    "%s não existe em %s. Caminho %s",
                    strtoupper($formName),
                    $this->templateFolder,
                    $completeFilePath
                )
            );
        }
        return $completeFilePath;
    }

    /**
     * Cria arquivo html parseado
     *
     * @param string $formName
     * @param array $data
     * @return void
     */
    private function setParsedFile(string $formName, array $data): void
    {
        $file = $this->checkFileExistence($formName);
        $clonedFields = [];
        foreach ($data as $value) {
            if (is_array($value)) {
                $clonedFields[] = $value;
            }
        }
        if ($formName == 'romaneio-board') {
            $clonedFields = array_reverse($clonedFields);
        }
        $this->parsedFile = $this->twig->render($file, [
            'data' => $data,
            'prod' => $clonedFields,
            'logo' => $this->nonStaticConfig->getProperty('logo_image_path'),
        ]);
    }
}
