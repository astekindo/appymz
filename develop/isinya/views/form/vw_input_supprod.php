<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('header');
?>
<script>
      $(document).ready(function(){
        $("#fSuppprod").validate();
		
		$("#kd_supplier").change(function(){
			var kd_supplier = $("#kd_supplier").val(); ;
			$.ajax({
				url : "<?php echo base_url(); ?>supp_prod/ambil_data_supplier_ajax",
				data: "kd_supplier="+kd_supplier,
				cache: false,
				success: function(msg){
					$('#data_supplier').html(msg);
				}
			});
		});
		$('#data_supplier').load('<?php echo base_url(); ?>supp_prod/ambil_data_supplier_session');
		
		$(".tablectrl_small").click(function(){
			 var element = $(this);
			 var del_id = element.attr("id");
			 var info = del_id;
			 if(confirm("Anda yakin akan menghapus?"))
			 {
					 $.ajax({
					 url: "<?php echo base_url(); ?>supprod/delcart", 
					 data: "kode="+info,
					 cache: false, 
					 success: function(){
					 }
				 });	
				$(this).parents(".content").animate({ opacity: "hide" }, "slow");
				}
			 return false;
			 });
	});	
    </script>
    
<fieldset>
<div class="wrapper">
	<div class="fluid">
		<div class="widget">
			<div class="whead"><h6>Input Supplier Per Barang</h6><div class="clear"></div></div>
			<?php $atr = array('name' => 'fSuppprod', 'id' => 'fSuppprod'); echo form_open('supp_prod/savesuppprod',$atr); ?>
			<div class="formRow">
			<h6> Data Supplier</h6>
				<div class="formRow">
					<div class="grid3"><label>Nama Supplier:</label></div>
					<div class="grid9">
						<select name="kd_supplier" id="kd_supplier">
							<option value="">- Pilih Supplier -</option>
							<?php
								foreach($listsupplier->result_array() as $ds)
								{
								$pilih='';
								if($ds['kd_supplier']==$kd_supplier)
								{
								$pilih='selected="selected"';
							?>
								<option value="<?php echo $ds['kd_supplier']; ?>" <?php echo $pilih; ?>><?php echo $ds['nama_supplier']; ?></option>
							<?php
								}
							else
								{
							?>
								<option value="<?php echo $ds['kd_supplier']; ?>"><?php echo $ds['nama_supplier']; ?></option>
							<?php
								}
								}
							?>
						</select>
					</div>
					<div class="clear"></div>
				</div>
				
					<div id="data_supplier"></div>
				<div class="formRow">
                    <div class="whead"><h6>List Produk</h6><div class="clear"></div>
						<input type="submit" style="float: right;margin-top:-33px;margin-right:165px;" name="simpan" id="simpan" value="Simpan Data" class="buttonM bRed" />
                        &nbsp;<a href="<?php echo base_url(); ?>supp_prod/list_produk" class="cblsbarang" />
						<input type="button" style="float: right;margin-top:-33px;margin-right:85px;" name="addcart" id="addcart" value="Tambah" class="buttonM bRed" />
						</a>
						<input type="button" style="float: right;margin-top:-33px;margin-right:5px;" name="Kembali" id="Kembali" value="Kembali" class="buttonM bRed" onclick="parent.location='<?=base_url();?>supp_prod'" />
                    </div>
                    <div id="dyn" class="hiddenpars">
					<?php echo form_close(); ?>

					<?php echo form_open('supp_prod/update_cart'); ?>
                        
                        <table cellpadding="0" cellspacing="0" border="0" class="dTable">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Kode Produk</th>
                                    <th>Nama Produk</th>
                                    <th>Disk % 1</th>
                                    <th>Disk % 2</th>
									<th>Disk % 3</th>
									<th>Disk % 4</th>
									<th>Disk amt 1</th>
                                    <th>Disk amt 2</th>
									<th>Disk amt 3</th>
									<th>Disk amt 4</th>
									<th>TOP</th>
									<th>Konsinyasi</th>
									<th>Harga</th>
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
									<td class="td-keranjang" align="left"><?php echo $items['name']; ?></td>
								<?php 
								/*if ( $items['disk_amt_supp1']=='0' || $items['disk_amt_supp1']=='0' || $items['disk_amt_supp1']=='0' || $items['disk_amt_supp1']=='0') 
								{*/
								?>
									<td class="td-keranjang" align="right"><?php echo $items['disk_persen_supp1']; ?> %</td>
									<td class="td-keranjang" align="right"><?php echo $items['disk_persen_supp2']; ?> %</td>
									<td class="td-keranjang" align="right"><?php echo $items['disk_persen_supp3']; ?> %</td>
									<td class="td-keranjang" align="right"><?php echo $items['disk_persen_supp4']; ?> %</td>
								<?php
								/*} else if ( $items['disk_persen_supp1']=='0' || $items['disk_persen_supp2']=='0' || $items['disk_persen_supp3']=='0' || $items['disk_persen_supp4']=='0') 
								{*/
								?>
									<td class="td-keranjang" align="right"><?php echo $items['disk_amt_supp1']; ?></td>
									<td class="td-keranjang" align="right"><?php echo $items['disk_amt_supp2']; ?></td>
									<td class="td-keranjang" align="right"><?php echo $items['disk_amt_supp3']; ?></td>
									<td class="td-keranjang" align="right"><?php echo $items['disk_amt_supp4']; ?></td>
								<?php
								//}
								?>
									<td class="td-keranjang" align="right"><?php echo $items['waktu_top']; ?></td>
									<td class="td-keranjang" align="right"><?php echo $items['konsinyasi']; ?></td>
									<td class="td-keranjang" align="right"><?php echo $items['hrg_supplier']; ?></td>
									<td class="td-keranjang" align="right"><?php echo $items['dpp']; ?></td>
									<!--<td><a href="#" title="Delete" class="tablectrl_small bDefault tipS" onClick="confirmationDel('<? echo  $items['rowid']; ?>','delcart');"><span class="iconb" data-icon="&#xe136;"></a></td>-->
									<td class="td-keranjang" align="center"><a href="#" title="Delete" class="tablectrl_small bDefault tipS" id="<?php echo $items['rowid'].'/'.$kd_supplier.'/'.$items['id']; ?>">
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
</div>
</fieldset>
<?php $this->load->view('footer');?>