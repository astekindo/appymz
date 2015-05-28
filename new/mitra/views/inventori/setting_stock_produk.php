<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">    
	var strcbsetstcokP = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_produk', 'kd_produk_lama', 'nama_produk'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("setting_stock_produk/get_produk") ?>',
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
	
	var searchshgproduk = new Ext.app.SearchField({
        store: strcbsetstcokP,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchlistbarang'
    });
    
    var tbshgproduk = new Ext.Toolbar({
        items: [searchshgproduk]
    });
	
	var gridshgsearchproduk = new Ext.grid.GridPanel({
        store: strcbsetstcokP,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
            header: 'Kode Produk',
            dataIndex: 'kd_produk',
            width: 90,
            sortable: true,			
            
        },{
            header: 'Kode Produk Lama',
            dataIndex: 'kd_produk_lama',
            width: 110,
            sortable: true,			
            
        },{
            header: 'Nama Produk',
            dataIndex: 'nama_produk',
            width: 400,
			sortable: true,         
        }],
		listeners: {
			'rowdblclick': function(){			
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {					
					Ext.Ajax.request({
                        url: '<?= site_url("setting_stock_produk/get_row_kode_produk") ?>',
                        method: 'POST',
                        params: {
                            kd_produk: sel[0].get('kd_produk')
                        },
						callback:function(opt,success,responseObj){
							var de = Ext.util.JSON.decode(responseObj.responseText);
							if(de.success==true){
								var senders = Ext.getCmp('ssp_gridSender').getValue();
								if(senders === 'ssp_kd_produk'){
									Ext.getCmp(senders).setValue(sel[0].get('kd_produk'));
									Ext.getCmp('ssp_nm_produk').setValue(sel[0].get('nama_produk'));
									if(de.data.kd_produk!=undefined){							
										Ext.getCmp('ssp_stockmin').setValue(de.data.stok_min);								
										Ext.getCmp('ssp_stockmax').setValue(de.data.stok_max);	
										Ext.getCmp('ssp_maxorder').setValue(de.data.max_order);							
										Ext.getCmp('ssp_stock_alert').setValue(de.data.pct_alert);				
										Ext.getCmp('ssp_is_kelipatan').setValue(de.data.is_kelipatan);
			
									}else{								
										Ext.getCmp('ssp_stockmin').setValue(0);								
										Ext.getCmp('ssp_stockmax').setValue(0);								
										Ext.getCmp('ssp_maxorder').setValue(0);								
										Ext.getCmp('ssp_stock_alert').setValue(0);								

									}
								}
								
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
					menushg.hide();
				}
			}
		},
		tbar:tbshgproduk,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strcbsetstcokP,
            displayInfo: true
        })
    });	

	var menushg = new Ext.menu.Menu();
		menushg.add(new Ext.Panel({
			title: 'Pilih Produk',
			layout: 'fit',
			buttonAlign: 'left',
			modal: true,
			width: 500,
			height: 400,
			closeAction: 'hide',
			plain: true,
			items: [gridshgsearchproduk],
			buttons: [{
				text: 'Close',
				handler: function(){
					menushg.hide();
				}
			}]
		}));
		
    Ext.ux.TwinCombo = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
			//load store grid
			Ext.getCmp('ssp_gridSender').setValue(this.id);
            strcbsetstcokP.load();
            menushg.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
	
	//FORM
    var settingstockp = new Ext.FormPanel({
        id: 'settingstockp',
        border: false,
        frame: true,
        autoScroll:true,        
        bodyStyle:'padding-right:20px;',
        labelWidth: 100,
        items: [{xtype: 'hidden', 
				 name: 'gridSender',
				 id:'ssp_gridSender',
				},{ 	xtype:'fieldset',
							autoheight: true,
							title: 'Produk',
							anchor: '90%',
							items:[{xtype : 'compositefield',
										anchor: '90%',
										msgTarget: 'side',
										fieldLabel: 'Kode Produk',
										width: 400,
										items : [new Ext.ux.TwinCombo({
														id: 'ssp_kd_produk',
														store: strcbsetstcokP,
														valueField: 'kd_produk',
														displayField: 'kd_produk',
														typeAhead: true,
														allowBlank: false,			
														editable: false,
														hiddenName: 'kd_produk',
														emptyText: 'Pilih Kode Produk',    
														listeners:{
															'expand': function(){
																strcbsetstcokP.load();
															}
														}
													})
												,{
													xtype: 'displayfield',
													value: 'Nama Produk',
													flex:1,
													width: 130,
													style: 'padding-left:30px',
											   },{
													xtype: 'textfield',
													name: 'nama_produk',
													fieldClass:'readonly-input',
													readOnly: true,
													id: 'ssp_nm_produk',
													flex: 1,
													anchor: '90%'                
												}]
									}]
				},{	
					xtype:'fieldset',
					autoheight: true,
					title: 'Harga',
					anchor: '90%',
					items:[ {
							xtype : 'compositefield',
							msgTarget: 'side',
							fieldLabel: 'Stock Min',
							items : [ {
										xtype: 'numberfield',
										name: 'stok_min',
										id: 'ssp_stockmin',
										maxLength: 11,
										style: 'text-align:right;',
										value: 0,
										width: 170,
										anchor: '90%'
									}]
							},{
							xtype : 'compositefield',
							msgTarget: 'side',
							fieldLabel: 'Stock max',
							items : [ {
										xtype: 'numberfield',
										name: 'stok_max',
										id: 'ssp_stockmax',
										maxLength: 11,
										style: 'text-align:right;',
										value: 0,
										width: 170,
										anchor: '90%'
									}]
							},{
							xtype : 'compositefield',
							msgTarget: 'side',
							fieldLabel: 'Min Order',
							items : [ {
										xtype: 'numberfield',
										name: 'max_order',
										id: 'ssp_maxorder',
										maxLength: 11,
										style: 'text-align:right;',
										value: 0,
										width: 170,
										anchor: '90%'
									}]
							},{
							xtype : 'compositefield',
							msgTarget: 'side',
							fieldLabel: 'Is Kelipatan',
							items : [ new Ext.form.Checkbox({
									xtype: 'checkbox',
									boxLabel:'Ya',
									name:'is_kelipatan',
									id:'ssp_is_kelipatan',
									inputValue: '1',
									autoLoad : true,
									checked: false,
									width: 250
								})]
							},{
							xtype : 'compositefield',
							msgTarget: 'side',
							fieldLabel: 'Stock Alert (%)',
							items : [ {
										xtype: 'numberfield',
										name: 'pct_alert',
										id: 'ssp_stock_alert',
										maxLength: 11,
										style: 'text-align:right;',
										value: 0,
										width: 50,
										anchor: '90%'
									}]
							}]
				}],
        buttons: [{
            text: 'Save',
            handler: function(){
				
				Ext.getCmp('settingstockp').getForm().submit({
	                url: '<?= site_url("setting_stock_produk/update_row") ?>',
	                scope: this,
	                waitMsg: 'Saving Data...',
					success: function(form, action){
			            Ext.Msg.show({
			                title: 'Success',
			                msg: 'Form submitted successfully',
			                modal: true,
			                icon: Ext.Msg.INFO,
			                buttons: Ext.Msg.OK
			            });			            
			            
			            clearsettinghargajual();						
					},
					failure: function(form, action){		            
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
                clearsettinghargajual();
            }
        }]
    });
	
     function clearsettinghargajual(){
        Ext.getCmp('settingstockp').getForm().reset();
		strcbsetstcokP.load();
    }
</script>
