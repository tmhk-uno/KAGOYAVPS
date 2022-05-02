<?php

namespace Plugin\RegionalShippingFeeCustom\Controller\Admin;

use Eccube\Controller\AbstractController;
use Plugin\RegionalShippingFeeCustom\Form\Type\Admin\RegionalShippingFeeType;
use Plugin\RegionalShippingFeeCustom\Repository\RegionalShippingFeeRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Template;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Form\Extension\Core\Type\TextareaType;
use Symfony\Component\Form\Extension\Core\Type\TextType;

class RegionalShippingFeeController extends AbstractController
{
    

    private $regionalShippingFeeRepository;

   
    public function __construct(
        RegionalShippingFeeRepository $regionalShippingFeeRepository)
    {
        $this->regionalShippingFeeRepository = $regionalShippingFeeRepository;
    }

    /**
     * @Route("/%eccube_admin_route%/regional_shipping_fee", name="admin_setting_shop_regional_shipping_fee")
     * @Template("@RegionalShippingFeeCustom/admin/RegionalShippingFee.twig")
     */
    public function index(Request $request)
    {
        $RegionalShippingFee = $this->regionalShippingFeeRepository->find(1);
        $builder = $this->formFactory->createBuilder(RegionalShippingFeeType::class,$RegionalShippingFee);
        $form = $builder->getForm();
        $form->handleRequest($request);

        if ($form->isSubmitted() && $form->isValid()) {
            log_info('特別地域送料編集開始');
            $RegionalShippingFee = $form->getData();

            log_info('特別地域送料登録開始');
            try {
                $this->entityManager->persist($RegionalShippingFee);
                $this->entityManager->flush($RegionalShippingFee);
                log_info('特別地域送料登録完了');
            } catch(\Exception $e) {
                log_error('特別地域送料登録エラー',[
                    'err' => $e
                ]);
            }

            $this->addSuccess('admin.common.save_complete', 'admin');

            return $this->redirectToRoute('admin_setting_shop_regional_shipping_fee');

        }

        return [
            'form' => $form->createView(),
            'RegionalShippingFee' => $RegionalShippingFee,
        ];
    }
}