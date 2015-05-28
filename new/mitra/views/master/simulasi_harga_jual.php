<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
     var strcbshjsuplier = new Ext.data.ArrayStore({
        fields: ['nama_supplier'],
        data : []
    });
	
    var strgridshjsuplier = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_supplier', 'nama_supplier'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("simulasi_harga_jual/search_supplier") ?>',
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
	
    var searchgridshjsuplier = new Ext.app.SearchField({
        store: strgridshjsuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridshjsuplier'
    });
	
	
    strgridshjsuplier.on('load', function(){
        Ext.getCmp('id_searchgridshjsuplier').focus();
    });
	
    var gridshjsuplier = new Ext.grid.GridPanel({
        store: strgridshjsuplier,
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
            items: [searchgridshjsuplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridshjsuplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0){				
                    Ext.getCmp('shj_kd_supplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_cbshjsuplier').setValue(sel[0].get('nama_supplier'));
                    strpembeliancreaterequest.removeAll();       
                    menushjsuplier.hide();
                }
            }
        }
    });
	
    var menushjsuplier = new Ext.menu.Menu();
    menushjsuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridshjsuplier],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menushjsuplier.hide();
                }
            }]
    }));
    
    Ext.ux.TwinComboShjSuplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgridshjsuplier.load();
            menushjsuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menushjsuplier.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridshjsuplier').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgridshjsuplier').setValue('');
            searchgridshjsuplier.onTrigger2Click();
        }
    });
	
    var cbshjsuplier = new Ext.ux.TwinComboShjSuplier({
        fieldLabel: 'Supplier <span class="asterix">*</span>',
        id: 'id_cbshjsuplier',
        store: strcbshjsuplier,
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
    // START PRODUK
    var strcbshjproduk = new Ext.data.ArrayStore({
        fields: ['nama_produk'],
        data : []
    });
	
    var strgridshjproduk = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_produk', 'nama_produk','p_rp_cogs','p_rp_het_cogs',
                    'diskon1',
                    'diskon2',
                    'diskon3',
                    'diskon4',
                    'diskon5',
                    'hrg_supplier',
                    'net_hrg_supplier_sup_inc',
                    'hrg_beli_sup',
                    'rp_ongkos_kirim',
                    'pct_margin',
                    'rp_jual_supermarket',
                    'rp_het_harga_beli',
                    'rp_cogs',
                    'rp_het_cogs',
                    'margin',
                    'net_price_jual'
                    ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("simulasi_harga_jual/search_produk") ?>',
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
	
    var searchgridshjproduk = new Ext.app.SearchField({
        store: strgridshjproduk,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridshjproduk'
    });
//    strgridshjproduk.on('load',function(){
//        strgridshjproduk.setBaseParam('kd_supplier',Ext.getCmp('shj_kd_supplier').getValue());
//     });
	
    strgridshjproduk.on('load', function(){
        Ext.getCmp('id_searchgridshjproduk').focus();
    });
	
    var gridshjproduk = new Ext.grid.GridPanel({
        store: strgridshjproduk,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'Kode Produk',
                dataIndex: 'kd_produk',
                width: 120,
                sortable: true			
            
            },{
                header: 'Nama Produk',
                dataIndex: 'nama_produk',
                width: 300,
                sortable: true         
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridshjproduk]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridshjproduk,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0){				
                    Ext.getCmp('id_cbshjproduk').setValue(sel[0].get('nama_produk'));
                    Ext.getCmp('shj_net_price_beli').setValue(sel[0].get('hrg_beli_sup'));
                    Ext.getCmp('shj_cogs').setValue(sel[0].get('p_rp_cogs'));
                    Ext.getCmp('shj_ongkos_kirim').setValue(sel[0].get('rp_ongkos_kirim'));
                    Ext.getCmp('shj_margin').setValue(sel[0].get('margin'));
                    Ext.getCmp('shj_het_net_price_beli').setValue(sel[0].get('rp_het_harga_beli'));
                    Ext.getCmp('shj_het_cogs').setValue(sel[0].get('p_rp_het_cogs'));
                    Ext.getCmp('shj_harga_jual').setValue(sel[0].get('rp_jual_supermarket'));
                    Ext.getCmp('shj_diskon1').setValue(sel[0].get('diskon1'));
                    Ext.getCmp('shj_diskon2').setValue(sel[0].get('diskon2'));
                    Ext.getCmp('shj_diskon3').setValue(sel[0].get('diskon3'));
                    Ext.getCmp('shj_diskon4').setValue(sel[0].get('diskon4'));
                    Ext.getCmp('shj_diskon5').setValue(sel[0].get('diskon5'));
                    Ext.getCmp('shj_net_price_jual').setValue(sel[0].get('net_price_jual'));
                    menushjproduk.hide();
                }
            }
        }
    });
	
    var menushjproduk = new Ext.menu.Menu();
    menushjproduk.add(new Ext.Panel({
        title: 'Pilih Produk',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridshjproduk],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menushjproduk.hide();
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
            strgridshjproduk.load({
                params: {
                    kd_supplier: Ext.getCmp('shj_kd_supplier').getValue(),
                    }
            });
            menushjproduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);

        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menushjproduk.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridshjproduk').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgridshjproduk').setValue('');
            searchgridshjproduk.onTrigger2Click();
        }
    });
	
    var cbshjproduk = new Ext.ux.TwinComboProduk({
        fieldLabel: 'Kode Produk <span class="asterix">*</span>',
        id: 'id_cbshjproduk',
        store: strcbshjproduk,
        mode: 'local',
        valueField: 'nama_produk',
        displayField: 'nama_produk',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        hiddenName: 'nama_produk',
        emptyText: 'Pilih Produk'
    });
    /**
     * deklarasi array untuk store bwat combo tipe diskon
     */
    var diskonType_sh = [
        ['Rp.'], ['%']
    ];

    /**
     * deklarasi store bwat combo diskon type
     */
    var diskonStore_sh = new Ext.data.ArrayStore({
        data: diskonType_sh,
        fields: ['diskon_type']
    });


    /**
     * deklarasi form simulasi harga beli
     */
    var simulasiHargaBeli_sh = {
        columnWidth: .5,
        layout: 'form',
        labelStyle: 'width:200px',
        width: 700,
        border: false,
        height: 450,
        frame: true,
        title: 'Simulasi Harga Beli',
        labelWidth: 100,
        defaults: {labelSeparator: '', allowBlank: false},
        items: [
            {
                xtype: 'panel',
                layout: 'form',
                width: '100%',
                labelWidth: 170,
                defaults: {anchor: '95%'},
                items: [cbshjsuplier,
                        cbshjproduk,{
                        xtype: 'hidden',
                        style: 'text-align: left',
                        currencySymbol: '',
                        fieldLabel: 'kd_supplier',
                        id: 'shj_kd_supplier',
                        name: 'kd_supplier',
                        allowBlank: false,
                        value: ''
                    },{
                        xtype: 'numericfield',
                        currencySymbol: 'Rp.',
                        style: 'text-align:left',
                        fieldLabel: 'Net Price Beli',
                        id: 'shj_net_price_beli',
                        name: 'net_price_beli',
                        readOnly: true,
                        fieldClass: 'readonly-input'
                    },{
                        xtype: 'numericfield',
                        currencySymbol: 'Rp.',
                        style: 'text-align:left',
                        fieldLabel: 'COGS',
                        id: 'shj_cogs',
                        name: 'cogs',
                        readOnly: true,
                        fieldClass: 'readonly-input'
                    },{
                        xtype: 'numericfield',
                        currencySymbol: 'Rp.',
                        style: 'text-align:left',
                        fieldLabel: 'Ongkos Kirim',
                        id: 'shj_ongkos_kirim',
                        name: 'ongkos_kirim',
                        readOnly: true,
                        fieldClass: 'readonly-input'
                    },{
                        xtype: 'textfield',
                        style: 'text-align:left',
                        fieldLabel: 'Margin',
                        id: 'shj_margin',
                        name: 'margin',
                        readOnly: true,
                        fieldClass: 'readonly-input'
                    },{
                        xtype: 'numericfield',
                        currencySymbol: 'Rp.',
                        style: 'text-align:left',
                        fieldLabel: 'HET Net Price Beli (Inc PPN)',
                        id: 'shj_het_net_price_beli',
                        name: 'het_net_price_beli',
                        readOnly: true,
                        fieldClass: 'readonly-input'
                    },{
                        xtype: 'numericfield',
                        currencySymbol: 'Rp.',
                        style: 'text-align:left',
                        fieldLabel: 'HET COGS',
                        id: 'shj_het_cogs',
                        name: 'het_cogs',
                        readOnly: true,
                        fieldClass: 'readonly-input'
                    },{
                        xtype: 'numericfield',
                        currencySymbol: 'Rp.',
                        style: 'text-align:left',
                        fieldLabel: 'Harga Jual',
                        id: 'shj_harga_jual',
                        name: 'harga_jual',
                        readOnly: true,
                        fieldClass: 'readonly-input'
                    },{
                        xtype: 'textfield',
                        style: 'text-align:left',
                        fieldLabel: 'Diskon 1',
                        id: 'shj_diskon1',
                        name: 'diskon1',
                        readOnly: true,
                        fieldClass: 'readonly-input'
                    },{
                        xtype: 'textfield',
                        style: 'text-align:left',
                        fieldLabel: 'Diskon 2',
                        id: 'shj_diskon2',
                        name: 'diskon2',
                        readOnly: true,
                        fieldClass: 'readonly-input'
                    },{
                        xtype: 'textfield',
                        style: 'text-align:left',
                        fieldLabel: 'Diskon 3',
                        id: 'shj_diskon3',
                        name: 'diskon3',
                        readOnly: true,
                        fieldClass: 'readonly-input'
                    },{
                        xtype: 'textfield',
                        style: 'text-align:left',
                        fieldLabel: 'Diskon 4',
                        id: 'shj_diskon4',
                        name: 'diskon4',
                        readOnly: true,
                        fieldClass: 'readonly-input'
                    },{
                        xtype: 'textfield',
                        style: 'text-align:left',
                        fieldLabel: 'Diskon 5',
                        id: 'shj_diskon5',
                        name: 'diskon5',
                        readOnly: true,
                        fieldClass: 'readonly-input'
                    },{
                        xtype: 'numericfield',
                        currencySymbol: 'Rp.',
                        style: 'text-align:left',
                        fieldLabel: 'Net Price Jual',
                        id: 'shj_net_price_jual',
                        name: 'net_price_jual',
                        readOnly: true,
                        fieldClass: 'readonly-input'
                    },
//                    {
//                        xtype: 'numericfield',
//                        style: 'text-align: left',
//                        currencySymbol: '',
//                        fieldLabel: 'Harga Beli',
//                        id: 'id_txt_harga_beli_sh',
//                        name: 'txt_harga_beli_sh',
//                        allowBlank: false,
//                        value: 0//,
////                listeners: {
////                    'blur': function() {
////                        var netPriceExBeli = 0;
////                        var hargaBeli = Ext.getCmp('id_txt_harga_beli_sh').getValue();
////                        if (hargaBeli > 0) {
////                            netPriceExBeli = hargaBeli;
////                        }
////                        Ext.getCmp('id_txt_net_price_exclude_ppn_sh').setValue(netPriceExBeli);
////                    }
////                }
//                    }, {
//                        xtype: 'compositefield',
//                        msgTarget: 'side',
//                        fieldLabel: 'Diskon 1 <span class="asterix">*</span>',
//                        items: [{
//                                xtype: 'combo',
//                                id: 'id_combo_diskon_1_sh',
//                                name: 'combo_diskon_1_sh',
//                                store: diskonStore_sh,
//                                typeAhead: true,
//                                mode: 'local',
//                                displayField: 'diskon_type',
//                                editable: false,
//                                triggerAction: 'all',
//                                forceSelection: true,
//                                value: '%',
//                                width: 50
//                            },
//                            {
//                                xtype: 'numericfield',
//                                style: 'text-align: left',
//                                currencySymbol: '',
//                                name: 'txt_diskon_1_sh',
//                                id: 'id_txt_diskon_1_sh',
//                                allowBlank: false,
//                                value: 0,
//                                width: 263,
//                                listeners: {
//                                    'specialkey': function(field, e) {
//                                        if (e.getKey() == e.ENTER) {
//                                            getDiskonPembelian();
//                                            getDiskon(Ext.getCmp('id_txt_harga_beli_sh').getValue());
//                                        }
//                                    },
//                                    'blur': function() {
//                                        getDiskonPembelian();
//                                        getDiskon(Ext.getCmp('id_txt_harga_beli_sh').getValue());
//                                    }
//                                }
//                            }]
//                    }, {
//                        xtype: 'compositefield',
//                        msgTarget: 'side',
//                        fieldLabel: 'Diskon 2 <span class="asterix">*</span>',
//                        items: [{
//                                xtype: 'combo',
//                                id: 'id_combo_diskon_2_sh',
//                                name: 'combo_diskon_2_sh',
//                                width: 50,
//                                store: diskonStore_sh,
//                                typeAhead: true,
//                                mode: 'local',
//                                displayField: 'diskon_type',
//                                editable: false,
//                                triggerAction: 'all',
//                                forceSelection: true,
//                                value: '%'
//                            },
//                            {
//                                xtype: 'numericfield',
//                                style: 'text-align: left',
//                                currencySymbol: '',
//                                name: 'txt_diskon_2_sh',
//                                id: 'id_txt_diskon_2_sh',
//                                allowBlank: false,
//                                value: 0,
//                                width: 263,
//                                listeners: {
//                                    'specialkey': function(field, e) {
//                                        if (e.getKey() == e.ENTER) {
//                                            getDiskonPembelian();
//                                            getDiskon(Ext.getCmp('id_txt_harga_beli_sh').getValue());
//                                        }
//                                    },
//                                    'blur': function() {
//                                        getDiskonPembelian();
//                                        getDiskon(Ext.getCmp('id_txt_harga_beli_sh').getValue());
//                                    }
//                                }
//                            }]
//                    }, {
//                        xtype: 'compositefield',
//                        msgTarget: 'side',
//                        fieldLabel: 'Diskon 3 <span class="asterix">*</span>',
//                        items: [{
//                                xtype: 'combo',
//                                id: 'id_combo_diskon_3_sh',
//                                name: 'combo_diskon_3_sh',
//                                width: 50,
//                                store: diskonStore_sh,
//                                typeAhead: true,
//                                mode: 'local',
//                                displayField: 'diskon_type',
//                                editable: false,
//                                triggerAction: 'all',
//                                forceSelection: true,
//                                value: '%'
//                            },
//                            {
//                                xtype: 'numericfield',
//                                style: 'text-align: left',
//                                currencySymbol: '',
//                                name: 'txt_diskon_3_sh',
//                                id: 'id_txt_diskon_3_sh',
//                                allowBlank: false,
//                                value: 0,
//                                width: 263,
//                                listeners: {
//                                    'specialkey': function(field, e) {
//                                        if (e.getKey() == e.ENTER) {
//                                            getDiskonPembelian();
//                                            getDiskon(Ext.getCmp('id_txt_harga_beli_sh').getValue());
//                                        }
//                                    },
//                                    'blur': function() {
//                                        getDiskonPembelian();
//                                        getDiskon(Ext.getCmp('id_txt_harga_beli_sh').getValue());
//                                    }
//                                }
//                            }]
//                    }, {
//                        xtype: 'compositefield',
//                        msgTarget: 'side',
//                        fieldLabel: 'Diskon 4 <span class="asterix">*</span>',
//                        items: [{
//                                xtype: 'combo',
//                                id: 'id_combo_diskon_4_sh',
//                                name: 'combo_diskon_4_sh',
//                                width: 50,
//                                store: diskonStore_sh,
//                                typeAhead: true,
//                                mode: 'local',
//                                displayField: 'diskon_type',
//                                editable: false,
//                                triggerAction: 'all',
//                                forceSelection: true,
//                                value: '%'
//                            },
//                            {
//                                xtype: 'numericfield',
//                                style: 'text-align: left',
//                                currencySymbol: '',
//                                name: 'txt_diskon_4_sh',
//                                id: 'id_txt_diskon_4_sh',
//                                allowBlank: false,
//                                value: 0,
//                                width: 263,
//                                listeners: {
//                                    'specialkey': function(field, e) {
//                                        if (e.getKey() == e.ENTER) {
//                                            getDiskonPembelian();
//                                            getDiskon(Ext.getCmp('id_txt_harga_beli_sh').getValue());
//                                        }
//                                    },
//                                    'blur': function() {
//                                        getDiskonPembelian();
//                                        getDiskon(Ext.getCmp('id_txt_harga_beli_sh').getValue());
//                                    }
//                                }
//                            }]
//                    }, {
//                        xtype: 'compositefield',
//                        msgTarget: 'side',
//                        fieldLabel: 'Diskon 5 <span class="asterix">*</span>',
//                        items: [{
//                                xtype: 'combo',
//                                id: 'id_combo_diskon_5_sh',
//                                name: 'combo_diskon_5_sh',
//                                width: 50,
//                                store: diskonStore_sh,
//                                typeAhead: true,
//                                mode: 'local',
//                                displayField: 'diskon_type',
//                                editable: false,
//                                triggerAction: 'all',
//                                forceSelection: true,
//                                value: '%'
//                            },
//                            {
//                                xtype: 'numericfield',
//                                style: 'text-align: left',
//                                currencySymbol: '',
//                                name: 'txt_diskon_5_sh',
//                                id: 'id_txt_diskon_5_sh',
//                                allowBlank: false,
//                                value: 0,
//                                width: 263,
//                                listeners: {
//                                    'specialkey': function(field, e) {
//                                        if (e.getKey() == e.ENTER) {
//                                            getDiskonPembelian();
//                                            getDiskon(Ext.getCmp('id_txt_harga_beli_sh').getValue());
//                                        }
//                                    },
//                                    'blur': function() {
//                                        getDiskonPembelian();
//                                        getDiskon(Ext.getCmp('id_txt_harga_beli_sh').getValue());
//                                    }
//                                }
//                            }]
//                    }, {
//                        xtype: 'hidden',
//                        id: 'id_txt_total_diskon_sh',
//                        name: 'txt_total_diskon_sh'
//                    }, {
//                        xtype: 'hidden',
//                        id: 'id_txt_total_diskon_rp_sh',
//                        name: 'txt_total_diskon_rp_sh'
//                    }, {
//                        xtype: 'numericfield',
//                        currencySymbol: 'Rp.',
//                        style: 'text-align:left',
//                        fieldLabel: 'Net Price Beli (INC PPN)',
//                        id: 'id_txt_net_price_exclude_ppn_sh',
//                        name: 'txt_net_price_exclude_ppn_sh',
//                        readOnly: true,
//                        fieldClass: 'readonly-input'
//                    }, {
//                        xtype: 'numericfield',
//                        currencySymbol: 'Rp.',
//                        fieldLabel: 'Net Price Beli (EXC PPN)',
//                        style: 'text-align:left',
//                        id: 'id_txt_net_price_include_ppn_sh',
//                        name: 'txt_net_price_include_ppn_sh',
//                        readOnly: true,
//                        fieldClass: 'readonly-input'
//                    }, {
//                        xtype: 'compositefield',
//                        msgTarget: 'side',
//                        fieldLabel: 'Margin',
//                        items: [{
//                                xtype: 'numberfield',
//                                id: 'id_txt_margin_percent_beli_sh',
//                                name: 'txt_margin_percent_beli_sh',
//                                width: 50,
//                                readOnly: true,
//                                fieldClass: 'readonly-input'
//                            },
//                            {
//                                xtype: 'displayfield',
//                                value: '%'
//                            },
//                            {
//                                xtype: 'numericfield',
//                                style: 'text-align: left',
//                                currencySymbol: '',
//                                name: 'txt_margin_rp_beli_sh',
//                                id: 'id_txt_margin_rp_beli_sh',
//                                allowBlank: false,
//                                value: 0,
//                                width: 246,
//                                readOnly: true,
//                                fieldClass: 'readonly-input'
//                            }]
//                    }, {
//                        xtype: 'textfield',
//                        fieldLabel: 'Ongkos Kirim',
//                        id: 'id_txt_ongkos_kirim_sh',
//                        name: 'txt_net_price_exclude_ppn_sh'
//                    }, {
//                        xtype: 'numericfield',
//                        currencySymbol: 'Rp.',
//                        fieldLabel: 'HET Net Price Beli (EXC PPN)',
//                        style: 'text-align:left',
//                        id: 'id_txt_het_net_price_exclude_ppn_sh',
//                        name: 'txt_het_net_price_exclude_ppn_sh',
//                        readOnly: true,
//                        fieldClass: 'readonly-input'
//                    }, {
//                        xtype: 'numericfield',
//                        currencySymbol: 'Rp.',
//                        fieldLabel: 'HET Net Price Beli (INC PPN)',
//                        style: 'text-align:left',
//                        id: 'id_txt_het_net_price_include_ppn_sh',
//                        name: 'txt_het_net_price_include_ppn_sh',
//                        readOnly: true,
//                        fieldClass: 'readonly-input'
//                    }
                ]
            }
        ]
    };


    /**
     * deklarasi form simulasi harga jual
     */
    var simulasiHargaJual_sh = {
        columnWidth: .5,
        layout: 'form',
        border: false,
        frame: true,
        height: 450,
        title: 'Simulasi Harga Jual',
        labelWidth: 100,
        defaults: {labelSeparator: '', allowBlank: false},
        items: [
            {
                xtype: 'panel',
                layout: 'form',
                width: '100%',
                labelWidth: 170,
                defaults: {anchor: '95%'},
                items: [{
                        xtype: 'numericfield',
                        style: 'text-align: left',
                        currencySymbol: '',
                        fieldLabel: 'Harga Jual',
                        id: 'id_txt_harga_jual_sh',
                        name: 'txt_harga_jual_sh',
                        allowBlank: false,
                        value: 0//,
//                listeners: {
//                    'blur': function() {
//                        var netPriceExJual = 0;
//                        var hargaJual = Ext.getCmp('id_txt_harga_jual_sh').getValue();
//                        if (hargaJual > 0) {
//                            netPriceExJual = hargaJual;
//                        }
//                        Ext.getCmp('id_txt_net_price_jual_exclude_ppn_sh').setValue(netPriceExJual);
//                    }
//                }
                    }, {
                        xtype: 'compositefield',
                        msgTarget: 'side',
                        fieldLabel: 'Diskon 1 <span class="asterix">*</span>',
                        items: [{
                                xtype: 'combo',
                                id: 'id_combo_diskon_1_jual_sh',
                                name: 'combo_diskon_1_jual_sh',
                                width: 50,
                                store: diskonStore_sh,
                                typeAhead: true,
                                mode: 'local',
                                displayField: 'diskon_type',
                                editable: false,
                                triggerAction: 'all',
                                forceSelection: true,
                                value: '%'
                            },
                            {
                                xtype: 'numericfield',
                                style: 'text-align: left',
                                currencySymbol: '',
                                name: 'txt_diskon_1_jual_sh',
                                id: 'id_txt_diskon_1_jual_sh',
                                allowBlank: false,
                                value: 0,
                                width: 263,
                                listeners: {
                                    'specialkey': function(field, e) {
                                        if (e.getKey() == e.ENTER) {
                                            getDiskonPenjualan();
                                            getDiskon2(Ext.getCmp('id_txt_harga_jual_sh').getValue());
                                        }
                                    },
                                    'blur': function() {
                                        getDiskonPenjualan();
                                        getDiskon2(Ext.getCmp('id_txt_harga_jual_sh').getValue());
                                    }
                                }
                            }]
                    }, {
                        xtype: 'compositefield',
                        msgTarget: 'side',
                        fieldLabel: 'Diskon 2 <span class="asterix">*</span>',
                        items: [{
                                xtype: 'combo',
                                id: 'id_combo_diskon_2_jual_sh',
                                name: 'combo_diskon_2_jual_sh',
                                width: 50,
                                store: diskonStore_sh,
                                typeAhead: true,
                                mode: 'local',
                                displayField: 'diskon_type',
                                editable: false,
                                triggerAction: 'all',
                                forceSelection: true,
                                value: '%'
                            },
                            {
                                xtype: 'numericfield',
                                style: 'text-align: left',
                                currencySymbol: '',
                                name: 'txt_diskon_2_jual_sh',
                                id: 'id_txt_diskon_2_jual_sh',
                                allowBlank: false,
                                value: 0,
                                width: 263,
                                listeners: {
                                    'specialkey': function(field, e) {
                                        if (e.getKey() == e.ENTER) {
                                            getDiskonPenjualan();
                                            getDiskon2(Ext.getCmp('id_txt_harga_jual_sh').getValue());
                                        }
                                    },
                                    'blur': function() {
                                        getDiskonPenjualan();
                                        getDiskon2(Ext.getCmp('id_txt_harga_jual_sh').getValue());
                                    }
                                }
                            }]
                    }, {
                        xtype: 'compositefield',
                        msgTarget: 'side',
                        fieldLabel: 'Diskon 3 <span class="asterix">*</span>',
                        items: [{
                                xtype: 'combo',
                                id: 'id_combo_diskon_3_jual_sh',
                                name: 'combo_diskon_3_jual_sh',
                                width: 50,
                                store: diskonStore_sh,
                                typeAhead: true,
                                mode: 'local',
                                displayField: 'diskon_type',
                                editable: false,
                                triggerAction: 'all',
                                forceSelection: true,
                                value: '%'
                            },
                            {
                                xtype: 'numericfield',
                                style: 'text-align: left',
                                currencySymbol: '',
                                name: 'txt_diskon_3_jual_sh',
                                id: 'id_txt_diskon_3_jual_sh',
                                allowBlank: false,
                                value: 0,
                                width: 263,
                                listeners: {
                                    'specialkey': function(field, e) {
                                        if (e.getKey() == e.ENTER) {
                                            getDiskonPenjualan();
                                            getDiskon2(Ext.getCmp('id_txt_harga_jual_sh').getValue());
                                        }
                                    },
                                    'blur': function() {
                                        getDiskonPenjualan();
                                        getDiskon2(Ext.getCmp('id_txt_harga_jual_sh').getValue());
                                    }
                                }
                            }]
                    }, {
                        xtype: 'compositefield',
                        msgTarget: 'side',
                        fieldLabel: 'Diskon 4 <span class="asterix">*</span>',
                        items: [{
                                xtype: 'combo',
                                id: 'id_combo_diskon_4_jual_sh',
                                name: 'combo_diskon_4_jual_sh',
                                width: 50,
                                store: diskonStore_sh,
                                typeAhead: true,
                                mode: 'local',
                                displayField: 'diskon_type',
                                editable: false,
                                triggerAction: 'all',
                                forceSelection: true,
                                value: '%'
                            },
                            {
                                xtype: 'numericfield',
                                style: 'text-align: left',
                                currencySymbol: '',
                                name: 'txt_diskon_4_jual_sh',
                                id: 'id_txt_diskon_4_jual_sh',
                                allowBlank: false,
                                value: 0,
                                width: 263,
                                listeners: {
                                    'specialkey': function(field, e) {
                                        if (e.getKey() == e.ENTER) {
                                            getDiskonPenjualan();
                                            getDiskon2(Ext.getCmp('id_txt_harga_jual_sh').getValue());
                                        }
                                    },
                                    'blur': function() {
                                        getDiskonPenjualan();
                                        getDiskon2(Ext.getCmp('id_txt_harga_jual_sh').getValue());
                                    }
                                }
                            }]
                    }, {
                        xtype: 'compositefield',
                        msgTarget: 'side',
                        fieldLabel: 'Diskon 5 <span class="asterix">*</span>',
                        items: [{
                                xtype: 'combo',
                                id: 'id_combo_diskon_5_jual_sh',
                                name: 'combo_diskon_5_jual_sh',
                                width: 50,
                                store: diskonStore_sh,
                                typeAhead: true,
                                mode: 'local',
                                displayField: 'diskon_type',
                                editable: false,
                                triggerAction: 'all',
                                forceSelection: true,
                                value: '%'
                            },
                            {
                                xtype: 'numericfield',
                                style: 'text-align: left',
                                currencySymbol: '',
                                name: 'txt_diskon_5_jual_sh',
                                id: 'id_txt_diskon_5_jual_sh',
                                allowBlank: false,
                                value: 0,
                                width: 263,
                                listeners: {
                                    'specialkey': function(field, e) {
                                        if (e.getKey() == e.ENTER) {
                                            getDiskonPenjualan();
                                            getDiskon2(Ext.getCmp('id_txt_harga_jual_sh').getValue());
                                        }
                                    },
                                    'blur': function() {
                                        getDiskonPenjualan();
                                        getDiskon2(Ext.getCmp('id_txt_harga_jual_sh').getValue());
                                    }
                                }
                            }]
                    }, {
                        xtype: 'hidden',
                        id: 'id_txt_total_diskon_jual_sh',
                        name: 'txt_total_diskon_sh',
                    }, {
                        xtype: 'hidden',
                        id: 'id_txt_total_diskon_rp_jual_sh',
                        name: 'txt_total_diskon_rp_sh',
                    }, {
                        xtype: 'numericfield',
                        style: 'text-align: left',
                        currencySymbol: 'Rp.',
                        fieldLabel: 'Net Price Jual (INC PPN)',
                        id: 'id_txt_net_price_jual_exclude_ppn_sh',
                        name: 'txt_net_price_jual_exclude_ppn_sh',
                        fieldClass: 'readonly-input'
                    }, {
                        xtype: 'numericfield',
                        style: 'text-align: left',
                        currencySymbol: 'Rp.',
                        fieldLabel: 'Net Price Jual (EXC PPN)',
                        id: 'id_txt_net_price_jual_include_ppn_sh',
                        name: 'txt_net_price_jual_include_ppn_sh',
                        readOnly: true,
                        fieldClass: 'readonly-input'
                    }, {
                        xtype: 'compositefield',
                        msgTarget: 'side',
                        fieldLabel: 'Margin',
                        items: [{
                                xtype: 'numberfield',
                                id: 'id_txt_margin_percent_jual_sh',
                                name: 'txt_margin_percent_jual_sh',
                                width: 50,
                                readOnly: true,
                                fieldClass: 'readonly-input'
                            },
                            {
                                xtype: 'displayfield',
                                value: '%'
                            },
                            {
                                xtype: 'numericfield',
                                style: 'text-align: left',
                                currencySymbol: '',
                                name: 'txt_margin_rp_jual_sh',
                                id: 'id_txt_margin_rp_jual_sh',
                                allowBlank: false,
                                value: 0,
                                width: 246,
                                readOnly: true,
                                fieldClass: 'readonly-input'
                            }]
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'Ongkos Kirim',
                        id: 'id_txt_ongkos_kirim_jual_sh',
                        name: 'txt_ongkos_kirim_jual_sh'
                    }, {
                        xtype: 'numericfield',
                        currencySymbol: 'Rp.',
                        fieldLabel: 'HET Net Price Jual (EXC PPN)',
                        style: 'text-align:left',
                        id: 'id_txt_het_net_price_jual_exclude_ppn_sh',
                        name: 'txt_het_net_price_jual_exclude_ppn_sh',
                        readOnly: true,
                        fieldClass: 'readonly-input'
                    }, {
                        xtype: 'numericfield',
                        currencySymbol: 'Rp.',
                        fieldLabel: 'HET Net Price Jual (INC PPN)',
                        style: 'text-align:left',
                        id: 'id_txt_het_net_price_jual_include_ppn_sh',
                        name: 'txt_het_net_price_jual_include_ppn_sh',
                        readOnly: true,
                        fieldClass: 'readonly-input'
                    }
                ]
            }
        ]
    };


    var headerSimulasiHarga = {
        layout: 'column',
        border: false,
        buttonAlign: 'left',
        items: [simulasiHargaBeli_sh, simulasiHargaJual_sh
        ]
        ,
        buttons: [
            {
                text: 'reset',
                handler: function() {
                    Ext.getCmp('id_simulasi_harga').getForm().reset();
                }
            }
//            , {
//                text: 'reset'
//            }, {
//                text: 'cancel'
//            }
        ]
    };

    Ext.ns('id_simulasi_harga');
    var simulasi_harga = new Ext.FormPanel({
        id: 'id_simulasi_harga',
        border: false,
        frame: true,
        autoScroll: false,
        //bodyStyle: 'padding:5px;',
        items: [
            headerSimulasiHarga
        ]

    });

    function getDiskon(hargaDiskon) {
        var diskonPercent = parseFloat(Ext.getCmp('id_txt_total_diskon_sh').getValue());
        var diskonRp = parseFloat(Ext.getCmp('id_txt_total_diskon_rp_sh').getValue());
        var totalDiskon = diskonPercent + diskonRp;
        Ext.getCmp('id_txt_net_price_exclude_ppn_sh').setValue(hargaDiskon - totalDiskon);
        Ext.getCmp('id_txt_net_price_include_ppn_sh').setValue((hargaDiskon - totalDiskon) / 1.1);
    }

    function getDiskon2(hargaDiskon) {
        var diskonPercent = parseFloat(Ext.getCmp('id_txt_total_diskon_jual_sh').getValue());
        var diskonRp = parseFloat(Ext.getCmp('id_txt_total_diskon_rp_jual_sh').getValue());
        var totalDiskon = diskonPercent + diskonRp;
        Ext.getCmp('id_txt_net_price_jual_exclude_ppn_sh').setValue(hargaDiskon - totalDiskon);
        Ext.getCmp('id_txt_net_price_jual_include_ppn_sh').setValue((hargaDiskon - totalDiskon) / 1.1);
    }

    function getDiskonPembelian() {
        var diskon1 = Ext.getCmp('id_txt_diskon_1_sh').getValue();
        var diskon2 = Ext.getCmp('id_txt_diskon_2_sh').getValue();
        var diskon3 = Ext.getCmp('id_txt_diskon_3_sh').getValue();
        var diskon4 = Ext.getCmp('id_txt_diskon_4_sh').getValue();
        var diskon5 = Ext.getCmp('id_txt_diskon_5_sh').getValue();
        var type1 = Ext.getCmp('id_combo_diskon_1_sh').getValue();
        var type2 = Ext.getCmp('id_combo_diskon_2_sh').getValue();
        var type3 = Ext.getCmp('id_combo_diskon_3_sh').getValue();
        var type4 = Ext.getCmp('id_combo_diskon_4_sh').getValue();
        var type5 = Ext.getCmp('id_combo_diskon_5_sh').getValue();
        var hargaBeli = Ext.getCmp('id_txt_harga_beli_sh').getValue();
        Ext.getCmp('id_txt_total_diskon_sh').setValue(getTotalDiskon(diskon1, diskon2, diskon3, diskon4, diskon5, type1, type2, type3, type4, type5, hargaBeli));
        Ext.getCmp('id_txt_total_diskon_rp_sh').setValue(getTotalRp(diskon1, diskon2, diskon3, diskon4, diskon5, type1, type2, type3, type4, type5));
    }

    function getDiskonPenjualan() {
        var diskon1 = Ext.getCmp('id_txt_diskon_1_jual_sh').getValue();
        var diskon2 = Ext.getCmp('id_txt_diskon_2_jual_sh').getValue();
        var diskon3 = Ext.getCmp('id_txt_diskon_3_jual_sh').getValue();
        var diskon4 = Ext.getCmp('id_txt_diskon_4_jual_sh').getValue();
        var diskon5 = Ext.getCmp('id_txt_diskon_5_jual_sh').getValue();
        var type1 = Ext.getCmp('id_combo_diskon_1_jual_sh').getValue();
        var type2 = Ext.getCmp('id_combo_diskon_2_jual_sh').getValue();
        var type3 = Ext.getCmp('id_combo_diskon_3_jual_sh').getValue();
        var type4 = Ext.getCmp('id_combo_diskon_4_jual_sh').getValue();
        var type5 = Ext.getCmp('id_combo_diskon_5_jual_sh').getValue();
        var hargaJual = Ext.getCmp('id_txt_harga_jual_sh').getValue();
        Ext.getCmp('id_txt_total_diskon_jual_sh').setValue(getTotalDiskon(diskon1, diskon2, diskon3, diskon4, diskon5, type1, type2, type3, type4, type5, hargaJual));
        Ext.getCmp('id_txt_total_diskon_rp_jual_sh').setValue(getTotalRp(diskon1, diskon2, diskon3, diskon4, diskon5, type1, type2, type3, type4, type5));
    }

    function getTotalDiskon(diskon1, diskon2, diskon3, diskon4, diskon5, dType1, dType2, dType3, dType4, dType5, hargaDiskon) {
        var diskon = 0;
        if (diskon1 != 0 && dType1 == '%') {
            diskon = diskon1;
        }
        if (diskon2 != 0 && dType2 == '%') {
            diskon = diskon + diskon2;
        }
        if (diskon3 != 0 && dType3 == '%') {
            diskon = diskon + diskon3;
        }
        if (diskon4 != 0 && dType4 == '%') {
            diskon = diskon + diskon4;
        }
        if (diskon5 != 0 && dType5 == '%') {
            diskon = diskon + diskon5;
        }
        //Ext.getCmp('id_txt_total_diskon_sh').setValue(diskon);
        return (diskon / 100) * hargaDiskon;
    }

    function getTotalRp(diskon1, diskon2, diskon3, diskon4, diskon5, dType1, dType2, dType3, dType4, dType5) {
        var diskon = 0;
        if (diskon1 != 0 && dType1 != '%') {
            diskon = diskon1;
        }
        if (diskon2 != 0 && dType2 != '%') {
            diskon = diskon + diskon2;
        }
        if (diskon3 != 0 && dType3 != '%') {
            diskon = diskon + diskon3;
        }
        if (diskon4 != 0 && dType4 != '%') {
            diskon = diskon + diskon4;
        }
        if (diskon5 != 0 && dType5 != '%') {
            diskon = diskon + diskon5;
        }
        //Ext.getCmp('id_txt_total_diskon_sh').setValue(diskon);
        return diskon;
    }
</script>
