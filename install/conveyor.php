<?php defined('BASEPATH') or exit('No direct script access allowed');


if (!$CI->db->table_exists(db_prefix() . 'conveyor')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "conveyor` (
      `id` int(11) NOT NULL,
      `rel_id` int(11) DEFAULT NULL,
      `task_id` int(11) DEFAULT NULL,
      `nama_perusahaan` varchar(60) DEFAULT NULL,
      `alamat_perusahaan` varchar(60) DEFAULT NULL,
      `nomor_seri` varchar(20) DEFAULT NULL,
      `nomor_unit` varchar(20) DEFAULT NULL,
      `jenis_pesawat` varchar(30) NOT NULL,
      `type_model` varchar(30) DEFAULT NULL,
      `nama_pesawat` varchar(50) DEFAULT NULL,
      `kapasitas` decimal(10,0) DEFAULT NULL,
      `satuan_kapasitas` varchar(10) DEFAULT NULL,
      `kesimpulan` text DEFAULT NULL,
      `temuan` text DEFAULT NULL,
      `regulasi` text DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'conveyor`
      ADD PRIMARY KEY (`id`),
      ADD UNIQUE KEY `rel_id_task_id` (`rel_id`,`task_id`) USING BTREE,
      ADD KEY `rel_id` (`rel_id`),
      ADD KEY `nama_perusahaan` (`nama_perusahaan`),
      ADD KEY `jenis_pesawat` (`jenis_pesawat`),
        ADD KEY `task_id` (`task_id`);');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'conveyor`
      MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1');
}

