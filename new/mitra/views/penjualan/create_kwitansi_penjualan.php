<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
//============================================start combo kode pelanggan=================================================================

    /**
     * deklarasi store kode pelanggan
     */
    var storeComboKdPelanggan = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_pelanggan', 'nama_pelanggan', 'npwp'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("create_kwitansi_penjualan_controller/finalGetDataPelanggan") ?>',
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
    /**
     * deklarasi search grid pelanggan
     */
    var searchGridPelanggan = new Ext.app.SearchField({
        store: storeComboKdPelanggan,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_search_grid_pelanggan'
    });

    var smGridPelangganCreatekwitansi = new Ext.grid.CheckboxSelectionModel();

    /**
     * deklarasi grid pelanggan
     */
    var gridPelanggan = new Ext.grid.GridPanel({
        store: storeComboKdPelanggan,
        stripeRows: true,
        frame: true,
        border: true,
        sm: smGridPelangganCreatekwitansi,
        columns: [{
                header: 'Kode Pelanggan',
                dataIndex: 'kd_pelanggan',
                width: 120,
                sortable: true
            }, {
                header: 'Nama Pelanggan',
                dataIndex: 'nama_pelanggan',
                width: 120,
                sortable: true
            }, {
                header: 'NPWP',
                dataIndex: 'npwp',
                width: 150,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchGridPelanggan]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: storeComboKdPelanggan,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_combo_pelanggan_create_kwitansi').setValue(sel[0].get('kd_pelanggan'));
                    Ext.getCmp('id_txt_kd_nama_pelanggan').setValue(sel[0].get('nama_pelanggan'));
                    Ext.getCmp('id_txt_terima_dari').setValue(sel[0].get('nama_pelanggan'));
                    menuPelanggan.hide();
                }
            }
        }
    });
    /**
     * deklarasi menu pelanggan
     */
    var menuPelanggan = new Ext.menu.Menu();
    menuPelanggan.add(new Ext.Panel({
        title: 'Pilih Pelanggan',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridPelanggan],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menuPelanggan.hide();
                }
            }]
    }));
    /**
     * deklarasi twin combo pelanggan
     * @returns {undefined} */
    Ext.ux.TwincomboKdPelanggan = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            storeComboKdPelanggan.load();
            menuPelanggan.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    menuPelanggan.on('hide', function() {
        var sf = Ext.getCmp('id_search_grid_pelanggan').getValue();
        if (sf !== '') {
            Ext.getCmp('id_search_grid_pelanggan').setValue('');
            searchGridPelanggan.onTrigger2Click();
        }
    });
    var comboPelanggan = new Ext.ux.TwincomboKdPelanggan({
        fieldLabel: 'Pelanggan <span class="asterix">*</span>',
        id: 'id_combo_pelanggan_create_kwitansi',
        store: storeComboKdPelanggan,
        mode: 'local',
        valueField: 'kd_pelanggan',
        displayField: 'kd_pelanggan',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '95%',
        width: 200,
        hiddenName: 'kd_pelanggan',
        emptyText: 'Pilih Pelanggan'
    });
//==============================================end combo kode pelanggan==================================================================

