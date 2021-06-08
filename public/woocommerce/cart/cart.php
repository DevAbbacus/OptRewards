<?php
/**
 * Cart Page
 *
 * This template can be overridden by copying it to yourtheme/woocommerce/cart/cart.php.
 *
 * HOWEVER, on occasion WooCommerce will need to update template files and you
 * (the theme developer) will need to copy the new files to your theme to
 * maintain compatibility. We try to do this as little as possible, but it does
 * happen. When this occurs the version of the template file will be bumped and
 * the readme will list any important changes.
 *
 * @see     https://docs.woocommerce.com/document/template-structure/
 * @package WooCommerce/Templates
 * @version 3.8.0
 */

defined( 'ABSPATH' ) || exit;

do_action( 'woocommerce_before_cart' ); 

?>

<form class="woocommerce-cart-form" action="<?php echo esc_url( wc_get_cart_url() ); ?>" method="post">
	<?php do_action( 'woocommerce_before_cart_table' ); ?>

	<table class="shop_table shop_table_responsive cart woocommerce-cart-form__contents" cellspacing="0">
		<thead>
			<tr>
				<th class="product-remove">&nbsp;</th>
				<th class="product-thumbnail">&nbsp;</th>
				<th class="product-name"><?php esc_html_e( 'Product', 'woocommerce' ); ?></th>
				<th class="product-price"><?php esc_html_e( 'Price', 'woocommerce' ); ?></th>
				<th class="product-quantity"><?php esc_html_e( 'Quantity', 'woocommerce' ); ?></th>
				<th class="product-subtotal"><?php esc_html_e( 'Subtotal', 'woocommerce' ); ?></th>
			</tr>
		</thead>
		<tbody>
			<?php do_action( 'woocommerce_before_cart_contents' ); ?>

			<?php
			foreach ( WC()->cart->get_cart() as $cart_item_key => $cart_item ) {
				$_product   = apply_filters( 'woocommerce_cart_item_product', $cart_item['data'], $cart_item, $cart_item_key );
				$product_id = apply_filters( 'woocommerce_cart_item_product_id', $cart_item['product_id'], $cart_item, $cart_item_key );

				if ( $_product && $_product->exists() && $cart_item['quantity'] > 0 && apply_filters( 'woocommerce_cart_item_visible', true, $cart_item, $cart_item_key ) ) {
					$product_permalink = apply_filters( 'woocommerce_cart_item_permalink', $_product->is_visible() ? $_product->get_permalink( $cart_item ) : '', $cart_item, $cart_item_key );
					?>
					<tr class="woocommerce-cart-form__cart-item <?php echo esc_attr( apply_filters( 'woocommerce_cart_item_class', 'cart_item', $cart_item, $cart_item_key ) ); ?>">

						<td class="product-remove">
							<?php
								echo apply_filters( // phpcs:ignore WordPress.Security.EscapeOutput.OutputNotEscaped
									'woocommerce_cart_item_remove_link',
									sprintf(
										'<a href="%s" class="remove" aria-label="%s" data-product_id="%s" data-product_sku="%s">&times;</a>',
										esc_url( wc_get_cart_remove_url( $cart_item_key ) ),
										esc_html__( 'Remove this item', 'woocommerce' ),
										esc_attr( $product_id ),
										esc_attr( $_product->get_sku() )
									),
									$cart_item_key
								);
							?>
						</td>

						<td class="product-thumbnail">
						<?php
						$thumbnail = apply_filters( 'woocommerce_cart_item_thumbnail', $_product->get_image(), $cart_item, $cart_item_key );

						if ( ! $product_permalink ) {
							echo $thumbnail; // PHPCS: XSS ok.
						} else {
							printf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $thumbnail ); // PHPCS: XSS ok.
						}
						?>
						</td>

						<td class="product-name" data-title="<?php esc_attr_e( 'Product', 'woocommerce' ); ?>">
						<?php
						if ( ! $product_permalink ) {
							echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', $_product->get_name(), $cart_item, $cart_item_key ) . '&nbsp;' );
						} else {
							echo wp_kses_post( apply_filters( 'woocommerce_cart_item_name', sprintf( '<a href="%s">%s</a>', esc_url( $product_permalink ), $_product->get_name() ), $cart_item, $cart_item_key ) );
						}

						do_action( 'woocommerce_after_cart_item_name', $cart_item, $cart_item_key );

						// Meta data.
						echo wc_get_formatted_cart_item_data( $cart_item ); // PHPCS: XSS ok.

						// Backorder notification.
						if ( $_product->backorders_require_notification() && $_product->is_on_backorder( $cart_item['quantity'] ) ) {
							echo wp_kses_post( apply_filters( 'woocommerce_cart_item_backorder_notification', '<p class="backorder_notification">' . esc_html__( 'Available on backorder', 'woocommerce' ) . '</p>', $product_id ) );
						}
						?>
						</td>

						<td class="product-price" data-title="<?php esc_attr_e( 'Price', 'woocommerce' ); ?>">
							<?php
								echo apply_filters( 'woocommerce_cart_item_price', WC()->cart->get_product_price( $_product ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
							?>
						</td>

						<td class="product-quantity" data-title="<?php esc_attr_e( 'Quantity', 'woocommerce' ); ?>">
						<?php
						if ( $_product->is_sold_individually() ) {
							$product_quantity = sprintf( '1 <input type="hidden" name="cart[%s][qty]" value="1" />', $cart_item_key );
						} else {
							$product_quantity = woocommerce_quantity_input(
								array(
									'input_name'   => "cart[{$cart_item_key}][qty]",
									'input_value'  => $cart_item['quantity'],
									'max_value'    => $_product->get_max_purchase_quantity(),
									'min_value'    => '0',
									'product_name' => $_product->get_name(),
								),
								$_product,
								false
							);
						}

						echo apply_filters( 'woocommerce_cart_item_quantity', $product_quantity, $cart_item_key, $cart_item ); // PHPCS: XSS ok.
						?>
						</td>

						<td class="product-subtotal" data-title="<?php esc_attr_e( 'Subtotal', 'woocommerce' ); ?>">
							<?php
								echo apply_filters( 'woocommerce_cart_item_subtotal', WC()->cart->get_product_subtotal( $_product, $cart_item['quantity'] ), $cart_item, $cart_item_key ); // PHPCS: XSS ok.
							?>
						</td>
					</tr>
					<?php
				}
			}
			?>

			<?php do_action( 'woocommerce_cart_contents' ); ?>

			<tr>
				<td colspan="6" class="actions">

					<?php if ( wc_coupons_enabled() ) { ?>
						<div class="coupon">
							<label for="coupon_code"><?php esc_html_e( 'Coupon:', 'woocommerce' ); ?></label> <input type="text" name="coupon_code" class="input-text" id="coupon_code" value="" placeholder="<?php esc_attr_e( 'Coupon code', 'woocommerce' ); ?>" /> <button type="submit" class="button" name="apply_coupon" value="<?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?>"><?php esc_attr_e( 'Apply coupon', 'woocommerce' ); ?></button>
							<?php do_action( 'woocommerce_cart_coupon' ); ?>
						</div>
					<?php } ?>

					<button type="submit" class="button" name="update_cart" value="<?php esc_attr_e( 'Update cart', 'woocommerce' ); ?>"><?php esc_html_e( 'Update cart', 'woocommerce' ); ?></button>

					<?php do_action( 'woocommerce_cart_actions' ); ?>

					<?php wp_nonce_field( 'woocommerce-cart', 'woocommerce-cart-nonce' ); ?>
				</td>
			</tr>

			<?php do_action( 'woocommerce_after_cart_contents' ); ?>
		</tbody>
	</table>
	<?php do_action( 'woocommerce_after_cart_table' ); ?>
</form>

<?php do_action( 'woocommerce_before_cart_collaterals' ); ?>

<div class="cart-collaterals">
	<?php 
	    $email = '';
	    $phone_no = '';
	    if(is_user_logged_in()){

			$current_user = wp_get_current_user();
			$customerId = $current_user->ID;
			 
	        $firstName = $current_user->user_firstname;
	        $lastName = $current_user->user_lastname;
        
    	    $enrollmentOption = get_user_meta( $customerId, 'user_reward_program_enable', true);

	        if($enrollmentOption == "1"){
	        	$email = $current_user->user_email;
		        $phone_no  = get_user_meta( $customerId, 'user_phone_no', true);
		        
		    }
   
        } 
    ?>

    <div class="loyalty-points">
		<h2>Enter Email/Phone Number</h2>	
		<p><b>(to check, earn and redeem rewards)</b></p>

		<form class="reward-program" id="reward-program" action="" method="post">

        	<p class="form-row form-row-first reward-enable">
			    <label><input type="radio" id="emailradio" checked="checked" name="inquiry" value="email">By Email</label>
		    </p>

		    <p class="form-row form-row-last reward-enable">
			   <label><input type="radio" id="phoneradio" name="inquiry" value="phone">By Phone</label>
			</p>   
			
			<p class="form-row form-row-wide byemail">
				<label for="loyalty_account_email">Email address<span class="required">*</span></label>
				<input type="email" class="input-text" name="loyalty_account_email" id="loyalty_account_email" autocomplete="email" value="<?php echo $email; ?>">
			</p>

		    <p class="form-row form-row-wide byphone" style="display: none;">
			    <label for="loyalty_account_phone">Mobile Number <span class="required">*</span></label>
			    <input type="text" class="input-text" name="loyalty_account_phone" id="loyalty_account_phone" maxlength="10" value="<?php echo $phone_no; ?>">
		    </p>
				    		    
			<p class="loyaltybtn-row">
				<input type="hidden" name="is_validate" value="0" id="is_validate">
				<button type="button" class="button get_loyalty" id="get_loyalty" name="get_loyalty" value="Save changes">Submit</button>
			</p>

		</form>

		<div class="redeem-points"></div>	
    </div>     
	<?php

		/**
		 * Cart collaterals hook.
		 *
		 * @hooked woocommerce_cross_sell_display
		 * @hooked woocommerce_cart_totals - 10
		 */
		do_action( 'woocommerce_cart_collaterals' );
	?>
</div>

<?php do_action( 'woocommerce_after_cart' ); ?>


<script type="text/javascript">
	
	jQuery('input[type=radio][name=inquiry]').change(function() {
	    if (this.value == 'email') {
	        jQuery(".byemail").show();
	        jQuery(".byphone").hide();
	    }
	    else if (this.value == 'phone') {
	        jQuery(".byemail").hide();
	        jQuery(".byphone").show();
	    }
    });
   <?php if(is_user_logged_in()){ ?>

	jQuery(window).load(function() {
    	setTimeout(function(){
    		jQuery('#get_loyalty').trigger('click');
		}, 1000);
	});

	<?php } ?>

	jQuery( document.body ).on( 'updated_cart_totals', function(){
	    jQuery('#get_loyalty').trigger('click');
	});

	jQuery('html').on('click', '.code_check', function () {
	    applyCoupons(jQuery(this));
	});

	jQuery("#loyalty_account_email").on("keypress", function (e) {
           // Number 13 is the "Enter" key on the keyboard    
        if (e.keyCode === 13) {
            // Trigger the button element with a click
            jQuery(".get_loyalty").trigger("click");
        }
    });
    jQuery("#loyalty_account_phone").on("keypress", function (e) {
           // Number 13 is the "Enter" key on the keyboard    
        if (e.keyCode === 13) {
            // Trigger the button element with a click
            jQuery(".get_loyalty").trigger("click");
        }
    });

	function applyCoupons(object)
	{
	    if (object.prop('checked') == true) {
	        var type = object.data('type');
	        if (type == 'cart') {
	            object.closest('li').siblings().find('input').attr('disabled', true);
	            jQuery('.code_check.item').attr('disabled', true);
	        } else {
	            jQuery('.code_check.cart').attr('disabled', true);
	        }
	    } else {
	        checked = jQuery('.code_check:checked').length;
	        if (checked == 0) {
	            jQuery('.code_check').removeAttr('disabled');
	        }
	    }
	}

    jQuery("#get_loyalty").click(function(){
    	
        var inquiryway = jQuery("input[type='radio']:checked").val();
        var email = jQuery("#loyalty_account_email").val();
        var phone = jQuery("#loyalty_account_phone").val();
        
        jQuery('.opterrmsg').remove();
        jQuery('form.reward-program input').removeClass('opterror');
        var flag = 1;
        if(inquiryway == 'email' && email == ''){
            jQuery('#loyalty_account_email').after('<span class="opterrmsg">Please enter your email address.</span>');
            jQuery('#loyalty_account_email').addClass('opterror');
            flag = 0;
        }
        if(inquiryway == 'phone' && phone == ''){
        	jQuery('#loyalty_account_phone').after('<span class="opterrmsg">Please enter your phone number.</span>');
            jQuery('#loyalty_account_phone').addClass('opterror');
            flag = 0;
        }

        if(flag == 0){
        	return false;
        }else{
        	jQuery('.opt-loader').show();
        	var action_data = "loyalty_inquiry";
        	jQuery.ajax({
                url:"<?php echo admin_url('admin-ajax.php'); ?>",
                type:'POST',
                data: jQuery('#reward-program').serialize() + "&action=" + action_data,
                success: function(data){
                	jQuery('.opt-loader').hide();
                    jQuery(".redeem-points").html(data);

                    /*-- Checkbox validation --*/ 
                    var check = jQuery('[name="coupons[]"]:checked').length;
                    if(check > 0){
                    	checked_cart = jQuery('.code_check.cart:checked').length;
                    	checked_item = jQuery('.code_check.item:checked').length;
                    	if(checked_cart > 0){
                           jQuery('.code_check.item').attr('disabled', true);
                           jQuery('.code_check.cart').parent().parent().find('input').attr('disabled', true);
                           jQuery('.code_check.cart:checked').attr('disabled', false);
                    	}
                    	if(checked_item > 0){
                            jQuery('.code_check.cart').attr('disabled', true);
                    	}
                    }
                    /*-- Checkbox validation --*/ 
                }
            });  
        }
    });  

</script>
 