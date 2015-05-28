<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
// -------- COMBOBOX USER --------------------
    var smgridLP1User= new Ext.grid.CheckboxSelectionModel();

    var strCbLP1User = new Ext.data.ArrayStore({
        fields: ['kd_user'],
        data : []
    });

    var strgridLP1User = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_user', 'username'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("report/get_user") ?>',
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

    var searchgridLP1User = new Ext.app.SearchField({
        store: strgridLP1User,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridLP1user'
    });

    var gridLP1User = new Ext.grid.GridPanel({
        store: strgridLP1User,
        stripeRows: true,
        frame: true,
        border:true,
        sm: smgridLP1User,
        columns: [
            smgridLP1User,
            {
                header: 'ID User',
                dataIndex: 'kd_user',
                width: 80,
                sortable: true

            },
            {
                header: 'Nama User',
                dataIndex: 'username',
                width: 300,
                sortable: true
            }
        ],
        tbar: new Ext.Toolbar({
            items: [searchgridLP1User]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridLP1User,
            displayInfo: true
        })
    });

    var menuLP1User = new Ext.menu.Menu();

    menuLP1User.add(new Ext.Panel({
        title: 'Pilih User',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridLP1User],
        buttons: [
            {
                text: 'Done',
                handler: function () {
                    var sm  = gridLP1User.getSelectionModel();
                    var sel = sm.getSelections();
                    if(sel.length > 0) {
                        addSelectedValue('id_lp1_user_sel','username',sel);
                        sm.clearSelections();
                    }
                    menuLP1User.hide();
                }
            },
            {
                text: 'Add Selected',
                handler: function(){
                    var sm  = gridLP1User.getSelectionModel();
                    var sel = sm.getSelections();
                    if(sel.length > 0) {
                        addSelectedValue('id_lp1_user_sel','username',sel);
                        sm.clearSelections();
                    }
                }
            },
            {
                text: 'Reset',
                handler: function(){
                    Ext.getCmp('id_lp1_user_sel').setValue('');
                }
            },
            {
                text: 'Close',
                handler: function(){ menuLP1User.hide(); }
            }]
    }));

    Ext.ux.TwinComboLP1User = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgridLP1User.load();
            menuLP1User.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menuLP1User.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridLP1user').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgridLP1user').setValue('');
            searchgridLP1User.onTrigger2Click();
        }
    });

    var comboLP1User = new Ext.ux.TwinComboLP1User({
        fieldLabel: 'User ID',
        id: 'id_cblp1user',
        store: strCbLP1User,
        mode: 'local',
        valueField: 'kd_user',
        displayField: 'username',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_user',
        emptyText: 'Pilih User'
    });

// -------- COMBOBOX USER --------------------


// -------- COMBOBOX SHIFT -------------------

var smgridLP1Shift= new Ext.grid.CheckboxSelectionModel();

var strCbLP1Shift = new Ext.data.ArrayStore({
    fields: ['no_open_saldo'],
    data : []
});

var strgridLP1Shift = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: ['no_open_saldo', 'username'],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("report/get_shift") ?>',
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

var searchgridLP1Shift = new Ext.app.SearchField({
    store: strgridLP1Shift,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridLP1shift'
});

var gridLP1Shift = new Ext.grid.GridPanel({
    store: strgridLP1Shift,
    stripeRows: true,
    frame: true,
    border:true,
    sm: smgridLP1Shift,
    columns: [
        smgridLP1Shift,
        {
            header: 'Kode Shift',
            dataIndex: 'no_open_saldo',
            width: 150,
            sortable: true

        },
        {
            header: 'Nama Shift',
            dataIndex: 'username',
            width: 250,
            sortable: true
        }
    ],
    tbar: new Ext.Toolbar({
        items: [searchgridLP1Shift]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridLP1Shift,
        displayInfo: true
    })
});

