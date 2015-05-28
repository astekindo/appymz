<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    // Twin Combo Supplier
    var strcbcphsuplier = new Ext.data.ArrayStore({
        fields: ['nama_supplier'],
        data: []
    });

    var strgridcphsuplier = new Ext.data.Store({
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

    strgridcphsuplier.on('load', function() {
        Ext.getCmp('id_searchgridcphpsuplier').focus();
    });

    var searchgridcphpsuplier = new Ext.app.SearchField({
        store: strgridcphsuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridcphpsuplier'
    });

    var gridcphsuplier = new Ext.grid.GridPanel({
        store: strgridcphsuplier,
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
            items: [searchgridcphpsuplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridcphsuplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('cph_kd_supplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_cbcphsuplier').setValue(sel[0].get('nama_supplier'));
                    strpelunasanhutangprint.load({
                        params: {
                            kd_supplier: sel[0].get('kd_supplier')
                        }
                    });
                    menucphsuplier.hide();
                }
            }
        }
    });

    var menucphsuplier = new Ext.menu.Menu();
    menucphsuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridcphsuplier],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menucphsuplier.hide();
                }
            }]
    }));

    Ext.ux.TwinComboCphSulier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridcphsuplier.load();
            menucphsuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menucphsuplier.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridcphpsuplier').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgridcphpsuplier').setValue('');
            searchgridcphpsuplier.onTrigger2Click();
        }
    });

    var cbcphsuplier = new Ext.ux.TwinComboCphSulier({
        fieldLabel: 'Supplier',
        id: 'id_cbcphsuplier',
        store: strcbcphsuplier,
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

    //Header Pelunasan Hutang Print
    var headerpelunasanhutangprint = {
        layout: 'column',
        border: false,
        items: [{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [cbcphsuplier,{
                        xtype: 'hidden',
                        name: 'kd_supplier',
                        id: 'cph_kd_supplier',
                        value: ''
                    }]
            }]
    }

    // START GRID Pelunasan Hutang Print
    var strpelunasanhutangprint = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_bukti',
                'kd_supplier',
                'nama_supplier',
                'tanggal',
                'rp_total_invoice',
                'rp_total_potongan',
                'rp_selisih',
                'rp_total',
                'rp_total_dibayar'
                
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("cetak_pelunasan_hutang/get_rows") ?>',
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
    strpelunasanhutangprint.on('load', function(){
        Ext.getCmp('idsearch_invoice_order_print').focus();
    });
    // search field
    var search_invoice_order_print = new Ext.app.SearchField({
        store: strpelunasanhutangprint,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        emptyText : 'Cari No Bukti',
        id: 'idsearch_invoice_order_print'
    });

    // top toolbar
    var tb_invoice_order_print = new Ext.Toolbar({
        items: [search_invoice_order_print, '->', '<i>Klik row untuk melihat detail pelunasan hutang</i>']
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
    var smgridpelunasanhutangprint = new Ext.grid.CheckboxSelectionModel();
    var smgridDetailCphprint = new Ext.grid.CheckboxSelectionModel();

    // data store
    var strpelunasanhutangprintdetail = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_invoice',
                'rp_total',
                'potongan',
                'tgl_invoice',
                'tgl_terima_invoice',
                'rp_bayar',
                'nm_pembayaran',
                'no_bank',
                'no_ref',
                'tgl_jth_tempo',
                'rp_dibayar',
                'rp_total_potongan',
                'rp_total_dibayar',
                'sisa_invoice',
                'nama_pembayaran',
                'jumlah_bayar',
                'no_bukti_supplier',
                'rp_bayar',
                'rp_pelunasan_hutang'
                
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("cetak_pelunasan_hutang/get_rows_detail") ?>',
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

    strpelunasanhutangprint.on('load', function() {
        strpelunasanhutangprintdetail.removeAll();
    })

    var gridpelunasanhutangprint = new Ext.grid.EditorGridPanel({
        id: 'gridpelunasanhutangprint',
        frame: true,
        border: true,
        stripeRows: true,
        sm: smgridpelunasanhutangprint,
        store: strpelunasanhutangprint,
        loadMask: true,
        title: 'Data Pembayaran Hutang',
        style: 'margin:0 auto;',
        height: 225,
        // width: 550,
        columns: [{
                header: "No Bukti",
                dataIndex: 'no_bukti',
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
                header: "Tanggal Pembayaran",
                dataIndex: 'tanggal',
                sortable: true,
                width: 120
            }, {
                xtype: 'numbercolumn',
                header: "Total Invoice",
                dataIndex: 'rp_total_invoice',
                sortable: true,
                format :'0,0',
                width: 100
            }, {
                xtype: 'numbercolumn',
                header: "Total Potongan",
                dataIndex: 'rp_total_potongan',
                sortable: true,
                format :'0,0',
                width: 120
            },{
                xtype: 'numbercolumn',
                header: "Total Dibayar",
                dataIndex: 'rp_total_dibayar',
                sortable: true,
                format :'0,0',
                width: 90
            },{
                xtype: 'numbercolumn',
                header: "Total Bayar",
                dataIndex: 'rp_total',
                sortable: true,
                format :'0,0',
                width: 90
            }],
        listeners: {
            'rowclick': function() {
                var sm = gridpelunasanhutangprint.getSelectionModel();
                var sel = sm.getSelections();
                gridDetailCphprint.store.proxy.conn.url = '<?= site_url("cetak_pelunasan_hutang/get_rows_detail") ?>/' + sel[0].get('no_bukti');
                gridDetailCphprint.store.reload();
            }
        },
        tbar: tb_invoice_order_print,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strpelunasanhutangprint,
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
            }, 
             {
                header: "Tanggal Terima Invoice",
                dataIndex: 'tgl_terima_invoice',
                sortable: true,
                width: 130
            },
            {
                header: "Tanggal Invoice",
                dataIndex: 'tgl_invoice',
                sortable: true,
                width: 100
            }, {
                header: "No Bukti Supp",
                dataIndex: 'no_bukti_supplier',
                sortable: true,
                width: 100
            },{
                xtype: 'numbercolumn',
                header: "total Invoice",
                dataIndex: 'rp_total',
                sortable: true,
                format :'0,0',
                width: 100
            }, {
                xtype: 'numbercolumn',
                header: 'Potongan',
                dataIndex: 'potongan',           
                width: 120,
                sortable: true,
                format :'0,0',
                align: 'right'
                
            }, {
                xtype: 'numbercolumn',
                header: "Jumlah Bayar",
                dataIndex: 'rp_bayar',
                sortable: true,
                format :'0,0',
                width: 100
            },{
                xtype: 'numbercolumn',
                header: 'Total Bayar',
                dataIndex: 'rp_pelunasan_hutang',           
                width: 100,
                sortable: true,
                format :'0,0',
                align: 'right'
            },{
                xtype: 'numbercolumn',
                header: 'Sisa Invoice',
                dataIndex: 'sisa_invoice',           
                width: 120,
                sortable: true,
                format :'0,0',
                align: 'right'
                
            }]
    });

    var gridDetailCphprint = new Ext.grid.EditorGridPanel({
        id: 'gridDetailCphprint',
        store: strpelunasanhutangprintdetail,
        stripeRows: true,
        style: 'margin-bottom:5px;',
        height: 225,
        frame: true,
        border: true,
        loadMask: true,
        sm: smgridDetailCphprint,
        // plugins: [action_approval_detail_approve_manager,action_approval_detail_reject_manager],
        view: new Ext.ux.grid.LockingGridView(),
        cm: cmm,
    });
    // Form Panel
    var cetakpelunasanhutang = new Ext.FormPanel({
        id: 'cetakpelunasanhutang',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },
                items: [headerpelunasanhutangprint]
            },gridpelunasanhutangprint,
            gridDetailCphprint

        ],
        buttons: [{
                text: 'Cetak',
                handler: function() {

                    var sm = gridpelunasanhutangprint.getSelectionModel();                
                    var sel = sm.getSelections(); 	
                    wincetakpelunasanhutangprint.show();
                    Ext.getDom('printpelunasanhutangprint').src = '<?= site_url("cetak_pelunasan_hutang/print_form") ?>'+'/'+sel[0].get('no_bukti');
                }
            }, {
                text: 'Reset',
                handler: function() {
                    clearcetakpelunasanhutang(); 
                }
            }]
    });
    var wincetakpelunasanhutangprint = new Ext.Window({
        id: 'id_wincetakpelunasanhutangprint',
	title: 'Pembayaran Hutang Form',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="printpelunasanhutangprint" src=""></iframe>'
    });
    function clearcetakpelunasanhutang(){
		Ext.getCmp('cetakpelunasanhutang').getForm().reset();
		strpelunasanhutangprint.removeAll();
		strpelunasanhutangprintdetail.removeAll();
	}
</script>