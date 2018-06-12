<?php

namespace App\Controller;

use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\Controller;

class StorageController extends Controller
{
    /**
     * @Route("/storage", name="storage")
     */
    public function index()
    {
        return $this->render('storage/index.html.twig', [
            'controller_name' => 'StorageController',
        ]);
    }
}
