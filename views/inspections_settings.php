<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php echo form_hidden('inspections_settings'); ?>
<div class="horizontal-scrollable-tabs mbot15">
   <div role="tabpanel" class="tab-pane" id="inspections">
      <div class="form-group">
         <label class="control-label" for="inspection_prefix"><?php echo _l('inspection_prefix'); ?></label>
         <input type="text" name="settings[inspection_prefix]" class="form-control" value="<?php echo get_option('inspection_prefix'); ?>">
      </div>
      <hr />
      <i class="fa fa-question-circle pull-left" data-toggle="tooltip" data-title="<?php echo _l('next_inspection_number_tooltip'); ?>"></i>
      <?php echo render_input('settings[next_inspection_number]','next_inspection_number',get_option('next_inspection_number'), 'number', ['min'=>1]); ?>
      <hr />
      <i class="fa fa-question-circle pull-left" data-toggle="tooltip" data-title="<?php echo _l('inspection_number_of_date_tooltip'); ?>"></i>
      <?php echo render_input('settings[inspection_number_of_date]','inspection_number_of_date',get_option('inspection_number_of_date'), 'number', ['min'=>0]); ?>
      <hr />
      <i class="fa fa-question-circle pull-left" data-toggle="tooltip" data-title="<?php echo _l('inspection_start_date_label'); ?>"></i>
        <?php $start_date = get_option('inspection_start_date'); ?>
        <?php $value = $start_date ? $start_date : date('Y-m-d'); ?>
        <?php echo render_date_input('settings[inspection_start_date]','inspection_start_date',$value); ?>

      <hr />
      <i class="fa fa-question-circle pull-left" data-toggle="tooltip" data-title="<?php echo _l('due_after_help'); ?>"></i>
      <?php echo render_input('settings[inspection_qrcode_size]', 'inspection_qrcode_size', get_option('inspection_qrcode_size')); ?>
      <hr />
      <i class="fa fa-question-circle pull-left" data-toggle="tooltip" data-title="<?php echo _l('due_after_help'); ?>"></i>
      <?php echo render_input('settings[inspection_due_after]','inspection_due_after',get_option('inspection_due_after')); ?>
      <hr />
      <?php render_yes_no_option('delete_only_on_last_inspection','delete_only_on_last_inspection'); ?>
      <hr />
      <?php render_yes_no_option('inspection_number_decrement_on_delete','decrement_inspection_number_on_delete','decrement_inspection_number_on_delete_tooltip'); ?>
      <hr />
      <?php echo render_yes_no_option('allow_staff_view_inspections_assigned','allow_staff_view_inspections_assigned'); ?>
      <hr />
      <?php render_yes_no_option('view_inspection_only_logged_in','require_client_logged_in_to_view_inspection'); ?>
      <hr />
      <?php render_yes_no_option('show_assigned_on_inspections','show_assigned_on_inspections'); ?>
      <hr />
      <?php render_yes_no_option('show_project_on_inspection','show_project_on_inspection'); ?>
      <hr />
      <?php render_yes_no_option('show_inspections_clients_area_menu_items','show_inspections_clients_area_menu_items'); ?>
      <hr />
      <?php
      $staff = $this->staff_model->get('', ['active' => 1]);
      $selected = get_option('default_inspection_assigned');
      foreach($staff as $member){
       
         if($selected == $member['staffid']) {
           $selected = $member['staffid'];
         
       }
      }
      echo render_select('settings[default_inspection_assigned]',$staff,array('staffid',array('firstname','lastname')),'default_inspection_assigned_string',$selected);
      ?>
      <hr />
      <?php render_yes_no_option('exclude_inspection_from_client_area_with_draft_status','exclude_inspection_from_client_area_with_draft_status'); ?>
      <hr />   
      <?php render_yes_no_option('inspection_accept_identity_confirmation','inspection_accept_identity_confirmation'); ?>
      <hr />
      <?php echo render_input('settings[inspection_year]','inspection_year',get_option('inspection_year'), 'number', ['min'=>2020]); ?>
      <hr />
      
      <div class="form-group">
         <label for="inspection_number_format" class="control-label clearfix"><?php echo _l('inspection_number_format'); ?></label>
         <div class="radio radio-primary radio-inline">
            <input type="radio" name="settings[inspection_number_format]" value="1" id="e_number_based" <?php if(get_option('inspection_number_format') == '1'){echo 'checked';} ?>>
            <label for="e_number_based"><?php echo _l('inspection_number_format_number_based'); ?></label>
         </div>
         <div class="radio radio-primary radio-inline">
            <input type="radio" name="settings[inspection_number_format]" value="2" id="e_year_based" <?php if(get_option('inspection_number_format') == '2'){echo 'checked';} ?>>
            <label for="e_year_based"><?php echo _l('inspection_number_format_year_based'); ?> (YYYY.000001)</label>
         </div>
         <div class="radio radio-primary radio-inline">
            <input type="radio" name="settings[inspection_number_format]" value="3" id="e_short_year_based" <?php if(get_option('inspection_number_format') == '3'){echo 'checked';} ?>>
            <label for="e_short_year_based">000001-YY</label>
         </div>
         <div class="radio radio-primary radio-inline">
            <input type="radio" name="settings[inspection_number_format]" value="4" id="e_year_month_based" <?php if(get_option('inspection_number_format') == '4'){echo 'checked';} ?>>
            <label for="e_year_month_based">000001.MM.YYYY</label>
         </div>
         <hr />
      </div>
      <div class="row">
         <div class="col-md-12">
            <?php echo render_input('settings[inspections_pipeline_limit]','pipeline_limit_status',get_option('inspections_pipeline_limit')); ?>
         </div>
         <div class="col-md-7">
            <label for="default_proposals_pipeline_sort" class="control-label"><?php echo _l('default_pipeline_sort'); ?></label>
            <select name="settings[default_inspections_pipeline_sort]" id="default_inspections_pipeline_sort" class="selectpicker" data-width="100%" data-none-selected-text="<?php echo _l('dropdown_non_selected_tex'); ?>">
               <option value="datecreated" <?php if(get_option('default_inspections_pipeline_sort') == 'datecreated'){echo 'selected'; }?>><?php echo _l('inspections_sort_datecreated'); ?></option>
               <option value="date" <?php if(get_option('default_inspections_pipeline_sort') == 'date'){echo 'selected'; }?>><?php echo _l('inspections_sort_inspection_date'); ?></option>
               <option value="pipeline_order" <?php if(get_option('default_inspections_pipeline_sort') == 'pipeline_order'){echo 'selected'; }?>><?php echo _l('inspections_sort_pipeline'); ?></option>
               <option value="expirydate" <?php if(get_option('default_inspections_pipeline_sort') == 'expirydate'){echo 'selected'; }?>><?php echo _l('inspections_sort_expiry_date'); ?></option>
            </select>
         </div>
         <div class="col-md-5">
            <div class="mtop30 text-right">
               <div class="radio radio-inline radio-primary">
                  <input type="radio" id="k_desc_inspection" name="settings[default_inspections_pipeline_sort_type]" value="asc" <?php if(get_option('default_inspections_pipeline_sort_type') == 'asc'){echo 'checked';} ?>>
                  <label for="k_desc_inspection"><?php echo _l('order_ascending'); ?></label>
               </div>
               <div class="radio radio-inline radio-primary">
                  <input type="radio" id="k_asc_inspection" name="settings[default_inspections_pipeline_sort_type]" value="desc" <?php if(get_option('default_inspections_pipeline_sort_type') == 'desc'){echo 'checked';} ?>>
                  <label for="k_asc_inspection"><?php echo _l('order_descending'); ?></label>
               </div>
            </div>
         </div>
         <div class="clearfix"></div>
      </div>
      <hr  />
      <?php echo render_textarea('settings[predefined_terms_inspection]','predefined_terms',get_option('predefined_terms_inspection'),array('rows'=>3)); ?>

      <?php echo render_textarea('settings[predefined_regulation_of_paa]','regulation_of_paa',get_option('predefined_regulation_of_paa'),array('rows'=>3)); ?>
      <?php
      $staff = $this->staff_model->get('', ['active' => 1]);
      $selected = get_option('default_inspection_assigned_paa');
      foreach($staff as $member){
       
         if($selected == $member['staffid']) {
           $selected = $member['staffid'];
         
       }
      }
      echo render_select('settings[default_inspection_assigned_paa]',$staff,array('staffid',array('firstname','lastname')),'default_inspection_assigned_paa_string',$selected);
      ?>
      <hr />
      <?php echo render_textarea('settings[predefined_regulation_of_pubt]','regulation_of_pubt',get_option('predefined_regulation_of_pubt'),array('rows'=>3)); ?>
      <?php
      $staff = $this->staff_model->get('', ['active' => 1]);
      $selected = get_option('default_inspection_assigned_pubt');
      foreach($staff as $member){
       
         if($selected == $member['staffid']) {
           $selected = $member['staffid'];
         
       }
      }
      echo render_select('settings[default_inspection_assigned_pubt]',$staff,array('staffid',array('firstname','lastname')),'default_inspection_assigned_pubt_string',$selected);
      ?>
      <hr />
      <?php echo render_textarea('settings[predefined_regulation_of_lie]','regulation_of_lie',get_option('predefined_regulation_of_lie'),array('rows'=>3)); ?>
      <?php
      $staff = $this->staff_model->get('', ['active' => 1]);
      $selected = get_option('default_inspection_assigned_lie');
      foreach($staff as $member){
       
         if($selected == $member['staffid']) {
           $selected = $member['staffid'];
         
       }
      }
      echo render_select('settings[default_inspection_assigned_lie]',$staff,array('staffid',array('firstname','lastname')),'default_inspection_assigned_lie_string',$selected);
      ?>
      <hr />
      <?php echo render_textarea('settings[predefined_regulation_of_ipp]','regulation_of_ipp',get_option('predefined_regulation_of_ipp'),array('rows'=>3)); ?>
      <?php
      $staff = $this->staff_model->get('', ['active' => 1]);
      $selected = get_option('default_inspection_assigned_ipp');
      foreach($staff as $member){
       
         if($selected == $member['staffid']) {
           $selected = $member['staffid'];
         
       }
      }
      echo render_select('settings[default_inspection_assigned_ipp]',$staff,array('staffid',array('firstname','lastname')),'default_inspection_assigned_ipp_string',$selected);
      ?>
      <hr />
      <?php echo render_textarea('settings[predefined_regulation_of_iil]','regulation_of_iil',get_option('predefined_regulation_of_iil'),array('rows'=>3)); ?>
      <?php
      $staff = $this->staff_model->get('', ['active' => 1]);
      $selected = get_option('default_inspection_assigned_iil');
      foreach($staff as $member){
       
         if($selected == $member['staffid']) {
           $selected = $member['staffid'];
         
       }
      }
      echo render_select('settings[default_inspection_assigned_iil]',$staff,array('staffid',array('firstname','lastname')),'default_inspection_assigned_iil_string',$selected);
      ?>
       <hr />
      <?php echo render_textarea('settings[predefined_regulation_of_ipka]','regulation_of_ipka',get_option('predefined_regulation_of_ipka'),array('rows'=>3)); ?>
     <hr />
      <?php
      $staff = $this->staff_model->get('', ['active' => 1]);
      $selected = get_option('default_inspection_assigned_ipka');
      foreach($staff as $member){
       
         if($selected == $member['staffid']) {
           $selected = $member['staffid'];
         
       }
      }
      echo render_select('settings[default_inspection_assigned_ipka]',$staff,array('staffid',array('firstname','lastname')),'default_inspection_assigned_ipka_string',$selected);
      ?>

       <hr />
      <?php echo render_textarea('settings[predefined_regulation_of_ipk]','regulation_of_ipk',get_option('predefined_regulation_of_ipk'),array('rows'=>3)); ?>
      <hr />
      <?php
      $staff = $this->staff_model->get('', ['active' => 1]);
      $selected = get_option('default_inspection_assigned_ipk');
      foreach($staff as $member){
       
         if($selected == $member['staffid']) {
           $selected = $member['staffid'];
         
       }
      }
      echo render_select('settings[default_inspection_assigned_ipk]',$staff,array('staffid',array('firstname','lastname')),'default_inspection_assigned_ipk_string',$selected);
      ?>

      <hr />
      <?php echo render_textarea('settings[predefined_regulation_of_ipkh]','regulation_of_ipkh',get_option('predefined_regulation_of_ipkh'),array('rows'=>3)); ?>
      <hr />
      <?php
      $staff = $this->staff_model->get('', ['active' => 1]);
      $selected = get_option('default_inspection_assigned_ipkh');
      foreach($staff as $member){
       
         if($selected == $member['staffid']) {
           $selected = $member['staffid'];
         
       }
      }
      echo render_select('settings[default_inspection_assigned_ipkh]',$staff,array('staffid',array('firstname','lastname')),'default_inspection_assigned_ipkh_string',$selected);
      ?>
      <hr />
      <?php echo render_textarea('settings[predefined_regulation_of_ptp]','regulation_of_ptp',get_option('predefined_regulation_of_ptp'),array('rows'=>3)); ?>
      <?php
      $staff = $this->staff_model->get('', ['active' => 1]);
      $selected = get_option('default_inspection_assigned_ptp');
      foreach($staff as $member){
       
         if($selected == $member['staffid']) {
           $selected = $member['staffid'];
         
       }
      }
      echo render_select('settings[default_inspection_assigned_ptp]',$staff,array('staffid',array('firstname','lastname')),'default_inspection_assigned_ptp_string',$selected);
      ?>
      <hr />
      <?php 

          $inspections_model = 'Inspections_model';
          $model_path = FCPATH . 'modules/'. INSPECTIONS_MODULE_NAME .'/models/' . $inspections_model .'.php';

          include_once($model_path);
          $this->load->model($inspections_model);
          
          $CI = &get_instance();

          $CI->load->model('inspections_model');   
          $taggables = get_available_tags();

         if(is_array($taggables)){
            foreach ($taggables as $tag) {
               echo render_input('settings[tag_id_'.$tag['tag_id'].']','Category untuk Tag ID '.$tag['tag_id'].' '.$tag['tag_name'] ,get_option('tag_id_'.$tag['tag_id'])); 
            }
         }else{
            echo $taggables;
         }
      ?>
   </div>
 <?php hooks()->do_action('after_inspections_tabs_content'); ?>
</div>
