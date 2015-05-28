<?php if (!defined( 'BASEPATH')) exit( 'No direct script access allowed'); ?>
<script type="text/javascript">
//-------- COMBOBOX SUPPLIER --------------------
var smGridPK3Supplier = new Ext.grid.CheckboxSelectionModel();

var strReportJualPK3Supplier = new Ext.data.ArrayStore({
    fields: ['kd_supplier'],
    data: []
});

// GRID PANEL TWIN COMBOBOX supplier Data Store
var strGridReportJualPK3Supplier = new Ext.data.Store({
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
var searchGridReportJualPK3Supplier = new Ext.app.SearchField({
    store: strGridReportJualPK3Supplier,
    width: 350,
    id: 'id_searchGridReportJualPK3Supplier'
});

// GRID PANEL TWIN COMBOBOX supplier
var GridReportJualPK3Supplier = new Ext.grid.GridPanel({
    store: strGridReportJualPK3Supplier,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smGridPK3Supplier,
    columns: [
        smGridPK3Supplier,
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
        items: [searchGridReportJualPK3Supplier]
    })
});

// PANEL TWIN COMBOBOX supplier
var menuReportJualPK3Supplier = new Ext.menu.Menu();

menuReportJualPK3Supplier.add(new Ext.Panel({
    title: 'Pilih Supplier',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [GridReportJualPK3Supplier],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = GridReportJualPK3Supplier.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lppk3_supplier_sel','kd_supplier',sel);
                    sm.clearSelections();
                }
                menuReportJualPK3Supplier.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = GridReportJualPK3Supplier.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lppk3_supplier_sel','kd_supplier',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lppk3_supplier_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuReportJualPK3Supplier.hide(); }
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
        strGridReportJualPK3Supplier.load();
        menuReportJualPK3Supplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuReportJualPK3Supplier.on('hide', function () {
    var sf = Ext.getCmp('id_searchGridReportJualPK3Supplier').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchGridReportJualPK3Supplier').setValue('');
        searchGridReportJualPK3Supplier.onTrigger2Click();
    }
});

// TWIN COMBOBOX supplier
var cbReportJualPK3Supplier = new Ext.ux.TwinComboSuplier({
    fieldLabel: 'Supplier',
    id: 'id_cbReportJualPK3Supplier',
    store: strReportJualPK3Supplier,
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
var smgridPK3Kategori1 = new Ext.grid.CheckboxSelectionModel();

var strPK3Kategori1 = new Ext.data.ArrayStore({
    fields: ['kd_kategori1', 'nama_kategori1'],
    data: []
});

// GRID PANEL TWIN COMBOBOX kategori2 Data Store
var strgridPK3Kategori1 = new Ext.data.Store({
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

strgridPK3Kategori1.on('load', function(){
    Ext.getCmp('id_searchgridPK3Kategori1').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX kategori2
var searchgridPK3Kategori1 = new Ext.app.SearchField({
    store: strgridPK3Kategori1,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridPK3Kategori1'
});

// GRID PANEL TWIN COMBOBOX kategori1
var gridPK3Kategori1 = new Ext.grid.GridPanel({
    store: strgridPK3Kategori1,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridPK3Kategori1,
    columns: [
        smgridPK3Kategori1,
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
        items: [searchgridPK3Kategori1]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridPK3Kategori1,
        displayInfo: true
    })
});

var menuPK3Kategori1 = new Ext.menu.Menu();

menuPK3Kategori1.add(new Ext.Panel({
    title: 'Pilih Kategori 1',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridPK3Kategori1],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridPK3Kategori1.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lppk3_kategori1_sel','kd_kategori1',sel);
                    sm.clearSelections();
                }
                menuPK3Kategori1.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridPK3Kategori1.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lppk3_kategori1_sel','kd_kategori1',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lppk3_kd_kategori1_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuPK3Kategori1.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX kategori1
Ext.ux.TwinComboPK3Kategori1 = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridPK3Kategori1.removeAll();
        strgridPK3Kategori1.load();
        menuPK3Kategori1.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuPK3Kategori1.on('hide', function () {
    var sf = Ext.getCmp('id_searchgridPK3Kategori1').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgridPK3Kategori1').setValue('');
        searchgridPK3Kategori1.onTrigger2Click();
    }
});

// TWIN COMBOBOX kategori2
var comboPK3Kategori1 = new Ext.ux.TwinComboPK3Kategori1({
    fieldLabel: 'Kategori 1',
    id: 'id_cbPK3Kategori1',
    store: strPK3Kategori1,
    mode: 'local',
    valueField: 'kd_kategori1',
    displayField: 'nama_kategori1',
    typeAhead: true,
    triggerAction: 'all',
    editable: false,
    anchor: '90%',
    hiddenName: 'kd_kategori1',
    emptyText: 'Pilih Kategori 1'
});

//-------- COMBOBOX KATEGORI1 ---------------------


//-------- COMBOBOX KATEGORI2 ---------------------

var smgridPK3Kategori2 = new Ext.grid.CheckboxSelectionModel();

var strPK3Kategori2 = new Ext.data.ArrayStore({
    fields: ['kd_kategori2', 'nama_kategori2'],
    data: []
});

// GRID PANEL TWIN COMBOBOX kategori2 Data Store
var strgridPK3Kategori2 = new Ext.data.Store({
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

strgridPK3Kategori2.on('load', function(){
    strgridPK3Kategori2.setBaseParam('kategori1', Ext.getCmp('id_lppk3_kd_kategori2_sel').getValue());
    Ext.getCmp('id_searchgridPK3Kategori2').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX kategori2
var searchgridPK3Kategori2 = new Ext.app.SearchField({
    store: strgridPK3Kategori2,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridPK3Kategori2'
});

// GRID PANEL TWIN COMBOBOX kategori2
var gridPK3Kategori2 = new Ext.grid.GridPanel({
    store: strgridPK3Kategori2,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridPK3Kategori2,
    columns: [
        smgridPK3Kategori2,
        {
            header: 'Kode kategori 2',
            dataIndex: 'kd_kategori',
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
        items: [searchgridPK3Kategori2]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridPK3Kategori2,
        displayInfo: true
    })
});

var menuPK3Kategori2 = new Ext.menu.Menu();

menuPK3Kategori2.add(new Ext.Panel({
    title: 'Pilih Kategori 2',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridPK3Kategori2],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridPK3Kategori2.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lppk3_kd_kategori2_sel','kd_kategori2',sel);
                    sm.clearSelections();
                }
                menuPK3Kategori2.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridPK3Kategori2.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lppk3_kategori2_sel','kd_kategori2',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lppk3_kategori2_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuPK3Kategori2.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX kategori2
Ext.ux.TwinComboPK3Kategori2 = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridPK3Kategori2.removeAll();
        var kategori1 = Ext.getCmp('id_lppk3_kategori1_sel').getValue();
        if(kategori1.length>0) {
            strgridPK3Kategori2.load({params: {kategori1: kategori1}});
        } else {
            strgridPK3Kategori2.load();
        }
        menuPK3Kategori2.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuPK3Kategori2.on('hide', function () {
    var sf = Ext.getCmp('id_searchgridPK3Kategori2').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgridPK3Kategori2').setValue('');
        searchgridPK3Kategori2.onTrigger2Click();
    }
});

// TWIN COMBOBOX kategori2
var comboPK3Kategori2 = new Ext.ux.TwinComboPK3Kategori2({
    fieldLabel: 'Kategori 2',
    id: 'id_cbPK3Kategori2',
    store: strPK3Kategori2,
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

var smgridPK3Kategori3 = new Ext.grid.CheckboxSelectionModel();

var strPK3Kategori3 = new Ext.data.ArrayStore({
    fields: ['kd_kategori3', 'nama_kategori3'],
    data: []
});

// GRID PANEL TWIN COMBOBOX kategori3 Data Store
var strgridPK3Kategori3 = new Ext.data.Store({
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

strgridPK3Kategori3.on('load', function(){
    strgridPK3Kategori3.setBaseParam('kategori1', Ext.getCmp('id_lppk3_kategori1_sel').getValue());
    strgridPK3Kategori3.setBaseParam('kategori2', Ext.getCmp('id_lppk3_kategori2_sel').getValue());
    Ext.getCmp('id_searchgridPK3Kategori3').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX kategori3
var searchgridPK3Kategori3 = new Ext.app.SearchField({
    store: strgridPK3Kategori3,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridPK3Kategori3'
});

// GRID PANEL TWIN COMBOBOX kategori3
var gridPK3Kategori3 = new Ext.grid.GridPanel({
    store: strgridPK3Kategori3,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridPK3Kategori3,
    columns: [
        smgridPK3Kategori3,
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
        items: [searchgridPK3Kategori3]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridPK3Kategori3,
        displayInfo: true
    })
});

var menuPK3Kategori3 = new Ext.menu.Menu();

menuPK3Kategori3.add(new Ext.Panel({
    title: 'Pilih Kategori 3',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridPK3Kategori3],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridPK3Kategori3.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lppk3_kategori3_sel','kd_kategori3',sel);
                    sm.clearSelections();
                }
                menuPK3Kategori3.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridPK3Kategori3.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lppk3_kategori3_sel','kd_kategori3',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lppk3_kategori3_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuPK3Kategori3.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX kategori3
Ext.ux.TwinComboPK3Kategori3 = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridPK3Kategori3.removeAll();
        //load store grid
        var kategori1 = Ext.getCmp('id_lppk3_kategori1_sel').getValue();
        var kategori2 = Ext.getCmp('id_lppk3_kategori2_sel').getValue();
        if(kategori1.length > 0 || kategori2.length > 0 ) {
            strgridPK3Kategori3.load({params: {
                kategori1: kategori1,
                kategori2: kategori2
            }});
        } else {
            strgridPK3Kategori3.load();
        }
        menuPK3Kategori3.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuPK3Kategori3.on('hide', function () {
    var sf = Ext.getCmp('id_searchgridPK3Kategori3').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgridPK3Kategori3').setValue('');
        searchgridPK3Kategori3.onTrigger2Click();
    }
});

// TWIN COMBOBOX kategori3
var comboPK3Kategori3 = new Ext.ux.TwinComboPK3Kategori3({
    fieldLabel: 'Kategori 3',
    id: 'id_cbPK3Kategori3',
    store: strPK3Kategori3,
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

//-------- CHECKBOX SORT ORDER -------------------
var sortReportJualPK3 = new Ext.form.Checkbox({
    xtype: 'checkbox',
    fieldLabel: 'Sort Order',
    boxLabel: 'Descending',
    name: 'sort_order',
    id: 'id_sortReportJualPK3',
    checked: true,
    inputValue: '1',
    autoLoad: true
});
//-------- CHECKBOX SORT ORDER -------------------

//-------- COMBOBOX PERUNTUKAN -------------------
var dsStatusReportJualPK3=[['D',"Distribusi"],['B',"Bazar"],['S',"Supermarket"]];

var strReportJualPK3Status = new Ext.data.ArrayStore({
    fields: [{name: 'key'},{name: 'value'}],
    data:dsStatusReportJualPK3
});

// COMBOBOX status
var cbReportJualPK3Status = new Ext.form.ComboBox({
    fieldLabel: 'Peruntukkan',
    id: 'id_cbReportJualPK3Status',
    name:'status',
    // allowBlank:false,
    store: strReportJualPK3Status,
    valueField:'key',
    displayField:'value',
    mode:'local',
    forceSelection: true,
    triggerAction: 'all',
    anchor: '90%'
});
//-------- COMBOBOX PERUNTUKAN -------------------

//-------- HEADER TANGGAL ------------------------
var headerReportJualPK3Tanggal = {
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
                items: [{
                    columnWidth: .5,
                    layout: 'form',
                    border: false,
                    labelWidth: 100,
                    defaults: {
                        labelSeparator: ''
                    },
                    items: [{
                        xtype: 'datefield',
                        fieldLabel: 'Dari Tgl ',
                        name: 'lppk3_dari_tgl',
                        allowBlank: false,
                        format: 'd-m-Y',
                        editable: false,
                        id: 'id_lppk3_dari_tgl',
                        anchor: '90%',
                        value: ''
                    }]
                }, {
                    columnWidth: .5,
                    layout: 'form',
                    border: false,
                    labelWidth: 100,
                    defaults: {
                        labelSeparator: ''
                    },
                    items: [{
                        xtype: 'datefield',
                        fieldLabel: 'Sampai Tgl',
                        name: 'lppk3_sampai_tgl',
                        // readOnly: true,
                        allowBlank: false,
                        editable: false,
                        format: 'd-m-Y',
                        id: 'id_lppk3_smp_tgl',
                        anchor: '90%',
                        // fieldClass:'readonly-input',
                        value: ''
                    }]
                }]
            }]
        }]
    }]
}
//-------- HEADER TANGGAL ------------------------

//-------- HEADER KATEGORI -----------------------
var headerReportJualPK3Kategori = {
    layout: 'column',
    border: false,
    items: [{
        columnWidth: .8,
        layout: 'form',
        border: false,
        labelWidth: 100,
        defaults: {
            labelSeparator: ''
        },
        items: [{
            xtype: 'fieldset',
            autoHeight: true,
            items: [{
                layout: 'column',
                items: [{
                    columnWidth: .5,
                    layout: 'form',
                    border: false,
                    labelWidth: 100,
                    defaults: {
                        labelSeparator: ''
                    },
                    items: [
                        comboPK3Kategori1,
                        comboPK3Kategori2,
                        comboPK3Kategori3,
                        cbReportJualPK3Supplier,
                        cbReportJualPK3Status,
                        sortReportJualPK3
                    ]
                }, {
                    columnWidth: .5,
                    layout: 'form',
                    border: false,
                    labelWidth: 100,
                    defaults: {
                        labelSeparator: ''
                    },
                    items: [
                        {
                            xtype: 'textfield',
                            fieldLabel: 'Kd. Kategori 1',
                            name: 'kd_kategori1_sel',
                            readOnly:true,
                            fieldClass:'readonly-input',
                            id: 'id_lppk3_kategori1_sel',
                            anchor: '90%',
                            value:''
                        }, {
                            xtype: 'textfield',
                            fieldLabel: 'Kd. Kategori 2',
                            name: 'kd_kategori2_sel',
                            readOnly:true,
                            fieldClass:'readonly-input',
                            id: 'id_lppk3_kategori2_sel',
                            anchor: '90%',
                            value:''
                        }, {
                            xtype: 'textfield',
                            fieldLabel: 'Kd. Kategori 3',
                            name: 'kd_kategori3_sel',
                            readOnly:true,
                            fieldClass:'readonly-input',
                            id: 'id_lppk3_kategori3_sel',
                            anchor: '90%',
                            value:''
                        }, {
                            xtype: 'textfield',
                            fieldLabel: 'Kd. Supplier',
                            name: 'kd_supplier_sel',
                            readOnly:true,
                            fieldClass:'readonly-input',
                            id: 'id_lppk3_supplier_sel',
                            anchor: '90%',
                            value:''
                        }, {
                            xtype: 'radiogroup',
                            fieldLabel: 'Tampilan data',
                            columnWidth: [.5, .5],
                            name: 'data_type',
                            id: 'id_lppk3_data_type',
                            width: 250,
                            anchor: '90%',
                            allowBlank:false,
                            items: [{
                                boxLabel: 'Value',
                                name: 'data_type',
                                id: 'id_lppk3_data_typeV',
                                inputValue: '0',
                                checked: true
                            }, {
                                boxLabel: 'Quantity',
                                name: 'data_type',
                                inputValue: '1',
                                id: 'id_lppk3_data_typeQ'
                            }]
                        }
                    ]
                }]
            }]
        }]
    }]
}
//-------- HEADER KATEGORI -----------------------

//-------- HEADER FORM ---------------------------
var headerReportJualPK3Utama = {
    buttonAlign: 'left',
    layout: 'form',
    border: false,
    labelWidth: 100,
    defaults: { labelSeparator: ''},
    items: [headerReportJualPK3Tanggal, headerReportJualPK3Kategori],
    buttons: [{
        text: 'Print',
        formBind: true,
        handler: function () {
            Ext.getCmp('rpt_penjualan_perkategori3').getForm().submit({
                url: '<?= site_url("laporan_penjualan_per_kategori3/get_report") ?>',
                scope: this,
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
                            //redirect ke report
                        }
                    });
                    clearform('rpt_penjualan_perkategori3');
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
            })
        }
    }, {
        text: 'Cancel',
        handler: function () { clearform('rpt_penjualan_perkategori3');}
    }]
};
//-------- HEADER FORM ---------------------------

//-------- MAIN PANEL ----------------------------
var ReportJualPK3 = new Ext.FormPanel({
    id: 'rpt_penjualan_perkategori3',
    border: false,
    frame: true,
    monitorValid: true,
    labelWidth: 130,
    items: [{
        bodyStyle: { margin: '0px 0px 15px 0px'},
        items: [headerReportJualPK3Utama]
    }]
});

</script>