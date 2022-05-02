<?php

/*
 * This file is part of EC-CUBE
 *
 * Copyright(c) LOCKON CO.,LTD. All Rights Reserved.
 *
 * http://www.lockon.co.jp/
 *
 * For the full copyright and license information, please view the LICENSE
 * file that was distributed with this source code.
 */

namespace Plugin\RestockMail\Controller\Admin;

use Eccube\Controller\AbstractController;
use Eccube\Repository\Master\PageMaxRepository;
use Eccube\Repository\ProductClassRepository;
use Eccube\Repository\ProductRepository;
use Eccube\Util\FormUtil;
use Knp\Component\Pager\PaginatorInterface;
use Plugin\RestockMail\Entity\RestockMail;
use Plugin\RestockMail\Entity\RestockMailConfig;
use Plugin\RestockMail\Entity\RestockMailStatus;
use Plugin\RestockMail\Form\Type\Admin\RestockMailSearchType;
use Plugin\RestockMail\Form\Type\Admin\RestockMailType;
use Plugin\RestockMail\Repository\RestockMailConfigRepository;
use Plugin\RestockMail\Repository\RestockMailRepository;
use Plugin\RestockMail\Repository\RestockMailStatusRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Method;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\StreamedResponse;

use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Connection;


/**
 * Class RestockMailController admin.
 */
class RestockMailController extends AbstractController
{
    /**
     * @var PageMaxRepository
     */
    protected $pageMaxRepository;

    /**
     * @var RestockMailRepository
     */
    protected $RestockMailRepository;

    /**
     * @var RestockMailConfigRepository
     */
    protected $RestockMailConfigRepository;

    protected $RestockMailStatusRepository;

    protected $ObjectManager ;


    /**
     * @var ProductRepository
     */
    protected $productRepository;


    /**
     * @var ProductClassRepository
     */
    protected $productClassRepository;


    /**
     * RestockMailController constructor.
     *
     * @param PageMaxRepository $pageMaxRepository
     * @param RestockMailRepository $RestockMailRepository
     * @param RestockMailConfigRepository $RestockMailConfigRepository
     */
    public function __construct(
        PageMaxRepository $pageMaxRepository,
        RestockMailRepository $RestockMailRepository,
        RestockMailStatusRepository $RestockMailStatusRepository,
        RestockMailConfigRepository $RestockMailConfigRepository,
        ObjectManager $ObjectManager,
                ProductRepository $productRepository,
        ProductClassRepository $productClassRepository

    ) {
        $this->pageMaxRepository = $pageMaxRepository;
        $this->RestockMailRepository = $RestockMailRepository;
        $this->RestockMailStatusRepository =$RestockMailStatusRepository;
        $this->RestockMailConfigRepository = $RestockMailConfigRepository;
        $this->ObjectManager =  $ObjectManager;
        $this->productClassRepository = $productClassRepository;
        $this->productRepository = $productRepository;

    }



