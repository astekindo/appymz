<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
/* START twin produk*/

var strcbhjdproduk = new Ext.data.ArrayStore({
    fields: ['kd_produk_baru', 'nama_produk'],
    data : []
});

var strgridhjdproduk = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: ['kd_produk_baru', 'nama_produk'],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("harga_penjualan_distribusi/search_produk_by_kategori") ?>',
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

var searchgridhjdproduk = new Ext.app.SearchField({
    store: strgridhjdproduk,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridhjdproduk'
});


var gridhjdproduk = new Ext.grid.GridPanel({
    store: strgridhjdproduk,
    stripeRows: true,
    frame: true,
    border:true,
    columns: [{
        header: 'Kode Produk',
        dataIndex: 'kd_produk_baru',
        width: 150,
        sortable: true

    },{
        header: 'Nama Produk',
        dataIndex: 'nama_produk',
        width: 150,
        sortable: true

    }],
    tbar: new Ext.Toolbar({
        items: [searchgridhjdproduk]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridhjdproduk,
        displayInfo: true
    }),
    listeners: {
        'rowdblclick': function(){
            var sm = this.getSelectionModel();
            var sel = sm.getSelections();
            if (sel.length > 0) {
                Ext.getCmp('id_cbhjdproduk').setValue(sel[0].get('kd_produk_baru'));
                menuhjdproduk.hide();
            }
        }
    }
});

var menuhjdproduk = new Ext.menu.Menu();
menuhjdproduk.add(new Ext.Panel({
    title: 'Pilih Produk',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridhjdproduk],
    buttons: [{
        text: 'Close',
        handler: function(){
            menuhjdproduk.hide();
        }
    }]
}));

