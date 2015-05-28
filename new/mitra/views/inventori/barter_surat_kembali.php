<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>

<script type="text/javascript">
/**
 * combo barter nomor pengantar
 */
    var strCbSBKBukti = new Ext.data.ArrayStore({
        fields: ['no_sb'],
        data : []
    });

    var strGridSBKBukti = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_transfer_stok',
                'no_sb',
                'tanggal',
                'pic_supplier',
                'qty_tr',
                'qty_kirim',
                'qty_sb',
                'qty_kembali',
                'keterangan'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("barterbarang/get_rows_kembali") ?>',
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

    var searchGridSBKBukti = new Ext.app.SearchField({
        store: strGridSBKBukti,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgrid_kembali_sb'
    });

    var gridSBKBukti = new Ext.grid.GridPanel({
        store: strGridSBKBukti,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [
            {header:'No. Bukti Transfer',dataIndex:'no_transfer_stok',width: 80,sortable: true},
            {header:'No. SB',dataIndex:'no_sb',width: 80,sortable: true},
            {header:'Tanggal',dataIndex:'tanggal',width: 80,sortable: true},
            {header:'PIC Penerima',dataIndex:'pic_supplier',width: 80,sortable: true},
            {header:'Qty barter',dataIndex:'qty_tr',width: 80,sortable: true},
            {header:'Qty kirim',dataIndex:'qty_sb',width: 80,sortable: true},
            {header:'Qty kembali',dataIndex:'qty_kembali',width: 80,sortable: true},
            {header:'Keterangan',dataIndex:'keterangan',width: 80,sortable: true}
        ],
        tbar: new Ext.Toolbar({
            items: [searchGridSBKBukti]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strGridSBKBukti,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){
                resetFormPengembalian();
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_kembali_tanggal_sb').setValue(sel[0].get('tanggal'));
                    var tanggal_kembali = new Date(Date.parse(sel[0].get('tanggal')));
                    Ext.getCmp('id_kembali_sb_tanggal').setMinValue(tanggal_kembali);
                    Ext.getCmp('id_kembali_no_trf').setValue(sel[0].get('no_transfer_stok'));
                    Ext.getCmp('id_kembali_no_sb').setValue(sel[0].get('no_sb'));

                    menuSBKBukti.hide();
                }
            }
        }
    });

    var menuSBKBukti = new Ext.menu.Menu();
    menuSBKBukti.add(new Ext.Panel({
        title: 'Pilih No. SB',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridSBKBukti],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menuSBKBukti.hide();
                }
            }]
    }));

    Ext.ux.TwinComboSBKBukti = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            strGridSBKBukti.load();
            menuSBKBukti.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menuSBKBukti.on('hide', function(){
        var sf = Ext.getCmp('id_searchgrid_kembali_sb').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgrid_kembali_sb').setValue('');
            searchGridSBKBukti.onTrigger2Click();
        }
    });

