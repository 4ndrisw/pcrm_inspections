<?php defined('BASEPATH') or exit('No direct script access allowed');


if (!$CI->db->table_exists(db_prefix() . 'tangki')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "tangki` (
      `id` int(11) NOT NULL,
      `rel_id` int(11) DEFAULT NULL,
      `task_id` int(11) DEFAULT NULL,
      `nama_perusahaan` varchar(60) DEFAULT NULL,
      `alamat_perusahaan` varchar(60) DEFAULT NULL,
      `lokasi` varchar(50) DEFAULT NULL,
      `jenis_pesawat` varchar(30) NOT NULL,
      `nama_pesawat` varchar(50) DEFAULT NULL,
      `pabrik_pembuat` varchar(50) DEFAULT NULL,
      `tahun_pembuatan` smallint(4) DEFAULT NULL,
      `merk` varchar(30) DEFAULT NULL,
      `nomor_seri` varchar(20) DEFAULT NULL,
      `nomor_unit` varchar(20) DEFAULT NULL,
      `type_model` varchar(30) DEFAULT NULL,
      `kapasitas` varchar(20) DEFAULT NULL,
      `digunakan_untuk` varchar(60) DEFAULT NULL,
      `pemeriksaan_dokumen` tinyint(1) DEFAULT NULL,
      `pemeriksaan_visual` tinyint(1) DEFAULT NULL,
      `pemeriksaan_pengaman` tinyint(1) DEFAULT NULL,
      `pengujian_penetrant` tinyint(1) DEFAULT NULL,
      `pengujian_operasional` tinyint(1) DEFAULT NULL,
      `kesimpulan` text DEFAULT NULL,
      `temuan` text DEFAULT NULL,
      `regulasi` text DEFAULT NULL,
      `jenis_pemeriksaan` varchar(30) DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'tangki`
      ADD PRIMARY KEY (`id`),
      ADD UNIQUE KEY `rel_id_task_id` (`rel_id`,`task_id`) USING BTREE,
      ADD KEY `rel_id` (`rel_id`),
      ADD KEY `nama_perusahaan` (`nama_perusahaan`),
      ADD KEY `jenis_pesawat` (`jenis_pesawat`),
        ADD KEY `task_id` (`task_id`);');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'tangki`
      MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1');
}

