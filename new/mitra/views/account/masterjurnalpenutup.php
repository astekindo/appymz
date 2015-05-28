<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">	
    
    /* START FORM */
    // twin master akun
    Ext.ns('masterjpform');
    var strcb_akun_mjp = new Ext.data.ArrayStore({
        fields: ['kd_akun'],
        data : []
    });
	
    var strgrid_akun_mjp = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_akun', 'nama','dk'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("account_entry_voucher/get_search_akun") ?>',
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
	
    var searchgrid_akun_mjp = new Ext.app.SearchField({
        store: strgrid_akun_mjp,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgrid_akun_mjp'
    });
	
	
    var grid_akun_mjp = new Ext.grid.GridPanel({
        
        //id:'id_searchgrid_akun_transaksi',
        store: strgrid_akun_mjp,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'Kode Akun',
                dataIndex: 'kd_akun',
                width: 80,
                sortable: true			
            
            },{
                header: 'Nama Akun',
                dataIndex: 'nama',
                width: 300,
                sortable: true         
            },{
                header: 'D/K',
                dataIndex: 'dk',
                width: 50,
                sortable: true         
            }],
        tbar: new Ext.Toolbar({
            items: [searchgrid_akun_mjp]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgrid_akun_mjp,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                    Ext.getCmp('emjp_kd_akun').setValue(sel[0].get('kd_akun'));
                    Ext.getCmp('emjp_nama_akun').setValue(sel[0].get('nama'));
                    Ext.getCmp('emjp_dk_akun').setValue(sel[0].get('dk'));                       
                    menu_akun_mjp.hide();
                }
            }
        }
    });
	
    var menu_akun_mjp = new Ext.menu.Menu();
    menu_akun_mjp.add(new Ext.Panel({
        title: 'Pilih Akun',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [grid_akun_mjp],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menu_akun_mjp.hide();
                }
            }]
    }));
    
    Ext.ux.TwinComboAkunmjp = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgrid_akun_mjp.load();
            menu_akun_mjp.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menu_akun_mjp.on('hide', function(){
        var sf = Ext.getCmp('id_searchgrid_akun_mjp').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgrid_akun_mjp').setValue('');
            searchgrid_akun_mjp.onTrigger2Click();
        }
    });
	
  
    //==============
    var streditakunmjp = new Ext.data.Store({
        autoSave:false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_akun', allowBlank: false, type: 'string'},
                {name: 'nama', allowBlank: false, type: 'string'},
                {name: 'dk_akun', allowBlank: false, type: 'string'},                           
                {name: 'dk_transaksi', allowBlank: false, type: 'string'},
                            
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("account_master_jp/get_rows_akun") ?>',
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
    
    
    var editorgridmjpakun= new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });
 

    var gridmjpakun = new Ext.grid.GridPanel({
        store: streditakunmjp,
        stripeRows: true,
        height: 220,
        frame: true,
        border:true,
        plugins: [editorgridmjpakun],
        tbar: [{
                icon: BASE_ICONS + 'add.png',
                text: 'Add',
                handler: function(){
                    if(Ext.getCmp('id_nama_mjp').getValue() == ''){
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Silahkan nama transaksi terlebih dulu',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK			               
                        });
                        return;
                    }
                    var rowentryakunedit = new gridmjpakun.store.recordType({
                        kd_akun : '',
                        dk_akun: '',
                        dk_transaksi:''
                    });                
                    editorgridmjpakun.stopEditing();
                    streditakunmjp.insert(0, rowentryakunedit);
                    gridmjpakun.getView().refresh();
                    gridmjpakun.getSelectionModel().selectRow(0);
                    editorgridmjpakun.startEditing(0);
                }
            },{
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                disabled: true,
                handler: function(){
                    editorgridmjpakun.stopEditing();
                    var s = gridmjpakun.getSelectionModel().getSelections();
                    for(var i = 0, r; r = s[i]; i++){
                        streditakunmjp.remove(r);
                    }
                }
            }],
        columns: [{
                //            xtype: 'numbercolumn',
                header: 'Kode Akun',
                dataIndex: 'kd_akun',
                width: 50,
                format: '0',
                sortable: true,	
                editor: new Ext.ux.TwinComboAkunmjp({
                    id: 'emjp_kd_akun',
                    store: strcb_akun_mjp,
                    mode: 'local',
                    valueField: 'kd_akun',
                    displayField: 'kd_akun',
                    typeAhead: true,
                    triggerAction: 'all',
                    allowBlank: true ,
                    editable: false,
                    hiddenName: 'kd_akun',
                    emptyText: 'Pilih Akun'
				
                })		
			
            },{
                header: 'Nama Akun',
                dataIndex: 'nama',
                width: 200,
                editor: new Ext.form.TextField({                
                    readOnly: true,
                    id: 'emjp_nama_akun'
                })
            },{
                header: 'D/K Akun',
                dataIndex: 'dk_akun',            
                width: 60,
                editor: new Ext.form.TextField({                
                    readOnly: true,
                    id: 'emjp_dk_akun'
                })            
            }
//            ,{
//                header: 'D/K Transaksi',
//                dataIndex: 'dk_transaksi',            
//                width: 120,
//                editor:{
//                
//            xtype: 'radiogroup',
//             id: 'emjp_dk_transaksi',
////            fieldLabel: 'Auto Layout',
//            items: [
//                {boxLabel: 'Debet', name: 'dk_transaksi', inputValue: 'D', checked: true},
//                {boxLabel: 'Kredit', name: 'dk_transaksi', inputValue: 'K'}
//               
//            ]
//        }
//                        
//            }
        ]
    });
    gridmjpakun.getSelectionModel().on('selectionchange', function(sm){
        gridmjpakun.removeBtn.setDisabled(sm.getCount() < 1);	 });
    
    masterjpform.Form = Ext.extend(Ext.form.FormPanel, {
    
        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 100,
        url: '<?= site_url("account_master_jp/update_row") ?>',
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
            masterjpform.Form.superclass.constructor.call(this, config);
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
                        name: 'kd_transaksi',
                        id: 'id_kd_mjp'
                    },{
                        xtype: 'textarea',
                        fieldLabel: 'Nama Transaksi <span class="asterix">*</span>',
                        name: 'nama_transaksi',
                        allowBlank: false,
                        id: 'id_nama_mjp',
                        maxLength: 40,
                        anchor: '90%'                
                    },gridmjpakun],
                buttons: [{
                        text: 'Submit',
                        id: 'btnsubmitmjp',
                        formBind: true,
                        scope: this,
                        handler: this.submit
                    }, {
                        text: 'Reset',
                        id: 'btnresetmjp',
                        scope: this,
                        handler: this.reset
                    }, {
                        text: 'Close',
                        id: 'btnClose',
                        scope: this,
                        handler: function(){
                            winaddmjp.hide();
                        }
                    }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            
            // call parent
            masterjpform.Form.superclass.initComponent.apply(this, arguments);
            
        } // eo function initComponent	
        ,
        onRender: function(){
        
            // call parent
            masterjpform.Form.superclass.onRender.apply(this, arguments);
            
            // set wait message target
            this.getForm().waitMsgTarget = this.getEl();
            
            // loads form after initial layout
            // this.on('afterlayout', this.onLoadClick, this, {single:true});
        
        } // eo function onRender
        ,
        reset: function(){
            //            Ext.getCmp('id_nama_transaksi').setReadOnly(false);
            
            this.getForm().reset();
        },
        submit: function(){
            var arr_akuntrx= new Array();
            streditakunmjp.each(function(node){                                          
                    arr_akuntrx.push(node.data);               
                                     
            });	
            var str_akuntrx= Ext.util.JSON.encode(arr_akuntrx);
            console.log(str_akuntrx);
            this.getForm().submit({
                url: this.url,
                scope: this,
                success: this.onSuccess,
                failure: this.onFailure,
                params: {
                    cmd: 'save',
                    data:Ext.util.JSON.encode(arr_akuntrx)
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
            
            
            strmjp.reload();
            Ext.getCmp('id_nama_mjp').setReadOnly(false);
            Ext.getCmp('id_formaddmjp').getForm().reset();
            winaddmjp.hide();
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
    Ext.reg('formaddmjp', masterjpform.Form);
  
    var winaddmjp= new Ext.Window({
        id: 'id_winaddmjp',
        closeAction: 'hide',
        width: 480,
        height: 420,
        layout: 'fit',
        border: false,
        items: [{
                id: 'id_formaddmjp',
                xtype: 'formaddmjp'
            }],
        onHide: function(){
            Ext.getCmp('id_formaddmjp').getForm().reset();
        }
    });
	
    var headermjp = {
        layout: 'column',
        border: false,
        items: [{
                columnWidth: 1,
                layout: 'form',
                border: false,
                frame: true,
                labelWidth: 120,
                items: [{
                        xtype: 'textfield',
                        fieldLabel: 'Kode Transaksi',
                        name: 'kd_transaksi',
                        id: 'mjp_kd_transaksi',
                        fieldClass: 'readonly-input',
                        readOnly: true,
                        value: '',
                        width: 375               
                    },{
                        xtype: 'textfield',
                        fieldLabel: 'Nama Transaksi',
                        name: 'nama_transaksi',
                        id: 'mjp_nama_transaksi',
                        fieldClass: 'readonly-input',
                        readOnly: true,
                        value: '',
                        width: 375               
                    }]
            }]
    };
    
     // checkbox grid
     // data store
     var strmjpakun = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [ 
                'kd_transaksi',
                'kd_akun',
                'nama',
                'dk_akun',
                'dk_transaksi',
				  
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("account_master_jp/get_rows_akun") ?>',
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
	
        
    var strmjp = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [ 
                'kd_transaksi',
                'nama_transaksi'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("account_master_jp/get_rows") ?>',
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
    
    var searchmjp = new Ext.app.SearchField({
        store: strmjp,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchmjp'
    });
    
    // top toolbar
    var tbmjp = new Ext.Toolbar({
        items: [{
                text: 'Add',
                icon: BASE_ICONS + 'add.png',
                onClick: function(){	                   
                    Ext.getCmp('btnresetmjp').show();
                    Ext.getCmp('btnsubmitmjp').setText('Submit');
                    //streditakunmjp.clearData();
                    streditakunmjp.removeAll();
                    winaddmjp.setTitle('Add Form');
                    winaddmjp.show();                
                }            
            }, '-', searchmjp]
    });
    
    var cbGridmjp = new Ext.grid.CheckboxSelectionModel();
    
    // row actions
    var actionmjp = new Ext.ux.grid.RowActions({
        header:'Edit',
        autoWidth: false,
        width: 30,
        actions:[
            {iconCls: 'icon-edit-record', qtip: 'Edit'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });	
    var actionmjpdel = new Ext.ux.grid.RowActions({
        header:'Delete',
        autoWidth: false,
        width: 40,
        actions:[	      
            {iconCls: 'icon-delete-record', qtip: 'Delete'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });	
	
    actionmjp.on('action', function(grid, record, action, row, col) {
        var id_transaksi = record.get('kd_transaksi');
        if (action=='icon-edit-record'){
            editmjp(id_transaksi);
        }
    });  
	
    actionmjpdel.on('action', function(grid, record, action, row, col) {
        var id_transaksi = record.get('kd_transaksi');
        if (action=='icon-delete-record'){
            Ext.Msg.show({
                title: 'Confirm',
                msg: 'Are you sure delete selected row ?',
                buttons: Ext.Msg.YESNO,
                fn: function(btn){
                    if (btn == 'yes') {
                        Ext.Ajax.request({
                            url: '<?= site_url("account_master_jp/delete_row") ?>',
                            method: 'POST',
                            params: {
                                kd_transaksi: id_transaksi
                            },
                            callback:function(opt,success,responseObj){
                                var de = Ext.util.JSON.decode(responseObj.responseText);
                                if(de.success==true){
                                    strmjp.reload();
                                    strmjp.load({
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
    });  
    
    var westmjp =  new Ext.grid.EditorGridPanel({
        //        flex:1,
        region:'west',
        id: 'id-mjp-gridpanel',
        frame: true,
        border: true,
        split:true,
        stripeRows: true,
        sm: cbGridmjp,
        store: strmjp,
        loadMask: true,
        style: 'margin:0 auto;',
        height: 250,
        width: 400,
        columns: [actionmjp,actionmjpdel,{
                header: "Kode Transaksi",
                dataIndex: 'kd_transaksi',
                sortable: true,
                width: 90
            },{
                header: "Nama Transaksi",
                dataIndex: 'nama_transaksi',
                sortable: true,
                width: 200
            }],
        plugins: [actionmjp, actionmjpdel],
        listeners: {
            'rowclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                var kdtrans=null;   
                if (sel.length > 0) {
                    kdtrans=sel[0].get('kd_transaksi'); 
                }
                strmjpakun.reload({params:{query:kdtrans}});				
            },
            'rowdblclick': function(){				
                var sm = westmjp.getSelectionModel();                
                var sel = sm.getSelections();                
                if (sel.length > 0) {
                    edittransaksi(sel[0].get('kd_transaksi'),sel[0].get('nama_transaksi'));                    
                }                 
            }          
        },
        tbar: tbmjp,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strmjp,
            displayInfo: true
        })
    });
    
    var centermjp = new Ext.grid.GridPanel({
        //        flex:2, 
        region:'center',
        id: 'idgridmjp',
        store: strmjpakun,
        stripeRows: true,
        height: 250,		
        border:true,
        frame:true,
        columns: [
            {
                header: "Kode Transaksi",
                dataIndex: 'kd_transaksi',
                hidden:true,
                sortable: true,
                width: 90
            },
            {
                header: "Kode Akun",
                dataIndex: 'kd_akun',
                sortable: true,
                width: 90
            },{
                header: "Nama Akun",
                dataIndex: 'nama',
                sortable: true,
                width: 200
            },{
                header: "Akun D/K",
                dataIndex: 'dk_akun',
                sortable: true,
                width: 80
            },{
                header: "Posting D/K",
                dataIndex: 'dk_transaksi',
                sortable: true,
                //		listeners: {
                //			'rowclick': function(){			
                //				var sm = this.getSelectionModel();
                //				var sel = sm.getSelections();
                //				if (sel.length > 0) {
                //					Ext.getCmp('trans_kd_transaksi').setValue(sel[0].get('kd_transaksi'));
                //					Ext.getCmp('trans_nama_transaksi').setValue(sel[0].get('nama_transaksi'));
                //				}
                //		}
                //            }
                width: 80
            }
        ]
        //		
    });
    
   var masterjp_form = new Ext.FormPanel({
        id: 'masterjurnalpenutup',
        border: false,
        frame: true,
        autoScroll:true, 
        monitorValid: true,       
        bodyStyle:'padding-right:20px;',
        labelWidth: 130,
        layout: 'border',
        items: [westmjp,centermjp]     
    });
    
    
    function editmjp(id_transaksi,nama_transaksi){
        // Ext.getCmp('id_nama_transaksi').setReadOnly(true);
        //strcbtypeP.load();
        //Ext.getCmp('id_cbtypeP').setDisabled(true);
        //Ext.getCmp('id_cbtypeP').setValue(type_parameter);
        Ext.getCmp('btnresetmjp').hide();		
        Ext.getCmp('btnsubmitmjp').setText('Update');
        winaddmjp.setTitle('Edit Form');
        streditakunmjp.clearData();
        streditakunmjp.reload({params:{query:id_transaksi}});	
        Ext.getCmp('id_formaddmjp').getForm().load({
            url: '<?= site_url("account_master_jp/get_row") ?>',
            params: {
                id: id_transaksi,
                cmd: 'POST'
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
	
        
        // streditakuntransaksi.reload({params:{query:id_transaksi}});
        winaddmjp.show();
    }
	
    function deletetransaksi(){		
        var sm = masterjp_form.getSelectionModel();
        var sel = sm.getSelections();
        if (sel.length > 0) {
            Ext.Msg.show({
                title: 'Confirm',
                msg: 'Are you sure delete selected row ?',
                buttons: Ext.Msg.YESNO,
                fn: function(btn){
                    if (btn == 'yes') {
                    
                        var data = sel[i].get('kd_transaksi');
                        
                        Ext.Ajax.request({
                            url: '<?= site_url("account_master_jp/delete_row") ?>',
                            method: 'POST',
                            params: {
                                kd_transaksi: data
                            },
                            callback:function(opt,success,responseObj){
                                var de = Ext.util.JSON.decode(responseObj.responseText);
                                if(de.success==true){
                                    strmjp.reload();
                                    strmjp.load({
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