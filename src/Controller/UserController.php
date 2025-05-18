<?php

namespace App\Controller;

use App\Entity\User;
use App\Form\UserType;
use App\Repository\UserRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Attribute\Route;
use Symfony\Component\Validator\Constraints\IsTrue;

use Doctrine\ORM\EntityManagerInterface;
class UserController extends AbstractController
{
    #[Route('/admin/users/all', name: 'admin_users_index')]
    public function index(UserRepository $userRepository): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        // Récupérer uniquement les utilisateurs avec ROLE_USER
        $users = $userRepository->createQueryBuilder('u')
            ->where('u.roles LIKE :role')
            ->setParameter('role', '%"ROLE_ADMIN"%')
            ->getQuery()
            ->getResult();

        return $this->render('user/index.html.twig', [
            'users' => $users,  // Utilisez la variable filtrée $users ici
        ]);
    }

    #[Route('/admin/users/{id}', name: 'admin_users_show', methods: ['GET'])]
    public function show(User $user): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        return $this->render('user/show.html.twig', [
            'user' => $user,
        ]);
    }

   #[Route('/admin/users/{id}/edit', name: 'admin_users_edit', methods: ['GET', 'POST'])]
    public function edit(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        $form = $this->createForm(UserType::class, $user);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            // No need to explicitly call persist() for existing entities
            $entityManager->flush();

            $this->addFlash('success', 'Utilisateur modifié avec succès');
            return $this->redirectToRoute('admin_users_index', [], Response::HTTP_SEE_OTHER);
        }

        return $this->render('user/edit.html.twig', [
            'user' => $user,
            'form' => $form->createView(),
        ]);
    }

    #[Route('/admin/users/{id}/delete', name: 'admin_users_delete', methods: ['POST'])]
    public function delete(Request $request, User $user, EntityManagerInterface $entityManager): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');
        
        if ($this->isCsrfTokenValid('delete' . $user->getId(), $request->request->get('_token'))) {
            $entityManager->remove($user);
            $entityManager->flush();
            $this->addFlash('success', 'Utilisateur supprimé avec succès');
        }

        return $this->redirectToRoute('admin_users_index', [], Response::HTTP_SEE_OTHER);
    }
}