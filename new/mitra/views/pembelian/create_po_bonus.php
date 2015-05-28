<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    //Combo PO Induk
    var strcbpobpoinduk = new Ext.data.ArrayStore({
        fields: ['no_po'],
        data: []
    });

    var strgridpobpoinduk = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_po', 'tanggal_po'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_create_po_bonus/search_po_induk") ?>',
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

    var searchgridpobpoinduk = new Ext.app.SearchField({
        store: strgridpobpoinduk,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridpobpoinduk'
    });
     searchgridpobpoinduk.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';

            // Get the value of search field
            var fid = Ext.getCmp('id_cbpcpobsuplier').getValue();
            var o = {start: 0, kd_supplier: fid};

            this.store.baseParams = this.store.baseParams || {};
            this.store.baseParams[this.paramName] = '';
            this.store.reload({
                params: o
            });
            this.triggers[0].hide();
            this.hasSearch = false;
        }
    };

    searchgridpobpoinduk.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }

        // Get the value of search field
        var fid = Ext.getCmp('id_cbpcpobsuplier').getValue();
        var o = {start: 0, kd_supplier: fid};

        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params: o});
        this.hasSearch = true;
        this.triggers[0].show();
    };

    var gridpobpoinduk = new Ext.grid.GridPanel({
        store: strgridpobpoinduk,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'No PO',
                dataIndex: 'no_po',
                width: 120,
                sortable: true
            }, {
                header: 'Tanggal PO',
                dataIndex: 'tanggal_po',
                width: 150,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridpobpoinduk]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridpobpoinduk,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cbpobpoinduk').setValue(sel[0].get('no_po'));
                    menupobpoinduk.hide();
                }
            }
        }
    });

    var menupobpoinduk = new Ext.menu.Menu();
    menupobpoinduk.add(new Ext.Panel({
        title: 'Pilih PO Induk',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridpobpoinduk],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menupobpoinduk.hide();
                }
            }]
    }));

    Ext.ux.TwinCombopobpoinduk = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
        strgridpobpoinduk.load({
                params: {
                    kd_supplier: Ext.getCmp('id_cbpcpobsuplier').getValue(),
                    }
            });    
        menupobpoinduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menupobpoinduk.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridpobpoinduk').getValue();
        if (sf !== '') {
            Ext.getCmp('id_searchgridpobpoinduk').setValue('');
            searchgridpobpoinduk.onTrigger2Click();
        }
    });

    var cbpobpoinduk = new Ext.ux.TwinCombopobpoinduk({
        fieldLabel: 'PO Induk',
        id: 'id_cbpobpoinduk',
        store: strcbpobpoinduk,
        mode: 'local',
        valueField: 'no_po',
        displayField: 'no_po',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: true,
        editable: false,
        anchor: '90%',
        hiddenName: 'no_po_induk',
        emptyText: 'Pilih PO Induk'
    });
    //End Combo PO Induk
    var strcbpcpobsuplier = new Ext.data.ArrayStore({
        fields: ['kd_supplier'],
        data: []
    });

    var strgridpcpobsuplier = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_supplier', 'nama_supplier', 'top', 'pic', 'pkp', 'alamat'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_create_po_bonus/search_supplier") ?>',
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

    strgridpcpobsuplier.on('load', function() {
        Ext.getCmp('id_searchgridpcpobsuplier').focus();
    });

    var searchgridpcpobsuplier = new Ext.app.SearchField({
        store: strgridpcpobsuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridpcpobsuplier'
    });


    var gridpcpobsuplier = new Ext.grid.GridPanel({
        store: strgridpcpobsuplier,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'Kode Supplier',
                dataIndex: 'kd_supplier',
                width: 80,
                sortable: true
            }, {
                header: 'Nama Supplier',
                dataIndex: 'nama_supplier',
                width: 300,
                sortable: true
            }, {
                header: 'PIC',
                dataIndex: 'pic',
                width: 100,
                sortable: true
            }, {
                header: 'Alamat',
                dataIndex: 'alamat',
                width: 200,
                sortable: true
            }, {
                header: 'Waktu TOP',
                dataIndex: 'top',
                width: 80,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridpcpobsuplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridpcpobsuplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cbpcpobsuplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('pcpob_nama_supplier').setValue(sel[0].get('nama_supplier'));
                    Ext.getCmp('pcpob_pic_supplier').setValue(sel[0].get('pic'));
                    Ext.getCmp('pcpob_waktu_top').setValue(sel[0].get('top'));
                    Ext.getCmp('pcpob_alamat_supplier').setValue(sel[0].get('alamat'));
                    if (sel[0].get('pkp') === 1) {
                        Ext.getCmp('pcpob_pkp_supplier').setValue('YA');
                        Ext.getCmp('pcpob_ppn_persen').setValue(10);
                       // Ext.getCmp('pcpob_ppn_yesno').setValue('1');
                    } else {
                        Ext.getCmp('pcpob_pkp_supplier').setValue('TIDAK');
                        Ext.getCmp('pcpob_ppn_persen').setValue(0);
                        //Ext.getCmp('pcpob_ppn_yesno').setValue('0');
                    }
                    cbpcpobtop.setValue();
                    cbpcpobtop.store.removeAll();
                    cbpcpobtop.store.proxy.conn.url = '<?= site_url("pembelian_create_po_non_request/get_term_of_payment_by_supplier") ?>/' + sel[0].get('kd_supplier');
                    cbpcpobtop.store.reload();
                    Ext.Ajax.request({
                        url: '<?= site_url("pembelian_create_po_bonus/get_nilai_parameter_pic") ?>',
                        method: 'POST',
                        callback: function(opt, success, responseObj) {
                            var de = Ext.util.JSON.decode(responseObj.responseText);
                            if (de.success === true) {
                                Ext.getCmp('pcpob_pic_penerima').setValue(de.data.nilai_parameter);
                            }
                        }
                    });

                    Ext.Ajax.request({
                        url: '<?= site_url("pembelian_create_po_bonus/get_nilai_parameter_alamat") ?>',
                        method: 'POST',
                        callback: function(opt, success, responseObj) {
                            var de = Ext.util.JSON.decode(responseObj.responseText);
                            if (de.success === true) {
                                Ext.getCmp('pcpob_alamat_penerima').setValue(de.data.nilai_parameter);
                            }
                        }
                    });

                    Ext.Ajax.request({
                        url: '<?= site_url("pembelian_create_po_bonus/get_nilai_parameter_remark") ?>',
                        method: 'POST',
                        callback: function(opt, success, responseObj) {
                            var de = Ext.util.JSON.decode(responseObj.responseText);
                            if (de.success === true) {
                                Ext.getCmp('pcpob_remark').setValue(de.data.nilai_parameter);
                            }
                        }
                    });
                    menupcpobsuplier.hide();
                    Ext.getCmp('pcpob_waktu_top').focus();
                }
            }
        }
    });

    var menupcpobsuplier = new Ext.menu.Menu();
    menupcpobsuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridpcpobsuplier],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menupcpobsuplier.hide();
                }
            }]
    }));

    Ext.ux.TwinCombopcpobSuplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridpcpobsuplier.load();
            menupcpobsuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menupcpobsuplier.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridpcpobsuplier').getValue();
        if (sf !== '') {
            Ext.getCmp('id_searchgridpcpobsuplier').setValue('');
            searchgridpcpobsuplier.onTrigger2Click();
        }
    });

    var cbpcpobsuplier = new Ext.ux.TwinCombopcpobSuplier({
        fieldLabel: 'Supplier <span class="asterix">*</span>',
        id: 'id_cbpcpobsuplier',
        store: strcbpcpobsuplier,
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

    var strcbpcpobtop = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['waktu_top'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_create_po_non_request/get_term_of_payment_by_supplier") ?>',
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

    var cbpcpobtop = new Ext.form.ComboBox({
        fieldLabel: 'Term Of Payment <span class="asterix">*</span>',
        id: 'pcpob_waktu_top',
        store: strcbpcpobtop,
        valueField: 'waktu_top',
        displayField: 'waktu_top',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        allowBlank: false,
        mode: 'local',
        anchor: '90%',
        hiddenName: 'waktu_top',
        emptyText: 'Term Of Payment'
    });

    var headerpembeliancreatepobonus = {
        layout: 'column',
        border: false,
        items: [{
                columnWidth: .4,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [cbpcpobsuplier,
                    {
                        xtype: 'textfield',
                        fieldLabel: 'Nama Supplier',
                        name: 'nama_supplier',
                        fieldClass: 'readonly-input',
                        readOnly: true,
                        id: 'pcpob_nama_supplier',
                        anchor: '90%',
                        value: ''
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'PIC Supplier',
                        name: 'pic',
                        fieldClass: 'readonly-input',
                        readOnly: true,
                        id: 'pcpob_pic_supplier',
                        anchor: '90%',
                        value: ''
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'Status PKP',
                        name: 'pkp',
                        fieldClass: 'readonly-input',
                        readOnly: true,
                        id: 'pcpob_pkp_supplier',
                        anchor: '90%',
                        value: ''
                    }, {
                        xtype: 'hidden',
                        fieldLabel: 'Alamat Kirim',
                        name: 'alamat',
                        id: 'pcpob_alamat_supplier',
                        anchor: '90%',
                        value: ''
                    }]
            }, {
                columnWidth: .3,
                layout: 'form',
                border: false,
                labelWidth: 110,
                defaults: {labelSeparator: ''},
                items: [cbpobpoinduk,{
                        xtype: 'textfield',
                        fieldLabel: 'No PO',
                        name: 'no_po',
                        readOnly: true,
                        fieldClass: 'readonly-input',
                        id: 'pcpob_no_po',
                        maxLength: 255,
                        anchor: '90%',
                        value: ''
                    }, cbpcpobtop, {
                        xtype: 'datefield',
                        fieldLabel: 'Masa Berlaku <span class="asterix">*</span>',
                        name: 'tgl_berlaku_po',
                        allowBlank: false,
                        format: 'd-m-Y',
                        editable: false,
                        id: 'pcpob_tgl_berlaku_po',
                        anchor: '90%',
                        value: ''
                    } 
//                        new Ext.form.Checkbox({
//                        fieldLabel: 'Ada Faktur Pajak',
//                        xtype: 'checkbox',
//                        boxLabel: 'Ya',
//                        name: 'PPN',
//                        id: 'pcpob_ppn_yesno',
//                        inputValue: '0',
//                        autoLoad: true,
//                        anchor: '90%',
//                        listeners: {
//                            check: function() {
//                                var ppn = this.getValue();
//                                var pkp = Ext.getCmp('pcpob_pkp_supplier').getValue();
//                                if (ppn) {
//                                    if (pkp == 'YA') {
//                                        Ext.getCmp('pcpob_ppn_persen').setValue('10');
//                                    }
//                                } else {
//                                    Ext.getCmp('pcpob_ppn_persen').setValue('0');
//                                }
//                                var sub_jumlah = Ext.getCmp('pcpob_sub_jumlah').getValue();
//                                var ppn_rp = (Ext.getCmp('pcpob_ppn_persen').getValue() * sub_jumlah) / 100;
//                                var grand_total = sub_jumlah + ppn_rp;
//
//                                Ext.getCmp('pcpob_ppn_rp').setValue(ppn_rp);
//                                Ext.getCmp('pcpob_total').setValue(grand_total);
//                                var sisa_bayar = grand_total - Ext.getCmp('pcpob_dp').getValue();
//                                Ext.getCmp('pcpob_sisa_bayar').setValue(sisa_bayar);
//                            }
//                        }
//                    })
                    ]
            }, {
                columnWidth: .3,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [{
                        xtype: 'datefield',
                        format: 'd-m-Y',
                        fieldLabel: 'Tanggal',
                        name: 'tanggal_po',
                        //fieldClass:'readonly-input',
                        //readOnly:true,
                        allowBlank: false,
                        id: 'pcpob_tanggal_po',
                        anchor: '90%',
                        value: '',
                        maxValue: (new Date()).clearTime()
                    }, {
                        fieldLabel: 'Peruntukan <span class="asterix">*</span>',
                        xtype: 'radiogroup',
                        columnWidth: [.5, .5],
                        allowBlank: false,
                        items: [{
                                boxLabel: 'Supermarket',
                                name: 'kd_peruntukan',
                                inputValue: '0',
                                id: 'pcpob_peruntukan_supermarket',
                                checked: true
                            }, {
                                boxLabel: 'Distribusi',
                                name: 'kd_peruntukan',
                                inputValue: '1',
                                id: 'pcpob_peruntukan_distribusi'
                            }]
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'Order By',
                        name: 'order_by',
                        readOnly: false,
                        fieldClass: 'readonly-input',
                        id: 'pcpob_order_by',
                        anchor: '90%',
                        value: ''
                    }, new Ext.form.Checkbox({
                        xtype: 'checkbox',
                        fieldLabel: 'Scan Barcode',
                        boxLabel: 'Ya',
                        name: 'scan_barcode',
                        id: 'pcpob_scan_barcode',
                        checked: false,
                        inputValue: '0',
                        autoLoad: true
                    })]
            }
        ]

    };

    var strpembeliancreatepobonus = new Ext.data.Store({
        autoSave: false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_produk', allowBlank: false, type: 'text'},
                {name: 'nama_produk', allowBlank: false, type: 'text'},
                {name: 'nm_satuan', allowBlank: false, type: 'text'},
                {name: 'qty', allowBlank: false, type: 'int'},
                {name: 'disk_persen_supp1_po', allowBlank: false, type: 'float'},
                {name: 'disk_persen_supp2_po', allowBlank: false, type: 'float'},
                {name: 'disk_persen_supp3_po', allowBlank: false, type: 'float'},
                {name: 'disk_persen_supp4_po', allowBlank: false, type: 'float'},
                {name: 'disk_amt_supp1_po', allowBlank: false, type: 'int'},
                {name: 'disk_amt_supp2_po', allowBlank: false, type: 'int'},
                {name: 'disk_amt_supp3_po', allowBlank: false, type: 'int'},
                {name: 'disk_amt_supp4_po', allowBlank: false, type: 'int'},
                {name: 'disk_amt_supp5_po', allowBlank: false, type: 'int'},
                {name: 'disk_persen_supp1', allowBlank: false, type: 'text'},
                {name: 'disk_persen_supp2', allowBlank: false, type: 'text'},
                {name: 'disk_persen_supp3', allowBlank: false, type: 'text'},
                {name: 'disk_persen_supp4', allowBlank: false, type: 'text'},
                {name: 'disk_persen_supp5', allowBlank: false, type: 'text'},
                {name: 'total_diskon', allowBlank: false, type: 'int'},
                {name: 'hrg_supplier', allowBlank: false, type: 'int'},
                {name: 'harga', allowBlank: false, type: 'float'},
                {name: 'dpp_po', allowBlank: false, type: 'float'},
                {name: 'jumlah', allowBlank: false, type: 'float'}
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

    strpembeliancreatepobonus.on('update', function() {
        var jumlah = 0;

        strpembeliancreatepobonus.each(function(node) {
            jumlah += parseFloat(node.data.jumlah);
        });

        var diskon_persen = Ext.getCmp('pcpob_diskon_persen').getValue();
        var diskon_rp = diskon_persen * jumlah;
        var sub_jumlah = jumlah - diskon_rp;
        var ppn_persen = Ext.getCmp('pcpob_ppn_persen').getValue();
        var ppn_rp = (ppn_persen * sub_jumlah) / 100;
        var grand_total = sub_jumlah + ppn_rp;

        jumlah = Math.round(jumlah);
        sub_jumlah = Math.round(sub_jumlah);
        ppn_rp = Math.round(ppn_rp);
        grand_total = Math.round(grand_total);

        Ext.getCmp('pcpob_jumlah').setValue(jumlah);
        Ext.getCmp('pcpob_diskon_persen').setValue(diskon_persen);
        Ext.getCmp('pcpob_diskon_rp').setValue(diskon_rp);
        Ext.getCmp('pcpob_sub_jumlah').setValue(sub_jumlah);
        Ext.getCmp('pcpob_ppn_persen').setValue(ppn_persen);
        Ext.getCmp('pcpob_ppn_rp').setValue(ppn_rp);
        Ext.getCmp('pcpob_total').setValue(grand_total);
        var sisa_bayar = grand_total - Ext.getCmp('pcpob_dp').getValue();
        Ext.getCmp('pcpob_sisa_bayar').setValue(sisa_bayar);
    });

    var strcbpcpobproduk = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data: []
    });

    var strgridpcpobproduk = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_produk', allowBlank: false, type: 'text'},
                {name: 'nama_produk', allowBlank: false, type: 'text'},
                {name: 'nm_satuan', allowBlank: false, type: 'text'},
                {name: 'waktu_top', allowBlank: false, type: 'int'},
                {name: 'disk_persen_supp1_po', allowBlank: false, type: 'float'},
                {name: 'disk_persen_supp2_po', allowBlank: false, type: 'float'},
                {name: 'disk_persen_supp3_po', allowBlank: false, type: 'float'},
                {name: 'disk_persen_supp4_po', allowBlank: false, type: 'float'},
                {name: 'disk_amt_supp1_po', allowBlank: false, type: 'int'},
                {name: 'disk_amt_supp2_po', allowBlank: false, type: 'int'},
                {name: 'disk_amt_supp3_po', allowBlank: false, type: 'int'},
                {name: 'disk_amt_supp4_po', allowBlank: false, type: 'int'},
                {name: 'disk_amt_supp5_po', allowBlank: false, type: 'int'},
                {name: 'disk_persen_supp1', allowBlank: false, type: 'text'},
                {name: 'disk_persen_supp2', allowBlank: false, type: 'text'},
                {name: 'disk_persen_supp3', allowBlank: false, type: 'text'},
                {name: 'disk_persen_supp4', allowBlank: false, type: 'text'},
                {name: 'disk_persen_supp5', allowBlank: false, type: 'text'},
                {name: 'total_diskon', allowBlank: false, type: 'int'},
                {name: 'hrg_supplier', allowBlank: false, type: 'int'},
                {name: 'harga', allowBlank: false, type: 'float'},
                {name: 'dpp_po', allowBlank: false, type: 'float'},
                {name: 'jumlah', allowBlank: false, type: 'float'},
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_create_po_bonus/search_produk_by_supplier") ?>',
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

    strgridpcpobproduk.on('load', function() {
        Ext.getCmp('search_query_pobonus').focus();
    });

    var searchFieldPOBonus = new Ext.app.SearchField({
        width: 220,
        id: 'search_query_pobonus',
        store: strgridpcpobproduk
    });

    strgridpcpobproduk.on('load', function() {
        var scan = Ext.getCmp('pcpob_scan_barcode').getValue();
        if (scan) {
            Ext.getCmp('pcpob_scan_barcode_kode').focus();
        } else {
            Ext.getCmp('search_query_pononreq').focus();
        }
    });


    searchFieldPOBonus.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';

            // Get the value of search field
            var fid = Ext.getCmp('id_cbpcpobsuplier').getValue();
            var top = Ext.getCmp('pcpob_waktu_top').getValue();
            var pkp = Ext.getCmp('pcpob_pkp_supplier').getValue();
            if (pkp === 'YA') {
                pkp = 1;
            } else
                pkp = 2;
            var o = {start: 0, kd_supplier: fid, waktu_top: top, pkp: pkp};

            this.store.baseParams = this.store.baseParams || {};
            this.store.baseParams[this.paramName] = '';
            this.store.reload({
                params: o
            });
            this.triggers[0].hide();
            this.hasSearch = false;
        }
    };

    searchFieldPOBonus.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }

        // Get the value of search field
        var fid = Ext.getCmp('id_cbpcpobsuplier').getValue();
        var top = Ext.getCmp('pcpob_waktu_top').getValue();
        var pkp = Ext.getCmp('pcpob_pkp_supplier').getValue();
        if (pkp === 'YA') {
            pkp = 1;
        } else
            pkp = 2;
        var o = {start: 0, kd_supplier: fid, waktu_top: top, pkp: pkp};

        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params: o});
        this.hasSearch = true;
        this.triggers[0].show();
    };

    // top toolbar
    var tbsearchbarangPOBonus = new Ext.Toolbar({
        items: [searchFieldPOBonus]
    });

    var gridpcpobproduk = new Ext.grid.GridPanel({
        store: strgridpcpobproduk,
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
            }, {
                header: 'Waktu TOP',
                dataIndex: 'waktu_top',
                width: 70,
                sortable: true
            }, {
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 70
            }, {
                header: '',
                dataIndex: 'disk_persen_supp1_po'
            }, {
                header: '',
                dataIndex: 'disk_persen_supp2_po'
            }, {
                header: '',
                dataIndex: 'disk_persen_supp3_po'
            }, {
                header: '',
                dataIndex: 'disk_persen_supp4_po'
            }, {
                header: '',
                dataIndex: 'disk_amt_supp1_po'
            }, {
                header: '',
                dataIndex: 'disk_amt_supp2_po'
            }, {
                header: '',
                dataIndex: 'disk_amt_supp3_po'
            }, {
                header: '',
                dataIndex: 'disk_amt_supp4_po'
            }, {
                header: '',
                dataIndex: 'disk_amt_supp5_po'
            }, {
                header: '',
                dataIndex: 'disk_persen_supp1'
            }, {
                header: '',
                dataIndex: 'disk_persen_supp2'
            }, {
                header: '',
                dataIndex: 'disk_persen_supp3'
            }, {
                header: '',
                dataIndex: 'disk_persen_supp4'
            }, {
                header: '',
                dataIndex: 'disk_persen_supp5'
            }, {
                header: '',
                dataIndex: 'total_diskon'
            }, {
                header: '',
                dataIndex: 'hrg_supplier'
            }, {
                header: '',
                dataIndex: 'harga'
            }, {
                header: '',
                dataIndex: 'dpp_po'
            }, {
                header: '',
                dataIndex: 'jumlah'
            } ],
        tbar: tbsearchbarangPOBonus,
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
//                    if (sel[0].get('waktu_top') !== Ext.getCmp('pcpob_waktu_top').getValue()) {
//                        Ext.Msg.show({
//                            title: 'Error',
//                            msg: 'Produk tidak bisa dipilih<br>karena waktu TOP harus sama',
//                            modal: true,
//                            icon: Ext.Msg.ERROR,
//                            buttons: Ext.Msg.OK
//                        });
//                        return;
//                    }

                    Ext.getCmp('epcpob_kd_produk').setValue(sel[0].get('kd_produk'));
                    Ext.getCmp('epcpob_nama_produk').setValue(sel[0].get('nama_produk'));
                    Ext.getCmp('epcpob_nm_satuan').setValue(sel[0].get('nm_satuan'));

                    Ext.getCmp('epcpob_disk_persen_supp1_po').setValue(sel[0].get('disk_persen_supp1_po'));
                    Ext.getCmp('epcpob_disk_persen_supp2_po').setValue(sel[0].get('disk_persen_supp2_po'));
                    Ext.getCmp('epcpob_disk_persen_supp3_po').setValue(sel[0].get('disk_persen_supp3_po'));
                    Ext.getCmp('epcpob_disk_persen_supp4_po').setValue(sel[0].get('disk_persen_supp4_po'));

                    Ext.getCmp('epcpob_disk_amt_supp1_po').setValue(sel[0].get('disk_amt_supp1_po'));
                    Ext.getCmp('epcpob_disk_amt_supp2_po').setValue(sel[0].get('disk_amt_supp2_po'));
                    Ext.getCmp('epcpob_disk_amt_supp3_po').setValue(sel[0].get('disk_amt_supp3_po'));
                    Ext.getCmp('epcpob_disk_amt_supp4_po').setValue(sel[0].get('disk_amt_supp4_po'));
                    Ext.getCmp('epcpob_disk_amt_supp5_po').setValue(sel[0].get('disk_amt_supp5_po'));

                    Ext.getCmp('epcpob_disk_persen_supp1').setValue(sel[0].get('disk_persen_supp1'));
                    Ext.getCmp('epcpob_disk_persen_supp2').setValue(sel[0].get('disk_persen_supp2'));
                    Ext.getCmp('epcpob_disk_persen_supp3').setValue(sel[0].get('disk_persen_supp3'));
                    Ext.getCmp('epcpob_disk_persen_supp4').setValue(sel[0].get('disk_persen_supp4'));
                    Ext.getCmp('epcpob_disk_persen_supp5').setValue(sel[0].get('disk_persen_supp5'));

                    Ext.getCmp('epcpob_total_diskon').setValue(sel[0].get('total_diskon'));

                    Ext.getCmp('epcpob_hrg_supplier').setValue(sel[0].get('hrg_supplier'));
                    Ext.getCmp('epcpob_harga').setValue(sel[0].get('harga'));
                    Ext.getCmp('epcpob_harga_exc').setValue(sel[0].get('dpp_po'));
                    Ext.getCmp('epcpob_jumlah').setValue(sel[0].get('jumlah'));
                    Ext.getCmp('epcpob_qty').setValue(0);
                    Ext.getCmp('epcpob_qty').focus();
                    menupcpobproduk.hide();
                }
            }
        }
    });

    var menupcpobproduk = new Ext.menu.Menu();
    menupcpobproduk.add(new Ext.Panel({
        title: 'Pilih Barang',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 630,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridpcpobproduk],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menupcpobproduk.hide();
                }
            }]
    }));

    var menupcpobprodukscan = new Ext.Window();
    menupcpobprodukscan.add(new Ext.Panel({
        title: 'Scan Barcode Produk',
        layout: 'form',
        border: false,
        frame: true,
        autoScroll: true,
        bodyStyle: 'padding-right:20px;',
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
                id: 'pcpob_scan_barcode_kode',
                anchor: '90%',
                value: '',
                listeners: {
                    specialKey: function(field, e) {
                        if (e.getKey() === e.RETURN || e.getKey() === e.ENTER) {
                            var supermarket = Ext.getCmp('pcpob_peruntukan_supermarket').getValue();
                            var distribusi = Ext.getCmp('pcpob_peruntukan_distribusi').getValue();


                            if (supermarket) {
                                kd_peruntukkan = '0';
                            } else if (distribusi) {
                                kd_peruntukkan = '1';
                            }

                            var pkp = Ext.getCmp('pcpob_pkp_supplier').getValue();
                            if (pkp === 'YA') {
                                pkp = 1;
                            } else
                                pkp = 2;


                            Ext.Ajax.request({
                                url: '<?= site_url("pembelian_create_po_non_request/search_produk_by_supplier") ?>',
                                method: 'POST',
                                params: {
                                    kd_supplier: Ext.getCmp('id_cbpcpobsuplier').getValue(),
                                    pkp: pkp,
                                    waktu_top: Ext.getCmp('pcpob_waktu_top').getValue(),
                                    kd_peruntukan: kd_peruntukkan,
                                    query: Ext.getCmp('pcpob_scan_barcode_kode').getValue(),
                                    sender: 'scan'
                                },
                                callback: function(opt, success, responseObj) {
                                    var scn = Ext.util.JSON.decode(responseObj.responseText);
                                    if (scn.success === true) {
                                        Ext.getCmp('pcpob_kd_produk_scan').setValue(scn.data[0]['kd_produk']);
                                        Ext.getCmp('pcpob_kd_produk_supp_scan').setValue(scn.data[0]['kd_produk_supp']);
                                        Ext.getCmp('pcpob_kd_produk_lama_scan').setValue(scn.data[0]['kd_produk_lama']);
                                        Ext.getCmp('pcpob_nama_produk_scan').setValue(scn.data[0]['nama_produk']);
                                    }
                                }
                            });
                            if (Ext.getCmp('pcpob_kd_produk_scan').getValue() !== '') {
                                Ext.getCmp('pcpob_submit_button').focus();
                            }


                        }
                    }
                }
            }, {
                xtype: 'textfield',
                fieldLabel: 'Kode Produk',
                name: 'kd_produk',
                readOnly: true,
                fieldClass: 'readonly-input',
                id: 'pcpob_kd_produk_scan',
                anchor: '90%',
                value: ''
            }, {
                xtype: 'textfield',
                fieldLabel: 'Kode Produk Supplier',
                name: 'kd_produk_supp',
                readOnly: true,
                fieldClass: 'readonly-input',
                id: 'pcpob_kd_produk_supp_scan',
                anchor: '90%',
                value: ''
            }, {
                xtype: 'textfield',
                fieldLabel: 'Kode Produk Lama',
                name: 'kd_produk_lama',
                readOnly: true,
                fieldClass: 'readonly-input',
                id: 'pcpob_kd_produk_lama_scan',
                anchor: '90%',
                value: ''
            }, {
                xtype: 'textfield',
                fieldLabel: 'Nama Produk',
                name: 'nama_produk',
                readOnly: true,
                fieldClass: 'readonly-input',
                id: 'pcpob_nama_produk_scan',
                anchor: '90%',
                value: ''
            }
        ],
        buttons: [{
                text: 'Submit',
                formBind: true,
                id: 'pcpob_submit_button',
                handler: function() {
                    var supermarket = Ext.getCmp('pcpob_peruntukan_supermarket').getValue();
                    var distribusi = Ext.getCmp('pcpob_peruntukan_distribusi').getValue();

                    if (supermarket) {
                        kd_peruntukkan = '0';
                    } else if (distribusi) {
                        kd_peruntukkan = '1';
                    }
                    var pkp = Ext.getCmp('pcpob_pkp_supplier').getValue();
                    if (pkp === 'YA') {
                        pkp = 1;
                    } else
                        pkp = 2;
                    Ext.Ajax.request({
                        url: '<?= site_url("pembelian_create_po_non_request/search_produk_by_supplier") ?>',
                        method: 'POST',
                        params: {
                            kd_supplier: Ext.getCmp('id_cbpcpobsuplier').getValue(),
                            pkp: pkp,
                            waktu_top: Ext.getCmp('pcpob_waktu_top').getValue(),
                            kd_peruntukan: kd_peruntukkan,
                            query: Ext.getCmp('pcpob_scan_barcode_kode').getValue(),
                            kd_produk: Ext.getCmp('pcpob_kd_produk_scan').getValue(),
                            action: 'validate',
                            sender: 'scan'
                        },
                        callback: function(opt, success, responseObj) {
                            var scn = Ext.util.JSON.decode(responseObj.responseText);
                            if (scn.success === true) {
                                Ext.getCmp('epcpob_kd_produk').setValue(scn.data[0]['kd_produk']);
                                Ext.getCmp('epcpob_nama_produk').setValue(scn.data[0]['nama_produk']);
                                Ext.getCmp('epcpob_nm_satuan').setValue(scn.data[0]['nm_satuan']);
                                // Ext.getCmp('epcpob_min_stok').setValue(scn.data[0]['min_stok']);
                                // Ext.getCmp('epcpob_max_stok').setValue(scn.data[0]['max_stok']);
                                // Ext.getCmp('epcpob_jml_stok').setValue(scn.data[0]['jml_stok']);


                                Ext.getCmp('epcpob_disk_persen_supp1_po').setValue(scn.data[0]['disk_persen_supp1_po']);
                                Ext.getCmp('epcpob_disk_persen_supp2_po').setValue(scn.data[0]['disk_persen_supp2_po']);
                                Ext.getCmp('epcpob_disk_persen_supp3_po').setValue(scn.data[0]['disk_persen_supp3_po']);
                                Ext.getCmp('epcpob_disk_persen_supp4_po').setValue(scn.data[0]['disk_persen_supp4_po']);

                                Ext.getCmp('epcpob_disk_amt_supp1_po').setValue(scn.data[0]['disk_amt_supp1_po']);
                                Ext.getCmp('epcpob_disk_amt_supp2_po').setValue(scn.data[0]['disk_amt_supp2_po']);
                                Ext.getCmp('epcpob_disk_amt_supp3_po').setValue(scn.data[0]['disk_amt_supp3_po']);
                                Ext.getCmp('epcpob_disk_amt_supp4_po').setValue(scn.data[0]['disk_amt_supp4_po']);
                                Ext.getCmp('epcpob_disk_amt_supp5_po').setValue(scn.data[0]['disk_amt_supp5_po']);

                                Ext.getCmp('epcpob_disk_persen_supp1').setValue(scn.data[0]['disk_persen_supp1']);
                                Ext.getCmp('epcpob_disk_persen_supp2').setValue(scn.data[0]['disk_persen_supp2']);
                                Ext.getCmp('epcpob_disk_persen_supp3').setValue(scn.data[0]['disk_persen_supp3']);
                                Ext.getCmp('epcpob_disk_persen_supp4').setValue(scn.data[0]['disk_persen_supp4']);
                                Ext.getCmp('epcpob_disk_persen_supp5').setValue(scn.data[0]['disk_persen_supp5']);

                                Ext.getCmp('epcpob_total_diskon').setValue(scn.data[0]['total_diskon']);

                                Ext.getCmp('epcpob_hrg_supplier').setValue(scn.data[0]['hrg_supplier']);
                                Ext.getCmp('epcpob_harga').setValue(scn.data[0]['harga']);
                                var dpp_po = scn.data[0]['dpp_po'];
                                // dpp_po = Math.round(dpp_po * 100) / 100;
                                Ext.getCmp('epcpob_harga_exc').setValue(dpp_po);
                                // Ext.getCmp('epcpob_harga_exc_view').setValue(dpp_po_view);
                                Ext.getCmp('epcpob_jumlah').setValue(scn.data[0]['jumlah']);
                                // Ext.getCmp('epcpob_min_order').setValue(scn.data[0]['min_order']);
                                // Ext.getCmp('epcpob_is_kelipatan_order').setValue(scn.data[0]['is_kelipatan_order']);
                                Ext.getCmp('epcpob_qty').setValue(0);
                                Ext.getCmp('epcpob_qty').focus();
                            } else {
                                // Ext.getCmp('epcpob_min_order').setValue('');
                                // Ext.getCmp('epcpob_is_kelipatan_order').setValue('');
                                Ext.getCmp('epcpob_kd_produk').setValue('');
                                Ext.getCmp('epcpob_nama_produk').setValue('');
                                Ext.getCmp('epcpob_nm_satuan').setValue('');
                                // Ext.getCmp('epcpob_min_stok').setValue('');
                                // Ext.getCmp('epcpob_max_stok').setValue('');
                                // Ext.getCmp('epcpob_jml_stok').setValue('');

                                Ext.getCmp('epcpob_disk_persen_supp1_po').setValue('');
                                Ext.getCmp('epcpob_disk_persen_supp2_po').setValue('');
                                Ext.getCmp('epcpob_disk_persen_supp3_po').setValue('');
                                Ext.getCmp('epcpob_disk_persen_supp4_po').setValue('');

                                Ext.getCmp('epcpob_disk_amt_supp1_po').setValue('');
                                Ext.getCmp('epcpob_disk_amt_supp2_po').setValue('');
                                Ext.getCmp('epcpob_disk_amt_supp3_po').setValue('');
                                Ext.getCmp('epcpob_disk_amt_supp4_po').setValue('');
                                Ext.getCmp('epcpob_disk_amt_supp5_po').setValue('');

                                Ext.getCmp('epcpob_disk_persen_supp1').setValue('');
                                Ext.getCmp('epcpob_disk_persen_supp2').setValue('');
                                Ext.getCmp('epcpob_disk_persen_supp3').setValue('');
                                Ext.getCmp('epcpob_disk_persen_supp4').setValue('');
                                Ext.getCmp('epcpob_disk_persen_supp5').setValue('');

                                Ext.getCmp('epcpob_total_diskon').setValue('');

                                Ext.getCmp('epcpob_hrg_supplier').setValue('');
                                Ext.getCmp('epcpob_harga').setValue('');
                                Ext.getCmp('epcpob_harga_exc').setValue('');
                                Ext.getCmp('epcpob_jumlah').setValue('');
                                // Ext.getCmp('epcpob_min_order').setValue('');
                                // Ext.getCmp('epcpob_is_kelipatan_order').setValue('');
                                Ext.Msg.show({
                                    title: 'Error',
                                    msg: scn.errMsg,
                                    modal: true,
                                    icon: Ext.Msg.ERROR,
                                    buttons: Ext.Msg.OK,
                                    fn: function(btn) {
                                        if (btn === 'ok' && scn.errMsg === 'Session Expired') {
                                            window.location = '<?= site_url("auth/login") ?>';
                                        }
                                    }
                                });
                                Ext.MessageBox.getDialog().getEl().setStyle('z-index', '80000');

                            }

                            menupcpobprodukscan.hide();
                        }
                    });
                }
            }, {
                text: 'Close',
                handler: function() {
                    menupcpobprodukscan.hide();
                }
            }]
    }));

    menupcpobproduk.on('hide', function() {
        var sf = Ext.getCmp('search_query_pobonus').getValue();
        if (sf !== '') {
            Ext.getCmp('search_query_pobonus').setValue('');
            searchFieldPOBonus.onTrigger2Click();
        }
    });

    Ext.ux.TwinCombopcpobProduk = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            var pkp = Ext.getCmp('pcpob_pkp_supplier').getValue();
            if (pkp === 'YA') {
                pkp = 1;
            } else
                pkp = 2;
            strgridpcpobproduk.load({
                params: {
                    kd_supplier: Ext.getCmp('id_cbpcpobsuplier').getValue(),
                    waktu_top: Ext.getCmp('pcpob_waktu_top').getValue(),
                    pkp: pkp
                }
            });
            var scan = Ext.getCmp('pcpob_scan_barcode').getValue();
            if (scan) {
                Ext.getCmp('pcpob_scan_barcode_kode1').setValue('');
                Ext.getCmp('pcpob_kd_produk_scan').setValue('');
                Ext.getCmp('pcpob_kd_produk_supp_scan').setValue('');
                Ext.getCmp('pcpob_kd_produk_lama_scan').setValue('');
                Ext.getCmp('pcpob_nama_produk_scan').setValue('');
                var win = Ext.WindowMgr;
                // win.zseed='80000';
                win.get(menupcpobprodukscan).show();
            } else {
                menupcpobproduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
            }

        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });


    var editorpembeliancreatepobonus1 = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });


    var gridpembeliancreatepobonus = new Ext.grid.GridPanel({
        store: strpembeliancreatepobonus,
        stripeRows: true,
        height: 200,
        frame: true,
        border: true,
        tbar: [{
                icon: BASE_ICONS + 'add.png',
                text: 'Add',
                handler: function() {
                    if (Ext.getCmp('id_cbpcpobsuplier').getValue() === '') {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Silahkan pilih supplier terlebih dulu',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                        });
                        return;
                    }

                    if (Ext.getCmp('pcpob_waktu_top').getValue() === '') {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Waktu TOP harus diisi terlebih dulu',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                        });
                        return;
                    }
                    var rowpembeliancreatepobonus = new gridpembeliancreatepobonus.store.recordType({
                        kd_produk: '',
                        qty: '0'
                    });

                    var x = strpembeliancreatepobonus.getCount();
                    editorpembeliancreatepobonus1.stopEditing();

                    strpembeliancreatepobonus.insert(x, rowpembeliancreatepobonus);
                    gridpembeliancreatepobonus.getView().refresh();
                    gridpembeliancreatepobonus.getSelectionModel().selectRow(x);
                    editorpembeliancreatepobonus1.startEditing(x);

                }
            }, {
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                disabled: true,
                handler: function() {
                    editorpembeliancreatepobonus1.stopEditing();
                    var s = gridpembeliancreatepobonus.getSelectionModel().getSelections();
                    for (var i = 0, r; r = s[i]; i++) {
                        strpembeliancreatepobonus.remove(r);
                    }

                    var jumlah = 0;

                    strpembeliancreatepobonus.each(function(node) {
                        jumlah += parseFloat(node.data.jumlah);
                    });
                    var diskon_persen = Ext.getCmp('pcpob_diskon_persen').getValue();
                    var diskon_rp = diskon_persen * jumlah;
                    var sub_jumlah = jumlah - diskon_rp;
                    var ppn_persen = Ext.getCmp('pcpob_ppn_persen').getValue();
                    var ppn_rp = (ppn_persen * sub_jumlah) / 100;
                    var grand_total = sub_jumlah + ppn_rp;


                    jumlah = Math.round(jumlah);
                    sub_jumlah = Math.round(sub_jumlah);
                    ppn_rp = Math.round(ppn_rp);
                    grand_total = Math.round(grand_total);
                    Ext.getCmp('pcpob_jumlah').setValue(jumlah);
                    Ext.getCmp('pcpob_diskon_persen').setValue(diskon_persen);
                    Ext.getCmp('pcpob_diskon_rp').setValue(diskon_rp);
                    Ext.getCmp('pcpob_sub_jumlah').setValue(sub_jumlah);
                    Ext.getCmp('pcpob_ppn_persen').setValue(ppn_persen);
                    Ext.getCmp('pcpob_ppn_rp').setValue(ppn_rp);
                    Ext.getCmp('pcpob_total').setValue(grand_total);
                    var sisa_bayar = grand_total - Ext.getCmp('pcpob_dp').getValue();
                    Ext.getCmp('pcpob_sisa_bayar').setValue(sisa_bayar);
                }
            }],
        plugins: [editorpembeliancreatepobonus1],
        columns: [
            new Ext.grid.RowNumberer({width: 30}),
            {
                header: 'Kode',
                dataIndex: 'kd_produk',
                width: 150,
                sortable: true,
                editor: new Ext.ux.TwinCombopcpobProduk({
                    id: 'epcpob_kd_produk',
                    store: strcbpcpobproduk,
                    mode: 'local',
                    valueField: 'kd_produk',
                    displayField: 'kd_produk',
                    typeAhead: true,
                    triggerAction: 'all',
                    allowBlank: false,
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
                    id: 'epcpob_nama_produk'
                })
            }, {
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 60,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epcpob_nm_satuan'
                })
            }, {
                xtype: 'numbercolumn',
                header: 'Qty',
                dataIndex: 'qty',
                width: 50,
                align: 'center',
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'epcpob_qty',
                    allowBlank: false,
                    selectOnFocus: true,
                    listeners: {
                        'change': function() {
                            if (Ext.getCmp('epcpob_kd_produk').getValue() === '') {
                                Ext.Msg.show({
                                    title: 'Error',
                                    msg: 'Silahkan pilih produk terlebih dulu',
                                    modal: true,
                                    icon: Ext.Msg.ERROR,
                                    buttons: Ext.Msg.OK
                                });
                                this.setValue('0');
                                return;
                            }
                            var jumlah = this.getValue() * Ext.getCmp('epcpob_harga_exc').getRawValue();
                            Ext.getCmp('epcpob_jumlah').setValue(jumlah);
                        }
                    }
                }
            }, {
                xtype: 'numbercolumn',
                header: 'Harga Supplier',
                dataIndex: 'hrg_supplier',
                width: 100,
                align: 'right',
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'epcpob_hrg_supplier',
                    readOnly: true
                }
            }, {
                // xtype: 'numbercolumn',
                header: 'Diskon 1',
                dataIndex: 'disk_persen_supp1',
                width: 100,
                align: 'right',
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'textfield',
                    id: 'epcpob_disk_persen_supp1',
                    readOnly: true
                }
            }, {
                // xtype: 'numbercolumn',
                header: 'Diskon 2',
                dataIndex: 'disk_persen_supp2',
                width: 100,
                sortable: true,
                align: 'right',
                format: '0,0',
                editor: {
                    xtype: 'textfield',
                    id: 'epcpob_disk_persen_supp2',
                    readOnly: true
                }
            }, {
                // xtype: 'numbercolumn',
                header: 'Diskon 3',
                dataIndex: 'disk_persen_supp3',
                width: 100,
                sortable: true,
                align: 'right',
                format: '0,0',
                editor: {
                    xtype: 'textfield',
                    id: 'epcpob_disk_persen_supp3',
                    readOnly: true
                }
            }, {
                // xtype: 'numbercolumn',
                header: 'Diskon 4',
                dataIndex: 'disk_persen_supp4',
                width: 100,
                sortable: true,
                align: 'right',
                format: '0,0',
                editor: {
                    xtype: 'textfield',
                    id: 'epcpob_disk_persen_supp4',
                    readOnly: true
                }
            }, {
                // xtype: 'numbercolumn',
                header: 'Diskon 5',
                dataIndex: 'disk_persen_supp5',
                width: 100,
                sortable: true,
                align: 'right',
                format: '0,0',
                editor: {
                    xtype: 'textfield',
                    id: 'epcpob_disk_persen_supp5',
                    readOnly: true
                }
            }, {
                xtype: 'numbercolumn',
                header: 'Total Diskon',
                dataIndex: 'total_diskon',
                width: 100,
                sortable: true,
                align: 'right',
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'epcpob_total_diskon',
                    readOnly: true
                }
            }, {
                xtype: 'numbercolumn',
                header: 'Harga Nett',
                dataIndex: 'harga',
                width: 100,
                align: 'right',
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'epcpob_harga',
                    readOnly: true
                }
            }, {
                xtype: 'numbercolumn',
                header: 'Harga Nett (Exc.)',
                dataIndex: 'dpp_po',
                width: 150,
                align: 'right',
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'epcpob_harga_exc',
                    readOnly: true
                }
            }, {
                xtype: 'numbercolumn',
                header: 'Jumlah',
                dataIndex: 'jumlah',
                width: 100,
                sortable: true,
                align: 'right',
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'epcpob_jumlah',
                    readOnly: true
                }
            }, {
                header: ' ',
                width: 0,
                dataIndex: 'disk_persen_supp1_po',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epcpob_disk_persen_supp1_po'
                })
            }, {
                header: ' ',
                width: 0,
                dataIndex: 'disk_persen_supp2_po',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epcpob_disk_persen_supp2_po'
                })
            }, {
                header: ' ',
                width: 0,
                dataIndex: 'disk_persen_supp3_po',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epcpob_disk_persen_supp3_po'
                })
            }, {
                header: ' ',
                width: 0,
                dataIndex: 'disk_persen_supp4_po',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epcpob_disk_persen_supp4_po'
                })
            }, {
                header: ' ',
                width: 0,
                dataIndex: 'disk_amt_supp1_po',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epcpob_disk_amt_supp1_po'
                })
            }, {
                header: ' ',
                width: 0,
                dataIndex: 'disk_amt_supp2_po',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epcpob_disk_amt_supp2_po'
                })
            }, {
                header: ' ',
                width: 0,
                dataIndex: 'disk_amt_supp3_po',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epcpob_disk_amt_supp3_po'
                })
            }, {
                header: ' ',
                width: 0,
                dataIndex: 'disk_amt_supp4_po',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epcpob_disk_amt_supp4_po'
                })
            }, {
                header: ' ',
                width: 0,
                dataIndex: 'disk_amt_supp5_po',
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epcpob_disk_amt_supp5_po'
                })
            }]
    });

    gridpembeliancreatepobonus.getSelectionModel().on('selectionchange', function(sm) {
        gridpembeliancreatepobonus.removeBtn.setDisabled(sm.getCount() < 1);
    });

    var winpembeliancreatepobonusprint = new Ext.Window({
        id: 'id_winpembeliancreatepobonusprint',
        title: 'Print Receive Order Form',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html: '<iframe style="width:100%;height:100%;" id="pembeliancreatepobonusprint" src=""></iframe>'
    });

    var pembeliancreatepobonus = new Ext.FormPanel({
        id: 'pembeliancreatepobonus',
        border: false,
        frame: true,
        autoScroll: true,
        monitorValid: true,
        bodyStyle: 'padding-right:20px;',
        labelWidth: 130,
        items: [{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },
                items: [headerpembeliancreatepobonus]
            },
            gridpembeliancreatepobonus,
            {
                layout: 'column',
                border: false,
                items: [{
                        columnWidth: .6,
                        style: 'margin:6px 3px 0 0;',
                        layout: 'form',
                        labelWidth: 100,
                        items: [
                            {
                                xtype: 'textfield',
                                name: 'pic_penerima',
                                id: 'pcpob_pic_penerima',
                                fieldLabel: 'PIC Penerima',
                                width: 300,
                                value: '',
                            },
                            {
                                xtype: 'textarea',
                                fieldLabel: 'Alamat Penerima',
                                name: 'alamat_penerima',
                                id: 'pcpob_alamat_penerima',
                                width: 300,
                            },
                            {
                                xtype: 'textarea',
                                fieldLabel: 'Remark',
                                name: 'remark',
                                id: 'pcpob_remark',
                                width: 300,
                            }
                        ]
                    }, {
                        columnWidth: .4,
                        layout: 'form',
                        style: 'margin:6px 0 0 0;',
                        border: false,
                        labelWidth: 110,
                        defaults: {labelSeparator: ''},
                        items: [
                            {
                                xtype: 'fieldset',
                                autoHeight: true,
                                items: [
                                    {
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'Jumlah',
                                        name: 'jumlah',
                                        readOnly: true,
                                        id: 'pcpob_jumlah',
                                        anchor: '90%',
                                        fieldClass: 'readonly-input number',
                                        value: '0',
                                    }, {
                                        xtype: 'compositefield',
                                        fieldLabel: 'Diskon',
                                        anchor: '-10',
                                        combineErrors: false,
                                        items: [
                                            {
                                                xtype: 'numberfield',
                                                currencySymbol: '',
                                                format: '0',
                                                name: 'diskon_persen',
                                                id: 'pcpob_diskon_persen',
                                                fieldClass: 'number',
                                                width: 50,
                                                value: '0',
                                                allowBlank: false,
                                                maxValue: 100,
                                                selectOnFocus: true,
                                                listeners: {
                                                    'change': function() {
                                                        var jumlah = Ext.getCmp('pcpob_jumlah').getValue();
                                                        var diskon_rp = (this.getValue() * jumlah) / 100;
                                                        var sub_jumlah = jumlah - diskon_rp;
                                                        var ppn_persen = Ext.getCmp('pcpob_ppn_persen').getValue();
                                                        var ppn_rp = (ppn_persen * sub_jumlah) / 100;
                                                        var grand_total = sub_jumlah + ppn_rp;

                                                        Ext.getCmp('pcpob_diskon_rp').setValue(diskon_rp);
                                                        Ext.getCmp('pcpob_sub_jumlah').setValue(sub_jumlah);
                                                        Ext.getCmp('pcpob_ppn_rp').setValue(ppn_rp);
                                                        Ext.getCmp('pcpob_total').setValue(grand_total);
                                                        var sisa_bayar = grand_total - Ext.getCmp('pcpob_dp').getValue();
                                                        Ext.getCmp('pcpob_sisa_bayar').setValue(sisa_bayar);
                                                    }
                                                }

                                            },
                                            {
                                                xtype: 'displayfield',
                                                value: '%',
                                                width: 20,
                                            },
                                            {
                                                xtype: 'numericfield',
                                                name: 'diskon_rp',
                                                id: 'pcpob_diskon_rp',
                                                currencySymbol: '',
                                                fieldClass: 'readonly-input number',
                                                readOnly: true,
                                                //anchor: '100%',
                                                value: '0'

                                            }
                                        ]
                                    }, {
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'Sub Jumlah',
                                        name: 'sub_jumlah',
                                        id: 'pcpob_sub_jumlah',
                                        anchor: '90%',
                                        readOnly: true,
                                        cls: 'vertical-space',
                                        fieldClass: 'readonly-input number',
                                        labelStyle: 'margin-top:10px;',
                                        value: '0',
                                    }, {
                                        xtype: 'compositefield',
                                        fieldLabel: 'PPN',
                                        fieldClass: 'readonly-input number',
                                        anchor: '-10',
                                        combineErrors: false,
                                        items: [
                                            {
                                                xtype: 'numberfield',
                                                currencySymbol: '',
                                                format: '0',
                                                name: 'ppn_persen',
                                                id: 'pcpob_ppn_persen',
                                                fieldClass: 'number',
                                                width: 50,
                                                allowBlank: false,
                                                value: '10',
                                                maxValue: 100,
                                                selectOnFocus: true,
                                                listeners: {
                                                    'change': function() {
                                                        var sub_jumlah = Ext.getCmp('pcpob_sub_jumlah').getValue();
                                                        var ppn_rp = (this.getValue() * sub_jumlah) / 100;
                                                        var grand_total = sub_jumlah + ppn_rp;

                                                        Ext.getCmp('pcpob_ppn_rp').setValue(ppn_rp);
                                                        Ext.getCmp('pcpob_total').setValue(grand_total);
                                                        var sisa_bayar = grand_total - Ext.getCmp('pcpob_dp').getValue();
                                                        Ext.getCmp('pcpob_sisa_bayar').setValue(sisa_bayar);
                                                    }
                                                }

                                            },
                                            {
                                                xtype: 'displayfield',
                                                value: '%',
                                                width: 20,
                                            },
                                            {
                                                xtype: 'numericfield',
                                                name: 'ppn_rp',
                                                id: 'pcpob_ppn_rp',
                                                currencySymbol: '',
                                                fieldClass: 'readonly-input number',
                                                readOnly: true,
                                                //anchor: '100%',
                                               value: '0',
                                            }
                                        ]
                                    }, {
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: '<b>Grand Total</b>',
                                        name: 'total',
                                        cls: 'vertical-space',
                                        readOnly: true,
                                        id: 'pcpob_total',
                                        anchor: '90%',
                                        fieldClass: 'readonly-input bold-input number',
                                        labelStyle: 'margin-top:10px;',
                                        value: '0',
                                    }, {
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: '',
                                        name: 'dp',
                                        cls: 'vertical-space',
                                        labelStyle: 'margin-top:10px;',
                                        id: 'pcpob_dp',
                                        anchor: '90%',
                                        fieldClass: 'number',
                                        hidden: true,
                                        value: '0',
                                        listeners: {
                                            'change': function() {
                                                var total = Ext.getCmp('pcpob_total').getValue();
                                                var sisa_bayar = total - this.getValue();

                                                Ext.getCmp('pcpob_sisa_bayar').setValue(sisa_bayar);

                                            }
                                        }
                                    }, {
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'Sisa Bayar',
                                        name: 'sisa_bayar',
                                        readOnly: true,
                                        fieldClass: 'readonly-input number',
                                        id: 'pcpob_sisa_bayar',
                                        anchor: '90%',
                                        value: '0',
                                    },
                                ]
                            }
                        ]
                    }]
            }

        ],
        buttons: [{
                text: 'Save',
                formBind: true,
                handler: function() {
//                    if (Ext.getCmp('pcpob_total').getValue() == 0) {
//                        Ext.Msg.show({
//                            title: 'Error',
//                            msg: 'Tidak ada pembelian!',
//                            modal: true,
//                            icon: Ext.Msg.ERROR,
//                            buttons: Ext.Msg.OK,
//                        });
//                        return;
//                    }

                    Ext.Msg.show({
                        title: 'Confirm',
                        msg: 'Apakah anda akan menyimpan data ini ??',
                        buttons: Ext.Msg.YESNO,
                        fn: function(btn) {
                            if (btn == 'yes') {

                                var detailpembeliancreatepobonus = new Array();
                                strpembeliancreatepobonus.each(function(node) {
                                    detailpembeliancreatepobonus.push(node.data)
                                });

                                Ext.getCmp('pembeliancreatepobonus').getForm().submit({
                                    url: '<?= site_url("pembelian_create_po_bonus/update_row") ?>',
                                    scope: this,
                                    params: {
                                        detail: Ext.util.JSON.encode(detailpembeliancreatepobonus),
                                        _dp: Ext.getCmp('pcpob_dp').getValue(),
                                        _jumlah: Ext.getCmp('pcpob_jumlah').getValue(),
                                        _diskon_rp: Ext.getCmp('pcpob_diskon_rp').getValue(),
                                        _ppn_persen: Ext.getCmp('pcpob_ppn_persen').getValue(),
                                        _ppn_rp: Ext.getCmp('pcpob_ppn_rp').getValue(),
                                        _total: Ext.getCmp('pcpob_total').getValue(),
                                    },
                                    waitMsg: 'Saving data...',
                                    success: function(form, action) {
                                        var r = Ext.util.JSON.decode(action.response.responseText);
                                        Ext.Msg.show({
                                            title: 'Success',
                                            msg: r.errMsg,
                                            modal: true,
                                            icon: Ext.Msg.INFO,
                                            buttons: Ext.Msg.OK,
                                            fn: function(btn) {
                                                if (btn == 'ok') {
                                                    //winpembeliancreatepobonusprint.show();
                                                    //Ext.getDom('pembeliancreatepobonusprint').src = r.printUrl;
                                                }
                                            }
                                        });

                                        clearpembeliancreatepobonus();
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
                        }
                    });

                }
            }, {
                text: 'Reset',
                handler: function() {
                    clearpembeliancreatepobonus();
                }
            }]
    });

    pembeliancreatepobonus.on('afterrender', function() {
        this.getForm().load({
            url: '<?= site_url("pembelian_create_po_bonus/get_form") ?>',
            success: function(form, action){
                var r = Ext.util.JSON.decode(action.response.responseText);
                if(r.data.user_peruntukan === "0"){
                    Ext.getCmp('pcpob_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('pcpob_peruntukan_supermarket').show();
                    Ext.getCmp('pcpob_peruntukan_distribusi').hide();
                }else if(r.data.user_peruntukan === "1"){
                    Ext.getCmp('pcpob_peruntukan_distribusi').setValue(true);
                    Ext.getCmp('pcpob_peruntukan_supermarket').hide();
                    Ext.getCmp('pcpob_peruntukan_distribusi').show();
                }else{
                    Ext.getCmp('pcpob_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('pcpob_peruntukan_supermarket').show();
                    Ext.getCmp('pcpob_peruntukan_distribusi').show();
                }
            },
            failure: function(form, action) {
                var de = Ext.util.JSON.decode(action.response.responseText);
                Ext.Msg.show({
                    title: 'Error',
                    msg: de.errMsg,
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK,
                    fn: function(btn) {
                        if (btn == 'ok' && de.errMsg == 'Session Expired') {
                            window.location = '<?= site_url("auth/login") ?>';
                        }
                    }
                });
            }
        });
    });

    function clearpembeliancreatepobonus() {
        Ext.getCmp('pembeliancreatepobonus').getForm().reset();
        Ext.getCmp('pembeliancreatepobonus').getForm().load({
            url: '<?= site_url("pembelian_create_po_bonus/get_form") ?>',
            failure: function(form, action) {
                var de = Ext.util.JSON.decode(action.response.responseText);
                Ext.Msg.show({
                    title: 'Error',
                    msg: de.errMsg,
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK,
                    fn: function(btn) {
                        if (btn == 'ok' && de.errMsg == 'Session Expired') {
                            window.location = '<?= site_url("auth/login") ?>';
                        }
                    }
                });
            }
        });
        strpembeliancreatepobonus.removeAll();
    }
</script>
