<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php //echo form_hidden('equipment_type','bucket'); ?>



      <div class="horizontal-scrollable-tabs mbot15">
         <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
         <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
         <div class="horizontal-tabs">
            <ul class="nav nav-tabs nav-tabs-horizontal" role="tablist">
               <li role="presentation" class="active">
                  <a href="#inspection_bapr" aria-controls="inspection_bapr" role="tab" data-toggle="tab"><?php echo _l('inspection_bapr'); ?></a>
               </li>
               <li role="presentation">
                  <a href="#inspection_general_data" aria-controls="inspection_general_data" role="tab" data-toggle="tab"><?php echo _l('inspection_general_data'); ?></a>
               </li>
               <li role="presentation">
                  <a href="#inspection_technical_data" aria-controls="inspection_technical_data" role="tab" data-toggle="tab"><?php echo _l('inspection_technical_data'); ?></a>
               </li>
               <li role="presentation">
                  <a href="#inspection_operation_test" aria-controls="inspection_operation_test" role="tab" data-toggle="tab"><?php echo _l('inspection_operation_test'); ?></a>
               </li>
               <li role="presentation">
                  <a href="#inspection_safety_test" aria-controls="inspection_safety_test" role="tab" data-toggle="tab"><?php echo _l('inspection_safety_test'); ?></a>
               </li>
               <li role="presentation">
                  <a href="#inspection_conclusion" aria-controls="inspection_conclusion" role="tab" data-toggle="tab"><?php echo _l('inspection_conclusion'); ?></a>
               </li>
            </ul>
         </div>
      </div>

      <div class="tab-content">
         <div role="tabpanel" class="tab-pane active" id="inspection_bapr">
            <h4 class="bold">
               <?php echo _l('inspection_bapr'); ?>
            </h4>
            <hr />
            <div class="panel komponen_pemeriksaan form-group">
               <div class="panel-body ">
                  <div class="row">
                     <div class="col-md-6">
                       <?php $value = (isset($equipment['nama_perusahaan']) ? $equipment['nama_perusahaan'] : get_inspection_company_name($equipment['rel_id'])); ?>
                       <?php echo render_input('equipment[nama_perusahaan]','nama_perusahaan',$value); ?>

                       <?php $value = (isset($equipment->alamat_perusahaan) ? $equipment->alamat_perusahaan : get_inspection_company_address($equipment['rel_id'])); ?>
                       <?php echo render_input('equipment[alamat_perusahaan]','alamat_perusahaan',$value); ?>

                       <?php $value = (isset($equipment['nomor_seri']) ? $equipment['nomor_seri'] : ''); ?>
                       <?php echo render_input('equipment[nomor_seri]','nomor_seri',$value); ?>
                    
                       <?php $value = (isset($equipment['nomor_unit']) ? $equipment['nomor_unit'] : ''); ?>
                       <?php echo render_input('equipment[nomor_unit]','nomor_unit',$value); ?>

                     </div>
                     <div class="col-md-6">
                        <?php $value = (isset($equipment['jenis_pesawat']) ? $equipment['jenis_pesawat'] : 'Listrik'); ?>
                        <?php echo render_input('equipment[jenis_pesawat]','jenis_pesawat',$value); ?>
                        <?php $value = (isset($equipment['nama_pesawat']) ? $equipment['nama_pesawat'] : ''); ?>
                        <?php echo render_input('equipment[nama_pesawat]','nama_pesawat',$value); ?>
                        <?php $value = (isset($equipment['kapasitas']) ? $equipment['kapasitas'] : ''); ?>
                        <?php echo render_input('equipment[kapasitas]','kapasitas',$value); ?>
                        <?php $value = (isset($equipment['satuan_kapasitas']) ? $equipment['satuan_kapasitas'] : ''); ?>
                        <?php echo render_input('equipment[satuan_kapasitas]','satuan_kapasitas',$value); ?>
                     </div>

                  </div>

                  <div class="container-fluid mbot15">
                     <div class="row">
                        <ul class="nav nav-tabs">
                           <li class="active"><?= _l('komponen_pemeriksan') ?></li>
                        </ul>
                        <div class="row vertical-align-middle pemeriksaan_dokumen text-center">
                           <div class="col-xs-12 col-sm-3">
                              <label class="btn-secondary"><?= _l('pemeriksaan_dokumen') ?> </label>
                              <?php $equipment_pemeriksaan_dokumen = isset($equipment_pemeriksaan_dokumen) ? $equipment_pemeriksaan_dokumen : ''; ?>
                              <?= $equipment_pemeriksaan_dokumen ?>
                           </div>
                           <div class="col-sm-1 no-padding hidden-xs text-center">
                              :
                           </div>
                           <div class="col-xs-12 col-sm-8">
                              <div class="row">
                                 <div class="col-xs-12 col-sm-4 no-padding">
                                    <input type="radio" class="btn-check" id="pemeriksaan_dokumen_t" name="equipment[pemeriksaan_dokumen]" value="1" <?php if(isset($equipment)){if($equipment['pemeriksaan_dokumen'] == 1){echo ' checked';}}; ?>>
                                    <label class="btn btn-secondary" for="pemeriksaan_dokumen_t"><i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?php echo _l('dokumen_lengkap'); ?>" ></i> <?php echo _l('lengkap'); ?></label>
                                 </div>
                                 <div class="col-xs-12 col-sm-4 no-padding">
                                    <input type="radio" class="btn-check" id="pemeriksaan_dokumen_f" name="equipment[pemeriksaan_dokumen]" value="2" <?php if(isset($equipment)){if($equipment['pemeriksaan_dokumen'] == 2){echo 'checked';}}; ?>>
                                    <label class="btn btn-secondary" for="pemeriksaan_dokumen_f"><?php echo _l('tidak_lengkap'); ?></label>
                                 </div>
                                 <div class="col-xs-12 col-sm-4 no-padding">
                                    <input type="radio" class="btn-check" id="pemeriksaan_dokumen_n" name="equipment[pemeriksaan_dokumen]" value="3" <?php if(isset($equipment)){if($equipment['pemeriksaan_dokumen'] == 3){echo 'checked';}}; ?>>
                                    <label class="btn btn-secondary" for="pemeriksaan_dokumen_n"><?php echo _l('tidak_ada'); ?></label>
                                 </div>
                              </div>
                           </div>
                        </div>

                        <div class="row vertical-align-middle pemeriksaan_visual text-center">
                           <div class="col-xs-12 col-sm-3">
                              <label class="btn-secondary"><?= _l('pemeriksaan_visual') ?> </label>
                              <?php $equipment_pemeriksaan_visual = isset($equipment_pemeriksaan_visual) ? $equipment_pemeriksaan_visual : ''; ?>
                              <?= $equipment_pemeriksaan_visual ?>
                           </div>
                           <div class="col-sm-1 no-padding hidden-xs text-center">
                              :
                           </div>
                           <div class="col-xs-12 col-sm-8">
                              <div class="row">
                                 <div class="col-xs-12 col-sm-4 no-padding">
                                    <input type="radio" class="btn-check" id="pemeriksaan_visual_t" name="equipment[pemeriksaan_visual]" value="1" <?php if(isset($equipment)){if($equipment['pemeriksaan_visual'] == 1){echo ' checked';}}; ?>>
                                    <label class="btn btn-secondary" for="pemeriksaan_visual_t"><i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?php echo _l('baik'); ?>" ></i> <?php echo _l('baik'); ?></label>
                                 </div>
                                 <div class="col-xs-12 col-sm-4 no-padding">
                                    <input type="radio" class="btn-check" id="pemeriksaan_visual_f" name="equipment[pemeriksaan_visual]" value="2" <?php if(isset($equipment)){if($equipment['pemeriksaan_visual'] == 2){echo 'checked';}}; ?>>
                                    <label class="btn btn-secondary" for="pemeriksaan_visual_f"><?php echo _l('tidak_baik'); ?></label>
                                 </div>
                                 <div class="col-xs-12 col-sm-4 no-padding">
                                    <input type="radio" class="btn-check" id="pemeriksaan_visual_n" name="equipment[pemeriksaan_visual]" value="3" <?php if(isset($equipment)){if($equipment['pemeriksaan_visual'] == 3){echo 'checked';}}; ?>>
                                    <label class="btn btn-secondary" for="pemeriksaan_visual_n"><?php echo _l('tidak_ada'); ?></label>
                                 </div>
                              </div>
                           </div>
                        </div>

                        <div class="row vertical-align-middle pemeriksaan_pengaman text-center">
                           <div class="col-xs-12 col-sm-3">
                              <label class="btn-secondary"><?= _l('pemeriksaan_pengaman') ?> </label>
                              <?php $equipment_pemeriksaan_pengaman = isset($equipment_pemeriksaan_pengaman) ? $equipment_pemeriksaan_pengaman : ''; ?>
                              <?= $equipment_pemeriksaan_pengaman ?>
                           </div>
                           <div class="col-sm-1  no-padding hidden-xs text-center">
                              :
                           </div>
                           <div class="col-xs-12 col-sm-8">
                              <div class="row">
                                 <div class="col-xs-12 col-sm-4 no-padding">
                                    <input type="radio" class="btn-check" id="pemeriksaan_pengaman_t" name="equipment[pemeriksaan_pengaman]" value="1" <?php if(isset($equipment)){if($equipment['pemeriksaan_pengaman'] == 1){echo ' checked';}}; ?>>
                                    <label class="btn btn-secondary" for="pemeriksaan_pengaman_t"><i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?php echo _l('baik'); ?>" ></i> <?php echo _l('baik'); ?></label>
                                 </div>
                                 <div class="col-xs-12 col-sm-4 no-padding">
                                    <input type="radio" class="btn-check" id="pemeriksaan_pengaman_f" name="equipment[pemeriksaan_pengaman]" value="2" <?php if(isset($equipment)){if($equipment['pemeriksaan_pengaman'] == 2){echo 'checked';}}; ?>>
                                    <label class="btn btn-secondary" for="pemeriksaan_pengaman_f"><?php echo _l('tidak_baik'); ?></label>
                                 </div>
                                 <div class="col-xs-12 col-sm-4 no-padding">
                                    <input type="radio" class="btn-check" id="pemeriksaan_pengaman_n" name="equipment[pemeriksaan_pengaman]" value="3" <?php if(isset($equipment)){if($equipment['pemeriksaan_pengaman'] == 3){echo 'checked';}}; ?>>
                                    <label class="btn btn-secondary" for="pemeriksaan_pengaman_n"><?php echo _l('tidak_ada'); ?></label>
                                 </div>
                              </div>
                           </div>
                        </div>

                        <div class="row vertical-align-middle pengujian_infrared text-center">
                           <div class="col-xs-12 col-sm-3">
                              <label class="btn-secondary"><?= _l('pengujian_infrared') ?> </label>
                              <?php $equipment_pengujian_infrared = isset($equipment_pengujian_infrared) ? $equipment_pengujian_infrared : ''; ?>
                              <?= $equipment_pengujian_infrared ?>
                           </div>
                           <div class="col-sm-1  no-padding hidden-xs text-center">
                              :
                           </div>
                           <div class="col-xs-12 col-sm-8">
                              <div class="row">
                                 <div class="col-xs-12 col-sm-4 no-padding">
                                    <input type="radio" class="btn-check" id="pengujian_infrared_t" name="equipment[pengujian_infrared]" value="1" <?php if(isset($equipment)){if($equipment['pengujian_infrared'] == 1){echo ' checked';}}; ?>>
                                    <label class="btn btn-secondary" for="pengujian_infrared_t"><i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?php echo _l('baik'); ?>" ></i> <?php echo _l('baik'); ?></label>
                                 </div>
                                 <div class="col-xs-12 col-sm-4 no-padding">
                                    <input type="radio" class="btn-check" id="pengujian_infrared_f" name="equipment[pengujian_infrared]" value="2" <?php if(isset($equipment)){if($equipment['pengujian_infrared'] == 2){echo 'checked';}}; ?>>
                                    <label class="btn btn-secondary" for="pengujian_infrared_f"><?php echo _l('tidak_baik'); ?></label>
                                 </div>
                                 <div class="col-xs-12 col-sm-4 no-padding">
                                    <input type="radio" class="btn-check" id="pengujian_infrared_n" name="equipment[pengujian_infrared]" value="3" <?php if(isset($equipment)){if($equipment['pengujian_infrared'] == 3){echo 'checked';}}; ?>>
                                    <label class="btn btn-secondary" for="pengujian_infrared_n"><?php echo _l('tidak_ada'); ?></label>
                                 </div>
                              </div>
                           </div>
                        </div>

                        <div class="row vertical-align-middle pengujian_operasional text-center">
                           <div class="col-xs-12 col-sm-3">
                              <label class="btn-secondary"><?= _l('pengujian_operasional') ?> </label>
                              <?php $equipment_pengujian_operasional = isset($equipment_pengujian_operasional) ? $equipment_pengujian_operasional : ''; ?>
                              <?= $equipment_pengujian_operasional ?>
                           </div>
                           <div class="col-sm-1  no-padding hidden-xs text-center">
                              :
                           </div>
                           <div class="col-xs-12 col-sm-8">
                              <div class="row">
                                 <div class="col-xs-12 col-sm-4 no-padding">
                                    <input type="radio" class="btn-check" id="pengujian_operasional_t" name="equipment[pengujian_operasional]" value="1" <?php if(isset($equipment)){if($equipment['pengujian_operasional'] == 1){echo ' checked';}}; ?>>
                                    <label class="btn btn-secondary" for="pengujian_operasional_t"><i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?php echo _l('baik'); ?>" ></i> <?php echo _l('baik'); ?></label>
                                 </div>
                                 <div class="col-xs-12 col-sm-4 no-padding">
                                    <input type="radio" class="btn-check" id="pengujian_operasional_f" name="equipment[pengujian_operasional]" value="2" <?php if(isset($equipment)){if($equipment['pengujian_operasional'] == 2){echo 'checked';}}; ?>>
                                    <label class="btn btn-secondary" for="pengujian_operasional_f"><?php echo _l('tidak_baik'); ?></label>
                                 </div>
                                 <div class="col-xs-12 col-sm-4 no-padding">
                                    <input type="radio" class="btn-check" id="pengujian_operasional_n" name="equipment[pengujian_operasional]" value="3" <?php if(isset($equipment)){if($equipment['pengujian_operasional'] == 3){echo 'checked';}}; ?>>
                                    <label class="btn btn-secondary" for="pengujian_operasional_n"><?php echo _l('tidak_ada'); ?></label>
                                 </div>
                              </div>
                           </div>
                        </div>
                     </div>
                  </div>
                  <?php $value = (!empty($equipment['regulasi']) ? $equipment['regulasi'] : get_option('predefined_regulation_of_iil')); ?>
                  <?php echo render_textarea('equipment[regulasi]','equipment_regulasi',$value); ?>
                  <?php $value = (!empty($equipment['temuan']) ? $equipment['temuan'] : ''); ?>
                  <?php echo render_textarea('equipment[temuan]','equipment_temuan',$value); ?>
                  <?php $value = (!empty($equipment['kesimpulan']) ? $equipment['kesimpulan'] : ''); ?>
                  <?php echo render_textarea('equipment[kesimpulan]','equipment_kesimpulan',$value); ?>
               </div>
            </div>

