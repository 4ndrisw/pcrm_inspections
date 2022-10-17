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
               <li role="presentation">
                  <a href="#inspection_documentation" aria-controls="inspection_documentation" role="tab" data-toggle="tab"><?php echo _l('inspection_documentation'); ?></a>
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
                                <td style="width:20%">Lokasi</td>
                                <td style="width:2%">:</td>
                                <td class="<?= $editable_class ?>" data-field="lokasi" data-jenis_pesawat="<?= $inspection->equipment_type ?>" data-inspection_id="<?= $inspection->id ?>" data-task_id="<?= $task->id ?>"><?= isset($equipment['lokasi']) ? $equipment['lokasi'] : '' ?></td>      
                             </tr>
                             <tr>
                                <td style="width:20%">Nama Pesawat</td>
                                <td style="width:2%">:</td>
                                <td class="<?= $editable_class ?>" data-field="nama_pesawat" data-jenis_pesawat="<?= $inspection->equipment_type ?>" data-inspection_id="<?= $inspection->id ?>" data-task_id="<?= $task->id ?>"><?= isset($equipment['nama_pesawat']) ? $equipment['nama_pesawat'] : '' ?></td>      
                             </tr>
                             <tr>
                                <td style="width:20%">Pabrik Pembuat</td>
                                <td style="width:2%">:</td>
                                <td class="<?= $editable_class ?>" data-field="pabrik_pembuat" data-jenis_pesawat="<?= $inspection->equipment_type ?>" data-inspection_id="<?= $inspection->id ?>" data-task_id="<?= $task->id ?>"><?= isset($equipment['pabrik_pembuat']) ? $equipment['pabrik_pembuat'] : '' ?></td>      
                             </tr>
                             <tr>
                                <td style="width:20%">Tempat Pembuatan</td>
                                <td style="width:2%">:</td>
                                <td class="<?= $editable_class ?>" data-field="tempat_pembuatan" data-jenis_pesawat="<?= $inspection->equipment_type ?>" data-inspection_id="<?= $inspection->id ?>" data-task_id="<?= $task->id ?>"><?= isset($equipment['tempat_pembuatan']) ? $equipment['tempat_pembuatan'] : '' ?></td>      
                             </tr>
                             <tr>
                                <td style="width:20%">Tahun Pembuatan</td>
                                <td style="width:2%">:</td>
                                <td id="tahun_pembuatan" class="<?= $editable_class ?>" data-field="tahun_pembuatan" data-jenis_pesawat="<?= $inspection->equipment_type ?>" data-inspection_id="<?= $inspection->id ?>" data-task_id="<?= $task->id ?>"><?= isset($equipment['tahun_pembuatan']) ? $equipment['tahun_pembuatan'] : '' ?></td>      
                             </tr>
                             <tr>
                                <td style="width:20%">Merk</td>
                                <td style="width:2%">:</td>
                                <td class="<?= $editable_class ?>" data-field="merk" data-jenis_pesawat="<?= $inspection->equipment_type ?>" data-inspection_id="<?= $inspection->id ?>" data-task_id="<?= $task->id ?>"><?= isset($equipment['merk']) ? $equipment['merk'] : '' ?></td>      
                             </tr>
                             <tr>
                                <td style="width:20%">Nomor Seri</td>
                                <td style="width:2%">:</td>
                                <td class="<?= $editable_class ?>" data-field="nomor_seri" data-jenis_pesawat="<?= $inspection->equipment_type ?>" data-inspection_id="<?= $inspection->id ?>" data-task_id="<?= $task->id ?>"><?= isset($equipment['nomor_seri']) ? $equipment['nomor_seri'] : '' ?></td>      
                             </tr>
                             <tr>
                                <td style="width:20%">Nomor Unit</td>
                                <td style="width:2%">:</td>
                                <td class="<?= $editable_class ?>" data-field="nomor_unit" data-jenis_pesawat="<?= $inspection->equipment_type ?>" data-inspection_id="<?= $inspection->id ?>" data-task_id="<?= $task->id ?>"><?= isset($equipment['nomor_unit']) ? $equipment['nomor_unit'] : '' ?></td>
                             </tr>
                             <tr>
                                <td style="width:20%">Type / Model</td>
                                <td style="width:2%">:</td>
                                <td class="<?= $editable_class ?>" data-field="type_model" data-jenis_pesawat="<?= $inspection->equipment_type ?>" data-inspection_id="<?= $inspection->id ?>" data-task_id="<?= $task->id ?>"><?= isset($equipment['type_model']) ? $equipment['type_model'] : '' ?></td>
                             </tr>
                             <tr>
                                <td style="width:20%">Kapasitas</td>
                                <td style="width:2%">:</td>
                                <td class="<?= $editable_class ?>" data-field="kapasitas" data-jenis_pesawat="<?= $inspection->equipment_type ?>" data-inspection_id="<?= $inspection->id ?>" data-task_id="<?= $task->id ?>"><?= isset($equipment['kapasitas']) ? $equipment['kapasitas'] : '' ?></td>
                             </tr>
                             <tr>
                                <td style="width:20%">Digunakan untuk</td>
                                <td style="width:2%">:</td>
                                <td class="<?= $editable_class ?>" data-field="digunakan_untuk" data-jenis_pesawat="<?= $inspection->equipment_type ?>" data-inspection_id="<?= $inspection->id ?>" data-task_id="<?= $task->id ?>"><?= isset($equipment['digunakan_untuk']) ? $equipment['digunakan_untuk'] : '' ?></td>
                             </tr>
                             <tr>
                                <td style="width:20%">Jenis pemeriksaan</td>
                                <td style="width:2%">:</td>
                                <td>
                                    <div class="col-xs-12 col-sm-4 mbot5">
                                       <input type="radio" class="btn-check" id="jenis_pemeriksaan_t" onchange="inspection_item_pengujian_data(this, '<?= $inspection->equipment_type ?>', 'jenis_pemeriksaan', <?= $inspection->id ?>, <?= $task->id ?>)" name="equipment[jenis_pemeriksaan]" value="Pertama" <?php if(isset($equipment['jenis_pemeriksaan']) && $equipment['jenis_pemeriksaan'] == 'Pertama'){echo 'checked';}; ?>>
                                       <label class="btn btn-secondary" for="jenis_pemeriksaan_t"><i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?php echo _l('jenis_pemeriksaan_pertama'); ?>" ></i><?php echo _l('Pertama'); ?></label>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 mbot5">
                                       <input type="radio" class="btn-check" id="jenis_pemeriksaan_f" onchange="inspection_item_pengujian_data(this, '<?= $inspection->equipment_type ?>', 'jenis_pemeriksaan', <?= $inspection->id ?>, <?= $task->id ?>)" name="equipment[jenis_pemeriksaan]" value="Berkala" <?php if(isset($equipment['jenis_pemeriksaan']) && $equipment['jenis_pemeriksaan'] == 'Berkala'){echo 'checked';}; ?>>
                                       <label class="btn btn-secondary" for="jenis_pemeriksaan_f"><i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?php echo _l('jenis_pemeriksaan_berkala'); ?>" ></i><?php echo _l('Berkala'); ?></label>
                                    </div>
                                </td>
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

                           <div class="row vertical-align-middle pengujian_beban text-center">
                              <div class="col-xs-12 col-sm-3">
                                 <label class="btn-secondary"><?= _l('pengujian_beban') ?> </label>
                                 <?php $equipment_pengujian_beban = isset($equipment_pengujian_beban) ? $equipment_pengujian_beban : ''; ?>
                                 <?= $equipment_pengujian_beban ?>
                              </div>
                              <div class="col-sm-1  no-padding hidden-xs text-center">
                                 :
                              </div>
                              <div class="col-xs-12 col-sm-8">
                                 <div class="row">
                                    <div class="col-xs-12 col-sm-4 no-padding">
                                       <input type="radio" class="btn-check" id="pengujian_beban_t" onchange="inspection_item_pengujian_data(this, '<?= $inspection->equipment_type ?>', 'pengujian_beban', <?= $inspection->id ?>, <?= $task->id ?>)" name="equipment[pengujian_beban]" value="1" <?php if(isset($equipment['pengujian_beban']) && $equipment['pengujian_beban'] == 1){echo 'checked';}; ?>>
                                       <label class="btn btn-secondary" for="pengujian_beban_t"><i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?php echo _l('baik'); ?>" ></i> <?php echo _l('baik'); ?></label>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 no-padding">
                                       <input type="radio" class="btn-check" id="pengujian_beban_f" onchange="inspection_item_pengujian_data(this, '<?= $inspection->equipment_type ?>', 'pengujian_beban', <?= $inspection->id ?>, <?= $task->id ?>)" name="equipment[pengujian_beban]" value="2" <?php if(isset($equipment['pengujian_beban']) && $equipment['pengujian_beban'] == 2){echo 'checked';}; ?>>
                                       <label class="btn btn-secondary" for="pengujian_beban_f"><?php echo _l('tidak_baik'); ?></label>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 no-padding">
                                       <input type="radio" class="btn-check" id="pengujian_beban_n" onchange="inspection_item_pengujian_data(this, '<?= $inspection->equipment_type ?>', 'pengujian_beban', <?= $inspection->id ?>, <?= $task->id ?>)" name="equipment[pengujian_beban]" value="0" <?php if(isset($equipment['pengujian_beban']) && $equipment['pengujian_beban'] == 0){echo 'checked';}; ?>>
                                       <label class="btn btn-secondary" for="pengujian_beban_n"><?php echo _l('tidak_ada'); ?></label>
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

                     <div class="col-md-12 table-responsive">
                       <table id="<?= 'resume-inspection-'.$inspection->id ?>" class="table inspection table-bordered">
                           <tr class="data-label">
                              <td>Regulasi</td>
                           </tr>
                           <tr>
                              <td class="<?= $editableText_class ?> pbot15" data-field="regulasi" data-jenis_pesawat="<?= $inspection->equipment_type ?>" data-inspection_id="<?= $inspection->id ?>" data-task_id="<?= $task->id ?>"><?= isset($equipment['regulasi']) ? $equipment['regulasi'] : get_option('predefined_regulation_of_paa') ?></td>
                           </tr>
                           <tr class="data-label">
                              <td>Temuan</td>
                           </tr>
                           <tr>
                              <td class="<?= $editableText_class ?> pbot15" data-field="temuan" data-jenis_pesawat="<?= $inspection->equipment_type ?>" data-inspection_id="<?= $inspection->id ?>" data-task_id="<?= $task->id ?>"><?= isset($equipment['temuan']) ? $equipment['temuan'] : '' ?></td>                              
                           </tr>
                           <tr class="data-label">
                              <td>Kesimpulan</td>
                           </tr>
                           <tr>
                              <td class="<?= $editableText_class ?> pbot15" data-field="kesimpulan" data-jenis_pesawat="<?= $inspection->equipment_type ?>" data-inspection_id="<?= $inspection->id ?>" data-task_id="<?= $task->id ?>"><?= isset($equipment['kesimpulan']) ? $equipment['kesimpulan'] : '' ?></td>                              
                           </tr>
                        </table>
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
         <div data-role="page" role="tabpanel" class="tab-pane" id="inspection_documentation">
            <h4 class="bold">
               <?php echo _l('inspection_documentation'); ?>
            </h4>
            <p class="text-muted">
               <p><?php echo _l('inspection_documentation'); ?></p>
            </p>
            <hr />
            <div class="row">
               <div class="col-md-12 documentation-form">
                  <?php echo form_open_multipart('inspections/inspection_item/'. $inspection->id.'/'. $inspection->task_id.'/upload_file',array('id'=>'inspection-documentation','class'=>'dropzone')); ?>
                  <?php echo form_close(); ?>
               </div>

               <div class="col-md-12 clearfix">
                  <a class="btn btn-success reload-div" onclick="reloadInspectionsAttachments();">Reload</a>
               </div>

               <div id="inspection-documentations">
                  <?php if(count($inspection->documentations) > 0){ ?>
                  <div class="col-md-12">
                     <div class="inspections_attachments_wrapper">
                        <div class="col-md-12" id="attachments">
                           <hr />
                           <h4 class="th font-medium mbot15"><?php echo _l('task_view_attachments'); ?></h4>
                           <div class="row">
                              <?php
                                 $i = 1;
                                 // Store all url related data here
                                 //$comments_attachments = array();
                                 //$attachments_data = array();
                                 
                                 $show_more_link_task_attachments = hooks()->apply_filters('show_more_link_task_attachments', 4);
                                
                                 foreach($inspection->documentations as $attachment){ ?>
                              <?php ob_start(); ?>
                              <div data-num="<?php echo $i; ?>" data-inspection-attachment-id="<?php echo $attachment['id']; ?>" class="task-attachment-col col-md-6<?php if($i > $show_more_link_task_attachments){echo ' hide task-attachment-col-more';} ?>">
                                 <ul class="list-unstyled task-attachment-wrapper" data-placement="right" data-toggle="tooltip" data-title="<?php echo $attachment['file_name']; ?>" >
                                    <li class="mbot10 task-attachment<?php if(strtotime($attachment['dateadded']) >= strtotime('-16 hours')){echo ' highlight-bg'; } ?>">
                                       <div class="mbot10 pull-right inspection-attachment-user">
                                          <?php if($attachment['staffid'] == get_staff_user_id() || is_admin()){ ?>
                                          <a href="#" class="pull-right" onclick="remove_inspection_attachment(this,<?php echo $attachment['id']; ?>); return false;">
                                             <i class="fa fa fa-times"></i>
                                          </a>
                                          <?php }
                                             $externalPreview = false;
                                             $is_image = false;
                                             $path = INSPECTION_ATTACHMENTS_FOLDER . get_upload_path_by_type('inspections') . $inspection->id . '/'. $attachment['file_name'];

                                             $href_url = site_url('download/file/taskattachment/'. $attachment['attachment_key']);
                                             $isHtml5Video = is_html5_video($path);
                                             if(empty($attachment['external'])){
                                              $is_image = is_image($path);
                                              $img_url = site_url('download/preview_image?path='.protected_file_url_by_path($path,true).'&type='.$attachment['filetype']);
                                             } else if((!empty($attachment['thumbnail_link']) || !empty($attachment['external']))
                                             && !empty($attachment['thumbnail_link'])){
                                             $is_image = true;
                                             $img_url = optimize_dropbox_thumbnail($attachment['thumbnail_link']);
                                             $externalPreview = $img_url;
                                             $href_url = $attachment['external_link'];
                                             } else if(!empty($attachment['external']) && empty($attachment['thumbnail_link'])) {
                                             $href_url = $attachment['external_link'];
                                             }
                                             if(!empty($attachment['external']) && $attachment['external'] == 'dropbox' && $is_image){ ?>
                                          <a href="<?php echo $href_url; ?>" target="_blank" class="" data-toggle="tooltip" data-title="<?php echo _l('open_in_dropbox'); ?>"><i class="fa fa-dropbox" aria-hidden="true"></i></a>
                                          <?php } else if(!empty($attachment['external']) && $attachment['external'] == 'gdrive'){ ?>
                                          <a href="<?php echo $href_url; ?>" target="_blank" class="" data-toggle="tooltip" data-title="<?php echo _l('open_in_google'); ?>"><i class="fa fa-google" aria-hidden="true"></i></a>
                                          <?php }
                                             if($attachment['staffid'] != 0){
                                               echo '<a href="'.admin_url('profile/'.$attachment['staffid']).'" target="_blank">'.get_staff_full_name($attachment['staffid']) .'</a> - ';
                                             } else if($attachment['contact_id'] != 0) {
                                               echo '<a href="'.admin_url('clients/client/'.get_user_id_by_contact_id($attachment['contact_id']).'?contactid='.$attachment['contact_id']).'" target="_blank">'.get_contact_full_name($attachment['contact_id']) .'</a> - ';
                                             }
                                             echo '<span class="text-has-action" data-toggle="tooltip" data-title="'._dt($attachment['dateadded']).'">'.time_ago($attachment['dateadded']).'</span>';
                                             ?>
                                       </div>
                                       <div class="clearfix"></div>
                                       <div class="<?php if($is_image){echo 'preview-image';}else if(!$isHtml5Video){echo 'task-attachment-no-preview';} ?>">
                                          <?php
                                             // Not link on video previews because on click on the video is opening new tab
                                             if(!$isHtml5Video){ ?>
                                          <a href="<?php echo (!$externalPreview ? $href_url : $externalPreview); ?>" target="_blank"<?php if($is_image){ ?> data-lightbox="task-attachment"<?php } ?> class="<?php if($isHtml5Video){echo 'video-preview';} ?>">
                                             <?php } ?>
                                             <?php if($is_image){ ?>
                                             <img src="<?php echo $img_url; ?>" class="img img-responsive">
                                             <?php } else if($isHtml5Video) { ?>
                                             <video width="100%" height="100%" src="<?php echo site_url('download/preview_video?path='.protected_file_url_by_path($path).'&type='.$attachment['filetype']); ?>" controls>
                                                Your browser does not support the video tag.
                                             </video>
                                             <?php } else { ?>
                                             <i class="<?php echo get_mime_class($attachment['filetype']); ?>"></i>
                                             <?php echo $attachment['file_name']; ?>
                                             <?php } ?>
                                          </a>
                                       </div>
                                       <div class="clearfix"></div>
                                    </li>
                                 </ul>
                              </div>
                              <?php
                                 $attachments_data[$attachment['id']] = ob_get_contents();
                                 if($attachment['task_comment_id'] != 0) {
                                  $comments_attachments[$attachment['task_comment_id']][$attachment['id']] = $attachments_data[$attachment['id']];
                                 }
                                 ob_end_clean();
                                 echo $attachments_data[$attachment['id']];
                                 ?>
                              <?php
                                 $i++;
                                 } ?>
                           </div>
                        </div>
                        <div class="clearfix"></div>
                        <?php if(($i - 1) > $show_more_link_task_attachments){ ?>
                        <div class="col-md-12" id="show-more-less-inspections-attachments-col">
                           <a href="#" class="inspections-attachments-more" onclick="slideToggle('.inspections_attachments_wrapper .task-attachment-col-more', task_attachments_toggle); return false;"><?php echo _l('show_more'); ?></a>
                           <a href="#" class="inspections-attachments-less hide" onclick="slideToggle('.inspections_attachments_wrapper .task-attachment-col-more', task_attachments_toggle); return false;"><?php echo _l('show_less'); ?></a>
                        </div>
                        <?php } ?>
                        <div class="col-md-12 text-center">
                           <hr />
                           <a href="<?php echo admin_url('inspections/download_files/'.$inspection->id .'/'. $inspection->task_id); ?>" class="bold">
                           <?php echo _l('download_all'); ?> (.zip)
                           </a>
                        </div>
                     </div>
                  </div>
                  <?php } ?>
               </div>



            </div>
         </div>

      </div>
