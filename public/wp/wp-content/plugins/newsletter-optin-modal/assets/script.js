jQuery.noConflict()
jQuery(document).ready(function(jQuery) {
    // Show newsletter opt-in modal on page load
    show_newsletter_optin_modal();

    // Show newsletter opt-in modal on scroll
    jQuery(window).scroll(function() {
        show_newsletter_optin_modal();
    });

    // Hide newsletter opt-in modal when close button is clicked
    jQuery('.newsletter-optin-modal .close').click(function() {
        hide_newsletter_optin_modal();
    });

    // Hide newsletter opt-in modal when overlay is clicked
    jQuery('.newsletter-optin-modal .overlay').click(function() {
        hide_newsletter_optin_modal();
    });

    // Submit newsletter opt-in form via AJAX
    jQuery('.newsletter-optin-modal form').submit(function(event) {
        event.preventDefault();

        // Get name and email values
        var name = jQuery('#newsletter-optin-name').val();
        var email = jQuery('#newsletter-optin-email').val();

        // Check if name and email are not empty
        if (name.trim() === '' || email.trim() === '') {
            alert('Please enter your name and email.');
            return;
        }

        // Send AJAX request to record user data

        var ajaxUrl = newsletter_optin_form_submit.ajax_url;
        console.log('ajaxurl', ajaxUrl)

        jQuery.ajax({
            url: ajaxUrl,
            type: 'post',
            dataType: 'json',
            data: {
                action: 'newsletter_optin_form_submit',
                name: name,
                email: email
            },
            success: function(response) {
                // Hide modal and show success message
                hide_newsletter_optin_modal();
                alert('Thank you for subscribing to our newsletter!');
                localStorage.setItem('newsletter-optin-modal-shown', 'true');

            },
            error: function(xhr, status, error) {
                // Show error message
                alert('There was an error processing your request. Please try again later.');
            }
        });
    });
});

function show_newsletter_optin_modal() {

    // Check if modal has not been shown before
    // localStorage.setItem('newsletter-optin-modal-shown', 'false');
    console.log('storage', localStorage.getItem('newsletter-optin-modal-shown'));
    if (!localStorage.getItem('newsletter-optin-modal-shown')) {
        // Show modal
        console.log('call modal');
        jQuery('.newsletter-optin-modal').fadeIn();

        // Set flag to indicate that modal has been shown
    }
}

function hide_newsletter_optin_modal() {
    jQuery('.newsletter-optin-modal').fadeOut();
}
