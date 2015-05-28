<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript"> 
    var strcbsuplierclosepo = new Ext.data.ArrayStore({
        fields: ['nama_supplier'],
        data : []
    });
	
    var strgridpopsuplier_closepo = new Ext.data.Store({
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
	
    strgridpopsuplier_closepo.on('load', function(){
        Ext.getCmp('id_searchgridpopsuplier_closepo').focus();
    });
	
    var searchgridpopsuplier_closepo = new Ext.app.SearchField({
        store: strgridpopsuplier_closepo,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridpopsuplier_closepo'
    });
	
    var gridpopsuplier_closepo = new Ext.grid.GridPanel({
        store: strgridpopsuplier_closepo,
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
            items: [searchgridpopsuplier_closepo]
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                     Ext.getCmp('cpo_kd_supplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_cbpopsuplier_closepo').setValue(sel[0].get('nama_supplier'));
                   /* strclosepo.load({
                        params:{
                            kd_supplier: sel[0].get('kd_supplier')
                        }
                    });       */
                    menupopsuplier_closepo.hide();
                }
            }
        }
    });
	
    var menupopsuplier_closepo = new Ext.menu.Menu();
    menupopsuplier_closepo.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridpopsuplier_closepo],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menupopsuplier_closepo.hide();
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
            strgridpopsuplier_closepo.load();
            menupopsuplier_closepo.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menupopsuplier_closepo.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridpopsuplier_closepo').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgridpopsuplier_closepo').setValue('');
            searchgridpopsuplier_closepo.onTrigger2Click();
        }
    });
	
    var cbpopsuplierclosepo = new Ext.ux.TwinCombopopSuplier({
        fieldLabel: 'Supplier',
        id: 'id_cbpopsuplier_closepo',
        store: strcbsuplierclosepo,
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
	   
    var headerclosepo = {
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
					id: 'cpo_kd_supplier',
					anchor: '90%',
					value: '',
					emptyText: 'Kode Supplier'
				},cbpopsuplierclosepo
                            ],
            }],buttons: [{
			text: 'Filter',
			formBind: true,
			handler: function () {
				// var kd_supplier = Ext.getCmp('id_cbmprsuplier').getValue();
				// var tgl_awal = Ext.getCmp('mpr_tgl_awal').getValue();
				// var tgl_akhir = Ext.getCmp('mpr_tgl_akhir').getValue();
				// if (kd_supplier == '' && tgl_awal == '' && tgl_akhir == ''){
				// Ext.Msg.show({
				// title: 'Error',
				// msg: 'Silahkan Search Supplier / Tanggal Terlebih Dulu',
				// modal: true,
				// icon: Ext.Msg.ERROR,
				// buttons: Ext.Msg.OK			               
				// });
				// return;
				// }
				gridclosepo.store.load({
					params: {
						kd_supplier: Ext.getCmp('cpo_kd_supplier').getValue(),
						
					}
				});
			}
		}]
    }

    /* START GRID */
    var strclosepo = new Ext.data.Store({
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
               
            url: '<?= site_url("pembelian_close_po/get_rows") ?>' ,
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
        store: strclosepo,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
            emptyText: 'No PO',
        id: 'idsearch_close_purchase_order'
    });
    
    // top toolbar
    var tb_close_po = new Ext.Toolbar({
        items: [search_close_purchase_order, '->', '<i>Klik row untuk melihat detail PO</i>']
    });
    
    // checkbox grid
    var smgridclosepo = new Ext.grid.CheckboxSelectionModel();
    var smgridDetclosepo = new Ext.grid.CheckboxSelectionModel();
    
    // data store
    var strclosepodetail = new Ext.data.Store({
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
            url: '<?= site_url("pembelian_close_po/get_rows_detail") ?>',
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
	
    strclosepo.on('load', function(){
        strclosepodetail.removeAll();
    })
	
	
    var editorpembelianapprovalordermanager = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });
	
    var gridclosepo = new Ext.grid.EditorGridPanel({
        id: 'gridclosepo',
        frame: true,
        border: true,
        stripeRows: true,
        sm: smgridclosepo,
        store: strclosepo,
        loadMask: true,
        title: 'PO',
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
                header: "No PO",
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
                var sm = gridclosepo.getSelectionModel();                
                var sel = sm.getSelections(); 				
                gridDetclosepo.store.proxy.conn.url = '<?= site_url("pembelian_close_po/get_rows_detail") ?>/' + sel[0].get('no_po');
                gridDetclosepo.store.reload();
            }          
        },
         tbar: tb_close_po,
        bbar: new Ext.PagingToolbar({
          pageSize: ENDPAGE,
           store: strclosepo,
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
                header: "Qty PO",
                dataIndex: 'qty_po',
                sortable: true,
                width: 50
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
	
    var gridDetclosepo = new Ext.grid.EditorGridPanel({
        id: 'gridDetclosepo',
        store: strclosepodetail,
        stripeRows: true,
        style: 'margin-bottom:5px;',
        height: 225,
        frame: true,
        border:true,
        loadMask: true,
        sm: smgridDetclosepo,
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
	
    var closepo = new Ext.FormPanel({
        id: 'closepo',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },					
                items: [headerclosepo]
            },gridclosepo, gridDetclosepo
				
        ],
        buttons: [{
                text: 'Close PO',
                handler: function(){
                Ext.Msg.show({
                     title: 'Confirm',
                     msg: 'Apakah anda akan Close PO ini ??',
                     buttons: Ext.Msg.YESNO,
                     fn: function(btn){
                         if (btn == 'yes') {  
                            var sm = gridclosepo.getSelectionModel();                
                               var sel = sm.getSelections(); 

                               Ext.getCmp('closepo').getForm().submit({
                                   url: '<?= site_url("pembelian_close_po/update_row") ?>',
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

                                       clearclosepo();                       
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
                          }
                     });
                }
            },{
                text: 'Reset',
                handler: function(){
                    clearclosepo(); 
                }
            }]
    });
		
    function clearclosepo(){
        Ext.getCmp('closepo').getForm().reset();
        strclosepo.removeAll();
        strclosepodetail.removeAll();
    }
</script>