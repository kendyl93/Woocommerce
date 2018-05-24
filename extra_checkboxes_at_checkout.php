<?php // Add extra tick box at checkout
add_action('woocommerce_review_order_before_submit', 'bbloomer_add_checkout_tickbox', 9);

function bbloomer_add_checkout_tickbox() {

?>
<p class="form-row terms">
	<input type="checkbox" class="input-checkbox" name="consenttodataprocessing" id="consenttodataprocessing" />
	<label for="consenttodataprocessing" class="checkbox"><span>Zgadzam się na przetwarzanie moich danych osobowych przez (dane administratora danych osobowych – tj. przedsiębiorcy prowadzącego sklep/stronę), w celu prowadzenia konta w sklepie. Podanie danych jest dobrowolne. Podstawą przetwarzania danych jest moja zgoda. Mam prawo wycofania zgody w dowolnym momencie. Dane osobowe będą przetwarzane do czasu odwołania zgody. Mam prawo żądania od administratora dostępu do moich danych osobowych, ich sprostowania, usunięcia lub ograniczenia przetwarzania, a także prawo wniesienia skargi do organu nadzorczego.</span></label>
</p>

<?php
}

// Save the order meta with hidden field value
add_action( 'woocommerce_checkout_update_order_meta', 'my_custom_checkout_field_update_order_meta' );
function my_custom_checkout_field_update_order_meta( $order_id ) {
    if ( ! empty( $_POST['consenttodataprocessing'] ) ) {
        update_post_meta( $order_id, '_my_field_1_slug', $_POST['consenttodataprocessing'] );
    }
}

// Display field value on the order edit page (not in custom fields metabox)
add_action( 'woocommerce_admin_order_data_after_billing_address', 'my_custom_checkout_field_display_admin_order_meta', 10, 1 );
function my_custom_checkout_field_display_admin_order_meta($order){
    $my_custom_field_1 = get_post_meta( $order->id, '_my_field_1_slug', true );


    if ( ! empty( $my_custom_field_1 ) ) {
        echo '<strong>'. __("Zgoda na przetwarzanie danych osobowych", "woocommerce").':</strong> Zaakceptowane<br>';
    } else {
		echo '<strong>'. __("Zgoda na przetwarzanie danych osobowych", "woocommerce").':</strong> Odmowa<br>';
	}

}

// Show notice if customer does not tick
add_action('woocommerce_checkout_process', 'bbloomer_not_approved_delivery');


 function bbloomer_not_approved_delivery() {
     if ( ! (int) isset( $_POST['consenttodataprocessing'] ) ) {
 		wc_add_notice( __( 'Musisz zaakceptować wszystkie zgody.' ), 'error' );
     }
 }
