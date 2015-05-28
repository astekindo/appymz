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

    var strgrid_retRO_supplier = new Ext.data.Store({
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

    var searchgrid_retRO_supplier = new Ext.app.SearchField({
        store: strgrid_retRO_supplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgrid_retRO_supplier'
    });


    var grid_retRO_supplier = new Ext.grid.GridPanel({
        store: strgrid_retRO_supplier,
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
            items: [searchgrid_retRO_supplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgrid_retRO_supplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('rro_kd_supplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_cbrrosuplier').setValue(sel[0].get('nama_supplier'));
                    Ext.getCmp('rro_status_pkp').setValue(sel[0].get('pkp'));
                    
                    var pkp = sel[0].get('pkp');
                    if (pkp === 'YA') {
                       // Ext.getCmp('rro_ppn_persen').setValue(10);
                        
                    } else {
                        //Ext.getCmp('rro_ppn_persen').setValue(0);
                        
                    }
                    strreturreceiveorder.removeAll();
                    menu_retRO_supplier.hide();
                }
            }
        }
    });

    var menu_retRO_supplier = new Ext.menu.Menu();
    menu_retRO_supplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [grid_retRO_supplier],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menu_retRO_supplier.hide();
                }
            }]
    }));

    Ext.ux.TwinComboReturROSupplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgrid_retRO_supplier.load();
            menu_retRO_supplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menu_retRO_supplier.on('hide', function() {
        var sf = Ext.getCmp('id_searchgrid_retRO_supplier').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgrid_retRO_supplier').setValue('');
            searchgrid_retRO_supplier.onTrigger2Click();
        }
    });

    var cbrrosuplier = new Ext.ux.TwinComboReturROSupplier({
        fieldLabel: 'Nama Supplier <span class="asterix">*</span>',
        id: 'id_cbrrosuplier',
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

// twin combo no ro
    var str_rro_noro = new Ext.data.ArrayStore({
        fields: ['no_do'],
        data: []
    });

    var strgrid_rro_noro = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_do', 'tanggal_po', 'no_po','tanggal'],
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

    var searchgrid_rro_noro = new Ext.app.SearchField({
        store: strgrid_rro_noro,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgrid_rro_noro'
    });

    searchgrid_rro_noro.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';

            // Get the value of search field
            var fid = Ext.getCmp('rro_kd_supplier').getValue();
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

    searchgrid_rro_noro.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }

        // Get the value of search field
        var fid = Ext.getCmp('rro_kd_supplier').getValue();
        var o = {start: 0, kd_supplier: fid};

        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params: o});
        this.hasSearch = true;
        this.triggers[0].show();
    };

    var grid_rro_noro = new Ext.grid.GridPanel({
        store: strgrid_rro_noro,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'No RO',
                dataIndex: 'no_do',
                width: 100,
                sortable: true
            }, {
                header: 'Tanggal RO',
                dataIndex: 'tanggal',
                width: 170,
                sortable: true
            }, {
                header: 'NO PO',
                dataIndex: 'no_po',
                width: 100,
                sortable: true
            },{
                header: 'Tanggal PO',
                dataIndex: 'tanggal_po',
                width: 170,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchgrid_rro_noro]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgrid_rro_noro,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cbreturnoro').setValue(sel[0].get('no_do'));
                    Ext.getCmp('rro_tgl_ro').setValue(sel[0].get('tanggal'));
//                    Ext.getCmp('rro_no_po').setValue(sel[0].get('no_po'));
//                    Ext.getCmp('rro_tgl_po').setValue(sel[0].get('tanggal_po'));
                    //strpembelianretur.removeAll();
                    menu_rro_noro.hide();
                }
            }
        }
    });

    var menu_rro_noro = new Ext.menu.Menu();
    menu_rro_noro.add(new Ext.Panel({
        title: 'Pilih No RO',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [grid_rro_noro],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menu_rro_noro.hide();
                }
            }]
    }));

    Ext.ux.TwinComboVRRONORO = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            //strgrid_rro_noro.load();
             strgrid_rro_noro.load({
                params: {
                    kd_supplier: Ext.getCmp('rro_kd_supplier').getValue(),
                    //kd_produk: Ext.getCmp('erro_kd_produk').getValue()
                }
            });
            menu_rro_noro.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menu_rro_noro.on('hide', function() {
        var sf = Ext.getCmp('id_searchgrid_rro_noro').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgrid_rro_noro').setValue('');
            searchgrid_rro_noro.onTrigger2Click();
        }
    });

    var cbreturnoro = new Ext.ux.TwinComboVRRONORO({
        fieldLabel: 'NO RO',
        id: 'id_cbreturnoro',
        store: str_rro_noro,
        mode: 'local',
        valueField: 'no_do',
        displayField: 'no_do',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: true,
        editable: false,
        anchor: '90%',
        hiddenName: 'no_do',
        emptyText: 'Pilih No RO'
    });
    //end twincombo no ro
    
    var headerreturreceiveorder = {
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
                        id: 'rro_no_retur',
                        maxLength: 255,
                        anchor: '90%',
                        value: ''
                    }, {
                        xtype: 'datefield',
                        fieldLabel: 'Tanggal <span class="asterix">*</span>',
                        name: 'tgl_retur',
                        id: 'rro_tglretur',
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
                        id: 'rro_kd_supplier',
                        anchor: '90%',
                        value: ''
                    }, cbrrosuplier
                    
                 ]
            },{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [ cbreturnoro,
                    {
                        xtype: 'textfield',
                        fieldLabel: 'Tanggal RO',
                        name: 'tgl_ro',
                        fieldClass: 'readonly-input',
                        readOnly: true,
                        id: 'rro_tgl_ro',
                        anchor: '90%',
                        value: ''
                    },{
                        xtype: 'textfield',
                        fieldLabel: 'Status PKP',
                        name: 'status_pkp',
                        fieldClass: 'readonly-input',
                        readOnly: true,
                        id: 'rro_status_pkp',
                        anchor: '90%',
                        value: ''
                    }
//                    {
//                        xtype: 'textfield',
//                        fieldLabel: 'NO PO',
//                        name: 'no_po',
//                        fieldClass: 'readonly-input',
//                        readOnly: true,
//                        id: 'rro_no_po',
//                        anchor: '90%',
//                        value: ''
//                    },{
//                        xtype: 'textfield',
//                        fieldLabel: 'Tanggal PO',
//                        name: 'tgl_po',
//                        fieldClass: 'readonly-input',
//                        readOnly: true,
//                        id: 'rro_tgl_po',
//                        anchor: '90%',
//                        value: ''
//                    }

                ]
            }
        ]
    };
    // twin barang
    var strcbrroproduk = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data: []
    });

    var strgridrroproduk = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [{name: 'kd_produk', allowBlank: false, type: 'text'},
                {name: 'kd_produk_lama', allowBlank: false, type: 'text'},
                {name: 'sub', allowBlank: false, type: 'text'},
                {name: 'nama_sub', allowBlank: false, type: 'text'},
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
                {name: 'rp_total_diskon', allowBlank: false, type: 'float'},
                {name: 'hrg_supplier', allowBlank: false, type: 'int'},
                {name: 'harga', allowBlank: false, type: 'float'},
                {name: 'harga_exc', allowBlank: false, type: 'float'},
                {name: 'jumlah', allowBlank: false, type: 'float'},
                {name: 'qty_terima', allowBlank: false, type: 'int'},
                {name: 'qty_retur', allowBlank: false, type: 'int'}],
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


    var searchgridrroproduk = new Ext.app.SearchField({
        store: strgridrroproduk,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridrroproduk'
    });

    searchgridrroproduk.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';

            // Get the value of search field
            var fid = Ext.getCmp('rro_kd_supplier').getValue();
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

    searchgridrroproduk.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }

        // Get the value of search field
        var fid = Ext.getCmp('rro_kd_supplier').getValue();
        var o = {start: 0, kd_supplier: fid};

        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params: o});
        this.hasSearch = true;
        this.triggers[0].show();
    };

    var gridrroproduk = new Ext.grid.GridPanel({
        store: strgridrroproduk,
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
                width: 200,
                sortable: true
            },{
                header: 'Qty RO',
                dataIndex: 'qty_terima',
                width: 80,
                sortable: true
            },{
                header: 'Kode Sub Blok',
                dataIndex: 'sub',
                width: 100,
                sortable: true
            },{
                header: 'Nama Sub Blok',
                dataIndex: 'nama_sub',
                width: 100,
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
            items: [searchgridrroproduk]
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                   var _ada = false;
                                
                    strreturreceiveorder.each(function(record){
                        if(record.get('kd_produk') === sel[0].get('kd_produk') && (record.get('no_po') === Ext.getCmp('erro_no_po').getValue())){
                            _ada = true;
                        }
                    });

                    if (_ada){
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Produk Berdasarkan No Po sudah pernah dipilih',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK,
                            fn: function(btn){
                                if (btn == 'ok') {
                                    Ext.getCmp('erro_kd_produk').reset();
                                }
                            }                            
                        });
                        Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                        Ext.getCmp('erro_kd_produk').focus();	
                        return;
                    }
                    
                    Ext.getCmp('erro_kd_produk').setValue(sel[0].get('kd_produk'));
                    Ext.getCmp('erro_nama_produk').setValue(sel[0].get('nama_produk'));
                    Ext.getCmp('erro_qty_retur').setValue(sel[0].get('qty_retur'));
                    Ext.getCmp('erro_qty_terima').setValue(sel[0].get('qty_terima'));
                    Ext.getCmp('erro_satuan').setValue(sel[0].get('nm_satuan'));
                    Ext.getCmp('erro_disk_supp1_op').setValue(sel[0].get('disk_supp1_op'));
                    Ext.getCmp('erro_disk_supp2_op').setValue(sel[0].get('disk_supp2_op'));
                    Ext.getCmp('erro_disk_supp3_op').setValue(sel[0].get('disk_supp3_op'));
                    Ext.getCmp('erro_disk_supp4_op').setValue(sel[0].get('disk_supp4_op'));

                    Ext.getCmp('erro_disk_supp1').setValue(sel[0].get('disk_supp1'));
                    Ext.getCmp('erro_disk_supp2').setValue(sel[0].get('disk_supp2'));
                    Ext.getCmp('erro_disk_supp3').setValue(sel[0].get('disk_supp3'));
                    Ext.getCmp('erro_disk_supp4').setValue(sel[0].get('disk_supp4'));
                    Ext.getCmp('erro_disk_amt_supp5').setValue(sel[0].get('disk_amt_supp5'));
                    
                    Ext.getCmp('erro_total_diskon').setValue(sel[0].get('rp_total_diskon'));
                    Ext.getCmp('erro_hrg_supplier').setValue(sel[0].get('hrg_supplier'));
                    Ext.getCmp('erro_harga').setValue(sel[0].get('harga'));
                    Ext.getCmp('erro_sub').setValue(sel[0].get('sub'));
                    Ext.getCmp('erro_nama_sub').setValue(sel[0].get('nama_sub'));
