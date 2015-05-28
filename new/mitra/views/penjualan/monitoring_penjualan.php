<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">

    /**
     * store for sales data grid on the second body 
     */
    var storeDataSalesGrid_mp = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['tgl_so', 'no_so', 'rp_grand_total', 'no_open_saldo', 'status', 'userid', 'kirim_so', 'kirim_alamat_so', 'kirim_telp_so', 'no_setor_kasir', 'status_store'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("monitoring_penjualan_controller/finalGetDataSales") ?>',
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
    //storeDataSalesGrid_mp.load();

    /**
     * store for grid detil penjualan 
     */
    var storeDataDetilSalesGrid_mp = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_produk', 'nm_produk', 'qty', 'rp_harga', 'is_kirim', 'rp_ekstra_diskon', 'rp_total', 'keterangan'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("monitoring_penjualan_controller/finalGetDataDetailSales") ?>',
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
     * store for grid bonus penjualan 
     */
    var storeDataBonusSalesGrid_mp = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_produk', 'nama_produk', 'kd_produk_bonus', 'qty_bonus'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("monitoring_penjualan_controller/finalGetDataBonusSales") ?>',
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
     * store for grid bonus penjualan 
     */
    var storeDataDetailBonusSalesGrid = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_produk', 'nama_produk', 'kd_produk_bonus', 'qty_bonus'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("monitoring_penjualan_controller/finalGetDataBonusSales") ?>',
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
     * store for grid detail pembayaran 
     */
    var storeDataDetailPembayaranSalesGrid = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['nm_pembayaran', 'rp_jumlah', 'rp_charge', 'no_kartu', 'tgl_jth_tempo', 'keterangan'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("monitoring_penjualan_controller/finalGetDataDetailBayar") ?>',
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
     * store for grid detail pembayaran 
     */
    var storeDataDetailPengirimanSalesGrid_mp = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_so', 'tgl_so', 'kd_produk', 'nama_produk', 'qty_kirim', 'qty_dikirim', 'qty_sisa', 'kirim_so', 'kirim_alamat_so', 'kirim_telp_so'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("monitoring_penjualan_controller/finalGetDataPengirimanBarang") ?>',
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
     * declaration of array cashier combobox store
     */
    var cashierType_mp = [
        ['SUPERMARKET'], ['BAZAR'], ['SEMUA']
    ];

    /**
     * declaration of array setorean combobox store
     */
    var setoranType_mp = [
        ['SEMUA'], ['SUDAH SETOR'], ['BELUM STORE']
    ];

    /**
     * declaration of cashier type combobox store
     */
    var cashierStore_mp = new Ext.data.ArrayStore({
        data: cashierType_mp,
        fields: ['cashier_type']
    });

    /**
     * declaration of setoran type combobox store
     */
    var setoranStore_mp = new Ext.data.ArrayStore({
        data: setoranType_mp,
        fields: ['setoran_type']
    });

    /**
     * declaration of cashier combobox
     */


    firstColumnMonitoringPenjualan_mp = {
        columnWidth: .40,
        border: false,
        frame: false,
        height: 100,
        defaults: {buttonAlign: 'left'},
        items: [
            {
                xtype: 'compositefield',
                items: [
                    {
                        xtype: 'radio',
                        name: 'radio_filter_tanggal_mp',
                        id: 'id_radio_filter_tanggal_mp',
                        checked: true,
                        listeners: {
                            check: function() {
                                if (this.getValue()) {
                                    //Ext.Msg.alert('faktur');
                                    Ext.getCmp('id_date_filter_tanggal_dari_mp').setReadOnly(true);
                                    Ext.getCmp('id_date_filter_tanggal_dari_mp').addClass('readonly-input');
                                    Ext.getCmp('id_date_filter_tanggal_sampai_mp').setReadOnly(true);
                                    Ext.getCmp('id_date_filter_tanggal_sampai_mp').addClass('readonly-input');
                                    Ext.getCmp('id_date_filter_bulan_mp').setReadOnly(false);
                                    Ext.getCmp('id_date_filter_bulan_mp').removeClass('readonly-input');
                                }
                            }
                        }
                    }, {
                        xtype: 'displayfield',
                        value: 'Bulan :'
                    }, {
                        xtype: 'datefield',
                        name: 'data_filter_bulan_mp',
                        id: 'id_date_filter_bulan_mp',
                        format:'m-Y',
                        value: new Date()
                    }
                ]
            }, {
                xtype: 'compositefield',
                style: 'margin-top:10px',
                items: [
                    {
                        xtype: 'radio',
                        name: 'radio_filter_tanggal_mp',
                        id: 'id_radio_filter_tanggan_mp',
                        listeners: {
                            check: function() {
                                if (this.getValue()) {
                                    //Ext.Msg.alert('faktur');
                                    Ext.getCmp('id_date_filter_tanggal_dari_mp').setReadOnly(false);
                                    Ext.getCmp('id_date_filter_tanggal_dari_mp').removeClass('readonly-input');
                                    Ext.getCmp('id_date_filter_tanggal_sampai_mp').setReadOnly(false);
                                    Ext.getCmp('id_date_filter_tanggal_sampai_mp').removeClass('readonly-input');
                                    Ext.getCmp('id_date_filter_bulan_mp').setReadOnly(true);
                                    Ext.getCmp('id_date_filter_bulan_mp').addClass('readonly-input');
                                }
                            }
                        }
                    }, {
                        xtype: 'displayfield',
                        value: 'Harian :'
                    }, {
                        xtype: 'displayfield',
                        value: 'Dari Tgl.'
                    }, {
                        xtype: 'datefield',
                        fieldLabel: 'Dari Tanggal',
                        name: 'data_filter_tanggal_dari_mp',
                        id: 'id_date_filter_tanggal_dari_mp',
                        readOnly: true,
                        fieldClass: 'readonly-input'
                    }, {
                        xtype: 'displayfield',
                        value: 'S.d Tgl.'
                    }, {
                        xtype: 'datefield',
                        fieldLabel: 's.d Tanggal',
                        name: 'data_filter_tanggal_sampai_mp',
                        id: 'id_date_filter_tanggal_sampai_mp',
                        readOnly: true,
                        fieldClass: 'readonly-input'
                    }
                ]
            }
        ]
    };
    secondColumnMonitoringPenjualan_mp = {
        columnWidth: .30,
        border: false,
        frame: false,
        height: 100,
        labelWidth: 100,
        defaults: {anchor: '95%'},
        items: [
            {
                xtype: 'compositefield',
                items: [
                    {
                        xtype: 'checkbox',
                        name: 'checkbox_filter_petugas_kasir_mp',
                        id: 'id_checkbox_filter_petugas_kasir_mp'
                    }, {
                        xtype: 'displayfield',
                        value: 'Petugas Kasir :'
                    }, {
                        xtype: 'textfield',
                        name: 'txt_petugas_kasir_mp',
                        id: 'id_txt_petugas_kasir_mp'
                    }
                ]
            }, {
                xtype: 'compositefield',
                style: 'margin-top:10px',
                items: [
                    {
                        xtype: 'checkbox',
                        name: 'checkbox_filter_no_struk_mp',
                        id: 'id_checkbox_filter_no_struk_mp'
                    }, {
                        xtype: 'displayfield',
                        value: 'No. Struk &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;:'
                    }, {
                        xtype: 'textfield',
                        name: 'txt_no_struk_mp',
                        id: 'id_txt_no_struk_mp'
                    }
                ]
            }, {
                xtype: 'compositefield',
                style: 'margin-top:10px',
                items: [
                    {
                        xtype: 'checkbox',
                        name: 'checkbox_filter_pic_penerima_mp',
                        id: 'id_checkbox_filter_pic_penerima_mp'
                    }, {
                        xtype: 'displayfield',
                        value: 'PIC Penerima :'
                    }, {
                        xtype: 'textfield',
                        name: 'txt_filter_pic_penerima_mp',
                        id: 'id_txt_filter_pic_penerima_mp',
                    }
                ]
            }
        ]
    };
    thirdColumnMonitoringPenjualan_mp = {
        columnWidth: .30,
        border: true,
        frame: false,
        height: 100,
        items: [
            {
                xtype: 'compositefield',
                items: [
                    {
                        xtype: 'displayfield',
                        value: 'Mode Kasir&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp; :'
                    }, {
                        xtype: 'combo',
                        id: 'id_combo_filter_cashier_mp',
                        name: 'combo_filter_cashier_mp',
                        store: cashierStore_mp,
                        typeAhead: true,
                        mode: 'local',
                        displayField: 'cashier_type',
                        editable: false,
                        triggerAction: 'all',
                        forceSelection: true,
                        value:'SEMUA',
                        width: 140
                    }
                ]
            }, {
                xtype: 'compositefield',
                style: 'margin-top:10px',
                items: [
                    {
                        xtype: 'displayfield',
                        value: 'Status Setoran :'
                    }, {
                        xtype: 'combo',
                        id: 'id_combo_filter_setoran_mp',
                        name: 'combo_filter_setoran_mp',
                        store: setoranStore_mp,
                        typeAhead: true,
                        mode: 'local',
                        displayField: 'setoran_type',
                        editable: false,
                        triggerAction: 'all',
                        forceSelection: true,
                        value:'SEMUA',
                        width: 140
                    }
                ]
            }
        ],
        buttons: [
            {
                text: 'filter',
                handler: function() {
                    storeDataSalesGrid_mp.load({
                        params: {
                            filterBulan_mp: Ext.getCmp('id_date_filter_bulan_mp').getValue(),
                            filterHaridari_mp: Ext.getCmp('id_date_filter_tanggal_dari_mp').getValue(),
                            filterHariSampai_mp: Ext.getCmp('id_date_filter_tanggal_sampai_mp').getValue(),
                            chkPetugasKasir_mp: Ext.getCmp('id_checkbox_filter_petugas_kasir_mp').getValue(),
                            chkNoStruk: Ext.getCmp('id_checkbox_filter_no_struk_mp').getValue(),
                            chkPicPenerima_mp: Ext.getCmp('id_checkbox_filter_pic_penerima_mp').getValue(),
                            filterPetugasKasir_mp: Ext.getCmp('id_txt_petugas_kasir_mp').getValue(),
                            filterNoStruk: Ext.getCmp('id_txt_no_struk_mp').getValue(),
                            filterPicPenerima_mp: Ext.getCmp('id_txt_filter_pic_penerima_mp').getValue(),
                            filterModeKasir_mp: Ext.getCmp('id_combo_filter_cashier_mp').getValue(),
                            filterStatusSetoran_mp: Ext.getCmp('id_combo_filter_setoran_mp').getValue(),
                        }
                    });
                }

            }, {
                text: 'reset',
                handler: function() {
                    Ext.getCmp('id_monitoring_penjualan').getForm().reset();
                }
            }
        ]
    };

    /**
     * selection model of grid data sales penjualan
     */
    var smGridDataSalesPenjualan_mp = new Ext.grid.CheckboxSelectionModel();
    /**
     * sales data grid declaration for the second body
     */
    var gridDataSalesPenjualan_mp = new Ext.grid.GridPanel({
        frame: true,
        border: true,
        stripeRows: true,
        sm: smGridDataSalesPenjualan_mp,
        store: storeDataSalesGrid_mp,
        loadMask: true,
        style: 'margin:0 auto;',
        height: 250,
        columns: [
            {
                header: "Tanggal",
                dataIndex: 'tgl_so',
                sortable: true,
                width: 100
            }, {
                header: "No Struk",
                dataIndex: 'no_so',
                sortable: true,
                width: 150
            }, {
                header: "Total Tagihan",
                dataIndex: 'rp_grand_total',
                sortable: true,
                width: 150
            }, {
                header: "No.Open kasir",
                dataIndex: 'no_open_saldo',
                sortable: true,
                width: 150
            }, {
                header: "Status",
                dataIndex: 'status',
                sortable: true,
                width: 100
            }, {
                header: "Kasir",
                dataIndex: 'userid',
                sortable: true,
                width: 150
            }, {
                header: "PIC Penerima",
                dataIndex: 'kirim_so',
                sortable: true,
                width: 150
            }, {
                header: "Alamat Penerima",
                dataIndex: 'kirim_alamat_so',
                sortable: true,
                width: 200
            }, {
                header: "No.Telp Penerima",
                dataIndex: 'kirim_telp_so',
                sortable: true,
                width: 200
            }, {
                header: "Status Store",
                dataIndex: 'status_store',
                sortable: true,
                width: 100
            }],
        listeners: {
            'rowclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();

                storeDataDetilSalesGrid_mp.reload({
                    params: {
                        no_so: sel[0].get('no_so')
                    }
                });
                storeDataBonusSalesGrid_mp.reload({
                    params: {
                        no_so: sel[0].get('no_so')
                    }
                });
                storeDataDetailPembayaranSalesGrid.reload({
                    params: {
                        no_so: sel[0].get('no_so')
                    }
                });

                storeDataDetailPengirimanSalesGrid_mp.reload({
                    params: {
                        no_so: sel[0].get('no_so')
                    }
                });

                Ext.getCmp('id_txt_dikirim_ke_mp').setValue(sel[0].get('kirim_so'));
                Ext.getCmp('id_txt_alamat_kirim_mp').setValue(sel[0].get('kirim_alamat_so'));
                Ext.getCmp('id_txt_no_telp_kirim_mp').setValue(sel[0].get('kirim_telp_so'));

            }
        },
        bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: storeDataSalesGrid_mp,
            displayInfo: true
        })
    });

    /**
     * selection model of grid data sales penjualan
     */
    var smGridDataDetailSales_mp = new Ext.grid.CheckboxSelectionModel();

    /**
     * selection model of grid data sales penjualan
     */
    var smGridDataBonusPenjualan_mp = new Ext.grid.CheckboxSelectionModel();

    /**
     * selection model of grid data sales penjualan
     */
    var smGridDataDetilPembayaran_mp = new Ext.grid.CheckboxSelectionModel();

    /**
     * selection model of grid data sales penjualan
     */
    var smGridDataPengirimanBarang_mp = new Ext.grid.CheckboxSelectionModel();
    /**
     * sales data grid declaration for the second body
     */
    var gridDataDetailSales_mp = new Ext.grid.GridPanel({
        frame: true,
        border: true,
        stripeRows: true,
        sm: smGridDataDetailSales_mp,
        store: storeDataDetilSalesGrid_mp,
        loadMask: true,
        style: 'margin:0 auto;',
        height: 250,
        columns: [
            {
                header: "Kd Produk",
                dataIndex: 'kd_produk',
                sortable: true,
                width: 150
            }, {
                header: "Nama Produk",
                dataIndex: 'nm_produk',
                sortable: true,
                width: 100
            }, {
                header: "QTY",
                dataIndex: 'qty',
                sortable: true,
                width: 150
            }, {
                header: "Harga Jual",
                dataIndex: 'rp_harga',
                sortable: true,
                width: 100
            }, {
                header: "Diskon",
                dataIndex: 'diskon',
                sortable: true,
                width: 100
            }, {
                header: "Extra Diskon",
                dataIndex: 'rp_ekstra_diskon',
                sortable: true,
                width: 150
            }, {
                header: "Total",
                dataIndex: 'rp_total',
                sortable: true,
                width: 150
            }, {
                header: "Kirim",
                dataIndex: 'is_kirim',
                sortable: true,
                width: 200
            }, {
                header: "Keterangan",
                dataIndex: 'keterangan',
                sortable: true,
                width: 100
            }],
        bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: storeDataDetilSalesGrid_mp,
            displayInfo: true
        })
    });
    //storeDataBonusSalesGrid_mp.load();
    var gridDataBonusPenjualan_mp = new Ext.grid.GridPanel({
        frame: true,
        border: true,
        stripeRows: true,
        sm: smGridDataBonusPenjualan_mp,
        store: storeDataBonusSalesGrid_mp,
        loadMask: true,
        style: 'margin:0 auto;',
        height: 250,
        columns: [
            {
                header: "Kd Barang",
                dataIndex: 'kd_produk',
                sortable: true,
                width: 150
            }, {
                header: "Nama Barang",
                dataIndex: 'nama_produk',
                sortable: true,
                width: 100
            }, {
                header: "Kd Barang Bonus",
                dataIndex: 'kd_produk_bonus',
                sortable: true,
                width: 150
            }, {
                header: "Nama Barang Bonus",
                dataIndex: 'nama_produk',
                sortable: true,
                width: 150
            }, {
                header: "Qty Bonus",
                dataIndex: 'qty_bonus',
                sortable: true,
                width: 100
            }],
        bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: storeDataBonusSalesGrid_mp,
            displayInfo: true
        })
    });

    var gridDataDetilPembayaran_mp = new Ext.grid.GridPanel({
        frame: true,
        border: true,
        stripeRows: true,
        sm: smGridDataDetilPembayaran_mp,
        store: storeDataDetailPembayaranSalesGrid,
        loadMask: true,
        style: 'margin:0 auto;',
        height: 250,
        columns: [
            {
                header: "Jenis Bayar",
                dataIndex: 'nm_pembayaran',
                sortable: true,
                width: 150
            }, {
                header: "Rp. Bayar",
                dataIndex: 'rp_jumlah',
                sortable: true,
                width: 100
            }, {
                header: "Rp. Charge",
                dataIndex: 'rp_charge',
                sortable: true,
                width: 150
            }, {
                header: "No Kartu",
                dataIndex: 'no_kartu',
                sortable: true,
                width: 150
            }, {
                header: "Jatuh Tempo",
                dataIndex: 'tgl_jth_tempo',
                sortable: true,
                width: 100
            }, {
                header: "Keterangan",
                dataIndex: 'keterangan',
                sortable: true,
                width: 100
            }],
        bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: storeDataDetailPembayaranSalesGrid,
            displayInfo: true
        })
    });

    //storeDataDetailPengirimanSalesGrid_mp.load();
    var gridDataPengirimanBarang_mp = new Ext.grid.GridPanel({
        frame: true,
        border: true,
        stripeRows: true,
        sm: smGridDataPengirimanBarang_mp,
        store: storeDataDetailPengirimanSalesGrid_mp,
        loadMask: true,
        style: 'margin:0 auto;',
        height: 185,
        columns: [
            {
                header: "Kode Barang",
                dataIndex: 'kd_produk',
                sortable: true,
                width: 150
            }, {
                header: "Nama Barang",
                dataIndex: 'nama_produk',
                sortable: true,
                width: 100
            }, {
                header: "Jumlah Pengiriman",
                dataIndex: 'qty_kirim',
                sortable: true,
                width: 150
            }, {
                header: "Jumlah Terkirim",
                dataIndex: 'qty_dikirim',
                sortable: true,
                width: 150
            }, {
                header: "Sisa Kirim",
                dataIndex: 'qty_sisa',
                sortable: true,
                width: 100
            }],
        bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: storeDataDetailPengirimanSalesGrid_mp,
            displayInfo: true
        })
    });

    /**
     * declaration of right content of form pengirman barang
     */
    contentFormPengirimanDataBarang_mp = {
        border: true,
        frame: true,
        height: 200,
        items: [
            {
                layout: 'column',
                border: false,
                items: [
                    {
                        columnWidth: .3,
                        frame: true,
                        height: 185,
                        items: [{
                                xtype: 'compositefield',
                                items: [
                                    {
                                        xtype: 'displayfield',
                                        value: 'Dikirim Ke :'
                                    }, {
                                        xtype: 'textfield',
                                        width: 200,
                                        name: 'txt_dikirim_ke_mp',
                                        id: 'id_txt_dikirim_ke_mp',
                                        readOnly: true,
                                        fieldClass: 'readonly-input'
                                    }
                                ]
                            }, {
                                xtype: 'compositefield',
                                style: 'margin-top:20px',
                                items: [
                                    {
                                        xtype: 'displayfield',
                                        value: 'Alamat &nbsp;&nbsp;&nbsp;&nbsp;:'
                                    }, {
                                        xtype: 'textarea',
                                        width: 200,
                                        name: 'txt_alamat_kirim_mp',
                                        id: 'id_txt_alamat_kirim_mp',
                                        readOnly: true,
                                        fieldClass: 'readonly-input'
                                    }
                                ]
                            },
                            {
                                xtype: 'compositefield',
                                style: 'margin-top:20px',
                                items: [
                                    {
                                        xtype: 'displayfield',
                                        value: 'No.Telp&nbsp;&nbsp;&nbsp;:'
                                    }, {
                                        xtype: 'textfield',
                                        width: 200,
                                        name: 'txt_no_telp_kirim_mp',
                                        id: 'id_txt_no_telp_kirim_mp',
                                        readOnly: true,
                                        fieldClass: 'readonly-input'
                                    }
                                ]
                            }
                        ]
                    },
                    {
                        columnWidth: .7,
                        frame: false,
                        layout: 'column',
                        items: [
                            gridDataPengirimanBarang_mp
                        ]
                    }]
            }
        ]
    };

    /**
     * declaration of form data pengiriman barang on the fourth tab
     */
    var formDataPengirimanBarang_mp = {
        xtype: 'panel',
        layout: 'column',
        frame: false,
        autoheight: true,
        items: [
            contentFormPengirimanDataBarang_mp
        ]
    };

    var tabsMonitoringPenjualan_mp = new Ext.TabPanel({
        activeTab: 0,
        frame: true,
        defaults: {autoHeight: true},
        items: [
            {
                title: 'Data Detil Penjualan',
                items: [gridDataDetailSales_mp]
            }, {
                title: 'Data Bonus Penjualan',
                items: [gridDataBonusPenjualan_mp]
            }, {
                title: 'Data Detil Pembayaran',
                items: [gridDataDetilPembayaran_mp]
            }, {
                title: 'Data Pengiriman Barang dengan Surat jalan',
                items: [formDataPengirimanBarang_mp]
            }
        ]
    });

    var firstBodyMonitoringPenjualan_mp = {
        id: 'id_filter_header_mp',
        title: 'Filter Data',
        layout: 'column',
        xtype: 'fieldset',
        autoheight: true,
        anchor: '100%',
        items: [
            firstColumnMonitoringPenjualan_mp, secondColumnMonitoringPenjualan_mp, thirdColumnMonitoringPenjualan_mp
        ]
    };

    var secondBodyMonitoringPenjualan_mp = {
        title: 'Data Penjualan',
        xtype: 'fieldset',
        autoheight: true,
        anchor: '100%',
        items: [
            gridDataSalesPenjualan_mp, tabsMonitoringPenjualan_mp
        ]
    };

    var thirdBodyMonitoringPenjualan_mp = {
        //title: 'Data Penjualan',
        xtype: 'fieldset',
        autoheight: true,
        anchor: '100%',
        items: [
            tabsMonitoringPenjualan_mp
        ]
    };

    Ext.ns('id_monitoring_penjualan');
    var monitoringPenjualanPanel_mp = new Ext.FormPanel({
        id: 'id_monitoring_penjualan',
        autoScroll: true,
        bodyStyle: 'padding-right:20px;',
        border: false,
        frame: true,
        //autoScroll: false,
        //bodyStyle: 'padding:5px;',
        items: [
            firstBodyMonitoringPenjualan_mp, secondBodyMonitoringPenjualan_mp, thirdBodyMonitoringPenjualan_mp
        ]

    });

</script>
