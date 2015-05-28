<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
/**
 * Combo supplier
 **/
    var strPaketComboSupplier = new Ext.data.ArrayStore({
        fields: ['kd_supplier', 'nama_supplier'],
        data : []
    });

    var strGridPaketSupplier = new Ext.data.Store({
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

    var searchGridPaketSupplier = new Ext.app.SearchField({
        store: strGridPaketSupplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_barter_search_grid_supplier'
    });


    var gridPaketSupplier = new Ext.grid.GridPanel({
        store: strGridPaketSupplier,
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
            items: [searchGridPaketSupplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strGridPaketSupplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_paket_combo_supplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_paket_nama_supplier').setValue(sel[0].get('nama_supplier'));
                    strpembelianretur.removeAll();
                    menuPaketSupplier.hide();
                }
            }
        }
    });

    var menuPaketSupplier = new Ext.menu.Menu();

    menuPaketSupplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridPaketSupplier],
        buttons: [{
            text: 'Close',
            handler: function(){
                menuPaketSupplier.hide();
            }
        }]
    }));

    Ext.ux.TwinComboPaketSupplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            strGridPaketSupplier.load();
            menuPaketSupplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menuPaketSupplier.on('hide', function(){
        var sf = Ext.getCmp('id_barter_search_grid_supplier').getValue();
        if( sf != ''){
            Ext.getCmp('id_barter_search_grid_supplier').setValue('');
            searchGridPaketSupplier.onTrigger2Click();
        }
    });

    var comboPaketSupplier = new Ext.ux.TwinComboPaketSupplier({
        fieldLabel: 'Supplier <span class="asterix">*</span>',
        id: 'id_paket_combo_supplier',
        store: strPaketComboSupplier,
        mode: 'local',
        valueField: 'kd_supplier',
        displayField: 'kd_supplier',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_supplier',
        emptyText: 'Pilih Supplier'
    });

    //------------ COMBO PRODUK ---------------------
    var mbpStrCmbProduk = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_produk','kd_produk_lama','kd_produk_supp', 'nama_produk', 'jml_stok'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("barang_paket/get_produk") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function(event, options, response, error){
                console.log(error);
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var mbpSearchProduk = new Ext.app.SearchField({
        store: mbpStrCmbProduk,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'id_mbp_search_produk'
    });

    var mbpToolbarProduk = new Ext.Toolbar({
        items: [mbpSearchProduk]
    });

    var mbpGridProduk = new Ext.grid.GridPanel({
        store: mbpStrCmbProduk,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [
            {
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
                        url: '<?= site_url("barang_paket/get_produk_detail") ?>',
                        method: 'POST',
                        params: {
                            kd_produk: sel[0].get('kd_produk')
                        },
                        callback:function(opt,success,responseObj){
                            var de = Ext.util.JSON.decode(responseObj.responseText);
                            if(de.success==true){
                                var data = de.data[0];
                                var peruntukan = "";
                                switch (data.kd_peruntukkan) {
                                    case "0":
                                        peruntukan  = "Supermarket";break;
                                    case "1":
                                        peruntukan  = "Distribusi";break;
                                }

                                var konsinyasi      = data.is_konsinyasi == "0" ? "Tidak" : "Ya";
                                var aktifPurchase   = data.aktif_purchase == "0" ? "Tidak" : "Ya";
                                var aktifBarang     = data.aktif == "0" ? "Tidak" : "Ya";
                                var hargaLepas      = data.is_harga_lepas == "0" ? "Tidak" : "Ya";

                                Ext.getCmp('mbp_kd_produk').setValue(data.kd_produk);
                                Ext.getCmp('id_mbp_kd_produk').setValue(data.kd_produk);
                                Ext.getCmp('id_mbp_info_nama_produk').setValue(data.nama_produk);
                                Ext.getCmp('id_mbp_info_kd_produk_lama').setValue(data.kd_produk_lama);
                                Ext.getCmp('id_mbp_info_kd_produk_supp').setValue(data.kd_produk_supp);
                                Ext.getCmp('id_mbp_info_satuan').setValue(data.nm_satuan);
                                Ext.getCmp('id_mbp_info_ukuran').setValue(data.nama_ukuran);
                                Ext.getCmp('id_mbp_info_kategori1').setValue(data.nama_kategori1);
                                Ext.getCmp('id_mbp_info_kategori2').setValue(data.nama_kategori2);
                                Ext.getCmp('id_mbp_info_kategori3').setValue(data.nama_kategori3);
                                Ext.getCmp('id_mbp_info_kategori4').setValue(data.nama_kategori4);
                                Ext.getCmp('id_mbp_kd_peruntukan').setValue(data.kd_peruntukkan);
                                Ext.getCmp('id_mbp_info_peruntukan').setValue(peruntukan);
                                Ext.getCmp('id_mbp_info_kosinyasi').setValue(konsinyasi);
                                Ext.getCmp('id_mbp_info_aktif_purchase').setValue(aktifPurchase);
                                Ext.getCmp('id_mbp_info_aktif').setValue(aktifBarang);
                                Ext.getCmp('id_mbp_info_harga_lepas').setValue(hargaLepas);
                                Ext.getCmp('id_mbp_info_min_stok').setValue(data.min_stok);
                                Ext.getCmp('id_mbp_info_max_stok').setValue(data.max_stok);
                                Ext.getCmp('id_mbp_info_min_order').setValue(data.min_order);
                                gridMBPBarangPaket.addBtn.setDisabled(false);
                                Ext.Ajax.request({
                                    url: '<?= site_url("barang_paket/get_detail_paket") ?>',
                                    method: 'POST',
                                    params: {
                                        kd_produk: data.kd_produk
                                    },
                                    callback:function(opt,success,responseObj){
                                        gridMBPBarangPaket.stopEditing();
                                        var de = Ext.util.JSON.decode(responseObj.responseText);
                                        if(de.success==true) {
                                            if(de.data.length > 0) {
                                                strMBPProdukPaket.removeAll();
                                                var totalPaket = 0;
                                                var totalHarga = 0;
                                                for(var i=0;i<de.data.length;i++){
                                                    rowbarangpaket = new strMBPProdukPaket.recordType({
                                                        kd_produk   : de.data[i].kd_produk,
                                                        nama_produk : de.data[i].nama_produk,
                                                        qty         : de.data[i].qty,
                                                        qty_oh      : de.data[i].qty_oh,
                                                        rp_harga    : de.data[i].rp_harga,
                                                        rp_total    : de.data[i].rp_total
                                                    });
                                                    totalPaket = parseInt(totalPaket) + parseInt(de.data[i].qty);
                                                    totalHarga = parseFloat(totalHarga) + parseFloat(de.data[i].rp_total);

                                                    if(Ext.getCmp('id_mbp_tgl_berlaku_dari').getValue() == '') {
                                                        Ext.getCmp('id_mbp_tgl_berlaku_dari').setValue(de.data[i].tgl_berlaku_dari);
                                                    }
                                                    if(Ext.getCmp('id_mbp_tgl_berlaku_sampai').getValue() == '') {
                                                        Ext.getCmp('id_mbp_tgl_berlaku_sampai').setValue(de.data[i].tgl_berlaku_sampai);
                                                    }
                                                    strMBPProdukPaket.insert(i, rowbarangpaket);
                                                }

                                                Ext.getCmp('id_mbp_jum_paket').setValue(data.qty_paket);
                                                Ext.getCmp('id_mbp_qty_paket').setValue(totalPaket);
                                                Ext.getCmp('id_mbp_rp_total_paket').setValue(totalHarga);
                                                gridMBPBarangPaket.addBtn.setDisabled(true);
                                                Ext.getCmp('id_mbp_jum_paket').setDisabled(true);
                                                Ext.getCmp('id_mbp_save_button').setDisabled(true);
                                            } else {
                                                gridMBPBarangPaket.addBtn.setDisabled(false);
                                                Ext.getCmp('id_mbp_jum_paket').setDisabled(false);
                                                Ext.getCmp('id_mbp_save_button').setDisabled(false);
                                            }
                                        }
                                        gridMBPBarangPaket.getView().refresh();
                                    }
                                });


                            } else {
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
                    })
                }
                mbpMenuProduk.hide();
            }
        },
        tbar:mbpToolbarProduk,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: mbpStrCmbProduk,
            displayInfo: true
        })
    });

    var mbpMenuProduk = new Ext.menu.Menu();

    mbpMenuProduk.add(new Ext.Panel({
        title: 'Pilih Produk',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 700,
        height: 400,
        closeAction: 'hide',
        plain: true,
        items: [mbpGridProduk],
        buttons: [{
            text: 'Close',
            handler: function(){
                mbpMenuProduk.hide();
            }
        }]
    }));

    Ext.ux.MBPTwinComboProduk = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
