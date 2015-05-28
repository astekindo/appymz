<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">	
    /* START twin produk*/
	
    var strcbsbjproduk = new Ext.data.ArrayStore({
        fields: ['kd_produk', 'nama_produk'],
        data : []
    });
	
    var strgridsbjproduk = new Ext.data.Store({
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
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
	
    var searchgridsbjproduk = new Ext.app.SearchField({
        store: strgridsbjproduk,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridsbjproduk'
    });
	
	
    var gridsbjproduk = new Ext.grid.GridPanel({
        store: strgridsbjproduk,
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
            items: [searchgridsbjproduk]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridsbjproduk,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {	
                    Ext.getCmp('id_cbsbjproduk').setValue(sel[0].get('kd_produk'));
                    menusbjproduk.hide();
                }
            }
        }
    });
	
    var menusbjproduk = new Ext.menu.Menu();
    menusbjproduk.add(new Ext.Panel({
        title: 'Pilih Produk',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridsbjproduk],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menusbjproduk.hide();
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
            var kd_supplier =  Ext.getCmp('id_cbsbpsuplier').getValue();
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
            strgridsbjproduk.load({
                params:{
                    start: STARTPAGE,
                    limit: ENDPAGE,
                    kd_supplier: Ext.getCmp('id_cbsbpsuplier').getValue(),
                    kd_kategori1: Ext.getCmp('sbj_cbkategori1').getValue(),
                    kd_kategori2: Ext.getCmp('sbj_cbkategori2').getValue(),
                    kd_kategori3: Ext.getCmp('sbj_cbkategori3').getValue(),
                    kd_kategori4: Ext.getCmp('sbj_cbkategori4').getValue(),
                    no_bukti: Ext.getCmp('id_cbsbpnobuktifilter').getValue(),
                    konsinyasi: Ext.getCmp('sbp_konsinyasi').getValue(),
                    list: Ext.getCmp('esbp_list').getValue()
                }
            });
            menusbjproduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menusbjproduk.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridsbjproduk').getValue();
        if( sf !== ''){
            Ext.getCmp('id_searchgridsbjproduk').setValue('');
            searchgridsbjproduk.onTrigger2Click();
        }
    });
	
    var cbsbjproduk = new Ext.ux.TwinCombohjproduk({
        id: 'id_cbsbjproduk',
        store: strcbsbjproduk,
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
	
    var strcbsbjnobukti = new Ext.data.ArrayStore({
        fields: ['no_bukti','keterangan'],
        data : []
    });
	
    var strgridsbjnobukti = new Ext.data.Store({
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
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
	
    var searchgridsbjnobukti = new Ext.app.SearchField({
        store: strgridsbjnobukti,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridsbjnobukti'
    });
	
	
    var gridsbjnobukti = new Ext.grid.GridPanel({
        store: strgridsbjnobukti,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'No Bukti',
                dataIndex: 'no_bukti',
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
            items: [searchgridsbjnobukti]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridsbjnobukti,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {	
                    Ext.getCmp('id_cbsbjnobukti').setValue(sel[0].get('no_bukti'));
					        
                    menusbjnobukti.hide();
                }
            }
        }
    });
	
    var menusbjnobukti = new Ext.menu.Menu();
    menusbjnobukti.add(new Ext.Panel({
        title: 'Pilih No Bukti',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 500,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridsbjnobukti],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menusbjnobukti.hide();
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
            strgridsbjnobukti.load();
            menusbjnobukti.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menusbjnobukti.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridsbjnobukti').getValue();
        if( sf !== ''){
            Ext.getCmp('id_searchgridsbjnobukti').setValue('');
            searchgridsbjnobukti.onTrigger2Click();
        }
    });
	
    var cbsbjnobukti = new Ext.ux.TwinCombohjnobukti({
        fieldLabel: 'No Bukti <span class="asterix">*</span>',
        id: 'id_cbsbjnobukti',
        store: strcbsbjnobukti,
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
	
    var strcbsbpnobuktifilter = new Ext.data.ArrayStore({
        fields: ['no_bukti','keterangan'],
        data : []
    });
	
    var strgridsbpnobuktifilter = new Ext.data.Store({
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
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
	
    var searchgridsbpnobuktifilter = new Ext.app.SearchField({
        store: strgridsbpnobuktifilter,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridsbpnobuktifilter'
    });
	
	
    var gridsbpnobuktifilter = new Ext.grid.GridPanel({
        store: strgridsbpnobuktifilter,
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
            items: [searchgridsbpnobuktifilter]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridsbpnobuktifilter,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {	
                    Ext.getCmp('id_cbsbpnobuktifilter').setValue(sel[0].get('no_bukti_filter'));
					        
                    menusbpnobuktifilter.hide();
                }
            }
        }
    });
	
    var menusbpnobuktifilter = new Ext.menu.Menu();
    menusbpnobuktifilter.add(new Ext.Panel({
        title: 'Pilih No Bukti',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 500,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridsbpnobuktifilter],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menusbpnobuktifilter.hide();
                }
            }]
    }));
    
    Ext.ux.TwinCombosbpnobuktifilter = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgridsbpnobuktifilter.load();
            menusbpnobuktifilter.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menusbpnobuktifilter.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridsbpnobuktifilter').getValue();
        if( sf !== ''){
            Ext.getCmp('id_searchgridsbpnobuktifilter').setValue('');
            searchgridsbpnobuktifilter.onTrigger2Click();
        }
    });
	
    var cbsbpnobuktifilter = new Ext.ux.TwinCombosbpnobuktifilter({
        fieldLabel: 'No Bukti Filter',
        id: 'id_cbsbpnobuktifilter',
        store: strcbsbpnobuktifilter,
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
    var strsetbonuspenjualanhistory = new Ext.data.Store({
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
                {name: 'tgl_approve', allowBlank: false, type: 'text'}
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
	
    var gridsetbonuspenjualanhistory = new Ext.grid.GridPanel({
        store: strsetbonuspenjualanhistory,
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
                header: 'Ket. Perubahan',
                dataIndex: 'keterangan',                                     
                width: 300
            },{	
                header: 'Approved By',
                dataIndex: 'approve_by',                                     
                width: 300
            }]
    });
	
    var winsetbonuspenjualanprint = new Ext.Window({
        id: 'id_winsetbonuspenjualanprint',
        title: 'Print History Harga Penjualan',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="setbonuspenjualanprint" src=""></iframe>'
    });
	
    Ext.ns('setbonuspenjualanform');
    setbonuspenjualanform.Form = Ext.extend(Ext.form.FormPanel, {
    
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
            setbonuspenjualanform.Form.superclass.constructor.call(this, config);
        },
        initComponent: function(){
        
            // hard coded - cannot be changed from outsid
            var config = {
                layout:'form',
                items: [gridsetbonuspenjualanhistory],
                buttons: [{
                        text: 'Cetak',
                        id: 'btnCetaksetbonuspenjualan',
                        scope: this,
                        handler: function(){
                            function isEmpty(str) {
                                return (!str || 0 === str.length);
                            }
                            var no_bukti = Ext.getCmp('id_cbsbjnobukti').getValue();
                            var kd_produk = Ext.getCmp('id_cbsbjproduk').getValue();
								
                            if(isEmpty(no_bukti)){
                                no_bukti = 0;
                            }
                            winsetbonuspenjualanprint.show();
                            Ext.getDom('setbonuspenjualanprint').src = '<?= site_url("harga_penjualan/print_form") ?>' +'/'+no_bukti+'/'+kd_produk;
                        }
                    },{
                        text: 'Close',
                        id: 'btnClosesetbonuspenjualan',
                        scope: this,
                        handler: function(){
                            winshowhistorysetbonuspenjualan.hide();
                        }
                    }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            
            // call parent
            setbonuspenjualanform.Form.superclass.initComponent.apply(this, arguments);
            
        } // eo function initComponent  
        ,
        onRender: function(){
        
            // call parent
            setbonuspenjualanform.Form.superclass.onRender.apply(this, arguments);
            
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
    Ext.reg('formaddsetbonuspenjualan', setbonuspenjualanform.Form);
    
    var winshowhistorysetbonuspenjualan = new Ext.Window({
        id: 'id_winshowhistorysetbonuspenjualan',
        closeAction: 'hide',
        width: 1000,
        height: 500,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_formaddsetbonuspenjualan',
            xtype: 'formaddsetbonuspenjualan'
        },
        onHide: function(){
            Ext.getCmp('id_formaddsetbonuspenjualan').getForm().reset();
        }
    });

    var strcbkdproduksbj = new Ext.data.Store({
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
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
	
    var searchsbjproduk = new Ext.app.SearchField({
        store: strcbkdproduksbj,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'hjsearchlistbarang'
    });
    
    var tbsbjproduk = new Ext.Toolbar({
        items: [searchsbjproduk]
    });
	
    var gridsbjsearchproduk = new Ext.grid.GridPanel({
        store: strcbkdproduksbj,
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
                                if(senders === 'sbj_kd_produk_bonus'){
                                    Ext.getCmp(senders).setValue(sel[0].get('kd_produk'));
                                    Ext.getCmp('sbj_nama_produk_bonus').setValue(sel[0].get('nama_produk'));
                                }else if(senders === 'sbj_kd_produk_member'){{
                                        Ext.getCmp(senders).setValue(sel[0].get('kd_produk'));
                                        Ext.getCmp('sbj_nama_produk_member').setValue(sel[0].get('nama_produk'));
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
                    menusbj.hide();
                }
            }
        },
        tbar:tbsbjproduk,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strcbkdproduksbj,
            displayInfo: true
        })
    });
	
	

    var menusbj = new Ext.menu.Menu();
    menusbj.add(new Ext.Panel({
        title: 'Pilih Produk',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 500,
        height: 400,
        closeAction: 'hide',
        plain: true,
        items: [gridsbjsearchproduk],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menusbj.hide();
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
            strcbkdproduksbj.load();
            menusbj.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    var strsetbonuspenjualan = new Ext.data.Store({
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
                {name: 'keterangan', allowBlank: false, type: 'text'}
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
	
    strsetbonuspenjualan.on('load',function(){
        strsetbonuspenjualan.setBaseParam('kd_supplier',Ext.getCmp('id_cbsbpsuplier').getValue());
        strsetbonuspenjualan.setBaseParam('kd_kategori1',Ext.getCmp('sbj_cbkategori1').getValue());
        strsetbonuspenjualan.setBaseParam('kd_kategori2',Ext.getCmp('sbj_cbkategori2').getValue());
        strsetbonuspenjualan.setBaseParam('kd_kategori3',Ext.getCmp('sbj_cbkategori3').getValue());
        strsetbonuspenjualan.setBaseParam('kd_kategori4',Ext.getCmp('sbj_cbkategori4').getValue());
        strsetbonuspenjualan.setBaseParam('kd_ukuran',Ext.getCmp('id_sbj_cbukuran').getValue());
        strsetbonuspenjualan.setBaseParam('no_bukti',Ext.getCmp('id_cbsbpnobuktifilter').getValue());
        strsetbonuspenjualan.setBaseParam('konsinyasi',Ext.getCmp('sbp_konsinyasi').getValue());
    });
	
    strsetbonuspenjualan.on('update',function(){
        var net_price = Ext.getCmp('esbj_hrg_beli_satuan').getValue();
        var edited = Ext.getCmp('sbj_edited').getValue();
        if(net_price === 0 && edited === 'Y'){
            Ext.Msg.show({
                title: 'Warning',
                msg: 'Net Price Pembelian Masih 0',
                modal: true,
                icon: Ext.Msg.ERROR,
                buttons: Ext.Msg.OK
            });
            return;
        }
        if(Ext.getCmp('esbj_het_cogs').getValue() === 0){
            if(Ext.getCmp('esbj_rp_jual_supermarket').getValue() < Ext.getCmp('esbj_rp_het_harga_beli').getValue()){
                Ext.getCmp('esbj_rp_jual_supermarket').setValue('0');
                Ext.Msg.show({
                    title: 'Error',
                    msg: 'Net Price Jual tidak boleh lebih kecil dari HET Net Price Beli (inc PPN 1)',
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK,
                    fn: function(btn){
                        Ext.getCmp('esbj_rp_jual_supermarket').focus();
                    }
                });
                Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');  
            }
            if(Ext.getCmp('esbj_rp_cogs').getValue() > 0){
                if(Ext.getCmp('esbj_net_price_jual_kons').getValue() < Ext.getCmp('esbj_rp_cogs').getValue()){
                    Ext.getCmp('esbj_net_price_jual_kons').setValue('0');
                    Ext.Msg.show({
                        title: 'Error',
                        msg: 'Net Price Jual tidak boleh lebih kecil dari HET COGS (inc PPN)',
                        modal: true,
                        icon: Ext.Msg.ERROR,
                        buttons: Ext.Msg.OK,
                        fn: function(btn){
                            Ext.getCmp('esbj_net_price_jual_kons').focus();
                        }
                    });
                    Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');  
                }
            }
        }else{
            if(Ext.getCmp('esbj_rp_jual_supermarket').getValue() < Ext.getCmp('esbj_het_cogs').getValue()){
                Ext.getCmp('esbj_rp_jual_supermarket').setValue('0');
                Ext.Msg.show({
                    title: 'Error',
                    msg: 'Net Price Jual tidak boleh lebih kecil dari HET COGS (inc PPN)',
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK,
                    fn: function(btn){
                        Ext.getCmp('esbj_rp_jual_supermarket').focus();
                    }
                });
                Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');  
            }
            //if(Ext.getCmp('esbj_net_price_jual_kons').getValue() < Ext.getCmp('esbj_rp_het_harga_beli').getValue()){
            //	Ext.getCmp('esbj_net_price_jual_kons').setValue('0');
            //	Ext.Msg.show({
            //		title: 'Error',
            //		msg: 'Net Price Jual tidak boleh lebih kecil dari HET Net Price Beli (inc PPN 3)',
            //		modal: true,
            //		icon: Ext.Msg.ERROR,
            //		buttons: Ext.Msg.OK,
            //		fn: function(btn){
            //			Ext.getCmp('esbj_net_price_jual_kons').focus();
            //		}
            //	});
            //	Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');  
            //}
		
        }
        /*
                if(Ext.getCmp('ehj_rp_jual_distribusi').getValue() < Ext.getCmp('esbj_rp_het_harga_beli').getValue()){
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
        var hrg_beli = Ext.getCmp('esbj_hrg_beli_satuan').getValue();
        var cogs = Ext.getCmp('esbj_rp_cogs').getValue();
        var ongkos = Ext.getCmp('esbj_rp_ongkos_kirim').getValue();
        var margin_op = Ext.getCmp('sbj_margin_op').getValue()
        var margin = Ext.getCmp('esbj_margin').getValue();
        var margin_rp = 0;
        if(margin_op === "%"){
            margin_rp = (margin*hrg_beli)/100;
            margin_pct = margin;
        }else{
            margin_rp = margin;
            margin_pct = (margin*100)/hrg_beli;
        }
        ongkos = ongkos+(ongkos*0.1);
        var HET = hrg_beli+ongkos+margin_rp;
			

        if(margin_op === "%"){
            margin_rp = (margin*cogs)/100;
            margin_pct = margin;
        }else{
            margin_rp = margin;
            margin_pct = (margin*100)/cogs;
        }
        ongkos = Ext.getCmp('esbj_rp_ongkos_kirim').getValue();
        var HETCOGS = (cogs+ongkos+margin_rp) * 1.1;
        if(cogs === 0){
            HETCOGS = 0;
        }
        Ext.getCmp('esbj_rp_het_harga_beli').setValue(HET);
        Ext.getCmp('esbj_het_cogs').setValue(HETCOGS);
        Ext.getCmp('esbj_pct_margin').setValue(margin_pct);
        Ext.getCmp('esbj_rp_margin').setValue(margin_rp);
        Edited_bonus();
    };
	
    function Edited_bonus(){
        Ext.getCmp('sbj_edited').setValue('Y');
    };
	
    function HitungNetPJualBonus(){
        Edited_bonus();
        var total_disk = 0;
        var rp_jual_supermarket = Ext.getCmp('esbj_rp_jual_supermarket').getValue();
        var disk_kons1_op = Ext.getCmp('sbj_disk_kons1_op').getValue();
        var disk_kons1 = Ext.getCmp('sbj_disk_kons1').getValue();
        if (disk_kons1_op === '%'){
            // disk_kons1 = (disk_kons1*rp_jual_supermarket)/100;
            total_disk = rp_jual_supermarket-(rp_jual_supermarket*(disk_kons1/100));
        }else{
            total_disk = rp_jual_supermarket-disk_kons1;				
        }
			
        var disk_kons2_op = Ext.getCmp('sbj_disk_kons2_op').getValue();
        var disk_kons2 = Ext.getCmp('sbj_disk_kons2').getValue();
        if (disk_kons2_op === '%'){
            // disk_kons2 = (disk_kons2*disk_kons1)/100;
            total_disk =  total_disk-(total_disk*(disk_kons2/100));
        }else{
            total_disk = total_disk-disk_kons2;				
        }
			
        var disk_kons3_op = Ext.getCmp('sbj_disk_kons3_op').getValue();
        var disk_kons3 = Ext.getCmp('sbj_disk_kons3').getValue();
        if (disk_kons3_op === '%'){
            // disk_kons3 = (disk_kons3*disk_kons2)/100;
            total_disk = total_disk-(total_disk*(disk_kons3/100));
        }else{
            total_disk = total_disk-disk_kons3;				
        }
			
        var disk_kons4_op = Ext.getCmp('sbj_disk_kons4_op').getValue();
        var disk_kons4 = Ext.getCmp('sbj_disk_kons4').getValue();
        if (disk_kons4_op === '%'){
            // disk_kons4 = (disk_kons4*disk_kons3)/100;
            total_disk = total_disk-(total_disk*(disk_kons4/100));
        }else{
            total_disk = total_disk-disk_kons4;				
        }
			
        var total_disk = total_disk-Ext.getCmp('sbj_disk_kons5').getValue();

        var net_jual_kons = total_disk;
        Ext.getCmp('esbj_net_price_jual_kons').setValue(net_jual_kons);
			
			
        var disk_member1_op = Ext.getCmp('sbj_disk_member1_op').getValue();
        var disk_member1 = Ext.getCmp('sbj_disk_member1').getValue();
        if (disk_member1_op === '%'){
            // disk_member1 = (disk_member1*rp_jual_supermarket)/100;
            total_disk = rp_jual_supermarket-(rp_jual_supermarket*(disk_member1/100));
        }else{
            total_disk = rp_jual_supermarket-disk_member1;				
        }
			
        var disk_member2_op = Ext.getCmp('sbj_disk_member2_op').getValue();
        var disk_member2 = Ext.getCmp('sbj_disk_member2').getValue();
        if (disk_member2_op === '%'){
            // disk_member2 = (disk_member2*disk_member1)/100;
            total_disk = total_disk-(total_disk*(disk_member2/100));
        }else{
            total_disk = total_disk-disk_member2;				
        }
			
        var disk_member3_op = Ext.getCmp('sbj_disk_member3_op').getValue();
        var disk_member3 = Ext.getCmp('sbj_disk_member3').getValue();
        if (disk_member3_op === '%'){
            // disk_member3 = (disk_member3*disk_member2)/100;
            total_disk = total_disk-(total_disk*(disk_member3/100));
        }else{
            total_disk = total_disk-disk_member3;				
        }
			
        var disk_member4_op = Ext.getCmp('sbj_disk_member4_op').getValue();
        var disk_member4 = Ext.getCmp('sbj_disk_member4').getValue();
        if (disk_member4_op === '%'){
            // disk_member4 = (disk_member4*disk_member3)/100;
            total_disk = total_disk-(total_disk*(disk_member4/100));
        }else{
            total_disk = total_disk-disk_member4;				
        }
			
        var total_disk = total_disk - Ext.getCmp('sbj_disk_amt_member5').getValue();

        var net_price_memb = total_disk;
        Ext.getCmp('esbj_net_price_jual_member').setValue(net_price_memb);			
    }
    // combobox kategori1
    var str_sbj_cbkategori1 = new Ext.data.Store({
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
                var r = new (str_sbj_cbkategori1.recordType)({
                    'kd_kategori1': '',
                    'nama_kategori1': '-----'
                });
                str_sbj_cbkategori1.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var sbj_cbkategori1 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 1',
        id: 'sbj_cbkategori1',
        store: str_sbj_cbkategori1,
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
                var kdsbj_cbkategori1 = sbj_cbkategori1.getValue();
                // sbj_cbkategori2.setValue();
                sbj_cbkategori2.store.proxy.conn.url = '<?= site_url("kategori3/get_kategori2") ?>/' + kdsbj_cbkategori1;
                sbj_cbkategori2.store.reload();
				            
            }
        }
    });
    var str_sbj_cbkategori1_konsumen = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori1', 'nama_kategori1'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_barang/get_kategori1") ?>',
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

    var sbj_cbkategori1_konsumen = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 1',
        id: 'mb_sbj_cbkategori1_konsumen',
        store: str_sbj_cbkategori1_konsumen,
        valueField: 'kd_kategori1',
        displayField: 'nama_kategori1',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        width: 250,
        anchor: '90%',
        hiddenName: 'nama_kategori1_bonus',
        emptyText: 'Pilih kategori 1',
        listeners: {
            'select': function(combo, records) {
                var kdsbj_cbkategori1_konsumen = sbj_cbkategori1_konsumen.getValue();
                sbj_cbkategori2_konsumen.setValue();
                sbj_cbkategori3_konsumen.setValue();
                sbj_cbkategori4_konsumen.setValue();
                sbj_cbkategori2_konsumen.store.proxy.conn.url = '<?= site_url("master_barang/get_kategori2") ?>/' + kdsbj_cbkategori1_konsumen;
                sbj_cbkategori2_konsumen.store.reload();
            }
        }
    });
    
    var sbj_cbkategori1_member = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 1',
        id: 'mb_sbj_cbkategori1_member',
        store: str_sbj_cbkategori1_konsumen,
        valueField: 'kd_kategori1',
        displayField: 'nama_kategori1',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        width: 250,
        anchor: '90%',
        hiddenName: 'nama_kategori1_member',
        emptyText: 'Pilih kategori 1',
        listeners: {
            'select': function(combo, records) {
                var kdsbj_cbkategori1_member = sbj_cbkategori1_member.getValue();
                sbj_cbkategori2_member.setValue();
                sbj_cbkategori3_member.setValue();
                sbj_cbkategori4_member.setValue();
                sbj_cbkategori2_member.store.proxy.conn.url = '<?= site_url("master_barang/get_kategori2") ?>/' + kdsbj_cbkategori1_member;
                sbj_cbkategori2_member.store.reload();
            }
        }
    });
    
    // combobox kategori2
    var str_sbj_cbkategori2 = new Ext.data.Store({
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
                var r = new (str_sbj_cbkategori2.recordType)({
                    'kd_kategori2': '',
                    'nama_kategori2': '-----'
                });
                str_sbj_cbkategori2.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var sbj_cbkategori2 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 2 ',
        id: 'sbj_cbkategori2',
        mode: 'local',
        store: str_sbj_cbkategori2,
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
                var kd_sbj_cbkategori1 = sbj_cbkategori1.getValue();
                var kd_sbj_cbkategori2 = this.getValue();
                sbj_cbkategori3.setValue();
                sbj_cbkategori3.store.proxy.conn.url = '<?= site_url("kategori4/get_kategori3") ?>/' + kd_sbj_cbkategori1 +'/'+ kd_sbj_cbkategori2;
                sbj_cbkategori3.store.reload();
				          
            }
        }
    });
    var str_sbj_cbkategori2_konsumen = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori2', 'nama_kategori2'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({			
            url: '<?= site_url("master_barang/get_kategori2") ?>',
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
	
    var sbj_cbkategori2_konsumen = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 2',
        id: 'mb_sbj_cbkategori2_konsumen',
        mode: 'local',
        store: str_sbj_cbkategori2_konsumen,
        valueField: 'kd_kategori2',
        displayField: 'nama_kategori2',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        width: 250,
        anchor: '90%',
        hiddenName: 'nama_kategori2_bonus',
        emptyText: 'Pilih kategori 2',
        listeners: {
            select: function(combo, records) {
                var kd_sbj_cbkategori1_konsumen = sbj_cbkategori1_konsumen.getValue();
                var kd_sbj_cbkategori2_konsumen = this.getValue();
                sbj_cbkategori3_konsumen.setValue();
                sbj_cbkategori4_konsumen.setValue();
                sbj_cbkategori3_konsumen.store.proxy.conn.url = '<?= site_url("master_barang/get_kategori3") ?>/' + kd_sbj_cbkategori1_konsumen +'/'+ kd_sbj_cbkategori2_konsumen;
                sbj_cbkategori3_konsumen.store.reload();
            }
        }
    });
	
    var sbj_cbkategori2_member = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 2',
        id: 'mb_sbj_cbkategori2_member',
        mode: 'local',
        store: str_sbj_cbkategori2_konsumen,
        valueField: 'kd_kategori2',
        displayField: 'nama_kategori2',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        width: 250,
        anchor: '90%',
        hiddenName: 'nama_kategori2_member',
        emptyText: 'Pilih kategori 2',
        listeners: {
            select: function(combo, records) {
                var kd_sbj_cbkategori1_member = sbj_cbkategori1_member.getValue();
                var kd_sbj_cbkategori2_member = this.getValue();
                sbj_cbkategori3_member.setValue();
                sbj_cbkategori4_member.setValue();
                sbj_cbkategori3_member.store.proxy.conn.url = '<?= site_url("master_barang/get_kategori3") ?>/' + kd_sbj_cbkategori1_member +'/'+ kd_sbj_cbkategori2_member;
                sbj_cbkategori3_member.store.reload();
            }
        }
    });
    
    // combobox kategori3
    var str_sbj_cbkategori3 = new Ext.data.Store({
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
                var r = new (str_sbj_cbkategori3.recordType)({
                    'kd_kategori3': '',
                    'nama_kategori3': '-----'
                });
                str_sbj_cbkategori3.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
	
    var sbj_cbkategori3 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 3 ',
        id: 'sbj_cbkategori3',
        mode: 'local',
        store: str_sbj_cbkategori3,
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
                var kd_sbj_cbkategori1 = sbj_cbkategori1.getValue();
                var kd_sbj_cbkategori2 = sbj_cbkategori2.getValue();
                var kd_sbj_cbkategori3 = this.getValue();
                sbj_cbkategori4.setValue();
                sbj_cbkategori4.store.proxy.conn.url = '<?= site_url("master_barang/get_kategori4") ?>/' + kd_sbj_cbkategori1 +'/'+ kd_sbj_cbkategori2 +'/'+ kd_sbj_cbkategori3;
                sbj_cbkategori4.store.reload();				
				           

            }
        }
    });
     var str_sbj_cbkategori3_konsumen = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kategori3', 'nama_kategori3'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_barang/get_kategori3") ?>',
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
	
    var sbj_cbkategori3_konsumen = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 3',
        id: 'mb_sbj_cbkategori3_konsumen',
        mode: 'local',
        store: str_sbj_cbkategori3_konsumen,
        valueField: 'kd_kategori3',
        displayField: 'nama_kategori3',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        width: 250,
        anchor: '90%',
        hiddenName: 'nama_kategori3_bonus',
        emptyText: 'Pilih kategori 3',
        listeners: {
            select: function(combo, records) {
                var kd_sbj_cbkategori1_konsumen = sbj_cbkategori1_konsumen.getValue();
                var kd_sbj_cbkategori2_konsumen = sbj_cbkategori2_konsumen.getValue();
                var kd_sbj_cbkategori3_konsumen = this.getValue();
                sbj_cbkategori4_konsumen.setValue();
                sbj_cbkategori4_konsumen.store.proxy.conn.url = '<?= site_url("master_barang/get_kategori4") ?>/' + kd_sbj_cbkategori1_konsumen +'/'+ kd_sbj_cbkategori2_konsumen +'/'+ kd_sbj_cbkategori3_konsumen;
                sbj_cbkategori4_konsumen.store.reload();
            }
        }
    });
	
    var sbj_cbkategori3_member = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 3',
        id: 'mb_sbj_cbkategori3_member',
        mode: 'local',
        store: str_sbj_cbkategori3_konsumen,
        valueField: 'kd_kategori3',
        displayField: 'nama_kategori3',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        width: 250,
        anchor: '90%',
        hiddenName: 'nama_kategori3_member',
        emptyText: 'Pilih kategori 3',
        listeners: {
            select: function(combo, records) {
                var kd_sbj_cbkategori1_member = sbj_cbkategori1_member.getValue();
                var kd_sbj_cbkategori2_member = sbj_cbkategori2_member.getValue();
                var kd_sbj_cbkategori3_member = this.getValue();
                sbj_cbkategori4_member.setValue();
                sbj_cbkategori4_member.store.proxy.conn.url = '<?= site_url("master_barang/get_kategori4") ?>/' + kd_sbj_cbkategori1_member +'/'+ kd_sbj_cbkategori2_member +'/'+ kd_sbj_cbkategori3_member;
                sbj_cbkategori4_member.store.reload();
            }
        }
    });
	
    // combobox kategori4
    var str_sbj_cbkategori4 = new Ext.data.Store({
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
                var r = new (str_sbj_cbkategori4.recordType)({
                    'kd_kategori4': '',
                    'nama_kategori4': '-----'
                });
                str_sbj_cbkategori4.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var sbj_cbkategori4 = new Ext.form.ComboBox({   
        fieldLabel: 'Kategori 4 ',
        id: 'sbj_cbkategori4',
        mode: 'local',
        store: str_sbj_cbkategori4,
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
    var str_sbj_cbkategori4_konsumen = new Ext.data.Store({
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
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var sbj_cbkategori4_konsumen = new Ext.form.ComboBox({   
        fieldLabel: 'Kategori 4',
        id: 'mb_sbj_cbkategori4_konsumen',
        mode: 'local',
        store: str_sbj_cbkategori4_konsumen,
        valueField: 'kd_kategori4',
        displayField: 'nama_kategori4',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        width: 250,
        anchor: '90%',
        hiddenName: 'nama_kategori4_bonus',
        emptyText: 'Pilih kategori 4'
    });
	
    var sbj_cbkategori4_member = new Ext.form.ComboBox({   
        fieldLabel: 'Kategori 4',
        id: 'mb_sbj_cbkategori4_member',
        mode: 'local',
        store: str_sbj_cbkategori4_konsumen,
        valueField: 'kd_kategori4',
        displayField: 'nama_kategori4',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        width: 250,
        anchor: '90%',
        hiddenName: 'nama_kategori4_member',
        emptyText: 'Pilih kategori 4'
    });
     // combobox Ukuran
	var str_sbj_cbukuran = new Ext.data.Store({
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
                var r = new (str_sbj_cbukuran.recordType)({
                    'kd_ukuran': '',
                    'nama_ukuran': '-----'
                });
                str_sbj_cbukuran.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
    
    // combobox Satuan
	var str_sbj_cbsatuan = new Ext.data.Store({
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
                var r = new (str_sbj_cbsatuan.recordType)({
                    'kd_satuan': '',
                    'nm_satuan': '-----'
                });
                str_sbj_cbsatuan.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
    var sbj_cbsatuan = new Ext.form.ComboBox({
        fieldLabel: 'Satuan',
        id: 'id_sbj_cbsatuan',
        store: str_sbj_cbsatuan,
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
    
    var sbj_cbukuran = new Ext.form.ComboBox({
        fieldLabel: 'Ukuran',
        id: 'id_sbj_cbukuran',
        store: str_sbj_cbukuran,
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
	
    var searchgridsetbonuspenjualan = new Ext.app.SearchField({
        store: strsetbonuspenjualan,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridsetbonuspenjualan',
        emptyText: 'Kode Barang, Kode Barang Lama, Nama Barang'
    });
	
    searchgridsetbonuspenjualan.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';
			
            // Get the value of search field
            var kd_kategori1 = Ext.getCmp('sbj_cbkategori1').getValue();
            var kd_kategori2 = Ext.getCmp('sbj_cbkategori2').getValue();
            var kd_kategori3 = Ext.getCmp('sbj_cbkategori3').getValue();
            var kd_kategori4 = Ext.getCmp('sbj_cbkategori4').getValue();
            var konsinyasi = Ext.getCmp('sbp_konsinyasi').getValue();
            var kd_supplier = Ext.getCmp('id_cbsbpsuplier').getValue();
            var list = Ext.getCmp('esbp_list').getValue();
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
	
    searchgridsetbonuspenjualan.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }

        // Get the value of search field
        var kd_kategori1 = Ext.getCmp('sbj_cbkategori1').getValue();
        var kd_kategori2 = Ext.getCmp('sbj_cbkategori2').getValue();
        var kd_kategori3 = Ext.getCmp('sbj_cbkategori3').getValue();
        var kd_kategori4 = Ext.getCmp('sbj_cbkategori4').getValue();
        var konsinyasi = Ext.getCmp('sbp_konsinyasi').getValue();
        var kd_supplier = Ext.getCmp('id_cbsbpsuplier').getValue();
        var list = Ext.getCmp('esbp_list').getValue();
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
	// Combo Supplier
    var strcbsbpsuplier = new Ext.data.ArrayStore({
        fields: ['kd_supplier'],
        data : []
    });
	
    var strgridsbpsuplier = new Ext.data.Store({
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
	
    var searchgridsbpsuplier = new Ext.app.SearchField({
        store: strgridsbpsuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridsbpsuplier'
    });
	
    strgridsbpsuplier.on('load', function(){
        Ext.getCmp('id_searchgridsbpsuplier').focus();
    });
	
    var gridsbpsuplier = new Ext.grid.GridPanel({
        store: strgridsbpsuplier,
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
            items: [searchgridsbpsuplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridsbpsuplier,
            displayInfo: true
        }),listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {	
                    Ext.getCmp('id_cbsbpsuplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('sbp_nama_supplier').setValue(sel[0].get('nama_supplier'));
                    if(sel[0].get('pkp') == '1'){
                        Ext.getCmp('sbp_pkp').setValue('YA');
                    }else{
                        Ext.getCmp('sbp_pkp').setValue('TIDAK');
                    }
					
					           
                    menusbpsuplier.hide();
                }
            }
        }
    });
	
    var menusbpsuplier = new Ext.menu.Menu();
    menusbpsuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridsbpsuplier],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menusbpsuplier.hide();
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
            strgridsbpsuplier.load();
            menusbpsuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menusbpsuplier.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridsbpsuplier').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgridsbpsuplier').setValue('');
            searchgridsbpsuplier.onTrigger2Click();
        }
    });
	
    var cbsbpsuplier = new Ext.ux.TwinCombohjsuplier({
        fieldLabel: 'Supplier <span class="asterix">*</span>',
        id: 'id_cbsbpsuplier',
        store: strcbsbpsuplier,
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
	//End Combo Suplier
    // Header Set Bonus Penjualan
    var headersetbonuspenjualan = {
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
                        id: 'sbp_no_hj',                
                        anchor: '90%',
                        value:''
                    },cbsbpsuplier,cbsbpnobuktifilter,
                    sbj_cbkategori1,sbj_cbkategori2,{
                        xtype: 'textarea',
                        fieldLabel: 'Kode Barang, Kode Barang Lama',
                        style:'text-transform: uppercase',
                        name: 'list',     
                        id: 'esbp_list',                 
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
                        id:'sbp_konsinyasi',
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
                        id: 'sbp_tanggal',                
                        anchor: '90%',
                        value: ''
                    },{
                        xtype: 'textfield',
                        fieldLabel: 'Nama Supplier',
                        name: 'nama_supplier',
                        readOnly:true,
                        fieldClass:'readonly-input',
                        id: 'sbp_nama_supplier',                
                        anchor: '90%',
                        value:''
                    },{
                        xtype: 'textfield',
                        fieldLabel: 'Status PKP',
                        name: 'pkp',
                        readOnly:true,
                        fieldClass:'readonly-input',
                        id: 'sbp_pkp',                
                        anchor: '90%',
                        value:''
                    },sbj_cbkategori3,sbj_cbkategori4,sbj_cbukuran,sbj_cbsatuan
                    
                ]
            },]
        ,
        buttons: [{
                text: 'Filter',
                formBind: true,
                handler: function(){
                    var kd_supplier =  Ext.getCmp('id_cbsbpsuplier').getValue();
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
                    strsetbonuspenjualan.load({
                        params:{
                            start: STARTPAGE,
                            limit: ENDPAGE,
                            kd_supplier: Ext.getCmp('id_cbsbpsuplier').getValue(),
                            kd_kategori1: Ext.getCmp('sbj_cbkategori1').getValue(),
                            kd_kategori2: Ext.getCmp('sbj_cbkategori2').getValue(),
                            kd_kategori3: Ext.getCmp('sbj_cbkategori3').getValue(),
                            kd_kategori4: Ext.getCmp('sbj_cbkategori4').getValue(),
                            kd_ukuran: Ext.getCmp('id_sbj_cbukuran').getValue(),
                            kd_satuan: Ext.getCmp('id_sbj_cbsatuan').getValue(),
                            konsinyasi: Ext.getCmp('sbp_konsinyasi').getValue(),
                            no_bukti: Ext.getCmp('id_cbsbpnobuktifilter').getValue(),
                            list: Ext.getCmp('esbp_list').getValue()
                        }
                    }); 
                }
            }]
    }
	
    var actionsetbonuspenjualan = new Ext.ux.grid.RowActions({
        header :'History',
        autoWidth: false,
        locked: true,
        width: 60,
        actions:[{iconCls: 'icon-history-record', qtip: 'Show History'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    }); 
   
    
    actionsetbonuspenjualan.on('action', function(grid, record, action, row, col) {
        var kd_supp = record.get('kd_supplier');
        var kd_prod = record.get('kd_produk');
        var nm_prod = record.get('nama_produk');
        switch(action) {
            case 'icon-history-record':
                var sm = gridsetbonuspenjualan.getSelectionModel();
                var sel = sm.getSelections();
                gridsetbonuspenjualanhistory.store.proxy.conn.url = '<?= site_url("harga_penjualan/search_produk_history") ?>/' +sel[0].get('kd_produk');
                gridsetbonuspenjualanhistory.store.reload();
                winshowhistorysetbonuspenjualan.setTitle('History');
                winshowhistorysetbonuspenjualan.show();
                break;            
            }
        });  
        var editorsetbonuspenjualan = new Ext.ux.grid.RowEditor({
            saveText: 'Update'		
        });

        var gridsetbonuspenjualan = new Ext.grid.GridPanel({
            store: strsetbonuspenjualan,
            stripeRows: true,
            height: 350,
            loadMask: true,
            frame: true,
            border:true,
            plugins: [editorsetbonuspenjualan],
            columns: [ {
                    dataIndex: 'kd_diskon_sales',
                    hidden: true,
                },{
                    dataIndex: 'pct_margin',
                    hidden: true,
                    editor: new Ext.form.TextField({
                        readOnly: true,
                        id: 'esbj_pct_margin'
                    })			
                },{
                    dataIndex: 'rp_margin',
                    hidden: true,
                    editor: new Ext.form.TextField({
                        readOnly: true,
                        id: 'esbj_rp_margin'
                    })
                },{
                    dataIndex: 'koreksi_diskon',
                    hidden: true
                },{
                    dataIndex: 'koreksi_produk',
                    hidden: true
                },{
                    // header: 'Edited_bonus',
                    // dataIndex: 'Edited_bonus',
                    // width: 50,
                    // sortable: true,
                    // editor: new Ext.form.TextField({
                    // readOnly: true,
                    // fieldClass: 'readonly-input',
                    // id: 'sbj_Edited_bonus'
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
                        id:           	'sbj_edited',
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
                        // Ext.getCmp('esbj_margin').setValue(0);
                        // HETChange();
                        // },
                        // select:function(){
                        // HETChange();
                        // Ext.getCmp('esbj_margin').setMaxValue(Number.MAX_VALUE);
                        // if (this.getValue() === 'persen') 
                        // Ext.getCmp('esbj_margin').maxValue = 100;
                        // else 
                        // Ext.getCmp('esbj_margin').maxLength = 11;
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
                        id: 'esbj_kd_produk'
                    })
                },{
                    header: 'Kode Barang Lama',
                    dataIndex: 'kd_produk_lama',
                    width: 110,
                    sortable: true,
                    editor: new Ext.form.TextField({
                        readOnly: true,
                        fieldClass: 'readonly-input',
                        id: 'esbj_kd_produk_lama'
                    })
                },{
                    header: 'Nama Barang',
                    dataIndex: 'nama_produk',
                    width: 300,
                    sortable: true,
                    editor: new Ext.form.TextField({
                        readOnly: true,
                        fieldClass: 'readonly-input',
                        id: 'esbj_nama_produk'
                    })
                },{
                    header: 'Satuan',
                    dataIndex: 'nm_satuan',
                    width: 80,
                    editor: new Ext.form.TextField({
                        readOnly: true,
                        fieldClass: 'readonly-input',
                        id: 'esbj_satuan'
                    })
                },{
                    header: 'Nama Supplier',
                    dataIndex: 'nama_supplier',
                    width: 130,
                    editor: new Ext.form.TextField({
                        readOnly: true,
                        fieldClass: 'readonly-input',
                        id: 'esbp_nama_supplier'
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
                        id: 'esbj_hrg_beli_satuan',
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
                        id: 'esbj_rp_cogs',
                        readOnly: true,
                        fieldClass: 'readonly-input',
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
                        id: 'esbj_rp_ongkos_kirim',
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
                                {name : 'Rp'},
                            ]
                        }),
                        id:           	'sbj_margin_op',
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
                                Ext.getCmp('esbj_margin').setValue(0);
                                HETChange();
                            },
                            select:function(){
                                HETChange();
                                Ext.getCmp('esbj_margin').setMaxValue(Number.MAX_VALUE);
                                if (this.getValue() === 'persen') 
                                    Ext.getCmp('esbj_margin').maxValue = 100;
                                else 
                                    Ext.getCmp('esbj_margin').maxLength = 11;
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
                        id: 'esbj_margin',
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
                        id: 'esbj_rp_het_harga_beli',
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
                        id: 'esbj_het_cogs',
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
                        id: 'esbj_rp_jual_supermarket',
                        listeners:{			
                            'change': function() {
                                if(Ext.getCmp('esbj_rp_cogs').getValue() > 0){
                                    if(this.getValue() < Ext.getCmp('esbj_rp_cogs').getValue()){
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
                                    if(this.getValue() < Ext.getCmp('esbj_rp_het_harga_beli').getValue()){
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
                                    HitungNetPJualBonus();
                                }, c);
                            }
                        }
                    }
                },/*{
            xtype: 'numbercolumn',
                        align: 'right',
            format: '0,0',
            header: 'Harga Jual Distribusi',
            dataIndex: 'rp_jual_distribusi',           
            width: 180,
                        editor: {
                xtype: 'numberfield',
                id: 'ehj_rp_jual_distribusi',
                                listeners:{			
                                        'change': function() {
                                                if(this.getValue() < Ext.getCmp('esbj_rp_het_harga_beli').getValue()){
                                                        this.setValue('0');
                                                        Ext.Msg.show({
                                                                title: 'Error',
                                                                msg: 'Harga Jual Tidak Boleh Lebih Kecil Dari HET Beli dan HET COGS',
                                                                modal: true,
                                                                icon: Ext.Msg.ERROR,
                                                                buttons: Ext.Msg.OK,
                                                                fn: function(btn){
                                                                        this.focus();
                                                                }
                                                        });
                                                        Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');  
                                                }
					  
                                        },
                                        'render': function(c) {
                                                  c.getEl().on('keyup', function() {
                                                        // Edited();
                                                  }, c);
                                                }
                                }
            }
                },*/{
                    header: '% / Rp',
                    dataIndex: 'disk_kons1_op',
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
                        id:           	'sbj_disk_kons1_op',
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
                                Ext.getCmp('sbj_disk_kons1').setValue(0);
                            },
                            select:function(){
                                Ext.getCmp('sbj_disk_kons1').setMaxValue(Number.MAX_VALUE);
                                if (this.getValue() === 'persen') 
                                    Ext.getCmp('sbj_disk_kons1').maxValue = 100;
                                else 
                                    Ext.getCmp('sbj_disk_kons1').maxLength = 11;
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
                        id: 'sbj_disk_kons1',
                        style: 'text-align:right;',
                        listeners:{
                            'render': function(c) {
                                c.getEl().on('keyup', function() {
                                    HitungNetPJualBonus();
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
                                {name : 'Rp'},
                            ]
                        }),
                        id:           	'sbj_disk_kons2_op',
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
                                Ext.getCmp('sbj_disk_kons2').setValue(0);
                            },
                            select:function(){
                                Ext.getCmp('sbj_disk_kons2').setMaxValue(Number.MAX_VALUE);
                                if (this.getValue() === 'persen') 
                                    Ext.getCmp('sbj_disk_kons2').maxValue = 100;
                                else 
                                    Ext.getCmp('sbj_disk_kons2').maxLength = 11;
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
                        id: 'sbj_disk_kons2',
                        style: 'text-align:right;',
                        listeners:{
                            'render': function(c) {
                                c.getEl().on('keyup', function() {
                                    // Edited();
                                    HitungNetPJualBonus();
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
                                {name : 'Rp'},
                            ]
                        }),
                        id:           	'sbj_disk_kons3_op',
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
                                Ext.getCmp('sbj_disk_kons3').setValue(0);
                            },
                            select:function(){
                                Ext.getCmp('sbj_disk_kons3').setMaxValue(Number.MAX_VALUE);
                                if (this.getValue() === 'persen') 
                                    Ext.getCmp('sbj_disk_kons3').maxValue = 100;
                                else 
                                    Ext.getCmp('sbj_disk_kons3').maxLength = 11;
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
                        id: 'sbj_disk_kons3',
                        style: 'text-align:right;',
                        listeners:{
                            'render': function(c) {
                                c.getEl().on('keyup', function() {
                                    // Edited();
                                    HitungNetPJualBonus();
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
                        id:           	'sbj_disk_kons4_op',
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
                                Ext.getCmp('sbj_disk_kons4').setValue(0);
                            },
                            select:function(){
                                Ext.getCmp('sbj_disk_kons4').setMaxValue(Number.MAX_VALUE);
                                if (this.getValue() === 'persen') 
                                    Ext.getCmp('sbj_disk_kons4').maxValue = 100;
                                else 
                                    Ext.getCmp('sbj_disk_kons4').maxLength = 11;
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
                        id: 'sbj_disk_kons4',
                        style: 'text-align:right;',
                        listeners:{
                            'render': function(c) {
                                c.getEl().on('keyup', function() {
                                    // Edited();
                                    HitungNetPJualBonus();
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
                        id: 'sbj_disk_kons5',
                        style: 'text-align:right;',
                        listeners:{
                            'render': function(c) {
                                c.getEl().on('keyup', function() {
                                    // Edited();
                                    HitungNetPJualBonus();
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
                        id: 'esbj_net_price_jual_kons',
                        readOnly: true,
                        fieldClass: 'readonly-input',
                        listeners:{			
                            'change': function() {
                                if(Ext.getCmp('esbj_rp_cogs').getValue() > 0){
                                    if(Ext.getCmp('esbj_net_price_jual_kons').getValue() < Ext.getCmp('esbj_rp_cogs').getValue()){
                                        Ext.getCmp('esbj_net_price_jual_kons').setValue('0');
                                        Ext.Msg.show({
                                            title: 'Error',
                                            msg: 'Net Price Jual Tidak Boleh Lebih Kecil Dari HET COGS',
                                            modal: true,
                                            icon: Ext.Msg.ERROR,
                                            buttons: Ext.Msg.OK,
                                            fn: function(btn){
                                                Ext.getCmp('esbj_net_price_jual_kons').focus();
                                            }
                                        });
                                        Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');  
                                    }
                                }else{
                                    if(Ext.getCmp('esbj_net_price_jual_kons').getValue() < Ext.getCmp('esbj_rp_het_harga_beli').getValue()){
                                        Ext.getCmp('esbj_net_price_jual_kons').setValue('0');
                                        Ext.Msg.show({
                                            title: 'Error',
                                            msg: 'Net Price Jual Tidak Boleh Lebih Kecil Dari HET Beli',
                                            modal: true,
                                            icon: Ext.Msg.ERROR,
                                            buttons: Ext.Msg.OK,
                                            fn: function(btn){
                                                Ext.getCmp('esbj_net_price_jual_kons').focus();
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
                                {name : 'Rp'},
                            ]
                        }),
                        id:           	'sbj_disk_member1_op',
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
                                Ext.getCmp('sbj_disk_member1').setValue(0);
                            },
                            select:function(){
                                Ext.getCmp('sbj_disk_member1').setMaxValue(Number.MAX_VALUE);
                                if (this.getValue() === 'persen') 
                                    Ext.getCmp('sbj_disk_member1').maxValue = 100;
                                else 
                                    Ext.getCmp('sbj_disk_member1').maxLength = 11;
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
                        id: 'sbj_disk_member1',
                        style: 'text-align:right;',
                        listeners:{
                            'render': function(c) {
                                c.getEl().on('keyup', function() {
                                    // Edited();
                                    HitungNetPJualBonus();
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
                                {name : 'Rp'},
                            ]
                        }),
                        id:           	'sbj_disk_member2_op',
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
                                Ext.getCmp('sbj_disk_member2').setValue(0);
                            },
                            select:function(){
                                Ext.getCmp('sbj_disk_member2').setMaxValue(Number.MAX_VALUE);
                                if (this.getValue() === 'persen') 
                                    Ext.getCmp('sbj_disk_member2').maxValue = 100;
                                else 
                                    Ext.getCmp('sbj_disk_member2').maxLength = 11;
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
                        id: 'sbj_disk_member2',
                        style: 'text-align:right;',
                        listeners:{
                            'render': function(c) {
                                c.getEl().on('keyup', function() {
                                    // Edited();
                                    HitungNetPJualBonus();
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
                                {name : 'Rp'},
                            ]
                        }),
                        id:           	'sbj_disk_member3_op',
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
                                Ext.getCmp('sbj_disk_member3').setValue(0);
                            },
                            select:function(){
                                Ext.getCmp('sbj_disk_member3').setMaxValue(Number.MAX_VALUE);
                                if (this.getValue() === 'persen') 
                                    Ext.getCmp('sbj_disk_member3').maxValue = 100;
                                else 
                                    Ext.getCmp('sbj_disk_member3').maxLength = 11;
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
                        id: 'sbj_disk_member3',
                        style: 'text-align:right;',
                        listeners:{
                            'render': function(c) {
                                c.getEl().on('keyup', function() {
                                    // Edited();
                                    HitungNetPJualBonus();
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
                                {name : 'Rp'},
                            ]
                        }),
                        id:           	'sbj_disk_member4_op',
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
                                Ext.getCmp('sbj_disk_member4').setValue(0);
                            },
                            select:function(){
                                Ext.getCmp('sbj_disk_member4').setMaxValue(Number.MAX_VALUE);
                                if (this.getValue() === 'persen') 
                                    Ext.getCmp('sbj_disk_member4').maxValue = 100;
                                else 
                                    Ext.getCmp('sbj_disk_member4').maxLength = 11;
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
                        id: 'sbj_disk_member4',
                        style: 'text-align:right;',
                        listeners:{
                            'render': function(c) {
                                c.getEl().on('keyup', function() {
                                    // Edited();
                                    HitungNetPJualBonus();
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
                        id: 'sbj_disk_amt_member5',
                        style: 'text-align:right;',
                        listeners:{
                            'render': function(c) {
                                c.getEl().on('keyup', function() {
                                    // Edited();
                                    HitungNetPJualBonus();
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
                        id: 'esbj_net_price_jual_member',
                        readOnly: true,
                        fieldClass: 'readonly-input',
                        listeners:{			
                            'change': function() {
                                if(Ext.getCmp('esbj_rp_cogs').getValue() > 0){
                                    if(this.getValue() < Ext.getCmp('esbj_rp_cogs').getValue()){
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
                                    if(this.getValue() < Ext.getCmp('esbj_rp_het_harga_beli').getValue()){
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
                        id: 'sbj_qty_beli_bonus',
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
                        id: 'sbj_kd_produk_bonus',
                        store: strcbkdproduksbj,
                        valueField: 'kd_produk_bonus',
                        displayField: 'kd_produk_bonus',
                        typeAhead: true,	
                        editable: false,
                        hiddenName: 'kd_produk_bonus',
                        emptyText: 'Pilih Kode Produk',    
                        listeners:{
                            'expand': function(){
                                strcbkdproduksbj.load();
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
                        id: 'sbj_qty_bonus',
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
                                {name : 'Tidak'},
                            ]
                        }),
                        id:           	'sbj_is_bonus_kelipatan',
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
                        id: 'sbj_qty_beli_member',
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
                        id: 'sbj_kd_produk_member',
                        store: strcbkdproduksbj,
                        valueField: 'kd_produk_member',
                        displayField: 'kd_produk_member',
                        typeAhead: true,	
                        editable: false,
                        hiddenName: 'kd_produk_member',
                        emptyText: 'Pilih Kode Produk',    
                        listeners:{
                            'expand': function(){
                                strcbkdproduksbj.load();
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
                        id: 'sbj_qty_member',
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
                                {name : 'Tidak'},
                            ]
                        }),
                        id:           	'sbj_is_member_kelipatan',
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
            
                },],
            tbar: new Ext.Toolbar({
                items: [searchgridsetbonuspenjualan, '->', cbsbjproduk, cbsbjnobukti, '-' ,{
                        text: 'Show History',
                        icon: BASE_ICONS + 'grid.png',
                        onClick: function(){
                            var kd_produk = Ext.getCmp('id_cbsbjproduk').getValue();
                            var no_bukti = Ext.getCmp('id_cbsbjnobukti').getValue();
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
                            gridsetbonuspenjualanhistory.store.load({
                                params:{
                                    no_bukti: Ext.getCmp('id_cbsbjnobukti').getValue(),
                                    kd_produk: Ext.getCmp('id_cbsbjproduk').getValue()
                                }
                            });
                            winshowhistorysetbonuspenjualan.setTitle('History');
                            winshowhistorysetbonuspenjualan.show();	
                            // var sm = gridsetbonuspenjualan.getSelectionModel();
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
                            // gridsetbonuspenjualanhistory.store.proxy.conn.url = '<?= site_url("harga_penjualan/search_produk_history") ?>/' +sel[0].get('kd_produk');
                            // gridsetbonuspenjualanhistory.store.reload();
                            // winshowhistorysetbonuspenjualan.setTitle('History');
                            // winshowhistorysetbonuspenjualan.show();				        
                        }
                    },'-',{
                        text: 'Reset',
                        icon: BASE_ICONS + 'refresh.gif',
                        onClick: function(){		
                            Ext.getCmp('id_cbsbjnobukti').setValue('');
                            Ext.getCmp('id_cbsbjproduk').setValue('');		        
                        }
                    }]
            })
            // bbar: new Ext.PagingToolbar({
            // pageSize: ENDPAGE,
            // store: strsetbonuspenjualan,
            // displayInfo: true
            // })
        });
	
    
        var setbonuspenjualan = new Ext.FormPanel({
            id: 'setbonuspenjualan',
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
                    items: [headersetbonuspenjualan]
                },{	
                xtype:'fieldset',
                autoheight: true,
                title: 'Bonus',
                collapsed: false,
                collapsible: true,
                anchor: '90%',
                items:[ {
                        xtype : 'compositefield',
                        msgTarget: 'side',
                        fieldLabel: 'Konsumen',
                        items : [{
                                xtype: 'displayfield',
                                value: 'Member:',
                                style: 'padding-left:295px;',
                                width: 250
                            }
                        ]
                    },{
                        xtype : 'compositefield',
                        msgTarget: 'side',
                        fieldLabel: 'Qty Beli',
                        items : [{
                                xtype: 'numericfield',
                                currencySymbol:'',
                                width: 250,
                                name : 'qty_beli_bonus',
                                allowBlank: false,
                                id: 'sbj_qty_beli_bonus',
                                style: 'text-align:right;',
                                value: 0
                            },{
                                xtype: 'displayfield',
                                value: 'Qty Beli',
                                style: 'padding-left:40px;',
                                width: 130
                            },{
                                xtype: 'numericfield',
                                currencySymbol:'',
                                name : 'qty_beli_member',
                                allowBlank: false,
                                width: 250,
                                id: 'sbj_qty_beli_member',
                                style: 'text-align:right;',
                                value: 0,
                            }
                        ]
                    },{
								
                        layout: 'column',
                        border: false,
                        width: 800,
                        items: [{
                                columnWidth: .5,
                                layout: 'form',
                                border: false,
                                labelWidth: 100,
                                defaultType: 'textfield',
                                defaults: { labelSeparator: ''},
                                items: [{
                                        xtype:'fieldset',
                                        autoheight: true,
                                        checkboxToggle:true,
                                        title: 'Bonus by Kategori',
                                        collapsed: true,
                                        anchor: '90%',
                                        items:[ sbj_cbkategori1_konsumen,sbj_cbkategori2_konsumen,
                                            sbj_cbkategori3_konsumen,sbj_cbkategori4_konsumen
                                        ]
                                    }]
                            },{
                                columnWidth: .5,
                                layout: 'form',
                                border: false,
                                labelWidth: 100,
                                defaultType: 'textfield',
                                defaulg_cbkategori3_bonusts: { labelSeparator: ''},
                                items: [{
                                        xtype:'fieldset',
                                        autoheight: true,
                                        checkboxToggle:true,
                                        collapsed: true,
                                        title: 'Bonus by Kategori',
                                        anchor: '90%',
                                        items: [sbj_cbkategori1_member,sbj_cbkategori2_member,
                                            sbj_cbkategori3_member,sbj_cbkategori4_member,
                                        ],
                                        listeners: {
                                            'onCheckClick': function(){
                                                alert('checked');
                                            }
                                        }
                                    }]
                            }]
                    },{
                        layout: 'column',
                        border: false,
                        width: 800,
                        items: [{
                                columnWidth: .5,
                                layout: 'form',
                                border: false,
                                labelWidth: 100,
                                defaultType: 'textfield',
                                defaults: { labelSeparator: ''},
                                items: [{
                                        xtype:'fieldset',
                                        autoheight: true,
                                        checkboxToggle:true,
                                        collapsed: false,
                                        title: 'Bonus by Produk',
                                        anchor: '90%',
                                        items:[ new Ext.ux.TwinComboHj({
                                                id: 'sbj_kd_produk_bonus',
                                                store: strcbkdproduksbj,
                                                fieldLabel: 'Kode Produk',
                                                valueField: 'kd_produk_bonus',
                                                displayField: 'kd_produk_bonus',
                                                typeAhead: true,	
                                                width: 250,
                                                anchor: '99%',
                                                editable: false,
                                                hiddenName: 'kd_produk_bonus',
                                                emptyText: 'Pilih Kode Produk',    
                                                listeners:{
                                                    'expand': function(){
                                                        strcbkdproduksbj.load();
                                                    }
                                                }
                                            }),{
                                                xtype: 'textfield',
                                                fieldLabel: 'Nama Produk',
                                                name: 'nama_produk_bonus',
                                                id: 'sbj_nama_produk_bonus',
                                                fieldClass: 'readonly-input',
                                                readOnly: true,
                                                value: '',
                                                width: 250,     
                                                anchor: '99%'        
                                            }
                                        ]
                                    }]
                            },{
                                columnWidth: .5,
                                layout: 'form',
                                border: false,
                                labelWidth: 100,
                                defaultType: 'textfield',
                                defaults: { labelSeparator: ''},
                                items: [{
                                        xtype:'fieldset',
                                        autoheight: true,
                                        checkboxToggle:true,
                                        collapsed: false,
                                        title: 'Bonus by Produk',
                                        anchor: '90%',
                                        items: [new Ext.ux.TwinComboHj({
                                                id: 'sbj_kd_produk_member',
                                                fieldLabel: 'Kode Produk',
                                                store: strcbkdproduksbj,
                                                valueField: 'kd_produk_member',
                                                displayField: 'kd_produk_member',
                                                typeAhead: true,	
                                                width: 250,
                                                anchor: '99%',
                                                editable: false,
                                                hiddenName: 'kd_produk_member',
                                                emptyText: 'Pilih Kode Produk',    
                                                listeners:{
                                                    'expand': function(){
                                                        strcbkdproduksbj.load();
                                                    }
                                                }
                                            }),{
                                                xtype: 'textfield',
                                                fieldLabel: 'Nama Produk',
                                                name: 'nama_produk_member',
                                                id: 'sbj_nama_produk_member',
                                                fieldClass: 'readonly-input',
                                                readOnly: true,
                                                value: '',
                                                width: 250,
                                                anchor: '99%'
                                            }
                                        ]
                                    }]
                            }]
                    },{
                        xtype : 'compositefield',
                        msgTarget: 'side',
                        fieldLabel: 'Qty Bonus',
                        items : [{
                                xtype: 'numberfield',
                                width: 250,
                                name : 'qty_bonus',
                                allowBlank: false,
                                id: 'sbj_qty_bonus',
                                style: 'text-align:right;',
                                value: 0
                            },{
                                xtype: 'displayfield',
                                value: 'Qty Bonus',
                                style: 'padding-left:40px;',
                                width: 130
                            },{
                                xtype: 'numberfield',
                                name : 'qty_member',
                                allowBlank: false,
                                width: 250,
                                id : 'sbj_qty_member',
                                style: 'text-align:right;',
                                value: 0
                            }
                        ]
                    },{
                        xtype : 'compositefield',
                        msgTarget: 'side',
                        fieldLabel: 'Kelipatan',
                        items : [new Ext.form.Checkbox({
                                xtype: 'checkbox',
                                boxLabel:'Ya',
                                name:'is_bonus_kelipatan',
                                id:'sbj_is_bonus_kelipatan',
                                inputValue: '1',
                                autoLoad : true,
                                width: 250
                            }),{
                                xtype: 'displayfield',
                                value: 'Kelipatan',
                                style: 'padding-left:40px;',
                                width: 130,
                            },new Ext.form.Checkbox({
                                xtype: 'checkbox',
                                boxLabel:'Ya',
                                name:'is_member_kelipatan',
                                id:'sbj_is_member_kelipatan',
                                inputValue: '1',
                                autoLoad : true,
                                width: 250
                            })
                        ]
                    }]
            },
                gridsetbonuspenjualan,   
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
				
                        var detailsetbonuspenjualan = new Array();              
                        strsetbonuspenjualan.each(function(node){
                            detailsetbonuspenjualan.push(node.data)
                        });
                        Ext.getCmp('setbonuspenjualan').getForm().submit({
                            url: '<?= site_url("harga_penjualan/update_row") ?>',
                            scope: this,
                            params: {
                                detail: Ext.util.JSON.encode(detailsetbonuspenjualan)
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
                        
                                clearsetbonuspenjualan();                       
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
                        clearsetbonuspenjualan();
                    }
                }]
        });
    
        setbonuspenjualan.on('afterrender', function(){
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
    
        function clearsetbonuspenjualan(){
            Ext.getCmp('setbonuspenjualan').getForm().reset();
            Ext.getCmp('setbonuspenjualan').getForm().load({
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
            strsetbonuspenjualan.removeAll();
        }
</script>
