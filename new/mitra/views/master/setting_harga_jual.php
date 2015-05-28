<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
/**
 * Combo kategori1
 **/
    var strSBPComboKategori1Kons = new Ext.data.Store({
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
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var sbpComboKategori1Kons = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 1 <span class="asterix">*</span>',
        id: 'id_sbp_combo_kategori1_kons',
        store: strSBPComboKategori1Kons,
        valueField: 'kd_kategori1',
        displayField: 'nama_kategori1',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        width: 250,
        anchor: '90%',
        hiddenName: 'kd_kategori1_kons',
        emptyText: 'Pilih kategori 1',
        listeners: {
            'select': function(combo, records) {
                var kdbrg_cbkategori1 = sbpComboKategori1Kons.getValue();
                sbpComboKategori2Kons.setValue();
                sbpComboKategori2Kons.store.proxy.conn.url = '<?= site_url("kategori3/get_kategori2") ?>/' + kdbrg_cbkategori1;
                sbpComboKategori2Kons.store.reload();
            }
        }
    });

/**
 * Combo kategori2
 **/
    var strSBPComboKategori2Kons = new Ext.data.Store({
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

    var sbpComboKategori2Kons = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 2 <span class="asterix">*</span>',
        id: 'id_sbp_combo_kategori2_kons',
        mode: 'local',
        store: strSBPComboKategori2Kons,
        valueField: 'kd_kategori2',
        displayField: 'nama_kategori2',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        width: 250,
        anchor: '90%',
        hiddenName: 'kd_kategori2_kons',
        emptyText: 'Pilih kategori 2',
        listeners: {
            select: function(combo, records) {
                var kd_brg_cbkategori1 = sbpComboKategori1Kons.getValue();
                var kd_brg_cbkategori2 = this.getValue();
                sbpComboKategori3Kons.setValue();
                sbpComboKategori3Kons.store.proxy.conn.url = '<?= site_url("kategori4/get_kategori3") ?>/' + kd_brg_cbkategori1 +'/'+ kd_brg_cbkategori2;
                sbpComboKategori3Kons.store.reload();
            }
        }
    });

/**
 * Combo kategori3
 **/
    var strSBPComboKategori3Kons = new Ext.data.Store({
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
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var sbpComboKategori3Kons = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 3 <span class="asterix">*</span>',
        id: 'id_sbp_combo_kategori3_kons',
        mode: 'local',
        store: strSBPComboKategori3Kons,
        valueField: 'kd_kategori3',
        displayField: 'nama_kategori3',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        width: 250,
        anchor: '90%',
        hiddenName: 'kd_kategori3_kons',
        emptyText: 'Pilih kategori 3',
        listeners: {
            select: function(combo, records) {
                var kd_brg_cbkategori1 = sbpComboKategori1Kons.getValue();
                var kd_brg_cbkategori2 = sbpComboKategori2Kons.getValue();
                var kd_brg_cbkategori3 = this.getValue();
                sbpComboKategori4Kons.setValue();
                sbpComboKategori4Kons.store.proxy.conn.url = '<?= site_url("master_barang/get_kategori4") ?>/' + kd_brg_cbkategori1 +'/'+ kd_brg_cbkategori2 +'/'+ kd_brg_cbkategori3;
                sbpComboKategori4Kons.store.reload();
            }
        }
    });

/**
 * Combo kategori4
 **/
    var strSBPKategori4 = new Ext.data.Store({
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

    var sbpComboKategori4Kons = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 4 <span class="asterix">*</span>',
        id: 'id_sbp_combo_kategori4_kons',
        mode: 'local',
        store: strSBPKategori4,
        valueField: 'kd_kategori4',
        displayField: 'nama_kategori4',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        width: 250,
        anchor: '90%',
        hiddenName: 'kd_kategori4_kons',
        emptyText: 'Pilih kategori 4'
    });

