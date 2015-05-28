<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">   
	// combobox satuan
    var strcbsatuanmep = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_satuan', 'nm_satuan'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_barang/get_satuan") ?>',
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
    
	var cbsatuanmep = new Ext.form.ComboBox({
		fieldLabel: 'Satuan <span class="asterix">*</span>',
		id: 'mep_cbsatuan',
        name: 'satuan',
		store: strcbsatuanmep,
		valueField: 'kd_satuan',
		displayField: 'nm_satuan',
		typeAhead: true,
		triggerAction: 'all',
		allowBlank: false,
		editable: false,
		anchor: '90%',
		width: 170,
		hiddenName: 'kd_satuan',
		emptyText: 'Pilih Satuan'
	});
	
    /* START FORM */ 
    Ext.ns('masterekspedisiform');
    masterekspedisiform.Form = Ext.extend(Ext.form.FormPanel, {
    
        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 130,
        url: '<?= site_url("master_ekspedisi/update_row") ?>',
        constructor: function(config){
            config = config || {};
            config.listeners = config.listeners || {};
            Ext.applyIf(config.listeners, {
                actioncomplete: function(){
                    //if (console && console.log) {
                    //    console.log('actioncomplete:', arguments);
                    //}
                },
                actionfailed: function(){
                    //if (console && console.log) {
                    //    console.log('actionfailed:', arguments);
                    //}
                }
            });
            masterekspedisiform.Form.superclass.constructor.call(this, config);
        },
        initComponent: function(){
        
            // hard coded - cannot be changed from outsid
            var config = {
                defaultType: 'textfield',
                defaults: { labelSeparator: ''},
                monitorValid: true,
                autoScroll: false // ,buttonAlign:'right'
                ,
                items: [{
							xtype: 'hidden',
							name: 'action',
							id: 'me_action'
						},{
							xtype: 'hidden',
							name: 'kd_ekspedisi',
							id: 'me_kd_ekspedisi'
						},{
							xtype: 'textfield',
							fieldLabel: 'Nama Ekspedisi <span class="asterix">*</span>',
							name: 'nama_ekspedisi',
							id: 'me_nama_supp',
							anchor: '90%'                
						},new Ext.form.Checkbox({
								xtype: 'checkbox',
								fieldLabel: 'Status Aktif',
								boxLabel:'Ya',
								name:'aktif',
								id:'me_aktif',
								inputValue: '1',
								autoLoad : true
							})],
					buttons: [{
						text: 'Submit',
						id: 'btnsubmitmasterekspedisi',
						formBind: true,
						scope: this,
						handler: this.submit
					}, {
						text: 'Reset',
						id: 'btnresetmasterekspedisi',
						scope: this,
						handler: this.reset
					},{
						text: 'Close',
						id: 'btnClosemasterekspedisi',
						scope: this,
						handler: function(){
							winaddmasterekspedisi.hide();
						}
					}]
				}; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            
            // call parent
            masterekspedisiform.Form.superclass.initComponent.apply(this, arguments);
            
        } // eo function initComponent  
        ,
        onRender: function(){
        
            // call parent
            masterekspedisiform.Form.superclass.onRender.apply(this, arguments);
            
            // set wait message target
            this.getForm().waitMsgTarget = this.getEl();
            
            // loads form after initial layout
            // this.on('afterlayout', this.onLoadClick, this, {single:true});
        
        } // eo function onRender
        ,
        reset: function(){
            this.getForm().reset();
        },
        submit: function(){
			var text = Ext.getCmp('btnsubmitmasterekspedisi').getText();
			if (text == 'Update'){
				Ext.Msg.show({
					title: 'Confirm',
					msg: 'Are you sure update selected row ?',
					buttons: Ext.Msg.YESNO,
					fn: function(btn){
						if (btn == 'yes') {
							Ext.getCmp('id_formaddmasterekspedisi').getForm().submit({
								url: Ext.getCmp('id_formaddmasterekspedisi').url,
								scope: this,
								success: Ext.getCmp('id_formaddmasterekspedisi').onSuccess,
								failure: Ext.getCmp('id_formaddmasterekspedisi').onFailure,
								params: {
									cmd: 'save'
								},
								waitMsg: 'Saving Data...'
							});
						}
					}
				})
			}else{
				Ext.getCmp('id_formaddmasterekspedisi').getForm().submit({
					url: Ext.getCmp('id_formaddmasterekspedisi').url,
					scope: this,
					success: Ext.getCmp('id_formaddmasterekspedisi').onSuccess,
					failure: Ext.getCmp('id_formaddmasterekspedisi').onFailure,
					params: {
						cmd: 'save'
					},
					waitMsg: 'Saving Data...'
				});
			}
        } // eo function submit
        ,
        onSuccess: function(form, action){
            Ext.Msg.show({
                title: 'Success',
                msg: 'Form submitted successfully',
                modal: true,
                icon: Ext.Msg.INFO,
                buttons: Ext.Msg.OK
            });
            
            strmasterekspedisi.reload();
            strmasterekspedisiprice.load({
				params:{fieldId:Ext.getCmp('mep_kd_ekspedisi_search').getValue()}
			});
            Ext.getCmp('id_formaddmasterekspedisi').getForm().reset();
            winaddmasterekspedisi.hide();
        } // eo function onSuccess
        ,
        onFailure: function(form, action){
        
            var fe = Ext.util.JSON.decode(action.response.responseText);
            this.showError(fe.errMsg || '');
            
            
        } // eo function onFailure
        ,
        showError: function(msg, title){
            title = title || 'Error';
            Ext.Msg.show({
                title: title,
                msg: msg,
                modal: true,
                icon: Ext.Msg.ERROR,
                buttons: Ext.Msg.OK,
                fn: function(btn){
                    if (btn == 'ok' && msg == 'Session Expired') {
                        window.location = '<?= site_url("auth/login") ?>';
                    }
                }
            });
        }
    }); // eo extend
    // register xtype
    Ext.reg('formaddmasterekspedisi', masterekspedisiform.Form);
    
    var winaddmasterekspedisi = new Ext.Window({
        id: 'id_winaddmasterekspedisi',
        closeAction: 'hide',
        width: 450,
        height: 350,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_formaddmasterekspedisi',
            xtype: 'formaddmasterekspedisi'
        },
        onHide: function(){
            Ext.getCmp('id_formaddmasterekspedisi').getForm().reset();
        }
    });  
	
	Ext.ns('masterekspedisipriceform');
    masterekspedisipriceform.Form = Ext.extend(Ext.form.FormPanel, {
    
        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 130,
        url: '<?= site_url("master_ekspedisi/update_row") ?>',
        constructor: function(config){
            config = config || {};
            config.listeners = config.listeners || {};
            Ext.applyIf(config.listeners, {
                actioncomplete: function(){
                },
                actionfailed: function(){
                }
            });
            masterekspedisipriceform.Form.superclass.constructor.call(this, config);
        },
        initComponent: function(){
        
            // hard coded - cannot be changed from outsid
            var config = {
				 defaultType: 'textfield',
                defaults: { labelSeparator: ''},
                monitorValid: true,
                autoScroll: false,
				items: [
                    {
                        xtype: 'textfield',
                        fieldLabel: 'Kode Ekspedisi <span class="asterix">*</span>',
                        name: 'kd_ekspedisi',
                        id: 'mep_kd_ekspedisi',
                        readOnly:true,
                        fieldClass:'readonly-input',
                        anchor: '90%'
                    },{
                        xtype: 'textfield',
                        fieldLabel: 'Tujuan <span class="asterix">*</span>',
                        name: 'tujuan',
                        id: 'mep_tujuan',
                        anchor: '90%'
                    },{
                        xtype: 'numberfield',
                        fieldLabel: 'Nilai Satuan<span class="asterix">*</span>',
                        name: 'nilai_satuan',
                        id: 'mep_jml_satuan',
                        anchor: '90%'
                    },cbsatuanmep,{
                        xtype: 'numberfield',
                        fieldLabel: 'Harga <span class="asterix">*</span>',
                        name: 'rp_harga',
                        id: 'mep_rp_harga',
                        anchor: '90%'
                    },{
                        xtype: 'textarea',
                        fieldLabel: 'Keterangan <span class="asterix">*</span>',
                        name: 'keterangan',
                        id: 'id_mep_keterangan',
                        maxLength: 40,
                        anchor: '90%'
                    }
                ],
					buttons: [{
						text: 'Submit',
						id: 'btnsubmitmasterekspedisiprice',
						formBind: true,
						scope: this,
						handler: this.submit
					}, {
						text: 'Reset',
						id: 'btnresetmasterekspedisiprice',
						scope: this,
						handler: this.reset
					},{
						text: 'Close',
						id: 'btnClosemasterekspedisiprice',
						scope: this,
						handler: function(){
							winAddMasterEkspedisiHarga.hide();
						}
					}]
				}; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            
            // call parent
            masterekspedisipriceform.Form.superclass.initComponent.apply(this, arguments);
            
        } // eo function initComponent  
        ,
        onRender: function(){
        
            // call parent
            masterekspedisipriceform.Form.superclass.onRender.apply(this, arguments);
            
            // set wait message target
            this.getForm().waitMsgTarget = this.getEl();
            
            // loads form after initial layout
            // this.on('afterlayout', this.onLoadClick, this, {single:true});
        
        } // eo function onRender
        ,
        reset: function(){
            this.getForm().reset();
        },
        submit: function(){
        
            this.getForm().submit({
                url: this.url,
                scope: this,
                success: this.onSuccess,
                failure: this.onFailure,
                params: {
                    cmd: 'save',
                    action: Ext.getCmp('mep_action').getValue()
                },
                waitMsg: 'Saving Data...'
            });
        } // eo function submit
        ,
        onSuccess: function(form, action){
            Ext.Msg.show({
                title: 'Success',
                msg: 'Form submitted successfully',
                modal: true,
                icon: Ext.Msg.INFO,
                buttons: Ext.Msg.OK
            });
            
            strmasterekspedisiprice.load({
				params:{fieldId:Ext.getCmp('mep_kd_ekspedisi_search').getValue()}
			});
            Ext.getCmp('id_formaddmasterekspedisiprice').getForm().reset();
            winAddMasterEkspedisiHarga.hide();
        } // eo function onSuccess
        ,
        onFailure: function(form, action){
        
            var fe = Ext.util.JSON.decode(action.response.responseText);
            this.showError(fe.errMsg || '');
            
            
        } // eo function onFailure
        ,
        showError: function(msg, title){
            title = title || 'Error';
            Ext.Msg.show({
                title: title,
                msg: msg,
                modal: true,
                icon: Ext.Msg.ERROR,
                buttons: Ext.Msg.OK,
                fn: function(btn){
                    if (btn == 'ok' && msg == 'Session Expired') {
                        window.location = '<?= site_url("auth/login") ?>';
                    }
                }
            });
        }
    }); // eo extend
    // register xtype
    Ext.reg('formaddmasterekspedisiprice', masterekspedisipriceform.Form);
    
    var winAddMasterEkspedisiHarga = new Ext.Window({
        id: 'id_winaddmasterekspedisiprice',
        closeAction: 'hide',
        width: 450,
        height: 350,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_formaddmasterekspedisiprice',
            xtype: 'formaddmasterekspedisiprice'
        },
        onHide: function(){
            Ext.getCmp('id_formaddmasterekspedisiprice').getForm().reset();
        }
    });
    
    /* START GRID */    
	
	/* START TOP GRID */
	
	// data store	
    var strmasterekspedisi = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
				'kd_ekspedisi',
                'nama_ekspedisi',
                'aktif'
			],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_ekspedisi/get_rows_master") ?>',
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
	
	var searchFieldmasterekspedisi = new Ext.app.SearchField({
		width: 220,
		id: 'search_query',
		store: strmasterekspedisi
	});
		
	var tbmasterekspedisi = new Ext.Toolbar({
        items: [{
            text: 'Add',
            icon: BASE_ICONS + 'add.png',
            onClick: function(){
                Ext.getCmp('me_action').setValue('save_master');
                Ext.getCmp('me_action').setRawValue('save_master');
                Ext.getCmp('btnresetmasterekspedisi').show();
                Ext.getCmp('btnsubmitmasterekspedisi').setText('Submit');
                winaddmasterekspedisi.setTitle('Add Form');
                winaddmasterekspedisi.show();                
            }
        }, '-', searchFieldmasterekspedisi]
    });
    
    // checkbox grid
    var cbGrid = new Ext.grid.CheckboxSelectionModel();
    var smGrid = new Ext.grid.CheckboxSelectionModel();
    
    // row actions
    var actionmasterekspedisi = new Ext.ux.grid.RowActions({
		header :'Edit',
		autoWidth: false,
		width: 30,
        actions:[{iconCls: 'icon-edit-record', qtip: 'Edit'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    }); 
    actionmasterekspedisi.on('action', function(grid, record, action, row, col) {
        var kd_ekspedisi = record.get('kd_ekspedisi');
        switch(action) {
            case 'icon-edit-record':                
                editMasterEkspedisi(kd_ekspedisi);
                break; 
			
        }
    });  
	
	 var gridmasterekspedisi = new Ext.grid.EditorGridPanel({
        id: 'gridmasterekspedisi',
        frame: true,
        border: true,
        stripeRows: true,
        sm: cbGrid,
        store: strmasterekspedisi,
        loadMask: true,
        // title: 'Master Ekspedisi',
        style: 'margin:0 auto;',
        height: 200,
        // width: 550,
        columns: [actionmasterekspedisi,{
            header: "Kode Ekspedisi",
            dataIndex: 'kd_ekspedisi',
            sortable: true,
            width: 150
        },{
            header: "Nama Ekspedisi",
            dataIndex: 'nama_ekspedisi',
            sortable: true,
            width: 250
        },{
            header: "Aktif",
            dataIndex: 'aktif',
            sortable: true,
            width: 150
        }],
        plugins: [actionmasterekspedisi],
        listeners: {
            'rowclick': function(){              
                var sm = gridmasterekspedisi.getSelectionModel();                
                var sel = sm.getSelections(); 				
                gridmasterekspedisiprice.store.proxy.conn.url = '<?= site_url("master_ekspedisi/get_rows_price") ?>/' + sel[0].get('kd_ekspedisi');
                gridmasterekspedisiprice.store.reload();
                Ext.getCmp('mep_kd_ekspedisi_search').setValue(sel[0].get('kd_ekspedisi'));
                Ext.getCmp('mep_kd_ekspedisi').setValue(sel[0].get('kd_ekspedisi'));
            },    
            'rowdblclick': function(){        
                var sm = gridmasterekspedisi.getSelectionModel();                
                var sel = sm.getSelections();          
               editMasterEkspedisi(sel[0].get('kd_ekspedisi'));
            }          
        },
		tbar: tbmasterekspedisi,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strmasterekspedisi,
            displayInfo: true
        })
    });
	
	/* END TOP GRID */
	
	
	/* START BOTTOM GRID */
	var strmasterekspedisiprice = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_ekspedisi',
                'kd_harga',
                'tujuan',
                'kd_satuan',
                'nm_satuan',
                'nilai_satuan',
                'rp_harga',
                'keterangan'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({			
            url: '<?= site_url("master_ekspedisi/get_rows_price") ?>',
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
	var searchFieldmasterekspedisiprice = new Ext.app.SearchField({
		width: 220,
		id: 'search_query',
		store: strmasterekspedisiprice
	});
	
	searchFieldmasterekspedisiprice.onTrigger1Click = function(evt) {
		if (this.hasSearch) {
			this.el.dom.value = '';
			
			// Get the value of search field
			var fid = Ext.getCmp('mep_kd_ekspedisi_search').getValue();
			var o = { start: 0, fieldId: fid };
			
			this.store.baseParams = this.store.baseParams || {};
			this.store.baseParams[this.paramName] = '';
			this.store.reload({
						params : o
					});
			this.triggers[0].hide();
			this.hasSearch = false;
		}
	};
	
	searchFieldmasterekspedisiprice.onTrigger2Click = function(evt) {
	  var text = this.getRawValue();
	  if (text.length < 1) {
		this.onTrigger1Click();
		return;
	  }
	 
	  // Get the value of search field
	  var fid = Ext.getCmp('mep_kd_ekspedisi_search').getValue();
	  var o = { start: 0, fieldId: fid };
	 
	  this.store.baseParams = this.store.baseParams || {};
	  this.store.baseParams[this.paramName] = text;
	  this.store.reload({params:o});
	  this.hasSearch = true;
	  this.triggers[0].show();
	};
	
    // top toolbar
	var tbmasterekspedisiprice = new Ext.Toolbar({
        items: [{
            text: 'Add',
            icon: BASE_ICONS + 'add.png',
            onClick: function(){
                Ext.getCmp('mep_action').setValue('save_price');
                Ext.getCmp('mep_action').setRawValue('save_price');
                Ext.getCmp('mep_tujuan').setReadOnly(false);
                Ext.getCmp('mep_tujuan').removeClass('readonly-input');
                Ext.getCmp('btnresetmasterekspedisiprice').show();
                Ext.getCmp('btnsubmitmasterekspedisiprice').setText('Submit');
                winAddMasterEkspedisiHarga.setTitle('Add Form');
                winAddMasterEkspedisiHarga.show();
            }
        }, '-', 
		{
			xtype: 'hidden',
			name: 'kd_ekspedisi_search',
			id: 'mep_kd_ekspedisi_search'
		},searchFieldmasterekspedisiprice]
    });
    
    // checkbox grid
    var cbGridmasterekspedisiprice = new Ext.grid.CheckboxSelectionModel();
    var smGridmasterekspedisiprice = new Ext.grid.CheckboxSelectionModel();
    
    // row actions
    var actionmasterekspedisiprice = new Ext.ux.grid.RowActions({
		header :'Edit',
		autoWidth: false,
		// locked: true,
		width: 30,
        actions:[{iconCls: 'icon-edit-record', qtip: 'Edit'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    }); 
	
    var actionmasterekspedisipricedel = new Ext.ux.grid.RowActions({
		header: 'Delete',
		autoWidth: false,
		width: 40,
        actions:[{iconCls: 'icon-delete-record', qtip: 'Delete'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    }); 
    
    actionmasterekspedisiprice.on('action', function(grid, record, action, row, col) {
        var kd_harga = record.get('kd_harga');
        var kd_ekspedisi = record.get('kd_ekspedisi');
        var tujuan = record.get('tujuan');
        switch(action) {
            case 'icon-edit-record':                
                editMasterEkspedisiHarga(kd_harga);
                break;
			case 'icon-delete-record':
                Ext.Msg.show({
                    title: 'Confirm',
                    msg: 'Are you sure delete selected row ?',
                    buttons: Ext.Msg.YESNO,
                    fn: function(btn){
                        if (btn == 'yes') {
                            Ext.Ajax.request({
                                url: '<?= site_url("master_ekspedisi/delete_row_price") ?>',
                                method: 'POST',
                                params: {
                                    kd_harga: kd_harga,
									tujuan: tujuan
                                },
                                callback:function(opt,success,responseObj){
                                    var de = Ext.util.JSON.decode(responseObj.responseText);
                                    if(de.success==true){
                                        strmasterekspedisiprice.load({
											params:{
                                                kd_ekspedisi: kd_ekspedisi
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
	
	var gridmasterekspedisiprice = new Ext.grid.EditorGridPanel({
        id: 'gridmasterekspedisiprice',
        frame: true,
        border: true,
		sm: smGrid,
        stripeRows: true,
        store: strmasterekspedisiprice,
        loadMask: true,
        style: 'margin:0 auto;',
        height: 250,
		view: new Ext.ux.grid.LockingGridView(),
        colModel: new Ext.ux.grid.LockingColumnModel([
		actionmasterekspedisiprice,actionmasterekspedisipricedel,{
            dataIndex: 'kd_ekspedisi',
			id: 'gmep_kd_ekspedisi',
            header: 'Kd Ekspedisi',
            sortable: true,
            width: 75
        }, {
            dataIndex: 'kd_harga',
			id: 'gmep_kd_harga',
            header: 'Kode Harga',
            sortable: true,
            width: 75
        },{
            dataIndex: 'tujuan',
            header: 'Tujuan',
            sortable: true,
            width: 200
        },{
            header: "Nilai Satuan",
            dataIndex: 'nilai_satuan',
            sortable: true,
            width: 100
        },{
            header: "Satuan",
            dataIndex: 'nm_satuan',
            sortable: true,
            width: 80
        },{
            xtype: 'numbercolumn',
            header: "Harga",
            dataIndex: 'rp_harga',
            sortable: true,
            align: 'right',
            format: '0,0',
            width: 110
        },{
            header: "Keterangan",
            dataIndex: 'keterangan',
            sortable: false,
            width: 200
        }]),
        plugins: [actionmasterekspedisiprice,actionmasterekspedisipricedel],
        listeners: {
            'rowdblclick': function(){              
                var sm = gridmasterekspedisiprice.getSelectionModel();                
                var sel = sm.getSelections();                
                if (sel.length > 0) {
                    editMasterEkspedisiHarga(sel[0].get('kd_ekspedisi'));
                }                 
            }          
        },
        tbar: tbmasterekspedisiprice,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strmasterekspedisiprice,
            displayInfo: true
        })
    });
    
	/* END BOTTOM GRID */
	
	 var masterekspedisi = new Ext.FormPanel({
			id: 'masterekspedisi',
			border: false,
			frame: true,
			autoScroll:true,		
			bodyStyle:'padding:5px;',
			items: [gridmasterekspedisi,gridmasterekspedisiprice]
		});
    // grid
   
    function editMasterEkspedisi(kd_ekspedisi){
		Ext.getCmp('me_action').setValue('update_master');
		Ext.getCmp('me_action').setRawValue('update_master');
        Ext.getCmp('btnresetmasterekspedisi').hide();
        Ext.getCmp('btnsubmitmasterekspedisi').setText('Update');
        winaddmasterekspedisi.setTitle('Edit Form');
		
        Ext.getCmp('id_formaddmasterekspedisi').getForm().load({
            url: '<?= site_url("master_ekspedisi/get_row_master") ?>',
            params: {
                id: kd_ekspedisi,
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
        winaddmasterekspedisi.show();
    }
    function editMasterEkspedisiHarga(kd_harga){
        Ext.getCmp('mep_action').setValue('update_price');
        Ext.getCmp('mep_action').setRawValue('update_price');
		Ext.getCmp('mep_tujuan').addClass('readonly-input');
		Ext.getCmp('mep_tujuan').setReadOnly(true);
        Ext.getCmp('btnresetmasterekspedisi').hide();
        Ext.getCmp('btnsubmitmasterekspedisi').setText('Update');
        winAddMasterEkspedisiHarga.setTitle('Edit Form');

        Ext.getCmp('id_formaddmasterekspedisiprice').getForm().load({
            url: '<?= site_url("master_ekspedisi/get_row_price") ?>',
            params: {
                id: kd_harga,
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
        winAddMasterEkspedisiHarga.show();
    }
    
</script>
