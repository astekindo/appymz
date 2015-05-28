<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
/* START twin produk*/

var strcbhjbproduk = new Ext.data.ArrayStore({
    fields: ['kd_produk_baru', 'nama_produk'],
    data : []
});

var strgridhjbproduk = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: ['kd_produk_baru', 'nama_produk'],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("harga_penjualan_proyek/search_produk_by_kategori") ?>',
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

var searchgridhjbproduk = new Ext.app.SearchField({
    store: strgridhjbproduk,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridhjbproduk'
});


var gridhjbproduk = new Ext.grid.GridPanel({
    store: strgridhjbproduk,
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
        items: [searchgridhjbproduk]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridhjbproduk,
        displayInfo: true
    }),
    listeners: {
        'rowdblclick': function(){
            var sm = this.getSelectionModel();
            var sel = sm.getSelections();
            if (sel.length > 0) {
                Ext.getCmp('id_cbhjbproduk').setValue(sel[0].get('kd_produk_baru'));
                menuhjbproduk.hide();
            }
        }
    }
});

var menuhjbproduk = new Ext.menu.Menu();
menuhjbproduk.add(new Ext.Panel({
    title: 'Pilih Produk',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridhjbproduk],
    buttons: [{
        text: 'Close',
        handler: function(){
            menuhjbproduk.hide();
        }
    }]
}));

Ext.ux.TwinCombohjbproduk = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function(){
        //load store grid
        var kd_supplier =  Ext.getCmp('id_cbhjsuplier_proyek').getValue();
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
        strgridhjbproduk.load({
            params:{
                start: STARTPAGE,
                limit: ENDPAGE,
                kd_supplier: Ext.getCmp('id_cbhjsuplier_proyek').getValue(),
                kd_kategori1: Ext.getCmp('hjp_cbkategori1').getValue(),
                kd_kategori2: Ext.getCmp('hjp_cbkategori2').getValue(),
                kd_kategori3: Ext.getCmp('hjp_cbkategori3').getValue(),
                kd_kategori4: Ext.getCmp('hjp_cbkategori4').getValue(),
                no_bukti: Ext.getCmp('id_cbhjnobuktifilter_proyek').getValue(),
                konsinyasi: Ext.getCmp('hjp_konsinyasi').getValue(),
                list: Ext.getCmp('ehjp_list').getValue()
            }
        });
        menuhjbproduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuhjbproduk.on('hide', function(){
    var sf = Ext.getCmp('id_searchgridhjbproduk').getValue();
    if( sf != ''){
        Ext.getCmp('id_searchgridhjbproduk').setValue('');
        searchgridhjbproduk.onTrigger2Click();
    }
});

var cbhjbproduk = new Ext.ux.TwinCombohjbproduk({
    id: 'id_cbhjbproduk',
    store: strcbhjbproduk,
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

var strcbhjbnobukti = new Ext.data.ArrayStore({
    fields: ['kd_diskon_sales','keterangan'],
    data : []
});

var strgridhjbnobukti = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: ['kd_diskon_sales','keterangan','created_by','nama_supplier'],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("harga_penjualan_proyek/search_no_bukti") ?>',
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

var searchgridhjbnobukti = new Ext.app.SearchField({
    store: strgridhjbnobukti,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridhjbnobukti'
});


var gridhjbnobukti = new Ext.grid.GridPanel({
    store: strgridhjbnobukti,
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
        items: [searchgridhjbnobukti]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridhjbnobukti,
        displayInfo: true
    }),
    listeners: {
        'rowdblclick': function(){
            var sm = this.getSelectionModel();
            var sel = sm.getSelections();
            if (sel.length > 0) {
                Ext.getCmp('id_cbhjbnobukti').setValue(sel[0].get('kd_diskon_sales'));

                menuhjbnobukti.hide();
            }
        }
    }
});

var menuhjbnobukti = new Ext.menu.Menu();
menuhjbnobukti.add(new Ext.Panel({
    title: 'Pilih No Bukti',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 500,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridhjbnobukti],
    buttons: [{
        text: 'Close',
        handler: function(){
            menuhjbnobukti.hide();
        }
    }]
}));

Ext.ux.TwinCombohjbnobukti = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function(){
        //load store grid
        strgridhjbnobukti.setBaseParam('kd_supplier',Ext.getCmp('id_cbhjsuplier_proyek').getValue());
        strgridhjbnobukti.load();
        menuhjbnobukti.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuhjbnobukti.on('hide', function(){
    var sf = Ext.getCmp('id_searchgridhjbnobukti').getValue();
    if( sf != ''){
        Ext.getCmp('id_searchgridhjbnobukti').setValue('');
        searchgridhjbnobukti.onTrigger2Click();
    }
});

var cbhjbnobukti = new Ext.ux.TwinCombohjbnobukti({
    fieldLabel: 'No Bukti <span class="asterix">*</span>',
    id: 'id_cbhjbnobukti',
    store: strcbhjbnobukti,
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

var strcbhjnobuktifilter_proyek = new Ext.data.ArrayStore({
    fields: ['kd_diskon_sales','keterangan'],
    data : []
});

var strgridhjnobuktifilter_proyek = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: ['kd_diskon_sales','keterangan','created_by','nama_supplier'],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("harga_penjualan_proyek/search_no_bukti_filter") ?>',
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

var searchgridhjnobuktifilter_proyek = new Ext.app.SearchField({
    store: strgridhjnobuktifilter_proyek,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridhjnobuktifilter_proyek'
});


var gridhjnobuktifilter_proyek = new Ext.grid.GridPanel({
    store: strgridhjnobuktifilter_proyek,
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
        items: [searchgridhjnobuktifilter_proyek]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridhjnobuktifilter_proyek,
        displayInfo: true
    }),
    listeners: {
        'rowdblclick': function(){
            var sm = this.getSelectionModel();
            var sel = sm.getSelections();
            if (sel.length > 0) {
                Ext.getCmp('id_cbhjnobuktifilter_proyek').setValue(sel[0].get('kd_diskon_sales'));

                menuhjnobuktifilter_proyek.hide();
            }
        }
    }
});

var menuhjnobuktifilter_proyek = new Ext.menu.Menu();
menuhjnobuktifilter_proyek.add(new Ext.Panel({
    title: 'Pilih No Bukti',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 500,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridhjnobuktifilter_proyek],
    buttons: [{
        text: 'Close',
        handler: function(){
            menuhjnobuktifilter_proyek.hide();
        }
    }]
}));

Ext.ux.TwinCombohjnobuktifilter_proyek = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function(){
        //load store grid
        strgridhjnobuktifilter_proyek.setBaseParam('kd_supplier',Ext.getCmp('id_cbhjsuplier_proyek').getValue());
        strgridhjnobuktifilter_proyek.load();
        menuhjnobuktifilter_proyek.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuhjnobuktifilter_proyek.on('hide', function(){
    var sf = Ext.getCmp('id_searchgridhjnobuktifilter_proyek').getValue();
    if( sf !== ''){
        Ext.getCmp('id_searchgridhjnobuktifilter_proyek').setValue('');
        searchgridhjnobuktifilter_proyek.onTrigger2Click();
    }
});

var cbhjnobuktifilter_proyek = new Ext.ux.TwinCombohjnobuktifilter_proyek({
    fieldLabel: 'No Bukti Filter',
    id: 'id_cbhjnobuktifilter_proyek',
    store: strcbhjnobuktifilter_proyek,
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
var strhargapenjualanproyekhistory = new Ext.data.Store({
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
            {name: 'disk_proyek1_op', allowBlank: false, type: 'text'},
            {name: 'disk_proyek2_op', allowBlank: false, type: 'text'},
            {name: 'disk_proyek3_op', allowBlank: false, type: 'text'},
            {name: 'disk_proyek4_op', allowBlank: false, type: 'text'},
            {name: 'disk_agen1_op', allowBlank: false, type: 'text'},
            {name: 'disk_agen2_op', allowBlank: false, type: 'text'},
            {name: 'disk_agen3_op', allowBlank: false, type: 'text'},
            {name: 'disk_agen4_op', allowBlank: false, type: 'text'},
            {name: 'disk_proyek1', allowBlank: false, type: 'float'},
            {name: 'disk_proyek2', allowBlank: false, type: 'float'},
            {name: 'disk_proyek3', allowBlank: false, type: 'float'},
            {name: 'disk_proyek4', allowBlank: false, type: 'float'},
            {name: 'disk_amt_proyek5', allowBlank: false, type: 'int'},
            {name: 'net_price_jual_toko', allowBlank: false, type: 'int'},
            {name: 'disk_agen1', allowBlank: false, type: 'float'},
            {name: 'disk_agen2', allowBlank: false, type: 'float'},
            {name: 'disk_agen3', allowBlank: false, type: 'float'},
            {name: 'disk_agen4', allowBlank: false, type: 'float'},
            {name: 'disk_amt_agen5', allowBlank: false, type: 'int'},
            {name: 'net_price_jual_agen', allowBlank: false, type: 'int'},
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
            {name: 'rp_jual_proyek', allowBlank: false, type: 'int'},
            {name: 'rp_jual_proyek_net', allowBlank: false, type: 'int'},
            {name: 'rp_jual_agen', allowBlank: false, type: 'int'},
            {name: 'rp_jual_distribusi', allowBlank: false, type: 'int'},
            {name: 'qty_beli_bonus', allowBlank: false, type: 'int'},
            {name: 'kd_produk_proyek', allowBlank: false, type: 'text'},
            {name: 'qty_bonus', allowBlank: false, type: 'int'},
            {name: 'is_bonus_kelipatan', allowBlank: false, type: 'text'},
            {name: 'qty_agen', allowBlank: false, type: 'int'},
            {name: 'kd_produk_agen', allowBlank: false, type: 'text'},
            {name: 'qty_bonus', allowBlank: false, type: 'int'},
            {name: 'is_member_kelipatan', allowBlank: false, type: 'text'},
            {name: 'tanggal', allowBlank: false, type: 'text'},
            {name: 'keterangan', allowBlank: false, type: 'text'},
            {name: 'approve_by', allowBlank: false, type: 'text'},
            {name: 'approve_date', allowBlank: false, type: 'text'},
            {name: 'tgl_start_diskon', allowBlank: false, type: 'text'},
            {name: 'tgl_end_diskon', allowBlank: false, type: 'text'},
            {name: 'is_validasi', allowBlank: false, type: 'text'}
        ],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("harga_penjualan_proyek/search_produk_history") ?>',
        method: 'POST'
    }),
    writer: new Ext.data.JsonWriter(
        {
            encode: true,
            writeAllFields: true
        })
});

