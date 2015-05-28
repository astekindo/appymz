<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    // Twin Combo No SO
    var strcbcrjnoso = new Ext.data.ArrayStore({
        fields: ['no_so'],
        data: []
    });

    var strgridcrjnoso = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_so', 'tgl_so'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("cetak_retur_penjualan/get_list_so") ?>',
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

    strgridcrjnoso.on('load', function() {
        Ext.getCmp('id_searchgridcrjnoso').focus();
    });

    var searchgridcrjnoso = new Ext.app.SearchField({
        store: strgridcrjnoso,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridcrjnoso'
    });

    var gridcrjnoso = new Ext.grid.GridPanel({
        store: strgridcrjnoso,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'No SO/Struk',
                dataIndex: 'no_so',
                width: 150,
                sortable: true
            }, {
                header: 'Tanggal Sales Order',
                dataIndex: 'tgl_so',
                width: 300,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridcrjnoso]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridcrjnoso,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    //Ext.getCmp('crp_kd_supplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_cbcrjnoso').setValue(sel[0].get('no_so'));
                    strcetakreturjualprint.load({
                        params: {
                            no_so: sel[0].get('no_so')
                        }
                    });
                    menucrjnoso.hide();
                }
            }
        }
    });

    var menucrjnoso = new Ext.menu.Menu();
    menucrjnoso.add(new Ext.Panel({
        title: 'Pilih No SO',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridcrjnoso],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menucrjnoso.hide();
                }
            }]
    }));

    Ext.ux.TwinComboCrjNoSo = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridcrjnoso.load();
            menucrjnoso.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menucrjnoso.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridcrjnoso').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgridcrjnoso').setValue('');
            searchgridcrjnoso.onTrigger2Click();
        }
    });

    var cbcrjnoso = new Ext.ux.TwinComboCrjNoSo({
        fieldLabel: 'No SO/Struk',
        id: 'id_cbcrjnoso',
        store: strcbcrjnoso,
        mode: 'local',
        valueField: 'no_so',
        displayField: 'no_so',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'no_so',
        emptyText: 'Pilih No Struk'
    });

    //Header Retur Pembelian Print
    var headercetakreturjual = {
        layout: 'column',
        border: false,
        items: [{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [cbcrjnoso,{
                        xtype: 'hidden',
                        name: 'kd_supplier',
                        id: 'crp_kd_supplier',
                        value: ''
                    }]
            }]
    }

    // START GRID Retur Pembelian Print
    var strcetakreturjualprint = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_retur',
                'tgl_retur',
                'no_so',
                'kd_produk_supp',
                'nama_produk',
                'qty',
                'rp_jumlah',
                'rp_total',
                'remark',
                'rp_potongan',
                'dpp'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("cetak_retur_penjualan/get_rows") ?>',
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
    strcetakreturjualprint.on('load', function(){
        Ext.getCmp('idsearch_retur_jual_print').focus();
    });
    // search field
    var search_retur_jual_print = new Ext.app.SearchField({
        store: strcetakreturjualprint,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        emptyText : 'No Retur Jual',
        id: 'idsearch_retur_jual_print'
    });

    // top toolbar
    var tb_cetak_Rj_print = new Ext.Toolbar({
        items: [search_retur_jual_print, '->', '<i>Klik row untuk melihat Detail Retur Penjualan</i>']
    });
     search_retur_jual_print.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';
			
            // Get the value of search field
            var fid = Ext.getCmp('id_cbcrjnoso').getValue();
            var o = { start: 0, no_so: fid };
			
            this.store.baseParams = this.store.baseParams || {};
            this.store.baseParams[this.paramName] = '';
            this.store.reload({
                params : o
            });
            this.triggers[0].hide();
            this.hasSearch = false;
        }
    };
	
    search_retur_jual_print.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }
	 
        // Get the value of search field
        var fid = Ext.getCmp('id_cbcrjnoso').getValue();
        var o = { start: 0, no_so: fid };
	 
        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params:o});
        this.hasSearch = true;
        this.triggers[0].show();
    };
    // checkbox grid
    var smgridcrjprint = new Ext.grid.CheckboxSelectionModel();
    var smgridDeetailCrjprint = new Ext.grid.CheckboxSelectionModel();

    // data store
    var strcetakreturjualprintdetail = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_produk',
                'no_retur',
                'qty',
                'rp_disk',
                'rp_jumlah',
                'rp_potongan',
                'rp_total',
                'Lokasi',
                'nama_produk',
                'grand_total',
                'potongan'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("cetak_retur_penjualan/get_rows_detail") ?>',
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

    strcetakreturjualprint.on('load', function() {
        strcetakreturjualprintdetail.removeAll();
    })

    var gridcrjprint = new Ext.grid.EditorGridPanel({
        id: 'gridcrjprint',
        frame: true,
        border: true,
        stripeRows: true,
        sm: smgridcrjprint,
        store: strcetakreturjualprint,
        loadMask: true,
        title: 'Retur Penjualan',
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
                header: "Tanggal Retur",
                dataIndex: 'tgl_retur',
                sortable: true,
                width: 150
            },{
                header: "Jumlah Retur",
                dataIndex: 'rp_jumlah',
                sortable: true,
                width: 80
            },{
                header: "Diskon Tambahan",
                dataIndex: 'rp_potongan',
                sortable: true,
                width: 120
            },{
                header: "Grand Total",
                dataIndex: 'rp_total',
                sortable: true,
                width: 80
            }],
        listeners: {
            'rowclick': function() {
                var sm = gridcrjprint.getSelectionModel();
                var sel = sm.getSelections();
                gridDeetailCrjprint.store.proxy.conn.url = '<?= site_url("cetak_retur_penjualan/get_rows_detail") ?>/' + sel[0].get('no_retur');
                gridDeetailCrjprint.store.reload();
            }
        },
        tbar: tb_cetak_Rj_print,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strcetakreturjualprint,
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
                header: "No Retur",
                dataIndex: 'no_retur',
                sortable: true,
                width: 100
            }, {
                header: "Kode Produk",
                dataIndex: 'kd_produk',
                sortable: true,
                width: 100
            }, {
                header: "Kode Produk Supplier",
                dataIndex: 'kd_produk_supp',
                sortable: true,
                width: 100
            }, {
                header: "Nama Produk",
                dataIndex: 'nama_produk',
                sortable: true,
                width: 250
            }, {
                header: "Qty Retur",
                dataIndex: 'qty',
                sortable: true,
                width: 100
            }, {
                // xtype: 'numbercolumn',
                header: 'Harga',
                dataIndex: 'rp_jumlah',           
                width: 80,
                sortable: true,
                align: 'right',
                // format: '0,0',
                
            },{
                header: "Rp Diskon",
                dataIndex: 'rp_disk',
                sortable: true,
                width: 100
            }, {
                // xtype: 'numbercolumn',
                header: 'Rp Ekstra Diskon',
                dataIndex: 'rp_potongan',           
                width: 130,
                sortable: true,
                align: 'right',
                // format: '0,0',
               
            },{
                // xtype: 'numbercolumn',
                header: 'Rp Jumlah',
                dataIndex: 'rp_total',           
                width: 80,
                sortable: true,
                align: 'right',
                // format: '0,0',
                
            },{
                header: "Rp Potongan",
                dataIndex: 'potongan',
                sortable: true,
                width: 120
            },{
                header: "Grand Total",
                dataIndex: 'grand_total',
                sortable: true,
                width: 80
            },{
                // xtype: 'numbercolumn',
                header: 'Lokasi',
                dataIndex: 'lokasi',           
                width: 100,
                sortable: true,
                align: 'right',
                // format: '0,0',
               
            }]
    });

    var gridDeetailCrjprint = new Ext.grid.EditorGridPanel({
        id: 'gridDeetailCrjprint',
        store: strcetakreturjualprintdetail,
        stripeRows: true,
        style: 'margin-bottom:5px;',
        height: 225,
        frame: true,
        border: true,
        loadMask: true,
        sm: smgridDeetailCrjprint,
        // plugins: [action_approval_detail_approve_manager,action_approval_detail_reject_manager],
        view: new Ext.ux.grid.LockingGridView(),
        cm: cmm,
    });
    // Form Panel
    var cetakreturjual = new Ext.FormPanel({
        id: 'cetakreturjual',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },
                items: [headercetakreturjual]
            }, gridcrjprint, 
             gridDeetailCrjprint

        ],
        buttons: [{
                text: 'Cetak',
                handler: function() {

                    var sm = gridcrjprint.getSelectionModel();                
                    var sel = sm.getSelections(); 	
                    wincetakreturjualprint.show();
                    Ext.getDom('printcetakreturjual').src = '<?= site_url("cetak_retur_penjualan/print_form") ?>'+'/'+sel[0].get('no_retur');
                }
            }, {
                text: 'Reset',
                handler: function() {
                    clearcetakreturjualprint(); 
                }
            }]
    });
    var wincetakreturjualprint = new Ext.Window({
        id: 'id_wincetakreturjualprint',
	title: 'Print Retur Penjualan Form',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="printcetakreturjual" src=""></iframe>'
    });
    function clearcetakreturjualprint(){
		Ext.getCmp('cetakreturjual').getForm().reset();
		strcetakreturjualprint.removeAll();
		strcetakreturjualprintdetail.removeAll();
	}
</script>