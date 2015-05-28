<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
//-------- COMBOBOX SUPPLIER --------------------
var smGridLPPSSupplier = new Ext.grid.CheckboxSelectionModel();

var strReportJualLPPSSupplier = new Ext.data.ArrayStore({
    fields: ['kd_supplier'],
    data: []
});

// GRID PANEL TWIN COMBOBOX supplier Data Store
var strGridReportJualLPPSSupplier = new Ext.data.Store({
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

        loadexception: function (event, options, response, error) {
            var err = Ext.util.JSON.decode(response.responseText);
            if (err.errMsg == 'Session Expired') {
                session_expired(err.errMsg);
            }
        }
    }
});

// SEARCH GRID PANEL TWIN COMBOBOX supplier
var searchGridReportJualLPPSSupplier = new Ext.app.SearchField({
    store: strGridReportJualLPPSSupplier,
    width: 350,
    id: 'id_searchGridReportJualLPPSSupplier'
});

// GRID PANEL TWIN COMBOBOX supplier
var GridReportJualLPPSSupplier = new Ext.grid.GridPanel({
    store: strGridReportJualLPPSSupplier,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smGridLPPSSupplier,
    columns: [
        smGridLPPSSupplier,
        {
            header: 'Kode Supplier',
            dataIndex: 'kd_supplier',
            width: 80,
            sortable: true

        }, {
            header: 'Nama Supplier',
            dataIndex: 'nama_supplier',
            width: 300,
            sortable: true
        }],
    tbar: new Ext.Toolbar({
        items: [searchGridReportJualLPPSSupplier]
    })
});

// PANEL TWIN COMBOBOX supplier
var menuReportJualLPPSSupplier = new Ext.menu.Menu();

