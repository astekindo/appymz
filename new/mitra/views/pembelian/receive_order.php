<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    var strcbprosuplier = new Ext.data.ArrayStore({
        fields: ['kd_supplier'],
        data : []
    });
	
    var strgridprosuplier = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_supplier', 'nama_supplier'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_receive_order/search_supplier") ?>',
            method: 'POST'
        }),
        listeners: {
			
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
	
    var searchgridprosuplier = new Ext.app.SearchField({
        store: strgridprosuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridprosuplier'
    });
	
    strgridprosuplier.on('load', function(){
        Ext.getCmp('id_searchgridprosuplier').focus();
    });
	
    var gridprosuplier = new Ext.grid.GridPanel({
        store: strgridprosuplier,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'Kode Supplier',
                dataIndex: 'kd_supplier',
                width: 80,
                sortable: true			
            
            },{
                header: 'Nama Supplier',
                dataIndex: 'nama_supplier',
                width: 300,
                sortable: true         
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridprosuplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridprosuplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                    Ext.getCmp('id_cbprosuplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('pro_nama_supplier').setValue(sel[0].get('nama_supplier'));
                   
                    menuprosuplier.hide();
                }
            }
        }
    });
	
    var menuprosuplier = new Ext.menu.Menu();
    menuprosuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridprosuplier],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menuprosuplier.hide();
                }
            }]
    }));
    
    Ext.ux.TwinComboproSuplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgridprosuplier.load();
            menuprosuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menuprosuplier.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridprosuplier').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgridprosuplier').setValue('');
            searchgridprosuplier.onTrigger2Click();
        }
    });
	
    var cbprosuplier = new Ext.ux.TwinComboproSuplier({
        fieldLabel: 'Supplier <span class="asterix">*</span>',
        id: 'id_cbprosuplier',
        store: strcbprosuplier,
        mode: 'local',
        valueField: 'kd_supplier',
        displayField: 'kd_supplier',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_supplier',
        emptyText: 'Pilih Supplier'
    });
	
    // ekspedisi
    var strcbproekspedisi = new Ext.data.ArrayStore({
        fields: ['kd_ekspedisi'],
        data : []
    });
	
    var strgridproekspedisi = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_ekspedisi', 'nama_ekspedisi'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_receive_order/search_ekspedisi") ?>',
            method: 'POST'
        }),
        listeners: {
			
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
	
    var searchgridproekspedisi = new Ext.app.SearchField({
        store: strgridproekspedisi,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridproekspedisi'
    });
	
	
    var gridproekspedisi = new Ext.grid.GridPanel({
        store: strgridproekspedisi,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'Kode Ekspedisi',
                dataIndex: 'kd_ekspedisi',
                width: 80,
                sortable: true			
            
            },{
                header: 'Nama Ekspedisi',
                dataIndex: 'nama_ekspedisi',
                width: 300,
                sortable: true         
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridproekspedisi]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridproekspedisi,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                    Ext.getCmp('id_cbproekspedisi').setValue(sel[0].get('kd_ekspedisi'));                   
                    Ext.getCmp('nama_cbproekspedisi').setValue(sel[0].get('nama_ekspedisi'));                   
                    menuproekspedisi.hide();
                }
            }
        }
    });
	
    var menuproekspedisi = new Ext.menu.Menu();
    menuproekspedisi.add(new Ext.Panel({
        title: 'Pilih Ekspedisi',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridproekspedisi],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menuproekspedisi.hide();
                }
            }]
    }));
    
    Ext.ux.TwinComboproekspedisi = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgridproekspedisi.load();
            menuproekspedisi.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menuproekspedisi.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridproekspedisi').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgridproekspedisi').setValue('');
            searchgridproekspedisi.onTrigger2Click();
        }
    });
   var cbroekspedisi = new Ext.ux.TwinComboproekspedisi({
        fieldLabel: 'Ekspedisi <span class="asterix">*</span>',
        id: 'nama_cbproekspedisi',
        store: strcbproekspedisi,
        mode: 'local',
        valueField: 'nama_ekspedisi',
        displayField: 'nama_ekspedisi',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        anchor: '90%',
        hiddenName: 'nama_ekspedisi',
        emptyText: 'Pilih Ekspedisi'
    });
	
    // SATUAN ekspedisi
    var strcbprosatuanekspedisi = new Ext.data.ArrayStore({
        fields: ['kd_satuan'],
        data : []
    });
	
    var strgridprosatuanekspedisi = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_satuan', 'nm_satuan_eksp'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_receive_order/search_satuan") ?>',
            method: 'POST'
        }),
        listeners: {
			
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
	
    var searchgridprosatuanekspedisi = new Ext.app.SearchField({
        store: strgridprosatuanekspedisi,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridprosatuanekspedisi'
    });
	
	
    var gridprosatuanekspedisi = new Ext.grid.GridPanel({
        store: strgridprosatuanekspedisi,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'Kode Satuan',
                dataIndex: 'kd_satuan',
                width: 80,
                sortable: true			
            
            },{
                header: 'Nama Satuan',
                dataIndex: 'nm_satuan_eksp',
                width: 300,
                sortable: true         
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridprosatuanekspedisi]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridprosatuanekspedisi,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                    Ext.getCmp('id_cbprosatuanekspedisi').setValue(sel[0].get('kd_satuan'));                   
                    Ext.getCmp('nama_cbprosatuanekspedisi').setValue(sel[0].get('nm_satuan_eksp'));                   
                    menuprosatuanekspedisi.hide();
                }
            }
        }
    });
	
    var menuprosatuanekspedisi = new Ext.menu.Menu();
    menuprosatuanekspedisi.add(new Ext.Panel({
        title: 'Pilih Satuan',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridprosatuanekspedisi],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menuprosatuanekspedisi.hide();
                }
            }]
    }));
    
    Ext.ux.TwinComboprosatuanekspedisi = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgridprosatuanekspedisi.load({
                params:{
                    kd_ekspedisi: Ext.getCmp('id_cbproekspedisi').getValue()
                }
            });
            menuprosatuanekspedisi.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menuprosatuanekspedisi.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridprosatuanekspedisi').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgridprosatuanekspedisi').setValue('');
            searchgridprosatuanekspedisi.onTrigger2Click();
        }
    });
	
    var headerpembelianreceiveorder = {
        layout: 'column',
        border: false,
        items: [{
                columnWidth: .3,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [{
                        xtype: 'textfield',
                        fieldLabel: 'RO No.',
                        name: 'no_do',
                        readOnly:true,
                        fieldClass:'readonly-input',
                        id: 'pro_no_do',                
                        anchor: '90%',
                        value:''
                    },cbprosuplier,cbroekspedisi,
                    new Ext.form.Checkbox({
                        xtype: 'checkbox',
                        fieldLabel: 'Scan Barcode',
                        boxLabel:'Ya',
                        name:'scan_barcode',
                        id:'pro_scan_barcode',
//                        checked: true,
                        inputValue: '1',
                        autoLoad : true
                    }),{
                        xtype: 'textfield',
                        name: 'kd_ekspedisi',
                        readOnly:true,
                        hidden :true,
                        fieldClass:'readonly-input',
                        id: 'id_cbproekspedisi',                
                        anchor: '90%',
                        value:''
                    },
                ]
            }, {
                columnWidth: .4,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [ {
                        xtype: 'datefield',
                        fieldLabel: 'Tanggal Terima <span class="asterix">*</span>',
                        name: 'tanggal_terima',				
                        allowBlank:false,   
                        format:'d-m-Y',  
                        editable:false,           
                        id: 'pro_tanggal_terima',                
                        anchor: '90%',
                        maxValue: (new Date()).clearTime() 
                    },{
                        xtype: 'textfield',
                        fieldLabel: 'Nama Supplier',
                        name: 'nama_supplier',
                        readOnly:true,
                        fieldClass:'readonly-input',
                        id: 'pro_nama_supplier',                
                        anchor: '90%',
                        value:''
                    },{
                     xtype: 'textfield',
                    fieldLabel: 'Tanggal Input',
                    name: 'tanggal',
                    fieldClass:'readonly-input',
                    readOnly:true,
                    id: 'pro_tanggal',                
                    anchor: '90%',
                    value: ''
                      
                        
                    }]
            },{
                columnWidth: .3,
                layout: 'form',
                border: false,
                labelWidth: 120,
                defaults: { labelSeparator: ''},
                items: [ {
                        xtype: 'textfield',
                        fieldLabel: 'No. Bukti Supplier<span class="asterix">*</span>',
                        name: 'bukti_supplier',              
                        allowBlank: false,
                        id: 'pro_bukti_supplier',                
                        anchor: '90%'
                    },{
                        xtype: 'datefield',
                        fieldLabel: 'Tgl.Bukti Supplier  <span class="asterix">*</span>',
                        name: 'tanggal_bukti',				
                        allowBlank:false,   
                        format:'d-m-Y',  
                        editable:false,           
                        id: 'tanggal_bukti',                
                        anchor: '90%',
                        maxValue: (new Date()).clearTime() 
                    },{
                        fieldLabel: 'Peruntukan <span class="asterix">*</span>',
                        xtype: 'radiogroup',
                        name: 'kd_peruntukan',
                        columnWidth: [.5, .5],
                        allowBlank:false,
                        items: [{
                                boxLabel: 'Supermarket',
                                name: 'kd_peruntukan',
                                inputValue: '0',
                                id: 'epro_peruntukan_supermarket',
                                checked:true
                            }, {
                                boxLabel: 'Distribusi',
                                name: 'kd_peruntukan',
                                inputValue: '1',
                                id: 'epro_peruntukan_distribusi'
                            }]
                    } ]
            }]
    }
	
    /* SubBlok */
    var strcbkdsubblokpro = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['sub', 'nama_sub'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_receive_order/get_sub_blok") ?>',
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
	
    var strgridsubblokpro = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'sub',
                'nama_sub',
                'kd_sub_blok', 
                'kd_blok',
                'kd_lokasi',
                'nama_lokasi',
                'nama_blok',
                'nama_sub_blok',
                'kapasitas'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_receive_order/get_rows_lokasi") ?>',
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
	
    // search field
    var searchgridprosubblok = new Ext.app.SearchField({
        store: strgridsubblokpro,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchgridprosubblok'
    });
	
    // top toolbar
    var tbgridprosubblok = new Ext.Toolbar({
        items: [searchgridprosubblok]
    });
	
    var gridprosubblok = new Ext.grid.GridPanel({
        store: strgridsubblokpro,
        stripeRows: true,
        frame: true,
        border:true,
        tbar: tbgridprosubblok,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridsubblokpro,
            displayInfo: true
        }),
        columns: [{
                dataIndex: 'kd_lokasi',
                hidden: true
            },{
                dataIndex: 'kd_blok',
                hidden: true
            },{
                dataIndex: 'kd_sub_blok',
                hidden: true
            },{
                header: 'Kode',
                dataIndex: 'sub',
                width: 90,
                sortable: true			
            
            },{
                header: 'Sub Blok Lokasi',
                dataIndex: 'nama_sub',
                width: 200,
                sortable: true         
            }],
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('epro_sub').setValue(sel[0].get('sub'));
                    Ext.getCmp('epro_nama_sub').setValue(sel[0].get('nama_sub'));
										
                    menusubblokreceiveorder.hide();
                }
            }
        }
    });
	
    var menusubblokreceiveorder = new Ext.menu.Menu();
    menusubblokreceiveorder.add(new Ext.Panel({
        title: 'Pilih Sub Blok Lokasi',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 350,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridprosubblok],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menusubblokreceiveorder.hide();
                }
            }]
    }));
    
    Ext.ux.TwinComboproSubBlok = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
