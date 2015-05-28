<?php $this->load->helper('form'); ?>

<?php echo form_open('index.php/report/view', array('id' => 'form_search')); ?>

<div class="formRow">
	<div class="grid1"><?php echo form_label('Subject', 'subject'); ?></div>
	<div class="grid7">
		<span class="grid6"><?php echo form_dropdown('subject', $listSubject, 'default'); ?></span>
	</div>
	<div class="clear"></div>
</div>

<?php echo form_close(); ?>