<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('header');
?>
<script type="text/javascript">
      $(document).ready(function(){
        $("#fReturOrder").validate();
      });

	  $(document).ready(function() {
		
		$("#no_ro").change(function(){
			var no_ro = $("#no_ro").val(); 
			$.ajax({ 
			url: "<?php echo base_url(); ?>retur_order/ambil_ro",
			data: "no_ro="+no_ro, 
			cache: false, 
			success: function(msg){ 
				$("#show_ro").html(msg);
					document.frm.simpan.disabled=false;
				} 
			})
		})
			$("#kd_supplier").change(function(){
			var kd_supplier = {kd_supplier:$("#kd_supplier").val()};
			$.ajax({
			type: "POST",
			url : "<?php echo base_url(); ?>retur_order/addsession",
			data: kd_supplier,
			success: function(callback){
			$('#sessionkd_supplier_retur').html(callback);
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
					 url: "<?php echo base_url(); ?>retur_order/delcart", 
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
		
		
</script>

<!--<form action="<?=base_url();?>kategori4/save" id="fReturOrder" method="post" class="form colours">-->
<fieldset>
<div class="wrapper">
	<div class="fluid">
		<div class="widget">
			<div class="whead"><h6>Input Retur Order</h6><div class="clear"></div></div>
			<label for="id_retur"><input type="hidden" name="id_retur" value="<?=$id_retur?>"></label>
			<?php $atr = array('name' => 'fReturOrder', 'id' => 'fReturOrder'); echo form_open('retur_order/saveretur',$atr); ?>
                    <div class="formRow">
                        <div class="grid3"><label>Cari No RO</label></div>
                        <div class="grid9">
                            <span class="grid6 searchDrop">
                             <select data-placeholder="Pilih No RO..." class="select" style="width:350px;" tabindex="2" name="no_rocari" id="no_rocari" onChange="javascript:window.location.href='<?php echo base_url(); ?>retur_order/form/'+this.value">
							<option value=""></option> 
								<?php
									foreach($tt_ro->result_array() as $db)
									{
								?>
									<option value="<?php echo $db['no_ro']; ?>"><?php echo $db['no_ro']; ?></option>
								<?php
									}
								?>
							 </select>
							 </span>
						 </div>
                        <div class="clear"></div>
                    </div>
					<div class="formRow">
                        <div class="grid3"><label>No Retur</label></div>
                        <div class="grid9">
                            <span class="grid6"><input type="text" id="no_retur" name="no_retur" value="<?=$no_retur?>" style="width:120px;"  class="required" readonly = "true"/></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Tanggal</label></div>
                        <div class="grid9">
                            <span class="grid6"><input type="text" id="tgltrans" name="tgltrans" value="<?=$tgltrans?>" style="width:100px;" class="required" readonly="TRUE" /></span>
                        </div>
                        <div class="clear"></div>
                    </div>
					<div class="formRow">
						<div class="grid3"><label>NO RO</label></div>
						<div class="grid9">
							<span class="grid6"><input type="text" name="no_ro" value="<?=$no_ro?>" style="width:120px;" readonly="true" /></span>
						</div>
						<div class="clear"></div>
					</div>
					<div class="formRow">
						<div class="grid3"><label>Nama Supplier</label></div>
						<div class="grid9">
							<span class="grid6"><input type="text" name="kd_supplier" value="<?=$kd_supplier?>" style="width:50px;"  class="required" readonly = "true"/><input type="text" name="nama_supplier" value="<?=$nama_supplier?>" style="width:200px;"  class="required" readonly = "true"/></span>
						</div>
						<div class="clear"></div>
					</div>
					<div class="clear"></div>
					
			<div class="formRow">
                    <div class="whead"><h6>LIST PRODUK</h6><div class="clear"></div>
						<input type="submit" style="float: right;margin-top:-33px;margin-right:165px;" name="simpan" id="simpan" value="Simpan Transaksi" class="buttonM bRed" />
                        <a href="<?php echo base_url(); ?>retur_order/daftar_produk_retur" class="cblsbarang" />
						<input type="button" style="float: right;margin-top:-33px;margin-right:85px;" name="addcart" id="addcart" value="Tambah" class="buttonM bRed" />
						</a>
						<input type="button" style="float: right;margin-top:-33px;margin-right:5px;" name="Kembali" id="Kembali" value="Kembali" class="buttonM bRed" onclick="parent.location='<?=base_url();?>retur_order'" />
                    </div>
                    <div id="dyn" class="hiddenpars">
					<?php echo form_close(); ?>

					<?php echo form_open('retur_order/update_cart'); ?>
                        
                        <table cellpadding="0" cellspacing="0" border="0" class="dTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Produk</th>
                                    <th>Nama Produk</th>
									<th>Kode Lokasi</th>
									<th>Nama Lokasi</th>
                                    <th>Qty Retur</th>
                                    <th>Satuan</th>
									<th>Disk % 1</th>
									<th>Disk % 2</th>
									<th>Disk % 3</th>
									<th>Disk % 4</th>
									<th>Disk 1</th>
									<th>Disk 2</th>
									<th>Disk 3</th>
									<th>Disk 4</th>
									<th>Harga Supplier</th>
									<th>DPP</th>
                                    <th>Action</th>
                                </tr>
                            </thead> 
                            <tbody> 
								<?php $i = 1; $no=1;?>
								<?php foreach($this->cart->contents() as $items): ?>
								<?php echo form_hidden('rowid[]', $items['rowid']); ?>
									<tr class="content">
									<td class="td-keranjang" align="center"><?php echo $no; ?></td>
									<td class="td-keranjang" align="center"><?php echo $items['id']; ?></td>
									<td class="td-keranjang" align="left"><?php echo $items['namap']; ?></td>
									<td class="td-keranjang" align="left"><?php echo $items['kode_lokasi']; ?></td>
									<td class="td-keranjang" align="left"><?php echo $items['nama_lokasi']; ?></td>
									<td class="td-keranjang" align="right"><?php echo number_format($items['qty']); ?></td>
									<td class="td-keranjang" align="center"><?php echo $items['satuan']; ?></td>
									<td class="td-keranjang" align="center"><?php echo $items['disk_persen_supp1']; ?></td>
									<td class="td-keranjang" align="center"><?php echo $items['disk_persen_supp2']; ?></td>
									<td class="td-keranjang" align="center"><?php echo $items['disk_persen_supp3']; ?></td>
									<td class="td-keranjang" align="center"><?php echo $items['disk_persen_supp4']; ?></td>
									<td class="td-keranjang" align="center"><?php echo $items['disk_amt_supp1']; ?></td>
									<td class="td-keranjang" align="center"><?php echo $items['disk_amt_supp2']; ?></td>
									<td class="td-keranjang" align="center"><?php echo $items['disk_amt_supp3']; ?></td>
									<td class="td-keranjang" align="center"><?php echo $items['disk_amt_supp4']; ?></td>
									<td class="td-keranjang" align="center"><?php echo $items['hrg_supplier']; ?></td>
									<td class="td-keranjang" align="center"></td>
									<td class="td-keranjang" align="center"><a href="#" title="Delete" class="tablectrl_small bDefault tipS" id="<?php echo $items['rowid'].'/'.$no_retur.'/'.$items['id']; ?>" >
									<span class="iconb" data-icon="&#xe136;"></a>
									</td>
								</tr>
								<?php $i++; $no++;?>
								<?php endforeach; ?>
							</tbody> 
                        </table>
						<?php echo form_close(); ?>
                    </div>
                    <div class="clear"></div>
				</div>			
		</div>
</div>
</div>
</fieldset>


<?php $this->load->view('footer');?>