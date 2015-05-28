<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
/*START TWIN NO BUKTI FILTER*/

var strcbahjp_nobukti_filter = new Ext.data.ArrayStore({
    fields: ['no_bukti_filter'],
    data : []
});

var strgridahjp_nobukti_filter = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: ['no_bukti_filter','keterangan','created_by','nama_supplier'],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("approval_harga_penjualan_proyek/get_no_bukti_filter") ?>',
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

var searchgridahjp_nobukti_filter = new Ext.app.SearchField({
    store: strgridahjp_nobukti_filter,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridahjp_nobukti_filter'
});


var gridahjp_nobukti_filter = new Ext.grid.GridPanel({
    store: strgridahjp_nobukti_filter,
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
        width: 80,
        sortable: true

    },{
        header: 'Ket Perubahan',
        dataIndex: 'keterangan',
        width: 200,
        sortable: true

    }],
    tbar: new Ext.Toolbar({
        items: [searchgridahjp_nobukti_filter]
    }),
    listeners: {
        'rowdblclick': function(){
            var sm = this.getSelectionModel();
            var sel = sm.getSelections();
            if (sel.length > 0) {
                Ext.getCmp('ahjp_user').setValue(sel[0].get('created_by'));
                Ext.getCmp('id_cbahjp_nobukti_filter').setValue(sel[0].get('no_bukti_filter'));
                gridapprovalhargapenjualanproyek.store.load({
                    params:{
                        no_bukti: Ext.getCmp('id_cbahjp_nobukti_filter').getValue()
                    }
                });
                menuahjp_nobukti_filter.hide();
            }
        }
    }
});

var menuahjp_nobukti_filter = new Ext.menu.Menu();
menuahjp_nobukti_filter.add(new Ext.Panel({
    title: 'Pilih No Bukti',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 500,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridahjp_nobukti_filter],
    buttons: [{
        text: 'Close',
        handler: function(){
            menuahjp_nobukti_filter.hide();
        }
    }]
}));

