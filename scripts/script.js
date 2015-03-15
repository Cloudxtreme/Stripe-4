/*jslint browser:true */
/*global Stripe, document*/
(function () {
    'use strict';

    var address_line1   = document.getElementById('address_line1'),
        address_city    = document.getElementById('address_city'),
        address_state   = document.getElementById('address_state'),
        address_zip     = document.getElementById('address_zip'),
        buttonSubmit    = document.getElementById('submit-button'),
        filter          = /^(.+)@(.+){2,}\.(.+){2,}$/,
        name            = document.getElementById('name'),
        node            = document.createElement('input'),
        payError        = document.getElementById('payment-errors'),
        payForm,
        receipt_email   = document.getElementById('receipt_email'),
        token,
        valError;

    // This identifies your website in the createToken call below
    Stripe.setPublishableKey('ADD_YOUR_PUBLISHABLE_KEY_HERE');

    /**
     * AJAX form validation and submit
     */
    function stripeResponseHandler(status, response) {
        payForm = document.getElementById('payment-form');
        if (response.error) {
            // re-enable the submit button
            buttonSubmit.removeAttribute('disabled');
            // show the errors on the form
            payError.innerHTML = response.error.message + ' Error Code ' + status;
        } else {
            token = response.id;
            // insert the token into the form so it gets submitted to the server
            node.type = 'hidden';
            node.name = 'stripeToken';
            node.value = token;
            payForm.appendChild(node);
            // and submit
            payForm.submit();
        }
    }

    /**
     * Create our token and pass it off to the form handler.
     */
    function createToken() {
        payForm = document.getElementById('payment-form');
        // disable the submit button to prevent repeated clicks
        buttonSubmit.setAttribute('disabled', 'disabled');
        // createToken returns immediately - the supplied callback submits the form if there are no errors
        Stripe.card.createToken(payForm, stripeResponseHandler);
        return false; // submit from callback
    }

    /**
     * EXTREMELY basic form validation to make sure all required fields have SOMETHING.
     */
    function validate() {
        valError = 0;
        if (name.value === '') {
            name.style.backgroundColor = '#FFCCCC';
            valError = 1;
        } else {
            name.style.backgroundColor = '#F3F9FF';
        }

        if (address_line1.value === '') {
            address_line1.style.backgroundColor = '#FFCCCC';
            valError = 1;
        } else {
            address_line1.style.backgroundColor = '#F3F9FF';
        }

        if (address_city.value === '') {
            address_city.style.backgroundColor = '#FFCCCC';
            valError = 1;
        } else {
            address_city.style.backgroundColor = '#F3F9FF';
        }

        if (address_state.value === '') {
            address_state.style.backgroundColor = '#FFCCCC';
            valError = 1;
        } else {
            address_state.style.backgroundColor = '#F3F9FF';
        }

        if (address_zip.value === '') {
            address_zip.style.backgroundColor = '#FFCCCC';
            valError = 1;
        } else {
            address_zip.style.backgroundColor = '#F3F9FF';
        }

        if ((receipt_email.value === '') || (!filter.test(receipt_email.value))) {
            receipt_email.style.backgroundColor = '#FFCCCC';
            valError = 1;
        } else {
            receipt_email.style.backgroundColor = '#F3F9FF';
        }

        // Don't create the token unless the form passes a basic check.
        if (valError === 0) {
            createToken();
        }

    }

    // IE9+ only.
    buttonSubmit.addEventListener('click', validate, false);

}());