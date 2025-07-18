(function($) {
    $(document).ready(function () {

        bookneticHooks.addAction('ajax_after_confirm_success', function(booknetic, data, result) {
            if (data.get('payment_method') === 'stripe' || data.get('payment_method') === 'stripe_split') {
                console.log("[Booknetic] Stripe Checkout URL:", result.url);
                if (result.url) {
                    window.location.href = result.url;
                } else {
                    console.error("[Booknetic] Stripe URL missing in response.");
                }
            }
        });

    });
})(jQuery);