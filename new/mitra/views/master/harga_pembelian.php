<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    /* START HISTORY */
    var strhargapembelianhistory = new Ext.data.Store({
        autoSave: false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'tanggal', allowBlank: false, type: 'text'},
                {name: 'tgl_approve', allowBlank: false, type: 'text'},
                {name: 'kd_produk', allowBlank: false, type: 'text'},
                {name: 'nama_produk', allowBlank: false, type: 'text'},
                {name: 'nm_satuan', allowBlank: false, type: 'text'},
                {name: 'nama_suppllier', allowBlank: false, type: 'text'},
                {name: 'rp_het_harga_beli', allowBlank: false, type: 'int'},
                {name: 'pct_margin', allowBlank: false, type: 'int'},
                {name: 'rp_ongkos_kirim', allowBlank: false, type: 'int'},
                {name: 'hrg_supplier', allowBlank: false, type: 'int'},
                {name: 'disk_supp1_op', allowBlank: false, type: 'text'},
                {name: 'disk_supp2_op', allowBlank: false, type: 'text'},
                {name: 'disk_supp3_op', allowBlank: false, type: 'text'},
                {name: 'disk_supp4_op', allowBlank: false, type: 'text'},
                {name: 'disk_dist1_op', allowBlank: false, type: 'text'},
                {name: 'disk_dist2_op', allowBlank: false, type: 'text'},
                {name: 'disk_dist3_op', allowBlank: false, type: 'text'},
                {name: 'disk_dist4_op', allowBlank: false, type: 'text'},
                {name: 'disk_supp1', allowBlank: false, type: 'float'},
                {name: 'disk_supp2', allowBlank: false, type: 'float'},
                {name: 'disk_supp3', allowBlank: false, type: 'float'},
                {name: 'disk_supp4', allowBlank: false, type: 'float'},
                {name: 'disk_amt_supp5', allowBlank: false, type: 'int'},
                {name: 'disk_dist1', allowBlank: false, type: 'float'},
                {name: 'disk_dist2', allowBlank: false, type: 'float'},
                {name: 'disk_dist3', allowBlank: false, type: 'float'},
                {name: 'disk_dist4', allowBlank: false, type: 'float'},
                {name: 'disk_amt_dist5', allowBlank: false, type: 'int'},
                {name: 'net_hrg_supplier_dist_inc', allowBlank: false, type: 'int'},
                {name: 'net_hrg_supplier_sup_inc', allowBlank: false, type: 'int'},
                {name: 'net_hrg_supplier_dist', allowBlank: false, type: 'int'},
                {name: 'net_hrg_supplier_sup', allowBlank: false, type: 'int'},
                {name: 'waktu_top', allowBlank: false, type: 'text'},
                {name: 'keterangan', allowBlank: false, type: 'text'},
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("harga_pembelian/search_produk_history") ?>',
            method: 'POST'
        }),
        writer: new Ext.data.JsonWriter(
                {
                    encode: true,
                    writeAllFields: true
                })
    });

    var gridhargapembelianhistory = new Ext.grid.GridPanel({
        store: strhargapembelianhistory,
        stripeRows: true,
        height: 400,
        frame: true,
        border: true,
        columns: [{
                header: 'Tanggal',
                dataIndex: 'tanggal',
                width: 100,
                sortable: true
            }, {
                header: 'Tanggal Approval',
                dataIndex: 'tgl_approve',
                width: 100,
                sortable: true
            }, {
                header: 'Kode Barang',
                dataIndex: 'kd_produk',
                width: 100,
                sortable: true
            }, {
                header: 'Nama Barang',
                dataIndex: 'nama_produk',
                width: 300,
                sortable: true
            }, {
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 80
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Rp Margin',
                dataIndex: 'pct_margin',
                width: 80
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Rp Ongkos Kirim',
                dataIndex: 'rp_ongkos_kirim',
                width: 80
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Harga Supplier',
                dataIndex: 'hrg_supplier',
                width: 100
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'HET BELI',
                dataIndex: 'rp_het_harga_beli',
                width: 80
            }, {
                header: '% / Rp',
                dataIndex: 'disk_supp1_op',
                width: 50
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                // format: '0,0',
                header: 'Diskon Supermarket 1',
                dataIndex: 'disk_supp1',
                width: 150
            }, {
                header: '% / Rp',
                dataIndex: 'disk_supp2_op',
                width: 50
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                // format: '0,0',
                header: 'Diskon Supermarket 2',
                dataIndex: 'disk_supp2',
                width: 150
            }, {
                header: '% / Rp',
                dataIndex: 'disk_supp3_op',
                width: 50
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                // format: '0,0',
                header: 'Diskon Supermarket 3',
                dataIndex: 'disk_supp3',
                width: 150
            }, {
                header: '% / Rp',
                dataIndex: 'disk_supp4_op',
                width: 50
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                // format: '0,0',
                header: 'Diskon Supermarket 4',
                dataIndex: 'disk_supp4',
                width: 150
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Diskon Supermarket 5',
                dataIndex: 'disk_amt_supp5',
                width: 150
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Net Price Supermarket(Inc.PPN)',
                dataIndex: 'net_hrg_supplier_sup_inc',
                width: 190
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Net Price Supermarket(Exc.PPN)',
                dataIndex: 'net_hrg_supplier_sup',
                width: 190
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'TOP',
                dataIndex: 'waktu_top',
                width: 60
            }, {
                header: 'Ket. Perubahan',
                dataIndex: 'keterangan',
                width: 300
            }]
    });

    var winhargapembelianprint = new Ext.Window({
        id: 'id_winhargapembelianprint',
        title: 'Print History Harga Pembelian',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html: '<iframe style="width:100%;height:100%;" id="hargapembelianprint" src=""></iframe>'
    });

    Ext.ns('hargapembelianform');
    hargapembelianform.Form = Ext.extend(Ext.form.FormPanel, {
        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 130,
        url: '<?= site_url("harga_pembelian/update_row") ?>',
        constructor: function(config) {
            config = config || {};
            config.listeners = config.listeners || {};
            Ext.applyIf(config.listeners, {
                actioncomplete: function() {
                    //if (console && console.log) {
                    //    console.log('actioncomplete:', arguments);
                    //}
                },
                actionfailed: function() {
                    //if (console && console.log) {
                    //    console.log('actionfailed:', arguments);
                    //}
                }
            });
            hargapembelianform.Form.superclass.constructor.call(this, config);
        },
        initComponent: function() {

            // hard coded - cannot be changed from outsid
            var config = {
                layout: 'form',
                items: [gridhargapembelianhistory],
                buttons: [{
                        text: 'Cetak',
                        id: 'btnCetakhargapembelian',
                        scope: this,
                        handler: function() {
                            function isEmpty(str) {
                                return (!str || 0 === str.length);
                            }
                            var no_bukti = Ext.getCmp('id_cbhpnobukti').getValue();
                            var kd_produk = Ext.getCmp('id_cbhpproduk').getValue();

                            if (isEmpty(no_bukti)) {
                                no_bukti = 0;
                            }
                            winhargapembelianprint.show();
                            Ext.getDom('hargapembelianprint').src = '<?= site_url("harga_pembelian/print_form") ?>' + '/' + no_bukti + '/' + kd_produk;
                        }
                    }, {
                        text: 'Close',
                        id: 'btnClosehargapembelian',
                        scope: this,
                        handler: function() {
                            winshowhistoryhargapembelian.hide();
                        }
                    }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));

            // call parent
            hargapembelianform.Form.superclass.initComponent.apply(this, arguments);

        } // eo function initComponent  
        ,
        onRender: function() {

            // call parent
            hargapembelianform.Form.superclass.onRender.apply(this, arguments);

            // set wait message target
            this.getForm().waitMsgTarget = this.getEl();

            // loads form after initial layout
            // this.on('afterlayout', this.onLoadClick, this, {single:true});

        } // eo function onRender
        ,
        showError: function(msg, title) {
            title = title || 'Error';
            Ext.Msg.show({
                title: title,
                msg: msg,
                modal: true,
                icon: Ext.Msg.ERROR,
                buttons: Ext.Msg.OK,
                fn: function(btn) {
                    if (btn == 'ok' && msg == 'Session Expired') {
                        window.location = '<?= site_url("auth/login") ?>';
                    }
                }
            });
        }
    }); // eo extend
    // register xtype
    Ext.reg('formaddhargapembelian', hargapembelianform.Form);

    var winshowhistoryhargapembelian = new Ext.Window({
        id: 'id_winshowhistoryhargapembelian',
        closeAction: 'hide',
        width: 1000,
        height: 500,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_formaddhargapembelian',
            xtype: 'formaddhargapembelian'
        },
        onHide: function() {
            Ext.getCmp('id_formaddhargapembelian').getForm().reset();
        }
    });

    var strcbhpnobukti = new Ext.data.ArrayStore({
        fields: ['no_bukti', 'keterangan'],
        data: []
    });

    var strgridhpnobukti = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_bukti', 'keterangan', 'created_by', 'nama_supplier'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("harga_pembelian/search_no_bukti") ?>',
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

    var searchgridhpnobukti = new Ext.app.SearchField({
        store: strgridhpnobukti,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridhpnobukti'
    });


    var gridhpnobukti = new Ext.grid.GridPanel({
        store: strgridhpnobukti,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'No Bukti',
                dataIndex: 'no_bukti',
                width: 100,
                sortable: true,
            }, {
                header: 'Nama Supplier',
                dataIndex: 'nama_supplier',
                width: 125,
                sortable: true,
            }, {
                header: 'Request By',
                dataIndex: 'created_by',
                width: 100,
                sortable: true,
            }, {
                header: 'Ket. Perubahan',
                dataIndex: 'keterangan',
                width: 200,
                sortable: true,
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridhpnobukti]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridhpnobukti,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cbhpnobukti').setValue(sel[0].get('no_bukti'));

                    menuhpnobukti.hide();
                }
            }
        }
    });

    var menuhpnobukti = new Ext.menu.Menu();
    menuhpnobukti.add(new Ext.Panel({
        title: 'Pilih No Bukti',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 500,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridhpnobukti],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menuhpnobukti.hide();
                }
            }]
    }));

    Ext.ux.TwinCombohpnobukti = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridhpnobukti.load();
            menuhpnobukti.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menuhpnobukti.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridhpnobukti').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgridhpnobukti').setValue('');
            searchgridhpnobukti.onTrigger2Click();
        }
    });

    var cbhpnobukti = new Ext.ux.TwinCombohpnobukti({
        fieldLabel: 'No Bukti <span class="asterix">*</span>',
        id: 'id_cbhpnobukti',
        store: strcbhpnobukti,
        mode: 'local',
        valueField: 'no_bukti',
        displayField: 'no_bukti',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'no_bukti',
        emptyText: 'Pilih No Bukti'
    });

    /* END HISTORY */

    /*START TWIN NO BUKTI FILTER*/

    var strcbhpnobuktifilter = new Ext.data.ArrayStore({
        fields: ['no_bukti', 'keterangan'],
        data: []
    });

    var strgridhpnobuktifilter = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_bukti_filter', 'keterangan', 'created_by', 'nama_supplier'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("harga_pembelian/get_no_bukti_filter") ?>',
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

    var searchgridhpnobuktifilter = new Ext.app.SearchField({
        store: strgridhpnobuktifilter,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridhpnobuktifilter'
    });


    var gridhpnobuktifilter = new Ext.grid.GridPanel({
        store: strgridhpnobuktifilter,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'No Bukti',
                dataIndex: 'no_bukti_filter',
                width: 100,
                sortable: true,
            }, {
                header: 'Nama Supplier',
                dataIndex: 'nama_supplier',
                width: 125,
                sortable: true,
            }, {
                header: 'Request By',
                dataIndex: 'created_by',
                width: 100,
                sortable: true,
            }, {
                header: 'Ket. Perubahan',
                dataIndex: 'keterangan',
                width: 200,
                sortable: true,
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridhpnobuktifilter]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridhpnobuktifilter,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cbhpnobuktifilter').setValue(sel[0].get('no_bukti_filter'));

                    menuhpnobuktifilter.hide();
                }
            }
        }
    });

    var menuhpnobuktifilter = new Ext.menu.Menu();
    menuhpnobuktifilter.add(new Ext.Panel({
        title: 'Pilih No Bukti',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 500,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridhpnobuktifilter],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menuhpnobuktifilter.hide();
                }
            }]
    }));

    Ext.ux.TwinCombohpnobuktifilter = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridhpnobuktifilter.load();
            menuhpnobuktifilter.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menuhpnobuktifilter.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridhpnobuktifilter').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgridhpnobuktifilter').setValue('');
            searchgridhpnobuktifilter.onTrigger2Click();
        }
    });

    var cbhpnobuktifilter = new Ext.ux.TwinCombohpnobuktifilter({
        fieldLabel: 'No Bukti Filter',
        id: 'id_cbhpnobuktifilter',
        store: strcbhpnobuktifilter,
        mode: 'local',
        valueField: 'no_bukti_filter',
        displayField: 'no_bukti_filter',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'no_bukti_filter',
        emptyText: 'Pilih No Bukti'
    });

    /*END TWIN NO BUKTI FILTER*/

    /* START twin produk*/

    var strcbhpproduk = new Ext.data.ArrayStore({
        fields: ['kd_produk', 'nama_produk'],
        data: []
    });

    var strgridhpproduk = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_produk', 'nama_produk'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("harga_pembelian/search_produk_by_supplier") ?>',
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

    var searchgridhpproduk = new Ext.app.SearchField({
        store: strgridhpproduk,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridhpproduk'
    });
    searchgridhpproduk.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';
			
            // Get the value of search field
            var fid = Ext.getCmp('id_cbhpsuplier').getValue();
            var o = { start: 0, kd_supplier: fid };
			
            this.store.baseParams = this.store.baseParams || {};
            this.store.baseParams[this.paramName] = '';
            this.store.reload({
                params : o
            });
            this.triggers[0].hide();
            this.hasSearch = false;
        }
    };
	
    searchgridhpproduk.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }
	 
        // Get the value of search field
        var fid = Ext.getCmp('id_cbhpsuplier').getValue();
        var o = { start: 0, kd_supplier: fid };
	 
        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params:o});
        this.hasSearch = true;
        this.triggers[0].show();
    };

    var gridhpproduk = new Ext.grid.GridPanel({
        store: strgridhpproduk,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'Kode Produk',
                dataIndex: 'kd_produk',
                width: 150,
                sortable: true,
            }, {
                header: 'Nama Produk',
                dataIndex: 'nama_produk',
                width: 150,
                sortable: true,
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridhpproduk]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridhpproduk,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cbhpproduk').setValue(sel[0].get('kd_produk'));
                    menuhpproduk.hide();
                }
            }
        }
    });

    var menuhpproduk = new Ext.menu.Menu();
    menuhpproduk.add(new Ext.Panel({
        title: 'Pilih Produk',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridhpproduk],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menuhpproduk.hide();
                }
            }]
    }));

    Ext.ux.TwinCombohpproduk = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            var kd_supplier = Ext.getCmp('id_cbhpsuplier').getValue();
            if (!kd_supplier) {
                Ext.Msg.show({
                    title: 'Error',
                    msg: 'Silahkan Pilih Supplier Terlebih Dahulu',
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK,
                    // fn: function(btn){
                    // if (btn == 'ok' && msg == 'Session Expired') {
                    // window.location = '<?= site_url("auth/login") ?>';
                    // }
                    // }
                });
                return;
            }

            strgridhpproduk.load({
                params: {
                    start: STARTPAGE,
                    limit: ENDPAGE,
                    kd_supplier: Ext.getCmp('id_cbhpsuplier').getValue(),
                    kd_kategori1: Ext.getCmp('mb_hp_cbkategori1').getValue(),
                    kd_kategori2: Ext.getCmp('mb_hp_cbkategori2').getValue(),
                    kd_kategori3: Ext.getCmp('mb_hp_cbkategori3').getValue(),
                    kd_kategori4: Ext.getCmp('mb_hp_cbkategori4').getValue(),
                    no_bukti: Ext.getCmp('id_cbhpnobuktifilter').getValue(),
                    list: Ext.getCmp('ehp_list').getValue(),
                }
            });
            menuhpproduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menuhpproduk.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridhpproduk').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgridhpproduk').setValue('');
            searchgridhpproduk.onTrigger2Click();
        }
    });

    var cbhpproduk = new Ext.ux.TwinCombohpproduk({
        id: 'id_cbhpproduk',
        store: strcbhpproduk,
        mode: 'local',
        valueField: 'kd_produk',
        displayField: 'kd_produk',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_produk',
        emptyText: 'Pilih Produk'
    });
    /* END twin produk*/



    function Editedhp_beli() {
        Ext.getCmp('hp_edited').setValue('Y');
    }
    ;
    function diskChangeharga_beli() {
        Editedhp_beli();
        var pct_margin = Ext.getCmp('edit_pct_margin').getValue();
        var rp_ongkos_kirim = Ext.getCmp('edit_rp_ongkos_kirim').getValue();

        var hrg_supp = Ext.getCmp('ehp_hrg_supplier').getValue();
        var rp_margin = (hrg_supp * pct_margin) / 100;
        var het_harga_beli = parseInt(hrg_supp) + parseInt(rp_margin) + parseInt(rp_ongkos_kirim);
        Ext.getCmp('ehp_het_harga_beli').setValue(parseInt(het_harga_beli));

        var total_disk = 0;
        var disk_sup1_op = Ext.getCmp('hp_disk_supp1_op').getValue();
        var disk_sup1 = Ext.getCmp('hp_disk_supp1').getValue();
        if (disk_sup1_op == '%') {
            // disk_sup1 = (disk_sup1*hrg_supp)/100;
            total_disk = hrg_supp - (hrg_supp * (disk_sup1 / 100));
        } else {
            total_disk = hrg_supp - disk_sup1;
        }

        var disk_sup2_op = Ext.getCmp('hp_disk_supp2_op').getValue();
        var disk_sup2 = Ext.getCmp('hp_disk_supp2').getValue();
        if (disk_sup2_op == '%') {
            // disk_sup2 = (disk_sup2*disk_sup1)/100;
            total_disk = total_disk - (total_disk * (disk_sup2 / 100));
        } else {
            total_disk = total_disk - disk_sup2;
        }

        var disk_sup3_op = Ext.getCmp('hp_disk_supp3_op').getValue();
        var disk_sup3 = Ext.getCmp('hp_disk_supp3').getValue();
        if (disk_sup3_op == '%') {
            // disk_sup3 = (disk_sup3*disk_sup2)/100;
            total_disk = total_disk - (total_disk * (disk_sup3 / 100));
        } else {
            total_disk = total_disk - disk_sup3;
        }

        var disk_sup4_op = Ext.getCmp('hp_disk_supp4_op').getValue();
        var disk_sup4 = Ext.getCmp('hp_disk_supp4').getValue();
        if (disk_sup4_op == '%') {
            // disk_sup4 = (disk_sup4*disk_sup3)/100;
            total_disk = total_disk - (total_disk * (disk_sup4 / 100));
        } else {
            total_disk = total_disk - disk_sup4;
        }

        var total_disk = total_disk - Ext.getCmp('hp_disk_supp5').getValue();

        var net_price_sup = total_disk;
        Ext.getCmp('ehp_net_hrg_supplier_sup_inc').setValue(net_price_sup);


        if (Ext.getCmp('hp_pkp').getValue() == 'YA') {
            Ext.getCmp('ehp_net_hrg_supplier_sup').setValue(net_price_sup / 1.1);
        } else {
            Ext.getCmp('ehp_net_hrg_supplier_sup').setValue(net_price_sup);
        }

//        var disk_dist1_op = Ext.getCmp('hp_disk_dist1_op').getValue();
//        var disk_dist1 = Ext.getCmp('hp_disk_dist1').getValue();
//        if (disk_dist1_op == '%') {
//            // disk_dist1 = (disk_dist1*hrg_supp)/100;
//            total_disk = hrg_supp - (hrg_supp * (disk_dist1 / 100));
//        } else {
//            total_disk = hrg_supp - disk_dist1;
//        }
//
//        var disk_dist2_op = Ext.getCmp('hp_disk_dist2_op').getValue();
//        var disk_dist2 = Ext.getCmp('hp_disk_dist2').getValue();
//        if (disk_dist2_op == '%') {
//            // disk_dist2 = (disk_dist2*disk_dist1)/100;
//            total_disk = total_disk - (total_disk * (disk_dist2 / 100));
//        } else {
//            total_disk = total_disk - disk_dist2;
//        }
//
//        var disk_dist3_op = Ext.getCmp('hp_disk_dist3_op').getValue();
//        var disk_dist3 = Ext.getCmp('hp_disk_dist3').getValue();
//        if (disk_dist3_op == '%') {
//            // disk_dist3 = (disk_dist3*disk_dist2)/100;
//            total_disk = total_disk - (total_disk * (disk_dist3 / 100));
//        } else {
//            total_disk = total_disk - disk_dist3;
//        }
//
//        var disk_dist4_op = Ext.getCmp('hp_disk_dist4_op').getValue();
//        var disk_dist4 = Ext.getCmp('hp_disk_dist4').getValue();
//        if (disk_dist4_op == '%') {
//            // disk_dist4 = (disk_dist4*disk_dist3)/100;
//            total_disk = total_disk - (total_disk * (disk_dist4 / 100));
//        } else {
//            total_disk = total_disk - disk_dist4;
//        }
//
//        var total_disk = total_disk - Ext.getCmp('hp_disk_amt_dist5').getValue();
//
//        var net_price_dist = total_disk;
//        Ext.getCmp('ehp_net_hrg_supplier_dist_inc').setValue(net_price_dist);
//
//        if (Ext.getCmp('hp_pkp').getValue() == 'YA') {
//            Ext.getCmp('ehp_net_hrg_supplier_dist').setValue(net_price_dist / 1.1);
//        } else {
//            Ext.getCmp('ehp_net_hrg_supplier_dist').setValue(net_price_dist);
//        }
    }
    ;


    var strcbhpsuplier = new Ext.data.ArrayStore({
        fields: ['kd_supplier'],
        data: []
    });

    var strgridhpsuplier = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_supplier', 'nama_supplier', 'pkp'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("harga_pembelian/search_supplier") ?>',
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

    var searchgridhpsuplier = new Ext.app.SearchField({
        store: strgridhpsuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridhpsuplier'
    });

    strgridhpsuplier.on('load', function() {
        Ext.getCmp('id_searchgridhpsuplier').focus();
    });

    var gridhpsuplier = new Ext.grid.GridPanel({
        store: strgridhpsuplier,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'Kode Supplier',
                dataIndex: 'kd_supplier',
                width: 80,
                sortable: true,
            }, {
                header: 'Nama Supplier',
                dataIndex: 'nama_supplier',
                width: 300,
                sortable: true,
            }, {
                header: 'PKP',
                dataIndex: 'pkp',
                width: 300,
                sortable: true,
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridhpsuplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridhpsuplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cbhpsuplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('hp_nama_supplier').setValue(sel[0].get('nama_supplier'));
                    if (sel[0].get('pkp') == 1) {
                        Ext.getCmp('hp_pkp').setValue('YA');
                    } else {
                        Ext.getCmp('hp_pkp').setValue('TIDAK');
                    }

                    strhargapembelian.removeAll();

                    menuhpsuplier.hide();
                }
            }
        }
    });

    var menuhpsuplier = new Ext.menu.Menu();
    menuhpsuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridhpsuplier],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menuhpsuplier.hide();
                }
            }]
    }));

    Ext.ux.TwinCombohpSuplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridhpsuplier.load();
            menuhpsuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menuhpsuplier.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridhpsuplier').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgridhpsuplier').setValue('');
            searchgridhpsuplier.onTrigger2Click();
        }
    });

    var cbhpsuplier = new Ext.ux.TwinCombohpSuplier({
        fieldLabel: 'Supplier <span class="asterix">*</span>',
        id: 'id_cbhpsuplier',
        store: strcbhpsuplier,
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

    // combobox kategori1
    var str_hp_cbkategori1 = new Ext.data.Store({
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
            load: function() {
                var r = new (str_hp_cbkategori1.recordType)({
                    'kd_kategori1': '',
                    'nama_kategori1': '-----'
                });
                str_hp_cbkategori1.insert(0, r);
            },
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var hp_cbkategori1 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 1 ',
        id: 'mb_hp_cbkategori1',
        store: str_hp_cbkategori1,
        valueField: 'kd_kategori1',
        displayField: 'nama_kategori1',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori1',
        emptyText: 'Pilih kategori 1',
        listeners: {
            'select': function(combo, records) {
                var kdhp_cbkategori1 = hp_cbkategori1.getValue();
                // hp_cbkategori2.setValue();
                hp_cbkategori2.store.proxy.conn.url = '<?= site_url("kategori3/get_kategori2") ?>/' + kdhp_cbkategori1;
                hp_cbkategori2.store.reload();
            }
        }
    });
    // combobox kategori2
    var str_hp_cbkategori2 = new Ext.data.Store({
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
            load: function() {
                var r = new (str_hp_cbkategori2.recordType)({
                    'kd_kategori2': '',
                    'nama_kategori2': '-----'
                });
                str_hp_cbkategori2.insert(0, r);
            },
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var hp_cbkategori2 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 2 ',
        id: 'mb_hp_cbkategori2',
        mode: 'local',
        store: str_hp_cbkategori2,
        valueField: 'kd_kategori2',
        displayField: 'nama_kategori2',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori2',
        emptyText: 'Pilih kategori 2',
        listeners: {
            select: function(combo, records) {
                var kd_hp_cbkategori1 = hp_cbkategori1.getValue();
                var kd_hp_cbkategori2 = this.getValue();
                hp_cbkategori3.setValue();
                hp_cbkategori3.store.proxy.conn.url = '<?= site_url("kategori4/get_kategori3") ?>/' + kd_hp_cbkategori1 + '/' + kd_hp_cbkategori2;
                hp_cbkategori3.store.reload();
            }
        }
    });

    // combobox kategori3
    var str_hp_cbkategori3 = new Ext.data.Store({
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
            load: function() {
                var r = new (str_hp_cbkategori3.recordType)({
                    'kd_kategori3': '',
                    'nama_kategori3': '-----'
                });
                str_hp_cbkategori3.insert(0, r);
            },
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var hp_cbkategori3 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 3 ',
        id: 'mb_hp_cbkategori3',
        mode: 'local',
        store: str_hp_cbkategori3,
        valueField: 'kd_kategori3',
        displayField: 'nama_kategori3',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori3',
        emptyText: 'Pilih kategori 3',
        listeners: {
            select: function(combo, records) {
                var kd_hp_cbkategori1 = hp_cbkategori1.getValue();
                var kd_hp_cbkategori2 = hp_cbkategori2.getValue();
                var kd_hp_cbkategori3 = this.getValue();
                hp_cbkategori4.setValue();
                hp_cbkategori4.store.proxy.conn.url = '<?= site_url("master_barang/get_kategori4") ?>/' + kd_hp_cbkategori1 + '/' + kd_hp_cbkategori2 + '/' + kd_hp_cbkategori3;
                hp_cbkategori4.store.reload();
            }
        }
    });

    // combobox kategori4
    var str_hp_cbkategori4 = new Ext.data.Store({
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
            load: function() {
                var r = new (str_hp_cbkategori4.recordType)({
                    'kd_kategori4': '',
                    'nama_kategori4': '-----'
                });
                str_hp_cbkategori4.insert(0, r);
            },
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var hp_cbkategori4 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 4',
        id: 'mb_hp_cbkategori4',
        mode: 'local',
        store: str_hp_cbkategori4,
        valueField: 'kd_kategori4',
        displayField: 'nama_kategori4',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori4',
        emptyText: 'Pilih kategori 4'
    });
    // combobox Ukuran
    var str_hp_cbukuran = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_ukuran', 'nama_ukuran'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_barang/get_ukuran_produk") ?>',
            method: 'POST'
        }),
        listeners: {
            load: function() {
                var r = new (str_hp_cbukuran.recordType)({
                    'kd_ukuran': '',
                    'nama_ukuran': '-----'
                });
                str_hp_cbukuran.insert(0, r);
            },
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var hp_cbukuran = new Ext.form.ComboBox({
        fieldLabel: 'Ukuran ',
        id: 'id_hp_cbukuran',
        store: str_hp_cbukuran,
        valueField: 'kd_ukuran',
        displayField: 'nama_ukuran',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: true,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_ukuran',
        emptyText: 'Pilih Ukuran'

    });

    // combobox Satuan
    var str_hp_cbsatuan = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_satuan', 'nm_satuan'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_barang/get_satuan_produk") ?>',
            method: 'POST'
        }),
        listeners: {
            load: function() {
                var r = new (str_hp_cbsatuan.recordType)({
                    'kd_satuan': '',
                    'nm_satuan': '-----'
                });
                str_hp_cbsatuan.insert(0, r);
            },
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
    var hp_cbsatuan = new Ext.form.ComboBox({
        fieldLabel: 'Satuan',
        id: 'id_hp_cbsatuan',
        store: str_hp_cbsatuan,
        valueField: 'kd_satuan',
        displayField: 'nm_satuan',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: true,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_satuan',
        emptyText: 'Pilih Satuan'

    });

    var strhargapembelian = new Ext.data.Store({
        autoSave: false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_supplier', allowBlank: false, type: 'text'},
                {name: 'kd_produk', allowBlank: false, type: 'text'},
                {name: 'kd_produk_lama', allowBlank: false, type: 'text'},
                {name: 'nama_produk', allowBlank: false, type: 'text'},
                {name: 'nm_satuan', allowBlank: false, type: 'text'},
                {name: 'nama_supplier', allowBlank: false, type: 'text'},
                {name: 'rp_het_harga_beli', allowBlank: false, type: 'int'},
                {name: 'pct_margin', allowBlank: false, type: 'int'},
                {name: 'rp_ongkos_kirim', allowBlank: false, type: 'int'},
                {name: 'hrg_supplier', allowBlank: false, type: 'int'},
                {name: 'disk_supp1_op', allowBlank: false, type: 'text'},
                {name: 'disk_supp2_op', allowBlank: false, type: 'text'},
                {name: 'disk_supp3_op', allowBlank: false, type: 'text'},
                {name: 'disk_supp4_op', allowBlank: false, type: 'text'},
                {name: 'disk_dist1_op', allowBlank: false, type: 'text'},
                {name: 'disk_dist2_op', allowBlank: false, type: 'text'},
                {name: 'disk_dist3_op', allowBlank: false, type: 'text'},
                {name: 'disk_dist4_op', allowBlank: false, type: 'text'},
                {name: 'disk_supp1', allowBlank: false, type: 'float'},
                {name: 'disk_supp2', allowBlank: false, type: 'float'},
                {name: 'disk_supp3', allowBlank: false, type: 'float'},
                {name: 'disk_supp4', allowBlank: false, type: 'float'},
                {name: 'disk_amt_supp5', allowBlank: false, type: 'int'},
                {name: 'disk_dist1', allowBlank: false, type: 'float'},
                {name: 'disk_dist2', allowBlank: false, type: 'float'},
                {name: 'disk_dist3', allowBlank: false, type: 'float'},
                {name: 'disk_dist4', allowBlank: false, type: 'float'},
                {name: 'disk_amt_dist5', allowBlank: false, type: 'int'},
                {name: 'net_hrg_supplier_dist', allowBlank: false, type: 'int'},
                {name: 'net_hrg_supplier_sup', allowBlank: false, type: 'int'},
                {name: 'net_hrg_supplier_dist_inc', allowBlank: false, type: 'int'},
                {name: 'net_hrg_supplier_sup_inc', allowBlank: false, type: 'int'},
                {name: 'waktu_top', allowBlank: false, type: 'text'},
                {name: 'keterangan', allowBlank: false, type: 'text'},
                {name: 'is_konsinyasi', allowBlank: false, type: 'text'},
                {name: 'tgl_start_diskon', allowBlank: false, type: 'text'}
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("harga_pembelian/search_produk_by_supplier") ?>',
            method: 'POST'
        }),
        writer: new Ext.data.JsonWriter(
                {
                    encode: true,
                    writeAllFields: true
                })
    });

    strhargapembelian.on('load', function() {
        strhargapembelian.setBaseParam('kd_supplier', Ext.getCmp('id_cbhpsuplier').getValue());
        strhargapembelian.setBaseParam('kd_kategori1', Ext.getCmp('mb_hp_cbkategori1').getValue());
        strhargapembelian.setBaseParam('kd_kategori2', Ext.getCmp('mb_hp_cbkategori2').getValue());
        strhargapembelian.setBaseParam('kd_kategori3', Ext.getCmp('mb_hp_cbkategori3').getValue());
        strhargapembelian.setBaseParam('kd_kategori4', Ext.getCmp('mb_hp_cbkategori4').getValue());
        strhargapembelian.setBaseParam('no_bukti', Ext.getCmp('id_cbhpnobuktifilter').getValue());
    })

    var searchgridhargapembelian = new Ext.app.SearchField({
        store: strhargapembelian,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridhargapembelian',
        emptyText: 'Kode Barang, Kode Barang Lama, Nama Barang'
    });

    searchgridhargapembelian.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';

            // Get the value of search field
            var kd_supplier = Ext.getCmp('id_cbhpsuplier').getValue();
            var kd_kategori1 = Ext.getCmp('mb_hp_cbkategori1').getValue();
            var kd_kategori2 = Ext.getCmp('mb_hp_cbkategori2').getValue();
            var kd_kategori3 = Ext.getCmp('mb_hp_cbkategori3').getValue();
            var kd_kategori4 = Ext.getCmp('mb_hp_cbkategori4').getValue();
            var no_bukti = Ext.getCmp('id_cbhpnobuktifilter').getValue();
            var list = Ext.getCmp('ehp_list').getValue();

            if (!kd_supplier) {
                Ext.Msg.show({
                    title: 'Error',
                    msg: 'Silahkan Pilih Supplier Terlebih Dahulu',
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK,
                    // fn: function(btn){
                    // if (btn == 'ok' && msg == 'Session Expired') {
                    // window.location = '<?= site_url("auth/login") ?>';
                    // }
                    // }
                });
                return;
            }

            var o = {start: 0,
                kd_supplier: kd_supplier,
                kd_kategori1: kd_kategori1,
                kd_kategori2: kd_kategori2,
                kd_kategori3: kd_kategori3,
                kd_kategori4: kd_kategori4,
                no_bukti: no_bukti,
                list: list,
            };

            this.store.baseParams = this.store.baseParams || {};
            this.store.baseParams[this.paramName] = '';
            this.store.reload({
                params: o
            });
            this.triggers[0].hide();
            this.hasSearch = false;
        }
    };

    searchgridhargapembelian.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }

        // Get the value of search field
        var kd_supplier = Ext.getCmp('id_cbhpsuplier').getValue();
        var kd_kategori1 = Ext.getCmp('mb_hp_cbkategori1').getValue();
        var kd_kategori2 = Ext.getCmp('mb_hp_cbkategori2').getValue();
        var kd_kategori3 = Ext.getCmp('mb_hp_cbkategori3').getValue();
        var kd_kategori4 = Ext.getCmp('mb_hp_cbkategori4').getValue();
        var no_bukti = Ext.getCmp('id_cbhpnobuktifilter').getValue();
        var list = Ext.getCmp('ehp_list').getValue();

        if (!kd_supplier) {
            Ext.Msg.show({
                title: 'Error',
                msg: 'Silahkan Pilih Supplier Terlebih Dahulu',
                modal: true,
                icon: Ext.Msg.ERROR,
                buttons: Ext.Msg.OK,
                // fn: function(btn){
                // if (btn == 'ok' && msg == 'Session Expired') {
                // window.location = '<?= site_url("auth/login") ?>';
                // }
                // }
            });
            return;
        }

        var o = {start: 0,
            kd_supplier: kd_supplier,
            kd_kategori1: kd_kategori1,
            kd_kategori2: kd_kategori2,
            kd_kategori3: kd_kategori3,
            kd_kategori4: kd_kategori4,
            no_bukti: no_bukti,
            list: list,
        };

        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params: o});
        this.hasSearch = true;
        this.triggers[0].show();
    };


    var headerhargapembelian = {
        layout: 'column',
        border: false,
        buttonAlign: 'left',
        items: [{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [{
                        xtype: 'textfield',
                        fieldLabel: 'No Bukti',
                        name: 'no_hp',
                        readOnly: true,
                        fieldClass: 'readonly-input',
                        id: 'hp_no_hp',
                        anchor: '90%',
                        value: ''
                    }, cbhpsuplier, cbhpnobuktifilter, hp_cbkategori1, hp_cbkategori2, {
                        xtype: 'textarea',
                        style: 'text-transform: uppercase',
                        fieldLabel: 'Kode Barang, Kode Barang Lama',
                        name: 'list',
                        id: 'ehp_list',
                        anchor: '90%',
                    }, {
                        xtype: 'label',
                        text: '*) Tidak Boleh Ada Spasi dan Enter',
                        style: 'margin-left:100px',
                    }
                ]
            }, {
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [{
                        xtype: 'datefield',
                        fieldLabel: 'Tanggal <span class="asterix">*</span>',
                        name: 'tanggal',
                        allowBlank: false,
                        format: 'd-m-Y',
                        editable: false,
                        id: 'hp_tanggal',
                        anchor: '90%',
                        value: ''
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'Nama Supplier',
                        name: 'nama_supplier',
                        readOnly: true,
                        fieldClass: 'readonly-input',
                        id: 'hp_nama_supplier',
                        anchor: '90%',
                        value: ''
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'Status PKP',
                        name: 'pkp',
                        readOnly: true,
                        fieldClass: 'readonly-input',
                        id: 'hp_pkp',
                        anchor: '90%',
                        value: ''
                    }, hp_cbkategori3, hp_cbkategori4, hp_cbukuran, hp_cbsatuan]
            }],
        buttons: [{
                text: 'Filter',
                formBind: true,
                handler: function() {
                    var kd_supplier = Ext.getCmp('id_cbhpsuplier').getValue();
                    if (!kd_supplier) {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Silahkan Pilih Supplier Terlebih Dahulu',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK,
                            // fn: function(btn){
                            // if (btn == 'ok' && msg == 'Session Expired') {
                            // window.location = '<?= site_url("auth/login") ?>';
                            // }
                            // }
                        });
                        return;
                    }
                    strhargapembelian.load({
                        params: {
                            start: STARTPAGE,
                            limit: ENDPAGE,
                            kd_supplier: Ext.getCmp('id_cbhpsuplier').getValue(),
                            kd_kategori1: Ext.getCmp('mb_hp_cbkategori1').getValue(),
                            kd_kategori2: Ext.getCmp('mb_hp_cbkategori2').getValue(),
                            kd_kategori3: Ext.getCmp('mb_hp_cbkategori3').getValue(),
                            kd_kategori4: Ext.getCmp('mb_hp_cbkategori4').getValue(),
                            kd_ukuran: Ext.getCmp('id_hp_cbukuran').getValue(),
                            kd_satuan: Ext.getCmp('id_hp_cbsatuan').getValue(),
                            no_bukti: Ext.getCmp('id_cbhpnobuktifilter').getValue(),
                            list: Ext.getCmp('ehp_list').getValue(),
                        }
                    });
                }
            }]
    };

    var editorhargapembelian = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });

    var gridhargapembelian = new Ext.grid.GridPanel({
        store: strhargapembelian,
        stripeRows: true,
        height: 350,
        loadMask: true,
        frame: true,
        border: true,
        plugins: [editorhargapembelian],
        columns: [new Ext.grid.RowNumberer({width: 30}), {
                header: 'Kd Supplier',
                dataIndex: 'kd_supplier',
                width: 100,
                hidden: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'edit_hp_kd_supplier'
                })
                        // },{
                        // header: 'Edited',
                        // dataIndex: 'edited',
                        // width: 50,
                        // sortable: true,
                        // editor: new Ext.form.TextField({
                        // readOnly: true,
                        // fieldClass:'readonly-input',
                        // id: 'hp_edited'
                        // })
            }, {header: 'Edited',
                dataIndex: 'edited',
                width: 50,
                sortable: true,
                editor: {
                    xtype: 'combo',
                    store: new Ext.data.JsonStore({
                        fields: ['name'],
                        data: [
                            {name: 'Y'},
                            {name: 'No'},
                        ]
                    }),
                    id: 'hp_edited',
                    mode: 'local',
                    name: 'edited',
                    value: '%',
                    width: 50,
                    editable: false,
                    hiddenName: 'edited',
                    valueField: 'name',
                    displayField: 'name',
                    triggerAction: 'all',
                    forceSelection: true,
                }
            }, {
                header: 'Kode Barang',
                dataIndex: 'kd_produk',
                width: 100,
                sortable: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'ehp_kd_produk'
                })
            }, {
                header: 'Kode Barang Lama',
                dataIndex: 'kd_produk_lama',
                width: 110,
                sortable: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'ehp_kd_produk_lama'
                })
            }, {
                header: 'Nama Barang',
                dataIndex: 'nama_produk',
                width: 300,
                sortable: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'ehp_nama_produk'
                })
            }, {
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 80,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'ehp_satuan'
                })
            }, {
                header: 'Nama Supplier',
                dataIndex: 'nama_supplier',
                width: 150,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'ehp_nama_supplier'
                })
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'TOP',
                dataIndex: 'waktu_top',
                width: 60,
                editor: new Ext.form.TextField({
                    // readOnly: true,
                    id: 'ehp_waktu_top',
                    listeners: {
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                Editedhp_beli();
                            }, c);
                        }
                    }
                })
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Harga Supplier',
                dataIndex: 'hrg_supplier',
                width: 100,
                editor: {
                    xtype: 'numberfield',
                    id: 'ehp_hrg_supplier',
                    // readOnly: true,
                    listeners: {
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                diskChangeharga_beli();
                            }, c);
                        }
                    }
                }
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: "% Margin",
                dataIndex: 'pct_margin',
                hidden: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'edit_pct_margin'
                })
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: "Rp Ongkos Kirim",
                dataIndex: 'rp_ongkos_kirim',
                width: 120,
                hidden: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'edit_rp_ongkos_kirim'
                })
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'HET Beli',
                hidden: true,
                dataIndex: 'rp_het_harga_beli',
                width: 100,
                editor: {
                    xtype: 'numberfield',
                    id: 'ehp_het_harga_beli',
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    // listeners:{
                    // 'render': function(c) {
                    // c.getEl().on('keyup', function() {
                    // diskChangeharga_beli();
                    // }, c);
                    // }
                    // }
                }
            }, {
                header: '% / Rp',
                dataIndex: 'disk_supp1_op',
                width: 50,
                editor: {
                    xtype: 'combo',
                    store: new Ext.data.JsonStore({
                        fields: ['name'],
                        data: [
                            {name: '%'},
                            {name: 'Rp'},
                        ]
                    }),
                    id: 'hp_disk_supp1_op',
                    mode: 'local',
                    name: 'disk_supp1_op',
                    value: '%',
                    width: 50,
                    editable: false,
                    hiddenName: 'disk_supp1_op',
                    valueField: 'name',
                    displayField: 'name',
                    triggerAction: 'all',
                    forceSelection: true,
                    listeners: {
                        'expand': function() {
                            Ext.getCmp('hp_disk_supp1').setValue(0);
                        },
                        select: function() {
                            Ext.getCmp('hp_disk_supp1').setMaxValue(Number.MAX_VALUE);
                            if (this.getValue() === 'persen')
                                Ext.getCmp('hp_disk_supp1').maxValue = 100;
                            else
                                Ext.getCmp('hp_disk_supp1').maxLength = 11;
                        }
                    }
                }
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                // format: '0,0',
                header: 'Diskon Supermarket 1',
                dataIndex: 'disk_supp1',
                width: 150,
                editor: {
                    xtype: 'numberfield',
                    msgTarget: 'under',
                    flex: 1,
                    width: 115,
                    name: 'disk_supp1',
                    id: 'hp_disk_supp1',
                    style: 'text-align:right;',
                    listeners: {
                        // 'change':function(){
                        // diskChangeharga_beli();
                        // },
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                diskChangeharga_beli();
                            }, c);
                        }

                    }
                }
            }, {
                header: '% / Rp',
                dataIndex: 'disk_supp2_op',
                width: 50,
                editor: {
                    xtype: 'combo',
                    store: new Ext.data.JsonStore({
                        fields: ['name'],
                        data: [
                            {name: '%'},
                            {name: 'Rp'},
                        ]
                    }),
                    id: 'hp_disk_supp2_op',
                    mode: 'local',
                    name: 'disk_supp2_op',
                    value: '%',
                    width: 50,
                    editable: false,
                    hiddenName: 'disk_supp2_op',
                    valueField: 'name',
                    displayField: 'name',
                    triggerAction: 'all',
                    forceSelection: true,
                    listeners: {
                        'expand': function() {
                            Ext.getCmp('hp_disk_supp2').setValue(0);
                        },
                        select: function() {
                            Ext.getCmp('hp_disk_supp2').setMaxValue(Number.MAX_VALUE);
                            if (this.getValue() === 'persen')
                                Ext.getCmp('hp_disk_supp2').maxValue = 100;
                            else
                                Ext.getCmp('hp_disk_supp2').maxLength = 11;
                            Editedhp_beli();
                        }
                    }
                }
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                // format: '0,0',
                header: 'Diskon Supermarket 2',
                dataIndex: 'disk_supp2',
                width: 150,
                editor: {
                    xtype: 'numberfield',
                    msgTarget: 'under',
                    flex: 1,
                    width: 115,
                    name: 'disk_supp2',
                    id: 'hp_disk_supp2',
                    style: 'text-align:right;',
                    listeners: {
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                diskChangeharga_beli();
                            }, c);
                        }
                    }
                }
            }, {
                header: '% / Rp',
                dataIndex: 'disk_supp3_op',
                width: 50,
                editor: {
                    xtype: 'combo',
                    store: new Ext.data.JsonStore({
                        fields: ['name'],
                        data: [
                            {name: '%'},
                            {name: 'Rp'},
                        ]
                    }),
                    id: 'hp_disk_supp3_op',
                    mode: 'local',
                    name: 'disk_supp3_op',
                    value: '%',
                    width: 50,
                    editable: false,
                    hiddenName: 'disk_supp3_op',
                    valueField: 'name',
                    displayField: 'name',
                    triggerAction: 'all',
                    forceSelection: true,
                    listeners: {
                        'expand': function() {
                            Ext.getCmp('hp_disk_supp3').setValue(0);
                        },
                        select: function() {
                            Ext.getCmp('hp_disk_supp3').setMaxValue(Number.MAX_VALUE);
                            if (this.getValue() === 'persen')
                                Ext.getCmp('hp_disk_supp3').maxValue = 100;
                            else
                                Ext.getCmp('hp_disk_supp3').maxLength = 11;
                            Editedhp_beli();
                        }
                    }
                }
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                // format: '0,0',
                header: 'Diskon Supermarket 3',
                dataIndex: 'disk_supp3',
                width: 150,
                editor: {
                    xtype: 'numberfield',
                    msgTarget: 'under',
                    flex: 1,
                    width: 115,
                    name: 'disk_supp3',
                    id: 'hp_disk_supp3',
                    style: 'text-align:right;',
                    listeners: {
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                diskChangeharga_beli();
                            }, c);
                        }
                    }
                }
            }, {
                header: '% / Rp',
                dataIndex: 'disk_supp4_op',
                width: 50,
                editor: {
                    xtype: 'combo',
                    store: new Ext.data.JsonStore({
                        fields: ['name'],
                        data: [
                            {name: '%'},
                            {name: 'Rp'},
                        ]
                    }),
                    id: 'hp_disk_supp4_op',
                    mode: 'local',
                    name: 'disk_supp4_op',
                    value: '%',
                    width: 50,
                    editable: false,
                    hiddenName: 'disk_supp4_op',
                    valueField: 'name',
                    displayField: 'name',
                    triggerAction: 'all',
                    forceSelection: true,
                    listeners: {
                        'expand': function() {
                            Ext.getCmp('hp_disk_supp4').setValue(0);
                        },
                        select: function() {
                            Ext.getCmp('hp_disk_supp4').setMaxValue(Number.MAX_VALUE);
                            if (this.getValue() === 'persen')
                                Ext.getCmp('hp_disk_supp4').maxValue = 100;
                            else
                                Ext.getCmp('hp_disk_supp4').maxLength = 11;
                            Editedhp_beli();
                        }
                    }
                }
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                // format: '0,0',
                header: 'Diskon Supermarket 4',
                dataIndex: 'disk_supp4',
                width: 150,
                editor: {
                    xtype: 'numberfield',
                    msgTarget: 'under',
                    flex: 1,
                    width: 115,
                    name: 'disk_supp4',
                    id: 'hp_disk_supp4',
                    style: 'text-align:right;',
                    listeners: {
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                diskChangeharga_beli();
                            }, c);
                        }
                    }
                }
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Diskon Supermarket 5',
                dataIndex: 'disk_amt_supp5',
                width: 150,
                editor: {
                    xtype: 'numberfield',
                    msgTarget: 'under',
                    flex: 1,
                    width: 115,
                    name: 'disk_supp5',
                    id: 'hp_disk_supp5',
                    style: 'text-align:right;',
                    listeners: {
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                diskChangeharga_beli();
                            }, c);
                        }
                    }
                }
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Net Price Supermarket(Inc.PPN)',
                dataIndex: 'net_hrg_supplier_sup_inc',
                width: 190,
                editor: {
                    xtype: 'numberfield',
                    id: 'ehp_net_hrg_supplier_sup_inc',
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    // style: 'text-align:right;'
                }
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Net Price Supermarket(Exc.PPN)',
                dataIndex: 'net_hrg_supplier_sup',
                width: 190,
                editor: {
                    xtype: 'numberfield',
                    id: 'ehp_net_hrg_supplier_sup',
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    // style: 'text-align:right;'
                }
            }, 