/**
 * Combo kategori1 member
 **/
    var strSBPComboKategori1Member = new Ext.data.Store({
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
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var sbpComboKategori1Member = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 1 <span class="asterix">*</span>',
        id: 'id_sbp_combo_kategori1_member',
        store: strSBPComboKategori1Member,
        valueField: 'kd_kategori1',
        displayField: 'nama_kategori1',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        width: 250,
        anchor: '90%',
        hiddenName: 'kd_kategori1_member',
        emptyText: 'Pilih kategori 1',
        listeners: {
            'select': function(combo, records) {
                var kdbrg_cbkategori1 = sbpComboKategori1Member.getValue();
                sbpComboKategori2Member.setValue();
                sbpComboKategori2Member.store.proxy.conn.url = '<?= site_url("kategori3/get_kategori2") ?>/' + kdbrg_cbkategori1;
                sbpComboKategori2Member.store.reload();
            }
        }
    });

/**
 * Combo kategori2 member
 **/
    var strSBPComboKategori2Member = new Ext.data.Store({
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

    var sbpComboKategori2Member = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 2 <span class="asterix">*</span>',
        id: 'id_sbp_combo_kategori2_member',
        mode: 'local',
        store: strSBPComboKategori2Member,
        valueField: 'kd_kategori2',
        displayField: 'nama_kategori2',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        width: 250,
        anchor: '90%',
        hiddenName: 'kd_kategori2_member',
        emptyText: 'Pilih kategori 2',
        listeners: {
            select: function(combo, records) {
                var kd_brg_cbkategori1 = sbpComboKategori1Member.getValue();
                var kd_brg_cbkategori2 = this.getValue();
                sbpComboKategori3Member.setValue();
                sbpComboKategori3Member.store.proxy.conn.url = '<?= site_url("kategori4/get_kategori3") ?>/' + kd_brg_cbkategori1 +'/'+ kd_brg_cbkategori2;
                sbpComboKategori3Member.store.reload();
            }
        }
    });

/**
 * Combo kategori3 member
 **/
    var strSBPComboKategori3Member = new Ext.data.Store({
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
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var sbpComboKategori3Member = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 3 <span class="asterix">*</span>',
        id: 'id_sbp_combo_kategori3_member',
        mode: 'local',
        store: strSBPComboKategori3Member,
        valueField: 'kd_kategori3',
        displayField: 'nama_kategori3',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        width: 250,
        anchor: '90%',
        hiddenName: 'kd_kategori3_member',
        emptyText: 'Pilih kategori 3',
        listeners: {
            select: function(combo, records) {
                var kd_brg_cbkategori1 = sbpComboKategori1Member.getValue();
                var kd_brg_cbkategori2 = sbpComboKategori2Member.getValue();
                var kd_brg_cbkategori3 = this.getValue();
                sbpComboKategori4Member.setValue();
                sbpComboKategori4Member.store.proxy.conn.url = '<?= site_url("master_barang/get_kategori4") ?>/' + kd_brg_cbkategori1 +'/'+ kd_brg_cbkategori2 +'/'+ kd_brg_cbkategori3;
                sbpComboKategori4Member.store.reload();
            }
        }
    });

/**
 * Combo kategori4 member
 **/
    var strSBPKategori4 = new Ext.data.Store({
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

    var sbpComboKategori4Member = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 4 <span class="asterix">*</span>',
        id: 'id_sbp_combo_kategori4_member',
        mode: 'local',
        store: strSBPKategori4,
        valueField: 'kd_kategori4',
        displayField: 'nama_kategori4',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        width: 250,
        anchor: '90%',
        hiddenName: 'kd_kategori4_member',
        emptyText: 'Pilih kategori 4'
    });

