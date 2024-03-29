<?php defined('BASEPATH') or exit('No direct script access allowed');


if (!$CI->db->table_exists(db_prefix() . 'listrik')) {
    $CI->db->query('CREATE TABLE `' . db_prefix() . "listrik` (
      `id` int(11) NOT NULL,
      `rel_id` int(11) DEFAULT NULL,
      `task_id` int(11) DEFAULT NULL,
      `nama_perusahaan` varchar(60) DEFAULT NULL,
      `alamat_perusahaan` varchar(60) DEFAULT NULL,
      `lokasi` varchar(50) DEFAULT NULL,
      `jenis_pesawat` varchar(30) NOT NULL,
      `nama_pesawat` varchar(50) DEFAULT NULL,
      `daya_penerangan` varchar(30) DEFAULT NULL,
      `daya_produksi` varchar(30) DEFAULT NULL,
      `daya_tenaga` varchar(30) DEFAULT NULL,
      `daya_terpasang` varchar(30) DEFAULT NULL,
      `sumber_tenaga` varchar(30) DEFAULT NULL,
      `jenis_arus` varchar(30) DEFAULT NULL,
      `pemeriksaan_dokumen` tinyint(1) DEFAULT NULL,
      `pemeriksaan_visual` tinyint(1) DEFAULT NULL,
      `pemeriksaan_pengaman` tinyint(1) DEFAULT NULL,
      `pengujian_grounding` tinyint(1) DEFAULT NULL,
      `pengujian_kapasitas_hantar` tinyint(1) DEFAULT NULL,
      `pengujian_operasional` tinyint(1) DEFAULT NULL,
      `kesimpulan` text DEFAULT NULL,
      `temuan` text DEFAULT NULL,
      `regulasi` text DEFAULT NULL,
      `jenis_pemeriksaan` varchar(30) DEFAULT NULL
    ) ENGINE=InnoDB DEFAULT CHARSET=" . $CI->db->char_set . ';');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'listrik`
      ADD PRIMARY KEY (`id`),
      ADD UNIQUE KEY `rel_id_task_id` (`rel_id`,`task_id`) USING BTREE,
      ADD KEY `rel_id` (`rel_id`),
      ADD KEY `nama_perusahaan` (`nama_perusahaan`),
      ADD KEY `jenis_pesawat` (`jenis_pesawat`),
        ADD KEY `task_id` (`task_id`);');

    $CI->db->query('ALTER TABLE `' . db_prefix() . 'listrik`
      MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=1');
}

