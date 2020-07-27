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


    if ( ! defined( 'GWP_BUNDLE_PRODUCT_DIR' ) ) {
        define( 'GWP_BUNDLE_PRODUCT_DIR', plugin_dir_path( __FILE__ ) );
    }

    if ( ! defined( 'GWP_BUNDLE_PRODUCT_URL' ) ) {
        define( 'GWP_BUNDLE_PRODUCT_URL', plugin_dir_url( __FILE__ ) );
    }

    if ( ! defined( 'GWP_BUNDLE_PRODUCT_TEMPLATE_PATH' ) ) {
        define( 'GWP_BUNDLE_PRODUCT_TEMPLATE_PATH', GWP_BUNDLE_PRODUCT_DIR . 'templates' );
    }

    if ( ! defined( 'GWP_BUNDLE_PRODUCT_ASSETS_URL' ) ) {
        define( 'GWP_BUNDLE_PRODUCT_ASSETS_URL', GWP_BUNDLE_PRODUCT_URL . 'assets' );
    }

    /**
     * Register custom product type
     */
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

    /**
     * Load admin theme style
     */
    function gwp_bundle_admin_theme() {
        wp_enqueue_style( 'gwp_bundle_admin_style', GWP_BUNDLE_PRODUCT_ASSETS_URL . '/css/admin.css' );
        wp_enqueue_script( 'gwp_bundle_admin_script', GWP_BUNDLE_PRODUCT_ASSETS_URL . '/js/admin.js', 'jquery', '1.0.0', true );
    }

    add_action( 'admin_enqueue_scripts', 'gwp_bundle_admin_theme' );

    /**
     * Show product type in dropdown
     */
    if ( ! function_exists( 'add_gwp_bundle_product_type' ) ) {
        function add_gwp_bundle_product_type( $type ) {
            $type['gwp_bundle'] = __( 'Bundle Product', 'gwp_bundle_product' );
            return $type;
        }

        add_filter( 'product_type_selector', 'add_gwp_bundle_product_type' );
    }

    /**
     * Bundle product tab
     */
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

    /**
     * Keep general price tab
     */
    if ( ! function_exists( 'gwp_bundle_product_js' ) ) {
        function gwp_bundle_product_js() {
            if ( 'product' != get_post_type() ) {
                return;
            }
            echo '<script type="text/javascript">';
                echo 'jQuery( ".options_group.pricing" ).addClass( "show_if_gwp_bundle" );';
            echo '</script>';
        }

        add_action( 'admin_footer', 'gwp_bundle_product_js' );
    }

    /**
     * Add to cart for bundle product as like as simple product
     */
    if ( ! function_exists( 'gwp_bundle_add_to_cart' ) ) {
        function gwp_bundle_add_to_cart() {
            wc_get_template( 'single-product/add-to-cart/simple.php' );
        }

        add_action( 'woocommerce_gwp_bundle_add_to_cart', 'gwp_bundle_add_to_cart' );
    }

    /**
     * Bundle product tab content
     */
    if ( ! function_exists( 'gwp_bundle_product_tab_content' ) ) {
        function gwp_bundle_product_tab_content() {
            ?>
            <div id="gwp_bundle_options" class="panel woocommerce_options_panel">
                <div class="options_group">

                    <div class="gwp_bundle_product_add">
                        <a href="#TB_inline?&width=753&height=400&inlineId=gwp-bundle-products" title="Product list" class="thickbox button button-primary" style="margin: 10px;">Add Product</a>

                        <div id="gwp-bundle-products" style="display:none;">
                            <div>
                                <?php include GWP_BUNDLE_PRODUCT_TEMPLATE_PATH . '/admin/all-products.php'; ?>
                            </div>
                        </div>
                    </div>

                    <div class="gwp_bundle_product_tab_list">
                        <?php
                            global $post;
                            $product_arr = json_decode( get_post_meta( $post->ID, '_gwp_bundle_products', true ), true );
                            if ( count( $product_arr ) > 0 ) {
                                foreach ( $product_arr as $id => $qty ) {
                                    $product = wc_get_product( $id );
                                    ?>
                                        <div class="gwp_bundle_product_tab_info">
                                        <label for="product_id"><?php echo $product->name; ?></label>
                                        <input name="product_id[]" type="hidden" value="<?php echo $id; ?>" />
                                        <input name="product_quantity[]" type="text" value="<?php echo $qty; ?>" />
                                        <span class="button button-secondary removeBundleProduct">Remove</span>
                                        </div>
                                    <?php
                                }
                            }
                        ?>
                    </div>

                </div>
            </div>
            <?php
        }

        add_action( 'woocommerce_product_data_panels', 'gwp_bundle_product_tab_content' );
    }

    /**
     * Save Bundle product meta data
     */
    if ( ! function_exists( 'save_gwp_bundle_product_meta' ) ) {
        function save_gwp_bundle_product_meta( $post_id ) {
            $product_ids = array_combine($_POST['product_id'], $_POST['product_quantity']);

            if ( count( $product_ids ) > 0 ) {
                $product_ids_json = json_encode( $product_ids );
                update_post_meta( $post_id, '_gwp_bundle_products', $product_ids_json );
            }
        }

        add_action( 'woocommerce_process_product_meta', 'save_gwp_bundle_product_meta' );
    }

    /** Show Bundle product info in single product page */
    if ( ! function_exists( 'gwp_bundle_product_detail' ) ) {
        function gwp_bundle_product_detail() {
            global $product;
            add_thickbox();
            ?>
            <div id="testID">
                <div>
                    This is a test
                </div>
            </div>
            <a href="#TB_inline?&width=300&height=400&inlineId=testID" class="thickbox button button-primary">Add Product</a>
            <?php
        }

        add_action( 'woocommerce_single_product_summary', 'gwp_bundle_product_detail' );
    }
