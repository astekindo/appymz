<?php

if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    
    //==============
    var streditakunjenisvoucher = new Ext.data.Store({
        autoSave:false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'sel', allowBlank: false, type: 'bool'},
                {name: 'kd_akun', allowBlank: false, type: 'string'},
                {name: 'nama_akun', allowBlank: false, type: 'string'}
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("account_jenis_voucher/get_rows_akun_edit") ?>',
            method: 'POST'
        }),
        writer: new Ext.data.JsonWriter(
        {
            encode: true,
            writeAllFields: true
        }),listeners: {
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
     var checksakun_jv = new Ext.grid.CheckColumn({
        header:'Select Akun',      
        id:'id_jv_sel_akun',       
        dataIndex: 'sel',             
        width: 55      
    });	
    var gridjenisvoucherakun = new Ext.grid.GridPanel({
        store: streditakunjenisvoucher,
        stripeRows: true,
        height: 300,
        frame: true,
        border:true,
        plugins: [checksakun_jv],        
        columns: [checksakun_jv,{
                //            xtype: 'numbercolumn',
                
                header: 'Kode Akun',
                dataIndex: 'kd_akun',
                width: 80,
                format: '0',
                sortable: true				                
			
            },{
                header: 'Nama Akun',
                dataIndex: 'nama_akun',
                width: 300                
            }
            
        ]
    });
//    gridjenisvoucherakun.getSelectionModel().on('selectionchange', function(sm){
//        gridjenisvoucherakun.removeBtn.setDisabled(sm.getCount() < 1);	 });
    
    Ext.ns('jenisvoucher_form');
    jenisvoucher_form.Form = Ext.extend(Ext.form.FormPanel, {    
        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 120,
        url: '<?= site_url("account_jenis_voucher/update_row") ?>',
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
            jenisvoucher_form.Form.superclass.constructor.call(this, config);
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
                        name: 'kd_jenis_voucher',
                        id: 'id_kd_jenis_voucher'
                    },{
                        xtype: 'textfield',
                        fieldLabel: 'Title <span class="asterix">*</span>',
                        name: 'title',
                        allowBlank: false,
                        id: 'id_title',
                        maxLength: 40,
                        anchor: '90%'                
                    }
                    ,{
                        xtype:          'combo',
                        fieldLabel:		'Debet/Kredit <span class="asterix">*</span>',
                        mode:           'local', 
                        value:          '',
                        triggerAction:  'all',
                        forceSelection: true,
                        editable:       false,
                        name:           'dk',
                        id:           	'id_dk',
                        hiddenName:     'dk',
                        displayField:   'name',
                        valueField:     'value',
                        anchor:			'60%',
                        store:          new Ext.data.JsonStore({
                            fields : ['name', 'value'],
                            data   : [
                                {name : 'Debet', value: 'd'},
                                {name : 'Kredit', value: 'k'},
                                {name : 'All', value: 'dk'}
                            ]
                        }),
                        allowBlank: false
                                                        
                    }
                    ,{
                        xtype: 'checkbox',
                        fieldLabel: 'Auto Posting',
                        name: 'auto_posting_voucher',          
                        id: 'id_auto_posting_voucher',                
                        anchor: '90%',
                        checked: false
                    },                    
                    gridjenisvoucherakun
                    ],
                buttons: [{
                        text: 'Submit',
                        id: 'btnsubmitjenisvoucher',
                        formBind: true,
                        scope: this,
                        handler: this.submit
                    }, {
                        text: 'Reset',
                        id: 'btnresetjenisvoucher',
                        scope: this,
                        handler: this.reset
                    }, {
                        text: 'Close',
                        id: 'btnClosejenisvoucher',
                        scope: this,
                        handler: function(){
                            winaddjenisvoucher.hide();
                        }
                    }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            
            // call parent
            jenisvoucher_form.Form.superclass.initComponent.apply(this, arguments);
            
        } // eo function initComponent	
        ,
        onRender: function(){
        
            // call parent
            jenisvoucher_form.Form.superclass.onRender.apply(this, arguments);
            
            // set wait message target
            this.getForm().waitMsgTarget = this.getEl();
            
            // loads form after initial layout
            // this.on('afterlayout', this.onLoadClick, this, {single:true});
        
        } // eo function onRender
        ,
        reset: function(){
          
            gridjenisvoucherakun.getStore().reload();
            this.getForm().reset();
        },
        submit: function(){
            var cmd='';
            if(Ext.getCmp('btnsubmitjenisvoucher').getText()==='Submit'){
                cmd='insert';
            }else{
                cmd='update';
            }
            var arr_akuntrx= new Array();
            streditakunjenisvoucher.each(function(node){    
                if (node.data.sel){                            
                    arr_akuntrx.push(node.data);                 
                }                       
                            
                                     
            });	
           
            var auto_posting='off';
            auto_posting=Ext.getCmp('id_auto_posting_voucher').getValue();
            this.getForm().submit({
                url: this.url,
                scope: this,
                success: this.onSuccess,
                failure: this.onFailure,
                params: {
                    cmd: cmd,                    
                    data:Ext.util.JSON.encode(arr_akuntrx),
                    auto_posting_voucher:auto_posting                    
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
            
            
            str_headjenisvoucher.reload();
            str_detailjenisvoucher.reload();
            Ext.getCmp('id_formaddjenisvoucher').getForm().reset();
            winaddjenisvoucher.hide();
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
    Ext.reg('formaddjenisvoucher', jenisvoucher_form.Form);
  
    var winaddjenisvoucher = new Ext.Window({
        id: 'id_winaddjenisvoucher',
        closeAction: 'hide',
        width: 650,
        height: 500,
        layout: 'fit',
        border: false,
        items: [{
                id: 'id_formaddjenisvoucher',
                xtype: 'formaddjenisvoucher'
            }],
        onHide: function(){
            Ext.getCmp('id_formaddjenisvoucher').getForm().reset();
        }
    });
    
    var str_headjenisvoucher = new Ext.data.Store({
        autoLoad:false,
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_jenis_voucher',
                'title',
                'dk',
                'auto_posting_voucher'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("account_jenis_voucher/get_rows") ?>',
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
    var tb_headjenisvoucher = new Ext.Toolbar({
        items: [{
                text: 'Add',
                icon: BASE_ICONS + 'add.png',
                onClick: function(){	
//                    //strcbtypeP.load(); 				Ext.getCmp('id_cbtypeP').setDisabled(false);			 				Ext.getCmp('id_cbtypeP').setValue('');			
                    Ext.getCmp('btnresetjenisvoucher').show();
                    Ext.getCmp('btnsubmitjenisvoucher').setText('Submit');
//                    //---
                    
                    Ext.getCmp('id_kd_jenis_voucher').setValue('');
                    Ext.getCmp('id_title').setValue('');
                    Ext.getCmp('id_dk').setValue('d');
                    Ext.getCmp('id_auto_posting_voucher').setValue(false);
                    
                    streditakunjenisvoucher.load();
                    winaddjenisvoucher.setTitle('Add Form');
                    winaddjenisvoucher.show();                
                }            
            }]
    });
    
    // row actions
    var action_headjenisvoucher_edit = new Ext.ux.grid.RowActions({
        header:'Edit',
        autoWidth: false,
        width: 40,
        actions:[
            {iconCls: 'icon-edit-record', qtip: 'Edit'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });	
    var action_headjenisvoucher_del = new Ext.ux.grid.RowActions({
        header:'Delete',
        autoWidth: false,
        width: 40,
        actions:[	      
            {iconCls: 'icon-delete-record', qtip: 'Delete'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });	
    action_headjenisvoucher_edit.on('action', function(grid, record, action, row, col) {
    if (action=='icon-edit-record'){
        Ext.getCmp('id_kd_jenis_voucher').setValue(record.get('kd_jenis_voucher'));
                    Ext.getCmp('id_title').setValue(record.get('title').trim());                            
                    Ext.getCmp('id_dk').setValue(record.get('dk').trim());
                   
                    Ext.getCmp('id_auto_posting_voucher').setValue(record.get('auto_posting_voucher'));
                    
                    Ext.getCmp('btnresetjenisvoucher').show();
                    Ext.getCmp('btnsubmitjenisvoucher').setText('Edit');
//                    Ext.getCmp('btnsubmitjenisvoucher').show();
                    
                    streditakunjenisvoucher.load({params:{query:record.get('kd_jenis_voucher')}});
                    winaddjenisvoucher.setTitle('Edit Form');
                    winaddjenisvoucher.show();    
    }
        
        
    
    });
    action_headjenisvoucher_del.on('action', function(grid, record, action, row, col) {
        var kdjenisvoucher=record.get('kd_jenis_voucher');
        if (action=='icon-delete-record'){
            Ext.Msg.show({
                title: 'Confirm',
                msg: 'Are you sure delete selected row ?',
                buttons: Ext.Msg.YESNO,
                fn: function(btn){
                    if (btn == 'yes') {
                        Ext.Ajax.request({
                            url: '<?= site_url("account_jenis_voucher/delete_header") ?>',
                            method: 'POST',
                            params: {
                                kd_jenis_voucher: kdjenisvoucher
                            },
                            callback:function(opt,success,responseObj){
                                var de = Ext.util.JSON.decode(responseObj.responseText);
                                if(de.success==true){
                                    str_headjenisvoucher.reload();
                                    str_detailjenisvoucher.reload();
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
    var headjenisvoucher = new Ext.grid.GridPanel({
        id: 'idheadjenisvoucher',
        store: str_headjenisvoucher,
        stripeRows: true,
        height: 200,		
        border:true,
        frame:true,
        listeners: {
            'rowclick': function(){              
                var sm = headjenisvoucher.getSelectionModel();                
                var sel = sm.getSelections(); 				             
                detailjenisvoucher.store.reload({params:{query:sel[0].get('kd_jenis_voucher')}});
            }},
        plugins:[action_headjenisvoucher_edit,action_headjenisvoucher_del],
        columns: [action_headjenisvoucher_edit,action_headjenisvoucher_del,{            
                header: 'Kode Jenis',
                dataIndex: 'kd_jenis_voucher',
                width: 80
            },{            
                header: 'Title',
                dataIndex: 'title',
                width: 160
                
            },{
                header: 'D/K',
                dataIndex: 'dk',
                width: 50
            },{
                header: 'Auto Posting',
                dataIndex: 'auto_posting_voucher',
                width: 70
            }
        ],
        tbar: tb_headjenisvoucher,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: str_headjenisvoucher,
            displayInfo: true
        })
    });
 var str_detailjenisvoucher = new Ext.data.Store({
        autoLoad:false,
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_jenis_voucher',
                'kd_akun',
                'nama'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("account_jenis_voucher/get_rows_akun") ?>',
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

var action_detailjenisvoucher_del = new Ext.ux.grid.RowActions({
        header:'Delete',
        autoWidth: false,
        width: 40,
        actions:[	      
            {iconCls: 'icon-delete-record', qtip: 'Delete'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });	
 action_detailjenisvoucher_del.on('action', function(grid, record, action, row, col) {
        var kdjenisvoucher=record.get('kd_jenis_voucher');
        var kdakun=record.get('kd_akun');
        if (action=='icon-delete-record'){
            Ext.Msg.show({
                title: 'Confirm',
                msg: 'Are you sure delete selected row ?',
                buttons: Ext.Msg.YESNO,
                fn: function(btn){
                    if (btn == 'yes') {
                        Ext.Ajax.request({
                            url: '<?= site_url("account_jenis_voucher/delete_detail") ?>',
                            method: 'POST',
                            params: {
                                kd_jenis_voucher: kdjenisvoucher,
                                kd_akun:kdakun
                            },
                            callback:function(opt,success,responseObj){
                                var de = Ext.util.JSON.decode(responseObj.responseText);
                                if(de.success==true){
                                    str_headjenisvoucher.reload();
                                    str_detailjenisvoucher.reload();
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
 var detailjenisvoucher = new Ext.grid.GridPanel({
        id: 'iddetailjenisvoucher',
        store: str_detailjenisvoucher,
        stripeRows: true,
        height: 300,		
        border:true,
        frame:true,
        plugins:[action_detailjenisvoucher_del],
//        listeners: {
//            'rowclick': function(){              
//                var sm = gridusergroup.getSelectionModel();                
//                var sel = sm.getSelections(); 				
//                usergroup1.store.proxy.conn.url = '<?= site_url("usergroup/get_rows_detail") ?>/' + sel[0].get('kd_group');
//                usergroup1.store.reload();
//                Ext.getCmp('id_kd_group1').setValue(sel[0].get('kd_group'));
//            }},
        columns: [action_detailjenisvoucher_del,
            {            
                header: 'Kode Jenis',
                dataIndex: 'kd_jenis_voucher',
                width: 80
            },{            
                header: 'Kode Akun',
                dataIndex: 'kd_akun',
                width: 80
            },{            
                header: 'Nama Akun',
                dataIndex: 'nama',
                width: 160
                
            }
        ]
    });
var jenisvoucher = new Ext.FormPanel({
        id: 'jenis_voucher',
        border: false,
        frame: true,
        autoScroll:true,		
        bodyStyle:'padding-right:20px;',
        labelWidth: 130,
        items:[headjenisvoucher,detailjenisvoucher],
        listeners:{
            show:function(){
                str_headjenisvoucher.load();
            }
        }
	});    
</script>