<?php

namespace App\Enums;

enum NotificationType: string
{
    // Orders
    case ORDER_CREATED = 'order_created';
    case ORDER_STATUS_UPDATED = 'order_status_updated';
    case ORDER_CANCELLED = 'order_cancelled';
    case ORDER_REFUND_REQUESTED = 'order_refund_requested';
    case ORDER_COMPLETED = 'order_completed';
    case ORDER_ASSIGNED_TO_DRIVER = 'order_assigned_to_driver';

    // Inventory
    case PRODUCT_LOW_STOCK = 'product_low_stock';
    case PRODUCT_OUT_OF_STOCK = 'product_out_of_stock';
    case PRODUCT_ADDED = 'product_added';

    // Payments
    case PAYMENT_SUCCESS = 'payment_success';
    case PAYMENT_FAILED = 'payment_failed';
    case PAYMENT_REFUNDED = 'payment_refunded';

    // Users & Customers
    case CUSTOMER_REGISTERED = 'customer_registered';
    case NEWSLETTER_SUBSCRIBED = 'newsletter_subscribed';
    case CONTACT_MESSAGE = 'contact_message';
    case CHAT_MESSAGE = 'chat_message';

    // Ratings
    case PRODUCT_RATED = 'product_rated';
    case SITE_RATED = 'site_rated';

    // Services
    case SERVICE_SUBSCRIBED = 'service_subscribed';
}
