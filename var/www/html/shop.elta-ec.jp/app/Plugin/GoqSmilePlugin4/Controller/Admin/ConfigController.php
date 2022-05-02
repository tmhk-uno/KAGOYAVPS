<?php

namespace Plugin\GoqSmilePlugin4\Controller\Admin;

use Eccube\Controller\AbstractController;
use Plugin\GoqSmilePlugin4\Form\Type\Admin\ConfigType;
use Plugin\GoqSmilePlugin4\Repository\ConfigRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;

class ConfigController extends AbstractController
{
    /**
     * @var ConfigRepository
     */
    protected $configRepository;

    /**
     * ConfigController constructor.
     *
     * @param ConfigRepository $configRepository
     */
    public function __construct(ConfigRepository $configRepository)
    {
        $this->configRepository = $configRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/GoqSmilePlugin4/config", name="goq_smile_plugin4_admin_config")
     * @Template("@GoqSmilePlugin4/admin/config.twig")
     */
    public function index(Request $request)
    {
        $Config = $this->configRepository->get();
        $form = $this->createForm(ConfigType::class, $Config);
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            $Config = $form->getData();
            $this->entityManager->persist($Config);
            $this->entityManager->flush($Config);
            $this->addSuccess('登録しました。', 'admin');

            return $this->redirectToRoute('goq_smile_plugin4_admin_config');
        }

        return [
            'form' => $form->createView(),
        ];
    }
}