//                    harga  = Ext.getCmp('erro_harga').getValue();
//                    if (Ext.getCmp('rro_status_pkp').getValue()=== 'YA'){
//                         harga_exc = harga / 1.1 ;
//                    }else {
//                         harga_exc = harga ;
//                    }
                    Ext.getCmp('erro_harga_exc').setValue(sel[0].get('harga_exc'));
                    Ext.getCmp('erro_jumlah').setValue(sel[0].get('jumlah'));
                    //Ext.getCmp('erro_qty_terima').setValue('');
                    Ext.getCmp('erro_qty').focus();
                    menurroproduk.hide();
                }
            }
        }
    });

    var menurroproduk = new Ext.menu.Menu();
    menurroproduk.add(new Ext.Panel({
        title: 'Pilih Produk',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 600,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridrroproduk],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menurroproduk.hide();
                }
            }]
    }));

    menurroproduk.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridrroproduk').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgridrroproduk').setValue('');
            searchgridrroproduk.onTrigger2Click();
        }
    });

    Ext.ux.TwinComboRROProduk = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            strgridrroproduk.load({
                params: {
                    kd_supplier: Ext.getCmp('rro_kd_supplier').getValue(),
                    no_ro: Ext.getCmp('id_cbreturnoro').getValue(),
                    no_po: Ext.getCmp('erro_no_po').getValue()
                }
            });
            menurroproduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    //end twin produk   

      
    
    // START TWINCOMBO SUBBLOK

