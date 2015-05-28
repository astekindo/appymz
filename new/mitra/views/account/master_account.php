<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">    
   
    Ext.ns('master_accountform');
    var strcbparentakun = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_akun'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("account_master_account/get_kd_akun") ?>',
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

    var cbparentakun = new Ext.form.ComboBox({
        fieldLabel: 'Parent Akun <span class="asterix">*</span>',
        id: 'macc_parentakun',
        store: strcbparentakun,
        valueField: 'kd_akun',
        displayField: 'kd_akun',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'parent_kd_akun',
        emptyText: 'Parent Akun',
//        listeners: {
//			expand: function(){
//				strcbparentakun.reload();
//			}
//       }
    });

    master_accountform.Form = Ext.extend(Ext.form.FormPanel,{
        border: false,
        closeable: true,
        frame: true,
        labelWidth: 100,
        waitMsg:'Loading...',
        url: '<?= site_url("account_master_account/update_row") ?>',
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
            master_accountform.Form.superclass.constructor.call(this, config);
        },
        initComponent: function(){
            var config = {
                defaultType: 'textfield',
                defaults: { labelSeparator: ''},
                monitorValid: true,
                autoScroll: false // ,buttonAlign:'right'
                ,
                items: [{
						xtype: 'textfield',
						fieldLabel: 'Kode Akun',
						name: 'kd_akun',   
						id: 'macc_kd_akun',                
						anchor: '90%',
						value: ''
					}, cbparentakun,
                    {
						xtype: 'textfield',
						fieldLabel: 'Nama Akun',
						name: 'nama',   
						id: 'macc_nama',                
						anchor: '90%',
						value: ''
					},{
						xtype: 'textarea',
						fieldLabel: 'Deskripsi',
						name: 'deskripsi',          
						id: 'macc_deskripsi',                
						anchor: '90%',
						value: ''
					},{
						fieldLabel: 'Saldo Default <span class="asterix">*</span>',
						xtype: 'radiogroup',
						columnWidth: [.5, .5],name: 'dk',
						allowBlank:false,
						items: [{
							boxLabel: 'Debet',
							name: 'dk',
							inputValue: 'D',
							id: 'macc_debet',
//							checked:true
						}, {
							boxLabel: 'Kredit',
							name: 'dk',
							inputValue: 'K',
							id: 'macc_kredit'
						}]
					},{
						xtype:          'combo',
						fieldLabel:		'Type Akun',
						mode:           'local',
						value:          '',
						triggerAction:  'all',
						forceSelection: true,
						editable:       false,
						name:           'type_akun',
						id:           	'macc_type_akun',
						hiddenName:     'type_akun',
						displayField:   'name',
						valueField:     'value',
						anchor:			'90%',
						store:          new Ext.data.JsonStore({
							fields : ['name', 'value'],
							data   : [
                                                            {name : 'Neraca', value: 'N'},
                                                            {name : 'Pendapatan', value: 'P'},
                                                            {name : 'Biaya/Beban', value: 'B'}                                                            
//								{name : 'Aktiva', value: '1'},
//								{name : 'Passiva', value: '2'},
//								{name : 'Modal', value:'3'},
//                                                                {name : 'Pendapatan', value: '4'},
//								{name : 'Biaya Beban', value:''}
								]
							})
					}
                                        ,{
						xtype: 'checkbox',
						fieldLabel: 'Laba / Rugi',
						name: 'labarugi',          
						id: 'macc_labarugi',                
						anchor: '90%',
						checked: false
					},{
						xtype: 'checkbox',
						fieldLabel: 'Neraca',
						name: 'neraca',          
						id: 'macc_neraca',                
						anchor: '90%',
						checked: false
					},{
						xtype: 'checkbox',
						fieldLabel: 'Header Status',
						name: 'header_status',          
						id: 'macc_header_status',                
						anchor: '90%',
						checked: false
					}
                                        ],
                buttons: [{
                        text: 'Submit',
                        id: 'btnsubmitmaster_account',
                        formBind: true,
                        scope: this,
                        handler: this.submit
                    }, {
                        text: 'Reset',
                        id: 'btnresetmaster_account',
                        scope: this,
                        handler: this.reset
                    }, {
                        text: 'Close',
                        id: 'btnclosemaster_account',
                        scope: this,
                        handler: function(){
                            winaddmaster_account.hide();
                        }
                    }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            master_accountform.Form.superclass.initComponent.apply(this, arguments);
        },
        onRender: function(){
        
            // call parent
            master_accountform.Form.superclass.onRender.apply(this, arguments);
            
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
            
            
            strmaster_account.reload();
            Ext.getCmp('id_formaddmaster_account').getForm().reset();
            winaddmaster_account.hide();
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
    Ext.reg('formaddmaster_account', master_accountform.Form);
    
    var winaddmaster_account = new Ext.Window({
        id: 'id_winaddmaster_account',
        closeAction: 'hide',
        width: 450,
        height: 350,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_formaddmaster_account',
            xtype: 'formaddmaster_account'
        },
        onHide: function(){
            Ext.getCmp('id_formaddmaster_account').getForm().reset();
        },
        onShow: function(){
            strcbparentakun.reload();
            
        }
    });
    var strmaster_account = new Ext.data.Store({
    reader: new Ext.data.JsonReader({ 
        fields: [
            'kd_akun',
            'parent_kd_akun',
            'nama',
            'dk',
            'deskripsi',
            'type_akun',
            {name: 'labarugi', type: 'bool'},
            {name: 'neraca', type: 'bool'},
            {name: 'header_status', type: 'bool'}
            
        ],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
    url: '<?= site_url("account_master_account/get_rows") ?>',
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
    


    
    var searchmaster_account = new Ext.app.SearchField({
        store: strmaster_account,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchmaster_account'
    });
    
//    var expBtnmasteracc=new Ext.ux.Exporter.Button({
//          store: strmaster_account,
//          text     : "ExportToExcel"
//        });

    var tbmaster_account = new Ext.Toolbar({
        items: [{
            text: 'Add',
            icon: BASE_ICONS + 'add.png',
            onClick: function(){
                strcbparentakun.reload();
                Ext.getCmp('btnresetmaster_account').show();
                Ext.getCmp('btnsubmitmaster_account').setText('Submit');
                
                winaddmaster_account.setTitle('Add Form');
                winaddmaster_account.show();                
            }            
        }, '-', searchmaster_account,'-',
        {
            text: 'Export Excel',
            icon: BASE_ICONS + 'application_go.png',
            onClick: function(){
                var xd = toCSV(Ext.getCmp('id-master_account-gridpanel'));
                document.location = 'data:application/vnd.ms-excel;base64,' + Base64.encode(xd);
            }
        }
        
        ]
    });
    
    var cbGridmaster_account = new Ext.grid.CheckboxSelectionModel();
    var actionmaster_account = new Ext.ux.grid.RowActions({
		header :'Edit',
		autoWidth: false,
		width: 30,
	    actions:[{iconCls: 'icon-edit-record', qtip: 'Edit'}],
	    widthIntercept: Ext.isSafari ? 4 : 2
	});	
	var actionmaster_accountdel = new Ext.ux.grid.RowActions({
		header: 'Delete',
		autoWidth: false,
		width: 40,
	    actions:[{iconCls: 'icon-delete-record', qtip: 'Delete'}],
	    widthIntercept: Ext.isSafari ? 4 : 2
	});
        
    actionmaster_account.on('action', function(grid, record, action, row, col) {
		var kd_akun = record.get('kd_akun');
		switch(action) {
			case 'icon-edit-record':	        	
				editmaster_account(kd_akun);
	        	break;
	      	case 'icon-delete-record':
				Ext.Msg.show({
	                title: 'Confirm',
	                msg: 'Are you sure delete selected row ?',
	                buttons: Ext.Msg.YESNO,
	                fn: function(btn){
	                    if (btn == 'yes') {
	                        Ext.Ajax.request({
	                            url: '<?= site_url("account_master_account/delete_row") ?>',
	                            method: 'POST',
	                            params: {
	                                kd_akun: kd_akun
	                            },
								callback:function(opt,success,responseObj){
									var de = Ext.util.JSON.decode(responseObj.responseText);
									if(de.success==true){
										strmaster_account.reload();
		                                strmaster_account.load({
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

    var checklr = new Ext.grid.CheckColumn({
            header: 'Laba / Rugi',
            dataIndex: 'labarugi',
            width: 100
        });
        var checknr = new Ext.grid.CheckColumn({
            header: 'Neraca',
            dataIndex: 'neraca',
            width: 100
        });
         var checkheader = new Ext.grid.CheckColumn({
            header: 'Header Status',
            dataIndex: 'header_status',
            width: 100
        });
    var master_account = new Ext.grid.EditorGridPanel({
        id: 'id-master_account-gridpanel',
        frame: true,
        border: true,
        stripeRows: true,
        sm: cbGridmaster_account,
        store: strmaster_account,
	closable:true,
        loadMask: true,
        style: 'margin:0 auto;',
        height: 450,
        columns: [actionmaster_account,actionmaster_accountdel,{
            header: 'Kode',
            dataIndex: 'kd_akun',
            width: 90,
            sortable: true            
        },{
            header: 'Nama Akun',
            dataIndex: 'nama',
            width: 250
        },{
            header: 'Deskripsi',
            dataIndex: 'deskripsi',
            width: 100
        },{
            header: 'Debet/Kredit',
            dataIndex: 'dk',           
            width: 100,
            sortable: true
		},checklr,checknr,checkheader,
                {
            header: 'Type Akun',
            dataIndex: 'type_akun',           
            width: 100,
            sortable: true
		}],
	plugins: [actionmaster_account,actionmaster_accountdel],
        listeners:{
            'rowdblclick': function(){
				
                var sm = master_account.getSelectionModel();
                
                var sel = sm.getSelections();
                
                if (sel.length > 0) {
                    Ext.getCmp('btnresetmaster_account').hide();
                    Ext.getCmp('btnsubmitmaster_account').setText('Update');
                    winaddmaster_account.setTitle('Edit Form');
                    Ext.getCmp('id_formaddmaster_account').getForm().load({
                        url: '<?= site_url("account_master_account/get_row") ?>',
                        params: {
                            id: sel[0].get('kd_akun'),
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
                    winaddmaster_account.show();
                }
                 
            }
         },
        tbar: tbmaster_account,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strmaster_account,
            displayInfo: true
        })
                
    });
	
//    master_account.getTopToolbar().add(expBtnmasteracc);
	var master_accountpanel = new Ext.FormPanel({
	 	id: 'master_account',
		border: false,
        frame: false,
		autoScroll:true,	
        items: [master_account]
	});
    
function editmaster_account(kd_akun){
		Ext.getCmp('btnresetmaster_account').hide();
        Ext.getCmp('btnsubmitmaster_account').setText('Update');
        winaddmaster_account.setTitle('Edit Form');
        Ext.getCmp('id_formaddmaster_account').getForm().load({
            url: '<?= site_url("account_master_account/get_row") ?>',
            params: {
                id: kd_akun,
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
        winaddmaster_account.show();
	}
  function deletemaster_account(){		
        var sm = master_account.getSelectionModel();
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
                            data = data + sel[i].get('kd_akun') + ';';
                        }
                        
                        Ext.Ajax.request({
                            url: '<?= site_url("account_master_account/delete_rows") ?>',
                            method: 'POST',
                            params: {
                                postdata: data
                            },
							callback:function(opt,success,responseObj){
								var de = Ext.util.JSON.decode(responseObj.responseText);
								if(de.success==true){
									strmaster_account.reload();
	                                strmaster_account.load({
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