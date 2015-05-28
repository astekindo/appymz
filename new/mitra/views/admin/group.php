<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">    
    /* START FORM */
	
	 
    Ext.ns('groupform');
    groupform.Form = Ext.extend(Ext.form.FormPanel, {
    
        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 100,
        url: '<?= site_url("group/update_row") ?>',
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
            groupform.Form.superclass.constructor.call(this, config);
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
                    name: 'kd_group'
                },{
                    type: 'textfield',
                    fieldLabel: 'Nama Group <span class="asterix">*</span>',
                    name: 'nama_group',
                    allowBlank: false,
                    id: 'id_nama_group',
                    maxLength: 40,
                    anchor: '90%'                
                },{
                    xtype: 'textarea',
                    fieldLabel: 'Deskripsi <span class="asterix">*</span>',
                    name: 'deskripsi',
                    allowBlank: false,
                    id: 'id_deskripsi',
                    maxLength: 40,
                    anchor: '90%'                
                },{
					xtype: 'radiogroup',
					cls: 'x-check-group-alt',            
					fieldLabel: 'Aktif <span class="asterix">*</span>',           
					name: 'aktif',
                    anchor: '90%',     			
					allowBlank:false,  
					items: [{                    
						boxLabel: 'Ya',                     
						name: 'aktif',                     
						inputValue: '1',                     
						id: 'id_aktifY', 					
					}, {                     
						boxLabel: 'Tidak',                     
						name: 'aktif',                     
						inputValue: '0',                    
						id: 'id_aktifT'                 
					}]             
				}],
                buttons: [{
                    text: 'Submit',
                    id: 'btnsubmitgroup',
                    formBind: true,
                    scope: this,
                    handler: this.submit
                }, {
                    text: 'Reset',
                    id: 'btnresetgroup',
                    scope: this,
                    handler: this.reset
                }, {
                    text: 'Close',
                    id: 'btnClose',
                    scope: this,
                    handler: function(){
                        winaddgroup.hide();
                    }
                }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            
            // call parent
            groupform.Form.superclass.initComponent.apply(this, arguments);
            
        } // eo function initComponent	
        ,
        onRender: function(){
        
            // call parent
            groupform.Form.superclass.onRender.apply(this, arguments);
            
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
            
            
            strgroup.reload();
            Ext.getCmp('id_formaddgroup').getForm().reset();
            winaddgroup.hide();
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
    Ext.reg('formaddgroup', groupform.Form);
    
    var winaddgroup = new Ext.Window({
        id: 'id_winaddgroup',
        closeAction: 'hide',
        width: 450,
        height: 350,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_formaddgroup',
            xtype: 'formaddgroup'
        },
        onHide: function(){
            Ext.getCmp('id_formaddgroup').getForm().reset();
        }
    });
    
	/* START GRID */    
	
	// data store
	var strgroup = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [ 
				  'kd_group',
				  'nama_group',
				  'deskripsi'
			],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("group/get_rows") ?>',
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
    var searchgroup = new Ext.app.SearchField({
        store: strgroup,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchgroup'
    });
    
	// top toolbar
    var tbgroup = new Ext.Toolbar({
        items: [{
            text: 'Add',
            icon: BASE_ICONS + 'add.png',
            onClick: function(){	
				//strcbtypeP.load(); 				Ext.getCmp('id_cbtypeP').setDisabled(false);			 				Ext.getCmp('id_cbtypeP').setValue('');			
                Ext.getCmp('btnresetgroup').show();
                Ext.getCmp('btnsubmitgroup').setText('Submit');
                winaddgroup.setTitle('Add Form');
                winaddgroup.show();                
            }            
        }, '-', searchgroup]
    });
	
	// checkbox grid
    var cbGrid = new Ext.grid.CheckboxSelectionModel();
    
	// row actions
	var actiongroup = new Ext.ux.grid.RowActions({
        header:'Edit',
		autoWidth: false,
		width: 30,
	    actions:[
	      {iconCls: 'icon-edit-record', qtip: 'Edit'}],
	    widthIntercept: Ext.isSafari ? 4 : 2
	});	
	var actiongroupdel = new Ext.ux.grid.RowActions({
        header:'Delete',
		autoWidth: false,
		width: 40,
	    actions:[	      
	      {iconCls: 'icon-delete-record', qtip: 'Delete'}],
	    widthIntercept: Ext.isSafari ? 4 : 2
	});	
	
	actiongroup.on('action', function(grid, record, action, row, col) {
		var id_group = record.get('kd_group');
		if (action=='icon-edit-record'){
		editgroup(id_group);
		}
	});  
	
  	actiongroupdel.on('action', function(grid, record, action, row, col) {
		var id_group = record.get('kd_group');
		if (action=='icon-delete-record'){
		Ext.Msg.show({
			title: 'Confirm',
			msg: 'Are you sure delete selected row ?',
			buttons: Ext.Msg.YESNO,
			fn: function(btn){
				if (btn == 'yes') {
					Ext.Ajax.request({
						url: '<?= site_url("group/delete_row") ?>',
						method: 'POST',
						params: {
							kd_group: id_group
						},
						callback:function(opt,success,responseObj){
							var de = Ext.util.JSON.decode(responseObj.responseText);
							if(de.success==true){
								strgroup.reload();
								strgroup.load({
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
    var group = new Ext.grid.EditorGridPanel({
        //id: 'id-group-panel',
        id: 'group',
		frame: true,
        border: true,
        stripeRows: true,
        sm: cbGrid,
        store: strgroup,
        loadMask: true,
        style: 'margin:0 auto;',
        height: 450,
        columns: [actiongroup,actiongroupdel,{
            header: "Kode Group",
            dataIndex: 'kd_group',
            sortable: true,
            width: 90
        },{
            header: "Nama Group",
            dataIndex: 'nama_group',
            sortable: true,
            width: 200
        },{
            header: "Deskripsi",
            dataIndex: 'deskripsi',
            sortable: true,
            width: 200
        }],
		plugins: [actiongroup, actiongroupdel],
        listeners: {
            'rowdblclick': function(){				
                var sm = group.getSelectionModel();                
                var sel = sm.getSelections();                
                if (sel.length > 0) {
					editgroup(sel[0].get('kd_group'),sel[0].get('nama_group'));                    
                }                 
            }          
        },
        tbar: tbgroup,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgroup,
            displayInfo: true
        })
    });
    /**
	var grouppanel = new Ext.FormPanel({
	 	id: 'group',
		border: false,
        frame: false,
		autoScroll:true,	
        items: [group]
	});
	**/
	function editgroup(id_group,nama_group){
		//strcbtypeP.load();
		//Ext.getCmp('id_cbtypeP').setDisabled(true);
		//Ext.getCmp('id_cbtypeP').setValue(type_parameter);
		Ext.getCmp('btnresetgroup').hide();		
        Ext.getCmp('btnsubmitgroup').setText('Update');
        winaddgroup.setTitle('Edit Form');
        Ext.getCmp('id_formaddgroup').getForm().load({
            url: '<?= site_url("group/get_row") ?>',
            params: {
                id: id_group,
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
        winaddgroup.show();
	}
	
    function deletegroup(){		
        var sm = group.getSelectionModel();
        var sel = sm.getSelections();
        if (sel.length > 0) {
            Ext.Msg.show({
                title: 'Confirm',
                msg: 'Are you sure delete selected row ?',
                buttons: Ext.Msg.YESNO,
                fn: function(btn){
                    if (btn == 'yes') {
                    
                        var data = sel[i].get('kd_group');
                        
                        Ext.Ajax.request({
                            url: '<?= site_url("group/delete_row") ?>',
                            method: 'POST',
                            params: {
                                kd_group: data
                            },
							callback:function(opt,success,responseObj){
								var de = Ext.util.JSON.decode(responseObj.responseText);
								if(de.success==true){
									strgroup.reload();
	                                strgroup.load({
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
