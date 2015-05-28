<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('header');
?>
<script type="text/javascript">

      $(document).ready(function(){
        $("#fmutasi").validate();
      });

	  $(document).ready(function() {
		$("#keterangan").change(function(){
		var keterangan = {keterangan:$("#keterangan").val()};
		$.ajax({
		type: "POST",
		url : "<?php echo base_url(); ?>mutasibarang/addsessionketerangan",
		data: keterangan,
		success: function(callback){
		$('#sessionketerangan').html(callback);
		}
		});
		});
		});
	  
	  $(document).ready(function() {
		$("#kd_lokasi").change(function(){
		var kd_lokasi = {kd_lokasi:$("#kd_lokasi").val()};
		$.ajax({
		type: "POST",
		url : "<?php echo base_url(); ?>mutasibarang/grab_blok",
		data: kd_lokasi,
		success: function(callback){
		$('#kd_blok').html(callback);
		}
		});
		});
		});

		$(document).ready(function() {
		$("#kd_blok").change(function(){
		var kd_blok = {kd_blok:$("#kd_blok").val()};
		$.ajax({
		type: "POST",
		url : "<?php echo base_url(); ?>mutasibarang/grab_sub_blok",
		data: kd_blok,
		success: function(callback){
		$('#kd_subblok').html(callback);
		}
		});
		});
		});
		
</script>

<form action="<?=base_url();?>mutasibarang/save" id="fkategori" method="post" class="form colours">
<fieldset>
<div class="wrapper">
	<div class="fluid">
		<div class="widget">
			<div class="whead"><h6>Input Mutasi Barang</h6><div class="clear"></div></div>
			<div class="formRow"><input type="hidden" name="id_mutasi" value="<?=$id_mutasi?>">
                    <div class="formRow">
                        <div class="grid3"><label>No Mutasi :</label></div>
                        <div class="grid9">
                            <span class="grid6"><input type="text" id="no_ms" name="no_ms" value="<?=$no_ms?>" style="width:120px;"  class="required" readonly = "true"/></span>
                        </div>
                        <div class="clear"></div>
                    </div>
<!--                    <div class="formRow">
                        <div class="grid3"><label>Lokasi :</label></div>
                        <div class="grid9">
							<span class="searchDrop">
								 <select data-placeholder="Pilih No Lokasi..." class="select" style="width:200px;text-align:left;" name="lokasi">
								<option value=""></option> 
									<?php
										foreach($listlokasi->result_array() as $db)
										{
									?>
										<option value="<?php echo $db['kode']; ?>"><?php echo $db['nama_lokasi']; ?></option>
									<?php
										}
									?>
								 </select>
							</span>
                        </div>
                        <div class="clear"></div>
                    </div>-->
<!--                    <div class="formRow">
                        <div class="grid3"><label>Blok :</label></div>
                        <div class="grid9">
                            <span class="grid6"><?= form_dropdown('kd_blok', $listblok, $kd_blok, 'id = "kd_blok"'); ?></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Sub Blok :</label></div>
                        <div class="grid9">
                            <span class="grid6"><?= form_dropdown('kd_subblok', $listsubblok, $kd_subblok, 'id = "kd_subblok"'); ?></span>
                        </div>
                        <div class="clear"></div>
                    </div> -->
                   <div class="formRow">
                        <div class="grid3"><label>Keterangan	:</label></div>
                        <div class="grid9"><span class="grid6"><input type="text" id="keterangan" name="keterangan" value="<?=$keterangan?>" style="width:250px;"  class="required" /></span>
                        </div>
                        <div class="clear"></div>
                    </div>
<!-- Tabel Temp -->
				<div class="wrapper">
					<div class="widget">
						<div class="whead"><h6>LIST PRODUK</h6><div class="clear"></div>
                        <a href="<?php echo base_url(); ?>mutasibarang/daftar_produk" class="cblsprodukpr" />
						<input type="button" style="float: right;margin-top:-33px;margin-right:5px;" name="tambah" id="tambah" value="Tambah" class="buttonM bBlue" />
						</a>
						</div>
								<table cellpadding="0" cellspacing="0" border="0" width="100%" class="tDefault">
									<thead>
										<tr>
											<th>NO</th>
											<th>KODE</th>
											<th>NAMA</th>
											<th>LOKASI LAMA</th>
											<th>QTY OH</th>
											<th>LOKASI TUJUAN</th>
											<th>QTY MUTASI</th>
										</tr>
									</thead> 
									<tbody> 
										<?php $i = 1; $no=1;?>
										<?php foreach($this->cart->contents() as $items): ?>
										<?php echo form_hidden('rowid[]', $items['rowid']); ?>
											<tr class="content">
											<td class="td-keranjang" align="center"><?php echo $no; ?></td>
											<td class="td-keranjang" align="center"><?php echo $items['kdproduk']; ?><input type="hidden" name="kd_produk[]" value="<?php echo $items['kdproduk']; ?>" /></td>
											<td class="td-keranjang" align="left"><?php echo $items['namap']; ?></td>
											<td class="td-keranjang" align="left"><?php echo $items['lokasilama']; ?><input type="hidden" name="lokasilama[]" value="<?php echo $items['kodelokasilama']; ?>" /></td>
											<td class="td-keranjang" align="right"><?php echo number_format($items['qtyoh']); ?>
											<input type="hidden" name="qty_oh[]" value="<?php echo number_format($items['qtyoh']); ?>" /></td>
											<td class="td-keranjang" align="center">
											<input type="hidden" name="kapasitas[]" value="<?php echo $items['kapasitas']; ?>" />
											<span class="searchDrop">
											 <select data-placeholder="Pilih No Lokasi..." class="select" style="width:200px;text-align:left;" name="lokasitujuan[]">
											<option value="-"></option> 
												<?php
													foreach($listlokasi->result_array() as $db)
													{
												?>
													<option value="<?php echo $db['kode']; ?>"><?php echo $db['nama_lokasi']; ?></option>
												<?php
													}
												?>
											 </select>
											 </span>
											</td>
											<td class="td-keranjang" align="right">
											<input type="text" onKeyPress="return isNumberKey(event)" value="<?php echo number_format($items['qtymutasi']); ?>" class="input-read-only" style="width:50px;text-align:right;" name="qty_mutasi[]" />
											</td>
										<!--	<td align="center">
												<a href="<?php echo base_url(); ?>receiveorder/pilihlokasi" class="cblsbarang" />
												<input type="button" name="lokasi" id="lokasi" value="Lokasi" class="buttonM bBlue" />
												</a>
											</td>-->
										</tr>
										<?php $i++; $no++;?>
										<?php endforeach; ?>
									</tbody> 
						</table>
					</div>
				</div>
				<div class="formRow">
					<div class="grid3"></div>
					<div class="grid9">
					<p class="submit"><input type="submit" name="simpan" id="simpan" value="Simpan" class="buttonM bBlue" />
					<input type="button" name="Kembali" id="Kembali" value="Kembali" class="buttonM bBlue" onclick="parent.location='<?=base_url();?>mutasibarang'" /></p>
					 </div>
					<div class="clear"></div>
				</div>

					</div>
			</div>
		</div>
</div>
</fieldset>
</form>

<?php $this->load->view('footer');?>