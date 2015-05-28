<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">    
        var strstokbrgbonus = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'lokasi',
                'kd_produk',
                'nama_produk',
                {name: 'qty_oh',type: 'int'},
                'nm_satuan'
                
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("stok_barang_bonus/get_rows") ?>',
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
    var searchstokbrgbonus = new Ext.app.SearchField({
        store: strstokbrgbonus,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchstokbrgbonus'
    });
	
    var tbstokbrgbonus = new Ext.Toolbar({
        items: [searchstokbrgbonus]
    });

    var cbGrid = new Ext.grid.CheckboxSelectionModel();
        
    // row actions
    var actionstokbrgbonus = new Ext.ux.grid.RowActions({
        actions:[
          {iconCls: 'icon-edit-record', qtip: 'Edit'},
          {iconCls: 'icon-delete-record', qtip: 'Delete'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    }); 
    actionstokbrgbonus.on('action', function(grid, record, action, row, col) {
        var kd_supplier = record.get('kd_supplier');
        switch(action) {
            case 'icon-edit-record':                
                editstokbrgbonus(kd_supplier);
                break;
            case 'icon-delete-record':
                Ext.Msg.show({
                    title: 'Confirm',
                    msg: 'Are you sure delete selected row ?',
                    buttons: Ext.Msg.YESNO,
                    fn: function(btn){
                        if (btn == 'yes') {
                            Ext.Ajax.request({
                                url: '<?= site_url("stok_barang_bonus/delete_row") ?>',
                                method: 'POST',
                                params: {
                                    kd_supplier: kd_supplier
                                },
                                callback:function(opt,success,responseObj){
                                    var de = Ext.util.JSON.decode(responseObj.responseText);
                                    if(de.success==true){
                                        strstokbrgbonus.reload();
                                        strstokbrgbonus.load({
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
                });
                break;        
            
        }
    });  
    function pctChange(val){
        if(val > 0){
            return '<span style="color:green;">' + val + '%</span>';
        }else if(val < 0){
            return '<span style="color:red;">' + val + '%</span>';
        }
        return val;
    }
    var stokbrgbonus = new Ext.grid.EditorGridPanel({
        id: 'id-stokbrgbonus-gridpanel',
        frame: true,
        border: true,
        stripeRows: true,
        sm: cbGrid,
        store: strstokbrgbonus,
        loadMask: true,
        style: 'margin:0 auto;',
        height: 450,
        columns: [
//        cbGrid, 
        {
            header: "Kode Produk",
            dataIndex: 'kd_produk',
            sortable: true,
            width: 100
        },{
            header: "Nama Produk",
            dataIndex: 'nama_produk',
            sortable: true,
            width: 250
        },{
            header: "Lokasi",
            dataIndex: 'lokasi',
            sortable: true,
            width: 300
        },{
            header: "Stok",
            dataIndex: 'qty_oh',
            sortable: true,
            width: 100
            ,renderer: function(value, metaData, record, rowIndex, colIndex, store) {
                return Ext.util.Format.number(value, '0,000');
            },
            align:'right'
        },{
            header: "Satuan",
            dataIndex: 'nm_satuan',
            sortable: true,
            width: 150
        }
        ],
        tbar: tbstokbrgbonus,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strstokbrgbonus,
            displayInfo: true
        })
    });
    
	var stokbrgbonuspanel = new Ext.FormPanel({
	 	id: 'stokbrgbonus',
		border: false,
        frame: false,
		autoScroll:true,	
        items: [stokbrgbonus]
	});
	
    function editstokbrgbonus(kd_supplier){
        Ext.getCmp('btnresetstokbrgbonus').hide();
        Ext.getCmp('btnsubmitstokbrgbonus').setText('Update');
        winaddstokbrgbonus.setTitle('Edit Form');
        Ext.getCmp('id_formaddstokbrgbonus').getForm().load({
            url: '<?= site_url("stok_barang_bonus/get_row") ?>',
            params: {
                id: kd_supplier,
                cmd: 'get'
            },                  
            failure: function(form, action){
                var de = Ext.util.JSON.decode(action.response.responseText);
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
        });
        winaddstokbrgbonus.show();
    }
    
    function deletestokbrgbonus(){      
        var sm = stokbrgbonus.getSelectionModel();
        var sel = sm.getSelections();
        if (sel.length > 0) {
            Ext.Msg.show({
                title: 'Confirm',
                msg: 'Are you sure delete selected row ?',
                buttons: Ext.Msg.YESNO,
                fn: function(btn){
                    if (btn == 'yes') {
                    
                        var data = '';
                        for (i = 0; i < sel.length; i++) {
                            data = data + sel[i].get('kd_supplier') + ';';
                        }
                        
                        Ext.Ajax.request({
                            url: '<?= site_url("stok_barang_bonus/delete_rows") ?>',
                            method: 'POST',
                            params: {
                                postdata: data
                            },
                            callback:function(opt,success,responseObj){
                                var de = Ext.util.JSON.decode(responseObj.responseText);
                                if(de.success==true){
                                    strstokbrgbonus.reload();
                                    strstokbrgbonus.load({
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
