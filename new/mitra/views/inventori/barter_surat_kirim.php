<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>

<script type="text/javascript">
/**
 * combo barter lokasi
 */
    var strComboBarterLokasi = new Ext.data.ArrayStore({
        fields: ['kd_lokasi','nama_lokasi', 'peruntukan'],
        data : []
    });

    var strGridBarterLokasi = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_lokasi', allowBlank: false, type: 'text'},
                {name: 'nama_lokasi', allowBlank: false, type: 'text'},
                {name: 'peruntukan', allowBlank: false, type: 'text'}
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("mutasi_barang/search_lokasi") ?>',
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

    var searchGridBarterLokasi = new Ext.app.SearchField({
        store: strGridBarterLokasi,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_barter_search_lokasi'
    });

    var gridBarterLokasi = new Ext.grid.GridPanel({
        store: strGridBarterLokasi,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
            header: 'Kode Lokasi',
            dataIndex: 'kd_lokasi',
            width: 100,
            sortable: true

        },{
            header: 'Peruntukan',
            dataIndex: 'peruntukan',
            width: 100,
            sortable: true

        },{
            header: 'Nama Lokasi',
            dataIndex: 'nama_lokasi',
            width: 300,
            sortable: true
        }],

        tbar: new Ext.Toolbar({
            items: [searchGridBarterLokasi]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strGridBarterLokasi,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('barter_ref_kd_lokasi').setValue(sel[0].get('kd_lokasi'));
                    Ext.getCmp('id_barter_form_lokasi').setValue(sel[0].get('nama_lokasi'));
                    menuBarterLokasi.hide();
                }
            }
        }
    });

    var menuBarterLokasi = new Ext.menu.Menu();
    menuBarterLokasi.add(new Ext.Panel({
        title: 'Pilih Lokasi',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 600,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridBarterLokasi],
        buttons: [{
            text: 'Close',
            handler: function(){
                menuBarterLokasi.hide();
            }
        }]
    }));

    Ext.ux.TwinComboBarterLokasi = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            strGridBarterLokasi.load();
            menuBarterLokasi.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menuBarterLokasi.on('hide', function(){
        var sf = Ext.getCmp('id_barter_search_lokasi').getValue();
        if( sf != ''){
            Ext.getCmp('id_barter_search_lokasi').setValue('');
            searchGridBarterLokasi.onTrigger2Click();
        }
    });

    var comboBarterLokasi = new Ext.ux.TwinComboBarterLokasi({
        fieldLabel: 'Lokasi Pengambilan <span class="asterix">*</span>',
        id: 'id_barter_form_lokasi',
        store: strComboBarterLokasi,
        mode: 'local',
        valueField: 'kd_lokasi',
        displayField: 'nama_lokasi',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_lokasi',
        emptyText: 'Pilih Lokasi'

    });