/**
 * combo barter kode produk
 */
    var strComboSBKProduk = new Ext.data.ArrayStore({
        fields: ['kd_produk_awal', 'qty_kembali'],
        data : []
    });

    var strGridSBKProduk = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_transfer_stok',
                'kd_produk_awal',
                'kd_satuan_awal',
                'nm_satuan_awal',
                'nama_produk_awal',
                'nama_sub_awal',
                'alias_sub_awal',
                'kd_produk_tujuan',
                'kd_satuan_tujuan',
                'nm_satuan_tujuan',
                'nama_produk_tujuan',
                'qty',
                'qty_kirim',
                'qty_sb',
                'qty_kembali',
                'qty_oh'
            ],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("barterbarang/get_rows_detail_kembali") ?>',
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

    var searchGridSBKProduk = new Ext.app.SearchField({
        width: 220,
        id: 'sjk_search_query',
        store: strGridSBKProduk
    });

    searchGridSBKProduk.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';

            var fid = Ext.getCmp('id_kembali_no_sb').getValue();
            var o = { start: 0, no_sb: fid };

            this.store.baseParams = this.store.baseParams || {};
            this.store.baseParams[this.paramName] = '';
            this.store.reload({
                params : o
            });
            this.triggers[0].hide();
            this.hasSearch = false;
        }
    };

    searchGridSBKProduk.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }

        // Get the value of search field
        var fid = Ext.getCmp('id_kembali_no_sb').getValue();
        var o = { start: 0, no_sb: fid };

        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params:o});
        this.hasSearch = true;
        this.triggers[0].show();
    };

    // top toolbar
    var tbGridSBKProduk = new Ext.Toolbar({
        items: [searchGridSBKProduk]
    });

    var gridSBKProduk = new Ext.grid.GridPanel({
        store: strGridSBKProduk,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
            header: 'Kode Produk',
            dataIndex: 'kd_produk_awal',
            width: 100,
            sortable: true

        }, {
            header: 'Nama Produk',
            dataIndex: 'nama_produk_awal',
            width: 250,
            sortable: true
        }, {
            header: 'Satuan',
            dataIndex: 'nm_satuan_awal',
            width: 60
        }, {
            header: 'Qty Kirim',
            dataIndex: 'qty_kirim',
            width: 60,
            sortable: true
        }, {
            header: 'Qty SB',
            dataIndex: 'qty_sb',
            width: 60,
            sortable: true
        }],
        tbar:tbGridSBKProduk,
        listeners: {
            'rowdblclick': function(){
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_grid_sbk_kd_produk_awal').setValue(sel[0].get('kd_produk_awal'));
                    Ext.getCmp('id_grid_sbk_nama_produk_awal').setValue(sel[0].get('nama_produk_awal'));
                    Ext.getCmp('id_grid_sbk_nm_satuan_awal').setValue(sel[0].get('nm_satuan_awal'));
                    Ext.getCmp('id_grid_sbk_kd_produk_tujuan').setValue(sel[0].get('kd_produk_tujuan'));
                    Ext.getCmp('id_grid_sbk_nama_produk_tujuan').setValue(sel[0].get('nama_produk_tujuan'));
                    Ext.getCmp('id_grid_sbk_nm_satuan_tujuan').setValue(sel[0].get('nm_satuan_tujuan'));
                    Ext.getCmp('id_grid_sbk_qty_kirim').setValue(sel[0].get('qty_sb'));
                    Ext.getCmp('id_grid_sbk_qty_kembali').setValue('0');
                    Ext.getCmp('id_grid_sbk_qty_batal').setValue('0');
                    Ext.getCmp('id_grid_sbk_qty_kembali').focus();
                    menuSBKProduk.hide();
                }
            }
        }
    });

    var menuSBKProduk = new Ext.menu.Menu();
    menuSBKProduk.add(new Ext.Panel({
        title: 'Pilih Barang',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 600,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridSBKProduk],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menuSBKProduk.hide();
                }
            }]
    }));

    Ext.ux.TwinComboSBKProduk = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            if(Ext.getCmp('id_kembali_no_sb').getValue() == ''){
                Ext.Msg.show({
                    title: 'Error',
                    msg: 'Silahkan pilih No SB terlebih dulu',
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK
                });
                return;
            }
            //load store grid
            strGridSBKProduk.load({
                params: {
                    no_sb: Ext.getCmp('id_kembali_no_sb').getValue()
                }
            });
            menuSBKProduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

