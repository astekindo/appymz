<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('header');
?>
<script type="text/javascript" charset="utf-8">
    $(document).ready( function () {
        var oTable = $('#the_table').dataTable( {
            "bProcessing": true,
            "sAjaxSource": "<?=base_url();?>ctrl_member/getData"
        });
    });

</script>
<p>
<a href="<?=base_url()?>input_member" class="btn btn btn-primary">New Data</a>
</p>
<table cellpadding="0" cellspacing="0" border="0" class="table table-striped table-bordered" id="the_table"> 
    <thead>
    <tr>
        <th>ID</th>
        <th>Nama&nbsp;Member</th>
        <th>Jenis&nbsp;Member</th>
        <th>Alamat</th>
        <th>Kota</th>
        <th>Kode&nbsp;Pos</th>
        <th>Telepon</th>
        <th>Email</th>
	 <th>Action</th>
    </tr>
    </thead> 
    <tbody> 
    </tbody> 
</table> 

<?php $this->load->view('footer');?>