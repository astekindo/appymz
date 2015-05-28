<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<script type="text/javascript">

    // input produk
    var strcbNamaProduk = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_produk', 'nama_produk'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_barang/get_rows") ?>',
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

    var cbNamaProduk = new Ext.form.ComboBox({
        fieldLabel: 'Nama Produk <span class="asterix">*</span>',
        id: 'mb_nama_produk',
        triggerAction: 'query',
        store: strcbNamaProduk,
        valueField: 'nama_produk',
        displayField: 'nama_produk',
        // typeAhead: true,
        allowBlank: false,
        width: 640,
        anchor: '90%',
        hiddenName: 'nama_produk',
        style:'text-transform: uppercase',
        minChars: 1,
        hideTrigger:true
    });


    /* START FORM */

    var strcbkdprodukmb = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_produk','kd_produk_lama','kd_produk_supp', 'nama_produk', 'jml_stok'],
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

    var searchmbproduk = new Ext.app.SearchField({
        store: strcbkdprodukmb,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'mbsearchlistbarang'
    });

    var tbmbproduk = new Ext.Toolbar({
        items: [searchmbproduk]
    });

    var gridmbsearchproduk = new Ext.grid.GridPanel({
        store: strcbkdprodukmb,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'Kode Produk',
                dataIndex: 'kd_produk',
                width: 90,
                sortable: true

        },{
                header: 'Kode Produk Lama',
                dataIndex: 'kd_produk_lama',
                width: 110,
                sortable: true

            },{
                header: 'Kode Produk Supp',
                dataIndex: 'kd_produk_supp',
                width: 110,
                sortable: true

        },{
                header: 'Nama Produk',
                dataIndex: 'nama_produk',
                width: 300,
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
                				Ext.getCmp('mb_brg_cbkategori1').setReadOnly(true);
                                Ext.getCmp('mb_brg_cbkategori2').setReadOnly(true);
                                Ext.getCmp('mb_brg_cbkategori3').setReadOnly(true);
                                Ext.getCmp('mb_brg_cbkategori4').setReadOnly(true);
                                Ext.getCmp('id_combo_lokasi_mstbarang').setReadOnly(true);
                                Ext.getCmp('id_combo_blok_mstbarang').setReadOnly(true);
                                Ext.getCmp('id_combo_subblok_mstbarang').setReadOnly(true);
                                Ext.getCmp('id_combo_subblok_mstbarang').allowBlank = true;
                                Ext.getCmp('id_comboTypeLokasiMasterBarang').setReadOnly(true);

                                Ext.getCmp('mb_brg_cbkategori1').addClass('readonly-input');
                                Ext.getCmp('mb_brg_cbkategori2').addClass('readonly-input');
                                Ext.getCmp('mb_brg_cbkategori3').addClass('readonly-input');
                                Ext.getCmp('mb_brg_cbkategori4').addClass('readonly-input');
                                Ext.getCmp('id_combo_lokasi_mstbarang').addClass('readonly-input');
                                Ext.getCmp('id_combo_blok_mstbarang').addClass('readonly-input');
                                Ext.getCmp('id_combo_subblok_mstbarang').addClass('readonly-input');
                                Ext.getCmp('id_comboTypeLokasiMasterBarang').addClass('readonly-input');

                                Ext.getCmp('id_combo_lokasi_mstbarang').setValue(de.data.kd_lokasi);
                                Ext.getCmp('id_combo_blok_mstbarang').setValue(de.data.kd_blok);
                                Ext.getCmp('id_combo_subblok_mstbarang').setValue(de.data.kd_sub_blok);
                                Ext.getCmp('id_comboTypeLokasiMasterBarang').setValue(de.data.flag_lokasi);

                                Ext.getCmp('id_combo_lokasi_mstbarang').setRawValue(de.data.nama_lokasi);
                                Ext.getCmp('id_combo_blok_mstbarang').setRawValue(de.data.nama_blok);
                                Ext.getCmp('id_combo_subblok_mstbarang').setRawValue(de.data.nama_sub_blok);
                                Ext.getCmp('id_comboTypeLokasiMasterBarang').setRawValue(de.data.type_lokasi);

                                var senders = Ext.getCmp('mb_gridSender').getValue();
                                if(senders === 'mb_kd_produk') {
                                    Ext.Ajax.request({
                                        url: '<?= site_url("master_barang/get_kategori4") ?>',
                                        method: 'POST',
                                        params: {
                                            cmd: 'get',
                                            kd_kategori1: de.data.kd_kategori1_bonus,
                                            kd_kategori2: de.data.kd_kategori2_bonus,
                                            kd_kategori3: de.data.kd_kategori3_bonus,
                                            kd_kategori4: de.data.kd_kategori4_bonus
                                        },
                                        callback:function(opt,success,responseObj){
                                            var bns = Ext.util.JSON.decode(responseObj.responseText);
                                            if(bns.success==true){
                                                console.log(bns);
                                            }
                                        }
                                    });
                                    Ext.Ajax.request({
                                        url: '<?= site_url("master_barang/get_kategori4") ?>',
                                        method: 'POST',
                                        params: {
                                            cmd: 'get',
                                            kd_kategori1: de.data.kd_kategori1_member,
                                            kd_kategori2: de.data.kd_kategori2_member,
                                            kd_kategori3: de.data.kd_kategori3_member,
                                            kd_kategori4: de.data.kd_kategori4_member
                                        },
                                        callback:function(opt,success,responseObj){
                                            var bns = Ext.util.JSON.decode(responseObj.responseText);
                                            if(bns.success==true){
                                                console.log(bns);
                                            }
                                        }
                                    });
                                    strmbhisto.reload({params:{kd_produk:sel[0].get('kd_produk')}});
                                    strmbhistocogs.load({params:{kd_produk:sel[0].get('kd_produk')}});
                                    strmbhistocogsdist.load({params:{kd_produk:sel[0].get('kd_produk')}});
                                    strmbhistoinv.load({params:{kd_produk:sel[0].get('kd_produk')}});

                                    Ext.getCmp('mb_cbsatuan').setValue(de.data.kd_satuan);
                                    Ext.getCmp('mb_cbsatuan_berat').setValue(de.data.kd_satuan_berat);
                                    Ext.getCmp('mb_cbukuran').setValue(de.data.kd_ukuran);
                                    Ext.getCmp('mb_no_urut').setValue(de.data.no_urut);
                                    Ext.getCmp('mb_created_date').setValue(new Date(de.data.created_date));
                                    Ext.getCmp(senders).setValue(sel[0].get('kd_produk'));
                                    Ext.getCmp('mb_nama_produk').setValue(sel[0].get('nama_produk'));
                                    setDataMasterProduk(de.data);
                                    if(de.data.kd_diskon_sales!=undefined){
                                        setDataDiskonBonus(de.data);
                                    }else{
                                        cleanDataDiskonBonus();
                                    }
                                }else if(senders === 'mb_kd_produk_bonus'){
                                    Ext.getCmp(senders).setValue(sel[0].get('kd_produk'));
                                    Ext.getCmp('mb_nama_produk_bonus').setValue(sel[0].get('nama_produk'));
                                }else{
                                    Ext.getCmp(senders).setValue(sel[0].get('kd_produk'));
                                    Ext.getCmp('mb_nama_produk_member').setValue(sel[0].get('nama_produk'));
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
                    });
                    menumb.hide();
                }
            }
        },
        tbar:tbmbproduk,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strcbkdprodukmb,
            displayInfo: true
        })
    });

    var menumb = new Ext.menu.Menu();
    menumb.add(new Ext.Panel({
        title: 'Pilih Produk',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 700,
        height: 400,
        closeAction: 'hide',
        plain: true,
        items: [gridmbsearchproduk],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menumb.hide();
                }
            }]
    }));

    Ext.ux.TwinCombo = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            Ext.getCmp('mb_gridSender').setValue(this.id);
            strcbkdprodukmb.load();
            menumb.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    //History
    var strmbhisto = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_produk', 'nama_produk', 'koreksi_ke', 'tanggal', 'ket_perubahan','created_by','updated_by'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_barang/get_history") ?>',
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

    var gridmbhisto = new Ext.grid.GridPanel({
        id: 'id-gridmbhisto',
        title: 'HISTORY PERUBAHAN',
        store: strmbhisto,
        stripeRows: true,
        frame: true,
        border:true,
        height: 200,
        columns: [{
                header: 'Koreksi Ke',
                dataIndex: 'koreksi_ke',
                width: 90,
                sortable: true
        },{
                dataIndex: 'updated_date',
                header: 'Tanggal',
                dataIndex: 'tanggal',
                width: 90,
                sortable: true
        },{
                header: 'Kode Produk',
                dataIndex: 'kd_produk',
                width: 90,
                sortable: true
        },{
                header: 'Nama Produk',
                dataIndex: 'nama_produk',
                width: 400,
                sortable: true
        },{
                header: 'Keterangan Perubahan',
                dataIndex: 'ket_perubahan',
                width: 400,
                sortable: true
        },{
                header: 'Create By',
                dataIndex: 'created_by',
                width: 100,
                sortable: true
        },{
                header: 'Updated By',
                dataIndex: 'updated_by',
                width: 100,
                sortable: true
        }],
        listeners: {
            'rowdblclick': function(){
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.Ajax.request({
                        url: '<?= site_url("master_barang/get_row_history") ?>',
                        method: 'POST',
                        params: {
                            kd_produk: Ext.getCmp('mb_kd_produk').getValue(),
                            koreksi_ke: sel[0].get('koreksi_ke')

                        },
                        callback:function(opt,success,responseObj){
                            var de = Ext.util.JSON.decode(responseObj.responseText);
                            if(de.success==true){
                                Ext.getCmp('mb_nama_produk').setValue(sel[0].get('nama_produk'));
                                setDataMasterProduk(de.data);

                                if(de.data.kd_diskon_sales!=undefined){
                                    setDataDiskonBonus(de.data);
                                }else{
                                    cleanDataDiskonBonus();
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
                    });
                    menumb.hide();
                }
            }
        },
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strmbhisto,
            displayInfo: true
        })
    });

    //History COGS
    var strmbhistocogs = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_ref', 'kd_produk', 'qty_in', 'qty_out', 'qty_stok', 'hrg_beli_satuan', 'rp_cogs', 'rp_nilai_stok',
                'rp_angkut', 'pct_margin', 'rp_margin', 'rp_ppn', 'rp_het', 'rp_het_hrg_beli', 'tanggal', 'jenis_trx', 'rp_ajd_jumlah'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_barang/get_history_cogs") ?>',
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

    var gridmbhistocogs = new Ext.grid.GridPanel({
        id: 'id-gridmbhistocogs',
        title: 'HISTORY COGS & HET SUPERMARKET',
        store: strmbhistocogs,
        stripeRows: true,
        frame: true,
        border:true,
        height: 200,
        columns: [{
                header: 'Tanggal',
                dataIndex: 'tanggal',
                width: 75,
                sortable: true
        },{
                header: 'No Dokumen',
                dataIndex: 'no_ref',
                width: 125,
                sortable: true
        },{
                header: 'Jenis',
                dataIndex: 'jenis_trx',
                width: 125,
                sortable: true
        },{
                header: 'Kode Barang',
                dataIndex: 'kd_produk',
                width: 90,
                sortable: true
        },{
                header: 'Qty In',
                dataIndex: 'qty_in',
                width: 90,
                sortable: true
        },{
                header: 'Qty Out',
                dataIndex: 'qty_out',
                width: 90,
                sortable: true
        },{
                header: 'Qty Sisa',
                dataIndex: 'qty_stok',
                width: 90,
                sortable: true
        },{
                header: 'Harga Beli',
                dataIndex: 'hrg_beli_satuan',
                width: 90,
                sortable: true
        },{
                header: 'Adjust Inv',
                dataIndex: 'rp_ajd_jumlah',
                width: 90,
                sortable: true
        },{
                header: 'COGS',
                dataIndex: 'rp_cogs',
                width: 90,
                sortable: true
        },{
                header: 'Jumlah Nilai Stok',
                dataIndex: 'rp_nilai_stok',
                width: 90,
                sortable: true
        },{
                header: 'Ongkos Angkut',
                dataIndex: 'rp_angkut',
                width: 90,
                sortable: true
        },{
                header: '%margin',
                dataIndex: 'pct_margin',
                width: 90,
                sortable: true
        },{
                header: 'Nilai Margin',
                dataIndex: 'rp_margin',
                width: 90,
                sortable: true
        },{
                header: 'PPn',
                dataIndex: 'rp_ppn',
                width: 90,
                sortable: true
        },{
                header: 'HET by COGS',
                dataIndex: 'rp_het',
                width: 90,
                sortable: true
        },{
                header: 'HET by HRG BELI',
                dataIndex: 'rp_het_hrg_beli',
                width: 125,
                sortable: true
        }],
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strmbhistocogs,
            displayInfo: true
        })
    });

    //History COGS Distribusi
    var strmbhistocogsdist = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_ref', 'kd_produk', 'qty_in', 'qty_out', 'qty_stok', 'hrg_beli_satuan', 'rp_cogs', 'rp_nilai_stok',
                'rp_angkut', 'pct_margin', 'rp_margin', 'rp_ppn', 'rp_het', 'rp_het_hrg_beli', 'tanggal', 'jenis_trx', 'rp_ajd_jumlah'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_barang/get_history_cogs_dist") ?>',
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

    var gridmbhistocogsdist = new Ext.grid.GridPanel({
        id: 'id-gridmbhistocogsdist',
        title: 'HISTORY COGS & HET DISTRIBUSI',
        store: strmbhistocogsdist,
        stripeRows: true,
        frame: true,
        border:true,
        height: 200,
        columns: [{
                header: 'Tanggal',
                dataIndex: 'tanggal',
                width: 75,
                sortable: true
        },{
                header: 'No Dokumen',
                dataIndex: 'no_ref',
                width: 125,
                sortable: true
        },{
                header: 'Jenis',
                dataIndex: 'jenis_trx',
                width: 125,
                sortable: true
        },{
                header: 'Kode Barang',
                dataIndex: 'kd_produk',
                width: 90,
                sortable: true
        },{
                header: 'Qty In',
                dataIndex: 'qty_in',
                width: 90,
                sortable: true
        },{
                header: 'Qty Out',
                dataIndex: 'qty_out',
                width: 90,
                sortable: true
        },{
                header: 'Qty Sisa',
                dataIndex: 'qty_stok',
                width: 90,
                sortable: true
        },{
                header: 'Harga Beli',
                dataIndex: 'hrg_beli_satuan',
                width: 90,
                sortable: true
        },{
                header: 'Adjust Inv',
                dataIndex: 'rp_ajd_jumlah',
                width: 90,
                sortable: true
        },{
                header: 'COGS',
                dataIndex: 'rp_cogs',
                width: 90,
                sortable: true
        },{
                header: 'Jumlah Nilai Stok',
                dataIndex: 'rp_nilai_stok',
                width: 90,
                sortable: true
        },{
                header: 'Ongkos Angkot',
                dataIndex: 'rp_angkut',
                width: 90,
                sortable: true
        },{
                header: '%margin',
                dataIndex: 'pct_margin',
                width: 90,
                sortable: true
        },{
                header: 'Nilai Margin',
                dataIndex: 'rp_margin',
                width: 90,
                sortable: true
        },{
                header: 'PPn',
                dataIndex: 'rp_ppn',
                width: 90,
                sortable: true
        },{
                header: 'HET by COGS',
                dataIndex: 'rp_het',
                width: 90,
                sortable: true
        },{
                header: 'HET by HRG BELI',
                dataIndex: 'rp_het_hrg_beli',
                width: 125,
                sortable: true
        }],
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strmbhistocogsdist,
            displayInfo: true
        })
    });

    //History Invoice
    var strmbhistoinv = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['tgl_invoice', 'no_invoice', 'qty_in',
                'kd_supplier', 'qty', 'nama_supplier',
                'harga_supplier','rp_diskon1','rp_diskon2',
                'rp_diskon3','rp_diskon4','rp_dpp',
                'rp_jumlah','rp_ajd_jumlah'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_barang/get_history_inv") ?>',
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

    var gridmbhistoinv = new Ext.grid.GridPanel({
        id: 'id-gridmbhistoinv',
        title: 'HISTORY INVOICE PEMBELIAN',
        store: strmbhistoinv,
        stripeRows: true,
        frame: true,
        border:true,
        height: 200,
        columns: [{
                header: 'Tgl Invoice',
                dataIndex: 'tgl_invoice',
                width: 125,
                sortable: true
        },{
                header: 'No Invoice',
                dataIndex: 'no_invoice',
                width: 90,
                sortable: true
        },{
                header: 'Kode Supplier',
                dataIndex: 'kd_supplier',
                width: 90,
                sortable: true
        },{
                header: 'Nama Supplier',
                dataIndex: 'nama_supplier',
                width: 150,
                sortable: true
        },{
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Qty',
                dataIndex: 'qty',
                width: 90,
                sortable: true
        },{
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Pricelist',
                dataIndex: 'harga_supplier',
                width: 90,
                sortable: true
        },{
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Disk 1',
                dataIndex: 'rp_diskon1',
                width: 90,
                sortable: true
        },{
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Disk 2',
                dataIndex: 'rp_diskon2',
                width: 90,
                sortable: true
        },{
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Disk 3',
                dataIndex: 'rp_diskon3',
                width: 90,
                sortable: true
        },{
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Disk 4',
                dataIndex: 'rp_diskon4',
                width: 90,
                sortable: true
        },{
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'DPP',
                dataIndex: 'rp_dpp',
                width: 90,
                sortable: true
        },{
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Adjust',
                dataIndex: 'rp_ajd_jumlah',
                width: 90,
                sortable: true
        },{
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Jumlah',
                dataIndex: 'rp_jumlah',
                width: 90,
                sortable: true
        }
        ],
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strmbhistoinv,
            displayInfo: true
        })
    });

    // combobox kategori1
    var str_brg_cbkategori1 = new Ext.data.Store({
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
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var brg_cbkategori1 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 1 <span class="asterix">*</span>',
        id: 'mb_brg_cbkategori1',
        store: str_brg_cbkategori1,
        valueField: 'kd_kategori1',
        displayField: 'nama_kategori1',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        width: 250,
        anchor: '90%',
        hiddenName: 'nama_kategori1',
        emptyText: 'Pilih kategori 1',
        listeners: {
            'select': function(combo, records) {
                var kdbrg_cbkategori1 = brg_cbkategori1.getValue();
                brg_cbkategori2.setValue();
                brg_cbkategori3.setValue();
                brg_cbkategori4.setValue();
                brg_cbkategori2.store.proxy.conn.url = '<?= site_url("master_barang/get_kategori2") ?>/' + kdbrg_cbkategori1;
                brg_cbkategori2.store.reload();
            }
        }
    });


    // combobox kategori2
    var str_brg_cbkategori2 = new Ext.data.Store({
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
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var brg_cbkategori2 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 2 <span class="asterix">*</span>',
        id: 'mb_brg_cbkategori2',
        mode: 'local',
        store: str_brg_cbkategori2,
        valueField: 'kd_kategori2',
        displayField: 'nama_kategori2',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        width: 250,
        anchor: '90%',
        hiddenName: 'nama_kategori2',
        emptyText: 'Pilih kategori 2',
        listeners: {
            select: function(combo, records) {
                var kd_brg_cbkategori1 = brg_cbkategori1.getValue();
                var kd_brg_cbkategori2 = this.getValue();
                brg_cbkategori3.setValue();
                brg_cbkategori4.setValue();
                brg_cbkategori3.store.proxy.conn.url = '<?= site_url("master_barang/get_kategori3") ?>/' + kd_brg_cbkategori1 +'/'+ kd_brg_cbkategori2;
                brg_cbkategori3.store.reload();
            }
        }
    });

    // combobox kategori3
    var str_brg_cbkategori3 = new Ext.data.Store({
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
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var brg_cbkategori3 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 3 <span class="asterix">*</span>',
        id: 'mb_brg_cbkategori3',
        mode: 'local',
        store: str_brg_cbkategori3,
        valueField: 'kd_kategori3',
        displayField: 'nama_kategori3',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        width: 250,
        anchor: '90%',
        hiddenName: 'nama_kategori3',
        emptyText: 'Pilih kategori 3',
        listeners: {
            select: function(combo, records) {
                var kd_brg_cbkategori1 = brg_cbkategori1.getValue();
                var kd_brg_cbkategori2 = brg_cbkategori2.getValue();
                var kd_brg_cbkategori3 = this.getValue();
                brg_cbkategori4.setValue();
                brg_cbkategori4.store.proxy.conn.url = '<?= site_url("master_barang/get_kategori4") ?>/' + kd_brg_cbkategori1 +'/'+ kd_brg_cbkategori2 +'/'+ kd_brg_cbkategori3;
                brg_cbkategori4.store.reload();
            }
        }
    });

    // combobox kategori4
    var str_brg_cbkategori4 = new Ext.data.Store({
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
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var brg_cbkategori4 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 4 <span class="asterix">*</span>',
        id: 'mb_brg_cbkategori4',
        mode: 'local',
        store: str_brg_cbkategori4,
        valueField: 'kd_kategori4',
        displayField: 'nama_kategori4',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        width: 250,
        anchor: '90%',
        hiddenName: 'nama_kategori4',
        emptyText: 'Pilih kategori 4',
        listeners: {
            select: function(combo, records) {
                var kd_produk = Ext.getCmp('mb_kd_produk').getValue();
                if(!kd_produk){
                    var kd_brg_cbkategori1 = brg_cbkategori1.getValue();
                    var kd_brg_cbkategori2 = brg_cbkategori2.getValue();
                    var kd_brg_cbkategori3 = brg_cbkategori3.getValue();
                    var kd_brg_cbkategori4 = brg_cbkategori4.getValue();
                    Ext.Ajax.request({
                        url: '<?= site_url("master_barang/get_parameter_margin") ?>',
                        method: 'POST',
                        params: {
                            kd_kategori1: kd_brg_cbkategori1,
                            kd_kategori2: kd_brg_cbkategori2,
                            kd_kategori3: kd_brg_cbkategori3,
                            kd_kategori4: kd_brg_cbkategori4
                        },
                        callback:function(opt,success,responseObj){
                            var de = Ext.util.JSON.decode(responseObj.responseText);
                            if(de.success==true){
                                Ext.getCmp('mb_margin').setValue(de.data.nilai_parameter);
                                Ext.getCmp('mb_margin_dist').setValue(de.data.nilai_parameter);
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
        }
    });

    // combobox satuan
    var strcbsatuan = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_satuan', 'nm_satuan'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_barang/get_satuan") ?>',
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

    var cbsatuan = new Ext.form.ComboBox({
		minChars: 1,
        fieldLabel: 'Satuan <span class="asterix">*</span>',
        id: 'mb_cbsatuan',
        store: strcbsatuan,
        valueField: 'kd_satuan',
        displayField: 'nm_satuan',
        forceSelection: true,
        triggerAction: 'all',
        allowBlank: false,
        anchor: '90%',
        width: 250,
        hiddenName: 'kd_satuan',
        emptyText: 'Pilih Satuan'
    });
    // combobox satuan berat
    var strcbsatuanberat = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_satuan', 'nm_satuan','kd_satuan_berat','nama_satuan_berat'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_barang/get_satuan_berat") ?>',
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

    var cbsatuanberat = new Ext.form.ComboBox({
		minChars: 1,
        fieldLabel: 'Satuan <span class="asterix">*</span>',
        id: 'mb_cbsatuan_berat',
        store: strcbsatuanberat,
        valueField: 'kd_satuan_berat',
        displayField: 'nama_satuan_berat',
        forceSelection: true,
        triggerAction: 'all',
        allowBlank: false,
        anchor: '90%',
        width: 250,
        hiddenName: 'kd_satuan_berat',
        emptyText: 'Pilih Satuan'
    });

    // combobox ukuran
    var strcbukuran = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_ukuran', 'nama_ukuran'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_barang/get_ukuran") ?>',
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

    var cbukuran = new Ext.form.ComboBox({
        // typeAhead: true,
        allowBlank: false,
        // editable: false,
		// queryMode: 'remote',
        minChars: 1,
        fieldLabel: 'Ukuran <span class="asterix">*</span>',
        id: 'mb_cbukuran',
        store: strcbukuran,
        valueField: 'kd_ukuran',
        displayField: 'nama_ukuran',
        forceSelection: true,
        allowBlank: false,
        triggerAction: 'all',
        anchor: '90%',
        width: 250,
        hiddenName: 'kd_ukuran',
        emptyText: 'Pilih Ukuran'
    });
    // combobox type lokasi
    var valcomboTypeLokasiMasterBarang = [
		['S', "Supermarket"],
		['G', "Gudang"]
	];
	var strtypelokasimasterbarang = new Ext.data.ArrayStore({
		fields: [{
			name: 'flag_lokasi'
		}, {
			name: 'type_lokasi'
		}],
		data: valcomboTypeLokasiMasterBarang
	});
	var comboTypeLokasiMasterBarang = new Ext.form.ComboBox({
		fieldLabel: 'Type Lokasi',
		id: 'id_comboTypeLokasiMasterBarang',
		name: 'flag_lokasi',
		// allowBlank:false,
		store: strtypelokasimasterbarang,
		valueField: 'flag_lokasi',
		displayField: 'type_lokasi',
		mode: 'local',
		typeAhead: true,
		triggerAction: 'all',
                editable: false,
		anchor: '40%',
                emptyText: 'Type Lokasi'
	});

    // combobox lokasi
    var strComboLokasiMasterBarang = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_lokasi', 'nama_lokasi'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("blok_lokasi/get_all") ?>',
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

    var comboLokasiMasterBarang = new Ext.form.ComboBox({
        fieldLabel: 'Nama Lokasi',
        id: 'id_combo_lokasi_mstbarang',
        store: strComboLokasiMasterBarang,
        valueField: 'kd_lokasi',
        displayField: 'nama_lokasi',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        anchor: '40%',
        hiddenName: 'kd_lokasi',
        emptyText: 'Pilih Lokasi',
        listeners: {

            select: function(combo, records) {
                var kd_lokasi = this.getValue();
                comboBlokMasterBarang.setValue();
                comboBlokMasterBarang.store.proxy.conn.url = '<?= site_url("sub_blok_lokasi/get_blok") ?>/' + kd_lokasi;
                comboBlokMasterBarang.store.reload();
            }
        }
    });

    // combobox blok
    var strComboBlokMasterBarang = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_blok', 'nama_blok'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("sub_blok_lokasi/get_blok") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var comboBlokMasterBarang = new Ext.form.ComboBox({
        fieldLabel: 'Nama Blok',
        id: 'id_combo_blok_mstbarang',
        mode: 'local',
        store: strComboBlokMasterBarang,
        valueField: 'kd_blok',
        displayField: 'nama_blok',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        anchor: '40%',
        hiddenName: 'kd_blok',
        emptyText: 'Pilih Blok',
        listeners: {
            select: function(combo, records) {
                var kd_lokasi = Ext.getCmp('id_combo_lokasi_mstbarang').getValue();
                var kd_blok = this.getValue();
                comboSubblokMasterBarang.setValue('');
                comboSubblokMasterBarang.store.proxy.conn.url = '<?= site_url("sub_blok_lokasi/get_sub_blok") ?>/' + kd_lokasi + '/' + kd_blok;
                comboSubblokMasterBarang.store.reload();
            }
        }
    });

    // combobox sub_blok
    var strComboSubblokMasterBarang = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_sub_blok', 'nama_sub_blok'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("sub_blok_lokasi/get_sub_blok") ?>',
            method: 'POST'
        })
    });

    var comboSubblokMasterBarang = new Ext.form.ComboBox({
        fieldLabel: 'Nama Sub Blok <span class="asterix">*</span>',
        id: 'id_combo_subblok_mstbarang',
        mode: 'local',
        store: strComboSubblokMasterBarang,
        valueField: 'kd_sub_blok',
        displayField: 'nama_sub_blok',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        allowBlank: false,
        anchor: '40%',
        hiddenName: 'kd_sub_blok',
        emptyText: 'Pilih Sub Blok'
    });

    Ext.ns('masterbarangform');
    masterbarangform.Form = Ext.extend(Ext.form.FormPanel, {

        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 140,
        url: '<?= site_url("master_barang/update_row") ?>',
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
            masterbarangform.Form.superclass.constructor.call(this, config);
        },
        initComponent: function(){

            // hard coded - cannot be changed from outsid
            var config = {
                defaults: { labelSeparator: '', value :0},
                monitorValid: true,
                //
                autoScroll: false,// ,buttonAlign:'right'
                items: [{
                        xtype: 'tabpanel',
                        height: 400,
                        activeTab: 0,
                        deferredRender: false,
                        items: [{
                                title:'Produk',
                                layout:'form',
                                items: [{
                                        layout: 'column',
                                        border: false,
                                        style:'padding:10px',
                                        items: [{
                                                columnWidth: 0.5,
                                                layout: 'form',
                                                border: false,
                                                defaultType: 'textfield',
                                                items:[]
                                        },{
                                                columnWidth: 0.5,
                                                layout: 'form',
                                                border: false,
                                                defaultType: 'textfield',
                                                items:[
                                                ]
                                            }]
                                    }]
                            }, {
                                title:'Diskon',
                                layout:'form',
                                style:'padding:10px',
                                items: []
                            }, {
                                title:'Diskon Member',
                                layout:'form',
                                style:'padding:10px',
                                defaultType: 'textfield',
                                items: []
                            }],
                        buttons: [{
                                text: 'Submit',
                                id: 'btnsubmitmasterbarang',
                                formBind: true,
                                scope: this,
                                handler: this.submit
                            }, {
                                text: 'Reset',
                                id: 'btnresetmasterbarang',
                                scope: this,
                                handler: this.reset
                            }, {
                                text: 'Close',
                                id: 'btnClose',
                                scope: this,
                                handler: function(){
                                    winaddmasterbarang.hide();
                                }
                            }]
                    }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));

            // call parent
            masterbarangform.Form.superclass.initComponent.apply(this, arguments);

        } // eo function initComponent
        ,
        onRender: function(){

            // call parent
            masterbarangform.Form.superclass.onRender.apply(this, arguments);

            // set wait message target
            this.getForm().waitMsgTarget = this.getEl();

            // loads form after initial layout
            // this.on('afterlayout', this.onLoadClick, this, {single:true});

        } // eo function onRender
        ,
        reset: function(){
            this.getForm().reset();
        },
        submit: function(){

            this.getForm().submit({
                url: this.url,
                scope: this,
                success: this.onSuccess,
                failure: this.onFailure,
                params: {
                    cmd: 'save'
                },
                waitMsg: 'Saving Data...'
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


            strmasterbarang.reload();
            Ext.getCmp('mb_formaddmasterbarang').getForm().reset();
            winaddmasterbarang.hide();
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
    Ext.reg('formaddmasterbarang', masterbarangform.Form);

    var winaddmasterbarang = new Ext.Window({
        id: 'mb_winaddmasterbarang',
        closeAction: 'hide',
        width: 800,
        height: 450,
        layout: 'fit',
        border: false,
        items: {
            id: 'mb_formaddmasterbarang',
            xtype: 'formaddmasterbarang'
        },
        onHide: function(){
            Ext.getCmp('mb_formaddmasterbarang').getForm().reset();
        }
    });

    /* START GRID */
    var strmasterbarang = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_kategori1',
                'kd_kategori2',
                'kd_kategori3',
                'kd_kategori4',
                'thn_reg',
                'no_urut',
                'nama_produk',
                'kd_produk',
                'kd_produk_lama',
                'kd_produk_supp',
                'kd_peruntukkan',
                'hrg_beli_sup',
                'hrg_beli_dist',
                'min_stok',
                'max_stok',
                'min_order',
                'kd_satuan',
                'kd_ukuran',
                'is_konsinyasi',
                'rp_margin',
                'rp_ongkos_kirim',
                'pct_margin',
                'rp_jual_supermarket',
                'rp_jual_distribusi',
                'rp_het_harga_beli',
                'koreksi_ke'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_barang/get_rows") ?>',
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

    function hitungHargaJualSup(){
        var hrg_beli = Ext.getCmp('mb_net_hrg_supplier_sup_exc').getValue();
        var cogs_sup = Ext.getCmp('mb_rp_cogs').getValue();
        var ongkos = Ext.getCmp('mb_rp_ongkos_kirim').getValue();
        var margin_op = Ext.getCmp('mb_margin_op').getValue();
        var margin = Ext.getCmp('mb_margin').getValue();

        if (margin_op === 'persen'){
            var rp_margin = (hrg_beli*margin)/100;
            var rp_margin_cogs = (cogs_sup*margin)/100;

            Ext.getCmp('mb_rp_margin').setValue(rp_margin);
            Ext.getCmp('mb_rp_margin_cogs').setValue(rp_margin_cogs);
        }
        else {
            var rp_margin = margin;
            Ext.getCmp('mb_rp_margin').setValue(rp_margin);
            Ext.getCmp('mb_rp_margin_cogs').setValue(rp_margin);
        }

        Ext.getCmp('mb_rp_het_harga_beli').setValue(hrg_beli+rp_margin+ongkos);
        Ext.getCmp('mb_rp_het_harga_beli_inc').setValue((hrg_beli+rp_margin+ongkos)*1.1);
        Ext.getCmp('mb_net_hrg_supplier_sup_inc').setValue((hrg_beli+rp_margin+ongkos)*1.1);
        Ext.getCmp('mb_rp_het_cogs').setValue(cogs_sup+rp_margin_cogs+ongkos);
    };

    function hitungHargaJualDist(){
        var hrg_beli_dist = Ext.getCmp('mb_hrg_beli_dist').getValue();
        var cogs_sup_dist = Ext.getCmp('mb_rp_cogs_dist').getValue();
        var ongkos_dist = Ext.getCmp('mb_rp_ongkos_kirim').getValue();
        var margin_op_dist = Ext.getCmp('mb_margin_op_dist').getValue();
        var margin_dist = Ext.getCmp('mb_margin_dist').getValue();

        if (margin_op_dist === 'persen'){
            var rp_margin_dist = (hrg_beli_dist*margin_dist)/100;
            var rp_margin_cogs_dist = (cogs_sup_dist*margin_dist)/100;

            Ext.getCmp('mb_rp_margin_dist').setValue(rp_margin_dist);
            Ext.getCmp('mb_rp_margin_dist_cogs').setValue(rp_margin_cogs_dist);
        }
        else {
            var rp_margin_dist = margin_dist;
            Ext.getCmp('mb_rp_margin_dist').setValue(rp_margin_dist);
            Ext.getCmp('mb_rp_margin_dist_cogs').setValue(rp_margin_dist);
        }

        Ext.getCmp('mb_rp_het_harga_beli_dist').setValue(hrg_beli_dist+rp_margin_dist+ongkos_dist);
        Ext.getCmp('mb_rp_het_cogs_dist').setValue(cogs_sup_dist+rp_margin_cogs_dist+ongkos_dist);
    };

    //form
    var masterbarang = new Ext.FormPanel({
        id: 'masterbarang',
        border: false,
        frame: true,
        autoScroll:true,
        bodyStyle:'padding-right:20px;',
        labelWidth: 100,
        items: [ { 	xtype:'fieldset',
                autoheight: true,
                anchor: '90%',
                items:[
                    {
                        xtype: 'hidden',
                        name: 'kd_diskon_sales',
                        id: 'mb_kd_diskon_sales'
                    },{
                        xtype: 'hidden',
                        name: 'koreksi_ke',
                        id: 'mb_koreksi_ke'
                    },{
                        xtype : 'compositefield',
                        anchor: '90%',
                        msgTarget: 'side',
                        fieldLabel: 'Kode Produk',
                        width: 400,
                        items : [
                            new Ext.ux.TwinCombo({
                                id: 'mb_kd_produk',
                                store: strcbkdprodukmb,
                                valueField: 'kd_produk',
                                displayField: 'kd_produk',
                                typeAhead: true,
                                editable: false,
                                hiddenName: 'kd_produk',
                                emptyText: 'Pilih Kode Produk',
                                listeners:{
                                    'expand': function(){
                                        strcbkdprodukmb.load();
                                    }
                                }
                            }),{
                                xtype: 'displayfield',
                                value: 'No Urut',
                                style: 'padding-left:30px',
                                width: 100
                            },{
                                xtype: 'numberfield',
                                name: 'no_urut',
                                id: 'mb_no_urut',
                                readOnly: true,
                                maxLength: 11,
                                style: 'text-align:right;',
                                fieldClass:'readonly-input number',
                                value: 0,
                                width: 70,
                                anchor: '90%'
                            },{
                                xtype: 'displayfield',
                                value: 'Tgl Pembuatan',
                                style: 'padding-left:30px',
                                width: 115
                            },{
                                xtype: 'datefield',
                                name: 'created_date',
                                id: 'mb_created_date',
                                labelStyle:'width:200px',
                                readOnly: true,
                                format: 'd-M-Y',
                                fieldClass:'readonly-input number',
                                width: 170,
                                anchor: '90%'
                            }
                        ]
                    }
                ]
            }, {
                xtype:'fieldset',
                autoheight: true,
                title: 'Produk',
                anchor: '90%',
                items:[ {xtype : 'compositefield',
                        anchor: '90%',
                        msgTarget: 'side',
                        fieldLabel: 'Nama Produk',
                        items:[cbNamaProduk]
                    },{xtype : 'compositefield',
                        anchor: '90%',
                        msgTarget: 'side',
                        fieldLabel: 'Tonaliti',
                        items:[{
                                xtype: 'textfield',
                                name: 'tonaliti',
                                id: 'mb_tonaliti',
                                style:'text-transform: uppercase',
                                allowBlank: true,
                                maxLength: 6,
                                value:'',
                                width: 250,
                                anchor: '90%'
                            }]
                    },{xtype : 'compositefield',
                        anchor: '90%',
                        msgTarget: 'side',
                        fieldLabel: 'Kategori 1',
                        items : [brg_cbkategori1, {
                                xtype: 'displayfield',
                                value: 'Kd Produk Lama <span class="asterix">*</span>',
                                style: 'padding-left:30px',
                                width: 130
                        },{
                                xtype: 'textfield',
                                name: 'kd_produk_lama',
                                id: 'mb_kd_produk_lama',
                                style:'text-transform: uppercase',
                                allowBlank: false,
                                maxLength: 40,
                                value:'-',
                                width: 250,
                                anchor: '90%'
                            }]
                    },{xtype : 'compositefield',
                        msgTarget: 'side',
                        fieldLabel: 'Kategori 2',
                        items : [brg_cbkategori2,{
                                xtype: 'displayfield',
                                value: 'Kd Produk Supplier <span class="asterix">*</span>',
                                style: 'padding-left:30px',
                                width: 130
                        },{
                                xtype: 'textfield',
                                name: 'kd_produk_supp',
                                id: 'mb_kd_produk_supp',
                                style:'text-transform: uppercase',
                                allowBlank: false,
                                maxLength: 40,
                                value: '-',
                                width: 250,
                                anchor: '90%'
                            }]
                    },{xtype : 'compositefield',
                        anchor: '90%',
                        msgTarget: 'side',
                        fieldLabel: 'Kategori 3',
                        items : [brg_cbkategori3,
                            {
                                xtype: 'displayfield',
                                value: 'Satuan <span class="asterix">*</span>',
                                width: 130,
                                style: 'padding-left:30px'

                            },cbsatuan]
                    },{xtype : 'compositefield',
                        anchor: '90%',
                        msgTarget: 'side',
                        fieldLabel: 'Kategori 4',
                        items : [brg_cbkategori4,
                            {
                                xtype: 'displayfield',
                                value: 'Ukuran <span class="asterix">*</span>',
                                width: 130,
                                style: 'padding-left:30px'
                            },cbukuran]
                    },{xtype : 'compositefield',
                        anchor: '90%',
                        msgTarget: 'side',
                        fieldLabel: 'Peruntukkan',
                        items : [ {
                                xtype: 'radiogroup',
                                fieldLabel: 'Peruntukkan <span class="asterix">*</span>',
                                columnWidth: [.5, .5],
                                name: 'kd_peruntukkan',
                                width: 250,
                                anchor: '90%',
                                allowBlank:false,
                                items: [{
                                        boxLabel: 'Supermarket',
                                        name: 'kd_peruntukkan',
                                        id: 'mb_kd_peruntukkanS',
                                        inputValue: '0',
                                        checked: true
                                }, {
                                        boxLabel: 'Distribusi',
                                        name: 'kd_peruntukkan',
                                        inputValue: '1',
                                        id: 'mb_kd_peruntukkanD'
                                    }]
                            },{
                                xtype: 'displayfield',
                                value: 'Min Stok',
                                style: 'padding-left:30px',
                                width: 130
                        },{
                                xtype: 'numberfield',
                                fieldLabel: 'Min Stok',
                                name: 'min_stok',
                                readOnly: true,
                                id: 'mb_min_stok',
                                maxLength: 11,
                                style: 'text-align:right;',
                                fieldClass:'readonly-input number',
                                value: 0,
                                width: 250,
                                anchor: '90%'
                            }]
                    },{xtype : 'compositefield',
                        anchor: '90%',
                        msgTarget: 'side',
                        fieldLabel: 'Konsinyasi',
                        items : [ new Ext.form.Checkbox({
                                xtype: 'checkbox',
                                boxLabel:'Ya',
                                name:'is_konsinyasi',
                                id:'mb_is_konsinyasi_brg',
                                inputValue: '1',
                                autoLoad : true,
                                width: 250
                            }),{
                                xtype: 'displayfield',
                                value: 'Max Stok',
                                style: 'padding-left:30px',
                                width: 130
                        },{
                                xtype: 'numberfield',
                                name: 'max_stok',
                                readOnly: true,
                                id: 'mb_max_stok',
                                maxLength: 11,
                                style: 'text-align:right;',
                                fieldClass:'readonly-input number',
                                value: 0,
                                width: 250,
                                anchor: '90%'
                            }]
                    },{xtype : 'compositefield',
                        anchor: '90%',
                        msgTarget: 'side',
                        fieldLabel: 'Status Purchase',
                        items : [ new Ext.form.Checkbox({
                                xtype: 'checkbox',
                                boxLabel:'Ya',
                                name:'aktif_purchase',
                                id:'mb_aktif_purchase',
                                inputValue: '1',
                                autoLoad : true,
                                checked: true,
                                width: 250
                            }),{
                                xtype: 'displayfield',
                                value: 'Minimal Order',
                                style: 'padding-left:30px',
                                width: 130
                        },{
                                xtype: 'numberfield',
                                name: 'min_order',
                                id: 'mb_min_order',
                                style: 'text-align:right;',
                                readOnly: true,
                                fieldClass:'readonly-input number',
                                value: 0,
                                maxLength: 11,
                                width: 250,
                                anchor: '90%'
                            }]
                    },{xtype : 'compositefield',
                        anchor: '90%',
                        msgTarget: 'side',
                        fieldLabel: 'Status Aktif',
                        items : [ new Ext.form.Checkbox({
                                xtype: 'checkbox',
                                boxLabel:'Ya',
                                name:'aktif',
                                id:'mb_aktif',
                                inputValue: '1',
                                autoLoad : true,
                                checked: true,
                                width: 250
                            }),{
                                xtype: 'displayfield',
                                value: 'Alert (%)',
                                style: 'padding-left:30px',
                                width: 130
                        },{
                                xtype: 'numberfield',
                                name: 'pct_alert',
                                readOnly: true,
                                id: 'mb_pct_alert',
                                style: 'text-align:right;',
                                fieldClass:'readonly-input number',
                                value: 0,
                                maxLength: 11,
                                width: 250,
                                anchor: '90%'
                            }]
                    }, {
                        xtype : 'compositefield',
                        anchor: '90%',
                        msgTarget: 'side',
                        fieldLabel: 'Barang Paket',
                        items : [
                            new Ext.form.Checkbox({
                                xtype: 'checkbox',
                                boxLabel:'Ya',
                                name:'is_barang_paket',
                                id:'mb_is_barang_paket',
                                inputValue: '1',
                                autoLoad : true,
                                width: 250
                            }),{
                                xtype: 'displayfield',
                                value: 'TOP',
                                style: 'padding-left:30px',
                                width: 130
                            },{
                                xtype: 'textfield',
                                name: 'waktu_top',
                                readOnly: true,
                                id: 'mb_waktu_top',
                                style: 'text-align:right;',
                                fieldClass:'readonly-input',
                                value: 0,
                                width: 250,
                                anchor: '90%'
                            }
                        ]
                    },  {
                        xtype : 'compositefield',
                        anchor: '90%',
                        msgTarget: 'side',
                        fieldLabel: 'Nilai Berat <span class="asterix">*</span>',
                        items : [{
                                xtype: 'numberfield',
                                name: 'nilai_berat',
                                id: 'mb_nilai_berat',
                                allowBlank: false,
                                width: 250
                                },
                           {
                                xtype: 'displayfield',
                                value: 'Satuan Berat <span class="asterix">*</span>',
                                width: 130,
                                style: 'padding-left:30px'

                            },cbsatuanberat
                        ]
                    }, {
                        xtype : 'compositefield',
                        anchor: '90%',
                        msgTarget: 'side',
                        fieldLabel: 'Harga Lepas',
                        items : [
                            new Ext.form.Checkbox({
                                xtype: 'checkbox',
                                boxLabel:'Ya',
                                name:'is_harga_lepas',
                                id:'mb_is_harga_lepas',
                                inputValue: '1',
                                autoLoad : true,
                                checked: false,
                                width: 250
                            }), {
                                xtype: 'displayfield',
                                value: 'Keterangan Perubahan',
                                style: 'padding-left:30px',
                                width: 130
                            }, {
                                xtype: 'textarea',
                                allowBlank: false,
                                name:'ket_perubahan',
                                id:'mb_ket_perubahan',
                                style:'text-transform: uppercase',
                                width: 250
                            }
                        ]
                    }]
            }, {
                xtype:'fieldset',
                autoheight: true,
                title: 'Lokasi Default',
                anchor: '90%',
                items: [comboLokasiMasterBarang, comboBlokMasterBarang, comboSubblokMasterBarang, comboTypeLokasiMasterBarang]
            },{
                xtype:'fieldset',
                autoheight: true,
                collapsed: false,
                collapsible: true,
                title: 'Harga',
                anchor: '90%',
                items:[ {
                        xtype : 'compositefield',
                        msgTarget: 'side',
                        fieldLabel: 'Supermarket',
                        labelStyle:'width:200px',
                        width:700,
                        items : [ {
                                xtype: 'displayfield',
                                //value: 'Distribusi:',
                                hideLabel:true,
                                width: 150
                        },{
                                xtype: 'displayfield',
                                value: 'Distribusi:',
                                style: 'padding-left:30px',
                                width: 200
                        }]
                    },{
                        xtype : 'compositefield',
                        msgTarget: 'side',
                        fieldLabel: 'Net Harga Beli (Inc. PPN)',
                        labelStyle:'width:200px',
                        width:700,
                        items : [ {
                                xtype: 'numericfield',
                                currencySymbol:'',
                                name: 'net_hrg_supplier_sup_inc',
                                id: 'mb_net_hrg_supplier_sup_inc',
                                maxLength: 11,
                                style: 'text-align:right;',
                                fieldClass: 'readonly-input',
                                readOnly: true,
                                value: 0,
                                width: 150
                            //anchor: '90%',
                            },{
                                xtype: 'displayfield',
                                value: 'Net Harga Beli (Inc. PPN):',
                                style: 'padding-left:30px',
                                width: 230
                        },{
                                xtype: 'numericfield',
                                currencySymbol:'',
                                name: 'net_hrg_supplier_dist_inc',
                                id: 'mb_net_hrg_supplier_dist_inc',
                                maxLength: 11,
                                style: 'text-align:right;',
                                fieldClass: 'readonly-input',
                                readOnly: true,
                                value: 0,
                                width: 150
                            //anchor: '90%',
                            }
                        ]
                    }, {
                        xtype : 'compositefield',
                        msgTarget: 'side',
                        fieldLabel: 'Net Harga Beli (Exc. PPN)',
                        labelStyle:'width:200px',
                        width:700,
                        items : [ {
                                xtype: 'numericfield',
                                currencySymbol:'',
                                name: 'net_hrg_supplier_sup_exc',
                                id: 'mb_net_hrg_supplier_sup_exc',
                                maxLength: 11,
                                style: 'text-align:right;',
                                fieldClass: 'readonly-input',
                                readOnly: true,
                                value: 0,
                                width: 150
                            //anchor: '90%',
                            },{
                                xtype: 'displayfield',
                                value: 'Net Harga Beli (Exc. PPN):',
                                style: 'padding-left:30px',
                                width: 230
                        },{
                                xtype: 'numericfield',
                                currencySymbol:'',
                                name: 'hrg_beli_dist',
                                id: 'mb_hrg_beli_dist',
                                maxLength: 11,
                                style: 'text-align:right;',
                                fieldClass: 'readonly-input',
                                readOnly: true,
                                value: 0,
                                width: 150
                            //anchor: '90%',
                            }
                        ]
                    }, {
                        xtype : 'compositefield',
                        msgTarget: 'side',
                        fieldLabel: 'Margin',
                        labelStyle:'width:200px',
                        width:700,
                        items : [ {
                                xtype : 'compositefield',
                                msgTarget: 'side',
                                width: 170,
                                items : [{
                                        xtype : 'compositefield',
                                        msgTarget: 'side',
                                        //width:200,
                                        items : [{
                                                xtype: 'numberfield',
                                                // flex:1,
                                                width:30,
                                                name : 'margin',
                                                id: 'mb_margin',
                                                style: 'text-align:right;',
                                                value: 0,
                                                fieldClass: 'readonly-input',
                                                readOnly: true,
                                                listeners:{
                                                    'render': function(c) {
                                                        c.getEl().on('keyup', function() {
                                                            hitungHargaJualSup();
                                                        }, c);
                                                    }
                                                }
                                            },{
                                                xtype: 'displayfield',
                                                value: '%',
                                                width: 15
                                            },{
                                                xtype: 'numericfield',
                                                currencySymbol:'',
                                                name: 'rp_margin',
                                                id: 'mb_rp_margin',
                                                maxLength: 11,
                                                fieldClass: 'readonly-input',
                                                readOnly: true,
                                                style: 'text-align:right;',
                                                value: 0,
                                                width: 95
                                            //anchor: '90%'
                                            }
                                        ]
                                    }]
                            },{
                                xtype: 'displayfield',
                                value: 'Margin :',
                                style: 'padding-left:10px',
                                width: 210
                        },{
                                xtype : 'compositefield',
                                msgTarget: 'side',
                                width: 300,
                                items : [{
                                        xtype : 'compositefield',
                                        msgTarget: 'side',
                                        //width:200,
                                        items : [{
                                                xtype: 'numberfield',
                                                flex:1,
                                                width:30,
                                                fieldClass: 'readonly-input',
                                                readOnly: true,
                                                name : 'margin_dist',
                                                id: 'mb_margin_dist',
                                                style: 'text-align:right;',
                                                value: 0,
                                                listeners:{
                                                    'render': function(c) {
                                                        c.getEl().on('keyup', function() {
                                                            hitungHargaJualDist();
                                                        }, c);
                                                    }
                                                }
                                            },{
                                                xtype: 'displayfield',
                                                value: '%',
                                                width: 15
                                            },{
                                                xtype: 'numericfield',
                                                currencySymbol:'',
                                                name: 'rp_margin_dist',
                                                id: 'mb_rp_margin_dist',
                                                maxLength: 11,
                                                fieldClass: 'readonly-input',
                                                readOnly: true,
                                                style: 'text-align:right;',
                                                value: 0,
                                                width: 95
                                            //anchor: '90%'
                                            }]
                                    }]
                            }]
                    },
                    {
                        xtype : 'compositefield',
                        msgTarget: 'side',
                        fieldLabel: 'Ongkos Kirim',
                        labelStyle:'width:200px',
                        width:700,
                        items : [{
                                xtype: 'numericfield',
                                currencySymbol:'',
                                name: 'rp_ongkos_kirim',
                                id: 'mb_rp_ongkos_kirim',
                                maxLength: 11,
                                style: 'text-align:right;',
                                value: 0,
                                width: 150,
                                fieldClass: 'readonly-input',
                                readOnly: true,
                                //anchor: '90%',
                                listeners:{
                                    'render': function(c) {
                                        c.getEl().on('keyup', function() {
                                            hitungHargaJualSup();
                                        }, c);
                                    }
                                }
                            },{
                                xtype: 'displayfield',
                                value: 'Ongkos Kirim :',
                                style: 'padding-left:30px',
                                width: 230
                        },{
                                xtype: 'numericfield',
                                currencySymbol:'',
                                name: 'rp_ongkos_kirim_dist',
                                id: 'mb_rp_ongkos_kirim_dist',
                                maxLength: 11,
                                style: 'text-align:right;',
                                value: 0,
                                width: 150,
                                fieldClass: 'readonly-input',
                                readOnly: true,
                                //anchor: '90%',
                                listeners:{
                                    'render': function(c) {
                                        c.getEl().on('keyup', function() {
                                            hitungHargaJualDist();
                                        }, c);
                                    }
                                }
                            }]
                    },{
                        xtype : 'compositefield',
                        msgTarget: 'side',
                        fieldLabel: '<b>HET Net Price Beli (Exc. PPN)</b>',
                        labelStyle:'width:200px',
                        width:700,
                        items : [ {
                                xtype: 'numericfield',
                                currencySymbol:'',
                                name: 'rp_het_harga_beli',
                                id: 'mb_rp_het_harga_beli',
                                maxLength: 11,
                                style: 'text-align:right;',
                                readOnly: true,
                                fieldClass:'readonly-input number bold-input',
                                value: 0,
                                width: 150
                            //anchor: '90%'
                            },{
                                xtype: 'displayfield',
                                value: '<b>HET Net Price Beli (Exc. PPN):</b>',
                                style: 'padding-left:30px',
                                width: 230
                        },{
                                xtype: 'numericfield',
                                currencySymbol:'',
                                name: 'rp_het_harga_beli_dist',
                                id: 'mb_rp_het_harga_beli_dist',
                                readOnly: true,
                                fieldClass:'readonly-input number bold-input',
                                maxLength: 11,
                                style: 'text-align:right;',
                                value: 0,
                                width: 150
                            //anchor: '90%'
                            }]
                    },{
                        xtype : 'compositefield',
                        msgTarget: 'side',
                        fieldLabel: '<b>HET Net Price Beli (Inc. PPN)</b>',
                        labelStyle:'width:200px',
                        width:700,
                        items : [ {
                                xtype: 'numericfield',
                                currencySymbol:'',
                                name: 'rp_het_harga_beli_inc',
                                id: 'mb_rp_het_harga_beli_inc',
                                maxLength: 11,
                                style: 'text-align:right;',
                                readOnly: true,
                                fieldClass:'readonly-input number bold-input',
                                value: 0,
                                width: 150
                            //anchor: '90%'
                            },{
                                xtype: 'displayfield',
                                value: '<b>HET Net Price Beli (Inc. PPN):</b>',
                                style: 'padding-left:30px',
                                width: 230
                        },{
                                xtype: 'numericfield',
                                currencySymbol:'',
                                name: 'rp_het_harga_beli_dist_inc',
                                id: 'mb_rp_het_harga_beli_dist_inc',
                                readOnly: true,
                                fieldClass:'readonly-input number bold-input',
                                maxLength: 11,
                                style: 'text-align:right;',
                                value: 0,
                                width: 150
                            //anchor: '90%'
                            }]
                    },{
                        xtype : 'compositefield',
                        msgTarget: 'side',
                        fieldLabel: 'COGS(Exc. PPN)',
                        labelStyle:'width:200px',
                        width:700,
                        items : [ {
                                xtype: 'numericfield',
                                currencySymbol:'',
                                name: 'rp_cogs',
                                id: 'mb_rp_cogs',
                                maxLength: 11,
                                style: 'text-align:right;',
                                fieldClass: 'readonly-input',
                                readOnly: true,
                                value: 0,
                                width: 150
                            //anchor: '90%',
                            },{
                                xtype: 'displayfield',
                                value: 'COGS(Exc. PPn):',
                                style: 'padding-left:30px',
                                width: 230
                        },{
                                xtype: 'numericfield',
                                currencySymbol:'',
                                name: 'rp_cogs_dist',
                                id: 'mb_rp_cogs_dist',
                                maxLength: 11,
                                style: 'text-align:right;',
                                fieldClass: 'readonly-input',
                                readOnly: true,
                                value: 0,
                                width: 150
                            //anchor: '90%',
                            }
                        ]
                    }, {
                        xtype : 'compositefield',
                        msgTarget: 'side',
                        fieldLabel: 'Margin COGS',
                        labelStyle:'width:200px',
                        width:700,
                        items : [ {
                                xtype : 'compositefield',
                                msgTarget: 'side',
                                width: 170,
                                items : [{
                                        xtype : 'compositefield',
                                        msgTarget: 'side',
                                        //width:200,
                                        items : [{
                                                xtype: 'numberfield',
                                                // flex:1,
                                                width:30,
                                                name : 'margin_cogs',
                                                id: 'mb_margin_cogs',
                                                style: 'text-align:right;',
                                                value: 0,
                                                fieldClass: 'readonly-input',
                                                readOnly: true
                                            /*listeners:{
                                                                                                                'render': function(c) {
                                                                                                                  c.getEl().on('keyup', function() {
                                                                                                                        hitungHargaJualSup();
                                                                                                                  }, c);
                                                                                                                }
                                                                                                        }*/
                                            },{
                                                xtype: 'displayfield',
                                                value: '%',
                                                width: 15
                                            },{
                                                xtype: 'numericfield',
                                                currencySymbol:'',
                                                name: 'rp_margin_cogs',
                                                id: 'mb_rp_margin_cogs',
                                                maxLength: 11,
                                                fieldClass: 'readonly-input',
                                                readOnly: true,
                                                style: 'text-align:right;',
                                                value: 0,
                                                width: 95
                                            //anchor: '90%'
                                            }
                                        ]
                                    }]
                            },{
                                xtype: 'displayfield',
                                value: 'Margin COGS:',
                                style: 'padding-left:10px',
                                width: 210
                        },{
                                xtype : 'compositefield',
                                msgTarget: 'side',
                                width: 300,
                                items : [{
                                        xtype : 'compositefield',
                                        msgTarget: 'side',
                                        //width:200,
                                        items : [{
                                                xtype: 'numberfield',
                                                flex:1,
                                                width:30,
                                                fieldClass: 'readonly-input',
                                                readOnly: true,
                                                name : 'margin_dist_cogs',
                                                id: 'mb_margin_dist_cogs',
                                                style: 'text-align:right;',
                                                value: 0
                                            /*listeners:{
                                                                                                                'render': function(c) {
                                                                                                                  c.getEl().on('keyup', function() {
                                                                                                                        hitungHargaJualDist();
                                                                                                                  }, c);
                                                                                                                }
                                                                                                        }*/
                                            },{
                                                xtype: 'displayfield',
                                                value: '%',
                                                width: 15
                                            },{
                                                xtype: 'numericfield',
                                                currencySymbol:'',
                                                name: 'rp_margin_dist_cogs',
                                                id: 'mb_rp_margin_dist_cogs',
                                                maxLength: 11,
                                                fieldClass: 'readonly-input',
                                                readOnly: true,
                                                style: 'text-align:right;',
                                                value: 0,
                                                width: 95
                                            //anchor: '90%'
                                            }]
                                    }]
                            }]
                    }, {
                        xtype : 'compositefield',
                        msgTarget: 'side',
                        fieldLabel: 'Ongkos Kirim',
                        labelStyle:'width:200px',
                        width:700,
                        items : [{
                                xtype: 'numericfield',
                                currencySymbol:'',
                                name: 'rp_ongkos_kirim_cogs',
                                id: 'mb_rp_ongkos_kirim_cogs',
                                maxLength: 11,
                                style: 'text-align:right;',
                                value: 0,
                                width: 150,
                                fieldClass: 'readonly-input',
                                readOnly: true
                            //anchor: '90%',
                                /*listeners:{
                                                                                        'render': function(c) {
                                                                                          c.getEl().on('keyup', function() {
                                                                                                hitungHargaJualSup();
                                                                                          }, c);
                                                                                        }
                                                                                } */
                            },{
                                xtype: 'displayfield',
                                value: 'Ongkos Kirim :',
                                style: 'padding-left:30px',
                                width: 230
                        },{
                                xtype: 'numericfield',
                                currencySymbol:'',
                                name: 'rp_ongkos_kirim_dist_cogs',
                                id: 'mb_rp_ongkos_kirim_dist_cogs',
                                maxLength: 11,
                                style: 'text-align:right;',
                                value: 0,
                                width: 150,
                                fieldClass: 'readonly-input',
                                readOnly: true
                            //anchor: '90%',
                                /*listeners:{
                                                                                        'render': function(c) {
                                                                                          c.getEl().on('keyup', function() {
                                                                                                hitungHargaJualDist();
                                                                                          }, c);
                                                                                        }
                                                                                }*/
                            }]
                    }, {
                        xtype : 'compositefield',
                        msgTarget: 'side',
                        fieldLabel: '<b>HET COGS (Exc. PPN)</b>',
                        labelStyle:'width:200px',
                        width:700,
                        items : [ {
                                xtype: 'numericfield',
                                currencySymbol:'',
                                name: 'rp_het_cogs',
                                id: 'mb_rp_het_cogs',
                                maxLength: 11,
                                style: 'text-align:right;',
                                readOnly: true,
                                fieldClass:'readonly-input number bold-input',
                                value: 0,
                                width: 150
                            //anchor: '90%'
                            },{
                                xtype: 'displayfield',
                                value: '<b>HET COGS (Exc. PPN):</b>',
                                style: 'padding-left:30px',
                                width: 230
                        },{
                                xtype: 'numericfield',
                                currencySymbol:'',
                                name: 'rp_het_cogs_dist',
                                id: 'mb_rp_het_cogs_dist',
                                maxLength: 11,
                                style: 'text-align:right;',
                                readOnly: true,
                                fieldClass:'readonly-input number bold-input',
                                value: 0,
                                width: 150
                            //anchor: '90%'
                            }]
                    },  {
                        xtype : 'compositefield',
                        msgTarget: 'side',
                        fieldLabel: '<b>HET COGS (Inc. PPN)</b>',
                        labelStyle:'width:200px',
                        width:700,
                        items : [ {
                                xtype: 'numericfield',
                                currencySymbol:'',
                                name: 'rp_het_cogs_inc',
                                id: 'mb_rp_het_cogs_inc',
                                maxLength: 11,
                                style: 'text-align:right;',
                                readOnly: true,
                                fieldClass:'readonly-input number bold-input',
                                value: 0,
                                width: 150
                            //anchor: '90%'
                            },{
                                xtype: 'displayfield',
                                value: '<b>HET COGS (Inc. PPN):</b>',
                                style: 'padding-left:30px',
                                width: 230
                        },{
                                xtype: 'numericfield',
                                currencySymbol:'',
                                name: 'rp_het_cogs_dist_inc',
                                id: 'mb_rp_het_cogs_dist_inc',
                                maxLength: 11,
                                style: 'text-align:right;',
                                readOnly: true,
                                fieldClass:'readonly-input number bold-input',
                                value: 0,
                                width: 150
                            //anchor: '90%'
                            }]
                    },{
                        xtype : 'compositefield',
                        msgTarget: 'side',
                        fieldLabel: 'Harga Jual Supermarket',
                        labelStyle:'width:200px',
                        width:700,
                        items : [ {
                                xtype: 'numericfield',
                                currencySymbol:'',
                                name: 'rp_jual_supermarket',
                                id: 'mb_rp_jual_supermarket',
                                maxLength: 11,
                                style: 'text-align:right;',
                                value: 0,
                                width: 150,
                                fieldClass: 'readonly-input',
                                readOnly: true
                            //anchor: '90%'
                            },{
                                xtype: 'displayfield',
                                value: 'Harga Jual Distribusi:',
                                style: 'padding-left:30px',
                                width: 230
                        },{
                                xtype: 'numericfield',
                                currencySymbol:'',
                                name: 'rp_jual_distribusi',
                                id: 'mb_rp_jual_distribusi',
                                maxLength: 11,
                                style: 'text-align:right;',
                                value: 0,
                                width: 150,
                                fieldClass: 'readonly-input',
                                readOnly: true
                            //anchor: '90%'
                            }]
                    }, {
                        xtype : 'compositefield',
                        msgTarget: 'side',
                        fieldLabel: 'Net Price Jual ke Konsumen',
                        labelStyle:'width:200px',
                        width:700,
                        items : [ {
                                xtype: 'numericfield',
                                currencySymbol:'',
                                name: 'rp_jual_supermarket_net',
                                id: 'mb_rp_jual_supermarket_net',
                                maxLength: 11,
                                style: 'text-align:right;',
                                value: 0,
                                width: 150,
                                fieldClass: 'readonly-input',
                                readOnly: true
                            //anchor: '90%'
                            },{
                                xtype: 'displayfield',
                                value: 'Net Price Jual Distribusi:',
                                style: 'padding-left:30px',
                                width: 230
                        },{
                                xtype: 'numericfield',
                                currencySymbol:'',
                                name: 'rp_jual_distribusi_net',
                                id: 'mb_rp_jual_distribusi_net',
                                maxLength: 11,
                                style: 'text-align:right;',
                                value: 0,
                                width: 150,
                                fieldClass: 'readonly-input',
                                readOnly: true
                            //anchor: '90%'
                            }]
                    }, {
                        xtype : 'compositefield',
                        msgTarget: 'side',
                        fieldLabel: 'Net Price Jual ke Member',
                        labelStyle:'width:200px',
                        width:700,
                        items : [ {
                                xtype: 'numericfield',
                                currencySymbol:'',
                                name: 'rp_jual_supermarket_member_net',
                                id: 'mb_rp_jual_supermarket_member_net',
                                maxLength: 11,
                                style: 'text-align:right;',
                                value: 0,
                                width: 150,
                                fieldClass: 'readonly-input',
                                readOnly: true
                            //anchor: '90%'
                            }]
                    }
                ]},{
                xtype: 'tabpanel',
                height: 450,
                activeTab: 0,
                deferredRender: false,
                items: [gridmbhisto, gridmbhistoinv, gridmbhistocogs, gridmbhistocogsdist]
            },
            {xtype: 'hidden',
                name: 'gridSender',
                id:'mb_gridSender'
            },{
                xtype:'fieldset',
                autoheight: true,
                title: 'Diskon',
                collapsed: false,
                collapsible: true,
                anchor: '90%',
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
                                        value:          'persen',
                                        triggerAction:  'all',
                                        forceSelection: true,
                                        readOnly: true,
                                        fieldClass: 'readonly-input',
                                        editable:       false,
                                        name:           'disk_kons1_op',
                                        id:           	'mb_disk_kons1_op',
                                        hiddenName:     'disk_kons1_op',
                                        displayField:   'name',
                                        valueField:     'value',
                                        width:	50,
                                        store:          new Ext.data.JsonStore({
                                            fields : ['name', 'value'],
                                            data   : [
                                                {name : '%',   value: 'persen'},
                                                {name : 'Rp',  value: 'amount'}
                                            ]
                                        }),
                                        listeners:{
                                            select:function(){
                                                Ext.getCmp('mb_disk_kons1').setMaxValue(Number.MAX_VALUE);
                                                if (this.getValue() == 'persen')
                                                    Ext.getCmp('mb_disk_kons1').maxValue = 100;
                                                else Ext.getCmp('mb_disk_kons1').maxLength = 11;
                                            }
                                        }
                                    },{
                                        xtype: 'numberfield',
                                        flex:1,
                                        width:115,
                                        name : 'disk_kons1',
                                        readOnly: true,
                                        fieldClass: 'readonly-input',
                                        allowBlank: false,
                                        id: 'mb_disk_kons1',
                                        style: 'text-align:right;',
                                        value: 0
                                }]
                            }, {
                                xtype: 'displayfield',
                                value: 'Disk Member 1',
                                width: 100
                        },{
                                xtype : 'compositefield',
                                msgTarget: 'side',
                                fieldLabel: 'Disk Member 1',
                                width:200,
                                items : [{
                                        width:          50,
                                        xtype:          'combo',
                                        mode:           'local',
                                        value:          'persen',
                                        triggerAction:  'all',
                                        forceSelection: true,
                                        readOnly: true,
                                        fieldClass: 'readonly-input',
                                        editable:       false,
                                        name:           'disk_memb1_op',
                                        id:           	'mb_disk_memb1_op',
                                        hiddenName:     'disk_memb1_op',
                                        displayField:   'name',
                                        valueField:     'value',
                                        store:          new Ext.data.JsonStore({
                                            fields : ['name', 'value'],
                                            data   : [
                                                {name : '%',   value: 'persen'},
                                                {name : 'Rp',  value: 'amount'}
                                            ]
                                        }),
                                        listeners:{
                                            select:function(){
                                                Ext.getCmp('mb_disk_memb1').setMaxValue(Number.MAX_VALUE);
                                                if (this.getValue() == 'persen')
                                                    Ext.getCmp('mb_disk_memb1').maxValue = 100;
                                                else Ext.getCmp('mb_disk_memb1').maxLength = 11;
                                            }
                                        }
                                    },{
                                        xtype: 'numberfield',
                                        width: 115,
                                        name : 'disk_memb1',
                                        allowBlank: false,
                                        readOnly: true,
                                        fieldClass: 'readonly-input',
                                        id: 'mb_disk_memb1',
                                        style: 'text-align:right;',
                                        value: 0
                                }]
                            }]
                    }, {
                        xtype : 'compositefield',
                        msgTarget: 'side',
                        fieldLabel: 'Disk Konsumen 2',
                        items : [{
                                xtype : 'compositefield',
                                msgTarget: 'side',
                                fieldLabel: 'Disk Konsumen 2',
                                width:200,
                                items : [{
                                        width:          50,
                                        xtype:          'combo',
                                        mode:           'local',
                                        value:          'persen',
                                        triggerAction:  'all',
                                        forceSelection: true,
                                        readOnly: true,
                                        fieldClass: 'readonly-input',
                                        editable:       false,
                                        name:           'disk_kons2_op',
                                        id:           	'mb_disk_kons2_op',
                                        hiddenName:     'disk_kons2_op',
                                        displayField:   'name',
                                        valueField:     'value',
                                        store:          new Ext.data.JsonStore({
                                            fields : ['name', 'value'],
                                            data   : [
                                                {name : '%',   value: 'persen'},
                                                {name : 'Rp',  value: 'amount'}
                                            ]
                                        }),
                                        listeners:{
                                            select:function(){
                                                Ext.getCmp('mb_disk_kons2').setMaxValue(Number.MAX_VALUE);
                                                if (this.getValue() == 'persen')
                                                    Ext.getCmp('mb_disk_kons2').maxValue = 100;
                                                else Ext.getCmp('mb_disk_kons2').maxLength = 11;
                                            }
                                        }
                                    },{
                                        xtype: 'numberfield',
                                        flex : 1,
                                        width:115,
                                        name : 'disk_kons2',
                                        allowBlank: false,
                                        readOnly: true,
                                        fieldClass: 'readonly-input',
                                        id: 'mb_disk_kons2',
                                        style: 'text-align:right;',
                                        value: 0
                                }]

                            },{
                                xtype: 'displayfield',
                                value: 'Disk Member 2',
                                width: 100
                        },{
                                xtype : 'compositefield',
                                msgTarget: 'side',
                                fieldLabel: 'Disk Member 2',
                                width:200,
                                items : [{
                                        width:          50,
                                        xtype:          'combo',
                                        mode:           'local',
                                        value:          'persen',
                                        triggerAction:  'all',
                                        forceSelection: true,
                                        readOnly: true,
                                        fieldClass: 'readonly-input',
                                        editable:       false,
                                        name:           'disk_memb2_op',
                                        id:           	'mb_disk_memb2_op',
                                        hiddenName:     'disk_memb2_op',
                                        displayField:   'name',
                                        valueField:     'value',
                                        store:          new Ext.data.JsonStore({
                                            fields : ['name', 'value'],
                                            data   : [
                                                {name : '%',   value: 'persen'},
                                                {name : 'Rp',  value: 'amount'}
                                            ]
                                        }),
                                        listeners:{
                                            select:function(){
                                                Ext.getCmp('mb_disk_memb2').setMaxValue(Number.MAX_VALUE);
                                                if (this.getValue() == 'persen')
                                                    Ext.getCmp('mb_disk_memb2').maxValue = 100;
                                                else Ext.getCmp('mb_disk_memb2').maxLength = 11;
                                            }
                                        }
                                    },{
                                        xtype: 'numberfield',
                                        width: 115,
                                        name : 'disk_memb2',
                                        allowBlank: false,
                                        readOnly: true,
                                        fieldClass: 'readonly-input',
                                        id: 'mb_disk_memb2',
                                        style: 'text-align:right;',
                                        value: 0
                                }]
                            }
                        ]
                    }, {
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
                                        value:          'persen',
                                        triggerAction:  'all',
                                        forceSelection: true,
                                        readOnly: true,
                                        fieldClass: 'readonly-input',
                                        editable:       false,
                                        name:           'disk_kons3_op',
                                        id:           	'mb_disk_kons3_op',
                                        hiddenName:     'disk_kons3_op',
                                        displayField:   'name',
                                        valueField:     'value',
                                        store:          new Ext.data.JsonStore({
                                            fields : ['name', 'value'],
                                            data   : [
                                                {name : '%',   value: 'persen'},
                                                {name : 'Rp',  value: 'amount'}
                                            ]
                                        }),
                                        listeners:{
                                            select:function(){
                                                Ext.getCmp('mb_disk_kons3').setMaxValue(Number.MAX_VALUE);
                                                if (this.getValue() == 'persen')
                                                    Ext.getCmp('mb_disk_kons3').maxValue = 100;
                                                else Ext.getCmp('mb_disk_kons3').maxLength = 11;
                                            }
                                        }
                                    },{
                                        xtype: 'numberfield',
                                        flex : 1,
                                        width:115,
                                        name : 'disk_kons3',
                                        readOnly: true,
                                        fieldClass: 'readonly-input',
                                        allowBlank: false,
                                        id: 'mb_disk_kons3',
                                        style: 'text-align:right;',
                                        value: 0
                                }]

                            },{
                                xtype: 'displayfield',
                                value: 'Disk Member 3',
                                width: 100
                        },{
                                xtype : 'compositefield',
                                msgTarget: 'side',
                                fieldLabel: 'Disk Member 3',
                                width:200,
                                items : [{
                                        width:          50,
                                        xtype:          'combo',
                                        mode:           'local',
                                        value:          'persen',
                                        triggerAction:  'all',
                                        forceSelection: true,
                                        readOnly: true,
                                        fieldClass: 'readonly-input',
                                        editable:       false,
                                        name:           'disk_memb3_op',
                                        id:           	'mb_disk_memb3_op',
                                        hiddenName:     'disk_memb3_op',
                                        displayField:   'name',
                                        valueField:     'value',
                                        store:          new Ext.data.JsonStore({
                                            fields : ['name', 'value'],
                                            data   : [
                                                {name : '%',   value: 'persen'},
                                                {name : 'Rp',  value: 'amount'}
                                            ]
                                        }),
                                        listeners:{
                                            select:function(){
                                                Ext.getCmp('mb_disk_memb3').setMaxValue(Number.MAX_VALUE);
                                                if (this.getValue() == 'persen')
                                                    Ext.getCmp('mb_disk_memb3').maxValue = 100;
                                                else Ext.getCmp('mb_disk_memb3').maxLength = 11;
                                            }
                                        }
                                    },{
                                        xtype: 'numberfield',
                                        width: 115,
                                        name : 'disk_memb3',
                                        allowBlank: false,
                                        readOnly: true,
                                        fieldClass: 'readonly-input',
                                        id: 'mb_disk_memb3',
                                        style: 'text-align:right;',
                                        value: 0
                                }]
                            }
                        ]
                    },{
                        xtype : 'compositefield',
                        msgTarget: 'side',
                        fieldLabel: 'Disk Konsumen 4',
                        items : [{
                                xtype : 'compositefield',
                                msgTarget: 'side',
                                fieldLabel: 'Disk Konsumen 4',
                                width:200,
                                items : [{
                                        width:          50,
                                        xtype:          'combo',
                                        mode:           'local',
                                        value:          'persen',
                                        triggerAction:  'all',
                                        forceSelection: true,
                                        readOnly: true,
                                        fieldClass: 'readonly-input',
                                        editable:       false,
                                        name:           'disk_kons4_op',
                                        id:           	'mb_disk_kons4_op',
                                        hiddenName:     'disk_kons4_op',
                                        displayField:   'name',
                                        valueField:     'value',
                                        store:          new Ext.data.JsonStore({
                                            fields : ['name', 'value'],
                                            data   : [
                                                {name : '%',   value: 'persen'},
                                                {name : 'Rp',  value: 'amount'}
                                            ]
                                        }),
                                        listeners:{
                                            select:function(){
                                                Ext.getCmp('mb_disk_kons4').setMaxValue(Number.MAX_VALUE);
                                                if (this.getValue() == 'persen')
                                                    Ext.getCmp('mb_disk_kons4').maxValue = 100;
                                                else Ext.getCmp('mb_disk_kons4').maxLength = 11;
                                            }
                                        }
                                    },{
                                        xtype: 'numberfield',
                                        flex : 1,
                                        width:115,
                                        name : 'disk_kons4',
                                        allowBlank: false,
                                        readOnly: true,
                                        fieldClass: 'readonly-input',
                                        id: 'mb_disk_kons4',
                                        style: 'text-align:right;',
                                        value: 0
                                }]

                            },{
                                xtype: 'displayfield',
                                value: 'Disk Member 4',
                                width: 100
                        },{
                                xtype : 'compositefield',
                                msgTarget: 'side',
                                fieldLabel: 'Disk Member 4',
                                width:200,
                                items : [{
                                        width:          50,
                                        xtype:          'combo',
                                        mode:           'local',
                                        value:          'persen',
                                        triggerAction:  'all',
                                        forceSelection: true,
                                        readOnly: true,
                                        fieldClass: 'readonly-input',
                                        editable:       false,
                                        name:           'disk_memb4_op',
                                        id:           	'mb_disk_memb4_op',
                                        hiddenName:     'disk_memb4_op',
                                        displayField:   'name',
                                        valueField:     'value',
                                        store:          new Ext.data.JsonStore({
                                            fields : ['name', 'value'],
                                            data   : [
                                                {name : '%',   value: 'persen'},
                                                {name : 'Rp',  value: 'amount'}
                                            ]
                                        }),
                                        listeners:{
                                            select:function(){
                                                Ext.getCmp('mb_disk_memb4').setMaxValue(Number.MAX_VALUE);
                                                if (this.getValue() == 'persen')
                                                    Ext.getCmp('mb_disk_memb4').maxValue = 100;
                                                else Ext.getCmp('mb_disk_memb4').maxLength = 11;
                                            }
                                        }
                                    },{
                                        xtype: 'numberfield',
                                        name : 'disk_memb4',
                                        width: 115,
                                        allowBlank: false,
                                        id: 'mb_disk_memb4',
                                        readOnly: true,
                                        fieldClass: 'readonly-input',
                                        style: 'text-align:right;',
                                        value: 0
                                }]
                            }
                        ]
                    },{
                        xtype : 'compositefield',
                        msgTarget: 'side',
                        fieldLabel: 'Disk Konsumen 5',
                        items : [{
                                xtype: 'numericfield',
                                currencySymbol:'',
                                width: 170,
                                name : 'disk_kons5',
                                allowBlank: false,
                                readOnly: true,
                                fieldClass: 'readonly-input',
                                id: 'mb_disk_kons5',
                                style: 'text-align:right;',
                                value: 0
                        },{
                                xtype: 'displayfield',
                                value: 'Disk Member 5',
                                style: 'padding-left:30px;',
                                width: 130
                        },{
                                xtype: 'numericfield',
                                currencySymbol:'',
                                name : 'disk_memb5',
                                allowBlank: false,
                                readOnly: true,
                                fieldClass: 'readonly-input',
                                width: 170,
                                id: 'mb_disk_memb5',
                                style: 'text-align:right;',
                                value: 0
                        }
                        ]
                    }]
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
                    }]
                }, {
                    xtype : 'compositefield',
                    msgTarget: 'side',
                    fieldLabel: 'Aktif',
                    items : [
                    new Ext.form.Checkbox({
                        xtype: 'checkbox',
                        boxLabel:'Ya',
                        name:'is_bonus',
                        id:'mb_is_bonus',
                        inputValue: '1',
                        autoLoad : true,
                        width: 290
                    }), {
                        xtype: 'displayfield',
                        value: 'Periode',
                        width: 90
                    }, {
                        xtype: 'datefield',
                        name: 'tgl_start_bonus',
                        id: 'mb_tgl_start_bonus',
                        format: 'd-M-Y',
                        readOnly: true,
                        fieldClass: 'readonly-input',
                        width: 170
                    }, {
                        xtype: 'displayfield',
                        value: 's.d',
                        width: 20
                    }, {
                        xtype: 'datefield',
                        name: 'tgl_end_bonus',
                        id: 'mb_tgl_end_bonus',
                        format: 'd-M-Y',
                        readOnly: true,
                        fieldClass: 'readonly-input',
                        width: 170
                    }]
            }, {
                    xtype : 'compositefield',
                    msgTarget: 'side',
                    fieldLabel: 'Qty Beli',
                    items : [{
                        xtype: 'numericfield',
                        currencySymbol:'',
                        width: 250,
                        name : 'qty_beli_bonus',
                        readOnly: true,
                        fieldClass: 'readonly-input',
                        id: 'mb_qty_beli_bonus',
                        style: 'text-align:right;',
                        value: 0
                    },{
                        xtype: 'displayfield',
                        value: 'Qty Beli',
                        style: 'padding-left:40px;',
                        readOnly: true,
                        fieldClass: 'readonly-input',
                        width: 130
                    },{
                        xtype: 'numericfield',
                        currencySymbol:'',
                        name : 'qty_beli_member',
                        readOnly: true,
                        fieldClass: 'readonly-input',
                        width: 250,
                        id: 'mb_qty_beli_member',
                        style: 'text-align:right;',
                        value: 0
                    } ]
                },{
                    xtype : 'compositefield',
                    msgTarget: 'side',
                    fieldLabel: 'Qty Bonus',
                    items : [{
                        xtype: 'numberfield',
                        width: 250,
                        name : 'qty_bonus',
                        id: 'mb_qty_bonus',
                        style: 'text-align:right;',
                        readOnly: true,
                        fieldClass: 'readonly-input',
                        value: 0
                    },{
                        xtype: 'displayfield',
                        value: 'Qty Bonus',
                        style: 'padding-left:40px;',
                        width: 130
                    },{
                        xtype: 'numberfield',
                        name : 'qty_member',
                        width: 250,
                        id: 'mb_qty_member',
                        style: 'text-align:right;',
                        readOnly: true,
                        fieldClass: 'readonly-input',
                        value: 0
                    }]
                },{
                    xtype : 'compositefield',
                    msgTarget: 'side',
                    fieldLabel: 'Kelipatan',
                    items : [new Ext.form.Checkbox({
                            xtype: 'checkbox',
                            boxLabel:'Ya',
                            name:'is_bonus_kelipatan',
                            id:'mb_is_bonus_kelipatan',
                            inputValue: '1',
                            readOnly: true,
                            fieldClass: 'readonly-input',
                            autoLoad : true,
                            width: 250
                        }),{
                            xtype: 'displayfield',
                            value: 'Kelipatan',
                            style: 'padding-left:40px;',
                            width: 130
                    },new Ext.form.Checkbox({
                            xtype: 'checkbox',
                            boxLabel:'Ya',
                            name:'is_member_kelipatan',
                            id:'mb_is_member_kelipatan',
                            inputValue: '1',
                            readOnly: true,
                            fieldClass: 'readonly-input',
                            autoLoad : true,
                            width: 250
                        })
                    ]
                },{

                    layout: 'column',
                    border: false,
                    width: 800,
                    items: [{
                        columnWidth: .5,
                        layout: 'form',
                        border: false,
                        labelWidth: 80,
                        defaultType: 'textfield',
                        defaults: { labelSeparator: ''},
                        items: [{
                            xtype:'fieldset',
                            defaults: { labelSeparator: ''},
                            autoheight: true,
                            title: 'Bonus by Kategori',
                            anchor: '90%',
                            items:[{
                                xtype: 'textfield',
                                fieldLabel: 'Kategori 1',
                                width: 250,
                                name : 'kategori1_bonus',
                                id: 'mb_kategori1_bonus',
                                readOnly: true,
                                fieldClass: 'readonly-input'
                            }, {
                                xtype: 'textfield',
                                fieldLabel: 'Kategori 2',
                                width: 250,
                                name : 'kategori2_bonus',
                                id: 'mb_kategori2_bonus',
                                readOnly: true,
                                fieldClass: 'readonly-input'
                            }, {
                                xtype: 'textfield',
                                fieldLabel: 'Kategori 3',
                                width: 250,
                                name : 'kategori3_bonus',
                                id: 'mb_kategori3_bonus',
                                readOnly: true,
                                fieldClass: 'readonly-input'
                            }, {
                                xtype: 'textfield',
                                fieldLabel: 'Kategori 4',
                                width: 250,
                                name : 'kategori4_bonus',
                                id: 'mb_kategori4_bonus',
                                readOnly: true,
                                fieldClass: 'readonly-input'
                            }]
                        }]
                    },{
                        columnWidth: .5,
                        layout: 'form',
                        border: false,
                        labelWidth: 80,
                        defaultType: 'textfield',
                        defaults: { labelSeparator: ''},
                        items: [{
                            xtype:'fieldset',
                            defaults: { labelSeparator: ''},
                            autoheight: true,
                            title: 'Bonus by Kategori',
                            anchor: '90%',
                            items:[{
                                xtype: 'textfield',
                                fieldLabel: 'Kategori 1',
                                width: 250,
                                name : 'kategori1_member',
                                id: 'mb_kategori1_member',
                                readOnly: true,
                                fieldClass: 'readonly-input'
                            }, {
                                xtype: 'textfield',
                                fieldLabel: 'Kategori 2',
                                width: 250,
                                name : 'kategori2_member',
                                id: 'mb_kategori2_member',
                                readOnly: true,
                                fieldClass: 'readonly-input'
                            }, {
                                xtype: 'textfield',
                                fieldLabel: 'Kategori 3',
                                width: 250,
                                name : 'kategori3_member',
                                id: 'mb_kategori3_member',
                                readOnly: true,
                                fieldClass: 'readonly-input'
                            }, {
                                xtype: 'textfield',
                                fieldLabel: 'Kategori 4',
                                width: 250,
                                name : 'kategori4_member',
                                id: 'mb_kategori4_member',
                                readOnly: true,
                                fieldClass: 'readonly-input'
                            }]
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
                        labelWidth: 80,
                        defaultType: 'textfield',
                        defaults: { labelSeparator: ''},
                        items: [{
                            xtype:'fieldset',
                            autoheight: true,
                            title: 'Bonus by Produk',
                            defaults: { labelSeparator: ''},
                            anchor: '90%',
                            items:[{
                                xtype: 'textfield',
                                fieldLabel: 'Produk',
                                width: 250,
                                name : 'nama_produk_bonus',
                                id: 'mb_nama_produk_bonus',
                                readOnly: true,
                                fieldClass: 'readonly-input'
                            }]
                        }]
                    },{
                        columnWidth: .5,
                        layout: 'form',
                        border: false,
                        labelWidth: 80,
                        defaultType: 'textfield',
                        defaults: { labelSeparator: ''},
                        items: [{
                            xtype:'fieldset',
                            autoheight: true,
                            title: 'Bonus by Produk',
                            anchor: '90%',
                            items: [{
                                xtype: 'textfield',
                                fieldLabel: 'Produk',
                                width: 250,
                                name : 'nama_produk_member',
                                id: 'mb_nama_produk_member',
                                readOnly: true,
                                fieldClass: 'readonly-input'
                            }]
                        }]
                    }]
                }]
            }
        ],
        buttons: [{
                text: 'Save',
                handler: function(){

                    if (Ext.getCmp('mb_ket_perubahan').getValue() == ''){
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Keterangan Perubahan harus di isi !',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                        });
                        return;
                    }

                    var masterbarang = new Array();
                    strmasterbarang.each(function(node){
                        masterbarang.push(node.data)
                    });
                    Ext.getCmp('masterbarang').getForm().submit({
                        url: '<?= site_url("master_barang/update_row") ?>',
                        scope: this,
//                        params: {
//                            detail: Ext.util.JSON.encode(masterbarang)
//                        },
                        waitMsg: 'Saving Data...',
                        success: function(form, action){
                            Ext.Msg.show({
                                title: 'Success',
                                msg: 'Form submitted successfully',
                                modal: true,
                                icon: Ext.Msg.INFO,
                                buttons: Ext.Msg.OK
                            });

                            clearmasterbarang();
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
                    clearmasterbarang();
                }
            }]
    });

    function clearmasterbarang(){
        Ext.getCmp('mb_brg_cbkategori1').setReadOnly(false);
        Ext.getCmp('mb_brg_cbkategori2').setReadOnly(false);
        Ext.getCmp('mb_brg_cbkategori3').setReadOnly(false);
        Ext.getCmp('mb_brg_cbkategori4').setReadOnly(false);
        Ext.getCmp('id_combo_lokasi_mstbarang').setReadOnly(false);
        Ext.getCmp('id_combo_blok_mstbarang').setReadOnly(false);
        Ext.getCmp('id_combo_subblok_mstbarang').setReadOnly(false);
        Ext.getCmp('id_comboTypeLokasiMasterBarang').setReadOnly(false);

        Ext.getCmp('mb_brg_cbkategori1').removeClass('readonly-input');
        Ext.getCmp('mb_brg_cbkategori2').removeClass('readonly-input');
        Ext.getCmp('mb_brg_cbkategori3').removeClass('readonly-input');
        Ext.getCmp('mb_brg_cbkategori4').removeClass('readonly-input');
        Ext.getCmp('id_combo_lokasi_mstbarang').removeClass('readonly-input');
        Ext.getCmp('id_combo_blok_mstbarang').removeClass('readonly-input');
        Ext.getCmp('id_combo_subblok_mstbarang').removeClass('readonly-input');
        Ext.getCmp('id_comboTypeLokasiMasterBarang').removeClass('readonly-input');

        Ext.getCmp('masterbarang').getForm().reset();
        strmasterbarang.removeAll();
        strmbhisto.removeAll();
        strmbhistocogs.removeAll();
        strmbhistocogsdist.removeAll();
        strmbhistoinv.removeAll();
    }

    function setDataMasterProduk (data) {
        Ext.getCmp('mb_brg_cbkategori1').setValue(data.kd_kategori1);
        Ext.getCmp('mb_brg_cbkategori2').setValue(data.kd_kategori2);
        Ext.getCmp('mb_brg_cbkategori3').setValue(data.kd_kategori3);
        Ext.getCmp('mb_brg_cbkategori4').setValue(data.kd_kategori4);
        Ext.getCmp('mb_brg_cbkategori1').setRawValue(data.nama_kategori1);
        Ext.getCmp('mb_brg_cbkategori2').setRawValue(data.nama_kategori2);
        Ext.getCmp('mb_brg_cbkategori3').setRawValue(data.nama_kategori3);
        Ext.getCmp('mb_brg_cbkategori4').setRawValue(data.nama_kategori4);
        Ext.getCmp('mb_no_urut').setValue(data.no_urut);
        Ext.getCmp('mb_created_date').setValue(new Date(data.created_date));
        Ext.getCmp('mb_cbsatuan').setValue(data.nm_satuan);
        Ext.getCmp('mb_cbukuran').setValue(data.nama_ukuran);
        Ext.getCmp('mb_kd_produk_lama').setValue(data.kd_produk_lama);
        Ext.getCmp('mb_kd_produk_supp').setValue(data.kd_produk_supp);
        Ext.getCmp('mb_min_stok').setValue(data.min_stok);
        Ext.getCmp('mb_max_stok').setValue(data.max_stok);
        Ext.getCmp('mb_min_order').setValue(data.min_order);
        Ext.getCmp('mb_min_order').setValue(data.min_order);
        Ext.getCmp('mb_waktu_top').setValue(data.waktu_top);
        Ext.getCmp('mb_kd_peruntukkanS').setValue(data.mb_kd_peruntukkanS);
        Ext.getCmp('mb_kd_peruntukkanD').setValue(data.mb_kd_peruntukkanD);
        Ext.getCmp('mb_is_konsinyasi_brg').setValue(data.is_konsinyasi);
        Ext.getCmp('mb_aktif').setValue(data.aktif);
        Ext.getCmp('mb_pct_alert').setValue(data.pct_alert);
        Ext.getCmp('mb_aktif_purchase').setValue(data.aktif_purchase);
        Ext.getCmp('mb_is_harga_lepas').setValue(data.is_harga_lepas);
        Ext.getCmp('mb_net_hrg_supplier_sup_exc').setValue(data.net_hrg_supplier_sup_exc);
        Ext.getCmp('mb_net_hrg_supplier_sup_inc').setValue(data.net_hrg_supplier_sup_inc);
        Ext.getCmp('mb_net_hrg_supplier_dist_inc').setValue(data.net_hrg_supplier_dist_inc);
        Ext.getCmp('mb_hrg_beli_dist').setValue(data.mb_hrg_beli_dist);
        Ext.getCmp('mb_margin_cogs').setValue(data.margin_cogs);
        Ext.getCmp('mb_rp_margin_cogs').setValue(data.rp_margin_cogs);
        Ext.getCmp('mb_rp_ongkos_kirim_cogs').setValue(data.rp_ongkos_kirim_cogs);
        Ext.getCmp('mb_rp_cogs').setValue(data.rp_cogs);
        Ext.getCmp('mb_rp_het_cogs').setValue(data.rp_het_cogs);
        Ext.getCmp('mb_rp_het_cogs_inc').setValue(data.rp_het_cogs_inc);
        Ext.getCmp('mb_rp_cogs_dist').setValue(data.rp_cogs_dist);
        Ext.getCmp('mb_rp_jual_supermarket').setValue(data.rp_jual_supermarket);
        Ext.getCmp('mb_rp_jual_supermarket_member_net').setValue(data.rp_jual_supermarket_member_net);
        Ext.getCmp('mb_rp_jual_supermarket_net').setValue(data.rp_jual_supermarket_net);

        Ext.getCmp('mb_rp_jual_distribusi_net').setValue(data.rp_jual_distribusi);

        Ext.getCmp('mb_rp_ongkos_kirim').setValue(data.rp_ongkos_kirim);
        Ext.getCmp('mb_margin').setValue(data.pct_margin);
        Ext.getCmp('mb_rp_margin').setValue(data.rp_margin);
        Ext.getCmp('mb_rp_ongkos_kirim_dist').setValue(data.rp_ongkos_kirim);
        Ext.getCmp('mb_margin_dist').setValue(data.pct_margin);
        Ext.getCmp('mb_rp_margin_dist').setValue(data.rp_margin_dist);
        Ext.getCmp('mb_rp_het_harga_beli').setValue(data.rp_het_harga_beli);
        Ext.getCmp('mb_rp_het_harga_beli_inc').setValue(data.rp_het_harga_beli_inc);
        Ext.getCmp('mb_rp_het_harga_beli_dist').setValue(data.rp_het_harga_beli_dist);
        Ext.getCmp('mb_rp_het_harga_beli_dist_inc').setValue(data.rp_het_harga_beli_dist_inc);
        Ext.getCmp('mb_rp_jual_distribusi').setValue(data.rp_jual_distribusi);
    }

    function setDataDiskonBonus (data) {
        Ext.getCmp('mb_disk_kons1_op').setValue(data.disk_kons1_op);
        Ext.getCmp('mb_disk_kons1').setValue(data.disk_kons1);
        Ext.getCmp('mb_disk_kons2_op').setValue(data.disk_kons2_op);
        Ext.getCmp('mb_disk_kons2').setValue(data.disk_kons2);
        Ext.getCmp('mb_disk_kons3_op').setValue(data.disk_kons3_op);
        Ext.getCmp('mb_disk_kons3').setValue(data.disk_kons3);
        Ext.getCmp('mb_disk_kons4_op').setValue(data.disk_kons4_op);
        Ext.getCmp('mb_disk_kons4').setValue(data.disk_kons4);
        Ext.getCmp('mb_disk_kons5').setValue(data.disk_amt_kons5);
        Ext.getCmp('mb_disk_memb1_op').setValue(data.disk_memb1_op);
        Ext.getCmp('mb_disk_memb1').setValue(data.disk_memb1);
        Ext.getCmp('mb_disk_memb2_op').setValue(data.disk_memb2_op);
        Ext.getCmp('mb_disk_memb2').setValue(data.disk_memb2);
        Ext.getCmp('mb_disk_memb3_op').setValue(data.disk_memb3_op);
        Ext.getCmp('mb_disk_memb3').setValue(data.disk_memb3);
        Ext.getCmp('mb_disk_memb4_op').setValue(data.disk_memb4_op);
        Ext.getCmp('mb_disk_memb4').setValue(data.disk_memb4);
        Ext.getCmp('mb_disk_memb5').setValue(data.disk_amt_member5);
        Ext.getCmp('mb_is_barang_paket').setValue(data.is_barang_paket);

        Ext.getCmp('mb_is_bonus').setValue(data.is_bonus);
        Ext.getCmp('mb_tgl_start_bonus').setValue(data.tgl_start_bonus);
        Ext.getCmp('mb_tgl_end_bonus').setValue(data.tgl_end_bonus);
        Ext.getCmp('mb_qty_beli_bonus').setValue(data.qty_beli_bonus);
        Ext.getCmp('mb_qty_beli_member').setValue(data.qty_beli_member);
        Ext.getCmp('mb_nama_produk_bonus').setValue(data.nama_produk_bonus);
        Ext.getCmp('mb_nama_produk_member').setValue(data.nama_produk_member);
        Ext.getCmp('mb_nama_produk_bonus').setValue(data.nama_produk_bonus);
        Ext.getCmp('mb_nama_produk_member').setValue(data.nama_produk_member);
        Ext.getCmp('mb_qty_bonus').setValue(data.qty_bonus);
        Ext.getCmp('mb_qty_member').setValue(data.qty_member);
        Ext.getCmp('mb_is_bonus_kelipatan').setValue(data.is_bonus_kelipatan);
        Ext.getCmp('mb_is_member_kelipatan').setValue(data.is_member_kelipatan);
        Ext.getCmp('mb_kd_diskon_sales').setValue(data.kd_diskon_sales);
    }

    function cleanDataDiskonBonus() {
        Ext.getCmp('mb_disk_kons1_op').setValue('persen');
        Ext.getCmp('mb_disk_kons1').setValue(0);
        Ext.getCmp('mb_disk_kons2_op').setValue('persen');
        Ext.getCmp('mb_disk_kons2').setValue(0);
        Ext.getCmp('mb_disk_kons3_op').setValue('persen');
        Ext.getCmp('mb_disk_kons3').setValue(0);
        Ext.getCmp('mb_disk_kons4_op').setValue('persen');
        Ext.getCmp('mb_disk_kons4').setValue(0);
        Ext.getCmp('mb_disk_kons5').setValue(0);
        Ext.getCmp('mb_disk_memb1_op').setValue('persen');
        Ext.getCmp('mb_disk_memb1').setValue(0);
        Ext.getCmp('mb_disk_memb2_op').setValue('persen');
        Ext.getCmp('mb_disk_memb2').setValue(0);
        Ext.getCmp('mb_disk_memb3_op').setValue('persen');
        Ext.getCmp('mb_disk_memb3').setValue(0);
        Ext.getCmp('mb_disk_memb4_op').setValue('persen');
        Ext.getCmp('mb_disk_memb4').setValue(0);
        Ext.getCmp('mb_disk_memb5').setValue(0);
        Ext.getCmp('mb_is_barang_paket').setValue();

        Ext.getCmp('mb_is_bonus').setValue();
        Ext.getCmp('mb_tgl_start_bonus').setValue();
        Ext.getCmp('mb_tgl_end_bonus').setValue();
        Ext.getCmp('mb_qty_beli_bonus').setValue(0);
        Ext.getCmp('mb_qty_beli_member').setValue(0);
        Ext.getCmp('mb_nama_produk_bonus').setValue();
        Ext.getCmp('mb_nama_produk_member').setValue();
        Ext.getCmp('mb_nama_produk_bonus').setValue();
        Ext.getCmp('mb_nama_produk_member').setValue();
        Ext.getCmp('mb_qty_bonus').setValue(0);
        Ext.getCmp('mb_qty_member').setValue(0);
        Ext.getCmp('mb_is_bonus_kelipatan').setValue(false);
        Ext.getCmp('mb_is_member_kelipatan').setValue(false);
    }
</script>
