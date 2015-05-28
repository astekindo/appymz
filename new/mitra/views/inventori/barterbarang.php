<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>

<script type="text/javascript">

/**
 * Combo supplier
 **/
    var strBarterComboSupplier = new Ext.data.ArrayStore({
        fields: ['nama_supplier'],
        data : []
    });

    var strGridBarterSupplier = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_supplier', 'nama_supplier'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_create_request/search_supplier") ?>',
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

    var searchGridBarterSupplier = new Ext.app.SearchField({
        store: strGridBarterSupplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_barter_search_grid_supplier'
    });


    var gridBarterSupplier = new Ext.grid.GridPanel({
        store: strGridBarterSupplier,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
            header: 'Kode Supplier',
            dataIndex: 'kd_supplier',
            width: 80,
            sortable: true

        },{
            header: 'Nama Supplier',
            dataIndex: 'nama_supplier',
            width: 300,
            sortable: true
        }],
        tbar: new Ext.Toolbar({
            items: [searchGridBarterSupplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strGridBarterSupplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_barter_combo_supplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_barter_supplier_brg').setValue(sel[0].get('nama_supplier'));
                    strpembelianretur.removeAll();
                    menuBarterBarangSupplier.hide();
                }
            }
        }
    });

    var menuBarterBarangSupplier = new Ext.menu.Menu();

    menuBarterBarangSupplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridBarterSupplier],
        buttons: [{
            text: 'Close',
            handler: function(){
                menuBarterBarangSupplier.hide();
            }
        }]
    }));

    Ext.ux.TwinCombo_TransBrgSupplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            if( (Ext.getCmp('id_barter_combo_supplier').getValue() == "" && Ext.getCmp('id_barter_supplier_brg').getValue() == "") ||
                strGridBarter.getCount() == 0) {
                strGridBarterSupplier.load();
                menuBarterBarangSupplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
            } else {
                Ext.Msg.show({
                    title: 'Error',
                    msg: "Satu dokumen hanya untuk satu (1) supplier.",
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK
                });
            }
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menuBarterBarangSupplier.on('hide', function(){
        var sf = Ext.getCmp('id_barter_search_grid_supplier').getValue();
        if( sf != ''){
            Ext.getCmp('id_barter_search_grid_supplier').setValue('');
            searchGridBarterSupplier.onTrigger2Click();
        }
    });

    var comboBarterSupplier = new Ext.ux.TwinCombo_TransBrgSupplier({
        fieldLabel: 'Supplier <span class="asterix">*</span>',
        id: 'id_barter_combo_supplier',
        store: strBarterComboSupplier,
        mode: 'local',
        valueField: 'kd_supplier',
        displayField: 'kd_supplier',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_supplier',
        emptyText: 'Pilih Supplier'
    });

/**
 * Container u/ supplier
 **/

    var headerFormBarter ={
        xtype:'fieldset',
        layout: 'column',
        autoHeight:true,
        items: [{
            columnWidth: .4,
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: { labelSeparator: ''},
            items: [
            {
                xtype: 'textfield',
                fieldLabel: 'No. Barter <span class="asterix">*</span>',
                name: 'no_tso',
                readOnly:true,
                fieldClass:'readonly-input',
                id: 'id_notrans_brg',
                maxLength: 255,
                anchor: '90%',
                value:''
            },comboBarterSupplier
            ]
        },{
            columnWidth: .4,
            layout: 'form',
            border: false,
            labelWidth: 110,
            defaults: { labelSeparator: ''},
            items: [
            {
                xtype: 'datefield',
                fieldLabel: 'Tanggal Barter <span class="asterix">*</span>',
                name: 'tgl_barter',
                readOnly:true,
                fieldClass:'readonly-input',
                id: 'id_barter_tgl',
                maxLength: 255,
                anchor: '90%',
                format:'d-M-Y'
            },{
                xtype: 'textfield',
                fieldLabel: 'Nama Supplier <span class="asterix">*</span>',
                name: 'nm_supplier',
                readOnly:true,
                fieldClass:'readonly-input',
                id: 'id_barter_supplier_brg',
                maxLength: 255,
                anchor: '90%',
                value:''
            }]
        }]
    };

