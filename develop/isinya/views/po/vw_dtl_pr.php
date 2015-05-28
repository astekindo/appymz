<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('header');
?>
<script type="text/javascript">
      $(document).ready(function(){
        $("#frm").validate();
      });

		
		
		
</script>

<fieldset>
<div class="wrapper">
	<div class="fluid">
		<div class="widget ">
			<?php $atr = array('name' => 'frm', 'id' => 'frm'); echo form_open('purchase_order/createpo',$atr); ?>
			<div class="whead"><h6>Create Purchase Order</h6><div class="clear"></div></div>
				<div class="formRow">
					<div class="grid3"><label>NO PR</label></div>
					<div class="grid9">
						<span class="grid6"><input type="text" id="no_pr" name="no_pr" value="<?=$no_pr?>" style="width:120px;"  class="required" readonly = "true"/></span>
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid3"><label>Subject</label></div>
					<div class="grid9">
						<span class="grid6">
						<input type="text" name="subject" value="<?=$subject?>" style="width:350px;" class="required" readonly="TRUE"  /></span>
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid3"><label>Tanggal</label></div>
					<div class="grid9">
						<span class="grid6"><input type="text" name="tgltrans" value="<?=$tgltrans?>" style="width:100px;" class="required" readonly="TRUE" /></span>
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid3"><label>Masa Berlaku</label></div>
					<div class="grid9 onlyNums">
						<span class="grid6"><input type="text" id="masa_berlaku" name="masa_berlaku" value="<?=$masa_berlaku?>" style="width:50px;" class="required" /> hari</span>
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="wrapper">
						<div class="widget">
							<div class="whead"><h6>List Produk</h6><div class="clear"></div></div>
								<table cellpadding="0" cellspacing="0" border="0" width="100%" class="tDefault">
									<thead>
										<tr>
											<th>No</th>
											<th>Kode Produk</th>
											<th>Nama Produk</th>
											<th>Qty</th>
											<th>Satuan</th>
											<th>Supplier</th>
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
											<td class="td-keranjang" align="center"><?php echo $items['satuan']; ?></td>
											<td class="td-keranjang" align="left">
											<span class="searchDrop">
												<select data-placeholder="Pilih Supplier..." class="select" style="width:200px;text-align:left;" name="kode_supplier[]">
													<option value="-"></option> 
												<?php
													foreach($this->purchase_order_models->getSuppData($items['id'])->result_array() as $ds)
													{
												?>
													<option value="<?php echo $ds['kd_supplier']; ?>"><?php echo $ds['nama_supplier']; ?></option>
												<?php	
													}
												?>
												</select>
											</span>
											</td>
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
						<input type="submit" name="simpan" id="simpan" value="Create PO" class="buttonM bRed" />
						<input type="button" name="Kembali" id="Kembali" value="Kembali" class="buttonM bRed" onclick="parent.location='<?=base_url();?>purchase_order'" />
					</div>
					<div class="clear"></div>
				</div>
				<?php echo form_close(); ?>
		</div>
	</div>
</div>
</fieldset>


<?php $this->load->view('footer');?>