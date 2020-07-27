jQuery(document).ready(function () {

    jQuery(".addBundleProduct").on('click', function(event) {
        productElement = jQuery(this).closest('tr').find('.product-name');
        productName = productElement[0].innerText;
        productID = event.target.getAttribute('id');

        htmlBundleProduct = '<div class="gwp_bundle_product_tab_info">' +
                            '<label for="product_id">' + productName + '</label>' +
                            '<input name="product_id[]" type="hidden" value="' + productID + '" />' +
                            '<input name="product_quantity[]" type="text" value="1" />' +
                            '<span class="button button-secondary removeBundleProduct">Remove</span>' +
                            '</div>';
        jQuery("#gwp_bundle_options .options_group").append(htmlBundleProduct);

        jQuery(this).parent().parent().remove();
    });

    jQuery(document).on('click', '.removeBundleProduct', function (event) {
        // console.log(event);
        jQuery(this).parent().remove();
    });

});