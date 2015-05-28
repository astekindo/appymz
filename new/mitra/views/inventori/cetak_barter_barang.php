<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>

<script type="text/javascript">
    /**
     * store grid barter barang
     */
    var storeGridBarterBarang = new Ext.data.Store({
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
            url: '<?= site_url("cetak_barter_barang_controller/finalGetDataBarter") ?>',
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
     * store grid surat barter barang
     */
    var storeGridSuratBarter = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_sb',
                'tanggal',
                'no_transfer_stok',
                'kd_ekspedisi',
                'no_kendaraan',
                'sopir',
                'pic_penerima',
                'alamat_penerima',
                'no_telp_penerima',
                'keterangan',
                'created_by',
                'created_date',
                'is_kembali',
                'tanggal_kembali',
                'penerima',
                'ket_pengembalian',
                'updated_by',
                'updated_date'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("cetak_barter_barang_controller/finalGetDataSuratBarter") ?>',
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


    storeGridSuratBarter.on('load', function() {
        var sm = gridBarterBarang_cbb.getSelectionModel();
        var sel = sm.getSelections();
        storeGridSuratBarter.setBaseParam('no_transfer', sel[0].get('no_transfer_stok'));
    });
    /**
     * deklarasi store combo supplier
     */
    var storeCboSupplier_cbb = new Ext.data.ArrayStore({
        fields: ['kd_supplier'],
        data: []
    });

    /**
     * deklarasi store grid supplier
     */
    var storeCboGridSupplier_cbb = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_supplier', 'nama_supplier', 'top', 'pic', 'pkp', 'alamat'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_create_po_bonus/search_supplier") ?>',
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
     * override onload event of store combo grid supplier
     */
    storeCboGridSupplier_cbb.on('load', function() {
        Ext.getCmp('id_search_grid_cbo_supplier_cbb').focus();
    });

    /**
     * declaration of search grid for combo grid supplier
     */
    var searchGridCboSupplier_cbb = new Ext.app.SearchField({
        store: storeCboGridSupplier_cbb,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_search_grid_cbo_supplier_cbb'
    });


    /**
     * declaration of grid supplier
     */
    var gridpcpobsuplier = new Ext.grid.GridPanel({
        store: storeCboGridSupplier_cbb,
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
            }, {
                header: 'PIC',
                dataIndex: 'pic',
                width: 100,
                sortable: true
            }, {
                header: 'Alamat',
                dataIndex: 'alamat',
                width: 200,
                sortable: true
            }, {
                header: 'Waktu TOP',
                dataIndex: 'top',
                width: 80,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchGridCboSupplier_cbb]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: storeCboGridSupplier_cbb,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cbo_supplier_cbb').setValue(sel[0].get('kd_supplier'));
                    menuComboSupplier_cbb.hide();
                }
            }
        }
    });

    /**
     * declaration of menu container for grid combo supplier
     */
    var menuComboSupplier_cbb = new Ext.menu.Menu();
    menuComboSupplier_cbb.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridpcpobsuplier],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menuComboSupplier_cbb.hide();
                }
            }]
    }));

    /**
     *declaration of twin combo supplier 
     */
    Ext.ux.TwinComboSupplier_cbb = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            storeCboGridSupplier_cbb.load();
            menuComboSupplier_cbb.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    /**
     * overrid menu combo supplier on hide event
     */
    menuComboSupplier_cbb.on('hide', function() {
        var sf = Ext.getCmp('id_search_grid_cbo_supplier_cbb').getValue();
        if (sf !== '') {
            Ext.getCmp('id_search_grid_cbo_supplier_cbb').setValue('');
            searchGridCboSupplier_cbb.onTrigger2Click();
        }
    });

    /**
     * declaration of combo supplier
     */
    var cboSupplier_cbb = new Ext.ux.TwinComboSupplier_cbb({
        fieldLabel: 'Supplier <span class="asterix">*</span>',
        id: 'id_cbo_supplier_cbb',
        store: storeCboSupplier_cbb,
        mode: 'local',
        valueField: 'kd_supplier',
        displayField: 'kd_supplier',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_supplier',
        emptyText: 'Pilih Supplier'
    });


    /**
     * header 
     */
    var headerCetakBarter_cbb = {
        layout: 'column',
        border: false,
        buttonAlign: 'left',
        style: 'padding:5px',
        frame: true,
        items: [{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                buttonAlign: 'left',
                defaults: {labelSeparator: ''},
                items: [
                    cboSupplier_cbb
                ],
                buttons: [{
                        text: 'filter',
                        handler: function() {
                            storeGridBarterBarang.reload({
                                params: {
                                    kd_supplier: Ext.getCmp('id_cbo_supplier_cbb').getValue(),
                                    tanggal_barter: Ext.getCmp('id_tanggal_barter_cbb').getValue()
                                }
                            });
                        }
                    }, {
                        text: 'reset',
                        handler: function() {
                            Ext.getCmp('cetak_barter_barang').getForm().reset();
                            storeGridBarterBarang.removeAll();
                            storeGridSuratBarter.removeAll();
                        }
                    }]
            }, {
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [
                    {
                        xtype: 'datefield',
                        fieldLabel: 'Tgl Barter',
                        //emptyText: 'Tanggal Kwitansi',
                        name: 'tanggal_barter_cbb',
                        id: 'id_tanggal_barter_cbb',
                        maxLength: 255,
                        anchor: '90%',
                        value: ''//,
                                //format: 'Y-m-d'

                    }
                ]
            }]
    };
    /**
     * search for grid barter barang
     * @type Ext.grid.CheckboxSelectionModel
     */
    var searchGridBarterBarang_cbb = new Ext.app.SearchField({
        store: storeGridBarterBarang,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_search_grid_barter_barang_cbb'
    });

    /**
     * barter barang grid's selection model
     */
    var smGridBarterBarang_cbb = new Ext.grid.CheckboxSelectionModel();

    /**
     *grid barter barang / first grid 
     */
    var gridBarterBarang_cbb = new Ext.grid.GridPanel({
        frame: true,
        border: true,
        stripeRows: true,
        //sm: smGridBarterBarang_cbb,
        store: storeGridBarterBarang,
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
                storeGridSuratBarter.load({
                    params: {
                        no_transfer: sel[0].get('no_transfer_stok')
                    }
                });
            }
        },
        tbar: new Ext.Toolbar({
            items: [searchGridBarterBarang_cbb]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: storeGridBarterBarang,
            displayInfo: true
        })
    });

    /**
     * search for grid surat barter
     * @type Ext.grid.CheckboxSelectionModel
     */
    var searchGridSuratBarter_cbb = new Ext.app.SearchField({
        store: storeGridSuratBarter,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_search_grid_surat_barter_cbb'
    });

    /**
     * surat barter grid's selection model
     */
    var smGridSuratBarter_cbb = new Ext.grid.CheckboxSelectionModel();

    /**
     *grid surat barter / second grid 
     */
    var gridSuratBarter_cbb = new Ext.grid.GridPanel({
        frame: true,
        border: true,
        stripeRows: true,
        sm: smGridBarterBarang_cbb,
        store: storeGridSuratBarter,
        loadMask: true,
        height: 250,
        title: 'Data Surat Barter',
        style: 'margin:0 auto;', height: 250,
                columns: [
                    {
                        header: "No Surat",
                        dataIndex: 'no_sb',
                        sortable: true,
                        width: 150
                    }, {
                        header: "Tanggal",
                        dataIndex: 'tanggal',
                        sortable: true,
                        width: 250
                    }, {
                        header: "No Transfer",
                        dataIndex: 'no_transfer_stok',
                        sortable: true,
                        width: 150
                    }, {
                        header: "Kd Ekspedisi",
                        dataIndex: 'kd_ekspedisi',
                        sortable: true,
                        width: 70
                    }, {
                        header: "No Kendaraan",
                        dataIndex: 'no_kendaraan',
                        sortable: true,
                        width: 250
                    }, {
                        header: "Sopir",
                        dataIndex: 'sopir',
                        sortable: true,
                        width: 250
                    }, {
                        header: "PIC",
                        dataIndex: 'pic_penerima',
                        sortable: true,
                        width: 250
                    }, {
                        header: "Alamat Penerima",
                        dataIndex: 'alamat_penerima',
                        sortable: true,
                        width: 250
                    }, {
                        header: "Telp Penerima",
                        dataIndex: 'no_telp_penerima',
                        sortable: true,
                        width: 250
                    }, {
                        header: "Keterangan",
                        dataIndex: 'keterangan',
                        sortable: true,
                        width: 250
                    }, {
                        header: "Created By",
                        dataIndex: 'created_by',
                        sortable: true,
                        width: 250
                    }, {
                        header: "Kembali",
                        dataIndex: 'is_kembali',
                        sortable: true,
                        width: 250
                    }, {
                        header: "Penerima",
                        dataIndex: 'penerima',
                        sortable: true,
                        width: 250
                    }, {
                        header: "Ket. Pengembalian",
                        dataIndex: 'ket_pengembalian',
                        sortable: true,
                        width: 250
                    }, {
                        header: "Updated By",
                        dataIndex: 'updated_by',
                        sortable: true,
                        width: 250
                    }, {
                        header: "Updated Date",
                        dataIndex: 'updated_date',
                        sortable: true,
                        width: 250
                    }],
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                windowBarterBarangPrint.show();
                Ext.getDom('id_cetak_barter_barang_print').src = '<?= site_url("cetak_barter_barang_controller/finalPrint") ?>' + '/' + sel[0].get('no_sb');
            }
        },
        tbar: new Ext.Toolbar({
            items: [searchGridSuratBarter_cbb]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: storeGridSuratBarter,
            displayInfo: true
        })
    });

    /**
     * deklarai window print
     */
    var windowBarterBarangPrint = new Ext.Window({
        id: 'id_window_barter_barang_print',
        title: 'Print Barter Barang',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        //html: 'div id="lap_do" />'
        html: '<iframe style="width:100%;height:100%;" id="id_cetak_barter_barang_print" src=""></iframe>'
    });

    /**
     * main panel container
     */
    Ext.ns('cetak_barter_barang');
    var cetakBarterBarang_cbb = new Ext.FormPanel({
        id: 'cetak_barter_barang',
        monitorValid: true,
        border: false,
        frame: true,
        bodyStyle: 'padding-right:20px;overflowY: auto',
        labelWidth: 130,
        autoScroll: true,
        items: [headerCetakBarter_cbb,
            {
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },
                items: [gridBarterBarang_cbb]
            }, {
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },
                items: [gridSuratBarter_cbb]
            }
        ],
        buttons: [
            {
                text: 'cetak',
                handler: function() {
                    var sm = gridSuratBarter_cbb.getSelectionModel();
                    var sel = sm.getSelections();
                    windowBarterBarangPrint.show();
                    Ext.getDom('id_cetak_barter_barang_print').src = '<?= site_url("cetak_barter_barang_controller/finalPrint") ?>' + '/' + sel[0].get('no_sb');
                }
            }
        ]

    });


</script>