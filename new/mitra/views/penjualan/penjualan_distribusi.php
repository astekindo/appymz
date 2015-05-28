<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">

    var strcbpdpelanggan = new Ext.data.ArrayStore({
        fields: ['kd_pelanggan'],
        data: []
    });

    var strgridpdpelanggan = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_pelanggan', 'nama_pelanggan', 'tipe','nama_tipe', 'alamat_kirim', 'no_telp', 'nama_sales', 'kd_sales'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("penjualan_distribusi/search_pelanggan") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var searchgridpdpelanggan = new Ext.app.SearchField({
        store: strgridpdpelanggan,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridpdpelanggan'
    });


    var gridpdpelanggan = new Ext.grid.GridPanel({
        store: strgridpdpelanggan,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'Kode Pelanggan',
                dataIndex: 'kd_pelanggan',
                width: 80,
                sortable: true
            }, {
                header: 'Nama Pelanggan',
                dataIndex: 'nama_pelanggan',
                width: 150,
                sortable: true
            },{
                header: 'Jenis Pelanggan',
                dataIndex: 'nama_tipe',
                width: 100,
                sortable: true
            }, {
                header: 'Kode tipe',
                dataIndex: 'tipe',
                width: 100,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridpdpelanggan]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridpdpelanggan,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('pd_nama_pelanggan').setValue(sel[0].get('nama_pelanggan'));
                    Ext.getCmp('id_cbpdpelanggan').setValue(sel[0].get('kd_pelanggan'));
                    Ext.getCmp('pd_tipe_pelanggan').setValue(sel[0].get('tipe'));
                    Ext.getCmp('pd_kirim_so').setValue(sel[0].get('nama_pelanggan'));
                    Ext.getCmp('pd_kirim_alamat_so').setValue(sel[0].get('alamat_kirim'));
                    Ext.getCmp('pd_kirim_telp_so').setValue(sel[0].get('no_telp'));
                    Ext.getCmp('pd_nama_sales').setValue(sel[0].get('nama_sales'));
                    Ext.getCmp('pd_kd_sales').setValue(sel[0].get('kd_sales'));
                    menupdpelanggan.hide();
                }
            }
        }
    });

    var menupdpelanggan = new Ext.menu.Menu();
    menupdpelanggan.add(new Ext.Panel({
        title: 'Pilih Pelanggan',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridpdpelanggan],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menupdpelanggan.hide();
                }
            }]
    }));

    Ext.ux.TwinCombopdpelanggan = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridpdpelanggan.load();
            menupdpelanggan.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menupdpelanggan.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridpdpelanggan').getValue();
        if (sf !== '') {
            Ext.getCmp('id_searchgridpdpelanggan').setValue('');
            searchgridpdpelanggan.onTrigger2Click();
        }
    });

    var cbpdpelanggan = new Ext.ux.TwinCombopdpelanggan({
        fieldLabel: 'Pelanggan <span class="asterix">*</span>',
        id: 'id_cbpdpelanggan',
        store: strcbpdpelanggan,
        mode: 'local',
        valueField: 'kd_pelanggan',
        displayField: 'kd_pelanggan',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_pelanggan',
        emptyText: 'Pilih Pelanggan'
    });
// Start Combo Sales
  /*  var strcbpdsales = new Ext.data.ArrayStore({
        fields: ['kd_supplier'],
        data: []
    });

    var strgridpdsales = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_sales', 'nama_sales'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("penjualan_distribusi/search_sales") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    strgridpdsales.on('load', function() {
        Ext.getCmp('id_searchgridpdsales').focus();
    });

    var searchgridpdsales = new Ext.app.SearchField({
        store: strgridpdsales,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridpdsales'
    });


    var gridpdsales = new Ext.grid.GridPanel({
        store: strgridpdsales,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'Kode Sales',
                dataIndex: 'kd_sales',
                width: 80,
                sortable: true
            }, {
                header: 'Nama Sales',
                dataIndex: 'nama_sales',
                width: 300,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridpdsales]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridpdsales,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cbpdsales').setValue(sel[0].get('kd_sales'));
                    Ext.getCmp('pd_nama_sales').setValue(sel[0].get('nama_sales'));

                    menupdsales.hide();
                }
                //strpembelianpelunasanhutang.removeAll();
                // strpphjenispembayaran.removeAll();
            }
        }
    });

    var menupdsales = new Ext.menu.Menu();
    menupdsales.add(new Ext.Panel({
        title: 'Pilih Sales',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridpdsales],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menupdsales.hide();
                }
            }]
    }));

    Ext.ux.TwinCombopdSales = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridpdsales.load();
            menupdsales.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menupdsales.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridpdsales').getValue();
        if (sf !== '') {
            Ext.getCmp('id_searchgridpdsales').setValue('');
            searchgridpdsales.onTrigger2Click();
        }
    });

    var cbpdsales = new Ext.ux.TwinCombopdSales({
        fieldLabel: 'Sales <span class="asterix">*</span>',
        id: 'id_cbpdsales',
        store: strcbpdsales,
        mode: 'local',
        valueField: 'kd_sales',
        displayField: 'kd_sales',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_sales',
        emptyText: 'Pilih Sales'
    });*/
