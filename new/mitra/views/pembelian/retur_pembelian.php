<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    // twin combo supplier
    var strcb_retbeli_supplier = new Ext.data.ArrayStore({
        fields: ['nama_supplier'],
        data: []
    });

    var strgrid_retbeli_supplier = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_supplier', 'nama_supplier', 'pkp'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_retur/search_supplier") ?>',
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

    var searchgrid_retbeli_supplier = new Ext.app.SearchField({
        store: strgrid_retbeli_supplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgrid_retbeli_supplier'
    });


    var grid_retbeli_supplier = new Ext.grid.GridPanel({
        store: strgrid_retbeli_supplier,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'Kode Supplier',
                dataIndex: 'kd_supplier',
                width: 100,
                sortable: true
            }, {
                header: 'Nama Supplier',
                dataIndex: 'nama_supplier',
                width: 170,
                sortable: true
            }, {
                header: 'Status PKP',
                dataIndex: 'pkp',
                width: 100,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchgrid_retbeli_supplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgrid_retbeli_supplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_pcret_kd_supplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_cbpcretsuplier').setValue(sel[0].get('nama_supplier'));
                    Ext.getCmp('id_pcret_status_pkp').setValue(sel[0].get('pkp'));
                    
                    var pkp = sel[0].get('pkp');
                    if (pkp === 'YA') {
                        Ext.getCmp('pcret_ppn_persen').setValue(10);
                        
                    } else {
                        Ext.getCmp('pcret_ppn_persen').setValue(0);
                        
                    }
                    strpembelianretur.removeAll();
                    menu_retbeli_supplier.hide();
                }
            }
        }
    });

    var menu_retbeli_supplier = new Ext.menu.Menu();
    menu_retbeli_supplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [grid_retbeli_supplier],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menu_retbeli_supplier.hide();
                }
            }]
    }));

    Ext.ux.TwinComboReturBeliSupplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgrid_retbeli_supplier.load();
            menu_retbeli_supplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menu_retbeli_supplier.on('hide', function() {
        var sf = Ext.getCmp('id_searchgrid_retbeli_supplier').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgrid_retbeli_supplier').setValue('');
            searchgrid_retbeli_supplier.onTrigger2Click();
        }
    });

    var cbpcretsuplier = new Ext.ux.TwinComboReturBeliSupplier({
        fieldLabel: 'Nama Supplier <span class="asterix">*</span>',
        id: 'id_cbpcretsuplier',
        store: strcb_retbeli_supplier,
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
    //end twincombosupplier

    var headerpembelianretur = {
        layout: 'column',
        border: false,
        items: [{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [{
                        xtype: 'textfield',
                        fieldLabel: 'No.Retur',
                        name: 'no_retur',
                        allowBlank: true,
                        readOnly: true,
                        fieldClass: 'readonly-input',
                        id: 'id_pcret_no_retur',
                        maxLength: 255,
                        anchor: '90%',
                        value: ''
                    }, {
                        xtype: 'datefield',
                        fieldLabel: 'Tanggal <span class="asterix">*</span>',
                        name: 'tgl_retur',
                        id: 'id_pcret_tglretur',
                        allowBlank: false,
                        format: 'd-M-Y',
                        editable: false,
                        anchor: '90%',
                        maxValue: (new Date()).clearTime()
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'Kode Supplier',
                        name: 'kd_supplier',
                        fieldClass: 'readonly-input',
                        readOnly: true,
                        id: 'id_pcret_kd_supplier',
                        anchor: '90%',
                        value: ''
                    }, cbpcretsuplier,
                    {
                        xtype: 'textfield',
                        fieldLabel: 'Status PKP',
                        name: 'status_pkp',
                        fieldClass: 'readonly-input',
                        readOnly: true,
                        id: 'id_pcret_status_pkp',
                        anchor: '90%',
                        value: ''
                    }
                    // {
                    // xtype: 'checkbox',
                    // fieldLabel: 'Konsinyasi <span class="asterix">*</span>',
                    // boxLabel: ' Check Jika Konsinyasi',
                    // name: 'is_konsinyasi',                        
                    // id: 'id_pcret_is_konsinyasi'
                    // ,anchor: '90%'
                    // }

                ]
            }
        ]
    };
    // twin barang
    var strcbpcretproduk = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data: []
    });

    var strgridpcretproduk = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [{name: 'kd_produk', allowBlank: false, type: 'text'},
                {name: 'kd_produk_lama', allowBlank: false, type: 'text'},
                {name: 'nama_produk', allowBlank: false, type: 'text'},
                {name: 'nm_satuan', allowBlank: false, type: 'text'},
                {name: 'waktu_top', allowBlank: false, type: 'int'},
                {name: 'disk_supp1_op', allowBlank: false, type: 'text'},
                {name: 'disk_supp2_op', allowBlank: false, type: 'text'},
                {name: 'disk_supp3_op', allowBlank: false, type: 'text'},
                {name: 'disk_supp4_op', allowBlank: false, type: 'text'},
                {name: 'disk_supp1', allowBlank: false, type: 'int'},
                {name: 'disk_supp2', allowBlank: false, type: 'int'},
                {name: 'disk_supp3', allowBlank: false, type: 'int'},
                {name: 'disk_supp4', allowBlank: false, type: 'int'},
                {name: 'disk_amt_supp5', allowBlank: false, type: 'int'},
                {name: 'rp_total_diskon', allowBlank: false, type: 'int'},
                {name: 'hrg_supplier', allowBlank: false, type: 'int'},
                {name: 'harga', allowBlank: false, type: 'int'},
                {name: 'harga_exc', allowBlank: false, type: 'int'},
                {name: 'jumlah', allowBlank: false, type: 'int'}],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_retur/search_produk_by_supplier") ?>',
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


    var searchgridpcretproduk = new Ext.app.SearchField({
        store: strgridpcretproduk,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridpcretproduk'
    });

    searchgridpcretproduk.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';

            // Get the value of search field
            var fid = Ext.getCmp('id_pcret_kd_supplier').getValue();
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

    searchgridpcretproduk.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }

        // Get the value of search field
        var fid = Ext.getCmp('id_pcret_kd_supplier').getValue();
        var o = {start: 0, kd_supplier: fid};

        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params: o});
        this.hasSearch = true;
        this.triggers[0].show();
    };

    var gridpcretproduk = new Ext.grid.GridPanel({
        store: strgridpcretproduk,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'Kode produk',
                dataIndex: 'kd_produk',
                width: 100,
                sortable: true

            }, {
                header: 'Kd Produk Lama',
                dataIndex: 'kd_produk_lama',
                width: 120,
                sortable: true

            }, {
                header: 'Nama produk',
                dataIndex: 'nama_produk',
                width: 400,
                sortable: true
            }, {
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 80
            }, {
                header: 'Waktu TOP',
                dataIndex: 'waktu_top',
                width: 80,
                sortable: true
            }, {
                header: '',
                dataIndex: 'disk_supp1_op'
            }, {
                header: '',
                dataIndex: 'disk_supp2_op'
            }, {
                header: '',
                dataIndex: 'disk_supp3_op'
            }, {
                header: '',
                dataIndex: 'disk_supp4_op'
            }, {
                header: '',
                dataIndex: 'disk_supp1'
            }, {
                header: '',
                dataIndex: 'disk_supp2'
            }, {
                header: '',
                dataIndex: 'disk_supp3'
            }, {
                header: '',
                dataIndex: 'disk_supp4'
            }, {
                header: '',
                dataIndex: 'disk_amt_supp5_po'
            }, {
                header: '',
                dataIndex: 'hrg_supplier'
            }, {
                header: '',
                dataIndex: 'harga'
            }, {
                header: '',
                dataIndex: 'jumlah'
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridpcretproduk]
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('epcret_kd_produk').setValue(sel[0].get('kd_produk'));
                    Ext.getCmp('epcret_nama_produk').setValue(sel[0].get('nama_produk'));

                    Ext.getCmp('epcret_satuan').setValue(sel[0].get('nm_satuan'));
                    Ext.getCmp('epcret_disk_supp1_op').setValue(sel[0].get('disk_supp1_op'));
                    Ext.getCmp('epcret_disk_supp2_op').setValue(sel[0].get('disk_supp2_op'));
                    Ext.getCmp('epcret_disk_supp3_op').setValue(sel[0].get('disk_supp3_op'));
                    Ext.getCmp('epcret_disk_supp4_op').setValue(sel[0].get('disk_supp4_op'));

                    Ext.getCmp('epcret_disk_supp1').setValue(sel[0].get('disk_supp1'));
                    Ext.getCmp('epcret_disk_supp2').setValue(sel[0].get('disk_supp2'));
                    Ext.getCmp('epcret_disk_supp3').setValue(sel[0].get('disk_supp3'));
                    Ext.getCmp('epcret_disk_supp4').setValue(sel[0].get('disk_supp4'));
                    Ext.getCmp('epcret_disk_amt_supp5').setValue(sel[0].get('disk_amt_supp5'));
                    
                    Ext.getCmp('epcret_total_diskon').setValue(sel[0].get('rp_total_diskon'));
                    Ext.getCmp('epcret_hrg_supplier').setValue(sel[0].get('hrg_supplier'));
                    Ext.getCmp('epcret_harga').setValue(sel[0].get('harga'));
                    harga  = Ext.getCmp('epcret_harga').getValue();
                    if (Ext.getCmp('id_pcret_status_pkp').getValue()=== 'YA'){
                         harga_exc = harga / 1.1 ;
                    }else {
                         harga_exc = harga ;
                    }
                    Ext.getCmp('epcret_harga_exc').setValue(harga_exc);
                    Ext.getCmp('epcret_jumlah').setValue(sel[0].get('jumlah'));
                    Ext.getCmp('epcret_qty_terima').setValue('');
                    Ext.getCmp('epcret_no_invoice').focus();
                    menupcretproduk.hide();
                }
            }
        }
    });

    var menupcretproduk = new Ext.menu.Menu();
    menupcretproduk.add(new Ext.Panel({
        title: 'Pilih Barang',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 600,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridpcretproduk],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menupcretproduk.hide();
                }
            }]
    }));

    menupcretproduk.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridpcretproduk').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgridpcretproduk').setValue('');
            searchgridpcretproduk.onTrigger2Click();
        }
    });

    Ext.ux.TwinCombopcretProduk = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            strgridpcretproduk.load({
                params: {
                    kd_supplier: Ext.getCmp('id_pcret_kd_supplier').getValue()
                }
            });
            menupcretproduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    //end twin produk   

    // TWIN NO INVOICE {
    var strcbpcretinvoice = new Ext.data.ArrayStore({
        fields: ['no_invoice'],
        data: []
    });
    
    var strcbpcretpo = new Ext.data.ArrayStore({
        fields: ['no_do'],
        data: []
    });

    var strgridpcretinvoice = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [{name: 'no_invoice', allowBlank: false, type: 'text'},
                {name: 'disk_supp1_op', allowBlank: false, type: 'text'},
                {name: 'disk_supp2_op', allowBlank: false, type: 'text'},
                {name: 'disk_supp3_op', allowBlank: false, type: 'text'},
                {name: 'disk_supp4_op', allowBlank: false, type: 'text'},
                {name: 'disk_supp1', allowBlank: false, type: 'int'},
                {name: 'disk_supp2', allowBlank: false, type: 'int'},
                {name: 'disk_supp3', allowBlank: false, type: 'int'},
                {name: 'disk_supp4', allowBlank: false, type: 'int'},
                {name: 'disk_amt_supp5', allowBlank: false, type: 'int'},
                {name: 'rp_total_diskon', allowBlank: false, type: 'int'},
                {name: 'harga_supplier', allowBlank: false, type: 'int'},
                {name: 'harga', allowBlank: false, type: 'int'},
                {name: 'rp_total_diskon', allowBlank: false, type: 'int'},
                {name: 'qty_terima', allowBlank: false, type: 'int'},
                {name: 'qty_retur', allowBlank: false, type: 'int'},
                {name: 'harga_exc', allowBlank: false, type: 'int'},
                {name: 'jumlah', allowBlank: false, type: 'int'},
                {name: 'no_do', allowBlank: false, type: 'text'},
                {name: 'no_faktur_pajak', allowBlank: false, type: 'text'}
            ],
                
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_retur/search_produk_by_no_invoice") ?>',
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

    var gridpcretinvoice = new Ext.grid.GridPanel({
        store: strgridpcretinvoice,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'No Invoice',
                dataIndex: 'no_invoice',
                width: 100,
                sortable: true
            }, {
                hidden: true,
                dataIndex: 'disk_supp1_op'
            }, {
                hidden: true,
                dataIndex: 'disk_supp2_op'
            }, {
                hidden: true,
                dataIndex: 'disk_supp3_op'
            }, {
                hidden: true,
                dataIndex: 'disk_supp4_op'
            }, {
                header: 'Disk 1',
                dataIndex: 'disk_supp1'
            }, {
                header: 'Disk 2',
                dataIndex: 'disk_supp2'
            }, {
                header: 'Disk 3',
                dataIndex: 'disk_supp3'
            }, {
                header: 'Disk 4',
                dataIndex: 'disk_supp4'
            }, {
                header: 'Disk 5',
                dataIndex: 'disk_amt_supp5'
            }, {
                hidden: true,
                dataIndex: 'harga_supplier'
            }, {
                hidden: true,
                dataIndex: 'harga'
            }, {
                hidden: true,
                dataIndex: 'jumlah'
            }],
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('epcret_no_invoice').setValue(sel[0].get('no_invoice'));
                    Ext.getCmp('epcret_disk_supp1_op').setValue(sel[0].get('disk_supp1_op'));
                    Ext.getCmp('epcret_disk_supp2_op').setValue(sel[0].get('disk_supp2_op'));
                    Ext.getCmp('epcret_disk_supp3_op').setValue(sel[0].get('disk_supp3_op'));
                    Ext.getCmp('epcret_disk_supp4_op').setValue(sel[0].get('disk_supp4_op'));

                    Ext.getCmp('epcret_disk_supp1').setValue(sel[0].get('disk_supp1'));
                    Ext.getCmp('epcret_disk_supp2').setValue(sel[0].get('disk_supp2'));
                    Ext.getCmp('epcret_disk_supp3').setValue(sel[0].get('disk_supp3'));
                    Ext.getCmp('epcret_disk_supp4').setValue(sel[0].get('disk_supp4'));
                    Ext.getCmp('epcret_disk_amt_supp5').setValue(sel[0].get('disk_amt_supp5'));
                    
                    Ext.getCmp('epcret_no_do').setValue(sel[0].get('no_do'));
                    Ext.getCmp('epcret_no_faktur_pajak').setValue(sel[0].get('no_faktur_pajak'));
                    Ext.getCmp('epcret_hrg_supplier').setValue(sel[0].get('harga_supplier'));
                    Ext.getCmp('epcret_total_diskon').setValue(sel[0].get('rp_total_diskon'));
                    Ext.getCmp('epcret_harga').setValue(sel[0].get('harga'));
                    Ext.getCmp('epcret_harga_exc').setValue(sel[0].get('harga_exc'));
                    Ext.getCmp('epcret_jumlah').setValue(sel[0].get('jumlah'));
                    Ext.getCmp('epcret_qty_terima').setValue(sel[0].get('qty_terima'));
                    Ext.getCmp('epcret_qty_retur').setValue(sel[0].get('qty_retur'));
                    Ext.getCmp('epcret_qty').focus();
                    menupcretinvoice.hide();
                }
            }
        }
    });

    var menupcretinvoice = new Ext.menu.Menu();
    menupcretinvoice.add(new Ext.Panel({
        title: 'Pilih No Invoice',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 600,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridpcretinvoice],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menupcretinvoice.hide();
                }
            }]
    }));

    Ext.ux.TwinCombopcretinvoice = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            strgridpcretinvoice.load({
                params: {
                    kd_supplier: Ext.getCmp('id_pcret_kd_supplier').getValue(),
                    kd_produk: Ext.getCmp('epcret_kd_produk').getValue()
                }
            });
            menupcretinvoice.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    //end twin No Invoice }   
    // twin no po
     var strgridpcretpo = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'no_do', allowBlank: false, type: 'text'},
                {name: 'no_po', allowBlank: false, type: 'text'},
                {name: 'no_faktur_pajak', allowBlank: false, type: 'text'},
                {name: 'disk_supp1_op', allowBlank: false, type: 'text'},
                {name: 'disk_supp2_op', allowBlank: false, type: 'text'},
                {name: 'disk_supp3_op', allowBlank: false, type: 'text'},
                {name: 'disk_supp4_op', allowBlank: false, type: 'text'},
                {name: 'disk_supp1', allowBlank: false, type: 'int'},
                {name: 'disk_supp2', allowBlank: false, type: 'int'},
                {name: 'disk_supp3', allowBlank: false, type: 'int'},
                {name: 'disk_supp4', allowBlank: false, type: 'int'},
                {name: 'disk_amt_supp5_po', allowBlank: false, type: 'int'},
                {name: 'harga_supplier', allowBlank: false, type: 'int'},
                {name: 'harga', allowBlank: false, type: 'int'},
                {name: 'rp_total_diskon', allowBlank: false, type: 'int'},
                {name: 'qty_terima', allowBlank: false, type: 'int'},
                {name: 'qty', allowBlank: false, type: 'int'},
                {name: 'harga_exc', allowBlank: false, type: 'float'},
                {name: 'jumlah', allowBlank: false, type: 'int'}],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_retur/search_produk_by_no_po") ?>',
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
    
     var gridpcretpo = new Ext.grid.GridPanel({
        store: strgridpcretpo,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'No PO',
                dataIndex: 'no_po',
                width: 100,
                sortable: true
            },
            {
                header: 'Qty PO',
                dataIndex: 'qty',
                width: 100,
                sortable: true
            },{
                header: 'No RO',
                dataIndex: 'no_do',
                width: 100,
                sortable: true
            }, 
            {
                header: 'Qty RO',
                dataIndex: 'qty_terima',
                width: 100,
                sortable: true
            },{
                hidden: true,
                dataIndex: 'disk_supp1_op'
            }, {
                hidden: true,
                dataIndex: 'disk_supp2_op'
            }, {
                hidden: true,
                dataIndex: 'disk_supp3_op'
            }, {
                hidden: true,
                dataIndex: 'disk_supp4_op'
            }, {
                header: 'Disk 1',
                dataIndex: 'disk_supp1'
            }, {
                header: 'Disk 2',
                dataIndex: 'disk_supp2'
            }, {
                header: 'Disk 3',
                dataIndex: 'disk_supp3'
            }, {
                header: 'Disk 4',
                dataIndex: 'disk_supp4'
            }, {
                header: 'Disk 5',
                dataIndex: 'disk_amt_supp5_po'
            }, {
                hidden: true,
                dataIndex: 'harga_supplier'
            }, {
                hidden: true,
                dataIndex: 'harga'
            }, {
                hidden: true,
                dataIndex: 'jumlah'
            }],
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('epcret_no_do').setValue(sel[0].get('no_do'));
                    Ext.getCmp('epcret_no_faktur_pajak').setValue(sel[0].get('no_faktur_pajak'));
                    Ext.getCmp('epcret_no_po').setValue(sel[0].get('no_po'));
                    Ext.getCmp('epcret_disk_supp1_op').setValue(sel[0].get('disk_supp1_op'));
                    Ext.getCmp('epcret_disk_supp2_op').setValue(sel[0].get('disk_supp2_op'));
                    Ext.getCmp('epcret_disk_supp3_op').setValue(sel[0].get('disk_supp3_op'));
                    Ext.getCmp('epcret_disk_supp4_op').setValue(sel[0].get('disk_supp4_op'));

                    Ext.getCmp('epcret_disk_supp1').setValue(sel[0].get('disk_supp1'));
                    Ext.getCmp('epcret_disk_supp2').setValue(sel[0].get('disk_supp2'));
                    Ext.getCmp('epcret_disk_supp3').setValue(sel[0].get('disk_supp3'));
                    Ext.getCmp('epcret_disk_supp4').setValue(sel[0].get('disk_supp4'));
                    Ext.getCmp('epcret_disk_amt_supp5').setValue(sel[0].get('disk_amt_supp5_po'));

                    Ext.getCmp('epcret_hrg_supplier').setValue(sel[0].get('harga_supplier'));
                    Ext.getCmp('epcret_harga').setValue(sel[0].get('harga'));
                    Ext.getCmp('epcret_harga_exc').setValue(sel[0].get('harga_exc'));
                    Ext.getCmp('epcret_jumlah').setValue(sel[0].get('jumlah'));
                    Ext.getCmp('epcret_qty_terima').setValue(sel[0].get('qty_terima'));
                    Ext.getCmp('epcret_qty').focus();
                    menupcretpo.hide();
                }
            }
        }
    });
    
     var menupcretpo = new Ext.menu.Menu();
    menupcretpo.add(new Ext.Panel({
        title: 'Pilih No PO',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 600,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridpcretpo],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menupcretpo.hide();
                }
            }]
    }));
    
    Ext.ux.TwinCombopcretpo = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            strgridpcretpo.load({
                params: {
                    kd_supplier: Ext.getCmp('id_pcret_kd_supplier').getValue(),
                    kd_produk: Ext.getCmp('epcret_kd_produk').getValue()
                }
            });
            menupcretpo.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    // end no po
    // START TWINCOMBO SUBBLOK

    var strcbkdsubblokpret = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['sub', 'nama_sub'],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_receive_order/get_sub_blok") ?>',
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

    var strgridsubblokpret = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'sub',
                'nama_sub',
                'kd_sub_blok',
                'kd_blok',
                'kd_lokasi',
                'nama_lokasi',
                'nama_blok',
                'nama_sub_blok',
                'kapasitas'
            ],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_retur/get_rows_lokasi") ?>',
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

    // search field
    var searchgridsubblokpret = new Ext.app.SearchField({
        store: strgridsubblokpret,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchgridsubblokpret'
    });

    // top toolbar
    var tbgridsubblokpret = new Ext.Toolbar({
        items: [searchgridsubblokpret]
    });

    var gridsubblokpret = new Ext.grid.GridPanel({
        store: strgridsubblokpret,
        stripeRows: true,
        frame: true,
        border: true,
        tbar: tbgridsubblokpret,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridsubblokpret,
            displayInfo: true
        }),
        columns: [{
                dataIndex: 'kd_lokasi',
                hidden: true
            }, {
                dataIndex: 'kd_blok',
                hidden: true
            }, {
                dataIndex: 'kd_sub_blok',
                hidden: true
            }, {
                header: 'Kode',
                dataIndex: 'sub',
                width: 90,
                sortable: true

            }, {
                header: 'Sub Blok Lokasi',
                dataIndex: 'nama_sub',
                width: 200,
                sortable: true
            }],
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('epcret_sub').setValue(sel[0].get('sub'));
                    Ext.getCmp('epcret_nama_sub').setValue(sel[0].get('nama_sub'));

                    menusubblokreturpembelian.hide();
                }
            }
        }
    });

    var menusubblokreturpembelian = new Ext.menu.Menu();
    menusubblokreturpembelian.add(new Ext.Panel({
        title: 'Pilih Sub Blok Lokasi',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 350,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridsubblokpret],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menusubblokreturpembelian.hide();
                }
            }]
    }));

    Ext.ux.TwinComboSubBlokPcret = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
