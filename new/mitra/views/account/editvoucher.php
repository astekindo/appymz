<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
/*
 * To change this template, choose Tools | Templates
 * and open the template in the editor.
 */
?>
<script>
    var stredvrcabang=createStoreData([ 
                'kd_cabang',
                'nama_cabang'
            ], '<?= site_url("account_entry_voucher/get_cabang") ?>');        
    
    var cmb_edvr_cabang= new Ext.form.ComboBox({
        fieldLabel:		'Cabang<span class="asterix">*</span>',
        mode:           'local',
        value:          '',
        triggerAction:  'all',
        forceSelection: true,
        editable:       false,
        name:           'nama_cabang',
        id:           	'edvr_nama_cabang',
        hiddenName:     'kd_cabang',
        displayField:   'nama_cabang',
        valueField:     'kd_cabang',
        anchor:			'90%',
        store:  stredvrcabang,
        allowBlank:false   
        
    });
    
    function get_header_master_e(idmaster){        
        Ext.Ajax.request({
            url: '<?= site_url("transaksi/get_rows") ?>',
            method: 'POST',
            params: { query: idmaster },            
            success: function( action){ 
                var response=action.responseText;
                response=response.replace('[', '');
                response=response.replace(']', '');
                var fe = Ext.util.JSON.decode(response);
                Ext.getCmp('edvr_kd_jenis_voucher').setValue(fe.data.kd_jenis_voucher);
            },
            failure: function( action){        
                var fe = Ext.util.JSON.decode(action.responseText);
                Ext.getCmp('edvr_kd_jenis_voucher').setValue('');
            }
            
        });
         
    }
    var data_approval_limit_e=new Array();
    function set_type_transaksi_e(idmaster){        
        Ext.Ajax.request({
            url: '<?= site_url("account_entry_voucher/get_header_transaksi") ?>',
            method: 'POST',
            params: { query: idmaster },            
            success: function( action){ 
                var response=action.responseText;
                //                console.log(response);
                if(!response==''){
                    response=response.replace('[', '');
                    response=response.replace(']', '');
                    var fe = Ext.util.JSON.decode(response);   
                    data_approval_limit_e=fe.data;
                    //                    console.log(fe.data.type_transaksi1);
                    
                    if(fe.data.type_transaksi1==1){
                        Ext.getCmp('edvr_type_transaksi').expand(true); 
                        Ext.getCmp('edvr_type_transaksi').setDisabled(false);
                                       
                    }else{
                        Ext.getCmp('edvr_type_transaksi').collapse(true); 
                        Ext.getCmp('edvr_type_transaksi').setDisabled(true);
                                       
                    }
                    
                    if(fe.data.approval1==1){                        
                        Ext.getCmp('edvr_approvallevel').setValue('approval1',true);                                
                    }else{                        
                        Ext.getCmp('edvr_approvallevel').setValue('approval1',false);
                    }
                    
                    if(fe.data.approval2 == 1){                     
                        Ext.getCmp('edvr_approvallevel').setValue('approval2',true);
                    }else{                        
                        Ext.getCmp('edvr_approvallevel').setValue('approval2',false);
                    }
                    if(fe.data.approval3==1){                     
                        Ext.getCmp('edvr_approvallevel').setValue('approval3',true);
                    }else{                        
                        Ext.getCmp('edvr_approvallevel').setValue('approval3',false);
                    }
                
                }
                
            },
            failure: function( action){        
                var fe = Ext.util.JSON.decode(action.responseText);
                
            }
            
        });
         
    }
    
    var stredvrakuntrx=createStoreData([ 
                'kd_transaksi',
                'nama_transaksi'
                
            ], '<?= site_url("transaksi/get_rows") ?>');        
    var cmb_edvr= new Ext.form.ComboBox({
        fieldLabel:		'Nama Transaksi',
        mode:           'local',
        flex: 1,
        value:          '',
        triggerAction:  'all',
        forceSelection: true,
        editable:       false,
        name:           'nama_transaksi',
        id:           	'edvr_nama_transaksi',
        hiddenName:     'kd_transaksi',
        displayField:   'nama_transaksi',
        valueField:     'kd_transaksi',
        anchor:			'90%',
        store:  stredvrakuntrx,
        disabled :true,
        //        allowBlank:false,   
        listeners:{
            select: function(combo, records) {
                var vidtrx = this.getValue();                
                streditvoucher.reload({params:{query:vidtrx}});                
                get_header_master_e(vidtrx);              
                set_type_transaksi_e(vidtrx);              
                
            }
        }
    });
    
    var str_edvr_jenisvoucher= createStoreData([
        {name: 'kd_jenis_voucher', allowBlank: false, type: 'string'},
        {name: 'title', allowBlank: false, type: 'string'},
        {name: 'dk',type: 'string'},
        {name: 'auto_posting_voucher',type: 'bool'}
                
    ], '<?= site_url("transaksi/get_rows_jenisvoucher") ?>');
    
    var str_edvr_jenisvoucher_akun= createStoreData([
        {name: 'kd_jenis_voucher', allowBlank: false, type: 'string'},
        {name: 'kd_akun', allowBlank: false, type: 'string'},         
        {name: 'nama', allowBlank: false, type: 'string'},
    ],'<?= site_url("transaksi/get_rows_jenisvoucher_akun") ?>');
    
    var cmb_edvr_jv= new Ext.form.ComboBox({
        fieldLabel:		'Jenis Voucher <span class="asterix">*</span>',
        allowBlank:false,
        readOnly:false,
        mode:           'local',
        value:          '',
        triggerAction:  'all',
        forceSelection: true,
        editable:       true,
        name:           'kd_jenis_voucher',
        id:'edvr_kd_jenis_voucher',
        //        id:           	'evr_nama_costcenter',
        hiddenName:     'kd_jenis_voucher',
        displayField:   'title',
        valueField:     'kd_jenis_voucher',
        anchor:			'90%',
        store:  str_edvr_jenisvoucher,
        listeners:{
            select: function(combo, records) {
                afterselect_jv(records.get('dk'),combo.getValue(),records.get('auto_posting_voucher'));
//                grideditvoucher.addBtn.setDisabled(false);
//                autopose=records.get('auto_posting_voucher');
//                if (!Ext.getCmp('edvr_ismaster').getValue()){                    
//                    var vdkvoucher = records.get('dk'); 
//                    if (vdkvoucher.trim() == 'd'){
//                        if (streditvoucher.data.getCount()>0){
//                            streditvoucher.removeAll();
//                            Ext.getCmp('edvr_t_debet').setValue(0);
//                            Ext.getCmp('edvr_t_kredit').setValue(0);
//                            Ext.getCmp('edvr_t_selisih').setValue(0);
//                        }
//                        Ext.getCmp('edvr_type_transaksi').collapse(true); 
//                        Ext.getCmp('edvr_type_transaksi').setDisabled(true);
//
//                        
//                    }
//                    if (vdkvoucher.trim() == 'k'){
//                        if (streditvoucher.data.getCount()>0){
//                            streditvoucher.removeAll();
//                            Ext.getCmp('edvr_t_debet').setValue(0);
//                            Ext.getCmp('edvr_t_kredit').setValue(0);
//                            Ext.getCmp('edvr_t_selisih').setValue(0);
//                        }
//                        Ext.getCmp('edvr_type_transaksi').expand(true); 
//                        Ext.getCmp('edvr_type_transaksi').setDisabled(false);
//                    }
//                    var kdv=combo.getValue();
//                    str_edvr_jenisvoucher_akun.reload({params:{query:kdv}});
//                    
//                }
                
            }
        }
    });
    function afterselect_jv(dk,cmbval,auto){
                grideditvoucher.addBtn.setDisabled(false);
                autopose=auto;
                if (!Ext.getCmp('edvr_ismaster').getValue()){                    
                    var vdkvoucher = dk; 
                    if (vdkvoucher.trim() == 'd'){
                        if (streditvoucher.data.getCount()>0){
                            streditvoucher.removeAll();
                            Ext.getCmp('edvr_t_debet').setValue(0);
                            Ext.getCmp('edvr_t_kredit').setValue(0);
                            Ext.getCmp('edvr_t_selisih').setValue(0);
                        }
                        Ext.getCmp('edvr_type_transaksi').collapse(true); 
                        Ext.getCmp('edvr_type_transaksi').setDisabled(true);

                        
                    }
                    if (vdkvoucher.trim() == 'k'){
                        if (streditvoucher.data.getCount()>0){
                            streditvoucher.removeAll();
                            Ext.getCmp('edvr_t_debet').setValue(0);
                            Ext.getCmp('edvr_t_kredit').setValue(0);
                            Ext.getCmp('edvr_t_selisih').setValue(0);
                        }
                        Ext.getCmp('edvr_type_transaksi').expand(true); 
                        Ext.getCmp('edvr_type_transaksi').setDisabled(false);
                    }
//                    var kdv=combo.getValue();
                    str_edvr_jenisvoucher_akun.reload({params:{query:cmbval}});
                    
                }
                
            }
    var stredvrcc=createStoreData([ 
                'kd_costcenter',
                'nama_costcenter'
            ], '<?= site_url("account_mcostcenter/get_rows_all") ?>');
    
    
    
    
    //-----------------------------------------------------------------
    var str_cmb_kdv=createStoreArray(['kd_voucher'],[]);
            
    var str_kdv=createStoreData([ 
        'kd_voucher',         
        'tgl_transaksi',      
        'kd_transaksi', 
        'nama_transaksi',
        'keterangan',         
        'kd_cabang',
        'nama_cabang',
        'type_transaksi',     
        'diterima_oleh',      
        'no_giro_cheque',     
        'kd_jenis_voucher',   
        'jenis_voucher',
        'tgl_jttempo',        
        'approval1',          
        'approval2',          
        'approval3',          
        'auto_posting_voucher'
    ], '<?= site_url("account_edit_voucher/get_rows_header") ?>');
    
    var search_kdv=createSearchField('id_search_kdv', str_kdv, 250);
    var strloadakun=createStoreData([
                {name: 'kd_akun', allowBlank: false, type: 'string'},
                {name: 'nama', allowBlank: false, type: 'string'},
                {name: 'dk_akun', allowBlank: false, type: 'string'},
                {name: 'dk_transaksi', allowBlank: false, type: 'string'},                           
                {name: 'debet', allowBlank: false, type: 'int'},
                {name: 'kredit', allowBlank: false, type: 'int'},
                {name: 'ref_detail', allowBlank: false, type: 'string'},                                           
                {name: 'costcenter', allowBlank: true, type: 'string'},
                {name: 'nama_costcenter', allowBlank: true, type: 'string'},                
                {name: 'keterangan_detail', allowBlank: false, type: 'string'}                                   
            ], '<?= site_url("account_edit_voucher/get_rows_detail") ?>');
    strloadakun.on('load',function(store , records, object){
        console.log(records[0].data.kd_akun);
        if(Ext.getCmp('edvr_ismaster').getValue()){
            console.log(streditvoucher);
        }else{
//            strloadakun.commitChanges();
//            console.log(strloadakun);
            var myNewRecord = null;
            var node=null;
            for(var i=0,len=records.length; i<len; i++){
//                console.log(records[i].data.kd_akun);
                node=records[i];
                myNewRecord=new streditvoucher.recordType({
                                kd_akun:node.data.kd_akun,
                                nama:node.data.nama,
                                dk_akun:node.data.dk_akun,
                                dk_transaksi:node.data.dk_transaksi.toUpperCase(),
                                debet:node.data.debet,
                                kredit:node.data.kredit,
                                ref_detail:node.data.ref_detail,
                                costcenter:node.data.costcenter,
                                nama_costcenter:node.data.nama_costcenter,
                                keterangan_detail:node.data.keterangan_detail
                            });
                            streditvoucher.add(myNewRecord);
                            set_total_edvr();
                
            }                        
        }
    });
    var grid_kdv = new Ext.grid.GridPanel({        
        //id:'id_searchgrid_akun_transaksi',
        store: str_kdv,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [
            {
                header: 'No Voucher',
                dataIndex: 'kd_voucher',
                width: 100,
                sortable: true			
            
            },{
                header: 'Tgl Transaksi',
                dataIndex: 'tgl_transaksi',
                width: 80,
                sortable: true         
            },{
                header: 'Jenis Voucher',
                dataIndex: 'jenis_voucher',
                width: 150,
                sortable: true         
            },{
                header: 'Keterangan',
                dataIndex: 'keterangan',
                width: 200,
                sortable: true         
            },{
                header: 'Transaksi',
                dataIndex: 'nama_transaksi',
                width: 200,
                sortable: true         
            }
        ],
        tbar: new Ext.Toolbar({
            items: [search_kdv]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: str_kdv,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                var kdcc=null,kdakun=null;
                if (sel.length > 0) {	
                    
                    
                    Ext.getCmp('id_kdv').setValue(sel[0].get('kd_voucher'));
                    Ext.getCmp('edvr_tgl_transaksi').setValue(sel[0].get('tgl_transaksi'));
                    Ext.getCmp('edvr_nama_cabang').setValue(sel[0].get('kd_cabang'));
                    var kdtrx=sel[0].get('kd_transaksi');
                    console.log(kdtrx);
                    if(kdtrx){
                        if(kdtrx!=''){      
                            console.log('masuk sini');
                            Ext.getCmp('edvr_ismaster').setValue(true);
                            Ext.getCmp('edvr_nama_transaksi').setValue(kdtrx);                                       
                            get_header_master_e(kdtrx);              
                            set_type_transaksi_e(kdtrx);   
                            streditvoucher.proxy.conn.url = '<?= site_url("account_edit_voucher/get_rows_detail") ?>';
                            streditvoucher.reload({params:{query:sel[0].get('kd_voucher')}});
                            set_total_edvr();
                        }else{
                            Ext.getCmp('edvr_ismaster').setValue(false);
                            Ext.getCmp('edvr_kd_jenis_voucher').setValue(sel[0].get('kd_jenis_voucher'));
                        }
                    }else{
                            Ext.getCmp('edvr_ismaster').setValue(false);
                            Ext.getCmp('edvr_kd_jenis_voucher').setValue(sel[0].get('kd_jenis_voucher'));
                        }
                    
                    Ext.getCmp('edvr_diterima_oleh').setValue(sel[0].get('diterima_oleh'));
                    Ext.getCmp('edvr_no_giro_cheque').setValue(sel[0].get('no_giro_cheque'));
                    Ext.getCmp('edvr_tgl_jttempo').setValue(sel[0].get('tgl_jttempo'));
                    var strjv=Ext.getCmp('edvr_kd_jenis_voucher').store;
                    var recordNumber = strjv.findExact('kd_jenis_voucher', sel[0].get('kd_jenis_voucher'), 0);
                    var dk = strjv.getAt(recordNumber).data['dk'];
                    var auto=strjv.getAt(recordNumber).data['auto_posting_voucher'];
                    afterselect_jv(dk,sel[0].get('kd_jenis_voucher'),auto);


                    Ext.getCmp('edvr_keterangan').setValue(sel[0].get('keterangan'));
                    Ext.getCmp('edvr_approvallevel').setValue('approval1',sel[0].get('approval1'));
                    Ext.getCmp('edvr_approvallevel').setValue('approval2',sel[0].get('approval2'));
                    Ext.getCmp('edvr_approvallevel').setValue('approval3',sel[0].get('approval3'));
                    
                    strloadakun.reload({params:{query:sel[0].get('kd_voucher')}});
                    menu_kdv.hide();                    
                    
                }
            }
        }
    });
