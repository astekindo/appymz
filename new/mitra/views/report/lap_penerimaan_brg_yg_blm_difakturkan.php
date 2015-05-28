<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
// -------- COMBOBOX Kategori 1 ------------------

var smgridLPBBFKategori1 = new Ext.grid.CheckboxSelectionModel();

var strLPBBFKategori1 = new Ext.data.ArrayStore({
    fields: ['kd_kategori1', 'nama_kategori1'],
    data: []
});

// GRID PANEL TWIN COMBOBOX kategori1 Data Store
var strgridLPBBFKategori1 = new Ext.data.Store({
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

strgridLPBBFKategori1.on('load', function(){
    Ext.getCmp('id_searchgrid_lpbbf_kategori1').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX kategori1
var searchgridLPBBFKategori1 = new Ext.app.SearchField({
    store: strgridLPBBFKategori1,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgrid_lpbbf_kategori1'
});

// GRID PANEL TWIN COMBOBOX kategori1
var gridLPBBFKategori1 = new Ext.grid.GridPanel({
    store: strgridLPBBFKategori1,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridLPBBFKategori1,
    columns: [
        smgridLPBBFKategori1,
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
        items: [searchgridLPBBFKategori1]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridLPBBFKategori1,
        displayInfo: true
    })
});

var menuLPBBFKategori1 = new Ext.menu.Menu();

menuLPBBFKategori1.add(new Ext.Panel({
    title: 'Pilih Kategori 1',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridLPBBFKategori1],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridLPBBFKategori1.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpbbf_kategori1_sel','kd_kategori1',sel);
                    sm.clearSelections();
                }
                menuLPBBFKategori1.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridLPBBFKategori1.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpbbf_kategori1_sel','kd_kategori1',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lpbbf_kategori1_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuLPBBFKategori1.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX kategori1
Ext.ux.TwinComboLPBBFKategori1 = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        //load store grid
        strgridLPBBFKategori1.removeAll();
        strgridLPBBFKategori1.load();
        menuLPBBFKategori1.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuLPBBFKategori1.on('hide', function () {
    var sf = Ext.getCmp('id_searchgrid_lpbbf_kategori1').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgrid_lpbbf_kategori1').setValue('');
        searchgridLPBBFKategori1.onTrigger2Click();
    }
});

// TWIN COMBOBOX kategori1
var comboLPBBFKategori1 = new Ext.ux.TwinComboLPBBFKategori1({
    fieldLabel: 'Kategori 1',
    id: 'id_cb_lpbbf_kategori1',
    store: strLPBBFKategori1,
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
var smgridLPBBFKategori2 = new Ext.grid.CheckboxSelectionModel();

var strLPBBFKategori2 = new Ext.data.ArrayStore({
    fields: ['kd_kategori2', 'nama_kategori2'],
    data: []
});

// GRID PANEL TWIN COMBOBOX kategori2 Data Store
var strgridLPBBFKategori2 = new Ext.data.Store({
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

strgridLPBBFKategori2.on('load', function(){
    strgridLPBBFKategori2.setBaseParam('kategori1', Ext.getCmp('id_lpbbf_kategori1_sel').getValue());
    Ext.getCmp('id_searchgrid_lpbbf_kategori2').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX kategori2
var searchgridLPBBFKategori2 = new Ext.app.SearchField({
    store: strgridLPBBFKategori2,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgrid_lpbbf_kategori2'
});

// GRID PANEL TWIN COMBOBOX kategori2
var gridLPBBFKategori2 = new Ext.grid.GridPanel({
    store: strgridLPBBFKategori2,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridLPBBFKategori2,
    columns: [
        smgridLPBBFKategori2,
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
        items: [searchgridLPBBFKategori2]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridLPBBFKategori2,
        displayInfo: true
    })
});

var menuLPBBFKategori2 = new Ext.menu.Menu();

menuLPBBFKategori2.add(new Ext.Panel({
    title: 'Pilih Kategori 2',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridLPBBFKategori2],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridLPBBFKategori2.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpbbf_kategori2_sel','kd_kategori2',sel);
                    sm.clearSelections();
                }
                menuLPBBFKategori2.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridLPBBFKategori2.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpbbf_kategori2_sel','kd_kategori2',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lpbbf_kategori2_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuLPBBFKategori2.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX kategori2
Ext.ux.TwinComboLPBBFKategori2 = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridLPBBFKategori2.removeAll();
        var kategori1 = Ext.getCmp('id_lpbbf_kategori1_sel').getValue();
        if(kategori1.length>0) {
            strgridLPBBFKategori2.load({params: {kategori1: kategori1}});
        } else {
            strgridLPBBFKategori2.load();
        }
        menuLPBBFKategori2.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuLPBBFKategori2.on('hide', function () {
    var sf = Ext.getCmp('id_searchgrid_lpbbf_kategori2').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgrid_lpbbf_kategori2').setValue('');
        searchgridLPBBFKategori2.onTrigger2Click();
    }
});

// TWIN COMBOBOX kategori2
var comboLPBBFKategori2 = new Ext.ux.TwinComboLPBBFKategori2({
    fieldLabel: 'Kategori 2',
    id: 'id_cb_lpbbf_kategori2',
    store: strLPBBFKategori2,
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
var smgridLPBBFKategori3 = new Ext.grid.CheckboxSelectionModel();

var strLPBBFKategori3 = new Ext.data.ArrayStore({
    fields: ['kd_kategori3', 'nama_kategori3'],
    data: []
});

// GRID PANEL TWIN COMBOBOX kategori3 Data Store
var strgridLPBBFKategori3 = new Ext.data.Store({
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

strgridLPBBFKategori3.on('load', function(){
    strgridLPBBFKategori3.setBaseParam('kategori1', Ext.getCmp('id_lpbbf_kategori1_sel').getValue());
    strgridLPBBFKategori3.setBaseParam('kategori2', Ext.getCmp('id_lpbbf_kategori2_sel').getValue());
    Ext.getCmp('id_searchgrid_lpbbf_kategori3').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX kategori3
var searchgridLPBBFKategori3 = new Ext.app.SearchField({
    store: strgridLPBBFKategori3,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgrid_lpbbf_kategori3'
});

// GRID PANEL TWIN COMBOBOX kategori3
var gridLPBBFKategori3 = new Ext.grid.GridPanel({
    store: strgridLPBBFKategori3,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridLPBBFKategori3,
    columns: [
        smgridLPBBFKategori3,
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
        items: [searchgridLPBBFKategori3]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridLPBBFKategori3,
        displayInfo: true
    })
});

var menuLPBBFKategori3 = new Ext.menu.Menu();

menuLPBBFKategori3.add(new Ext.Panel({
    title: 'Pilih Kategori 3',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridLPBBFKategori3],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridLPBBFKategori3.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpbbf_kategori3_sel','kd_kategori3',sel);
                    sm.clearSelections();
                }
                menuLPBBFKategori3.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridLPBBFKategori3.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpbbf_kategori3_sel','kd_kategori3',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lpbbf_kategori3_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuLPBBFKategori3.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX kategori3
Ext.ux.TwinComboLPBBFKategori3 = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridLPBBFKategori3.removeAll();
        //load store grid
        var kategori1 = Ext.getCmp('id_lpbbf_kategori1_sel').getValue();
        var kategori2 = Ext.getCmp('id_lpbbf_kategori2_sel').getValue();
        if(kategori1.length > 0 || kategori2.length > 0 ) {
            strgridLPBBFKategori3.load({params: {
                kategori1: kategori1,
                kategori2: kategori2
            }});
        } else {
            strgridLPBBFKategori3.load();
        }
        menuLPBBFKategori3.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuLPBBFKategori3.on('hide', function () {
    var sf = Ext.getCmp('id_searchgrid_lpbbf_kategori3').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgrid_lpbbf_kategori3').setValue('');
        searchgridLPBBFKategori3.onTrigger2Click();
    }
});

// TWIN COMBOBOX kategori3
var comboLPBBFKategori3 = new Ext.ux.TwinComboLPBBFKategori3({
    fieldLabel: 'Kategori 3',
    id: 'id_cb_lpbbf_kategori3',
    store: strLPBBFKategori3,
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
var smgridLPBBFKategori4 = new Ext.grid.CheckboxSelectionModel();

var strLPBBFKategori4 = new Ext.data.ArrayStore({
    fields: ['kd_kategori4', 'nama_kategori4'],
    data: []
});

// GRID PANEL TWIN COMBOBOX kategori4 Data Store
var strgridLPBBFKategori4 = new Ext.data.Store({
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

strgridLPBBFKategori4.on('load', function(){
    strgridLPBBFKategori4.setBaseParam('kategori1', Ext.getCmp('id_lpbbf_kategori1_sel').getValue());
    strgridLPBBFKategori4.setBaseParam('kategori2', Ext.getCmp('id_lpbbf_kategori2_sel').getValue());
    strgridLPBBFKategori4.setBaseParam('kategori3', Ext.getCmp('id_lpbbf_kategori3_sel').getValue());
    Ext.getCmp('id_searchgrid_lpbbf_kategori4').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX kategori4
var searchgridLPBBFKategori4 = new Ext.app.SearchField({
    store: strgridLPBBFKategori4,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgrid_lpbbf_kategori4'
});

// GRID PANEL TWIN COMBOBOX kategori4
var gridLPBBFKategori4 = new Ext.grid.GridPanel({
    store: strgridLPBBFKategori4,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridLPBBFKategori4,
    columns: [
        smgridLPBBFKategori4,
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
        items: [searchgridLPBBFKategori4]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridLPBBFKategori4,
        displayInfo: true
    })
});

var menuLPBBFKategori4 = new Ext.menu.Menu();

menuLPBBFKategori4.add(new Ext.Panel({
    title: 'Pilih Kategori 4',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridLPBBFKategori4],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridLPBBFKategori4.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpbbf_kategori4_sel','kd_kategori4',sel);
                    sm.clearSelections();
                }
                menuLPBBFKategori4.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridLPBBFKategori4.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpbbf_kategori4_sel','kd_kategori4',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lpbbf_kategori4_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuLPBBFKategori4.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX kategori4
Ext.ux.TwinComboLPBBFKategori4 = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridLPBBFKategori4.removeAll();
        var kategori1 = Ext.getCmp('id_lpbbf_kategori1_sel').getValue();
        var kategori2 = Ext.getCmp('id_lpbbf_kategori2_sel').getValue();
        var kategori3 = Ext.getCmp('id_lpbbf_kategori3_sel').getValue();
        if(kategori1.length > 0 || kategori2.length > 0 || kategori3.length > 0 ) {
            strgridLPBBFKategori4.load({params: {
                kategori1: kategori1,
                kategori2: kategori2,
                kategori3: kategori3
            }});
        } else {
            strgridLPBBFKategori4.load();
        }
        menuLPBBFKategori4.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuLPBBFKategori4.on('hide', function () {
    var sf = Ext.getCmp('id_searchgrid_lpbbf_kategori4').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgrid_lpbbf_kategori4').setValue('');
        searchgridLPBBFKategori4.onTrigger2Click();
    }
});

// TWIN COMBOBOX kategori4
var comboLPBBFKategori4 = new Ext.ux.TwinComboLPBBFKategori4({
    fieldLabel: 'Kategori 4',
    id: 'id_cb_lpbbf_kategori4',
    store: strLPBBFKategori4,
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
var smgridLPBBFSupplier= new Ext.grid.CheckboxSelectionModel();

var strCbLPBBFSupplier = new Ext.data.ArrayStore({
    fields: ['kd_supplier'],
    data : []
});

var strgridLPBBFSupplier = new Ext.data.Store({
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

var searchgridLPBBFSupplier = new Ext.app.SearchField({
    store: strgridLPBBFSupplier,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgrid_lpbbf_supplier'
});

var gridLPBBFSupplier = new Ext.grid.GridPanel({
    store: strgridLPBBFSupplier,
    stripeRows: true,
    frame: true,
    border:true,
    sm: smgridLPBBFSupplier,
    columns: [
        smgridLPBBFSupplier,
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
        items: [searchgridLPBBFSupplier]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridLPBBFSupplier,
        displayInfo: true
    })
});

var menuLPBBFSupplier = new Ext.menu.Menu();

menuLPBBFSupplier.add(new Ext.Panel({
    title: 'Pilih Supplier',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridLPBBFSupplier],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridLPBBFSupplier.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpbbf_supplier_sel','kd_supplier',sel);
                    sm.clearSelections();
                }
                menuLPBBFSupplier.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridLPBBFSupplier.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpbbf_supplier_sel','kd_supplier',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lpbbf_supplier_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuLPBBFSupplier.hide(); }
        }]
}));

Ext.ux.TwinComboLPBBFSupplier = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function(){
        //load store grid
        strgridLPBBFSupplier.load();
        menuLPBBFSupplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuLPBBFSupplier.on('hide', function(){
    var sf = Ext.getCmp('id_searchgrid_lpbbf_supplier').getValue();
    if( sf != ''){
        Ext.getCmp('id_searchgrid_lpbbf_supplier').setValue('');
        searchgridLPBBFSupplier.onTrigger2Click();
    }
});

var comboLPBBFSupplier = new Ext.ux.TwinComboLPBBFSupplier({
    fieldLabel: 'Supplier',
    id: 'id_cb_lpbbf_supplier',
    store: strCbLPBBFSupplier,
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

var smgridLPBBFPurchaseOrder = new Ext.grid.CheckboxSelectionModel();

var strLPBBFPurchaseOrder = new Ext.data.ArrayStore({
    fields: ['no_po'],
    data: []
});

// GRID PANEL TWIN COMBOBOX PurchaseOrder Data Store
var strgridLPBBFPurchaseOrder = new Ext.data.Store({
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

strgridLPBBFPurchaseOrder.on('load', function(){
    strgridLPBBFPurchaseOrder.setBaseParam('kd_supplier', Ext.getCmp('id_lpbbf_supplier_sel').getValue());
    strgridLPBBFPurchaseOrder.setBaseParam('konsinyasi', Ext.getCmp('id_cb_lpbbf_konsinyasi').getValue());
    Ext.getCmp('id_searchgrid_lpbbf_po').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX PurchaseOrder
var searchgridLPBBFPurchaseOrder = new Ext.app.SearchField({
    store: strgridLPBBFPurchaseOrder,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgrid_lpbbf_po'
});

// GRID PANEL TWIN COMBOBOX PurchaseOrder
var gridLPBBFPurchaseOrder = new Ext.grid.GridPanel({
    store: strgridLPBBFPurchaseOrder,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridLPBBFPurchaseOrder,
    columns: [
        smgridLPBBFPurchaseOrder,
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
        items: [searchgridLPBBFPurchaseOrder]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridLPBBFPurchaseOrder,
        displayInfo: true
    })
});

var menuLPBBFPurchaseOrder = new Ext.menu.Menu();

menuLPBBFPurchaseOrder.add(new Ext.Panel({
    title: 'Pilih No. PO',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridLPBBFPurchaseOrder],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridLPBBFPurchaseOrder.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpbbf_po_sel','no_po',sel);
                    sm.clearSelections();
                }
                menuLPBBFPurchaseOrder.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridLPBBFPurchaseOrder.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpbbf_po_sel','no_po',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lpbbf_po_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuLPBBFPurchaseOrder.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX PurchaseOrder
Ext.ux.TwinComboLPBBFPurchaseOrder = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridLPBBFPurchaseOrder.removeAll();
        var kd_supplier = Ext.getCmp('id_lpbbf_supplier_sel').getValue();
        var konsinyasi  = Ext.getCmp('id_cb_lpbbf_konsinyasi').getValue();
        strgridLPBBFPurchaseOrder.load({params: {
            kd_supplier : kd_supplier,
            konsinyasi  : konsinyasi
        }});
        strgridLPBBFPurchaseOrder.load();
        menuLPBBFPurchaseOrder.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuLPBBFPurchaseOrder.on('hide', function () {
    var sf = Ext.getCmp('id_searchgrid_lpbbf_po').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgrid_lpbbf_po').setValue('');
        searchgridLPBBFPurchaseOrder.onTrigger2Click();
    }
});

// TWIN COMBOBOX PurchaseOrder
var comboLPBBFPurchaseOrder = new Ext.ux.TwinComboLPBBFPurchaseOrder({
    fieldLabel: 'PurchaseOrder',
    id: 'id_cbLPBBFPurchaseOrder',
    store: strLPBBFPurchaseOrder,
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

//-------- COMBOBOX ReceiveOrder -----------------

var smgridLPBBFReceiveOrder = new Ext.grid.CheckboxSelectionModel();

var strLPBBFReceiveOrder = new Ext.data.ArrayStore({
    fields: ['no_do', 'kd_supplier'],
    data: []
});

// GRID PANEL TWIN COMBOBOX ReceiveOrder Data Store
var strgridLPBBFReceiveOrder = new Ext.data.Store({
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

strgridLPBBFReceiveOrder.on('load', function(){
    strgridLPBBFReceiveOrder.setBaseParam('kd_supplier', Ext.getCmp('id_lpbbf_supplier_sel').getValue());
    strgridLPBBFReceiveOrder.setBaseParam('konsinyasi', Ext.getCmp('id_cb_lpbbf_konsinyasi').getValue());
    Ext.getCmp('id_searchgrid_lpbbf_receiveorder').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX ReceiveOrder
var searchgridLPBBFReceiveOrder = new Ext.app.SearchField({
    store: strgridLPBBFReceiveOrder,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgrid_lpbbf_receiveorder'
});

// GRID PANEL TWIN COMBOBOX ReceiveOrder
var gridLPBBFReceiveOrder = new Ext.grid.GridPanel({
    store: strgridLPBBFReceiveOrder,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridLPBBFReceiveOrder,
    columns: [
        smgridLPBBFReceiveOrder,
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
        items: [searchgridLPBBFReceiveOrder]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridLPBBFReceiveOrder,
        displayInfo: true
    })
});

var menuLPBBFReceiveOrder = new Ext.menu.Menu();

menuLPBBFReceiveOrder.add(new Ext.Panel({
    title: 'Pilih No. RO',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridLPBBFReceiveOrder],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridLPBBFReceiveOrder.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpbbf_ro_sel','no_do',sel);
                    sm.clearSelections();
                }
                menuLPBBFReceiveOrder.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridLPBBFReceiveOrder.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpbbf_ro_sel','no_do',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lpbbf_ro_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuLPBBFReceiveOrder.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX ReceiveOrder
Ext.ux.TwinComboLPBBFReceiveOrder = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridLPBBFReceiveOrder.removeAll();
        var kd_supplier = Ext.getCmp('id_lpbbf_supplier_sel').getValue();
        var konsinyasi  = Ext.getCmp('id_cb_lpbbf_konsinyasi').getValue();
        strgridLPBBFReceiveOrder.load({params: {
            kd_supplier : kd_supplier,
            konsinyasi  : konsinyasi
        }});
        menuLPBBFReceiveOrder.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuLPBBFReceiveOrder.on('hide', function () {
    var sf = Ext.getCmp('id_searchgrid_lpbbf_receiveorder').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgrid_lpbbf_receiveorder').setValue('');
        searchgridLPBBFReceiveOrder.onTrigger2Click();
    }
});

// TWIN COMBOBOX ReceiveOrder
var comboLPBBFReceiveOrder = new Ext.ux.TwinComboLPBBFReceiveOrder({
    fieldLabel: 'Receive Order',
    id: 'id_cbLPBBFReceiveOrder',
    store: strLPBBFReceiveOrder,
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
//-------- COMBOBOX ReceiveOrder -----------------

// -------- Combobox Konsinyasi ------------------
var strLPBBFKonsinyasi = new Ext.data.ArrayStore({
    fields: [{name: 'kd_konsinyasi'},{name: 'nama_konsinyasi'}],
    data: [['A',"Semua"], ['K',"Hanya Konsinyasi"], ['N',"Bukan Konsinyasi"]]
});

var comboLPBBFKonsinyasi = new Ext.form.ComboBox({
    fieldLabel: 'Konsinyasi',
    id: 'id_cb_lpbbf_konsinyasi',
    name:'konsinyasi',
    store: strLPBBFKonsinyasi,
    valueField:'kd_konsinyasi',
    hiddenName:'kd_konsinyasi',
    displayField:'nama_konsinyasi',
    mode:'local',
    forceSelection: true,
    triggerAction: 'all',
    anchor: '90%'
});
// -------- Combobox Konsinyasi ------------------

// -------- Fieldset Kategori 1-4 ----------------
var formLPBBFfieldsetKategori = {
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
                        comboLPBBFKategori1,
                        comboLPBBFKategori2,
                        comboLPBBFKategori3,
                        comboLPBBFKategori4
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
                                id: 'id_lpbbf_kategori1_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Kategori 2',
                                name: 'kategori2_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_lpbbf_kategori2_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Kategori 3',
                                name: 'kategori3_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_lpbbf_kategori3_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Kategori 4',
                                name: 'kategori4_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_lpbbf_kategori4_sel',
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
var formLPBBFfieldsetSupplier = {
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
                        {
                            xtype: 'datefield',
                            fieldLabel: 'Tanggal',
                            name: 'dari_tgl',
                            allowBlank:false,
                            format:'d-m-Y',
                            editable:false,
                            id: 'id_lpbbf_tgl',
                            anchor: '90%',
                            value: ''
                        },
                        comboLPBBFSupplier,
                        comboLPBBFPurchaseOrder,
                        comboLPBBFReceiveOrder,
                        new Ext.form.Checkbox({
                            xtype: 'checkbox',
                            fieldLabel: 'Sort Order',
                            boxLabel: 'Descending',
                            name: 'sort_order',
                            id: 'id_lpbbf_sort_order',
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
                            comboLPBBFKonsinyasi,
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Kd. Supplier',
                                name: 'kd_supplier_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_lpbbf_supplier_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'No. PO',
                                name: 'no_po_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_lpbbf_po_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Kd. Produk',
                                name: 'kd_produk_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_lpbbf_produk_sel',
                                anchor: '90%',
                                value:''
                            }
                        ]
                    } ]
            } ]
        }]
    } ]
}
// -------- Fieldset Supplier --------------------


var laporanPurchaseOrder = new Ext.FormPanel({
    id: 'rpt_penerimaan_brg_yg_blm_difakturkan',
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
            items: [formLPBBFfieldsetSupplier, formLPBBFfieldsetKategori],
            buttons: [
                {
                    text: 'Print',
                    formBind:true,
                    handler: function () {
                        Ext.getCmp('rpt_penerimaan_brg_yg_blm_difakturkan').getForm().submit({
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

                                clearform('rpt_penerimaan_brg_yg_blm_difakturkan');
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
                    handler: function(){clearform('rpt_penerimaan_brg_yg_blm_difakturkan');}
                }
            ]
        }]
    }
    ]
});


</script>