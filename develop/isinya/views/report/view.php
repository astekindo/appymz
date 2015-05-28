<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>

<?php $this->load->view('header'); ?>

<script type="text/javascript">
	jQuery(document).ready(function() 
	{
		$('[name=subject]').change(function()
		{
			$('#form_search').submit();
			return false;	
		});		
	});
</script>

<fieldset>
<div class="wrapper">
	<div class="fluid">
		<div class="widget">
			<div class="whead">
				<h6>Report</h6>
				<div class="clear"></div>
			</div>
            <?php $this->load->view('report/rpt_sbj_form', array('listSubject'=>$listSubject)); ?>
            <div class="clear"></div>
            <div class="whead">
            	<h6>List Data</h6>
            	<div class="clear"></div>
            </div>
            <div class="formRow">
            	<?php if($is_not_selected == true){ ?>
            	<table cellpadding="0" cellspacing="0" border="0" class="dTable">
                	<thead>
                    	<tr>
	                        <th>field1</th>
	                        <th>field2</th>
	                        <th>field3</th>
	                        <th>field4</th>
	                        <th>field5</th>
	                        <th>field6</th>
	                    </tr>
                	</thead> 
                	<tbody> 
					</tbody> 
                </table>
                <?php }else{ ?>

                <?php } ?>
            </div>
            <div class="formRow">
            	<?php echo form_open('index.php/report/test', '', ''); ?>
            	<span><?php echo form_submit('print', 'Print', 'class="buttonM bBlue"'); ?></span>
            	<?php echo form_close(); ?>
            	<div class="clear"></div>
            </div>
		</div>
	</div>
</div>
</fieldset>

<?php $this->load->view('footer');?>