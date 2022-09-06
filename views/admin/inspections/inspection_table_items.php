<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>

    <div class="panel_s">
        <div class="panel-body">
         <div class="clearfix"></div>
         <hr class="hr-panel-heading" />
         <div class="table-responsive">
            <?php render_datatable(array(
                _l('inspection_task'),
                _l('inspection_equipment'),
                _l('inspection_tag'),
                _l('inspection_flag'),
                ),'inspection-items'); ?>
         </div>
        </div>
    </div>
