<?php

defined('BASEPATH') or exit('No direct script access allowed');
require_once('install/inspections.php');
require_once('install/inspection_members.php');
require_once('install/inspection_activity.php');
require_once('install/inspection_items.php');
require_once('install/equipment_category.php');

require_once('install/alarm_kebakaran.php');
require_once('install/backhoe_loader.php');
require_once('install/bejana_tekan.php');
require_once('install/bejana_uap.php');
require_once('install/boiler.php');
require_once('install/bpv.php');
require_once('install/chain_hoist.php');
require_once('install/compressor.php');
require_once('install/conveyor.php');
require_once('install/elevator.php');
require_once('install/excavator.php');
require_once('install/forklift.php');
require_once('install/gantry.php');
require_once('install/hoist_crane.php');
require_once('install/hydrant.php');
require_once('install/inspection_activity.php');
require_once('install/inspection_items.php');
require_once('install/inspection_members.php');
require_once('install/inspections.php');
require_once('install/jib_crane.php');
require_once('install/lift_barang.php');
require_once('install/listrik.php');
require_once('install/mesin_bubut.php');
require_once('install/mesin_produksi.php');
require_once('install/mobil_crane.php');
require_once('install/motor_diesel.php');
require_once('install/overhead_crane.php');
require_once('install/pesawat_tenaga.php');
require_once('install/petir.php');
require_once('install/sterilizer.php');
require_once('install/tangki.php');
require_once('install/tanur.php');
require_once('install/towing.php');
require_once('install/trucktor.php');
require_once('install/wheel_loader.php');





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
add_option('predefined_regulation_of_pubt', 'In accordance with Law No. 1 of 1970 and Regulation of the Minister of Manpower No. Per-08/MEN/2020 Regarding Lifting And Transporting Aircraft.');
add_option('predefined_regulation_of_lie', 'In accordance with Law No. 1 of 1970 and Regulation of the Minister of Manpower No. Per-08/MEN/2020 Regarding Lifting And Transporting Aircraft.');
add_option('predefined_regulation_of_ipp', 'In accordance with Law No. 1 of 1970 and Regulation of the Minister of Manpower No. Per-08/MEN/2020 Regarding Lifting And Transporting Aircraft.');
add_option('predefined_regulation_of_iil', 'In accordance with Law No. 1 of 1970 and Regulation of the Minister of Manpower No. Per-08/MEN/2020 Regarding Lifting And Transporting Aircraft.');
add_option('predefined_regulation_of_ipk', 'In accordance with Law No. 1 of 1970 and Regulation of the Minister of Manpower No. Per-08/MEN/2020 Regarding Lifting And Transporting Aircraft.');
add_option('predefined_regulation_of_ptp', 'In accordance with Law No. 1 of 1970 and Regulation of the Minister of Manpower No. Per-08/MEN/2020 Regarding Lifting And Transporting Aircraft.');

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

DROP TABLE if EXISTS `tblinspections`;
DROP TABLE if EXISTS `tblinspection_activity`, `tblinspection_members`;

DROP TABLE if EXISTS `tblinspection_items`;


DROP TABLE if EXISTS `tblalarm_kebakaran`;
DROP TABLE if EXISTS `tblbackhoe_loader`;
DROP TABLE if EXISTS `tblbejana_tekan`;
DROP TABLE if EXISTS `tblbejana_uap`;
DROP TABLE if EXISTS `tblboiler`;
DROP TABLE if EXISTS `tblbpv`;
DROP TABLE if EXISTS `tblchain_hoist`;
DROP TABLE if EXISTS `tblcompressor`;
DROP TABLE if EXISTS `tblconveyor`;
DROP TABLE if EXISTS `tblelevator`;
DROP TABLE if EXISTS `tblexcavator`;
DROP TABLE if EXISTS `tblforklift`;
DROP TABLE if EXISTS `tblgantry`;
DROP TABLE if EXISTS `tblhoist_crane`;
DROP TABLE if EXISTS `tblhydrant`;
DROP TABLE if EXISTS `tblinspection_activity`;
DROP TABLE if EXISTS `tblinspection_items`;
DROP TABLE if EXISTS `tblinspection_members`;
DROP TABLE if EXISTS `tblinspections`;
DROP TABLE if EXISTS `tbljib_crane`;
DROP TABLE if EXISTS `tbllift_barang`;
DROP TABLE if EXISTS `tbllistrik`;
DROP TABLE if EXISTS `tblmesin_bubut`;
DROP TABLE if EXISTS `tblmesin_produksi`;
DROP TABLE if EXISTS `tblmobil_crane`;
DROP TABLE if EXISTS `tblmotor_diesel`;
DROP TABLE if EXISTS `tbloverhead_crane`;
DROP TABLE if EXISTS `tblpesawat_tenaga`;
DROP TABLE if EXISTS `tblpetir`;
DROP TABLE if EXISTS `tblsterilizer`;
DROP TABLE if EXISTS `tbltangki`;
DROP TABLE if EXISTS `tbltanur`;
DROP TABLE if EXISTS `tbltowing`;
DROP TABLE if EXISTS `tbltrucktor`;
DROP TABLE if EXISTS `tblwheel_loader`;


delete FROM `tbloptions` WHERE `name` LIKE '%inspection%';
DELETE FROM `tblemailtemplates` WHERE `type` LIKE 'inspection';



*/