//==============================================start combo uang muka======================================================================


    /**
     * deklarasi store combo uang muka
     */
    var storeComboUangMuka = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_bayar', 'kd_jenis_bayar', 'rp_bayar', 'jumlah_uang', 'terbilang_bayar', 'keterangan_pembayaran'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("create_kwitansi_penjualan_controller/finalGetDataUangMuka") ?>',
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
    /**
     * deklarasi search grid uang muka
     */
    var searchGridUangMuka = new Ext.app.SearchField({
        store: storeComboUangMuka,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_search_grid_uang_muka'
    });
    /**
     * deklarasi grid pelanggan
     */
    var gridUangMuka = new Ext.grid.GridPanel({
        store: storeComboUangMuka,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'No Bayar',
                dataIndex: 'no_bayar',
                width: 120,
                sortable: true
            }, {
                header: 'Kode Jenis Bayar',
                dataIndex: 'kd_jenis_bayar',
                width: 120,
                sortable: true
            }, {
                header: 'Rp Bayar',
                dataIndex: 'rp_bayar',
                width: 150,
                sortable: true
            }, {
                header: 'Terbilang',
                dataIndex: 'terbilang_bayar',
                width: 160,
                hidden: true,
                sortable: true
            }, {
                header: 'Terbilang',
                dataIndex: 'keterangan_pembayaran',
                width: 160,
                hidden: true,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchGridUangMuka]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: storeComboUangMuka,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_combo_uang_muka').setValue(sel[0].get('no_bayar'));
                    Ext.getCmp('id_rp_total').reset();
                    Ext.getCmp('id_rp_total').setValue(sel[0].get('rp_bayar'));
                    Ext.getCmp('id_terbilang_total').reset();
                    Ext.getCmp('id_terbilang_total').setValue(sel[0].get('terbilang_bayar'));
                    Ext.getCmp('id_keterangan_pembayaran').setValue(sel[0].get('keterangan_pembayaran'));
                    menuUangMuka.hide();
                }
            }
        }
    });
    /**
     * deklarasi menu uang muka
     */
    var menuUangMuka = new Ext.menu.Menu();
    menuUangMuka.add(new Ext.Panel({
        title: 'Pilih Uang Muka',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridUangMuka],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menuUangMuka.hide();
                }
            }]
    }));
    /**
     * deklarasi twin combo uang muka
     * @returns {undefined} */
    Ext.ux.TwincomboUangMuka = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            storeComboUangMuka.load();
            menuUangMuka.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    var comboUangMuka = new Ext.ux.TwincomboUangMuka({
        fieldLabel: 'Uang Muka <span class="asterix">*</span>',
        id: 'id_combo_uang_muka',
        store: storeComboUangMuka,
        mode: 'local',
        valueField: 'no_bayar',
        displayField: 'no_bayar',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '95%',
        width: 200,
        hiddenName: 'no_bayar',
        emptyText: 'Pilih Uang Muka'
    });
//======================================end of combo uang muka===========================================================================================    
//==============================================start combo faktur penjualan======================================================================
    /**
     * deklarasi store combo faktur jual
     */
    var storeComboPembayaranPiutang_ckp = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_pembayaran_piutang', 'tgl_bayar', 'nom_fak', 'rp_faktur', 'rp_bayar', 'terbilang_bayar', 'keterangan', 'keterangan_bayar'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("create_kwitansi_penjualan_controller/finalGetDataFakturJual") ?>',
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
    /**
     * deklarasi search grid faktur jual
     */
    var searchGridFakturJual = new Ext.app.SearchField({
        store: storeComboPembayaranPiutang_ckp,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_search_grid_faktur_jual'
    });
    /**
     * deklarasi grid pelanggan
     */
    var gridFakturJual = new Ext.grid.GridPanel({
        store: storeComboPembayaranPiutang_ckp,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'No Bukti',
                dataIndex: 'no_pembayaran_piutang',
                width: 120,
                sortable: true
            }, {
                header: 'Tgl Bayar',
                dataIndex: 'tgl_bayar',
                width: 120,
                sortable: true
            }, {
                header: 'No Faktur',
                dataIndex: 'nom_fak',
                width: 120,
                sortable: true
            }, {
                header: 'Rp Faktur',
                dataIndex: 'rp_faktur',
                width: 150,
                sortable: true
            }, {
                header: 'Rp Bayar',
                dataIndex: 'rp_bayar',
                width: 160,
                sortable: true
            }, {
                header: 'Terbilang',
                dataIndex: 'terbilang_bayar',
                width: 160,
                hidden: true,
                sortable: true
            }, {
                header: 'Keterangan',
                dataIndex: 'keterangan_bayar',
                width: 160,
                hidden: true,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchGridFakturJual]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: storeComboPembayaranPiutang_ckp,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_combo_faktur_jual').setValue(sel[0].get('no_pembayaran_piutang'));
                    Ext.getCmp('id_rp_total').reset();
                    Ext.getCmp('id_rp_total').setValue(sel[0].get('rp_bayar'));
                    Ext.getCmp('id_terbilang_total').reset();
                    Ext.getCmp('id_terbilang_total').setValue(sel[0].get('terbilang_bayar'));
                    Ext.getCmp('id_keterangan_pembayaran').setValue(sel[0].get('keterangan_bayar'));
                    menuUangMuka.hide();
                    menuFakturJual.hide();
                }
            }
        }
    });
    /**
     * deklarasi menu faktur jual
     */
    var menuFakturJual = new Ext.menu.Menu();
    menuFakturJual.add(new Ext.Panel({
        title: 'Pilih Faktur Jual',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridFakturJual],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menuFakturJual.hide();
                }
            }]
    }));
    /**
     * deklarasi twin combo uang muka
     * @returns {undefined} */
    Ext.ux.TwincomboFakturJual = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            storeComboPembayaranPiutang_ckp.load();
            menuFakturJual.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    var comboFakturJual = new Ext.ux.TwincomboFakturJual({
        fieldLabel: 'Faktur Jual <span class="asterix">*</span>',
        id: 'id_combo_faktur_jual',
        store: storeComboUangMuka,
        mode: 'local',
        valueField: 'no_faktur',
        displayField: 'no_faktur',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '95%',
        width: 200,
        hiddenName: 'no_faktur',
        emptyText: 'Pilih Pembayaran',
        readOnly: true,
        fieldClass: 'readonly-input'
    });
