<?php if (!defined( 'BASEPATH')) exit( 'No direct script access allowed'); ?>
<script type="text/javascript">
    //-------- COMBOBOX SUPPLIER ---------------------
    //
    var strcblrpbpssuplier = new Ext.data.ArrayStore({
        fields: ['kd_supplier'],
        data: []
    });

    // GRID PANEL TWIN COMBOBOX supplier Data Store
    var strgridlrpbpssuplier = new Ext.data.Store({
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
    var searchgridlrpbpssuplier = new Ext.app.SearchField({
        store: strgridlrpbpssuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridlrpbpssuplier'
    });

    // GRID PANEL TWIN COMBOBOX supplier
    var gridlrpbpssuplier = new Ext.grid.GridPanel({
        store: strgridlrpbpssuplier,
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
            items: [searchgridlrpbpssuplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridlrpbpssuplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function () {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    //Ext.getCmp('lpo_kd_supplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_cblrpbpssuplier').setValue(sel[0].get('kd_supplier'));
                    // strlaporanpenerimaanbarang.removeAll();       
                    menulrpbpssuplier.hide();
                }
            }
        }
    });

    // PANEL TWIN COMBOBOX supplier
    var menulrpbpssuplier = new Ext.menu.Menu();
    menulrpbpssuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridlrpbpssuplier],
        buttons: [{
            text: 'Close',
            handler: function () {
                menulrpbpssuplier.hide();
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
            menulrpbpssuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    //
    menulrpbpssuplier.on('hide', function () {
        var sf = Ext.getCmp('id_searchgridlrpbpssuplier').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgridlrpbpssuplier').setValue('');
            searchgridlrpbpssuplier.onTrigger2Click();
        }
    });

    // TWIN COMBOBOX supplier
    var cblrpbpssuplier = new Ext.ux.TwinComboSuplier({
        fieldLabel: 'Supplier',
        id: 'id_cblrpbpssuplier',
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
    

    // COMBOBOX kategori 1 Data Store
    var str_cblrpbpskategori1 = new Ext.data.Store({
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
            load: function () {
                var r = new(str_cblrpbpskategori1.recordType)({
                    'kd_kategori1': '',
                    'nama_kategori1': '-----'
                });
                str_cblrpbpskategori1.insert(0, r);
            },
            loadexception: function (event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    // COMBOBOX kategori 1
    var cblrpbpskategori1 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 1 ',
        id: 'id_cblrpbpskategori1',
        store: str_cblrpbpskategori1,
        valueField: 'kd_kategori1',
        displayField: 'nama_kategori1',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori1',
        emptyText: 'Pilih kategori 1',
        listeners: {
            'select': function (combo, records) {
                var kdhp_cbkategori1 = cblrpbpskategori1.getValue();
                // hp_cbkategori2.setValue();
                cblrpbpskategori2.store.proxy.conn.url = '<?= site_url("kategori3/get_kategori2") ?>/' + kdhp_cbkategori1;
                cblrpbpskategori2.store.reload();
            }
        }
    });

    // COMBOBOX kategori 2 Data Store
    var str_cblrpbpskategori2 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori2', 'nama_kategori2'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("kategori3/get_kategori2") ?>',
            method: 'POST'
        }),
        listeners: {
            load: function () {
                var r = new(str_cblrpbpskategori2.recordType)({
                    'kd_kategori2': '',
                    'nama_kategori2': '-----'
                });
                str_cblrpbpskategori2.insert(0, r);
            },
            loadexception: function (event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    // COMBOBOX kategori 2
    var cblrpbpskategori2 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 2 ',
        id: 'id_cblrpbpskategori2',
        mode: 'local',
        store: str_cblrpbpskategori2,
        valueField: 'kd_kategori2',
        displayField: 'nama_kategori2',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori2',
        emptyText: 'Pilih kategori 2',
        listeners: {
            select: function (combo, records) {
                var kd_hp_cbkategori1 = cblrpbpskategori1.getValue();
                var kd_hp_cbkategori2 = this.getValue();
                cblrpbpskategori3.setValue();
                cblrpbpskategori3.store.proxy.conn.url = '<?= site_url("kategori4/get_kategori3") ?>/' + kd_hp_cbkategori1 + '/' + kd_hp_cbkategori2;
                cblrpbpskategori3.store.reload();
            }
        }
    });

    // COMBOBOX kategori 3 Data Store
    var str_cblrpbpskategori3 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori3', 'nama_kategori3'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("kategori4/get_kategori3") ?>',
            method: 'POST'
        }),
        listeners: {
            load: function () {
                var r = new(str_cblrpbpskategori3.recordType)({
                    'kd_kategori3': '',
                    'nama_kategori3': '-----'
                });
                str_cblrpbpskategori3.insert(0, r);
            },
            loadexception: function (event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    // COMBOBOX kategori 3
    var cblrpbpskategori3 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 3 ',
        id: 'id_cblrpbpskategori3',
        mode: 'local',
        store: str_cblrpbpskategori3,
        valueField: 'kd_kategori3',
        displayField: 'nama_kategori3',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori3',
        emptyText: 'Pilih kategori 3',
        listeners: {
            select: function (combo, records) {
                var kd_hp_cbkategori1 = cblrpbpskategori1.getValue();
                var kd_hp_cbkategori2 = cblrpbpskategori2.getValue();
                var kd_hp_cbkategori3 = this.getValue();
                cblrpbpskategori4.setValue();
                cblrpbpskategori4.store.proxy.conn.url = '<?= site_url("master_barang/get_kategori4") ?>/' + kd_hp_cbkategori1 + '/' + kd_hp_cbkategori2 + '/' + kd_hp_cbkategori3;
                cblrpbpskategori4.store.reload();
            }
        }
    });

    // COMBOBOX kategori 4 Data Store
    var str_cblrpbpskategori4 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori4', 'nama_kategori4'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_barang/get_kategori4") ?>',
            method: 'POST'
        }),
        listeners: {
            load: function () {
                var r = new(str_cblrpbpskategori4.recordType)({
                    'kd_kategori4': '',
                    'nama_kategori4': '-----'
                });
                str_cblrpbpskategori4.insert(0, r);
            },
            loadexception: function (event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    // COMBOBOX kategori 4
    var cblrpbpskategori4 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 4',
        id: 'id_cblrpbpskategori4',
        mode: 'local',
        store: str_cblrpbpskategori4,
        valueField: 'kd_kategori4',
        displayField: 'nama_kategori4',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori4',
        emptyText: 'Pilih kategori 4'
    });

    // CHECKBOX is Konsinyasi
    var lrpbpsiskonsinyasi = new Ext.form.Checkbox({
        xtype: 'checkbox',
        fieldLabel: 'Is Konsinyasi',
        boxLabel: 'Ya',
        name: 'is_null',
        id: 'id_lrpbpsiskonsinyasi',
        checked: true,
        inputValue: '1',
        autoLoad: true
    });

    // CHECKBOX Sort Order
    var lrpbpssortorder = new Ext.form.Checkbox({
        xtype: 'checkbox',
        fieldLabel: 'Sort Order Kode Supplier',
        boxLabel: 'Descending',
        name: 'sort_order',
        id: 'id_lrpbpssortorder',
        checked: true,
        inputValue: '1',
        autoLoad: true
    });

    // HEADER tanggal
    var headerlrpbpstanggal = {
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
                                fieldLabel: 'Dari Tgl ',
                                name: 'lrpbps_dari_tgl',
                                allowBlank: false,
                                format: 'd-m-Y',
                                editable: false,
                                id: 'id_lrpbps_dari_tgl',
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
                                name: 'lrpbps_sampai_tgl',
                                // readOnly: true,				
                                allowBlank: false,
                                editable: false,
                                format: 'd-m-Y',
                                id: 'id_lrpbps_smp_tgl',
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

    // HEADER kategori
    var headerlrpbpskategori = {
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
                            cblrpbpskategori1,
                            cblrpbpskategori2,
                            cblrpbpskategori3,
                            cblrpbpskategori4
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
                            cblrpbpssuplier,
                            lrpbpsiskonsinyasi,
                            lrpbpssortorder
                        ]



                    }]
                }]
            }]
        }]

    }

    // HEADER
    var headerlaporanrekappenerimaanbarangpersupplier = {
        buttonAlign: 'left',
        layout: 'form',
        border: false,
        labelWidth: 100,
        defaults: {
            labelSeparator: ''
        },
        items: [headerlrpbpstanggal, headerlrpbpskategori, {
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
                winlaporanrekappenerimaanbarangpersupplierprint.show();
                Ext.getDom('laporanrekappenerimaanbarangpersupplierprint').src = '<?= site_url("laporan_purchase_order/print_form") ?>';
            }
        }, {
            text: 'Cancel',
            handler: function () {
                clearlaporanrekappenerimaanbarangpersupplier();
            }
        }]
    };

    // PRINT
    var winlaporanrekappenerimaanbarangpersupplierprint = new Ext.Window({
        id: 'id_winlaporanrekappenerimaanbarangpersupplierprint',
        Title: 'Print Laporan Rekap Penerimaan Barang Per Supplier',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html: '<iframe style="width:100%;height:390px;" id="laporanrekappenerimaanbarangpersupplierprint" src=""></iframe>'
    });

    //  FORM PANEL
    var laporanrekappenerimaanbarangpersupplier = new Ext.FormPanel({
        id: 'rpt_rekap_penerimaan_barang_persupplier',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
            bodyStyle: {
                margin: '0px 0px 15px 0px'
            },
            items: [headerlaporanrekappenerimaanbarangpersupplier]
        }]
    });

    // CLEAR DATA FORM PANEL
    function clearlaporanrekappenerimaanbarangpersupplier() {
        Ext.getCmp('rpt_rekap_penerimaan_barang_persupplier').getForm().reset();
        // strlaporanpenerimaanbarang.removeAll();
    }
</script>