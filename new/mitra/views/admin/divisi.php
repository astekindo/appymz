<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">    
    
    Ext.ns('divisiform');
    divisiform.Form = Ext.extend(Ext.form.FormPanel,{
        border: false,
        closeable: true,
        frame: true,
        labelWidth: 100,
        waitMsg:'Loading...',
        url: '<?= site_url("divisi/update_row") ?>',
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
            divisiform.Form.superclass.constructor.call(this, config);
        },
        initComponent: function(){
            var config = {
                defaultType: 'textfield',
                defaults: { labelSeparator: ''},
                monitorValid: true,
                autoScroll: false // ,buttonAlign:'right'
                ,
                items: [{
                        xtype: 'hidden',
                        name: 'kd_divisi'
                    },{
                        type: 'textfield',
                        fieldLabel: 'Nama Divisi <span class="asterix">*</span>',
                        name: 'nama_divisi',
                        allowBlank: false,
                        id: 'id_nama_divisi',
                        anchor: '90%',
                        maxLength: 40,
                        style:'text-transform: uppercase'					       
                    },{
                        type: 'textfield',
                        fieldLabel: 'Kepala Divisi <span class="asterix">*</span>',
                        name: 'kepala_divisi',
                        allowBlank: false,
                        id: 'id_kepala_divisi',
                        anchor: '90%',
                        maxLength: 40,
                        style:'text-transform: uppercase'					       
                    }
                    ],
                buttons: [{
                        text: 'Submit',
                        id: 'btnsubmitdivisi',
                        formBind: true,
                        scope: this,
                        handler: this.submit
                    }, {
                        text: 'Reset',
                        id: 'btnresetdivisi',
                        scope: this,
                        handler: this.reset
                    }, {
                        text: 'Close',
                        id: 'btnclosedivisi',
                        scope: this,
                        handler: function(){
                            winadddivisi.hide();
                        }
                    }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            divisiform.Form.superclass.initComponent.apply(this, arguments);
        },
        onRender: function(){
        
            // call parent
            divisiform.Form.superclass.onRender.apply(this, arguments);
            
            // set wait message target
            this.getForm().waitMsgTarget = this.getEl();
            
            // loads form after initial layout
            // this.on('afterlayout', this.onLoadClick, this, {single:true});
        
        },
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
            
            
            strdivisi.reload();
            Ext.getCmp('id_formadddivisi').getForm().reset();
            winadddivisi.hide();
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
    });
    Ext.reg('formadddivisi', divisiform.Form);
    
    var winadddivisi = new Ext.Window({
        id: 'id_winadddivisi',
        closeAction: 'hide',
        width: 450,
        height: 350,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_formadddivisi',
            xtype: 'formadddivisi'
        },
        onHide: function(){
            Ext.getCmp('id_formadddivisi').getForm().reset();
        }
    });
    var strdivisi = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: [
            'kd_divisi',
            'nama_divisi',
            'kepala_divisi'
        ],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
    url: '<?= site_url("divisi/get_rows") ?>',
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


    
    var searchdivisi = new Ext.app.SearchField({
        store: strdivisi,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchdivisi'
    });
    
    var tbdivisi = new Ext.Toolbar({
        items: [{
            text: 'Add',
            icon: BASE_ICONS + 'add.png',
            onClick: function(){				
                Ext.getCmp('btnresetdivisi').show();
                Ext.getCmp('btnsubmitdivisi').setText('Submit');
                winadddivisi.setTitle('Add Form');
                winadddivisi.show();                
            }            
        }, '-', searchdivisi]
    });
    
    var cbGriddivisi = new Ext.grid.CheckboxSelectionModel();
    var actiondivisi = new Ext.ux.grid.RowActions({
		header :'Edit',
		autoWidth: false,
		width: 30,
	    actions:[{iconCls: 'icon-edit-record', qtip: 'Edit'}],
	    widthIntercept: Ext.isSafari ? 4 : 2
	});	
	var actiondivisidel = new Ext.ux.grid.RowActions({
		header: 'Delete',
		autoWidth: false,
		width: 40,
	    actions:[{iconCls: 'icon-delete-record', qtip: 'Delete'}],
	    widthIntercept: Ext.isSafari ? 4 : 2
	});
        
    actiondivisi.on('action', function(grid, record, action, row, col) {
		var kd_divisi = record.get('kd_divisi');
		switch(action) {
			case 'icon-edit-record':	        	
				editdivisi(kd_divisi);
	        	break;
	      	case 'icon-delete-record':
				Ext.Msg.show({
	                title: 'Confirm',
	                msg: 'Are you sure delete selected row ?',
	                buttons: Ext.Msg.YESNO,
	                fn: function(btn){
	                    if (btn == 'yes') {
	                        Ext.Ajax.request({
	                            url: '<?= site_url("divisi/delete_row") ?>',
	                            method: 'POST',
	                            params: {
	                                kd_divisi: kd_divisi
	                            },
								callback:function(opt,success,responseObj){
									var de = Ext.util.JSON.decode(responseObj.responseText);
									if(de.success==true){
										strdivisi.reload();
		                                strdivisi.load({
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

    var divisi = new Ext.grid.EditorGridPanel({
        //id: 'id-divisi-gridpanel',
        id: 'divisi',
		frame: true,
        border: true,
        stripeRows: true,
        sm: cbGriddivisi,
        store: strdivisi,
	closable:true,
        loadMask: true,
        style: 'margin:0 auto;',
        height: 450,
        columns: [actiondivisi,actiondivisidel,{
            header: "Kode",
            dataIndex: 'kd_divisi',
            sortable: true,
            width: 50
        },{
            header: "Nama Divisi",
            dataIndex: 'nama_divisi',
            sortable: true,
            width: 100
        },{
            header: "Kepala Divisi",
            dataIndex: 'kepala_divisi',
            sortable: true,
            width: 100        
        }],
	plugins: [actiondivisi,actiondivisidel],
        listeners:{
            'rowdblclick': function(){
				
                var sm = divisi.getSelectionModel();
                
                var sel = sm.getSelections();
                
                if (sel.length > 0) {
                    Ext.getCmp('btnresetdivisi').hide();
                    Ext.getCmp('btnsubmitdivisi').setText('Update');
                    winadddivisi.setTitle('Edit Form');
                    Ext.getCmp('id_formadddivisi').getForm().load({
                        url: '<?= site_url("divisi/get_row") ?>',
                        params: {
                            id: sel[0].get('kd_divisi'),
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
                    winadddivisi.show();
                }
                 
            }
          
        },
        tbar: tbdivisi,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strdivisi,
            displayInfo: true
        })
                
    });
    /**
	var divisipanel = new Ext.FormPanel({
	 	id: 'divisi',
		border: false,
        frame: false,
		autoScroll:true,	
        items: [divisi]
	});
	**/
    function editdivisi(kd_divisi){
		Ext.getCmp('btnresetdivisi').hide();
        Ext.getCmp('btnsubmitdivisi').setText('Update');
        winadddivisi.setTitle('Edit Form');
        Ext.getCmp('id_formadddivisi').getForm().load({
            url: '<?= site_url("divisi/get_row") ?>',
            params: {
                id: kd_divisi,
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
        winadddivisi.show();
	}
    function deletedivisi(){		
        var sm = divisi.getSelectionModel();
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
                            data = data + sel[i].get('kd_divisi') + ';';
                        }
                        
                        Ext.Ajax.request({
                            url: '<?= site_url("divisi/delete_rows") ?>',
                            method: 'POST',
                            params: {
                                postdata: data
                            },
							callback:function(opt,success,responseObj){
								var de = Ext.util.JSON.decode(responseObj.responseText);
								if(de.success==true){
									strdivisi.reload();
	                                strdivisi.load({
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