//            strgridsubblokpro.load({
//                params:{
//                    kd_produk: Ext.getCmp('epro_kd_produk').getValue()
//                }
//            });epro_peruntukan_distribusi
            strgridsubblokpro.setBaseParam('kd_produk',Ext.getCmp('epro_kd_produk').getValue());
            strgridsubblokpro.setBaseParam('kd_peruntukan_dist',Ext.getCmp('epro_peruntukan_distribusi').getValue());
            strgridsubblokpro.setBaseParam('kd_peruntukan_supp',Ext.getCmp('epro_peruntukan_supermarket').getValue());
            strgridsubblokpro.load();
            menusubblokreceiveorder.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    /* END SubBlok*/
	
    var strpembelianreceiveorder = new Ext.data.Store({
        autoSave:false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'no_po', allowBlank: false, type: 'text'},
                {name: 'kd_produk', allowBlank: false, type: 'text'},
                {name: 'nama_produk', allowBlank: false, type: 'text'},				
                {name: 'nm_satuan', allowBlank: false, type: 'text'},
                {name: 'qty_po', allowBlank: false, type: 'int'},
                {name: 'qty_do', allowBlank: false, type: 'int'},				
                {name: 'qty_terima', allowBlank: false, type: 'int'},				
                {name: 'sub', allowBlank: false, type: 'text'},
                {name: 'nama_sub', allowBlank: false, type: 'text'},				
                {name: 'kd_ekspedisi', allowBlank: false, type: 'text'},				
                {name: 'kd_satuan', allowBlank: false, type: 'text'},				
                {name: 'nm_satuan_eksp', allowBlank: false, type: 'text'},				
                {name: 'jumlah_barcode', allowBlank: false, type: 'text'},				
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        writer: new Ext.data.JsonWriter(
        {
            encode: true,
            writeAllFields: true
        })
    });
	
   
    var strcbpronopo = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_po'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_receive_order/get_all_po") ?>',
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
	
    var strcbproproduk = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data : []
    });
	
    var strgridproproduk = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_produk','kd_produk_supp','kd_produk_lama','nama_produk','qty_po','qty_do','qty_terima','qty_retur','nm_satuan','jumlah_barcode'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_receive_order/search_produk_by_no_po") ?>',
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
	
    var searchFieldRO = new Ext.app.SearchField({
        width: 220,
        id: 'search_query',
        store: strgridproproduk
    });
	
    searchFieldRO.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';
			
            // Get the value of search field
            var fid = Ext.getCmp('epro_no_po').getValue();
            var o = { start: 0, no_po: fid };
			
            this.store.baseParams = this.store.baseParams || {};
            this.store.baseParams[this.paramName] = '';
            this.store.reload({
                params : o
            });
            this.triggers[0].hide();
            this.hasSearch = false;
        }
    };
	
    searchFieldRO.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }
	 
        // Get the value of search field
        var fid = Ext.getCmp('epro_no_po').getValue();
        var o = { start: 0, no_po: fid };
	 
        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params:o});
        this.hasSearch = true;
        this.triggers[0].show();
    };
	
    // top toolbar
    var tbsearchbarang = new Ext.Toolbar({
        items: [searchFieldRO]
    });
	
    var gridproproduk = new Ext.grid.GridPanel({
        store: strgridproproduk,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'Kode Produk',
                dataIndex: 'kd_produk',
                width: 100,
                sortable: true			
            
            },{
                header: 'Kode Produk Supp',
                dataIndex: 'kd_produk_supp',
                width: 120,
                sortable: true		
           
            },{
                header: 'Kode Produk Lama',
                dataIndex: 'kd_produk_lama',
                width: 120,
                sortable: true		
            
            },{
                header: 'Nama Produk',
                dataIndex: 'nama_produk',
                width: 400,
                sortable: true         
            },{
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 80			         
            },{
                header: 'Qty PO',
                dataIndex: 'qty_po',
                width: 80,
                sortable: true         
            },{
                header: 'Qty RO',
                dataIndex: 'qty_terima',
                width: 80,
                sortable: true
            },{
                header: 'Qty',
                dataIndex: 'qty_do',
                width: 80,
                sortable: true
            },{
                header: 'Jumlah Barcode',
                dataIndex: 'jumlah_barcode',
                width: 80,
                sortable: true         
            }],
        tbar:tbsearchbarang,
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {	
                    
                    var _ada = false;
                                
                    strpembelianreceiveorder.each(function(record){
                        if(record.get('kd_produk') === sel[0].get('kd_produk') && (record.get('no_po') === Ext.getCmp('epro_no_po').getValue())){
                            _ada = true;
                        }
                    });

                    if (_ada){
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Produk Berdasarkan No Po sudah pernah dipilih',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK,
                            fn: function(btn){
                                if (btn == 'ok') {
                                    Ext.getCmp('epro_kd_produk').reset();
                                }
                            }                            
                        });
                        Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                        Ext.getCmp('epro_kd_produk').focus();	
                        return;
                    }
                   
                    Ext.getCmp('epro_kd_produk').setValue(sel[0].get('kd_produk'));
                    Ext.getCmp('epro_nama_produk').setValue(sel[0].get('nama_produk'));
                    Ext.getCmp('epro_qty_po').setValue(sel[0].get('qty_po'));  
                    Ext.getCmp('epro_qty_terima').setValue(sel[0].get('qty_terima'));
                    Ext.getCmp('epro_qty_retur').setValue(sel[0].get('qty_retur'));
                    Ext.getCmp('epro_qty').setValue(sel[0].get('qty_do'));  
                    Ext.getCmp('epro_nm_satuan').setValue(sel[0].get('nm_satuan'));    
                    Ext.getCmp('pro_jumlah_barcode').setValue(sel[0].get('jumlah_barcode'));   
                    //Ext.getCmp('epro_qty').setValue(0);
                    Ext.getCmp('epro_qty').focus();
                    menuproproduk.hide();
                }
            }
        }
    });
	
    var menuproproduk = new Ext.menu.Menu();
    menuproproduk.add(new Ext.Panel({
        title: 'Pilih Barang',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 600,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridproproduk],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menuproproduk.hide();
                }
            }]
    }));
    
    Ext.ux.TwinComboproproduk = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            if(Ext.getCmp('epro_no_po').getValue() == ''){
                Ext.Msg.show({
                    title: 'Error',
                    msg: 'Silahkan pilih No PO terlebih dulu',
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK			               
                });
                return;				
            }
            //load store grid
            strgridproproduk.load({
                params: {
                    no_po: Ext.getCmp('epro_no_po').getValue()                                 
                }
            });
            menuproproduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
