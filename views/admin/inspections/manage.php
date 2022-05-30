<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
    <div class="content">
        <div class="row">
            <div class="col-md-12">
                <div class="panel_s">
                    <div class="panel-body">
                     <?php if(has_permission('inspections','','create')){ ?> 

                     <div class="_buttons">
                        <a href="<?php echo admin_url('inspections/create'); ?>" class="btn btn-info pull-left display-block"><?php echo _l('new_inspection'); ?></a>
                    </div>
                    <div class="clearfix"></div>
                    <hr class="hr-panel-heading" />
                    <?php } ?>
                    <?php render_datatable(array(
                        _l('inspection_number'),
                        _l('inspection_company'),
                        _l('inspection_list_project'),
                        _l('equiptment_type'),
                        _l('inspection_status'),
                        _l('inspection_start_date'),
                        _l('inspection_acceptance_name'),
                        _l('inspection_acceptance_date'),
                        //_l('inspection_end_date'),
                        ),'inspections'); ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
<?php init_tail(); ?>
<script type="text/javascript" id="inspection-js" src="<?= base_url() ?>modules/inspections/assets/js/inspections.js?"></script>
<script>
    $(function(){
        initDataTable('.table-inspections', window.location.href, 'undefined', 'undefined','fnServerParams', [0, 'desc']);
    });
</script>
</body>
</html>