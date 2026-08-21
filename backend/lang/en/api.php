<?php

return [

    'common' => [
        'not_found' => 'Not found.',
        'unauthorized' => 'Unauthorized.',
        'forbidden' => 'You are not authorized to access this resource.',
    ],

    'auth' => [
        'registered' => 'Registered. Check your email to activate your account. Please also check spam folder.',
        'email_verified' => 'Email verified.',
        'invalid_verification_link' => 'Invalid verification link.',
        'invalid_credentials' => 'Invalid credentials',
        'verify_email_first' => 'Please verify your email first.',
        'account_inactive' => 'Your account is not active. Please contact support.',
        'logged_out' => 'Logged out',
        'profile_updated' => 'Profile updated successfully.',
        'admin_cannot_delete' => 'Admin account cannot be deleted.',
        'password_changed' => 'Password changed successfully',
        'current_password_incorrect' => 'Current password is incorrect',
        'forgot_password_sent' => 'If that email exists, we sent a reset link.',
        'reset_token_invalid' => 'This password reset token is invalid.',
        'password_reset' => 'Password reset successfully',
        'verification_resent' => 'If that email exists and is unverified, we sent a verification link.',
    ],

    'cart' => [
        'not_found' => 'Cart not found',
        'item_not_found' => 'Cart item not found',
        'not_enough_stock' => 'Not enough stock.',
        'not_enough_stock_for_product' => 'Not enough stock for this product.',
    ],

    'orders' => [
        'cart_empty' => 'Cart is empty.',
        'placed' => 'Order placed successfully.',
        'invalid_status' => 'Invalid status.',
        'status_updated' => 'Order status updated successfully.',
        'invalid_delivery_method' => 'Invalid or inactive delivery method.',
        'invalid_payment_method' => 'Invalid or inactive payment method.',
        'payment_failed' => 'Could not start payment. Please try again.',
    ],

    'admin' => [
        'category_created' => 'Category created successfully.',
        'category_updated' => 'Category updated successfully.',
        'category_delete_has_products' => 'Cannot delete category with products.',
        'product_created' => 'Product created successfully.',
        'product_updated' => 'Product updated successfully.',
        'user_updated' => 'User updated successfully.',
        'cannot_change_own_active_status' => 'You cannot change your own active status.',
        'delivery_method_created' => 'Delivery method created successfully.',
        'delivery_method_updated' => 'Delivery method updated successfully.',
        'payment_method_created' => 'Payment method created successfully.',
        'payment_method_updated' => 'Payment method updated successfully.',
    ],

];
