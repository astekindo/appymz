<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">	
  
//    function setdtlock(dtp,bln,thn)
//    {
//        var jsdt = new Date(thn, bln-1,1);
//        var jedt = new Date((new Date(thn, bln,1))-1);
//        dtp.setMinValue(jsdt);
//        dtp.setMaxValue(jedt);
//        dtp.setValue(jsdt);
//
//    }
    var dtpjur= new Ext.form.DateField(
    {
        fieldLabel: 'Tanggal Awal',
        id: 'dtpjur'
        ,allowBlank:false
        ,anchor: '60%'
//        ,vtype: 'daterangemonjur',
//        endDateField: 'dtpjurakhir',
        ,format:'Y-m-d'
        ,listeners:{
            select:function(){
                if(Ext.getCmp('dtpjurakhir').getValue()){
                    if(this.getValue()>Ext.getCmp('dtpjurakhir').getValue()){
                        var dtn=Ext.getCmp('dtpjurakhir').getValue();
                        this.setValue(dtn);
                    }
                }
                
                
            }
        }
    });
    var dtpjurakhir= new Ext.form.DateField(
    {
        fieldLabel: 'Tanggal Akhir',
        id: 'dtpjurakhir'
        ,allowBlank:false
        ,anchor: '60%'
//        ,vtype: 'daterangemonjur',
//        startDateField: 'dtpjur',
        ,format:'Y-m-d'
        ,listeners:{
            select:function(){
                if(this.getValue()){
                    if(this.getValue()<Ext.getCmp('dtpjur').getValue()){
                        var dtn=this.getValue();
                        Ext.getCmp('dtpjur').setValue(dtn);
                        
                    }
                }
                
                
            }
        }
    });
   
    var dslapakun = new Ext.data.Store({
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
    
    var strakunmonjur=createStoreData(['kd_akun', 'nama','dk'], '<?= site_url("account_master_account/get_akun_twin") ?>');
    var search_akun_monjur=createSearchField('id_search_akun_monjur', strakunmonjur, 350);
    var strcmbakunmonjur=createStoreData(['kd_akun', 'nama'], '<?= site_url("account_master_account/get_akun_twin") ?>');//createStoreArray(['kd_akun','nama'],[]);
    var grid_akun_monjur = new Ext.grid.GridPanel({
        
        //id:'id_searchgrid_akun_transaksi',
        store: strakunmonjur,
        stripeRows: true,
        frame: true,
        border:true,
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
                sortable: true         
            }],
        tbar: new Ext.Toolbar({
            items: [search_akun_monjur]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strakunmonjur,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {		
//                    strcmbakunmonjur.reload();                    
                    var kdakun=sel[0].get('kd_akun');
                    Ext.getCmp('mca_akunmonjur').setValue(kdakun);
                    menu_akun_monjur.hide();
                }
            }
        }
    });
    var menu_akun_monjur = new Ext.menu.Menu();
    setPanelMenu(menu_akun_monjur, 'Pilih Akun', 400, 300, grid_akun_monjur, function(){
        menu_akun_monjur.hide();
    }, function(){
        var sf = Ext.getCmp('id_search_akun_monjur').getValue();
        if( sf != ''){
            Ext.getCmp('id_search_akun_monjur').setValue('');
            Ext.getCmp('id_search_akun_monjur').onTrigger2Click();
        }
    });
    
    Ext.ux.TwinComboAkunmonjur = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strcmbakunmonjur.reload();  
            strakunmonjur.load();
            menu_akun_monjur.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    
    var cmb_akunmonjur = new Ext.ux.TwinComboAkunmonjur({
        fieldLabel: 'Nama Akun',
        id: 'mca_akunmonjur',
        store: strcmbakunmonjur,
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
//    var cmbakunjur = new Ext.form.ComboBox({
//        fieldLabel: 'Nama Akun',
//        id: 'cmbakunjur',
////        allowBlank:false,
//        store: dslapakun
//        ,valueField:'kd_akun'
//        ,displayField:'nama'
//        ,name:'kd_akun'
//        ,hiddenName:'kd_akun'
//        ,mode:'local'
//        ,forceSelection: true
//        ,anchor: '90%'
//        ,triggerAction: 'all'
//    });
    
    var select_akun={
        xtype:'fieldset',
        id:'id_select_akun',
        checkboxToggle:true,
        anchor: '90%',
        title: 'Pilih Akun',
        autoHeight:true,
        collapsed: true,
        layout: 'form',
        items :[cmb_akunmonjur
//            cmbakunjur
            //            {columnWidth: .4,
            //                layout: 'form',
            //                border: false,
            //                labelWidth: 100,
            //                defaults: { labelSeparator: ''},
            //                items: [ cmbakunjur
            //                ]
            //            }

        ],
        listeners:{
            collapse: function(n){
                //            console.log("test barang");
                //            Ext.getCmp('group_po').expand();
            },
            expand: function(n){
                //            console.log("test barang");
                dslapakun.reload();
            }
        }
    }    
    
    var strmonjurcabang=new Ext.data.Store({
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
    
    var cmb_monjur_cabang= new Ext.form.ComboBox({
        fieldLabel:		'Cabang',
        mode:           'local',
        value:          '',
        triggerAction:  'all',
        forceSelection: true,
        editable:       true,
        name:           'nama_cabang',
        id:           	'monjur_nama_cabang',
        hiddenName:     'kd_cabang',
        displayField:   'nama_cabang',
        valueField:     'kd_cabang',
        anchor:			'80%',
        emptyText:'All',
        store:  strmonjurcabang
//        allowBlank:false
//        ,
//        listeners:{
//            select: function(combo, records) {
//                var vcabang = this.getValue();                
//                strapprovalvoucher.reload({params:{kd_cabang:vcabang}});
//            }
//        }
    });
    var cmb_dk_monjur={
                        xtype:          'combo',
                        fieldLabel:		'Debet/Kredit <span class="asterix">*</span>',
                        mode:           'local', 
                        value:          '',
                        triggerAction:  'all',
                        forceSelection: true,
                        editable:       false,
                        name:           'dk',
                        id:           	'id_dk_monjur',
                        hiddenName:     'dk',
                        displayField:   'name',
                        valueField:     'value',
                        anchor:			'60%',
                        store:          new Ext.data.JsonStore({
                            fields : ['name', 'value'],
                            data   : [
                                {name : 'Debet', value: 'd'},
                                {name : 'Kredit', value: 'k'},
                                {name : 'All', value: 'dk'}
                            ]
                        }),
                        allowBlank: false
                                                        
                    }
    var headermonjurnal={
        region:'north',
        layout: 'column',
        border: false,
        items: [{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 80,
                defaults: { labelSeparator: ''},
                items: [dtpjur,dtpjurakhir,cmb_monjur_cabang
                    
                ]
           
            },{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 80,
                defaults: { labelSeparator: ''},
                items: [cmb_dk_monjur,select_akun
                    
                ]
           
            }],
        bbar:[
            {
                text:'Preview',
                //iconCls:'glapp-preview',
                id:'bbarpreviewjur'
                ,handler: function(){
                    var kdakun=null;
                    var tglawal=null;
                    var tglakhir=null;
                    if(Ext.getCmp('dtpjur').getValue()>Ext.getCmp('dtpjurakhir').getValue()){
                        Ext.Msg.show({
                            title: 'Validasi Tanggal',
                            msg: 'Tanggal Awal Lebih Besar Dari Tanggal Akhir',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK                           
                        });
                        return;
                    }
                    tglawal=Ext.getCmp('dtpjur').getValue().format('Y-m-d');
                    tglakhir=Ext.getCmp('dtpjurakhir').getValue().format('Y-m-d');
//                    kdakun=Ext.getCmp('cmbakunjur').getValue();
                    kdakun=Ext.getCmp('mca_akunmonjur').getValue();
                    var kdcabang=Ext.getCmp('monjur_nama_cabang').getValue();
                    var dk=Ext.getCmp('id_dk_monjur').getValue();
//                        strmonjurnal2.load({params:{
//                                tglawal:tglawal,
//                                tglakhir:tglakhir
//                            
//                            }}); 
                    
                    if(!Ext.getCmp('id_select_akun').collapsed){
//                        kdakun=Ext.getCmp('cmbakunjur').getValue();
                        kdakun=Ext.getCmp('mca_akunmonjur').getValue();
                        strmonjurnal.reload({params:{
                                tglawal:tglawal,
                                tglakhir:tglakhir,
                                akun:kdakun,
                                kd_cabang:kdcabang,
                                dk:dk
                                
                            
                            }}); 
                    }else
                    {
                        strmonjurnal.reload({params:{
                                tglawal:tglawal,
                                tglakhir:tglakhir
                            ,kd_cabang:kdcabang,
                                dk:dk
                            }}); 
                    }
                    
                                   
                }
            }
            ,'-'
            //            ,{
            //                text:'preview tanggal',
            //                //iconCls:'glapp-preview',
            //                id:'bbarpreviewtjur'
            //                ,handler:function(){
            //                                        
            //                }
            //            },'-'
            //            ,{
            //                text:'preview akun',
            //                //iconCls:'glapp-preview',
            //                id:'bbarpreviewajur'
            //                ,handler:function(){
            //                                        
            //                }
            //            },'-'
            //            ,{
            //                text:'cetak',
            //                // iconCls:'glapp-cetak',
            //                id:'bbarcetakjur'
            //                ,handler:function(){
            //                                        
            //                }
            //            },'-'
        ]
    } 
    var strmonjurnal=new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [ 
                'idjurnal', 
                'tgl_transaksi', 
                'kd_transaksi', 
                'nama_transaksi', 
                'referensi', 
                'keterangan', 
                'kd_akun',
                'nama',
                'dk_akun', 
                'dk_transaksi', 
                'faktor', 
                'jumlah', 
                'debet', 
                'kredit', 
                'kd_costcenter',
                'nama_costcenter',
                'keterangan_detail',
                'ref_detail',
                'created_by', 
                'created_date',
                'typepost',
                'idpost',
                'kd_cabang',
                'nama_cabang'
                
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("account_monjurnal/get_view") ?>',
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
    
    function set_total_monjur(){		
        var totaldebet=0;
        var totalkredit=0;
        //        var totalselisih=0;
                
        strmonjurnal.each(function(node){			
            totaldebet += parseInt(node.data.debet);
            totalkredit += parseInt(node.data.kredit);
        });
        //        totalselisih=totaldebet-totalkredit;
        Ext.getCmp('monjur_t_debet').setValue(totaldebet);
        Ext.getCmp('monjur_t_kredit').setValue(totalkredit);
        //        Ext.getCmp('evr_t_selisih').setValue(totalselisih);
                
    };
    strmonjurnal.on('load', function(){
        set_total_monjur();
		
		
    });
    
    strmonjurnal.on('update', function(){
        set_total_monjur();
		
		
    });
    strmonjurnal.on('remove',  function(){
        set_total_monjur();
		
    });
    strmonjurnal.on('removeAll',  function(){
        set_total_monjur();
		
    });
    
        var gridmonjurnal = new Ext.grid.GridPanel({
        region:'center',
        store: strmonjurnal,
        stripeRows: true,
        height: 300,
        frame: true,
        loadMask: true,
        border:true,
        //        plugins: [editorentryjp],        
        columns: [    
            {                //            xtype: 'numbercolumn',
                header: 'Id Posting From',
                dataIndex: 'idpost',
                width: 120,                
                sortable: true             	
			
            },
            {                //            xtype: 'numbercolumn',
                header: 'Id Jurnal',
                dataIndex: 'idjurnal',
                width: 100,                
                sortable: true             	
			
            },
            {                //            xtype: 'numbercolumn',
                header: 'Tanggal Transaksi',
                dataIndex: 'tgl_transaksi',
                width: 80,                
                sortable: true             	
			
            },
  
            {                //            xtype: 'numbercolumn',
                header: 'Kode Transaksi',
                dataIndex: 'kd_transaksi',
                width: 50,                
                sortable: true             	
			
            },
  
            {                //            xtype: 'numbercolumn',
                header: 'Nama Transaksi',
                dataIndex: 'nama_transaksi',
                width: 80,                
                sortable: true             	
			
            },
            {                //            xtype: 'numbercolumn',
                header: 'Referensi',
                dataIndex: 'referensi',
                width: 80,                
                sortable: true             	
		,hidden:true	
            },
            {                //            xtype: 'numbercolumn',
                header: 'Keterangan',
                dataIndex: 'keterangan',
                width: 200,                
                sortable: true             	
			
            },
            
            {
                //            xtype: 'numbercolumn',
                header: 'Kode Akun',
                dataIndex: 'kd_akun',
                width: 80,
                format: '0',
                sortable: true             	
			
            },{
                header: 'Nama Akun',
                dataIndex: 'nama',
                width: 200
            }
            ,{
                header: 'D/K Akun',
                dataIndex: 'dk_akun',
                width: 120,
                hidden:true
            },{
                header: 'D/K Transaksi',
                dataIndex: 'dk_transaksi',
                width: 120,
                hidden:true
            },{
                xtype: 'numbercolumn',
                header: 'Debet',
                dataIndex: 'debet',			
                width: 80,
                sortable: true,
                format: '0,0'   
                ,align:'right'
            },{
                xtype: 'numbercolumn',
                header: 'Kredit',
                dataIndex: 'kredit',			
                width: 80,
                sortable: true,
                format: '0,0',
                align:'right'
            },{
                header: 'kd costcenter',
                dataIndex: 'kd_costcenter',
                width: 80,                
                sortable: true
            },{
                header: 'CostCenter',
                dataIndex: 'nama_costcenter',
                width: 100,                
                sortable: true
            },{
                header: 'Referensi',
                dataIndex: 'ref_detail',
                width: 80,                
                sortable: true
            },{
                header: 'Keterangan_Detail',
                dataIndex: 'keterangan_detail',
                width: 200,                
                sortable: true
            },{                //            xtype: 'numbercolumn',
                header: 'Kode Cabang',
                dataIndex: 'kd_cabang',
                width: 50,                
                sortable: true             	
//                ,hidden:true
			
            },{                //            xtype: 'numbercolumn',
                header: 'Nama Cabang',
                dataIndex: 'nama_cabang',
                width: 80,                
                sortable: true             	
//                ,hidden:true
			
            },{                //            xtype: 'numbercolumn',
                header: 'Posting From',
                dataIndex: 'typepost',
                width: 80,                
                sortable: true             	
			
            },{                //            xtype: 'numbercolumn',
                header: 'Created By',
                dataIndex: 'created_by',
                width: 80,                
                sortable: true             	
			
            }
//            ,{                //            xtype: 'numbercolumn',
//                header: 'Created Date',
//                dataIndex: 'created_date',
//                width: 80,                
//                sortable: true             	
//			
//            }
        ],bbar: [new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strmonjurnal,
            displayInfo: true
        }), '->','Total Debet :',{xtype: 'numericfield',currencySymbol: '',id: 'monjur_t_debet',fieldClass:'number',readOnly:true },
            'Total Kredit :',{xtype:'numericfield',currencySymbol: '',id: 'monjur_t_kredit',fieldClass:'number',  readOnly:true }
        ]
    });
    var monjurnal_form = new Ext.FormPanel({
        id: 'monitoringjurnal',
        border: false,
        frame: true,
        autoScroll:true, 
        monitorValid: true,       
        bodyStyle:'padding-right:20px;',
        labelWidth: 130,
        layout:'border',
        items: [headermonjurnal,gridmonjurnal]
        ,listeners:{
            afterrender:function(){
                strmonjurcabang.reload();
                Ext.getCmp('id_dk_monjur').setValue('dk');
            },
            show:function(){
                strmonjurcabang.reload();
                Ext.getCmp('id_dk_monjur').setValue('dk');
                var dtbbp = new Date();
                Ext.getCmp('dtpjur').setValue(dtbbp);
//                Ext.getCmp('dtpjurakhir').setValue(dtbbp);
                
            }
        }
    });
        
</script>