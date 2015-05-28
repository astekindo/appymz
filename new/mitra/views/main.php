<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');

$this->load->view('header');
$this->load->view('master/master');
$this->load->view('pembelian/pembelian');
$this->load->view('konsinyasi/konsinyasi');
$this->load->view('penjualan/penjualan');
$this->load->view('inventori/inventori');
$this->load->view('account/account');
$this->load->view('admin/admin');
$this->load->view('report/report');
?>
<script type="text/javascript">
    Ext.onReady(function(){

        Ext.override(Ext.Container, {
            doRemove: Ext.Container.prototype.doRemove.createSequence(function(c, autoDestroy){
                if (!(autoDestroy === true || (autoDestroy !== false && this.autoDestroy))){
                    this.getLayoutTarget().dom.removeChild(c.getPositionEl().dom);
                }
            })
        });

        Ext.QuickTips.init();
        Ext.BLANK_IMAGE_URL = BASE_PATH + 'assets/js/ext/resources/images/default/s.gif';
        Ext.form.Field.prototype.msgTarget = 'side';

<?php $status_user = intval($this->session->userdata('user_peruntukan'));
switch( $status_user ) {
    case 0:
        $status_user = 'SUPERMARKET';break;
    case 1:
        $status_user = 'DISTRIBUSI';break;
    case 2:
        $status_user = 'ALL';break;
    default:
        $status_user = 'SUPERMARKET';break;
}
$this->load->view('profile') ?>

                        var start = {
                            id: 'start-panel',
                            layout: 'fit',
                            bodyStyle: 'padding:25px',
                            border: false,
                            contentEl: 'start-div' // pull existing content from the page
                        };

                        var tbCenter = new Ext.Toolbar({
                            items: [{
                                    id: 'x-title-content',
                                    xtype: 'tbtext',
                                    text: 'Dashboard',
                                    style:{
                                        'color': 'red',
                                        'font-weight' : 'bold',
                                        'font-size':'12px',
                                        'text-transform':'uppercase'
                                    }
                                },'->', {
                                    html: 'Welcome <b><?= strtoupper($username) .' at '. $this->session->userdata('nama_cabang') ?></b>, you are login as a <b><?= strtoupper($usergroup). ' with status '.$status_user ?></b>'
                                }, '-', {
                                    icon: BASE_PATH + 'assets/icons/key.png',
                                    text: 'Change Password',
                                    handler: function(){
                                        winProfile.show();
                                    }
                                }, '-', {
                                    icon: BASE_PATH + 'assets/icons/minus-circle.png',
                                    text: 'Logout',
                                    handler: doLogout
                                }]
                        });


                        function doLogout(){
                            Ext.Msg.show({
                                title: 'Konfirmasi',
                                msg: '<?= $this->lang->line("confirm_logout") ?>',
                                buttons: Ext.Msg.YESNO,
                                fn: function(btn){
                                    if (btn == 'yes') {
                                        Ext.Ajax.request({
                                            url: '<?= site_url("auth/logout") ?>',
                                            method: 'POST',
                                            success: function(xhr){
                                                window.location = '<?= site_url("auth/login") ?>';
                                            }
                                        });
                                    }
                                }
                            });

                        }

                        /* layout center - modules tab
        var tab_center = new Ext.Panel({
            id: 'tab_modules',
            border: false,
                        layout: 'fit',
                        bodyStyle: 'background-color:#DFE8F6;',
                        autoScroll: true,
            items: [{
                xtype: 'panel',
                id: 'tab_welcome',
                bodyStyle: 'padding:5px',
                autoScroll: true,
                items: start
            }]
        });
                         */
                        var layout_center = new Ext.Panel({
                            id: 'tab_modules',
                            region: 'center',
                            layout: 'fit',
                            margins: '2 5 5 0',
                            autoScroll: true,
                            border: true,
                            split: false,
                            tbar: tbCenter
                        });


<?php
$accmenu = '';
if ($accordion_menu) {
    foreach ($accordion_menu as $obj) {
        $accmenu .= 'tree' . $obj->menu_id . ',';
        ?>
                                var tree<?= $obj->menu_id ?> = new Ext.tree.TreePanel({
                                    id: 'tree-panel-<?= $obj->menu_id ?>',
                                    title: '<?= $obj->menu_text ?>',
                                    region: 'center',
                                    split: true,
                                    border:false,
                                    height: 400,
                                    minSize: 150,
                                    autoScroll: true,
                                    rootVisible: false,
                                    lines: true,
                                    expanded: true,
                                    iconCls: 'icon-tree',
                                    loader: new Ext.tree.TreeLoader({
                                        dataUrl: '<?= site_url("main/all_menu/$obj->kd_menu") ?>'
                                    }),
                                    root: new Ext.tree.AsyncTreeNode()
                                });

                                tree<?= $obj->menu_id ?>.on('click', function(n){
        <?php $this->load->view('storedb') ?>
                                var sn = this.selModel.selNode || {}; // selNode is null on initial selection
                                if (n.leaf && n.id != sn.id) { // ignore clicks on folders and currently selected node
                                    var TabPanel = Ext.getCmp('tab_modules');
                                    var modId = Ext.getCmp(n.id);
                                    TabPanel.removeAll(false);
                                    Ext.getCmp('x-title-content').setText(n.attributes.text);
                                    TabPanel.items.add(modId).show();
                                    TabPanel.doLayout();
                                }
                            });
        <?php
    }
}
?>
                        var accmenu = [<?= $accmenu ?>];

                        // Layout Main Page
                        var main = new Ext.Viewport({
                            id:'main_layout_browser',
                            layout: 'border',
                            items: [{
                                    region: 'north',
                                    //autoHeight: true,
                                    height: 70,
                                    border: false,
                                    html: '<div id="header" style="background-color:#386cba;"><div class="head-col-l"><img src="<?= base_url() ?>assets/img/logo.png" height="60"/></div><div class="head-col-r"><div class="version"><p style="color:#fff;"><i>MITRA BANGUNAN SUPERMARKET</i></p></div></div><div class="clear"></div></div>',
                                    margins: '0 0 5 0',
                                    style: 'border-bottom: 4px solid #CC0000;'
                                }, {
                                    layout: 'accordion',
                                    id: 'x-menuutama',
                                    region: 'west',
                                    border: true,
                                    header: true,
                                    title: 'MENU UTAMA',
                                    split: true,
                                    iconCls: 'icon-main-menu',
                                    margins: '2 0 5 5',
                                    width: 275,
                                    minSize: 100,
                                    maxSize: 500,
                                    collapseMode: 'mini',
                                    items: accmenu
                                }, layout_center],
                            renderTo: Ext.getBody()
                        });

                        var TabPanel = Ext.getCmp('tab_modules');
                        var modId = Ext.getCmp('minimumstok');
                        TabPanel.removeAll(false);
                        Ext.getCmp('x-title-content').setText('ALERT');
                        TabPanel.items.add(modId).show();
                        TabPanel.doLayout();
                        strminimumstok.load();
                        strnotificationpo.load();
                        strnotificationinvoice.load();
                        storenotificationhargajual.load();
                        storelistapprovalhargajual.load();
                        strNotifLokasiDefault.load();
                    });
</script>
</head>
<body>
    <div style="display:none;">
        <!-- Start Dashboard -->
        <div id="start-div"></div>
    </div>
</body>
</html>