Ext.ux.TwinComboLP1Shift = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function(){
        var users = Ext.getCmp('id_lp1_user_sel').getValue();
        if(users.length>0) {
            strgridLP1Shift.load({params: {users: users}})
        } else {
            strgridLP1Shift.load();
        }
        menuLP1Shift.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

var menuLP1Shift = new Ext.menu.Menu();

menuLP1Shift.add(new Ext.Panel({
    title: 'Pilih Shift',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridLP1Shift],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridLP1Shift.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lp1_shift_sel','no_open_saldo',sel);
                    sm.clearSelections();
                }
                menuLP1Shift.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridLP1Shift.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lp1_shift_sel','no_open_saldo',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lp1_shift_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuLP1Shift.hide(); }
        }
    ]
}));

menuLP1Shift.on('hide', function(){
    var sf = Ext.getCmp('id_searchgridLP1shift').getValue();
    if( sf != ''){
        Ext.getCmp('id_searchgridLP1shift').setValue('');
        searchgridLP1Shift.onTrigger2Click();
    }
});

var comboLP1Shift = new Ext.ux.TwinComboLP1Shift({
    id: 'id_cblp1shift',
    fieldLabel: 'Shift',
    store: strCbLP1Shift,
    mode: 'local',
    anchor: '90%',
    valueField: 'no_open_saldo',
    displayField: 'no_open_saldo',
    typeAhead: true,
    triggerAction: 'all',
    allowBlank: true ,
    editable: false,
    hiddenName: 'no_open_saldo',
    emptyText: 'Pilih Shift'
});
// -------- COMBOBOX SHIFT --------------------

// -------- COMBOBOX MEMBER -------------------
var smgridLP1Member= new Ext.grid.CheckboxSelectionModel();

var strCbLP1Member = new Ext.data.ArrayStore({
    fields: ['kd_member'],
    data : []
});

var strgridLP1Member = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: ['kd_member', 'nmmember'],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("report/get_member") ?>',
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

var searchgridLP1member = new Ext.app.SearchField({
    store: strgridLP1Member,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridLP1member'
});

var gridLP1Member = new Ext.grid.GridPanel({
    store: strgridLP1Member,
    stripeRows: true,
    frame: true,
    border:true,
    sm: smgridLP1Member,
    columns: [
        smgridLP1Member,
        {
            header: 'ID Member',
            dataIndex: 'kd_member',
            width: 80,
            sortable: true

        },
        {
            header: 'Nama Member',
            dataIndex: 'nmmember',
            width: 300,
            sortable: true
        }
    ],
    tbar: new Ext.Toolbar({
        items: [searchgridLP1member]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridLP1Member,
        displayInfo: true
    })
});

Ext.ux.TwinComboLP1Member = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function(){
        //load store grid
        strgridLP1Member.load();
        menuLP1Member.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

var menuLP1Member = new Ext.menu.Menu();

menuLP1Member.add(new Ext.Panel({
    title: 'Pilih Member',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridLP1Member],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridLP1Member.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lp1_member_sel','kd_member',sel);
                    sm.clearSelections();
                }
                menuLP1Member.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridLP1Member.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lp1_member_sel','kd_member',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lp1_member_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuLP1Member.hide(); }
        }
    ]
}));

menuLP1Member.on('hide', function(){
    var sf = Ext.getCmp('id_searchgridLP1member').getValue();
    if( sf != ''){
        Ext.getCmp('id_searchgridLP1member').setValue('');
        searchgridLP1member.onTrigger2Click();
    }
});


var comboLP1Member = new Ext.ux.TwinComboLP1Member({
    fieldLabel: 'Kode Member',
    id: 'id_cblp1member',
    store: strCbLP1Member,
    mode: 'local',
    valueField: 'kd_member',
    displayField: 'nmmember',
    typeAhead: true,
    triggerAction: 'all',
    // allowBlank: false,
    editable: false,
    anchor: '90%',
    hiddenName: 'kd_member',
    emptyText: 'Pilih member'
});

// -------- COMBOBOX MEMBER -------------------

//-------- COMBOBOX KATEGORI1 ---------------------

var smgridLP1Kategori1 = new Ext.grid.CheckboxSelectionModel();

