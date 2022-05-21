<?php defined('BASEPATH') or exit('No direct script access allowed');?>

<div class="form-group">
    <label for="nama_pesawat">Nama pesawat</label>
    <input type="value" class="form-control" id="nama_pesawat" value="<?php echo $equipment->nama_pesawat; ?>" disabled>

    <label for="nomor_seri">Nomor seri</label>
    <input type="value" class="form-control" id="nomor_seri" value="<?php echo $equipment->nomor_seri; ?>" disabled>

    <label for="nomor_unit">Nomor unit</label>
    <input type="value" class="form-control" id="nomor_unit" value="<?php echo $equipment->nomor_unit; ?>" disabled>
</div>