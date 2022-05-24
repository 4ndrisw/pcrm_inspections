<?php defined('BASEPATH') or exit('No direct script access allowed');?>

<div class ="table-responsive">
  <table id="<?= 'inspection-'.$inspection->id ?>" class="table inspection table-bordered">
     <tbody>
        <tr>
           <td style="width:20%">Nama Perusahaan</td>
           <td style="width:2%">:</td>
           <td><?= get_inspection_company_by_clientid($inspection->clientid) ?></td>      
        </tr>
        <tr>
           <td style="width:20%">Alamat Perusahaan</td>
           <td style="width:2%">:</td>
           <td><?= get_inspection_company_address($inspection->id) ?></td>      
        </tr>
        <tr>
           <td style="width:20%">Jenis Pesawat</td>
           <td style="width:2%">:</td>
           <td><?= render_tags(get_tags_in($inspection->id, 'inspection'))?></td>      
        </tr>
        <tr>
           <td style="width:20%">Nama Pesawat</td>
           <td style="width:2%">:</td>
           <td><?= $equipment->nama_pesawat ?></td>      
        </tr>
        <tr>
           <td style="width:20%">Nomor Seri / Nomor Unit</td>
           <td style="width:2%">:</td>
           <td><?= $equipment->nomor_seri . ' / ' . $equipment->nomor_unit ?></td>      
        </tr>
        <tr>
           <td style="width:20%">Kapasitas</td>
           <td style="width:2%">:</td>
           <td><?= $equipment->kapasitas .' '. $equipment->satuan_kapasitas?></td>      
        </tr>
     </tbody>
  </table>
</div>

<div class ="table-responsive">
  <?php 
     $pemeriksaan_dokumen_t = '&#x2610';
     if($equipment->pemeriksaan_dokumen == 1){
        $pemeriksaan_dokumen_t = '&#x2611';
     }
     $pemeriksaan_dokumen_f = '&#x2610';
     if($equipment->pemeriksaan_dokumen == 0){
        $pemeriksaan_dokumen_f = '&#x2611';
     }
     $pemeriksaan_dokumen_n = '&#x2610';
     if($equipment->pemeriksaan_dokumen == 2){
        $pemeriksaan_dokumen_n = '&#x2611';
     }

     $pemeriksaan_visual_t = '&#x2610';
     if($equipment->pemeriksaan_visual == 1){
        $pemeriksaan_visual_t = '&#x2611';
     }
     $pemeriksaan_visual_f = '&#x2610';
     if($equipment->pemeriksaan_visual == 0){
        $pemeriksaan_visual_f = '&#x2611';
     }
     $pemeriksaan_visual_n = '&#x2610';
     if($equipment->pemeriksaan_visual == 2){
        $pemeriksaan_visual_n = '&#x2611';
     }

     $pemeriksaan_pengaman_t = '&#x2610';
     if($equipment->pemeriksaan_pengaman == 1){
        $pemeriksaan_pengaman_t = '&#x2611';
     }
     $pemeriksaan_pengaman_f = '&#x2610';
     if($equipment->pemeriksaan_pengaman == 0){
        $pemeriksaan_pengaman_f = '&#x2611';
     }
     $pemeriksaan_pengaman_n = '&#x2610';
     if($equipment->pemeriksaan_pengaman == 2){
        $pemeriksaan_pengaman_n = '&#x2611';
     }

     $pengujian_penetrant_t = '&#x2610';
     if($equipment->pengujian_penetrant == 1){
        $pengujian_penetrant_t = '&#x2611';
     }
     $pengujian_penetrant_f = '&#x2610';
     if($equipment->pengujian_penetrant == 0){
        $pengujian_penetrant_f = '&#x2611';
     }
     $pengujian_penetrant_n = '&#x2610';
     if($equipment->pengujian_penetrant == 2){
        $pengujian_penetrant_n = '&#x2611';
     }
     
     $pengujian_operasional_t = '&#x2610';
     if($equipment->pengujian_operasional == 1){
        $pengujian_operasional_t = '&#x2611';
     }
     $pengujian_operasional_f = '&#x2610';
     if($equipment->pengujian_operasional == 0){
        $pengujian_operasional_f = '&#x2611';
     }
     $pengujian_operasional_n = '&#x2610';
     if($equipment->pengujian_operasional == 2){
        $pengujian_operasional_n = '&#x2611';
     }
  ?>
  <table class="table table-bordered">
     <tbody>
        <tr class="pemeriksaan_dokumen">
           <td width="32%">Pemeriksan Dokumen</td>
           <td width="1%">:</td>
           <td width="22%"><span style='font-size:2.5rem;'><?= $pemeriksaan_dokumen_t ?></span> Lengkap</td>
           <td width="22%"><span style='font-size:2.5rem;'><?= $pemeriksaan_dokumen_f ?></span> Tidak lengkap</td>
           <td width="22%"><span style='font-size:2.5rem;'><?= $pemeriksaan_dokumen_n ?></span> Tidak ada</td>
        </tr>
        <tr class="pemeriksaan_visual">
           <td width="32%">Pemeriksan Visual</td>
           <td width="1%">:</td>
           <td width="22%"><span style='font-size:2.5rem;'><?= $pemeriksaan_visual_t ?></span> Baik</td>
           <td width="22%"><span style='font-size:2.5rem;'><?= $pemeriksaan_visual_f ?></span> Tidak baik</td>
           <td width="22%"><span style='font-size:2.5rem;'><?= $pemeriksaan_visual_n ?></span> Tidak ada</td>
        </tr>
        <tr class="pemeriksaan_pengaman">
           <td width="32%">Pemeriksan Perlengkapan Pengaman</td>
           <td width="1%">:</td>
           <td width="22%"><span style='font-size:2.5rem;'><?= $pemeriksaan_pengaman_t ?> </span> Baik</td>
           <td width="22%"><span style='font-size:2.5rem;'><?= $pemeriksaan_pengaman_f ?> </span> Tidak baik</td>
           <td width="22%"><span style='font-size:2.5rem;'><?= $pemeriksaan_pengaman_n ?> </span> Tidak ada</td>
        </tr>
        <tr class="pengujian_penetrant">
           <td width="32%">Pengujian NDT (Penetrant)</td>
           <td width="1%">:</td>
           <td width="22%"><span style='font-size:2.5rem;'><?= $pengujian_penetrant_t ?> </span> Baik</td>
           <td width="22%"><span style='font-size:2.5rem;'><?= $pengujian_penetrant_f ?> </span> Tidak baik</td>
           <td width="22%"><span style='font-size:2.5rem;'><?= $pengujian_penetrant_n ?> </span> Tidak ada</td>
        </tr>
        <tr class="pengujian_operasional">
           <td width="32%">Pengujian Operasional</td>
           <td width="1%">:</td>
           <td width="22%"><span style='font-size:2.5rem;'><?= $pengujian_operasional_t ?> </span> Baik</td>
           <td width="22%"><span style='font-size:2.5rem;'><?= $pengujian_operasional_f ?> </span> Tidak baik</td>
           <td width="22%"><span style='font-size:2.5rem;'><?= $pengujian_operasional_n ?> </span> Tidak ada</td>
        </tr>
     </tbody>
  </table>
</div>	    