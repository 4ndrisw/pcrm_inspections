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
                  <a href="#inspection_pompa_test" aria-controls="inspection_pompa_test" role="tab" data-toggle="tab"><?php echo _l('inspection_pompa_test'); ?></a>
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
                                <td style="width:20%">Tahun Pembuatan</td>
                                <td style="width:2%">:</td>
                                <td id="tahun_pembuatan" class="<?= $editable_class ?>" data-field="tahun_pembuatan" data-jenis_pesawat="<?= $inspection->equipment_type ?>" data-inspection_id="<?= $inspection->id ?>" data-task_id="<?= $task->id ?>"><?= isset($equipment['tahun_pembuatan']) ? $equipment['tahun_pembuatan'] : '' ?></td>      
                             </tr>
                             <tr>
                                <td style="width:20%">Jumlah Kotak Hydrant</td>
                                <td style="width:2%">:</td>
                                <td class="<?= $editable_class ?>" data-field="jumlah_kotak_hydrant" data-jenis_pesawat="<?= $inspection->equipment_type ?>" data-inspection_id="<?= $inspection->id ?>" data-task_id="<?= $task->id ?>"><?= isset($equipment['jumlah_kotak_hydrant']) ? $equipment['jumlah_kotak_hydrant'] : '' ?></td>      
                             </tr>
                             <tr>
                                <td style="width:20%">Jumlah Selang Hydrant</td>
                                <td style="width:2%">:</td>
                                <td class="<?= $editable_class ?>" data-field="jumlah_selang_hydrant" data-jenis_pesawat="<?= $inspection->equipment_type ?>" data-inspection_id="<?= $inspection->id ?>" data-task_id="<?= $task->id ?>"><?= isset($equipment['jumlah_selang_hydrant']) ? $equipment['jumlah_selang_hydrant'] : '' ?></td>
                             </tr>
                             <tr>
                                <td style="width:20%">Jumlah Bozzle</td>
                                <td style="width:2%">:</td>
                                <td class="<?= $editable_class ?>" data-field="jumlah_nozzle" data-jenis_pesawat="<?= $inspection->equipment_type ?>" data-inspection_id="<?= $inspection->id ?>" data-task_id="<?= $task->id ?>"><?= isset($equipment['jumlah_nozzle']) ? $equipment['jumlah_nozzle'] : '' ?></td>
                             </tr>
                             <tr>
                                <td style="width:20%">Tekanan Pompa</td>
                                <td style="width:2%">:</td>
                                <td class="<?= $editable_class ?>" data-field="tekanan_pompa" data-jenis_pesawat="<?= $inspection->equipment_type ?>" data-inspection_id="<?= $inspection->id ?>" data-task_id="<?= $task->id ?>"><?= isset($equipment['tekanan_pompa']) ? $equipment['tekanan_pompa'] : '' ?></td>
                             </tr>
                             <tr>
                                <td style="width:20%">Kapasitas Air</td>
                                <td style="width:2%">:</td>
                                <td class="<?= $editable_class ?>" data-field="kapasitas_air" data-jenis_pesawat="<?= $inspection->equipment_type ?>" data-inspection_id="<?= $inspection->id ?>" data-task_id="<?= $task->id ?>"><?= isset($equipment['kapasitas_air']) ? $equipment['kapasitas_air'] : '' ?></td>
                             </tr>
                             <tr>
                                <td style="width:20%">Jenis pemeriksaan</td>
                                <td style="width:2%">:</td>
                                <td class="<?= $editable_class ?>" data-field="jenis_pemeriksaan" data-jenis_pesawat="<?= $inspection->equipment_type ?>" data-inspection_id="<?= $inspection->id ?>" data-task_id="<?= $task->id ?>"><?= isset($equipment['jenis_pemeriksaan']) ? $equipment['jenis_pemeriksaan'] : '' ?></td>
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

                           <div class="row vertical-align-middle pengujian_pompa text-center">
                              <div class="col-xs-12 col-sm-3">
                                 <label class="btn-secondary"><?= _l('pengujian_pompa') ?> </label>
                                 <?php $equipment_pengujian_pompa = isset($equipment_pengujian_pompa) ? $equipment_pengujian_pompa : ''; ?>
                                 <?= $equipment_pengujian_pompa ?>
                              </div>
                              <div class="col-sm-1  no-padding hidden-xs text-center">
                                 :
                              </div>
                              <div class="col-xs-12 col-sm-8">
                                 <div class="row">
                                    <div class="col-xs-12 col-sm-4 no-padding">
                                       <input type="radio" class="btn-check" id="pengujian_pompa_t" onchange="inspection_item_pengujian_data(this, '<?= $inspection->equipment_type ?>', 'pengujian_pompa', <?= $inspection->id ?>, <?= $task->id ?>)" name="equipment[pengujian_pompa]" value="1" <?php if(isset($equipment['pengujian_pompa']) && $equipment['pengujian_pompa'] == 1){echo 'checked';}; ?>>
                                       <label class="btn btn-secondary" for="pengujian_pompa_t"><i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?php echo _l('baik'); ?>" ></i> <?php echo _l('baik'); ?></label>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 no-padding">
                                       <input type="radio" class="btn-check" id="pengujian_pompa_f" onchange="inspection_item_pengujian_data(this, '<?= $inspection->equipment_type ?>', 'pengujian_pompa', <?= $inspection->id ?>, <?= $task->id ?>)" name="equipment[pengujian_pompa]" value="2" <?php if(isset($equipment['pengujian_pompa']) && $equipment['pengujian_pompa'] == 2){echo 'checked';}; ?>>
                                       <label class="btn btn-secondary" for="pengujian_pompa_f"><?php echo _l('tidak_baik'); ?></label>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 no-padding">
                                       <input type="radio" class="btn-check" id="pengujian_pompa_n" onchange="inspection_item_pengujian_data(this, '<?= $inspection->equipment_type ?>', 'pengujian_pompa', <?= $inspection->id ?>, <?= $task->id ?>)" name="equipment[pengujian_pompa]" value="0" <?php if(isset($equipment['pengujian_pompa']) && $equipment['pengujian_pompa'] == 0){echo 'checked';}; ?>>
                                       <label class="btn btn-secondary" for="pengujian_pompa_n"><?php echo _l('tidak_ada'); ?></label>
                                    </div>
                                 </div>
                              </div>
                           </div>

                           <div class="row vertical-align-middle pengujian_tekanan text-center">
                              <div class="col-xs-12 col-sm-3">
                                 <label class="btn-secondary"><?= _l('pengujian_tekanan') ?> </label>
                                 <?php $equipment_pengujian_tekanan = isset($equipment_pengujian_tekanan) ? $equipment_pengujian_tekanan : ''; ?>
                                 <?= $equipment_pengujian_tekanan ?>
                              </div>
                              <div class="col-sm-1  no-padding hidden-xs text-center">
                                 :
                              </div>
                              <div class="col-xs-12 col-sm-8">
                                 <div class="row">
                                    <div class="col-xs-12 col-sm-4 no-padding">
                                       <input type="radio" class="btn-check" id="pengujian_tekanan_t" onchange="inspection_item_pengujian_data(this, '<?= $inspection->equipment_type ?>', 'pengujian_tekanan', <?= $inspection->id ?>, <?= $task->id ?>)" name="equipment[pengujian_tekanan]" value="1" <?php if(isset($equipment['pengujian_tekanan']) && $equipment['pengujian_tekanan'] == 1){echo 'checked';}; ?>>
                                       <label class="btn btn-secondary" for="pengujian_tekanan_t"><i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?php echo _l('baik'); ?>" ></i> <?php echo _l('baik'); ?></label>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 no-padding">
                                       <input type="radio" class="btn-check" id="pengujian_tekanan_f" onchange="inspection_item_pengujian_data(this, '<?= $inspection->equipment_type ?>', 'pengujian_tekanan', <?= $inspection->id ?>, <?= $task->id ?>)" name="equipment[pengujian_tekanan]" value="2" <?php if(isset($equipment['pengujian_tekanan']) && $equipment['pengujian_tekanan'] == 2){echo 'checked';}; ?>>
                                       <label class="btn btn-secondary" for="pengujian_tekanan_f"><?php echo _l('tidak_baik'); ?></label>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 no-padding">
                                       <input type="radio" class="btn-check" id="pengujian_tekanan_n" onchange="inspection_item_pengujian_data(this, '<?= $inspection->equipment_type ?>', 'pengujian_tekanan', <?= $inspection->id ?>, <?= $task->id ?>)" name="equipment[pengujian_tekanan]" value="0" <?php if(isset($equipment['pengujian_tekanan']) && $equipment['pengujian_tekanan'] == 0){echo 'checked';}; ?>>
                                       <label class="btn btn-secondary" for="pengujian_tekanan_n"><?php echo _l('tidak_ada'); ?></label>
                                    </div>
                                 </div>
                              </div>
                           </div>
                           <div class="row vertical-align-middle pengujian_daya_pancar text-center">
                              <div class="col-xs-12 col-sm-3">
                                 <label class="btn-secondary"><?= _l('pengujian_daya_pancar') ?> </label>
                                 <?php $equipment_pengujian_daya_pancar = isset($equipment_pengujian_daya_pancar) ? $equipment_pengujian_daya_pancar : ''; ?>
                                 <?= $equipment_pengujian_daya_pancar ?>
                              </div>
                              <div class="col-sm-1  no-padding hidden-xs text-center">
                                 :
                              </div>
                              <div class="col-xs-12 col-sm-8">
                                 <div class="row">
                                    <div class="col-xs-12 col-sm-4 no-padding">
                                       <input type="radio" class="btn-check" id="pengujian_daya_pancar_t" onchange="inspection_item_pengujian_data(this, '<?= $inspection->equipment_type ?>', 'pengujian_daya_pancar', <?= $inspection->id ?>, <?= $task->id ?>)" name="equipment[pengujian_daya_pancar]" value="1" <?php if(isset($equipment['pengujian_daya_pancar']) && $equipment['pengujian_daya_pancar'] == 1){echo 'checked';}; ?>>
                                       <label class="btn btn-secondary" for="pengujian_daya_pancar_t"><i class="fa fa-question-circle" data-toggle="tooltip" data-placement="left" title="<?php echo _l('baik'); ?>" ></i> <?php echo _l('baik'); ?></label>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 no-padding">
                                       <input type="radio" class="btn-check" id="pengujian_daya_pancar_f" onchange="inspection_item_pengujian_data(this, '<?= $inspection->equipment_type ?>', 'pengujian_daya_pancar', <?= $inspection->id ?>, <?= $task->id ?>)" name="equipment[pengujian_daya_pancar]" value="2" <?php if(isset($equipment['pengujian_daya_pancar']) && $equipment['pengujian_daya_pancar'] == 2){echo 'checked';}; ?>>
                                       <label class="btn btn-secondary" for="pengujian_daya_pancar_f"><?php echo _l('tidak_baik'); ?></label>
                                    </div>
                                    <div class="col-xs-12 col-sm-4 no-padding">
                                       <input type="radio" class="btn-check" id="pengujian_daya_pancar_n" onchange="inspection_item_pengujian_data(this, '<?= $inspection->equipment_type ?>', 'pengujian_daya_pancar', <?= $inspection->id ?>, <?= $task->id ?>)" name="equipment[pengujian_daya_pancar]" value="0" <?php if(isset($equipment['pengujian_daya_pancar']) && $equipment['pengujian_daya_pancar'] == 0){echo 'checked';}; ?>>
                                       <label class="btn btn-secondary" for="pengujian_daya_pancar_n"><?php echo _l('tidak_ada'); ?></label>
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
                              <td class="<?= $editableText_class ?> pbot15" data-field="regulasi" data-jenis_pesawat="<?= $inspection->equipment_type ?>" data-inspection_id="<?= $inspection->id ?>" data-task_id="<?= $task->id ?>"><?= isset($equipment['regulasi']) ? $equipment['regulasi'] : get_option('predefined_regulation_of_ptp') ?></td>
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
         <div role="tabpanel" class="tab-pane" id="inspection_pompa_test">
            <h4 class="bold">
               <?php echo _l('inspection_pompa_test'); ?>
            </h4>
            <p class="text-muted">
               <p><?php echo _l('inspection_pompa_test'); ?></p>
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
