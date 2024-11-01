<?php

class LE_WooCategoryAlert 
{
    /** The single instance of the class. */
    protected static $_instance = null;

    /**
     * Returns the *Singleton* instance of this class.
     *
     * @return Singleton The *Singleton* instance.
     */
    public static function instance()
    {
        if ( is_null( self::$_instance ) )
        {
            self::$_instance = new self();
        }
        return self::$_instance;
    }

    /**
     * Comax Gateway Constructor.
     */
    private function __construct()
    {
        $this->init_hooks();
    }

    /**
     * All Hooks
     */

    private function init_hooks()
    {
        
        add_filter( 'woocommerce_email_recipient_new_order', [$this, 'emailRecipientNewOrder'], 10, 2 );
        
        // Add metafield to Category add/edit
        add_action('product_cat_add_form_fields', [$this, 'category_add_recipient_field']);
        add_action('product_cat_edit_form_fields', [$this, 'category_edit_recipient_field']);
        
        // Save Category Recipient
        add_action('edited_product_cat', [$this, 'save_category_recipient']);
        add_action('create_product_cat', [$this, 'save_category_recipient']);

    }

    /**
     * Order notification
     * Check order item's categories
     * and check category's email
     * if category's has email add to email recipient's
     * 
     */
    function emailRecipientNewOrder( $recipient, $order ) 
    {    
        $recipients = [];

        // check instance of Order
        if ( ! $order instanceof WC_Order ) {
            return $recipient; 
        }
        
        // Loop through order items
        foreach ( $order->get_items() as $item ) {
            // Get item category'ies
            $terms = get_the_terms ( $item->get_product_id(), 'product_cat' );
            if($terms){
                foreach ( $terms as $term ) {
                    $email = get_term_meta($term->term_id, 'LE_cat_recipient', true);
                    $recipients[] = trim($email);
                }
            }
        }
        if(count($recipients)){
            $recipient .= ',' . implode(',', array_unique($recipients));
        }
        
        return $recipient;
    }

    //Product Cat Create page
    function category_add_recipient_field() {
        ?>
        <div class="form-field">
            <label for="LE_cat_recipient"><?php _e('Order recipient Email', 'woo_category_alert'); ?></label>
            <input type="text" name="LE_cat_recipient" id="LE_cat_recipient">
        </div>
        <?php
    }

    //Product Cat Edit page
    function category_edit_recipient_field($term) {

        // retrieve the existing email
        $recipient = get_term_meta($term->term_id, 'LE_cat_recipient', true);
        ?>
        <tr class="form-field">
            <th scope="row" valign="top"><label for="LE_cat_recipient"><?php _e('Order recipient Email', 'woo_category_alert'); ?></label></th>
            <td>
                <input type="text" name="LE_cat_recipient" id="LE_cat_recipient" value="<?php echo $recipient ? esc_attr($recipient) : ''; ?>">
            </td>
        </tr>
        <?php
    }

    // Save extra taxonomy fields callback function.
    function save_category_recipient($term_id) {

        $recipient = filter_input(INPUT_POST, 'LE_cat_recipient');
        update_term_meta($term_id, 'LE_cat_recipient', $recipient);
    }
    
}