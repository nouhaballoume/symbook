<?php


namespace App\Controller;

use App\Entity\Commande;
use App\Repository\CommandeRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface; 

class AdminCommandeController extends AbstractController
{
    #[Route('/admin/commandes', name: 'admin_commandes_list')]
    public function listCommandes(
        Request $request,
        CommandeRepository $commandeRepository,
        PaginatorInterface $paginator
    ): Response {
        // Vérifier que l'utilisateur est admin
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        // Récupérer le statut de filtrage si présent
        $statut = $request->query->get('statut');

        // Créer la requête en fonction du filtre
        $qb = $commandeRepository->createQueryBuilder('c')
            ->orderBy('c.dateCommande', 'DESC');

        if ($statut && in_array($statut, ['en attente', 'en cours', 'terminée', 'payée'])) {
            $qb->andWhere('c.statut = :statut')
               ->setParameter('statut', $statut);
        }

        // Pagination
        $pagination = $paginator->paginate(
            $qb->getQuery(),
            $request->query->getInt('page', 1),
            10
        );

        return $this->render('admin/commandes/list.html.twig', [
            'pagination' => $pagination,
            'current_statut' => $statut
        ]);
    }

    #[Route('/admin/commande/{id}', name: 'admin_commande_show')]
    public function showCommande(Commande $commande): Response
    {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        return $this->render('admin/commandes/show.html.twig', [
            'commande' => $commande
        ]);
    }

    #[Route('/admin/commande/{id}/changer-statut', name: 'admin_commande_change_statut', methods: ['POST'])]
    public function changeStatut(
        Request $request,
        Commande $commande,
        EntityManagerInterface $em
    ): Response {
        $this->denyAccessUnlessGranted('ROLE_ADMIN');

        $nouveauStatut = $request->request->get('statut');
        
        if (in_array($nouveauStatut, ['en attente', 'en cours', 'terminée'])) {
            $commande->setStatut($nouveauStatut);
            $em->flush();
            
            $this->addFlash('success', 'Statut de la commande mis à jour avec succès.');
        } else {
            $this->addFlash('error', 'Statut invalide.');
        }

        return $this->redirectToRoute('admin_commande_show', ['id' => $commande->getId()]);
    }
}