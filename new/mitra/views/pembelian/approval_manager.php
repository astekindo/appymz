<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">    
    /* START GRID */    
    var strpembelianapprovalmanager = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_ro',
                'subject',
				'tgl_ro',
				'keterangan2',
				'nama_supplier',
				'app_ass_manager',
                                'waktu_top'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({			
               
            url: '<?= site_url("approval_manager/get_rows") ?>',
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
    var search_approval_manager = new Ext.app.SearchField({
        store: strpembelianapprovalmanager,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
		emptyText: 'No PR, Nama Supplier',
        id: 'idsearch_approval_manager'
    });
    
    // top toolbar
    var tb_approval_manager = new Ext.Toolbar({
        items: [search_approval_manager, '->', '<i>Klik row untuk melihat detail PR</i>']
    });
    
    // checkbox grid
    var smgridROManager = new Ext.grid.CheckboxSelectionModel();
    var smgridDetRoManager = new Ext.grid.CheckboxSelectionModel();
    
    // data store
    var strpembelianapprovalmanagerdetail = new Ext.data.Store({
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
            url: '<?= site_url("approval_manager/get_rows_detail") ?>',
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
	
	strpembelianapprovalmanager.on('load', function(){
		strpembelianapprovalmanagerdetail.removeAll();
	})
	
	var action_approval_detail_approve_manager_pr = new Ext.ux.grid.RowActions({
		header :'Approve',
		autoWidth: false,
			// locked: true,
		width: 60,
        actions:[
          {iconCls: 'icon-approve-record', qtip: 'Approve'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    }); 
    var action_approval_detail_approve_manager= new Ext.ux.grid.RowActions({
		header :'Approve',
		autoWidth: false,
			// locked: true,
		width: 60,
        actions:[
          {iconCls: 'icon-approve-record', qtip: 'Approve'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    }); 
	
	var action_approval_detail_reject_manager = new Ext.ux.grid.RowActions({
		header :'Reject',
		autoWidth: false,
			// locked: true,
		width: 50,
        actions:[
          {iconCls: 'icon-delete-record', qtip: 'Reject'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    }); 
    
    action_approval_detail_approve_manager_pr.on('action', function(grid, record, action, row, col) {
        var no_ro_det = record.get('no_ro');
        var kd_prod_det = record.get('kd_produk');
		switch(action){
			case 'icon-approve-record':
				edit_approval_manager_detail(no_ro_det,kd_prod_det,2);
			break;
		}		
    });
	
    action_approval_detail_reject_manager.on('action', function(grid, record, action, row, col) {
        var no_ro_det = record.get('no_ro');
        var kd_prod_det = record.get('kd_produk');
		switch(action){
			case 'icon-delete-record' :
				edit_approval_manager_detail(no_ro_det,kd_prod_det,9);
			break;
		}		
    });  
	
    var editorpembelianapprovalrequestmanager = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });
	
	var gridROManager = new Ext.grid.EditorGridPanel({
        id: 'gridROManager',
        frame: true,
        border: true,
        stripeRows: true,
        sm: smgridROManager,
        store: strpembelianapprovalmanager,
        loadMask: true,
        title: 'PR',
        style: 'margin:0 auto;',
        height: 225,
        // width: 550,
        columns: [{
            header: "No PR",
            dataIndex: 'no_ro',
            sortable: true,
            width: 150
        },{
            header: "Subject",
            dataIndex: 'subject',
            sortable: true,
            width: 250
        },{
            header: "Tanggal PR",
            dataIndex: 'tgl_ro',
            sortable: true,
            width: 80
        },{
            header: "Nama Supplier",
            dataIndex: 'nama_supplier',
            sortable: true,
            width: 250
        },{
            header: "TOP",
            dataIndex: 'waktu_top',
            sortable: true,
            width: 50
        },{
            header: "Approval Ass. Manager",
            dataIndex: 'app_ass_manager',
            sortable: true,
            width: 150
        }],
		listeners: {
            'rowclick': function(){              
                var sm = gridROManager.getSelectionModel();                
                var sel = sm.getSelections(); 				
                gridDetROManager.store.proxy.conn.url = '<?= site_url("approval_manager/get_rows_detail") ?>/' + sel[0].get('no_ro');
                gridDetROManager.store.reload();
            }          
        },
        tbar: tb_approval_manager,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strpembelianapprovalmanager,
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
        },action_approval_detail_approve_manager_pr,action_approval_detail_reject_manager,{
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
	
	var gridDetROManager = new Ext.grid.EditorGridPanel({
		id: 'gridDetROManager',
        store: strpembelianapprovalmanagerdetail,
        stripeRows: true,
        style: 'margin-bottom:5px;',
        height: 225,
        frame: true,
        border:true,
        loadMask: true,
		sm: smgridDetRoManager,
        plugins: [action_approval_detail_approve_manager_pr,action_approval_detail_reject_manager],
		view: new Ext.ux.grid.LockingGridView(),
        cm: cmm,
		tbar:[{
				iconCls: 'icon-approve-record',
				text: 'Approve All',
				handler: function(){
					edit_approval_manager(2);
				}
			},{
				ref: '../removeBtn',
				icon: BASE_ICONS + 'delete.gif',
				text: 'Reject All',
				handler: function(){
					edit_approval_manager(9);
				}
			}]
    });	
    
	
	var pembelianapprovalmanager = new Ext.FormPanel({
			id: 'pembelianapprovalmanager',			
			border: false,
			frame: true,
			autoScroll:true,		
			bodyStyle:'padding:5px;',
			items: [gridROManager,gridDetROManager]
	});  
    
    
    function edit_approval_manager(stat){
       	var messages = 'Reject';
		if(stat == 2){
			messages = 'Approve';
		}

        var sm = gridROManager.getSelectionModel();
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
					strpembelianapprovalmanagerdetail.commitChanges();
					strpembelianapprovalmanagerdetail.each(function(node){						
						detailrequestapproval.push(node.data)
					});

					Ext.Ajax.request({
				            url: '<?= site_url("approval_manager/update_row") ?>',
				            method: 'POST',
				            params: {
				                postdata: data,
						detail: Ext.util.JSON.encode(detailrequestapproval)
				            },
					    callback:function(opt,success,responseObj){
						var de = Ext.util.JSON.decode(responseObj.responseText);
						if(de.success==true){
							strpembelianapprovalmanager.reload();
					                strpembelianapprovalmanager.load({
					                    params: {
					                        start: STARTPAGE,
					                        limit: ENDPAGE
					                    }
				                	});
							strpembelianapprovalmanagerdetail.reload();
					                strpembelianapprovalmanagerdetail.load({
					                    params: {
					                        start: STARTPAGE,
					                        limit: ENDPAGE
					                    }
				                	});
							//Ext.getCmp('papp_keterangan2').setValue('');
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
    
    function edit_approval_manager_detail(no_ro_det, kd_prod_det, stat){
		var messages = 'Reject';
		if(stat == 2){
			messages = 'Approve';
		}

        var sm = gridDetROManager.getSelectionModel();
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
                            url: '<?= site_url("approval_manager/update_row_detail") ?>',
                            method: 'POST',
                            params: {
                                postdata: data
                            },
							callback:function(opt,success,responseObj){
								var de = Ext.util.JSON.decode(responseObj.responseText);
								if(de.success==true){
									strpembelianapprovalmanager.reload();
	                                strpembelianapprovalmanager.load({
	                                    params: {
	                                        start: STARTPAGE,
	                                        limit: ENDPAGE
	                                    }
                                	});
									strpembelianapprovalmanagerdetail.reload();
	                                strpembelianapprovalmanagerdetail.load({
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
