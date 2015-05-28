<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">    
    /* START GRID */    
    var strpembelianapproval = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_ro',
                'subject',
                'tgl_ro',
                'keterangan1',
                'nama_supplier',
                'waktu_top'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({			
               
            url: '<?= site_url("approval/get_rows") ?>',
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
    var search_approval = new Ext.app.SearchField({
        store: strpembelianapproval,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
		emptyText: 'No PR, Nama Supplier',
        id: 'idsearch_approval'
    });
    
    // top toolbar
    var tb_approval = new Ext.Toolbar({
        items: [search_approval, '->', '<i>Klik row untuk melihat detail PR</i>']
    });
    
    // checkbox grid
    var smgridRO = new Ext.grid.CheckboxSelectionModel();
    var smgridDetRo = new Ext.grid.CheckboxSelectionModel();
    
    // data store
    var strpembelianapprovaldetail = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
				'no_ro',
		        'kd_produk',
		        'nama_produk',
				'qty',
				'qty_adj',
				'keterangan',
				'nm_satuan',
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
            url: '<?= site_url("approval/get_rows_detail") ?>',
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
	
	strpembelianapproval.on('load', function(){
		strpembelianapprovaldetail.removeAll();
	})
	
	var action_approval_detail_approve = new Ext.ux.grid.RowActions({
		header :'Approve',
		autoWidth: false,
			// locked: true,
		width: 60,
        actions:[{iconCls: 'icon-approve-record', qtip: 'Approve'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    }); 
	
	var action_approval_detail_reject = new Ext.ux.grid.RowActions({
		header :'Reject',
		autoWidth: false,
			// locked: true,
		width: 50,
        actions:[{iconCls: 'icon-delete-record', qtip: 'Reject'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    }); 
    
    action_approval_detail_approve.on('action', function(grid, record, action, row, col) {
        var no_ro_det = record.get('no_ro');
        var kd_prod_det = record.get('kd_produk');
		switch(action){
			case 'icon-approve-record':
				edit_approval_detail(no_ro_det,kd_prod_det,1);
			break;
		}		
    });
	
    action_approval_detail_reject.on('action', function(grid, record, action, row, col) {
        var no_ro_det = record.get('no_ro');
        var kd_prod_det = record.get('kd_produk');
		switch(action){
			case 'icon-delete-record' :
				edit_approval_detail(no_ro_det,kd_prod_det,9);
			break;
		}		
    });  
	
    var editorpembelianapprovalrequest = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });
	
	var gridRO = new Ext.grid.EditorGridPanel({
        id: 'gridRO',
        frame: true,
        border: true,
        stripeRows: true,
        sm: smgridRO,
        store: strpembelianapproval,
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
            width: 300
        },{
            header: "TOP",
            dataIndex: 'waktu_top',
            sortable: true,
            width: 50
        }],
		listeners: {
            'rowclick': function(){              
                var sm = gridRO.getSelectionModel();                
                var sel = sm.getSelections(); 				
                gridDetRO.store.proxy.conn.url = '<?= site_url("approval/get_rows_detail") ?>/' + sel[0].get('no_ro');
                gridDetRO.store.reload();
            }          
        },
        tbar: tb_approval,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strpembelianapproval,
            displayInfo: true
        })
    });

	// shorthand alias
	var fm = Ext.form;

	var cm = new Ext.ux.grid.LockingColumnModel({
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
        },action_approval_detail_approve,action_approval_detail_reject,{
            header: "Nama Barang",
            dataIndex: 'nama_produk',
            sortable: true,
            width: 300
        },{
            header: "Qty",
            dataIndex: 'qty',
            sortable: true,
            width: 50
		},{
            header: "Qty Adjust",
            dataIndex: 'qty_adj',
            sortable: true,
            width: 100,
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
			header: "Alasan",
            dataIndex: 'keterangan',
            sortable: true,
            width: 200,
			editor: new fm.TextField({
                    allowBlank: false,
                })
		}],
        
    });	
	
	var gridDetRO = new Ext.grid.EditorGridPanel({
		id: 'gridDetRO',
        store: strpembelianapprovaldetail,
        stripeRows: true,
        style: 'margin-bottom:5px;',
        height: 225,
        frame: true,
        border:true,
        loadMask: true,
		sm: smgridDetRo,
        view: new Ext.ux.grid.LockingGridView(),
        plugins: [action_approval_detail_approve,action_approval_detail_reject],
        cm: cm,
		tbar:[{
				iconCls: 'icon-approve-record',
				text: 'Approve All',
				handler: function(){
					edit_approval(1);
				}
			},{
				ref: '../removeBtn',
				icon: BASE_ICONS + 'delete.gif',
				text: 'Reject All',
				handler: function(){
					edit_approval(9);
				}
			}]
    });	
    
	
	var pembelianapproval = new Ext.FormPanel({
			id: 'pembelianapproval',
			border: false,
			frame: true,
			autoScroll:true,		
			bodyStyle:'padding:5px;',
			items: [gridRO,gridDetRO]
	});  
    
    
    function edit_approval(stat){
	var messages = 'Reject';
	if(stat == 1){
		messages = 'Approve';
	}
       	
        var sm = gridRO.getSelectionModel();
        var sel = sm.getSelections();

        if (sel.length > 0) {
            Ext.Msg.show({
                title: 'Confirm',
                msg: 'Apakah anda akan ' + messages + ' semua barang ?',
                buttons: Ext.Msg.YESNO,
                fn: function(btn){
                    if (btn == 'yes') {
                    
                        var data =  sel[0].get('no_ro') + '_' + sel[0].get('keterangan') + '_' + stat ;         
						
                        var detailrequestapproval = new Array();
			strpembelianapprovaldetail.commitChanges();
			strpembelianapprovaldetail.each(function(node){						
				detailrequestapproval.push(node.data)
			});

			Ext.Ajax.request({
				url: '<?= site_url("approval/update_row") ?>',
				method: 'POST',
				params: {
					postdata: data,
					detail: Ext.util.JSON.encode(detailrequestapproval)
				},
				callback:function(opt,success,responseObj){
					var de = Ext.util.JSON.decode(responseObj.responseText);
					if(de.success==true){
						strpembelianapproval.reload();
						strpembelianapproval.load({
							params: {
								start: STARTPAGE,
								limit: ENDPAGE
							}
						});
						strpembelianapprovaldetail.reload();
						strpembelianapprovaldetail.load({
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
    
    function edit_approval_detail(no_ro_det, kd_prod_det, stat){
		var messages = 'Reject';
		if(stat == 1){
			messages = 'Approve';
		}

        var sm = gridDetRO.getSelectionModel();
        var sel = sm.getSelections();
        if (sel.length > 0) {
            Ext.Msg.show({
                title: 'Confirm',
                msg: 'Apakah anda akan ' + messages + ' barang ini ?',
                buttons: Ext.Msg.YESNO,
                fn: function(btn){
                    if (btn == 'yes') {
				
			if( ( sel[0].get('qty') != sel[0].get('qty_adj') ) && sel[0].get('keterangan') == ''){
				Ext.Msg.show({
			                title: 'Error',
			                msg: 'Silahkan isi Alasan terlebih dahulu !!',
			                modal: true,
			                icon: Ext.Msg.ERROR,
			                buttons: Ext.Msg.OK			               
			            });	
			}else{

	
		                var data =  no_ro_det + '_' + kd_prod_det + '_' + sel[0].get('qty') +  '_' + sel[0].get('qty_adj') + '_' + sel[0].get('keterangan') + '_' + stat ;                        
		                Ext.Ajax.request({
		                    url: '<?= site_url("approval/update_row_detail") ?>',
		                    method: 'POST',
		                    params: {
		                        postdata: data
		                    },
					callback:function(opt,success,responseObj){
					var de = Ext.util.JSON.decode(responseObj.responseText);
					if(de.success==true){
						strpembelianapproval.reload();
	                                	strpembelianapproval.load({
			                            params: {
			                                start: STARTPAGE,
			                                limit: ENDPAGE
			                            }
		                        	});
						strpembelianapprovaldetail.reload();
			                        strpembelianapprovaldetail.load({
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