/**
 * combo barter ekspedisi
 */
    var strComboBarterEkspedisi = new Ext.data.ArrayStore({
        fields: ['nama_ekspedisi'],
        data : []
    });

    var strGridBarterEkspedisi = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_ekspedisi', 'nama_ekspedisi'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("penjualan_sj/search_ekspedisi") ?>',
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

    var searchGridBarterEkspedisi = new Ext.app.SearchField({
        store: strGridBarterEkspedisi,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_barter_search_ekspedisi'
    });


    var gridBarterEkspedisi = new Ext.grid.GridPanel({
        store: strGridBarterEkspedisi,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'Kode Ekspedisi',
                dataIndex: 'kd_ekspedisi',
                width: 80,
                sortable: true

            },{
                header: 'Nama Ekspedisi',
                dataIndex: 'nama_ekspedisi',
                width: 300,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchGridBarterEkspedisi]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strGridBarterEkspedisi,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_kd_ekspedisi').setValue(sel[0].get('kd_ekspedisi'));
                    Ext.getCmp('id_barter_form_ekspedisi').setValue(sel[0].get('nama_ekspedisi'));
                    strpembelianretur.removeAll();
                    menuBarterEkspedisi.hide();
                }
            }
        }
    });

    var menuBarterEkspedisi = new Ext.menu.Menu();
    menuBarterEkspedisi.add(new Ext.Panel({
        title: 'Pilih Ekspedisi',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridBarterEkspedisi],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menuBarterEkspedisi.hide();
                }
            }]
    }));

    Ext.ux.TwinComboEkspedisi = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            strGridBarterEkspedisi.load();
            menuBarterEkspedisi.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menuBarterEkspedisi.on('hide', function(){
        var sf = Ext.getCmp('id_barter_search_ekspedisi').getValue();
        if( sf != ''){
            Ext.getCmp('id_barter_search_ekspedisi').setValue('');
            searchGridBarterEkspedisi.onTrigger2Click();
        }
    });

    var comboBarterEkspedisi = new Ext.ux.TwinComboEkspedisi({
        fieldLabel: 'Ekspedisi <span class="asterix">*</span>',
        id: 'id_barter_form_ekspedisi',
        store: strComboBarterEkspedisi,
        mode: 'local',
        valueField: 'nama_ekspedisi',
        displayField: 'nama_ekspedisi',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'nama_ekspedisi',
        emptyText: 'Pilih Ekspedisi'
    });/**
 * combo barter bukti
 */
    var strComboBarterBukti = new Ext.data.ArrayStore({
        fields: ['no_transfer_stok'],
        data : []
    });

    var strGridBarterBukti = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_transfer_stok',
                'tanggal',
                'jenis_transfer',
                'no_po',
                'kd_supplier',
                'nama_supplier',
                'pic_supplier',
                'alamat_supplier',
                'no_telp_supplier',
                'status',
                'keterangan'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("barterbarang/get_rows_kirim") ?>',
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

    strGridBarterBukti.on('load', function(){
        strGridBarterBukti.setBaseParam('stat', 2);
    })

    var searchGridBarterBukti = new Ext.app.SearchField({
        store: strGridBarterBukti,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_barter_search_bukti'
    });


    var gridBarterBukti = new Ext.grid.GridPanel({
        store: strGridBarterBukti,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [
            {header:'No. Bukti',dataIndex:'no_transfer_stok',width: 100,sortable: true},
            {header:'Tanggal',dataIndex:'tanggal',width: 80,sortable: true},
            {header:'Kd. Supplier',dataIndex:'kd_supplier',width: 80,sortable: true},
            {header:'Nama Supplier',dataIndex:'nama_supplier',width: 150,sortable: true},
            {header:'No. PO',dataIndex:'no_po',width: 100,sortable: true},
            {header:'Keterangan',dataIndex:'keterangan',width: 200,sortable: true}
        ],
        tbar: new Ext.Toolbar({
            items: [searchGridBarterBukti]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strGridBarterBukti,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_barter_form_tanggal_bukti').setValue(sel[0].get('tanggal'));
                    Ext.getCmp('id_barter_form_bukti').setValue(sel[0].get('no_transfer_stok'));

                    Ext.getCmp('id_barter_kirim_penerima').setValue(sel[0].get('pic_supplier'));
                    Ext.getCmp('id_barter_kirim_alamat').setValue(sel[0].get('alamat_supplier'));
                    Ext.getCmp('id_barter_kirim_telepon').setValue(sel[0].get('no_telp_supplier'));
                    Ext.getCmp('id_barter_kirim_keterangan').setValue(sel[0].get('keterangan'));
                    menuBarterBukti.hide();
                }
            }
        }
    });

    var menuBarterBukti = new Ext.menu.Menu();
    menuBarterBukti.add(new Ext.Panel({
        title: 'Pilih No.Bukti',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridBarterBukti],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menuBarterBukti.hide();
                }
            }]
    }));

    Ext.ux.TwinComboBarterBukti = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strGridBarterBukti.load({params: {stat: 2}});
            menuBarterBukti.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menuBarterBukti.on('hide', function(){
        var sf = Ext.getCmp('id_barter_search_bukti').getValue();
        if( sf != ''){
            Ext.getCmp('id_barter_search_bukti').setValue('');
            searchGridBarterBukti.onTrigger2Click();
        }
    });

    var comboBarterBukti = new Ext.ux.TwinComboBarterBukti({
        fieldLabel: 'No. Bukti <span class="asterix">*</span>',
        id: 'id_barter_form_bukti',
        store: strcb_salessj_faktur,
        mode: 'local',
        valueField: 'no_transfer_stok',
        displayField: 'no_transfer_stok',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'no_transfer_stok',
        emptyText: 'Pilih No. Bukti'
    });

