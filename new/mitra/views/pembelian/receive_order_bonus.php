<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    //Combo Supplier
    var strcbprobsuplier = new Ext.data.ArrayStore({
        fields: ['kd_supplier'],
        data: []
    });

    var strgridprobsuplier = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_supplier', 'nama_supplier'],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_receive_order_bonus/search_supplier") ?>',
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

    var searchgridprobsuplier = new Ext.app.SearchField({
        store: strgridprobsuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridprobsuplier'
    });


    var gridprobsuplier = new Ext.grid.GridPanel({
        store: strgridprobsuplier,
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
            items: [searchgridprobsuplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridprobsuplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cbprobsuplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('prob_nama_supplier').setValue(sel[0].get('nama_supplier'));

                    menuprobsuplier.hide();
                }
            }
        }
    });

    var menuprobsuplier = new Ext.menu.Menu();
    menuprobsuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridprobsuplier],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menuprobsuplier.hide();
                }
            }]
    }));

    Ext.ux.TwinComboprobSuplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridprobsuplier.load();
            menuprobsuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menuprobsuplier.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridprobsuplier').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgridprobsuplier').setValue('');
            searchgridprobsuplier.onTrigger2Click();
        }
    });

    var cbprobsuplier = new Ext.ux.TwinComboprobSuplier({
        fieldLabel: 'Supplier <span class="asterix">*</span>',
        id: 'id_cbprobsuplier',
        store: strcbprobsuplier,
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


   //Combo PO Induk
    var strcbrobpoinduk = new Ext.data.ArrayStore({
        fields: ['no_po'],
        data: []
    });

    var strgridrobpoinduk = new Ext.data.Store({
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


    strgridrobpoinduk.on('load',function(){
        strgridrobpoinduk.setBaseParam('kd_supplier',Ext.getCmp('id_cbprobsuplier').getValue());
    });

    var searchgridrobpoinduk = new Ext.app.SearchField({
        store: strgridrobpoinduk,
        params: {
            kd_supplier: Ext.getCmp('id_cbprobsuplier').getValue(),
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridrobpoinduk'
    });


    var gridrobpoinduk = new Ext.grid.GridPanel({
        store: strgridrobpoinduk,
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
            items: [searchgridrobpoinduk]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridrobpoinduk,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cbrobpoinduk').setValue(sel[0].get('no_po'));
                    menurobpoinduk.hide();
                }
            }
        }
    });

    var menurobpoinduk = new Ext.menu.Menu();
    menurobpoinduk.add(new Ext.Panel({
        title: 'Pilih PO Induk',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridrobpoinduk],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menurobpoinduk.hide();
                }
            }]
    }));

    Ext.ux.TwinComborobpoinduk = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            var supp = Ext.getCmp('id_cbprobsuplier').getValue();
            strgridrobpoinduk.load({
                params: { kd_supplier: supp}
            });
        menurobpoinduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menurobpoinduk.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridrobpoinduk').getValue();
        if (sf !== '') {
            Ext.getCmp('id_searchgridrobpoinduk').setValue('');
            searchgridrobpoinduk.onTrigger2Click();
        }
    });

    var cbrobpoinduk = new Ext.ux.TwinComborobpoinduk({
        fieldLabel: 'PO Induk',
        id: 'id_cbrobpoinduk',
        store: strcbrobpoinduk,
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

    var headerpembelianreceiveorderbonus = {
        layout: 'column',
        border: false,
        items: [{
                columnWidth: .3,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [{
                        xtype: 'textfield',
                        fieldLabel: 'RO No.',
                        name: 'no_do',
                        readOnly: true,
                        fieldClass: 'readonly-input',
                        id: 'prob_no_do',
                        anchor: '90%',
                        value: ''
                    }, cbprobsuplier,cbrobpoinduk
                ]
            }, {
                columnWidth: .4,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [{
                        xtype: 'datefield',
                        fieldLabel: 'Tanggal Terima <span class="asterix">*</span>',
                        name: 'tanggal_terima',
                        allowBlank: false,
                        format: 'd-m-Y',
                        editable: false,
                        id: 'prob_tanggal_terima',
                        anchor: '90%',
                        value: '',
                        maxValue: (new Date()).clearTime()
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'Nama Supplier',
                        name: 'nama_supplier',
                        readOnly: true,
                        fieldClass: 'readonly-input',
                        id: 'prob_nama_supplier',
                        anchor: '90%',
                        value: ''
                    },{
                        xtype: 'textfield',
                        fieldLabel: 'Tanggal Input',
                        name: 'tanggal',
                        fieldClass:'readonly-input',
                        readOnly:true,
                        id: 'prob_tanggal',
                        anchor: '90%',
                        value: ''
                    }]
            }, {
                columnWidth: .3,
                layout: 'form',
                border: false,
                labelWidth: 120,
                defaults: {labelSeparator: ''},
                items: [ {
	                xtype: 'textfield',
	                fieldLabel: 'No. Bukti Supplier<span class="asterix">*</span>',
	                name: 'bukti_supplier',
                        allowBlank: false,
	                id: 'prob_bukti_supplier',
	                anchor: '90%'
                    },{
                        xtype: 'datefield',
                        fieldLabel: 'Tgl.Bukti Supplier  <span class="asterix">*</span>',
                        name: 'tanggal_bukti',				
                        allowBlank:false,   
                        format:'d-m-Y',  
                        editable:false,           
                        id: 'prob_tanggal_bukti',                
                        anchor: '90%',
                        maxValue: (new Date()).clearTime() 
                    },{
                        fieldLabel: 'Peruntukan <span class="asterix">*</span>',
                        xtype: 'radiogroup',
                        name: 'kd_peruntukan',
                        columnWidth: [.5, .5],
                        allowBlank:false,
                        items: [{
                                boxLabel: 'Supermarket',
                                name: 'kd_peruntukan',
                                inputValue: '0',
                                id: 'eprob_peruntukan_supermarket',
                                checked:true
                            }, {
                                boxLabel: 'Distribusi',
                                name: 'kd_peruntukan',
                                inputValue: '1',
                                id: 'eprob_peruntukan_distribusi'
                            }]
                    }]
            }]
    };

    var strcbkdsubblokprob = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['sub', 'nama_sub'],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_receive_order_bonus/get_sub_blok") ?>',
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

    var strgridsubblokprob = new Ext.data.Store({
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
            url: '<?= site_url("pembelian_receive_order_bonus/get_rows_lokasi") ?>',
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
    var searchgridprobsubblok = new Ext.app.SearchField({
        store: strgridsubblokprob,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchgridprobsubblok'
    });

    // top toolbar
    var tbgridprobsubblok = new Ext.Toolbar({
        items: [searchgridprobsubblok]
    });

    var gridprobsubblok = new Ext.grid.GridPanel({
        store: strgridsubblokprob,
        stripeRows: true,
        frame: true,
        border: true,
        tbar: tbgridprobsubblok,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridsubblokprob,
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
                    Ext.getCmp('eprob_sub').setValue(sel[0].get('sub'));
                    Ext.getCmp('eprob_nama_sub').setValue(sel[0].get('nama_sub'));

                    menusubblokreceiveorderbonus.hide();
                }
            }
        }
    });

    var menusubblokreceiveorderbonus = new Ext.menu.Menu();
    menusubblokreceiveorderbonus.add(new Ext.Panel({
        title: 'Pilih Sub Blok Lokasi',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 350,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridprobsubblok],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menusubblokreceiveorderbonus.hide();
                }
            }]
    }));

    Ext.ux.TwinComboprobSubBlok = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
