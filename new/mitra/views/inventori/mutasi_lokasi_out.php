<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
// twin lokasi
var str_cb_mlo_asal = new Ext.data.ArrayStore({
    fields: ['kd_lokasi','nama_lokasi'],
    data : []
});

var str_grid_mlo_asal = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: [{name: 'kd_lokasi', allowBlank: false, type: 'text'},
            {name: 'nama_lokasi', allowBlank: false, type: 'text'},
            {name: 'peruntukan', allowBlank: false, type: 'text'}
        ],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("mutasi_barang/search_lokasi") ?>',
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

str_grid_mlo_asal.on('load', function() {
    str_grid_mlo_asal.setBaseParam('sender', 'mutasiout_asal');
});

var searchgrid_mlo_asal = new Ext.app.SearchField({
    store: str_grid_mlo_asal,
    params: {
        sender: 'mutasiout_asal',
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgrid_mlo_asal'
});
var grid_mlo_asal = new Ext.grid.GridPanel({
    store: str_grid_mlo_asal,
    stripeRows: true,
    frame: true,
    border:true,
    columns: [{
        header: 'Kode Lokasi',
        dataIndex: 'kd_lokasi',
        width: 100,
        sortable: true
    },{
        header: 'Nama Lokasi',
        dataIndex: 'nama_lokasi',
        width: 350,
        sortable: true
    },{
        header: 'Peruntukan',
        dataIndex: 'peruntukan',
        width: 100,
        sortable: true
    }],

    tbar: new Ext.Toolbar({
        items: [searchgrid_mlo_asal]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: str_grid_mlo_asal,
        displayInfo: true
    }),
    listeners: {
        'rowdblclick': function(){
            var sm = this.getSelectionModel();
            var sel = sm.getSelections();
            if (sel.length > 0) {
                Ext.getCmp('id_cb_mlo_asal').setValue(sel[0].get('kd_lokasi'));
                Ext.getCmp('id_nama_mlo_asal').setValue(sel[0].get('nama_lokasi'));
                menu_mlo_asal.hide();
            }
        }
    }
});



var menu_mlo_asal = new Ext.menu.Menu();
menu_mlo_asal.add(new Ext.Panel({
    title: 'Pilih Lokasi',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 600,
    height: 250,
    closeAction: 'hide',
    plain: true,
    items: [grid_mlo_asal],
    buttons: [{
        text: 'Close',
        handler: function(){
            menu_mlo_asal.hide();
        }
    }]
}));

Ext.ux.TwinComb_mlo_asal = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function(){
        //load store grid
        str_grid_mlo_asal.load({
            params: {
                sender: 'mutasiout_asal'
            }
        });
        menu_mlo_asal.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menu_mlo_asal.on('hide', function(){
    var sf = Ext.getCmp('id_searchgrid_mlo_asal').getValue();
    if( sf != ''){
        Ext.getCmp('id_searchgrid_mlo_asal').setValue('');
        searchgrid_mlo_asal.onTrigger2Click();
    }
});
//end twin lokasi
var cb_mlo_asal = new Ext.ux.TwinComb_mlo_asal({
    fieldLabel: 'Lokasi Asal <span class="asterix">*</span>',
    id: 'id_cb_mlo_asal',
    store: str_cb_mlo_asal,
    mode: 'local',
    valueField: 'kd_lokasi',
    displayField: 'nama_lokasi',
    typeAhead: true,
    triggerAction: 'all',
    allowBlank: false,
    editable: false,
    anchor: '90%',
    hiddenName: 'kd_lokasi_asal',
    emptyText: 'Pilih Lokasi'

});

//twin lokasi tujuan
// twin lokasi
var str_cb_mlo_tujuan = new Ext.data.ArrayStore({
    fields: ['kd_lokasi','nama_lokasi'],
    data : []
});

var str_grid_mlo_tujuan = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: [{name: 'kd_lokasi', allowBlank: false, type: 'text'},
            {name: 'nama_lokasi', allowBlank: false, type: 'text'},
            {name: 'peruntukan', allowBlank: false, type: 'text'}
        ],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("mutasi_barang/search_lokasi_out") ?>',
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

str_grid_mlo_tujuan.on('load', function() {
    str_grid_mlo_tujuan.setBaseParam('sender', 'mutasiout_tujuan');
});

var searchgrid_mlo_tujuan = new Ext.app.SearchField({
    store: str_grid_mlo_tujuan,
    params: {
        sender: 'mutasiout_tujuan',
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgrid_mlo_tujuan'
});
var grid_mlo_tujuan = new Ext.grid.GridPanel({
    store: str_grid_mlo_tujuan,
    stripeRows: true,
    frame: true,
    border:true,
    columns: [{
        header: 'Kode Lokasi',
        dataIndex: 'kd_lokasi',
        width: 100,
        sortable: true

    },{
        header: 'Nama Lokasi',
        dataIndex: 'nama_lokasi',
        width: 350,
        sortable: true
    },{
        header: 'Peruntukan',
        dataIndex: 'peruntukan',
        width: 100,
        sortable: true
    }],

    tbar: new Ext.Toolbar({
        items: [searchgrid_mlo_tujuan]
    }),
    bbar: new Ext.PagingToolbar({
        pageSize: ENDPAGE,
        store: str_grid_mlo_tujuan,
        displayInfo: true
    }),
    listeners: {
        'rowdblclick': function(){
            var sm = this.getSelectionModel();
            var sel = sm.getSelections();
            if (sel.length > 0) {
                Ext.getCmp('id_cb_mlo_tujuan').setValue(sel[0].get('kd_lokasi'));
                Ext.getCmp('id_nama_mlo_tujuan').setValue(sel[0].get('nama_lokasi'));
                menu_mlo_tujuan.hide();
            }
        }
    }
});



var menu_mlo_tujuan = new Ext.menu.Menu();
menu_mlo_tujuan.add(new Ext.Panel({
    title: 'Pilih Lokasi',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 600,
    height: 250,
    closeAction: 'hide',
    plain: true,
    items: [grid_mlo_tujuan],
    buttons: [{
        text: 'Close',
        handler: function(){
            menu_mlo_tujuan.hide();
        }
    }]
}));

Ext.ux.TwinComb_mlo_tujuan = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function(){
        //load store grid
        str_grid_mlo_tujuan.load({
            params: {
                sender: 'mutasiout_tujuan'
            }
        });
        menu_mlo_tujuan.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menu_mlo_tujuan.on('hide', function(){
    var sf = Ext.getCmp('id_searchgrid_mlo_tujuan').getValue();
    if( sf != ''){
        Ext.getCmp('id_searchgrid_mlo_tujuan').setValue('');
        searchgrid_mlo_tujuan.onTrigger2Click();
    }
});
//end twin lokasi
var cb_mlo_tujuan = new Ext.ux.TwinComb_mlo_tujuan({
    fieldLabel: 'Lokasi tujuan <span class="asterix">*</span>',
    id: 'id_cb_mlo_tujuan',
    store: str_cb_mlo_tujuan,
    mode: 'local',
    valueField: 'kd_lokasi',
    displayField: 'nama_lokasi',
    typeAhead: true,
    triggerAction: 'all',
    allowBlank: false,
    editable: false,
    anchor: '90%',
    hiddenName: 'kd_lokasi_tujuan',
    emptyText: 'Pilih Lokasi'

});


//grid data store
var strmutasiout = new Ext.data.Store({
    autoSave:false,
    reader: new Ext.data.JsonReader({
        fields: [

        {name: 'kd_produk', allowBlank: false, type: 'text'},
        {name: 'nama_produk', allowBlank: false, type: 'text'},
        {name: 'nm_satuan', allowBlank: false, type: 'text'},
        {name: 'qty_oh', allowBlank: false, type: 'int'},
        {name: 'qty', allowBlank: false, type: 'int'},
        {name: 'sub_asal', allowBlank: false, type: 'text'}  ,
        {name: 'nama_sub_asal', allowBlank: false, type: 'text'},
        {name: 'sub_tujuan', allowBlank: false, type: 'text'}  ,
        {name: 'nama_sub_tujuan', allowBlank: false, type: 'text'}
                //

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



var headermutasiout = {
    layout: 'column',
    border: false,
    items: [{
        columnWidth: .4,
        layout: 'form',
        border: false,
        labelWidth: 100,
        defaults: { labelSeparator: ''},
        items: [

        {
            xtype: 'textfield',
            fieldLabel: 'No. Mutasi Barang',
            name: 'no_mutasi_stok',
            readOnly:true,
            fieldClass:'readonly-input',
            id: 'id_no_mutasiout_stok',
            anchor: '90%',
            value:''
        },{
            xtype: 'textfield',
            fieldLabel: 'No. Referensi <span class="asterix">*</span>',
            name: 'no_ref',
            readOnly:false,
            allowBlank: false,
//                        fieldClass:'readonly-input',
id: 'id_no_ref_out',
anchor: '90%',
value:''
},{
    xtype: 'datefield',
    fieldLabel: 'Tanggal <span class="asterix">*</span>',
    allowBlank:false,
    format:'d-m-Y',
                        //editable:false,
                        name: 'tgl_mutasi',
                        id: 'id_tgl_mutasiout',
                        anchor: '90%',
                        value: ''
                    },{
                        xtype: 'textfield',
                        fieldLabel: 'Nama pengambil <span class="asterix">*</span>',
                        name: 'nama_pengambil',
                        readOnly:false,
                        allowBlank: false,
                        id: 'id_nama_pengambil_out',
                        anchor: '90%',
                        value:''
                    }
                    ]
                },{
                    columnWidth: .2,
                    layout: 'form',
                    border: false,
                    labelWidth: 100,
                    defaults: { labelSeparator: ''},
                    items: [
                    cb_mlo_asal,
                    cb_mlo_tujuan,
                    new Ext.form.Checkbox({
                        xtype: 'checkbox',
                        fieldLabel: 'Scan Barcode',
                        boxLabel:'Ya',
                        name:'scan_barcode',
                        id:'id_mlo_cb_scan_barcode',
                        checked: false,
                        inputValue: '1',
                        autoLoad : true
                    })
                    ]
                },{
                    columnWidth: .4,
                    layout: 'form',
                    border: false,
                    labelWidth: 100,
                    defaults: { labelSeparator: ''},
                    items: [
                    {
                        xtype: 'textfield',
                        fieldLabel: 'Nama Lokasi',
                        name: 'nama_mlo_asal',
                        readOnly:true,
                        fieldClass:'readonly-input',
                        id: 'id_nama_mlo_asal',
                        anchor: '90%',
                        value:''
                    },{
                        xtype: 'textfield',
                        fieldLabel: 'Nama Lokasi',
                        name: 'nama_mlo_tujuan',
                        readOnly:true,
                        fieldClass:'readonly-input',
                        id: 'id_nama_mlo_tujuan',
                        anchor: '90%',
                        value:''
                    }
                    ]
                }]
            }

    // twin barang
    var strcb_mout_produk = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data : []
    });

    var strgrid_mout_produk = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [{name: 'kd_produk', allowBlank: false, type: 'text'},
            {name: 'nama_produk', allowBlank: false, type: 'text'},
            {name: 'nm_satuan', allowBlank: false, type: 'text'},
            {name: 'qty_oh', allowBlank: false, type: 'int'}
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("mutasi_barang/search_barang") ?>',
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

    var searchgrid_mout_produk = new Ext.app.SearchField({
        store: strgrid_mout_produk,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgrid_mout_produk'
    });

    var grid_mout_produk = new Ext.grid.GridPanel({
        store: strgrid_mout_produk,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
            header: 'Kode produk',
            dataIndex: 'kd_produk',
            width: 100,
            sortable: true

        },{
            header: 'Nama produk',
            dataIndex: 'nama_produk',
            width: 350,
            sortable: true
        },{
            header: 'Satuan',
            dataIndex: 'nm_satuan',
            width: 80
        },{
            header: 'Qty OH',
            dataIndex: 'qty_oh',
            width: 80
        }],

        tbar: new Ext.Toolbar({
            items: [searchgrid_mout_produk]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgrid_mout_produk,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('mout_kd_produk').setValue(sel[0].get('kd_produk'));
                    Ext.getCmp('mout_nama_produk').setValue(sel[0].get('nama_produk'));

                    Ext.getCmp('mout_nm_satuan').setValue(sel[0].get('nm_satuan'));
                    Ext.getCmp('mout_qty_oh').setValue(sel[0].get('qty_oh'));
                    Ext.getCmp('mout_qty').setValue(0);
                    Ext.getCmp('mout_qty').focus();
                    menu_mout_produk.hide();
                }
            }
        }
    });

strgrid_mout_produk.on('load', function(){
    strgrid_mout_produk.setBaseParam('datablok', Ext.getCmp('id_mlo_twin_subblok').getValue());
    var scan = Ext.getCmp('id_mlo_cb_scan_barcode').getValue();
    if(scan){
        Ext.getCmp('id_mlo_scan_barcode').focus();
    }else{
        Ext.getCmp('id_searchgrid_mout_produk').focus();
    }
});

var menu_mout_produk = new Ext.menu.Menu();
menu_mout_produk.add(new Ext.Panel({
    title: 'Pilih Barang',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 600,
    height: 250,
    closeAction: 'hide',
    plain: true,
    items: [grid_mout_produk],
    buttons: [{
        text: 'Close',
        handler: function(){
            menu_mout_produk.hide();
        }
    }]
}));

var menu_mlo_produk_scan = new Ext.menu.Menu();
menu_mlo_produk_scan.add(new Ext.Panel({
    title: 'Scan Barcode Produk',
    layout: 'form',
    border: false,
    frame: true,
    autoScroll:true,
    bodyStyle:'padding-right:20px;',
    labelWidth: 130,
    buttonAlign: 'left',
    width: 400,
    height: 150,
    closeAction: 'hide',
    items: [{
        xtype: 'textfield',
        fieldLabel: 'Scan Barcode',
        name: 'scan_barcode',
        id: 'id_mlo_scan_barcode',
        anchor: '90%',
        value:'',
        listeners:{
            specialKey: function( field, e ) {
                if ( e.getKey() == e.RETURN || e.getKey() == e.ENTER ) {
                    var valid = (Ext.getCmp('id_mlo_twin_subblok').getValue() !== '');

                    if (!valid){
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Pilih sub blok terlebih dulu',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK,
                            fn: function(btn){
                                if (btn == 'ok') { Ext.getCmp('ms_kd_produk').reset();}
                            }
                        });
                        Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                        Ext.getCmp('ms_kd_produk').focus();
                        return;
                    }

                    Ext.Ajax.request({
                        url: '<?= site_url("mutasi_barang/search_barang") ?>',
                        method: 'POST',
                        params: {
                            datablok: Ext.getCmp('id_mlo_twin_subblok').getValue(),
                            query: Ext.getCmp('id_mlo_scan_barcode').getValue(),
                            type: 1,
                            sender: 'scan'
                        },
                        callback:function(opt,success,responseObj){
                            var scn = Ext.util.JSON.decode(responseObj.responseText);
                            if(scn.success==true){
                                Ext.getCmp('id_mlo_kd_produk_scan').setValue(scn.data.kd_produk);
                                Ext.getCmp('id_mlo_nama_produk_scan').setValue(scn.data.nama_produk);
                            }
                        }
                    });
                    if(Ext.getCmp('id_mlo_kd_produk_scan').getValue() != ''){
                        Ext.getCmp('id_mlo_scan_submit_button').focus();
                    }

                }
            }
        }
    },{
        xtype: 'textfield',
        fieldLabel: 'Kode Produk',
        name: 'kd_produk',
        readOnly:true,
        fieldClass:'readonly-input',
        id: 'id_mlo_kd_produk_scan',
        anchor: '90%',
        value:''
    },{
        xtype: 'textfield',
        fieldLabel: 'Nama Produk',
        name: 'nama_produk',
        readOnly:true,
        fieldClass:'readonly-input',
        id: 'id_mlo_nama_produk_scan',
        anchor: '90%',
        value:''
    }
    ],
    buttons: [{
        text: 'Submit',
        formBind: true,
        id:'id_mlo_scan_submit_button',
        handler: function(){
            Ext.Ajax.request({
                url: '<?= site_url("mutasi_barang/search_barang") ?>',
                method: 'POST',
                params: {
                    datablok: Ext.getCmp('id_mlo_twin_subblok').getValue(),
                    query: Ext.getCmp('id_mlo_scan_barcode').getValue(),
                    type: 1,
                    sender: 'validate'
                },
                callback:function(opt,success,responseObj){
                    var scn = Ext.util.JSON.decode(responseObj.responseText);
                    if(scn.success==true){
                        Ext.getCmp('mout_kd_produk').setValue(scn.data.kd_produk);
                        Ext.getCmp('mout_nama_produk').setValue(scn.data.nama_produk);
                        Ext.getCmp('mout_nm_satuan').setValue(scn.data.nm_satuan);
                        Ext.getCmp('mout_qty_oh').setValue(scn.data.qty_oh);
                        Ext.getCmp('mout_qty').setValue(scn.data.qty_mutasi);
                        Ext.getCmp('mout_qty').focus();
                    }else{
                        Ext.getCmp('mout_kd_produk').setValue('');
                        Ext.getCmp('mout_nama_produk').setValue('');
                        Ext.getCmp('mout_nm_satuan').setValue('');
                        Ext.getCmp('mout_qty_oh').setValue('');
                        Ext.getCmp('mout_qty').setValue('');
                        Ext.Msg.show({
                            title: 'Error',
                            msg: scn.errMsg,
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK,
                            fn: function(btn){
                                if (btn == 'ok' && scn.errMsg == 'Session Expired') {
                                    window.location = '<?= site_url("auth/login") ?>';
                                }
                            }
                        });
                        Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                    }

                    menu_mlo_produk_scan.hide();
    //                        Ext.getCmp('ms_qty').focus();
}
});
}
},{
    text: 'Close',
    handler: function(){
        menu_mlo_produk_scan.hide();
    }
}]
}));

Ext.ux.TwinComb_mout_Produk = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function(){
        //load store grid
        var datablok=Ext.getCmp('id_mlo_twin_subblok').getValue();

        strgrid_mout_produk.load({ params: { datablok: datablok } });
        var scan = Ext.getCmp('id_mlo_cb_scan_barcode').getValue();
        if(scan){
            menu_mlo_produk_scan.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
            Ext.getCmp('id_mlo_scan_barcode').focus();
        } else {
            menu_mout_produk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
            Ext.getCmp('id_searchgrid_mout_produk').focus();
        }
    },
validationEvent: false,
validateOnBlur: false,
trigger1Class: 'x-form-clear-trigger',
trigger2Class: 'x-form-search-trigger',
hideTrigger1: true
});
    //end twin produk


    //// twin subblok-------------

    var strcbkdsubblokpro_dout = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['sub', 'nama_sub'],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("mutasi_barang/get_subblok_out") ?>',
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

    var strgridsubblokpro_dout = new Ext.data.Store({
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
            url: '<?= site_url("mutasi_barang/get_subblok") ?>',
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
    var searchgridprosubblok_dout = new Ext.app.SearchField({
        store: strgridsubblokpro_dout,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE

        },
        width: 220,
        id: 'idsearchgridprosubblok_dout'
    });

    searchgridprosubblok_dout.onTrigger2Click = function(evt){
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }

        // Get the value of search field
        var fid = Ext.getCmp('id_cb_mlo_asal').getValue();
        var o = { start: 0, kd_lokasi: fid };

        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params:o});
        this.hasSearch = true;
        this.triggers[0].show();
    };


    // top toolbar
    var tbgridprosubblok_dout = new Ext.Toolbar({
        items: [searchgridprosubblok_dout]
    });

    var gridprosubblok_dout = new Ext.grid.GridPanel({
        store: strgridsubblokpro_dout,
        stripeRows: true,
        frame: true,
        border:true,
        tbar: tbgridprosubblok_dout,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridsubblokpro_dout,
            displayInfo: true
        }),
        columns: [{
            dataIndex: 'kd_lokasi',
            hidden: true
        },{
            dataIndex: 'kd_blok',
            hidden: true
        },{
            dataIndex: 'kd_sub_blok',
            hidden: true
        },{
            header: 'Kode',
            dataIndex: 'sub',
            width: 90,
            sortable: true

        },{
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
                    Ext.getCmp('id_mlo_twin_subblok').setValue(sel[0].get('sub'));
                    Ext.getCmp('id_mlo_nama_subblok_asal').setValue(sel[0].get('nama_sub'));

                    menusubblok_dout.hide();
                }
            }
        }
    });

