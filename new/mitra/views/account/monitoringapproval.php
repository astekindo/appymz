<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>

<script type="text/javascript">	
    var strmonapprovalvoucher_akun = new Ext.data.Store({
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
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
    function set_total_avr(){		
        var totaldebet=0;
        var totalkredit=0;
        //        var totalselisih=0;
                
        strmonapprovalvoucher_akun.each(function(node){			
            totaldebet += parseInt(node.data.debet);
            totalkredit += parseInt(node.data.kredit);
        });
        //        totalselisih=totaldebet-totalkredit;
        Ext.getCmp('mon_avr_t_debet').setValue(totaldebet);
        Ext.getCmp('mon_avr_t_kredit').setValue(totalkredit);
        //        Ext.getCmp('evr_t_selisih').setValue(totalselisih);
                
    };
    strmonapprovalvoucher_akun.on('load', function(){
        set_total_avr();
		
		
    });
    
    strmonapprovalvoucher_akun.on('update', function(){
        set_total_avr();
		
		
    });
    strmonapprovalvoucher_akun.on('remove',  function(){
        set_total_avr();
		
    });
    strmonapprovalvoucher_akun.on('removeAll',  function(){
        set_total_avr();
		
    });
    var gridmonapvrakun = new Ext.grid.GridPanel({
        //        flex:2, 
        region:'center',
        split:true,
        id: 'mon_idgridapvr_akun',
        store: strmonapprovalvoucher_akun,
        title:'Detail Voucher',
        stripeRows: true,
        //        width:420,
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
        bbar:[ '->','Total Debet :',{xtype: 'numericfield',currencySymbol: '',id: 'mon_avr_t_debet',fieldClass:'number',readOnly:true },
            'Total Kredit :',{xtype:'numericfield',currencySymbol: '',id: 'mon_avr_t_kredit',fieldClass:'number',  readOnly:true }
        ]
        //		
    });
    
    var strmonapprovalreject1=createStoreData([
                'approval_reject',                 
                'reason', 
                'approval_by',
                'reject_by',
                'approval_date',
                'reject_date',
                'reject_level'
            ], 
            '<?= site_url("account_rejectapp/get_rows_reject") ?>');
            
    var gridmonapvrreject1 = new Ext.grid.GridPanel({
        //        flex:2, 
        region:'east',
        split:true,
        id: 'mon_idgridapvr_reject1',
        store: strmonapprovalreject1,
        title:'Detail Reject',
        stripeRows: true,
        width:350,
        height: 170,		
        border:true,
        frame:true,
        columns: [            
            {
                header: "Approval Reject",
                dataIndex: 'approval_reject',
                sortable: true,
                width: 100
            },{
                header: "Alasan",
                dataIndex: 'reason',
                sortable: true,
                width: 150
            },{
                header: "Reject Level",
                dataIndex: 'reject_level',
                sortable: true,
                width: 100
            },{
                header: "Approval By",
                dataIndex: 'approval_by',
                sortable: true,
                width: 80
            },{
                header: "Reject By",
                dataIndex: 'reject_by',
                sortable: true,          
                width: 80
            },{
                
                header: "Approval Date",
                dataIndex: 'approval_date',
                sortable: true,                 
                width: 80
            },{
              
                header: "Reject Date",
                dataIndex: 'reject_date',
                sortable: true,                 
                width: 80
            }
        ]
        //		
    });
    
    var dtpmonapp= new Ext.form.DateField(
    {
        fieldLabel: 'Tanggal Awal',
        id: 'dtpmonapp'
        ,value:new Date()
        ,allowBlank:false,anchor: '90%'
        //        ,vtype: 'daterangemonapp',
        //        endDateField: 'dtpmonappakhir'
        ,format:'Y-m-d'
        ,listeners:{
            select:function(){
                if(Ext.getCmp('dtpmonappakhir').getValue()){
                    if(this.getValue()>Ext.getCmp('dtpmonappakhir').getValue()){                        
                        var dtn=Ext.getCmp('dtpmonappakhir').getValue();
                        this.setValue(dtn);
                    }
                }
                
                
            }
        }
    });
    var dtpmonappakhir= new Ext.form.DateField(
    {
        fieldLabel: 'Tanggal Akhir',
        id: 'dtpmonappakhir'
        ,allowBlank:false,anchor: '90%'
        //        ,vtype: 'daterangemonapp',
        //        startDateField: 'dtpmonapp'
        ,format:'Y-m-d'
        ,value:new Date()
        ,listeners:{
            select:function(){
                if(this.getValue()){
                    if(this.getValue()<Ext.getCmp('dtpmonapp').getValue()){
                        var dtn=this.getValue();
                        Ext.getCmp('dtpmonapp').setValue(dtn);
                        
                    }
                }
                
                
            }
        }
    });
    var strmonapprovalcabang=createStoreData([ 
                'kd_cabang',
                'nama_cabang'
            ],'<?= site_url("account_entry_voucher/get_cabang") ?>' );
    var cmb_monapproval_cabang= new Ext.form.ComboBox({
        fieldLabel:		'Cabang',
        mode:           'local',
        value:          '',
        triggerAction:  'all',
        forceSelection: true,
//        editable:       false,
        name:           'nama_cabang',
        id:           	'monapproval_nama_cabang',
        hiddenName:     'kd_cabang',
        displayField:   'nama_cabang',
        valueField:     'kd_cabang',
        anchor:			'90%',
        store:  strmonapprovalcabang,
        emptyText:'All'
    });
    
    var headermonapproval={
        region:'north',
        layout: 'column',
        border: false,
        height:150,
        items: [{
                columnWidth: .3,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [dtpmonapp,dtpmonappakhir,cmb_monapproval_cabang,
                          
                    
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
                        id:'mon_approval_level_box',
                        name:'approval_level_box',
                        title: 'Parameter',
                        //                        checkboxToggle: true,
                        collapsible:false,
                        autoHeight:true,  
                        anchor: '90%',
                        //                        collapsed :true,
                        //                        disabled:true,
                        items:[
                            {xtype: 'checkbox',
                                checked: true,
                                fieldLabel: 'Status Close',
                                labelSeparator: '',
                                boxLabel: 'Closed',
                                name: 'mon_close',
                                id:'mon_close',
                                listeners:{
                                    check:function(cb, checked){
                                        
                                    }
                                }
                            },
                            {xtype: 'checkbox',
                                checked: true,
                                fieldLabel: 'Status Approval',
                                labelSeparator: '',
                                boxLabel: 'Sudah Approval',
                                name: 'mon_sb_approval',
                                id:'mon_sb_approval'
                            },
                            {xtype: 'checkboxgroup',
                                fieldLabel: 'Approval Level',
                                id:'mon_approval_level',
                                name:'mon_approval_level',
                                items: [
                                    {boxLabel: 'Level 1', name: 'approval1' ,checked:false},
                                    {boxLabel: 'Level 2', name: 'approval2',checked:false},
                                    {boxLabel: 'Level 3', name: 'approval3',checked:false}
               
                                ]}]
                        
                    }            
                ]
           
            }],
        bbar:[
            {
                text:'Preview All',
                //iconCls:'glapp-preview',
                id:'bbarpreviewmonappall'
                ,handler: function(){

                    var tglawal=null;
                    var tglakhir=null;
                    var kdcabang=null;
                    tglawal=Ext.getCmp('dtpmonapp').getValue().format('Y-m-d');
                    tglakhir=Ext.getCmp('dtpmonappakhir').getValue().format('Y-m-d');
                    kdcabang=Ext.getCmp('monapproval_nama_cabang').getValue();
                    var approval1=null,approval2=null,approval3=null,sbapproval=null;
                    
//                    approval1=Ext.getCmp('mon_approval_level').getValue('approval1'); 
//                    approval2=Ext.getCmp('mon_approval_level').getValue('approval2');   
//                    approval3=Ext.getCmp('mon_approval_level').getValue('approval3'); 
//                    sbapproval=Ext.getCmp('mon_sb_approval').getValue('mon_sb_approval'); 


                    strmonapproval.reload({params:{
                            all:1,
                            tglawal:tglawal,
                            tglakhir:tglakhir,  
                            approval1:approval1,
                            approval2:approval2,
                            approval3:approval3,
                            sbapproval:sbapproval,
                            kdcabang:kdcabang
                        }}); 


                                   
                }
            },'-',
            {
                text:'Preview With Parameter',
                //iconCls:'glapp-preview',
                id:'bbarpreviewmonapp'
                ,handler: function(){

                    var tglawal=null;
                    var tglakhir=null;
                    var kdcabang=null;
                    var stclose=null;
                    tglawal=Ext.getCmp('dtpmonapp').getValue().format('Y-m-d');
                    tglakhir=Ext.getCmp('dtpmonappakhir').getValue().format('Y-m-d');
                    kdcabang=Ext.getCmp('monapproval_nama_cabang').getValue();
                    var approval1=null,approval2=null,approval3=null,sbapproval=null;
                    var app1=Ext.getCmp('mon_approval_level').items.items[0].getValue();
                    console.log(app1); 
                    approval1=Ext.getCmp('mon_approval_level').items.items[0].getValue(); 
                    approval2=Ext.getCmp('mon_approval_level').items.items[1].getValue();   
                    approval3=Ext.getCmp('mon_approval_level').items.items[2].getValue(); 
                    sbapproval=Ext.getCmp('mon_sb_approval').getValue('mon_sb_approval'); 
                    stclose=Ext.getCmp('mon_close').getValue('mon_close'); 

                    strmonapproval.reload({params:{
                            all:null,
                            tglawal:tglawal,
                            tglakhir:tglakhir,  
                            approval1:approval1,
                            approval2:approval2,
                            approval3:approval3,
                            sbapproval:sbapproval,
                            kdcabang:kdcabang,
                            stclose:stclose
                        }}); 


                                   
                }
            }

        ]
    } 
    
    
    var strmonapproval=createStoreData([
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
        'tgl_jttempo','kd_cabang','nama_cabang',
        {name:'status_close',type:'bool'},
        'close_by','close_date','count_reject'
        
        
    ],'<?= site_url("account_monapproval/get_rows") ?>');
    var col_stclose =new Ext.grid.CheckColumn({
        header: 'Status Close',
            dataIndex: 'status_close',
//            editable:false,
            width: 80
            ,sortable:true
    });
    
    var col_approval1 =new Ext.grid.CheckColumn({
        header: 'Approval1',
            dataIndex: 'approval1',
//            editable:false,
            width: 80
            ,sortable:true
    });
    var col_status_apv1 =new Ext.grid.CheckColumn({
        header: 'Status Approval1',
            dataIndex: 'status_apv1',
//            editable:false,
            width: 80
            ,sortable:true
    });
    var col_approval2 =new Ext.grid.CheckColumn({
        header: 'Approval2',
            dataIndex: 'approval2',
//            editable:false,
            width: 80
            ,sortable:true
    });
    var col_status_apv2 =new Ext.grid.CheckColumn({
        header: 'Status Approval2',
            dataIndex: 'status_apv2',
//            editable:false,
            width: 80
            ,sortable:true
    });
    var col_approval3 =new Ext.grid.CheckColumn({
        header: 'Approval3',
            dataIndex: 'approval3',
//            editable:false,
            width: 80
            ,sortable:true
    });
    var col_status_apv3 =new Ext.grid.CheckColumn({
        header: 'Status Approval3',
            dataIndex: 'status_apv3',
//            editable:false,
            width: 80
            ,sortable:true
    });
    
    var col_autopost =new Ext.grid.CheckColumn({
        header: 'Auto Posting Voucher',
            dataIndex: 'auto_posting_voucher',
//            editable:false,
            width: 80
            ,sortable:true
    });
    var col_status_posting =new Ext.grid.CheckColumn({
        header: 'Status Posting',
            dataIndex: 'status_posting',
//            editable:false,
            width: 80
            ,sortable:true
    });
    var colmodelmonapproval=new Ext.grid.ColumnModel({
        columns:[ 
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
            ,col_approval1,col_status_apv1
//            ,{header:'Approval1',             
//                dataIndex: 'approval1',
//                width: 80     
//                ,sortable:true
//            }
//            ,{header:'Status Approval1',             
//                dataIndex: 'status_apv1',
//                width: 80    
//                ,sortable:true
//            }
            ,{header:'Approval1 By',             
                dataIndex: 'approval_by',
                width: 80     
                ,sortable:true
            },{header:'Approval1 Date',             
                dataIndex: 'approval_date',
                width: 80     
                ,sortable:true
            }
             ,col_approval2,col_status_apv2
//            ,{header:'Approval2',             
//                dataIndex: 'approval2',
//                width: 80     
//                ,sortable:true
//            },{header:'Status Approval2',             
//                dataIndex: 'status_apv2',
//                width: 80     
//                ,sortable:true
//            }
            ,{header:'Approval2 By',             
                dataIndex: 'approval2_by',
                width: 80     
                ,sortable:true
            },{header:'Approval2 Date',             
                dataIndex: 'approval2_date',
                width: 80     
                ,sortable:true
            },col_approval3,col_status_apv3
//            ,{header:'Approval3',             
//                dataIndex: 'approval3',
//                width: 80     
//                ,sortable:true
//            },{header:'Status Approval3',             
//                dataIndex: 'status_apv3',
//                width: 80     
//                ,sortable:true
//            }
            ,{header:'Approval3 By',             
                dataIndex: 'approval3_by',
                width: 80     
                ,sortable:true
            },{header:'Approval3 Date',             
                dataIndex: 'approval3_date',
                width: 80     
                ,sortable:true
            }
            , col_autopost,col_status_posting
//            ,{header:'Auto Posting Jurnal',             
//                dataIndex: 'auto_posting_voucher',
//                width: 80     
//                ,sortable:true
//            },{header:'Status Posting',             
//                dataIndex: 'status_posting',
//                width: 80     
//                ,sortable:true
//            }
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
            },col_stclose
            ,{header:'Close By',             
                dataIndex: 'close_by',
                width: 100    
                ,sortable:true
            },{header:'Close Date',             
                dataIndex: 'close_date',
                width: 100    
                ,sortable:true
            },{header:'Count Reject',             
                dataIndex: 'count_reject',
                width: 100    
                ,sortable:true
            }
           
            
        ]
        
    });
    //    var mtglawal=Ext.getCmp('dtpmonapp').getValue().format('Y-m-d');
    var searchmonapp = new Ext.app.SearchField({
        store: strmonapproval,
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
    var tbmonapp = new Ext.Toolbar({
        items: [searchmonapp]
    });
    var gridmonapproval= new Ext.grid.GridPanel({
        region:'center',
//        sortable:true,
        store: strmonapproval,
        cm: colmodelmonapproval,        
        width: 580,
        //        height: 300,        
        //        title: 'Monitoring Ap',
        frame: true,
        stripeRows: true,        
        loadMask:true,
        tbar:tbmonapp
        ,bbar: [new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strmonapproval,
            displayInfo: true
        })]
    ,listeners:{
            show:function(){
                
            },
            'rowclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                var kdtrans=null;   
                var kdc=null;
                if (sel.length > 0) {
                    kdtrans=sel[0].get('kd_voucher'); 
                    kdc=sel[0].get('kd_cabang'); 
                }
                strmonapprovalvoucher_akun.reload({params:{query:kdtrans}});
                strmonapprovalreject1.reload({params:{kd_voucher:kdtrans,kd_cabang:kdc}});
            }
        }
        
        
    });
//    gridmonapvrreject1
//gridmonapvrakun
    var monapproval_form = new Ext.FormPanel({
        id: 'monitoringapproval',
        border: false,
        frame: true,
        autoScroll:true, 
        monitorValid: true,       
        bodyStyle:'padding-right:20px;',
        labelWidth: 130
        ,layout:'border'
        ,items: [headermonapproval
            ,gridmonapproval,{
                region:'south',
                layout: 'border',
                border: false,
                height: 200,
                items:[gridmonapvrakun,gridmonapvrreject1]
            }
        ]
        ,listeners:{
            afterrender:function(){
                strmonapprovalcabang.load();                
            },
            show:function(){
                strmonapprovalcabang.reload();                                
            }
        }
    });
</script>