Ext.ux.TwinComboahjp_nobukti_filter = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function(){
        //load store grid
        strgridahjp_nobukti_filter.load();
        menuahjp_nobukti_filter.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuahjp_nobukti_filter.on('hide', function(){
    var sf = Ext.getCmp('id_searchgridahjp_nobukti_filter').getValue();
    if( sf !== ''){
        Ext.getCmp('id_searchgridahjp_nobukti_filter').setValue('');
        searchgridahjp_nobukti_filter.onTrigger2Click();
    }
});

var cbahjp_nobukti_filter = new Ext.ux.TwinComboahjp_nobukti_filter({
    fieldLabel: 'No Bukti Filter',
    id: 'id_cbahjp_nobukti_filter',
    store: strcbahjp_nobukti_filter,
    mode: 'local',
    valueField: 'no_bukti_filter',
    displayField: 'no_bukti_filter',
    typeAhead: true,
    triggerAction: 'all',
    editable: false,
    anchor: '90%',
    hiddenName: 'no_bukti_filter',
    emptyText: 'Pilih No Bukti'
});

/*END TWIN NO BUKTI FILTER*/

var headerapprovalhargapenjualanproyek = {
    layout: 'form',
    border: false,
    labelWidth: 100,
    width: 500,
    buttonAlign: 'left',
    defaults: { labelSeparator: ''},
    items: [cbahjp_nobukti_filter,
        {
        xtype: 'datefield',
        fieldLabel: 'Tanggal',
        name: 'tanggal',
        format:'d-m-Y',
        editable:false,
        id: 'ahjp_tanggal',
        anchor: '90%',
        value: new Date().format('m/d/Y')
    },{
        xtype: 'textfield',
        fieldLabel: 'Request By',
        name: 'user',
        readOnly:true,
        fieldClass:'readonly-input',
        id: 'ahjp_user',
        anchor: '90%',
        value:''
    }],
    buttons: [{
        text: 'Submit',
        formBind:true,
        handler: function(){
            var detailapprovalhargapenjualanproyek = new Array();
            strapprovalhargapenjualanproyek.each(function(node){
                detailapprovalhargapenjualanproyek.push(node.data);
            });

            var no_bukti = Ext.getCmp('id_cbahjp_nobukti_filter').getValue();
            if (no_bukti === ''){
                Ext.Msg.show({
                    title: 'Error',
                    msg: 'Silahkan Pilih No Bukti Terlebih Dulu',
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK
                });
                return;
            }
            Ext.Ajax.request({
                url: '<?= site_url("approval_harga_penjualan_proyek/approval") ?>',
                method: 'POST',
                params: {
                    detail: Ext.util.JSON.encode(detailapprovalhargapenjualanproyek),
                    no_bukti: Ext.getCmp('id_cbahjp_nobukti_filter').getValue(),
                    tanggal: Ext.getCmp('ahjp_tanggal').getValue()
                },
                callback:function(opt,success,responseObj){
                    var de = Ext.util.JSON.decode(responseObj.responseText);
                    if(de.success === true){
                        Ext.Msg.show({
                            title: 'Success',
                            msg: 'Form submitted successfully',
                            modal: true,
                            icon: Ext.Msg.INFO,
                            buttons: Ext.Msg.OK
                        });
                        clearapprovalhargapenjualanproyek();
                    }else{
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
                }
            });
        }
    }
    ]
};

/***/
var strapprovalhargapenjualanproyek = new Ext.data.Store({
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
            {name: 'rp_jual_proyek', allowBlank: false, type: 'int'},
            {name: 'rp_jual_proyek_net', allowBlank: false, type: 'int'},
            {name: 'disk_agen1', allowBlank: false, type: 'float'},
            {name: 'disk_agen2', allowBlank: false, type: 'float'},
            {name: 'disk_agen3', allowBlank: false, type: 'float'},
            {name: 'disk_agen4', allowBlank: false, type: 'float'},
            {name: 'disk_amt_agen5', allowBlank: false, type: 'int'},
            {name: 'rp_jual_agen_net', allowBlank: false, type: 'int'},
            {name: 'hrg_beli_satuan', allowBlank: false, type: 'int'},
            {name: 'hrg_supplier', allowBlank: false, type: 'int'},
            {name: 'net_hrg_supplier_inc', allowBlank: false, type: 'int'},
            {name: 'rp_ongkos_kirim', allowBlank: false, type: 'int'},
            {name: 'margin_op', allowBlank: false, type: 'text'},
            {name: 'margin', allowBlank: false, type: 'int'},
            {name: 'pct_margin', allowBlank: false, type: 'int'},
            {name: 'rp_margin', allowBlank: false, type: 'int'},
            {name: 'rp_het_harga_beli', allowBlank: false, type: 'int'},
            {name: 'rp_cogs', allowBlank: false, type: 'int'},
            {name: 'rp_het_cogs', allowBlank: false, type: 'int'},
            {name: 'p_rp_cogs', allowBlank: false, type: 'int'},
            {name: 'p_rp_het_cogs', allowBlank: false, type: 'int'},
            {name: 'rp_jual_bazar', allowBlank: false, type: 'int'},
            {name: 'rp_jual_agen', allowBlank: false, type: 'int'},
            {name: 'rp_jual_distribusi', allowBlank: false, type: 'int'},
            {name: 'qty_beli_bonus', allowBlank: false, type: 'int'},
            {name: 'kd_produk_bonus', allowBlank: false, type: 'text'},
            {name: 'qty_bonus', allowBlank: false, type: 'int'},
            {name: 'is_bonus_kelipatan', allowBlank: false, type: 'text'},
            {name: 'qty_agen', allowBlank: false, type: 'int'},
            {name: 'qty_beli_agen', allowBlank: false, type: 'int'},
            {name: 'kd_produk_agen', allowBlank: false, type: 'text'},
            {name: 'qty_bonus', allowBlank: false, type: 'int'},
            {name: 'is_agen_kelipatan', allowBlank: false, type: 'text'},
            {name: 'keterangan', allowBlank: false, type: 'text'},
            {name: 'tanggal', allowBlank: false, type: 'text'},
            {name: 'status', allowBlank: false, type: 'text'},
            {name: 'tgl_start_diskon', allowBlank: false, type: 'text'},
            {name: 'tgl_end_diskon', allowBlank: false, type: 'text'},
            {name: 'is_validasi', allowBlank: false, type: 'text'}
        ],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("approval_harga_penjualan_proyek/search_produk_by_no_bukti") ?>',
        method: 'POST'
    }),
    writer: new Ext.data.JsonWriter(
        {
            encode: true,
            writeAllFields: true
        })
});

