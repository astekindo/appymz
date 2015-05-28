<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">    
    /* START FORM */ 

    // combobox kategori1
    var str_mpoint_cbkategori1 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori1', 'nama_kategori1'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("kategori2/get_kategori1") ?>',
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

    var mpoincbkategori1 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 1 <span class="asterix">*</span>',
        id: 'id_mpoint_cbkategori1',
        store: str_mpoint_cbkategori1,
        valueField: 'kd_kategori1',
        displayField: 'nama_kategori1',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_kategori1',
        emptyText: 'Pilih kategori 1',
        listeners: {
            select: function(combo, records) {
                var kd_mpoincbkategori1 = this.getValue();
                mpoincbkategori2.setValue();
                mpoincbkategori2.store.proxy.conn.url = '<?= site_url("kategori3/get_kategori2") ?>/' + kd_mpoincbkategori1;
                mpoincbkategori2.store.reload();
            }
        }
    });
    // combobox kategori2
    var str_mpoint_cbkategori2 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori2', 'nama_kategori2'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("kategori3/get_kategori2") ?>',
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

    var mpoincbkategori2 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 2',
        id: 'id_mpoint_cbkategori2',
        mode: 'local',
        store: str_mpoint_cbkategori2,
        valueField: 'kd_kategori2',
        displayField: 'nama_kategori2',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_kategori2',
        emptyText: 'Pilih kategori 2',
        listeners: {
            select: function(combo, records) {
                var kd_mpoincbkategori1 = mpoincbkategori1.getValue();
                var kd_mpoincbkategori2 = this.getValue();
                mpoincbkategori3.setValue();
                mpoincbkategori3.store.proxy.conn.url = '<?= site_url("master_point/get_kategori3") ?>/' + kd_mpoincbkategori1 +'/'+ kd_mpoincbkategori2;
                mpoincbkategori3.store.reload();
            }
        }
    });
	
    // combobox kategori3
    var str_mpoint_cbkategori3 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori3', 'nama_kategori3'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_point/get_kategori3") ?>',
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

    var mpoincbkategori3 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 3',
        id: 'id_mpoint_cbkategori3',
        mode: 'local',
        store: str_mpoint_cbkategori3,
        valueField: 'kd_kategori3',
        displayField: 'nama_kategori3',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_kategori3',
        emptyText: 'Pilih kategori 3',
        listeners: {
            select: function(combo, records) {
                var kd_mpoincbkategori1 = mpoincbkategori1.getValue();
                var kd_mpoincbkategori2 = mpoincbkategori2.getValue();
                var kd_mpoincbkategori3 = this.getValue();
                mpoincbkategori4.setValue();
                mpoincbkategori4.store.proxy.conn.url = '<?= site_url("master_point/get_kategori4") ?>/' + kd_mpoincbkategori1 +'/'+ kd_mpoincbkategori2 +'/'+kd_mpoincbkategori3;
                mpoincbkategori4.store.reload();
            }
        }
    });
	
	var str_mpoint_cbkategori4 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori4', 'nama_kategori4'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_point/get_kategori4") ?>',
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

    var mpoincbkategori4 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 4',
        id: 'id_mpoint_cbkategori4',
        mode: 'local',
        store: str_mpoint_cbkategori4,
        valueField: 'kd_kategori4',
        displayField: 'nama_kategori4',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_kategori4',
        emptyText: 'Pilih kategori 4'
    });

    Ext.ns('mpointformform');
    mpointformform.Form = Ext.extend(Ext.form.FormPanel, {
    
        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 100,
        url: '<?= site_url("master_point/update_row") ?>',
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
            mpointformform.Form.superclass.constructor.call(this, config);
        },
        initComponent: function(){
        
            // hard coded - cannot be changed from outsid
            var config = {
                defaultType: 'textfield',
                defaults: { labelSeparator: ''},
                monitorValid: true,
                autoScroll: false // ,buttonAlign:'right'
                ,
                items: [mpoincbkategori1,mpoincbkategori2,mpoincbkategori3, mpoincbkategori4
				, {
                    type: 'numberfield',
                    fieldLabel: 'Jumlah Point <span class="asterix">*</span>',
                    name: 'point',
                    allowBlank: false,
                    id: 'id_point',
                    maxLength: 40,
					style:'text-align: right',  
                    anchor: '70%'                
                },{
							xtype: 'datefield',
							fieldLabel: 'Tanggal Awal <span class="asterix">*</span>',
							name: 'tgl_awal',
							id: 'id_tgl_awal',
							format: 'Y-m-d',
							value: new Date().format('m/d/Y'),
							readOnly:true,
							fieldClass:'readonly-input',
							editable: false,
							anchor: '70%'                  
						}, new Ext.form.Checkbox({
						xtype: 'checkbox',
						fieldLabel: 'Status Aktif <span class="asterix">*</span>',
                    	boxLabel:'Ya',
						name:'aktif',
						id:'mpoint_aktif',
						checked : true,
						inputValue: '1',
						autoLoad : true
				})],
                buttons: [{
                    text: 'Submit',
                    id: 'btnsubmitmpointform',
                    formBind: true,
                    scope: this,
                    handler: this.submit
                }, {
                    text: 'Reset',
                    id: 'btnresetmpointform',
                    scope: this,
                    handler: this.reset
                }, {
                    text: 'Close',
                    id: 'btnClosempointform',
                    scope: this,
                    handler: function(){
                        winaddmpointform.hide();
                    }
                }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            
            // call parent
            mpointformform.Form.superclass.initComponent.apply(this, arguments);
            
        } // eo function initComponent  
        ,
        onRender: function(){
        
            // call parent
            mpointformform.Form.superclass.onRender.apply(this, arguments);
            
            // set wait message target
            this.getForm().waitMsgTarget = this.getEl();
            
            // loads form after initial layout
            // this.on('afterlayout', this.onLoadClick, this, {single:true});
        
        } // eo function onRender
        ,
        reset: function(){
            this.getForm().reset();
        },
        submit: function(){ var text = Ext.getCmp('btnsubmitmpointform').getText();
				if (text == 'Update'){
					Ext.Msg.show({
						title: 'Confirm',
						msg: 'Are you sure update selected row ?',
						buttons: Ext.Msg.YESNO,
						fn: function(btn){
							if (btn == 'yes') {
								Ext.getCmp('id_formaddmpointform').getForm().submit({
									url: Ext.getCmp('id_formaddmpointform').url,
									scope: this,
									success: Ext.getCmp('id_formaddmpointform').onSuccess,
									failure: Ext.getCmp('id_formaddmpointform').onFailure,
									params: {
										cmd: 'save'
									},
									waitMsg: 'Saving Data...'
								});
							}
						}
					})
				}else{
					Ext.getCmp('id_formaddmpointform').getForm().submit({
						url: Ext.getCmp('id_formaddmpointform').url,
						scope: this,
						success: Ext.getCmp('id_formaddmpointform').onSuccess,
						failure: Ext.getCmp('id_formaddmpointform').onFailure,
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
            
            
            strmpointform.reload();
            Ext.getCmp('id_formaddmpointform').getForm().reset();
            winaddmpointform.hide();
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
    Ext.reg('formaddmpointform', mpointformform.Form);
    
    var winaddmpointform = new Ext.Window({
        id: 'id_winaddmpointform',
        closeAction: 'hide',
        width: 450,
        height: 350,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_formaddmpointform',
            xtype: 'formaddmpointform'
        },
        onHide: function(){
            Ext.getCmp('id_formaddmpointform').getForm().reset();
        }
    });
    
    /* START GRID */    
    
    // data store
    var strmpointform = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_kategori1', 
                'kd_kategori2', 
                'kd_kategori3', 
                'kd_kategori4',
                'kd_kategori', 
                'nama_kategori',
                'kd_point_setting',
                'tgl_awal',
                'tgl_akhir',
                'point',
				'aktif'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_point/get_rows") ?>',
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
    var searchmpointform = new Ext.app.SearchField({
        store: strmpointform,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchmpointform'
    });
    
    // top toolbar
    var tbmpointform = new Ext.Toolbar({
        items: [{
            text: 'Add',
            icon: BASE_ICONS + 'add.png',
            onClick: function(){            
				Ext.getCmp('id_mpoint_cbkategori1').setValue('');
				Ext.getCmp('id_mpoint_cbkategori2').setValue('');   
				Ext.getCmp('id_mpoint_cbkategori3').setValue('');   
				Ext.getCmp('id_mpoint_cbkategori4').setValue('');   
                Ext.getCmp('btnresetmpointform').show();
                Ext.getCmp('btnsubmitmpointform').setText('Submit');
                winaddmpointform.setTitle('Add Form');
                winaddmpointform.show();                
            }            
        }, '-', searchmpointform]
    });
    
    // checkbox grid
    var cbGrid = new Ext.grid.CheckboxSelectionModel();
    
    // row actions
    var actionmpointform = new Ext.ux.grid.RowActions({
		header :'Edit',
		autoWidth: false,
		width: 30,
        actions:[{iconCls: 'icon-edit-record', qtip: 'Edit'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    }); 
    var actionmpointformdel = new Ext.ux.grid.RowActions({
		header: 'Delete',
		autoWidth: false,
		width: 40,
        actions:[{iconCls: 'icon-delete-record', qtip: 'Delete'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    }); 
    
    actionmpointform.on('action', function(grid, record, action, row, col) {
        var kd_kategori1 = record.get('kd_kategori1');
        var kd_kategori2 = record.get('kd_kategori2');
        var kd_kategori3 = record.get('kd_kategori3');
        var kd_kategori4 = record.get('kd_kategori4');
        switch(action) {
            case 'icon-edit-record':                
                editmpointform(kd_kategori1,kd_kategori2,kd_kategori3,kd_kategori4);
                break;
            case 'icon-delete-record':
                Ext.Msg.show({
                    title: 'Confirm',
                    msg: 'Are you sure delete selected row ?',
                    buttons: Ext.Msg.YESNO,
                    fn: function(btn){
                        if (btn == 'yes') {
                            Ext.Ajax.request({
                                url: '<?= site_url("master_point/delete_rows") ?>',
                                method: 'POST',
                                params: {
                                    postdata: kd_kategori1 + '-' + kd_kategori2 + '-' + kd_kategori3 + '-' +kd_kategori4
                                },
                                callback:function(opt,success,responseObj){
                                    var de = Ext.util.JSON.decode(responseObj.responseText);
                                    if(de.success==true){
                                        strmpointform.reload();
                                        strmpointform.load({
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
    
    // grid
    var mpointform = new Ext.grid.EditorGridPanel({
        //id: 'mpointform-gridpanel',
        id: 'mpointform',
		frame: true,
        border: true,
        stripeRows: true,
        sm: cbGrid,
        store: strmpointform,
        loadMask: true,
        // title: 'Kategori 4',
        style: 'margin:0 auto;',
        height: 450,
        // width: 550,
        columns: [ actionmpointform,{
            dataIndex: 'kd_kategori4',
            hidden: true
        },{
            dataIndex: 'kd_kategori1',
            hidden: true
        },{
            dataIndex: 'kd_kategori2',
            hidden: true
        },{
            dataIndex: 'kd_kategori3',
            hidden: true
        },{
            dataIndex: 'kd_point_setting',
            hidden: true
        },{
            header: "Kode Kategori",
            dataIndex: 'kd_kategori',
            sortable: true,
            width: 150
        },{
            header: "Nama Kategori",
            dataIndex: 'nama_kategori',
            sortable: true,
            width: 150
        },{
            header: "Total Point",
            dataIndex: 'point',
            sortable: true,
            width: 100
        },{
            header: "Tanggal Awal",
            dataIndex: 'tgl_awal',
            sortable: true,
            width: 100
        },{
            header: "Tanggal Akhir",
            dataIndex: 'tgl_akhir',
            sortable: true,
            width: 100
        },{
            header: "Status Aktif",
            dataIndex: 'aktif',
            sortable: true,
            width: 100
        }],
        plugins: [actionmpointform],
        listeners: {
            'rowdblclick': function(){              
                var sm = mpointform.getSelectionModel();                
                var sel = sm.getSelections();                
                if (sel.length > 0) {
                    editmpointform(sel[0].get('kd_kategori1'),sel[0].get('kd_kategori2'),sel[0].get('kd_kategori3'),
									sel[0].get('kd_kategori4')
								);                    
                }                 
            }          
        },
        tbar: tbmpointform,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strmpointform,
            displayInfo: true
        })
    });
	
	/**
    var mpointformpanel = new Ext.FormPanel({
		id: 'mpointform',
		border: false,
		frame: false,
		autoScroll: true,
		items: [mpointform]
	});
	**/
    
    function editmpointform(kd_kategori1,kd_kategori2,kd_kategori3,kd_kategori4){
		str_mpoint_cbkategori1.load();
		mpoincbkategori4.store.proxy.conn.url = '<?= site_url("master_point/get_kategori4") ?>/' + kd_kategori1 +'/'+ kd_kategori2 +'/'+kd_kategori3;
        mpoincbkategori4.store.reload();
		mpoincbkategori3.store.proxy.conn.url = '<?= site_url("master_point/get_kategori3") ?>/' + kd_kategori1 +'/'+ kd_kategori2;
        mpoincbkategori3.store.reload();
		mpoincbkategori2.store.proxy.conn.url = '<?= site_url("kategori3/get_kategori2") ?>/' + kd_kategori1;
        mpoincbkategori2.store.reload();
		Ext.getCmp('btnresetmpointform').hide();
		Ext.getCmp('btnsubmitmpointform').setText('Update');
		winaddmpointform.setTitle('Edit Form');
		Ext.getCmp('id_formaddmpointform').getForm().load({
			url: '<?= site_url("master_point/get_row") ?>',
			params: {
				id: kd_kategori1,
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
		winaddmpointform.show();
		}
    
    function deletempointform(){     
        var sm = mpointform.getSelectionModel();
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
                            data = data + sel[i].get('kd_kategori1') + '-' + sel[i].get('kd_kategori2') + '-' + sel[i].get('kd_kategori3') + '-' + sel[i].get('kd_kategori4') +  ';';
                        }
                        
                        Ext.Ajax.request({
                            url: '<?= site_url("master_point/delete_rows") ?>',
                            method: 'POST',
                            params: {
                                postdata: data
                            },
                            callback:function(opt,success,responseObj){
                                var de = Ext.util.JSON.decode(responseObj.responseText);
                                if(de.success==true){
                                    strmpointform.reload();
                                    strmpointform.load({
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