menuReportJualLPPSSupplier.add(new Ext.Panel({
    title: 'Pilih Supplier',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [GridReportJualLPPSSupplier],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = GridReportJualLPPSSupplier.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpps_kd_supplier_sel','kd_supplier',sel);
                    sm.clearSelections();
                }
                menuReportJualLPPSSupplier.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = GridReportJualLPPSSupplier.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpps_kd_supplier_sel','kd_supplier',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lpps_kd_supplier_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuReportJualLPPSSupplier.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX supplier
Ext.ux.TwinComboSuplier = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        //load store grid
        strGridReportJualLPPSSupplier.load();
        menuReportJualLPPSSupplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuReportJualLPPSSupplier.on('hide', function () {
    var sf = Ext.getCmp('id_searchGridReportJualLPPSSupplier').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchGridReportJualLPPSSupplier').setValue('');
        searchGridReportJualLPPSSupplier.onTrigger2Click();
    }
});

// TWIN COMBOBOX supplier
var comboLPPSSupplier = new Ext.ux.TwinComboSuplier({
    fieldLabel: 'Supplier',
    id: 'id_cbReportJualLPPSSupplier',
    store: strReportJualLPPSSupplier,
    mode: 'local',
    valueField: 'kd_supplier',
    displayField: 'kd_supplier',
    typeAhead: true,
    triggerAction: 'all',
    // allowBlank: false,
    editable: false,
    anchor: '90%',
    hiddenName: 'kd_supplier',
    emptyText: 'Pilih Supplier'
});
//-------- COMBOBOX SUPPLIER ---------------------

//-------- COMBOBOX KATEGORI1 ---------------------

var smgridLPPSKategori1 = new Ext.grid.CheckboxSelectionModel();

var strLPPSKategori1 = new Ext.data.ArrayStore({
    fields: ['kd_kategori1', 'nama_kategori1'],
    data: []
});

// GRID PANEL TWIN COMBOBOX kategori1 Data Store
var strgridLPPSKategori1 = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: ['kd_kategori1', 'nama_kategori1'],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("report/get_kategori1") ?>',
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

strgridLPPSKategori1.on('load', function(){
    Ext.getCmp('id_searchgridLPPSKategori1').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX kategori1
var searchgridLPPSKategori1 = new Ext.app.SearchField({
    store: strgridLPPSKategori1,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridLPPSKategori1'
});

// GRID PANEL TWIN COMBOBOX kategori1
var gridLPPSKategori1 = new Ext.grid.GridPanel({
    store: strgridLPPSKategori1,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridLPPSKategori1,
    columns: [
        smgridLPPSKategori1,
        {
            header: 'Kode kategori 1',
            dataIndex: 'kd_kategori1',
            width: 100,
            sortable: true

        },
        {
            header: 'Nama kategori 1',
            dataIndex: 'nama_kategori1',
            width: 300,
            sortable: true
        }
    ],
    tbar: new Ext.Toolbar({
        items: [searchgridLPPSKategori1]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridLPPSKategori1,
        displayInfo: true
    })
});

var menuLPPSKategori1 = new Ext.menu.Menu();

menuLPPSKategori1.add(new Ext.Panel({
    title: 'Pilih Kategori 1',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridLPPSKategori1],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridLPPSKategori1.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpps_kategori1_sel','kd_kategori1',sel);
                    sm.clearSelections();
                }
                menuLPPSKategori1.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridLPPSKategori1.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpps_kategori1_sel','kd_kategori1',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lpps_kategori1_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuLPPSKategori1.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX kategori1
Ext.ux.TwinComboLPPSKategori1 = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        //load store grid
        strgridLPPSKategori1.removeAll();
        strgridLPPSKategori1.load();
        menuLPPSKategori1.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuLPPSKategori1.on('hide', function () {
    var sf = Ext.getCmp('id_searchgridLPPSKategori1').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgridLPPSKategori1').setValue('');
        searchgridLPPSKategori1.onTrigger2Click();
    }
});

// TWIN COMBOBOX kategori1
var comboLPPSKategori1 = new Ext.ux.TwinComboLPPSKategori1({
    fieldLabel: 'Kategori 1',
    id: 'id_cbLPPSKategori1',
    store: strLPPSKategori1,
    mode: 'local',
    valueField: 'kd_kategori1',
    displayField: 'kd_kategori1',
    typeAhead: true,
    triggerAction: 'all',
    editable: false,
    anchor: '90%',
    hiddenName: 'kd_kategori1',
    emptyText: 'Pilih Kategori 1'
});
//-------- COMBOBOX KATEGORI1 ---------------------

//-------- COMBOBOX KATEGORI2 ---------------------

var smgridLPPSKategori2 = new Ext.grid.CheckboxSelectionModel();

var strLPPSKategori2 = new Ext.data.ArrayStore({
    fields: ['kd_kategori2', 'nama_kategori2'],
    data: []
});

// GRID PANEL TWIN COMBOBOX kategori2 Data Store
var strgridLPPSKategori2 = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: ['kd_kategori2', 'nama_kategori2', 'kd_kategori', 'nama_kategori'],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("report/get_kategori2") ?>',
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

strgridLPPSKategori2.on('load', function(){
    strgridLPPSKategori2.setBaseParam('kategori1', Ext.getCmp('id_lpps_kategori1_sel').getValue());
    Ext.getCmp('id_searchgridLPPSKategori2').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX kategori2
var searchgridLPPSKategori2 = new Ext.app.SearchField({
    store: strgridLPPSKategori2,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridLPPSKategori2'
});

// GRID PANEL TWIN COMBOBOX kategori2
var gridLPPSKategori2 = new Ext.grid.GridPanel({
    store: strgridLPPSKategori2,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridLPPSKategori2,
    columns: [
        smgridLPPSKategori2,
        {
            header: 'Kode kategori 2',
            dataIndex: 'kd_kategori2',
            width: 100,
            sortable: true

        },
        {
            header: 'Nama kategori 2',
            dataIndex: 'nama_kategori',
            width: 300,
            sortable: true
        }
    ],
    tbar: new Ext.Toolbar({
        items: [searchgridLPPSKategori2]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridLPPSKategori2,
        displayInfo: true
    })
});

var menuLPPSKategori2 = new Ext.menu.Menu();

menuLPPSKategori2.add(new Ext.Panel({
    title: 'Pilih Kategori 2',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridLPPSKategori2],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridLPPSKategori2.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpps_kategori2_sel','kd_kategori2',sel);
                    sm.clearSelections();
                }
                menuLPPSKategori2.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridLPPSKategori2.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpps_kategori2_sel','kd_kategori2',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lpps_kategori2_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuLPPSKategori2.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX kategori2
Ext.ux.TwinComboLPPSKategori2 = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridLPPSKategori2.removeAll();
        var kategori1 = Ext.getCmp('id_lpps_kategori1_sel').getValue();
        if(kategori1.length>0) {
            strgridLPPSKategori2.load({params: {kategori1: kategori1}});
        } else {
            strgridLPPSKategori2.load();
        }
        menuLPPSKategori2.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuLPPSKategori2.on('hide', function () {
    var sf = Ext.getCmp('id_searchgridLPPSKategori2').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgridLPPSKategori2').setValue('');
        searchgridLPPSKategori2.onTrigger2Click();
    }
});

// TWIN COMBOBOX kategori2
var comboLPPSKategori2 = new Ext.ux.TwinComboLPPSKategori2({
    fieldLabel: 'Kategori 2',
    id: 'id_cbLPPSKategori2',
    store: strLPPSKategori2,
    mode: 'local',
    valueField: 'kd_kategori2',
    displayField: 'nama_kategori2',
    typeAhead: true,
    triggerAction: 'all',
    editable: false,
    anchor: '90%',
    hiddenName: 'kd_kategori2',
    emptyText: 'Pilih Kategori 2'
});
//-------- COMBOBOX KATEGORI2 ---------------------

//-------- COMBOBOX KATEGORI3 ---------------------

var smgridLPPSKategori3 = new Ext.grid.CheckboxSelectionModel();

var strLPPSKategori3 = new Ext.data.ArrayStore({
    fields: ['kd_kategori3', 'nama_kategori3'],
    data: []
});

// GRID PANEL TWIN COMBOBOX kategori3 Data Store
var strgridLPPSKategori3 = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: ['kd_kategori3', 'nama_kategori3', 'kd_kategori', 'nama_kategori'],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("report/get_kategori3") ?>',
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

strgridLPPSKategori3.on('load', function(){
    strgridLPPSKategori3.setBaseParam('kategori1', Ext.getCmp('id_lpps_kategori1_sel').getValue());
    strgridLPPSKategori3.setBaseParam('kategori2', Ext.getCmp('id_lpps_kategori2_sel').getValue());
    Ext.getCmp('id_searchgridLPPSKategori3').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX kategori3
var searchgridLPPSKategori3 = new Ext.app.SearchField({
    store: strgridLPPSKategori3,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridLPPSKategori3'
});

// GRID PANEL TWIN COMBOBOX kategori3
var gridLPPSKategori3 = new Ext.grid.GridPanel({
    store: strgridLPPSKategori3,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridLPPSKategori3,
    columns: [
        smgridLPPSKategori3,
        {
            header: 'Kode kategori 3',
            dataIndex: 'kd_kategori3',
            width: 100,
            sortable: true

        },
        {
            header: 'Nama kategori 3',
            dataIndex: 'nama_kategori',
            width: 300,
            sortable: true
        }
    ],
    tbar: new Ext.Toolbar({
        items: [searchgridLPPSKategori3]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridLPPSKategori3,
        displayInfo: true
    })
});

var menuLPPSKategori3 = new Ext.menu.Menu();

menuLPPSKategori3.add(new Ext.Panel({
    title: 'Pilih Kategori 3',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridLPPSKategori3],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridLPPSKategori3.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpps_kategori3_sel','kd_kategori3',sel);
                    sm.clearSelections();
                }
                menuLPPSKategori3.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridLPPSKategori3.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpps_kategori3_sel','kd_kategori3',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lpps_kategori3_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuLPPSKategori3.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX kategori3
Ext.ux.TwinComboLPPSKategori3 = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridLPPSKategori3.removeAll();
        //load store grid
        var kategori1 = Ext.getCmp('id_lpps_kategori1_sel').getValue();
        var kategori2 = Ext.getCmp('id_lpps_kategori2_sel').getValue();
        if(kategori1.length > 0 || kategori2.length > 0 ) {
            strgridLPPSKategori3.load({params: {
                kategori1: kategori1,
                kategori2: kategori2
            }});
        } else {
            strgridLPPSKategori3.load();
        }
        menuLPPSKategori3.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuLPPSKategori3.on('hide', function () {
    var sf = Ext.getCmp('id_searchgridLPPSKategori3').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgridLPPSKategori3').setValue('');
        searchgridLPPSKategori3.onTrigger2Click();
    }
});

// TWIN COMBOBOX kategori3
var comboLPPSKategori3 = new Ext.ux.TwinComboLPPSKategori3({
    fieldLabel: 'Kategori 3',
    id: 'id_cbLPPSKategori3',
    store: strLPPSKategori3,
    mode: 'local',
    valueField: 'kd_kategori3',
    displayField: 'nama_kategori3',
    typeAhead: true,
    triggerAction: 'all',
    editable: false,
    anchor: '90%',
    hiddenName: 'kd_kategori3',
    emptyText: 'Pilih Kategori 3'
});
//-------- COMBOBOX KATEGORI3 ---------------------

//-------- COMBOBOX KATEGORI4 ---------------------

var smgridLPPSKategori4 = new Ext.grid.CheckboxSelectionModel();

var strLPPSKategori4 = new Ext.data.ArrayStore({
    fields: ['kd_kategori4', 'nama_kategori4'],
    data: []
});

// GRID PANEL TWIN COMBOBOX kategori4 Data Store
var strgridLPPSKategori4 = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: ['kd_kategori4', 'nama_kategori4', 'kd_kategori', 'nama_kategori'],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("report/get_kategori4") ?>',
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

strgridLPPSKategori4.on('load', function(){
    strgridLPPSKategori4.setBaseParam('kategori1', Ext.getCmp('id_lpps_kategori1_sel').getValue());
    strgridLPPSKategori4.setBaseParam('kategori2', Ext.getCmp('id_lpps_kategori2_sel').getValue());
    strgridLPPSKategori4.setBaseParam('kategori3', Ext.getCmp('id_lpps_kategori3_sel').getValue());
    Ext.getCmp('id_searchgridLPPSKategori4').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX kategori4
var searchgridLPPSKategori4 = new Ext.app.SearchField({
    store: strgridLPPSKategori4,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridLPPSKategori4'
});

// GRID PANEL TWIN COMBOBOX kategori4
var gridLPPSKategori4 = new Ext.grid.GridPanel({
    store: strgridLPPSKategori4,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridLPPSKategori4,
    columns: [
        smgridLPPSKategori4,
        {
            header: 'Kode kategori 4',
            dataIndex: 'kd_kategori4',
            width: 100,
            sortable: true

        },
        {
            header: 'Nama kategori 4',
            dataIndex: 'nama_kategori',
            width: 300,
            sortable: true
        }
    ],
    tbar: new Ext.Toolbar({
        items: [searchgridLPPSKategori4]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridLPPSKategori4,
        displayInfo: true
    })
});

var menuLPPSKategori4 = new Ext.menu.Menu();

menuLPPSKategori4.add(new Ext.Panel({
    title: 'Pilih Kategori 4',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridLPPSKategori4],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridLPPSKategori4.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpps_kategori4_sel','kd_kategori4',sel);
                    sm.clearSelections();
                }
                menuLPPSKategori4.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridLPPSKategori4.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpps_kategori4_sel','kd_kategori4',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lpps_kategori4_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuLPPSKategori4.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX kategori4
Ext.ux.TwinComboLPPSKategori4 = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridLPPSKategori4.removeAll();
        var kategori1 = Ext.getCmp('id_lpps_kategori1_sel').getValue();
        var kategori2 = Ext.getCmp('id_lpps_kategori2_sel').getValue();
        var kategori3 = Ext.getCmp('id_lpps_kategori3_sel').getValue();
        if(kategori1.length > 0 || kategori2.length > 0 || kategori3.length > 0 ) {
            strgridLPPSKategori4.load({params: {
                kategori1: kategori1,
                kategori2: kategori2,
                kategori3: kategori3
            }});
        } else {
            strgridLPPSKategori4.load();
        }
        menuLPPSKategori4.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuLPPSKategori4.on('hide', function () {
    var sf = Ext.getCmp('id_searchgridLPPSKategori4').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgridLPPSKategori4').setValue('');
        searchgridLPPSKategori4.onTrigger2Click();
    }
});

// TWIN COMBOBOX kategori4
var comboLPPSKategori4 = new Ext.ux.TwinComboLPPSKategori4({
    fieldLabel: 'Kategori 4',
    id: 'id_cbLPPSKategori4',
    store: strLPPSKategori4,
    mode: 'local',
    valueField: 'kd_kategori4',
    displayField: 'nama_kategori4',
    typeAhead: true,
    triggerAction: 'all',
    editable: false,
    anchor: '90%',
    hiddenName: 'kd_kategori4',
    emptyText: 'Pilih Kategori 4'
});
//-------- COMBOBOX KATEGORI4 ---------------------

//-------- COMBOBOX Produk ---------------------

var smgridLPPSProduk = new Ext.grid.CheckboxSelectionModel();

var strLPPSProduk = new Ext.data.ArrayStore({
    fields: ['kd_produk', 'nama_produk'],
    data: []
});

// GRID PANEL TWIN COMBOBOX Produk Data Store
var strgridLPPSProduk = new Ext.data.Store({
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

strgridLPPSProduk.on('load', function(){
    strgridLPPSProduk.setBaseParam('kategori1', Ext.getCmp('id_lpps_kategori1_sel').getValue());
    strgridLPPSProduk.setBaseParam('kategori2', Ext.getCmp('id_lpps_kategori2_sel').getValue());
    strgridLPPSProduk.setBaseParam('kategori3', Ext.getCmp('id_lpps_kategori3_sel').getValue());
    strgridLPPSProduk.setBaseParam('kategori4', Ext.getCmp('id_lpps_kategori4_sel').getValue());
    Ext.getCmp('id_searchgridLPPSProduk').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX Produk
var searchgridLPPSProduk = new Ext.app.SearchField({
    store: strgridLPPSProduk,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridLPPSProduk'
});

// GRID PANEL TWIN COMBOBOX Produk
var gridLPPSProduk = new Ext.grid.GridPanel({
    store: strgridLPPSProduk,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridLPPSProduk,
    columns: [
        smgridLPPSProduk,
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
        items: [searchgridLPPSProduk]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridLPPSProduk,
        displayInfo: true
    })
});

var menuLPPSProduk = new Ext.menu.Menu();

menuLPPSProduk.add(new Ext.Panel({
    title: 'Pilih Produk',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridLPPSProduk],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridLPPSProduk.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpps_produk_sel','kd_produk',sel);
                    sm.clearSelections();
                }
                menuLPPSProduk.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridLPPSProduk.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpps_produk_sel','kd_produk',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lpps_produk_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuLPPSProduk.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX Produk
Ext.ux.TwinComboLPPSProduk = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridLPPSKategori4.removeAll();
        var kategori1   = Ext.getCmp('id_lpps_kategori1_sel').getValue();
        var kategori2   = Ext.getCmp('id_lpps_kategori2_sel').getValue();
        var kategori3   = Ext.getCmp('id_lpps_kategori3_sel').getValue();
        var kategori4   = Ext.getCmp('id_lpps_kategori4_sel').getValue();
        var supplier    = Ext.getCmp('id_lpps_kd_supplier_sel').getValue();
        strgridLPPSProduk.load({params: {
            kategori1: kategori1,
            kategori2: kategori2,
            kategori3: kategori3,
            kategori4: kategori4,
            supplier: supplier
        }});
        menuLPPSProduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuLPPSProduk.on('hide', function () {
    var sf = Ext.getCmp('id_searchgridLPPSProduk').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgridLPPSProduk').setValue('');
        searchgridLPPSProduk.onTrigger2Click();
    }
});

// TWIN COMBOBOX Produk
var comboLPPSProduk = new Ext.ux.TwinComboLPPSProduk({
    fieldLabel: 'Produk',
    id: 'id_cbLPPSProduk',
    store: strLPPSProduk,
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

//-------- COMBOBOX PERUNTUKAN -------------------
var dsStatusReportLPPS=[['D',"Distribusi"],['B',"Bazar"],['S',"Supermarket"]];

var strReportLPPSStatus = new Ext.data.ArrayStore({
    fields: [{name: 'key'},{name: 'value'}],
    data:dsStatusReportLPPS
});

// COMBOBOX status
var comboLPPSStatus = new Ext.form.ComboBox({
    fieldLabel: 'Peruntukkan',
    id: 'id_cbReportLPPSStatus',
    name:'status',
    // allowBlank:false,
    store: strReportLPPSStatus,
    valueField:'key',
    displayField:'value',
    mode:'local',
    forceSelection: true,
    triggerAction: 'all',
    anchor: '90%'
});
//-------- COMBOBOX PERUNTUKAN -------------------

// -------- MAIN FORM -------------------------
var headerLPPStanggal = {
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
                    items:[
                        {
                        xtype: 'datefield',
                        fieldLabel: 'Dari Tgl',
                        name: 'dari_tgl',
                        allowBlank:false,
                        format:'d-m-Y',
                        editable:false,
                        id: 'id_lpps_tgl_awal',
                        anchor: '90%',
                        value: ''
                    },
                        comboLPPSSupplier,
                        comboLPPSKategori1,
                        comboLPPSKategori2,
                        comboLPPSKategori3,
                        comboLPPSKategori4,
                        comboLPPSProduk,
                        comboLPPSStatus
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
                            fieldLabel: 'Sampai Tgl',
                            name: 'sampai_tgl',
                            allowBlank:false,
                            editable:false,
                            format:'d-m-Y',
                            id: 'id_lpps_tgl_akhir',
                            anchor: '90%',
                            value: ''
                        },
                        {
                            xtype: 'textfield',
                            fieldLabel: 'Kd. Supplier',
                            name: 'kd_supplier_sel',
                            readOnly:true,
                            fieldClass:'readonly-input',
                            id: 'id_lpps_kd_supplier_sel',
                            anchor: '90%',
                            value:''
                        },
                        {
                            xtype: 'textfield',
                            fieldLabel: 'Kd. Kategori 1',
                            name: 'kd_kategori1_sel',
                            readOnly:true,
                            fieldClass:'readonly-input',
                            id: 'id_lpps_kategori1_sel',
                            anchor: '90%',
                            value:''
                        },
                        {
                            xtype: 'textfield',
                            fieldLabel: 'Kd. Kategori 2',
                            name: 'kd_kategori2_sel',
                            readOnly:true,
                            fieldClass:'readonly-input',
                            id: 'id_lpps_kategori2_sel',
                            anchor: '90%',
                            value:''
                        },
                        {
                            xtype: 'textfield',
                            fieldLabel: 'Kd. Kategori 3',
                            name: 'kd_kategori3_sel',
                            readOnly:true,
                            fieldClass:'readonly-input',
                            id: 'id_lpps_kategori3_sel',
                            anchor: '90%',
                            value:''
                        },
                        {
                            xtype: 'textfield',
                            fieldLabel: 'Kd. Kategori 4',
                            name: 'kd_kategori4_sel',
                            readOnly:true,
                            fieldClass:'readonly-input',
                            id: 'id_lpps_kategori4_sel',
                            anchor: '90%',
                            value:''
                        },
                        {
                            xtype: 'textfield',
                            fieldLabel: 'Kode Produk',
                            name: 'kd_produk_sel',
                            readOnly:true,
                            fieldClass:'readonly-input',
                            id: 'id_lpps_produk_sel',
                            anchor: '90%',
                            value:''
                        },
                        new Ext.form.Checkbox({
                            xtype: 'checkbox',
                            fieldLabel: 'Sort Order',
                            boxLabel: 'Descending',
                            name: 'sort_order',
                            id: 'id_lpps_sort',
                            checked: true,
                            inputValue: '1',
                            autoLoad: true
                        })
                    ]
                }]
            }]
        }]
    }]
}


var laporanpenjualan1 = new Ext.FormPanel({
    id: 'rpt_penjualan_per_supplier',
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
            items: [headerLPPStanggal],
            buttons: [
                {
                    text: 'Print',
                    formBind:true,
                    handler: function () {
                        Ext.getCmp('rpt_penjualan_per_supplier').getForm().submit({
                            url: '<?= site_url("laporan_penjualan_per_supplier/get_report") ?>',
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

                                clearform('rpt_penjualan_per_supplier');
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
                    handler: function(){clearform('rpt_penjualan1');}
                }
            ]
        }]
    }
    ]
});

function clearform(form_id){
    Ext.getCmp(form_id).getForm().reset();
}

/**
 * tambahkan nilai baru ke LoV yang sudah ada
 * @param target_id target id
 * @param key       nama key dari array nilai
 * @param values    nilai dari selectionModel
 */
function addSelectedValue(target_id,key,values) {
    var listOfValue = Ext.getCmp(target_id).getValue();
    if(listOfValue == undefined) listOfValue = '';
    var tmpList = listOfValue.split(',');
    var isFound = true;

    for (i = 0; i < values.length; i++) {
        for (j = 0; j < tmpList.length; j++) {
            if(values[i].get(key) == tmpList[j]) {
                isFound = false;
                break;
            } else {
                isFound = true;
            }
        }
        if(isFound && listOfValue.length > 0) {
            listOfValue = listOfValue + ',' + values[i].get(key);
        } else if(isFound && listOfValue.length == 0) {
            listOfValue= values[i].get(key);
        }
    }
    Ext.getCmp(target_id).setValue(listOfValue);
}

</script>