<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">	
    
    var strevrakuntrx=new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [ 
                'kd_transaksi',
                'nama_transaksi'
                
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("transaksi/get_rows") ?>',
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
    
    function get_header_master(idmaster){        
        Ext.Ajax.request({
            url: '<?= site_url("transaksi/get_rows") ?>',
            method: 'POST',
            params: { query: idmaster },            
            success: function( action){ 
                var response=action.responseText;
                response=response.replace('[', '');
                response=response.replace(']', '');
                var fe = Ext.util.JSON.decode(response);
                Ext.getCmp('evr_kd_jenis_voucher').setValue(fe.data.kd_jenis_voucher);
            },
            failure: function( action){        
                var fe = Ext.util.JSON.decode(action.responseText);
                Ext.getCmp('evr_kd_jenis_voucher').setValue('');
            }
            
        });
         
    }
    var data_approval_limit=new Array();
    function set_type_transaksi(idmaster){        
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
                    data_approval_limit=fe.data;
                    //                    console.log(fe.data.type_transaksi1);
                    
                    if(fe.data.type_transaksi1==1){
                        Ext.getCmp('evr_type_transaksi').expand(true); 
                        Ext.getCmp('evr_type_transaksi').setDisabled(false);
                                       
                    }else{
                        Ext.getCmp('evr_type_transaksi').collapse(true); 
                        Ext.getCmp('evr_type_transaksi').setDisabled(true);
                                       
                    }
                    //                    if(fe.data.approval12==1){
                    //                        Ext.getCmp('evr_approval12').expand(true);
                    //                        
                    //                    }else{
                    //                        Ext.getCmp('evr_approval12').collapse(true);
                    //                    }
                    
                    if(fe.data.approval1==1){                        
                        Ext.getCmp('evr_approvallevel').setValue('approval1',true);                                
                    }else{                        
                        Ext.getCmp('evr_approvallevel').setValue('approval1',false);
                    }
                    
                    if(fe.data.approval2 == 1){                     
                        Ext.getCmp('evr_approvallevel').setValue('approval2',true);
                    }else{                        
                        Ext.getCmp('evr_approvallevel').setValue('approval2',false);
                    }
                    if(fe.data.approval3==1){                     
                        Ext.getCmp('evr_approvallevel').setValue('approval3',true);
                    }else{                        
                        Ext.getCmp('evr_approvallevel').setValue('approval3',false);
                    }
                
                }
                
            },
            failure: function( action){        
                var fe = Ext.util.JSON.decode(action.responseText);
                
            }
            
        });
         
    }
    
    var cmb_evr= new Ext.form.ComboBox({
        fieldLabel:		'Nama Transaksi',
        mode:           'local',
        flex: 1,
        value:          '',
        triggerAction:  'all',
        forceSelection: true,
        editable:       false,
        name:           'nama_transaksi',
        id:           	'evr_nama_transaksi',
        hiddenName:     'kd_transaksi',
        displayField:   'nama_transaksi',
        valueField:     'kd_transaksi',
        anchor:			'90%',
        store:  strevrakuntrx,
        disabled :true,
        //        allowBlank:false,   
        listeners:{
            select: function(combo, records) {
                var vidtrx = this.getValue();                
                strentryvoucher.reload({params:{query:vidtrx}});                
                get_header_master(vidtrx);              
                set_type_transaksi(vidtrx);              
                
            }
        }
    });
    
    
    
    var strevrcabang=new Ext.data.Store({
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
    
    var cmb_evr_cabang= new Ext.form.ComboBox({
        fieldLabel:		'Cabang<span class="asterix">*</span>',
        mode:           'local',
        value:          '',
        triggerAction:  'all',
        forceSelection: true,
        editable:       false,
        name:           'nama_cabang',
        id:           	'evr_nama_cabang',
        hiddenName:     'kd_cabang',
        displayField:   'nama_cabang',
        valueField:     'kd_cabang',
        anchor:			'90%',
        store:  strevrcabang,
        allowBlank:false   
        //        listeners:{
        //            select: function(combo, records) {
        //                var vidtrx = this.getValue();                
        //                strentryvoucher.reload({params:{query:vidtrx}});
        //            }
        //        }
    });
    
    
    var strevrcc=new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [ 
                'kd_costcenter',
                'nama_costcenter'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("account_mcostcenter/get_rows_all") ?>',
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
    
    //    var cmb_evr_cc= new Ext.form.ComboBox({
    //        fieldLabel:		'CostCenter',
    //        mode:           'local',
    //        value:          '',
    //        triggerAction:  'all',
    //        forceSelection: true,
    //        editable:       true,
    //        name:           'nama_costcenter',
    //        id:'eg_evr_costcenter',
    //        //        id:           	'evr_nama_costcenter',
    //        hiddenName:     'kd_costcenter',
    //        displayField:   'nama_costcenter',
    //        valueField:     'kd_costcenter',
    //        anchor:			'90%',
    //        store:  strevrcc
    //    });
    var str_evr_jenisvoucher= createStoreData([
        {name: 'kd_jenis_voucher', allowBlank: false, type: 'string'},
        {name: 'title', allowBlank: false, type: 'string'},
        {name: 'dk',type: 'string'},
        {name: 'auto_posting_voucher',type: 'bool'}
                
    ], '<?= site_url("transaksi/get_rows_jenisvoucher") ?>');
    
    var str_evr_jenisvoucher_akun= createStoreData([
        {name: 'kd_jenis_voucher', allowBlank: false, type: 'string'},
        {name: 'kd_akun', allowBlank: false, type: 'string'},         
        {name: 'nama', allowBlank: false, type: 'string'},
    ],'<?= site_url("transaksi/get_rows_jenisvoucher_akun") ?>');
    var cmb_evr_jv= new Ext.form.ComboBox({
        fieldLabel:		'Jenis Voucher <span class="asterix">*</span>',
        allowBlank:false,
        readOnly:false,
        mode:           'local',
        value:          '',
        triggerAction:  'all',
        forceSelection: true,
        editable:       true,
        name:           'kd_jenis_voucher',
        id:'evr_kd_jenis_voucher',
        //        id:           	'evr_nama_costcenter',
        hiddenName:     'kd_jenis_voucher',
        displayField:   'title',
        valueField:     'kd_jenis_voucher',
        anchor:			'90%',
        store:  str_evr_jenisvoucher,
        listeners:{
            select: function(combo, records) {
                gridentryvoucher.addBtn.setDisabled(false);
                autopos=records.get('auto_posting_voucher');
                if (!Ext.getCmp('evr_ismaster').getValue()){                    
                    var vdkvoucher = records.get('dk'); 
                    if (vdkvoucher.trim() == 'd'){
                        if (strentryvoucher.data.getCount()>0){
                            strentryvoucher.removeAll();
                            Ext.getCmp('evr_t_debet').setValue(0);
                            Ext.getCmp('evr_t_kredit').setValue(0);
                            Ext.getCmp('evr_t_selisih').setValue(0);
                        }
                        Ext.getCmp('evr_type_transaksi').collapse(true); 
                        Ext.getCmp('evr_type_transaksi').setDisabled(true);

                        
                    }
                    if (vdkvoucher.trim() == 'k'){
                        if (strentryvoucher.data.getCount()>0){
                            strentryvoucher.removeAll();
                            Ext.getCmp('evr_t_debet').setValue(0);
                            Ext.getCmp('evr_t_kredit').setValue(0);
                            Ext.getCmp('evr_t_selisih').setValue(0);
                        }
                        Ext.getCmp('evr_type_transaksi').expand(true); 
                        Ext.getCmp('evr_type_transaksi').setDisabled(false);
                    }
                    var kdv=combo.getValue();
                    str_evr_jenisvoucher_akun.reload({params:{query:kdv}});
                    
                }
                
            }
        }
    })
    
    var headerentryvoucher = {
        layout: 'column',
        border: false,
        items: [{
                id:'evr_left_layout',
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [{
                        xtype: 'hidden',
                        fieldLabel: 'No.Voucher',
                        name: 'kd_voucher',
                        readOnly:true,
                        fieldClass:'readonly-input',
                        id: 'evr_kd_voucher',                
                        anchor: '90%',
                        value:''
                    }, {
                        xtype: 'datefield',
                        fieldLabel: 'Tanggal Transaksi',
                        name: 'tgl_transaksi',
                        allowBlank:false,   
                        format:'Y-m-d', 
                        id: 'evr_tgl_transaksi',                
                        anchor: '50%',
                        maxValue : new Date(),
                        value:new Date()
                    }
                    
                    ,cmb_evr_cabang
                    //                    ,{
                    //						xtype: 'checkbox',
                    //						fieldLabel: 'Entry By Master',
                    //						name: 'ismaster',          
                    //						id: 'evr_ismaster',                
                    //						anchor: '90%',
                    //						checked: false
                    //					}
                    ,{
                        xtype: 'compositefield',
                        fieldLabel: 'Master',
                        //                        msgTarget : 'side',
                        anchor    : '90%',
                        //                        defaults: {
                        //                            flex: 1
                        //                        },
                        items: [
                            {xtype: 'checkbox',
                                checked: false,
                                fieldLabel: 'Master',
                                labelSeparator: '',
                                //                                        boxLabel: 'Load Master',
                                name: 'ismaster',
                                id:'evr_ismaster'
                                ,listeners:{
                                    check:function( cb, checked){
                                        if(checked){
                                            Ext.getCmp('evr_nama_transaksi').setDisabled(false);
                                            Ext.getCmp('evr_nama_transaksi').getStore().reload();
                                                    
                                                    
                                            Ext.getCmp('evr_kd_jenis_voucher').setValue('');
                                            Ext.getCmp('evr_kd_jenis_voucher').setReadOnly(true);
                                            Ext.getCmp('eg_evr_kd_akun').setDisabled(true);
                                            Ext.getCmp('eg_evr_kd_akun').setReadOnly(true);
                                            strentryvoucher.removeAll();

                                            Ext.getCmp('evr_t_debet').setValue(0);
                                            Ext.getCmp('evr_t_kredit').setValue(0);
                                            Ext.getCmp('evr_t_selisih').setValue(0);

                                            gridentryvoucher.addBtn.setDisabled(true);
                                            //                                if(Ext.getCmp('evr_type_transaksi').disabled){
                                            //                                    Ext.getCmp('evr_type_transaksi').setDisabled(false);
                                            //                                }
                                            Ext.getCmp('evr_type_transaksi').collapse(true);  
                                            //                                                    cmb_evr
                                            //                                                    console.log('pilih');
                                        }else{
                                            Ext.getCmp('evr_kd_jenis_voucher').setValue('');
                                            Ext.getCmp('evr_kd_jenis_voucher').setReadOnly(false);
                                            Ext.getCmp('evr_nama_transaksi').setValue('');
                                            Ext.getCmp('evr_nama_transaksi').setDisabled(true);
                                            Ext.getCmp('eg_evr_kd_akun').setDisabled(false);
                                            Ext.getCmp('eg_evr_kd_akun').setReadOnly(false);
                                            strentryvoucher.removeAll();

                                            Ext.getCmp('evr_t_debet').setValue(0);
                                            Ext.getCmp('evr_t_kredit').setValue(0);
                                            Ext.getCmp('evr_t_selisih').setValue(0);

                                            gridentryvoucher.addBtn.setDisabled(false);

                                            //                                if(Ext.getCmp('evr_type_transaksi').disabled){
                                            //                                    Ext.getCmp('evr_type_transaksi').setDisabled(false);
                                            //                                }
                                            //                                   
                                            //                                    
                                            Ext.getCmp('evr_type_transaksi').collapse(true);

                                            Ext.getCmp('evr_approvallevel').setValue('approval1',false);
                                            Ext.getCmp('evr_approvallevel').setValue('approval2',false);
                                            Ext.getCmp('evr_approvallevel').setValue('approval3',false);
                                                    
                                                    
                                            //                                                    console.log('tidak pilih');
                                        }
                                                
                                    }
                                }
                                            
                            },cmb_evr
                        ]
                    }                    
                    ,cmb_evr_jv
                    ,
                    {xtype:'fieldset',
                        id:'evr_type_transaksi',
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
                                id: 'evr_diterima_oleh',                
                                anchor: '90%',                                
                                value:''
                                
                            },{
                                xtype: 'textfield',
                                fieldLabel: 'No.Giro/Cheque',
                                name: 'no_giro_cheque',
                                //                        allowBlank:false,
                                readOnly:true,                        
                                id: 'evr_no_giro_cheque',                
                                anchor: '90%',
                                style:{textTransform: "uppercase"},
                                value:''
                            }, {
                                xtype: 'datefield',
                                fieldLabel: 'Jatuh Tempo',
                                name: 'tgl_jttempo',
                                //                                allowBlank:false,   
                                format:'Y-m-d', 
                                id: 'evr_tgl_jttempo',                
                                anchor: '50%',
                                //                        minValue : new Date(Ext.getCmp('evr_tgl_transaksi').getValue()),
                                value:new Date()
                            }],listeners:{
                            collapse:function(pnl){
                                                                
                                
                                Ext.getCmp('evr_diterima_oleh').setReadOnly(true);
                                Ext.getCmp('evr_no_giro_cheque').setReadOnly(true);
                                //                                if (Ext.getCmp('evr_ismaster').collapsed){ 
                                //                                    this.setDisabled(true);    
                                //                                } else{
                                //                                    this.setDisabled(false); 
                                //                                }
                                  
                            },
                            expand:function(pnl){
                                Ext.getCmp('evr_left_layout').setDisabled(false);
                                Ext.getCmp('evr_diterima_oleh').setReadOnly(false);
                                Ext.getCmp('evr_no_giro_cheque').setReadOnly(false);
                            }
                        }
                
                        
                    }
                    
                    //                    ,cmb_evr
                    
                    //                    ,cmb_evr_cc
                ]
            }, {
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [  
                    //                {
                    //                        xtype: 'textfield',
                    //                        fieldLabel: 'Referensi',
                    //                        name: 'referensi',				
                    //                        id: 'evr_kd_transaksi',                
                    //                        anchor: '90%',
                    //                        style:{textTransform: "uppercase"},
                    //                        value: ''
                    //                    }, 
                    {
                        xtype: 'textarea',
                        fieldLabel: 'Keterangan<span class="asterix">*</span>',
                        allowBlank:false,   
                        name: 'keterangan',				
                        id: 'evr_keterangan',                
                        anchor: '90%',
                        style:{textTransform: "uppercase"},
                        value: ''
                    },
                    {xtype:'fieldset',
                        id:'evr_approval12',
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
                                id:'evr_approvallevel',
                                items: [
                                    {boxLabel: 'Level 1', name: 'approval1' ,checked:false},
                                    {boxLabel: 'Level 2', name: 'approval2',checked:false},
                                    {boxLabel: 'Level 3', name: 'approval3',checked:false}
               
                                ]}]
                        //                        ,listeners:{
                        //                            beforecollapse:function(pnl,bo){
                        //                                Ext.getCmp('evr_approvallevel').setValue('approval1',false);
                        //                                Ext.getCmp('evr_approvallevel').setValue('approval2',false);
                        //                            },
                        //                            	beforeexpand:function(pnl,bo){
                        //                                Ext.getCmp('evr_approvallevel').setValue('approval1',true);
                        //                                Ext.getCmp('evr_approvallevel').setValue('approval2',false);                                
                        //                            }
                        //                        }
                        
                    }
                    
                ]
            }]
    }
    var footerentryvoucher = {
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
                        id: 'evr_t_debet',                
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
                        id: 'evr_t_kredit',                
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
                        id: 'evr_t_selisih',                
                        anchor: '90%',
                        value: '0'
                    }]
            }]
    }
    
    //-----------------------------------------------------------------
    var str_cmb_evr_cc=createStoreArray(['kd_costcenter','nama_costcenter'],[]);
            
    var str_evr_cc=createStoreData([ 
        'kd_costcenter',
        'nama_costcenter'
    ], '<?= site_url("account_mcostcenter/get_rows_twin") ?>');
    
    var search_cc_evr=createSearchField('id_search_evr_cc', str_evr_cc, 250);
    
    var grid_evr_cc = new Ext.grid.GridPanel({        
        //id:'id_searchgrid_akun_transaksi',
        store: str_evr_cc,
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
            items: [search_cc_evr]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: str_evr_cc,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                var kdcc=null,kdakun=null;
                if (sel.length > 0) {	
                    kdcc=sel[0].get('kd_costcenter');
                    kdakun=Ext.getCmp('eg_evr_kd_akun').getValue();
                    if(Ext.getCmp('eg_evr_kd_akun').getValue()){
                        Ext.getCmp('eg_evr_costcenter').setValue(sel[0].get('kd_costcenter'));                    
                        Ext.getCmp('eg_evr_nama_costcenter').setValue(sel[0].get('nama_costcenter'));
                    }
                    
                    validateCostCenter(kdcc,kdakun,'eg_evr_costcenter','eg_evr_nama_costcenter','');
                    
                    //                    strcmbakunmonjur.reload();                    
                    //                    var kdakun=sel[0].get('kd_akun');
                    //                    Ext.getCmp('mca_akunmonjur').setValue(kdakun);
                    menu_evr_cc.hide();
                }
            }
        }
    });
    var menu_evr_cc = new Ext.menu.Menu();
    setPanelMenu(menu_evr_cc, 'Pilih CostCenter', 250, 300, grid_evr_cc, function(){
        menu_evr_cc.hide();
    }, function(){
        var sf = Ext.getCmp('id_search_evr_cc').getValue();
        if( sf != ''){
            Ext.getCmp('id_search_evr_cc').setValue('');
            Ext.getCmp('id_search_evr_cc').onTrigger2Click();
        }
    });
    
    Ext.ux.TwinComboCCevr = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            //            str_cmb_mtrx_cc.reload();
            var kdakun='';
            kdakun=Ext.getCmp('eg_evr_kd_akun').getValue();
            str_evr_cc.setBaseParam('query',kdakun);
                        str_evr_cc.load();