/**
 * Pilih Produk
 **/
    var strSBPProduk = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_produk', 'nama_produk'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("setting_harga_jual/get_produk") ?>',
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

    var searchSBPProduk = new Ext.app.SearchField({
        store: strSBPProduk,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
    });

    var toolbarSBPProduk = new Ext.Toolbar({
        items: [searchSBPProduk]
    });

    var gridSBPProduk = new Ext.grid.GridPanel({
        store: strSBPProduk,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [ {
            header: 'Kode Produk',
            dataIndex: 'kd_produk',
            width: 90,
            sortable: true,
        }, {
            header: 'Nama Produk',
            dataIndex: 'nama_produk',
            width: 400,
            sortable: true,
        } ],
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.Ajax.request({
                        url: '<?= site_url("setting_harga_jual/get_row_kode_produk") ?>',
                        method: 'POST',
                        params: {
                            kd_produk: sel[0].get('kd_produk')
                        },
                        callback: function(opt, success, responseObj) {
                            console.log(success);
                            if(success) {
                                var res = Ext.util.JSON.decode(responseObj.responseText);
                                Ext.getCmp('id_sbp_kd_produk').setValue(res.data.kd_produk);
                                Ext.getCmp('id_sbp_nama_produk').setValue(res.data.nama_produk);
                                Ext.getCmp('id_sbp_is_bonus').setValue(res.data.is_bonus);
                                Ext.getCmp('id_sbp_tgl_start_bonus').setValue(res.data.tgl_start_bonus);
                                Ext.getCmp('id_sbp_tgl_end_bonus').setValue(res.data.tgl_end_bonus);

                                Ext.getCmp('id_sbp_qty_beli_kons').setValue(res.data.qty_beli_bonus);
                                Ext.getCmp('id_sbp_qty_beli_member').setValue(res.data.qty_beli_member);
                                Ext.getCmp('id_sbp_qty_bonus_kons').setValue(res.data.qty_bonus);
                                Ext.getCmp('id_sbp_qty_bonus_member').setValue(res.data.qty_member);
                                Ext.getCmp('id_sbp_is_kelipatan_kons').setValue(res.data.is_bonus_kelipatan);
                                Ext.getCmp('id_sbp_is_kelipatan_member').setValue(res.data.is_member_kelipatan);

                                Ext.getCmp('id_sbp_kd_produk_bonus').setValue(res.data.kd_produk_bonus);
                                Ext.getCmp('id_sbp_kd_produk_member').setValue(res.data.kd_produk_member);
                                Ext.getCmp('id_sbp_nama_produk_bonus').setValue(res.data.nama_produk_bonus);
                                Ext.getCmp('id_sbp_nama_produk_member').setValue(res.data.nama_produk_member);

                                Ext.getCmp('id_sbp_combo_kategori1_kons').setValue(res.data.kd_kategori1_bonus);
                                Ext.getCmp('id_sbp_combo_kategori2_kons').setValue(res.data.kd_kategori2_bonus);
                                Ext.getCmp('id_sbp_combo_kategori3_kons').setValue(res.data.kd_kategori3_bonus);
                                Ext.getCmp('id_sbp_combo_kategori4_kons').setValue(res.data.kd_kategori4_bonus);

                                Ext.getCmp('id_sbp_combo_kategori1_member').setValue(res.data.kd_kategori1_member);
                                Ext.getCmp('id_sbp_combo_kategori2_member').setValue(res.data.kd_kategori2_member);
                                Ext.getCmp('id_sbp_combo_kategori3_member').setValue(res.data.kd_kategori3_member);
                                Ext.getCmp('id_sbp_combo_kategori4_member').setValue(res.data.kd_kategori4_member);
                            }
                        }
                    });
                    menuSBPProduk.hide();
                }
            }
        },
        tbar: toolbarSBPProduk,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strSBPProduk,
            displayInfo: true
        })
    });


    var menuSBPProduk = new Ext.menu.Menu();
    menuSBPProduk.add(new Ext.Panel({
        title: 'Pilih Produk',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 500,
        height: 400,
        closeAction: 'hide',
        plain: true,
        items: [gridSBPProduk],
        buttons: [ {
            text: 'Close',
            handler: function() {
                menuSBPProduk.hide();
            }
        } ]
    }));

