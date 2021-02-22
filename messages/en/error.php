<?php

return [
    // HTTP error codes
    '401_default' => 'You need to be authenticated in order to view this page.',
    '403_default' => 'You\'re not authorized to view this page.',
    '404_default' => 'The page you\'re looking for does not exist.',
    '404_user' => 'The user does not exist.',
    '404_category' => 'The category does not exist.',
    '404_product' => 'The product does not exist.',
    '404_order' => 'The order does not exist.',
    '405_ajax' => 'This action only supports XMLHttpRequest.',

    // custom errors
    'signup_failed' => 'Signup failed. Please, check the input data.',
    'create_failed' => 'The creation failed. Please, check the input data.',
    'update_failed' => 'Update failed. Please, check the input data.',
    'login_failed' => 'Login failed. Please, check your username/password.',
    'upload_failed' => 'Upload failed. Please check your image.',
    'order_delete_refund' => 'The order has already been processed and cannot be deleted. Please request a refund.',
    'order_invalid_status' => 'The status is not valid.',
    'model_load' => 'There was a problem loading your data.',
    'cart_not_found' => 'The product is not in your cart.',
    '' => '',
    '' => '',
    '' => '',
    '' => '',
    '' => '',

    // form errors
    'form_unk_err' => 'Unknown error while processing the form. Please, try again or contact support.',
    'form_images' => 'This form requires file uploads.',
    'form_missing_field' => 'The form field %s is missing. What are you trying to do?',
    'form_required' => 'The form field %s is required. Please fill it in and try again.',
    'form_string_max' => 'The form field %s has surpassed the maximum amount of characters allowed.',
    'form_string_min' => 'The form field %s does not meet the minimum amount of characters required.',
    'form_int' => 'The form field %s must be an integer number.',
    'form_int_match' => 'The selected option for %s has to match the predefined list. Please, select an existing option from the list and try again.',
    'form_int_check' => 'The selected option for %s does not exist. Please, select an existing option from the list and try again.',
    'form_decimal' => 'The form field %s must be a decimal number.',
    'form_email' => 'The email doesn\'t have a valid format.',
    'form_unique' => 'The value entered in %s already exists. Please, enter a new one and try again.',
    'form_login' => 'The email or password do not match.',
    'form_valid' => 'The form field %s is not valid. Please, enable javascript and follow the instructions in the form.',
    '' => '',
    '' => '',
    '' => '',
    '' => '',
    '' => ''
];
