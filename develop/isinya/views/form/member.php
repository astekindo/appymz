<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('header');
?>
<script>
      $(document).ready(function(){
        $("#fMember").validate();
        $("#tgljoin").datepicker();
        $("#sdtgl").datepicker();
        $("#tgllahir").datepicker();
      });
    </script>

<form action="<?=base_url();?>member/save" id="fMember" method="post" class="form colours">
<fieldset>
<div class="wrapper">
	<div class="fluid">
		<div class="widget">
			<div class="whead"><h6 id="grid">Input Member</h6><div class="clear"></div></div>
				<input type="hidden" name="id_member" id="id_member" value="<?=$id_member?>"/>
				<div class="formRow fluid">
					<div class="grid9">
						<span class="grid3">Kode Member : &nbsp;&nbsp;&nbsp;<input type="text" style="width:60px;text-align:right;" name="kd_member" value="<?=$kd_member?>" readonly/></span>
						<span class="grid3">Tanggal Join : &nbsp;&nbsp;&nbsp;<input type="text" name="tgljoin" id="tgljoin" value="<?=$tgljoin?>" style="width:100px;text-align:left;" class="required" /></span>
						<span class="grid3">Berlaku s/d * : &nbsp;&nbsp;&nbsp;<input type="text" name="sdtgl" id="sdtgl" style="width:100px;text-align:left;" value="<?=$sdtgl?>" class="required" /></span>
						<span class="grid3">Jenis Member : <?= form_dropdown('jenis', array("-"=>"--Pilih--","P"=>"PLATINUM","G"=>"GOLD","S"=>"SILVER"),$jenis,"style='width:100px;'");?></span>
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow fluid">
					<div class="grid9">
						<span class="grid4">Nama * : &nbsp;&nbsp;&nbsp;<input type="text" id="nmmember" name="nmmember" value="<?=$nmmember?>" class="required" style="width:200px;text-align:left;"/></span>
						<span class="grid4">Jenis Kelamin *  : &nbsp;&nbsp;&nbsp;<?= form_dropdown('kelamin', array("-"=>"--Pilih--","L"=>"Laki Laki","P"=>"Perempuan"),$kelamin,"style='width:100px;'"); ?></span>
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow fluid">
					<div class="grid9">
						<span class="grid4">Tempat Lahir * : &nbsp;&nbsp;&nbsp;<input type="text" id="tmplahir" name="tmplahir" value="<?=$tmplahir?>" class="required" style="width:160px;text-align:left;"/></span>
						<span class="grid4">Tanggal Lahir * : &nbsp;&nbsp;&nbsp;<input type="text" name="tgllahir" id="tgllahir" value="<?=$tgllahir?>" class="required" style="width:100px;text-align:left;"/></span>
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow fluid">
					<div class="grid9">
						<span class="grid4">Agama * : &nbsp;&nbsp;&nbsp;<?= form_dropdown('agama', array("-"=>"--Pilih--","Islam"=>"Islam","Kristen"=>"Kristen","Hindu"=>"Hindu","Budha"=>"Budha"),$agama,"style='width:120px;'");?></span>
						<span class="grid4">No Identitas : &nbsp;&nbsp;&nbsp;<input type="text" id="idno" name="idno" value="<?=str_replace(" ","",$idno)?>" class="required number" style="width:150px;text-align:left;"/></span>
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid2"><label>Alamat Rumah * :</label></div>
					<div class="grid4"><textarea id="alamat_rumah" name="alamat_rumah" class="required" rows="4" style="width:500px;"><?=$alamat_rumah?></textarea></div>
					<div class="clear"></div>
				</div>
				<div class="formRow fluid">
					<div class="grid9">
						<span class="grid3">Kelurahan * : <input type="text" id="kelurahan" name="kelurahan" value="<?=$kelurahan?>" class="required" /></span>
						<span class="grid3">Kecamatan/Daerah : <input type="text" id="kecamatan" name="kecamatan" value="<?=$kecamatan?>" class="" /></span>
						<span class="grid3">Kota/Kabupaten : <input type="text" id="kota" name="kota" value="<?=$kota?>" class="" /></span>
						<span class="grid3">Kode Pos : <input type="text" id="kodepos" name="kodepos" value="<?=str_replace(" ","",$kodepos)?>" class="required number" /></span>
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow fluid">
					<div class="grid9">
						<span class="grid3">Telepon Rumah : <input type="text" id="telepon" name="telepon" value="<?=str_replace(" ","",$telepon)?>" class="required number" /></span>
						<span class="grid3">Hp : <input type="text" id="hp" name="hp" value="<?=str_replace(" ","",$hp)?>" class="required number"  /></span>
						<span class="grid3">Fax : <input type="text" id="fax" name="fax" value="<?=str_replace(" ","",$fax)?>" class="required number"  /></span>
						<span class="grid3">Email : <input type="text" id="email" name="email" value="<?=$email?>" class="required email" /></span>
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow fluid">
					<div class="grid9">
						<span class="grid4">Profesi : &nbsp;&nbsp;&nbsp;<input type="text" id="profesi" name="profesi" value="<?=$profesi?>" style="width:200px;" class=""/></span>
						<span class="grid8">Nama Perusahaan : &nbsp;&nbsp;&nbsp;<input type="text" id="nmpersh" name="nmpersh" value="<?=$nmpersh?>" style="width:200px;" class=""/></span>
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow">
					<div class="grid2"><label>Alamat Kantor : </label></div>
					<div class="grid4"><textarea id="alamat_kantor" name="alamat_kantor" rows="4" style="width:500px;"><?=$alamat_kantor?></textarea></div>
					<div class="clear"></div>
				</div>
				<div class="formRow fluid">
					<div class="grid9">
						<span class="grid6">Telepon Kantor : &nbsp;&nbsp;&nbsp;<input type="text" id="teleponk" name="teleponk" value="<?=$teleponk?>" style="width:200px;" class=""/></span>
						<span class="grid4">Fax Kantor : &nbsp;&nbsp;&nbsp;<input type="text" id="faxk" name="faxk" value="<?=$faxk?>" style="width:200px;" class=""/></span>
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow fluid">
					<div class="grid9">
						<span class="grid4">Status (Harga Jual) : &nbsp;&nbsp;&nbsp;<?= form_dropdown('status', array("-"=>"--Pilih--","S"=>"Supermarket","D"=>"Distribusi"),$status,"style='width:100px;'"); ?></span>
					</div>
					<div class="clear"></div>
				</div>
				<div class="formRow fluid">
					<div class="grid9">
						<span class="grid4"><p class="submit"><input type="submit" name="simpan" id="simpan" value="Simpan" class="buttonM bBlue" />
						<input type="button" name="Kembali" id="Kembali" value="Kembali" class="buttonM bBlue" onclick="parent.location='<?=base_url();?>member'" /></p>
						</span>
					</div>
					<div class="clear"></div>
				</div>
			</div>
		</div>
	</div>
</fieldset>
</form>

<?php $this->load->view('footer');?>