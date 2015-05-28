<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">	
    var dtprejectapp= new Ext.form.DateField(
    {
        fieldLabel: 'Tanggal Awal',
        id: 'dtprejectapp'
        ,value:new Date()
        ,allowBlank:false,anchor: '90%'
        //        ,vtype: 'daterangemonapp',
        //        endDateField: 'dtpmonappakhir'
        ,format:'Y-m-d'
        ,listeners:{
            select:function(){
                if(Ext.getCmp('dtprejectappakhir').getValue()){
                    if(this.getValue()>Ext.getCmp('dtprejectappakhir').getValue()){
                        var dtn=Ext.getCmp('dtprejectappakhir').getValue();
                        this.setValue(dtn);
                    }
                }
                
                
            }
        }
    });
    var dtprejectappakhir= new Ext.form.DateField(
    {
        fieldLabel: 'Tanggal Akhir',
        id: 'dtprejectappakhir'
        ,allowBlank:false,anchor: '90%'
        //        ,vtype: 'daterangemonapp',
        //        startDateField: 'dtpmonapp'
        ,format:'Y-m-d'
        ,value:new Date()
        ,listeners:{
            select:function(){
                if(this.getValue()){
                    if(this.getValue()<Ext.getCmp('dtprejectapp').getValue()){
                        var dtn=this.getValue();
                        Ext.getCmp('dtprejectapp').setValue(dtn);
                        
                    }
                }
                
                
            }
        }
    });
    var strrejectapprovalcabang=createStoreData([ 
        'kd_cabang',
        'nama_cabang'
    ],'<?= site_url("account_entry_voucher/get_cabang") ?>' );
    var cmb_rejectapproval_cabang= new Ext.form.ComboBox({
        fieldLabel:		'Cabang',
        mode:           'local',
        value:          '',
        triggerAction:  'all',
        forceSelection: true,
        //        editable:       false,
        name:           'nama_cabang',
        id:           	'rejectapproval_nama_cabang',
        hiddenName:     'kd_cabang',
        displayField:   'nama_cabang',
        valueField:     'kd_cabang',
        anchor:			'90%',
        store:  strrejectapprovalcabang,
        emptyText:'All'
    });
    
    var headerrejectapproval={
        region:'north',
        layout: 'column',
        border: false,
        height:120,
        items: [{
                columnWidth: .3,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [dtprejectapp,dtprejectappakhir,cmb_rejectapproval_cabang,
                          
                    
                ]
           
            },{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                //                anchor: '50%',
                defaults: { labelSeparator: ''},
                items: [
                    {xtype:'fieldset',
                        id:'reject_approval_level_box',
                        name:'approval_level_box',
                        title: 'Parameter Approval Sampai Dengan',
                        //                        checkboxToggle: true,
                        collapsible:false,
                        autoHeight:true,  
                        anchor: '90%',
                        //                        collapsed :true,
                        //                        disabled:true,
                        items:[
                            {xtype: 'checkbox',
                                checked: true,
                                fieldLabel: 'Status Approval',
                                labelSeparator: '',
                                boxLabel: 'Sudah Approval',
                                name: 'reject_sb_approval',
                                id:'reject_sb_approval',
                                disabled:true
                            },
                            {xtype: 'radiogroup',
                                fieldLabel: 'Approval Level',
                                id:'reject_approval_level',
                                name:'reject_approval_level',
                                items: [
                                    {boxLabel: 'Level 1', name: 'approval' ,inputValue: 1,checked:true},
                                    {boxLabel: 'Level 2', name: 'approval',inputValue: 2,checked:false},
                                    {boxLabel: 'Level 3', name: 'approval',inputValue: 3,checked:false}
               
                                ]}]
                        
                    }            
                ]
           
            }],
        
        bbar:[
            {
                text:'Preview All',
                //iconCls:'glapp-preview',
                id:'bbarpreviewrejectappall'
                ,handler: function(){

                    var tglawal=null;
                    var tglakhir=null;
                    var kdcabang=null;
                    tglawal=Ext.getCmp('dtprejectapp').getValue().format('Y-m-d');
                    tglakhir=Ext.getCmp('dtprejectappakhir').getValue().format('Y-m-d');
                    kdcabang=Ext.getCmp('rejectapproval_nama_cabang').getValue();
                    var approval1=null,approval2=null,approval3=null,sbapproval=null;
                    
                    
                    //                    approval1=Ext.getCmp('mon_approval_level').getValue('approval1'); 
                    //                    approval2=Ext.getCmp('mon_approval_level').getValue('approval2');   
                    //                    approval3=Ext.getCmp('mon_approval_level').getValue('approval3'); 
                    //                    sbapproval=Ext.getCmp('mon_sb_approval').getValue('mon_sb_approval'); 
                    
                    strrejectapproval.proxy.conn.url = '<?= site_url("account_rejectapp/get_rows_all") ?>';
                    strrejectapproval.reload({params:{                            
                            tglawal:tglawal,
                            tglakhir:tglakhir,        
                            kdcabang:kdcabang
                        }}); 


                                   
                }
            },'-',
            {
                text:'Preview With Parameter',
                //iconCls:'glapp-preview',
                id:'bbarpreviewrejectapp'
                ,handler: function(){

                    var tglawal=null;
                    var tglakhir=null;
                    var kdcabang=null;
                    tglawal=Ext.getCmp('dtprejectapp').getValue().format('Y-m-d');
                    tglakhir=Ext.getCmp('dtprejectappakhir').getValue().format('Y-m-d');
                    kdcabang=Ext.getCmp('rejectapproval_nama_cabang').getValue();
                    var sapproval=null;
                    var grb=Ext.getCmp('reject_approval_level').getValue();
                    if (grb == undefined) return;
                    sapproval=grb.getGroupValue();
                    
                    strrejectapproval.proxy.conn.url = '<?= site_url("account_rejectapp/get_rows_params") ?>';
                    strrejectapproval.reload({params:{                            
                            tglawal:tglawal,
                            tglakhir:tglakhir,  
                            sapproval:sapproval,
                            kdcabang:kdcabang
                        }}); 


                                   
                }
            }

        ]
    } 
    
    
    var strrejectapproval=createStoreData([
        'tgl_transaksi',
        'kd_voucher',
        'kd_transaksi',
        'nama_transaksi',
        'kd_jenis_voucher',
        'title',
        'referensi',
        'keterangan',
        {name:'approval1',type:'bool'},
        {name:'status_apv1',type:'bool'},
        'approval_by',
        'approval_date',
        {name:'approval2',type:'bool'},
        {name:'status_apv2',type:'bool'},
        'approval2_by',
        'approval2_date',
        {name:'approval3',type:'bool'},
        {name:'status_apv3',type:'bool'},
        'approval3_by',
        'approval3_date',
        {name:'auto_posting_voucher',type:'bool'},
        {name:'status_posting',type:'bool'},
        'posting_by',
        'posting_date',
        'idjurnal',
        'diterima_oleh',
        'no_giro_cheque',
        'tgl_jttempo','kd_cabang','nama_cabang'
        
    ],'<?= site_url("account_rejectapp/get_rows_all") ?>');
    
    var col_approval1_reject =new Ext.grid.CheckColumn({
        header: 'Approval1',
        dataIndex: 'approval1',
        //            editable:false,
        width: 80
        ,sortable:true
    });
    var col_status_apv1_reject =new Ext.grid.CheckColumn({
        header: 'Status Approval1',
        dataIndex: 'status_apv1',
        //            editable:false,
        width: 80
        ,sortable:true
    });
    var col_approval2_reject =new Ext.grid.CheckColumn({
        header: 'Approval2',
        dataIndex: 'approval2',
        //            editable:false,
        width: 80
        ,sortable:true
    });
    var col_status_apv2_reject =new Ext.grid.CheckColumn({
        header: 'Status Approval2',
        dataIndex: 'status_apv2',
        //            editable:false,
        width: 80
        ,sortable:true
    });
    var col_approval3_reject =new Ext.grid.CheckColumn({
        header: 'Approval3',
        dataIndex: 'approval3',
        //            editable:false,
        width: 80
        ,sortable:true
    });
    var col_status_apv3_reject =new Ext.grid.CheckColumn({
        header: 'Status Approval3',
        dataIndex: 'status_apv3',
        //            editable:false,
        width: 80
        ,sortable:true
    });
    
    var col_autopost_reject =new Ext.grid.CheckColumn({
        header: 'Auto Posting Voucher',
        dataIndex: 'auto_posting_voucher',
        //            editable:false,
        width: 80
        ,sortable:true
    });
    var col_status_posting_reject =new Ext.grid.CheckColumn({
        header: 'Status Posting',
        dataIndex: 'status_posting',
        //            editable:false,
        width: 80
        ,sortable:true
    });
    
    
    Ext.ns('confirmrejectapproval');
    confirmrejectapproval.Form = Ext.extend(Ext.form.FormPanel,{
        border: false,
        closeable: true,
        frame: true,
        labelWidth: 100,
        waitMsg:'Loading...',
        url: '<?= site_url("account_rejectapp/update_row") ?>',
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
            confirmrejectapproval.Form.superclass.constructor.call(this, config);
        },
        initComponent: function(){
            var config = {
                defaultType: 'textfield',
                defaults: { labelSeparator: ''},
                monitorValid: true,
                autoScroll: false // ,buttonAlign:'right'
                ,labelWidth:100
                ,
                items: [                    
                    {
                        //                                            xtype: 'textfield',
                        fieldLabel: 'No.Voucher',
                        name: 'kd_voucher',				
                        id: 'rj_kd_voucher',                
                        anchor: '90%',
                        readOnly:true,
                        allowBlank:false                                            
                    }, 
                    {
                        //                                            xtype: 'textfield',
                        fieldLabel: 'Alasan Reject <span class="asterix">*</span>',
                        name: 'reason',				
                        id: 'rj_reason',                
                        anchor: '90%',
                        //                    readOnly:true,
                        allowBlank:false                                            
                    }
                    , {
                        xtype: 'compositefield',
                        fieldLabel: 'Cabang',                        
                        anchor    : '90%',    
                        id:'rj_cabang',
                        items: [
                            new Ext.form.TextField({                
                                name: 'kd_cabang',				
                                id: 'rj_kd_cabang',                
                                width:50,
                                //                                anchor: '90%',
                                readOnly:true
                            })

                            ,new Ext.form.TextField({
                                //                                xtype: 'textfield',
                                //                                fieldLabel: 'Nama Cabang',                            
                                name: 'nama_cabang',				
                                id: 'rj_nama_cabang',                
                                //                                anchor: '90%',
                                readOnly:true
                                ,flex:1
                            })  
                        ]
                    }
                    , {
                        xtype: 'compositefield',
                        id:'rj_transaksi',
                        fieldLabel: 'Transaksi',                        
                        anchor    : '90%',                        
                        items: [
                            new Ext.form.TextField({
                                //                                                    xtype: 'textfield',
                                fieldLabel: 'Kode Transaksi',                            
                                name: 'kd_transaksi',				
                                id: 'rj_kd_transaksi',                
                                width:50,
                                //                                anchor: '90%',
                                readOnly:true
                            }),new Ext.form.TextField({
                                //                                                    xtype: 'textfield',
                                fieldLabel: 'Nama Transaksi',                            
                                name: 'nama_transaksi',				
                                id: 'rj_nama_transaksi',                
                                //                                anchor: '90%',
                                readOnly:true
                                ,flex:1
                            })    
                                                
                        ]
                    }, {
                        xtype: 'compositefield',
                        fieldLabel: 'Jenis Voucher',                        
                        id:'rj_jenisvoucher',
                        anchor    : '90%',                        
                        items: [
                            new Ext.form.TextField({
                                //                                                    xtype: 'textfield',
                                fieldLabel: 'Kode Jenis Voucher',                            
                                name: 'kd_jenis_voucher',				
                                id: 'rj_kd_jenis_voucher',                
                                width:50,
                                //                                anchor: '90%',
                                readOnly:true
                            }),new Ext.form.TextField({
                                //                                                    xtype: 'textfield',
                                fieldLabel: 'Title',                            
                                name: 'title',				
                                id: 'rj_title',                
                                //                                anchor: '90%',
                                readOnly:true
                                ,flex:1
                            }    )
                                                
                        ]
                    },{
                        fieldLabel: 'Keterangan',                            
                        name: 'keterangan',				
                        id: 'rj_keterangan',                
                        //                                                    width:50,
                        anchor: '90%',
                        readOnly:true
                    }  ,{
                        xtype:'hidden',
                        //                                                    fieldLabel: 'Keterangan',                            
                        name: 'approval_by',				
                        id: 'rj_approval_by',                
                        //                                                    width:50,
                        anchor: '90%',
                        readOnly:true
                    }  ,{
                        xtype:'hidden',
                        //                                                    fieldLabel: 'Keterangan',                            
                        name: 'approval_date',				
                        id: 'rj_approval_date',                
                        //                                                    width:50,
                        anchor: '90%',
                        readOnly:true
                    }  ,{
                        xtype:'hidden',
                        //                                                    fieldLabel: 'Keterangan',                            
                        name: 'reject_level',				
                        id: 'rj_reject_level',                
                        //                                                    width:50,
                        anchor: '90%',
                        readOnly:true
                    }  
                    ,
                    {xtype:'fieldset',
                        id:'rj_approval_level_box',
                        name:'approval_level_box',
                        title: 'Approval Level To Reject',
                        //                        checkboxToggle: true,
                        collapsible:false,
                        autoHeight:true,  
                        anchor: '90%',
                        //                        collapsed :true,
                        //                        disabled:true,
                        items:[
                            {xtype: 'radiogroup',
                                fieldLabel: 'Approval Level',
                                id:'rj_approval_level',
                                name:'gr_approval',
                                disabled :true,
                                columns: [70,70,70],
                                items: [
                                    {boxLabel: 'Level 1', name: 'approval' ,inputValue: 1,checked:false},
                                    {boxLabel: 'Level 2', name: 'approval',inputValue: 2,checked:false},
                                    {boxLabel: 'Level 3', name: 'approval',inputValue: 3,checked:false}
                        
                                ]
                            }
                        ]}
                
                    ,{xtype:'fieldset',
                        id:'rj_rejecttype_box',
                        name:'rejecttype_box',
                        title: 'Reject Type To Reject',
                        //                        checkboxToggle: true,
                        collapsible:false,
                        autoHeight:true,  
                        anchor: '90%',
                        //                        collapsed :true,
                        //                        disabled:true,
                        items:[{xtype: 'radiogroup',
                                fieldLabel: 'Reject Type',
                                id:'rj_reject_type',
                                name:'gr_rejecttype',
                                columns: [70,70],
                                //                    itemCls: 'x-check-group-alt',
                                readOnly :false,
                                items: [
                                    {boxLabel: 'Close', name: 'rejecttype' ,inputValue: 1,checked:false},
                                    {boxLabel: 'Edit', name: 'rejecttype',inputValue: 2,checked:true}
                                ]}
                        ]}
                ],
                buttons: [{
                        text: 'Submit',
                        id: 'btnsubmit_rejectapp',
                        formBind: true,
                        scope: this,
                        handler: this.submit
                    }
                    , {
                        text: 'Close',
                        id: 'btnclose_rejectapp',
                        scope: this,
                        handler: function(){
                            winrejectapproval.hide();
                        }
                    }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            confirmrejectapproval.Form.superclass.initComponent.apply(this, arguments);
        },
        onRender: function(){
        
            // call parent
            confirmrejectapproval.Form.superclass.onRender.apply(this, arguments);
            
            // set wait message target
            this.getForm().waitMsgTarget = this.getEl();
            
            // loads form after initial layout
            // this.on('afterlayout', this.onLoadClick, this, {single:true});
        
        },
        reset: function(){
            this.getForm().reset();
        },
        submit: function(){
            var applevel=null,tipe=null;
            applevel=Ext.getCmp('rj_approval_level').getValue().getGroupValue();
            tipe=Ext.getCmp('rj_reject_type').getValue().getGroupValue();
            this.getForm().submit({
                url: this.url,
                scope: this,
                success: this.onSuccess,
                failure: this.onFailure,
                params: {                    
                    applevel:applevel,
                    tipe:tipe
                    //                    mtahun:tahun,
                    //                    mbulan:bulan,
                    //                    mkdcabang:kdcabang
                    
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
            
            strrejectapproval.reload();
            Ext.getCmp('id_formconfirm').getForm().reset();
            winrejectapproval.hide();
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
    Ext.reg('formconfirm', confirmrejectapproval.Form);
    
    var winrejectapproval = new Ext.Window({
        id: 'id_winrejectapproval',
        closeAction: 'hide',
        width: 400,
        height: 400,
        layout: 'fit',
        border: false,
        title:'Confirm Reject Approval',
        items: {
            id:'id_formconfirm',
            xtype:'formconfirm'
        },
        onHide: function(){
            //            Ext.getCmp('id_formaddmaster_account').getForm().reset();
        },
        onShow: function(){
            //            strcbparentakun.reload();
            
        }
    });
    
    var actionrejectapproval = new Ext.ux.grid.RowActions({
        header :'Reject',
        autoWidth: false,
        width: 50,
        actions:[{iconCls: 'icon-export', qtip: 'Reject Approval'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });	
    actionrejectapproval.on('action', function(grid, record, action, row, col) {
        var kdvoucher=record.get('kd_voucher');
        if(kdvoucher){
            Ext.getCmp('rj_kd_voucher').setValue(kdvoucher);
            var rec = record; 
            var apv1=rec.get('approval1');
            var stapv1=rec.get('status_apv1');
            var apv2=rec.get('approval2');
            var stapv2=rec.get('status_apv2');
            var apv3=rec.get('approval3');
            var stapv3=rec.get('status_apv3');
            var app1=false;
            var app2=false;
            var app3=false;
            if (apv3 && stapv3){
                app3=true;
            }
            if ((!apv3 && stapv3)||(apv3 && !stapv3)){
                app3=false;
            }
            if (apv2 && stapv2){
                app2=true;
            }
            if ((!apv2 && stapv2)||(apv2 && !stapv2)){
                app2=false;
            }
            if (apv1 && stapv1){
                app1=true;
            }
            if ((!apv1 && stapv1)||(apv1 && !stapv1)){
                app2=false;
            }
                          
            if (app1 && !app2 && !app3){
                Ext.getCmp('rj_approval_level').setValue(1); 
                Ext.getCmp('rj_reject_level').setValue(1); 
                              
                Ext.getCmp('rj_reject_type').setDisabled(false); 
                Ext.getCmp('rj_approval_by').setValue(record.get('approval_by'));
                Ext.getCmp('rj_approval_date').setValue(record.get('approval_date'));
            }
            if (app2 && !app3){
                Ext.getCmp('rj_approval_level').setValue(2);
                Ext.getCmp('rj_reject_level').setValue(2); 
                Ext.getCmp('rj_reject_type').setValue(2);
                Ext.getCmp('rj_reject_type').setDisabled(true);                  
                Ext.getCmp('rj_approval_by').setValue(record.get('approval2_by'));
                Ext.getCmp('rj_approval_date').setValue(record.get('approval2_date'));
            }
            if (app3){
                Ext.getCmp('rj_approval_level').setValue(3);
                Ext.getCmp('rj_reject_level').setValue(3); 
                Ext.getCmp('rj_reject_type').setValue(2);
                Ext.getCmp('rj_reject_type').setDisabled(true);                  
                Ext.getCmp('rj_approval_by').setValue(record.get('approval3_by'));
                Ext.getCmp('rj_approval_date').setValue(record.get('approval3_date'));
            }

            Ext.getCmp('rj_kd_cabang').setValue(record.get('kd_cabang'));
            Ext.getCmp('rj_nama_cabang').setValue(record.get('nama_cabang'));
                          
            Ext.getCmp('rj_kd_transaksi').setValue(record.get('kd_transaksi'));
            Ext.getCmp('rj_nama_transaksi').setValue(record.get('nama_transaksi'));
                          
            Ext.getCmp('rj_kd_jenis_voucher').setValue(record.get('kd_jenis_voucher'));
            Ext.getCmp('rj_title').setValue(record.get('title'));
            //              
            Ext.getCmp('rj_keterangan').setValue(record.get('keterangan'));                            

            winrejectapproval.show();
        }
        
    }); 
    
    var colmodelrejectapproval=new Ext.grid.ColumnModel({
        columns:[ 
            actionrejectapproval,
            {header:'Tanggal',             
                dataIndex: 'tgl_transaksi',
                width: 80 
                ,sortable:true
            }
            ,{
                header:'No.Voucher',                          
                dataIndex: 'kd_voucher',
                width: 100  
                ,sortable:true
            }
            ,{header:'Kode',             
                dataIndex: 'kd_transaksi',
                width: 80,
                hidden:true                
            }
            ,{header:'Nama Transaksi',             
                dataIndex: 'nama_transaksi',
                width: 80
                ,sortable:true
                //                ,hidden:true                
            }
            ,{header:'Kode Jenis Voucher',             
                dataIndex: 'kd_jenis_voucher',
                width: 80,
                hidden:true     
                
            }
            ,{header:'Jenis Voucher',             
                dataIndex: 'title',
                width: 80
                ,sortable:true
                //               ,hidden:true                
            }
            ,{header:'Referensi',             
                dataIndex: 'referensi',
                width: 80                
            }
            ,{header:'Keterangan',             
                dataIndex: 'keterangan',
                width: 200                
            }
            ,col_approval1_reject,col_status_apv1_reject
            ,{header:'Approval1 By',             
                dataIndex: 'approval_by',
                width: 80     
                ,sortable:true
            },{header:'Approval1 Date',             
                dataIndex: 'approval_date',
                width: 80     
                ,sortable:true
            }
            ,col_approval2_reject,col_status_apv2_reject
            ,{header:'Approval2 By',             
                dataIndex: 'approval2_by',
                width: 80     
                ,sortable:true
            },{header:'Approval2 Date',             
                dataIndex: 'approval2_date',
                width: 80     
                ,sortable:true
            },col_approval3_reject,col_status_apv3_reject
            ,{header:'Approval3 By',             
                dataIndex: 'approval3_by',
                width: 80     
                ,sortable:true
            },{header:'Approval3 Date',             
                dataIndex: 'approval3_date',
                width: 80     
                ,sortable:true
            }
            , col_autopost_reject,col_status_posting_reject
            ,
            {header:'Posting By',             
                dataIndex: 'posting_by',
                width: 80     
                ,sortable:true
            },{header:'Posting Date',             
                dataIndex: 'posting_date',
                width: 80     
                ,sortable:true
            },{header:'Id Jurnal',             
                dataIndex: 'idjurnal',
                width: 100    
                ,sortable:true
            },{header:'No.Giro/Checque',             
                dataIndex: 'no_giro_cheque',
                width: 100    
                ,sortable:true
            },{header:'Diterima Oleh',             
                dataIndex: 'diterima_oleh',
                width: 100    
                ,sortable:true
            },{header:'Jatuh Tempo',             
                dataIndex: 'tgl_jttempo',
                width: 100    
                ,sortable:true
            },{header:'Kode Cabang',             
                dataIndex: 'kd_cabang',
                width: 100    
                ,sortable:true
            },{header:'Nama Cabang',             
                dataIndex: 'nama_cabang',
                width: 100    
                ,sortable:true
            }
           
            
        ]
        
    });
    
   
    
    
    
    //    var mtglawal=Ext.getCmp('dtpmonapp').getValue().format('Y-m-d');
    var searchrejectapp = new Ext.app.SearchField({
        store: strrejectapproval,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
            //            ,tglawal:mtglawal
            //                                tglakhir:Ext.getCmp('dtpmonappakhir').getValue().format('Y-m-d'),  
            //                                approval1:Ext.getCmp('mon_approval_level').getValue('approval1'),
            //                                approval2:Ext.getCmp('mon_approval_level').getValue('approval2'),
            //                                approval3:Ext.getCmp('mon_approval_level').getValue('approval3')
        },
        width: 220,
        id: 'idsearchmonapp'
    });
    //    
    var tbrejectapp = new Ext.Toolbar({
        items: [searchrejectapp]
    });
    var gridrejectapproval= new Ext.grid.GridPanel({
        region:'center',
        //        sortable:true,
        store: strrejectapproval,
        cm: colmodelrejectapproval,        
        plugins:[actionrejectapproval],
        width: 580,
        //        height: 300,        
        //        title: 'Monitoring Ap',
        frame: true,
        stripeRows: true,        
        loadMask:true,
        tbar:tbrejectapp
        ,bbar: [new Ext.PagingToolbar({
                pageSize: ENDPAGE,
                store: strrejectapproval,
                displayInfo: true
            })],
        listeners:{
            cellclick:function(grid, rowIndex, columnIndex, e){
                var rec = grid.getStore().getAt(rowIndex); 
                var apv1=rec.get('approval1');
                var stapv1=rec.get('status_apv1');
                var apv2=rec.get('approval2');
                var stapv2=rec.get('status_apv2');
                var apv3=rec.get('approval3');
                var stapv3=rec.get('status_apv3');
                var app1=false;
                var app2=false;
                var app3=false;
                if (apv3 && stapv3){
                    app3=true;
                }
                if ((!apv3 && stapv3)||(apv3 && !stapv3)){
                    app3=false;
                }
                if (apv2 && stapv2){
                    app2=true;
                }
                if ((!apv2 && stapv2)||(apv2 && !stapv2)){
                    app2=false;
                }
                if (apv1 && stapv1){
                    app1=true;
                }
                if ((!apv1 && stapv1)||(apv1 && !stapv1)){
                    app2=false;
                }
              
                if (app1 && !app2 && !app3){
                    Ext.getCmp('reject_approval_level').setValue(1);
                }
                if (app2 && !app3){
                    Ext.getCmp('reject_approval_level').setValue(2);
                }
                if (app3){
                    Ext.getCmp('reject_approval_level').setValue(3);
                }
              
            }  
        }
        
        
    });
    var rejectapproval_form = new Ext.FormPanel({
        id: 'rejectapproval_acc',
        border: false,
        frame: true,
        autoScroll:true, 
        monitorValid: true,       
        bodyStyle:'padding-right:20px;',
        labelWidth: 130
        ,layout:'border'
        
        ,items: [headerrejectapproval
            ,gridrejectapproval
        ]
        ,listeners:{
            afterrender:function(){
                strrejectapprovalcabang.load();                
            },
            show:function(){
                strrejectapprovalcabang.reload();                                
            }
        }
    });
</script>
