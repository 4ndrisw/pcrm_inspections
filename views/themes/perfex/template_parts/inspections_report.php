<?php defined('BASEPATH') or exit('No direct script access allowed');?>

<?php $equipment = $this_equipment[0];?>

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
        <?php ?>
        <?php if(isset($equipment['nama_pesawat'])) { ?>
           <tr>
              <td style="width:20%">Nama Pesawat</td>
              <td style="width:2%">:</td>
              <td><?= $equipment['nama_pesawat'] ?></td>
           </tr>
        <?php } ?>
        <?php if(isset($equipment['pabrik_pembuat'])) { ?>
           <tr>
              <td style="width:20%">Pabrik Pembuat</td>
              <td style="width:2%">:</td>
              <td><?= $equipment['pabrik_pembuat'] ?></td>
           </tr>
        <?php } ?>
        <?php if(isset($equipment['tempat_pembuatan'])) { ?>
           <tr>
              <td style="width:20%">Tempat Pembuatan</td>
              <td style="width:2%">:</td>
              <td><?= $equipment['tempat_pembuatan'] ?></td>
           </tr>
        <?php } ?>
        <?php if(isset($equipment['tahun_pembuatan'])) { ?>
           <tr>
              <td style="width:20%">Tahun Pembuatan</td>
              <td style="width:2%">:</td>
              <td><?= $equipment['tahun_pembuatan'] ?></td>
           </tr>
        <?php } ?>

        <?php if(isset($equipment['merk'])) { ?>
           <tr>
              <td style="width:20%">Merk</td>
              <td style="width:2%">:</td>
              <td><?= $equipment['merk'] ?></td>
           </tr>
        <?php } ?>

        <?php if(isset($equipment['nomor_seri'])) { ?>
           <tr>
              <td style="width:20%">Nomor Seri</td>
              <td style="width:2%">:</td>
              <td class="editable" data-field="nomor_seri" data-jenis_pesawat="<?= $inspection->equipment_type ?>" data-inspection_id="<?= $inspection->id ?>" data-task_id="<?= $task->id ?>"><?= $equipment['nomor_seri'] ?></td>      
           </tr>
        <?php } ?>

        <?php if(isset($equipment['nomor_unit'])) { ?>
           <tr>
              <td style="width:20%">Nomor Unit</td>
              <td style="width:2%">:</td>
              <td><?= $equipment['nomor_unit'] ?></td>
           </tr>
        <?php } ?>
        <?php if(isset($equipment['type_model'])) { ?>
           <tr>
              <td style="width:20%">Type / Model</td>
              <td style="width:2%">:</td>
              <td><?= $equipment['type_model'] ?></td>
           </tr>
        <?php } ?>
        <?php if(isset($equipment['kapasitas'])) { ?>
           <tr>
              <td style="width:20%">Kapasitas</td>
              <td style="width:2%">:</td>
              <td><?= $equipment['kapasitas'] ?></td>
           </tr>
        <?php } ?>
        <?php if(isset($equipment['daya'])) { ?>
           <tr>
              <td style="width:20%">Daya</td>
              <td style="width:2%">:</td>
              <td><?= $equipment['daya'] ?></td>
           </tr>
        <?php } ?>
        <?php if(isset($equipment['tekanan_design'])) { ?>
           <tr>
              <td style="width:20%">Tekanan Design</td>
              <td style="width:2%">:</td>
              <td><?= $equipment['tekanan_design'] ?></td>
           </tr>
        <?php } ?>
        <?php if(isset($equipment['tekanan_kerja'])) { ?>
           <tr>
              <td style="width:20%">Tekanan Kerja</td>
              <td style="width:2%">:</td>
              <td><?= $equipment['tekanan_kerja'] ?></td>
           </tr>
        <?php } ?>
        <?php if(isset($equipment['tekanan_uji'])) { ?>
           <tr>
              <td style="width:20%">Tekanan Uji</td>
              <td style="width:2%">:</td>
              <td><?= $equipment['tekanan_uji'] ?></td>
           </tr>
        <?php } ?>
        <?php if(isset($equipment['bentuk'])) { ?>
           <tr>
              <td style="width:20%">Bentuk</td>
              <td style="width:2%">:</td>
              <td><?= $equipment['bentuk'] ?></td>
           </tr>
        <?php } ?>
        <?php if(isset($equipment['jenis_pemeriksaan'])) { ?>
           <tr>
              <td style="width:20%">Jenis Pemeriksaan</td>
              <td style="width:2%">:</td>
              <td><?= $equipment['jenis_pemeriksaan'] ?></td>
           </tr>
        <?php } ?>

        <?php if(isset($equipment['digunakan_untuk'])) { ?>
           <tr>
              <td style="width:20%">Digunakan Untuk</td>
              <td style="width:2%">:</td>
              <td><?= $equipment['digunakan_untuk'] ?></td>
           </tr>
        <?php } ?>

     
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