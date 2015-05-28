<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">    
    /* START FORM */ 
    Ext.ns('masterlokasiform');
    masterlokasiform.Form = Ext.extend(Ext.form.FormPanel, {
    
        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 100,
        url: '<?= site_url("master_lokasi/update_row") ?>',
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
            masterlokasiform.Form.superclass.constructor.call(this, config);
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
                    name: 'kd_lokasi'
                }, {
                    type: 'textfield',
                    fieldLabel: 'Nama Lokasi <span class="asterix">*</span>',
                    name: 'nama_lokasi',
                    allowBlank: false,
                    id: 'id_nama_lokasi',
                    maxLength: 40,
					style:'text-transform: uppercase',
                    anchor: '90%'                
                },{
                    type: 'textfield',
                    fieldLabel: 'Nama Lokasi2 <span class="asterix">*</span>',
                    name: 'nama_lokasi2',
                    allowBlank: false,
                    id: 'id_nama_lokasi2',
                    maxLength: 40,
					style:'text-transform: uppercase',
                    anchor: '90%'                
                },{
					xtype: 'radiogroup',                 
					fieldLabel: 'Peruntukan <span class="asterix">*</span>',
					columnWidth: [.5, .5], 	                   
					name: 'kd_peruntukan',
					width: 170,
					anchor: '90%',
					allowBlank:false,  
					items: [{                    
						boxLabel: 'Supermarket',                     
						name: 'kd_peruntukan',                     
						id: 'mlokasi_kd_peruntukanS', 					
						inputValue: '0',
						checked: true,
					}, {                     
						boxLabel: 'Distribusi',
						name: 'kd_peruntukan',                     
						inputValue: '1',                    
						id: 'mlokasi_kd_peruntukanD'                 
							
					}]             
				}, new Ext.form.Checkbox({
						xtype: 'checkbox',
						fieldLabel: 'Status Aktif <span class="asterix">*</span>',
                    	boxLabel:'Ya',
						name:'aktif',
						id:'mlokasi_aktif',
						inputValue: '1',
						autoLoad : true
					})],
                buttons: [{
                    text: 'Submit',
                    id: 'btnsubmitmasterlokasi',
                    formBind: true,
                    scope: this,
                    handler: this.submit
                }, {
                    text: 'Reset',
                    id: 'btnresetmasterlokasi',
                    scope: this,
                    handler: this.reset
                }, {
                    text: 'Close',
                    id: 'btnCloseLokasi',
                    scope: this,
                    handler: function(){
                        winaddmasterlokasi.hide();
                    }
                }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            
            // call parent
            masterlokasiform.Form.superclass.initComponent.apply(this, arguments);
            
        } // eo function initComponent	
        ,
        onRender: function(){
        
            // call parent
            masterlokasiform.Form.superclass.onRender.apply(this, arguments);
            
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
			var text = Ext.getCmp('btnsubmitmasterlokasi').getText();
			if (text == 'Update'){
				Ext.Msg.show({
					title: 'Confirm',
					msg: 'Are you sure update selected row ?',
					buttons: Ext.Msg.YESNO,
					fn: function(btn){
						if (btn == 'yes') {
							Ext.getCmp('id_formaddmasterlokasi').getForm().submit({
								url: Ext.getCmp('id_formaddmasterlokasi').url,
								scope: this,
								success: Ext.getCmp('id_formaddmasterlokasi').onSuccess,
								failure: Ext.getCmp('id_formaddmasterlokasi').onFailure,
								params: {
									cmd: 'save'
								},
								waitMsg: 'Saving Data...'
							});
						}
					}
				})
			}else{
				Ext.getCmp('id_formaddmasterlokasi').getForm().submit({
					url: Ext.getCmp('id_formaddmasterlokasi').url,
					scope: this,
					success: Ext.getCmp('id_formaddmasterlokasi').onSuccess,
					failure: Ext.getCmp('id_formaddmasterlokasi').onFailure,
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
            
            
            strmasterlokasi.reload();
            Ext.getCmp('id_formaddmasterlokasi').getForm().reset();
            winaddmasterlokasi.hide();
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
    Ext.reg('formaddmasterlokasi', masterlokasiform.Form);
    
    var winaddmasterlokasi = new Ext.Window({
        id: 'id_winaddmasterlokasi',
        closeAction: 'hide',
        width: 450,
        height: 350,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_formaddmasterlokasi',
            xtype: 'formaddmasterlokasi'
        },
        onHide: function(){
            Ext.getCmp('id_formaddmasterlokasi').getForm().reset();
        }
    });
    
	/* START GRID */    
	var strmasterlokasi = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
				'kd_lokasi',
				'nama_lokasi',
                                'kd_peruntukan_string',
				'kd_peruntukan',
				'aktif',
                                'nama_lokasi2'
			],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_lokasi/get_rows") ?>',
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
	
    var searchmasterlokasi = new Ext.app.SearchField({
        store: strmasterlokasi,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchmasterlokasi'
    });
    
    var tbmasterlokasi = new Ext.Toolbar({
        items: [{
            text: 'Add',
            icon: BASE_ICONS + 'add.png',
            onClick: function(){				
                Ext.getCmp('btnresetmasterlokasi').show();
                Ext.getCmp('btnsubmitmasterlokasi').setText('Submit');
                winaddmasterlokasi.setTitle('Add Form');
                Ext.getCmp('id_formaddmasterlokasi').getForm().load({
                        url: '<?= site_url("master_lokasi/get_form") ?>',
                        success: function(form, action){
                            var r = Ext.util.JSON.decode(action.response.responseText);
                            if(r.data.user_peruntukan === "0"){
                                Ext.getCmp('mlokasi_kd_peruntukanS').setValue(true);
                                Ext.getCmp('mlokasi_kd_peruntukanS').show();
                                Ext.getCmp('mlokasi_kd_peruntukanD').hide();
                            }else if(r.data.user_peruntukan === "1"){
                                Ext.getCmp('mlokasi_kd_peruntukanD').setValue(true);
                                Ext.getCmp('mlokasi_kd_peruntukanS').hide();
                                Ext.getCmp('mlokasi_kd_peruntukanD').show();
                            }else{
                                Ext.getCmp('mlokasi_kd_peruntukanS').setValue(true);
                                Ext.getCmp('mlokasi_kd_peruntukanS').show();
                                Ext.getCmp('mlokasi_kd_peruntukanD').show();
                            }
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
                winaddmasterlokasi.show();                
            }            
        }, '-', searchmasterlokasi]
    });

    var cbGrid = new Ext.grid.CheckboxSelectionModel();
    	
	// row actions
	var actionmasterlokasi = new Ext.ux.grid.RowActions({
		header :'Edit',
		autoWidth: false,
		width: 30,
	    actions:[{iconCls: 'icon-edit-record', qtip: 'Edit'}],
	    widthIntercept: Ext.isSafari ? 4 : 2
	});	
	
	var actionmasterlokasidel = new Ext.ux.grid.RowActions({
		header: 'Delete',
		autoWidth: false,
		width: 40,
	    actions:[{iconCls: 'icon-delete-record', qtip: 'Delete'}],
	    widthIntercept: Ext.isSafari ? 4 : 2
	});	
	
	actionmasterlokasi.on('action', function(grid, record, action, row, col) {
		var kd_lokasi = record.get('kd_lokasi');
		switch(action) {
			case 'icon-edit-record':	        	
				editmasterlokasi(kd_lokasi);
	        	break;
	      	case 'icon-delete-record':
				Ext.Msg.show({
	                title: 'Confirm',
	                msg: 'Are you sure delete selected row ?',
	                buttons: Ext.Msg.YESNO,
	                fn: function(btn){
	                    if (btn == 'yes') {
	                        Ext.Ajax.request({
	                            url: '<?= site_url("master_lokasi/delete_rows") ?>',
	                            method: 'POST',
	                            params: {
	                                postdata: kd_lokasi
	                            },
								callback:function(opt,success,responseObj){
									var de = Ext.util.JSON.decode(responseObj.responseText);
									if(de.success==true){
										strmasterlokasi.reload();
		                                strmasterlokasi.load({
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
	        	break;	      
	      	
	    }
	});  
    var masterlokasi = new Ext.grid.EditorGridPanel({
        //id: 'id-masterlokasi-gridpanel',
        id: 'masterlokasi',
		frame: true,
        border: true,
        stripeRows: true,
        sm: cbGrid,
        store: strmasterlokasi,
        loadMask: true,
        style: 'margin:0 auto;',
        height: 450,
        columns: [actionmasterlokasi,{
            header: "Kode Lokasi",
            dataIndex: 'kd_lokasi',
            sortable: true,
            width: 150
        },{
            header: "Nama Lokasi",
            dataIndex: 'nama_lokasi',
            sortable: true,
            width: 300
        },{
            header: "Nama Lokasi2",
            dataIndex: 'nama_lokasi2',
            sortable: true,
            width: 200
        },{
            header: "Peruntukan",
            dataIndex: 'kd_peruntukan_string',
            sortable: true,
            width: 75
        },{
            header: "Status Aktif",
            dataIndex: 'aktif',
            sortable: true,
            width: 75
        }],
		plugins: [actionmasterlokasi],
        listeners: {
            'rowdblclick': function(){
				
                var sm = masterlokasi.getSelectionModel();
                
                var sel = sm.getSelections();
                
                if (sel.length > 0) {
                    Ext.getCmp('btnresetmasterlokasi').hide();
                    Ext.getCmp('btnsubmitmasterlokasi').setText('Update');
                    winaddmasterlokasi.setTitle('Edit Form');
                    Ext.getCmp('id_formaddmasterlokasi').getForm().load({
                        url: '<?= site_url("master_lokasi/get_row") ?>',
                        params: {
                            id: sel[0].get('kd_lokasi'),
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
                    winaddmasterlokasi.show();
                }
                 
            }
          
        },
        tbar: tbmasterlokasi,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strmasterlokasi,
            displayInfo: true
        })
    });
    /**
	var masterlokasipanel = new Ext.FormPanel({
	 	id: 'masterlokasi',
		border: false,
        frame: false,
		autoScroll:true,	
        items: [masterlokasi]
	});
	**/
	function editmasterlokasi(kd_lokasi){
		Ext.getCmp('btnresetmasterlokasi').hide();
        Ext.getCmp('btnsubmitmasterlokasi').setText('Update');
        winaddmasterlokasi.setTitle('Edit Form');
        Ext.getCmp('id_formaddmasterlokasi').getForm().load({
            url: '<?= site_url("master_lokasi/get_row") ?>',
            params: {
                id: kd_lokasi,
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
        winaddmasterlokasi.show();
	}
    
    function deletemasterlokasi(){		
        var sm = masterlokasi.getSelectionModel();
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
                            data = data + sel[i].get('kd_lokasi') + ';';
                        }
                        
                        Ext.Ajax.request({
                            url: '<?= site_url("master_lokasi/delete_rows") ?>',
                            method: 'POST',
                            params: {
                                postdata: data
                            },
							callback:function(opt,success,responseObj){
								var de = Ext.util.JSON.decode(responseObj.responseText);
								if(de.success==true){
									strmasterlokasi.reload();
	                                strmasterlokasi.load({
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
