<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">    
    /* START GRID */    
    var strkonsinyasiapproval = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_ro',
                'subject',
				'tgl_ro',
				'keterangan1',
				'nama_supplier'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({			
               
            url: '<?= site_url("konsinyasi_approval/get_rows") ?>',
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
    var search_konsinyasi_approval = new Ext.app.SearchField({
        store: strkonsinyasiapproval,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearch_konsinyasi_approval'
    });
    
    // top toolbar
    var tb_konsinyasi_approval = new Ext.Toolbar({
        items: [search_konsinyasi_approval, '->', '<i>Klik row untuk melihat detail RO</i>']
    });
    
    // checkbox grid
    var smgridROKonsinyasi = new Ext.grid.CheckboxSelectionModel();
    var smgridDetRo = new Ext.grid.CheckboxSelectionModel();
    
    // data store
    var strkonsinyasiapprovaldetail = new Ext.data.Store({
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
            url: '<?= site_url("konsinyasi_approval/get_rows_detail") ?>',
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
	
	strkonsinyasiapproval.on('load', function(){
		strkonsinyasiapprovaldetail.removeAll();
	})
	
	var action_konsinyasi_approval_detail_approve = new Ext.ux.grid.RowActions({
		header :'Approve',
		autoWidth: false,
		width: 60,
        actions:[
          {iconCls: 'icon-approve-record', qtip: 'Approve'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    }); 
	
	var action_konsinyasi_approval_detail_reject = new Ext.ux.grid.RowActions({
		header :'Reject',
		autoWidth: false,
		width: 50,
        actions:[
          {iconCls: 'icon-delete-record', qtip: 'Reject'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    }); 
    
    action_konsinyasi_approval_detail_approve.on('action', function(grid, record, action, row, col) {
        var no_ro_det = record.get('no_ro');
        var kd_prod_det = record.get('kd_produk');
		switch(action){
			case 'icon-approve-record':
				edit_konsinyasi_approval_detail(no_ro_det,kd_prod_det,1);
			break;
		}		
    });
	
    action_konsinyasi_approval_detail_reject.on('action', function(grid, record, action, row, col) {
        var no_ro_det = record.get('no_ro');
        var kd_prod_det = record.get('kd_produk');
		switch(action){
			case 'icon-delete-record' :
				edit_konsinyasi_approval_detail(no_ro_det,kd_prod_det,9);
			break;
		}		
    });  
	
    var editorkonsinyasiapprovalrequest = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });
	
	var gridROKonsinyasi = new Ext.grid.EditorGridPanel({
        id: 'gridROKonsinyasi',
        frame: true,
        border: true,
        stripeRows: true,
        sm: smgridROKonsinyasi,
        store: strkonsinyasiapproval,
        loadMask: true,
        title: 'RO',
        style: 'margin:0 auto;',
        height: 200,
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
            width: 150
        },{
            header: "Nama Supplier",
            dataIndex: 'nama_supplier',
            sortable: true,
            width: 150
        }],
		listeners: {
            'rowclick': function(){              
                var sm = gridROKonsinyasi.getSelectionModel();                
                var sel = sm.getSelections(); 				
                gridDetROKonsinyasi.store.proxy.conn.url = '<?= site_url("konsinyasi_approval/get_rows_detail") ?>/' + sel[0].get('no_ro');
                gridDetROKonsinyasi.store.reload();
            }          
        },
        tbar: tb_konsinyasi_approval,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strkonsinyasiapproval,
            displayInfo: true
        })
    });

	// shorthand alias
	var fm = Ext.form;

	var cm = new Ext.grid.ColumnModel({
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
        },action_konsinyasi_approval_detail_approve,action_konsinyasi_approval_detail_reject,{
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
            width: 70,
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
	
	var gridDetROKonsinyasi = new Ext.grid.EditorGridPanel({
		id: 'gridDetROKonsinyasi',
        store: strkonsinyasiapprovaldetail,
        stripeRows: true,
        style: 'margin-bottom:5px;',
        height: 200,
        frame: true,
        border:true,
        loadMask: true,
		sm: smgridDetRo,
        plugins: [action_konsinyasi_approval_detail_approve,action_konsinyasi_approval_detail_reject],
        cm: cm,
		tbar:[{
				iconCls: 'icon-approve-record',
				text: 'Approve All',
				handler: function(){
					edit_konsinyasi_approval(1);
				}
			},{
				ref: '../removeBtn',
				icon: BASE_ICONS + 'delete.gif',
				text: 'Reject All',
				handler: function(){
					edit_konsinyasi_approval(9);
				}
			}]
    });	
    
	
	var konsinyasiapproval = new Ext.FormPanel({
			id: 'konsinyasiapproval',
			border: false,
			frame: true,
			autoScroll:true,		
			bodyStyle:'padding:5px;',
			items: [gridROKonsinyasi,gridDetROKonsinyasi,
					// { 	xtype: 'textarea',
						// fieldLabel: 'Keterangan',
						// name: 'keterangan1',                                    
						// id: 'kapp_keterangan1',      
						// anchor: '50%'
					// }
					]
	});  
    
    
    function edit_konsinyasi_approval(stat){
       	
		var messages = 'Reject';
		if(stat == 1){
			messages = 'Approve';
		}
        var sm = gridROKonsinyasi.getSelectionModel();
        var sel = sm.getSelections();
        if (sel.length > 0) {
            Ext.Msg.show({
                title: 'Confirm',
                msg: 'Apakah anda akan ' + messages + ' barang ini ?',
                buttons: Ext.Msg.YESNO,
                fn: function(btn){
                    if (btn == 'yes') {
                    
                        var data =  sel[0].get('no_ro') + '_'  + stat ;         
						
                        var detailrequestkonsinyasiapproval = new Array();
						strkonsinyasiapprovaldetail.commitChanges();
						strkonsinyasiapprovaldetail.each(function(node){						
							detailrequestkonsinyasiapproval.push(node.data)
						});
						
						if( ( sel[0].get('qty') != sel[0].get('qty_adj') ) && sel[0].get('keterangan') == ''){
							Ext.Msg.show({
										title: 'Error',
										msg: 'Silahkan isi Alasan terlebih dahulu !!',
										modal: true,
										icon: Ext.Msg.ERROR,
										buttons: Ext.Msg.OK			               
									});	
						}else{
							Ext.Ajax.request({
								url: '<?= site_url("konsinyasi_approval/update_row") ?>',
								method: 'POST',
								params: {
									postdata: data,
									detail: Ext.util.JSON.encode(detailrequestkonsinyasiapproval)
								},
								callback:function(opt,success,responseObj){
									var de = Ext.util.JSON.decode(responseObj.responseText);
									if(de.success==true){
										strkonsinyasiapproval.reload();
										strkonsinyasiapproval.load({
											params: {
												start: STARTPAGE,
												limit: ENDPAGE
											}
										});
										strkonsinyasiapprovaldetail.reload();
										strkonsinyasiapprovaldetail.load({
											params: {
												start: STARTPAGE,
												limit: ENDPAGE
											}
										});
										// Ext.getCmp('kapp_keterangan1').setValue('');
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
    
    function edit_konsinyasi_approval_detail(no_ro_det, kd_prod_det, stat){
		var messages = 'Reject';
		if(stat == 1){
			messages = 'Approve';
		}
        var sm = gridDetROKonsinyasi.getSelectionModel();
        var sel = sm.getSelections();
        if (sel.length > 0) {
            Ext.Msg.show({
                title: 'Confirm',
                msg: 'Apakah anda akan ' + messages + ' barang ini ?',
                buttons: Ext.Msg.YESNO,
                fn: function(btn){
                    if (btn == 'yes') {
						
                        var data =  no_ro_det + '_' + kd_prod_det + '_' + sel[0].get('qty') +  '_' + sel[0].get('qty_adj') + '_' + sel[0].get('keterangan') + '_' + stat ;                        
                        Ext.Ajax.request({
                            url: '<?= site_url("konsinyasi_approval/update_row_detail") ?>',
                            method: 'POST',
                            params: {
                                postdata: data
                            },
							callback:function(opt,success,responseObj){
								var de = Ext.util.JSON.decode(responseObj.responseText);
								if(de.success==true){
									strkonsinyasiapproval.reload();
	                                strkonsinyasiapproval.load({
	                                    params: {
	                                        start: STARTPAGE,
	                                        limit: ENDPAGE
	                                    }
                                	});
									strkonsinyasiapprovaldetail.reload();
	                                strkonsinyasiapprovaldetail.load({
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