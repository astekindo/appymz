<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script>
    // twin combo supplier
    var str_vink_supplier = new Ext.data.ArrayStore({
        fields: ['nama_supplier'],
        data: []
    });

    var strgrid_vink_supplier = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_supplier', 'nama_supplier', 'pkp'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_retur/search_supplier") ?>',
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

    var searchgrid_vink_supplier = new Ext.app.SearchField({
        store: strgrid_vink_supplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgrid_vink_supplier'
    });


    var grid_vink_supplier = new Ext.grid.GridPanel({
        store: strgrid_vink_supplier,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'Kode Supplier',
                dataIndex: 'kd_supplier',
                width: 100,
                sortable: true
            }, {
                header: 'Nama Supplier',
                dataIndex: 'nama_supplier',
                width: 170,
                sortable: true
            }, {
                header: 'Status PKP',
                dataIndex: 'pkp',
                width: 100,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchgrid_vink_supplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgrid_vink_supplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cbvinksuplier').setValue(sel[0].get('nama_supplier'));
                    Ext.getCmp('id_vink_kode_supplier').setValue(sel[0].get('kd_supplier'));
                    //strpembelianretur.removeAll();
                    menu_vink_supplier.hide();
                }
            }
        }
    });

    var menu_vink_supplier = new Ext.menu.Menu();
    menu_vink_supplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [grid_vink_supplier],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menu_vink_supplier.hide();
                }
            }]
    }));

    Ext.ux.TwinComboVINKSupplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgrid_vink_supplier.load();
            menu_vink_supplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menu_vink_supplier.on('hide', function() {
        var sf = Ext.getCmp('id_searchgrid_vink_supplier').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgrid_vink_supplier').setValue('');
            searchgrid_vink_supplier.onTrigger2Click();
        }
    });

    var cbvinksuplier = new Ext.ux.TwinComboVINKSupplier({
        fieldLabel: 'Nama Supplier',
        id: 'id_cbvinksuplier',
        store: str_vink_supplier,
        mode: 'local',
        valueField: 'nama_supplier',
        displayField: 'nama_supplier',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: true,
        editable: false,
        anchor: '90%',
        hiddenName: 'nama_supplier',
        emptyText: 'Pilih Supplier'
    });
    //end twincombosupplier

    // start COMBOBOX NO INVOICE
    var strcbvinnoinvoice_kons = new Ext.data.ArrayStore({
        fields: ['no_invoice'],
        data: []
    });
    var strgridvinnoinvoice_kons = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_invoice', 'tgl_invoice'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("konsinyasi_view_invoice/search_noinvoice") ?>',
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
    var searchgridvinnoinvoice_kons = new Ext.app.SearchField({
        store: strgridvinnoinvoice_kons,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridvinnoinvoice_kons'
    });
    var gridvinnoinvoice_kons = new Ext.grid.GridPanel({
        store: strgridvinnoinvoice_kons,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'No Invoice',
                dataIndex: 'no_invoice',
                width: 100,
                sortable: true
            }, {
                header: 'Tanggal Invoice',
                dataIndex: 'tgl_invoice',
                width: 80,
                sortable: true

            }, ],
        tbar: new Ext.Toolbar({
            items: [searchgridvinnoinvoice_kons]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridvinnoinvoice_kons,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cbvinnoinvoice_kons').setValue(sel[0].get('no_invoice'));
                    menuvrinnoinvoice_kons.hide();
                }
            }
        }
    });
    var menuvrinnoinvoice_kons = new Ext.menu.Menu();
    menuvrinnoinvoice_kons.add(new Ext.Panel({
        title: 'Pilih No Invoice',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridvinnoinvoice_kons],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menuvrinnoinvoice_kons.hide();
                }
            }]
    }));
    Ext.ux.TwinCombonoinvoicekons = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridvinnoinvoice_kons.load();
            menuvrinnoinvoice_kons.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    menuvrinnoinvoice_kons.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridvinnoinvoice_kons').getValue();
        if (sf !== '') {
            Ext.getCmp('id_searchgridvinnoinvoice_kons').setValue('');
            searchgridvinnoinvoice_kons.onTrigger2Click();
        }
        ;
    }
    );
    var cbvinnoinvoice_kons = new Ext.ux.TwinCombonoinvoicekons({
        fieldLabel: 'No Invoice',
        id: 'id_cbvinnoinvoice_kons',
        store: strcbvinnoinvoice_kons,
        mode: 'local',
        valueField: 'no_invoice',
        displayField: 'no_invoice',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'no_invoice',
        emptyText: 'Pilih No Invoice'
    });
    // end COMBOBOX NO INVOICE

    // Start Header View Invoice
    var headerview_invoice_konsinyasi = {
        layout: 'column',
        border: false,
        buttonAlign: 'left',
        items: [{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [cbvinnoinvoice_kons, //cbvrbproduk,
                    {
                        xtype: 'datefield',
                        fieldLabel: 'Tgl Awal',
                        emptyText: 'Tanggal Awal',
                        name: 'tgl_retur_awal',
                        id: 'vink_tgl_retur_awal',
                        maxLength: 255,
                        anchor: '90%',
                        value: '',
                        format: 'd-M-Y'
                    },
//                    {
//                        fieldLabel: 'Peruntukan <span class="asterix">*</span>',
//                        xtype: 'radiogroup',
//                        columnWidth: [.5, .5],
//                        allowBlank:false,
//                        items: [{
//                                boxLabel: 'Supermarket',
//                                name: 'kd_peruntukan',
//                                inputValue: '0',
//                                id: 'vink_peruntukan_supermarket',
//                                checked:true
//                            }, {
//                                boxLabel: 'Distribusi',
//                                name: 'kd_peruntukan',
//                                inputValue: '1',
//                                id: 'vink_peruntukan_distribusi'
//                            }]
//                    },
                ]
            }, {
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [cbvinksuplier,
                    {
                        xtype: 'hidden',
                        name: 'kd_supplier',
                        id: 'id_vink_kode_supplier',
                        value: ''
                    },
                    {
                        xtype: 'datefield',
                        fieldLabel: 'Tgl Akhir',
                        emptyText: 'Tanggal Akhir',
                        name: 'tgl_retur_akhir',
                        id: 'vink_tgl_retur_akhir',
                        maxLength: 255,
                        anchor: '90%',
                        value: '',
                        format: 'd-M-Y'
                    }//,cbvrjmember
                ]
            }],
        buttons: [{
                text: 'Filter',
                formBind: true,
                handler: function() {
                    gridview_invoice_konsinyasi.store.load({
                        params: {
                            no_invoice: Ext.getCmp('id_cbvinnoinvoice_kons').getValue(),
                            tgl_awal: Ext.getCmp('vink_tgl_retur_awal').getValue(),
                            tgl_akhir: Ext.getCmp('vink_tgl_retur_akhir').getValue(),
                            kd_supplier: Ext.getCmp('id_vink_kode_supplier').getValue(),
//                            peruntukan_sup: Ext.getCmp('vink_peruntukan_supermarket').getValue(),
//                            peruntukan_dist: Ext.getCmp('vink_peruntukan_distribusi').getValue()
                        }
                    });
                }
            }, {
                text: 'Reset',
                formBind: true,
                handler: function() {
                    clearview_invoice_konsinyasi();
//                    Ext.getCmp('id_cbvrbproduk').setValue('');
//                    Ext.getCmp('id_cbvinnoinvoice_kons').setValue('');
//                    Ext.getCmp('id_cbvinksuplier').setValue('');
//                    Ext.getCmp('vink_tgl_retur_awal').setValue('');
//                    Ext.getCmp('vink_tgl_retur_akhir').setValue('');
//                    Ext.getCmp('id_vink_kode_supplier').setValue('');
//                    gridview_invoice_konsinyasi.store.removeAll();
                }
            }]
    };
    //End Header View Invoice
    // start GRID VIEW CREATE INVOICE	
    var strviewinvoice_kons = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_invoice',
                'tgl_invoice',
                'kd_produk',
                'nama_produk',
                'no_do',
                'no_po',
                'qty',
                'harga_supplier',
                'rp_dpp',
                'rp_jumlah',
                'nama_supplier',
                'rp_total_diskon',
                'rp_ajd_jumlah',
                'harga_net'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("konsinyasi_view_invoice/get_rows") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function(event, options, response, error) {

            }
        }
    });

    var searchviewinvoice_kons = new Ext.app.SearchField({
        store: strviewinvoice_kons,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        emptyText: 'No Invoice, Kode Produk',
        id: 'idsearchviewinvoice_kons'
    });

    var tbviewinvoice_kons = new Ext.Toolbar({
        items: [searchviewinvoice_kons]
    });

    var smview_invoice_konsinyasi = new Ext.grid.CheckboxSelectionModel();

    var gridview_invoice_konsinyasi = new Ext.grid.EditorGridPanel({
        id: 'gridview_invoice_konsinyasi',
        frame: true,
        border: true,
        stripeRows: true,
        sm: smview_invoice_konsinyasi,
        store: strviewinvoice_kons,
        loadMask: false,
        style: 'margin:0 auto;',
        height: 400,
        columns: [{
                header: "Nama Supplier",
                dataIndex: 'nama_supplier',
                sortable: true,
                width: 170
            }, {
                header: "No Invoice",
                dataIndex: 'no_invoice',
                sortable: true,
                width: 100
            }, {
                header: "Tanggal Invoice",
                dataIndex: 'tgl_invoice',
                sortable: true,
                width: 100
            }, {
                header: "NO RO",
                dataIndex: 'no_do',
                sortable: true,
                width: 100
            }, {
                header: "Kode Produk",
                dataIndex: 'kd_produk',
                sortable: true,
                width: 100
            }, {
                header: "Nama Produk",
                dataIndex: 'nama_produk',
                sortable: true,
                width: 250
            }, {
                header: "Qty Invoice",
                dataIndex: 'qty',
                sortable: true,
                width: 100
            }, {
                xtype: 'numbercolumn',
                header: "Harga Beli",
                dataIndex: 'harga_supplier',
                sortable: true,
                width: 100,
                format: '0,0'
            }, {
                xtype: 'numbercolumn',
                header: "Total Diskon",
                dataIndex: 'rp_total_diskon',
                sortable: true,
                width: 100,
                format: '0,0'
            }, {
                xtype: 'numbercolumn',
                header: "Harga Net",
                dataIndex: 'harga_net',
                sortable: true,
                width: 100,
                format: '0,0'
            }, {
                xtype: 'numbercolumn',
                header: "Harga Net(Exc)",
                dataIndex: 'rp_dpp',
                sortable: true,
                width: 100,
                format: '0,0'
            }, {
                xtype: 'numbercolumn',
                header: "Adjustment",
                dataIndex: 'rp_ajd_jumlah',
                sortable: true,
                width: 100,
                format: '0,0'
            }, {
                xtype: 'numbercolumn',
                header: "Jumlah",
                dataIndex: 'rp_jumlah',
                sortable: true,
                width: 100,
                format: '0,0'
            }],
        listeners: {
            'rowdblclick': function() {
                var sm = gridview_invoice_konsinyasi.getSelectionModel();
                var sel = sm.getSelections();

                if (sel.length > 0) {

                    Ext.Ajax.request({
                        url: '<?= site_url("konsinyasi_view_invoice/get_data_invoice") ?>/' + sel[0].get('no_invoice'),
                        method: 'POST',
                        params: {},
                        callback: function(opt, success, responseObj) {
                            var windowviewreturbeli = new Ext.Window({
                                title: 'View Create Invoice',
                                width: 850,
                                height: 500,
                                autoScroll: true,
                                html: responseObj.responseText
                            });

                            windowviewreturbeli.show();

                        }
                    });
                }
            }
        },
        tbar: tbviewinvoice_kons,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strviewinvoice_kons,
            displayInfo: true
        })
    });
    // end Grid View CREATE INVOICE

    // FORM PANEL
    var view_invoice_konsinyasi = new Ext.FormPanel({
        id: 'view_invoice_konsinyasi',
        border: false,
        frame: true,
        //autoScroll:true,	 
        bodyStyle: 'padding-right:20px;',
        labelWidth: 130,
        items: [{
                bodyStyle: {
                    margin: '10px 0px 15px 0px'
                },
                items: [headerview_invoice_konsinyasi,
                    gridview_invoice_konsinyasi
                ]
            }

        ]
    });
    view_invoice_konsinyasi.on('afterrender', function(){
        this.getForm().load({
            url: '<?= site_url("pembelian_create_po/get_form") ?>',
            success: function(form, action){
                var r = Ext.util.JSON.decode(action.response.responseText);
//                if(r.data.user_peruntukan === "0"){
//                    Ext.getCmp('vink_peruntukan_supermarket').setValue(true);
//                    Ext.getCmp('vink_peruntukan_supermarket').show();
//                    Ext.getCmp('vink_peruntukan_distribusi').hide();
//                }else if(r.data.user_peruntukan === "1"){
//                    Ext.getCmp('vink_peruntukan_distribusi').setValue(true);
//                    Ext.getCmp('vink_peruntukan_supermarket').hide();
//                    Ext.getCmp('vink_peruntukan_distribusi').show();
//                }else{
//                    Ext.getCmp('vink_peruntukan_supermarket').setValue(true);
//                    Ext.getCmp('vink_peruntukan_supermarket').show();
//                    Ext.getCmp('vink_peruntukan_distribusi').show();
//                }
            },
            failure: function(form, action){
                var de = Ext.util.JSON.decode(action.response.responseText);
                Ext.Msg.show({
                    title: 'Error',
                    msg: de.errMsg,
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK,
                    fn: function(btn){
                        if (btn == 'ok' && de.errMsg == 'Session Expired') {
                            window.location = '<?= site_url("auth/login") ?>';
                        }
                    }
                });
            }
        });
    });
    
    function clearview_invoice_konsinyasi(){
        Ext.getCmp('view_invoice_konsinyasi').getForm().reset();
        Ext.getCmp('view_invoice_konsinyasi').getForm().load({
            url: '<?= site_url("pembelian_create_po/get_form") ?>',
            success: function(form, action){
                var r = Ext.util.JSON.decode(action.response.responseText);
//                if(r.data.user_peruntukan === "0"){
//                    Ext.getCmp('vink_peruntukan_supermarket').setValue(true);
//                    Ext.getCmp('vink_peruntukan_supermarket').show();
//                    Ext.getCmp('vink_peruntukan_distribusi').hide();
//                }else if(r.data.user_peruntukan === "1"){
//                    Ext.getCmp('vink_peruntukan_distribusi').setValue(true);
//                    Ext.getCmp('vink_peruntukan_supermarket').hide();
//                    Ext.getCmp('vink_peruntukan_distribusi').show();
//                }else{
//                    Ext.getCmp('vink_peruntukan_supermarket').setValue(true);
//                    Ext.getCmp('vink_peruntukan_supermarket').show();
//                    Ext.getCmp('vink_peruntukan_distribusi').show();
//                }
            },
            failure: function(form, action){
                var de = Ext.util.JSON.decode(action.response.responseText);
                Ext.Msg.show({
                    title: 'Error',
                    msg: de.errMsg,
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK,
                    fn: function(btn){
                        if (btn == 'ok' && de.errMsg == 'Session Expired') {
                            window.location = '<?= site_url("auth/login") ?>';
                        }
                    }
                });
            }
        });
         gridview_invoice_konsinyasi.store.removeAll();
    }
</script>