//    function set_akun_edit(strloadakun){
//        console.log(strloadakun);
//    }
    var menu_kdv = new Ext.menu.Menu();
    
    setPanelMenu(menu_kdv, 'Pilih CostCenter', 400, 300, grid_kdv, function(){
        menu_kdv.hide();
    }, function(){
        var sf = Ext.getCmp('id_search_kdv').getValue();
        if( sf != ''){
            Ext.getCmp('id_search_kdv').setValue('');
            Ext.getCmp('id_search_kdv').onTrigger2Click();
        }
        
        
        
    });
    
    Ext.ux.TwinCombokdv = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
           
//            str_kdv.setBaseParam('query',kdakun);
                        str_kdv.load();
//            str_evr_cc.load({params:{query:kdakun}});
            menu_kdv.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    
    var cmb_kdv = new Ext.ux.TwinCombokdv({
        fieldLabel: 'No.Voucher',
        id: 'id_kdv',
        store: str_cmb_kdv,
        mode: 'local',
        valueField: 'kd_voucher',
        displayField: 'kd_voucher',
        typeAhead: true,
        triggerAction: 'all',
        //        allowBlank: false,
        editable: false,
        anchor: '50%',
        hiddenName: 'kd_voucher',
        name:'kd_voucher',
        emptyText: 'Pilih No Voucher'       
    });
    //---------------------------------------------------------------
    
    var headereditvoucher={
        layout: 'column',
        border: false,
        items: [{
                id:'edvr_left_layout',
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [
                    cmb_kdv,
//                    {
//                        xtype: 'hidden',
//                        fieldLabel: 'No.Voucher',
//                        name: 'kd_voucher',
//                        readOnly:true,
//                        fieldClass:'readonly-input',
//                        id: 'edvr_kd_voucher',                
//                        anchor: '90%',
//                        value:''
//                    },
                    {
                        xtype: 'datefield',
                        fieldLabel: 'Tanggal Transaksi',
                        name: 'tgl_transaksi',
                        allowBlank:false,   
                        format:'Y-m-d', 
                        id: 'edvr_tgl_transaksi',                
                        anchor: '50%',
                        maxValue : new Date(),
                        value:new Date()
                    }                    
                    ,cmb_edvr_cabang
                    ,{
                        xtype: 'compositefield',
                        fieldLabel: 'Master',
                        anchor    : '90%',
                        items: [
                            {xtype: 'checkbox',
                                checked: false,
                                fieldLabel: 'Master',
                                labelSeparator: '',
                                name: 'ismaster',
                                id:'edvr_ismaster'
                                ,listeners:{
                                    check:function( cb, checked){
                                        if(checked){
                                            Ext.getCmp('edvr_nama_transaksi').setDisabled(false);
                                            Ext.getCmp('edvr_nama_transaksi').getStore().reload();
                                                    
                                            Ext.getCmp('edvr_kd_jenis_voucher').setValue('');
                                            Ext.getCmp('edvr_kd_jenis_voucher').setReadOnly(true);
                                            Ext.getCmp('eg_edvr_kd_akun').setDisabled(true);
                                            Ext.getCmp('eg_edvr_kd_akun').setReadOnly(true);
                                            streditvoucher.removeAll();

                                            Ext.getCmp('edvr_t_debet').setValue(0);
                                            Ext.getCmp('edvr_t_kredit').setValue(0);
                                            Ext.getCmp('edvr_t_selisih').setValue(0);

                                            grideditvoucher.addBtn.setDisabled(true);                                            
                                            Ext.getCmp('edvr_type_transaksi').collapse(true);  
                                            
                                        }else{
                                            Ext.getCmp('edvr_kd_jenis_voucher').setValue('');
                                            Ext.getCmp('edvr_kd_jenis_voucher').setReadOnly(false);
                                            Ext.getCmp('edvr_nama_transaksi').setValue('');
                                            Ext.getCmp('edvr_nama_transaksi').setDisabled(true);
                                            Ext.getCmp('eg_edvr_kd_akun').setDisabled(false);
                                            Ext.getCmp('eg_edvr_kd_akun').setReadOnly(false);
                                            streditvoucher.removeAll();

                                            Ext.getCmp('edvr_t_debet').setValue(0);
                                            Ext.getCmp('edvr_t_kredit').setValue(0);
                                            Ext.getCmp('edvr_t_selisih').setValue(0);

                                            grideditvoucher.addBtn.setDisabled(false);

                                            Ext.getCmp('edvr_type_transaksi').collapse(true);

                                            Ext.getCmp('edvr_approvallevel').setValue('approval1',false);
                                            Ext.getCmp('edvr_approvallevel').setValue('approval2',false);
                                            Ext.getCmp('edvr_approvallevel').setValue('approval3',false);

                                        }
                                                
                                    }
                                }
                                            
                            }
                            ,cmb_edvr
                        ]
                    }                    
                    ,cmb_edvr_jv
                    ,
                    {xtype:'fieldset',
                        id:'edvr_type_transaksi',
                        name:'type_transaksi',
                        title: 'Cash Out',
                        checkboxToggle: false,
                        collapsible:false,
                        collapsed:true,
                        disabled:true,
                        autoHeight:true,  
                        anchor: '90%',                        
                        items:[{
                                xtype: 'textfield',
                                fieldLabel: 'Diterima Oleh',
                                name: 'diterima_oleh',
                                style:{textTransform: "uppercase"},
                                //                        allowBlank:false,
                                readOnly:true,                        
                                id: 'edvr_diterima_oleh',                
                                anchor: '90%',                                
                                value:''
                                
                            },{
                                xtype: 'textfield',
                                fieldLabel: 'No.Giro/Cheque',
                                name: 'no_giro_cheque',
                                //                        allowBlank:false,
                                readOnly:true,                        
                                id: 'edvr_no_giro_cheque',                
                                anchor: '90%',
                                style:{textTransform: "uppercase"},
                                value:''
                            }, {
                                xtype: 'datefield',
                                fieldLabel: 'Jatuh Tempo',
                                name: 'tgl_jttempo',
                                //                                allowBlank:false,   
                                format:'Y-m-d', 
                                id: 'edvr_tgl_jttempo',                
                                anchor: '50%',
                                //                        minValue : new Date(Ext.getCmp('edvr_tgl_transaksi').getValue()),
                                value:new Date()
                            }],listeners:{
                            collapse:function(pnl){
                                Ext.getCmp('edvr_diterima_oleh').setReadOnly(true);
                                Ext.getCmp('edvr_no_giro_cheque').setReadOnly(true);
                            },
                            expand:function(pnl){
                                Ext.getCmp('edvr_left_layout').setDisabled(false);
                                Ext.getCmp('edvr_diterima_oleh').setReadOnly(false);
                                Ext.getCmp('edvr_no_giro_cheque').setReadOnly(false);
                            }
                        }
                    }                    
                ]
            }, {
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [                      
                    {
                        xtype: 'textarea',
                        fieldLabel: 'Keterangan<span class="asterix">*</span>',
                        allowBlank:false,   
                        name: 'keterangan',				
                        id: 'edvr_keterangan',                
                        anchor: '90%',
                        style:{textTransform: "uppercase"},
                        value: ''
                    },
                    {xtype:'fieldset',
                        id:'edvr_approval12',
                        name:'approval12',
                        title: 'Approval',
                        //                        checkboxToggle: true,
                        collapsible:false,
                        autoHeight:true,  
                        anchor: '90%',
                        //                        collapsed :true,
                        disabled:true,
                        items:[{xtype: 'checkboxgroup',
                                fieldLabel: 'Approval Level',
                                id:'edvr_approvallevel',
                                items: [
                                    {boxLabel: 'Level 1', name: 'approval1' ,checked:false},
                                    {boxLabel: 'Level 2', name: 'approval2',checked:false},
                                    {boxLabel: 'Level 3', name: 'approval3',checked:false}
               
                                ]}]                                                
                    }
                    
                ]
            }]
    }
    var footereditvoucher = {
        layout: 'column',
        border: false,
        items: [{
                columnWidth: .3,
                layout: 'form',
                border: false,
                labelWidth: 80,
                defaults: { labelSeparator: ''},
                items: [{
                        xtype: 'numericfield',
                        fieldLabel: 'Total Debet',
                        name: 't_debet',
                        format:'0,0',
                        readOnly:true,
                        fieldClass:'readonly-input number',
                        currencySymbol: '',
                        id: 'edvr_t_debet',                
                        anchor: '90%',
                        value:'0'
                    } ]
            }, {
                columnWidth: .3,
                layout: 'form',
                border: false,
                labelWidth: 80,
                defaults: { labelSeparator: ''},
                items: [{
                        xtype: 'numericfield',
                        fieldLabel: 'Total Kredit',
                        name: 't_kredit',
                        //                        allowBlank:false, 
                        readOnly:true,
                        fieldClass:'readonly-input number',
                        currencySymbol: '',
                        format:'0,0', 
                        id: 'edvr_t_kredit',                
                        anchor: '90%',
                        value: '0'
                    } ]
            }, {
                columnWidth: .3,
                layout: 'form',
                border: false,
                labelWidth: 80,
                defaults: { labelSeparator: ''},
                items: [ {
                        xtype: 'numericfield',
                        fieldLabel: 'Selisih',
                        name: 't_selisih',
                        readOnly:true,
                        fieldClass:'readonly-input number',
                        currencySymbol: '',
                        //                        allowBlank:false,   
                        format:'0,0', 
                        id: 'edvr_t_selisih',                
                        anchor: '90%',
                        value: '0'
                    }]
            }]
    }
    //-----------------------------------------------------------------
    var str_cmb_edvr_cc=createStoreArray(['kd_costcenter','nama_costcenter'],[]);
            
    var str_edvr_cc=createStoreData([ 
        'kd_costcenter',
        'nama_costcenter'
    ], '<?= site_url("account_mcostcenter/get_rows_twin") ?>');
    
    var search_cc_edvr=createSearchField('id_search_edvr_cc', str_edvr_cc, 250);
    
    var grid_edvr_cc = new Ext.grid.GridPanel({        
        //id:'id_searchgrid_akun_transaksi',
        store: str_edvr_cc,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'Kode',
                dataIndex: 'kd_costcenter',
                width: 80,
                sortable: true			
            
            },{
                header: 'Nama Costcenter',
                dataIndex: 'nama_costcenter',
                width: 300,
                sortable: true         
            }],
        tbar: new Ext.Toolbar({
            items: [search_cc_edvr]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: str_edvr_cc,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                var kdcc=null,kdakun=null;
                if (sel.length > 0) {	
                    kdcc=sel[0].get('kd_costcenter');
                    kdakun=Ext.getCmp('eg_edvr_kd_akun').getValue();
                    if(Ext.getCmp('eg_edvr_kd_akun').getValue()){
                        Ext.getCmp('eg_edvr_costcenter').setValue(sel[0].get('kd_costcenter'));                    
                        Ext.getCmp('eg_edvr_nama_costcenter').setValue(sel[0].get('nama_costcenter'));
                    }                    
                    validateCostCenter(kdcc,kdakun,'eg_edvr_costcenter','eg_edvr_nama_costcenter','');                   
                    menu_edvr_cc.hide();
                }
            }
        }
    });
    var menu_edvr_cc = new Ext.menu.Menu();
    setPanelMenu(menu_edvr_cc, 'Pilih CostCenter', 250, 300, grid_edvr_cc, function(){
        menu_edvr_cc.hide();
    }, function(){
        var sf = Ext.getCmp('id_search_edvr_cc').getValue();
        if( sf != ''){
            Ext.getCmp('id_search_edvr_cc').setValue('');
            Ext.getCmp('id_search_edvr_cc').onTrigger2Click();
        }
    });
    
    Ext.ux.TwinComboCCedvr = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            //            str_cmb_mtrx_cc.reload();
            var kdakun='';
            kdakun=Ext.getCmp('eg_edvr_kd_akun').getValue();
            str_edvr_cc.load({params:{query:kdakun}});
            menu_edvr_cc.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    
    var cmb_edvr_cc = new Ext.ux.TwinComboCCevr({
        fieldLabel: 'Nama Cost Center',
        id: 'eg_edvr_costcenter',
        store: str_cmb_edvr_cc,
        mode: 'local',
        valueField: 'kd_costcenter',
        displayField: 'nama_costcenter',
        typeAhead: true,
        triggerAction: 'all',
        //        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_costcenter',
        name:'kd_costcenter',
        emptyText: 'Pilih Cost Center'       
    });
    //---------------------------------------------------------------
    // twin master akun
    var strecb_akun_voucher = new Ext.data.ArrayStore({
        fields: ['kd_akun'],
        data : []
    });
    	
   var stregrid_akun_voucher = createStoreData(['kd_akun', 'nama','dk'], '<?= site_url("account_entry_voucher/get_search_akun") ?>');
   var searchegrid_akun_voucher = createSearchField('id_searchegrid_akun_voucher', stregrid_akun_voucher, 350); 
    	
    	
    var gride_akun_voucher = new Ext.grid.GridPanel({
            
        //id:'id_searchgrid_akun_voucher',
        store: stregrid_akun_voucher,
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
            items: [searchegrid_akun_voucher]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: stregrid_akun_voucher,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {			
                    Ext.getCmp('eg_edvr_dkakun').setValue(sel[0].get('dk')); 
                    Ext.getCmp('eg_edvr_kd_akun').setValue(sel[0].get('kd_akun'));
                    Ext.getCmp('eg_edvr_nama_akun').setValue(sel[0].get('nama'));
                    
                    //                    console.log(sel[0].get('dk'));
                    if (!Ext.getCmp('edvr_ismaster').getValue()){
                        if (Ext.getCmp('edvr_kd_jenis_voucher').getValue()){
                            validateDKvoucher(
                            Ext.getCmp('edvr_kd_jenis_voucher').getValue(),
                            sel[0].get('kd_akun'),
                            'eg_edvr_dktransaksi','');
                        }
                        var kdakun=Ext.getCmp('eg_edvr_kd_akun').getValue();
                        var kdcc=Ext.getCmp('eg_edvr_costcenter').getValue();                        
                        str_edvr_cc.load({params:{query:kdakun}});
                        validateCostCenter(kdcc,kdakun,'eg_edvr_costcenter','eg_edvr_nama_costcenter','');
                    }
                    
                    
                    menue_akun_voucher.hide();
                    Ext.getCmp('eg_edvr_debet').focus();
                }
            }
        }
    });
    	
    var menue_akun_voucher = new Ext.menu.Menu();
    setPanelMenu(menue_akun_voucher, 'Pilih CostCenter', 400, 300, gride_akun_voucher, function(){
        menue_akun_voucher.hide();
    }, function(){
        var sf = Ext.getCmp('id_searchegrid_akun_voucher').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchegrid_akun_voucher').setValue('');
            Ext.getCmp('id_searchegrid_akun_voucher').onTrigger2Click();
        }
    });
            
    Ext.ux.TwinComboAkunVouchere = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            stregrid_akun_voucher.load();
            menue_akun_voucher.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    	
    menue_akun_voucher.on('hide', function(){
        var sf = Ext.getCmp('id_searchegrid_akun_voucher').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchegrid_akun_voucher').setValue('');
            searchegrid_akun_voucher.onTrigger2Click();
        }
    });
