<?php

require_once 'class-afsa-infos-cart-item.php';

/*
 * https://docs.woocommerce.com/wc-apidocs/source-class-WC_Cart.html
 */

use AFSA_Cart_Item_Infos as item;

class AFSA_Cart_Infos {

	public $items;

	public function __construct( $cart = null ) {

		$this->set_cart( $cart ?: $this->get_global_cart() );
	}

	public function get_global_cart() {
		global $woocommerce;
		return $woocommerce->cart;
	}

	public function set_cart( $cart ) {
		$this->cart  = $cart;
		$this->items = method_exists( $cart, 'get_cart_contents' ) ?
				$cart->get_cart() :
				$cart->cart_contents;
	}

	public function get_item_keys() {
		return empty( $this->items ) ?
				null :
				array_keys( $this->items );
	}

	public function get_item( $key ) {
		return empty( $this->items[ $key ] ) ?
				null :
				new item( $this, $key );
	}

	public function get_current_quantity_for_item_key( $key ) {
		return empty( $this->items[ $key ] ) ?
				null :
				$this->items[ $key ] ['quantity'];
	}

	/*
	  public function get_items_summary($cart = null) {

	  $cart = $cart ?: $this->get_current();

	  if (!$cart || !$cart->cart_contents)
	  return null;

	  $items = $cart->get_cart();
	  $produts_infos = [];
	  foreach ($items as $key => $item) {

	  $_product = wc_get_product($item['data']->get_id());
	  $produts_infos[$key] = [
	  'sku' => $_product->get_sku(),
	  'quantity' => $item['quantity'],
	  'name' => $_product->get_title(),
	  'price' => get_post_meta($item['product_id'], '_price', true)
	  ];
	  }

	  return $produts_infos;
	  }

	  static public function product_id_from_item_key($WC_cart, $item_key) {

	  $cart_items = $WC_cart->get_cart();
	  if (empty($cart_items) || empty($cart_items[$item_key]))
	  return 0;

	  $cart_item = $cart_items[$item_key];

	  // compatibility with WC +3
	  if (version_compare(WC_VERSION, '3.0', '<')) {
	  return $cart_item['data']->id; // Before version 3.0
	  }

	  return $cart_item['data']->get_id(); // For version 3 or more
	  }

	  static public function product_quantity_from_item_key($WC_cart, $item_key) {

	  $cart_items = $WC_cart->get_cart();
	  if (empty($cart_items) || empty($cart_items[$item_key]))
	  return 0;

	  $cart_item = $cart_items[$item_key];
	  return $cart_item['quantity'];
	  }



	 */

	/**
	 * Proccess card items that have been saved to DB
	 * and render js for added / deleted products
	 */
	// public function render_Bottom_JS() {
	// $js = '';
	// $cart_items = $this->db->getCard($this->cart_id, $this->shop_id);
	// if (!empty($cart_items)) {
	// foreach ($cart_items as $item) {
	// if (isset($item['quantity'])) {
	// if ($item['quantity'] > 0) {
	// $js .= 'aa.addToCart(' . json_encode($item) . ');';
	// } elseif ($item['quantity'] < 0) {
	// $item['quantity'] = abs($item['quantity']);
	// $js .= 'aa.removeFromCart(' . json_encode($item) . ');';
	// }
	// } else {
	// $js .= $item; // script
	// }
	// }
	// }
	// $this->db->deleteCard($this->cart_id, $this->shop_id);
	//
	// return $js;
	// }
}
