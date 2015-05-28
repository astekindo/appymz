<?php if (!defined( 'BASEPATH')) exit( 'No direct script access allowed'); ?>
<script type="text/javascript">
    //-------- COMBOBOX SUPPLIER ---------------------
    //
    var strcblpbp3suplier = new Ext.data.ArrayStore({
        fields: ['kd_supplier'],
        data: []
    });

    // GRID PANEL TWIN COMBOBOX supplier Data Store
    var strgridlpbp3suplier = new Ext.data.Store({
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
    var searchgridlpbp3suplier = new Ext.app.SearchField({
        store: strgridlpbp3suplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridlpbp3suplier'
    });

    // GRID PANEL TWIN COMBOBOX supplier
    var gridlpbp3suplier = new Ext.grid.GridPanel({
        store: strgridlpbp3suplier,
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
            items: [searchgridlpbp3suplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridlpbp3suplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function () {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    //Ext.getCmp('lpo_kd_supplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_cblpbp3suplier').setValue(sel[0].get('kd_supplier'));
                    // strlaporanpenerimaanbarang.removeAll();       
                    menulpbp3suplier.hide();
                }
            }
        }
    });

    // PANEL TWIN COMBOBOX supplier
    var menulpbp3suplier = new Ext.menu.Menu();
    menulpbp3suplier.add(new Ext.Panel({
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
                menulpbp3suplier.hide();
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
            strgridlpbp3suplier.load();
            menulpbp3suplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    //
    menulpbp3suplier.on('hide', function () {
        var sf = Ext.getCmp('id_searchgridlpbp3suplier').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgridlpbp3suplier').setValue('');
            searchgridlpbp3suplier.onTrigger2Click();
        }
    });

    // TWIN COMBOBOX supplier
    var cblpbp3suplier = new Ext.ux.TwinComboSuplier({
        fieldLabel: 'Supplier',
        id: 'id_cblpbp3suplier',
        store: strcblpbp3suplier,
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
    var str_cblpbp3kategori1 = new Ext.data.Store({
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
                var r = new(str_cblpbp3kategori1.recordType)({
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
    var cblpbp3kategori1 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 1',
        id: 'id_cblpbp3kategori1',
        store: str_cblpbp3kategori1,
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
                var kdhp_cbkategori1 = cblpbp3kategori1.getValue();
                // hp_cbkategori2.setValue();
                cblpbp3kategori2.store.proxy.conn.url = '<?= site_url("kategori3/get_kategori2") ?>/' + kdhp_cbkategori1;
                cblpbp3kategori2.store.reload();
            }
        }
    });

    // COMBOBOX kategori 2 Data Store
    var str_cblpbp3kategori2 = new Ext.data.Store({
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
                var r = new(str_cblpbp3kategori2.recordType)({
                    'kd_kategori2': '',
                    'nama_kategori2': '-----'
                });
                str_cblpbp3kategori2.insert(0, r);
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
    var cblpbp3kategori2 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 2',
        id: 'id_cblpbp3kategori2',
        mode: 'local',
        store: str_cblpbp3kategori2,
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
                var kd_hp_cbkategori1 = cblpbp3kategori1.getValue();
                var kd_hp_cbkategori2 = this.getValue();
                cblpbp3kategori3.setValue();
                cblpbp3kategori3.store.proxy.conn.url = '<?= site_url("kategori4/get_kategori3") ?>/' + kd_hp_cbkategori1 + '/' + kd_hp_cbkategori2;
                cblpbp3kategori3.store.reload();
            }
        }
    });

    // COMBOBOX kategori 3 Data Store
    var str_cblpbp3kategori3 = new Ext.data.Store({
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
                var r = new(str_cblpbp3kategori3.recordType)({
                    'kd_kategori3': '',
                    'nama_kategori3': '-----'
                });
                str_cblpbp3kategori3.insert(0, r);
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
    var cblpbp3kategori3 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 3',
        id: 'id_cblpbp3kategori3',
        mode: 'local',
        store: str_cblpbp3kategori3,
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
                var kd_hp_cbkategori1 = cblpbp2kategori1.getValue();
                var kd_hp_cbkategori2 = cblpbp2kategori2.getValue();
                var kd_hp_cbkategori3 = this.getValue();
                cblpbp3kategori4.setValue();
                cblpbp3kategori4.store.proxy.conn.url = '<?= site_url("master_barang/get_kategori4") ?>/' + kd_hp_cbkategori1 + '/' + kd_hp_cbkategori2 + '/' + kd_hp_cbkategori3;
                cblpbp3kategori4.store.reload();
            }
        }
    });

    // COMBOBOX kategori 4 Data Store
    var str_cblpbp3kategori4 = new Ext.data.Store({
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
                var r = new(str_cblpbp3kategori4.recordType)({
                    'kd_kategori4': '',
                    'nama_kategori4': '-----'
                });
                str_cblpbp3kategori4.insert(0, r);
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
    var cblpbp3kategori4 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 4 ',
        id: 'id_cblpbp3kategori4',
        mode: 'local',
        store: str_cblpbp3kategori4,
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
    var lpbp3sortorder = new Ext.form.Checkbox({
        xtype: 'checkbox',
        fieldLabel: 'Sort Order Kategori 3',
        boxLabel: 'Descending',
        name: 'sort_order',
        id: 'id_lpbp3sortorder',
        checked: true,
        inputValue: '1',
        autoLoad: true
    });

	var valcblpbp3status=[
		['D',"Distribusi"],
		['B',"Bazar"],
		['S',"Supermarket"]
	];
    
    var strcblpbp3status = new Ext.data.ArrayStore({
        fields: [
            {name: 'key'},
            {name: 'value'}
        ],
        data:valcblpbp3status
    });

	// COMBOBOX status
    var cblpbp3status = new Ext.form.ComboBox({
        fieldLabel: 'Status',
        id: 'cblpbp3status',
        name:'status',
        // allowBlank:false,
        store: strcblpbp3status,
		valueField:'key',
		displayField:'value',
		mode:'local',
        forceSelection: true,
        triggerAction: 'all',
		anchor: '90%'
    });

    // HEADER tanggal
    var headerlpbp3tanggal = {
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
                                name: 'lpbp3_dari_tgl',
                                allowBlank: false,
                                format: 'd-m-Y',
                                editable: false,
                                id: 'id_lpbp3_dari_tgl',
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
                                name: 'lpbp3_sampai_tgl',
                                // readOnly: true,				
                                allowBlank: false,
                                editable: false,
                                format: 'd-m-Y',
                                id: 'id_lpbp3_smp_tgl',
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
    var headerlpbp3kategori = {
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
							cblpbp3kategori1,
							cblpbp3kategori2,
							cblpbp3kategori3,
							cblpbp3suplier
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
							cblpbp3status,
                            lpbp3sortorder
                        ]

                    }]
                }]
            }]
        }]

    }

    // HEADER
    var headerlaporanpenerimaanbrgperkategori3 = {
        buttonAlign: 'left',
        layout: 'form',
        border: false,
        labelWidth: 100,
        defaults: {
            labelSeparator: ''
        },
        items: [headerlpbp3tanggal, headerlpbp3kategori

        ],
        buttons: [{
            text: 'Print',
            formBind: true,
            handler: function () {
                winlaporanpenerimaanbrgperkategori3print.show();
                Ext.getDom('laporanpenerimaanbrgperkategori3print').src = '<?= site_url("laporan_purchase_order/print_form") ?>';
            }
        }, {
            text: 'Cancel',
            handler: function () {
                clearlaporanpenerimaanbrgperkategori3();
            }
        }]
    };

    // PRINT
    var winlaporanpenerimaanbrgperkategori3print = new Ext.Window({
        id: 'id_winlaporanpenerimaanbrgperkategori3print',
        Title: 'Print Laporan Penerimaan Barang Per Kategori 3',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html: '<iframe style="width:100%;height:390px;" id="laporanpenerimaanbrgperkategori3print" src=""></iframe>'
    });

    //  FORM PANEL
    var laporanpenerimaanbrgperkategori3 = new Ext.FormPanel({
        id: 'rpt_penerimaan_brg_perkategori3',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
            bodyStyle: {
                margin: '0px 0px 15px 0px'
            },
            items: [headerlaporanpenerimaanbrgperkategori3]
        }]
    });

    // CLEAR DATA FORM PANEL
    function clearlaporanpenerimaanbrgperkategori3() {
        Ext.getCmp('rpt_penerimaan_brg_perkategori3').getForm().reset();
        // strlaporanpenerimaanbarang.removeAll();
    }
</script>