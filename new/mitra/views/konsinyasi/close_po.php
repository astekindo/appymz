<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript"> 
    var strcbsuplierkonsinyasiclosepo = new Ext.data.ArrayStore({
        fields: ['nama_supplier'],
        data : []
    });
	
    var strgridpopsuplier_konsinyasiclosepo = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_supplier', 'nama_supplier'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_create_request/search_supplier") ?>',
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
	
    strgridpopsuplier_konsinyasiclosepo.on('load', function(){
        Ext.getCmp('id_searchgridpopsuplier_konsinyasiclosepo').focus();
    });
	
    var searchgridpopsuplier_konsinyasiclosepo = new Ext.app.SearchField({
        store: strgridpopsuplier_konsinyasiclosepo,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridpopsuplier_konsinyasiclosepo'
    });
	
    var gridpopsuplier_konsinyasiclosepo = new Ext.grid.GridPanel({
        store: strgridpopsuplier_konsinyasiclosepo,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'Kode Supplier',
                dataIndex: 'kd_supplier',
                width: 80,
                sortable: true			
            
            },{
                header: 'Nama Supplier',
                dataIndex: 'nama_supplier',
                width: 300,
                sortable: true     
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridpopsuplier_konsinyasiclosepo]
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                     Ext.getCmp('cpo_kd_supplier_kons').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_cbpopsuplier_konsinyasiclosepo').setValue(sel[0].get('nama_supplier'));
                   /* strkonsinyasiclosepo.load({
                        params:{
                            kd_supplier: sel[0].get('kd_supplier')
                        }
                    });       */
                    menupopsuplier_konsinyasiclosepo.hide();
                }
            }
        }
    });
	
    var menupopsuplier_konsinyasiclosepo = new Ext.menu.Menu();
    menupopsuplier_konsinyasiclosepo.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridpopsuplier_konsinyasiclosepo],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menupopsuplier_konsinyasiclosepo.hide();
                }
            }]
    }));
    
    Ext.ux.TwinCombopopSuplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgridpopsuplier_konsinyasiclosepo.load();
            menupopsuplier_konsinyasiclosepo.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menupopsuplier_konsinyasiclosepo.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridpopsuplier_konsinyasiclosepo').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgridpopsuplier_konsinyasiclosepo').setValue('');
            searchgridpopsuplier_konsinyasiclosepo.onTrigger2Click();
        }
    });
	
    var cbpopsuplierkonsinyasiclosepo = new Ext.ux.TwinCombopopSuplier({
        fieldLabel: 'Supplier',
        id: 'id_cbpopsuplier_konsinyasiclosepo',
        store: strcbsuplierkonsinyasiclosepo,
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
	   
    var headerkonsinyasiclosepo = {
        layout: 'column',
        border: false,
        buttonAlign: 'left',
        items: [{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [{
					xtype: 'textfield',
					fieldLabel: 'Kode Supplier',
					name: 'kd_supplier',
					readOnly: true,
					fieldClass: 'readonly-input',
					id: 'cpo_kd_supplier_kons',
					anchor: '90%',
					value: '',
					emptyText: 'Nama Supplier'
				},cbpopsuplierkonsinyasiclosepo
                            ],
            }],buttons: [{
			text: 'Filter',
			formBind: true,
			handler: function () {
				
				gridkonsinyasiclosepo.store.load({
					params: {
						kd_supplier: Ext.getCmp('cpo_kd_supplier_kons').getValue(),
						
					}
				});
			}
		}]
    }

    /* START GRID */
    var strkonsinyasiclosepo = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_suplier_po',
                'nama_supplier',
                'no_po',
                'tanggal_po',
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({			
               
            url: '<?= site_url("konsinyasi_close_po/get_rows") ?>' ,
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
    var search_close_purchase_order = new Ext.app.SearchField({
        store: strkonsinyasiclosepo,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
            emptyText: 'No Surat Pesanan',
        id: 'idsearch_close_purchase_order'
    });
    
    // top toolbar
    var tb_close_po = new Ext.Toolbar({
        items: [search_close_purchase_order, '->', '<i>Klik row untuk melihat detail surat pesanan</i>']
    });
    
    // checkbox grid
    var smgridkonsinyasiclosepo = new Ext.grid.CheckboxSelectionModel();
    var smgridDetkonsinyasiclosepo = new Ext.grid.CheckboxSelectionModel();
    
    // data store
    var strkonsinyasiclosepodetail = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_produk',
                'nama_produk',
                'qty_po',
                'nm_satuan',
                'qty_ro',
                'qty_sisa'
                
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("konsinyasi_close_po/get_rows_detail") ?>',
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
	
    strkonsinyasiclosepo.on('load', function(){
        strkonsinyasiclosepodetail.removeAll();
    })
	
	
    var editorpembelianapprovalordermanager = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });
	
    var gridkonsinyasiclosepo = new Ext.grid.EditorGridPanel({
        id: 'gridkonsinyasiclosepo',
        frame: true,
        border: true,
        stripeRows: true,
        sm: smgridkonsinyasiclosepo,
        store: strkonsinyasiclosepo,
        loadMask: true,
        title: 'Surat Pesanan',
        style: 'margin:0 auto;',
        height: 225,
        // width: 550,
        columns: [{
                header: "Kode Supplier",
                dataIndex: 'kd_suplier_po',
                sortable: true,
                width: 150
            },{
                header: "Nama Supplier",
                dataIndex: 'nama_supplier',
                sortable: true,
                width: 250
            },{
                header: "No Surat Pesanan",
                dataIndex: 'no_po',
                // hidden: true,
                sortable: true,
                width: 150
            },{
                header: "Tanggal",
                dataIndex: 'tanggal_po',
                sortable: true,
                width: 80
            }],
        listeners: {
            'rowclick': function(){              
                var sm = gridkonsinyasiclosepo.getSelectionModel();                
                var sel = sm.getSelections(); 				
                gridDetkonsinyasiclosepo.store.proxy.conn.url = '<?= site_url("Pembelian_close_po/get_rows_detail") ?>/' + sel[0].get('no_po');
                gridDetkonsinyasiclosepo.store.reload();
            }          
        },
         tbar: tb_close_po,
        bbar: new Ext.PagingToolbar({
          pageSize: ENDPAGE,
           store: strkonsinyasiclosepo,
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
                header: "Qty Surat Pesanan",
                dataIndex: 'qty_po',
                sortable: true,
                width: 120
            },
            {
                header: "Qty RO",
                dataIndex: 'qty_ro',
                sortable: true,
                width: 50
            },{
                header: "Qty Sisa",
                dataIndex: 'qty_sisa',
                sortable: true,
                width: 50
            },{
                header: "Satuan",
                dataIndex: 'nm_satuan',
                sortable: true,
                width: 50
            }]
        
    });	
	
    var gridDetkonsinyasiclosepo = new Ext.grid.EditorGridPanel({
        id: 'gridDetkonsinyasiclosepo',
        store: strkonsinyasiclosepodetail,
        stripeRows: true,
        style: 'margin-bottom:5px;',
        height: 225,
        frame: true,
        border:true,
        loadMask: true,
        sm: smgridDetkonsinyasiclosepo,
        plugins: [action_approval_detail_approve_manager,action_approval_detail_reject_manager],
        view: new Ext.ux.grid.LockingGridView(),
        cm: cmm
    });	
	
	
    /*var winprintpurchaseorderprint = new Ext.Window({
        id: 'id_winprintpurchaseorderprint',
        title: 'Print Purchase Order Form',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="printpurchaseorderprint" src=""></iframe>'
    });
	
    var winprintpurchaseordernonhargaprint = new Ext.Window({
        id: 'id_winprintpurchaseordernonhargaprint',
        title: 'Print Purchase Order Non Harga Form',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="printpurchaseordernonhargaprint" src=""></iframe>'
    });*/
	
    var konsinyasiclosepo = new Ext.FormPanel({
        id: 'konsinyasiclosepo',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },					
                items: [headerkonsinyasiclosepo]
            },gridkonsinyasiclosepo, gridDetkonsinyasiclosepo
				
        ],
        buttons: [{
                text: 'Close PO',
                handler: function(){
                    
                 var sm = gridkonsinyasiclosepo.getSelectionModel();                
                    var sel = sm.getSelections(); 
                    
                    Ext.getCmp('konsinyasiclosepo').getForm().submit({
                        url: '<?= site_url("konsinyasi_close_po/update_row") ?>',
                        scope: this,
                        params: {						
                                        no_po: sel[0].get('no_po'),
                                                                               					
                                    },
                        waitMsg: 'Close PO...',
                        success: function(form, action){
                            var r = Ext.util.JSON.decode(action.response.responseText);
                           Ext.Msg.show({
                                            title: 'Success',
                                            msg: r.errMsg,
                                            modal: true,
                                            icon: Ext.Msg.INFO,
                                            buttons: Ext.Msg.OK,
                                            fn: function(btn){
                                                if (btn == 'ok') {
                                                    //winreturpenjualanprint.show();
                                                    // Ext.getDom('returpenjualanprint').src = r.printUrl;
                                                }
                                            }
                            });                     
                        
                            clearkonsinyasiclosepo();                       
                        },
                        failure: function(form, action){        
                            var fe = Ext.util.JSON.decode(action.response.responseText);                        
                            Ext.Msg.show({
                                title: 'Error',
                                msg: fe.errMsg,
                                modal: true,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OK,
                                fn: function(btn){
                                    if (btn == 'ok' && fe.errMsg == 'Session Expired') {
                                        window.location = '<?= site_url("auth/login") ?>';
                                    }
                                }
                            });
                        
                        }                   
                    }); 
                }
            },{
                text: 'Reset',
                handler: function(){
                    clearkonsinyasiclosepo(); 
                }
            }]
    });
		
    function clearkonsinyasiclosepo(){
        Ext.getCmp('konsinyasiclosepo').getForm().reset();
        strkonsinyasiclosepo.removeAll();
        strkonsinyasiclosepodetail.removeAll();
    }
</script>