<?php

	namespace App\Controller;

	use App\Entity\User;
	use App\Form\RegistrationType;
	use Doctrine\ORM\EntityManagerInterface;
	use phpDocumentor\Reflection\Types\This;
	use PhpParser\Node\Expr\Empty_;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Routing\Annotation\Route;
	use Symfony\Component\Security\Core\Encoder\UserPasswordEncoderInterface;
	use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

	class UserController extends AbstractController
	{
		/**
		 * @Route("/user", name="user")
		 */
		public function index()
		{
			return $this->render('user/index.html.twig', [
				'controller_name' => 'UserController',
			]);
		}

		/**
		 * @Route("/inscription")
		 */
		public function register(
			Request $request,
			UserPasswordEncoderInterface $passwordEncoder,
			EntityManagerInterface $manager
		)
		{
			$user = new User();
			$form = $this->createForm(RegistrationType::class, $user);

			$form->handleRequest($request);

			if ($form->isSubmitted()) {
				if ($form->isValid()) {
					$encodedPassword = $passwordEncoder->encodePassword(
						$user,
						$user->getPlainPassword()
					);

					$user->setPassword($encodedPassword);

					$manager->persist($user);
					$manager->flush();

					$this->addFlash('success', 'Votre compte est créé');

					return $this->redirectToRoute('app_index_index');
				} else {
					$this->addFlash('error', 'Le formulaire contien des erreurs');
				}
			}

			return $this->render(
				'user/register.html.twig',
				[
					'form' => $form->createView()
				]
			);
		}

		/**
		 * @Route("/connexion")
		 */
		public function login(AuthenticationUtils $authenticationUtils)
		{
			$error = $authenticationUtils->getLastAuthenticationError();

			$lastUsername = $authenticationUtils->getLastUsername();

			if (!empty($error)) {
				$this->addFlash('error', 'Identifiants incorrect');
			}

			return $this->render(
				'user\login.html.twig',
				[
					'last_username' => $lastUsername
				]
			);

		}

		/**
		 * @Route("/deconnexion")
		 */
		public function logout()
		{

		}
	}
