<?php

namespace App\Controller;

use App\Entity\Exercice;
use App\Form\ExerciceType;
use App\Entity\ExerciceLike;
use App\Entity\ExerciceComment;
use App\Form\ExerciceCommentType;

use App\Repository\LogoRepository;
use App\Repository\ExerciceRepository;

use Doctrine\ORM\EntityManagerInterface;
use App\Repository\ExerciceLikeRepository;
use App\Repository\ExerciceCommentRepository;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class ExerciceController extends AbstractController
{
     //Fonction liée aux exo

    /**
     * @Route("/report_e/{id}",options={"expose"=true}, name="report_e")
     */
    public function report(Request $request, EntityManagerInterface $manager,ExerciceCommentRepository $repo)
    {
        $bool = true;
        $id = $request->get('id');
         //Recup l'id du coms 
        $comment = $repo->report_exo($id,$bool);
        return $this->json([
            'code' => 200,
            'message' => "Signalé"
        ],200);
    }

    /**
    * @Route("/exercice", name="exercice")
    */
    public function exercice(ExerciceRepository $repo,LogoRepository $logo)
    {
        // Plus besoin grâce au use et on passe en paramètre $repo = $this->getDoctrine()->getRepository(Article::class);

        $exercices = $repo->findAll();
        $Logo = $logo->findAll();
        return $this->render('exercice/exercice.html.twig', [
            'controller_name' => 'BlogController',
            'exercices' => $exercices,
            'logo' => $Logo
        ]);
    }

    /**
     * @Route("/exercice/{id}", name="exercice_show")
     */
    public function show_exercice(Exercice $exercices, Request $request,EntityManagerInterface $manager,LogoRepository $logo)
    {
        $comment = new ExerciceComment();

        $form = $this->createForm(ExerciceCommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $comment->setCreatedAt(new \DateTime())
                    ->setExercice($exercices)
                    ->setAuthor($this->getUser()->getUsername()); //Tu correspond à l'article que j'ai en variable
            $manager->persist($comment);
            $manager->flush();
            $this->addFlash('success', 'Message envoyé !');


            return $this->redirectToRoute('exercice_show',[
                'id' =>$exercices->getId()
            ]);
        }
        //ici aussi on peux passer en arg le repo ArticleRepository $repo
        $repo = $this->getDoctrine()->getRepository(Exercice::class);
        $exercices = $repo->find($exercices);
        $d = 1;
        $Logo = $logo->findAll();
        return $this->render('exercice/show_exercice.html.twig',[
            'exercices' => $exercices,
            'commentForm' => $form->createView(),
            'logo' => $Logo
        ]);
    }


/**
 * Permet de liké ou unliké
 *
 * @Route("/exercice/{id}/like", name="exercice_like")
 * 
 * @param Exercice $exercice
 * @param ObjectManager $manager
 * @param ExerciceLikeRepository $likeRepo
 * @return Response
 */
    public function like(Exercice $exercice, EntityManagerInterface $manager, ExerciceLikeRepository $likeRepo ) : Response
    {
        $user = $this->getUser();

        //Si on essai de liker sans être connecté
        if(!$user) return $this->json([
            'code' => 403,
            'message' => "Non autorisé"
        ],403);


        if($exercice->isLikedByUser($user)){
            $like = $likeRepo->findOneBy([
                'exercice' => $exercice,
                'user' => $user
            ]);

            $manager->remove($like);
            $manager->flush();

            return $this->json([
                'code' => 200,
                'message' => 'Like supprimé',
                'likes' => $likeRepo->count(['exercice' => $exercice])
            ],200);
        }

        $like = New ExerciceLike();
            $like->setExercice($exercice)
                ->setUser($user);

            $manager->persist($like);   
            $manager->flush();

       return $this->json([
           'code' => 200,
            'message' => 'Its okay liké',
            'likes' => $likeRepo->count(['exercice' => $exercice ])
        ], 200);
    }


    /**
    * @Route("/favoris", name="show_favoris")
    */
    public function show_favoris(ExerciceRepository $repo,LogoRepository $logo)
    {
        $exercices = $repo->findAll();
        $Logo = $logo->findAll();

        return $this->render('blog/show_favoris.html.twig',[
            'exercices' => $exercices,
            'logo' => $Logo
        ]);
    }


}

