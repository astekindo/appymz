<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    // Twin Combo Supplier
    var strcbcrpsuplier = new Ext.data.ArrayStore({
        fields: ['nama_supplier'],
        data: []
    });

    var strgridcrpsuplier = new Ext.data.Store({
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

    strgridcrpsuplier.on('load', function() {
        Ext.getCmp('id_searchgridcrpsuplier').focus();
    });

    var searchgridcrpsuplier = new Ext.app.SearchField({
        store: strgridcrpsuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridcrpsuplier'
    });

    var gridcrpsuplier = new Ext.grid.GridPanel({
        store: strgridcrpsuplier,
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
            items: [searchgridcrpsuplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridcrpsuplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('crp_kd_supplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_cbcrpsuplier').setValue(sel[0].get('nama_supplier'));
                    strreturpembelianprint.load({
                        params: {
                            kd_supplier: sel[0].get('kd_supplier')
                        }
                    });
                    menucrpsuplier.hide();
                }
            }
        }
    });

    var menucrpsuplier = new Ext.menu.Menu();
    menucrpsuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridcrpsuplier],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menucrpsuplier.hide();
                }
            }]
    }));

    Ext.ux.TwinComboCrpSuplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridcrpsuplier.load();
            menucrpsuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menucrpsuplier.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridcrpsuplier').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgridcrpsuplier').setValue('');
            searchgridcrpsuplier.onTrigger2Click();
        }
    });

    var cbcrpsuplier = new Ext.ux.TwinComboCrpSuplier({
        fieldLabel: 'Supplier',
        id: 'id_cbcrpsuplier',
        store: strcbcrpsuplier,
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

    //Header Retur Pembelian Print
    var headerreturpembelianrint = {
        layout: 'column',
        border: false,
        items: [{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [cbcrpsuplier,{
                        xtype: 'hidden',
                        name: 'kd_supplier',
                        id: 'crp_kd_supplier',
                        value: ''
                    }]
            }]
    }

    // START GRID Retur Pembelian Print
    var strreturpembelianprint = new Ext.data.Store({
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
            url: '<?= site_url("cetak_retur_pembelian/get_rows") ?>',
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
    strreturpembelianprint.on('load', function(){
        Ext.getCmp('idsearch_retur_beli_print').focus();
    });
    // search field
    var search_retur_beli_print = new Ext.app.SearchField({
        store: strreturpembelianprint,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        emptyText :'No Retur Beli',
        id: 'idsearch_retur_beli_print'
    });

    // top toolbar
    var tb_retur_beli_print = new Ext.Toolbar({
        items: [search_retur_beli_print, '->', '<i>Klik row untuk melihat Detail Retur Pembelian</i>']
    });
     search_retur_beli_print.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';
			
            // Get the value of search field
            var fid = Ext.getCmp('crp_kd_supplier').getValue();
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
	
    search_retur_beli_print.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }
	 
        // Get the value of search field
        var fid = Ext.getCmp('crp_kd_supplier').getValue();
        var o = { start: 0, kd_supplier: fid };
	 
        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params:o});
        this.hasSearch = true;
        this.triggers[0].show();
    };
    // checkbox grid
    var smgridcrpprint = new Ext.grid.CheckboxSelectionModel();
    var smgridDetCrpprint = new Ext.grid.CheckboxSelectionModel();

    // data store
    var strreturpembelianprintdetail = new Ext.data.Store({
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
                'rp_ppn'
                
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("cetak_retur_pembelian/get_rows_detail") ?>',
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

    strreturpembelianprint.on('load', function() {
        strreturpembelianprintdetail.removeAll();
    })

    var gridcrpprint = new Ext.grid.EditorGridPanel({
        id: 'gridcrpprint',
        frame: true,
        border: true,
        stripeRows: true,
        sm: smgridcrpprint,
        store: strreturpembelianprint,
        loadMask: true,
        title: 'Retur Pembelian',
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
                var sm = gridcrpprint.getSelectionModel();
                var sel = sm.getSelections();
                gridDetCrpprint.store.proxy.conn.url = '<?= site_url("cetak_retur_pembelian/get_rows_detail") ?>/' + sel[0].get('no_retur');
                gridDetCrpprint.store.reload();
            }
        },
        tbar: tb_retur_beli_print,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strreturpembelianprint,
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
                header: "No Invoice",
                dataIndex: 'no_invoice',
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
		},{
			xtype: 'numbercolumn',
                        header: "Rp PPN",
			dataIndex: 'rp_ppn',
			sortable: true,
			width: 100,
                        format : '0,0'
		},{
			xtype: 'numbercolumn',
                        header: "Rp Total",
			dataIndex: 'grand_total',
			sortable: true,
			width: 100,
                        format : '0,0'
		}],
    });

    var gridDetCrpprint = new Ext.grid.EditorGridPanel({
        id: 'gridDetCrpprint',
        store: strreturpembelianprintdetail,
        stripeRows: true,
        style: 'margin-bottom:5px;',
        height: 225,
        frame: true,
        border: true,
        loadMask: true,
        sm: smgridDetCrpprint,
        // plugins: [action_approval_detail_approve_manager,action_approval_detail_reject_manager],
        view: new Ext.ux.grid.LockingGridView(),
        cm: cmm,
    });
    // Form Panel
    var cetakreturpembelian = new Ext.FormPanel({
        id: 'cetakreturbeli',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },
                items: [headerreturpembelianrint]
            }, gridcrpprint, 
             gridDetCrpprint

        ],
        buttons: [{
                text: 'Cetak',
                handler: function() {

                    var sm = gridcrpprint.getSelectionModel();                
                    var sel = sm.getSelections(); 	
                    winreturpembelianprint.show();
                    Ext.getDom('printreturpembelianprint').src = '<?= site_url("cetak_retur_pembelian/print_form") ?>'+'/'+sel[0].get('no_retur');
                }
            }, {
                text: 'Reset',
                handler: function() {
                    clearcetakreturpembelianprint(); 
                }
            }]
    });
    var winreturpembelianprint = new Ext.Window({
        id: 'id_winreturpembelianprint',
	title: 'Print Retur Pembelian Form',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="printreturpembelianprint" src=""></iframe>'
    });
    function clearcetakreturpembelianprint(){
		Ext.getCmp('cetakreturbeli').getForm().reset();
		strreturpembelianprint.removeAll();
		strreturpembelianprintdetail.removeAll();
	}
</script>