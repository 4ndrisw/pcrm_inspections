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
           <td class="editable" data-field="nama_pesawat" data-jenis_pesawat="<?= $inspection->equipment_type ?>" data-inspection_id="<?= $inspection->id ?>" data-task_id="<?= $task->id ?>"><?= isset($equipment[0]['nama_pesawat']) ? $equipment[0]['nama_pesawat'] : '' ?></td>      
        </tr>
        <tr>
           <td style="width:20%">Nomor Seri</td>
           <td style="width:2%">:</td>
           <td class="editable" data-field="nomor_seri" data-jenis_pesawat="<?= $inspection->equipment_type ?>" data-inspection_id="<?= $inspection->id ?>" data-task_id="<?= $task->id ?>"><?= isset($equipment[0]['nomor_seri']) ? $equipment[0]['nomor_seri'] : '' ?></td>      
        </tr>
        <tr>
           <td style="width:20%">Nomor Unit</td>
           <td style="width:2%">:</td>
           <td class="editable" data-field="nomor_unit" data-jenis_pesawat="<?= $inspection->equipment_type ?>" data-inspection_id="<?= $inspection->id ?>" data-task_id="<?= $task->id ?>"><?= isset($equipment[0]['nomor_unit']) ? $equipment[0]['nomor_unit'] : '' ?></td>
        </tr>
        <tr>
           <td style="width:20%">Type / Model</td>
           <td style="width:2%">:</td>
           <td class="editable" data-field="type_model" data-jenis_pesawat="<?= $inspection->equipment_type ?>" data-inspection_id="<?= $inspection->id ?>" data-task_id="<?= $task->id ?>"><?= isset($equipment[0]['type_model']) ? $equipment[0]['type_model'] : '' ?></td>
        </tr>
        <tr>
           <td style="width:20%">Kapasitas</td>
           <td style="width:2%">:</td>
           <td class="editable" data-field="kapasitas" data-jenis_pesawat="<?= $inspection->equipment_type ?>" data-inspection_id="<?= $inspection->id ?>" data-task_id="<?= $task->id ?>"><?= isset($equipment[0]['kapasitas']) ? $equipment[0]['kapasitas'] : '' ?></td>
        </tr>
        <tr>
           <td style="width:20%">Satuan</td>
           <td style="width:2%">:</td>
           <td class="editable" data-field="satuan_kapasitas" data-jenis_pesawat="<?= $inspection->equipment_type ?>" data-inspection_id="<?= $inspection->id ?>" data-task_id="<?= $task->id ?>"><?= isset($equipment[0]['satuan_kapasitas']) ? $equipment[0]['satuan_kapasitas'] : '' ?></td>
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
        <tr>
           <td width="32%">Pengujian Operasional</td>
           <td width="1%">:</td>
           <!-- <td width="22%"><span style='font-size:2.5rem;'><?//= $pengujian_operasional_t ?> </span> Baik</td> -->
           <td class="slider" width="22%">
           <input type="checkbox" data-switch-url= "<?=admin_url() ?>inspections/inspection_status_change" name="pengujian_operasional_t" class="slider-checkbox" id="pengujian_operasional_t" data-id="<?= $inspection->id ?>-<?= $task->id ?>-<?= $inspection->equipment_type ?>-pengujian_operasional_t" data-pengujian ="pengujian_operasional_t"/>
           <label class="slider-label" for="pengujian_operasional_t">
              
               <span class="slider-inner"></span>
               <span class="slider-circle"></span>
           </label>
           </td>
           <td width="22%" class="slider">
             <input type="checkbox" name="pengujian_operasional_f" value="None" class="slider-checkbox" id="pengujian_operasional_f" onclick="inspection_item_pengujian_operasional(<?= $inspection->id ?>, <?= $task->id ?>, 'pengujian_operasional_f')" class="slider-checkbox" <?php if(isset($pengujian_operasional_f)) echo 'checked = checked'; ?>/>
             <label class="slider-label" for="pengujian_operasional_f">
               <span class="slider-inner"></span>
               <span class="slider-circle"></span>
             </label>
           </td>

           <td width="22%" class="slider">
             <input type="checkbox" name="pengujian_data" class="slider-checkbox" id="pengujian_operasional_n" data-switch-url= "<?=admin_url() ?>inspections/inspection_status_change" data-id="98" data-pengujian ="pengujian_operasional_n" class="onoffswitch-checkbox" <?php if(isset($pengujian_operasional_n)) echo 'checked = checked'; ?>/>
             <label class="slider-label" for="pengujian_operasional_n">
               <span class="slider-inner"></span>
               <span class="slider-circle"></span>
             </label>
           </td>

        </tr>

     </tbody>
  </table>
</div>	    