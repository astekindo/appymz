<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
/*START TWIN NO BUKTI FILTER*/

var strcbahjb_nobukti_filter = new Ext.data.ArrayStore({
    fields: ['no_bukti_filter'],
    data : []
});

var strgridahjb_nobukti_filter = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: ['no_bukti_filter','keterangan','created_by','nama_supplier'],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("approval_harga_penjualan_bazar/get_no_bukti_filter") ?>',
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

var searchgridahjb_nobukti_filter = new Ext.app.SearchField({
    store: strgridahjb_nobukti_filter,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridahjb_nobukti_filter'
});


var gridahjb_nobukti_filter = new Ext.grid.GridPanel({
    store: strgridahjb_nobukti_filter,
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
        items: [searchgridahjb_nobukti_filter]
    }),
    listeners: {
        'rowdblclick': function(){
            var sm = this.getSelectionModel();
            var sel = sm.getSelections();
            if (sel.length > 0) {
                Ext.getCmp('ahjb_user').setValue(sel[0].get('created_by'));
                Ext.getCmp('id_cbahjb_nobukti_filter').setValue(sel[0].get('no_bukti_filter'));
                gridapprovalhargapenjualanbazar.store.load({
                    params:{
                        no_bukti: Ext.getCmp('id_cbahjb_nobukti_filter').getValue()
                    }
                });
                menuahjb_nobukti_filter.hide();
            }
        }
    }
});

var menuahjb_nobukti_filter = new Ext.menu.Menu();
menuahjb_nobukti_filter.add(new Ext.Panel({
    title: 'Pilih No Bukti',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 500,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridahjb_nobukti_filter],
    buttons: [{
        text: 'Close',
        handler: function(){
            menuahjb_nobukti_filter.hide();
        }
    }]
}));

