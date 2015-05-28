<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>

<script type="text/javascript">
    // twin lokasi
    var strcb_sj_lokasi = new Ext.data.ArrayStore({
        fields: ['kd_lokasi','nama_lokasi'],
        data : []
    });

    var strgrid_sj_lokasi = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [{name: 'kd_lokasi', allowBlank: false, type: 'text'},
                {name: 'nama_lokasi', allowBlank: false, type: 'text'}
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

    var searchgrid_sj_lokasi = new Ext.app.SearchField({
        store: strgrid_sj_lokasi,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgrid_sj_lokasi'
    });

    var grid_sj_lokasi = new Ext.grid.GridPanel({
        store: strgrid_sj_lokasi,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
            header: 'Kode Lokasi',
            dataIndex: 'kd_lokasi',
            width: 100,
            sortable: true

        },{
            header: 'Nama Lokasi',
            dataIndex: 'nama_lokasi',
            width: 400,
            sortable: true
        }],

        tbar: new Ext.Toolbar({
            items: [searchgrid_sj_lokasi]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgrid_sj_lokasi,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('kd_ref_kd_lokasi').setValue(sel[0].get('kd_lokasi'));
                    Ext.getCmp('id_cb_sj_lokasi').setValue(sel[0].get('nama_lokasi'));
                    menu_sj_lokasi.hide();
                }
            }
        }
    });

    var menu_sj_lokasi = new Ext.menu.Menu();
    menu_sj_lokasi.add(new Ext.Panel({
        title: 'Pilih Lokasi',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 600,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [grid_sj_lokasi],
        buttons: [{
            text: 'Close',
            handler: function(){
                menu_sj_lokasi.hide();
            }
        }]
    }));

    Ext.ux.TwinComb_sj_lokasi = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgrid_sj_lokasi.load();
            menu_sj_lokasi.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menu_sj_lokasi.on('hide', function(){
        var sf = Ext.getCmp('id_searchgrid_sj_lokasi').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgrid_sj_lokasi').setValue('');
            searchgrid_sj_lokasi.onTrigger2Click();
        }
    });

    //end twin lokasi
    var cb_sj_lokasi = new Ext.ux.TwinComb_sj_lokasi({
        fieldLabel: 'Lokasi Pengambilan <span class="asterix">*</span>',
        id: 'id_cb_sj_lokasi',
        store: strcb_sj_lokasi,
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

    // twin combo supplier
    var strcb_sj_ekspedisi = new Ext.data.ArrayStore({
        fields: ['nama_ekspedisi'],
        data : []
    });

    var strgrid_sj_ekspedisi = new Ext.data.Store({
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

    var searchgrid_sj_ekspedisi = new Ext.app.SearchField({
        store: strgrid_sj_ekspedisi,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgrid_sj_ekspedisi'
    });


    var grid_sj_ekspedisi = new Ext.grid.GridPanel({
        store: strgrid_sj_ekspedisi,
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
            items: [searchgrid_sj_ekspedisi]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgrid_sj_ekspedisi,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_kd_ekspedisi').setValue(sel[0].get('kd_ekspedisi'));
                    Ext.getCmp('id_cbsj_ekspedisi').setValue(sel[0].get('nama_ekspedisi'));
                    strpembelianretur.removeAll();
                    menu_sj_ekspedisi.hide();
                }
            }
        }
    });

    var menu_sj_ekspedisi = new Ext.menu.Menu();
    menu_sj_ekspedisi.add(new Ext.Panel({
        title: 'Pilih Ekspedisi',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [grid_sj_ekspedisi],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menu_sj_ekspedisi.hide();
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
            //load store grid
            strgrid_sj_ekspedisi.load();
            menu_sj_ekspedisi.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menu_sj_ekspedisi.on('hide', function(){
        var sf = Ext.getCmp('id_searchgrid_sj_ekspedisi').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgrid_sj_ekspedisi').setValue('');
            searchgrid_sj_ekspedisi.onTrigger2Click();
        }
    });

    var cbsj_ekspedisi = new Ext.ux.TwinComboEkspedisi({
        fieldLabel: 'Ekspedisi <span class="asterix">*</span>',
        id: 'id_cbsj_ekspedisi',
        store: strcb_sj_ekspedisi,
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
    });


    //twin nodo
    var strcb_salessj_faktur = new Ext.data.ArrayStore({
        fields: ['no_do'],
        data : []
    });

    var strgrid_salessj_faktur = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_do',
                'tanggal',
                'pic_penerima',
                'alamat_penerima',
                'no_telp_penerima',
                'keterangan'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("penjualan_sj/search_do") ?>',
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

    var searchgrid_salessj_faktur = new Ext.app.SearchField({
        store: strgrid_salessj_faktur,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgrid_salessj_faktur'
    });


    var grid_salessj_faktur = new Ext.grid.GridPanel({
        store: strgrid_salessj_faktur,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [
            {header:'No. DO',dataIndex:'no_do',width: 80,sortable: true},
            {header:'Tanggal',dataIndex:'tanggal',width: 80,sortable: true},
            {header:'PIC Penerima',dataIndex:'pic_penerima',width: 80,sortable: true},
            {header:'Alamat penerima',dataIndex:'alamat_penerima',width: 80,sortable: true},
            {header:'No. telp penerima',dataIndex:'no_telp_penerima',width: 80,sortable: true},
            {header:'Keterangan',dataIndex:'keterangan',width: 80,sortable: true}
        ],
        tbar: new Ext.Toolbar({
            items: [searchgrid_salessj_faktur]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgrid_salessj_faktur,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_salessj_tgldo').setValue(sel[0].get('tanggal'));
                    Ext.getCmp('id_salessj_nodo').setValue(sel[0].get('no_do'));

                    Ext.getCmp('id_pic_sj').setValue(sel[0].get('pic_penerima'));
                    Ext.getCmp('id_alm_penerima_sj').setValue(sel[0].get('alamat_penerima'));
                    Ext.getCmp('id_telp_sj').setValue(sel[0].get('no_telp_penerima'));
                    Ext.getCmp('id_salessj_keterangan').setValue(sel[0].get('keterangan'));
                    menu_salessj_faktur.hide();
                }
            }
        }
    });

    var menu_salessj_faktur = new Ext.menu.Menu();
    menu_salessj_faktur.add(new Ext.Panel({
        title: 'Pilih No.DO',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [grid_salessj_faktur],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menu_salessj_faktur.hide();
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
            strgrid_salessj_faktur.load();
            menu_salessj_faktur.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menu_salessj_faktur.on('hide', function(){
        var sf = Ext.getCmp('id_searchgrid_salessj_faktur').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgrid_salessj_faktur').setValue('');
            searchgrid_salessj_faktur.onTrigger2Click();
        }
    });

    var cb_sales_nodo_sj = new Ext.ux.TwinComboReturBeliSupplier({
        fieldLabel: 'No.DO <span class="asterix">*</span>',
        id: 'id_salessj_nodo',
        store: strcb_salessj_faktur,
        mode: 'local',
        valueField: 'no_do',
        displayField: 'no_do',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'no_do',
        emptyText: 'Pilih No.DO'
    });

    var header_sales_sj=
        {layout: 'column',
        border: false,
        items: [{columnWidth: .4,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [{
                        xtype: 'textfield',
                        fieldLabel: 'No.SJ',
                        name: 'no_sj',
                        allowBlank: true,
                        readOnly:true,
                        fieldClass:'readonly-input',
                        id: 'id_salessj_sj',
                        maxLength: 255,
                        anchor: '90%',
                        value:''
                    },{
                        xtype: 'datefield',
                        fieldLabel: 'Tanggal <span class="asterix">*</span>',
                        name: 'tgl_sj',
                        id:'id_salessj_tglsj',
                        allowBlank:false,
                        format:'d-M-Y',
                        editable:false,
                        anchor: '90%',
                        value:new Date(),
                        maxValue: (new Date()).clearTime()
                    },cb_sales_nodo_sj,
                    {xtype: 'datefield',
                        fieldLabel: 'Tanggal DO <span class="asterix">*</span>',
                        name: 'tgl_do',
                        id:'id_salessj_tgldo',
                        readOnly:true,
                        allowBlank:false,
                        format:'d-M-Y',
                        editable:false,
                        anchor: '90%'   }

                ]

            },{columnWidth: .4,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [
                    {
                        xtype:'hidden',
                        name:'kd_ekspedisi',
                        id:'id_kd_ekspedisi'
                    },cbsj_ekspedisi,
                    {
                        xtype: 'textfield',
                        fieldLabel: 'No.Kendaraan <span class="asterix">*</span>',
                        name: 'no_kendaraan',
                        allowBlank: false,
                        id: 'id_salessj_kendaraan',
                        maxLength: 255,
                        anchor: '90%',
                        value:''
                    },{
                        xtype: 'textfield',
                        fieldLabel: 'Sopir <span class="asterix">*</span>',
                        name: 'sopir',
                        allowBlank: false,
                        id: 'id_salessj_sopir',
                        maxLength: 255,
                        anchor: '90%',
                        value:''
                    },cb_sj_lokasi, {
                        xtype:'hidden',
                        name:'kd_lokasi',
                        id:'kd_ref_kd_lokasi'
                    }
                ]
            }]
    };
    //twin produk
    var strcbproproduk_sj = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data : []
    });

    var strgridproproduk_sj = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_produk','nama_produk','qtydo','qty_retur_do','nm_satuan', 'qty_sj', 'qty_oh'],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("penjualan_sj/search_produk_nodo") ?>',
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

    var searchFieldRO_sj = new Ext.app.SearchField({
        width: 220,
        id: 'search_query',
        store: strgridproproduk_sj
    });

    searchFieldRO_sj.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';

            // Get the value of search field
            var fid = Ext.getCmp('id_salessj_nodo').getValue();
            var o = { start: 0, no_do: fid };

            this.store.baseParams = this.store.baseParams || {};
            this.store.baseParams[this.paramName] = '';
            this.store.reload({
                params : o
            });
            this.triggers[0].hide();
            this.hasSearch = false;
        }
    };

    searchFieldRO_sj.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }

        // Get the value of search field
        var fid = Ext.getCmp('id_salessj_nodo').getValue();
        var o = { start: 0, no_do: fid };

        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params:o});
        this.hasSearch = true;
        this.triggers[0].show();
    };

    // top toolbar
    var tbsearchbarang_sj = new Ext.Toolbar({
        items: [searchFieldRO_sj]
    });

    var gridproproduk_sj = new Ext.grid.GridPanel({
        store: strgridproproduk_sj,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'Kode Produk',
                dataIndex: 'kd_produk',
                width: 100,
                sortable: true

            },{
                header: 'Nama Produk',
                dataIndex: 'nama_produk',
                width: 400,
                sortable: true
            },{
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 80
            },{
                header: 'Qty',
                dataIndex: 'qtydo',
                width: 80,
                sortable: true
            },{
                header: 'Qty Retur DO',
                dataIndex: 'qty_retur_do',
                width: 80,
                sortable: true
            },{
                header: 'Stok OH',
                dataIndex: 'qty_oh',
                width: 80,
                sortable: true
            }],
        tbar:tbsearchbarang_sj,
        listeners: {
            'rowdblclick': function(){
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('epsj_kd_produk').setValue(sel[0].get('kd_produk'));
                    Ext.getCmp('epsj_nama_produk').setValue(sel[0].get('nama_produk'));
                    Ext.getCmp('epsj_qty_oh').setValue(sel[0].get('qty_oh'));
                    Ext.getCmp('epsj_qty_do').setValue(sel[0].get('qtydo'));
                    Ext.getCmp('epsj_qty_retur_do').setValue(sel[0].get('qty_retur_do'));
                    Ext.getCmp('epsj_qty_retur_do').setValue(sel[0].get('qty_retur_do'));
                    Ext.getCmp('epsj_qty_sj').setValue(sel[0].get('qty_sj'));
                    Ext.getCmp('epsj_qty').setValue('0');
                    Ext.getCmp('epsj_nm_satuan').setValue(sel[0].get('nm_satuan'));
                    Ext.getCmp('epsj_sub').focus();
                    menupjproduk_sj.hide();
                }
            }
        }
    });

    var menupjproduk_sj = new Ext.menu.Menu();
    menupjproduk_sj.add(new Ext.Panel({
        title: 'Pilih Barang',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 600,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridproproduk_sj],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menupjproduk_sj.hide();
                }
            }]
    }));

    Ext.ux.TwinComboproproduk_sj = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            if(Ext.getCmp('id_salessj_nodo').getValue() == ''){
                Ext.Msg.show({
                    title: 'Error',
                    msg: 'Silahkan pilih No Faktur terlebih dulu',
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK
                });
                return;
            }
            //load store grid
           
            strgridproproduk_sj.load({
                params: {
                    no_do: Ext.getCmp('id_salessj_nodo').getValue()
                }
            });
            menupjproduk_sj.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    // twin subblok-------------

    var strcbkdsubblokpro_sj = new Ext.data.Store({
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

    var strgridsubblokpro_sj = new Ext.data.Store({
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
    var searchgridprosubblok_sj = new Ext.app.SearchField({
        store: strgridsubblokpro_sj,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchgridprosubblok_sj'
    });

    // top toolbar
    var tbgridprosubblok_sj = new Ext.Toolbar({
        items: [searchgridprosubblok_sj]
    });

    var gridprosubblok_sj = new Ext.grid.GridPanel({
        store: strgridsubblokpro_sj,
        stripeRows: true,
        frame: true,
        border:true,
        tbar: tbgridprosubblok_sj,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridsubblokpro_sj,
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
                            kd_produk: Ext.getCmp('epsj_kd_produk').getValue()
                        },
                        callback:function(opt,success,responseObj){
                            var scn = Ext.util.JSON.decode(responseObj.responseText);
                            if(scn.success==true){
                                Ext.getCmp('epsj_qty_per_lokasi').setValue(scn.data.qty_oh);
                            }
                        }
                    });

                    Ext.getCmp('epsj_sub').setValue(sel[0].get('sub'));
                    Ext.getCmp('epsj_nama_sub').setValue(sel[0].get('nama_sub'));

                    menusubblok_sj.hide();
                }
            }
        }
    });

    var menusubblok_sj = new Ext.menu.Menu();
    menusubblok_sj.add(new Ext.Panel({
        title: 'Pilih Sub Blok Lokasi',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 350,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridprosubblok_sj],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menusubblok_sj.hide();
                }
            }]
    }));

    Ext.ux.TwinComboproSubBlok_sj = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            strgridsubblokpro_sj.load({
                params:{
                    kd_produk: Ext.getCmp('epsj_kd_produk').getValue(),
                    kd_lokasi: Ext.getCmp('kd_ref_kd_lokasi').getValue()
                }
            });
            menusubblok_sj.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    //-------grid---------------------------------------------------
    var storesalessj= new Ext.data.Store({
        autoSave:false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_produk', allowBlank: false, type: 'text'},
                {name: 'nama_produk', allowBlank: false, type: 'text'},
                {name: 'qtydo', allowBlank: false, type: 'int'},
                {name: 'qty_retur_do', allowBlank: false, type: 'int'},
                {name: 'qty', allowBlank: false, type: 'int'},
                {name: 'satuan', allowBlank: false, type: 'text'},
                {name: 'keterangan', allowBlank: false, type: 'text'},

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

    var editorsalessj = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });

    var strcbkdsubblokpro = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['sub', 'nama_sub'],
            root: 'data',
            totalProperty: 'record'
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



    var gridsalessj=new Ext.grid.GridPanel({
        store: storesalessj,
        stripeRows: true,
        height: 200,
        frame: true,
        border:true,
        plugins:[editorsalessj],
        columns: [{
                header: 'Kode produk',
                dataIndex: 'kd_produk',
                width: 110,
                editor: new Ext.ux.TwinComboproproduk_sj({
                    id: 'epsj_kd_produk',
                    store: strgridproproduk_sj,
                    mode: 'local',
                    valueField: 'kd_produk',
                    displayField: 'kd_produk',
                    typeAhead: true,
                    triggerAction: 'all',
                    // allowBlank: false,
                    editable: false,
                    hiddenName: 'kd_produk',
                    emptyText: 'Pilih produk'

                })

            },{
                header: 'Nama Barang',
                dataIndex: 'nama_produk',
                width: 320,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epsj_nama_produk'
                })
            },{
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 80,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epsj_nm_satuan'
                })
            },{
                header: 'Stok OH',
                dataIndex: 'qty_oh',
                width: 60,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epsj_qty_oh'
                })
            },{
                header: 'Stok di Sub Blok',
                dataIndex: 'qty_per_lokasi',
                width: 100,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epsj_qty_per_lokasi'
                })
            },{
                header: 'Qty DO',
                dataIndex: 'qtydo',
                width: 60,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epsj_qty_do'
                })
            },{
                header: 'Qty Retur DO',
                dataIndex: 'qty_retur_do',
                width: 100,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epsj_qty_retur_do'
                })
            },{
                header: 'Qty SJ',
                dataIndex: 'qty_sj',
                width: 60,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epsj_qty_sj'
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
                    id: 'epsj_qty',
                    selectOnFocus:true,
                    listeners:{
                        'change': function(){
                            var qty_do = Ext.getCmp('epsj_qty_do').getValue();
                            var qty_sj = Ext.getCmp('epsj_qty_sj').getValue();
                            var qty_retur_do = Ext.getCmp('epsj_qty_retur_do').getValue();

                            if(this.getValue() == '') this.setValue('0');
                            if(qty_do == '') Ext.getCmp('epsj_qty_do').setValue('0');
                            if(qty_sj == '') Ext.getCmp('epsj_qty_sj').setValue('0');

                            if(Ext.getCmp('epsj_sub').getValue() == ''){
                                this.setValue('0');
                                Ext.Msg.show({
                                    title: 'Error',
                                    msg: 'Tentukan dulu sub blok pengambilan barang!',
                                    modal: true,
                                    icon: Ext.Msg.ERROR,
                                    buttons: Ext.Msg.OK
                                });
                            }

                            if(Ext.getCmp('epsj_qty_per_lokasi').getValue() < 0 || Ext.getCmp('epsj_qty_per_lokasi').getValue() < this.getValue()) {
                                this.setValue('0');
                                Ext.Msg.show({
                                    title: 'Error',
                                    msg: 'Nilai stok di sub blok tidak cukup!!',
                                    modal: true,
                                    icon: Ext.Msg.ERROR,
                                    buttons: Ext.Msg.OK
                                });
                            }

                            if(this.getValue() > (qty_do - qty_sj - qty_retur_do)){
                                this.setValue('0');
                                Ext.Msg.show({
                                    title: 'Error',
                                    msg: 'Quantity Melebihi Quantity DO - Qty SJ - Qty Retur DO !',
                                    modal: true,
                                    icon: Ext.Msg.ERROR,
                                    buttons: Ext.Msg.OK
                                });
                            }

                            if(this.getValue() > Ext.getCmp('epsj_qty_oh').getValue()){
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
//                        'specialKey': function( field, e ) {
//                            if ( e.getKey() == e.RETURN || e.getKey() == e.ENTER ) {
//                                this.fireEvent('change');
//                            }
//                        }
                    }
                }
            },{
                header: 'Kode Sub Blok',
                dataIndex: 'sub',
                width: 100,
                editor: new Ext.ux.TwinComboproSubBlok_sj({
                    id: 'epsj_sub',
                    store: strcbkdsubblokpro,
                    valueField: 'sub',
                    displayField: 'sub',
                    typeAhead: true,
                    triggerAction: 'all',
                    editable: false,
                    hiddenName: 'sub',
                    emptyText: 'Pilih Sub Blok',
                    listeners: {
                        'expand': function(){
                            var datablok = Ext.getCmp('id_ms_grid_sub').getValue();
                            strcbkdsubblokpro.load({params: {datablok: datablok}});
                        }
                    }
                })
            },{
                header: 'Sub Blok',
                dataIndex: 'nama_sub',
                width: 200,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epsj_nama_sub'
                })
            },{
                header: 'Keterangan',
                dataIndex: 'keterangan',
                width: 200,
                editor: new Ext.form.TextField({
                    //                readOnly: true,
                    id: 'epsj_keterangan'
                })
            }],tbar: [{
                icon: BASE_ICONS + 'add.png',
                text: 'Add',
                handler: function(){
                    if(Ext.getCmp('id_salessj_nodo').getValue() == ''){
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Silahkan pilih no faktur terlebih dulu',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                        });
                        return;
                    }
                    var rowpembelianreceiveorder = new gridsalessj.store.recordType({
                        no_do:'',
                        kd_produk : '',
                        qty: ''
                    });
                    editorsalessj.stopEditing();
                    storesalessj.insert(0, rowpembelianreceiveorder);
                    gridsalessj.getView().refresh();
                    gridsalessj.getSelectionModel().selectRow(0);
                    editorsalessj.startEditing(0);
                }
            },{
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                disabled: true,
                handler: function(){
                    editorsalessj.stopEditing();
                    var s = gridsalessj.getSelectionModel().getSelections();
                    for(var i = 0, r; r = s[i]; i++){
                        storesalessj.remove(r);
                    }
                }
            }]
    });

    gridsalessj.getSelectionModel().on('selectionchange', function(sm){
        gridsalessj.removeBtn.setDisabled(sm.getCount() < 1);
    });
    var penjualansj= new Ext.FormPanel({
        id: 'penjualansuratjalan',
        border: false,
        frame: true,
        autoScroll:true,
        monitorValid: true,
        bodyStyle:'padding-right:20px;',
        labelWidth: 130,
        items:[{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },
                items: [header_sales_sj]},
            gridsalessj,{
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
                                readOnly:true,
                                id: 'id_pic_sj',
                                maxLength: 255,
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textarea',
                                fieldLabel: 'Alamat Penerima',
                                name: 'alm_penerima',
                                id: 'id_alm_penerima_sj',
                                readOnly:true,
                                width: 300,
                                anchor: '90%'
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Telepon Penerima',
                                name: 'telp_terima',
                                readOnly:true,
                                id: 'id_telp_sj',
                                maxLength: 255,
                                anchor: '90%',
                                value:''
                            },]
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
                                id: 'id_salessj_keterangan',
                                maxLength: 255,
                                anchor: '90%',
                                value:''
                            }]}
                ]
            }
        ],
        buttons: [{
                text: 'Save',
                formBind: true,
                handler: function(){
                    var detaildo = new Array();
                    storesalessj.each(function(node){
                        detaildo.push(node.data)
                    });
                    Ext.getCmp('penjualansuratjalan').getForm().submit({
                        url: '<?= site_url("penjualan_sj/update_row") ?>',
                        scope: this,
                        params: {
                            data: Ext.util.JSON.encode(detaildo)
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
                                        winpenjualansuratjalan.show();
                                        Ext.getDom('penjualansuratjalanprint').src = r.printUrl;
                                    }
                                }
                            });

                            clearsales_sj();
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
                text: 'Reset', handler: function(){clearsales_sj()}
            }],
        listeners:{
        }
    });

    var winpenjualansuratjalan = new Ext.Window({
        id: 'id_winpenjualansuratjalan',
        title: 'Print Surat Jalan',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="penjualansuratjalanprint" src=""></iframe>'
    });

    function clearsales_sj(){
        Ext.getCmp('penjualansuratjalan').getForm().reset();
        storesalessj.removeAll();
    }
</script>
