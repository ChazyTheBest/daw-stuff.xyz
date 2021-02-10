<?php

return
    [
        // page title / heading
        'h1_create' => 'Create New Product',
        'h1_products' => 'Products',
        'h1_update' => 'Update Product: {name}',
        'h1_about' => 'About',
        'h1_contact' => 'Contact',
        'h1_login' => 'Login',
        'h1_reqreset' => 'Request password reset',
        'h1_resverify' => 'Resend verification email',
        'h1_reset' => 'Reset password',
        'h1_chgpwd' => 'Change Password',
        'h1_users' => 'My User Network',
        'h1_signup' => 'Invite User',
        'h1_updateinfo' => 'Update Info',
        'h1_orders' => 'My Orders',
        'h1_userorders' => 'My Network Orders',
        'h1_earn' => 'Wanna earn money?',
        'h1_createorder' => 'NeW Order',
        'h1_payment' => 'Payment Method',
        'h2_instructions' => 'Instructions',
        'h1_billing' => 'Billing and Shipping Info',

        // table headers
        // category/manage
        'th_name' => 'Name',
        'th_type' => 'Type',



        // user/index
        'th_cardnum' => 'Red Comercio #',
        'th_full_name' => 'Full Name',
        //'th_email' => 'Email',
        'th_invby' => 'Invited By',
        'th_invdate' => 'Invite Date',
        'th_lvl' => 'Level',
        // /order/index
        'th_product' => 'Product',
        'th_batches' => 'Batches',
        'th_price' => 'Sub-Total',
        'th_discount' => 'Discount',
        //'th_total' => 'Total',
        'th_status' => 'Status',
        'th_modify' => 'Modify',
        // /order/users
        'th_credit' => 'Credit',
        'th_returned' => 'Returned',

        // table data
        // category/manage
        'category' => 'Category',
        'subcategory' => 'Subcategory',



        // /order/index
        'td_pending' => 'Pending',
        'td_awaiting' => 'Awaiting Payment',
        'td_declined' => 'Declined',
        'td_completed' => 'Paid',
        'td_shipped' => 'Shipped',
        'td_delivered' => 'Delivered',
        'td_partref' => 'Partially Refunded',
        'td_refunded' => 'Refunded',
        // /order/users
        'td_yes' => 'Yes',
        'td_no' => 'No',

        // buttons
        'b_signup' => 'Invite',
        'b_invite' => 'Invite User',
        'b_all' => 'All',
        'b_level' => 'Level {level}',

        // a links
        'a_create' => 'Create',
        'a_update' => 'Update',
        'a_delete' => 'Delete',
        'a_buy' => 'Buy',
        'a_reset' => 'reset it',
        'a_resend' => 'Resend',
        'a_presentation' => 'Presentation',
        'a_paynow' => 'Pay Now',
        'a_tryagain' => 'Try Again',
        'a_vieworder' => 'View Order',
        'a_updateorder' => 'Update Order',
        'a_delorder' => 'Delete Order',
        'a_contact_us' => 'Contact with Us',
        'a_consult_price' => 'Check shipping',

        // span
        'span_bank_transfer' => 'Bank Transfer',

        // resources
        'url_earnmoney' => '@static/docs/earn_money.pps',

        // paragraphs
        'p_product' => '{l_price}: {price}<br>{l_discount}: {discount}<br>{shipping}<br>{product_min}',
        'p_about' => 'This is the About page. You may modify the following file to customize its content',
        'p_contact' => 'If you have business inquiries or other questions, please fill out the following form to contact us. Thank you.',
        'p_error1' => 'The above error occurred while the Web server was processing your request.',
        'p_error2' => 'Please contact us if you think this is a server error. Thank you.',
        'p_login1' => 'Please fill out the following fields to login',
        'p_login2' => 'If you forgot your password you can {link}',
        'p_login3' => 'Need new verification email? {link}',
        'p_reqreset' => 'Please fill out your email. A link to reset password will be sent there.',
        'p_resverify' => 'Please fill out your email. A verification email will be sent there.',
        'p_reset' => 'Please choose your new password',
        'p_chgpwd' => 'Please choose your new password',
        'p_signup' => 'Please fill out the following fields to signup',
        'p_updateinfo' => 'The following information will be used for the purchase process',
        'p_updateinfo_2' => 'Where do you want us to send your monthly credits? (Choose only one)',
        'default_shipping' => 'Free for Spain (Mainland and Baleares)',
        'no_shipping' => 'Not available for your Country',
        'free_shipping' => 'Free',
        'shipping_price' => 'Shipping Price: {price}',
        'product_min' => 'Order batches of 12 units.',
        'p_earn1' => 'Download and check out this presentation',
        'p_earn2' => 'In order to Sign Up',
        'p_earn3' => 'Contact the person who introduced you to us.',
        'p_total_credits' => 'Total Credits',
        'p_lvl_credits' => 'Credits Level',
        // /order/create
        'p_create' => 'All purchases will be made in accordance with the terms and conditions of the aforementioned Agreement.',
        'p_pname' => 'Product Name: {product}',
        'p_batchprice' => 'Batch Retail Price: {price}',
        'p_discount' => 'Member Discount: {discount} ({percentage})',
        'p_more' => 'Remember that you can only get batches of 12 units.',
        // /order/billing
        'p_billing' => 'Enter your billing info:',
        'p_shipping' => 'Enter your shipping info:',
        // /order/pay
        'p_orderref' => 'Order Reference: {reference}',
        'p_quantity' => 'Quantity: {quantity}',
        'p_subtotal' => 'Sub-Total: {subtotal}',
        'p_total' => 'Total: {total}',
        'p_bankname' => 'Bank Name: {bankname}',
        'p_iban' => 'IBAN: {iban}',
        'p_instructions' => 'Include the Order Reference in the Observations.',
        'p_instructions_2' => 'Products will be reserved for 5 days. If it\'s not paid the order will be deleted automatically.',
        // /order/index
        'p_order' => 'All orders are subject to a 15 day cooling off period.',
        // /order/user
        'p_userorder' => 'Different levels\' credits will be issued once prescribed the return period (15 days).',
        'opt_av' => 'Available',
        'opt_na' => 'Not Available',
        'opt_issued' => 'Issued',
        'p_welcome' => 'Welcome {name}! To get started we need you to tell us where you\'re from:',

        // alert
        'alert_delete' => 'Are you sure you want to delete this item?'
    ];
