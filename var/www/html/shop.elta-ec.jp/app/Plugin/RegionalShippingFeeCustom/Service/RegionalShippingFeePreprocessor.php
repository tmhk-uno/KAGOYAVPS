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

namespace Plugin\RegionalShippingFeeCustom\Service;

use Doctrine\ORM\EntityManagerInterface;
use Eccube\Entity\BaseInfo;
use Eccube\Entity\DeliveryFee;
use Eccube\Entity\ItemHolderInterface;
use Eccube\Entity\Master\OrderItemType;
use Eccube\Entity\Master\TaxDisplayType;
use Eccube\Entity\Master\TaxType;
use Eccube\Entity\Order;
use Eccube\Entity\OrderItem;
use Eccube\Entity\Shipping;
use Eccube\Repository\BaseInfoRepository;
use Eccube\Repository\DeliveryFeeRepository;
use Eccube\Repository\TaxRuleRepository;
use Eccube\Annotation\ShoppingFlow;
use Eccube\Service\PurchaseFlow\ItemHolderPreprocessor;
use Eccube\Service\PurchaseFlow\PurchaseContext;
use Eccube\Service\PurchaseFlow\ItemCollection;
use Eccube\Service\PurchaseFlow\Processor\DeliveryFeePreprocessor as BaseDeliveryFeePreprocessor;
use Plugin\RegionalShippingFeeCustom\Repository\RegionalShippingFeeRepository;

/**
 * 送料無料条件を適用します.
 *  
 * @author My-System Co.,LTD. <info@my-system.co.jp>
 * 
 * ご注文手続きページで実行されるようアノテーションを設定
 * @ShoppingFlow
 */

class RegionalShippingFeePreprocessor implements ItemHolderPreprocessor
{
    /** @var BaseInfo */
    protected $BaseInfo;

    /**
     * @var EntityManagerInterface
     */
    protected $entityManager;

    /**
     * @var TaxRuleRepository
     */
    protected $taxRuleRepository;

    /**
     * @var DeliveryFeeRepository
     */
    protected $deliveryFeeRepository;

    /**
     * RegionalShippingFeePreprocessor constructor.
     *
     * @param BaseInfoRepository $baseInfoRepository
     * @param EntityManagerInterface $entityManager
     * @param TaxRuleRepository $taxRuleRepository
     * @param DeliveryFeeRepository $deliveryFeeRepository
     */

    public function __construct(
        BaseInfoRepository $baseInfoRepository,
        EntityManagerInterface $entityManager,
        TaxRuleRepository $taxRuleRepository,
        DeliveryFeeRepository $deliveryFeeRepository,
        RegionalShippingFeeRepository $regionalShippingFeeRepository
    ) {
        $this->BaseInfo = $baseInfoRepository->get();
        $this->entityManager = $entityManager;
        $this->taxRuleRepository = $taxRuleRepository;
        $this->deliveryFeeRepository = $deliveryFeeRepository;
        $this->RegionalShippingFeeRepository = $regionalShippingFeeRepository;
    }

    /**
     * @param ItemHolderInterface $itemHolder
     * @param PurchaseContext $context
     *
     * @throws \Doctrine\ORM\NoResultException
     */

    public function process(ItemHolderInterface $itemHolder, PurchaseContext $context)
    {
        $this->removeDeliveryFeeItem($itemHolder);
        $this->saveDeliveryFeeItem($itemHolder);
    }

    private function removeDeliveryFeeItem(ItemHolderInterface $itemHolder)
    {
        foreach ($itemHolder->getShippings() as $Shipping) {
            foreach ($Shipping->getOrderItems() as $item) {
                if ($item->getProcessorName() == DeliveryFeePreprocessor::class) {
                    $Shipping->removeOrderItem($item);
                    $itemHolder->removeOrderItem($item);
                    $this->entityManager->remove($item);
                }
            }
        }
    }

    /**
     * @param ItemHolderInterface $itemHolder
     *
     * @throws \Doctrine\ORM\NoResultException
     */
    private function saveDeliveryFeeItem(ItemHolderInterface $itemHolder)
    {
        $DeliveryFeeType = $this->entityManager
            ->find(OrderItemType::class, OrderItemType::DELIVERY_FEE);
        $TaxInclude = $this->entityManager
            ->find(TaxDisplayType::class, TaxDisplayType::INCLUDED);
        $Taxation = $this->entityManager
            ->find(TaxType::class, TaxType::TAXATION);

        /** @var Order $Order */
        $Order = $itemHolder;
        
        /* @var Shipping $Shipping */
        foreach ($Order->getShippings() as $Shipping) {
             // 送料の計算
             $deliveryFeeProduct = 0;
             if ($this->BaseInfo->isOptionProductDeliveryFee()) {
                 /** @var OrderItem $item */
                 foreach ($Shipping->getOrderItems() as $item) {
                     if (!$item->isProduct()) {
                         continue;
                     }
                     $deliveryFeeProduct += $item->getProductClass()->getDeliveryFee() * $item->getQuantity();
                 }
             }

            /** @var DeliveryFee $DeliveryFee */
            $DeliveryFee = $this->deliveryFeeRepository->findOneBy([
                'Delivery' => $Shipping->getDelivery(),
                'Pref' => $Shipping->getPref(),
            ]);

            $regionalShippingPC = $this->RegionalShippingFeeRepository->get();
            $Item = $Shipping->getOrderItems();
            
            // 郵便番号が特定地域郵便番号に指定されているかチェック
            if (in_array($Shipping->getPostalCode(),(array)$regionalShippingPC->getPostalCodeLists(),true)) {
                 // お届け先ごとに判定
                foreach ($itemHolder->getShippings() as $Shipping) {
                    // 送料無料の受注明細かどうか確認
                    foreach($Shipping->getOrderItems() as $Item) {
                        // 送料明細を探す
                        if ($Item->getProcessorName() == BaseDeliveryFeePreprocessor::class) {
                            // 送料明細の数量が0の場合は加算B
                            if ($Item->getQuantity() == 0) {
                                    $addFee = $regionalShippingPC->getFeeB();
                                // 送料明細の数量がある場合は加算A 
                                } else {
                                    $addFee = $regionalShippingPC->getFeeA();
                                        }
                                    }
                                }
                            }
                    
            // 郵便番号が特定地域に指定されていない場合送料加算しない        
            } else {
                $addFee = 0;
            }

            // 税率取得（参考src/Eccube/Controller/Service/PurchaseFlow/TaxRuleService.php）
            $TaxRule = $this->taxRuleRepository->getByRule();
            $OrderItem = new OrderItem();

            // 送料、基本税率を追加する（参考src/Eccube/Controller/Service/PurchaseFlow/Processor/DeliveryFeePreprocessor.php）
            $OrderItem->setProductName($DeliveryFeeType->getName())
                ->setPrice($addFee)
                ->setQuantity(1)
                ->setOrderItemType($DeliveryFeeType)
                ->setShipping($Shipping)
                ->setOrder($itemHolder)
                ->setTaxDisplayType($TaxInclude)
                ->setTaxType($Taxation)
                ->setTaxRate($TaxRule->getTaxRate())
                ->setProcessorName(DeliveryFeePreprocessor::class);

            $itemHolder->addItem($OrderItem);
            $Shipping->addOrderItem($OrderItem);
        }
    }
}