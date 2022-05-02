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

namespace Plugin\colorselect\Controller;

use Eccube\Controller\AbstractController;
use Doctrine\ORM\EntityManagerInterface;
use Eccube\Common\EccubeConfig;
use Eccube\Form\Type\Admin\SearchProductType;
use Plugin\Rank4\Entity\RankProduct;
use Plugin\Rank4\Form\Type\RankProductType;
use Plugin\Rank4\Repository\RankProductRepository;
use Plugin\Rank4\Service\RankService;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\Form\Form;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpKernel\Exception\UnsupportedMediaTypeHttpException;
use Symfony\Component\Routing\Annotation\Route;
use Eccube\Form\DataTransformer;

/**
 * Class RankController.
 */
class ColorselectController extends AbstractController
{




    /**
     * OsusumeProductType constructor.
     *
     * @param EntityManagerInterface $entityManager
     */
    public function __construct( )
    {
    }




    /**
     * colorselect rank with ajax.
     *
     * @param Request     $request
     *
     * @throws \Exception
     *
     * @return Response
     *
     * @Route("/%eccube_admin_route%/plugin/colorselect/setimg", name="plugin_colorselect_set")
     */
    public function setimg(Request $request)
    {
        $response = array();
        $images = $request->files->get('admin_product');
            $allowExtensions = ['gif', 'jpg', 'jpeg', 'png'];
            $files = [];
            if (count($images) > 0) {
                foreach ($images as $img) {
                    foreach ($img as $image) {
                        //ファイルフォーマット検証
                        $mimeType = $image->getMimeType();
                        if (0 !== strpos($mimeType, 'image')) {
                            throw new UnsupportedMediaTypeHttpException();
                        }
                        // 拡張子
                        $extension = $image->getClientOriginalExtension();
                        if (!in_array($extension, $allowExtensions)) {
                            throw new UnsupportedMediaTypeHttpException();
                        }
                        $filename = date('mdHis').uniqid('_').'.'.$extension;
                        $image->move($this->eccubeConfig['eccube_save_image_dir'], $filename);
                        $files[] = $filename;
                    }
                }
            }
            $response['files']= $files;
            $response['status']= "OK";
        $json = json_encode($response);
        return new Response($json);
    }



}
