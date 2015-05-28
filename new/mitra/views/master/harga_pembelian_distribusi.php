<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
/* START HISTORY */
    var strhargapembeliandisthistory = new Ext.data.Store({
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
                {name: 'hrg_supplier_dist', allowBlank: false, type: 'text'},
                {name: 'rp_het_harga_beli_dist', allowBlank: false, type: 'int'}
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("harga_pembelian/search_produk_history_dist") ?>',
            method: 'POST'
        }),
        writer: new Ext.data.JsonWriter(
                {
                    encode: true,
                    writeAllFields: true
                })
    });

    var gridhargapembeliandisthistory = new Ext.grid.GridPanel({
        store: strhargapembeliandisthistory,
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
                dataIndex: 'hrg_supplier_dist',
                width: 100
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'HET BELI',
                dataIndex: 'rp_het_harga_beli_dist',
                width: 80
            }, {
                header: '% / Rp',
                dataIndex: 'disk_dist1_op',
                width: 50
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                // format: '0,0',
                header: 'Diskon Distribusi 1',
                dataIndex: 'disk_dist1',
                width: 150
            }, {
                header: '% / Rp',
                dataIndex: 'disk_dist2_op',
                width: 50
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                // format: '0,0',
                header: 'Diskon Distribusi 2',
                dataIndex: 'disk_dist2',
                width: 150
            }, {
                header: '% / Rp',
                dataIndex: 'disk_dist3_op',
                width: 50
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                // format: '0,0',
                header: 'Diskon Distribusi 3',
                dataIndex: 'disk_dist3',
                width: 150
            }, {
                header: '% / Rp',
                dataIndex: 'disk_dist4_op',
                width: 50
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                // format: '0,0',
                header: 'Diskon Distribusi 4',
                dataIndex: 'disk_dist4',
                width: 150
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Diskon Distribusi 5',
                dataIndex: 'disk_amt_dist5',
                width: 150
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Net Price Distribusi(Inc.PPN)',
                dataIndex: 'net_hrg_supplier_dist_inc',
                width: 190
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Net Price Distribusi(Exc.PPN)',
                dataIndex: 'net_hrg_supplier_dist',
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

    var winhargapembeliandistprint = new Ext.Window({
        id: 'id_winhargapembeliandistprint',
        title: 'Print History Harga Pembelian Distribusi',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html: '<iframe style="width:100%;height:100%;" id="hargapembeliandistprint" src=""></iframe>'
    });

    Ext.ns('hargapembeliandistform');
    hargapembeliandistform.Form = Ext.extend(Ext.form.FormPanel, {
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
            hargapembeliandistform.Form.superclass.constructor.call(this, config);
        },
        initComponent: function() {

            // hard coded - cannot be changed from outsid
            var config = {
                layout: 'form',
                items: [gridhargapembeliandisthistory],
                buttons: [{
                        text: 'Cetak',
                        id: 'btnCetakhargapembeliandist',
                        scope: this,
                        handler: function() {
                            function isEmpty(str) {
                                return (!str || 0 === str.length);
                            }
                            var no_bukti = Ext.getCmp('id_cbhpnobukti_dist').getValue();
                            var kd_produk = Ext.getCmp('id_cbhpproduk_dist').getValue();

                            if (isEmpty(no_bukti)) {
                                no_bukti = 0;
                            }
                            winhargapembeliandistprint.show();
                            Ext.getDom('hargapembeliandistprint').src = '<?= site_url("harga_pembelian/print_form_dist") ?>' + '/' + no_bukti + '/' + kd_produk;
                        }
                    }, {
                        text: 'Close',
                        id: 'btnClosehargapembelian_dist',
                        scope: this,
                        handler: function() {
                            winshowhistoryhargapembelian_dist.hide();
                        }
                    }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));

            // call parent
            hargapembeliandistform.Form.superclass.initComponent.apply(this, arguments);

        } // eo function initComponent  
        ,
        onRender: function() {

            // call parent
            hargapembeliandistform.Form.superclass.onRender.apply(this, arguments);

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
    Ext.reg('formaddhargapembelian_dist', hargapembeliandistform.Form);

    var winshowhistoryhargapembelian_dist = new Ext.Window({
        id: 'id_winshowhistoryhargapembelian_dist',
        closeAction: 'hide',
        width: 1000,
        height: 500,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_formaddhargapembelian_dist',
            xtype: 'formaddhargapembelian_dist'
        },
        onHide: function() {
            Ext.getCmp('id_formaddhargapembelian_dist').getForm().reset();
        }
    });

    var strcbhpnobukti_dist = new Ext.data.ArrayStore({
        fields: ['no_bukti', 'keterangan'],
        data: []
    });

    var strgridhpnobukti_dist = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_bukti', 'keterangan', 'created_by', 'nama_supplier'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("harga_pembelian/search_no_bukti_dist") ?>',
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

    var searchgridhpnobukti_dist = new Ext.app.SearchField({
        store: strgridhpnobukti_dist,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridhpnobukti_dist'
    });


    var gridhpnobukti_dist = new Ext.grid.GridPanel({
        store: strgridhpnobukti_dist,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'No Bukti',
                dataIndex: 'no_bukti',
                width: 100,
                sortable: true
            }, {
                header: 'Nama Supplier',
                dataIndex: 'nama_supplier',
                width: 125,
                sortable: true
            }, {
                header: 'Request By',
                dataIndex: 'created_by',
                width: 100,
                sortable: true
            }, {
                header: 'Ket. Perubahan',
                dataIndex: 'keterangan',
                width: 200,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridhpnobukti_dist]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridhpnobukti_dist,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cbhpnobukti_dist').setValue(sel[0].get('no_bukti'));

                    menuhpnobukti_dist.hide();
                }
            }
        }
    });

    var menuhpnobukti_dist = new Ext.menu.Menu();
    menuhpnobukti_dist.add(new Ext.Panel({
        title: 'Pilih No Bukti',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 500,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridhpnobukti_dist],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menuhpnobukti_dist.hide();
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
            strgridhpnobukti_dist.load();
            menuhpnobukti_dist.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menuhpnobukti_dist.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridhpnobukti_dist').getValue();
        if (sf !== '') {
            Ext.getCmp('id_searchgridhpnobukti_dist').setValue('');
            searchgridhpnobukti_dist.onTrigger2Click();
        }
    });

    var cbhpnobukti_dist = new Ext.ux.TwinCombohpnobukti({
        fieldLabel: 'No Bukti <span class="asterix">*</span>',
        id: 'id_cbhpnobukti_dist',
        store: strcbhpnobukti_dist,
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
// Combo Supplier
    var strcbhpsuplier_dist = new Ext.data.ArrayStore({
        fields: ['kd_supplier'],
        data : []
    });
	
    var strgridhpsuplier_dist = new Ext.data.Store({
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
			
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
	
    var searchgridhpsuplier_dist = new Ext.app.SearchField({
        store: strgridhpsuplier_dist,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridhpsuplier_dist'
    });
	
	strgridhpsuplier_dist.on('load', function(){
		 Ext.getCmp('id_searchgridhpsuplier_dist').focus();
	});
	
    var gridhpsuplier_dist = new Ext.grid.GridPanel({
        store: strgridhpsuplier_dist,
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
                width: 200,
                sortable: true         
            },{
                header: 'PKP',
                dataIndex: 'pkp',
                width: 100,
                sortable: true         
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridhpsuplier_dist]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridhpsuplier_dist,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {	
                    Ext.getCmp('id_cbhpsuplier_dist').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('hpd_nama_supplier').setValue(sel[0].get('nama_supplier'));
                    if(sel[0].get('pkp') === '1'){
                        Ext.getCmp('hpd_pkp').setValue('YA');
                    }else{
                        Ext.getCmp('hpd_pkp').setValue('TIDAK');
                    }
					
                    strhargapembeliandistribusi.removeAll();
					          
                    menuhpsuplier_dist.hide();
                }
            }
        }
    });
	
    var menuhpsuplier_dist = new Ext.menu.Menu();
    menuhpsuplier_dist.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridhpsuplier_dist],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menuhpsuplier_dist.hide();
                }
            }]
    }));
    
    Ext.ux.TwinCombohpSuplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgridhpsuplier_dist.load();
            menuhpsuplier_dist.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menuhpsuplier_dist.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridhpsuplier_dist').getValue();
        if( sf !== ''){
            Ext.getCmp('id_searchgridhpsuplier_dist').setValue('');
            searchgridhpsuplier_dist.onTrigger2Click();
        }
    });
	
    var cbhpsuplier_dist = new Ext.ux.TwinCombohpSuplier({
        fieldLabel: 'Supplier <span class="asterix">*</span>',
        id: 'id_cbhpsuplier_dist',
        store: strcbhpsuplier_dist,
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
   /*START TWIN NO BUKTI FILTER*/
	
    var strcbhpnobuktifilter_dist = new Ext.data.ArrayStore({
        fields: ['no_bukti','keterangan'],
        data : []
    });
	
    var strgridhpnobuktifilter_dist = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_bukti_filter','keterangan','created_by','nama_supplier'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("harga_pembelian/get_no_bukti_filter_dist") ?>',
            method: 'POST'
        }),
        listeners: {
			
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
	
    var searchgridhpnobuktifilter_dist = new Ext.app.SearchField({
        store: strgridhpnobuktifilter_dist,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridhpnobuktifilter_dist'
    });
	
	
    var gridhpnobuktifilter_dist = new Ext.grid.GridPanel({
        store: strgridhpnobuktifilter_dist,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'No Bukti',
                dataIndex: 'no_bukti_filter',
                width: 100,
                sortable: true			
            
            },{
                header: 'Nama Supplier',
                dataIndex: 'nama_supplier',
                width: 125,
                sortable: true			
            
            },{
                header: 'Request By',
                dataIndex: 'created_by',
                width: 100,
                sortable: true			
            
            },{
                header: 'Ket. Perubahan',
                dataIndex: 'keterangan',
                width: 200,
                sortable: true	
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridhpnobuktifilter_dist]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridhpnobuktifilter_dist,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {	
                    Ext.getCmp('id_cbhpnobuktifilter_dist').setValue(sel[0].get('no_bukti_filter'));
					        
                    menuhpnobuktifilter_dist.hide();
                }
            }
        }
    });
	
    var menuhpnobuktifilter_dist = new Ext.menu.Menu();
    menuhpnobuktifilter_dist.add(new Ext.Panel({
        title: 'Pilih No Bukti',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 500,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridhpnobuktifilter_dist],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menuhpnobuktifilter_dist.hide();
                }
            }]
    }));
    
    Ext.ux.TwinCombohpnobuktifilter = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgridhpnobuktifilter_dist.load();
            menuhpnobuktifilter_dist.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menuhpnobuktifilter_dist.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridhpnobuktifilter_dist').getValue();
        if( sf !== ''){
            Ext.getCmp('id_searchgridhpnobuktifilter_dist').setValue('');
            searchgridhpnobuktifilter_dist.onTrigger2Click();
        }
    });
	
    var cbhpnobuktifilter_dist = new Ext.ux.TwinCombohpnobuktifilter({
        fieldLabel: 'No Bukti Filter',
        id: 'id_cbhpnobuktifilter_dist',
        store: strcbhpnobuktifilter_dist,
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
    // COMBOBOX KATEGORI 1
    var str_hpd_cbkategori1 = new Ext.data.Store({
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
                var r = new (str_hpd_cbkategori1.recordType)({
                    'kd_kategori1': '',
                    'nama_kategori1': '-----'
                });
                str_hpd_cbkategori1.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var hpd_cbkategori1 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 1 ',
        id: 'mb_hpd_cbkategori1',
        store: str_hpd_cbkategori1,
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
                var kdhpd_cbkategori1 = hpd_cbkategori1.getValue();
                // hpd_cbkategori2.setValue();
                hpd_cbkategori2.store.proxy.conn.url = '<?= site_url("kategori3/get_kategori2") ?>/' + kdhpd_cbkategori1;
                hpd_cbkategori2.store.reload();            
            }
        }
    });
    // combobox kategori2
    var str_hpd_cbkategori2 = new Ext.data.Store({
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
                var r = new (str_hpd_cbkategori2.recordType)({
                    'kd_kategori2': '',
                    'nama_kategori2': '-----'
                });
                str_hpd_cbkategori2.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var hpd_cbkategori2 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 2 ',
        id: 'mb_hpd_cbkategori2',
        mode: 'local',
        store: str_hpd_cbkategori2,
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
                var kd_hpd_cbkategori1 = hpd_cbkategori1.getValue();
                var kd_hpd_cbkategori2 = this.getValue();
                hpd_cbkategori3.setValue();
                hpd_cbkategori3.store.proxy.conn.url = '<?= site_url("kategori4/get_kategori3") ?>/' + kd_hpd_cbkategori1 +'/'+ kd_hpd_cbkategori2;
                hpd_cbkategori3.store.reload();          
            }
        }
    });
	
    // combobox kategori3
    var str_hpd_cbkategori3 = new Ext.data.Store({
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
                var r = new (str_hpd_cbkategori3.recordType)({
                    'kd_kategori3': '',
                    'nama_kategori3': '-----'
                });
                str_hpd_cbkategori3.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
	
    var hpd_cbkategori3 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 3 ',
        id: 'mb_hpd_cbkategori3',
        mode: 'local',
        store: str_hpd_cbkategori3,
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
                var kd_hpd_cbkategori1 = hpd_cbkategori1.getValue();
                var kd_hpd_cbkategori2 = hpd_cbkategori2.getValue();
                var kd_hpd_cbkategori3 = this.getValue();
                hpd_cbkategori4.setValue();
                hpd_cbkategori4.store.proxy.conn.url = '<?= site_url("master_barang/get_kategori4") ?>/' + kd_hpd_cbkategori1 +'/'+ kd_hpd_cbkategori2 +'/'+ kd_hpd_cbkategori3;
                hpd_cbkategori4.store.reload();     
            }
        }
    });
	
    // combobox kategori4
    var str_hpd_cbkategori4 = new Ext.data.Store({
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
                var r = new (str_hpd_cbkategori4.recordType)({
                    'kd_kategori4': '',
                    'nama_kategori4': '-----'
                });
                str_hpd_cbkategori4.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var hpd_cbkategori4 = new Ext.form.ComboBox({   
        fieldLabel: 'Kategori 4',
        id: 'mb_hpd_cbkategori4',
        mode: 'local',
        store: str_hpd_cbkategori4,
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
	var str_hpd_cbukuran = new Ext.data.Store({
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
                var r = new (str_hpd_cbukuran.recordType)({
                    'kd_ukuran': '',
                    'nama_ukuran': '-----'
                });
                str_hpd_cbukuran.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
    
    var hpd_cbukuran = new Ext.form.ComboBox({
        fieldLabel: 'Ukuran ',
        id: 'id_hpd_cbukuran',
        store: str_hpd_cbukuran,
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
	var str_hpd_cbsatuan = new Ext.data.Store({
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
                var r = new (str_hpd_cbsatuan.recordType)({
                    'kd_satuan': '',
                    'nm_satuan': '-----'
                });
                str_hpd_cbsatuan.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
    var hpd_cbsatuan = new Ext.form.ComboBox({
        fieldLabel: 'Satuan',
        id: 'id_hpd_cbsatuan',
        store: str_hpd_cbsatuan,
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
    function Editedhp_dist(){
        Ext.getCmp('hpd_edited').setValue('Y');
    };
    function diskChange_dist(){
        Editedhp_dist();
        var pct_margin = Ext.getCmp('ehpd_pct_margin').getValue();
        var rp_ongkos_kirim = Ext.getCmp('ehpd_rp_ongkos_kirim').getValue();
			
        var hrg_supp = Ext.getCmp('ehpd_hrg_supplier').getValue();
        var rp_margin = (hrg_supp*pct_margin)/100;
        var het_harga_beli = parseInt(hrg_supp)+parseInt(rp_margin)+parseInt(rp_ongkos_kirim);
        Ext.getCmp('ehpd_het_harga_beli').setValue(parseInt(het_harga_beli));
			
        var total_disk = 0;
        var disk_dist1_op = Ext.getCmp('hpd_disk_dist1_op').getValue();
        var disk_dist1 = Ext.getCmp('hpd_disk_dist1').getValue();
        if (disk_dist1_op === '%'){
            // disk_dist1 = (disk_dist1*hrg_supp)/100;
            total_disk = hrg_supp-(hrg_supp*(disk_dist1/100));
        }else{
            total_disk = hrg_supp-disk_dist1;				
        }
			
        var disk_dist2_op = Ext.getCmp('hpd_disk_dist2_op').getValue();
        var disk_dist2 = Ext.getCmp('hpd_disk_dist2').getValue();
        if (disk_dist2_op === '%'){
            // disk_dist2 = (disk_dist2*disk_dist1)/100;
            total_disk = total_disk-(total_disk*(disk_dist2/100));
        }else{
            total_disk = total_disk-disk_dist2;				
        }
			
        var disk_dist3_op = Ext.getCmp('hpd_disk_dist3_op').getValue();
        var disk_dist3 = Ext.getCmp('hpd_disk_dist3').getValue();
        if (disk_dist3_op === '%'){
            // disk_dist3 = (disk_dist3*disk_dist2)/100;
            total_disk = total_disk-(total_disk*(disk_dist3/100));
        }else{
            total_disk = total_disk-disk_dist3;				
        }
			
        var disk_dist4_op = Ext.getCmp('hpd_disk_dist4_op').getValue();
        var disk_dist4 = Ext.getCmp('hpd_disk_dist4').getValue();
        if (disk_dist4_op === '%'){
            // disk_dist4 = (disk_dist4*disk_dist3)/100;
            total_disk = total_disk-(total_disk*(disk_dist4/100));
        }else{
            total_disk = total_disk-disk_dist4;				
        }
			
        var total_disk = total_disk - Ext.getCmp('hpd_disk_amt_dist5').getValue();

        var net_price_dist = total_disk;
        Ext.getCmp('ehpd_net_hrg_supplier_dist_inc').setValue(net_price_dist);
				
        if(Ext.getCmp('hpd_pkp').getValue() === 'YA'){
            Ext.getCmp('ehpd_net_hrg_supplier_dist').setValue(net_price_dist/1.1);
        }else{
            Ext.getCmp('ehpd_net_hrg_supplier_dist').setValue(net_price_dist);
        }
    };
// Header harga pembelian distribusi
    var headerhargapembeliandistribusi = {
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
                        id: 'hpd_no_hp',
                        anchor: '90%',
                        value: ''
                    },
                   cbhpsuplier_dist, cbhpnobuktifilter_dist, hpd_cbkategori1, hpd_cbkategori2,
                    {
                        xtype: 'textarea',
                        style: 'text-transform: uppercase',
                        fieldLabel: 'Kode Barang, Kode Barang Lama',
                        name: 'list',
                        id: 'ehpd_list',
                        anchor: '90%'
                    }, {
                        xtype: 'label',
                        text: '*) Tidak Boleh Ada Spasi dan Enter',
                        style: 'margin-left:100px'
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
                        id: 'hpd_tanggal',
                        anchor: '90%',
                        value: ''
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'Nama Supplier',
                        name: 'nama_supplier',
                        readOnly: true,
                        fieldClass: 'readonly-input',
                        id: 'hpd_nama_supplier',
                        anchor: '90%',
                        value: ''
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'Status PKP',
                        name: 'pkp',
                        readOnly: true,
                        fieldClass: 'readonly-input',
                        id: 'hpd_pkp',
                        anchor: '90%',
                        value: ''
                    },
                     hpd_cbkategori3, hpd_cbkategori4, hpd_cbukuran, hpd_cbsatuan
                ]
            }],
        buttons: [{
                text: 'Filter',
                formBind: true,
                handler: function() {
                    var kd_supplier = Ext.getCmp('id_cbhpsuplier_dist').getValue();
                    if (!kd_supplier) {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Silahkan Pilih Supplier Terlebih Dahulu',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                            // fn: function(btn){
                            // if (btn == 'ok' && msg == 'Session Expired') {
                            // window.location = '<?= site_url("auth/login") ?>';
                            // }
                            // }
                        });
                        return;                   strhargapembeliandistribusi.load({
                        params: {
                            start: STARTPAGE,
                            limit: ENDPAGE,
                            kd_supplier: Ext.getCmp('id_cbhpsuplier_dist').getValue(),
                            kd_kategori1: Ext.getCmp('mb_hpd_cbkategori1').getValue(),
                            kd_kategori2: Ext.getCmp('mb_hpd_cbkategori2').getValue(),
                            kd_kategori3: Ext.getCmp('mb_hpd_cbkategori3').getValue(),
                            kd_kategori4: Ext.getCmp('mb_hpd_cbkategori4').getValue(),
                            kd_ukuran: Ext.getCmp('id_hpd_cbukuran').getValue(),
                            kd_satuan: Ext.getCmp('id_hpd_cbsatuan').getValue(),
                            no_bukti: Ext.getCmp('id_cbhpnobuktifilter_dist').getValue(),
                            list: Ext.getCmp('ehpd_list').getValue()
                        }
                    });
                    }
                    strhargapembeliandistribusi.load({
                        params: {
                            start: STARTPAGE,
                            limit: ENDPAGE,
                            kd_supplier: Ext.getCmp('id_cbhpsuplier_dist').getValue(),
                            kd_kategori1: Ext.getCmp('mb_hpd_cbkategori1').getValue(),
                            kd_kategori2: Ext.getCmp('mb_hpd_cbkategori2').getValue(),
                            kd_kategori3: Ext.getCmp('mb_hpd_cbkategori3').getValue(),
                            kd_kategori4: Ext.getCmp('mb_hpd_cbkategori4').getValue(),
                            kd_ukuran: Ext.getCmp('id_hpd_cbukuran').getValue(),
                            kd_satuan: Ext.getCmp('id_hpd_cbsatuan').getValue(),
                            no_bukti: Ext.getCmp('id_cbhpnobuktifilter_dist').getValue(),
                            list: Ext.getCmp('ehpd_list').getValue()
                        }
                    });
                }
            }]
    };
     /* START twin produk*/

    var strcbhpproduk_dist = new Ext.data.ArrayStore({
        fields: ['kd_produk', 'nama_produk'],
        data: []
    });

    var strgridhpproduk_dist = new Ext.data.Store({
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
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var searchgridhpproduk_dist = new Ext.app.SearchField({
        store: strgridhpproduk_dist,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridhpproduk_dist'
    });


    var gridhpproduk_dist = new Ext.grid.GridPanel({
        store: strgridhpproduk_dist,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'Kode Produk',
                dataIndex: 'kd_produk',
                width: 150,
                sortable: true
            }, {
                header: 'Nama Produk',
                dataIndex: 'nama_produk',
                width: 150,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridhpproduk_dist]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridhpproduk_dist,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cbhpproduk_dist').setValue(sel[0].get('kd_produk'));
                    menuhpproduk_dist.hide();
                }
            }
        }
    });

    var menuhpproduk_dist = new Ext.menu.Menu();
    menuhpproduk_dist.add(new Ext.Panel({
        title: 'Pilih Produk',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridhpproduk_dist],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menuhpproduk_dist.hide();
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
            var kd_supplier = Ext.getCmp('id_cbhpsuplier_dist').getValue();
            if (!kd_supplier) {
                Ext.Msg.show({
                    title: 'Error',
                    msg: 'Silahkan Pilih Supplier Terlebih Dahulu',
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK
                    // fn: function(btn){
                    // if (btn == 'ok' && msg == 'Session Expired') {
                    // window.location = '<?= site_url("auth/login") ?>';
                    // }
                    // }
                });
                return;
            }

            strgridhpproduk_dist.load({
                params: {
                    start: STARTPAGE,
                    limit: ENDPAGE,
                    kd_supplier: Ext.getCmp('id_cbhpsuplier_dist').getValue(),
                    kd_kategori1: Ext.getCmp('mb_hpd_cbkategori1').getValue(),
                    kd_kategori2: Ext.getCmp('mb_hpd_cbkategori2').getValue(),
                    kd_kategori3: Ext.getCmp('mb_hpd_cbkategori3').getValue(),
                    kd_kategori4: Ext.getCmp('mb_hpd_cbkategori4').getValue(),
                    no_bukti: Ext.getCmp('id_cbhpnobuktifilter_dist').getValue(),
                    list: Ext.getCmp('ehp_list').getValue()
                }
            });
            menuhpproduk_dist.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menuhpproduk_dist.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridhpproduk_dist').getValue();
        if (sf !== '') {
            Ext.getCmp('id_searchgridhpproduk_dist').setValue('');
            searchgridhpproduk_dist.onTrigger2Click();
        }
    });

    var cbhpproduk_dist = new Ext.ux.TwinCombohpproduk({
        id: 'id_cbhpproduk_dist',
        store: strcbhpproduk_dist,
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
    // Store harga pembelian distribusi
    var strhargapembeliandistribusi = new Ext.data.Store({
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
                {name: 'hrg_supplier_dist', allowBlank: false, type: 'int'},
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
                {name: 'is_konsinyasi', allowBlank: false, type: 'text'}
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

    strhargapembeliandistribusi.on('load', function() {
        strhargapembeliandistribusi.setBaseParam('kd_supplier', Ext.getCmp('id_cbhpsuplier_dist').getValue());
        strhargapembeliandistribusi.setBaseParam('kd_kategori1', Ext.getCmp('mb_hpd_cbkategori1').getValue());
        strhargapembeliandistribusi.setBaseParam('kd_kategori2', Ext.getCmp('mb_hpd_cbkategori2').getValue());
        strhargapembeliandistribusi.setBaseParam('kd_kategori3', Ext.getCmp('mb_hpd_cbkategori3').getValue());
        strhargapembeliandistribusi.setBaseParam('kd_kategori4', Ext.getCmp('mb_hpd_cbkategori4').getValue());
        strhargapembeliandistribusi.setBaseParam('no_bukti', Ext.getCmp('id_cbhpnobuktifilter_dist').getValue());
    });

    var searchgridhargapembeliandistribusi = new Ext.app.SearchField({
        store: strhargapembeliandistribusi,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridhargapembeliandistribusi',
        emptyText: 'Kode Barang, Kode Barang Lama, Nama Barang'
    });

    searchgridhargapembeliandistribusi.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';

            // Get the value of search field
            var kd_supplier = Ext.getCmp('id_cbhpsuplier_dist').getValue();
            var kd_kategori1 = Ext.getCmp('mb_hpd_cbkategori1').getValue();
            var kd_kategori2 = Ext.getCmp('mb_hpd_cbkategori2').getValue();
            var kd_kategori3 = Ext.getCmp('mb_hpd_cbkategori3').getValue();
            var kd_kategori4 = Ext.getCmp('mb_hpd_cbkategori4').getValue();
            var no_bukti = Ext.getCmp('id_cbhpnobuktifilter_dist').getValue();
            var list = Ext.getCmp('ehpd_list').getValue();

            if (!kd_supplier) {
                Ext.Msg.show({
                    title: 'Error',
                    msg: 'Silahkan Pilih Supplier Terlebih Dahulu',
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK
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
                list: list
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

    searchgridhargapembeliandistribusi.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }

        // Get the value of search field
        var kd_supplier = Ext.getCmp('id_cbhpsuplier_dist').getValue();
        var kd_kategori1 = Ext.getCmp('mb_hpd_cbkategori1').getValue();
        var kd_kategori2 = Ext.getCmp('mb_hpd_cbkategori2').getValue();
        var kd_kategori3 = Ext.getCmp('mb_hpd_cbkategori3').getValue();
        var kd_kategori4 = Ext.getCmp('mb_hpd_cbkategori4').getValue();
        var no_bukti = Ext.getCmp('id_cbhpnobuktifilter_dist').getValue();
        var list = Ext.getCmp('ehpd_list').getValue();

        if (!kd_supplier) {
            Ext.Msg.show({
                title: 'Error',
                msg: 'Silahkan Pilih Supplier Terlebih Dahulu',
                modal: true,
                icon: Ext.Msg.ERROR,
                buttons: Ext.Msg.OK
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
            list: list
        };

        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params: o});
        this.hasSearch = true;
        this.triggers[0].show();
    };
    // GRID PANEL Harga Pembelian Distribusi
    var editorhargapembeliandistribusi = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });

    var gridhargapembeliandistribusi = new Ext.grid.GridPanel({
        store: strhargapembeliandistribusi,
        stripeRows: true,
        height: 350,
        loadMask: true,
        frame: true,
        border: true,
        plugins: [editorhargapembeliandistribusi],
        columns: [new Ext.grid.RowNumberer({width: 30}), {
                header: 'Kd Supplier',
                dataIndex: 'kd_supplier',
                width: 100,
                hidden: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'edit_hpd_kd_supplier'
                })
                        // },{
                        // header: 'Edited',
                        // dataIndex: 'edited',
                        // width: 50,
                        // sortable: true,
                        // editor: new Ext.form.TextField({
                        // readOnly: true,
                        // fieldClass:'readonly-input',
                        // id: 'hpd_edited'
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
                            {name: 'No'}
                        ]
                    }),
                    id: 'hpd_edited',
                    mode: 'local',
                    name: 'edited',
                    value: '%',
                    width: 50,
                    editable: false,
                    hiddenName: 'edited',
                    valueField: 'name',
                    displayField: 'name',
                    triggerAction: 'all',
                    forceSelection: true
                }
            }, {
                header: 'Kode Barang',
                dataIndex: 'kd_produk',
                width: 100,
                sortable: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'ehpd_kd_produk'
                })
            }, {
                header: 'Kode Barang Lama',
                dataIndex: 'kd_produk_lama',
                width: 110,
                sortable: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'ehpd_kd_produk_lama'
                })
            }, {
                header: 'Nama Barang',
                dataIndex: 'nama_produk',
                width: 300,
                sortable: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'ehpd_nama_produk'
                })
            }, {
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 80,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'ehpd_satuan'
                })
            }, {
                header: 'Nama Supplier',
                dataIndex: 'nama_supplier',
                width: 150,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'ehpd_nama_supplier'
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
                    id: 'ehpd_waktu_top',
                    listeners: {
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                Editedhp_dist();
                            }, c);
                        }
                    }
                })
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Harga Supplier',
                dataIndex: 'hrg_supplier_dist',
                width: 100,
                editor: {
                    xtype: 'numberfield',
                    id: 'ehpd_hrg_supplier',
                    // readOnly: true,
                    listeners: {
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                diskChange_dist();
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
                    id: 'ehpd_pct_margin'
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
                    id: 'ehpd_rp_ongkos_kirim'
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
                    id: 'ehpd_het_harga_beli',
                    readOnly: true,
                    fieldClass: 'readonly-input'
                    // listeners:{
                    // 'render': function(c) {
                    // c.getEl().on('keyup', function() {
                    // diskChange_dist();
                    // }, c);
                    // }
                    // }
                }
            }, 
                {
                header: '% / Rp',
                dataIndex: 'disk_dist1_op',
                width: 50,
                editor: {
                    xtype: 'combo',
                    store: new Ext.data.JsonStore({
                        fields: ['name'],
                        data: [
                            {name: '%'},
                            {name: 'Rp'}
                        ]
                    }),
                    id: 'hpd_disk_dist1_op',
                    mode: 'local',
                    name: 'disk_dist1_op',
                    value: '%',
                    width: 50,
                    editable: false,
                    hiddenName: 'disk_dist1_op',
                    valueField: 'name',
                    displayField: 'name',
                    triggerAction: 'all',
                    forceSelection: true,
                    listeners: {
                        'expand': function() {
                            Ext.getCmp('hpd_disk_dist1').setValue(0);
                        },
                        select: function() {
                            Ext.getCmp('hpd_disk_dist1').setMaxValue(Number.MAX_VALUE);
                            if (this.getValue() === 'persen')
                                Ext.getCmp('hpd_disk_dist1').maxValue = 100;
                            else
                                Ext.getCmp('hpd_disk_dist1').maxLength = 11;
                            Editedhp_dist();
                        }
                    }
                }
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                // format: '0,0',
                header: 'Diskon Distribusi 1',
                dataIndex: 'disk_dist1',
                width: 150,
                editor: {
                    xtype: 'numberfield',
                    msgTarget: 'under',
                    flex: 1,
                    width: 115,
                    name: 'disk_dist1',
                    id: 'hpd_disk_dist1',
                    style: 'text-align:right;',
                    listeners: {
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                diskChange_dist();
                            }, c);
                        }
                    }
                }
            }, {
                header: '% / Rp',
                dataIndex: 'disk_dist2_op',
                width: 50,
                editor: {
                    xtype: 'combo',
                    store: new Ext.data.JsonStore({
                        fields: ['name'],
                        data: [
                            {name: '%'},
                            {name: 'Rp'}
                        ]
                    }),
                    id: 'hpd_disk_dist2_op',
                    mode: 'local',
                    name: 'disk_dist2_op',
                    value: '%',
                    width: 50,
                    editable: false,
                    hiddenName: 'disk_dist2_op',
                    valueField: 'name',
                    displayField: 'name',
                    triggerAction: 'all',
                    forceSelection: true,
                    listeners: {
                        'expand': function() {
                            Ext.getCmp('hpd_disk_dist2').setValue(0);
                        },
                        select: function() {
                            Ext.getCmp('hpd_disk_dist2').setMaxValue(Number.MAX_VALUE);
                            if (this.getValue() === 'persen')
                                Ext.getCmp('hpd_disk_dist2').maxValue = 100;
                            else
                                Ext.getCmp('hpd_disk_dist2').maxLength = 11;
                            Editedhp_dist();
                        }
                    }
                }
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                // format: '0,0',
                header: 'Diskon Distribusi 2',
                dataIndex: 'disk_dist2',
                width: 150,
                editor: {
                    xtype: 'numberfield',
                    msgTarget: 'under',
                    flex: 1,
                    width: 115,
                    name: 'disk_dist2',
                    id: 'hpd_disk_dist2',
                    style: 'text-align:right;',
                    listeners: {
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                diskChange_dist();
                            }, c);
                        }
                    }
                }
            }, {
                header: '% / Rp',
                dataIndex: 'disk_dist3_op',
                width: 50,
                editor: {
                    xtype: 'combo',
                    store: new Ext.data.JsonStore({
                        fields: ['name'],
                        data: [
                            {name: '%'},
                            {name: 'Rp'}
                        ]
                    }),
                    id: 'hpd_disk_dist3_op',
                    mode: 'local',
                    name: 'disk_dist3_op',
                    value: '%',
                    width: 50,
                    editable: false,
                    hiddenName: 'disk_dist3_op',
                    valueField: 'name',
                    displayField: 'name',
                    triggerAction: 'all',
                    forceSelection: true,
                    listeners: {
                        'expand': function() {
                            Ext.getCmp('hpd_disk_dist3').setValue(0);
                        },
                        select: function() {
                            Ext.getCmp('hpd_disk_dist3').setMaxValue(Number.MAX_VALUE);
                            if (this.getValue() === 'persen')
                                Ext.getCmp('hpd_disk_dist3').maxValue = 100;
                            else
                                Ext.getCmp('hpd_disk_dist3').maxLength = 11;
                            Editedhp_dist();
                        }
                    }
                }
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                // format: '0,0',
                header: 'Diskon Distribusi 3',
                dataIndex: 'disk_dist3',
                width: 150,
                editor: {
                    xtype: 'numberfield',
                    msgTarget: 'under',
                    flex: 1,
                    width: 115,
                    name: 'disk_dist3',
                    id: 'hpd_disk_dist3',
                    style: 'text-align:right;',
                    listeners: {
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                diskChange_dist();
                            }, c);
                        }
                    }
                }
            }, {
                header: '% / Rp',
                dataIndex: 'disk_dist4_op',
                width: 50,
                editor: {
                    xtype: 'combo',
                    store: new Ext.data.JsonStore({
                        fields: ['name'],
                        data: [
                            {name: '%'},
                            {name: 'Rp'}
                        ]
                    }),
                    id: 'hpd_disk_dist4_op',
                    mode: 'local',
                    name: 'disk_dist4_op',
                    value: '%',
                    width: 50,
                    editable: false,
                    hiddenName: 'disk_dist4_op',
                    valueField: 'name',
                    displayField: 'name',
                    triggerAction: 'all',
                    forceSelection: true,
                    listeners: {
                        'expand': function() {
                            Ext.getCmp('hpd_disk_dist4').setValue(0);
                        },
                        select: function() {
                            Ext.getCmp('hpd_disk_dist4').setMaxValue(Number.MAX_VALUE);
                            if (this.getValue() === 'persen')
                                Ext.getCmp('hpd_disk_dist4').maxValue = 100;
                            else
                                Ext.getCmp('hpd_disk_dist4').maxLength = 11;
                            Editedhp_dist();
                        }
                    }
                }
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                // format: '0,0',
                header: 'Diskon Distribusi 4',
                dataIndex: 'disk_dist4',
                width: 150,
                editor: {
                    xtype: 'numberfield',
                    msgTarget: 'under',
                    flex: 1,
                    width: 115,
                    name: 'disk_dist4',
                    id: 'hpd_disk_dist4',
                    style: 'text-align:right;',
                    listeners: {
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                diskChange_dist();
                            }, c);
                        }
                    }
                }
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Diskon Distribusi 5',
                dataIndex: 'disk_amt_dist5',
                width: 150,
                editor: {
                    xtype: 'numberfield',
                    msgTarget: 'under',
                    flex: 1,
                    width: 115,
                    name: 'disk_amt_dist5',
                    id: 'hpd_disk_amt_dist5',
                    style: 'text-align:right;',
                    listeners: {
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                diskChange_dist();
                            }, c);
                        }
                    }
                }
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Net Price Distribusi(Inc.PPN)',
                dataIndex: 'net_hrg_supplier_dist_inc',
                width: 190,
                editor: {
                    xtype: 'numberfield',
                    id: 'ehpd_net_hrg_supplier_dist_inc',
                    readOnly: true,
                    fieldClass: 'readonly-input'
                    // style: 'text-align:right;'
                }
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Net Price Distribusi(Exc.PPN)',
                dataIndex: 'net_hrg_supplier_dist',
                width: 190,
                editor: {
                    xtype: 'numberfield',
                    id: 'ehpd_net_hrg_supplier_dist',
                    readOnly: true,
                    fieldClass: 'readonly-input'
                }
            }, {
                header: 'Is Konsinyasi',
                dataIndex: 'is_konsinyasi',
                width: 190


            }],
        tbar: new Ext.Toolbar({
            items: [searchgridhargapembeliandistribusi, '->', cbhpproduk_dist, '-', cbhpnobukti_dist, '-', {
                    text: 'Show History',
                    icon: BASE_ICONS + 'grid.png',
                    onClick: function() {
                        var kd_produk = Ext.getCmp('id_cbhpproduk_dist').getValue();
                        var no_bukti = Ext.getCmp('id_cbhpnobukti_dist').getValue();
                        if (kd_produk === '' && no_bukti === '') {
                            Ext.Msg.show({
                                title: 'Error',
                                msg: 'Silahkan Search Produk / No Bukti Terlebih Dulu',
                                modal: true,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OK
                            });
                            return;
                        }
                        gridhargapembeliandisthistory.store.load({
                            params: {
                                no_bukti: Ext.getCmp('id_cbhpnobukti_dist').getValue(),
                                kd_produk: Ext.getCmp('id_cbhpproduk_dist').getValue()
                            }
                        });
                        winshowhistoryhargapembelian_dist.setTitle('History');
                        winshowhistoryhargapembelian_dist.show();
                    }
                }, '-', {
                    text: 'Reset',
                    icon: BASE_ICONS + 'refresh.gif',
                    onClick: function() {
                        Ext.getCmp('id_cbhpnobukti_dist').setValue('');
                        Ext.getCmp('id_cbhpproduk_dist').setValue('');
                    }
                }]
        })
        // bbar: new Ext.PagingToolbar({
        // pageSize: ENDPAGE,
        // store: strhargapembeliandistribusi,
        // displayInfo: true
        // }),
    });
    // FORM PANEL
    var hargapembeliandistribusi = new Ext.FormPanel({
        id: 'hargapembeliandistribusi',
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
                items: [headerhargapembeliandistribusi]
            },
            {
                xtype: 'fieldset',
                autoheight: true,
                title: 'Diskon',
                collapsed: false,
                collapsible: true,
                anchor: '70%',
                items: [{
                        xtype: 'compositefield',
                        msgTarget: 'side',
                        fieldLabel: 'Disk Distribusi 1',
                        items: [{
                                xtype: 'compositefield',
                                msgTarget: 'side',
                                fieldLabel: 'Disk Distribusi 1',
                                width: 200,
                                items: [{
                                        xtype: 'combo',
                                        mode: 'local',
                                        value: '%',
                                        triggerAction: 'all',
                                        forceSelection: true,
                                        name: 'disk_kons1_op',
                                        id: 'hbd_disk_kons1_op',
                                        hiddenName: 'disk_kons1_op',
                                        displayField: 'name',
                                        valueField: 'value',
                                        width: 50,
                                        store: new Ext.data.JsonStore({
                                            fields: ['name', 'value'],
                                            data: [
                                                {name: '%', value: '%'},
                                                {name: 'Rp', value: 'Rp'}
                                            ]
                                        }),
                                        listeners: {
                                            select: function() {
                                                Ext.getCmp('hbd_disk_kons1').setMaxValue(Number.MAX_VALUE);
                                                if (this.getValue() === '%')
                                                    Ext.getCmp('hbd_disk_kons1').maxValue = 100;
                                                else
                                                    Ext.getCmp('hbd_disk_kons1').maxLength = 11;
                                            }
                                        }
                                    }, {
                                        xtype: 'numberfield',
                                        flex: 1,
                                        width: 115,
                                        name: 'disk_kons1',
                                        id: 'hbd_disk_kons1',
                                        value: '0',
                                        style: 'text-align:right;'

                                    }]
                            }, {
                                xtype: 'displayfield',
                                value: 'Disk Distribusi 2',
                                width: 120
                            }, {
                                xtype: 'compositefield',
                                msgTarget: 'side',
                                fieldLabel: 'Disk Distribusi 2',
                                width: 200,
                                items: [{
                                        width: 50,
                                        xtype: 'combo',
                                        mode: 'local',
                                        value: '%',
                                        triggerAction: 'all',
                                        forceSelection: true,
                                        name: 'disk_kons2_op',
                                        id: 'hbd_disk_kons2_op',
                                        hiddenName: 'disk_kons2_op',
                                        displayField: 'name',
                                        valueField: 'value',
                                        store: new Ext.data.JsonStore({
                                            fields: ['name', 'value'],
                                            data: [
                                                {name: '%', value: '%'},
                                                {name: 'Rp', value: 'Rp'}
                                            ]
                                        }),
                                        listeners: {
                                            select: function() {
                                                Ext.getCmp('hbd_disk_kons2').setMaxValue(Number.MAX_VALUE);
                                                if (this.getValue() === '%')
                                                    Ext.getCmp('hbd_disk_kons2').maxValue = 100;
                                                else
                                                    Ext.getCmp('hbd_disk_kons2').maxLength = 11;
                                            }
                                        }
                                    }, {
                                        xtype: 'numberfield',
                                        flex: 1,
                                        width: 115,
                                        name: 'disk_kons2',
                                        value: '0',
                                        id: 'hbd_disk_kons2',
                                        style: 'text-align:right;'

                                    }]

                            }]
                    }, {
                        xtype: 'compositefield',
                        msgTarget: 'side',
                        fieldLabel: 'Disk Distribusi 3',
                        items: [{
                                xtype: 'compositefield',
                                msgTarget: 'side',
                                fieldLabel: 'Disk Distribusi 3',
                                width: 200,
                                items: [{
                                        width: 50,
                                        xtype: 'combo',
                                        mode: 'local',
                                        value: '%',
                                        triggerAction: 'all',
                                        forceSelection: true,
                                        name: 'disk_kons3_op',
                                        id: 'hbd_disk_kons3_op',
                                        hiddenName: 'disk_kons3_op',
                                        displayField: 'name',
                                        valueField: 'value',
                                        store: new Ext.data.JsonStore({
                                            fields: ['name', 'value'],
                                            data: [
                                                {name: '%', value: '%'},
                                                {name: 'Rp', value: 'Rp'}
                                            ]
                                        }),
                                        listeners: {
                                            select: function() {
                                                Ext.getCmp('hbd_disk_kons3').setMaxValue(Number.MAX_VALUE);
                                                if (this.getValue() === '%')
                                                    Ext.getCmp('hbd_disk_kons3').maxValue = 100;
                                                else
                                                    Ext.getCmp('hbd_disk_kons3').maxLength = 11;
                                            }
                                        }
                                    }, {
                                        xtype: 'numberfield',
                                        flex: 1,
                                        width: 115,
                                        name: 'disk_kons3',
                                        value: '0',
                                        id: 'hbd_disk_kons3',
                                        style: 'text-align:right;'

                                    }]

                            }, {
                                xtype: 'displayfield',
                                value: 'Disk Distribusi 4',
                                width: 120
                            }, {
                                xtype: 'compositefield',
                                msgTarget: 'side',
                                fieldLabel: 'Disk Distribusi 4',
                                width: 200,
                                items: [{
                                        width: 50,
                                        xtype: 'combo',
                                        mode: 'local',
                                        value: '%',
                                        triggerAction: 'all',
                                        forceSelection: true,
                                        name: 'disk_kons4_op',
                                        id: 'hbd_disk_kons4_op',
                                        hiddenName: 'disk_kons4_op',
                                        displayField: 'name',
                                        valueField: 'value',
                                        store: new Ext.data.JsonStore({
                                            fields: ['name', 'value'],
                                            data: [
                                                {name: '%', value: '%'},
                                                {name: 'Rp', value: 'Rp'}
                                            ]
                                        }),
                                        listeners: {
                                            select: function() {
                                                Ext.getCmp('hbd_disk_kons4').setMaxValue(Number.MAX_VALUE);
                                                if (this.getValue() === '%')
                                                    Ext.getCmp('hbd_disk_kons4').maxValue = 100;
                                                else
                                                    Ext.getCmp('hbd_disk_kons4').maxLength = 11;
                                            }
                                        }
                                    }, {
                                        xtype: 'numberfield',
                                        flex: 1,
                                        width: 115,
                                        name: 'disk_kons4',
                                        value: '0',
                                        id: 'hbd_disk_kons4',
                                        style: 'text-align:right;'

                                    }]

                            }
                        ]
                    }, {
                        xtype: 'compositefield',
                        msgTarget: 'side',
                        fieldLabel: 'Disk Distribusi 5',
                        items: [{
                                xtype: 'numberfield',
                                currencySymbol: '',
                                width: 170,
                                name: 'disk_kons5',
                                value: '0',
                                id: 'hbd_disk_kons5',
                                style: 'text-align:right;'

                            }
                        ]
                    }],
                buttons: [{
                        text: 'Apply All',
                        formBind: true,
                        handler: function() {
                            var kd_supplier = Ext.getCmp('id_cbhpsuplier_dist').getValue();
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

                            strhargapembeliandistribusi.each(function(record) {

                                record.set('disk_dist1_op', Ext.getCmp('hbd_disk_kons1_op').getValue());
                                record.set('disk_dist1', Ext.getCmp('hbd_disk_kons1').getValue());
                                record.set('disk_dist2_op', Ext.getCmp('hbd_disk_kons2_op').getValue());
                                record.set('disk_dist2', Ext.getCmp('hbd_disk_kons2').getValue());
                                record.set('disk_dist3_op', Ext.getCmp('hbd_disk_kons3_op').getValue());
                                record.set('disk_dist3', Ext.getCmp('hbd_disk_kons3').getValue());
                                record.set('disk_dist4_op', Ext.getCmp('hbd_disk_kons4_op').getValue());
                                record.set('disk_dist4', Ext.getCmp('hbd_disk_kons4').getValue());
                                record.set('disk_amt_dist5', Ext.getCmp('hbd_disk_kons5').getValue());

                                record.commit();

                                record.set('edited', 'Y');

                                var total_disk = 0;
                                var rp_hrga_supplier = record.get('hrg_supplier_dist');
                                var disk_supp1_op = record.get('disk_dist1_op');
                                var disk_supp1 = record.get('disk_dist1');
                                if (disk_supp1_op === '%') {
                                    total_disk = rp_hrga_supplier - (rp_hrga_supplier * (disk_supp1 / 100));
                                } else {
                                    total_disk = rp_hrga_supplier - disk_supp1;
                                }

                                var disk_supp2_op = record.get('disk_dist2_op');
                                var disk_supp2 = record.get('disk_dist2');
                                if (disk_supp2_op === '%') {
                                    total_disk = total_disk - (total_disk * (disk_supp2 / 100));
                                } else {
                                    total_disk = total_disk - disk_supp2;
                                }

                                var disk_supp3_op = record.get('disk_dist3_op');
                                var disk_supp3 = record.get('disk_dist3');
                                if (disk_supp3_op === '%') {
                                    total_disk = total_disk - (total_disk * (disk_supp3 / 100));
                                } else {
                                    total_disk = total_disk - disk_supp3;
                                }

                                var disk_supp4_op = record.get('disk_dist4_op');
                                var disk_supp4 = record.get('disk_dist4');
                                if (disk_supp4_op === '%') {
                                    total_disk = total_disk - (total_disk * (disk_supp4 / 100));
                                } else {
                                    total_disk = total_disk - disk_supp4;
                                }

                                var total_disk = total_disk - record.get('disk_amt_dist5');

                                record.set('net_hrg_supplier_dist_inc', total_disk);
                                var harga_exc = (total_disk / (11 / 10));
                                record.set('net_hrg_supplier_dist', harga_exc);

                                record.commit();
                            });

                        }
                    }]
            },
            gridhargapembeliandistribusi,
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
                                id: 'ehpd_keterangan',
                                width: 300
                            }]
                    }]
            }
        ],
        buttons: [{
                text: 'Save',
                formBind: true,
                handler: function() {

                    var detailhargapembeliandistribusi = new Array();
                    strhargapembeliandistribusi.each(function(node) {
                        detailhargapembeliandistribusi.push(node.data)
                    });
                    Ext.getCmp('hargapembeliandistribusi').getForm().submit({
                        url: '<?= site_url("harga_pembelian/update_row_dist") ?>',
                        scope: this,
                        params: {
                            detail: Ext.util.JSON.encode(detailhargapembeliandistribusi)
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

                            clearhargapembeliandistribusi();
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
                    clearhargapembeliandistribusi();
                }
            }]
    });
    hargapembeliandistribusi.on('afterrender', function(){
        this.getForm().load({
            url: '<?= site_url("harga_pembelian/get_form") ?>',
            failure: function(form, action){
                var de = Ext.util.JSON.decode(action.response.responseText);
                Ext.Msg.show({
                    title: 'Error',
                    msg: de.errMsg,
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK,
                    fn: function(btn){
                        if (btn === 'ok' && de.errMsg === 'Session Expired') {
                            window.location = '<?= site_url("auth/login") ?>';
                        }
                    }
                });
            }
        });
    });
    function clearhargapembeliandistribusi(){
        Ext.getCmp('hargapembeliandistribusi').getForm().reset();
        Ext.getCmp('hargapembeliandistribusi').getForm().load({
            url: '<?= site_url("harga_pembelian/get_form") ?>',
            failure: function(form, action){
                var de = Ext.util.JSON.decode(action.response.responseText);
                Ext.Msg.show({
                    title: 'Error',
                    msg: de.errMsg,
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK,
                    fn: function(btn){
                        if (btn === 'ok' && de.errMsg === 'Session Expired') {
                            window.location = '<?= site_url("auth/login") ?>';
                        }
                    }
                });
            }
        });
        strhargapembeliandistribusi.removeAll();
    }
</script>