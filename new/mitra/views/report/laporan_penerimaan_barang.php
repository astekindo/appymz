<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
// -------- COMBOBOX Supplier --------------------
var smgridLPBSupplier= new Ext.grid.CheckboxSelectionModel();

var strCbLPBSupplier = new Ext.data.ArrayStore({
    fields: ['kd_supplier'],
    data : []
});

var strgridLPBSupplier = new Ext.data.Store({
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

var searchgridLPBSupplier = new Ext.app.SearchField({
    store: strgridLPBSupplier,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridLPBSupplier'
});

var gridLPBSupplier = new Ext.grid.GridPanel({
    store: strgridLPBSupplier,
    stripeRows: true,
    frame: true,
    border:true,
    sm: smgridLPBSupplier,
    columns: [
        smgridLPBSupplier,
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
        items: [searchgridLPBSupplier]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridLPBSupplier,
        displayInfo: true
    })
});

var menuLPBSupplier = new Ext.menu.Menu();

menuLPBSupplier.add(new Ext.Panel({
    title: 'Pilih Supplier',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridLPBSupplier],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridLPBSupplier.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpb_kd_supplier_sel','kd_supplier',sel);
                    sm.clearSelections();
                }
                menuLPBSupplier.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridLPBSupplier.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpb_kd_supplier_sel','kd_supplier',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lpb_kd_supplier_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuLPBSupplier.hide(); }
        }]
}));

