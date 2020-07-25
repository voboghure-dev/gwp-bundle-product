jQuery(document).ready(function () {
    jQuery(".addBundleProduct").on('click', function(event) {
        jQuery(this).parent().parent().remove();
    });
});