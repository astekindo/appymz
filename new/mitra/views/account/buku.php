<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">    
    /* START FORM */
	
	 
    Ext.ns('bukuform');
    bukuform.Form = Ext.extend(Ext.form.FormPanel, {
    
        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 100,
        url: '<?= site_url("buku/update_row") ?>',
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
            bukuform.Form.superclass.constructor.call(this, config);
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
                    name: 'kd_buku'
                },{
                    xtype: 'textarea',
                    fieldLabel: 'Nama Buku <span class="asterix">*</span>',
                    name: 'nama_buku',
                    allowBlank: false,
                    id: 'id_nama_buku',
                    maxLength: 40,
                    anchor: '90%'                
                }],
                buttons: [{
                    text: 'Submit',
                    id: 'btnsubmitbuku',
                    formBind: true,
                    scope: this,
                    handler: this.submit
                }, {
                    text: 'Reset',
                    id: 'btnresetbuku',
                    scope: this,
                    handler: this.reset
                }, {
                    text: 'Close',
                    id: 'btnClose',
                    scope: this,
                    handler: function(){
                        winaddbuku.hide();
                    }
                }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            
            // call parent
            bukuform.Form.superclass.initComponent.apply(this, arguments);
            
        } // eo function initComponent	
        ,
        onRender: function(){
        
            // call parent
            bukuform.Form.superclass.onRender.apply(this, arguments);
            
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
                    cmd: 'save'
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
            
            
            strbuku.reload();
            Ext.getCmp('id_formaddbuku').getForm().reset();
            winaddbuku.hide();
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
    Ext.reg('formaddbuku', bukuform.Form);
    
    var winaddbuku = new Ext.Window({
        id: 'id_winaddbuku',
        closeAction: 'hide',
        width: 450,
        height: 350,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_formaddbuku',
            xtype: 'formaddbuku'
        },
        onHide: function(){
            Ext.getCmp('id_formaddbuku').getForm().reset();
        }
    });
    
	/* START GRID */    
	
	// data store
	var strbuku = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [ 
				  'kd_buku',
				  'nama_buku'
			],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("buku/get_rows") ?>',
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
    var searchbuku = new Ext.app.SearchField({
        store: strbuku,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchbuku'
    });
    
	// top toolbar
    var tbbuku = new Ext.Toolbar({
        items: [{
            text: 'Add',
            icon: BASE_ICONS + 'add.png',
            onClick: function(){	
				//strcbtypeP.load(); 				Ext.getCmp('id_cbtypeP').setDisabled(false);			 				Ext.getCmp('id_cbtypeP').setValue('');			
                Ext.getCmp('btnresetbuku').show();
                Ext.getCmp('btnsubmitbuku').setText('Submit');
                winaddbuku.setTitle('Add Form');
                winaddbuku.show();                
            }            
        }, '-', searchbuku]
    });
	
	// checkbox grid
    var cbGrid = new Ext.grid.CheckboxSelectionModel();
    
	// row actions
	var actionbuku = new Ext.ux.grid.RowActions({
        header:'Edit',
		autoWidth: false,
		width: 30,
	    actions:[
	      {iconCls: 'icon-edit-record', qtip: 'Edit'}],
	    widthIntercept: Ext.isSafari ? 4 : 2
	});	
	var actionbukudel = new Ext.ux.grid.RowActions({
        header:'Delete',
		autoWidth: false,
		width: 40,
	    actions:[	      
	      {iconCls: 'icon-delete-record', qtip: 'Delete'}],
	    widthIntercept: Ext.isSafari ? 4 : 2
	});	
	
	actionbuku.on('action', function(grid, record, action, row, col) {
		var id_buku = record.get('kd_buku');
		if (action=='icon-edit-record'){
		editbuku(id_buku);
		}
	});  
	
  	actionbukudel.on('action', function(grid, record, action, row, col) {
		var id_buku = record.get('kd_buku');
		if (action=='icon-delete-record'){
		Ext.Msg.show({
			title: 'Confirm',
			msg: 'Are you sure delete selected row ?',
			buttons: Ext.Msg.YESNO,
			fn: function(btn){
				if (btn == 'yes') {
					Ext.Ajax.request({
						url: '<?= site_url("buku/delete_row") ?>',
						method: 'POST',
						params: {
							kd_buku: id_buku
						},
						callback:function(opt,success,responseObj){
							var de = Ext.util.JSON.decode(responseObj.responseText);
							if(de.success==true){
								strbuku.reload();
								strbuku.load({
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
    var buku = new Ext.grid.EditorGridPanel({
        id: 'id-buku-gridpanel',
        frame: true,
        border: true,
        stripeRows: true,
        sm: cbGrid,
        store: strbuku,
        loadMask: true,
        style: 'margin:0 auto;',
        height: 450,
        columns: [actionbuku,actionbukudel,{
            header: "Kode Buku",
            dataIndex: 'kd_buku',
            sortable: true,
            width: 90
        },{
            header: "Nama Buku",
            dataIndex: 'nama_buku',
            sortable: true,
            width: 200
        }],
		plugins: [actionbuku, actionbukudel],
        listeners: {
            'rowdblclick': function(){				
                var sm = buku.getSelectionModel();                
                var sel = sm.getSelections();                
                if (sel.length > 0) {
					editbuku(sel[0].get('kd_buku'),sel[0].get('nama_buku'));                    
                }                 
            }          
        },
        tbar: tbbuku,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strbuku,
            displayInfo: true
        })
    });
	
	var bukupanel = new Ext.FormPanel({
	 	id: 'buku',
		border: false,
        frame: false,
		autoScroll:true,	
        items: [buku]
	});
    
	function editbuku(id_buku,nama_buku){
		//strcbtypeP.load();
		//Ext.getCmp('id_cbtypeP').setDisabled(true);
		//Ext.getCmp('id_cbtypeP').setValue(type_parameter);
		Ext.getCmp('btnresetbuku').hide();		
        Ext.getCmp('btnsubmitbuku').setText('Update');
        winaddbuku.setTitle('Edit Form');
        Ext.getCmp('id_formaddbuku').getForm().load({
            url: '<?= site_url("buku/get_row") ?>',
            params: {
                id: id_buku,
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
        winaddbuku.show();
	}
	
    function deletebuku(){		
        var sm = buku.getSelectionModel();
        var sel = sm.getSelections();
        if (sel.length > 0) {
            Ext.Msg.show({
                title: 'Confirm',
                msg: 'Are you sure delete selected row ?',
                buttons: Ext.Msg.YESNO,
                fn: function(btn){
                    if (btn == 'yes') {
                    
                        var data = sel[i].get('kd_buku');
                        
                        Ext.Ajax.request({
                            url: '<?= site_url("buku/delete_row") ?>',
                            method: 'POST',
                            params: {
                                kd_buku: data
                            },
							callback:function(opt,success,responseObj){
								var de = Ext.util.JSON.decode(responseObj.responseText);
								if(de.success==true){
									strbuku.reload();
	                                strbuku.load({
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
