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
        <legend><h2>Input Member</h2></legend>
        <input type="hidden" name="kdmember" id="kdmember" value="<?=$kdmember?>"/>
        <input type="hidden" name="status_data" id="status_data" value="<?=$status_data?>"/>
        <p>
            Tgl Join : <input type="text" name="tgljoin" id="tgljoin" value="<?=$tgljoin?>" class="required" /> &nbsp; &nbsp;
            No/ID Pelanggan * : <input type="text" disabled style="width:60px;text-align:right;" name="kode_member" value="<?=$kode_member?>"/> &nbsp; &nbsp;
            Berlaku s/d * : <input type="text" name="sdtgl" id="sdtgl" value="<?=$sdtgl?>" class="required" /> &nbsp; &nbsp;
        </p>
        <p>Jenis Member : <?= form_dropdown('jenis', array("-"=>"--Pilih--","G"=>"GOLD","P"=>"PRATINUM","S"=>"SILVER"),$jenis,"style='width:100px;'");?></p>
        <p>Nama * : <input type="text" id="nmmember" name="nmmember" value="<?=$nmmember?>" class="required" /></p>
        <p>Jenis Kelamin *  : <?= form_dropdown('kelamin', array("-"=>"--Pilih--","L"=>"Laki Laki","P"=>"Perempuan"),$kelamin,"style='width:100px;'"); ?></p>
        <p>Tempat; Tgl Lahir * : <input type="text" id="tmplahir" name="tmplahir" value="<?=$tmplahir?>" class="required" />&nbsp; &nbsp;
            <input type="text" name="tgllahir" id="tgllahir" value="<?=$tgllahir?>" class="required" /></p>
        <p>Agama * : <?= form_dropdown('agama', array("-"=>"--Pilih--","Islam"=>"Islam","Kristen"=>"Kristen","Hindu"=>"Hindu","Budha"=>"Budha"),$agama,"style='width:100px;'");?> &nbsp; &nbsp;
           No Identitas : <input type="text" id="idno" name="idno" value="<?=str_replace(" ","",$idno)?>" class="required number" /></p>
        <p>Alamat Rumah * : <textarea id="altr" name="altr" class="required" rows="4" style="width:500px;"><?=$altr?></textarea></p>
        <p>Kelurahan * : <input type="text" id="kelurahan" name="kelurahan" value="<?=$kelurahan?>" class="required" />&nbsp; &nbsp;
           Kecamatan/Daerah : <input type="text" id="kecamatan" name="kecamatan" value="<?=$kecamatan?>" class="" />
        </p>
        <p>Kota/Kabupaten : <input type="text" id="kota" name="kota" value="<?=$kota?>" class="" />&nbsp; &nbsp;
           Kode Pos : <input type="text" id="kodepos" name="kodepos" value="<?=str_replace(" ","",$kodepos)?>" class="required number" />
        </p>
        <p>Telepon Rumah : <input type="text" id="telepon" name="telepon" value="<?=str_replace(" ","",$telepon)?>" class="required number" />&nbsp; &nbsp;
           Hp : <input type="text" id="hp" name="hp" value="<?=str_replace(" ","",$hp)?>" class="required number"  />
        </p>
        <p>Fax : <input type="text" id="fax" name="fax" value="<?=str_replace(" ","",$fax)?>" class="required number"  />&nbsp; &nbsp;
           Email : <input type="text" id="email" name="email" value="<?=$email?>" class="required email" />
        </p>
        <p>Profesi : <input type="text" id="profesi" name="profesi" value="<?=$profesi?>" class=""/></p>
        <p>Nama Perusahaan : <input type="text" id="nmpersh" name="nmpersh" value="<?=$nmpersh?>" class=""/></p>
        <p>Alamat Kantor : <textarea id="altk" name="altk" rows="4" style="width:500px;"><?=$altk?></textarea></p>
        <p>Telepon Kantor : <input type="text" id="teleponk" name="teleponk" value="<?=$teleponk?>" class=""/>&nbsp; &nbsp;
            Fax Kantor : <input type="text" id="faxk" name="faxk" value="<?=$faxk?>" class=""/></p>        
        <p>Status (Harga Jual) : <?= form_dropdown('status', array("-"=>"--Pilih--","S"=>"Supermarket","D"=>"Distribusi"),$status,"style='width:100px;'"); ?></p>
        <p class="submit"><input type="submit" name="simpan" id="simpan" value="Simpan" />
        <input type="button" name="Kembali" id="Kembali" value="Kembali" onclick="parent.location='<?=base_url();?>member'" /></p>
    </fieldset>
</form>

<?php $this->load->view('footer');?>