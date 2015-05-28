<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">   

	// input kategori3
    var strcbNamaKategori3 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori3', 'nama_kategori3'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("kategori3/get_nama_kategori3") ?>',
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

    var cbNamaKategori3 = new Ext.form.ComboBox({
        fieldLabel: 'Nama Kategori3 <span class="asterix">*</span>',
        id: 'id_nama_kategori3',
        triggerAction: 'query',
        store: strcbNamaKategori3,
        valueField: 'nama_kategori3',
        displayField: 'nama_kategori3',
        // typeAhead: true,
        allowBlank: false,
		width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori3',
		style:'text-transform: uppercase',
		minChars: 1,
		hideTrigger:true,
    });
  
 
    /* START FORM */ 

    // combobox kategori1
    var str__cbkategori = new Ext.data.Store({
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

    var __cbkategori1 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 1 <span class="asterix">*</span>',
        id: 'id___cbkategori1',
        store: str__cbkategori,
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
                var kd___cbkategori1 = this.getValue();
                __cbkategori2.setValue();
                __cbkategori2.store.proxy.conn.url = '<?= site_url("kategori3/get_kategori2") ?>/' + kd___cbkategori1;
                __cbkategori2.store.reload();
            }
        }
    });
    
    // combobox kategori2
    var str__cbkategori2 = new Ext.data.Store({
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

    var __cbkategori2 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 2 <span class="asterix">*</span>',
        id: 'id___cbkategori2',
        mode: 'local',
        store: str__cbkategori2,
        valueField: 'kd_kategori2',
        displayField: 'nama_kategori2',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_kategori2',
        emptyText: 'Pilih kategori 2'
    });

    Ext.ns('kategori3form');
    kategori3form.Form = Ext.extend(Ext.form.FormPanel, {
    
        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 100,
        url: '<?= site_url("kategori3/update_row") ?>',
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
            kategori3form.Form.superclass.constructor.call(this, config);
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
                    name: 'kd_kategori1'
                },{
                    xtype: 'hidden',
                    name: 'kd_kategori2'
                },{
                    xtype: 'hidden',
                    name: 'kd_kategori3'
                },__cbkategori1,__cbkategori2, cbNamaKategori3
				// {
                    // type: 'textfield',
                    // fieldLabel: 'Kategori <span class="asterix">*</span>',
                    // name: 'nama_kategori3',
                    // allowBlank: false,
                    // id: 'id_nama_kategori3',
					// style:'text-transform: uppercase',  
                    // maxLength: 40,
                    // anchor: '90%'                
                // }
				, new Ext.form.Checkbox({
						xtype: 'checkbox',
						fieldLabel: 'Status Aktif <span class="asterix">*</span>',
                    	boxLabel:'Ya',
						name:'aktif',
						id:'k3_aktif',
						inputValue: '1',
						autoLoad : true
				})],
                buttons: [{
                    text: 'Submit',
                    id: 'btnsubmitkategori3',
                    formBind: true,
                    scope: this,
                    handler: this.submit
                }, {
                    text: 'Reset',
                    id: 'btnresetkategori3',
                    scope: this,
                    handler: this.reset
                }, {
                    text: 'Close',
                    id: 'btnClosekategori3',
                    scope: this,
                    handler: function(){
                        winaddkategori3.hide();
                    }
                }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            
            // call parent
            kategori3form.Form.superclass.initComponent.apply(this, arguments);
            
        } // eo function initComponent  
        ,
        onRender: function(){
        
            // call parent
            kategori3form.Form.superclass.onRender.apply(this, arguments);
            
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
           var text = Ext.getCmp('btnsubmitkategori3').getText();
				if (text == 'Update'){
					Ext.Msg.show({
						title: 'Confirm',
						msg: 'Are you sure update selected row ?',
						buttons: Ext.Msg.YESNO,
						fn: function(btn){
							if (btn == 'yes') {
								Ext.getCmp('id_formaddkategori3').getForm().submit({
									url: Ext.getCmp('id_formaddkategori3').url,
									scope: this,
									success: Ext.getCmp('id_formaddkategori3').onSuccess,
									failure: Ext.getCmp('id_formaddkategori3').onFailure,
									params: {
										cmd: 'save'
									},
									waitMsg: 'Saving Data...'
								});
							}
						}
					})
				}else{
					Ext.getCmp('id_formaddkategori3').getForm().submit({
						url: Ext.getCmp('id_formaddkategori3').url,
						scope: this,
						success: Ext.getCmp('id_formaddkategori3').onSuccess,
						failure: Ext.getCmp('id_formaddkategori3').onFailure,
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
            
            
            strkategori3.reload();
            Ext.getCmp('id_formaddkategori3').getForm().reset();
            winaddkategori3.hide();
        } // eo function onSuccess
        ,
        onFailure: function(form, action){
        
            var fe = Ext.util.JSON.decode(action.response.responseText);
            Ext.getCmp('id_formaddkategori3').showError(fe.errMsg || '');
            
            
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
    Ext.reg('formaddkategori3', kategori3form.Form);
    
    var winaddkategori3 = new Ext.Window({
        id: 'id_winaddkategori3',
        closeAction: 'hide',
        width: 450,
        height: 350,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_formaddkategori3',
            xtype: 'formaddkategori3'
        },
        onHide: function(){
            Ext.getCmp('id_formaddkategori3').getForm().reset();
        }
    });
    
    /* START GRID */    
    
    // data store
    var strkategori3 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_kategori',
                'kd_kategori1', 
                'kd_kategori2', 
                'kd_kategori3',
                'nama_kategori',
                'nama_kategori1',
                'nama_kategori2',
                'nama_kategori3',
				'aktif'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("kategori3/get_rows") ?>',
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
    var searchkategori3 = new Ext.app.SearchField({
        store: strkategori3,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchkategori3'
    });
    
    // top toolbar
    var tbkategori3 = new Ext.Toolbar({
        items: [{
            text: 'Add',
            icon: BASE_ICONS + 'add.png',
            onClick: function(){  
                Ext.getCmp('id___cbkategori1').setDisabled(false);
                Ext.getCmp('id___cbkategori2').setDisabled(false);   
				Ext.getCmp('id___cbkategori1').setValue('');
				Ext.getCmp('id___cbkategori2').setValue('');           
                Ext.getCmp('btnresetkategori3').show();
                Ext.getCmp('btnsubmitkategori3').setText('Submit');
                winaddkategori3.setTitle('Add Form');
                winaddkategori3.show();                
            }            
        }, '-', searchkategori3]
    });
    
    // checkbox grid
    var cbGrid = new Ext.grid.CheckboxSelectionModel();
    
    // row actions
    var actionkategori3 = new Ext.ux.grid.RowActions({
		header :'Edit',
		autoWidth: false,
		width: 30,
        actions:[{iconCls: 'icon-edit-record', qtip: 'Edit'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    }); 
    var actionkategori3del = new Ext.ux.grid.RowActions({
		header: 'Delete',
		autoWidth: false,
		width: 40,
        actions:[{iconCls: 'icon-delete-record', qtip: 'Delete'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    }); 
    
    actionkategori3.on('action', function(grid, record, action, row, col) {
        var kd_kategori1 = record.get('kd_kategori1');
        var kd_kategori2 = record.get('kd_kategori2');
        var kd_kategori3 = record.get('kd_kategori3');
        var nm_kategori1 = record.get('nama_kategori1');
        var nm_kategori2 = record.get('nama_kategori2');
        switch(action) {
            case 'icon-edit-record':                
                editkategori3(kd_kategori1,kd_kategori2,kd_kategori3,nm_kategori1,nm_kategori2);
                break;
            case 'icon-delete-record':
                Ext.Msg.show({
                    title: 'Confirm',
                    msg: 'Are you sure delete selected row ?',
                    buttons: Ext.Msg.YESNO,
                    fn: function(btn){
                        if (btn == 'yes') {
                            Ext.Ajax.request({
                                url: '<?= site_url("kategori3/delete_rows") ?>',
                                method: 'POST',
                                params: {
                                    postdata: kd_kategori1 + '-' + kd_kategori2 + '-' + kd_kategori3 
                                },
                                callback:function(opt,success,responseObj){
                                    var de = Ext.util.JSON.decode(responseObj.responseText);
                                    if(de.success==true){
                                        strkategori3.reload();
                                        strkategori3.load({
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
	
	var multisearchkategori3 = new Ext.ux.grid.Search({
				iconCls:'icon-zoom'
				//,readonlyIndexes:['note']
				//,disableIndexes:['pctChange']
				,minChars:3
				,autoFocus:true
				,width:250
			});
    
    // grid
    var kategori3 = new Ext.grid.EditorGridPanel({
        //id: 'kategori3-gridpanel',
        id: 'kategori3',
		frame: true,
        border: true,
        stripeRows: true,
        sm: cbGrid,
        store: strkategori3,
        loadMask: true,
        // title: 'Kategori 3',
        style: 'margin:0 auto;',
        height: 450,
        // width: 600,
        columns: [actionkategori3,{
            dataIndex: 'kd_kategori1',
            hidden: true
        },{
            dataIndex: 'kd_kategori2',
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
            width: 400
        },{
            header: "Status Aktif",
            dataIndex: 'aktif',
            sortable: true,
            width: 100
        }],
        plugins: [actionkategori3,multisearchkategori3],
        listeners: {
            'rowdblclick': function(){              
                var sm = kategori3.getSelectionModel();                
                var sel = sm.getSelections();                
                if (sel.length > 0) {
                    editkategori3(sel[0].get('kd_kategori1'),sel[0].get('kd_kategori2'),
								sel[0].get('kd_kategori3'),sel[0].get('nama_kategori1'),
								sel[0].get('nama_kategori2')
					);                    
                }                 
            }          
        },
        tbar: tbkategori3,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strkategori3,
            displayInfo: true
        })
    });
	
	/**
    var kategori3panel = new Ext.FormPanel({
		id: 'kategori3',
		border: false,
		frame: false,
		autoScroll: true,
		items: [kategori3]
	});
	**/
    
    function editkategori3(kd_kategori1,kd_kategori2,kd_kategori3,nm_kategori1,nm_kategori2){
		str__cbkategori.load();
		str__cbkategori2.load();
		Ext.getCmp('id___cbkategori1').setValue(nm_kategori1);
		Ext.getCmp('id___cbkategori2').setValue(nm_kategori2);
		Ext.getCmp('id___cbkategori1').setDisabled(true);
		Ext.getCmp('id___cbkategori2').setDisabled(true);
		Ext.getCmp('btnresetkategori3').hide();
		Ext.getCmp('btnsubmitkategori3').setText('Update');
		winaddkategori3.setTitle('Edit Form');
		Ext.getCmp('id_formaddkategori3').getForm().load({
			url: '<?= site_url("kategori3/get_row") ?>',
			params: {
				id1: kd_kategori1,
				id2: kd_kategori2,
				id: kd_kategori3,
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
		winaddkategori3.show();
		}
		
    function deletekategori3(){     
        var sm = kategori3.getSelectionModel();
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
                            data = data + sel[i].get('kd_kategori1')  + '-' + sel[i].get('kd_kategori2')  + '-' + sel[i].get('kd_kategori3') +';';
                        }
                        
                        Ext.Ajax.request({
                            url: '<?= site_url("kategori3/delete_rows") ?>',
                            method: 'POST',
                            params: {
                                postdata: data
                            },
                            callback:function(opt,success,responseObj){
                                var de = Ext.util.JSON.decode(responseObj.responseText);
                                if(de.success==true){
                                    strkategori3.reload();
                                    strkategori3.load({
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
