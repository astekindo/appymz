<?php if (!defined( 'BASEPATH')) exit( 'No direct script access allowed'); ?>
<script type="text/javascript">
	
	//COMBOBOX RO (belum ada isinya)
	var cblfrpro = new Ext.ux.TwinComboSuplier({
        fieldLabel: 'Request Order',
        id: 'id_cblfrpro',
        store: '',
        mode: 'local',
        valueField: 'kd_ro',
        displayField: 'kd_ro',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_ro',
        emptyText: 'Pilih Request Order'
    });
	
	//COMBOBOX Faktur (belum ada isinya)
	var cblfrpfaktur = new Ext.ux.TwinComboSuplier({
        fieldLabel: 'Nomor Faktur',
        id: 'id_cblfrpfaktur',
        store: '',
        mode: 'local',
        valueField: 'kd_faktur',
        displayField: 'kd_faktur',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_faktur',
        emptyText: 'Pilih Nomor Faktur'
    });
	
	//COMBOBOX PO (belum ada isinya)
	var cblfrppo = new Ext.ux.TwinComboSuplier({
        fieldLabel: 'Purchase Order',
        id: 'id_cblfrppo',
        store: '',
        mode: 'local',
        valueField: 'kd_po',
        displayField: 'kd_po',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_po',
        emptyText: 'Pilih Purchase Order'
    });
	

    //-------- COMBOBOX SUPPLIER ---------------------
    //
    var strcblfrpsuplier = new Ext.data.ArrayStore({
        fields: ['kd_supplier'],
        data: []
    });

    // GRID PANEL TWIN COMBOBOX supplier Data Store
    var strgridlfrpsuplier = new Ext.data.Store({
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
    var searchgridlfrpsuplier = new Ext.app.SearchField({
        store: strgridlfrpsuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridlfrpsuplier'
    });

    // GRID PANEL TWIN COMBOBOX supplier
    var gridlfrpsuplier = new Ext.grid.GridPanel({
        store: strgridlfrpsuplier,
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
            items: [searchgridlfrpsuplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridlfrpsuplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function () {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    //Ext.getCmp('lpo_kd_supplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_cblfrpsuplier').setValue(sel[0].get('kd_supplier'));
                    // strlaporanpenerimaanbarang.removeAll();       
                    menulfrpsuplier.hide();
                }
            }
        }
    });

    // PANEL TWIN COMBOBOX supplier
    var menulfrpsuplier = new Ext.menu.Menu();
    menulfrpsuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridlfrpsuplier],
        buttons: [{
            text: 'Close',
            handler: function () {
                menulfrpsuplier.hide();
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
            strgridlfrpsuplier.load();
            menulfrpsuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    //
    menulfrpsuplier.on('hide', function () {
        var sf = Ext.getCmp('id_searchgridlfrpsuplier').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgridlfrpsuplier').setValue('');
            searchgridlfrpsuplier.onTrigger2Click();
        }
    });

    // TWIN COMBOBOX supplier
    var cblfrpsuplier = new Ext.ux.TwinComboSuplier({
        fieldLabel: 'Supplier',
        id: 'id_cblfrpsuplier',
        store: strcblfrpsuplier,
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
    var lfrpsortorder = new Ext.form.Checkbox({

        xtype: 'checkbox',
        fieldLabel: 'Sort Order Tanggal',
        boxLabel: 'Descending',
        name: 'sort_order',
        id: 'id_lfrpsortorder',
        checked: true,
        inputValue: '1',
        autoLoad: true
    });

    // HEADER jatuhtempo
    var headerjatuhtempo = {
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
                        items: [
							{
                                xtype: 'datefield',
                                fieldLabel: 'Dari Tgl</span>',
                                name: 'lfrp_dari_tgl_jthtempo',
                                allowBlank: true,
                                format: 'd-m-Y',
                                editable: false,
                                id: 'id_lfrp_dari_tgl_jthtempo',
                                anchor: '90%',
                                value: ''
                            }
							
                        ]
                    },
					{


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
                                fieldLabel: 'Sampai Tgl</span>',
                                name: 'lfrp_smp_tgl_jthtempo',
                                allowBlank: true,
                                format: 'd-m-Y',
                                editable: false,
                                id: 'id_lfrp_smp_tgl_jthtempo',
                                anchor: '90%',
                                value: ''
                            }
                        ]
					}]
                }]
            }]
        }]
    }

    // HEADER tanggal
    var headertanggal = {
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
                title: '',
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
                                name: 'lfrp_dari_tgl',
                                allowBlank: true,
                                format: 'd-m-Y',
                                editable: false,
                                id: 'id_lfrp_dari_tgl',
                                anchor: '90%',
                                value: ''
                            },
							{
                                xtype: 'datefield',
                                fieldLabel: 'Sampai Tgl',
                                name: 'lfrp_sampai_tgl',
                                // readOnly: true,				
                                allowBlank: true,
                                editable: false,
                                format: 'd-m-Y',
                                id: 'id_lfrp_smp_tgl',
                                anchor: '90%',
                                // fieldClass:'readonly-input',
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
                            items: [
								lfrpsortorder
							]
                        },

                    ]
                }]
            }]
        }]
    }

	
    // HEADER supplier
    var headersupplier = {
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
                title: '',
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
							cblfrpsuplier,
							cblfrpfaktur,
							cblfrppo,
							cblfrpro
							
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
							
							]
                        },

                    ]
                }]
            }]
        }]
    }

   
    // HEADER
    var headerlaporanfakturreturpembelian = {
        buttonAlign: 'left',
        layout: 'form',
        border: false,
        labelWidth: 100,
        defaults: {
            labelSeparator: ''
        },
        items: [headertanggal, headersupplier, headerjatuhtempo, {}],
        buttons: [{
            text: 'Print',
            formBind: true,
            handler: function () {
                winlaporanfakturreturpembelianprint.show();
                Ext.getDom('laporanfakturreturpembelianprint').src = '<?= site_url("laporan_purchase_order/print_form") ?>';
            }
        }, {
            text: 'Cancel',
            handler: function () {
                clearlaporanfakturreturpembelian();
            }
        }]
    };

    // PRINT
    var winlaporanfakturreturpembelianprint = new Ext.Window({
        id: 'id_winlaporanfakturreturpembelianprint',
        Title: 'Print Laporan Purchase Order',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html: '<iframe style="width:100%;height:390px;" id="laporanfakturreturpembelianprint" src=""></iframe>'
    });

    //  FORM PANEL
    var laporanfakturreturpembelian = new Ext.FormPanel({
        id: 'rpt_faktur_retur_pembelian',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
            bodyStyle: {
                margin: '0px 0px 15px 0px'
            },
            items: [headerlaporanfakturreturpembelian]
        }]
    });

    // CLEAR DATA FORM PANEL
    function clearlaporanfakturreturpembelian() {
        Ext.getCmp('rpt_faktur_retur_pembelian').getForm().reset();
        // strlaporanpenerimaanbarang.removeAll();
    }
</script>