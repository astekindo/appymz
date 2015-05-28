<?php if (!defined( 'BASEPATH')) exit( 'No direct script access allowed'); ?>
<script type="text/javascript">
// -------- COMBOBOX Supplier --------------------
var smgridLPPK1Supplier= new Ext.grid.CheckboxSelectionModel();

var strCbLPPK1Supplier = new Ext.data.ArrayStore({
    fields: ['kd_supplier'],
    data : []
});

var strgridLPPK1Supplier = new Ext.data.Store({
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

var searchgridLPPK1Supplier = new Ext.app.SearchField({
    store: strgridLPPK1Supplier,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridLPPK1Supplier'
});

var gridLPPK1Supplier = new Ext.grid.GridPanel({
    store: strgridLPPK1Supplier,
    stripeRows: true,
    frame: true,
    border:true,
    sm: smgridLPPK1Supplier,
    columns: [
        smgridLPPK1Supplier,
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
        items: [searchgridLPPK1Supplier]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridLPPK1Supplier,
        displayInfo: true
    })
});

var menuLPPK1Supplier = new Ext.menu.Menu();

menuLPPK1Supplier.add(new Ext.Panel({
    title: 'Pilih Supplier',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridLPPK1Supplier],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridLPPK1Supplier.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_LPPK1_supplier_sel','nama_supplier',sel);
                    sm.clearSelections();
                }
                menuLPPK1Supplier.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridLPPK1Supplier.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_lppk_kd_supplier_sel','kd_supplier',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_lppk_kd_supplier_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuLPPK1Supplier.hide(); }
        }]
}));