//    -----------------------------------------------------------------
    var streditvoucher = new Ext.data.Store({
        autoSave:false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_akun', allowBlank: false, type: 'string'},
                {name: 'nama', allowBlank: false, type: 'string'},
                {name: 'dk_akun', allowBlank: false, type: 'string'},
                {name: 'dk_transaksi', allowBlank: false, type: 'string'},                           
                {name: 'debet', allowBlank: false, type: 'int'},
                {name: 'kredit', allowBlank: false, type: 'int'},
                {name: 'ref_detail', allowBlank: false, type: 'string'},                                           
                {name: 'costcenter', allowBlank: true, type: 'string'},
                {name: 'nama_costcenter', allowBlank: true, type: 'string'},                
                {name: 'keterangan_detail', allowBlank: false, type: 'string'}                                   
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("account_entry_voucher/get_rows_akun") ?>',
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
        ,
        writer: new Ext.data.JsonWriter(
        {
            encode: true,
            writeAllFields: true
        })
    });
    
    function set_total_edvr(){		
        var totaldebet=0;
        var totalkredit=0;
        var totalselisih=0;
                
        streditvoucher.each(function(node){			
            totaldebet += parseInt(node.data.debet);
            totalkredit += parseInt(node.data.kredit);
        });
        totalselisih=totaldebet-totalkredit;
        Ext.getCmp('edvr_t_debet').setValue(totaldebet);
        Ext.getCmp('edvr_t_kredit').setValue(totalkredit);
        Ext.getCmp('edvr_t_selisih').setValue(totalselisih);
        
        if (!Ext.getCmp('edvr_ismaster').getValue()){
            
            Ext.getCmp('edvr_approvallevel').setValue('approval1',false); 
            Ext.getCmp('edvr_approvallevel').setValue('approval2',false); 
            Ext.getCmp('edvr_approvallevel').setValue('approval3',false); 
            
            var st_apv1= str_limitapprovale.data.items[0].data.startapv1;
            var en_apv1= str_limitapprovale.data.items[0].data.endapv1;
            var st_apv2= str_limitapprovale.data.items[0].data.startapv2;
            var en_apv2= str_limitapprovale.data.items[0].data.endapv2;
            var st_apv3= str_limitapprovale.data.items[0].data.startapv3;
            var en_apv3= str_limitapprovale.data.items[0].data.endapv3;
            
            
            if (totaldebet>st_apv1 && totaldebet<=en_apv1){            
                Ext.getCmp('edvr_approvallevel').setValue('approval1',true);   
            } 

            if (totaldebet>st_apv2 && totaldebet<=en_apv2){  
                Ext.getCmp('edvr_approvallevel').setValue('approval1',true); 
                Ext.getCmp('edvr_approvallevel').setValue('approval2',true);   
            }
            
            if (en_apv3==0 && st_apv3>0){
                if (totaldebet>st_apv3){                
                    Ext.getCmp('edvr_approvallevel').setValue('approval1',true); 
                    Ext.getCmp('edvr_approvallevel').setValue('approval2',true);   
                    Ext.getCmp('edvr_approvallevel').setValue('approval3',true);   
                }
            }else{
                if (totaldebet>st_apv3 && totaldebet<=en_apv3){                
                    Ext.getCmp('edvr_approvallevel').setValue('approval1',true); 
                    Ext.getCmp('edvr_approvallevel').setValue('approval2',true);   
                    Ext.getCmp('edvr_approvallevel').setValue('approval3',true);   
                }
            }            
        }        
    };
    streditvoucher.on('update', function(){
        set_total_edvr();        
		
    });
    streditvoucher.on('remove',  function(){
        set_total_edvr();
		
    });
    
    streditvoucher.on('load', function(str,rec,opt){
        var totaldebet=0;
        var totalkredit=0;
        var totalselisih=0;
        
        for(var i=0;i<rec.length;i++){            
            totaldebet += parseInt(rec[i].data.debet);
            totalkredit += parseInt(rec[i].data.kredit);
        }
        totalselisih=totaldebet-totalkredit;
        
        Ext.getCmp('edvr_t_debet').setValue(totaldebet);
        Ext.getCmp('edvr_t_kredit').setValue(totalkredit);
        Ext.getCmp('edvr_t_selisih').setValue(totalselisih);
    });
    
    Ext.override(Ext.ux.grid.RowEditor, {
    showTooltip: function(msg) {
        var t = this.tooltip;
        if(!t){
            t = this.tooltip = new Ext.ToolTip({
                maxWidth: 600,
                cls: 'errorTip',
                width: 300,
                title: this.errorText,
                autoHide: false,
                anchor: 'bottom',
                anchorToTarget: true,
                mouseOffset: [40,0]
            });
//            t.hide();
        }
        var v = this.grid.getView(),
            top = parseInt(this.el.dom.style.top, 10),
            scroll = v.scroller.dom.scrollTop,
            h = this.el.getHeight();

        if(top + h >= scroll){
            t.initTarget(this.lastVisibleColumn().getEl());
            if(!t.rendered){
//                t.show();
                t.hide();
            }
//            t.body.update(msg);
//            t.doAutoWidth(20);
//            t.show();
            t.hide();
        }else if(t.rendered){
            t.hide();
        }
    }
});