var menusubblok_dout = new Ext.menu.Menu();
menusubblok_dout.add(new Ext.Panel({
    title: 'Pilih Sub Blok Lokasi',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 350,
    height: 250,
    closeAction: 'hide',
    plain: true,
    items: [gridprosubblok_dout],
    buttons: [{
        text: 'Close',
        handler: function(){
            menusubblok_dout.hide();
        }
    }]
}));

Ext.ux.TwinComboproSubBlok_dout = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function(){
            //load store grid
            var asal = Ext.getCmp('id_cb_mlo_asal').getValue();
            strgridsubblokpro_dout.load({params:{kd_lokasi:asal}});
            menusubblok_dout.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });


    //// twin subblok tujuan-------------

    var strcbkdsubblokpro_mout = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['sub', 'nama_sub'],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("mutasi_barang/get_subblok_out") ?>',
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

    var strgridsubblokpro_mout = new Ext.data.Store({
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
            url: '<?= site_url("mutasi_barang/search_subbloktujuan_out") ?>',
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
    var searchgridprosubblok_mout = new Ext.app.SearchField({
        store: strgridsubblokpro_mout,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchgridprosubblok_mout'
    });


    searchgridprosubblok_mout.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }

    // Get the value of search field

    var datablok=Ext.getCmp('id_mlo_twin_subblok').getValue();
    var o = { start: 0, datablok:datablok };

    this.store.baseParams = this.store.baseParams || {};
    this.store.baseParams[this.paramName] = text;
    this.store.reload({params:o});
    this.hasSearch = true;
    this.triggers[0].show();
};
    // top toolbar
    var tbgridprosubblok_mout = new Ext.Toolbar({
        items: [searchgridprosubblok_mout]
    });

    var gridprosubblok_mout = new Ext.grid.GridPanel({
        store: strgridsubblokpro_mout,
        stripeRows: true,
        frame: true,
        border:true,
        tbar: tbgridprosubblok_mout,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridsubblokpro_mout,
            displayInfo: true
        }),
        columns: [{
            dataIndex: 'kd_lokasi',
            hidden: true
        },{
            dataIndex: 'kd_blok',
            hidden: true
        },{
            dataIndex: 'kd_sub_blok',
            hidden: true
        },{
            header: 'Kode',
            dataIndex: 'sub',
            width: 90,
            sortable: true

        },{
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
                    Ext.getCmp('id_mlo_twin_subblok_tujuan').setValue(sel[0].get('sub'));
                    Ext.getCmp('id_mlo_nama_subblok_asal_tujuan').setValue(sel[0].get('nama_sub'));

                    menusubblok_mout.hide();
                }
            }
        }
    });

