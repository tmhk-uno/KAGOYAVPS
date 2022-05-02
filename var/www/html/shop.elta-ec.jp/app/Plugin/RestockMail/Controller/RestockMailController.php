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

namespace Plugin\RestockMail\Controller;

use Eccube\Controller\AbstractController;
use Eccube\Entity\Master\ProductStatus;
use Eccube\Entity\Product;
use Eccube\Repository\Master\ProductListMaxRepository;
use Eccube\Repository\ProductClassRepository;
use Eccube\Repository\ProductRepository;
use Knp\Component\Pager\PaginatorInterface;
use Plugin\RestockMail\Entity\RestockMail;
use Plugin\RestockMail\Entity\RestockMailStatus;
use Plugin\RestockMail\Form\Type\RestockMailType;
use Plugin\RestockMail\Repository\RestockMailRepository;
use Plugin\RestockMail\Repository\RestockMailStatusRepository;
use Plugin\RestockMail\Repository\RestockMailConfigRepository;

use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\NotFoundHttpException;
use Eccube\Entity\MailHistory;
use Eccube\Repository\BaseInfoRepository;
use Eccube\Repository\MailHistoryRepository;


use Doctrine\Common\Persistence\ObjectManager;
use Doctrine\DBAL\Connection;


/**
 * Class RestockMailController front.
 */
class RestockMailController extends AbstractController
{
    /**
     * @var RestockMailStatusRepository
     */
    private $RestockMailStatusRepository;

    /**
     * @var RestockMailRepository
     */
    private $RestockMailRepository;

    /**
     * @var \Twig_Environment
     */
    protected $twig;

    protected $baseInfo;

    protected $ObjectManager ;
    /**
     * RestockMailController constructor.
     *
     * @param RestockMailStatusRepository $productStatusRepository
     * @param RestockMailRepository $productReviewRepository
     */
    public function __construct(
        \Twig_Environment $twig,
        \Swift_Mailer $mailer,
        RestockMailStatusRepository $RestockMailStatusRepository,
        BaseInfoRepository $baseInfoRepository,
        RestockMailRepository $RestockMailRepository,
        ProductClassRepository $productClassRepository,
        ProductRepository $productRepository,
        ObjectManager $ObjectManager

    ) {
        $this->productClassRepository = $productClassRepository;
        $this->productRepository = $productRepository;
        $this->RestockMailStatusRepository = $RestockMailStatusRepository;
        $this->RestockMailRepository = $RestockMailRepository;
        $this->mailer = $mailer;
        $this->twig = $twig;
        $this->baseInfo = $baseInfoRepository->get();
        $this->ObjectManager =  $ObjectManager;
    }

    /**
     * @Route("/restock_mail/get", name="restock_mail_get")
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function rget(Request $request)
    {
        $response = array();
                    if ($this->isGranted('ROLE_USER')) {

                        $Customer = $this->getUser();
                        $product_class_id = $_REQUEST['cid'];
                        $Connection = $this->ObjectManager->getConnection();

                        $sql = 'SELECT count(id) FROM plg_restock_mail WHERE product_class_id =? and status_id = 2 and customer_id = ?';
                        $count = $Connection->fetchColumn($sql, [$product_class_id,$Customer->getId()]);



                        $response['customer']= $Customer->getId();
                        $response['product_class_id']= $product_class_id;

                        $response['count']= $count;

                        $response['status']= "OK";
                    }else{
                        $response["status"]="nologin";
                    }
        $json = json_encode($response);
        return new Response($json);
    }


    /**
     * @Route("/restock_mail/set", name="restock_mail_set")
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */

    public function rset(Request $request)
    {
        $response = array();
        if ($this->isGranted('ROLE_USER')) {
            $Customer = $this->getUser();

        $RestockMail = new RestockMail();
        $Product = $this->productRepository->find($_REQUEST["pid"]);

        $RestockMail->setCustomer($Customer);
        $RestockMail->setProduct($Product);
        $RestockMail->setProductCode($_REQUEST["code"]);
        $RestockMail->setProductClassID($_REQUEST["classid"]);

        $RestockMail->setStatus($this->RestockMailStatusRepository->find(RestockMailStatus::nosend));
        $this->entityManager->persist($RestockMail);
        $this->entityManager->flush($RestockMail);
            $response['status']= "OK";
        }else{
            $response['status']= "FAIL";
        }
        $json = json_encode($response);
        return new Response($json);
    }

    /**
     * @Route("/restock_mail/send", name="restock_mail_send")
     *
     * @param Request $request
     *
     * @return RedirectResponse|Response
     */
    public function send(Request $request,PaginatorInterface $paginator,RestockMailConfigRepository $configRepository)
    {

        $Config = $configRepository->get();
        $searchData =array();
        $searchData['status'] = array(2);
        $qb = $this->RestockMailRepository->getQueryBuilderBySearchData($searchData);

        $status = "0";
        $send =0;

        $pagination = $paginator->paginate(
            $qb,
            1,
            150
        );

        foreach ($pagination as $line){
            $body = $this->twig->render('@RestockMail/default/Mail/restock.twig', [
                'Customer' => $line->getCustomer(),
                'Product' => $line->getProduct(),
                "Baseurl" => (empty($_SERVER["HTTPS"]) ? "http://" : "https://") . $_SERVER["HTTP_HOST"].""
            ]);
            $cid = (int) $line->getProductClassID();
            $qb2 = $this->productClassRepository->createQueryBuilder("pc")->where('pc.id = :id')->setParameter('id', $cid);
            $product = $qb2->getQuery()->getResult();
            if(count($product ) == 1){
            if($product[0]->getStock() > 0){
                $message = (new \Swift_Message());
                $message->setSubject($Config->getSubject());
                $message->setFrom(array($this->baseInfo->getEmail01() => $this->baseInfo->getShopName()));
                $message->setTo(array($line->getCustomer()->getEmail() =>$line->getCustomer()->getName01()));
                $message->setBody($body);
                $status = $this->mailer->send($message);
                $send++;
                $line->setSendDate(new \DateTime("now"));
                $line->setStatus($this->RestockMailStatusRepository->find(RestockMailStatus::send));
                $this->entityManager->flush($line);
            }
            }else{
            }
        }
        $response['status'] = $status;
        $response['send'] = $send;
        $json = json_encode($response);
        return new Response($json);
    }




}
