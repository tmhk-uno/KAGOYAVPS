<?php
add_action( 'wp_enqueue_scripts', 'theme_enqueue_styles' );

function theme_enqueue_styles() {
  wp_enqueue_style( 'parent-style', get_template_directory_uri() . '/style.css' );
  wp_enqueue_style( 'child-style', get_stylesheet_directory_uri() . '/style.css', array('parent-style')
);
}

/* ページ共通のパンくずリストを削除 */
remove_action ( 'woocommerce_before_main_content' , 'woocommerce_breadcrumb' , 20); 
/* 商品詳細ページのメタ情報を削除 */
remove_action ( 'woocommerce_single_product_summary' , 'woocommerce_template_single_meta' , 40);
/* 商品詳細ページの関連商品を削除 */
remove_action ( 'woocommerce_after_single_product_summary' , 'woocommerce_output_related_products' , 20);
?>