<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">	
   
    var dtpbukubesar= new Ext.form.DateField(
    {
        fieldLabel: 'Tanggal Awal',
        id: 'dtpbukubesar'
        ,allowBlank:false,anchor: '70%'
        ,vtype: 'daterange',
        endDateField: 'dtpbukubesarakhir',
        format:'Y-m-d'
    });
    
    var dtpbukubesarakhir= new Ext.form.DateField(
    {
        fieldLabel: 'Tanggal Akhir',
        id: 'dtpbukubesarakhir'
        ,allowBlank:false,anchor: '70%'
        ,vtype: 'daterange',
        startDateField: 'dtpbukubesar',
        format:'Y-m-d'
    });
   
    var dslapbukubesar = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_akun',
                'nama'          
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("account_monjurnal/get_akun") ?>',
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
    
    //=================================================
    
    var strakunbukubesar=createStoreData(['kd_akun', 'nama','dk'], '<?= site_url("account_master_account/get_akun_twin") ?>');
    var search_akun_bukubesar=createSearchField('id_search_akun_bukubesar', strakunbukubesar, 350);
    var strcmbakunbukubesar=createStoreData(['kd_akun', 'nama'], '<?= site_url("account_monjurnal/get_akun") ?>');//createStoreArray(['kd_akun','nama'],[]);
    var grid_akun_bukubesar = new Ext.grid.GridPanel({
        
        id:'id_searchgrid_akun_bb',
        store: strakunbukubesar,
        stripeRows: true,
        frame: true,
        border:true,
//        height:200,
//        width: 380,
        columns: [{
                header: 'Kode Akun',
                dataIndex: 'kd_akun',
                width: 80,
                sortable: true			
            
            },{
                header: 'Nama Akun',
                dataIndex: 'nama',
                width: 300,
                sortable: true         
            },{
                header: 'D/K',
                dataIndex: 'dk',
                width: 50,
                sortable: true,
                hidden:true
            }],
        tbar: new Ext.Toolbar({
            items: [search_akun_bukubesar]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strakunbukubesar,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {		
                    var strselect=Ext.getCmp('grid_pilihakun_bb').getStore();
                    var Plant = strselect.recordType;
                    var rowentryvoucher = null;
                    
                    for(var i =0;i<sel.length;i++){
                //        console.log(sel[i].get('kd_akun'));
                        if(cek_akun_select(sel[i].get('kd_akun'))){
                            rowentryvoucher=new Plant({kd_akun:sel[i].get('kd_akun'),nama:sel[i].get('nama')});
                            strselect.add(rowentryvoucher);
                        }

                    }
//                    strcmbakunmonjur.reload();                    
//                    var kdakun=sel[0].get('kd_akun');
//                    Ext.getCmp('mca_akunbukubesar').setValue(kdakun);
                    menu_akun_bukubesar.hide();
                }
            }
        }
    });
    var menu_akun_bukubesar = new Ext.menu.Menu();
    setPanelMenu2(menu_akun_bukubesar, 'Pilih Akun / CTRL + CLICK Jika Lebih Dari Satu', 400, 300, grid_akun_bukubesar,'Pilih Akun', 
    function(){
        var sm = Ext.getCmp('id_searchgrid_akun_bb').getSelectionModel();
        var sel = sm.getSelections();
        var strselect=Ext.getCmp('grid_pilihakun_bb').getStore();
        var Plant = strselect.recordType;
        var rowentryvoucher = null;
        for(var i =0;i<sel.length;i++){
    //        console.log(sel[i].get('kd_akun'));
            if(cek_akun_select(sel[i].get('kd_akun'))){
                rowentryvoucher=new Plant({kd_akun:sel[i].get('kd_akun'),nama:sel[i].get('nama')});
                strselect.add(rowentryvoucher);
            }
            
        }
        menu_akun_bukubesar.hide();    
    }
    ,
    function(){
        menu_akun_bukubesar.hide();
    }, function(){
        var sf = Ext.getCmp('id_search_akun_bukubesar').getValue();
        if( sf != ''){
            Ext.getCmp('id_search_akun_bukubesar').setValue('');
            Ext.getCmp('id_search_akun_bukubesar').onTrigger2Click();
        }
    });
    
    function cek_akun_select(kdakun){
        var retval=true;
        var kd='';
        for(var i =0;i<secondGridStore.getCount();i++){
            kd=secondGridStore.getAt(i).data.kd_akun;
            if(kd==kdakun){
                retval=false;
                break;
                
            }
        }
        return retval;
        
    }
    Ext.ux.TwinComboAkunbukubesar = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