/**
 * Combo kategori1
 **/
    var strBarterComboKategori1 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori1', 'nama_kategori1'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("kategori2/get_kategori1") ?>',
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

    var barterComboKategori1 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 1 <span class="asterix">*</span>',
        id: 'id_barter_combo_kategori1',
        store: strBarterComboKategori1,
        valueField: 'kd_kategori1',
        displayField: 'nama_kategori1',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori1',
        emptyText: 'Pilih kategori 1',
        listeners: {
            'select': function(combo, records) {
                var kdbrg_cbkategori1 = barterComboKategori1.getValue();
                barterComboKategori2.setValue();
                barterComboKategori2.store.proxy.conn.url = '<?= site_url("kategori3/get_kategori2") ?>/' + kdbrg_cbkategori1;
                barterComboKategori2.store.reload();
            }
        }
    });

/**
 * Combo kategori2
 **/
    var strBarterComboKategori2 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori2', 'nama_kategori2'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("kategori3/get_kategori2") ?>',
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

    var barterComboKategori2 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 2 <span class="asterix">*</span>',
        id: 'id_barter_combo_kategori2',
        mode: 'local',
        store: strBarterComboKategori2,
        valueField: 'kd_kategori2',
        displayField: 'nama_kategori2',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori2',
        emptyText: 'Pilih kategori 2',
        listeners: {
            select: function(combo, records) {
                var kd_brg_cbkategori1 = barterComboKategori1.getValue();
                var kd_brg_cbkategori2 = this.getValue();
                barterComboKategori3.setValue();
                barterComboKategori3.store.proxy.conn.url = '<?= site_url("kategori4/get_kategori3") ?>/' + kd_brg_cbkategori1 +'/'+ kd_brg_cbkategori2;
                barterComboKategori3.store.reload();
            }
        }
    });

/**
 * Combo kategori3
 **/
    var strBarterComboKategori3 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori3', 'nama_kategori3'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("kategori4/get_kategori3") ?>',
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

    var barterComboKategori3 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 3 <span class="asterix">*</span>',
        id: 'id_barter_combo_kategori3',
        mode: 'local',
        store: strBarterComboKategori3,
        valueField: 'kd_kategori3',
        displayField: 'nama_kategori3',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori3',
        emptyText: 'Pilih kategori 3',
        listeners: {
            select: function(combo, records) {
                var kd_brg_cbkategori1 = barterComboKategori1.getValue();
                var kd_brg_cbkategori2 = barterComboKategori2.getValue();
                var kd_brg_cbkategori3 = this.getValue();
                barterComboKategori4.setValue();
                barterComboKategori4.store.proxy.conn.url = '<?= site_url("master_barang/get_kategori4") ?>/' + kd_brg_cbkategori1 +'/'+ kd_brg_cbkategori2 +'/'+ kd_brg_cbkategori3;
                barterComboKategori4.store.reload();
            }
        }
    });

/**
 * Combo kategori4
 **/
    var strBarterKategori4 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori4', 'nama_kategori4'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_barang/get_kategori4") ?>',
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

    var barterComboKategori4 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 4 <span class="asterix">*</span>',
        id: 'id_barter_combo_kategori4',
        mode: 'local',
        store: strBarterKategori4,
        valueField: 'kd_kategori4',
        displayField: 'nama_kategori4',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori4',
        emptyText: 'Pilih kategori 4'
    });

/**
 * Combo ukuran
 **/
    var strBarterComboUkuran = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_ukuran', 'nama_ukuran'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_barang/get_ukuran_produk") ?>',
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

    var barterComboUkuran = new Ext.form.ComboBox({
        fieldLabel: 'Ukuran <span class="asterix">*</span>',
        id: 'id_barter_combo_ukuran',
        store: strBarterComboUkuran,
        valueField: 'kd_ukuran',
        displayField: 'nama_ukuran',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_ukuran',
        emptyText: 'Pilih Ukuran'

    });

