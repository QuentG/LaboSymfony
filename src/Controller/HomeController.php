<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class HomeController extends AbstractController {

    public function home() {
        $name = 'Quentin';
        return $this->render('index.html.twig', [
            'name' => $name
        ]);
    }

    public function homeName($name) {
        return $this->render('index_name.html.twig', [
            'name' => $name
            ]);
    }


}