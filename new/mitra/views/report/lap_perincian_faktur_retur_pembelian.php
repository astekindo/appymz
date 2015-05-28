<?php if (!defined( 'BASEPATH')) exit( 'No direct script access allowed'); ?>
<script type="text/javascript">
	
	//COMBOBOX RO (belum ada isinya)
	var cblpfrpro = new Ext.ux.TwinComboSuplier({
        fieldLabel: 'Request Order',
        id: 'id_cblpfrpro',
        store: '',
        mode: 'local',
        valueField: 'kd_ro',
        displayField: 'kd_ro',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_ro',
        emptyText: 'Pilih Request Order'
    });
	
	//COMBOBOX Faktur (belum ada isinya)
	var cblpfrpfaktur = new Ext.ux.TwinComboSuplier({
        fieldLabel: 'Nomor Faktur',
        id: 'id_cblpfrfaktur',
        store: '',
        mode: 'local',
        valueField: 'kd_faktur',
        displayField: 'kd_faktur',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_faktur',
        emptyText: 'Pilih Nomor Faktur'
    });
	
	//COMBOBOX PO (belum ada isinya)
	var cblpfrppo = new Ext.ux.TwinComboSuplier({
        fieldLabel: 'Purchase Order',
        id: 'id_cblpfrppo',
        store: '',
        mode: 'local',
        valueField: 'kd_po',
        displayField: 'kd_po',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_po',
        emptyText: 'Pilih Purchase Order'
    });
	

    //-------- COMBOBOX SUPPLIER ---------------------
    //
    var strcblpfrpsuplier = new Ext.data.ArrayStore({
        fields: ['kd_supplier'],
        data: []
    });

    // GRID PANEL TWIN COMBOBOX supplier Data Store
    var strgridlpfrpsuplier = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_supplier', 'nama_supplier'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("laporan_purchase_order/search_supplier") ?>',
            method: 'POST'
        }),
        listeners: {

            loadexception: function (event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    // SEARCH GRID PANEL TWIN COMBOBOX supplier
    var searchgridlpfrpsuplier = new Ext.app.SearchField({
        store: strgridlpfrpsuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridlpfrpsuplier'
    });

    // GRID PANEL TWIN COMBOBOX supplier
    var gridlpfrpsuplier = new Ext.grid.GridPanel({
        store: strgridlpfrpsuplier,
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
        }],
        tbar: new Ext.Toolbar({
            items: [searchgridlpfrpsuplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridlpfrpsuplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function () {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    //Ext.getCmp('lpo_kd_supplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_cblpfrpsuplier').setValue(sel[0].get('kd_supplier'));
                    // strlaporanpenerimaanbarang.removeAll();       
                    menulpfrpsuplier.hide();
                }
            }
        }
    });

    // PANEL TWIN COMBOBOX supplier
    var menulpfrpsuplier = new Ext.menu.Menu();
    menulpfrpsuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridlpfrpsuplier],
        buttons: [{
            text: 'Close',
            handler: function () {
                menulpfrpsuplier.hide();
            }
        }]
    }));

    // PANEL TWIN COMBOBOX supplier
    Ext.ux.TwinComboSuplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function () {
            //load store grid
            strgridlpfrpsuplier.load();
            menulpfrpsuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    //
    menulpfrpsuplier.on('hide', function () {
        var sf = Ext.getCmp('id_searchgridlpfrpsuplier').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgridlpfrpsuplier').setValue('');
            searchgridlpfrpsuplier.onTrigger2Click();
        }
    });

    // TWIN COMBOBOX supplier
    var cblpfrpsuplier = new Ext.ux.TwinComboSuplier({
        fieldLabel: 'Supplier',
        id: 'id_cblpfrpsuplier',
        store: strcblpfrpsuplier,
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
    //-------- COMBOBOX SUPPLIER ---------------------

	
	//-------- COMBOBOX PRODUK -----------------------
    //
    var strcbloutpoproduk = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data: []
    });

    // GRID PANEL TWIN COMBOBOX produk Data Store
    var strgridlpfrpproduk = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_produk', 'nama_produk'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("laporan_purchase_order/search_produk") ?>',
            method: 'POST'
        }),
        listeners: {

            loadexception: function (event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    // SEARCH GRID PANEL TWIN COMBOBOX produk
    var searchgridlpfrpproduk = new Ext.app.SearchField({
        store: strgridlpfrpproduk,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridlpfrpproduk'
    });

    // GRID PANEL TWIN COMBOBOX produk
    var gridlpfrpproduk = new Ext.grid.GridPanel({
        store: strgridlpfrpproduk,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
            header: 'Kode Produk',
            dataIndex: 'kd_produk',
            width: 80,
            sortable: true

        }, {
            header: 'Nama Produk',
            dataIndex: 'nama_produk',
            width: 300,
            sortable: true
        }],
        tbar: new Ext.Toolbar({
            items: [searchgridlpfrpproduk]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridlpfrpproduk,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function () {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    // Ext.getCmp('lpo_kd_supplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_cblrpbps_kd_produk').setValue(sel[0].get('kd_produk'));
                    // strlaporanpenerimaanbarang.removeAll();       
                    menulpfrpproduk.hide();
                }
            }
        }
    });

    // PANEL TWIN COMBOBOX produk
    Ext.ux.TwinCombolrpbpsProduk = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function () {
            //load store grid
            strgridlpfrpproduk.load();
            menulpfrpproduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    // PANEL TWIN COMBOBOX produk
    var menulpfrpproduk = new Ext.menu.Menu();
    menulpfrpproduk.add(new Ext.Panel({
        title: 'Pilih Produk',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridlpfrpproduk],
        buttons: [{
            text: 'Close',
            handler: function () {
                menulpfrpproduk.hide();
            }
        }]
    }));

    //
    menulpfrpproduk.on('hide', function () {
        var sf = Ext.getCmp('id_searchgridlpfrpproduk').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgridlpfrpproduk').setValue('');
            searchgridlpfrpproduk.onTrigger2Click();
        }
    });

    // TWIN COMBOBOX produk
    var cblpfrpproduk = new Ext.ux.TwinCombolrpbpsProduk({
        id: 'id_cblrpbps_kd_produk',
        fieldLabel: 'Produk',
        store: strcbloutpoproduk,
        mode: 'local',
        anchor: '90%',
        valueField: 'kd_produk',
        displayField: 'kd_produk',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: true,
        editable: false,
        hiddenName: 'kd_produk',
        emptyText: 'Pilih Produk'
    });
    //-------- COMBOBOX PRODUK -----------------------

    // CHECKBOX Sort Order
    var lpfrpsortorder = new Ext.form.Checkbox({

        xtype: 'checkbox',
        fieldLabel: 'Sort Order Tanggal',
        boxLabel: 'Descending',
        name: 'sort_order',
        id: 'id_lpfrpsortorder',
        checked: true,
        inputValue: '1',
        autoLoad: true
    });

    // HEADER jatuhtempo
    var headerjatuhtempo = {
        layout: 'column',
        border: false,
        items: [{
            columnWidth: .8,
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: {
                labelSeparator: ''
            },
            items: [{
                xtype: 'fieldset',
				title: 'Tanggal Jatuh Tempo',
                autoHeight: true,
                items: [{
                    layout: 'column',
                    items: [{
                        columnWidth: .5,
                        layout: 'form',
                        border: false,
                        labelWidth: 100,
                        defaults: {
                            labelSeparator: ''
                        },
                        items: [
							{
                                xtype: 'datefield',
                                fieldLabel: 'Dari Tgl</span>',
                                name: 'lpfrp_dari_tgl_jthtempo',
                                allowBlank: true,
                                format: 'd-m-Y',
                                editable: false,
                                id: 'id_lpfrp_dari_tgl_jthtempo',
                                anchor: '90%',
                                value: ''
                            }							
							
                        ]
                    },
					{


                        columnWidth: .5,
                        layout: 'form',
                        border: false,
                        labelWidth: 100,
                        defaults: {
                            labelSeparator: ''
                        },
                        items: [
                            {
                                xtype: 'datefield',
                                fieldLabel: 'Sampai Tgl</span>',
                                name: 'lpfrp_smp_tgl_jthtempo',
                                allowBlank: true,
                                format: 'd-m-Y',
                                editable: false,
                                id: 'id_lpfrp_smp_tgl_jthtempo',
                                anchor: '90%',
                                value: ''
                            }
                        ]
					}]
                }]
            }]
        }]
    }

    // HEADER tanggal
    var headertanggal = {
        layout: 'column',
        border: false,
        items: [{
            columnWidth: .8,
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: {
                labelSeparator: ''
            },
            items: [{
                xtype: 'fieldset',
                title: '',
                autoHeight: true,
                items: [{
                    layout: 'column',
                    items: [{
                            columnWidth: .5,
                            layout: 'form',
                            border: false,
                            labelWidth: 100,
                            defaults: {
                                labelSeparator: ''
                            },
                            items: [{
                                xtype: 'datefield',
                                fieldLabel: 'Dari Tgl</span>',
                                name: 'lpfrp_dari_tgl',
                                allowBlank: true,
                                format: 'd-m-Y',
                                editable: false,
                                id: 'id_lpfrp_dari_tgl',
                                anchor: '90%',
                                value: ''
                            },
							{
                                xtype: 'datefield',
                                fieldLabel: 'Sampai Tgl',
                                name: 'lpfrp_sampai_tgl',
                                // readOnly: true,				
                                allowBlank: true,
                                editable: false,
                                format: 'd-m-Y',
                                id: 'id_lpfrp_smp_tgl',
                                anchor: '90%',
                                // fieldClass:'readonly-input',
                                value: ''
                            }
							
							]
                        }, {
                            columnWidth: .5,
                            layout: 'form',
                            border: false,
                            labelWidth: 100,
                            defaults: {
                                labelSeparator: ''
                            },
                            items: [
								lpfrpsortorder
							]
                        },

                    ]
                }]
            }]
        }]
    }
   
	 // HEADER produk
    var headerproduk = {
        layout: 'column',
        border: false,
        items: [{
            columnWidth: .8,
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: {
                labelSeparator: ''
            },
            items: [{
                xtype: 'fieldset',
                title: '',
                autoHeight: true,
                items: [{
                    layout: 'column',
                    items: [{
                            columnWidth: .5,
                            layout: 'form',
                            border: false,
                            labelWidth: 100,
                            defaults: {
                                labelSeparator: ''
                            },
                            items: [
							cblpfrpproduk,
							cblpfrpsuplier,
							cblpfrpfaktur,
							cblpfrppo,
							cblpfrpro
							
							]
                        }, {
                            columnWidth: .5,
                            layout: 'form',
                            border: false,
                            labelWidth: 100,
                            defaults: {
                                labelSeparator: ''
                            },
                            items: [
								
							]
                        },

                    ]
                }]
            }]
        }]
    }
   
	
    // HEADER
    var headerlaporanperincianfakturreturpembelian = {
        buttonAlign: 'left',
        layout: 'form',
        border: false,
        labelWidth: 100,
        defaults: {
            labelSeparator: ''
        },
        items: [headertanggal, headerproduk, headerjatuhtempo, {}],
        buttons: [{
            text: 'Print',
            formBind: true,
            handler: function () {
                winlaporanperincianfakturreturpembelianprint.show();
                Ext.getDom('laporanperincianfakturreturpembelianprint').src = '<?= site_url("laporan_purchase_order/print_form") ?>';
            }
        }, {
            text: 'Cancel',
            handler: function () {
                clearlaporanperincianfakturreturpembelian();
            }
        }]
    };

    // PRINT
    var winlaporanperincianfakturreturpembelianprint = new Ext.Window({
        id: 'id_winlaporanperincianfakturreturpembelianprint',
        Title: 'Print Laporan Perincian Faktur & Retur Pembelian',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html: '<iframe style="width:100%;height:390px;" id="laporanperincianfakturreturpembelianprint" src=""></iframe>'
    });

    //  FORM PANEL
    var laporanperincianfakturreturpembelian = new Ext.FormPanel({
        id: 'rpt_perincian_faktur_retur_pembelian',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
            bodyStyle: {
                margin: '0px 0px 15px 0px'
            },
            items: [headerlaporanperincianfakturreturpembelian]
        }]
    });

    // CLEAR DATA FORM PANEL
    function clearlaporanperincianfakturreturpembelian() {
        Ext.getCmp('rpt_perincian_faktur_retur_pembelian').getForm().reset();
        // strlaporanpenerimaanbarang.removeAll();
    }
</script>