/**
 * Combo satuan
 **/
    var strBarterComboSatuan = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_satuan', 'nm_satuan'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_barang/get_satuan_produk") ?>',
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

    var barterComboSatuan = new Ext.form.ComboBox({
        fieldLabel: 'Satuan <span class="asterix">*</span>',
        id: 'id_barter_combo_satuan',
        store: strBarterComboSatuan,
        valueField: 'kd_satuan',
        displayField: 'nm_satuan',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_satuan',
        emptyText: 'Pilih Satuan'
    });

/**
 * Container filter barang
 **/
    var barterToggleGroupBarang = {
        xtype:'fieldset',
        id:'id_barter_group_barang',
        checkboxToggle:true,
        title: 'Barang',
        autoHeight:true,
        collapsed: false,
        layout: 'column',
        items :[{
            columnWidth: .4,
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: { labelSeparator: ''},
            items: [barterComboKategori1, barterComboKategori2, barterComboSatuan]
        },{
            columnWidth: .4,
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: { labelSeparator: ''},
            items: [barterComboKategori3, barterComboKategori4, barterComboUkuran]
        }],
        listeners:{
            collapse: function(n){
                Ext.getCmp('id_barter_group_po').expand();
            },
            expand: function(n){
                Ext.getCmp('id_barter_group_po').collapse();
            }
        }
    }

/**
 * Container pilih PO
 **/
    var barterToggleGroupPO={
        xtype:'fieldset',
        id:'id_barter_group_po',
        checkboxToggle:true,
        title: 'No.PO',
        autoHeight:true,
        collapsed: true,
        layout: 'column',
        items :[{columnWidth: .4,
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: { labelSeparator: ''},
            items: [{
                id:'id_barter_no_po',
                xtype:'textfield',
                fieldLabel: 'No.PO',
                name: 'no_po',
                anchor: '90%',
            }]
        }],
        listeners:{
            collapse: function(n){
                Ext.getCmp('id_barter_group_barang').expand();
            },
            expand: function(n){
                Ext.getCmp('id_barter_group_barang').collapse();
            }
        }
    }

/**
 * Container Keterangan
 **/
    var barterFieldsetKeterangan={
        xtype:'fieldset',
        id:'id_barter_fs_keterangan',
        title: 'Keterangan',
        autoHeight:true,
        collapsed: false,
        layout: 'column',
        items :[{columnWidth: .8,
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: { labelSeparator: ''},
            items: [{
                xtype: 'textarea',
                fieldLabel: 'Keterangan',
                allowBlank: false,
                maxLength: 200,
                name: 'keterangan',
                id: 'id_barter_keterangan',
                anchor: '90%'
            }]
        }]
    }