strapprovalhargapenjualanproyek.on('update',function(){
    if(Ext.getCmp('eahjp_het_cogs').getValue() === 0){

        if(Ext.getCmp('eahjp_rp_cogs').getValue() > 0){
            if(Ext.getCmp('eahjp_rp_jual_proyek_net').getValue() < Ext.getCmp('eahjp_rp_cogs').getValue()){
                Ext.getCmp('eahjp_rp_jual_proyek_net').setValue('0');
                Ext.Msg.show({
                    title: 'Error',
                    msg: 'Net Price Jual tidak boleh lebih kecil dari HET COGS (inc PPN)',
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK,
                    fn: function(btn){
                        Ext.getCmp('eahjp_rp_jual_proyek_net').focus();
                    }
                });
                Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
            }
        }else if(Ext.getCmp('eahjp_rp_jual_proyek').getValue() < Ext.getCmp('eahjp_rp_het_harga_beli').getValue()){
            Ext.getCmp('eahjp_rp_jual_proyek').setValue('0');
            Ext.Msg.show({
                title: 'Error',
                msg: 'Net Price Jual tidak boleh lebih kecil dari HET Net Price Beli (inc PPN)',
                modal: true,
                icon: Ext.Msg.ERROR,
                buttons: Ext.Msg.OK,
                fn: function(btn){
                    Ext.getCmp('eahjp_rp_jual_proyek').focus();
                }
            });
            Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
        }
    }else{
        if(Ext.getCmp('eahjp_rp_jual_proyek').getValue() < Ext.getCmp('eahjp_het_cogs').getValue()){
            Ext.getCmp('eahjp_rp_jual_proyek').setValue('0');
            Ext.Msg.show({
                title: 'Error',
                msg: 'Net Price Jual tidak boleh lebih kecil dari HET COGS (inc PPN)',
                modal: true,
                icon: Ext.Msg.ERROR,
                buttons: Ext.Msg.OK,
                fn: function(btn){
                    Ext.getCmp('eahjp_rp_jual_proyek').focus();
                }
            });
            Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
        }else if(Ext.getCmp('eahjp_rp_jual_proyek_net').getValue() < Ext.getCmp('eahjp_rp_het_harga_beli').getValue()){
            Ext.getCmp('eahjp_rp_jual_proyek_net').setValue('0');
            Ext.Msg.show({
                title: 'Error',
                msg: 'Net Price Jual tidak boleh lebih kecil dari HET Net Price Beli (inc PPN)',
                modal: true,
                icon: Ext.Msg.ERROR,
                buttons: Ext.Msg.OK,
                fn: function(btn){
                    Ext.getCmp('eahjp_rp_jual_proyek_net').focus();
                }
            });
            Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
        }

    }
});

