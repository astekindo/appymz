<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    /* START GRID */
    var strapprovalsuratpesanan = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_sp',
                'tgl_sp',
                'kd_supplier',
                'nama_supplier',
                'masa_berlaku',
                'rp_jumlah_po',
                'ppn_percent_po',
                'rp_ppn_po',
                'order_by',
                'rp_total_po',
                'kirim_sp',
                'alamat_kirim_sp',
                'pot_konsinyasi',
                'remark',
                'rp_diskon_po'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("konsinyasi_approve_surat_pesanan/get_rows") ?>',
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
    
strapprovalsuratpesanan.load();
strapprovalsuratpesanan.reload();

    // search field
    var search_konsinyasi_approve_sp = new Ext.app.SearchField({
        store: strapprovalsuratpesanan,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearch_konsinyasi_approve_sp'
    });

    // top toolbar
    var tb_konsinyasi_approve_sp = new Ext.Toolbar({
        items: ['No Surat Pesanan ', search_konsinyasi_approve_sp, '->', '<i>Klik row untuk melihat detail surat pesanan</i>']
    });

    // checkbox grid
    var smgridKonsAppSuratPesanan = new Ext.grid.CheckboxSelectionModel();
    var smgridKonsAppDetSuratPesanan = new Ext.grid.CheckboxSelectionModel();

    // row actions
    var action_konsinyasi_approve_sp = new Ext.ux.grid.RowActions({
        actions: [
            {iconCls: 'icon-approve-record', qtip: 'Approve'},
            {iconCls: 'icon-delete-record', qtip: 'Reject'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });

    action_konsinyasi_approve_sp.on('action', function(grid, record, action, row, col) {
        var kd_app_po = record.get('no_sp');
        var status = 0;
        switch (action) {
            case 'icon-approve-record':
                edit_konsinyasi_approve_surat_pesanan(kd_app_po, 1, 'approve');
                break;
            case 'icon-delete-record' :
                edit_konsinyasi_approve_surat_pesanan(kd_app_po, 9, 'reject');
                break;
        }
    });

    // data store
    var strapprovalsuratpesanandetail = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_produk',
                'nama_produk',
                'no_sp',
                'qty_sp',
                {name: 'disk_supp1_po', allowBlank: false, type: 'float'},
                {name: 'disk_supp2_po', allowBlank: false, type: 'float'},
                {name: 'disk_supp3_po', allowBlank: false, type: 'float'},
                {name: 'disk_supp4_po', allowBlank: false, type: 'float'},
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
            url: '<?= site_url("konsinyasi_approve_surat_pesanan/get_rows_detail") ?>',
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

    var gridKonsAppSuratPesanan = new Ext.grid.EditorGridPanel({
        id: 'gridKonsAppSuratPesanan',
        frame: true,
        border: true,
        stripeRows: true,
        sm: smgridKonsAppSuratPesanan,
        store: strapprovalsuratpesanan,
        loadMask: true,
        //title: 'PO',
        style: 'margin:0 auto;',
        height: 235,
        // width: 550,
        columns: [action_konsinyasi_approve_sp, {
                header: "No Surat Pesanan",
                dataIndex: 'no_sp',
                sortable: true,
                width: 110
            }, {
                header: "Nama Supplier",
                dataIndex: 'nama_supplier',
                sortable: true,
                width: 200
            }, {
                header: "Tanggal",
                dataIndex: 'tgl_sp',
                sortable: true,
                width: 80
            }, {
                xtype: 'numbercolumn',
                format: '0',
                header: "Masa Berlaku",
                dataIndex: 'masa_berlaku',
                sortable: true,
                align: 'center',
                width: 100
            }, {
                header: "Order By",
                dataIndex: 'order_by',
                sortable: true,
                width: 100
            }, {
                header: "Kirim",
                dataIndex: 'kirim_sp',
                sortable: true,
                width: 150
            }, {
                header: "Alamat Kirim",
                dataIndex: 'alamat_kirim_sp',
                sortable: true,
                width: 150
            }, {
                header: "Remark",
                dataIndex: 'remark',
                sortable: true,
                width: 150
            }],
        plugins: action_konsinyasi_approve_sp, listeners: {
            'rowclick': function() {
                var sm = gridKonsAppSuratPesanan.getSelectionModel();
                var sel = sm.getSelections();
                gridKonsAppDetSuratPesanan.store.proxy.conn.url = '<?= site_url("konsinyasi_approve_surat_pesanan/get_rows_detail") ?>/' + sel[0].get('no_sp');
                gridKonsAppDetSuratPesanan.store.reload();
            }
        },
        tbar: tb_konsinyasi_approve_sp,
         bbar: new Ext.PagingToolbar({
         pageSize: ENDPAGE,
         store: strapprovalsuratpesanan,
         displayInfo: true
         })
    });

    var gridKonsAppDetSuratPesanan = new Ext.grid.EditorGridPanel({
        id: 'gridKonsAppDetSuratPesanan',
        frame: true,
        border: true,
        sm: smgridKonsAppDetSuratPesanan,
        stripeRows: true,
        store: strapprovalsuratpesanandetail,
        loadMask: true,
        style: 'margin:0 auto;',
        height: 220,
       // view: new Ext.ux.grid.LockingGridView(),
       // colModel: new Ext.ux.grid.LockingColumnModel([
        columns :([
            {
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
            },{
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: "Qty",
                dataIndex: 'qty_sp',
                sortable: true,
                width: 70
            },
           
            ])
    });


    var approvalsuratpesanan = new Ext.FormPanel({
        id: 'approvalsuratpesanan',
        border: false,
        frame: true,
        autoScroll: true,
        bodyStyle: 'padding:2px 20px 5px 5px;',
        items: [gridKonsAppSuratPesanan, gridKonsAppDetSuratPesanan]
    });
    // grid



    function edit_konsinyasi_approve_surat_pesanan(kd_app_po, status, statement) {

        var sm = gridKonsAppSuratPesanan.getSelectionModel();
        var sel = sm.getSelections();
        if (sel.length > 0) {
            Ext.Msg.show({
                title: 'Confirm',
                msg: 'Are you sure to ' + statement + ' selected SP ?',
                buttons: Ext.Msg.YESNO,
                fn: function(btn) {
                    if (btn == 'yes') {

                        var data = kd_app_po + '_' + status;

                        Ext.Ajax.request({
                            url: '<?= site_url("konsinyasi_approve_surat_pesanan/update_row") ?>',
                            method: 'POST',
                            params: {
                                postdata: data
                            },
                            callback: function(opt, success, responseObj) {
                                var de = Ext.util.JSON.decode(responseObj.responseText);
                                if (de.success == true) {
                                    strapprovalsuratpesanan.reload();
                                    strapprovalsuratpesanan.load({
                                        params: {
                                            start: STARTPAGE,
                                            limit: ENDPAGE
                                        }
                                    });
                                    strapprovalsuratpesanandetail.reload();
                                    strapprovalsuratpesanandetail.load({
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