/**
 * combo barter produk
 */
    var strComboBarterKirimProduk = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data : []
    });

    var strGridBarterKirimProduk = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_produk_awal','nama_produk_awal','qty','nm_satuan_awal', 'qty_kirim', 'qty_oh'],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("barterbarang/get_rows_detail") ?>',
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

    var searchGridBarterKirimProduk = new Ext.app.SearchField({
        width: 220,
        id: 'search_query',
        store: strGridBarterKirimProduk
    });

    searchGridBarterKirimProduk.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';
            var fid = Ext.getCmp('id_barter_form_bukti').getValue();
            var o = { start: 0, no_transfer_stok: fid };

            this.store.baseParams = this.store.baseParams || {};
            this.store.baseParams[this.paramName] = '';
            this.store.reload({ params : o });
            this.triggers[0].hide();
            this.hasSearch = false;
        }
    };

    searchGridBarterKirimProduk.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }
        var fid = Ext.getCmp('id_barter_form_bukti').getValue();
        var o = { start: 0, no_transfer_stok: fid };

        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params:o});
        this.hasSearch = true;
        this.triggers[0].show();
    };

    // top toolbar
    var toolbarGridBarterKirimProduk = new Ext.Toolbar({
        items: [searchGridBarterKirimProduk]
    });

    var gridBarterKirimProduk = new Ext.grid.GridPanel({
        store: strGridBarterKirimProduk,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'Kode Produk',
                dataIndex: 'kd_produk_awal',
                width: 90,
                sortable: true

            },{
                header: 'Nama Produk',
                dataIndex: 'nama_produk_awal',
                width: 250,
                sortable: true
            },{
                header: 'Satuan',
                dataIndex: 'nm_satuan_awal',
                width: 50
            },{
                header: 'Qty',
                dataIndex: 'qty',
                width: 75,
                sortable: true
            },{
                header: 'Qty Kirim',
                dataIndex: 'qty_kirim',
                width: 75,
                sortable: true
            },{
                header: 'Stok OH',
                dataIndex: 'qty_oh',
                width: 75,
                sortable: true
            }],
        tbar:toolbarGridBarterKirimProduk,
        listeners: {
            'rowdblclick': function(){
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_barter_row_kd_produk').setValue(sel[0].get('kd_produk_awal'));
                    Ext.getCmp('id_barter_row_nama_produk').setValue(sel[0].get('nama_produk_awal'));
                    Ext.getCmp('id_barter_row_qty_oh').setValue(sel[0].get('qty_oh'));
                    Ext.getCmp('id_barter_row_qty_sb').setValue(sel[0].get('qty'));
                    Ext.getCmp('id_barter_row_qty_kirim').setValue(sel[0].get('qty_kirim'));
                    Ext.getCmp('id_barter_row_qty').setValue('0');
                    Ext.getCmp('id_barter_row_nm_satuan').setValue(sel[0].get('nm_satuan_awal'));
                    //asdfasdf
                    Ext.getCmp('id_barter_row_sub').focus();
                    menuBarterKirimProduk.hide();
                }
            }
        }
    });

    var menuBarterKirimProduk = new Ext.menu.Menu();
    menuBarterKirimProduk.add(new Ext.Panel({
        title: 'Pilih Barang',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 600,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridBarterKirimProduk],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menuBarterKirimProduk.hide();
                }
            }]
    }));

    Ext.ux.TwinComboBarterKirimProduk = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            if(Ext.getCmp('id_barter_form_bukti').getValue() == ''){
                Ext.Msg.show({
                    title: 'Error',
                    msg: 'Silahkan pilih No Bukti terlebih dulu',
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK
                });
                return;
            }

            strGridBarterKirimProduk.load({
                params: { no_transfer_stok: Ext.getCmp('id_barter_form_bukti').getValue() }
            });
            menuBarterKirimProduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

