<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
// -------- COMBOBOX Supplier --------------------
var smgridLKSSupplier= new Ext.grid.CheckboxSelectionModel();

var strCbLKSSupplier = new Ext.data.ArrayStore({
    fields: ['kd_supplier'],
    data : []
});

var strgridLKSSupplier = new Ext.data.Store({
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

var searchgridLKSSupplier = new Ext.app.SearchField({
    store: strgridLKSSupplier,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridLKSSupplier'
});

var gridLKSSupplier = new Ext.grid.GridPanel({
    store: strgridLKSSupplier,
    stripeRows: true,
    frame: true,
    border:true,
    sm: smgridLKSSupplier,
    columns: [
        smgridLKSSupplier,
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
        items: [searchgridLKSSupplier]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridLKSSupplier,
        displayInfo: true
    })
});

var menuLKSSupplier = new Ext.menu.Menu();

menuLKSSupplier.add(new Ext.Panel({
    title: 'Pilih Supplier',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridLKSSupplier],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridLKSSupplier.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lks_kd_supplier_sel','kd_supplier',sel);
                    sm.clearSelections();
                }
                menuLKSSupplier.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridLKSSupplier.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lks_kd_supplier_sel','kd_supplier',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lks_kd_supplier_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuLKSSupplier.hide(); }
        }]
}));

