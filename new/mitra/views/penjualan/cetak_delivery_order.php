<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>

<script type="text/javascript">
    /**
     * store grid delivery order
     */
    var storeGridDeliveryOrder_cdo = new Ext.data.Store({
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
            url: '<?= site_url("cetak_delivery_order_controller/finalGetDataDO") ?>',
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
    var storeGridDeliveryOrderDetail_cdo = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_do',
                'kd_barang',
                'nama_produk',
                'qty',
                'qty_sj',
                'qty_retur_do'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("cetak_delivery_order_controller/finalGetDataDODetail") ?>',
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

    storeGridDeliveryOrderDetail_cdo.on('load', function() {
        var sm = gridDeliveryOrder_cdo.getSelectionModel();
        var sel = sm.getSelections();
        storeGridDeliveryOrderDetail_cdo.setBaseParam('no_do', sel[0].get('no_do'));
    });


    /**
     * search grid delivery order
     */
    var searchGridDeliveryOrder_cdo = new Ext.app.SearchField({
        store: storeGridDeliveryOrder_cdo,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_search_grid_delivery_order_cdo'
    });

    /**
     * search grid delivery order detail
     */
    var searchGridDeliveryOrderDetail_cdo = new Ext.app.SearchField({
        store: storeGridDeliveryOrderDetail_cdo,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_search_grid_delivery_order_detail_cdo'
    });


    /**
     *grid delivery order / first grid 
     */
    var gridDeliveryOrder_cdo = new Ext.grid.GridPanel({
        frame: true,
        border: true,
        stripeRows: true,
        //sm: smGridBarterBarang_cbb,
        store: storeGridDeliveryOrder_cdo,
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
                storeGridDeliveryOrderDetail_cdo.reload({
                    params: {
                        no_do: sel[0].get('no_do'),
                    }
                });
            }
        },
        tbar: new Ext.Toolbar({
            items: [searchGridDeliveryOrder_cdo]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: storeGridDeliveryOrder_cdo,
            displayInfo: true
        })
    });

    /**
     *grid delivery order detail / second grid 
     */
    var gridDeliveryOrderDetail_cdo = new Ext.grid.GridPanel({
        frame: true,
        border: true,
        stripeRows: true,
        //sm: smGridBarterBarang_cbb,
        store: storeGridDeliveryOrderDetail_cdo,
        loadMask: true,
        height: 250,
        title: 'Data Delivery Order Detail',
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
            }, {
                header: "QTY retur",
                dataIndex: 'qty_retur_do',
                sortable: true,
                width: 250
            }],
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                windowDeliveryOrderPrint_cdo.show();
                Ext.getDom('id_cetak_delivery_order_print_cdo').src = '<?= site_url("penjualan_do/print_form") ?>' + '/' + sel[0].get('no_do');
            }
        },
        tbar: new Ext.Toolbar({
            items: [searchGridDeliveryOrderDetail_cdo]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: storeGridDeliveryOrderDetail_cdo,
            displayInfo: true
        })
    });

    /**
     * header 
     */
    var headerCetakDeliveryOrder_cdo = {
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
                                id: 'id_tanggal_awal_cdo',
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
                                id: 'id_tanggal_akhir_cdo',
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
                            storeGridDeliveryOrder_cdo.reload({
                                params: {
                                    tgl_awal: Ext.getCmp('id_tanggal_awal_cdo').getValue(),
                                    tgl_akhir: Ext.getCmp('id_tanggal_akhir_cdo').getValue()
                                }
                            });
                        }
                    }, {
                        text: 'reset',
                        handler: function() {
                            gridDeliveryOrder_cdo.store.removeAll();
                            gridDeliveryOrderDetail_cdo.store.removeAll();
                            Ext.getCmp('cetak_delivery_order').getForm().reset();
                        }
                    }]
            }]
    };

    /**
     * deklarai window print
     */
    var windowDeliveryOrderPrint_cdo = new Ext.Window({
        id: 'id_window_cetak_delivery_order_print_cdo',
        title: 'Print Delivery Order',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        //html: 'div id="lap_do" />'
        html: '<iframe style="width:100%;height:100%;" id="id_cetak_delivery_order_print_cdo" src=""></iframe>'
    });

    /**
     * main panel container
     */
    var cetakDeliveryOrder_cod = new Ext.FormPanel({
        id: 'cetak_delivery_order',
        monitorValid: true,
        border: false,
        frame: true,
        bodyStyle: 'padding-right:20px;',
        labelWidth: 130,
        autoScroll: true,
        items: [headerCetakDeliveryOrder_cdo, {
                bodyStyle: {
                    margin: '0px 15px 15px 0px'
                },
                items: [gridDeliveryOrder_cdo]
            }, gridDeliveryOrderDetail_cdo],
        buttons: [
            {
                text: 'cetak',
                handler: function() {
                    var sm = gridDeliveryOrder_cdo.getSelectionModel();
                    var sel = sm.getSelections();
                    windowDeliveryOrderPrint_cdo.show();
                    Ext.getDom('id_cetak_delivery_order_print_cdo').src = '<?= site_url("penjualan_do/print_form") ?>' + '/' + sel[0].get('no_do');
                }
            }
        ]
    });


</script>