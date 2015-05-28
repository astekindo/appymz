<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">    
    
    Ext.ns('jabatanform');
    var strcmbdivisi = new Ext.data.Store({
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

var strcmbjabatan = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: [
            'kd_jabatan',
            'kd_parent_jabatan',
            'nama_jabatan',
            'lvl_jabatan',
            'kd_divisi',
            'aktif'
        ],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
    url: '<?= site_url("jabatan/get_rows") ?>',
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
var kodeparent=null;
var kodedivisi=null;

    jabatanform.Form = Ext.extend(Ext.form.FormPanel,{
        border: false,
        closeable: true,
        frame: true,
        labelWidth: 100,
        waitMsg:'Loading...',
        url: '<?= site_url("jabatan/update_row") ?>',
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
            jabatanform.Form.superclass.constructor.call(this, config);
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
                        name: 'kd_jabatan'
                    },
                    {
                        xtype: 'combo',
                        fieldLabel: 'Parent Jabatan <span class="asterix">*</span>',
                        name: 'kd_parent_jabatan',
                        id: 'id_parent_jabatan',
                        anchor: '90%',
                        store: strcmbjabatan,
                        displayField: 'nama_jabatan',    
                        valueField:'kd_jabatan',                    
                        queryMode: 'remote',
                        typeAhead: true,
//                        forceSelection: true,
                        triggerAction: 'all',
//                        selectOnFocus : true,
                        listeners:{
                            select:function(cmb){                                   
                                kodeparent=cmb.getValue();                                
                            }                                                   
                        }
                    },
                    {
                        type: 'textfield',
                        fieldLabel: 'Nama Jabatan <span class="asterix">*</span>',
                        name: 'nama_jabatan',
                        allowBlank: false,
                        id: 'id_nama_jabatan',
                        anchor: '90%',
                        maxLength: 40,
                        style:'text-transform: uppercase'					       
                    
                    },{
                        xtype: 'hidden',
                        name: 'lvl_jabatan'
                    }
                    ,{
                        xtype: 'combo',
                        fieldLabel: 'Divisi <span class="asterix">*</span>',
                        name: 'kd_divisi',
                        id: 'id_kd_divisi',
                        anchor: '90%',
                        allowBlank: false,
                        store: strcmbdivisi,
                        displayField: 'nama_divisi',    
                        valueField:'kd_divisi',                    
                        queryMode: 'local',     
                        typeAhead: true,
//                        forceSelection: true,
                        triggerAction: 'all',
//                        selectOnFocus : true,
                        listeners:{
                            select:function(cmb){                                   
                                kodedivisi=cmb.getValue();                                
                            }                                                   
                        }
                    }
                    ],
                buttons: [{
                        text: 'Submit',
                        id: 'btnsubmitjabatan',
                        formBind: true,
                        scope: this,
                        handler: this.submit
                    }, {
                        text: 'Reset',
                        id: 'btnresetjabatan',
                        scope: this,
                        handler: this.reset
                    }, {
                        text: 'Close',
                        id: 'btnclosejabatan',
                        scope: this,
                        handler: function(){
                            winaddjabatan.hide();
                        }
                    }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            jabatanform.Form.superclass.initComponent.apply(this, arguments);
        },
        onRender: function(){
        
            // call parent
            jabatanform.Form.superclass.onRender.apply(this, arguments);
            
            // set wait message target
            this.getForm().waitMsgTarget = this.getEl();
            
            // loads form after initial layout
            // this.on('afterlayout', this.onLoadClick, this, {single:true});
        
        },
        reset: function(){
            this.getForm().reset();
        },
        submit: function(){
            kodeparent=this.getForm().findField('kd_parent_jabatan').getValue();
            kodedivisi=this.getForm().findField('kd_divisi').getValue();
            this.getForm().submit({
                url: this.url,
                scope: this,
                success: this.onSuccess,
                failure: this.onFailure,
                params: {
                    cmd: 'save' ,
                    kdparent:kodeparent,
                    kddivisi:kodedivisi
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
            
            
            strjabatan.reload();
            Ext.getCmp('id_formaddjabatan').getForm().reset();
            winaddjabatan.hide();
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
    Ext.reg('formaddjabatan', jabatanform.Form);
    
    var winaddjabatan = new Ext.Window({
        id: 'id_winaddjabatan',
        closeAction: 'hide',
        width: 450,
        height: 350,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_formaddjabatan',
            xtype: 'formaddjabatan'
        },
        onHide: function(){
            Ext.getCmp('id_formaddjabatan').getForm().reset();
        },
        onShow: function(){
            strcmbdivisi.reload();
            strcmbjabatan.reload();
            
        }
    });
    var strjabatan = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: [
            'kd_jabatan',
            'kd_parent_jabatan',
            'nama_jabatan',
            'lvl_jabatan',
            'kd_divisi',
            'aktif'
        ],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
    url: '<?= site_url("jabatan/get_rows") ?>',
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
    


    
    var searchjabatan = new Ext.app.SearchField({
        store: strjabatan,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchjabatan'
    });
    
    var tbjabatan = new Ext.Toolbar({
        items: [{
            text: 'Add',
            icon: BASE_ICONS + 'add.png',
            onClick: function(){				
                Ext.getCmp('btnresetjabatan').show();
                Ext.getCmp('btnsubmitjabatan').setText('Submit');
                
                winaddjabatan.setTitle('Add Form');
                winaddjabatan.show();                
            }            
        }, '-', searchjabatan]
    });
    
    var cbGridjabatan = new Ext.grid.CheckboxSelectionModel();
    var actionjabatan = new Ext.ux.grid.RowActions({
		header :'Edit',
		autoWidth: false,
		width: 30,
	    actions:[{iconCls: 'icon-edit-record', qtip: 'Edit'}],
	    widthIntercept: Ext.isSafari ? 4 : 2
	});	
	var actionjabatandel = new Ext.ux.grid.RowActions({
		header: 'Delete',
		autoWidth: false,
		width: 40,
	    actions:[{iconCls: 'icon-delete-record', qtip: 'Delete'}],
	    widthIntercept: Ext.isSafari ? 4 : 2
	});
        
    actionjabatan.on('action', function(grid, record, action, row, col) {
		var kd_jabatan = record.get('kd_jabatan');
		switch(action) {
			case 'icon-edit-record':	        	
				editjabatan(kd_jabatan);
	        	break;
	      	case 'icon-delete-record':
				Ext.Msg.show({
	                title: 'Confirm',
	                msg: 'Are you sure delete selected row ?',
	                buttons: Ext.Msg.YESNO,
	                fn: function(btn){
	                    if (btn == 'yes') {
	                        Ext.Ajax.request({
	                            url: '<?= site_url("jabatan/delete_row") ?>',
	                            method: 'POST',
	                            params: {
	                                kd_jabatan: kd_jabatan
	                            },
								callback:function(opt,success,responseObj){
									var de = Ext.util.JSON.decode(responseObj.responseText);
									if(de.success==true){
										strjabatan.reload();
		                                strjabatan.load({
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

    var jabatan = new Ext.grid.EditorGridPanel({
        //id: 'id-jabatan-gridpanel',
        id: 'jabatan',
		frame: true,
        border: true,
        stripeRows: true,
        sm: cbGridjabatan,
        store: strjabatan,
		closable:true,
        loadMask: true,
        style: 'margin:0 auto;',
        height: 450,
        columns: [actionjabatan,actionjabatandel,{
            header: "Kode",
            dataIndex: 'kd_jabatan',
            sortable: true,
            width: 50
        },{
            header: "Parent",
            dataIndex: 'kd_parent_jabatan',
            sortable: true,
            width: 50
        },{
            header: "Nama Jabatan",
            dataIndex: 'nama_jabatan',
            sortable: true,
            width: 100
        },{
            header: "Level",
            dataIndex: 'lvl_jabatan',
            sortable: true,
            width: 100
        },{
            header: "Divisi",
            dataIndex: 'kd_divisi',
            sortable: true,
            width: 100
        },{
            header: "Aktif",
            dataIndex: 'aktif',
            sortable: true,
            width: 100
        }],
	plugins: [actionjabatan,actionjabatandel],
        listeners:{
            'rowdblclick': function(){
				
                var sm = jabatan.getSelectionModel();
                
                var sel = sm.getSelections();
                
                if (sel.length > 0) {
                    Ext.getCmp('btnresetjabatan').hide();
                    Ext.getCmp('btnsubmitjabatan').setText('Update');
                    winaddjabatan.setTitle('Edit Form');
                    Ext.getCmp('id_formaddjabatan').getForm().load({
                        url: '<?= site_url("jabatan/get_row") ?>',
                        params: {
                            id: sel[0].get('kd_jabatan'),
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
                    winaddjabatan.show();
                }
                 
            }
         },
        tbar: tbjabatan,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strjabatan,
            displayInfo: true
        })
                
    });
	/**
	var jabatanpanel = new Ext.FormPanel({
	 	id: 'jabatan',
		border: false,
        frame: false,
		autoScroll:true,	
        items: [jabatan]
	});
    **/
function editjabatan(kd_jabatan){
		Ext.getCmp('btnresetjabatan').hide();
        Ext.getCmp('btnsubmitjabatan').setText('Update');
        winaddjabatan.setTitle('Edit Form');
        Ext.getCmp('id_formaddjabatan').getForm().load({
            url: '<?= site_url("jabatan/get_row") ?>',
            params: {
                id: kd_jabatan,
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
        winaddjabatan.show();
	}
  function deletejabatan(){		
        var sm = jabatan.getSelectionModel();
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
                            data = data + sel[i].get('kd_jabatan') + ';';
                        }
                        
                        Ext.Ajax.request({
                            url: '<?= site_url("jabatan/delete_rows") ?>',
                            method: 'POST',
                            params: {
                                postdata: data
                            },
							callback:function(opt,success,responseObj){
								var de = Ext.util.JSON.decode(responseObj.responseText);
								if(de.success==true){
									strjabatan.reload();
	                                strjabatan.load({
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