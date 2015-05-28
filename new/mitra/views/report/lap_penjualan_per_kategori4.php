<?php if (!defined( 'BASEPATH')) exit( 'No direct script access allowed'); ?>
<script type="text/javascript">
//-------- COMBOBOX SUPPLIER --------------------
var smGridPK4Supplier = new Ext.grid.CheckboxSelectionModel();

var strReportJualPK4Supplier = new Ext.data.ArrayStore({
    fields: ['kd_supplier'],
    data: []
});

// GRID PANEL TWIN COMBOBOX supplier Data Store
var strGridReportJualPK4Supplier = new Ext.data.Store({
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
var searchGridReportJualPK4Supplier = new Ext.app.SearchField({
    store: strGridReportJualPK4Supplier,
    width: 350,
    id: 'id_searchGridReportJualPK4Supplier'
});

// GRID PANEL TWIN COMBOBOX supplier
var GridReportJualPK4Supplier = new Ext.grid.GridPanel({
    store: strGridReportJualPK4Supplier,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smGridPK4Supplier,
    columns: [
        smGridPK4Supplier,
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
        items: [searchGridReportJualPK4Supplier]
    })
});

// PANEL TWIN COMBOBOX supplier
var menuReportJualPK4Supplier = new Ext.menu.Menu();

menuReportJualPK4Supplier.add(new Ext.Panel({
    title: 'Pilih Supplier',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [GridReportJualPK4Supplier],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = GridReportJualPK4Supplier.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lppk4_supplier_sel','kd_supplier',sel);
                    sm.clearSelections();
                }
                menuReportJualPK4Supplier.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = GridReportJualPK4Supplier.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lppk4_supplier_sel','kd_supplier',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lppk4_supplier_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuReportJualPK4Supplier.hide(); }
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
        strGridReportJualPK4Supplier.load();
        menuReportJualPK4Supplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuReportJualPK4Supplier.on('hide', function () {
    var sf = Ext.getCmp('id_searchGridReportJualPK4Supplier').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchGridReportJualPK4Supplier').setValue('');
        searchGridReportJualPK4Supplier.onTrigger2Click();
    }
});

// TWIN COMBOBOX supplier
var cbReportJualPK4Supplier = new Ext.ux.TwinComboSuplier({
    fieldLabel: 'Supplier',
    id: 'id_cbReportJualPK4Supplier',
    store: strReportJualPK4Supplier,
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
var smgridPK4Kategori1 = new Ext.grid.CheckboxSelectionModel();

var strPK4Kategori1 = new Ext.data.ArrayStore({
    fields: ['kd_kategori1', 'nama_kategori1'],
    data: []
});

// GRID PANEL TWIN COMBOBOX kategori2 Data Store
var strgridPK4Kategori1 = new Ext.data.Store({
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

strgridPK4Kategori1.on('load', function(){
    Ext.getCmp('id_searchgridPK4Kategori1').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX kategori2
var searchgridPK4Kategori1 = new Ext.app.SearchField({
    store: strgridPK4Kategori1,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridPK4Kategori1'
});

// GRID PANEL TWIN COMBOBOX kategori1
var gridPK4Kategori1 = new Ext.grid.GridPanel({
    store: strgridPK4Kategori1,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridPK4Kategori1,
    columns: [
        smgridPK4Kategori1,
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
        items: [searchgridPK4Kategori1]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridPK4Kategori1,
        displayInfo: true
    })
});

var menuPK4Kategori1 = new Ext.menu.Menu();

menuPK4Kategori1.add(new Ext.Panel({
    title: 'Pilih Kategori 1',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridPK4Kategori1],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridPK4Kategori1.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lppk4_kategori1_sel','kd_kategori1',sel);
                    sm.clearSelections();
                }
                menuPK4Kategori1.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridPK4Kategori1.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lppk4_kategori1_sel','kd_kategori1',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lppk4_kd_kategori1_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuPK4Kategori1.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX kategori1
Ext.ux.TwinComboPK4Kategori1 = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridPK4Kategori1.removeAll();
        strgridPK4Kategori1.load();
        menuPK4Kategori1.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuPK4Kategori1.on('hide', function () {
    var sf = Ext.getCmp('id_searchgridPK4Kategori1').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgridPK4Kategori1').setValue('');
        searchgridPK4Kategori1.onTrigger2Click();
    }
});

// TWIN COMBOBOX kategori2
var comboPK4Kategori1 = new Ext.ux.TwinComboPK4Kategori1({
    fieldLabel: 'Kategori 1',
    id: 'id_cbPK4Kategori1',
    store: strPK4Kategori1,
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

var smgridPK4Kategori2 = new Ext.grid.CheckboxSelectionModel();

var strPK4Kategori2 = new Ext.data.ArrayStore({
    fields: ['kd_kategori2', 'nama_kategori2'],
    data: []
});

// GRID PANEL TWIN COMBOBOX kategori2 Data Store
var strgridPK4Kategori2 = new Ext.data.Store({
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

strgridPK4Kategori2.on('load', function(){
    strgridPK4Kategori2.setBaseParam('kategori1', Ext.getCmp('id_lppk4_kategori1_sel').getValue());
    Ext.getCmp('id_searchgridPK4Kategori2').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX kategori2
var searchgridPK4Kategori2 = new Ext.app.SearchField({
    store: strgridPK4Kategori2,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridPK4Kategori2'
});

// GRID PANEL TWIN COMBOBOX kategori2
var gridPK4Kategori2 = new Ext.grid.GridPanel({
    store: strgridPK4Kategori2,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridPK4Kategori2,
    columns: [
        smgridPK4Kategori2,
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
        items: [searchgridPK4Kategori2]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridPK4Kategori2,
        displayInfo: true
    })
});

var menuPK4Kategori2 = new Ext.menu.Menu();

menuPK4Kategori2.add(new Ext.Panel({
    title: 'Pilih Kategori 2',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridPK4Kategori2],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridPK4Kategori2.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lppk4_kd_kategori_sel','kd_kategori2',sel);
                    sm.clearSelections();
                }
                menuPK4Kategori2.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridPK4Kategori2.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lppk4_kategori2_sel','kd_kategori2',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lppk4_kategori2_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuPK4Kategori2.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX kategori2
Ext.ux.TwinComboPK4Kategori2 = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridPK4Kategori2.removeAll();
        var kategori1 = Ext.getCmp('id_lppk4_kategori1_sel').getValue();
        if(kategori1.length>0) {
            strgridPK4Kategori2.load({params: {kategori1: kategori1}});
        } else {
            strgridPK4Kategori2.load();
        }
        menuPK4Kategori2.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuPK4Kategori2.on('hide', function () {
    var sf = Ext.getCmp('id_searchgridPK4Kategori2').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgridPK4Kategori2').setValue('');
        searchgridPK4Kategori2.onTrigger2Click();
    }
});

// TWIN COMBOBOX kategori2
var comboPK4Kategori2 = new Ext.ux.TwinComboPK4Kategori2({
    fieldLabel: 'Kategori 2',
    id: 'id_cbPK4Kategori2',
    store: strPK4Kategori2,
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

var smgridPK4Kategori3 = new Ext.grid.CheckboxSelectionModel();

var strPK4Kategori3 = new Ext.data.ArrayStore({
    fields: ['kd_kategori3', 'nama_kategori3'],
    data: []
});

// GRID PANEL TWIN COMBOBOX kategori3 Data Store
var strgridPK4Kategori3 = new Ext.data.Store({
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

strgridPK4Kategori3.on('load', function(){
    strgridPK4Kategori3.setBaseParam('kategori1', Ext.getCmp('id_lppk4_kategori1_sel').getValue());
    strgridPK4Kategori3.setBaseParam('kategori2', Ext.getCmp('id_lppk4_kategori2_sel').getValue());
    Ext.getCmp('id_searchgridPK4Kategori3').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX kategori3
var searchgridPK4Kategori3 = new Ext.app.SearchField({
    store: strgridPK4Kategori3,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridPK4Kategori3'
});

// GRID PANEL TWIN COMBOBOX kategori3
var gridPK4Kategori3 = new Ext.grid.GridPanel({
    store: strgridPK4Kategori3,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridPK4Kategori3,
    columns: [
        smgridPK4Kategori3,
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
        items: [searchgridPK4Kategori3]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridPK4Kategori3,
        displayInfo: true
    })
});

var menuPK4Kategori3 = new Ext.menu.Menu();

menuPK4Kategori3.add(new Ext.Panel({
    title: 'Pilih Kategori 3',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridPK4Kategori3],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridPK4Kategori3.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lppk4_kategori3_sel','kd_kategori3',sel);
                    sm.clearSelections();
                }
                menuPK4Kategori3.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridPK4Kategori3.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lppk4_kategori3_sel','kd_kategori3',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lppk4_kategori3_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuPK4Kategori3.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX kategori3
Ext.ux.TwinComboPK4Kategori3 = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridPK4Kategori3.removeAll();
        //load store grid
        var kategori1 = Ext.getCmp('id_lppk4_kategori1_sel').getValue();
        var kategori2 = Ext.getCmp('id_lppk4_kategori2_sel').getValue();
        if(kategori1.length > 0 || kategori2.length > 0 ) {
            strgridPK4Kategori3.load({params: {
                kategori1: kategori1,
                kategori2: kategori2
            }});
        } else {
            strgridPK4Kategori3.load();
        }
        menuPK4Kategori3.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuPK4Kategori3.on('hide', function () {
    var sf = Ext.getCmp('id_searchgridPK4Kategori3').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgridPK4Kategori3').setValue('');
        searchgridPK4Kategori3.onTrigger2Click();
    }
});

// TWIN COMBOBOX kategori3
var comboPK4Kategori3 = new Ext.ux.TwinComboPK4Kategori3({
    fieldLabel: 'Kategori 3',
    id: 'id_cbPK4Kategori3',
    store: strPK4Kategori3,
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

var smgridPK4Kategori4 = new Ext.grid.CheckboxSelectionModel();

var strPK4Kategori4 = new Ext.data.ArrayStore({
    fields: ['kd_kategori4', 'nama_kategori4'],
    data: []
});

// GRID PANEL TWIN COMBOBOX kategori4 Data Store
var strgridPK4Kategori4 = new Ext.data.Store({
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

strgridPK4Kategori4.on('load', function(){
    strgridPK4Kategori4.setBaseParam('kategori1', Ext.getCmp('id_lppk4_kategori1_sel').getValue());
    strgridPK4Kategori4.setBaseParam('kategori2', Ext.getCmp('id_lppk4_kategori2_sel').getValue());
    strgridPK4Kategori4.setBaseParam('kategori3', Ext.getCmp('id_lppk4_kategori3_sel').getValue());
    Ext.getCmp('id_searchgridPK4Kategori4').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX kategori4
var searchgridPK4Kategori4 = new Ext.app.SearchField({
    store: strgridPK4Kategori4,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridPK4Kategori4'
});

// GRID PANEL TWIN COMBOBOX kategori4
var gridPK4Kategori4 = new Ext.grid.GridPanel({
    store: strgridPK4Kategori4,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridPK4Kategori4,
    columns: [
        smgridPK4Kategori4,
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
        items: [searchgridPK4Kategori4]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridPK4Kategori4,
        displayInfo: true
    })
});

var menuPK4Kategori4 = new Ext.menu.Menu();

menuPK4Kategori4.add(new Ext.Panel({
    title: 'Pilih Kategori 4',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridPK4Kategori4],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridPK4Kategori4.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lppk4_kategori4_sel','kd_kategori4',sel);
                    sm.clearSelections();
                }
                menuPK4Kategori4.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridPK4Kategori4.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lppk4_kategori4_sel','kd_kategori4',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lppk4_kategori4_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuPK4Kategori4.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX kategori4
Ext.ux.TwinComboPK4Kategori4 = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridPK4Kategori4.removeAll();
        var kategori1 = Ext.getCmp('id_lppk4_kategori1_sel').getValue();
        var kategori2 = Ext.getCmp('id_lppk4_kategori2_sel').getValue();
        var kategori3 = Ext.getCmp('id_lppk4_kategori3_sel').getValue();
        if(kategori1.length > 0 || kategori2.length > 0 || kategori3.length > 0 ) {
            strgridPK4Kategori4.load({params: {
                kategori1: kategori1,
                kategori2: kategori2,
                kategori3: kategori3
            }});
        } else {
            strgridPK4Kategori4.load();
        }
        menuPK4Kategori4.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuPK4Kategori4.on('hide', function () {
    var sf = Ext.getCmp('id_searchgridPK4Kategori4').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgridPK4Kategori4').setValue('');
        searchgridPK4Kategori4.onTrigger2Click();
    }
});

// TWIN COMBOBOX kategori4
var comboPK4Kategori4 = new Ext.ux.TwinComboPK4Kategori4({
    fieldLabel: 'Kategori 4',
    id: 'id_cbPK4Kategori4',
    store: strPK4Kategori4,
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

//-------- CHECKBOX SORT ORDER -------------------
var sortReportJualPK4 = new Ext.form.Checkbox({
    xtype: 'checkbox',
    fieldLabel: 'Sort Order',
    boxLabel: 'Descending',
    name: 'sort_order',
    id: 'id_sortReportJualPK4',
    checked: true,
    inputValue: '1',
    autoLoad: true
});
//-------- CHECKBOX SORT ORDER -------------------

//-------- COMBOBOX PERUNTUKAN -------------------
var dsStatusReportJualPK4=[['D',"Distribusi"],['B',"Bazar"],['S',"Supermarket"]];

var strReportJualPK4Status = new Ext.data.ArrayStore({
    fields: [{name: 'key'},{name: 'value'}],
    data:dsStatusReportJualPK4
});

// COMBOBOX status
var cbReportJualPK4Status = new Ext.form.ComboBox({
    fieldLabel: 'Peruntukkan',
    id: 'id_cbReportJualPK4Status',
    name:'status',
    // allowBlank:false,
    store: strReportJualPK4Status,
    valueField:'key',
    displayField:'value',
    mode:'local',
    forceSelection: true,
    triggerAction: 'all',
    anchor: '90%'
});
//-------- COMBOBOX PERUNTUKAN -------------------

//-------- HEADER TANGGAL ------------------------
var headerReportJualPK4Tanggal = {
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
                        name: 'lppk4_dari_tgl',
                        allowBlank: false,
                        format: 'd-m-Y',
                        editable: false,
                        id: 'id_lppk4_dari_tgl',
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
                        name: 'lppk4_sampai_tgl',
                        // readOnly: true,
                        allowBlank: false,
                        editable: false,
                        format: 'd-m-Y',
                        id: 'id_lppk4_smp_tgl',
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
var headerReportJualPK4Kategori = {
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
                        comboPK4Kategori1,
                        comboPK4Kategori2,
                        comboPK4Kategori3,
                        comboPK4Kategori4,
                        cbReportJualPK4Supplier,
                        cbReportJualPK4Status,
                        sortReportJualPK4
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
                            id: 'id_lppk4_kategori1_sel',
                            anchor: '90%',
                            value:''
                        }, {
                            xtype: 'textfield',
                            fieldLabel: 'Kd. Kategori 2',
                            name: 'kd_kategori2_sel',
                            readOnly:true,
                            fieldClass:'readonly-input',
                            id: 'id_lppk4_kategori2_sel',
                            anchor: '90%',
                            value:''
                        }, {
                            xtype: 'textfield',
                            fieldLabel: 'Kd. Kategori 3',
                            name: 'kd_kategori3_sel',
                            readOnly:true,
                            fieldClass:'readonly-input',
                            id: 'id_lppk4_kategori3_sel',
                            anchor: '90%',
                            value:''
                        }, {
                            xtype: 'textfield',
                            fieldLabel: 'Kd. Kategori 4',
                            name: 'kd_kategori4_sel',
                            readOnly:true,
                            fieldClass:'readonly-input',
                            id: 'id_lppk4_kategori4_sel',
                            anchor: '90%',
                            value:''
                        }, {
                            xtype: 'hidden',
                            name: 'kd_supplier_sel',
                            readOnly:true,
                            fieldClass:'readonly-input',
                            id: 'id_lppk4_supplier_sel',
                            anchor: '90%',
                            value:''
//                        {
//                            xtype: 'hidden',
//                            name: 'kd_kategori1_sel',
//                            id: 'id_lppk4_kategori1_sel'
//                        }, {
//                            xtype: 'hidden',
//                            name: 'kd_kategori2_sel',
//                            id: 'id_lppk4_kategori2_sel'
//                        }, {
//                            xtype: 'hidden',
//                            name: 'kd_kategori3_sel',
//                            id: 'id_lppk4_kategori3_sel'
//                        }, {
//                            xtype: 'hidden',
//                            name: 'kd_kategori4_sel',
//                            id: 'id_lppk4_kategori4_sel'
//                        }, {
//                            xtype: 'hidden',
//                            name: 'kd_supplier_sel',
//                            id: 'id_lppk4_supplier_sel'
                        }, {
                            xtype: 'radiogroup',
                            fieldLabel: 'Tampilan data',
                            columnWidth: [.5, .5],
                            name: 'data_type',
                            id: 'id_lppk4_data_type',
                            width: 250,
                            anchor: '90%',
                            allowBlank:false,
                            items: [{
                                boxLabel: 'Value',
                                name: 'data_type',
                                id: 'id_lppk4_data_typeV',
                                inputValue: '0',
                                checked: true
                            }, {
                                boxLabel: 'Quantity',
                                name: 'data_type',
                                inputValue: '1',
                                id: 'id_lppk4_data_typeQ'
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
var headerReportJualPK4Utama = {
    buttonAlign: 'left',
    layout: 'form',
    border: false,
    labelWidth: 100,
    defaults: { labelSeparator: ''},
    items: [headerReportJualPK4Tanggal, headerReportJualPK4Kategori],
    buttons: [{
        text: 'Print',
        formBind: true,
        handler: function () {
            Ext.getCmp('rpt_penjualan_perkategori4').getForm().submit({
                url: '<?= site_url("laporan_penjualan_per_kategori4/get_report") ?>',
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
                    clearform('rpt_penjualan_perkategori4');
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
        handler: function () { clearform('rpt_penjualan_perkategori4');}
    }]
};
//-------- HEADER FORM ---------------------------

//-------- MAIN PANEL ----------------------------
var ReportJualPK4 = new Ext.FormPanel({
    id: 'rpt_penjualan_perkategori4',
    border: false,
    frame: true,
    monitorValid: true,
    labelWidth: 130,
    items: [{
        bodyStyle: { margin: '0px 0px 15px 0px'},
        items: [headerReportJualPK4Utama]
    }]
});

</script>