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
           <td style="width:20%">Nama Pesawat</td>
           <td style="width:2%">:</td>
           <td><?= isset($equipment['nama_pesawat']) ? $equipment['nama_pesawat'] : '' ?></td>      
        </tr>
        <tr>
           <td style="width:20%">Nomor Seri / Nomor Unit</td>
           <td style="width:2%">:</td>
           <td><?= (isset($equipment['nomor_seri']) ? $equipment['nomor_seri'] : '') . ' / ' . (isset($equipment['nomor_unit']) ? $equipment['nomor_unit'] : '') ?></td>      
        </tr>
        <tr>
           <td style="width:20%">Kapasitas</td>
           <td style="width:2%">:</td>
           <td><?= (isset($equipment['kapasitas']) ? $equipment['kapasitas'] : '') .' '. (isset($equipment['satuan_kapasitas']) ? $equipment['satuan_kapasitas'] : '') ?></td>      
        </tr>
     </tbody>
  </table>
</div>

<div class ="table-responsive">
  <?php 
     $pemeriksaan_dokumen_t = '&#9744;';
     if(isset($equipment['pemeriksaan_dokumen']) && ($equipment['pemeriksaan_dokumen'] == 1)){
        $pemeriksaan_dokumen_t = '&#9745;';
     }
     $pemeriksaan_dokumen_f = '&#9744;';
     if(isset($equipment['pemeriksaan_dokumen']) && ($equipment['pemeriksaan_dokumen'] == 2)){
        $pemeriksaan_dokumen_f = '&#9745;';
     }
     $pemeriksaan_dokumen_n = '&#9744;';
     if(isset($equipment['pemeriksaan_dokumen']) && ($equipment['pemeriksaan_dokumen'] == 3)){
        $pemeriksaan_dokumen_n = '&#9745;';
     }

     $pemeriksaan_visual_t = '&#9744;';
     if(isset($equipment['pemeriksaan_visual']) && $equipment['pemeriksaan_visual'] == 1){
        $pemeriksaan_visual_t = '&#9745;';
     }
     $pemeriksaan_visual_f = '&#9744;';
     if(isset($equipment['pemeriksaan_visual']) && $equipment['pemeriksaan_visual'] == 2){
        $pemeriksaan_visual_f = '&#9745;';
     }
     $pemeriksaan_visual_n = '&#9744;';
     if(isset($equipment['pemeriksaan_visual']) && $equipment['pemeriksaan_visual'] == 3){
        $pemeriksaan_visual_n = '&#9745;';
     }

     $pemeriksaan_pengaman_t = '&#9744;';
     if(isset($equipment['pemeriksaan_pengaman']) && $equipment['pemeriksaan_pengaman'] == 1){
        $pemeriksaan_pengaman_t = '&#9745;';
     }
     $pemeriksaan_pengaman_f = '&#9744;';
     if(isset($equipment['pemeriksaan_pengaman']) && $equipment['pemeriksaan_pengaman'] == 2){
        $pemeriksaan_pengaman_f = '&#9745;';
     }
     $pemeriksaan_pengaman_n = '&#9744;';
     if(isset($equipment['pemeriksaan_pengaman']) && $equipment['pemeriksaan_pengaman'] == 3){
        $pemeriksaan_pengaman_n = '&#9745;';
     }

     $pengujian_penetrant_t = '&#9744;';
     if(isset($equipment['pengujian_penetrant']) && $equipment['pengujian_penetrant'] == 1){
        $pengujian_penetrant_t = '&#9745;';
     }
     $pengujian_penetrant_f = '&#9744;';
     if(isset($equipment['pengujian_penetrant']) && $equipment['pengujian_penetrant'] == 2){
        $pengujian_penetrant_f = '&#9745;';
     }
     $pengujian_penetrant_n = '&#9744;';
     if(isset($equipment['pengujian_penetrant']) && $equipment['pengujian_penetrant'] == 3){
        $pengujian_penetrant_n = '&#9745;';
     }

     $pengujian_thickness_t = '&#9744;';
     if(isset($equipment['pengujian_thickness']) && $equipment['pengujian_thickness'] == 1){
        $pengujian_thickness_t = '&#9745;';
     }
     $pengujian_thickness_f = '&#9744;';
     if(isset($equipment['pengujian_thickness']) && $equipment['pengujian_thickness'] == 2){
        $pengujian_thickness_f = '&#9745;';
     }
     $pengujian_thickness_n = '&#9744;';
     if(isset($equipment['pengujian_thickness']) && $equipment['pengujian_thickness'] == 3){
        $pengujian_thickness_n = '&#9745;';
     }

     $pengujian_infrared_t = '&#9744;';
     if(isset($equipment['pengujian_infrared']) && $equipment['pengujian_infrared'] == 1){
        $pengujian_infrared_t = '&#9745;';
     }
     $pengujian_infrared_f = '&#9744;';
     if(isset($equipment['pengujian_infrared']) && $equipment['pengujian_infrared'] == 2){
        $pengujian_infrared_f = '&#9745;';
     }
     $pengujian_infrared_n = '&#9744;';
     if(isset($equipment['pengujian_infrared']) && $equipment['pengujian_infrared'] == 3){
        $pengujian_infrared_n = '&#9745;';
     }

     $pengujian_operasional_t = '&#9744;';
     if(isset($equipment['pengujian_operasional']) && $equipment['pengujian_operasional'] == 1){
        $pengujian_operasional_t = '&#9745;';
     }
     $pengujian_operasional_f = '&#9744;';
     if(isset($equipment['pengujian_operasional']) && $equipment['pengujian_operasional'] == 2){
        $pengujian_operasional_f = '&#9745;';
     }
     $pengujian_operasional_n = '&#9744;';
     if(isset($equipment['pengujian_operasional']) && $equipment['pengujian_operasional'] == 3){
        $pengujian_operasional_n = '&#9745;';
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

        <?php if(isset($inspection->equipment_category) && ($inspection->equipment_category == 'paa' || $inspection->equipment_category == 'pubt')){ ?>
           <tr class="pengujian_penetrant">
              <td width="32%">Pengujian NDT (Penetrant)</td>
              <td width="1%">:</td>
              <td width="22%"><span style='font-size:2.5rem;'><?= $pengujian_penetrant_t ?> </span> Baik</td>
              <td width="22%"><span style='font-size:2.5rem;'><?= $pengujian_penetrant_f ?> </span> Tidak baik</td>
              <td width="22%"><span style='font-size:2.5rem;'><?= $pengujian_penetrant_n ?> </span> Tidak ada</td>
           </tr>         
        <?php } ?>
        <?php if(isset($inspection->equipment_category) && ($inspection->equipment_category == 'pubt')){ ?>
           <tr class="pengujian_thickness">
              <td width="32%">Pengujian NDT (thickness)</td>
              <td width="1%">:</td>
              <td width="22%"><span style='font-size:2.5rem;'><?= $pengujian_thickness_t ?> </span> Baik</td>
              <td width="22%"><span style='font-size:2.5rem;'><?= $pengujian_thickness_f ?> </span> Tidak baik</td>
              <td width="22%"><span style='font-size:2.5rem;'><?= $pengujian_thickness_n ?> </span> Tidak ada</td>
           </tr>         
        <?php } ?>

        <?php if(isset($inspection->equipment_category) && ($inspection->equipment_category == 'iil')){ ?>
           <tr class="pengujian_infrared">
              <td width="32%">Pengujian Termal Infrared</td>
              <td width="1%">:</td>
              <td width="22%"><span style='font-size:2.5rem;'><?= $pengujian_infrared_t ?> </span> Baik</td>
              <td width="22%"><span style='font-size:2.5rem;'><?= $pengujian_infrared_f ?> </span> Tidak baik</td>
              <td width="22%"><span style='font-size:2.5rem;'><?= $pengujian_infrared_n ?> </span> Tidak ada</td>
           </tr>         
        <?php } ?>

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