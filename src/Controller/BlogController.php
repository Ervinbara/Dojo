<?php

namespace App\Controller;

use App\Entity\Article;
use App\Entity\Comment;
use App\Entity\Exercice;

use App\Entity\PostLike;
use App\Form\ArticleType;

use App\Form\CommentType;
use App\Form\QuestionType;
use App\Entity\QuestionFrom;
use App\Repository\LogoRepository;
use App\Repository\UploadRepository;
use App\Repository\CommentRepository;
use App\Repository\ExerciceRepository;
use App\Repository\PostLikeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Repository\ArticleRepository; //Va nous permettre dans la focntion index de passé en paramètre le repo dont on à besoin



class BlogController extends AbstractController
{
    /**
     * @Route("/report/{id}",options={"expose"=true}, name="report")
     */
    public function report(Request $request, EntityManagerInterface $manager,CommentRepository $repo)
    {
        $bool = true;
        $id = $request->get('id');
         //Recup l'id du coms 
        $comment = $repo->report($id,$bool);
        return $this->json([
            'code' => 200,
            'message' => "Signalé"
        ],200);
    }

    /**
     * @Route("/blog", name="blog")
     */
    public function index(ArticleRepository $repo,PaginatorInterface $paginator,Request $request,LogoRepository $logo, EntityManagerInterface $manager)
    {
        //On stocke dans $article tout les élèments du repo, pour pouvoir les afficher dans le twig
        $articles = $paginator->paginate(
            $repo->findAll(),
            $request->query->getInt('page', 1),
            3
        );
        $Logo = $logo->findAll();
        

        return $this->render('blog/index.html.twig', [
            'controller_name' => 'BlogController',
            'articles' => $articles,
            'logo' => $Logo,
            
        ]);
    }

    /**
     * @Route("/", name="home")
     */
    public function home(UploadRepository $repo,ArticleRepository $repo_article,
    ExerciceRepository $repo_exercice,LogoRepository $logo,Request $request)
    {
        
        $limit = 1;
        $Exo = 2;
        $last_image = $repo_article->findBy(array(), null, $limit);
        $last_exercice = $repo_exercice->findBy(array(), null, $Exo);
        $uploads = $repo->findAll();
        $Logo = $logo->findAll();
        $question = new QuestionFrom();
        $form = $this->createForm(QuestionType::class, $question);

        $form->handleRequest($request);
        
        if($form->isSubmitted() && $form->isValid()){
            $question->setCreatedAt(new \DateTime());
                    // ->setMail($article)
                    // ->setAuthor($this->getUser()->getUsername()); //Tu correspond à l'article que j'ai en variable
            $manager->persist($question);
            $manager->flush();
            $this->addFlash('success', 'Votre question est envoyé ! Merci !');


            return $this->redirectToRoute('home');
        }

        return $this->render('blog/home.html.twig',[
            'controller_name' => 'BlogController',
            'uploads' => $uploads,
            'last_image' => $last_image,
            'last_exercice' =>$last_exercice,
            'questionForm' => $form->createView(),
            'logo' => $Logo
        ]);
    }


    /**
     * @Route("/blog/{id}", name="blog_show")
     */
    public function show(Article $article, Request $request, EntityManagerInterface $manager,LogoRepository $logo)
    {
        $comment = new Comment();

        $form = $this->createForm(CommentType::class, $comment);

        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()){
            $comment->setCreatedAt(new \DateTime())
                    ->setArticle($article)
                    ->setAuthor($this->getUser()->getUsername()); //Tu correspond à l'article que j'ai en variable
            $manager->persist($comment);
            $manager->flush();
            $this->addFlash('success', 'Message envoyé !');

            return $this->redirectToRoute('blog_show',[
                'id' =>$article->getId()
            ]);
        } 
        //ici aussi on peux passer en arg le repo ArticleRepository $repo
        $repo = $this->getDoctrine()->getRepository(Article::class);
        $article = $repo->find($article);
        $Logo = $logo->findAll();
        return $this->render('blog/show.html.twig',[
            'article' => $article,
            'commentForm' => $form->createView(),
            'logo' => $Logo

        ]);
    }

 
    /**
     * Permet de liké ou unliké un article
     *
     * @Route("/blog/{id}/like", name="post_like")
     * 
     * 
     * @param Article $article
     * @param ObjectManager $manager
     * @param PostLikeRepository $likeRepo
     * @return Response
     */
    public function like(Article $article, EntityManagerInterface $manager, PostLikeRepository $likeRepo ) : Response
    {
        $user = $this->getUser();
 
        //Si on essai de liker  sans être connecté
        if(!$user) return $this->json([
            'code' => 403,
            'message' => "Non autorisé"
        ],403);

        //Si l'article est déjà "liké", si on réappui ça supprime le like
        if($article->isLikedByUser($user)){
            $like = $likeRepo->findOneBy([
                'post' => $article,
                'user' => $user
            ]);
 
            $manager->remove($like);
            $manager->flush();

            return $this->json([
                'code' => 200,
                'message' => 'Like supprimé',
                'likes' => $likeRepo->count(['post' => $article])
            ],200);
        }

        $like = New PostLike();
            $like->setPost($article)
                ->setUser($user);

            $manager->persist($like);   
            $manager->flush();

    return $this->json([
        'code' => 200,
            'message' => 'Its okay liké',
            'likes' => $likeRepo->count(['post' => $article ])
        ], 200);
    }


    /**
    * @Route("/likes", name="show_likes")
    */
    public function show_likes(ArticleRepository $repo,LogoRepository $logo,PaginatorInterface $paginator,Request $request,EntityManagerInterface $manager)
    {   
        $articles = $repo->findAll();
        // $articles = $repo->findAll();
        $Logo = $logo->findAll();
        return $this->render('blog/show_likes.html.twig',[
            'articles' => $articles,
            'logo' => $Logo
            
        ]);
    }


    

}
