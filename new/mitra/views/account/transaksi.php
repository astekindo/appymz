<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">    
    /* START FORM */
    // twin master akun
    
    var strtransaksi = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [ 
                'kd_transaksi',
                'nama_transaksi',
                'type_transaksi',
                {name:'approval1',type:'bool'},
                {name:'approval2',type:'bool'},
                {name:'approval3',type:'bool'},               
                'limit_apv1',
                'end_limit_apv1',
                'limit_apv2',
                'end_limit_apv2',
                'costcenter',                
                'limit_apv3',
                'end_limit_apv3',
                'kd_jenis_voucher',
                'title'
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
    
    var strcb_akun_transaksi = new Ext.data.ArrayStore({
        fields: ['kd_akun'],
        data : []
    });
	
    var strgrid_akun_transaksi = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_akun', 'nama','dk'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("account_master_account/get_akun_twin") ?>',
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
	
    var searchgrid_akun_transaksi = new Ext.app.SearchField({
        store: strgrid_akun_transaksi,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgrid_akun_transaksi'
    });
	
	
    var grid_akun_transaksi = new Ext.grid.GridPanel({
        
        //id:'id_searchgrid_akun_transaksi',
        store: strgrid_akun_transaksi,
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
            items: [searchgrid_akun_transaksi]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgrid_akun_transaksi,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                    var kdakun=sel[0].get('kd_akun');
                    var kdvouch=Ext.getCmp('id_trx_kd_jenis_voucher').getValue();
                    var kdcc=Ext.getCmp('id_mtrx_cc').getValue();
                    Ext.getCmp('etrans_kd_akun').setValue(sel[0].get('kd_akun'));
                    Ext.getCmp('etrans_nama_akun').setValue(sel[0].get('nama'));
                    Ext.getCmp('etrans_dk_akun').setValue(sel[0].get('dk'));  
                    
//                    str_mtrx_cc.load({params:{query:kdakun}});
                    str_mtrx_cc.setBaseParam('query',kdakun);
                    str_mtrx_cc.load();
                    validateCostCenter(kdcc,kdakun,'id_mtrx_cc','etrans_nama_costcenter','');
                    validateDKvoucher(kdvouch,kdakun,'etrans_dk_transaksi','');
                    //                   var valDKv=ret_DKvoucher.trim();
                    //                   console.log(valDKv);
                    //                   if (valDKv=='d' || valDKv=='k'){
                    //                       
                    //                       Ext.getCmp('etrans_dk_transaksi').setValue(valDKv.toUpperCase()); 
                    //                   }else{
                    //                       Ext.getCmp('etrans_dk_transaksi').setValue(''); 
                    //                   }
                    menu_akun_transaksi.hide();
                }
            }
        }
    });
	
    var menu_akun_transaksi = new Ext.menu.Menu();
    menu_akun_transaksi.add(new Ext.Panel({
        title: 'Pilih Akun',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [grid_akun_transaksi],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menu_akun_transaksi.hide();
                }
            }]
    }));
    
    Ext.ux.TwinComboAkuntransaksi = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgrid_akun_transaksi.load();
            menu_akun_transaksi.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menu_akun_transaksi.on('hide', function(){
        var sf = Ext.getCmp('id_searchgrid_akun_transaksi').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgrid_akun_transaksi').setValue('');
            searchgrid_akun_transaksi.onTrigger2Click();
        }
    });
	
  
    //==============
    var streditakuntransaksi = new Ext.data.Store({
        autoSave:false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_akun',  type: 'string'},
                {name: 'nama',  type: 'string'},
                {name: 'dk_akun',  type: 'string'},                           
                {name: 'dk_transaksi', type: 'string'},
                {name: 'kd_costcenter', type: 'string'},
                {name: 'nama_costcenter', type: 'string'}
                
                
                            
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("transaksi/get_rows_akun") ?>',
            method: 'POST'
        }),
        writer: new Ext.data.JsonWriter(
        {
            encode: true,
            writeAllFields: true
        }),listeners: {
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
    
    var dkstore = createStoreArray(['nama','nilai'],[['Debet','D'],['Kredit','K']]);
    var dkstoregrid= createStoreArray(['nama','nilai'],[['Debet','D'],['Kredit','K']]);
    //    var dkstoregrid = createStoreData(['nama','nilai'],'transaksi/get_dk');
    var grid_dk_transaksi = new Ext.grid.GridPanel({        
        //id:'id_searchgrid_akun_transaksi',
        store: dkstoregrid,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'Description',
                dataIndex: 'nama',
                width: 80,
                sortable: true			
            
            },{
                header: 'Value',
                dataIndex: 'nilai',
                width: 50,
                sortable: true         
            }]
        ,
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    var kdakun=Ext.getCmp('etrans_kd_akun').getValue();
                    var kdvouch=Ext.getCmp('id_trx_kd_jenis_voucher').getValue();
                    Ext.getCmp('etrans_dk_transaksi').setValue(sel[0].get('nilai'));    
                    validateDKvoucher(kdvouch,kdakun,'etrans_dk_transaksi',sel[0].get('nilai'));
                    
                    menudk.hide();
                }
            }
        }
    });
    var menudk=new Ext.menu.Menu();
    setPanelMenu(menudk,'Pilih Debet / Kredit',200,200,grid_dk_transaksi,function(){
        menudk.hide();
    },function(){}
);
    Ext.ux.TwinComboDKTrans=Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: 
            function(){
            //load store grid
            //            dkstoregrid.load();
            menudk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
   
    var cmb_dktrans= new Ext.ux.TwinComboDKTrans({
        id: 'etrans_dk_transaksi',
        store: dkstore,
        mode: 'local',
        valueField: 'nilai',
        displayField: 'nama',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false ,
        editable: false,
        hiddenName: 'dk_transaksi',
        emptyText: 'Pilih Debet/Kredit'
				
    });	
    
    var str_cmb_mtrx_cc=createStoreArray(['kd_costcenter','nama_costcenter'],[]);
            
    var str_mtrx_cc=createStoreData([ 
        'kd_costcenter',
        'nama_costcenter'
    ], '<?= site_url("account_mcostcenter/get_rows_twin") ?>');
    
    var search_cc_mtrx=createSearchField('id_search_mtrx_cc', str_mtrx_cc, 250);
    
    var grid_mtrx_cc = new Ext.grid.GridPanel({        
        //id:'id_searchgrid_akun_transaksi',
        store: str_mtrx_cc,
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
            items: [search_cc_mtrx]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: str_mtrx_cc,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                var kdcc=null,kdakun=null;
                if (sel.length > 0) {	
                    kdcc=sel[0].get('kd_costcenter');
                    kdakun=Ext.getCmp('etrans_kd_akun').getValue();
                    if(Ext.getCmp('etrans_kd_akun').getValue()){
                        Ext.getCmp('id_mtrx_cc').setValue(sel[0].get('kd_costcenter'));                    
                        Ext.getCmp('etrans_nama_costcenter').setValue(sel[0].get('nama_costcenter'));
                    }
                    
                    validateCostCenter(kdcc,kdakun,'id_mtrx_cc','etrans_nama_costcenter','');
                    
                    //                    strcmbakunmonjur.reload();                    
                    //                    var kdakun=sel[0].get('kd_akun');
                    //                    Ext.getCmp('mca_akunmonjur').setValue(kdakun);
                    menu_mtrx_cc.hide();
                }
            }
        }
    });
    var menu_mtrx_cc = new Ext.menu.Menu();
    setPanelMenu(menu_mtrx_cc, 'Pilih CostCenter', 250, 300, grid_mtrx_cc, function(){
        menu_mtrx_cc.hide();
    }, function(){
        var sf = Ext.getCmp('id_search_mtrx_cc').getValue();
        if( sf != ''){
            Ext.getCmp('id_search_mtrx_cc').setValue('');
            Ext.getCmp('id_search_mtrx_cc').onTrigger2Click();
        }
    });
    
    Ext.ux.TwinComboCCtrx = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            //            str_cmb_mtrx_cc.reload();
            var kdakun='';
            kdakun=Ext.getCmp('etrans_kd_akun').getValue();
