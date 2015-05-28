<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    Ext.ns('usergroupform');
	
    var strcbusergroup = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_menu',
                'kd_group',
                'menu_text'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("usergroup/get_cbusergroup") ?>',
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
	
	
    var cbmenutextusergroup = new Ext.form.ComboBox({
        fieldLabel: 'Group Menu <span class="asterix">*</span>',
        id: 'id_cbkd_menu',
        store: strcbusergroup,
        valueField: 'kd_menu',
        displayField: 'menu_text',
        typeAhead: true,
        mode: 'local',
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_menu',
    });
    
    
	
    var strusergroup = new Ext.data.Store({
        autoLoad:false,
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_group',
                'nama_group',
                'deskripsi'				
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("usergroup/get_menu") ?>',
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
    
    var strgroupedit = new Ext.data.Store({
//        autoLoad:true,
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_menu',
                'parent',               
                'text',
                {name: 'sel', type: 'bool'} ,
                {name: 'vie', type: 'bool'} ,
                {name: 'ins', type: 'bool'} ,
                {name: 'upd', type: 'bool'} ,
                {name: 'del', type: 'bool'}

            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("usergroup/get_all_menu") ?>',
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
    var cbGridgroup = new Ext.grid.CheckboxSelectionModel({
        id:'id_sel',
        singleSelect:false 
    }
);    
    //    var editorgroup = new Ext.ux.grid.RowEditor({
    //        saveText: 'Update'
    //    });
    var checks = new Ext.grid.CheckColumn({
        header:'Select Menu',      
        id:'id_checks',       
        dataIndex: 'sel',             
        width: 55
      
    });
    var checkv = new Ext.grid.CheckColumn({
        header:'view',     
        id:'id_checkv',
        dataIndex: 'vie',             
        width: 55
    });
    var checki = new Ext.grid.CheckColumn({
        header:'Insert',      
        id:'id_checki',       
        dataIndex: 'ins',             
        width: 55
    });
    var checku = new Ext.grid.CheckColumn({
        header:'Update',    
        id:'id_checku',
        dataIndex: 'upd',             
        width: 55
    });
    var checkd = new Ext.grid.CheckColumn({
        header:'Delete',   
        id:'id_checkd',
        dataIndex: 'del',             
        width: 55
       
    });
    var colmodelgroup=new Ext.grid.ColumnModel({
        columns:[
            //            cbGridgroup,
            {
                header:'Kode Menu',                          
                dataIndex: 'kd_menu',
                width: 100                
            },{header:'Parent',             
                dataIndex: 'parent',
                width: 100                
            }
            //            ,{header:'id',             
            //             dataIndex: 'id',
            //             width: 100                
            //            }
            ,{header:'text',             
                dataIndex: 'text',
                width: 100                
            },checks,checkv,checki,checku,checkd
            
        ]
        
    });
    var treemenusetting = new Ext.grid.EditorGridPanel({
        store: strgroupedit,
        cm: colmodelgroup,        
        width: 600,
        height: 300,        
        title: 'Menu',
        frame: true,
        //        sm:cbGridgroup,
        loadMask:true,
        // specify the check column plugin on the grid so the plugin is initialized
        plugins:[checks,checkv,checki,checku,checkd] ,
        clicksToEdit: 1,
//        tbar: [{
//                icon: BASE_ICONS + 'add.png',
//                text: 'Add',
//                handler: function(){
//                    var marr= new Array();
//                    strgroupedit.each(function(node){
//                        if (node.data.sel){                            
//                            marr.push(node.data)                 
//                        }                        
//                    });		
//                    var str= Ext.util.JSON.encode(marr);
//                    console.log(str);
//                }
//            }],
        listeners:{
            show:function(){
                
            },
            cellclick : function( Grid , rowIndex, columnIndex, e ) {
                //                console.log(Grid.)
            }
        }
    });


    
    var gridusergroup = new Ext.grid.GridPanel({
        id: 'idgridusergroup',
        store: strusergroup,
        stripeRows: true,
        height: 150,		
        border:true,
        frame:true,
        listeners: {
            'rowclick': function(){              
                var sm = gridusergroup.getSelectionModel();                
                var sel = sm.getSelections(); 				
                usergroup1.store.proxy.conn.url = '<?= site_url("usergroup/get_rows_detail") ?>/' + sel[0].get('kd_group');
                usergroup1.store.reload();
                Ext.getCmp('id_kd_group1').setValue(sel[0].get('kd_group'));
            }},
        columns: [{            
                header: 'Kode Group',
                dataIndex: 'kd_group',
                width: 50
            },{            
                header: 'Nama Group',
                dataIndex: 'nama_group',
                width: 160,
                sortable: true,
            },{
                header: 'Deskripsi',
                dataIndex: 'deskripsi',
                width: 200,
            }
        ]
    });
    var kdgroupsel='';
    function reload_strgroup1( kdgroup){
            usergroup1.store.proxy.conn.url = '<?= site_url("usergroup/get_rows_detail") ?>/' + kdgroup;
            usergroup1.store.reload();
    }
    
    usergroupform.Form = Ext.extend(Ext.form.FormPanel, {
    
        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 100,
        url: '<?= site_url("usergroup/update_row") ?>',
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
            usergroupform.Form.superclass.constructor.call(this, config);
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
                        type: 'textfield',
                        fieldLabel: 'Kode Group <span class="asterix">*</span>',
                        name: 'kd_group',
                        fieldClass: 'readonly-input',
                        readOnly: true,
                        id: 'id_kd_group1',
                        maxLength: 40,
                        anchor: '90%'                
                    }
                    ,treemenusetting],
                buttons: [{
                        text: 'Submit',
                        id: 'btnsubmitusergroup',
                        formBind: true,
                        scope: this,
                        handler: this.submit
                    }, {
                        text: 'Reset',
                        id: 'btnresetusergroup',
                        scope: this,
                        handler: this.reset
                    }, {
                        text: 'Close',
                        id: 'btnCloseusergroup',
                        scope: this,
                        handler: function(){
                            winaddusergroup.hide();
                        }
                    }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            
            // call parent
            usergroupform.Form.superclass.initComponent.apply(this, arguments);
            
        } // eo function initComponent	
        ,
        onRender: function(){
        
            // call parent
            usergroupform.Form.superclass.onRender.apply(this, arguments);
            
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
            var marr= new Array();
            strgroupedit.each(function(node){
                if (node.data.sel){                            
                    marr.push(node.data)                 
                }                        
            });		
            var str= Ext.util.JSON.encode(marr);
            console.log(str);
            this.getForm().submit({
                url: this.url,
                scope: this,
                success: this.onSuccess,
                failure: this.onFailure,
                params: {
                    cmd: 'save',
                    data:str
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
            
            
            //strusergroup.reload();
//            strusergroup1.reload({params:{kd_group:kdgroupsel}});
               reload_strgroup1(kdgroupsel);
            Ext.getCmp('id_formaddusergroup').getForm().reset();
            winaddusergroup.hide();
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
    Ext.reg('formaddusergroup', usergroupform.Form);
    
    var winaddusergroup = new Ext.Window({
        id: 'id_winaddusergroup',
        closeAction: 'hide',
        width: 620,
        height: 500,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_formaddusergroup',
            xtype: 'formaddusergroup'
        },
        onHide: function(){
            Ext.getCmp('id_formaddusergroup').getForm().reset();
        }
    });
    
    /* START GRID */    
	
    var strusergroup1 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_group',
                'kd_menu',
                {name: 'bview', type: 'boolean'},
                {name: 'binsert', type: 'boolean'},
                {name: 'bupdate', type: 'boolean'},
                {name: 'bdelete', type: 'boolean'},
                // 'binsert',
                // 'bupdate',
                // 'bdelete',
                'menu_text',
                'menu_description'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("usergroup/get_rows_detail") ?>',
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
    var searchusergroup = new Ext.app.SearchField({
        store: strusergroup,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchusergroup'
    });
    
    // top toolbar
    var tbusergroup = new Ext.Toolbar({
        items: [{
                text: 'Add',
                icon: BASE_ICONS + 'add.png',
                onClick: function(){
                    
                    //strcbtypeP.load(); 				Ext.getCmp('id_cbtypeP').setDisabled(false);			 				Ext.getCmp('id_cbtypeP').setValue('');			
                    var idgroup = Ext.getCmp('id_kd_group1').getValue();
                    if (!idgroup){
                        Ext.Msg.show({
                            title: 'Kode Group',
                            msg: 'Group Not Selected, Please Select Group!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                        });
                        return;
                    }
                    Ext.getCmp('btnresetusergroup').show();
                    Ext.getCmp('btnsubmitusergroup').setText('Submit');
                    winaddusergroup.setTitle('Add Form');
                    kdgroupsel = Ext.getCmp('id_kd_group1').getValue();
                    
                    strgroupedit.reload({params:{kd_group:kdgroupsel}});
                    
                    
                    winaddusergroup.show();                
                }            
            }, '-', searchusergroup]
    });
	
    // checkbox grid
    var cbGrid = new Ext.grid.CheckboxSelectionModel();
    
    var actionusergroupdel = new Ext.ux.grid.RowActions({
        header:'Delete',
        autoWidth: false,
        width: 40,
        actions:[	      
            {iconCls: 'icon-delete-record', qtip: 'Delete'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });	
	
    actionusergroupdel.on('action', function(grid, record, action, row, col) {
        var id_kdgroup = record.get('kd_group');
        kdgroupsel=id_kdgroup;
        var id_kdmenu = record.get('kd_menu');
        if (action=='icon-delete-record'){
            Ext.Msg.show({
                title: 'Confirm',
                msg: 'Are you sure delete selected row ?',
                buttons: Ext.Msg.YESNO,
                fn: function(btn){
                    if (btn == 'yes') {
                        Ext.Ajax.request({
                            url: '<?= site_url("usergroup/delete_row") ?>',
                            method: 'POST',
                            params: {
                                kd_group: id_kdgroup,
                                kd_menu:id_kdmenu
                            },
                            callback:function(opt,success,responseObj){
                                var de = Ext.util.JSON.decode(responseObj.responseText);
                                if(de.success==true){
                                    reload_strgroup1(kdgroupsel);
//                                strusergroup1.reload({params:{kd_group:kdgroupsel}});
//                                    strusergroup.reload();
//                                    strusergroup.load({
//                                        params: {
//                                            start: STARTPAGE,
//                                            limit: ENDPAGE
//                                        }
//                                    });
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
  	
    // grid
    var usergroup1 = new Ext.grid.GridPanel({
        id: 'usergroup1',
        store: strusergroup1,
        stripeRows: true,
        height: 250,		
        border:true,
        frame:true,
        columns: [actionusergroupdel,{
                type: 'textfield',
                header: 'Kode Group',
                dataIndex: 'kd_group',
                width: 120,
                sortable: true,
                valueField: 'kd_group',
                displayField: 'kd_group',
                typeAhead: true,
                allowBlank: false,				
                //editable: false,
                hiddenName: 'kd_group',
            },{
                type: 'textfield',
                header: 'Group Menu',
                dataIndex: 'menu_text',
                width: 150,
                format: '0,0',
                align:'left'
            },{
                xtype: 'booleancolumn',
                header: 'View',
                dataIndex: 'bview',
                align: 'center',
                width: 50,
                trueText: 'Ya',
                falseText: 'Tidak'
            },{
                xtype: 'booleancolumn',
                header: 'Insert',
                dataIndex: 'binsert',
                align: 'center',
                width: 50,
                trueText: 'Ya',
                falseText: 'Tidak'
            },{
                xtype: 'booleancolumn',
                header: 'Update',
                dataIndex: 'bupdate',
                align: 'center',
                width: 50,
                trueText: 'Ya',
                falseText: 'Tidak'
            },{
                xtype: 'booleancolumn',
                header: 'Delete',
                dataIndex: 'bdelete',
                align: 'center',
                width: 50,
                trueText: 'Ya',
                falseText: 'Tidak'
            }],
        plugins: [actionusergroupdel],
        tbar: tbusergroup
    });
    
    var usergroup = new Ext.FormPanel({
        id: 'usergroup',
        border: false,
        frame: true,
        autoScroll:true,		
        bodyStyle:'padding-right:20px;',
        labelWidth: 130,
        items:[gridusergroup,usergroup1]
	});
	
    function deleteusergroup(){		
        var sm = usergroup.getSelectionModel();
        var sel = sm.getSelections();
        if (sel.length > 0) {
            Ext.Msg.show({
                title: 'Confirm',
                msg: 'Are you sure delete selected row ?',
                buttons: Ext.Msg.YESNO,
                fn: function(btn){
                    if (btn == 'yes') {
                    
                        var data = sel[i].get('kd_menu');
                        var test = sel[i].get('kd_group')
                        
                        Ext.Ajax.request({
                            url: '<?= site_url("usergroup/delete_row") ?>',
                            method: 'POST',
                            params: {
                                kd_group:test,
                                kd_menu: data
                            },
                            callback:function(opt,success,responseObj){
                                var de = Ext.util.JSON.decode(responseObj.responseText);
                                if(de.success==true){
                                    strusergroup1.reload();
                                    strusergroup1.load({
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
        
    };
</script>