<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>

<script type="text/javascript">	
    
    var dtpcetakapp= new Ext.form.DateField(
    {
        fieldLabel: 'Tanggal Awal',
        id: 'dtpcetakapp'
        ,allowBlank:false,anchor: '90%'
        ,value:new Date()
        //        ,vtype: 'daterangemonapp',
        //        endDateField: 'dtpmonappakhir'
        ,format:'Y-m-d'
        ,listeners:{
            select:function(){
                if(Ext.getCmp('dtpcetakappakhir').getValue()){
                    if(this.getValue()>Ext.getCmp('dtpcetakappakhir').getValue()){
                        var dtn=Ext.getCmp('dtpcetakappakhir').getValue();
                        this.setValue(dtn);
                    }
                }
                
                
            }
        }
    });
    var dtpcetakappakhir= new Ext.form.DateField(
    {
        fieldLabel: 'Tanggal Akhir',
        id: 'dtpcetakappakhir'
        ,allowBlank:false,anchor: '90%'
        //        ,vtype: 'daterangemonapp',
        //        startDateField: 'dtpmonapp'
        ,value:new Date()
        ,format:'Y-m-d'
        ,listeners:{
            select:function(){
                if(this.getValue()){
                    if(this.getValue()<Ext.getCmp('dtpcetakapp').getValue()){
                        var dtn=this.getValue();
                        Ext.getCmp('dtpcetakapp').setValue(dtn);
                        
                    }
                }
                
                
            }
        }
    });
    
    var strcetakapprovalcabang=createStoreData([ 
                'kd_cabang',
                'nama_cabang'
            ],'<?= site_url("account_entry_voucher/get_cabang") ?>' );
    var cmb_cetakapproval_cabang= new Ext.form.ComboBox({
        fieldLabel:		'Cabang',
        mode:           'local',
        value:          '',
        triggerAction:  'all',
        forceSelection: true,
//        editable:       false,
        name:           'nama_cabang',
        id:           	'cetakapproval_nama_cabang',
        hiddenName:     'kd_cabang',
        displayField:   'nama_cabang',
        valueField:     'kd_cabang',
        anchor:			'90%',
        store:  strcetakapprovalcabang,
        emptyText:'All'
    });
    
    var headercetakapproval={
        region:'north',
        layout: 'column',
        border: false,
        items: [{
                columnWidth: .3,
                layout: 'form',
                border: false,
                labelWidth: 80,
                defaults: { labelSeparator: ''},
                items: [dtpcetakapp,dtpcetakappakhir,cmb_cetakapproval_cabang,
                    //                    ,{
                    //                        xtype:'textfield',
                    //                        fieldLabel: 'No.Voucher',
                    //                        name: 'kd_voucher',				
                    //                        id: 'monappkdvoucher',                
                    //                        anchor: '90%',
                    //                        value: ''
                    //                    }
                    
                ]
           
            },{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [{xtype:'fieldset',
                        id:'cetak_approval_level_box',
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
                                fieldLabel: 'Status Approval',
                                labelSeparator: '',
                                boxLabel: 'Sudah Approval',
                                name: 'cetak_sb_approval',
                                id:'cetak_sb_approval'
                            },
                            {xtype: 'checkboxgroup',
                                fieldLabel: 'Approval Level',
                                id:'cetak_approval_level',
                                name:'cetak_approval_level',
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
                id:'bbarpreviewcetakappall'
                ,handler: function(){

                    var tglawal=null;
                    var tglakhir=null;
                    var kdcabang=null;
                    tglawal=Ext.getCmp('dtpcetakapp').getValue().format('Y-m-d');
                    tglakhir=Ext.getCmp('dtpcetakappakhir').getValue().format('Y-m-d');
                    kdcabang=Ext.getCmp('cetakapproval_nama_cabang').getValue();
                    var approval1=null,approval2=null,approval3=null,sbapproval=null;
                    
//                    approval1=Ext.getCmp('mon_approval_level').getValue('approval1'); 
//                    approval2=Ext.getCmp('mon_approval_level').getValue('approval2');   
//                    approval3=Ext.getCmp('mon_approval_level').getValue('approval3'); 
//                    sbapproval=Ext.getCmp('mon_sb_approval').getValue('mon_sb_approval'); 


                    strcetakapproval.reload({params:{
                            all:1,
                            tglawal:tglawal,
                            tglakhir:tglakhir,  
                            approval1:approval1,
                            approval2:approval2,
                            approval3:approval3,
                            sbapproval:sbapproval,
                            kdcabang:kdcabang,
                            stclose:false
                        }}); 


                                   
                }
            },'-',
            {
                text:'Preview With Parameter',
                //iconCls:'glapp-preview',
                id:'bbarpreviewcetakapp'
                ,handler: function(){

                    var tglawal=null;
                    var tglakhir=null;
                    var kdcabang=null;
                    tglawal=Ext.getCmp('dtpcetakapp').getValue().format('Y-m-d');
                    tglakhir=Ext.getCmp('dtpcetakappakhir').getValue().format('Y-m-d');
                    kdcabang=Ext.getCmp('cetakapproval_nama_cabang').getValue();
                    var approval1=null,approval2=null,approval3=null,sbapproval=null;
                    approval1=Ext.getCmp('cetak_approval_level').items.items[0].getValue(); 
                    approval2=Ext.getCmp('cetak_approval_level').items.items[1].getValue();   
                    approval3=Ext.getCmp('cetak_approval_level').items.items[2].getValue(); 
                    sbapproval=Ext.getCmp('cetak_sb_approval').getValue('cetak_sb_approval'); 

                    strcetakapproval.reload({params:{
                            all:null,
                            tglawal:tglawal,
                            tglakhir:tglakhir,  
                            approval1:approval1,
                            approval2:approval2,
                            approval3:approval3,
                            sbapproval:sbapproval,
                            kdcabang:kdcabang,
                            stclose:false
                        }}); 


                                   
                }
            }

        ]
    } 
    
    var strcetakapproval=createStoreData([
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
        'posting_date','idjurnal',
         'diterima_oleh',
        'no_giro_cheque',
        'tgl_jttempo','kd_cabang','nama_cabang'
    ],'<?= site_url("account_monapproval/get_rows") ?>');

    var wincetakvoucher=createWinCetak('id_wincetakvoucher','Print Voucher','cetakvoucherprint');
//    var wincetakvoucher = new Ext.Window({
//        id: 'id_wincetakvoucher',
//        title: 'Print Voucher',
//        closeAction: 'hide',
//        width: 900,
//        height: 450,
//        layout: 'fit',
//        border: false,
//        html:'<iframe style="width:100%;height:100%;" id="cetakvoucherprint" src=""></iframe>'
//    });
    
    var actioncetakvoucher = new Ext.ux.grid.RowActions({
        header :'Cetak',
        autoWidth: false,
        width: 50,
        actions:[{iconCls: 'icon-zoom', qtip: 'Cetak'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });	
    actioncetakvoucher.on('action', function(grid, record, action, row, col) {
        var kdvoucher=record.get('kd_voucher');
        if(kdvoucher){
            var urlprint='<?= site_url("account_monapproval/print_form") ?>'+'/'+kdvoucher;
//            window.open(urlprint);
//            wincetakvoucher.html='<iframe style="width:100%;height:100%;" id="cetakvoucherprint" src="'+urlprint+'"></iframe>';
            
            wincetakvoucher.show();
            Ext.getDom('cetakvoucherprint').src ='<?= site_url("account_monapproval/print_form") ?>'+'/'+kdvoucher ;
            
        }
        
    }); 
    
    var ccol_approval1 =new Ext.grid.CheckColumn({
        header: 'Approval1',
            dataIndex: 'approval1',
//            editable:false,
            width: 80
            ,sortable:true
    });
    var ccol_status_apv1 =new Ext.grid.CheckColumn({
        header: 'Status Approval1',
            dataIndex: 'status_apv1',
//            editable:false,
            width: 80
            ,sortable:true
    });
    var ccol_approval2 =new Ext.grid.CheckColumn({
        header: 'Approval2',
            dataIndex: 'approval2',
//            editable:false,
            width: 80
            ,sortable:true
    });
    var ccol_status_apv2 =new Ext.grid.CheckColumn({
        header: 'Status Approval2',
            dataIndex: 'status_apv2',
//            editable:false,
            width: 80
            ,sortable:true
    });
    var ccol_approval3 =new Ext.grid.CheckColumn({
        header: 'Approval3',
            dataIndex: 'approval3',
//            editable:false,
            width: 80
            ,sortable:true
    });
    var ccol_status_apv3 =new Ext.grid.CheckColumn({
        header: 'Status Approval3',
            dataIndex: 'status_apv3',
//            editable:false,
            width: 80
            ,sortable:true
    });
    
    var ccol_autopost =new Ext.grid.CheckColumn({
        header: 'Auto Posting Voucher',
            dataIndex: 'auto_posting_voucher',
//            editable:false,
            width: 80
            ,sortable:true
    });
    var ccol_status_posting =new Ext.grid.CheckColumn({
        header: 'Status Posting',
            dataIndex: 'status_posting',
//            editable:false,
            width: 80
            ,sortable:true
    });
    
    var colmodelcetakapproval=new Ext.grid.ColumnModel({
        columns:[ actioncetakvoucher,
            {header:'Tanggal',             
                dataIndex: 'tgl_transaksi',
                width: 80                
            }
            ,{
                header:'No.Voucher',                          
                dataIndex: 'kd_voucher',
                width: 100                
            }
            ,{header:'Kode',             
                dataIndex: 'kd_transaksi',
                width: 80,
                hidden:true                
            }
            ,{header:'Nama Transaksi',             
                dataIndex: 'nama_transaksi',
                width: 80
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
                //               ,hidden:true                
            }
            ,{header:'Referensi',             
                dataIndex: 'referensi',
                width: 80                
            }
            ,{header:'Keterangan',             
                dataIndex: 'keterangan',
                width: 200                
            },ccol_approval1,ccol_status_apv1,
//            {header:'Approval1',             
//                dataIndex: 'approval1',
//                width: 80                
//            },{header:'Status Approval1',             
//                dataIndex: 'status_apv1',
//                width: 80                
//            },
            {header:'Approval1 By',             
                dataIndex: 'approval_by',
                width: 80                
            },{header:'Approval1 Date',             
                dataIndex: 'approval_date',
                width: 80                
            },ccol_approval2,ccol_status_apv2
//            ,{header:'Approval2',             
//                dataIndex: 'approval2',
//                width: 80                
//            },{header:'Status Approval2',             
//                dataIndex: 'status_apv2',
//                width: 80                
//            }
            ,{header:'Approval2 By',             
                dataIndex: 'approval2_by',
                width: 80                
            },{header:'Approval2 Date',             
                dataIndex: 'approval2_date',
                width: 80                
            },ccol_approval3,ccol_status_apv3
//            ,{header:'Approval3',             
//                dataIndex: 'approval3',
//                width: 80                
//            },{header:'Status Approval3',             
//                dataIndex: 'status_apv3',
//                width: 80                
//            }
            ,{header:'Approval3 By',             
                dataIndex: 'approval3_by',
                width: 80                
            },{header:'Approval3 Date',             
                dataIndex: 'approval3_date',
                width: 80                
            }, ccol_autopost,ccol_status_posting
//            ,{header:'Auto Posting Jurnal',             
//                dataIndex: 'auto_posting_voucher',
//                width: 80                
//            },{header:'Status Posting',             
//                dataIndex: 'status_posting',
//                width: 80                
//            }
            ,{header:'Posting By',             
                dataIndex: 'posting_by',
                width: 80                
            },{header:'Posting Date',             
                dataIndex: 'posting_date',
                width: 80                
            },{header:'Id Jurnal',             
                dataIndex: 'idjurnal',
                width: 100                
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
    var searchcetakapp = new Ext.app.SearchField({
        store: strcetakapproval,
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
        id: 'idsearchcetakapp'
    });
    //    
    var tbcetakapp = new Ext.Toolbar({
        items: [searchcetakapp]
    });
    
      
    var gridcetakapproval= new Ext.grid.GridPanel({
        region:'center',
        store: strcetakapproval,
        cm: colmodelcetakapproval,        
        width: 580,
        plugins:[actioncetakvoucher],
        //        height: 300,        
        //        title: 'Monitoring Ap',
        frame: true,
        stripeRows: true,        
        loadMask:true,
        tbar:tbcetakapp
         ,bbar: [new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strcetakapproval,
            displayInfo: true
        })]
        
    });
    var cetakapproval_form = new Ext.FormPanel({
        id: 'cetakvoucher',
        border: false,
        frame: true,
        autoScroll:true, 
        monitorValid: true,       
        bodyStyle:'padding-right:20px;',
        labelWidth: 130,
        layout:'border'
        ,items: [headercetakapproval
            ,gridcetakapproval
        ]
        ,listeners:{
            afterrender:function(){
                strcetakapprovalcabang.load();
                //                strmonjurcabang.reload();
                //                Ext.getCmp('id_dk_monjur').setValue('dk');
            },
            show:function(){
//                strcetakapprovalcabang.load();
                //                strmonjurcabang.reload();
                //                Ext.getCmp('id_dk_monjur').setValue('dk');
                //                var dtbbp = new Date();
                //                Ext.getCmp('dtpjur').setValue(dtbbp);

                
            }
        }
    });
</script>
