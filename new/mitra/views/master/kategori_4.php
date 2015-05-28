<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">   

	// input kategori4
    var strcbNamaKategori4 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori4', 'nama_kategori4'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("kategori4/get_nama_kategori4") ?>',
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

    var cbNamaKategori4 = new Ext.form.ComboBox({
        fieldLabel: 'Nama Kategori4 <span class="asterix">*</span>',
        id: 'id_nama_kategori4',
        triggerAction: 'query',
        store: strcbNamaKategori4,
        valueField: 'nama_kategori4',
        displayField: 'nama_kategori4',
        // typeAhead: true,
        allowBlank: false,
		width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori4',
		style:'text-transform: uppercase',
		minChars: 1,
		hideTrigger:true,
    });
  
 
    /* START FORM */ 

    // combobox kategori1
    var str_k4_cbkategori1 = new Ext.data.Store({
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

    var ___cbkategori1 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 1 <span class="asterix">*</span>',
        id: 'id_k4_cbkategori1',
        store: str_k4_cbkategori1,
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
                var kd____cbkategori1 = this.getValue();
                ___cbkategori2.setValue();
                ___cbkategori2.store.proxy.conn.url = '<?= site_url("kategori3/get_kategori2") ?>/' + kd____cbkategori1;
                ___cbkategori2.store.reload();
            }
        }
    });
    // combobox kategori2
    var str_k4_cbkategori2 = new Ext.data.Store({
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

    var ___cbkategori2 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 2 <span class="asterix">*</span>',
        id: 'id_k4_cbkategori2',
        mode: 'local',
        store: str_k4_cbkategori2,
        valueField: 'kd_kategori2',
        displayField: 'nama_kategori2',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_kategori2',
        emptyText: 'Pilih kategori 2',
        listeners: {
            select: function(combo, records) {
                var kd____cbkategori1 = ___cbkategori1.getValue();
                var kd____cbkategori2 = this.getValue();
                ___cbkategori3.setValue();
                ___cbkategori3.store.proxy.conn.url = '<?= site_url("kategori4/get_kategori3") ?>/' + kd____cbkategori1 +'/'+ kd____cbkategori2;
                ___cbkategori3.store.reload();
            }
        }
    });
	
    // combobox kategori3
    var str_k4_cbkategori3 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori3', 'nama_kategori3'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("kategori4/get_kategori3") ?>',
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

    var ___cbkategori3 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 3 <span class="asterix">*</span>',
        id: 'id_k4_cbkategori3',
        mode: 'local',
        store: str_k4_cbkategori3,
        valueField: 'kd_kategori3',
        displayField: 'nama_kategori3',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_kategori3',
        emptyText: 'Pilih kategori 3'
    });

    Ext.ns('kategori4form');
    kategori4form.Form = Ext.extend(Ext.form.FormPanel, {
    
        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 100,
        url: '<?= site_url("kategori4/update_row") ?>',
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
            kategori4form.Form.superclass.constructor.call(this, config);
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
                },{
                    xtype: 'hidden',
                    name: 'kd_kategori4'
                },___cbkategori1,___cbkategori2,___cbkategori3, cbNamaKategori4
				
				// {
                    // type: 'textfield',
                    // fieldLabel: 'Kategori <span class="asterix">*</span>',
                    // name: 'nama_kategori4',
                    // allowBlank: false,
                    // id: 'id_nama_kategori4',
                    // maxLength: 40,
					// style:'text-transform: uppercase',  
                    // anchor: '90%'                
                // }
				, new Ext.form.Checkbox({
						xtype: 'checkbox',
						fieldLabel: 'Status Aktif <span class="asterix">*</span>',
                    	boxLabel:'Ya',
						name:'aktif',
						id:'k4_aktif',
						inputValue: '1',
						autoLoad : true
				})],
                buttons: [{
                    text: 'Submit',
                    id: 'btnsubmitkategori4',
                    formBind: true,
                    scope: this,
                    handler: this.submit
                }, {
                    text: 'Reset',
                    id: 'btnresetkategori4',
                    scope: this,
                    handler: this.reset
                }, {
                    text: 'Close',
                    id: 'btnClosekategori4',
                    scope: this,
                    handler: function(){
                        winaddkategori4.hide();
                    }
                }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            
            // call parent
            kategori4form.Form.superclass.initComponent.apply(this, arguments);
            
        } // eo function initComponent  
        ,
        onRender: function(){
        
            // call parent
            kategori4form.Form.superclass.onRender.apply(this, arguments);
            
            // set wait message target
            this.getForm().waitMsgTarget = this.getEl();
            
            // loads form after initial layout
            // this.on('afterlayout', this.onLoadClick, this, {single:true});
        
        } // eo function onRender
        ,
        reset: function(){
            this.getForm().reset();
        },
        submit: function(){ var text = Ext.getCmp('btnsubmitkategori4').getText();
				if (text == 'Update'){
					Ext.Msg.show({
						title: 'Confirm',
						msg: 'Are you sure update selected row ?',
						buttons: Ext.Msg.YESNO,
						fn: function(btn){
							if (btn == 'yes') {
								Ext.getCmp('id_formaddkategori4').getForm().submit({
									url: Ext.getCmp('id_formaddkategori4').url,
									scope: this,
									success: Ext.getCmp('id_formaddkategori4').onSuccess,
									failure: Ext.getCmp('id_formaddkategori4').onFailure,
									params: {
										cmd: 'save'
									},
									waitMsg: 'Saving Data...'
								});
							}
						}
					})
				}else{
					Ext.getCmp('id_formaddkategori4').getForm().submit({
						url: Ext.getCmp('id_formaddkategori4').url,
						scope: this,
						success: Ext.getCmp('id_formaddkategori4').onSuccess,
						failure: Ext.getCmp('id_formaddkategori4').onFailure,
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
            
            
            strkategori4.reload();
            Ext.getCmp('id_formaddkategori4').getForm().reset();
            winaddkategori4.hide();
        } // eo function onSuccess
        ,
        onFailure: function(form, action){
        
            var fe = Ext.util.JSON.decode(action.response.responseText);
            Ext.getCmp('id_formaddkategori4').showError(fe.errMsg || '');
            
            
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
    Ext.reg('formaddkategori4', kategori4form.Form);
    
    var winaddkategori4 = new Ext.Window({
        id: 'id_winaddkategori4',
        closeAction: 'hide',
        width: 450,
        height: 350,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_formaddkategori4',
            xtype: 'formaddkategori4'
        },
        onHide: function(){
            Ext.getCmp('id_formaddkategori4').getForm().reset();
        }
    });
    
    /* START GRID */    
    
    // data store
    var strkategori4 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_kategori',
                'kd_kategori1', 
                'kd_kategori2', 
                'kd_kategori3', 
                'kd_kategori4',
                'nama_kategori',
                'nama_kategori1',
                'nama_kategori2',
                'nama_kategori3',
                'nama_kategori4',
				'aktif'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("kategori4/get_rows") ?>',
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
    var searchkategori4 = new Ext.app.SearchField({
        store: strkategori4,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchkategori4'
    });
    
    // top toolbar
    var tbkategori4 = new Ext.Toolbar({
        items: [{
            text: 'Add',
            icon: BASE_ICONS + 'add.png',
            onClick: function(){             
                Ext.getCmp('id_k4_cbkategori1').setDisabled(false);
                Ext.getCmp('id_k4_cbkategori2').setDisabled(false);
                Ext.getCmp('id_k4_cbkategori3').setDisabled(false); 
				Ext.getCmp('id_k4_cbkategori1').setValue('');
				Ext.getCmp('id_k4_cbkategori2').setValue('');   
				Ext.getCmp('id_k4_cbkategori3').setValue('');   
                Ext.getCmp('btnresetkategori4').show();
                Ext.getCmp('btnsubmitkategori4').setText('Submit');
                winaddkategori4.setTitle('Add Form');
                winaddkategori4.show();                
            }            
        }, '-', searchkategori4]
    });
    
    // checkbox grid
    var cbGrid = new Ext.grid.CheckboxSelectionModel();
    
    // row actions
    var actionkategori4 = new Ext.ux.grid.RowActions({
		header :'Edit',
		autoWidth: false,
		width: 30,
        actions:[{iconCls: 'icon-edit-record', qtip: 'Edit'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    }); 
    var actionkategori4del = new Ext.ux.grid.RowActions({
		header: 'Delete',
		autoWidth: false,
		width: 40,
        actions:[{iconCls: 'icon-delete-record', qtip: 'Delete'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    }); 
    
    actionkategori4.on('action', function(grid, record, action, row, col) {
        var nm_kategori1 = record.get('nama_kategori1');
        var nm_kategori2 = record.get('nama_kategori2');
        var nm_kategori3 = record.get('nama_kategori3');
        var kd_kategori1 = record.get('kd_kategori1');
        var kd_kategori2 = record.get('kd_kategori2');
        var kd_kategori3 = record.get('kd_kategori3');
        var kd_kategori4 = record.get('kd_kategori4');
        switch(action) {
            case 'icon-edit-record':                
                editkategori4(nm_kategori1,nm_kategori2,nm_kategori3,kd_kategori1,kd_kategori2,kd_kategori3,kd_kategori4);
                break;
            case 'icon-delete-record':
                Ext.Msg.show({
                    title: 'Confirm',
                    msg: 'Are you sure delete selected row ?',
                    buttons: Ext.Msg.YESNO,
                    fn: function(btn){
                        if (btn == 'yes') {
                            Ext.Ajax.request({
                                url: '<?= site_url("kategori4/delete_rows") ?>',
                                method: 'POST',
                                params: {
                                    postdata: kd_kategori1 + '-' + kd_kategori2 + '-' + kd_kategori3 + '-' +kd_kategori4
                                },
                                callback:function(opt,success,responseObj){
                                    var de = Ext.util.JSON.decode(responseObj.responseText);
                                    if(de.success==true){
                                        strkategori4.reload();
                                        strkategori4.load({
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
	
	var multisearchkategori4 = new Ext.ux.grid.Search({
				iconCls:'icon-zoom'
				//,readonlyIndexes:['note']
				//,disableIndexes:['pctChange']
				,minChars:3
				,autoFocus:true
				,width:250
			});
    
    // grid
    var kategori4 = new Ext.grid.EditorGridPanel({
        //id: 'kategori4-gridpanel',
        id: 'kategori4',
		frame: true,
        border: true,
        stripeRows: true,
        sm: cbGrid,
        store: strkategori4,
        loadMask: true,
        // title: 'Kategori 4',
        style: 'margin:0 auto;',
        height: 450,
        // width: 550,
        columns: [ actionkategori4,{
            dataIndex: 'kd_kategori1',
            hidden: true
        },{
            dataIndex: 'kd_kategori2',
            hidden: true
        },{
            dataIndex: 'kd_kategori3',
            hidden: true
        },{
            dataIndex: 'kd_kategori4',
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
        plugins: [actionkategori4,multisearchkategori4],
        listeners: {
            'rowdblclick': function(){              
                var sm = kategori4.getSelectionModel();                
                var sel = sm.getSelections();                
                if (sel.length > 0) {
                    editkategori4(sel[0].get('nama_kategori1'),sel[0].get('nama_kategori2'),sel[0].get('nama_kategori3'),
									sel[0].get('kd_kategori1'),sel[0].get('kd_kategori2'),sel[0].get('kd_kategori3'),
									sel[0].get('kd_kategori4')
								);                    
                }                 
            }          
        },
        tbar: tbkategori4,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strkategori4,
            displayInfo: true
        })
    });
	
	/**
    var kategori4panel = new Ext.FormPanel({
		id: 'kategori4',
		border: false,
		frame: false,
		autoScroll: true,
		items: [kategori4]
	});
	**/
    
    function editkategori4(nama_kategori1,nama_kategori2,nama_kategori3,kd_kategori1,kd_kategori2,kd_kategori3,kd_kategori4){
		str_k4_cbkategori1.load();
		str_k4_cbkategori2.load();
		str_k4_cbkategori3.load();
		Ext.getCmp('id_k4_cbkategori1').setDisabled(true);
		Ext.getCmp('id_k4_cbkategori2').setDisabled(true);
		Ext.getCmp('id_k4_cbkategori3').setDisabled(true);
		Ext.getCmp('id_k4_cbkategori1').setValue(nama_kategori1);
		Ext.getCmp('id_k4_cbkategori2').setValue(nama_kategori2);   
		Ext.getCmp('id_k4_cbkategori3').setValue(nama_kategori3);   
		Ext.getCmp('btnresetkategori4').hide();
		Ext.getCmp('btnsubmitkategori4').setText('Update');
		winaddkategori4.setTitle('Edit Form');
		Ext.getCmp('id_formaddkategori4').getForm().load({
			url: '<?= site_url("kategori4/get_row") ?>',
			params: {
				id1: kd_kategori1,
				id2: kd_kategori2,
				id3: kd_kategori3,
				id: kd_kategori4,
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
		winaddkategori4.show();
		}
    
    function deletekategori4(){     
        var sm = kategori4.getSelectionModel();
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
                            url: '<?= site_url("kategori4/delete_rows") ?>',
                            method: 'POST',
                            params: {
                                postdata: data
                            },
                            callback:function(opt,success,responseObj){
                                var de = Ext.util.JSON.decode(responseObj.responseText);
                                if(de.success==true){
                                    strkategori4.reload();
                                    strkategori4.load({
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
