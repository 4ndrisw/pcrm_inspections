<?php defined('BASEPATH') or exit('No direct script access allowed');


if (!$CI->db->table_exists(db_prefix() . 'inspections')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "inspections` (
      `id` int(11) NOT NULL,
      `staff_id` int(11) NOT NULL DEFAULT 0,
      `sent` tinyint(1) NOT NULL DEFAULT 0,
      `datesend` datetime DEFAULT NULL,
      `clientid` int(11) NOT NULL DEFAULT 0,
      `deleted_customer_name` varchar(100) DEFAULT NULL,
      `project_id` int(11) NOT NULL DEFAULT 0,
      `schedule_id` int(11) NOT NULL DEFAULT 0,
      `number` int(11) NOT NULL DEFAULT 0,
      `prefix` varchar(50) DEFAULT NULL,
      `number_format` int(11) NOT NULL DEFAULT 0,
      `formatted_number` varchar(20) DEFAULT NULL,
      `hash` varchar(32) DEFAULT NULL,
      `datecreated` datetime NOT NULL DEFAULT CURRENT_TIMESTAMP,
      `date` date DEFAULT NULL,
      `expirydate` date DEFAULT NULL,
      `addedfrom` int(11) NOT NULL DEFAULT 0,
      `status` int(11) NOT NULL DEFAULT 1,
      `clientnote` text DEFAULT NULL,
      `adminnote` text DEFAULT NULL,
      `jobreportid` int(11) DEFAULT NULL,
      `jobreport_date` datetime DEFAULT NULL,
      `terms` text DEFAULT NULL,
      `reference_no` varchar(100) DEFAULT NULL,
      `assigned` int(11) NOT NULL DEFAULT 0,

      `equipment_type` varchar(20) DEFAULT NULL,
      `equipment_category` varchar(4) DEFAULT NULL,
      `billing_street` varchar(200) DEFAULT NULL,
      `billing_city` varchar(100) DEFAULT NULL,
      `billing_state` varchar(100) DEFAULT NULL,
      `billing_zip` varchar(100) DEFAULT NULL,
      `billing_country` int(11) DEFAULT NULL,
      `shipping_street` varchar(200) DEFAULT NULL,
      `shipping_city` varchar(100) DEFAULT NULL,
      `shipping_state` varchar(100) DEFAULT NULL,
      `shipping_zip` varchar(100) DEFAULT NULL,
      `shipping_country` int(11) DEFAULT NULL,
      `include_shipping` tinyint(1) NOT NULL DEFAULT 0,
      `show_shipping_on_inspection` tinyint(1) NOT NULL DEFAULT 1,
      `show_quantity_as` int(11) NOT NULL DEFAULT 1,
      `pipeline_order` int(11) DEFAULT 1,
      `is_expiry_notified` int(11) NOT NULL DEFAULT 0,
      `signed` tinyint(1) NOT NULL DEFAULT 0,
      `acceptance_firstname` varchar(50) DEFAULT NULL,
      `acceptance_lastname` varchar(50) DEFAULT NULL,
      `acceptance_email` varchar(100) DEFAULT NULL,
      `acceptance_date` datetime DEFAULT NULL,
      `acceptance_ip` varchar(40) DEFAULT NULL,
      `signature` varchar(40) DEFAULT NULL,
      `short_link` varchar(100) DEFAULT NULL,
      `inspector_name` varchar(100) DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'inspections`
      ADD PRIMARY KEY (`id`),
      ADD UNIQUE( `number`),
      ADD KEY `signed` (`signed`),
      ADD KEY `status` (`status`),
      ADD KEY `clientid` (`clientid`),
      ADD KEY `project_id` (`project_id`),
      ADD KEY `schedule_id` (`schedule_id`),
      ADD KEY `date` (`date`);');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'inspections`
      MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1');
}
