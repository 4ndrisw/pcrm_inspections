<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<div class="mtop15 preview-top-wrapper">
   <div class="row">
      <div class="col-md-3">
         <div class="mbot30">
            <div class="inspection-html-logo">
               <?php echo get_dark_company_logo(); ?>
            </div>
         </div>
      </div>
      <div class="clearfix"></div>
   </div>
   <div class="top" data-sticky data-sticky-class="preview-sticky-header">
      <?php if(is_client_logged_in()  || is_staff_logged_in()) { ?>
      <div class="container preview-sticky-container">
         <div class="row">
            <div class="col-md-12">
               <div class="col-md-3">
                  <h3 class="bold no-mtop inspection-html-number no-mbot">
                     <span class="sticky-visible hide">
                     <?php echo format_inspection_number($inspection->id); ?>
                     </span>
                  </h3>
               </div>
            </div>
            <div class="clearfix"></div>
         </div>

      </div>
      <?php } ?>
   </div>
</div>
<div class="clearfix"></div>
<div class="col-md-6 col-md-offset-3">
   <div class="panel_s mtop20">
      <div class="panel-body text-center">
         <div class="row">
            <div class="col-md-12">
               <h1 class="bold inspection-html-number"><?php echo format_inspection_item_number($inspection->id, $inspection->task_id); ?></h1>
               <address class="inspection-html-company-info">
                  <?php echo format_organization_info(); ?>
               </address>
            </div>
               <?php if(is_client_logged_in() || is_staff_logged_in()) { ?>
                  <p class="bold"><?php echo $client_company; ?></p>
                  <p class="no-mbot inspection-html-project">
                     <span class="bold"><?php echo _l('project'); ?>:</span>
                     <?php echo get_project_name_by_id($inspection->project_id); ?>
                  </p>
               <?php } ?>
               <p class="no-mbot inspection-html-date">
                  <span class="bold">
                  <?php echo _l('inspection_data_date'); ?>:
                  </span>
                  <?php echo _d($inspection->date); ?>
               </p>
               <p class="no-mbot inspection-html-date">
                  <span class="bold">
                  <?php echo _l('inspection_equipment_nama_pesawat'); ?>:
                  </span>
                  <?php echo $equipment_name; ?>
               </p>

            <div class="row mtop25">
               <div class="col-md-12">
                     <div class="bold"><?php echo get_option('invoice_company_name'); ?></div>
                     <div class="qrcode text-center">
                        <img src="<?php echo site_url('download/preview_image?path='.protected_file_url_by_path(get_inspection_upload_path('inspection').$inspection->id.'/assigned-'.$inspection_number.'.png')); ?>" class="img-responsive center-block inspection-assigned" alt="inspection-<?= $inspection->id ?>">
                     </div>
                     <div class="assigned">
                     <?php if($inspection->assigned != 0 && get_option('show_assigned_on_inspections') == 1){ ?>
                        <?php echo get_staff_full_name($inspection->assigned); ?>
                     <?php } ?>

                     </div>
               </div>
            </div>
         </div>
      </div>
   </div>
</div>

<?php
   if($identity_confirmation_enabled == '1' && $can_be_accepted){
    get_template_part('identity_confirmation_form',array('formData'=>form_hidden('inspection_action',4)));
   }
?>
<script>
   $(function(){
     new Sticky('[data-sticky]');
   })
</script>
