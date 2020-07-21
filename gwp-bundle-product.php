<?php
    /**
     * Plugin Name: GWP Bundle Product
     * Plugin URI: https://voboghure.com/
     * Description: WooCommerce Bundle Product
     * Author: Tapan
     * Author URI: https://voboghure.com/
     * Version: 0.0.1
     * License: GPL2+
     * License URI: http://www.gnu.org/licenses/gpl-2.0.txt
     * Text Domain: gwp_bundle_product
     */

    defined( 'ABSPATH' ) || exit;

    /** Register custom product type */
    if ( ! function_exists( 'register_gwp_bundle_product_type' ) ) {
        function register_gwp_bundle_product_type() {
            class WC_Product_gwp_bundle extends WC_Product {
                public function __construct( $product ) {
                    $this->product_type = 'gwp_bundle';
                    parent::__construct( $product );
                }
            }
        }

        add_action( 'init', 'register_gwp_bundle_product_type' );
    }

    /** Show product type in dropdown */
    if ( ! function_exists( 'add_gwp_bundle_product_type' ) ) {
        function add_gwp_bundle_product_type( $type ) {
            $type['gwp_bundle'] = __( 'Bundle Product', 'gwp_bundle_product' );
            return $type;
        }

        add_filter( 'product_type_selector', 'add_gwp_bundle_product_type' );
    }

    /** Bundle product tab */
    if ( ! function_exists( 'gwp_bundle_product_tab' ) ) {
        function gwp_bundle_product_tab( $tabs ) {
            $tabs['gwp_bundle'] = [
                'label'  => __( 'Bundle Product', 'gwp_bundle_product' ),
                'target' => 'gwp_bundle_options',
                'class'  => ( 'show_if_gwp_bundle' ),
            ];
            return $tabs;
        }

        add_filter( 'woocommerce_product_data_tabs', 'gwp_bundle_product_tab' );
    }

    /** Keep general price tab */
    if ( ! function_exists( 'gwp_bundle_product_js' ) ) {
        function gwp_bundle_product_js() {
            if ( 'product' != get_post_type() ) {
                return;
            }
            ?>
            <script type='text/javascript'>
                jQuery( document ).ready( function() {
                    jQuery( '.options_group.pricing' ).addClass( 'show_if_gwp_bundle' ).show();
                });
            </script>
            <?php
        }

        add_action( 'admin_footer', 'gwp_bundle_product_js' );
    }

    /** Add to cart for bundle product as like as simple product */
    if ( ! function_exists( 'gwp_bundle_add_to_cart' ) ) {
        function gwp_bundle_add_to_cart() {
            wc_get_template( 'single-product/add-to-cart/simple.php' );
        }

        add_action( 'woocommerce_gwp_bundle_add_to_cart', 'gwp_bundle_add_to_cart' );
    }

    /** Bundle product tab content */
    if ( ! function_exists( 'gwp_bundle_product_tab_content' ) ) {
        function gwp_bundle_product_tab_content() {
            ?>
            <div id='gwp_bundle_options' class='panel woocommerce_options_panel'>
                <div class='options_group'>
                <?php
                    woocommerce_wp_text_input( [
                        'id'          => '_gwp_bundle_product_price',
                        'label'       => __( 'Bundle Price', 'gwp_bundle_product' ),
                        'placeholder' => '',
                        'desc_tip'    => 'true',
                        'description' => __( 'Enter Bundle Product Price.', 'gwp_bundle_product' ),
                    ] );
                ?>
                    <p class="form-field _gwp_bundle_products">
                        <label for="_gwp_bundle_products"><?php _e( 'Product Select', 'gwp_bundle_product' ); ?></label>
                        <select class="wc-product-search" id="_gwp_bundle_products" name="_gwp_bundle_products[]" multiple="multiple" data-placeholder="<?php _e( 'Search for a product', 'gwp_bundle_product' ); ?>">
                        <?php
                            global $post;
                            $product_ids_arr = json_decode( get_post_meta( $post->ID, '_gwp_bundle_products', true ) );

                            $args = [
                                'type' => 'simple',
                            ];
                            $products = wc_get_products( $args );
                            if ( $products ) {
                                foreach ( $products as $product ) {
                                    echo '<option value="' . esc_attr( $product->id ) . '" ' . selected( in_array( $product->id, $product_ids_arr ) ) . '>' . esc_html( $product->name ) . '</option>';
                                }
                            }
                        ?>
                        </select>
                    </p>
                </div>
            </div>
        <?php
        }

        add_action( 'woocommerce_product_data_panels', 'gwp_bundle_product_tab_content' );
    }

    /** Save Bundle product meta data */
    if ( ! function_exists( 'save_gwp_bundle_product_meta' ) ) {
        function save_gwp_bundle_product_meta( $post_id ) {
            if ( isset( $_POST['_gwp_bundle_products'] ) ) {
                $product_ids_json = json_encode( $_POST['_gwp_bundle_products'] );
                update_post_meta( $post_id, '_gwp_bundle_products', $product_ids_json );
            }
            if ( isset( $_POST['_gwp_bundle_product_price'] ) ) {
                update_post_meta( $post_id, '_gwp_bundle_product_price', sanitize_text_field( $_POST['_gwp_bundle_product_price'] ) );
            }
        }

        add_action( 'woocommerce_process_product_meta', 'save_gwp_bundle_product_meta' );
    }

    /** Show Bundle product price in single product page */
    if ( ! function_exists( 'gwp_bundle_product_detail' ) ) {
        function gwp_bundle_product_detail() {
            global $product;
            ?>
            <div>
                <span><strong> Bundle Price:</strong><?php get_post_meta( $product->id, '_gwp_bundle_product_price', true ) ?></span>
            </div>
            <?php
        }

        add_action( 'woocommerce_single_product_summary', 'gwp_bundle_product_detail' );
    }
