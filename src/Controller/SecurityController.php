<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\PasswordUpdate;
use App\Form\ChangePasswordType;
use App\Form\PasswordUpdateType;
use Symfony\Component\Mime\Email;
use App\Repository\UserRepository;
use App\Form\ForgottenPasswordType;
use Symfony\Component\Form\FormError;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;
use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
use Symfony\Component\Security\Csrf\TokenGenerator\TokenGeneratorInterface;

class SecurityController extends AbstractController
{
    /**
     * @Route("/login", name="app_login")
     * 
     */
    public function login(AuthenticationUtils $authenticationUtils): Response
    {
        if ($this->getUser()) {
            return $this->redirectToRoute('home');
        }

        // get the login error if there is one
        $error = $authenticationUtils->getLastAuthenticationError();
        // last username entered by the user
        $lastUsername = $authenticationUtils->getLastUsername();

        return $this->render('security/login.html.twig', ['last_username' => $lastUsername, 'error' => $error]);
    }

    /**
     * @Route("/logout", name="app_logout")
     */
    public function logout()
    {
        throw new \LogicException('This method can be blank - it will be intercepted by the logout key on your firewall.');
    }

    /**
     * @Route("/security/forgotten_password", name="forgotten_password")
     */
    public function forgottenPassword(User $user = null, EntityManagerInterface $manager, MailerInterface $mailer, Request $request, TokenGeneratorInterface $tokenGenerator, UserRepository $userRepository) : Response
    {
        //On instancie la variable $form

        $form = $this->createForm(ForgottenPasswordType::class);

        $form->handleRequest($request);

        $email = $form->get('emailResetPass')->getData();

        $user = $userRepository->findOneByEmail($email);

        if ($form->isSubmitted() && $form->isValid()) {
        
            if($request->isMethod('POST')){
                //On génère un token unique
                $token = $tokenGenerator->generateToken();
                try{
                    $user->setResetToken($token);

                    $manager->flush();

                }catch (\Exception $e){
                    $this->addFlash('Warning', $e->getMessage());
                    return $this->redirectToRoute('app_login');
                }

                //On génère une URL comportant la route permettant de changer le mot de passe

                $url = $this->generateUrl('reset_password', array('token' => $token), UrlGeneratorInterface::ABSOLUTE_URL);

                //On envoie le mail

                $message = (new Email())
                    ->from('testdlcolmar88@gmail.com')
                    ->to($user->getEmail())
                    ->subject('Récupération de mdp test')
                    ->text("Voici le lien de récupération de votre mot de passe :".$url, 'text/html')
                    ->html ("<p> Ceci est un test : ".$url, 'text/html'. "</p>");

                    $mailer->send($message);

                    $this->addFlash('info', 'Le mail de récupération de mdp a bien été envoyé, vous pouvez aller le consulter');
            }
            
        }

        return $this->render('security/forgottenPassword.html.twig', [
            'form' => $form->createView(),
            'title' => 'Réinitilisation du mdp'
            ]);
    }
    /**
     * @Route("reset_password/{token}", name="reset_password")
     */
    public function resetPassword(EntityManagerInterface $manager, Request $request, string $token, UserPasswordEncoderInterface $passwordEncoder, UserRepository $userRepository){

        $form = $this->createForm(ChangePasswordType::class);
        $form->handleRequest($request);

        //Redéfinir le user

        $user = $userRepository->findOneByResetToken($token);

        if($form->isSubmitted() && $form->isValid()){

            if($request->isMethod('POST')){

                $user->setResetToken(NULL);

                $newPassword = $form->get('password')->getData();

                $user->setPassword(
                    $passwordEncoder->encodePassword($user, $newPassword)
                );
                $manager->persist($user);
                $manager->flush();
                $this->addFlash('info', 'Votre mot de passe a bien été réinitialisé');

                return $this->redirectToRoute('app_login');
                
            }
        }

        return $this->render('security/resetPassword.html.twig', [
            'token' => $token,
            'form' => $form->createView(),
            'title' => "Réinitialisation du mot de passe"
        ]);
        
    }

    /**
     * modifier le mdp
     * 
     * @IsGranted("ROLE_USER")
     * 
     * @Route("/passwordUpdate", name="update_password")
     */
    public function update_user_password(EntityManagerInterface $manager, Request $request, UserPasswordEncoderInterface $passwordEncoder)
    {
        // Dans Symfony le $this->getUser permet de récupèrer le user connecté
        $user = $this->getUser();
        $id = $this->getUser()->getId();

        $passwordUpdate = new PasswordUpdate;

        $form = $this->createForm(PasswordUpdateType::class, $passwordUpdate);

        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // on va vérifier que l'ancien mdp du formulaire correspond bien à celui de l'utilisateur
            if (!password_verify($passwordUpdate->getOldPassword(), $user->getPassword())) {
                // message d'erreur
                $form->get('old_password')->addError(new FormError("Le mot de passe n'est pas bon."));
            } else {
                $newPassword = $passwordUpdate->getNewPassword();
                $password = $passwordEncoder->encodePassword($user, $newPassword);

                $user->setPassword($password);

                $manager->persist($user);
                $manager->flush();
                // message add flash de confirmation
                $this->addFlash('success', 'Le mot de passe a bien été changé.');
                return $this->redirectToRoute('user_show', ['id' => $id]);
            }
        }

        return $this->render('security/updatePassword.html.twig', [
            'form' => $form->createView(),
        ]);
    }
}