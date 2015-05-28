<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
// -------- COMBOBOX Kategori 1 ------------------

var smgridPOPOKategori1 = new Ext.grid.CheckboxSelectionModel();

var strPOPOKategori1 = new Ext.data.ArrayStore({
    fields: ['kd_kategori1', 'nama_kategori1'],
    data: []
});

// GRID PANEL TWIN COMBOBOX kategori1 Data Store
var strgridPOPOKategori1 = new Ext.data.Store({
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

strgridPOPOKategori1.on('load', function(){
    Ext.getCmp('id_searchgrid_popo_kategori1').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX kategori1
var searchgridPOPOKategori1 = new Ext.app.SearchField({
    store: strgridPOPOKategori1,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgrid_popo_kategori1'
});

// GRID PANEL TWIN COMBOBOX kategori1
var gridPOPOKategori1 = new Ext.grid.GridPanel({
    store: strgridPOPOKategori1,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridPOPOKategori1,
    columns: [
        smgridPOPOKategori1,
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
        items: [searchgridPOPOKategori1]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridPOPOKategori1,
        displayInfo: true
    })
});

var menuPOPOKategori1 = new Ext.menu.Menu();

menuPOPOKategori1.add(new Ext.Panel({
    title: 'Pilih Kategori 1',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridPOPOKategori1],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridPOPOKategori1.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_popo_kategori1_sel','kd_kategori1',sel);
                    sm.clearSelections();
                }
                menuPOPOKategori1.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridPOPOKategori1.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_popo_kategori1_sel','kd_kategori1',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_popo_kategori1_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuPOPOKategori1.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX kategori1
Ext.ux.TwinComboPOPOKategori1 = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        //load store grid
        strgridPOPOKategori1.removeAll();
        strgridPOPOKategori1.load();
        menuPOPOKategori1.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuPOPOKategori1.on('hide', function () {
    var sf = Ext.getCmp('id_searchgrid_popo_kategori1').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgrid_popo_kategori1').setValue('');
        searchgridPOPOKategori1.onTrigger2Click();
    }
});

// TWIN COMBOBOX kategori1
var comboPOPOKategori1 = new Ext.ux.TwinComboPOPOKategori1({
    fieldLabel: 'Kategori 1',
    id: 'id_cb_popo_kategori1',
    store: strPOPOKategori1,
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
// -------- COMBOBOX Kategori 1 ------------------

// -------- COMBOBOX Kategori 2 ------------------
var smgridPOPOKategori2 = new Ext.grid.CheckboxSelectionModel();

var strPOPOKategori2 = new Ext.data.ArrayStore({
    fields: ['kd_kategori2', 'nama_kategori2'],
    data: []
});

// GRID PANEL TWIN COMBOBOX kategori2 Data Store
var strgridPOPOKategori2 = new Ext.data.Store({
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

strgridPOPOKategori2.on('load', function(){
    strgridPOPOKategori2.setBaseParam('kategori1', Ext.getCmp('id_popo_kategori1_sel').getValue());
    Ext.getCmp('id_searchgrid_popo_kategori2').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX kategori2
var searchgridPOPOKategori2 = new Ext.app.SearchField({
    store: strgridPOPOKategori2,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgrid_popo_kategori2'
});

// GRID PANEL TWIN COMBOBOX kategori2
var gridPOPOKategori2 = new Ext.grid.GridPanel({
    store: strgridPOPOKategori2,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridPOPOKategori2,
    columns: [
        smgridPOPOKategori2,
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
        items: [searchgridPOPOKategori2]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridPOPOKategori2,
        displayInfo: true
    })
});

var menuPOPOKategori2 = new Ext.menu.Menu();

menuPOPOKategori2.add(new Ext.Panel({
    title: 'Pilih Kategori 2',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridPOPOKategori2],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridPOPOKategori2.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_popo_kategori2_sel','kd_kategori2',sel);
                    sm.clearSelections();
                }
                menuPOPOKategori2.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridPOPOKategori2.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_popo_kategori2_sel','kd_kategori2',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_popo_kategori2_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuPOPOKategori2.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX kategori2
Ext.ux.TwinComboPOPOKategori2 = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridPOPOKategori2.removeAll();
        var kategori1 = Ext.getCmp('id_popo_kategori1_sel').getValue();
        if(kategori1.length>0) {
            strgridPOPOKategori2.load({params: {kategori1: kategori1}});
        } else {
            strgridPOPOKategori2.load();
        }
        menuPOPOKategori2.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuPOPOKategori2.on('hide', function () {
    var sf = Ext.getCmp('id_searchgrid_popo_kategori2').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgrid_popo_kategori2').setValue('');
        searchgridPOPOKategori2.onTrigger2Click();
    }
});

// TWIN COMBOBOX kategori2
var comboPOPOKategori2 = new Ext.ux.TwinComboPOPOKategori2({
    fieldLabel: 'Kategori 2',
    id: 'id_cb_popo_kategori2',
    store: strPOPOKategori2,
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
// -------- COMBOBOX Kategori 2 ------------------

// -------- COMBOBOX Kategori 3 ------------------
var smgridPOPOKategori3 = new Ext.grid.CheckboxSelectionModel();

var strPOPOKategori3 = new Ext.data.ArrayStore({
    fields: ['kd_kategori3', 'nama_kategori3'],
    data: []
});

// GRID PANEL TWIN COMBOBOX kategori3 Data Store
var strgridPOPOKategori3 = new Ext.data.Store({
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

strgridPOPOKategori3.on('load', function(){
    strgridPOPOKategori3.setBaseParam('kategori1', Ext.getCmp('id_popo_kategori1_sel').getValue());
    strgridPOPOKategori3.setBaseParam('kategori2', Ext.getCmp('id_popo_kategori2_sel').getValue());
    Ext.getCmp('id_searchgrid_popo_kategori3').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX kategori3
var searchgridPOPOKategori3 = new Ext.app.SearchField({
    store: strgridPOPOKategori3,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgrid_popo_kategori3'
});

// GRID PANEL TWIN COMBOBOX kategori3
var gridPOPOKategori3 = new Ext.grid.GridPanel({
    store: strgridPOPOKategori3,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridPOPOKategori3,
    columns: [
        smgridPOPOKategori3,
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
        items: [searchgridPOPOKategori3]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridPOPOKategori3,
        displayInfo: true
    })
});

var menuPOPOKategori3 = new Ext.menu.Menu();

menuPOPOKategori3.add(new Ext.Panel({
    title: 'Pilih Kategori 3',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridPOPOKategori3],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridPOPOKategori3.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_popo_kategori3_sel','kd_kategori3',sel);
                    sm.clearSelections();
                }
                menuPOPOKategori3.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridPOPOKategori3.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_popo_kategori3_sel','kd_kategori3',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_popo_kategori3_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuPOPOKategori3.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX kategori3
Ext.ux.TwinComboPOPOKategori3 = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridPOPOKategori3.removeAll();
        //load store grid
        var kategori1 = Ext.getCmp('id_popo_kategori1_sel').getValue();
        var kategori2 = Ext.getCmp('id_popo_kategori2_sel').getValue();
        if(kategori1.length > 0 || kategori2.length > 0 ) {
            strgridPOPOKategori3.load({params: {
                kategori1: kategori1,
                kategori2: kategori2
            }});
        } else {
            strgridPOPOKategori3.load();
        }
        menuPOPOKategori3.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuPOPOKategori3.on('hide', function () {
    var sf = Ext.getCmp('id_searchgrid_popo_kategori3').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgrid_popo_kategori3').setValue('');
        searchgridPOPOKategori3.onTrigger2Click();
    }
});

// TWIN COMBOBOX kategori3
var comboPOPOKategori3 = new Ext.ux.TwinComboPOPOKategori3({
    fieldLabel: 'Kategori 3',
    id: 'id_cb_popo_kategori3',
    store: strPOPOKategori3,
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
// -------- COMBOBOX Kategori 3 ------------------

// -------- COMBOBOX Kategori 4 ------------------
var smgridPOPOKategori4 = new Ext.grid.CheckboxSelectionModel();

var strPOPOKategori4 = new Ext.data.ArrayStore({
    fields: ['kd_kategori4', 'nama_kategori4'],
    data: []
});

// GRID PANEL TWIN COMBOBOX kategori4 Data Store
var strgridPOPOKategori4 = new Ext.data.Store({
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

strgridPOPOKategori4.on('load', function(){
    strgridPOPOKategori4.setBaseParam('kategori1', Ext.getCmp('id_popo_kategori1_sel').getValue());
    strgridPOPOKategori4.setBaseParam('kategori2', Ext.getCmp('id_popo_kategori2_sel').getValue());
    strgridPOPOKategori4.setBaseParam('kategori3', Ext.getCmp('id_popo_kategori3_sel').getValue());
    Ext.getCmp('id_searchgrid_popo_kategori4').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX kategori4
var searchgridPOPOKategori4 = new Ext.app.SearchField({
    store: strgridPOPOKategori4,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgrid_popo_kategori4'
});

// GRID PANEL TWIN COMBOBOX kategori4
var gridPOPOKategori4 = new Ext.grid.GridPanel({
    store: strgridPOPOKategori4,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridPOPOKategori4,
    columns: [
        smgridPOPOKategori4,
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
        items: [searchgridPOPOKategori4]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridPOPOKategori4,
        displayInfo: true
    })
});

var menuPOPOKategori4 = new Ext.menu.Menu();

menuPOPOKategori4.add(new Ext.Panel({
    title: 'Pilih Kategori 4',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridPOPOKategori4],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridPOPOKategori4.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_popo_kategori4_sel','kd_kategori4',sel);
                    sm.clearSelections();
                }
                menuPOPOKategori4.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridPOPOKategori4.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_popo_kategori4_sel','kd_kategori4',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_popo_kategori4_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuPOPOKategori4.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX kategori4
Ext.ux.TwinComboPOPOKategori4 = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridPOPOKategori4.removeAll();
        var kategori1 = Ext.getCmp('id_popo_kategori1_sel').getValue();
        var kategori2 = Ext.getCmp('id_popo_kategori2_sel').getValue();
        var kategori3 = Ext.getCmp('id_popo_kategori3_sel').getValue();
        if(kategori1.length > 0 || kategori2.length > 0 || kategori3.length > 0 ) {
            strgridPOPOKategori4.load({params: {
                kategori1: kategori1,
                kategori2: kategori2,
                kategori3: kategori3
            }});
        } else {
            strgridPOPOKategori4.load();
        }
        menuPOPOKategori4.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuPOPOKategori4.on('hide', function () {
    var sf = Ext.getCmp('id_searchgrid_popo_kategori4').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgrid_popo_kategori4').setValue('');
        searchgridPOPOKategori4.onTrigger2Click();
    }
});

// TWIN COMBOBOX kategori4
var comboPOPOKategori4 = new Ext.ux.TwinComboPOPOKategori4({
    fieldLabel: 'Kategori 4',
    id: 'id_cb_popo_kategori4',
    store: strPOPOKategori4,
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
// -------- COMBOBOX Kategori 4 ------------------

// -------- COMBOBOX Supplier --------------------
var smgridPOPOSupplier= new Ext.grid.CheckboxSelectionModel();

var strCbPOPOSupplier = new Ext.data.ArrayStore({
    fields: ['kd_supplier'],
    data : []
});

var strgridPOPOSupplier = new Ext.data.Store({
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

var searchgridPOPOSupplier = new Ext.app.SearchField({
    store: strgridPOPOSupplier,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgrid_popo_supplier'
});

var gridPOPOSupplier = new Ext.grid.GridPanel({
    store: strgridPOPOSupplier,
    stripeRows: true,
    frame: true,
    border:true,
    sm: smgridPOPOSupplier,
    columns: [
        smgridPOPOSupplier,
        {
            header: 'Kd Supplier',
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
        items: [searchgridPOPOSupplier]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridPOPOSupplier,
        displayInfo: true
    })
});

var menuPOPOSupplier = new Ext.menu.Menu();

menuPOPOSupplier.add(new Ext.Panel({
    title: 'Pilih Supplier',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridPOPOSupplier],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridPOPOSupplier.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_popo_supplier_sel','kd_supplier',sel);
                    sm.clearSelections();
                }
                menuPOPOSupplier.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridPOPOSupplier.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_popo_supplier_sel','kd_supplier',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_popo_supplier_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuPOPOSupplier.hide(); }
        }]
}));

Ext.ux.TwinComboPOPOSupplier = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function(){
        //load store grid
        strgridPOPOSupplier.load();
        menuPOPOSupplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuPOPOSupplier.on('hide', function(){
    var sf = Ext.getCmp('id_searchgrid_popo_supplier').getValue();
    if( sf != ''){
        Ext.getCmp('id_searchgrid_popo_supplier').setValue('');
        searchgridPOPOSupplier.onTrigger2Click();
    }
});

var comboPOPOSupplier = new Ext.ux.TwinComboPOPOSupplier({
    fieldLabel: 'Supplier',
    id: 'id_cb_popo_supplier',
    store: strCbPOPOSupplier,
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

// -------- Combobox PurchaseOrder ---------------

var smgridPOPOPurchaseOrder = new Ext.grid.CheckboxSelectionModel();

var strPOPOPurchaseOrder = new Ext.data.ArrayStore({
    fields: ['no_po'],
    data: []
});

// GRID PANEL TWIN COMBOBOX PurchaseOrder Data Store
var strgridPOPOPurchaseOrder = new Ext.data.Store({
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

strgridPOPOPurchaseOrder.on('load', function(){
    strgridPOPOPurchaseOrder.setBaseParam('kd_supplier', Ext.getCmp('id_popo_supplier_sel').getValue());
    strgridPOPOPurchaseOrder.setBaseParam('tgl_awal', Ext.getCmp('id_popo_tgl_awal').getValue());
    strgridPOPOPurchaseOrder.setBaseParam('tgl_akhir', Ext.getCmp('id_popo_tgl_akhir').getValue());
    strgridPOPOPurchaseOrder.setBaseParam('konsinyasi', Ext.getCmp('id_cb_popo_konsinyasi').getValue());
    Ext.getCmp('id_searchgrid_popo_po').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX PurchaseOrder
var searchgridPOPOPurchaseOrder = new Ext.app.SearchField({
    store: strgridPOPOPurchaseOrder,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgrid_popo_po'
});

// GRID PANEL TWIN COMBOBOX PurchaseOrder
var gridPOPOPurchaseOrder = new Ext.grid.GridPanel({
    store: strgridPOPOPurchaseOrder,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridPOPOPurchaseOrder,
    columns: [
        smgridPOPOPurchaseOrder,
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
        items: [searchgridPOPOPurchaseOrder]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridPOPOPurchaseOrder,
        displayInfo: true
    })
});

var menuPOPOPurchaseOrder = new Ext.menu.Menu();

menuPOPOPurchaseOrder.add(new Ext.Panel({
    title: 'Pilih No. PO',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridPOPOPurchaseOrder],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridPOPOPurchaseOrder.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_popo_po_sel','no_po',sel);
                    sm.clearSelections();
                }
                menuPOPOPurchaseOrder.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridPOPOPurchaseOrder.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_popo_po_sel','no_po',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_popo_po_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuPOPOPurchaseOrder.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX PurchaseOrder
Ext.ux.TwinComboPOPOPurchaseOrder = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridPOPOPurchaseOrder.removeAll();
        var kd_supplier = Ext.getCmp('id_popo_supplier_sel').getValue();
        var tgl_awal    = Ext.getCmp('id_popo_tgl_awal').getValue();
        var tgl_akhir   = Ext.getCmp('id_popo_tgl_akhir').getValue();
        var konsinyasi  = Ext.getCmp('id_cb_popo_konsinyasi').getValue();
        strgridPOPOPurchaseOrder.load({params: {
            kd_supplier : kd_supplier,
            tgl_awal    : tgl_awal,
            tgl_akhir   : tgl_akhir,
            konsinyasi  : konsinyasi
        }});
        strgridPOPOPurchaseOrder.load();
        menuPOPOPurchaseOrder.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuPOPOPurchaseOrder.on('hide', function () {
    var sf = Ext.getCmp('id_searchgrid_popo_po').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgrid_popo_po').setValue('');
        searchgridPOPOPurchaseOrder.onTrigger2Click();
    }
});

// TWIN COMBOBOX PurchaseOrder
var comboPOPOPurchaseOrder = new Ext.ux.TwinComboPOPOPurchaseOrder({
    fieldLabel: 'PurchaseOrder',
    id: 'id_cbPOPOPurchaseOrder',
    store: strPOPOPurchaseOrder,
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
// -------- Combobox PurchaseOrder ---------------

// -------- Combobox Produk ----------------------

var smgridPOPOProduk = new Ext.grid.CheckboxSelectionModel();

var strPOPOProduk = new Ext.data.ArrayStore({
    fields: ['kd_produk', 'nama_produk'],
    data: []
});

// GRID PANEL TWIN COMBOBOX Produk Data Store
var strgridPOPOProduk = new Ext.data.Store({
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

strgridPOPOProduk.on('load', function(){
    strgridPOPOProduk.setBaseParam('kategori1', Ext.getCmp('id_popo_kategori1_sel').getValue());
    strgridPOPOProduk.setBaseParam('kategori2', Ext.getCmp('id_popo_kategori2_sel').getValue());
    strgridPOPOProduk.setBaseParam('kategori3', Ext.getCmp('id_popo_kategori3_sel').getValue());
    strgridPOPOProduk.setBaseParam('kategori4', Ext.getCmp('id_popo_kategori4_sel').getValue());
    Ext.getCmp('id_searchgrid_popo_produk').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX Produk
var searchgridPOPOProduk = new Ext.app.SearchField({
    store: strgridPOPOProduk,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgrid_popo_produk'
});

// GRID PANEL TWIN COMBOBOX Produk
var gridPOPOProduk = new Ext.grid.GridPanel({
    store: strgridPOPOProduk,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridPOPOProduk,
    columns: [
        smgridPOPOProduk,
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
        items: [searchgridPOPOProduk]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridPOPOProduk,
        displayInfo: true
    })
});

var menuPOPOProduk = new Ext.menu.Menu();

menuPOPOProduk.add(new Ext.Panel({
    title: 'Pilih Produk',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridPOPOProduk],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridPOPOProduk.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_popo_produk_sel','kd_produk',sel);
                    sm.clearSelections();
                }
                menuPOPOProduk.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridPOPOProduk.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_popo_produk_sel','kd_produk',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_popo_produk_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuPOPOProduk.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX Produk
Ext.ux.TwinComboPOPOProduk = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridPOPOProduk.removeAll();
        var kategori1   = Ext.getCmp('id_popo_kategori1_sel').getValue();
        var kategori2   = Ext.getCmp('id_popo_kategori2_sel').getValue();
        var kategori3   = Ext.getCmp('id_popo_kategori3_sel').getValue();
        var kategori4   = Ext.getCmp('id_popo_kategori4_sel').getValue();
        strgridPOPOProduk.load({params: {
            kategori1: kategori1,
            kategori2: kategori2,
            kategori3: kategori3,
            kategori4: kategori4
        }});
        strgridPOPOProduk.load();
        menuPOPOProduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuPOPOProduk.on('hide', function () {
    var sf = Ext.getCmp('id_searchgrid_popo_produk').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgrid_popo_produk').setValue('');
        searchgridPOPOProduk.onTrigger2Click();
    }
});

// TWIN COMBOBOX Produk
var comboPOPOProduk = new Ext.ux.TwinComboPOPOProduk({
    fieldLabel: 'Produk',
    id: 'id_cb_popo_produk',
    store: strPOPOProduk,
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
// -------- Combobox Produk ----------------------

// -------- Combobox Konsinyasi ------------------
var strPOPOKonsinyasi = new Ext.data.ArrayStore({
    fields: [{name: 'kd_konsinyasi'},{name: 'nama_konsinyasi'}],
    data: [['A',"Semua"], ['K',"Hanya Konsinyasi"], ['N',"Bukan Konsinyasi"]]
});

var comboPOPOKonsinyasi = new Ext.form.ComboBox({
    fieldLabel: 'Konsinyasi',
    id: 'id_cb_popo_konsinyasi',
    name:'konsinyasi',
    store: strPOPOKonsinyasi,
    valueField:'kd_konsinyasi',
    hiddenName:'kd_konsinyasi',
    displayField:'nama_konsinyasi',
    mode:'local',
    forceSelection: true,
    triggerAction: 'all',
    anchor: '90%'
});
// -------- Combobox Konsinyasi ------------------

// -------- Fieldset Tanggal dan Sort Order ------
var formPOPOfieldsetTanggal = {
    layout: 'column',
    border: false,
    items: [{
        columnWidth: .8,
        layout: 'form',
        border: false,
        title: 'Periode',
        labelWidth: 100,
        defaults: { labelSeparator: ''},
        items: [{
            xtype: 'fieldset',
            autoHeight: true,
            items: [ {
                layout: 'column',
                items:[ {
                    columnWidth: .5,
                    layout: 'form',
                    border: false,
                    labelWidth: 100,
                    defaults: { labelSeparator: ''},
                    items:[{
                        xtype: 'datefield',
                        fieldLabel: 'Dari Tgl',
                        name: 'dari_tgl',
                        allowBlank:false,
                        format:'d-m-Y',
                        editable:false,
                        id: 'id_popo_tgl_awal',
                        anchor: '90%',
                        value: ''
                    }]
                },
                    {
                        columnWidth: .5,
                        layout: 'form',
                        border: false,
                        labelWidth: 100,
                        defaults: { labelSeparator: ''},
                        items:[{
                            xtype: 'datefield',
                            fieldLabel: 'Sampai Tgl',
                            name: 'sampai_tgl',
                            allowBlank:false,
                            editable:false,
                            format:'d-m-Y',
                            id: 'id_popo_tgl_akhir',
                            anchor: '90%',
                            value: ''
                        }]
                    } ]
            } ]
        }]
    } ]
}
// -------- Fieldset Tanggal dan Sort Order ------

// -------- Fieldset Masa Berlaku PO -------------
var formPOPOfieldsetMB = {
    layout: 'column',
    border: false,
    items: [{
        columnWidth: .8,
        layout: 'form',
        border: false,
        title: 'Masa Berlaku PO',
        labelWidth: 100,
        defaults: { labelSeparator: ''},
        items: [{
            xtype: 'fieldset',
            autoHeight: true,
            items: [ {
                layout: 'column',
                items:[ {
                    columnWidth: .5,
                    layout: 'form',
                    border: false,
                    labelWidth: 100,
                    defaults: { labelSeparator: ''},
                    items:[{
                        xtype: 'datefield',
                        fieldLabel: 'Dari Tgl',
                        name: 'berlaku_dari',
                        allowBlank:false,
                        format:'d-m-Y',
                        editable:false,
                        id: 'id_popo_masa_awal',
                        anchor: '90%',
                        value: ''
                    }]
                },
                    {
                        columnWidth: .5,
                        layout: 'form',
                        border: false,
                        labelWidth: 100,
                        defaults: { labelSeparator: ''},
                        items:[{
                            xtype: 'datefield',
                            fieldLabel: 'Sampai Tgl',
                            name: 'berlaku_sampai',
                            allowBlank:false,
                            editable:false,
                            format:'d-m-Y',
                            id: 'id_popo_masa_akhir',
                            anchor: '90%',
                            value: ''
                        }]
                    } ]
            } ]
        }]
    } ]
}
// -------- Fieldset Masa Berlaku PO -------------

// -------- Fieldset Kategori 1-4 ----------------
var formPOPOfieldsetKategori = {
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
            items: [ {
                layout: 'column',
                items:[ {
                    columnWidth: .5,
                    layout: 'form',
                    border: false,
                    labelWidth: 100,
                    defaults: { labelSeparator: ''},
                    items:[
                        comboPOPOKategori1,
                        comboPOPOKategori2,
                        comboPOPOKategori3,
                        comboPOPOKategori4
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
                                xtype: 'textfield',
                                fieldLabel: 'Kategori 1',
                                name: 'kategori1_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_popo_kategori1_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Kategori 2',
                                name: 'kategori2_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_popo_kategori2_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Kategori 3',
                                name: 'kategori3_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_popo_kategori3_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Kategori 4',
                                name: 'kategori4_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_popo_kategori4_sel',
                                anchor: '90%',
                                value:''
                            }
                        ]
                    } ]
            } ]
        }]
    } ]
}
// -------- Fieldset Kategori 1-4 ----------------

// -------- Fieldset Supplier --------------------
var formPOPOfieldsetSupplier = {
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
            items: [ {
                layout: 'column',
                items:[
                    {
                        columnWidth: .5,
                        layout: 'form',
                        border: false,
                        labelWidth: 100,
                        defaults: { labelSeparator: ''},
                        items:[
                            comboPOPOSupplier,
                            comboPOPOPurchaseOrder,
                            new Ext.form.Checkbox({
                                xtype: 'checkbox',
                                fieldLabel: 'Sort Order',
                                boxLabel: 'Descending',
                                name: 'sort_order',
                                id: 'id_popo_sort_order',
                                checked: true,
                                inputValue: '1',
                                autoLoad: true
                            })
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
                                xtype: 'textfield',
                                fieldLabel: 'Kd. Supplier',
                                name: 'kd_supplier_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_popo_supplier_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'No. PO',
                                name: 'no_po_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_popo_po_sel',
                                anchor: '90%',
                                value:''
                            },
                            comboPOPOKonsinyasi
                        ]
                    } ]
            } ]
        }]
    } ]
}
// -------- Fieldset Supplier --------------------

// -------- Fieldset Tanggal dan Sort Order ------
var formPOPOfieldsetProduk = {
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
            items: [ {
                layout: 'column',
                items:[
                    {
                        columnWidth: .5,
                        layout: 'form',
                        border: false,
                        labelWidth: 100,
                        defaults: { labelSeparator: ''},
                        items:[comboPOPOProduk]
                    },
                    {
                        columnWidth: .5,
                        layout: 'form',
                        border: false,
                        labelWidth: 100,
                        defaults: { labelSeparator: ''},
                        items:[
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Kd. Produk',
                                name: 'kd_produk_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_popo_produk_sel',
                                anchor: '90%',
                                value:''
                            }
                        ]
                    } ]
            } ]
        }]
    } ]
}
// -------- Fieldset Tanggal dan Sort Order ------


var laporanPurchaseOrder = new Ext.FormPanel({
    id: 'rpt_purchase_outstanding_po',
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
            items: [formPOPOfieldsetTanggal, formPOPOfieldsetMB, formPOPOfieldsetKategori, formPOPOfieldsetSupplier],
            buttons: [
                {
                    text: 'Print',
                    formBind:true,
                    handler: function () {
                        Ext.getCmp('rpt_purchase_outstanding_po').getForm().submit({
                            url: '<?= site_url("laporan_purchase_order/get_report") ?>',
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

                                clearform('rpt_purchase_outstanding_po');
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
                    handler: function(){clearform('rpt_purchase_outstanding_po');}
                }
            ]
        }]
    }
    ]
});


</script>