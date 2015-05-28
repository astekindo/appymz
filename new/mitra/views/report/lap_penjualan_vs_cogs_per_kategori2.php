<?php if (!defined( 'BASEPATH')) exit( 'No direct script access allowed'); ?>
<script type="text/javascript">

//-------- COMBOBOX KATEGORI ---------------------

    var smGridPVCPK2Kategori = new Ext.grid.CheckboxSelectionModel();

    var strPVCPK2Kategori = new Ext.data.ArrayStore({
        fields: ['kd_kategori1', 'nama_kategori1'],
        data: []
    });

    // GRID PANEL TWIN COMBOBOX kategori1 Data Store
    var strGridPVCPK2Kategori = new Ext.data.Store({
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

    strGridPVCPK2Kategori.on('load', function(){
        Ext.getCmp('id_searchGridPVCPK2Kategori').focus();
    });

    // SEARCH GRID PANEL TWIN COMBOBOX kategori1
    var searchGridPVCPK2Kategori = new Ext.app.SearchField({
        store: strGridPVCPK2Kategori,
        width: 350,
        id: 'id_searchGridPVCPK2Kategori'
    });

    // GRID PANEL TWIN COMBOBOX kategori1
    var GridPVCPK2Kategori = new Ext.grid.GridPanel({
        store: strGridPVCPK2Kategori,
        stripeRows: true,
        frame: true,
        border: true,
        sm: smGridPVCPK2Kategori,
        columns: [
            smGridPVCPK2Kategori,
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
            items: [searchGridPVCPK2Kategori]
        })
    });

    var menuPVCPK2Kategori = new Ext.menu.Menu();

    menuPVCPK2Kategori.add(new Ext.Panel({
        title: 'Pilih kategori1',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [GridPVCPK2Kategori],
        buttons: [{
            text: 'Done',
            handler: function () {
                var sm  = GridPVCPK2Kategori.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    var kd_list = '';
                    for (i = 0; i < sel.length; i++) {
                        kd_list = kd_list + sel[i].get('kd_kategori1') + ',';
                    }
                    // console.log(kd_list);
                    kd_list = kd_list.substring(0, kd_list.length-1);
                    Ext.getCmp('id_pvcpk2_kategori1_sel').setValue(kd_list);
                    menuPVCPK2Kategori.hide();
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
                menuPVCPK2Kategori.hide();
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
            strGridPVCPK2Kategori.load();
            menuPVCPK2Kategori.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menuPVCPK2Kategori.on('hide', function () {
        var sf = Ext.getCmp('id_searchGridPVCPK2Kategori').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchGridPVCPK2Kategori').setValue('');
            searchGridPVCPK2Kategori.onTrigger2Click();
        }
    });

    // TWIN COMBOBOX kategori1
    var comboPVCPK2Kategori = new Ext.ux.TwinComboSuplier({
        fieldLabel: 'Kategori',
        id: 'id_cbPVCPK2Kategori',
        store: strPVCPK2Kategori,
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

//-------- COMBOBOX KATEGORI2 ---------------------

var smgridPVCPK2Kategori2 = new Ext.grid.CheckboxSelectionModel();

var strPVCPK2Kategori2 = new Ext.data.ArrayStore({
    fields: ['kd_kategori2', 'nama_kategori2'],
    data: []
});

// GRID PANEL TWIN COMBOBOX kategori2 Data Store
var strgridPVCPK2Kategori2 = new Ext.data.Store({
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

strgridPVCPK2Kategori2.on('load', function(){
    strgridPVCPK2Kategori2.setBaseParam('kategori1', Ext.getCmp('id_pvcpk2_kategori1_sel').getValue());
    Ext.getCmp('id_searchgrid_pvcpk2_kategori2').focus();
});

// SEARCH GRID PANEL TWIN COMBOBOX kategori2
var searchgridPVCPK2Kategori2 = new Ext.app.SearchField({
    store: strgridPVCPK2Kategori2,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgrid_pvcpk2_kategori2'
});

// GRID PANEL TWIN COMBOBOX kategori2
var gridPVCPK2Kategori2 = new Ext.grid.GridPanel({
    store: strgridPVCPK2Kategori2,
    stripeRows: true,
    frame: true,
    border: true,
    sm: smgridPVCPK2Kategori2,
    columns: [
        smgridPVCPK2Kategori2,
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
        items: [searchgridPVCPK2Kategori2]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridPVCPK2Kategori2,
        displayInfo: true
    })
});

var menuPVCPK2Kategori2 = new Ext.menu.Menu();

menuPVCPK2Kategori2.add(new Ext.Panel({
    title: 'Pilih Kategori 2',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridPVCPK2Kategori2],
    buttons: [
        {
            text: 'Done',
            handler: function () {
                var sm  = gridPVCPK2Kategori2.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_pvcpk2_kategori2_sel','kd_kategori2',sel);
                    sm.clearSelections();
                }
                menuPVCPK2Kategori2.hide();
            }
        },
        {
            text: 'Add Selected',
            handler: function(){
                var sm  = gridPVCPK2Kategori2.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    addSelectedValue('id_pvcpk2_kategori2_sel','kd_kategori2',sel);
                    sm.clearSelections();
                }
            }
        },
        {
            text: 'Reset',
            handler: function(){
                Ext.getCmp('id_pvcpk2_kategori2_sel').setValue('');
            }
        },
        {
            text: 'Close',
            handler: function(){ menuPVCPK2Kategori2.hide(); }
        }
    ]
}));

// PANEL TWIN COMBOBOX kategori2
Ext.ux.TwinComboPVCPK2Kategori2 = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function () {
        strgridPVCPK2Kategori2.removeAll();
        var kategori1 = Ext.getCmp('id_pvcpk2_kategori1_sel').getValue();
        if(kategori1.length>0) {
            strgridPVCPK2Kategori2.load({params: {kategori1: kategori1}});
        } else {
            strgridPVCPK2Kategori2.load();
        }
        menuPVCPK2Kategori2.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuPVCPK2Kategori2.on('hide', function () {
    var sf = Ext.getCmp('id_searchgrid_pvcpk2_kategori2').getValue();
    if (sf != '') {
        Ext.getCmp('id_searchgrid_pvcpk2_kategori2').setValue('');
        searchgridPVCPK2Kategori2.onTrigger2Click();
    }
});

// TWIN COMBOBOX kategori2
var comboPVCPK2Kategori2 = new Ext.ux.TwinComboPVCPK2Kategori2({
    fieldLabel: 'Kategori 2',
    id: 'id_cb_pvcpk2_kategori2',
    store: strPVCPK2Kategori2,
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
    var sortPVCPK2 = new Ext.form.Checkbox({
        xtype: 'checkbox',
        fieldLabel: 'Sort Order',
        boxLabel: 'Descending',
        name: 'sort_order',
        id: 'id_sortPVCPK2',
        checked: true,
        inputValue: '1',
        autoLoad: true
    });
//-------- CHECKBOX SORT ORDER -------------------

//-------- HEADER TANGGAL ------------------------
    var headerPVCPK2Tanggal = {
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
                            name: 'pvcpk2_dari_tgl',
                            allowBlank: false,
                            format: 'd-m-Y',
                            editable: false,
                            id: 'id_pvcpk2_dari_tgl',
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
                            name: 'pvcpk2_sampai_tgl',
                            // readOnly: true,
                            allowBlank: false,
                            editable: false,
                            format: 'd-m-Y',
                            id: 'id_pvcpk2_smp_tgl',
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
    var headerPVCPK2Kategori = {
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
                            comboPVCPK2Kategori,
                            comboPVCPK2Kategori2,
                            sortPVCPK2
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
                                fieldLabel: 'Kd. Kategori1',
                                name: 'kategori1_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_pvcpk2_kategori1_sel',
                                anchor: '90%',
                                value:''
                            },{
                                xtype: 'textfield',
                                fieldLabel: 'Kd. Kategori2',
                                name: 'kategori2_sel',
                                readOnly:true,
                                fieldClass:'readonly-input',
                                id: 'id_pvcpk2_kategori2_sel',
                                anchor: '90%',
                                value:''
                            },{
                                xtype: 'radiogroup',
                                fieldLabel: 'Pembeli',
                                columnWidth: [.5, .5],
                                name: 'pembeli',
                                id: 'pvcpk2_pembeli',
                                width: 250,
                                anchor: '90%',
                                allowBlank:false,
                                items: [{
                                    boxLabel: 'Semua pembeli',
                                    name: 'pembeli',
                                    id: 'pvcpk2_value_memberN',
                                    inputValue: '0',
                                    checked: true
                                }, {
                                    boxLabel: 'Hanya member',
                                    name: 'pembeli',
                                    inputValue: '1',
                                    id: 'pvcpk2_value_memberY'
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
    var headerPVCPK2Utama = {
        buttonAlign: 'left',
        layout: 'form',
        border: false,
        labelWidth: 100,
        defaults: { labelSeparator: ''},
        items: [headerPVCPK2Tanggal, headerPVCPK2Kategori],
        buttons: [{
            text: 'Print',
            formBind: true,
            handler: function () {
                Ext.getCmp('rpt_penjualan_vs_cogs_perkategori2').getForm().submit({
                    url: '<?= site_url("laporan_penjualan_vs_cogs_perkategori2/get_report") ?>',
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
                        clearform('rpt_penjualan_vs_cogs_perkategori2');
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
            handler: function () { clearform('rpt_penjualan_vs_cogs_perkategori2');}
        }]
    };
//-------- HEADER FORM ---------------------------

//-------- MAIN PANEL ----------------------------
    var PVCPK2 = new Ext.FormPanel({
        id: 'rpt_penjualan_vs_cogs_perkategori2',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
            bodyStyle: { margin: '0px 0px 15px 0px'},
            items: [headerPVCPK2Utama]
        }]
    });

//-------- MAIN PANEL ----------------------------
</script>