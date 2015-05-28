<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    //grid data store
    var strmutasiin = new Ext.data.Store({
//        autoSave:false,
        reader: new Ext.data.JsonReader({
            fields: [

                {name: 'kd_produk', allowBlank: false, type: 'text'},
                {name: 'nama_produk', allowBlank: false, type: 'text'},
                {name: 'nm_satuan', allowBlank: false, type: 'text'},
                {name: 'qty', allowBlank: false, type: 'int'},
                {name: 'sub_asal', allowBlank: false, type: 'text'}	,
                {name: 'nama_sub_asal', allowBlank: false, type: 'text'},
                {name: 'sub_tujuan', allowBlank: false, type: 'text'}	,
                {name: 'nama_sub_tujuan', allowBlank: false, type: 'text'}
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("mutasi_barang/get_form_in_detail") ?>',
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


    // twin nomutasi
    var strcb_min_nomutasi = new Ext.data.ArrayStore({
        fields: ['no_mutasi_stok','tgl_mutasi','keterangan','no_ref'],
        data : []
    });

    var strgrid_min_nomutasi = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_mutasi_stok','no_ref','tgl_mutasi','nama_pengambil','keterangan','tgl_mutasi_in'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("mutasi_barang/get_form_in") ?>',
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
    var searchgrid_min_nomutasi = new Ext.app.SearchField({
        store: strgrid_min_nomutasi,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_mi_searchgrid_min_nomutasi'
    });
    var grid_min_nomutasi = new Ext.grid.GridPanel({
        store: strgrid_min_nomutasi,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
            header: 'No.Mutasi',
            dataIndex: 'no_mutasi_stok',
            width: 100,
            sortable: true
        },{
            header: 'Tanggal',
            dataIndex: 'tgl_mutasi',
            width: 90,
            sortable: true
        },{
            header: 'No.Referensi',
            dataIndex: 'no_ref',
            width: 100,
            sortable: true
        },{
            header: 'Tanggal In',
            dataIndex: 'tgl_mutasi_in',
            width: 90,
            sortable: true,hidden:true
        },{
            header: 'Nama Pengambil',
            dataIndex: 'nama_pengambil',
            width: 100,
            sortable: true
        },{
            header: 'Keterangan',
            dataIndex: 'keterangan',
            width: 400,
            sortable: true
        }],

        tbar: new Ext.Toolbar({
            items: [searchgrid_min_nomutasi]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgrid_min_nomutasi,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_mi_cb_min_nomutasi').setValue(sel[0].get('no_mutasi_stok'));
                    Ext.getCmp('id_mi_no_ref_in').setValue(sel[0].get('no_ref'));
                    Ext.getCmp('id_mi_np_mi').setValue(sel[0].get('nama_pengambil'));
                    Ext.getCmp('id_mi_tgl_mutasiout').setValue(sel[0].get('tgl_mutasi'));
                    Ext.getCmp('min_keterangan').setValue(sel[0].get('keterangan'));
                    Ext.getCmp('id_mi_tgl_mutasiinin').setValue(sel[0].get('tgl_mutasi_in'));

                    var vidmutasi=sel[0].get('no_mutasi_stok');
                    strmutasiin.load({params:{query:vidmutasi}});
                    menu_min_nomutasi.hide();
                }
            }
        }
    });



    var menu_min_nomutasi = new Ext.menu.Menu();
    menu_min_nomutasi.add(new Ext.Panel({
        title: 'Pilih No.Mutasi Barang',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 600,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [grid_min_nomutasi],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menu_min_nomutasi.hide();
                }
            }]
    }));

    Ext.ux.TwinComb_min_nomutasi = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid

            strgrid_min_nomutasi.load();
            menu_min_nomutasi.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menu_min_nomutasi.on('hide', function(){
        var sf = Ext.getCmp('id_mi_searchgrid_min_nomutasi').getValue();
        if( sf != ''){
            Ext.getCmp('id_mi_searchgrid_min_nomutasi').setValue('');
            searchgrid_min_nomutasi.onTrigger2Click();
        }
    });
    //end twin lokasi
    var cb_min_nomutasi = new Ext.ux.TwinComb_min_nomutasi({
        fieldLabel: 'No.Mutasi Barang <span class="asterix">*</span>',
        id: 'id_mi_cb_min_nomutasi',
        store: strcb_min_nomutasi,
        mode: 'local',
        valueField: 'no_mutasi_stok',
        displayField: 'no_mutasi_stok',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '80%',
        hiddenName: 'no_mutasi_stok',
        emptyText: 'Pilih No.Mutasi Barang'

    });
    // end twin nomutasi
    var headermutasiin = {
        layout: 'column',
        border: false,
        items: [{
            columnWidth: .5,
            layout: 'form',
            border: false,
            labelWidth: 120,
            defaults: { labelSeparator: ''},
            items: [
                cb_min_nomutasi,
                {
                    xtype: 'textfield',
                    fieldLabel: 'No. Referensi',
                    name: 'no_ref',
                    readOnly:true,
                    fieldClass:'readonly-input',
                    id: 'id_mi_no_ref_in',
                    anchor: '80%',
                    value:''
                },{
                    xtype: 'textfield',
                    fieldLabel: 'Nama Pengambil',
                    name: 'nama_pengambil',
                    readOnly:true,
                    fieldClass:'readonly-input',
                    id: 'id_mi_np_mi',
                    anchor: '80%',
                    value:''
                }
            ]
        },{
            columnWidth: .4,
            layout: 'form',
            border: false,
            labelWidth: 110,
            defaults: { labelSeparator: ''},
            items: [
                {
                    xtype: 'textfield',
                    fieldLabel: 'Tanggal Mutasi Out',
                    name: 'tgl_mutasi',
                    fieldClass:'readonly-input',
                    readOnly:true,
                    id: 'id_mi_tgl_mutasiout',
                    anchor: '70%',
                    value: ''
                },{
                    xtype: 'datefield',
                    allowBlank:false,
                    format:'d-m-Y',
                    fieldLabel: 'Tanggal Mutasi In',
                    name: 'tgl_mutasi_in',
                    id: 'id_mi_tgl_mutasiinin',
                    anchor: '70%',
                    value: ''
                }
            ]
        }]
    }

    // twin barang
    var strcb_min_produk = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data : []
    });

    var strgrid_min_produk = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [{name: 'kd_produk', allowBlank: false, type: 'text'},
                {name: 'nama_produk', allowBlank: false, type: 'text'},
                {name: 'nm_satuan', allowBlank: false, type: 'text'},
                {name: 'qty_oh', allowBlank: false, type: 'int'}
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("mutasi_barang/search_barang") ?>',
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
    var searchgrid_min_produk = new Ext.app.SearchField({
        store: strgrid_min_produk,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_mi_searchgrid_min_produ'
    });
    var grid_min_produk = new Ext.grid.GridPanel({
        store: strgrid_min_produk,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'Kode produk',
                dataIndex: 'kd_produk',
                width: 100,
                sortable: true

            },{
                header: 'Nama produk',
                dataIndex: 'nama_produk',
                width: 400,
                sortable: true
            },{
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 80
            },{
                header: 'Qty OH',
                dataIndex: 'qty_oh',
                width: 80
            }],

        tbar: new Ext.Toolbar({
            items: [searchgrid_min_produk]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgrid_min_produk,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('min_kd_produk').setValue(sel[0].get('kd_produk'));
                    Ext.getCmp('min_nama_produk').setValue(sel[0].get('nama_produk'));

                    Ext.getCmp('min_nm_satuan').setValue(sel[0].get('nm_satuan'));
                    Ext.getCmp('min_qty_oh').setValue(sel[0].get('qty_oh'));
                    Ext.getCmp('min_qty').setValue(0);
                    Ext.getCmp('min_qty').focus();
                    menu_min_produk.hide();
                }
            }
        }
    });

    var menu_min_produk = new Ext.menu.Menu();
    menu_min_produk.add(new Ext.Panel({
        title: 'Pilih Barang',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 600,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [grid_min_produk],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menu_min_produk.hide();
                }
            }]
    }));

    Ext.ux.TwinComb_min_Produk = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            var datablok=Ext.getCmp('epdin_sub').getValue();

            console.log(datablok);
            strgrid_min_produk.load(
            {
                params: {
                    datablok: datablok

                }
            }
        );
            menu_min_produk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    //end twin produk


    //// twin subblok-------------

    var strcbkdsubblokpro_din = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['sub', 'nama_sub'],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("mutasi_barang/get_subblok_in") ?>',
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

    var strgridsubblokpro_din = new Ext.data.Store({
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
            url: '<?= site_url("mutasi_barang/get_subblok_in") ?>',
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
    var searchgridprosubblok_din = new Ext.app.SearchField({
        store: strgridsubblokpro_din,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE

        },
        width: 220,
        id: 'idsearchgridprosubblok_din'
    });


    // top toolbar
    var tbgridprosubblok_din = new Ext.Toolbar({
        items: [searchgridprosubblok_din]
    });

    var gridprosubblok_din = new Ext.grid.GridPanel({
        store: strgridsubblokpro_din,
        stripeRows: true,
        frame: true,
        border:true,
        tbar: tbgridprosubblok_din,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridsubblokpro_din,
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
                    Ext.getCmp('epdin_sub').setValue(sel[0].get('sub'));
                    Ext.getCmp('epdin_nama_sub').setValue(sel[0].get('nama_sub'));

                    menusubblok_din.hide();
                }
            }
        }
    });

    var menusubblok_din = new Ext.menu.Menu();
    menusubblok_din.add(new Ext.Panel({
        title: 'Pilih Sub Blok Lokasi',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 350,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridprosubblok_din],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menusubblok_din.hide();
                }
            }]
    }));

    Ext.ux.TwinComboproSubBlok_din = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid

            strgridsubblokpro_din.load();
            menusubblok_din.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });


    //// twin subblok tujuan-------------

    var strcbkdsubblokpro_min = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['sub', 'nama_sub'],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("mutasi_barang/get_subblok_in") ?>',
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

    var strgridsubblokpro_min = new Ext.data.Store({
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
            url: '<?= site_url("mutasi_barang/search_subbloktujuan_in") ?>',
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

    strgridsubblokpro_min.on('load', function() {
        strgridsubblokpro_min.setBaseParam('datablok', Ext.getCmp('epdin_sub').getValue());
    });

    // search field
    var searchgridprosubblok_min = new Ext.app.SearchField({
        store: strgridsubblokpro_min,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchgridprosubblok_min'
    });


    searchgridprosubblok_min.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }

        // Get the value of search field

        var datablok=Ext.getCmp('epdin_sub').getValue();
        var o = { start: 0, datablok:datablok };

        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params:o});
        this.hasSearch = true;
        this.triggers[0].show();
    };
    // top toolbar
    var tbgridprosubblok_min = new Ext.Toolbar({
        items: [searchgridprosubblok_min]
    });

    var gridprosubblok_min = new Ext.grid.GridPanel({
        store: strgridsubblokpro_min,
        stripeRows: true,
        frame: true,
        border:true,
        tbar: tbgridprosubblok_min,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridsubblokpro_min,
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
                    Ext.getCmp('epdin_sub_tujuan').setValue(sel[0].get('sub'));
                    Ext.getCmp('epdin_nama_sub_tujuan').setValue(sel[0].get('nama_sub'));

                    menusubblok_min.hide();
                }
            }
        }
    });

    var menusubblok_min = new Ext.menu.Menu();
    menusubblok_min.add(new Ext.Panel({
        title: 'Pilih Sub Blok Lokasi',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 350,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridprosubblok_min],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menusubblok_min.hide();
                }
            }]
    }));

    Ext.ux.TwinComboproSubBlok_min = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            var datablok=Ext.getCmp('epdin_sub_tujuan').getValue();
            strgridsubblokpro_min.load({params: {datablok: datablok}});
            menusubblok_min.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    var editormutasiin = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });


    var gridmutasiin = new Ext.grid.GridPanel({
        store: strmutasiin,
        stripeRows: true,
        height: 250,
        frame: true,
        border:true,
        plugins: [editormutasiin],
        columns: [
            {
                header: 'Kode Sub Blok Asal',
                dataIndex: 'sub_asal',
                width: 100,
                editor: new Ext.form.TextField({readOnly: true,id: 'epdin_sub'})
            },{
                header: 'Sub Blok Asal',
                dataIndex: 'nama_sub_asal',
                width: 200,
                editor: new Ext.form.TextField({readOnly: true,id: 'epdin_nama_sub'})
            },{
                header: 'Kode Barang',
                dataIndex: 'kd_produk',
                fieldClass:'readonly-input',
                width: 110
                ,editor: new Ext.form.TextField({readOnly: true,id: 'min_kd_produk'})
            },{
                header: 'Nama Barang',
                dataIndex: 'nama_produk',
                fieldClass:'readonly-input',
                width: 400,
                editor: new Ext.form.TextField({readOnly: true,id: 'min_nama_produk'})
            },{
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                fieldClass:'readonly-input',
                width: 80,
                editor: new Ext.form.TextField({readOnly: true, id: 'min_nm_satuan'})
            },{
                xtype: 'numbercolumn',
                header: 'Qty',
                dataIndex: 'qty',
                width: 70,
                align: 'center',
                sortable: true,
                format: '0,0',
                editor: {xtype: 'numberfield',id: 'min_qty',readOnly: true}
            },{
                header: 'Kode Sub Blok Tujuan',
                dataIndex: 'sub_tujuan',
                width: 100,
                editor: new Ext.ux.TwinComboproSubBlok_min({
                    id: 'epdin_sub_tujuan',
                    store: strgridsubblokpro_min,
                    valueField: 'sub',
                    displayField: 'sub',
                    typeAhead: true,
                    triggerAction: 'all',
                    allowBlank: false,
                    editable: false,
                    hiddenName: 'sub_tujuan',
                    emptyText: 'Pilih Sub Blok',
                    listeners: {
                        'expand': function(){
                            var datablok=Ext.getCmp('epdin_sub_tujuan').getValue();
                            strgridsubblokpro_min.load({params: {datablok: datablok}});
                        }
                    }
                })
            },{
                header: 'Sub Blok Tujuan',
                dataIndex: 'nama_sub_tujuan',
                width: 200,
                editor: new Ext.form.TextField({readOnly: true,id: 'epdin_nama_sub_tujuan'})
            }]
    });


    var win_cetak_mli = new Ext.Window({
        id: 'id_win_cetak_mli',
        title: 'Print Bukti Mutasi Masuk',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="win_cetak_mli_frame" src=""></iframe>'
    });

    var mutasiin = new Ext.FormPanel({
        id: 'mutasiin',
        border: false,
        frame: true,
        autoScroll:true,
        monitorValid: true,
        bodyStyle:'padding-right:20px;',
        labelWidth: 130,
        items: [{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },
                items: [headermutasiin]
            },{
                layout: 'column',
                border: false,
                items: [{
                        columnWidth: 1,
                        style:'margin:6px 3px 0 0;',
                        layout: 'form',
                        labelWidth: 125,
                        buttonAlign: 'left',
                        items: []
                    }]
            },
            gridmutasiin,{
                layout: 'column',
                border: false,
                items: [{
                        columnWidth: 1,
                        style:'margin:6px 3px 0 0;',
                        layout: 'form',
                        labelWidth: 125,
                        buttonAlign: 'left',
                        items: []
                    }]
            }
            ,{
                layout: 'column',
                border: false,
                items: [{
                        columnWidth: .5,
                        style:'margin:6px 3px 0 0;',
                        layout: 'form',
                        labelWidth: 100,
                        buttonAlign: 'left',
                        items: [{ xtype: 'textarea',
                            fieldLabel: 'Keterangan',
                            name: 'keterangan',
                            id: 'min_keterangan',
                            allowBlank: false,
                            anchor: '90%'
                            }]
                    }]
            }
        ],
        buttons: [{
                text: 'Save',
                formBind: true,
                handler: function(){

                    var detailmutasiin = new Array();
                    strmutasiin.each(function(node){
                        detailmutasiin.push(node.data)
                    });
                    Ext.getCmp('mutasiin').getForm().submit({
                        url: '<?= site_url("mutasi_barang/update_row_in") ?>',
                        scope: this,
                        params: {
                            detail: Ext.util.JSON.encode(detailmutasiin)
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
                                    win_cetak_mli.show();
                                    Ext.getDom('win_cetak_mli_frame').src = r.printUrl;
                                }
                            });

                            clearmutasiin();
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
            },{
                text: 'Reset',
                handler: function(){
                    clearmutasiin();
                }
            }]
    });

    mutasiin.on('afterrender', function(){
        this.getForm().load({
            url: '<?= site_url("mutasi_barang/get_form_in") ?>',
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

    function clearmutasiin(){
        Ext.getCmp('id_mi_no_ref_in').setValue('');
        Ext.getCmp('mutasiin').getForm().reset();
        Ext.getCmp('mutasiin').getForm().load({
            url: '<?= site_url("mutasi_barang/get_form_in") ?>',
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
        strmutasiin.removeAll();
    }
</script>
