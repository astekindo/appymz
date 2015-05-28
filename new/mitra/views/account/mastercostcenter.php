<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">  
    var cbgrid_mcc = new Ext.grid.CheckColumn({
        header:'Select Akun',      
        id:'id_selectakun_mcc',       
        dataIndex: 'sel',             
        width: 55      
    });	
var colmodel_mcc=new Ext.grid.ColumnModel({
        columns:[
            cbgrid_mcc,            
            {
                header:'Kode Akun',                          
                dataIndex: 'kd_akun',
                width: 100             
                
            },{header:'Nama Akun',             
                dataIndex: 'nama_akun',
                width: 200                
            }            
        ]
        
    });
    var str_akun_mcc=createStoreData([
                {name: 'sel', allowBlank: false, type: 'bool'},
                {name: 'kd_akun', allowBlank: false, type: 'string'},
                {name: 'nama_akun', allowBlank: false, type: 'string'}
            ], '<?= site_url("account_mcostcenter/get_rows_akun_edit") ?>');
  var gridakun_mcc = new Ext.grid.GridPanel({
        store: str_akun_mcc,
        stripeRows: true,
        height: 400,
        frame: true,
        border:true,
        plugins: [cbgrid_mcc],
        cm:colmodel_mcc
    });
    Ext.ns('master_costcenterform');
    master_costcenterform.Form = Ext.extend(Ext.form.FormPanel,{
        border: false,
        closeable: true,
        frame: true,
        labelWidth: 100,
        waitMsg:'Loading...',
        url: '<?= site_url("account_mcostcenter/update_row") ?>',
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
            master_costcenterform.Form.superclass.constructor.call(this, config);
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
						fieldLabel: 'Kode CostCenter',
						name: 'kd_costcenter',   
						id: 'mcc_kd_costcenter',     
                                                hiddenName:'kd_costcenter',
						anchor: '90%',
						value: ''}
					,
                    {
						xtype: 'textfield',
						fieldLabel: 'Nama CostCenter',
						name: 'nama_costcenter',   
						id: 'mcc_nama_costcenter',                
						anchor: '90%',
						value: '',
                                                allowBlank:false
					},
                                        gridakun_mcc
                                        ],
                buttons: [{
                        text: 'Submit',
                        id: 'btnsubmitmaster_costcenter',
                        formBind: true,
                        scope: this,
                        handler: this.submit
                    }, {
                        text: 'Reset',
                        id: 'btnresetmaster_costcenter',
                        scope: this,
                        handler: this.reset
                    }, {
                        text: 'Close',
                        id: 'btnclosemaster_costcenter',
                        scope: this,
                        handler: function(){
                            winaddmaster_costcenter.hide();
                        }
                    }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            master_costcenterform.Form.superclass.initComponent.apply(this, arguments);
        },
        onRender: function(){
        
            // call parent
            master_costcenterform.Form.superclass.onRender.apply(this, arguments);
            
            // set wait message target
            this.getForm().waitMsgTarget = this.getEl();
            
            // loads form after initial layout
            // this.on('afterlayout', this.onLoadClick, this, {single:true});
        
        },
        reset: function(){
            gridakun_mcc.getStore().reload();
            this.getForm().reset();
        },
        submit: function(){
            var cmd='';
            if(Ext.getCmp('btnsubmitmaster_costcenter').getText()==='Submit'){
                cmd='insert';
            }else{
                cmd='update';
            }
            var arr_akun= new Array();
            str_akun_mcc.each(function(node){    
                if (node.data.sel){                            
                    arr_akun.push(node.data);                 
                }                       
                            
                                     
            });	
            
            this.getForm().submit({
                url: this.url,
                scope: this,
                success: this.onSuccess,
                failure: this.onFailure,
                params: {
                    cmd: cmd
                    ,data:Ext.util.JSON.encode(arr_akun)
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
            
            
            strmaster_costcenter.reload();
            Ext.getCmp('id_formaddmaster_costcenter').getForm().reset();
            winaddmaster_costcenter.hide();
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
    Ext.reg('formaddmaster_costcenter', master_costcenterform.Form);
    
    var winaddmaster_costcenter = new Ext.Window({
        id: 'id_winaddmaster_costcenter',
        closeAction: 'hide',
        width: 450,
        height: 520,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_formaddmaster_costcenter',
            xtype: 'formaddmaster_costcenter'
        },
        onHide: function(){
            Ext.getCmp('id_formaddmaster_costcenter').getForm().reset();
        },
        onShow: function(){
//            strcbparentakun.reload();
            
        }
    });
    var strmaster_costcenter = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: [
            'kd_costcenter',            
            'nama_costcenter'            
        ],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
    url: '<?= site_url("account_mcostcenter/get_rows") ?>',
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
    


    
    var searchmaster_costcenter = new Ext.app.SearchField({
        store: strmaster_costcenter,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchmaster_costcenter'
    });
    
    var tbmaster_costcenter = new Ext.Toolbar({
        items: [{
            text: 'Add',
            icon: BASE_ICONS + 'add.png',
            onClick: function(){
//                strcbparentakun.reload();
                Ext.getCmp('btnresetmaster_costcenter').show();
                Ext.getCmp('btnsubmitmaster_costcenter').setText('Submit');
                str_akun_mcc.load();
                winaddmaster_costcenter.setTitle('Add Form');
                winaddmaster_costcenter.show();                
            }            
        }, '-', searchmaster_costcenter]
    });
    
    var cbGridmaster_costcenter = new Ext.grid.CheckboxSelectionModel();
    var actionmaster_costcenter = new Ext.ux.grid.RowActions({
		header :'Edit',
		autoWidth: false,
		width: 30,
	    actions:[{iconCls: 'icon-edit-record', qtip: 'Edit'}],
	    widthIntercept: Ext.isSafari ? 4 : 2
	});	
	var actionmaster_costcenterdel = new Ext.ux.grid.RowActions({
		header: 'Delete',
		autoWidth: false,
		width: 40,
	    actions:[{iconCls: 'icon-delete-record', qtip: 'Delete'}],
	    widthIntercept: Ext.isSafari ? 4 : 2
	});
        
    actionmaster_costcenter.on('action', function(grid, record, action, row, col) {
		var kd_costcenter = record.get('kd_costcenter');
                console.log(kd_costcenter);
		switch(action) {
			case 'icon-edit-record':	        	
				editmaster_costcenter(kd_costcenter);
	        	break;
	      	case 'icon-delete-record':
				Ext.Msg.show({
	                title: 'Confirm',
	                msg: 'Are you sure delete selected row ?',
	                buttons: Ext.Msg.YESNO,
	                fn: function(btn){
	                    if (btn == 'yes') {
	                        Ext.Ajax.request({
	                            url: '<?= site_url("account_mcostcenter/delete_row") ?>',
	                            method: 'POST',
	                            params: {
	                                kd_costcenter: kd_costcenter
	                            },
								callback:function(opt,success,responseObj){
									var de = Ext.util.JSON.decode(responseObj.responseText);
									if(de.success==true){
										strmaster_costcenter.reload();
		                                strmaster_costcenter.load({
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

   
    var master_costcenter = new Ext.grid.EditorGridPanel({
        id: 'id-master_costcenter-gridpanel',
        frame: true,
        border: true,
        stripeRows: true,
        sm: cbGridmaster_costcenter,
        store: strmaster_costcenter,
	closable:true,
        loadMask: true,
        style: 'margin:0 auto;',
        height: 280,
        columns: [actionmaster_costcenter,actionmaster_costcenterdel,{
            header: 'Kode',
            dataIndex: 'kd_costcenter',
            width: 90,
            sortable: true            
        },{
            header: 'Nama costcenter',
            dataIndex: 'nama_costcenter',
            width: 250
        }],
	plugins: [actionmaster_costcenter,actionmaster_costcenterdel],
        listeners:{
            'rowclick': function ( mGrid, rowIndex, e ) {              
                var sm = mGrid.getSelectionModel();                
                var sel = sm.getSelections(); 				             
                Ext.getCmp('id_master_costcenter_akun').getStore().reload({params:{query:sel[0].get('kd_costcenter')}});
            },
            'rowdblclick': function(){
				
                var sm = master_costcenter.getSelectionModel();
                
                var sel = sm.getSelections();
                
                if (sel.length > 0) {
                    editmaster_costcenter(sel[0].get('kd_costcenter'));
//                    Ext.getCmp('btnresetmaster_costcenter').hide();
//                    Ext.getCmp('btnsubmitmaster_costcenter').setText('Update');
//                    winaddmaster_costcenter.setTitle('Edit Form');
//                    Ext.getCmp('id_formaddmaster_costcenter').getForm().load({
//                        url: '<?= site_url("account_mcostcenter/get_row") ?>',
//                        params: {
//                            id: sel[0].get('kd_costcenter'),
//                            cmd: 'get'
//                        },                  
//                        failure: function(form, action){
//							var de = Ext.util.JSON.decode(action.response.responseText);
//                            Ext.Msg.show({
//                                    title: 'Error',
//                                    msg: de.errMsg,
//                                    modal: true,
//                                    icon: Ext.Msg.ERROR,
//                                    buttons: Ext.Msg.OK,
//                                    fn: function(btn){
//                                        if (btn == 'ok' && de.errMsg == 'Session Expired') {
//                                            window.location = '<?= site_url("auth/login") ?>';
//                                        }
//                                    }
//                                });
//                        }
//                    });
//                    winaddmaster_costcenter.show();
                }
                 
            }
         },
        tbar: tbmaster_costcenter,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strmaster_costcenter,
            displayInfo: true
        })
                
    });
    
    
    
var strmaster_costcenter_akun=createStoreData([
                'kd_costcenter',
                'kd_akun',
                'nama'
            ], '<?= site_url("account_mcostcenter/get_rows_akun") ?>');



var action_master_costcenter_akun_del = new Ext.ux.grid.RowActions({
        header:'Delete',
        autoWidth: false,
        width: 40,
        actions:[	      
            {iconCls: 'icon-delete-record', qtip: 'Delete'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });	        
    
action_master_costcenter_akun_del.on('action', function(grid, record, action, row, col){
    var kdcc=record.get('kd_costcenter');
        var kdakun=record.get('kd_akun');
        if (action=='icon-delete-record'){
            Ext.Msg.show({
                title: 'Confirm',
                msg: 'Are you sure delete selected row ?',
                buttons: Ext.Msg.YESNO,
                fn: function(btn){
                    if (btn == 'yes') {
                        Ext.Ajax.request({
                            url: '<?= site_url("account_mcostcenter/delete_row_akun") ?>',
                            method: 'POST',
                            params: {
                                kd_costcenter: kdcc,
                                kd_akun:kdakun
                            },
                            callback:function(opt,success,responseObj){
                                var de = Ext.util.JSON.decode(responseObj.responseText);
                                if(de.success==true){
                                    strmaster_costcenter.reload();
                                    strmaster_costcenter_akun.reload();
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
    

 var master_costcenter_akun = new Ext.grid.GridPanel({
        id: 'id_master_costcenter_akun',
        store: strmaster_costcenter_akun,
        stripeRows: true,
        height: 300,		
        border:true,
        frame:true,
        plugins:[action_master_costcenter_akun_del],
        columns: [action_master_costcenter_akun_del,
            {            
                header: 'Kode',
                dataIndex: 'kd_costcenter',
                width: 80
            },{            
                header: 'Kode Akun',
                dataIndex: 'kd_akun',
                width: 80
            },{            
                header: 'Nama Akun',
                dataIndex: 'nama',
                width: 200
                
            }
        ],bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strmaster_costcenter_akun,
            displayInfo: true
        })
    });
    
	var master_costcenterpanel = new Ext.FormPanel({
	 	id: 'mastercostcenter',
		border: false,
        frame: false,
		autoScroll:true,	
        items: [master_costcenter,master_costcenter_akun]
	});

    
function editmaster_costcenter(kd_costcenter){
        str_akun_mcc.load({params:{query:kd_costcenter}});
		Ext.getCmp('btnresetmaster_costcenter').hide();
        Ext.getCmp('btnsubmitmaster_costcenter').setText('Update');
        winaddmaster_costcenter.setTitle('Edit Form');
        Ext.getCmp('id_formaddmaster_costcenter').getForm().load({
            url: '<?= site_url("account_mcostcenter/get_row") ?>',
            params: {
                id: kd_costcenter,
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
        winaddmaster_costcenter.show();
	}
  function deletemaster_costcenter(){		
        var sm = master_costcenter.getSelectionModel();
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
                            data = data + sel[i].get('kd_costcenter') + ';';
                        }
                        
                        Ext.Ajax.request({
                            url: '<?= site_url("account_mcostcenter/delete_rows") ?>',
                            method: 'POST',
                            params: {
                                postdata: data
                            },
							callback:function(opt,success,responseObj){
								var de = Ext.util.JSON.decode(responseObj.responseText);
								if(de.success==true){
									strmaster_costcenter.reload();
	                                strmaster_costcenter.load({
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