// Twin Tonaliti
    var strcbprotonaliti = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data : []
    });
	
    var strgridprotonaliti = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_produk'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_receive_order/search_tonaliti") ?>',
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
	
    var searchFieldTonaliti = new Ext.app.SearchField({
        width: 220,
        id: 'search_tonaliti',
        store: strgridprotonaliti
    });
	
    searchFieldTonaliti.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';
			
            // Get the value of search field
            var fid = Ext.getCmp('epro_no_po').getValue();
            var o = { start: 0, no_po: fid };
			
            this.store.baseParams = this.store.baseParams || {};
            this.store.baseParams[this.paramName] = '';
            this.store.reload({
                params : o
            });
            this.triggers[0].hide();
            this.hasSearch = false;
        }
    };
	
    searchFieldTonaliti.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }
	 
        // Get the value of search field
        var fid = Ext.getCmp('epro_no_po').getValue();
        var o = { start: 0, no_po: fid };
	 
        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params:o});
        this.hasSearch = true;
        this.triggers[0].show();
    };
    
 var gridprotonaliti = new Ext.grid.GridPanel({
        store: strgridprotonaliti,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'Tonaliti',
                dataIndex: 'kd_produk',
                width: 150,
                sortable: true			
            
            }],
       // tbar:tbsearchbarang,
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {	
                    Ext.getCmp('epro_tonaliti').setValue(sel[0].get('kd_produk'));
                    var _ada = false;
                              
                    strpembelianreceiveorder.each(function(record){
                        if(record.get('kd_produk') === sel[0].get('kd_produk') && (record.get('no_po') === Ext.getCmp('epro_no_po').getValue())){
                            _ada = true;
                        }
                    });

                    if (_ada){
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Produk Berdasarkan No Po sudah pernah dipilih',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK,
                            fn: function(btn){
                                if (btn == 'ok') {
                                    Ext.getCmp('epro_kd_produk').reset();
                                }
                            }                            
                        });
                        Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                        Ext.getCmp('epro_kd_produk').focus();	
                        return;
                    }
                   
