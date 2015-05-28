<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    // Twin Combo Supplier
    var strcbcrrosuplier = new Ext.data.ArrayStore({
        fields: ['nama_supplier'],
        data: []
    });

    var strgridcrrosuplier = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_supplier', 'nama_supplier'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_receive_order/search_supplier") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    strgridcrrosuplier.on('load', function() {
        Ext.getCmp('id_searchgridcrrosuplier').focus();
    });

    var searchgridcrrosuplier = new Ext.app.SearchField({
        store: strgridcrrosuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridcrrosuplier'
    });

    var gridcrrosuplier = new Ext.grid.GridPanel({
        store: strgridcrrosuplier,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'Kode Supplier',
                dataIndex: 'kd_supplier',
                width: 80,
                sortable: true,
            }, {
                header: 'Nama Supplier',
                dataIndex: 'nama_supplier',
                width: 300,
                sortable: true,
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridcrrosuplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridcrrosuplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('crro_kd_supplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_cbcrrosuplier').setValue(sel[0].get('nama_supplier'));
                    strreturreceiveorderprint.load({
                        params: {
                            kd_supplier: sel[0].get('kd_supplier')
                        }
                    });
                    menucrrosuplier.hide();
                }
            }
        }
    });

    var menucrrosuplier = new Ext.menu.Menu();
    menucrrosuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridcrrosuplier],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menucrrosuplier.hide();
                }
            }]
    }));

    Ext.ux.TwinComboCrroSuplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridcrrosuplier.load();
            menucrrosuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menucrrosuplier.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridcrrosuplier').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgridcrrosuplier').setValue('');
            searchgridcrrosuplier.onTrigger2Click();
        }
    });

    var cbcrrosuplier = new Ext.ux.TwinComboCrroSuplier({
        fieldLabel: 'Supplier',
        id: 'id_cbcrrosuplier',
        store: strcbcrrosuplier,
        mode: 'local',
        valueField: 'nama_supplier',
        displayField: 'nama_supplier',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'nama_supplier',
        emptyText: 'Pilih Supplier'
    });

    //Header Retur Receive Order Print
    var headerreturreceiveorderprint = {
        layout: 'column',
        border: false,
        items: [{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [cbcrrosuplier,{
                        xtype: 'hidden',
                        name: 'kd_supplier',
                        id: 'crro_kd_supplier',
                        value: ''
                    }]
            }]
    }

    // START GRID Retur Receive Order Print
    var strreturreceiveorderprint = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_retur',
                'kd_suplier',
                'nama_supplier',
                'tgl_retur'
                
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("cetak_retur_receive_order/get_rows") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
    strreturreceiveorderprint.on('load', function(){
        Ext.getCmp('idsearch_retur_ro_print').focus();
    });
    // search field
    var search_retur_ro_print = new Ext.app.SearchField({
        store: strreturreceiveorderprint,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        emptyText :'No Retur',
        id: 'idsearch_retur_ro_print'
    });

    // top toolbar
    var tb_retur_beli_print = new Ext.Toolbar({
        items: [search_retur_ro_print]
    });
     search_retur_ro_print.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';
			
            // Get the value of search field
            var fid = Ext.getCmp('crro_kd_supplier').getValue();
            var o = { start: 0, kd_supplier: fid };
			
            this.store.baseParams = this.store.baseParams || {};
            this.store.baseParams[this.paramName] = '';
            this.store.reload({
                params : o
            });
            this.triggers[0].hide();
            this.hasSearch = false;
        }
    };
	
    search_retur_ro_print.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }
	 
        // Get the value of search field
        var fid = Ext.getCmp('crro_kd_supplier').getValue();
        var o = { start: 0, kd_supplier: fid };
	 
        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params:o});
        this.hasSearch = true;
        this.triggers[0].show();
    };
    // checkbox grid
    var smgridcetakreturroprint = new Ext.grid.CheckboxSelectionModel();
    var smgridDetCetakReturRoprint = new Ext.grid.CheckboxSelectionModel();

    // data store
    var strreturreceiveorderprintdetail = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_invoice',
                'qty',
                'nama_produk',
                'kd_produk',
                'net_price',
                'disk_grid_supp1',
                'disk_grid_supp2',
                'disk_grid_supp3',
                'disk_grid_supp4',
                'disk_grid_supp5',
                'harga',
                'rp_diskon',
                'price_supp',
                'rp_jumlah',
                'grand_total',
                'rp_ppn',
                'no_po',
                'no_do'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("cetak_retur_receive_order/get_rows_detail") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    strreturreceiveorderprint.on('load', function() {
        strreturreceiveorderprintdetail.removeAll();
    })

    var gridcetakreturroprint = new Ext.grid.EditorGridPanel({
        id: 'gridcetakreturroprint',
        frame: true,
        border: true,
        stripeRows: true,
        sm: smgridcetakreturroprint,
        store: strreturreceiveorderprint,
        loadMask: true,
        title: 'Retur Receive Order',
        style: 'margin:0 auto;',
        height: 225,
        // width: 550,
        columns: [{
                header: "No Retur",
                dataIndex: 'no_retur',
                // hidden: true,
                sortable: true,
                width: 150
            }, {
                header: "Kode Supplier",
                dataIndex: 'kd_suplier',
                sortable: true,
                width: 150
            }, {
                header: "Nama Supplier",
                dataIndex: 'nama_supplier',
                sortable: true,
                width: 250
            }, {
                header: "Tanggal Retur",
                dataIndex: 'tgl_retur',
                sortable: true,
                width: 80
            }],
        listeners: {
            'rowclick': function() {
                var sm = gridcetakreturroprint.getSelectionModel();
                var sel = sm.getSelections();
                gridDetCetakReturRoprint.store.proxy.conn.url = '<?= site_url("cetak_retur_receive_order/get_rows_detail") ?>/' + sel[0].get('no_retur');
                gridDetCetakReturRoprint.store.reload();
            }
        },
        tbar: [tb_retur_beli_print,{
                icon: BASE_ICONS + 'grid.png',
                text: 'View Retur RO',
                handler: function() {
                    
				var sm = gridcetakreturroprint.getSelectionModel();
				var sel = sm.getSelections();
				
				if (sel.length > 0) {

                                    Ext.Ajax.request({
                                    url: '<?= site_url("cetak_retur_receive_order/get_data_retur_ro") ?>/' + sel[0].get('no_retur'),
                                    method: 'POST',
                                    params: {},
                                    callback: function (opt, success, responseObj) {
                                        var windowviewreturreceiveorder = new Ext.Window({
                                            title: 'View Retur Receive Order',
                                            width: 850,
                                            height: 500,
                                            autoScroll: true,
                                            html: responseObj.responseText
                                        });

                                        windowviewreturreceiveorder.show();

                                    }
                                });
                            }
	
                        }
            }],
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strreturreceiveorderprint,
            displayInfo: true
        })
    });
    // shorthand alias
    var fm = Ext.form;

    var cmm = new Ext.ux.grid.LockingColumnModel({
        // specify any defaults for each column
        defaults: {
            sortable: true // columns are not sortable by default           
        },
        columns: [{
                header: "No PO",
                dataIndex: 'no_po',
                sortable: true,
                width: 100
            },{
                header: "No RO",
                dataIndex: 'no_do',
                sortable: true,
                width: 100
            }, {
                header: "Kode Barang",
                dataIndex: 'kd_produk',
                sortable: true,
                width: 100
            },
            {
                header: "Nama Barang",
                dataIndex: 'nama_produk',
                sortable: true,
                width: 250
            }, {
                header: "Qty Retur",
                dataIndex: 'qty',
                sortable: true,
                width: 100
            }, {
                header: "Harga Supplier",
                dataIndex: 'price_supp',
                sortable: true,
                width: 100
            }, {
                // xtype: 'numbercolumn',
                header: 'Disk 1',
                dataIndex: 'disk_grid_supp1',           
                width: 60,
                sortable: true,
                align: 'right',
                // format: '0,0',
                
            },{
                // xtype: 'numbercolumn',
                header: 'Disk 2',
                dataIndex: 'disk_grid_supp2',           
                width: 60,
                sortable: true,
                align: 'right',
                // format: '0,0',
               
            },{
                // xtype: 'numbercolumn',
                header: 'Disk 3',
                dataIndex: 'disk_grid_supp3',           
                width: 60,
                sortable: true,
                align: 'right',
                // format: '0,0',
                
            },{
                // xtype: 'numbercolumn',
                header: 'Disk 4',
                dataIndex: 'disk_grid_supp4',           
                width: 60,
                sortable: true,
                align: 'right',
                // format: '0,0',
               
            },{
                // xtype: 'numbercolumn',
                header: 'Disk 5',
                dataIndex: 'disk_grid_supp5',           
                width: 60,
                sortable: true,
                align: 'right',
                // format: '0,0',
                
            },{
			xtype: 'numbercolumn',
                        header: "Total Diskon",
			dataIndex: 'rp_diskon',
			sortable: true,
			width: 100,
                        format : '0,0'
		},{
			xtype: 'numbercolumn',
                        header: "Harga",
			dataIndex: 'net_price',
			sortable: true,
			width: 100,
                        format : '0,0'
		},{
			xtype: 'numbercolumn',
                        header: "Jumlah",
			dataIndex: 'rp_jumlah',
			sortable: true,
			width: 100,
                        format : '0,0'
		}],
    });

    var gridDetCetakReturRoprint = new Ext.grid.EditorGridPanel({
        id: 'gridDetCetakReturRoprint',
        store: strreturreceiveorderprintdetail,
        stripeRows: true,
        style: 'margin-bottom:5px;',
        height: 225,
        frame: true,
        border: true,
        loadMask: true,
        sm: smgridDetCetakReturRoprint,
        // plugins: [action_approval_detail_approve_manager,action_approval_detail_reject_manager],
        view: new Ext.ux.grid.LockingGridView(),
        cm: cmm,
    });
    // Form Panel
    var cetakreturreceiveorder = new Ext.FormPanel({
        id: 'cetakreturreceiveorder',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },
                items: [headerreturreceiveorderprint]
            }, gridcetakreturroprint, 
             gridDetCetakReturRoprint

        ],
        buttons: [{
                text: 'Cetak',
                handler: function() {

                    var sm = gridcetakreturroprint.getSelectionModel();                
                    var sel = sm.getSelections(); 	
                    winreturreceiveorderprint.show();
                    Ext.getDom('printreturreceiveorderprint').src = '<?= site_url("cetak_retur_receive_order/print_form") ?>'+'/'+sel[0].get('no_retur');
                }
            }, {
                text: 'Reset',
                handler: function() {
                    clearcetakreturreceiveorderprint(); 
                }
            }]
    });
    var winreturreceiveorderprint = new Ext.Window({
        id: 'id_winreturreceiveorderprint',
	title: 'Print Retur Receive Order Form',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="printreturreceiveorderprint" src=""></iframe>'
    });
    function clearcetakreturreceiveorderprint(){
		Ext.getCmp('cetakreturreceiveorder').getForm().reset();
		strreturreceiveorderprint.removeAll();
		strreturreceiveorderprintdetail.removeAll();
	}
</script>