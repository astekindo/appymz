<?php if (!defined( 'BASEPATH')) exit( 'No direct script access allowed'); ?>
<script type="text/javascript">
    //-------- COMBOBOX SUPPLIER ---------------------
    //
    var strcblohpsuplier = new Ext.data.ArrayStore({
        fields: ['kd_supplier'],
        data: []
    });

    // GRID PANEL TWIN COMBOBOX supplier Data Store
    var strgridlohpsuplier = new Ext.data.Store({
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
    var searchgridlohpsuplier = new Ext.app.SearchField({
        store: strgridlohpsuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridlohpsuplier'
    });

    // GRID PANEL TWIN COMBOBOX supplier
    var gridlohpsuplier = new Ext.grid.GridPanel({
        store: strgridlohpsuplier,
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
            items: [searchgridlohpsuplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridlohpsuplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function () {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    //Ext.getCmp('lpo_kd_supplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_cblohpsuplier').setValue(sel[0].get('kd_supplier'));
                    // strlaporanpenerimaanbarang.removeAll();       
                    menulohpsuplier.hide();
                }
            }
        }
    });

    // PANEL TWIN COMBOBOX supplier
    var menulohpsuplier = new Ext.menu.Menu();
    menulohpsuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridlohpsuplier],
        buttons: [{
            text: 'Close',
            handler: function () {
                menulohpsuplier.hide();
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
            strgridlohpsuplier.load();
            menulohpsuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    //
    menulohpsuplier.on('hide', function () {
        var sf = Ext.getCmp('id_searchgridlohpsuplier').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgridlohpsuplier').setValue('');
            searchgridlohpsuplier.onTrigger2Click();
        }
    });

    // TWIN COMBOBOX supplier
    var cblohpsuplier = new Ext.ux.TwinComboSuplier({
        fieldLabel: 'Supplier',
        id: 'id_cblohpsuplier',
        store: strcblohpsuplier,
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
    var lohpsortorder = new Ext.form.Checkbox({

        xtype: 'checkbox',
        fieldLabel: 'Sort Order Tanggal',
        boxLabel: 'Descending',
        name: 'sort_order',
        id: 'id_lohpsortorder',
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
                                name: 'lohp_dari_tgl',
                                allowBlank: true,
                                format: 'd-m-Y',
                                editable: false,
                                id: 'id_lohp_dari_tgl',
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
                                name: 'lohp_sampai_tgl',
                                // readOnly: true,				
                                allowBlank: true,
                                editable: false,
                                format: 'd-m-Y',
                                id: 'id_lohp_smp_tgl',
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
								name: 'lohp_tgl',
								allowBlank: true,
								format: 'd-m-Y',
								editable: false,
								id: 'id_lohp_tgl',
								width: 170,
								anchor: '90%',
								value: ''
							},
                            cblohpsuplier
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
							lohpsortorder
						]



                    }]
                }]
            }]
        }]

    }

    // HEADER
    var headerlaporanoutstandinghutangpersupplier = {
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
                winlaporanoutstandinghutangpersupplierprint.show();
                Ext.getDom('laporanoutstandinghutangpersupplierprint').src = '<?= site_url("laporan_purchase_order/print_form") ?>';
            }
        }, {
            text: 'Cancel',
            handler: function () {
                clearlaporanoutstandinghutangpersupplier();
            }
        }]
    };

    // PRINT
    var winlaporanoutstandinghutangpersupplierprint = new Ext.Window({
        id: 'id_winlaporanoutstandinghutangpersupplierprint',
        Title: 'Print Laporan Laporan Outstanding Hutang Per Supplier',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html: '<iframe style="width:100%;height:390px;" id="laporanoutstandinghutangpersupplierprint" src=""></iframe>'
    });

    //  FORM PANEL
    var laporanoutstandinghutangpersupplier = new Ext.FormPanel({
        id: 'rpt_outstanding_hutang_persupplier',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
            bodyStyle: {
                margin: '0px 0px 15px 0px'
            },
            items: [headerlaporanoutstandinghutangpersupplier]
        }]
    });

    // CLEAR DATA FORM PANEL
    function clearlaporanoutstandinghutangpersupplier() {
        Ext.getCmp('rpt_outstanding_hutang_persupplier').getForm().reset();
        // strlaporanpenerimaanbarang.removeAll();
    }
</script>