/**
 * store detail row
 */
    var strBarterKirim = new Ext.data.Store({
        autoSave:false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_produk_awal', allowBlank: false, type: 'text'},
                {name: 'nama_produk_awal', allowBlank: false, type: 'text'},
                {name: 'qty', allowBlank: false, type: 'int'},
                {name: 'nm_satuan', allowBlank: false, type: 'text'},
                {name: 'qty_kirim', allowBlank: false, type: 'int'},
                {name: 'qty_oh', allowBlank: false, type: 'int'}
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

    var barterKirimRowEditor = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });

/**
 * Kombo sub blok detail row
 */
    var strBarterKirimSubBlok = new Ext.data.Store({
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

    var strGridBarterKirimSubBlok = new Ext.data.Store({
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

    // search field
    var searchGridBarterKirimSubBlok = new Ext.app.SearchField({
        store: strGridBarterKirimSubBlok,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'id_search_combo_sub_blok'
    });

    // top toolbar
    var toolbarGridBarterKirimSubBlok = new Ext.Toolbar({
        items: [searchGridBarterKirimSubBlok]
    });

    var gridBarterKirimSubBlok = new Ext.grid.GridPanel({
        store: strGridBarterKirimSubBlok,
        stripeRows: true,
        frame: true,
        border:true,
        tbar: toolbarGridBarterKirimSubBlok,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strGridBarterKirimSubBlok,
            displayInfo: true
        }),
        columns: [{
                dataIndex: 'kd_lokasi',
                hidden: true
            },{
                dataIndex: 'kd_blok',
                hidden: true
            },{
                dataIndex: 'kd_sub_blok',
                hidden: true
            },{
                header: 'Kode',
                dataIndex: 'sub',
                width: 90,
                sortable: true

            },{
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
                            kd_produk: Ext.getCmp('id_barter_row_kd_produk').getValue()
                        },
                        callback:function(opt,success,responseObj){
                            var scn = Ext.util.JSON.decode(responseObj.responseText);
                            if(scn.success==true){
                                Ext.getCmp('id_barter_row_qty_per_lokasi').setValue(scn.data.qty_oh);
                            }
                        }
                    });

                    Ext.getCmp('id_barter_row_sub').setValue(sel[0].get('sub'));
                    Ext.getCmp('id_barter_row_nama_sub').setValue(sel[0].get('nama_sub'));
                    Ext.getCmp('id_barter_row_qty_kirim').focus();
                    menuBarterKirimSubBlok.hide();
                }
            }
        }
    });

    var menuBarterKirimSubBlok = new Ext.menu.Menu();
    menuBarterKirimSubBlok.add(new Ext.Panel({
        title: 'Pilih Sub Blok Lokasi',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 350,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridBarterKirimSubBlok],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menuBarterKirimSubBlok.hide();
                }
            }]
    }));

    Ext.ux.TwinComboBarterSubBlok = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            strGridBarterKirimSubBlok.load({
                params:{
                    kd_produk: Ext.getCmp('id_barter_row_kd_produk').getValue(),
                    kd_lokasi: Ext.getCmp('barter_ref_kd_lokasi').getValue()
                }
            });
            menuBarterKirimSubBlok.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    var comboBarterSubBlok = new Ext.ux.TwinComboBarterSubBlok({
        id: 'id_barter_row_sub',
        store: strBarterKirimSubBlok,
        valueField: 'sub',
        displayField: 'sub',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        hiddenName: 'sub',
        emptyText: 'Pilih Sub Blok',
        listeners: {
            'expand': function(){
                var datablok = Ext.getCmp('barter_ref_kd_lokasi').getValue();
                strBarterKirimSubBlok.load({params: {datablok: datablok}});
            }
        }
    });

