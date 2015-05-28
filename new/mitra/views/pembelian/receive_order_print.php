<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript"> 
	var strcbpropsuplier = new Ext.data.ArrayStore({
        fields: ['nama_supplier'],
        data : []
    });
	
	var strgridpropsuplier = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_supplier', 'nama_supplier'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_receive_order/search_supplier") ?>',
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
	
	strgridpropsuplier.on('load', function(){
		 Ext.getCmp('id_searchgridpropsuplier').focus();
	});
	
	var searchgridpropsuplier = new Ext.app.SearchField({
        store: strgridpropsuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
		id: 'id_searchgridpropsuplier'
    });
	
	var gridpropsuplier = new Ext.grid.GridPanel({
        store: strgridpropsuplier,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
            header: 'Kode Supplier',
            dataIndex: 'kd_supplier',
            width: 80,
            sortable: true,			
            
        },{
            header: 'Nama Supplier',
            dataIndex: 'nama_supplier',
            width: 300,
			sortable: true,         
        }],
		tbar: new Ext.Toolbar({
	        items: [searchgridpropsuplier]
	    }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridpropsuplier,
            displayInfo: true
        }),
		listeners: {
			'rowdblclick': function(){			
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {				
                    // Ext.getCmp('prop_kd_supplier').setValue(sel[0].get('kd_supplier'));
					Ext.getCmp('id_cbpropsuplier').setValue(sel[0].get('nama_supplier'));
                    strreceiveorderprint.load({
						params:{
								kd_supplier: sel[0].get('kd_supplier')
						}
					});       
					menupropsuplier.hide();
				}
			}
		}
    });
	
	var menupropsuplier = new Ext.menu.Menu();
    menupropsuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridpropsuplier],
        buttons: [{
            text: 'Close',
            handler: function(){
                menupropsuplier.hide();
            }
        }]
    }));
    
    Ext.ux.TwinCombopropSuplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
			//load store grid
            strgridpropsuplier.load();
            menupropsuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
	menupropsuplier.on('hide', function(){
		var sf = Ext.getCmp('id_searchgridpropsuplier').getValue();
		if( sf != ''){
			Ext.getCmp('id_searchgridpropsuplier').setValue('');
			searchgridpropsuplier.onTrigger2Click();
		}
	});
	
	var cbpropsuplier = new Ext.ux.TwinCombopropSuplier({
        fieldLabel: 'Supplier',
        id: 'id_cbpropsuplier',
        store: strcbpropsuplier,
		mode: 'local',
        valueField: 'nama_supplier',
        displayField: 'nama_supplier',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'nama_supplier',
        emptyText: 'Pilih Supplier'
    });
	   
    var headerreceiveorderprint = {
        layout: 'column',
        border: false,
        items: [{
            columnWidth: .5,
            layout: 'form',
            border: false,
            labelWidth: 100,
			defaults: { labelSeparator: ''},
            items: [cbpropsuplier]
		}]
    }

    /* START GRID */
    var strreceiveorderprint = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
				'no_do',
                'kd_supplier',
                'nama_supplier',
				'tanggal',
				'tanggal_terima',
				'no_bukti_supplier',
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({			
               
            url: '<?= site_url("pembelian_receive_order_print/get_rows") ?>',
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
    var search_receive_order_print = new Ext.app.SearchField({
        store: strreceiveorderprint,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearch_receive_order_print'
    });
    
    // top toolbar
    var tb_receive_order_print = new Ext.Toolbar({
        items: [search_receive_order_print, '->', '<i>Klik row untuk melihat detail RO</i>']
    });
    
    // checkbox grid
    var smgridproprint = new Ext.grid.CheckboxSelectionModel();
    var smgridDetproprint = new Ext.grid.CheckboxSelectionModel();
    
    // data store
    var strreceiveorderprintdetail = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_produk',
                'nama_produk',
				'qty_beli',
				'qty_terima',
				'nm_satuan'
			],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_receive_order_print/get_rows_detail") ?>',
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
	
	strreceiveorderprint.on('load', function(){
		strreceiveorderprintdetail.removeAll();
	})
	
	
    var editorpembelianapprovalrequestmanager = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });
	
	var gridproprint = new Ext.grid.EditorGridPanel({
        id: 'gridproprint',
        frame: true,
        border: true,
        stripeRows: true,
        sm: smgridproprint,
        store: strreceiveorderprint,
        loadMask: true,
        title: 'RO',
        style: 'margin:0 auto;',
        height: 225,
        // width: 550,
        columns: [{
            header: "No RO",
            dataIndex: 'no_do',
            // hidden: true,
            sortable: true,
            width: 150
        },{
            header: "Kode Supplier",
            dataIndex: 'kd_supplier',
            sortable: true,
            width: 150
        },{
            header: "Nama Supplier",
            dataIndex: 'nama_supplier',
            sortable: true,
            width: 250
        },{
            header: "Tanggal Input",
            dataIndex: 'tanggal',
            sortable: true,
            width: 80
        },{
            header: "Tanggal Terima",
            dataIndex: 'tanggal_terima',
            sortable: true,
            width: 90
        },{
            header: "No Bukti Supplier",
            dataIndex: 'no_bukti_supplier',
            sortable: true,
            width: 300
        }],
		listeners: {
            'rowclick': function(){              
                var sm = gridproprint.getSelectionModel();                
                var sel = sm.getSelections(); 				
                gridDetproprint.store.proxy.conn.url = '<?= site_url("pembelian_receive_order_print/get_rows_detail") ?>/' + sel[0].get('no_do');
                gridDetproprint.store.reload();
            }          
        }
        // tbar: tb_purchase_request_print,
        //bbar: new Ext.PagingToolbar({
        //    pageSize: ENDPAGE,
        //    store: strreceiveorderprint,
        //    displayInfo: true
        //})
    });

	// shorthand alias
	var fm = Ext.form;

	var cmm = new Ext.ux.grid.LockingColumnModel({
        // specify any defaults for each column
        defaults: {
            sortable: true // columns are not sortable by default           
        },
        columns: [ {
            header: "Kode Barang",
            dataIndex: 'kd_produk',
            sortable: true,
            width: 250
        },{
            header: "Nama Barang",
            dataIndex: 'nama_produk',
            sortable: true,
            width: 250
        },{
            header: "Qty Beli",
            dataIndex: 'qty_beli',
            sortable: true,
            width: 50
		},{
            header: "Qty Terima",
            dataIndex: 'qty_terima',
            sortable: true,
            width: 90
		},{
            header: "Satuan",
            dataIndex: 'nm_satuan',
            sortable: true,
            width: 100
       }],
        
    });	
	
	var gridDetproprint = new Ext.grid.EditorGridPanel({
		id: 'gridDetproprint',
        store: strreceiveorderprintdetail,
        stripeRows: true,
        style: 'margin-bottom:5px;',
        height: 225,
        frame: true,
        border:true,
        loadMask: true,
		sm: smgridDetproprint,
        plugins: [action_approval_detail_approve_manager,action_approval_detail_reject_manager],
		view: new Ext.ux.grid.LockingGridView(),
        cm: cmm,
    });	
	
	
	var winprintreceiveorderprint = new Ext.Window({
        id: 'id_winprintreceiveorderprint',
		title: 'Print Receive Order Form',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="printreceiveorderprint" src=""></iframe>'
    });
	
	var receiveorderprint = new Ext.FormPanel({
	 	id: 'receiveorderprint',
		border: false,
		frame: true,
		monitorValid: true,
        	labelWidth: 130,
        items: [{
                    bodyStyle: {
                        margin: '0px 0px 15px 0px'
                    },					
                    items: [headerreceiveorderprint]
                },gridproprint, gridDetproprint
				
        ],
        buttons: [{
            text: 'Cetak',
            handler: function(){
						 
						var sm = gridproprint.getSelectionModel();                
						var sel = sm.getSelections(); 	
						winprintreceiveorderprint.show();
						Ext.getDom('printreceiveorderprint').src = '<?= site_url("pembelian_receive_order/print_form") ?>'+'/'+sel[0].get('no_do');
					}
        },{
			text: 'Reset',
			handler: function(){
				clearreceiveorderprint(); 
			}
		}]
    });
		
	function clearreceiveorderprint(){
		Ext.getCmp('receiveorderprint').getForm().reset();
		strreceiveorderprint.removeAll();
		strreceiveorderprintdetail.removeAll();
	}
</script>