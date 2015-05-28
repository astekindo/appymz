<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
// -------- COMBOBOX Supplier --------------------
var smgridRSPGSupplier= new Ext.grid.CheckboxSelectionModel();

var strCbRSPGSupplier = new Ext.data.ArrayStore({
    fields: ['kd_supplier'],
    data : []
});

var strgridRSPGSupplier = new Ext.data.Store({
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

var searchgridRSPGSupplier = new Ext.app.SearchField({
    store: strgridRSPGSupplier,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridRSPGSupplier'
});

var gridRSPGSupplier = new Ext.grid.GridPanel({
    store: strgridRSPGSupplier,
    stripeRows: true,
    frame: true,
    border:true,
    sm: smgridRSPGSupplier,
    columns: [
        smgridRSPGSupplier,
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
        items: [searchgridRSPGSupplier]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridRSPGSupplier,
        displayInfo: true
    })
});

var menuRSPGSupplier = new Ext.menu.Menu();

menuRSPGSupplier.add(new Ext.Panel({
    title: 'Pilih Supplier',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridRSPGSupplier],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridRSPGSupplier.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_rspg_kd_supplier_sel','kd_supplier',sel);
                    sm.clearSelections();
                }
                menuRSPGSupplier.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridRSPGSupplier.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_rspg_kd_supplier_sel','kd_supplier',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_rspg_kd_supplier_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuRSPGSupplier.hide(); }
        }]
}));