Ext.ux.TwinComboLPPK1Supplier = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function(){
        //load store grid
        strgridLPPK1Supplier.load();
        menuLPPK1Supplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuLPPK1Supplier.on('hide', function(){
    var sf = Ext.getCmp('id_searchgridLPPK1Supplier').getValue();
    if( sf != ''){
        Ext.getCmp('id_searchgridLPPK1Supplier').setValue('');
        searchgridLPPK1Supplier.onTrigger2Click();
    }
});

var comboLPPK1Supplier = new Ext.ux.TwinComboLPPK1Supplier({
    fieldLabel: 'Supplier ID',
    id: 'id_cbLPPK1Supplier',
    store: strCbLPPK1Supplier,
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

//-------- COMBOBOX KATEGORI ---------------------

    var smGridPK1Kategori = new Ext.grid.CheckboxSelectionModel();

    var strReportJualPK1Kategori = new Ext.data.ArrayStore({
        fields: ['kd_kategori1', 'nama_kategori1'],
        data: []
    });

    // GRID PANEL TWIN COMBOBOX kategori1 Data Store
    var strGridReportJualPK1Kategori = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori1', 'nama_kategori1'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("kategori2/get_kategori1") ?>',
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

    strGridReportJualPK1Kategori.on('load', function(){
        Ext.getCmp('id_searchGridReportJualPK1Kategori').focus();
    });

    // SEARCH GRID PANEL TWIN COMBOBOX kategori1
    var searchGridReportJualPK1Kategori = new Ext.app.SearchField({
        store: strGridReportJualPK1Kategori,
        width: 350,
        id: 'id_searchGridReportJualPK1Kategori'
    });

    // GRID PANEL TWIN COMBOBOX kategori1
    var GridReportJualPK1Kategori = new Ext.grid.GridPanel({
        store: strGridReportJualPK1Kategori,
        stripeRows: true,
        frame: true,
        border: true,
        sm: smGridPK1Kategori,
        columns: [
            smGridPK1Kategori,
            {
                header: 'Kode kategori1',
                dataIndex: 'kd_kategori1',
                width: 80,
                sortable: true

            }, {
                header: 'Nama kategori1',
                dataIndex: 'nama_kategori1',
                width: 300,
                sortable: true
            }
        ],
        tbar: new Ext.Toolbar({
            items: [searchGridReportJualPK1Kategori]
        })
    });

    var menuReportJualPK1Kategori = new Ext.menu.Menu();

    menuReportJualPK1Kategori.add(new Ext.Panel({
        title: 'Pilih kategori1',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [GridReportJualPK1Kategori],
        buttons: [{
            text: 'Done',
            handler: function () {
                var sm  = GridReportJualPK1Kategori.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    var kd_list = '';
                    for (i = 0; i < sel.length; i++) {
                        kd_list = kd_list + sel[i].get('kd_kategori1') + ',';
                    }
                    // console.log(kd_list);
                    kd_list = kd_list.substring(0, kd_list.length-1);
                    Ext.getCmp('id_lppk_kd_kategori1_sel').setValue(kd_list);
                    menuReportJualPK1Kategori.hide();
                } else {
                    Ext.Msg.show({
                        title: 'Error',
                        msg: 'Silahkan pilih Kategori',
                        modal: true,
                        icon: Ext.Msg.ERROR,
                        buttons: Ext.Msg.OK
                    });
                    return;
                }
            }
        }, {
            text: 'Close',
            handler: function () {
                menuReportJualPK1Kategori.hide();
            }
        }]
    }));

    // PANEL TWIN COMBOBOX kategori1
    Ext.ux.TwinComboSuplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function () {
            //load store grid
            strGridReportJualPK1Kategori.load();
            menuReportJualPK1Kategori.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menuReportJualPK1Kategori.on('hide', function () {
        var sf = Ext.getCmp('id_searchGridReportJualPK1Kategori').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchGridReportJualPK1Kategori').setValue('');
            searchGridReportJualPK1Kategori.onTrigger2Click();
        }
    });

    // TWIN COMBOBOX kategori1
    var cbReportJualPK1Kategori = new Ext.ux.TwinComboSuplier({
        fieldLabel: 'Kategori',
        id: 'id_cbReportJualPK1Kategori',
        store: strReportJualPK1Kategori,
        mode: 'local',
        valueField: 'kd_kategori1',
        displayField: 'kd_kategori1',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_kategori1',
        emptyText: 'Pilih Kategori'
    });
//-------- COMBOBOX KATEGORI ---------------------

//-------- CHECKBOX SORT ORDER -------------------
    var sortReportJualPK1 = new Ext.form.Checkbox({
        xtype: 'checkbox',
        fieldLabel: 'Sort Order',
        boxLabel: 'Descending',
        name: 'sort_order',
        id: 'id_sortReportJualPK1',
        checked: true,
        inputValue: '1',
        autoLoad: true
    });
//-------- CHECKBOX SORT ORDER -------------------

//-------- COMBOBOX PERUNTUKAN -------------------
    var dsStatusReportJualPK1=[['D',"Distribusi"],['B',"Bazar"],['S',"Supermarket"]];

    var strReportJualPK1Status = new Ext.data.ArrayStore({
        fields: [{name: 'key'},{name: 'value'}],
        data:dsStatusReportJualPK1
    });

    // COMBOBOX status
    var cbReportJualPK1Status = new Ext.form.ComboBox({
        fieldLabel: 'Peruntukkan',
        id: 'id_cbReportJualPK1Status',
        name:'status',
        // allowBlank:false,
        store: strReportJualPK1Status,
        valueField:'key',
        displayField:'value',
        mode:'local',
        forceSelection: true,
        triggerAction: 'all',
        anchor: '90%'
    });
//-------- COMBOBOX PERUNTUKAN -------------------

//-------- HEADER TANGGAL ------------------------
    var headerReportJualPK1Tanggal = {
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
                            name: 'lppk1_dari_tgl',
                            allowBlank: false,
                            format: 'd-m-Y',
                            editable: false,
                            id: 'id_lppk1_dari_tgl',
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
                            name: 'lppk1_sampai_tgl',
                            // readOnly: true,
                            allowBlank: false,
                            editable: false,
                            format: 'd-m-Y',
                            id: 'id_lppk1_smp_tgl',
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
    var headerReportJualPK1Kategori = {
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
                            cbReportJualPK1Kategori,
                            comboLPPK1Supplier,
                            cbReportJualPK1Status,
                            sortReportJualPK1
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
                                fieldLabel: 'Kd. Kategori',
                                name: 'kd_kategori1_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_lppk_kd_kategori1_sel',
                                anchor: '90%',
                                value:''
                            }, {
                                xtype: 'textfield',
                                fieldLabel: 'Kd. Supplier',
                                name: 'kd_supplier_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_lppk_kd_supplier_sel',
                                anchor: '90%',
                                value:''
                            }, {
                                xtype: 'radiogroup',
                                fieldLabel: 'Tampilan data',
                                columnWidth: [.5, .5],
                                name: 'data_type',
                                id: 'lppk1_data_type',
                                width: 250,
                                anchor: '90%',
                                allowBlank:false,
                                items: [{
                                    boxLabel: 'Value',
                                    name: 'data_type',
                                    id: 'lppk1_data_typeV',
                                    inputValue: '0',
                                    checked: true
                                }, {
                                    boxLabel: 'Quantity',
                                    name: 'data_type',
                                    inputValue: '1',
                                    id: 'lppk1_data_typeQ'
                                }]
                            },{
                                xtype: 'radiogroup',
                                fieldLabel: 'Nilai ditampilkan',
                                columnWidth: [.5, .5],
                                name: 'value_type',
                                id: 'lppk1_value_type',
                                width: 250,
                                anchor: '90%',
                                allowBlank:false,
                                items: [{
                                    boxLabel: 'DPP',
                                    name: 'value_type',
                                    id: 'lppk1_value_typeD',
                                    inputValue: '0',
                                    checked: true
                                }, {
                                    boxLabel: 'GROSS',
                                    name: 'value_type',
                                    inputValue: '1',
                                    id: 'lppk1_value_typeG'
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
    var headerReportJualPK1Utama = {
        buttonAlign: 'left',
        layout: 'form',
        border: false,
        labelWidth: 100,
        defaults: { labelSeparator: ''},
        items: [headerReportJualPK1Tanggal, headerReportJualPK1Kategori],
        buttons: [{
            text: 'Print',
            formBind: true,
            handler: function () {
                Ext.getCmp('rpt_penjualan_perkategori1').getForm().submit({
                    url: '<?= site_url("laporan_penjualan_per_kategori1/get_report") ?>',
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
                        clearform('rpt_penjualan_perkategori1');
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
            handler: function () { clearform('rpt_penjualan_perkategori1');}
        }]
    };
//-------- HEADER FORM ---------------------------

//-------- MAIN PANEL ----------------------------
    var ReportJualPK1 = new Ext.FormPanel({
        id: 'rpt_penjualan_perkategori1',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
            bodyStyle: { margin: '0px 0px 15px 0px'},
            items: [headerReportJualPK1Utama]
        }]
    });

    // // CLEAR DATA FORM PANEL
    // function clearform(id) {
    //     Ext.getCmp(id).getForm().reset();
    // }
//-------- MAIN PANEL ----------------------------
</script>