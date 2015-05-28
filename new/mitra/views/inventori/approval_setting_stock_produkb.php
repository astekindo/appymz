<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">  
	
    // checkbox grid
    var smgridROManager = new Ext.grid.CheckboxSelectionModel();
    var smgridDetRoManager = new Ext.grid.CheckboxSelectionModel();
    
    // data store
    var strasspb = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
				'kd_produk',
                'nama_produk',
				'approval_ops', 
				'approval_buyer',  
				'stok_min', 
				'stok_max', 
				'max_order', 
				'pct_alert',
				'limit_stok',
				'qty_oh',
				'qty_oh_so',
				'is_kelipatan_order'
			],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("approval_setting_stockb/get_rows_detail") ?>',
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
	
	var action_approval_settingstockpb = new Ext.ux.grid.RowActions({
		header :'Approve',
		autoWidth: false,
			// locked: true,
		width: 60,
        actions:[
          {iconCls: 'icon-approve-record', qtip: 'Approve'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    }); 
	
	var action_reject_settingstockpb = new Ext.ux.grid.RowActions({
		header :'Reject',
		autoWidth: false,
			// locked: true,
		width: 50,
        actions:[
          {iconCls: 'icon-delete-record', qtip: 'Reject'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    }); 
    
    action_approval_settingstockpb.on('action', function(grid, record, action, row, col) {
		var kd_prod_det = record.get('kd_produk');
		switch(action){
			case 'icon-approve-record':
				edit_approval_setting_stok_pb(kd_prod_det,1);
			break;
		}		
    });
	
    action_reject_settingstockpb.on('action', function(grid, record, action, row, col) {
		var kd_prod_det = record.get('kd_produk');
		switch(action){
			case 'icon-delete-record' :
				edit_approval_setting_stok_pb(kd_prod_det,9);
			break;
		}		
    });  
	
    var editorpembelianapprovalrequestmanager = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });

	var cmm = new Ext.ux.grid.LockingColumnModel({
        // specify any defaults for each column
        defaults: {
            sortable: true // columns are not sortable by default           
        },
        columns: [ {
            dataIndex: 'approval_ops',
            hidden: true
        },{
            dataIndex: 'kd_produk',
            hidden: true
        },action_approval_settingstockpb,action_reject_settingstockpb,{
            header: "Kode Barang",
            dataIndex: 'kd_produk',
            sortable: true,
            width: 150
        },{
            header: "Nama Barang",
            dataIndex: 'nama_produk',
            sortable: true,
            width: 300
        },{
            header: "Stok OH",
            dataIndex: 'qty_oh',
            sortable: true,
            align: 'right',
            width: 70
        },{
            header: "Stok Potong SO",
            dataIndex: 'qty_oh_so',
            sortable: true,
            align: 'right',
            width: 100
        },{
            header: "Stok Min",
            dataIndex: 'stok_min',
            align: 'right',
            sortable: true,
            width: 70
        },{
            header: "Stok Max",
            dataIndex: 'stok_max',
            align: 'right',
            sortable: true,
            width: 70
        },{
            header: "Min Order",
            dataIndex: 'max_order',
            align: 'right',
            sortable: true,
            width: 70
        },{
            header: "Kelipatan Order",
            dataIndex: 'is_kelipatan_order',
            align: 'right',
            sortable: true,
            width: 100
        },{
            header: "Alert (%)",
            dataIndex: 'pct_alert',
            align: 'right',
            sortable: true,
            width: 70
        },{
            header: "Nilai Alert",
            dataIndex: 'limit_stok',
            align: 'right',
            sortable: true,
            width: 70
        },{
            header: "Keterangan",
            dataIndex: 'keterangan',
            sortable: true,
            width: 250
        }],
        
    });	
	
	var gridASSPB = new Ext.grid.EditorGridPanel({
		id: 'gridASSPB',
        store: strasspb,
        stripeRows: true,
        style: 'margin-bottom:5px;',
        height: 450,
        frame: true,
        border:true,
        loadMask: true,
		sm: smgridDetRoManager,
		view: new Ext.ux.grid.LockingGridView(),
        plugins: [action_approval_settingstockpb,action_reject_settingstockpb],
        cm: cmm
    });	
    
	
	var approvalsspb = new Ext.FormPanel({
			id: 'approvalsspb',
			border: false,
			frame: true,
			autoScroll:true,		
			bodyStyle:'padding:5px;',
			items: [gridASSPB]
	});    
	
	function edit_approval_setting_stok_pb(kd_prod_det,stat){
        var sm = gridASSPB.getSelectionModel();
        var sel = sm.getSelections();
        if (sel.length > 0) {
            Ext.Msg.show({
                title: 'Confirm',
                msg: 'Are you sure update selected row ?',
                buttons: Ext.Msg.YESNO,
                fn: function(btn){
                    if (btn == 'yes') {
						
                        var data =  kd_prod_det + '_' + stat + '_' + sel[0].get('stok_min') + '_' + sel[0].get('stok_max') + '_' + sel[0].get('max_order') + '_' + sel[0].get('pct_alert');                        
                        Ext.Ajax.request({
                            url: '<?= site_url("approval_setting_stockb/update_row_detail") ?>',
                            method: 'POST',
                            params: {
                                postdata : data
                            },
							callback:function(opt,success,responseObj){
								var de = Ext.util.JSON.decode(responseObj.responseText);
								if(de.success==true){
									strasspb.reload();
	                                strasspb.load({
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
