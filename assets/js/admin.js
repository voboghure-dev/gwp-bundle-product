jQuery(document).ready(function () {
    // console.log(jQuery.fn.jquery);

    jQuery(".addBundleProduct").on("click", function (event) {
        productElement = jQuery(this).closest("tr").find(".product-name");
        productName = productElement[0].innerText;
        productID = event.target.getAttribute("id");

        htmlBundleProduct =
            '<div class="gwp_bundle_product_tab_info">' +
            '<label for="product_id">' +
            productName +
            "</label>" +
            '<input name="product_id[]" type="hidden" value="' +
            productID +
            '" />' +
            '<input name="product_quantity[]" type="text" value="1" />' +
            '<span class="button button-secondary removeBundleProduct">Remove</span>' +
            "</div>";
        jQuery("#gwp_bundle_options .options_group").append(htmlBundleProduct);

        jQuery(this).parent().parent().remove();
    });

    jQuery(document).on("click", ".removeBundleProduct", function (event) {
        // console.log(event);
        jQuery(this).parent().remove();
    });

    jQuery("#product-type").change(function () {
        var productType = jQuery(this).val();
        if (productType == "gwp_bundle") {
            jQuery(".product_data_tabs li").each(function () {
                if (jQuery(this).hasClass("active")) {
                    jQuery(this).removeClass("active");
                }
            });
            jQuery(".product_data_tabs .gwp_bundle_tab").addClass("active").show();

            jQuery(".panel-wrap .panel").each(function () {
                jQuery(this).hide();
            });
            jQuery(".panel-wrap #gwp_bundle_options").show();
        }
    });
});

//for Price tab
jQuery(".product_data_tabs .general_tab")
    .addClass("show_if_gwp_bundle show_if_simple")
    .show();
jQuery("#general_product_data .pricing").addClass("show_if_gwp_bundle").show();
//for Inventory tab
jQuery(".inventory_options").addClass("show_if_gwp_bundle").show();
jQuery("#inventory_product_data ._manage_stock_field")
    .addClass("show_if_gwp_bundle")
    .show();
jQuery("#inventory_product_data ._sold_individually_field")
    .parent()
    .addClass("show_if_gwp_bundle")
    .show();
jQuery("#inventory_product_data ._sold_individually_field")
    .addClass("show_if_gwp_bundle")
    .show();