Ext.ux.TwinComboahjb_nobukti_filter = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function(){
        //load store grid
        strgridahjb_nobukti_filter.load();
        menuahjb_nobukti_filter.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuahjb_nobukti_filter.on('hide', function(){
    var sf = Ext.getCmp('id_searchgridahjb_nobukti_filter').getValue();
    if( sf !== ''){
        Ext.getCmp('id_searchgridahjb_nobukti_filter').setValue('');
        searchgridahjb_nobukti_filter.onTrigger2Click();
    }
});

var cbahjb_nobukti_filter = new Ext.ux.TwinComboahjb_nobukti_filter({
    fieldLabel: 'No Bukti Filter',
    id: 'id_cbahjb_nobukti_filter',
    store: strcbahjb_nobukti_filter,
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

var headerapprovalhargapenjualanbazar = {

    layout: 'form',
    border: false,
    labelWidth: 100,
    width: 500,
    buttonAlign: 'left',
    defaults: { labelSeparator: ''},
    items: [cbahjb_nobukti_filter,{
        xtype: 'datefield',
        fieldLabel: 'Tanggal',
        name: 'tanggal',
        // allowBlank:false,   
        format:'d-m-Y',
        editable:false,
        id: 'ahjb_tanggal',
        anchor: '90%',
        value: new Date().format('m/d/Y')
    },{
        xtype: 'textfield',
        fieldLabel: 'Request By',
        name: 'user',
        readOnly:true,
        fieldClass:'readonly-input',
        id: 'ahjb_user',
        anchor: '90%',
        value:''
    }],
    buttons: [{
        text: 'Submit',
        formBind:true,
        handler: function(){

            var detailapprovalhargapenjualanbazar = new Array();
            strapprovalhargapenjualanbazar.each(function(node){
                detailapprovalhargapenjualanbazar.push(node.data);
            });

            var no_bukti = Ext.getCmp('id_cbahjb_nobukti_filter').getValue();
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
                url: '<?= site_url("approval_harga_penjualan_bazar/approval") ?>',
                method: 'POST',
                params: {
                    detail: Ext.util.JSON.encode(detailapprovalhargapenjualanbazar),
                    no_bukti: Ext.getCmp('id_cbahjb_nobukti_filter').getValue(),
                    tanggal: Ext.getCmp('ahjb_tanggal').getValue()
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
                        clearapprovalhargapenjualanbazar();
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
var strapprovalhargapenjualanbazar = new Ext.data.Store({
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
            {name: 'disk_bazar1_op', allowBlank: false, type: 'text'},
            {name: 'disk_bazar2_op', allowBlank: false, type: 'text'},
            {name: 'disk_bazar3_op', allowBlank: false, type: 'text'},
            {name: 'disk_bazar4_op', allowBlank: false, type: 'text'},
            {name: 'disk_agen1_op', allowBlank: false, type: 'text'},
            {name: 'disk_agen2_op', allowBlank: false, type: 'text'},
            {name: 'disk_agen3_op', allowBlank: false, type: 'text'},
            {name: 'disk_agen4_op', allowBlank: false, type: 'text'},
            {name: 'disk_bazar1', allowBlank: false, type: 'float'},
            {name: 'disk_bazar2', allowBlank: false, type: 'float'},
            {name: 'disk_bazar3', allowBlank: false, type: 'float'},
            {name: 'disk_bazar4', allowBlank: false, type: 'float'},
            {name: 'disk_amt_bazar5', allowBlank: false, type: 'int'},
            {name: 'rp_jual_bazar_net', allowBlank: false, type: 'int'},
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
        url: '<?= site_url("approval_harga_penjualan_bazar/search_produk_by_no_bukti") ?>',
        method: 'POST'
    }),
    writer: new Ext.data.JsonWriter(
        {
            encode: true,
            writeAllFields: true
        })
});

strapprovalhargapenjualanbazar.on('update',function(){
    if(Ext.getCmp('eahjb_het_cogs').getValue() === 0){

        if(Ext.getCmp('eahjb_rp_cogs').getValue() > 0){
            if(Ext.getCmp('eahjd_rp_jual_bazar_net').getValue() < Ext.getCmp('eahjb_rp_cogs').getValue()){
                Ext.getCmp('eahjd_rp_jual_bazar_net').setValue('0');
                Ext.Msg.show({
                    title: 'Error',
                    msg: 'Net Price Jual tidak boleh lebih kecil dari HET COGS (inc PPN)',
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK,
                    fn: function(btn){
                        Ext.getCmp('eahjd_rp_jual_bazar_net').focus();
                    }
                });
                Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
            }
        }else if(Ext.getCmp('eahjd_rp_jual_bazar').getValue() < Ext.getCmp('eahjb_rp_het_harga_beli').getValue()){
            Ext.getCmp('eahjd_rp_jual_bazar').setValue('0');
            Ext.Msg.show({
                title: 'Error',
                msg: 'Net Price Jual tidak boleh lebih kecil dari HET Net Price Beli (inc PPN)',
                modal: true,
                icon: Ext.Msg.ERROR,
                buttons: Ext.Msg.OK,
                fn: function(btn){
                    Ext.getCmp('eahjd_rp_jual_bazar').focus();
                }
            });
            Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
        }
    }else{
        if(Ext.getCmp('eahjd_rp_jual_bazar').getValue() < Ext.getCmp('eahjb_het_cogs').getValue()){
            Ext.getCmp('eahjd_rp_jual_bazar').setValue('0');
            Ext.Msg.show({
                title: 'Error',
                msg: 'Net Price Jual tidak boleh lebih kecil dari HET COGS (inc PPN)',
                modal: true,
                icon: Ext.Msg.ERROR,
                buttons: Ext.Msg.OK,
                fn: function(btn){
                    Ext.getCmp('eahjd_rp_jual_bazar').focus();
                }
            });
            Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
        }else if(Ext.getCmp('eahjd_rp_jual_bazar_net').getValue() < Ext.getCmp('eahjb_rp_het_harga_beli').getValue()){
            Ext.getCmp('eahjd_rp_jual_bazar_net').setValue('0');
            Ext.Msg.show({
                title: 'Error',
                msg: 'Net Price Jual tidak boleh lebih kecil dari HET Net Price Beli (inc PPN)',
                modal: true,
                icon: Ext.Msg.ERROR,
                buttons: Ext.Msg.OK,
                fn: function(btn){
                    Ext.getCmp('eahjd_rp_jual_bazar_net').focus();
                }
            });
            Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
        }

    }
});

function HETChangeAHJBaz(){
    var hrg_beli = Ext.getCmp('eahjb_hrg_beli_satuan').getValue();
    var cogs = Ext.getCmp('eahjb_rp_cogs').getValue();
    var ongkos = Ext.getCmp('eahjb_rp_ongkos_kirim').getValue();
    var margin_op = Ext.getCmp('eahjb_margin_op').getValue();
    var margin = Ext.getCmp('eahjb_margin').getValue();
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
    var ongkos_cogs = Ext.getCmp('eahjb_rp_ongkos_kirim').getValue();
    var HETCOGS = (cogs + margin_rp + ongkos_cogs) * 1.1;
    if(cogs === 0){
        HETCOGS = 0;
    }
    Ext.getCmp('eahjb_rp_het_harga_beli').setValue(HET);
    Ext.getCmp('eahjb_het_cogs').setValue(HETCOGS);
    Ext.getCmp('eahjb_pct_margin').setValue(margin_pct);
    Ext.getCmp('eahjb_rp_margin').setValue(margin_rp);
    EditedAHJBaz();
};

function EditedAHJBaz(){
    Ext.getCmp('eahjb_edited').setValue('Y');
};

function HitungNetPJualAHJBazar(){
    EditedAHJBaz();
    var total_disk = 0;
    var rp_jual_bazar = Ext.getCmp('eahjd_rp_jual_bazar').getValue();
    var disk_bazar1_op = Ext.getCmp('eahjb_disk_bazar1_op').getValue();
    var disk_bazar1 = Ext.getCmp('eahjb_disk_bazar1').getValue();
    if (disk_bazar1_op === '%'){
        // disk_bazar1 = (disk_bazar1*rp_jual_bazar)/100;
        total_disk = rp_jual_bazar-(rp_jual_bazar*(disk_bazar1/100));
    }else{
        total_disk = rp_jual_bazar-disk_bazar1;
    }

    var disk_bazar2_op = Ext.getCmp('eahjd_disk_bazar2_op').getValue();
    var disk_bazar2 = Ext.getCmp('eahjd_disk_bazar2').getValue();
    if (disk_bazar2_op === '%'){
        // disk_bazar2 = (disk_bazar2*disk_bazar1)/100;
        total_disk =  total_disk-(total_disk*(disk_bazar2/100));
    }else{
        total_disk = total_disk-disk_bazar2;
    }

    var disk_bazar3_op = Ext.getCmp('eahjd_disk_bazar3_op').getValue();
    var disk_bazar3 = Ext.getCmp('eahjd_disk_bazar3').getValue();
    if (disk_bazar3_op === '%'){
        // disk_bazar3 = (disk_bazar3*disk_bazar2)/100;
        total_disk = total_disk-(total_disk*(disk_bazar3/100));
    }else{
        total_disk = total_disk-disk_bazar3;
    }

    var disk_bazar4_op = Ext.getCmp('eahjd_disk_bazar4_op').getValue();
    var disk_bazar4 = Ext.getCmp('eahjd_disk_bazar4').getValue();
    if (disk_bazar4_op === '%'){
        // disk_bazar4 = (disk_bazar4*disk_bazar3)/100;
        total_disk = total_disk-(total_disk*(disk_bazar4/100));
    }else{
        total_disk = total_disk-disk_bazar4;
    }

    var total_disk = total_disk-Ext.getCmp('eahjd_disk_bazar5').getValue();

    var net_jual_kons = total_disk;
    Ext.getCmp('eahjd_rp_jual_bazar_net').setValue(net_jual_kons);
}

var editorapprovalhargapenjualanbazar = new Ext.ux.grid.RowEditor({
    saveText: 'Update'
});

var gridapprovalhargapenjualanbazar = new Ext.grid.GridPanel({
    store: strapprovalhargapenjualanbazar,
    stripeRows: true,
    height: 350,
    frame: true,
    border:true,
    plugins: [editorapprovalhargapenjualanbazar],
    columns: [ {
        dataIndex: 'kd_diskon_sales',
        hidden: true
    },{
        dataIndex: 'pct_margin',
        hidden: true,
        editor: new Ext.form.TextField({
            readOnly: true,
            id: 'eahjb_pct_margin'
        })
    },{
        dataIndex: 'rp_margin',
        hidden: true,
        editor: new Ext.form.TextField({
            readOnly: true,
            id: 'eahjb_rp_margin'
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
            id:           	'eahjb_status',
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
            id: 'eahjb_edited'
        })
    },{
        header: 'Tanggal',
        dataIndex: 'tanggal',
        width: 100,
        sortable: true,
        editor: new Ext.form.TextField({
            readOnly: true,
            fieldClass: 'readonly-input',
            id: 'eahjb_tanggal'
        })
    },{
        header: 'Kode Barang',
        dataIndex: 'kd_produk',
        width: 100,
        sortable: true,
        editor: new Ext.form.TextField({
            readOnly: true,
            fieldClass: 'readonly-input',
            id: 'eahjb_kd_produk'
        })
    },{
        header: 'Kode Brg Lama',
        dataIndex: 'kd_produk_lama',
        width: 100,
        sortable: true,
        editor: new Ext.form.TextField({
            readOnly: true,
            fieldClass: 'readonly-input',
            id: 'eahjb_kd_produk_lama'
        })
    },{
        header: 'Nama Barang',
        dataIndex: 'nama_produk',
        width: 300,
        sortable: true,
        editor: new Ext.form.TextField({
            readOnly: true,
            fieldClass: 'readonly-input',
            id: 'eahjb_nama_produk'
        })
    },{
        header: 'Satuan',
        dataIndex: 'nm_satuan',
        width: 80,
        editor: new Ext.form.TextField({
            readOnly: true,
            id: 'eahjb_satuan',
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
            id: 'eahjb_hrg_beli_satuan',
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
            id: 'eahjb_rp_cogs',
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
            id: 'eahjb_rp_ongkos_kirim',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        HETChangeAHJBaz();
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
            id:           	'eahjb_margin_op',
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
                    Ext.getCmp('eahjb_margin').setValue(0);
                    HETChangeAHJBaz();
                },
                select:function(){
                    HETChangeAHJBaz();
                    Ext.getCmp('eahjb_margin').setMaxValue(Number.MAX_VALUE);
                    if (this.getValue() === 'persen')
                        Ext.getCmp('eahjb_margin').maxValue = 100;
                    else
                        Ext.getCmp('eahjb_margin').maxLength = 11;
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
            id: 'eahjb_margin',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        HETChangeAHJBaz();
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
            id: 'eahjb_rp_het_harga_beli',
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
            id: 'eahjb_het_cogs',
            readOnly: true
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Harga Jual Bazar',
        dataIndex: 'rp_jual_bazar',
        width: 180,
        editor: {
            xtype: 'numberfield',
            id: 'eahjd_rp_jual_bazar',
            listeners:{
                'change': function() {
                    if(Ext.getCmp('eahjb_rp_cogs').getValue() > 0){
                        if(this.getValue() < Ext.getCmp('eahjb_rp_cogs').getValue()){
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
                        if(this.getValue() < Ext.getCmp('eahjb_rp_het_harga_beli').getValue()){
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
                        HitungNetPJualAHJBazar();
                    }, c);
                }
            }
        }
    },{
        header: '% / Rp',
        dataIndex: 'disk_bazar1_op',
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
            id:           	'eahjb_disk_bazar1_op',
            mode:           'local',
            name:           'disk_bazar1_op',
            value:          '%',
            width:			50,
            editable:       false,
            hiddenName:     'disk_bazar1_op',
            valueField:     'name',
            displayField:   'name',
            triggerAction:  'all',
            forceSelection: true,
            listeners:{
                'expand':function(){
                    Ext.getCmp('eahjb_disk_bazar1').setValue(0);
                },
                select:function(){
                    Ext.getCmp('eahjb_disk_bazar1').setMaxValue(Number.MAX_VALUE);
                    if (this.getValue() === 'persen')
                        Ext.getCmp('eahjb_disk_bazar1').maxValue = 100;
                    else
                        Ext.getCmp('eahjb_disk_bazar1').maxLength = 11;
                }
            }
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        // format: '0,0',
        header: 'Diskon Bazar 1',
        dataIndex: 'disk_bazar1',
        width: 150,
        editor: {
            xtype: 'numberfield',
            msgTarget: 'under',
            flex:1,
            width:115,
            name : 'disk_bazar1',
            id: 'eahjb_disk_bazar1',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        HitungNetPJualAHJBazar();
                    }, c);
                }

            }
        }
    },{
        header: '% / Rp',
        dataIndex: 'disk_bazar2_op',
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
            id:           	'eahjd_disk_bazar2_op',
            mode:           'local',
            name:           'disk_bazar2_op',
            value:          '%',
            width:			50,
            editable:       false,
            hiddenName:     'disk_bazar2_op',
            valueField:     'name',
            displayField:   'name',
            triggerAction:  'all',
            forceSelection: true,
            listeners:{
                'expand':function(){
                    Ext.getCmp('eahjd_disk_bazar2').setValue(0);
                },
                select:function(){
                    Ext.getCmp('eahjd_disk_bazar2').setMaxValue(Number.MAX_VALUE);
                    if (this.getValue() === 'persen')
                        Ext.getCmp('eahjd_disk_bazar2').maxValue = 100;
                    else
                        Ext.getCmp('eahjd_disk_bazar2').maxLength = 11;
                }
            }
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        // format: '0,0',
        header: 'Diskon Bazar 2',
        dataIndex: 'disk_bazar2',
        width: 150,
        editor: {
            xtype: 'numberfield',
            msgTarget: 'under',
            flex:1,
            width:115,
            name : 'disk_bazar2',
            id: 'eahjd_disk_bazar2',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // EditedAHJ();
                        HitungNetPJualAHJBazar();
                    }, c);
                }
            }
        }
    },{
        header: '% / Rp',
        dataIndex: 'disk_bazar3_op',
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
            id:           	'eahjd_disk_bazar3_op',
            mode:           'local',
            name:           'disk_bazar3_op',
            value:          '%',
            width:			50,
            editable:       false,
            hiddenName:     'disk_bazar3_op',
            valueField:     'name',
            displayField:   'name',
            triggerAction:  'all',
            forceSelection: true,
            listeners:{
                'expand':function(){
                    Ext.getCmp('eahjd_disk_bazar3').setValue(0);
                },
                select:function(){
                    Ext.getCmp('eahjd_disk_bazar3').setMaxValue(Number.MAX_VALUE);
                    if (this.getValue() === 'persen')
                        Ext.getCmp('eahjd_disk_bazar3').maxValue = 100;
                    else
                        Ext.getCmp('eahjd_disk_bazar3').maxLength = 11;
                }
            }
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        // format: '0,0',
        header: 'Diskon Bazar 3',
        dataIndex: 'disk_bazar3',
        width: 150,
        editor: {
            xtype: 'numberfield',
            msgTarget: 'under',
            flex:1,
            width:115,
            name : 'disk_bazar3',
            id: 'eahjd_disk_bazar3',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // EditedAHJ();
                        HitungNetPJualAHJBazar();
                    }, c);
                }
            }
        }
    },{
        header: '% / Rp',
        dataIndex: 'disk_bazar4_op',
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
            id:           	'eahjd_disk_bazar4_op',
            mode:           'local',
            name:           'disk_bazar4_op',
            value:          '%',
            width:			50,
            editable:       false,
            hiddenName:     'disk_bazar4_op',
            valueField:     'name',
            displayField:   'name',
            triggerAction:  'all',
            forceSelection: true,
            listeners:{
                'expand':function(){
                    Ext.getCmp('eahjd_disk_bazar4').setValue(0);
                },
                select:function(){
                    Ext.getCmp('eahjd_disk_bazar4').setMaxValue(Number.MAX_VALUE);
                    if (this.getValue() === 'persen')
                        Ext.getCmp('eahjd_disk_bazar4').maxValue = 100;
                    else
                        Ext.getCmp('eahjd_disk_bazar4').maxLength = 11;
                }
            }
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        // format: '0,0',
        header: 'Diskon Bazar 4',
        dataIndex: 'disk_bazar4',
        width: 150,
        editor: {
            xtype: 'numberfield',
            msgTarget: 'under',
            flex:1,
            width:115,
            name : 'disk_bazar4',
            id: 'eahjd_disk_bazar4',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // EditedAHJ();
                        HitungNetPJualAHJBazar();
                    }, c);
                }
            }
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Diskon Bazar 5',
        dataIndex: 'disk_amt_bazar5',
        width: 150,
        editor: {
            xtype: 'numberfield',
            msgTarget: 'under',
            flex:1,
            width:115,
            name : 'disk_bazar5',
            id: 'eahjd_disk_bazar5',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // EditedAHJ();
                        HitungNetPJualAHJBazar();
                    }, c);
                }
            }
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Net Price Jual Bazar',
        dataIndex: 'rp_jual_bazar_net',
        width: 180,
        editor: {
            xtype: 'numberfield',
            id: 'eahjd_rp_jual_bazar_net',
            readOnly: true,
            fieldClass: 'readonly-input',
            listeners:{
                'change': function() {
                    if(Ext.getCmp('eahjb_rp_cogs').getValue() > 0){
                        if(Ext.getCmp('eahjd_rp_jual_bazar_net').getValue() < Ext.getCmp('eahjb_rp_cogs').getValue()){
                            Ext.getCmp('eahjd_rp_jual_bazar_net').setValue('0');
                            Ext.Msg.show({
                                title: 'Error',
                                msg: 'Net Price Jual tidak boleh lebih kecil dari HET COGS (inc PPN)',
                                modal: true,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OK,
                                fn: function(btn){
                                    Ext.getCmp('eahjd_rp_jual_bazar_net').focus();
                                }
                            });
                            Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                        }
                    }else{
                        if(Ext.getCmp('eahjd_rp_jual_bazar_net').getValue() < Ext.getCmp('eahjb_rp_het_harga_beli').getValue()){
                            Ext.getCmp('eahjd_rp_jual_bazar_net').setValue('0');
                            Ext.Msg.show({
                                title: 'Error',
                                msg: 'Net Price Jual tidak boleh lebih kecil dari HET Net Price Beli (inc PPN)',
                                modal: true,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OK,
                                fn: function(btn){
                                    Ext.getCmp('eahjd_rp_jual_bazar_net').focus();
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
            id: 'eahjb_qty_beli_bonus',
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
            id: 'eahjb_kd_produk_bonus',
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
            id: 'eahjb_qty_bonus',
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
            id:           	'eahjb_is_bonus_kelipatan',
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
                id: 'eahpb_tgl_start_diskon',
                format: 'd/m/Y',
                minValue: (new Date()).clearTime(),
                 listeners:{			
                    'change': function() {
                          Ext.getCmp('eahjb_edited').setValue('Y');
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
                id: 'eahpb_tgl_end_diskon',
                format: 'd/m/Y',
                minValue: (new Date()).clearTime(),
                listeners:{			
                    'change': function() {
                          Ext.getCmp('eahjb_edited').setValue('Y');
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
            id: 'eahjb_keterangan'
        })
    },{
        header: 'Is Validasi',
        dataIndex: 'is_validasi',
        width: 300,
        editor: new Ext.form.TextField({
            readOnly: true,
            fieldClass: 'readonly-input',
            id: 'eahjb_is_validasi'
        })
    }]
});

/***/
var approvalhargapenjualanbazar = new Ext.FormPanel({
    id: 'approvalhpbazar',
    buttonAlign: 'left',
    border: false,
    frame: true,
    monitorValid: true,
    labelWidth: 130,
    items: [{
        bodyStyle: {
            margin: '0px 0px 15px 0px'
        },
        items: [headerapprovalhargapenjualanbazar,gridapprovalhargapenjualanbazar]
    }
    ],
    buttons: [{
        text: 'Reset',
        handler: function(){
            clearapprovalhargapenjualanbazar();
        }
    }]
});

function clearapprovalhargapenjualanbazar(){
    Ext.getCmp('approvalhpbazar').getForm().reset();
    strapprovalhargapenjualanbazar.removeAll();
}
</script>