/**
 * Kombo sub blok asal
 */
    var strBarterTerimaSubBlok = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['sub', 'nama_sub'],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_receive_order/get_sub_blok") ?>',
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

    var strGridBarterTerimaSubBlok = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'sub',
                'nama_sub',
                'kd_sub_blok',
                'kd_blok',
                'kd_lokasi',
                'nama_lokasi',
                'nama_blok',
                'nama_sub_blok',
                'kapasitas'
            ],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("penjualan_sj/get_lokasi_by_produk") ?>',
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
    strGridBarterTerimaSubBlok.on('load', function (argument) {
        strGridBarterTerimaSubBlok.setBaseParam('kd_produk', Ext.getCmp('id_grid_sbk_kd_produk_tujuan').getValue());
    });

    // search field
    var searchGridBarterTerimaSubBlok = new Ext.app.SearchField({
        store: strGridBarterTerimaSubBlok,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220
    });

    // top toolbar
    var toolbarGridBarterTerimaSubBlok = new Ext.Toolbar({
        items: [searchGridBarterTerimaSubBlok]
    });

    var gridBarterTerimaSubBlok = new Ext.grid.GridPanel({
        store: strGridBarterTerimaSubBlok,
        stripeRows: true,
        frame: true,
        border:true,
        tbar: toolbarGridBarterTerimaSubBlok,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strGridBarterTerimaSubBlok,
            displayInfo: true
        }),
        columns: [{
                dataIndex: 'kd_lokasi',
                hidden: true
            }, {
                dataIndex: 'kd_blok',
                hidden: true
            }, {
                dataIndex: 'kd_sub_blok',
                hidden: true
            }, {
                header: 'Kode',
                dataIndex: 'sub',
                width: 90,
                sortable: true

            }, {
                header: 'Sub Blok Lokasi',
                dataIndex: 'nama_sub',
                width: 200,
                sortable: true
            }],
        listeners: {
            'rowdblclick': function(){
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.Ajax.request({
                        url: '<?= site_url("penjualan_sj/get_qty_by_lokasi") ?>',
                        method: 'POST',
                        params: {
                            lokasi: sel[0].get('kd_lokasi'),
                            blok: sel[0].get('kd_blok'),
                            subblok: sel[0].get('kd_sub_blok'),
                            kd_produk: Ext.getCmp('id_grid_sbk_kd_produk_tujuan').getValue()
                        },
                        callback:function(opt,success,responseObj){
                            var scn = Ext.util.JSON.decode(responseObj.responseText);
                            if(scn.success==true){
                                Ext.getCmp('id_grid_sbk_qty_sub_awal').setValue(scn.data.qty_oh);
                            }
                        }
                    });

                    Ext.getCmp('id_grid_sbk_sub_awal').setValue(sel[0].get('sub'));
                    Ext.getCmp('id_grid_sbk_nama_sub_awal').setValue(sel[0].get('nama_sub'));
                    Ext.getCmp('id_grid_sbk_qty_kembali').focus();
                    menuBarterTerimaSubBlok.hide();
                }
            }
        }
    });

    var menuBarterTerimaSubBlok = new Ext.menu.Menu();
    menuBarterTerimaSubBlok.add(new Ext.Panel({
        title: 'Pilih Sub Blok Lokasi',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 350,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridBarterTerimaSubBlok],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menuBarterTerimaSubBlok.hide();
                }
            }]
    }));

    Ext.ux.TwinComboBarterTerimaSubBlok = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            strGridBarterTerimaSubBlok.load({
                params:{
                    kd_produk: Ext.getCmp('id_grid_sbk_kd_produk_tujuan').getValue()
                }
            });
            menuBarterTerimaSubBlok.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    var comboBarterTerimaSubBlok = new Ext.ux.TwinComboBarterTerimaSubBlok({
        id: 'id_grid_sbk_sub_awal',
        store: strBarterTerimaSubBlok,
        valueField: 'sub',
        displayField: 'sub',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        hiddenName: 'sub',
        emptyText: 'Pilih Sub Blok',
        listeners: {
            'expand': function(){
                strBarterTerimaSubBlok.load({
                    params: {
                        kd_produk: Ext.getCmp('id_grid_sbk_kd_produk_tujuan').getValue()
                    }
                });
            }
        }
    });