<!--
<div class="btn-group">
  <input type="radio" class="btn-check" name="options" id="option1" autocomplete="off" checked />
  <label class="btn btn-secondary" for="option1">Checked</label>

  <input type="radio" class="btn-check" name="options" id="option2" autocomplete="off" />
  <label class="btn btn-secondary" for="option2">Radio</label>

  <input type="radio" class="btn-check" name="options" id="option3" autocomplete="off" />
  <label class="btn btn-secondary" for="option3">Radio</label>
</div>
-->

         </div> <!-- end tabpanel-->
         <div role="tabpanel" class="tab-pane" id="inspection_general_data">

            <h4 class="bold">
               <?php echo _l('inspection_general_data'); ?>
            </h4>
            <p class="text-muted">
               <p><?php echo _l('inspection_general_data'); ?></p>
            </p>
            <hr />
            <div class="row">

            </div>
        </div>
         <div role="tabpanel" class="tab-pane" id="inspection_technical_data">

            <h4 class="bold">
               <?php echo _l('inspection_technical_data'); ?>
            </h4>
            <p class="text-muted">
               <p><?php echo _l('inspection_technical_data'); ?></p>
            </p>
            <hr />
            <div class="row">

            </div>
        </div>
         <div role="tabpanel" class="tab-pane" id="inspection_operation_test">
            <h4 class="bold">
               <?php echo _l('inspection_operation_test'); ?>
            </h4>
            <p class="text-muted">
               <p><?php echo _l('inspection_operation_test'); ?></p>
            </p>
            <hr />
            <div class="row">

            </div>
         </div>
         <div role="tabpanel" class="tab-pane" id="inspection_safety_test">
            <h4 class="bold">
               <?php echo _l('inspection_safety_test'); ?>
            </h4>
            <p class="text-muted">
               <p><?php echo _l('inspection_safety_test'); ?></p>
            </p>
            <hr />
            <div class="row">

            </div>
         </div>
         <div role="tabpanel" class="tab-pane" id="inspection_conclusion">
            <h4 class="bold">
               <?php echo _l('inspection_conclusion'); ?>
            </h4>
            <p class="text-muted">
               <p><?php echo _l('inspection_conclusion'); ?></p>
            </p>
            <hr />
            <div class="row">

            </div>
        
         </div>

      </div>