Ext.ux.TwinComboSBPProduk = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function() {
            //load store grid
            Ext.getCmp('id_sbp_grid_sender').setValue(this.id);
            strSBPProduk.load();
            menuSBPProduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

/**
 * Pilih Produk
 **/
    var strSBPProdukBonusKons = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_produk', 'nama_produk'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("setting_harga_jual/get_produk") ?>',
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

    var searchSBPProdukBonusKons = new Ext.app.SearchField({
        store: strSBPProdukBonusKons,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
    });

    var toolbarSBPProdukBonusKons = new Ext.Toolbar({
        items: [searchSBPProdukBonusKons]
    });

    var gridSBPProdukBonusKons = new Ext.grid.GridPanel({
        store: strSBPProdukBonusKons,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [ {
            header: 'Kode Produk',
            dataIndex: 'kd_produk',
            width: 90,
            sortable: true,
        }, {
            header: 'Nama Produk',
            dataIndex: 'nama_produk',
            width: 400,
            sortable: true,
        } ],
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.Ajax.request({
                        url: '<?= site_url("setting_harga_jual/get_row_kode_produk") ?>',
                        method: 'POST',
                        params: {
                            kd_produk: sel[0].get('kd_produk')
                        },
                        callback: function(opt, success, responseObj) {
                            console.log(success);
                            if(success) {
                                var res = Ext.util.JSON.decode(responseObj.responseText);
                                Ext.getCmp('id_sbp_kd_produk_bonus').setValue(res.data.kd_produk);
                                Ext.getCmp('id_sbp_nama_produk_bonus').setValue(res.data.nama_produk);
                            }
                        }
                    });
                    menuSBPProdukBonusKons.hide();
                }
            }
        },
        tbar: toolbarSBPProdukBonusKons,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strSBPProdukBonusKons,
            displayInfo: true
        })
    });


    var menuSBPProdukBonusKons = new Ext.menu.Menu();
    menuSBPProdukBonusKons.add(new Ext.Panel({
        title: 'Pilih Produk',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 500,
        height: 400,
        closeAction: 'hide',
        plain: true,
        items: [gridSBPProdukBonusKons],
        buttons: [ {
            text: 'Close',
            handler: function() {
                menuSBPProdukBonusKons.hide();
            }
        } ]
    }));

Ext.ux.TwinComboSBPProdukBonusKons = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function() {
            //load store grid
            Ext.getCmp('id_sbp_grid_sender').setValue(this.id);
            strSBPProdukBonusKons.load();
            menuSBPProdukBonusKons.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

/**
 * Pilih Produk
 **/
    var strSBPProdukBonusMem = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_produk', 'nama_produk'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("setting_harga_jual/get_produk") ?>',
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

    var searchSBPProdukBonusMem = new Ext.app.SearchField({
        store: strSBPProdukBonusMem,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
    });

    var toolbarSBPProdukBonusMem = new Ext.Toolbar({
        items: [searchSBPProdukBonusMem]
    });

    var gridSBPProdukBonusMem = new Ext.grid.GridPanel({
        store: strSBPProdukBonusMem,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [ {
            header: 'Kode Produk',
            dataIndex: 'kd_produk',
            width: 90,
            sortable: true,
        }, {
            header: 'Nama Produk',
            dataIndex: 'nama_produk',
            width: 400,
            sortable: true,
        } ],
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.Ajax.request({
                        url: '<?= site_url("setting_harga_jual/get_row_kode_produk") ?>',
                        method: 'POST',
                        params: {
                            kd_produk: sel[0].get('kd_produk')
                        },
                        callback: function(opt, success, responseObj) {
                            console.log(success);
                            if(success) {
                                var res = Ext.util.JSON.decode(responseObj.responseText);
                                Ext.getCmp('id_sbp_kd_produk_member').setValue(res.data.kd_produk);
                                Ext.getCmp('id_sbp_nama_produk_member').setValue(res.data.nama_produk);
                            }
                        }
                    });
                    menuSBPProdukBonusMem.hide();
                }
            }
        },
        tbar: toolbarSBPProdukBonusMem,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strSBPProdukBonusMem,
            displayInfo: true
        })
    });


    var menuSBPProdukBonusMem = new Ext.menu.Menu();
    menuSBPProdukBonusMem.add(new Ext.Panel({
        title: 'Pilih Produk',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 500,
        height: 400,
        closeAction: 'hide',
        plain: true,
        items: [gridSBPProdukBonusMem],
        buttons: [ {
            text: 'Close',
            handler: function() {
                menuSBPProdukBonusMem.hide();
            }
        } ]
    }));

