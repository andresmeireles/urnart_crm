<?php declare(strict_types = 1);

namespace App\Utils\Andresmei;

use App\Utils\GenericContainer;
use Doctrine\ORM\EntityManagerInterface;
use Spatie\Browsershot\Browsershot;
use Twig\Environment;

class Form extends GenericContainer
{
    /**
     * @var string
     */
    private $templateFolder = 'print/forms/';

    /**
     * @var array
     */
    private $allowedTypes = ['pdf', 'show'];

    /**
     * @var string
     */
    private $parsedFile;

    public function __construct(
        EntityManagerInterface $entityManager,
        Environment $twig
    ) {
        parent::__construct($entityManager, $twig);
    }

    public function __get($name)
    {
        if ($name === 'parsedFile') {
            return $this->getParsedFile();
        }
    }

    /**
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
     * @throws \Exception
     */
    public function returnSelectedFromType(string $type, string $formName, array $data): array
    {
        if (! in_array($type, $this->allowedTypes, true)) {
            throw new \Exception(sprintf('Tipo %s não é um tipo valido', $type));
        }
        $template = $type === 'pdf' ? sprintf('%sPdf', $formName) : $formName;

        return $this->{$type}($template, $data);
    }

    /**
     * @return string
     */
    public function getParsedFile(): string
    {
        return $this->parsedFile;
    }

    /**
     * @param string $formName
     * @param array $data
     * @return array
     * @throws \App\Config\NotFoundParameterException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function show(string $formName, array $data): array
    {
        $this->setParsedFile($formName, $data);

        return [
            'template' => $this->parsedFile,
        ];
    }

    /**
     * @param string $formName
     * @param array $data
     * @return array
     * @throws \App\Config\NotFoundParameterException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
     */
    public function pdf(string $formName, array $data)
    {
        $this->setParsedFile($formName, $data);
        $pdfDir = sprintf(
            'reportBuilder/report%s.pdf',
            $formName
        );
        Browsershot::html($this->parsedFile)->save($pdfDir);

        return [
            'pdf_path' => $pdfDir,
            'type' => 'success',
        ];
    }

    /**
     * @param string $formName
     * @return string
     * @throws \Exception
     */
    private function checkFileExistence(string $formName): string
    {
        $completeFilePath = $this->templateFolder . '/' . $formName . '.html.twig';
        if (!file_exists(__DIR__ . '/../../../templates/' . $completeFilePath)) {
            throw new \Exception(
                sprintf(
                    '%s não existe em %s. Caminho %s',
                    strtoupper($formName),
                    $this->templateFolder,
                    $completeFilePath
                )
            );
        }

        return $completeFilePath;
    }

    /**
     * @param string $formName
     * @param array $data
     * @throws \App\Config\NotFoundParameterException
     * @throws \Twig\Error\LoaderError
     * @throws \Twig\Error\RuntimeError
     * @throws \Twig\Error\SyntaxError
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
        if ($formName === 'romaneio-board') {
            $clonedFields = array_reverse($clonedFields);
        }

        $this->parsedFile = $this->twig->render($file, [
            'data' => $data,
            'prod' => $clonedFields,
            'logo' => '/sys/logo_img/logo.png',
        ]);
    }
}
