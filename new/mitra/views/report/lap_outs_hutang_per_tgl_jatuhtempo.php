<?php if (!defined( 'BASEPATH')) exit( 'No direct script access allowed'); ?>
<script type="text/javascript">
    //-------- COMBOBOX SUPPLIER ---------------------
    //
    var strcblohjtsuplier = new Ext.data.ArrayStore({
        fields: ['kd_supplier'],
        data: []
    });

    // GRID PANEL TWIN COMBOBOX supplier Data Store
    var strgridlohjtsuplier = new Ext.data.Store({
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
    var searchgridlohjtsuplier = new Ext.app.SearchField({
        store: strgridlohjtsuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridlohjtsuplier'
    });

    // GRID PANEL TWIN COMBOBOX supplier
    var gridlohjtsuplier = new Ext.grid.GridPanel({
        store: strgridlohjtsuplier,
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
            items: [searchgridlohjtsuplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridlohjtsuplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function () {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    //Ext.getCmp('lpo_kd_supplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_cblohjtsuplier').setValue(sel[0].get('kd_supplier'));
                    // strlaporanpenerimaanbarang.removeAll();       
                    menulohjtsuplier.hide();
                }
            }
        }
    });

    // PANEL TWIN COMBOBOX supplier
    var menulohjtsuplier = new Ext.menu.Menu();
    menulohjtsuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridlohjtsuplier],
        buttons: [{
            text: 'Close',
            handler: function () {
                menulohjtsuplier.hide();
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
            strgridlohjtsuplier.load();
            menulohjtsuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    //
    menulohjtsuplier.on('hide', function () {
        var sf = Ext.getCmp('id_searchgridlohjtsuplier').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgridlohjtsuplier').setValue('');
            searchgridlohjtsuplier.onTrigger2Click();
        }
    });

    // TWIN COMBOBOX supplier
    var cblohjtsuplier = new Ext.ux.TwinComboSuplier({
        fieldLabel: 'Supplier',
        id: 'id_cblohjtsuplier',
        store: strcblohjtsuplier,
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
    var lohjtsortorder = new Ext.form.Checkbox({

        xtype: 'checkbox',
        fieldLabel: 'Sort Order Tanggal',
        boxLabel: 'Descending',
        name: 'sort_order',
        id: 'id_lohjtsortorder',
        checked: true,
        inputValue: '1',
        autoLoad: true
    });
    
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
                                name: 'lohjt_dari_tgl',
                                allowBlank: true,
                                format: 'd-m-Y',
                                editable: false,
                                id: 'id_lohjt_dari_tgl',
                                anchor: '90%',
                                value: ''
                            }							
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
                                fieldLabel: 'Sampai Tgl',
                                name: 'lohjt_sampai_tgl',
                                // readOnly: true,				
                                allowBlank: true,
                                editable: false,
                                format: 'd-m-Y',
                                id: 'id_lohjt_smp_tgl',
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
							{
								xtype: 'datefield',
								fieldLabel: 'Tanggal </span>',
								name: 'lohjt_tgl',
								allowBlank: true,
								format: 'd-m-Y',
								editable: false,
								id: 'id_lohjt_tgl',
								width: 170,
								anchor: '90%',
								value: ''
							},
                            cblohjtsuplier
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
							lohjtsortorder
						]



                    }]
                }]
            }]
        }]

    }

    // HEADER
    var headerlaporanoutstandinghutangperjatuhtempo = {
        buttonAlign: 'left',
        layout: 'form',
        border: false,
        labelWidth: 100,
        defaults: {
            labelSeparator: ''
        },
        items: [headertanggalsupplier, headertanggaljatuhtempo, {}],
        buttons: [{
            text: 'Print',
            formBind: true,
            handler: function () {
                winlaporanoutstandinghutangperjatuhtempoprint.show();
                Ext.getDom('laporanoutstandinghutangperjatuhtempoprint').src = '<?= site_url("laporan_purchase_order/print_form") ?>';
            }
        }, {
            text: 'Cancel',
            handler: function () {
                clearlaporanoutstandinghutangperjatuhtempo();
            }
        }]
    };

    // PRINT
    var winlaporanoutstandinghutangperjatuhtempoprint = new Ext.Window({
        id: 'id_winlaporanoutstandinghutangperjatuhtempoprint',
        Title: 'Print Laporan Outstanding Hutang Per Tanggal Jatuh Tempo',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html: '<iframe style="width:100%;height:390px;" id="laporanoutstandinghutangperjatuhtempoprint" src=""></iframe>'
    });

    //  FORM PANEL
    var laporanoutstandinghutangperjatuhtempo = new Ext.FormPanel({
        id: 'rpt_outs_hutang_per_tgl_jatuhtempo',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
            bodyStyle: {
                margin: '0px 0px 15px 0px'
            },
            items: [headerlaporanoutstandinghutangperjatuhtempo]
        }]
    });

    // CLEAR DATA FORM PANEL
    function clearlaporanoutstandinghutangperjatuhtempo() {
        Ext.getCmp('rpt_outs_hutang_per_tgl_jatuhtempo').getForm().reset();
        // strlaporanpenerimaanbarang.removeAll();
    }
</script>