    /**
     * Search function.
     *
     * @Route("/%eccube_admin_route%/restock_mail/csv", name="restock_mail_admin_restock_mail_csv")
     * @Template("@RestockMail/admin/index.twig")
     *
     * @param Request $request
     *
     * @return array
     */
    public function csv(Request $request, PaginatorInterface $paginator)
    {


        $searchData = $this->session->get('restock_mail.admin.restock_mail.search.searchData');

        $qb = $this->RestockMailRepository->getQueryBuilderBySearchData($searchData);

        $pagination = $paginator->paginate(
            $qb,
            1,
            99999
        );
        $list = array();
        $list[0]= array('id','ユーザID',"ユーザ名",'メールアドレス','商品URL',"商品コード","商品名",'登録日','送信日','購入日',"状態");
        $Connection = $this->ObjectManager->getConnection();
        foreach ($pagination as $line){
            $id = $line->getId();
            $customer_id =  $line->getCustomer()->getId() ;
            $product_id =  $line->getProduct()->getId() ;
            $product_class_id =  $line->getProductClassID();
            $sql = 'SELECT max(d.create_date) as bdate  FROM dtb_order as d,  dtb_order_item  as doi   WHERE d.id = doi.order_id  and d.customer_id = ? and doi.product_class_id = ?';


           $date = $Connection->fetchColumn($sql, [$customer_id,$product_class_id]);
            $baseurl = (empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"]."/";
            $list[]= array(
                $id,
                $line->getCustomer()->getId(),
                $line->getCustomer()->getName01() .$line->getCustomer()->getName02(),
                $line->getCustomer()->getEmail(),
                $baseurl."/products/detail/". $line->getProduct()->getId() ,
                $line->getProductCode() ,
                $line->getProduct()->getName(),
                $line->getCreateDate()->format('Y-m-d H:i:s'),
                ($line->getSendDate() == null)?"":$line->getSendDate()->format('Y-m-d H:i:s'),
                $date,
                (string) $line->getStatus());
        }

        header('Content-Type: application/octet-stream');
        header('Content-Disposition: attachment; filename=restockmail.csv');
        header('Content-Transfer-Encoding: binary');
        $fp = fopen('php://temp/maxmemory:'.(12*1024*1024),'r+');
        foreach ($list as $fields) {
            fputcsv($fp, $fields);
        }
        rewind($fp);
        $csv = stream_get_contents($fp);
        echo mb_convert_encoding($csv,"SJIS", "UTF-8");


        exit();
    }
    /**
     * Search function.
     *
     * @Route("/%eccube_admin_route%/restock_mail/", name="restock_mail_admin_restock_mail")
     * @Route("/%eccube_admin_route%/restock_mail/page/{page_no}", requirements={"page_no" = "\d+"}, name="restock_mail_admin_restock_mail_page")
     * @Template("@RestockMail/admin/index.twig")
     *
     * @param Request $request
     * @param null $page_no
     *
     * @return array
     */
    public function index(Request $request, $page_no = null, PaginatorInterface $paginator)
    {

        $builder = $this->formFactory->createBuilder(RestockMailSearchType::class);
        $searchForm = $builder->getForm();
        // 値を保存

        $pageMaxis = $this->pageMaxRepository->findAll();
        $pageCount = $this->session->get(
            'restock_mail.admin.restock_mail.search.page_count',
            $this->eccubeConfig['eccube_default_page_count']
        );
        $pageCountParam = $request->get('page_count');
        if ($pageCountParam && is_numeric($pageCountParam)) {
            foreach ($pageMaxis as $pageMax) {
                if ($pageCountParam == $pageMax->getName()) {
                    $pageCount = $pageMax->getName();
                    $this->session->set('restock_mail.admin.restock_mail.search.page_count', $pageCount);
                    break;
                }
            }
        }

        if ('POST' === $request->getMethod()) {
            $searchForm->handleRequest($request);
            if ($searchForm->isValid()) {
                $searchData = $searchForm->getData();
                $page_no = 1;

                $this->session->set('restock_mail.admin.restock_mail.search', FormUtil::getViewData($searchForm));
                $this->session->set('restock_mail.admin.restock_mail.search.page_no', $page_no);
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
                    $this->session->set('restock_mail.admin.restock_mail.search.page_no', (int) $page_no);
                } else {
                    $page_no = $this->session->get('restock_mail.admin.restock_mail.search.page_no', 1);
                }
                $viewData = $this->session->get('restock_mail.admin.restock_mail.search', []);
            } else {
                $page_no = 1;
                $viewData = FormUtil::getViewData($searchForm);
                $this->session->set('restock_mail.admin.restock_mail.search', $viewData);
                $this->session->set('restock_mail.admin.restock_mail.search.page_no', $page_no);
            }
            $searchData = FormUtil::submitAndGetData($searchForm, $viewData);
        }

        $this->session->set('restock_mail.admin.restock_mail.search.searchData',$searchData);
        $qb = $this->RestockMailRepository->getQueryBuilderBySearchData($searchData);
        $pagination = $paginator->paginate(
            $qb,
            $page_no,
            $pageCount
        );
        $bdate = array();
        $Connection = $this->ObjectManager->getConnection();
foreach ($pagination as $line){
    $id = $line->getId();
    $customer_id =  $line->getCustomer()->getId() ;
    $product_id =  $line->getProduct()->getId() ;
    $product_class_id =  $line->getProductClassID();
    $sql = 'SELECT max(d.create_date) as bdate  FROM dtb_order as d,  dtb_order_item  as doi   WHERE d.id = doi.order_id  and d.customer_id = ? and doi.product_class_id = ?';
    $date = $Connection->fetchColumn($sql, [$customer_id,$product_class_id]);
    $bdate[$id] =$date;
}
        return [
            'searchForm' => $searchForm->createView(),
            'pagination' => $pagination,
            'bdate' => $bdate,
            'pageMaxis' => $pageMaxis,
            'page_no' => $page_no,
            'page_count' => $pageCount,
            'has_errors' => false,
        ];
    }

    /**
     * Product review delete function.
     *
     * @Method("DELETE")
     * @Route("%eccube_admin_route%/restock_mail/{id}/delete", name="restock_mail_admin_restock_mail_delete")
     *
     * @param Request $request
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function delete(RestockMail $RestockMail)
    {
        $this->isTokenValid();

        $this->entityManager->remove($RestockMail);
        $this->entityManager->flush($RestockMail);
        $this->addSuccess('restock_mail.admin.delete.complete', 'admin');

        log_info('Product review delete', ['id' => $RestockMail->getId()]);

        return $this->redirect($this->generateUrl('restock_mail_admin_restock_mail_page', ['resume' => 1]));
    }


    /**
     * Product review delete function.
     *
     * @Method("SENDS")
     * @Route("%eccube_admin_route%/restock_mail/{id}/sends", name="restock_mail_admin_restock_mail_send")
     *
     * @param Request $request
     * @param int $id
     *
     * @return RedirectResponse
     */
    public function sends(RestockMail $RestockMail)
    {
        $this->isTokenValid();
        $RestockMail->setSendDate(new \DateTime("now"));
        $RestockMail->setStatus($this->RestockMailStatusRepository->find(RestockMailStatus::send));

        $this->entityManager->flush($RestockMail);

        return $this->redirect($this->generateUrl('restock_mail_admin_restock_mail_page', ['resume' => 1]));
    }


}
