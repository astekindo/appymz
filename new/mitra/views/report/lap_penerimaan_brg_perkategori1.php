<?php if (!defined( 'BASEPATH')) exit( 'No direct script access allowed'); ?>
<script type="text/javascript">
    //-------- COMBOBOX SUPPLIER ---------------------
    //
    var strcblpbp1suplier = new Ext.data.ArrayStore({
        fields: ['kd_supplier'],
        data: []
    });

    // GRID PANEL TWIN COMBOBOX supplier Data Store
    var strgridlpbp1suplier = new Ext.data.Store({
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
    var searchgridlpbp1suplier = new Ext.app.SearchField({
        store: strgridlpbp1suplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridlpbp1suplier'
    });

    // GRID PANEL TWIN COMBOBOX supplier
    var gridlpbp1suplier = new Ext.grid.GridPanel({
        store: strgridlpbp1suplier,
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
            items: [searchgridlpbp1suplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridlpbp1suplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function () {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    //Ext.getCmp('lpo_kd_supplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_cblpbp1suplier').setValue(sel[0].get('kd_supplier'));
                    // strlaporanpenerimaanbarang.removeAll();       
                    menulpbp1suplier.hide();
                }
            }
        }
    });

    // PANEL TWIN COMBOBOX supplier
    var menulpbp1suplier = new Ext.menu.Menu();
    menulpbp1suplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridlpbp1suplier],
        buttons: [{
            text: 'Close',
            handler: function () {
                menulpbp1suplier.hide();
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
            strgridlpbp1suplier.load();
            menulpbp1suplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    //
    menulpbp1suplier.on('hide', function () {
        var sf = Ext.getCmp('id_searchgridlpbp1suplier').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgridlpbp1suplier').setValue('');
            searchgridlpbp1suplier.onTrigger2Click();
        }
    });

    // TWIN COMBOBOX supplier
    var cblpbp1suplier = new Ext.ux.TwinComboSuplier({
        fieldLabel: 'Supplier',
        id: 'id_cblpbp1suplier',
        store: strcblpbp1suplier,
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
    var str_cblpbp1kategori1 = new Ext.data.Store({
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
                var r = new(str_cblpbp1kategori1.recordType)({
                    'kd_kategori1': '',
                    'nama_kategori1': '-----'
                });
                str_cblpbp1kategori1.insert(0, r);
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
    var cblpbp1kategori1 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 1 ',
        id: 'id_cblpbp1kategori1',
        store: str_cblpbp1kategori1,
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
                var kdhp_cbkategori1 = cblpbp1kategori1.getValue();
                // hp_cbkategori2.setValue();
                cblpbp1kategori2.store.proxy.conn.url = '<?= site_url("kategori3/get_kategori2") ?>/' + kdhp_cbkategori1;
                cblpbp1kategori2.store.reload();
            }
        }
    });


    // CHECKBOX Sort Order
    var lpbp1sortorder = new Ext.form.Checkbox({
        xtype: 'checkbox',
        fieldLabel: 'Sort Order Kategori 1',
        boxLabel: 'Descending',
        name: 'sort_order',
        id: 'id_lpbp1sortorder',
        checked: true,
        inputValue: '1',
        autoLoad: true
    });

	var valcblpbp1status=[
		['D',"Distribusi"],
		['B',"Bazar"],
		['S',"Supermarket"]
	];
    
    var strcblpbp1status = new Ext.data.ArrayStore({
        fields: [
            {name: 'key'},
            {name: 'value'}
        ],
        data:valcblpbp1status
    });

	// COMBOBOX status
    var cblpbp1status = new Ext.form.ComboBox({
        fieldLabel: 'Status',
        id: 'cblpbp1status',
        name:'status',
        // allowBlank:false,
        store: strcblpbp1status,
		valueField:'key',
		displayField:'value',
		mode:'local',
        forceSelection: true,
        triggerAction: 'all',
		anchor: '90%'
    });

    // HEADER tanggal
    var headerlpbp1tanggal = {
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
                                name: 'lpbp1_dari_tgl',
                                allowBlank: false,
                                format: 'd-m-Y',
                                editable: false,
                                id: 'id_lpbp1_dari_tgl',
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
                                name: 'lpbp1_sampai_tgl',
                                // readOnly: true,				
                                allowBlank: false,
                                editable: false,
                                format: 'd-m-Y',
                                id: 'id_lpbp1_smp_tgl',
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
    var headerlpbp1kategori = {
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
                            cblpbp1kategori1,
							cblpbp1suplier
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
							cblpbp1status,
                            lpbp1sortorder
                        ]



                    }]
                }]
            }]
        }]

    }

    // HEADER
    var headerlaporanpenerimaanbrgperkategori1 = {
        buttonAlign: 'left',
        layout: 'form',
        border: false,
        labelWidth: 100,
        defaults: {
            labelSeparator: ''
        },
        items: [headerlpbp1tanggal, headerlpbp1kategori

        ],
        buttons: [{
            text: 'Print',
            formBind: true,
            handler: function () {
                winlaporanpenerimaanbrgperkategori1print.show();
                Ext.getDom('laporanpenerimaanbrgperkategori1print').src = '<?= site_url("laporan_purchase_order/print_form") ?>';
            }
        }, {
            text: 'Cancel',
            handler: function () {
                clearlaporanpenerimaanbrgperkategori1();
            }
        }]
    };

    // PRINT
    var winlaporanpenerimaanbrgperkategori1print = new Ext.Window({
        id: 'id_winlaporanpenerimaanbrgperkategori1print',
        Title: 'Print Laporan Penerimaan Barang Per Kategori 1',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html: '<iframe style="width:100%;height:390px;" id="laporanpenerimaanbrgperkategori1print" src=""></iframe>'
    });

    //  FORM PANEL
    var laporanpenerimaanbrgperkategori1 = new Ext.FormPanel({
        id: 'rpt_penerimaan_brg_perkategori1',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
            bodyStyle: {
                margin: '0px 0px 15px 0px'
            },
            items: [headerlaporanpenerimaanbrgperkategori1]
        }]
    });

    // CLEAR DATA FORM PANEL
    function clearlaporanpenerimaanbrgperkategori1() {
        Ext.getCmp('rpt_penerimaan_brg_perkategori1').getForm().reset();
        // strlaporanpenerimaanbarang.removeAll();
    }
</script>