//                    Ext.getCmp('epro_kd_produk').setValue(sel[0].get('kd_produk'));
//                    Ext.getCmp('epro_nama_produk').setValue(sel[0].get('nama_produk'));
//                    Ext.getCmp('epro_qty_po').setValue(sel[0].get('qty_po'));  
//                    Ext.getCmp('epro_qty_terima').setValue(sel[0].get('qty_terima'));
//                    Ext.getCmp('epro_qty_retur').setValue(sel[0].get('qty_retur'));
//                    Ext.getCmp('epro_qty').setValue(sel[0].get('qty_do'));  
//                    Ext.getCmp('epro_nm_satuan').setValue(sel[0].get('nm_satuan'));    
//                    Ext.getCmp('pro_jumlah_barcode').setValue(sel[0].get('jumlah_barcode'));   
//                    //Ext.getCmp('epro_qty').setValue(0);
                    Ext.getCmp('epro_qty').focus();
                    menuprotonaliti.hide();
                }
            }
        }
    });
	
    var menuprotonaliti = new Ext.menu.Menu();
    menuprotonaliti.add(new Ext.Panel({
        title: 'Pilih Tonaliti',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 300,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridprotonaliti],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menuprotonaliti.hide();
                }
            }]
    }));
    
     Ext.ux.TwinComboproTonaliti = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            if(Ext.getCmp('epro_no_po').getValue() == ''){
                Ext.Msg.show({
                    title: 'Error',
                    msg: 'Silahkan pilih No PO terlebih dulu',
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK			               
                });
                return;				
            }
            if(Ext.getCmp('epro_kd_produk').getValue() == ''){
                Ext.Msg.show({
                    title: 'Error',
                    msg: 'Silahkan pilih Kode Produk terlebih dulu',
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK			               
                });
                return;				
            }
            //load store grid
            strgridprotonaliti.load({
                params: {
                    kd_produk: Ext.getCmp('epro_kd_produk').getValue()                                 
                }
            });
            menuprotonaliti.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    
    
