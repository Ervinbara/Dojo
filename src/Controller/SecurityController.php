<?php

namespace App\Controller;

use App\Entity\Ad;
use App\Entity\User;

use App\Form\FormadminType;
use App\Form\RegistrationType;
use App\Repository\LogoRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Doctrine\Common\Persistence\ObjectManager;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;

//USER
class SecurityController extends AbstractController
{
    /**
     * @Route("/inscription", name="security_registration")
     */
    public function registration(Request $request, EntityManagerInterface $manager,
    UserPasswordEncoderInterface $encoder,LogoRepository $logo)
    {

        $user = new User();
        $form = $this->createForm(RegistrationType::class, $user); 
        //En mettant user ici, on va relier les champs du formulaire aux champs de l'utilisateur
        //Ce qui va permttre de remplir l'user

        //handleRequest permet d'analyser la requête http qu'on est en train d'effectuer
        $form->handleRequest($request);
        if($form->isSubmitted() && $form->isValid()){
            $hash = $encoder->encodePassword($user, $user->getPassword());

            //On change le mdp par sa version crypté et là on l'insère dans la db
            $user->setPassword($hash);

            $manager->persist($user);//Prépare toi à l'insérer dans la base
            $manager->flush();//Fait le !  
            
            return $this->redirectToRoute('security_login');
        }
        $Logo = $logo->findAll();
        return $this->render('security/registation.html.twig',[
            'form' => $form->createView(),
            'logo' => $Logo
        ]);
    }


    /**
     * @Route("/connexion", name="security_login")
     */
    public function login(LogoRepository $logo)
    {
        $Logo = $logo->findAll();
        return $this->render('security/login.html.twig',[
            'logo' => $Logo 
        ]);
    }

    /**
     * @Route("/deconnexion"), name="security_logout")
     */
    public function logout(){}
}
