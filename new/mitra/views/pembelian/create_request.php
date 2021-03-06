<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript"> 
    var strcbpcrsuplier = new Ext.data.ArrayStore({
        fields: ['nama_supplier'],
        data : []
    });
	
    var strgridpcrsuplier = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_supplier', 'nama_supplier'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_create_request/search_supplier") ?>',
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
	
    var searchgridpcrsuplier = new Ext.app.SearchField({
        store: strgridpcrsuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridpcrsuplier'
    });
	
	
    strgridpcrsuplier.on('load', function(){
        Ext.getCmp('id_searchgridpcrsuplier').focus();
    });
	
    var gridpcrsuplier = new Ext.grid.GridPanel({
        store: strgridpcrsuplier,
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
            items: [searchgridpcrsuplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridpcrsuplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0){				
                    Ext.getCmp('pcr_kd_supplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_cbpcrsuplier').setValue(sel[0].get('nama_supplier'));
                    strpembeliancreaterequest.removeAll();       
                    menupcrsuplier.hide();
                }
            }
        }
    });
	
    var menupcrsuplier = new Ext.menu.Menu();
    menupcrsuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridpcrsuplier],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menupcrsuplier.hide();
                }
            }]
    }));
    
    Ext.ux.TwinComboSuplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgridpcrsuplier.load();
            menupcrsuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menupcrsuplier.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridpcrsuplier').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgridpcrsuplier').setValue('');
            searchgridpcrsuplier.onTrigger2Click();
        }
    });
	
    var cbpcrsuplier = new Ext.ux.TwinComboSuplier({
        fieldLabel: 'Supplier <span class="asterix">*</span>',
        id: 'id_cbpcrsuplier',
        store: strcbpcrsuplier,
        mode: 'local',
        valueField: 'nama_supplier',
        displayField: 'nama_supplier',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'nama_supplier',
        emptyText: 'Pilih Supplier'
    });
	   
    var headerpembeliancreaterequest = {
        layout: 'column',
        border: false,
        items: [{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [{
                        xtype: 'textfield',
                        fieldLabel: 'No PR',
                        name: 'no_ro',
                        allowBlank: true,
                        readOnly:true,
                        fieldClass:'readonly-input',
                        id: 'id_no_ro',
                        maxLength: 255,
                        anchor: '90%',
                        value:''
                    },{
                        xtype: 'hidden',
                        name: 'kd_supplier',
                        id: 'pcr_kd_supplier',
                        value: ''
                    },cbpcrsuplier,
                    new Ext.form.Checkbox({
                        xtype: 'checkbox',
                        fieldLabel: 'Scan Barcode',
                        boxLabel:'Ya',
                        name:'scan_barcode',
                        id:'pcr_scan_barcode',
                        checked: false,
                        inputValue: '1',
                        autoLoad : true
                    })]
            }, {
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [ {
                        xtype: 'datefield',
                        fieldLabel: 'Tanggal',
                        name: 'tgl_ro',				
                        allowBlank:false,   
                        format:'d-m-Y',  
                        //editable:false,           
                        id: 'tgl_ro',                
                        anchor: '90%',
                        maxValue: (new Date()).clearTime() 
                    },{
                        xtype: 'textfield',
                        fieldLabel: 'Subject <span class="asterix">*</span>',
                        name: 'subject',
                        allowBlank: false,
                        id: 'subject',
                        maxLength: 255,
                        anchor: '90%'
                    },{
                        fieldLabel: 'Peruntukan <span class="asterix">*</span>',
                        xtype: 'radiogroup',
                        name: 'kd_peruntukan',
                        columnWidth: [.5, .5],
                        allowBlank:false,
                        anchor: '90%',
                        items: [{
                                boxLabel: 'Supermarket',
                                name: 'kd_peruntukan',
                                inputValue: '0',
                                id: 'epcr_peruntukan_supermarket',
                                checked:true
                            }, {
                                boxLabel: 'Distribusi',
                                name: 'kd_peruntukan',
                                inputValue: '1',
                                id: 'epcr_peruntukan_distribusi'
                            }]
                    }]
            }]
    }
	
    var strcbpcrproduk = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data : []
    });
	
	
    var strgridpcrproduk = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['waktu_top','kd_produk','kd_produk_supp','kd_produk_lama','nama_produk','min_stok','max_stok','jml_stok','nm_satuan', 'min_order', 'is_kelipatan_order'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_create_request/search_produk_by_supplier") ?>',
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
	
    var searchgridpcrproduk = new Ext.app.SearchField({
        store: strgridpcrproduk,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridpcrproduk'
    });
	
    strgridpcrproduk.on('load', function(){
        var scan = Ext.getCmp('pcr_scan_barcode').getValue();
        if(scan){
            Ext.getCmp('pcr_scan_barcode_kode').focus();
        }else{
            Ext.getCmp('id_searchgridpcrproduk').focus();
        }
    });
	
    searchgridpcrproduk.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';
			
            // Get the value of search field
            var fid = Ext.getCmp('pcr_kd_supplier').getValue();
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
	
    searchgridpcrproduk.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }
	 
        // Get the value of search field
        var fid = Ext.getCmp('pcr_kd_supplier').getValue();
        var o = { start: 0, kd_supplier: fid };
	 
        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params:o});
        this.hasSearch = true;
        this.triggers[0].show();
    };
	
    var gridpcrproduk = new Ext.grid.GridPanel({
        store: strgridpcrproduk,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'Kode produk',
                dataIndex: 'kd_produk',
                width: 100,
                sortable: true		
            },{
                header: 'Kode produk supplier',
                dataIndex: 'kd_produk_supp',
                width: 100,
                sortable: true		
            },{
                header: 'Kode produk lama',
                dataIndex: 'kd_produk_lama',
                width: 100,
                sortable: true		
            },{
                header: 'Nama produk',
                dataIndex: 'nama_produk',
                width: 400,
                sortable: true         
            },{
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 80			         
            },{
                header: 'Min.Stok',
                dataIndex: 'min_stok',
                width: 80,
                sortable: true        
            },{
                header: 'Max.Stok',
                dataIndex: 'max_stok',
                width: 80,
                sortable: true         
            },{
                header: 'Jml.Stok Pot.SO',
                dataIndex: 'jml_stok',
                width: 120,
                sortable: true         
            },{
                header: 'TOP',
                dataIndex: 'waktu_top',
                width: 50,
                sortable: true         
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridpcrproduk]
        }),
        // bbar: new Ext.PagingToolbar({
        // pageSize: ENDPAGE,
        // store: strgridpcrproduk,
        // displayInfo: true
        // }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
               
                var supermarket = Ext.getCmp('epcr_peruntukan_supermarket').getValue();
                var distribusi = Ext.getCmp('epcr_peruntukan_distribusi').getValue();

                    if (supermarket){
                        kd_peruntukkan = '0';
                    }else if (distribusi) {
                         kd_peruntukkan = '1';									
                    }
                if (sel.length > 0) {
                    Ext.Ajax.request({
                        url: '<?= site_url("pembelian_create_request/search_produk_by_supplier") ?>',
                        method: 'POST',
                        params: {
                            kd_supplier: Ext.getCmp('pcr_kd_supplier').getValue(),
                            kd_peruntukan: kd_peruntukkan,
                            kd_produk: sel[0].get('kd_produk'),
                            action: 'validate'
                        },
                        callback:function(opt,success,responseObj){
                            var de = Ext.util.JSON.decode(responseObj.responseText);
                            if(de.success==true){
                                
                                var _ada = false;
                                
                                strpembeliancreaterequest.each(function(record){
                                    if(record.get('kd_produk') === sel[0].get('kd_produk')){
                                        _ada = true;
                                    }
                                });
                                
                                if (_ada){
                                    Ext.Msg.show({
                                        title: 'Error',
                                        msg: 'Kode Barang yang sama tidak boleh dipilih lebih dari satu kali',
                                        modal: true,
                                        icon: Ext.Msg.ERROR,
                                        buttons: Ext.Msg.OK,
                                        fn: function(btn){
                                            if (btn == 'ok') {
                                                Ext.getCmp('epcr_kd_produk').reset();
                                            }
                                        }                            
                                    });
                                    Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                                    Ext.getCmp('epcr_kd_produk').focus();	
                                    return;
                                }
                                
                                Ext.getCmp('epcr_kd_produk').setValue(sel[0].get('kd_produk'));
                                Ext.getCmp('epcr_nama_produk').setValue(sel[0].get('nama_produk'));
                                Ext.getCmp('epcr_min_stok').setValue(sel[0].get('min_stok'));
                                Ext.getCmp('epcr_max_stok').setValue(sel[0].get('max_stok'));
                                Ext.getCmp('epcr_min_order').setValue(sel[0].get('min_order'));
                                Ext.getCmp('epcr_is_kelipatan_order').setValue(sel[0].get('is_kelipatan_order'));
                                Ext.getCmp('epcr_jml_stok').setValue(sel[0].get('jml_stok'));   
                                Ext.getCmp('epcr_satuan').setValue(sel[0].get('nm_satuan'));
                                Ext.getCmp('epcr_waktu_top').setValue(sel[0].get('waktu_top'));
                                Ext.getCmp('epcr_qty').setValue(0);
                                Ext.getCmp('epcr_qty').focus();	
                            }else{
                                Ext.getCmp('epcr_kd_produk').setValue('');
                                Ext.getCmp('epcr_nama_produk').setValue('');
                                Ext.getCmp('epcr_min_stok').setValue('');
                                Ext.getCmp('epcr_max_stok').setValue('');
                                Ext.getCmp('epcr_min_order').setValue('');
                                Ext.getCmp('epcr_is_kelipatan_order').setValue('');
                                Ext.getCmp('epcr_jml_stok').setValue('');   
                                Ext.getCmp('epcr_satuan').setValue('');
                                Ext.getCmp('epcr_waktu_top').setValue('');
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
                                Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
								
                            }
                        }
                    });
					
                    menupcrproduk.hide();
                }
            }
        }
    });
	
    var menupcrproduk = new Ext.menu.Menu();
    menupcrproduk.add(new Ext.Panel({
        title: 'Pilih Barang',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 600,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridpcrproduk],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menupcrproduk.hide();
                }
            }]
    }));
	
    var menupcrprodukscan = new Ext.Window();
    menupcrprodukscan.add(new Ext.Panel({
        title: 'Scan Barcode Produk',
        layout: 'form',
        border: false,
        frame: true,
        autoScroll:true, 
        bodyStyle:'padding-right:20px;',
        labelWidth: 130,
        buttonAlign: 'left',
        width: 400,
        height: 250,
        closeAction: 'hide',
        //plain: true,
        //modal: true,
        //monitorValid: true,       
        items: [{
                xtype: 'textfield',
                fieldLabel: 'Scan Barcode',
                name: 'scan_barcode',
                id: 'pcr_scan_barcode_kode',                
                anchor: '90%',
                value:'',
                listeners:{
                    specialKey: function( field, e ) {
                        if ( e.getKey() == e.RETURN || e.getKey() == e.ENTER ) {
							
                            Ext.Ajax.request({
                                url: '<?= site_url("pembelian_create_request/search_produk_by_supplier") ?>',
                                method: 'POST',
                                params: {
                                    kd_supplier: Ext.getCmp('pcr_kd_supplier').getValue(),
                                    query: Ext.getCmp('pcr_scan_barcode_kode').getValue(),
                                    sender: 'scan'
                                },
                                callback:function(opt,success,responseObj){
                                    var scn = Ext.util.JSON.decode(responseObj.responseText);
                                    if(scn.success==true){
                                                                                    
                                        var _ada = false;
                                
                                        strpembeliancreaterequest.each(function(record){
                                            if(record.get('kd_produk') === scn.data.kd_produk){
                                                _ada = true;
                                            }
                                        });
                                
                                        if (_ada){
                                            Ext.Msg.show({
                                                title: 'Error',
                                                msg: 'Kode Barang yang sama tidak boleh dipilih lebih dari satu kali',
                                                modal: true,
                                                icon: Ext.Msg.ERROR,
                                                buttons: Ext.Msg.OK,
                                                fn: function(btn){
                                                    if (btn == 'ok') {
                                                        Ext.getCmp('pcr_scan_barcode_kode').reset();
                                                    }
                                                }                            
                                            });
                                            Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                                            Ext.getCmp('pcr_scan_barcode_kode').focus();	
                                            return;
                                        }
                                                                                    
                                        Ext.getCmp('pcr_kd_produk_scan').setValue(scn.data.kd_produk);   
                                        Ext.getCmp('pcr_kd_produk_supp_scan').setValue(scn.data.kd_produk_supp);   
                                        Ext.getCmp('pcr_kd_produk_lama_scan').setValue(scn.data.kd_produk_lama);
                                        Ext.getCmp('pcr_nama_produk_scan').setValue(scn.data.nama_produk);
                                    }
                                }
                            });
                            if(Ext.getCmp('pcr_kd_produk_scan').getValue() != ''){
                                Ext.getCmp('pcr_submit_button').focus();
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
                id: 'pcr_kd_produk_scan',                
                anchor: '90%',
                value:''
            },{
                xtype: 'textfield',
                fieldLabel: 'Kode Produk Supplier',
                name: 'kd_produk_supp',
                readOnly:true,
                fieldClass:'readonly-input',
                id: 'pcr_kd_produk_supp_scan',                
                anchor: '90%',
                value:''
            },{
                xtype: 'textfield',
                fieldLabel: 'Kode Produk Lama',
                name: 'kd_produk_lama',
                readOnly:true,
                fieldClass:'readonly-input',
                id: 'pcr_kd_produk_lama_scan',                
                anchor: '90%',
                value:''
            },{
                xtype: 'textfield',
                fieldLabel: 'Nama Produk',
                name: 'nama_produk',
                readOnly:true,
                fieldClass:'readonly-input',
                id: 'pcr_nama_produk_scan',                
                anchor: '90%',
                value:''
            }
        ],
        buttons: [{
                text: 'Submit',
                formBind: true,
                id:'pcr_submit_button',
                handler: function(){
                    Ext.Ajax.request({
                        url: '<?= site_url("pembelian_create_request/search_produk_by_supplier") ?>',
                        method: 'POST',
                        params: {
                            kd_supplier: Ext.getCmp('pcr_kd_supplier').getValue(),
                            query: Ext.getCmp('pcr_scan_barcode_kode').getValue(),
                            kd_produk: Ext.getCmp('pcr_kd_produk_scan').getValue(),
                            action: 'validate', 
                            sender: 'scan'
                        },
                        callback:function(opt,success,responseObj){
                            var scn = Ext.util.JSON.decode(responseObj.responseText);
                            if(scn.success==true){
                                Ext.getCmp('epcr_kd_produk').setValue(scn.data.kd_produk);
                                Ext.getCmp('epcr_nama_produk').setValue(scn.data.nama_produk);
                                Ext.getCmp('epcr_min_stok').setValue(scn.data.min_stok);
                                Ext.getCmp('epcr_max_stok').setValue(scn.data.max_stok);
                                Ext.getCmp('epcr_min_order').setValue(scn.data.min_order);
                                Ext.getCmp('epcr_is_kelipatan_order').setValue(scn.data.is_kelipatan_order);
                                Ext.getCmp('epcr_jml_stok').setValue(scn.data.jml_stok);   
                                Ext.getCmp('epcr_satuan').setValue(scn.data.nm_satuan);
                                Ext.getCmp('epcr_waktu_top').setValue(scn.data.waktu_top);
                                Ext.getCmp('epcr_qty').setValue(0);
                                Ext.getCmp('epcr_qty').focus();
                            }else{
                                Ext.getCmp('epcr_kd_produk').setValue('');
                                Ext.getCmp('epcr_nama_produk').setValue('');
                                Ext.getCmp('epcr_min_stok').setValue('');
                                Ext.getCmp('epcr_max_stok').setValue('');
                                Ext.getCmp('epcr_min_order').setValue('');
                                Ext.getCmp('epcr_is_kelipatan_order').setValue('');
                                Ext.getCmp('epcr_jml_stok').setValue('');   
                                Ext.getCmp('epcr_satuan').setValue('');
                                Ext.getCmp('epcr_waktu_top').setValue('');
                                Ext.Msg.show({
                                    title: 'Error',
                                    msg: scn.errMsg,
                                    modal: true,
                                    icon: Ext.Msg.ERROR,
                                    buttons: Ext.Msg.OK,
                                    fn: function(btn){
                                        if (btn == 'ok' && scn.errMsg == 'Session Expired') {
                                            window.location = '<?= site_url("auth/login") ?>';
                                        }
                                    }
                                });
                                Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
								
                            }
							
                            menupcrprodukscan.hide();
                        }
                    });
                }
            },{
                text: 'Close',
                handler: function(){
                    menupcrprodukscan.hide();
                }
            }]
    }));
	
    Ext.ux.TwinComboProduk = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgridpcrproduk.load({
                params: {
                    kd_supplier: Ext.getCmp('pcr_kd_supplier').getValue()                                 
                }
            });

            var scan = Ext.getCmp('pcr_scan_barcode').getValue();
            if(scan){
                Ext.getCmp('pcr_scan_barcode_kode').setValue('');   
                Ext.getCmp('pcr_kd_produk_scan').setValue('');   
                Ext.getCmp('pcr_kd_produk_supp_scan').setValue('');   
                Ext.getCmp('pcr_kd_produk_lama_scan').setValue('');
                Ext.getCmp('pcr_nama_produk_scan').setValue('');
                var win = Ext.WindowMgr;
                // win.zseed='80000';
                win.get(menupcrprodukscan).show();
            }else{
                menupcrproduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
            }
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menupcrproduk.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridpcrproduk').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgridpcrproduk').setValue('');
            searchgridpcrproduk.onTrigger2Click();
        }
    });

    var strpembeliancreaterequest = new Ext.data.Store({
        autoSave:false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_produk', allowBlank: false, type: 'int'},
                {name: 'qty', allowBlank: false, type: 'int'}
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

    var editorpembeliancreaterequest = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });
    
	
    var gridpembeliancreaterequest = new Ext.grid.GridPanel({
        store: strpembeliancreaterequest,
        stripeRows: true,
        height: 300,
        frame: true,
        border:true,
        plugins: [editorpembeliancreaterequest],
        tbar: [{
                icon: BASE_ICONS + 'add.png',
                text: 'Add',
                handler: function(){
                    if(Ext.getCmp('pcr_kd_supplier').getValue() == ''){
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Silahkan pilih supplier terlebih dulu',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK			               
                        });
                        return;
                    }
                    var rowpembeliancreaterequest = new gridpembeliancreaterequest.store.recordType({
                        kd_produk : '',
                        qty: ''
                    });                
                    editorpembeliancreaterequest.stopEditing();
                    strpembeliancreaterequest.insert(0, rowpembeliancreaterequest);
                    gridpembeliancreaterequest.getView().refresh();
                    gridpembeliancreaterequest.getSelectionModel().selectRow(0);
                    editorpembeliancreaterequest.startEditing(0);
                }
            },{
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                disabled: true,
                handler: function(){
                    editorpembeliancreaterequest.stopEditing();
                    var s = gridpembeliancreaterequest.getSelectionModel().getSelections();
                    for(var i = 0, r; r = s[i]; i++){
                        strpembeliancreaterequest.remove(r);
                    }
                }
            }],
        columns: [new Ext.grid.RowNumberer({width: 30}),{
                header: 'Kode Produk',
                dataIndex: 'kd_produk',
                width: 200,
                sortable: true,	
                editor: new Ext.ux.TwinComboProduk({
                    id: 'epcr_kd_produk',
                    store: strcbpcrproduk,
                    mode: 'local',
                    valueField: 'kd_produk',
                    displayField: 'kd_produk',
                    typeAhead: true,
                    triggerAction: 'all',
                    allowBlank: true ,
                    editable: false,
                    hiddenName: 'kd_produk',
                    emptyText: 'Pilih Produk'
				
                })		
			
            },{
                header: 'Nama Barang',
                dataIndex: 'nama_produk',
                width: 400,
                editor: new Ext.form.TextField({                
                    readOnly: true,
                    id: 'epcr_nama_produk'
                })
            },{
                xtype: 'numbercolumn',
                header: 'Quantity',
                dataIndex: 'qty',			
                width: 80,
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'epcr_qty',
                    allowBlank: true,
                    listeners:{
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                var max = Ext.getCmp('epcr_max_stok').getValue();
                                var jml = Ext.getCmp('epcr_jml_stok').getValue();
                                var qty = this.getValue();
                                var validasi = qty+jml;
                                if(validasi > max){
                                    Ext.Msg.show({
                                        title: 'Error',
                                        msg: 'Qty + Jml Stok tidak boleh lebih besar dari Max. Stok',
                                        modal: true,
                                        icon: Ext.Msg.ERROR,
                                        buttons: Ext.Msg.OK,
                                        fn: function(btn){
                                            if (btn == 'ok') {
                                                Ext.getCmp('epcr_qty').reset();
                                            }
                                        }                            
                                    });
                                    Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                                    return;
                                }
                            }, c);
                        }
                        // 'change': function(){
                        // }
                    }
                }
            },{
                header: 'Satuan',
                dataIndex: 'satuan',
                width: 90,
                editor: new Ext.form.TextField({                
                    readOnly: true,
                    id: 'epcr_satuan'
                })
            },{
                xtype: 'numbercolumn',
                header: 'Min.Stok',
                dataIndex: 'min_stok',			
                width: 80,            
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'epcr_min_stok',
                    readOnly: true
                }
            },{
                xtype: 'numbercolumn',
                header: 'Max.Stok',
                dataIndex: 'max_stok',			
                width: 80,            
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'epcr_max_stok',
                    readOnly: true
                }
            },{
                xtype: 'numbercolumn',
                header: 'Min Order',
                dataIndex: 'min_order',			
                width: 80,            
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'epcr_min_order',
                    readOnly: true
                }
            },{
                header: 'Order Kelipatan',
                dataIndex: 'is_kelipatan_order',			
                width: 80,          
                editor: new Ext.form.TextField({                
                    readOnly: true,
                    id: 'epcr_is_kelipatan_order'
                })
            },{
                xtype: 'numbercolumn',
                header: 'Jml.Stok Pot.SO',
                dataIndex: 'jml_stok',			
                width: 80,            
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'epcr_jml_stok',
                    readOnly: true
                }
            },{
                xtype: 'numbercolumn',
                header: 'TOP',
                dataIndex: 'waktu_top',			
                width: 50,
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'epcr_waktu_top',
                    readOnly: true
                }
            }]
    });
	
    gridpembeliancreaterequest.getSelectionModel().on('selectionchange', function(sm){
        gridpembeliancreaterequest.removeBtn.setDisabled(sm.getCount() < 1);
    });
	
    var winpembeliancreaterequestprint = new Ext.Window({
        id: 'id_winpembeliancreaterequestprint',
        title: 'Print Purchase Request Form',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="pembeliancreaterequestprint" src=""></iframe>'
    });
	
    var pembeliancreaterequest = new Ext.FormPanel({
        id: 'pembeliancreaterequest',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },					
                items: [headerpembeliancreaterequest]
            }, 
            gridpembeliancreaterequest
        ],
        buttons: [{
                text: 'Save',
                formBind:true,
                handler: function(){
                    Ext.Msg.show({
                        title: 'Confirm',
                        msg: 'Apakah anda akan menyimpan data ini ??',
                        buttons: Ext.Msg.YESNO,
                        fn: function(btn){
                            if (btn == 'yes') {

                                var detailpembeliancreaterequest = new Array();		
                                strpembeliancreaterequest.sort('waktu_top', 'ASC');
                                strpembeliancreaterequest.each(function(node){
                                    detailpembeliancreaterequest.push(node.data)
                                });
                                Ext.getCmp('pembeliancreaterequest').getForm().submit({
                                    url: '<?= site_url("pembelian_create_request/update_row") ?>',
                                    scope: this,
                                    params: {
                                        detail: Ext.util.JSON.encode(detailpembeliancreaterequest)
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
                                                if (btn == 'ok') {
                                                    // winpembeliancreaterequestprint.show();
                                                    // Ext.getDom('pembeliancreaterequestprint').src = r.printUrl;
                                                }
                                            }
                                        });        

                                        clearpembeliancreaterequest();						
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
                    clearpembeliancreaterequest();
                }
            }]
    });
	
    pembeliancreaterequest.on('afterrender', function(){
        this.getForm().load({
            url: '<?= site_url("pembelian_create_request/get_form") ?>',
            success: function(form, action){
                var r = Ext.util.JSON.decode(action.response.responseText);
                if(r.data.user_peruntukan === "0"){
                    Ext.getCmp('epcr_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('epcr_peruntukan_supermarket').show();
                    Ext.getCmp('epcr_peruntukan_distribusi').hide();
                }else if(r.data.user_peruntukan === "1"){
                    Ext.getCmp('epcr_peruntukan_distribusi').setValue(true);
                    Ext.getCmp('epcr_peruntukan_supermarket').hide();
                    Ext.getCmp('epcr_peruntukan_distribusi').show();
                }else{
                    Ext.getCmp('epcr_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('epcr_peruntukan_supermarket').show();
                    Ext.getCmp('epcr_peruntukan_distribusi').show();
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
                        if (btn == 'ok' && de.errMsg == 'Session Expired') {
                            window.location = '<?= site_url("auth/login") ?>';
                        }
                    }
                });
            }
        });
    });
	
    function clearpembeliancreaterequest(){
        Ext.getCmp('pembeliancreaterequest').getForm().reset();
        Ext.getCmp('pembeliancreaterequest').getForm().load({
            url: '<?= site_url("pembelian_create_request/get_form") ?>',
            success: function(form, action){
                var r = Ext.util.JSON.decode(action.response.responseText);
                if(r.data.user_peruntukan === "0"){
                    Ext.getCmp('epcr_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('epcr_peruntukan_supermarket').show();
                    Ext.getCmp('epcr_peruntukan_distribusi').hide();
                }else if(r.data.user_peruntukan === "1"){
                    Ext.getCmp('epcr_peruntukan_distribusi').setValue(true);
                    Ext.getCmp('epcr_peruntukan_supermarket').hide();
                    Ext.getCmp('epcr_peruntukan_distribusi').show();
                }else{
                    Ext.getCmp('epcr_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('epcr_peruntukan_supermarket').show();
                    Ext.getCmp('epcr_peruntukan_distribusi').show();
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
                        if (btn == 'ok' && de.errMsg == 'Session Expired') {
                            window.location = '<?= site_url("auth/login") ?>';
                        }
                    }
                });
            }
        });
        strpembeliancreaterequest.removeAll();
    }
</script>
