<?php

namespace App\EventSubscriber;

use App\Model\BoletoModel;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventSubscriberInterface;
use Symfony\Component\Yaml\Yaml;

final class StartSubscriber extends AbstractController implements EventSubscriberInterface
{
    private $model;

    public function __construct(BoletoModel $model)
    {
        $this->model = $model;
    }

    /**
     * @param BoletoModel $model
     * @return StartSubscriber
     * @throws \Exception
     */
    public function onKernelController(/**FilterControllerEvent $event */): self
    {
        $today = new \DateTime('now');
        $this->checkIfFolderExists();
        $fileName = sprintf('Utils/ReportFiles/%s.yaml', $today->format('d-m-Y'));
        if (! file_exists(__DIR__ . '/../' . $fileName)) {
            $this->model->writeDaylyBoletoRegister($today);
        }

        return $this;
    }

    public static function getSubscribedEvents()
    {
        return [
            'kernel.controller' => 'onKernelController',
        ];
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
