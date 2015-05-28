<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('header');
?>
<script>
      $(document).ready(function(){
        $("#fMenu").validate();
      });
    </script>
<form action="<?=base_url();?>input_menu/save" id="fMenu" method="post" class="form colours">
    <fieldset>
        <legend><h2>Input Menu</h2></legend>
        <input type="hidden" name="dId" id="dId" value="<?=$vId?>">
        <p><label for="title">Nama Menu:</label><input type="text" id="dTitle" name="dTitle" value="<?=$vTitle?>" class="required" /></p>
        <p><label for="index_menu">Index Menu:</label><input type="text" id="dIndex" name="dIndex" value="<?=$vIndex?>" class="required number" /></p>
        <p><label for="parent">Parent:</label>
            <?= form_dropdown('dParent', $listparent, $vParent); ?>
        </p>
        <p><label for="controller">Controller:</label><input type="text" id="dController" name="dController" value="<?=$vController?>" class="required" /></p>
        <p><label for="description">Description:</label>
            <textarea id="dDescription" name="dDescription"><?=$vDescription?></textarea>
        </p>
        <p class="submit"><input type="submit" name="simpan" id="simpan" value="Simpan" class="buttonL bGreyish" /> &nbsp;&nbsp;
        <input type="button" name="Kembali" id="Kembali" value="Kembali" onclick="parent.location='<?=base_url();?>menu'"  class="buttonL bGreyish"/></p>
    </fieldset>
</form>

<?php $this->load->view('footer');?>