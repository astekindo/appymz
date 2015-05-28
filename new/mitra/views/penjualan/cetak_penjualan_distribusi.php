<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>

<script type="text/javascript">
    //Pelanggan 
    var storeCboPelanggan_cpd = new Ext.data.ArrayStore({
        fields: ['kd_pelanggan'],
        data: []
    });

    var storeGridCboPelanggan_cpd = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_pelanggan', 'nama_pelanggan', 'tipe', 'nama_tipe', 'alamat_kirim', 'no_telp', 'nama_sales', 'kd_sales'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_create_po_bonus_controller/finalGetCustomers") ?>',
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
     * deklarasi combo pelanggan
     */
    var searchGridCboPelanggan_cpd = new Ext.app.SearchField({
        store: storeGridCboPelanggan_cpd,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_search_grid_cbo_pelanggan_cpd'
    });


    var gridCboPelanggan_cpd = new Ext.grid.GridPanel({
        store: storeGridCboPelanggan_cpd,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'Kode Pelanggan',
                dataIndex: 'kd_pelanggan',
                width: 80,
                sortable: true
            }, {
                header: 'Nama Pelanggan',
                dataIndex: 'nama_pelanggan',
                width: 200,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchGridCboPelanggan_cpd]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: storeGridCboPelanggan_cpd,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cbo_pelanggan_cpd').setValue(sel[0].get('kd_pelanggan'));
                    menuCboPelanggan_cpd.hide();
                }
            }
        }
    });

    var menuCboPelanggan_cpd = new Ext.menu.Menu();
    menuCboPelanggan_cpd.add(new Ext.Panel({
        title: 'Pilih Pelanggan',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 300,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridCboPelanggan_cpd],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menuCboPelanggan_cpd.hide();
                }
            }]
    }));

    Ext.ux.TwinComboPelanggan_cpd = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            storeGridCboPelanggan_cpd.load();
            menuCboPelanggan_cpd.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menuCboPelanggan_cpd.on('hide', function() {
        var sf = Ext.getCmp('id_search_grid_cbo_pelanggan_cpd').getValue();
        if (sf !== '') {
            Ext.getCmp('id_search_grid_cbo_pelanggan_cpd').setValue('');
            searchGridCboPelanggan_cpd.onTrigger2Click();
        }
    });

    var cboPelanggan_cpd = new Ext.ux.TwinComboPelanggan_cpd({
        fieldLabel: 'Pelanggan',
        id: 'id_cbo_pelanggan_cpd',
        store: storeCboPelanggan_cpd,
        mode: 'local',
        valueField: 'kd_pelanggan',
        displayField: 'kd_pelanggan',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: true,
        editable: false,
        width: 300,
        hiddenName: 'kd_pelanggan',
        emptyText: 'Pilih Pelanggan'
    });

    /**
     * store grid sales order
     */
    var storeSalesOrder_cpd = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_so',
                'kd_member',
                'status',
                'tgl_so',
                'kirim_so',
                'kirim_alamat_so',
                'kirim_telp_so',
                'rp_total',
                'rp_diskon',
                'rp_bank_charge',
                'rp_ongkos_kirim',
                'rp_ongkos_pasang',
                'rp_total_bayar',
                'kd_voucher',
                'qty_voucher',
                'no_open_saldo',
                'userid',
                'type_sales',
                'kirim_passwd_so',
                'rp_diskon_tambahan',
                'keterangan',
                'rp_kurang_bayar',
                'rp_total_nett',
                'kd_sales',
                'no_ref',
                'rp_uang_muka'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("cetak_penjualan_distribusi_controller/finalGetDataSO") ?>',
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
     * store grid sales order detail
     */
    var storeSalesOrderDetail_cpd = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_so',
                'kd_produk',
                'qty'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("cetak_penjualan_distribusi_controller/finalGetDataSODetail") ?>',
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

    storeSalesOrderDetail_cpd.on('load', function() {
        var sm = gridSalesOrder_cpd.getSelectionModel();
        var sel = sm.getSelections();
        storeSalesOrderDetail_cpd.setBaseParam('no_so', sel[0].get('no_so'));
    });
    /**
     * header 
     */
    var headerCetakPenjualanDistribusi_cbb = {
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
                    cboPelanggan_cpd
                ],
                buttons: [{
                        text: 'filter',
                        handler: function() {
                            storeSalesOrder_cpd.reload({
                                params: {
                                    kd_member: Ext.getCmp('id_cbo_pelanggan_cpd').getValue(),
                                    tanggal_so: Ext.getCmp('id_tanggal_penjualan_distribusi_cbb').getValue()
                                }
                            });

                        }
                    }, {
                        text: 'reset',
                        handler: function() {
                            Ext.getCmp('cetak_penjualan_distribusi').getForm().reset();
                            storeSalesOrder_cpd.removeAll();
                            storeSalesOrderDetail_cpd.removeAll();
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
                        fieldLabel: 'Tanggal',
                        //emptyText: 'Tanggal Kwitansi',
                        name: 'tanggal_penjualan_distribusi_cbb',
                        id: 'id_tanggal_penjualan_distribusi_cbb',
                        maxLength: 255,
                        anchor: '90%',
                        value: '',
                        format: 'Y-m-d'

                    }
                ]
            }]
    };
    /**
     * search grid sales order
     */
    var searchGridSalesOrder_cpd = new Ext.app.SearchField({
        store: storeSalesOrder_cpd,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_search_grid_sales_order_cbb'
    });

    /**
     *grid sales order / first grid 
     */
    var gridSalesOrder_cpd = new Ext.grid.GridPanel({
        frame: true,
        border: true,
        stripeRows: true,
        //sm: smGridBarterBarang_cbb,
        store: storeSalesOrder_cpd,
        loadMask: true,
        height: 250,
        title: 'Data Sales Order',
        style: 'margin:0 auto;',
        columns: [
            {
                header: "No SO",
                dataIndex: 'no_so',
                sortable: true,
                width: 100
            }, {
                header: "No Ref",
                dataIndex: 'no_ref',
                sortable: true,
                width: 150
            }, {
                header: "Kd Member",
                dataIndex: 'kd_member',
                sortable: true,
                width: 100
            }, {
                header: "Status",
                dataIndex: 'status',
                sortable: true,
                width: 70
            }, {
                header: "Tanggal SO",
                dataIndex: 'tgl_so',
                sortable: true,
                width: 70
            }, {
                header: "Kirim SO",
                dataIndex: 'kirim_so',
                sortable: true,
                width: 250
            }, {
                header: "Kirim Alamat SO",
                dataIndex: 'kirim_alamat_so',
                sortable: true,
                width: 250
            }, {
                header: "Kirim Telp SO",
                dataIndex: 'kirim_telp_so',
                sortable: true,
                width: 150
            }, {
                header: "Rp.Total",
                dataIndex: 'rp_total',
                sortable: true,
                width: 150
            }, {
                header: "Rp Diskon",
                dataIndex: 'rp_diskon',
                sortable: true,
                width: 150
            }, {
                header: "Bank Charge",
                dataIndex: 'rp_bank_charge',
                sortable: true,
                width: 150
            }, {
                header: "Ongkos Kirim",
                dataIndex: 'rp_ongkos_kirim',
                sortable: true,
                width: 150
            }, {
                header: "Ongkos Pasang",
                dataIndex: 'rp_ongkos_pasang',
                sortable: true,
                width: 150
            }, {
                header: "Total Bayar",
                dataIndex: 'rp_total_bayar',
                sortable: true,
                width: 150
            }],
        listeners: {
            'rowclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                //if (sel.length() > 0) {
                storeSalesOrderDetail_cpd.reload({
                    params: {
                        no_so: sel[0].get('no_so')
                    }
                });
                //}

            }
        },
        tbar: new Ext.Toolbar({
            items: [searchGridSalesOrder_cpd]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: storeSalesOrder_cpd,
            displayInfo: true
        })
    });

    /**
     * search grid sales order detail
     */
    var searchGridSalesOrderDetail_cpd = new Ext.app.SearchField({
        store: storeSalesOrderDetail_cpd,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_search_grid_sales_order_detail_cbb'
    });

    /**
     *grid sales order / first grid 
     */
    var gridSalesOrderDetail_cpd = new Ext.grid.GridPanel({
        frame: true,
        border: true,
        stripeRows: true,
        //sm: smGridBarterBarang_cbb,
        store: storeSalesOrderDetail_cpd,
        loadMask: true,
        height: 250,
        title: 'Data Sales Order',
        style: 'margin:0 auto;',
        columns: [
            {
                header: "No SO",
                dataIndex: 'no_so',
                sortable: true,
                width: 150
            }, {
                header: "Qty",
                dataIndex: 'qty',
                sortable: true,
                width: 250
            }],
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                windowDistributionSalesPrint_cpd.show();
                Ext.getDom('id_cetak_penjualan_distribusi_print_cpd').src = '<?= site_url("penjualan_distribusi/print_form") ?>' + '/' + sel[0].get('no_so');
            }
        },
        tbar: new Ext.Toolbar({
            items: [searchGridSalesOrderDetail_cpd]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: storeSalesOrderDetail_cpd,
            displayInfo: true
        })
    });

    /**
     * deklarai window print
     */
    var windowDistributionSalesPrint_cpd = new Ext.Window({
        id: 'id_window_penjualan_distribusi_print_cpd',
        title: 'Print Barter Barang',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        //html: 'div id="lap_do" />'
        html: '<iframe style="width:100%;height:100%;" id="id_cetak_penjualan_distribusi_print_cpd" src=""></iframe>'
    });



    /**
     * main panel container
     */
    var cetakBarterBarang_cbb = new Ext.FormPanel({
        id: 'cetak_penjualan_distribusi',
        monitorValid: true,
        border: false,
        frame: true,
        bodyStyle: 'padding-right:20px;',
        labelWidth: 130,
        autoScroll: true,
        items: [headerCetakPenjualanDistribusi_cbb,
            {
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },
                items: [gridSalesOrder_cpd]
            }, {
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },
                items: [gridSalesOrderDetail_cpd]
            }],
        buttons: [
            {
                text: 'cetak',
                handler: function() {
                    var sm = gridSalesOrderDetail_cpd.getSelectionModel();
                    var sel = sm.getSelections();
                    windowDistributionSalesPrint_cpd.show();
                    Ext.getDom('id_cetak_penjualan_distribusi_print_cpd').src = '<?= site_url("penjualan_distribusi/print_form") ?>' + '/' + sel[0].get('no_so');
                }
            }
        ]
    });


</script>