//            strgridsubblokpret.load({
//                params: {
//                    kd_produk: Ext.getCmp('epcret_kd_produk').getValue()
//                }
//            });
            strgridsubblokpret.setBaseParam('kd_produk',Ext.getCmp('epcret_kd_produk').getValue());
            strgridsubblokpret.load();
            menusubblokreturpembelian.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    // END TWINCOMBO SUBBLOK

    var strpembelianretur = new Ext.data.Store({
        autoSave: false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_produk', allowBlank: false, type: 'text'},
                {name: 'nama_produk', allowBlank: false, type: 'text'},
                {name: 'no_faktur_pajak', allowBlank: false, type: 'text'},
                {name: 'satuan', allowBlank: false, type: 'text'},
                {name: 'qty', allowBlank: false, type: 'int'},
                {name: 'qty_terima', allowBlank: false, type: 'int'},
                {name: 'disk_persen_supp1', allowBlank: false, type: 'int'},
                {name: 'disk_persen_supp2', allowBlank: false, type: 'int'},
                {name: 'disk_persen_supp3', allowBlank: false, type: 'int'},
                {name: 'disk_persen_supp4', allowBlank: false, type: 'int'},
                {name: 'disk_amt_supp1', allowBlank: false, type: 'int'},
                {name: 'disk_amt_supp2', allowBlank: false, type: 'int'},
                {name: 'disk_amt_supp3', allowBlank: false, type: 'int'},
                {name: 'disk_amt_supp4', allowBlank: false, type: 'int'},
                {name: 'disk_amt_supp5', allowBlank: false, type: 'int'},
                {name: 'rp_total_diskon', allowBlank: false, type: 'int'},
                {name: 'hrg_supplier', allowBlank: false, type: 'int'},
                {name: 'harga', allowBlank: false, type: 'int'},
                {name: 'harga_exc', allowBlank: false, type: 'int'},
                {name: 'jumlah', allowBlank: false, type: 'int'},
                
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

    strpembelianretur.on('update', function() {
         jumlah = 0;

        strpembelianretur.each(function(node) {
            jumlah += (node.data.jumlah);
        });

        var diskon_persen = Ext.getCmp('pcret_diskon_persen').getValue();
        var diskon_rp = diskon_persen * jumlah;
        var sub_jumlah = jumlah - diskon_rp;
        var ppn_persen = Ext.getCmp('pcret_ppn_persen').getValue();
        var ppn_rp = (ppn_persen * sub_jumlah) / 100;
        var grand_total = sub_jumlah + ppn_rp;


        Ext.getCmp('pcret_jumlah').setValue(jumlah);
        Ext.getCmp('pcret_diskon_persen').setValue(diskon_persen);
        Ext.getCmp('pcret_diskon_rp').setValue(diskon_rp);
        Ext.getCmp('pcret_sub_jumlah').setValue(sub_jumlah);
        Ext.getCmp('pcret_ppn_persen').setValue(ppn_persen);
        Ext.getCmp('pcret_ppn_rp').setValue(ppn_rp);
        Ext.getCmp('pcret_total').setValue(grand_total);
    });

    var editorpembelianretur = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });
    var gridreturpembelian = new Ext.grid.GridPanel({
        stripeRows: true,
        height: 200,
        store: strpembelianretur,
        frame: true,
        border: true,
        plugins: [editorpembelianretur],
        tbar: [{
                icon: BASE_ICONS + 'add.png',
                text: 'Add',
                handler: function() {
                    if (Ext.getCmp('id_pcret_kd_supplier').getValue() == '') {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Silahkan pilih supplier terlebih dulu',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                        });
                        return;
                    }
                    var rowpembelianretur = new gridreturpembelian.store.recordType({
                        kd_produk: '',
                        qty: ''
                    });
                    editorpembelianretur.stopEditing();
                    strpembelianretur.insert(0, rowpembelianretur);
                    gridreturpembelian.getView().refresh();
                    gridreturpembelian.getSelectionModel().selectRow(0);
                    editorpembelianretur.startEditing(0);
                }
            }, {
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                disabled: true,
                handler: function() {
                    editorpembelianretur.stopEditing();
                    var s = gridreturpembelian.getSelectionModel().getSelections();
                    for (var i = 0, r; r = s[i]; i++) {
                        strpembelianretur.remove(r);
                    }
                }
            }],
        columns: [{
                header: 'Kode Produk',
                dataIndex: 'kd_produk',
                width: 200,
                sortable: true,
                editor: new Ext.ux.TwinCombopcretProduk({
                    id: 'epcret_kd_produk',
                    store: strcbpcretproduk,
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

            },
               {
                header: 'NO RO',
                dataIndex: 'no_do',
                width: 100,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epcret_no_do',
                    fieldClass: 'readonly-input'
                })
                
               }, {
                header: 'No Faktur Pajak',
                dataIndex: 'no_faktur_pajak',
                width: 120,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epcret_no_faktur_pajak',
                    fieldClass: 'readonly-input'
                })
                
               },{
               
                header: 'No Invoice',
                dataIndex: 'no_invoice',
                width: 200,
                format: '0',
                sortable: true,
                editor: new Ext.ux.TwinCombopcretinvoice({
                    id: 'epcret_no_invoice',
                    store: strcbpcretinvoice,
                    mode: 'local',
                    valueField: 'no_invoice',
                    displayField: 'no_invoice',
                    typeAhead: true,
                    triggerAction: 'all',
                    // allowBlank: false,
                    editable: false,
                    hiddenName: 'no_invoice',
                    emptyText: 'Pilih Invoice'

                })

            }, {
                header: 'Nama Barang',
                dataIndex: 'nama_produk',
                width: 300,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epcret_nama_produk',
                    fieldClass: 'readonly-input'
                })
            }, {
                header: 'Satuan',
                dataIndex: 'satuan',
                width: 50, readOnly: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epcret_satuan',
                    fieldClass: 'readonly-input'
                })
            }, {
                header: 'Qty Invoice',
                dataIndex: 'qty_terima',
                width: 100, readOnly: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epcret_qty_terima',
                    fieldClass: 'readonly-input number'
                })
            },{
                header: 'Qty Retur',
                dataIndex: 'qty_retur',
                width: 100, readOnly: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epcret_qty_retur',
                    fieldClass: 'readonly-input number'
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
                    id: 'epcret_qty',
                    // allowBlank: false,
                    selectOnFocus: true,
                    listeners: {
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                            if (Ext.getCmp('epcret_kd_produk').getValue() === '') {
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
                            if (Ext.getCmp('epcret_no_invoice').getValue() === '') {
                                Ext.Msg.show({
                                    title: 'Error',
                                    msg: 'Silahkan pilih no invoice terlebih dulu',
                                    modal: true,
                                    icon: Ext.Msg.ERROR,
                                    buttons: Ext.Msg.OK
                                });
                                this.setValue('0');
                                return;
                            }
                                var qty = this.getValue();
                                var qty_retur = parseFloat(Ext.getCmp('epcret_qty_retur').getValue());
                                var qty_terima = parseFloat(Ext.getCmp('epcret_qty_terima').getValue());
                                var retur = qty_retur + qty;
                                if (retur > qty_terima){
                                    Ext.Msg.show({
                                        title: 'Error',
                                        msg: 'Qty Retur + Qty tidak boleh lebih besar dari Qty Invoice',
                                        modal: true,
                                        icon: Ext.Msg.ERROR,
                                        buttons: Ext.Msg.OK,
                                        fn: function(btn){
                                            if (btn === 'ok') {
                                                Ext.getCmp('epcret_qty').reset();
                                                }
                                        }                            
                                    });
                                }
                            
                            var harga_exc = Ext.getCmp('epcret_harga_exc').getRawValue();
                            var jumlah = this.getValue() * harga_exc;
                            Ext.getCmp('epcret_jumlah').setValue(jumlah);
                        }, c);
                        }
                    }

                }
            }, {
                header: 'Kode Sub Blok',
                dataIndex: 'sub',
                width: 100,
                editor: new Ext.ux.TwinComboSubBlokPcret({
                    id: 'epcret_sub',
                    store: strcbkdsubblokpret,
                    valueField: 'sub',
                    displayField: 'sub',
                    typeAhead: true,
                    triggerAction: 'all',
                    //allowBlank: false,
                    editable: false,
                    hiddenName: 'sub',
                    emptyText: 'Pilih Sub Blok',
                    listeners: {
                        'expand': function() {
                            strcbkdsubblokpret.load();
                        }
                    }
                })
            }, {
                header: 'Sub Blok',
                dataIndex: 'nama_sub',
                width: 200,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epcret_nama_sub',
                    fieldClass: 'readonly-input'
                })
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
                    id: 'epcret_hrg_supplier',
                    readOnly: true,
                    fieldClass: 'readonly-input number'
                }
            }, {
                header: '% / Rp',
                dataIndex: 'disk_supp1_op',
                width: 50,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epcret_disk_supp1_op',
                    fieldClass: 'readonly-input'
                })
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Diskon Supplier 1',
                dataIndex: 'disk_supp1',
                width: 150,
                editor: {
                    xtype: 'numberfield',
                    id: 'epcret_disk_supp1',
                    readOnly: true,
                    fieldClass: 'readonly-input number'
                }
            }, {
                header: '% / Rp',
                dataIndex: 'disk_supp2_op',
                width: 50,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epcret_disk_supp2_op',
                    fieldClass: 'readonly-input'
                })
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Diskon Supplier 2',
                dataIndex: 'disk_supp2',
                width: 150,
                editor: {
                    xtype: 'numberfield',
                    id: 'epcret_disk_supp2',
                    readOnly: true,
                    fieldClass: 'readonly-input number'
                }
            }, {
                header: '% / Rp',
                dataIndex: 'disk_supp3_op',
                width: 50,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epcret_disk_supp3_op',
                    fieldClass: 'readonly-input'
                })
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Diskon Supplier 3',
                dataIndex: 'disk_supp3',
                width: 150,
                editor: {
                    xtype: 'numberfield',
                    id: 'epcret_disk_supp3',
                    readOnly: true,
                    fieldClass: 'readonly-input number'
                }
            }, {
                header: '% / Rp',
                dataIndex: 'disk_supp4_op',
                width: 50,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epcret_disk_supp4_op',
                    fieldClass: 'readonly-input'
                })
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Diskon Supplier 4',
                dataIndex: 'disk_supp4',
                width: 150,
                editor: {
                    xtype: 'numberfield',
                    id: 'epcret_disk_supp4',
                    readOnly: true,
                    fieldClass: 'readonly-input number'
                }
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Diskon Supplier 5',
                dataIndex: 'disk_amt_supp5',
                width: 150,
                editor: {
                    xtype: 'numberfield',
                    id: 'epcret_disk_amt_supp5',
                    readOnly: true,
                    fieldClass: 'readonly-input number'
                }
            },{
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Total Diskon',
                dataIndex: 'rp_total_diskon',
                width: 150,
                editor: {
                    xtype: 'numberfield',
                    id: 'epcret_total_diskon',
                    readOnly: true,
                    fieldClass: 'readonly-input number'
                }
            },  {
                xtype: 'numbercolumn',
                header: 'Harga',
                dataIndex: 'harga',
                width: 100,
                align: 'right',
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'epcret_harga',
                    readOnly: true,
                    fieldClass: 'readonly-input number'
                }
            }, {
                xtype: 'numbercolumn',
                header: 'Harga Exc.PPN',
                dataIndex: 'harga_exc',
                width: 100,
                align: 'right',
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'epcret_harga_exc',
                    readOnly: true,
                    fieldClass: 'readonly-input number'
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
                    id: 'epcret_jumlah',
                    readOnly: true,
                    fieldClass: 'readonly-input number'
                }
            }]
    });

    gridreturpembelian.getSelectionModel().on('selectionchange', function(sm) {
        gridreturpembelian.removeBtn.setDisabled(sm.getCount() < 1);
    });

    var winpembelianreturprint = new Ext.Window({
        id: 'id_winpembelianreturprint',
        title: 'Print Receive Order Form',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html: '<iframe style="width:100%;height:100%;" id="pembelianreturprint" src=""></iframe>'
    });

    var pembelianreturbeli = new Ext.FormPanel({
        id: 'pembelianreturbeli',
        border: false,
        frame: true,
        autoScroll: true,
        bodyStyle: 'padding-right:20px;',
        labelWidth: 130,
        items: [{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },
                items: [headerpembelianretur]
            },
            gridreturpembelian,
            {
                layout: 'column',
                border: false,
                items: [{
                        columnWidth: .6,
                        style: 'margin:6px 3px 0 0;',
                        layout: 'form',
                        labelWidth: 70,
                        items: [
                            {
                                xtype: 'textarea',
                                fieldLabel: 'Remark <span class="asterix">*</span>',
                                name: 'remark',
                                id: 'pcret_remark',
                                width: 300, 
                                allowBlank: false
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
                                        id: 'pcret_jumlah',
                                        anchor: '90%',
                                        fieldClass: 'readonly-input number',
                                        format: '0',
                                        alwaysDisplayDecimals: false
                                    }, {
                                        xtype: 'hidden',
                                        //                                                hidden: true,
                                        currencySymbol: '',
                                        format: '0',
                                        name: 'diskon_persen',
                                        id: 'pcret_diskon_persen',
                                        fieldClass: 'number',
                                        width: 60,
                                        value: '0',
                                        // allowBlank:false,
                                        maxValue: 100,
                                        selectOnFocus: true,
                                        listeners: {
                                            'change': function() {
                                                var jumlah = Ext.getCmp('pcret_jumlah').getValue();
                                                var diskon_rp = (this.getValue() * jumlah) / 100;
                                                var sub_jumlah = jumlah - diskon_rp;
                                                var ppn_persen = Ext.getCmp('pcret_ppn_persen').getValue();
                                                var ppn_rp = (ppn_persen * sub_jumlah) / 100;
                                                var grand_total = sub_jumlah + ppn_rp;

                                                Ext.getCmp('pcret_diskon_rp').setValue(diskon_rp);
                                                Ext.getCmp('pcret_sub_jumlah').setValue(sub_jumlah);
                                                Ext.getCmp('pcret_ppn_rp').setValue(ppn_rp);
                                                Ext.getCmp('pcret_total').setValue(grand_total);

                                            }
                                        }

                                    },
                                    {
                                        xtype: 'hidden',
                                        value: '%'
                                                //                                                ,hidden: true
                                    },
                                    {
                                        xtype: 'hidden',
                                        name: 'diskon_rp',
                                        id: 'pcret_diskon_rp',
                                        currencySymbol: '',
                                        fieldClass: 'readonly-input number',
                                        readOnly: true,
                                        anchor: '100%',
                                        value: '0'
                                                //                                                ,hidden: true

                                    }

                                    , {
                                        xtype: 'hidden',
                                        currencySymbol: '',
                                        //                                        fieldLabel: 'Sub Jumlah',
                                        name: 'sub_jumlah',
                                        id: 'pcret_sub_jumlah',
                                        anchor: '90%',
                                        readOnly: true,
                                        cls: 'vertical-space',
                                        fieldClass: 'readonly-input number',
                                        labelStyle: 'margin-top:10px;',
                                        value: '0'
                                                //                                        ,hidden: true                                                                                   
                                    }, {
                                        xtype: 'compositefield',
                                        fieldLabel: 'PPN',
                                        combineErrors: false,
                                        items: [
                                            {
                                                xtype: 'numericfield',
                                                currencySymbol: '',
                                                format: '0',
                                                name: 'ppn_persen',
                                                id: 'pcret_ppn_persen',
                                                fieldClass: 'number',
                                                width: 60,
                                                // allowBlank:false,
                                                value: '',
                                                fieldClass: 'readonly-input number',
                                                readOnly: true,
                                                maxValue: 100,
                                                listeners: {
                                                    'change': function() {
                                                        var sub_jumlah = Ext.getCmp('pcret_sub_jumlah').getValue();
                                                        var ppn_rp = parseFloat(this.getValue() * sub_jumlah) / 100;
                                                        var grand_total = sub_jumlah + ppn_rp;

                                                        Ext.getCmp('pcret_ppn_rp').setValue(ppn_rp);
                                                        Ext.getCmp('pcret_total').setValue(grand_total);

                                                    }
                                                }

                                            },
                                            {
                                                xtype: 'displayfield',
                                                value: '%'
                                            },
                                            {
                                                xtype: 'numericfield',
                                                name: 'ppn_rp',
                                                id: 'pcret_ppn_rp',
                                                currencySymbol: '',
                                                fieldClass: 'readonly-input number',
                                                readOnly: true,
                                                anchor: '100%',
                                                value: '0'

                                            }
                                        ]
                                    }, {
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: '<b>Grand Total</b>',
                                        name: 'total',
                                        cls: 'vertical-space',
                                        readOnly: true,
                                        id: 'pcret_total',
                                        anchor: '90%',
                                        fieldClass: 'readonly-input bold-input number',
                                        labelStyle: 'margin-top:10px;',
                                        value: '0'
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
                    if (Ext.getCmp('pcret_total').getValue() == 0) {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Tidak ada retur pembelian!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK

                        });
                        return;
                    }
                    if(Ext.getCmp('epcret_sub').getValue() ==''){
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'kode sub blok harus di isi!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                            
                        });
                        return;
                    }
                    var detailpembeliancreatepononrequest = new Array();
                    strpembelianretur.each(function(node) {
                        detailpembeliancreatepononrequest.push(node.data)
                    });

                    // var mkonsi=Ext.getCmp('id_pcret_is_konsinyasi').getValue();
                    // var konsi=null;
                    // if (mkonsi){
                    // konsi=1;
                    // }else{konsi=0;}

                    Ext.getCmp('pembelianreturbeli').getForm().submit({
                        url: '<?= site_url("pembelian_retur/update_row") ?>',
                        scope: this,
                        params: {
                            detail: Ext.util.JSON.encode(detailpembeliancreatepononrequest),
                            //_dp: Ext.getCmp('pcret_dp').getValue(),
                            _jumlah: Ext.getCmp('pcret_jumlah').getValue(),
                            _diskon_rp: Ext.getCmp('pcret_diskon_rp').getValue(),
                            _ppn_persen: Ext.getCmp('pcret_ppn_persen').getValue(),
                            _ppn_rp: Ext.getCmp('pcret_ppn_rp').getValue(),
                            _total: Ext.getCmp('pcret_total').getValue(),
                            _remark: Ext.getCmp('pcret_remark').getValue()
                                    // pis_konsinyasi:konsi
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
                                        winpembelianreturprint.show();
                                        Ext.getDom('pembelianreturprint').src = r.printUrl;
                                    }
                                }
                            });

                            clearpembelianretur();
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
                    clearpembelianretur();
                }
            }],
        listeners: {
            afterrender: function() {

                this.getForm().load({
                    url: '<?= site_url("pembelian_retur/get_form") ?>',
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
            }
        }
    });

    function clearpembelianretur() {
        Ext.getCmp('pembelianreturbeli').getForm().reset();
        Ext.getCmp('pembelianreturbeli').getForm().load({
            url: '<?= site_url("pembelian_retur/get_form") ?>',
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
        strpembelianretur.removeAll();
        strgrid_retbeli_supplier.removeAll();
    }
</script>