Ext.ux.TwinCombohjdproduk = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function(){
        //load store grid
        var kd_supplier =  Ext.getCmp('id_cbhjdsuplier').getValue();
        if(!kd_supplier){
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
        strgridhjdproduk.load({
            params:{
                start: STARTPAGE,
                limit: ENDPAGE,
                kd_supplier: Ext.getCmp('id_cbhjdsuplier').getValue(),
                kd_kategori1: Ext.getCmp('hjd_cbkategori1').getValue(),
                kd_kategori2: Ext.getCmp('hjd_cbkategori2').getValue(),
                kd_kategori3: Ext.getCmp('hjd_cbkategori3').getValue(),
                kd_kategori4: Ext.getCmp('hjd_cbkategori4').getValue(),
                no_bukti: Ext.getCmp('id_cbhjdnobuktifilter').getValue(),
                konsinyasi: Ext.getCmp('hjd_konsinyasi').getValue(),
                list: Ext.getCmp('ehjd_list').getValue()
            }
        });
        menuhjdproduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuhjdproduk.on('hide', function(){
    var sf = Ext.getCmp('id_searchgridhjdproduk').getValue();
    if( sf != ''){
        Ext.getCmp('id_searchgridhjdproduk').setValue('');
        searchgridhjdproduk.onTrigger2Click();
    }
});

var cbhjdproduk = new Ext.ux.TwinCombohjdproduk({
    id: 'id_cbhjdproduk',
    store: strcbhjdproduk,
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

/* START TWIN NO BUKTI*/

var strcbhjdnobukti = new Ext.data.ArrayStore({
    fields: ['no_bukti','keterangan'],
    data : []
});

var strgridhjdnobukti = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: ['no_bukti','kd_diskon_sales','keterangan','created_by','nama_supplier'],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("harga_penjualan_distribusi/search_no_bukti_approve") ?>',
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

var searchgridhjdnobukti = new Ext.app.SearchField({
    store: strgridhjdnobukti,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridhjdnobukti'
});


var gridhjdnobukti = new Ext.grid.GridPanel({
    store: strgridhjdnobukti,
    stripeRows: true,
    frame: true,
    border:true,
    columns: [{
        header: 'No Bukti',
        dataIndex: 'kd_diskon_sales',
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
        items: [searchgridhjdnobukti]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridhjdnobukti,
        displayInfo: true
    }),
    listeners: {
        'rowdblclick': function(){
            var sm = this.getSelectionModel();
            var sel = sm.getSelections();
            if (sel.length > 0) {
                Ext.getCmp('id_cbhjdnobukti').setValue(sel[0].get('kd_diskon_sales'));

                menuhjdnobukti.hide();
            }
        }
    }
});

var menuhjdnobukti = new Ext.menu.Menu();
menuhjdnobukti.add(new Ext.Panel({
    title: 'Pilih No Bukti',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 500,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridhjdnobukti],
    buttons: [{
        text: 'Close',
        handler: function(){
            menuhjdnobukti.hide();
        }
    }]
}));

Ext.ux.TwinCombohjdnobukti = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function(){
        //load store grid
        strgridhjdnobukti.setBaseParam('kd_supplier',Ext.getCmp('id_cbhjdsuplier').getValue());
        strgridhjdnobukti.load();
        menuhjdnobukti.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuhjdnobukti.on('hide', function(){
    var sf = Ext.getCmp('id_searchgridhjdnobukti').getValue();
    if( sf != ''){
        Ext.getCmp('id_searchgridhjdnobukti').setValue('');
        searchgridhjdnobukti.onTrigger2Click();
    }
});

var cbhjdnobukti = new Ext.ux.TwinCombohjdnobukti({
    fieldLabel: 'No Bukti <span class="asterix">*</span>',
    id: 'id_cbhjdnobukti',
    store: strcbhjdnobukti,
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

/* END TWIN NO BUKTI*/

/*START TWIN NO BUKTI FILTER*/

var strcbhjdnobuktifilter = new Ext.data.ArrayStore({
    fields: ['no_bukti','keterangan'],
    data : []
});

var strgridhjdnobuktifilter = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: ['no_bukti','kd_diskon_sales','keterangan','created_by','nama_supplier'],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("harga_penjualan_distribusi/search_no_bukti") ?>',
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

var searchgridhjdnobuktifilter = new Ext.app.SearchField({
    store: strgridhjdnobuktifilter,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridhjdnobuktifilter'
});


var gridhjdnobuktifilter = new Ext.grid.GridPanel({
    store: strgridhjdnobuktifilter,
    stripeRows: true,
    frame: true,
    border:true,
    columns: [{
        header: 'No Bukti',
        dataIndex: 'kd_diskon_sales',
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
        items: [searchgridhjdnobuktifilter]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridhjdnobuktifilter,
        displayInfo: true
    }),
    listeners: {
        'rowdblclick': function(){
            var sm = this.getSelectionModel();
            var sel = sm.getSelections();
            if (sel.length > 0) {
                Ext.getCmp('id_cbhjdnobuktifilter').setValue(sel[0].get('kd_diskon_sales'));

                menuhjdnobuktifilter.hide();
            }
        }
    }
});

var menuhjdnobuktifilter = new Ext.menu.Menu();
menuhjdnobuktifilter.add(new Ext.Panel({
    title: 'Pilih No Bukti',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 500,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridhjdnobuktifilter],
    buttons: [{
        text: 'Close',
        handler: function(){
            menuhjdnobuktifilter.hide();
        }
    }]
}));

Ext.ux.TwinCombohjdnobuktifilter = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function(){
        //load store grid
        strgridhjdnobuktifilter.setBaseParam('kd_supplier',Ext.getCmp('id_cbhjdsuplier').getValue());
        strgridhjdnobuktifilter.load();
        menuhjdnobuktifilter.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuhjdnobuktifilter.on('hide', function(){
    var sf = Ext.getCmp('id_searchgridhjdnobuktifilter').getValue();
    if( sf !== ''){
        Ext.getCmp('id_searchgridhjdnobuktifilter').setValue('');
        searchgridhjdnobuktifilter.onTrigger2Click();
    }
});

var cbhjdnobuktifilter = new Ext.ux.TwinCombohjdnobuktifilter({
    fieldLabel: 'No Bukti Filter',
    id: 'id_cbhjdnobuktifilter',
    store: strcbhjdnobuktifilter,
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

/* START HISTORY */
var strhargapenjualandistribusihistory = new Ext.data.Store({
    autoSave:false,
    reader: new Ext.data.JsonReader({
        fields: [
            {name: 'kd_diskon_sales', allowBlank: true, type: 'text'},
            {name: 'koreksi_diskon', allowBlank: true, type: 'text'},
            {name: 'koreksi_produk', allowBlank: true, type: 'text'},
            {name: 'kd_produk', allowBlank: false, type: 'text'},
            {name: 'nama_produk', allowBlank: false, type: 'text'},
            {name: 'nm_satuan', allowBlank: false, type: 'text'},
            {name: 'nama_supplier', allowBlank: false, type: 'text'},
            {name: 'disk_toko1_op', allowBlank: false, type: 'text'},
            {name: 'disk_toko2_op', allowBlank: false, type: 'text'},
            {name: 'disk_toko3_op', allowBlank: false, type: 'text'},
            {name: 'disk_toko4_op', allowBlank: false, type: 'text'},
            {name: 'disk_agen1_op', allowBlank: false, type: 'text'},
            {name: 'disk_agen2_op', allowBlank: false, type: 'text'},
            {name: 'disk_agen3_op', allowBlank: false, type: 'text'},
            {name: 'disk_agen4_op', allowBlank: false, type: 'text'},
            {name: 'disk_modern_market1_op', allowBlank: false, type: 'text'},
            {name: 'disk_modern_market2_op', allowBlank: false, type: 'text'},
            {name: 'disk_modern_market3_op', allowBlank: false, type: 'text'},
            {name: 'disk_modern_market4_op', allowBlank: false, type: 'text'},
            {name: 'disk_toko1', allowBlank: false, type: 'float'},
            {name: 'disk_toko2', allowBlank: false, type: 'float'},
            {name: 'disk_toko3', allowBlank: false, type: 'float'},
            {name: 'disk_toko4', allowBlank: false, type: 'float'},
            {name: 'disk_amt_toko5', allowBlank: false, type: 'int'},
            {name: 'net_price_jual_toko', allowBlank: false, type: 'int'},
            {name: 'disk_agen1', allowBlank: false, type: 'float'},
            {name: 'disk_agen2', allowBlank: false, type: 'float'},
            {name: 'disk_agen3', allowBlank: false, type: 'float'},
            {name: 'disk_agen4', allowBlank: false, type: 'float'},
            {name: 'disk_amt_agen5', allowBlank: false, type: 'int'},
            {name: 'net_price_jual_agen', allowBlank: false, type: 'int'},
            {name: 'disk_modern_market1', allowBlank: false, type: 'float'},
            {name: 'disk_modern_market2', allowBlank: false, type: 'float'},
            {name: 'disk_modern_market3', allowBlank: false, type: 'float'},
            {name: 'disk_modern_market4', allowBlank: false, type: 'float'},
            {name: 'disk_amt_modern_market5', allowBlank: false, type: 'int'},
            {name: 'net_price_jual_modern_market', allowBlank: false, type: 'int'},
            {name: 'hrg_beli_satuan', allowBlank: false, type: 'int'},
            {name: 'rp_cogs', allowBlank: false, type: 'int'},
            {name: 'rp_het_cogs', allowBlank: false, type: 'int'},
            {name: 'hrg_supplier', allowBlank: false, type: 'int'},
            {name: 'net_hrg_supplier_sup_inc', allowBlank: false, type: 'int'},
            {name: 'net_hrg_supplier_dist_inc', allowBlank: false, type: 'int'},
            {name: 'rp_ongkos_kirim', allowBlank: false, type: 'int'},
            {name: 'margin_op', allowBlank: false, type: 'text'},
            {name: 'margin', allowBlank: false, type: 'int'},
            {name: 'pct_margin', allowBlank: false, type: 'int'},
            {name: 'rp_margin', allowBlank: false, type: 'int'},
            {name: 'rp_het_harga_beli', allowBlank: false, type: 'int'},
            {name: 'rp_het_harga_beli_dist', allowBlank: false, type: 'int'},
            {name: 'rp_jual_toko', allowBlank: false, type: 'int'},
            {name: 'rp_jual_toko_net', allowBlank: false, type: 'int'},
            {name: 'rp_jual_agen', allowBlank: false, type: 'int'},
            {name: 'rp_jual_modern_market', allowBlank: false, type: 'int'},
            {name: 'rp_jual_distribusi', allowBlank: false, type: 'int'},
            {name: 'qty_beli_bonus', allowBlank: false, type: 'int'},
            {name: 'kd_produk_bonus', allowBlank: false, type: 'text'},
            {name: 'qty_bonus', allowBlank: false, type: 'int'},
            {name: 'is_bonus_kelipatan', allowBlank: false, type: 'text'},
            {name: 'qty_agen', allowBlank: false, type: 'int'},
            {name: 'kd_produk_agen', allowBlank: false, type: 'text'},
            {name: 'qty_bonus', allowBlank: false, type: 'int'},
            {name: 'is_member_kelipatan', allowBlank: false, type: 'text'},
            {name: 'tanggal', allowBlank: false, type: 'text'},
            {name: 'keterangan', allowBlank: false, type: 'text'},
            {name: 'approve_by', allowBlank: false, type: 'text'},
            {name: 'tgl_approve', allowBlank: false, type: 'text'},
            {name: 'tgl_start_diskon', allowBlank: false, type: 'text'},
            {name: 'tgl_end_diskon', allowBlank: false, type: 'text'}
        ],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("harga_penjualan_distribusi/search_produk_history") ?>',
        method: 'POST'
    }),
    writer: new Ext.data.JsonWriter(
        {
            encode: true,
            writeAllFields: true
        })
});

var gridhargapenjualandistribusihistory = new Ext.grid.GridPanel({
    store: strhargapenjualandistribusihistory,
    stripeRows: true,
    height: 400,
    frame: true,
    border:true,
    columns: [{
        header: 'Koreksi Ke',
        dataIndex: 'koreksi_produk',
        hidden: true
    },{
        header: 'Tanggal',
        dataIndex: 'tanggal',
        width: 100,
        sortable: true
    },{
        header: 'Tanggal Approval',
        dataIndex: 'tgl_approve',
        width: 100,
        sortable: true
    },{
        header: 'Kode Barang',
        dataIndex: 'kd_produk',
        width: 100,
        sortable: true
    },{
        header: 'Nama Barang',
        dataIndex: 'nama_produk',
        width: 300,
        sortable: true
    },{
        header: 'Satuan',
        dataIndex: 'nm_satuan',
        width: 80
    },{
        header: 'Nama Supplier',
        dataIndex: 'nama_supplier',
        width: 200
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Net Price Beli (Inc.PPN)',
        // dataIndex: 'hrg_beli_satuan',           
        dataIndex: 'net_hrg_supplier_dist_inc',
        width: 150
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'COGS',
        // dataIndex: 'hrg_beli_satuan',           
        dataIndex: 'rp_cogs',
        width: 120
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Ongkos Kirim',
        dataIndex: 'rp_ongkos_kirim',
        width: 120
    },{
        header: '% / Rp',
        dataIndex: 'margin_op',
        width: 50
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Margin',
        dataIndex: 'margin',
        width: 100
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'HET Net Price Beli (Inc.PPN)',
        dataIndex: 'rp_het_harga_beli_dist',
        width: 180
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'HET COGS (Inc.PPN)',
        dataIndex: 'rp_het_cogs',
        width: 140
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Harga Jual Toko',
        dataIndex: 'rp_jual_toko',
        width: 180
    },/*{
     xtype: 'numbercolumn',
     align: 'right',
     format: '0,0',
     header: 'Harga Jual Distribusi',
     dataIndex: 'rp_jual_distribusi',           
     width: 180,
     },*/{
        header: '% / Rp',
        dataIndex: 'disk_toko1_op',
        width: 50
    },{
        xtype: 'numbercolumn',
        align: 'right',
        // format: '0,0',
        header: 'Diskon Toko 1',
        dataIndex: 'disk_toko1',
        width: 150
    },{
        header: '% / Rp',
        dataIndex: 'disk_toko2_op',
        width: 50
    },{
        xtype: 'numbercolumn',
        align: 'right',
        // format: '0,0',
        header: 'Diskon Toko 2',
        dataIndex: 'disk_toko2',
        width: 150
    },{
        header: '% / Rp',
        dataIndex: 'disk_toko3_op',
        width: 50
    },{
        xtype: 'numbercolumn',
        align: 'right',
        // format: '0,0',
        header: 'Diskon Toko 3',
        dataIndex: 'disk_toko3',
        width: 150
    },{
        header: '% / Rp',
        dataIndex: 'disk_toko4_op',
        width: 50
    },{
        xtype: 'numbercolumn',
        align: 'right',
        // format: '0,0',
        header: 'Diskon Toko 4',
        dataIndex: 'disk_toko4',
        width: 150
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Diskon Toko 5',
        dataIndex: 'disk_amt_toko5',
        width: 150
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Net Price Jual Toko',
        dataIndex: 'rp_jual_toko_net',
        width: 150
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Harga Jual Agen',
        dataIndex: 'rp_jual_agen',
        width: 150
    },{
        header: '% / Rp',
        dataIndex: 'disk_agen1_op',
        width: 50
    },{
        xtype: 'numbercolumn',
        align: 'right',
        // format: '0,0',
        header: 'Diskon Agen 1',
        dataIndex: 'disk_agen1',
        width: 150
    },{
        header: '% / Rp',
        dataIndex: 'disk_agen2_op',
        width: 50
    },{
        xtype: 'numbercolumn',
        align: 'right',
        // format: '0,0',
        header: 'Diskon Agen 2',
        dataIndex: 'disk_agen2',
        width: 150
    },{
        header: '% / Rp',
        dataIndex: 'disk_agen3_op',
        width: 50
    },{
        xtype: 'numbercolumn',
        align: 'right',
        // format: '0,0',
        header: 'Diskon Agen 3',
        dataIndex: 'disk_agen3',
        width: 150
    },{
        header: '% / Rp',
        dataIndex: 'disk_agen4_op',
        width: 50
    },{
        xtype: 'numbercolumn',
        align: 'right',
        // format: '0,0',
        header: 'Diskon Agen 4',
        dataIndex: 'disk_agen4',
        width: 150
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Diskon Agen 5',
        dataIndex: 'disk_amt_agen5',
        width: 150
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Net Price Jual Agen',
        dataIndex: 'net_price_jual_agen',
        width: 150
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Harga Jual Modern Market',
        dataIndex: 'rp_jual_modern_market',
        width: 150
    },{
        header: '% / Rp',
        dataIndex: 'disk_modern_market1_op',
        width: 50
    },{
        xtype: 'numbercolumn',
        align: 'right',
        // format: '0,0',
        header: 'Diskon Modern Market 1',
        dataIndex: 'disk_modern_market1',
        width: 150
    },{
        header: '% / Rp',
        dataIndex: 'disk_modern_market2_op',
        width: 50
    },{
        xtype: 'numbercolumn',
        align: 'right',
        // format: '0,0',
        header: 'Diskon Modern Market 2',
        dataIndex: 'disk_modern_market2',
        width: 150
    },{
        header: '% / Rp',
        dataIndex: 'disk_modern_market3_op',
        width: 50
    },{
        xtype: 'numbercolumn',
        align: 'right',
        // format: '0,0',
        header: 'Diskon Modern Market 3',
        dataIndex: 'disk_modern_market3',
        width: 150
    },{
        header: '% / Rp',
        dataIndex: 'disk_modern_market4_op',
        width: 50
    },{
        xtype: 'numbercolumn',
        align: 'right',
        // format: '0,0',
        header: 'Diskon Modern Market 4',
        dataIndex: 'disk_modern_market4',
        width: 150
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Diskon Modern Market 5',
        dataIndex: 'disk_amt_modern_market5',
        width: 150
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Net Price Jual Modern Market',
        dataIndex: 'net_price_jual_modern_market',
        width: 150
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Qty Beli |Toko|',
        dataIndex: 'qty_beli_bonus',
        width: 150
    },{
        header: 'Kd Produk |Toko|',
        dataIndex: 'kd_produk_bonus',
        width: 150
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Qty Bonus |Toko|',
        dataIndex: 'qty_bonus',
        width: 150
    },{
        header: 'Kelipatan ? |Toko|',
        dataIndex: 'is_bonus_kelipatan',
        width: 150
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Qty Beli |Agen|',
        dataIndex: 'qty_agen',
        width: 150
    },{
        header: 'Kd Produk |Agen|',
        dataIndex: 'kd_produk_agen',
        width: 150
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Qty Bonus |Agen|',
        dataIndex: 'qty_bonus',
        width: 150
    },{
        header: 'Kelipatan ? |Agen|',
        dataIndex: 'is_member_kelipatan',
        width: 150
    },{
        xtype: 'datecolumn',
        header: 'Tgl Mulai Diskon',
        dataIndex: 'tgl_start_diskon',
        format: 'd/m/Y',
        width: 120

    },{
        xtype: 'datecolumn',
        header: 'Tgl Akhir Diskon',
        dataIndex: 'tgl_end_diskon',
        format: 'd/m/Y',
        width: 120

    },{
        header: 'Ket. Perubahan',
        dataIndex: 'keterangan',
        width: 300
    },{
        header: 'Approved By',
        dataIndex: 'approve_by',
        width: 300
    }]
});

var winhargapenjualandistribusiprint = new Ext.Window({
    id: 'id_winhargapenjualandistribusiprint',
    title: 'Print History Harga Penjualan',
    closeAction: 'hide',
    width: 900,
    height: 450,
    layout: 'fit',
    border: false,
    html:'<iframe style="width:100%;height:100%;" id="hargapenjualandistribusiprint" src=""></iframe>'
});

Ext.ns('hargapenjualandistribusiform');
hargapenjualandistribusiform.Form = Ext.extend(Ext.form.FormPanel, {

    // defaults - can be changed from outside
    border: false,
    frame: true,
    labelWidth: 130,
    url: '<?= site_url("harga_penjualan_distribusi/update_row") ?>',
    constructor: function(config){
        config = config || {};
        config.listeners = config.listeners || {};
        Ext.applyIf(config.listeners, {
            actioncomplete: function(){
                //if (console && console.log) {
                //    console.log('actioncomplete:', arguments);
                //}
            },
            actionfailed: function(){
                //if (console && console.log) {
                //    console.log('actionfailed:', arguments);
                //}
            }
        });
        hargapenjualandistribusiform.Form.superclass.constructor.call(this, config);
    },
    initComponent: function(){

        // hard coded - cannot be changed from outsid
        var config = {
            layout:'form',
            items: [gridhargapenjualandistribusihistory],
            buttons: [{
                text: 'Cetak',
                id: 'btnCetakhargapenjualandistribusi',
                scope: this,
                handler: function(){
                    function isEmpty(str) {
                        return (!str || 0 === str.length);
                    }
                    var no_bukti = Ext.getCmp('id_cbhjdnobukti').getValue();
                    var kd_produk = Ext.getCmp('id_cbhjdproduk').getValue();

                    if(isEmpty(no_bukti)){
                        no_bukti = 0;
                    }
                    winhargapenjualandistribusiprint.show();
                    Ext.getDom('hargapenjualandistribusiprint').src = '<?= site_url("harga_penjualan_distribusi/print_form") ?>' +'/'+no_bukti+'/'+kd_produk;
                }
            },{
                text: 'Close',
                id: 'btnClosehargapenjualandistribusi',
                scope: this,
                handler: function(){
                    winshowhistoryhargapenjualandistribusi.hide();
                }
            }]
        }; // eo config object
        // apply config
        Ext.apply(this, Ext.apply(this.initialConfig, config));

        // call parent
        hargapenjualandistribusiform.Form.superclass.initComponent.apply(this, arguments);

    } // eo function initComponent  
    ,
    onRender: function(){

        // call parent
        hargapenjualandistribusiform.Form.superclass.onRender.apply(this, arguments);

        // set wait message target
        this.getForm().waitMsgTarget = this.getEl();

        // loads form after initial layout
        // this.on('afterlayout', this.onLoadClick, this, {single:true});

    } // eo function onRender
    ,
    showError: function(msg, title){
        title = title || 'Error';
        Ext.Msg.show({
            title: title,
            msg: msg,
            modal: true,
            icon: Ext.Msg.ERROR,
            buttons: Ext.Msg.OK,
            fn: function(btn){
                if (btn === 'ok' && msg === 'Session Expired') {
                    window.location = '<?= site_url("auth/login") ?>';
                }
            }
        });
    }
}); // eo extend
// register xtype
Ext.reg('formaddhargapenjualandistribusi', hargapenjualandistribusiform.Form);

var winshowhistoryhargapenjualandistribusi = new Ext.Window({
    id: 'id_winshowhistoryhargapenjualandistribusi',
    closeAction: 'hide',
    width: 1000,
    height: 500,
    layout: 'fit',
    border: false,
    items: {
        id: 'id_formaddhargapenjualandistribusi',
        xtype: 'formaddhargapenjualandistribusi'
    },
    onHide: function(){
        Ext.getCmp('id_formaddhargapenjualandistribusi').getForm().reset();
    }
});

var strcbkdprodukhjd = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: ['kd_produk', 'nama_produk', 'jml_stok'],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("master_barang/get_produk") ?>',
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

var searchhjdproduk = new Ext.app.SearchField({
    store: strcbkdprodukhjd,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 220,
    id: 'hjdsearchlistbarang'
});

var tbhjdproduk = new Ext.Toolbar({
    items: [searchhjdproduk]
});

var gridhjdsearchproduk = new Ext.grid.GridPanel({
    store: strcbkdprodukhjd,
    stripeRows: true,
    frame: true,
    border:true,
    columns: [{
        header: 'Kode Produk',
        dataIndex: 'kd_produk',
        width: 90,
        sortable: true

    },{
        header: 'Nama Produk',
        dataIndex: 'nama_produk',
        width: 340,
        sortable: true
    },{
        header: 'Qty',
        dataIndex: 'jml_stok',
        width: 50,
        sortable: true
    }],
    listeners: {
        'rowdblclick': function(){
            var sm = this.getSelectionModel();
            var sel = sm.getSelections();
            if (sel.length > 0) {
                Ext.Ajax.request({
                    url: '<?= site_url("master_barang/get_row_kode_produk") ?>',
                    method: 'POST',
                    params: {
                        kd_produk: sel[0].get('kd_produk')
                    },
                    callback:function(opt,success,responseObj){
                        var de = Ext.util.JSON.decode(responseObj.responseText);
                        if(de.success===true){
                            var senders = Ext.getCmp('hjd_gridsender').getValue();
                            if(senders === 'hjd_kd_produk_bonus'){
                                Ext.getCmp(senders).setValue(sel[0].get('kd_produk'));
                            }else if(senders === 'hjd_kd_produk_member'){
                                Ext.getCmp(senders).setValue(sel[0].get('kd_produk'));
                            }else if(senders === 'hjd_kd_produk_modern_market'){{
                                Ext.getCmp(senders).setValue(sel[0].get('kd_produk'));
                            }
                            }else{
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
                        }
                    }
                });
                menuhjd.hide();
            }
        }
    },
    tbar:tbhjdproduk,
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strcbkdprodukhjd,
        displayInfo: true
    })
});



var menuhjd = new Ext.menu.Menu();
menuhjd.add(new Ext.Panel({
    title: 'Pilih Produk',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 500,
    height: 400,
    closeAction: 'hide',
    plain: true,
    items: [gridhjdsearchproduk],
    buttons: [{
        text: 'Close',
        handler: function(){
            menuhjd.hide();
        }
    }]
}));

Ext.ux.TwinCombohjd = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function(){
        //load store grid
        Ext.getCmp('hjd_gridsender').setValue(this.id);
        strcbkdprodukhjd.load();
        menuhjd.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

var strhargapenjualandistribusi = new Ext.data.Store({
    autoSave:false,
    reader: new Ext.data.JsonReader({
        fields: [
            {name: 'kd_diskon_sales', allowBlank: true, type: 'text'},
            {name: 'koreksi_diskon', allowBlank: true, type: 'text'},
            {name: 'koreksi_produk', allowBlank: true, type: 'text'},
            {name: 'kd_produk_baru', allowBlank: false, type: 'text'},
            {name: 'kd_produk', allowBlank: false, type: 'text'},
            {name: 'kd_produk_lama', allowBlank: false, type: 'text'},
            {name: 'nama_produk', allowBlank: false, type: 'text'},
            {name: 'nm_satuan', allowBlank: false, type: 'text'},
            {name: 'nama_supplier', allowBlank: false, type: 'text'},
            {name: 'disk_toko1_op', allowBlank: false, type: 'text'},
            {name: 'disk_toko2_op', allowBlank: false, type: 'text'},
            {name: 'disk_toko3_op', allowBlank: false, type: 'text'},
            {name: 'disk_toko4_op', allowBlank: false, type: 'text'},
            {name: 'disk_agen1_op', allowBlank: false, type: 'text'},
            {name: 'disk_agen2_op', allowBlank: false, type: 'text'},
            {name: 'disk_agen3_op', allowBlank: false, type: 'text'},
            {name: 'disk_agen4_op', allowBlank: false, type: 'text'},
            {name: 'disk_modern_market1_op', allowBlank: false, type: 'text'},
            {name: 'disk_modern_market2_op', allowBlank: false, type: 'text'},
            {name: 'disk_modern_market3_op', allowBlank: false, type: 'text'},
            {name: 'disk_modern_market4_op', allowBlank: false, type: 'text'},
            {name: 'disk_toko1', allowBlank: false, type: 'float'},
            {name: 'disk_toko2', allowBlank: false, type: 'float'},
            {name: 'disk_toko3', allowBlank: false, type: 'float'},
            {name: 'disk_toko4', allowBlank: false, type: 'float'},
            {name: 'disk_amt_toko5', allowBlank: false, type: 'int'},
            {name: 'rp_jual_toko_net', allowBlank: false, type: 'int'},
            {name: 'disk_agen1', allowBlank: false, type: 'float'},
            {name: 'disk_agen2', allowBlank: false, type: 'float'},
            {name: 'disk_agen3', allowBlank: false, type: 'float'},
            {name: 'disk_agen4', allowBlank: false, type: 'float'},
            {name: 'disk_amt_agen5', allowBlank: false, type: 'int'},
            {name: 'rp_jual_agen_net', allowBlank: false, type: 'int'},
            {name: 'disk_modern_market1', allowBlank: false, type: 'float'},
            {name: 'disk_modern_market2', allowBlank: false, type: 'float'},
            {name: 'disk_modern_market3', allowBlank: false, type: 'float'},
            {name: 'disk_modern_market4', allowBlank: false, type: 'float'},
            {name: 'disk_amt_modern_market5', allowBlank: false, type: 'int'},
            {name: 'rp_jual_modern_market_net', allowBlank: false, type: 'int'},
            {name: 'hrg_beli_satuan', allowBlank: false, type: 'int'},
            {name: 'hrg_supplier', allowBlank: false, type: 'int'},
            {name: 'net_hrg_supplier_sup_inc', allowBlank: false, type: 'int'},
            {name: 'net_hrg_supplier_dist_inc', allowBlank: false, type: 'int'},
            {name: 'rp_ongkos_kirim', allowBlank: false, type: 'int'},
            {name: 'margin_op', allowBlank: false, type: 'text'},
            {name: 'margin', allowBlank: false, type: 'int'},
            {name: 'pct_margin', allowBlank: false, type: 'int'},
            {name: 'rp_margin', allowBlank: false, type: 'int'},
            {name: 'rp_het_harga_beli', allowBlank: false, type: 'int'},
            {name: 'rp_het_harga_beli_dist', allowBlank: false, type: 'int'},
            {name: 'rp_cogs', allowBlank: false, type: 'int'},
            {name: 'rp_het_cogs', allowBlank: false, type: 'int'},
            {name: 'p_rp_cogs', allowBlank: false, type: 'int'},
            {name: 'p_rp_het_cogs', allowBlank: false, type: 'int'},
            {name: 'rp_jual_toko', allowBlank: false, type: 'int'},
            {name: 'rp_jual_agen', allowBlank: false, type: 'int'},
            {name: 'rp_jual_modern_market', allowBlank: false, type: 'int'},
            {name: 'rp_jual_distribusi', allowBlank: false, type: 'int'},
            {name: 'qty_beli_bonus', allowBlank: false, type: 'int'},
            {name: 'kd_produk_bonus', allowBlank: false, type: 'text'},
            {name: 'qty_bonus', allowBlank: false, type: 'int'},
            {name: 'is_bonus_kelipatan', allowBlank: false, type: 'text'},
            {name: 'qty_agen', allowBlank: false, type: 'int'},
            {name: 'kd_produk_agen', allowBlank: false, type: 'text'},
            {name: 'qty_beli_agen', allowBlank: false, type: 'int'},
            {name: 'is_member_kelipatan', allowBlank: false, type: 'text'},
            {name: 'qty_modern_market', allowBlank: false, type: 'int'},
            {name: 'kd_produk_modern_market', allowBlank: false, type: 'text'},
            {name: 'qty_beli_modern_market', allowBlank: false, type: 'int'},
            {name: 'is_modern_market_kelipatan', allowBlank: false, type: 'text'},
            {name: 'keterangan', allowBlank: false, type: 'text'},
            {name: 'tgl_start_diskon', allowBlank: false, type: 'text'},
            {name: 'tgl_end_diskon', allowBlank: false, type: 'text'}
        ],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("harga_penjualan_distribusi/search_produk_by_kategori") ?>',
        method: 'POST'
    }),
    writer: new Ext.data.JsonWriter(
        {
            encode: true,
            writeAllFields: true
        })
});

strhargapenjualandistribusi.on('load',function(){
    strhargapenjualandistribusi.setBaseParam('kd_supplier',Ext.getCmp('id_cbhjdsuplier').getValue());
    strhargapenjualandistribusi.setBaseParam('kd_kategori1',Ext.getCmp('hjd_cbkategori1').getValue());
    strhargapenjualandistribusi.setBaseParam('kd_kategori2',Ext.getCmp('hjd_cbkategori2').getValue());
    strhargapenjualandistribusi.setBaseParam('kd_kategori3',Ext.getCmp('hjd_cbkategori3').getValue());
    strhargapenjualandistribusi.setBaseParam('kd_kategori4',Ext.getCmp('hjd_cbkategori4').getValue());
    strhargapenjualandistribusi.setBaseParam('kd_ukuran',Ext.getCmp('id_hjd_cbukuran').getValue());
    strhargapenjualandistribusi.setBaseParam('no_bukti',Ext.getCmp('id_cbhjdnobuktifilter').getValue());
    strhargapenjualandistribusi.setBaseParam('konsinyasi',Ext.getCmp('hjd_konsinyasi').getValue());
});

strhargapenjualandistribusi.on('update',function(){
    var net_price = Ext.getCmp('ehjd_hrg_beli_satuan').getValue();
    var edited = Ext.getCmp('hjd_edited').getValue();
    if(net_price === 0 && edited === 'Y'){
        Ext.Msg.show({
            title: 'Warning',
            msg: 'Net Price Pembelian Masih 0',
            modal: true,
            icon: Ext.Msg.ERROR,
            buttons: Ext.Msg.OK
        });
        return;
    }
    if(Ext.getCmp('ehjd_het_cogs').getValue() === 0){
        if(Ext.getCmp('ehjd_rp_jual_toko').getValue() < Ext.getCmp('ehjd_rp_het_harga_beli').getValue()){
            Ext.getCmp('ehjd_rp_jual_toko').setValue('0');
            Ext.Msg.show({
                title: 'Error',
                msg: 'Net Price Jual tidak boleh lebih kecil dari HET Net Price Beli (inc PPN 1)',
                modal: true,
                icon: Ext.Msg.ERROR,
                buttons: Ext.Msg.OK,
                fn: function(btn){
                    Ext.getCmp('ehjd_rp_jual_toko').focus();
                }
            });
            Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
        }
        if(Ext.getCmp('ehjd_rp_cogs').getValue() > 0){
            if(Ext.getCmp('ehjd_net_price_jual_toko').getValue() < Ext.getCmp('ehjd_rp_cogs').getValue()){
                Ext.getCmp('ehjd_net_price_jual_toko').setValue('0');
                Ext.Msg.show({
                    title: 'Error',
                    msg: 'Net Price Jual tidak boleh lebih kecil dari HET COGS (inc PPN)',
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK,
                    fn: function(btn){
                        Ext.getCmp('ehjd_net_price_jual_toko').focus();
                    }
                });
                Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
            }
        }
    }else{
        if(Ext.getCmp('ehjd_rp_jual_toko').getValue() < Ext.getCmp('ehjd_het_cogs').getValue()){
            Ext.getCmp('ehjd_rp_jual_toko').setValue('0');
            Ext.Msg.show({
                title: 'Error',
                msg: 'Net Price Jual tidak boleh lebih kecil dari HET COGS (inc PPN)',
                modal: true,
                icon: Ext.Msg.ERROR,
                buttons: Ext.Msg.OK,
                fn: function(btn){
                    Ext.getCmp('ehjd_rp_jual_toko').focus();
                }
            });
            Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
        }
        //if(Ext.getCmp('ehjd_net_price_jual_toko').getValue() < Ext.getCmp('ehjd_rp_het_harga_beli').getValue()){
        //	Ext.getCmp('ehjd_net_price_jual_toko').setValue('0');
        //	Ext.Msg.show({
        //		title: 'Error',
        //		msg: 'Net Price Jual tidak boleh lebih kecil dari HET Net Price Beli (inc PPN 3)',
        //		modal: true,
        //		icon: Ext.Msg.ERROR,
        //		buttons: Ext.Msg.OK,
        //		fn: function(btn){
        //			Ext.getCmp('ehjd_net_price_jual_toko').focus();
        //		}
        //	});
        //	Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');  
        //}

    }
    /*
     if(Ext.getCmp('ehjd_rp_jual_distribusi').getValue() < Ext.getCmp('ehjd_rp_het_harga_beli').getValue()){
     Ext.getCmp('ehjd_rp_jual_distribusi').setValue('0');
     Ext.Msg.show({
     title: 'Error',
     msg: 'Harga Jual Tidak Boleh Lebih Kecil Dari HET Net Price Beli',
     modal: true,
     icon: Ext.Msg.ERROR,
     buttons: Ext.Msg.OK,
     fn: function(btn){
     Ext.getCmp('ehjd_rp_jual_distribusi').focus();
     }
     });
     Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');  
     }
     */
});

function HETChangeDist(){
    var hrg_beli = Ext.getCmp('ehjd_hrg_beli_satuan').getValue();
    var cogs = Ext.getCmp('ehjd_rp_cogs').getValue();
    var ongkos = Ext.getCmp('ehjd_rp_ongkos_kirim').getValue();
    var margin_op = Ext.getCmp('hjd_margin_op').getValue();
    var margin = Ext.getCmp('ehjd_margin').getValue();
    var margin_rp = 0;
    if(margin_op === "%"){
        margin_rp = (margin*hrg_beli)/100;
        margin_pct = margin;
    }else{
        margin_rp = margin;
        margin_pct = (margin*100)/hrg_beli;
    }
    ongkos = ongkos+(ongkos*0.1);
    var HET = hrg_beli+ongkos+margin_rp;


    if(margin_op === "%"){
        margin_rp = (margin*cogs)/100;
        margin_pct = margin;
    }else{
        margin_rp = margin;
        margin_pct = (margin*100)/cogs;
    }
    ongkos = Ext.getCmp('ehjd_rp_ongkos_kirim').getValue();
    var HETCOGS = (cogs+ongkos+margin_rp) * 1.1;
    if(cogs === 0){
        HETCOGS = 0;
    }
    Ext.getCmp('ehjd_rp_het_harga_beli').setValue(HET);
    Ext.getCmp('ehjd_het_cogs').setValue(HETCOGS);
    Ext.getCmp('ehjd_pct_margin').setValue(margin_pct);
    Ext.getCmp('ehjd_rp_margin').setValue(margin_rp);
    Edited_hj_dis();
};

function Edited_hj_dis(){
    Ext.getCmp('hjd_edited').setValue('Y');
};

function HitungNetPJualDist(){
    Edited_hj_dis();
    var total_disk = 0;
    var rp_jual_toko = Ext.getCmp('ehjd_rp_jual_toko').getValue();
    var disk_toko1_op = Ext.getCmp('hjd_disk_toko1_op').getValue();
    var disk_toko1 = Ext.getCmp('hjd_disk_toko1').getValue();
    if (disk_toko1_op === '%'){
        // disk_toko1 = (disk_toko1*rp_jual_toko)/100;
        total_disk = rp_jual_toko-(rp_jual_toko*(disk_toko1/100));
    }else{
        total_disk = rp_jual_toko-disk_toko1;
    }

    var disk_toko2_op = Ext.getCmp('hjd_disk_toko2_op').getValue();
    var disk_toko2 = Ext.getCmp('hjd_disk_toko2').getValue();
    if (disk_toko2_op === '%'){
        // disk_toko2 = (disk_toko2*disk_toko1)/100;
        total_disk =  total_disk-(total_disk*(disk_toko2/100));
    }else{
        total_disk = total_disk-disk_toko2;
    }

    var disk_toko3_op = Ext.getCmp('hjd_disk_toko3_op').getValue();
    var disk_toko3 = Ext.getCmp('hjd_disk_toko3').getValue();
    if (disk_toko3_op === '%'){
        // disk_toko3 = (disk_toko3*disk_toko2)/100;
        total_disk = total_disk-(total_disk*(disk_toko3/100));
    }else{
        total_disk = total_disk-disk_toko3;
    }

    var disk_toko4_op = Ext.getCmp('hjd_disk_toko4_op').getValue();
    var disk_toko4 = Ext.getCmp('hjd_disk_toko4').getValue();
    if (disk_toko4_op === '%'){
        // disk_toko4 = (disk_toko4*disk_toko3)/100;
        total_disk = total_disk-(total_disk*(disk_toko4/100));
    }else{
        total_disk = total_disk-disk_toko4;
    }

    var total_disk = total_disk-Ext.getCmp('hjd_disk_toko5').getValue();

    var net_jual_kons = total_disk;
    Ext.getCmp('ehjd_net_price_jual_toko').setValue(net_jual_kons);

    Ext.getCmp('ehjd_rp_jual_agen').setValue(rp_jual_toko);
    var rp_jual_agen = Ext.getCmp('ehjd_rp_jual_agen').getValue();
    var disk_agen1_op = Ext.getCmp('hjd_disk_agen1_op').getValue();
    var disk_agen1 = Ext.getCmp('hjd_disk_agen1').getValue();
    if (disk_agen1_op === '%'){
        // disk_agen1 = (disk_agen1*rp_jual_toko)/100;
        total_disk = rp_jual_agen-(rp_jual_toko*(disk_agen1/100));
    }else{
        total_disk = rp_jual_agen-disk_agen1;
    }

    var disk_agen2_op = Ext.getCmp('hjd_disk_agen2_op').getValue();
    var disk_agen2 = Ext.getCmp('hjd_disk_agen2').getValue();
    if (disk_agen2_op === '%'){
        // disk_agen2 = (disk_agen2*disk_agen1)/100;
        total_disk = total_disk-(total_disk*(disk_agen2/100));
    }else{
        total_disk = total_disk-disk_agen2;
    }

    var disk_agen3_op = Ext.getCmp('hjd_disk_agen3_op').getValue();
    var disk_agen3 = Ext.getCmp('hjd_disk_agen3').getValue();
    if (disk_agen3_op === '%'){
        // disk_agen3 = (disk_agen3*disk_agen2)/100;
        total_disk = total_disk-(total_disk*(disk_agen3/100));
    }else{
        total_disk = total_disk-disk_agen3;
    }

    var disk_agen4_op = Ext.getCmp('hjd_disk_agen4_op').getValue();
    var disk_agen4 = Ext.getCmp('hjd_disk_agen4').getValue();
    if (disk_agen4_op === '%'){
        // disk_agen4 = (disk_agen4*disk_agen3)/100;
        total_disk = total_disk-(total_disk*(disk_agen4/100));
    }else{
        total_disk = total_disk-disk_agen4;
    }

    var total_disk = total_disk - Ext.getCmp('hjd_disk_amt_agen5').getValue();

    var net_price_memb = total_disk;
    Ext.getCmp('ehjd_net_price_jual_agen').setValue(net_price_memb);
    
    Ext.getCmp('ehjd_rp_jual_modern_market').setValue(rp_jual_toko);
    var rp_jual_modern_market = Ext.getCmp('ehjd_rp_jual_modern_market').getValue();
    var disk_modern_market1_op = Ext.getCmp('hjd_disk_modern_market1_op').getValue();
    var disk_modern_market1 = Ext.getCmp('hjd_disk_modern_market1').getValue();
    if (disk_modern_market1_op === '%'){
        // disk_agen1 = (disk_agen1*rp_jual_toko)/100;
        total_disk = rp_jual_modern_market-(rp_jual_modern_market*(disk_modern_market1/100));
    }else{
        total_disk = rp_jual_modern_market-disk_modern_market1;
    }

    var disk_modern_market2_op = Ext.getCmp('hjd_disk_modern_market2_op').getValue();
    var disk_modern_market2 = Ext.getCmp('hjd_disk_modern_market2').getValue();
    if (disk_modern_market2_op === '%'){
        // disk_agen2 = (disk_agen2*disk_agen1)/100;
        total_disk = total_disk-(total_disk*(disk_modern_market2/100));
    }else{
        total_disk = total_disk-disk_modern_market2;
    }

    var disk_modern_market3_op = Ext.getCmp('hjd_disk_modern_market3_op').getValue();
    var disk_modern_market3 = Ext.getCmp('hjd_disk_modern_market3').getValue();
    if (disk_modern_market3_op === '%'){
        // disk_agen3 = (disk_agen3*disk_agen2)/100;
        total_disk = total_disk-(total_disk*(disk_modern_market3/100));
    }else{
        total_disk = total_disk-disk_modern_market3;
    }

    var disk_modern_market4_op = Ext.getCmp('hjd_disk_modern_market4_op').getValue();
    var disk_modern_market4 = Ext.getCmp('hjd_disk_modern_market4').getValue();
    if (disk_modern_market4_op === '%'){
        // disk_agen4 = (disk_agen4*disk_agen3)/100;
        total_disk = total_disk-(total_disk*(disk_modern_market4/100));
    }else{
        total_disk = total_disk-disk_modern_market4;
    }

    var total_disk = total_disk - Ext.getCmp('hjd_disk_amt_modern_market5').getValue();

    var net_price_memb = total_disk;
    Ext.getCmp('ehjd_net_price_jual_modern_market').setValue(net_price_memb);
}
// combobox kategori1
var str_hjd_cbkategori1 = new Ext.data.Store({
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
            var r = new (str_hjd_cbkategori1.recordType)({
                'kd_kategori1': '',
                'nama_kategori1': '-----'
            });
            str_hjd_cbkategori1.insert(0, r);
        },
        loadexception: function(event, options, response, error){
            var err = Ext.util.JSON.decode(response.responseText);
            if (err.errMsg === 'Session Expired') {
                session_expired(err.errMsg);
            }
        }
    }
});

var hjd_cbkategori1 = new Ext.form.ComboBox({
    fieldLabel: 'Kategori 1',
    id: 'hjd_cbkategori1',
    store: str_hjd_cbkategori1,
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
            var kdhjd_cbkategori1 = hjd_cbkategori1.getValue();
            // hjd_cbkategori2.setValue();
            hjd_cbkategori2.store.proxy.conn.url = '<?= site_url("kategori3/get_kategori2") ?>/' + kdhjd_cbkategori1;
            hjd_cbkategori2.store.reload();

        }
    }
});
// combobox kategori2
var str_hjd_cbkategori2 = new Ext.data.Store({
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
            var r = new (str_hjd_cbkategori2.recordType)({
                'kd_kategori2': '',
                'nama_kategori2': '-----'
            });
            str_hjd_cbkategori2.insert(0, r);
        },
        loadexception: function(event, options, response, error){
            var err = Ext.util.JSON.decode(response.responseText);
            if (err.errMsg === 'Session Expired') {
                session_expired(err.errMsg);
            }
        }
    }
});

var hjd_cbkategori2 = new Ext.form.ComboBox({
    fieldLabel: 'Kategori 2 ',
    id: 'hjd_cbkategori2',
    mode: 'local',
    store: str_hjd_cbkategori2,
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
            var kd_hjd_cbkategori1 = hjd_cbkategori1.getValue();
            var kd_hjd_cbkategori2 = this.getValue();
            hjd_cbkategori3.setValue();
            hjd_cbkategori3.store.proxy.conn.url = '<?= site_url("kategori4/get_kategori3") ?>/' + kd_hjd_cbkategori1 +'/'+ kd_hjd_cbkategori2;
            hjd_cbkategori3.store.reload();

        }
    }
});

