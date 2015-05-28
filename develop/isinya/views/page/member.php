<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('header');
?>
<script type="text/javascript" charset="utf-8">
    $(document).ready( function () {
        var oTable = $('#the_table').dataTable( {
            "bProcessing": true,
            "sAjaxSource": "<?=base_url();?>member/getData"
        });
    });

</script>
<div class="wrapper">
	<div class="widget">
		<div class="whead"><h6>Tabel Member</h6><div class="clear"></div></div>
            <div class="shownpars">
                <a href="<?=base_url()?>member/form" class="tOptions" title="Add New"><img src="<?=base_url();?>images/icons/middlenav/create.png" title="Add New" /></a>
				<table cellpadding="0" cellspacing="0" border="0" class="dTable">
				<thead>
				<tr>
					<th>No</th>
					<th>Kode Member</th>
					<th>Nama Member</th>
					<th>Jenis Member</th>
					<th>Alamat</th>
					<th>Telpon</th>
					<th>Hp</th>
					<th>Email</th>
					<th>Action</th>
				</tr>
				</thead> 
				<tbody> 
					<?=$rcmember;?>
				</tbody> 
			</table> 
		</div>
	</div>
</div>

<?php $this->load->view('footer');?>