<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    //grid data store
    var strmutasibarang = new Ext.data.Store({
        autoSave:false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_produk', allowBlank: false, type: 'text'},
                {name: 'nama_produk', allowBlank: false, type: 'text'},
                {name: 'nm_satuan', allowBlank: false, type: 'text'},
                {name: 'qty_oh', allowBlank: false, type: 'int'},
                {name: 'qty', allowBlank: false, type: 'int'},
                {name: 'sub_asal', allowBlank: false, type: 'text'}	,
                {name: 'nama_sub_asal', allowBlank: false, type: 'text'},
                {name: 'sub_tujuan', allowBlank: false, type: 'text'}	,
                {name: 'nama_sub_tujuan', allowBlank: false, type: 'text'}
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

    // twin lokasi
    var strcb_ms_lokasi_asal = new Ext.data.ArrayStore({
        fields: ['kd_lokasi','nama_lokasi'],
        data : []
    });

    var strgrid_ms_lokasi_asal = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [{name: 'kd_lokasi', allowBlank: false, type: 'text'},
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

    var searchgrid_ms_lokasi_asal = new Ext.app.SearchField({
        store: strgrid_ms_lokasi_asal,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgrid_ms_lokasi_asal'
    });

    var grid_ms_lokasi_asal = new Ext.grid.GridPanel({
        store: strgrid_ms_lokasi_asal,
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
            width: 350,
            sortable: true
        },{
            header: 'Peruntukan',
            dataIndex: 'peruntukan',
            width: 100,
            sortable: true
            }],

        tbar: new Ext.Toolbar({
            items: [searchgrid_ms_lokasi_asal]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgrid_ms_lokasi_asal,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cb_ms_lokasi_asal').setValue(sel[0].get('kd_lokasi'));
                    Ext.getCmp('id_nama_lokasi_asal').setValue(sel[0].get('nama_lokasi'));
                    menu_ms_lokasi_asal.hide();
                }
            }
        }
    });

    var menu_ms_lokasi_asal = new Ext.menu.Menu();
    menu_ms_lokasi_asal.add(new Ext.Panel({
        title: 'Pilih Lokasi',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 600,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [grid_ms_lokasi_asal],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menu_ms_lokasi_asal.hide();
                }
            }]
    }));

    Ext.ux.TwinComb_ms_lokasi = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid

            strgrid_ms_lokasi_asal.load();
            menu_ms_lokasi_asal.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menu_ms_lokasi_asal.on('hide', function(){
        var sf = Ext.getCmp('id_searchgrid_ms_lokasi_asal').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgrid_ms_lokasi_asal').setValue('');
            searchgrid_ms_lokasi_asal.onTrigger2Click();
        }
    });

    //end twin lokasi
    var cb_ms_lokasi_asal = new Ext.ux.TwinComb_ms_lokasi({
        fieldLabel: 'Lokasi Asal <span class="asterix">*</span>',
        id: 'id_cb_ms_lokasi_asal',
        store: strcb_ms_lokasi_asal,
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

    // twin lokasi
    var strcb_ms_lokasi_tujuan = new Ext.data.ArrayStore({
        fields: ['kd_lokasi','nama_lokasi'],
        data : []
    });

    var strgrid_ms_lokasi_tujuan = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [{name: 'kd_lokasi', allowBlank: false, type: 'text'},
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

    var searchgrid_ms_lokasi_tujuan = new Ext.app.SearchField({
        store: strgrid_ms_lokasi_tujuan,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgrid_ms_lokasi_tujuan'
    });

    var grid_ms_lokasi_tujuan = new Ext.grid.GridPanel({
        store: strgrid_ms_lokasi_tujuan,
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
            width: 350,
            sortable: true
        },{
            header: 'Peruntukan',
            dataIndex: 'peruntukan',
            width: 100,
            sortable: true
        }],

        tbar: new Ext.Toolbar({
            items: [searchgrid_ms_lokasi_tujuan]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgrid_ms_lokasi_tujuan,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cb_ms_lokasi_tujuan').setValue(sel[0].get('kd_lokasi'));
                    Ext.getCmp('id_nama_lokasi_tujuan').setValue(sel[0].get('nama_lokasi'));
                    menu_ms_lokasi_tujuan.hide();
                }
            }
        }
    });

    var menu_ms_lokasi_tujuan = new Ext.menu.Menu();
    menu_ms_lokasi_tujuan.add(new Ext.Panel({
        title: 'Pilih Lokasi',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 600,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [grid_ms_lokasi_tujuan],
        buttons: [{
            text: 'Close',
            handler: function() {
                menu_ms_lokasi_tujuan.hide();
            }
        }]
    }));

    Ext.ux.TwinComb_ms_lokasi = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            var lokasi_asal = Ext.getCmp('id_cb_ms_lokasi_asal').getValue();
            if(lokasi_asal == '') {
                Ext.Msg.show({
                    title: 'Error',
                    msg: 'Pilih dulu lokasi asal',
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK
                });
            } else {
                strgrid_ms_lokasi_tujuan.load({params: {lokasi: lokasi_asal}});
                menu_ms_lokasi_tujuan.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
            }
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menu_ms_lokasi_tujuan.on('hide', function(){
        var sf = Ext.getCmp('id_searchgrid_ms_lokasi_tujuan').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgrid_ms_lokasi_tujuan').setValue('');
            searchgrid_ms_lokasi_tujuan.onTrigger2Click();
        }
    });

    //end twin lokasi
    var cb_ms_lokasi_tujuan = new Ext.ux.TwinComb_ms_lokasi({
        fieldLabel: 'Lokasi Tujuan <span class="asterix">*</span>',
        id: 'id_cb_ms_lokasi_tujuan',
        store: strcb_ms_lokasi_tujuan,
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

    // twin mutasi sales
    var strcb_ms_mutasi = new Ext.data.ArrayStore({
        fields: ['no_mutasi_stok','tgl_mutasi', 'no_ref', 'keterangan'],
        data : []
    });

    var strgrid_ms_mutasi = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'no_mutasi_stok', allowBlank: false, type: 'text'},
                {name: 'no_ref', allowBlank: false, type: 'text'},
                {name: 'keterangan', allowBlank: false, type: 'text'},
                {name: 'tgl_mutasi', allowBlank: false, type: 'date'}
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("mutasi_barang/search_mutasi") ?>',
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

    var searchgrid_ms_mutasi = new Ext.app.SearchField({
        store: strgrid_ms_mutasi,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgrid_ms_mutasi'
    });

    var grid_ms_mutasi = new Ext.grid.GridPanel({
        store: strgrid_ms_mutasi,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'No Mutasi',
                dataIndex: 'no_mutasi_stok',
                width: 100,
                sortable: true

            },{
                header: 'Tgl Mutasi',
                dataIndex: 'tgl_mutasi',
                width: 100,
                sortable: true
            },{
                header: 'No Ref',
                dataIndex: 'no_ref',
                width: 100,
                sortable: true
            },{
                header: 'Keterangan',
                dataIndex: 'keterangan',
                width: 200,
                sortable: true
            }],

        tbar: new Ext.Toolbar({
            items: [searchgrid_ms_mutasi]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgrid_ms_mutasi,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cb_ms_mutasi').setRawValue(sel[0].get('no_ref'));
                    Ext.getCmp('id_cb_ms_mutasi').setValue(sel[0].get('no_mutasi_stok'));
                    menu_ms_mutasi.hide();
                }
            }
        }
    });

    var menu_ms_mutasi = new Ext.menu.Menu();
    menu_ms_mutasi.add(new Ext.Panel({
        title: 'Pilih Mutasi',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 600,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [grid_ms_mutasi],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menu_ms_mutasi.hide();
                }
            }]
    }));

    Ext.ux.TwinComb_ms_mutasi = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            strgrid_ms_mutasi.load();
            menu_ms_mutasi.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menu_ms_mutasi.on('hide', function(){
        var sf = Ext.getCmp('id_searchgrid_ms_mutasi').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgrid_ms_mutasi').setValue('');
            searchgrid_ms_mutasi.onTrigger2Click();
        }
    });
    //end twin mutasi sales

    var cb_ms_mutasi = new Ext.ux.TwinComb_ms_mutasi({
        fieldLabel: 'No Ref Mutasi',
        id: 'id_cb_ms_mutasi',
        store: strcb_ms_mutasi,
        mode: 'local',
        valueField: 'no_mutasi_stok',
        displayField: 'no_ref',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: true,
        editable: false,
        anchor: '90%',
        hiddenName: 'no_mutasi_sales',
        emptyText: 'Pilih Sales Order'

    });

    var headermutasibarang = {
        layout: 'column',
        border: false,
        items: [{
                columnWidth: .3,
                layout: 'form',
                border: false,
                labelWidth: 120,
                defaults: { labelSeparator: ''},
                items: [
                    cb_ms_lokasi_asal,
                    cb_ms_lokasi_tujuan,
                    {
                        xtype: 'datefield',
                        format:'d-m-Y',
                        fieldLabel: 'Tanggal',
                        name: 'tgl_mutasi',
                        id: 'id_tgl_mutasi',
                        anchor: '90%',
                        allowBlank: false,
                        value: new Date(),
                        maxDate: new Date()
                    },
                    cb_ms_mutasi,
                    new Ext.form.Checkbox({
                        xtype: 'checkbox',
                        fieldLabel: 'Scan Barcode',
                        boxLabel:'Ya',
                        name:'scan_barcode',
                        id:'id_mb_scan_barcode',
                        checked: false,
                        inputValue: '1',
                        autoLoad : true
                    })
                ]
            },{
                columnWidth: .4,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [
                    {
                        xtype: 'textfield',
                        fieldLabel: 'Nama Lokasi Asal',
                        name: 'nama_lokasi_asal',
                        readOnly:true,
                        fieldClass:'readonly-input',
                        id: 'id_nama_lokasi_asal',
                        anchor: '90%',
                        value:''
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'Nama Lokasi Tujuan',
                        name: 'nama_lokasi_tujuan',
                        readOnly:true,
                        fieldClass:'readonly-input',
                        id: 'id_nama_lokasi_tujuan',
                        anchor: '90%',
                        value:''
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'No. Referensi',
                        name: 'no_ref',
                        readOnly:false,
                        allowBlank: true,
                        id: 'id_no_ref',
                        anchor: '90%',
                        value:''
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'Nama Pengambil <span class="asterix">*</span>',
                        name: 'nama_pengambil',
                        allowBlank: false,
                        id: 'id_nama_pengambil',
                        anchor: '90%',
                        value:''
                    }
                ]
            }]
    }

    // twin barang
    var strcb_ms_produk = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data : []
    });

    var strgrid_ms_produk = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [{name: 'kd_produk', allowBlank: false, type: 'text'},
                {name: 'nama_produk', allowBlank: false, type: 'text'},
                {name: 'nm_satuan', allowBlank: false, type: 'text'},
                {name: 'qty_oh', allowBlank: false, type: 'int'},
                {name: 'qty_mutasi', allowBlank: false, type: 'int'}
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

    var searchgrid_ms_produk = new Ext.app.SearchField({
        store: strgrid_ms_produk,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgrid_ms_produk'
    });

    strgrid_ms_produk.on('load', function(){
        strgridsubblokpro_ms.setBaseParam('datablok', Ext.getCmp('id_ms_grid_sub').getValue());
        var scan = Ext.getCmp('id_mb_scan_barcode').getValue();
        if(scan){
            Ext.getCmp('id_ms_scan_barcode').focus();
        }else{
            Ext.getCmp('id_searchgrid_ms_produk').focus();
        }
    });

    searchgrid_ms_produk.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';

            // Get the value of search field
            var fid = Ext.getCmp('id_ms_grid_sub').getValue();
            var o = { start: 0, datablok: fid };

            this.store.baseParams = this.store.baseParams || {};
            this.store.baseParams[this.paramName] = '';
            this.store.reload({
                params : o
            });
            this.triggers[0].hide();
            this.hasSearch = false;
        }
    };

    searchgrid_ms_produk.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }

        // Get the value of search field
        var fid = Ext.getCmp('id_ms_grid_sub').getValue();
        var no_mutasi_stok = Ext.getCmp('id_cb_ms_mutasi').getValue();
        var o = { start: 0, datablok: fid, no_mutasi_stok : no_mutasi_stok };

        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params:o});
        this.hasSearch = true;
        this.triggers[0].show();
    };

    var grid_ms_produk = new Ext.grid.GridPanel({
        store: strgrid_ms_produk,
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
                width: 300,
                sortable: true
            },{
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 80
            },{
                header: 'Qty OH',
                dataIndex: 'qty_oh',
                width: 80
            },{
                header: 'Qty mutasi',
                dataIndex: 'qty_mutasi',
                width: 80
            }],

        tbar: new Ext.Toolbar({
            items: [searchgrid_ms_produk]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgrid_ms_produk,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('ms_kd_produk').setValue(sel[0].get('kd_produk'));
                    Ext.getCmp('ms_nama_produk').setValue(sel[0].get('nama_produk'));

                    Ext.getCmp('ms_nm_satuan').setValue(sel[0].get('nm_satuan'));
                    Ext.getCmp('ms_qty_oh').setValue(sel[0].get('qty_oh'));
                    Ext.getCmp('ms_qty').setValue(sel[0].get('qty_mutasi'));
                    if(sel[0].get('qty_mutasi') > 0){
                        Ext.getCmp('ms_qty').setDisabled(true);
                    }else{
                        Ext.getCmp('ms_qty').setDisabled(false);
                        Ext.getCmp('ms_qty').focus();
                    }
                    menu_ms_produk.hide();
                }
            }
        }
    });

    var menu_ms_produk = new Ext.menu.Menu();
    menu_ms_produk.add(new Ext.Panel({
        title: 'Pilih Barang',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 600,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [grid_ms_produk],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menu_ms_produk.hide();
                }
            }]
    }));

    var menu_ms_produk_scan = new Ext.menu.Menu();
    menu_ms_produk_scan.add(new Ext.Panel({
        title: 'Scan Barcode Produk',
        layout: 'form',
        border: false,
        frame: true,
        autoScroll:true,
        bodyStyle:'padding-right:20px;',
        labelWidth: 130,
        buttonAlign: 'left',
        width: 400,
        height: 150,
        closeAction: 'hide',
        items: [{
            xtype: 'textfield',
            fieldLabel: 'Scan Barcode',
            name: 'scan_barcode',
            id: 'id_ms_scan_barcode',
            anchor: '90%',
            value:'',
            listeners:{
                specialKey: function( field, e ) {
                    if ( e.getKey() == e.RETURN || e.getKey() == e.ENTER ) {
                        var valid = (Ext.getCmp('id_ms_grid_sub').getValue() !== '');

                        if (!valid){
                            Ext.Msg.show({
                                title: 'Error',
                                msg: 'Pilih sub blok terlebih dulu',
                                modal: true,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OK,
                                fn: function(btn){
                                    if (btn == 'ok') { Ext.getCmp('ms_kd_produk').reset();}
                                }
                            });
                            Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                            Ext.getCmp('ms_kd_produk').focus();
                            return;
                        }

                        Ext.Ajax.request({
                            url: '<?= site_url("mutasi_barang/search_barang") ?>',
                            method: 'POST',
                            params: {
                                datablok: Ext.getCmp('id_ms_grid_sub').getValue(),
                                query: Ext.getCmp('id_ms_scan_barcode').getValue(),
                                type: 1,
                                sender: 'scan'
                            },
                            callback:function(opt,success,responseObj){
                                var scn = Ext.util.JSON.decode(responseObj.responseText);
                                if(scn.success==true){
                                    Ext.getCmp('id_ms_kd_produk_scan').setValue(scn.data.kd_produk);
                                    Ext.getCmp('id_ms_nama_produk_scan').setValue(scn.data.nama_produk);
                                }
                            }
                        });
                        if(Ext.getCmp('id_ms_kd_produk_scan').getValue() != ''){
                            Ext.getCmp('id_ms_scan_submit_button').focus();
                        }

                    }
                }
            }
        },{
            xtype: 'textfield',
            fieldLabel: 'Kode Produk',
            name: 'kd_produk',
            readOnly:true,
            fieldClass:'readonly-input',
            id: 'id_ms_kd_produk_scan',
            anchor: '90%',
            value:''
        },{
            xtype: 'textfield',
            fieldLabel: 'Nama Produk',
            name: 'nama_produk',
            readOnly:true,
            fieldClass:'readonly-input',
            id: 'id_ms_nama_produk_scan',
            anchor: '90%',
            value:''
        }
        ],
        buttons: [{
            text: 'Submit',
            formBind: true,
            id:'id_ms_scan_submit_button',
            handler: function(){
                Ext.Ajax.request({
                    url: '<?= site_url("mutasi_barang/search_barang") ?>',
                    method: 'POST',
                    params: {
                        datablok: Ext.getCmp('id_ms_grid_sub').getValue(),
                        query: Ext.getCmp('id_ms_scan_barcode').getValue(),
                        type: 1,
                        sender: 'validate'
                    },
                    callback:function(opt,success,responseObj){
                        var scn = Ext.util.JSON.decode(responseObj.responseText);
                        if(scn.success==true){
                            Ext.getCmp('ms_kd_produk').setValue(scn.data.kd_produk);
                            Ext.getCmp('ms_nama_produk').setValue(scn.data.nama_produk);
                            Ext.getCmp('ms_nm_satuan').setValue(scn.data.nm_satuan);
                            Ext.getCmp('ms_qty_oh').setValue(scn.data.qty_oh);
                            Ext.getCmp('ms_qty').setValue(scn.data.qty_mutasi);
//                            if(scn.data.qty_mutasi) > 0){
//                                Ext.getCmp('ms_qty').setDisabled(true);
//                            }else{
//                                Ext.getCmp('ms_qty').setDisabled(false);
//                            }
                        }else{
                            Ext.getCmp('ms_kd_produk').setValue('');
                            Ext.getCmp('ms_nama_produk').setValue('');
                            Ext.getCmp('ms_nm_satuan').setValue('');
                            Ext.getCmp('ms_qty_oh').setValue('');
                            Ext.getCmp('ms_qty').setValue('');
                            Ext.Msg.show({
                                title: 'Error',
                                msg: scn.errMsg,
                                modal: true,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OK,
                                fn: function(btn){
                                    if (btn == 'ok' && scn.errMsg == 'Session Expired') {
                                        window.location = '<?= site_url("auth/login") ?>';
                                    }
                                }
                            });
                            Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                        }

                        menu_ms_produk_scan.hide();
//                        Ext.getCmp('ms_qty').focus();
                    }
                });
            }
        },{
            text: 'Close',
            handler: function(){
                menu_ms_produk_scan.hide();
            }
        }]
    }));

    Ext.ux.TwinComb_ms_Produk = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            var datablok = Ext.getCmp('id_ms_grid_sub').getValue();
            var no_mutasi_stok = Ext.getCmp('id_cb_ms_mutasi').getValue();
            strgrid_ms_produk.load(
            {
                params: {
                    datablok: datablok,
                    no_mutasi_stok: no_mutasi_stok
                }
            }
        );
            var scan = Ext.getCmp('id_mb_scan_barcode').getValue();
            if(scan){
//                Ext.getCmp('id_ms_scan_barcode').focus();
                menu_ms_produk_scan.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
            }else{
//                Ext.getCmp('id_searchgrid_ms_produk').focus();
                menu_ms_produk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
            }
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    //end twin produk

    //// twin subblok-------------
    var strcbkdsubblokpro_do = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['sub', 'nama_sub'],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("mutasi_barang/get_subblok") ?>',
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

    var strgridsubblokpro_do = new Ext.data.Store({
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
            url: '<?= site_url("mutasi_barang/get_subblok") ?>',
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
    var searchgridprosubblok_do = new Ext.app.SearchField({
        store: strgridsubblokpro_do,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE

        },
        width: 220,
        id: 'idsearchgridprosubblok_do'
    });

    searchgridprosubblok_do.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }

        // Get the value of search field
        var fid = Ext.getCmp('id_cb_ms_lokasi_asal').getValue();
        var o = { start: 0, kd_lokasi: fid };

        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params:o});
        this.hasSearch = true;
        this.triggers[0].show();
    };

    // top toolbar
    var tbgridprosubblok_do = new Ext.Toolbar({
        items: [searchgridprosubblok_do]
    });

    var gridprosubblok_do = new Ext.grid.GridPanel({
        store: strgridsubblokpro_do,
        stripeRows: true,
        frame: true,
        border:true,
        tbar: tbgridprosubblok_do,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridsubblokpro_do,
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
                    Ext.getCmp('id_ms_grid_sub').setValue(sel[0].get('sub'));
                    Ext.getCmp('id_ms_grid_nama_sub').setValue(sel[0].get('nama_sub'));

                    menusubblok_do.hide();
                }
            }
        }
    });

    var menusubblok_do = new Ext.menu.Menu();
    menusubblok_do.add(new Ext.Panel({
        title: 'Pilih Sub Blok Lokasi',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 350,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridprosubblok_do],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menusubblok_do.hide();
                }
            }]
    }));

    Ext.ux.TwinComboproSubBlok_do = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            var vlokasi=Ext.getCmp('id_cb_ms_lokasi_asal').getValue();
            strgridsubblokpro_do.load({params:{kd_lokasi:vlokasi}});
            menusubblok_do.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    //// twin subblok tujuan-------------
    var strcbkdsubblokpro_ms = new Ext.data.Store({
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

    var strgridsubblokpro_ms = new Ext.data.Store({
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
            url: '<?= site_url("mutasi_barang/search_subbloktujuan") ?>',
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

    strgridsubblokpro_ms.on('load', function() {
        strgridsubblokpro_ms.setBaseParam('kd_lokasi', Ext.getCmp('id_cb_ms_lokasi_tujuan').getValue());
        strgridsubblokpro_ms.setBaseParam('kd_produk', Ext.getCmp('ms_kd_produk').getValue());
    });

    // search field
    var searchgridprosubblok_ms = new Ext.app.SearchField({
        store: strgridsubblokpro_ms,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchgridprosubblok_ms'
    });

    searchgridprosubblok_ms.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }

        // Get the value of search field
        var fid = Ext.getCmp('id_cb_ms_lokasi_tujuan').getValue();
        var datablok=Ext.getCmp('id_ms_grid_sub').getValue();
        var o = { start: 0, kd_lokasi: fid, datablok:datablok };

        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params:o});
        this.hasSearch = true;
        this.triggers[0].show();
    };

    // top toolbar
    var tbgridprosubblok_ms = new Ext.Toolbar({
        items: [searchgridprosubblok_ms]
    });

    var gridprosubblok_ms = new Ext.grid.GridPanel({
        store: strgridsubblokpro_ms,
        stripeRows: true,
        frame: true,
        border:true,
        tbar: tbgridprosubblok_ms,
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
                width: 400,
                sortable: true
            }],
        listeners: {
            'rowdblclick': function(){
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    var ref = Ext.getCmp('id_ms_grid_sub').getValue();
                    var ini = sel[0].get('sub');
                    if(ref == ini) {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Sub blok asal dan tujuan harus berbeda!!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                        });
                    } else {
                        Ext.getCmp('id_ms_grid_sub_tujuan').setValue(sel[0].get('sub'));
                        Ext.getCmp('id_ms_grid_nama_sub_tujuan').setValue(sel[0].get('nama_sub'));
                        menusubblok_ms.hide();
                    }

                }
            }
        }
    });

    var menusubblok_ms = new Ext.menu.Menu();
    menusubblok_ms.add(new Ext.Panel({
        title: 'Pilih Sub Blok Lokasi',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 350,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridprosubblok_ms],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menusubblok_ms.hide();
                }
            }]
    }));

    Ext.ux.TwinComboproSubBlok_ms = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            var datablok = Ext.getCmp('id_ms_grid_sub').getValue();
            var vlokasi = Ext.getCmp('id_cb_ms_lokasi_tujuan').getValue();
            var kd_produk = Ext.getCmp('ms_kd_produk').getValue();
            strgridsubblokpro_ms.load({
                params: {
                    datablok: datablok,
                    kd_lokasi:vlokasi,
                    kd_produk: kd_produk
                }
            });
            menusubblok_ms.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    var editormutasibarang = new Ext.ux.grid.RowEditor({saveText: 'Update'});

    var gridmutasibarang = new Ext.grid.GridPanel({
        store: strmutasibarang,
        stripeRows: true,
        height: 250,
        frame: true,
        border:true,
        plugins: [editormutasibarang],
        columns: [{
                header: 'Kode Sub Blok Asal',
                dataIndex: 'sub_asal',
                width: 100,
                editor: new Ext.ux.TwinComboproSubBlok_do({
                    id: 'id_ms_grid_sub',
                    store: strcbkdsubblokpro_do,
                    valueField: 'sub',
                    displayField: 'sub',
                    typeAhead: true,
                    triggerAction: 'all',
                    allowBlank: true,
                    editable: false,
                    hiddenName: 'sub_asal',
                    emptyText: 'Pilih Sub Blok',
                    listeners: {
                        'expand': function(){
                            var datablok = Ext.getCmp('id_ms_grid_sub').getValue();
                            strcbkdsubblokpro_do.load({params: {datablok: datablok}});
                        }
                    }
                })
            },{
                header: 'Sub Blok Asal',
                dataIndex: 'nama_sub_asal',
                width: 200,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'id_ms_grid_nama_sub'
                })
            },{
                header: 'Kode Barang',
                dataIndex: 'kd_produk',
                width: 110
                ,editor: new Ext.ux.TwinComb_ms_Produk ({
                    id: 'ms_kd_produk',
                    store: strcb_ms_produk,
                    mode: 'local',
                    valueField: 'kd_produk',
                    displayField: 'kd_produk',
                    typeAhead: true,
                    triggerAction: 'all',
                    editable: false,
                    hiddenName: 'kd_produk',
                    emptyText: 'Pilih produk'

                })
            },{
                header: 'Nama Barang',
                dataIndex: 'nama_produk',
                width: 400,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'ms_nama_produk'
                })
            },{
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 80,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'ms_nm_satuan'
                })
            },{
                xtype: 'numbercolumn',
                header: 'Qty OH',
                dataIndex: 'qty_oh',
                width: 70,
                align: 'center',
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'ms_qty_oh',
                    readOnly:true
                }
            },{
                xtype: 'numbercolumn',
                header: 'Qty',
                dataIndex: 'qty',
                width: 70,
                align: 'center',
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'ms_qty',
                    selectOnFocus: true,
                    listeners:{
                        'change': function(){
                            if(this.getValue() == ''){this.setValue('0');}
                            if(Ext.getCmp('ms_qty_oh').getValue() == ''){
                                Ext.getCmp('ms_qty_oh').setValue('0');
                            }

                            if(this.getValue() > Ext.getCmp('ms_qty_oh').getValue()){
                                Ext.Msg.show({
                                    title: 'Error',
                                    msg: 'Quantity Melebihi Quantity On Hand !!',
                                    modal: true,
                                    icon: Ext.Msg.ERROR,
                                    buttons: Ext.Msg.OK
                                });
                                this.setValue('0');
                            }

                        }
                    }
                }
            }, {
                header: 'Kode Sub Blok Tujuan',
                dataIndex: 'sub_tujuan',
                width: 100,
                editor: new Ext.ux.TwinComboproSubBlok_ms({
                    id: 'id_ms_grid_sub_tujuan',
                    store: strgridsubblokpro_ms,
                    valueField: 'sub',
                    displayField: 'sub',
                    typeAhead: true,
                    triggerAction: 'all',
                    allowBlank: true,
                    editable: false,
                    hiddenName: 'sub_tujuan',
                    emptyText: 'Pilih Sub Blok',
                    listeners: {
                        'expand': function(){
                            var datablok=Ext.getCmp('id_ms_grid_sub').getValue();
                            strgridsubblokpro_ms.load({params: {datablok: datablok}});
                        }
                    }
                })
            },{
                header: 'Sub Blok Tujuan',
                dataIndex: 'nama_sub_tujuan',
                width: 200,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'id_ms_grid_nama_sub_tujuan'
                })
            }],tbar: [{
                icon: BASE_ICONS + 'add.png',
                text: 'Add',
                handler: function(){
                    var lokasi = Ext.getCmp('id_cb_ms_lokasi_asal').getValue();
                    if(lokasi == '') {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Lokasi awal harus ditentukan!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                        });
                    } else {
                        var rowmutasi = new gridmutasibarang.store.recordType({
                            kd_produk : '',
                            qty: ''
                        });
                        editormutasibarang.stopEditing();
                        strmutasibarang.insert(0, rowmutasi);
                        gridmutasibarang.getView().refresh();
                        gridmutasibarang.getSelectionModel().selectRow(0);
                        editormutasibarang.startEditing(0);
                    }
                }
            },{
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                disabled: true,
                handler: function(){
                    editormutasibarang.stopEditing();
                    var s = gridmutasibarang.getSelectionModel().getSelections();
                    for(var i = 0, r; r = s[i]; i++){
                        strmutasibarang.remove(r);
                    }
                }
            }]
    });

    gridmutasibarang.getSelectionModel().on('selectionchange', function(sm){
        gridmutasibarang.removeBtn.setDisabled(sm.getCount() < 1);
    });

    var wininv_mutasi_barang = new Ext.Window({
        id: 'id_wininv_mutasi_barang',
        title: 'Print Mutasi Barang',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="wininv_mutasi_barang" src=""></iframe>'
    });

    var mutasibarang = new Ext.FormPanel({
        id: 'mutasibarang',
        border: false,
        frame: true,
        autoScroll:true,
        monitorValid: true,
        bodyStyle:'padding-right:20px;',
        labelWidth: 130,
        items: [
            {
                bodyStyle: {margin: '0px 0px 15px 0px'},
                items: [headermutasibarang]
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
            gridmutasibarang,
            {
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
            },{
                layout: 'column',
                border: false,
                items: [{
                    columnWidth: .5,
                    style:'margin:6px 3px 0 0;',
                    layout: 'form',
                    labelWidth: 100,
                    buttonAlign: 'left',
                    items: [{
                        xtype: 'textarea',
                        fieldLabel: 'Keterangan',
                        allowBlank: false,
                        name: 'keterangan',
                        id: 'ms_keterangan',
                        anchor: '90%'
                    }]
                }]
            }
        ],
        buttons: [{
            text: 'Save',
            formBind: true,
            handler: function(){
                var detailmutasibarang = new Array();
                strmutasibarang.each(function(node){detailmutasibarang.push(node.data)});
                Ext.getCmp('mutasibarang').getForm().submit({
                    url: '<?= site_url("mutasi_barang/update_row") ?>',
                    scope: this,
                    params: {detail: Ext.util.JSON.encode(detailmutasibarang)},
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
                                wininv_mutasi_barang.show();
                                Ext.getDom('wininv_mutasi_barang').src = r.printUrl;
                            }
                        });

                        clearmutasibarang();
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
                    clearmutasibarang();
                }
            }]
        });

    function clearmutasibarang(){
        Ext.getCmp('id_no_ref').setValue('');
        Ext.getCmp('mutasibarang').getForm().reset();
        strmutasibarang.removeAll();
    }
</script>