Ext.ux.TwinComboSBPProdukBonusMem = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function() {
            //load store grid
            Ext.getCmp('id_sbp_grid_sender').setValue(this.id);
            strSBPProdukBonusMem.load();
            menuSBPProdukBonusMem.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
/**
 * Main Form
 **/
    var settinghargajual = new Ext.FormPanel({
        id: 'settinghargajual',
        border: false,
        frame: true,
        autoScroll: true,
        labelWidth: 100,
        items: [ {
            xtype: 'hidden',
            name: 'sender',
            id: 'id_sbp_grid_sender',
        }, {
            xtype: 'fieldset',
            autoheight: true,
            title: 'Produk',
            anchor: '90%',
            items: [ {
                xtype: 'hidden',
                name: 'kd_bonus_sales',
                id: 'id_sbp_bonus_sales'
            }, {
                xtype: 'hidden',
                name: 'koreksi_ke',
                id: 'id_sbp_koreksi',
            }, {
                xtype: 'compositefield',
                anchor: '90%',
                msgTarget: 'side',
                fieldLabel: 'Kode Produk',
                width: 400,
                items: [
                new Ext.ux.TwinComboSBPProduk({
                    id: 'id_sbp_kd_produk',
                    store: strSBPProduk,
                    valueField: 'kd_produk',
                    displayField: 'kd_produk',
                    typeAhead: true,
                    allowBlank: false,
                    editable: false,
                    hiddenName: 'kd_produk',
                    emptyText: 'Pilih Kode Produk',
                    listeners: {
                        'expand': function() {
                            strSBPProduk.load();
                        }
                    }
                }),
                {
                    xtype: 'displayfield',
                    value: 'Nama Produk',
                    flex: 1,
                    width: 130,
                    style: 'padding-left:30px',
                }, {
                    xtype: 'textfield',
                    name: 'nama_produk',
                    fieldClass: 'readonly-input',
                    readOnly: true,
                    id: 'id_sbp_nama_produk',
                    flex: 1,
                    anchor: '90%'
                } ]
            } ]
        }, {
            xtype:'fieldset',
            autoheight: true,
            title: 'Bonus',
            anchor: '90%',
            defaults: { labelSeparator: ''},
            items:[{
                xtype : 'compositefield',
                msgTarget: 'side',
                fieldLabel: 'Konsumen',
                items : [ {
                    xtype: 'displayfield',
                    value: 'Member',
                    style: 'padding-left:295px;',
                    width: 250
                } ]
            }, {
                xtype : 'compositefield',
                msgTarget: 'side',
                fieldLabel: 'Aktif',
                items : [
                new Ext.form.Checkbox({
                    xtype: 'checkbox',
                    boxLabel:'Ya',
                    name:'is_bonus',
                    id:'id_sbp_is_bonus',
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
                    id: 'id_sbp_tgl_start_bonus',
                    format: 'd-M-Y',
                    width: 170
                }, {
                    xtype: 'displayfield',
                    value: 's.d',
                    width: 20
                }, {
                    xtype: 'datefield',
                    name: 'tgl_end_bonus',
                    id: 'id_sbp_tgl_end_bonus',
                    format: 'd-M-Y',
                    width: 170
                }]
            }, {
                xtype : 'compositefield',
                msgTarget: 'side',
                fieldLabel: 'Qty Beli',
                items : [ {
                    xtype: 'numericfield',
                    currencySymbol:'',
                    width: 250,
                    name : 'qty_beli_bonus',
                    allowBlank: false,
                    id: 'id_sbp_qty_beli_kons',
                    style: 'text-align:right;',
                    value: 0
                }, {
                    xtype: 'displayfield',
                    value: 'Qty Beli',
                    style: 'padding-left:40px;',
                    width: 130
                }, {
                    xtype: 'numericfield',
                    currencySymbol:'',
                    name : 'qty_beli_member',
                    allowBlank: false,
                    width: 250,
                    id: 'id_sbp_qty_beli_member',
                    style: 'text-align:right;',
                    value: 0,
                } ]
            }, {
                xtype : 'compositefield',
                msgTarget: 'side',
                fieldLabel: 'Qty Bonus',
                items : [ {
                    xtype: 'numberfield',
                    width: 250,
                    name : 'qty_bonus',
                    allowBlank: false,
                    id: 'id_sbp_qty_bonus_kons',
                    style: 'text-align:right;',
                    value: 0
                }, {
                    xtype: 'displayfield',
                    value: 'Qty Bonus',
                    style: 'padding-left:40px;',
                    width: 130
                }, {
                    xtype: 'numberfield',
                    name : 'qty_member',
                    allowBlank: false,
                    width: 250,
                    id : 'id_sbp_qty_bonus_member',
                    style: 'text-align:right;',
                    value: 0
                } ]
            }, {
                xtype : 'compositefield',
                msgTarget: 'side',
                fieldLabel: 'Kelipatan',
                items : [
                new Ext.form.Checkbox({
                    xtype: 'checkbox',
                    boxLabel:'Ya',
                    name:'is_bonus_kelipatan',
                    id:'id_sbp_is_kelipatan_kons',
                    inputValue: '1',
                    autoLoad : true,
                    width: 250
                }),
                {
                    xtype: 'displayfield',
                    value: 'Kelipatan',
                    style: 'padding-left:40px;',
                    width: 130,
                },
                new Ext.form.Checkbox({
                    xtype: 'checkbox',
                    boxLabel:'Ya',
                    name:'is_member_kelipatan',
                    id:'id_sbp_is_kelipatan_member',
                    inputValue: '1',
                    autoLoad : true,
                    width: 250
                }) ]
            }, {
                xtype:'fieldset',
                autoheight: true,
                title: 'Bonus by Kategori',
                checkboxToggle:true,
                collapsed: true,
                anchor: '90%',
                id: 'is_sbp_fs_kategori',
                items:[{
                    xtype : 'compositefield',
                    msgTarget: 'side',
                    fieldLabel: 'Kategori 1',
                    items:[
                        sbpComboKategori1Kons,
                        {
                            xtype: 'displayfield',
                            value: 'Kategori 1',
                            style: 'padding-left:40px;',
                            width: 130
                        },
                        sbpComboKategori1Member
                    ]
                }, {
                    xtype : 'compositefield',
                    msgTarget: 'side',
                    fieldLabel: 'Kategori 2',
                    items:[
                        sbpComboKategori2Kons,
                        {
                            xtype: 'displayfield',
                            value: 'Kategori 2',
                            style: 'padding-left:40px;',
                            width: 130
                        },
                        sbpComboKategori2Member
                    ]
                }, {
                    xtype : 'compositefield',
                    msgTarget: 'side',
                    fieldLabel: 'Kategori 3',
                    items:[
                        sbpComboKategori3Kons,
                        {
                            xtype: 'displayfield',
                            value: 'Kategori 3',
                            style: 'padding-left:40px;',
                            width: 130
                        },
                        sbpComboKategori3Member
                    ]
                }, {
                    xtype : 'compositefield',
                    msgTarget: 'side',
                    fieldLabel: 'Kategori 4',
                    items:[
                        sbpComboKategori4Kons,
                        {
                            xtype: 'displayfield',
                            value: 'Kategori 4',
                            style: 'padding-left:40px;',
                            width: 130
                        },
                        sbpComboKategori4Member
                    ]
                }],
                listeners:{
                    collapse: function(n){
                        Ext.getCmp('is_sbp_fs_produk').expand();
                    },
                    expand: function(n){
                        Ext.getCmp('is_sbp_fs_produk').collapse();
                    }
                }
            }, {
                xtype:'fieldset',
                autoheight: true,
                checkboxToggle:true,
                collapsed: false,
                title: 'Bonus by Produk',
                anchor: '90%',
                id: 'is_sbp_fs_produk',
                items:[{
                }, {
                    xtype : 'compositefield',
                    msgTarget: 'side',
                    fieldLabel: 'Kode Produk',
                    items:[
                        new Ext.ux.TwinComboSBPProdukBonusKons({
                            id: 'id_sbp_kd_produk_bonus',
                            store: strSBPProduk,
                            valueField: 'kd_produk',
                            displayField: 'kd_produk',
                            typeAhead: true,
                            editable: false,
                            hiddenName: 'kd_produk_bonus',
                            emptyText: 'Kode Produk',
                            listeners: {
                                'expand': function() {
                                    strSBPProduk.load();
                                }
                            }
                        }),
                        {
                            xtype: 'displayfield',
                            value: '&nbsp;',
                            style: 'padding-left:40px;',
                            width: 90
                        }, {
                            xtype: 'displayfield',
                            value: 'Kode Produk',
                            style: 'padding-left:40px;',
                            width: 130
                        },
                        new Ext.ux.TwinComboSBPProdukBonusMem({
                            id: 'id_sbp_kd_produk_member',
                            store: strSBPProduk,
                            valueField: 'kd_produk',
                            displayField: 'kd_produk',
                            typeAhead: true,
                            editable: false,
                            hiddenName: 'kd_produk_member',
                            emptyText: 'Kode Produk',
                            listeners: {
                                'expand': function() {
                                    strSBPProduk.load();
                                }
                            }
                        })
                    ]
                }, {
                    xtype : 'compositefield',
                    msgTarget: 'side',
                    fieldLabel: 'Bonus Konsumen',
                    items:[{
                        xtype: 'textfield',
                        width: 250,
                        name : 'nama_produk_bonus_kons',
                        readOnly: true,
                        id: 'id_sbp_nama_produk_bonus',
                        fieldClass: 'readonly-input'
                    }, {
                        xtype: 'displayfield',
                        value: 'Bonus Member',
                        style: 'padding-left:40px;',
                        width: 130
                    }, {
                        xtype: 'textfield',
                        width: 250,
                        name : 'nama_produk_bonus_member',
                        readOnly: true,
                        id: 'id_sbp_nama_produk_member',
                        fieldClass: 'readonly-input'
                    }]
                }],
                listeners:{
                    collapse: function(n){
                        Ext.getCmp('is_sbp_fs_kategori').expand();
                    },
                    expand: function(n){
                        Ext.getCmp('is_sbp_fs_kategori').collapse();
                    }
                }

            } ]
        } ],
        buttons: [ {
            text: 'Save',
            handler: function() {
                Ext.getCmp('settinghargajual').getForm().submit({
                    url: '<?= site_url("setting_harga_jual/update_row") ?>',
                    scope: this,
                    waitMsg: 'Saving Data...',
                    success: function(form, action) {
                        Ext.Msg.show({
                            title: 'Success',
                            msg: 'Form submitted successfully',
                            modal: true,
                            icon: Ext.Msg.INFO,
                            buttons: Ext.Msg.OK
                        });
                        clearSettingHargaJual();
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
                                if (btn == 'ok' && fe.errMsg == 'Session Expired') {
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
                clearSettingHargaJual();
            }
        } ]
    });

function clearSettingHargaJual() {
    Ext.getCmp('settinghargajual').getForm().reset();
}
</script>
