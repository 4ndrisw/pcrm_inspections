<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

<div class="panel_s">
    <div class="panel-body">
     <?php if(has_permission('inspections','','create')){ ?>
     <div class="_buttons">
        <a href="<?php echo admin_url('inspections/create'); ?>" class="btn btn-info pull-left display-block"><?php echo _l('new_inspection'); ?></a>
     </div>
     <?php } ?>
     <?php if(has_permission('inspections','','create')){ ?>
     <div class="_buttons">
        <a href="<?php echo admin_url('inspections'); ?>" class="btn btn-primary pull-right display-block"><?php echo _l('inspections'); ?></a>
     </div>
     <?php } ?>
     <div class="clearfix"></div>
     <hr class="hr-panel-heading" />
     <div class="table-responsive">
        <?php render_datatable(array(
            _l('inspection_number'),
            _l('inspection_company'),
            _l('equipment_type'),
            _l('inspection_start_date'),
            ),'inspections'); ?>
     </div>
    </div>
</div>
