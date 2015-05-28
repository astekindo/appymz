<?php if (!defined( 'BASEPATH')) exit( 'No direct script access allowed'); ?>
<script type="text/javascript">


    //-------- COMBOBOX PRODUK -----------------------
    //
    var str_cb_produk_so = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data: []
    });

    // GRID PANEL TWIN COMBOBOX produk Data Store
    var str_grid_produk_so = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_produk', 'nama_produk'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("laporan_purchase_order/search_produk") ?>',
            method: 'POST'
        }),
        listeners: {

            loadexception: function (event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    // SEARCH GRID PANEL TWIN COMBOBOX produk
    var search_cb_produk_so = new Ext.app.SearchField({
        store: str_grid_produk_so,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_search_cb_produk_so'
    });

    // GRID PANEL TWIN COMBOBOX produk
    var grid_cb_produk = new Ext.grid.GridPanel({
        store: str_grid_produk_so,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
            header: 'Kode Produk',
            dataIndex: 'kd_produk',
            width: 80,
            sortable: true

        }, {
            header: 'Nama Produk',
            dataIndex: 'nama_produk',
            width: 300,
            sortable: true
        }],
        tbar: new Ext.Toolbar({
            items: [search_cb_produk_so]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: str_grid_produk_so,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function () {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_so_kd_produk').setValue(sel[0].get('kd_produk'));
                    menu_so_produk.hide();
                }
            }
        }
    });

    // PANEL TWIN COMBOBOX produk
    Ext.ux.TwinCombolrpbpsProduk = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function () {
            //load store grid
            str_grid_produk_so.load();
            menu_so_produk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    // PANEL TWIN COMBOBOX produk
    var menu_so_produk = new Ext.menu.Menu();

    menu_so_produk.add(new Ext.Panel({
        title: 'Pilih Produk',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [grid_cb_produk],
        buttons: [{text: 'Close', handler: function () {menu_so_produk.hide();}}]
    }));

    //
    menu_so_produk.on('hide', function () {
        var sf = Ext.getCmp('id_search_cb_produk_so').getValue();
        if (sf != '') {
            Ext.getCmp('id_search_cb_produk_so').setValue('');
            search_cb_produk_so.onTrigger2Click();
        }
    });

    // TWIN COMBOBOX produk
    var cb_produk_so = new Ext.ux.TwinCombolrpbpsProduk({
        id: 'id_so_kd_produk',
        fieldLabel: 'Produk',
        store: str_cb_produk_so,
        mode: 'local',
        anchor: '90%',
        valueField: 'kd_produk',
        displayField: 'kd_produk',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: true,
        editable: false,
        hiddenName: 'kd_produk',
        emptyText: 'Pilih Produk'
    });

    //-------- COMBOBOX No. SO-----------------------
    //
    var str_cb_no_so = new Ext.data.ArrayStore({
        fields: ['no_so'],
        data : []
    });

    var str_grid_no_so = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_so',
                'kd_member',
                'tgl_so',
                'kirim_so',
                'kirim_alamat_so',
                'kirim_telp_so',
                'rp_total',
                'rp_diskon',
                'rp_bank_charge',
                'rp_ongkos_kirim',
                'rp_ongkos_pasang',
                'rp_total_bayar',
                'kd_voucher',
                'qty_voucher',
                'no_open_saldo',
                'rp_diskon_tambahan',
                'keterangan',
                'rp_kurang_bayar'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("penjualan_do/search_faktur") ?>',
            method: 'POST'
        }),
        listeners: {

            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var search_grid_no_so = new Ext.app.SearchField({
        store: str_grid_no_so,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_search_grid_no_so'
    });


    var grid_no_so = new Ext.grid.GridPanel({
        store: str_grid_no_so,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [
            {header:'No SO',dataIndex:'no_so',width: 120,sortable: true},
            {header:'Rp Kurang Bayar',dataIndex:'rp_kurang_bayar',width: 100,sortable: true},
            {header:'Keterangan',dataIndex:'keterangan',width: 200,sortable: true},
            {header:'Tgl So',dataIndex:'tgl_so',width: 80,sortable: true},
            {header:'Kirim',dataIndex:'kirim_so',width: 150,sortable: true},
            {header:'Alamat',dataIndex:'kirim_alamat_so',width: 200,sortable: true},
            {header:'Telp',dataIndex:'kirim_telp_so',width: 100,sortable: true},
        ],
        tbar: new Ext.Toolbar({
            items: [search_grid_no_so]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: str_grid_no_so,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_sales_tglfaktur').setValue(sel[0].get('tgl_so'));
                    Ext.getCmp('id_sales_nofaktur').setValue(sel[0].get('no_so'));

                    Ext.getCmp('id_pic_do').setValue(sel[0].get('kirim_so'));
                    Ext.getCmp('penjualandeliveryorder').getForm().findField('alm_penerima').setValue(sel[0].get('kirim_alamat_so'));
                    Ext.getCmp('id_telp_do').setValue(sel[0].get('kirim_telp_so'));
                    var vnoso=sel[0].get('no_so');
                    storesalesdo.reload({params:{no_so:vnoso}});

                    menu_no_so.hide();
                }
            }
        }
    });

    var menu_no_so = new Ext.menu.Menu();
    menu_no_so.add(new Ext.Panel({
        title: 'Pilih No Struk',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [grid_no_so],
        buttons: [{
            text: 'Close',
            handler: function(){
                menu_no_so.hide();
            }
        }]
    }));

    Ext.ux.TwinComboReturBeliSupplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            str_grid_no_so.load();
            menu_no_so.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menu_no_so.on('hide', function(){
        var sf = Ext.getCmp('id_search_grid_no_so').getValue();
        if( sf != ''){
            Ext.getCmp('id_search_grid_no_so').setValue('');
            search_grid_no_so.onTrigger2Click();
        }
    });

    var cb_no_so = new Ext.ux.TwinComboReturBeliSupplier({
        fieldLabel: 'No.Struk/SO',
        id: 'id_sales_nofaktur',
        store: str_cb_no_so,
        mode: 'local',
        valueField: 'no_so',
        displayField: 'no_so',
        typeAhead: true,
        triggerAction: 'all',
//        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'no_so',
        emptyText: 'Pilih No Struk/SO'
    });
