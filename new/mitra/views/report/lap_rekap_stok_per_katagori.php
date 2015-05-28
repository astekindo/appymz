<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
// -------- COMBOBOX Supplier --------------------
var smgridRSPKSupplier= new Ext.grid.CheckboxSelectionModel();

var strCbRSPKSupplier = new Ext.data.ArrayStore({
    fields: ['kd_supplier'],
    data : []
});

var strgridRSPKSupplier = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: ['kd_supplier', 'nama_supplier'],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("report/get_supplier") ?>',
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

var searchgridRSPKSupplier = new Ext.app.SearchField({
    store: strgridRSPKSupplier,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridRSPKSupplier'
});

var gridRSPKSupplier = new Ext.grid.GridPanel({
    store: strgridRSPKSupplier,
    stripeRows: true,
    frame: true,
    border:true,
    sm: smgridRSPKSupplier,
    columns: [
        smgridRSPKSupplier,
        {
            header: 'ID Supplier',
            dataIndex: 'kd_supplier',
            width: 80,
            sortable: true

        },
        {
            header: 'Nama Supplier',
            dataIndex: 'nama_supplier',
            width: 300,
            sortable: true
        }
    ],
    tbar: new Ext.Toolbar({
        items: [searchgridRSPKSupplier]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridRSPKSupplier,
        displayInfo: true
    })
});

var menuRSPKSupplier = new Ext.menu.Menu();

menuRSPKSupplier.add(new Ext.Panel({
    title: 'Pilih Supplier',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridRSPKSupplier],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridRSPKSupplier.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_rspk_kd_supplier_sel','kd_supplier',sel);
                    sm.clearSelections();
                }
                menuRSPKSupplier.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridRSPKSupplier.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_rspk_kd_supplier_sel','kd_supplier',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_rspk_kd_supplier_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuRSPKSupplier.hide(); }
        }]
}));

