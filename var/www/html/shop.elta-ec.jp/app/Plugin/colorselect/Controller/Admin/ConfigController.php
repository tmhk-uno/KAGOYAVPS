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

namespace Plugin\colorselect\Controller\Admin;

use Plugin\colorselect\Form\Type\Admin\ColorselectConfigType;
use Plugin\colorselect\Repository\ColorselectConfigRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ConfigController.
 */
class ConfigController extends \Eccube\Controller\AbstractController
{
    /**
     * @Route("/%eccube_admin_route%/colorselect/config", name="colorselect_admin_config")
     * @Template("@colorselect/admin/config.twig")
     *
     * @param Request $request
     * @param ColorselectConfigRepository $configRepository
     *
     * @return array
     */
    public function index(Request $request, ColorselectConfigRepository $configRepository)
    {
        $Config = $configRepository->get();
        $form = $this->createForm(ColorselectConfigType::class, $Config);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $Config = $form->getData();
            $this->entityManager->persist($Config);
            $this->entityManager->flush($Config);
            $this->addSuccess('colorselect.admin.save.complete', 'admin');
            return $this->redirectToRoute('colorselect_admin_config');
        }

        return [
            'form' => $form->createView(),
        ];
    }
}