/**
 * Grid Sales Jual
 */
    var gridDataBarter = new Ext.grid.GridPanel({
        store: strBarterKirim,
        stripeRows: true,
        height: 200,
        frame: true,
        border:true,
        plugins:[barterKirimRowEditor],
        columns: [{
                header: 'Kode produk',
                dataIndex: 'kd_produk',
                width: 110,
                editor: new Ext.ux.TwinComboBarterKirimProduk({
                    id: 'id_barter_row_kd_produk',
                    store: strComboBarterKirimProduk,
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
            },{
                header: 'Nama Barang',
                dataIndex: 'nama_produk',
                width: 320,
                editor: new Ext.form.TextField({
                    fieldClass: 'readonly-input',
                    readOnly: true,
                    id: 'id_barter_row_nama_produk'
                })
            },{
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 80,
                editor: new Ext.form.TextField({
                    fieldClass: 'readonly-input',
                    readOnly: true,
                    id: 'id_barter_row_nm_satuan'
                })
            },{
                header: 'Stok OH',
                dataIndex: 'qty_oh',
                width: 60,
                editor: new Ext.form.TextField({
                    fieldClass: 'readonly-input',
                    readOnly: true,
                    id: 'id_barter_row_qty_oh'
                })
            },{
                header: 'Stok di Sub Blok',
                dataIndex: 'qty_per_lokasi',
                width: 100,
                editor: new Ext.form.TextField({
                    fieldClass: 'readonly-input',
                    readOnly: true,
                    id: 'id_barter_row_qty_per_lokasi'
                })
            },{
                header: 'Qty SB',
                dataIndex: 'qty_sb',
                width: 60,
                editor: new Ext.form.TextField({
                    fieldClass: 'readonly-input',
                    readOnly: true,
                    id: 'id_barter_row_qty_sb'
                })
            },{
                header: 'Qty Kirim',
                dataIndex: 'qty_kirim',
                width: 60,
                editor: new Ext.form.TextField({
                    fieldClass: 'readonly-input',
                    readOnly: true,
                    id: 'id_barter_row_qty_kirim'
                })
            },{
                xtype: 'numbercolumn',
                header: 'Qty',
                dataIndex: 'qty',
                width: 60,
                align: 'right',
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'id_barter_row_qty',
                    selectOnFocus:true,
                    listeners:{
                        'change': function(){
                            var qty_sb = Ext.getCmp('id_barter_row_qty_sb').getValue();
                            var qty_kirim = Ext.getCmp('id_barter_row_qty_kirim').getValue();

                            if(this.getValue() == '') this.setValue('0');
                            if(qty_sb == '') Ext.getCmp('id_barter_row_qty_sb').setValue('0');
                            if(qty_kirim == '') Ext.getCmp('id_barter_row_qty').setValue('0');

                            if(Ext.getCmp('id_barter_row_sub').getValue() == ''){
                                this.setValue('0');
                                Ext.Msg.show({
                                    title: 'Error',
                                    msg: 'Tentukan dulu sub blok pengambilan barang!',
                                    modal: true,
                                    icon: Ext.Msg.ERROR,
                                    buttons: Ext.Msg.OK
                                });
                            }

                            if(Ext.getCmp('id_barter_row_qty_per_lokasi').getValue() < 0 || Ext.getCmp('id_barter_row_qty_per_lokasi').getValue() < this.getValue()) {
                                this.setValue('0');
                                Ext.Msg.show({
                                    title: 'Error',
                                    msg: 'Nilai stok di sub blok tidak cukup!!',
                                    modal: true,
                                    icon: Ext.Msg.ERROR,
                                    buttons: Ext.Msg.OK
                                });
                            }

                            if(this.getValue() > ( qty_sb - qty_kirim )){
                                this.setValue('0');
                                Ext.Msg.show({
                                    title: 'Error',
                                    msg: 'Quantity melebihi batas kirim !',
                                    modal: true,
                                    icon: Ext.Msg.ERROR,
                                    buttons: Ext.Msg.OK
                                });
                            }

                            if(this.getValue() > Ext.getCmp('id_barter_row_qty_oh').getValue()){
                                this.setValue('0');
                                Ext.Msg.show({
                                    title: 'Error',
                                    msg: 'Quantity Melebihi Quantity Stok !',
                                    modal: true,
                                    icon: Ext.Msg.ERROR,
                                    buttons: Ext.Msg.OK
                                });
                            }
                        }
                    }
                }
            },{
                header: 'Kode Sub Blok',
                dataIndex: 'sub',
                width: 100,
                editor: comboBarterSubBlok
            },{
                header: 'Sub Blok',
                dataIndex: 'nama_sub',
                width: 200,
                editor: new Ext.form.TextField({
                    fieldClass: 'readonly-input',
                    readOnly: true,
                    id: 'id_barter_row_nama_sub'
                })
            }],tbar: [{
                icon: BASE_ICONS + 'add.png',
                text: 'Add',
                handler: function(){
                    if(Ext.getCmp('id_barter_form_bukti').getValue() == ''){
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Silahkan pilih no buktu terlebih dulu',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                        });
                        return;
                    }

                    var rowpembelianreceiveorder = new gridDataBarter.store.recordType({
                        no_transfer_stok:'',
                        kd_produk : '',
                        qty: ''
                    });
                    barterKirimRowEditor.stopEditing();
                    strBarterKirim.insert(0, rowpembelianreceiveorder);
                    gridDataBarter.getView().refresh();
                    gridDataBarter.getSelectionModel().selectRow(0);
                    barterKirimRowEditor.startEditing(0);
                }
            },{
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                disabled: true,
                handler: function(){
                    barterKirimRowEditor.stopEditing();
                    var s = gridDataBarter.getSelectionModel().getSelections();
                    for(var i = 0, r; r = s[i]; i++){
                        strBarterKirim.remove(r);
                    }
                }
            }]
    });

    gridDataBarter.getSelectionModel().on('selectionchange', function(sm){
        gridDataBarter.removeBtn.setDisabled(sm.getCount() < 1);
    });


    var barterKirim= new Ext.FormPanel({
        id: 'barterkirim',
        border: false,
        frame: true,
        autoScroll:true,
        monitorValid: true,
        bodyStyle:'padding-right:20px;',
        labelWidth: 130,
        items:[
            {
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },
                items: [{
                    layout: 'column',
                    border: false,
                    items: [{columnWidth: .4,
                            layout: 'form',
                            border: false,
                            labelWidth: 100,
                            defaults: { labelSeparator: ''},
                            items: [{
                                xtype: 'datefield',
                                fieldLabel: 'Tanggal kirim <span class="asterix">*</span>',
                                name: 'tgl_kirim',
                                id:'id_barter_form_tanggal',
                                allowBlank:false,
                                format:'d-M-Y',
                                editable:false,
                                anchor: '90%',
                                value:new Date(),
                                maxValue: (new Date()).clearTime()
                            },
                            comboBarterBukti,
                            {
                                xtype: 'datefield',
                                fieldLabel: 'Tanggal SB <span class="asterix">*</span>',
                                name: 'tgl_sb',
                                id:'id_barter_form_tanggal_bukti',
                                readOnly:true,
                                allowBlank:false,
                                format:'d-M-Y',
                                editable:false,
                                anchor: '90%'
                            },
                            comboBarterLokasi,
                            {
                                xtype:'hidden',
                                name:'kd_lokasi',
                                id:'barter_ref_kd_lokasi'
                            }]
                        },{
                            columnWidth: .4,
                            layout: 'form',
                            border: false,
                            labelWidth: 100,
                            defaults: { labelSeparator: ''},
                            items: [{
                                xtype:'hidden',
                                name:'kd_ekspedisi',
                                id:'id_kd_ekspedisi'
                            },
                            comboBarterEkspedisi,
                            {
                                xtype: 'textfield',
                                fieldLabel: 'No.Kendaraan <span class="asterix">*</span>',
                                name: 'no_kendaraan',
                                allowBlank: false,
                                id: 'id_barter_form_kendaraan',
                                maxLength: 255,
                                anchor: '90%',
                                value:''
                            },{
                                xtype: 'textfield',
                                fieldLabel: 'Sopir <span class="asterix">*</span>',
                                name: 'sopir',
                                allowBlank: false,
                                id: 'id_barter_form_sopir',
                                maxLength: 255,
                                anchor: '90%',
                                value:''
                            }]
                        }]
                }]
            },
            gridDataBarter,
            {
                layout: 'column',
                border: false,
                items: [{
                    columnWidth: .4,
                    style:'margin:6px 3px 0 0;',
                    layout: 'form',
                    labelWidth: 120,
                    items: [{
                        xtype: 'textfield',
                        fieldLabel: 'PIC Penerima',
                        name: 'pic_terima',
                        allowBlank: false,
                        id: 'id_barter_kirim_penerima',
                        maxLength: 255,
                        anchor: '90%',
                        value:''
                    }, {
                        xtype: 'textarea',
                        fieldLabel: 'Alamat Penerima',
                        allowBlank: false,
                        name: 'alm_penerima',
                        id: 'id_barter_kirim_alamat',
                        width: 300,
                        anchor: '90%'
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'Telepon Penerima',
                        allowBlank: false,
                        name: 'telp_terima',
                        id: 'id_barter_kirim_telepon',
                        maxLength: 255,
                        anchor: '90%',
                        value:''
                    }]
                },{
                    columnWidth: .4,
                    style:'margin:6px 3px 0 0;',
                    layout: 'form',
                    labelWidth: 120,
                    items: [{
                        xtype: 'textarea',
                        fieldLabel: 'Keterangan',
                        name: 'keterangan',
                        readOnly:true,
                        id: 'id_barter_kirim_keterangan',
                        maxLength: 255,
                        anchor: '90%',
                        value:''
                    }]
                }]
            }
        ],
        buttons: [{
                text: 'Save',
                formBind: true,
                handler: function(){
                    var detail = new Array();
                    strBarterKirim.each(function(node){
                        detail.push(node.data)
                    });
                    Ext.getCmp('barterkirim').getForm().submit({
                        url: '<?= site_url("barterbarang/save_pengantar") ?>',
                        scope: this,
                        params: {
                            data: Ext.util.JSON.encode(detail)
                        },
                        waitMsg: 'Saving Data...',
                        success: function(form, action){
                            var r = Ext.util.JSON.decode(action.response.responseText);
                            Ext.Msg.show({
                                title: 'Success',
                                msg: 'Form submitted successfully',
                                modal: true,
                                icon: Ext.Msg.INFO,
                                buttons: Ext.Msg.OK,
                                fn: function(btn){
                                    if (btn == 'ok') {
                                        winBarterShowBukti.show();
                                        Ext.getDom('id_barter_print_frame').src = r.printUrl;
                                    }
                                }
                            });

                            clearBarterForm();
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
                text: 'Reset', handler: function(){clearBarterForm()}
            }],
        listeners:{
        }
    });

    var winBarterShowBukti = new Ext.Window({
        id: 'id_barter_win_print',
        title: 'Print Surat Pengantar Barter',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="id_barter_print_frame" src=""></iframe>'
    });

    function clearBarterForm(){
        Ext.getCmp('barterkirim').getForm().reset();
        strBarterKirim.removeAll();
    }
</script>