function HETChangeAHJProyek(){
    var hrg_beli = Ext.getCmp('eahjp_hrg_beli_satuan').getValue();
    var cogs = Ext.getCmp('eahjp_rp_cogs').getValue();
    var ongkos = Ext.getCmp('eahjp_rp_ongkos_kirim').getValue();
    var margin_op = Ext.getCmp('eahjp_margin_op').getValue();
    var margin = Ext.getCmp('eahjp_margin').getValue();
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
    var ongkos_cogs = Ext.getCmp('eahjp_rp_ongkos_kirim').getValue();
    var HETCOGS = (cogs + margin_rp + ongkos_cogs) * 1.1;
    if(cogs === 0){
        HETCOGS = 0;
    }
    Ext.getCmp('eahjp_rp_het_harga_beli').setValue(HET);
    Ext.getCmp('eahjp_het_cogs').setValue(HETCOGS);
    Ext.getCmp('eahjp_pct_margin').setValue(margin_pct);
    Ext.getCmp('eahjp_rp_margin').setValue(margin_rp);
    EditedAHJProyek();
};

function EditedAHJProyek(){
    Ext.getCmp('eahjp_edited').setValue('Y');
};

function HitungNetPJualAHJProyek(){
    EditedAHJProyek();
    var total_disk = 0;
    var rp_jual_bazar = Ext.getCmp('eahjp_rp_jual_proyek').getValue();
    var disk_proyek1_op = Ext.getCmp('eahjb_disk_proyek1_op').getValue();
    var disk_proyek1 = Ext.getCmp('eahjb_disk_proyek1').getValue();
    if (disk_proyek1_op === '%'){
        // disk_proyek1 = (disk_proyek1*rp_jual_bazar)/100;
        total_disk = rp_jual_bazar-(rp_jual_bazar*(disk_proyek1/100));
    }else{
        total_disk = rp_jual_bazar-disk_proyek1;
    }

    var disk_proyek2_op = Ext.getCmp('eahjd_disk_proyek2_op').getValue();
    var disk_proyek2 = Ext.getCmp('eahjd_disk_proyek2').getValue();
    if (disk_proyek2_op === '%'){
        // disk_proyek2 = (disk_proyek2*disk_proyek1)/100;
        total_disk =  total_disk-(total_disk*(disk_proyek2/100));
    }else{
        total_disk = total_disk-disk_proyek2;
    }

    var disk_proyek3_op = Ext.getCmp('eahjd_disk_proyek3_op').getValue();
    var disk_proyek3 = Ext.getCmp('eahjd_disk_proyek3').getValue();
    if (disk_proyek3_op === '%'){
        // disk_proyek3 = (disk_proyek3*disk_proyek2)/100;
        total_disk = total_disk-(total_disk*(disk_proyek3/100));
    }else{
        total_disk = total_disk-disk_proyek3;
    }

    var disk_proyek4_op = Ext.getCmp('eahjd_disk_proyek4_op').getValue();
    var disk_proyek4 = Ext.getCmp('eahjd_disk_proyek4').getValue();
    if (disk_proyek4_op === '%'){
        // disk_proyek4 = (disk_proyek4*disk_proyek3)/100;
        total_disk = total_disk-(total_disk*(disk_proyek4/100));
    }else{
        total_disk = total_disk-disk_proyek4;
    }

    var total_disk = total_disk-Ext.getCmp('eahjd_disk_proyek5').getValue();

    var net_jual_kons = total_disk;
    Ext.getCmp('eahjp_rp_jual_proyek_net').setValue(net_jual_kons);
}

var editorapprovalhargapenjualanproyek = new Ext.ux.grid.RowEditor({
    saveText: 'Update'
});

