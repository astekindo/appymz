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
		url : "<?php echo base_url(); ?>purchaserequest/addsession",
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
					 url: "<?php echo base_url(); ?>purchaserequest/delcart", 
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

<!--<form action="<?=base_url();?>kategori4/save" id="fkategori" method="post" class="form colours">-->
<fieldset>
<div class="wrapper">
	<div class="fluid">
		<div class="widget grid8">
			<div class="whead"><h6>Input Purchase Request</h6><div class="clear"></div></div>
			<label for="id_pr"><input type="hidden" name="id_pr" value="<?=$id_pr?>"></label>
			<?php $atr = array('name' => 'fkategori', 'id' => 'fkategori'); echo form_open('purchaserequest/savepr',$atr); ?>
                    <div class="formRow">
                        <div class="grid3"><label>NO PR</label></div>
                        <div class="grid9">
                            <span class="grid6"><input type="text" id="no_pr" name="no_pr" value="<?=$no_pr?>" style="width:120px;"  class="required" readonly = "true"/></span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>Member</label></div>
                        <div class="grid9">
                            <span class="grid6">
                            <span class="grid6">
                                <?= form_dropdown('kd_member', $listnama_member, $kd_member); ?>
                                <input type="text" id="nmmember" name="nmmember" value="" style="width:300px;" /></span>
                            </span>
                        </div>
                        <div class="clear"></div>
                    </div>
                    <div class="formRow">
                        <div class="grid3"><label>status</label></div>
                        <div class="grid9">
                            <span class="grid6">
                            <span class="grid6">
                                <?= form_dropdown('status', $listnama_status, $kd_member); ?>
                            </span>
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
                    <div class="whead"><h6>LIST PRODUK</h6><div class="clear"></div>
						<input type="submit" style="float: right;margin-top:-33px;margin-right:165px;" name="simpan" id="simpan" value="Simpan Transaksi" class="buttonM bBlue" />
                        <a href="<?php echo base_url(); ?>purchaserequest/daftar_produk" class="cblsbarang" />
						<input type="button" style="float: right;margin-top:-33px;margin-right:85px;" name="addcart" id="addcart" value="Tambah" class="buttonM bBlue" />
						</a>
						<input type="button" style="float: right;margin-top:-33px;margin-right:5px;" name="Kembali" id="Kembali" value="Kembali" class="buttonM bBlue" onclick="parent.location='<?=base_url();?>penjualan_barang'" />
                    </div>
                    <div id="dyn" class="hiddenpars">
					<?php echo form_close(); ?>

					<?php echo form_open('purchaserequest/update_cart'); ?>
                        
                        <table cellpadding="0" cellspacing="0" border="0" class="dTable">
                            <thead>
                                <tr>
                                    <th>NO</th>
                                    <th>KODE PRODUK</th>
                                    <th>NAMA PRODUK</th>
                                    <th>QTY</th>
                                    <th>SATUAN</th>
                                    <th>ACTION</th>
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
									<td class="td-keranjang" align="right"><?php echo number_format($items['qty']); ?></td>
									<td class="td-keranjang" align="center"><?php echo $items['satuan']; ?></td>
									<!--<td><a href="#" title="Delete" class="tablectrl_small bDefault tipS" onClick="confirmationDel('<? echo  $items['rowid']; ?>','delcart');"><span class="iconb" data-icon="&#xe136;"></a></td>-->
									<td class="td-keranjang" align="center"><a href="#" title="Delete" class="tablectrl_small bDefault tipS" id="<?php echo $items['rowid'].'/'.$no_pr.'/'.$items['id']; ?>" >
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