//            strgridsubblokprob.load({
//                params:{
//                    kd_produk: Ext.getCmp('eprob_kd_produk').getValue()
//                }
//            });
            strgridsubblokprob.setBaseParam('kd_produk',Ext.getCmp('eprob_kd_produk').getValue());
            strgridsubblokprob.setBaseParam('kd_peruntukan_dist',Ext.getCmp('eprob_peruntukan_distribusi').getValue());
            strgridsubblokprob.setBaseParam('kd_peruntukan_supp',Ext.getCmp('eprob_peruntukan_supermarket').getValue());
            strgridsubblokprob.load();
            menusubblokreceiveorderbonus.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    var strpembelianreceiveorderbonus = new Ext.data.Store({
        autoSave: false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'no_po', allowBlank: false, type: 'text'},
                {name: 'kd_produk', allowBlank: false, type: 'text'},
                {name: 'nama_produk', allowBlank: false, type: 'text'},
                {name: 'nm_satuan', allowBlank: false, type: 'text'},
                {name: 'qty_po', allowBlank: false, type: 'int'},
                {name: 'qty_do', allowBlank: false, type: 'int'},
                {name: 'sub', allowBlank: false, type: 'text'},
                {name: 'nama_sub', allowBlank: false, type: 'text'}
            ],
            root: 'data',
            totalproperty: 'record'
        }),
        writer: new Ext.data.JsonWriter(
                {
                    encode: true,
                    writeAllFields: true
                })
    });

    var strcbprobnopo = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_po'],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_receive_order_bonus/get_all_po") ?>',
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

    var strcbprobproduk = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data: []
    });

    var strgridprobproduk = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_produk', 'nama_produk', 'qty_po', 'nm_satuan','qty_do','qty_terima'],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_receive_order_bonus/search_produk_by_no_po") ?>',
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
    var searchFieldROBonus = new Ext.app.SearchField({
        width: 220,
        id: 'search_query_rob',
        store: strgridprobproduk
    });

    searchFieldROBonus.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';

            // Get the value of search field
            var fid = Ext.getCmp('eprob_no_po').getValue();
            var o = {start: 0, no_po: fid};

            this.store.baseParams = this.store.baseParams || {};
            this.store.baseParams[this.paramName] = '';
            this.store.reload({
                params: o
            });
            this.triggers[0].hide();
            this.hasSearch = false;
        }
    };

    searchFieldROBonus.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }

        // Get the value of search field
        var fid = Ext.getCmp('eprob_no_po').getValue();
        var o = {start: 0, no_po: fid};

        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params: o});
        this.hasSearch = true;
        this.triggers[0].show();
    };

    // top toolbar
    var tbsearchbarang = new Ext.Toolbar({
        items: [searchFieldROBonus]
    });

    var gridprobproduk = new Ext.grid.GridPanel({
        store: strgridprobproduk,
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
                width: 400,
                sortable: true
            }, {
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 80
            }, {
                header: 'Qty PO',
                dataIndex: 'qty_po',
                width: 80,
                sortable: true
            },{
                header: 'Qty RO',
                dataIndex: 'qty_do',
                width: 80,
                sortable: true
         },{
                header: 'Qty',
                dataIndex: 'qty_terima',
                width: 80,
                sortable: true
            }],
        tbar: tbsearchbarang,
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('eprob_kd_produk').setValue(sel[0].get('kd_produk'));
                    Ext.getCmp('eprob_nama_produk').setValue(sel[0].get('nama_produk'));
                    Ext.getCmp('eprob_qty_po').setValue(sel[0].get('qty_po'));
                    Ext.getCmp('eprob_qty').setValue(sel[0].get('qty_do'));
                    Ext.getCmp('eprob_qty_terima').setValue(sel[0].get('qty_terima'));
                    Ext.getCmp('eprob_nm_satuan').setValue(sel[0].get('nm_satuan'));
                    Ext.getCmp('eprob_qty').setValue(0);
                    Ext.getCmp('eprob_qty').focus();
                    menuprobproduk.hide();
                }
            }
        }
    });

    var menuprobproduk = new Ext.menu.Menu();
    menuprobproduk.add(new Ext.Panel({
        title: 'Pilih Barang',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 600,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridprobproduk],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menuprobproduk.hide();
                }
            }]
    }));

    Ext.ux.TwinComboprobproduk = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            if (Ext.getCmp('eprob_no_po').getValue() === '') {
                Ext.Msg.show({
                    title: 'Error',
                    msg: 'Silahkan pilih No PO terlebih dulu',
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK
                });
                return;
            }
            //load store grid
            strgridprobproduk.load({
                params: {
                    no_po: Ext.getCmp('eprob_no_po').getValue()
                }
            });
            menuprobproduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    var strcbprobnopo = new Ext.data.ArrayStore({
        fields: ['no_po'],
        data: []
    });

    var strgridprobnopo = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_po','tanggal_po'],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_receive_order_bonus/get_all_po") ?>',
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

    var gridprobnopo = new Ext.grid.GridPanel({
        store: strgridprobnopo,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'No PO',
                dataIndex: 'no_po',
                width: 150,
                sortable: true
            },{
                header: 'Tanggal PO',
                dataIndex: 'tanggal_po',
                width: 150,
                sortable: true
            }],
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('eprob_no_po').setValue(sel[0].get('no_po'));
                    Ext.getCmp('eprob_tgl_po').setValue(sel[0].get('tanggal_po'));
                    menuprobnopo.hide();
                }
            }
        }
    });

    var menuprobnopo = new Ext.menu.Menu();
    menuprobnopo.add(new Ext.Panel({
        title: 'Pilih No PO',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 300,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridprobnopo],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menuprobnopo.hide();
                }
            }]
    }));

    Ext.ux.TwinComboprobNoPO = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridprobnopo.load({
                params: {
                    kd_supplier: Ext.getCmp('id_cbprobsuplier').getValue(),
                    no_po_induk: Ext.getCmp('id_cbrobpoinduk').getValue()
                }
            });
            menuprobnopo.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    var editorpembelianreceiveorderbonus = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });

    var gridpembelianreceiveorderbonus = new Ext.grid.GridPanel({
        store: strpembelianreceiveorderbonus,
        stripeRows: true,
        height: 300,
        frame: true,
        border: true,
        plugins: [editorpembelianreceiveorderbonus],
        tbar: [{
                icon: BASE_ICONS + 'add.png',
                text: 'Add',
                handler: function() {
                    if (Ext.getCmp('id_cbprobsuplier').getValue() === '') {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Silahkan pilih supplier terlebih dulu',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                        });
                        return;
                    }
                    var rowpembelianreceiveorderbonus = new gridpembelianreceiveorderbonus.store.recordType({
                        no_po: '',
                        kd_produk: '',
                        qty: ''
                    });
                    editorpembelianreceiveorderbonus.stopEditing();
                    strpembelianreceiveorderbonus.insert(0, rowpembelianreceiveorderbonus);
                    gridpembelianreceiveorderbonus.getView().refresh();
                    gridpembelianreceiveorderbonus.getSelectionModel().selectRow(0);
                    editorpembelianreceiveorderbonus.startEditing(0);
                }
            }, {
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                disabled: true,
                handler: function() {
                    editorpembelianreceiveorderbonus.stopEditing();
                    var s = gridpembelianreceiveorderbonus.getSelectionModel().getSelections();
                    for (var i = 0, r; r = s[i]; i++) {
                        strpembelianreceiveorderbonus.remove(r);
                    }
                }
            }],
        columns: [{
                header: 'No PO',
                dataIndex: 'no_po',
                width: 140,
                editor: new Ext.ux.TwinComboprobNoPO({
                    id: 'eprob_no_po',
                    store: strcbprobnopo,
                    mode: 'local',
                    valueField: 'no_po',
                    displayField: 'no_po',
                    typeAhead: true,
                    triggerAction: 'all',
                    //allowBlank: false,
                    editable: false,
                    hiddenName: 'no_po',
                    emptyText: 'Pilih No PO'

                })
            },{
                header: 'Tanggal PO',
                dataIndex: 'tanggal_po',
                width: 120,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'eprob_tgl_po'
                })
            }, {
                header: 'Kode',
                dataIndex: 'kd_produk',
                width: 110,
                editor: new Ext.ux.TwinComboprobproduk({
                    id: 'eprob_kd_produk',
                    store: strcbprobproduk,
                    mode: 'local',
                    valueField: 'kd_produk',
                    displayField: 'kd_produk',
                    typeAhead: true,
                    triggerAction: 'all',
                    //allowBlank: false,
                    editable: false,
                    hiddenName: 'kd_produk',
                    emptyText: 'Pilih produk'

                })

            }, {
                header: 'Nama Barang',
                dataIndex: 'nama_produk',
                width: 300,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'eprob_nama_produk'
                })
            }, {
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 80,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'eprob_nm_satuan'
                })
            }, {
                header: 'Qty PO',
                dataIndex: 'qty_po',
                width: 50,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'eprob_qty_po'
                })
            },{
            header: 'Qty RO',
            dataIndex: 'qty_terima',
	    width: 50,
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'eprob_qty_terima'
            })
            },{
                xtype: 'numbercolumn',
                header: 'Qty',
                dataIndex: 'qty_do',
                width: 50,
                align: 'center',
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'eprob_qty',
                    //allowBlank: false,
                    selectOnFocus: true,
                     listeners: {
                        'render': function(c) {
                            c.getEl().on('keyup', function() {

				var max = parseFloat (Ext.getCmp('eprob_qty_po').getValue());
                                var jml = parseFloat(Ext.getCmp('eprob_qty_terima').getValue());
                                var qty = this.getValue();
                                var validasi = qty + jml;
                                console.log(validasi);
                                console.log(max);
                                if(validasi > max){
                                    Ext.Msg.show({
                                        title: 'Error',
                                        msg: 'Qty RO + Qty tidak boleh lebih besar dari Qty PO',
                                        modal: true,
                                        icon: Ext.Msg.ERROR,
                                        buttons: Ext.Msg.OK,
                                        fn: function(btn){
                                            if (btn === 'ok') {

                                                Ext.getCmp('eprob_qty').reset();
                                            }
                                        }
                                    });
                                    Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                                    return;
                                }
                            }, c);
                        }
                    }
                }
            }, {
                header: 'Kode Sub Blok',
                dataIndex: 'sub',
                width: 100,
                editor: new Ext.ux.TwinComboprobSubBlok({
                    id: 'eprob_sub',
                    store: strcbkdsubblokprob,
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
                            strcbkdsubblokprob.load();
                        }
                    }
                })
            }, {
                header: 'Sub Blok',
                dataIndex: 'nama_sub',
                width: 200,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'eprob_nama_sub'
                })
            }]
    });

    gridpembelianreceiveorderbonus.getSelectionModel().on('selectionchange', function(sm) {
        gridpembelianreceiveorderbonus.removeBtn.setDisabled(sm.getCount() < 1);
    });

    var pembelianreceiveorderbonus = new Ext.FormPanel({
        id: 'pembelianreceiveorderbonus',
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
                items: [headerpembelianreceiveorderbonus]
            },
            gridpembelianreceiveorderbonus
        ],
        buttons: [{
                text: 'Save',
                formBind: true,
                handler: function() {

                    var detailpembelianreceiveorderbonus = new Array();
                    strpembelianreceiveorderbonus.each(function(node) {
                        detailpembelianreceiveorderbonus.push(node.data)
                    });
                    Ext.getCmp('pembelianreceiveorderbonus').getForm().submit({
                        url: '<?= site_url("pembelian_receive_order_bonus/update_row") ?>',
                        scope: this,
                        params: {
                            detail: Ext.util.JSON.encode(detailpembelianreceiveorderbonus)
                        },
                        waitMsg: 'Saving Data...',
                        success: function(form, action) {
                            var r = Ext.util.JSON.decode(action.response.responseText);
                            Ext.Msg.show({
                                title: 'Success',
                                msg: r.errMsg,
                                modal: true,
                                icon: Ext.Msg.INFO,
                                buttons: Ext.Msg.OK
                            });

                            clearpembelianreceiveorderbonus();
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
                    clearpembelianreceiveorderbonus();
                }
            }]
    });

    pembelianreceiveorderbonus.on('afterrender', function() {
        this.getForm().load({
            url: '<?= site_url("pembelian_receive_order_bonus/get_form") ?>',
            success: function(form, action){
                var r = Ext.util.JSON.decode(action.response.responseText);
                if(r.data.user_peruntukan === "0"){
                    Ext.getCmp('eprob_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('eprob_peruntukan_supermarket').show();
                    Ext.getCmp('eprob_peruntukan_distribusi').hide();
                }else if(r.data.user_peruntukan === "1"){
                    Ext.getCmp('eprob_peruntukan_distribusi').setValue(true);
                    Ext.getCmp('eprob_peruntukan_supermarket').hide();
                    Ext.getCmp('eprob_peruntukan_distribusi').show();
                }else{
                    Ext.getCmp('eprob_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('eprob_peruntukan_supermarket').show();
                    Ext.getCmp('eprob_peruntukan_distribusi').show();
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
                        if (btn === 'ok' && de.errMsg === 'Session Expired') {
                            window.location = '<?= site_url("auth/login") ?>';
                        }
                    }
                });
            }
        });
    });

    function clearpembelianreceiveorderbonus() {
        Ext.getCmp('pembelianreceiveorderbonus').getForm().reset();
        Ext.getCmp('pembelianreceiveorderbonus').getForm().load({
            url: '<?= site_url("pembelian_receive_order_bonus/get_form") ?>',
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
        strpembelianreceiveorderbonus.removeAll();
    }
</script>