/**
 * Kombo sub blok tujuan
 */
    var strBarterBatalSubBlok = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['sub', 'nama_sub'],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_receive_order/get_sub_blok") ?>',
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

    var strGridBarterBatalSubBlok = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'sub',
                'nama_sub',
                'kd_sub_blok',
                'kd_blok',
                'kd_lokasi',
                'nama_lokasi',
                'nama_blok',
                'nama_sub_blok',
                'kapasitas'
            ],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("penjualan_sj/get_lokasi_by_produk") ?>',
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

    strGridBarterBatalSubBlok.on('load', function (argument) {
        strGridBarterBatalSubBlok.setBaseParam('kd_produk', Ext.getCmp('id_grid_sbk_kd_produk_awal').getValue());
    });

    // search field
    var searchGridBarterBatalSubBlok = new Ext.app.SearchField({
        store: strGridBarterBatalSubBlok,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220
    });

    // top toolbar
    var toolbarGridBarterBatalSubBlok = new Ext.Toolbar({
        items: [searchGridBarterBatalSubBlok]
    });

    var gridBarterBatalSubBlok = new Ext.grid.GridPanel({
        store: strGridBarterBatalSubBlok,
        stripeRows: true,
        frame: true,
        border:true,
        tbar: toolbarGridBarterBatalSubBlok,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strGridBarterBatalSubBlok,
            displayInfo: true
        }),
        columns: [{
                dataIndex: 'kd_lokasi',
                hidden: true
            }, {
                dataIndex: 'kd_blok',
                hidden: true
            }, {
                dataIndex: 'kd_sub_blok',
                hidden: true
            }, {
                header: 'Kode',
                dataIndex: 'sub',
                width: 90,
                sortable: true

            }, {
                header: 'Sub Blok Lokasi',
                dataIndex: 'nama_sub',
                width: 200,
                sortable: true
            }],
        listeners: {
            'rowdblclick': function(){
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.Ajax.request({
                        url: '<?= site_url("penjualan_sj/get_qty_by_lokasi") ?>',
                        method: 'POST',
                        params: {
                            lokasi: sel[0].get('kd_lokasi'),
                            blok: sel[0].get('kd_blok'),
                            subblok: sel[0].get('kd_sub_blok'),
                            kd_produk: Ext.getCmp('id_grid_sbk_kd_produk_awal').getValue()
                        },
                        callback:function(opt,success,responseObj){
                            var scn = Ext.util.JSON.decode(responseObj.responseText);
                            if(scn.success==true){
                                Ext.getCmp('id_grid_sbk_qty_sub_batal').setValue(scn.data.qty_oh);
                            }
                        }
                    });

                    Ext.getCmp('id_grid_sbk_sub_batal').setValue(sel[0].get('sub'));
                    Ext.getCmp('id_grid_sbk_nama_sub_batal').setValue(sel[0].get('nama_sub'));
                    Ext.getCmp('id_grid_sbk_qty_batal').focus();
                    menuBarterBatalSubBlok.hide();
                }
            }
        }
    });

    var menuBarterBatalSubBlok = new Ext.menu.Menu();
    menuBarterBatalSubBlok.add(new Ext.Panel({
        title: 'Pilih Sub Blok Lokasi',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 350,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridBarterBatalSubBlok],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menuBarterBatalSubBlok.hide();
                }
            }]
    }));

    Ext.ux.TwinComboBarterBatalSubBlok = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            strGridBarterBatalSubBlok.load({
                params:{
                    kd_produk: Ext.getCmp('id_grid_sbk_kd_produk_awal').getValue()
                }
            });
            menuBarterBatalSubBlok.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    var comboBarterBatalSubBlok = new Ext.ux.TwinComboBarterBatalSubBlok({
        id: 'id_grid_sbk_sub_batal',
        store: strBarterBatalSubBlok,
        valueField: 'sub',
        displayField: 'sub',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        hiddenName: 'sub',
        emptyText: 'Pilih Sub Blok',
        listeners: {
            'expand': function(){
                strBarterBatalSubBlok.load({
                    params: {
                        kd_produk: Ext.getCmp('id_grid_sbk_kd_produk_awal').getValue()
                    }
                });
            }
        }
    });

