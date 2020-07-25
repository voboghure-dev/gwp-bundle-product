<?php
    // Exit if accessed directly
    ! defined( 'ABSPATH' ) && exit;

    $args = [
        'type'      => 'simple',
        'status'    => 'publish',
        'paginate'  => true,
    ];

    $products_query = new WC_Product_Query( $args );
    $results        = $products_query->get_products();
    $products       = $results->products;
?>
<table class="gwp-bundle-product-table widefat striped">
    <thead>
    <tr>
        <td class="column-image"><?php _e( 'Image', 'gwp_bundle_product' )?></td>
        <td class="column-name"><?php _e( 'Name', 'gwp_bundle_product' )?></td>
        <td class="column-price"><?php _e( 'Price', 'gwp_bundle_product' )?></td>
        <td class="column-type"><?php _e( 'Type', 'gwp_bundle_product' )?></td>
        <td class="column-action"><?php _e( 'Action', 'gwp_bundle_product' )?></td>
    </tr>
    </thead>
    <tbody>
        <?php foreach ( $products as $product ) {
            $edit_link = get_edit_post_link( $product->get_id() );
        ?>
        <tr>
            <td class="column-image"><?php echo $product->get_image( 'thumbnail' ); ?></td>

            <td class="column-name">
                <div class="product-name">
                    <a href="<?php echo $edit_link ?>" target="_blank"><?php echo $product->get_formatted_name(); ?></a>
                </div>
                <div class="product-info">
                    <?php if ( ! $product->is_in_stock() ): ?>
                        <span class="product-single-info out-of-stock"><?php _e( 'Out of stock', 'gwp_bundle_product' ); ?></span>
                    <?php endif;?>
                </div>
            </td>
            <td class="column-price"><?php echo $product->get_price_html(); ?></td>
            <td class="column-type"><?php echo ucfirst( str_replace( '_', ' ', $product->get_type() ) ); ?></td>
            <td class="column-action">
                <button class="button button-primary addBundleProduct"><?php _e( 'Add', 'gwp_bundle_product' )?></button>
            </td>
        </tr>
        <?php }?>
    </tbody>
</table>