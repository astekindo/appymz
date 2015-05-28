<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script>
    // twin combo supplier
    var str_vin_supplier = new Ext.data.ArrayStore({
        fields: ['nama_supplier'],
        data: []
    });

    var strgrid_vin_supplier = new Ext.data.Store({
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

    var searchgrid_vin_supplier = new Ext.app.SearchField({
        store: strgrid_vin_supplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgrid_vin_supplier'
    });


    var grid_vin_supplier = new Ext.grid.GridPanel({
        store: strgrid_vin_supplier,
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
            items: [searchgrid_vin_supplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgrid_vin_supplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cbvinsuplier').setValue(sel[0].get('nama_supplier'));
                    Ext.getCmp('id_vin_kode_supplier').setValue(sel[0].get('kd_supplier'));
                    //strpembelianretur.removeAll();
                    menu_vin_supplier.hide();
                }
            }
        }
    });

    var menu_vin_supplier = new Ext.menu.Menu();
    menu_vin_supplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [grid_vin_supplier],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menu_vin_supplier.hide();
                }
            }]
    }));

    Ext.ux.TwinComboVINSupplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgrid_vin_supplier.load();
            menu_vin_supplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menu_vin_supplier.on('hide', function() {
        var sf = Ext.getCmp('id_searchgrid_vin_supplier').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgrid_vin_supplier').setValue('');
            searchgrid_vin_supplier.onTrigger2Click();
        }
    });

    var cbvinsuplier = new Ext.ux.TwinComboVINSupplier({
        fieldLabel: 'Nama Supplier',
        id: 'id_cbvinsuplier',
        store: str_vin_supplier,
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
    var strcbvinnoinvoice = new Ext.data.ArrayStore({
        fields: ['no_invoice'],
        data: []
    });
    var strgridvinnoinvoice = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_invoice', 'tgl_invoice'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("view_create_invoice/search_noinvoice") ?>',
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
    var searchgridvinnoinvoice = new Ext.app.SearchField({
        store: strgridvinnoinvoice,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridvinnoinvoice'
    });
    var gridvinnoinvoice = new Ext.grid.GridPanel({
        store: strgridvinnoinvoice,
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
            items: [searchgridvinnoinvoice]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridvinnoinvoice,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cbvinnoinvoice').setValue(sel[0].get('no_invoice'));
                    menuvrinnoinvoice.hide();
                }
            }
        }
    });
    var menuvrinnoinvoice = new Ext.menu.Menu();
    menuvrinnoinvoice.add(new Ext.Panel({
        title: 'Pilih No Invoice',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridvinnoinvoice],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menuvrinnoinvoice.hide();
                }
            }]
    }));
    Ext.ux.TwinCombonoinvoice = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridvinnoinvoice.load();
            menuvrinnoinvoice.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    menuvrinnoinvoice.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridvinnoinvoice').getValue();
        if (sf !== '') {
            Ext.getCmp('id_searchgridvinnoinvoice').setValue('');
            searchgridvinnoinvoice.onTrigger2Click();
        }
        ;
    }
    );
    var cbvinnoinvoice = new Ext.ux.TwinCombonoinvoice({
        fieldLabel: 'No Invoice',
        id: 'id_cbvinnoinvoice',
        store: strcbvinnoinvoice,
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
    var headerviewcreateinvoice = {
        layout: 'column',
        border: false,
        buttonAlign: 'left',
        items: [{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [cbvinnoinvoice, //cbvrbproduk,
                    {
                        xtype: 'datefield',
                        fieldLabel: 'Tgl Awal',
                        emptyText: 'Tanggal Awal',
                        name: 'tgl_retur_awal',
                        id: 'vin_tgl_retur_awal',
                        maxLength: 255,
                        anchor: '90%',
                        value: '',
                        format: 'd-M-Y'
                    },{
                        fieldLabel: 'Peruntukan <span class="asterix">*</span>',
                        xtype: 'radiogroup',
                        columnWidth: [.5, .5],
                        allowBlank:false,
                        items: [{
                                boxLabel: 'Supermarket',
                                name: 'kd_peruntukan',
                                inputValue: '0',
                                id: 'vin_peruntukan_supermarket',
                                checked:true
                            }, {
                                boxLabel: 'Distribusi',
                                name: 'kd_peruntukan',
                                inputValue: '1',
                                id: 'vin_peruntukan_distribusi'
                            }]
                    },
                ]
            }, {
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [cbvinsuplier,
                    {
                        xtype: 'hidden',
                        name: 'kd_supplier',
                        id: 'id_vin_kode_supplier',
                        value: ''
                    },
                    {
                        xtype: 'datefield',
                        fieldLabel: 'Tgl Akhir',
                        emptyText: 'Tanggal Akhir',
                        name: 'tgl_retur_akhir',
                        id: 'vin_tgl_retur_akhir',
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
                    gridviewcreateinvoice.store.load({
                        params: {
                            no_invoice: Ext.getCmp('id_cbvinnoinvoice').getValue(),
                            tgl_awal: Ext.getCmp('vin_tgl_retur_awal').getValue(),
                            tgl_akhir: Ext.getCmp('vin_tgl_retur_akhir').getValue(),
                            kd_supplier: Ext.getCmp('id_vin_kode_supplier').getValue(),
                            peruntukan_sup: Ext.getCmp('vin_peruntukan_supermarket').getValue(),
                            peruntukan_dist: Ext.getCmp('vin_peruntukan_distribusi').getValue()
                        }
                    });
                }
            }, {
                text: 'Reset',
                formBind: true,
                handler: function() {
                    clearviewcreateinvoice();
//                    Ext.getCmp('id_cbvrbproduk').setValue('');
//                    Ext.getCmp('id_cbvinnoinvoice').setValue('');
//                    Ext.getCmp('id_cbvinsuplier').setValue('');
//                    Ext.getCmp('vin_tgl_retur_awal').setValue('');
//                    Ext.getCmp('vin_tgl_retur_akhir').setValue('');
//                    Ext.getCmp('id_vin_kode_supplier').setValue('');
//                    gridviewcreateinvoice.store.removeAll();
                }
            }]
    };
    //End Header View Invoice
    // start GRID VIEW CREATE INVOICE	
    var strviewinvoice = new Ext.data.Store({
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
            url: '<?= site_url("view_create_invoice/get_rows") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function(event, options, response, error) {

            }
        }
    });

    var searchviewinvoice = new Ext.app.SearchField({
        store: strviewinvoice,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        emptyText: 'No Invoice, Kode Produk',
        id: 'idsearchviewinvoice'
    });
    strviewinvoice.on('load',function(){
        strviewinvoice.setBaseParam('no_invoice',Ext.getCmp('id_cbvinnoinvoice').getValue());
        strviewinvoice.setBaseParam('tgl_awal',Ext.getCmp('vin_tgl_retur_awal').getValue());
        strviewinvoice.setBaseParam('tgl_akhir',Ext.getCmp('vin_tgl_retur_akhir').getValue());
        strviewinvoice.setBaseParam('kd_supplier',Ext.getCmp('id_vin_kode_supplier').getValue());
        strviewinvoice.setBaseParam('peruntukan_sup',Ext.getCmp('vin_peruntukan_supermarket').getValue());
        strviewinvoice.setBaseParam('peruntukan_dist',Ext.getCmp('vin_peruntukan_distribusi').getValue());
    });

    var tbviewinvoice = new Ext.Toolbar({
        items: [searchviewinvoice]
    });

    var smviewcreateinvoice = new Ext.grid.CheckboxSelectionModel();

    var gridviewcreateinvoice = new Ext.grid.EditorGridPanel({
        id: 'gridviewcreateinvoice',
        frame: true,
        border: true,
        stripeRows: true,
        sm: smviewcreateinvoice,
        store: strviewinvoice,
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
                var sm = gridviewcreateinvoice.getSelectionModel();
                var sel = sm.getSelections();

                if (sel.length > 0) {

                    Ext.Ajax.request({
                        url: '<?= site_url("view_create_invoice/get_data_invoice") ?>/' + sel[0].get('no_invoice'),
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
        tbar: tbviewinvoice,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strviewinvoice,
            displayInfo: true
        })
    });
    // end Grid View CREATE INVOICE

    // FORM PANEL
    var viewcreateinvoice = new Ext.FormPanel({
        id: 'viewcreateinvoice',
        border: false,
        frame: true,
        //autoScroll:true,	 
        bodyStyle: 'padding-right:20px;',
        labelWidth: 130,
        items: [{
                bodyStyle: {
                    margin: '10px 0px 15px 0px'
                },
                items: [headerviewcreateinvoice,
                    gridviewcreateinvoice
                ]
            }

        ]
    });
    viewcreateinvoice.on('afterrender', function(){
        this.getForm().load({
            url: '<?= site_url("pembelian_create_po/get_form") ?>',
            success: function(form, action){
                var r = Ext.util.JSON.decode(action.response.responseText);
                if(r.data.user_peruntukan === "0"){
                    Ext.getCmp('vin_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('vin_peruntukan_supermarket').show();
                    Ext.getCmp('vin_peruntukan_distribusi').hide();
                }else if(r.data.user_peruntukan === "1"){
                    Ext.getCmp('vin_peruntukan_distribusi').setValue(true);
                    Ext.getCmp('vin_peruntukan_supermarket').hide();
                    Ext.getCmp('vin_peruntukan_distribusi').show();
                }else{
                    Ext.getCmp('vin_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('vin_peruntukan_supermarket').show();
                    Ext.getCmp('vin_peruntukan_distribusi').show();
                }
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
    
    function clearviewcreateinvoice(){
        Ext.getCmp('viewcreateinvoice').getForm().reset();
        Ext.getCmp('viewcreateinvoice').getForm().load({
            url: '<?= site_url("pembelian_create_po/get_form") ?>',
            success: function(form, action){
                var r = Ext.util.JSON.decode(action.response.responseText);
                if(r.data.user_peruntukan === "0"){
                    Ext.getCmp('vin_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('vin_peruntukan_supermarket').show();
                    Ext.getCmp('vin_peruntukan_distribusi').hide();
                }else if(r.data.user_peruntukan === "1"){
                    Ext.getCmp('vin_peruntukan_distribusi').setValue(true);
                    Ext.getCmp('vin_peruntukan_supermarket').hide();
                    Ext.getCmp('vin_peruntukan_distribusi').show();
                }else{
                    Ext.getCmp('vin_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('vin_peruntukan_supermarket').show();
                    Ext.getCmp('vin_peruntukan_distribusi').show();
                }
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
         gridviewcreateinvoice.store.removeAll();
    }
</script>