<?php

namespace App\EventSubscriber;

use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\GetResponseEvent;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Config\Config;
use App\Model\ReportModel;
use App\Utils\Andresmei\CsrfToken;
use App\Utils\Andresmei\StdResponse;
use App\Utils\Andresmei\WriteBoletoReport;

class StartSubscriber extends AbstractController implements EventSubscriberInterface
{
    public function onKernelRequest(GetResponseEvent $event) 
    {
        $session = $event->getRequest()->getSession();
        if (!Config::getStatus()) {
            Config::start();
        }
        if (!array_key_exists('csrfToken', $session->all())) {
            $today = new \DateTime('now');
            $csrfToken = $this->setTokens($today);
            $session->set('csrfToken', $csrfToken);
        }
        return;
    }

    public function onKernelController(FilterControllerEvent $event)
    {
        $session = $event->getRequest()->getSession();
        $today = new \DateTime('now');

        if (!array_key_exists('csrfToken', $session->all())) {
            $csrfToken = $this->setTokens($today);
            $session->set('csrfToken', $csrfToken);
        }

        $this->checkIfFolderExists();

        $fileName = sprintf('Utils/ReportFiles/%s.yaml', $today->format('d-m-Y') );

        if (!file_exists(__DIR__.'/../'.$fileName)) {
            $this->writeDaylyBoletoRegister($today, $this);
        }

        return;
    }
    
    public static function getSubscribedEvents()
    {
        return [
            'kernel.request' => 'onKernelRequest',
            'kernel.controller' => 'onKernelController'
        ];
    }

    /**
     * [writeDaylyBoletoRegister description]
     *
     * @param   \DateTimeInterface              $date           [$date description]
     * @param   AbstractController              $controller     [$event description]
     *
     * @return  void                                            [return description]
     */
    private function writeDaylyBoletoRegister(\DateTimeInterface $date, AbstractController $controller): void
    {
        $model = new ReportModel($controller->getDoctrine()->getManager());
        $titulos = $model->getNonPayedBoletosByDate($date->format('Y-m-d'));
        
        $response = new StdResponse();

        foreach ($titulos as $titulo) {
            switch ($titulo->getBoletoStatus()) {
                case 0:
                    $response->naoPago[] = $titulo;       
                    break;
                case 2:
                    $response->atrasado[] = $titulo;
                    break;
                case 3;
                    $response->provisionado[] = $titulo;
                    break;
                case 4:
                    $response->conta[] = $titulo;
                    break;
                default:
                    $response->naoPago[] = $titulo;
                    break;
            }
        }
        $report = new WriteBoletoReport();
        $report->write($date, $response);
    }
    
    private function checkIfFolderExists(): void
    {
        $folders = [
            __DIR__.'/../Utils/ReportFiles' => ['ignore' => true]
        ];

        foreach ($folders as $folder => $ignore) {
            if (!file_exists($folder) && !is_dir($folder)) {
                mkdir($folder, 0777);
                if ($ignore['ignore'] !== false) {
                    file_put_contents(sprintf('%s/.gitignore', $folder), '');
                }
            }
        }
    }

    private function setTokens(\DateTimeInterface $date): CsrfToken
    {
        return new CsrfToken($date->format('d/m/Y H:m:s'));
    }
}