/**
 * grid utama
 */
    var strGridBarterKembali = new Ext.data.Store({
        autoSave:false,
        reader: new Ext.data.JsonReader({
            fields: [
                'no_transfer_stok',
                'kd_produk_awal',
                'kd_satuan_awal',
                'nm_satuan_awal',
                'nama_produk_awal',
                'nama_sub_awal',
                'alias_sub_awal',
                'kd_produk_tujuan',
                'kd_satuan_tujuan',
                'nm_satuan_tujuan',
                'nama_produk_tujuan',
                'qty',
                'qty_kirim',
                'qty_sb',
                'qty_kembali',
                'qty_oh'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        writer: new Ext.data.JsonWriter(
        {
            encode: true,
            writeAllFields: true
        })
    });

    var editorGridBarterKembali = new Ext.ux.grid.RowEditor({
        saveText: 'Update',
        listeners: {
            'validateedit': function(object, changes, r, rowIndex) {
                var qty_kirim   = Ext.getCmp('id_grid_sbk_qty_kirim').getValue();
                var qty_kembali = Ext.getCmp('id_grid_sbk_qty_kembali').getValue();
                var qty_batal   = Ext.getCmp('id_grid_sbk_qty_batal').getValue();
                if(Number(qty_kembali) + Number(qty_batal) < Number(qty_kirim)) {
                    Ext.Msg.show({
                        title: 'Error',
                        msg: 'Quantity Terima + Quantity Batal harus sama dengan Quantity Kirim!',
                        modal: true,
                        icon: Ext.Msg.ERROR,
                        buttons: Ext.Msg.OK,
                        fn: function(btn) {
                            if(btn == 'ok') {
                                gridBarterKembali.getSelectionModel().selectRow(rowIndex);
                                editorGridBarterKembali.startEditing(rowIndex);
                                Ext.getCmp('id_grid_sbk_qty_kembali').focus();
                            }
                        }
                    });
                }
            }
        }
    });

    var gridBarterKembali = new Ext.grid.GridPanel({
        store: strGridBarterKembali ,
        stripeRows: true,
        height: 200,
        frame: true,
        border:true,
        plugins:[editorGridBarterKembali],
        columns: [{
            header: 'Kode produk',
            dataIndex: 'kd_produk',
            width: 110,
            editor: new Ext.ux.TwinComboSBKProduk({
                id: 'id_grid_sbk_kd_produk_awal',
                store: strComboSBKProduk,
                mode: 'local',
                valueField: 'kd_produk',
                displayField: 'kd_produk',
                typeAhead: true,
                triggerAction: 'all',
                allowBlank: false,
                editable: false,
                hiddenName: 'kd_produk',
                emptyText: 'Pilih produk'

            })

        }, {
            header: 'Nama Barang',
            dataIndex: 'nama_produk_awal',
            width: 250,
            editor: new Ext.form.TextField({
                fieldClass: 'readonly-input',
                readOnly: true,
                id: 'id_grid_sbk_nama_produk_awal'
            })
        }, {
            header: 'Satuan',
            dataIndex: 'nm_satuan_awal',
            width: 60,
            editor: new Ext.form.TextField({
                fieldClass: 'readonly-input',
                readOnly: true,
                id: 'id_grid_sbk_nm_satuan_awal'
            })
        }, {
            header: 'Qty Barter',
            dataIndex: 'qty_kirim',
            width: 60,
            editor: new Ext.form.TextField({
                fieldClass: 'readonly-input',
                readOnly: true,
                id: 'id_grid_sbk_qty_kirim'
            })
        }, {
            xtype: 'numbercolumn',
            header: 'Qty Terima',
            dataIndex: 'qty_kembali',
            width: 80,
            align: 'right',
            sortable: true,
            format: '0,0',
            editor: {
                xtype: 'numberfield',
                id: 'id_grid_sbk_qty_kembali',
                allowBlank: false,
                selectOnFocus:true,
                listeners:{
                    'change': function(){
                        var qty_kembali = Ext.getCmp('id_grid_sbk_qty_kirim').getValue();
                        if(qty_kembali < 1) {
                            resetFormPengembalian();
                            Ext.Msg.show({
                                title: 'Error',
                                msg: 'Quantity kirim kosong!',
                                modal: true,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OK
                            });
                        }

                        if(this.getValue() == '') {
                            this.setValue('0');
                            return;
                        }
                        if( this.getValue() > qty_kembali ) {
                            this.setValue('0');
                            Ext.Msg.show({
                                title: 'Error',
                                msg: 'Quantity Kembali Melebihi Quantity Kirim!',
                                modal: true,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OK,
                                fn: function(btn) {
                                    if(btn == 'ok') {
                                        Ext.getCmp('id_grid_sbk_qty_kembali').focus();
                                    }
                                }
                            });
                        }

                    },
                    'specialKey': function( field, e ) {
                        Ext.getCmp('id_grid_sbk_qty_kembali').focus();
                        if ( e.getKey() == e.RETURN || e.getKey() == e.ENTER ) {
                            this.fireEvent('change');
                        }
                    }
                }
            }
        }, {
            header: 'Sub Blok Barang Terima',
            dataIndex: 'sub_terima',
            width: 100,
            editor: comboBarterTerimaSubBlok
        }, {
            header: 'Nama Sub Blok',
            dataIndex: 'nama_sub_terima',
            width: 150,
            editor: new Ext.form.TextField({
                fieldClass: 'readonly-input',
                readOnly: true,
                id: 'id_grid_sbk_nama_sub_awal'
            })
        }, {
            header: 'Stok',
            dataIndex: 'qty_sub_terima',
            width: 60,
            editor: new Ext.form.TextField({
                fieldClass: 'readonly-input',
                readOnly: true,
                id: 'id_grid_sbk_qty_sub_awal'
            })
        }, {
            header: 'Kode produk barter',
            dataIndex: 'kd_produk_tujuan',
            width: 110,
            editor: new Ext.form.TextField({
                fieldClass: 'readonly-input',
                readOnly: true,
                id: 'id_grid_sbk_kd_produk_tujuan'
            })
        }, {
            header: 'Nama Barang',
            dataIndex: 'nama_produk_tujuan',
            width: 250,
            editor: new Ext.form.TextField({
                fieldClass: 'readonly-input',
                readOnly: true,
                id: 'id_grid_sbk_nama_produk_tujuan'
            })
        }, {
            header: 'Satuan',
            dataIndex: 'nm_satuan_tujuan',
            width: 60,
            editor: new Ext.form.TextField({
                fieldClass: 'readonly-input',
                readOnly: true,
                id: 'id_grid_sbk_nm_satuan_tujuan'
            })
        }, {
            xtype: 'numbercolumn',
            header: 'Qty Batal',
            dataIndex: 'qty_batal',
            width: 80,
            align: 'right',
            sortable: true,
            format: '0,0',
            editor: {
                xtype: 'numberfield',
                id: 'id_grid_sbk_qty_batal',
                allowBlank: false,
                selectOnFocus:true,
                listeners:{
                    'change': function(){
                        var qty_kirim   = Ext.getCmp('id_grid_sbk_qty_kirim').getValue();
                        var qty_kembali = Ext.getCmp('id_grid_sbk_qty_kembali').getValue();
                        var qty_batal = this.getValue();
                        if(qty_batal == '') {
                            this.setValue('0');
                            return;
                        }

                        if((Number(qty_kembali) + Number(qty_batal) ) > qty_kirim  ){
                            this.setValue('0');
                            Ext.Msg.show({
                                title: 'Error',
                                msg: 'Quantity Kembali + Quantity Batal Barter Melebihi Quantity Kirim !',
                                modal: true,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OK,
                                fn: function(btn) {
                                    if(btn == 'ok') {
                                        Ext.getCmp('id_grid_sbk_qty_kembali').focus();
                                    }
                                }
                            });

                        } else {
                            Ext.getCmp('id_grid_sbk_keterangan').focus();
                        }

                    },
                    'specialKey': function( field, e ) {
                        Ext.getCmp('id_grid_sbk_qty_batal').focus();
                        if ( e.getKey() == e.RETURN || e.getKey() == e.ENTER ) {
                            this.fireEvent('change');
                        }
                    }
                }
            }
        }, {
            header: 'Sub Blok Barang Batal',
            dataIndex: 'sub_batal',
            width: 100,
            editor: comboBarterBatalSubBlok
        }, {
            header: 'Nama Sub Blok',
            dataIndex: 'nama_sub_batal',
            width: 150,
            editor: new Ext.form.TextField({
                fieldClass: 'readonly-input',
                readOnly: true,
                id: 'id_grid_sbk_nama_sub_batal'
            })
        }, {
            header: 'Stok',
            dataIndex: 'qty_sub_batal',
            width: 60,
            editor: new Ext.form.TextField({
                fieldClass: 'readonly-input',
                readOnly: true,
                id: 'id_grid_sbk_qty_sub_batal'
            })
        }, {
            header: 'Keterangan',
            dataIndex: 'keterangan',
            width: 300,
            editor: new Ext.form.TextField({
//                allowBlank: false,
                id: 'id_grid_sbk_keterangan'
            })
        }],
        tbar: [{
                icon: BASE_ICONS + 'add.png',
                text: 'Add',
                handler: function(){
                    if(Ext.getCmp('id_kembali_no_sb').getValue() == ''){
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Silahkan pilih no SB terlebih dulu',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                        });
                        return;
                    }
                    var rowSuratJalanKembaliEdit = new gridBarterKembali.store.recordType({
                        no_sb:'',
                        kd_produk : '',
                        qty: ''
                    });
                    editorGridBarterKembali.stopEditing();
                    strGridBarterKembali .insert(0, rowSuratJalanKembaliEdit);
                    gridBarterKembali.getView().refresh();
                    gridBarterKembali.getSelectionModel().selectRow(0);
                    editorGridBarterKembali.startEditing(0);
                }
            }, {
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                disabled: true,
                handler: function(){
                    editorGridBarterKembali.stopEditing();
                    var s = gridBarterKembali.getSelectionModel().getSelections();
                    for(var i = 0, r; r = s[i]; i++){
                        strGridBarterKembali .remove(r);
                    }
                }
            }]
    });

    gridBarterKembali.getSelectionModel().on('selectionchange', function(sm){
        gridBarterKembali.removeBtn.setDisabled(sm.getCount() < 1);
    });

    var barterkembali= new Ext.FormPanel({
        id: 'barterkembali',
        border: false,
        frame: true,
        autoScroll:true,
        monitorValid: true,
        labelWidth: 130,
        items:[{
            bodyStyle: { margin: '0px 0px 15px 0px' },
            items: [{
                layout: 'column',
                border: false,
                items: [{
                    columnWidth: .4,
                    layout: 'form',
                    border: false,
                    labelWidth: 100,
                    defaults: { labelSeparator: ''},
                    items: [
                        new Ext.ux.TwinComboSBKBukti({
                            fieldLabel: 'No. SB<span class="asterix">*</span>',
                            id: 'id_kembali_no_sb',
                            store: strCbSBKBukti,
                            mode: 'local',
                            valueField: 'no_sb',
                            displayField: 'no_sb',
                            typeAhead: true,
                            triggerAction: 'all',
                            allowBlank: false,
                            editable: false,
                            anchor: '90%',
                            hiddenName: 'no_sb',
                            emptyText: 'Pilih No. SB'
                        }),
                        {
                            xtype: 'datefield',
                            fieldLabel: 'Tanggal Kembali<span class="asterix">*</span>',
                            name: 'tanggal_kembali',
                            id:'id_kembali_sb_tanggal',
                            allowBlank:false,
                            maxValue: new Date(),
                            format:'d-M-Y',
                            editable:false,
                            anchor: '90%'
                        }, {
                            xtype: 'textfield',
                            fieldLabel: 'Penerima<span class="asterix">*</span>',
                            name: 'penerima',
                            allowBlank: false,
                            id: 'id_kembali_sb_penerima',
                            maxLength: 255,
                            anchor: '90%',
                            value:''
                        }
                    ]
                }, {
                    columnWidth: .4,
                    layout: 'form',
                    border: false,
                    labelWidth: 100,
                    defaults: { labelSeparator: ''},
                    items: [{
                        xtype: 'hidden',
                        name: 'no_transfer_stok',
                        id:'id_kembali_no_trf',
                        value: 1
                    }, {
                        xtype: 'hidden',
                        name: 'is_kembali',
                        id:'id_kembali_status',
                        value: 1
                    }, {
                        xtype: 'datefield',
                        fieldLabel: 'Tanggal SB<span class="asterix">*</span>',
                        fieldClass:'readonly-input',
                        name: 'tgl_sb',
                        id:'id_kembali_tanggal_sb',
                        readOnly:true,
                        allowBlank:false,
                        format:'d-M-Y',
                        editable:false,
                        anchor: '90%'
                    }, {
                        xtype: 'textarea',
                        fieldLabel: 'Keterangan',
                        name: 'ket_pengembalian',
                        allowBlank:false,
                        id: 'id_kembali_sb_keterangan',
                        maxLength: 100,
                        anchor: '90%',
                        value:''
                    }]
                }]
            }]
            },
            gridBarterKembali
        ],
        buttons: [{
            text: 'Save',
            formBind: true,
            handler: function(){
                var data = new Array();
                strGridBarterKembali .each(function(node){
                    data.push(node.data)
                });
                Ext.getCmp('barterkembali').getForm().submit({
                    url: '<?= site_url("barterbarang/proses_penerimaan") ?>',
                    scope: this,
                    params: {
                        data: Ext.util.JSON.encode(data)
                    },
                    waitMsg: 'Saving Data...',
                    success: function(form, action){
                        var fe = Ext.util.JSON.decode(action.response.responseText);
                        Ext.Msg.show({
                            title: 'Success',
                            msg: fe.successMsg,
                            modal: true,
                            icon: Ext.Msg.INFO,
                            buttons: Ext.Msg.OK
                        });
                        resetFormPengembalian();
                        return;
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
        },
        {
            text: 'Reset',
            handler: function(){ resetFormPengembalian() }
        }]
    });

    function resetFormPengembalian(){
        Ext.getCmp('barterkembali').getForm().reset();
        strGridBarterKembali .removeAll();
    }
</script>
