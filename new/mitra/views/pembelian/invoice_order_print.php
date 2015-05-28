<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    // Twin Combo Supplier
    var strcbiopsuplier = new Ext.data.ArrayStore({
        fields: ['nama_supplier'],
        data: []
    });

    var strgridiopsuplier = new Ext.data.Store({
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

    strgridiopsuplier.on('load', function() {
        Ext.getCmp('id_searchgridiopsuplier').focus();
    });

    var searchgridiopsuplier = new Ext.app.SearchField({
        store: strgridiopsuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridiopsuplier'
    });

    var gridiopsuplier = new Ext.grid.GridPanel({
        store: strgridiopsuplier,
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
            items: [searchgridiopsuplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridiopsuplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('iop_kd_supplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_cbiopsuplier').setValue(sel[0].get('nama_supplier'));
                    strinvoiceorderprint.load({
                        params: {
                            kd_supplier: sel[0].get('kd_supplier')
                        }
                    });
                    menuiopsuplier.hide();
                }
            }
        }
    });

    var menuiopsuplier = new Ext.menu.Menu();
    menuiopsuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridiopsuplier],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menuiopsuplier.hide();
                }
            }]
    }));

    Ext.ux.TwinComboIopSuplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridiopsuplier.load();
            menuiopsuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menuiopsuplier.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridiopsuplier').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgridiopsuplier').setValue('');
            searchgridiopsuplier.onTrigger2Click();
        }
    });

    var cbiopsuplier = new Ext.ux.TwinComboIopSuplier({
        fieldLabel: 'Supplier',
        id: 'id_cbiopsuplier',
        store: strcbiopsuplier,
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
    var headerinvoiceorderprint = {
        layout: 'column',
        border: false,
        items: [{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [cbiopsuplier,{
                        xtype: 'hidden',
                        name: 'kd_supplier',
                        id: 'iop_kd_supplier',
                        value: ''
                    }]
            }]
    }

    // START GRID Invoice Order Print
    var strinvoiceorderprint = new Ext.data.Store({
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
            url: '<?= site_url("pembelian_invoice_order_print/get_rows") ?>',
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
    strinvoiceorderprint.on('load', function(){
        strinvoiceorderprint.setBaseParam('kd_supplier',Ext.getCmp('iop_kd_supplier').getValue());
        Ext.getCmp('idsearch_invoice_order_print').focus();
    });
    // search field
    var search_invoice_order_print = new Ext.app.SearchField({
        store: strinvoiceorderprint,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        emptyText : 'No Invoice,No Bukti Supplier',
        id: 'idsearch_invoice_order_print'
    });

    // top toolbar
    var tb_invoice_order_print = new Ext.Toolbar({
        items: [search_invoice_order_print, '->', '<i>Klik row untuk melihat detail Invoice</i>']
    });
     search_invoice_order_print.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';
			
            // Get the value of search field
            var fid = Ext.getCmp('iop_kd_supplier').getValue();
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
        var fid = Ext.getCmp('iop_kd_supplier').getValue();
        var o = { start: 0, kd_supplier: fid };
	 
        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params:o});
        this.hasSearch = true;
        this.triggers[0].show();
    };
    // checkbox grid
    var smgridiopprint = new Ext.grid.CheckboxSelectionModel();
    var smgridDetIopprint = new Ext.grid.CheckboxSelectionModel();

    // data store
    var strinvoiceorderprintdetail = new Ext.data.Store({
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

    strinvoiceorderprint.on('load', function() {
        strinvoiceorderprintdetail.removeAll();
    })

    var gridiopprint = new Ext.grid.EditorGridPanel({
        id: 'gridiopprint',
        frame: true,
        border: true,
        stripeRows: true,
        sm: smgridiopprint,
        store: strinvoiceorderprint,
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
                var sm = gridiopprint.getSelectionModel();
                var sel = sm.getSelections();
                gridDetIopprint.store.proxy.conn.url = '<?= site_url("pembelian_invoice_order_print/get_rows_detail") ?>/' + sel[0].get('no_invoice');
                gridDetIopprint.store.reload();
            }
        },
        tbar: tb_invoice_order_print,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strinvoiceorderprint,
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

    var gridDetIopprint = new Ext.grid.EditorGridPanel({
        id: 'gridDetIopprint',
        store: strinvoiceorderprintdetail,
        stripeRows: true,
        style: 'margin-bottom:5px;',
        height: 225,
        frame: true,
        border: true,
        loadMask: true,
        sm: smgridDetIopprint,
        // plugins: [action_approval_detail_approve_manager,action_approval_detail_reject_manager],
        view: new Ext.ux.grid.LockingGridView(),
        cm: cmm,
    });
    // Form Panel
    var cetakinvoiceorder = new Ext.FormPanel({
        id: 'cetakinvoiceorder',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },
                items: [headerinvoiceorderprint]
            }, gridiopprint, gridDetIopprint

        ],
        buttons: [{
                text: 'Cetak',
                handler: function() {

                    var sm = gridiopprint.getSelectionModel();                
                    var sel = sm.getSelections(); 	
                    wininvoiceorderprint.show();
                    Ext.getDom('printinvoiceorderprint').src = '<?= site_url("pembelian_invoice_order_print/print_form") ?>'+'/'+sel[0].get('no_invoice');
                }
            }, {
                text: 'Reset',
                handler: function() {
                    clearinvoiceorderprint(); 
                }
            }]
    });
    var wininvoiceorderprint = new Ext.Window({
        id: 'id_wininvoiceorderprint',
	title: 'Print Invoice Order Form',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="printinvoiceorderprint" src=""></iframe>'
    });
    function clearinvoiceorderprint(){
		Ext.getCmp('cetakinvoiceorder').getForm().reset();
		strinvoiceorderprint.removeAll();
		strinvoiceorderprintdetail.removeAll();
	}
</script>