// End Tonaliti
    // TWIN SCAN
    var strcbproscanbarang = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data : []
    });
	
    var strgridproscanbarang = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_produk','kd_produk_supp','kd_produk_lama','nama_produk','qty_po','qty_do','qty_terima','qty_retur','nm_satuan','jumlah_barcode'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_receive_order/search_produk_by_no_po") ?>',
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
	
    strgridproscanbarang.on('load', function(){
        // var searchString = Ext.getCmp('search_query_scan').getValue();
        // if(searchString == ''){
        // Ext.getCmp('search_query_scan').focus();
        // }else{
        // Ext.getCmp('pro_kd_produk_scan').focus();
        // // Ext.getCmp('pro_submit_button').focus();
        // }
        Ext.getCmp('pro_scan_barcode_kode').focus();
    });
	
    var searchFieldROScan = new Ext.app.SearchField({
        width: 220,
        id: 'search_query_scan',
        store: strgridproscanbarang
    });
	
    searchFieldROScan.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';
			
            // Get the value of search field
            var fid = Ext.getCmp('epro_no_po').getValue();
            var o = { start: 0, no_po: fid };
			
            this.store.baseParams = this.store.baseParams || {};
            this.store.baseParams[this.paramName] = '';
            this.store.reload({
                params : o
            });
            this.triggers[0].hide();
            this.hasSearch = false;
        }
    };
	
    searchFieldROScan.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }
	 
        // Get the value of search field
        var fid = Ext.getCmp('epro_no_po').getValue();
        var o = { start: 0, no_po: fid };
	 
        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params:o});
        this.hasSearch = true;
        this.triggers[0].show();
    };
	
    // top toolbar
    var tbsearchscanbarang = new Ext.Toolbar({
        items: [searchFieldROScan]
    });
	
    var gridproscanbarang = new Ext.grid.GridPanel({
        store: strgridproscanbarang,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'Kode Produk',
                dataIndex: 'kd_produk',
                width: 100,
                sortable: true,
                id:'pro_scan_kd_produk'
            
            },{
                header: 'Kode Produk Supp',
                dataIndex: 'kd_produk_supp',
                width: 120,
                sortable: true			
            
            },{
                header: 'Kode Produk Lama',
                dataIndex: 'kd_produk_lama',
                width: 120,
                sortable: true			
            
            },{
                header: 'Nama Produk',
                dataIndex: 'nama_produk',
                width: 400,
                sortable: true         
            },{
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 80			         
            },{
                header: 'Qty PO',
                dataIndex: 'qty_po',
                width: 80,
                sortable: true         
            },{
                header: 'Qty RO',
                dataIndex: 'qty_do',
                width: 80,
                sortable: true         
            },{
                header: 'Qty',
                dataIndex: 'qty_terima',
                width: 80,
                sortable: true         
            },{
                header: 'Jumlah Barcode',
                dataIndex: 'jumlah_barcode',
                width: 80,
                sortable: true         
            }],
        tbar:tbsearchscanbarang,
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                    Ext.getCmp('epro_kd_produk').setValue(sel[0].get('kd_produk'));
                    Ext.getCmp('epro_nama_produk').setValue(sel[0].get('nama_produk'));
                    Ext.getCmp('epro_qty_po').setValue(sel[0].get('qty_po'));  
                    Ext.getCmp('epro_qty_terima').setValue(sel[0].get('qty_terima'));
                    Ext.getCmp('epro_qty_retur').setValue(sel[0].get('qty_retur'));
                    Ext.getCmp('epro_qty').setValue(sel[0].get('qty_do'));  
                    Ext.getCmp('epro_nm_satuan').setValue(sel[0].get('nm_satuan'));    
                    Ext.getCmp('pro_jumlah_barcode').setValue(sel[0].get('jumlah_barcode'));   
                    //Ext.getCmp('epro_qty').setValue(0);
                    Ext.getCmp('epro_qty').focus();
                    menuproscanbarang.hide();
                }
            }
        }
    });
	
    var menuproscanbarang = new Ext.Window();
    menuproscanbarang.add(new Ext.Panel({
        title: 'Scan Barcode Produk',
        layout: 'form',
        border: false,
        frame: true,
        autoScroll:true, 
        //monitorValid: true,       
        bodyStyle:'padding-right:20px;',
        labelWidth: 130,
        buttonAlign: 'left',
        //modal: true,
        width: 400,
        height: 250,
        closeAction: 'hide',
        //plain: true,
        items: [{
                xtype: 'textfield',
                fieldLabel: 'Scan Barcode',
                name: 'scan_barcode',
                id: 'pro_scan_barcode_kode',                
                anchor: '90%',
                value:'',
                listeners:{
                    specialKey: function( field, e ) {
                        if ( e.getKey() === e.RETURN || e.getKey() === e.ENTER ) {
                            Ext.Ajax.request({
                                url: '<?= site_url("pembelian_receive_order/search_produk_by_no_po") ?>',
                                method: 'POST',
                                params: {
                                    no_po: Ext.getCmp('epro_no_po').getValue(),
                                    query: Ext.getCmp('pro_scan_barcode_kode').getValue(),
                                    sender: 'scan'
                                },
                                callback:function(opt,success,responseObj){
                                    var scn = Ext.util.JSON.decode(responseObj.responseText);
                                    if(scn.success === true){ 
                                        var _ada = false;
                                
                                        strpembelianreceiveorder.each(function(record){
                                            if(record.get('kd_produk') === scn.data.kd_produk && (record.get('no_po') === Ext.getCmp('epro_no_po').getValue())){
                                                _ada = true;
                                            }
                                        });

                                        if (_ada){
                                            Ext.Msg.show({
                                                title: 'Error',
                                                msg: 'Produk Berdasarkan No Po sudah pernah dipilih',
                                                modal: true,
                                                icon: Ext.Msg.ERROR,
                                                buttons: Ext.Msg.OK,
                                                fn: function(btn){
                                                    if (btn === 'ok') {
                                                        Ext.getCmp('pro_scan_barcode_kode').reset();
                                                    }
                                                }                            
                                            });
                                            Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                                            Ext.getCmp('pro_scan_barcode_kode').focus();	
                                            return;
                                        }
                                        
                                        Ext.getCmp('pro_kd_produk_scan').setValue(scn.data.kd_produk);   
                                        Ext.getCmp('pro_kd_produk_supp_scan').setValue(scn.data.kd_produk_supp);   
                                        Ext.getCmp('pro_kd_produk_lama_scan').setValue(scn.data.kd_produk_lama);
                                        Ext.getCmp('pro_nama_produk_scan').setValue(scn.data.nama_produk);
                                    }
                                }
                            });
                            if(Ext.getCmp('pro_kd_produk_scan').getValue() != ''){
                                Ext.getCmp('pro_submit_button').focus();
                            }
								
                        }
                    }
                }
            },{
                xtype: 'textfield',
                fieldLabel: 'Kode Produk',
                name: 'kd_produk',
                readOnly:true,
                fieldClass:'readonly-input',
                id: 'pro_kd_produk_scan',                
                anchor: '90%',
                value:''
            },{
                xtype: 'textfield',
                fieldLabel: 'Kode Produk Supplier',
                name: 'kd_produk_supp',
                readOnly:true,
                fieldClass:'readonly-input',
                id: 'pro_kd_produk_supp_scan',                
                anchor: '90%',
                value:''
            },{
                xtype: 'textfield',
                fieldLabel: 'Kode Produk Lama',
                name: 'kd_produk_lama',
                readOnly:true,
                fieldClass:'readonly-input',
                id: 'pro_kd_produk_lama_scan',                
                anchor: '90%',
                value:''
            },{
                xtype: 'textfield',
                fieldLabel: 'Nama Produk',
                name: 'nama_produk',
                readOnly:true,
                fieldClass:'readonly-input',
                id: 'pro_nama_produk_scan',                
                anchor: '90%',
                value:''
            }
        ],
        buttons: [{
                text: 'Submit',
                formBind: true,
                id:'pro_submit_button',
                handler: function(){
                    Ext.Ajax.request({
                        url: '<?= site_url("pembelian_receive_order/search_produk_by_no_po") ?>',
                        method: 'POST',
                        params: {
                            no_po: Ext.getCmp('epro_no_po').getValue(),
                            query: Ext.getCmp('pro_scan_barcode_kode').getValue(),
                            sender: 'scan'
                        },
                        callback:function(opt,success,responseObj){
                            var scn = Ext.util.JSON.decode(responseObj.responseText);
                            if(scn.success === true){
                                                             
                                Ext.getCmp('pro_kd_produk_scan').setValue(scn.data.kd_produk);   
                                Ext.getCmp('epro_kd_produk').setValue(scn.data.kd_produk);
                                Ext.getCmp('epro_nama_produk').setValue(scn.data.nama_produk);
                                Ext.getCmp('epro_qty_po').setValue(scn.data.qty_po);  
                                Ext.getCmp('epro_qty_terima').setValue(scn.data.qty_terima);
                                Ext.getCmp('epro_qty_retur').setValue(scn.data.qty_retur);  
                                Ext.getCmp('epro_qty').setValue(scn.data.qty_do);  
                                Ext.getCmp('epro_nm_satuan').setValue(scn.data.nm_satuan);    
                                Ext.getCmp('pro_jumlah_barcode').setValue(scn.data.jumlah_barcode);
                                //Ext.getCmp('epro_qty').setValue(0);
                                Ext.getCmp('epro_qty').focus();
                                menuproscanbarang.hide();
                            }
                        }
                    });
                }
            },{
                text: 'Close',
                handler: function(){
                    menuproscanbarang.hide();
                }
            }]
    }));
	
	
    // TWIN NO PO
    var strcbpronopo = new Ext.data.ArrayStore({
        fields: ['no_po'],
        data : []
    });
	
   /* function validasi_qty(){
        var qty_po = Ext.getCmp('epro_qty_po').getValue();
        var qty = Ext.getCmp('epro_qty').getValue();
        var qty_realisasi = qty_po - Ext.getCmp('epro_qty_terima').getValue();
			
        if(qty > qty_realisasi){
            Ext.Msg.show({
                title: 'Error',
                msg: 'Qty tidak boleh lebih besar dari Qty PO',
                modal: true,
                icon: Ext.Msg.ERROR,
                buttons: Ext.Msg.OK,
                fn: function(btn){
                    if (btn === 'ok') {
                        console.log ('test');
                        Ext.getCmp('epro_qty').setValue(0);
                    }
                }                          
            });
            Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');  
            return;
        }
    };*/
	
    var strgridpronopo = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_po','tanggal_po'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_receive_order/get_all_po") ?>',
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
	
    strgridpronopo.on('load', function(){
        Ext.getCmp('search_query_no_po').focus();
    });
	
    var searchFieldRONoPO = new Ext.app.SearchField({
        width: 220,
        id: 'search_query_no_po',
        store: strgridpronopo
    });
    searchFieldRONoPO.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';
			
            // Get the value of search field
            var fid = Ext.getCmp('id_cbprosuplier').getValue();
            var o = { start: 0, kd_supplier: fid };
			
            this.store.baseParams = this.store.baseParams || {};
            this.store.baseParams[this.paramName] = '';
            this.store.reload({
                params : o
            });
            this.triggers[0].hide();
            this.hasSearch = false;
        }
    };
	
    searchFieldRONoPO.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }
	 
        // Get the value of search field
        var fid = Ext.getCmp('id_cbprosuplier').getValue();
        var o = { start: 0, kd_supplier: fid };
	 
        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params:o});
        this.hasSearch = true;
        this.triggers[0].show();
    };
	
    // top toolbar
    var tbsearchnopo = new Ext.Toolbar({
        items: [searchFieldRONoPO]
    });
	
    var gridpronopo = new Ext.grid.GridPanel({
        store: strgridpronopo,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'No PO',
                dataIndex: 'no_po',
                width: 200,
                sortable: true,			
            
            },{
                header: 'Tgl PO',
                dataIndex: 'tanggal_po',
                width: 200,
                sortable: true,			
            
            }],
        tbar:tbsearchnopo,
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                    Ext.getCmp('epro_no_po').setValue(sel[0].get('no_po'));
                    Ext.getCmp('epro_tgl_po').setValue(sel[0].get('tanggal_po'));
                    var no_po = Ext.getCmp('epro_no_po').getValue();
                    Ext.Ajax.request({
                        url: '<?= site_url("pembelian_receive_order/search_po_bonus") ?>/' + no_po,
                        method: 'POST',
                        callback:function(opt,success,responseObj){
                            var de = Ext.util.JSON.decode(responseObj.responseText);
                            if (de.success ===true){
                                 Ext.Msg.show({
                                    title: 'Pesan : ',
                                    msg: de.msg,
                                    modal: true,
                                    buttons: Ext.Msg.OK
                                  });
                                 return; 
                            }
//                            
                        }
                    });
                    menupronopo.hide();
                    
                    var scan = Ext.getCmp('pro_scan_barcode').getValue();
					
                    if(scan){
						
                        // strgridproscanbarang.load({
                        // params: {
                        // no_po: Ext.getCmp('epro_no_po').getValue()                                 
                        // }
                        // });
                        strgridproscanbarang.load();
                        Ext.getCmp('pro_scan_barcode_kode').setValue('');   
                        Ext.getCmp('pro_kd_produk_scan').setValue('');   
                        Ext.getCmp('pro_kd_produk_supp_scan').setValue('');   
                        Ext.getCmp('pro_kd_produk_lama_scan').setValue('');
                        Ext.getCmp('pro_nama_produk_scan').setValue('');
                        //menuproscanbarang.showAt([300, 266 + 20]);
                        var win = Ext.WindowMgr;
                        // win.zseed='90000';
                        win.get(menuproscanbarang).show();

                        // menuproscanbarang.show();
                    }
                }
            }
        }
    });
	
    var menupronopo = new Ext.menu.Menu();
    menupronopo.add(new Ext.Panel({
        title: 'Pilih No PO',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 300,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridpronopo],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menupronopo.hide();
                }
            }]
    }));
    
    Ext.ux.TwinComboproNoPO = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgridpronopo.load({
                params: {
                    kd_supplier: Ext.getCmp('id_cbprosuplier').getValue()                                 
                }
            });
            menupronopo.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    var editorpembelianreceiveorder = new Ext.ux.grid.RowEditor({
        saveText: 'Update'		
    });

    var gridpembelianreceiveorder = new Ext.grid.GridPanel({
        store: strpembelianreceiveorder,
        stripeRows: true,
        height: 400,
        frame: true,
        border:true,
        plugins: [editorpembelianreceiveorder],
        tbar: [{
                icon: BASE_ICONS + 'add.png',
                text: 'Add',
                handler: function(){
                    if(Ext.getCmp('id_cbprosuplier').getValue() === ''){
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Silahkan pilih supplier terlebih dulu',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK			               
                        });
                        return;
                    }
                    var rowpembelianreceiveorder = new gridpembelianreceiveorder.store.recordType({
                        no_po:'',
                        kd_produk : '',
                        qty: ''
                    });                
                    var x = strpembelianreceiveorder.getCount() ;
                    editorpembelianreceiveorder.stopEditing();
                    strpembelianreceiveorder.insert(x, rowpembelianreceiveorder);
                    gridpembelianreceiveorder.getView().refresh();
                    gridpembelianreceiveorder.getSelectionModel().selectRow(x);
                    editorpembelianreceiveorder.startEditing(x);
                }
            },{
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                disabled: true,
                handler: function(){
                    editorpembelianreceiveorder.stopEditing();
                    var s = gridpembelianreceiveorder.getSelectionModel().getSelections();
                    for(var i = 0, r; r = s[i]; i++){
                        strpembelianreceiveorder.remove(r);
                    }
                }
            }],
        columns: [new Ext.grid.RowNumberer({width: 30}),
                {
                header: 'No PO',
                dataIndex: 'no_po',
                width: 140,
                editor: new Ext.ux.TwinComboproNoPO({
                    id: 'epro_no_po',
                    store: strcbpronopo,
                    mode: 'local',
                    valueField: 'no_po',
                    displayField: 'no_po',
                    typeAhead: true,
                    triggerAction: 'all',
                    //allowBlank: false,
                    editable: false,
                    hiddenName: 'no_po',
                    emptyText: 'Pilih No PO'
				
                })          
            },{
                header: 'Tanggal PO',
                dataIndex: 'tgl_po',
                width: 100,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epro_tgl_po',
                    fieldClass: 'readonly-input'
                })
            },{
                header: 'Kode',
                dataIndex: 'kd_produk',
                width: 110,
                editor: new Ext.ux.TwinComboproproduk({
                    id: 'epro_kd_produk',
                    store: strcbproproduk,
                    mode: 'local',
                    valueField: 'kd_produk',
                    displayField: 'kd_produk',
                    typeAhead: true,
                    triggerAction: 'all',
                    // allowBlank: false,
                    editable: false,
                    hiddenName: 'kd_produk',
                    emptyText: 'Pilih produk'
				
                })
           
            },{
                header: 'Tonality',
                dataIndex: 'tonality',
                width: 150,
                editor: new Ext.ux.TwinComboproTonaliti({
                    id: 'epro_tonaliti',
                    store: strcbprotonaliti,
                    mode: 'local',
                    valueField: 'tonaliti',
                    displayField: 'tonaliti',
                    typeAhead: true,
                    triggerAction: 'all',
                    // allowBlank: false,
                    editable: false,
                    hiddenName: 'tonaliti',
                    emptyText: 'Pilih Tonaliti'
				
                })
           
            },{
                header: 'Nama Barang',
                dataIndex: 'nama_produk',
                width: 300,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epro_nama_produk'
                })
            },{
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 80,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epro_nm_satuan'
                })
            },{
                header: 'Qty PO',
                dataIndex: 'qty_po',
                width: 50,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epro_qty_po'
                })
            },{
                header: 'Qty RO',
                dataIndex: 'qty_terima',
                width: 50,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epro_qty_terima'
                })
            },{
                header: 'Qty Retur',
                dataIndex: 'qty_retur',
                width: 70,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epro_qty_retur'
                })
            },{
                xtype: 'numbercolumn',
                header: 'Qty',
                dataIndex: 'qty_do',           
                width: 50,
                align: 'center',
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'epro_qty',
                    //allowBlank: false,
                    selectOnFocus: true,
                    listeners: {
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                
				Ext.getCmp('pro_jumlah_barcode').setValue(qty);
                                
                                var max = parseFloat (Ext.getCmp('epro_qty_po').getValue());
                                var jml = parseFloat(Ext.getCmp('epro_qty_terima').getValue());
                                var retur = parseFloat(Ext.getCmp('epro_qty_retur').getValue());
                                var qty = this.getValue();
                                var validasi = max - jml + retur;
                                console.log(validasi);
                                console.log(max);
                                if(qty > validasi){
                                    Ext.Msg.show({
                                        title: 'Error',
                                        msg: 'Qty RO + Qty - Qty Retur tidak boleh lebih besar dari Qty PO',
                                        modal: true,
                                        icon: Ext.Msg.ERROR,
                                        buttons: Ext.Msg.OK,
                                        fn: function(btn){
                                            if (btn === 'ok') {
                                                
                                                Ext.getCmp('epro_qty').reset();
                                            }
                                        }                            
                                    });
                                    Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                                    return;
                                }
                            }, c);
                        }
                    }
                }
            },{
                header: 'Kode Sub Blok',
                dataIndex: 'sub',
                width: 100,
                editor: new Ext.ux.TwinComboproSubBlok({
                    id: 'epro_sub',
                    store: strcbkdsubblokpro,
                    valueField: 'sub',
                    displayField: 'sub',
                    typeAhead: true,
                    triggerAction: 'all',
                    //allowBlank: false,
                    editable: false,
                    hiddenName: 'sub',
                    emptyText: 'Pilih Sub Blok',
                    listeners: {
                        'expand': function(){
                            strcbkdsubblokpro.load();
                        }
                    }
                })			
            },{
                header: 'Sub Blok',
                dataIndex: 'nama_sub',
                width: 200,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epro_nama_sub'
                })
            },