var menusubblok_mout = new Ext.menu.Menu();
menusubblok_mout.add(new Ext.Panel({
    title: 'Pilih Sub Blok Lokasi',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 350,
    height: 250,
    closeAction: 'hide',
    plain: true,
    items: [gridprosubblok_mout],
    buttons: [{
        text: 'Close',
        handler: function(){
            menusubblok_mout.hide();
        }
    }]
}));

Ext.ux.TwinComboproSubBlok_mout = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function(){
            //load store grid

            var datablok=Ext.getCmp('id_mlo_twin_subblok').getValue();

            //            console.log(datablok);
            strgridsubblokpro_mout.load(
            {
                params: {
                    datablok: datablok


                }
            });
//            strgridsubblokpro_mout.load();
menusubblok_mout.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
},
validationEvent: false,
validateOnBlur: false,
trigger1Class: 'x-form-clear-trigger',
trigger2Class: 'x-form-search-trigger',
hideTrigger1: true
});

var editormutasiout = new Ext.ux.grid.RowEditor({
    saveText: 'Update'
});


var gridmutasiout = new Ext.grid.GridPanel({
    store: strmutasiout,
    stripeRows: true,
    height: 250,
    frame: true,
    border:true,
    plugins: [editormutasiout],
    columns: [{
        header: 'Kode Sub Blok Asal',
        dataIndex: 'sub_asal',
        width: 100,
        editor: new Ext.ux.TwinComboproSubBlok_dout({
            id: 'id_mlo_twin_subblok',
            store: strcbkdsubblokpro_dout,
            valueField: 'sub',
            displayField: 'sub',
            typeAhead: true,
            triggerAction: 'all',
            allowBlank: false,
            editable: false,
            hiddenName: 'sub_asal',
            emptyText: 'Pilih Sub Blok',
            listeners: {
                'expand': function(){
                    strcbkdsubblokpro_dout.load();
                }
            }
        })
    },{
        header: 'Sub Blok Asal',
        dataIndex: 'nama_sub_asal',
        width: 200,
        editor: new Ext.form.TextField({
            readOnly: true,
            id: 'id_mlo_nama_subblok_asal'
        })
    },{
        header: 'Kode Barang',
        dataIndex: 'kd_produk',
        width: 110
        ,
        editor: new Ext.ux.TwinComb_mout_Produk ({
            id: 'mout_kd_produk',
            store: strcb_mout_produk,
            mode: 'local',
            valueField: 'kd_produk',
            displayField: 'kd_produk',
            typeAhead: true,
            triggerAction: 'all',
                    // allowBlank: false,
                    editable: false,
                    hiddenName: 'kd_produk',
                    emptyText: 'Pilih produk'

                })
    },{
        header: 'Nama Barang',
        dataIndex: 'nama_produk',
        width: 400,
        editor: new Ext.form.TextField({
            readOnly: true,
            id: 'mout_nama_produk'
        })
    },{
        header: 'Satuan',
        dataIndex: 'nm_satuan',
        width: 80,
        editor: new Ext.form.TextField({
            readOnly: true,
            id: 'mout_nm_satuan'
        })
    },{
        xtype: 'numbercolumn',
        header: 'Qty OH',
        dataIndex: 'qty_oh',
        width: 70,
        align: 'center',
        sortable: true,
        format: '0,0',
        editor: {
            xtype: 'numberfield',
            id: 'mout_qty_oh',
            readOnly:true
        }
    },{
        xtype: 'numbercolumn',
        header: 'Qty',
        dataIndex: 'qty',
        width: 70,
        align: 'center',
        sortable: true,
        format: '0,0',
        editor: {
            xtype: 'numberfield',
            id: 'mout_qty',
                    //                    value:0,
                    allowBlank: false,
                    selectOnFocus: true,
                    listeners:{
                        'change': function(){
                            if(this.getValue() == ''){

                                this.setValue('0');

                            }

                            if(Ext.getCmp('mout_qty_oh').getValue() == ''){
                                Ext.getCmp('mout_qty_oh').setValue('0');
                            }

                            if(this.getValue() > Ext.getCmp('mout_qty_oh').getValue()){
                                Ext.Msg.show({
                                    title: 'Error',
                                    msg: 'Quantity Melebihi Quantity On Hand !!',
                                    modal: true,
                                    icon: Ext.Msg.ERROR,
                                    buttons: Ext.Msg.OK
                                });
                                this.setValue('0');
                            }

                        }
                    }
                }
            }],
            tbar: [{
                icon: BASE_ICONS + 'add.png',
                text: 'Add',
                handler: function(){
                    var lokasi_out = Ext.getCmp('id_cb_mlo_asal').getValue();
                    var lokasi_in  = Ext.getCmp('id_cb_mlo_tujuan').getValue();
                    if(lokasi_out == '' || lokasi_in == '') {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Tentukan dulu lokasi asal dan tujuan!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                        });
                    } else {
                        var rowmutasi = new gridmutasiout.store.recordType({kd_produk : '',qty: ''});
                        editormutasiout.stopEditing();
                        strmutasiout.insert(0, rowmutasi);
                        gridmutasiout.getView().refresh();
                        gridmutasiout.getSelectionModel().selectRow(0);
                        editormutasiout.startEditing(0);
                    }
                }
            },{
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                disabled: true,
                handler: function(){
                    editormutasiout.stopEditing();
                    var s = gridmutasiout.getSelectionModel().getSelections();
                    for(var i = 0, r; r = s[i]; i++){
                        strmutasiout.remove(r);
                    }
                }
            }]
        });

