jQuery(document).ready(function($) {

    $("body").on("click", ".ssbp-email", function () {
        $("#ssbp-email-div").fadeIn(500);
    });
    $("body").on("click", ".ssbp-close-email-div", function () {
        $("#ssbp-email-div").fadeOut(500);
    });

    function ssbp_email_alert(alert_message, alert_class) {
        // show alert
        $("#ssbp-email-alert").html(alert_message).addClass(alert_class).fadeIn();
    }

    function ssbp_email_hide_form() {
        // hide send button and form
        $("#js-ssbp-email-form").fadeOut();
    }

    $("#js-ssbp-email-form").on("submit", function (e) {

        // dont submit the form
        e.preventDefault();

        // disable all inputs
        $(":input").prop("disabled", true);

        // show spinner to show progress
        $("#ssbp-email-send").html("<span class=\'ssbp-spinner\'></span>");

        // prepare data
        var data = {
            action: "ssbp_email_send",
            security: ssbpEmail.security,
            fill_me: $("#js-ssbp-email-form #fill_me").val(),
            email: $("#js-ssbp-email-form #email").val(),
            message: $("#js-ssbp-email-form #message").val(),
            url: $("#js-ssbp-email-form #url").val()
        };

        // post
        $.post(ssbpEmail.ajax_url, data, function (response) {
            // honeypot?
            if (response == "bot") {
                // remove modal
                $("#ssbp-email-div").hide().html("");
            } else if (response == "check") {
                // set alert vars
                var alert_message = $("#js-ssbp-email-form").attr('data-warning-alert-text');
                var alert_class = "ssbp-alert-warning";

                // show alert
                ssbp_email_alert(alert_message, alert_class);

                // re-enable fields
                $(":input").prop("disabled", false);

                // reset button content
                $("#ssbp-email-send").html("Send");
            }

            if (response == "brute") {
                // set alert vars
                var alert_message = $("#js-ssbp-email-form").attr('data-brute-alert-text');
                var alert_class = "ssbp-alert-warning";

                // show alert
                ssbp_email_alert(alert_message, alert_class);

                // hide the form
                ssbp_email_hide_form();
            } else if (response == "success") {
                // set alert vars
                var alert_message = $("#js-ssbp-email-form").attr('data-success-alert-text');
                var alert_class = "ssbp-alert-success";

                // show success message
                ssbp_email_alert(alert_message, alert_class);

                // hide the form
                ssbp_email_hide_form();
            }
        }); // end post
    }); // end form submit
});