//            {
//                header: 'Nama Ekspedisi',
//                dataIndex: 'nama_ekspedisi',
//                width: 100,
//                editor: new Ext.ux.TwinComboproekspedisi({
//                    id: 'nama_cbproekspedisi',
//                    store: strcbproekspedisi,
//                    mode: 'local',
//                    valueField: 'nama_ekspedisi',
//                    displayField: 'nama_ekspedisi',
//                    typeAhead: true,
//                    triggerAction: 'all',
//                    editable: false,
//                    anchor: '90%',
//                    hiddenName: 'nama_ekspedisi',
//                    emptyText: 'Pilih Kode Ekspedisi'
//                })
//            },
            {
                header: 'Satuan Ekspedisi',
                dataIndex: 'nm_satuan_eksp',
                width: 100,
                editor: new Ext.ux.TwinComboprosatuanekspedisi({
                    id: 'nama_cbprosatuanekspedisi',
                    store: strcbprosatuanekspedisi,
                    mode: 'local',
                    valueField: 'nm_satuan_eksp',
                    displayField: 'nm_satuan_eksp',
                    typeAhead: true,
                    triggerAction: 'all',
                    editable: false,
                    anchor: '90%',
                    hiddenName: 'nm_satuan_eksp',
                    emptyText: 'Pilih Kode Satuan'
                })
            },{
                header: 'Berat Ekspedisi',
                dataIndex: 'berat_ekspedisi',
                editor: {
                    xtype: 'numberfield',
                    id: 'berat_ekspedisi'
                    //allowBlank: false,
                }
            },{
                header:'',
                width:0,
                dataIndex: 'jumlah_barcode',
                editor: {
                    xtype: 'numberfield',
                    id: 'pro_jumlah_barcode'
                    //allowBlank: false,
                }
            },