gridmutasiout.getSelectionModel().on('selectionchange', function(sm){
    gridmutasiout.removeBtn.setDisabled(sm.getCount() < 1);
});

var win_cetak_mlo = new Ext.Window({
    id: 'id_win_cetak_mlo',
    title: 'Print Bukti Mutasi Keluar',
    closeAction: 'hide',
    width: 900,
    height: 450,
    layout: 'fit',
    border: false,
    html:'<iframe style="width:100%;height:100%;" id="win_cetak_mlo_frame" src=""></iframe>'
});

var mutasiout = new Ext.FormPanel({
    id: 'mutasiout',
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
        items: [headermutasiout]
    },{
        layout: 'column',
        border: false,
        items: [{
            columnWidth: 1,
            style:'margin:6px 3px 0 0;',
            layout: 'form',
            labelWidth: 125,
            buttonAlign: 'left',
            items: []
        }]
    },
    gridmutasiout,{
        layout: 'column',
        border: false,
        items: [{
            columnWidth: 1,
            style:'margin:6px 3px 0 0;',
            layout: 'form',
            labelWidth: 125,
            buttonAlign: 'left',
            items: []
        }]
    }
    ,{
        layout: 'column',
        border: false,
        items: [{
            columnWidth: .5,
            style:'margin:6px 3px 0 0;',
            layout: 'form',
            labelWidth: 100,
            buttonAlign: 'left',
            items: [{ xtype: 'textarea',
            fieldLabel: 'Keterangan <span class="asterix">*</span>',
            name: 'keterangan',
            allowBlank: false,
            id: 'mout_keterangan',
            anchor: '90%'
        }]
    }]
},
],
buttons: [{
    text: 'Save',
    formBind: true,
    handler: function(){

        var detailmutasiout = new Array();
        strmutasiout.each(function(node){
            detailmutasiout.push(node.data)
        });
        Ext.getCmp('mutasiout').getForm().submit({
            url: '<?= site_url("mutasi_barang/update_row_out") ?>',
            scope: this,
            params: {
                detail: Ext.util.JSON.encode(detailmutasiout)
            },
            waitMsg: 'Saving Data...',
            success: function(form, action){
                var r = Ext.util.JSON.decode(action.response.responseText);
                Ext.Msg.show({
                    title: 'Success',
                    msg: 'Form submitted successfully',
                    modal: true,
                    icon: Ext.Msg.INFO,
                    buttons: Ext.Msg.OK,
                    fn: function(btn){
                        win_cetak_mlo.show();
                        Ext.getDom('win_cetak_mlo_frame').src = r.printUrl;
                    }
                });

                clearmutasiout();
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
},{
    text: 'Reset',
    handler: function(){
        clearmutasiout();
    }
}]
});

function clearmutasiout(){
    Ext.getCmp('id_no_ref_out').setValue('');
    Ext.getCmp('mutasiout').getForm().reset();
    strmutasiout.removeAll();
}
</script>