var gridhargapenjualanproyekhistory = new Ext.grid.GridPanel({
    store: strhargapenjualanproyekhistory,
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
        dataIndex: 'approve_date',
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
        dataIndex: 'net_hrg_supplier_sup_inc',
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
        dataIndex: 'rp_het_harga_beli',
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
        header: 'Harga Jual Proyek',
        dataIndex: 'rp_jual_proyek',
        width: 180
    },{
        header: '% / Rp',
        dataIndex: 'disk_proyek1_op',
        width: 50
    },{
        xtype: 'numbercolumn',
        align: 'right',
        // format: '0,0',
        header: 'Diskon Proyek 1',
        dataIndex: 'disk_proyek1',
        width: 150
    },{
        header: '% / Rp',
        dataIndex: 'disk_proyek2_op',
        width: 50
    },{
        xtype: 'numbercolumn',
        align: 'right',
        // format: '0,0',
        header: 'Diskon Proyek 2',
        dataIndex: 'disk_proyek2',
        width: 150
    },{
        header: '% / Rp',
        dataIndex: 'disk_proyek3_op',
        width: 50
    },{
        xtype: 'numbercolumn',
        align: 'right',
        // format: '0,0',
        header: 'Diskon Proyek 3',
        dataIndex: 'disk_proyek3',
        width: 150
    },{
        header: '% / Rp',
        dataIndex: 'disk_proyek4_op',
        width: 50
    },{
        xtype: 'numbercolumn',
        align: 'right',
        // format: '0,0',
        header: 'Diskon Proyek 4',
        dataIndex: 'disk_proyek4',
        width: 150
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Diskon Proyek 5',
        dataIndex: 'disk_amt_proyek5',
        width: 150
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Net Price Jual Proyek',
        dataIndex: 'rp_jual_proyek_net',
        width: 150
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Qty Beli |Proyek|',
        dataIndex: 'qty_beli_bonus',
        width: 150
    },{
        header: 'Kd Produk |Proyek|',
        dataIndex: 'kd_produk_proyek',
        width: 150
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Qty Bonus |Proyek|',
        dataIndex: 'qty_bonus',
        width: 150
    },{
        header: 'Kelipatan ? |Proyek|',
        dataIndex: 'is_bonus_kelipatan',
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
        header: 'Approved By',
        dataIndex: 'approve_by',
        width: 120
    },{
        header: 'Is Validasi',
        dataIndex: 'is_validasi',
        width: 100
    },{
        header: 'Ket. Perubahan',
        dataIndex: 'keterangan',
        width: 300
    }]
});

var winhargapenjualanproyekprint = new Ext.Window({
    id: 'id_winhargapenjualanproyekprint',
    title: 'Print History Harga Penjualan Proyek',
    closeAction: 'hide',
    width: 900,
    height: 450,
    layout: 'fit',
    border: false,
    html:'<iframe style="width:100%;height:100%;" id="hargapenjualanproyekprint" src=""></iframe>'
});

Ext.ns('hargapenjualanproyekform');
hargapenjualanproyekform.Form = Ext.extend(Ext.form.FormPanel, {

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
        hargapenjualanproyekform.Form.superclass.constructor.call(this, config);
    },
    initComponent: function(){

        // hard coded - cannot be changed from outsid
        var config = {
            layout:'form',
            items: [gridhargapenjualanproyekhistory],
            buttons: [{
                text: 'Cetak',
                id: 'btnCetakhargapenjualanproyek',
                scope: this,
                handler: function(){
                    function isEmpty(str) {
                        return (!str || 0 === str.length);
                    }
                    var no_bukti = Ext.getCmp('id_cbhjbnobukti').getValue();
                    var kd_produk = Ext.getCmp('id_cbhjbproduk').getValue();

                    if(isEmpty(no_bukti)){
                        no_bukti = 0;
                    }
                    winhargapenjualanproyekprint.show();
                    Ext.getDom('hargapenjualanproyekprint').src = '<?= site_url("harga_penjualan_proyek/print_form") ?>' +'/'+no_bukti+'/'+kd_produk;
                }
            },{
                text: 'Close',
                id: 'btnClosehargapenjualanproyek',
                scope: this,
                handler: function(){
                    winshowhistoryhargapenjualanproyek.hide();
                }
            }]
        }; // eo config object
        // apply config
        Ext.apply(this, Ext.apply(this.initialConfig, config));

        // call parent
        hargapenjualanproyekform.Form.superclass.initComponent.apply(this, arguments);

    } // eo function initComponent  
    ,
    onRender: function(){

        // call parent
        hargapenjualanproyekform.Form.superclass.onRender.apply(this, arguments);

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
Ext.reg('formaddhargapenjualanproyek', hargapenjualanproyekform.Form);

var winshowhistoryhargapenjualanproyek = new Ext.Window({
    id: 'id_winshowhistoryhargapenjualanproyek',
    closeAction: 'hide',
    width: 1000,
    height: 500,
    layout: 'fit',
    border: false,
    items: {
        id: 'id_formaddhargapenjualanproyek',
        xtype: 'formaddhargapenjualanproyek'
    },
    onHide: function(){
        Ext.getCmp('id_formaddhargapenjualanproyek').getForm().reset();
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

var searchhjbproduk = new Ext.app.SearchField({
    store: strcbkdprodukhjd,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 220,
    id: 'hjdsearchlistbarang'
});

var tbhjbproduk = new Ext.Toolbar({
    items: [searchhjbproduk]
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
                            var senders = Ext.getCmp('hjp_gridsender').getValue();
                            if(senders === 'hjb_kd_produk_proyek'){
                                Ext.getCmp(senders).setValue(sel[0].get('kd_produk'));
                            }else if(senders === 'hjd_kd_produk_member'){{
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
                menuhjpro.hide();
            }
        }
    },
    tbar:tbhjbproduk,
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strcbkdprodukhjd,
        displayInfo: true
    })
});



var menuhjpro = new Ext.menu.Menu();
menuhjpro.add(new Ext.Panel({
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
            menuhjpro.hide();
        }
    }]
}));