//============== editorentryvoucher.setErrorSummary(false);
    var editoreditvoucher = new Ext.ux.grid.RowEditor({
        saveText: 'Update',
        monitorValid:true,tooltip :''        
        ,listeners:{
            afteredit:function(roweditor,nrec,rec,rowi){
//                console.log(str_evr_cc.getCount());
//                console.log(nrec);                
//                console.log(rec);
                if (!Ext.getCmp('edvr_ismaster').getValue()){
                    if(str_edvr_cc.getCount()>0){
                        if(!rec.data.costcenter || rec.data.costcenter===""){                       
                            set_message(1, 'Cost Center Tidak Valid', function(btn){
                                if (btn == 'ok'){                                    //                                console.log(rec.data);
                                    Ext.getCmp('id_grideditvoucher').getStore().remove(rec);
                                }

                            });
                        }
                    }
                }
            }
        }
    });
    
    var grideditvoucher = new Ext.grid.GridPanel({
        id:'id_grideditvoucher',
        store: streditvoucher,
        stripeRows: true,
        //        autoHeight:true,
        height: 220,
        frame: true,
        border:true,
        plugins: [editoreditvoucher],       
        columns: [{
                //            xtype: 'numbercolumn',
                header: 'Kode Akun',
                dataIndex: 'kd_akun',
                width: 80,
                format: '0',
                sortable: true,	
                //                editor: new Ext.form.TextField({                
                //                    readOnly: true,
                //                    id: 'eg_evr_kd_akun',
                //                    disabled :true
                //                })
                editor: new Ext.ux.TwinComboAkunVouchere({
                    readOnly:true,
                    id: 'eg_edvr_kd_akun',
                    store: strecb_akun_voucher,
                    mode: 'local',
                    valueField: 'kd_akun',
                    displayField: 'kd_akun',
                    typeAhead: true,
                    triggerAction: 'all',
                    allowBlank: false ,
                    editable: false,
                    hiddenName: 'kd_akun',
                    emptyText: 'Pilih Akun',
                    disabled :true
				
                })	
            },{
                header: 'Nama Akun',
                dataIndex: 'nama',
                width: 250,
                editor: new Ext.form.TextField({                
                    readOnly: true,
                    id: 'eg_edvr_nama_akun',
                    disabled :true
                })
            },{
                header: 'D/K Default',
                dataIndex: 'dk_akun',                
                width: 60,
                hidden:true,
                //                hiddenName: 'dk_akun',
                editor: new Ext.form.TextField({                
                    id: 'eg_edvr_dkakun'
                    //                    ,
                    //                    readOnly: true,
                    //                    disabled :true
                })
            },{
                header: 'D/K',
                dataIndex: 'dk_transaksi',
                align:'center',
                width: 50,
                editor: new Ext.form.TextField({                
                    readOnly: true,
                    id: 'eg_edvr_dktransaksi',
                    disabled :true
                })
            },{
                xtype: 'numbercolumn',
                header: 'Debet',
                align:'right',
                dataIndex: 'debet',			
                width: 80,
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'eg_edvr_debet',
                    allowBlank: false
                    ,
                    listeners:{
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                var dk = Ext.getCmp('eg_edvr_dktransaksi').getValue();  
                                dk=dk.substr(0,1);
                                console.log(dk);
                                if(Ext.getCmp('eg_edvr_kd_akun').getValue()===''){
                                    Ext.getCmp('eg_edvr_debet').setValue(0);
                                }
                                if (dk==='K'){
                                    Ext.getCmp('eg_edvr_debet').setValue(0);
                                    
                                }
                                if(Ext.getCmp('eg_edvr_kredit').getValue>0){
                                    Ext.getCmp('eg_edvr_debet').setValue(0);
                                }				
                    						
                            }, c);
                        }}
                    // 'change': function(){
                }
                //				}
            },{
                xtype: 'numbercolumn',
                header: 'Kredit',
                align:'right',
                dataIndex: 'kredit',			
                width: 80,
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'eg_edvr_kredit',
                    allowBlank: false,
                    listeners:{
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                var dk = Ext.getCmp('eg_edvr_dktransaksi').getValue();                    						
                                dk=dk.substr(0,1);
                                if(Ext.getCmp('eg_edvr_kd_akun').getValue()===''){
                                    Ext.getCmp('eg_edvr_kredit').setValue(0);
                                }
                                
                                if (dk==='D'){
                                    Ext.getCmp('eg_edvr_kredit').setValue(0);
                                    
                                }
                                //                                console.log(Ext.getCmp('eg_evr_debet').getValue());
                                if(Ext.getCmp('eg_edvr_debet').getValue()>0){
                                    Ext.getCmp('eg_edvr_kredit').setValue(0);
                                }
                    						
                    						
                            }, c);
                        }}
                    //                              
                    //				}
                }
            },{
                header:'Referensi',
                dataIndex: 'ref_detail',
                width: 100
                ,editor: new Ext.form.TextField({                                    
                    id: 'eg_edvr_ref_detail',                    
                    style:{textTransform: "uppercase"}
//                    ,allowBlank: false
                })
                ,renderer:function(value, metaData, record, rowIndex, colIndex, store) {
                    return Ext.util.Format.uppercase(value);
                }
            },{
                header: 'Cost Center',
                dataIndex: 'costcenter',
                width: 80,
                editor:cmb_edvr_cc
                //                editor: new Ext.form.TextField({                                    
                //                    id: 'eg_evr_costcenter',
                //                    allowBlank: false
                //                })
            },{
                header: 'Nama CostCenter',
                dataIndex: 'nama_costcenter',
                width: 200
                ,editor: new Ext.form.TextField({                                    
                    id: 'eg_edvr_nama_costcenter',                    
                    readOnly:true
                })
            },{
                header: 'Keterangan Detail',
                dataIndex: 'keterangan_detail',
                width: 200,
                editor: new Ext.form.TextField({                                    
                    id: 'eg_edvr_ketdetail',                    
                    style:{textTransform: "uppercase"}
                    ,allowBlank: false
                }),
                renderer:function(value, metaData, record, rowIndex, colIndex, store) {
                    return Ext.util.Format.uppercase(value);
                }
            }]
        ,listeners:{
            cellclick:function(grid, rowIndex, columnIndex, e){
                if (Ext.getCmp('edvr_ismaster').getValue()){
                    var rec = grid.getStore().getAt(rowIndex); 
                    var data=rec.get('costcenter');
                    //                    if(data == '-'){
                    Ext.getCmp('eg_edvr_costcenter').setDisabled(true);  
                    Ext.getCmp('eg_edvr_costcenter').setReadOnly(true);
                    
                    data=rec.get('dk_transaksi');        
                    if(data.toUpperCase() == 'D'){
                        Ext.getCmp('eg_edvr_kredit').setDisabled(true);  
                        Ext.getCmp('eg_edvr_debet').setDisabled(false);  
          
                    }else if(data.toUpperCase() == 'K'){
                        Ext.getCmp('eg_edvr_debet').setDisabled(true);  
                        Ext.getCmp('eg_edvr_kredit').setDisabled(false);  
          
                    }
                    Ext.getCmp('eg_edvr_addBtn').setDisabled(true);  
                }else{
                    Ext.getCmp('eg_edvr_costcenter').setDisabled(false);  
                    Ext.getCmp('eg_edvr_costcenter').setReadOnly(false); 
//                    Ext.getCmp('eg_edvr_addBtn').setDisabled(false);  
                }
                
            }
        },
        tbar:[{
                ref: '../addBtn',
                icon: BASE_ICONS + 'add.png',
                text: 'Add',
                id:'eg_edvr_addBtn',
                handler : function(){
                   
                    // access the Record constructor through the grid's store
                    if (!Ext.getCmp('edvr_kd_jenis_voucher').getValue()){
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Silahkan Isi Jenis Voucher terlebih dulu',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK			               
                        });
                        return;
                    }
                    if(Ext.getCmp('edvr_ismaster').getValue()){
                        Ext.getCmp('eg_edvr_addBtn').setDisabled(true);
                        return;
                    }
                    Ext.getCmp('eg_edvr_kd_akun').setDisabled(false);
                    Ext.getCmp('eg_edvr_kd_akun').setReadOnly(false);
                     
                    Ext.getCmp('eg_edvr_debet').setDisabled(false);
                    Ext.getCmp('eg_edvr_debet').setReadOnly(false);
                     
                    Ext.getCmp('eg_edvr_kredit').setDisabled(false);
                    Ext.getCmp('eg_edvr_kredit').setReadOnly(false);
                     
                    Ext.getCmp('eg_edvr_costcenter').setDisabled(false);
                    Ext.getCmp('eg_edvr_costcenter').setReadOnly(false);
                     
                    var Plant = grideditvoucher.getStore().recordType;
                    var rowentryvoucher = new Plant({
                        kd_akun:'',
                        nama:'',
                        dk_akun:'',
                        dk_transaksi:'',                           
                        debet:0,
                        kredit:0,
                        ref_detail:'',
                        costcenter:'', 
                        nama_costcenter:'',
                        keterangan_detail:''                        
                    
                    });
                    editoreditvoucher.stopEditing();
                    streditvoucher.insert(0, rowentryvoucher);
                    grideditvoucher.getView().refresh();
                    grideditvoucher.getSelectionModel().selectRow(0);
                    editoreditvoucher.startEditing(0);
                
                  
                }
            },{
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                id:'eg_edvr_removeBtn',
                disabled: true,
                handler: function(){
                    editoreditvoucher.stopEditing();
                    var s = grideditvoucher.getSelectionModel().getSelections();
                    for(var i = 0, r; r = s[i]; i++){
                        streditvoucher.remove(r);
                    }
                }
            }]
    });
  
    grideditvoucher.getSelectionModel().on('selectionchange', function(sm){
        if (!Ext.getCmp('edvr_ismaster').getValue()){
            grideditvoucher.removeBtn.setDisabled(sm.getCount() < 1);
        }else{
            grideditvoucher.removeBtn.setDisabled(true);
        }
    });
    //---------------------------------------------------------------
    var editvoucher_form = new Ext.FormPanel({
        id: 'editvoucher',
        border: false,
        frame: true,
        autoScroll:true, 
        monitorValid: true,       
        bodyStyle:'padding-right:20px;',
        labelWidth: 130,
        items: [
            {
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                }
                ,                  
                items: [headereditvoucher]
            }
            ,grideditvoucher
            ,{
                bodyStyle: {
                    margin: '5px 0px 15px 0px'
                },                  
                items: [
                    footereditvoucher
                ]
            }
        ],
        buttons: [{
                text: 'Save',
                formBind: true,
                handler: function(){       
                    if(streditvoucher.getCount()==0){
                        set_message(1,'Akun Transaksi Kosong!!!',null);
                        return;
                    }
                    if(Ext.getCmp('edvr_t_selisih').getValue()!='0'){
                        set_message(1, 'Masih ada Selisih Total Debet dan Total Kredit', null);                        	
                        return;
                    }
                
                    if(Ext.getCmp('edvr_t_debet').getValue()=='0' && Ext.getCmp('edvr_t_kredit').getValue()=='0'){
                        set_message(1, 'Total Debet dan Total Kredit Masih 0', null);                            
                        return;
                    }
                    var validasi_jvakun = 0;
                    var validasi_ketdetail=0;
                    var detailevr = new Array();		
//                    console.log(strentryvoucher);
                    streditvoucher.each(function(node){
                        if(node.data.dk_transaksi==''){
                            if(node.data.debet>0){
                                node.data.dk_transaksi='D';
                            }else{
                                node.data.dk_transaksi='K';
                            }
                            
                        }
                        if(node.data.dk_transaksi.length > 1){
                            var dkt=node.data.dk_transaksi;
                            node.data.dk_transaksi=dkt.substr(0,1);
                        }
                        
                        if (!node.data.keterangan_detail || node.data.keterangan_detail===''){
                            validasi_ketdetail=1;
                        }
                        console.log(Ext.getCmp('edvr_ismaster').getValue());
                        if (!Ext.getCmp('edvr_ismaster').getValue()){  
                                
                                if(str_edvr_jenisvoucher_akun.getCount()> 0){
                                    str_edvr_jenisvoucher_akun.each(function(nd){
//                                        console.log(nd.data.kd_akun+' '+node.data.kd_akun+'-');
                                        if(nd.data.kd_akun==node.data.kd_akun){                                                        
                                            validasi_jvakun=1;
                                        }
                                    });
                                }
                            }
                        detailevr.push(node.data)
                    });
                    if(validasi_ketdetail==1){
                        set_message(1,'Keterangan Detail masih kosong',null);
                        return;
                    }
                    if(str_edvr_jenisvoucher_akun.getCount()> 0){
                        if (validasi_jvakun==0) {
                            set_message(1,'data akun tidak sesuai dengan jenis voucher',null);
                            //                    console.log('data akun tidak sesuai dengan jenis voucher');
                            return;
                        }
                    }
                                
                    //                    ======eksekusi
                    Ext.Msg.show({
                        title: 'Confirm',
                        msg: 'Are you sure to save this entry ?',
                        buttons: Ext.Msg.YESNO,
                        fn: function(btn){
                            if (btn == 'yes') {
                                var type_transaksi='';
                                if(Ext.getCmp('evr_type_transaksi').collapsed){
                                    type_transaksi='Cash In';
                                }else{
                                    type_transaksi='Cash Out';
                                }
                                
                                var autopost='off';
                                if(autopose){
                                    autopost='on';
                                }                              
                                Ext.getCmp('editvoucher').getForm().submit({
                                    url: '<?= site_url("account_edit_voucher/update_row") ?>',
                                    scope: this,
                                    params: {
                                        data: Ext.util.JSON.encode(detailevr),
                                        type_transaksi:type_transaksi,
                                        autopost:autopost
                                    },
                                    waitMsg: 'Saving Data...',
                                    success: function(form, action){
                                        set_message(0, 'Form submitted successfully', null);                                        	            
                                        clearEdvr();				
                                    },
                                    failure: function(form, action){        
                                        var fe = Ext.util.JSON.decode(action.response.responseText);
                                        set_message(1, fe.errMsg, function(btn){
                                                if (btn == 'ok' && fe.errMsg == 'Session Expired') {
                                                    window.location = '<?= site_url("auth/login") ?>';
                                                }
                                            });   
                                    }			        
                                });	
                            }
                        }
                    });          
                }
            },{
                text: 'Reset',
                handler: function(){
                    clearEdvr();
                }
            }
        ],
        listeners:{
            afterrender:function(){                
                this.getForm().load({
                    url: '<?= site_url("account_edit_voucher/get_form") ?>',
                    failure: function(form, action){
                        var de = Ext.util.JSON.decode(action.response.responseText);
                        Ext.Msg.show({
                            title: 'Error',
                            msg: de.errMsg,
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK,
                            fn: function(btn){
                                if (btn == 'ok' && de.errMsg == 'Session Expired') {
                                    window.location = '<?= site_url("auth/login") ?>';
                                }
                            }
                        });
                    }
                });
                //                Ext.getCmp('evr_tgl_transaksi').setValue(new Date());
                stredvrakuntrx.reload();
                stredvrcc.reload();
                stredvrcabang.reload();
                str_limitapprovale.reload();
                str_edvr_jenisvoucher.reload();
                grideditvoucher.addBtn.setDisabled(true);
                
            }
            ,
            show:function(){             
                stredvrcc.reload();           
            }
            
        }
    });
    function clearEdvr(){
        Ext.getCmp('editvoucher').getForm().reset();
        Ext.getCmp('editvoucher').getForm().load({
            url: '<?= site_url("account_edit_voucher/get_form") ?>',
            failure: function(form, action){
                var de = Ext.util.JSON.decode(action.response.responseText);
                Ext.Msg.show({
                    title: 'Error',
                    msg: de.errMsg,
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK,
                    fn: function(btn){
                        if (btn == 'ok' && de.errMsg == 'Session Expired') {
                            window.location = '<?= site_url("auth/login") ?>';
                        }
                    }
                });
            }
        });
        Ext.getCmp('edvr_type_transaksi').collapse(true); 
        Ext.getCmp('edvr_ismaster').setValue(false); 
        
        str_edvr_jenisvoucher.load();
        stredvrakuntrx.load();
        streditvoucher.removeAll();
        str_limitapprovale.load();
        
    }
</script>
