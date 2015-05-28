<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">	
    var ds_ns_thbl=createStoreArray([
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
    var cmbmonthns = new Ext.form.ComboBox({
        fieldLabel: 'Bulan Transaksi',
        id: 'cmb_monthns',
        name:'bulan',
        allowBlank:false,
        store: ds_ns_thbl
        ,valueField:'mid'
        ,displayField:'mtext'
        ,mode:'local',
        forceSelection: true,
        triggerAction: 'all',anchor: '90%'
            
    });
    
    var arr_yearns = new Array();
    var arrgetns = new Array();
    var dtns = new Date();
    var now_yearns = dtns.getFullYear();
    var yearminns = now_yearns-5;
    var ytoarrns = yearminns;
    arrgetns=[];
    arr_yearns=[];
    for (var i = 0;i<=5;i++)
    {
        ytoarrns=yearminns+i;
        arrgetns.push(ytoarrns);
        arr_yearns.push(arrgetns);
        arrgetns=[];
    }
    
    var cmbyearns = new Ext.form.ComboBox({
    
        fieldLabel: 'Tahun Transaksi',
        id: 'cmb_yearns',
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
                this.store.loadData(arr_yearns);
    
            }
        }
    
    });
    
    var strnscabang=createStoreData([ 
        'kd_cabang',
        'nama_cabang'
    ],'<?= site_url("account_entry_voucher/get_cabang") ?>');
            
    var cmb_ns_cabang= new Ext.form.ComboBox({
        fieldLabel:		'Cabang',
        mode:           'local',
        value:          '',
        triggerAction:  'all',
        forceSelection: true,
        editable:       true,
        name:           'nama_cabang',
        id:           	'ns_nama_cabang',
        hiddenName:     'kd_cabang',
        displayField:   'nama_cabang',
        valueField:     'kd_cabang',
        anchor:			'90%',
        emptyText:'All',
        store:  strnscabang
       
    });
    var winnsprint=createWinCetak('id_nscetak','Print Neraca Saldo','nsprintprev');
    
    var headerns=
        {region:'north',
        layout: 'column',
        border: false,
        items: [
            {
                columnWidth: .3,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [cmbyearns
                    
                ]
           
            },
            {
                columnWidth: .3,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [cmbmonthns
                    
                ]
           
            },
            {
                columnWidth: .3,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [cmb_ns_cabang
                    
                ]
           
            }],
        bbar:[
            {
                text:'Preview',
                //iconCls:'glapp-preview',
                id:'bbar_ns_preview'
                ,handler: function(){
                                                            var vthbl=Ext.getCmp('cmb_yearns').getValue() + Ext.getCmp('cmb_monthns').getValue();
                                                            var kdcabang=Ext.getCmp('ns_nama_cabang').getValue();
//                                                            console.log(vthbl +','+kdcabang);
                                                            if(!vthbl){
                                                                return;
                                                            }
//                                                            if(!kdcabang){
//                                                                return;
//                                                            }
//                    dtns.reload();
                                                            strns.reload({
                                                                params:{
                                                                    thbl:vthbl,
                                                                    kd_cabang:kdcabang}
                                                            })
                }
            },{
                text:'Cetak',
                //iconCls:'glapp-preview',
                id:'bbar_cetakns'
                ,handler: function(){
                    var vthbl=Ext.getCmp('cmb_yearns').getValue() + Ext.getCmp('cmb_monthns').getValue();
                    var kdcabang=Ext.getCmp('ns_nama_cabang').getValue();
                    var nmcabang=Ext.getCmp('ns_nama_cabang').getRawValue();
                    nmcabang=nmcabang.replace(' ','');
                    winnsprint.show();
                    console.log(Ext.getDom('nsprintprev'));
                    Ext.getDom('nsprintprev').src ='<?= site_url("account_neracalajur/print_form") ?>'+'/'+vthbl+'/'+'/'+kdcabang+'/'+nmcabang;
                }
            },{
            text: 'Export Excel',
            icon: BASE_ICONS + 'application_go.png',
            onClick: function(){
                var xd = toCSV(Ext.getCmp('grid_ns'));
                document.location = 'data:application/vnd.ms-excel;base64,' + Base64.encode(xd);
            }
        }
        ]
    }
    
    var strns= createStoreData(['jenis','groupname','kd_akun','nama',
        'saldoawald','saldoawalk',
        'mutasid','mutasik',
        'saldoakhird','saldoakhirk',
        'labarugid','labarugik',
        'neracad','neracak','cls'
    ], '<?= site_url("account_neracalajur/get_rows") ?>');
    
    
    
    
    var cityGroupRow = [];
    
    cityGroupRow = [{ header:"Rekening",align:"center", colspan:4},
        //    { header:"Rekening1",align:"center", colspan:2},
        { header:"Saldo Awal", align:"center", colspan:2},
        { header:"Mutasi", align:"center", colspan:2},
        { header:"Saldo Akhir", align:"center", colspan:2},
        { header:"Rugi Laba", align:"center", colspan:2},
        { header:"neraca", align:"center", colspan:2}];
    
  
   
    var group = new Ext.ux.grid.ColumnHeaderGroup({
        rows: [ cityGroupRow]
    });
   
   
   
    var gridns = new Ext.grid.GridPanel({
        region:'center',
        id:'grid_ns',        
//        title: 'Sales By Location',
        //        width: 1000,
        stripeRows: true,
        height: 400,
        frame: true,
        border:true,
        loadMask:true,
        columnLines : true,
        store: strns,
        plugins: group,
        columns: [{dataIndex: 'jenis',
            header: 'Jenis',
            hidden:true,
            id:'id_jenis_ns',            
            sortable: false ,     
            width: 125
            ,renderer:function(value, metaData, record, rowIndex, colIndex, store) {
                
                if(record.get('cls')== 'x-bls-header' || record.get('cls')== 'x-bls-header3'){
                    
                    //                                                metaData.attr = 'style="background-color: red;"';
//                                                                    metaData.css='x-bls-header';
                    metaData.css=record.get('cls');
                }
                                            
                return value;
            }	
        },
        {dataIndex: 'groupname',
            header: 'Group Name',
            id:'id_groupname_ns',            
            sortable: false ,     
            width: 125
            ,renderer:function(value, metaData, record, rowIndex, colIndex, store) {
                if(record.get('cls')== 'x-bls-header' || record.get('cls')== 'x-bls-header1' || record.get('cls')== 'x-bls-header3'){
                    //                                                metaData.attr = 'style="background-color: red;"';
                    metaData.css=record.get('cls');
                }
                                            
                return value;
            }	
        },
        {dataIndex: 'kd_akun',
            header: 'Kode Akun',
            id:'id_kd_akun_ns',            
            sortable: false ,   
            align:'center',
            width: 80
            ,renderer:function(value, metaData, record, rowIndex, colIndex, store) {
                if(record.get('cls')== 'x-bls-header' || record.get('cls')== 'x-bls-header1'  || record.get('cls')== 'x-bls-header2' || record.get('cls')== 'x-bls-header3'){
                    //                                                metaData.attr = 'style="background-color: red;"';
                    metaData.css=record.get('cls');
                }
                                            
                return value;
            }
        },
        {dataIndex: 'nama',
            header: 'Nama Akun',
            id:'id_nama_akun_ns',            
            sortable: false ,
            width: 300
            ,renderer:function(value, metaData, record, rowIndex, colIndex, store) {
                if(record.get('cls')== 'x-bls-header'  || record.get('cls')== 'x-bls-header1'  || record.get('cls')== 'x-bls-header2' || record.get('cls')== 'x-bls-header3'){
                    //                                               metaData.attr = 'style="background-color: red;"';
                    metaData.css=record.get('cls');
                }
                                            
                return value;
            }	
        },
        {dataIndex: 'saldoawald',
            header: 'Debet',
            id:'id_saldoawald_ns',            
            sortable: false ,
            width: 100, align: 'right',
            renderer:function(value, metaData, record, rowIndex, colIndex, store) {
                var newvalue=value;
                if(record.get('cls')== 'x-bls-header'  || record.get('cls')== 'x-bls-header1'  || record.get('cls')== 'x-bls-header2'  || record.get('cls')== 'x-bls-header3'){
                    //                                                metaData.attr = 'style="background-color: red;"';
                    metaData.css=record.get('cls');
                
                }
                newvalue=Ext.util.Format.number(value, '0,000');
                return newvalue;
            }
        },
        {dataIndex: 'saldoawalk',
            header: 'Kredit', 
            id:'id_saldoawalk_ns',            
            sortable: false ,
            align: 'right',
            width: 100,
            renderer:function(value, metaData, record, rowIndex, colIndex, store) {
                var newvalue=value;
                if(record.get('cls')== 'x-bls-header'  || record.get('cls')== 'x-bls-header1'  || record.get('cls')== 'x-bls-header2'  || record.get('cls')== 'x-bls-header3'){
                    //                                                metaData.attr = 'style="background-color: red;"';
                    metaData.css=record.get('cls');
                
                }
                newvalue=Ext.util.Format.number(value, '0,000');
                return newvalue;}
        },
        {dataIndex: 'mutasid',
            header: 'Debet',
            id:'id_mutasid_ns',            
            sortable: false ,
            width: 100, align: 'right',
            renderer:function(value, metaData, record, rowIndex, colIndex, store) {
                var newvalue=value;
                if(record.get('cls')== 'x-bls-header'  || record.get('cls')== 'x-bls-header1'  || record.get('cls')== 'x-bls-header2'  || record.get('cls')== 'x-bls-header3'){
                    //                                                metaData.attr = 'style="background-color: red;"';
                    metaData.css=record.get('cls');
                
                }
                newvalue=Ext.util.Format.number(value, '0,000');
                return newvalue;
            }
        },
        {dataIndex: 'mutasik',
            header: 'Kredit',
            id:'id_mutasik_ns',            
            sortable: false ,
            width: 100, align: 'right',
            renderer:function(value, metaData, record, rowIndex, colIndex, store) {
                var newvalue=value;
                if(record.get('cls')== 'x-bls-header'  || record.get('cls')== 'x-bls-header1'  || record.get('cls')== 'x-bls-header2'  || record.get('cls')== 'x-bls-header3'){
                    //                                                metaData.attr = 'style="background-color: red;"';
                    metaData.css=record.get('cls');
                
                }
                newvalue=Ext.util.Format.number(value, '0,000');
                return newvalue;}
        },
        {dataIndex: 'saldoakhird',
            header: 'Debet',
            id:'id_saldoakhird_ns',            
            sortable: false ,
            width: 100, align: 'right',
            renderer:function(value, metaData, record, rowIndex, colIndex, store) {
                var newvalue=value;
                if(record.get('cls')== 'x-bls-header'  || record.get('cls')== 'x-bls-header1'  || record.get('cls')== 'x-bls-header2'  || record.get('cls')== 'x-bls-header3'){
                    //                                                metaData.attr = 'style="background-color: red;"';
                    metaData.css=record.get('cls');
                
                }
                newvalue=Ext.util.Format.number(value, '0,000');
                return newvalue;}
        },
        {dataIndex: 'saldoakhirk',
            header: 'Kredit',
            id:'id_saldoakhirk_ns',            
            sortable: false ,
            width: 100, align: 'right',
            renderer:function(value, metaData, record, rowIndex, colIndex, store) {
                var newvalue=value;
                if(record.get('cls')== 'x-bls-header'  || record.get('cls')== 'x-bls-header1'  || record.get('cls')== 'x-bls-header2'  || record.get('cls')== 'x-bls-header3'){
                    //                                                metaData.attr = 'style="background-color: red;"';
                    metaData.css=record.get('cls');
                
                }
                newvalue=Ext.util.Format.number(value, '0,000');
                return newvalue;}
        },
        {dataIndex: 'labarugid',
            header: 'Debet', 
            id:'id_labarugid_ns',            
            sortable: false ,
            align: 'right',
            width: 100,
            renderer:function(value, metaData, record, rowIndex, colIndex, store) {
                var newvalue=value;
                if(record.get('cls')== 'x-bls-header'  || record.get('cls')== 'x-bls-header1'  || record.get('cls')== 'x-bls-header2'  || record.get('cls')== 'x-bls-header3'){
                    //                                                metaData.attr = 'style="background-color: red;"';
                    metaData.css=record.get('cls');
                
                }
                newvalue=Ext.util.Format.number(value, '0,000');
                return newvalue;}
        },
        {dataIndex: 'labarugik',
            header: 'Kredit',
            id:'id_labarugik_ns',            
            sortable: false ,
            width: 100, align: 'right',
            renderer:function(value, metaData, record, rowIndex, colIndex, store) {
                var newvalue=value;
                if(record.get('cls')== 'x-bls-header'  || record.get('cls')== 'x-bls-header1'  || record.get('cls')== 'x-bls-header2'  || record.get('cls')== 'x-bls-header3'){
                    //                                                metaData.attr = 'style="background-color: red;"';
                    metaData.css=record.get('cls');
                
                }
                newvalue=Ext.util.Format.number(value, '0,000');
                return newvalue;}
        },
        {dataIndex: 'neracad',
            header: 'Debet', 
            id:'id_neracad_ns',            
            sortable: false ,
            align: 'right',
            width: 100,
            renderer:function(value, metaData, record, rowIndex, colIndex, store) {
                var newvalue=value;
                if(record.get('cls')== 'x-bls-header'  || record.get('cls')== 'x-bls-header1'  || record.get('cls')== 'x-bls-header2'  || record.get('cls')== 'x-bls-header3'){
                    //                                                metaData.attr = 'style="background-color: red;"';
                    metaData.css=record.get('cls');
                
                }
                newvalue=Ext.util.Format.number(value, '0,000');
                return newvalue;}
        },
        {dataIndex: 'neracak', align: 'right',
            header: 'Kredit',
            id:'id_neracak_ns',            
            sortable: false ,
            width: 100,
            renderer:function(value, metaData, record, rowIndex, colIndex, store) {
                var newvalue=value;
                if(record.get('cls')== 'x-bls-header'  || record.get('cls')== 'x-bls-header1'  || record.get('cls')== 'x-bls-header2'  || record.get('cls')== 'x-bls-header3'){
                    //                                                metaData.attr = 'style="background-color: red;"';
                    metaData.css=record.get('cls');
                
                }
                newvalue=Ext.util.Format.number(value, '0,000');
                return newvalue;
            }
        }]
        //        viewConfig: {
        //            forceFit: true
        //        },
//        plugins: group
    });
   
    var ns_form = new Ext.FormPanel({
        id: 'neracasaldo',
        border: false,
        frame: true,
        autoScroll:true, 
        monitorValid: true,       
        bodyStyle:'padding-right:20px;',
        labelWidth: 130,
        layout:'border',
        items: [headerns,gridns]
        ,listeners:{
            afterrender:function(){
                strnscabang.reload();
            },
            show:function(){
                strnscabang.reload();
            }
        }
    });
</script>