/**
 * Store u/ grid barang
 **/
    var strBarterComboProduk = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data : []
    });

    var strBarterGridProduk = new Ext.data.Store({
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
            url: '<?= site_url("barterbarang/search_barang") ?>',
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

    var searchBarterGridProduk = new Ext.app.SearchField({
        store: strBarterGridProduk,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_search_grid_barter_produk'
    });

    strBarterGridProduk.on('load', function(){
        this.setBaseParam('supplier', Ext.getCmp('id_barter_combo_supplier').getValue());
        this.setBaseParam('no_po', Ext.getCmp('id_barter_no_po').getValue());
        this.setBaseParam('kategori1', Ext.getCmp('id_barter_combo_kategori1').getValue());
        this.setBaseParam('kategori2', Ext.getCmp('id_barter_combo_kategori2').getValue());
        this.setBaseParam('kategori3', Ext.getCmp('id_barter_combo_kategori3').getValue());
        this.setBaseParam('kategori4', Ext.getCmp('id_barter_combo_kategori4').getValue());
        this.setBaseParam('satuan', Ext.getCmp('id_barter_combo_satuan').getValue());
        this.setBaseParam('ukuran', Ext.getCmp('id_barter_combo_ukuran').getValue());
    });

    searchBarterGridProduk.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';

            var supplier    = Ext.getCmp('id_barter_combo_supplier').getValue();
            var no_po       = Ext.getCmp('id_barter_no_po').getValue();
            var kategori1   = Ext.getCmp('id_barter_combo_kategori1').getValue();
            var kategori2   = Ext.getCmp('id_barter_combo_kategori2').getValue();
            var kategori3   = Ext.getCmp('id_barter_combo_kategori3').getValue();
            var kategori4   = Ext.getCmp('id_barter_combo_kategori4').getValue();
            var satuan      = Ext.getCmp('id_barter_combo_satuan').getValue();
            var ukuran      = Ext.getCmp('id_barter_combo_ukuran').getValue();
            this.store.reload({
                params : {
                    start: 0,
                    no_po: no_po,
                    kategori1: kategori1,
                    kategori2: kategori2,
                    kategori3: kategori3,
                    kategori4: kategori4,
                    satuan: satuan,
                    ukuran: ukuran
                }
            });
            this.triggers[0].hide();
            this.hasSearch = false;
        }
    };

    searchBarterGridProduk.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }

        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;

        var supplier    = Ext.getCmp('id_barter_combo_supplier').getValue();
        var no_po       = Ext.getCmp('id_barter_no_po').getValue();
        var kategori1   = Ext.getCmp('id_barter_combo_kategori1').getValue();
        var kategori2   = Ext.getCmp('id_barter_combo_kategori2').getValue();
        var kategori3   = Ext.getCmp('id_barter_combo_kategori3').getValue();
        var kategori4   = Ext.getCmp('id_barter_combo_kategori4').getValue();
        var satuan      = Ext.getCmp('id_barter_combo_satuan').getValue();
        var ukuran      = Ext.getCmp('id_barter_combo_ukuran').getValue();
        this.store.reload({
            params : {
                start: 0,
                no_po: no_po,
                kategori1: kategori1,
                kategori2: kategori2,
                kategori3: kategori3,
                kategori4: kategori4,
                satuan: satuan,
                ukuran: ukuran
            }
        });
        this.hasSearch = true;
        this.triggers[0].show();
    };

    var barterGridProduk = new Ext.grid.GridPanel({
        store: strBarterGridProduk,
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
            }],

        tbar: new Ext.Toolbar({
            items: [searchBarterGridProduk]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strBarterGridProduk,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('bart_g_kd_produk').setValue(sel[0].get('kd_produk'));
                    Ext.getCmp('bart_g_nama_produk').setValue(sel[0].get('nama_produk'));
                    Ext.getCmp('bart_g_nm_satuan').setValue(sel[0].get('nm_satuan'));
                    Ext.getCmp('bart_g_qty_oh').setValue(sel[0].get('qty_oh'));
                    menuBarterProduk.hide();
                }
            }
        }
    });

    var menuBarterProduk = new Ext.menu.Menu();
    menuBarterProduk.add(new Ext.Panel({
        title: 'Pilih Barang',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 600,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [barterGridProduk],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menuBarterProduk.hide();
                }
            }]
    }));

    Ext.ux.comboBarterProduk = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            var supplier    = Ext.getCmp('id_barter_combo_supplier').getValue();
            var no_po       = Ext.getCmp('id_barter_no_po').getValue();
            var kategori1   = Ext.getCmp('id_barter_combo_kategori1').getValue();
            var kategori2   = Ext.getCmp('id_barter_combo_kategori2').getValue();
            var kategori3   = Ext.getCmp('id_barter_combo_kategori3').getValue();
            var kategori4   = Ext.getCmp('id_barter_combo_kategori4').getValue();
            var satuan      = Ext.getCmp('id_barter_combo_satuan').getValue();
            var ukuran      = Ext.getCmp('id_barter_combo_ukuran').getValue();
            strBarterGridProduk.load({
                params : {
                    supplier: supplier,
                    start: 0,
                    no_po: no_po,
                    kategori1: kategori1,
                    kategori2: kategori2,
                    kategori3: kategori3,
                    kategori4: kategori4,
                    satuan: satuan,
                    ukuran: ukuran
                }
            });
            menuBarterProduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