//            strcmbakunbukubesar.reload();  
            strakunbukubesar.load();
            menu_akun_bukubesar.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    
    var cmb_akunbukubesar = new Ext.ux.TwinComboAkunbukubesar({
        fieldLabel: 'Nama Akun',
        id: 'mca_akunbukubesar',
        store: strcmbakunbukubesar,
        mode: 'local',
        valueField: 'kd_akun',
        displayField: 'nama',
        typeAhead: true,
        triggerAction: 'all',
        //        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_akun',
        name:'kd_akun',
        emptyText: 'Pilih Akun'
        //        listeners:{
        //            change:function(){
        //                
        //            }
        //            
        //        }
    });
    
    //=================================================
    
//    var cmbakunbukubesar = new Ext.form.ComboBox({
//        fieldLabel: 'Nama Akun',
//        id: 'cmbakunbukubesar',
//        allowBlank:false,
//        store: dslapbukubesar
//        ,valueField:'kd_akun'
//        ,displayField:'nama'
//        ,mode:'local'
//        ,forceSelection: true,anchor: '90%',
//        triggerAction: 'all'
//    });
    
    var strBBcabang=new Ext.data.Store({
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
    
    var cmb_BB_cabang= new Ext.form.ComboBox({
        fieldLabel:		'Cabang',
        mode:           'local',
        value:          '',
        triggerAction:  'all',
        forceSelection: true,
        editable:       true,
        name:           'nama_cabang',
        id:           	'BB_nama_cabang',
        hiddenName:     'kd_cabang',
        displayField:   'nama_cabang',
        valueField:     'kd_cabang',
        anchor:			'90%',
        emptyText:'All',
        store:  strBBcabang
//        allowBlank:false
//        ,
//        listeners:{
//            select: function(combo, records) {
//                var vcabang = this.getValue();                
//                strapprovalvoucher.reload({params:{kd_cabang:vcabang}});
//            }
//        }
    });
    var myData = {
		records : [
			{ kd_akun : "110.0002", nama : "a"},
                        { kd_akun : "710.0001", nama : "a"}
			
		]
	};
    var fieldsecond = [
		{name: 'kd_akun', mapping : 'kd_akun'},
		{name: 'nama', mapping : 'nama'}
	];
     var secondGridStore = new Ext.data.JsonStore({
        fields : fieldsecond,
//        data:myData,
		root   : 'records'
    });
    
    var secondGrid = new Ext.grid.GridPanel({
    id:'grid_pilihakun_bb',
        height:150,
//	ddGroup          : 'firstGridDDGroup',
//        anchor:'90%',
        store            : secondGridStore,
        columns          : [
		{header: "Kode Akun", width: 80, sortable: true, dataIndex: 'kd_akun'},
		{header: "Nama Akun", width: 300, sortable: true, dataIndex: 'nama'}
	],
//	enableDragDrop   : false,
        stripeRows       : true,
//        autoExpandColumn : 'nama',
        title            : 'Pilih Akun / Kosong = All',
        tbar:[{text:'Pilih Akun',
                //iconCls:'glapp-preview',
                id:'tb_pilihakun_bb'
                ,handler: function(){
                    strakunbukubesar.load();
                    menu_akun_bukubesar.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
                }
            } ,'-'
                        ,{
                            text:'Clear Akun',
                            //iconCls:'glapp-preview',
                            id:'tb_clearakun_bb'
                            ,handler:function(){
                                var strselect=Ext.getCmp('grid_pilihakun_bb').getStore();
                                if(strselect.getCount()>0){
                                    strselect.removeAll();
                                }
                            }}
                        ,'-'
                        ,{
                            text:'Delete Akun',
                            //iconCls:'glapp-preview',
                            id:'tb_delakun_bb'
                            ,handler:function(){
                                var sm = Ext.getCmp('grid_pilihakun_bb').getSelectionModel();
                                var sel = sm.getSelections();
                                var strselect=Ext.getCmp('grid_pilihakun_bb').getStore();
                                var Plant = strselect.recordType;
                                var rowentryvoucher = null;
                                console.log(sel);
                                if(sel.length>0){
                                    for(var i = 0, r; r = sel[i]; i++){
                                        strselect.remove(r);
                    }
                                }
                               
                            }}
            ]
    });

    var winbbprint=createWinCetak('id_bbcetak','Print Buku Besar','bbprintprev');
    var headerbukubesar={
        region:'north',
        layout: 'column',
        border: false,
        height: 180,
        items: [{
                columnWidth: .4,
                layout: 'form',
                border: false,
                labelWidth: 80,
                defaults: { labelSeparator: ''},
                items: [dtpbukubesar,dtpbukubesarakhir,cmb_BB_cabang,
//                    cmb_akunbukubesar
                    //cmbakunbukubesar
                    
                ]
           
            },{
                columnWidth: .4,
                layout: 'fit',
                border: false,
                labelWidth: 80,
                defaults: { labelSeparator: ''},
                items: [ secondGrid         
                    
                ]
           
            }],
        bbar:[
            {
                text:'Preview',
                //iconCls:'glapp-preview',
                id:'bbarpreviewbb'
                ,handler: function(){
                    var kdakun=null;
                    var tglawal=null;
                    var tglakhir=null;
                    var detailakun=new Array();
                    tglawal=Ext.getCmp('dtpbukubesar').getValue().format('Y-m-d');
                    tglakhir=Ext.getCmp('dtpbukubesarakhir').getValue().format('Y-m-d');
//                    kdakun=Ext.getCmp('mca_akunbukubesar').getValue();
                    var kdcabang=Ext.getCmp('BB_nama_cabang').getValue();
                    secondGridStore.each(function(node){
                        detailakun.push(node.data)
                    });
                    
                    strbukubesar.reload({params:{
                            tglawal:tglawal,
                            tglakhir:tglakhir,
                            akun:Ext.util.JSON.encode(detailakun),
                            kd_cabang:kdcabang                            
                        }});      
                    strbukubesar.groupBy('kd_akun', true);
                }
            }
            
//                        ,'-'
//                        ,{
//                            text:'Clear Akun',
//                            //iconCls:'glapp-preview',
//                            id:'bbarpreviewtjur'
//                            ,handler:function(){
//                                                    
//                            }
            //            },'-'
            //            ,{
            //                text:'preview akun',
            //                //iconCls:'glapp-preview',
            //                id:'bbarpreviewajur'
            //                ,handler:function(){
            //                                        
            //                }
            //            },'-'
                        ,{
                            text:'cetak',
                            // iconCls:'glapp-cetak',
                            id:'bbarcetakbb'
                            ,handler:function(){
                                var kdakun=null;
                                var tglawal=null;
                                var tglakhir=null;
                                var detailakun='';
                                tglawal=Ext.getCmp('dtpbukubesar').getValue().format('Y-m-d');
                                tglakhir=Ext.getCmp('dtpbukubesarakhir').getValue().format('Y-m-d');
            //                    kdakun=Ext.getCmp('mca_akunbukubesar').getValue();
                                var kdcabang=Ext.getCmp('BB_nama_cabang').getValue();
                                var nmcabang=Ext.getCmp('BB_nama_cabang').getRawValue();
                                nmcabang=nmcabang.replace(' ','');
                                var i=0;
                                secondGridStore.each(function(node){
                                    detailakun +=node.data.kd_akun;
                                    if(i<secondGridStore.getCount()-1){
                                        detailakun +='_';
                                    }
                                    i++;
                                });
                                winbbprint.show();
                                console.log('/'+tglawal+'/'+tglakhir+'/'+kdcabang+':'+nmcabang+':'+detailakun);
                                Ext.getDom('bbprintprev').src ='<?= site_url("account_bukubesar/print_form") ?>'+'/'+tglawal+'/'+tglakhir+'/'+kdcabang+':'+nmcabang+':'+detailakun;
                            }
                        }
        ]
    } 
    var strbukubesar=new Ext.data.GroupingStore({
        reader: new Ext.data.JsonReader({
            fields: [
               {name:'nomor'},
               {name: 'tgl_transaksi'},
               {name: 'idjurnal'},
               {name: 'novoucher'},
               {name: 'keterangan'},
               {name:'keterangan_detail'},
               {name:'costcenter'},
               {name:'cabang'},
               {name:'kd_akun'},
               {name:'nama'},
               {name:'dk_transaksi'},
                {name:'jumlahd'},
                 {name:'jumlahk'},
               {name:'jumlah'}
            ],
            root: 'data',
            groupField:'kd_akun'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("account_bukubesar/get_view") ?>',
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
    var gridbukubesar = new Ext.grid.GridPanel({
        region:'center',
        store: strbukubesar,
        
        //        plugins: [editorentryjp],        
        columns: [
//            {   
//                hidden:true,
//                header: 'Tanggal Transaksi',
//                dataIndex: 'tgl_transaksi',
//                width: 80,                
//                sortable: true             	
//			
//            },
            {                //            xtype: 'numbercolumn',
                header: 'Tanggal Transaksi',
                dataIndex: 'tgl_transaksi',
                width: 100,                
                sortable: false             	
			
            },{                //            xtype: 'numbercolumn',
                header: 'Id Jurnal',
                dataIndex: 'idjurnal',
                width: 150,                
                sortable: false             	
			
            },{                //            xtype: 'numbercolumn',
                header: 'No Voucher',
                dataIndex: 'novoucher',
                width: 150,                
                sortable: false             	
			
            },{                //            xtype: 'numbercolumn',
                header: 'Keterangan',
                dataIndex: 'keterangan',
                width: 150,                
                sortable: false             	
			
            },{                //            xtype: 'numbercolumn',
                header: 'Keterangan Detail',
                dataIndex: 'keterangan_detail',
                width: 150,                
                sortable: false             	
			
            },{                //            xtype: 'numbercolumn',
                header: 'Cost Center',
                dataIndex: 'costcenter',
                width: 150,                
                sortable: false             	
			
            },{                //            xtype: 'numbercolumn',
                header: 'Cabang',
                dataIndex: 'cabang',
                width: 100,                
                sortable: false             	
			
            },{                //            xtype: 'numbercolumn',
                header: 'Account',
                dataIndex: 'kd_akun',
                width: 80,                
                sortable: false
                ,hidden:true             	
			
            },{                //            xtype: 'numbercolumn',
                header: 'Nama Akun',
                dataIndex: 'nama',
                width: 100,                
                sortable: false
                ,hidden:true             	
			
            },{                //            xtype: 'numbercolumn',
                header: 'Debet/Kredit',
                dataIndex: 'dk_transaksi',
                width: 80,                
                sortable: false   
                ,hidden:true               	
			
            }
            ,{           
                //            xtype: 'numbercolumn',
                xtype: 'numbercolumn',
                header: 'Debet',
                dataIndex: 'jumlahd',                
                width: 100,    
                format:'0,0',
                sortable: false , align: 'right'            	
			
            }
            ,{           
                //            xtype: 'numbercolumn',
                xtype: 'numbercolumn',
                header: 'Kredit',
                dataIndex: 'jumlahk',                
                width: 100,    
                format:'0,0',
                sortable: false , align: 'right'            	
			
            }
            ,{           
                //            xtype: 'numbercolumn',
                xtype: 'numbercolumn',
                header: 'Saldo',
                dataIndex: 'jumlah',                
                width: 100,    
                format:'0,0',
                sortable: false , align: 'right'            	
			
            }
            
        ],
        view: new Ext.grid.GroupingView({             
            forceFit:true,
            groupTextTpl: '{text}  {[values.rs[0].data.nama]}'
            ,getRowClass: function(record, rowIndex, rp, ds){
                if(record.get('keterangan') == 'Saldo Awal' || record.get('keterangan') == 'Saldo Akhir')
                {
                    return 'grid-row-highlight';
                }
            }
        }),
        stripeRows: true,
        height: 300,
        loadMask:true,
        frame: true,
        border:true
    });
    
    var bukubesar_form = new Ext.FormPanel({
        id: 'bukubesar',
        border: false,
        frame: true,
        autoScroll:true, 
        monitorValid: true,       
        bodyStyle:'padding-right:20px;',
        labelWidth: 130,
        layout:'border',
        items: [headerbukubesar,gridbukubesar]
        ,listeners:{
            afterrender:function(){
                dslapbukubesar.reload();
                strBBcabang.reload();
                strcmbakunbukubesar.reload();
            }
            
        }
        
    });
        
</script>