<?php defined('BASEPATH') or exit('No direct script access allowed');


if (!$CI->db->table_exists(db_prefix() . 'alarm_kebakaran')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "alarm_kebakaran` (
      `id` int(11) NOT NULL,
      `rel_id` int(11) DEFAULT NULL,
      `task_id` int(11) DEFAULT NULL,
      `nama_perusahaan` varchar(60) DEFAULT NULL,
      `alamat_perusahaan` varchar(60) DEFAULT NULL,
      `lokasi` varchar(50) DEFAULT NULL,
      `jenis_pesawat` varchar(30) NOT NULL,
      `nama_pesawat` varchar(50) DEFAULT NULL,
      `pabrik_pembuat` varchar(50) DEFAULT NULL,
      `tahun_pemasangan` smallint(4) DEFAULT NULL,
      `merk` varchar(30) DEFAULT NULL,
      `instalatir` varchar(30) DEFAULT NULL,
      `jumlah_mca` varchar(20) DEFAULT NULL,
      `jumlah_smoke_detector` varchar(30) DEFAULT NULL,
      `jumlah_alarm_bell` varchar(20) DEFAULT NULL,
      `jumlah_alarm_lamp` varchar(20) DEFAULT NULL,
      `pemeriksaan_dokumen` tinyint(1) DEFAULT NULL,
      `pemeriksaan_visual` tinyint(1) DEFAULT NULL,
      `pemeriksaan_pengaman` tinyint(1) DEFAULT NULL,
      `pengujian_operasional` tinyint(1) DEFAULT NULL,
      `kesimpulan` text DEFAULT NULL,
      `temuan` text DEFAULT NULL,
      `regulasi` text DEFAULT NULL,
      `jenis_pemeriksaan` varchar(30) DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'alarm_kebakaran`
      ADD PRIMARY KEY (`id`),
      ADD UNIQUE KEY `rel_id_task_id` (`rel_id`,`task_id`) USING BTREE,
      ADD KEY `rel_id` (`rel_id`),
      ADD KEY `nama_perusahaan` (`nama_perusahaan`),
      ADD KEY `jenis_pesawat` (`jenis_pesawat`),
      ADD KEY `task_id` (`task_id`);');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'alarm_kebakaran`
      MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1');
}

