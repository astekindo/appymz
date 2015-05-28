<?php if (!defined( 'BASEPATH')) exit( 'No direct script access allowed'); ?>
<script type="text/javascript">
//-------- COMBOBOX SUPPLIER --------------------
var smGridPK2Supplier = new Ext.grid.CheckboxSelectionModel();

var strReportJualPK2Supplier = new Ext.data.ArrayStore({
    fields: ['kd_supplier'],
    data: []
});

// GRID PANEL TWIN COMBOBOX supplier Data Store
var strGridReportJualPK2Supplier = new Ext.data.Store({
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
var searchGridReportJualPK2Supplier = new Ext.app.SearchField({
    store: strGridReportJualPK2Supplier,
    width: 350,
    id: 'id_searchGridReportJualPK2Supplier'
});

// GRID PANEL TWIN COMBOBOX supplier
var GridReportJualPK2Supplier = new Ext.grid.GridPanel({
    store: strGridReportJualPK2Supplier,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smGridPK2Supplier,
    columns: [
        smGridPK2Supplier,
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
        items: [searchGridReportJualPK2Supplier]
    })
});

// PANEL TWIN COMBOBOX supplier
var menuReportJualPK2Supplier = new Ext.menu.Menu();

menuReportJualPK2Supplier.add(new Ext.Panel({
    title: 'Pilih Supplier',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [GridReportJualPK2Supplier],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = GridReportJualPK2Supplier.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lppk2_supplier_sel','kd_supplier',sel);
                    sm.clearSelections();
                }
                menuReportJualPK2Supplier.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = GridReportJualPK2Supplier.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lppk2_supplier_sel','kd_supplier',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lppk2_supplier_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuReportJualPK2Supplier.hide(); }
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
        strGridReportJualPK2Supplier.load();
        menuReportJualPK2Supplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuReportJualPK2Supplier.on('hide', function () {
    var sf = Ext.getCmp('id_searchGridReportJualPK2Supplier').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchGridReportJualPK2Supplier').setValue('');
        searchGridReportJualPK2Supplier.onTrigger2Click();
    }
});

// TWIN COMBOBOX supplier
var cbReportJualPK2Supplier = new Ext.ux.TwinComboSuplier({
    fieldLabel: 'Supplier',
    id: 'id_cbReportJualPK2Supplier',
    store: strReportJualPK2Supplier,
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
var smgridPK2Kategori1 = new Ext.grid.CheckboxSelectionModel();

var strPK2Kategori1 = new Ext.data.ArrayStore({
    fields: ['kd_kategori1', 'nama_kategori1'],
    data: []
});

// GRID PANEL TWIN COMBOBOX kategori2 Data Store
var strgridPK2Kategori1 = new Ext.data.Store({
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

strgridPK2Kategori1.on('load', function(){
    Ext.getCmp('id_searchgridPK2Kategori1').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX kategori2
var searchgridPK2Kategori1 = new Ext.app.SearchField({
    store: strgridPK2Kategori1,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridPK2Kategori1'
});

// GRID PANEL TWIN COMBOBOX kategori1
var gridPK2Kategori1 = new Ext.grid.GridPanel({
    store: strgridPK2Kategori1,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridPK2Kategori1,
    columns: [
        smgridPK2Kategori1,
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
        items: [searchgridPK2Kategori1]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridPK2Kategori1,
        displayInfo: true
    })
});

var menuPK2Kategori1 = new Ext.menu.Menu();

menuPK2Kategori1.add(new Ext.Panel({
    title: 'Pilih Kategori 1',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridPK2Kategori1],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridPK2Kategori1.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lppk2_kategori1_sel','kd_kategori1',sel);
                    sm.clearSelections();
                }
                menuPK2Kategori1.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridPK2Kategori1.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lppk2_kategori1_sel','kd_kategori1',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lppk2_kategori1_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuPK2Kategori1.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX kategori1
Ext.ux.TwinComboPK2Kategori1 = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridPK2Kategori1.removeAll();
        strgridPK2Kategori1.load();
        menuPK2Kategori1.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuPK2Kategori1.on('hide', function () {
    var sf = Ext.getCmp('id_searchgridPK2Kategori1').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgridPK2Kategori1').setValue('');
        searchgridPK2Kategori1.onTrigger2Click();
    }
});

// TWIN COMBOBOX kategori2
var comboPK2Kategori1 = new Ext.ux.TwinComboPK2Kategori1({
    fieldLabel: 'Kategori 1',
    id: 'id_cbPK2Kategori1',
    store: strPK2Kategori1,
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

var smgridPK2Kategori2 = new Ext.grid.CheckboxSelectionModel();

var strPK2Kategori2 = new Ext.data.ArrayStore({
    fields: ['kd_kategori2', 'nama_kategori2'],
    data: []
});

// GRID PANEL TWIN COMBOBOX kategori2 Data Store
var strgridPK2Kategori2 = new Ext.data.Store({
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

strgridPK2Kategori2.on('load', function(){
    strgridPK2Kategori2.setBaseParam('kategori1', Ext.getCmp('id_lppk2_kategori1_sel').getValue());
    Ext.getCmp('id_searchgridPK2Kategori2').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX kategori2
var searchgridPK2Kategori2 = new Ext.app.SearchField({
    store: strgridPK2Kategori2,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridPK2Kategori2'
});

// GRID PANEL TWIN COMBOBOX kategori2
var gridPK2Kategori2 = new Ext.grid.GridPanel({
    store: strgridPK2Kategori2,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridPK2Kategori2,
    columns: [
        smgridPK2Kategori2,
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
        items: [searchgridPK2Kategori2]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridPK2Kategori2,
        displayInfo: true
    })
});

var menuPK2Kategori2 = new Ext.menu.Menu();

menuPK2Kategori2.add(new Ext.Panel({
    title: 'Pilih Kategori 2',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridPK2Kategori2],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridPK2Kategori2.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lppk2_kategori2_sel','kd_kategori2',sel);
                    sm.clearSelections();
                }
                menuPK2Kategori2.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridPK2Kategori2.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lppk2_kategori2_sel','kd_kategori2',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lppk2_kategori2_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuPK2Kategori2.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX kategori2
Ext.ux.TwinComboPK2Kategori2 = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridPK2Kategori2.removeAll();
        var kategori1 = Ext.getCmp('id_lppk2_kategori1_sel').getValue();
        if(kategori1.length>0) {
            strgridPK2Kategori2.load({params: {kategori1: kategori1}});
        } else {
            strgridPK2Kategori2.load();
        }
        menuPK2Kategori2.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuPK2Kategori2.on('hide', function () {
    var sf = Ext.getCmp('id_searchgridPK2Kategori2').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgridPK2Kategori2').setValue('');
        searchgridPK2Kategori2.onTrigger2Click();
    }
});

// TWIN COMBOBOX kategori2
var comboPK2Kategori2 = new Ext.ux.TwinComboPK2Kategori2({
    fieldLabel: 'Kategori 2',
    id: 'id_cbPK2Kategori2',
    store: strPK2Kategori2,
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


//-------- CHECKBOX SORT ORDER -------------------
var sortReportJualPK2 = new Ext.form.Checkbox({
    xtype: 'checkbox',
    fieldLabel: 'Sort Order',
    boxLabel: 'Descending',
    name: 'sort_order',
    id: 'id_sortReportJualPK2',
    checked: true,
    inputValue: '1',
    autoLoad: true
});
//-------- CHECKBOX SORT ORDER -------------------

//-------- COMBOBOX PERUNTUKAN -------------------
var dsStatusReportJualPK2=[['D',"Distribusi"],['B',"Bazar"],['S',"Supermarket"]];

var strReportJualPK2Status = new Ext.data.ArrayStore({
    fields: [{name: 'key'},{name: 'value'}],
    data:dsStatusReportJualPK2
});

// COMBOBOX status
var cbReportJualPK2Status = new Ext.form.ComboBox({
    fieldLabel: 'Peruntukkan',
    id: 'id_cbReportJualPK2Status',
    name:'status',
    // allowBlank:false,
    store: strReportJualPK2Status,
    valueField:'key',
    displayField:'value',
    mode:'local',
    forceSelection: true,
    triggerAction: 'all',
    anchor: '90%'
});
//-------- COMBOBOX PERUNTUKAN -------------------

//-------- HEADER TANGGAL ------------------------
var headerReportJualPK2Tanggal = {
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
                        name: 'lppk2_dari_tgl',
                        allowBlank: false,
                        format: 'd-m-Y',
                        editable: false,
                        id: 'id_lppk2_dari_tgl',
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
                        name: 'lppk2_sampai_tgl',
                        // readOnly: true,
                        allowBlank: false,
                        editable: false,
                        format: 'd-m-Y',
                        id: 'id_lppk2_smp_tgl',
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
var headerReportJualPK2Kategori = {
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
                        comboPK2Kategori1,
                        comboPK2Kategori2,
                        cbReportJualPK2Supplier,
                        cbReportJualPK2Status,
                        sortReportJualPK2
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
                            id: 'id_lppk2_kategori1_sel',
                            anchor: '90%',
                            value:''
                        }, {
                            xtype: 'textfield',
                            fieldLabel: 'Kd. Kategori 2',
                            name: 'kd_kategori2_sel',
                            readOnly:true,
                            fieldClass:'readonly-input',
                            id: 'id_lppk2_kategori2_sel',
                            anchor: '90%',
                            value:''
                        }, {
                            xtype: 'textfield',
                            fieldLabel: 'Kd. Supplier',
                            name: 'kd_supplier_sel',
                            readOnly:true,
                            fieldClass:'readonly-input',
                            id: 'id_lppk2_supplier_sel',
                            anchor: '90%',
                            value:''
                        }, {
                            xtype: 'radiogroup',
                            fieldLabel: 'Tampilan data',
                            columnWidth: [.5, .5],
                            name: 'data_type',
                            id: 'id_lppk2_data_type',
                            width: 250,
                            anchor: '90%',
                            allowBlank:false,
                            items: [{
                                boxLabel: 'Value',
                                name: 'data_type',
                                id: 'id_lppk2_data_typeV',
                                inputValue: '0',
                                checked: true
                            }, {
                                boxLabel: 'Quantity',
                                name: 'data_type',
                                inputValue: '1',
                                id: 'id_lppk2_data_typeQ'
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
var headerReportJualPK2Utama = {
    buttonAlign: 'left',
    layout: 'form',
    border: false,
    labelWidth: 100,
    defaults: { labelSeparator: ''},
    items: [headerReportJualPK2Tanggal, headerReportJualPK2Kategori],
    buttons: [{
        text: 'Print',
        formBind: true,
        handler: function () {
            Ext.getCmp('rpt_penjualan_perkategori2').getForm().submit({
                url: '<?= site_url("laporan_penjualan_per_kategori2/get_report") ?>',
                scope: this,
                waitMsg: 'Saving Data...',
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
                    clearform('rpt_penjualan_perkategori2');
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
        handler: function () { clearform('rpt_penjualan_perkategori2');}
    }]
};
//-------- HEADER FORM ---------------------------

//-------- MAIN PANEL ----------------------------
var ReportJualPK2 = new Ext.FormPanel({
    id: 'rpt_penjualan_perkategori2',
    border: false,
    frame: true,
    monitorValid: true,
    labelWidth: 130,
    items: [{
        bodyStyle: { margin: '0px 0px 15px 0px'},
        items: [headerReportJualPK2Utama]
    }]
});

</script>