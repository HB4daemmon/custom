<?php
require_once("connection.php");

$conn = db_connect();
$sql = "INSERT INTO `sales_flat_order` ( `state`, `status`, `coupon_code`, `protect_code`, `shipping_description`, `is_virtual`, `store_id`,
        `customer_id`, `base_discount_amount`, `base_discount_canceled`, `base_discount_invoiced`, `base_discount_refunded`, `base_grand_total`, `base_shipping_amount`,
        `base_shipping_canceled`, `base_shipping_invoiced`, `base_shipping_refunded`, `base_shipping_tax_amount`, `base_shipping_tax_refunded`,
         `base_subtotal`, `base_subtotal_canceled`, `base_subtotal_invoiced`, `base_subtotal_refunded`, `base_tax_amount`, `base_tax_canceled`,
          `base_tax_invoiced`, `base_tax_refunded`, `base_to_global_rate`, `base_to_order_rate`, `base_total_canceled`, `base_total_invoiced`,
          `base_total_invoiced_cost`, `base_total_offline_refunded`, `base_total_online_refunded`, `base_total_paid`, `base_total_qty_ordered`,
           `base_total_refunded`, `discount_amount`, `discount_canceled`, `discount_invoiced`, `discount_refunded`, `grand_total`, `shipping_amount`,
            `shipping_canceled`, `shipping_invoiced`, `shipping_refunded`, `shipping_tax_amount`, `shipping_tax_refunded`, `store_to_base_rate`,
            `store_to_order_rate`, `subtotal`, `subtotal_canceled`, `subtotal_invoiced`, `subtotal_refunded`, `tax_amount`, `tax_canceled`, `tax_invoiced`,
             `tax_refunded`, `total_canceled`, `total_invoiced`, `total_offline_refunded`, `total_online_refunded`, `total_paid`, `total_qty_ordered`,
              `total_refunded`, `can_ship_partially`, `can_ship_partially_item`, `customer_is_guest`, `customer_note_notify`, `billing_address_id`,
              `customer_group_id`, `edit_increment`, `email_sent`, `forced_shipment_with_invoice`, `payment_auth_expiration`, `quote_address_id`, `quote_id`,
              `shipping_address_id`, `adjustment_negative`, `adjustment_positive`, `base_adjustment_negative`, `base_adjustment_positive`,
              `base_shipping_discount_amount`, `base_subtotal_incl_tax`, `base_total_due`, `payment_authorization_amount`, `shipping_discount_amount`,
               `subtotal_incl_tax`, `total_due`, `weight`, `customer_dob`, `increment_id`, `applied_rule_ids`, `base_currency_code`, `customer_email`,
                `customer_firstname`, `customer_lastname`, `customer_middlename`, `customer_prefix`, `customer_suffix`, `customer_taxvat`, `discount_description`,
                `ext_customer_id`, `ext_order_id`, `global_currency_code`, `hold_before_state`, `hold_before_status`, `order_currency_code`, `original_increment_id`,
                 `relation_child_id`, `relation_child_real_id`, `relation_parent_id`, `relation_parent_real_id`, `remote_ip`, `shipping_method`, `store_currency_code`,
                  `store_name`, `x_forwarded_for`, `customer_note`, `created_at`, `updated_at`, `total_item_count`, `customer_gender`, `hidden_tax_amount`,
                  `base_hidden_tax_amount`, `shipping_hidden_tax_amount`, `base_shipping_hidden_tax_amnt`, `hidden_tax_invoiced`, `base_hidden_tax_invoiced`,
                  `hidden_tax_refunded`, `base_hidden_tax_refunded`, `shipping_incl_tax`, `base_shipping_incl_tax`, `coupon_rule_name`, `paypal_ipn_customer_notified`,
                  `gift_message_id`) VALUES  ('new', 'pending', '', '', 'Flat Rate - Fixed', 0, 1, 1,
                   '0.0000', NULL, NULL, NULL, '3.0000', '0.0000', NULL, NULL, NULL, '0.0000', NULL, '3.0000', NULL, NULL, NULL, '0.0000',
                   NULL, NULL, NULL, '1.0000', '1.0000', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.0000', NULL, NULL, NULL, '3.0000', '0.0000',
                   NULL, NULL, NULL, '0.0000', NULL, '1.0000', '1.0000', '3.0000', NULL, NULL, NULL, '0.0000', NULL, NULL, NULL, NULL, NULL, NULL, NULL,
                    NULL, '3.0000', NULL, NULL, NULL, 0, 1, 1, 1, NULL, 1, NULL, NULL, NULL, 1, 2, NULL, NULL, NULL, NULL, '0.0000', '3.0000', NULL, NULL,
                     '0.0000', '3.0000', NULL, '0.0000', NULL, '100000004', '1', 'CNY', 'hb4daemon@163.com', 'daemon', 'wang', NULL, NULL, NULL, NULL,
                      '', NULL, NULL, 'CNY', NULL, NULL, 'CNY', NULL, NULL, NULL, NULL, NULL, '::1', 'flatrate_flatrate',
                       'CNY', 'Main Website\nMain Website Store\nDefault Store View', NULL, NULL, '2015-07-21 20:47:15', '2015-07-21 20:47:16', 1, NULL, '0.0000',
                       '0.0000', '0.0000', '0.0000', NULL, NULL, NULL, NULL, '0.0000', '0.0000', 'Free shipping', 0, NULL); ";


        $sql = " INSERT INTO `sales_flat_order_item` ( `order_id`, `parent_item_id`, `quote_item_id`, `store_id`, `created_at`, `updated_at`, `product_id`,
        `product_type`, `product_options`, `weight`, `is_virtual`, `sku`, `name`, `description`, `applied_rule_ids`, `additional_data`, `free_shipping`,
         `is_qty_decimal`, `no_discount`, `qty_backordered`, `qty_canceled`, `qty_invoiced`, `qty_ordered`, `qty_refunded`, `qty_shipped`, `base_cost`, `price`,
         `base_price`, `original_price`, `base_original_price`, `tax_percent`, `tax_amount`, `base_tax_amount`, `tax_invoiced`, `base_tax_invoiced`, `discount_percent`,
         `discount_amount`, `base_discount_amount`, `discount_invoiced`, `base_discount_invoiced`, `amount_refunded`, `base_amount_refunded`, `row_total`,
          `base_row_total`, `row_invoiced`, `base_row_invoiced`, `row_weight`, `base_tax_before_discount`, `tax_before_discount`, `ext_order_item_id`,
          `locked_do_invoice`, `locked_do_ship`, `price_incl_tax`, `base_price_incl_tax`, `row_total_incl_tax`, `base_row_total_incl_tax`, `hidden_tax_amount`,
          `base_hidden_tax_amount`, `hidden_tax_invoiced`, `base_hidden_tax_invoiced`, `hidden_tax_refunded`, `base_hidden_tax_refunded`, `is_nominal`, `tax_canceled`,
           `hidden_tax_canceled`, `tax_refunded`, `base_tax_refunded`, `discount_refunded`, `base_discount_refunded`, `gift_message_id`, `gift_message_available`,
           `base_weee_tax_applied_amount`, `base_weee_tax_applied_row_amnt`, `weee_tax_applied_amount`, `weee_tax_applied_row_amount`, `weee_tax_applied`,
           `weee_tax_disposition`, `weee_tax_row_disposition`, `base_weee_tax_disposition`, `base_weee_tax_row_disposition`) VALUES
            ( 4, NULL, 1, 1, '2015-07-21 20:47:16', '2015-07-21 20:47:16', 506, 'simple', '', '1.0000', 0, 'YXR000562', '【1元购】越南白心小火龙果(1个)', NULL, '1', NULL,
             0, 0, 0, NULL, '0.0000', '0.0000', '3.0000', '0.0000', '0.0000', NULL, '1.0000', '1.0000', '1.0000', '1.0000',
             '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000', '0.000', '0.0000', '0.0000', '0.0000', '0.0000', '0.0000',
              '3.0000', '3.0000', '0.0000', '0.0000', '0.0000', NULL, NULL, NULL, NULL, NULL, '1.0000', '1.0000', '3.0000', '3.0000',
              '0.0000', '0.0000', NULL, NULL, NULL, NULL, 0, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.0000', '0.0000', '0.0000',
              '0.0000', 'a:0:{}', '0.0000', '0.0000', '0.0000', '0.0000')";


        $sql = "INSERT INTO `sales_flat_order_grid` ( `status`, `store_id`, `store_name`, `customer_id`, `base_grand_total`, `base_total_paid`, `grand_total`,
                `total_paid`, `increment_id`, `base_currency_code`, `order_currency_code`, `shipping_name`, `billing_name`, `created_at`, `updated_at`) VALUES
                 ( 'pending', 1, 'Main Website\nMain Website Store\nDefault Store View', 1, '3.0000', NULL, '3.0000', NULL, '100000004', 'CNY', 'CNY', 'daemon wang', 'daemon wang', '2015-07-21 20:47:15', '2015-07-21 20:47:16');
        ";

        $sql = " INSERT INTO `sales_flat_order_address` (`parent_id`, `customer_address_id`, `quote_address_id`, `region_id`, `customer_id`, `fax`,
        `region`, `postcode`, `lastname`, `street`, `city`, `email`, `telephone`, `country_id`, `firstname`, `address_type`, `prefix`, `middlename`, `suffix`, `company`,
         `vat_id`, `vat_is_valid`, `vat_request_id`, `vat_request_date`, `vat_request_success`) VALUES
          ( 1, NULL, NULL, NULL, 1, NULL, 'Jiangsu', '210000', 'wang', 'address1\naddress2', 'Nanjing', 'hb4daemon@163.com', '15151834774', 'CN', 'daemon',
           'shipping', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL);
         ";

        $sql = " INSERT INTO `sales_flat_order_payment` ( `parent_id`, `base_shipping_captured`, `shipping_captured`, `amount_refunded`, `base_amount_paid`,
         `amount_canceled`, `base_amount_authorized`, `base_amount_paid_online`, `base_amount_refunded_online`, `base_shipping_amount`, `shipping_amount`, `amount_paid`,
         `amount_authorized`, `base_amount_ordered`, `base_shipping_refunded`, `shipping_refunded`, `base_amount_refunded`, `amount_ordered`, `base_amount_canceled`,
          `quote_payment_id`, `additional_data`, `cc_exp_month`, `cc_ss_start_year`, `echeck_bank_name`, `method`, `cc_debug_request_body`, `cc_secure_verify`,
           `protection_eligibility`, `cc_approval`, `cc_last4`, `cc_status_description`, `echeck_type`, `cc_debug_response_serialized`, `cc_ss_start_month`,
            `echeck_account_type`, `last_trans_id`, `cc_cid_status`, `cc_owner`, `cc_type`, `po_number`, `cc_exp_year`, `cc_status`, `echeck_routing_number`,
             `account_status`, `anet_trans_method`, `cc_debug_response_body`, `cc_ss_issue`, `echeck_account_name`, `cc_avs_status`, `cc_number_enc`, `cc_trans_id`,
              `paybox_request_number`, `address_status`, `additional_information`) VALUES
               ( 1, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0.0000', '0.0000', NULL, NULL, '3.0000', NULL, NULL, NULL, '3.0000', NULL, NULL,
                NULL, '0', '0', NULL, 'checkmo', NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL, NULL, NULL, NULL, NULL, '0', NULL, NULL,
                 NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL); ";

?>