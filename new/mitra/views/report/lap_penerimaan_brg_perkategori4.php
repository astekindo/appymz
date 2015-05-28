<?php if (!defined( 'BASEPATH')) exit( 'No direct script access allowed'); ?>
<script type="text/javascript">
    //-------- COMBOBOX SUPPLIER ---------------------
    //
    var strcblpbp4suplier = new Ext.data.ArrayStore({
        fields: ['kd_supplier'],
        data: []
    });

    // GRID PANEL TWIN COMBOBOX supplier Data Store
    var strgridlpbp4suplier = new Ext.data.Store({
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
    var searchgridlpbp4suplier = new Ext.app.SearchField({
        store: strgridlpbp4suplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridlpbp4suplier'
    });

    // GRID PANEL TWIN COMBOBOX supplier
    var gridlpbp3suplier = new Ext.grid.GridPanel({
        store: strgridlpbp4suplier,
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
            items: [searchgridlpbp4suplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridlpbp4suplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function () {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    //Ext.getCmp('lpo_kd_supplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_cblpbp4suplier').setValue(sel[0].get('kd_supplier'));
                    // strlaporanpenerimaanbarang.removeAll();       
                    menulpbp4suplier.hide();
                }
            }
        }
    });

    // PANEL TWIN COMBOBOX supplier
    var menulpbp4suplier = new Ext.menu.Menu();
    menulpbp4suplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridlpbp3suplier],
        buttons: [{
            text: 'Close',
            handler: function () {
                menulpbp4suplier.hide();
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
            strgridlpbp4suplier.load();
            menulpbp4suplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    //
    menulpbp4suplier.on('hide', function () {
        var sf = Ext.getCmp('id_searchgridlpbp4suplier').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgridlpbp4suplier').setValue('');
            searchgridlpbp4suplier.onTrigger2Click();
        }
    });

    // TWIN COMBOBOX supplier
    var cblpbp4suplier = new Ext.ux.TwinComboSuplier({
        fieldLabel: 'Supplier',
        id: 'id_cblpbp4suplier',
        store: strcblpbp4suplier,
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
    var str_cblpbp4kategori1 = new Ext.data.Store({
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
                var r = new(str_cblpbp4kategori1.recordType)({
                    'kd_kategori1': '',
                    'nama_kategori1': '-----'
                });
                str_cblpbp2kategori1.insert(0, r);
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
    var cblpbp4kategori1 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 1',
        id: 'id_cblpbp4kategori1',
        store: str_cblpbp4kategori1,
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
                var kdhp_cbkategori1 = cblpbp4kategori1.getValue();
                // hp_cbkategori2.setValue();
                cblpbp4kategori2.store.proxy.conn.url = '<?= site_url("kategori3/get_kategori2") ?>/' + kdhp_cbkategori1;
                cblpbp4kategori2.store.reload();
            }
        }
    });

    // COMBOBOX kategori 2 Data Store
    var str_cblpbp4kategori2 = new Ext.data.Store({
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
                var r = new(str_cblpbp4kategori2.recordType)({
                    'kd_kategori2': '',
                    'nama_kategori2': '-----'
                });
                str_cblpbp4kategori2.insert(0, r);
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
    var cblpbp4kategori2 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 2',
        id: 'id_cblpbp4kategori2',
        mode: 'local',
        store: str_cblpbp4kategori2,
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
                var kd_hp_cbkategori1 = cblpbp4kategori1.getValue();
                var kd_hp_cbkategori2 = this.getValue();
                cblpbp4kategori3.setValue();
                cblpbp4kategori3.store.proxy.conn.url = '<?= site_url("kategori4/get_kategori3") ?>/' + kd_hp_cbkategori1 + '/' + kd_hp_cbkategori2;
                cblpbp4kategori3.store.reload();
            }
        }
    });

    // COMBOBOX kategori 3 Data Store
    var str_cblpbp4kategori3 = new Ext.data.Store({
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
                var r = new(str_cblpbp4kategori3.recordType)({
                    'kd_kategori3': '',
                    'nama_kategori3': '-----'
                });
                str_cblpbp4kategori3.insert(0, r);
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
    var cblpbp4kategori3 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 3',
        id: 'id_cblpbp4kategori3',
        mode: 'local',
        store: str_cblpbp4kategori3,
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
                var kd_hp_cbkategori1 = cblpbp4kategori1.getValue();
                var kd_hp_cbkategori2 = cblpbp4kategori2.getValue();
                var kd_hp_cbkategori3 = this.getValue();
                cblpbp4kategori4.setValue();
                cblpbp4kategori4.store.proxy.conn.url = '<?= site_url("master_barang/get_kategori4") ?>/' + kd_hp_cbkategori1 + '/' + kd_hp_cbkategori2 + '/' + kd_hp_cbkategori3;
                cblpbp4kategori4.store.reload();
            }
        }
    });

    // COMBOBOX kategori 4 Data Store
    var str_cblpbp4kategori4 = new Ext.data.Store({
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
                var r = new(str_cblpbp4kategori4.recordType)({
                    'kd_kategori4': '',
                    'nama_kategori4': '-----'
                });
                str_cblpbp4kategori4.insert(0, r);
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
    var cblpbp4kategori4 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 4 ',
        id: 'id_cblpbp4kategori4',
        mode: 'local',
        store: str_cblpbp4kategori4,
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
	
	
    // CHECKBOX Sort Order
    var lpbp4sortorder = new Ext.form.Checkbox({
        xtype: 'checkbox',
        fieldLabel: 'Sort Order Kategori 4',
        boxLabel: 'Descending',
        name: 'sort_order',
        id: 'id_lpbp4sortorder',
        checked: true,
        inputValue: '1',
        autoLoad: true
    });

	var valcblpbp4status=[
		['D',"Distribusi"],
		['B',"Bazar"],
		['S',"Supermarket"]
	];
    
    var strcblpbp4status = new Ext.data.ArrayStore({
        fields: [
            {name: 'key'},
            {name: 'value'}
        ],
        data:valcblpbp4status
    });

	// COMBOBOX status
    var cblpbp4status = new Ext.form.ComboBox({
        fieldLabel: 'Status',
        id: 'cblpbp4status',
        name:'status',
        // allowBlank:false,
        store: strcblpbp4status,
		valueField:'key',
		displayField:'value',
		mode:'local',
        forceSelection: true,
        triggerAction: 'all',
		anchor: '90%'
    });

    // HEADER tanggal
    var headerlpbp4tanggal = {
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
                                name: 'lpbp4_dari_tgl',
                                allowBlank: false,
                                format: 'd-m-Y',
                                editable: false,
                                id: 'id_lpbp4_dari_tgl',
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
                                name: 'lpbp4_sampai_tgl',
                                // readOnly: true,				
                                allowBlank: false,
                                editable: false,
                                format: 'd-m-Y',
                                id: 'id_lpbp4_smp_tgl',
                                anchor: '90%',
                                // fieldClass:'readonly-input',
                                value: ''
                            }]
                        }

                    ]
                }]
            }]
        }]
    }

    // HEADER kategori
    var headerlpbp4kategori = {
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
							cblpbp4kategori1,
							cblpbp4kategori2,
							cblpbp4kategori3,
							cblpbp4kategori4,
							cblpbp4suplier
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
							cblpbp4status,
                            lpbp4sortorder
                        ]

                    }]
                }]
            }]
        }]

    }

    // HEADER
    var headerlaporanpenerimaanbrgperkategori4 = {
        buttonAlign: 'left',
        layout: 'form',
        border: false,
        labelWidth: 100,
        defaults: {
            labelSeparator: ''
        },
        items: [headerlpbp4tanggal, headerlpbp4kategori

        ],
        buttons: [{
            text: 'Print',
            formBind: true,
            handler: function () {
                winlaporanpenerimaanbrgperkategori4print.show();
                Ext.getDom('laporanpenerimaanbrgperkategori4print').src = '<?= site_url("laporan_purchase_order/print_form") ?>';
            }
        }, {
            text: 'Cancel',
            handler: function () {
                clearlaporanpenerimaanbrgperkategori4();
            }
        }]
    };

    // PRINT
    var winlaporanpenerimaanbrgperkategori4print = new Ext.Window({
        id: 'id_winlaporanpenerimaanbrgperkategori4print',
        Title: 'Print Laporan Penerimaan Barang Per Kategori 4',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html: '<iframe style="width:100%;height:390px;" id="laporanpenerimaanbrgperkategori4print" src=""></iframe>'
    });

    //  FORM PANEL
    var laporanpenerimaanbrgperkategori4 = new Ext.FormPanel({
        id: 'rpt_penerimaan_brg_perkategori4',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
            bodyStyle: {
                margin: '0px 0px 15px 0px'
            },
            items: [headerlaporanpenerimaanbrgperkategori4]
        }]
    });

    // CLEAR DATA FORM PANEL
    function clearlaporanpenerimaanbrgperkategori4() {
        Ext.getCmp('rpt_penerimaan_brg_perkategori4').getForm().reset();
        // strlaporanpenerimaanbarang.removeAll();
    }
</script>