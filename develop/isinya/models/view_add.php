<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="utf-8">
<title>List Dropdown Add</title>

<script src="<?=base_url();?>public/jquery-1.7.2.min.js" type="text/javascript"></script>
<script type="text/javascript">
$(document).ready(function() {
$("#provinsi").change(function(){
var provinsi = {provinsi:$("#provinsi").val()};
$.ajax({
type: "POST",
url : "<?php echo base_url(); ?>grab_kota",
data: provinsi,
success: function(callback){
$('#kota').html(callback);
}
});
});
});
</script>
</head>
<body>
<h2 align="center">Add Data User</h2>
<br/>
<?=form_open()?>

<label for="nama" style="margin-left: 30%; float: left; width: 200px;">Nama User:</label>
<?php
$data_nama = array('placeholder' => 'Masukkan Nama', 'name' => 'nama', 'id' => 'nama');
echo form_input($data_nama);
?>
<br/><br/>
<label for="provinsi" style="margin-left: 30%; float: left; width: 200px;">Pilih Provinsi:</label>
<?php
echo form_dropdown('provinsi', $list_provinsi, '', 'id = "provinsi"');
?>
<br/><br/>
<label for="kota" style="margin-left: 30%; float: left; width: 200px;">Pilih Kabupaten/Kota:</label>
<?php
echo form_dropdown('kota', $list_kota, '', 'id = "kota"');
?>
<br/><br/>
<div style="margin-left: 50%">
<?php
$reset = array('name' => 'Reset', 'value' => 'Reset', 'class' => 'button');
$submit = array('name' => 'Submit', 'value' => 'Tambah', 'class' => 'button');
?>
<?=form_reset($reset);?> <?=form_submit($submit);?>
</div>

<?=form_close()?>
<br/><br/><hr/>
<div align="center">Page rendered in <b>{elapsed_time}</b> seconds</div>
</body>
</html>