// combobox kategori3
var str_hjd_cbkategori3 = new Ext.data.Store({
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
            var r = new (str_hjd_cbkategori3.recordType)({
                'kd_kategori3': '',
                'nama_kategori3': '-----'
            });
            str_hjd_cbkategori3.insert(0, r);
        },
        loadexception: function(event, options, response, error){
            var err = Ext.util.JSON.decode(response.responseText);
            if (err.errMsg === 'Session Expired') {
                session_expired(err.errMsg);
            }
        }
    }
});

var hjd_cbkategori3 = new Ext.form.ComboBox({
    fieldLabel: 'Kategori 3 ',
    id: 'hjd_cbkategori3',
    mode: 'local',
    store: str_hjd_cbkategori3,
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
            var kd_hjd_cbkategori1 = hjd_cbkategori1.getValue();
            var kd_hjd_cbkategori2 = hjd_cbkategori2.getValue();
            var kd_hjd_cbkategori3 = this.getValue();
            hjd_cbkategori4.setValue();
            hjd_cbkategori4.store.proxy.conn.url = '<?= site_url("master_barang/get_kategori4") ?>/' + kd_hjd_cbkategori1 +'/'+ kd_hjd_cbkategori2 +'/'+ kd_hjd_cbkategori3;
            hjd_cbkategori4.store.reload();


        }
    }
});