// HEADER tanggal
    var header_tgl_so = {
        layout: 'column',
        border: false,
        items: [{
            columnWidth: .5,
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: {labelSeparator: ''},
            items: [{
                xtype: 'fieldset',
                title: 'Periode',
                autoHeight: true,
                items: [{
                    layout: 'column',
                    items: [{
                        columnWidth: .8,
                        layout: 'form',
                        border: false,
                        labelWidth: 100,
                        defaults: {labelSeparator: ''},
                        items: [{
                            xtype: 'datefield',
                            fieldLabel: 'Dari Tgl',
                            name: 'tanggal_dari',
                            allowBlank: false,
                            format: 'd-m-Y',
                            editable: false,
                            id: 'id_so_tanggal_dari',
                            anchor: '90%',
                            value: ''
                        },{
                            xtype: 'datefield',
                            fieldLabel: 'Sampai Tgl',
                            name: 'tanggal_sampai',
                            allowBlank: false,
                            editable: false,
                            format: 'd-m-Y',
                            id: 'id_so_tanggal_sampai',
                            anchor: '90%',
                            value: ''
                        },{
                            xtype: 'textfield',
                            fieldLabel: 'Min. Hari Gantung',
                            name: 'gantung_min',
                            allowBlank: true,
                            id: 'id_so_gantung_min',
                            anchor: '90%',
                            value: ''
                        },{
                            xtype: 'textfield',
                            fieldLabel: 'Max. Hari Gantung',
                            name: 'gantung_max',
                            allowBlank: true,
                            editable: false,
                            id: 'id_so_gantung_max',
                            anchor: '90%',
                            value: ''
                        },
                        cb_produk_so,
                        cb_no_so
                        ]
                    }]
                }]
            }]
        }]
    }

    // CHECKBOX Sort Order
    var so_sort_order = new Ext.form.Checkbox({
        xtype: 'checkbox',
        fieldLabel: 'Sort Order',
        boxLabel: 'Descending',
        name: 'sort_order',
        id: 'id_so_sort_order',
        checked: false,
        inputValue: '1',
        autoLoad: true
    });

    // CHECKBOX SO Belum Terkirim
    var so_status_kirim = new Ext.form.Checkbox({

        xtype: 'checkbox',
        fieldLabel: 'Hanya SO Belum Kirim?',
        boxLabel: 'Ya',
        name: 'status_kirim',
        id: 'id_so_status_kirim',
        checked: false,
        inputValue: '1',
        autoLoad: true
    });

    // HEADER checkboxes
    var header_contreng_so = {
        layout: 'column',
        border: false,
        items: [{
            columnWidth:.5,
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: {labelSeparator: ''},
            items: [{
                xtype: 'fieldset',
                title: 'Pilihan',
                autoHeight: true,
                items: [{
                    layout: 'column',
                    items: [{
                        columnWidth: .5,
                        layout: 'form',
                        border: false,
                        labelWidth: 100,
                        defaults: {labelSeparator: ''},
                        items: [so_sort_order,so_status_kirim]
                    }]
                }]
            }]
        }]
    }

    // HEADER
    var header_lap_penjualan_so = {
        buttonAlign: 'left',
        layout: 'form',
        border: false,
        labelWidth: 100,
        defaults: {
            labelSeparator: ''
        },
        items: [
            header_tgl_so,
            header_contreng_so
        ]
    };

    // PRINT
    var win_lap_penjualan_so = new Ext.Window({
        id: 'id_win_lap_penjualan_so',
        Title: 'Print Laporan Sales Order',
        closeAction: 'hide',
        width: 900,
        height: 500,
        layout: 'fit',
        border: false,
        html: '<iframe style="width:100%;height:490px;" id="iframe_penjualan_so" src=""></iframe>'
    });

    //  FORM PANEL
    var report_penjualan_so = new Ext.FormPanel({
        id: 'rpt_sales_order',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
            bodyStyle: {
                margin: '0px 0px 15px 0px'
            },
            items: [header_lap_penjualan_so]
        }],
        buttons: [{
            text: 'Print',
            formBind: true,
            handler: function () {
                Ext.getCmp('rpt_sales_order').getForm().submit({
                    url: '<?= site_url("laporan_sales_order/print_pdf") ?>',
                    scope: this,
                    waitMsg: 'Preparing Data...',
                    success: function(form, action){
                        var r = Ext.util.JSON.decode(action.response.responseText);
                        Ext.Msg.show({
                            title: 'Success',
                            msg: r.successMsg,
                            modal: true,
                            icon: Ext.Msg.INFO,
                            buttons: Ext.Msg.OK,
                            fn: function(btn){
                                window.open(r.printUrl, '_blank');
                                // win_lap_penjualan_so.show();
                                // Ext.getDom('iframe_penjualan_so').src = r.printUrl;
                            }
                        });

                        clear_lap_penjualan_so();
                    },
                    failure: function(form, action){
                        var fe = Ext.util.JSON.decode(action.response.responseText);
                        Ext.Msg.show({
                            title: 'Error',
                            msg: fe.errMsg,
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK,
                            fn: function(btn){
                                if (btn == 'ok' && fe.errMsg == 'Session Expired') {
                                    window.location = '<?= site_url("auth/login") ?>';
                                }
                            }
                        });

                    }
                });
            }
        }, {
            text: 'Cancel',
            handler: function () {
                clear_lap_penjualan_so();
            }
        }]
    });

    // CLEAR DATA FORM PANEL
    function clear_lap_penjualan_so() {
        Ext.getCmp('rpt_sales_order').getForm().reset();
    }
</script>