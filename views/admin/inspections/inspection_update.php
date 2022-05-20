<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper">
	<div class="content inspection-update">
		<div class="row">
			<?php
			echo form_open($this->uri->uri_string(),array('id'=>'inspection-form','class'=>'_transaction_form'));
			if(isset($inspection)){
				echo form_hidden('isedit');
			}
			?>
			<div class="col-md-12">
				<?php $this->load->view('admin/inspections/inspection_template'); ?>
			</div>
			<?php echo form_close(); ?>
			<?php $this->load->view('admin/invoice_items/item'); ?>
		</div>
	</div>
</div>
</div>
<?php init_tail(); ?>

<script type="text/javascript" src="/modules/inspections/assets/js/inspections.js?<?=strtotime('now')?>"></script>

<script>
	$(function(){
		validate_inspection_form();
		// Project ajax search
		init_ajax_project_search_by_customer_id();
		// Maybe items ajax search
	    init_ajax_search('items','#item_select.ajax-search',undefined,admin_url+'items/search');
	    add_inspection_item_to_table('data', 'itemid');
	});
</script>
</body>
</html>
