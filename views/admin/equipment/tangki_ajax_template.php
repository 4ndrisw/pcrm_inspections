<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

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
                  <a href="#inspection_penetrant_test" aria-controls="inspection_penetrant_test" role="tab" data-toggle="tab"><?php echo _l('inspection_penetrant_test'); ?></a>
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
               <?php echo _l('inspection_bapr') .' - '. $task->name; ?>
            </h4>
            <hr />
            <div class="komponen_pemeriksaan">
               <div class="panel_s">
                  <div class="panel-body">

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
                                <td class="editable" data-field="nama_pesawat" data-jenis_pesawat="<?= $inspection->equipment_type ?>" data-inspection_id="<?= $inspection->id ?>" data-task_id="<?= $task->id ?>"><?= isset($equipment['nama_pesawat']) ? $equipment['nama_pesawat'] : '' ?></td>      
                             </tr>
                             <tr>
                                <td style="width:20%">Nomor Seri</td>
                                <td style="width:2%">:</td>
                                <td class="editable" data-field="nomor_seri" data-jenis_pesawat="<?= $inspection->equipment_type ?>" data-inspection_id="<?= $inspection->id ?>" data-task_id="<?= $task->id ?>"><?= isset($equipment['nomor_seri']) ? $equipment['nomor_seri'] : '' ?></td>      
                             </tr>
                             <tr>
                                <td style="width:20%">Nomor Unit</td>
                                <td style="width:2%">:</td>
                                <td class="editable" data-field="nomor_unit" data-jenis_pesawat="<?= $inspection->equipment_type ?>" data-inspection_id="<?= $inspection->id ?>" data-task_id="<?= $task->id ?>"><?= isset($equipment['nomor_unit']) ? $equipment['nomor_unit'] : '' ?></td>
                             </tr>
                             <tr>
                                <td style="width:20%">Type / Model</td>
                                <td style="width:2%">:</td>
                                <td class="editable" data-field="type_model" data-jenis_pesawat="<?= $inspection->equipment_type ?>" data-inspection_id="<?= $inspection->id ?>" data-task_id="<?= $task->id ?>"><?= isset($equipment['type_model']) ? $equipment['type_model'] : '' ?></td>
                             </tr>
                             <tr>
                                <td style="width:20%">Kapasitas</td>
                                <td style="width:2%">:</td>
                                <td class="editable" data-field="kapasitas" data-jenis_pesawat="<?= $inspection->equipment_type ?>" data-inspection_id="<?= $inspection->id ?>" data-task_id="<?= $task->id ?>"><?= isset($equipment['kapasitas']) ? $equipment['kapasitas'] : '' ?></td>
                             </tr>
                             <tr>
                                <td style="width:20%">Satuan</td>
                                <td style="width:2%">:</td>
                                <td class="editable" data-field="satuan_kapasitas" data-jenis_pesawat="<?= $inspection->equipment_type ?>" data-inspection_id="<?= $inspection->id ?>" data-task_id="<?= $task->id ?>"><?= isset($equipment['satuan_kapasitas']) ? $equipment['satuan_kapasitas'] : '' ?></td>
                             </tr>
                          
                          </tbody>
                       </table>
                     </div>

                     <div class="col-md-12">
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
                                       <input type="radio" class="btn-check" id="pemeriksaan_dokumen_t" onchange="inspection_item_pengujian_data(this, '<?= $inspection->equipment_type ?>', 'pemeriksaan_dokumen', <?= $inspection->id ?>, <?= $task->id ?>)" name="equipment[pemeriksaan_dokumen]" value="1" <?php if(isset($equipment['pemeriksaan_dokumen']) && $equipment['pemeriksaan_dokumen'] == 1){echo 'checked';}; ?>>
                                       <label class="btn btn-secondary" for="pemeriksaan_dokumen_t"><i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?php echo _l('baik'); ?>" ></i> <?php echo _l('baik'); ?></label>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 no-padding">
                                       <input type="radio" class="btn-check" id="pemeriksaan_dokumen_f" onchange="inspection_item_pengujian_data(this, '<?= $inspection->equipment_type ?>', 'pemeriksaan_dokumen', <?= $inspection->id ?>, <?= $task->id ?>)" name="equipment[pemeriksaan_dokumen]" value="2" <?php if(isset($equipment['pemeriksaan_dokumen']) && $equipment['pemeriksaan_dokumen'] == 2){echo 'checked';}; ?>>
                                       <label class="btn btn-secondary" for="pemeriksaan_dokumen_f"><?php echo _l('tidak_baik'); ?></label>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 no-padding">
                                       <input type="radio" class="btn-check" id="pemeriksaan_dokumen_n" onchange="inspection_item_pengujian_data(this, '<?= $inspection->equipment_type ?>', 'pemeriksaan_dokumen', <?= $inspection->id ?>, <?= $task->id ?>)" name="equipment[pemeriksaan_dokumen]" value="0" <?php if(isset($equipment['pemeriksaan_dokumen']) && $equipment['pemeriksaan_dokumen'] == 0){echo 'checked';}; ?>>
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
                                       <input type="radio" class="btn-check" id="pemeriksaan_visual_t" onchange="inspection_item_pengujian_data(this, '<?= $inspection->equipment_type ?>', 'pemeriksaan_visual', <?= $inspection->id ?>, <?= $task->id ?>)" name="equipment[pemeriksaan_visual]" value="1" <?php if(isset($equipment['pemeriksaan_visual']) && $equipment['pemeriksaan_visual'] == 1){echo 'checked';}; ?>>
                                       <label class="btn btn-secondary" for="pemeriksaan_visual_t"><i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?php echo _l('baik'); ?>" ></i> <?php echo _l('baik'); ?></label>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 no-padding">
                                       <input type="radio" class="btn-check" id="pemeriksaan_visual_f" onchange="inspection_item_pengujian_data(this, '<?= $inspection->equipment_type ?>', 'pemeriksaan_visual', <?= $inspection->id ?>, <?= $task->id ?>)" name="equipment[pemeriksaan_visual]" value="2" <?php if(isset($equipment['pemeriksaan_visual']) && $equipment['pemeriksaan_visual'] == 2){echo 'checked';}; ?>>
                                       <label class="btn btn-secondary" for="pemeriksaan_visual_f"><?php echo _l('tidak_baik'); ?></label>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 no-padding">
                                       <input type="radio" class="btn-check" id="pemeriksaan_visual_n" onchange="inspection_item_pengujian_data(this, '<?= $inspection->equipment_type ?>', 'pemeriksaan_visual', <?= $inspection->id ?>, <?= $task->id ?>)" name="equipment[pemeriksaan_visual]" value="0" <?php if(isset($equipment['pemeriksaan_visual']) && $equipment['pemeriksaan_visual'] == 0){echo 'checked';}; ?>>
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
                                       <input type="radio" class="btn-check" id="pemeriksaan_pengaman_t" onchange="inspection_item_pengujian_data(this, '<?= $inspection->equipment_type ?>', 'pemeriksaan_pengaman', <?= $inspection->id ?>, <?= $task->id ?>)" name="equipment[pemeriksaan_pengaman]" value="1" <?php if(isset($equipment['pemeriksaan_pengaman']) && $equipment['pemeriksaan_pengaman'] == 1){echo 'checked';}; ?>>
                                       <label class="btn btn-secondary" for="pemeriksaan_pengaman_t"><i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?php echo _l('baik'); ?>" ></i> <?php echo _l('baik'); ?></label>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 no-padding">
                                       <input type="radio" class="btn-check" id="pemeriksaan_pengaman_f" onchange="inspection_item_pengujian_data(this, '<?= $inspection->equipment_type ?>', 'pemeriksaan_pengaman', <?= $inspection->id ?>, <?= $task->id ?>)" name="equipment[pemeriksaan_pengaman]" value="2" <?php if(isset($equipment['pemeriksaan_pengaman']) && $equipment['pemeriksaan_pengaman'] == 2){echo 'checked';}; ?>>
                                       <label class="btn btn-secondary" for="pemeriksaan_pengaman_f"><?php echo _l('tidak_baik'); ?></label>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 no-padding">
                                       <input type="radio" class="btn-check" id="pemeriksaan_pengaman_n" onchange="inspection_item_pengujian_data(this, '<?= $inspection->equipment_type ?>', 'pemeriksaan_pengaman', <?= $inspection->id ?>, <?= $task->id ?>)" name="equipment[pemeriksaan_pengaman]" value="0" <?php if(isset($equipment['pemeriksaan_pengaman']) && $equipment['pemeriksaan_pengaman'] == 0){echo 'checked';}; ?>>
                                       <label class="btn btn-secondary" for="pemeriksaan_pengaman_n"><?php echo _l('tidak_ada'); ?></label>
                                    </div>
                                 </div>
                              </div>
                           </div>

                           <div class="row vertical-align-middle pengujian_penetrant text-center">
                              <div class="col-xs-12 col-sm-3">
                                 <label class="btn-secondary"><?= _l('pengujian_penetrant') ?> </label>
                                 <?php $equipment_pengujian_penetrant = isset($equipment_pengujian_penetrant) ? $equipment_pengujian_penetrant : ''; ?>
                                 <?= $equipment_pengujian_penetrant ?>
                              </div>
                              <div class="col-sm-1  no-padding hidden-xs text-center">
                                 :
                              </div>
                              <div class="col-xs-12 col-sm-8">
                                 <div class="row">
                                    <div class="col-xs-12 col-sm-4 no-padding">
                                       <input type="radio" class="btn-check" id="pengujian_penetrant_t" onchange="inspection_item_pengujian_data(this, '<?= $inspection->equipment_type ?>', 'pengujian_penetrant', <?= $inspection->id ?>, <?= $task->id ?>)" name="equipment[pengujian_penetrant]" value="1" <?php if(isset($equipment['pengujian_penetrant']) && $equipment['pengujian_penetrant'] == 1){echo 'checked';}; ?>>
                                       <label class="btn btn-secondary" for="pengujian_penetrant_t"><i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?php echo _l('baik'); ?>" ></i> <?php echo _l('baik'); ?></label>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 no-padding">
                                       <input type="radio" class="btn-check" id="pengujian_penetrant_f" onchange="inspection_item_pengujian_data(this, '<?= $inspection->equipment_type ?>', 'pengujian_penetrant', <?= $inspection->id ?>, <?= $task->id ?>)" name="equipment[pengujian_penetrant]" value="2" <?php if(isset($equipment['pengujian_penetrant']) && $equipment['pengujian_penetrant'] == 2){echo 'checked';}; ?>>
                                       <label class="btn btn-secondary" for="pengujian_penetrant_f"><?php echo _l('tidak_baik'); ?></label>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 no-padding">
                                       <input type="radio" class="btn-check" id="pengujian_penetrant_n" onchange="inspection_item_pengujian_data(this, '<?= $inspection->equipment_type ?>', 'pengujian_penetrant', <?= $inspection->id ?>, <?= $task->id ?>)" name="equipment[pengujian_penetrant]" value="0" <?php if(isset($equipment['pengujian_penetrant']) && $equipment['pengujian_penetrant'] == 0){echo 'checked';}; ?>>
                                       <label class="btn btn-secondary" for="pengujian_penetrant_n"><?php echo _l('tidak_ada'); ?></label>
                                    </div>
                                 </div>
                              </div>
                           </div>

                           <div class="row vertical-align-middle pengujian_thickness text-center">
                              <div class="col-xs-12 col-sm-3">
                                 <label class="btn-secondary"><?= _l('pengujian_thickness') ?> </label>
                                 <?php $equipment_pengujian_thickness = isset($equipment_pengujian_thickness) ? $equipment_pengujian_thickness : ''; ?>
                                 <?= $equipment_pengujian_thickness ?>
                              </div>
                              <div class="col-sm-1  no-padding hidden-xs text-center">
                                 :
                              </div>
                              <div class="col-xs-12 col-sm-8">
                                 <div class="row">
                                    <div class="col-xs-12 col-sm-4 no-padding">
                                       <input type="radio" class="btn-check" id="pengujian_thickness_t"   name="equipment[pengujian_thickness]" value="1" <?php if(isset($equipment['pengujian_thickness']) && $equipment['pengujian_thickness'] == 1){echo ' checked';}; ?>>
                                       <label class="btn btn-secondary" for="pengujian_thickness_t"><i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?php echo _l('baik'); ?>" ></i> <?php echo _l('baik'); ?></label>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 no-padding">
                                       <input type="radio" class="btn-check" id="pengujian_thickness_f" name="equipment[pengujian_thickness]" value="2" <?php if(isset($equipment['pengujian_thickness']) && $equipment['pengujian_thickness'] == 2){echo 'checked';}; ?>>
                                       <label class="btn btn-secondary" for="pengujian_thickness_f"><?php echo _l('tidak_baik'); ?></label>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 no-padding">
                                       <input type="radio" class="btn-check" id="pengujian_thickness_n" name="equipment[pengujian_thickness]" value="3" <?php if(isset($equipment['pengujian_thickness']) && $equipment['pengujian_thickness'] == 3){echo 'checked';}; ?>>
                                       <label class="btn btn-secondary" for="pengujian_thickness_n"><?php echo _l('tidak_ada'); ?></label>
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
                                       <input type="radio" class="btn-check" id="pengujian_operasional_t" onchange="inspection_item_pengujian_data(this, '<?= $inspection->equipment_type ?>', 'pengujian_operasional', <?= $inspection->id ?>, <?= $task->id ?>)" name="equipment[pengujian_operasional]" value="1" <?php if(isset($equipment['pengujian_operasional']) && $equipment['pengujian_operasional'] == 1){echo 'checked';}; ?>>
                                       <label class="btn btn-secondary" for="pengujian_operasional_t"><i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?php echo _l('baik'); ?>" ></i> <?php echo _l('baik'); ?></label>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 no-padding">
                                       <input type="radio" class="btn-check" id="pengujian_operasional_f" onchange="inspection_item_pengujian_data(this, '<?= $inspection->equipment_type ?>', 'pengujian_operasional', <?= $inspection->id ?>, <?= $task->id ?>)" name="equipment[pengujian_operasional]" value="2" <?php if(isset($equipment['pengujian_operasional']) && $equipment['pengujian_operasional'] == 2){echo 'checked';}; ?>>
                                       <label class="btn btn-secondary" for="pengujian_operasional_f"><?php echo _l('tidak_baik'); ?></label>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 no-padding">
                                       <input type="radio" class="btn-check" id="pengujian_operasional_n" onchange="inspection_item_pengujian_data(this, '<?= $inspection->equipment_type ?>', 'pengujian_operasional', <?= $inspection->id ?>, <?= $task->id ?>)" name="equipment[pengujian_operasional]" value="0" <?php if(isset($equipment['pengujian_operasional']) && $equipment['pengujian_operasional'] == 0){echo 'checked';}; ?>>
                                       <label class="btn btn-secondary" for="pengujian_operasional_n"><?php echo _l('tidak_ada'); ?></label>
                                    </div>
                                 </div>
                              </div>
                           </div>
                        </div>

                     <div class="col-md-12">
                        <?php $value = (!empty($equipment['regulasi']) ? $equipment['regulasi'] : get_option('predefined_regulation_of_paa')); ?>
                        <?php echo render_textarea('equipment[regulasi]','equipment_regulasi',$value); ?>
                        <?php $value = (!empty($equipment['temuan']) ? $equipment['temuan'] : ''); ?>
                        <?php echo render_textarea('equipment[temuan]','equipment_temuan',$value); ?>
                        <?php $value = (!empty($equipment['kesimpulan']) ? $equipment['kesimpulan'] : ''); ?>
                        <?php echo render_textarea('equipment[kesimpulan]','equipment_kesimpulan',$value); ?>
                     </div>




                  </div>

               </div>
            </div>
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
         <div role="tabpanel" class="tab-pane" id="inspection_penetrant_test">
            <h4 class="bold">
               <?php echo _l('inspection_penetrant_test'); ?>
            </h4>
            <p class="text-muted">
               <p><?php echo _l('inspection_penetrant_test'); ?></p>
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
