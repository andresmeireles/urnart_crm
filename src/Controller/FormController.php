<?php declare(strict_types=1);
/**
 * FormController
 *
 * @category Controller
 * @package  App\Controller
 * @author   André Meireles <andre2meireles@gmail.com>
 * @license  MIT <https://mit-license.org>
 * @link     https://bitbucket.org/andresmeireles/sysadmin
 */
namespace App\Controller;

use App\Config\NonStaticConfig;
use App\Entity\PaymentType;
use App\Entity\Product;
use App\Entity\Transporter;
use App\Entity\TravelAccountability;
use App\Model\FormModel;
use App\Utils\Andresmei\FileFunctions;
use App\Utils\Andresmei\Form;
use App\Utils\Andresmei\NestedArraySeparator;
use App\Utils\Andresmei\StringConvertions;
use App\Utils\Exceptions\CustomException;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Filesystem\Exception\FileNotFoundException;
use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Yaml\Yaml;

/**
 * Controller das paginas de formulário
 *
 * @category Controller
 * @package  App\Controller\FormController
 * @author   André Meireles <andre2meireles@gmail.com>
 * @license  MIT <https://mit-license.org>
 * @link     https://bitbucket.org/andresmeireles/sysadmin
 */
class FormController extends AbstractController
{
    /**
     * Reireciona para pagina inicial da sessão de formulários
     *
     * @Route("/forms", name="form")
     *
     * @return Response
     */
    public function index()
    {
        return $this->render('form/index.html.twig');
    }

    /**
     * @Route("/forms/view", methods="GET", name="form_view")
     *
     * @return  Response
     */
    public function viewSaveReports(Request $request, FileFunctions $fileFunc): Response
    {
        $repoType = $request->query->get('type');
        $reportFolder = sprintf(
            '%s/%s/%s',
            $this->getParameter('kernel.root_dir'),
            $this->getParameter('app.path.report_folder'),
            ucwords($repoType)
        );

        $files = $fileFunc->getFilesFromFolder($reportFolder);
        $reports = [];
        foreach (array_keys($files) as $key) {
            $arrayFile = file_get_contents($key);
            if (is_string($arrayFile)) {
                $reports[] = Yaml::parse($arrayFile);
            }
        }
        return $this->render("form/saveReports.html.twig", [
            'reports' => $reports,
            'type' => $repoType
        ]);
    }
    
    /**
     * @Route("/forms/save", methods="GET", name="save_report")
     *
     * @param Request   $request
     * @param FormModel $model   Nome do formulatio
     *
     * @return  Response
     */
    public function saveFunction(Request $request, FormModel $model): Response
    {
        $data = $request->query->all();
        $formName = $request->query->get('formName');
        $parameterName = sprintf('app.path.%s_report', $formName);
        $path = $this->getParameter($parameterName);
        $rootDir = $this->getParameter('kernel.root_dir');
        $reportPath = sprintf('%s/%s', $rootDir, $path);
        $result = $model->saveReport($data, $reportPath);
        $this->addFlash(
            $result->getType(),
            $result->getMessage()
        );

        return $this->redirect(sprintf('/forms/%s', $formName));
    }
    
    /**
     * Redireciona para o template de formulário requisitado
     *
     * @param string $formName Nome do furomulario usado para template
     *
     * @Route("/forms/{formName}", methods={"GET"})
     *
     * @return Response
     */
    public function findFormTemplate(Request $request, string $formName): Response
    {
        $fileNamePath = __DIR__.'/../../templates/form/'.$formName.'Form.html.twig';
        if (file_exists($fileNamePath)) {
            if (null !== $request->query->get('repofile')) {
                $reportFolder = sprintf(
                    '%s/%s/%s/%s.yaml',
                    $this->getParameter('kernel.root_dir'),
                    $this->getParameter('app.path.report_folder'),
                    ucwords($formName),
                    $request->query->get('repofile')
                );
                if (!is_string(file_get_contents($reportFolder))) {
                    throw new \Exception("Arquivo incorreto");
                }
                $reportData = Yaml::parse(file_get_contents($reportFolder));
                $formAllData = new NestedArraySeparator($reportData);
            }

            $requestData = [
                'formName' => $formName,
                'config' => new NonStaticConfig,
                'formFill' => isset($formAllData) ? $formAllData->getArrayInArray() : null,
                'formData' => isset($formAllData) ? $formAllData->getSimpleArray() : null
            ];
            if ($formName === 'order') {
                $requestData['products'] = $this->getDoctrine()->getManager()->getRepository(Product::class)->findAll();
                $requestData['payments'] = $this->getDoctrine()
                                                ->getManager()
                                                ->getRepository(PaymentType::class)
                                                ->findAll();
                $requestData['transporters'] = $this->getDoctrine()
                                                    ->getManager()
                                                    ->getRepository(Transporter::class)
                                                    ->findAll();
            }
            return $this->render('form/'.$formName.'Form.html.twig', $requestData);
        }

        throw new \Exception('Page not found');
    }

    /**
     * Redireciona para o template de formulário requisitado
     *
     * @param string $formName Nome do furomulario usado para template
     *
     * @Route("/forms/{formName}", methods={"POST"})
     *
     * @return Response
     */
    public function saveFormOnDb(Request $request, string $formName, FormModel $model): Response
    {
        if (!$this->isCsrfTokenValid('saveOnDb', $request->request->get('_csrf_token'))) {
            throw new CustomException('Token invalido');
        }
        $data = $request->request->all();
        $result = $model->reportResolver($formName, $data);
        $this->addFlash($result->getType(), $result->getMessage());

        return $this->redirectToRoute('form');
    }

