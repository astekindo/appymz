<?php if (!defined( 'BASEPATH')) exit( 'No direct script access allowed'); ?>
<script type="text/javascript">

//-------- COMBOBOX KATEGORI ---------------------

    var smGridPK1Kategori = new Ext.grid.CheckboxSelectionModel();

    var strReportPVCPK1Kategori = new Ext.data.ArrayStore({
        fields: ['kd_kategori1', 'nama_kategori1'],
        data: []
    });

    // GRID PANEL TWIN COMBOBOX kategori1 Data Store
    var strGridReportPVCPK1Kategori = new Ext.data.Store({
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

    strGridReportPVCPK1Kategori.on('load', function(){
        Ext.getCmp('id_searchGridReportPVCPK1Kategori').focus();
    });

    // SEARCH GRID PANEL TWIN COMBOBOX kategori1
    var searchGridReportPVCPK1Kategori = new Ext.app.SearchField({
        store: strGridReportPVCPK1Kategori,
        width: 350,
        id: 'id_searchGridReportPVCPK1Kategori'
    });

    // GRID PANEL TWIN COMBOBOX kategori1
    var GridReportPVCPK1Kategori = new Ext.grid.GridPanel({
        store: strGridReportPVCPK1Kategori,
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
            items: [searchGridReportPVCPK1Kategori]
        })
    });

    var menuReportPVCPK1Kategori = new Ext.menu.Menu();

    menuReportPVCPK1Kategori.add(new Ext.Panel({
        title: 'Pilih kategori1',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [GridReportPVCPK1Kategori],
        buttons: [{
            text: 'Done',
            handler: function () {
                var sm  = GridReportPVCPK1Kategori.getSelectionModel();
                var sel = sm.getSelections();
                if(sel.length > 0) {
                    var kd_list = '';
                    for (i = 0; i < sel.length; i++) {
                        kd_list = kd_list + sel[i].get('kd_kategori1') + ',';
                    }
                    // console.log(kd_list);
                    kd_list = kd_list.substring(0, kd_list.length-1);
                    Ext.getCmp('id_lpvcpk1_kd_kategori1_sel').setValue(kd_list);
                    menuReportPVCPK1Kategori.hide();
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
                menuReportPVCPK1Kategori.hide();
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
            strGridReportPVCPK1Kategori.load();
            menuReportPVCPK1Kategori.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menuReportPVCPK1Kategori.on('hide', function () {
        var sf = Ext.getCmp('id_searchGridReportPVCPK1Kategori').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchGridReportPVCPK1Kategori').setValue('');
            searchGridReportPVCPK1Kategori.onTrigger2Click();
        }
    });

    // TWIN COMBOBOX kategori1
    var comboPVCPK1Kategori = new Ext.ux.TwinComboSuplier({
        fieldLabel: 'Kategori',
        id: 'id_cbReportPVCPK1Kategori',
        store: strReportPVCPK1Kategori,
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
    var sortReportPVCPK1 = new Ext.form.Checkbox({
        xtype: 'checkbox',
        fieldLabel: 'Sort Order',
        boxLabel: 'Descending',
        name: 'sort_order',
        id: 'id_sortReportPVCPK1',
        checked: true,
        inputValue: '1',
        autoLoad: true
    });
//-------- CHECKBOX SORT ORDER -------------------

//-------- HEADER TANGGAL ------------------------
    var headerReportPVCPK1Tanggal = {
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
                            name: 'lpvcpk1_dari_tgl',
                            allowBlank: false,
                            format: 'd-m-Y',
                            editable: false,
                            id: 'id_lpvcpk1_dari_tgl',
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
                            name: 'lpvcpk1_sampai_tgl',
                            // readOnly: true,
                            allowBlank: false,
                            editable: false,
                            format: 'd-m-Y',
                            id: 'id_lpvcpk1_smp_tgl',
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
    var headerReportPVCPK1Kategori = {
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
                            comboPVCPK1Kategori,
                            sortReportPVCPK1
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
                                id: 'id_lpvcpk1_kd_kategori1_sel',
                                anchor: '90%',
                                value:''
                            },{
                                xtype: 'radiogroup',
                                fieldLabel: 'Pembeli',
                                columnWidth: [.5, .5],
                                name: 'pembeli',
                                id: 'lpvcpk1_pembeli',
                                width: 250,
                                anchor: '90%',
                                allowBlank:false,
                                items: [{
                                    boxLabel: 'Semua pembeli',
                                    name: 'pembeli',
                                    id: 'lpvcpk1_value_memberN',
                                    inputValue: '0',
                                    checked: true
                                }, {
                                    boxLabel: 'Hanya member',
                                    name: 'pembeli',
                                    inputValue: '1',
                                    id: 'lpvcpk1_value_memberY'
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
    var headerReportPVCPK1Utama = {
        buttonAlign: 'left',
        layout: 'form',
        border: false,
        labelWidth: 100,
        defaults: { labelSeparator: ''},
        items: [headerReportPVCPK1Tanggal, headerReportPVCPK1Kategori],
        buttons: [{
            text: 'Print',
            formBind: true,
            handler: function () {
                Ext.getCmp('rpt_penjualan_vs_cogs_perkategori1').getForm().submit({
                    url: '<?= site_url("laporan_penjualan_per_kategori1/get_report") ?>',
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
                        clearform('rpt_penjualan_vs_cogs_perkategori1');
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
            handler: function () { clearform('rpt_penjualan_vs_cogs_perkategori1');}
        }]
    };
//-------- HEADER FORM ---------------------------

//-------- MAIN PANEL ----------------------------
    var ReportPVCPK1 = new Ext.FormPanel({
        id: 'rpt_penjualan_vs_cogs_perkategori1',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
            bodyStyle: { margin: '0px 0px 15px 0px'},
            items: [headerReportPVCPK1Utama]
        }]
    });

    // // CLEAR DATA FORM PANEL
    // function clearform(id) {
    //     Ext.getCmp(id).getForm().reset();
    // }
//-------- MAIN PANEL ----------------------------
</script>