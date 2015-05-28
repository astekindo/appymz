<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    /* START GRID */
    var strkonsinyasiapprovepo = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_po',
                'tanggal_po',
                'kd_supplier_po',
                'nama_supplier',
                'masa_berlaku_po',
                'rp_jumlah_po',
                'ppn_percent_po',
                'rp_ppn_po',
                'order_by_po',
                'rp_total_po',
                'kirim_po',
                'alamat_kirim_po',
                'pot_konsinyasi',
                'remark',
                'rp_diskon_po'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("konsinyasi_approve_po/get_rows") ?>',
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

    // search field
    var search_konsinyasi_approve_po = new Ext.app.SearchField({
        store: strkonsinyasiapprovepo,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearch_konsinyasi_approve_po'
    });

    // top toolbar
    var tb_konsinyasi_approve_po = new Ext.Toolbar({
        items: ['No Surat Pesanan ', search_konsinyasi_approve_po, '->', '<i>Klik row untuk melihat detail PO</i>']
    });

    // checkbox grid
    var smgridKonsAppPO = new Ext.grid.CheckboxSelectionModel();
    var smgridKonsAppDetPO = new Ext.grid.CheckboxSelectionModel();

    // row actions
    var action_konsinyasi_approve_po = new Ext.ux.grid.RowActions({
        actions: [
            {iconCls: 'icon-approve-record', qtip: 'Approve'},
            {iconCls: 'icon-delete-record', qtip: 'Reject'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });

    action_konsinyasi_approve_po.on('action', function(grid, record, action, row, col) {
        var kd_app_po = record.get('no_po');
        var status = 0;
        switch (action) {
            case 'icon-approve-record':
                edit_konsinyasi_approve_po(kd_app_po, 1, 'approve');
                break;
            case 'icon-delete-record' :
                edit_konsinyasi_approve_po(kd_app_po, 9, 'reject');
                break;
        }
    });

    // data store
    var strkonsinyasiapprovepodetail = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_produk',
                'nama_produk',
                'no_po',
                'qty_po',
                {name: 'disk_supp1_po', allowBlank: false, type: 'float'},
                {name: 'disk_supp2_po', allowBlank: false, type: 'float'},
                {name: 'disk_supp3_po', allowBlank: false, type: 'float'},
                {name: 'disk_supp4_po', allowBlank: false, type: 'float'},
                // 'disk_supp1_po',
                // 'disk_supp2_po',
                // 'disk_supp3_po',
                // 'disk_supp4_po',
                'disk_amt_supp5_po',
                'price_supp_po',
                'net_price_po',
                'dpp_po',
                'rp_disk_po',
                'rp_jumlah_po',
                'rp_total_po'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("konsinyasi_approve_po/get_rows_detail") ?>',
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

    var gridKonsAppPO = new Ext.grid.EditorGridPanel({
        id: 'gridKonsAppPO',
        frame: true,
        border: true,
        stripeRows: true,
        sm: smgridKonsAppPO,
        store: strkonsinyasiapprovepo,
        loadMask: true,
        //title: 'PO',
        style: 'margin:0 auto;',
        height: 235,
        // width: 550,
        columns: [action_konsinyasi_approve_po, {
                header: "No Surat Pesanan",
                dataIndex: 'no_po',
                sortable: true,
                width: 110
            }, {
                header: "Nama Supplier",
                dataIndex: 'nama_supplier',
                sortable: true,
                width: 200
            }, {
                header: "Tanggal",
                dataIndex: 'tanggal_po',
                sortable: true,
                width: 80
            }, {
                xtype: 'numbercolumn',
                format: '0',
                header: "Masa Berlaku",
                dataIndex: 'masa_berlaku_po',
                sortable: true,
                align: 'center',
                width: 100
            }, {
                xtype: 'numbercolumn',
                header: "Jumlah (Rp)",
                dataIndex: 'rp_jumlah_po',
                sortable: true,
                align: 'right',
                format: '0,0',
                width: 110
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: "Diskon (Rp)",
                dataIndex: 'rp_diskon_po',
                sortable: true,
                width: 100
            }, {
                xtype: 'numbercolumn',
                align: 'center',
                format: '0',
                header: "PPN (%)",
                dataIndex: 'ppn_percent_po',
                sortable: true,
                width: 80
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: "PPN (Rp)",
                dataIndex: 'rp_ppn_po',
                sortable: true,
                width: 100
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: "Total (Rp)",
                dataIndex: 'rp_total_po',
                sortable: true,
                width: 100
            }, {
                header: "Order By",
                dataIndex: 'order_by_po',
                sortable: true,
                width: 100
            }, {
                header: "Kirim",
                dataIndex: 'kirim_po',
                sortable: true,
                width: 150
            }, {
                header: "Alamat Kirim",
                dataIndex: 'alamat_kirim_po',
                sortable: true,
                width: 150
            }, {
                header: "Remark",
                dataIndex: 'remark',
                sortable: true,
                width: 150
            }],
        plugins: action_konsinyasi_approve_po, listeners: {
            'rowclick': function() {
                var sm = gridKonsAppPO.getSelectionModel();
                var sel = sm.getSelections();
                gridKonsAppDetPO.store.proxy.conn.url = '<?= site_url("konsinyasi_approve_po/get_rows_detail") ?>/' + sel[0].get('no_po');
                gridKonsAppDetPO.store.reload();
            }
        },
        tbar: tb_konsinyasi_approve_po,
        // bbar: new Ext.PagingToolbar({
        // pageSize: ENDPAGE,
        // store: strkonsinyasiapprovepo,
        // displayInfo: true
        // })
    });

    var gridKonsAppDetPO = new Ext.grid.EditorGridPanel({
        id: 'gridKonsAppDetPO',
        frame: true,
        border: true,
        sm: smgridKonsAppDetPO,
        stripeRows: true,
        store: strkonsinyasiapprovepodetail,
        loadMask: true,
        style: 'margin:0 auto;',
        height: 220,
        view: new Ext.ux.grid.LockingGridView(),
        colModel: new Ext.ux.grid.LockingColumnModel([{
                header: "Kode Barang",
                dataIndex: 'kd_produk',
                locked: true,
                sortable: true,
                width: 100
            },{
                header: "Nama Barang",
                dataIndex: 'nama_produk',
                locked: true,
                sortable: true,
                width: 300
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: "Price Supplier",
                dataIndex: 'price_supp_po',
                sortable: true,
                width: 125
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: "Jumlah",
                dataIndex: 'qty_po',
                sortable: true,
                width: 70
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                // format: '0,0',
                header: "Disk Supp1",
                dataIndex: 'disk_supp1_po',
                sortable: true,
                width: 100
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                // format: '0,0',
                header: "Disk Supp2",
                dataIndex: 'disk_supp2_po',
                sortable: true,
                width: 100
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                // format: '0,0',
                header: "Disk Supp3",
                dataIndex: 'disk_supp3_po',
                sortable: true,
                width: 100
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                // format: '0,0',
                header: "Disk Supp4",
                dataIndex: 'disk_supp4_po',
                sortable: true,
                width: 100
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: "Disk Supp5",
                dataIndex: 'disk_amt_supp5_po',
                sortable: true,
                width: 100
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: "Disk",
                dataIndex: 'rp_disk_po',
                sortable: true,
                width: 100
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: "Net Price",
                dataIndex: 'net_price_po',
                sortable: true,
                width: 100
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: "DPP",
                dataIndex: 'dpp_po',
                sortable: true,
                width: 70
                        // },{
                        // xtype: 'numbercolumn',
                        // align: 'right',
                        // format: '0,0',
                        // header: "Jumlah PO",
                        // dataIndex: 'rp_jumlah_po',
                        // sortable: true,
                        // width: 100
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: "Total",
                dataIndex: 'rp_total_po',
                sortable: true,
                width: 100
            }])
    });


    var konsinyasiapprovepo = new Ext.FormPanel({
        id: 'konsinyasiapprovepo',
        border: false,
        frame: true,
        autoScroll: true,
        bodyStyle: 'padding:2px 20px 5px 5px;',
        items: [gridKonsAppPO, gridKonsAppDetPO]
    });
    // grid



    function edit_konsinyasi_approve_po(kd_app_po, status, statement) {

        var sm = gridKonsAppPO.getSelectionModel();
        var sel = sm.getSelections();
        if (sel.length > 0) {
            Ext.Msg.show({
                title: 'Confirm',
                msg: 'Are you sure to ' + statement + ' selected PO ?',
                buttons: Ext.Msg.YESNO,
                fn: function(btn) {
                    if (btn == 'yes') {

                        var data = kd_app_po + '_' + status;

                        Ext.Ajax.request({
                            url: '<?= site_url("konsinyasi_approve_po/update_row") ?>',
                            method: 'POST',
                            params: {
                                postdata: data
                            },
                            callback: function(opt, success, responseObj) {
                                var de = Ext.util.JSON.decode(responseObj.responseText);
                                if (de.success == true) {
                                    strkonsinyasiapprovepo.reload();
                                    strkonsinyasiapprovepo.load({
                                        params: {
                                            start: STARTPAGE,
                                            limit: ENDPAGE
                                        }
                                    });
                                    strkonsinyasiapprovepodetail.reload();
                                    strkonsinyasiapprovepodetail.load({
                                        params: {
                                            start: STARTPAGE,
                                            limit: ENDPAGE
                                        }
                                    });
                                } else {
                                    Ext.Msg.show({
                                        title: 'Error',
                                        msg: de.errMsg,
                                        modal: true,
                                        icon: Ext.Msg.ERROR,
                                        buttons: Ext.Msg.OK,
                                        fn: function(btn) {
                                            if (btn == 'ok' && de.errMsg == 'Session Expired') {
                                                window.location = '<?= site_url("auth/login") ?>';
                                            }
                                        }
                                    });
                                }
                            }
                        });
                    }
                }
            });
        }
        else {
            Ext.Msg.show({
                title: 'Info',
                msg: 'Please selected row',
                modal: true,
                icon: Ext.Msg.ERROR,
                buttons: Ext.Msg.OK
            });
        }

    }


</script>