<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">    
    /* START FORM */
	
	 
    Ext.ns('setparameterform');
    setparameterform.Form = Ext.extend(Ext.form.FormPanel, {
    
        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 100,
        url: '<?= site_url("setparameter/update_row") ?>',
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
            setparameterform.Form.superclass.constructor.call(this, config);
        },
        initComponent: function(){
        
            // hard coded - cannot be changed from outsid
            var config = {
                defaultType: 'textfield',
                defaults: { labelSeparator: '',
                    labelWidth: 100},
                monitorValid: true,
                autoScroll: false // ,buttonAlign:'right'
                ,
                items: [{
                        xtype: 'hidden',
                        name: 'kd_parameter'
                    },{
                        type: 'textfield',
                        fieldLabel: 'Nama Parameter <span class="asterix">*</span>',
                        name: 'nama_parameter',
                        labelStyle: 'white-space: nowrap;',
                        labelWidth: 150,
                        allowBlank: false,
                        id: 'id_nama_parameter',
                        maxLength: 50,
                        anchor: '90%'                
                    }, {
                        xtype: 'textarea',
                        fieldLabel: 'Nilai Parameter <span class="asterix">*</span>',
                        name: 'nilai_parameter',
                        labelWidth: 100,
                        allowBlank: false,
                        id: 'id_nilai_parameter',
                        // maxLength: 40,
                        anchor: '90%'                
                    },{
                        xtype: 'textarea',
                        fieldLabel: 'Keterangan <span class="asterix">*</span>',
                        name: 'keterangan',
                        allowBlank: false,
                        id: 'id_sp_keterangan',
                        maxLength: 40,
                        anchor: '90%'                
                    }],
                buttons: [{
                        text: 'Submit',
                        id: 'btnsubmitsetparameter',
                        formBind: true,
                        scope: this,
                        handler: this.submit
                    }, {
                        text: 'Reset',
                        id: 'btnresetsetparameter',
                        scope: this,
                        handler: this.reset
                    }, {
                        text: 'Close',
                        id: 'btnClose',
                        scope: this,
                        handler: function(){
                            winaddsetparameter.hide();
                        }
                    }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            
            // call parent
            setparameterform.Form.superclass.initComponent.apply(this, arguments);
            
        } // eo function initComponent	
        ,
        onRender: function(){
        
            // call parent
            setparameterform.Form.superclass.onRender.apply(this, arguments);
            
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
        
            this.getForm().submit({
                url: this.url,
                scope: this,
                success: this.onSuccess,
                failure: this.onFailure,
                params: {
                    cmd: 'save'
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
            
            
            strsetparameter.reload();
            Ext.getCmp('id_formaddsetparameter').getForm().reset();
            winaddsetparameter.hide();
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
    Ext.reg('formaddsetparameter', setparameterform.Form);
    
    var winaddsetparameter = new Ext.Window({
        id: 'id_winaddsetparameter',
        closeAction: 'hide',
        width: 450,
        height: 350,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_formaddsetparameter',
            xtype: 'formaddsetparameter'
        },
        onHide: function(){
            Ext.getCmp('id_formaddsetparameter').getForm().reset();
        }
    });
    
    /* START GRID */    
	
    // data store
    var strsetparameter = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [ 
                'kd_parameter',
                'type_parameter',
                'nama_parameter',
                'nilai_parameter',
                'ref_parameter',
                'keterangan',
                'created_by',
                'created_date',
                'updated_by',
                'updated_date'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("setparameter/get_rows") ?>',
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
    var searchsetparameter = new Ext.app.SearchField({
        store: strsetparameter,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchsetparameter'
    });
    
    // top toolbar
    var tbsetparameter = new Ext.Toolbar({
        items: [{
                text: 'Add',
                icon: BASE_ICONS + 'add.png',
                onClick: function(){		
                    Ext.getCmp('btnresetsetparameter').show();
                    Ext.getCmp('btnsubmitsetparameter').setText('Submit');
                    winaddsetparameter.setTitle('Add Form');
                    winaddsetparameter.show();                
                }            
            }, '-', searchsetparameter]
    });
	
    // checkbox grid
    var cbGrid = new Ext.grid.CheckboxSelectionModel();
    
    // row actions
    var actionsetparameter = new Ext.ux.grid.RowActions({
        header:'Edit',
        autoWidth: false,
        width: 30,
        actions:[
            {iconCls: 'icon-edit-record', qtip: 'Edit'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });	
    var actionsetparameterdel = new Ext.ux.grid.RowActions({
        header:'Delete',
        autoWidth: false,
        width: 40,
        actions:[	      
            {iconCls: 'icon-delete-record', qtip: 'Delete'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });	
	
    actionsetparameter.on('action', function(grid, record, action, row, col) {
        var id_setparameter = record.get('kd_parameter');
        if (action=='icon-edit-record'){
            editsetparameter(id_setparameter);
        }
    });  
	
    actionsetparameterdel.on('action', function(grid, record, action, row, col) {
        var id_setparameter = record.get('kd_parameter');
        if (action=='icon-delete-record'){
            Ext.Msg.show({
                title: 'Confirm',
                msg: 'Are you sure delete selected row ?',
                buttons: Ext.Msg.YESNO,
                fn: function(btn){
                    if (btn == 'yes') {
                        Ext.Ajax.request({
                            url: '<?= site_url("setparameter/delete_row") ?>',
                            method: 'POST',
                            params: {
                                kd_parameter: id_setparameter
                            },
                            callback:function(opt,success,responseObj){
                                var de = Ext.util.JSON.decode(responseObj.responseText);
                                if(de.success==true){
                                    strsetparameter.reload();
                                    strsetparameter.load({
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
  	
    // grid
    var setparameter = new Ext.grid.EditorGridPanel({
        //id: 'id-setparameter-gridpanel',
        id: 'setparameter',
        frame: true,
        border: true,
        stripeRows: true,
        sm: cbGrid,
        store: strsetparameter,
        loadMask: true,
        style: 'margin:0 auto;',
        height: 450,
        columns: [actionsetparameter,{
                header: "Kode Parameter",
                dataIndex: 'kd_parameter',
                sortable: true,
                width: 90
            },{
                header: "Nama Parameter",
                dataIndex: 'nama_parameter',
                sortable: true,
                width: 200
            },{
                header: "Nilai Parameter",
                dataIndex: 'nilai_parameter',
                sortable: true,
                width: 350
            },{
                header: "Keterangan",
                dataIndex: 'keterangan',
                sortable: true,
                width: 200
            }],
        plugins: [actionsetparameter, actionsetparameterdel],
        listeners: {
            'rowdblclick': function(){				
                var sm = setparameter.getSelectionModel();                
                var sel = sm.getSelections();                
                if (sel.length > 0) {
                    editsetparameter(sel[0].get('kd_parameter'),sel[0].get('nilai_parameter'),sel[0].get('type_parameter'));                    
                }                 
            }          
        },
        tbar: tbsetparameter,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strsetparameter,
            displayInfo: true
        })
    });
    /**
        var setparameterpanel = new Ext.FormPanel({
                id: 'setparameter',
                border: false,
        frame: false,
                autoScroll:true,	
        items: [setparameter]
        });
     **/
    function editsetparameter(id_setparameter,nilai_parameter,type_parameter){
        Ext.getCmp('btnresetsetparameter').hide();		
        Ext.getCmp('btnsubmitsetparameter').setText('Update');
        winaddsetparameter.setTitle('Edit Form');
        Ext.getCmp('id_formaddsetparameter').getForm().load({
            url: '<?= site_url("setparameter/get_row") ?>',
            params: {
                id: id_setparameter,
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
        winaddsetparameter.show();
    }
	
    function deletesetparameter(){		
        var sm = setparameter.getSelectionModel();
        var sel = sm.getSelections();
        if (sel.length > 0) {
            Ext.Msg.show({
                title: 'Confirm',
                msg: 'Are you sure delete selected row ?',
                buttons: Ext.Msg.YESNO,
                fn: function(btn){
                    if (btn == 'yes') {
                    
                        var data = sel[i].get('kd_parameter');
                        
                        Ext.Ajax.request({
                            url: '<?= site_url("setparameter/delete_row") ?>',
                            method: 'POST',
                            params: {
                                kd_parameter: data
                            },
                            callback:function(opt,success,responseObj){
                                var de = Ext.util.JSON.decode(responseObj.responseText);
                                if(de.success==true){
                                    strsetparameter.reload();
                                    strsetparameter.load({
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
