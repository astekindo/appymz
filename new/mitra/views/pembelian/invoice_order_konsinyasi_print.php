<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    // Twin Combo Supplier
    var strcbiopksuplier = new Ext.data.ArrayStore({
        fields: ['nama_supplier'],
        data: []
    });

    var strgridiopksuplier = new Ext.data.Store({
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

    strgridiopksuplier.on('load', function() {
        Ext.getCmp('id_searchgridiopksuplier').focus();
    });

    var searchgridiopksuplier = new Ext.app.SearchField({
        store: strgridiopksuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridiopksuplier'
    });

    var gridiopksuplier = new Ext.grid.GridPanel({
        store: strgridiopksuplier,
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
            items: [searchgridiopksuplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridiopksuplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('iopk_kd_supplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_cbiopksuplier').setValue(sel[0].get('nama_supplier'));
                    strinvoiceorderprint_kons.load({
                        params: {
                            kd_supplier: sel[0].get('kd_supplier')
                        }
                    });
                    menuiopksuplier.hide();
                }
            }
        }
    });

    var menuiopksuplier = new Ext.menu.Menu();
    menuiopksuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridiopksuplier],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menuiopksuplier.hide();
                }
            }]
    }));

    Ext.ux.TwinComboIopkSuplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridiopksuplier.load();
            menuiopksuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menuiopksuplier.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridiopksuplier').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgridiopksuplier').setValue('');
            searchgridiopksuplier.onTrigger2Click();
        }
    });

    var cbiopksuplier = new Ext.ux.TwinComboIopkSuplier({
        fieldLabel: 'Supplier',
        id: 'id_cbiopksuplier',
        store: strcbiopksuplier,
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

    //Header Invoice Order Print
    var headerinvoiceorderprint_kons = {
        layout: 'column',
        border: false,
        items: [{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [cbiopksuplier,{
                        xtype: 'hidden',
                        name: 'kd_supplier',
                        id: 'iopk_kd_supplier',
                        value: ''
                    }]
            }]
    }

    // START GRID Invoice Order Print
    var strinvoiceorderprint_kons = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_invoice',
                'kd_supplier',
                'nama_supplier',
                'tgl_invoice',
                'tgl_terima_invoice',
                'no_bukti_supplier',
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_invoice_order_konsinyasi_print/get_rows") ?>',
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
    strinvoiceorderprint_kons.on('load', function(){
        Ext.getCmp('idsearch_invoice_order_print').focus();
    });
    // search field
    var search_invoice_order_print = new Ext.app.SearchField({
        store: strinvoiceorderprint_kons,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        emptyText : 'No Invoice',
        id: 'idsearch_invoice_order_print'
    });

    // top toolbar
    var tb_invoice_order_kons_print = new Ext.Toolbar({
        items: [search_invoice_order_print, '->', '<i>Klik row untuk melihat detail Invoice</i>']
    });
     search_invoice_order_print.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';
			
            // Get the value of search field
            var fid = Ext.getCmp('iopk_kd_supplier').getValue();
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
	
    search_invoice_order_print.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }
	 
        // Get the value of search field
        var fid = Ext.getCmp('iopk_kd_supplier').getValue();
        var o = { start: 0, kd_supplier: fid };
	 
        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params:o});
        this.hasSearch = true;
        this.triggers[0].show();
    };
    // checkbox grid
    var smgridiopprint_kons = new Ext.grid.CheckboxSelectionModel();
    var smgridDetIopprint_kons = new Ext.grid.CheckboxSelectionModel();

    // data store
    var strinvoiceorderprint_konsdetail = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_do',
                'no_po',
                'kd_produk',
                'nama_produk',
                'qty',
                'rp_total_diskon',
                'harga_supplier',
                'harga_net',
                'harga_net_ect',
                'rp_dpp',
                'rp_ajd_jumlah',
                'rp_jumlah',
                'disk_grid_supp1',
                'disk_grid_supp2',
                'disk_grid_supp3',
                'disk_grid_supp4',
                'disk_grid_supp5'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_invoice_order_print/get_rows_detail") ?>',
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

    strinvoiceorderprint_kons.on('load', function() {
        strinvoiceorderprint_konsdetail.removeAll();
    })

    var gridiopprint_kons = new Ext.grid.EditorGridPanel({
        id: 'gridiopprint_kons',
        frame: true,
        border: true,
        stripeRows: true,
        sm: smgridiopprint_kons,
        store: strinvoiceorderprint_kons,
        loadMask: true,
        title: 'Invoice Order',
        style: 'margin:0 auto;',
        height: 225,
        // width: 550,
        columns: [{
                header: "No Invoice",
                dataIndex: 'no_invoice',
                // hidden: true,
                sortable: true,
                width: 150
            }, {
                header: "Kode Supplier",
                dataIndex: 'kd_supplier',
                sortable: true,
                width: 150
            }, {
                header: "Nama Supplier",
                dataIndex: 'nama_supplier',
                sortable: true,
                width: 250
            }, {
                header: "Tanggal Input",
                dataIndex: 'tgl_invoice',
                sortable: true,
                width: 80
            }, {
                header: "Tanggal Terima",
                dataIndex: 'tgl_terima_invoice',
                sortable: true,
                width: 90
            }, {
                header: "No Bukti Supplier",
                dataIndex: 'no_bukti_supplier',
                sortable: true,
                width: 300
            }],
        listeners: {
            'rowclick': function() {
                var sm = gridiopprint_kons.getSelectionModel();
                var sel = sm.getSelections();
                gridDetIopprint_kons.store.proxy.conn.url = '<?= site_url("pembelian_invoice_order_print/get_rows_detail") ?>/' + sel[0].get('no_invoice');
                gridDetIopprint_kons.store.reload();
            }
        },
        tbar: tb_invoice_order_kons_print,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strinvoiceorderprint_kons,
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
                header: "Qty Invoice",
                dataIndex: 'qty',
                sortable: true,
                width: 100
            }, {xtype: 'numbercolumn',
                header: "Harga Beli",
                dataIndex: 'harga_supplier',
                sortable: true,
                width: 100,
                format : '0,0'
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
			dataIndex: 'rp_total_diskon',
			sortable: true,
			width: 100,
                        format : '0,0'
		},{
			xtype: 'numbercolumn',
                        header: "Harga Net",
			dataIndex: 'harga_net',
			sortable: true,
			width: 100,
                        format : '0,0'
		},{
			xtype: 'numbercolumn',
                        header: "Harga Net(Exc)",
			dataIndex: 'harga_net_ect',
			sortable: true,
			width: 100,
                        format : '0,0'
		},{
			xtype: 'numbercolumn',
                        header: "Adjustment",
			dataIndex: 'rp_ajd_jumlah',
			sortable: true,
			width: 100,
                        format : '0,0'
		}, {
			xtype: 'numbercolumn',
                        header: "Jumlah",
			dataIndex: 'rp_jumlah',
			sortable: true,
			width: 100,
                        format : '0,0'
		}],
    });

    var gridDetIopprint_kons = new Ext.grid.EditorGridPanel({
        id: 'gridDetIopprint_kons',
        store: strinvoiceorderprint_konsdetail,
        stripeRows: true,
        style: 'margin-bottom:5px;',
        height: 225,
        frame: true,
        border: true,
        loadMask: true,
        sm: smgridDetIopprint_kons,
        // plugins: [action_approval_detail_approve_manager,action_approval_detail_reject_manager],
        view: new Ext.ux.grid.LockingGridView(),
        cm: cmm,
    });
    // Form Panel
    var cetakinvoiceorder_kons = new Ext.FormPanel({
        id: 'cetakinvoiceorder_kons',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },
                items: [headerinvoiceorderprint_kons]
            }, gridiopprint_kons, gridDetIopprint_kons

        ],
        buttons: [{
                text: 'Cetak',
                handler: function() {

                    var sm = gridiopprint_kons.getSelectionModel();                
                    var sel = sm.getSelections(); 	
                    wininvoiceorderprint_kons.show();
                    Ext.getDom('printinvoiceorderprint_kons').src = '<?= site_url("konsinyasi_create_invoice/print_form") ?>'+'/'+sel[0].get('no_invoice');
                }
            }, {
                text: 'Reset',
                handler: function() {
                    clearinvoiceorderprint(); 
                }
            }]
    });
    var wininvoiceorderprint_kons = new Ext.Window({
        id: 'id_wininvoiceorderprint_kons',
	title: 'Print Invoice Order Form',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="printinvoiceorderprint_kons" src=""></iframe>'
    });
    function clearinvoiceorderprint(){
		Ext.getCmp('cetakinvoiceorder_kons').getForm().reset();
		strinvoiceorderprint_kons.removeAll();
		strinvoiceorderprint_konsdetail.removeAll();
	}
</script>