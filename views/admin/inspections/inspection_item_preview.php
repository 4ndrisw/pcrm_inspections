<?php defined('BASEPATH') or exit('No direct script access allowed'); ?>
<?php init_head(); ?>
<div id="wrapper" class="inspection-item-wrapper">
	<div class="content">
		<div class="row">
			<div class="col-md-6 no-padding">
				<?php $this->load->view('admin/inspections/inspection_table_items_submitted'); ?>
			</div>
			<div class="col-md-6 no-padding inspection-preview">
				<?php $this->load->view('admin/inspections/inspection_item_preview_template'); ?>
			</div>
		</div>
    <div class="row">
      
      <div role="button" class="col-md-3 mtop10 mbot10">
          <input class ="btn btn-sm" type="button" id="refresh" value="Refresh" />
      </div>
      <div role="button" class="col-md-3 mtop10 mbot10">
          <a class="btn btn-sm btn-danger" href="<?php echo admin_url() . 'inspections/inspection_report/'.$inspection->id.'/'.$inspection->task_id; ?>">
          <?php echo _l('download'); ?></a>
      </div>

      <div id="equipment-template-wrapper" class="col-md-12 no-padding equipment-template">
        <?php

            $template = strtolower($inspection->equipment_type) . '_ajax_template';
            $this->load->view('admin/equipment/' . $template);
        ?>
      </div>
    </div>
	</div>
</div>

<?php init_tail(); ?>
<script type="text/javascript" id="inspection-js" src="<?= base_url() ?>modules/inspections/assets/js/inspections.js?"></script>

<script>
   init_btn_with_tooltips();
   //validateTahunPembuatan();
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
        initDataTable('.table-inspection-items-submitted', admin_url+'inspections/table_items_submitted', 'undefined', 'undefined','fnServerParams', [0, 'desc']);
    });
</script>

<script type="text/javascript">
  $("#refresh").click(function(){
    //$("#equipment-template-wrapper").load(location.href+" #equipment-template-wrapper>*","");
    console.log("to verify");
  });
</script>

<script type="text/javascript">
	// Editable
function Editable(sel, options) {
  if (!(this instanceof Editable)) return new Editable(...arguments);

  const attr = (EL, obj) => Object.entries(obj).forEach(([prop, val]) => EL.setAttribute(prop, val));

  Object.assign(this, {
    onStart() {},
    onInput() {},
    onEnd() {},
    classEditing: "is-editing", // added onStart
    classModified: "is-modified", // added onEnd if content changed
  }, options || {}, {
    elements: document.querySelectorAll(sel),
    element: null, // the latest edited Element
    isModified: false, // true if onEnd the HTML content has changed
  });

  const start = (ev) => {
    this.isModified = false;
    this.element = ev.currentTarget;
    this.element.classList.add(this.classEditing);
    this.text_before = ev.currentTarget.textContent;
    this.html_before = ev.currentTarget.innerHTML;
    this.onStart.call(this.element, ev, this);
  };

  const input = (ev) => {
    this.text = this.element.textContent;
    this.html = this.element.innerHTML;
    this.isModified = this.html !== this.html_before;
    this.element.classList.toggle(this.classModified, this.isModified);
    this.onInput.call(this.element, ev, this);
  }

  const end = (ev) => {
    this.element.classList.remove(this.classEditing);
    this.onEnd.call(this.element, ev, this);
  }

  this.elements.forEach(el => {
    attr(el, {tabindex: 1, contenteditable: true});
    el.addEventListener("focusin", start);
    el.addEventListener("input", input);
    el.addEventListener("focusout", end);
  });

  return this;
}

// Use like:
Editable(".editable", {
  onEnd(ev, UI) { // ev=Event UI=Editable this=HTMLElement
    if (!UI.isModified) return; // No change in content. Abort here.
    const data = {
      rel_id: this.dataset.inspection_id,
      field: this.dataset.field,
      jenis_pesawat: this.dataset.jenis_pesawat,
      task_id: this.dataset.task_id,
      text: this.textContent, // or you can also use UI.text
      //text: this.innerHTML, // or you can also use UI.text
    }
    //console.log(data); // Submit your data to server
    inspection_update_inspection_data(data);

  }
});

// Use like:
Editable(".editableText", {
  onEnd(ev, UI) { // ev=Event UI=Editable this=HTMLElement
    if (!UI.isModified) return; // No change in content. Abort here.
    const data = {
      rel_id: this.dataset.inspection_id,
      field: this.dataset.field,
      jenis_pesawat: this.dataset.jenis_pesawat,
      task_id: this.dataset.task_id,
      //text: this.textContent, // or you can also use UI.text
      text: this.innerHTML, // or you can also use UI.text
    }
    //console.log(data); // Submit your data to server
    inspection_update_inspection_data(data);

  }
});

   Dropzone.autoDiscover = false;
   inspectionDocumentationDropzone = new Dropzone("#inspection-documentation", appCreateDropzoneOptions({
      url: "<?php echo admin_url('inspections/upload_file') ?>",
      uploadMultiple: true,
      parallelUploads: 20,
      maxFiles: 20,
      paramName: 'file',
      addRemoveLinks:true,
      sending: function (file, xhr, formData) {
         formData.append("rel_id", '<?php echo $inspection->id; ?>');
         formData.append("task_id", '<?php echo $inspection->task_id; ?>');
      },
      success: function (files, response) {
         response = JSON.parse(response);
         if (this.getUploadingFiles().length === 0 && this.getQueuedFiles().length === 0) {
            //_task_append_html(response.taskHtml);
                this.removeAllFiles();
                reloadInspectionsAttachments();
         }
      }
   }));

</script>
</body>
</html>
