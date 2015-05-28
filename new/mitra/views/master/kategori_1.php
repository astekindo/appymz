<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">  

    // input kategori1
    var strcbNamaKategori1 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori1', 'nama_kategori1'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("kategori1/get_rows") ?>',
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

    var cbNamaKategori1 = new Ext.form.ComboBox({
        fieldLabel: 'Nama Kategori1 <span class="asterix">*</span>',
        id: 'id_nama_kategori1',
        triggerAction: 'query',
        store: strcbNamaKategori1,
        valueField: 'nama_kategori1',
        displayField: 'nama_kategori1',
        // typeAhead: true,
        allowBlank: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori1',
        style:'text-transform: uppercase',
        minChars: 1,
        hideTrigger:true
    });
  
    /* START FORM */ 
    Ext.ns('kategori1form');
    kategori1form.Form = Ext.extend(Ext.form.FormPanel, {
    
        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 100,
        waitMsg:'Loading...',
        url: '<?= site_url("kategori1/update_row") ?>',
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
            kategori1form.Form.superclass.constructor.call(this, config);
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
                    },cbNamaKategori1
                    // {
                    // type: 'textfield',
                    // fieldLabel: 'Kategori <span class="asterix">*</span>',
                    // name: 'nama_kategori1',
                    // allowBlank: false,
                    // id: 'id_nama_kategori1',
                    // anchor: '90%',
                    // maxLength: 40,
                    // style:'text-transform: uppercase',  
					       
                    // }
                    , new Ext.form.Checkbox({
                        xtype: 'checkbox',
                        fieldLabel: 'Status Aktif <span class="asterix">*</span>',
                        boxLabel:'Ya',
                        name:'aktif',
                        id:'k1_aktif',
                        inputValue: '1',
                        autoLoad : true
                    })],
                buttons: [{
                        text: 'Submit',
                        id: 'btnsubmitkategori1',
                        formBind: true,
                        scope: this,
                        handler: this.submit
                    }, {
                        text: 'Reset',
                        id: 'btnresetkategori1',
                        scope: this,
                        handler: this.reset
                    }, {
                        text: 'Close',
                        id: 'btnClosekategori1',
                        scope: this,
                        handler: function(){
                            winaddkategori1.hide();
                        }
                    }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            
            // call parent
            kategori1form.Form.superclass.initComponent.apply(this, arguments);
            
        } // eo function initComponent	
        ,
        onRender: function(){
        
            // call parent
            kategori1form.Form.superclass.onRender.apply(this, arguments);
            
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
            var text = Ext.getCmp('btnsubmitkategori1').getText();
            if (text == 'Update'){
                Ext.Msg.show({
                    title: 'Confirm',
                    msg: 'Are you sure update selected row ?',
                    buttons: Ext.Msg.YESNO,
                    fn: function(btn){
                        if (btn == 'yes') {
                            Ext.getCmp('id_formaddkategori1').getForm().submit({
                                url: Ext.getCmp('id_formaddkategori1').url,
                                scope: this,
                                success: Ext.getCmp('id_formaddkategori1').onSuccess,
                                failure: Ext.getCmp('id_formaddkategori1').onFailure,
                                params: {
                                    cmd: 'save'
                                },
                                waitMsg: 'Saving Data...'
                            });
                        }
                    }
                })
            }else{
                Ext.getCmp('id_formaddkategori1').getForm().submit({
                    url: Ext.getCmp('id_formaddkategori1').url,
                    scope: this,
                    success: Ext.getCmp('id_formaddkategori1').onSuccess,
                    failure: Ext.getCmp('id_formaddkategori1').onFailure,
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
            
            
            strkategori1.reload();
            Ext.getCmp('id_formaddkategori1').getForm().reset();
            winaddkategori1.hide();
        } // eo function onSuccess
        ,
        onFailure: function(form, action){
        
            var fe = Ext.util.JSON.decode(action.response.responseText);
            Ext.getCmp('id_formaddkategori1').showError(fe.errMsg || '');
            
            
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
    Ext.reg('formaddkategori1', kategori1form.Form);
    
    var winaddkategori1 = new Ext.Window({
        id: 'id_winaddkategori1',
        closeAction: 'hide',
        width: 450,
        height: 350,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_formaddkategori1',
            xtype: 'formaddkategori1'
        },
        onHide: function(){
            Ext.getCmp('id_formaddkategori1').getForm().reset();
        }
    });
    
    /* START GRID */    
	
    // data store
    var strkategori1 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_kategori1',
                'nama_kategori1',
                'aktif'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("kategori1/get_rows") ?>',
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
    var searchkategori1 = new Ext.app.SearchField({
        store: strkategori1,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchkategori1'
    });
    
    // top toolbar
    var tbkategori1 = new Ext.Toolbar({
        items: [{
                text: 'Add',
                icon: BASE_ICONS + 'add.png',
                onClick: function(){				
                    Ext.getCmp('btnresetkategori1').show();
                    Ext.getCmp('btnsubmitkategori1').setText('Submit');
                    winaddkategori1.setTitle('Add Form');
                    winaddkategori1.show();                
                }            
            }, '-', searchkategori1]
    });
	
    // checkbox grid
    var cbGrid = new Ext.grid.CheckboxSelectionModel();
    
    // row actions
    var actionkategori1 = new Ext.ux.grid.RowActions({
        locked: true,
        header: 'Edit',
        autoWidth: false,
        width: 30,
        actions:[{iconCls: 'icon-edit-record', qtip: 'Edit'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });	
    var actionkategori1del = new Ext.ux.grid.RowActions({
        header: 'Delete',
        autoWidth: false,
        width: 40,
        actions:[{iconCls: 'icon-delete-record', qtip: 'Delete'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });	
	
    actionkategori1.on('action', function(grid, record, action, row, col) {
        var kd_kategori1 = record.get('kd_kategori1');
        switch(action) {
            case 'icon-edit-record':	        	
                editkategori1(kd_kategori1);
                break;
            case 'icon-delete-record':
                deletekategori1();
                break;	      
	      	
        }
    });  
  	
    var multisearchkategori1 = new Ext.ux.grid.Search({
        iconCls:'icon-zoom'
        //,readonlyIndexes:['note']
        //,disableIndexes:['pctChange']
        ,minChars:3
        ,autoFocus:true
        ,width:250
    });
			
    // grid
    var kategori1 = new Ext.grid.EditorGridPanel({
        //id: 'id-kategori1-grid',
        id: 'kategori1',
        frame: false,
        border: false,
        stripeRows: true,
        sm: cbGrid,
        store: strkategori1,
        //closable:true,
        loadMask: true,
        //title: 'Kategori 1',
        style: 'margin:0 auto;',
        height: 450,
        //width: 550,
        view: new Ext.ux.grid.LockingGridView(),
        colModel: new Ext.ux.grid.LockingColumnModel([actionkategori1,
            {
                header: "Kode Kategori",
                dataIndex: 'kd_kategori1',
                sortable: true,
                locked: true,
                width: 150
            },{
                header: "Nama Kategori",
                dataIndex: 'nama_kategori1',
                sortable: true,
                width: 300
            },{
                header: "Status Aktif",
                dataIndex: 'aktif',
                sortable: true,
                width: 100
            }]),
        plugins: [actionkategori1,multisearchkategori1],
        listeners: {
            'rowdblclick': function(){				
                var sm = kategori1.getSelectionModel();                
                var sel = sm.getSelections();                
                if (sel.length > 0) {
                    editkategori1(sel[0].get('kd_kategori1'));                    
                }                 
            }          
        },
        tbar: tbkategori1,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strkategori1,
            displayInfo: true
        })
    });
   
    /**
        var kategoripanel = new Ext.FormPanel({
                //id: 'kategori1',
                border: false,
        frame: false,
                autoScroll:true,	
        items: [kategori1]
        });
     **/
    function editkategori1(kd_kategori1){
        Ext.getCmp('btnresetkategori1').hide();
        Ext.getCmp('btnsubmitkategori1').setText('Update');
        winaddkategori1.setTitle('Edit Form');
        Ext.getCmp('id_formaddkategori1').getForm().load({
            url: '<?= site_url("kategori1/get_row") ?>',
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
        winaddkategori1.show();
    }
	
    function deletekategori1(){		
        var sm = kategori1.getSelectionModel();
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
                            data = data + sel[i].get('kd_kategori1') + ';';
                        }
                        
                        Ext.Ajax.request({
                            url: '<?= site_url("kategori1/delete_rows") ?>',
                            method: 'POST',
                            params: {
                                postdata: data
                            },
                            callback:function(opt,success,responseObj){
                                var de = Ext.util.JSON.decode(responseObj.responseText);
                                if(de.success==true){
                                    strkategori1.reload();
                                    strkategori1.load({
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