Ext.ux.TwinCombohjpro = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function(){
        //load store grid
        Ext.getCmp('hjp_gridsender').setValue(this.id);
        strcbkdprodukhjd.load();
        menuhjpro.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

var strhargapenjualanproyek = new Ext.data.Store({
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
            {name: 'disk_proyek1_op', allowBlank: false, type: 'text'},
            {name: 'disk_proyek2_op', allowBlank: false, type: 'text'},
            {name: 'disk_proyek3_op', allowBlank: false, type: 'text'},
            {name: 'disk_proyek4_op', allowBlank: false, type: 'text'},
            {name: 'disk_agen1_op', allowBlank: false, type: 'text'},
            {name: 'disk_agen2_op', allowBlank: false, type: 'text'},
            {name: 'disk_agen3_op', allowBlank: false, type: 'text'},
            {name: 'disk_agen4_op', allowBlank: false, type: 'text'},
            {name: 'disk_proyek1', allowBlank: false, type: 'float'},
            {name: 'disk_proyek2', allowBlank: false, type: 'float'},
            {name: 'disk_proyek3', allowBlank: false, type: 'float'},
            {name: 'disk_proyek4', allowBlank: false, type: 'float'},
            {name: 'disk_amt_proyek5', allowBlank: false, type: 'int'},
            {name: 'rp_jual_proyek_net', allowBlank: false, type: 'int'},
            {name: 'disk_agen1', allowBlank: false, type: 'float'},
            {name: 'disk_agen2', allowBlank: false, type: 'float'},
            {name: 'disk_agen3', allowBlank: false, type: 'float'},
            {name: 'disk_agen4', allowBlank: false, type: 'float'},
            {name: 'disk_amt_agen5', allowBlank: false, type: 'int'},
            {name: 'rp_jual_agen_net', allowBlank: false, type: 'int'},
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
            {name: 'rp_jual_proyek', allowBlank: false, type: 'int'},
            {name: 'rp_jual_agen', allowBlank: false, type: 'int'},
            {name: 'rp_jual_distribusi', allowBlank: false, type: 'int'},
            {name: 'qty_beli_bonus', allowBlank: false, type: 'int'},
            {name: 'kd_produk_proyek', allowBlank: false, type: 'text'},
            {name: 'qty_bonus', allowBlank: false, type: 'int'},
            {name: 'is_bonus_kelipatan', allowBlank: false, type: 'text'},
            {name: 'qty_agen', allowBlank: false, type: 'int'},
            {name: 'kd_produk_agen', allowBlank: false, type: 'text'},
            {name: 'qty_beli_agen', allowBlank: false, type: 'int'},
            {name: 'is_member_kelipatan', allowBlank: false, type: 'text'},
            {name: 'keterangan', allowBlank: false, type: 'text'},
            {name: 'is_validasi', allowBlank: false, type: 'text'},
            {name: 'tgl_start_diskon', allowBlank: false, type: 'text'},
            {name: 'tgl_end_diskon', allowBlank: false, type: 'text'}
        ],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("harga_penjualan_proyek/search_produk_by_kategori") ?>',
        method: 'POST'
    }),
    writer: new Ext.data.JsonWriter(
        {
            encode: true,
            writeAllFields: true
        })
});

strhargapenjualanproyek.on('load',function(){
    strhargapenjualanproyek.setBaseParam('kd_supplier',Ext.getCmp('id_cbhjsuplier_proyek').getValue());
    strhargapenjualanproyek.setBaseParam('kd_kategori1',Ext.getCmp('hjp_cbkategori1').getValue());
    strhargapenjualanproyek.setBaseParam('kd_kategori2',Ext.getCmp('hjp_cbkategori2').getValue());
    strhargapenjualanproyek.setBaseParam('kd_kategori3',Ext.getCmp('hjp_cbkategori3').getValue());
    strhargapenjualanproyek.setBaseParam('kd_kategori4',Ext.getCmp('hjp_cbkategori4').getValue());
    strhargapenjualanproyek.setBaseParam('kd_ukuran',Ext.getCmp('id_hjp_cbukuran').getValue());
    strhargapenjualanproyek.setBaseParam('no_bukti',Ext.getCmp('id_cbhjnobuktifilter_proyek').getValue());
    strhargapenjualanproyek.setBaseParam('konsinyasi',Ext.getCmp('hjp_konsinyasi').getValue());
});

