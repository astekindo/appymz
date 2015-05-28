<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
    <head>
        <title>
            <?php echo $this->config->item('app_title'); ?>
        </title>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/js/ext/ux/css/ColumnHeaderGroup.css" /> 
        <!-- ** CSS ** --><!-- base library -->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/js/ext/resources/css/ext-all.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/style.css" />
        
        <!-- overrides to base library -->
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/js/ext/ux/css/CenterLayout.css" />
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/js/ext/ux/fileuploadfield/css/fileuploadfield.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/js/ext/ux/css/MultiSelect.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/js/ext/ux/css/superboxselect.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/js/ext/ux/css/RowEditor.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/css/Ext.ux.grid.RowActions.css"/>
        <link rel="stylesheet" type="text/css" href="<?php echo base_url(); ?>assets/js/ext/ux/css/LockingGridView.css" />
        <!-- page specific -->
        <!-- ** Javascript ** -->

        <!-- ExtJS library: base/adapter -->
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/ext/adapter/ext/ext-base.js"></script>
        <!-- ExtJS library: all widgets -->
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/ext/ext-all.js"></script>
        <!-- extensions -->
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/ext/ux/CenterLayout.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/ext/ux/RowLayout.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/ext/ux/CheckColumn.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/ext/ux/Ext.ux.form.XCheckbox.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/ext/ux/RowEditor.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/ext/ux/MultiSelect.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/ext/ux/fileuploadfield/FileUploadField.js"></script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/ext/TabCloseMenu.js"></script>        
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/ext/ux/SuperBoxSelect.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/ext/Ext.ux.grid.RowActions.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/ext/ux/Ext.ux.NumericField.js"></script>
		<script type="text/javascript" src="<?php echo base_url(); ?>assets/js/ext/ux/Ext.ux.grid.Search.js"></script>
                <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/ext/ux/LockingGridView.js"></script>
                <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/ext/ux/treegrid/TreeGridSorter.js"></script>
                <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/ext/ux/treegrid/TreeGridColumnResizer.js"></script>
                <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/ext/ux/treegrid/TreeGridNodeUI.js"></script>
                <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/ext/ux/treegrid/TreeGridLoader.js"></script>
                <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/ext/ux/treegrid/TreeGridColumns.js"></script>
                <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/ext/ux/treegrid/TreeGrid.js"></script>
                <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/ext/ux/GroupSummary.js"></script>
<!--                <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/ext/ux/MonthPicker.js"></script>-->
                <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/ext/ux/ColumnHeaderGroup.js"></script>
                
                
        <script type="text/javascript">
            var STARTPAGE = 0;
            var ENDPAGE = <?= $this->config->item('length_records') ?>;
            var BASE_URL = '<?php echo site_url() . '/'; ?>';
            var BASE_PATH = '<?php echo base_url(); ?>';
            var BASE_ICONS = BASE_PATH + 'assets/icons/';
            function session_expired(err){
                Ext.Msg.show({
                    title: 'Error',
                    msg: err,
                    modal: true,
                    closable: false,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK,
                    fn: function(btn){
                        if (btn == 'ok') {
                            window.location = '<?= site_url("auth/login") ?>';
                        }
                    }
                });
            }			
        </script>
        <script type="text/javascript" src="<?php echo base_url(); ?>assets/js/ext/searchfield.js"></script>