/**
 * Store u/ grid barang target
 **/
    var strBarterComboProdukTarget = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data : []
    });

    var strBarterGridProdukTarget = new Ext.data.Store({
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
            url: '<?= site_url("barterbarang/search_barang") ?>',
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

    var searchBarterGridProdukTarget = new Ext.app.SearchField({
        store: strBarterGridProdukTarget,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_search_grid_barter_produk_target'
    });

    strBarterGridProdukTarget.on('load', function(){
        this.setBaseParam('supplier', Ext.getCmp('id_barter_combo_supplier').getValue());
        this.setBaseParam('no_po', Ext.getCmp('id_barter_no_po').getValue());
        this.setBaseParam('kategori1', Ext.getCmp('id_barter_combo_kategori1').getValue());
        this.setBaseParam('kategori2', Ext.getCmp('id_barter_combo_kategori2').getValue());
        this.setBaseParam('kategori3', Ext.getCmp('id_barter_combo_kategori3').getValue());
        this.setBaseParam('kategori4', Ext.getCmp('id_barter_combo_kategori4').getValue());
        this.setBaseParam('satuan', Ext.getCmp('id_barter_combo_satuan').getValue());
        this.setBaseParam('ukuran', Ext.getCmp('id_barter_combo_ukuran').getValue());
    });

    searchBarterGridProdukTarget.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';

            var supplier    = Ext.getCmp('id_barter_combo_supplier').getValue();
            var no_po       = Ext.getCmp('id_barter_no_po').getValue();
            var kategori1   = Ext.getCmp('id_barter_combo_kategori1').getValue();
            var kategori2   = Ext.getCmp('id_barter_combo_kategori2').getValue();
            var kategori3   = Ext.getCmp('id_barter_combo_kategori3').getValue();
            var kategori4   = Ext.getCmp('id_barter_combo_kategori4').getValue();
            var satuan      = Ext.getCmp('id_barter_combo_satuan').getValue();
            var ukuran      = Ext.getCmp('id_barter_combo_ukuran').getValue();
            this.store.reload({
                params : {
                    start: 0,
                    no_po: no_po,
                    kategori1: kategori1,
                    kategori2: kategori2,
                    kategori3: kategori3,
                    kategori4: kategori4,
                    satuan: satuan,
                    ukuran: ukuran
                }
            });
            this.triggers[0].hide();
            this.hasSearch = false;
        }
    };

    searchBarterGridProdukTarget.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }

        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;

        var supplier    = Ext.getCmp('id_barter_combo_supplier').getValue();
        var no_po       = Ext.getCmp('id_barter_no_po').getValue();
        var kategori1   = Ext.getCmp('id_barter_combo_kategori1').getValue();
        var kategori2   = Ext.getCmp('id_barter_combo_kategori2').getValue();
        var kategori3   = Ext.getCmp('id_barter_combo_kategori3').getValue();
        var kategori4   = Ext.getCmp('id_barter_combo_kategori4').getValue();
        var satuan      = Ext.getCmp('id_barter_combo_satuan').getValue();
        var ukuran      = Ext.getCmp('id_barter_combo_ukuran').getValue();
        this.store.reload({
            params : {
                start: 0,
                no_po: no_po,
                kategori1: kategori1,
                kategori2: kategori2,
                kategori3: kategori3,
                kategori4: kategori4,
                satuan: satuan,
                ukuran: ukuran
            }
        });
        this.hasSearch = true;
        this.triggers[0].show();
    };

    var barterGridProdukTarget = new Ext.grid.GridPanel({
        store: strBarterGridProdukTarget,
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
            }],

        tbar: new Ext.Toolbar({
            items: [searchBarterGridProdukTarget]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strBarterGridProdukTarget,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('bart_g_kd_produk_target').setValue(sel[0].get('kd_produk'));
                    Ext.getCmp('bart_g_nama_produk_target').setValue(sel[0].get('nama_produk'));
                    menuBarterProdukTarget.hide();
                }
            }
        }
    });

    var menuBarterProdukTarget = new Ext.menu.Menu();
    menuBarterProdukTarget.add(new Ext.Panel({
        title: 'Pilih Barang',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 600,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [barterGridProdukTarget],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menuBarterProdukTarget.hide();
                }
            }]
    }));

    Ext.ux.comboBarterProdukTarget = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            var supplier    = Ext.getCmp('id_barter_combo_supplier').getValue();
            var no_po       = Ext.getCmp('id_barter_no_po').getValue();
            var kategori1   = Ext.getCmp('id_barter_combo_kategori1').getValue();
            var kategori2   = Ext.getCmp('id_barter_combo_kategori2').getValue();
            var kategori3   = Ext.getCmp('id_barter_combo_kategori3').getValue();
            var kategori4   = Ext.getCmp('id_barter_combo_kategori4').getValue();
            var satuan      = Ext.getCmp('id_barter_combo_satuan').getValue();
            var ukuran      = Ext.getCmp('id_barter_combo_ukuran').getValue();
            strBarterGridProdukTarget.load({
                params : {
                    supplier: supplier,
                    start: 0,
                    no_po: no_po,
                    kategori1: kategori1,
                    kategori2: kategori2,
                    kategori3: kategori3,
                    kategori4: kategori4,
                    satuan: satuan,
                    ukuran: ukuran
                }
            });
            menuBarterProdukTarget.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    //end twin produk

