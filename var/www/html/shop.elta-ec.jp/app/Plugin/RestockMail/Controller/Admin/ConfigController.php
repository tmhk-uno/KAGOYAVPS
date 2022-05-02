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

use Plugin\RestockMail\Form\Type\Admin\RestockMailConfigType;
use Plugin\RestockMail\Repository\RestockMailConfigRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;

/**
 * Class ConfigController.
 */
class ConfigController extends \Eccube\Controller\AbstractController
{
    /**
     * @Route("/%eccube_admin_route%/restock_mail/config", name="restock_mail_admin_config")
     * @Template("@RestockMail/admin/config.twig")
     *
     * @param Request $request
     * @param RestockMailConfigRepository $configRepository
     *
     * @return array
     */
    public function index(Request $request, RestockMailConfigRepository $configRepository)
    {
        $Config = $configRepository->get();
        $form = $this->createForm(RestockMailConfigType::class, $Config);
        $form->handleRequest($request);

        $mail_templates = realpath(dirname(__FILE__)."/../../Resource/template/default/Mail/restock.twig");
        $mtext = file_get_contents($mail_templates);

        if ($form->isSubmitted() && $form->isValid()) {
            $Config = $form->getData();
            $this->entityManager->persist($Config);
            $this->entityManager->flush($Config);

            $mtext = $_POST['mtext'];
            file_put_contents($mail_templates,$mtext);
            log_info('Product review config', ['status' => 'Success']);
            $this->addSuccess('restock_mail.admin.save.complete', 'admin');

            return $this->redirectToRoute('restock_mail_admin_config');
        }

        return [
            'form' => $form->createView(),
            'mtext' => $mtext
        ];
    }
}
