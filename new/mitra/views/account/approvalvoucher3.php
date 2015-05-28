<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">	
    var strapprovalvoucher_akun4 = new Ext.data.Store({
        //        autoLoad:true,
        reader: new Ext.data.JsonReader({
            fields: [        
                
                'kd_voucher',                 
                'kd_akun', 
                'nama',
                'dk_akun',
                'dk_transaksi', 
                {name: 'debet', type: 'int'},
                {name: 'kredit', type: 'int'},
                'costcenter','keterangan_detail','ref_detail'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("account_app_voucher/get_rows_akun") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if(err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
    function set_total_avr4(){		
        var totaldebet=0;
        var totalkredit=0;
        //        var totalselisih=0;
                
        strapprovalvoucher_akun4.each(function(node){			
            totaldebet += parseInt(node.data.debet);
            totalkredit += parseInt(node.data.kredit);
        });
        //        totalselisih=totaldebet-totalkredit;
        Ext.getCmp('avr_t_debet4').setValue(totaldebet);
        Ext.getCmp('avr_t_kredit4').setValue(totalkredit);
        //        Ext.getCmp('evr_t_selisih').setValue(totalselisih);
                
    };
    strapprovalvoucher_akun4.on('load', function(){
        set_total_avr4();
		
		
    });
    
    strapprovalvoucher_akun4.on('update', function(){
        set_total_avr4();
		
		
    });
    strapprovalvoucher_akun4.on('remove',  function(){
        set_total_avr4();
		
    });
    strapprovalvoucher_akun4.on('removeAll',  function(){
        set_total_avr4();
		
    });
    var gridapvrakun4 = new Ext.grid.GridPanel({
        //        flex:2, 
        region:'south',
        split:true,
        id: 'idgridapvr_akun4',
        store: strapprovalvoucher_akun4,
        title:'Detail Voucher',
        stripeRows: true,
        width:420,
        height: 170,		
        border:true,
        frame:true,
        columns: [            
            {
                header: "Kode Akun",
                dataIndex: 'kd_akun',
                sortable: true,
                width: 80
            },{
                header: "Nama Akun",
                dataIndex: 'nama',
                sortable: true,
                width: 150
            },{
                header: "Akun D/K",
                dataIndex: 'dk_akun',
                sortable: true,
                width: 80,hidden:true
            },{
                header: "Transaksi D/K",
                dataIndex: 'dk_transaksi',
                sortable: true,          
                width: 80,hidden:true
            },{
                xtype: 'numbercolumn',
                header: "Debet",
                dataIndex: 'debet',
                align:'right',
                sortable: true,  
                format: '0,0',
                width: 80
            },{
                xtype: 'numbercolumn',
                header: "Kredit",
                dataIndex: 'kredit',
                align:'right',
                sortable: true,  
                format: '0,0',
                width: 80
            },{
                header: "Referensi",
                dataIndex: 'ref_detail',
                sortable: true,
                width: 200,hidden:false
            },{
                header: "Cost Center",
                dataIndex: 'costcenter',
                sortable: true,
                width: 100,hidden:false
            },{
                header: "Keterangan Detail",
                dataIndex: 'keterangan_detail',
                sortable: true,
                width: 200,hidden:false
            }
        ],
        bbar:[ '->','Total Debet :',{xtype: 'numericfield',currencySymbol: '',id: 'avr_t_debet4',fieldClass:'number',readOnly:true },
            'Total Kredit :',{xtype:'numericfield',currencySymbol: '',id: 'avr_t_kredit4',fieldClass:'number',  readOnly:true }
        ]
        //		
    });
    
    var stravrcabang4=new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [ 
                'kd_cabang',
                'nama_cabang'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("account_entry_voucher/get_cabang") ?>',
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
    
    var cmb_avr_cabang4= new Ext.form.ComboBox({
        fieldLabel:		'Cabang',
        mode:           'local',
        value:          '',
        triggerAction:  'all',
        forceSelection: true,
        editable:       false,
        name:           'nama_cabang',
        id:           	'avr_nama_cabang4',
        hiddenName:     'kd_cabang',
        displayField:   'nama_cabang',
        valueField:     'kd_cabang',
        anchor:			'75%',
        store:  stravrcabang4,
        allowBlank:false,
        listeners:{
            select: function(combo, records) {
                var vcabang = this.getValue();                
                strapprovalvoucher4.reload({params:{kd_cabang:vcabang}});
                Ext.getCmp('idgridapvr_akun4').getStore().removeAll();
                Ext.getCmp('avr_t_debet4').setValue(0);
                Ext.getCmp('avr_t_kredit4').setValue(0);
            }
        }
    });
    
    var dtpapp4_evr= new Ext.form.DateField(
    {
        fieldLabel: 'Tanggal Approval',
        id: 'id_dtpapp4_evr',
        name:'id_tglapproval'
        ,disabled:false
        ,allowBlank:false
        ,anchor: '75%'
        ,format:'Y-m-d',
        value: new Date()
    });
    
    var strapprovalvoucher4 = new Ext.data.Store({
        //        autoLoad:true,
        reader: new Ext.data.JsonReader({
            fields: [                
                {name: 'approval', type: 'bool'},
                'kd_voucher', 
                'tgl_transaksi', 
                'kd_transaksi', 
                'nama_transaksi', 
                'keterangan', 
                'referensi',
                'no_giro_cheque',
                'kd_jenis_voucher',                
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
                'approval3_date'
                ,'kd_cabang'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("account_app_voucher/get_rows_approval4") ?>',
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
    
    
    var cbGridapvr4 = new Ext.grid.CheckboxSelectionModel({
        id:'id_sel4',
        singleSelect:false 
    }
);    
    var checkApproval4=new Ext.grid.CheckColumn({
        header:'Approval',      
        id:'id_apvr_approval4',       
        dataIndex: 'approval',             
        width: 55
      
    });
    //reject action------------------------------------------------
    Ext.ns('confirmrejectapproval3');
    confirmrejectapproval3.Form=Ext.extend(Ext.form.FormPanel,{
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
            confirmrejectapproval3.Form.superclass.constructor.call(this, config);
        },initComponent: function(){
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
                        id: 'app3_kd_voucher',                
                        anchor: '90%',
                        readOnly:true,
                        allowBlank:false                                            
                    }, 
                    {
                        //                                            xtype: 'textfield',
                        fieldLabel: 'Alasan Reject <span class="asterix">*</span>',
                        name: 'reason',				
                        id: 'app3_reason',                
                        anchor: '90%',
                        //                    readOnly:true,
                        allowBlank:false                                            
                    },{         xtype:'hidden',       
                        name: 'kd_cabang',				
                        id: 'app3_kd_cabang',                
                        width:50,
                        //                                anchor: '90%',
                        readOnly:true
                    },
                    {xtype:'hidden',
                        //                                                    xtype: 'textfield',
                        fieldLabel: 'Kode Transaksi',                            
                        name: 'kd_transaksi',				
                        id: 'app3_kd_transaksi',                
                        width:50,
                        //                                anchor: '90%',
                        readOnly:true
                    },
                    {
                        xtype:'hidden',
                        //                                                    xtype: 'textfield',
                        fieldLabel: 'Kode Jenis Voucher',                            
                        name: 'kd_jenis_voucher',				
                        id: 'app3_kd_jenis_voucher',                
                        width:50,
                        //                                anchor: '90%',
                        readOnly:true
                    },{
                        fieldLabel: 'Keterangan',                            
                        name: 'keterangan',				
                        id: 'app3_keterangan',                
                        //                                                    width:50,
                        anchor: '90%',
                        readOnly:true
                    }  ,{
                        xtype:'hidden',
                        //                                                    fieldLabel: 'Keterangan',                            
                        name: 'approval_by',				
                        id: 'app3_approval_by',                
                        //                                                    width:50,
                        anchor: '90%',
                        readOnly:true
                    }  ,{
                        xtype:'hidden',
                        //                                                    fieldLabel: 'Keterangan',                            
                        name: 'approval_date',				
                        id: 'app3_approval_date',                
                        //                                                    width:50,
                        anchor: '90%',
                        readOnly:true
                    },{         xtype:'hidden',       
                        name: 'applevel',				
                        id: 'app3_approval_level',                
                        width:50,
                        //                                anchor: '90%',
                        readOnly:true
                    }  ,{         xtype:'hidden',       
                        name: 'tipe',				
                        id: 'app3_reject_type',                
                        width:50,
                        //                                anchor: '90%',
                        readOnly:true
                    }  
                ],
                buttons: [{
                        text: 'Submit',
                        id: 'btnsubmit_rejectapp3',
                        formBind: true,
                        scope: this,
                        handler: this.submit
                    }
                    , {
                        text: 'Close',
                        id: 'btnclose_rejectapp3',
                        scope: this,
                        handler: function(){
                            winrejectapproval3.hide();
                        }
                    }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            confirmrejectapproval3.Form.superclass.initComponent.apply(this, arguments);
        },onRender: function(){
        
            // call parent
            confirmrejectapproval3.Form.superclass.onRender.apply(this, arguments);
            
            // set wait message target
            this.getForm().waitMsgTarget = this.getEl();
            
            // loads form after initial layout
            // this.on('afterlayout', this.onLoadClick, this, {single:true});
        
        },
        reset: function(){
            this.getForm().reset();
        },
        submit: function(){
            //            var applevel=null,tipe=null;
            //            applevel=Ext.getCmp('rj_approval_level').getValue().getGroupValue();
            //            tipe=Ext.getCmp('rj_reject_type').getValue().getGroupValue();
            this.getForm().submit({
                url: this.url,
                scope: this,
                success: this.onSuccess,
                failure: this.onFailure
                ,params: {                    
                    reject_level:3
                                    
                }
                ,
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
            
            strapprovalvoucher4.reload();
            Ext.getCmp('id_formconfirmapp3').getForm().reset();
            winrejectapproval3.hide();
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
    
    Ext.reg('formconfirmapp3', confirmrejectapproval3.Form);    
    var winrejectapproval3 = new Ext.Window({
        id: 'id_winrejectapproval3',
        closeAction: 'hide',
        width: 400,
        height: 150,
        layout: 'fit',
        border: false,
        title:'Confirm Reject Approval',
        items: {
            id:'id_formconfirmapp3',
            xtype:'formconfirmapp3'
        },
        onHide: function(){
            //            Ext.getCmp('id_formaddmaster_account').getForm().reset();
        },
        onShow: function(){
            //            strcbparentakun.reload();
            
        }
    });
    var actionrejectapproval3 = new Ext.ux.grid.RowActions({
        header :'Reject',
        autoWidth: false,
        width: 50,
        actions:[{iconCls: 'icon-export', qtip: 'Reject Approval'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });	
    actionrejectapproval3.on('action', function(grid, record, action, row, col) {
        var kdvoucher=record.get('kd_voucher');
        if(kdvoucher){
            Ext.getCmp('app3_kd_voucher').setValue(kdvoucher);
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
                Ext.getCmp('app3_approval_level').setValue(1);  
                //                              Ext.getCmp('app3_reject_type').setDisabled(false); 
                Ext.getCmp('app3_approval_by').setValue(record.get('approval_by'));
                Ext.getCmp('app3_approval_date').setValue(record.get('approval_date'));
            }
            if (app2 && !app3){
                Ext.getCmp('app3_approval_level').setValue(2);
                Ext.getCmp('app3_reject_type').setValue(2);
                //                              Ext.getCmp('app3_reject_type').setDisabled(true);                  
                Ext.getCmp('app3_approval_by').setValue(record.get('approval2_by'));
                Ext.getCmp('app3_approval_date').setValue(record.get('approval2_date'));
            }
            if (app3){
                Ext.getCmp('app3_approval_level').setValue(3);
                Ext.getCmp('app3_reject_type').setValue(2);
                //                              Ext.getCmp('app3_reject_type').setDisabled(true);                  
                Ext.getCmp('app3_approval_by').setValue(record.get('approval3_by'));
                Ext.getCmp('app3_approval_date').setValue(record.get('approval3_date'));
            }

            Ext.getCmp('app3_kd_cabang').setValue(record.get('kd_cabang'));
            //                          Ext.getCmp('app3_nama_cabang').setValue(record.get('nama_cabang'));
                          
            Ext.getCmp('app3_kd_transaksi').setValue(record.get('kd_transaksi'));
            //                          Ext.getCmp('app3_nama_transaksi').setValue(record.get('nama_transaksi'));
                          
            Ext.getCmp('app3_kd_jenis_voucher').setValue(record.get('kd_jenis_voucher'));
            //                          Ext.getCmp('app3_title').setValue(record.get('title'));
            //              
            Ext.getCmp('app3_keterangan').setValue(record.get('keterangan'));
            winrejectapproval3.show();
        }
    }
)
    //------------------------------------------------
    var colmodelapvr4=new Ext.grid.ColumnModel({
        columns:[
            checkApproval4,
            {
                header:'No.Voucher',                          
                dataIndex: 'kd_voucher',
                width: 100                
            },{header:'Tanggal',             
                dataIndex: 'tgl_transaksi',
                width: 80                
            }
            ,{header:'Kode',             
                dataIndex: 'kd_transaksi',
                width: 80
                //                ,hidden:true                
            },{header:'Nama Transaksi',             
                dataIndex: 'nama_transaksi',
                width: 80
                //                ,hidden:true                
            }
            ,{header:'Keterangan',             
                dataIndex: 'keterangan',
                width: 200                
            },{header:'Referensi',             
                dataIndex: 'referensi',
                width: 80                
            },{header:'No.Giro/Cheque',             
                dataIndex: 'no_giro_cheque',
                width: 100                
            },actionrejectapproval3
           
            
        ]
        
    });
    
    var searchapvr4 = new Ext.app.SearchField({
        store: strapprovalvoucher4,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchapvr4'
    });
    
    var tbapvr4 = new Ext.Toolbar({
        items: [searchapvr4]
    });
    
    var lay_approval4 =  new Ext.grid.EditorGridPanel({
        region:'center',
        store: strapprovalvoucher4,
        cm: colmodelapvr4,        
        width: 580,
        //        height: 300,        
        title: 'Voucher To Approve',
        frame: true,
        stripeRows: true,
        sm:cbGridapvr4,
        loadMask:true,
        // specify the check column plugin on the grid so the plugin is initialized
        plugins:[checkApproval4,actionrejectapproval3] ,
        clicksToEdit: 1,
        tbar:tbapvr4,
        listeners:{
            show:function(){
                
            },
            'rowclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                var kdtrans=null;   
                if (sel.length > 0) {
                    kdtrans=sel[0].get('kd_voucher'); 
                }
                strapprovalvoucher_akun4.reload({params:{query:kdtrans}});				
            }
        },
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strapprovalvoucher4,
            displayInfo: true
        })
    });
    
    var head_approval4 = new Ext.FormPanel({
        id:'tglapproval4',
        height:80,
        frame:true,
        monitorValid: true, 
        labelWidth: 100,
        defaults: {width: 220},
        bodyStyle:'padding:5px 5px 0',
        items: [ dtpapp4_evr,cmb_avr_cabang4
        ]
    });
    
    var approvalvoucher4_frm = new Ext.FormPanel({
        id: 'approvalvoucher3',
        border: false,
        frame: true,
        autoScroll:true,        
        monitorValid: true,     
        //		tbar: tbtransaksimenu,		
        bodyStyle:'padding-right:20px;',
        labelWidth: 130,
        layout: 'border',
        items:[
            {
                region:'north',
                layout: 'column',
                border: false,
                items:[
                    {
                        columnWidth: .4,
                        layout: 'form',
                        border: false,
                        labelWidth: 100,
                        defaults: { labelSeparator: ''},
                        items: [
                            dtpapp4_evr,cmb_avr_cabang4
                        ]}
                ]
            }
            //            {
            //                region: 'north',
            //                height: 70,
            //                widht:80,
            //                minSize: 50,
            //                maxSize: 80,
            //                cmargins: '5 0 5 0',
            //                items:[head_approval2],
            //                header:false,
            //                collapsible: false,
            //                layout:'form'
            //            }
            ,lay_approval4,gridapvrakun4]
        ,
        buttons:[{
                text: 'Approve',
                formBind: true,
                handler: function(){    
                                   
                    var dataapvr = new Array();				
                    strapprovalvoucher4.each(function(node){
                        if(node.data.approval){
                            dataapvr.push(node.data);    
                        }                        
                    });
                    var tglapp=Ext.getCmp('id_dtpapp4_evr').getValue().format('Y-m-d');
                    var kdcabang=Ext.getCmp('avr_nama_cabang4').getValue();
                    if(dataapvr.length==0){
                        Ext.Msg.show({
                            title: 'Execute Approval Voucher',
                            msg: 'No Selected Data To Approve',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                            //                            ,
                            //                            fn: function(btn){
                            //                                if (btn == 'ok' ) {
                            //                                    
                            //                                }
                            //                            }
                        });
                        return;
                    }
                    Ext.getCmp('approvalvoucher3').getForm().submit({
                        url: '<?= site_url("account_app_voucher/update_row4") ?>',
                        scope: this,
                        params: {
                            data: Ext.util.JSON.encode(dataapvr),
                            tglapproval:tglapp,kd_cabang:kdcabang
                        },
                        waitMsg: 'Saving Data...',
                        success: function(form, action){
                            Ext.Msg.show({
                                title: 'Success',
                                msg: 'Form submitted successfully',
                                modal: true,
                                icon: Ext.Msg.INFO,
                                buttons: Ext.Msg.OK,
                                fn: function(btn){
                                    if (btn == 'ok' ) {
                                        clearAvr4();	
                                    }
                                }
                            });			            
                            clearAvr4();				
                        },
                        failure: function(form, action){        
                            var fe = Ext.util.JSON.decode(action.response.responseText);			            
                            Ext.Msg.show({
                                title: 'Error',
                                msg: fe.errMsg,
                                modal: true,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OK,
                                fn: function(btn){
                                    if (btn == 'ok' && fe.errMsg == 'Session Expired') {
                                        window.location = '<?= site_url("auth/login") ?>';
                                    }
                                }
                            });
			            
                        }			        
                    });	
                }
            }
            ,
            {
                text: 'Reset',
                handler: function(){
                    clearAvr4();	
                }
            }
        ],
        listeners:{
            afterrender:function(){                 
                stravrcabang4.reload();
                
            }
        }
    });
    
    function clearAvr4(){
        Ext.getCmp('avr_nama_cabang4').setValue('');
        Ext.getCmp('approvalvoucher3').getForm().reset();        

        strapprovalvoucher4.removeAll();
        Ext.getCmp('avr_t_debet4').setValue(0);
        Ext.getCmp('avr_t_kredit4').setValue(0);
        strapprovalvoucher_akun4.removeAll();
        
    }
</script>
