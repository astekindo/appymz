<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    // twin combo no sales order
    var strcb_retjual_salesorder = new Ext.data.ArrayStore({
        fields: ['no_so'],
        data : []
    });

    var strgrid_retjual_salesorder = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_so',
                      'no_bukti_pelanggan',
                      'tgl_so',
                      'rp_total',
                      'rp_total_bayar',
                      'rp_kurang_bayar',
                      'sisa_faktur',
                      'rp_retur',
                      'efektif_retur',
                      'rp_grand_total'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("penjualan_retur/get_all_faktur") ?>',
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

    var searchgrid_retjual_salesorder = new Ext.app.SearchField({
        store: strgrid_retjual_salesorder,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgrid_retjual_salesorder'
    });


    var grid_retjual_salesorder = new Ext.grid.GridPanel({
        store: strgrid_retjual_salesorder,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'No Sales Order',
                dataIndex: 'no_so',
                width: 130,
                sortable: true
            }, {
                header: 'Tanggal Sales Order',
                dataIndex: 'tgl_so',
                width: 120,
                sortable: true
            }, {
                xtype: 'numbercolumn',
                header: 'Rp Total Struk',
                dataIndex: 'rp_grand_total',
                width: 130,
                align: 'right',
                format: '0,0',
                sortable: true
            }, {
                xtype: 'numbercolumn',
                header: 'Rp Total Bayar',
                dataIndex: 'rp_total_bayar',
                width: 130,
                align: 'right',
                format: '0,0',
                sortable: true
            }, {
                xtype: 'numbercolumn',
                header: 'Rp Kurang Bayar',
                dataIndex: 'rp_kurang_bayar',
                width: 130,
                align: 'right',
                format: '0,0',
                sortable: true
            }, {
                xtype: 'numbercolumn',
                header: 'Rp Retur',
                dataIndex: 'rp_retur',
                width: 130,
                align: 'right',
                format: '0,0',
                sortable: true
            }, {
                xtype: 'numbercolumn',
                header: 'Efektif Retur',
                dataIndex: 'efektif_retur',
                width: 130,
                align: 'right',
                format: '0,0',
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchgrid_retjual_salesorder]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgrid_retjual_salesorder,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cbpjretsalesorder').setValue(sel[0].get('no_so'));
                    Ext.getCmp('id_pjret_tglso').setValue(sel[0].get('tgl_so'));
                    Ext.getCmp('id_pjret_total_so').setValue(sel[0].get('rp_grand_total'));
                    Ext.getCmp('id_pjret_efektif_retur').setValue(sel[0].get('efektif_retur'));
                    Ext.getCmp('id_pjret_rp_retur').setValue(sel[0].get('rp_retur'));
                    Ext.getCmp('id_pjret_total_bayar').setValue(sel[0].get('rp_total_bayar'));
                    Ext.getCmp('id_pjret_kurang_bayar').setValue(sel[0].get('rp_kurang_bayar'));

                    strpenjualanretur.removeAll();
                    menu_retjual_salesorder.hide();

                    var no_so = Ext.getCmp('id_cbpjretsalesorder').getValue();
                    console.log(no_so);
                    var urlgetdetailbarangbysales = '<?= site_url("penjualan_retur/search_produk_by_salesorder") ?>/' + no_so;
                    gridreturpenjualan.store.proxy = new Ext.data.HttpProxy({
                        url: urlgetdetailbarangbysales,
                        method: 'POST'
                    });
                    gridreturpenjualan.store.load();

                    Ext.Ajax.request({
                        url: '<?= site_url("penjualan_retur/search_produk_by_salesorder") ?>/' + no_so,
                        method: 'POST',
                        callback:function(opt,success,responseObj){
                            var de = Ext.util.JSON.decode(responseObj.responseText);

                            // Ext.getCmp('jumlah_returjual').setValue(sel[0].get('rp_total1'));
                            // Ext.getCmp('diskon_returjual').setValue(sel[0].get('rp_diskon'));
                            //Ext.getCmp('diskon_ekstra_returjual').setValue(sel[0].get('rp_diskon_tambahan'));
                            // Ext.getCmp('jumlah_returjual').setValue(sel[0].get('rp_grand_total'));

                        }
                    });
                    gridbonus.store.load();

                    Ext.Ajax.request({
                        url: '<?= site_url("penjualan_retur/search_produk_bonus_by_so") ?>/' + no_so,
                        method: 'POST',
                        callback:function(opt,success,responseObj){
                            var de = Ext.util.JSON.decode(responseObj.responseText);
                        }
                    });

                }
            }
        }
    });

    var menu_retjual_salesorder = new Ext.menu.Menu();
    menu_retjual_salesorder.add(new Ext.Panel({
        title: 'Pilih No Sales Order',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 700,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [grid_retjual_salesorder],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menu_retjual_salesorder.hide();
                }
            }]
    }));

    Ext.ux.TwinComboReturJualSalesOrder = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgrid_retjual_salesorder.load();
            menu_retjual_salesorder.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menu_retjual_salesorder.on('hide', function(){
        var sf = Ext.getCmp('id_searchgrid_retjual_salesorder').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgrid_retjual_salesorder').setValue('');
            searchgrid_retjual_salesorder.onTrigger2Click();
        }
    });

    var mask =new Ext.LoadMask(Ext.getBody(),{msg:'Loading data...', store: strpenjualanretur});

    var cbpjretsalesorder = new Ext.ux.TwinComboReturJualSalesOrder({
        fieldLabel: 'No SO/Struk <span class="asterix">*</span>',
        id: 'id_cbpjretsalesorder',
        store: strcb_retjual_salesorder,
        mode: 'local',
        valueField: 'no_so',
        displayField: 'no_so',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'no_so',
        emptyText: 'Pilih Sales Order'

    });

    var headerpenjualanretur = {
        layout: 'column',
        border: false,
        items: [{
            columnWidth: .5,
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: { labelSeparator: ''},
            items: [{
                xtype: 'textfield',
                fieldLabel: 'No.Retur',
                name: 'no_retur',
                allowBlank: true,
                readOnly:true,
                fieldClass:'readonly-input',
                id: 'id_pjret_no_retur',
                maxLength: 255,
                anchor: '90%',
                value:''
            }, {
                xtype: 'datefield',
                fieldLabel: 'Tanggal <span class="asterix">*</span>',
                name: 'tgl_retur',
                id:'id_pjret_tglretur',
                allowBlank:false,
                format:'d-M-Y',
                editable:false,
                anchor: '90%'
            },cbpjretsalesorder,
            {
                xtype: 'datefield',
                fieldLabel: 'Tanggal SO',
                name: 'tgl_so',
                id:'id_pjret_tglso',
                allowBlank:false,
                fieldClass:'readonly-input',
                format:'d-M-Y',
                editable:false,
                anchor: '90%'
            }, {
                xtype: 'textfield',
                fieldLabel: 'Tanggal SO/Struk',
                name: 'tgl_struk',
                id:'id_pjret_tglso',
                allowBlank:false,
                format:'d-M-Y',
                editable:false,
                anchor: '90%',
                fieldClass:'readonly-input'
            }]
        }, {
            columnWidth: .5,
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: { labelSeparator: ''},
            items: [{
                xtype: 'numericfield',
                currencySymbol: '',
                fieldLabel: 'Total SO/Struk',
                name: 'total_struk',
                id:'id_pjret_total_so',
                allowBlank:false,
                editable:false,
                anchor: '50%',
                fieldClass:'readonly-input'
            }, {
                xtype: 'numericfield',
                currencySymbol: '',
                fieldLabel: 'Total Bayar ',
                name: 'total_bayar',
                id:'id_pjret_total_bayar',
                allowBlank:false,
                editable:false,
                anchor: '50%',
                fieldClass:'readonly-input'
            }, {
                xtype: 'numericfield',
                currencySymbol: '',
                fieldLabel: 'Kurang Bayar ',
                name: 'rp_kurang_bayar',
                id:'id_pjret_kurang_bayar',
                allowBlank:false,
                editable:false,
                anchor: '50%',
                fieldClass:'readonly-input'
            }, {
                xtype: 'numericfield',
                currencySymbol: '',
                fieldLabel: 'Rp. Retur ',
                name: 'rp_retur',
                id:'id_pjret_rp_retur',
                allowBlank:false,
                editable:false,
                anchor: '50%',
                fieldClass:'readonly-input'
            }, {
                xtype: 'numericfield',
                currencySymbol: '',
                fieldLabel: 'Efektif Retur',
                name: 'efektif_retur',
                id:'id_pjret_efektif_retur',
                allowBlank:false,
                editable:false,
                anchor: '50%',
                fieldClass:'readonly-input'
            }]
        }]
    };


    var strpenjualanretur = new Ext.data.Store({
        autoSave:false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'no_so', allowBlank: false, type: 'text'},
                {name: 'kd_produk', allowBlank: false, type: 'text'},
                {name: 'kd_produk_supp', allowBlank: false, type: 'text'},
                {name: 'nama_produk', allowBlank: false, type: 'text'},
                {name: 'nm_satuan', allowBlank: false, type: 'text'},
                {name: 'qty_so', allowBlank: false, type: 'int'},
                {name: 'qty_retur', allowBlank: false, type: 'int'},
                {name: 'qty_do', allowBlank: false, type: 'int'},
                {name: 'qty_sj', allowBlank: false, type: 'int'},
                {name: 'qty_input', allowBlank: false, type: 'int'},
                {name: 'retur_so', allowBlank: false, type: 'int'},
                {name: 'qty_retur_so', allowBlank: false, type: 'int'},
                {name: 'qty_retur_do', allowBlank: false, type: 'int'},
                {name: 'rp_harga', allowBlank: false, type: 'int'},
                {name: 'rp_diskon', allowBlank: false, type: 'int'},
                {name: 'rp_total1', allowBlank: false, type: 'int'},
                {name: 'ekstra_diskon', allowBlank: false, type: 'int'},
                {name: 'rp_diskon_tambahan', allowBlank: false, type: 'int'},
                {name: 'rp_ekstra_diskon', allowBlank: false, type: 'int'},
                {name: 'disk_persen_supp2', allowBlank: false, type: 'int'},
                {name: 'disk_persen_supp3', allowBlank: false, type: 'int'},
                {name: 'disk_persen_supp4', allowBlank: false, type: 'int'},
                {name: 'disk_amt_supp1', allowBlank: false, type: 'int'},
                {name: 'qty_efektif_sj', allowBlank: false, type: 'int'},
                {name: 'qty_efektif_do', allowBlank: false, type: 'int'},
                {name: 'qty_retur_do', allowBlank: false, type: 'int'},
                {name: 'qty_efektif_so', allowBlank: false, type: 'int'},
                {name: 'hrg_supplier', allowBlank: false, type: 'int'},
                {name: 'harga', allowBlank: false, type: 'int'},
                {name: 'jumlah', allowBlank: false, type: 'int'},
                {name: 'kd_sub_blok', allowBlank: false, type: 'int'},
                {name: 'qty_retur_so', allowBlank: false, type: 'int'},
                {name: 'tot_qty_retur_so', allowBlank: false, type: 'int'},
                {name: 'tot_qty_retur_do', allowBlank: false, type: 'int'}
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

    strpenjualanretur.on('remove',  function(){

        var grand_total = 0;
        var dpp = 0;
        var ppn = 0;
        var jumlah = 0;
        var edit = "";
        strpenjualanretur.each(function(node){
            //jumlah += parseInt(node.data.rp_total);
            if (edit === 'Y'){
            grand_total += parseInt(node.data.rp_total1);
            grand_total += parseInt(node.data.rp_total1);
            dpp = parseInt(grand_total / 1.1);
            ppn = parseInt (dpp * 0.1);
            }

        });
        Ext.getCmp('ppn_returjual').setValue(ppn);
        Ext.getCmp('dpp_returjual').setValue(dpp);
        Ext.getCmp('jumlah_returjual').setValue(grand_total);
        var grandtotal_retur = grand_total - Ext.getCmp('rj_rp_diskon_tambahan').getValue();
	Ext.getCmp('grandtotal_returjual').setValue(grandtotal_retur);
    });

    strpenjualanretur.on('update',  function(){

        var grand_total = 0;
        var dpp = 0;
        var ppn = 0;
        var jumlah = 0;
        var edit = "";
        strpenjualanretur.each(function(node){
            //jumlah += parseInt(node.data.rp_total);
            edit = node.data.edited;
            if (edit === 'Y'){
            grand_total += parseInt(node.data.rp_total1);
            dpp = parseInt(grand_total / 1.1);
            ppn = parseInt (dpp * 0.1);
            }
            //rp_diskon += parseInt(node.data.rp_diskon);
            //rp_ekstra_diskon += parseInt(node.data.rp_diskon_tambahan);
        });

        //grand_total = jumlah-rp_diskon-rp_ekstra_diskon;
        var bayar = Ext.getCmp('id_pjret_total_bayar').getValue();

        Ext.getCmp('ppn_returjual').setValue(ppn);
        Ext.getCmp('dpp_returjual').setValue(dpp);
        Ext.getCmp('jumlah_returjual').setValue(grand_total);
        var total_so = Ext.getCmp('id_pjret_total_so').getValue();
        var rp_retur = Ext.getCmp('id_pjret_rp_retur').getValue();
        var grandtotal_retur = grand_total - Ext.getCmp('rj_rp_diskon_tambahan').getValue();
        var kurang_bayar = Ext.getCmp('id_pjret_kurang_bayar').getValue();
    	Ext.getCmp('grandtotal_returjual').setValue(grandtotal_retur);
        // Ext.getCmp('nilai_retur').setValue(bayar - kurang_bayar);
        (3200 - 0) - (4200 - 1400)
        var nilai_retur = Number( bayar - rp_retur) - Number( total_so - grandtotal_retur );
        if(nilai_retur < 0 ) nilai_retur = 0;
        Ext.getCmp('nilai_retur').setValue(nilai_retur);


    });

   /* SubBlok */
    var strcbkdsubblokpr = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['sub', 'nama_sub'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("penjualan_retur/get_sub_blok") ?>',
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

    var strgridsubblokpr = new Ext.data.Store({
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
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("penjualan_retur/get_rows_lokasi") ?>',
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

    // search field
    var searchgridrjsubblok = new Ext.app.SearchField({
        store: strgridsubblokpr,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchgridrjsubblok'
    });

    // top toolbar
    var tbgridrjsubblok = new Ext.Toolbar({
        items: [searchgridrjsubblok]
    });

    var gridrjsubblok = new Ext.grid.GridPanel({
        store: strgridsubblokpr,
        stripeRows: true,
        frame: true,
        border:true,
        tbar: tbgridrjsubblok,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridsubblokpr,
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
            'rowdblclick': function(){
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('pjret_sub_blok').setValue(sel[0].get('sub'));
                    Ext.getCmp('pjret_nama_sub_blok').setValue(sel[0].get('nama_sub'));

                    menusubblokreturjual.hide();
                }
            }
        }
    });

    var menusubblokreturjual = new Ext.menu.Menu();
    menusubblokreturjual.add(new Ext.Panel({
        title: 'Pilih Sub Blok Lokasi',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 350,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridrjsubblok],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menusubblokreturjual.hide();
                }
            }]
    }));

    Ext.ux.TwinComborpSubBlok = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
           /* strgridsubblokpr.load({
                params:{
                    kd_produk: Ext.getCmp('pjret_kd_produk').getValue()
                }
            });*/
            strgridsubblokpr.setBaseParam('kd_produk',Ext.getCmp('pjret_kd_produk').getValue());
            strgridsubblokpr.load();

            menusubblokreturjual.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    /* END SubBlok*/
    // TWIN NO DO
    var strcbpronodo = new Ext.data.ArrayStore({
        fields: ['no_do'],
        data : []
    });
    var strgridpronodo = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_do','tanggal','qty'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("penjualan_retur/search_do") ?>',
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

    strgridpronodo.on('load', function(){
        Ext.getCmp('search_query_no_do').focus();
    });

    var searchFieldRONoDO = new Ext.app.SearchField({
        width: 220,
        id: 'search_query_no_do',
        store: strgridpronodo
    });
    searchFieldRONoDO.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';

            // Get the value of search field
            var fid = Ext.getCmp('id_cbpjretsalesorder').getValue();
            var xx = Ext.getCmp('pjret_kd_produk').getValue();
            var o = { start: 0, no_so: fid, kd_produk:xx};

            this.store.baseParams = this.store.baseParams || {};
            this.store.baseParams[this.paramName] = '';
            this.store.reload({
                params : o
            });
            this.triggers[0].hide();
            this.hasSearch = false;
        }
    };

    searchFieldRONoDO.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }

        // Get the value of search field
        var fid = Ext.getCmp('id_cbpjretsalesorder').getValue();
        var xx = Ext.getCmp('pjret_kd_produk').getValue();
        var o = { start: 0, no_so: fid, kd_produk: xx};

        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params:o});
        this.hasSearch = true;
        this.triggers[0].show();
    };

    // top toolbar
    var tbsearchnodo = new Ext.Toolbar({
        items: [searchFieldRONoDO]
    });

    var gridpronodo = new Ext.grid.GridPanel({
        store: strgridpronodo,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'No DO',
                dataIndex: 'no_do',
                width: 120,
                sortable: true

            }, {
                header: 'Tgl DO',
                dataIndex: 'tanggal',
                width: 100,
                sortable: true

            }, {
                header: 'Qty',
                dataIndex: 'qty',
                width: 100,
                sortable: true

            }],
        tbar:tbsearchnodo,
        listeners: {
            'rowdblclick': function(){
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    if(sel[0].get('no_do') < Ext.getCmp('pjret_qty_retur_do').getValue()){
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Qty DO yang di pilih lebih kecil daripada qty retur do!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK

                        });
                        Ext.getCmp('pjret_qty_retur_do').setValue(0);
                        return;
                    }
                    Ext.getCmp('epro_no_do').setValue(sel[0].get('no_do'));
                    menupronodo.hide();
                   }
            }
        }
    });

    var menupronodo = new Ext.menu.Menu();
    menupronodo.add(new Ext.Panel({
        title: 'Pilih No DO',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 300,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridpronodo],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menupronodo.hide();
                }
            }]
    }));

    Ext.ux.TwinComboproNoDO = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgridpronodo.load({
                params: {
                    no_so: Ext.getCmp('id_cbpjretsalesorder').getValue(),
                    kd_produk : Ext.getCmp('pjret_kd_produk').getValue(),
                }
            });
            menupronodo.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    //END NO DO

    var editorpenjualanretur = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });
    var gridreturpenjualan = new Ext.grid.GridPanel({
        stripeRows: true,
        title: 'RETUR JUAL',
        height: 200,
        store:strpenjualanretur,
        frame: true,
        border:true,
        plugins: [editorpenjualanretur],
//        tbar: [{
//                ref: '../removeBtn',
//                icon: BASE_ICONS + 'delete.gif',
//                text: 'Remove',
//                disabled: true,
//                handler: function(){
//                    editorpenjualanretur.stopEditing();
//                    var s = gridreturpenjualan.getSelectionModel().getSelections();
//                    for(var i = 0, r; r = s[i]; i++){
//                        strpenjualanretur.remove(r);
//                    }
//                }
//            }],
        columns: [{
                    header: 'Edited',
                    dataIndex: 'edited',
                    width: 50,
                    sortable: true,
                    editor: {
                        xtype:          'textfield',
                       /* store:          new Ext.data.JsonStore({
                            fields : ['name'],
                            data   : [
                                {name : 'Y'},
                                {name : 'N'},
                            ]
                        }),*/
                        id:           	'rj_edited',
                        mode:           'local',
                        name:           'edited',
                        value:          '%',
                        width:			50,
                        editable:       false,
                        hiddenName:     'edited',
                        valueField:     'name',
                        displayField:   'name',
                        triggerAction:  'all',
                        forceSelection: true,
                        readOnly: true
                    }
                }, {
                header: 'Kode Produk',
                dataIndex: 'kd_produk',
                width: 200,
                sortable: true,
                editor: {
                    xtype: 'textfield',
                    id:'pjret_kd_produk',
                    readOnly: true
                }
            }, {
                header: 'Kode Produk Supplier',
                dataIndex: 'kd_produk_supp',
                width: 200,
                sortable: true,
                editor: {
                    xtype: 'textfield',
                    id:'pjret_kd_produk_supp',
                    readOnly: true
                }
            }, {
                header: 'Nama Barang',
                dataIndex: 'nama_produk',
                width: 400

            }, {
                xtype: 'numbercolumn',
                header: 'Qty SO',
                dataIndex: 'qty_so',
                width: 50,
                align: 'center',
                sortable: true,
                format: '0',
                fieldClass:'number',
                 editor: {
                    xtype: 'numberfield',
                    id:'pjret_qty_struk',
                    readOnly: true,
                    fieldClass:'readonly-input'
                }
            }, {
                xtype: 'numbercolumn',
                header: 'Total Retur SO',
                dataIndex: 'tot_qty_retur_so',
                width: 80,
                align: 'center',
                sortable: true,
                format: '0',
                fieldClass:'number',
                 editor: {
                    xtype: 'numberfield',
                    id:'pjret_total_retur_so',
                    readOnly: true,
                    fieldClass:'readonly-input'
                }
            }, {
                xtype: 'numbercolumn',
                header: 'Efektif SO',
                dataIndex: 'qty_efektif_so',
                width: 80,
                align: 'center',
                sortable: true,
                format: '0',
                fieldClass:'number',
                 editor: {
                    xtype: 'numberfield',
                    id:'pjret_efektif_so',
                    readOnly: true,
                    fieldClass:'readonly-input'
                }
            }, {
                xtype: 'numbercolumn',
                header: 'Qty DO',
                dataIndex: 'qty_do',
                width: 50,
                align: 'center',
                sortable: true,
                format: '0',
                fieldClass:'number',
                 editor: {
                    xtype: 'numberfield',
                    id:'pjret_qty_do',
                    readOnly: true,
                    fieldClass:'readonly-input'
                }
            }, {
                xtype: 'numbercolumn',
                header: 'Total Retur DO',
                dataIndex: 'tot_qty_retur_do',
                width: 80,
                align: 'center',
                sortable: true,
                format: '0',
                fieldClass:'number',
                 editor: {
                    xtype: 'numberfield',
                    id:'pjret_total_retur_do',
                    readOnly: true,
                    fieldClass:'readonly-input'
                }
            }, {
                xtype: 'numbercolumn',
                header: 'Efektif DO',
                dataIndex: 'qty_efektif_do',
                width: 80,
                align: 'center',
                sortable: true,
                format: '0',
                fieldClass:'number',
                 editor: {
                    xtype: 'numberfield',
                    id:'pjret_efektif_do',
                    readOnly: true,
                    fieldClass:'readonly-input'
                }
            }, {
                xtype: 'numbercolumn',
                header: 'Qty Struk/SJ',
                dataIndex: 'qty_sj',
                width: 90,
                align: 'center',
                sortable: true,
                format: '0',
                fieldClass:'number',
                 editor: {
                    xtype: 'numberfield',
                    id:'pjret_qty_sj',
                    readOnly: true,
                    fieldClass:'readonly-input'
                }
            }, {
                xtype: 'numbercolumn',
                header: 'Total Retur',
                dataIndex: 'qty_retur',
                width: 80,
                align: 'center',
                sortable: true,
                format: '0',
                fieldClass:'number',
                 editor: {
                    xtype: 'numberfield',
                    id:'pjret_qty_retur',
                    readOnly: true,
                    fieldClass:'readonly-input'
                }
            }, {
                xtype: 'numbercolumn',
                header: 'Efektif Struk/SJ',
                dataIndex: 'qty_efektif_sj',
                width: 80,
                align: 'center',
                sortable: true,
                format: '0',
                fieldClass:'number',
                 editor: {
                    xtype: 'numberfield',
                    id:'pjret_efektif_sj',
                    readOnly: true,
                    fieldClass:'readonly-input'
                }
            },
            {
                 hidden: true,
                header: 'Retur SO',
                dataIndex: 'tot_qty_retur_so',
                width: 80,
                align: 'center',
                sortable: true,
                format: '0',
                fieldClass:'number',
                 editor: {
                    xtype: 'numberfield',
                    id:'pjret_qty_retur_so_hidden',
                    readOnly: true
                }
            },
            {
                xtype: 'numbercolumn',
                header: 'Qty_Retur_SO',
                dataIndex: 'qty_retur_so',
                width: 80,
                align: 'center',
                sortable: true,
                format: '0',
                fieldClass:'number',
                 editor: {
                    xtype: 'numberfield',
                    id:'pjret_qty_retur_so',
                    readOnly: false,
                     listeners:{
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                var harga = parseFloat(Ext.getCmp('pjret_harga').getValue());
                                var diskon = parseFloat(Ext.getCmp('pjret_diskon').getValue());
                                var qty_struk = parseFloat(Ext.getCmp('pjret_qty_struk').getValue());
                                var diskon_tambahan = parseFloat(Ext.getCmp('pjret_diskon_tambahan').getValue());
                                var diskon_rinci = diskon_tambahan / qty_struk;
                                var disk = diskon_rinci * this.getValue();
                                Ext.getCmp('pjret_ekstra_diskon').setValue(disk);
                                var harga_barang = (harga - diskon) * this.getValue();
                                var rp_total = harga_barang - disk;
                                Ext.getCmp('pjret_jumlah').setValue(rp_total);
                                var qty = this.getValue();
                                if (qty === '' || qty === 0){
                                    Ext.getCmp('rj_edited').setValue('N');
                                }else {
                                    Ext.getCmp('rj_edited').setValue('Y');
                                }


                                var ttl_retur_so = parseFloat(Ext.getCmp('pjret_qty_retur_so_hidden').getValue()) + this.getValue();
                                var efektif_so = parseFloat(Ext.getCmp('pjret_qty_struk').getValue()) - ttl_retur_so;
                                if (efektif_so < 0 ){
                                    Ext.Msg.show({
                                        title: 'Error',
                                        msg: 'Nilai Retur SO Tidak Sesuai',
                                        modal: true,
                                        icon: Ext.Msg.ERROR,
                                        buttons: Ext.Msg.OK,
                                        fn: function(btn){
                                            if (btn == 'ok') {
                                                Ext.getCmp('pjret_qty_retur_so').reset();
                                                Ext.getCmp('rj_edited').setValue('N');
                                            }
                                        }
                                    });
                                }
                                Ext.getCmp('pjret_total_retur_so').setValue(ttl_retur_so);
                                Ext.getCmp('pjret_efektif_so').setValue(efektif_so);

                                var grand_total = 0;
                                var rp_diskon = 0;
                                var rp_ekstra_diskon = 0;
                                var jumlah = 0;


                            }, c);
                        }
                    }
                }
            }, {
                hidden: true,
                header: 'Retur DO',
                dataIndex: 'tot_qty_retur_do',
                width: 80,
                align: 'center',
                sortable: true,
                format: '0',
                fieldClass:'number',
                 editor: {
                    xtype: 'numberfield',
                    id:'pjret_qty_retur_do_hidden',
                    readOnly: true
                }
            },
            {
                xtype: 'numbercolumn',
                header: 'Qty_Retur_DO',
                dataIndex: 'qty_retur_do',
                width: 80,
                align: 'center',
                sortable: true,
                format: '0',
                fieldClass:'number',
                 editor: {
                    xtype: 'numberfield',
                    id:'pjret_qty_retur_do',
                    readOnly: false,
                     listeners:{
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                var harga = parseFloat(Ext.getCmp('pjret_harga').getValue());
                                var diskon = parseFloat(Ext.getCmp('pjret_diskon').getValue());
                                var qty_struk = parseFloat(Ext.getCmp('pjret_qty_struk').getValue());
                                var diskon_tambahan = parseFloat(Ext.getCmp('pjret_diskon_tambahan').getValue());
                                var diskon_rinci = diskon_tambahan / qty_struk;
                                var disk = diskon_rinci * this.getValue();
                                Ext.getCmp('pjret_ekstra_diskon').setValue(disk);
                                var harga_barang = (harga - diskon) * this.getValue();
                                var rp_total = harga_barang - disk;
                                Ext.getCmp('pjret_jumlah').setValue(rp_total);
                                var qty = this.getValue();
                                if (qty === '' || qty === 0){
                                    Ext.getCmp('rj_edited').setValue('N');
                                }else {
                                    Ext.getCmp('rj_edited').setValue('Y');
                                }
                                var qty_retur = parseFloat(Ext.getCmp('pjret_qty_retur').getValue());
                                var qty_do = parseFloat(Ext.getCmp('pjret_qty_do').getValue());

                                var retur_do = this.getValue();
                                //Ext.getCmp('pjret_qty_retur_do_hidden').setValue(retur_do);

                                var ttl_retur_do = parseFloat(Ext.getCmp('pjret_qty_retur_do_hidden').getValue()) + this.getValue();
                                var efektif_do = parseFloat(Ext.getCmp('pjret_qty_do').getValue()) - ttl_retur_do;
                                var ttl_retur_so = parseFloat(Ext.getCmp('pjret_qty_retur_so_hidden').getValue()) + this.getValue();
                                var efektif_so = parseFloat(Ext.getCmp('pjret_qty_struk').getValue()) - ttl_retur_so;
                                if (efektif_do < 0){
                                    Ext.Msg.show({
                                        title: 'Error',
                                        msg: 'Nilai Retur DO Tidak Sesuai',
                                        modal: true,
                                        icon: Ext.Msg.ERROR,
                                        buttons: Ext.Msg.OK,
                                        fn: function(btn){
                                            if (btn == 'ok') {
                                                Ext.getCmp('pjret_qty_do').reset();
                                                Ext.getCmp('rj_edited').setValue('N');
                                            }
                                        }
                                    });
                                }
                                Ext.getCmp('pjret_total_retur_do').setValue(ttl_retur_do);
                                Ext.getCmp('pjret_efektif_do').setValue(efektif_do);
                                Ext.getCmp('pjret_total_retur_so').setValue(ttl_retur_so);
                                Ext.getCmp('pjret_efektif_so').setValue(efektif_so);

                                var grand_total = 0;
                                var rp_diskon = 0;
                                var rp_ekstra_diskon = 0;
                                var jumlah = 0;


                            }, c);
                        }
                    }
                }
            }, {
                header: 'No DO',
                dataIndex: 'no_do',
                width: 140,
                editor: new Ext.ux.TwinComboproNoDO({
                    id: 'epro_no_do',
                    store: strcbpronodo,
                    mode: 'local',
                    valueField: 'no_do',
                    displayField: 'no_do',
                    typeAhead: true,
                    triggerAction: 'all',
                    //allowBlank: false,
                    editable: false,
                    hiddenName: 'no_do',
                    emptyText: 'Pilih No DO'

                })
            }, {
                hidden: true,
                header: 'Retur ',
                dataIndex: 'qty_retur',
                width: 80,
                align: 'center',
                sortable: true,
                format: '0',
                fieldClass:'number',
                 editor: {
                    xtype: 'numberfield',
                    id:'pjret_qty_hidden',
                    readOnly: true
                }
            },
            {
                xtype: 'numbercolumn',
                header: 'Qty Retur',
                dataIndex: 'qty_input',
                width: 80,
                align: 'center',
                sortable: true,
                format: '0',
                fieldClass:'number',
                editor: {
                    xtype: 'numberfield',
                    id: 'pjret_qty',
                    listeners:{
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                var harga = parseFloat(Ext.getCmp('pjret_harga').getValue());
                                var diskon = parseFloat(Ext.getCmp('pjret_diskon').getValue());
                                var qty_struk = parseFloat(Ext.getCmp('pjret_qty_struk').getValue());
                                var diskon_tambahan = parseFloat(Ext.getCmp('pjret_diskon_tambahan').getValue());
                                var diskon_rinci = diskon_tambahan / qty_struk;
                                var disk = diskon_rinci * this.getValue();
                                Ext.getCmp('pjret_ekstra_diskon').setValue(disk);
                                var harga_barang = (harga - diskon) * this.getValue();
                                var rp_total = harga_barang - disk;
                                Ext.getCmp('pjret_jumlah').setValue(rp_total);
                                var qty = this.getValue();
                                if (qty === '' || qty === 0){
                                    Ext.getCmp('rj_edited').setValue('N');
                                }else {
                                    Ext.getCmp('rj_edited').setValue('Y');
                                }

                                var retur = this.getValue();
                                //Ext.getCmp('pjret_qty_hidden').setValue(retur);

                                var ttl_retur = parseFloat(Ext.getCmp('pjret_qty_hidden').getValue()) + this.getValue();
                                var efektif_sj = parseFloat(Ext.getCmp('pjret_qty_sj').getValue()) - ttl_retur;
                                var ttl_retur_do = parseFloat(Ext.getCmp('pjret_qty_retur_do_hidden').getValue()) + this.getValue();
                                var efektif_do = parseFloat(Ext.getCmp('pjret_qty_do').getValue()) - ttl_retur_do;
                                var ttl_retur_so = parseFloat(Ext.getCmp('pjret_qty_retur_so_hidden').getValue()) + this.getValue();
                                var efektif_so = parseFloat(Ext.getCmp('pjret_qty_struk').getValue()) - ttl_retur_so;
                                // if (efektif_sj < 0){
                                //     Ext.Msg.show({
                                //         title: 'Error',
                                //         msg: 'Nilai Retur Tidak Sesuai',
                                //         modal: true,
                                //         icon: Ext.Msg.ERROR,
                                //         buttons: Ext.Msg.OK,
                                //         fn: function(btn){
                                //             if (btn == 'ok') {
                                //                 Ext.getCmp('pjret_qty').reset();
                                //                 Ext.getCmp('rj_edited').setValue('N');
                                //             }
                                //         }
                                //     });
                                // }
                                Ext.getCmp('pjret_qty_retur').setValue(ttl_retur);
                                Ext.getCmp('pjret_efektif_sj').setValue(efektif_sj);
                                Ext.getCmp('pjret_total_retur_do').setValue(ttl_retur_do);
                                Ext.getCmp('pjret_efektif_do').setValue(efektif_do);
                                Ext.getCmp('pjret_total_retur_so').setValue(ttl_retur_so);
                                Ext.getCmp('pjret_efektif_so').setValue(efektif_so);

                                var grand_total = 0;
                                var rp_diskon = 0;
                                var rp_ekstra_diskon = 0;
                                var jumlah = 0;


                            }, c);
                        }
                    }
                }
            }, {
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 50,readOnly: true

            }, {
                header: 'Kode Sub Blok',
                dataIndex: 'sub',
                width: 100,
                editor: new Ext.ux.TwinComborpSubBlok({
                    id: 'pjret_sub_blok',
                    store: strcbkdsubblokpr,
                    valueField: 'sub',
                    displayField: 'sub',
                    typeAhead: true,
                    triggerAction: 'all',
                    //allowBlank: false,
                    editable: false,
                    hiddenName: 'sub',
                    emptyText: 'Pilih Sub Blok',
                    listeners: {
                        'expand': function(){
                            strcbkdsubblokpr.load();
                        }
                    }
                })
            }, {
                header: 'Sub Blok',
                dataIndex: 'nama_sub',
                width: 200,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'pjret_nama_sub_blok'
                })
            }, {
                xtype: 'numbercolumn',
                format: '0,0',
                header: 'Harga',
                dataIndex: 'rp_harga',
                width: 100,
                align: 'right',
                sortable: true,


                editor: {
                    xtype: 'numberfield',
                    id:'pjret_harga',
                     readOnly: true
                }

            }, {
                xtype: 'numbercolumn',
                header: 'Diskon',
                dataIndex: 'rp_diskon',
                width: 80,
                align: 'right',
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id:'pjret_diskon',
                     readOnly: true
                }
            }, {
                xtype: 'numbercolumn',
                header: 'Ekstra Diskon (Struk)',
                dataIndex: 'rp_ekstra_diskon',
                width: 140,
                align: 'right',
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id:'pjret_diskon_tambahan',
                     readOnly: true
                }
            }, {
                xtype: 'numbercolumn',
                header: 'Ekstra Diskon',
                dataIndex: 'ekstra_diskon',
                width: 100,
                align: 'right',
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id:'pjret_ekstra_diskon',
                     readOnly: true
                }
            }, {
                xtype: 'numbercolumn',
                header: 'Total',
                dataIndex: 'rp_total1',
                width: 100,
                sortable: true,
                align: 'right',
                format: '0,0',
                id: 'pjret_jumlah',
                fieldClass:'number',
                editor: {
                    xtype: 'numberfield',
                    id: 'pjret_jumlah'
                    // readOnly: true
                }
            }]
    });

    gridreturpenjualan.getSelectionModel().on('selectionchange', function(sm){
        gridreturpenjualan.removeBtn.setDisabled(sm.getCount() < 1);
    });
    // Bonus

    var strbonusretur = new Ext.data.Store({
        autoSave: false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_produk', allowBlank: false, type: 'text'},
                {name: 'kd_produk_bonus', allowBlank: false, type: 'text'},
                {name: 'qty_bonus', allowBlank: false, type: 'int'},
                {name: 'qty_retur_bonus', allowBlank: false, type: 'int'},
                {name: 'qty_retur', allowBlank: false, type: 'int'}
                ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("penjualan_retur/search_produk_bonus_by_so") ?>',
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

    var editorbonusretur = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });


    var gridbonus = new Ext.grid.GridPanel({
        store: strbonusretur,
        title: 'RETUR BONUS',
        stripeRows: true,
        height: 190,
        frame: true,
        border: true,
        plugins: [editorbonusretur],
        columns: [{
                header: 'Kode Produk',
                dataIndex: 'kd_produk',
                width: 90,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'erjb_kd_produk',
                    fieldClass: 'readonly-input'
                })
            },
                {
                header: 'Kode Produk Bonus',
                dataIndex: 'kd_produk_bonus',
                width: 120,
                sortable: true,
                align: 'right',
                format: '0,0',
                value: 0,
                editor: {
                    xtype: 'textfield',
                    id: 'erjb_kd_produk_bonus',
                    fieldClass: 'readonly-input number',
                    readOnly: true
                }
            }, {
                xtype: 'numbercolumn',
                header: 'Qty Bonus',
                dataIndex: 'qty_bonus',
                width: 90,
                sortable: true,
                align: 'right',
                format: '0,0',
                value: 0,
                editor: {
                    xtype: 'numberfield',
                    id: 'erjb_qty_retur'

                }
            }]
    });

     gridbonus.getSelectionModel().on('selectionchange', function(sm){
     gridbonus.removeBtn.setDisabled(sm.getCount() < 1);
     });

    // End Bonus
    var winpembelianreturprint = new Ext.Window({
        id: 'id_winpembelianreturprint',
        title: 'Print Receive Order Form',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="pembelianreturprint" src=""></iframe>'
    });

    var penjualanretursalesorder = new Ext.FormPanel({
        id: 'retursalesorder',
        border: false,
        frame: true,
        autoScroll:true,
        monitorValid: true,
        bodyStyle:'padding-right:20px;',
        labelWidth: 130,
        items: [{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },
                items: [headerpenjualanretur]
            }, {
                xtype: 'tabpanel',
                height: 230,
                activeTab: 0,
                deferredRender: false,
                items: [gridreturpenjualan,gridbonus]
            },
             {
                layout: 'column',
                border: false,
                items: [{
                        columnWidth: .6,
                        style:'margin:6px 3px 0 0;',
                        layout: 'form',
                        labelWidth: 100,
                        items: [
                            {
                                xtype: 'textarea',
                                fieldLabel: 'Alasan Retur <span class="asterix">*</span>',
                                allowBlank: false,
                                name: 'remark',
                                id: 'remark_returjual',
                                width: 300,
                                value:''
                            }
                        ]
                    }, {
                        columnWidth: .4,
                        layout: 'form',
                        style:'margin:6px 0 0 0;',
                        border: false,
                        labelWidth: 110,
                        defaults: { labelSeparator: ''},
                        items: [
                            {
                                xtype: 'fieldset',
                                autoHeight: true,
                                items: [
                                  /*  {
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'Jumlah',
                                        name: 'jumlah',
                                        readOnly: true,
                                        id: 'jumlah_returjual',
                                        anchor: '90%',
                                        fieldClass:'readonly-input number',
                                        value:''

                                    },
                                    {
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'Rp.Diskon',
                                        name: 'diskon_returjual',
                                        readOnly: true,
                                        id: 'diskon_returjual',
                                        anchor: '90%',
                                        fieldClass:'readonly-input number',
                                        value:''
                                    },
                                    {
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'Ekstra Diskon',
                                        name: 'diskon_ekstra_returjual',
                                        readOnly: true,
                                        id: 'diskon_ekstra_returjual',
                                        anchor: '90%',
                                        fieldClass:'readonly-input number',
                                        value:''
                                    },*/{
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: '<b>Jumlah Retur</b>',
                                        name: 'total',
                                        cls:'vertical-space',
                                        readOnly: true,
                                        id: 'jumlah_returjual',
                                        anchor: '90%',
                                        fieldClass:'readonly-input bold-input number',
                                        labelStyle:'margin-top:10px;',
                                        value:''
                                    }, {
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'DPP',
                                        name: 'dpp_returjual',
                                        readOnly: true,
                                        id: 'dpp_returjual',
                                        anchor: '90%',
                                        fieldClass:'readonly-input number',
                                        value:''
                                    }, {
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'PPN',
                                        name: 'ppn_returjual',
                                        readOnly: true,
                                        id: 'ppn_returjual',
                                        anchor: '90%',
                                        fieldClass:'readonly-input number',
                                        value:''
                                    }, {
                                        xtype: 'compositefield',
                                        fieldLabel: 'Potongan Retur',
                                        combineErrors: false,
                                        items: [
                                            {
                                                xtype: 'numericfield',
                                                currencySymbol:'',
                                                format:'0',
                                                name : 'pct_diskon_tambahan',
                                                id: 'rj_pct_diskon_tambahan',
                                                fieldClass:'number',
                                                width: 60,
                                                value: '0',
                                                maxValue:100,
                                                listeners: {
                                                    'change': function(){
                                                        var diskon_tambahan = Ext.getCmp('jumlah_returjual').getValue() *  this.getValue() / 100;
                                                        var total = Ext.getCmp('jumlah_returjual').getValue() - diskon_tambahan;
							Ext.getCmp('rj_rp_diskon_tambahan').setValue(diskon_tambahan);
                                                        Ext.getCmp('grandtotal_returjual').setValue(total);

                                                    }
                                                }

                                            },
                                            {
                                                xtype: 'displayfield',
                                                value: '%',
                                                width: 17.5
                                            },
                                            {
                                                xtype: 'numericfield',
                                                name : 'rp_diskon_tambahan',
                                                id : 'rj_rp_diskon_tambahan',
                                                currencySymbol:'',
                                                value : '0',
                                                fieldClass:'number',
                                                readOnly: false,
                                                width: 120,
                                                anchor: '90%',
                                                listeners: {
                                                    'change': function(){
                                                        var diskon_tambahan = (this.getValue() / Ext.getCmp('jumlah_returjual').getValue()) * 100 ;
                                                        var total = Ext.getCmp('jumlah_returjual').getValue() - this.getValue();
							Ext.getCmp('rj_pct_diskon_tambahan').setValue(diskon_tambahan);
                                                        Ext.getCmp('grandtotal_returjual').setValue(total);

                                                    }
                                                }

                                            }
                                        ]
                                    }, {
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: '<b>Grand Total</b>',
                                        name: 'total',
                                        cls:'vertical-space',
                                        readOnly: true,
                                        id: 'grandtotal_returjual',
                                        anchor: '90%',
                                        fieldClass:'readonly-input bold-input number',
                                        labelStyle:'margin-top:10px;',
                                        value:''
                                    }, {
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: '<b>Nilai Retur</b>',
                                        name: 'rp_nilai_retur',
                                        cls:'vertical-space',
                                        readOnly: true,
                                        id: 'nilai_retur',
                                        anchor: '90%',
                                        fieldClass:'readonly-input bold-input number',
                                        labelStyle:'margin-top:10px;',
                                        value:''
                                    }
                                ]
                            }
                        ]
                    }]
            }


        ],
        buttons: [{
                text: 'Save',
                formBind: true,
                handler: function(){
                    if(Ext.getCmp('grandtotal_returjual').getValue() ==0){
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Tidak ada retur penjualan!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK

                        });
                        return;
                    }
                    if(Ext.getCmp('id_pjret_tglretur').getValue() < Ext.getCmp('id_pjret_tglso').getValue()){
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Tgl Retur tidak boleh lebih kecil dari Tgl SO!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK

                        });
                        return;
                    }