//        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            mbpStrCmbProduk.load();
            mbpMenuProduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    var comboMBPProduk = new Ext.ux.MBPTwinComboProduk({
        id: 'mbp_kd_produk',
        store: mbpStrCmbProduk,
        valueField: 'kd_produk',
        displayField: 'kd_produk',
        typeAhead: true,
        editable: false,
        hiddenName: 'kd_produk',
        emptyText: 'Pilih Kode Produk',
        listeners:{
            'expand': function(){
                mbpStrCmbProduk.load();
            }
        }
    });

    //------------ COMBO PRODUK ---------------------

    //------------ GRID PRODUK PAKET ----------------
    var strMBPProdukPaket = new Ext.data.Store({
        autoSave:false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_produk', allowBlank: false, type: 'text'},
                {name: 'nama_produk', allowBlank: false, type: 'text'},
                {name: 'qty', allowBlank: false, type: 'int'},
                {name: 'qty_oh', allowBlank: false, type: 'int'},
                {name: 'rp_harga', allowBlank: false, type: 'int'},
                {name: 'rp_total', allowBlank: false, type: 'int'}
            ],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("barang_paket/get_produk_paket") ?>',
            method: 'POST'
        }),
        writer: new Ext.data.JsonWriter(
          {
              encode: true,
              writeAllFields: true
          })
    });

    var strComboMBPProduk = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data : []
    });

    var strMBPProdukGrid = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_produk','nama_produk','jml_stok','rp_cogs','rp_cogs_dist'],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("barang_paket/search_produk_paket") ?>',//+Ext.getCmp('mbp_kd_produk').getValue(),
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

    var searchFieldMBP = new Ext.app.SearchField({
        width: 220,
        id: 'id_mbp_search_field',
        store: strMBPProdukGrid
    });

    searchFieldMBP.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';

            // Get the value of search field
            var fid = Ext.getCmp('mbp_kd_produk').getValue();
            var o = { start: 0, kd_produk: fid };

            this.store.baseParams = this.store.baseParams || {};
            this.store.baseParams[this.paramName] = '';
            this.store.reload({
                params : o
            });
            this.triggers[0].hide();
            this.hasSearch = false;
        }
    };

    searchFieldMBP.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }

        // Get the value of search field
        var fid = Ext.getCmp('mbp_kd_produk').getValue();
        var o = { start: 0, kd_produk: fid };

        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params:o});
        this.hasSearch = true;
        this.triggers[0].show();
    };

    // top toolbar
    var tbMBPSearchBarangPaket = new Ext.Toolbar({
        items: [searchFieldMBP]
    });

    var MBPGridPilihProduk = new Ext.grid.GridPanel({
        store: strMBPProdukGrid,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
            dataIndex: 'rp_cogs',
            hidden: true
        },{
            dataIndex: 'rp_cogs_dist',
            hidden: true
        },{
            header: 'Kode Produk',
            dataIndex: 'kd_produk',
            width: 100,
            sortable: true
        },{
            header: 'Nama Produk',
            dataIndex: 'nama_produk',
            width: 400,
            sortable: true
        },{
            header: 'Qty',
            dataIndex: 'jml_stok',
            width: 50,
            sortable: true
        }],
        tbar:tbMBPSearchBarangPaket,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strMBPProdukGrid,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    var qty_row     = Ext.getCmp('grid_mbp_qty').getValue();
                    var qty_paket   = Ext.getCmp('id_mbp_jum_paket').getValue();
                    var peruntukan  = Ext.getCmp('id_mbp_info_peruntukan').getValue();
                    var harga_paket = 0;
                    Ext.getCmp('grid_mbp_kd_produk').setValue(sel[0].get('kd_produk'));
                    Ext.getCmp('grid_mbp_nama_produk').setValue(sel[0].get('nama_produk'));
                    switch(peruntukan){
                        case "Supermarket":
                            harga_paket = sel[0].get('rp_cogs');
                            Ext.getCmp('grid_mbp_harga').setValue(harga_paket);
                            break;
                        case "Distribusi":
                            harga_paket = sel[0].get('rp_cogs_dist');
                            Ext.getCmp('grid_mbp_harga').setValue(harga_paket);
                            break;
                    }
                    Ext.getCmp('grid_mbp_qty_oh').setValue(sel[0].get('jml_stok'));
                    if(sel[0].get('jml_stok') < (qty_row * qty_paket) ) {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Jumlah paket melebihi jumlah stok',
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

                    if(qty_row > 0) {
                        Ext.getCmp('grid_mbp_rp_total').setValue(harga_paket * qty_row);
                    }

                    Ext.getCmp('grid_mbp_qty').focus();
                    mbpMenuPilihProduk.hide();
                }
            }
        }
    });

    var mbpMenuPilihProduk = new Ext.menu.Menu();

    mbpMenuPilihProduk.add(new Ext.Panel({
        title: 'Pilih Barang',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 600,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [MBPGridPilihProduk],
        buttons: [{
            text: 'Close',
            handler: function(){
                mbpMenuPilihProduk.hide();
            }
        }]
    }));

    Ext.ux.TwinComboMBPPilihProduk = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            strMBPProdukGrid.load();
            mbpMenuPilihProduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    strMBPProdukPaket.on('update', function(){
        var qtyPaket = 0;
        var totalPaket = 0;

        strMBPProdukPaket.each(function(node){
            qtyPaket += parseInt(node.data.qty);
            totalPaket += parseInt(node.data.rp_total);
        });

        Ext.getCmp('id_mbp_qty_paket').setValue(qtyPaket);
        Ext.getCmp('id_mbp_rp_total_paket').setValue(totalPaket);
    });

    strMBPProdukPaket.on('remove', function(){
        var qtyPaket = 0;
        var totalPaket = 0;

        strMBPProdukPaket.each(function(node){
            qtyPaket += parseInt(node.data.qty);
            totalPaket += parseInt(node.data.rp_total);
        });

        Ext.getCmp('id_mbp_qty_paket').setValue(qtyPaket);
        Ext.getCmp('id_mbp_rp_total_paket').setValue(totalPaket);
    });

    strMBPProdukPaket.on('load', function(){
        var qtyPaket = 0;
        var totalPaket = 0;
        strMBPProdukPaket.each(function(node){
            qtyPaket += parseInt(node.data.qty);
            totalPaket += parseInt(node.data.rp_total);
        });
        Ext.getCmp('id_mbp_qty_paket').setValue(qtyPaket);
        Ext.getCmp('id_mbp_rp_total_paket').setValue(totalPaket);
    });

    var editorMBPBarangPaket = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });

    var gridMBPBarangPaket = new Ext.grid.GridPanel({
        store: strMBPProdukPaket,
        stripeRows: true,
        height: 300,
        frame: true,
        border:true,
        style:'padding-bottom:10px;',
        plugins: [editorMBPBarangPaket],
        tbar: [{
            ref: '../addBtn',
            icon: BASE_ICONS + 'add.png',
            text: 'Add',
            disabled: true,
            handler: function(){
                var jum_paket   = Ext.getCmp('id_mbp_jum_paket').getValue();
                var tgl_dari    = Ext.getCmp('id_mbp_tgl_berlaku_dari').getValue();
                var tgl_sampai  = Ext.getCmp('id_mbp_tgl_berlaku_sampai').getValue();
                var errMsg = '';
                if(jum_paket == '' || jum_paket == 0) {
                    errMsg += '- Jumlah barang paket masih kosong<br/>';
                }
                if(tgl_dari == '' || tgl_sampai == '') {
                    errMsg += '- Tanggal berlaku paket belum lengkap<br/>';
                }
                if( errMsg != '' ) {
                    Ext.Msg.show({
                        title: 'Error',
                        msg: 'Data tidak lengkap<br/>' + errMsg,
                        modal: true,
                        icon: Ext.Msg.ERROR,
                        buttons: Ext.Msg.OK,
                        fn: function(btn){
                            if (btn == 'ok' && fe.errMsg == 'Session Expired') {
                                window.location = '<?= site_url("auth/login") ?>';
                            }
                        }
                    });
                    Ext.getCmp('id_mbp_jum_paket').focus();
                } else {
                    var rowbarangpaket = new gridMBPBarangPaket.store.recordType({
                        kd_produk : '',
                        qty: ''
                    });
                    editorMBPBarangPaket.stopEditing();
                    strMBPProdukPaket.insert(0, rowbarangpaket);
                    gridMBPBarangPaket.getView().refresh();
                    gridMBPBarangPaket.getSelectionModel().selectRow(0);
                    editorMBPBarangPaket.startEditing(0);
                }
            }
        },{
            ref: '../removeBtn',
            icon: BASE_ICONS + 'delete.gif',
            text: 'Remove',
            disabled: true,
            handler: function(){
                editorMBPBarangPaket.stopEditing();
                var s = gridMBPBarangPaket.getSelectionModel().getSelections();
                for(var i = 0, r; r = s[i]; i++){
                    strMBPProdukPaket.remove(r);
                }
            }
        }],
        columns: [{
            header: 'Kode',
            dataIndex: 'kd_produk',
            width: 110,
            editor: new Ext.ux.TwinComboMBPPilihProduk({
                id: 'grid_mbp_kd_produk',
                store: strComboMBPProduk,
                mode: 'local',
                valueField: 'kd_produk',
                displayField: 'kd_produk',
                typeAhead: true,
                triggerAction: 'all',
                editable: false,
                hiddenName: 'kd_produk',
                emptyText: 'Pilih produk'
            })
        },{
            header: 'Nama Barang',
            dataIndex: 'nama_produk',
            width: 300,
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'grid_mbp_nama_produk'
            })
        },{
            xtype: 'numbercolumn',
            header: 'Qty',
            dataIndex: 'qty',
            width: 50,
            align: 'center',
            sortable: true,
            format: '0,0',
            editor: {
                xtype: 'numberfield',
                id: 'grid_mbp_qty',
                //allowBlank: false,
                selectOnFocus: true,
                listeners:{
                    'render': function(c) {
                        c.getEl().on('keyup', function() {
                            var harga_paket = Ext.getCmp('grid_mbp_harga').getValue();
                            var qtyPaket = this.getValue();

                            var totalPaket = harga_paket*qtyPaket;
                            var jum_paket  = Ext.getCmp('id_mbp_jum_paket').getValue();
                            var qty_oh     = Ext.getCmp('grid_mbp_qty_oh').getValue();
                            var qty_paket  = this.getValue() * jum_paket;
                            if(qty_oh < qty_paket) {
                                Ext.Msg.show({
                                    title: 'Error',
                                    msg: 'Jumlah paket melebihi jumlah stok',
                                    modal: true,
                                    icon: Ext.Msg.ERROR,
                                    buttons: Ext.Msg.OK,
                                    fn: function(btn){
                                        if (btn == 'ok' && fe.errMsg == 'Session Expired') {
                                            window.location = '<?= site_url("auth/login") ?>';
                                        }
                                    }
                                });
                                this.setValue(0);
                            } else {
                                Ext.getCmp('grid_mbp_rp_total').setValue(totalPaket);
                            }
                        });
                    }
                }
            }
        },{
            xtype: 'numbercolumn',
            header: 'Qty OH',
            dataIndex: 'qty_oh',
            width: 75,
            align: 'center',
            sortable: true,
            format: '0,0',
            editor: {
                xtype: 'numberfield',
                id: 'grid_mbp_qty_oh',
                readOnly: true,
                fieldClass: 'readonly-input',
                selectOnFocus: true
            }
        },{
            xtype: 'numbercolumn',
            header: 'COGS (exc. PPN)',
            dataIndex: 'rp_harga',
            width: 100,
            align: 'center',
            sortable: true,
            format: '0,0',
            editor: {
                xtype: 'numberfield',
                id: 'grid_mbp_harga',
                readOnly: true,
                fieldClass: 'readonly-input',
                //allowBlank: false,
                selectOnFocus: true
            }
        },{
            xtype: 'numbercolumn',
            header: 'Total',
            dataIndex: 'rp_total',
            width: 100,
            align: 'center',
            sortable: true,
            format: '0,0',
            editor: {
                xtype: 'numberfield',
                id: 'grid_mbp_rp_total',
                //allowBlank: false,
                selectOnFocus: true
            }
        }]
    });

    gridMBPBarangPaket.getSelectionModel().on('selectionchange', function(sm){
        gridMBPBarangPaket.removeBtn.setDisabled(sm.getCount() < 1);
    });

    var strMasterBarangPaket = new Ext.data.Store({
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
                'rp_cogs',
                'rp_cogs_dist',
                'rp_het_harga_beli',
                'koreksi_ke'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("barang_paket/get_rows") ?>',
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
    //------------ GRID PRODUK PAKET ----------------

    //------------ FORM UTAMA -----------------------
    var masterBarangPaket = new Ext.FormPanel({
        id: 'mst_barang_paket',
        border: false,
        frame: true,
        autoScroll:true,
        anchor: '90%',
        items: [
            {
                title: 'Pilih Produk dan Supplier',
                xtype:'fieldset',
                autoheight: true,
                anchor: '95%',
                items: [
                    {
                        layout: 'column',
                        items: [
                            {
                                layout: 'form',
                                border: false,
                                labelWidth: 100,
                                columnWidth: .4,
                                defaults: {
                                    labelSeparator: ''
                                },
                                items: [
                                    {
                                        xtype : 'compositefield',
                                        anchor: '90%',
                                        msgTarget: 'side',
                                        fieldLabel: 'Kd Produk',
                                        items : [comboMBPProduk]
                                    }
                                ]
                            }, {
                                layout: 'form',
                                border: false,
                                labelWidth: 1,
                                columnWidth: .6,
                                defaults: {
                                    labelSeparator: ''
                                },
                                items: [
                                    {
                                        xtype: 'hidden',
                                        name: 'kd_produk_paket',
                                        id: 'id_mbp_kd_produk'
                                    }, {
                                        xtype: 'textfield',
                                        fieldLabel: '',
                                        name: 'nama_produk',
                                        readOnly:true,
                                        fieldClass:'readonly-input',
                                        id: 'id_mbp_info_nama_produk',
                                        anchor: '90%',
                                        value:''
                                    }
                                ]
                            }
                        ]
                    },{
                        layout: 'column',
                        items: [
                            {
                                layout: 'form',
                                border: false,
                                labelWidth: 100,
                                columnWidth: .4,
                                defaults: {
                                    labelSeparator: ''
                                },
                                items: [
                                    {
                                        xtype : 'compositefield',
                                        anchor: '90%',
                                        msgTarget: 'side',
                                        fieldLabel: 'Kd Supplier',
                                        items : [comboPaketSupplier]
                                    }
                                ]
                            }, {
                                layout: 'form',
                                border: false,
                                labelWidth: 1,
                                columnWidth: .6,
                                defaults: {
                                    labelSeparator: ''
                                },
                                items: [
                                    {
                                        xtype: 'textfield',
                                        fieldLabel: '',
                                        name: 'nama_supplier',
                                        readOnly:true,
                                        fieldClass:'readonly-input',
                                        id: 'id_paket_nama_supplier',
                                        anchor: '90%',
                                        value:''
                                    }
                                ]
                            }
                        ]
                    }
                ]
            },
            {
                title: 'Infomasi Produk',
                xtype:'fieldset',
                anchor: '95%',
                autoheight: true,
                collapsed: false,
                collapsible: true,
                items: [
                    {
                        layout: 'column',
                        items: [
                            {
                                layout: 'form',
                                border: false,
                                labelWidth: 120,
                                columnWidth: .5,
                                defaults: {
                                    labelSeparator: ''
                                },
                                items: [
                                    {
                                        xtype: 'textfield',
                                        fieldLabel: 'Kategori 1',
                                        name: 'kategori1',
                                        readOnly:true,
                                        fieldClass:'readonly-input',
                                        id: 'id_mbp_info_kategori1',
                                        anchor: '90%',
                                        value:''
                                    }, {
                                        xtype: 'textfield',
                                        fieldLabel: 'Kategori 2',
                                        name: 'kategori2',
                                        readOnly:true,
                                        fieldClass:'readonly-input',
                                        id: 'id_mbp_info_kategori2',
                                        anchor: '90%',
                                        value:''
                                    }, {
                                        xtype: 'textfield',
                                        fieldLabel: 'Kategori 3',
                                        name: 'kategori3',
                                        readOnly:true,
                                        fieldClass:'readonly-input',
                                        id: 'id_mbp_info_kategori3',
                                        anchor: '90%',
                                        value:''
                                    }, {
                                        xtype: 'textfield',
                                        fieldLabel: 'Kategori 4',
                                        name: 'kategori4',
                                        readOnly:true,
                                        fieldClass:'readonly-input',
                                        id: 'id_mbp_info_kategori4',
                                        anchor: '90%',
                                        value:''
                                    }, {
                                        xtype: 'hidden',
                                        name: 'kd_peruntukan',
                                        id: 'id_mbp_kd_peruntukan'
                                    }, {
                                        xtype: 'textfield',
                                        fieldLabel: 'Peruntukan',
                                        name: 'peruntukan',
                                        readOnly:true,
                                        fieldClass:'readonly-input',
                                        id: 'id_mbp_info_peruntukan',
                                        anchor: '90%',
                                        value:''
                                    }, {
                                        xtype: 'textfield',
                                        fieldLabel: 'Konsinyasi',
                                        name: 'kosinyasi',
                                        readOnly:true,
                                        fieldClass:'readonly-input',
                                        id: 'id_mbp_info_kosinyasi',
                                        anchor: '90%',
                                        value:''
                                    }, {
                                        xtype: 'textfield',
                                        fieldLabel: 'Status Purchase',
                                        name: 'aktif_purchase',
                                        readOnly:true,
                                        fieldClass:'readonly-input',
                                        id: 'id_mbp_info_aktif_purchase',
                                        anchor: '90%',
                                        value:''
                                    }, {
                                        xtype: 'textfield',
                                        fieldLabel: 'Status aktif',
                                        name: 'aktif',
                                        readOnly:true,
                                        fieldClass:'readonly-input',
                                        id: 'id_mbp_info_aktif',
                                        anchor: '90%',
                                        value:''
                                    }
                                ]
                            }, {
                                layout: 'form',
                                border: false,
                                labelWidth: 120,
                                columnWidth: .5,
                                defaults: {
                                    labelSeparator: ''
                                },
                                items: [
                                    {
                                        xtype: 'textfield',
                                        fieldLabel: 'Kd. Produk Lama',
                                        name: 'kd_produk_lama',
                                        readOnly:true,
                                        fieldClass:'readonly-input',
                                        id: 'id_mbp_info_kd_produk_lama',
                                        anchor: '90%',
                                        value:''
                                    }, {
                                        xtype: 'textfield',
                                        fieldLabel: 'Kd. Produk Suplier',
                                        name: 'kd_produk_supp',
                                        readOnly:true,
                                        fieldClass:'readonly-input',
                                        id: 'id_mbp_info_kd_produk_supp',
                                        anchor: '90%',
                                        value:''
                                    }, {
                                        xtype: 'textfield',
                                        fieldLabel: 'Satuan',
                                        name: 'satuan',
                                        readOnly:true,
                                        fieldClass:'readonly-input',
                                        id: 'id_mbp_info_satuan',
                                        anchor: '90%',
                                        value:''
                                    }, {
                                        xtype: 'textfield',
                                        fieldLabel: 'Ukuran',
                                        name: 'ukuran',
                                        readOnly:true,
                                        fieldClass:'readonly-input',
                                        id: 'id_mbp_info_ukuran',
                                        anchor: '90%',
                                        value:''
                                    }, {
                                        xtype: 'textfield',
                                        fieldLabel: 'Min. Stok',
                                        name: 'min_stok',
                                        readOnly:true,
                                        fieldClass:'readonly-input',
                                        id: 'id_mbp_info_min_stok',
                                        anchor: '90%',
                                        value:''
                                    }, {
                                        xtype: 'textfield',
                                        fieldLabel: 'Max. Stok',
                                        name: 'max_stok',
                                        readOnly:true,
                                        fieldClass:'readonly-input',
                                        id: 'id_mbp_info_max_stok',
                                        anchor: '90%',
                                        value:''
                                    }, {
                                        xtype: 'textfield',
                                        fieldLabel: 'Minimal order',
                                        name: 'min_order',
                                        readOnly:true,
                                        fieldClass:'readonly-input',
                                        id: 'id_mbp_info_min_order',
                                        anchor: '90%',
                                        value:''
                                    }, {
                                        xtype: 'textfield',
                                        fieldLabel: 'Harga lepas',
                                        name: 'is_harga_lepas',
                                        readOnly:true,
                                        fieldClass:'readonly-input',
                                        id: 'id_mbp_info_harga_lepas',
                                        anchor: '90%',
                                        value:''
                                    }
                                ]
                            }
                        ]
                    }
                ]
            },
            {
                title: 'Barang Paket',
                xtype:'fieldset',
                autoheight: true,
                anchor: '95%',
                items: [
                    {
                        layout: 'column',
                        items: [
                            {
                                columnWidth: .3,
                                layout: 'form',
                                border: false,
                                labelWidth: 100,
                                defaults: { labelSeparator: '' },
                                items: [
                                    {
                                        xtype: 'numberfield',
                                        fieldLabel: 'Jumlah Paket',
                                        name: 'jum_paket',
                                        id: 'id_mbp_jum_paket',
                                        maxLength: 11,
                                        style: 'text-align:right;',
                                        format: '0,0',
                                        value: 0,
                                        width: 100,
                                        anchor: '83.5%'
                                    }
                                ]
                            }, {
                                columnWidth: .7,
                                layout: 'form',
                                border: false,
                                labelWidth: 100,
                                defaults: { labelSeparator: '' },
                                items: [
                                    {
                                        xtype : 'compositefield',
                                        msgTarget: 'side',
                                        fieldLabel: 'Masa Berlaku',
                                        items : [
                                            {
                                                xtype: 'datefield',
                                                name: 'tgl_berlaku_dari',
                                                allowBlank:true,
                                                format:'Y-m-d',
                                                editable:false,
                                                id: 'id_mbp_tgl_berlaku_dari'
                                            },{
                                                xtype: 'displayfield',
                                                value: 'S/D',
                                                style: 'padding-left:40px',
                                                width: 80
                                            },{
                                                xtype: 'datefield',
                                                name: 'tgl_berlaku_sampai',
                                                allowBlank:true,
                                                format:'Y-m-d',
                                                editable:false,
                                                minValue: new Date(),
                                                id: 'id_mbp_tgl_berlaku_sampai'
                                            }
                                        ]
                                    }
                                ]
                            }
                        ]
                    },
                    gridMBPBarangPaket,
                    {
                        xtype: 'numberfield',
                        fieldLabel: 'Qty Paket',
                        name: 'qty_paket',
                        id: 'id_mbp_qty_paket',
                        maxLength: 11,
                        style: 'text-align:right;',
                        fieldClass: 'readonly-input',
                        format: '0,0',
                        readOnly: true,
                        value: 0,
                        width: 100,
                        anchor: '25%'
                    },{
                        xtype: 'numberfield',
                        fieldLabel: 'Rp. Total COGS (exc. PPN)',
                        name: 'rp_total_paket',
                        id: 'id_mbp_rp_total_paket',
                        maxLength: 11,
                        style: 'text-align:right;',
                        fieldClass: 'readonly-input',
                        readOnly: true,
                        value: 0,
                        width: 100,
                        anchor: '25%',
                        renderer: function (val) {
                            return "Rp. " + Number(val).toLocaleString('id');
                        }
                    }
                ]
            }
        ],
        buttons: [
            {
                text: 'Save',
                id: 'id_mbp_save_button',
                handler: function(){
                    var detail = new Array();
                    strMBPProdukPaket.each(function(node){
                        detail.push(node.data);
                    });
                    Ext.getCmp('mst_barang_paket').getForm().submit({
                        url: '<?= site_url("barang_paket/update_row") ?>',
                        scope: this,
                        params: {
                            detail: Ext.util.JSON.encode(detail)
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

                            clearFormMBP();
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
                    clearFormMBP();
                }
            }
        ]
    });
    //------------ FORM UTAMA -----------------------

    function clearFormMBP(){
        Ext.getCmp('id_mbp_info_nama_produk').setValue('');
        Ext.getCmp('id_mbp_info_kd_produk_lama').setValue('');
        Ext.getCmp('id_mbp_info_kd_produk_supp').setValue('');
        Ext.getCmp('id_mbp_info_satuan').setValue('');
        Ext.getCmp('id_mbp_info_ukuran').setValue('');
        Ext.getCmp('id_mbp_info_kategori1').setValue('');
        Ext.getCmp('id_mbp_info_kategori2').setValue('');
        Ext.getCmp('id_mbp_info_kategori3').setValue('');
        Ext.getCmp('id_mbp_info_kategori4').setValue('');
        Ext.getCmp('id_mbp_info_peruntukan').setValue('');
        Ext.getCmp('id_mbp_info_kosinyasi').setValue('');
        Ext.getCmp('id_mbp_info_aktif_purchase').setValue('');
        Ext.getCmp('id_mbp_info_aktif').setValue('');
        Ext.getCmp('id_mbp_info_harga_lepas').setValue('');
        Ext.getCmp('id_mbp_info_min_stok').setValue('');
        Ext.getCmp('id_mbp_info_max_stok').setValue('');
        Ext.getCmp('id_mbp_info_min_order').setValue('');
        Ext.getCmp('id_paket_combo_supplier').setValue('');
        Ext.getCmp('id_paket_nama_supplier').setValue('');

        Ext.getCmp('id_mbp_jum_paket').setValue(0);
        Ext.getCmp('id_mbp_tgl_berlaku_dari').setValue('');
        Ext.getCmp('id_mbp_tgl_berlaku_sampai').setValue('');

        Ext.getCmp('id_mbp_qty_paket').setValue(0);
        Ext.getCmp('id_mbp_rp_total_paket').setValue(0);

        strComboMBPProduk.removeAll();
        strMasterBarangPaket.removeAll();
        strMBPProdukGrid.removeAll();
        strMBPProdukPaket.removeAll();

        Ext.getCmp('mbp_kd_produk').setValue('');
        Ext.getCmp('id_mbp_jum_paket').setDisabled(false);
        gridMBPBarangPaket.addBtn.setDisabled(true);
    }
</script>
