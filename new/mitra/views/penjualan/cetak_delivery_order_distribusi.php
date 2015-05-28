<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>

<script type="text/javascript">
    /**
     * store grid delivery order
     */
    var storeGridDeliveryOrderDist_cdod = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_do',
                'tanggal',
                'no_so',
                'status',
                'created_by',
                'created_date',
                'updated_by',
                'updated_date',
                'pic_penerima',
                'alamat_penerima',
                'no_telp_penerima',
                'keterangan',
                'tanggal_kirim'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("cetak_delivery_order_dist_controller/finalGetDataDODist") ?>',
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
     * store grid delivery order
     */
    var storeGridDeliveryOrderDetailDist_cdod = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_do',
                'kd_barang',
                'nama_produk',
                'qty',
                'qty_sj'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("cetak_delivery_order_dist_controller/finalGetDataDODistDetail") ?>',
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

    storeGridDeliveryOrderDetailDist_cdod.on('load', function() {
        var sm = gridDeliveryOrderDist_cdod.getSelectionModel();
        var sel = sm.getSelections();
        storeGridDeliveryOrderDetailDist_cdod.setBaseParam('no_do', sel[0].get('no_do'));
    });


    /**
     * search grid delivery order
     */
    var searchGridDeliveryOrderDist_cdod = new Ext.app.SearchField({
        store: storeGridDeliveryOrderDist_cdod,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_search_grid_delivery_order_dist_cdod'
    });

    /**
     * search grid delivery order detail
     */
    var searchGridDeliveryOrderDetailDist_cdod = new Ext.app.SearchField({
        store: storeGridDeliveryOrderDetailDist_cdod,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_search_grid_delivery_order_detail_dist_cdod'
    });


    /**
     *grid delivery order / first grid 
     */
    var gridDeliveryOrderDist_cdod = new Ext.grid.GridPanel({
        frame: true,
        border: true,
        stripeRows: true,
        //sm: smGridBarterBarang_cbb,
        store: storeGridDeliveryOrderDist_cdod,
        loadMask: true,
        height: 250,
        title: 'Data Delivery Order',
        style: 'margin:0 auto;',
        columns: [
            {
                header: "No DO",
                dataIndex: 'no_do',
                sortable: true,
                width: 100
            }, {
                header: "Tanggal",
                dataIndex: 'tanggal',
                sortable: true,
                width: 150
            }, {
                header: "No SO",
                dataIndex: 'no_so',
                sortable: true,
                width: 100
            }, {
                header: "Tanggal SO",
                dataIndex: 'tanggal_so',
                sortable: true,
                width: 70
            }, {
                header: "Status",
                dataIndex: 'status',
                sortable: true,
                width: 70
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
                header: "No.telp Penerima",
                dataIndex: 'no_telp_penerima',
                sortable: true,
                width: 150
            }, {
                header: "Tanggal Kirim",
                dataIndex: 'tanggal_kirim',
                sortable: true,
                width: 150
            }, {
                header: "Keterangan",
                dataIndex: 'keterangan',
                sortable: true,
                width: 150
            }],
        listeners: {
            'rowclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                storeGridDeliveryOrderDetailDist_cdod.reload({
                    params: {
                        no_do: sel[0].get('no_do'),
                    }
                });
            }
        },
        tbar: new Ext.Toolbar({
            items: [searchGridDeliveryOrderDist_cdod]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: storeGridDeliveryOrderDist_cdod,
            displayInfo: true
        })
    });

    /**
     *grid delivery order detail / second grid 
     */
    var gridDeliveryOrderDetailDist_cdod = new Ext.grid.GridPanel({
        frame: true,
        border: true,
        stripeRows: true,
        //sm: smGridBarterBarang_cbb,
        store: storeGridDeliveryOrderDetailDist_cdod,
        loadMask: true,
        height: 250,
        title: 'Data Delivery Order',
        style: 'margin:0 auto;',
        columns: [
            {
                header: "No DO",
                dataIndex: 'no_do',
                sortable: true,
                width: 100
            }, {
                header: "Kd Barang",
                dataIndex: 'kd_barang',
                sortable: true,
                width: 150
            }, {
                header: "Nama Barang",
                dataIndex: 'nama_produk',
                sortable: true,
                width: 100
            }, {
                header: "QTY",
                dataIndex: 'qty',
                sortable: true,
                width: 70
            }, {
                header: "QTY SJ",
                dataIndex: 'qty_sj',
                sortable: true,
                width: 70
            }
        ],
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                windowDeliveryOrderPrintDist_cdod.show();
                Ext.getDom('id_cetak_delivery_order_dist_print_cdod').src = '<?= site_url("penjualan_do_distribusi/print_form") ?>' + '/' + sel[0].get('no_do');
            }
        },
        tbar: new Ext.Toolbar({
            items: [searchGridDeliveryOrderDetailDist_cdod]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: storeGridDeliveryOrderDetailDist_cdod,
            displayInfo: true
        })
    });

    /**
     * header 
     */
    var headerCetakDeliveryOrderDist_cdod = {
        layout: 'column',
        border: false,
        buttonAlign: 'left',
        style: 'padding:5px',
        frame: true,
        items: [{
                columnWidth: .6,
                border: false,
                labelWidth: 100,
                buttonAlign: 'left',
                defaults: {labelSeparator: ''},
                items: [
                    {
                        xtype: 'compositefield',
                        achor: '100%',
                        items: [{
                                xtype: 'displayfield',
                                value: 'Tanggal'
                            },
                            {
                                xtype: 'datefield',
                                name: 'tanggal_awal',
                                id: 'id_tanggal_awal_cdod',
                                maxLength: 255,
                                width: 200,
                                value: '',
                                format: 'Y-m-d'

                            }, {
                                xtype: 'displayfield',
                                value: 's.d'
                            }, {
                                xtype: 'datefield',
                                //emptyText: 'Tanggal Kwitansi',
                                name: 'tanggal_akhir',
                                id: 'id_tanggal_akhir_cdod',
                                maxLength: 255,
                                width: 200,
                                value: '',
                                format: 'Y-m-d'

                            }
                        ]
                    }

                ],
                buttons: [{
                        text: 'filter',
                        handler: function() {
                            storeGridDeliveryOrderDist_cdod.reload({
                                params: {
                                    tgl_awal: Ext.getCmp('id_tanggal_awal_cdod').getValue(),
                                    tgl_akhir: Ext.getCmp('id_tanggal_akhir_cdod').getValue()
                                }
                            });
                        }
                    }, {
                        text: 'reset',
                        handler: function() {
                            gridDeliveryOrderDist_cdod.store.removeAll();
                            gridDeliveryOrderDetailDist_cdod.store.removeAll();
                            Ext.getCmp('cetak_do_distribusi').getForm().reset();
                        }
                    }]
            }]
    };

    /**
     * deklarai window print
     */
    var windowDeliveryOrderPrintDist_cdod = new Ext.Window({
        id: 'id_window_cetak_delivery_order_dist_print_cdod',
        title: 'Print Delivery Order Dist',
        closeAction: 'hide',
        width: 800,
        height: 450,
        layout: 'fit',
        border: false,
        //html: 'div id="lap_do" />'
        html: '<iframe style="width:100%;height:100%;" id="id_cetak_delivery_order_dist_print_cdod" src=""></iframe>'
    });

    /**
     * main panel container
     */
    var cetakDeliveryOrderDist_cdod = new Ext.FormPanel({
        id: 'cetak_do_distribusi',
        monitorValid: true,
        border: false,
        frame: true,
        bodyStyle: 'padding-right:20px;',
        labelWidth: 130,
        autoScroll: true,
        items: [headerCetakDeliveryOrderDist_cdod, {
                bodyStyle: {
                    margin: '0px 15px 15px 0px'
                },
                items: [gridDeliveryOrderDist_cdod]
            }, gridDeliveryOrderDetailDist_cdod],
        buttons: [
            {
                text: 'cetak',
                handler: function() {
                    var sm = gridDeliveryOrderDist_cdod.getSelectionModel();
                    var sel = sm.getSelections();
                    windowDeliveryOrderPrintDist_cdod.show();
                    Ext.getDom('id_cetak_delivery_order_dist_print_cdod').src = '<?= site_url("penjualan_do_distribusi/print_form") ?>' + '/' + sel[0].get('no_do');
                }
            }
        ]
    });


</script>