// combobox kategori4
var str_hjd_cbkategori4 = new Ext.data.Store({
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
            var r = new (str_hjd_cbkategori4.recordType)({
                'kd_kategori4': '',
                'nama_kategori4': '-----'
            });
            str_hjd_cbkategori4.insert(0, r);
        },
        loadexception: function(event, options, response, error){
            var err = Ext.util.JSON.decode(response.responseText);
            if (err.errMsg === 'Session Expired') {
                session_expired(err.errMsg);
            }
        }
    }
});

var hjd_cbkategori4 = new Ext.form.ComboBox({
    fieldLabel: 'Kategori 4 ',
    id: 'hjd_cbkategori4',
    mode: 'local',
    store: str_hjd_cbkategori4,
    valueField: 'kd_kategori4',
    displayField: 'nama_kategori4',
    typeAhead: true,
    triggerAction: 'all',
    // allowBlank: false,
    editable: false,
    width: 170,
    anchor: '90%',
    hiddenName: 'nama_kategori4',
    emptyText: 'Pilih kategori 4',
    listeners: {
        select: function(combo, records) {
        }
    }
});

// combobox Ukuran
var str_hjd_cbukuran = new Ext.data.Store({
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
            var r = new (str_hjd_cbukuran.recordType)({
                'kd_ukuran': '',
                'nama_ukuran': '-----'
            });
            str_hjd_cbukuran.insert(0, r);
        },
        loadexception: function(event, options, response, error){
            var err = Ext.util.JSON.decode(response.responseText);
            if (err.errMsg === 'Session Expired') {
                session_expired(err.errMsg);
            }
        }
    }
});