Ext.ux.TwinComboLKSSupplier = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function(){
        //load store grid
        strgridLKSSupplier.load();
        menuLKSSupplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuLKSSupplier.on('hide', function(){
    var sf = Ext.getCmp('id_searchgridLKSSupplier').getValue();
    if( sf != ''){
        Ext.getCmp('id_searchgridLKSSupplier').setValue('');
        searchgridLKSSupplier.onTrigger2Click();
    }
});

var comboLKSSupplier = new Ext.ux.TwinComboLKSSupplier({
    fieldLabel: 'Supplier',
    id: 'id_cbLKSSupplier',
    store: strCbLKSSupplier,
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

//-------- COMBOBOX KATEGORI1 ---------------------

var smgridLKSKategori1 = new Ext.grid.CheckboxSelectionModel();

var strLKSKategori1 = new Ext.data.ArrayStore({
    fields: ['kd_kategori1', 'nama_kategori1'],
    data: []
});

// GRID PANEL TWIN COMBOBOX kategori1 Data Store
var strgridLKSKategori1 = new Ext.data.Store({
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

strgridLKSKategori1.on('load', function(){
    Ext.getCmp('id_searchgridLKSKategori1').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX kategori1
var searchgridLKSKategori1 = new Ext.app.SearchField({
    store: strgridLKSKategori1,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridLKSKategori1'
});

// GRID PANEL TWIN COMBOBOX kategori1
var gridLKSKategori1 = new Ext.grid.GridPanel({
    store: strgridLKSKategori1,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridLKSKategori1,
    columns: [
        smgridLKSKategori1,
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
        items: [searchgridLKSKategori1]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridLKSKategori1,
        displayInfo: true
    })
});

var menuLKSKategori1 = new Ext.menu.Menu();

menuLKSKategori1.add(new Ext.Panel({
    title: 'Pilih Kategori 1',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridLKSKategori1],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridLKSKategori1.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lks_kategori1_sel','kd_kategori1',sel);
                    sm.clearSelections();
                }
                menuLKSKategori1.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridLKSKategori1.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lks_kategori1_sel','kd_kategori1',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lks_kategori1_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuLKSKategori1.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX kategori1
Ext.ux.TwinComboLKSKategori1 = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        //load store grid
        strgridLKSKategori1.removeAll();
        strgridLKSKategori1.load();
        menuLKSKategori1.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuLKSKategori1.on('hide', function () {
    var sf = Ext.getCmp('id_searchgridLKSKategori1').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgridLKSKategori1').setValue('');
        searchgridLKSKategori1.onTrigger2Click();
    }
});

// TWIN COMBOBOX kategori1
var comboLKSKategori1 = new Ext.ux.TwinComboLKSKategori1({
    fieldLabel: 'Kategori 1',
    id: 'id_cbLKSKategori1',
    store: strLKSKategori1,
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

var smgridLKSKategori2 = new Ext.grid.CheckboxSelectionModel();

var strLKSKategori2 = new Ext.data.ArrayStore({
    fields: ['kd_kategori2', 'nama_kategori2'],
    data: []
});

// GRID PANEL TWIN COMBOBOX kategori2 Data Store
var strgridLKSKategori2 = new Ext.data.Store({
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

strgridLKSKategori2.on('load', function(){
    strgridLKSKategori2.setBaseParam('kategori1',Ext.getCmp('id_lks_kategori1_sel').getValue());
    Ext.getCmp('id_searchgridLKSKategori2').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX kategori2
var searchgridLKSKategori2 = new Ext.app.SearchField({
    store: strgridLKSKategori2,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridLKSKategori2'
});

// GRID PANEL TWIN COMBOBOX kategori2
var gridLKSKategori2 = new Ext.grid.GridPanel({
    store: strgridLKSKategori2,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridLKSKategori2,
    columns: [
        smgridLKSKategori2,
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
        items: [searchgridLKSKategori2]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridLKSKategori2,
        displayInfo: true
    })
});

var menuLKSKategori2 = new Ext.menu.Menu();

menuLKSKategori2.add(new Ext.Panel({
    title: 'Pilih Kategori 2',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridLKSKategori2],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridLKSKategori2.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lks_kategori2_sel','kd_kategori2',sel);
                    sm.clearSelections();
                }
                menuLKSKategori2.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridLKSKategori2.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lks_kategori2_sel','kd_kategori2',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lks_kategori2_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuLKSKategori2.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX kategori2
Ext.ux.TwinComboLKSKategori2 = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridLKSKategori2.removeAll();
        var kategori1 = Ext.getCmp('id_lks_kategori1_sel').getValue();
        strgridLKSKategori2.load({params: {kategori1: kategori1}});

        menuLKSKategori2.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuLKSKategori2.on('hide', function () {
    var sf = Ext.getCmp('id_searchgridLKSKategori2').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgridLKSKategori2').setValue('');
        searchgridLKSKategori2.onTrigger2Click();
    }
});

// TWIN COMBOBOX kategori2
var comboLKSKategori2 = new Ext.ux.TwinComboLKSKategori2({
    fieldLabel: 'Kategori 2',
    id: 'id_cbLKSKategori2',
    store: strLKSKategori2,
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

var smgridLKSKategori3 = new Ext.grid.CheckboxSelectionModel();

var strLKSKategori3 = new Ext.data.ArrayStore({
    fields: ['kd_kategori3', 'nama_kategori3'],
    data: []
});

// GRID PANEL TWIN COMBOBOX kategori3 Data Store
var strgridLKSKategori3 = new Ext.data.Store({
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

strgridLKSKategori3.on('load', function(){
    strgridLKSKategori3.setBaseParam('kategori1',Ext.getCmp('id_lks_kategori1_sel').getValue());
    strgridLKSKategori3.setBaseParam('kategori2',Ext.getCmp('id_lks_kategori2_sel').getValue());
    Ext.getCmp('id_searchgridLKSKategori3').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX kategori3
var searchgridLKSKategori3 = new Ext.app.SearchField({
    store: strgridLKSKategori3,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridLKSKategori3'
});

// GRID PANEL TWIN COMBOBOX kategori3
var gridLKSKategori3 = new Ext.grid.GridPanel({
    store: strgridLKSKategori3,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridLKSKategori3,
    columns: [
        smgridLKSKategori3,
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
        items: [searchgridLKSKategori3]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridLKSKategori3,
        displayInfo: true
    })
});

var menuLKSKategori3 = new Ext.menu.Menu();

menuLKSKategori3.add(new Ext.Panel({
    title: 'Pilih Kategori 3',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridLKSKategori3],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridLKSKategori3.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lks_kategori3_sel','kd_kategori3',sel);
                    sm.clearSelections();
                }
                menuLKSKategori3.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridLKSKategori3.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lks_kategori3_sel','kd_kategori3',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lks_kategori3_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuLKSKategori3.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX kategori3
Ext.ux.TwinComboLKSKategori3 = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridLKSKategori3.removeAll();
        //load store grid
        var kategori1 = Ext.getCmp('id_lks_kategori1_sel').getValue();
        var kategori2 = Ext.getCmp('id_lks_kategori2_sel').getValue();
        if(kategori1.length > 0 || kategori2.length > 0 ) {
            strgridLKSKategori3.load({params: {
                kategori1: kategori1,
                kategori2: kategori2
            }});
        } else {
            strgridLKSKategori3.load();
        }
        menuLKSKategori3.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuLKSKategori3.on('hide', function () {
    var sf = Ext.getCmp('id_searchgridLKSKategori3').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgridLKSKategori3').setValue('');
        searchgridLKSKategori3.onTrigger2Click();
    }
});

// TWIN COMBOBOX kategori3
var comboLKSKategori3 = new Ext.ux.TwinComboLKSKategori3({
    fieldLabel: 'Kategori 3',
    id: 'id_cbLKSKategori3',
    store: strLKSKategori3,
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

var smgridLKSKategori4 = new Ext.grid.CheckboxSelectionModel();

var strLKSKategori4 = new Ext.data.ArrayStore({
    fields: ['kd_kategori4', 'nama_kategori4'],
    data: []
});

// GRID PANEL TWIN COMBOBOX kategori4 Data Store
var strgridLKSKategori4 = new Ext.data.Store({
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

strgridLKSKategori4.on('load', function(){
    strgridLKSKategori4.setBaseParam('kategori1',Ext.getCmp('id_lks_kategori1_sel').getValue());
    strgridLKSKategori4.setBaseParam('kategori2',Ext.getCmp('id_lks_kategori2_sel').getValue());
    strgridLKSKategori4.setBaseParam('kategori3',Ext.getCmp('id_lks_kategori3_sel').getValue());
    Ext.getCmp('id_searchgridLKSKategori4').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX kategori4
var searchgridLKSKategori4 = new Ext.app.SearchField({
    store: strgridLKSKategori4,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridLKSKategori4'
});

// GRID PANEL TWIN COMBOBOX kategori4
var gridLKSKategori4 = new Ext.grid.GridPanel({
    store: strgridLKSKategori4,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridLKSKategori4,
    columns: [
        smgridLKSKategori4,
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
        items: [searchgridLKSKategori4]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridLKSKategori4,
        displayInfo: true
    })
});

var menuLKSKategori4 = new Ext.menu.Menu();

menuLKSKategori4.add(new Ext.Panel({
    title: 'Pilih Kategori 4',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridLKSKategori4],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridLKSKategori4.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lks_kategori4_sel','kd_kategori4',sel);
                    sm.clearSelections();
                }
                menuLKSKategori4.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridLKSKategori4.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lks_kategori4_sel','kd_kategori4',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lks_kategori4_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuLKSKategori4.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX kategori4
Ext.ux.TwinComboLKSKategori4 = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridLKSKategori4.removeAll();
        var kategori1 = Ext.getCmp('id_lks_kategori1_sel').getValue();
        var kategori2 = Ext.getCmp('id_lks_kategori2_sel').getValue();
        var kategori3 = Ext.getCmp('id_lks_kategori3_sel').getValue();
        if(kategori1.length > 0 || kategori2.length > 0 || kategori3.length > 0 ) {
            strgridLKSKategori4.load({params: {
                kategori1: kategori1,
                kategori2: kategori2,
                kategori3: kategori3
            }});
        } else {
            strgridLKSKategori4.load();
        }
        menuLKSKategori4.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuLKSKategori4.on('hide', function () {
    var sf = Ext.getCmp('id_searchgridLKSKategori4').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgridLKSKategori4').setValue('');
        searchgridLKSKategori4.onTrigger2Click();
    }
});

// TWIN COMBOBOX kategori4
var comboLKSKategori4 = new Ext.ux.TwinComboLKSKategori4({
    fieldLabel: 'Kategori 4',
    id: 'id_cbLKSKategori4',
    store: strLKSKategori4,
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


//-------- COMBOBOX Ukuran ---------------------

var smgridLKSUkuran = new Ext.grid.CheckboxSelectionModel();

var strLKSUkuran = new Ext.data.ArrayStore({
    fields: ['kd_ukuran', 'nama_ukuran'],
    data: []
});

// GRID PANEL TWIN COMBOBOX Ukuran Data Store
var strgridLKSUkuran = new Ext.data.Store({
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

strgridLKSUkuran.on('load', function(){
    Ext.getCmp('id_searchgridLKSUkuran').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX Ukuran
var searchgridLKSUkuran = new Ext.app.SearchField({
    store: strgridLKSUkuran,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridLKSUkuran'
});

// GRID PANEL TWIN COMBOBOX Ukuran
var gridLKSUkuran = new Ext.grid.GridPanel({
    store: strgridLKSUkuran,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridLKSUkuran,
    columns: [
        smgridLKSUkuran,
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
        items: [searchgridLKSUkuran]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridLKSUkuran,
        displayInfo: true
    })
});

var menuLKSUkuran = new Ext.menu.Menu();

menuLKSUkuran.add(new Ext.Panel({
    title: 'Pilih Ukuran',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridLKSUkuran],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridLKSUkuran.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lks_ukuran_sel','kd_ukuran',sel);
                    sm.clearSelections();
                }
                menuLKSUkuran.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridLKSUkuran.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lks_ukuran_sel','kd_ukuran',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lks_ukuran_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuLKSUkuran.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX Ukuran
Ext.ux.TwinComboLKSUkuran = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        //load store grid
        strgridLKSUkuran.removeAll();
        strgridLKSUkuran.load();
        menuLKSUkuran.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuLKSUkuran.on('hide', function () {
    var sf = Ext.getCmp('id_searchgridLKSUkuran').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgridLKSUkuran').setValue('');
        searchgridLKSUkuran.onTrigger2Click();
    }
});

// TWIN COMBOBOX Ukuran
var comboLKSUkuran = new Ext.ux.TwinComboLKSUkuran({
    fieldLabel: 'Ukuran',
    id: 'id_cbLKSUkuran',
    store: strLKSUkuran,
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

var smgridLKSSatuan = new Ext.grid.CheckboxSelectionModel();

var strLKSSatuan = new Ext.data.ArrayStore({
    fields: ['kd_satuan', 'nm_satuan'],
    data: []
});

// GRID PANEL TWIN COMBOBOX Satuan Data Store
var strgridLKSSatuan = new Ext.data.Store({
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

strgridLKSSatuan.on('load', function(){
    Ext.getCmp('id_searchgridLKSSatuan').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX Satuan
var searchgridLKSSatuan = new Ext.app.SearchField({
    store: strgridLKSSatuan,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridLKSSatuan'
});

// GRID PANEL TWIN COMBOBOX Satuan
var gridLKSSatuan = new Ext.grid.GridPanel({
    store: strgridLKSSatuan,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridLKSSatuan,
    columns: [
        smgridLKSSatuan,
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
        items: [searchgridLKSSatuan]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridLKSSatuan,
        displayInfo: true
    })
});

var menuLKSSatuan = new Ext.menu.Menu();

menuLKSSatuan.add(new Ext.Panel({
    title: 'Pilih Satuan',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridLKSSatuan],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridLKSSatuan.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lks_satuan_sel','kd_satuan',sel);
                    sm.clearSelections();
                }
                menuLKSSatuan.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridLKSSatuan.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lks_satuan_sel','kd_satuan',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lks_satuan_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuLKSSatuan.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX Satuan
Ext.ux.TwinComboLKSSatuan = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        //load store grid
        strgridLKSSatuan.removeAll();
        strgridLKSSatuan.load();
        menuLKSSatuan.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuLKSSatuan.on('hide', function () {
    var sf = Ext.getCmp('id_searchgridLKSSatuan').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgridLKSSatuan').setValue('');
        searchgridLKSSatuan.onTrigger2Click();
    }
});

// TWIN COMBOBOX Satuan
var comboLKSSatuan = new Ext.ux.TwinComboLKSSatuan({
    fieldLabel: 'Satuan',
    id: 'id_cbLKSSatuan',
    store: strLKSSatuan,
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

var smgridLKSProduk = new Ext.grid.CheckboxSelectionModel();

var strLKSProduk = new Ext.data.ArrayStore({
    fields: ['kd_produk', 'nama_produk'],
    data: []
});

// GRID PANEL TWIN COMBOBOX Produk Data Store
var strgridLKSProduk = new Ext.data.Store({
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

strgridLKSProduk.on('load', function(){
    strgridLKSProduk.setBaseParam('kategori1', Ext.getCmp('id_lks_kategori1_sel').getValue());
    strgridLKSProduk.setBaseParam('kategori2', Ext.getCmp('id_lks_kategori2_sel').getValue());
    strgridLKSProduk.setBaseParam('kategori3', Ext.getCmp('id_lks_kategori3_sel').getValue());
    strgridLKSProduk.setBaseParam('kategori4', Ext.getCmp('id_lks_kategori4_sel').getValue());
    strgridLKSProduk.setBaseParam('ukuran', Ext.getCmp('id_lks_ukuran_sel').getValue());
    strgridLKSProduk.setBaseParam('satuan', Ext.getCmp('id_lks_satuan_sel').getValue());
    Ext.getCmp('id_searchgridLKSProduk').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX Produk
var searchgridLKSProduk = new Ext.app.SearchField({
    store: strgridLKSProduk,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridLKSProduk'
});

// GRID PANEL TWIN COMBOBOX Produk
var gridLKSProduk = new Ext.grid.GridPanel({
    store: strgridLKSProduk,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridLKSProduk,
    columns: [
        smgridLKSProduk,
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
        items: [searchgridLKSProduk]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridLKSProduk,
        displayInfo: true
    })
});

var menuLKSProduk = new Ext.menu.Menu();

menuLKSProduk.add(new Ext.Panel({
    title: 'Pilih Produk',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridLKSProduk],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridLKSProduk.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lks_produk_sel','kd_produk',sel);
                    sm.clearSelections();
                }
                menuLKSProduk.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridLKSProduk.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lks_produk_sel','kd_produk',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lks_produk_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuLKSProduk.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX Produk
Ext.ux.TwinComboLKSProduk = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridLKSKategori4.removeAll();
        var kategori1   = Ext.getCmp('id_lks_kategori1_sel').getValue();
        var kategori2   = Ext.getCmp('id_lks_kategori2_sel').getValue();
        var kategori3   = Ext.getCmp('id_lks_kategori3_sel').getValue();
        var kategori4   = Ext.getCmp('id_lks_kategori4_sel').getValue();
        var ukuran      = Ext.getCmp('id_lks_ukuran_sel').getValue();
        var satuan      = Ext.getCmp('id_lks_satuan_sel').getValue();
        strgridLKSProduk.load({params: {
            kategori1: kategori1,
            kategori2: kategori2,
            kategori3: kategori3,
            kategori4: kategori4,
            ukuran: ukuran,
            satuan: satuan
        }});
        menuLKSProduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuLKSProduk.on('hide', function () {
    var sf = Ext.getCmp('id_searchgridLKSProduk').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgridLKSProduk').setValue('');
        searchgridLKSProduk.onTrigger2Click();
    }
});

// TWIN COMBOBOX Produk
var comboLKSProduk = new Ext.ux.TwinComboLKSProduk({
    fieldLabel: 'Produk',
    id: 'id_cbLKSProduk',
    store: strLKSProduk,
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
var strCbLKSLokasi = new Ext.data.ArrayStore({
    fields: ['kd_lokasi', 'nama_lokasi'],
    data : []
});

var strGridLKSLokasi = new Ext.data.Store({
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

var searchGridLKSLokasi = new Ext.app.SearchField({
    store: strGridLKSLokasi,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchGridLKSLokasi'
});
var gridLKSLokasi = new Ext.grid.GridPanel({
    store: strGridLKSLokasi,
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
        items: [searchGridLKSLokasi]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strGridLKSLokasi,
        displayInfo: true
    }),
    listeners: {
        'rowdblclick': function(){
            var sm = this.getSelectionModel();
            var sel = sm.getSelections();
            if (sel.length > 0) {
                Ext.getCmp('id_comboLKSLokasi').setValue(sel[0].get('kd_lokasi'));
                Ext.getCmp('id_lks_kd_lokasi_sel').setValue(sel[0].get('kd_lokasi'));
                Ext.getCmp('id_lks_nama_lokasi_sel').setValue(sel[0].get('nama_lokasi'));
                menuLKSLokasi.hide();
            }
        }
    }
});

var menuLKSLokasi = new Ext.menu.Menu();
menuLKSLokasi.add(new Ext.Panel({
    title: 'Pilih Lokasi',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 600,
    height: 250,
    closeAction: 'hide',
    plain: true,
    items: [gridLKSLokasi],
    buttons: [{
        text: 'Close',
        handler: function(){
            menuLKSLokasi.hide();
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
        strGridLKSLokasi.load();
        menuLKSLokasi.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuLKSLokasi.on('hide', function(){
    var sf = Ext.getCmp('id_searchGridLKSLokasi').getValue();
    if( sf != ''){
        Ext.getCmp('id_searchGridLKSLokasi').setValue('');
        searchGridLKSLokasi.onTrigger2Click();
    }
});
//end twin lokasi
var comboLKSLokasi = new Ext.ux.TwinComb_mlo_asal({
    fieldLabel: 'Lokasi Asal <span class="asterix">*</span>',
    id: 'id_comboLKSLokasi',
    store: strCbLKSLokasi,
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

// -------- COMBOBOX PERUNTUKAN -------------------
var dsLKSPeruntukan=[['D',"Distribusi"],['B',"Bazar"],['S',"Supermarket"]];

var strLKSPeruntukan = new Ext.data.ArrayStore({
    fields: [{name: 'key'},{name: 'value'}],
    data: dsLKSPeruntukan
});

// COMBOBOX status
var comboLKSPeruntukan = new Ext.form.ComboBox({
    fieldLabel: 'Peruntukkan',
    id: 'id_cbReportJualPK1Status',
    name:'status',
    store: dsLKSPeruntukan,
    valueField:'key',
    hiddenName:'key',
    displayField:'value',
    mode:'local',
    forceSelection: true,
    triggerAction: 'all',
    anchor: '90%'
});
//-------- COMBOBOX PERUNTUKAN -------------------

//-------- CHECKBOX SORT ORDER -------------------
var checkboxLKSSort = new Ext.form.Checkbox({
    xtype: 'checkbox',
    fieldLabel: 'Sort Order',
    boxLabel: 'Descending',
    name: 'sort_order',
    id: 'id_lks_sort',
    checked: true,
    inputValue: '1',
    autoLoad: true
});
//-------- CHECKBOX SORT ORDER -------------------

// -------- MAIN FORM -------------------------
var headerLKStanggal = {
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
                        id: 'id_lks_tgl_awal',
                        anchor: '90%',
                        value: ''
                    },
                        comboLKSLokasi,
                        comboLKSSupplier,
                        comboLKSKategori1,
                        comboLKSKategori2,
                        comboLKSKategori3,
                        comboLKSKategori4,
                        comboLKSUkuran,
                        comboLKSSatuan,
                        comboLKSProduk,
                        comboLKSPeruntukan
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
                                id: 'id_lks_tgl_akhir',
                                anchor: '90%',
                                value: ''
                            },
                            {
                                xtype: 'hidden',
                                name: 'kd_lokasi',
                                id: 'id_lks_kd_lokasi_sel'
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Nama Lokasi',
                                name: 'nama_lokasi',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_lks_nama_lokasi_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Kd. Supplier',
                                name: 'kd_supplier_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_lks_kd_supplier_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Kd. Kategori 1',
                                name: 'kd_kategori1_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_lks_kategori1_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Kd. Kategori 2',
                                name: 'kd_kategori2_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_lks_kategori2_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Kd. Kategori 3',
                                name: 'kd_kategori3_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_lks_kategori3_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Kd. Kategori 4',
                                name: 'kd_kategori4_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_lks_kategori4_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Ukuran',
                                name: 'kd_ukuran_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_lks_ukuran_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Satuan',
                                name: 'kd_satuan_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_lks_satuan_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Kode Produk',
                                name: 'kd_produk_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_lks_produk_sel',
                                anchor: '90%',
                                value:''
                            },
                            checkboxLKSSort
                        ]
                    }]
            }]
        }]
    }]
}


var laporanKartuStok = new Ext.FormPanel({
    id: 'rpt_kartu_stok',
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
            items: [headerLKStanggal],
            buttons: [
                {
                    text: 'Print',
                    formBind:true,
                    handler: function () {
                        Ext.getCmp('rpt_kartu_stok').getForm().submit({
                            url: '<?= site_url("laporan_penjualan1/get_report") ?>',
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

                                clearform('rpt_kartu_stok');
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
                    handler: function(){clearform('rpt_kartu_stok');}
                }
            ]
        }]
    }
    ]
});

</script>