// End Combo Sales    
    var headerpenjualandistribusi = {
        layout: 'column',
        border: false,
        items: [{
                columnWidth: .6,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [{
                        xtype: 'fieldset',
                        autoHeight: true,
                        items: [
                            {
                                layout: 'column',
                                items: [
                                    {
                                        columnWidth: .5,
                                        layout: 'form',
                                        border: false,
                                        labelWidth: 70,
                                        defaults: {labelSeparator: ''},
                                        items: [
                                            {
                                                xtype: 'textfield',
                                                fieldLabel: 'SO No.',
                                                readOnly: true,
                                                fieldClass: 'readonly-input',
                                                name: 'no_so',
                                                id: 'id_no_so',
                                                anchor: '90%'
                                            }, cbpdpelanggan,{
                                                xtype: 'textfield',
                                                fieldLabel: 'Nama Sales',
                                                name: 'nama_sales',
                                                readOnly: true,
                                                id: 'pd_nama_sales',
                                                anchor: '90%',
                                                fieldClass: 'readonly-input'
                                            },{
                                                xtype: 'hidden',
                                                name: 'kd_sales',
                                                readOnly: true,
                                                id: 'pd_kd_sales',
                                                anchor: '90%',
                                                fieldClass: 'readonly-input'
                                            } //cbpdsales
                                        ]
                                    },
                                    {
                                        columnWidth: .5,
                                        layout: 'form',
                                        border: false,
                                        labelWidth: 70,
                                        defaults: {labelSeparator: ''},
                                        items: [
                                            {
                                                xtype: 'datefield',
                                                fieldLabel: 'Tanggal',
                                                name: 'tgl_so',
                                                // readOnly: true,				
                                                allowBlank: false,
                                                editable: false,
                                                format: 'd-M-Y',
                                                id: 'pd_tgl_so',
                                                anchor: '90%',
                                                // fieldClass:'readonly-input',
                                                value: new Date()
                                            }, {
                                                xtype: 'textfield',
                                                fieldLabel: 'Nama',
                                                name: 'nama_pelanggan',
                                                readOnly: true,
                                                id: 'pd_nama_pelanggan',
                                                anchor: '90%',
                                                fieldClass: 'readonly-input'
                                            },{
                                                xtype: 'textfield',
                                                fieldLabel: 'No Referensi',
                                                name: 'no_ref',
                                                allowBlank: false,
                                                readOnly: false,
                                                id: 'pd_no_ref',
                                                anchor: '90%'
                                                
                                            }, {
                                                xtype: 'hidden',
                                                name: 'tipe',
                                                id: 'pd_tipe_pelanggan'


                                            }
                                        ]
                                    }
                                ]
                            }
                        ]
                    }]
            }
//            {
//                columnWidth: .4,
//                layout: 'form',
//                border: false,
//                align: 'right',
//                labelWidth: 50,
//                defaults: {labelSeparator: ''},
//                extraCls: 'text-align:right;border:1px solid;',
//                items: [{
//                        xtype: 'displayfield',
//                        name: 'display_grand_total',
//                        id: 'display_grand_total',
//                        fieldLabel: 'Rp.',
//                        labelStyle: 'font-size:35px;text-align:left;padding-left:50px;',
//                        style: 'font-size:35px;text-align:right;padding-right:10px;margin-top:10px;'
//                    }]
//            }
        ]
    };

    var strpenjualandistribusi = new Ext.data.Store({
        autoSave: false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'qty', type: 'int'},
                {name: 'hrg_jual', type: 'int'},
                {name: 'kd_produk', type: 'text'},
                {name: 'nama_produk', type: 'text'},
                {name: 'satuan', type: 'text'},
                {name: 'rp_jual_toko', type: 'int'},
                {name: 'rp_diskon', type: 'int'},
                {name: 'rp_diskon_tambahan', type: 'int'},
                {name: 'disk_persen_kons1_pd', type: 'int'},
                {name: 'rp_jumlah', type: 'int'},
                {name: 'rp_total', type: 'int'},
                {name: 'rp_diskon', type: 'int'},
                {name: 'qty_bonus', type: 'int'},
                {name: 'rp_het_cogs', type: 'int'},
                {name: 'net_hrg_supplier_dist_inc', type: 'int'},
                {name: 'rp_diskon_satuan', type: 'int'},
                {name: 'kd_produk_bonus', type: 'text'},
                {name: 'nama_produk_bonus', type: 'text'},
                {name: 'is_kirim', type: 'bool'}
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
    function set_total() {
        // SET TOTAL
        var grand_total = 0;
        var total_diskon = 0;
        var total_penjualan = 0;
        var bi_kirim = 0;

        var bi_kirim = parseInt(Ext.getCmp('pd_rp_ongkos_kirim').getValue());

        strpenjualandistribusi.each(function(node) {
            total_penjualan += parseInt(node.data.rp_total);
            total_diskon += parseInt(node.data.rp_diskon);
        });

        grand_total = total_penjualan + bi_kirim;
        Ext.getCmp('pd_rp_total').setValue(grand_total);
        Ext.getCmp('pd_rp_diskon_total').setValue(total_diskon);
        Ext.getCmp('pd_rp_total_tagihan').setValue(grand_total);
        //Ext.getCmp('display_grand_total').setValue(Ext.util.Format.number(grand_total, '0,0'));
    }
    ;

    function set_qty_bonus() {
        // SET QTY BONUS
        var qty = Ext.getCmp('pd_qty').getValue();
        var qty_beli = Ext.getCmp('pd_qty_beli_bonus').getValue();
        var kelipatan = Ext.getCmp('pd_is_bonus_kelipatan').getValue();
        var qty_bonus = 0;

        if (qty >= qty_beli) {
            qty_bonus = Ext.getCmp('pd_qty_bonus_awal').getValue();

            if (kelipatan !== 0) {
                qty_bonus = Math.floor(qty / qty_beli) * qty_bonus;
            }
        }
        //Ext.getCmp('pd_qty_bonus').setValue(qty_bonus);
    }
    ;

    strpenjualandistribusi.on('update', function() {

        var grand_total = 0;
        var total_diskon = 0;
        var total_penjualan = 0;
        var bi_kirim = 0;
        var het_cogs = 0;
        var qty_so = 0;
        var harga_total = 0;
        //var uang_muka = parseFloat(Ext.getCmp('pd_uang_muka').getValue());
        
        strpenjualandistribusi.each(function(node) {
            total_penjualan += parseFloat(node.data.rp_jumlah);
            total_diskon += node.data.rp_diskon;
            het_cogs = node.data.rp_het_cogs;
            qty_so = node.data.qty;
            harga_total = node.data.rp_total;
        });
        total_penjualan = Math.round(total_penjualan);
       // grand_total = total_penjualan - uang_muka;
        //grand_total = Math.round(grand_total);
        Ext.getCmp('pd_rp_total').setValue(total_penjualan);
        //Ext.getCmp('pd_total').setValue(grand_total);
        var dpp = total_penjualan / 1.1;
        dpp = Math.round(dpp);
        Ext.getCmp('pd_dpp').setValue(dpp);
        var ppn = dpp * 0.1;
        ppn = Math.round(ppn);
        //var total_tagihan = grand_total + ppn;
        Ext.getCmp('pd_pcin_rp_ppn').setValue(ppn);
        //Ext.getCmp('pd_rp_diskon_total').setValue(total_diskon);
        //Ext.getCmp('pd_rp_total_tagihan').setValue(total_tagihan);
        //Ext.getCmp('display_grand_total').setValue(Ext.util.Format.number(grand_total, '0,0'));


        var qty = Ext.getCmp('pd_qty').getValue();
        var qty_beli = Ext.getCmp('pd_qty_beli_bonus').getValue();
        var kelipatan = Ext.getCmp('pd_is_bonus_kelipatan').getValue();
        var qty_bonus = 0;

        if (qty >= qty_beli) {
            qty_bonus = Ext.getCmp('pd_qty_bonus_awal').getValue();

            if (kelipatan !== 0) {
                qty_bonus = Math.floor(qty / qty_beli) * qty_bonus;
            }
        }
        var jml_cogs = het_cogs * qty_so;
        if (harga_total < jml_cogs){
                                    Ext.Msg.show({
                                        title: 'Error',
                                        msg: 'Total tidak boleh lebih kecil dari het cogs x Qty',
                                        modal: true,
                                        icon: Ext.Msg.ERROR,
                                        buttons: Ext.Msg.OK,
                                        fn: function(btn){
                                            if (btn == 'ok') {
                                                Ext.getCmp('pd_rp_extra_bonus').reset();
                                               }
                                        }                            
                                    });
                                }
       
    });

    strpenjualandistribusi.on('remove', function() {

        var grand_total = 0;
        var total_diskon = 0;
        var total_penjualan = 0;
        var bi_kirim = 0;

        //var uang_muka = parseFloat(Ext.getCmp('pd_uang_muka').getValue());

        strpenjualandistribusi.each(function(node) {
            total_penjualan += parseFloat(node.data.rp_jumlah);
            total_diskon += node.data.rp_diskon;
        });
        total_penjualan = Math.round(total_penjualan);
        //grand_total = total_penjualan - uang_muka;
        //grand_total = Math.round(grand_total);
        Ext.getCmp('pd_rp_total').setValue(total_penjualan);
        //Ext.getCmp('pd_total').setValue(grand_total);
        var dpp = total_penjualan / 1.1;
        dpp = Math.round(dpp);
        Ext.getCmp('pd_dpp').setValue(dpp);
        var ppn = dpp * 0.1;
        ppn = Math.round(ppn);
        //var total_tagihan = grand_total + ppn;
        Ext.getCmp('pd_pcin_rp_ppn').setValue(ppn);
        // Ext.getCmp('display_grand_total').setValue(Ext.util.Format.number(grand_total, '0,0'));


        var qty = Ext.getCmp('pd_qty').getValue();
        var qty_beli = Ext.getCmp('pd_qty_beli_bonus').getValue();
        var kelipatan = Ext.getCmp('pd_is_bonus_kelipatan').getValue();
        var qty_bonus = 0;

        if (qty >= qty_beli) {
            qty_bonus = Ext.getCmp('pd_qty_bonus_awal').getValue();

            if (kelipatan !== 0) {
                qty_bonus = Math.floor(qty / qty_beli) * qty_bonus;
            }
        }
        //Ext.getCmp('pd_qty_bonus').setValue(qty_bonus);
    });

    var editorpenjualandistribusi = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });

    var strcbpdproduk = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data: []
    });

    var strgridpdproduk = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_produk', allowBlank: false, type: 'text'},
                {name: 'nama_produk', allowBlank: false, type: 'text'}
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("penjualan_distribusi/search_produk") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    strgridpdproduk.on('load', function() {
        Ext.getCmp('search_query_pdproduk').focus();
    });

    var searchfieldpdproduk = new Ext.app.SearchField({
        width: 220,
        id: 'search_query_pdproduk',
        store: strgridpdproduk
    });



    // top toolbar
    var tbsearchfieldpdproduk = new Ext.Toolbar({
        items: [searchfieldpdproduk]
    });

    var gridpdproduk = new Ext.grid.GridPanel({
        store: strgridpdproduk,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'Kode produk',
                dataIndex: 'kd_produk',
                width: 100,
                sortable: true
            }, {
                header: 'Nama produk',
                dataIndex: 'nama_produk',
                width: 350,
                sortable: true
            }],
        tbar: tbsearchfieldpdproduk,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridpdproduk,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    var _ada = false;
                                
                    strpenjualandistribusi.each(function(record){
                        if(record.get('kd_produk') === sel[0].get('kd_produk')){
                            _ada = true;
                        }
                    });

                    if (_ada){
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Produk yang sama tidak bisa di pilih lebih dari 1 kali',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK,
                            fn: function(btn){
                                if (btn == 'ok') {
                                    Ext.getCmp('pd_kd_produk').reset();
                                }
                            }                            
                        });
                        Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                        Ext.getCmp('pd_kd_produk').focus();	
                        return;
                    }
                    Ext.Ajax.request({
                        url: '<?= site_url("penjualan_distribusi/get_row_produk") ?>',
                        method: 'POST',
                        params: {
                            id: sel[0].get('kd_produk'),
                            qty: Ext.getCmp('pd_qty').getValue(),
                            search_by: 'kode'
                        },
                        callback: function(opt, success, responseObj) {
                            var de = Ext.util.JSON.decode(responseObj.responseText);
                            if (de.success === true) {

                                Ext.getCmp('pd_kd_produk').setValue(de.data.kd_produk);
                                Ext.getCmp('pd_nama_produk').setValue(de.data.nama_produk);
                                Ext.getCmp('pd_satuan').setValue(de.data.nm_satuan);

                                if (Ext.getCmp('pd_tipe_pelanggan').getValue() === '1') {
                                    Ext.getCmp('pd_hrg_jual').setValue(de.data.rp_jual_toko);
                                    Ext.getCmp('pd_disk_persen_kons1').setValue(de.data.disk_toko1);
                                    Ext.getCmp('pd_disk_persen_kons2').setValue(de.data.disk_toko2);
                                    Ext.getCmp('pd_disk_persen_kons3').setValue(de.data.disk_toko3);
                                    Ext.getCmp('pd_disk_persen_kons4').setValue(de.data.disk_toko4);
                                    Ext.getCmp('pd_disk_persen_kons5').setValue(de.data.disk_toko5);
                                    Ext.getCmp('pd_rp_harga_nett').setValue(de.data.rp_harga_nett_toko);
                                    Ext.getCmp('pd_rp_diskon').setValue(de.data.rp_diskon_toko);
                                    Ext.getCmp('pd_so_jumlah').setValue(de.data.rp_jumlah_toko);
                                    Ext.getCmp('pd_rp_diskon_hidden').setValue(de.data.rp_diskon_agen);
                                    Ext.getCmp('pd_rp_diskon_satuan').setValue(0);
                                    Ext.getCmp('pd_rp_total_diskon').setValue(de.data.rp_diskon_toko);
                                    Ext.getCmp('pd_rp_het_cogs').setValue(de.data.rp_het_cogs);
                                    Ext.getCmp('pd_net_hrg_supplier_dist_inc').setValue(de.data.net_hrg_supplier_dist_inc);
                                }
                                else if (Ext.getCmp('pd_tipe_pelanggan').getValue() === '0') {
                                    Ext.getCmp('pd_hrg_jual').setValue(de.data.rp_jual_agen);
                                    Ext.getCmp('pd_disk_persen_kons1').setValue(de.data.disk_agen1);
                                    Ext.getCmp('pd_disk_persen_kons2').setValue(de.data.disk_agen2);
                                    Ext.getCmp('pd_disk_persen_kons3').setValue(de.data.disk_agen3);
                                    Ext.getCmp('pd_disk_persen_kons4').setValue(de.data.disk_agen4);
                                    Ext.getCmp('pd_disk_persen_kons5').setValue(de.data.disk_agen5);
                                    Ext.getCmp('pd_rp_harga_nett').setValue(de.data.rp_harga_nett_agen);
                                    Ext.getCmp('pd_rp_diskon').setValue(de.data.rp_diskon_agen);
                                    Ext.getCmp('pd_so_jumlah').setValue(de.data.rp_jumlah_agen);
                                    Ext.getCmp('pd_rp_diskon_hidden').setValue(de.data.rp_diskon_agen);
                                    Ext.getCmp('pd_rp_diskon_satuan').setValue(0);
                                    Ext.getCmp('pd_rp_total_diskon').setValue(de.data.rp_diskon_agen);
                                    Ext.getCmp('pd_rp_het_cogs').setValue(de.data.rp_het_cogs);
                                    Ext.getCmp('pd_net_hrg_supplier_dist_inc').setValue(de.data.net_hrg_supplier_dist_inc);
                                } 
                                else if (Ext.getCmp('pd_tipe_pelanggan').getValue() === '2') {
                                    Ext.getCmp('pd_hrg_jual').setValue(de.data.rp_jual_modern_market);
                                    Ext.getCmp('pd_disk_persen_kons1').setValue(de.data.disk_modern_market1);
                                    Ext.getCmp('pd_disk_persen_kons2').setValue(de.data.disk_modern_market2);
                                    Ext.getCmp('pd_disk_persen_kons3').setValue(de.data.disk_modern_market3);
                                    Ext.getCmp('pd_disk_persen_kons4').setValue(de.data.disk_modern_market4);
                                    Ext.getCmp('pd_disk_persen_kons5').setValue(de.data.disk_modern_market5);
                                    Ext.getCmp('pd_rp_harga_nett').setValue(de.data.rp_harga_nett_modern_market);
                                    Ext.getCmp('pd_rp_diskon').setValue(de.data.rp_diskon_modern_market);
                                    Ext.getCmp('pd_so_jumlah').setValue(de.data.rp_jumlah_modern_market);
                                    Ext.getCmp('pd_rp_diskon_hidden').setValue(de.data.rp_diskon_modern_market);
                                    Ext.getCmp('pd_rp_diskon_satuan').setValue(0);
                                    Ext.getCmp('pd_rp_total_diskon').setValue(de.data.rp_diskon_modern_market);
                                    Ext.getCmp('pd_rp_het_cogs').setValue(de.data.rp_het_cogs);
                                    Ext.getCmp('pd_net_hrg_supplier_dist_inc').setValue(de.data.net_hrg_supplier_dist_inc);
                                }

                                if (Ext.getCmp('pd_tipe_pelanggan').getValue() === '1') {
                                    Ext.getCmp('pd_disk_persen_kons1_op').setValue(de.data.disk_toko1_op);
                                    Ext.getCmp('pd_disk_persen_kons2_op').setValue(de.data.disk_toko2_op);
                                    Ext.getCmp('pd_disk_persen_kons3_op').setValue(de.data.disk_toko3_op);
                                    Ext.getCmp('pd_disk_persen_kons4_op').setValue(de.data.disk_toko4_op);
                                    Ext.getCmp('pd_disk_persen_kons5_op').setValue(de.data.disk_toko5_op);

//                                Ext.getCmp('pd_disk_amt_kons1_pd').setValue(de.data.disk_toko1_op);
//                                Ext.getCmp('pd_disk_amt_kons2_pd').setValue(de.data.disk_toko2_op);
//                                Ext.getCmp('pd_disk_amt_kons3_pd').setValue(de.data.disk_toko3_op);
//                                Ext.getCmp('pd_disk_amt_kons4_pd').setValue(de.data.disk_toko4);
//                                Ext.getCmp('pd_disk_amt_kons5_pd').setValue(de.data.disk_toko5);

                                }
                                else if (Ext.getCmp('pd_tipe_pelanggan').getValue() === '0') {
                                    Ext.getCmp('pd_disk_persen_kons1_op').setValue(de.data.disk_agen1_op);
                                    Ext.getCmp('pd_disk_persen_kons2_op').setValue(de.data.disk_agen2_op);
                                    Ext.getCmp('pd_disk_persen_kons3_op').setValue(de.data.disk_agen3_op);
                                    Ext.getCmp('pd_disk_persen_kons4_op').setValue(de.data.disk_agen4_op);
                                    Ext.getCmp('pd_disk_persen_kons5_op').setValue(de.data.disk_agen5_op);

//                                Ext.getCmp('pd_disk_amt_kons1_pd').setValue(de.data.disk_agen1);
//                                Ext.getCmp('pd_disk_amt_kons2_pd').setValue(de.data.disk_agen2);
//                                Ext.getCmp('pd_disk_amt_kons3_pd').setValue(de.data.disk_agen3);
//                                Ext.getCmp('pd_disk_amt_kons4_pd').setValue(de.data.disk_agen4);
//                                Ext.getCmp('pd_disk_amt_kons5_pd').setValue(de.data.disk_agen5);
                                }
                                else if (Ext.getCmp('pd_tipe_pelanggan').getValue() === '2') {
                                    Ext.getCmp('pd_disk_persen_kons1_op').setValue(de.data.disk_modern_market1_op);
                                    Ext.getCmp('pd_disk_persen_kons2_op').setValue(de.data.disk_modern_market2_op);
                                    Ext.getCmp('pd_disk_persen_kons3_op').setValue(de.data.disk_modern_market3_op);
                                    Ext.getCmp('pd_disk_persen_kons4_op').setValue(de.data.disk_modern_market4_op);
                                    Ext.getCmp('pd_disk_persen_kons5_op').setValue(de.data.disk_modern_market5_op);
                                }
                                // Ext.getCmp('pd_kd_produk_bonus').setValue(de.data.kd_produk_bonus);
                                // Ext.getCmp('pd_nama_produk_bonus').setValue(de.data.nama_produk_bonus);															
                            } else {
                                Ext.Msg.show({
                                    title: 'Error',
                                    msg: de.errMsg,
                                    modal: true,
                                    icon: Ext.Msg.ERROR,
                                    buttons: Ext.Msg.OK,
                                    fn: function(btn) {
                                        if (btn === 'ok' && de.errMsg === 'Session Expired') {
                                            window.location = '<?= site_url("auth/login") ?>';
                                        }
                                    }
                                });
                            }
                        }
                    });
                    Ext.getCmp('pd_qty').focus();
                    menupdproduk.hide();
                }
            }
        }
    });

    var menupdproduk = new Ext.menu.Menu();
    menupdproduk.add(new Ext.Panel({
        title: 'Pilih Barang',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 630,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridpdproduk],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menupdproduk.hide();
                }
            }]
    }));

    menupdproduk.on('hide', function() {
        var sf = Ext.getCmp('search_query_pdproduk').getValue();
        if (sf !== '') {
            Ext.getCmp('search_query_pdproduk').setValue('');
            searchfieldpdproduk.onTrigger2Click();
        }
    });


    Ext.ux.TwinCombopdproduk = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridpdproduk.load();
            menupdproduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    var strcbpdbonus = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data: []
    });

    var strgridpdbonus = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'qty_bonus', allowBlank: false, type: 'int'},
                {name: 'kd_produk', allowBlank: false, type: 'text'},
                {name: 'nama_produk', allowBlank: false, type: 'text'}
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("penjualan_distribusi/search_bonus") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    strgridpdbonus.on('load', function() {
        Ext.getCmp('search_query_pdbonus').focus();
    });

    var searchfieldpdbonus = new Ext.app.SearchField({
        width: 220,
        id: 'search_query_pdbonus',
        store: strgridpdbonus
    });



    // top toolbar
    var tbsearchfieldpdbonus = new Ext.Toolbar({
        items: [searchfieldpdbonus]
    });

    var gridpdbonus = new Ext.grid.GridPanel({
        store: strgridpdbonus,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                dataIndex: 'qty_bonus',
                hidden: true
            }, {
                header: 'Kode Produk Bonus',
                dataIndex: 'kd_produk',
                width: 130,
                sortable: true
            }, {
                header: 'Nama Produk Bonus',
                dataIndex: 'nama_produk',
                width: 350,
                sortable: true
            }],
        tbar: tbsearchfieldpdbonus,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridpdbonus,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    set_qty_bonus();
                    Ext.getCmp('pd_kd_produk_bonus').setValue(sel[0].get('kd_produk'));
                    Ext.getCmp('pd_nama_produk_bonus').setValue(sel[0].get('nama_produk'));

                    menupdbonus.hide();
                }
            }
        }
    });

    var menupdbonus = new Ext.menu.Menu();
    menupdbonus.add(new Ext.Panel({
        title: 'Pilih Barang',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 630,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridpdbonus],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menupdbonus.hide();
                }
            }]
    }));

    menupdbonus.on('hide', function() {
        var sf = Ext.getCmp('search_query_pdbonus').getValue();
        if (sf !== '') {
            Ext.getCmp('search_query_pdbonus').setValue('');
            searchfieldpdbonus.onTrigger2Click();
        }
    });


    Ext.ux.TwinCombopdbonus = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridpdbonus.load({
                params: {
                    kd_produk: Ext.getCmp('pd_kd_produk').getValue()
                }
            });
            menupdbonus.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    function HitungHargaJualDist(){
        
        var total_disk = 0;
        var rp_jual_toko = Ext.getCmp('pd_hrg_jual').getValue();
        var disk_toko1_op = Ext.getCmp('pd_disk_persen_kons1_op').getValue();
        var disk_toko1 = Ext.getCmp('pd_disk_persen_kons1').getValue();
        if (disk_toko1_op === '%'){
            // disk_toko1 = (disk_toko1*rp_jual_toko)/100;
            total_disk = rp_jual_toko-(rp_jual_toko*(disk_toko1/100));
        }else{
            total_disk = rp_jual_toko-disk_toko1;
        }

        var disk_toko2_op = Ext.getCmp('pd_disk_persen_kons2_op').getValue();
        var disk_toko2 = Ext.getCmp('pd_disk_persen_kons2').getValue();
        if (disk_toko2_op === '%'){
            // disk_toko2 = (disk_toko2*disk_toko1)/100;
            total_disk =  total_disk-(total_disk*(disk_toko2/100));
        }else{
            total_disk = total_disk-disk_toko2;
        }

        var disk_toko3_op = Ext.getCmp('pd_disk_persen_kons3_op').getValue();
        var disk_toko3 = Ext.getCmp('pd_disk_persen_kons3').getValue();
        if (disk_toko3_op === '%'){
            // disk_toko3 = (disk_toko3*disk_toko2)/100;
            total_disk = total_disk-(total_disk*(disk_toko3/100));
        }else{
            total_disk = total_disk-disk_toko3;
        }

        var disk_toko4_op = Ext.getCmp('pd_disk_persen_kons4_op').getValue();
        var disk_toko4 = Ext.getCmp('pd_disk_persen_kons4').getValue();
        if (disk_toko4_op === '%'){
            // disk_toko4 = (disk_toko4*disk_toko3)/100;
            total_disk = total_disk-(total_disk*(disk_toko4/100));
        }else{
            total_disk = total_disk-disk_toko4;
        }

        var total_disk = total_disk-Ext.getCmp('pd_disk_persen_kons5').getValue();
        var diskon = rp_jual_toko - total_disk;
        var net_jual_kons = total_disk;
        Ext.getCmp('pd_rp_diskon').setValue(diskon);
        var ekstra_diskon = Ext.getCmp('pd_rp_diskon_satuan').getValue();
        var total_diskon = diskon + ekstra_diskon;
        Ext.getCmp('pd_rp_total_diskon').setValue(total_diskon);
        Ext.getCmp('pd_rp_harga_nett').setValue(net_jual_kons);
        var jumlah = Ext.getCmp('pd_qty').getValue() * net_jual_kons;
        Ext.getCmp('pd_so_jumlah').setValue(jumlah);
       }
    
    var gridpenjualandistribusi = new Ext.grid.GridPanel({
        store: strpenjualandistribusi,
        stripeRows: true,
        height: 200,
        frame: true,
        border: true,
        plugins: [editorpenjualandistribusi],
        tbar: [{
                icon: BASE_ICONS + 'add.png',
                text: 'Add',
                handler: function() {
                    if (Ext.getCmp('id_cbpdpelanggan').getValue() === '') {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Pelanggan dan Sales Harus Di Pilih Dahulu !',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                        });
                        return;
                    }

                    var rowpenjualandistribusi = new gridpenjualandistribusi.store.recordType({
                        kd_produk: '',
                        qty: '0'
                    });
                    editorpenjualandistribusi.stopEditing();
                    strpenjualandistribusi.insert(0, rowpenjualandistribusi);
                    gridpenjualandistribusi.getView().refresh();
                    gridpenjualandistribusi.getSelectionModel().selectRow(0);
                    editorpenjualandistribusi.startEditing(0);
                }
            }, {
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                disabled: true,
                handler: function() {
                    editorpenjualandistribusi.stopEditing();
                    var s = gridpenjualandistribusi.getSelectionModel().getSelections();
                    for (var i = 0, r; r = s[i]; i++) {
                        strpenjualandistribusi.remove(r);
                    }
                }
            }],
        columns: [{
                header: 'Kode',
                dataIndex: 'kd_produk',
                width: 120,
                sortable: true,
                //format: '0',
                editor: new Ext.ux.TwinCombopdproduk({
                    id: 'pd_kd_produk',
                    store: strcbpdproduk,
                    mode: 'local',
                    valueField: 'kd_produk',
                    displayField: 'kd_produk',
                    typeAhead: true,
                    triggerAction: 'all',
                    //allowBlank: false,
                    editable: false,
                    hiddenName: 'kd_produk',
                    emptyText: 'Pilih Produk'

                })
            }, {
                header: 'Nama Barang',
                dataIndex: 'nama_produk',
                width: 250,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'pd_nama_produk'
                })
            }, {
                xtype: 'numbercolumn',
                header: 'Qty',
                dataIndex: 'qty',
                width: 40,
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'pd_qty',
                    //allowBlank: false,
                    listeners: {
                        'change': function() {
                            var rp_total = this.getValue() * Ext.getCmp('pd_rp_harga_nett').getValue();
                            Ext.getCmp('pd_so_jumlah').setValue(rp_total);
                            //Ext.getCmp('pd_so_total').setValue(rp_total);
//                            var exc = rp_total / 1.1;
//                            Ext.getCmp('pd_so_total_exc').setValue(exc);

                        }
                    }
                }
            }, {
                header: 'Satuan',
                dataIndex: 'satuan',
                width: 80,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'pd_satuan'
                })
            },{
                xtype: 'numbercolumn',
                header: 'HET Net Price Beli (Inc.PPN)',
                dataIndex: 'net_hrg_supplier_dist_inc',
                width: 140,
                align: 'right',
                format: '0,0',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'pd_net_hrg_supplier_dist_inc'
                })
            },{
                xtype: 'numbercolumn',
                header: 'HET COGS (Inc.PPN)',
                dataIndex: 'rp_het_cogs',
                width: 110,
                align: 'right',
                format: '0,0',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'pd_rp_het_cogs'
                })
            }, {
                xtype: 'numbercolumn',
                header: 'Hrg Jual',
                dataIndex: 'hrg_jual',
                align: 'right',
                width: 90,
                format: '0,0',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'pd_hrg_jual'
                })
            }, {
                hidden: true,
                width: 100,
                align: 'right',
                sortable: true,
                dataIndex: 'disk_persen_kons1_pd',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'pd_disk_persen_kons1_pd'
                })
            }, {
                hidden: true,
                dataIndex: 'disk_persen_kons2_pd',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'pd_disk_persen_kons2_pd'
                })
            }, {
                hidden: true,
                dataIndex: 'disk_persen_kons3_pd',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'pd_disk_persen_kons3_pd'
                })
            }, {
                hidden: true,
                dataIndex: 'disk_persen_kons4_pd',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'pd_disk_persen_kons4_pd'
                })
            }, {
                hidden: true,
                dataIndex: 'disk_amt_kons1_pd',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'pd_disk_amt_kons1_pd'
                })
            }, {
                hidden: true,
                dataIndex: 'disk_amt_kons2_pd',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'pd_disk_amt_kons2_pd'
                })
            }, {
                hidden: true,
                dataIndex: 'disk_amt_kons3_pd',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'pd_disk_amt_kons3_pd'
                })
            }, {
                hidden: true,
                dataIndex: 'pd_disk_amt_kons4_pd',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'pd_disk_amt_kons4_pd'
                })
            }, {
                hidden: true,
                dataIndex: 'pd_disk_amt_kons5_pd',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'pd_disk_amt_kons5_pd'
                })
            }, {
                header: 'Rp / %',
                dataIndex: 'disk_persen1_op',
                width: 50,
                sortable: true,
                editor: {
                    xtype:          'combo',
                        store:          new Ext.data.JsonStore({
                            fields : ['name'],
                            data   : [
                                {name : '%'},
                                {name : 'Rp'}
                            ]
                        }),
                        id:           	'pd_disk_persen_kons1_op',
                        mode:           'local',
                        name:           'disk_persen1_op',
                        value:          '%',
                        width:			50,
                        editable:       false,
                        hiddenName:     'disk_persen1_op',
                        valueField:     'name',
                        displayField:   'name',
                        triggerAction:  'all',
                        forceSelection: true,
                        listeners:{
                            'expand':function(){
                                Ext.getCmp('pd_disk_persen_kons1').setValue(0);
                             },
                            select:function(){
                                HETChangeDist();
                                Ext.getCmp('pd_disk_persen_kons1').setMaxValue(Number.MAX_VALUE);
                                if (this.getValue() === 'persen')
                                    Ext.getCmp('pd_disk_persen_kons1').maxValue = 100;
                                else
                                    Ext.getCmp('pd_disk_persen_kons1').maxLength = 11;
                            }
                        }
                }
            }, {
                header: 'Diskon 1',
                dataIndex: 'disk_persen_kons1',
                width: 100,
                align: 'right',
                sortable: true,
                editor: {
                     xtype: 'numberfield',
                     msgTarget: 'under',
                     flex:1,
                     width:115,
                     name : 'disk_persen_kons1',
                     id: 'pd_disk_persen_kons1',
                     style: 'text-align:right;',
                     listeners:{
                         'render': function(c) {
                             c.getEl().on('keyup', function() {
                                 HitungHargaJualDist();
                             }, c);
                         }

                     }
                    }
               }, {
                header: 'Rp / %',
                dataIndex: 'disk_persen2_op',
                width: 50,
                sortable: true,
                editor: {
                    xtype:          'combo',
                        store:          new Ext.data.JsonStore({
                            fields : ['name'],
                            data   : [
                                {name : '%'},
                                {name : 'Rp'}
                            ]
                        }),
                        id:           	'pd_disk_persen_kons2_op',
                        mode:           'local',
                        name:           'disk_persen2_op',
                        value:          '%',
                        width:			50,
                        editable:       false,
                        hiddenName:     'disk_persen2_op',
                        valueField:     'name',
                        displayField:   'name',
                        triggerAction:  'all',
                        forceSelection: true,
                        listeners:{
                            'expand':function(){
                                Ext.getCmp('pd_disk_persen_kons2').setValue(0);
                             },
                            select:function(){
                                HETChangeDist();
                                Ext.getCmp('pd_disk_persen_kons2').setMaxValue(Number.MAX_VALUE);
                                if (this.getValue() === 'persen')
                                    Ext.getCmp('pd_disk_persen_kons2').maxValue = 100;
                                else
                                    Ext.getCmp('pd_disk_persen_kons2').maxLength = 11;
                            }
                        }
             
                }
            }, {
                header: 'Diskon 2',
                dataIndex: 'disk_persen_kons2',
                width: 100,
                sortable: true,
                align: 'right',
                editor: {
                    xtype: 'numberfield',
                     msgTarget: 'under',
                     flex:1,
                     width:115,
                     name : 'disk_persen_kons2',
                     id: 'pd_disk_persen_kons2',
                     style: 'text-align:right;',
                     listeners:{
                         'render': function(c) {
                             c.getEl().on('keyup', function() {
                                 HitungHargaJualDist();
                             }, c);
                         }

                     }
                }
            }, {
                header: 'Rp / %',
                dataIndex: 'disk_persen3_op',
                width: 50,
                sortable: true,
                editor: {
                    xtype:          'combo',
                        store:          new Ext.data.JsonStore({
                            fields : ['name'],
                            data   : [
                                {name : '%'},
                                {name : 'Rp'}
                            ]
                        }),
                        id:           	'pd_disk_persen_kons3_op',
                        mode:           'local',
                        name:           'disk_persen3_op',
                        value:          '%',
                        width:			50,
                        editable:       false,
                        hiddenName:     'disk_persen3_op',
                        valueField:     'name',
                        displayField:   'name',
                        triggerAction:  'all',
                        forceSelection: true,
                        listeners:{
                            'expand':function(){
                                Ext.getCmp('pd_disk_persen_kons3').setValue(0);
                            },
                            select:function(){
                                HETChangeDist();
                                Ext.getCmp('pd_disk_persen_kons3').setMaxValue(Number.MAX_VALUE);
                                if (this.getValue() === 'persen')
                                    Ext.getCmp('pd_disk_persen_kons3').maxValue = 100;
                                else
                                    Ext.getCmp('pd_disk_persen_kons3').maxLength = 11;
                            }
                        }
                }
            }, {
                header: 'Diskon 3 ',
                dataIndex: 'disk_persen_kons3',
                width: 100,
                sortable: true,
                align: 'right',
                editor: {
                    xtype: 'numberfield',
                     msgTarget: 'under',
                     flex:1,
                     width:115,
                     name : 'disk_persen_kons3',
                     id: 'pd_disk_persen_kons3',
                     style: 'text-align:right;',
                     listeners:{
                         'render': function(c) {
                             c.getEl().on('keyup', function() {
                                 HitungHargaJualDist();
                             }, c);
                         }

                     }
                }
            }, {
                header: 'Rp / %',
                dataIndex: 'disk_persen4_op',
                width: 50,
                sortable: true,
                editor: {
                    xtype:          'combo',
                        store:          new Ext.data.JsonStore({
                            fields : ['name'],
                            data   : [
                                {name : '%'},
                                {name : 'Rp'}
                            ]
                        }),
                        id:           	'pd_disk_persen_kons4_op',
                        mode:           'local',
                        name:           'disk_persen4_op',
                        value:          '%',
                        width:			50,
                        editable:       false,
                        hiddenName:     'disk_persen4_op',
                        valueField:     'name',
                        displayField:   'name',
                        triggerAction:  'all',
                        forceSelection: true,
                        listeners:{
                            'expand':function(){
                                Ext.getCmp('pd_disk_persen_kons4').setValue(0);
                             },
                            select:function(){
                                HETChangeDist();
                                Ext.getCmp('pd_disk_persen_kons4').setMaxValue(Number.MAX_VALUE);
                                if (this.getValue() === 'persen')
                                    Ext.getCmp('pd_disk_persen_kons4').maxValue = 100;
                                else
                                    Ext.getCmp('pd_disk_persen_kons4').maxLength = 11;
                            }
                        }
       
                }
            }, {
                header: 'Diskon 4',
                dataIndex: 'disk_persen_kons4',
                width: 100,
                sortable: true,
                align: 'right',
                editor: {
                    xtype: 'numberfield',
                     msgTarget: 'under',
                     flex:1,
                     width:115,
                     name : 'disk_persen_kons4',
                     id: 'pd_disk_persen_kons4',
                     style: 'text-align:right;',
                     listeners:{
                         'render': function(c) {
                             c.getEl().on('keyup', function() {
                                 HitungHargaJualDist();
                             }, c);
                         }

                     }
                }
            }, {
                header: 'Rp / %',
                dataIndex: 'disk_persen5_op',
                width: 50,
                sortable: true,
                editor: {
                    xtype:          'combo',
                        store:          new Ext.data.JsonStore({
                            fields : ['name'],
                            data   : [
                                {name : '%'},
                                {name : 'Rp'}
                            ]
                        }),
                        id:           	'pd_disk_persen_kons5_op',
                        mode:           'local',
                        name:           'disk_persen5_op',
                        value:          '%',
                        width:			50,
                        editable:       false,
                        hiddenName:     'disk_persen5_op',
                        valueField:     'name',
                        displayField:   'name',
                        triggerAction:  'all',
                        forceSelection: true,
                        listeners:{
                            'expand':function(){
                                Ext.getCmp('pd_disk_persen_kons5').setValue(0);
                               
                            },
                            select:function(){
                                HETChangeDist();
                                Ext.getCmp('pd_disk_persen_kons5').setMaxValue(Number.MAX_VALUE);
                                if (this.getValue() === 'persen')
                                    Ext.getCmp('pd_disk_persen_kons5').maxValue = 100;
                                else
                                    Ext.getCmp('pd_disk_persen_kons5').maxLength = 11;
                            }
                        }
                }
            }, {
                header: 'Diskon 5',
                dataIndex: 'disk_persen_kons5',
                width: 100,
                sortable: true,
                align: 'right',
                editor: {
                   xtype: 'numberfield',
                     msgTarget: 'under',
                     flex:1,
                     width:115,
                     name : 'disk_persen_kons5',
                     id: 'pd_disk_persen_kons5',
                     style: 'text-align:right;',
                     listeners:{
                         'render': function(c) {
                             c.getEl().on('keyup', function() {
                                 HitungHargaJualDist();
                             }, c);
                         }

                     }
                }
            }, {xtype: 'numbercolumn',
                header: 'Diskon ',
                dataIndex: 'rp_diskon',
                width: 100,
                sortable: true,
                align: 'right',
                format: '0,0',
                editor: {
                   xtype: 'numberfield',
                     msgTarget: 'under',
                     flex:1,
                     width:115,
                     name : 'diskon',
                     id: 'pd_rp_diskon',
                     style: 'text-align:right;',
                     fieldClass: 'readonly-input',
                    readOnly: true
                   }
            },{
                xtype: 'numbercolumn',
                header: 'Extra Diskon satuan',
                dataIndex: 'rp_diskon_satuan',
                align: 'right',
                width: 120,
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'pd_rp_diskon_satuan',
                    //allowBlank: false,
                    listeners: {
                         'change': function(){
                            var diskon = this.getValue();
                            
                            var rp_diskon = parseFloat(Ext.getCmp('pd_rp_diskon').getValue());
                            var total_diskon = rp_diskon + diskon;
                            Ext.getCmp('pd_rp_total_diskon').setValue(total_diskon);
                            var harga_jual = parseFloat(Ext.getCmp('pd_hrg_jual').getValue());
                            var total_harga_net = harga_jual - total_diskon;
                            Ext.getCmp('pd_rp_harga_nett').setValue(total_harga_net);
                            var jumlah = total_harga_net * Ext.getCmp('pd_qty').getValue();
                            var exc = jumlah / 1.1;
                            Ext.getCmp('pd_so_jumlah').setValue(jumlah);
                            //Ext.getCmp('pd_so_total_exc').setValue(exc);
                            
                            var het_cogs = parseFloat(Ext.getCmp('pd_rp_het_cogs').getValue());
                            if (total_harga_net < het_cogs){
                                    Ext.Msg.show({
                                        title: 'Error',
                                        msg: 'Harga Nett tidak boleh lebih kecil dari HET COGS (Inc)',
                                        modal: true,
                                        icon: Ext.Msg.ERROR,
                                        buttons: Ext.Msg.OK,
                                        fn: function(btn){
                                            if (btn == 'ok') {
                                                Ext.getCmp('pd_rp_diskon_satuan').reset();
                                               }
                                        }                            
                                    });
                                }
                        }
                }
                }
            },{
                xtype: 'numbercolumn',
                header: 'Total Diskon',
                dataIndex: 'rp_diskon_total',
                align: 'right',
                width: 90,
                format: '0,0',
                editor: new Ext.form.TextField({
                    xtype: 'numberfield',
                    fieldClass: 'readonly-input',
                    readOnly: true,
                    id: 'pd_rp_total_diskon'
                })
            },
            {
                hidden: true,
                dataIndex: 'pd_rp_diskon',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'pd_rp_diskon_hidden'
                })
            },
            {
                hidden: true,
                header: 'Total Diskon',
                dataIndex: 'rp_diskon_total',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'pd_rp_diskon_total'
                })
            },{
                xtype: 'numbercolumn',
                header: 'Harga Nett',
                dataIndex: 'rp_harga_nett',
                align: 'right',
                width: 100,
                format: '0,0',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'pd_rp_harga_nett'
                })
            }, {
                xtype: 'numbercolumn',
                header: 'Jumlah',
                dataIndex: 'rp_jumlah',
                align: 'right',
                width: 100,
                format: '0,0',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'pd_so_jumlah'
                })
            }, 
