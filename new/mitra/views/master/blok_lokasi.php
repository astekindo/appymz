<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">    
    /* START FORM */
	
	// combobox lokasi
	var strcblokasi = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_lokasi', 'nama_lokasi'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("blok_lokasi/get_all") ?>',
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
    
    var cblokasi = new Ext.form.ComboBox({
        fieldLabel: 'Nama Lokasi <span class="asterix">*</span>',
        id: 'id_cblokasi',
        store: strcblokasi,
        valueField: 'kd_lokasi',
        displayField: 'nama_lokasi',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_lokasi',
        emptyText: 'Pilih Lokasi'
    });
	 
    Ext.ns('bloklokasiform');
    bloklokasiform.Form = Ext.extend(Ext.form.FormPanel, {
    
        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 100,
        url: '<?= site_url("blok_lokasi/update_row") ?>',
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
            bloklokasiform.Form.superclass.constructor.call(this, config);
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
                    name: 'kd_blok'
                }, {
                    xtype: 'hidden',
                    name: 'kd_lokasi'
                }, cblokasi, {
                    type: 'textfield',
                    fieldLabel: 'Nama Blok <span class="asterix">*</span>',
                    name: 'nama_blok',
                    allowBlank: false,
                    id: 'id_nama_blok',
                    maxLength: 40,
					style:'text-transform: uppercase',
                    anchor: '90%'                
                },{
                    type: 'textfield',
                    fieldLabel: 'Nama Blok2 <span class="asterix">*</span>',
                    name: 'nama_blok2',
                    allowBlank: false,
                    id: 'id_nama_blok2',
                    maxLength: 40,
					style:'text-transform: uppercase',
                    anchor: '90%'                
                }],
                buttons: [{
                    text: 'Submit',
                    id: 'btnsubmitbloklokasi',
                    formBind: true,
                    scope: this,
                    handler: this.submit
                }, {
                    text: 'Reset',
                    id: 'btnresetbloklokasi',
                    scope: this,
                    handler: this.reset
                }, {
                    text: 'Close',
                    id: 'btnCloseBlok',
                    scope: this,
                    handler: function(){
                        winaddbloklokasi.hide();
                    }
                }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            
            // call parent
            bloklokasiform.Form.superclass.initComponent.apply(this, arguments);
            
        } // eo function initComponent	
        ,
        onRender: function(){
        
            // call parent
            bloklokasiform.Form.superclass.onRender.apply(this, arguments);
            
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
			var text = Ext.getCmp('btnsubmitbloklokasi').getText();
			if (text == 'Update'){
				Ext.Msg.show({
					title: 'Confirm',
					msg: 'Are you sure update selected row ?',
					buttons: Ext.Msg.YESNO,
					fn: function(btn){
						if (btn == 'yes') {
							Ext.getCmp('id_formaddbloklokasi').getForm().submit({
								url: Ext.getCmp('id_formaddbloklokasi').url,
								scope: this,
								success: Ext.getCmp('id_formaddbloklokasi').onSuccess,
								failure: Ext.getCmp('id_formaddbloklokasi').onFailure,
								params: {
									cmd: 'save'
								},
								waitMsg: 'Saving Data...'
							});
						}
					}
				})
			}else{
				Ext.getCmp('id_formaddbloklokasi').getForm().submit({
					url: Ext.getCmp('id_formaddbloklokasi').url,
					scope: this,
					success: Ext.getCmp('id_formaddbloklokasi').onSuccess,
					failure: Ext.getCmp('id_formaddbloklokasi').onFailure,
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
            
            
            strbloklokasi.reload();
            Ext.getCmp('id_formaddbloklokasi').getForm().reset();
            winaddbloklokasi.hide();
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
    Ext.reg('formaddbloklokasi', bloklokasiform.Form);
    
    var winaddbloklokasi = new Ext.Window({
        id: 'id_winaddbloklokasi',
        closeAction: 'hide',
        width: 450,
        height: 350,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_formaddbloklokasi',
            xtype: 'formaddbloklokasi'
        },
        onHide: function(){
            Ext.getCmp('id_formaddbloklokasi').getForm().reset();
        }
    });
    
	/* START GRID */    
	
	// data store
	var strbloklokasi = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [ 
				'kd_blok',
				'nama_blok',
				'kd_lokasi',
				'nama_lokasi',
				'blok',
				'nm_blok',
                                'nama_blok2',
                                'nm_blok2'
			],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("blok_lokasi/get_rows") ?>',
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
    var searchbloklokasi = new Ext.app.SearchField({
        store: strbloklokasi,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchbloklokasi'
    });
    
	// top toolbar
    var tbbloklokasi = new Ext.Toolbar({
        items: [{
            text: 'Add',
            icon: BASE_ICONS + 'add.png',
            onClick: function(){	
				strcblokasi.load();
				Ext.getCmp('id_cblokasi').setDisabled(false);			
				Ext.getCmp('id_cblokasi').setValue('');			
                Ext.getCmp('btnresetbloklokasi').show();
                Ext.getCmp('btnsubmitbloklokasi').setText('Submit');
                winaddbloklokasi.setTitle('Add Form');
                winaddbloklokasi.show();                
            }            
        }, '-', searchbloklokasi]
    });
	
	// checkbox grid
    var cbGrid = new Ext.grid.CheckboxSelectionModel();
    
	// row actions
	var actionbloklokasi = new Ext.ux.grid.RowActions({
        header:'Edit',
		autoWidth: false,
		width: 30,
	    actions:[
	      {iconCls: 'icon-edit-record', qtip: 'Edit'}],
	    widthIntercept: Ext.isSafari ? 4 : 2
	});	
	var actionbloklokasidel = new Ext.ux.grid.RowActions({
        header:'Delete',
		autoWidth: false,
		width: 40,
	    actions:[	      
	      {iconCls: 'icon-delete-record', qtip: 'Delete'}],
	    widthIntercept: Ext.isSafari ? 4 : 2
	});	
	
	actionbloklokasi.on('action', function(grid, record, action, row, col) {
		var id_bloklokasi = record.get('kd_blok');
		var id__lokasi = record.get('kd_lokasi');
		var nm_lokasi = record.get('nama_lokasi');
		if (action=='icon-edit-record'){
		editbloklokasi(id_bloklokasi,id__lokasi,nm_lokasi);
		}
	});  
	
  	actionbloklokasidel.on('action', function(grid, record, action, row, col) {
		var id_bloklokasi = record.get('kd_blok');
		var id__lokasi = record.get('kd_lokasi');
		var nm_lokasi = record.get('nama_lokasi');
		if (action=='icon-delete-record'){
		Ext.Msg.show({
			title: 'Confirm',
			msg: 'Are you sure delete selected row ?',
			buttons: Ext.Msg.YESNO,
			fn: function(btn){
				if (btn == 'yes') {
					Ext.Ajax.request({
						url: '<?= site_url("blok_lokasi/delete_row") ?>',
						method: 'POST',
						params: {
							kd_blok: id_bloklokasi,
							kd_lokasi: id__lokasi
						},
						callback:function(opt,success,responseObj){
							var de = Ext.util.JSON.decode(responseObj.responseText);
							if(de.success==true){
								strbloklokasi.reload();
								strbloklokasi.load({
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
	});  
  	
	// grid
    var bloklokasi = new Ext.grid.EditorGridPanel({
        //id: 'bloklokasi',
        id: 'bloklokasi',
		frame: true,
        border: true,
        stripeRows: true,
        sm: cbGrid,
        store: strbloklokasi,
        loadMask: true,
        style: 'margin:0 auto;',
        height: 450,
        columns: [actionbloklokasi,{
            header: "Kode Blok",
            dataIndex: 'blok',
            sortable: true,
            width: 90
        },{
            header: "Nama Blok",
            dataIndex: 'nm_blok',
            sortable: true,
            width: 300
        },{
            header: "Nama Blok2",
            dataIndex: 'nm_blok2',
            sortable: true,
            width: 300
        }],
		plugins: [actionbloklokasi],
        listeners: {
            'rowdblclick': function(){				
                var sm = bloklokasi.getSelectionModel();                
                var sel = sm.getSelections();                
                if (sel.length > 0) {
					editbloklokasi(sel[0].get('kd_blok'),sel[0].get('kd_lokasi'),sel[0].get('nama_lokasi'));                    
                }                 
            }          
        },
        tbar: tbbloklokasi,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strbloklokasi,
            displayInfo: true
        })
    });
    /**
	var bloklokasipanel = new Ext.FormPanel({
	 	id: 'bloklokasi',
		border: false,
        frame: false,
		autoScroll:true,	
        items: [bloklokasi]
	});
	**/
	function editbloklokasi(id_bloklokasi,id__lokasi,nama_lokasi){
		strcblokasi.load();
		Ext.getCmp('id_cblokasi').setDisabled(true);
		Ext.getCmp('id_cblokasi').setValue(nama_lokasi);
		Ext.getCmp('btnresetbloklokasi').hide();		
        Ext.getCmp('btnsubmitbloklokasi').setText('Update');
        winaddbloklokasi.setTitle('Edit Form');
        Ext.getCmp('id_formaddbloklokasi').getForm().load({
            url: '<?= site_url("blok_lokasi/get_row") ?>',
            params: {
                id: id_bloklokasi,
                id1: id__lokasi,
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
        winaddbloklokasi.show();
	}
	
    function deletebloklokasi(){		
        var sm = bloklokasi.getSelectionModel();
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
                            data = data + sel[i].get('kd_blok') + '-' + sel[i].get('kd_lokasi') + ';';
                        }
                        
                        Ext.Ajax.request({
                            url: '<?= site_url("blok_lokasi/delete_rows") ?>',
                            method: 'POST',
                            params: {
                                postdata: data
                            },
							callback:function(opt,success,responseObj){
								var de = Ext.util.JSON.decode(responseObj.responseText);
								if(de.success==true){
									strbloklokasi.reload();
	                                strbloklokasi.load({
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