/**
 * Store u/ grid barang
 **/
    var strGridBarter= new Ext.data.Store({
        autoSave:false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_produk', type: 'text'},
                {name: 'nama_produk', type: 'text'},
                {name: 'qty', type: 'int'},
                {name: 'satuan', type: 'text'},
                {name: 'kd_produk_target', type: 'text'},
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("barterbarang/search_barang") ?>',
            method: 'POST'
        }),
        writer: new Ext.data.JsonWriter({
            encode: true,
            writeAllFields: true
        })
    });

    var barterRowEditor = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });

    var gridBarterBarang = new Ext.grid.GridPanel({
        store: strGridBarter,
        stripeRows: true,
        height: 250,
        frame: true,
        border:true,
        plugins:[barterRowEditor],
        columns: [
        {
            header: 'Kode produk',
            dataIndex: 'kd_produk',
            width: 110,
            editor: new Ext.ux.comboBarterProduk({ store: strBarterComboProduk, id: 'bart_g_kd_produk' })
        },{
            header: 'Nama Barang',
            dataIndex: 'nama_produk',
            width: 250,
            editor: new Ext.form.TextField({ readOnly: true, fieldClass: 'readonly-input', id: 'bart_g_nama_produk' })
        },{
            header: 'Satuan',
            dataIndex: 'nm_satuan',
            width: 60,
            editor: new Ext.form.TextField({ readOnly: true, fieldClass: 'readonly-input', id: 'bart_g_nm_satuan' })
        },{
            xtype: 'numbercolumn',
            header: 'Qty OH',
            dataIndex: 'qty_oh',
            width: 50,
            align: 'right',
            sortable: true,
            format: '0,0',
            editor: new Ext.form.TextField({ readOnly: true, fieldClass: 'readonly-input', id: 'bart_g_qty_oh' })
        },{
            xtype: 'numbercolumn',
            header: 'Qty',
            dataIndex: 'qty',
            width: 50,
            align: 'right',
            sortable: true,
            format: '0,0',
            editor: new Ext.form.NumberField({ id: 'bart_g_qty', allowBlank: false, allowDecimals: false, allowNegative: false, listeners: {
                change: function() {
                    var qty_oh = Ext.getCmp('bart_g_qty_oh').getValue();
                    if(Number(qty_oh) < Number(this.getValue()) ) {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Quantity barter melebihi quantity on hand',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK,
                            fn: function(btn){
                                if (btn == 'ok') {
                                    this.setValue('0');
                                }
                            }
                        });

                    }
                }
            } })
        },{
            header: 'Kd. produk Barter',
            dataIndex: 'kd_produk_target',
            width: 110,
           editor: new Ext.ux.comboBarterProdukTarget({ store: strBarterComboProdukTarget, id: 'bart_g_kd_produk_target' })
        },{
            header: 'Nama Barang Barter',
            dataIndex: 'nama_produk_target',
            width: 250,
            editor: new Ext.form.TextField({ readOnly: true, fieldClass: 'readonly-input', id: 'bart_g_nama_produk_target' })
        }],
        tbar: [{
            icon: BASE_ICONS + 'add.png',
            text: 'Add',
            handler: function(){
                var allowed = true;
                var detail = "<br/>";
                if(Ext.getCmp('id_barter_combo_supplier').getValue() == "") {
                    allowed = false;
                    detail += "- Supplier belum dipilih<br/>";
                }
                if(Ext.getCmp('id_barter_group_barang').collapsed && allowed) {
                    var no_po     = Ext.getCmp('id_barter_no_po').getValue();
                    if(no_po == "") {
                        allowed = false;
                        detail += "- No. PO belum di isi<br/>";
                    }
                }
                if(Ext.getCmp('id_barter_group_po').collapsed && allowed) {
                    var check = [
                        {"name": "Kategori 1", value: Ext.getCmp('id_barter_combo_kategori1').getValue()},
                        {"name": "Kategori 2", value: Ext.getCmp('id_barter_combo_kategori2').getValue()},
                        {"name": "Kategori 3", value: Ext.getCmp('id_barter_combo_kategori3').getValue()},
                        {"name": "Kategori 4", value: Ext.getCmp('id_barter_combo_kategori4').getValue()},
                        {"name": "Satuan", value: Ext.getCmp('id_barter_combo_satuan').getValue()},
                        {"name": "Ukuran", value: Ext.getCmp('id_barter_combo_ukuran').getValue()}
                    ];
                    check.map(function(item) {
                        if(item.value == "") {
                            allowed = false;
                            detail += "- " + item.name + " belum di set<br/>";
                        }
                    });
                }
                if(allowed) {
                        var newRow = new gridBarterBarang.store.recordType({
                            kd_produk: '',
                            nama_produk: '',
                            nm_satuan: '',
                            qty: '',
                            kd_produk_target: '',
                            nama_produk_target: ''
                        });
                        barterRowEditor.stopEditing();
                        strGridBarter.insert(0, newRow);
                        gridBarterBarang.getView().refresh();
                        gridBarterBarang.getSelectionModel().selectRow(0);
                        barterRowEditor.startEditing(0);

                } else {
                    Ext.Msg.show({
                        title: 'Error',
                        msg: 'Data belum lengkap:' + detail,
                        modal: true,
                        icon: Ext.Msg.ERROR,
                        buttons: Ext.Msg.OK
                    });
                }
            }
        },{
            ref: '../removeBtn',
            icon: BASE_ICONS + 'delete.gif',
            text: 'Remove',
            handler: function(){
                barterRowEditor.stopEditing();
                var s = gridBarterBarang.getSelectionModel().getSelections();
                for(var i = 0, r; r = s[i]; i++){
                    strGridBarter.remove(r);
                }
            }
        }]
    });



    var formBarterBarang = new Ext.FormPanel({
        id: 'barterbarang',
        border: false,
        frame: true,
        autoScroll:true,
        monitorValid: true,
        bodyStyle:'padding-right:20px;',
        labelWidth: 130,
        items:[
            headerFormBarter,
            barterToggleGroupBarang,
            barterToggleGroupPO,
            gridBarterBarang,
            barterFieldsetKeterangan
        ],
        buttons:[{
            text: 'Save',
            formBind: true,
            handler: function(){
                var detail = new Array();
                strGridBarter.each(function(node){detail.push(node.data)});
                formBarterBarang.getForm().submit({
                    url: '<?= site_url("barterbarang/update_row") ?>',
                    scope: this,
                    params: {
                        supplier: Ext.getCmp('id_barter_combo_supplier').getValue(),
                        data: Ext.util.JSON.encode(detail)
                    },
                    waitMsg: 'Saving Data...',
                    success: function(form, action){
                        var fe = Ext.util.JSON.decode(action.response.responseText);
                        Ext.Msg.show({
                            title: 'Success',
                            msg: fe.successMsg,
                            modal: true,
                            icon: Ext.Msg.INFO,
                            buttons: Ext.Msg.OK,
                            // fn: function(btn){}
                        });
                        strGridBarter.removeAll();
                        formBarterBarang.getForm().reset();
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
            text: 'Reset',
            handler: function(){
                strGridBarter.removeAll();
                formBarterBarang.getForm().reset();
            }
        }]
    });
</script>
