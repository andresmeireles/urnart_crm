<?php declare(strict_types=1);

namespace App\Utils\Andresmei;

use Twig\Environment;
use App\Config\NonStaticConfig;
use App\Utils\GenericContainer;
use Doctrine\ORM\EntityManagerInterface;
use App\Utils\Exceptions\BinaryNotFoundException;
use Spatie\Browsershot\Browsershot;

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

    /**
     * Função que criar formulario a partir de parametros enviados
     *
     * @param string $type Tipo de formulario desejado, parametros atuais [pdf, show]
     * @param string $formName Nome do formulário que pode ser criado. Lista de formularios são:
     *                          tag
     *                          freight-letter                          
     *                          order
     *                          receipt
     *                          remand
     *                          romaneio
     *                          travel
     *                          travel-report
     *                          withdrawal
     * @param array $data Informações sobre o formulário
     * @return array
     */
    public function returnSelectedFromType(string $type, string $formName, array $data): array
    {
        if (!in_array($type, $this->allowedTypes)) {
            throw new \Exception(sprintf('Tipo %s não é um tipo valido', $type));
        }
        $template = $type === 'pdf' ? sprintf('%sPdf', $formName) : $formName;
        return $this->$type($template, $data);
    }

    /**
     * Retorna relatorio no formato HTML, para informação mais detalhada checar documentação do metodo
     * returnSelectedFormType
     *
     * @param string $formName nome do formulário
     * @param array $data Informações do formulário
     * @return array
     */
    public function show(string $formName, array $data): array
    {
        $this->setParsedFile($formName, $data);
        return [
            'template' => $this->parsedFile
        ];
    }

    /**
     * Recebe parametros para criação de html e conversão para pdf. Para informação mais geerica sobre
     * parametros checar metodo returnSelectedFormType
     *
     * @param string $formName Nome do formulario
     * @param array  $data informações do formulário
     *
     * @return array array associativo com parametros [pdf_path] com caminho do pdf gerado e [type] com tipo de mensagem
     */
    public function pdf(string $formName, array $data): array
    {
        $this->setParsedFile($formName, $data);
        Browsershot::html($this->parsedFile)->save('xof.pdf');
        die('fooooe');
        // exec('wkhtmltopdf --version', $opt, $result);
        // if ($result === 1) {
        //     throw new BinaryNotFoundException(
        //         'wkhtmltopdf não esta instaldado no sistama ou não está no PATH do sistema operacional.'
        //     );
        // }
        // $this->setParsedFile($formName, $data);

        // if (!file_exists(__DIR__.'/../../../public/reportBuilder') &&
        //     !is_dir(__DIR__.'/../../../public/reportBuilder')) {
        //     mkdir(__DIR__.'/../../../public/reportBuilder');
        //     file_put_contents(__DIR__.'/../../../public/reportBuilder/.gitignore', '/*');
        // }
        // $htmlReportFile = 'reportBuilder/report.html';
        // $pdfDir= sprintf('reportBuilder/report%s.pdf', $formName);
        // file_put_contents($htmlReportFile, $this->parsedFile);
        // exec("wkhtmltopdf --encoding 'utf-8' $htmlReportFile $pdfDir", $opt, $result);
        // if ($result === 1) {
        //     throw new \Exception('Conversão não ocorrida. Por Algum erro no wkhtmltopdf.');
        // }
        // unset($result);
        // unset($opt);
        // unlink($htmlReportFile);
        
        // return [
        //     'pdf_path' => $pdfDir,
        //     'type' => 'success',
        // ];
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
