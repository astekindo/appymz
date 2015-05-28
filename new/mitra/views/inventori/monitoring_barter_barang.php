<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>

<script type="text/javascript">
    /**
     * store grid barter barang
     */
    var storeGridBarterBarang_mbb = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_transfer_stok',
                'tanggal',
                'keterangan',
                'jenis_transfer',
                'no_po',
                'kd_supplier',
                'nama_supplier',
                'status',
                'approve_ops_by',
                'approve_ops_date',
                'approve_buyer_by',
                'approve_buyer_date',
                'created_by',
                'created_date',
                'updated_by',
                'updated_date'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("monitoring_barter_barang_controller/finalGetDataBarter") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    /**
     * store grid barter barang detail
     */
    var storeGridBarterBarangDetail_mbb = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_sb',
                'kd_produk',
                'nama_produk',
                'qty',
                'kd_lokasi',
                'kd_blok',
                'kd_sub_blok',
                'keterangan'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("monitoring_barter_barang_controller/finalGetDataBarterDetail") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    storeGridBarterBarangDetail_mbb.on('load', function() {
        var sm = gridBarterBarang_mbb.getSelectionModel();
        var sel = sm.getSelections();
        if (sel.length > 0) {
            storeGridBarterBarangDetail_mbb.setBaseParam('no_transfer', sel[0].get('no_transfer_stok'));
        }

    });

    /**
     * deklarasi store kode pelanggan
     */
    var storeComboSupplier_mbb = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_supplier', 'nama_supplier', 'alamat'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("monitoring_barter_barang_controller/finalGetDataSupplier") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
    /**
     * deklarasi search grid pelanggan
     */
    var searchGridSupplier_mbb = new Ext.app.SearchField({
        store: storeComboSupplier_mbb,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_search_grid_supplier_mbb'
    });

    var smGridSupplier_mbb = new Ext.grid.CheckboxSelectionModel();

    /**
     * deklarasi grid pelanggan
     */
    var gridSupplier_mbb = new Ext.grid.GridPanel({
        store: storeComboSupplier_mbb,
        stripeRows: true,
        frame: true,
        border: true,
        sm: smGridSupplier_mbb,
        columns: [{
                header: 'Kode Supplier',
                dataIndex: 'kd_supplier',
                width: 120,
                sortable: true
            }, {
                header: 'Nama Supplier',
                dataIndex: 'nama_supplier',
                width: 120,
                sortable: true
            }, {
                header: 'Alamat',
                dataIndex: 'alamat',
                width: 150,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchGridSupplier_mbb]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: storeComboSupplier_mbb,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_combo_supplier_mbb').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_nama_supplier_mbb').setValue(sel[0].get('nama_supplier'));
                    menuSupplier_mbb.hide();
                }
            }
        }
    });
    /**
     * deklarasi menu pelanggan
     */
    var menuSupplier_mbb = new Ext.menu.Menu();
    menuSupplier_mbb.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridSupplier_mbb],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menuSupplier_mbb.hide();
                }
            }]
    }));
    /**
     * deklarasi twin combo pelanggan
     * @returns {undefined} */
    Ext.ux.TwincomboSupplier_mbb = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            storeComboSupplier_mbb.load();
            menuSupplier_mbb.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    menuSupplier_mbb.on('hide', function() {
        var sf = Ext.getCmp('id_search_grid_supplier_mbb').getValue();
        if (sf !== '') {
            Ext.getCmp('id_search_grid_supplier_mbb').setValue('');
            searchGridSupplier_mbb.onTrigger2Click();
        }
    });
    var comboSupplier_mbb = new Ext.ux.TwincomboSupplier_mbb({
        fieldLabel: 'Supplier <span class="asterix">*</span>',
        id: 'id_combo_supplier_mbb',
        store: storeComboSupplier_mbb,
        mode: 'local',
        valueField: 'kd_supplier',
        displayField: 'kd_supplier',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        width: 200,
        hiddenName: 'kd_supplier',
        emptyText: 'Pilih Supplier'
    });
    /**
     * end of combo supplier
     */

    /**
     * start combo status
     */
    var valStoreCboStatus_mbb = [
        ['', "All"],
        ['0', "New"],
        ['1', "Approval Ops"],
        ['2', "Approval Buyer"],
        ['3', "Surat Jalan"],
        ['4', "Barter In"],
        ['9', "Reject"]
    ];
    var storeCboStatus_mbb = new Ext.data.ArrayStore({
        fields: [{
                name: 'key'
            }, {
                name: 'value'
            }],
        data: valStoreCboStatus_mbb
    });
    var cboStatusPO_mbb = new Ext.form.ComboBox({
        fieldLabel: 'Status',
        id: 'id_cbo_status_mbb',
        name: 'status',
        // allowBlank:false,
        store: storeCboStatus_mbb,
        valueField: 'key',
        displayField: 'value',
        emptyText: 'Status',
        mode: 'local',
        forceSelection: true,
        triggerAction: 'all',
        anchor: '90%'
    });
    /**
     * end of cbo status
     */

    /**
     * start combo status
     */
    var valStoreJenisTransfer_mbb = [
        ['', "All"],
        ['0', "Inventory"],
        ['1', "PO"]
    ];
    var storeCboJenisTranser_mbb = new Ext.data.ArrayStore({
        fields: [{
                name: 'key'
            }, {
                name: 'value'
            }],
        data: valStoreJenisTransfer_mbb
    });
    var cboJenisTransfer_mbb = new Ext.form.ComboBox({
        fieldLabel: 'Jenis Transfer',
        id: 'id_cbo_jenis_transfer_mbb',
        name: 'status',
        // allowBlank:false,
        store: storeCboJenisTranser_mbb,
        valueField: 'key',
        emptyText: 'Jenis Transfer',
        displayField: 'value',
        mode: 'local',
        forceSelection: true,
        triggerAction: 'all',
        anchor: '90%'
    });
    /**
     * end of cbo status
     */

    // HEADER MONITORING BARTER BARANG
    var headerMonitoringPOBonus_mpob = {
        layout: 'column',
        border: false,
        buttonAlign: 'left',
        items: [{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {
                    labelSeparator: ''
                },
                items: [comboSupplier_mbb, {
                        xtype: 'datefield',
                        fieldLabel: 'Tgl Barter',
                        emptyText: 'Tanggal Awal',
                        name: 'tgl_awal',
                        id: 'id_tgl_awal_mbb',
                        maxLength: 255,
                        anchor: '90%',
                        value: '',
                        format: 'Y-m-d'
                    },
                    cboStatusPO_mbb
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
                    {
                        xtype: 'textfield',
                        fieldLabel: 'Nama Supplier',
                        emptyText: 'Nama Supplier',
                        name: 'nama_supplier',
                        id: 'id_nama_supplier_mbb',
                        maxLength: 255,
                        anchor: '90%',
                        value: '',
                        readOnly: true,
                        fieldClass: 'readonly-input'
                    }, {
                        xtype: 'datefield',
                        fieldLabel: 's/d',
                        emptyText: 'Tanggal Akhir',
                        name: 'tgl_akhir',
                        id: 'id_tgl_akhir_mbb',
                        maxLength: 255,
                        anchor: '90%',
                        value: '',
                        format: 'Y-m-d'
                    }, cboJenisTransfer_mbb
                ]
            }],
        buttons: [{
                text: 'Filter',
                formBind: true,
                handler: function() {
                    storeGridBarterBarang_mbb.reload({
                        params: {
                            kd_supplier: Ext.getCmp('id_combo_supplier_mbb').getValue(),
                            tgl_awal: Ext.getCmp('id_tgl_awal_mbb').getValue(),
                            tgl_akhir: Ext.getCmp('id_tgl_akhir_mbb').getValue(),
                            status: Ext.getCmp('id_cbo_status_mbb').getValue(),
                            jenis_transfer: Ext.getCmp('id_cbo_jenis_transfer_mbb').getValue()
                        }
                    });
                }
            }, {
                text: 'Reset',
                formBind: true,
                handler: function() {
                    Ext.getCmp('monitoring_barter_barang').getForm().reset();
                    storeGridBarterBarang_mbb.removeAll();
                }
            }]
    };

    var searchGridBarterBarang_mbb = new Ext.app.SearchField({
        store: storeGridBarterBarang_mbb,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_search_grid_barter_barang_mbb'
    });

    /**
     *grid barter barang / first grid 
     */
    var gridBarterBarang_mbb = new Ext.grid.GridPanel({
        frame: true,
        border: true,
        stripeRows: true,
        //sm: smGridBarterBarang_cbb,
        store: storeGridBarterBarang_mbb,
        loadMask: true,
        height: 250,
        title: 'Data Barter Barang',
        style: 'margin:0 auto;',
        columns: [
            {
                header: "No Transfer",
                dataIndex: 'no_transfer_stok',
                sortable: true,
                width: 150
            }, {
                header: "Tanggal",
                dataIndex: 'tanggal',
                sortable: true,
                width: 250
            }, {
                header: "Keterangan",
                dataIndex: 'keterangan',
                sortable: true,
                width: 150
            }, {
                header: "Jenis Transfer",
                dataIndex: 'jenis_transfer',
                sortable: true,
                width: 70
            }, {
                header: "No Po",
                dataIndex: 'no_po',
                sortable: true,
                width: 250
            }, {
                header: "Nama Supplier",
                dataIndex: 'nama_supplier',
                sortable: true,
                width: 250
            }, {
                header: "Status",
                dataIndex: 'status',
                sortable: true,
                width: 250
            }, {
                header: "Approved Ops By",
                dataIndex: 'approve_ops_by',
                sortable: true,
                width: 250
            }, {
                header: "Approve Ops Date",
                dataIndex: 'approve_ops_date',
                sortable: true,
                width: 250
            }, {
                header: "Approved Buyer By",
                dataIndex: 'approve_buyer_by',
                sortable: true,
                width: 250
            }, {
                header: "Approved Buyer Date",
                dataIndex: 'approve_buyer_date',
                sortable: true,
                width: 250
            }],
        listeners: {
            'rowclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    storeGridBarterBarangDetail_mbb.reload({
                        params: {
                            no_transfer: sel[0].get('no_transfer_stok')
                        }
                    });
                }

            }
        },
        tbar: new Ext.Toolbar({
            items: [searchGridBarterBarang_mbb]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: storeGridBarterBarang_mbb,
            displayInfo: true
        })
    });

    var searchGridBarterBarangDetail_mbb = new Ext.app.SearchField({
        store: storeGridBarterBarangDetail_mbb,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_search_grid_barter_barang_detail_mbb'
    });

    /**
     *grid barter barang detail / second grid 
     */
    var gridBarterBarangDetail_mbb = new Ext.grid.GridPanel({
        frame: true,
        border: true,
        stripeRows: true,
        //sm: smGridBarterBarang_cbb,
        store: storeGridBarterBarangDetail_mbb,
        loadMask: true,
        height: 250,
        title: 'Data Barter Barang Detail',
        style: 'margin:0 auto;',
        columns: [
            {
                header: "No SB",
                dataIndex: 'no_sb',
                sortable: true,
                width: 150
            }, {
                header: "Kd Produk",
                dataIndex: 'kd_produk',
                sortable: true,
                width: 250
            }, {
                header: "Nama Produk",
                dataIndex: 'nama_produk',
                sortable: true,
                width: 150
            }, {
                header: "QTY",
                dataIndex: 'qty',
                sortable: true,
                width: 70
            }, {
                header: "Kd lokasi",
                dataIndex: 'kd_lokasi',
                sortable: true,
                width: 70
            }, {
                header: "Kd Blok",
                dataIndex: 'kd_blok',
                sortable: true,
                width: 70
            }, {
                header: "Kd Sub Blok",
                dataIndex: 'kd_sub_blok',
                sortable: true,
                width: 70
            }, {
                header: "Keterangan",
                dataIndex: 'keterangan',
                sortable: true,
                width: 250
            }],
        listeners: {
            'rowclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();

            }
        },
        tbar: new Ext.Toolbar({
            items: [searchGridBarterBarangDetail_mbb]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: storeGridBarterBarangDetail_mbb,
            displayInfo: true
        })
    });


    /**
     * main panel container
     */
    Ext.ns('monitoring_barter_barang');
    var monitoringBarterBarang_mbb = new Ext.FormPanel({
        id: 'monitoring_barter_barang',
        monitorValid: true,
        border: false,
        frame: true,
        bodyStyle: 'padding-right:20px;overflowY: auto',
        labelWidth: 130,
        autoScroll: true,
        items: [
            headerMonitoringPOBonus_mpob
                    , gridBarterBarang_mbb
                    , gridBarterBarangDetail_mbb
        ],
        buttons: [
        ]

    });


</script>