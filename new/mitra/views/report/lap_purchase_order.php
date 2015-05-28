<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
// -------- COMBOBOX Kategori 1 ------------------

var smgridLPOKategori1 = new Ext.grid.CheckboxSelectionModel();

var strLPOKategori1 = new Ext.data.ArrayStore({
    fields: ['kd_kategori1', 'nama_kategori1'],
    data: []
});

// GRID PANEL TWIN COMBOBOX kategori1 Data Store
var strgridLPOKategori1 = new Ext.data.Store({
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

strgridLPOKategori1.on('load', function(){
    Ext.getCmp('id_searchgrid_lpo_kategori1').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX kategori1
var searchgridLPOKategori1 = new Ext.app.SearchField({
    store: strgridLPOKategori1,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgrid_lpo_kategori1'
});

// GRID PANEL TWIN COMBOBOX kategori1
var gridLPOKategori1 = new Ext.grid.GridPanel({
    store: strgridLPOKategori1,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridLPOKategori1,
    columns: [
        smgridLPOKategori1,
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
        items: [searchgridLPOKategori1]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridLPOKategori1,
        displayInfo: true
    })
});

var menuLPOKategori1 = new Ext.menu.Menu();

menuLPOKategori1.add(new Ext.Panel({
    title: 'Pilih Kategori 1',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridLPOKategori1],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridLPOKategori1.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpo_kategori1_sel','kd_kategori1',sel);
                    sm.clearSelections();
                }
                menuLPOKategori1.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridLPOKategori1.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpo_kategori1_sel','kd_kategori1',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lpo_kategori1_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuLPOKategori1.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX kategori1
Ext.ux.TwinComboLPOKategori1 = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        //load store grid
        strgridLPOKategori1.removeAll();
        strgridLPOKategori1.load();
        menuLPOKategori1.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuLPOKategori1.on('hide', function () {
    var sf = Ext.getCmp('id_searchgrid_lpo_kategori1').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgrid_lpo_kategori1').setValue('');
        searchgridLPOKategori1.onTrigger2Click();
    }
});

// TWIN COMBOBOX kategori1
var comboLPOKategori1 = new Ext.ux.TwinComboLPOKategori1({
    fieldLabel: 'Kategori 1',
    id: 'id_cb_lpo_kategori1',
    store: strLPOKategori1,
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
var smgridLPOKategori2 = new Ext.grid.CheckboxSelectionModel();

var strLPOKategori2 = new Ext.data.ArrayStore({
    fields: ['kd_kategori2', 'nama_kategori2'],
    data: []
});

// GRID PANEL TWIN COMBOBOX kategori2 Data Store
var strgridLPOKategori2 = new Ext.data.Store({
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

strgridLPOKategori2.on('load', function(){
    strgridLPOKategori2.setBaseParam('kategori1', Ext.getCmp('id_lpo_kategori1_sel').getValue());
    Ext.getCmp('id_searchgrid_lpo_kategori2').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX kategori2
var searchgridLPOKategori2 = new Ext.app.SearchField({
    store: strgridLPOKategori2,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgrid_lpo_kategori2'
});

// GRID PANEL TWIN COMBOBOX kategori2
var gridLPOKategori2 = new Ext.grid.GridPanel({
    store: strgridLPOKategori2,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridLPOKategori2,
    columns: [
        smgridLPOKategori2,
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
        items: [searchgridLPOKategori2]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridLPOKategori2,
        displayInfo: true
    })
});

var menuLPOKategori2 = new Ext.menu.Menu();

menuLPOKategori2.add(new Ext.Panel({
    title: 'Pilih Kategori 2',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridLPOKategori2],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridLPOKategori2.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpo_kategori2_sel','kd_kategori',sel);
                    sm.clearSelections();
                }
                menuLPOKategori2.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridLPOKategori2.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpo_kategori2_sel','kd_kategori',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lpo_kategori2_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuLPOKategori2.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX kategori2
Ext.ux.TwinComboLPOKategori2 = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridLPOKategori2.removeAll();
        var kategori1 = Ext.getCmp('id_lpo_kategori1_sel').getValue();
        if(kategori1.length>0) {
            strgridLPOKategori2.load({params: {kategori1: kategori1}});
        } else {
            strgridLPOKategori2.load();
        }
        menuLPOKategori2.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuLPOKategori2.on('hide', function () {
    var sf = Ext.getCmp('id_searchgrid_lpo_kategori2').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgrid_lpo_kategori2').setValue('');
        searchgridLPOKategori2.onTrigger2Click();
    }
});

// TWIN COMBOBOX kategori2
var comboLPOKategori2 = new Ext.ux.TwinComboLPOKategori2({
    fieldLabel: 'Kategori 2',
    id: 'id_cb_lpo_kategori2',
    store: strLPOKategori2,
    mode: 'local',
    valueField: 'kd_kategori',
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
var smgridLPOKategori3 = new Ext.grid.CheckboxSelectionModel();

var strLPOKategori3 = new Ext.data.ArrayStore({
    fields: ['kd_kategori3', 'nama_kategori3'],
    data: []
});

// GRID PANEL TWIN COMBOBOX kategori3 Data Store
var strgridLPOKategori3 = new Ext.data.Store({
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

strgridLPOKategori3.on('load', function(){
    strgridLPOKategori3.setBaseParam('kategori1', Ext.getCmp('id_lpo_kategori1_sel').getValue());
    strgridLPOKategori3.setBaseParam('kategori2', Ext.getCmp('id_lpo_kategori2_sel').getValue());
    Ext.getCmp('id_searchgrid_lpo_kategori3').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX kategori3
var searchgridLPOKategori3 = new Ext.app.SearchField({
    store: strgridLPOKategori3,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgrid_lpo_kategori3'
});

// GRID PANEL TWIN COMBOBOX kategori3
var gridLPOKategori3 = new Ext.grid.GridPanel({
    store: strgridLPOKategori3,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridLPOKategori3,
    columns: [
        smgridLPOKategori3,
        {
            header: 'Kode kategori 3',
            dataIndex: 'kd_kategori',
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
        items: [searchgridLPOKategori3]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridLPOKategori3,
        displayInfo: true
    })
});

var menuLPOKategori3 = new Ext.menu.Menu();

menuLPOKategori3.add(new Ext.Panel({
    title: 'Pilih Kategori 3',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridLPOKategori3],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridLPOKategori3.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpo_kategori3_sel','kd_kategori',sel);
                    sm.clearSelections();
                }
                menuLPOKategori3.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridLPOKategori3.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpo_kategori3_sel','kd_kategori',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lpo_kategori3_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuLPOKategori3.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX kategori3
Ext.ux.TwinComboLPOKategori3 = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridLPOKategori3.removeAll();
        //load store grid
        var kategori1 = Ext.getCmp('id_lpo_kategori1_sel').getValue();
        var kategori2 = Ext.getCmp('id_lpo_kategori2_sel').getValue();
        if(kategori1.length > 0 || kategori2.length > 0 ) {
            strgridLPOKategori3.load({params: {
                kategori1: kategori1,
                kategori2: kategori2
            }});
        } else {
            strgridLPOKategori3.load();
        }
        menuLPOKategori3.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuLPOKategori3.on('hide', function () {
    var sf = Ext.getCmp('id_searchgrid_lpo_kategori3').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgrid_lpo_kategori3').setValue('');
        searchgridLPOKategori3.onTrigger2Click();
    }
});

// TWIN COMBOBOX kategori3
var comboLPOKategori3 = new Ext.ux.TwinComboLPOKategori3({
    fieldLabel: 'Kategori 3',
    id: 'id_cb_lpo_kategori3',
    store: strLPOKategori3,
    mode: 'local',
    valueField: 'kd_kategori',
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
var smgridLPOKategori4 = new Ext.grid.CheckboxSelectionModel();

var strLPOKategori4 = new Ext.data.ArrayStore({
    fields: ['kd_kategori4', 'nama_kategori4'],
    data: []
});

// GRID PANEL TWIN COMBOBOX kategori4 Data Store
var strgridLPOKategori4 = new Ext.data.Store({
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

strgridLPOKategori4.on('load', function(){
    strgridLPOKategori4.setBaseParam('kategori1', Ext.getCmp('id_lpo_kategori1_sel').getValue());
    strgridLPOKategori4.setBaseParam('kategori2', Ext.getCmp('id_lpo_kategori2_sel').getValue());
    strgridLPOKategori4.setBaseParam('kategori3', Ext.getCmp('id_lpo_kategori3_sel').getValue());
    Ext.getCmp('id_searchgrid_lpo_kategori4').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX kategori4
var searchgridLPOKategori4 = new Ext.app.SearchField({
    store: strgridLPOKategori4,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgrid_lpo_kategori4'
});

// GRID PANEL TWIN COMBOBOX kategori4
var gridLPOKategori4 = new Ext.grid.GridPanel({
    store: strgridLPOKategori4,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridLPOKategori4,
    columns: [
        smgridLPOKategori4,
        {
            header: 'Kode kategori 4',
            dataIndex: 'kd_kategori',
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
        items: [searchgridLPOKategori4]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridLPOKategori4,
        displayInfo: true
    })
});

var menuLPOKategori4 = new Ext.menu.Menu();

menuLPOKategori4.add(new Ext.Panel({
    title: 'Pilih Kategori 4',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridLPOKategori4],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridLPOKategori4.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpo_kategori4_sel','kd_kategori',sel);
                    sm.clearSelections();
                }
                menuLPOKategori4.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridLPOKategori4.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpo_kategori4_sel','kd_kategori',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lpo_kategori4_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuLPOKategori4.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX kategori4
Ext.ux.TwinComboLPOKategori4 = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridLPOKategori4.removeAll();
        var kategori1 = Ext.getCmp('id_lpo_kategori1_sel').getValue();
        var kategori2 = Ext.getCmp('id_lpo_kategori2_sel').getValue();
        var kategori3 = Ext.getCmp('id_lpo_kategori3_sel').getValue();
        if(kategori1.length > 0 || kategori2.length > 0 || kategori3.length > 0 ) {
            strgridLPOKategori4.load({params: {
                kategori1: kategori1,
                kategori2: kategori2,
                kategori3: kategori3
            }});
        } else {
            strgridLPOKategori4.load();
        }
        menuLPOKategori4.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuLPOKategori4.on('hide', function () {
    var sf = Ext.getCmp('id_searchgrid_lpo_kategori4').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgrid_lpo_kategori4').setValue('');
        searchgridLPOKategori4.onTrigger2Click();
    }
});

// TWIN COMBOBOX kategori4
var comboLPOKategori4 = new Ext.ux.TwinComboLPOKategori4({
    fieldLabel: 'Kategori 4',
    id: 'id_cb_lpo_kategori4',
    store: strLPOKategori4,
    mode: 'local',
    valueField: 'kd_kategori',
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
var smgridLPOSupplier= new Ext.grid.CheckboxSelectionModel();

var strCbLPOSupplier = new Ext.data.ArrayStore({
    fields: ['kd_supplier'],
    data : []
});

var strgridLPOSupplier = new Ext.data.Store({
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

var searchgridLPOSupplier = new Ext.app.SearchField({
    store: strgridLPOSupplier,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgrid_lpo_supplier'
});

var gridLPOSupplier = new Ext.grid.GridPanel({
    store: strgridLPOSupplier,
    stripeRows: true,
    frame: true,
    border:true,
    sm: smgridLPOSupplier,
    columns: [
        smgridLPOSupplier,
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
        items: [searchgridLPOSupplier]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridLPOSupplier,
        displayInfo: true
    })
});

var menuLPOSupplier = new Ext.menu.Menu();

menuLPOSupplier.add(new Ext.Panel({
    title: 'Pilih Supplier',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridLPOSupplier],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridLPOSupplier.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpo_supplier_sel','kd_supplier',sel);
                    sm.clearSelections();
                }
                menuLPOSupplier.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridLPOSupplier.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpo_supplier_sel','kd_supplier',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lpo_supplier_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuLPOSupplier.hide(); }
        }]
}));

Ext.ux.TwinComboLPOSupplier = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function(){
        //load store grid
        strgridLPOSupplier.load();
        menuLPOSupplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuLPOSupplier.on('hide', function(){
    var sf = Ext.getCmp('id_searchgrid_lpo_supplier').getValue();
    if( sf != ''){
        Ext.getCmp('id_searchgrid_lpo_supplier').setValue('');
        searchgridLPOSupplier.onTrigger2Click();
    }
});

var comboLPOSupplier = new Ext.ux.TwinComboLPOSupplier({
    fieldLabel: 'Supplier',
    id: 'id_cb_lpo_supplier',
    store: strCbLPOSupplier,
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

var smgridLPOPurchaseOrder = new Ext.grid.CheckboxSelectionModel();

var strLPOPurchaseOrder = new Ext.data.ArrayStore({
    fields: ['no_po'],
    data: []
});

// GRID PANEL TWIN COMBOBOX PurchaseOrder Data Store
var strgridLPOPurchaseOrder = new Ext.data.Store({
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

strgridLPOPurchaseOrder.on('load', function(){
    strgridLPOPurchaseOrder.setBaseParam('kd_supplier', Ext.getCmp('id_lpo_supplier_sel').getValue());
    strgridLPOPurchaseOrder.setBaseParam('tgl_awal', Ext.getCmp('id_lpo_tgl_awal').getValue());
    strgridLPOPurchaseOrder.setBaseParam('tgl_akhir', Ext.getCmp('id_lpo_tgl_akhir').getValue());
    strgridLPOPurchaseOrder.setBaseParam('konsinyasi', Ext.getCmp('id_cb_lpo_konsinyasi').getValue());
    Ext.getCmp('id_searchgrid_lpo_po').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX PurchaseOrder
var searchgridLPOPurchaseOrder = new Ext.app.SearchField({
    store: strgridLPOPurchaseOrder,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgrid_lpo_po'
});

// GRID PANEL TWIN COMBOBOX PurchaseOrder
var gridLPOPurchaseOrder = new Ext.grid.GridPanel({
    store: strgridLPOPurchaseOrder,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridLPOPurchaseOrder,
    columns: [
        smgridLPOPurchaseOrder,
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
        items: [searchgridLPOPurchaseOrder]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridLPOPurchaseOrder,
        displayInfo: true
    })
});

var menuLPOPurchaseOrder = new Ext.menu.Menu();

menuLPOPurchaseOrder.add(new Ext.Panel({
    title: 'Pilih No. PO',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridLPOPurchaseOrder],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridLPOPurchaseOrder.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpo_po_sel','no_po',sel);
                    sm.clearSelections();
                }
                menuLPOPurchaseOrder.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridLPOPurchaseOrder.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpo_po_sel','no_po',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lpo_po_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuLPOPurchaseOrder.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX PurchaseOrder
Ext.ux.TwinComboLPOPurchaseOrder = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridLPOPurchaseOrder.removeAll();
        var kd_supplier = Ext.getCmp('id_lpo_supplier_sel').getValue();
        var tgl_awal    = Ext.getCmp('id_lpo_tgl_awal').getValue();
        var tgl_akhir   = Ext.getCmp('id_lpo_tgl_akhir').getValue();
        var konsinyasi  = Ext.getCmp('id_cb_lpo_konsinyasi').getValue();
        strgridLPOPurchaseOrder.load({params: {
            kd_supplier : kd_supplier,
            tgl_awal    : tgl_awal,
            tgl_akhir   : tgl_akhir,
            konsinyasi  : konsinyasi
        }});
        strgridLPOPurchaseOrder.load();
        menuLPOPurchaseOrder.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuLPOPurchaseOrder.on('hide', function () {
    var sf = Ext.getCmp('id_searchgrid_lpo_po').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgrid_lpo_po').setValue('');
        searchgridLPOPurchaseOrder.onTrigger2Click();
    }
});

// TWIN COMBOBOX PurchaseOrder
var comboLPOPurchaseOrder = new Ext.ux.TwinComboLPOPurchaseOrder({
    fieldLabel: 'PurchaseOrder',
    id: 'id_cbLPOPurchaseOrder',
    store: strLPOPurchaseOrder,
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

var smgridLPOProduk = new Ext.grid.CheckboxSelectionModel();

var strLPOProduk = new Ext.data.ArrayStore({
    fields: ['kd_produk', 'nama_produk'],
    data: []
});

// GRID PANEL TWIN COMBOBOX Produk Data Store
var strgridLPOProduk = new Ext.data.Store({
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

strgridLPOProduk.on('load', function(){
    strgridLPOProduk.setBaseParam('kategori1', Ext.getCmp('id_lpo_kategori1_sel').getValue());
    strgridLPOProduk.setBaseParam('kategori2', Ext.getCmp('id_lpo_kategori2_sel').getValue());
    strgridLPOProduk.setBaseParam('kategori3', Ext.getCmp('id_lpo_kategori3_sel').getValue());
    strgridLPOProduk.setBaseParam('kategori4', Ext.getCmp('id_lpo_kategori4_sel').getValue());
    Ext.getCmp('id_searchgrid_lpo_produk').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX Produk
var searchgridLPOProduk = new Ext.app.SearchField({
    store: strgridLPOProduk,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgrid_lpo_produk'
});

// GRID PANEL TWIN COMBOBOX Produk
var gridLPOProduk = new Ext.grid.GridPanel({
    store: strgridLPOProduk,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridLPOProduk,
    columns: [
        smgridLPOProduk,
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
        items: [searchgridLPOProduk]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridLPOProduk,
        displayInfo: true
    })
});

var menuLPOProduk = new Ext.menu.Menu();

menuLPOProduk.add(new Ext.Panel({
    title: 'Pilih Produk',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridLPOProduk],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridLPOProduk.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpo_produk_sel','kd_produk',sel);
                    sm.clearSelections();
                }
                menuLPOProduk.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridLPOProduk.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lpo_produk_sel','kd_produk',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lpo_produk_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuLPOProduk.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX Produk
Ext.ux.TwinComboLPOProduk = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridLPOProduk.removeAll();
        var kategori1   = Ext.getCmp('id_lpo_kategori1_sel').getValue();
        var kategori2   = Ext.getCmp('id_lpo_kategori2_sel').getValue();
        var kategori3   = Ext.getCmp('id_lpo_kategori3_sel').getValue();
        var kategori4   = Ext.getCmp('id_lpo_kategori4_sel').getValue();
        strgridLPOProduk.load({params: {
             kategori1: kategori1,
             kategori2: kategori2,
             kategori3: kategori3,
             kategori4: kategori4
        }});
        strgridLPOProduk.load();
        menuLPOProduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuLPOProduk.on('hide', function () {
    var sf = Ext.getCmp('id_searchgrid_lpo_produk').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgrid_lpo_produk').setValue('');
        searchgridLPOProduk.onTrigger2Click();
    }
});

// TWIN COMBOBOX Produk
var comboLPOProduk = new Ext.ux.TwinComboLPOProduk({
    fieldLabel: 'Produk',
    id: 'id_cb_lpo_produk',
    store: strLPOProduk,
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

// -------- Combobox Status PKP ------------------
var strLPOStatus = new Ext.data.ArrayStore({
    fields: [{name: 'kd_status'},{name: 'nama_status'}],
    data: [['A',"Semua"], ['P',"PKP"], ['N',"Non PKP"]]
});

var comboLPOStatus = new Ext.form.ComboBox({
    fieldLabel: 'Status PKP',
    id: 'id_cb_rspg_status',
    name:'status_pkp',
    store: strLPOStatus,
    valueField:'kd_status',
    hiddenName:'kd_status',
    displayField:'nama_status',
    mode:'local',
    forceSelection: true,
    triggerAction: 'all',
    anchor: '90%'
});
// -------- Combobox Status PKP ------------------

// -------- Combobox Konsinyasi ------------------
var strLPOKonsinyasi = new Ext.data.ArrayStore({
    fields: [{name: 'kd_konsinyasi'},{name: 'nama_konsinyasi'}],
    data: [['A',"Semua"], ['K',"Hanya Konsinyasi"], ['N',"Bukan Konsinyasi"]]
});

var comboLPOKonsinyasi = new Ext.form.ComboBox({
    fieldLabel: 'Konsinyasi',
    id: 'id_cb_lpo_konsinyasi',
    name:'konsinyasi',
    store: strLPOKonsinyasi,
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
var formLPOfieldsetTanggal = {
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
                            fieldLabel: 'Dari Tgl',
                            name: 'dari_tgl',
                            allowBlank:false,
                            format:'d-m-Y',
                            editable:false,
                            id: 'id_lpo_tgl_awal',
                            anchor: '90%',
                            value: ''
                        },
                        new Ext.form.Checkbox({
                            xtype: 'checkbox',
                            fieldLabel: 'Sort Order',
                            boxLabel: 'Descending',
                            name: 'sort_order',
                            id: 'id_lpo_sort_order',
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
                        items:[{
                            xtype: 'datefield',
                            fieldLabel: 'Sampai Tgl',
                            name: 'sampai_tgl',
                            allowBlank:false,
                            editable:false,
                            format:'d-m-Y',
                            id: 'id_lpo_tgl_akhir',
                            anchor: '90%',
                            value: ''
                        }]
                    } ]
            } ]
        }]
    } ]
}
// -------- Fieldset Tanggal dan Sort Order ------

// -------- Fieldset Kategori 1-4 ----------------
var formLPOfieldsetKategori = {
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
                        comboLPOKategori1,
                        comboLPOKategori2,
                        comboLPOKategori3,
                        comboLPOKategori4
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
                                id: 'id_lpo_kategori1_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Kategori 2',
                                name: 'kategori2_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_lpo_kategori2_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Kategori 3',
                                name: 'kategori3_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_lpo_kategori3_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Kategori 4',
                                name: 'kategori4_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_lpo_kategori4_sel',
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
var formLPOfieldsetSupplier = {
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
                        comboLPOStatus,
                        comboLPOSupplier,
                        comboLPOPurchaseOrder,
                        comboLPOProduk
                    ]
                },
                    {
                        columnWidth: .5,
                        layout: 'form',
                        border: false,
                        labelWidth: 100,
                        defaults: { labelSeparator: ''},
                        items:[
                            comboLPOKonsinyasi,
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Kd. Supplier',
                                name: 'kd_supplier_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_lpo_supplier_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'No. PO',
                                name: 'no_po_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_lpo_po_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Kd. Produk',
                                name: 'kd_produk_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_lpo_produk_sel',
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
    id: 'rpt_purchase_order',
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
            items: [formLPOfieldsetTanggal, formLPOfieldsetKategori, formLPOfieldsetSupplier],
            buttons: [
                {
                    text: 'Print',
                    formBind:true,
                    handler: function () {
                        Ext.getCmp('rpt_purchase_order').getForm().submit({
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

                                clearform('rpt_purchase_order');
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
                    handler: function(){clearform('rpt_purchase_order');}
                }
            ]
        }]
    }
    ]
});


</script>
