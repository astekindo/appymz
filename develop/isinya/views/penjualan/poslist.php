<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('header');
?>
<div class="wrapper">
    <div class="widget">
        <div class="whead"><h6>List Purchase Request</h6><div class="clear"></div></div>
            <div class="shownpars">
                <a href="<?=base_url()?>penjualan_barang/form" class="tOptions" title="Add New"><img src="<?=base_url();?>images/icons/middlenav/create.png" title="Add New" /></a>
                <table cellpadding="0" cellspacing="0" border="0" class="dTable">
                <thead>
                    <tr>
                        <th><b>NO.</b></th>
                        <th><b>NO POS.</b></th>
                        <th><b>NAMA MEMBER</b></th>
                        <th><b>CREATE BY</b></th>
                        <th><b>DATE</b></th>
                        <th><b>STATUS</b></th>
                        <th><b>ACTION</b></th>
                    </tr>
                    </thead> 
                    <tbody> 
                        <?=$rcpurchaserequest;?>
                    </tbody> 
                </table> 
        </div>
    </div>
</div>

<?php $this->load->view('footer');?>