//            str_mtrx_cc.load({params:{query:kdakun}});
            str_mtrx_cc.setBaseParam('query',kdakun);
                    str_mtrx_cc.load();
            menu_mtrx_cc.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    
    var cmb_mtrx_cc = new Ext.ux.TwinComboCCtrx({
        fieldLabel: 'Nama Cost Center',
        id: 'id_mtrx_cc',
        store: str_cmb_mtrx_cc,
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
    
    var editorgridtransaksiakun= new Ext.ux.grid.RowEditor({
        saveText: 'Update',
        listeners:{
            afteredit:function(roweditor,nrec,rec,rowi){
                console.log(rec.data);
                if(str_mtrx_cc.getCount()>0){
                    if(!rec.data.kd_costcenter || rec.data.kd_costcenter===""){                       
                        set_message(1, 'Cost Center Tidak Valid', function(btn){
                            if (btn == 'ok'){
                                //                                console.log(rec.data);
                                Ext.getCmp('grid_trx_akun').getStore().remove(rec);
                            }
                            
                        });
                    }
                }
            }
        }
    });
    
    //str_mtrx_cc.load({params:{query:kdakun}});                
    //                    if(str_mtrx_cc.getCount()>0){
    //                        Ext.getCmp('id_mtrx_cc').allowBlank(false);
    //                    }
    var gridtransaksiakun = new Ext.grid.GridPanel({
        id:'grid_trx_akun',
        store: streditakuntransaksi,
        stripeRows: true,
        height: 250,
        frame: true,
        border:true,
        plugins: [editorgridtransaksiakun]
        ,
        //        autoExpandColumn:'nama',
        columns: [{
                //            xtype: 'numbercolumn',
                header: 'Kode Akun',
                dataIndex: 'kd_akun',
                width: 80,
                format: '0',
                sortable: true,	
                editor: new Ext.ux.TwinComboAkuntransaksi({
                    id: 'etrans_kd_akun',
                    store: strcb_akun_transaksi,
                    mode: 'local',
                    valueField: 'kd_akun',
                    displayField: 'kd_akun',
                    typeAhead: true,
                    triggerAction: 'all',
                    allowBlank: false ,
                    editable: false,
                    hiddenName: 'kd_akun',
                    emptyText: 'Pilih Akun'
				
                })		
			
            },{
                header: 'Nama Akun',
                dataIndex: 'nama',
                width: 300,
                editor: new Ext.form.TextField({                
                    readOnly: true,
                    id: 'etrans_nama_akun'
                })
            },{
                hidden:true,
                header: 'D/K Akun',
                dataIndex: 'dk_akun',            
                width: 60,
                editor: new Ext.form.TextField({                
                    readOnly: true,
                    id: 'etrans_dk_akun'
                })            
            },{
                header: 'Debet Kredit',
                dataIndex: 'dk_transaksi',            
                width: 80,
                editor:cmb_dktrans
            },{
                header: 'Kode CostCenter',
                dataIndex: 'kd_costcenter',            
                width: 80,
                editor:cmb_mtrx_cc
            },{
                header: 'Nama CostCenter',
                dataIndex: 'nama_costcenter',
                width: 200,
                editor: new Ext.form.TextField({                
                    readOnly: true,
                    id: 'etrans_nama_costcenter'
                })
            }
            
        ],
        tbar: [{
                icon: BASE_ICONS + 'add.png',
                text: 'Add',
                handler: function(){
                    if(Ext.getCmp('id_nama_transaksi').getValue() == ''){
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Silahkan nama transaksi terlebih dulu',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK			               
                        });
                        return;
                    }
                    
                    var plant=gridtransaksiakun.store.recordType;
                    var rowentryakunedit = new plant({
                        kd_akun : '',
                        nama:'',
                        dk_akun: '',                        
                        dk_transaksi:'',
                        kd_costcenter:'',
                        nama_costcenter:''
                    });                
                    editorgridtransaksiakun.stopEditing();
                    streditakuntransaksi.insert(0, rowentryakunedit);
                    gridtransaksiakun.getView().refresh();
                    gridtransaksiakun.getSelectionModel().selectRow(0);
                    editorgridtransaksiakun.startEditing(0);
                }
            },{
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                disabled: true,
                handler: function(){
                    editorgridtransaksiakun.stopEditing();
                    var s = gridtransaksiakun.getSelectionModel().getSelections();
                    for(var i = 0, r; r = s[i]; i++){
                        streditakuntransaksi.remove(r);
                    }
                }
            }]
    });
    gridtransaksiakun.getSelectionModel().on('selectionchange', function(sm){
        gridtransaksiakun.removeBtn.setDisabled(sm.getCount() < 1);	 });
    
    
    var str_trx_jenisvoucher = new Ext.data.Store({        
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_jenis_voucher', allowBlank: false, type: 'string'},
                {name: 'title', allowBlank: false, type: 'string'},
                {name: 'dk',type: 'string'}
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("transaksi/get_rows_jenisvoucher") ?>',
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
    
    var str_trx_jenisvoucher_akun = new Ext.data.Store({        
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_jenis_voucher', allowBlank: false, type: 'string'},
                {name: 'kd_akun', allowBlank: false, type: 'string'},         
                {name: 'nama', allowBlank: false, type: 'string'},
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("transaksi/get_rows_jenisvoucher_akun") ?>',
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
    var pub_vdkvoucher='';
    var cmb_jv_trx= new Ext.form.ComboBox({
        fieldLabel:		'Jenis Voucher  <span class="asterix">*</span>',
        mode:           'local',
        value:          '',
        triggerAction:  'all',
        forceSelection: true,
        editable:       false,
        name:           'kd_jenis_voucher',
        id:           	'id_trx_kd_jenis_voucher',
        hiddenName:     'kd_jenis_voucher',
        displayField:   'title',
        valueField:     'kd_jenis_voucher',
        anchor:			'50%',
        store:  str_trx_jenisvoucher,
        allowBlank:false,
        listeners:{
            select: function(combo, records) {                
                var vdkvoucher = records.get('dk'); 
                pub_vdkvoucher=vdkvoucher.trim().toUpperCase();                
                if (vdkvoucher.trim() == 'd'){
                    Ext.getCmp('id_type_cash_flow').setValue('Cash In');
                }
                if (vdkvoucher.trim() == 'k'){
                    Ext.getCmp('id_type_cash_flow').setValue('Cash Out');
                }
                var kdv=combo.getValue();
                str_trx_jenisvoucher_akun.reload({params:{query:kdv}});
            }
        }
    });
    
    
    
    Ext.ns('transaksiform');
    transaksiform.Form = Ext.extend(Ext.form.FormPanel, {
    
        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 120,
        url: '<?= site_url("transaksi/update_row") ?>',
        constructor: function(config){
            config = config || {};
            config.listeners = config.listeners || {};
            Ext.applyIf(config.listeners, {
                actioncomplete: function(){
                    //if (console && console.log) {
                    //    console.log('actioncomplete:', arguments);
                    //}
                },
                actionfailed: function(){
                    //if (console && console.log) {
                    //    console.log('actionfailed:', arguments);
                    //}
                }
            });
            transaksiform.Form.superclass.constructor.call(this, config);
        },
        initComponent: function(){
        
            // hard coded - cannot be changed from outsid
            var config = {
                defaultType: 'textfield',
                defaults: { labelSeparator: ''},
                monitorValid: true,
                autoScroll: false // ,buttonAlign:'right'
                ,
                items: [{
                        xtype: 'hidden',
                        name: 'kd_transaksi',
                        id: 'id_kd_transaksi'
                    },{
                        xtype: 'textfield',
                        fieldLabel: 'Nama Transaksi <span class="asterix">*</span>',
                        name: 'nama_transaksi',
                        allowBlank: false,
                        id: 'id_nama_transaksi',
                        maxLength: 40,
                        anchor: '50%'                
                    },cmb_jv_trx
                    ,{
                        xtype:          'combo',
                        fieldLabel:		'Type Transaksi',
                        //                        fieldLabel:		'Type Transaksi <span class="asterix">*</span>',
                        mode:           'local', 
                        value:          '',
                        triggerAction:  'all',
                        forceSelection: true,
                        editable:       false,
                        name:           'type_transaksi',
                        id:           	'id_type_cash_flow',
                        hiddenName:     'type_transaksi',
                        displayField:   'name',
                        valueField:     'value',
                        anchor:			'50%',
                        store:          new Ext.data.JsonStore({
                            fields : ['name', 'value'],
                            data   : [
                                {name : 'Cash In', value: 'Cash In'},
                                {name : 'Cash Out', value: 'Cash Out'}                                                            
                            ]
                        })
                        //                        ,allowBlank: false
                                                        
                    }                    
//                    ,{
//                        xtype: 'checkbox',
//                        fieldLabel: 'Cost Center',
//                        name: 'costcenter',          
//                        id: 'id_trx_costcenter',                
//                        anchor: '50%',
//                        checked: false
//                    }
                    ,{     xtype:'fieldset',
                        id:'approval_group',        
                        title: 'Approval',
                        autoHeight:true,        
                        layout: 'column',
                        items :[{columnWidth: .33,
                                layout: 'form',
                                border: false,
                                labelWidth: 100,
                                defaults: { labelSeparator: ''},
                                items:[{
                                        xtype: 'checkbox',
                                        fieldLabel: 'Approval 1',
                                        name: 'approval1',          
                                        id: 'id_trx_approval1',                
                                        anchor: '90%',
                                        checked: false,
                                        listeners:{
                                            check : function(scope ,checked ){
                                                if(checked){
                                                    var startapv1=str_limitapproval.data.items[0].data.startapv1;
                                                    var endapv1=str_limitapproval.data.items[0].data.endapv1;
                                                    //                                                    console.log(startapv1);
                                                    
                                                    Ext.getCmp('id_trx_limit_apv1').setValue(startapv1);
                                                    Ext.getCmp('id_trx_limit_apv1').setDisabled(false);
                                                    Ext.getCmp('id_trx_end_limit_apv1').setValue(endapv1);
                                                    Ext.getCmp('id_trx_end_limit_apv1').setDisabled(false);
                                                }else{
                                                    Ext.getCmp('id_trx_limit_apv1').setValue('0');
                                                    Ext.getCmp('id_trx_limit_apv1').setDisabled(true);
                                                    Ext.getCmp('id_trx_end_limit_apv1').setValue('0');
                                                    Ext.getCmp('id_trx_end_limit_apv1').setDisabled(true);
                                                }
                                            }
                                        }
                                    },
                                    {xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'Limit Approval1 <span class="asterix">*</span>',
                                        name: 'limit_apv1',					
                                        id: 'id_trx_limit_apv1',										
                                        anchor: '90%',	
                                        fieldClass:'number',	
                                        allowBlank: false,
                                        value:'0'}
                                    ,
                                    {xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'End Limit Approval1 <span class="asterix">*</span>',
                                        name: 'end_limit_apv1',					
                                        id: 'id_trx_end_limit_apv1',										
                                        anchor: '90%',	
                                        fieldClass:'number',	
                                        allowBlank: false,
                                        value:'0'}                                    
                                ]},
                            {columnWidth: .33,
                                layout: 'form',
                                border: false,
                                labelWidth: 100,
                                defaults: { labelSeparator: ''},
                                items:[{
                                        xtype: 'checkbox',
                                        fieldLabel: 'Approval 2',
                                        name: 'approval2',          
                                        id: 'id_trx_approval2',                
                                        anchor: '90%',
                                        checked: false,
                                        listeners:{
                                            check : function(scope ,checked ){
                                                if(checked){
                                                    var startapv2=str_limitapproval.data.items[0].data.startapv2;
                                                    var endapv2=str_limitapproval.data.items[0].data.endapv2;
                                                    
                                                    Ext.getCmp('id_trx_limit_apv2').setValue(startapv2);
                                                    Ext.getCmp('id_trx_limit_apv2').setDisabled(false);
                                                    Ext.getCmp('id_trx_end_limit_apv2').setValue(endapv2);
                                                    Ext.getCmp('id_trx_end_limit_apv2').setDisabled(false);
                                                }else{
                                                    Ext.getCmp('id_trx_limit_apv2').setValue('0');
                                                    Ext.getCmp('id_trx_limit_apv2').setDisabled(true);
                                                    Ext.getCmp('id_trx_end_limit_apv2').setValue('0');
                                                    Ext.getCmp('id_trx_end_limit_apv2').setDisabled(true);
                                                }
                                            }
                                        }
                                    },
                                    {xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'Limit Approval2 <span class="asterix">*</span>',
                                        name: 'limit_apv2',					
                                        id: 'id_trx_limit_apv2',										
                                        anchor: '90%',	
                                        fieldClass:'number',	
                                        allowBlank: false,
                                        value:'0'}
                                    ,
                                    {xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'End Limit Approval2 <span class="asterix">*</span>',
                                        name: 'end_limit_apv2',					
                                        id: 'id_trx_end_limit_apv2',										
                                        anchor: '90%',	
                                        fieldClass:'number',	
                                        allowBlank: false,
                                        value:'0'}                                   
                                ]},
                            {columnWidth: .33,
                                layout: 'form',
                                border: false,
                                labelWidth: 100,
                                defaults: { labelSeparator: ''},
                                items:[{
                                        xtype: 'checkbox',
                                        fieldLabel: 'Approval 3',
                                        name: 'approval3',          
                                        id: 'id_trx_approval3',                
                                        anchor: '90%',
                                        checked: false,
                                        listeners:{
                                            check : function(scope ,checked ){
                                                if(checked){
                                                    var startapv3=str_limitapproval.data.items[0].data.startapv3;
                                                    var endapv3=str_limitapproval.data.items[0].data.endapv3;
                                                    
                                                    Ext.getCmp('id_trx_limit_apv3').setValue(startapv3);
                                                    Ext.getCmp('id_trx_limit_apv3').setDisabled(false);
                                                    Ext.getCmp('id_trx_end_limit_apv3').setValue(endapv3);
                                                    Ext.getCmp('id_trx_end_limit_apv3').setDisabled(false);
                                                }else{
                                                    Ext.getCmp('id_trx_limit_apv3').setValue('0');
                                                    Ext.getCmp('id_trx_limit_apv3').setDisabled(true);
                                                    Ext.getCmp('id_trx_end_limit_apv3').setValue('0');
                                                    Ext.getCmp('id_trx_end_limit_apv3').setDisabled(true);
                                                }
                                            }
                                        }
                                    },
                                    {xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'Limit Approval3 <span class="asterix">*</span>',
                                        name: 'limit_apv3',					
                                        id: 'id_trx_limit_apv3',										
                                        anchor: '90%',	
                                        fieldClass:'number',	
                                        allowBlank: false,
                                        value:'0'}
                                    ,
                                    {xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'End Limit Approval3 <span class="asterix">*</span>',
                                        name: 'end_limit_apv3',					
                                        id: 'id_trx_end_limit_apv3',										
                                        anchor: '90%',	
                                        fieldClass:'number',	
                                        allowBlank: false,
                                        value:'0'}                                   
                                ]}
            
                        ]},
                    gridtransaksiakun],
                buttons: [{
                        text: 'Submit',
                        id: 'btnsubmittransaksi',
                        formBind: true,
                        scope: this,
                        handler: this.submit
                    }, {
                        text: 'Reset',
                        id: 'btnresettransaksi',
                        scope: this,
                        handler: this.reset
                    }, {
                        text: 'Close',
                        id: 'btnClose',
                        scope: this,
                        handler: function(){
                            winaddtransaksi.hide();
                        }
                    }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            
            // call parent
            transaksiform.Form.superclass.initComponent.apply(this, arguments);
            
        } // eo function initComponent	
        ,
        onRender: function(){
        
            // call parent
            transaksiform.Form.superclass.onRender.apply(this, arguments);
            
            // set wait message target
            this.getForm().waitMsgTarget = this.getEl();
            
            // loads form after initial layout
            // this.on('afterlayout', this.onLoadClick, this, {single:true});
        
        } // eo function onRender
        ,
        reset: function(){
            //            Ext.getCmp('id_nama_transaksi').setReadOnly(false);
            this.getForm().reset();
            streditakuntransaksi.load();
        },
        submit: function(){
            var arr_akuntrx= new Array();            
            var validasi_jvakun = 0;
            var validasi_d = 0;
            var validasi_k = 0;
            var validasi_balance = 0;
            if (streditakuntransaksi.getCount()==0){
                set_message(1,'Akun Transaksi Kosong!!!',null);
                return;
            }
            streditakuntransaksi.each(function(node){ 
                //                console.log('ampe sini');
                if(str_trx_jenisvoucher_akun.getCount()> 0){
                    //                    console.log('jumlah akun ' + str_trx_jenisvoucher_akun.getCount());
                    str_trx_jenisvoucher_akun.each(function(nd){
                        //                    console.log(nd.data.kd_akun+' '+node.data.kd_akun+'-');
                        if(nd.data.kd_akun==node.data.kd_akun){
                            if(pub_vdkvoucher!=''){
                                if(pub_vdkvoucher==node.data.dk_transaksi){
                                    validasi_jvakun=1; 
                                }                       
                            }else{
                                validasi_jvakun=1; 
                            }
                        
                        }
                    });
                }
                if(node.data.dk_transaksi=='D'){
                    validasi_d=1;
                }
                if(node.data.dk_transaksi=='K'){
                    validasi_k=1;
                }
                arr_akuntrx.push(node.data);               
                
                                     
            });	
            if(validasi_d!=1 || validasi_k!=1 ){
                //                console.log('debet kredit tidak balance');
                set_message(1,'debet kredit tidak balance',null);
                return;
            }
            
            
            if(str_trx_jenisvoucher_akun.getCount()> 0){
                if (validasi_jvakun==0) {
                    set_message(1,'data akun tidak sesuai dengan jenis voucher',null);
                    //                    console.log('data akun tidak sesuai dengan jenis voucher');
                    return;
                }
            }
            //            console.log('kok lewat');
            //            return;
            var str_akuntrx= Ext.util.JSON.encode(arr_akuntrx);
            //            console.log(str_akuntrx);
            var endlimit1=0;
            endlimit1=Ext.getCmp('id_trx_end_limit_apv1').getValue();            
            var endlimit2=0;
            endlimit2=Ext.getCmp('id_trx_end_limit_apv2').getValue();
            var endlimit3=0;
            endlimit3=Ext.getCmp('id_trx_end_limit_apv3').getValue();
            
            var limit1=0;
            limit1=Ext.getCmp('id_trx_limit_apv1').getValue();            
            var limit2=0;
            limit2=Ext.getCmp('id_trx_limit_apv2').getValue();
            var limit3=0;
            limit3=Ext.getCmp('id_trx_limit_apv3').getValue();
            
            Ext.Msg.show({
                title: 'Confirm',
                msg: 'Are you sure to save this entry ?',
                buttons: Ext.Msg.YESNO,
                fn: function(btn){
                    if (btn == 'yes') {
                        Ext.getCmp('id_formaddtransaksi').getForm().submit({
                            url: Ext.getCmp('id_formaddtransaksi').url,
                            scope: Ext.getCmp('id_formaddtransaksi'),
                            success: Ext.getCmp('id_formaddtransaksi').onSuccess,
                            failure: Ext.getCmp('id_formaddtransaksi').onFailure,
                            params: {
                                cmd: 'save',
                                endlimit1:endlimit1,
                                endlimit2:endlimit2,
                                endlimit3:endlimit3,
                                limit3:limit3,
                                limit1:limit1,
                                limit2:limit2,
                                data:Ext.util.JSON.encode(arr_akuntrx)
                    
                            },
                            waitMsg: 'Saving Data...'
                        });
                    }
                }
                        
            });
            
        } // eo function submit
        ,
        onSuccess: function(form, action){
            Ext.Msg.show({
                title: 'Success',
                msg: 'Form submitted successfully',
                modal: true,
                icon: Ext.Msg.INFO,
                buttons: Ext.Msg.OK
            });
            
            
            strtransaksi.reload();
            Ext.getCmp('id_nama_transaksi').setReadOnly(false);
            Ext.getCmp('id_formaddtransaksi').getForm().reset();
            winaddtransaksi.hide();
        } // eo function onSuccess
        ,
        onFailure: function(form, action){
        
            var fe = Ext.util.JSON.decode(action.response.responseText);
            this.showError(fe.errMsg || '');
            
            
        } // eo function onFailure
        ,
        showError: function(msg, title){
            title = title || 'Error';
            Ext.Msg.show({
                title: title,
                msg: msg,
                modal: true,
                icon: Ext.Msg.ERROR,
                buttons: Ext.Msg.OK,
                fn: function(btn){
                    if (btn == 'ok' && msg == 'Session Expired') {
                        window.location = '<?= site_url("auth/login") ?>';
                    }
                }
            });
        }
    }); // eo extend
    // register xtype
    Ext.reg('formaddtransaksi', transaksiform.Form);
  
    var winaddtransaksi = new Ext.Window({
        id: 'id_winaddtransaksi',
        closeAction: 'hide',
        width: 850,
        height: 600,
        layout: 'fit',
        border: false,
        items: [{
                id: 'id_formaddtransaksi',
                xtype: 'formaddtransaksi'
            }],
        onHide: function(){
            Ext.getCmp('id_formaddtransaksi').getForm().reset();
        }
    });
	
    var headertransaksi = {
        layout: 'column',
        border: false,
        items: [{
                columnWidth: 1,
                layout: 'form',
                border: false,
                frame: true,
                labelWidth: 120,
                items: [{
                        xtype: 'textfield',
                        fieldLabel: 'Kode Transaksi',
                        name: 'kd_transaksi',
                        id: 'trans_kd_transaksi',
                        fieldClass: 'readonly-input',
                        readOnly: true,
                        value: '',
                        width: 375               
                    },{
                        xtype: 'textfield',
                        fieldLabel: 'Nama Transaksi',
                        name: 'nama_transaksi',
                        id: 'trans_nama_transaksi',
                        fieldClass: 'readonly-input',
                        readOnly: true,
                        value: '',
                        width: 375               
                    }]
            }]
    };
    
    /* START GRID */    
	
    // data store
    
    var strtransaksiakun = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [ 
                'kd_transaksi',
                'kd_akun',
                'nama',
                'dk_akun',
                'dk_transaksi',
                'kd_costcenter',
                'nama_costcenter'
				  
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("transaksi/get_rows_akun") ?>',
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
	
	
	
    var searchtransaksi = new Ext.app.SearchField({
        store: strtransaksi,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchtransaksi'
    });
    
    // top toolbar
    var tbtransaksi = new Ext.Toolbar({
        items: [{
                text: 'Add',
                icon: BASE_ICONS + 'add.png',
                onClick: function(){	
                    //strcbtypeP.load(); 				Ext.getCmp('id_cbtypeP').setDisabled(false);			 				Ext.getCmp('id_cbtypeP').setValue('');			
                    Ext.getCmp('btnresettransaksi').show();
                    Ext.getCmp('btnsubmittransaksi').setText('Submit');
                    //---
                    Ext.getCmp('id_trx_approval1').setValue(false);
                    Ext.getCmp('id_trx_limit_apv1').setValue('0');
                    Ext.getCmp('id_trx_limit_apv1').setDisabled(true);
                    Ext.getCmp('id_trx_end_limit_apv1').setValue('0');
                    Ext.getCmp('id_trx_end_limit_apv1').setDisabled(true);
                    
                    
                    Ext.getCmp('id_trx_approval2').setValue(false);
                    Ext.getCmp('id_trx_limit_apv2').setValue('0');
                    Ext.getCmp('id_trx_limit_apv2').setDisabled(true);
                    Ext.getCmp('id_trx_end_limit_apv2').setValue('0');
                    Ext.getCmp('id_trx_end_limit_apv2').setDisabled(true);
                    
                    Ext.getCmp('id_trx_approval3').setValue(false);
                    Ext.getCmp('id_trx_limit_apv3').setValue('0');
                    Ext.getCmp('id_trx_limit_apv3').setDisabled(true);
                    Ext.getCmp('id_trx_end_limit_apv3').setValue('0');
                    Ext.getCmp('id_trx_end_limit_apv3').setDisabled(true);
                    
                    str_limitapproval.load();
                    str_trx_jenisvoucher.load();
                    streditakuntransaksi.load();
                    
                    winaddtransaksi.setTitle('Add Form');
                    winaddtransaksi.show();                
                }            
            }, '-', searchtransaksi]
    });
	
    var tbtransaksimenu = new Ext.Toolbar({
        items: [{
                text: 'Add',
                icon: BASE_ICONS + 'add.png',
                onClick: function(){	
                    //strcbtypeP.load(); 				Ext.getCmp('id_cbtypeP').setDisabled(false);			 				Ext.getCmp('id_cbtypeP').setValue('');			
                    Ext.getCmp('btnresettransaksi').show();
                    Ext.getCmp('btnsubmittransaksi').setText('Submit');
                    winaddtransaksi.setTitle('Add Form');
                    winaddtransaksi.show();                
                }            
            }, '-',{
                text: 'Remove',
                icon: BASE_ICONS + 'delete.gif'              
            }, '-',{
                text: 'Edit',
                iconCls: 'icon-edit-record'            
            }      
        ]});
	
    // checkbox grid
    var cbGrid = new Ext.grid.CheckboxSelectionModel();
    
    // row actions
    var actiontransaksi = new Ext.ux.grid.RowActions({
        header:'Edit',
        autoWidth: false,
        width: 40,
        actions:[
            {iconCls: 'icon-edit-record', qtip: 'Edit'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });	
    var actiontransaksidel = new Ext.ux.grid.RowActions({
        header:'Delete',
        autoWidth: false,
        width: 40,
        actions:[	      
            {iconCls: 'icon-delete-record', qtip: 'Delete'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });	
	
    actiontransaksi.on('action', function(grid, record, action, row, col) {
        var id_transaksi = record.get('kd_transaksi');
        var kdv = record.get('kd_jenis_voucher');
        if (action=='icon-edit-record'){
            str_trx_jenisvoucher_akun.reload({params:{query:kdv}});
            edittransaksi(id_transaksi);
        }
    });  
	
    actiontransaksidel.on('action', function(grid, record, action, row, col) {
        var id_transaksi = record.get('kd_transaksi');
        if (action=='icon-delete-record'){
            Ext.Msg.show({
                title: 'Confirm',
                msg: 'Are you sure delete selected row ?',
                buttons: Ext.Msg.YESNO,
                fn: function(btn){
                    if (btn == 'yes') {
                        Ext.Ajax.request({
                            url: '<?= site_url("transaksi/delete_row") ?>',
                            method: 'POST',
                            params: {
                                kd_transaksi: id_transaksi
                            },
                            callback:function(opt,success,responseObj){
                                var de = Ext.util.JSON.decode(responseObj.responseText);
                                if(de.success==true){
                                    strtransaksi.reload();
                                    strtransaksi.load({
                                        params: {
                                            start: STARTPAGE,
                                            limit: ENDPAGE
                                        }
                                    });
                                }else{
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
                            }
                        });                 
                    } 
                }
            });   
        }
    });  
	
    var gridtransaksi = new Ext.grid.GridPanel({
        //        flex:2, 
        region:'center',
        id: 'idgridtransaksi',
        store: strtransaksiakun,
        stripeRows: true,
        height: 250,		
        border:true,
        frame:true,
        columns: [
            {
                header: "Kode Transaksi",
                dataIndex: 'kd_transaksi',
                hidden:true,
                sortable: true,
                width: 90
            },
            {
                header: "Kode Akun",
                dataIndex: 'kd_akun',
                sortable: true,
                width: 90
            },{
                header: "Nama Akun",
                dataIndex: 'nama',
                sortable: true,
                width: 200
            },{
                hidden:true,
                header: "Akun D/K",
                dataIndex: 'dk_akun',
                sortable: true,
                width: 80
            },{
                header: "Posting D/K",
                dataIndex: 'dk_transaksi',
                sortable: true,
                //		listeners: {
                //			'rowclick': function(){			
                //				var sm = this.getSelectionModel();
                //				var sel = sm.getSelections();
                //				if (sel.length > 0) {
                //					Ext.getCmp('trans_kd_transaksi').setValue(sel[0].get('kd_transaksi'));
                //					Ext.getCmp('trans_nama_transaksi').setValue(sel[0].get('nama_transaksi'));
                //				}
                //		}
                //            }
                width: 80
            },{
                header: "Kode CostCenter",
                dataIndex: 'kd_costcenter',
                sortable: true,
                width: 100
            },{
                header: "Nama CostCenter",
                dataIndex: 'nama_costcenter',
                sortable: true,
                width: 200
            }
        ]
        //		
    });
  	
    // grid
    var checkcc = new Ext.grid.CheckColumn({
        header: "Cost Center",
        dataIndex: 'costcenter',
        sortable: true,
        width: 90
    });
    var checkapv1 = new Ext.grid.CheckColumn({            
        header: "Approval1",
        dataIndex: 'approval1',
        sortable: true,
        width: 90
    });
    var checkapv2 = new Ext.grid.CheckColumn({
        header: "Approval2",
        dataIndex: 'approval2',
        sortable: true,
        width: 90
    });
    var checkapv3 = new Ext.grid.CheckColumn({
        header: "Approval3",
        dataIndex: 'approval3',
        sortable: true,
        width: 90
    });
        
    var transaksi1 = new Ext.grid.EditorGridPanel({
        //        flex:1,
        region:'north',
        id: 'id-transaksi-gridpanel',
        frame: true,
        border: true,
        split:true,
        stripeRows: true,
        sm: cbGrid,
        store: strtransaksi,
        loadMask: true,
        style: 'margin:0 auto;',
        height: 380,
        width: 400,
        columns: [actiontransaksi,actiontransaksidel,{
                header: "Kode Transaksi",
                dataIndex: 'kd_transaksi',
                sortable: true,
                width: 90
            },{
                header: "Nama Transaksi",
                dataIndex: 'nama_transaksi',
                sortable: true,
                width: 200
            }
            ,{
                header: "Type Transaksi",
                dataIndex: 'type_transaksi',
                sortable: true,
                width: 90
            }
            ,checkcc,{
                header: "Kode Jenis Voucher",
                dataIndex: 'kd_jenis_voucher',
                sortable: true,
                width: 90,hidden:true
            },{
                header: "Jenis Voucher",
                dataIndex: 'title',
                sortable: true,
                width: 90
            },checkapv1,{
                xtype: 'numbercolumn',
                align: 'right',
                format:'0,0',
                header: "Limit Approval1",
                dataIndex: 'limit_apv1',
                sortable: true,
                width: 90
            },{
                xtype: 'numbercolumn',
                align: 'right',
                format:'0,0',
                header: "End Limit Approval1",
                dataIndex: 'end_limit_apv1',
                sortable: true,
                width: 90
            },checkapv2,{
                xtype: 'numbercolumn',
                align: 'right',
                format:'0,0',
                header: "Limit Approval2",
                dataIndex: 'limit_apv2',
                sortable: true,
                width: 90
                
            },{
                xtype: 'numbercolumn',
                align: 'right',
                format:'0,0',
                header: "End Limit Approval2",
                dataIndex: 'end_limit_apv2',
                sortable: true,
                width: 90
            },checkapv3,{
                xtype: 'numbercolumn',
                align: 'right',
                format:'0,0',
                header: "Limit Approval3",
                dataIndex: 'limit_apv3',
                sortable: true,
                width: 90
                
            },{
                xtype: 'numbercolumn',
                align: 'right',
                format:'0,0',
                header: "End Limit Approval3",
                dataIndex: 'end_limit_apv3',
                sortable: true,
                width: 90
            }],
        plugins: [actiontransaksi, actiontransaksidel],
        listeners: {
            'rowclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                var kdtrans=null;   
                if (sel.length > 0) {
                    kdtrans=sel[0].get('kd_transaksi'); 
                }
                strtransaksiakun.reload({params:{query:kdtrans}});				
            },
            'rowdblclick': function(){				
                var sm = transaksi1.getSelectionModel();                
                var sel = sm.getSelections();                
                if (sel.length > 0) {
                    edittransaksi(sel[0].get('kd_transaksi'),sel[0].get('nama_transaksi'));                    
                }                 
            }          
        },
        tbar: tbtransaksi,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strtransaksi,
            displayInfo: true
        })
    });
	
    var transaksi = new Ext.FormPanel({
        id: 'mastertransaksi_acc',
        border: false,
        frame: true,
        autoScroll:true,
        //		tbar: tbtransaksimenu,		
        bodyStyle:'padding-right:20px;',
        labelWidth: 130,
        layout: 'border',
        items:[transaksi1,gridtransaksi]
    });
	
    //	var transaksipanel = new Ext.FormPanel({
    //	 	id: 'mastertransaksi_acc',
    //		border: false,
    //        frame: false,
    //		autoScroll:true,	
    ////        items: [transaksi]
    //	});
    
    function edittransaksi(id_transaksi,nama_transaksi){
        // Ext.getCmp('id_nama_transaksi').setReadOnly(true);
        //strcbtypeP.load();
        //Ext.getCmp('id_cbtypeP').setDisabled(true);
        //Ext.getCmp('id_cbtypeP').setValue(type_parameter);
        str_limitapproval.reload();
        Ext.getCmp('btnresettransaksi').hide();		
        Ext.getCmp('btnsubmittransaksi').setText('Update');
        str_trx_jenisvoucher.load();
        winaddtransaksi.setTitle('Edit Form');
        streditakuntransaksi.clearData();
        streditakuntransaksi.reload({params:{query:id_transaksi}});	
        Ext.getCmp('id_formaddtransaksi').getForm().load({
            url: '<?= site_url("transaksi/get_row") ?>',
            params: {
                id: id_transaksi,
                cmd: 'POST'
            },             
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
	
        
        // streditakuntransaksi.reload({params:{query:id_transaksi}});
        winaddtransaksi.show();
    }
	
    function deletetransaksi(){		
        var sm = transaksi.getSelectionModel();
        var sel = sm.getSelections();
        if (sel.length > 0) {
            Ext.Msg.show({
                title: 'Confirm',
                msg: 'Are you sure delete selected row ?',
                buttons: Ext.Msg.YESNO,
                fn: function(btn){
                    if (btn == 'yes') {
                    
                        var data = sel[i].get('kd_transaksi');
                        
                        Ext.Ajax.request({
                            url: '<?= site_url("transaksi/delete_row") ?>',
                            method: 'POST',
                            params: {
                                kd_transaksi: data
                            },
                            callback:function(opt,success,responseObj){
                                var de = Ext.util.JSON.decode(responseObj.responseText);
                                if(de.success==true){
                                    strtransaksi.reload();
                                    strtransaksi.load({
                                        params: {
                                            start: STARTPAGE,
                                            limit: ENDPAGE
                                        }
                                    });
                                }else{
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
                            }
                        });                 
                    } 
                }
            });
        }
        else {
            Ext.Msg.show({
                title: 'Info',
                msg: 'Please selected row',
                modal: true,
                icon: Ext.Msg.ERROR,
                buttons: Ext.Msg.OK
            });
        }
        
    }
</script>
