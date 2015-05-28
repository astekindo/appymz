<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">    
    
    Ext.ns('menuform');   

    var strcmbmenu = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_menu',
                'kd_parent_menu',
                'menu_id',
                'menu_text',
                'menu_leaf',
                'menu_expanded',
                'menu_description',
                'aktif'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("menu/get_rows") ?>',
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
    var kodeparentmenu=null;
    var mnleaf=null;
    var mnexpand=null;

    menuform.Form = Ext.extend(Ext.form.FormPanel,{
        border: false,
        closeable: true,
        frame: true,
        labelWidth: 100,
        waitMsg:'Loading...',
        url: '<?= site_url("menu/update_row") ?>',
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
            menuform.Form.superclass.constructor.call(this, config);
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
                        name: 'kd_menu'
                    },
                    {
                        xtype: 'combo',
                        fieldLabel: 'Parent Menu <span class="asterix">*</span>',
                        name: 'kd_parent_menu',
                        id: 'id_parent_menu',
                        anchor: '90%',
                        store: strcmbmenu,
                        displayField: 'menu_text',    
                        valueField:'kd_menu',                    
                        queryMode: 'remote',
                        typeAhead: true,
                        //                        forceSelection: true,
                        triggerAction: 'all',
                        //                        selectOnFocus : true,
                        listeners:{
                            select:function(cmb){                                   
                                kodeparentmenu=cmb.getValue();                                
                            }                                                   
                        }
                    },
                    {
                        type: 'textfield',
                        fieldLabel: 'Menu ID <span class="asterix">*</span>',
                        name: 'menu_id',
                        allowBlank: false,
                        id: 'id_menu_id',
                        anchor: '90%',
                        maxLength: 40
//                        ,
//                        style:'text-transform: lowercase'					       
                    
                    },
                    {
                        type: 'textfield',
                        fieldLabel: 'Menu Text <span class="asterix">*</span>',
                        name: 'menu_text',
                        allowBlank: false,
                        id: 'id_menu_text',
                        anchor: '90%',
                        maxLength: 40
//                        ,
//                        style:'text-transform: uppercase'					       
                    
                    },
                    {
                        xtype: 'checkbox',
                        fieldLabel: 'Menu Leaf <span class="asterix">*</span>',
                        boxLabel: 'Check Jika Leaf',
                        name: 'menu_leaf',                        
                        id: 'id_menu_leaf'
                        ,anchor: '90%'
                    },
                    {
                        xtype: 'checkbox',
                        fieldLabel: 'Menu Expanded <span class="asterix">*</span>',
                        boxLabel: 'Check Jika Expand',
                        name: 'menu_expanded',                        
                        id: 'id_menu_expanded',
                        anchor: '90%'
                        // menu expanded
                    },
                    {
                        type: 'textfield',
                        fieldLabel: 'Description <span class="asterix">*</span>',
                        name: 'menu_description',
                        allowBlank: false,
                        id: 'id_menu_description',
                        anchor: '90%',
                        maxLength: 40,
                        style:'text-transform: uppercase'					       
                    
                    }
                    
                ],
                buttons: [{
                        text: 'Submit',
                        id: 'btnsubmitmenu',
                        formBind: true,
                        scope: this,
                        handler: this.submit
                    }, {
                        text: 'Reset',
                        id: 'btnresetmenu',
                        scope: this,
                        handler: this.reset
                    }, {
                        text: 'Close',
                        id: 'btnclosemenu',
                        scope: this,
                        handler: function(){
                            winaddmenu.hide();
                        }
                    }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            menuform.Form.superclass.initComponent.apply(this, arguments);
        },
        onRender: function(){
        
            // call parent
            menuform.Form.superclass.onRender.apply(this, arguments);
            
            // set wait message target
            this.getForm().waitMsgTarget = this.getEl();
            
            // loads form after initial layout
            // this.on('afterlayout', this.onLoadClick, this, {single:true});
        
        },
        reset: function(){
            this.getForm().reset();
        },
        submit: function(){
            kodeparentmenu=this.getForm().findField('kd_parent_menu').getValue();
            mnleaf=this.getForm().findField('menu_leaf').getValue();
            if(!mnleaf){
                mnleaf=null;
            }
            mnexpand=this.getForm().findField('menu_expanded').getValue();
            if(!mnexpand){
                mnexpand=null;
            }
            
            this.getForm().submit({
                url: this.url,
                scope: this,
                success: this.onSuccess,
                failure: this.onFailure,
                params: {
                    cmd: 'save' ,
                    kdparent:kodeparentmenu,
                    menuleaf:mnleaf,
                    menuexpanded:mnexpand
                    
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
            
            
            strmenu.reload();
            Ext.getCmp('id_formaddmenu').getForm().reset();
            winaddmenu.hide();
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
    Ext.reg('formaddmenu', menuform.Form);
    
    var winaddmenu = new Ext.Window({
        id: 'id_winaddmenu',
        closeAction: 'hide',
        width: 450,
        height: 350,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_formaddmenu',
            xtype: 'formaddmenu'
        },
        onHide: function(){
            Ext.getCmp('id_formaddmenu').getForm().reset();
        },
        onShow: function(){            
            strcmbmenu.reload();
        }
    });
    var strmenu = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_menu',
                'kd_parent_menu',
                'menu_id',
                'menu_text',
                {name: 'menu_leaf', type: 'bool'},
                {name: 'menu_expanded', type: 'bool'},                
                'menu_description',
                'aktif'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("menu/get_rows") ?>',
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
    


    
    var searchmenu = new Ext.app.SearchField({
        store: strmenu,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchmenu'
    });
    
    var tbmenu = new Ext.Toolbar({
        items: [{
                text: 'Add',
                icon: BASE_ICONS + 'add.png',
                onClick: function(){				
                    Ext.getCmp('btnresetmenu').show();
                    Ext.getCmp('btnsubmitmenu').setText('Submit');
                    winaddmenu.setTitle('Add Form');
                    winaddmenu.show();                
                }            
            }, '-', searchmenu]
    });
    
    var cbGridmenu = new Ext.grid.CheckboxSelectionModel();
    var actionmenu = new Ext.ux.grid.RowActions({
        header :'Edit',
        autoWidth: false,
        width: 30,
        actions:[{iconCls: 'icon-edit-record', qtip: 'Edit'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });	
    var actionmenudel = new Ext.ux.grid.RowActions({
        header: 'Delete',
        autoWidth: false,
        width: 40,
        actions:[{iconCls: 'icon-delete-record', qtip: 'Delete'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });
        
    actionmenu.on('action', function(grid, record, action, row, col) {
        var kd_menu = record.get('kd_menu');
        switch(action) {
            case 'icon-edit-record':	        	
                editmenu(kd_menu);
                break;
            case 'icon-delete-record':
                Ext.Msg.show({
                    title: 'Confirm',
                    msg: 'Are you sure delete selected row ?',
                    buttons: Ext.Msg.YESNO,
                    fn: function(btn){
                        if (btn == 'yes') {
                            Ext.Ajax.request({
                                url: '<?= site_url("menu/delete_row") ?>',
                                method: 'POST',
                                params: {
                                    kd_menu: kd_menu
                                },
                                callback:function(opt,success,responseObj){
                                    var de = Ext.util.JSON.decode(responseObj.responseText);
                                    if(de.success==true){
                                        strmenu.reload();
                                        strmenu.load({
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

        var checkColumn1 = new Ext.grid.CheckColumn({
            header: 'Menu Leaf',
            dataIndex: 'menu_leaf',
            width: 100
        });
        var checkColumn2 = new Ext.grid.CheckColumn({
            header: 'Menu Expanded',
            dataIndex: 'menu_expanded',
            width: 100
        });
    
        var menu = new Ext.grid.EditorGridPanel({
            //id: 'id-menu-gridpanel',
            id: 'menu',
			frame: true,
            border: true,
            stripeRows: true,
            sm: cbGridmenu,
            store: strmenu,
            closable:true,
            loadMask: true,
            style: 'margin:0 auto;',
            height: 450,
            columns: [actionmenu,actionmenudel,{
                    header: "Kode",
                    dataIndex: 'kd_menu',
                    sortable: true,
                    width: 100
                },{
                    header: "Parent",
                    dataIndex: 'kd_parent_menu',
                    sortable: true,
                    width: 100
                },{
                    header: "Menu ID",
                    dataIndex: 'menu_id',
                    sortable: true,
                    width: 100
                },{
                    header: "Menu Text",
                    dataIndex: 'menu_text',
                    sortable: true,
                    width: 100
                },checkColumn1
                ,
                checkColumn2,
                {
                    header: "Description",
                    dataIndex: 'menu_description',
                    sortable: true,
                    width: 150
                },{
                    header: "Aktif",
                    dataIndex: 'aktif',
                    sortable: true,
                    width: 100
                }],
            plugins: [actionmenu,actionmenudel,checkColumn1,checkColumn2],
            listeners:{
                'rowdblclick': function(){
				
                    var sm = menu.getSelectionModel();
                
                    var sel = sm.getSelections();
                
                    if (sel.length > 0) {
                        Ext.getCmp('btnresetmenu').hide();
                        Ext.getCmp('btnsubmitmenu').setText('Update');
                        winaddmenu.setTitle('Edit Form');
                        Ext.getCmp('id_formaddmenu').getForm().load({
                            url: '<?= site_url("menu/get_row") ?>',
                            params: {
                                id: sel[0].get('kd_menu'),
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
                        winaddmenu.show();
                    }
                 
                }
            },
            tbar: tbmenu,
            bbar: new Ext.PagingToolbar({
                pageSize: ENDPAGE,
                store: strmenu,
                displayInfo: true
            })
                
        });
    /**	
	var menupanel = new Ext.FormPanel({
	 	id: 'menu',
		border: false,
        frame: false,
		autoScroll:true,	
        items: [menu]
	});
	**/
        function editmenu(kd_menu){
            Ext.getCmp('btnresetmenu').hide();
            Ext.getCmp('btnsubmitmenu').setText('Update');
            winaddmenu.setTitle('Edit Form');
            Ext.getCmp('id_formaddmenu').getForm().load({
                url: '<?= site_url("menu/get_row") ?>',
                params: {
                    id: kd_menu,
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
            winaddmenu.show();
        }
        function deletemenu(){		
            var sm = menu.getSelectionModel();
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
                                data = data + sel[i].get('kd_menu') + ';';
                            }
                        
                            Ext.Ajax.request({
                                url: '<?= site_url("menu/delete_rows") ?>',
                                method: 'POST',
                                params: {
                                    postdata: data
                                },
                                callback:function(opt,success,responseObj){
                                    var de = Ext.util.JSON.decode(responseObj.responseText);
                                    if(de.success==true){
                                        strmenu.reload();
                                        strmenu.load({
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