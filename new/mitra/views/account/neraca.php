<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">	
    var ds_nrc_thbl=createStoreArray([
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
    var cmbmonthnrc = new Ext.form.ComboBox({
        fieldLabel: 'Bulan Transaksi',
        id: 'cmb_monthnrc',
        name:'bulan',
        allowBlank:false,
        store: ds_nrc_thbl
        ,valueField:'mid'
        ,displayField:'mtext'
        ,mode:'local',
        forceSelection: true,
        triggerAction: 'all',anchor: '90%'
            
    });
    
    var arr_yearnrc = new Array();
    var arrgetnrc = new Array();
    var dtnrc = new Date();
    var now_yearnrc = dtnrc.getFullYear();
    var yearminnrc = now_yearnrc-5;
    var ytoarrnrc = yearminnrc;
    arrgetnrc=[];
    arr_yearnrc=[];
    for (var i = 0;i<=5;i++)
    {
        ytoarrnrc=yearminnrc+i;
        arrgetnrc.push(ytoarrnrc);
        arr_yearnrc.push(arrgetnrc);
        arrgetnrc=[];
    }
    
    var cmbyearnrc = new Ext.form.ComboBox({
    
        fieldLabel: 'Tahun Transaksi',
        id: 'cmb_yearnrc',
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
                this.store.loadData(arr_yearnrc);
    
            }
        }
    
    });
    
    var strneracacabang=createStoreData([ 
        'kd_cabang',
        'nama_cabang'
    ],'<?= site_url("account_entry_voucher/get_cabang") ?>');
            
    var cmb_nrc_cabang= new Ext.form.ComboBox({
        fieldLabel:		'Cabang',
        mode:           'local',
        value:          '',
        triggerAction:  'all',
        forceSelection: true,
        editable:       true,
        name:           'nama_cabang',
        id:           	'nrc_nama_cabang',
        hiddenName:     'kd_cabang',
        displayField:   'nama_cabang',
        valueField:     'kd_cabang',
        anchor:			'90%',
        emptyText:'All',
        store:  strneracacabang
       
    });
    var winnrcprint=createWinCetak('id_nrccetak','Print Neraca','nrcprintprev');
     var headerneraca={region:'north',
        layout: 'column',
        border: false,
        items: [
            {
                columnWidth: .3,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [cmbyearnrc
                    
                ]
           
            },
            {
                columnWidth: .3,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [cmbmonthnrc
                    
                ]
           
            },
            {
                columnWidth: .3,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [cmb_nrc_cabang
                    
                ]
           
            }],
        bbar:[
            {
                text:'Preview',
                //iconCls:'glapp-preview',
                id:'bbar_nrc_preview'
                ,handler: function(){
                                                            var vthbl=Ext.getCmp('cmb_yearnrc').getValue() + Ext.getCmp('cmb_monthnrc').getValue();
                                                            var kdcabang=Ext.getCmp('nrc_nama_cabang').getValue();
//strneraca.reload();
                                                            if(!vthbl){
                                                                return;
                                                            }
                                                            strneraca.reload({
                                                                params:{
                                                                    thbl:vthbl,
                                                                    kd_cabang:kdcabang}
                                                            })
                }
            },{
                text:'Cetak',
                //iconCls:'glapp-preview',
                id:'bbar_cetaknrc'
                ,handler: function(){
                    var vthbl=Ext.getCmp('cmb_yearnrc').getValue() + Ext.getCmp('cmb_monthnrc').getValue();
                    var kdcabang=Ext.getCmp('nrc_nama_cabang').getValue();
                    var nmcabang=Ext.getCmp('nrc_nama_cabang').getRawValue();
                    nmcabang=nmcabang.replace(' ','');
                    winnrcprint.show();
//                    console.log(Ext.getDom('nrcprintprev'));
                    Ext.getDom('nrcprintprev').src ='<?= site_url("account_neraca/print_form") ?>'+'/'+vthbl+'/'+'/'+kdcabang+'/'+nmcabang;
                }
            }
            
        ]
    };
    var strneraca= createStoreData(
    ['groupname_a','kd_akun_a','nama_a','subtotal_a','total_a','cls_a','isheader_a',
        'groupname_p','kd_akun_p','nama_p','subtotal_p','total_p'        
        ,'cls_p','isheader_p'
    ], '<?= site_url("account_neraca/get_rows") ?>');
    var groupneraca=[
        { header:"AKTIVA",align:"center", colspan:5},
        { header:"PASSIVA",align:"center", colspan:5}
        ];
     var groupnrc = new Ext.ux.grid.ColumnHeaderGroup({
        rows: [ groupneraca]
    });
   
     var gridneraca=new Ext.grid.GridPanel({
        region:'center',
        id:'grid_nrc',        
//        title: 'Sales By Location',
        //        width: 1000,
        stripeRows: true,
        height: 400,
        frame: true,
        border:true,
        loadMask:true,
        columnLines : true,
        store: strneraca,
        plugins: groupnrc,
        columns: [
        {dataIndex: 'groupname_a',
            header: 'Group Name',
            id:'id_groupname_nrc_a',            
            sortable: false ,     
            width: 125
            ,renderer:function(value, metaData, record, rowIndex, colIndex, store) {
                if(record.get('cls_a')== 'x-bls-header' || record.get('cls_a')== 'x-bls-header1' || record.get('cls_a')== 'x-bls-header3'|| record.get('cls_a')== 'x-bls-header6'){
                    //                                                metaData.attr = 'style="background-color: red;"';
                    if(record.get('groupname_a')){
                        metaData.css=record.get('cls_a');
                    }
                    
                }
                                            
                return value;
            }	
        },
        {dataIndex: 'kd_akun_a',
            header: 'Kode Akun',
            id:'id_kd_akun_nrc_a',            
            sortable: false ,   
            align:'center',
            width: 80
            ,renderer:function(value, metaData, record, rowIndex, colIndex, store) {
                if(record.get('cls_a')== 'x-bls-header' || record.get('cls_a')== 'x-bls-header1'  || record.get('cls_a')== 'x-bls-header2' || record.get('cls_a')== 'x-bls-header3' || record.get('cls_a')== 'x-bls-header4' || record.get('cls_a')== 'x-bls-header5' || record.get('cls_a')== 'x-bls-header6'){
                    //                                                metaData.attr = 'style="background-color: red;"';
                    metaData.css=record.get('cls_a');
                }
                                            
                return value;
            }
        },
        {dataIndex: 'nama_a',
            header: 'Nama Akun',
            id:'id_nama_akun_nrc_a',            
            sortable: false ,
            width: 300
            ,renderer:function(value, metaData, record, rowIndex, colIndex, store) {
                if(record.get('cls_a')== 'x-bls-header'  || record.get('cls_a')== 'x-bls-header1'  || record.get('cls_a')== 'x-bls-header2' || record.get('cls_a')== 'x-bls-header3' || record.get('cls_a')== 'x-bls-header4' || record.get('cls_a')== 'x-bls-header5' || record.get('cls_a')== 'x-bls-header6'){
                    //                                               metaData.attr = 'style="background-color: red;"';
                    metaData.css=record.get('cls_a');
                }
                                            
                return value;
            }	
        },
        {dataIndex: 'total_a',
            header: 'S/D Bulan Lalu', 
            id:'id_total_nrc_a',            
            sortable: false ,
            align: 'right',
            width: 100,
            renderer:function(value, metaData, record, rowIndex, colIndex, store) {
                var newvalue=value;
                if(record.get('cls_a')== 'x-bls-header'  || record.get('cls_a')== 'x-bls-header1'  || record.get('cls_a')== 'x-bls-header2'  || record.get('cls_a')== 'x-bls-header3' || record.get('cls_a')== 'x-bls-header4' || record.get('cls_a')== 'x-bls-header5' || record.get('cls_a')== 'x-bls-header6'){
                    //                                                metaData.attr = 'style="background-color: red;"';
//                    if(record.get('groupname_a')){
//                        metaData.css=record.get('cls_a');
//                    }
                    
                        metaData.css=record.get('cls_a');
                    
                    
                
                }
                newvalue=Ext.util.Format.number(value, '0,000');
                return newvalue;}
        },
        {dataIndex: 'subtotal_a',
            header: 'S/D Bulan Ini',
            id:'id_subtotal_nrc_a',            
            sortable: false ,
            width: 100, align: 'right',
            renderer:function(value, metaData, record, rowIndex, colIndex, store) {
                var newvalue=value;
                if(record.get('cls_a')== 'x-bls-header'  || record.get('cls_a')== 'x-bls-header1'  || record.get('cls_a')== 'x-bls-header2'  || record.get('cls_a')== 'x-bls-header3' || record.get('cls_a')== 'x-bls-header4' || record.get('cls_a')== 'x-bls-header5' || record.get('cls_a')== 'x-bls-header6'){
                    //                                                metaData.attr = 'style="background-color: red;"';
                    metaData.css=record.get('cls_a');
                
                }
                newvalue=Ext.util.Format.number(value, '0,000');
                return newvalue;
            }
        },{dataIndex: 'groupname_p',
            header: 'Group Name',
            id:'id_groupname_nrc_p',            
            sortable: false ,     
            width: 125
            ,renderer:function(value, metaData, record, rowIndex, colIndex, store) {
                if(record.get('cls_p')== 'x-bls-header' || record.get('cls_p')== 'x-bls-header1' || record.get('cls_p')== 'x-bls-header3' || record.get('cls_p')== 'x-bls-header6'){
                    //                                                metaData.attr = 'style="background-color: red;"';
                    if(record.get('groupname_p')){
                        metaData.css=record.get('cls_p');
                    }
                }
                                            
                return value;
            }	
        },
        {dataIndex: 'kd_akun_p',
            header: 'Kode Akun',
            id:'id_kd_akun_nrc_p',            
            sortable: false ,   
            align:'center',
            width: 80
            ,renderer:function(value, metaData, record, rowIndex, colIndex, store) {
                if(record.get('cls_p')== 'x-bls-header' || record.get('cls_p')== 'x-bls-header1'  || record.get('cls_p')== 'x-bls-header2' || record.get('cls_p')== 'x-bls-header3' || record.get('cls_p')== 'x-bls-header4' || record.get('cls_p')== 'x-bls-header5' || record.get('cls_p')== 'x-bls-header6'){
                    //                                                metaData.attr = 'style="background-color: red;"';
                    metaData.css=record.get('cls_p');
                }
                                            
                return value;
            }
        },
        {dataIndex: 'nama_p',
            header: 'Nama Akun',
            id:'id_nama_akun_nrc_p',            
            sortable: false ,
            width: 300
            ,renderer:function(value, metaData, record, rowIndex, colIndex, store) {
                if(record.get('cls_p')== 'x-bls-header'  || record.get('cls_p')== 'x-bls-header1'  || record.get('cls_p')== 'x-bls-header2' || record.get('cls_p')== 'x-bls-header3' || record.get('cls_p')== 'x-bls-header4' || record.get('cls_p')== 'x-bls-header5' || record.get('cls_p')== 'x-bls-header6'){
                    //                                               metaData.attr = 'style="background-color: red;"';
                    metaData.css=record.get('cls_p');
                }
                                            
                return value;
            }	
        },
        {dataIndex: 'total_p',
            header: 'S/D Bulan Lalu', 
            id:'id_total_nrc_p',            
            sortable: false ,
            align: 'right',
            width: 100,
            renderer:function(value, metaData, record, rowIndex, colIndex, store) {
                var newvalue=value;
                if(record.get('cls_p')== 'x-bls-header'  || record.get('cls_p')== 'x-bls-header1'  || record.get('cls_p')== 'x-bls-header2'  || record.get('cls_p')== 'x-bls-header3' || record.get('cls_p')== 'x-bls-header4' || record.get('cls_p')== 'x-bls-header5' || record.get('cls_p')== 'x-bls-header6'){
                    //                                                metaData.attr = 'style="background-color: red;"';
//                    if(record.get('groupname_p')){
//                        metaData.css=record.get('cls_p');
//                    }
                    metaData.css=record.get('cls_p');
                
                }
                newvalue=Ext.util.Format.number(value, '0,000');
                return newvalue;}
        },
        {dataIndex: 'subtotal_p',
            header: 'S/D Bulan Ini',
            id:'id_subtotal_nrc_p',            
            sortable: false ,
            width: 100, align: 'right',
            renderer:function(value, metaData, record, rowIndex, colIndex, store) {
                var newvalue=value;
                if(record.get('cls_p')== 'x-bls-header'  || record.get('cls_p')== 'x-bls-header1'  || record.get('cls_p')== 'x-bls-header2'  || record.get('cls_p')== 'x-bls-header3' || record.get('cls_p')== 'x-bls-header4' || record.get('cls_p')== 'x-bls-header5' || record.get('cls_p')== 'x-bls-header6'){
                    //                                                metaData.attr = 'style="background-color: red;"';
                    metaData.css=record.get('cls_p');
                
                }
                newvalue=Ext.util.Format.number(value, '0,000');
                return newvalue;
            }
        }]
     });
     var neraca_form = new Ext.FormPanel({
        id: 'neraca',
        border: false,
        frame: true,
        autoScroll:true, 
        monitorValid: true,       
        bodyStyle:'padding-right:20px;',
        labelWidth: 130,
        layout:'border',
        items:[
            headerneraca,gridneraca
        ],
        listeners:{
             afterrender:function(){                 
                strneracacabang.reload();
            },
            show:function(){
                strneracacabang.reload();
            }
            
        }
    });
</script>