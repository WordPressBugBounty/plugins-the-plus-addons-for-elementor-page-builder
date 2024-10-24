(function($) {
    "use strict";
    const WidgetFormHandler = function($scope, $) {
        const container = $scope[0].querySelector('.tp-form-container');
        const form = container.querySelector('.tp-form');

        if (!container) {
            console.error('Container not found');
            return;
        }

        const formdata = container.dataset.formdata ? JSON.parse(container.dataset.formdata) : {};
        const requiredMask = formdata.Required_mask;
        const emailData = container?.dataset?.emaildata ? JSON.parse(container.dataset.emaildata) : {};
        const basicData = container?.dataset?.basic ? JSON.parse(container.dataset.basic) : {};
        const redirect_url = emailData?.redirection || '';

        // Show/hide required asterisks
        const requiredAsterisks = container.querySelectorAll('.tp-required-asterisk');
        requiredAsterisks.forEach(asterisk => {
            asterisk.style.display = requiredMask === 'hide-asterisks' ? 'none' : 'inline';
        });

        const recaptchaExists = form.querySelector('.g-recaptcha-response') !== null;
        const recaptchaSiteKey = basicData?.recaptcha_site_key || '';
        const invalidForm = formdata.invalid_form || "Invalid form submission.";
        const successMessage = formdata.success_message || "Your message has been sent successfully.";
        const formError = formdata.form_error || "There was an error with the form submission.";
        const requiredFieldsError = formdata.required_fields || "Please fill in the required fields.";
        const serverError = formdata.server_error || "Server error, please try again later.";

        let isSubmitting = false;

        form.addEventListener('submit', function(e) {
            e.preventDefault();  // Prevent default form submission right away
            
            if (isSubmitting) return;
            isSubmitting = true;
            clearMessages();

            let isValid = true;
            const formData = {};
            const formFields = [];

            form.querySelectorAll('.tp-form-field').forEach(function(field) {
                const input = field.querySelector('input, textarea');
                const label = field.querySelector('label') ? field.querySelector('label').textContent.trim() : '';

                if (input) {
                    const inputValue = input.value.trim();
                    const inputID = input.getAttribute('id') || ''; // Get input ID
                    const inputName = input.getAttribute('name') || ''; // Get input name
                    
                    formFields.push({
                        field_id: inputID,
                        field_name: inputName,
                        field_value: inputValue
                    });

                    if (input.required && inputValue === '') {
                        isValid = false;
                        displayMessage(requiredFieldsError.replace('%field%', label), 'error');
                    }

                    formData[label || (input.name || input.id)] = inputValue;
                }
            });

            if (!isValid) {
                displayMessage(invalidForm, 'error');
                isSubmitting = false;
                return false;  // Stop execution and prevent default action
            }

            // Handle reCAPTCHA
            if (recaptchaExists) {
                grecaptcha.ready(function() {
                    grecaptcha.execute(recaptchaSiteKey, { action: 'submit' }).then(function(token) {
                        formData['Recaptcha response'] = token;
                        submitForm(formData, formFields);
                    });
                });
            } else {
                submitForm(formData, formFields);
            }
        });

        const submitForm = (formData, formFields) => {            
            $.ajax({
                url: theplus_ajax_url,
                type: 'POST',
                data: {
                    action: 'tpae_form_submission',
                    form_data: JSON.stringify(formData),
                    email_data: JSON.stringify(emailData),
                    form_fields: JSON.stringify(formFields),
                    secretKey: basicData.recaptcha_secret_key,
                    security: basicData.nonce
                },
                success: function(response) {
                    if (response?.success) {
                        if (response?.data?.email_sent) {
                            displayMessage(successMessage, 'success');
                            form.reset();
                            if (emailData.redirection && emailData.redirection.url) {
                                if (emailData.redirection.is_external) {
                                    window.open(emailData.redirection.url, '_blank', 'noopener,noreferrer');
                                } else {
                                    window.location.href = emailData.redirection.url;
                                }
                            }
                        } else {
                            displayMessage("Emails could not be sent. Please try again.", 'error');
                        }
                    } else {
                        displayMessage(formError.replace('%error%', response?.data?.message), 'error');
                    }
                },
                error: function(xhr, status, error) {
                    displayMessage(serverError.replace('%error%', error), 'error');
                },
                complete: function() {
                    isSubmitting = false;
                }
            });

            return false;  // Return false at the end to prevent form refresh
        };

        const clearMessages = () => {
            const messages = form.querySelectorAll('.tp-form-message');
            messages.forEach(message => message.remove());
        };

        const displayMessage = (message, type = 'success') => {
            clearMessages();
            const messageDiv = document.createElement('div');
            messageDiv.className = `tp-form-message ${type}`;
            messageDiv.style.color = type === 'success' ? 'green' : 'red';
            messageDiv.textContent = message;
            form.appendChild(messageDiv);
        };
    };

    window.addEventListener('elementor/frontend/init', function () {
        elementorFrontend.hooks.addAction('frontend/element_ready/tp-plus-form.default', WidgetFormHandler);
    });

})(jQuery);