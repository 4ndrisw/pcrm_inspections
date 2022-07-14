<?php defined('BASEPATH') or exit('No direct script access allowed');


if (!$CI->db->table_exists(db_prefix() . 'inspection_items')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "inspection_items` (
      `id` int(11) NOT NULL,
      `inspection_id` int(11) DEFAULT NULL,
      `project_id` int(11) NOT NULL,
      `task_id` int(11) NOT NULL,
      `equipment_name` varchar(60) DEFAULT NULL,
      `flag` tinyint(1) DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'inspection_items`
      ADD PRIMARY KEY (`id`),
      ADD UNIQUE KEY `inspection_id_task_id` (`inspection_id`,`task_id`) USING BTREE,
      ADD KEY `project_id` (`project_id`),
      ADD KEY `task_id` (`task_id`),
      ADD KEY `inspection_id` (`inspection_id`) USING BTREE;');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'inspection_items`
      MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1');
}

