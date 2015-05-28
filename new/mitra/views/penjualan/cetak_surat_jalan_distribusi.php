<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>

<script type="text/javascript">
    /**
     * store grid delivery order
     */
    var storeGridSuratJalanDist_csjd = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_sj',
                'tanggal',
                'no_do',
                'kd_ekspedisi',
                'no_kendaraan',
                'sopir',
                'pic_penerima',
                'alamat_penerima',
                'no_telp_penerima',
                'keterangan',
                'is_kembali',
                'tanggal_kembali',
                'penerima',
                'ket_pengembalian',
                'created_by',
                'created_date'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("cetak_surat_jalan_distribusi_controller/finalGetDataSJDist") ?>',
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
    var storeGridSuratJalanDetailDist_csjd = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_sj',
                'kd_produk',
                'nama_produk',
                'qty',
                'rp_satuan',
                'rp_diskon',
                'rp_total',
                'kd_lokasi',
                'kd_blok',
                'kd_sub_blok',
                'keterangan',
                'qty_kembali',
                'ket_kembali'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("cetak_surat_jalan_distribusi_controller/finalGetDataSJDistDetail") ?>',
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

    storeGridSuratJalanDetailDist_csjd.on('load', function() {
        var sm = gridSuratJalanDist_csjd.getSelectionModel();
        var sel = sm.getSelections();
        if (sel.length > 0) {
            storeGridSuratJalanDetailDist_csjd.setBaseParam('no_sj', sel[0].get('no_sj'));
        }
    });


    /**
     * search grid delivery order
     */
    var searchGridSuratJalanDist_csjd = new Ext.app.SearchField({
        store: storeGridSuratJalanDist_csjd,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_search_grid_surat_jalan_dist_csjd'
    });

    /**
     * search grid delivery order detail
     */
    var searchGridSuratJalanDetailDist_csjd = new Ext.app.SearchField({
        store: storeGridSuratJalanDetailDist_csjd,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_search_grid_surat_jalan_dist_detail_csjd'
    });


    /**
     *grid delivery order / first grid 
     */
    var gridSuratJalanDist_csjd = new Ext.grid.GridPanel({
        frame: true,
        border: true,
        stripeRows: true,
        //sm: smGridBarterBarang_cbb,
        store: storeGridSuratJalanDist_csjd,
        loadMask: true,
        height: 250,
        title: 'Data Surat Jalan',
        style: 'margin:0 auto;',
        columns: [
            {
                header: "No Surat Jalan",
                dataIndex: 'no_sj',
                sortable: true,
                width: 100
            }, {
                header: "Tanggal",
                dataIndex: 'tanggal',
                sortable: true,
                width: 150
            }, {
                header: "No DO",
                dataIndex: 'no_do',
                sortable: true,
                width: 100
            }, {
                header: "Kode Ekspedisi",
                dataIndex: 'kd_ekspedisi',
                sortable: true,
                width: 70
            }, {
                header: "NO Kendaraan",
                dataIndex: 'no_kendaraan',
                sortable: true,
                width: 70
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
                width: 150
            }, {
                header: "No.telp Penerima",
                dataIndex: 'no_telp_penerima',
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
                storeGridSuratJalanDetailDist_csjd.reload({
                    params: {
                        no_sj: sel[0].get('no_sj'),
                    }
                });
            }
        },
        tbar: new Ext.Toolbar({
            items: [searchGridSuratJalanDist_csjd]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: storeGridSuratJalanDist_csjd,
            displayInfo: true
        })
    });

    /**
     *grid delivery order detail / second grid 
     */
    var gridSuratJalanDistDetail_csj = new Ext.grid.GridPanel({
        frame: true,
        border: true,
        stripeRows: true,
        //sm: smGridBarterBarang_cbb,
        store: storeGridSuratJalanDetailDist_csjd,
        loadMask: true,
        height: 250,
        title: 'Data Surat Jalan Detail',
        style: 'margin:0 auto;',
        columns: [
            {
                header: "No Surat Jalan",
                dataIndex: 'no_sj',
                sortable: true,
                width: 100
            }, {
                header: "Kd Barang",
                dataIndex: 'kd_produk',
                sortable: true,
                width: 150
            }, {
                header: "Nama Barang",
                dataIndex: 'nama_produk',
                sortable: true,
                width: 250
            }, {
                header: "Qty",
                dataIndex: 'qty',
                sortable: true,
                width: 70
            }, {
                header: "Kd Lokasi",
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
            }, {
                header: "Qty Kembali",
                dataIndex: 'qty_kembali',
                sortable: true,
                width: 70
            }, {
                header: "Ket. Kembali",
                dataIndex: 'ket_kembali',
                sortable: true,
                width: 250
            }],
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                windowSuratJalanDistPrint_csjd.show();
                Ext.getDom('id_cetak_surat_jalan_dist_print_csjd').src = '<?= site_url("penjualan_sj_distribusi/print_form") ?>' + '/' + sel[0].get('no_sj');
            }
        },
        tbar: new Ext.Toolbar({
            items: [searchGridSuratJalanDetailDist_csjd]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: 10,
            store: storeGridSuratJalanDetailDist_csjd,
            displayInfo: true
        })
    });

    /**
     * header 
     */
    var headerCetakSuratJalanDist_csjd = {
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
                                id: 'id_tanggal_awal_csj',
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
                                id: 'id_tanggal_akhir_csj',
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
                            storeGridSuratJalanDist_csjd.reload({
                                params: {
                                    tgl_awal: Ext.getCmp('id_tanggal_awal_csj').getValue(),
                                    tgl_akhir: Ext.getCmp('id_tanggal_akhir_csj').getValue()
                                }
                            });
                        }
                    }, {
                        text: 'reset',
                        handler: function() {
                            gridSuratJalanDist_csjd.store.removeAll();
                            gridSuratJalanDistDetail_csj.store.removeAll();
                            Ext.getCmp('cetak_surat_jalan').getForm().reset();
                        }
                    }]
            }]
    };

    /**
     * deklarai window print
     */
    var windowSuratJalanDistPrint_csjd = new Ext.Window({
        id: 'id_window_cetak_surat_jalan_print_csj',
        title: 'Print Surat Jalan Dist',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        //html: 'div id="lap_do" />'
        html: '<iframe style="width:100%;height:100%;" id="id_cetak_surat_jalan_dist_print_csjd" src=""></iframe>'
    });

    /**
     * main panel container
     */
    var cetakSuratJalanDist_csjd = new Ext.FormPanel({
        id: 'cetak_surat_jalan_distribusi',
        monitorValid: true,
        border: false,
        frame: true,
        bodyStyle: 'padding-right:20px;',
        labelWidth: 130,
        autoScroll: true,
        items: [headerCetakSuratJalanDist_csjd, {
                bodyStyle: {
                    margin: '0px 15px 15px 0px'
                },
                items: [gridSuratJalanDist_csjd]
            }, gridSuratJalanDistDetail_csj],
        buttons: [
            {
                text: 'cetak',
                handler: function() {
                    var sm = gridSuratJalanDist_csjd.getSelectionModel();
                    var sel = sm.getSelections();
                    windowSuratJalanDistPrint_csjd.show();
                    Ext.getDom('id_cetak_surat_jalan_dist_print_csjd').src = '<?= site_url("penjualan_sj_distribusi/print_form") ?>' + '/' + sel[0].get('no_sj');
                }
            }
        ]
    });


</script>