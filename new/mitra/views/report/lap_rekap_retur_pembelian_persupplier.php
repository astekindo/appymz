<?php if (!defined( 'BASEPATH')) exit( 'No direct script access allowed'); ?>
<script type="text/javascript">
    //-------- COMBOBOX SUPPLIER ---------------------
    //
    var strcblrrppsuplier = new Ext.data.ArrayStore({
        fields: ['kd_supplier'],
        data: []
    });

    // GRID PANEL TWIN COMBOBOX supplier Data Store
    var strgridlrrppsuplier = new Ext.data.Store({
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
    var searchgridlrrppsuplier = new Ext.app.SearchField({
        store: strgridlrrppsuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridlrrppsuplier'
    });

    // GRID PANEL TWIN COMBOBOX supplier
    var gridlrrppsuplier = new Ext.grid.GridPanel({
        store: strgridlrrppsuplier,
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
            items: [searchgridlrrppsuplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridlrrppsuplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function () {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    //Ext.getCmp('lpo_kd_supplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_cblrrppsuplier').setValue(sel[0].get('kd_supplier'));
                    // strlaporanpenerimaanbarang.removeAll();       
                    menulrrppsuplier.hide();
                }
            }
        }
    });

    // PANEL TWIN COMBOBOX supplier
    var menulrrppsuplier = new Ext.menu.Menu();
    menulrrppsuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridlrrppsuplier],
        buttons: [{
            text: 'Close',
            handler: function () {
                menulrrppsuplier.hide();
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
            strgridlrpbpssuplier.load();
            menulrrppsuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    //
    menulrrppsuplier.on('hide', function () {
        var sf = Ext.getCmp('id_searchgridlrrppsuplier').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgridlrrppsuplier').setValue('');
            searchgridlrrppsuplier.onTrigger2Click();
        }
    });

    // TWIN COMBOBOX supplier
    var cblrrppsuplier = new Ext.ux.TwinComboSuplier({
        fieldLabel: 'Supplier',
        id: 'id_cblrrppsuplier',
        store: strcblrpbpssuplier,
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
 
    // HEADER tanggal
    var headerlrrpptanggal = {
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
                                xtype: 'datefield',
                                fieldLabel: 'Dari Tgl </span>',
                                name: 'lrrpp_dari_tgl',
                                allowBlank: false,
                                format: 'd-m-Y',
                                editable: false,
                                id: 'id_lrrpp_dari_tgl',
                                anchor: '90%',
                                value: ''
                            },
							cblrrppsuplier
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
                                name: 'lrrpp_sampai_tgl',
                                // readOnly: true,				
                                allowBlank: false,
                                editable: false,
                                format: 'd-m-Y',
                                id: 'id_lrrpp_sampai_tgl',
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
    
    // HEADER
    var headerlaporanrekapreturpembelianpersupplier = {
        buttonAlign: 'left',
        layout: 'form',
        border: false,
        labelWidth: 100,
        defaults: {
            labelSeparator: ''
        },
        items: [headerlrrpptanggal,  {
                xtype: 'hidden',
                name: 'kd_supplier',
                id: 'lpo_kd_supplier',
                value: ''
            }

        ],
        buttons: [{
            text: 'Print',
            formBind: true,
            handler: function () {
                winlaporanrekapreturpembelianpersupplierprint.show();
                Ext.getDom('laporanrekapreturpembelianpersupplierprint').src = '<?= site_url("laporan_purchase_order/print_form") ?>';
            }
        }, {
            text: 'Cancel',
            handler: function () {
                clearlaporanrekapreturpembelianpersupplier();
            }
        }]
    };

    // PRINT
    var winlaporanrekapreturpembelianpersupplierprint = new Ext.Window({
        id: 'id_winlaporanrekapreturpembelianpersupplierprint',
        Title: 'Print Laporan Laporan Rekap Pembelian & Retur Pembelian Per Supplier',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html: '<iframe style="width:100%;height:390px;" id="laporanrekapreturpembelianpersupplierprint" src=""></iframe>'
    });

    //  FORM PANEL
    var laporanrekapreturpembelianpersupplier = new Ext.FormPanel({
        id: 'rpt_rekap_retur_pembelian_persupplier',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
            bodyStyle: {
                margin: '0px 0px 15px 0px'
            },
            items: [headerlaporanrekapreturpembelianpersupplier]
        }]
    });

    // CLEAR DATA FORM PANEL
    function clearlaporanrekapreturpembelianpersupplier() {
        Ext.getCmp('rpt_rekap_retur_pembelian_persupplier').getForm().reset();
        // strlaporanpenerimaanbarang.removeAll();
    }
</script>