//            str_evr_cc.load({params:{query:kdakun}});
            menu_evr_cc.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    
    var cmb_evr_cc = new Ext.ux.TwinComboCCevr({
        fieldLabel: 'Nama Cost Center',
        id: 'eg_evr_costcenter',
        store: str_cmb_evr_cc,
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
    var strentryvoucher = new Ext.data.Store({
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
    
    function set_total_evr(){		
        var totaldebet=0;
        var totalkredit=0;
        var totalselisih=0;
                
        strentryvoucher.each(function(node){			
            totaldebet += parseInt(node.data.debet);
            totalkredit += parseInt(node.data.kredit);
        });
        totalselisih=totaldebet-totalkredit;
        Ext.getCmp('evr_t_debet').setValue(totaldebet);
        Ext.getCmp('evr_t_kredit').setValue(totalkredit);
        Ext.getCmp('evr_t_selisih').setValue(totalselisih);
        
        if (!Ext.getCmp('evr_ismaster').getValue()){
            
            Ext.getCmp('evr_approvallevel').setValue('approval1',false); 
            Ext.getCmp('evr_approvallevel').setValue('approval2',false); 
            Ext.getCmp('evr_approvallevel').setValue('approval3',false); 
            
            var st_apv1= str_limitapproval.data.items[0].data.startapv1;
            var en_apv1= str_limitapproval.data.items[0].data.endapv1;
            var st_apv2= str_limitapproval.data.items[0].data.startapv2;
            var en_apv2= str_limitapproval.data.items[0].data.endapv2;
            var st_apv3= str_limitapproval.data.items[0].data.startapv3;
            var en_apv3= str_limitapproval.data.items[0].data.endapv3;
            
            
            if (totaldebet>st_apv1 && totaldebet<=en_apv1){            
                Ext.getCmp('evr_approvallevel').setValue('approval1',true);   
            } 

            if (totaldebet>st_apv2 && totaldebet<=en_apv2){  
                Ext.getCmp('evr_approvallevel').setValue('approval1',true); 
                Ext.getCmp('evr_approvallevel').setValue('approval2',true);   
            }
            
            if (en_apv3==0 && st_apv3>0){
                if (totaldebet>st_apv3){                
                    Ext.getCmp('evr_approvallevel').setValue('approval1',true); 
                    Ext.getCmp('evr_approvallevel').setValue('approval2',true);   
                    Ext.getCmp('evr_approvallevel').setValue('approval3',true);   
                }
            }else{
                if (totaldebet>st_apv3 && totaldebet<=en_apv3){                
                    Ext.getCmp('evr_approvallevel').setValue('approval1',true); 
                    Ext.getCmp('evr_approvallevel').setValue('approval2',true);   
                    Ext.getCmp('evr_approvallevel').setValue('approval3',true);   
                }
            }            
        }        
    };
    strentryvoucher.on('update', function(){
        set_total_evr();        
		
    });
    strentryvoucher.on('remove',  function(){
        set_total_evr();
		
    });
        
    // twin master akun
    var strcb_akun_voucher = new Ext.data.ArrayStore({
        fields: ['kd_akun'],
        data : []
    });
    	
    var strgrid_akun_voucher = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_akun', 'nama','dk'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("account_entry_voucher/get_search_akun") ?>',
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
    	
    var searchgrid_akun_voucher = new Ext.app.SearchField({
        store: strgrid_akun_voucher,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgrid_akun_voucher'
    });
    	
    	
    var grid_akun_voucher = new Ext.grid.GridPanel({
            
        //id:'id_searchgrid_akun_voucher',
        store: strgrid_akun_voucher,
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
            items: [searchgrid_akun_voucher]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgrid_akun_voucher,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {			
                    Ext.getCmp('eg_evr_dkakun').setValue(sel[0].get('dk')); 
                    Ext.getCmp('eg_evr_kd_akun').setValue(sel[0].get('kd_akun'));
                    Ext.getCmp('eg_evr_nama_akun').setValue(sel[0].get('nama'));
                    
                    //                    console.log(sel[0].get('dk'));
                    if (!Ext.getCmp('evr_ismaster').getValue()){
                        if (Ext.getCmp('evr_kd_jenis_voucher').getValue()){
                            validateDKvoucher(
                            Ext.getCmp('evr_kd_jenis_voucher').getValue(),
                            sel[0].get('kd_akun'),
                            'eg_evr_dktransaksi','');
                        }
                        var kdakun=Ext.getCmp('eg_evr_kd_akun').getValue();
                        var kdcc=Ext.getCmp('eg_evr_costcenter').getValue();                        
//                        str_evr_cc.load({params:{query:kdakun}});
                        str_evr_cc.setBaseParam('query',kdakun);
                        str_evr_cc.load();
                        validateCostCenter(kdcc,kdakun,'eg_evr_costcenter','eg_evr_nama_costcenter','');
                    }
                    
                    
                    menu_akun_voucher.hide();
                    Ext.getCmp('eg_evr_debet').focus();
                }
            }
        }
    });
    	
    var menu_akun_voucher = new Ext.menu.Menu();
    menu_akun_voucher.add(new Ext.Panel({
        title: 'Pilih Akun',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [grid_akun_voucher],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menu_akun_voucher.hide();
                }
            }]
    }));
        
    Ext.ux.TwinComboAkunVoucher = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgrid_akun_voucher.load();
            menu_akun_voucher.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    	
    menu_akun_voucher.on('hide', function(){
        var sf = Ext.getCmp('id_searchgrid_akun_voucher').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgrid_akun_voucher').setValue('');
            searchgrid_akun_voucher.onTrigger2Click();
        }
    });
	
    var cbakun_voucher = new Ext.ux.TwinComboAkunVoucher({
        fieldLabel: 'Supplier <span class="asterix">*</span>',
        id: 'id_cb_akun_voucher',
        store: strcb_akun_voucher,
        mode: 'local',
        valueField: 'nama',
        displayField: 'nama',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'nama',
        emptyText: 'Pilih Akun'
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
    var editorentryvoucher = new Ext.ux.grid.RowEditor({
        saveText: 'Update',
        monitorValid:true,tooltip :''        
        ,listeners:{
            afteredit:function(roweditor,nrec,rec,rowi){
//                console.log(str_evr_cc.getCount());
//                console.log(nrec);                
//                console.log(rec);
                if (!Ext.getCmp('evr_ismaster').getValue()){
                    if(str_evr_cc.getCount()>0){
                        if(!rec.data.costcenter || rec.data.costcenter===""){                       
                            set_message(1, 'Cost Center Tidak Valid', function(btn){
                                if (btn == 'ok'){                                    //                                console.log(rec.data);
                                    Ext.getCmp('id_gridentryvoucher').getStore().remove(rec);
                                }

                            });
                        }
                    }
                }
            }
        }
    });
    
	
    var gridentryvoucher = new Ext.grid.GridPanel({
        id:'id_gridentryvoucher',
        store: strentryvoucher,
        stripeRows: true,
        //        autoHeight:true,
        height: 220,
        frame: true,
        border:true,
        plugins: [editorentryvoucher],       
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
                editor: new Ext.ux.TwinComboAkunVoucher({
                    readOnly:true,
                    id: 'eg_evr_kd_akun',
                    store: strcb_akun_voucher,
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
                    id: 'eg_evr_nama_akun',
                    disabled :true
                })
            },{
                header: 'D/K Default',
                dataIndex: 'dk_akun',                
                width: 60,
                hidden:true,
                //                hiddenName: 'dk_akun',
                editor: new Ext.form.TextField({                
                    id: 'eg_evr_dkakun'
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
                    id: 'eg_evr_dktransaksi',
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
                    id: 'eg_evr_debet',
                    allowBlank: false
                    ,
                    listeners:{
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                var dk = Ext.getCmp('eg_evr_dktransaksi').getValue();  
                                
                                if(Ext.getCmp('eg_evr_kd_akun').getValue()===''){
                                    Ext.getCmp('eg_evr_debet').setValue(0);
                                }
                                if (dk==='K'){
                                    Ext.getCmp('eg_evr_debet').setValue(0);
                                    
                                }
                                if(Ext.getCmp('eg_evr_kredit').getValue>0){
                                    Ext.getCmp('eg_evr_debet').setValue(0);
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
                    id: 'eg_evr_kredit',
                    allowBlank: false,
                    listeners:{
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                var dk = Ext.getCmp('eg_evr_dktransaksi').getValue();                    						
                                
                                if(Ext.getCmp('eg_evr_kd_akun').getValue()===''){
                                    Ext.getCmp('eg_evr_kredit').setValue(0);
                                }
                                
                                if (dk==='D'){
                                    Ext.getCmp('eg_evr_kredit').setValue(0);
                                    
                                }
                                //                                console.log(Ext.getCmp('eg_evr_debet').getValue());
                                if(Ext.getCmp('eg_evr_debet').getValue()>0){
                                    Ext.getCmp('eg_evr_kredit').setValue(0);
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
                    id: 'eg_evr_ref_detail',                    
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
                editor:cmb_evr_cc
                //                editor: new Ext.form.TextField({                                    
                //                    id: 'eg_evr_costcenter',
                //                    allowBlank: false
                //                })
            },{
                header: 'Nama CostCenter',
                dataIndex: 'nama_costcenter',
                width: 200
                ,editor: new Ext.form.TextField({                                    
                    id: 'eg_evr_nama_costcenter',                    
                    readOnly:true
                })
            },{
                header: 'Keterangan Detail',
                dataIndex: 'keterangan_detail',
                width: 200,
                editor: new Ext.form.TextField({                                    
                    id: 'eg_evr_ketdetail',                    
                    style:{textTransform: "uppercase"}
                    ,allowBlank: false
                }),
                renderer:function(value, metaData, record, rowIndex, colIndex, store) {
                    return Ext.util.Format.uppercase(value);
                }
            }]
        ,listeners:{
            cellclick:function(grid, rowIndex, columnIndex, e){
//                editorentryvoucher.setErrorSummary(false);
                if (Ext.getCmp('evr_ismaster').getValue()){
                    var rec = grid.getStore().getAt(rowIndex); 
                    var data=rec.get('costcenter');
                    //                    if(data == '-'){
                    Ext.getCmp('eg_evr_costcenter').setDisabled(true);  
                    Ext.getCmp('eg_evr_costcenter').setReadOnly(true); 
                    //                    }else{
                    //                        Ext.getCmp('eg_evr_costcenter').setDisabled(false);      
                    //                    }
                    data=rec.get('dk_transaksi');        
                    if(data == 'D'){
                        Ext.getCmp('eg_evr_kredit').setDisabled(true);  
                        Ext.getCmp('eg_evr_debet').setDisabled(false);  
          
                    }else if(data == 'K'){
                        Ext.getCmp('eg_evr_debet').setDisabled(true);  
                        Ext.getCmp('eg_evr_kredit').setDisabled(false);  
          
                    }
                }
                
            }
        },
        tbar:[{
                ref: '../addBtn',
                icon: BASE_ICONS + 'add.png',
                text: 'Add',
                handler : function(){
                   
                    // access the Record constructor through the grid's store
                    if (!Ext.getCmp('evr_kd_jenis_voucher').getValue()){
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Silahkan Isi Jenis Voucher terlebih dulu',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK			               
                        });
                        return;
                    }
                    Ext.getCmp('eg_evr_kd_akun').setDisabled(false);
                    Ext.getCmp('eg_evr_kd_akun').setReadOnly(false);
                     
                    Ext.getCmp('eg_evr_debet').setDisabled(false);
                    Ext.getCmp('eg_evr_debet').setReadOnly(false);
                     
                    Ext.getCmp('eg_evr_kredit').setDisabled(false);
                    Ext.getCmp('eg_evr_kredit').setReadOnly(false);
                     
                    Ext.getCmp('eg_evr_costcenter').setDisabled(false);
                    Ext.getCmp('eg_evr_costcenter').setReadOnly(false);
                     
                    var Plant = gridentryvoucher.getStore().recordType;
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
                    editorentryvoucher.stopEditing();
                    strentryvoucher.insert(0, rowentryvoucher);
                    gridentryvoucher.getView().refresh();
                    gridentryvoucher.getSelectionModel().selectRow(0);
                    editorentryvoucher.startEditing(0);
                
                  
                }
            },{
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                disabled: true,
                handler: function(){
                    editorentryvoucher.stopEditing();
                    var s = gridentryvoucher.getSelectionModel().getSelections();
                    for(var i = 0, r; r = s[i]; i++){
                        strentryvoucher.remove(r);
                    }
                }
            }]
    });
  
    gridentryvoucher.getSelectionModel().on('selectionchange', function(sm){
        if (!Ext.getCmp('evr_ismaster').getValue()){
            gridentryvoucher.removeBtn.setDisabled(sm.getCount() < 1);
        }else{
            gridentryvoucher.removeBtn.setDisabled(true);
        }
    });
    
    
    var entryvaoucher_form = new Ext.FormPanel({
        id: 'entryvoucher',
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
                },                  
                items: [headerentryvoucher]
            }
            ,
            gridentryvoucher
            ,{
                bodyStyle: {
                    margin: '5px 0px 15px 0px'
                },                  
                items: [footerentryvoucher]
            }
        ],
        buttons: [{
                text: 'Save',
                formBind: true,
                handler: function(){       
                    if(strentryvoucher.getCount()==0){
                        set_message(1,'Akun Transaksi Kosong!!!',null);
                        return;
                    }
                    if(Ext.getCmp('evr_t_selisih').getValue()!='0'){
                        set_message(1, 'Masih ada Selisih Total Debet dan Total Kredit', null);                        	
                        return;
                    }
                
                    if(Ext.getCmp('evr_t_debet').getValue()=='0' && Ext.getCmp('evr_t_kredit').getValue()=='0'){
                        set_message(1, 'Total Debet dan Total Kredit Masih 0', null);                            
                        return;
                    }
                    var validasi_jvakun = 0;
                    var validasi_ketdetail=0;
                    var detailevr = new Array();		
//                    console.log(strentryvoucher);
                    strentryvoucher.each(function(node){
                        if(node.data.dk_transaksi==''){
                            if(node.data.debet>0){
                                node.data.dk_transaksi='D';
                            }else{
                                node.data.dk_transaksi='K';
                            }
                            
                        }
                        if (!node.data.keterangan_detail || node.data.keterangan_detail===''){
                            validasi_ketdetail=1;
                        }
                        if (!Ext.getCmp('evr_ismaster').getValue()){  
//                                console.log('sampai sini');
                                if(str_evr_jenisvoucher_akun.getCount()> 0){
                                    str_evr_jenisvoucher_akun.each(function(nd){
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
                    if(str_evr_jenisvoucher_akun.getCount()> 0){
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
                                if(autopos){
                                    autopost='on';
                                }                              
                                Ext.getCmp('entryvoucher').getForm().submit({
                                    url: '<?= site_url("account_entry_voucher/update_row") ?>',
                                    scope: this,
                                    params: {
                                        data: Ext.util.JSON.encode(detailevr),
                                        type_transaksi:type_transaksi,
                                        autopost:autopost
                                    },
                                    waitMsg: 'Saving Data...',
                                    success: function(form, action){
//                                        console.log(action.result.errMsg);
                                        var ms='Form submitted successfully';
                                        if(action.result.errMsg){
                                           ms=action.result.errMsg;
                                        }
                                        Ext.Msg.show({
                                            title: 'Success',
                                            msg: ms,
                                            modal: true,
                                            icon: Ext.Msg.INFO,
                                            buttons: Ext.Msg.OK
                                        });			            
                                        clearEvr();				
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
                    });                 
                    
                    
                }
            },{
                text: 'Reset',
                handler: function(){
                    clearEvr();
                }
                
            }],
        listeners:{
            afterrender:function(){                
                this.getForm().load({
                    url: '<?= site_url("account_entry_voucher/get_form") ?>',
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
                strevrakuntrx.reload();
                strevrcc.reload();
                strevrcabang.reload();
                str_limitapproval.reload();
                str_evr_jenisvoucher.reload();
                gridentryvoucher.addBtn.setDisabled(true);
                
            }
            ,
            show:function(){
                //            clearEvr();
                //                strevrakuntrx.reload();
                strevrcc.reload();
                //                strevrcabang.reload();
                //                str_limitapproval.reload();
                //                reload
                //                gridentryvoucher.addBtn.setDisabled(true);
            }
            
        }
        
    });
    
   
    //    var tab_mas={ title:'Entry Voucher Master',
    //                layout:'fit',
    //                items:[entryvaoucher_form]};
    //    var tab_man={ title:'Entry Voucher Manual',
    //                layout:'fit',
    //                items:[catatjurnal_form]};
    //                
    //   var formentryvaoucher=  new Ext.TabPanel({   
    //      id: 'entryvoucher',
    //      title: 'Entry Voucher',
    //      activeTab: 0,
    //      items:[tab_mas,tab_man]
    //
    //  }); 
    function clearEvr(){
        Ext.getCmp('entryvoucher').getForm().reset();
        Ext.getCmp('entryvoucher').getForm().load({
            url: '<?= site_url("account_entry_voucher/get_form") ?>',
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
        Ext.getCmp('evr_type_transaksi').collapse(true); 
        Ext.getCmp('evr_ismaster').setValue(false); 
        
        str_evr_jenisvoucher.load();
        strevrakuntrx.load();
        strentryvoucher.removeAll();
        str_limitapproval.load();
        //        console.log(str_limitapproval.data);
        //        console.log(str_limitapproval.data.items[0]);
    }
</script>


