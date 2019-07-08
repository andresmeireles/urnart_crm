<?php

namespace App\EventSubscriber;

use App\Entity\Boleto;
use App\Model\BoletoModel;
use App\Model\ReportModel;
use App\Utils\Andresmei\StdResponse;
use App\Utils\Andresmei\WriteBoletoReport;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\HttpKernel\Event\FilterControllerEvent;
use Symfony\Component\Yaml\Yaml;

final class StartSubscriber extends AbstractController implements EventSubscriberInterface
{
    public function onKernelController(/**FilterControllerEvent $event */)
    {
        $today = new \DateTime('now');

        $this->checkIfFolderExists();

        $fileName = sprintf('Utils/ReportFiles/%s.yaml', $today->format('d-m-Y'));

        if (! file_exists(__DIR__ . '/../' . $fileName)) {
            $this->writeDaylyBoletoRegister($today, $this);
        }

        $getFile = file_get_contents(__DIR__ . '/../../config/system_menus.yaml');
        if (is_string($getFile)) {
            $menus = Yaml::parse($getFile);
            $this->get('twig')->addGlobal('menus', $menus);
        }

        return;
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.controller' => 'onKernelController',
        ];
    }

    /**
     * @param \DateTimeInterface $date
     * @param AbstractController $controller
     */
    private function writeDaylyBoletoRegister(
        \DateTimeInterface $date,
        AbstractController $controller
    ): void {
        $model = new BoletoModel($controller->getDoctrine()->getManager());
        $titulos = $model->getNonPayedBoletosByDate($date->format('Y-m-d'));

        $response = new StdResponse();

        foreach ($titulos as $titulo) {
            /** @var Boleto $titulo */
            switch ($titulo->getBoletoStatus()) {
                case 2:
                    $response->atrasado[] = $titulo;
                    break;
                case 3:
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
            __DIR__ . '/../Utils/ReportFiles' => ['ignore' => true],
        ];

        foreach ($folders as $folder => $ignore) {
            if (! file_exists($folder)) {
                if (! mkdir($folder, 0777) && ! is_dir($folder)) {
                    throw new \RuntimeException(sprintf('Directory "%s" was not created', $folder));
                }
                if ($ignore['ignore'] !== false) {
                    file_put_contents(sprintf('%s/.gitignore', $folder), '');
                }
            }
        }
    }
}