// combobox Satuan
var str_hjd_cbsatuan = new Ext.data.Store({
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
            var r = new (str_hjd_cbsatuan.recordType)({
                'kd_satuan': '',
                'nm_satuan': '-----'
            });
            str_hjd_cbsatuan.insert(0, r);
        },
        loadexception: function(event, options, response, error){
            var err = Ext.util.JSON.decode(response.responseText);
            if (err.errMsg === 'Session Expired') {
                session_expired(err.errMsg);
            }
        }
    }
});
var hjd_cbsatuan = new Ext.form.ComboBox({
    fieldLabel: 'Satuan',
    id: 'id_hjd_cbsatuan',
    store: str_hjd_cbsatuan,
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

var hjd_cbukuran = new Ext.form.ComboBox({
    fieldLabel: 'Ukuran',
    id: 'id_hjd_cbukuran',
    store: str_hjd_cbukuran,
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

var searchgridhargapenjualandistribusi = new Ext.app.SearchField({
    store: strhargapenjualandistribusi,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridhargapenjualandistribusi',
    emptyText: 'Kode Barang, Kode Barang Lama, Nama Barang'
});

searchgridhargapenjualandistribusi.onTrigger1Click = function(evt) {
    if (this.hasSearch) {
        this.el.dom.value = '';

        // Get the value of search field
        var kd_kategori1 = Ext.getCmp('hjd_cbkategori1').getValue();
        var kd_kategori2 = Ext.getCmp('hjd_cbkategori2').getValue();
        var kd_kategori3 = Ext.getCmp('hjd_cbkategori3').getValue();
        var kd_kategori4 = Ext.getCmp('hjd_cbkategori4').getValue();
        var konsinyasi = Ext.getCmp('hjd_konsinyasi').getValue();
        var kd_supplier = Ext.getCmp('id_cbhjdsuplier').getValue();
        var list = Ext.getCmp('ehjd_list').getValue();
        if(!kd_supplier){
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
        var o = { 	start: 0,
            kd_kategori1: kd_kategori1,
            kd_kategori2: kd_kategori2,
            kd_kategori3: kd_kategori3,
            kd_kategori4: kd_kategori4,
            konsinyasi: konsinyasi,
            kd_supplier: kd_supplier,
            list: list
        };

        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = '';
        this.store.reload({
            params : o
        });
        this.triggers[0].hide();
        this.hasSearch = false;
    }
};

searchgridhargapenjualandistribusi.onTrigger2Click = function(evt) {
    var text = this.getRawValue();
    if (text.length < 1) {
        this.onTrigger1Click();
        return;
    }

    // Get the value of search field
    var kd_kategori1 = Ext.getCmp('hjd_cbkategori1').getValue();
    var kd_kategori2 = Ext.getCmp('hjd_cbkategori2').getValue();
    var kd_kategori3 = Ext.getCmp('hjd_cbkategori3').getValue();
    var kd_kategori4 = Ext.getCmp('hjd_cbkategori4').getValue();
    var konsinyasi = Ext.getCmp('hjd_konsinyasi').getValue();
    var kd_supplier = Ext.getCmp('id_cbhjdsuplier').getValue();
    var list = Ext.getCmp('ehjd_list').getValue();
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
    var o = { 	start: 0,
        kd_kategori1: kd_kategori1,
        kd_kategori2: kd_kategori2,
        kd_kategori3: kd_kategori3,
        kd_kategori4: kd_kategori4,
        konsinyasi: konsinyasi,
        kd_supplier: kd_supplier,
        list: list
    };

    this.store.baseParams = this.store.baseParams || {};
    this.store.baseParams[this.paramName] = text;
    this.store.reload({params:o});
    this.hasSearch = true;
    this.triggers[0].show();
};

var strcbhjdsuplier = new Ext.data.ArrayStore({
    fields: ['kd_supplier'],
    data : []
});

var strgridhjdsuplier = new Ext.data.Store({
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

var searchgridhjdsuplier = new Ext.app.SearchField({
    store: strgridhjdsuplier,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridhjdsuplier'
});

strgridhjdsuplier.on('load', function(){
    Ext.getCmp('id_searchgridhjdsuplier').focus();
});

var gridhjdsuplier = new Ext.grid.GridPanel({
    store: strgridhjdsuplier,
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
        width: 300,
        sortable: true
    },{
        header: 'PKP',
        dataIndex: 'pkp',
        width: 300,
        sortable: true
    }],
    tbar: new Ext.Toolbar({
        items: [searchgridhjdsuplier]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridhjdsuplier,
        displayInfo: true
    }),listeners: {
        'rowdblclick': function(){
            var sm = this.getSelectionModel();
            var sel = sm.getSelections();
            if (sel.length > 0) {
                Ext.getCmp('id_cbhjdsuplier').setValue(sel[0].get('kd_supplier'));
                Ext.getCmp('hjd_nama_supplier').setValue(sel[0].get('nama_supplier'));
                if(sel[0].get('pkp') === '1'){
                    Ext.getCmp('hjd_pkp').setValue('YA');
                }else{
                    Ext.getCmp('hjd_pkp').setValue('TIDAK');
                }


                menuhjdsuplier.hide();
            }
        }
    }
});

var menuhjdsuplier = new Ext.menu.Menu();
menuhjdsuplier.add(new Ext.Panel({
    title: 'Pilih Supplier',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridhjdsuplier],
    buttons: [{
        text: 'Close',
        handler: function(){
            menuhjdsuplier.hide();
        }
    }]
}));

Ext.ux.TwinCombohjdsuplier = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function(){
        //load store grid
        strgridhjdsuplier.load();
        menuhjdsuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuhjdsuplier.on('hide', function(){
    var sf = Ext.getCmp('id_searchgridhjdsuplier').getValue();
    if( sf !== ''){
        Ext.getCmp('id_searchgridhjdsuplier').setValue('');
        searchgridhjdsuplier.onTrigger2Click();
    }
});

var cbhjdsuplier = new Ext.ux.TwinCombohjdsuplier({
    fieldLabel: 'Supplier <span class="asterix">*</span>',
    id: 'id_cbhjdsuplier',
    store: strcbhjdsuplier,
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

var headerhargapenjualandistribusidist = {
    layout: 'column',
    border: false,
    buttonAlign:'left',
    items: [{
        columnWidth: .5,
        layout: 'form',
        border: false,
        labelWidth: 100,
        defaults: { labelSeparator: ''},
        items: [{
            xtype: 'hidden',
            name: 'gridsender',
            id: 'hjd_gridsender'
        },{
            xtype: 'textfield',
            fieldLabel: 'No Bukti',
            name: 'no_hjd',
            readOnly:true,
            fieldClass:'readonly-input',
            id: 'hjd_no_hjd',
            anchor: '90%',
            value:''
        },cbhjdsuplier,cbhjdnobuktifilter,
            hjd_cbkategori1,hjd_cbkategori2,{
                xtype: 'textarea',
                fieldLabel: 'Kode Barang, Kode Barang Lama',
                style:'text-transform: uppercase',
                name: 'list',
                id: 'ehjd_list',
                anchor: '90%'
            },{
                xtype: 'label',
                text: '*) Tidak Boleh Ada Spasi dan Enter',
                style: 'margin-left:100px'
            },new Ext.form.Checkbox({
                xtype: 'checkbox',
                fieldLabel: 'Hanya Konsinyasi',
                boxLabel:'Ya',
                name:'is_konsinyasi',
                id:'hjd_konsinyasi',
                inputValue: '1',
                autoLoad : true
            })
            ,{
                xtype: 'label',
                text: '*) Item Barang dengan Harga Beli Nol (0) tidak muncul pada table di bawah',
                style: 'margin-left:100px'
            }
        ]
    }, {
        columnWidth: .5,
        layout: 'form',
        border: false,
        labelWidth: 100,
        defaults: { labelSeparator: ''},
        items: [ {
            xtype: 'datefield',
            fieldLabel: 'Tanggal ',
            name: 'tanggal',
            allowBlank:false,
            format:'d-m-Y',
            editable:false,
            id: 'hjd_tanggal',
            anchor: '90%',
            value: ''
        },{
            xtype: 'textfield',
            fieldLabel: 'Nama Supplier',
            name: 'nama_supplier',
            readOnly:true,
            fieldClass:'readonly-input',
            id: 'hjd_nama_supplier',
            anchor: '90%',
            value:''
        },{
            xtype: 'textfield',
            fieldLabel: 'Status PKP',
            name: 'pkp',
            readOnly:true,
            fieldClass:'readonly-input',
            id: 'hjd_pkp',
            anchor: '90%',
            value:''
        },hjd_cbkategori3,hjd_cbkategori4,hjd_cbukuran,hjd_cbsatuan

        ]
    }]
    ,
    buttons: [{
        text: 'Filter',
        formBind: true,
        handler: function(){
            var kd_supplier =  Ext.getCmp('id_cbhjdsuplier').getValue();
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
            strhargapenjualandistribusi.load({
                params:{
                    start: STARTPAGE,
                    limit: ENDPAGE,
                    kd_supplier: Ext.getCmp('id_cbhjdsuplier').getValue(),
                    kd_kategori1: Ext.getCmp('hjd_cbkategori1').getValue(),
                    kd_kategori2: Ext.getCmp('hjd_cbkategori2').getValue(),
                    kd_kategori3: Ext.getCmp('hjd_cbkategori3').getValue(),
                    kd_kategori4: Ext.getCmp('hjd_cbkategori4').getValue(),
                    kd_ukuran: Ext.getCmp('id_hjd_cbukuran').getValue(),
                    kd_satuan: Ext.getCmp('id_hjd_cbsatuan').getValue(),
                    konsinyasi: Ext.getCmp('hjd_konsinyasi').getValue(),
                    no_bukti: Ext.getCmp('id_cbhjdnobuktifilter').getValue(),
                    list: Ext.getCmp('ehjd_list').getValue()
                }
            });
        }
    }]
};

var actionhargapenjualandistribusi = new Ext.ux.grid.RowActions({
    header :'History',
    autoWidth: false,
    locked: true,
    width: 60,
    actions:[{iconCls: 'icon-history-record', qtip: 'Show History'}],
    widthIntercept: Ext.isSafari ? 4 : 2
});


actionhargapenjualandistribusi.on('action', function(grid, record, action, row, col) {
    var kd_supp = record.get('kd_supplier');
    var kd_prod = record.get('kd_produk');
    var nm_prod = record.get('nama_produk');
    switch(action) {
        case 'icon-history-record':
            var sm = gridhargapenjualandistribusi.getSelectionModel();
            var sel = sm.getSelections();
            gridhargapenjualandistribusihistory.store.proxy.conn.url = '<?= site_url("harga_penjualan_distribusi/search_produk_history") ?>/' +sel[0].get('kd_produk');
            gridhargapenjualandistribusihistory.store.reload();
            winshowhistoryhargapenjualandistribusi.setTitle('History');
            winshowhistoryhargapenjualandistribusi.show();
            break;
    }
});
var editorhargapenjualandistribusi = new Ext.ux.grid.RowEditor({
    saveText: 'Update'
});

var gridhargapenjualandistribusi = new Ext.grid.GridPanel({
    store: strhargapenjualandistribusi,
    stripeRows: true,
    height: 350,
    loadMask: true,
    frame: true,
    border:true,
    plugins: [editorhargapenjualandistribusi],
    columns: [ {
        dataIndex: 'kd_diskon_sales',
        hidden: true
    },{
        dataIndex: 'pct_margin',
        hidden: true,
        editor: new Ext.form.TextField({
            readOnly: true,
            id: 'ehjd_pct_margin'
        })
    },{
        dataIndex: 'rp_margin',
        hidden: true,
        editor: new Ext.form.TextField({
            readOnly: true,
            id: 'ehjd_rp_margin'
        })
    },{
        dataIndex: 'koreksi_diskon',
        hidden: true
    },{
        dataIndex: 'koreksi_produk',
        hidden: true
    },{
        
        header: 'Edited',
        dataIndex: 'edited',
        width: 50,
        sortable: true,
        editor: {
            xtype:          'combo',
            store:          new Ext.data.JsonStore({
                fields : ['name'],
                data   : [
                    {name : 'Y'},
                    {name : 'No'}
                ]
            }),
            id:           	'hjd_edited',
            mode:           'local',
            name:           'edited',
            value:          '%',
            width:			50,
            editable:       false,
            hiddenName:     'edited',
            valueField:     'name',
            displayField:   'name',
            triggerAction:  'all',
            forceSelection: true
            
        }
    },{
        header: 'Kode Barang',
        dataIndex: 'kd_produk_baru',
        width: 100,
        sortable: true,
        editor: new Ext.form.TextField({
            readOnly: true,
            fieldClass: 'readonly-input',
            id: 'ehjd_kd_produk'
        })
    },{
        header: 'Kode Barang Lama',
        dataIndex: 'kd_produk_lama',
        width: 110,
        sortable: true,
        editor: new Ext.form.TextField({
            readOnly: true,
            fieldClass: 'readonly-input',
            id: 'ehjd_kd_produk_lama'
        })
    },{
        header: 'Nama Barang',
        dataIndex: 'nama_produk',
        width: 300,
        sortable: true,
        editor: new Ext.form.TextField({
            readOnly: true,
            fieldClass: 'readonly-input',
            id: 'ehjd_nama_produk'
        })
    },{
        header: 'Satuan',
        dataIndex: 'nm_satuan',
        width: 80,
        editor: new Ext.form.TextField({
            readOnly: true,
            fieldClass: 'readonly-input',
            id: 'ehjd_satuan'
        })
    },{
        header: 'Nama Supplier',
        dataIndex: 'nama_supplier',
        width: 130,
        editor: new Ext.form.TextField({
            readOnly: true,
            fieldClass: 'readonly-input',
            id: 'ehjd_nama_supplier'
        })
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Net Price Pembelian',
        dataIndex: 'net_hrg_supplier_dist_inc',
        width: 150,
        editor: {
            xtype: 'numberfield',
            id: 'ehjd_hrg_beli_satuan',
            readOnly: true,
            fieldClass: 'readonly-input'
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'COGS',
        dataIndex: 'p_rp_cogs',
        width: 100,
        editor: {
            xtype: 'numberfield',
            id: 'ehjd_rp_cogs',
            readOnly: true,
            fieldClass: 'readonly-input'
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Ongkos Kirim',
        dataIndex: 'rp_ongkos_kirim',
        width: 140,
        editor: {
            xtype: 'numberfield',
            id: 'ehjd_rp_ongkos_kirim',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        HETChangeDist();
                    }, c);
                }
            }
        }
    },{
        header: '% / Rp',
        dataIndex: 'margin_op',
        width: 50,
        editor: {
            xtype:          'combo',
            store:          new Ext.data.JsonStore({
                fields : ['name'],
                data   : [
                    {name : '%'},
                    {name : 'Rp'}
                ]
            }),
            id:           	'hjd_margin_op',
            mode:           'local',
            name:           'margin_op',
            value:          '%',
            width:			50,
            editable:       false,
            hiddenName:     'margin_op',
            valueField:     'name',
            displayField:   'name',
            triggerAction:  'all',
            forceSelection: true,
            listeners:{
                'expand':function(){
                    Ext.getCmp('ehjd_margin').setValue(0);
                    HETChangeDist();
                },
                select:function(){
                    HETChangeDist();
                    Ext.getCmp('ehjd_margin').setMaxValue(Number.MAX_VALUE);
                    if (this.getValue() === 'persen')
                        Ext.getCmp('ehjd_margin').maxValue = 100;
                    else
                        Ext.getCmp('ehjd_margin').maxLength = 11;
                }
            }
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Margin',
        dataIndex: 'margin',
        width: 100,
        editor: {
            xtype: 'numberfield',
            id: 'ehjd_margin',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        HETChangeDist();
                    }, c);
                }
            }
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'HET Net Price Beli (Inc.PPN)',
        dataIndex: 'rp_het_harga_beli_dist',
        width: 180,
        editor: {
            xtype: 'numberfield',
            id: 'ehjd_rp_het_harga_beli',
            readOnly: true
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'HET COGS (Inc.PPN)',
        dataIndex: 'p_rp_het_cogs',
        width: 140,
        editor: {
            xtype: 'numberfield',
            id: 'ehjd_het_cogs',
            readOnly: true
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Rp Jual Toko',
        dataIndex: 'rp_jual_toko',
        width: 180,
        editor: {
            xtype: 'numberfield',
            id: 'ehjd_rp_jual_toko',
            listeners:{
                'change': function() {
                    if(Ext.getCmp('ehjd_rp_cogs').getValue() > 0){
                        if(this.getValue() < Ext.getCmp('ehjd_rp_cogs').getValue()){
                            this.setValue('0');
                            Ext.Msg.show({
                                title: 'Error',
                                msg: 'Net Price Jual tidak boleh lebih kecil dari HET COGS (inc PPN)',
                                modal: true,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OK,
                                fn: function(btn){
                                    this.focus();
                                }
                            });
                            Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                        }
                    }else{
                        if(this.getValue() < Ext.getCmp('ehjd_rp_het_harga_beli').getValue()){
                            this.setValue('0');
                            Ext.Msg.show({
                                title: 'Error',
                                msg: 'Net Price Jual tidak boleh lebih kecil dari HET Net Price Beli (inc PPN)',
                                modal: true,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OK,
                                fn: function(btn){
                                    this.focus();
                                }
                            });
                            Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                        }
                    }


                },'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // Edited();
                        HitungNetPJualDist();
                    }, c);
                }
            }
        }
    },{
        header: '% / Rp',
        dataIndex: 'disk_toko1_op',
        width: 50,
        editor: {
            xtype:          'combo',
            store:          new Ext.data.JsonStore({
                fields : ['name'],
                data   : [
                    {name : '%'},
                    {name : 'Rp'}
                ]
            }),
            id:           	'hjd_disk_toko1_op',
            mode:           'local',
            name:           'disk_toko1_op',
            value:          '%',
            width:			50,
            editable:       false,
            hiddenName:     'disk_toko1_op',
            valueField:     'name',
            displayField:   'name',
            triggerAction:  'all',
            forceSelection: true,
            listeners:{
                'expand':function(){
                    Ext.getCmp('hjd_disk_toko1').setValue(0);
                },
                select:function(){
                    Ext.getCmp('hjd_disk_toko1').setMaxValue(Number.MAX_VALUE);
                    if (this.getValue() === 'persen')
                        Ext.getCmp('hjd_disk_toko1').maxValue = 100;
                    else
                        Ext.getCmp('hjd_disk_toko1').maxLength = 11;
                }
            }
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        // format: '0,0',
        header: 'Diskon Toko 1',
        dataIndex: 'disk_toko1',
        width: 150,
        editor: {
            xtype: 'numberfield',
            msgTarget: 'under',
            flex:1,
            width:115,
            name : 'disk_toko1',
            id: 'hjd_disk_toko1',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        HitungNetPJualDist();
                    }, c);
                }

            }
        }
    },{
        header: '% / Rp',
        dataIndex: 'disk_toko2_op',
        width: 50,
        editor: {
            xtype:          'combo',
            store:          new Ext.data.JsonStore({
                fields : ['name'],
                data   : [
                    {name : '%'},
                    {name : 'Rp'}
                ]
            }),
            id:           	'hjd_disk_toko2_op',
            mode:           'local',
            name:           'disk_toko2_op',
            value:          '%',
            width:			50,
            editable:       false,
            hiddenName:     'disk_toko2_op',
            valueField:     'name',
            displayField:   'name',
            triggerAction:  'all',
            forceSelection: true,
            listeners:{
                'expand':function(){
                    Ext.getCmp('hjd_disk_toko2').setValue(0);
                },
                select:function(){
                    Ext.getCmp('hjd_disk_toko2').setMaxValue(Number.MAX_VALUE);
                    if (this.getValue() === 'persen')
                        Ext.getCmp('hjd_disk_toko2').maxValue = 100;
                    else
                        Ext.getCmp('hjd_disk_toko2').maxLength = 11;
                }
            }
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        // format: '0,0',
        header: 'Diskon Toko 2',
        dataIndex: 'disk_toko2',
        width: 150,
        editor: {
            xtype: 'numberfield',
            msgTarget: 'under',
            flex:1,
            width:115,
            name : 'disk_toko2',
            id: 'hjd_disk_toko2',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                         //Edited();
                        HitungNetPJualDist();
                    }, c);
                }
            }
        }
    },{
        header: '% / Rp',
        dataIndex: 'disk_toko3_op',
        width: 50,
        editor: {
            xtype:          'combo',
            store:          new Ext.data.JsonStore({
                fields : ['name'],
                data   : [
                    {name : '%'},
                    {name : 'Rp'}
                ]
            }),
            id:           	'hjd_disk_toko3_op',
            mode:           'local',
            name:           'disk_toko3_op',
            value:          '%',
            width:			50,
            editable:       false,
            hiddenName:     'disk_toko3_op',
            valueField:     'name',
            displayField:   'name',
            triggerAction:  'all',
            forceSelection: true,
            listeners:{
                'expand':function(){
                    Ext.getCmp('hjd_disk_toko3').setValue(0);
                },
                select:function(){
                    Ext.getCmp('hjd_disk_toko3').setMaxValue(Number.MAX_VALUE);
                    if (this.getValue() === 'persen')
                        Ext.getCmp('hjd_disk_toko3').maxValue = 100;
                    else
                        Ext.getCmp('hjd_disk_toko3').maxLength = 11;
                }
            }
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        // format: '0,0',
        header: 'Diskon Toko 3',
        dataIndex: 'disk_toko3',
        width: 150,
        editor: {
            xtype: 'numberfield',
            msgTarget: 'under',
            flex:1,
            width:115,
            name : 'disk_toko3',
            id: 'hjd_disk_toko3',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // Edited();
                        HitungNetPJualDist();
                    }, c);
                }
            }
        }
    },{
        header: '% / Rp',
        dataIndex: 'disk_toko4_op',
        width: 50,
        editor: {
            xtype:          'combo',
            store:          new Ext.data.JsonStore({
                fields : ['name'],
                data   : [
                    {name : '%'},
                    {name : 'Rp'}
                ]
            }),
            id:           	'hjd_disk_toko4_op',
            mode:           'local',
            name:           'disk_toko4_op',
            value:          '%',
            width:			50,
            editable:       false,
            hiddenName:     'disk_toko4_op',
            valueField:     'name',
            displayField:   'name',
            triggerAction:  'all',
            forceSelection: true,
            listeners:{
                'expand':function(){
                    Ext.getCmp('hjd_disk_toko4').setValue(0);
                },
                select:function(){
                    Ext.getCmp('hjd_disk_toko4').setMaxValue(Number.MAX_VALUE);
                    if (this.getValue() === 'persen')
                        Ext.getCmp('hjd_disk_toko4').maxValue = 100;
                    else
                        Ext.getCmp('hjd_disk_toko4').maxLength = 11;
                }
            }
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        // format: '0,0',
        header: 'Diskon Toko 4',
        dataIndex: 'disk_toko4',
        width: 150,
        editor: {
            xtype: 'numberfield',
            msgTarget: 'under',
            flex:1,
            width:115,
            name : 'disk_toko4',
            id: 'hjd_disk_toko4',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // Edited();
                        HitungNetPJualDist();
                    }, c);
                }
            }
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Diskon Toko 5',
        dataIndex: 'disk_amt_toko5',
        width: 150,
        editor: {
            xtype: 'numberfield',
            msgTarget: 'under',
            flex:1,
            width:115,
            name : 'disk_toko5',
            id: 'hjd_disk_toko5',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // Edited();
                        HitungNetPJualDist();
                    }, c);
                }
            }
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Net Price Jual Toko',
        dataIndex: 'rp_jual_toko_net',
        width: 180,
        editor: {
            xtype: 'numberfield',
            id: 'ehjd_net_price_jual_toko',
            readOnly: true,
            fieldClass: 'readonly-input',
            listeners:{
                'change': function() {
                    if(Ext.getCmp('ehjd_rp_cogs').getValue() > 0){
                        if(Ext.getCmp('ehjd_net_price_jual_toko').getValue() < Ext.getCmp('ehjd_rp_cogs').getValue()){
                            Ext.getCmp('ehjd_net_price_jual_toko').setValue('0');
                            Ext.Msg.show({
                                title: 'Error',
                                msg: 'Net Price Jual Tidak Boleh Lebih Kecil Dari HET COGS',
                                modal: true,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OK,
                                fn: function(btn){
                                    Ext.getCmp('ehjd_net_price_jual_toko').focus();
                                }
                            });
                            Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                        }
                    }else{
                        if(Ext.getCmp('ehjd_net_price_jual_toko').getValue() < Ext.getCmp('ehjd_rp_het_harga_beli').getValue()){
                            Ext.getCmp('ehjd_net_price_jual_toko').setValue('0');
                            Ext.Msg.show({
                                title: 'Error',
                                msg: 'Net Price Jual Tidak Boleh Lebih Kecil Dari HET Beli',
                                modal: true,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OK,
                                fn: function(btn){
                                    Ext.getCmp('ehjd_net_price_jual_toko').focus();
                                }
                            });
                            Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                        }
                    }


                },'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // Edited();
                    }, c);
                }
            }
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Rp Jual Agen',
        dataIndex: 'rp_jual_agen',
        width: 180,
        editor: {
            xtype: 'numberfield',
            id: 'ehjd_rp_jual_agen',
            fieldClass: 'readonly-input',
            readOnly: true,
            listeners:{
                'change': function() {
                    if(Ext.getCmp('ehjd_rp_cogs').getValue() > 0){
                        if(this.getValue() < Ext.getCmp('ehjd_rp_cogs').getValue()){
                            this.setValue('0');
                            Ext.Msg.show({
                                title: 'Error',
                                msg: 'Net Price Jual tidak boleh lebih kecil dari HET COGS (inc PPN)',
                                modal: true,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OK,
                                fn: function(btn){
                                    this.focus();
                                }
                            });
                            Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                        }
                    }else{
                        if(this.getValue() < Ext.getCmp('ehjd_rp_het_harga_beli').getValue()){
                            this.setValue('0');
                            Ext.Msg.show({
                                title: 'Error',
                                msg: 'Net Price Jual tidak boleh lebih kecil dari HET Net Price Beli (inc PPN)',
                                modal: true,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OK,
                                fn: function(btn){
                                    this.focus();
                                }
                            });
                            Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                        }
                    }


                },'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // Edited();
                        HitungNetPJualDist();
                    }, c);
                }
            }
        }
    },{
        header: '% / Rp',
        dataIndex: 'disk_agen1_op',
        width: 50,
        editor: {
            xtype:          'combo',
            store:          new Ext.data.JsonStore({
                fields : ['name'],
                data   : [
                    {name : '%'},
                    {name : 'Rp'}
                ]
            }),
            id:           	'hjd_disk_agen1_op',
            mode:           'local',
            name:           'disk_agen1_op',
            value:          '%',
            width:			50,
            editable:       false,
            hiddenName:     'disk_agen1_op',
            valueField:     'name',
            displayField:   'name',
            triggerAction:  'all',
            forceSelection: true,
            listeners:{
                'expand':function(){
                    Ext.getCmp('hjd_disk_agen1').setValue(0);
                },
                select:function(){
                    Ext.getCmp('hjd_disk_agen1').setMaxValue(Number.MAX_VALUE);
                    if (this.getValue() === 'persen')
                        Ext.getCmp('hjd_disk_agen1').maxValue = 100;
                    else
                        Ext.getCmp('hjd_disk_agen1').maxLength = 11;
                }
            }
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        // format: '0,0',
        header: 'Diskon Agen 1',
        dataIndex: 'disk_agen1',
        width: 150,
        editor: {
            xtype: 'numberfield',
            msgTarget: 'under',
            flex:1,
            width:115,
            name : 'disk_agen1',
            id: 'hjd_disk_agen1',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // Edited();
                        HitungNetPJualDist();
                    }, c);
                }
            }
        }
    },{
        header: '% / Rp',
        dataIndex: 'disk_agen2_op',
        width: 50,
        editor: {
            xtype:          'combo',
            store:          new Ext.data.JsonStore({
                fields : ['name'],
                data   : [
                    {name : '%'},
                    {name : 'Rp'}
                ]
            }),
            id:           	'hjd_disk_agen2_op',
            mode:           'local',
            name:           'disk_agen2_op',
            value:          '%',
            width:			50,
            editable:       false,
            hiddenName:     'disk_agen2_op',
            valueField:     'name',
            displayField:   'name',
            triggerAction:  'all',
            forceSelection: true,
            listeners:{
                'expand':function(){
                    Ext.getCmp('hjd_disk_agen2').setValue(0);
                },
                select:function(){
                    Ext.getCmp('hjd_disk_agen2').setMaxValue(Number.MAX_VALUE);
                    if (this.getValue() === 'persen')
                        Ext.getCmp('hjd_disk_agen2').maxValue = 100;
                    else
                        Ext.getCmp('hjd_disk_agen2').maxLength = 11;
                }
            }
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        // format: '0,0',
        header: 'Diskon Agen 2',
        dataIndex: 'disk_agen2',
        width: 150,
        editor: {
            xtype: 'numberfield',
            msgTarget: 'under',
            flex:1,
            width:115,
            name : 'disk_agen2',
            id: 'hjd_disk_agen2',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // Edited();
                        HitungNetPJualDist();
                    }, c);
                }
            }
        }
    },{
        header: '% / Rp',
        dataIndex: 'disk_agen3_op',
        width: 50,
        editor: {
            xtype:          'combo',
            store:          new Ext.data.JsonStore({
                fields : ['name'],
                data   : [
                    {name : '%'},
                    {name : 'Rp'}
                ]
            }),
            id:           	'hjd_disk_agen3_op',
            mode:           'local',
            name:           'disk_agen3_op',
            value:          '%',
            width:			50,
            editable:       false,
            hiddenName:     'disk_agen3_op',
            valueField:     'name',
            displayField:   'name',
            triggerAction:  'all',
            forceSelection: true,
            listeners:{
                'expand':function(){
                    Ext.getCmp('hjd_disk_agen3').setValue(0);
                },
                select:function(){
                    Ext.getCmp('hjd_disk_agen3').setMaxValue(Number.MAX_VALUE);
                    if (this.getValue() === 'persen')
                        Ext.getCmp('hjd_disk_agen3').maxValue = 100;
                    else
                        Ext.getCmp('hjd_disk_agen3').maxLength = 11;
                }
            }
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        // format: '0,0',
        header: 'Diskon Agen 3',
        dataIndex: 'disk_agen3',
        width: 150,
        editor: {
            xtype: 'numberfield',
            msgTarget: 'under',
            flex:1,
            width:115,
            name : 'disk_agen3',
            id: 'hjd_disk_agen3',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // Edited();
                        HitungNetPJualDist();
                    }, c);
                }
            }
        }
    },{
        header: '% / Rp',
        dataIndex: 'disk_agen4_op',
        width: 50,
        editor: {
            xtype:          'combo',
            store:          new Ext.data.JsonStore({
                fields : ['name'],
                data   : [
                    {name : '%'},
                    {name : 'Rp'}
                ]
            }),
            id:           	'hjd_disk_agen4_op',
            mode:           'local',
            name:           'disk_agen4_op',
            value:          '%',
            width:			50,
            editable:       false,
            hiddenName:     'disk_agen4_op',
            valueField:     'name',
            displayField:   'name',
            triggerAction:  'all',
            forceSelection: true,
            listeners:{
                'expand':function(){
                    Ext.getCmp('hjd_disk_agen4').setValue(0);
                },
                select:function(){
                    Ext.getCmp('hjd_disk_agen4').setMaxValue(Number.MAX_VALUE);
                    if (this.getValue() === 'persen')
                        Ext.getCmp('hjd_disk_agen4').maxValue = 100;
                    else
                        Ext.getCmp('hjd_disk_agen4').maxLength = 11;
                }
            }
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        // format: '0,0',
        header: 'Diskon Agen 4',
        dataIndex: 'disk_agen4',
        width: 150,
        editor: {
            xtype: 'numberfield',
            msgTarget: 'under',
            flex:1,
            width:115,
            name : 'disk_agen4',
            id: 'hjd_disk_agen4',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // Edited();
                        HitungNetPJualDist();
                    }, c);
                }
            }
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Diskon Agen 5',
        dataIndex: 'disk_amt_agen5',
        width: 150,
        editor: {
            xtype: 'numberfield',
            msgTarget: 'under',
            flex:1,
            width:115,
            name : 'disk_amt_agen5',
            id: 'hjd_disk_amt_agen5',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // Edited();
                        HitungNetPJualDist();
                    }, c);
                }
            }
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Net Price Jual Agen',
        dataIndex: 'rp_jual_agen_net',
        width: 180,
        editor: {
            xtype: 'numberfield',
            id: 'ehjd_net_price_jual_agen',
            readOnly: true,
            fieldClass: 'readonly-input',
            listeners:{
                'change': function() {
                    if(Ext.getCmp('ehjd_rp_cogs').getValue() > 0){
                        if(this.getValue() < Ext.getCmp('ehjd_rp_cogs').getValue()){
                            this.setValue('0');
                            Ext.Msg.show({
                                title: 'Error',
                                msg: 'Net Price Jual Tidak Boleh Lebih Kecil Dari HET COGS',
                                modal: true,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OK,
                                fn: function(btn){
                                    this.focus();
                                }
                            });
                            Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                        }
                    }else{
                        if(this.getValue() < Ext.getCmp('ehjd_rp_het_harga_beli').getValue()){
                            this.setValue('0');
                            Ext.Msg.show({
                                title: 'Error',
                                msg: 'Net Price Jual Tidak Boleh Lebih Kecil Dari HET Beli',
                                modal: true,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OK,
                                fn: function(btn){
                                    this.focus();
                                }
                            });
                            Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                        }
                    }


                },'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // Edited();
                    }, c);
                }
            }
        }
    },
    //modern supermarket
    {
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Rp Jual Modern Market',
        dataIndex: 'rp_jual_modern_market',
        width: 180,
        editor: {
            xtype: 'numberfield',
            id: 'ehjd_rp_jual_modern_market',
            fieldClass: 'readonly-input',
            readOnly: true,
            listeners:{
                'change': function() {
                    if(Ext.getCmp('ehjd_rp_cogs').getValue() > 0){
                        if(this.getValue() < Ext.getCmp('ehjd_rp_cogs').getValue()){
                            this.setValue('0');
                            Ext.Msg.show({
                                title: 'Error',
                                msg: 'Net Price Jual tidak boleh lebih kecil dari HET COGS (inc PPN)',
                                modal: true,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OK,
                                fn: function(btn){
                                    this.focus();
                                }
                            });
                            Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                        }
                    }else{
                        if(this.getValue() < Ext.getCmp('ehjd_rp_het_harga_beli').getValue()){
                            this.setValue('0');
                            Ext.Msg.show({
                                title: 'Error',
                                msg: 'Net Price Jual tidak boleh lebih kecil dari HET Net Price Beli (inc PPN)',
                                modal: true,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OK,
                                fn: function(btn){
                                    this.focus();
                                }
                            });
                            Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                        }
                    }


                },'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // Edited();
                        HitungNetPJualDist();
                    }, c);
                }
            }
        }
    },{
        header: '% / Rp',
        dataIndex: 'disk_modern_market1_op',
        width: 50,
        editor: {
            xtype:          'combo',
            store:          new Ext.data.JsonStore({
                fields : ['name'],
                data   : [
                    {name : '%'},
                    {name : 'Rp'}
                ]
            }),
            id:           	'hjd_disk_modern_market1_op',
            mode:           'local',
            name:           'disk_modern_market1_op',
            value:          '%',
            width:			50,
            editable:       false,
            hiddenName:     'disk_modern_market1_op',
            valueField:     'name',
            displayField:   'name',
            triggerAction:  'all',
            forceSelection: true,
            listeners:{
                'expand':function(){
                    Ext.getCmp('hjd_disk_modern_market1').setValue(0);
                },
                select:function(){
                    Ext.getCmp('hjd_disk_modern_market1').setMaxValue(Number.MAX_VALUE);
                    if (this.getValue() === 'persen')
                        Ext.getCmp('hjd_disk_modern_market1').maxValue = 100;
                    else
                        Ext.getCmp('hjd_disk_modern_market1').maxLength = 11;
                }
            }
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        // format: '0,0',
        header: 'Diskon Modern Market 1',
        dataIndex: 'disk_modern_market1',
        width: 150,
        editor: {
            xtype: 'numberfield',
            msgTarget: 'under',
            flex:1,
            width:115,
            name : 'disk_modern_market1',
            id: 'hjd_disk_modern_market1',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // Edited();
                        HitungNetPJualDist();
                    }, c);
                }
            }
        }
    },{
        header: '% / Rp',
        dataIndex: 'disk_modern_market2_op',
        width: 50,
        editor: {
            xtype:          'combo',
            store:          new Ext.data.JsonStore({
                fields : ['name'],
                data   : [
                    {name : '%'},
                    {name : 'Rp'}
                ]
            }),
            id:           	'hjd_disk_modern_market2_op',
            mode:           'local',
            name:           'disk_modern_market2_op',
            value:          '%',
            width:			50,
            editable:       false,
            hiddenName:     'disk_modern_market2_op',
            valueField:     'name',
            displayField:   'name',
            triggerAction:  'all',
            forceSelection: true,
            listeners:{
                'expand':function(){
                    Ext.getCmp('hjd_disk_modern_market2').setValue(0);
                },
                select:function(){
                    Ext.getCmp('hjd_disk_modern_market2').setMaxValue(Number.MAX_VALUE);
                    if (this.getValue() === 'persen')
                        Ext.getCmp('hjd_disk_modern_market2').maxValue = 100;
                    else
                        Ext.getCmp('hjd_disk_modern_market2').maxLength = 11;
                }
            }
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        // format: '0,0',
        header: 'Diskon Modern Market 2',
        dataIndex: 'disk_modern_market2',
        width: 150,
        editor: {
            xtype: 'numberfield',
            msgTarget: 'under',
            flex:1,
            width:115,
            name : 'disk_modern_market2',
            id: 'hjd_disk_modern_market2',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // Edited();
                        HitungNetPJualDist();
                    }, c);
                }
            }
        }
    },{
        header: '% / Rp',
        dataIndex: 'disk_modern_market3_op',
        width: 50,
        editor: {
            xtype:          'combo',
            store:          new Ext.data.JsonStore({
                fields : ['name'],
                data   : [
                    {name : '%'},
                    {name : 'Rp'}
                ]
            }),
            id:           	'hjd_disk_modern_market3_op',
            mode:           'local',
            name:           'disk_modern_market3_op',
            value:          '%',
            width:			50,
            editable:       false,
            hiddenName:     'disk_modern_market3_op',
            valueField:     'name',
            displayField:   'name',
            triggerAction:  'all',
            forceSelection: true,
            listeners:{
                'expand':function(){
                    Ext.getCmp('hjd_disk_modern_market3').setValue(0);
                },
                select:function(){
                    Ext.getCmp('hjd_disk_modern_market3').setMaxValue(Number.MAX_VALUE);
                    if (this.getValue() === 'persen')
                        Ext.getCmp('hjd_disk_modern_market3').maxValue = 100;
                    else
                        Ext.getCmp('hjd_disk_modern_market3').maxLength = 11;
                }
            }
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        // format: '0,0',
        header: 'Diskon Modern Market 3',
        dataIndex: 'disk_modern_market3',
        width: 150,
        editor: {
            xtype: 'numberfield',
            msgTarget: 'under',
            flex:1,
            width:115,
            name : 'disk_modern_market3',
            id: 'hjd_disk_modern_market3',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // Edited();
                        HitungNetPJualDist();
                    }, c);
                }
            }
        }
    },{
        header: '% / Rp',
        dataIndex: 'disk_modern_market4_op',
        width: 50,
        editor: {
            xtype:          'combo',
            store:          new Ext.data.JsonStore({
                fields : ['name'],
                data   : [
                    {name : '%'},
                    {name : 'Rp'}
                ]
            }),
            id:           	'hjd_disk_modern_market4_op',
            mode:           'local',
            name:           'disk_modern_market4_op',
            value:          '%',
            width:			50,
            editable:       false,
            hiddenName:     'disk_modern_market4_op',
            valueField:     'name',
            displayField:   'name',
            triggerAction:  'all',
            forceSelection: true,
            listeners:{
                'expand':function(){
                    Ext.getCmp('hjd_disk_modern_market4').setValue(0);
                },
                select:function(){
                    Ext.getCmp('hjd_disk_modern_market4').setMaxValue(Number.MAX_VALUE);
                    if (this.getValue() === 'persen')
                        Ext.getCmp('hjd_disk_modern_market4').maxValue = 100;
                    else
                        Ext.getCmp('hjd_disk_modern_marketn4').maxLength = 11;
                }
            }
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        // format: '0,0',
        header: 'Diskon Modern Market 4',
        dataIndex: 'disk_modern_market4',
        width: 150,
        editor: {
            xtype: 'numberfield',
            msgTarget: 'under',
            flex:1,
            width:115,
            name : 'disk_modern_market4',
            id: 'hjd_disk_modern_market4',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // Edited();
                        HitungNetPJualDist();
                    }, c);
                }
            }
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Diskon Modern Market 5',
        dataIndex: 'disk_amt_modern_market5',
        width: 150,
        editor: {
            xtype: 'numberfield',
            msgTarget: 'under',
            flex:1,
            width:115,
            name : 'disk_amt_modern_market5',
            id: 'hjd_disk_amt_modern_market5',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // Edited();
                        HitungNetPJualDist();
                    }, c);
                }
            }
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Net Price Jual Modern Market',
        dataIndex: 'rp_jual_modern_market_net',
        width: 180,
        editor: {
            xtype: 'numberfield',
            id: 'ehjd_net_price_jual_modern_market',
            readOnly: true,
            fieldClass: 'readonly-input',
            listeners:{
                'change': function() {
                    if(Ext.getCmp('ehjd_rp_cogs').getValue() > 0){
                        if(this.getValue() < Ext.getCmp('ehjd_rp_cogs').getValue()){
                            this.setValue('0');
                            Ext.Msg.show({
                                title: 'Error',
                                msg: 'Net Price Jual Tidak Boleh Lebih Kecil Dari HET COGS',
                                modal: true,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OK,
                                fn: function(btn){
                                    this.focus();
                                }
                            });
                            Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                        }
                    }else{
                        if(this.getValue() < Ext.getCmp('ehjd_rp_het_harga_beli').getValue()){
                            this.setValue('0');
                            Ext.Msg.show({
                                title: 'Error',
                                msg: 'Net Price Jual Tidak Boleh Lebih Kecil Dari HET Beli',
                                modal: true,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OK,
                                fn: function(btn){
                                    this.focus();
                                }
                            });
                            Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                        }
                    }


                },'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // Edited();
                    }, c);
                }
            }
        }
    },
{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Qty Beli |Toko|',
        dataIndex: 'qty_beli_bonus',
        width: 150,
        editor: {
            xtype: 'numberfield',
            msgTarget: 'under',
            flex:1,
            width:115,
            name : 'qty_beli_bonus',
            id: 'hjd_qty_beli_bonus',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // Edited();
                    }, c);
                }
            }
        }
    },{
        header: 'Kd Produk |Toko|',
        dataIndex: 'kd_produk_bonus',
        width: 150,
        editor: new Ext.ux.TwinCombohjd({
            id: 'hjd_kd_produk_bonus',
            store: strcbkdprodukhjd,
            valueField: 'kd_produk_bonus',
            displayField: 'kd_produk_bonus',
            typeAhead: true,
            editable: false,
            hiddenName: 'kd_produk_bonus',
            emptyText: 'Pilih Kode Produk',
            listeners:{
                'expand': function(){
                    strcbkdprodukhjd.load();
                    // Edited();
                }
            }
        })
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Qty Bonus |Toko|',
        dataIndex: 'qty_bonus',
        width: 150,
        editor: {
            xtype: 'numberfield',
            msgTarget: 'under',
            flex:1,
            width:115,
            name : 'qty_bonus',
            id: 'hjd_qty_bonus',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // Edited();
                    }, c);
                }
            }
        }
    },{
        header: 'Kelipatan ? |Toko|',
        dataIndex: 'is_bonus_kelipatan',
        width: 150,
        editor: {
            xtype:          'combo',
            store:          new Ext.data.JsonStore({
                fields : ['name'],
                data   : [
                    {name : 'Ya'},
                    {name : 'Tidak'}
                ]
            }),
            id:           	'hjd_is_bonus_kelipatan',
            mode:           'local',
            name:           'is_bonus_kelipatan',
            value:          'Ya',
            width:			50,
            editable:       false,
            hiddenName:     'is_bonus_kelipatan',
            valueField:     'name',
            displayField:   'name',
            triggerAction:  'all',
            forceSelection: true,
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // Edited();
                    }, c);
                }
            }
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Qty Beli |Agen|',
        dataIndex: 'qty_beli_agen',
        width: 150,
        editor: {
            xtype: 'numberfield',
            msgTarget: 'under',
            flex:1,
            width:115,
            name : 'qty_beli_agen',
            id: 'hjd_qty_beli_agen',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // Edited();
                    }, c);
                }
            }
        }
    },{
        header: 'Kd Produk |Agen|',
        dataIndex: 'kd_produk_agen',
        width: 150,
        editor: new Ext.ux.TwinCombohjd({
            id: 'hjd_kd_produk_member',
            store: strcbkdprodukhjd,
            valueField: 'kd_produk_member',
            displayField: 'kd_produk_member',
            typeAhead: true,
            editable: false,
            hiddenName: 'kd_produk_member',
            emptyText: 'Pilih Kode Produk',
            listeners:{
                'expand': function(){
                    strcbkdprodukhjd.load();
                    // Edited();
                }
            }
        })

    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Qty Bonus |Agen|',
        dataIndex: 'qty_agen',
        width: 150,
        editor: {
            xtype: 'numberfield',
            msgTarget: 'under',
            flex:1,
            width:115,
            name : 'qty_agen',
            id: 'hjd_qty_bonus_agen',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // Edited();
                    }, c);
                }
            }
        }
    },{
        header: 'Kelipatan ? |Agen|',
        dataIndex: 'is_member_kelipatan',
        width: 150,
        editor:{
            xtype:          'combo',
            store:          new Ext.data.JsonStore({
                fields : ['name'],
                data   : [
                    {name : 'Ya'},
                    {name : 'Tidak'}
                ]
            }),
            id:           	'hjd_is_member_kelipatan',
            mode:           'local',
            name:           'is_member_kelipatan',
            value:          'Ya',
            width:			50,
            editable:       false,
            hiddenName:     'is_member_kelipatan',
            valueField:     'name',
            displayField:   'name',
            triggerAction:  'all',
            forceSelection: true,
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // Edited();
                    }, c);
                }
            }
        }

    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Qty Beli |Modern Market|',
        dataIndex: 'qty_beli_modern_market',
        width: 150,
        editor: {
            xtype: 'numberfield',
            msgTarget: 'under',
            flex:1,
            width:115,
            name : 'qty_beli_modern_market',
            id: 'hjd_qty_beli_modern_market',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // Edited();
                    }, c);
                }
            }
        }
    },{
        header: 'Kd Produk |Modern Market|',
        dataIndex: 'kd_produk_modern_market',
        width: 150,
        editor: new Ext.ux.TwinCombohjd({
            id: 'hjd_kd_produk_modern_market',
            store: strcbkdprodukhjd,
            valueField: 'kd_produk_modern_market',
            displayField: 'kd_produk_modern_market',
            typeAhead: true,
            editable: false,
            hiddenName: 'kd_produk_modern_market',
            emptyText: 'Pilih Kode Produk',
            listeners:{
                'expand': function(){
                    strcbkdprodukhjd.load();
                    // Edited();
                }
            }
        })

    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Qty Bonus |Modern Market|',
        dataIndex: 'qty_modern_market',
        width: 150,
        editor: {
            xtype: 'numberfield',
            msgTarget: 'under',
            flex:1,
            width:115,
            name : 'qty_agen',
            id: 'hjd_qty_bonus_modern_market',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // Edited();
                    }, c);
                }
            }
        }
    },{
        header: 'Kelipatan ? |Modern Market|',
        dataIndex: 'is_modern_market_kelipatan',
        width: 150,
        editor:{
            xtype:          'combo',
            store:          new Ext.data.JsonStore({
                fields : ['name'],
                data   : [
                    {name : 'Ya'},
                    {name : 'Tidak'}
                ]
            }),
            id:           	'hjd_is_modern_market_kelipatan',
            mode:           'local',
            name:           'is_modern_market_kelipatan',
            value:          'Ya',
            width:			50,
            editable:       false,
            hiddenName:     'is_modern_market_kelipatan',
            valueField:     'name',
            displayField:   'name',
            triggerAction:  'all',
            forceSelection: true,
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // Edited();
                    }, c);
                }
            }
        }

    },{
                    xtype: 'datecolumn',
                    header: 'Tgl Mulai Diskon',
                    dataIndex: 'tgl_start_diskon',
                    format: 'd/m/Y',
                    width: 120,
                    editor: new Ext.form.DateField({
                        id: 'hjd_tgl_start_diskon',
                        format: 'd/m/Y',
                        //minValue: (new Date()).clearTime(),
                         listeners:{			
                            'change': function() {
                               	  Ext.getCmp('hjd_edited').setValue('Y');
                            }
                        }
                    })
                },{
                    xtype: 'datecolumn',
                    header: 'Tgl Akhir Diskon',
                    dataIndex: 'tgl_end_diskon',
                    format: 'd/m/Y',
                    width: 120,
                    editor: new Ext.form.DateField({
                        id: 'hjd_tgl_end_diskon',
                        format: 'd/m/Y',
                        //minValue: (new Date()).clearTime(),
                        listeners:{			
                            'change': function() {
                               	  Ext.getCmp('hjd_edited').setValue('Y');
                            }
                        }
                    })
                }],
    tbar: new Ext.Toolbar({
        items: [searchgridhargapenjualandistribusi, '->', cbhjdproduk, cbhjdnobukti, '-' ,{
            text: 'Show History',
            icon: BASE_ICONS + 'grid.png',
            onClick: function(){
                var kd_produk = Ext.getCmp('id_cbhjdproduk').getValue();
                var no_bukti = Ext.getCmp('id_cbhjdnobukti').getValue();
                if (kd_produk === '' && no_bukti === ''){
                    Ext.Msg.show({
                        title: 'Error',
                        msg: 'Silahkan Search Produk / No Bukti Terlebih Dulu',
                        modal: true,
                        icon: Ext.Msg.ERROR,
                        buttons: Ext.Msg.OK
                    });
                    return;
                }
                gridhargapenjualandistribusihistory.store.load({
                    params:{
                        no_bukti: Ext.getCmp('id_cbhjdnobukti').getValue(),
                        kd_produk: Ext.getCmp('id_cbhjdproduk').getValue()
                    }
                });
                winshowhistoryhargapenjualandistribusi.setTitle('History');
                winshowhistoryhargapenjualandistribusi.show();
                // var sm = gridhargapenjualandistribusi.getSelectionModel();
                // var sel = sm.getSelections();
                // if (sel[0] == undefined){					
                // Ext.Msg.show({
                // title: 'Error',
                // msg: 'Silahkan klik salah satu data terlebih dulu',
                // modal: true,
                // icon: Ext.Msg.ERROR,
                // buttons: Ext.Msg.OK			               
                // });
                // return;
                // }
                // gridhargapenjualandistribusihistory.store.proxy.conn.url = '<?= site_url("harga_penjualan/search_produk_history") ?>/' +sel[0].get('kd_produk');
                // gridhargapenjualandistribusihistory.store.reload();
                // winshowhistoryhargapenjualandistribusi.setTitle('History');
                // winshowhistoryhargapenjualandistribusi.show();				        
            }
        },'-',{
            text: 'Reset',
            icon: BASE_ICONS + 'refresh.gif',
            onClick: function(){
                Ext.getCmp('id_cbhjdnobukti').setValue('');
                Ext.getCmp('id_cbhjdproduk').setValue('');
            }
        }]
    })
    // bbar: new Ext.PagingToolbar({
    // pageSize: ENDPAGE,
    // store: strhargapenjualandistribusi,
    // displayInfo: true
    // })
});


