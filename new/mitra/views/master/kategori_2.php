<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">    

    // input kategori2
    var strcbNamaKategori2 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori2', 'nama_kategori2'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("kategori2/get_nama_kategori2") ?>',
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

    var cbNamaKategori2 = new Ext.form.ComboBox({
        fieldLabel: 'Nama Kategori2 <span class="asterix">*</span>',
        id: 'id_nama_kategori2',
        triggerAction: 'query',
        store: strcbNamaKategori2,
        valueField: 'nama_kategori2',
        displayField: 'nama_kategori2',
        // typeAhead: true,
        allowBlank: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori2',
        style:'text-transform: uppercase',
        minChars: 1,
        hideTrigger:true,
    });
  

    /* START FORM */ 
    // combobox kategori1
    var strcbkategori1 = new Ext.data.Store({
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
 
    var cbkategori1 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 1 <span class="asterix">*</span>',
        id: 'id_cbkategori1',
        store: strcbkategori1,
        valueField: 'kd_kategori1',
        displayField: 'nama_kategori1',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_kategori1',
        emptyText: 'Pilih kategori 1',
        hideMode: 'Visibility'
    });

    Ext.ns('kategori2form');
    kategori2form.Form = Ext.extend(Ext.form.FormPanel, {
    
        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 100,
        url: '<?= site_url("kategori2/update_row") ?>',
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
            kategori2form.Form.superclass.constructor.call(this, config);
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
                        name: 'kd_kategori2'
                    },{
                        xtype: 'hidden',
                        name: 'kd_kategori1'
                    },cbkategori1, cbNamaKategori2
                    // {
                    // type: 'textfield',
                    // fieldLabel: 'Kategori <span class="asterix">*</span>',
                    // name: 'nama_kategori2',
                    // allowBlank: false,
                    // id: 'id_nama_kategori2',
                    // maxLength: 40,
                    // style:'text-transform: uppercase',  
                    // anchor: '90%'                
                    // }
                    , new Ext.form.Checkbox({
                        xtype: 'checkbox',
                        fieldLabel: 'Status Aktif <span class="asterix">*</span>',
                        boxLabel:'Ya',
                        name:'aktif',
                        id:'k2_aktif',
                        inputValue: '1',
                        autoLoad : true
                    })],
                buttons: [{
                        text: 'Submit',
                        id: 'btnsubmitkategori2',
                        formBind: true,
                        scope: this,
                        handler: this.submit
                    }, {
                        text: 'Reset',
                        id: 'btnresetkategori2',
                        scope: this,
                        handler: this.reset
                    }, {
                        text: 'Close',
                        id: 'btnClosekategori2',
                        scope: this,
                        handler: function(){
                            winaddkategori2.hide();
                        }
                    }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            
            // call parent
            kategori2form.Form.superclass.initComponent.apply(this, arguments);
            
        } // eo function initComponent  
        ,
        onRender: function(){
        
            // call parent
            kategori2form.Form.superclass.onRender.apply(this, arguments);
            
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
        
            var text = Ext.getCmp('btnsubmitkategori2').getText();
            if (text == 'Update'){
                Ext.Msg.show({
                    title: 'Confirm',
                    msg: 'Are you sure update selected row ?',
                    buttons: Ext.Msg.YESNO,
                    fn: function(btn){
                        if (btn == 'yes') {
                            Ext.getCmp('id_formaddkategori2').getForm().submit({
                                url: Ext.getCmp('id_formaddkategori2').url,
                                scope: this,
                                success: Ext.getCmp('id_formaddkategori2').onSuccess,
                                failure: Ext.getCmp('id_formaddkategori2').onFailure,
                                params: {
                                    cmd: 'save'
                                },
                                waitMsg: 'Saving Data...'
                            });
                        }
                    }
                })
            }else{
                Ext.getCmp('id_formaddkategori2').getForm().submit({
                    url: Ext.getCmp('id_formaddkategori2').url,
                    scope: this,
                    success: Ext.getCmp('id_formaddkategori2').onSuccess,
                    failure: Ext.getCmp('id_formaddkategori2').onFailure,
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
            
            
            strkategori2.reload();
            Ext.getCmp('id_formaddkategori2').getForm().reset();
            winaddkategori2.hide();
        } // eo function onSuccess
        ,
        onFailure: function(form, action){
        
            var fe = Ext.util.JSON.decode(action.response.responseText);
            Ext.getCmp('id_formaddkategori2').showError(fe.errMsg || '');
            
            
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
    Ext.reg('formaddkategori2', kategori2form.Form);
    
    var winaddkategori2 = new Ext.Window({
        id: 'id_winaddkategori2',
        closeAction: 'hide',
        width: 450,
        height: 350,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_formaddkategori2',
            xtype: 'formaddkategori2'
        },
        onHide: function(){
            Ext.getCmp('id_formaddkategori2').getForm().reset();
        }
    });
    
    /* START GRID */    
    
    // data store
    var strkategori2 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_kategori2',
                'kd_kategori1',
                'kd_kategori',
                'nama_kategori1',
                'nama_kategori2',
                'nama_kategori',
                'aktif'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("kategori2/get_rows") ?>',
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
    var searchkategori2 = new Ext.app.SearchField({
        store: strkategori2,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },

        width: 220,
        id: 'idsearchkategori2'
    });
    
    // top toolbar
    var tbkategori2 = new Ext.Toolbar({
        items: [{
                text: 'Add',
                icon: BASE_ICONS + 'add.png',
                onClick: function(){      
                    strcbkategori1.reload();
                    Ext.getCmp('id_cbkategori1').setDisabled(false);
                    Ext.getCmp('id_cbkategori1').setValue('');	
                    Ext.getCmp('btnresetkategori2').show();
                    Ext.getCmp('btnsubmitkategori2').setText('Submit');
                    winaddkategori2.setTitle('Add Form');
                    winaddkategori2.show();
                }            
            }, '-', searchkategori2]
    });
    
    // checkbox grid
    var cbGrid = new Ext.grid.CheckboxSelectionModel();
    
    // row actions
    var actionkategori2 = new Ext.ux.grid.RowActions({
        header :'Edit',
        autoWidth: false,
        width: 30,
        actions:[{iconCls: 'icon-edit-record', qtip: 'Edit'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    }); 
    var actionkategori2del = new Ext.ux.grid.RowActions({
        header: 'Delete',
        autoWidth: false,
        width: 40,
        actions:[{iconCls: 'icon-delete-record', qtip: 'Delete'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    }); 
    
    actionkategori2.on('action', function(grid, record, action, row, col) {
        var kd_kategori2 = record.get('kd_kategori2');
        var kd_kategori1 = record.get('kd_kategori1');
        var nm_kategori1 = record.get('nama_kategori1');
        switch(action) {
            case 'icon-edit-record':
                editkategori2(kd_kategori2,kd_kategori1,nm_kategori1);
                break;
            case 'icon-delete-record':
                Ext.Msg.show({
                    title: 'Confirm',
                    msg: 'Are you sure delete selected row ?',
                    buttons: Ext.Msg.YESNO,
                    fn: function(btn){
                        if (btn == 'yes') {
                            Ext.Ajax.request({
                                url: '<?= site_url("kategori2/delete_rows") ?>',
                                method: 'POST',
                                params: {
                                    postdata: kd_kategori2 + '-' + kd_kategori1
                                },
                                callback:function(opt,success,responseObj){
                                    var de = Ext.util.JSON.decode(responseObj.responseText);
                                    if(de.success==true){
                                        strkategori2.reload();
                                        strkategori2.load({
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
	
        var multisearchkategori2 = new Ext.ux.grid.Search({
            iconCls:'icon-zoom'
            //,readonlyIndexes:['note']
            //,disableIndexes:['pctChange']
            ,minChars:3
            ,autoFocus:true
            ,width:250
        });
    
        // grid
        var kategori2 = new Ext.grid.EditorGridPanel({
            //id: 'kategori2-gridpanel',
            id: 'kategori2',
            frame: true,
            border: true,
            stripeRows: true,
            sm: cbGrid,
            store: strkategori2,
            loadMask: true,
            // title: 'Kategori 2',
            style: 'margin:0 auto;',
            height: 450,
            columns: [actionkategori2,{
                    dataIndex: 'kd_kategori1',
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
                    width: 300
                },{
                    header: "Status Aktif",
                    dataIndex: 'aktif',
                    sortable: true,
                    width: 100
                }],
            plugins: [actionkategori2,multisearchkategori2],
            listeners: {
                'rowdblclick': function(){              
                    var sm = kategori2.getSelectionModel();                
                    var sel = sm.getSelections();                
                    if (sel.length > 0) {
                        editkategori2(sel[0].get('kd_kategori2'),sel[0].get('kd_kategori1'),sel[0].get('nama_kategori1'));
                    }                 
                }          
            },
            tbar: tbkategori2,
            bbar: new Ext.PagingToolbar({
                pageSize: ENDPAGE,
                store: strkategori2,
                displayInfo: true
            })
        });
        /**
    var kategori2panel = new Ext.FormPanel({
                id: 'kategori2',
                border: false,
                frame: false,
                autoScroll: true,
                items: [kategori2]
        });
         **/
        function editkategori2(kd_kategori2,kd_kategori1,nama_kategori1){
            strcbkategori1.load();
            Ext.getCmp('id_cbkategori1').setDisabled(true);
            Ext.getCmp('id_cbkategori1').setValue(nama_kategori1);			
            Ext.getCmp('btnsubmitkategori2').setText('Update');
            winaddkategori2.setTitle('Edit Form');
            Ext.getCmp('id_formaddkategori2').getForm().load({
                url: '<?= site_url("kategori2/get_row") ?>',
                params: {
                    id: kd_kategori2,
                    id1: kd_kategori1,
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
            winaddkategori2.show();
        }
			
    
        function deletekategori2(){     
            var sm = kategori2.getSelectionModel();
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
                                data = data + sel[i].get('kd_kategori2') + '-' + sel[i].get('kd_kategori1') + ';';
                            }
                        
                            Ext.Ajax.request({
                                url: '<?= site_url("kategori2/delete_rows") ?>',
                                method: 'POST',
                                params: {
                                    postdata: data
                                },
                                callback:function(opt,success,responseObj){
                                    var de = Ext.util.JSON.decode(responseObj.responseText);
                                    if(de.success==true){
                                        strkategori2.reload();
                                        strkategori2.load({
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