//=============================================================================end of faktur juak========================================================

    /**
     * deklarasi reporting window
     */
    var windowCreateKwitansiPrint = new Ext.Window({
        id: 'id_window_create_kwitansi_print',
        title: 'Print Kwitansi Penjualan',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        //html: 'div id="lap_do" />'
        html: '<iframe style="width:100%;height:100%;" id="id_kwitansi_penjualan_print" src=""></iframe>'
    });


    /**
     * top  header create kwitansi penjualan
     */
    var headerCreateKwitansiPenjualan = {
        layout: 'column',
        border: false,
        buttonAlign: 'left',
        style: 'padding:5px',
        frame: true,
        items: [{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [
                    {
                        xtype: 'textfield',
                        fieldLabel: 'No Kwitansi',
                        name: 'txt_no_kwitansi',
                        readOnly: true,
                        fieldClass: 'readonly-input',
                        id: 'id_txt_no_kwitansi',
                        maxLength: 255,
                        anchor: '90%',
                        value: ''
                    }
                ]
            }, {
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [
                    {
                        xtype: 'datefield',
                        fieldLabel: 'Tgl Kwitansi',
                        //emptyText: 'Tanggal Kwitansi',
                        name: 'txt_tgl_kwitansi',
                        id: 'id_txt_tgl_kwitansi',
                        maxLength: 255,
                        anchor: '90%',
                        value: new Date()//,
                                //format: 'Y-m-d'

                    }
                ]
            }]
    };

    /**
     * first body of create kwitansi penjualan 
     */
    var firstBodyCreateKwitansiPenjualan = {
        layout: 'column',
        border: false,
        style: 'padding:5px',
        buttonAlign: 'left',
        frame: true,
        items: [{
                xtype: 'radiogroup',
                items: [
                    {
                        xtype: 'compositefield',
                        msgTarget: 'side',
                        width: 900,
                        fieldLabel: 'Kode Pelanggan <span class="asterix">*</span>',
                        items: [{
                                xtype: 'displayfield',
                                value: 'Kode Pelanggan <span class="asterix">*</span>',
                                //style: 'padding-left:30px',
                                width: 130,
                            }, comboPelanggan,
                            {
                                xtype: 'textfield',
                                name: 'txt_kd_nama_pelanggan',
                                id: 'id_txt_kd_nama_pelanggan',
                                anchor: '90%',
                                readOnly: true,
                                fieldClass: 'readonly-input'
                            }]
                    }, {
                        xtype: 'compositefield',
                        msgTarget: 'side',
                        style: 'padding-top:10px',
                        width: 900,
                        fieldLabel: 'Kode Pelanggan <span class="asterix">*</span>',
                        items: [{
                                xtype: 'radio',
                                //fieldLabel: 'Aktif <span class="asterix">*</span>',
                                name: 'radio_jenis_bayar',
                                id: 'id_check_uang_muka',
                                checked: true,
                                anchor: '90%', listeners: {
                                    check: function() {
                                        if (this.getValue()) {
                                            //Ext.Msg.alert('faktur');

                                            Ext.getCmp('id_combo_faktur_jual').setReadOnly(true);
                                            Ext.getCmp('id_combo_faktur_jual').reset();
                                            Ext.getCmp('id_combo_faktur_jual').addClass('readonly-input');
                                            Ext.getCmp('id_combo_uang_muka').setReadOnly(false);
                                            Ext.getCmp('id_combo_uang_muka').removeClass('readonly-input');
                                        }
                                        else {
                                            //Ext.Msg.alert('uang muka');
                                            Ext.getCmp('id_combo_uang_muka').setReadOnly(true);
                                            Ext.getCmp('id_combo_uang_muka').reset();
                                            Ext.getCmp('id_combo_uang_muka').addClass('readonly-input');
                                            Ext.getCmp('id_combo_faktur_jual').setReadOnly(false);
                                            Ext.getCmp('id_combo_faktur_jual').removeClass('readonly-input');
                                        }
                                    }
                                }
                            }, {
                                xtype: 'displayfield',
                                value: 'Uang Muka <span class="asterix">*</span>',
                                //style: 'padding-left:30px',
                                width: 105
                            }, comboUangMuka
                        ]
                    }, {
                        xtype: 'compositefield',
                        msgTarget: 'side',
                        style: 'padding-top:10px',
                        width: 900,
                        fieldLabel: 'Kode Pelanggan <span class="asterix">*</span>',
                        items: [{
                                xtype: 'radio',
                                //fieldLabel: 'Aktif <span class="asterix">*</span>',
                                name: 'radio_jenis_bayar',
                                id: 'id_check_faktur_jual',
                                anchor: '90%',
                                listeners: {
                                    check: function() {
                                        if (this.getValue()) {
                                            //Ext.Msg.alert('faktur');
                                            Ext.getCmp('id_combo_uang_muka').setReadOnly(true);
                                            Ext.getCmp('id_combo_uang_muka').addClass('readonly-input');
                                            Ext.getCmp('id_combo_faktur_jual').setReadOnly(false);
                                            Ext.getCmp('id_combo_faktur_jual').removeClass('readonly-input');
                                        }
                                        else {
                                            //Ext.Msg.alert('uang muka');
                                            Ext.getCmp('id_combo_faktur_jual').setReadOnly(true);
                                            Ext.getCmp('id_combo_faktur_jual').addClass('readonly-input');
                                            Ext.getCmp('id_combo_uang_muka').setReadOnly(false);
                                            Ext.getCmp('id_combo_uang_muka').removeClass('readonly-input');
                                        }
                                    }
                                }
                            }, {
                                xtype: 'displayfield',
                                value: 'Pembayaran Piutang<span class="asterix">*</span>',
                                //style: 'padding-left:30px',
                                width: 105
                            }, comboFakturJual
                        ]
                    }
                ]
            }]
    };
    /**
     * second body of create kwitansi penjualan 
     */
    var secondBodyCreateKwitansiPenjualan = {
        layout: 'column',
        style: 'padding:5px',
        border: false,
        items: [{
                columnWidth: 1,
                layout: 'form',
                border: false,
                frame: true,
                labelWidth: 120,
                items: [
                    {
                        xtype: 'textfield',
                        fieldLabel: 'Sudah terima dari <span class="asterix">*</span>',
                        name: 'terima_dari',
                        allowBlank: false,
                        id: 'id_txt_terima_dari',
                        anchor: '30%',
                        readOnly: true,
                        fieldClass: 'readonly-input'
                    }, {
                        xtype: 'textarea',
                        fieldLabel: 'Uang Sejumlah <span class="asterix">*</span>',
                        name: 'terbilang_total',
                        readOnly: true,
                        fieldClass: 'readonly-input',
                        id: 'id_terbilang_total',
                        anchor: '90%',
                    }, {
                        xtype: 'textarea',
                        fieldLabel: 'Untuk Pembayaran <span class="asterix">*</span>',
                        name: 'keterangan_pembayaran',
                        allowBlank: false,
                        id: 'id_keterangan_pembayaran',
                        anchor: '90%',
                        readOnly: true,
                        fieldClass: 'readonly-input',
                    }, {
                        xtype: 'numericfield',
                        currencySymbol: '',
                        fieldLabel: 'Jumlah <span class="asterix">*</span>',
                        name: 'rp_total',
                        readOnly: true,
                        fieldClass: 'readonly-input bold-input number',
                        id: 'id_rp_total',
                        anchor: '30%',
                        style: 'text-align:right',
                        value: '0'
                    }]
            }]
    };
    function clearFormKwitansiPenjualan() {
//        Ext.getCmp('id_rp_total').setValue('');
//        Ext.getCmp('id_keterangan_pembayaran').setValue('');
//        Ext.getCmp('id_terbilang_total').setValue('');
//        Ext.getCmp('id_txt_terima_dari').setValue('');
        Ext.getCmp('id_create_kwitansi_penjualan').getForm().reset();
//        Ext.getCmp('id_create_kwitansi_penjualan').getForm().load({
//            url: '<?= site_url("create_kwitansi_penjualan_controller/getNoKwitansi") ?>',
//            failure: function(form, action) {
//                var de = Ext.util.JSON.decode(action.response.responseText);
//                Ext.Msg.show({
//                    title: 'Error',
//                    msg: de.errMsg,
//                    modal: true,
//                    icon: Ext.Msg.ERROR,
//                    buttons: Ext.Msg.OK,
//                    fn: function(btn) {
//                        if (btn == 'ok' && de.errMsg == 'Session Expired') {
//                            window.location = '<?= site_url("auth/login") ?>';
//                        }
//                    }
//                });
//            }
//        });
    }

    /**
     * deklarasi form panel utama
     */
    var createKwitansiPenjualan = new Ext.FormPanel({
        id: 'id_create_kwitansi_penjualan',
        border: false,
        frame: true,
        bodyStyle: 'p adding-right:20px;',
        labelWidth: 130,
        items: [
            {
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },
                items: [headerCreateKwitansiPenjualan, firstBodyCreateKwitansiPenjualan, secondBodyCreateKwitansiPenjualan]
            }, {
                layout: 'column',
                border: false,
                items: [{
                        columnWidth: .6,
                        style: 'margin:6px 3px 0 0;',
                        layout: 'form',
                        labelWidth: 110,
                        buttonAlign: 'left',
                        buttons: [{
                                text: 'Save',
                                handler: function() {

                                    Ext.getCmp('id_create_kwitansi_penjualan').getForm().submit({
                                        url: '<?= site_url("create_kwitansi_penjualan_controller/finalInsert") ?>',
                                        scope: this,
                                        params: {
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
                                                    if (btn == 'ok') {
                                                        windowCreateKwitansiPrint.show();
                                                        Ext.getDom('id_kwitansi_penjualan_print').src = r.printUrl;
                                                    }
                                                }
                                            });
                                            clearFormKwitansiPenjualan();
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
                                    clearFormKwitansiPenjualan();
                                }
                            }]
                    }]
            }
        ]
    });

//    createKwitansiPenjualan.on('afterrender', function() {
//        Ext.getCmp('id_create_kwitansi_penjualan').getForm().load({
//            url: '<?= site_url("create_kwitansi_penjualan_controller/getNoKwitansi") ?>',
//            failure: function(form, action) {
//                var de = Ext.util.JSON.decode(action.response.responseText);
//                Ext.Msg.show({
//                    title: 'Error',
//                    msg: de.errMsg,
//                    modal: true,
//                    icon: Ext.Msg.ERROR,
//                    buttons: Ext.Msg.OK,
//                    fn: function(btn) {
//                        if (btn == 'ok' && de.errMsg == 'Session Expired') {
//                            window.location = '<?= site_url("auth/login") ?>';
//                        }
//                    }
//                });
//            }
//        });
//    });

</script>