//    var strcbkdsubblokrro = new Ext.data.Store({
//        reader: new Ext.data.JsonReader({
//            fields: ['sub', 'nama_sub'],
//            root: 'data',
//            totalproperty: 'record'
//        }),
//        proxy: new Ext.data.HttpProxy({
//            url: '<?= site_url("pembelian_receive_order/get_sub_blok") ?>',
//            method: 'POST'
//        }),
//        listeners: {
//            loadexception: function(event, options, response, error) {
//                var err = Ext.util.JSON.decode(response.responseText);
//                if (err.errMsg == 'Session Expired') {
//                    session_expired(err.errMsg);
//                }
//            }
//        }
//    });
//
//    var strgridsubblokrro = new Ext.data.Store({
//        reader: new Ext.data.JsonReader({
//            fields: [
//                'sub',
//                'nama_sub',
//                'kd_sub_blok',
//                'kd_blok',
//                'kd_lokasi',
//                'nama_lokasi',
//                'nama_blok',
//                'nama_sub_blok',
//                'kapasitas'
//            ],
//            root: 'data',
//            totalproperty: 'record'
//        }),
//        proxy: new Ext.data.HttpProxy({
//            url: '<?= site_url("pembelian_receive_order/get_rows_lokasi") ?>',
//            method: 'POST'
//        }),
//        listeners: {
//            loadexception: function(event, options, response, error) {
//                var err = Ext.util.JSON.decode(response.responseText);
//                if (err.errMsg == 'Session Expired') {
//                    session_expired(err.errMsg);
//                }
//            }
//        }
//    });
//
//    // search field
//    var searchgridsubblokrro = new Ext.app.SearchField({
//        store: strgridsubblokrro,
//        params: {
//            start: STARTPAGE,
//            limit: ENDPAGE
//        },
//        width: 220,
//        id: 'idsearchgridsubblokrro'
//    });
//
//    // top toolbar
//    var tbgridsubblokrro = new Ext.Toolbar({
//        items: [searchgridsubblokrro]
//    });
//
//    var gridsubblokrro = new Ext.grid.GridPanel({
//        store: strgridsubblokrro,
//        stripeRows: true,
//        frame: true,
//        border: true,
//        tbar: tbgridsubblokrro,
//        bbar: new Ext.PagingToolbar({
//            pageSize: ENDPAGE,
//            store: strgridsubblokrro,
//            displayInfo: true
//        }),
//        columns: [{
//                dataIndex: 'kd_lokasi',
//                hidden: true
//            }, {
//                dataIndex: 'kd_blok',
//                hidden: true
//            }, {
//                dataIndex: 'kd_sub_blok',
//                hidden: true
//            }, {
//                header: 'Kode',
//                dataIndex: 'sub',
//                width: 90,
//                sortable: true
//
//            }, {
//                header: 'Sub Blok Lokasi',
//                dataIndex: 'nama_sub',
//                width: 200,
//                sortable: true
//            }],
//        listeners: {
//            'rowdblclick': function() {
//                var sm = this.getSelectionModel();
//                var sel = sm.getSelections();
//                if (sel.length > 0) {
//                    Ext.getCmp('erro_sub').setValue(sel[0].get('sub'));
//                    Ext.getCmp('erro_nama_sub').setValue(sel[0].get('nama_sub'));
//
//                    menusubblokreturreceiveorder.hide();
//                }
//            }
//        }
//    });
//
//    var menusubblokreturreceiveorder = new Ext.menu.Menu();
//    menusubblokreturreceiveorder.add(new Ext.Panel({
//        title: 'Pilih Sub Blok Lokasi',
//        layout: 'fit',
//        buttonAlign: 'left',
//        modal: true,
//        width: 350,
//        height: 250,
//        closeAction: 'hide',
//        plain: true,
//        items: [gridsubblokrro],
//        buttons: [{
//                text: 'Close',
//                handler: function() {
//                    menusubblokreturreceiveorder.hide();
//                }
//            }]
//    }));
//
//    Ext.ux.TwinComboSubBlokRRO = Ext.extend(Ext.form.ComboBox, {
//        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
//        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
//        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
//        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
//        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
//        onTrigger2Click: function() {
//            //load store grid
////            strgridsubblokrro.load({
////                params: {
////                    kd_produk: Ext.getCmp('erro_kd_produk').getValue()
////                }
////            });
//            strgridsubblokrro.setBaseParam('kd_produk',Ext.getCmp('erro_kd_produk').getValue());
//            strgridsubblokrro.load();
//            menusubblokreturreceiveorder.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
//        },
//        validationEvent: false,
//        validateOnBlur: false,
//        trigger1Class: 'x-form-clear-trigger',
//                trigger2Class: 'x-form-search-trigger',
//        hideTrigger1: true
//    });

    // END TWINCOMBO SUBBLOK

    var strreturreceiveorder = new Ext.data.Store({
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

    strreturreceiveorder.on('update', function() {
         var jumlah = 0;

        strreturreceiveorder.each(function(node) {
            jumlah += (node.data.jumlah);
        });

//        var diskon_persen = Ext.getCmp('rro_diskon_persen').getValue();
//        var diskon_rp = diskon_persen * jumlah;
//        var sub_jumlah = jumlah - diskon_rp;
//        var ppn_persen = Ext.getCmp('rro_ppn_persen').getValue();
//        var ppn_rp = (ppn_persen * sub_jumlah) / 100;
//        var grand_total = sub_jumlah + ppn_rp;

        jumlah = Math.round(jumlah);
        Ext.getCmp('rro_jumlah').setValue(jumlah);
//        Ext.getCmp('rro_diskon_persen').setValue(diskon_persen);
//        Ext.getCmp('rro_diskon_rp').setValue(diskon_rp);
//        Ext.getCmp('rro_sub_jumlah').setValue(sub_jumlah);
//        Ext.getCmp('rro_ppn_persen').setValue(ppn_persen);
//        Ext.getCmp('rro_ppn_rp').setValue(ppn_rp);
        //Ext.getCmp('rro_total').setValue(grand_total);
    });

 // TWIN NO PO
    var strcbrronopo = new Ext.data.ArrayStore({
        fields: ['no_po'],
        data : []
    });
	
      var strgridrronopo = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_po','tanggal_po'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_retur/get_all_po") ?>',
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
	
    strgridrronopo.on('load', function(){
        Ext.getCmp('search_query_rro_no_po').focus();
    });
	
    var searchFieldrroRONoPO = new Ext.app.SearchField({
        width: 220,
        id: 'search_query_rro_no_po',
        store: strgridrronopo
    });
    searchFieldrroRONoPO.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';
			
            // Get the value of search field
            var fid = Ext.getCmp('id_cbreturnoro').getValue();
            var o = { start: 0, no_ro: fid };
			
            this.store.baseParams = this.store.baseParams || {};
            this.store.baseParams[this.paramName] = '';
            this.store.reload({
                params : o
            });
            this.triggers[0].hide();
            this.hasSearch = false;
        }
    };
	
    searchFieldrroRONoPO.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }
	 
        // Get the value of search field
        var fid = Ext.getCmp('id_cbreturnoro').getValue();
        var o = { start: 0, no_ro: fid };
	 
        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params:o});
        this.hasSearch = true;
        this.triggers[0].show();
    };
	
    // top toolbar
    var tbsearchrronopo = new Ext.Toolbar({
        items: [searchFieldrroRONoPO]
    });
	
    var gridrronopo = new Ext.grid.GridPanel({
        store: strgridrronopo,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'No PO',
                dataIndex: 'no_po',
                width: 200,
                sortable: true,			
            
            },{
                header: 'Tgl PO',
                dataIndex: 'tanggal_po',
                width: 200,
                sortable: true,			
            
            }],
        tbar:tbsearchrronopo,
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                    Ext.getCmp('erro_no_po').setValue(sel[0].get('no_po'));
                    menurronopo.hide();
                    
                }
            }
        }
    });
	
    var menurronopo = new Ext.menu.Menu();
    menurronopo.add(new Ext.Panel({
        title: 'Pilih No PO',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 300,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridrronopo],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menurronopo.hide();
                }
            }]
    }));
    
    Ext.ux.TwinComborroNoPO = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgridrronopo.load({
                params: {
                    no_ro: Ext.getCmp('id_cbreturnoro').getValue()                                 
                }
            });
            menurronopo.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    var editorreturreceiveorder = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });
    var gridreturreceiveorder = new Ext.grid.GridPanel({
        stripeRows: true,
        height: 200,
        store: strreturreceiveorder,
        frame: true,
        border: true,
        plugins: [editorreturreceiveorder],
        tbar: [{
                icon: BASE_ICONS + 'add.png',
                text: 'Add',
                handler: function() {
                    if (Ext.getCmp('rro_kd_supplier').getValue() == '') {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Silahkan pilih supplier terlebih dulu',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                        });
                        return;
                    }
                    if (Ext.getCmp('id_cbreturnoro').getValue() == '') {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Silahkan pilih no ro terlebih dulu',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                        });
                        return;
                    }
                    var rowpembelianretur = new gridreturreceiveorder.store.recordType({
                        kd_produk: '',
                        qty: ''
                    });
                    editorreturreceiveorder.stopEditing();
                    strreturreceiveorder.insert(0, rowpembelianretur);
                    gridreturreceiveorder.getView().refresh();
                    gridreturreceiveorder.getSelectionModel().selectRow(0);
                    editorreturreceiveorder.startEditing(0);
                }
            }, {
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                disabled: true,
                handler: function() {
                    editorreturreceiveorder.stopEditing();
                    var s = gridreturreceiveorder.getSelectionModel().getSelections();
                    for (var i = 0, r; r = s[i]; i++) {
                        strreturreceiveorder.remove(r);
                    }
                    var jumlah = 0;

                    strreturreceiveorder.each(function(node) {
                        jumlah += (node.data.jumlah);
                    });
            
                    Ext.getCmp('rro_jumlah').setValue(jumlah);
                    
                }
            }],
        columns: [{
                header: 'No PO',
                dataIndex: 'no_po',
                width: 140,
                editor: new Ext.ux.TwinComborroNoPO({
                    id: 'erro_no_po',
                    store: strcbrronopo,
                    mode: 'local',
                    valueField: 'no_po',
                    displayField: 'no_po',
                    typeAhead: true,
                    triggerAction: 'all',
                    allowBlank: false,
                    editable: false,
                    hiddenName: 'no_po',
                    emptyText: 'Pilih No PO'
				
                })          
            },{
                header: 'Kode Produk',
                dataIndex: 'kd_produk',
                width: 200,
                sortable: true,
                editor: new Ext.ux.TwinComboRROProduk({
                    id: 'erro_kd_produk',
                    store: strcbrroproduk,
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
                header: 'Nama Barang',
                dataIndex: 'nama_produk',
                width: 300,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'erro_nama_produk',
                    fieldClass: 'readonly-input'
                })
            }, {
                header: 'Satuan',
                dataIndex: 'satuan',
                width: 50, readOnly: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'erro_satuan',
                    fieldClass: 'readonly-input'
                })
            }, {
                header: 'Qty RO',
                dataIndex: 'qty_terima',
                width: 100, readOnly: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'erro_qty_terima',
                    fieldClass: 'readonly-input number'
                })
            },{
                header: 'Qty Retur',
                dataIndex: 'qty_retur',
                width: 100, readOnly: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'erro_qty_retur',
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
                    id: 'erro_qty',
                    // allowBlank: false,
                    selectOnFocus: true,
                    listeners: {
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                            if (Ext.getCmp('erro_kd_produk').getValue() === '') {
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
//                            if (Ext.getCmp('erro_no_do').getValue() === '') {
//                                Ext.Msg.show({
//                                    title: 'Error',
//                                    msg: 'Silahkan pilih No RO terlebih dulu',
//                                    modal: true,
//                                    icon: Ext.Msg.ERROR,
//                                    buttons: Ext.Msg.OK
//                                });
//                                this.setValue('0');
//                                return;
//                            }
                                var qty = this.getValue();
                                var qty_retur = parseFloat(Ext.getCmp('erro_qty_retur').getValue());
                                var qty_terima = parseFloat(Ext.getCmp('erro_qty_terima').getValue());
                                var retur = qty_retur + qty;
                                if (retur > qty_terima){
                                    Ext.Msg.show({
                                        title: 'Error',
                                        msg: 'Qty Retur + Qty tidak boleh lebih besar dari Qty RO',
                                        modal: true,
                                        icon: Ext.Msg.ERROR,
                                        buttons: Ext.Msg.OK,
                                        fn: function(btn){
                                            if (btn === 'ok') {
                                                Ext.getCmp('erro_qty').reset();
                                                }
                                        }                            
                                    });
                                }
                            
                            var harga_exc = Ext.getCmp('erro_harga_exc').getRawValue();
                            var jumlah = this.getValue() * harga_exc;
                            console.log(harga_exc);
                            console.log(jumlah);
                            Ext.getCmp('erro_jumlah').setValue(jumlah);
                        }, c);
                        }
                    }

                }
            }, {
                header: 'Kode Sub Blok',
                dataIndex: 'sub',
                width: 100,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'erro_sub',
                    fieldClass: 'readonly-input'
                })