//                    var retur_so = parseFloat(Ext.getCmp('pjret_retur_so').getValue());
//                    var retur = parseFloat(Ext.getCmp('pjret_qty_retur').getValue());
//                    var qty_retur_so = parseFloat(Ext.getCmp('pjret_qty_retur_so').getValue());
//                    var qty_retur_do = parseFloat(Ext.getCmp('pjret_qty_retur_do').getValue());
//                    var qty_retur = parseFloat(Ext.getCmp('pjret_qty').getValue());
//                    var maks_retur = retur_so + retur + qty_retur_so + qty_retur_do + qty_retur;
//                    var qty_so = parseFloat(Ext.getCmp('pjret_qty_struk').getValue());
//
//                    if (maks_retur > qty_so){
//                        Ext.Msg.show({
//                                title: 'Error',
//                                msg: 'Retur SO + Retur + Qty Retur SO + Qty Retur DO + Qty Retur Tidak Boleh Lebih Dari Qty SO!',
//                                modal: true,
//                                icon: Ext.Msg.ERROR,
//                                buttons: Ext.Msg.OK
//
//                            });
//                            return;
//                    }

                    if (Ext.getCmp('pjret_qty').getValue() > 0){
                        if(Ext.getCmp('pjret_sub_blok').getValue() ==''){
                            Ext.Msg.show({
                                title: 'Error',
                                msg: 'kode sub blok harus di isi!',
                                modal: true,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OK

                            });
                            return;
                        }
                    }
                    var detailreturjual = new Array();
                    strpenjualanretur.each(function(node){
                        detailreturjual.push(node.data)
                    });

                    Ext.getCmp('retursalesorder').getForm().submit({
                        url: '<?= site_url("penjualan_retur/update_row") ?>',
                        scope: this,
                        params: {
                            detail: Ext.util.JSON.encode(detailreturjual),
                            _grandtotal_returjual: Ext.getCmp('grandtotal_returjual').getValue(),
                            nilai_retur: Ext.getCmp('nilai_retur').getValue(),
                            dpp: Ext.getCmp('dpp_returjual').getValue(),
                            ppn: Ext.getCmp('ppn_returjual').getValue(),
                            _jumlah_returjual: Ext.getCmp('jumlah_returjual').getValue(),
                            _remark: Ext.getCmp('remark_returjual').getValue(),
                            _pct_diskon_tammbahan: Ext.getCmp('rj_pct_diskon_tambahan').getValue(),
                            _rp_diskon_tambahan: Ext.getCmp('rj_rp_diskon_tambahan').getValue()

                        },
                        waitMsg: 'Saving Data...',
                        success: function(form, action){
                            var r = Ext.util.JSON.decode(action.response.responseText);
                            Ext.Msg.show({
                                title: 'Success',
                                msg: r.errMsg,
                                modal: true,
                                icon: Ext.Msg.INFO,
                                buttons: Ext.Msg.OK,
                                fn: function(btn){
                                    if (btn == 'ok') {
                                        winreturpenjualanprint.show();
                                        Ext.getDom('returpenjualanprint').src = r.printUrl;
                                    }
                                }
                            });

                            clearpenjualanretur();
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
            }, {
                text: 'Reset',
                handler: function(){
                    clearpenjualanretur();
                }
            }],
        listeners:{
            afterrender:function(){

                this.getForm().load({
                    url: '<?= site_url("penjualan_retur/get_form") ?>',
                    failure: function(form, action){
                        var de = Ext.util.JSON.decode(action.response.responseText);
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
                });
            }
        }
    });
    var winreturpenjualanprint = new Ext.Window({
        id: 'id_winreturpenjualanprint',
        title: 'Print Retur Penjualan',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="returpenjualanprint" src=""></iframe>'
    });

    function clearpenjualanretur(){
        Ext.getCmp('retursalesorder').getForm().reset();
        Ext.getCmp('retursalesorder').getForm().load({
            url: '<?= site_url("penjualan_retur/get_form") ?>',
            failure: function(form, action){
                var de = Ext.util.JSON.decode(action.response.responseText);
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
        });
        strpenjualanretur.removeAll();
        strgrid_retjual_salesorder.removeAll();
    }
</script>