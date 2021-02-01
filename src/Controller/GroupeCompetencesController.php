<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\GroupeCompetences;

class GroupeCompetencesController extends AbstractController
{

    public function addGrpeCompetences(Request $request)
    {
        /*//On récupère la requête(sous-format tab
        $requestContent = json_decode($request->getContent(),true);
        //On contrôle si le libelle et la description existe
        if(isset($requestContent['libelle']) && !empty($requestContent['libelle'])
            && isset($requestContent['description']) && !empty($requestContent['description'])
            && isset($requestContent['addCompetence']) && !empty($requestContent['addCompetence']))
        {
            //dd($requestContent);
            //on crée le nouveau groupe de competences
            $grpeCompetence = new GroupeCompetences();
            $grpeCompetence->setLibelle($requestContent['libelle'])
                            ->setDescription($requestContent['description']);
            //On parcours le tableau addCompetence
            foreach ($requestContent['addCompetence'] as $item){
                //dd($item);
                //On ajoute une compétence via son id
                if(isset($item['id']) && !empty($item['id'])){

                }
            }
        }*/
    }
}