//                editor: new Ext.ux.TwinComboSubBlokRRO({
//                    id: 'erro_sub',
//                    store: strcbkdsubblokrro,
//                    valueField: 'sub',
//                    displayField: 'sub',
//                    typeAhead: true,
//                    triggerAction: 'all',
//                    //allowBlank: false,
//                    editable: false,
//                    hiddenName: 'sub',
//                    emptyText: 'Pilih Sub Blok',
//                    listeners: {
//                        'expand': function() {
//                            strcbkdsubblokrro.load();
//                        }
//                    }
//                })
            }, {
                header: 'Sub Blok',
                dataIndex: 'nama_sub',
                width: 200,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'erro_nama_sub',
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
                    id: 'erro_hrg_supplier',
                    readOnly: true,
                    fieldClass: 'readonly-input number'
                }
            }, {
                header: '% / Rp',
                dataIndex: 'disk_supp1_op',
                width: 50,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'erro_disk_supp1_op',
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
                    id: 'erro_disk_supp1',
                    readOnly: true,
                    fieldClass: 'readonly-input number'
                }
            }, {
                header: '% / Rp',
                dataIndex: 'disk_supp2_op',
                width: 50,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'erro_disk_supp2_op',
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
                    id: 'erro_disk_supp2',
                    readOnly: true,
                    fieldClass: 'readonly-input number'
                }
            }, {
                header: '% / Rp',
                dataIndex: 'disk_supp3_op',
                width: 50,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'erro_disk_supp3_op',
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
                    id: 'erro_disk_supp3',
                    readOnly: true,
                    fieldClass: 'readonly-input number'
                }
            }, {
                header: '% / Rp',
                dataIndex: 'disk_supp4_op',
                width: 50,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'erro_disk_supp4_op',
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
                    id: 'erro_disk_supp4',
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
                    id: 'erro_disk_amt_supp5',
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
                    id: 'erro_total_diskon',
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
                    id: 'erro_harga',
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
                    id: 'erro_harga_exc',
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
                    id: 'erro_jumlah',
                    readOnly: true,
                    fieldClass: 'readonly-input number'
                }
            }]
    });

    gridreturreceiveorder.getSelectionModel().on('selectionchange', function(sm) {
        gridreturreceiveorder.removeBtn.setDisabled(sm.getCount() < 1);
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

    var returrecieveorder = new Ext.FormPanel({
        id: 'returrecieveorder',
        border: false,
        frame: true,
        autoScroll: true,
        bodyStyle: 'padding-right:20px;',
        labelWidth: 130,
        items: [{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },
                items: [headerreturreceiveorder]
            },
            gridreturreceiveorder,
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
                                id: 'rro_remark',
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
                                        id: 'rro_jumlah',
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
                                        id: 'rro_diskon_persen',
                                        fieldClass: 'number',
                                        width: 60,
                                        value: '0',
                                        // allowBlank:false,
                                        maxValue: 100,
                                        selectOnFocus: true,
                                        listeners: {
                                            'change': function() {
                                                var jumlah = Ext.getCmp('rro_jumlah').getValue();
                                                var diskon_rp = (this.getValue() * jumlah) / 100;
                                                var sub_jumlah = jumlah - diskon_rp;
                                                var ppn_persen = Ext.getCmp('rro_ppn_persen').getValue();
                                                var ppn_rp = (ppn_persen * sub_jumlah) / 100;
                                                var grand_total = sub_jumlah + ppn_rp;

                                                Ext.getCmp('rro_diskon_rp').setValue(diskon_rp);
                                                Ext.getCmp('rro_sub_jumlah').setValue(sub_jumlah);
                                                Ext.getCmp('rro_ppn_rp').setValue(ppn_rp);
                                                Ext.getCmp('rro_total').setValue(grand_total);

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
                                        id: 'rro_diskon_rp',
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
                                        id: 'rro_sub_jumlah',
                                        anchor: '90%',
                                        readOnly: true,
                                        cls: 'vertical-space',
                                        fieldClass: 'readonly-input number',
                                        labelStyle: 'margin-top:10px;',
                                        value: '0'
                                                //                                        ,hidden: true                                                                                   
                                    }, {
                                        xtype: 'hidden',
                                        fieldLabel: 'PPN',
                                        combineErrors: false,
                                        items: [
                                            {
                                                xtype: 'hidden',
                                                currencySymbol: '',
                                                format: '0',
                                                name: 'ppn_persen',
                                                id: 'rro_ppn_persen',
                                                fieldClass: 'number',
                                                width: 60,
                                                // allowBlank:false,
                                                value: '',
                                                fieldClass: 'readonly-input number',
                                                readOnly: true,
                                                maxValue: 100,
                                                listeners: {
                                                    'change': function() {
                                                        var sub_jumlah = Ext.getCmp('rro_sub_jumlah').getValue();
                                                        var ppn_rp = parseFloat(this.getValue() * sub_jumlah) / 100;
                                                        var grand_total = sub_jumlah + ppn_rp;

                                                        Ext.getCmp('rro_ppn_rp').setValue(ppn_rp);
                                                        Ext.getCmp('rro_total').setValue(grand_total);

                                                    }
                                                }

                                            },
                                            {
                                                xtype: 'hidden',
                                                value: '%'
                                            },
                                            {
                                                xtype: 'hidden',
                                                name: 'ppn_rp',
                                                id: 'rro_ppn_rp',
                                                currencySymbol: '',
                                                fieldClass: 'readonly-input number',
                                                readOnly: true,
                                                anchor: '100%',
                                                value: '0'

                                            }
                                        ]
                                    }, {
                                        xtype: 'hidden',
                                        currencySymbol: '',
                                        fieldLabel: '<b>Grand Total</b>',
                                        name: 'total',
                                        cls: 'vertical-space',
                                        readOnly: true,
                                        id: 'rro_total',
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
                    if (Ext.getCmp('rro_jumlah').getValue() == 0) {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Tidak ada retur receive order!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK

                        });
                        return;
                    }
                    if(Ext.getCmp('erro_sub').getValue() ==''){
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
                    strreturreceiveorder.each(function(node) {
                        detailpembeliancreatepononrequest.push(node.data)
                    });

                    // var mkonsi=Ext.getCmp('id_pcret_is_konsinyasi').getValue();
                    // var konsi=null;
                    // if (mkonsi){
                    // konsi=1;
                    // }else{konsi=0;}

                    Ext.getCmp('returrecieveorder').getForm().submit({
                        url: '<?= site_url("pembelian_retur/update_row_by_RO") ?>',
                        scope: this,
                        params: {
                            detail: Ext.util.JSON.encode(detailpembeliancreatepononrequest),
                            //_dp: Ext.getCmp('pcret_dp').getValue(),
                            _jumlah: Ext.getCmp('rro_jumlah').getValue(),
                            _no_do: Ext.getCmp('id_cbreturnoro').getValue(),
                           // _no_po: Ext.getCmp('rro_no_po').getValue(),
//                            _diskon_rp: Ext.getCmp('rro_diskon_rp').getValue(),
//                            _ppn_persen: Ext.getCmp('rro_ppn_persen').getValue(),
//                            _ppn_rp: Ext.getCmp('rro_ppn_rp').getValue(),
//                            _total: Ext.getCmp('rro_total').getValue(),
                            _remark: Ext.getCmp('rro_remark').getValue()
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

                            clearreturreceiveorder();
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
                    clearreturreceiveorder();
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

    function clearreturreceiveorder() {
        Ext.getCmp('returrecieveorder').getForm().reset();
        Ext.getCmp('returrecieveorder').getForm().load({
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
        strreturreceiveorder.removeAll();
        strgrid_retRO_supplier.removeAll();
    }
</script>