var hargapenjualandistribusi = new Ext.FormPanel({
    id: 'hargapenjualandistribusi',
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
        items: [headerhargapenjualandistribusidist]
    },{
        xtype:'fieldset',
        autoheight: true,
        title: 'Diskon',
        collapsed: false,
        collapsible: true,
        anchor: '70%',
        items:[ {
            xtype : 'compositefield',
            msgTarget: 'side',
            fieldLabel: 'Disk Toko 1',
            items : [ {
                xtype : 'compositefield',
                msgTarget: 'side',
                fieldLabel: 'Disk Toko 1',
                width:200,
                items : [{
                    xtype:          'combo',
                    mode:           'local',
                    value:          '%',
                    triggerAction:  'all',
                    forceSelection: true,
                    name:           'disk_toko1_op',
                    id:           	'hpd_disk_toko1_op',
                    hiddenName:     'disk_toko1_op',
                    displayField:   'name',
                    valueField:     'value',
                    width:	50,
                    store:          new Ext.data.JsonStore({
                        fields : ['name', 'value'],
                        data   : [
                            {name : '%',   value: '%'},
                            {name : 'Rp',  value: 'Rp'}
                        ]
                    }),
                    listeners:{
                        select:function(){
                            Ext.getCmp('hpd_disk_toko1').setMaxValue(Number.MAX_VALUE);
                            if (this.getValue() === '%')
                                Ext.getCmp('hpd_disk_toko1').maxValue = 100;
                            else Ext.getCmp('hpd_disk_toko1').maxLength = 11;
                        }
                    }
                },{
                    xtype: 'numberfield',
                    flex:1,
                    width:115,
                    name : 'disk_toko1',
                    id: 'hpd_disk_toko1',
                    style: 'text-align:right;',
                    value :'0'

                }]
            },{
                xtype: 'displayfield',
                value: 'Disk Toko 2',
                width: 100
            },{
                xtype : 'compositefield',
                msgTarget: 'side',
                fieldLabel: 'Disk Toko 2',
                width:200,
                items : [{
                    width:          50,
                    xtype:          'combo',
                    mode:           'local',
                    value:          '%',
                    triggerAction:  'all',
                    forceSelection: true,

                    name:           'disk_toko2_op',
                    id:           	'hpd_disk_toko2_op',
                    hiddenName:     'disk_toko2_op',
                    displayField:   'name',
                    valueField:     'value',
                    store:          new Ext.data.JsonStore({
                        fields : ['name', 'value'],
                        data   : [
                            {name : '%',   value: '%'},
                            {name : 'Rp',  value: 'Rp'}
                        ]
                    }),
                    listeners:{
                        select:function(){
                            Ext.getCmp('hpd_disk_toko2').setMaxValue(Number.MAX_VALUE);
                            if (this.getValue() === '%')
                                Ext.getCmp('hpd_disk_toko2').maxValue = 100;
                            else Ext.getCmp('hpd_disk_toko2').maxLength = 11;
                        }
                    }
                },{
                    xtype: 'numberfield',
                    flex : 1,
                    width:115,
                    name : 'disk_toko2',
                    value :'0',
                    id: 'hpd_disk_toko2',
                    style: 'text-align:right;'

                }]

            }]
        },{
            xtype : 'compositefield',
            msgTarget: 'side',
            fieldLabel: 'Disk Toko 3',
            items : [{
                xtype : 'compositefield',
                msgTarget: 'side',
                fieldLabel: 'Disk Toko 3',
                width:200,
                items : [{
                    width:          50,
                    xtype:          'combo',
                    mode:           'local',
                    value:          '%',
                    triggerAction:  'all',
                    forceSelection: true,

                    name:           'disk_toko3_op',
                    id:           	'hpd_disk_toko3_op',
                    hiddenName:     'disk_toko3_op',
                    displayField:   'name',
                    valueField:     'value',
                    store:          new Ext.data.JsonStore({
                        fields : ['name', 'value'],
                        data   : [
                            {name : '%',   value: '%'},
                            {name : 'Rp',  value: 'Rp'}
                        ]
                    }),
                    listeners:{
                        select:function(){
                            Ext.getCmp('hpd_disk_toko3').setMaxValue(Number.MAX_VALUE);
                            if (this.getValue() === '%')
                                Ext.getCmp('hpd_disk_toko3').maxValue = 100;
                            else Ext.getCmp('hpd_disk_toko3').maxLength = 11;
                        }
                    }
                },{
                    xtype: 'numberfield',
                    flex : 1,
                    width:115,
                    name : 'disk_toko3',
                    value :'0',
                    id: 'hpd_disk_toko3',
                    style: 'text-align:right;'

                }]

            }, {
                xtype: 'displayfield',
                value: 'Disk Toko 4',
                width: 100
            },{
                xtype : 'compositefield',
                msgTarget: 'side',
                fieldLabel: 'Disk Toko 4',
                width:200,
                items : [{
                    width:          50,
                    xtype:          'combo',
                    mode:           'local',
                    value:          '%',
                    triggerAction:  'all',
                    forceSelection: true,

                    name:           'disk_toko4_op',
                    id:           	'hpd_disk_toko4_op',
                    hiddenName:     'disk_toko4_op',
                    displayField:   'name',
                    valueField:     'value',
                    store:          new Ext.data.JsonStore({
                        fields : ['name', 'value'],
                        data   : [
                            {name : '%',   value: '%'},
                            {name : 'Rp',  value: 'Rp'}
                        ]
                    }),
                    listeners:{
                        select:function(){
                            Ext.getCmp('hpd_disk_toko4').setMaxValue(Number.MAX_VALUE);
                            if (this.getValue() === '%')
                                Ext.getCmp('hpd_disk_toko4').maxValue = 100;
                            else Ext.getCmp('hpd_disk_toko4').maxLength = 11;
                        }
                    }
                },{
                    xtype: 'numberfield',
                    flex : 1,
                    width:115,
                    name : 'disk_toko4',
                    value :'0',
                    id: 'hpd_disk_toko4',
                    style: 'text-align:right;'

                }]

            }
            ]
        },{
            xtype : 'compositefield',
            msgTarget: 'side',
            fieldLabel: 'Disk Toko 5',
            items : [{
                xtype: 'numberfield',
                currencySymbol:'',
                width: 170,
                name : 'disk_toko5',
                value :'0',
                id: 'hpd_disk_toko5',
                style: 'text-align:right;'

            }
            ]
        }],buttons: [{
            text: 'Apply All',
            formBind: true,
            handler: function(){
                var kd_supplier =  Ext.getCmp('id_cbhjdsuplier').getValue();
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

                strhargapenjualandistribusi.each(function(record){

                    record.set('disk_toko1_op',Ext.getCmp('hpd_disk_toko1_op').getValue());
                    record.set('disk_toko1',Ext.getCmp('hpd_disk_toko1').getValue());
                    record.set('disk_toko2_op',Ext.getCmp('hpd_disk_toko2_op').getValue());
                    record.set('disk_toko2',Ext.getCmp('hpd_disk_toko2').getValue());
                    record.set('disk_toko3_op',Ext.getCmp('hpd_disk_toko3_op').getValue());
                    record.set('disk_toko3',Ext.getCmp('hpd_disk_toko3').getValue());
                    record.set('disk_toko4_op',Ext.getCmp('hpd_disk_toko4_op').getValue());
                    record.set('disk_toko4',Ext.getCmp('hpd_disk_toko4').getValue());
                    record.set('disk_amt_toko5',Ext.getCmp('hpd_disk_toko5').getValue());

                    record.commit();

                    record.set('edited','Y');

                    var total_disk = 0;
                    var rp_jual_toko = record.get('rp_jual_toko');
                    var disk_toko1_op = record.get('disk_toko1_op');
                    var disk_toko1 = record.get('disk_toko1');
                    if (disk_toko1_op === '%'){
                        total_disk = rp_jual_toko-(rp_jual_toko*(disk_toko1/100));
                    }else{
                        total_disk = rp_jual_toko-disk_toko1;
                    }

                    var disk_toko2_op = record.get('disk_toko2_op');
                    var disk_toko2 = record.get('disk_toko2');
                    if (disk_toko2_op === '%'){
                        total_disk =  total_disk-(total_disk*(disk_toko2/100));
                    }else{
                        total_disk = total_disk-disk_toko2;
                    }

                    var disk_toko3_op = record.get('disk_toko3_op');
                    var disk_toko3 = record.get('disk_toko3');
                    if (disk_toko3_op === '%'){
                        total_disk = total_disk-(total_disk*(disk_toko3/100));
                    }else{
                        total_disk = total_disk-disk_toko3;
                    }

                    var disk_toko4_op = record.get('disk_toko4_op');
                    var disk_toko4 = record.get('disk_toko4');
                    if (disk_toko4_op === '%'){
                        total_disk = total_disk-(total_disk*(disk_toko4/100));
                    }else{
                        total_disk = total_disk-disk_toko4;
                    }

                    var total_disk = total_disk - record.get('disk_amt_toko5');

                    record.set('rp_jual_toko_net', total_disk);


                    var disk_agen1_op = record.get('disk_agen1_op');
                    var disk_agen1 = record.get('disk_agen1');
                    if (disk_agen1_op === '%'){
                        total_disk = rp_jual_toko-(rp_jual_toko*(disk_agen1/100));
                    }else{
                        total_disk = rp_jual_toko-disk_agen1;
                    }

                    var disk_agen2_op = record.get('disk_agen2_op');
                    var disk_agen2 = record.get('disk_agen2');
                    if (disk_agen2_op === '%'){
                        total_disk = total_disk-(total_disk*(disk_agen2/100));
                    }else{
                        total_disk = total_disk-disk_agen2;
                    }

                    var disk_agen3_op = record.get('disk_agen3_op');
                    var disk_agen3 = record.get('disk_agen3');
                    if (disk_agen3_op === '%'){
                        total_disk = total_disk-(total_disk*(disk_agen3/100));
                    }else{
                        total_disk = total_disk-disk_agen3;
                    }

                    var disk_agen4_op = record.get('disk_agen4_op');
                    var disk_agen4 = record.get('disk_agen4');
                    if (disk_agen4_op === '%'){
                        total_disk = total_disk-(total_disk*(disk_agen4/100));
                    }else{
                        total_disk = total_disk-disk_agen4;
                    }

                    var total_disk = total_disk - record.get('disk_amt_agen5');

                    var net_price_memb = total_disk;
                    record.set('rp_jual_agen_net', net_price_memb);
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
                        fieldLabel: 'Tgl Mulai Diskon',
                        items : [ {
                                    xtype: 'datefield',
                                    fieldLabel: 'Tgl Mulai Diskon',
                                    name: 'tgl_start_diskon',				
                                    allowBlank:true,   
                                    format:'d-m-Y',  
                                    //editable:false,           
                                    id: 'hpd_tgl_start_diskon',                
                                    width: 150,
                                    minValue: (new Date()).clearTime() 
                                },{
                                xtype: 'displayfield',
                                value: 'Tgl Akhir Diskon',
                                width: 100
                            },{
                                xtype : 'compositefield',
                                msgTarget: 'side',
                                fieldLabel: 'Tgl Akhir Diskon',
                                width:150,
                                items : [{
                                        xtype: 'datefield',
                                        fieldLabel: 'Tgl Akhir Diskon',
                                        name: 'tgl_end_diskon',				
                                        allowBlank:true,   
                                        format:'d-m-Y',  
                                        //editable:false,           
                                        id: 'hpd_tgl_end_diskon',                
                                        width: 150,
                                        minValue: (new Date()).clearTime() 
                                    }]
												
                            }]
                    }],buttons: [{
                        text: 'Apply All',
                        formBind: true,
                        handler: function(){
                            var kd_supplier =  Ext.getCmp('id_cbhjdsuplier').getValue();
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
                            
                            strhargapenjualandistribusi.each(function(record){
                                    
                                record.set('tgl_start_diskon',Ext.getCmp('hpd_tgl_start_diskon').getValue());
                                record.set('tgl_end_diskon',Ext.getCmp('hpd_tgl_end_diskon').getValue());
                                
                                record.commit();
                                record.set('edited','Y');
                                record.commit();
                            });

                        }
                    }]
            },
        gridhargapenjualandistribusi,
        {
            layout: 'column',
            border: false,
            items: [{
                columnWidth: .6,
                style:'margin:6px 3px 0 0;',
                layout: 'form',
                labelWidth: 100,
                items: [{
                    xtype: 'textarea',
                    fieldLabel: 'Ket. Perubahan <span class="asterix">*</span>',
                    name: 'keterangan',
                    allowBlank: false,
                    id: 'ehjd_keterangan',
                    width: 300
                }]
            }]
        }
    ],
    buttons: [{
        text: 'Save',
        formBind: true,
        handler: function(){
            
            var validasi = true;
            var kd_produk = '';
            strhargapenjualandistribusi.each(function(node){
                var tgl_start_diskon = node.data.tgl_start_diskon;
                var tgl_end_diskon = node.data.tgl_end_diskon;
                var kode_produk = node.data.kd_produk_baru;
                var edited = node.data.edited;
                if (edited === 'Y'){
                    if (tgl_end_diskon < tgl_start_diskon){
                        validasi= false;
                        kd_produk = kode_produk;
                    }
                }
               });
            if(!validasi){

                        Ext.Msg.show({
                                        title: 'Error',
                                        msg: 'Kode Produk <code>' + kd_produk + '</code> Tanggal Akhir Diskon Tidak Boleh Lebih Kecil dari Tanggal Mulai Diskon',
                                        modal: true,
                                        icon: Ext.Msg.ERROR,
                                        buttons: Ext.Msg.OK,
                                        fn: function(btn){
                                            //this.focus();
                                           }
                                    });
                                    Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                         return;

                   }
            var detailhargapenjualandistribusi = new Array();
            strhargapenjualandistribusi.each(function(node){
                detailhargapenjualandistribusi.push(node.data);
            });
            Ext.getCmp('hargapenjualandistribusi').getForm().submit({
                url: '<?= site_url("harga_penjualan_distribusi/update_row") ?>',
                scope: this,
                params: {
                    detail: Ext.util.JSON.encode(detailhargapenjualandistribusi)
                },
                waitMsg: 'Saving Data...',
                success: function(form, action){
                    Ext.Msg.show({
                        title: 'Success',
                        msg: 'Form submitted successfully',
                        modal: true,
                        icon: Ext.Msg.INFO,
                        buttons: Ext.Msg.OK
                    });

                    clearhargapenjualandistribusi();
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
                            if (btn === 'ok' && fe.errMsg === 'Session Expired') {
                                window.location = '<?= site_url("auth/login") ?>';
                            }
                        }
                    });

                }
            });

        }
    },{
        text: 'Reset',
        handler: function(){
            clearhargapenjualandistribusi();
        }
    }]
});

hargapenjualandistribusi.on('afterrender', function(){
    this.getForm().load({
        url: '<?= site_url("harga_penjualan_distribusi/get_form") ?>',
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

function clearhargapenjualandistribusi(){
    Ext.getCmp('hargapenjualandistribusi').getForm().reset();
    Ext.getCmp('hargapenjualandistribusi').getForm().load({
        url: '<?= site_url("harga_penjualan_distribusi/get_form") ?>',
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
    strhargapenjualandistribusi.removeAll();
}
</script>
