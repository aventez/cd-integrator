<?php

namespace App\Controller;

use App\Entity\Offer;
use App\Form\OfferType;
use App\Repository\OfferRepository;
use Knp\Component\Pager\PaginatorInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class OfferController extends AbstractController
{
    private const PER_PAGE_LIMIT = 20;

    private $repository;

    public function __construct(OfferRepository $repository)
    {
        $this->repository = $repository;
    }

    /**
     * @Route("/offer", name="integrator_offer_index")
     */
    public function index(Request $request, PaginatorInterface $paginator)
    {
        $offers = $this->repository->findAll();

        $pagination = $paginator->paginate(
            $offers,
            $request->query->get('page', 1),
            self::PER_PAGE_LIMIT
        );

        return $this->render('page/offer/index.html.twig', [
            'pagination' => $pagination
        ]);
    }

    /**
     * @Route("/offer/new", name="integrator_offer_new")
     */
    public function create(Request $request)
    {
        $offer = new Offer();
        $form = $this->createForm(OfferType::class, $offer);

        $form->handleRequest($request);
        if( $form->isSubmitted() && $form->isValid() ) {
            //$this->repository->save($offerToCreate);

            return $this->redirectToRoute('integrator_offer_index');
        }

        return $this->render('page/offer/create.html.twig', [
            'form' => $form->createView()
        ]);
    }
}
