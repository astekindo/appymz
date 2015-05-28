<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    /* START GRID */
    var strkonsinyasiapprovalmanager = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_ro',
                'subject',
                'tgl_ro',
                'keterangan2',
                'nama_supplier',
                'app_ass_manager'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({

            url: '<?= site_url("konsinyasi_approval_manager/get_rows") ?>',
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
    var search_konsinyasi_approval_manager = new Ext.app.SearchField({
        store: strkonsinyasiapprovalmanager,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearch_konsinyasi_approval_manager'
    });

    // top toolbar
    var tb_konsinyasi_approval_manager = new Ext.Toolbar({
        items: [search_konsinyasi_approval_manager, '->', '<i>Klik row untuk melihat detail RO</i>']
    });

    // checkbox grid
    var smgridKROManager = new Ext.grid.CheckboxSelectionModel();
    var smgridDetKRoManager = new Ext.grid.CheckboxSelectionModel();

    // data store
    var strkonsinyasiapprovalmanagerdetail = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_ro',
                'kd_produk',
                'nama_produk',
                'qty',
                'qty_adj',
                'keterangan2',
                'nm_satuan',
                'keterangan1',
                'approval1',
                'min_stok',
                'max_stok',
                'jml_stok',
                'is_kelipatan_order',
                'min_order'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("konsinyasi_approval_manager/get_rows_detail") ?>',
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

    strkonsinyasiapprovalmanager.on('load', function(){
        strkonsinyasiapprovalmanagerdetail.removeAll();
    })

    var action_approval_detail_konsinyasi_approve_manager = new Ext.ux.grid.RowActions({
        header :'Approve',
        autoWidth: false,
            // locked: true,
        width: 60,
        actions:[
          {iconCls: 'icon-approve-record', qtip: 'Approve'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });

    var action_approval_detail_konsinyasi_reject_manager = new Ext.ux.grid.RowActions({
        header :'Reject',
        autoWidth: false,
            // locked: true,
        width: 50,
        actions:[
          {iconCls: 'icon-delete-record', qtip: 'Reject'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });

    action_approval_detail_konsinyasi_approve_manager.on('action', function(grid, record, action, row, col) {
        var no_ro_det = record.get('no_ro');
        var kd_prod_det = record.get('kd_produk');
        switch(action){
            case 'icon-approve-record':
                edit_konsinyasi_approval_manager_detail(no_ro_det,kd_prod_det,1);
            break;
        }
    });

    action_approval_detail_konsinyasi_reject_manager.on('action', function(grid, record, action, row, col) {
        var no_ro_det = record.get('no_ro');
        var kd_prod_det = record.get('kd_produk');
        switch(action){
            case 'icon-delete-record' :
                edit_konsinyasi_approval_manager_detail(no_ro_det,kd_prod_det,9);
            break;
        }
    });

    var editorkonsinyasiapprovalrequestmanager = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });

    var gridKROManager = new Ext.grid.EditorGridPanel({
        id: 'gridKROManager',
        frame: true,
        border: true,
        stripeRows: true,
        sm: smgridKROManager,
        store: strkonsinyasiapprovalmanager,
        loadMask: true,
        title: 'RO',
        style: 'margin:0 auto;',
        height: 225,
        // width: 550,
        columns: [{
            header: "No RO",
            dataIndex: 'no_ro',
            sortable: true,
            width: 150
        },{
            header: "Subject",
            dataIndex: 'subject',
            sortable: true,
            width: 250
        },{
            header: "Tanggal RO",
            dataIndex: 'tgl_ro',
            sortable: true,
            width: 80
        },{
            header: "Nama Supplier",
            dataIndex: 'nama_supplier',
            sortable: true,
            width: 300
        },{
            header: "Approval Ass. Manager",
            dataIndex: 'app_ass_manager',
            sortable: true,
            width: 150
        }],
        listeners: {
            'rowclick': function(){
                var sm = gridKROManager.getSelectionModel();
                var sel = sm.getSelections();
                gridDetKROManager.store.proxy.conn.url = '<?= site_url("konsinyasi_approval_manager/get_rows_detail") ?>/' + sel[0].get('no_ro');
                gridDetKROManager.store.reload();
            }
        },
        tbar: tb_konsinyasi_approval_manager,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strkonsinyasiapprovalmanager,
            displayInfo: true
        })
    });

    // shorthand alias
    var fm = Ext.form;

    var cmm = new Ext.ux.grid.LockingColumnModel({
        // specify any defaults for each column
        defaults: {
            sortable: true // columns are not sortable by default
        },
        columns: [ {
            dataIndex: 'no_ro',
            hidden: true
        },{
            dataIndex: 'kd_produk',
            hidden: true
        },action_approval_detail_konsinyasi_approve_manager,action_approval_detail_konsinyasi_reject_manager,{
            header: "Nama Barang",
            dataIndex: 'nama_produk',
            sortable: true,
            width: 250
        },{
            header: "Qty",
            dataIndex: 'qty',
            sortable: true,
            width: 50
        },{
            header: "Qty Adjust",
            dataIndex: 'qty_adj',
            sortable: true,
            width: 70,
            editor: new fm.NumberField({
                    allowBlank: false,
                    allowNegative: false,
                    maxValue: 100000
                })
        },{
            header: "Satuan",
            dataIndex: 'nm_satuan',
            sortable: true,
            width: 50
        },{
            header: 'Min.Stok',
            dataIndex: 'min_stok',
            width: 70,
            sortable: true,
        },{
            header: 'Max.Stok',
            dataIndex: 'max_stok',
            width: 70,
            sortable: true,
        },{
            header: 'Min. Order',
            dataIndex: 'min_order',
            width: 70,
            sortable: true,
        },{
            header: 'Kelipatan Order',
            dataIndex: 'is_kelipatan_order',
            width: 70,
            sortable: true,
        },{
            header: 'Jml.Stok Pot. SO',
            dataIndex: 'jml_stok',
            width: 100,
            sortable: true,
        },{
            header: "Ass.Manager",
            dataIndex: 'approval1',
            sortable: true,
            width: 100
        },{
            header: "Alasan Ass. Manager",
            dataIndex: 'keterangan1',
            sortable: true,
            width: 150
        },{
            header: "Alasan Manager",
            dataIndex: 'keterangan2',
            sortable: true,
            width: 200,
            editor: new fm.TextField({
                    allowBlank: false,
                })
        }],

    });

    var gridDetKROManager = new Ext.grid.EditorGridPanel({
        id: 'gridDetKROManager',
        store: strkonsinyasiapprovalmanagerdetail,
        stripeRows: true,
        style: 'margin-bottom:5px;',
        height: 225,
        frame: true,
        border:true,
        loadMask: true,
        sm: smgridDetKRoManager,
        plugins: [action_approval_detail_konsinyasi_approve_manager,action_approval_detail_konsinyasi_reject_manager],
        view: new Ext.ux.grid.LockingGridView(),
        cm: cmm,
        tbar:[{
                iconCls: 'icon-approve-record',
                text: 'Approve All',
                handler: function(){
                    edit_konsinyasi_approval_manager(2);
                }
            },{
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Reject All',
                handler: function(){
                    edit_konsinyasi_approval_manager(9);
                }
            }]
    });


    var konsinyasiapprovalmanager = new Ext.FormPanel({
            id: 'konsinyasiapprovalmanager',
            border: false,
            frame: true,
            autoScroll:true,
            bodyStyle:'padding:5px;',
            items: [gridKROManager,gridDetKROManager]
    });


    function edit_konsinyasi_approval_manager(stat){

           var messages = 'Reject';
        if(stat == 2){
            messages = 'Approve';
        }
        var sm = gridKROManager.getSelectionModel();
        var sel = sm.getSelections();
        if (sel.length > 0) {

            Ext.Msg.show({
                title: 'Confirm',
                msg: 'Apakah anda akan ' + messages + ' semua barang ?',
                buttons: Ext.Msg.YESNO,
            fn: function(btn){

                if (btn == 'yes') {
                    var data =  sel[0].get('no_ro') + '_' + sel[0].get('keterangan2') + '_' + stat ;

                        var detailrequestapproval = new Array();
                    strkonsinyasiapprovalmanagerdetail.commitChanges();
                    strkonsinyasiapprovalmanagerdetail.each(function(node){
                        detailrequestapproval.push(node.data)
                    });

                    Ext.Ajax.request({
                            url: '<?= site_url("konsinyasi_approval_manager/update_row") ?>',
                            method: 'POST',
                            params: {
                                postdata: data,
                        detail: Ext.util.JSON.encode(detailrequestapproval)
                            },
                        callback:function(opt,success,responseObj){
                        var de = Ext.util.JSON.decode(responseObj.responseText);
                        if(de.success==true){
                            strkonsinyasiapprovalmanager.reload();
                                    strkonsinyasiapprovalmanager.load({
                                        params: {
                                            start: STARTPAGE,
                                            limit: ENDPAGE
                                        }
                                    });
                            strkonsinyasiapprovalmanagerdetail.reload();
                                    strkonsinyasiapprovalmanagerdetail.load({
                                        params: {
                                            start: STARTPAGE,
                                            limit: ENDPAGE
                                        }
                                    });
                            Ext.getCmp('papp_keterangan2').setValue('');
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
            }

        });

        }else {
            Ext.Msg.show({
                title: 'Info',
                msg: 'Please selected row',
                modal: true,
                icon: Ext.Msg.ERROR,
                buttons: Ext.Msg.OK
        });
        }

    }

    function edit_konsinyasi_approval_manager_detail(no_ro_det, kd_prod_det, stat){
        var messages = 'Reject';
        if(stat == 2){
            messages = 'Approve';
        }
        var sm = gridDetKROManager.getSelectionModel();
        var sel = sm.getSelections();
        if (sel.length > 0) {
            Ext.Msg.show({
                title: 'Confirm',
                msg: 'Apakah anda akan ' + messages + ' barang ini ?',
                buttons: Ext.Msg.YESNO,
                fn: function(btn){
                    if (btn == 'yes') {

                        var data =  no_ro_det + '_' + kd_prod_det + '_' + sel[0].get('qty') +  '_' + sel[0].get('qty_adj') + '_' + sel[0].get('keterangan2') + '_' + stat ;


            if( ( sel[0].get('qty') != sel[0].get('qty_adj') ) && sel[0].get('keterangan2') === ''){
                Ext.Msg.show({
                            title: 'Error',
                            msg: 'Silahkan isi Alasan terlebih dahulu !!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                        });
            }else{

                        Ext.Ajax.request({
                            url: '<?= site_url("konsinyasi_approval_manager/update_row_detail") ?>',
                            method: 'POST',
                            params: {
                                postdata: data
                            },
                            callback:function(opt,success,responseObj){
                                var de = Ext.util.JSON.decode(responseObj.responseText);
                                if(de.success==true){
                                    strkonsinyasiapprovalmanager.reload();
                                    strkonsinyasiapprovalmanager.load({
                                        params: {
                                            start: STARTPAGE,
                                            limit: ENDPAGE
                                        }
                                    });
                                    strkonsinyasiapprovalmanagerdetail.reload();
                                    strkonsinyasiapprovalmanagerdetail.load({
                                        params: {
                                            start: STARTPAGE,
                                            limit: ENDPAGE
                                        }
                                    });
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
