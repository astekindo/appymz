<?php if (!defined('BASEPATH')) exit('No direct script access allowed');
$this->load->view('header');
?>
<div class="wrapper">
    <div class="widget">
        <div class="whead"><h6>List Receive Order</h6><div class="clear"></div></div>
            <div class="shownpars">
                <!--<a href="<?=base_url()?>receiveorder/form" class="tOptions" title="Add New"><img src="<?=base_url();?>images/icons/middlenav/create.png" title="Add New" /></a>-->
                <table cellpadding="0" cellspacing="0" border="0" class="dTable">
                <thead>
                    <tr>
                        <th><b>NO.</b></th>
                        <th><b>NO RO.</b></th>
                        <th><b>NO PO.</b></th>
                        <th><b>NO PR.</b></th>
                        <th><b>KODE SUPPLIER</b></th>
                        <th><b>CREATE INVOICE</b></th>
                    </tr>
                    </thead> 
                    <tbody> 
                        <?=$rcreceiveorder;?>
                    </tbody> 
                </table> 
        </div>
    </div>
</div>

<?php $this->load->view('footer');?>