Ext.ux.TwinComboRSPGSupplier = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function(){
        //load store grid
        strgridRSPGSupplier.load();
        menuRSPGSupplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuRSPGSupplier.on('hide', function(){
    var sf = Ext.getCmp('id_searchgridRSPGSupplier').getValue();
    if( sf != ''){
        Ext.getCmp('id_searchgridRSPGSupplier').setValue('');
        searchgridRSPGSupplier.onTrigger2Click();
    }
});

var comboRSPGSupplier = new Ext.ux.TwinComboRSPGSupplier({
    fieldLabel: 'Supplier',
    id: 'id_cbRSPGSupplier',
    store: strCbRSPGSupplier,
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

var smgridRSPGKategori1 = new Ext.grid.CheckboxSelectionModel();

var strRSPGKategori1 = new Ext.data.ArrayStore({
    fields: ['kd_kategori1', 'nama_kategori1'],
    data: []
});

// GRID PANEL TWIN COMBOBOX kategori1 Data Store
var strgridRSPGKategori1 = new Ext.data.Store({
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

strgridRSPGKategori1.on('load', function(){
    Ext.getCmp('id_searchgridRSPGKategori1').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX kategori1
var searchgridRSPGKategori1 = new Ext.app.SearchField({
    store: strgridRSPGKategori1,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridRSPGKategori1'
});

// GRID PANEL TWIN COMBOBOX kategori1
var gridRSPGKategori1 = new Ext.grid.GridPanel({
    store: strgridRSPGKategori1,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridRSPGKategori1,
    columns: [
        smgridRSPGKategori1,
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
        items: [searchgridRSPGKategori1]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridRSPGKategori1,
        displayInfo: true
    })
});

var menuRSPGKategori1 = new Ext.menu.Menu();

menuRSPGKategori1.add(new Ext.Panel({
    title: 'Pilih Kategori 1',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridRSPGKategori1],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridRSPGKategori1.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_rspg_kategori1_sel','kd_kategori1',sel);
                    sm.clearSelections();
                }
                menuRSPGKategori1.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridRSPGKategori1.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_rspg_kategori1_sel','kd_kategori1',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_rspg_kategori1_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuRSPGKategori1.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX kategori1
Ext.ux.TwinComboRSPGKategori1 = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        //load store grid
        strgridRSPGKategori1.removeAll();
        strgridRSPGKategori1.load();
        menuRSPGKategori1.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuRSPGKategori1.on('hide', function () {
    var sf = Ext.getCmp('id_searchgridRSPGKategori1').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgridRSPGKategori1').setValue('');
        searchgridRSPGKategori1.onTrigger2Click();
    }
});

// TWIN COMBOBOX kategori1
var comboRSPGKategori1 = new Ext.ux.TwinComboRSPGKategori1({
    fieldLabel: 'Kategori 1',
    id: 'id_cbRSPGKategori1',
    store: strRSPGKategori1,
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

var smgridRSPGKategori2 = new Ext.grid.CheckboxSelectionModel();

var strRSPGKategori2 = new Ext.data.ArrayStore({
    fields: ['kd_kategori2', 'nama_kategori2'],
    data: []
});

// GRID PANEL TWIN COMBOBOX kategori2 Data Store
var strgridRSPGKategori2 = new Ext.data.Store({
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

strgridRSPGKategori2.on('load', function(){
    strgridRSPGKategori2.setBaseParam('kategori1', Ext.getCmp('id_rspg_kategori1_sel').getValue());
    Ext.getCmp('id_searchgridRSPGKategori2').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX kategori2
var searchgridRSPGKategori2 = new Ext.app.SearchField({
    store: strgridRSPGKategori2,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridRSPGKategori2'
});

// GRID PANEL TWIN COMBOBOX kategori2
var gridRSPGKategori2 = new Ext.grid.GridPanel({
    store: strgridRSPGKategori2,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridRSPGKategori2,
    columns: [
        smgridRSPGKategori2,
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
        items: [searchgridRSPGKategori2]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridRSPGKategori2,
        displayInfo: true
    })
});

var menuRSPGKategori2 = new Ext.menu.Menu();

menuRSPGKategori2.add(new Ext.Panel({
    title: 'Pilih Kategori 2',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridRSPGKategori2],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridRSPGKategori2.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_rspg_kategori2_sel','kd_kategori2',sel);
                    sm.clearSelections();
                }
                menuRSPGKategori2.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridRSPGKategori2.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_rspg_kategori2_sel','kd_kategori2',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_rspg_kategori2_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuRSPGKategori2.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX kategori2
Ext.ux.TwinComboRSPGKategori2 = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridRSPGKategori2.removeAll();
        var kategori1 = Ext.getCmp('id_rspg_kategori1_sel').getValue();
        if(kategori1.length>0) {
            strgridRSPGKategori2.load({params: {kategori1: kategori1}});
        } else {
            strgridRSPGKategori2.load();
        }
        menuRSPGKategori2.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuRSPGKategori2.on('hide', function () {
    var sf = Ext.getCmp('id_searchgridRSPGKategori2').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgridRSPGKategori2').setValue('');
        searchgridRSPGKategori2.onTrigger2Click();
    }
});

// TWIN COMBOBOX kategori2
var comboRSPGKategori2 = new Ext.ux.TwinComboRSPGKategori2({
    fieldLabel: 'Kategori 2',
    id: 'id_cbRSPGKategori2',
    store: strRSPGKategori2,
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

var smgridRSPGKategori3 = new Ext.grid.CheckboxSelectionModel();

var strRSPGKategori3 = new Ext.data.ArrayStore({
    fields: ['kd_kategori3', 'nama_kategori3'],
    data: []
});

// GRID PANEL TWIN COMBOBOX kategori3 Data Store
var strgridRSPGKategori3 = new Ext.data.Store({
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

strgridRSPGKategori3.on('load', function(){
    strgridRSPGKategori3.setBaseParam('kategori1', Ext.getCmp('id_rspg_kategori1_sel').getValue());
    strgridRSPGKategori3.setBaseParam('kategori2', Ext.getCmp('id_rspg_kategori2_sel').getValue());
    Ext.getCmp('id_searchgridRSPGKategori3').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX kategori3
var searchgridRSPGKategori3 = new Ext.app.SearchField({
    store: strgridRSPGKategori3,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridRSPGKategori3'
});

// GRID PANEL TWIN COMBOBOX kategori3
var gridRSPGKategori3 = new Ext.grid.GridPanel({
    store: strgridRSPGKategori3,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridRSPGKategori3,
    columns: [
        smgridRSPGKategori3,
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
        items: [searchgridRSPGKategori3]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridRSPGKategori3,
        displayInfo: true
    })
});

var menuRSPGKategori3 = new Ext.menu.Menu();

menuRSPGKategori3.add(new Ext.Panel({
    title: 'Pilih Kategori 3',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridRSPGKategori3],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridRSPGKategori3.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_rspg_kategori3_sel','kd_kategori3',sel);
                    sm.clearSelections();
                }
                menuRSPGKategori3.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridRSPGKategori3.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_rspg_kategori3_sel','kd_kategori3',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_rspg_kategori3_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuRSPGKategori3.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX kategori3
Ext.ux.TwinComboRSPGKategori3 = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridRSPGKategori3.removeAll();
        //load store grid
        var kategori1 = Ext.getCmp('id_rspg_kategori1_sel').getValue();
        var kategori2 = Ext.getCmp('id_rspg_kategori2_sel').getValue();
        if(kategori1.length > 0 || kategori2.length > 0 ) {
            strgridRSPGKategori3.load({params: {
                kategori1: kategori1,
                kategori2: kategori2
            }});
        } else {
            strgridRSPGKategori3.load();
        }
        menuRSPGKategori3.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuRSPGKategori3.on('hide', function () {
    var sf = Ext.getCmp('id_searchgridRSPGKategori3').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgridRSPGKategori3').setValue('');
        searchgridRSPGKategori3.onTrigger2Click();
    }
});

// TWIN COMBOBOX kategori3
var comboRSPGKategori3 = new Ext.ux.TwinComboRSPGKategori3({
    fieldLabel: 'Kategori 3',
    id: 'id_cbRSPGKategori3',
    store: strRSPGKategori3,
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

var smgridRSPGKategori4 = new Ext.grid.CheckboxSelectionModel();

var strRSPGKategori4 = new Ext.data.ArrayStore({
    fields: ['kd_kategori4', 'nama_kategori4'],
    data: []
});

// GRID PANEL TWIN COMBOBOX kategori4 Data Store
var strgridRSPGKategori4 = new Ext.data.Store({
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

strgridRSPGKategori4.on('load', function(){
    strgridRSPGKategori4.setBaseParam('kategori1', Ext.getCmp('id_rspg_kategori1_sel').getValue());
    strgridRSPGKategori4.setBaseParam('kategori2', Ext.getCmp('id_rspg_kategori2_sel').getValue());
    strgridRSPGKategori4.setBaseParam('kategori3', Ext.getCmp('id_rspg_kategori3_sel').getValue());
    Ext.getCmp('id_searchgridRSPGKategori4').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX kategori4
var searchgridRSPGKategori4 = new Ext.app.SearchField({
    store: strgridRSPGKategori4,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridRSPGKategori4'
});

// GRID PANEL TWIN COMBOBOX kategori4
var gridRSPGKategori4 = new Ext.grid.GridPanel({
    store: strgridRSPGKategori4,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridRSPGKategori4,
    columns: [
        smgridRSPGKategori4,
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
        items: [searchgridRSPGKategori4]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridRSPGKategori4,
        displayInfo: true
    })
});

var menuRSPGKategori4 = new Ext.menu.Menu();

menuRSPGKategori4.add(new Ext.Panel({
    title: 'Pilih Kategori 4',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridRSPGKategori4],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridRSPGKategori4.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_rspg_kategori4_sel','kd_kategori4',sel);
                    sm.clearSelections();
                }
                menuRSPGKategori4.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridRSPGKategori4.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_rspg_kategori4_sel','kd_kategori4',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_rspg_kategori4_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuRSPGKategori4.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX kategori4
Ext.ux.TwinComboRSPGKategori4 = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridRSPGKategori4.removeAll();
        var kategori1 = Ext.getCmp('id_rspg_kategori1_sel').getValue();
        var kategori2 = Ext.getCmp('id_rspg_kategori2_sel').getValue();
        var kategori3 = Ext.getCmp('id_rspg_kategori3_sel').getValue();
        if(kategori1.length > 0 || kategori2.length > 0 || kategori3.length > 0 ) {
            strgridRSPGKategori4.load({params: {
                kategori1: kategori1,
                kategori2: kategori2,
                kategori3: kategori3
            }});
        } else {
            strgridRSPGKategori4.load();
        }
        menuRSPGKategori4.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuRSPGKategori4.on('hide', function () {
    var sf = Ext.getCmp('id_searchgridRSPGKategori4').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgridRSPGKategori4').setValue('');
        searchgridRSPGKategori4.onTrigger2Click();
    }
});

// TWIN COMBOBOX kategori4
var comboRSPGKategori4 = new Ext.ux.TwinComboRSPGKategori4({
    fieldLabel: 'Kategori 4',
    id: 'id_cbRSPGKategori4',
    store: strRSPGKategori4,
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

var smgridRSPGUkuran = new Ext.grid.CheckboxSelectionModel();

var strRSPGUkuran = new Ext.data.ArrayStore({
    fields: ['kd_ukuran', 'nama_ukuran'],
    data: []
});

// GRID PANEL TWIN COMBOBOX Ukuran Data Store
var strgridRSPGUkuran = new Ext.data.Store({
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

strgridRSPGUkuran.on('load', function(){
    Ext.getCmp('id_searchgridRSPGUkuran').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX Ukuran
var searchgridRSPGUkuran = new Ext.app.SearchField({
    store: strgridRSPGUkuran,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridRSPGUkuran'
});

// GRID PANEL TWIN COMBOBOX Ukuran
var gridRSPGUkuran = new Ext.grid.GridPanel({
    store: strgridRSPGUkuran,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridRSPGUkuran,
    columns: [
        smgridRSPGUkuran,
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
        items: [searchgridRSPGUkuran]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridRSPGUkuran,
        displayInfo: true
    })
});

var menuRSPGUkuran = new Ext.menu.Menu();

menuRSPGUkuran.add(new Ext.Panel({
    title: 'Pilih Ukuran',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridRSPGUkuran],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridRSPGUkuran.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_rspg_ukuran_sel','kd_ukuran',sel);
                    sm.clearSelections();
                }
                menuRSPGUkuran.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridRSPGUkuran.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_rspg_ukuran_sel','kd_ukuran',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_rspg_ukuran_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuRSPGUkuran.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX Ukuran
Ext.ux.TwinComboRSPGUkuran = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        //load store grid
        strgridRSPGUkuran.removeAll();
        strgridRSPGUkuran.load();
        menuRSPGUkuran.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuRSPGUkuran.on('hide', function () {
    var sf = Ext.getCmp('id_searchgridRSPGUkuran').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgridRSPGUkuran').setValue('');
        searchgridRSPGUkuran.onTrigger2Click();
    }
});

// TWIN COMBOBOX Ukuran
var comboRSPGUkuran = new Ext.ux.TwinComboRSPGUkuran({
    fieldLabel: 'Ukuran',
    id: 'id_cbRSPGUkuran',
    store: strRSPGUkuran,
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

var smgridRSPGSatuan = new Ext.grid.CheckboxSelectionModel();

var strRSPGSatuan = new Ext.data.ArrayStore({
    fields: ['kd_satuan', 'nm_satuan'],
    data: []
});

// GRID PANEL TWIN COMBOBOX Satuan Data Store
var strgridRSPGSatuan = new Ext.data.Store({
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

strgridRSPGSatuan.on('load', function(){
    Ext.getCmp('id_searchgridRSPGSatuan').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX Satuan
var searchgridRSPGSatuan = new Ext.app.SearchField({
    store: strgridRSPGSatuan,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridRSPGSatuan'
});

// GRID PANEL TWIN COMBOBOX Satuan
var gridRSPGSatuan = new Ext.grid.GridPanel({
    store: strgridRSPGSatuan,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridRSPGSatuan,
    columns: [
        smgridRSPGSatuan,
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
        items: [searchgridRSPGSatuan]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridRSPGSatuan,
        displayInfo: true
    })
});

var menuRSPGSatuan = new Ext.menu.Menu();

menuRSPGSatuan.add(new Ext.Panel({
    title: 'Pilih Satuan',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridRSPGSatuan],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridRSPGSatuan.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_rspg_satuan_sel','kd_satuan',sel);
                    sm.clearSelections();
                }
                menuRSPGSatuan.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridRSPGSatuan.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_rspg_satuan_sel','kd_satuan',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_rspg_satuan_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuRSPGSatuan.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX Satuan
Ext.ux.TwinComboRSPGSatuan = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        //load store grid
        strgridRSPGSatuan.removeAll();
        strgridRSPGSatuan.load();
        menuRSPGSatuan.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuRSPGSatuan.on('hide', function () {
    var sf = Ext.getCmp('id_searchgridRSPGSatuan').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgridRSPGSatuan').setValue('');
        searchgridRSPGSatuan.onTrigger2Click();
    }
});

// TWIN COMBOBOX Satuan
var comboRSPGSatuan = new Ext.ux.TwinComboRSPGSatuan({
    fieldLabel: 'Satuan',
    id: 'id_cbRSPGSatuan',
    store: strRSPGSatuan,
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

var smgridRSPGProduk = new Ext.grid.CheckboxSelectionModel();

var strRSPGProduk = new Ext.data.ArrayStore({
    fields: ['kd_produk', 'nama_produk'],
    data: []
});

// GRID PANEL TWIN COMBOBOX Produk Data Store
var strgridRSPGProduk = new Ext.data.Store({
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

strgridRSPGProduk.on('load', function(){
    strgridRSPGProduk.setBaseParam('kategori1', Ext.getCmp('id_rspg_kategori1_sel').getValue());
    strgridRSPGProduk.setBaseParam('kategori2', Ext.getCmp('id_rspg_kategori2_sel').getValue());
    strgridRSPGProduk.setBaseParam('kategori3', Ext.getCmp('id_rspg_kategori3_sel').getValue());
    strgridRSPGProduk.setBaseParam('kategori4', Ext.getCmp('id_rspg_kategori4_sel').getValue());
    strgridRSPGProduk.setBaseParam('ukuran', Ext.getCmp('id_rspg_ukuran_sel').getValue());
    strgridRSPGProduk.setBaseParam('satuan', Ext.getCmp('id_rspg_satuan_sel').getValue());
    Ext.getCmp('id_searchgridRSPGProduk').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX Produk
var searchgridRSPGProduk = new Ext.app.SearchField({
    store: strgridRSPGProduk,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridRSPGProduk'
});

// GRID PANEL TWIN COMBOBOX Produk
var gridRSPGProduk = new Ext.grid.GridPanel({
    store: strgridRSPGProduk,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridRSPGProduk,
    columns: [
        smgridRSPGProduk,
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
        items: [searchgridRSPGProduk]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridRSPGProduk,
        displayInfo: true
    })
});

var menuRSPGProduk = new Ext.menu.Menu();

menuRSPGProduk.add(new Ext.Panel({
    title: 'Pilih Produk',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridRSPGProduk],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridRSPGProduk.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_rspg_produk_sel','kd_produk',sel);
                    sm.clearSelections();
                }
                menuRSPGProduk.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridRSPGProduk.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_rspg_produk_sel','kd_produk',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_rspg_produk_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuRSPGProduk.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX Produk
Ext.ux.TwinComboRSPGProduk = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridRSPGProduk.removeAll();
        var kategori1   = Ext.getCmp('id_rspg_kategori1_sel').getValue();
        var kategori2   = Ext.getCmp('id_rspg_kategori2_sel').getValue();
        var kategori3   = Ext.getCmp('id_rspg_kategori3_sel').getValue();
        var kategori4   = Ext.getCmp('id_rspg_kategori4_sel').getValue();
        var ukuran      = Ext.getCmp('id_rspg_ukuran_sel').getValue();
        var satuan      = Ext.getCmp('id_rspg_satuan_sel').getValue();
        strgridRSPGProduk.load({params: {
            kategori1: kategori1,
            kategori2: kategori2,
            kategori3: kategori3,
            kategori4: kategori4,
            ukuran: ukuran,
            satuan: satuan
        }});
        menuRSPGProduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuRSPGProduk.on('hide', function () {
    var sf = Ext.getCmp('id_searchgridRSPGProduk').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgridRSPGProduk').setValue('');
        searchgridRSPGProduk.onTrigger2Click();
    }
});

// TWIN COMBOBOX Produk
var comboRSPGProduk = new Ext.ux.TwinComboRSPGProduk({
    fieldLabel: 'Produk',
    id: 'id_cbRSPGProduk',
    store: strRSPGProduk,
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
var strCbRSPGLokasi = new Ext.data.ArrayStore({
    fields: ['kd_lokasi', 'nama_lokasi'],
    data : []
});

var strGridRSPGLokasi = new Ext.data.Store({
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

var searchGridRSPGLokasi = new Ext.app.SearchField({
    store: strGridRSPGLokasi,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchGridRSPGLokasi'
});
var gridRSPGLokasi = new Ext.grid.GridPanel({
    store: strGridRSPGLokasi,
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
        items: [searchGridRSPGLokasi]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strGridRSPGLokasi,
        displayInfo: true
    }),
    listeners: {
        'rowdblclick': function(){
            var sm = this.getSelectionModel();
            var sel = sm.getSelections();
            if (sel.length > 0) {
                Ext.getCmp('id_comboRSPGLokasi').setValue(sel[0].get('kd_lokasi'));
                Ext.getCmp('id_rspg_kd_lokasi_sel').setValue(sel[0].get('kd_lokasi'));
                Ext.getCmp('id_rspg_nama_lokasi_sel').setValue(sel[0].get('nama_lokasi'));
                menuRSPGLokasi.hide();
            }
        }
    }
});

var menuRSPGLokasi = new Ext.menu.Menu();
menuRSPGLokasi.add(new Ext.Panel({
    title: 'Pilih Lokasi',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 250,
    closeAction: 'hide',
    plain: true,
    items: [gridRSPGLokasi],
    buttons: [{
        text: 'Close',
        handler: function(){
            menuRSPGLokasi.hide();
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
        strGridRSPGLokasi.load();
        menuRSPGLokasi.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuRSPGLokasi.on('hide', function(){
    var sf = Ext.getCmp('id_searchGridRSPGLokasi').getValue();
    if( sf != ''){
        Ext.getCmp('id_searchGridRSPGLokasi').setValue('');
        searchGridRSPGLokasi.onTrigger2Click();
    }
});
//end twin lokasi
var comboRSPGLokasi = new Ext.ux.TwinComb_mlo_asal({
    fieldLabel: 'Lokasi Asal <span class="asterix">*</span>',
    id: 'id_comboRSPGLokasi',
    store: strCbRSPGLokasi,
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
var dsRSPGPeruntukan=[['D',"Distribusi"],['B',"Bazar"],['S',"Supermarket"]];

var strRSPGPeruntukan = new Ext.data.ArrayStore({
    fields: [{name: 'kd_peruntukan'},{name: 'nama_peruntukan'}],
    data: dsRSPGPeruntukan
});

var comboRSPGPeruntukan = new Ext.form.ComboBox({
    fieldLabel: 'Peruntukkan',
    id: 'id_cb_rspg_peruntukan',
    name:'peruntukan',
    store: dsRSPGPeruntukan,
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
var strRSPGKonsinyasi = new Ext.data.ArrayStore({
    fields: [{name: 'kd_kons'},{name: 'nama_kons'}],
    data: [['A',"Semua"], ['Y',"Hanya Konsinyasi"], ['T',"Bukan Konsinyasi"]]
});

var comboRSPGKonsinyasi = new Ext.form.ComboBox({
    fieldLabel: 'Jenis',
    id: 'id_cb_rspg_konsinyasi',
    name:'konsinyasi',
    store: strRSPGKonsinyasi,
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
var strRSPGStatus = new Ext.data.ArrayStore({
    fields: [{name: 'kd_status'},{name: 'nama_status'}],
    data: [['A',"Semua"], ['Y',"Aktif"], ['T',"Tidak aktif"]]
});

var comboRSPGStatus = new Ext.form.ComboBox({
    fieldLabel: 'Status',
    id: 'id_cb_rspg_status',
    name:'status',
    store: strRSPGStatus,
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
var checkboxRSPGSort = new Ext.form.Checkbox({
    xtype: 'checkbox',
    fieldLabel: 'Sort Order',
    boxLabel: 'Descending',
    name: 'sort_order',
    id: 'id_rspg_sort',
    checked: true,
    inputValue: '1',
    autoLoad: true
});
//-------- CHECKBOX SORT ORDER ----------------

// -------- MAIN FORM -------------------------
var headerRSPGtanggal = {
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
                        id: 'id_rspg_tgl_awal',
                        anchor: '90%',
                        value: ''
                    },
                        comboRSPGLokasi,
                        comboRSPGSupplier,
                        comboRSPGKategori1,
                        comboRSPGKategori2,
                        comboRSPGKategori3,
                        comboRSPGKategori4,
                        comboRSPGUkuran,
                        comboRSPGSatuan,
                        comboRSPGProduk,
                        comboRSPGPeruntukan,
                        comboRSPGKonsinyasi
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
                                id: 'id_rspg_tgl_akhir',
                                anchor: '90%',
                                value: ''
                            },
                            {
                                xtype: 'hidden',
                                name: 'kd_lokasi',
                                id: 'id_rspg_kd_lokasi_sel'
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Nama Lokasi',
                                name: 'nama_lokasi',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_rspg_nama_lokasi_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Kd. Supplier',
                                name: 'kd_supplier_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_rspg_kd_supplier_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Kd. Kategori 1',
                                name: 'kd_kategori1_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_rspg_kategori1_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Kd. Kategori 2',
                                name: 'kd_kategori2_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_rspg_kategori2_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Kd. Kategori 3',
                                name: 'kd_kategori3_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_rspg_kategori3_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Kd. Kategori 4',
                                name: 'kd_kategori4_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_rspg_kategori4_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Ukuran',
                                name: 'kd_ukuran_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_rspg_ukuran_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Satuan',
                                name: 'kd_satuan_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_rspg_satuan_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Kode Produk',
                                name: 'kd_produk_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_rspg_produk_sel',
                                anchor: '90%',
                                value:''
                            },
                            comboRSPGStatus,
                            checkboxRSPGSort
                        ]
                    }]
            }]
        }]
    }]
}


var laporanKartuStok = new Ext.FormPanel({
    id: 'rpt_rekap_stok_pergudang',
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
            items: [headerRSPGtanggal],
            buttons: [
                {
                    text: 'Print',
                    formBind:true,
                    handler: function () {
                        Ext.getCmp('rpt_rekap_stok_pergudang').getForm().submit({
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

                                clearform('rpt_rekap_stok_pergudang');
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
                    handler: function(){clearform('rpt_rekap_stok_pergudang');}
                }
            ]
        }]
    }
    ]
});

</script>