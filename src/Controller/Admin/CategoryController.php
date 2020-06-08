<?php


	namespace App\Controller\Admin;

	use App\Entity\Category;
	use App\Form\CategoryType;
	use App\Repository\CategoryRepository;
	use Doctrine\ORM\EntityManagerInterface;
	use Sensio\Bundle\FrameworkExtraBundle\Configuration\ParamConverter;
	use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
	use Symfony\Component\HttpFoundation\Request;
	use Symfony\Component\Routing\Annotation\Route;

	/**
	 * @Route("/categorie")
	 */
	class CategoryController extends AbstractController
	{

		/**
		 * @Route("/")
		 */
		public function index(CategoryRepository $repository)
		{

			$catergories = $repository->findAll();

			return $this->render(
				'admin/category/index.html.twig',
				[
					'categories' => $catergories
				]
			);
		}

		/**
		 * @Route("/edition{id}", defaults={"id": null}, requirements={"id": "\d+"})
		 */
		public function edit(Request $request, EntityManagerInterface $manager, CategoryRepository $repository, $id)
		{
			if (is_null($id)) {
				$category = new Category();
			} else {
				$category = $repository->find($id);
			}
			$form = $this->createForm(CategoryType::class, $category);

			$form->handleRequest($request);

			dump($category);

			if ($form->isSubmitted()) {

				if ($form->isValid()) {

					$manager->persist($category);
					$manager->flush();

					$this->addFlash('success', 'La catégorie est enregistrée');

					return $this->redirectToRoute('app_admin_category_index');
				} else {
					$this->addFlash('error', 'Le formulaire contien des erreurs');
				}
			}

			return $this->render(
				'admin/category/edit.html.twig',
				[
					'form' => $form->createView()
				]
			);
		}

		/**
		 * @ParamConverter()
		 * @Route("/suppression/{id}", requirements={"id": "\d+"})
		 */
		public function delete(EntityManagerInterface $manager, Category $category)
		{

			$manager->remove($category);
			$manager->flush();

			$this->addFlash('sucess', 'La catégorie est supprimée');

			return $this-> redirectToRoute('app_admin_category_index');
		}
	}