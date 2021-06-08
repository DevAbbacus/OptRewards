jQuery(document).ready(function($){

    jQuery("#opt_user_reward_program_enable").click(function () {
        if (jQuery(this).is(":checked")) {
            jQuery(".opt_phone_fields").show();
        } else {
            jQuery(".opt_phone_fields").hide();
        }
    });

});
jQuery('html').on('click', '.code_check', function () {
    applyCoupons(jQuery(this));
});
function applyCoupons(object)
{
    if (object.prop('checked') == true) {
        var type = object.data('type');
        if (type == 'cart') {
            object.closest('li').siblings().find('input').attr('disabled', true);
        } else {
            jQuery('.code_check.cart').attr('disabled', true);
        }
    } else {
        var checked = jQuery('.code_check:checked').length;
        if (checked == 0) {
            jQuery('.code_check').removeAttr('disabled');
        }
    }
}
