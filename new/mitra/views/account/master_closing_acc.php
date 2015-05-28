<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<script type="text/javascript"> 
    
   
    var strthbltype = createStoreArray([{name:'kode'},{name:'descript'}], [['1','Bulan'],['2','Tahun']]);
    var cmbthbltype = new Ext.form.ComboBox({    
        fieldLabel: 'Closing Type',
        id: 'cmb_thbltype',
        name:'thbltype',
        allowBlank:false,
        store: strthbltype
        ,valueField:'kode'
        ,displayField:'descript'
        ,mode:'local',
        forceSelection: true,
        triggerAction: 'all',
        anchor: '90%'
        ,
        listeners: {
            select: function(combo, record, index){
                var vs=combo.getValue();
                var vb=Ext.getCmp('cmb_jenisclose').getValue();
                if(vs=='2'){
                    if(vb=='LRBL' || vb=='LRTH'){ 
                        Ext.getCmp('mca_akunjenis').setDisabled(false);
                    }else{
                        Ext.getCmp('mca_akunjenis').setValue(null);
                        Ext.getCmp('id_nmakunjenis').setValue(null);
                        Ext.getCmp('mca_akunjenis').setDisabled(true);
                    }
                }else{
                    Ext.getCmp('mca_akunjenis').setValue(null);
                        Ext.getCmp('id_nmakunjenis').setValue(null);
                    Ext.getCmp('mca_akunjenis').setDisabled(true);
                }
            }
        }
    
    });
    
    var strjenisclosing = createStoreArray([{name:'kode'},{name:'descript'}], 
    [
        ['LRBL','LabaRugi Bulan Berjalan'],
        ['LRTH','LabaRugi Tahun Berjalan'],
        ['B','Biaya'],
        ['P','Pendapatan']
    ]);
    
    var cmbjenisclose = new Ext.form.ComboBox({    
        fieldLabel: 'Jenis',
        id: 'cmb_jenisclose',
        name:'jenis',
        allowBlank:false,
        store: strjenisclosing
        ,valueField:'kode'
        ,displayField:'descript'
        ,mode:'local',
        forceSelection: true,
        triggerAction: 'all',
        anchor: '90%'
        ,listeners: {
            select : function( combo, record, index ){
                var vs=combo.getValue();
                var vb=Ext.getCmp('cmb_thbltype').getValue();
                
                if(vs=='LRBL' || vs=='LRTH'){                    
                    if(vb=='1'){
                        Ext.getCmp('mca_akunjenis').setValue(null);
                        Ext.getCmp('id_nmakunjenis').setValue(null);
                        Ext.getCmp('mca_akunjenis').setDisabled(true);
                    }else{
                        Ext.getCmp('mca_akunjenis').setDisabled(false);
                    }
                }else{
                    Ext.getCmp('mca_akunjenis').setValue(null);
                        Ext.getCmp('id_nmakunjenis').setValue(null);
                    Ext.getCmp('mca_akunjenis').setDisabled(true);
                }
    
            }
        }
    
    });
    var strakunjenis=createStoreData(['kd_akun', 'nama','dk'], '<?= site_url("account_master_account/get_akun_twin") ?>');
    var search_akun_jenis=createSearchField('id_search_akun_jenis', strakunjenis, 350);
    var strcmbakunjenis =createStoreArray(['kd_akun'],[]);
    var grid_akun_jenis = new Ext.grid.GridPanel({
        
        //id:'id_searchgrid_akun_transaksi',
        store: strakunjenis,
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
            items: [search_akun_jenis]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strakunjenis,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {		
                    var vs=Ext.getCmp('cmb_thbltype').getValue();
                    var vb=Ext.getCmp('cmb_jenisclose').getValue();
                    var kdakun=sel[0].get('kd_akun');
                    if(vs=='2'){
                        if(vb=='LRBL' || vb=='LRTH'){ 
                            Ext.getCmp('mca_akunjenis').setDisabled(false);
                            Ext.getCmp('mca_akunjenis').setValue(kdakun);
                            Ext.getCmp('id_nmakunjenis').setValue(sel[0].get('nama'));
                        }else{
                            Ext.getCmp('mca_akunjenis').setDisabled(true);
                        }
                    }else{
                        Ext.getCmp('mca_akunjenis').setDisabled(true);
                    }
                    
                    
                    
                    menu_akun_jenis.hide();
                }
            }
        }
    });
    var menu_akun_jenis = new Ext.menu.Menu();
    setPanelMenu(menu_akun_jenis, 'Pilih Akun', 400, 300, grid_akun_jenis, function(){
        menu_akun_jenis.hide();
    }, function(){
        var sf = Ext.getCmp('id_search_akun_jenis').getValue();
        if( sf != ''){
            Ext.getCmp('id_search_akun_jenis').setValue('');
            Ext.getCmp('id_search_akun_jenis').onTrigger2Click();
        }
    });
    
    Ext.ux.TwinComboAkunJenis = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strakunjenis.load();
            menu_akun_jenis.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    
    var cmb_akunjenis = new Ext.ux.TwinComboAkunJenis({
        fieldLabel: 'Akun Jenis',
        id: 'mca_akunjenis',
        store: strcmbakunjenis,
        mode: 'local',
        valueField: 'kd_akun',
        displayField: 'kd_akun',
        typeAhead: true,
        triggerAction: 'all',
        //        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_akun_jenis',
        emptyText: 'Pilih Akun Jenis',
        //        listeners:{
        //            change:function(){
        //                
        //            }
        //            
        //        }
    });
    
    var strakunposting=createStoreData(['kd_akun', 'nama','dk'], '<?= site_url("account_master_account/get_akun_twin") ?>');
    var search_akun_posting=createSearchField('id_search_akun_posting', strakunposting, 350);
    var strcmbakunposting =createStoreArray(['kd_akun'],[]);
    var grid_akun_posting= new Ext.grid.GridPanel({
        
        //id:'id_searchgrid_akun_transaksi',
        store: strakunposting,
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
            items: [search_akun_posting]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strakunposting,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                    var kdakun=sel[0].get('kd_akun');
                    Ext.getCmp('mca_akunposting').setValue(kdakun);
                    Ext.getCmp('id_nmakunposting').setValue(sel[0].get('nama'));
                    menu_akun_posting.hide();
                }
            }
        }
    });
    var menu_akun_posting = new Ext.menu.Menu();
    setPanelMenu(menu_akun_posting, 'Pilih Akun', 400, 300, grid_akun_posting, function(){
        menu_akun_posting.hide();
    }, function(){
        var sf = Ext.getCmp('id_search_akun_posting').getValue();
        if( sf != ''){
            Ext.getCmp('id_search_akun_posting').setValue('');
            Ext.getCmp('id_search_akun_posting').onTrigger2Click();
        }
    });
    
    Ext.ux.TwinComboAkunPosting = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strakunposting.load();
            menu_akun_posting.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    
    var cmb_akunposting = new Ext.ux.TwinComboAkunPosting({
        fieldLabel: 'Akun posting',
        id: 'mca_akunposting',
        store: strcmbakunposting,
        mode: 'local',
        valueField: 'kd_akun',
        displayField: 'kd_akun',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_akun_posting',
        emptyText: 'Pilih Akun Posting'
    });
    
    Ext.ns('master_closeaccountform');
    master_closeaccountform.Form = Ext.extend(Ext.form.FormPanel,{
        border: false,
        closeable: true,
        frame: true,
        labelWidth: 120,
        waitMsg:'Loading...',
        url: '<?= site_url("account_masterclosing/update_row") ?>',
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
            master_closeaccountform.Form.superclass.constructor.call(this, config);
        },
        initComponent: function(){
            var config = {
                defaultType: 'textfield',
                defaults: { labelSeparator: ''},
                monitorValid: true,
                autoScroll: false // ,buttonAlign:'right'
                ,
                items: [
                    {
                        xtype:'hidden',
//                        fieldLabel: 'Nama Akun Jenis',
                        id:'id_kdcm',
                        name: 'kd_cm'
//                        readOnly :true,
//                        anchor:'90%'
                    },
                    cmbthbltype,
                    cmbjenisclose,                   
                    cmb_akunjenis,
                    {
                        xtype:'textfield',
                        fieldLabel: 'Nama Akun Jenis',
                        id:'id_nmakunjenis',
                        name: 'nmakunjenis',
                        readOnly :true,
                        anchor:'90%'
                    },
                    
                    cmb_akunposting ,
                    {
                        xtype:'textfield',
                        fieldLabel: 'Nama Akun posting',
                        id:'id_nmakunposting',
                        name: 'nmakunposting',
                        anchor:'90%',readOnly :true
                                        
                    }
                ],
                buttons: [{
                        text: 'Submit',
                        id: 'btnsubmitmca',
                        formBind: true,
                        scope: this,
                        handler: this.submit
                    }, {
                        text: 'Reset',
                        id: 'btnresetmca',
                        scope: this,
                        handler: this.reset
                    }, {
                        text: 'Close',
                        id: 'btnclosemca',
                        scope: this,
                        handler: function(){
                            winaddmaster_closeaccount.hide();
                        }
                    }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            master_closeaccountform.Form.superclass.initComponent.apply(this, arguments);
        },
        onRender: function(){
        
            // call parent
            master_closeaccountform.Form.superclass.onRender.apply(this, arguments);
            
            // set wait message target
            this.getForm().waitMsgTarget = this.getEl();
            
            // loads form after initial layout
            // this.on('afterlayout', this.onLoadClick, this, {single:true});
        
        },
        reset: function(){
            this.getForm().reset();
        },
        submit: function(){
            var thblt=Ext.getCmp('cmb_thbltype').getValue();
            var jeniscls=Ext.getCmp('cmb_jenisclose').getValue();
            var cmd='';
            cmd=Ext.getCmp('btnsubmitmca').getText();
            cmd=cmd.toLowerCase();
            this.getForm().submit({
                url: this.url,
                scope: this,
                success: this.onSuccess,
                failure: this.onFailure,
                params: {
                    cmd: cmd,
                    thblt:thblt,
                    jenisclose:jeniscls
                    
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
            
            
            strmaster_closeaccount.reload();
            Ext.getCmp('id_formaddmaster_closeaccount').getForm().reset();
            winaddmaster_closeaccount.hide();
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
    Ext.reg('formaddmaster_closeaccount', master_closeaccountform.Form);
    
    var winaddmaster_closeaccount = new Ext.Window({
        id: 'id_winaddmaster_closeaccount',
        closeAction: 'hide',
        width: 450,
        height: 250,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_formaddmaster_closeaccount',
            xtype: 'formaddmaster_closeaccount'
        },
        onHide: function(){
            Ext.getCmp('id_formaddmaster_closeaccount').getForm().reset();
        },
        onShow: function(){
            //            strcbparentakun.reload();
            
        }
    });
    
    var strmaster_closeaccount = createStoreData([
        'kd_cm',
        'thbl_type',
        'thbl_name',
        'kd_jenis',
        'jenis',
        'akun_jenis',
        'akun_posting',
        'nama_akun'
    ], '<?= site_url("account_masterclosing/get_rows") ?>');
    var tbmaster_closing_account = new Ext.Toolbar({
        items: [{
                text: 'Add',
                icon: BASE_ICONS + 'add.png',
                onClick: function(){
                    //                strcbparentakun.reload();
                    Ext.getCmp('btnresetmca').show();
                    Ext.getCmp('btnsubmitmca').setText('Submit');
                    //                
                    winaddmaster_closeaccount.setTitle('Add Form');
                    winaddmaster_closeaccount.show();                
                }            
            },
        ]
        //        '-', searchmaster_closing_account]
    });
    var cbGridmaster_closeaccount = new Ext.grid.CheckboxSelectionModel();
    var actionmaster_closeaccountedit = new Ext.ux.grid.RowActions({
        header :'Edit',
        autoWidth: false,
        width: 40,
        actions:[{iconCls: 'icon-edit-record', qtip: 'Edit'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });	
    var actionmaster_closeaccountdel = new Ext.ux.grid.RowActions({
        header: 'Delete',
        autoWidth: false,
        width: 40,
        actions:[{iconCls: 'icon-delete-record', qtip: 'Delete'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });
    
    function editmaster_closing(kd_cm){
        Ext.getCmp('btnresetmca').hide();
        Ext.getCmp('btnsubmitmca').setText('Update');
        winaddmaster_closeaccount.setTitle('Edit Form');
        Ext.getCmp('id_formaddmaster_closeaccount').getForm().load({
            url: '<?= site_url("account_masterclosing/get_row") ?>',
            params: {
                cmd: 'get',
                kd_cm:kd_cm
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
        winaddmaster_closeaccount.show();
    }
        
    actionmaster_closeaccountedit.on('action', function(grid, record, action, row, col) {
        var kd_cm = record.get('kd_cm');
        switch(action) {
            case 'icon-edit-record':	        	
                editmaster_closing(kd_cm);
                break;
            case 'icon-delete-record':
                Ext.Msg.show({
                    title: 'Confirm',
                    msg: 'Are you sure delete selected row ?',
                    buttons: Ext.Msg.YESNO,
                    fn: function(btn){
                        if (btn == 'yes') {
                            Ext.Ajax.request({
                                url: '<?= site_url("account_masterclosing/delete_row") ?>',
                                method: 'POST',
                                params: {
                                    kd_cm: kd_cm
                                },
                                callback:function(opt,success,responseObj){
                                    var de = Ext.util.JSON.decode(responseObj.responseText);
                                    if(de.success==true){
                                        strmaster_closeaccount.reload();
                                        strmaster_closeaccount.load({
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
        
        var master_closing_akun_grid=new Ext.grid.EditorGridPanel({
            id: 'id-master_account-closing',
            frame: true,
            border: true,
            stripeRows: true,
            sm: cbGridmaster_closeaccount,
            store: strmaster_closeaccount,
            closable:true,
            loadMask: true,
            style: 'margin:0 auto;',
            height: 450,
            plugins:[actionmaster_closeaccountedit,actionmaster_closeaccountdel],
            columns: [actionmaster_closeaccountedit,actionmaster_closeaccountdel,
                {
                    header: 'Tipe THBL code',           
                    dataIndex: 'thbl_type',
                    width: 100,
                    hidden:true
                },
                {
                    header: 'Tipe THBL',           
                    dataIndex: 'thbl_name',
                    width: 100
                },{
                    header: 'Jenis Closing',           
                    dataIndex: 'jenis',
                    width: 150
                },{
                    header: 'Akun Jenis',           
                    dataIndex: 'akun_jenis',
                    width: 100
                },{
                    header: 'Akun Posting',           
                    dataIndex: 'akun_posting',
                    width: 100
                },{
                    header: 'Nama Akun',           
                    dataIndex: 'nama_akun',
                    width: 200
                }
            ]
            ,tbar: tbmaster_closing_account
            ,bbar: new Ext.PagingToolbar({
                pageSize: ENDPAGE,
                store: strmaster_closeaccount,
                displayInfo: true
            })
        });
        var master_accountpanel = new Ext.FormPanel({
            id: 'master_closing_akun',
            border: false,
            frame: false,
            autoScroll:true,	
            items: [master_closing_akun_grid],
            listeners:{
                afterrender:function(){
                    strmaster_closeaccount.load();
                }
            }
        });
    
</script>