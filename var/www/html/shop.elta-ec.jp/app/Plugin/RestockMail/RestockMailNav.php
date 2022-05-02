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

namespace Plugin\RestockMail;

use Eccube\Common\EccubeNav;

class RestockMailNav implements EccubeNav
{
    public static function getNav()
    {
        return [
            'product' => [
                'children' => [
                    'restock_mail_review' => [
                        'name' => 'restock_mail.admin.restock_mail.title',
                        'url' => 'restock_mail_admin_restock_mail',
                    ],
                ],
                'content' => [
                    'children' => [
                        'plugin_RestockMail' => [
                            'name' => '再入荷お知らせ設定',
                            'url' => 'restock_mail_admin_config',
                        ],
                    ],
                ]
            ]
        ];
    }
}
