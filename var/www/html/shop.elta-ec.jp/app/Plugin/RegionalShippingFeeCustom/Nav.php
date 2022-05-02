<?php

namespace Plugin\RegionalShippingFeeCustom;

use Eccube\Common\EccubeNav;

class Nav implements EccubeNav
{
    /**
     * @return array
     */
    public static function getNav()
    {
        return [
            'setting' => [
                'children' => [
                    'shop' => [
                        'children' => [
                            'shop_regional_shipping_fee' => [
                                'name' => 'admin.setting.shop.regional_shipping_fee_info',
                                'url' => 'admin_setting_shop_regional_shipping_fee'
                            ]
                        ]
                    ]
                ]
            ]
        ];
    }
}