<?php

namespace App\Controller;

use App\Application\Enum\ProductImportStatusEnum;
use App\Entity\ProductImport;
use App\Event\ImportAddedEvent;
use App\Form\ProductImportType;
use App\Repository\ProductImportRepository;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\EventDispatcher\EventDispatcherInterface;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProductImportController extends AbstractController
{
    private $translator;
    private $repository;
    private $dispatcher;
    private $logger;

    public function __construct
    (
        TranslatorInterface $translator,
        EventDispatcherInterface $dispatcher,
        ProductImportRepository $repository,
        LoggerInterface $logger
    )
    {
        $this->translator = $translator;
        $this->dispatcher = $dispatcher;
        $this->repository = $repository;
        $this->logger = $logger;
    }

    /**
     * @Route("/import/create", name="integrator_products_import_create")
     */
    public function create(Request $request): Response
    {
        $productImport = new ProductImport();
        $productImport->setStatus(ProductImportStatusEnum::NEW);

        $form = $this->createForm(ProductImportType::class, $productImport);

        $form->handleRequest($request);
        if( $form->isSubmitted() && $form->isValid() ) {
            $this->repository->save($productImport);

            $this->dispatcher->dispatch(new ImportAddedEvent($productImport), ImportAddedEvent::NAME);
            $this->addFlash(
                'success',
                $this->translator->trans('app.importProduct.create.flash.success'));

            return $this->redirectToRoute('integrator_products_import_index');
        }

        return $this->render('page/product_import/create.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/import", name="integrator_products_import_index")
     */
    public function index(Request $request): Response
    {
        return $this->render('page/product_import/index.html.twig', [
            'products_imports' => $this->repository->findAll()
        ]);
    }
}