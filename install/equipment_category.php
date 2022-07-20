<?php defined('BASEPATH') or exit('No direct script access allowed');


if (!$CI->db->table_exists(db_prefix() . 'equipment_category')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "equipment_category` (
      `id` int(11) NOT NULL,
      `code` varchar(4) DEFAULT NULL,
      `title` varchar(30) DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');


    $CI->db->query("
      INSERT INTO `tblequipment_category` (`id`, `code`, `title`) VALUES
      (1, 'paa', 'Pesawat Angkat dan Angkut'),
      (2, 'pubt', 'Pesawat Uap dan Bejana Tekan'),
      (3, 'ptp', 'Pesawat Tenaga dan Produksi'),
      (4, 'lie', 'Elevator dan Eskalator');
    ");



    $CI->db->query('ALTER TABLE `' . db_prefix() . 'equipment_category`
      ADD PRIMARY KEY (`id`),
      ADD UNIQUE KEY `inspection_id_task_id` (`inspection_id`,`task_id`) USING BTREE,
      ADD KEY `project_id` (`project_id`),
      ADD KEY `task_id` (`task_id`),
      ADD KEY `inspection_id` (`inspection_id`) USING BTREE;');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'equipment_category`
      MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1');
}

