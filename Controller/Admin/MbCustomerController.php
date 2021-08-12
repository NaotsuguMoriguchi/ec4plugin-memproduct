<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) EC-CUBE CO.,LTD. All Rights Reserved.
 *
 * http://www.ec-cube.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\membership\Controller\Admin;


use Doctrine\DBAL\Exception\ForeignKeyConstraintViolationException;
use Doctrine\ORM\QueryBuilder;
use Eccube\Common\Constant;
use Eccube\Controller\AbstractController;
use Eccube\Entity\Master\CsvType;
use Eccube\Event\EccubeEvents;
use Eccube\Event\EventArgs;
use Eccube\Form\Type\Admin\SearchCustomerType;
use Eccube\Repository\CustomerRepository;
use Eccube\Repository\Master\PageMaxRepository;
use Eccube\Repository\Master\PrefRepository;
use Eccube\Repository\Master\SexRepository;
use Eccube\Service\CsvExportService;
use Eccube\Service\MailService;
use Eccube\Util\FormUtil;
use Knp\Component\Pager\Paginator;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;
use Symfony\Component\Translation\TranslatorInterface;

use Eccube\Controller\Admin\Customer\CustomerController;


class MbCustomerController extends CustomerController
{
    public function __construct(
      PageMaxRepository $pageMaxRepository,
      CustomerRepository $customerRepository,
      SexRepository $sexRepository,
      PrefRepository $prefRepository,
      MailService $mailService,
      CsvExportService $csvExportService
  ) {
      $this->pageMaxRepository = $pageMaxRepository;
      $this->customerRepository = $customerRepository;
      $this->sexRepository = $sexRepository;
      $this->prefRepository = $prefRepository;
      $this->mailService = $mailService;
      $this->csvExportService = $csvExportService;
  }
    /**
     * @Route("/%eccube_admin_route%/customer", name="admin_customer")
     * @Route("/%eccube_admin_route%/customer/page/{page_no}", requirements={"page_no" = "\d+"}, name="admin_customer_page")
     * @Template("@membership/admin/customer/index.twig")
     */
    public function index(Request $request, $page_no = null, Paginator $paginator)
    {
      $session = $this->session;
      $builder = $this->formFactory->createBuilder(SearchCustomerType::class);

      $event = new EventArgs(
          [
              'builder' => $builder,
          ],
          $request
      );
      $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_CUSTOMER_INDEX_INITIALIZE, $event);

      $searchForm = $builder->getForm();

      $pageMaxis = $this->pageMaxRepository->findAll();
      $pageCount = $session->get('eccube.admin.customer.search.page_count', $this->eccubeConfig['eccube_default_page_count']);
      $pageCountParam = $request->get('page_count');
      if ($pageCountParam && is_numeric($pageCountParam)) {
          foreach ($pageMaxis as $pageMax) {
              if ($pageCountParam == $pageMax->getName()) {
                  $pageCount = $pageMax->getName();
                  $session->set('eccube.admin.customer.search.page_count', $pageCount);
                  break;
              }
          }
      }

      if ('POST' === $request->getMethod()) {
          $searchForm->handleRequest($request);
          if ($searchForm->isValid()) {
              $searchData = $searchForm->getData();
              $page_no = 1;

              $session->set('eccube.admin.customer.search', FormUtil::getViewData($searchForm));
              $session->set('eccube.admin.customer.search.page_no', $page_no);
          } else {
              return [
                  'searchForm' => $searchForm->createView(),
                  'pagination' => [],
                  'pageMaxis' => $pageMaxis,
                  'page_no' => $page_no,
                  'page_count' => $pageCount,
                  'has_errors' => true,
              ];
          }
      } else {
          if (null !== $page_no || $request->get('resume')) {
              if ($page_no) {
                  $session->set('eccube.admin.customer.search.page_no', (int) $page_no);
              } else {
                  $page_no = $session->get('eccube.admin.customer.search.page_no', 1);
              }
              $viewData = $session->get('eccube.admin.customer.search', []);
          } else {
              $page_no = 1;
              $viewData = FormUtil::getViewData($searchForm);
              $session->set('eccube.admin.customer.search', $viewData);
              $session->set('eccube.admin.customer.search.page_no', $page_no);
          }
          $searchData = FormUtil::submitAndGetData($searchForm, $viewData);
      }

      /** @var QueryBuilder $qb */
      $qb = $this->customerRepository->getQueryBuilderBySearchData($searchData);

      $event = new EventArgs(
          [
              'form' => $searchForm,
              'qb' => $qb,
          ],
          $request
      );
      $this->eventDispatcher->dispatch(EccubeEvents::ADMIN_CUSTOMER_INDEX_SEARCH, $event);

      $pagination = $paginator->paginate(
          $qb,
          $page_no,
          $pageCount
      );
      return [
          'searchForm' => $searchForm->createView(),
          'pagination' => $pagination,
          'pageMaxis' => $pageMaxis,
          'page_no' => $page_no,
          'page_count' => $pageCount,
          'has_errors' => false,
      ];
    }   
}