var strLP1Kategori1 = new Ext.data.ArrayStore({
    fields: ['kd_kategori1', 'nama_kategori1'],
    data: []
});

// GRID PANEL TWIN COMBOBOX kategori1 Data Store
var strgridLP1Kategori1 = new Ext.data.Store({
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

strgridLP1Kategori1.on('load', function(){
    Ext.getCmp('id_searchgridLP1Kategori1').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX kategori1
var searchgridLP1Kategori1 = new Ext.app.SearchField({
    store: strgridLP1Kategori1,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridLP1Kategori1'
});

// GRID PANEL TWIN COMBOBOX kategori1
var gridLP1Kategori1 = new Ext.grid.GridPanel({
    store: strgridLP1Kategori1,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridLP1Kategori1,
    columns: [
        smgridLP1Kategori1,
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
        items: [searchgridLP1Kategori1]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridLP1Kategori1,
        displayInfo: true
    })
});

var menuLP1Kategori1 = new Ext.menu.Menu();

menuLP1Kategori1.add(new Ext.Panel({
    title: 'Pilih Kategori 1',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridLP1Kategori1],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridLP1Kategori1.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lp1_kategori1_sel','kd_kategori1',sel);
                    sm.clearSelections();
                }
                menuLP1Kategori1.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridLP1Kategori1.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lp1_kategori1_sel','kd_kategori1',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lp1_kategori1_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuLP1Kategori1.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX kategori1
Ext.ux.TwinComboLP1Kategori1 = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        //load store grid
        strgridLP1Kategori1.removeAll();
        strgridLP1Kategori1.load();
        menuLP1Kategori1.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuLP1Kategori1.on('hide', function () {
    var sf = Ext.getCmp('id_searchgridLP1Kategori1').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgridLP1Kategori1').setValue('');
        searchgridLP1Kategori1.onTrigger2Click();
    }
});

// TWIN COMBOBOX kategori1
var comboLP1Kategori1 = new Ext.ux.TwinComboLP1Kategori1({
    fieldLabel: 'Kategori 1',
    id: 'id_cbLP1Kategori1',
    store: strLP1Kategori1,
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

var smgridLP1Kategori2 = new Ext.grid.CheckboxSelectionModel();

var strLP1Kategori2 = new Ext.data.ArrayStore({
    fields: ['kd_kategori2', 'nama_kategori2'],
    data: []
});

// GRID PANEL TWIN COMBOBOX kategori2 Data Store
var strgridLP1Kategori2 = new Ext.data.Store({
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

strgridLP1Kategori2.on('load', function(){
    strgridLP1Kategori2.setBaseParam('kategori1', Ext.getCmp('id_lp1_kategori1_sel').getValue());
    Ext.getCmp('id_searchgridLP1Kategori2').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX kategori2
var searchgridLP1Kategori2 = new Ext.app.SearchField({
    store: strgridLP1Kategori2,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridLP1Kategori2'
});

// GRID PANEL TWIN COMBOBOX kategori2
var gridLP1Kategori2 = new Ext.grid.GridPanel({
    store: strgridLP1Kategori2,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridLP1Kategori2,
    columns: [
        smgridLP1Kategori2,
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
        items: [searchgridLP1Kategori2]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridLP1Kategori2,
        displayInfo: true
    })
});

var menuLP1Kategori2 = new Ext.menu.Menu();

menuLP1Kategori2.add(new Ext.Panel({
    title: 'Pilih Kategori 2',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridLP1Kategori2],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridLP1Kategori2.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lp1_kategori2_sel','kd_kategori2',sel);
                    sm.clearSelections();
                }
                menuLP1Kategori2.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridLP1Kategori2.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lp1_kategori2_sel','kd_kategori2',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lp1_kategori2_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuLP1Kategori2.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX kategori2
Ext.ux.TwinComboLP1Kategori2 = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridLP1Kategori2.removeAll();
        var kategori1 = Ext.getCmp('id_lp1_kategori1_sel').getValue();
        if(kategori1.length>0) {
            strgridLP1Kategori2.load({params: {kategori1: kategori1}});
        } else {
            strgridLP1Kategori2.load();
        }
        menuLP1Kategori2.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuLP1Kategori2.on('hide', function () {
    var sf = Ext.getCmp('id_searchgridLP1Kategori2').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgridLP1Kategori2').setValue('');
        searchgridLP1Kategori2.onTrigger2Click();
    }
});

// TWIN COMBOBOX kategori2
var comboLP1Kategori2 = new Ext.ux.TwinComboLP1Kategori2({
    fieldLabel: 'Kategori 2',
    id: 'id_cbLP1Kategori2',
    store: strLP1Kategori2,
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

var smgridLP1Kategori3 = new Ext.grid.CheckboxSelectionModel();

var strLP1Kategori3 = new Ext.data.ArrayStore({
    fields: ['kd_kategori3', 'nama_kategori3'],
    data: []
});

// GRID PANEL TWIN COMBOBOX kategori3 Data Store
var strgridLP1Kategori3 = new Ext.data.Store({
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

strgridLP1Kategori3.on('load', function(){
    strgridLP1Kategori3.setBaseParam('kategori1', Ext.getCmp('id_lp1_kategori1_sel').getValue());
    strgridLP1Kategori3.setBaseParam('kategori2', Ext.getCmp('id_lp1_kategori2_sel').getValue());
    Ext.getCmp('id_searchgridLP1Kategori3').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX kategori3
var searchgridLP1Kategori3 = new Ext.app.SearchField({
    store: strgridLP1Kategori3,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridLP1Kategori3'
});

// GRID PANEL TWIN COMBOBOX kategori3
var gridLP1Kategori3 = new Ext.grid.GridPanel({
    store: strgridLP1Kategori3,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridLP1Kategori3,
    columns: [
        smgridLP1Kategori3,
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
        items: [searchgridLP1Kategori3]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridLP1Kategori3,
        displayInfo: true
    })
});

var menuLP1Kategori3 = new Ext.menu.Menu();

menuLP1Kategori3.add(new Ext.Panel({
    title: 'Pilih Kategori 3',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridLP1Kategori3],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridLP1Kategori3.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lp1_kategori3_sel','kd_kategori3',sel);
                    sm.clearSelections();
                }
                menuLP1Kategori3.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridLP1Kategori3.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lp1_kategori3_sel','kd_kategori3',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lp1_kategori3_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuLP1Kategori3.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX kategori3
Ext.ux.TwinComboLP1Kategori3 = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridLP1Kategori3.removeAll();
        //load store grid
        var kategori1 = Ext.getCmp('id_lp1_kategori1_sel').getValue();
        var kategori2 = Ext.getCmp('id_lp1_kategori2_sel').getValue();
        if(kategori1.length > 0 || kategori2.length > 0 ) {
            strgridLP1Kategori3.load({params: {
                kategori1: kategori1,
                kategori2: kategori2
            }});
        } else {
            strgridLP1Kategori3.load();
        }
        menuLP1Kategori3.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuLP1Kategori3.on('hide', function () {
    var sf = Ext.getCmp('id_searchgridLP1Kategori3').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgridLP1Kategori3').setValue('');
        searchgridLP1Kategori3.onTrigger2Click();
    }
});

// TWIN COMBOBOX kategori3
var comboLP1Kategori3 = new Ext.ux.TwinComboLP1Kategori3({
    fieldLabel: 'Kategori 3',
    id: 'id_cbLP1Kategori3',
    store: strLP1Kategori3,
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

var smgridLP1Kategori4 = new Ext.grid.CheckboxSelectionModel();

var strLP1Kategori4 = new Ext.data.ArrayStore({
    fields: ['kd_kategori4', 'nama_kategori4'],
    data: []
});

// GRID PANEL TWIN COMBOBOX kategori4 Data Store
var strgridLP1Kategori4 = new Ext.data.Store({
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

strgridLP1Kategori4.on('load', function(){
    strgridLP1Kategori4.setBaseParam('kategori1', Ext.getCmp('id_lp1_kategori1_sel').getValue());
    strgridLP1Kategori4.setBaseParam('kategori2', Ext.getCmp('id_lp1_kategori2_sel').getValue());
    strgridLP1Kategori4.setBaseParam('kategori3', Ext.getCmp('id_lp1_kategori3_sel').getValue());
    Ext.getCmp('id_searchgridLP1Kategori4').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX kategori4
var searchgridLP1Kategori4 = new Ext.app.SearchField({
    store: strgridLP1Kategori4,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridLP1Kategori4'
});

// GRID PANEL TWIN COMBOBOX kategori4
var gridLP1Kategori4 = new Ext.grid.GridPanel({
    store: strgridLP1Kategori4,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridLP1Kategori4,
    columns: [
        smgridLP1Kategori4,
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
        items: [searchgridLP1Kategori4]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridLP1Kategori4,
        displayInfo: true
    })
});

var menuLP1Kategori4 = new Ext.menu.Menu();

menuLP1Kategori4.add(new Ext.Panel({
    title: 'Pilih Kategori 4',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridLP1Kategori4],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridLP1Kategori4.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lp1_kategori4_sel','kd_kategori4',sel);
                    sm.clearSelections();
                }
                menuLP1Kategori4.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridLP1Kategori4.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lp1_kategori4_sel','kd_kategori4',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lp1_kategori4_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuLP1Kategori4.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX kategori4
Ext.ux.TwinComboLP1Kategori4 = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridLP1Kategori4.removeAll();
        var kategori1 = Ext.getCmp('id_lp1_kategori1_sel').getValue();
        var kategori2 = Ext.getCmp('id_lp1_kategori2_sel').getValue();
        var kategori3 = Ext.getCmp('id_lp1_kategori3_sel').getValue();
        if(kategori1.length > 0 || kategori2.length > 0 || kategori3.length > 0 ) {
            strgridLP1Kategori4.load({params: {
                kategori1: kategori1,
                kategori2: kategori2,
                kategori3: kategori3
            }});
        } else {
            strgridLP1Kategori4.load();
        }
        menuLP1Kategori4.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuLP1Kategori4.on('hide', function () {
    var sf = Ext.getCmp('id_searchgridLP1Kategori4').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgridLP1Kategori4').setValue('');
        searchgridLP1Kategori4.onTrigger2Click();
    }
});

// TWIN COMBOBOX kategori4
var comboLP1Kategori4 = new Ext.ux.TwinComboLP1Kategori4({
    fieldLabel: 'Kategori 4',
    id: 'id_cbLP1Kategori4',
    store: strLP1Kategori4,
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

var smgridLP1Ukuran = new Ext.grid.CheckboxSelectionModel();

var strLP1Ukuran = new Ext.data.ArrayStore({
    fields: ['kd_ukuran', 'nama_ukuran'],
    data: []
});

// GRID PANEL TWIN COMBOBOX Ukuran Data Store
var strgridLP1Ukuran = new Ext.data.Store({
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

strgridLP1Ukuran.on('load', function(){
    Ext.getCmp('id_searchgridLP1Ukuran').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX Ukuran
var searchgridLP1Ukuran = new Ext.app.SearchField({
    store: strgridLP1Ukuran,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridLP1Ukuran'
});

// GRID PANEL TWIN COMBOBOX Ukuran
var gridLP1Ukuran = new Ext.grid.GridPanel({
    store: strgridLP1Ukuran,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridLP1Ukuran,
    columns: [
        smgridLP1Ukuran,
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
        items: [searchgridLP1Ukuran]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridLP1Ukuran,
        displayInfo: true
    })
});

var menuLP1Ukuran = new Ext.menu.Menu();

menuLP1Ukuran.add(new Ext.Panel({
    title: 'Pilih Ukuran',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridLP1Ukuran],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridLP1Ukuran.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lp1_ukuran_sel','kd_ukuran',sel);
                    sm.clearSelections();
                }
                menuLP1Ukuran.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridLP1Ukuran.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lp1_ukuran_sel','kd_ukuran',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lp1_ukuran_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuLP1Ukuran.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX Ukuran
Ext.ux.TwinComboLP1Ukuran = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        //load store grid
        strgridLP1Ukuran.removeAll();
        strgridLP1Ukuran.load();
        menuLP1Ukuran.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuLP1Ukuran.on('hide', function () {
    var sf = Ext.getCmp('id_searchgridLP1Ukuran').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgridLP1Ukuran').setValue('');
        searchgridLP1Ukuran.onTrigger2Click();
    }
});

// TWIN COMBOBOX Ukuran
var comboLP1Ukuran = new Ext.ux.TwinComboLP1Ukuran({
    fieldLabel: 'Ukuran',
    id: 'id_cbLP1Ukuran',
    store: strLP1Ukuran,
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

var smgridLP1Satuan = new Ext.grid.CheckboxSelectionModel();

var strLP1Satuan = new Ext.data.ArrayStore({
    fields: ['kd_satuan', 'nm_satuan'],
    data: []
});

// GRID PANEL TWIN COMBOBOX Satuan Data Store
var strgridLP1Satuan = new Ext.data.Store({
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

strgridLP1Satuan.on('load', function(){
    Ext.getCmp('id_searchgridLP1Satuan').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX Satuan
var searchgridLP1Satuan = new Ext.app.SearchField({
    store: strgridLP1Satuan,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridLP1Satuan'
});

// GRID PANEL TWIN COMBOBOX Satuan
var gridLP1Satuan = new Ext.grid.GridPanel({
    store: strgridLP1Satuan,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridLP1Satuan,
    columns: [
        smgridLP1Satuan,
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
        items: [searchgridLP1Satuan]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridLP1Satuan,
        displayInfo: true
    })
});

var menuLP1Satuan = new Ext.menu.Menu();

menuLP1Satuan.add(new Ext.Panel({
    title: 'Pilih Satuan',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridLP1Satuan],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridLP1Satuan.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lp1_satuan_sel','kd_satuan',sel);
                    sm.clearSelections();
                }
                menuLP1Satuan.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridLP1Satuan.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lp1_satuan_sel','kd_satuan',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lp1_satuan_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuLP1Satuan.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX Satuan
Ext.ux.TwinComboLP1Satuan = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        //load store grid
        strgridLP1Satuan.removeAll();
        strgridLP1Satuan.load();
        menuLP1Satuan.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuLP1Satuan.on('hide', function () {
    var sf = Ext.getCmp('id_searchgridLP1Satuan').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgridLP1Satuan').setValue('');
        searchgridLP1Satuan.onTrigger2Click();
    }
});

// TWIN COMBOBOX Satuan
var comboLP1Satuan = new Ext.ux.TwinComboLP1Satuan({
    fieldLabel: 'Satuan',
    id: 'id_cbLP1Satuan',
    store: strLP1Satuan,
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

var smgridLP1Produk = new Ext.grid.CheckboxSelectionModel();

var strLP1Produk = new Ext.data.ArrayStore({
    fields: ['kd_produk', 'nama_produk'],
    data: []
});

// GRID PANEL TWIN COMBOBOX Produk Data Store
var strgridLP1Produk = new Ext.data.Store({
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

strgridLP1Produk.on('load', function(){
    strgridLP1Produk.setBaseParam('kategori1', Ext.getCmp('id_lp1_kategori1_sel').getValue());
    strgridLP1Produk.setBaseParam('kategori2', Ext.getCmp('id_lp1_kategori2_sel').getValue());
    strgridLP1Produk.setBaseParam('kategori3', Ext.getCmp('id_lp1_kategori3_sel').getValue());
    strgridLP1Produk.setBaseParam('kategori4', Ext.getCmp('id_lp1_kategori4_sel').getValue());
    strgridLP1Produk.setBaseParam('ukuran', Ext.getCmp('id_lp1_ukuran_sel').getValue());
    strgridLP1Produk.setBaseParam('satuan', Ext.getCmp('id_lp1_satuan_sel').getValue());
    Ext.getCmp('id_searchgridLP1Produk').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX Produk
var searchgridLP1Produk = new Ext.app.SearchField({
    store: strgridLP1Produk,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridLP1Produk'
});

// GRID PANEL TWIN COMBOBOX Produk
var gridLP1Produk = new Ext.grid.GridPanel({
    store: strgridLP1Produk,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridLP1Produk,
    columns: [
        smgridLP1Produk,
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
        items: [searchgridLP1Produk]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridLP1Produk,
        displayInfo: true
    })
});

var menuLP1Produk = new Ext.menu.Menu();

menuLP1Produk.add(new Ext.Panel({
    title: 'Pilih Produk',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridLP1Produk],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridLP1Produk.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lp1_produk_sel','kd_produk',sel);
                    sm.clearSelections();
                }
                menuLP1Produk.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridLP1Produk.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lp1_produk_sel','kd_produk',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lp1_produk_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuLP1Produk.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX Produk
Ext.ux.TwinComboLP1Produk = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridLP1Kategori4.removeAll();
        var kategori1   = Ext.getCmp('id_lp1_kategori1_sel').getValue();
        var kategori2   = Ext.getCmp('id_lp1_kategori2_sel').getValue();
        var kategori3   = Ext.getCmp('id_lp1_kategori3_sel').getValue();
        var kategori4   = Ext.getCmp('id_lp1_kategori4_sel').getValue();
        var ukuran      = Ext.getCmp('id_lp1_ukuran_sel').getValue();
        var satuan      = Ext.getCmp('id_lp1_satuan_sel').getValue();
        strgridLP1Produk.load({params: {
            kategori1: kategori1,
            kategori2: kategori2,
            kategori3: kategori3,
            kategori4: kategori4,
               ukuran: ukuran,
               satuan: satuan
        }});
        menuLP1Produk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuLP1Produk.on('hide', function () {
    var sf = Ext.getCmp('id_searchgridLP1Produk').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgridLP1Produk').setValue('');
        searchgridLP1Produk.onTrigger2Click();
    }
});

// TWIN COMBOBOX Produk
var comboLP1Produk = new Ext.ux.TwinComboLP1Produk({
    fieldLabel: 'Produk',
    id: 'id_cbLP1Produk',
    store: strLP1Produk,
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


//-------- COMBOBOX JenisBayar ---------------------

var smGridLP1JenisBayar = new Ext.grid.CheckboxSelectionModel();

var strLP1JenisBayar = new Ext.data.ArrayStore({
    fields: ['kd_jenis_bayar', 'nm_pembayaran'],
    data: []
});

// GRID PANEL TWIN COMBOBOX JenisBayar Data Store
var strGridLP1JenisBayar = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: ['kd_jenis_bayar', 'nm_pembayaran'],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("report/get_jns_bayar") ?>',
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

strGridLP1JenisBayar.on('load', function(){
    Ext.getCmp('id_searchGridLP1JenisBayar').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX JenisBayar
var searchGridLP1JenisBayar = new Ext.app.SearchField({
    store: strGridLP1JenisBayar,
    width: 350,
    id: 'id_searchGridLP1JenisBayar'
});

// GRID PANEL TWIN COMBOBOX JenisBayar
var gridLP1JenisBayar = new Ext.grid.GridPanel({
    store: strGridLP1JenisBayar,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smGridLP1JenisBayar,
    columns: [
        smGridLP1JenisBayar,
        {
            header: 'Kode Metode Pembayaran',
            dataIndex: 'kd_jenis_bayar',
            width: 100,
            sortable: true

        },
        {
            header: 'Nama Metode Pembayaran',
            dataIndex: 'nm_pembayaran',
            width: 300,
            sortable: true
        }
    ],
    tbar: new Ext.Toolbar({
        items: [searchGridLP1JenisBayar]
    })
});

var menuLP1JenisBayar = new Ext.menu.Menu();

menuLP1JenisBayar.add(new Ext.Panel({
    title: 'Pilih Metode Pembayaran',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridLP1JenisBayar],

    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridLP1JenisBayar.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lp1_jns_bayar_sel','kd_jenis_bayar',sel);
                    sm.clearSelections();
                }
                menuLP1JenisBayar.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridLP1JenisBayar.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lp1_jns_bayar_sel','kd_jenis_bayar',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lp1_jns_bayar_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuLP1JenisBayar.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX JenisBayar
Ext.ux.TwinComboLP1JenisBayar = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        //load store grid
        strGridLP1JenisBayar.removeAll();
        strGridLP1JenisBayar.load();
        menuLP1JenisBayar.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuLP1JenisBayar.on('hide', function () {
    var sf = Ext.getCmp('id_searchGridLP1JenisBayar').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchGridLP1JenisBayar').setValue('');
        searchGridLP1JenisBayar.onTrigger2Click();
    }
});

// TWIN COMBOBOX JenisBayar
var comboLP1JenisBayar = new Ext.ux.TwinComboLP1JenisBayar({
    fieldLabel: 'Metode Pembayaran',
    id: 'id_cbLP1JenisBayar',
    store: strLP1JenisBayar,
    mode: 'local',
    valueField: 'kd_jenis_bayar',
    displayField: 'kd_jenis_bayar',
    typeAhead: true,
    triggerAction: 'all',
    editable: false,
    anchor: '90%',
    hiddenName: 'kd_jenis_bayar',
    emptyText: 'Pilih Metode Pembayaran'
});
//-------- COMBOBOX JenisBayar ---------------------
/* MASIH KURANG BANYAK VROOOOH
 * - jenis pembayaran
 * */

// -------- MAIN FORM -------------------------
var headerlp1tanggal = {
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
                        fieldLabel: 'Dari Tgl',
                        name: 'dari_tgl',
                        allowBlank:false,
                        format:'d-m-Y',
                        editable:false,
                        id: 'id_lp1_tgl_awal',
                        anchor: '90%',
                        value: ''
                    },
                        comboLP1User,
                        comboLP1Shift,
                        comboLP1Member,
                        comboLP1Kategori1,
                        comboLP1Kategori2,
                        comboLP1Kategori3,
                        comboLP1Kategori4,
                        comboLP1Ukuran,
                        comboLP1Satuan,
                        comboLP1Produk,
                        comboLP1JenisBayar
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
                                id: 'id_lp1_tgl_akhir',
                                anchor: '90%',
                                value: ''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'User',
                                name: 'id_user_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_lp1_user_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Shift',
                                name: 'shift_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_lp1_shift_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Kd. Member',
                                name: 'kd_member_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_lp1_member_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Kd. Kategori 1',
                                name: 'kd_kategori1_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_lp1_kategori1_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Kd. Kategori 2',
                                name: 'kd_kategori2_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_lp1_kategori2_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Kd. Kategori 3',
                                name: 'kd_kategori3_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_lp1_kategori3_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Kd. Kategori 4',
                                name: 'kd_kategori4_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_lp1_kategori4_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Ukuran',
                                name: 'kd_ukuran_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_lp1_ukuran_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Satuan',
                                name: 'kd_satuan_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_lp1_satuan_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Kode Produk',
                                name: 'kd_produk_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_lp1_produk_sel',
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Jns. Pembayaran',
                                name: 'kd_jns_byr_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_lp1_jns_bayar_sel',
                                anchor: '90%',
                                value:''
                            }]
                    }]
            }]
        }]
    }]
}


var laporanpenjualan1 = new Ext.FormPanel({
    id: 'rpt_penjualan1',
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
            items: [headerlp1tanggal],
            buttons: [
                {
                    text: 'Print',
                    formBind:true,
                    handler: function () {
                        Ext.getCmp('rpt_penjualan1').getForm().submit({
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

                                clearform('rpt_penjualan1');
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