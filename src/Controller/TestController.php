<?php

namespace App\Controller;

use App\Entity\Tag;
use Exception;
use DateTime;
use App\Repository\TagRepository;
use App\Repository\UserRepository;
use App\Repository\ProjectRepository;
use App\Entity\Project;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\Persistence\ManagerRegistry;


#[Route('/test')]
class TestController extends AbstractController
{
    #[Route('/tag', name: 'app_test')]
    public function tag(ManagerRegistry $doctrine, TagRepository $repository): Response
    {

        // récupération de l'entity manager
        $em = $doctrine->getManager();

        $tags = $repository->findAllOrderByName();
        dump($tags);

        $tag1 = $repository->find(1);
        dump($tag1);

        $tag12 = $repository->find(12);
        dump($tag12);

        //recherche par name
        $tags = $repository->findBy([
            'name' => 'HTML',
        ]);
        dump($tags);

        $tags = $repository->findByKeyword('deleniti');
        dump($tags);

        // création d'un nouvel objet tag
        $tag = new Tag();
        $tag->setName("tag de test");
        $tag->setDescription("Ce tag est un test");

        // avant enregistrement, l'objet n'a pas d'ID
        dump($tag->getId());

        // enregistrement dans la BDD
        $em->persist($tag);
        $em->flush();

        // après enregistrement, l'objet possède un ID
        dump($tag->getId());

        // $tag1 = $repository->find(1);
        // $tag1->setName("Un autre nom de tag de test");
        // $tag1->setDescription(null);
        
        // $em->flush();
        // dump($tag1);
        
        $tag14 = $repository->find(14);
        
        // gestion des exceptions
        try {
            // suppression d'un objet
            $em->remove($tag14);
            $em->flush();
        } catch (Exception $e) {
            dump($e->getMessage());
            dump($e->getCode());
            dump($e->getFile());
            dump($e->getLine());
            dump($e->getTraceAsString());
            
        }
        
        dump($tag14);
        
        // $tag1 = $repository->find(1);
        
        // if($tag1) {
        //     foreach( $tag1->getStudents() as $student){
        //         dump($student);
        //     };
            
        //     foreach($tag1->getProjects() as $project) {
        //         dump($tag1->getProjects());
        //     };
            
        //     // suppression d'un objet 
        //     $em->remove($tag1);
        //     $em->flush();
        // };
        
        
        exit();
    }
    
    #[Route('/user', name: 'app_test_user')]
    public function user(UserRepository $repository): Response {

        $user = $repository->findAllStudent();
        dump($user);

        $admins = $repository->findAllAdmin();
        dump($admins);

        exit();
    }

    #[Route('/project', name: 'app_test_project')]
    public function project(ManagerRegistry $doctrine): Response {

        $em = $doctrine->getManager();
        $repository = $doctrine->getRepository(Project::class);

        $startDate = DateTime::createFromFormat('d/m/Y', '01/03/2023');
        $endDate = DateTime::createFromFormat('d/m/Y', '01/05/2023');
        $projects = $repository->findByDeliveryDateBetween($startDate, $endDate);

        dump($projects);

        $project1 = $repository->find(1);


        
        if($project1) {
            foreach($project1->getStudents() as $student) {
                $student->setProject(null);
            };

            $em->remove($project1);
            $em->flush();
        };

        exit();
    }

}