Ext.ux.TwinComboLPBSupplier = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function(){
        //load store grid
        strgridLPBSupplier.load();
        menuLPBSupplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuLPBSupplier.on('hide', function(){
    var sf = Ext.getCmp('id_searchgridLPBSupplier').getValue();
    if( sf != ''){
        Ext.getCmp('id_searchgridLPBSupplier').setValue('');
        searchgridLPBSupplier.onTrigger2Click();
    }
});

var comboLPBSupplier = new Ext.ux.TwinComboLPBSupplier({
    fieldLabel: 'Supplier',
    id: 'id_cbLPBSupplier',
    store: strCbLPBSupplier,
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

var smgridLPBKategori1 = new Ext.grid.CheckboxSelectionModel();

var strLPBKategori1 = new Ext.data.ArrayStore({
    fields: ['kd_kategori1', 'nama_kategori1'],
    data: []
});

// GRID PANEL TWIN COMBOBOX kategori1 Data Store
var strgridLPBKategori1 = new Ext.data.Store({
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

strgridLPBKategori1.on('load', function(){
    Ext.getCmp('id_searchgridLPBKategori1').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX kategori1
var searchgridLPBKategori1 = new Ext.app.SearchField({
    store: strgridLPBKategori1,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridLPBKategori1'
});

// GRID PANEL TWIN COMBOBOX kategori1
var gridLPBKategori1 = new Ext.grid.GridPanel({
    store: strgridLPBKategori1,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridLPBKategori1,
    columns: [
        smgridLPBKategori1,
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
        items: [searchgridLPBKategori1]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridLPBKategori1,
        displayInfo: true
    })
});

var menuLPBKategori1 = new Ext.menu.Menu();

menuLPBKategori1.add(new Ext.Panel({
    title: 'Pilih Kategori 1',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridLPBKategori1],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridLPBKategori1.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpb_kategori1_sel','kd_kategori1',sel);
                    sm.clearSelections();
                }
                menuLPBKategori1.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridLPBKategori1.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpb_kategori1_sel','kd_kategori1',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lpb_kategori1_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuLPBKategori1.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX kategori1
Ext.ux.TwinComboLPBKategori1 = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        //load store grid
        strgridLPBKategori1.removeAll();
        strgridLPBKategori1.load();
        menuLPBKategori1.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuLPBKategori1.on('hide', function () {
    var sf = Ext.getCmp('id_searchgridLPBKategori1').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgridLPBKategori1').setValue('');
        searchgridLPBKategori1.onTrigger2Click();
    }
});

// TWIN COMBOBOX kategori1
var comboLPBKategori1 = new Ext.ux.TwinComboLPBKategori1({
    fieldLabel: 'Kategori 1',
    id: 'id_cbLPBKategori1',
    store: strLPBKategori1,
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

var smgridLPBKategori2 = new Ext.grid.CheckboxSelectionModel();

var strLPBKategori2 = new Ext.data.ArrayStore({
    fields: ['kd_kategori2', 'nama_kategori2'],
    data: []
});

// GRID PANEL TWIN COMBOBOX kategori2 Data Store
var strgridLPBKategori2 = new Ext.data.Store({
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

strgridLPBKategori2.on('load', function(){
    strgridLPBKategori2.setBaseParam('kategori1', Ext.getCmp('id_lpb_kategori1_sel').getValue());
    Ext.getCmp('id_searchgridLPBKategori2').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX kategori2
var searchgridLPBKategori2 = new Ext.app.SearchField({
    store: strgridLPBKategori2,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridLPBKategori2'
});

// GRID PANEL TWIN COMBOBOX kategori2
var gridLPBKategori2 = new Ext.grid.GridPanel({
    store: strgridLPBKategori2,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridLPBKategori2,
    columns: [
        smgridLPBKategori2,
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
        items: [searchgridLPBKategori2]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridLPBKategori2,
        displayInfo: true
    })
});

var menuLPBKategori2 = new Ext.menu.Menu();

menuLPBKategori2.add(new Ext.Panel({
    title: 'Pilih Kategori 2',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridLPBKategori2],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridLPBKategori2.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpb_kategori2_sel','kd_kategori2',sel);
                    sm.clearSelections();
                }
                menuLPBKategori2.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridLPBKategori2.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpb_kategori2_sel','kd_kategori2',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lpb_kategori2_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuLPBKategori2.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX kategori2
Ext.ux.TwinComboLPBKategori2 = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridLPBKategori2.removeAll();
        var kategori1 = Ext.getCmp('id_lpb_kategori1_sel').getValue();
        if(kategori1.length>0) {
            strgridLPBKategori2.load({params: {kategori1: kategori1}});
        } else {
            strgridLPBKategori2.load();
        }
        menuLPBKategori2.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuLPBKategori2.on('hide', function () {
    var sf = Ext.getCmp('id_searchgridLPBKategori2').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgridLPBKategori2').setValue('');
        searchgridLPBKategori2.onTrigger2Click();
    }
});

// TWIN COMBOBOX kategori2
var comboLPBKategori2 = new Ext.ux.TwinComboLPBKategori2({
    fieldLabel: 'Kategori 2',
    id: 'id_cbLPBKategori2',
    store: strLPBKategori2,
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

var smgridLPBKategori3 = new Ext.grid.CheckboxSelectionModel();

var strLPBKategori3 = new Ext.data.ArrayStore({
    fields: ['kd_kategori3', 'nama_kategori3'],
    data: []
});

// GRID PANEL TWIN COMBOBOX kategori3 Data Store
var strgridLPBKategori3 = new Ext.data.Store({
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

strgridLPBKategori3.on('load', function(){
    strgridLPBKategori3.setBaseParam('kategori1', Ext.getCmp('id_lpb_kategori1_sel').getValue());
    strgridLPBKategori3.setBaseParam('kategori2', Ext.getCmp('id_lpb_kategori2_sel').getValue());
    Ext.getCmp('id_searchgridLPBKategori3').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX kategori3
var searchgridLPBKategori3 = new Ext.app.SearchField({
    store: strgridLPBKategori3,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridLPBKategori3'
});

// GRID PANEL TWIN COMBOBOX kategori3
var gridLPBKategori3 = new Ext.grid.GridPanel({
    store: strgridLPBKategori3,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridLPBKategori3,
    columns: [
        smgridLPBKategori3,
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
        items: [searchgridLPBKategori3]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridLPBKategori3,
        displayInfo: true
    })
});

var menuLPBKategori3 = new Ext.menu.Menu();

menuLPBKategori3.add(new Ext.Panel({
    title: 'Pilih Kategori 3',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridLPBKategori3],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridLPBKategori3.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpb_kategori3_sel','kd_kategori3',sel);
                    sm.clearSelections();
                }
                menuLPBKategori3.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridLPBKategori3.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpb_kategori3_sel','kd_kategori3',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lpb_kategori3_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuLPBKategori3.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX kategori3
Ext.ux.TwinComboLPBKategori3 = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridLPBKategori3.removeAll();
        //load store grid
        var kategori1 = Ext.getCmp('id_lpb_kategori1_sel').getValue();
        var kategori2 = Ext.getCmp('id_lpb_kategori2_sel').getValue();
        if(kategori1.length > 0 || kategori2.length > 0 ) {
            strgridLPBKategori3.load({params: {
                kategori1: kategori1,
                kategori2: kategori2
            }});
        } else {
            strgridLPBKategori3.load();
        }
        menuLPBKategori3.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuLPBKategori3.on('hide', function () {
    var sf = Ext.getCmp('id_searchgridLPBKategori3').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgridLPBKategori3').setValue('');
        searchgridLPBKategori3.onTrigger2Click();
    }
});

// TWIN COMBOBOX kategori3
var comboLPBKategori3 = new Ext.ux.TwinComboLPBKategori3({
    fieldLabel: 'Kategori 3',
    id: 'id_cbLPBKategori3',
    store: strLPBKategori3,
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

var smgridLPBKategori4 = new Ext.grid.CheckboxSelectionModel();

var strLPBKategori4 = new Ext.data.ArrayStore({
    fields: ['kd_kategori4', 'nama_kategori4'],
    data: []
});

// GRID PANEL TWIN COMBOBOX kategori4 Data Store
var strgridLPBKategori4 = new Ext.data.Store({
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

strgridLPBKategori4.on('load', function(){
    strgridLPBKategori4.setBaseParam('kategori1', Ext.getCmp('id_lpb_kategori1_sel').getValue());
    strgridLPBKategori4.setBaseParam('kategori2', Ext.getCmp('id_lpb_kategori2_sel').getValue());
    strgridLPBKategori4.setBaseParam('kategori3', Ext.getCmp('id_lpb_kategori3_sel').getValue());
    Ext.getCmp('id_searchgridLPBKategori4').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX kategori4
var searchgridLPBKategori4 = new Ext.app.SearchField({
    store: strgridLPBKategori4,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridLPBKategori4'
});

// GRID PANEL TWIN COMBOBOX kategori4
var gridLPBKategori4 = new Ext.grid.GridPanel({
    store: strgridLPBKategori4,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridLPBKategori4,
    columns: [
        smgridLPBKategori4,
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
        items: [searchgridLPBKategori4]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridLPBKategori4,
        displayInfo: true
    })
});

var menuLPBKategori4 = new Ext.menu.Menu();

menuLPBKategori4.add(new Ext.Panel({
    title: 'Pilih Kategori 4',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridLPBKategori4],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridLPBKategori4.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpb_kategori4_sel','kd_kategori4',sel);
                    sm.clearSelections();
                }
                menuLPBKategori4.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridLPBKategori4.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpb_kategori4_sel','kd_kategori4',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lpb_kategori4_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuLPBKategori4.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX kategori4
Ext.ux.TwinComboLPBKategori4 = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridLPBKategori4.removeAll();
        var kategori1 = Ext.getCmp('id_lpb_kategori1_sel').getValue();
        var kategori2 = Ext.getCmp('id_lpb_kategori2_sel').getValue();
        var kategori3 = Ext.getCmp('id_lpb_kategori3_sel').getValue();
        if(kategori1.length > 0 || kategori2.length > 0 || kategori3.length > 0 ) {
            strgridLPBKategori4.load({params: {
                kategori1: kategori1,
                kategori2: kategori2,
                kategori3: kategori3
            }});
        } else {
            strgridLPBKategori4.load();
        }
        menuLPBKategori4.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuLPBKategori4.on('hide', function () {
    var sf = Ext.getCmp('id_searchgridLPBKategori4').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgridLPBKategori4').setValue('');
        searchgridLPBKategori4.onTrigger2Click();
    }
});

// TWIN COMBOBOX kategori4
var comboLPBKategori4 = new Ext.ux.TwinComboLPBKategori4({
    fieldLabel: 'Kategori 4',
    id: 'id_cbLPBKategori4',
    store: strLPBKategori4,
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

var smgridLPBProduk = new Ext.grid.CheckboxSelectionModel();

var strLPBProduk = new Ext.data.ArrayStore({
    fields: ['kd_produk', 'nama_produk'],
    data: []
});

// GRID PANEL TWIN COMBOBOX Produk Data Store
var strgridLPBProduk = new Ext.data.Store({
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

strgridLPBProduk.on('load', function(){
    strgridLPBProduk.setBaseParam('kategori1', Ext.getCmp('id_lpb_kategori1_sel').getValue());
    strgridLPBProduk.setBaseParam('kategori2', Ext.getCmp('id_lpb_kategori2_sel').getValue());
    strgridLPBProduk.setBaseParam('kategori3', Ext.getCmp('id_lpb_kategori3_sel').getValue());
    strgridLPBProduk.setBaseParam('kategori4', Ext.getCmp('id_lpb_kategori4_sel').getValue());
    Ext.getCmp('id_searchgridLPBProduk').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX Produk
var searchgridLPBProduk = new Ext.app.SearchField({
    store: strgridLPBProduk,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridLPBProduk'
});

// GRID PANEL TWIN COMBOBOX Produk
var gridLPBProduk = new Ext.grid.GridPanel({
    store: strgridLPBProduk,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridLPBProduk,
    columns: [
        smgridLPBProduk,
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
        items: [searchgridLPBProduk]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridLPBProduk,
        displayInfo: true
    })
});

var menuLPBProduk = new Ext.menu.Menu();

menuLPBProduk.add(new Ext.Panel({
    title: 'Pilih Produk',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridLPBProduk],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridLPBProduk.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpb_produk_sel','kd_produk',sel);
                    sm.clearSelections();
                }
                menuLPBProduk.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridLPBProduk.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpb_produk_sel','kd_produk',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lpb_produk_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuLPBProduk.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX Produk
Ext.ux.TwinComboLPBProduk = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridLPBProduk.removeAll();
        var kategori1   = Ext.getCmp('id_lpb_kategori1_sel').getValue();
        var kategori2   = Ext.getCmp('id_lpb_kategori2_sel').getValue();
        var kategori3   = Ext.getCmp('id_lpb_kategori3_sel').getValue();
        var kategori4   = Ext.getCmp('id_lpb_kategori4_sel').getValue();
        strgridLPBProduk.load({params: {
            kategori1: kategori1,
            kategori2: kategori2,
            kategori3: kategori3,
            kategori4: kategori4
        }});
        menuLPBProduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuLPBProduk.on('hide', function () {
    var sf = Ext.getCmp('id_searchgridLPBProduk').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgridLPBProduk').setValue('');
        searchgridLPBProduk.onTrigger2Click();
    }
});

// TWIN COMBOBOX Produk
var comboLPBProduk = new Ext.ux.TwinComboLPBProduk({
    fieldLabel: 'Produk',
    id: 'id_cbLPBProduk',
    store: strLPBProduk,
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

//-------- COMBOBOX KONSINYASI ----------------
var strLPBKonsinyasi = new Ext.data.ArrayStore({
    fields: [{name: 'kd_kons'},{name: 'nama_kons'}],
    data: [['A',"Semua"], ['Y',"Hanya Konsinyasi"], ['T',"Bukan Konsinyasi"]]
});

var comboLPBKonsinyasi = new Ext.form.ComboBox({
    fieldLabel: 'Jenis',
    id: 'id_cb_lpb_konsinyasi',
    name:'konsinyasi',
    store: strLPBKonsinyasi,
    valueField:'kd_kons',
    hiddenName:'kd_kons',
    displayField:'nama_kons',
    mode:'local',
    forceSelection: true,
    triggerAction: 'all',
    anchor: '90%'
});

//-------- COMBOBOX KONSINYASI ----------------

//-------- COMBOBOX ReceiveOrder ---------------------

var smgridLPBReceiveOrder = new Ext.grid.CheckboxSelectionModel();

var strLPBReceiveOrder = new Ext.data.ArrayStore({
    fields: ['no_do', 'kd_supplier'],
    data: []
});

// GRID PANEL TWIN COMBOBOX ReceiveOrder Data Store
var strgridLPBReceiveOrder = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: ['no_do', 'kd_supplier', 'nama_supplier', 'created_date', 'tanggal_terima'],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("report/get_no_ro") ?>',
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

strgridLPBReceiveOrder.on('load', function(){
    strgridLPBReceiveOrder.setBaseParam('kd_supplier', Ext.getCmp('id_lpb_kd_supplier_sel').getValue());
    strgridLPBReceiveOrder.setBaseParam('tgl_awal', Ext.getCmp('id_lpb_tgl_awal').getValue());
    strgridLPBReceiveOrder.setBaseParam('tgl_akhir', Ext.getCmp('id_lpb_tgl_akhir').getValue());
    strgridLPBReceiveOrder.setBaseParam('konsinyasi', Ext.getCmp('id_cb_lpb_konsinyasi').getValue());
    Ext.getCmp('id_searchgrid_lpb_receiveorder').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX ReceiveOrder
var searchgridLPBReceiveOrder = new Ext.app.SearchField({
    store: strgridLPBReceiveOrder,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgrid_lpb_receiveorder'
});

// GRID PANEL TWIN COMBOBOX ReceiveOrder
var gridLPBReceiveOrder = new Ext.grid.GridPanel({
    store: strgridLPBReceiveOrder,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridLPBReceiveOrder,
    columns: [
        smgridLPBReceiveOrder,
        {
            header: 'No. RO',
            dataIndex: 'no_do',
            width: 100,
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
        items: [searchgridLPBReceiveOrder]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridLPBReceiveOrder,
        displayInfo: true
    })
});

var menuLPBReceiveOrder = new Ext.menu.Menu();

menuLPBReceiveOrder.add(new Ext.Panel({
    title: 'Pilih No. RO',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridLPBReceiveOrder],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridLPBReceiveOrder.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpb_ro_sel','no_do',sel);
                    sm.clearSelections();
                }
                menuLPBReceiveOrder.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridLPBReceiveOrder.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpb_ro_sel','no_do',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lpb_ro_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuLPBReceiveOrder.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX ReceiveOrder
Ext.ux.TwinComboLPBReceiveOrder = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridLPBReceiveOrder.removeAll();
        var kd_supplier = Ext.getCmp('id_lpb_kd_supplier_sel').getValue();
        var tgl_awal    = Ext.getCmp('id_lpb_tgl_awal').getValue();
        var tgl_akhir   = Ext.getCmp('id_lpb_tgl_akhir').getValue();
        var konsinyasi  = Ext.getCmp('id_cb_lpb_konsinyasi').getValue();
        strgridLPBReceiveOrder.load({params: {
            kd_supplier : kd_supplier,
            tgl_awal    : tgl_awal,
            tgl_akhir   : tgl_akhir,
            konsinyasi  : konsinyasi
        }});
        menuLPBReceiveOrder.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuLPBReceiveOrder.on('hide', function () {
    var sf = Ext.getCmp('id_searchgrid_lpb_receiveorder').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgrid_lpb_receiveorder').setValue('');
        searchgridLPBReceiveOrder.onTrigger2Click();
    }
});

// TWIN COMBOBOX ReceiveOrder
var comboLPBReceiveOrder = new Ext.ux.TwinComboLPBReceiveOrder({
    fieldLabel: 'Receive Order',
    id: 'id_cbLPBReceiveOrder',
    store: strLPBReceiveOrder,
    mode: 'local',
    valueField: 'kd_produk',
    displayField: 'kd_produk',
    typeAhead: true,
    triggerAction: 'all',
    editable: false,
    anchor: '90%',
    hiddenName: 'kd_produk',
    emptyText: 'Pilih No. RO'
});
//-------- COMBOBOX ReceiveOrder ---------------------

//-------- COMBOBOX PurchaseOrder ---------------------

var smgridLPBPurchaseOrder = new Ext.grid.CheckboxSelectionModel();

var strLPBPurchaseOrder = new Ext.data.ArrayStore({
    fields: ['no_po'],
    data: []
});

// GRID PANEL TWIN COMBOBOX PurchaseOrder Data Store
var strgridLPBPurchaseOrder = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: ['no_po', 'tanggal_po', 'kd_supplier', 'nama_supplier'],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("report/get_no_po") ?>',
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

strgridLPBPurchaseOrder.on('load', function(){
    strgridLPBPurchaseOrder.setBaseParam('kd_supplier', Ext.getCmp('id_lpb_kd_supplier_sel').getValue());
    strgridLPBPurchaseOrder.setBaseParam('tgl_awal', Ext.getCmp('id_lpb_tgl_awal').getValue());
    strgridLPBPurchaseOrder.setBaseParam('tgl_akhir', Ext.getCmp('id_lpb_tgl_akhir').getValue());
    strgridLPBPurchaseOrder.setBaseParam('konsinyasi', Ext.getCmp('id_cb_lpb_konsinyasi').getValue());
    strgridLPBPurchaseOrder.setBaseParam('no_ro', Ext.getCmp('id_lpb_ro_sel').getValue());
    Ext.getCmp('id_searchgrid_lpb_po').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX PurchaseOrder
var searchgridLPBPurchaseOrder = new Ext.app.SearchField({
    store: strgridLPBPurchaseOrder,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgrid_lpb_po'
});

// GRID PANEL TWIN COMBOBOX PurchaseOrder
var gridLPBPurchaseOrder = new Ext.grid.GridPanel({
    store: strgridLPBPurchaseOrder,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridLPBPurchaseOrder,
    columns: [
        smgridLPBPurchaseOrder,
        {
            header: 'No. PO',
            dataIndex: 'no_po',
            width: 100,
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
        items: [searchgridLPBPurchaseOrder]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridLPBPurchaseOrder,
        displayInfo: true
    })
});

var menuLPBPurchaseOrder = new Ext.menu.Menu();

menuLPBPurchaseOrder.add(new Ext.Panel({
    title: 'Pilih No. PO',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridLPBPurchaseOrder],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridLPBPurchaseOrder.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpb_po_sel','no_po',sel);
                    sm.clearSelections();
                }
                menuLPBPurchaseOrder.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridLPBPurchaseOrder.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpb_po_sel','no_po',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lpb_po_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuLPBPurchaseOrder.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX PurchaseOrder
Ext.ux.TwinComboLPBPurchaseOrder = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridLPBPurchaseOrder.removeAll();
        var kd_supplier = Ext.getCmp('id_lpb_kd_supplier_sel').getValue();
        var tgl_awal    = Ext.getCmp('id_lpb_tgl_awal').getValue();
        var tgl_akhir   = Ext.getCmp('id_lpb_tgl_akhir').getValue();
        var konsinyasi  = Ext.getCmp('id_cb_lpb_konsinyasi').getValue();
        var no_ro       = Ext.getCmp('id_lpb_ro_sel').getValue();
        strgridLPBPurchaseOrder.load({params: {
            kd_supplier : kd_supplier,
            tgl_awal    : tgl_awal,
            tgl_akhir   : tgl_akhir,
            konsinyasi  : konsinyasi,
            no_ro       : no_ro
        }});
        menuLPBPurchaseOrder.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuLPBPurchaseOrder.on('hide', function () {
    var sf = Ext.getCmp('id_searchgrid_lpb_po').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgrid_lpb_po').setValue('');
        searchgridLPBPurchaseOrder.onTrigger2Click();
    }
});

// TWIN COMBOBOX PurchaseOrder
var comboLPBPurchaseOrder = new Ext.ux.TwinComboLPBPurchaseOrder({
    fieldLabel: 'Purchase Order',
    id: 'id_cbLPBPurchaseOrder',
    store: strLPBPurchaseOrder,
    mode: 'local',
    valueField: 'kd_produk',
    displayField: 'kd_produk',
    typeAhead: true,
    triggerAction: 'all',
    editable: false,
    anchor: '90%',
    hiddenName: 'kd_produk',
    emptyText: 'Pilih No. PO'
});
//-------- COMBOBOX PurchaseOrder ---------------------


//-------- CHECKBOX SORT ORDER ----------------
var checkboxLPBSort = new Ext.form.Checkbox({
    xtype: 'checkbox',
    fieldLabel: 'Sort Order',
    boxLabel: 'Descending',
    name: 'sort_order',
    id: 'id_lpb_sort',
    checked: true,
    inputValue: '1',
    autoLoad: true
});
//-------- CHECKBOX SORT ORDER ----------------

// -------- MAIN FORM -------------------------
var headerLPBtanggal = {
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
                        id: 'id_lpb_tgl_awal',
                        anchor: '90%',
                        value: ''
                    },
                        comboLPBSupplier,
                        comboLPBKategori1,
                        comboLPBKategori2,
                        comboLPBKategori3,
                        comboLPBKategori4,
                        comboLPBProduk,
                        comboLPBKonsinyasi,
                        comboLPBReceiveOrder,
                        comboLPBPurchaseOrder
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
                                id: 'id_lpb_tgl_akhir',
                                anchor: '90%',
                                value: ''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Kd. Supplier',
                                name: 'kd_supplier_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_lpb_kd_supplier_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Kd. Kategori 1',
                                name: 'kd_kategori1_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_lpb_kategori1_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Kd. Kategori 2',
                                name: 'kd_kategori2_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_lpb_kategori2_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Kd. Kategori 3',
                                name: 'kd_kategori3_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_lpb_kategori3_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Kd. Kategori 4',
                                name: 'kd_kategori4_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_lpb_kategori4_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Kode Produk',
                                name: 'kd_produk_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_lpb_produk_sel',
                                anchor: '90%',
                                value:''
                            },
                            checkboxLPBSort,
                            {
                                xtype: 'textfield',
                                fieldLabel: 'No RO',
                                name: 'no_ro_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_lpb_ro_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'No PO',
                                name: 'no_po_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_lpb_po_sel',
                                anchor: '90%',
                                value:''
                            }
                        ]
                    }]
            }]
        }]
    }]
}


var laporanPenerimaanBarang = new Ext.FormPanel({
    id: 'laporanpenerimaanbarang',
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
            items: [headerLPBtanggal],
            buttons: [
                {
                    text: 'Print',
                    formBind:true,
                    handler: function () {
                        Ext.getCmp('laporanpenerimaanbarang').getForm().submit({
                            url: '<?= site_url("laporan_rekap_stok_pergudang/get_report") ?>',
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

                                clearform('laporanpenerimaanbarang');
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
                    handler: function(){clearform('laporanpenerimaanbarang');}
                }
            ]
        }]
    }
    ]
});

</script>