<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-6 no-padding">
				<?php $this->load->view('admin/inspections/inspection_table_related'); ?>

				<div class="clearfix"></div>
				<?php 
					$this->load->view('admin/inspections/inspection_small_table'); 
				?>
			</div>
			<div class="col-md-6 no-padding inspection-preview">
				<?php $this->load->view('admin/inspections/inspection_preview_template'); ?>
			</div>
		</div>
	</div>
</div>
<?php init_tail(); ?>
<script type="text/javascript" id="licence-js" src="<?= base_url() ?>modules/inspections/assets/js/inspections.js?"></script>

<script>
   init_btn_with_tooltips();
   init_datepicker();
   init_selectpicker();
   init_form_reminder();
   init_tabs_scrollable();
   <?php if($send_later) { ?>
      inspection_inspection_send(<?php echo $inspection->id; ?>);
   <?php } ?>
</script>

<script>
    $(function(){
        initDataTable('.table-inspections', window.location.href, 'undefined', 'undefined','fnServerParams', [0, 'desc']);
    });
</script>

<script>
    $(function(){
        initDataTable('.table-inspection-items', admin_url+'inspections/table_items', 'undefined', 'undefined','fnServerParams', [0, 'desc']);
        initDataTable('.table-inspection-related', admin_url+'inspections/table_related', 'undefined', 'undefined','fnServerParams', [0, 'desc']);
    });
</script>

</body>
</html>