Ext.ux.TwinComboRSPKSupplier = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function(){
        //load store grid
        strgridRSPKSupplier.load();
        menuRSPKSupplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuRSPKSupplier.on('hide', function(){
    var sf = Ext.getCmp('id_searchgridRSPKSupplier').getValue();
    if( sf != ''){
        Ext.getCmp('id_searchgridRSPKSupplier').setValue('');
        searchgridRSPKSupplier.onTrigger2Click();
    }
});

var comboRSPKSupplier = new Ext.ux.TwinComboRSPKSupplier({
    fieldLabel: 'Supplier',
    id: 'id_cbRSPKSupplier',
    store: strCbRSPKSupplier,
    mode: 'local',
    valueField: 'kd_supplier',
    displayField: 'nama_supplier',
    typeAhead: true,
    triggerAction: 'all',
    // allowBlank: false,
    editable: false,
    anchor: '90%',
    hiddenName: 'kd_supplier',
    emptyText: 'Pilih Supplier'
});

// -------- COMBOBOX Supplier --------------------

//-------- COMBOBOX Ukuran ---------------------

var smgridRSPKUkuran = new Ext.grid.CheckboxSelectionModel();

var strRSPKUkuran = new Ext.data.ArrayStore({
    fields: ['kd_ukuran', 'nama_ukuran'],
    data: []
});

// GRID PANEL TWIN COMBOBOX Ukuran Data Store
var strgridRSPKUkuran = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: ['kd_ukuran', 'nama_ukuran'],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("report/get_ukuran") ?>',
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

strgridRSPKUkuran.on('load', function(){
    Ext.getCmp('id_searchgridRSPKUkuran').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX Ukuran
var searchgridRSPKUkuran = new Ext.app.SearchField({
    store: strgridRSPKUkuran,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridRSPKUkuran'
});

// GRID PANEL TWIN COMBOBOX Ukuran
var gridRSPKUkuran = new Ext.grid.GridPanel({
    store: strgridRSPKUkuran,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridRSPKUkuran,
    columns: [
        smgridRSPKUkuran,
        {
            header: 'Kode Ukuran',
            dataIndex: 'kd_ukuran',
            width: 100,
            sortable: true

        },
        {
            header: 'Nama Ukuran',
            dataIndex: 'nama_ukuran',
            width: 300,
            sortable: true
        }
    ],
    tbar: new Ext.Toolbar({
        items: [searchgridRSPKUkuran]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridRSPKUkuran,
        displayInfo: true
    })
});

var menuRSPKUkuran = new Ext.menu.Menu();

menuRSPKUkuran.add(new Ext.Panel({
    title: 'Pilih Ukuran',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridRSPKUkuran],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridRSPKUkuran.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_rspk_ukuran_sel','kd_ukuran',sel);
                    sm.clearSelections();
                }
                menuRSPKUkuran.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridRSPKUkuran.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_rspk_ukuran_sel','kd_ukuran',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_rspk_ukuran_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuRSPKUkuran.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX Ukuran
Ext.ux.TwinComboRSPKUkuran = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        //load store grid
        strgridRSPKUkuran.removeAll();
        strgridRSPKUkuran.load();
        menuRSPKUkuran.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuRSPKUkuran.on('hide', function () {
    var sf = Ext.getCmp('id_searchgridRSPKUkuran').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgridRSPKUkuran').setValue('');
        searchgridRSPKUkuran.onTrigger2Click();
    }
});

// TWIN COMBOBOX Ukuran
var comboRSPKUkuran = new Ext.ux.TwinComboRSPKUkuran({
    fieldLabel: 'Ukuran',
    id: 'id_cbRSPKUkuran',
    store: strRSPKUkuran,
    mode: 'local',
    valueField: 'kd_ukuran',
    displayField: 'kd_ukuran',
    typeAhead: true,
    triggerAction: 'all',
    editable: false,
    anchor: '90%',
    hiddenName: 'kd_ukuran',
    emptyText: 'Pilih Ukuran'
});
//-------- COMBOBOX Ukuran ---------------------


//-------- COMBOBOX Satuan ---------------------

var smgridRSPKSatuan = new Ext.grid.CheckboxSelectionModel();

var strRSPKSatuan = new Ext.data.ArrayStore({
    fields: ['kd_satuan', 'nm_satuan'],
    data: []
});

// GRID PANEL TWIN COMBOBOX Satuan Data Store
var strgridRSPKSatuan = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: ['kd_satuan', 'nm_satuan'],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("report/get_satuan") ?>',
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

strgridRSPKSatuan.on('load', function(){
    Ext.getCmp('id_searchgridRSPKSatuan').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX Satuan
var searchgridRSPKSatuan = new Ext.app.SearchField({
    store: strgridRSPKSatuan,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridRSPKSatuan'
});

// GRID PANEL TWIN COMBOBOX Satuan
var gridRSPKSatuan = new Ext.grid.GridPanel({
    store: strgridRSPKSatuan,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridRSPKSatuan,
    columns: [
        smgridRSPKSatuan,
        {
            header: 'Kode Satuan',
            dataIndex: 'kd_satuan',
            width: 100,
            sortable: true

        },
        {
            header: 'Nama Satuan',
            dataIndex: 'nm_satuan',
            width: 300,
            sortable: true
        }
    ],
    tbar: new Ext.Toolbar({
        items: [searchgridRSPKSatuan]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridRSPKSatuan,
        displayInfo: true
    })
});

var menuRSPKSatuan = new Ext.menu.Menu();

menuRSPKSatuan.add(new Ext.Panel({
    title: 'Pilih Satuan',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridRSPKSatuan],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridRSPKSatuan.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_rspk_satuan_sel','kd_satuan',sel);
                    sm.clearSelections();
                }
                menuRSPKSatuan.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridRSPKSatuan.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_rspk_satuan_sel','kd_satuan',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_rspk_satuan_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuRSPKSatuan.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX Satuan
Ext.ux.TwinComboRSPKSatuan = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        //load store grid
        strgridRSPKSatuan.removeAll();
        strgridRSPKSatuan.load();
        menuRSPKSatuan.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuRSPKSatuan.on('hide', function () {
    var sf = Ext.getCmp('id_searchgridRSPKSatuan').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgridRSPKSatuan').setValue('');
        searchgridRSPKSatuan.onTrigger2Click();
    }
});

// TWIN COMBOBOX Satuan
var comboRSPKSatuan = new Ext.ux.TwinComboRSPKSatuan({
    fieldLabel: 'Satuan',
    id: 'id_cbRSPKSatuan',
    store: strRSPKSatuan,
    mode: 'local',
    valueField: 'kd_satuan',
    displayField: 'kd_satuan',
    typeAhead: true,
    triggerAction: 'all',
    editable: false,
    anchor: '90%',
    hiddenName: 'kd_satuan',
    emptyText: 'Pilih Satuan'
});
//-------- COMBOBOX Satuan ---------------------


//-------- COMBOBOX Produk ---------------------

var smgridRSPKProduk = new Ext.grid.CheckboxSelectionModel();

var strRSPKProduk = new Ext.data.ArrayStore({
    fields: ['kd_produk', 'nama_produk'],
    data: []
});

// GRID PANEL TWIN COMBOBOX Produk Data Store
var strgridRSPKProduk = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: ['kd_produk', 'nama_produk'],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("report/get_produk") ?>',
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

strgridRSPKProduk.on('load', function(){
//    strgridRSPKProduk.setBaseParam('kategori1', Ext.getCmp('id_rspk_kategori1_sel').getValue());
//    strgridRSPKProduk.setBaseParam('kategori2', Ext.getCmp('id_rspk_kategori2_sel').getValue());
//    strgridRSPKProduk.setBaseParam('kategori3', Ext.getCmp('id_rspk_kategori3_sel').getValue());
//    strgridRSPKProduk.setBaseParam('kategori4', Ext.getCmp('id_rspk_kategori4_sel').getValue());
    strgridRSPKProduk.setBaseParam('ukuran', Ext.getCmp('id_rspk_ukuran_sel').getValue());
    strgridRSPKProduk.setBaseParam('satuan', Ext.getCmp('id_rspk_satuan_sel').getValue());
    Ext.getCmp('id_searchgridRSPKProduk').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX Produk
var searchgridRSPKProduk = new Ext.app.SearchField({
    store: strgridRSPKProduk,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridRSPKProduk'
});

// GRID PANEL TWIN COMBOBOX Produk
var gridRSPKProduk = new Ext.grid.GridPanel({
    store: strgridRSPKProduk,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridRSPKProduk,
    columns: [
        smgridRSPKProduk,
        {
            header: 'Kode Produk',
            dataIndex: 'kd_produk',
            width: 100,
            sortable: true

        },
        {
            header: 'Nama Produk',
            dataIndex: 'nama_produk',
            width: 300,
            sortable: true
        }
    ],
    tbar: new Ext.Toolbar({
        items: [searchgridRSPKProduk]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridRSPKProduk,
        displayInfo: true
    })
});

var menuRSPKProduk = new Ext.menu.Menu();

menuRSPKProduk.add(new Ext.Panel({
    title: 'Pilih Produk',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridRSPKProduk],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridRSPKProduk.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_rspk_produk_sel','kd_produk',sel);
                    sm.clearSelections();
                }
                menuRSPKProduk.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridRSPKProduk.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_rspk_produk_sel','kd_produk',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_rspk_produk_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuRSPKProduk.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX Produk
Ext.ux.TwinComboRSPKProduk = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridRSPKProduk.removeAll();
//        var kategori1   = Ext.getCmp('id_rspk_kategori1_sel').getValue();
//        var kategori2   = Ext.getCmp('id_rspk_kategori2_sel').getValue();
//        var kategori3   = Ext.getCmp('id_rspk_kategori3_sel').getValue();
//        var kategori4   = Ext.getCmp('id_rspk_kategori4_sel').getValue();
        var ukuran      = Ext.getCmp('id_rspk_ukuran_sel').getValue();
        var satuan      = Ext.getCmp('id_rspk_satuan_sel').getValue();
        strgridRSPKProduk.load({params: {
//            kategori1: kategori1,
//            kategori2: kategori2,
//            kategori3: kategori3,
//            kategori4: kategori4,
            ukuran: ukuran,
            satuan: satuan
        }});
        menuRSPKProduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuRSPKProduk.on('hide', function () {
    var sf = Ext.getCmp('id_searchgridRSPKProduk').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgridRSPKProduk').setValue('');
        searchgridRSPKProduk.onTrigger2Click();
    }
});

// TWIN COMBOBOX Produk
var comboRSPKProduk = new Ext.ux.TwinComboRSPKProduk({
    fieldLabel: 'Produk',
    id: 'id_cbRSPKProduk',
    store: strRSPKProduk,
    mode: 'local',
    valueField: 'kd_produk',
    displayField: 'kd_produk',
    typeAhead: true,
    triggerAction: 'all',
    editable: false,
    anchor: '90%',
    hiddenName: 'kd_produk',
    emptyText: 'Pilih Produk'
});
//-------- COMBOBOX Produk ---------------------

//-------- COMBOBOX Lokasi ---------------------
var strCbRSPKLokasi = new Ext.data.ArrayStore({
    fields: ['kd_lokasi', 'nama_lokasi'],
    data : []
});

var strGridRSPKLokasi = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: ['kd_lokasi', 'nama_lokasi', 'nama_lokasi2'],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("report/get_lokasi") ?>',
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

var searchGridRSPKLokasi = new Ext.app.SearchField({
    store: strGridRSPKLokasi,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchGridRSPKLokasi'
});
var gridRSPKLokasi = new Ext.grid.GridPanel({
    store: strGridRSPKLokasi,
    stripeRows: true,
    frame: true,
    border:true,
    columns: [
        {
            header: 'Kode Lokasi',
            dataIndex: 'kd_lokasi',
            width: 100,
            sortable: true

        },
        {
            header: 'Nama Lokasi',
            dataIndex: 'nama_lokasi',
            width: 200,
            sortable: true
        }
    ],

    tbar: new Ext.Toolbar({
        items: [searchGridRSPKLokasi]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strGridRSPKLokasi,
        displayInfo: true
    }),
    listeners: {
        'rowdblclick': function(){
            var sm = this.getSelectionModel();
            var sel = sm.getSelections();
            if (sel.length > 0) {
                Ext.getCmp('id_comboRSPKLokasi').setValue(sel[0].get('kd_lokasi'));
                Ext.getCmp('id_rspk_kd_lokasi_sel').setValue(sel[0].get('kd_lokasi'));
                Ext.getCmp('id_rspk_nama_lokasi_sel').setValue(sel[0].get('nama_lokasi'));
                menuRSPKLokasi.hide();
            }
        }
    }
});

var menuRSPKLokasi = new Ext.menu.Menu();
menuRSPKLokasi.add(new Ext.Panel({
    title: 'Pilih Lokasi',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 250,
    closeAction: 'hide',
    plain: true,
    items: [gridRSPKLokasi],
    buttons: [{
        text: 'Close',
        handler: function(){
            menuRSPKLokasi.hide();
        }
    }]
}));

Ext.ux.TwinComb_mlo_asal = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function(){
        //load store grid
        strGridRSPKLokasi.load();
        menuRSPKLokasi.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuRSPKLokasi.on('hide', function(){
    var sf = Ext.getCmp('id_searchGridRSPKLokasi').getValue();
    if( sf != ''){
        Ext.getCmp('id_searchGridRSPKLokasi').setValue('');
        searchGridRSPKLokasi.onTrigger2Click();
    }
});
//end twin lokasi
var comboRSPKLokasi = new Ext.ux.TwinComb_mlo_asal({
    fieldLabel: 'Lokasi Asal <span class="asterix">*</span>',
    id: 'id_comboRSPKLokasi',
    store: strCbRSPKLokasi,
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
//-------- COMBOBOX Lokasi ---------------------

// -------- COMBOBOX PERUNTUKAN ----------------
var dsRSPKPeruntukan=[['D',"Distribusi"],['B',"Bazar"],['S',"Supermarket"]];

var strRSPKPeruntukan = new Ext.data.ArrayStore({
    fields: [{name: 'kd_peruntukan'},{name: 'nama_peruntukan'}],
    data: dsRSPKPeruntukan
});

var comboRSPKPeruntukan = new Ext.form.ComboBox({
    fieldLabel: 'Peruntukkan',
    id: 'id_cb_rspk_peruntukan',
    name:'peruntukan',
    store: dsRSPKPeruntukan,
    valueField:'kd_peruntukan',
    hiddenName:'kd_peruntukan',
    displayField:'nama_peruntukan',
    mode:'local',
    forceSelection: true,
    triggerAction: 'all',
    anchor: '90%'
});
//-------- COMBOBOX PERUNTUKAN ----------------

//-------- COMBOBOX KONSINYASI ----------------
var strRSPKKonsinyasi = new Ext.data.ArrayStore({
    fields: [{name: 'kd_kons'},{name: 'nama_kons'}],
    data: [['A',"Semua"], ['Y',"Hanya Konsinyasi"], ['T',"Bukan Konsinyasi"]]
});

var comboRSPKKonsinyasi = new Ext.form.ComboBox({
    fieldLabel: 'Jenis',
    id: 'id_cb_rspk_konsinyasi',
    name:'konsinyasi',
    store: strRSPKKonsinyasi,
    valueField:'kd_kons',
    hiddenName:'kd_kons',
    displayField:'nama_kons',
    mode:'local',
    forceSelection: true,
    triggerAction: 'all',
    anchor: '90%'
});

//-------- COMBOBOX KONSINYASI ----------------

//-------- COMBOBOX STATUS --------------------
var strRSPKStatus = new Ext.data.ArrayStore({
    fields: [{name: 'kd_status'},{name: 'nama_status'}],
    data: [['A',"Semua"], ['Y',"Aktif"], ['T',"Tidak aktif"]]
});

var comboRSPKStatus = new Ext.form.ComboBox({
    fieldLabel: 'Status',
    id: 'id_cb_rspk_status',
    name:'konsinyasi',
    store: strRSPKStatus,
    valueField:'kd_status',
    hiddenName:'kd_status',
    displayField:'nama_status',
    mode:'local',
    forceSelection: true,
    triggerAction: 'all',
    anchor: '90%'
});
//-------- COMBOBOX STATUS --------------------

//-------- CHECKBOX SORT ORDER ----------------
var checkboxRSPKSort = new Ext.form.Checkbox({
    xtype: 'checkbox',
    fieldLabel: 'Sort Order',
    boxLabel: 'Descending',
    name: 'sort_order',
    id: 'id_rspk_sort',
    checked: true,
    inputValue: '1',
    autoLoad: true
});
//-------- CHECKBOX SORT ORDER ----------------

// -------- MAIN FORM -------------------------
var headerRSPKtanggal = {
    layout: 'column',
    border: false,
    items: [{
        columnWidth: .8,
        layout: 'form',
        border: false,
        labelWidth: 100,
        defaults: { labelSeparator: ''},
        items: [{
            xtype: 'fieldset',
            autoHeight: true,
            items: [{
                layout: 'column',
                items:[{
                    columnWidth: .5,
                    layout: 'form',
                    border: false,
                    labelWidth: 100,
                    defaults: { labelSeparator: ''},
                    items:[{
                        xtype: 'datefield',
                        fieldLabel: 'Dari Tgl <span class="asterix">*</span>',
                        name: 'dari_tgl',
                        allowBlank:false,
                        format:'d-m-Y',
                        editable:false,
                        id: 'id_rspk_tgl_awal',
                        anchor: '90%',
                        value: ''
                    },
                        comboRSPKLokasi,
                        comboRSPKSupplier,
                        comboRSPKUkuran,
                        comboRSPKSatuan,
                        comboRSPKProduk,
                        comboRSPKPeruntukan,
                        comboRSPKKonsinyasi
                    ]
                },
                    {
                        columnWidth: .5,
                        layout: 'form',
                        border: false,
                        labelWidth: 100,
                        defaults: { labelSeparator: ''},
                        items:[
                            {
                                xtype: 'datefield',
                                fieldLabel: 'Sampai Tgl <span class="asterix">*</span>',
                                name: 'sampai_tgl',
                                allowBlank:false,
                                editable:false,
                                format:'d-m-Y',
                                id: 'id_rspk_tgl_akhir',
                                anchor: '90%',
                                value: ''
                            },
                            {
                                xtype: 'hidden',
                                name: 'kd_lokasi',
                                id: 'id_rspk_kd_lokasi_sel'
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Nama Lokasi',
                                name: 'nama_lokasi',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_rspk_nama_lokasi_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Kd. Supplier',
                                name: 'kd_supplier_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_rspk_kd_supplier_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Ukuran',
                                name: 'kd_ukuran_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_rspk_ukuran_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Satuan',
                                name: 'kd_satuan_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_rspk_satuan_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Kode Produk',
                                name: 'kd_produk_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_rspk_produk_sel',
                                anchor: '90%',
                                value:''
                            },
                            comboRSPKStatus,
                            checkboxRSPKSort
                        ]
                    }]
            }]
        }]
    }]
}


var laporanKartuStok = new Ext.FormPanel({
    id: 'rpt_rekap_stok_per_supplier',
    border: false,
    frame: true,
    monitorValid: true,
    labelWidth: 130,
    items: [{
        bodyStyle: {
            margin: '0px 0px 15px 0px'
        },
        items: [{
            buttonAlign: 'left',
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: { labelSeparator: ''},
            items: [headerRSPKtanggal],
            buttons: [
                {
                    text: 'Print',
                    formBind:true,
                    handler: function () {
                        Ext.getCmp('rpt_rekap_stok_per_supplier').getForm().submit({
                            url: '<?= site_url("laporan_rekap_stok_per_katagori/get_report") ?>',
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
                                    }
                                });

                                clearform('rpt_rekap_stok_per_supplier');
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
                    text: 'Cancel',
                    handler: function(){clearform('rpt_rekap_stok_per_supplier');}
                }
            ]
        }]
    }
    ]
});

</script>