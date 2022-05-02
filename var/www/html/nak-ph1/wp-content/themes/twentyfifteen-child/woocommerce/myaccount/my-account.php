<?php
/**
 * My Account page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/myaccount/my-account.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce\Templates
 * @version 3.5.0
 */

defined( 'ABSPATH' ) || exit;

/**
 * My Account navigation.
 *
 * @since 2.6.0
 */
do_action( 'woocommerce_account_navigation' ); ?>

<div class="woocommerce-MyAccount-content">
<h3>
<div><a href="<?php
$user = wp_get_current_user();
echo $user->user_url; //ユーザーURL
?>" title="<?php
$user = wp_get_current_user();
echo $user->display_name; //ユーザーID
?>の写真ページ" ><?php
$user = wp_get_current_user();
echo $user->display_name; //ユーザーID
?>の写真ページ</a></div>
</h3>

<h3>
<div><a href="http://133.18.208.167/nak-ph1/product-category/gather/">全景・集合写真のページ</a></div>
</h3>

	<?php
		/**
		 * My Account content.
		 *
		 * @since 2.6.0
		 */
		do_action( 'woocommerce_account_content' );
	?>
</div>
