<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php echo form_hidden('equiptment_type','forklift'); ?>
<div class="horizontal-scrollable-tabs mbot15">
   <div class="scroller arrow-left"><i class="fa fa-angle-left"></i></div>
   <div class="scroller arrow-right"><i class="fa fa-angle-right"></i></div>
   <div class="horizontal-tabs">
      <ul class="nav nav-tabs nav-tabs-horizontal" role="tablist">
         <li role="presentation" class="active">
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
         <?php hooks()->do_action('after_finance_settings_last_tab'); ?>
      </ul>
   </div>
</div>
<div class="tab-content">
   <div role="tabpanel" class="tab-pane active" id="general_data">
      <h4 class="bold">
         <?php echo _l('general_data'); ?>
      </h4>
      <p class="text-muted">
         <p><?php echo _l('general_data'); ?></p>
      </p>
      <hr />
      <div class="row">

      </div>

   </div>
   <div role="tabpanel" class="tab-pane" id="technical_data">

      <h4 class="bold">
         <?php echo _l('technical_data'); ?>
      </h4>
      <p class="text-muted">
         <p><?php echo _l('technical_data'); ?></p>
      </p>
      <hr />
      <div class="row">

      </div>
  </div>
   <div role="tabpanel" class="tab-pane" id="operation_test">
      <h4 class="bold">
         <?php echo _l('operation_test'); ?>
      </h4>
      <p class="text-muted">
         <p><?php echo _l('operation_test'); ?></p>
      </p>
      <hr />
      <div class="row">

      </div>
   </div>
   <div role="tabpanel" class="tab-pane" id="safety_test">
      <h4 class="bold">
         <?php echo _l('safety_test'); ?>
      </h4>
      <p class="text-muted">
         <p><?php echo _l('safety_test'); ?></p>
      </p>
      <hr />
      <div class="row">

      </div>
   </div>
   <div role="tabpanel" class="tab-pane" id="conclusion">
      <h4 class="bold">
         <?php echo _l('conclusion'); ?>
      </h4>
      <p class="text-muted">
         <p><?php echo _l('conclusion'); ?></p>
      </p>
      <hr />
      <div class="row">

      </div>
  
   </div>

</div>