//            {
//               xtype: 'numbercolumn',
//                header: 'Diskon Satuan',
//                dataIndex: 'rp_diskon_satuan',
//                format: '0,0',
//                editor: new Ext.form.TextField({
//                    readOnly: true,
//                    fieldClass: 'readonly-input',
//                    id: 'pd_rp_diskon_satuan'
//                })
//            },  {
//                xtype: 'numbercolumn',
//                header: 'Total',
//                dataIndex: 'rp_total',
//                width: 100,
//                align: 'right',
//                format: '0,0',
//                editor: new Ext.form.TextField({
//                    readOnly: true,
//                    fieldClass: 'readonly-input',
//                    id: 'pd_so_total'
//                })
//            }, 
//            {
//                xtype: 'numbercolumn',
//                header: 'Jumlah (Exc PPN)',
//                dataIndex: 'rp_total_exc',
//                width: 100,
//                align: 'right',
//                format: '0,0',
//                editor: new Ext.form.TextField({
//                    readOnly: true,
//                    fieldClass: 'readonly-input',
//                    id: 'pd_so_total_exc'
//                })
//            }, 
            /*{
             xtype: 'booleancolumn',
             header: 'Dikirim',
             dataIndex: 'is_kirim',
             align: 'center',
             width: 50,
             trueText: 'Ya',
             falseText: 'Tidak',
             editor: {
             xtype: 'checkbox'
             }
             },*/{
                hidden: true,
                dataIndex: 'qty_beli_bonus',
                editor: {
                    xtype: 'numberfield',
                    fieldClass: 'readonly-input',
                    id: 'pd_qty_beli_bonus',
                    readOnly: true
                }
            }, {
                hidden: true,
                dataIndex: 'is_bonus_kelipatan',
                editor: {
                    xtype: 'numberfield',
                    fieldClass: 'readonly-input',
                    id: 'pd_is_bonus_kelipatan',
                    readOnly: true
                }
            }, {
                hidden: true,
                dataIndex: 'qty_bonus',
                editor: {
                    xtype: 'numberfield',
                    fieldClass: 'readonly-input',
                    id: 'pd_qty_bonus_awal',
                    readOnly: true
                }
//            }, {
//                header: 'Kode Barang Bonus',
//                dataIndex: 'kd_produk_bonus',
//                width: 110,
//                editor: new Ext.ux.TwinCombopdbonus({
//                    id: 'pd_kd_produk_bonus',
//                    store: strcbpdbonus,
//                    mode: 'local',
//                    valueField: 'kd_produk_bonus',
//                    displayField: 'kd_produk_bonus',
//                    typeAhead: true,
//                    triggerAction: 'all',
//                    //allowBlank: false,
//                    editable: false,
//                    hiddenName: 'kd_produk_bonus',
//                    emptyText: 'Pilih Produk Bonus'
//
//                })
//            }, {
//                header: 'Nama Barang Bonus',
//                dataIndex: 'nama_produk_bonus',
//                width: 250,
//                editor: new Ext.form.TextField({
//                    readOnly: true,
//                    fieldClass: 'readonly-input',
//                    id: 'pd_nama_produk_bonus'
//                })
//            }, {
//                xtype: 'numbercolumn',
//                header: 'Qty Bonus',
//                dataIndex: 'qty_bonus',
//                width: 70,
//                format: '0,0',
//                editor: {
//                    xtype: 'numberfield',
//                    fieldClass: 'readonly-input',
//                    id: 'pd_qty_bonus',
//                }
            }
        ]
    });

    gridpenjualandistribusi.getSelectionModel().on('selectionchange', function(sm) {
        gridpenjualandistribusi.removeBtn.setDisabled(sm.getCount() < 1);
    });

    /*
     var strpdjenispembayaran = new Ext.data.Store({
     reader: new Ext.data.JsonReader({
     fields: [
     {name: 'is_pilih', type: 'bool'},
     {name: 'kd_jenis_bayar', type: 'text'},
     {name: 'nm_pembayaran', type: 'text'},
     {name: 'charge', type: 'int'},
     {name: 'rp_jumlah', type: 'int'},
     {name: 'rp_charge', type: 'int'},
     {name: 'rp_total', type: 'int'},
     {name: 'no_kartu', type: 'text'},
     {name: 'tgl_jth_tempo', type: 'text'},				
     ],
     root: 'data',
     totalProperty: 'record'
     }),
     proxy: new Ext.data.HttpProxy({
     url: '<?= site_url("penjualan_distribusi/get_all_jenis_pembayaran") ?>',
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
     
     strpdjenispembayaran.on('update', function(){
     var total_jumlah = 0;
     var total_charge = 0;
     var total_bayar = 0;
     var total_tagihan = Ext.getCmp('pd_rp_total_tagihan').getValue();
     
     
     strpdjenispembayaran.each(function(node){			
     if (node.data.is_pilih) {
     total_jumlah += (node.data.rp_jumlah);
     total_charge += (node.data.rp_charge);
     total_bayar += (node.data.rp_total);
     }		
     });
     
     //Ext.getCmp('pd_rp_bank_charge').setValue(total_charge);
     var grand_total = total_tagihan + total_charge;
     
     Ext.getCmp('pd_rp_total_bayar').setValue(grand_total);
     Ext.getCmp('display_grand_total').setValue(Ext.util.Format.number(grand_total, '0,0'));
     Ext.getCmp('pd_total_bayar').setValue(total_bayar);
     var kembali = grand_total - total_bayar;
     //Ext.getCmp('pd_kembali_sisa').setValue(kembali);
     });
     
     var editorpdjenispembayaran = new Ext.ux.grid.RowEditor({
     saveText: 'Update'
     });
     
     var gridpdjenispembayaran = new Ext.grid.GridPanel({
     id: 'idgridpdjenispembayaran',
     store: strpdjenispembayaran,
     stripeRows: true,
     height: 150,		
     border:true,
     frame:true,
     plugins: [editorpdjenispembayaran],
     columns: [{
     xtype: 'booleancolumn',
     header: 'Pilih',
     dataIndex: 'is_pilih',
     align: 'center',
     width: 40,
     trueText: 'Ya',
     falseText: 'Tidak',
     editor: {
     xtype: 'checkbox'
     }
     },{            
     header: 'Kode',
     dataIndex: 'kd_jenis_bayar',
     width: 50,            		
     editor: new Ext.form.TextField({               
     readOnly: true,
     id: 'pd_kd_jenis_bayar'
     })
     },{            
     header: 'Jenis Pembayaran',
     dataIndex: 'nm_pembayaran',
     width: 160,
     sortable: true,			
     editor: new Ext.form.TextField({               
     readOnly: true,
     id: 'pd_nama_pembayaran'
     })
     },{
     xtype: 'numbercolumn',
     header: 'Charge (%)',
     dataIndex: 'charge',
     width: 70,
     format: '0,0',
     align:'center',
     editor: {
     xtype: 'numberfield',
     id: 'epdjp_charge',
     readOnly: true,				
     }
     },{
     xtype: 'numbercolumn',
     header: 'Jumlah',
     dataIndex: 'rp_jumlah',			
     width: 100,
     format: '0,0',
     editor: {
     xtype: 'numberfield',
     id: 'epdjp_rp_jumlah',
     // allowBlank: false,
     selectOnFocus: true,
     listeners:{
     'change': function(){
     var total_tagihan = Ext.getCmp('pd_rp_total_tagihan').getValue();
     
     if(total_tagihan == 0){
     Ext.Msg.show({
     title: 'Error',
     msg: 'Total tagihan masih kosong',
     modal: true,
     icon: Ext.Msg.ERROR,
     buttons: Ext.Msg.OK
     });
     }else{
     var rp_charge = (parseInt(Ext.getCmp('epdjp_charge').getValue()) * parseInt(this.getValue()))/100;
     Ext.getCmp('epdjp_rp_charge').setValue(rp_charge);
     var rp_total = parseInt(this.getValue()) + rp_charge;
     Ext.getCmp('epdjp_rp_total').setValue(rp_total);
     }
     
     }
     }
     }
     },{
     xtype: 'numbercolumn',
     header: 'Charge (Rp)',
     dataIndex: 'rp_charge',			
     width: 100,
     format: '0,0',
     editor: {
     xtype: 'numberfield',
     id: 'epdjp_rp_charge',
     readOnly: true
     }
     },{
     xtype: 'numbercolumn',
     header: 'Total',
     dataIndex: 'rp_total',			
     width: 100,
     format: '0,0',
     editor: {
     xtype: 'numberfield',
     id: 'epdjp_rp_total',
     readOnly: true
     }
     },{
     header: 'No Kartu',
     dataIndex: 'no_kartu',
     width: 100,
     editor: new Ext.form.TextField({
     id: 'epdjp_no_kartu'
     })
     },{
     header: 'Tgl Jatuh Tempo',
     dataIndex: 'tgl_jth_tempo',
     width: 100,
     editor: new Ext.form.DateField({
     id: 'epdjp_tgl_jth_tempo',
     format: 'd/m/Y',
     })
     },
     ]
     });
     
     gridpdjenispembayaran.on('afterrender', function(){
     strpdjenispembayaran.load();
     });
     */
