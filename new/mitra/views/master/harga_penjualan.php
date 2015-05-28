<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">	
    /* START twin produk*/
	
    var strcbhjproduk = new Ext.data.ArrayStore({
        fields: ['kd_produk', 'nama_produk'],
        data : []
    });
	
    var strgridhjproduk = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_produk', 'nama_produk'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("harga_penjualan/search_produk_by_kategori") ?>',
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
	
    var searchgridhjproduk = new Ext.app.SearchField({
        store: strgridhjproduk,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridhjproduk'
    });
    
     searchgridhjproduk.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';
			
            // Get the value of search field
            var fid = Ext.getCmp('id_cbhjsuplier').getValue();
            var kons = Ext.getCmp('hj_konsinyasi').getValue();
            var o = { start: 0, kd_supplier: fid, konsinyasi: kons};
			
            this.store.baseParams = this.store.baseParams || {};
            this.store.baseParams[this.paramName] = '';
            this.store.reload({
                params : o
            });
            this.triggers[0].hide();
            this.hasSearch = false;
        }
    };
	
    searchgridhjproduk.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }
	 
        // Get the value of search field
        var fid = Ext.getCmp('id_cbhjsuplier').getValue();
        var kons = Ext.getCmp('hj_konsinyasi').getValue();
        var o = { start: 0, kd_supplier: fid, konsinyasi: kons};
	 
        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params:o});
        this.hasSearch = true;
        this.triggers[0].show();
    };
	
    var gridhjproduk = new Ext.grid.GridPanel({
        store: strgridhjproduk,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'Kode Produk',
                dataIndex: 'kd_produk',
                width: 150,
                sortable: true		
            
            },{
                header: 'Nama Produk',
                dataIndex: 'nama_produk',
                width: 150,
                sortable: true			
            
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridhjproduk]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridhjproduk,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {	
                    Ext.getCmp('id_cbhjproduk').setValue(sel[0].get('kd_produk'));
                    menuhjproduk.hide();
                }
            }
        }
    });
	
    var menuhjproduk = new Ext.menu.Menu();
    menuhjproduk.add(new Ext.Panel({
        title: 'Pilih Produk',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridhjproduk],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menuhjproduk.hide();
                }
            }]
    }));
    
    Ext.ux.TwinCombohjproduk = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            var kd_supplier =  Ext.getCmp('id_cbhjsuplier').getValue();
            if(!kd_supplier){
                Ext.Msg.show({
                    title: 'Error',
                    msg: 'Silahkan Pilih Supplier Terlebih Dahulu',
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK,
                    // fn: function(btn){
                    // if (btn == 'ok' && msg == 'Session Expired') {
                    // window.location = '<?= site_url("auth/login") ?>';
                    // }
                    // }
                });
                return;
            }
            strgridhjproduk.load({
                params:{
                    start: STARTPAGE,
                    limit: ENDPAGE,
                    kd_supplier: Ext.getCmp('id_cbhjsuplier').getValue(),
                    kd_kategori1: Ext.getCmp('hj_cbkategori1').getValue(),
                    kd_kategori2: Ext.getCmp('hj_cbkategori2').getValue(),
                    kd_kategori3: Ext.getCmp('hj_cbkategori3').getValue(),
                    kd_kategori4: Ext.getCmp('hj_cbkategori4').getValue(),
                    no_bukti: Ext.getCmp('id_cbhjnobuktifilter').getValue(),
                    konsinyasi: Ext.getCmp('hj_konsinyasi').getValue(),
                    list: Ext.getCmp('ehj_list').getValue(),
                }
            });
            menuhjproduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menuhjproduk.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridhjproduk').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgridhjproduk').setValue('');
            searchgridhjproduk.onTrigger2Click();
        }
    });
	
    var cbhjproduk = new Ext.ux.TwinCombohjproduk({
        id: 'id_cbhjproduk',
        store: strcbhjproduk,
        mode: 'local',
        valueField: 'kd_produk',
        displayField: 'kd_produk',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_produk',
        emptyText: 'Pilih Produk'
    });
    /* END twin produk*/
	
    /* START TWIN NO BUKTI*/
	
    var strcbhjnobukti = new Ext.data.ArrayStore({
        fields: ['no_bukti','keterangan'],
        data : []
    });
	
    var strgridhjnobukti = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_bukti','keterangan','created_by','nama_supplier'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("harga_penjualan/search_no_bukti") ?>',
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
	
    var searchgridhjnobukti = new Ext.app.SearchField({
        store: strgridhjnobukti,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridhjnobukti'
    });
	
	
    var gridhjnobukti = new Ext.grid.GridPanel({
        store: strgridhjnobukti,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'No Bukti',
                dataIndex: 'no_bukti',
                width: 100,
                sortable: true,			
            
            },{
                header: 'Nama Supplier',
                dataIndex: 'nama_supplier',
                width: 125,
                sortable: true,			
            
            },{
                header: 'Request By',
                dataIndex: 'created_by',
                width: 100,
                sortable: true,			
            
            },{
                header: 'Ket. Perubahan',
                dataIndex: 'keterangan',
                width: 200,
                sortable: true,	
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridhjnobukti]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridhjnobukti,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {	
                    Ext.getCmp('id_cbhjnobukti').setValue(sel[0].get('no_bukti'));
					        
                    menuhjnobukti.hide();
                }
            }
        }
    });
	
    var menuhjnobukti = new Ext.menu.Menu();
    menuhjnobukti.add(new Ext.Panel({
        title: 'Pilih No Bukti',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 500,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridhjnobukti],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menuhjnobukti.hide();
                }
            }]
    }));
    
    Ext.ux.TwinCombohjnobukti = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgridhjnobukti.load();
            menuhjnobukti.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menuhjnobukti.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridhjnobukti').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgridhjnobukti').setValue('');
            searchgridhjnobukti.onTrigger2Click();
        }
    });
	
    var cbhjnobukti = new Ext.ux.TwinCombohjnobukti({
        fieldLabel: 'No Bukti <span class="asterix">*</span>',
        id: 'id_cbhjnobukti',
        store: strcbhjnobukti,
        mode: 'local',
        valueField: 'no_bukti',
        displayField: 'no_bukti',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'no_bukti',
        emptyText: 'Pilih No Bukti'
    });
	
    /* END TWIN NO BUKTI*/
	
    /*START TWIN NO BUKTI FILTER*/
	
    var strcbhjnobuktifilter = new Ext.data.ArrayStore({
        fields: ['no_bukti','keterangan'],
        data : []
    });
	
    var strgridhjnobuktifilter = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_bukti_filter','keterangan','created_by','nama_supplier'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("harga_penjualan/get_no_bukti_filter") ?>',
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
	
    var searchgridhjnobuktifilter = new Ext.app.SearchField({
        store: strgridhjnobuktifilter,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridhjnobuktifilter'
    });
	
	
    var gridhjnobuktifilter = new Ext.grid.GridPanel({
        store: strgridhjnobuktifilter,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'No Bukti',
                dataIndex: 'no_bukti_filter',
                width: 100,
                sortable: true			
            
            },{
                header: 'Nama Supplier',
                dataIndex: 'nama_supplier',
                width: 125,
                sortable: true		
            
            },{
                header: 'Request By',
                dataIndex: 'created_by',
                width: 100,
                sortable: true			
            
            },{
                header: 'Ket. Perubahan',
                dataIndex: 'keterangan',
                width: 200,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridhjnobuktifilter]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridhjnobuktifilter,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {	
                    Ext.getCmp('id_cbhjnobuktifilter').setValue(sel[0].get('no_bukti_filter'));
					        
                    menuhjnobuktifilter.hide();
                }
            }
        }
    });
	
    var menuhjnobuktifilter = new Ext.menu.Menu();
    menuhjnobuktifilter.add(new Ext.Panel({
        title: 'Pilih No Bukti',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 500,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridhjnobuktifilter],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menuhjnobuktifilter.hide();
                }
            }]
    }));
    
    Ext.ux.TwinCombohjnobuktifilter = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgridhjnobuktifilter.load();
            menuhjnobuktifilter.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menuhjnobuktifilter.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridhjnobuktifilter').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgridhjnobuktifilter').setValue('');
            searchgridhjnobuktifilter.onTrigger2Click();
        }
    });
	
    var cbhjnobuktifilter = new Ext.ux.TwinCombohjnobuktifilter({
        fieldLabel: 'No Bukti Filter',
        id: 'id_cbhjnobuktifilter',
        store: strcbhjnobuktifilter,
        mode: 'local',
        valueField: 'no_bukti_filter',
        displayField: 'no_bukti_filter',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'no_bukti_filter',
        emptyText: 'Pilih No Bukti'
    });
	
    /*END TWIN NO BUKTI FILTER*/
	
    /* START HISTORY */ 	
    var strhargapenjualanhistory = new Ext.data.Store({
        autoSave:false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_diskon_sales', allowBlank: true, type: 'text'},
                {name: 'koreksi_diskon', allowBlank: true, type: 'text'},
                {name: 'koreksi_produk', allowBlank: true, type: 'text'},
                {name: 'kd_produk', allowBlank: false, type: 'text'},
                {name: 'nama_produk', allowBlank: false, type: 'text'},
                {name: 'nm_satuan', allowBlank: false, type: 'text'},	
                {name: 'nama_supplier', allowBlank: false, type: 'text'},	
                {name: 'disk_kons1_op', allowBlank: false, type: 'text'},
                {name: 'disk_kons2_op', allowBlank: false, type: 'text'},
                {name: 'disk_kons3_op', allowBlank: false, type: 'text'},
                {name: 'disk_kons4_op', allowBlank: false, type: 'text'},
                {name: 'disk_member1_op', allowBlank: false, type: 'text'},
                {name: 'disk_member2_op', allowBlank: false, type: 'text'},
                {name: 'disk_member3_op', allowBlank: false, type: 'text'},
                {name: 'disk_member4_op', allowBlank: false, type: 'text'},
                {name: 'disk_kons1', allowBlank: false, type: 'float'},
                {name: 'disk_kons2', allowBlank: false, type: 'float'},
                {name: 'disk_kons3', allowBlank: false, type: 'float'},
                {name: 'disk_kons4', allowBlank: false, type: 'float'},
                {name: 'disk_amt_kons5', allowBlank: false, type: 'int'},
                {name: 'net_price_jual_kons', allowBlank: false, type: 'int'},
                {name: 'disk_member1', allowBlank: false, type: 'float'},
                {name: 'disk_member2', allowBlank: false, type: 'float'},
                {name: 'disk_member3', allowBlank: false, type: 'float'},
                {name: 'disk_member4', allowBlank: false, type: 'float'},
                {name: 'disk_amt_member5', allowBlank: false, type: 'int'},		
                {name: 'net_price_jual_member', allowBlank: false, type: 'int'},		
                {name: 'hrg_beli_satuan', allowBlank: false, type: 'int'},		
                {name: 'rp_cogs', allowBlank: false, type: 'int'},	
                {name: 'rp_het_cogs', allowBlank: false, type: 'int'},
                {name: 'hrg_supplier', allowBlank: false, type: 'int'},	
                {name: 'net_hrg_supplier_sup_inc', allowBlank: false, type: 'int'},	
                {name: 'rp_ongkos_kirim', allowBlank: false, type: 'int'},		
                {name: 'margin_op', allowBlank: false, type: 'text'},	
                {name: 'margin', allowBlank: false, type: 'int'},	
                {name: 'pct_margin', allowBlank: false, type: 'int'},		
                {name: 'rp_margin', allowBlank: false, type: 'int'},		
                {name: 'rp_het_harga_beli', allowBlank: false, type: 'int'},		
                {name: 'rp_jual_supermarket', allowBlank: false, type: 'int'},		
                {name: 'rp_jual_distribusi', allowBlank: false, type: 'int'},	
                {name: 'qty_beli_bonus', allowBlank: false, type: 'int'},	
                {name: 'kd_produk_bonus', allowBlank: false, type: 'text'},	
                {name: 'qty_bonus', allowBlank: false, type: 'int'},	
                {name: 'is_bonus_kelipatan', allowBlank: false, type: 'text'},	
                {name: 'qty_beli_member', allowBlank: false, type: 'int'},	
                {name: 'kd_produk_member', allowBlank: false, type: 'text'},	
                {name: 'qty_member', allowBlank: false, type: 'int'},	
                {name: 'is_member_kelipatan', allowBlank: false, type: 'text'},
                {name: 'tanggal', allowBlank: false, type: 'text'},
                {name: 'keterangan', allowBlank: false, type: 'text'},
                {name: 'approve_by', allowBlank: false, type: 'text'},
                {name: 'tgl_approve', allowBlank: false, type: 'text'},
                {name: 'tgl_start_diskon', allowBlank: false, type: 'text'},
                {name: 'tgl_end_diskon', allowBlank: false, type: 'text'},
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("harga_penjualan/search_produk_history") ?>',
            method: 'POST'
        }),
        writer: new Ext.data.JsonWriter(
        {
            encode: true,
            writeAllFields: true
        })
    });
	
    var gridhargapenjualanhistory = new Ext.grid.GridPanel({
        store: strhargapenjualanhistory,
        stripeRows: true,
        height: 400,
        frame: true,
        border:true,
        columns: [{
                header: 'Koreksi Ke',
                dataIndex: 'koreksi_produk',
                hidden: true
            },{
                header: 'Tanggal',
                dataIndex: 'tanggal',
                width: 100,
                sortable: true
            },{
                header: 'Tanggal Approval',
                dataIndex: 'tgl_approve',
                width: 100,
                sortable: true
            },{
                header: 'Kode Barang',
                dataIndex: 'kd_produk',
                width: 100,
                sortable: true
            },{
                header: 'Nama Barang',
                dataIndex: 'nama_produk',
                width: 300,
                sortable: true
            },{
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 80
            },{
                header: 'Nama Supplier',
                dataIndex: 'nama_supplier',
                width: 200
            },{
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Net Price Beli (Inc.PPN)',
                // dataIndex: 'hrg_beli_satuan',           
                dataIndex: 'net_hrg_supplier_sup_inc',           
                width: 150
            },{
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'COGS',
                // dataIndex: 'hrg_beli_satuan',           
                dataIndex: 'rp_cogs',           
                width: 120
            },{
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Ongkos Kirim',
                dataIndex: 'rp_ongkos_kirim',           
                width: 120
            },{
                header: '% / Rp',
                dataIndex: 'margin_op',
                width: 50
            },{
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Margin',
                dataIndex: 'margin',           
                width: 100
            },{
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'HET Net Price Beli (Inc.PPN)',
                dataIndex: 'rp_het_harga_beli',           
                width: 180
            },{
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'HET COGS (Inc.PPN)',
                dataIndex: 'rp_het_cogs',           
                width: 140
            },{
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Harga Jual Supermarket',
                dataIndex: 'rp_jual_supermarket',           
                width: 180
            },/*{
            xtype: 'numbercolumn',
                        align: 'right',
            format: '0,0',
            header: 'Harga Jual Distribusi',
            dataIndex: 'rp_jual_distribusi',           
            width: 180,
                },*/{
                header: '% / Rp',
                dataIndex: 'disk_kons1_op',
                width: 50
            },{
                xtype: 'numbercolumn',
                align: 'right',
                // format: '0,0',
                header: 'Diskon Konsumen 1',
                dataIndex: 'disk_kons1',           
                width: 150
            },{
                header: '% / Rp',
                dataIndex: 'disk_kons2_op',
                width: 50
            },{
                xtype: 'numbercolumn',
                align: 'right',
                // format: '0,0',
                header: 'Diskon Konsumen 2',
                dataIndex: 'disk_kons2',           
                width: 150
            },{
                header: '% / Rp',
                dataIndex: 'disk_kons3_op',
                width: 50
            },{
                xtype: 'numbercolumn',
                align: 'right',
                // format: '0,0',
                header: 'Diskon Konsumen 3',
                dataIndex: 'disk_kons3',           
                width: 150
            },{
                header: '% / Rp',
                dataIndex: 'disk_kons4_op',
                width: 50
            },{
                xtype: 'numbercolumn',
                align: 'right',
                // format: '0,0',
                header: 'Diskon Konsumen 4',
                dataIndex: 'disk_kons4',           
                width: 150
            },{
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Diskon Konsumen 5',
                dataIndex: 'disk_amt_kons5',           
                width: 150
            },{
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Net Price Jual Kons',
                dataIndex: 'net_price_jual_kons',           
                width: 150
            },{
                header: '% / Rp',
                dataIndex: 'disk_member1_op',
                width: 50
            },{
                xtype: 'numbercolumn',
                align: 'right',
                // format: '0,0',
                header: 'Diskon Member 1',
                dataIndex: 'disk_member1',           
                width: 150
            },{
                header: '% / Rp',
                dataIndex: 'disk_member2_op',
                width: 50
            },{
                xtype: 'numbercolumn',
                align: 'right',
                // format: '0,0',
                header: 'Diskon Member 2',
                dataIndex: 'disk_member2',           
                width: 150
            },{
                header: '% / Rp',
                dataIndex: 'disk_member3_op',
                width: 50
            },{
                xtype: 'numbercolumn',
                align: 'right',
                // format: '0,0',
                header: 'Diskon Member 3',
                dataIndex: 'disk_member3',           
                width: 150
            },{
                header: '% / Rp',
                dataIndex: 'disk_member4_op',
                width: 50
            },{
                xtype: 'numbercolumn',
                align: 'right',
                // format: '0,0',
                header: 'Diskon Member 4',
                dataIndex: 'disk_member4',           
                width: 150
            },{
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Diskon Member 5',
                dataIndex: 'disk_amt_member5',           
                width: 150
            },{
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Net Price Jual Member',
                dataIndex: 'net_price_jual_member',           
                width: 150
            },{
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Qty Beli |Konsumen|',
                dataIndex: 'qty_beli_bonus',           
                width: 150
            },{
                header: 'Kd Produk |Konsumen|',
                dataIndex: 'kd_produk_bonus',           
                width: 150
            },{
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Qty Bonus |Konsumen|',
                dataIndex: 'qty_bonus',           
                width: 150
            },{
                header: 'Kelipatan ? |Konsumen|',
                dataIndex: 'is_bonus_kelipatan',           
                width: 150
            },{
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Qty Beli |Member|',
                dataIndex: 'qty_beli_member',           
                width: 150
            },{
                header: 'Kd Produk |Member|',
                dataIndex: 'kd_produk_member',           
                width: 150
            },{
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Qty Bonus |Member|',
                dataIndex: 'qty_member',           
                width: 150
            },{
                header: 'Kelipatan ? |Member|',
                dataIndex: 'is_member_kelipatan',           
                width: 150            
            },{
                    xtype: 'datecolumn',
                    header: 'Tgl Mulai Diskon',
                    dataIndex: 'tgl_start_diskon',
                    format: 'd/m/Y',
                    width: 120
                    
                },{
                    xtype: 'datecolumn',
                    header: 'Tgl Akhir Diskon',
                    dataIndex: 'tgl_end_diskon',
                    format: 'd/m/Y',
                    width: 120
                    
                },{	
                header: 'Ket. Perubahan',
                dataIndex: 'keterangan',                                     
                width: 300
            },{	
                header: 'Approved By',
                dataIndex: 'approve_by',                                     
                width: 300
            }]
    });
	
    var winhargapenjualanprint = new Ext.Window({
        id: 'id_winhargapenjualanprint',
        title: 'Print History Harga Penjualan',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="hargapenjualanprint" src=""></iframe>'
    });
	
    Ext.ns('hargapenjualanform');
    hargapenjualanform.Form = Ext.extend(Ext.form.FormPanel, {
    
        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 130,
        url: '<?= site_url("harga_penjualan/update_row") ?>',
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
            hargapenjualanform.Form.superclass.constructor.call(this, config);
        },
        initComponent: function(){
        
            // hard coded - cannot be changed from outsid
            var config = {
                layout:'form',
                items: [gridhargapenjualanhistory],
                buttons: [{
                        text: 'Cetak',
                        id: 'btnCetakhargapenjualan',
                        scope: this,
                        handler: function(){
                            function isEmpty(str) {
                                return (!str || 0 === str.length);
                            }
                            var no_bukti = Ext.getCmp('id_cbhjnobukti').getValue();
                            var kd_produk = Ext.getCmp('id_cbhjproduk').getValue();
								
                            if(isEmpty(no_bukti)){
                                no_bukti = 0;
                            }
                            winhargapenjualanprint.show();
                            Ext.getDom('hargapenjualanprint').src = '<?= site_url("harga_penjualan/print_form") ?>' +'/'+no_bukti+'/'+kd_produk;
                        }
                    },{
                        text: 'Close',
                        id: 'btnClosehargapenjualan',
                        scope: this,
                        handler: function(){
                            winshowhistoryhargapenjualan.hide();
                        }
                    },]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            
            // call parent
            hargapenjualanform.Form.superclass.initComponent.apply(this, arguments);
            
        } // eo function initComponent  
        ,
        onRender: function(){
        
            // call parent
            hargapenjualanform.Form.superclass.onRender.apply(this, arguments);
            
            // set wait message target
            this.getForm().waitMsgTarget = this.getEl();
            
            // loads form after initial layout
            // this.on('afterlayout', this.onLoadClick, this, {single:true});
        
        } // eo function onRender
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
    Ext.reg('formaddhargapenjualan', hargapenjualanform.Form);
    
    var winshowhistoryhargapenjualan = new Ext.Window({
        id: 'id_winshowhistoryhargapenjualan',
        closeAction: 'hide',
        width: 1000,
        height: 500,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_formaddhargapenjualan',
            xtype: 'formaddhargapenjualan'
        },
        onHide: function(){
            Ext.getCmp('id_formaddhargapenjualan').getForm().reset();
        }
    });

    var strcbkdprodukhj = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_produk', 'nama_produk', 'jml_stok'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_barang/get_produk") ?>',
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
	
    var searchhjproduk = new Ext.app.SearchField({
        store: strcbkdprodukhj,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'hjsearchlistbarang'
    });
    
    var tbhjproduk = new Ext.Toolbar({
        items: [searchhjproduk]
    });
	
    var gridhjsearchproduk = new Ext.grid.GridPanel({
        store: strcbkdprodukhj,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'Kode Produk',
                dataIndex: 'kd_produk',
                width: 90,
                sortable: true			
            
            },{
                header: 'Nama Produk',
                dataIndex: 'nama_produk',
                width: 340,
                sortable: true         
            },{
                header: 'Qty',
                dataIndex: 'jml_stok',
                width: 50,
                sortable: true         
            }],
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {					
                    Ext.Ajax.request({
                        url: '<?= site_url("master_barang/get_row_kode_produk") ?>',
                        method: 'POST',
                        params: {
                            kd_produk: sel[0].get('kd_produk')
                        },
                        callback:function(opt,success,responseObj){
                            var de = Ext.util.JSON.decode(responseObj.responseText);
                            if(de.success==true){
                                var senders = Ext.getCmp('hj_gridsender').getValue();
                                if(senders === 'hj_kd_produk_bonus'){
                                    Ext.getCmp(senders).setValue(sel[0].get('kd_produk'));
                                }else if(senders === 'hj_kd_produk_member'){{
                                        Ext.getCmp(senders).setValue(sel[0].get('kd_produk'));
                                    }
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
                        }
                    });	
                    menuhj.hide();
                }
            }
        },
        tbar:tbhjproduk,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strcbkdprodukhj,
            displayInfo: true
        })
    });
	
	

    var menuhj = new Ext.menu.Menu();
    menuhj.add(new Ext.Panel({
        title: 'Pilih Produk',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 500,
        height: 400,
        closeAction: 'hide',
        plain: true,
        items: [gridhjsearchproduk],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menuhj.hide();
                }
            }]
    }));
		
    Ext.ux.TwinComboHj = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            Ext.getCmp('hj_gridsender').setValue(this.id);
            strcbkdprodukhj.load();
            menuhj.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    var strhargapenjualan = new Ext.data.Store({
        autoSave:false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_diskon_sales', allowBlank: true, type: 'text'},
                {name: 'koreksi_diskon', allowBlank: true, type: 'text'},
                {name: 'koreksi_produk', allowBlank: true, type: 'text'},
                {name: 'kd_produk', allowBlank: false, type: 'text'},
                {name: 'kd_produk_lama', allowBlank: false, type: 'text'},
                {name: 'nama_produk', allowBlank: false, type: 'text'},
                {name: 'nm_satuan', allowBlank: false, type: 'text'},	
                {name: 'nama_supplier', allowBlank: false, type: 'text'},	
                {name: 'disk_kons1_op', allowBlank: false, type: 'text'},
                {name: 'disk_kons2_op', allowBlank: false, type: 'text'},
                {name: 'disk_kons3_op', allowBlank: false, type: 'text'},
                {name: 'disk_kons4_op', allowBlank: false, type: 'text'},
                {name: 'disk_member1_op', allowBlank: false, type: 'text'},
                {name: 'disk_member2_op', allowBlank: false, type: 'text'},
                {name: 'disk_member3_op', allowBlank: false, type: 'text'},
                {name: 'disk_member4_op', allowBlank: false, type: 'text'},
                {name: 'disk_kons1', allowBlank: false, type: 'float'},
                {name: 'disk_kons2', allowBlank: false, type: 'float'},
                {name: 'disk_kons3', allowBlank: false, type: 'float'},
                {name: 'disk_kons4', allowBlank: false, type: 'float'},
                {name: 'disk_amt_kons5', allowBlank: false, type: 'int'},
                {name: 'net_price_jual_kons', allowBlank: false, type: 'int'},
                {name: 'disk_member1', allowBlank: false, type: 'float'},
                {name: 'disk_member2', allowBlank: false, type: 'float'},
                {name: 'disk_member3', allowBlank: false, type: 'float'},
                {name: 'disk_member4', allowBlank: false, type: 'float'},
                {name: 'disk_amt_member5', allowBlank: false, type: 'int'},		
                {name: 'net_price_jual_member', allowBlank: false, type: 'int'},		
                {name: 'hrg_beli_satuan', allowBlank: false, type: 'int'},		
                {name: 'hrg_supplier', allowBlank: false, type: 'int'},		
                {name: 'net_hrg_supplier_sup_inc', allowBlank: false, type: 'int'},
                {name: 'rp_ongkos_kirim', allowBlank: false, type: 'int'},		
                {name: 'margin_op', allowBlank: false, type: 'text'},	
                {name: 'margin', allowBlank: false, type: 'int'},	
                {name: 'pct_margin', allowBlank: false, type: 'int'},		
                {name: 'rp_margin', allowBlank: false, type: 'int'},		
                {name: 'rp_het_harga_beli', allowBlank: false, type: 'int'},	
                {name: 'rp_cogs', allowBlank: false, type: 'int'},	
                {name: 'rp_het_cogs', allowBlank: false, type: 'int'},	
                {name: 'p_rp_cogs', allowBlank: false, type: 'int'},	
                {name: 'p_rp_het_cogs', allowBlank: false, type: 'int'},	
                {name: 'rp_jual_supermarket', allowBlank: false, type: 'int'},		
                {name: 'rp_jual_distribusi', allowBlank: false, type: 'int'},	
                {name: 'qty_beli_bonus', allowBlank: false, type: 'int'},	
                {name: 'kd_produk_bonus', allowBlank: false, type: 'text'},	
                {name: 'qty_bonus', allowBlank: false, type: 'int'},	
                {name: 'is_bonus_kelipatan', allowBlank: false, type: 'text'},	
                {name: 'qty_beli_member', allowBlank: false, type: 'int'},	
                {name: 'kd_produk_member', allowBlank: false, type: 'text'},	
                {name: 'qty_member', allowBlank: false, type: 'int'},	
                {name: 'is_member_kelipatan', allowBlank: false, type: 'text'},
                {name: 'keterangan', allowBlank: false, type: 'text'},
                {name: 'approve_by', allowBlank: false, type: 'text'},
                {name: 'tgl_approve', allowBlank: false, type: 'text'},
                {name: 'is_konsinyasi', allowBlank: false, type: 'text'},
                {name: 'tgl_start_diskon', allowBlank: false, type: 'text'},
                {name: 'tgl_end_diskon', allowBlank: false, type: 'text'}
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("harga_penjualan/search_produk_by_kategori") ?>',
            method: 'POST'
        }),
        writer: new Ext.data.JsonWriter(
        {
            encode: true,
            writeAllFields: true
        })
    });
	
    strhargapenjualan.on('load',function(){
        strhargapenjualan.setBaseParam('kd_supplier',Ext.getCmp('id_cbhjsuplier').getValue());
        strhargapenjualan.setBaseParam('kd_kategori1',Ext.getCmp('hj_cbkategori1').getValue());
        strhargapenjualan.setBaseParam('kd_kategori2',Ext.getCmp('hj_cbkategori2').getValue());
        strhargapenjualan.setBaseParam('kd_kategori3',Ext.getCmp('hj_cbkategori3').getValue());
        strhargapenjualan.setBaseParam('kd_kategori4',Ext.getCmp('hj_cbkategori4').getValue());
        strhargapenjualan.setBaseParam('kd_ukuran',Ext.getCmp('id_hj_cbukuran').getValue());
        strhargapenjualan.setBaseParam('no_bukti',Ext.getCmp('id_cbhjnobuktifilter').getValue());
        strhargapenjualan.setBaseParam('konsinyasi',Ext.getCmp('hj_konsinyasi').getValue());
    })
	
    strhargapenjualan.on('update',function(){
        var net_price = Ext.getCmp('ehj_hrg_beli_satuan').getValue();
        var edited = Ext.getCmp('hj_edited').getValue();
        if(net_price == 0 && edited == 'Y'){
            Ext.Msg.show({
                title: 'Warning',
                msg: 'Net Price Pembelian Masih 0',
                modal: true,
                icon: Ext.Msg.ERROR,
                buttons: Ext.Msg.OK
            });
            return;
        }
        if(Ext.getCmp('ehj_het_cogs').getValue() == 0){
            if(Ext.getCmp('ehj_rp_jual_supermarket').getValue() < Ext.getCmp('ehj_rp_het_harga_beli').getValue()){
                Ext.getCmp('ehj_rp_jual_supermarket').setValue('0');
                Ext.Msg.show({
                    title: 'Error',
                    msg: 'Net Price Jual tidak boleh lebih kecil dari HET Net Price Beli (inc PPN 1)',
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK,
                    fn: function(btn){
                        Ext.getCmp('ehj_rp_jual_supermarket').focus();
                    }
                });
                Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');  
            }
            if(Ext.getCmp('ehj_rp_cogs').getValue() > 0 && Ext.getCmp('ehj_is_konsinyasi').getValue() === '0'){
                if(Ext.getCmp('ehj_net_price_jual_kons').getValue() < Ext.getCmp('ehj_rp_cogs').getValue()){
                    Ext.getCmp('ehj_net_price_jual_kons').setValue('0');
                    Ext.Msg.show({
                        title: 'Error',
                        msg: 'Net Price Jual tidak boleh lebih kecil dari HET COGS (inc PPN)',
                        modal: true,
                        icon: Ext.Msg.ERROR,
                        buttons: Ext.Msg.OK,
                        fn: function(btn){
                            Ext.getCmp('ehj_net_price_jual_kons').focus();
                        }
                    });
                    Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');  
                }
            }
        }else{
            if(Ext.getCmp('ehj_rp_jual_supermarket').getValue() < Ext.getCmp('ehj_het_cogs').getValue() && Ext.getCmp('ehj_is_konsinyasi').getValue() === '0'){
                Ext.getCmp('ehj_rp_jual_supermarket').setValue('0');
                Ext.Msg.show({
                    title: 'Error',
                    msg: 'Net Price Jual tidak boleh lebih kecil dari HET COGS (inc PPN)',
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK,
                    fn: function(btn){
                        Ext.getCmp('ehj_rp_jual_supermarket').focus();
                    }
                });
                Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');  
            }
            //if(Ext.getCmp('ehj_net_price_jual_kons').getValue() < Ext.getCmp('ehj_rp_het_harga_beli').getValue()){
            //	Ext.getCmp('ehj_net_price_jual_kons').setValue('0');
            //	Ext.Msg.show({
            //		title: 'Error',
            //		msg: 'Net Price Jual tidak boleh lebih kecil dari HET Net Price Beli (inc PPN 3)',
            //		modal: true,
            //		icon: Ext.Msg.ERROR,
            //		buttons: Ext.Msg.OK,
            //		fn: function(btn){
            //			Ext.getCmp('ehj_net_price_jual_kons').focus();
            //		}
            //	});
            //	Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');  
            //}
		
        }
        /*
                if(Ext.getCmp('ehj_rp_jual_distribusi').getValue() < Ext.getCmp('ehj_rp_het_harga_beli').getValue()){
                        Ext.getCmp('ehj_rp_jual_distribusi').setValue('0');
                        Ext.Msg.show({
                                title: 'Error',
                                msg: 'Harga Jual Tidak Boleh Lebih Kecil Dari HET Net Price Beli',
                                modal: true,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OK,
                                fn: function(btn){
                                        Ext.getCmp('ehj_rp_jual_distribusi').focus();
                                }
                        });
                        Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');  
                }
         */		
    });
	
    function HETChange(){
        var hrg_beli = Ext.getCmp('ehj_hrg_beli_satuan').getValue();
        var cogs = Ext.getCmp('ehj_rp_cogs').getValue();
        var ongkos = Ext.getCmp('ehj_rp_ongkos_kirim').getValue();
        var margin_op = Ext.getCmp('hj_margin_op').getValue()
        var margin = Ext.getCmp('ehj_margin').getValue();
        var margin_rp = 0;
        if(margin_op == "%"){
            margin_rp = (margin*hrg_beli)/100;
            margin_pct = margin;
        }else{
            margin_rp = margin;
            margin_pct = (margin*100)/hrg_beli;
        }
        ongkos = ongkos+(ongkos*0.1);
        var HET = hrg_beli+ongkos+margin_rp;
			

        if(margin_op == "%"){
            margin_rp = (margin*cogs)/100;
            margin_pct = margin;
        }else{
            margin_rp = margin;
            margin_pct = (margin*100)/cogs;
        }
        ongkos = Ext.getCmp('ehj_rp_ongkos_kirim').getValue();
        var HETCOGS = (cogs+ongkos+margin_rp) * 1.1;
        if(cogs == 0){
            HETCOGS = 0;
        }
        Ext.getCmp('ehj_rp_het_harga_beli').setValue(HET);
        Ext.getCmp('ehj_het_cogs').setValue(HETCOGS);
        Ext.getCmp('ehj_pct_margin').setValue(margin_pct);
        Ext.getCmp('ehj_rp_margin').setValue(margin_rp);
        Edited();
    };
	
    function Edited(){
        Ext.getCmp('hj_edited').setValue('Y');
    };
	
    function HitungNetPJual(){
        console.log("STart");
        Edited();
        var total_disk = 0;
        var rp_jual_supermarket = Ext.getCmp('ehj_rp_jual_supermarket').getValue();
        var disk_kons1_op = Ext.getCmp('hj_disk_kons1_op').getValue();
        var disk_kons1 = Ext.getCmp('hj_disk_kons1').getValue();
        if (disk_kons1_op == '%'){
            total_disk = rp_jual_supermarket-(rp_jual_supermarket*(disk_kons1/100));
        }else{
            total_disk = rp_jual_supermarket-disk_kons1;				
        }
	console.log("HJ _1");		
        var disk_kons2_op = Ext.getCmp('hj_disk_kons2_op').getValue();
        var disk_kons2 = Ext.getCmp('hj_disk_kons2').getValue();
        if (disk_kons2_op == '%'){
            // disk_kons2 = (disk_kons2*disk_kons1)/100;
            total_disk =  total_disk-(total_disk*(disk_kons2/100));
        }else{
            total_disk = total_disk-disk_kons2;				
        }
			
        var disk_kons3_op = Ext.getCmp('hj_disk_kons3_op').getValue();
        var disk_kons3 = Ext.getCmp('hj_disk_kons3').getValue();
        if (disk_kons3_op == '%'){
            // disk_kons3 = (disk_kons3*disk_kons2)/100;
            total_disk = total_disk-(total_disk*(disk_kons3/100));
        }else{
            total_disk = total_disk-disk_kons3;				
        }
	console.log("HJ _2");			
        var disk_kons4_op = Ext.getCmp('hj_disk_kons4_op').getValue();
        var disk_kons4 = Ext.getCmp('hj_disk_kons4').getValue();
        if (disk_kons4_op == '%'){
            // disk_kons4 = (disk_kons4*disk_kons3)/100;
            total_disk = total_disk-(total_disk*(disk_kons4/100));
        }else{
            total_disk = total_disk-disk_kons4;				
        }
			
        var total_disk = total_disk-Ext.getCmp('hj_disk_kons5').getValue();

        var net_jual_kons = total_disk;
        Ext.getCmp('ehj_net_price_jual_kons').setValue(net_jual_kons);
			
			
        var disk_member1_op = Ext.getCmp('hj_disk_member1_op').getValue();
        var disk_member1 = Ext.getCmp('hj_disk_member1').getValue();
        if (disk_member1_op == '%'){
            // disk_member1 = (disk_member1*rp_jual_supermarket)/100;
            total_disk = rp_jual_supermarket-(rp_jual_supermarket*(disk_member1/100));
        }else{
            total_disk = rp_jual_supermarket-disk_member1;				
        }
	console.log("HJ _3");			
        var disk_member2_op = Ext.getCmp('hj_disk_member2_op').getValue();
        var disk_member2 = Ext.getCmp('hj_disk_member2').getValue();
        if (disk_member2_op == '%'){
            // disk_member2 = (disk_member2*disk_member1)/100;
            total_disk = total_disk-(total_disk*(disk_member2/100));
        }else{
            total_disk = total_disk-disk_member2;				
        }
			
        var disk_member3_op = Ext.getCmp('hj_disk_member3_op').getValue();
        var disk_member3 = Ext.getCmp('hj_disk_member3').getValue();
        if (disk_member3_op == '%'){
            // disk_member3 = (disk_member3*disk_member2)/100;
            total_disk = total_disk-(total_disk*(disk_member3/100));
        }else{
            total_disk = total_disk-disk_member3;				
        }
	console.log("HJ _4");			
        var disk_member4_op = Ext.getCmp('hj_disk_member4_op').getValue();
        var disk_member4 = Ext.getCmp('hj_disk_member4').getValue();
        if (disk_member4_op == '%'){
            // disk_member4 = (disk_member4*disk_member3)/100;
            total_disk = total_disk-(total_disk*(disk_member4/100));
        }else{
            total_disk = total_disk-disk_member4;				
        }
			
        var total_disk = total_disk - Ext.getCmp('hj_disk_amt_member5').getValue();

        var net_price_memb = total_disk;
        Ext.getCmp('ehj_net_price_jual_member').setValue(net_price_memb);			
    }
    // combobox kategori1
    var str_hj_cbkategori1 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori1', 'nama_kategori1'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("kategori2/get_kategori1") ?>',
            method: 'POST'
        }),
        listeners: {
            load: function() {
                var r = new (str_hj_cbkategori1.recordType)({
                    'kd_kategori1': '',
                    'nama_kategori1': '-----'
                });
                str_hj_cbkategori1.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var hj_cbkategori1 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 1',
        id: 'hj_cbkategori1',
        store: str_hj_cbkategori1,
        valueField: 'kd_kategori1',
        displayField: 'nama_kategori1',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori1',
        emptyText: 'Pilih kategori 1',
        listeners: {
            'select': function(combo, records) {
                var kdhj_cbkategori1 = hj_cbkategori1.getValue();
                // hj_cbkategori2.setValue();
                hj_cbkategori2.store.proxy.conn.url = '<?= site_url("kategori3/get_kategori2") ?>/' + kdhj_cbkategori1;
                hj_cbkategori2.store.reload();
				            
            }
        }
    });
    // combobox kategori2
    var str_hj_cbkategori2 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori2', 'nama_kategori2'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({			
            url: '<?= site_url("kategori3/get_kategori2") ?>',
            method: 'POST'
        }),
        listeners: {
            load: function() {
                var r = new (str_hj_cbkategori2.recordType)({
                    'kd_kategori2': '',
                    'nama_kategori2': '-----'
                });
                str_hj_cbkategori2.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var hj_cbkategori2 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 2 ',
        id: 'hj_cbkategori2',
        mode: 'local',
        store: str_hj_cbkategori2,
        valueField: 'kd_kategori2',
        displayField: 'nama_kategori2',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori2',
        emptyText: 'Pilih kategori 2',
        listeners: {
            select: function(combo, records) {
                var kd_hj_cbkategori1 = hj_cbkategori1.getValue();
                var kd_hj_cbkategori2 = this.getValue();
                hj_cbkategori3.setValue();
                hj_cbkategori3.store.proxy.conn.url = '<?= site_url("kategori4/get_kategori3") ?>/' + kd_hj_cbkategori1 +'/'+ kd_hj_cbkategori2;
                hj_cbkategori3.store.reload();
				          
            }
        }
    });
	
    // combobox kategori3
    var str_hj_cbkategori3 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori3', 'nama_kategori3'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("kategori4/get_kategori3") ?>',
            method: 'POST'
        }),
        listeners: {
            load: function() {
                var r = new (str_hj_cbkategori3.recordType)({
                    'kd_kategori3': '',
                    'nama_kategori3': '-----'
                });
                str_hj_cbkategori3.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
	
    var hj_cbkategori3 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 3 ',
        id: 'hj_cbkategori3',
        mode: 'local',
        store: str_hj_cbkategori3,
        valueField: 'kd_kategori3',
        displayField: 'nama_kategori3',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori3',
        emptyText: 'Pilih kategori 3',
        listeners: {
            select: function(combo, records) {
                var kd_hj_cbkategori1 = hj_cbkategori1.getValue();
                var kd_hj_cbkategori2 = hj_cbkategori2.getValue();
                var kd_hj_cbkategori3 = this.getValue();
                hj_cbkategori4.setValue();
                hj_cbkategori4.store.proxy.conn.url = '<?= site_url("master_barang/get_kategori4") ?>/' + kd_hj_cbkategori1 +'/'+ kd_hj_cbkategori2 +'/'+ kd_hj_cbkategori3;
                hj_cbkategori4.store.reload();				
				           

            }
        }
    });
	
    // combobox kategori4
    var str_hj_cbkategori4 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori4', 'nama_kategori4'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_barang/get_kategori4") ?>',
            method: 'POST'
        }),
        listeners: {
            load: function() {
                var r = new (str_hj_cbkategori4.recordType)({
                    'kd_kategori4': '',
                    'nama_kategori4': '-----'
                });
                str_hj_cbkategori4.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var hj_cbkategori4 = new Ext.form.ComboBox({   
        fieldLabel: 'Kategori 4 ',
        id: 'hj_cbkategori4',
        mode: 'local',
        store: str_hj_cbkategori4,
        valueField: 'kd_kategori4',
        displayField: 'nama_kategori4',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori4',
        emptyText: 'Pilih kategori 4',
        listeners: {
            select: function(combo, records) {
            }
        }
    });
    
     // combobox Ukuran
	var str_hj_cbukuran = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_ukuran', 'nama_ukuran'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_barang/get_ukuran_produk") ?>',
            method: 'POST'
        }),
		listeners: {
            load: function() {
                var r = new (str_hj_cbukuran.recordType)({
                    'kd_ukuran': '',
                    'nama_ukuran': '-----'
                });
                str_hj_cbukuran.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
    
    // combobox Satuan
	var str_hj_cbsatuan = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_satuan', 'nm_satuan'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_barang/get_satuan_produk") ?>',
            method: 'POST'
        }),
		listeners: {
             load: function() {
                var r = new (str_hj_cbsatuan.recordType)({
                    'kd_satuan': '',
                    'nm_satuan': '-----'
                });
                str_hj_cbsatuan.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
    var hj_cbsatuan = new Ext.form.ComboBox({
        fieldLabel: 'Satuan',
        id: 'id_hj_cbsatuan',
        store: str_hj_cbsatuan,
        valueField: 'kd_satuan',
        displayField: 'nm_satuan',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: true,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_satuan',
        emptyText: 'Pilih Satuan'
       
    });
    
    var hj_cbukuran = new Ext.form.ComboBox({
        fieldLabel: 'Ukuran',
        id: 'id_hj_cbukuran',
        store: str_hj_cbukuran,
        valueField: 'kd_ukuran',
        displayField: 'nama_ukuran',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: true,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_ukuran',
        emptyText: 'Pilih Ukuran'
       
    });
	
    var searchgridhargapenjualan = new Ext.app.SearchField({
        store: strhargapenjualan,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridhargapenjualan',
        emptyText: 'Kode Barang, Kode Barang Lama, Nama Barang'
    });
	
    searchgridhargapenjualan.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';
			
            // Get the value of search field
            var kd_kategori1 = Ext.getCmp('hj_cbkategori1').getValue();
            var kd_kategori2 = Ext.getCmp('hj_cbkategori2').getValue();
            var kd_kategori3 = Ext.getCmp('hj_cbkategori3').getValue();
            var kd_kategori4 = Ext.getCmp('hj_cbkategori4').getValue();
            var konsinyasi = Ext.getCmp('hj_konsinyasi').getValue();
            var kd_supplier = Ext.getCmp('id_cbhjsuplier').getValue();
            var list = Ext.getCmp('ehj_list').getValue();
            if(!kd_supplier){
                Ext.Msg.show({
                    title: 'Error',
                    msg: 'Silahkan Pilih Supplier Terlebih Dahulu',
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK,
                    // fn: function(btn){
                    // if (btn == 'ok' && msg == 'Session Expired') {
                    // window.location = '<?= site_url("auth/login") ?>';
                    // }
                    // }
                });
                return;
            }
            var o = { 	start: 0, 
                kd_kategori1: kd_kategori1,
                kd_kategori2: kd_kategori2,
                kd_kategori3: kd_kategori3,
                kd_kategori4: kd_kategori4,						
                konsinyasi: konsinyasi,						
                kd_supplier: kd_supplier,						
                list: list,						
            };
			
            this.store.baseParams = this.store.baseParams || {};
            this.store.baseParams[this.paramName] = '';
            this.store.reload({
                params : o
            });
            this.triggers[0].hide();
            this.hasSearch = false;
        }
    };
	
    searchgridhargapenjualan.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }

        // Get the value of search field
        var kd_kategori1 = Ext.getCmp('hj_cbkategori1').getValue();
        var kd_kategori2 = Ext.getCmp('hj_cbkategori2').getValue();
        var kd_kategori3 = Ext.getCmp('hj_cbkategori3').getValue();
        var kd_kategori4 = Ext.getCmp('hj_cbkategori4').getValue();
        var konsinyasi = Ext.getCmp('hj_konsinyasi').getValue();
        var kd_supplier = Ext.getCmp('id_cbhjsuplier').getValue();
        var list = Ext.getCmp('ehj_list').getValue();
        if(!kd_supplier){
            Ext.Msg.show({
                title: 'Error',
                msg: 'Silahkan Pilih Supplier Terlebih Dahulu',
                modal: true,
                icon: Ext.Msg.ERROR,
                buttons: Ext.Msg.OK
            });
            return;
        }
        var o = { 	start: 0, 
            kd_kategori1: kd_kategori1,
            kd_kategori2: kd_kategori2,
            kd_kategori3: kd_kategori3,
            kd_kategori4: kd_kategori4,						
            konsinyasi: konsinyasi,						
            kd_supplier: kd_supplier,
            list: list
        };
	 
        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params:o});
        this.hasSearch = true;
        this.triggers[0].show();
    };
	
    var strcbhjsuplier = new Ext.data.ArrayStore({
        fields: ['kd_supplier'],
        data : []
    });
	
    var strgridhjsuplier = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_supplier', 'nama_supplier', 'pkp'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("harga_pembelian/search_supplier") ?>', 	
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
	
    var searchgridhjsuplier = new Ext.app.SearchField({
        store: strgridhjsuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridhjsuplier'
    });
	
    strgridhjsuplier.on('load', function(){
        Ext.getCmp('id_searchgridhjsuplier').focus();
    });
	
    var gridhjsuplier = new Ext.grid.GridPanel({
        store: strgridhjsuplier,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'Kode Supplier',
                dataIndex: 'kd_supplier',
                width: 80,
                sortable: true,			
            
            },{
                header: 'Nama Supplier',
                dataIndex: 'nama_supplier',
                width: 300,
                sortable: true,         
            },{
                header: 'PKP',
                dataIndex: 'pkp',
                width: 300,
                sortable: true,         
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridhjsuplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridhjsuplier,
            displayInfo: true
        }),listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {	
                    Ext.getCmp('id_cbhjsuplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('hj_nama_supplier').setValue(sel[0].get('nama_supplier'));
                    if(sel[0].get('pkp') == '1'){
                        Ext.getCmp('hj_pkp').setValue('YA');
                    }else{
                        Ext.getCmp('hj_pkp').setValue('TIDAK');
                    }
					
					           
                    menuhjsuplier.hide();
                }
            }
        }
    });
	
    var menuhjsuplier = new Ext.menu.Menu();
    menuhjsuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridhjsuplier],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menuhjsuplier.hide();
                }
            }]
    }));
    
    Ext.ux.TwinCombohjsuplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgridhjsuplier.load();
            menuhjsuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menuhjsuplier.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridhjsuplier').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgridhjsuplier').setValue('');
            searchgridhjsuplier.onTrigger2Click();
        }
    });
	
    var cbhjsuplier = new Ext.ux.TwinCombohjsuplier({
        fieldLabel: 'Supplier <span class="asterix">*</span>',
        id: 'id_cbhjsuplier',
        store: strcbhjsuplier,
        mode: 'local',
        valueField: 'kd_supplier',
        displayField: 'kd_supplier',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_supplier',
        emptyText: 'Pilih Supplier'
    });
	
    var headerhargapenjualan = {
        layout: 'column',
        border: false,
        buttonAlign:'left',
        items: [{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [{
                        xtype: 'hidden',
                        name: 'gridsender',
                        id: 'hj_gridsender'
                    },{
                        xtype: 'textfield',
                        fieldLabel: 'No Bukti',
                        name: 'no_hj',
                        readOnly:true,
                        fieldClass:'readonly-input',
                        id: 'hj_no_hj',                
                        anchor: '90%',
                        value:''
                    },cbhjsuplier,cbhjnobuktifilter,
                    hj_cbkategori1,hj_cbkategori2,{
                        xtype: 'textarea',
                        fieldLabel: 'Kode Barang, Kode Barang Lama',
                        style:'text-transform: uppercase',
                        name: 'list',     
                        id: 'ehj_list',                 
                        anchor: '90%'
                    },{
                        xtype: 'label',
                        text: '*) Tidak Boleh Ada Spasi dan Enter',
                        style: 'margin-left:100px'
                    },new Ext.form.Checkbox({
                        xtype: 'checkbox',
                        fieldLabel: 'Hanya Konsinyasi',
                        boxLabel:'Ya',
                        name:'is_konsinyasi',
                        id:'hj_konsinyasi',
                        inputValue: '1',
                        autoLoad : true
                    })
                    ,{
                        xtype: 'label',
                        text: '*) Item Barang dengan Harga Beli Nol (0) tidak muncul pada table di bawah',
                        style: 'margin-left:100px'
                    }
                ]
            }, {
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [ {
                        xtype: 'datefield',
                        fieldLabel: 'Tanggal ',
                        name: 'tanggal',				
                        allowBlank:false,   
                        format:'d-m-Y',  
                        editable:false,           
                        id: 'hj_tanggal',                
                        anchor: '90%',
                        value: ''
                    },{
                        xtype: 'textfield',
                        fieldLabel: 'Nama Supplier',
                        name: 'nama_supplier',
                        readOnly:true,
                        fieldClass:'readonly-input',
                        id: 'hj_nama_supplier',                
                        anchor: '90%',
                        value:''
                    },{
                        xtype: 'textfield',
                        fieldLabel: 'Status PKP',
                        name: 'pkp',
                        readOnly:true,
                        fieldClass:'readonly-input',
                        id: 'hj_pkp',                
                        anchor: '90%',
                        value:''
                    },hj_cbkategori3,hj_cbkategori4,hj_cbukuran,hj_cbsatuan
                    
                ]
            },]
        ,
        buttons: [{
                text: 'Filter',
                formBind: true,
                handler: function(){
                    var kd_supplier =  Ext.getCmp('id_cbhjsuplier').getValue();
                    if(!kd_supplier){
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Silahkan Pilih Supplier Terlebih Dahulu',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                        });
                        return;
                    }
                    strhargapenjualan.load({
                        params:{
                            start: STARTPAGE,
                            limit: ENDPAGE,
                            kd_supplier: Ext.getCmp('id_cbhjsuplier').getValue(),
                            kd_kategori1: Ext.getCmp('hj_cbkategori1').getValue(),
                            kd_kategori2: Ext.getCmp('hj_cbkategori2').getValue(),
                            kd_kategori3: Ext.getCmp('hj_cbkategori3').getValue(),
                            kd_kategori4: Ext.getCmp('hj_cbkategori4').getValue(),
                            kd_ukuran: Ext.getCmp('id_hj_cbukuran').getValue(),
                            kd_satuan: Ext.getCmp('id_hj_cbsatuan').getValue(),
                            konsinyasi: Ext.getCmp('hj_konsinyasi').getValue(),
                            no_bukti: Ext.getCmp('id_cbhjnobuktifilter').getValue(),
                            list: Ext.getCmp('ehj_list').getValue()
                        }
                    }); 
                }
            }]
    }
	
    var actionhargapenjualan = new Ext.ux.grid.RowActions({
        header :'History',
        autoWidth: false,
        locked: true,
        width: 60,
        actions:[{iconCls: 'icon-history-record', qtip: 'Show History'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    }); 
   
    
    actionhargapenjualan.on('action', function(grid, record, action, row, col) {
        var kd_supp = record.get('kd_supplier');
        var kd_prod = record.get('kd_produk');
        var nm_prod = record.get('nama_produk');
        switch(action) {
            case 'icon-history-record':
                var sm = gridhargapenjualan.getSelectionModel();
                var sel = sm.getSelections();
                gridhargapenjualanhistory.store.proxy.conn.url = '<?= site_url("harga_penjualan/search_produk_history") ?>/' +sel[0].get('kd_produk');
                gridhargapenjualanhistory.store.reload();
                winshowhistoryhargapenjualan.setTitle('History');
                winshowhistoryhargapenjualan.show();
                break;            
            }
        });  
        var editorhargapenjualan = new Ext.ux.grid.RowEditor({
            saveText: 'Update'		
        });

        var gridhargapenjualan = new Ext.grid.GridPanel({
            store: strhargapenjualan,
            stripeRows: true,
            height: 350,
            loadMask: true,
            frame: true,
            border:true,
            plugins: [editorhargapenjualan],
            columns: [new Ext.grid.RowNumberer({width: 30}),{
                    dataIndex: 'kd_diskon_sales',
                    hidden: true,
                },{
                    dataIndex: 'pct_margin',
                    hidden: true,
                    editor: new Ext.form.TextField({
                        readOnly: true,
                        id: 'ehj_pct_margin'
                    })			
                },{
                    dataIndex: 'rp_margin',
                    hidden: true,
                    editor: new Ext.form.TextField({
                        readOnly: true,
                        id: 'ehj_rp_margin'
                    })
                },{
                    dataIndex: 'koreksi_diskon',
                    hidden: true
                },{
                    dataIndex: 'koreksi_produk',
                    hidden: true
                },{
                    // header: 'Edited',
                    // dataIndex: 'edited',
                    // width: 50,
                    // sortable: true,
                    // editor: new Ext.form.TextField({
                    // readOnly: true,
                    // fieldClass: 'readonly-input',
                    // id: 'hj_edited'
                    // })
                    // },{
                    header: 'Edited',
                    dataIndex: 'edited',
                    width: 50,
                    sortable: true,
                    editor: {
                        xtype:          'combo',
                        store:          new Ext.data.JsonStore({
                            fields : ['name'],
                            data   : [
                                {name : 'Y'},
                                {name : 'No'},
                            ]
                        }),
                        id:           	'hj_edited',
                        mode:           'local',
                        name:           'edited',
                        value:          '%',
                        width:			50,
                        editable:       false,
                        hiddenName:     'edited',
                        valueField:     'name',
                        displayField:   'name',
                        triggerAction:  'all',
                        forceSelection: true
                        // listeners:{
                        // 'expand':function(){
                        // Ext.getCmp('ehj_margin').setValue(0);
                        // HETChange();
                        // },
                        // select:function(){
                        // HETChange();
                        // Ext.getCmp('ehj_margin').setMaxValue(Number.MAX_VALUE);
                        // if (this.getValue() === 'persen') 
                        // Ext.getCmp('ehj_margin').maxValue = 100;
                        // else 
                        // Ext.getCmp('ehj_margin').maxLength = 11;
                        // }
                        // }
                    }
                },{
                    header: 'Kode Barang',
                    dataIndex: 'kd_produk',
                    width: 100,
                    sortable: true,
                    editor: new Ext.form.TextField({
                        readOnly: true,
                        fieldClass: 'readonly-input',
                        id: 'ehj_kd_produk'
                    })
                },{
                    header: 'Kode Barang Lama',
                    dataIndex: 'kd_produk_lama',
                    width: 110,
                    sortable: true,
                    editor: new Ext.form.TextField({
                        readOnly: true,
                        fieldClass: 'readonly-input',
                        id: 'ehj_kd_produk_lama'
                    })
                },{
                    header: 'Nama Barang',
                    dataIndex: 'nama_produk',
                    width: 300,
                    sortable: true,
                    editor: new Ext.form.TextField({
                        readOnly: true,
                        fieldClass: 'readonly-input',
                        id: 'ehj_nama_produk'
                    })
                },{
                    header: 'Satuan',
                    dataIndex: 'nm_satuan',
                    width: 80,
                    editor: new Ext.form.TextField({
                        readOnly: true,
                        fieldClass: 'readonly-input',
                        id: 'ehj_satuan'
                    })
                },{
                    header: 'Nama Supplier',
                    dataIndex: 'nama_supplier',
                    width: 130,
                    editor: new Ext.form.TextField({
                        readOnly: true,
                        fieldClass: 'readonly-input',
                        id: 'ehj_nama_supplier'
                    })
                },{
                    xtype: 'numbercolumn',
                    align: 'right',
                    format: '0,0',
                    header: 'Net Price Pembelian',       
                    dataIndex: 'net_hrg_supplier_sup_inc',           
                    width: 150,
                    editor: {
                        xtype: 'numberfield',
                        id: 'ehj_hrg_beli_satuan',
                        readOnly: true,
                        fieldClass: 'readonly-input'
                    }
                },{
                    xtype: 'numbercolumn',
                    align: 'right',
                    format: '0,0',
                    header: 'COGS',           
                    dataIndex: 'p_rp_cogs',           
                    width: 100,
                    editor: {
                        xtype: 'numberfield',
                        id: 'ehj_rp_cogs',
                        readOnly: true,
                        fieldClass: 'readonly-input'
                    }
                },{
                    xtype: 'numbercolumn',
                    align: 'right',
                    format: '0,0',
                    header: 'Ongkos Kirim',
                    dataIndex: 'rp_ongkos_kirim',           
                    width: 140,
                    editor: {
                        xtype: 'numberfield',
                        id: 'ehj_rp_ongkos_kirim',
                        listeners:{
                            'render': function(c) {
                                c.getEl().on('keyup', function() {
                                    HETChange();
                                }, c);
                            }
                        }
                    }
                },{
                    header: '% / Rp',
                    dataIndex: 'margin_op',
                    width: 50,
                    editor: {
                        xtype:          'combo',
                        store:          new Ext.data.JsonStore({
                            fields : ['name'],
                            data   : [
                                {name : '%'},
                                {name : 'Rp'}
                            ]
                        }),
                        id:           	'hj_margin_op',
                        mode:           'local',
                        name:           'margin_op',
                        value:          '%',
                        width:			50,
                        editable:       false,
                        hiddenName:     'margin_op',
                        valueField:     'name',
                        displayField:   'name',
                        triggerAction:  'all',
                        forceSelection: true,
                        listeners:{
                            'expand':function(){
                                Ext.getCmp('ehj_margin').setValue(0);
                                HETChange();
                            },
                            select:function(){
                                HETChange();
                                Ext.getCmp('ehj_margin').setMaxValue(Number.MAX_VALUE);
                                if (this.getValue() === 'persen') 
                                    Ext.getCmp('ehj_margin').maxValue = 100;
                                else 
                                    Ext.getCmp('ehj_margin').maxLength = 11;
                            }
                        }
                    }
                },{
                    xtype: 'numbercolumn',
                    align: 'right',
                    format: '0,0',
                    header: 'Margin',
                    dataIndex: 'margin',           
                    width: 100,
                    editor: {
                        xtype: 'numberfield',
                        id: 'ehj_margin',
                        listeners:{
                            'render': function(c) {
                                c.getEl().on('keyup', function() {
                                    HETChange();
                                }, c);
                            }
                        }
                    }
                },{
                    xtype: 'numbercolumn',
                    align: 'right',
                    format: '0,0',
                    header: 'HET Net Price Beli (Inc.PPN)',
                    dataIndex: 'rp_het_harga_beli',           
                    width: 180,
                    editor: {
                        xtype: 'numberfield',
                        id: 'ehj_rp_het_harga_beli',
                        readOnly: true
                    }
                },{
                    xtype: 'numbercolumn',
                    align: 'right',
                    format: '0,0',
                    header: 'HET COGS (Inc.PPN)',
                    dataIndex: 'p_rp_het_cogs',           
                    width: 140,
                    editor: {
                        xtype: 'numberfield',
                        id: 'ehj_het_cogs',
                        readOnly: true
                    }
                },{
                    xtype: 'numbercolumn',
                    align: 'right',
                    format: '0,0',
                    header: 'Harga Jual Supermarket',
                    dataIndex: 'rp_jual_supermarket',           
                    width: 180,
                    editor: {
                        xtype: 'numberfield',
                        id: 'ehj_rp_jual_supermarket',
                        listeners:{			
                            'change': function() {
                                if(Ext.getCmp('ehj_rp_cogs').getValue() > 0 && Ext.getCmp('ehj_is_konsinyasi').getValue() === '0'){
                                    if(this.getValue() < Ext.getCmp('ehj_rp_cogs').getValue()){
                                        this.setValue('0');
                                        Ext.Msg.show({
                                            title: 'Error',
                                            msg: 'Net Price Jual tidak boleh lebih kecil dari HET COGS (inc PPN)',
                                            modal: true,
                                            icon: Ext.Msg.ERROR,
                                            buttons: Ext.Msg.OK,
                                            fn: function(btn){
                                                this.focus();
                                            }
                                        });
                                        Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');  
                                    }
                                }else{
                                    if(this.getValue() < Ext.getCmp('ehj_rp_het_harga_beli').getValue() ){
                                        this.setValue('0');
                                        Ext.Msg.show({
                                            title: 'Error',
                                            msg: 'Net Price Jual tidak boleh lebih kecil dari HET Net Price Beli (inc PPN)',
                                            modal: true,
                                            icon: Ext.Msg.ERROR,
                                            buttons: Ext.Msg.OK,
                                            fn: function(btn){
                                                this.focus();
                                            }
                                        });
                                        Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');  
                                    }
                                }
						
					  
                            },'render': function(c) {
                                c.getEl().on('keyup', function() {
                                    // Edited();
                                    HitungNetPJual();
                                }, c);
                            }
                        }
                    }
                },{
                    header: '% / Rp',
                    dataIndex: 'disk_kons1_op',
                    width: 50,
                    editor: {
                        xtype:          'combo',
                        store:          new Ext.data.JsonStore({
                            fields : ['name'],
                            data   : [
                                {name : '%'},
                                {name : 'Rp'}
                            ]
                        }),
                        id:           	'hj_disk_kons1_op',
                        mode:           'local',
                        name:           'disk_kons1_op',
                        value:          '%',
                        width:			50,
                        editable:       false,
                        hiddenName:     'disk_kons1_op',
                        valueField:     'name',
                        displayField:   'name',
                        triggerAction:  'all',
                        forceSelection: true,
                        listeners:{
                            'expand':function(){
                                Ext.getCmp('hj_disk_kons1').setValue(0);
                            },
                            select:function(){
                                Ext.getCmp('hj_disk_kons1').setMaxValue(Number.MAX_VALUE);
                                if (this.getValue() === 'persen') 
                                    Ext.getCmp('hj_disk_kons1').maxValue = 100;
                                else 
                                    Ext.getCmp('hj_disk_kons1').maxLength = 11;
                            }
                        }
                    }
                },{
                    xtype: 'numbercolumn',
                    align: 'right',
                    // format: '0,0',
                    header: 'Diskon Konsumen 1',
                    dataIndex: 'disk_kons1',           
                    width: 150,
                    editor: {
                        xtype: 'numberfield',
                        msgTarget: 'under',
                        flex:1,
                        width:115,
                        name : 'disk_kons1',
                        id: 'hj_disk_kons1',
                        style: 'text-align:right;',
                        listeners:{
                            'render': function(c) {
                                c.getEl().on('keyup', function() {
                                    console.log('mulai hitung');
                                    //Ext.getCmp('hj_edited').setValue('Y');
                                    HitungNetPJual();
                                                                        
                                }, c);
                            }
					  
                        }
                    }
                },{
                    header: '% / Rp',
                    dataIndex: 'disk_kons2_op',
                    width: 50,
                    editor: {
                        xtype:          'combo',
                        store:          new Ext.data.JsonStore({
                            fields : ['name'],
                            data   : [
                                {name : '%'},
                                {name : 'Rp'}
                            ]
                        }),
                        id:           	'hj_disk_kons2_op',
                        mode:           'local',
                        name:           'disk_kons2_op',
                        value:          '%',
                        width:			50,
                        editable:       false,
                        hiddenName:     'disk_kons2_op',
                        valueField:     'name',
                        displayField:   'name',
                        triggerAction:  'all',
                        forceSelection: true,
                        listeners:{
                            'expand':function(){
                                Ext.getCmp('hj_disk_kons2').setValue(0);
                            },
                            select:function(){
                                Ext.getCmp('hj_disk_kons2').setMaxValue(Number.MAX_VALUE);
                                if (this.getValue() === 'persen') 
                                    Ext.getCmp('hj_disk_kons2').maxValue = 100;
                                else 
                                    Ext.getCmp('hj_disk_kons2').maxLength = 11;
                            }
                        }
                    }
                },{
                    xtype: 'numbercolumn',
                    align: 'right',
                    // format: '0,0',
                    header: 'Diskon Konsumen 2',
                    dataIndex: 'disk_kons2',           
                    width: 150,
                    editor: {
                        xtype: 'numberfield',
                        msgTarget: 'under',
                        flex:1,
                        width:115,
                        name : 'disk_kons2',
                        id: 'hj_disk_kons2',
                        style: 'text-align:right;',
                        listeners:{
                            'render': function(c) {
                                c.getEl().on('keyup', function() {
                                    // Edited();
                                   HitungNetPJual();
                                }, c);
                            }
                        }
                    }
                },{
                    header: '% / Rp',
                    dataIndex: 'disk_kons3_op',
                    width: 50,
                    editor: {
                        xtype:          'combo',
                        store:          new Ext.data.JsonStore({
                            fields : ['name'],
                            data   : [
                                {name : '%'},
                                {name : 'Rp'}
                            ]
                        }),
                        id:           	'hj_disk_kons3_op',
                        mode:           'local',
                        name:           'disk_kons3_op',
                        value:          '%',
                        width:			50,
                        editable:       false,
                        hiddenName:     'disk_kons3_op',
                        valueField:     'name',
                        displayField:   'name',
                        triggerAction:  'all',
                        forceSelection: true,
                        listeners:{
                            'expand':function(){
                                Ext.getCmp('hj_disk_kons3').setValue(0);
                            },
                            select:function(){
                                Ext.getCmp('hj_disk_kons3').setMaxValue(Number.MAX_VALUE);
                                if (this.getValue() === 'persen') 
                                    Ext.getCmp('hj_disk_kons3').maxValue = 100;
                                else 
                                    Ext.getCmp('hj_disk_kons3').maxLength = 11;
                            }
                        }
                    }
                },{
                    xtype: 'numbercolumn',
                    align: 'right',
                    // format: '0,0',
                    header: 'Diskon Konsumen 3',
                    dataIndex: 'disk_kons3',           
                    width: 150,
                    editor: {
                        xtype: 'numberfield',
                        msgTarget: 'under',
                        flex:1,
                        width:115,
                        name : 'disk_kons3',
                        id: 'hj_disk_kons3',
                        style: 'text-align:right;',
                        listeners:{
                            'render': function(c) {
                                c.getEl().on('keyup', function() {
                                    // Edited();
                                    HitungNetPJual();
                                }, c);
                            }
                        }
                    }
                },{
                    header: '% / Rp',
                    dataIndex: 'disk_kons4_op',
                    width: 50,
                    editor: {
                        xtype:          'combo',
                        store:          new Ext.data.JsonStore({
                            fields : ['name'],
                            data   : [
                                {name : '%'},
                                {name : 'Rp'},
                            ]
                        }),
                        id:           	'hj_disk_kons4_op',
                        mode:           'local',
                        name:           'disk_kons4_op',
                        value:          '%',
                        width:			50,
                        editable:       false,
                        hiddenName:     'disk_kons4_op',
                        valueField:     'name',
                        displayField:   'name',
                        triggerAction:  'all',
                        forceSelection: true,
                        listeners:{
                            'expand':function(){
                                Ext.getCmp('hj_disk_kons4').setValue(0);
                            },
                            select:function(){
                                Ext.getCmp('hj_disk_kons4').setMaxValue(Number.MAX_VALUE);
                                if (this.getValue() === 'persen') 
                                    Ext.getCmp('hj_disk_kons4').maxValue = 100;
                                else 
                                    Ext.getCmp('hj_disk_kons4').maxLength = 11;
                            }
                        }
                    }
                },{
                    xtype: 'numbercolumn',
                    align: 'right',
                    // format: '0,0',
                    header: 'Diskon Konsumen 4',
                    dataIndex: 'disk_kons4',           
                    width: 150,
                    editor: {
                        xtype: 'numberfield',
                        msgTarget: 'under',
                        flex:1,
                        width:115,
                        name : 'disk_kons4',
                        id: 'hj_disk_kons4',
                        style: 'text-align:right;',
                        listeners:{
                            'render': function(c) {
                                c.getEl().on('keyup', function() {
                                    // Edited();
                                    HitungNetPJual();
                                }, c);
                            }
                        }
                    }
                },{
                    xtype: 'numbercolumn',
                    align: 'right',
                    format: '0,0',
                    header: 'Diskon Konsumen 5',
                    dataIndex: 'disk_amt_kons5',           
                    width: 150,
                    editor: {
                        xtype: 'numberfield',
                        msgTarget: 'under',
                        flex:1,
                        width:115,
                        name : 'disk_kons5',
                        id: 'hj_disk_kons5',
                        style: 'text-align:right;',
                        listeners:{
                            'render': function(c) {
                                c.getEl().on('keyup', function() {
                                    // Edited();
                                    HitungNetPJual();
                                }, c);
                            }
                        }
                    }
                },{
                    xtype: 'numbercolumn',
                    align: 'right',
                    format: '0,0',
                    header: 'Net Price Jual Konsumen',
                    dataIndex: 'net_price_jual_kons',           
                    width: 180,
                    editor: {
                        xtype: 'numberfield',
                        id: 'ehj_net_price_jual_kons',
                        readOnly: true,
                        fieldClass: 'readonly-input',
                        listeners:{			
                            'change': function() {
                                if(Ext.getCmp('ehj_rp_cogs').getValue() > 0){
                                    if(Ext.getCmp('ehj_net_price_jual_kons').getValue() < Ext.getCmp('ehj_rp_cogs').getValue()){
                                        Ext.getCmp('ehj_net_price_jual_kons').setValue('0');
                                        Ext.Msg.show({
                                            title: 'Error',
                                            msg: 'Net Price Jual Tidak Boleh Lebih Kecil Dari HET COGS',
                                            modal: true,
                                            icon: Ext.Msg.ERROR,
                                            buttons: Ext.Msg.OK,
                                            fn: function(btn){
                                                Ext.getCmp('ehj_net_price_jual_kons').focus();
                                            }
                                        });
                                        Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');  
                                    }
                                }else{
                                    if(Ext.getCmp('ehj_net_price_jual_kons').getValue() < Ext.getCmp('ehj_rp_het_harga_beli').getValue()){
                                        Ext.getCmp('ehj_net_price_jual_kons').setValue('0');
                                        Ext.Msg.show({
                                            title: 'Error',
                                            msg: 'Net Price Jual Tidak Boleh Lebih Kecil Dari HET Beli',
                                            modal: true,
                                            icon: Ext.Msg.ERROR,
                                            buttons: Ext.Msg.OK,
                                            fn: function(btn){
                                                Ext.getCmp('ehj_net_price_jual_kons').focus();
                                            }
                                        });
                                        Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');  
                                    }
                                }
						
					  
                            },'render': function(c) {
                                c.getEl().on('keyup', function() {
                                    // Edited();
                                }, c);
                            }
                        }
                    }
                },{
                    header: '% / Rp',
                    dataIndex: 'disk_member1_op',
                    width: 50,
                    editor: {
                        xtype:          'combo',
                        store:          new Ext.data.JsonStore({
                            fields : ['name'],
                            data   : [
                                {name : '%'},
                                {name : 'Rp'}
                            ]
                        }),
                        id:           	'hj_disk_member1_op',
                        mode:           'local',
                        name:           'disk_member1_op',
                        value:          '%',
                        width:			50,
                        editable:       false,
                        hiddenName:     'disk_member1_op',
                        valueField:     'name',
                        displayField:   'name',
                        triggerAction:  'all',
                        forceSelection: true,
                        listeners:{
                            'expand':function(){
                                Ext.getCmp('hj_disk_member1').setValue(0);
                            },
                            select:function(){
                                Ext.getCmp('hj_disk_member1').setMaxValue(Number.MAX_VALUE);
                                if (this.getValue() === 'persen') 
                                    Ext.getCmp('hj_disk_member1').maxValue = 100;
                                else 
                                    Ext.getCmp('hj_disk_member1').maxLength = 11;
                            }
                        }
                    }
                },{
                    xtype: 'numbercolumn',
                    align: 'right',
                    // format: '0,0',
                    header: 'Diskon Member 1',
                    dataIndex: 'disk_member1',           
                    width: 150,
                    editor: {
                        xtype: 'numberfield',
                        msgTarget: 'under',
                        flex:1,
                        width:115,
                        name : 'disk_member1',
                        id: 'hj_disk_member1',
                        style: 'text-align:right;',
                        listeners:{
                            'render': function(c) {
                                c.getEl().on('keyup', function() {
                                    // Edited();
                                    HitungNetPJual();
                                }, c);
                            }
                        }
                    }
                },{
                    header: '% / Rp',
                    dataIndex: 'disk_member2_op',
                    width: 50,
                    editor: {
                        xtype:          'combo',
                        store:          new Ext.data.JsonStore({
                            fields : ['name'],
                            data   : [
                                {name : '%'},
                                {name : 'Rp'}
                            ]
                        }),
                        id:           	'hj_disk_member2_op',
                        mode:           'local',
                        name:           'disk_member2_op',
                        value:          '%',
                        width:			50,
                        editable:       false,
                        hiddenName:     'disk_member2_op',
                        valueField:     'name',
                        displayField:   'name',
                        triggerAction:  'all',
                        forceSelection: true,
                        listeners:{
                            'expand':function(){
                                Ext.getCmp('hj_disk_member2').setValue(0);
                            },
                            select:function(){
                                Ext.getCmp('hj_disk_member2').setMaxValue(Number.MAX_VALUE);
                                if (this.getValue() === 'persen') 
                                    Ext.getCmp('hj_disk_member2').maxValue = 100;
                                else 
                                    Ext.getCmp('hj_disk_member2').maxLength = 11;
                            }
                        }
                    }
                },{
                    xtype: 'numbercolumn',
                    align: 'right',
                    // format: '0,0',
                    header: 'Diskon Member 2',
                    dataIndex: 'disk_member2',           
                    width: 150,
                    editor: {
                        xtype: 'numberfield',
                        msgTarget: 'under',
                        flex:1,
                        width:115,
                        name : 'disk_member2',
                        id: 'hj_disk_member2',
                        style: 'text-align:right;',
                        listeners:{
                            'render': function(c) {
                                c.getEl().on('keyup', function() {
                                    // Edited();
                                    HitungNetPJual();
                                }, c);
                            }
                        }
                    }
                },{
                    header: '% / Rp',
                    dataIndex: 'disk_member3_op',
                    width: 50,
                    editor: {
                        xtype:          'combo',
                        store:          new Ext.data.JsonStore({
                            fields : ['name'],
                            data   : [
                                {name : '%'},
                                {name : 'Rp'}
                            ]
                        }),
                        id:           	'hj_disk_member3_op',
                        mode:           'local',
                        name:           'disk_member3_op',
                        value:          '%',
                        width:			50,
                        editable:       false,
                        hiddenName:     'disk_member3_op',
                        valueField:     'name',
                        displayField:   'name',
                        triggerAction:  'all',
                        forceSelection: true,
                        listeners:{
                            'expand':function(){
                                Ext.getCmp('hj_disk_member3').setValue(0);
                            },
                            select:function(){
                                Ext.getCmp('hj_disk_member3').setMaxValue(Number.MAX_VALUE);
                                if (this.getValue() === 'persen') 
                                    Ext.getCmp('hj_disk_member3').maxValue = 100;
                                else 
                                    Ext.getCmp('hj_disk_member3').maxLength = 11;
                            }
                        }
                    }
                },{
                    xtype: 'numbercolumn',
                    align: 'right',
                    // format: '0,0',
                    header: 'Diskon Member 3',
                    dataIndex: 'disk_member3',           
                    width: 150,
                    editor: {
                        xtype: 'numberfield',
                        msgTarget: 'under',
                        flex:1,
                        width:115,
                        name : 'disk_member3',
                        id: 'hj_disk_member3',
                        style: 'text-align:right;',
                        listeners:{
                            'render': function(c) {
                                c.getEl().on('keyup', function() {
                                    // Edited();
                                    HitungNetPJual();
                                }, c);
                            }
                        }
                    }
                },{
                    header: '% / Rp',
                    dataIndex: 'disk_member4_op',
                    width: 50,
                    editor: {
                        xtype:          'combo',
                        store:          new Ext.data.JsonStore({
                            fields : ['name'],
                            data   : [
                                {name : '%'},
                                {name : 'Rp'}
                            ]
                        }),
                        id:           	'hj_disk_member4_op',
                        mode:           'local',
                        name:           'disk_member4_op',
                        value:          '%',
                        width:			50,
                        editable:       false,
                        hiddenName:     'disk_member4_op',
                        valueField:     'name',
                        displayField:   'name',
                        triggerAction:  'all',
                        forceSelection: true,
                        listeners:{
                            'expand':function(){
                                Ext.getCmp('hj_disk_member4').setValue(0);
                            },
                            select:function(){
                                Ext.getCmp('hj_disk_member4').setMaxValue(Number.MAX_VALUE);
                                if (this.getValue() === 'persen') 
                                    Ext.getCmp('hj_disk_member4').maxValue = 100;
                                else 
                                    Ext.getCmp('hj_disk_member4').maxLength = 11;
                            }
                        }
                    }
                },{
                    xtype: 'numbercolumn',
                    align: 'right',
                    // format: '0,0',
                    header: 'Diskon Member 4',
                    dataIndex: 'disk_member4',           
                    width: 150,
                    editor: {
                        xtype: 'numberfield',
                        msgTarget: 'under',
                        flex:1,
                        width:115,
                        name : 'disk_member4',
                        id: 'hj_disk_member4',
                        style: 'text-align:right;',
                        listeners:{
                            'render': function(c) {
                                c.getEl().on('keyup', function() {
                                    // Edited();
                                    HitungNetPJual();
                                }, c);
                            }
                        }
                    }
                },{
                    xtype: 'numbercolumn',
                    align: 'right',
                    format: '0,0',
                    header: 'Diskon Member 5',
                    dataIndex: 'disk_amt_member5',           
                    width: 150,
                    editor: {
                        xtype: 'numberfield',
                        msgTarget: 'under',
                        flex:1,
                        width:115,
                        name : 'disk_amt_member5',
                        id: 'hj_disk_amt_member5',
                        style: 'text-align:right;',
                        listeners:{
                            'render': function(c) {
                                c.getEl().on('keyup', function() {
                                    // Edited();
                                    HitungNetPJual();
                                }, c);
                            }
                        }
                    }
                },{
                    xtype: 'numbercolumn',
                    align: 'right',
                    format: '0,0',
                    header: 'Net Price Jual Member',
                    dataIndex: 'net_price_jual_member',           
                    width: 180,
                    editor: {
                        xtype: 'numberfield',
                        id: 'ehj_net_price_jual_member',
                        readOnly: true,
                        fieldClass: 'readonly-input',
                        listeners:{			
                            'change': function() {
                                if(Ext.getCmp('ehj_rp_cogs').getValue() > 0){
                                    if(this.getValue() < Ext.getCmp('ehj_rp_cogs').getValue()){
                                        this.setValue('0');
                                        Ext.Msg.show({
                                            title: 'Error',
                                            msg: 'Net Price Jual Tidak Boleh Lebih Kecil Dari HET COGS',
                                            modal: true,
                                            icon: Ext.Msg.ERROR,
                                            buttons: Ext.Msg.OK,
                                            fn: function(btn){
                                                this.focus();
                                            }
                                        });
                                        Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');  
                                    }
                                }else{
                                    if(this.getValue() < Ext.getCmp('ehj_rp_het_harga_beli').getValue()){
                                        this.setValue('0');
                                        Ext.Msg.show({
                                            title: 'Error',
                                            msg: 'Net Price Jual Tidak Boleh Lebih Kecil Dari HET Beli',
                                            modal: true,
                                            icon: Ext.Msg.ERROR,
                                            buttons: Ext.Msg.OK,
                                            fn: function(btn){
                                                this.focus();
                                            }
                                        });
                                        Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');  
                                    }
                                }
						
					  
                            },'render': function(c) {
                                c.getEl().on('keyup', function() {
                                    // Edited();
                                }, c);
                            }
                        }
                    }
                },{
                    xtype: 'numbercolumn',
                    align: 'right',
                    format: '0,0',
                    header: 'Qty Beli |Konsumen|',
                    dataIndex: 'qty_beli_bonus',           
                    width: 150,
                    editor: {
                        xtype: 'numberfield',
                        msgTarget: 'under',
                        flex:1,
                        width:115,
                        name : 'qty_beli_bonus',
                        id: 'hj_qty_beli_bonus',
                        style: 'text-align:right;',
                        listeners:{
                            'render': function(c) {
                                c.getEl().on('keyup', function() {
                                    // Edited();
                                }, c);
                            }
                        }
                    }
                },{
                    header: 'Kd Produk |Konsumen|',
                    dataIndex: 'kd_produk_bonus',           
                    width: 150,
                    editor: new Ext.ux.TwinComboHj({
                        id: 'hj_kd_produk_bonus',
                        store: strcbkdprodukhj,
                        valueField: 'kd_produk_bonus',
                        displayField: 'kd_produk_bonus',
                        typeAhead: true,	
                        editable: false,
                        hiddenName: 'kd_produk_bonus',
                        emptyText: 'Pilih Kode Produk',    
                        listeners:{
                            'expand': function(){
                                strcbkdprodukhj.load();
                                // Edited();
                            }
                        }
                    })
                },{
                    xtype: 'numbercolumn',
                    align: 'right',
                    format: '0,0',
                    header: 'Qty Bonus |Konsumen|',
                    dataIndex: 'qty_bonus',           
                    width: 150,
                    editor: {
                        xtype: 'numberfield',
                        msgTarget: 'under',
                        flex:1,
                        width:115,
                        name : 'qty_bonus',
                        id: 'hj_qty_bonus',
                        style: 'text-align:right;',
                        listeners:{
                            'render': function(c) {
                                c.getEl().on('keyup', function() {
                                    // Edited();
                                }, c);
                            }
                        }
                    }
                },{
                    header: 'Kelipatan ? |Konsumen|',
                    dataIndex: 'is_bonus_kelipatan',           
                    width: 150,
                    editor: {
                        xtype:          'combo',
                        store:          new Ext.data.JsonStore({
                            fields : ['name'],
                            data   : [
                                {name : 'Ya'},
                                {name : 'Tidak'}
                            ]
                        }),
                        id:           	'hj_is_bonus_kelipatan',
                        mode:           'local',
                        name:           'is_bonus_kelipatan',
                        value:          'Ya',
                        width:			50,
                        editable:       false,
                        hiddenName:     'is_bonus_kelipatan',
                        valueField:     'name',
                        displayField:   'name',
                        triggerAction:  'all',
                        forceSelection: true,
                        listeners:{
                            'render': function(c) {
                                c.getEl().on('keyup', function() {
                                    // Edited();
                                }, c);
                            }
                        }
                    }
                },{
                    xtype: 'numbercolumn',
                    align: 'right',
                    format: '0,0',
                    header: 'Qty Beli |Member|',
                    dataIndex: 'qty_beli_member',           
                    width: 150,
                    editor: {
                        xtype: 'numberfield',
                        msgTarget: 'under',
                        flex:1,
                        width:115,
                        name : 'qty_beli_member',
                        id: 'hj_qty_beli_member',
                        style: 'text-align:right;',
                        listeners:{
                            'render': function(c) {
                                c.getEl().on('keyup', function() {
                                    // Edited();
                                }, c);
                            }
                        }
                    }
                },{
                    header: 'Kd Produk |Member|',
                    dataIndex: 'kd_produk_member',           
                    width: 150,
                    editor: new Ext.ux.TwinComboHj({
                        id: 'hj_kd_produk_member',
                        store: strcbkdprodukhj,
                        valueField: 'kd_produk_member',
                        displayField: 'kd_produk_member',
                        typeAhead: true,	
                        editable: false,
                        hiddenName: 'kd_produk_member',
                        emptyText: 'Pilih Kode Produk',    
                        listeners:{
                            'expand': function(){
                                strcbkdprodukhj.load();
                                // Edited();
                            }
                        }
                    })
            
                },{
                    xtype: 'numbercolumn',
                    align: 'right',
                    format: '0,0',
                    header: 'Qty Bonus |Member|',
                    dataIndex: 'qty_member',           
                    width: 150,
                    editor: {
                        xtype: 'numberfield',
                        msgTarget: 'under',
                        flex:1,
                        width:115,
                        name : 'qty_member',
                        id: 'hj_qty_member',
                        style: 'text-align:right;',
                        listeners:{
                            'render': function(c) {
                                c.getEl().on('keyup', function() {
                                    // Edited();
                                }, c);
                            }
                        }
                    }
                },{
                    header: 'Kelipatan ? |Member|',
                    dataIndex: 'is_member_kelipatan',           
                    width: 150,
                    editor:{
                        xtype:          'combo',
                        store:          new Ext.data.JsonStore({
                            fields : ['name'],
                            data   : [
                                {name : 'Ya'},
                                {name : 'Tidak'}
                            ]
                        }),
                        id:           	'hj_is_member_kelipatan',
                        mode:           'local',
                        name:           'is_member_kelipatan',
                        value:          'Ya',
                        width:			50,
                        editable:       false,
                        hiddenName:     'is_member_kelipatan',
                        valueField:     'name',
                        displayField:   'name',
                        triggerAction:  'all',
                        forceSelection: true,
                        listeners:{
                            'render': function(c) {
                                c.getEl().on('keyup', function() {
                                    // Edited();
                                }, c);
                            }
                        }
                    }
            
                },{
                    header: 'Is Konsinyasi',
                    dataIndex: 'is_konsinyasi',
                    width: 130,
                    editor: new Ext.form.TextField({
                        readOnly: true,
                        fieldClass: 'readonly-input',
                        id: 'ehj_is_konsinyasi'
                    })
                },{
                    xtype: 'datecolumn',
                    header: 'Efektif Diskon',
                    dataIndex: 'tgl_start_diskon',
                    format: 'd/m/Y',
                    width: 120,
                    editor: new Ext.form.DateField({
                        id: 'eppp_tgl_start_diskon',
                        format: 'd/m/Y',
                        //minValue: (new Date()).clearTime(),
                         listeners:{			
                            'change': function() {
                               	  Ext.getCmp('hj_edited').setValue('Y');
                            }
                        }
                    })
                },
                
//                 {
//                    xtype: 'datecolumn',
//                    header: 'Tgl Akhir Diskon',
//                    dataIndex: 'tgl_end_diskon',
//                    format: 'd/m/Y',
//                    width: 120,
//                    editor: new Ext.form.DateField({
//                        id: 'eppp_tgl_end_diskon',
//                        format: 'd/m/Y',
//                        //minValue: (new Date()).clearTime(),
//                        listeners:{			
//                            'change': function() {
//                               	  Ext.getCmp('hj_edited').setValue('Y');
//                            }
//                        }
//                    })
//                }
                ],
            tbar: new Ext.Toolbar({
                items: [searchgridhargapenjualan, '->', cbhjproduk, cbhjnobukti, '-' ,{
                        text: 'Show History',
                        icon: BASE_ICONS + 'grid.png',
                        onClick: function(){
                            var kd_produk = Ext.getCmp('id_cbhjproduk').getValue();
                            var no_bukti = Ext.getCmp('id_cbhjnobukti').getValue();
                            if (kd_produk == '' && no_bukti == ''){					
                                Ext.Msg.show({
                                    title: 'Error',
                                    msg: 'Silahkan Search Produk / No Bukti Terlebih Dulu',
                                    modal: true,
                                    icon: Ext.Msg.ERROR,
                                    buttons: Ext.Msg.OK			               
                                });
                                return;
                            }
                            gridhargapenjualanhistory.store.load({
                                params:{
                                    no_bukti: Ext.getCmp('id_cbhjnobukti').getValue(),
                                    kd_produk: Ext.getCmp('id_cbhjproduk').getValue()
                                }
                            });
                            winshowhistoryhargapenjualan.setTitle('History');
                            winshowhistoryhargapenjualan.show();	
                            // var sm = gridhargapenjualan.getSelectionModel();
                            // var sel = sm.getSelections();
                            // if (sel[0] == undefined){					
                            // Ext.Msg.show({
                            // title: 'Error',
                            // msg: 'Silahkan klik salah satu data terlebih dulu',
                            // modal: true,
                            // icon: Ext.Msg.ERROR,
                            // buttons: Ext.Msg.OK			               
                            // });
                            // return;
                            // }
                            // gridhargapenjualanhistory.store.proxy.conn.url = '<?= site_url("harga_penjualan/search_produk_history") ?>/' +sel[0].get('kd_produk');
                            // gridhargapenjualanhistory.store.reload();
                            // winshowhistoryhargapenjualan.setTitle('History');
                            // winshowhistoryhargapenjualan.show();				        
                        }
                    },'-',{
                        text: 'Reset',
                        icon: BASE_ICONS + 'refresh.gif',
                        onClick: function(){		
                            Ext.getCmp('id_cbhjnobukti').setValue('');
                            Ext.getCmp('id_cbhjproduk').setValue('');		        
                        }
                    }]
            })
            // bbar: new Ext.PagingToolbar({
            // pageSize: ENDPAGE,
            // store: strhargapenjualan,
            // displayInfo: true
            // })
        });
	
    
        var hargapenjualan = new Ext.FormPanel({
            id: 'hargapenjualan',
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
                    items: [headerhargapenjualan]
                },{	
                xtype:'fieldset',
                autoheight: true,
                title: 'Diskon',
                collapsed: false,
                collapsible: true,
                anchor: '70%',
                items:[ {
                        xtype : 'compositefield',
                        msgTarget: 'side',
                        fieldLabel: 'Disk Konsumen 1',
                        items : [ {
                                xtype : 'compositefield',
                                msgTarget: 'side',
                                fieldLabel: 'Disk Konsumen 1',
                                width:200,
                                items : [{
                                        xtype:          'combo',
                                        mode:           'local',
                                        value:          '%',
                                        triggerAction:  'all',
                                        forceSelection: true,
                                        name:           'disk_kons1_op',
                                        id:           	'hp_disk_kons1_op',
                                        hiddenName:     'disk_kons1_op',
                                        displayField:   'name',
                                        valueField:     'value',
                                        width:	50,
                                        store:          new Ext.data.JsonStore({
                                            fields : ['name', 'value'],
                                            data   : [
                                                {name : '%',   value: '%'},
                                                {name : 'Rp',  value: 'Rp'},
                                            ]
                                        }),
                                        listeners:{
                                            select:function(){
                                                Ext.getCmp('hp_disk_kons1').setMaxValue(Number.MAX_VALUE);
                                                if (this.getValue() == '%') 
                                                    Ext.getCmp('hp_disk_kons1').maxValue = 100;
                                                else Ext.getCmp('hp_disk_kons1').maxLength = 11;
                                            }
                                        }
                                    },{
                                        xtype: 'numberfield',
                                        flex:1,
                                        width:115,
                                        name : 'disk_kons1',
                                        id: 'hp_disk_kons1',
                                        style: 'text-align:right;',
                                        value :'0'
                                       
                                    }]
                            },{
                                xtype: 'displayfield',
                                value: 'Disk Konsumen 2',
                                width: 100
                            },{
                                xtype : 'compositefield',
                                msgTarget: 'side',
                                fieldLabel: 'Disk Konsumen 2',
                                width:200,
                                items : [{
                                        width:          50,
                                        xtype:          'combo',
                                        mode:           'local',
                                        value:          '%',
                                        triggerAction:  'all',
                                        forceSelection: true,
                                        
                                        name:           'disk_kons2_op',
                                        id:           	'hp_disk_kons2_op',
                                        hiddenName:     'disk_kons2_op',
                                        displayField:   'name',
                                        valueField:     'value',
                                        store:          new Ext.data.JsonStore({
                                            fields : ['name', 'value'],
                                            data   : [
                                                {name : '%',   value: '%'},
                                                {name : 'Rp',  value: 'Rp'},
                                            ]
                                        }),
                                        listeners:{
                                            select:function(){
                                                Ext.getCmp('hp_disk_kons2').setMaxValue(Number.MAX_VALUE);
                                                if (this.getValue() == '%') 
                                                    Ext.getCmp('hp_disk_kons2').maxValue = 100;
                                                else Ext.getCmp('hp_disk_kons2').maxLength = 11;
                                            }
                                        }
                                    },{
                                        xtype: 'numberfield',
                                        flex : 1,
                                        width:115,
                                        name : 'disk_kons2',
                                         value :'0',
                                        id: 'hp_disk_kons2',
                                        style: 'text-align:right;'
                                        
                                    }]
												
                            }]
                    },{
                        xtype : 'compositefield',
                        msgTarget: 'side',
                        fieldLabel: 'Disk Konsumen 3',
                        items : [{
                                xtype : 'compositefield',
                                msgTarget: 'side',
                                fieldLabel: 'Disk Konsumen 3',
                                width:200,
                                items : [{
                                        width:          50,
                                        xtype:          'combo',
                                        mode:           'local',
                                        value:          '%',
                                        triggerAction:  'all',
                                        forceSelection: true,
                                       
                                        name:           'disk_kons3_op',
                                        id:           	'hp_disk_kons3_op',
                                        hiddenName:     'disk_kons3_op',
                                        displayField:   'name',
                                        valueField:     'value',
                                        store:          new Ext.data.JsonStore({
                                            fields : ['name', 'value'],
                                            data   : [
                                                {name : '%',   value: '%'},
                                                {name : 'Rp',  value: 'Rp'},
                                            ]
                                        }),
                                        listeners:{
                                            select:function(){
                                                Ext.getCmp('hp_disk_kons3').setMaxValue(Number.MAX_VALUE);
                                                if (this.getValue() == '%') 
                                                    Ext.getCmp('hp_disk_kons3').maxValue = 100;
                                                else Ext.getCmp('hp_disk_kons3').maxLength = 11;
                                            }
                                        }
                                    },{
                                        xtype: 'numberfield',
                                        flex : 1,
                                        width:115,
                                        name : 'disk_kons3',
                                         value :'0',
                                        id: 'hp_disk_kons3',
                                        style: 'text-align:right;'
                                        
                                    }]
												
                            }, {
                                xtype: 'displayfield',
                                value: 'Disk Konsumen 4',
                                width: 100
                            },{
                                xtype : 'compositefield',
                                msgTarget: 'side',
                                fieldLabel: 'Disk Konsumen 4',
                                width:200,
                                items : [{
                                        width:          50,
                                        xtype:          'combo',
                                        mode:           'local',
                                        value:          '%',
                                        triggerAction:  'all',
                                        forceSelection: true,
                                        
                                        name:           'disk_kons4_op',
                                        id:           	'hp_disk_kons4_op',
                                        hiddenName:     'disk_kons4_op',
                                        displayField:   'name',
                                        valueField:     'value',
                                        store:          new Ext.data.JsonStore({
                                            fields : ['name', 'value'],
                                            data   : [
                                                {name : '%',   value: '%'},
                                                {name : 'Rp',  value: 'Rp'},
                                            ]
                                        }),
                                        listeners:{
                                            select:function(){
                                                Ext.getCmp('hp_disk_kons4').setMaxValue(Number.MAX_VALUE);
                                                if (this.getValue() == '%') 
                                                    Ext.getCmp('hp_disk_kons4').maxValue = 100;
                                                else Ext.getCmp('hp_disk_kons4').maxLength = 11;
                                            }
                                        }
                                    },{
                                        xtype: 'numberfield',
                                        flex : 1,
                                        width:115,
                                        name : 'disk_kons4',
                                         value :'0',
                                        id: 'hp_disk_kons4',
                                        style: 'text-align:right;'
                                        
                                    }]
												
                            }
                        ]
                    },{
                        xtype : 'compositefield',
                        msgTarget: 'side',
                        fieldLabel: 'Disk Konsumen 5',
                        items : [{
                                xtype: 'numberfield',
                                currencySymbol:'',
                                width: 170,
                                name : 'disk_kons5',
                                 value :'0',
                                id: 'hp_disk_kons5',
                                style: 'text-align:right;'
                                
                            }
                        ]
                    }],buttons: [{
                        text: 'Apply All',
                        formBind: true,
                        handler: function(){
                            var kd_supplier =  Ext.getCmp('id_cbhjsuplier').getValue();
                            if(!kd_supplier){
                                Ext.Msg.show({
                                    title: 'Error',
                                    msg: 'Silahkan Pilih Supplier Terlebih Dahulu',
                                    modal: true,
                                    icon: Ext.Msg.ERROR,
                                    buttons: Ext.Msg.OK
                                });
                                return;
                            }
                           var total = gridhargapenjualan.getStore().getTotalCount();
                           if (total > 150){
                               Ext.Msg.show({
                                    title: 'Error',
                                    msg: 'Apply All Maksimal 150 Item,,Total Data Lebih Dari 150 Item',
                                    modal: true,
                                    icon: Ext.Msg.ERROR,
                                    buttons: Ext.Msg.OK
                                });
                                return;
                           }                           
                            strhargapenjualan.each(function(record){
                                    
                                record.set('disk_kons1_op',Ext.getCmp('hp_disk_kons1_op').getValue());
                                record.set('disk_kons1',Ext.getCmp('hp_disk_kons1').getValue());
                                record.set('disk_kons2_op',Ext.getCmp('hp_disk_kons2_op').getValue());
                                record.set('disk_kons2',Ext.getCmp('hp_disk_kons2').getValue());
                                record.set('disk_kons3_op',Ext.getCmp('hp_disk_kons3_op').getValue());
                                record.set('disk_kons3',Ext.getCmp('hp_disk_kons3').getValue());
                                record.set('disk_kons4_op',Ext.getCmp('hp_disk_kons4_op').getValue());
                                record.set('disk_kons4',Ext.getCmp('hp_disk_kons4').getValue());
                                record.set('disk_amt_kons5',Ext.getCmp('hp_disk_kons5').getValue());
                                
                                record.commit();

                                record.set('edited','Y');

                                var total_disk = 0;
                                var rp_jual_supermarket = record.get('rp_jual_supermarket');
                                var disk_kons1_op = record.get('disk_kons1_op');
                                var disk_kons1 = record.get('disk_kons1');
                                if (disk_kons1_op == '%'){
                                    total_disk = rp_jual_supermarket-(rp_jual_supermarket*(disk_kons1/100));
                                }else{
                                    total_disk = rp_jual_supermarket-disk_kons1;				
                                }

                                var disk_kons2_op = record.get('disk_kons2_op');
                                var disk_kons2 = record.get('disk_kons2');
                                if (disk_kons2_op == '%'){
                                    total_disk =  total_disk-(total_disk*(disk_kons2/100));
                                }else{
                                    total_disk = total_disk-disk_kons2;				
                                }

                                var disk_kons3_op = record.get('disk_kons3_op');
                                var disk_kons3 = record.get('disk_kons3');
                                if (disk_kons3_op == '%'){
                                    total_disk = total_disk-(total_disk*(disk_kons3/100));
                                }else{
                                    total_disk = total_disk-disk_kons3;				
                                }

                                var disk_kons4_op = record.get('disk_kons4_op');
                                var disk_kons4 = record.get('disk_kons4');
                                if (disk_kons4_op == '%'){
                                    total_disk = total_disk-(total_disk*(disk_kons4/100));
                                }else{
                                    total_disk = total_disk-disk_kons4;				
                                }

                                var total_disk = total_disk - record.get('disk_amt_kons5');

                                record.set('net_price_jual_kons', total_disk);


                                var disk_member1_op = record.get('disk_member1_op');
                                var disk_member1 = record.get('disk_member1');
                                if (disk_member1_op == '%'){
                                    total_disk = rp_jual_supermarket-(rp_jual_supermarket*(disk_member1/100));
                                }else{
                                    total_disk = rp_jual_supermarket-disk_member1;				
                                }

                                var disk_member2_op = record.get('disk_member2_op');
                                var disk_member2 = record.get('disk_member2');
                                if (disk_member2_op == '%'){
                                    total_disk = total_disk-(total_disk*(disk_member2/100));
                                }else{
                                    total_disk = total_disk-disk_member2;				
                                }

                                var disk_member3_op = record.get('disk_member3_op');
                                var disk_member3 = record.get('disk_member3');
                                if (disk_member3_op == '%'){
                                    total_disk = total_disk-(total_disk*(disk_member3/100));
                                }else{
                                    total_disk = total_disk-disk_member3;				
                                }

                                var disk_member4_op = record.get('disk_member4_op');
                                var disk_member4 = record.get('disk_member4');
                                if (disk_member4_op == '%'){
                                    total_disk = total_disk-(total_disk*(disk_member4/100));
                                }else{
                                    total_disk = total_disk-disk_member4;				
                                }

                                var total_disk = total_disk - record.get('disk_amt_member5');

                                var net_price_memb = total_disk;
                                record.set('net_price_jual_member', net_price_memb);
                                record.commit();
                            });

                        }
                    }]
            },{	
                xtype:'fieldset',
                autoheight: true,
                title: 'Efektif Diskon',
                collapsed: false,
                collapsible: true,
                anchor: '70%',
                items:[{
                        xtype : 'compositefield',
                        msgTarget: 'side',
                        fieldLabel: 'Efektif Diskon',
                        items : [ {
                                    xtype: 'datefield',
                                    fieldLabel: 'Efektif Diskon',
                                    name: 'tgl_start_diskon',				
                                    allowBlank:true,   
                                    format:'d-m-Y',  
                                    //editable:false,           
                                    id: 'tgl_start_diskon',                
                                    width: 150,
                                    minValue: (new Date()).clearTime() 
                                },
//                                {
//                                xtype: 'displayfield',
//                                value: 'Tgl Akhir Diskon',
//                                width: 100
//                            },{
//                                xtype : 'compositefield',
//                                msgTarget: 'side',
//                                fieldLabel: 'Tgl Akhir Diskon',
//                                width:150,
//                                items : [{
//                                        xtype: 'datefield',
//                                        fieldLabel: 'Tgl Akhir Diskon',
//                                        name: 'tgl_end_diskon',				
//                                        allowBlank:true,   
//                                        format:'d-m-Y',  
//                                        //editable:false,           
//                                        id: 'tgl_end_diskon',                
//                                        width: 150,
//                                        minValue: (new Date()).clearTime() 
//                                    }]
//												
//                            }
                        ]
                    }],buttons: [{
                        text: 'Apply All',
                        formBind: true,
                        handler: function(){
                            var kd_supplier =  Ext.getCmp('id_cbhjsuplier').getValue();
                            if(!kd_supplier){
                                Ext.Msg.show({
                                    title: 'Error',
                                    msg: 'Silahkan Pilih Supplier Terlebih Dahulu',
                                    modal: true,
                                    icon: Ext.Msg.ERROR,
                                    buttons: Ext.Msg.OK
                                });
                                return;
                            }
                            
                            strhargapenjualan.each(function(record){
                                    
                                record.set('tgl_start_diskon',Ext.getCmp('tgl_start_diskon').getValue());
                                //record.set('tgl_end_diskon',Ext.getCmp('tgl_end_diskon').getValue());
                                
                                record.commit();
                                record.set('edited','Y');
                                record.commit();
                            });

                        }
                    }]
            },
                gridhargapenjualan,   
                {
                    layout: 'column',
                    border: false,
                    items: [{
                            columnWidth: .6,
                            style:'margin:6px 3px 0 0;',
                            layout: 'form', 
                            labelWidth: 100,                   
                            items: [{	
                                    xtype: 'textarea',
                                    fieldLabel: 'Ket. Perubahan <span class="asterix">*</span>',
                                    name: 'keterangan',     
                                    allowBlank: false,
                                    id: 'ehj_keterangan',                                      
                                    width: 300
                                }]				
                        }]
                }
            ],
            buttons: [{
                    text: 'Save',
                    formBind: true,
                    handler: function(){
			var validasi = true;
                        var kd_produk = '';
                        strhargapenjualan.each(function(node){
                            var tgl_start_diskon = node.data.tgl_start_diskon;
                            var tgl_end_diskon = node.data.tgl_end_diskon;
                            var kode_produk = node.data.kd_produk;
                            
                            if (tgl_end_diskon < tgl_start_diskon){
                                validasi= false;
                                kd_produk = kode_produk;
                            }
                           });
//                        if(!validasi){
//                                  
//                                    Ext.Msg.show({
//                                                    title: 'Error',
//                                                    msg: 'Kode Produk <code>' + kd_produk + '</code> Tanggal Akhir Diskon Tidak Boleh Lebih Kecil dari Tanggal Mulai Diskon',
//                                                    modal: true,
//                                                    icon: Ext.Msg.ERROR,
//                                                    buttons: Ext.Msg.OK,
//                                                    fn: function(btn){
//                                                        //this.focus();
//                                                       }
//                                                });
//                                                Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
//                                     return;
//                                     
//                               }
                        var detailhargapenjualan = new Array();              
                        strhargapenjualan.each(function(node){
                            detailhargapenjualan.push(node.data)
                        });
                        Ext.getCmp('hargapenjualan').getForm().submit({
                            url: '<?= site_url("harga_penjualan/update_row") ?>',
                            scope: this,
                            params: {
                                detail: Ext.util.JSON.encode(detailhargapenjualan)
                            },
                            waitMsg: 'Saving Data...',
                            success: function(form, action){
                                Ext.Msg.show({
                                    title: 'Success',
                                    msg: 'Form submitted successfully',
                                    modal: true,
                                    icon: Ext.Msg.INFO,
                                    buttons: Ext.Msg.OK
                                });                     
                        
                                clearhargapenjualan();                       
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
                },{
                    text: 'Reset',
                    handler: function(){
                        clearhargapenjualan();
                    }
                }]
        });
    
        hargapenjualan.on('afterrender', function(){
            this.getForm().load({
                url: '<?= site_url("harga_penjualan/get_form") ?>',
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
    
        function clearhargapenjualan(){
            Ext.getCmp('hargapenjualan').getForm().reset();
            Ext.getCmp('hargapenjualan').getForm().load({
                url: '<?= site_url("harga_penjualan/get_form") ?>',
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
            strhargapenjualan.removeAll();
        }
</script>
