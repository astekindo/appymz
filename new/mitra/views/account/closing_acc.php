<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">	
    var dsblnclosing=createStoreArray([
        {name: 'mid'},
        {name: 'mtext'}
    ],[
        ['01',"Januari"],
        ['02',"Februari"],
        ['03',"Maret"],
        ['04',"April"],
        ['05',"Mei"],
        ['06',"Juni"],
        ['07',"Juli"],
        ['08',"Augustus"],
        ['09',"September"],
        ['10',"Oktober"],
        ['11',"November"],
        ['12',"Desember"]
    ]);
    var cmbblclosing = new Ext.form.ComboBox({
        fieldLabel: 'Bulan Closing',
        id: 'id_cmbblclosing',
        name:'bulan',
        allowBlank:false,
        store: dsblnclosing
        ,valueField:'mid'
        ,displayField:'mtext'
        ,mode:'local',
        forceSelection: true,
        triggerAction: 'all',anchor: '90%'            
    });
    
    
    var arr_yearclosing = new Array();
    var arrgetclosing = new Array();
    var dtclosing = new Date();
    var now_yearclosing = dtclosing.getFullYear();
    var yearminclosing = now_yearclosing-10;
    var ytoarrclosing = yearminclosing;
    arrgetclosing=[];
    arr_yearclosing=[];
    for (var i = 0;i<=10;i++)
    {
        ytoarrclosing=yearminclosing+i;
        arrgetclosing.push(ytoarrclosing);
        arr_yearclosing.push(arrgetclosing);
        arrgetclosing=[];
    }
    
    var cmbthclosing = new Ext.form.ComboBox({
    
        fieldLabel: 'Tahun Closing',
        id: 'id_cmbthclosing',
        name:'tahun',
        allowBlank:false,
        store: new Ext.data.ArrayStore({
            fields: [
                {name: 'mtext'}
            ]})
        ,valueField:'mtext'
        ,displayField:'mtext'
        ,mode:'local',
        forceSelection: true,
        triggerAction: 'all',
        anchor: '90%',
        listeners: {
            render: function(){
                this.store.loadData(arr_yearclosing);
    
            }
        }
    
    });
    
    var strclosingcabang=createStoreData([ 
        'kd_cabang',
        'nama_cabang'
    ],'<?= site_url("account_entry_voucher/get_cabang") ?>');
            
    var cmb_closing_cabang= new Ext.form.ComboBox({
        fieldLabel:		'Cabang',
        mode:           'local',
        value:          '',
        triggerAction:  'all',
        forceSelection: true,
        editable:       true,
        name:           'nama_cabang',
        id:           	'closing_nama_cabang',
        hiddenName:     'kd_cabang',
        displayField:   'nama_cabang',
        valueField:     'kd_cabang',
        anchor:			'90%',
        emptyText:'All',
        allowBlank:false,
        store:  strclosingcabang
       
    });
      //              cmbblclosing
                    
                    
    Ext.ns('closeaccountform');
    closeaccountform.Form = Ext.extend(Ext.form.FormPanel,{
        border: false,
        closeable: true,
        frame: true,
        labelWidth: 120,
        waitMsg:'Loading...',
        url: '<?= site_url("account_closing/add_closing") ?>',
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
            closeaccountform.Form.superclass.constructor.call(this, config);
        },
        initComponent: function(){
            var config = {
                defaultType: 'textfield',
                defaults: { labelSeparator: ''},
                monitorValid: true,
                autoScroll: false // ,buttonAlign:'right'
                ,
                items: [                    
                    cmbthclosing,
                    cmbblclosing,
                    cmb_closing_cabang
                    
                ],
                buttons: [{
                        text: 'Submit',
                        id: 'btnsubmit_closing',
                        formBind: true,
                        scope: this,
                        handler: this.submit
                    }, {
                        text: 'Reset',
                        id: 'btnreset_closing',
                        scope: this,
                        handler: this.reset
                    }, {
                        text: 'Close',
                        id: 'btnclose_closing',
                        scope: this,
                        handler: function(){
                            winclosing.hide();
                        }
                    }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            closeaccountform.Form.superclass.initComponent.apply(this, arguments);
        },
        onRender: function(){
        
            // call parent
            closeaccountform.Form.superclass.onRender.apply(this, arguments);
            
            // set wait message target
            this.getForm().waitMsgTarget = this.getEl();
            
            // loads form after initial layout
            // this.on('afterlayout', this.onLoadClick, this, {single:true});
        
        },
        reset: function(){
            this.getForm().reset();
        },
        submit: function(){
            var tahun=Ext.getCmp('id_cmbthclosing').getValue();
            var bulan=Ext.getCmp('id_cmbblclosing').getValue();
            var kdcabang=Ext.getCmp('closing_nama_cabang').getValue();
            var cmd='save';            
            this.getForm().submit({
                url: this.url,
                scope: this,
                success: this.onSuccess,
                failure: this.onFailure,
                params: {
//                    cmd: cmd,
                    mtahun:tahun,
                    mbulan:bulan,
                    mkdcabang:kdcabang
                    
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
            
            
            strmaster_closing.reload();
            Ext.getCmp('id_closeaccountform').getForm().reset();
            winclosing.hide();
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
    Ext.reg('frmcloseaccountform', closeaccountform.Form);
    
    var winclosing = new Ext.Window({
        id: 'id_winclosing',
        closeAction: 'hide',
        width: 350,
        height: 150,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_closeaccountform',
            xtype: 'frmcloseaccountform'
        },
        onHide: function(){
            Ext.getCmp('id_closeaccountform').getForm().reset();
        },
        onShow: function(){
            //            strcbparentakun.reload();
            
        }
    });
    //==============================================================================
    
    var tbmaster_closing=new Ext.Toolbar({
        items: [{
                text: 'New',
                icon: BASE_ICONS + 'add.png',
                onClick: function(){
//                   var recg=Ext.getCmp('id-closing-gridpanel').getStore();                   
//                   if(recg.getCount()==0){
                        winclosing.show();
//                   }

                }            
            },
        ]
        //        '-', searchmaster_closing_account]
    });
    
    var cbGridmaster_closing=new Ext.grid.CheckboxSelectionModel();
    
    var action_closing= new Ext.ux.grid.RowActions({
        header :'Action Close',
        autoWidth: false,
        width: 80,
        actions:[{iconCls: 'icon-proses', qtip: 'Closing'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });	
    function set_closing(vthbl,vkdcabang){
        Ext.Msg.show({
                    title: 'Confirm',
                    msg: 'Are you sure to action close selected row ?',
                    buttons: Ext.Msg.YESNO,
                    fn: function(btn){
                        if (btn == 'yes') {
                            Ext.Ajax.request({
                                url: '<?= site_url("account_closing/set_closing") ?>',
                                method: 'POST',
                                params: {
                                    thbl: vthbl,kdcabang:vkdcabang
                                },
                                callback:function(opt,success,responseObj){
                                    var de = Ext.util.JSON.decode(responseObj.responseText);
                                    if(de.success==true){
                                        //cek akumulasi
                                        //posting akumulasi
                                        //posting biaya
                                        //posting pendapatan
                                        //update masterclosing
                                        Ext.getCmp('id-closing-gridpanel').getStore().reload();
//                                        strmaster_closing.reload();
//                                        strmaster_closing.load({
//                                            params: {
//                                                start: STARTPAGE,
//                                                limit: ENDPAGE
//                                            }
//                                        });
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
    action_closing.on('action', function(grid, record, action, row, col) {
        var vthbl=record.get('thbl');
        var vkdcabang=record.get('kd_cabang');
        switch(action) {
            case 'icon-proses':	        	
                set_closing(vthbl,vkdcabang);
                break;
        }
    });
    
    
    var strmaster_closing=createStoreData(
    ['thbl','status','aktif_date','aktif_by','close_date','close_by','kd_cabang','nama_cabang'], 
    '<?= site_url("account_closing/get_rows") ?>');
    var closing_grid=new Ext.grid.EditorGridPanel({
        id: 'id-closing-gridpanel',
        frame: true,
        border: true,
        stripeRows: true,
        sm: cbGridmaster_closing,
        store: strmaster_closing,
        closable:true,
        loadMask: true,
        style: 'margin:0 auto;',
        height: 450,
        plugins:[action_closing],
        columns: [
            action_closing,
               
            {
                header: 'THBL',           
                dataIndex: 'thbl',
                width: 100
            },{
                header: 'Status',           
                dataIndex: 'status',
                width: 150
            },{
                header: 'Tanggal Aktif',           
                dataIndex: 'aktif_date',
                width: 100
            },{
                header: 'Aktif By',           
                dataIndex: 'aktif_by',
                width: 100
            },{
                header: 'Tanggal Close',           
                dataIndex: 'close_date',
                width: 100
            },{
                header: 'Close By',           
                dataIndex: 'close_by',
                width: 100
            },{
                header: 'Cabang',           
                dataIndex: 'nama_cabang',
                width: 100
            }
        ]
        ,tbar: tbmaster_closing
        ,bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strmaster_closing,
            displayInfo: true
        })
    });
    
    var closingacc_form = new Ext.FormPanel({
        id: 'closing_akuntansi',
        border: false,
        frame: false,
        autoScroll:true,	
        items: [closing_grid],
        listeners:{
            afterrender:function(){
                strmaster_closing.load();
                strclosingcabang.load();
            }
        }
    });
    
</script>
