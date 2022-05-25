<?php

defined('BASEPATH') or exit('No direct script access allowed');

if (!$CI->db->table_exists(db_prefix() . 'inspections')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "inspections` (
      `id` int(11) NOT NULL,
      `staff_id` int(11) NOT NULL DEFAULT 0,
      `sent` tinyint(1) NOT NULL DEFAULT 0,
      `datesend` datetime DEFAULT NULL,
      `clientid` int(11) NOT NULL DEFAULT 0,
      `deleted_customer_name` varchar(100) DEFAULT NULL,
      `project_id` int(11) NOT NULL DEFAULT 0,
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
      ADD KEY `date` (`date`);');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'inspections`
      MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1');
}


if (!$CI->db->table_exists(db_prefix() . 'inspection_members')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "inspection_members` (
      `id` int(11) NOT NULL,
      `inspection_id` int(11) NOT NULL DEFAULT 0,
      `staff_id` int(11) NOT NULL DEFAULT 0
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'inspection_members`
      ADD PRIMARY KEY (`id`),
      ADD KEY `staff_id` (`staff_id`),
      ADD KEY `inspection_id` (`inspection_id`) USING BTREE;');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'inspection_members`
      MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1');
}

if (!$CI->db->table_exists(db_prefix() . 'inspection_activity')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "inspection_activity` (
  `id` int(11) NOT NULL,
  `rel_type` varchar(20) DEFAULT NULL,
  `rel_id` int(11) NOT NULL,
  `description` text NOT NULL,
  `additional_data` text DEFAULT NULL,
  `staffid` varchar(11) DEFAULT NULL,
  `full_name` varchar(100) DEFAULT NULL,
  `date` datetime NOT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'inspection_activity`
        ADD PRIMARY KEY (`id`),
        ADD KEY `date` (`date`);');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'inspection_activity`
      MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1');
}


$CI->db->query("
INSERT INTO `tblemailtemplates` (`type`, `slug`, `language`, `name`, `subject`, `message`, `fromname`, `fromemail`, `plaintext`, `active`, `order`) VALUES
('inspection', 'inspection-send-to-client', 'english', 'Send inspection to Customer', 'inspection # {inspection_number} created', '<span style=\"font-size: 12pt;\">Dear {contact_firstname} {contact_lastname}</span><br /><br /><span style=\"font-size: 12pt;\">Please find the attached inspection <strong># {inspection_number}</strong></span><br /><br /><span style=\"font-size: 12pt;\"><strong>inspection status:</strong> {inspection_status}</span><br /><br /><span style=\"font-size: 12pt;\">You can view the inspection on the following link: <a href=\"{inspection_link}\">{inspection_number}</a></span><br /><br /><span style=\"font-size: 12pt;\">We look forward to your communication.</span><br /><br /><span style=\"font-size: 12pt;\">Kind Regards,</span><br /><span style=\"font-size: 12pt;\">{email_signature}<br /></span>', '{companyname} | CRM', '', 0, 1, 0),
('inspection', 'inspection-already-send', 'english', 'inspection Already Sent to Customer', 'inspection # {inspection_number} ', '<span style=\"font-size: 12pt;\">Dear {contact_firstname} {contact_lastname}</span><br /> <br /><span style=\"font-size: 12pt;\">Thank you for your inspection request.</span><br /> <br /><span style=\"font-size: 12pt;\">You can view the inspection on the following link: <a href=\"{inspection_link}\">{inspection_number}</a></span><br /> <br /><span style=\"font-size: 12pt;\">Please contact us for more information.</span><br /> <br /><span style=\"font-size: 12pt;\">Kind Regards,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span>', '{companyname} | CRM', '', 0, 1, 0),
('inspection', 'inspection-declined-to-staff', 'english', 'inspection Declined (Sent to Staff)', 'Customer Declined inspection', '<span style=\"font-size: 12pt;\">Hi</span><br /> <br /><span style=\"font-size: 12pt;\">Customer ({client_company}) declined inspection with number <strong># {inspection_number}</strong></span><br /> <br /><span style=\"font-size: 12pt;\">You can view the inspection on the following link: <a href=\"{inspection_link}\">{inspection_number}</a></span><br /> <br /><span style=\"font-size: 12pt;\">{email_signature}</span>', '{companyname} | CRM', '', 0, 1, 0),
('inspection', 'inspection-accepted-to-staff', 'english', 'inspection Accepted (Sent to Staff)', 'Customer Accepted inspection', '<span style=\"font-size: 12pt;\">Hi</span><br /> <br /><span style=\"font-size: 12pt;\">Customer ({client_company}) accepted inspection with number <strong># {inspection_number}</strong></span><br /> <br /><span style=\"font-size: 12pt;\">You can view the inspection on the following link: <a href=\"{inspection_link}\">{inspection_number}</a></span><br /> <br /><span style=\"font-size: 12pt;\">{email_signature}</span>', '{companyname} | CRM', '', 0, 1, 0),
('inspection', 'inspection-thank-you-to-customer', 'english', 'Thank You Email (Sent to Customer After Accept)', 'Thank for you accepting inspection', '<span style=\"font-size: 12pt;\">Dear {contact_firstname} {contact_lastname}</span><br /> <br /><span style=\"font-size: 12pt;\">Thank for for accepting the inspection.</span><br /> <br /><span style=\"font-size: 12pt;\">We look forward to doing business with you.</span><br /> <br /><span style=\"font-size: 12pt;\">We will contact you as soon as possible.</span><br /> <br /><span style=\"font-size: 12pt;\">Kind Regards,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span>', '{companyname} | CRM', '', 0, 1, 0),
('inspection', 'inspection-expiry-reminder', 'english', 'inspection Expiration Reminder', 'inspection Expiration Reminder', '<p><span style=\"font-size: 12pt;\">Hello {contact_firstname} {contact_lastname}</span><br /><br /><span style=\"font-size: 12pt;\">The inspection with <strong># {inspection_number}</strong> will expire on <strong>{inspection_expirydate}</strong></span><br /><br /><span style=\"font-size: 12pt;\">You can view the inspection on the following link: <a href=\"{inspection_link}\">{inspection_number}</a></span><br /><br /><span style=\"font-size: 12pt;\">Kind Regards,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span></p>', '{companyname} | CRM', '', 0, 1, 0),
('inspection', 'inspection-send-to-client', 'english', 'Send inspection to Customer', 'inspection # {inspection_number} created', '<span style=\"font-size: 12pt;\">Dear {contact_firstname} {contact_lastname}</span><br /><br /><span style=\"font-size: 12pt;\">Please find the attached inspection <strong># {inspection_number}</strong></span><br /><br /><span style=\"font-size: 12pt;\"><strong>inspection status:</strong> {inspection_status}</span><br /><br /><span style=\"font-size: 12pt;\">You can view the inspection on the following link: <a href=\"{inspection_link}\">{inspection_number}</a></span><br /><br /><span style=\"font-size: 12pt;\">We look forward to your communication.</span><br /><br /><span style=\"font-size: 12pt;\">Kind Regards,</span><br /><span style=\"font-size: 12pt;\">{email_signature}<br /></span>', '{companyname} | CRM', '', 0, 1, 0),
('inspection', 'inspection-already-send', 'english', 'inspection Already Sent to Customer', 'inspection # {inspection_number} ', '<span style=\"font-size: 12pt;\">Dear {contact_firstname} {contact_lastname}</span><br /> <br /><span style=\"font-size: 12pt;\">Thank you for your inspection request.</span><br /> <br /><span style=\"font-size: 12pt;\">You can view the inspection on the following link: <a href=\"{inspection_link}\">{inspection_number}</a></span><br /> <br /><span style=\"font-size: 12pt;\">Please contact us for more information.</span><br /> <br /><span style=\"font-size: 12pt;\">Kind Regards,</span><br /><span style=\"font-size: 12pt;\">{email_signature}</span>', '{companyname} | CRM', '', 0, 1, 0),
('inspection', 'inspection-declined-to-staff', 'english', 'inspection Declined (Sent to Staff)', 'Customer Declined inspection', '<span style=\"font-size: 12pt;\">Hi</span><br /> <br /><span style=\"font-size: 12pt;\">Customer ({client_company}) declined inspection with number <strong># {inspection_number}</strong></span><br /> <br /><span style=\"font-size: 12pt;\">You can view the inspection on the following link: <a href=\"{inspection_link}\">{inspection_number}</a></span><br /> <br /><span style=\"font-size: 12pt;\">{email_signature}</span>', '{companyname} | CRM', '', 0, 1, 0),
('inspection', 'inspection-accepted-to-staff', 'english', 'inspection Accepted (Sent to Staff)', 'Customer Accepted inspection', '<span style=\"font-size: 12pt;\">Hi</span><br /> <br /><span style=\"font-size: 12pt;\">Customer ({client_company}) accepted inspection with number <strong># {inspection_number}</strong></span><br /> <br /><span style=\"font-size: 12pt;\">You can view the inspection on the following link: <a href=\"{inspection_link}\">{inspection_number}</a></span><br /> <br /><span style=\"font-size: 12pt;\">{email_signature}</span>', '{companyname} | CRM', '', 0, 1, 0),
('inspection', 'staff-added-as-project-member', 'english', 'Staff Added as Project Member', 'New project assigned to you', '<p>Hi <br /><br />New inspection has been assigned to you.<br /><br />You can view the inspection on the following link <a href=\"{inspection_link}\">inspection__number</a><br /><br />{email_signature}</p>', '{companyname} | CRM', '', 0, 1, 0),
('inspection', 'inspection-accepted-to-staff', 'english', 'inspection Accepted (Sent to Staff)', 'Customer Accepted inspection', '<span style=\"font-size: 12pt;\">Hi</span><br /> <br /><span style=\"font-size: 12pt;\">Customer ({client_company}) accepted inspection with number <strong># {inspection_number}</strong></span><br /> <br /><span style=\"font-size: 12pt;\">You can view the inspection on the following link: <a href=\"{inspection_link}\">{inspection_number}</a></span><br /> <br /><span style=\"font-size: 12pt;\">{email_signature}</span>', '{companyname} | CRM', '', 0, 1, 0);
");
/*
 *
 */

// Add options for inspections
add_option('delete_only_on_last_inspection', 1);
add_option('inspection_prefix', 'IST-');
add_option('next_inspection_number', 1);
add_option('default_inspection_assigned', 9);
add_option('inspection_number_decrement_on_delete', 0);
add_option('inspection_number_format', 4);
add_option('inspection_year', date('Y'));
add_option('exclude_inspection_from_client_area_with_draft_status', 1);
add_option('predefined_regulation_of_paa', 'In accordance with Law No. 1 of 1970 and Regulation of the Minister of Manpower No. Per-08/MEN/2020 Regarding Lifting And Transporting Aircraft.');
add_option('predefined_regulation_of_ptp', 'In accordance with Law No. 1 of 1970 and Regulation of the Minister of Manpower No. Per-08/MEN/2020 Regarding Lifting And Transporting Aircraft.');
add_option('predefined_regulation_of_pubt', 'In accordance with Law No. 1 of 1970 and Regulation of the Minister of Manpower No. Per-08/MEN/2020 Regarding Lifting And Transporting Aircraft.');
add_option('predefined_regulation_of_iil', 'In accordance with Law No. 1 of 1970 and Regulation of the Minister of Manpower No. Per-08/MEN/2020 Regarding Lifting And Transporting Aircraft.');
add_option('predefined_regulation_of_iip', 'In accordance with Law No. 1 of 1970 and Regulation of the Minister of Manpower No. Per-08/MEN/2020 Regarding Lifting And Transporting Aircraft.');
add_option('predefined_regulation_of_lie', 'In accordance with Law No. 1 of 1970 and Regulation of the Minister of Manpower No. Per-08/MEN/2020 Regarding Lifting And Transporting Aircraft.');
add_option('predefined_regulation_of_ipk', 'In accordance with Law No. 1 of 1970 and Regulation of the Minister of Manpower No. Per-08/MEN/2020 Regarding Lifting And Transporting Aircraft.');

add_option('predefined_terms_inspection', '- Dokumen ini diterbitkan dari sistem CRM, tidak memerlukan tanda tangan dari PT. Cipta Mas Jaya');
add_option('inspection_due_after', 1);
add_option('allow_staff_view_inspections_assigned', 1);
add_option('show_assigned_on_inspections', 1);
add_option('require_client_logged_in_to_view_inspection', 0);

add_option('show_project_on_inspection', 1);
add_option('inspections_pipeline_limit', 1);
add_option('default_inspections_pipeline_sort', 1);
add_option('inspection_accept_identity_confirmation', 1);
add_option('inspection_qrcode_size', '160');




/*

DROP TABLE `tblinspections`;
DROP TABLE `tblinspection_activity`, `tblinspection_members`;
delete FROM `tbloptions` WHERE `name` LIKE '%inspection%';
DELETE FROM `tblemailtemplates` WHERE `type` LIKE 'inspection';



*/