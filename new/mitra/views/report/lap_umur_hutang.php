<?php if (!defined( 'BASEPATH')) exit( 'No direct script access allowed'); ?>
<script type="text/javascript">
    //-------- COMBOBOX SUPPLIER ---------------------
    //
    var strcbluhsuplier = new Ext.data.ArrayStore({
        fields: ['kd_supplier'],
        data: []
    });

    // GRID PANEL TWIN COMBOBOX supplier Data Store
    var strgridluhsuplier = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_supplier', 'nama_supplier'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("laporan_purchase_order/search_supplier") ?>',
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
    var searchgridluhsuplier = new Ext.app.SearchField({
        store: strgridluhsuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridluhsuplier'
    });

    // GRID PANEL TWIN COMBOBOX supplier
    var gridluhsuplier = new Ext.grid.GridPanel({
        store: strgridluhsuplier,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
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
            items: [searchgridluhsuplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridluhsuplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function () {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    //Ext.getCmp('lpo_kd_supplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_cbluhsuplier').setValue(sel[0].get('kd_supplier'));
                    // strlaporanpenerimaanbarang.removeAll();       
                    menuluhsuplier.hide();
                }
            }
        }
    });

    // PANEL TWIN COMBOBOX supplier
    var menuluhsuplier = new Ext.menu.Menu();
    menuluhsuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridluhsuplier],
        buttons: [{
            text: 'Close',
            handler: function () {
                menuluhsuplier.hide();
            }
        }]
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
            strgridluhsuplier.load();
            menuluhsuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    //
    menuluhsuplier.on('hide', function () {
        var sf = Ext.getCmp('id_searchgridluhsuplier').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgridluhsuplier').setValue('');
            searchgridluhsuplier.onTrigger2Click();
        }
    });

    // TWIN COMBOBOX supplier
    var cbluhsuplier = new Ext.ux.TwinComboSuplier({
        fieldLabel: 'Supplier',
        id: 'id_cbluhsuplier',
        store: strcbluhsuplier,
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

    // CHECKBOX Sort Order
    var luhsortorder = new Ext.form.Checkbox({

        xtype: 'checkbox',
        fieldLabel: 'Sort Order Tanggal',
        boxLabel: 'Descending',
        name: 'sort_order',
        id: 'id_luhsortorder',
        checked: true,
        inputValue: '1',
        autoLoad: true
    });

    // HEADER jumlah hari
    var headerjumlahhari = {
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
                        items: [{
                                xtype: 'numberfield',
                                fieldLabel: ' Jml. hari sebelum jatuh tempo',
                                name: 'luh_sblm_jth_tempo',
                                readOnly: false,
                                id: 'id_luh_sblm_jth_tempo',
                                anchor: '50%',
                                value: '',
                            }, {
                                xtype: 'numberfield',
                                fieldLabel: ' Jml. hari sesudah jatuh tempo',
                                name: 'luh_ssdh_jth_tempo',
                                readOnly: false,
                                id: 'id_luh_ssdh_jth_tempo',
                                anchor: '50%',
                                value: '',
                            },
                            luhsortorder
                        ]
                    }]
                }]
            }]
        }]
    }

    // HEADER tanggal jatuh tempo
    var headertanggaljatuhtempo = {
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
                title: 'Tanggal Jatuh Tempo',
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
                                fieldLabel: 'Dari Tgl</span>',
                                name: 'luh_dari_tgl',
                                allowBlank: true,
                                format: 'd-m-Y',
                                editable: false,
                                id: 'id_luh_dari_tgl',
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
                                name: 'luh_sampai_tgl',
                                // readOnly: true,				
                                allowBlank: true,
                                editable: false,
                                format: 'd-m-Y',
                                id: 'id_luh_smp_tgl',
                                anchor: '90%',
                                // fieldClass:'readonly-input',
                                value: ''
                            }]
                        },

                    ]
                }]
            }]
        }]
    }

    // HEADER tanggal & supplier
    var headertanggalsupplier = {
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
                            cbluhsuplier
                        ]
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
                            fieldLabel: 'Tanggal </span>',
                            name: 'luh_tgl',
                            allowBlank: true,
                            format: 'd-m-Y',
                            editable: false,
                            id: 'id_luh_tgl',
                            width: 170,
                            anchor: '90%',
                            value: ''
                        }]



                    }]
                }]
            }]
        }]

    }

    // HEADER
    var headerlaporanumurhutang = {
        buttonAlign: 'left',
        layout: 'form',
        border: false,
        labelWidth: 100,
        defaults: {
            labelSeparator: ''
        },
        items: [headertanggalsupplier, headertanggaljatuhtempo, headerjumlahhari, {}],
        buttons: [{
            text: 'Print',
            formBind: true,
            handler: function () {
                winlaporanumurhutangprint.show();
                Ext.getDom('laporanumurhutangprint').src = '<?= site_url("laporan_purchase_order/print_form") ?>';
            }
        }, {
            text: 'Cancel',
            handler: function () {
                clearlaporanumurhutang();
            }
        }]
    };

    // PRINT
    var winlaporanumurhutangprint = new Ext.Window({
        id: 'id_winlaporanumurhutangprint',
        Title: 'Print Laporan Umur Hutang',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html: '<iframe style="width:100%;height:390px;" id="laporanumurhutangprint" src=""></iframe>'
    });

    //  FORM PANEL
    var laporanumurhutang = new Ext.FormPanel({
        id: 'rpt_umur_hutang',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
            bodyStyle: {
                margin: '0px 0px 15px 0px'
            },
            items: [headerlaporanumurhutang]
        }]
    });

    // CLEAR DATA FORM PANEL
    function clearlaporanumurhutang() {
        Ext.getCmp('rpt_umur_hutang').getForm().reset();
        // strlaporanpenerimaanbarang.removeAll();
    }
</script>