strhargapenjualanproyek.on('update',function(){
    var net_price = Ext.getCmp('ehjp_hrg_beli_satuan').getValue();
    var edited = Ext.getCmp('hjp_edited').getValue();
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
    if(Ext.getCmp('ehjp_het_cogs').getValue() === 0){
        if(Ext.getCmp('ehjp_rp_jual_proyek').getValue() < Ext.getCmp('ehjp_rp_het_harga_beli').getValue()){
            Ext.getCmp('ehjp_rp_jual_proyek').setValue('0');
            Ext.Msg.show({
                title: 'Error',
                msg: 'Net Price Jual tidak boleh lebih kecil dari HET Net Price Beli (inc PPN 1)',
                modal: true,
                icon: Ext.Msg.ERROR,
                buttons: Ext.Msg.OK,
                fn: function(btn){
                    Ext.getCmp('ehjp_rp_jual_proyek').focus();
                }
            });
            Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
        }
        if(Ext.getCmp('ehjp_rp_cogs').getValue() > 0){
            if(Ext.getCmp('ehj_net_price_jual_proyek').getValue() < Ext.getCmp('ehjp_rp_cogs').getValue()){
                Ext.getCmp('ehj_net_price_jual_proyek').setValue('0');
                Ext.Msg.show({
                    title: 'Error',
                    msg: 'Net Price Jual tidak boleh lebih kecil dari HET COGS (inc PPN)',
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK,
                    fn: function(btn){
                        Ext.getCmp('ehj_net_price_jual_proyek').focus();
                    }
                });
                Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
            }
        }
    }else{
        if(Ext.getCmp('ehjp_rp_jual_proyek').getValue() < Ext.getCmp('ehjp_het_cogs').getValue()){
            Ext.getCmp('ehjp_rp_jual_proyek').setValue('0');
            Ext.Msg.show({
                title: 'Error',
                msg: 'Net Price Jual tidak boleh lebih kecil dari HET COGS (inc PPN)',
                modal: true,
                icon: Ext.Msg.ERROR,
                buttons: Ext.Msg.OK,
                fn: function(btn){
                    Ext.getCmp('ehjp_rp_jual_proyek').focus();
                }
            });
            Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
        }
        //if(Ext.getCmp('ehj_net_price_jual_proyek').getValue() < Ext.getCmp('ehjp_rp_het_harga_beli').getValue()){
        //	Ext.getCmp('ehj_net_price_jual_proyek').setValue('0');
        //	Ext.Msg.show({
        //		title: 'Error',
        //		msg: 'Net Price Jual tidak boleh lebih kecil dari HET Net Price Beli (inc PPN 3)',
        //		modal: true,
        //		icon: Ext.Msg.ERROR,
        //		buttons: Ext.Msg.OK,
        //		fn: function(btn){
        //			Ext.getCmp('ehj_net_price_jual_proyek').focus();
        //		}
        //	});
        //	Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');  
        //}

    }
    /*
     if(Ext.getCmp('ehjd_rp_jual_distribusi').getValue() < Ext.getCmp('ehjp_rp_het_harga_beli').getValue()){
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

function HETChange(){
    var hrg_beli = Ext.getCmp('ehjp_hrg_beli_satuan').getValue();
    var cogs = Ext.getCmp('ehjp_rp_cogs').getValue();
    var ongkos = Ext.getCmp('ehjp_rp_ongkos_kirim').getValue();
    var margin_op = Ext.getCmp('hjp_margin_op').getValue();
    var margin = Ext.getCmp('ehjd_margin').getValue();
    var margin_rp = 0;
    if(margin_op === "%"){
        margin_rp = (margin*hrg_beli)/100;
        var margin_pct = margin;
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
    ongkos = Ext.getCmp('ehjp_rp_ongkos_kirim').getValue();
    var HETCOGS = (cogs+ongkos+margin_rp) * 1.1;
    if(cogs === 0){
        HETCOGS = 0;
    }
    Ext.getCmp('ehjp_rp_het_harga_beli').setValue(HET);
    Ext.getCmp('ehjp_het_cogs').setValue(HETCOGS);
    Ext.getCmp('ehjp_pct_margin').setValue(margin_pct);
    Ext.getCmp('ehjp_rp_margin').setValue(margin_rp);
    Edited_hj_proyek();
};

function Edited_hj_proyek(){
    Ext.getCmp('hjp_edited').setValue('Y');
    Ext.getCmp('hjp_is_validasi').setValue('N');
};

function HitungNetPJualProyek(){
    Edited_hj_proyek();
    var total_disk = 0;
    var rp_jual_proyek = Ext.getCmp('ehjp_rp_jual_proyek').getValue();
    var disk_proyek1_op = Ext.getCmp('hjp_disk_proyek1_op').getValue();
    var disk_proyek1 = Ext.getCmp('hjp_disk_proyek1').getValue();
    if (disk_proyek1_op === '%'){
        // disk_proyek1 = (disk_proyek1*rp_jual_proyek)/100;
        total_disk = rp_jual_proyek-(rp_jual_proyek*(disk_proyek1/100));
    }else{
        total_disk = rp_jual_proyek-disk_proyek1;
    }

    var disk_proyek2_op = Ext.getCmp('hjb_disk_proyek2_op').getValue();
    var disk_proyek2 = Ext.getCmp('hjb_disk_proyek2').getValue();
    if (disk_proyek2_op === '%'){
        // disk_proyek2 = (disk_proyek2*disk_proyek1)/100;
        total_disk =  total_disk-(total_disk*(disk_proyek2/100));
    }else{
        total_disk = total_disk-disk_proyek2;
    }

    var disk_proyek3_op = Ext.getCmp('hjb_disk_proyek3_op').getValue();
    var disk_proyek3 = Ext.getCmp('hjb_disk_proyek3').getValue();
    if (disk_proyek3_op === '%'){
        // disk_proyek3 = (disk_proyek3*disk_proyek2)/100;
        total_disk = total_disk-(total_disk*(disk_proyek3/100));
    }else{
        total_disk = total_disk-disk_proyek3;
    }

    var disk_proyek4_op = Ext.getCmp('hjb_disk_proyek4_op').getValue();
    var disk_proyek4 = Ext.getCmp('hjb_disk_proyek4').getValue();
    if (disk_proyek4_op === '%'){
        // disk_proyek4 = (disk_proyek4*disk_proyek3)/100;
        total_disk = total_disk-(total_disk*(disk_proyek4/100));
    }else{
        total_disk = total_disk-disk_proyek4;
    }

    var total_disk = total_disk-Ext.getCmp('hjb_disk_proyek5').getValue();

    var net_jual_kons = total_disk;
    Ext.getCmp('ehj_net_price_jual_proyek').setValue(net_jual_kons);
    if(Ext.getCmp('ehjp_rp_cogs').getValue() > 0){
                        if(net_jual_kons < Ext.getCmp('ehjp_rp_cogs').getValue()){
                            //this.setValue('0');
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
                        if(net_jual_kons < Ext.getCmp('ehjp_rp_het_harga_beli').getValue()){
                            //this.setValue('0');
                            Ext.Msg.show({
                                title: 'Error',
                                msg: 'Net Price Jual tidak boleh lebih kecil dari HET Net Price Beli (inc PPN).  Apakah Akan Dilanjutkan ???',
                                modal: true,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OKCANCEL,
                                fn: function(btn){
                                    this.focus();
                                    //Ext.Msg.alert('', 'You clicked the <code>' + btn + '</code> button.');
                                    switch (btn) {
                                        case "ok":
                                            Ext.getCmp('hjp_is_validasi').setValue('Y');
                                            break;
                                        case "cancel":
                                            Ext.getCmp('ehjp_rp_jual_proyek').setValue('0');
                                            Ext.getCmp('ehj_net_price_jual_proyek').setValue('0');
                                            break;
                                        default:
                                            // something else
                                            break;
                                    } // switch
                                }
                            });
                            Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                        }
                    }
   

    
}
// combobox kategori1
var str_hjp_cbkategori1 = new Ext.data.Store({
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
            var r = new (str_hjp_cbkategori1.recordType)({
                'kd_kategori1': '',
                'nama_kategori1': '-----'
            });
            str_hjp_cbkategori1.insert(0, r);
        },
        loadexception: function(event, options, response, error){
            var err = Ext.util.JSON.decode(response.responseText);
            if (err.errMsg === 'Session Expired') {
                session_expired(err.errMsg);
            }
        }
    }
});

var hjp_cbkategori1 = new Ext.form.ComboBox({
    fieldLabel: 'Kategori 1',
    id: 'hjp_cbkategori1',
    store: str_hjp_cbkategori1,
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
            var kdhjp_cbkategori1 = hjp_cbkategori1.getValue();
            // hjp_cbkategori2.setValue();
            hjp_cbkategori2.store.proxy.conn.url = '<?= site_url("kategori3/get_kategori2") ?>/' + kdhjp_cbkategori1;
            hjp_cbkategori2.store.reload();

        }
    }
});
// combobox kategori2
var str_hjp_cbkategori2 = new Ext.data.Store({
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
            var r = new (str_hjp_cbkategori2.recordType)({
                'kd_kategori2': '',
                'nama_kategori2': '-----'
            });
            str_hjp_cbkategori2.insert(0, r);
        },
        loadexception: function(event, options, response, error){
            var err = Ext.util.JSON.decode(response.responseText);
            if (err.errMsg === 'Session Expired') {
                session_expired(err.errMsg);
            }
        }
    }
});

var hjp_cbkategori2 = new Ext.form.ComboBox({
    fieldLabel: 'Kategori 2 ',
    id: 'hjp_cbkategori2',
    mode: 'local',
    store: str_hjp_cbkategori2,
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
            var kd_hjp_cbkategori1 = hjp_cbkategori1.getValue();
            var kd_hjp_cbkategori2 = this.getValue();
            hjp_cbkategori3.setValue();
            hjp_cbkategori3.store.proxy.conn.url = '<?= site_url("kategori4/get_kategori3") ?>/' + kd_hjp_cbkategori1 +'/'+ kd_hjp_cbkategori2;
            hjp_cbkategori3.store.reload();

        }
    }
});

// combobox kategori3
var str_hjp_cbkategori3 = new Ext.data.Store({
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
            var r = new (str_hjp_cbkategori3.recordType)({
                'kd_kategori3': '',
                'nama_kategori3': '-----'
            });
            str_hjp_cbkategori3.insert(0, r);
        },
        loadexception: function(event, options, response, error){
            var err = Ext.util.JSON.decode(response.responseText);
            if (err.errMsg === 'Session Expired') {
                session_expired(err.errMsg);
            }
        }
    }
});

var hjp_cbkategori3 = new Ext.form.ComboBox({
    fieldLabel: 'Kategori 3 ',
    id: 'hjp_cbkategori3',
    mode: 'local',
    store: str_hjp_cbkategori3,
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
            var kd_hjp_cbkategori1 = hjp_cbkategori1.getValue();
            var kd_hjp_cbkategori2 = hjp_cbkategori2.getValue();
            var kd_hjp_cbkategori3 = this.getValue();
            hjp_cbkategori4.setValue();
            hjp_cbkategori4.store.proxy.conn.url = '<?= site_url("master_barang/get_kategori4") ?>/' + kd_hjp_cbkategori1 +'/'+ kd_hjp_cbkategori2 +'/'+ kd_hjp_cbkategori3;
            hjp_cbkategori4.store.reload();


        }
    }
});

// combobox kategori4
var str_hjp_cbkategori4 = new Ext.data.Store({
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
            var r = new (str_hjp_cbkategori4.recordType)({
                'kd_kategori4': '',
                'nama_kategori4': '-----'
            });
            str_hjp_cbkategori4.insert(0, r);
        },
        loadexception: function(event, options, response, error){
            var err = Ext.util.JSON.decode(response.responseText);
            if (err.errMsg === 'Session Expired') {
                session_expired(err.errMsg);
            }
        }
    }
});

var hjp_cbkategori4 = new Ext.form.ComboBox({
    fieldLabel: 'Kategori 4 ',
    id: 'hjp_cbkategori4',
    mode: 'local',
    store: str_hjp_cbkategori4,
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
var str_hjp_cbukuran = new Ext.data.Store({
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
            var r = new (str_hjp_cbukuran.recordType)({
                'kd_ukuran': '',
                'nama_ukuran': '-----'
            });
            str_hjp_cbukuran.insert(0, r);
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
var str_hjp_cbsatuan = new Ext.data.Store({
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
            var r = new (str_hjp_cbsatuan.recordType)({
                'kd_satuan': '',
                'nm_satuan': '-----'
            });
            str_hjp_cbsatuan.insert(0, r);
        },
        loadexception: function(event, options, response, error){
            var err = Ext.util.JSON.decode(response.responseText);
            if (err.errMsg === 'Session Expired') {
                session_expired(err.errMsg);
            }
        }
    }
});
var hjp_cbsatuan = new Ext.form.ComboBox({
    fieldLabel: 'Satuan',
    id: 'id_hjp_cbsatuan',
    store: str_hjp_cbsatuan,
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

var hjp_cbukuran = new Ext.form.ComboBox({
    fieldLabel: 'Ukuran',
    id: 'id_hjp_cbukuran',
    store: str_hjp_cbukuran,
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

var searchgridhargapenjualanproyek = new Ext.app.SearchField({
    store: strhargapenjualanproyek,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridhargapenjualanproyek',
    emptyText: 'Kode Barang, Kode Barang Lama, Nama Barang'
});

searchgridhargapenjualanproyek.onTrigger1Click = function(evt) {
    if (this.hasSearch) {
        this.el.dom.value = '';

        // Get the value of search field
        var kd_kategori1 = Ext.getCmp('hjp_cbkategori1').getValue();
        var kd_kategori2 = Ext.getCmp('hjp_cbkategori2').getValue();
        var kd_kategori3 = Ext.getCmp('hjp_cbkategori3').getValue();
        var kd_kategori4 = Ext.getCmp('hjp_cbkategori4').getValue();
        var konsinyasi = Ext.getCmp('hjp_konsinyasi').getValue();
        var kd_supplier = Ext.getCmp('id_cbhjsuplier_proyek').getValue();
        var list = Ext.getCmp('ehjp_list').getValue();
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

searchgridhargapenjualanproyek.onTrigger2Click = function(evt) {
    var text = this.getRawValue();
    if (text.length < 1) {
        this.onTrigger1Click();
        return;
    }

    // Get the value of search field
    var kd_kategori1 = Ext.getCmp('hjp_cbkategori1').getValue();
    var kd_kategori2 = Ext.getCmp('hjp_cbkategori2').getValue();
    var kd_kategori3 = Ext.getCmp('hjp_cbkategori3').getValue();
    var kd_kategori4 = Ext.getCmp('hjp_cbkategori4').getValue();
    var konsinyasi = Ext.getCmp('hjp_konsinyasi').getValue();
    var kd_supplier = Ext.getCmp('id_cbhjsuplier_proyek').getValue();
    var list = Ext.getCmp('ehjp_list').getValue();
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

var strcbhjsuplier_proyek = new Ext.data.ArrayStore({
    fields: ['kd_supplier'],
    data : []
});

var strgridhjsuplier_proyek = new Ext.data.Store({
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

var searchgridhjsuplier_proyek = new Ext.app.SearchField({
    store: strgridhjsuplier_proyek,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridhjsuplier_proyek'
});

strgridhjsuplier_proyek.on('load', function(){
    Ext.getCmp('id_searchgridhjsuplier_proyek').focus();
});

var gridhjsuplier_proyek = new Ext.grid.GridPanel({
    store: strgridhjsuplier_proyek,
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
        items: [searchgridhjsuplier_proyek]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: strgridhjsuplier_proyek,
        displayInfo: true
    }),listeners: {
        'rowdblclick': function(){
            var sm = this.getSelectionModel();
            var sel = sm.getSelections();
            if (sel.length > 0) {
                Ext.getCmp('id_cbhjsuplier_proyek').setValue(sel[0].get('kd_supplier'));
                Ext.getCmp('hjp_nama_supplier').setValue(sel[0].get('nama_supplier'));
                if(sel[0].get('pkp') === '1'){
                    Ext.getCmp('hjp_pkp').setValue('YA');
                }else{
                    Ext.getCmp('hjp_pkp').setValue('TIDAK');
                }


                menuhjsuplier_proyek.hide();
            }
        }
    }
});

var menuhjsuplier_proyek = new Ext.menu.Menu();
menuhjsuplier_proyek.add(new Ext.Panel({
    title: 'Pilih Supplier',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridhjsuplier_proyek],
    buttons: [{
        text: 'Close',
        handler: function(){
            menuhjsuplier_proyek.hide();
        }
    }]
}));

Ext.ux.TwinCombohjsuplier_proyek = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function(){
        //load store grid
        strgridhjsuplier_proyek.load();
        menuhjsuplier_proyek.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuhjsuplier_proyek.on('hide', function(){
    var sf = Ext.getCmp('id_searchgridhjsuplier_proyek').getValue();
    if( sf !== ''){
        Ext.getCmp('id_searchgridhjsuplier_proyek').setValue('');
        searchgridhjsuplier_proyek.onTrigger2Click();
    }
});

var cbhjsuplier_proyek = new Ext.ux.TwinCombohjsuplier_proyek({
    fieldLabel: 'Supplier <span class="asterix">*</span>',
    id: 'id_cbhjsuplier_proyek',
    store: strcbhjsuplier_proyek,
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

var headerhargapenjualanproyek = {
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
            id: 'hjp_gridsender'
        },{
            xtype: 'textfield',
            fieldLabel: 'No Bukti',
            name: 'no_hjp',
            readOnly:true,
            fieldClass:'readonly-input',
            id: 'hjp_no_hjp',
            anchor: '90%',
            value:''
        },cbhjsuplier_proyek,cbhjnobuktifilter_proyek,
            hjp_cbkategori1,hjp_cbkategori2,{
                xtype: 'textarea',
                fieldLabel: 'Kode Barang, Kode Barang Lama',
                style:'text-transform: uppercase',
                name: 'list',
                id: 'ehjp_list',
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
                id:'hjp_konsinyasi',
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
            id: 'hjp_tanggal',
            anchor: '90%',
            value: new Date()
        },{
            xtype: 'textfield',
            fieldLabel: 'Nama Supplier',
            name: 'nama_supplier',
            readOnly:true,
            fieldClass:'readonly-input',
            id: 'hjp_nama_supplier',
            anchor: '90%',
            value:''
        },{
            xtype: 'textfield',
            fieldLabel: 'Status PKP',
            name: 'pkp',
            readOnly:true,
            fieldClass:'readonly-input',
            id: 'hjp_pkp',
            anchor: '90%',
            value:''
        },hjp_cbkategori3,hjp_cbkategori4,hjp_cbukuran,hjp_cbsatuan

        ]
    }]
    ,
    buttons: [{
        text: 'Filter',
        formBind: true,
        handler: function(){
            var kd_supplier =  Ext.getCmp('id_cbhjsuplier_proyek').getValue();
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
            strhargapenjualanproyek.load({
                params:{
                    start: STARTPAGE,
                    limit: ENDPAGE,
                    kd_supplier: Ext.getCmp('id_cbhjsuplier_proyek').getValue(),
                    kd_kategori1: Ext.getCmp('hjp_cbkategori1').getValue(),
                    kd_kategori2: Ext.getCmp('hjp_cbkategori2').getValue(),
                    kd_kategori3: Ext.getCmp('hjp_cbkategori3').getValue(),
                    kd_kategori4: Ext.getCmp('hjp_cbkategori4').getValue(),
                    kd_ukuran: Ext.getCmp('id_hjp_cbukuran').getValue(),
                    kd_satuan: Ext.getCmp('id_hjp_cbsatuan').getValue(),
                    konsinyasi: Ext.getCmp('hjp_konsinyasi').getValue(),
                    no_bukti: Ext.getCmp('id_cbhjnobuktifilter_proyek').getValue(),
                    list: Ext.getCmp('ehjp_list').getValue()
                }
            });
        }
    }]
};

var actionhargapenjualanproyek = new Ext.ux.grid.RowActions({
    header :'History',
    autoWidth: false,
    locked: true,
    width: 60,
    actions:[{iconCls: 'icon-history-record', qtip: 'Show History'}],
    widthIntercept: Ext.isSafari ? 4 : 2
});


actionhargapenjualanproyek.on('action', function(grid, record, action, row, col) {
    var kd_supp = record.get('kd_supplier');
    var kd_prod = record.get('kd_produk');
    var nm_prod = record.get('nama_produk');
    switch(action) {
        case 'icon-history-record':
            var sm = gridhargapenjualanproyek.getSelectionModel();
            var sel = sm.getSelections();
            gridhargapenjualanproyekhistory.store.proxy.conn.url = '<?= site_url("harga_penjualan_distribusi/search_produk_history") ?>/' +sel[0].get('kd_produk');
            gridhargapenjualanproyekhistory.store.reload();
            winshowhistoryhargapenjualanproyek.setTitle('History');
            winshowhistoryhargapenjualanproyek.show();
            break;
    }
});
var editorhargapenjualanproyek = new Ext.ux.grid.RowEditor({
    saveText: 'Update'
});

var gridhargapenjualanproyek = new Ext.grid.GridPanel({
    store: strhargapenjualanproyek,
    stripeRows: true,
    height: 350,
    loadMask: true,
    frame: true,
    border:true,
    plugins: [editorhargapenjualanproyek],
    columns: [ {
        dataIndex: 'kd_diskon_sales',
        hidden: true
    },{
        dataIndex: 'pct_margin',
        hidden: true,
        editor: new Ext.form.TextField({
            readOnly: true,
            id: 'ehjp_pct_margin'
        })
    },{
        dataIndex: 'rp_margin',
        hidden: true,
        editor: new Ext.form.TextField({
            readOnly: true,
            id: 'ehjp_rp_margin'
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
            id:           	'hjp_edited',
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
            id: 'ehjp_kd_produk'
        })
    },{
        header: 'Kode Barang Lama',
        dataIndex: 'kd_produk_lama',
        width: 110,
        sortable: true,
        editor: new Ext.form.TextField({
            readOnly: true,
            fieldClass: 'readonly-input',
            id: 'ehjp_kd_produk_lama'
        })
    },{
        header: 'Nama Barang',
        dataIndex: 'nama_produk',
        width: 300,
        sortable: true,
        editor: new Ext.form.TextField({
            readOnly: true,
            fieldClass: 'readonly-input',
            id: 'ehjp_nama_produk'
        })
    },{
        header: 'Satuan',
        dataIndex: 'nm_satuan',
        width: 80,
        editor: new Ext.form.TextField({
            readOnly: true,
            fieldClass: 'readonly-input',
            id: 'ehjp_satuan'
        })
    },{
        header: 'Nama Supplier',
        dataIndex: 'nama_supplier',
        width: 130,
        editor: new Ext.form.TextField({
            readOnly: true,
            fieldClass: 'readonly-input',
            id: 'ehjp_nama_supplier'
        })
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Net Price Pembelian',
        dataIndex: 'net_hrg_supplier_sup_inc',
        width: 150,
        editor: {
            xtype: 'numberfield',
            id: 'ehjp_hrg_beli_satuan',
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
            id: 'ehjp_rp_cogs',
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
            id: 'ehjp_rp_ongkos_kirim',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        HETChange();
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
            id:           	'hjp_margin_op',
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
                    Ext.getCmp('ehjp_margin').setValue(0);
                    HETChange();
                },
                select:function(){
                    HETChange();
                    Ext.getCmp('ehjp_margin').setMaxValue(Number.MAX_VALUE);
                    if (this.getValue() === 'persen')
                        Ext.getCmp('ehjp_margin').maxValue = 100;
                    else
                        Ext.getCmp('ehjp_margin').maxLength = 11;
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
            id: 'ehjp_margin',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        HETChange();
                    }, c);
                }
            }
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'HET Net Price Beli (Inc.PPN)',
        dataIndex: 'rp_het_harga_beli',
        width: 180,
        editor: {
            xtype: 'numberfield',
            id: 'ehjp_rp_het_harga_beli',
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
            id: 'ehjp_het_cogs',
            readOnly: true
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Rp Jual Proyek',
        dataIndex: 'rp_jual_proyek',
        width: 180,
        editor: {
            xtype: 'numberfield',
            id: 'ehjp_rp_jual_proyek',
            listeners:{
                'change': function() {
                    HitungNetPJualProyek();
                      if(Ext.getCmp('ehjp_rp_cogs').getValue() > 0){
                        if(this.getValue() < Ext.getCmp('ehjp_rp_cogs').getValue()){
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
                        if(this.getValue() < Ext.getCmp('ehjp_rp_het_harga_beli').getValue()){
                           
                            Ext.Msg.show({
                                title: 'Error',
                                msg: 'Net Price Jual tidak boleh lebih kecil dari HET Net Price Beli (inc PPN).  Apakah Akan Dilanjutkan ???',
                                modal: true,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OKCANCEL,
                                fn: function(btn){
                                    this.focus();
                                    //Ext.Msg.alert('', 'You clicked the <code>' + btn + '</code> button.');
                                    switch (btn) {
                                        case "ok":
                                             Ext.getCmp('hjp_is_validasi').setValue('Y');
                                            break;
                                        case "cancel":
                                            Ext.getCmp('ehjp_rp_jual_proyek').setValue('0');
                                             
                                            break;
                                        default:
                                            // something else
                                            break;
                                    } // switch
                                }
                            });
                            Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                        }
                    }


                },
//                'render': function(c) {
//                    c.getEl().on('keyup', function() {
//                        // Edited();
//                        HitungNetPJualProyek();
//                    }, c);
//                }
            }
        }
    },{
        header: '% / Rp',
        dataIndex: 'disk_proyek1_op',
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
            id:           	'hjp_disk_proyek1_op',
            mode:           'local',
            name:           'disk_proyek1_op',
            value:          '%',
            width:			50,
            editable:       false,
            hiddenName:     'disk_proyek1_op',
            valueField:     'name',
            displayField:   'name',
            triggerAction:  'all',
            forceSelection: true,
            listeners:{
                'expand':function(){
                    Ext.getCmp('hjp_disk_proyek1').setValue(0);
                },
                select:function(){
                    Ext.getCmp('hjp_disk_proyek1').setMaxValue(Number.MAX_VALUE);
                    if (this.getValue() === 'persen')
                        Ext.getCmp('hjp_disk_proyek1').maxValue = 100;
                    else
                        Ext.getCmp('hjp_disk_proyek1').maxLength = 11;
                }
            }
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        // format: '0,0',
        header: 'Diskon Proyek 1',
        dataIndex: 'disk_proyek1',
        width: 150,
        editor: {
            xtype: 'numberfield',
            msgTarget: 'under',
            flex:1,
            width:115,
            name : 'disk_proyek1',
            id: 'hjp_disk_proyek1',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        HitungNetPJualProyek();
                    }, c);
                }

            }
        }
    },{
        header: '% / Rp',
        dataIndex: 'disk_proyek2_op',
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
            id:           	'hjb_disk_proyek2_op',
            mode:           'local',
            name:           'disk_proyek2_op',
            value:          '%',
            width:			50,
            editable:       false,
            hiddenName:     'disk_proyek2_op',
            valueField:     'name',
            displayField:   'name',
            triggerAction:  'all',
            forceSelection: true,
            listeners:{
                'expand':function(){
                    Ext.getCmp('hjb_disk_proyek2').setValue(0);
                },
                select:function(){
                    Ext.getCmp('hjb_disk_proyek2').setMaxValue(Number.MAX_VALUE);
                    if (this.getValue() === 'persen')
                        Ext.getCmp('hjb_disk_proyek2').maxValue = 100;
                    else
                        Ext.getCmp('hjb_disk_proyek2').maxLength = 11;
                }
            }
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        // format: '0,0',
        header: 'Diskon Proyek 2',
        dataIndex: 'disk_proyek2',
        width: 150,
        editor: {
            xtype: 'numberfield',
            msgTarget: 'under',
            flex:1,
            width:115,
            name : 'disk_proyek2',
            id: 'hjb_disk_proyek2',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                         //Edited();
                        HitungNetPJualProyek();
                    }, c);
                }
            }
        }
    },{
        header: '% / Rp',
        dataIndex: 'disk_proyek3_op',
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
            id:           	'hjb_disk_proyek3_op',
            mode:           'local',
            name:           'disk_proyek3_op',
            value:          '%',
            width:			50,
            editable:       false,
            hiddenName:     'disk_proyek3_op',
            valueField:     'name',
            displayField:   'name',
            triggerAction:  'all',
            forceSelection: true,
            listeners:{
                'expand':function(){
                    Ext.getCmp('hjb_disk_proyek3').setValue(0);
                },
                select:function(){
                    Ext.getCmp('hjb_disk_proyek3').setMaxValue(Number.MAX_VALUE);
                    if (this.getValue() === 'persen')
                        Ext.getCmp('hjb_disk_proyek3').maxValue = 100;
                    else
                        Ext.getCmp('hjb_disk_proyek3').maxLength = 11;
                }
            }
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        // format: '0,0',
        header: 'Diskon Proyek 3',
        dataIndex: 'disk_proyek3',
        width: 150,
        editor: {
            xtype: 'numberfield',
            msgTarget: 'under',
            flex:1,
            width:115,
            name : 'disk_proyek3',
            id: 'hjb_disk_proyek3',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // Edited();
                        HitungNetPJualProyek();
                    }, c);
                }
            }
        }
    },{
        header: '% / Rp',
        dataIndex: 'disk_proyek4_op',
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
            id:           	'hjb_disk_proyek4_op',
            mode:           'local',
            name:           'disk_proyek4_op',
            value:          '%',
            width:			50,
            editable:       false,
            hiddenName:     'disk_proyek4_op',
            valueField:     'name',
            displayField:   'name',
            triggerAction:  'all',
            forceSelection: true,
            listeners:{
                'expand':function(){
                    Ext.getCmp('hjb_disk_proyek4').setValue(0);
                },
                select:function(){
                    Ext.getCmp('hjb_disk_proyek4').setMaxValue(Number.MAX_VALUE);
                    if (this.getValue() === 'persen')
                        Ext.getCmp('hjb_disk_proyek4').maxValue = 100;
                    else
                        Ext.getCmp('hjb_disk_proyek4').maxLength = 11;
                }
            }
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        // format: '0,0',
        header: 'Diskon Proyek 4',
        dataIndex: 'disk_proyek4',
        width: 150,
        editor: {
            xtype: 'numberfield',
            msgTarget: 'under',
            flex:1,
            width:115,
            name : 'disk_proyek4',
            id: 'hjb_disk_proyek4',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // Edited();
                        HitungNetPJualProyek();
                    }, c);
                }
            }
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Diskon Proyek 5',
        dataIndex: 'disk_amt_proyek5',
        width: 150,
        editor: {
            xtype: 'numberfield',
            msgTarget: 'under',
            flex:1,
            width:115,
            name : 'disk_proyek5',
            id: 'hjb_disk_proyek5',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // Edited();
                        HitungNetPJualProyek();
                    }, c);
                }
            }
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Net Price Jual Proyek',
        dataIndex: 'rp_jual_proyek_net',
        width: 180,
        editor: {
            xtype: 'numberfield',
            id: 'ehj_net_price_jual_proyek',
            readOnly: true,
            fieldClass: 'readonly-input',
            listeners:{
                'change': function() {
                    if(Ext.getCmp('ehjp_rp_cogs').getValue() > 0){
                        if(Ext.getCmp('ehj_net_price_jual_proyek').getValue() < Ext.getCmp('ehjp_rp_cogs').getValue()){
                            Ext.getCmp('ehj_net_price_jual_proyek').setValue('0');
                            Ext.Msg.show({
                                title: 'Error',
                                msg: 'Net Price Jual Tidak Boleh Lebih Kecil Dari HET COGS',
                                modal: true,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OK,
                                fn: function(btn){
                                    Ext.getCmp('ehj_net_price_jual_proyek').focus();
                                }
                            });
                            Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                        }
                    }else{
                        if(Ext.getCmp('ehj_net_price_jual_proyek').getValue() < Ext.getCmp('ehjp_rp_het_harga_beli').getValue()){
                            Ext.getCmp('ehj_net_price_jual_proyek').setValue('0');
                            Ext.Msg.show({
                                title: 'Error',
                                msg: 'Net Price Jual Tidak Boleh Lebih Kecil Dari HET Beli',
                                modal: true,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OK,
                                fn: function(btn){
                                    Ext.getCmp('ehj_net_price_jual_proyek').focus();
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
        header: 'Is Validasi',
        dataIndex: 'is_validasi',
        width: 100,
        editor: {
            xtype: 'textfield',
            msgTarget: 'under',
            flex:1,
            width:100,
            fieldClass: 'readonly-input',
            name : 'is_validasi',
            id: 'hjp_is_validasi',
            style: 'text-align:right;'
           
        }
    },
   {
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Qty Beli',
        dataIndex: 'qty_beli_bonus',
        width: 150,
        editor: {
            xtype: 'numberfield',
            msgTarget: 'under',
            flex:1,
            width:115,
            name : 'qty_beli_bonus',
            id: 'hjp_qty_beli_bonus',
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
        header: 'Kd Produk ',
        dataIndex: 'kd_produk_proyek',
        width: 150,
        editor: new Ext.ux.TwinCombohjpro({
            id: 'hjb_kd_produk_proyek',
            store: strcbkdprodukhjd,
            valueField: 'kd_produk_proyek',
            displayField: 'kd_produk_proyek',
            typeAhead: true,
            editable: false,
            hiddenName: 'kd_produk_proyek',
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
        header: 'Qty Bonus ',
        dataIndex: 'qty_bonus',
        width: 150,
        editor: {
            xtype: 'numberfield',
            msgTarget: 'under',
            flex:1,
            width:115,
            name : 'qty_bonus',
            id: 'hjb_qty_bonus',
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
        header: 'Kelipatan ? ',
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
            id:           	'hjb_is_bonus_kelipatan',
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
                    xtype: 'datecolumn',
                    header: 'Tgl Mulai Diskon',
                    dataIndex: 'tgl_start_diskon',
                    format: 'd/m/Y',
                    width: 120,
                    editor: new Ext.form.DateField({
                        id: 'hjb_tgl_start_diskon',
                        format: 'd/m/Y',
                        //minValue: (new Date()).clearTime(),
                         listeners:{			
                            'change': function() {
                               	  Ext.getCmp('hjp_edited').setValue('Y');
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
                        id: 'hjb_tgl_end_diskon',
                        format: 'd/m/Y',
                        //minValue: (new Date()).clearTime(),
                        listeners:{			
                            'change': function() {
                               	  Ext.getCmp('hjp_edited').setValue('Y');
                            }
                        }
                    })
                }],
    tbar: new Ext.Toolbar({
        items: [searchgridhargapenjualanproyek, '->', cbhjbproduk, cbhjbnobukti, '-' ,{
            text: 'Show History',
            icon: BASE_ICONS + 'grid.png',
            onClick: function(){
                var kd_produk = Ext.getCmp('id_cbhjbproduk').getValue();
                var no_bukti = Ext.getCmp('id_cbhjbnobukti').getValue();
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
                gridhargapenjualanproyekhistory.store.load({
                    params:{
                        no_bukti: Ext.getCmp('id_cbhjbnobukti').getValue(),
                        kd_produk: Ext.getCmp('id_cbhjbproduk').getValue()
                    }
                });
                winshowhistoryhargapenjualanproyek.setTitle('History');
                winshowhistoryhargapenjualanproyek.show();
                // var sm = gridhargapenjualanproyek.getSelectionModel();
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
                // gridhargapenjualanproyekhistory.store.proxy.conn.url = '<?= site_url("harga_penjualan/search_produk_history") ?>/' +sel[0].get('kd_produk');
                // gridhargapenjualanproyekhistory.store.reload();
                // winshowhistoryhargapenjualanproyek.setTitle('History');
                // winshowhistoryhargapenjualanproyek.show();				        
            }
        },'-',{
            text: 'Reset',
            icon: BASE_ICONS + 'refresh.gif',
            onClick: function(){
                Ext.getCmp('id_cbhjbnobukti').setValue('');
                Ext.getCmp('id_cbhjbproduk').setValue('');
            }
        }]
    })
    // bbar: new Ext.PagingToolbar({
    // pageSize: ENDPAGE,
    // store: strhargapenjualanproyek,
    // displayInfo: true
    // })
});


var hargapenjualanproyek = new Ext.FormPanel({
    id: 'hargapenjualanproyek',
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
        items: [headerhargapenjualanproyek]
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
            fieldLabel: 'Disk Proyek 1',
            items : [ {
                xtype : 'compositefield',
                msgTarget: 'side',
                fieldLabel: 'Disk Proyek 1',
                width:200,
                items : [{
                    xtype:          'combo',
                    mode:           'local',
                    value:          '%',
                    triggerAction:  'all',
                    forceSelection: true,
                    name:           'disk_proyek1_op',
                    id:           	'hp_disk_proyek1_op',
                    hiddenName:     'disk_proyek1_op',
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
                            Ext.getCmp('hp_disk_proyek1').setMaxValue(Number.MAX_VALUE);
                            if (this.getValue() === '%')
                                Ext.getCmp('hp_disk_proyek1').maxValue = 100;
                            else Ext.getCmp('hp_disk_proyek1').maxLength = 11;
                        }
                    }
                },{
                    xtype: 'numberfield',
                    flex:1,
                    width:115,
                    name : 'disk_proyek1',
                    id: 'hp_disk_proyek1',
                    style: 'text-align:right;',
                    value :'0'

                }]
            },{
                xtype: 'displayfield',
                value: 'Disk Proyek 2',
                width: 100
            },{
                xtype : 'compositefield',
                msgTarget: 'side',
                fieldLabel: 'Disk Proyek 2',
                width:200,
                items : [{
                    width:          50,
                    xtype:          'combo',
                    mode:           'local',
                    value:          '%',
                    triggerAction:  'all',
                    forceSelection: true,

                    name:           'disk_proyek2_op',
                    id:           	'hp_disk_proyek2_op',
                    hiddenName:     'disk_proyek2_op',
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
                            Ext.getCmp('hp_disk_proyek2').setMaxValue(Number.MAX_VALUE);
                            if (this.getValue() === '%')
                                Ext.getCmp('hp_disk_proyek2').maxValue = 100;
                            else Ext.getCmp('hp_disk_proyek2').maxLength = 11;
                        }
                    }
                },{
                    xtype: 'numberfield',
                    flex : 1,
                    width:115,
                    name : 'disk_proyek2',
                    value :'0',
                    id: 'hp_disk_proyek2',
                    style: 'text-align:right;'

                }]

            }]
        },{
            xtype : 'compositefield',
            msgTarget: 'side',
            fieldLabel: 'Disk Proyek 3',
            items : [{
                xtype : 'compositefield',
                msgTarget: 'side',
                fieldLabel: 'Disk Proyek 3',
                width:200,
                items : [{
                    width:          50,
                    xtype:          'combo',
                    mode:           'local',
                    value:          '%',
                    triggerAction:  'all',
                    forceSelection: true,

                    name:           'disk_proyek3_op',
                    id:           	'hp_disk_proyek3_op',
                    hiddenName:     'disk_proyek3_op',
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
                            Ext.getCmp('hp_disk_proyek3').setMaxValue(Number.MAX_VALUE);
                            if (this.getValue() === '%')
                                Ext.getCmp('hp_disk_proyek3').maxValue = 100;
                            else Ext.getCmp('hp_disk_proyek3').maxLength = 11;
                        }
                    }
                },{
                    xtype: 'numberfield',
                    flex : 1,
                    width:115,
                    name : 'disk_proyek3',
                    value :'0',
                    id: 'hp_disk_proyek3',
                    style: 'text-align:right;'

                }]

            }, {
                xtype: 'displayfield',
                value: 'Disk Proyek 4',
                width: 100
            },{
                xtype : 'compositefield',
                msgTarget: 'side',
                fieldLabel: 'Disk Proyek 4',
                width:200,
                items : [{
                    width:          50,
                    xtype:          'combo',
                    mode:           'local',
                    value:          '%',
                    triggerAction:  'all',
                    forceSelection: true,

                    name:           'disk_proyek4_op',
                    id:           	'hp_disk_proyek4_op',
                    hiddenName:     'disk_proyek4_op',
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
                            Ext.getCmp('hp_disk_proyek4').setMaxValue(Number.MAX_VALUE);
                            if (this.getValue() === '%')
                                Ext.getCmp('hp_disk_proyek4').maxValue = 100;
                            else Ext.getCmp('hp_disk_proyek4').maxLength = 11;
                        }
                    }
                },{
                    xtype: 'numberfield',
                    flex : 1,
                    width:115,
                    name : 'disk_proyek4',
                    value :'0',
                    id: 'hp_disk_proyek4',
                    style: 'text-align:right;'

                }]

            }
            ]
        },{
            xtype : 'compositefield',
            msgTarget: 'side',
            fieldLabel: 'Disk Proyek 5',
            items : [{
                xtype: 'numberfield',
                currencySymbol:'',
                width: 170,
                name : 'disk_proyek5',
                value :'0',
                id: 'hp_disk_proyek5',
                style: 'text-align:right;'

            }
            ]
        }],buttons: [{
            text: 'Apply All',
            formBind: true,
            handler: function(){
                var kd_supplier =  Ext.getCmp('id_cbhjsuplier_proyek').getValue();
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
                var is_validasi = true;
                strhargapenjualanproyek.each(function(record){

                    record.set('disk_proyek1_op',Ext.getCmp('hp_disk_proyek1_op').getValue());
                    record.set('disk_proyek1',Ext.getCmp('hp_disk_proyek1').getValue());
                    record.set('disk_proyek2_op',Ext.getCmp('hp_disk_proyek2_op').getValue());
                    record.set('disk_proyek2',Ext.getCmp('hp_disk_proyek2').getValue());
                    record.set('disk_proyek3_op',Ext.getCmp('hp_disk_proyek3_op').getValue());
                    record.set('disk_proyek3',Ext.getCmp('hp_disk_proyek3').getValue());
                    record.set('disk_proyek4_op',Ext.getCmp('hp_disk_proyek4_op').getValue());
                    record.set('disk_proyek4',Ext.getCmp('hp_disk_proyek4').getValue());
                    record.set('disk_amt_proyek5',Ext.getCmp('hp_disk_proyek5').getValue());

                    record.commit();

                    record.set('edited','Y');
                    
                    var total_disk = 0;
                    var rp_jual_proyek = record.get('rp_jual_proyek');
                    var disk_proyek1_op = record.get('disk_proyek1_op');
                    var disk_proyek1 = record.get('disk_proyek1');
                    if (disk_proyek1_op === '%'){
                        total_disk = rp_jual_proyek-(rp_jual_proyek*(disk_proyek1/100));
                    }else{
                        total_disk = rp_jual_proyek-disk_proyek1;
                    }

                    var disk_proyek2_op = record.get('disk_proyek2_op');
                    var disk_proyek2 = record.get('disk_proyek2');
                    if (disk_proyek2_op === '%'){
                        total_disk =  total_disk-(total_disk*(disk_proyek2/100));
                    }else{
                        total_disk = total_disk-disk_proyek2;
                    }

                    var disk_proyek3_op = record.get('disk_proyek3_op');
                    var disk_proyek3 = record.get('disk_proyek3');
                    if (disk_proyek3_op === '%'){
                        total_disk = total_disk-(total_disk*(disk_proyek3/100));
                    }else{
                        total_disk = total_disk-disk_proyek3;
                    }

                    var disk_proyek4_op = record.get('disk_proyek4_op');
                    var disk_proyek4 = record.get('disk_proyek4');
                    if (disk_proyek4_op === '%'){
                        total_disk = total_disk-(total_disk*(disk_proyek4/100));
                    }else{
                        total_disk = total_disk-disk_proyek4;
                    }

                    var total_disk = total_disk - record.get('disk_amt_proyek5');
                    console.log ('total disk = '+ total_disk);
                                        
                    if(record.get('p_rp_cogs') > 0){
                        if(total_disk < record.get('p_rp_cogs')){
                            //this.setValue('0');
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
                        if(total_disk < record.get('rp_het_harga_beli')){
                           //is_validasi = false;
                           console.log ('total disk = '+ total_disk);
                           Ext.Msg.show({
                                title: 'Error',
                                msg: 'Net Price Jual tidak boleh lebih kecil dari HET Net Price Beli (inc PPN).  Apakah Akan Dilanjutkan ???',
                                modal: true,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OKCANCEL,
                                fn: function(btn){
                                    this.focus();
                                    switch (btn) {
                                        case "ok":
                                            record.set('is_validasi', 'Y'); 
                                            record.commit();
                                            //Ext.getCmp('hjp_is_validasi').setValue('Y');
                                            break;
                                        case "cancel":
                                            record.set('is_validasi', 'N'); 
                                             
                                            break;
                                        default:
                                            // something else
                                            break;
                                    } // switch
                                }
                            });
                            Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                        }
                    }
                    record.set('rp_jual_proyek_net', total_disk);
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
                                    id: 'hpp_tgl_start_diskon',                
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
                                        id: 'hpp_tgl_end_diskon',                
                                        width: 150,
                                        minValue: (new Date()).clearTime() 
                                    }]
												
                            }]
                    }],buttons: [{
                        text: 'Apply All',
                        formBind: true,
                        handler: function(){
                            var kd_supplier =  Ext.getCmp('id_cbhjsuplier_proyek').getValue();
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
                            
                            strhargapenjualanproyek.each(function(record){
                                    
                                record.set('tgl_start_diskon',Ext.getCmp('hpp_tgl_start_diskon').getValue());
                                record.set('tgl_end_diskon',Ext.getCmp('hpp_tgl_end_diskon').getValue());
                                
                                record.commit();
                                record.set('edited','Y');
                                record.commit();
                            });

                        }
                    }]
            },
        gridhargapenjualanproyek,
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
                    id: 'ehjp_keterangan',
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
            var validasi_tgl = true;
            var kd_produk = '';
            strhargapenjualanproyek.each(function(node){
                var rp_jual_proyek_net = node.data.rp_jual_proyek_net;
                var rp_het_harga_beli = node.data.rp_het_harga_beli;
                var kode_produk = node.data.kd_produk_baru;
                var tgl_start_diskon = node.data.tgl_start_diskon;
                var tgl_end_diskon = node.data.tgl_end_diskon;
                var is_validasi = node.data.is_validasi;
                var edited = node.data.edited;
                console.log (is_validasi);
                if (edited === 'Y'){
                    if (rp_jual_proyek_net < rp_het_harga_beli && is_validasi === 'N'){
                        validasi= false;
                        kd_produk = kode_produk;
                    }
                    if (tgl_end_diskon < tgl_start_diskon){
                        validasi_tgl= false;
                        kd_produk = kode_produk;
                    }
                }
            });
            if(!validasi_tgl){

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
            if(!validasi){
                //strhargapenjualanproyek.each(function(record){
                Ext.Msg.show({
                                title: 'Error',
                                msg: 'Kode Produk <code>' + kd_produk + '</code>  Net Price Jual lebih kecil dari HET Net Price Beli (inc PPN). Apakah Akan Dilanjutkan ???',
                                modal: true,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OKCANCEL,
                                fn: function(btn){
                                    this.focus();
                                   // Ext.Msg.alert('', 'You clicked the <code>' + btn + '</code> button.');
                                    switch (btn) {
                                        case "ok":
                                            //Edited_hj_proyek();
                                            record.set('is_validasi', 'Y'); 
//                                            record.commit();
                                            //Ext.getCmp('hjp_is_validasi').setValue('Y');
                                            break;
                                        case "cancel":
                                            //Ext.getCmp('ehjp_rp_jual_proyek').setValue('0');
                                             
                                            break;
                                        default:
                                            // something else
                                            break;
                                    } // switch
                                }
                            });
                            Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                 return;
                 //});
           }
           
            var detailhargapenjualanproyek = new Array();
            strhargapenjualanproyek.each(function(node){
                detailhargapenjualanproyek.push(node.data);
            });
            Ext.getCmp('hargapenjualanproyek').getForm().submit({
                url: '<?= site_url("harga_penjualan_proyek/update_row") ?>',
                scope: this,
                params: {
                    detail: Ext.util.JSON.encode(detailhargapenjualanproyek)
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

                    clearhargapenjualanproyek();
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
            clearhargapenjualanproyek();
        }
    }]
});

hargapenjualanproyek.on('afterrender', function(){
    this.getForm().load({
        url: '<?= site_url("harga_penjualan_proyek/get_form") ?>',
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

function clearhargapenjualanproyek(){
    Ext.getCmp('hargapenjualanproyek').getForm().reset();
    Ext.getCmp('hargapenjualanproyek').getForm().load({
        url: '<?= site_url("harga_penjualan_proyek/get_form") ?>',
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
    strhargapenjualanproyek.removeAll();
}
</script>