//            {
//                header: '% / Rp',
//                dataIndex: 'disk_dist1_op',
//                width: 50,
//                editor: {
//                    xtype: 'combo',
//                    store: new Ext.data.JsonStore({
//                        fields: ['name'],
//                        data: [
//                            {name: '%'},
//                            {name: 'Rp'},
//                        ]
//                    }),
//                    id: 'hp_disk_dist1_op',
//                    mode: 'local',
//                    name: 'disk_dist1_op',
//                    value: '%',
//                    width: 50,
//                    editable: false,
//                    hiddenName: 'disk_dist1_op',
//                    valueField: 'name',
//                    displayField: 'name',
//                    triggerAction: 'all',
//                    forceSelection: true,
//                    listeners: {
//                        'expand': function() {
//                            Ext.getCmp('hp_disk_dist1').setValue(0);
//                        },
//                        select: function() {
//                            Ext.getCmp('hp_disk_dist1').setMaxValue(Number.MAX_VALUE);
//                            if (this.getValue() === 'persen')
//                                Ext.getCmp('hp_disk_dist1').maxValue = 100;
//                            else
//                                Ext.getCmp('hp_disk_dist1').maxLength = 11;
//                            Editedhp_beli();
//                        }
//                    }
//                }
//            }, {
//                xtype: 'numbercolumn',
//                align: 'right',
//                // format: '0,0',
//                header: 'Diskon Distribusi 1',
//                dataIndex: 'disk_dist1',
//                width: 150,
//                editor: {
//                    xtype: 'numberfield',
//                    msgTarget: 'under',
//                    flex: 1,
//                    width: 115,
//                    name: 'disk_dist1',
//                    id: 'hp_disk_dist1',
//                    style: 'text-align:right;',
//                    listeners: {
//                        'render': function(c) {
//                            c.getEl().on('keyup', function() {
//                                diskChangeharga_beli();
//                            }, c);
//                        }
//                    }
//                }
//            }, {
//                header: '% / Rp',
//                dataIndex: 'disk_dist2_op',
//                width: 50,
//                editor: {
//                    xtype: 'combo',
//                    store: new Ext.data.JsonStore({
//                        fields: ['name'],
//                        data: [
//                            {name: '%'},
//                            {name: 'Rp'},
//                        ]
//                    }),
//                    id: 'hp_disk_dist2_op',
//                    mode: 'local',
//                    name: 'disk_dist2_op',
//                    value: '%',
//                    width: 50,
//                    editable: false,
//                    hiddenName: 'disk_dist2_op',
//                    valueField: 'name',
//                    displayField: 'name',
//                    triggerAction: 'all',
//                    forceSelection: true,
//                    listeners: {
//                        'expand': function() {
//                            Ext.getCmp('hp_disk_dist2').setValue(0);
//                        },
//                        select: function() {
//                            Ext.getCmp('hp_disk_dist2').setMaxValue(Number.MAX_VALUE);
//                            if (this.getValue() === 'persen')
//                                Ext.getCmp('hp_disk_dist2').maxValue = 100;
//                            else
//                                Ext.getCmp('hp_disk_dist2').maxLength = 11;
//                            Editedhp_beli();
//                        }
//                    }
//                }
//            }, {
//                xtype: 'numbercolumn',
//                align: 'right',
//                // format: '0,0',
//                header: 'Diskon Distribusi 2',
//                dataIndex: 'disk_dist2',
//                width: 150,
//                editor: {
//                    xtype: 'numberfield',
//                    msgTarget: 'under',
//                    flex: 1,
//                    width: 115,
//                    name: 'disk_dist2',
//                    id: 'hp_disk_dist2',
//                    style: 'text-align:right;',
//                    listeners: {
//                        'render': function(c) {
//                            c.getEl().on('keyup', function() {
//                                diskChangeharga_beli();
//                            }, c);
//                        }
//                    }
//                }
//            }, {
//                header: '% / Rp',
//                dataIndex: 'disk_dist3_op',
//                width: 50,
//                editor: {
//                    xtype: 'combo',
//                    store: new Ext.data.JsonStore({
//                        fields: ['name'],
//                        data: [
//                            {name: '%'},
//                            {name: 'Rp'},
//                        ]
//                    }),
//                    id: 'hp_disk_dist3_op',
//                    mode: 'local',
//                    name: 'disk_dist3_op',
//                    value: '%',
//                    width: 50,
//                    editable: false,
//                    hiddenName: 'disk_dist3_op',
//                    valueField: 'name',
//                    displayField: 'name',
//                    triggerAction: 'all',
//                    forceSelection: true,
//                    listeners: {
//                        'expand': function() {
//                            Ext.getCmp('hp_disk_dist3').setValue(0);
//                        },
//                        select: function() {
//                            Ext.getCmp('hp_disk_dist3').setMaxValue(Number.MAX_VALUE);
//                            if (this.getValue() === 'persen')
//                                Ext.getCmp('hp_disk_dist3').maxValue = 100;
//                            else
//                                Ext.getCmp('hp_disk_dist3').maxLength = 11;
//                            Editedhp_beli();
//                        }
//                    }
//                }
//            }, {
//                xtype: 'numbercolumn',
//                align: 'right',
//                // format: '0,0',
//                header: 'Diskon Distribusi 3',
//                dataIndex: 'disk_dist3',
//                width: 150,
//                editor: {
//                    xtype: 'numberfield',
//                    msgTarget: 'under',
//                    flex: 1,
//                    width: 115,
//                    name: 'disk_dist3',
//                    id: 'hp_disk_dist3',
//                    style: 'text-align:right;',
//                    listeners: {
//                        'render': function(c) {
//                            c.getEl().on('keyup', function() {
//                                diskChangeharga_beli();
//                            }, c);
//                        }
//                    }
//                }
//            }, {
//                header: '% / Rp',
//                dataIndex: 'disk_dist4_op',
//                width: 50,
//                editor: {
//                    xtype: 'combo',
//                    store: new Ext.data.JsonStore({
//                        fields: ['name'],
//                        data: [
//                            {name: '%'},
//                            {name: 'Rp'},
//                        ]
//                    }),
//                    id: 'hp_disk_dist4_op',
//                    mode: 'local',
//                    name: 'disk_dist4_op',
//                    value: '%',
//                    width: 50,
//                    editable: false,
//                    hiddenName: 'disk_dist4_op',
//                    valueField: 'name',
//                    displayField: 'name',
//                    triggerAction: 'all',
//                    forceSelection: true,
//                    listeners: {
//                        'expand': function() {
//                            Ext.getCmp('hp_disk_dist4').setValue(0);
//                        },
//                        select: function() {
//                            Ext.getCmp('hp_disk_dist4').setMaxValue(Number.MAX_VALUE);
//                            if (this.getValue() === 'persen')
//                                Ext.getCmp('hp_disk_dist4').maxValue = 100;
//                            else
//                                Ext.getCmp('hp_disk_dist4').maxLength = 11;
//                            Editedhp_beli();
//                        }
//                    }
//                }
//            }, {
//                xtype: 'numbercolumn',
//                align: 'right',
//                // format: '0,0',
//                header: 'Diskon Distribusi 4',
//                dataIndex: 'disk_dist4',
//                width: 150,
//                editor: {
//                    xtype: 'numberfield',
//                    msgTarget: 'under',
//                    flex: 1,
//                    width: 115,
//                    name: 'disk_dist4',
//                    id: 'hp_disk_dist4',
//                    style: 'text-align:right;',
//                    listeners: {
//                        'render': function(c) {
//                            c.getEl().on('keyup', function() {
//                                diskChangeharga_beli();
//                            }, c);
//                        }
//                    }
//                }
//            }, {
//                xtype: 'numbercolumn',
//                align: 'right',
//                format: '0,0',
//                header: 'Diskon Distribusi 5',
//                dataIndex: 'disk_amt_dist5',
//                width: 150,
//                editor: {
//                    xtype: 'numberfield',
//                    msgTarget: 'under',
//                    flex: 1,
//                    width: 115,
//                    name: 'disk_amt_dist5',
//                    id: 'hp_disk_amt_dist5',
//                    style: 'text-align:right;',
//                    listeners: {
//                        'render': function(c) {
//                            c.getEl().on('keyup', function() {
//                                diskChangeharga_beli();
//                            }, c);
//                        }
//                    }
//                }
//            }, {
//                xtype: 'numbercolumn',
//                align: 'right',
//                format: '0,0',
//                header: 'Net Price Distribusi(Inc.PPN)',
//                dataIndex: 'net_hrg_supplier_dist_inc',
//                width: 190,
//                editor: {
//                    xtype: 'numberfield',
//                    id: 'ehp_net_hrg_supplier_dist_inc',
//                    readOnly: true,
//                    fieldClass: 'readonly-input',
//                    // style: 'text-align:right;'
//                }
//            }, {
//                xtype: 'numbercolumn',
//                align: 'right',
//                format: '0,0',
//                header: 'Net Price Distribusi(Exc.PPN)',
//                dataIndex: 'net_hrg_supplier_dist',
//                width: 190,
//                editor: {
//                    xtype: 'numberfield',
//                    id: 'ehp_net_hrg_supplier_dist',
//                    readOnly: true,
//                    fieldClass: 'readonly-input'
//                }
//            }, 
            {
                header: 'Is Konsinyasi',
                dataIndex: 'is_konsinyasi',
                width: 190


            },{
                    xtype: 'datecolumn',
                    header: 'Efektif Date',
                    dataIndex: 'tgl_start_diskon',
                    format: 'd/m/Y',
                    width: 120,
                    editor: new Ext.form.DateField({
                        id: 'ehp_tgl_start_diskon',
                        format: 'd/m/Y',
                        //minValue: (new Date()).clearTime(),
                         listeners:{			
                            'change': function() {
                               	  Ext.getCmp('hp_edited').setValue('Y');
                            }
                        }
                    })
                }],
        tbar: new Ext.Toolbar({
            items: [searchgridhargapembelian, '->', cbhpproduk, '-', cbhpnobukti, '-', {
                    text: 'Show History',
                    icon: BASE_ICONS + 'grid.png',
                    onClick: function() {
                        var kd_produk = Ext.getCmp('id_cbhpproduk').getValue();
                        var no_bukti = Ext.getCmp('id_cbhpnobukti').getValue();
                        if (kd_produk == '' && no_bukti == '') {
                            Ext.Msg.show({
                                title: 'Error',
                                msg: 'Silahkan Search Produk / No Bukti Terlebih Dulu',
                                modal: true,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OK
                            });
                            return;
                        }
                        gridhargapembelianhistory.store.load({
                            params: {
                                no_bukti: Ext.getCmp('id_cbhpnobukti').getValue(),
                                kd_produk: Ext.getCmp('id_cbhpproduk').getValue()
                            }
                        });
                        winshowhistoryhargapembelian.setTitle('History');
                        winshowhistoryhargapembelian.show();
                    }
                }, '-', {
                    text: 'Reset',
                    icon: BASE_ICONS + 'refresh.gif',
                    onClick: function() {
                        Ext.getCmp('id_cbhpnobukti').setValue('');
                        Ext.getCmp('id_cbhpproduk').setValue('');
                    }
                }]
        }),
        // bbar: new Ext.PagingToolbar({
        // pageSize: ENDPAGE,
        // store: strhargapembelian,
        // displayInfo: true
        // }),
    });


    var hargapembelian = new Ext.FormPanel({
        id: 'hargapembelian',
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
                items: [headerhargapembelian]
            }, {
                xtype: 'fieldset',
                autoheight: true,
                title: 'Diskon',
                collapsed: false,
                collapsible: true,
                anchor: '70%',
                items: [{
                        xtype: 'compositefield',
                        msgTarget: 'side',
                        fieldLabel: 'Disk Supermarket 1',
                        items: [{
                                xtype: 'compositefield',
                                msgTarget: 'side',
                                fieldLabel: 'Disk Supermarket 1',
                                width: 200,
                                items: [{
                                        xtype: 'combo',
                                        mode: 'local',
                                        value: '%',
                                        triggerAction: 'all',
                                        forceSelection: true,
                                        name: 'disk_kons1_op',
                                        id: 'hb_disk_kons1_op',
                                        hiddenName: 'disk_kons1_op',
                                        displayField: 'name',
                                        valueField: 'value',
                                        width: 50,
                                        store: new Ext.data.JsonStore({
                                            fields: ['name', 'value'],
                                            data: [
                                                {name: '%', value: '%'},
                                                {name: 'Rp', value: 'Rp'},
                                            ]
                                        }),
                                        listeners: {
                                            select: function() {
                                                Ext.getCmp('hb_disk_kons1').setMaxValue(Number.MAX_VALUE);
                                                if (this.getValue() == '%')
                                                    Ext.getCmp('hb_disk_kons1').maxValue = 100;
                                                else
                                                    Ext.getCmp('hb_disk_kons1').maxLength = 11;
                                            }
                                        }
                                    }, {
                                        xtype: 'numberfield',
                                        flex: 1,
                                        width: 115,
                                        name: 'disk_kons1',
                                        id: 'hb_disk_kons1',
                                        value: '0',
                                        style: 'text-align:right;'

                                    }]
                            }, {
                                xtype: 'displayfield',
                                value: 'Disk Supermarket 2',
                                width: 120
                            }, {
                                xtype: 'compositefield',
                                msgTarget: 'side',
                                fieldLabel: 'Disk Supermarket 2',
                                width: 200,
                                items: [{
                                        width: 50,
                                        xtype: 'combo',
                                        mode: 'local',
                                        value: '%',
                                        triggerAction: 'all',
                                        forceSelection: true,
                                        name: 'disk_kons2_op',
                                        id: 'hb_disk_kons2_op',
                                        hiddenName: 'disk_kons2_op',
                                        displayField: 'name',
                                        valueField: 'value',
                                        store: new Ext.data.JsonStore({
                                            fields: ['name', 'value'],
                                            data: [
                                                {name: '%', value: '%'},
                                                {name: 'Rp', value: 'Rp'},
                                            ]
                                        }),
                                        listeners: {
                                            select: function() {
                                                Ext.getCmp('mb_disk_kons2').setMaxValue(Number.MAX_VALUE);
                                                if (this.getValue() == '%')
                                                    Ext.getCmp('mb_disk_kons2').maxValue = 100;
                                                else
                                                    Ext.getCmp('mb_disk_kons2').maxLength = 11;
                                            }
                                        }
                                    }, {
                                        xtype: 'numberfield',
                                        flex: 1,
                                        width: 115,
                                        name: 'disk_kons2',
                                        value: '0',
                                        id: 'hb_disk_kons2',
                                        style: 'text-align:right;'

                                    }]

                            }]
                    }, {
                        xtype: 'compositefield',
                        msgTarget: 'side',
                        fieldLabel: 'Disk Supermarket 3',
                        items: [{
                                xtype: 'compositefield',
                                msgTarget: 'side',
                                fieldLabel: 'Disk Konsumen 3',
                                width: 200,
                                items: [{
                                        width: 50,
                                        xtype: 'combo',
                                        mode: 'local',
                                        value: '%',
                                        triggerAction: 'all',
                                        forceSelection: true,
                                        name: 'disk_kons3_op',
                                        id: 'hb_disk_kons3_op',
                                        hiddenName: 'disk_kons3_op',
                                        displayField: 'name',
                                        valueField: 'value',
                                        store: new Ext.data.JsonStore({
                                            fields: ['name', 'value'],
                                            data: [
                                                {name: '%', value: '%'},
                                                {name: 'Rp', value: 'Rp'},
                                            ]
                                        }),
                                        listeners: {
                                            select: function() {
                                                Ext.getCmp('hb_disk_kons3').setMaxValue(Number.MAX_VALUE);
                                                if (this.getValue() == '%')
                                                    Ext.getCmp('hb_disk_kons3').maxValue = 100;
                                                else
                                                    Ext.getCmp('hb_disk_kons3').maxLength = 11;
                                            }
                                        }
                                    }, {
                                        xtype: 'numberfield',
                                        flex: 1,
                                        width: 115,
                                        name: 'disk_kons3',
                                        value: '0',
                                        id: 'hb_disk_kons3',
                                        style: 'text-align:right;'

                                    }]

                            }, {
                                xtype: 'displayfield',
                                value: 'Disk Supermarket 4',
                                width: 120
                            }, {
                                xtype: 'compositefield',
                                msgTarget: 'side',
                                fieldLabel: 'Disk Konsumen 4',
                                width: 200,
                                items: [{
                                        width: 50,
                                        xtype: 'combo',
                                        mode: 'local',
                                        value: '%',
                                        triggerAction: 'all',
                                        forceSelection: true,
                                        name: 'disk_kons4_op',
                                        id: 'hb_disk_kons4_op',
                                        hiddenName: 'disk_kons4_op',
                                        displayField: 'name',
                                        valueField: 'value',
                                        store: new Ext.data.JsonStore({
                                            fields: ['name', 'value'],
                                            data: [
                                                {name: '%', value: '%'},
                                                {name: 'Rp', value: 'Rp'},
                                            ]
                                        }),
                                        listeners: {
                                            select: function() {
                                                Ext.getCmp('hb_disk_kons4').setMaxValue(Number.MAX_VALUE);
                                                if (this.getValue() === '%')
                                                    Ext.getCmp('hb_disk_kons4').maxValue = 100;
                                                else
                                                    Ext.getCmp('hb_disk_kons4').maxLength = 11;
                                            }
                                        }
                                    }, {
                                        xtype: 'numberfield',
                                        flex: 1,
                                        width: 115,
                                        name: 'disk_kons4',
                                        value: '0',
                                        id: 'hb_disk_kons4',
                                        style: 'text-align:right;'

                                    }]

                            }
                        ]
                    }, {
                        xtype: 'compositefield',
                        msgTarget: 'side',
                        fieldLabel: 'Disk Supermarket 5',
                        items: [{
                                xtype: 'numberfield',
                                currencySymbol: '',
                                width: 170,
                                name: 'disk_kons5',
                                value: '0',
                                id: 'hb_disk_kons5',
                                style: 'text-align:right;'

                            }
                        ]
                    }],
                buttons: [{
                        text: 'Apply All',
                        formBind: true,
                        handler: function() {
                            var kd_supplier = Ext.getCmp('id_cbhpsuplier').getValue();
                            if (!kd_supplier) {
                                Ext.Msg.show({
                                    title: 'Error',
                                    msg: 'Silahkan Pilih Supplier Terlebih Dahulu',
                                    modal: true,
                                    icon: Ext.Msg.ERROR,
                                    buttons: Ext.Msg.OK
                                });
                                return;
                            }

                            strhargapembelian.each(function(record) {

                                record.set('disk_supp1_op', Ext.getCmp('hb_disk_kons1_op').getValue());
                                record.set('disk_supp1', Ext.getCmp('hb_disk_kons1').getValue());
                                record.set('disk_supp2_op', Ext.getCmp('hb_disk_kons2_op').getValue());
                                record.set('disk_supp2', Ext.getCmp('hb_disk_kons2').getValue());
                                record.set('disk_supp3_op', Ext.getCmp('hb_disk_kons3_op').getValue());
                                record.set('disk_supp3', Ext.getCmp('hb_disk_kons3').getValue());
                                record.set('disk_supp4_op', Ext.getCmp('hb_disk_kons4_op').getValue());
                                record.set('disk_supp4', Ext.getCmp('hb_disk_kons4').getValue());
                                record.set('disk_amt_supp5', Ext.getCmp('hb_disk_kons5').getValue());

                                record.commit();

                                record.set('edited', 'Y');

                                var total_disk = 0;
                                var rp_hrga_supplier = record.get('hrg_supplier');
                                var disk_supp1_op = record.get('disk_supp1_op');
                                var disk_supp1 = record.get('disk_supp1');
                                if (disk_supp1_op == '%') {
                                    total_disk = rp_hrga_supplier - (rp_hrga_supplier * (disk_supp1 / 100));
                                } else {
                                    total_disk = rp_hrga_supplier - disk_supp1;
                                }

                                var disk_supp2_op = record.get('disk_supp2_op');
                                var disk_supp2 = record.get('disk_supp2');
                                if (disk_supp2_op == '%') {
                                    total_disk = total_disk - (total_disk * (disk_supp2 / 100));
                                } else {
                                    total_disk = total_disk - disk_supp2;
                                }

                                var disk_supp3_op = record.get('disk_supp3_op');
                                var disk_supp3 = record.get('disk_supp3');
                                if (disk_supp3_op == '%') {
                                    total_disk = total_disk - (total_disk * (disk_supp3 / 100));
                                } else {
                                    total_disk = total_disk - disk_supp3;
                                }

                                var disk_supp4_op = record.get('disk_supp4_op');
                                var disk_supp4 = record.get('disk_supp4');
                                if (disk_supp4_op == '%') {
                                    total_disk = total_disk - (total_disk * (disk_supp4 / 100));
                                } else {
                                    total_disk = total_disk - disk_supp4;
                                }

                                var total_disk = total_disk - record.get('disk_amt_supp5');

                                record.set('net_hrg_supplier_sup_inc', total_disk);
                                var harga_exc = (total_disk / (11 / 10));
                                record.set('net_hrg_supplier_sup', harga_exc);

                                record.commit();
                            });

                        }
                    }]
            },{	
                xtype:'fieldset',
                autoheight: true,
                title: 'Periode Diskon',
                collapsed: false,
                collapsible: true,
                anchor: '70%',
                items:[{
                        xtype : 'compositefield',
                        msgTarget: 'side',
                        fieldLabel: 'Efektif Date',
                        items : [ {
                                    xtype: 'datefield',
                                    fieldLabel: 'Efektif Date',
                                    name: 'tgl_start_diskon',				
                                    allowBlank:true,   
                                    format:'d-m-Y',  
                                    //editable:false,           
                                    id: 'hp_tgl_start_diskon',                
                                    width: 150,
                                    minValue: (new Date()).clearTime() 
                                }
                        ]
                    }],
                    buttons: [{
                        text: 'Apply All',
                        formBind: true,
                        handler: function(){
                            var kd_supplier =  Ext.getCmp('id_cbhpsuplier').getValue();
                            if(!kd_supplier){
                                Ext.Msg.show({
                                    title: 'Error',
                                    msg: 'Silahkan Pilih Supplier Terlebih Dahulu',
                                    modal: true,
                                    icon: Ext.Msg.ERROR,
                                    buttons: Ext.Msg.OK
                                });
                                return;
                            }
                            
                            strhargapembelian.each(function(record){
                                    
                                record.set('tgl_start_diskon',Ext.getCmp('hp_tgl_start_diskon').getValue());
                               // record.set('tgl_end_diskon',Ext.getCmp('hpk_tgl_end_diskon').getValue());
                                
                                record.commit();
                                record.set('edited','Y');
                                record.commit();
                            });

                        }
                    }]
            },
            gridhargapembelian,
            {
                layout: 'column',
                border: false,
                items: [{
                        columnWidth: .6,
                        style: 'margin:6px 3px 0 0;',
                        layout: 'form',
                        labelWidth: 100,
                        items: [{
                                xtype: 'textarea',
                                fieldLabel: 'Ket. Perubahan <span class="asterix">*</span>',
                                name: 'keterangan',
                                allowBlank: false,
                                id: 'ehp_keterangan',
                                width: 300,
                            }]
                    }]
            }
        ],
        buttons: [{
                text: 'Save',
                formBind: true,
                handler: function() {

                    var detailhargapembelian = new Array();
                    strhargapembelian.each(function(node) {
                        detailhargapembelian.push(node.data)
                    });
                    Ext.getCmp('hargapembelian').getForm().submit({
                        url: '<?= site_url("harga_pembelian/update_row") ?>',
                        scope: this,
                        params: {
                            detail: Ext.util.JSON.encode(detailhargapembelian)
                        },
                        waitMsg: 'Saving Data...',
                        success: function(form, action) {
                            Ext.Msg.show({
                                title: 'Success',
                                msg: 'Form submitted successfully',
                                modal: true,
                                icon: Ext.Msg.INFO,
                                buttons: Ext.Msg.OK
                            });
                            Ext.MessageBox.getDialog().getEl().setStyle('z-index', '80000');

                            clearhargapembelian();
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
                    clearhargapembelian();
                }
            }]
    });

    hargapembelian.on('afterrender', function() {
        this.getForm().load({
            url: '<?= site_url("harga_pembelian/get_form") ?>',
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

    function clearhargapembelian() {
        Ext.getCmp('hargapembelian').getForm().reset();
        Ext.getCmp('hargapembelian').getForm().load({
            url: '<?= site_url("harga_pembelian/get_form") ?>',
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
        strhargapembelian.removeAll();
    }
</script>
