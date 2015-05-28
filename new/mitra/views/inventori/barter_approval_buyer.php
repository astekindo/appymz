<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
    /* START GRID */
    var strBarterApproval2Summary = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_transfer_stok',
                'tanggal',
                'keterangan',
                'jenis_transfer',
                'created_by',
                'created_date',
                'no_po',
                'kd_supplier',
                'nama_supplier',
                'status'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({

            url: '<?= site_url("barterbarang/get_rows") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    // search field
    var searchBarterApproval2Summary = new Ext.app.SearchField({
        store: strBarterApproval2Summary,
        params: { start: STARTPAGE, limit: ENDPAGE },
        width: 220,
        id: 'id_search_barter_app2sum'
    });

    // top toolbar
    var toolbarBarterApproval2Summary = new Ext.Toolbar({
        items: [searchBarterApproval2Summary, '->', '<i>Klik row untuk melihat detail barter</i>']
    });

    // checkbox grid
    var smGridSummary = new Ext.grid.CheckboxSelectionModel();

    var barterApproval2SummaryGrid = new Ext.grid.EditorGridPanel({
        id: 'barterApproval2SummaryGrid',
        frame: true,
        border: true,
        stripeRows: true,
        sm: smGridSummary,
        store: strBarterApproval2Summary,
        loadMask: true,
        title: 'Barter Barang',
        style: 'margin:0 auto;',
        height: 225,
        columns: [{
            header: "No. Bukti",
            dataIndex: 'no_transfer_stok',
            sortable: true,
            width: 100
        },{
            header: "Tanggal Transfer",
            dataIndex: 'tanggal',
            sortable: true,
            width: 100
        },{
            header: "No. PO",
            dataIndex: 'no_po',
            sortable: true,
            width: 100
        },{
            header: "Nama Supplier",
            dataIndex: 'nama_supplier',
            sortable: true,
            width: 200
        },{
            header: "Dibuat Oleh",
            dataIndex: 'created_by',
            sortable: true,
            width: 100
        },{
            header: "Status",
            dataIndex: 'status',
            sortable: true,
            width: 80
        },{
            header: "Keterangan",
            dataIndex: 'keterangan',
            sortable: true,
            width: 250
        }],
        listeners: {
            'rowclick': function(){
                var sm = barterApproval2SummaryGrid.getSelectionModel();
                var sel = sm.getSelections();
                // barterApproval2DetailGrid.store.proxy.conn.url = '<?= site_url("barterbarang/get_rows_detail") ?>';
                barterApproval2DetailGrid.store.reload({
                    params: { no_transfer_stok: sel[0].get('no_transfer_stok') }
                })
            }
        },
        tbar: toolbarBarterApproval2Summary,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strBarterApproval2Summary,
            displayInfo: true
        })
    });

    // shorthand alias
    var fm = Ext.form;

    // data store
    var strBarterApproval2Detail = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_transfer_stok',
                'kd_produk_awal',
                'nama_produk_awal',
                'nm_satuan_awal',
                'kd_lokasi_awal',
                'kd_blok_awal',
                'kd_sub_blok_awal',
                'sub_awal',
                'nama_sub_awal',
                'alias_sub_awal',
                'kd_produk_tujuan',
                'nama_produk_tujuan',
                'kd_lokasi_tujuan',
                'kd_blok_tujuan',
                'kd_sub_blok_tujuan',
                'sub_tujuan',
                'nama_sub_tujuan',
                'alias_sub_tujuan',
                'qty'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("barterbarang/get_rows_detail") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var smGridDetail  = new Ext.grid.CheckboxSelectionModel();

    strBarterApproval2Summary.on('load', function(){
        strBarterApproval2Summary.setBaseParam('stat', 1);
        strBarterApproval2Detail.removeAll();
    })

    var cmm = new Ext.ux.grid.LockingColumnModel({
        defaults: {
            sortable: true
        },
        columns: [
        {
            dataIndex: 'no_transfer_stok',
            hidden: true
        },{
            header: "Kd. Barang Awal",
            dataIndex: 'kd_produk_awal',
            width: 100
        },{
            header: "Nama Barang Awal",
            dataIndex: 'nama_produk_awal',
            width: 250
        },{
            header: "Qty",
            dataIndex: 'qty',
            width: 50
        },{
            header: "Satuan",
            dataIndex: 'nm_satuan',
            width: 50
        },{
            header: 'Lokasi Ambil',
            dataIndex: 'alias_sub_awal',
            width: 200,
        },{
            header: "Kd. Barang Tujuan",
            dataIndex: 'kd_produk_tujuan',
            width: 100
        },{
            header: "Nama Barang Tujuan",
            dataIndex: 'nama_produk_tujuan',
            width: 250
        },{
            header: 'Lokasi Pengembalian',
            dataIndex: 'alias_sub_tujuan',
            width: 200,
        }],

    });

    var barterApproval2DetailGrid = new Ext.grid.EditorGridPanel({
        id: 'barterApproval2DetailGrid',
        store: strBarterApproval2Detail,
        stripeRows: true,
        style: 'margin-bottom:5px;',
        height: 225,
        frame: true,
        border:true,
        loadMask: true,
        sm: smGridDetail,
        view: new Ext.ux.grid.LockingGridView(),
        cm: cmm,
        tbar:[{
                iconCls: 'icon-approve-record',
                text: 'Approve All',
                handler: function(){
                    barterChangeStatusBuyer(2);
                }
            },{
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Reject All',
                handler: function(){
                    barterChangeStatusBuyer(9);
                }
            }]
    });


    var barterApprovalOps = new Ext.FormPanel({
            id: 'barter_approval_buyer',
            border: false,
            frame: true,
            autoScroll:true,
            bodyStyle:'padding:5px;',
            items: [barterApproval2SummaryGrid,barterApproval2DetailGrid]
    });


    function barterChangeStatusBuyer(stat){

        var messages = 'Reject';
        if(stat == 1 || stat == 2 ){
            messages = 'Approve';
        }
        var sm = barterApproval2SummaryGrid.getSelectionModel();
        var sel = sm.getSelections();
        if (sel.length > 0) {

            Ext.Msg.show({
                title: 'Confirm',
                msg: 'Apakah anda akan ' + messages + ' semua barang ?',
                buttons: Ext.Msg.YESNO,
                fn: function(btn) {

                    if (btn != 'yes') {
                        return;
                    }

                    var no_bukti = sel[0].get('no_transfer_stok');

                    var detail = new Array();
                    strBarterApproval2Detail.commitChanges();
                    strBarterApproval2Detail.each(function(node){
                        detail.push(node.data)
                    });

                    Ext.Ajax.request({
                            url: '<?= site_url("barterbarang/approval_buyer") ?>',
                            method: 'POST',
                            params: {
                                no_bukti:  sel[0].get('no_transfer_stok')
                            },
                        callback:function(opt,success,responseObj){
                            var de = Ext.util.JSON.decode(responseObj.responseText);
                            if(de.success==true){
                                Ext.Msg.show({
                                    title: 'Success',
                                    msg: de.successMsg,
                                    modal: true,
                                    icon: Ext.Msg.INFO,
                                    buttons: Ext.Msg.OK
                                });
                                strBarterApproval2Summary.reload({ params: { start: STARTPAGE, limit: ENDPAGE } });
                                // strBarterApproval2Summary.load({ params: { start: STARTPAGE, limit: ENDPAGE } });
                                strBarterApproval2Detail.reload({ params: { start: STARTPAGE, limit: ENDPAGE } });
                                // strBarterApproval2Detail.load({ params: { start: STARTPAGE, limit: ENDPAGE } });
                            }else{
                                Ext.Msg.show({
                                    title: 'Error',
                                    msg: de.errMsg,
                                    modal: true,
                                    icon: Ext.Msg.ERROR,
                                    buttons: Ext.Msg.OK,
                                    fn: function(btn){
                                        if (btn == 'ok' && de.errMsg == 'Session Expired') {
                                            window.location = '<?= site_url("auth/login") ?>';
                                        }
                                    }
                                });
                            }
                        }
                    });
                }
            });
        } else {
            Ext.Msg.show({
                title: 'Info',
                msg: 'Pilih salah satu data',
                modal: true,
                icon: Ext.Msg.ERROR,
                buttons: Ext.Msg.OK
            });
        }
    }
    strBarterApproval2Summary.load({params: {stat: 1}});
</script>
