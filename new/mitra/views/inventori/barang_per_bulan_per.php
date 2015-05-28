<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">    
    /* START FORM */ 
    Ext.ns('barangperbulanperform');
    barangperbulanperform.Form = Ext.extend(Ext.form.FormPanel, {
    
        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 100,
        url: '<?= site_url("lokasi_per_barang/update_row") ?>',
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
            barangperbulanperform.Form.superclass.constructor.call(this, config);
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
                    name: 'id_sub_blok'
                },{
                    type: 'textfield',
                    fieldLabel: 'No. PO <span class="asterix">*</span>',
                    name: 'id_sub_blok',
                    allowBlank: false,
                    id: 'id__sub_blok',
                    maxLength: 255,
                    anchor: '90%'                
                }],
                buttons: [{
                    text: 'Submit',
                    id: 'btnsubmitbarangperbulanper',
                    formBind: true,
                    scope: this,
                    handler: this.submit
                }, {
                    text: 'Reset',
                    id: 'btnresetbarangperbulanper',
                    scope: this,
                    handler: this.reset
                }, {
                    text: 'Close',
                    id: 'btnClose',
                    scope: this,
                    handler: function(){
                        winaddbarangperbulanper.hide();
                    }
                }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            
            // call parent
            barangperbulanperform.Form.superclass.initComponent.apply(this, arguments);
            
        } // eo function initComponent  
        ,
        onRender: function(){
        
            // call parent
            barangperbulanperform.Form.superclass.onRender.apply(this, arguments);
            
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
            
            
            strbarangperbulanper.reload();
            Ext.getCmp('id_formaddbarangperbulanper').getForm().reset();
            winaddbarangperbulanper.hide();
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
    Ext.reg('formaddbarangperbulanper', barangperbulanperform.Form);
    
    var winaddbarangperbulanper = new Ext.Window({
        id: 'id_winaddbarangperbulanper',
        closeAction: 'hide',
        width: 450,
        height: 400,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_formaddbarangperbulanper',
            xtype: 'formaddbarangperbulanper'
        },
        onHide: function(){
            Ext.getCmp('id_formaddbarangperbulanper').getForm().reset();
        }
    });
    
    /* START GRID */    
    var strbarangperbulanper = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'blth',
                'kd_produk',
                'nama_produk',
                'qty_in',
                'qty_out',
                'qty_oh',
                'qty_mutasi_in',
                'qty_mutasi_out',
                'qty_target',
                'nm_satuan'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("barang_perbulan_per/get_rows") ?>',
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
    
    var searchbarangperbulanper = new Ext.app.SearchField({
        store: strbarangperbulanper,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchbarangperbulanper'
    });
    
    var tbbarangperbulanper = new Ext.Toolbar({
        items: [searchbarangperbulanper]
    });

    var cbGrid = new Ext.grid.CheckboxSelectionModel();
        
    // row actions
    var actionbarangperbulanper = new Ext.ux.grid.RowActions({
        actions:[
          {iconCls: 'icon-edit-record', qtip: 'Edit'},
          {iconCls: 'icon-delete-record', qtip: 'Delete'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    }); 
    actionbarangperbulanper.on('action', function(grid, record, action, row, col) {
        var kd_supplier = record.get('kd_supplier');
        switch(action) {
            case 'icon-edit-record':                
                editbarangperbulanper(kd_supplier);
                break;
            case 'icon-delete-record':
                Ext.Msg.show({
                    title: 'Confirm',
                    msg: 'Are you sure delete selected row ?',
                    buttons: Ext.Msg.YESNO,
                    fn: function(btn){
                        if (btn == 'yes') {
                            Ext.Ajax.request({
                                url: '<?= site_url("lokasi_per_barang/delete_row") ?>',
                                method: 'POST',
                                params: {
                                    kd_supplier: kd_supplier
                                },
                                callback:function(opt,success,responseObj){
                                    var de = Ext.util.JSON.decode(responseObj.responseText);
                                    if(de.success==true){
                                        strbarangperbulanper.reload();
                                        strbarangperbulanper.load({
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
    var barangperbulanper = new Ext.grid.EditorGridPanel({
        id: 'id-barangperbulanper-gridpanel',
        frame: true,
        border: true,
        stripeRows: true,
        sm: cbGrid,
        store: strbarangperbulanper,
        loadMask: true,
        style: 'margin:0 auto;',
        height: 450,
        columns: [{
            header: "Bulan Tahun",
            dataIndex: 'blth',
            sortable: true,
            width: 100
        },{
            header: "Kode Produk",
            dataIndex: 'kd_produk',
            sortable: true,
            width: 100
        },{
            header: "Nama Produk",
            dataIndex: 'nama_produk',
            sortable: true,
            width: 300
        },{
            header: "Satuan",
            dataIndex: 'nm_satuan',
            sortable: true,
            width: 100
        },{
            header: "Qty In",
            dataIndex: 'qty_in',
            sortable: true,
            width: 100
        },{
            header: "Qty Out",
            dataIndex: 'qty_out',
            sortable: true,
            width: 100
        },{
            header: "Qty Oh",
            dataIndex: 'qty_oh',
            sortable: true,
            width: 100
        },{
            header: "Qty Mutasi In",
            dataIndex: 'qty_mutasi_in',
            sortable: true,
            width: 100
        },{
            header: "Qty Mutasi Out",
            dataIndex: 'qty_mutasi_out',
            sortable: true,
            width: 100
        }],
        tbar: tbbarangperbulanper,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strbarangperbulanper,
            displayInfo: true
        })
    });
    
	var barangperbulanperpanel = new Ext.FormPanel({
	 	id: 'barangperbulanper',
		border: false,
        frame: false,
		autoScroll:true,	
        items: [barangperbulanper]
	});
	
    function editbarangperbulanper(kd_supplier){
        Ext.getCmp('btnresetbarangperbulanper').hide();
        Ext.getCmp('btnsubmitbarangperbulanper').setText('Update');
        winaddbarangperbulanper.setTitle('Edit Form');
        Ext.getCmp('id_formaddbarangperbulanper').getForm().load({
            url: '<?= site_url("lokasi_per_barang/get_row") ?>',
            params: {
                id: kd_supplier,
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
        winaddbarangperbulanper.show();
    }
    
    function deletebarangperbulanper(){      
        var sm = barangperbulanper.getSelectionModel();
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
                            data = data + sel[i].get('kd_supplier') + ';';
                        }
                        
                        Ext.Ajax.request({
                            url: '<?= site_url("lokasi_per_barang/delete_rows") ?>',
                            method: 'POST',
                            params: {
                                postdata: data
                            },
                            callback:function(opt,success,responseObj){
                                var de = Ext.util.JSON.decode(responseObj.responseText);
                                if(de.success==true){
                                    strbarangperbulanper.reload();
                                    strbarangperbulanper.load({
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
