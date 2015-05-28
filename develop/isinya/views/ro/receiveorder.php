<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('header');
?>
<script type="text/javascript">
      $(document).ready(function(){
        $("#fkategori").validate();
      });

	  $(document).ready(function() {
		$("#subject").change(function(){
		var subject = {subject:$("#subject").val()};
		$.ajax({
		type: "POST",
		url : "<?php echo base_url(); ?>receiveorder/addsession",
		data: subject,
		success: function(callback){
		$('#sessionsubject').html(callback);
		}
		});
		});
		});

		$(document).ready(function() {
			$(".tablectrl_small").click(function(){
			 var element = $(this);
			 var del_id = element.attr("id");
			 var info = del_id;
			 if(confirm("Anda yakin akan menghapus?"))
			 {
					 $.ajax({
					 url: "<?php echo base_url(); ?>receiveorder/delcart", 
					 data: "kode="+info,
					 cache: false, 
					 success: function(){
					 }
				 });	
				$(this).parents(".content").animate({ opacity: "hide" }, "slow");
				}
			 return false;
			 });
		})
		
      $(document).ready(function(){
        $("#frm").validate();
        $("#tgltrans").datepicker();
      });
		
</script>

<script type="text/javascript"> 
  $(document).ready(function() {
	$("#no_po").change(function(){
			var no_po = $("#no_po").val(); 
			$.ajax({ 
			url: "<?php echo base_url(); ?>receiveorder/ambil_po",
			data: "no_po="+no_po, 
			cache: false, 
			success: function(msg){ 
			$("#show_po").html(msg);
			document.frm.simpan.disabled=false;
		} 
	})
	})
	});

    function isNumberKey(evt)
    {
        var charCode = (evt.which) ? evt.which : event.keyCode
        if (charCode > 31 && (charCode < 48 || charCode > 57))
            return false;
		       
        return true;
    }

</script>

<!--<form action="<?=base_url();?>kategori4/save" id="fkategori" method="post" class="form colours">-->
<fieldset>
<div class="wrapper">
	<div class="fluid">
		<div class="widget">
			<?php $atr = array('name' => 'frm', 'id' => 'frm'); echo form_open('receiveorder/savero',$atr); ?>
			<div class="whead"><h6>&nbsp;Input Receive Order</h6><div class="clear"></div></div>
			<label for="id_ro"><input type="hidden" name="id_ro" value="<?=$id_ro?>"></label>
                    <div class="formRow">
                        <div class="grid3"><label>CARI NO PO</label></div>
                        <div class="grid9">
                            <span class="grid6 searchDrop">
                             <select data-placeholder="Pilih No PO..." class="select" style="width:350px;" tabindex="2" name="no_pocari" id="no_pocari" onChange="javascript:window.location.href='<?php echo base_url(); ?>receiveorder/form/'+this.value">
							<option value=""></option> 
								<?php
									foreach($tt_po->result_array() as $db)
									{
								?>
									<option value="<?php echo $db['no_po']; ?>"><?php echo $db['no_po']; ?></option>
								<?php
									}
								?>
							 </select>
							 </span>
						 </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>NO RO</label></div>
                        <div class="grid9">
                            <span class="grid6"><input type="text" id="no_ro" name="no_ro" value="<?=$no_ro?>" style="width:120px;"  class="required" readonly = "true"/></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Tanggal</label></div>
                        <div class="grid9">
                            <span class="grid6"><input type="text" id="tanggal" name="tanggal" value="<?=$tanggal?>" style="width:120px;"  class="required" readonly = "true"/></span>
                        </div>
                        <div class="clear"></div>
                    </div>
					<div class="formRow">
						<div class="grid3"><label>NO PO</label></div>
						<div class="grid9">
							<span class="grid6"><input type="text" name="no_po" value="<?=$no_po?>" style="width:120px;" readonly="true" /></span>
						</div>
						<div class="clear"></div>
					</div>
					<div class="formRow">
						<div class="grid3"><label>NO PR</label></div>
						<div class="grid9">
							<span class="grid6"><input type="text" name="no_pr" value="<?=$no_pr?>" style="width:120px;" readonly="true" /></span>
						</div>
						<div class="clear"></div>
					</div>
					<div class="formRow">
						<div class="grid3"><label>KD SUPPLIER</label></div>
						<div class="grid9">
							<span class="grid6"><input type="text" name="kd_supplier" value="<?=$kd_supplier?>" style="width:50px;"  class="required" readonly = "true"/><input type="text" name="nama_supplier" value="<?=$nama_supplier?>" style="width:200px;"  class="required" readonly = "true"/></span>
						</div>
						<div class="clear"></div>
					</div>
					<div class="formRow">
				<div class="wrapper">
					<div class="widget">
						<div class="whead"><h6>LIST PRODUK</h6><div class="clear"></div></div>
								<table cellpadding="0" cellspacing="0" border="0" width="100%" class="tDefault">
									<thead>
										<tr>
											<th>NO</th>
											<th>KODE PRODUK</th>
											<th>NAMA PRODUK</th>
											<th>QTY ORDER</th>
											<th>QTY TERIMA</th>
											<th>LOKASI</th>
											<th>KETERANGAN</th>
											<!--<th>ACTION</th>-->
										</tr>
									</thead> 
									<tbody> 
										<?php $i = 1; $no=1;?>
										<?php foreach($this->cart->contents() as $items): ?>
										<?php echo form_hidden('rowid[]', $items['rowid']); ?>
											<tr class="content">
											<td class="td-keranjang" align="center"><?php echo $no; ?></td>
											<td class="td-keranjang" align="center"><?php echo $items['id']; ?><input type="hidden" name="kd_produk[]" value="<?php echo $items['id']; ?>" /></td>
											<td class="td-keranjang" align="left"><?php echo $items['namap']; ?></td>
											<td class="td-keranjang" align="right"><?php echo number_format($items['qty']); ?><input type="hidden" name="qty_beli[]" value="<?php echo $items['qty']; ?>" /></td>
											<td class="td-keranjang" align="right">
											<input type="text" onKeyPress="return isNumberKey(event)" value="<?php echo number_format($items['qty']); ?>" class="input-read-only" style="width:50px;text-align:right;" name="qty_terima[]" />
											</td>
											<td class="td-keranjang" align="center">
											<span class="searchDrop">
											 <select data-placeholder="Pilih No Lokasi..." class="select" style="width:200px;text-align:left;" name="kodelokasi[]">
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
											<td class="td-keranjang" align="center">
											<input type="text" value="<?php echo $items['keterangan']; ?>" class="input-read-only" style="width:150px;align:left;" name="keterangan[]" />
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
					</div>
				<div class="formRow">
					<div class="grid3"></div>
					<div class="grid9">
					<input type="hidden" name="totbaris" value="<?php echo intval($no)-1; ?>" style="width:0px;"/>
					<input type="submit" name="simpan" id="simpan" value="Simpan Transaksi" class="buttonM bBlue" />
					<input type="button" name="Kembali" id="Kembali" value="Kembali" class="buttonM bBlue" onclick="parent.location='<?=base_url();?>receiveorder'" />
					 </div>
					<div class="clear"></div>
				</div>
			<?php echo form_close(); ?>
		</div>
	</div>
</div>
</fieldset>


<?php $this->load->view('footer');?>