<?php

namespace App\Controller;

use App\Application\Envelope\ProductRefreshProcessEnvelope;
use App\Application\Handler\Filter\ProductFilterHandler;
use App\Form\ProductEditType;
use App\Form\ProductFilterType;
use App\Repository\ProductRepository;
use Interop\Queue\Context;
use Knp\Component\Pager\PaginatorInterface;
use Psr\Log\LoggerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Contracts\Translation\TranslatorInterface;

class ProductController extends AbstractController
{
    private const PER_PAGE_LIMIT = 5;

    private $translator;
    private $repository;
    private $logger;
    private $context;

    public function __construct
    (
        TranslatorInterface $translator,
        ProductRepository $repository,
        LoggerInterface $logger,
        Context $context
    )
    {
        $this->translator = $translator;
        $this->repository = $repository;
        $this->logger = $logger;
        $this->context = $context;
    }

    /**
     * @Route("/products", name="integrator_products_index")
     */
    public function index(Request $request, PaginatorInterface $paginator, ProductFilterHandler $productFilterHandler): Response
    {
        $form = $this->createForm(ProductFilterType::class, null, [
            'method' => 'GET'
        ]);

        $form->handleRequest($request);

        $pagination = $paginator->paginate(
            $productFilterHandler->handle($form),
            $request->query->get('page', 1),
            self::PER_PAGE_LIMIT
        );

        return $this->render('page/product/index.html.twig', [
            'pagination' => $pagination,
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/products/edit/{id}", name="integrator_product_update")
     */
    public function update(Request $request): Response
    {
        $product = $this->repository->find($request->get('id'));
        if(!$product) throw new NotFoundHttpException('Product was not found.');

        $form = $this->createForm(ProductEditType::class, $product);
        $form->handleRequest($request);

        if($form->isSubmitted() && $form->isValid()) {
            $this->repository->save($product);

            $this->addFlash(
                'success',
                $this->translator->trans('app.product.update.flash.success')
            );

            return $this->redirectToRoute('integrator_products_index');
        }

        return $this->render('page/product/update.html.twig', [
            'form' => $form->createView()
        ]);
    }

    /**
     * @Route("/products/refresh", name="integrator_products_refresh")
     */
    public function refresh(Request $request): Response
    {
        $queue = $this->context->createQueue('refresh-product-to-process');

        $products = $this->repository->findAll();
        foreach($products as $product) {
            if($product->getSyncDisabled() != true) {
                $message = $this->context->createMessage(serialize(new ProductRefreshProcessEnvelope($product->getId())));
                $this->context->createProducer()->send($queue, $message);
            }
        }

        $this->addFlash('success', $this->translator->trans('app.product.refresh.flash.success'));
        return $this->redirectToRoute('integrator_products_index');
    }
}