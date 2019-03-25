<?php
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

use Symfony\Component\Filesystem\Filesystem;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\ResponseHeaderBag;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\Product;
use App\Entity\PaymentType;
use App\Entity\Transporter;
use App\Utils\Andresmei\Form;
use App\Config\NonStaticConfig;

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
     * Redireciona para o template de formulário requisitado
     *
     * @param string $formName Nome do furomulario usado para template
     *
     * @Route("/forms/{formName}", methods={"GET"})
     *
     * @return Response
     */
    public function findFormTemplate(string $formName): Response
    {
        $fileNamePath = __DIR__.'/../../templates/form/'.$formName.'Form.html.twig';
        if (file_exists($fileNamePath)) {
            $requestData = [
                'formName' => $formName,
                'config' => new NonStaticConfig
            ];
            if ($formName === 'order') {
                $requestData['products'] = $this->getDoctrine()->getManager()->getRepository(Product::class)->findAll();
                $requestData['payments'] = $this->getDoctrine()->getManager()->getRepository(PaymentType::class)->findAll();
                $requestData['transporters'] = $this->getDoctrine()->getManager()->getRepository(Transporter::class)->findAll();
            }
            return $this->render('form/'.$formName.'Form.html.twig', $requestData);
        }

        throw new \Exception('Page not found');
    }

    /**
     * Cria e exporta arquivos para criação de formulários.
     *
     * @param Request $request      objeto de requisição.
     * @param string  $formName     nome do formulário
     *                              usado como template.
     * @param Form    $form         objeto de formulário customizado.
     *
     * @Route("/forms/{formName}/print", methods={"GET", "POST"})
     *
     * @return Response
     */
    public function printForm(Request $request, string $formName, Form $form): Response
    {
        $data = $request->query->all();
        if (empty($data)) {
            echo 'Nenhum dado enviado';
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
}
