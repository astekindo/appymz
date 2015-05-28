<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">	
    var strapprovalvoucher_akun3 = new Ext.data.Store({
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
    function set_total_avr3(){		
        var totaldebet=0;
        var totalkredit=0;
        //        var totalselisih=0;
                
        strapprovalvoucher_akun3.each(function(node){			
            totaldebet += parseInt(node.data.debet);
            totalkredit += parseInt(node.data.kredit);
        });
        //        totalselisih=totaldebet-totalkredit;
        Ext.getCmp('avr_t_debet3').setValue(totaldebet);
        Ext.getCmp('avr_t_kredit3').setValue(totalkredit);
        //        Ext.getCmp('evr_t_selisih').setValue(totalselisih);
                
    };
    strapprovalvoucher_akun3.on('load', function(){
        set_total_avr3();
		
		
    });
    
    strapprovalvoucher_akun3.on('update', function(){
        set_total_avr3();
		
		
    });
    strapprovalvoucher_akun3.on('remove',  function(){
        set_total_avr3();
		
    });
    strapprovalvoucher_akun3.on('removeAll',  function(){
        set_total_avr3();
		
    });
    var gridapvrakun3 = new Ext.grid.GridPanel({
        //        flex:2, 
        region:'south',
        split:true,
        id: 'idgridapvr_akun3',
        store: strapprovalvoucher_akun3,
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
        bbar:[ '->','Total Debet :',{xtype: 'numericfield',currencySymbol: '',id: 'avr_t_debet3',fieldClass:'number',readOnly:true },
            'Total Kredit :',{xtype:'numericfield',currencySymbol: '',id: 'avr_t_kredit3',fieldClass:'number',  readOnly:true }
        ]
        //		
    });
    
    var stravrcabang3=new Ext.data.Store({
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
    
    var cmb_avr_cabang3= new Ext.form.ComboBox({
        fieldLabel:		'Cabang',
        mode:           'local',
        value:          '',
        triggerAction:  'all',
        forceSelection: true,
        editable:       false,
        name:           'nama_cabang',
        id:           	'avr_nama_cabang3',
        hiddenName:     'kd_cabang',
        displayField:   'nama_cabang',
        valueField:     'kd_cabang',
        anchor:			'75%',
        store:  stravrcabang3,
        allowBlank:false,
        listeners:{
            select: function(combo, records) {
                var vcabang = this.getValue();                
                strapprovalvoucher3.reload({params:{kd_cabang:vcabang}});
            }
        }
    });
    
    var dtpapp3_evr= new Ext.form.DateField(
    {
        fieldLabel: 'Tanggal Posting',
        id: 'id_dtpapp3_evr',
        name:'id_tglapproval'
        ,allowBlank:false,anchor: '75%'
        ,format:'Y-m-d',
        editable:false,
        value: new Date(),
        maxValue : new Date(),
        listeners:{
            select:function(f,d){
                Ext.getCmp('idgrid_hposting').getSelectionModel().clearSelections();
            }
        }
    });
    
    var strapprovalvoucher3 = new Ext.data.Store({
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
                'no_giro_cheque','lastapproval_date'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("account_app_voucher/get_rows_approval3") ?>',
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
    
    
    var cbGridapvr3 = new Ext.grid.CheckboxSelectionModel({
//        id:'id_sel3',
        singleSelect:false 
        ,listeners: {
            rowselect:function(sm,ri,rec){
                if (sm.getCount()) {
                    var dtapv=Ext.getCmp('id_dtpapp3_evr').getValue();
                   var dtsel =rec.get('lastapproval_date');
//                   var parts = dtapv.split('-');
                   dtapv = new Date(dtapv);
                   var parts = dtsel.split('-');
                   dtsel= new Date(parts[0], parts[1]-1, parts[2]);
//                   console.log(dtapv);
//                   console.log(dtsel);

                   if(dtapv<dtsel){
                       sm.deselectRow(ri);
                   }
                }
            }
//            ,
//            selectionchange: function(sm) {
//                if (sm.getCount()) {
////                    var recsel=sm.getCount();                    
////                    var sel = sm.getSelections();
////                    var dtsel=new Date(); 
////                    var dtapv=Ext.getCmp('id_dtpapp3_evr').getValue();
////                    for (i=0;i<recsel;i++){
////                        dtsel=sel[i].get('tgl_transaksi');
////                        
////                    }
////                    
////                    console.log(dtsel);
//                } else {
//                    console.log('kosong');
//                }
//            }
        }
    }
);    
    var checkApproval3=new Ext.grid.CheckColumn({
        header:'Approval',      
        id:'id_apvr_approval3',       
        dataIndex: 'approval',             
        width: 55
        ,hidden:true
//        ,listeners:{
//            mousedown  :function(){
////                var rec = Ext.getCmp('idgrid_hposting').getStore().getAt(rowIndex);
////                var dateappr=Ext.getCmp('id_dtpapp3_evr').getValue();
////                var daterec=sel[0].get('tgl_transaksi'); 
//                console.log('test');
////                console.log(rec);
//            }
//        }
      
    });
    
    var colmodelapvr3=new Ext.grid.ColumnModel({
        columns:[
            cbGridapvr3,
            checkApproval3,
            {
                header:'No.Voucher',                          
                dataIndex: 'kd_voucher',
                width: 100                
            },{header:'Tanggal',             
                dataIndex: 'tgl_transaksi',
                width: 80                
            },{header:'Last Approval',             
                dataIndex: 'lastapproval_date',
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
            }
           
            
        ]
        
    });
    
    var searchapvr3 = new Ext.app.SearchField({
        store: strapprovalvoucher3,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchapvr3'
    });
    
    var tbapvr3 = new Ext.Toolbar({
        items: [searchapvr3]
    });
    
    var lay_approval3 =  new Ext.grid.EditorGridPanel({
        region:'center',
        id:'idgrid_hposting',
        store: strapprovalvoucher3,
        cm: colmodelapvr3,        
        width: 580,
        //        height: 300,        
        title: 'Voucher To Approve',
        frame: true,
        stripeRows: true,
        sm:cbGridapvr3,
        loadMask:true,
        // specify the check column plugin on the grid so the plugin is initialized
        plugins:[checkApproval3] ,
        clicksToEdit: 1,
        tbar:tbapvr3,
        listeners:{
            show:function(){
                
            },
            'rowclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                var kdtrans=null;   
                if (sel.length > 0) {
                    kdtrans=sel[0].get('kd_voucher'); 
                    strapprovalvoucher_akun3.reload({params:{query:kdtrans}});
                }
                
                				
            }
        },
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strapprovalvoucher3,
            displayInfo: true
        })
    });
    
    var head_approval3 = new Ext.FormPanel({
        id:'tglapproval3',
        height:80,
        frame:true,
        monitorValid: true, 
        labelWidth: 100,
        defaults: {width: 220},
        bodyStyle:'padding:5px 5px 0',
        items: [ dtpapp3_evr,cmb_avr_cabang3
        ]
    });
    
    var approvalvoucher3_frm = new Ext.FormPanel({
        id: 'postingvoucher',
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
                            dtpapp3_evr,cmb_avr_cabang3
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
//                items:[head_approval3],
//                header:false,
//                collapsible: false,
//                layout:'form'
//            }
            ,lay_approval3,gridapvrakun3]
        ,
        buttons:[{
                text: 'Approve',
                formBind: true,
                handler: function(){    
                                   
                    var dataapvr = new Array();				
//                    strapprovalvoucher3.each(function(node){
//                        if(node.data.approval){
//                            dataapvr.push(node.data);    
//                        }                        
//                    });
//                    console.log(dataapvr);
//                    dataapvr=new Array();	
                    var strdata=Ext.getCmp('idgrid_hposting').getSelectionModel().getSelections();
                    for(var i=0;i<strdata.length; i++){
                        dataapvr.push(strdata[i].data);   
                    };
                    
//                    console.log(dataapvr);
                    var tglapp=Ext.getCmp('id_dtpapp3_evr').getValue().format('Y-m-d');
                    var kdcabang=Ext.getCmp('avr_nama_cabang3').getValue();
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
                    Ext.getCmp('postingvoucher').getForm().submit({
                        url: '<?= site_url("account_app_voucher/update_row3") ?>',
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
                                        clearAvr3();	
                                    }
                                }
                            });			            
                            clearAvr3();				
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
                    clearAvr3();	
                }
            }
        ],
        listeners:{
            afterrender:function(){                 
                stravrcabang3.reload();
                
            }
        }
    });
    
    function clearAvr3(){
        Ext.getCmp('avr_nama_cabang3').setValue('');
        Ext.getCmp('postingvoucher').getForm().reset();        

        strapprovalvoucher3.removeAll();
        Ext.getCmp('avr_t_debet3').setValue(0);
        Ext.getCmp('avr_t_kredit3').setValue(0);
        strapprovalvoucher_akun3.removeAll();
        
    }
</script>