<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('header');
?>
<script>
      $(document).ready(function(){
        $("#fPembayaran").validate();
      });
</script>

<div class="wrapper">
	<form action="<?=base_url();?>pembayaran/save" id="fSatuan" method="post" class="main">
	    <fieldset>
			<div class="fluid">
				<div class="widget grid6">
					<div class="whead"><h6>Input Jenis Pembayaran</h6><div class="clear"></div></div>
					<div class="formRow">
						<div class="grid3"><label>Nama Pembayaran:</label></div>
		                <div class="grid9 noSearch">
							<input type="hidden" name="id_pembayaran" id="id_pembayaran" value="<?=$id_pembayaran?>" />
							<input type="text" id="nm_pembayaran" name="nm_pembayaran" value="<?=$nm_pembayaran?>" class="required" />
						</div>
		                <div class="clear"></div>
					</div>
					<div class="formRow">
						<div class="grid3"><label>Charge (%):</label></div>
		                <div class="grid9">
							<input type="text" id="charge" name="charge" value="<?=$charge?>" class="required number" />
						</div>
		                <div class="clear"></div>
					</div>
					<div class="formRow">
						<div class="grid3"><label>Jenis:</label></div>
		                <div class="grid9 noSearch">
							<?php
				               // menampilkan dropdown 
				               $options = array(
								  'Cash'  => 'Cash',
				                  'Debit Card'    => 'Debit card',
								  'Giro'    => 'Giro',
								  'Credit card'    => 'Credit card',
								  'Transfer'    => 'Transfer',
				                );

								echo form_dropdown('jenis', $options, $jenis, 'class="select"');
				            ?>
						</div>
		                <div class="clear"></div>
					</div>
					<div class="formRow">
						<div class="grid3"><label>Status Aktif:</label></div>
		                <div class="grid9 noSearch">
							<?php
				               // menampilkan dropdown 
				               $options = array(
				                  'Ya'  => 'Ya',
				                  'Tidak'    => 'Tidak',
				                );

								echo form_dropdown('status_aktif', $options, $status_aktif, 'class="select"');
				            ?>
						</div>
		                <div class="clear"></div>
					</div>
					<div class="formRow">
						<input type="submit" name="simpan" id="simpan" value="Simpan" class="buttonL bRed" />
						<input type="button" name="Kembali" id="Kembali" value="Kembali" onclick="parent.location='<?=base_url();?>blok'" class="buttonL bRed" />
					</div>
				</div>
			</div>
		</fieldset>
	</form>
</div>

<?php $this->load->view('footer');?>