//            {
//                header:'',
//                width:0,
//                dataIndex: 'kd_ekspedisi',
//                editor: new Ext.form.TextField({
//                    readOnly: true,
//                    id: 'id_cbproekspedisi'
//                })
//            },
            {
                header:'',
                width:0,
                dataIndex: 'kd_satuan_ekspedisi',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'id_cbprosatuanekspedisi'
                })
            }]
    });
	
    gridpembelianreceiveorder.getSelectionModel().on('selectionchange', function(sm){
        gridpembelianreceiveorder.removeBtn.setDisabled(sm.getCount() < 1);
    });
    
    var winpembelianreceiveorderprint = new Ext.Window({
        id: 'id_winpembelianreceiveorderprint',
        title: 'Print Receive Order Form',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="pembelianreceiveorderprint" src=""></iframe>'
    });
	
    var pembelianreceiveorder = new Ext.FormPanel({
        id: 'pembelianreceiveorder',
        border: false,
        frame: true,
        autoScroll:true, 
        monitorValid: true,       
        bodyStyle:'padding-right:20px;',
        labelWidth: 130,
        items: [{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },                  
                items: [headerpembelianreceiveorder]
            },
            gridpembelianreceiveorder
        ],
        buttons: [{
                text: 'Save',
                formBind: true,
                id: 'button-save',
                handler: function(){
                    if(Ext.getCmp('epro_sub').getValue() ===''){
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'kode sub blok harus di isi!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                            
                        });
                        return;
                    }
                    var detailpembelianreceiveorder = new Array();              
                    strpembelianreceiveorder.each(function(node){
                        detailpembelianreceiveorder.push(node.data)
                    });
                    Ext.getCmp('pembelianreceiveorder').getForm().submit({
                        url: '<?= site_url("pembelian_receive_order/update_row") ?>',
                        scope: this,
                        params: {
                            detail: Ext.util.JSON.encode(detailpembelianreceiveorder)
                        },
                        waitMsg: 'Saving Data...',
                        success: function(form, action){
                            var r = Ext.util.JSON.decode(action.response.responseText);
                            Ext.Msg.show({
                                title: 'Success',
                                msg: r.errMsg,
                                modal: true,
                                icon: Ext.Msg.INFO,
                                buttons: Ext.Msg.OK,
                                fn: function(btn){
                                    if (btn === 'ok') {
                                        // winpembelianreceiveorderprint.show();
                                        // Ext.getDom('pembelianreceiveorderprint').src = r.printUrl;
                                    }
                                }
                            });                     
                        
                            clearpembelianreceiveorder();                       
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
                                    if (btn === 'ok' && fe.errMsg === 'Session Expired') {
                                        window.location = '<?= site_url("auth/login") ?>';
                                    }
                                }
                            });
                            Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000'); 
                        }                   
                    }); 
                }
            },{
                text: 'Reset',
                handler: function(){
                    clearpembelianreceiveorder();
                }
            }],
        keys: [
            { 
                key: [Ext.EventObject.ENTER], handler: function() {
                    Ext.getCmp('button-save').focus();
                }
            }]
    });
    
    pembelianreceiveorder.on('afterrender', function(){
        this.getForm().load({
            url: '<?= site_url("pembelian_receive_order/get_form") ?>',
            success: function(form, action){
                var r = Ext.util.JSON.decode(action.response.responseText);
                if(r.data.user_peruntukan === "0"){
                    Ext.getCmp('epro_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('epro_peruntukan_supermarket').show();
                    Ext.getCmp('epro_peruntukan_distribusi').hide();
                }else if(r.data.user_peruntukan === "1"){
                    Ext.getCmp('epro_peruntukan_distribusi').setValue(true);
                    Ext.getCmp('epro_peruntukan_supermarket').hide();
                    Ext.getCmp('epro_peruntukan_distribusi').show();
                }else{
                    Ext.getCmp('epro_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('epro_peruntukan_supermarket').show();
                    Ext.getCmp('epro_peruntukan_distribusi').show();
                }
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
                        if (btn === 'ok' && de.errMsg === 'Session Expired') {
                            window.location = '<?= site_url("auth/login") ?>';
                        }
                    }
                });
            }
        });
    });
    
    function clearpembelianreceiveorder(){
        Ext.getCmp('pembelianreceiveorder').getForm().reset();
        Ext.getCmp('pembelianreceiveorder').getForm().load({
            url: '<?= site_url("pembelian_receive_order/get_form") ?>',
            success: function(form, action){
                var r = Ext.util.JSON.decode(action.response.responseText);
                if(r.data.user_peruntukan === "0"){
                    Ext.getCmp('epro_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('epro_peruntukan_supermarket').show();
                    Ext.getCmp('epro_peruntukan_distribusi').hide();
                }else if(r.data.user_peruntukan === "1"){
                    Ext.getCmp('epro_peruntukan_distribusi').setValue(true);
                    Ext.getCmp('epro_peruntukan_supermarket').hide();
                    Ext.getCmp('epro_peruntukan_distribusi').show();
                }else{
                    Ext.getCmp('epro_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('epro_peruntukan_supermarket').show();
                    Ext.getCmp('epro_peruntukan_distribusi').show();
                }
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
                        if (btn === 'ok' && de.errMsg === 'Session Expired') {
                            window.location = '<?= site_url("auth/login") ?>';
                        }
                    }
                });
                Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');  
            }
        });
        strpembelianreceiveorder.removeAll();
    }
    function resetqty(){
        
    }
</script>