var gridapprovalhargapenjualanproyek = new Ext.grid.GridPanel({
    store: strapprovalhargapenjualanproyek,
    stripeRows: true,
    height: 350,
    frame: true,
    border:true,
    plugins: [editorapprovalhargapenjualanproyek],
    columns: [ {
        dataIndex: 'kd_diskon_sales',
        hidden: true
    },{
        dataIndex: 'pct_margin',
        hidden: true,
        editor: new Ext.form.TextField({
            readOnly: true,
            id: 'eahjp_pct_margin'
        })
    },{
        dataIndex: 'rp_margin',
        hidden: true,
        editor: new Ext.form.TextField({
            readOnly: true,
            id: 'eahjp_rp_margin'
        })
    },{
        dataIndex: 'koreksi_ke',
        hidden: true
    },{
        dataIndex: 'koreksi_produk',
        hidden: true
    },{
        header: 'Status',
        dataIndex: 'status',
        width: 80,
        sortable: true,
        editor: {
            xtype:          'combo',
            store:          new Ext.data.JsonStore({
                fields : ['name'],
                data   : [
                    {name : 'Approve'},
                    {name : 'Reject'}
                ]
            }),
            id:           	'eahjp_status',
            mode:           'local',
            name:           'status',
            value:          'Approve',
            width:			80,
            editable:       false,
            hiddenName:     'status',
            valueField:     'name',
            displayField:   'name',
            triggerAction:  'all',
            forceSelection: true
        }
    },{
        header: 'Edited',
        dataIndex: 'edited',
        width: 50,
        sortable: true,
        editor: new Ext.form.TextField({
            readOnly: true,
            fieldClass: 'readonly-input',
            id: 'eahjp_edited'
        })
    },{
        header: 'Tanggal',
        dataIndex: 'tanggal',
        width: 100,
        sortable: true,
        editor: new Ext.form.TextField({
            readOnly: true,
            fieldClass: 'readonly-input',
            id: 'eahjp_tanggal'
        })
    },{
        header: 'Kode Barang',
        dataIndex: 'kd_produk',
        width: 100,
        sortable: true,
        editor: new Ext.form.TextField({
            readOnly: true,
            fieldClass: 'readonly-input',
            id: 'eahjp_kd_produk'
        })
    },{
        header: 'Kode Brg Lama',
        dataIndex: 'kd_produk_lama',
        width: 100,
        sortable: true,
        editor: new Ext.form.TextField({
            readOnly: true,
            fieldClass: 'readonly-input',
            id: 'eahjp_kd_produk_lama'
        })
    },{
        header: 'Nama Barang',
        dataIndex: 'nama_produk',
        width: 300,
        sortable: true,
        editor: new Ext.form.TextField({
            readOnly: true,
            fieldClass: 'readonly-input',
            id: 'eahjp_nama_produk'
        })
    },{
        header: 'Satuan',
        dataIndex: 'nm_satuan',
        width: 80,
        editor: new Ext.form.TextField({
            readOnly: true,
            id: 'eahjp_satuan',
            fieldClass: 'readonly-input'
        })
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Net Price Beli (Inc.PPN)',
        dataIndex: 'net_hrg_supplier_inc',
        width: 150,
        editor: {
            xtype: 'numberfield',
            id: 'eahjp_hrg_beli_satuan',
            readOnly: true,
            fieldClass: 'readonly-input'
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'COGS',
        dataIndex: 'rp_cogs',
        width: 100,
        editor: {
            xtype: 'numberfield',
            id: 'eahjp_rp_cogs',
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
            id: 'eahjp_rp_ongkos_kirim',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        HETChangeAHJProyek();
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
            id:           	'eahjp_margin_op',
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
                    Ext.getCmp('eahjp_margin').setValue(0);
                    HETChangeAHJProyek();
                },
                select:function(){
                    HETChangeAHJProyek();
                    Ext.getCmp('eahjp_margin').setMaxValue(Number.MAX_VALUE);
                    if (this.getValue() === 'persen')
                        Ext.getCmp('eahjp_margin').maxValue = 100;
                    else
                        Ext.getCmp('eahjp_margin').maxLength = 11;
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
            id: 'eahjp_margin',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        HETChangeAHJProyek();
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
            id: 'eahjp_rp_het_harga_beli',
            readOnly: true
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'HET COGS (Inc.PPN)',
        dataIndex: 'rp_het_cogs',
        width: 140,
        editor: {
            xtype: 'numberfield',
            id: 'eahjp_het_cogs',
            readOnly: true
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Harga Jual Proyek',
        dataIndex: 'rp_jual_proyek',
        width: 180,
        editor: {
            xtype: 'numberfield',
            id: 'eahjp_rp_jual_proyek',
            listeners:{
                'change': function() {
                    if(Ext.getCmp('eahjp_rp_cogs').getValue() > 0){
                        if(this.getValue() < Ext.getCmp('eahjp_rp_cogs').getValue()){
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
                        if(this.getValue() < Ext.getCmp('eahjp_rp_het_harga_beli').getValue()){
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
                        // EditedAHJ();
                        HitungNetPJualAHJProyek();
                    }, c);
                }
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
            id:           	'eahjb_disk_proyek1_op',
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
                    Ext.getCmp('eahjb_disk_proyek1').setValue(0);
                },
                select:function(){
                    Ext.getCmp('eahjb_disk_proyek1').setMaxValue(Number.MAX_VALUE);
                    if (this.getValue() === 'persen')
                        Ext.getCmp('eahjb_disk_proyek1').maxValue = 100;
                    else
                        Ext.getCmp('eahjb_disk_proyek1').maxLength = 11;
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
            id: 'eahjb_disk_proyek1',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        HitungNetPJualAHJProyek();
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
            id:           	'eahjd_disk_proyek2_op',
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
                    Ext.getCmp('eahjd_disk_proyek2').setValue(0);
                },
                select:function(){
                    Ext.getCmp('eahjd_disk_proyek2').setMaxValue(Number.MAX_VALUE);
                    if (this.getValue() === 'persen')
                        Ext.getCmp('eahjd_disk_proyek2').maxValue = 100;
                    else
                        Ext.getCmp('eahjd_disk_proyek2').maxLength = 11;
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
            id: 'eahjd_disk_proyek2',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // EditedAHJ();
                        HitungNetPJualAHJProyek();
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
            id:           	'eahjd_disk_proyek3_op',
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
                    Ext.getCmp('eahjd_disk_proyek3').setValue(0);
                },
                select:function(){
                    Ext.getCmp('eahjd_disk_proyek3').setMaxValue(Number.MAX_VALUE);
                    if (this.getValue() === 'persen')
                        Ext.getCmp('eahjd_disk_proyek3').maxValue = 100;
                    else
                        Ext.getCmp('eahjd_disk_proyek3').maxLength = 11;
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
            id: 'eahjd_disk_proyek3',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // EditedAHJ();
                        HitungNetPJualAHJProyek();
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
            id:           	'eahjd_disk_proyek4_op',
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
                    Ext.getCmp('eahjd_disk_proyek4').setValue(0);
                },
                select:function(){
                    Ext.getCmp('eahjd_disk_proyek4').setMaxValue(Number.MAX_VALUE);
                    if (this.getValue() === 'persen')
                        Ext.getCmp('eahjd_disk_proyek4').maxValue = 100;
                    else
                        Ext.getCmp('eahjd_disk_proyek4').maxLength = 11;
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
            id: 'eahjd_disk_proyek4',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // EditedAHJ();
                        HitungNetPJualAHJProyek();
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
            id: 'eahjd_disk_proyek5',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // EditedAHJ();
                        HitungNetPJualAHJProyek();
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
            id: 'eahjp_rp_jual_proyek_net',
            readOnly: true,
            fieldClass: 'readonly-input',
            listeners:{
                'change': function() {
                    if(Ext.getCmp('eahjp_rp_cogs').getValue() > 0){
                        if(Ext.getCmp('eahjp_rp_jual_proyek_net').getValue() < Ext.getCmp('eahjp_rp_cogs').getValue()){
                            Ext.getCmp('eahjp_rp_jual_proyek_net').setValue('0');
                            Ext.Msg.show({
                                title: 'Error',
                                msg: 'Net Price Jual tidak boleh lebih kecil dari HET COGS (inc PPN)',
                                modal: true,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OK,
                                fn: function(btn){
                                    Ext.getCmp('eahjp_rp_jual_proyek_net').focus();
                                }
                            });
                            Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                        }
                    }else{
                        if(Ext.getCmp('eahjp_rp_jual_proyek_net').getValue() < Ext.getCmp('eahjp_rp_het_harga_beli').getValue()){
                            Ext.getCmp('eahjp_rp_jual_proyek_net').setValue('0');
                            Ext.Msg.show({
                                title: 'Error',
                                msg: 'Net Price Jual tidak boleh lebih kecil dari HET Net Price Beli (inc PPN)',
                                modal: true,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OK,
                                fn: function(btn){
                                    Ext.getCmp('eahjp_rp_jual_proyek_net').focus();
                                }
                            });
                            Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                        }
                    }


                },'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // EditedAHJ();
                    }, c);
                }
            }
        }
    },{
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
            id: 'eahjp_qty_beli_bonus',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // EditedAHJ();
                    }, c);
                }
            }
        }
    },{
        header: 'Kd Produk',
        dataIndex: 'kd_produk_bonus',
        width: 150,
        editor: new Ext.ux.TwinComboHj({
            id: 'eahjp_kd_produk_bonus',
            store: strcbkdprodukhj,
            valueField: 'kd_produk_bonus',
            displayField: 'kd_produk_bonus',
            typeAhead: true,
            editable: false,
            hiddenName: 'kd_produk_bonus',
            emptyText: 'Pilih Kode Produk',
            listeners:{
                'expand': function(){
                    strcbkdprodukhj.load();
                    // EditedAHJ();
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
            id: 'eahjp_qty_bonus',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // EditedAHJ();
                    }, c);
                }
            }
        }
    },{
        header: 'Kelipatan ?',
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
            id:           	'eahjp_is_bonus_kelipatan',
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
                    c.getEl().on('keyup', function() {}, c);
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
                id: 'eahjp_tgl_start_diskon',
                format: 'd/m/Y',
                listeners:{			
                    'change': function() {
                          Ext.getCmp('eahjp_edited').setValue('Y');
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
                id: 'eahjp_tgl_end_diskon',
                format: 'd/m/Y',
                minValue: (new Date()).clearTime(),
                listeners:{			
                    'change': function() {
                          Ext.getCmp('eahjp_edited').setValue('Y');
                    }
                }
            })
        },{
        header: 'Ket. Perubahan',
        dataIndex: 'keterangan',
        width: 300,
        editor: new Ext.form.TextField({
            readOnly: true,
            fieldClass: 'readonly-input',
            id: 'eahjp_keterangan'
        })
    },{
        header: 'Is Validasi',
        dataIndex: 'is_validasi',
        width: 300,
        editor: new Ext.form.TextField({
            readOnly: true,
            fieldClass: 'readonly-input',
            id: 'eahjp_is_validasi'
        })
    }]
});

/***/
var approvalhargapenjualanproyek = new Ext.FormPanel({
    id: 'approvalhargapenjualanproyek',
    buttonAlign: 'left',
    border: false,
    frame: true,
    monitorValid: true,
    labelWidth: 130,
    items: [{
        bodyStyle: {
            margin: '0px 0px 15px 0px'
        },
        items: [headerapprovalhargapenjualanproyek,
                gridapprovalhargapenjualanproyek
        ]
    }
    ],
    buttons: [{
        text: 'Reset',
        handler: function(){
            clearapprovalhargapenjualanproyek();
        }
    }]
});

function clearapprovalhargapenjualanproyek(){
    Ext.getCmp('approvalhargapenjualanproyek').getForm().reset();
    strapprovalhargapenjualanproyek.removeAll();
}
</script>