//    function hitungGrandTotal() {
//        var total_pembelian = Ext.getCmp('pd_rp_total').getValue();
//        //var total_diskon = Ext.getCmp('pd_rp_diskon_tambahan').getValue();
//        var bi_kirim = Ext.getCmp('pd_rp_ongkos_kirim').getValue();
//        //var bi_pasang = Ext.getCmp('pd_rp_ongkos_pasang').getValue();
//        //var bank_charge = Ext.getCmp('pd_rp_bank_charge').getValue();
//
//        var total_tagihan = (total_pembelian - total_diskon) + bi_kirim;
//        //var grand_total = total_tagihan + bank_charge;
//
//        Ext.getCmp('pd_rp_total_tagihan').setValue(total_tagihan);
//        //Ext.getCmp('pd_rp_total_bayar').setValue(grand_total);
//        //Ext.getCmp('display_grand_total').setValue(Ext.util.Format.number(total_tagihan, '0,0'));
//    }
    ;
    var penjualandistribusi = new Ext.FormPanel({
        id: 'penjualandistribusi',
        border: false,
        frame: true,
        autoScroll: true,
        bodyStyle: 'padding-right:20px;',
        labelWidth: 130,
        items: [headerpenjualandistribusi,
            gridpenjualandistribusi,
            {
                layout: 'column',
                border: false,
                monitorValid: true,
                items: [{
                        columnWidth: .6,
                        style: 'margin:0px 3px 0 0;',
                        items: [
                            //gridpdjenispembayaran,
                            {
                                xtype: 'fieldset',
                                autoWidth: true,
                                title: 'Dikirim ke?',
                                //collapsible: true,								
                                items: [
                                    {
                                        xtype: 'textfield',
                                        fieldLabel: 'Dikirim ke <span class="asterix">*</span>',
                                        allowBlank: false,
                                        name: 'kirim_so',
                                        id: 'pd_kirim_so',
                                        anchor: '60%'
                                    }, {
                                        xtype: 'textarea',
                                        fieldLabel: 'Alamat <span class="asterix">*</span>',
                                        allowBlank: false,
                                        name: 'kirim_alamat_so',
                                        id: 'pd_kirim_alamat_so',
                                        anchor: '60%'
                                    }, {
                                        xtype: 'textfield',
                                        fieldLabel: 'No Telp <span class="asterix">*</span>',
                                        allowBlank: false,
                                        name: 'kirim_telp_so',
                                        id: 'pd_kirim_telp_so',
                                        anchor: '60%'
                                    }
                                ]
                            }
                        ]
                    }, {
                        columnWidth: .4,
                        layout: 'form',
                        border: false,
                        labelWidth: 110,
                        defaults: {labelSeparator: ''},
                        items: [
                            {
                                xtype: 'fieldset',
                                autoHeight: true,
                                title: 'Amount Charge',
                                items: [
                                    {
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'Jumlah',
                                        name: 'rp_total',
                                        readOnly: true,
                                        id: 'pd_rp_total',
                                        anchor: '95%',
                                        fieldClass: 'readonly-input number',
                                        value: '0'
                                    },
//                                       {
//                                        xtype: 'numericfield',
//                                        currencySymbol: '',
//                                        fieldLabel: 'Uang Muka',
//                                        name: 'rp_uang_muka',
//                                        id: 'pd_uang_muka',
//                                        anchor: '95%',
//                                        fieldClass: 'number',
//                                        selectOnFocus: true,
//                                        value: '0',
//                                        listeners: {
//                                            change: function() {
//                                                var total = Ext.getCmp('pd_rp_total').getValue();
//                                                var uang_muka = this.getValue();
//                                                var total_tagihan = total - uang_muka;
//                                                Ext.getCmp('pd_total').setValue(total_tagihan);
//                                                var dpp = total_tagihan / 1.1;
//                                                dpp = Math.round(dpp);
//                                                Ext.getCmp('pd_dpp').setValue(dpp);
//                                                var ppn = dpp * 0.1;
//                                                ppn = Math.round(ppn);
//                                                Ext.getCmp('pd_pcin_rp_ppn').setValue(ppn);
//                                            }
//                                        }
//                                    },
//                                    {
//                                        xtype: 'compositefield',
//                                        fieldLabel: 'Diskon Tambahan',
//                                        combineErrors: false,
//                                        items: [
//                                            {
//                                                xtype: 'numericfield',
//                                                currencySymbol: '',
//                                                format: '0',
//                                                name: 'pct_diskon_tambahan',
//                                                id: 'pd_op_diskon_tambahan',
//                                                fieldClass: 'number',
//                                                width: 60,
//                                                value: '0',
//                                                maxValue: 100,
//                                                listeners: {
//                                                    'change': function() {
//                                                        var diskon_tambahan = Ext.getCmp('pd_rp_total').getValue() * this.getValue() / 100;
//                                                        var total = Ext.getCmp('pd_rp_total').getValue() - diskon_tambahan;
//
//                                                        Ext.getCmp('pd_rp_diskon_tambahan').setValue(diskon_tambahan);
//                                                        Ext.getCmp('pd_total').setValue(total);
//                                                        var ppn = total * 0.1;
//                                                        Ext.getCmp('pd_pcin_rp_ppn').setValue(ppn);
//                                                        var total_tagihan = total + ppn;
//                                                        Ext.getCmp('pd_rp_total_tagihan').setValue(total_tagihan);
//                                                    }
//                                                }
//
//                                            },
//                                            {
//                                                xtype: 'displayfield',
//                                                value: '%',
//                                                width: 17.5
//                                            },
//                                            {
//                                                xtype: 'numericfield',
//                                                name: 'rp_diskon_tambahan',
//                                                id: 'pd_rp_diskon_tambahan',
//                                                currencySymbol: '',
//                                                fieldClass: 'number',
//                                                readOnly: false,
//                                                width: 140,
//                                                anchor: '90%',
//                                                listeners: {
//                                                    'change': function() {
//                                                        var diskon_tambahan = (this.getValue() / Ext.getCmp('pd_rp_total').getValue()) * 100;
//                                                        var total = Ext.getCmp('pd_rp_total').getValue() - this.getValue();
//
//                                                        Ext.getCmp('pd_op_diskon_tambahan').setValue(diskon_tambahan);
//                                                        Ext.getCmp('pd_total').setValue(total);
//                                                        var ppn = total * 0.1;
//                                                        Ext.getCmp('pd_pcin_rp_ppn').setValue(ppn);
//                                                        var total_tagihan = total + ppn;
//                                                        Ext.getCmp('pd_rp_total_tagihan').setValue(total_tagihan);
//                                                    }
//                                                }
//
//                                            }
//                                        ]
//                                    }, 
//                                    {
//                                        xtype: 'numericfield',
//                                        currencySymbol: '',
//                                        fieldLabel: 'Total',
//                                        name: 'rp_total',
//                                        readOnly: true,
//                                        id: 'pd_total',
//                                        anchor: '95%',
//                                        fieldClass: 'readonly-input number',
//                                        value: '0'
//                                    }, 
                                            {
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'DPP',
                                        name: 'rp_dpp',
                                        readOnly: true,
                                        id: 'pd_dpp',
                                        anchor: '95%',
                                        fieldClass: 'readonly-input number',
                                        value: '0,0'
                                    }, {
                                        xtype: 'compositefield',
                                        fieldLabel: 'PPN',
                                        combineErrors: false,
                                        items: [
                                            {
                                                xtype: 'numericfield',
                                                currencySymbol: '',
                                                format: '0',
                                                name: 'ppn',
                                                id: 'pd_pcin_ppn',
                                                fieldClass: 'readonly-input number',
                                                width: 60,
                                                value: '10',
                                                maxValue: 100,
                                                listeners: {
//                                                    'change': function(){
//                                                        var total = Ext.getCmp('pcin_rp_jumlah').getValue();
//                                                        var diskon = Ext.getCmp('pcin_persen_diskon').getValue();
//                                                        var afterDiskon = total - diskon ;
//                                                        var pembulatan = Ext.getCmp('pcin_pembulatan').getValue();
//                                                        var rp_ppn = afterDiskon * (Ext.getCmp('pcin_ppn').getValue() / 100);
//                                                       
//                                                        var total_invoice = afterDiskon + rp_ppn;
//                                                        var grand_total = afterDiskon + rp_ppn + pembulatan;
//														
//                                                        Ext.getCmp('pcin_rp_diskon').setValue(diskon);
//                                                        Ext.getCmp('pcin_rp_ppn').setValue(rp_ppn);
//                                                        Ext.getCmp('pcin_rp_total_grand').setValue(grand_total);
//                                                        Ext.getCmp('pcin_total_invoice').setValue(total_invoice);
//                                                        Ext.getCmp('pcin_dpp').setValue(afterDiskon);	
//                                                    }
                                                }

                                            },
                                            {
                                                xtype: 'displayfield',
                                                value: '%',
                                                width: 17.5
                                            },
                                            {
                                                xtype: 'numericfield',
                                                name: 'rp_ppn',
                                                id: 'pd_pcin_rp_ppn',
                                                currencySymbol: '',
                                                fieldClass: 'readonly-input number',
                                                readOnly: true,
                                                width: 140,
                                                anchor: '90%'

                                            }
                                        ]
                                    },
//                                    {
//                                        xtype: 'numericfield',
//                                        currencySymbol: '',
//                                        fieldLabel: 'Diskon Tambahan (Rp)',
//                                        name: 'rp_diskon_tambahan',
//                                        id: 'pd_rp_diskon_tambahan',
//                                        anchor: '95%',
//                                        fieldClass: 'number',
//                                        value: '0',
//                                        listeners: {
//                                            change: function() {
//                                                var total_pembelian = Ext.getCmp('pd_rp_total').getValue();
//                                                var total_diskon = Ext.getCmp('pd_rp_diskon_tambahan').getValue();
//                                                var bi_kirim = Ext.getCmp('pd_rp_ongkos_kirim').getValue();
//                                                //var bi_pasang = Ext.getCmp('pd_rp_ongkos_pasang').getValue();
//                                                //var bank_charge = Ext.getCmp('pd_rp_bank_charge').getValue();
//
//                                                var total_tagihan = (total_pembelian - total_diskon) + bi_kirim;
//                                                //var grand_total = total_tagihan + bank_charge;
//
//                                                Ext.getCmp('pd_rp_total_tagihan').setValue(total_tagihan);
//                                                //Ext.getCmp('pd_rp_total_bayar').setValue(grand_total);
//                                                Ext.getCmp('display_grand_total').setValue(Ext.util.Format.number(total_tagihan, '0,0'));
//                                            }
//                                        }
//                                    },
//                                    {
//                                        xtype: 'numericfield',
//                                        currencySymbol: '',
//                                        fieldLabel: 'Biaya Kirim',
//                                        name: 'rp_ongkos_kirim',
//                                        id: 'pd_rp_ongkos_kirim',
//                                        anchor: '95%',
//                                        fieldClass: 'number',
//                                        selectOnFocus: true,
//                                        value: '0',
//                                        listeners: {
//                                            change: function() {
//                                                var total = Ext.getCmp('pd_total').getValue();
//                                                var ppn = Ext.getCmp('pd_pcin_rp_ppn').getValue();
//                                                var bi_kirim = Ext.getCmp('pd_rp_ongkos_kirim').getValue();
//
//                                                var total_tagihan = (total + ppn) + bi_kirim;
//                                                Ext.getCmp('pd_rp_total_tagihan').setValue(total_tagihan);
//
//                                                // Ext.getCmp('display_grand_total').setValue(Ext.util.Format.number(total_tagihan, '0,0'));
//                                            }
//                                        }
//                                    },
                                        /*{
                                     xtype: 'numericfield',
                                     currencySymbol: '',
                                     fieldLabel: 'Biaya Pasang',
                                     name: 'rp_ongkos_pasang',																		
                                     id: 'pd_rp_ongkos_pasang',										
                                     anchor: '95%',	
                                     fieldClass:'number',
                                     value:'0',
                                     selectOnFocus: true,		
                                     listeners:{
                                     change: function(){
                                     var total_pembelian = Ext.getCmp('pd_rp_total').getValue();
                                     var total_diskon = Ext.getCmp('pd_rp_diskon').getValue();
                                     var bi_kirim = Ext.getCmp('pd_rp_ongkos_kirim').getValue();
                                     var bi_pasang = this.getValue();
                                     //var bank_charge = Ext.getCmp('pd_rp_bank_charge').getValue();
                                     
                                     var total_tagihan = (total_pembelian - total_diskon) + bi_kirim + bi_pasang;
                                     //var grand_total = total_tagihan + bank_charge;
                                     
                                     Ext.getCmp('pd_rp_total_tagihan').setValue(total_tagihan);
                                     //Ext.getCmp('pd_rp_total_bayar').setValue(grand_total);
                                     Ext.getCmp('display_grand_total').setValue(Ext.util.Format.number(total_tagihan, '0,0'));
                                     }
                                     }														
                                     },{
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: '<b>Grand Total</b>',
                                        name: 'rp_total_tagihan',
                                        readOnly: true,
                                        id: 'pd_rp_total_tagihan',
                                        anchor: '95%',
                                        fieldClass: 'readonly-input bold-input number',
                                        value: '0'

                                    }*/
                                ]
                            }
                        ]
                    }]
            }
        ],
        buttons: [{
                text: 'Save',
                handler: function() {

                    var detailpenjualandistribusi = new Array();
                    strpenjualandistribusi.each(function(node) {
                        detailpenjualandistribusi.push(node.data);
                    });

                    Ext.getCmp('penjualandistribusi').getForm().submit({
                        url: '<?= site_url("penjualan_distribusi/update_row") ?>',
                        scope: this,
                        params: {
                            detail: Ext.util.JSON.encode(detailpenjualandistribusi),
                            _rp_total: Ext.getCmp('pd_rp_total').getValue(),
                            //_uang_muka: Ext.getCmp('pd_uang_muka').getValue(),
                            //_total: Ext.getCmp('pd_total').getValue(),
                            _rp_dpp: Ext.getCmp('pd_dpp').getValue(),
                            //_rp_ongkos_kirim: Ext.getCmp('pd_rp_ongkos_kirim').getValue(),
                            //_pct_diskon_tambahan: Ext.getCmp('pd_op_diskon_tambahan').getValue(),
                            _pct_ppn: Ext.getCmp('pd_pcin_ppn').getValue(),
                            _rp_ppn: Ext.getCmp('pd_pcin_rp_ppn').getValue(),
                            //_rp_total_bayar: Ext.getCmp('pd_rp_total_tagihan').getValue()
                        },
                        waitMsg: 'Saving Data...',
                        success: function(form, action) {
                            var r = Ext.util.JSON.decode(action.response.responseText);
                            Ext.Msg.show({
                                title: 'Success',
                                msg: 'Form submitted successfully',
                                modal: true,
                                icon: Ext.Msg.INFO,
                                buttons: Ext.Msg.OK,
                                fn: function(btn) {
                                    winpenjualandistribusiprint.show();
                                    Ext.getDom('penjualandistribusiprint').src = r.printUrl;
                                }
                            });

                            clearpenjualandistribusi();
                        },
                        failure: function(form, action) {
                            var fe = Ext.util.JSON.decode(action.response.responseText);
                            Ext.Msg.show({
                                title: 'Error',
                                msg: fe.errMsg,
                                modal: true,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OK,
                                fn: function(btn) {
                                    if (btn === 'ok' && fe.errMsg === 'Session Expired') {
                                        window.location = '<?= site_url("auth/login") ?>';
                                    }
                                }
                            });

                        }
                    });

                }
            }, {
                text: 'Reset',
                handler: function() {
                    clearpenjualandistribusi();
                }
            }]
    });
    var winpenjualandistribusiprint = new Ext.Window({
        id: 'id_winpenjualandistribusiprint',
        title: 'Print Penjualan Distribusi',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html: '<iframe style="width:100%;height:100%;" id="penjualandistribusiprint" src=""></iframe>'
    });
    penjualandistribusi.on('afterrender', function() {
        this.getForm().load({
            url: '<?= site_url("penjualan_distribusi/get_form") ?>',
            failure: function(form, action) {
                var de = Ext.util.JSON.decode(action.response.responseText);
                Ext.Msg.show({
                    title: 'Error',
                    msg: de.errMsg,
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK,
                    fn: function(btn) {
                        if (btn === 'ok' && de.errMsg === 'Session Expired') {
                            window.location = '<?= site_url("auth/login") ?>';
                        }
                    }
                });
            }
        });
    });

    function clearpenjualandistribusi() {

        Ext.getCmp('penjualandistribusi').getForm().reset();
        Ext.getCmp('penjualandistribusi').getForm().load({
            url: '<?= site_url("penjualan_distribusi/get_form") ?>',
            failure: function(form, action) {
                var de = Ext.util.JSON.decode(action.response.responseText);
                Ext.Msg.show({
                    title: 'Error',
                    msg: de.errMsg,
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK,
                    fn: function(btn) {
                        if (btn === 'ok' && de.errMsg === 'Session Expired') {
                            window.location = '<?= site_url("auth/login") ?>';
                        }
                    }
                });
            }
        });
        strpenjualandistribusi.removeAll();
        //strpdjenispembayaran.reload();
    }
</script>