    /**
     * Cria e exporta arquivos para criação de formulários.
     *
     * @param Request $request      objeto de requisição.
     * @param string  $formName     nome do formulário
     *                              usado como template.
     * @param Form    $form         objeto de formulário customizado.
     *
     * @Route("/forms/{formName}/print", methods={"GET", "POST"}, name="overlord")
     *
     * @return Response
     */
    public function printForm(Request $request, string $formName, Form $form): Response
    {
        $data = $request->query->all();
        if ($request->query->get('save')) {
            $data['formName'] = $formName;
            return $this->redirectToRoute('save_report', $data);
        }

        if (empty($data)) {
            throw new \Exception('Nenhum dado enviado');
        }
        $result = $form->returnSelectedFromType('show', $formName, $data);
        return new Response($result['template']);
    }

    /**
     * Recebe parametros, cria e envia para download arquivo pdf.
     *
     * @param Request $request  Objeto de requisição
     * @param string  $formName Nome for fomulário usado como template
     * @param Form    $form     Objeto de manipulação do Fourmulário
     *
     * @Route("/forms/{formName}/print/pdf", methods={"POST"})
     *
     * @return Response
     */
    public function sendPdfForm(Request $request, string $formName, Form $form): Response
    {
        $data = $request->request->all();
        
        if (empty($data)) {
            throw new \Exception('Nenhum dado enviado');
        }
        $result = $form->returnSelectedFromType('pdf', $formName, $data);

        //check if file exists
        $file = $result['pdf_path'];
        $filesystem = new Filesystem();
        if (!$filesystem->exists($file)) {
            throw $this->createNotFoundException('File not found.');
        }

        // send message
        $this->addFlash($result['type'], 'Sucesso!');

        // send file to download
        $response =  new BinaryFileResponse($file);
        //$response->trustXSendfileTypeHeader();
        $response->trustXSendfileTypeHeader();
        $response->setContentDisposition(ResponseHeaderBag::DISPOSITION_ATTACHMENT);

        return $response;
        //return $this->file($file); <-- Alternativa mais simples :)
    }

    /**
     * Redireciona para formulario e o completa com informações do banco de dados.
     *
     * @Route("/forms/{formName}/{idOrder<\d+>}")
     *
     * @return  Response
     */
    public function findFormFillWithId(string $formName, int $idOrder): Response
    {
        /** @var TravelAccountability */
        $regitry = $this->getDoctrine()->getRepository(TravelAccountability::class)->find($idOrder);
        if (!$regitry->getActive()) {
            throw new CustomException('Não se pode alterar item fechado.');
        }
        $pageName = sprintf('/form/%sForm.html.twig', $formName);
        $formFullName = sprintf('%s/templates%s', $this->getParameter('kernel.project_dir'), $pageName);

        if (!file_exists($formFullName)) {
            throw new FileNotFoundException("Pagina não existe");
        }

        if ($formName === 'travel-report') {
            $responseData = [
                'dataFill' => $this->getDoctrine()->getRepository(TravelAccountability::class)->find($idOrder)
            ];
        }

        return $this->render($pageName, $responseData ?? []);
    }

    /**
     * @Route("/forms/{formName}/{idReport<\d+>}/edit", methods={"POST"})
     */
    public function editTravelAccountability(Request $request, string $formName, int $idReport, FormModel $model): Response
    {
        /** @var TravelAccountability */
        $regitry = $this->getDoctrine()->getRepository(TravelAccountability::class)->find($idReport);
        if (!$regitry->getActive()) {
            throw new CustomException('Não se pode alterar item fechado.');
        }
        $data = $request->request->all();
        $result = $model->editReportResolver($formName, $data, $idReport);

        $this->addFlash(
            $result->getType(),
            $result->getMessage()
        );

        return $this->redirectToRoute('view_report_by_type', ['reportType' => $formName]);
    }

    /**
     * @Route("/forms/{formName}/{idOrder<\d+>}/terminate", methods={"POST"})
     */
    public function terminateTravel(Request $request, string $formName, int $idORder, Form $form): Response
    {
        $data = $request->request->all();
        $em = $this->getDoctrine()->getManager();
        /** @var TravelAccountability */
        $entity = $em->getRepository(TravelAccountability::class)->find($idORder);
        $entity->setActive(false);
        $em->merge($entity);
        $em->flush();

        $result = $form->returnSelectedFromType('show', $formName, $data);
        return new Response($result['template']);
    }

    /**
     * @Route("/forms/overlord/{formName}/{repoId}")
     */
    public function rxo(Request $request, $formName, $repoId)
    {
        $name = (new StringConvertions())->snakeToCamelCase($formName);
        $className = sprintf('App\Entity\%s', ucwords($name));
        /** @var TravelAccountability */
        $entity = $this->getDoctrine()->getRepository($className)->find($repoId);
        /* dump($entity->__toString(), Yaml::parse($entity->__toString()));
        die(); */
        return $this->redirectToRoute(
            'overlord',
            ['formName' => $formName, 'request' => Yaml::parse($entity->__toString())]
        );
    }
}
