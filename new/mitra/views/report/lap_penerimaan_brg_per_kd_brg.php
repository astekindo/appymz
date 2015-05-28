<?php if (!defined( 'BASEPATH')) exit( 'No direct script access allowed'); ?>
<script type="text/javascript">
    //-------- COMBOBOX SUPPLIER ---------------------
    //
    var strcblpbpsuplier = new Ext.data.ArrayStore({
        fields: ['kd_supplier'],
        data: []
    });

    // GRID PANEL TWIN COMBOBOX supplier Data Store
    var strgridlpbpkbsuplier = new Ext.data.Store({
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
    var searchgridlpbpkbsuplier = new Ext.app.SearchField({
        store: strgridlpbpkbsuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridlpbpkbsuplier'
    });

    // GRID PANEL TWIN COMBOBOX supplier
    var gridlpbpkbsuplier = new Ext.grid.GridPanel({
        store: strgridlpbpkbsuplier,
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
            items: [searchgridlpbpkbsuplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridlpbpkbsuplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function () {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    //Ext.getCmp('lpo_kd_supplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_cblpbpsuplier').setValue(sel[0].get('kd_supplier'));
                    // strlaporanpenerimaanbarang.removeAll();       
                    menulpbpkbsuplier.hide();
                }
            }
        }
    });

    // PANEL TWIN COMBOBOX supplier
    var menulpbpkbsuplier = new Ext.menu.Menu();
    menulpbpkbsuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridlpbpkbsuplier],
        buttons: [{
            text: 'Close',
            handler: function () {
                menulpbpkbsuplier.hide();
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
            strgridlpbpkbsuplier.load();
            menulpbpkbsuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    //
    menulpbpkbsuplier.on('hide', function () {
        var sf = Ext.getCmp('id_searchgridlpbpkbsuplier').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgridlpbpkbsuplier').setValue('');
            searchgridlpbpkbsuplier.onTrigger2Click();
        }
    });

    // TWIN COMBOBOX supplier
    var cblpbpsuplier = new Ext.ux.TwinComboSuplier({
        fieldLabel: 'Supplier',
        id: 'id_cblpbpsuplier',
        store: strcblpbpsuplier,
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

	
	//-------- COMBOBOX PRODUK -----------------------
    //
    var strcblpbpkbproduk = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data: []
    });

    // GRID PANEL TWIN COMBOBOX produk Data Store
    var strgridlpbpkbproduk = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_produk', 'nama_produk'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("laporan_purchase_order/search_produk") ?>',
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

    // SEARCH GRID PANEL TWIN COMBOBOX produk
    var searchgridlpbpkbproduk = new Ext.app.SearchField({
        store: strgridlpbpkbproduk,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridlpbpkbproduk'
    });

    // GRID PANEL TWIN COMBOBOX produk
    var gridlpbpkbproduk = new Ext.grid.GridPanel({
        store: strgridlpbpkbproduk,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
            header: 'Kode Produk',
            dataIndex: 'kd_produk',
            width: 80,
            sortable: true

        }, {
            header: 'Nama Produk',
            dataIndex: 'nama_produk',
            width: 300,
            sortable: true
        }],
        tbar: new Ext.Toolbar({
            items: [searchgridlpbpkbproduk]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridlpbpkbproduk,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function () {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    // Ext.getCmp('lpo_kd_supplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_lpbpkb_kd_produk').setValue(sel[0].get('kd_produk'));
                    // strlaporanpenerimaanbarang.removeAll();       
                    menulpbpkbproduk.hide();
                }
            }
        }
    });

    // PANEL TWIN COMBOBOX produk
    Ext.ux.TwinCombolpbpkbProduk = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function () {
            //load store grid
            strgridlpbpkbproduk.load();
            menulpbpkbproduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    // PANEL TWIN COMBOBOX produk
    var menulpbpkbproduk = new Ext.menu.Menu();
    menulpbpkbproduk.add(new Ext.Panel({
        title: 'Pilih Produk',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridlpbpkbproduk],
        buttons: [{
            text: 'Close',
            handler: function () {
                menulpbpkbproduk.hide();
            }
        }]
    }));

    //
    menulpbpkbproduk.on('hide', function () {
        var sf = Ext.getCmp('id_searchgridlpbpkbproduk').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgridlpbpkbproduk').setValue('');
            searchgridlpbpkbproduk.onTrigger2Click();
        }
    });

    // TWIN COMBOBOX produk
    var cblpbpkbproduk = new Ext.ux.TwinCombolpbpkbProduk({
        id: 'id_lpbpkb_kd_produk',
        fieldLabel: 'Produk',
        store: strcblpbpkbproduk,
        mode: 'local',
        anchor: '90%',
        valueField: 'kd_produk',
        displayField: 'kd_produk',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: true,
        editable: false,
        hiddenName: 'kd_produk',
        emptyText: 'Pilih Produk'
    });
    //-------- COMBOBOX PRODUK -----------------------

    
	// COMBOBOX kategori 1 Data Store
    var str_cblpbpkbkategori1 = new Ext.data.Store({
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
                var r = new(str_cblpbpkbkategori1.recordType)({
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
    var cblpbpkbkategori1 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 1',
        id: 'id_cblpbpkbkategori1',
        store: str_cblpbpkbkategori1,
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
                var kdhp_cbkategori1 = cblpbpkbkategori1.getValue();
                // hp_cbkategori2.setValue();
                cblpbpkbkategori2.store.proxy.conn.url = '<?= site_url("kategori3/get_kategori2") ?>/' + kdhp_cbkategori1;
                cblpbpkbkategori2.store.reload();
            }
        }
    });

    // COMBOBOX kategori 2 Data Store
    var str_cblpbpkbkategori2 = new Ext.data.Store({
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
                var r = new(str_cblpbpkbkategori2.recordType)({
                    'kd_kategori2': '',
                    'nama_kategori2': '-----'
                });
                str_cblpbpkbkategori2.insert(0, r);
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
    var cblpbpkbkategori2 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 2',
        id: 'id_cblpbpkbkategori2',
        mode: 'local',
        store: str_cblpbpkbkategori2,
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
                var kd_hp_cbkategori1 = cblpbpkbkategori1.getValue();
                var kd_hp_cbkategori2 = this.getValue();
                cblpbpkbkategori3.setValue();
                cblpbpkbkategori3.store.proxy.conn.url = '<?= site_url("kategori4/get_kategori3") ?>/' + kd_hp_cbkategori1 + '/' + kd_hp_cbkategori2;
                cblpbpkbkategori3.store.reload();
            }
        }
    });

    // COMBOBOX kategori 3 Data Store
    var str_cblpbpkbkategori3 = new Ext.data.Store({
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
                var r = new(str_cblpbpkbkategori3.recordType)({
                    'kd_kategori3': '',
                    'nama_kategori3': '-----'
                });
                str_cblpbpkbkategori3.insert(0, r);
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
    var cblpbpkbkategori3 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 3',
        id: 'id_cblpbpkbkategori3',
        mode: 'local',
        store: str_cblpbpkbkategori3,
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
                var kd_hp_cbkategori1 = cblpbpkbkategori1.getValue();
                var kd_hp_cbkategori2 = cblpbpkbkategori2.getValue();
                var kd_hp_cbkategori3 = this.getValue();
                cblpbpkbkategori4.setValue();
                cblpbpkbkategori4.store.proxy.conn.url = '<?= site_url("master_barang/get_kategori4") ?>/' + kd_hp_cbkategori1 + '/' + kd_hp_cbkategori2 + '/' + kd_hp_cbkategori3;
                cblpbpkbkategori4.store.reload();
            }
        }
    });

    // COMBOBOX kategori 4 Data Store
    var str_cblpbpkbkategori4 = new Ext.data.Store({
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
                var r = new(str_cblpbpkbkategori4.recordType)({
                    'kd_kategori4': '',
                    'nama_kategori4': '-----'
                });
                str_cblpbpkbkategori4.insert(0, r);
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
    var cblpbpkbkategori4 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 4 ',
        id: 'id_cblpbpkbkategori4',
        mode: 'local',
        store: str_cblpbpkbkategori4,
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
    var lpbpkbsortorder = new Ext.form.Checkbox({
        xtype: 'checkbox',
        fieldLabel: 'Sort Order Kode Barang',
        boxLabel: 'Descending',
        name: 'sort_order',
        id: 'id_lpbpkbsortorder',
        checked: true,
        inputValue: '1',
        autoLoad: true
    });

	var valcblpbpkbstatus=[
		['D',"Distribusi"],
		['B',"Bazar"],
		['S',"Supermarket"]
	];
    
    var strcblpbpkbstatus = new Ext.data.ArrayStore({
        fields: [
            {name: 'key'},
            {name: 'value'}
        ],
        data:valcblpbpkbstatus
    });

	// COMBOBOX status
    var cblpbpkbstatus = new Ext.form.ComboBox({
        fieldLabel: 'Status',
        id: 'cblpbpkbstatus',
        name:'status',
        // allowBlank:false,
        store: strcblpbpkbstatus,
		valueField:'key',
		displayField:'value',
		mode:'local',
        forceSelection: true,
        triggerAction: 'all',
		anchor: '90%'
    });

    // HEADER tanggal
    var headerlpbpkbtanggal = {
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
                                name: 'lpbpkb_dari_tgl',
                                allowBlank: false,
                                format: 'd-m-Y',
                                editable: false,
                                id: 'id_lpbpkb_dari_tgl',
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
                                name: 'lpbpkb_sampai_tgl',
                                // readOnly: true,				
                                allowBlank: false,
                                editable: false,
                                format: 'd-m-Y',
                                id: 'id_lpbpkb_smp_tgl',
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
    var headerlpbpkbkategori = {
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
							cblpbpkbkategori1,
							cblpbpkbkategori2,
							cblpbpkbkategori3,
							cblpbpkbkategori4							
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
							cblpbpsuplier,
							cblpbpkbproduk,
							cblpbpkbstatus,
                            lpbpkbsortorder
                        ]

                    }]
                }]
            }]
        }]

    }

    // HEADER
    var headerlaporanpenerimaanbrgperkdbrg = {
        buttonAlign: 'left',
        layout: 'form',
        border: false,
        labelWidth: 100,
        defaults: {
            labelSeparator: ''
        },
        items: [headerlpbpkbtanggal, headerlpbpkbkategori

        ],
        buttons: [{
            text: 'Print',
            formBind: true,
            handler: function () {
                winlaporanpenerimaanbrgperkdbrgprint.show();
                Ext.getDom('laporanpenerimaanbrgperkdbrgprint').src = '<?= site_url("laporan_purchase_order/print_form") ?>';
            }
        }, {
            text: 'Cancel',
            handler: function () {
                clearlaporanpenerimaanbrgperkdbrg();
            }
        }]
    };

    // PRINT
    var winlaporanpenerimaanbrgperkdbrgprint = new Ext.Window({
        id: 'id_winlaporanpenerimaanbrgperkdbrgprint',
        Title: 'Print Laporan Penerimaan Barang Per Kode Barang',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html: '<iframe style="width:100%;height:390px;" id="laporanpenerimaanbrgperkdbrgprint" src=""></iframe>'
    });

    //  FORM PANEL
    var laporanpenerimaanbrgperkdbrg = new Ext.FormPanel({
        id: 'rpt_penerimaan_brg_per_kd_brg',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
            bodyStyle: {
                margin: '0px 0px 15px 0px'
            },
            items: [headerlaporanpenerimaanbrgperkdbrg]
        }]
    });

    // CLEAR DATA FORM PANEL
    function clearlaporanpenerimaanbrgperkdbrg() {
        Ext.getCmp('rpt_penerimaan_brg_per_kd_brg').getForm().reset();
        // strlaporanpenerimaanbarang.removeAll();
    }
</script>