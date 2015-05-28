<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">	
    var monthrl=[
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
    ];
    
    var dsmonthrl = new Ext.data.ArrayStore({
        fields: [
            {name: 'mid'},
            {name: 'mtext'}
        ],
        data:monthrl
    });
    var cmbmonthrl = new Ext.form.ComboBox({
        fieldLabel: 'Bulan Transaksi',
        id: 'cmb_monthrl',
        name:'bulan',
        allowBlank:false,
        store: monthrl
        ,valueField:'mid'
        ,displayField:'mtext'
        ,mode:'local',
        forceSelection: true,
        triggerAction: 'all',anchor: '90%'
            
    });
    
    var arr_yearrl = new Array();
    var arrgetrl = new Array();
    var dtrl = new Date();
    var now_yearrl = dtrl.getFullYear();
    var yearminrl = now_yearrl-5;
    var ytoarrrl = yearminrl;
    arrgetrl=[];
    arr_yearrl=[];
    for (var i = 0;i<=5;i++)
    {
        ytoarrrl=yearminrl+i;
        arrgetrl.push(ytoarrrl);
        arr_yearrl.push(arrgetrl);
        arrgetrl=[];
    }
    
    var cmbyearrl = new Ext.form.ComboBox({
    
        fieldLabel: 'Tahun Transaksi',
        id: 'cmb_yearrl',
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
                this.store.loadData(arr_yearrl);
    
            }
        }
    
    });
    var strrlcabang=new Ext.data.Store({
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
    
    var cmb_rl_cabang= new Ext.form.ComboBox({
        fieldLabel:		'Cabang',
        mode:           'local',
        value:          '',
        triggerAction:  'all',
        forceSelection: true,
        editable:       true,
        name:           'nama_cabang',
        id:           	'rl_nama_cabang',
        hiddenName:     'kd_cabang',
        displayField:   'nama_cabang',
        valueField:     'kd_cabang',
        anchor:			'90%',
        emptyText:'All',
        store:  strrlcabang
        //        allowBlank:false
        //        ,
        //        listeners:{
        //            select: function(combo, records) {
        //                var vcabang = this.getValue();                
        //                strapprovalvoucher.reload({params:{kd_cabang:vcabang}});
        //            }
        //        }
    });
    var winrlprint=createWinCetak('id_rlcetak','Print Rugi Laba','rlprintprev');
    
   var yn_viewakun_rl=true;
    
    var headerrugilaba=
        {region:'north',
        layout: 'column',
        border: false,
        items: [{
                columnWidth: .3,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [cmbyearrl
                    
                ]
           
            },{
                columnWidth: .3,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [cmbmonthrl
                    
                ]
           
            },{
                columnWidth: .3,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [cmb_rl_cabang
                    
                ]
           
            }],
        bbar:[
            {
                text:'Preview',
                //iconCls:'glapp-preview',
                id:'bbar_bulanberjalan'
                ,handler: function(){
                    var vthbl=Ext.getCmp('cmb_yearrl').getValue() + Ext.getCmp('cmb_monthrl').getValue();
                    var kdcabang=Ext.getCmp('rl_nama_cabang').getValue();
                    dset.reload({
                        params:{
                            thbl:vthbl,
                            kd_cabang:kdcabang}
                    })
                }
            },{
                text:'Cetak',
                //iconCls:'glapp-preview',
                id:'bbar_cetakrl'
                ,handler: function(){
                    var vthbl=Ext.getCmp('cmb_yearrl').getValue() + Ext.getCmp('cmb_monthrl').getValue();
                    var kdcabang=Ext.getCmp('rl_nama_cabang').getValue();
                    var nmcabang=Ext.getCmp('rl_nama_cabang').getRawValue();
                    nmcabang=nmcabang.replace(' ','');
                    yn_viewakun_rl=false;
//                    var src_url='<?= site_url("account_rugilaba/print_form") ?>'+'/'+vthbl+'/'+'/'+kdcabang+'/'+nmcabang+'/1';
                    Ext.Msg.show({
                        title: 'Confirm',
                        msg: 'Tampilkan Kode Akun?',
                        buttons: Ext.Msg.YESNO,
                        fn: function(btn){
                            if (btn == 'yes') {
                                winrlprint.show();
                                Ext.getDom('rlprintprev').src ='<?= site_url("account_rugilaba/print_form") ?>'+'/'+vthbl+'/'+kdcabang+'/'+nmcabang;
//                                yn_viewakun_rl=true;
                            }else if(btn == 'no'){
                                winrlprint.show();
                                Ext.getDom('rlprintprev').src ='<?= site_url("account_rugilaba/print_form2") ?>'+'/'+vthbl+'/'+kdcabang+'/'+nmcabang;
                            }
                        }
                    });     
                    
//                    winrlprint.show();
//                    if (yn_viewakun_rl){
//                        Ext.getDom('rlprintprev').src ='<?= site_url("account_rugilaba/print_form") ?>'+'/'+vthbl+'/'+'/'+kdcabang+'/'+nmcabang+'/1';
//                    }else{
//                        Ext.getDom('rlprintprev').src ='<?= site_url("account_rugilaba/print_form") ?>'+'/'+vthbl+'/'+'/'+kdcabang+'/'+nmcabang+'/0';
//                    }
                    
                }
            },{
                text:'Cetak Tanpa Nilai 0',
                //iconCls:'glapp-preview',
                id:'bbar_cetakrl_nol'
                ,handler: function(){
                    var vthbl=Ext.getCmp('cmb_yearrl').getValue() + Ext.getCmp('cmb_monthrl').getValue();
                    var kdcabang=Ext.getCmp('rl_nama_cabang').getValue();
                    var nmcabang=Ext.getCmp('rl_nama_cabang').getRawValue();
                    nmcabang=nmcabang.replace(' ','');
                    yn_viewakun_rl=false;
//                    var src_url='<?= site_url("account_rugilaba/print_form") ?>'+'/'+vthbl+'/'+'/'+kdcabang+'/'+nmcabang+'/1';
                    Ext.Msg.show({
                        title: 'Confirm',
                        msg: 'Tampilkan Kode Akun?',
                        buttons: Ext.Msg.YESNO,
                        fn: function(btn){
                            if (btn == 'yes') {
                                winrlprint.show();
                                Ext.getDom('rlprintprev').src ='<?= site_url("account_rugilaba/print_form3") ?>'+'/'+vthbl+'/'+kdcabang+'/'+nmcabang;
//                                yn_viewakun_rl=true;
                            }else if(btn == 'no'){
                                winrlprint.show();
                                Ext.getDom('rlprintprev').src ='<?= site_url("account_rugilaba/print_form4") ?>'+'/'+vthbl+'/'+kdcabang+'/'+nmcabang;
                            }
                        }
                    });     
                    
//                    winrlprint.show();
//                    if (yn_viewakun_rl){
//                        Ext.getDom('rlprintprev').src ='<?= site_url("account_rugilaba/print_form") ?>'+'/'+vthbl+'/'+'/'+kdcabang+'/'+nmcabang+'/1';
//                    }else{
//                        Ext.getDom('rlprintprev').src ='<?= site_url("account_rugilaba/print_form") ?>'+'/'+vthbl+'/'+'/'+kdcabang+'/'+nmcabang+'/0';
//                    }
                    
                }
            }
        ]
    }
    var dset=new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [ 
                'groupakun','groupname','jenis',
                'kd_akun',
                'parent_kd_akun',
                'nama',            
            {name: 'isheader', type: 'boolean'},
            {name: 'jumlah2'},
            {name: 'jumlah'},
            //{name: 'jumlah', type: 'float'},
            {name: 'saldo'}
                ,'cls'
            ],
            root: 'data',
            totalProperty: 'record'
            
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("account_rugilaba/get_rows") ?>',
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
    
    // define a custom summary function
//    Ext.ux.grid.GroupSummary.Calculations['totalCost'] = function(v, record, field){
//        return v + (record.data.total);
//    };

    // utilize custom extension for Group Summary
//    var summary = new Ext.ux.grid.GroupSummary();
    
//    var mreader= new Ext.data.JsonReader({
//        fields: [ 
//            'groupakun',
//            'kd_akun',
//            'parent_kd_akun',
//            'nama',            
//            {name: 'isheader', type: 'boolean'},
//            {name: 'jumlah', type: 'float'},
//            {name: 'total', type: 'float'}
//                
//        ],
//        root: 'data',
//        totalProperty: 'record'
//            
//    });
//    var dset= new Ext.data.GroupingStore({
//        reader: mreader,
//        // use remote data
//        proxy : new Ext.data.HttpProxy({
//            url: '<?= site_url("account_rugilaba/get_rows") ?>',
//            method: 'POST'
//        }),
//        sortInfo: {field: 'groupakun', direction: 'DESC'},
//        groupField: 'groupakun'
//    });
    
    function set_total_dset(){		
        var totaldebet=0;
        var totalkredit=0;
        var totalselisih=0;
                
        dset.each(function(node){		
//            console.log(node.data.nama + ' ' + node.data.isheader);
            if(!node.data.isheader) {
                if(node.data.groupakun.toString().substr(0, 1)=='4'){
                    totaldebet += parseInt(node.data.jumlah);
                }else if(node.data.groupakun.toString().substr(0, 1)=='5'){
                    totalkredit += parseInt(node.data.jumlah);
                }
                    
                  
            }         
            
        });
        totalselisih=totaldebet-totalkredit;
        Ext.getCmp('t_rugilaba').setValue(totalselisih);
        if(totalselisih<0){
            Ext.getCmp('t_rugilabatext').setValue("Rugi");
        }else if(totalselisih>0){
             Ext.getCmp('t_rugilabatext').setValue("Laba");
        }
        
//        Ext.getCmp('evr_t_kredit').setValue(totalkredit);
//        Ext.getCmp('evr_t_selisih').setValue(totalselisih);
                
    };
//    dset.on('load', function(){
//        set_total_dset();
//    });
//    dset.on('update', function(){
//        set_total_dset();
//    });
//    dset.on('remove',  function(){
//        set_total_dset();
//		
//    });
    
    var gridrugilaba=new Ext.grid.GridPanel({    
        region:'center',
        id:'grid_rl',
        store: dset,
        
        stripeRows: true,
        height: 400,
        frame: true,
        border:true,
        loadMask:true,
//        plugins: summary,
        //                plugins: [editorentryjp],        
        columns: [{                //            xtype: 'numbercolumn',
                hidden:true,
                header: 'groupakun',
                dataIndex: 'groupakun',
                id:'id_groupakun',
                width: 80,                
                sortable: false ,               
                renderer:function(value, metaData, record, rowIndex, colIndex, store) {
                        
                                            if(record.get('cls')== 'x-bls-header' || record.get('cls')== 'x-bls-header6' || record.get('cls')== 'x-bls-header3'){                                               
//                                                metaData.attr = 'style="background-color: red;"';
                                                metaData.css=record.get('cls');
                                            }
                                            
                                            return value;
                                        }
			
            },{                //            xtype: 'numbercolumn',
                header: 'jenis',
                dataIndex: 'jenis',
                id:'id_jenis',
                width: 125,                
                sortable: false      
            	,tdCls:'x-jenis-cell' 
		,renderer:function(value, metaData, record, rowIndex, colIndex, store) {
                                            if(record.get('cls')== 'x-bls-header' || record.get('cls')== 'x-bls-header6' || record.get('cls')== 'x-bls-header3'){
//                                                metaData.attr = 'style="background-color: red;"';
//                                                metaData.css='x-bls-header';
                                                metaData.css=record.get('cls');
                                            }
                                            
                                            return value;
                                        }	
            },{                //            xtype: 'numbercolumn',
                header: 'groupname',
                dataIndex: 'groupname',
                id:'id_groupname',
                width: 125,                
                sortable: false                    	
		,renderer:function(value, metaData, record, rowIndex, colIndex, store) {
                                            if(record.get('cls')== 'x-bls-header' || record.get('cls')== 'x-bls-header6' || record.get('cls')== 'x-bls-header1' || record.get('cls')== 'x-bls-header3' || record.get('cls')== 'x-bls-header4'){
//                                                metaData.attr = 'style="background-color: red;"';
                                                metaData.css=record.get('cls');
                                            }
                                            
                                            return value;
                                        }	
            },{                //            xtype: 'numbercolumn',
                header: 'Kode Akun',
                dataIndex: 'kd_akun',
                width: 80,                
                sortable: false 
                ,renderer:function(value, metaData, record, rowIndex, colIndex, store) {
                                            if(record.get('cls')== 'x-bls-header' || record.get('cls')== 'x-bls-header6' || record.get('cls')== 'x-bls-header1'  || record.get('cls')== 'x-bls-header2' || record.get('cls')== 'x-bls-header3' || record.get('cls')== 'x-bls-header4' || record.get('cls')== 'x-bls-header5'){
//                                                metaData.attr = 'style="background-color: red;"';
                                                metaData.css=record.get('cls');
                                            }
                                            
                                            return value;
                                        }
                //                ,hidden:true             	
			
            },{                //            xtype: 'numbercolumn',
                header: 'Nama Akun',
                dataIndex: 'nama',
                width: 300,                
                sortable: false  
               	,renderer:function(value, metaData, record, rowIndex, colIndex, store) {
                                            if(record.get('cls')== 'x-bls-header'  || record.get('cls')== 'x-bls-header6' || record.get('cls')== 'x-bls-header1'  || record.get('cls')== 'x-bls-header2' || record.get('cls')== 'x-bls-header3' || record.get('cls')== 'x-bls-header4' || record.get('cls')== 'x-bls-header5'){
//                                               metaData.attr = 'style="background-color: red;"';
                                                metaData.css=record.get('cls');
                                            }
                                            
                                            return value;
                                        }	
            },{           
                //            xtype: 'numbercolumn',
//                xtype: 'numbercolumn',
                header: 'Bulan Lalu',
                dataIndex: 'jumlah2',                
                width: 100,    
                format:'0,0',
                sortable: false  , align: 'right'                  
		,renderer:function(value, metaData, record, rowIndex, colIndex, store) {
                    var newvalue=value; var nm=record.get('jenis');var valjenis=1;
                                            if(record.get('cls')== 'x-bls-header'  || record.get('cls')== 'x-bls-header6' || record.get('cls')== 'x-bls-header1'  || record.get('cls')== 'x-bls-header2' || record.get('cls')== 'x-bls-header3' || record.get('cls')== 'x-bls-header4' || record.get('cls')== 'x-bls-header5'){
//                                                metaData.attr = 'style="background-color: red;"';
                                                metaData.css=record.get('cls');
                                            }
                                            if(!nm){
                                                nm='aaaaa';
                                            }
                                            if(nm.substring( 0, 5)=='TOTAL' || nm==' RUGI LABA '){
                                                valjenis=0;
                                            }
                                            if( valjenis==1 && !(record.get('nama')) &&  !(record.get('kd_akun')) && !(record.get('groupname'))){
                                                newvalue= value;
                                            }else{
                                                newvalue= Ext.util.Format.number(value, '0,000');
                                            }
                                            
                                            return newvalue;
                                        }	
            },{        
                
//                xtype: 'numbercolumn',
                header: 'Bulan Ini',
                dataIndex: 'jumlah',                
                width: 100,    
                format:'0,0',
                sortable: false , align: 'right'  
                ,renderer:function(value, metaData, record, rowIndex, colIndex, store) {
                                            var newvalue=value;var nm=record.get('jenis');var valjenis=1;
                                            if(record.get('cls')== 'x-bls-header'  || record.get('cls')== 'x-bls-header6' || record.get('cls')== 'x-bls-header1'  || record.get('cls')== 'x-bls-header2'  || record.get('cls')== 'x-bls-header3' || record.get('cls')== 'x-bls-header4' || record.get('cls')== 'x-bls-header5'){
//                                                metaData.attr = 'style="background-color: red;"';
                                                metaData.css=record.get('cls');
                                            }
                                            if(!nm){
                                                nm='aaaaa';
                                            }
                                            if(nm.substring( 0, 5)=='TOTAL' || nm==' RUGI LABA '){
                                                valjenis=0;
                                            }
                                            if( valjenis==1 && !(record.get('nama')) &&  !(record.get('kd_akun')) && !(record.get('groupname'))){
                                                newvalue= value;
                                            }else{
                                                newvalue= Ext.util.Format.number(value, '0,000');
                                            }
                                            
                                            return newvalue;
                                        }
			
            },{        
                
//                xtype: 'numbercolumn',
                header: 'S/D Bulan Ini',
                dataIndex: 'saldo',                
                width: 100,    
                format:'0,0',
                sortable: false , align: 'right'  
                ,renderer:function(value, metaData, record, rowIndex, colIndex, store) {
                                            var newvalue=value;var nm=record.get('jenis');var valjenis=1;
                                            if(record.get('cls')== 'x-bls-header'  || record.get('cls')== 'x-bls-header6' || record.get('cls')== 'x-bls-header1'  || record.get('cls')== 'x-bls-header2'  || record.get('cls')== 'x-bls-header3' || record.get('cls')== 'x-bls-header4' || record.get('cls')== 'x-bls-header5'){
//                                                metaData.attr = 'style="background-color: red;"';
                                                metaData.css=record.get('cls');
                                            }
                                            if(!nm){
                                                nm='aaaaa';
                                            }
                                            if(nm.substring( 0, 5)=='TOTAL' || nm==' RUGI LABA '){
                                                valjenis=0;
                                            }
                                            if( valjenis==1 && !(record.get('nama')) &&  !(record.get('kd_akun')) && !(record.get('groupname'))){
                                                newvalue= value;
                                            }else{
                                                newvalue= Ext.util.Format.number(value, '0,000');
                                            }
                                            
                                            return newvalue;
                                        }
			
            }]
           
//        ,
//        viewConfig: {
//            getRowClass: function(record, rowIndex, rowParams, ds){                
//                //            if(rowIndex==5){
//                //                rowParams.tstyle += "background-color:" +  + ';';
//                //            }
//            if(record.get('nama')){
//                if(record.get('nama').toString().substr(0, 5).toLowerCase()=='total')
//                {//                    console.log(record.get('nama').toString());
//                    return 'grid-row-highlight';
//                }
//            }else{
//                if(record.get('groupname'))
//                {//                    console.log(record.get('nama').toString());
//                    return 'grid-row-highlight';
//                }
//            }
//                
//            }
//        },
//        bbar:['->',
////            'Rugi Laba : ',
//            {xtype: 'textfield',id: 't_rugilabatext',fieldClass:'readonly-input',readOnly:true },
//            {xtype: 'numericfield',currencySymbol: '',id: 't_rugilaba',fieldClass:'number',readOnly:true }]
    });
    
    var rugilaba_form = new Ext.FormPanel({
        id: 'rugilaba',
        border: false,
        frame: true,
        autoScroll:true, 
        monitorValid: true,       
        bodyStyle:'padding-right:20px;',
        labelWidth: 130,
        layout:'border',
        items: [headerrugilaba,gridrugilaba]
        ,listeners:{
            afterrender:function(){
                strrlcabang.reload();
            },
            show:function(){
                strrlcabang.reload();
            }
        }
    });
</script>