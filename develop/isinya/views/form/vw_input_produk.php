<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('header');
?>
<script>
      $(document).ready(function(){
        $("#fBarang").validate();
			
		$("#kd_kategori1").change(function(){
			var kd_kategori1 = {kd_kategori1:$("#kd_kategori1").val()};
			$.ajax({
				type: "POST",
				url : "<?php echo base_url(); ?>produk/grab_kategori2",
				data: kd_kategori1,
				success: function(callback){
					$('#kd_kategori2').html(callback);
				}
			});
		});
		
		$("#kd_kategori2").change(function(){
			var kd_kategori2 = {kd_kategori2:$("#kd_kategori2").val()};
			
			$.ajax({
				type: "POST",
				url : "<?php echo base_url(); ?>produk/grab_kategori3",
				data: kd_kategori2,
				success: function(callback){
					$('#kd_kategori3').html(callback);
				}
			});
		});
		
		$("#kd_kategori3").change(function(){
			var kd_kategori3 = {kd_kategori3:$("#kd_kategori3").val()};
			
			$.ajax({
				type: "POST",
				url : "<?php echo base_url(); ?>produk/grab_kategori4",
				data: kd_kategori3,
				success: function(callback){
					$('#kd_kategori4').html(callback);
				}
			});
		});
		
		$("#kd_kategori4").change(function(){
			var kd_kategori4 = {kd_kategori4:$("#kd_kategori4").val()};
			//var dataKategori = 'kd_kategori1='+ kd_kategori1 + '&kd_kategori2=' + kd_kategori2 + '&kd_kategori3=' + kd_kategori3 + '&kd_kategori4=' + kd_kategori4;
			$.ajax({
				type: "POST",
				url : "<?php echo base_url(); ?>produk/get_product_code",
				data: kd_kategori4,
				success: function(data){
					//alert(data);
					$('#kd_produk').val(data);
					//$('.kd_produk').html(data) 
				}
			});
		});
		
      });
    </script>
	
<form action="<?=base_url();?>produk/save" id="fSupplier" method="post" class="form colours">
	<fieldset>
		<div class="wrapper">
			<div class="fluid">
				<div class="widget grid6">
					<div class="whead"><h6>Input Produk</h6><div class="clear"></div></div>
					<div class="formRow">
						<div class="grid3"><label>Kategori 1</label></div>
						<div class="grid9">
							<?
								if ( !empty($id_produk)) {
							?>
							<input type="hidden" id="kd_kategori1" name="kd_kategori1" value="<?=$kd_kategori1?>" />
							<input type="text" id="nama_kategori1" name="nama_kategori1" value="<?=$nama_kategori1?>" readonly="readonly" />
							<?
								} else {
							?>
							<span class="grid6"><?= form_dropdown('kd_kategori1', $listkategori1, $kd_kategori1, 'id="kd_kategori1"'); ?></span>
							<? } ?>
							<input type="hidden" name="id_produk" id="id_produk" value="<?=$id_produk?>" />
						</div>
						<div class="clear"></div>
					</div>
					<div class="formRow">
						<div class="grid3"><label>Kategori 2 </label></div>
						<div class="grid9">
							<?
								if ( !empty($id_produk)) {
							?>
							<input type="hidden" id="kd_kategori2" name="kd_kategori2" value="<?=$kd_kategori2?>" />
							<input type="text" id="nama_kategori2" name="nama_kategori2" value="<?=$nama_kategori2?>" readonly="readonly" />
							<?
								} else {
							?>
							<span class="grid6"><?= form_dropdown('kd_kategori2', $listkategori2, $kd_kategori2, 'id="kd_kategori2"'); ?></span>
							<? } ?>
						</div>
						<div class="clear"></div>
					</div>
					<div class="formRow">
						<div class="grid3"><label>Kategori 3 </label></div>
						<div class="grid9">
							<?
								if ( !empty($id_produk)) {
							?>
							<input type="hidden" id="kd_kategori3" name="kd_kategori3" value="<?=$kd_kategori3?>" />
							<input type="text" id="nama_kategori2" name="nama_kategori2" value="<?=$nama_kategori2?>" readonly="readonly" />
							<?
								} else {
							?>
							<span class="grid6"><?= form_dropdown('kd_kategori3', $listkategori3, $kd_kategori3, 'id="kd_kategori3"'); ?></span>
							<? } ?>
						</div>
						<div class="clear"></div>
					</div>
					<div class="formRow">
						<div class="grid3"><label>Kategori 4</label></div>
						<div class="grid9">
							<?
								if ( !empty($id_produk)) {
							?>
							<input type="hidden" id="kd_kategori4" name="kd_kategori4" value="<?=$kd_kategori4?>" />
							<input type="text" id="nama_kategori4" name="nama_kategori4" value="<?=$nama_kategori4?>" readonly="readonly" />
							<?
								} else {
							?>
							<span class="grid6"><?= form_dropdown('kd_kategori4', $listkategori4, $kd_kategori4, 'id="kd_kategori4"'); ?></span>
							<? } ?>
							
							<input type="hidden" name="thn_reg" id="thn_reg" value="<?=$thn_reg?>" />
							<input type="hidden" name="no_urut" id="no_urut" value="<?=$no_urut?>" />
						</div>
						<div class="clear"></div>
					</div>
					<div class="formRow">
						<div class="grid3"><label>Nama Produk</label></div>
						<div class="grid9">
							<input type="text" id="nama_produk" name="nama_produk" value="<?=$nama_produk?>" class="required" />
						</div>
						<div class="clear"></div>
					</div>
					<div class="formRow">
						<div class="grid3"><label>Kode Produk</label></div>
						<div class="grid9">
							<input type="text" id="kd_produk" name="kd_produk" value="<?=$kd_produk?>" class="required" readonly="readonly"/>
						</div>
						<div class="clear"></div>
					</div>
					<div class="formRow">
						<div class="grid3"><label>Kode Produk Lama</label></div>
						<div class="grid9">
							<input type="text" id="kd_produk_lama" name="kd_produk_lama" value="<?=$kd_produk_lama?>" />
						</div>
						<div class="clear"></div>
					</div>
					<div class="formRow">
						<div class="grid3"><label>Kode Produk Supplier</label></div>
						<div class="grid9">
							<input type="text" id="kd_produk_supp" name="kd_produk_supp" value="<?=$kd_produk_supp?>" />
						</div>
						<div class="clear"></div>
					</div>
					<div class="formRow">
						<div class="grid3"><label>Satuan </label></div>
						<div class="grid9">
							<span class="grid6"><?= form_dropdown('id_satuan', $listsatuan, $id_satuan, 'id = "id_satuan"'); ?></span>
						</div>
						<div class="clear"></div>
					</div>
					<div class="formRow">
						<div class="grid3"><label>Kode Peruntukkan</label></div>
						<div class="grid9 on_off">
								<div class="floatL mr10"><input type="checkbox" id="kd_peruntukkan" <?=$kd_peruntukkan?> name="kd_peruntukkan" /></div>
							</div>
						<div class="clear"></div>
					</div>
					<div class="formRow">
						<div class="grid2"><label>Jml Masuk</label></div>
						<div class="grid4 onlyNums">
							<input type="text" id="qty_in" name="qty_in" value="<?=$qty_in?>" style="width:50px;" />
						</div>
						<div class="grid2"><label>Jml Keluar</label></div>
						<div class="grid4 onlyNums">
							<input type="text" id="qty_out" name="qty_out" value="<?=$qty_out?>" style="width:50px;" />
						</div>
						<div class="clear"></div>
					</div>
					<div class="formRow">
						<div class="grid2"><label>Jml Tersedia</label></div>
						<div class="grid4 onlyNums">
							<input type="text" id="qty_oh" name="qty_oh" value="<?=$qty_oh?>" style="width:50px;" />
						</div>
						<div class="grid2"><label>Jml DO</label></div>
						<div class="grid4 onlyNums">
							<input type="text" id="qty_do" name="qty_do" value="<?=$qty_do?>" style="width:50px;" />
						</div>
						<div class="clear"></div>
					</div>
					<div class="formRow">
						<div class="grid2"><label>Jml Siap Jual</label></div>
						<div class="grid4 onlyNums">
							<input type="text" id="qty_siap_jual" name="qty_siap_jual" value="<?=$qty_siap_jual?>" style="width:50px;" />
						</div>
						<div class="grid2"><label>Minimal Order</label></div>
						<div class="grid4 onlyNums">
							<input type="text" id="min_order" name="min_order" value="<?=$min_order?>" style="width:50px;" />
						</div>
						<div class="clear"></div>
					</div>
					<div class="formRow">
						<div class="grid2"><label>Stok Minimal</label></div>
						<div class="grid4 onlyNums">
							<input type="text" id="min_stok" name="min_stok" value="<?=$min_stok?>" style="width:50px;" />
						</div>
						<div class="grid2"><label>Stok Maksimal</label></div>
						<div class="grid4 onlyNums">
							<input type="text" id="max_stok" name="max_stok" value="<?=$max_stok?>" style="width:50px;" />
						</div>
						<div class="clear"></div>
					</div>
				</div>
				
				<div class="widget grid6">
					<div class="whead"><h6>Input Harga & Diskon</h6><div class="clear"></div></div>
					<div class="formRow">
						<div class="grid3"><label>Harga Pokok</label></div>
						<div class="grid9 onlyNums">
							<input type="text" id="hrg_hpp" name="hrg_hpp" value="<?=$hrg_hpp?>" style="width:100px;" />
						</div>
						<div class="clear"></div>
					</div>
					<div class="formRow">
						<div class="grid3"><label>Harga Jual</label></div>
						<div class="grid9 onlyNums">
							<input type="text" id="hrg_jual" name="hrg_jual" value="<?=$hrg_jual?>" style="width:100px;" />
						</div>
						<div class="clear"></div>
					</div>	
					<div class="formRow">
						<div class="grid3"><label>Diskon Konsumen 1 (%)</label></div>
						<div class="grid9 onlyNums">
							<input type="text" id="disk_persen_kons1" name="disk_persen_kons1" value="<?=$disk_persen_kons1?>" style="width:50px;" />
						</div>
						<div class="clear"></div>
					</div>
					<div class="formRow">
						<div class="grid3"><label>Diskon Konsumen 1 (Rp)</label></div>
						<div class="grid9 onlyNums">
							<input type="text" id="disk_amt_kons1" name="disk_amt_kons1" value="<?=$disk_amt_kons1?>" style="width:100px;" />
						</div>
						<div class="clear"></div>
					</div>
					<div class="formRow">
						<div class="grid3"><label>Diskon Konsumen 2 (%)</label></div>
						<div class="grid9 onlyNums">
							<input type="text" id="disk_persen_kons2" name="disk_persen_kons2" value="<?=$disk_persen_kons2?>" style="width:50px;"  />
						</div>
						<div class="clear"></div>
					</div>
					<div class="formRow">
						<div class="grid3"><label>Diskon Konsumen 2 (Rp)</label></div>
						<div class="grid9 onlyNums">
							<input type="text" id="disk_amt_kons2" name="disk_amt_kons2" value="<?=$disk_amt_kons2?>" style="width:100px;" />
						</div>
						<div class="clear"></div>
					</div>
					<div class="formRow">
						<div class="grid3"><label>Diskon Konsumen 3 (%)</label></div>
						<div class="grid9 onlyNums">
							<input type="text" id="disk_persen_kons3" name="disk_persen_kons3" value="<?=$disk_persen_kons3?>" style="width:50px;"  />
						</div>
						<div class="clear"></div>
					</div>
					<div class="formRow">
						<div class="grid3"><label>Diskon Konsumen 3 (Rp)</label></div>
						<div class="grid9 onlyNums">
							<input type="text" id="disk_amt_kons3" name="disk_amt_kons3" value="<?=$disk_amt_kons3?>" style="width:100px;" />
						</div>
						<div class="clear"></div>
					</div>
					<div class="formRow">
						<div class="grid3"><label>Diskon Konsumen 4 (%)</label></div>
						<div class="grid9 onlyNums">
							<input type="text" id="disk_persen_kons4" name="disk_persen_kons4" value="<?=$disk_persen_kons4?>" style="width:50px;"  />
						</div>
						<div class="clear"></div>
					</div>
					<div class="formRow">
						<div class="grid3"><label>Diskon Konsumen 4 (Rp)</label></div>
						<div class="grid9 onlyNums">
							<input type="text" id="disk_amt_kons4" name="disk_amt_kons4" value="<?=$disk_amt_kons4?>" style="width:100px;" />
						</div>
						<div class="clear"></div>
					</div>
				</div>
				<div class="formRow">
				<p class="submit">
				<input type="submit" name="simpan" id="simpan" value="Simpan" class="buttonL bRed" />
				<input type="button" name="Kembali" id="Kembali" value="Kembali" onclick="parent.location='<?=base_url();?>produk'" class="buttonL bRed" />	
				</div>
			</div>
			
		</div>
	</fieldset>
</form>

<?php $this->load->view('footer');?>