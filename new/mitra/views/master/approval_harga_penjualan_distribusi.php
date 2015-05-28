<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
/*START TWIN NO BUKTI FILTER*/

var strcbahjd_nobukti_filter = new Ext.data.ArrayStore({
    fields: ['no_bukti_filter'],
    data : []
});

var strgridahjd_nobukti_filter = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: ['no_bukti_filter','keterangan','created_by','nama_supplier'],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("approval_harga_penjualan_distribusi/get_no_bukti_filter") ?>',
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

var searchgridahjd_nobukti_filter = new Ext.app.SearchField({
    store: strgridahjd_nobukti_filter,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridahjd_nobukti_filter'
});


var gridahjd_nobukti_filter = new Ext.grid.GridPanel({
    store: strgridahjd_nobukti_filter,
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
        items: [searchgridahjd_nobukti_filter]
    }),
    listeners: {
        'rowdblclick': function(){
            var sm = this.getSelectionModel();
            var sel = sm.getSelections();
            if (sel.length > 0) {
                Ext.getCmp('ahjd_user').setValue(sel[0].get('created_by'));
                Ext.getCmp('id_cbahjd_nobukti_filter').setValue(sel[0].get('no_bukti_filter'));
                gridapprovalhargapenjualandist.store.load({
                    params:{
                        no_bukti: Ext.getCmp('id_cbahjd_nobukti_filter').getValue()
                    }
                });
                menuahjd_nobukti_filter.hide();
            }
        }
    }
});

var menuahjd_nobukti_filter = new Ext.menu.Menu();
menuahjd_nobukti_filter.add(new Ext.Panel({
    title: 'Pilih No Bukti',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 500,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridahjd_nobukti_filter],
    buttons: [{
        text: 'Close',
        handler: function(){
            menuahjd_nobukti_filter.hide();
        }
    }]
}));

Ext.ux.TwinComboahjd_nobukti_filter = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function(){
        //load store grid
        strgridahjd_nobukti_filter.load();
        menuahjd_nobukti_filter.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
    },
    validationEvent: false,
    validateOnBlur: false,
    trigger1Class: 'x-form-clear-trigger',
    trigger2Class: 'x-form-search-trigger',
    hideTrigger1: true
});

menuahjd_nobukti_filter.on('hide', function(){
    var sf = Ext.getCmp('id_searchgridahjd_nobukti_filter').getValue();
    if( sf !== ''){
        Ext.getCmp('id_searchgridahjd_nobukti_filter').setValue('');
        searchgridahjd_nobukti_filter.onTrigger2Click();
    }
});

var cbahjd_nobukti_filter = new Ext.ux.TwinComboahjd_nobukti_filter({
    fieldLabel: 'No Bukti Filter',
    id: 'id_cbahjd_nobukti_filter',
    store: strcbahjd_nobukti_filter,
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

var headerapprovalhargapenjualandist = {

    layout: 'form',
    border: false,
    labelWidth: 100,
    width: 500,
    buttonAlign: 'left',
    defaults: { labelSeparator: ''},
    items: [cbahjd_nobukti_filter,{
        xtype: 'datefield',
        fieldLabel: 'Tanggal',
        name: 'tanggal',
        // allowBlank:false,   
        format:'d-m-Y',
        editable:false,
        id: 'ahjd_tanggal',
        anchor: '90%',
        value: new Date().format('m/d/Y')
    },{
        xtype: 'textfield',
        fieldLabel: 'Request By',
        name: 'user',
        readOnly:true,
        fieldClass:'readonly-input',
        id: 'ahjd_user',
        anchor: '90%',
        value:''
    }],
    buttons: [{
        text: 'Submit',
        formBind:true,
        handler: function(){
            var validasi = true;
            var kd_produk = '';
            strapprovalhargapenjualandist.each(function(node){
                var tgl_start_diskon = node.data.tgl_start_diskon;
                var tgl_end_diskon = node.data.tgl_end_diskon;
                var kode_produk = node.data.kd_produk;

                if (tgl_end_diskon < tgl_start_diskon){
                    validasi= false;
                    kd_produk = kode_produk;
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
            var detailapprovalhargapenjualandist = new Array();
            strapprovalhargapenjualandist.each(function(node){
                detailapprovalhargapenjualandist.push(node.data);
            });

            var no_bukti = Ext.getCmp('id_cbahjd_nobukti_filter').getValue();
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
                url: '<?= site_url("approval_harga_penjualan_distribusi/approval") ?>',
                method: 'POST',
                params: {
                    detail: Ext.util.JSON.encode(detailapprovalhargapenjualandist),
                    no_bukti: Ext.getCmp('id_cbahjd_nobukti_filter').getValue(),
                    tanggal: Ext.getCmp('ahjd_tanggal').getValue()
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
                        clearapprovalhargapenjualandist();
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
var strapprovalhargapenjualandist = new Ext.data.Store({
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
            {name: 'net_hrg_supplier_dist_inc', allowBlank: false, type: 'int'},
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
            {name: 'rp_jual_toko', allowBlank: false, type: 'int'},
            {name: 'rp_jual_agen', allowBlank: false, type: 'int'},
            {name: 'rp_jual_distribusi', allowBlank: false, type: 'int'},
            {name: 'rp_jual_modern_market', allowBlank: false, type: 'int'},
            {name: 'qty_beli_bonus', allowBlank: false, type: 'int'},
            {name: 'kd_produk_bonus', allowBlank: false, type: 'text'},
            {name: 'qty_bonus', allowBlank: false, type: 'int'},
            {name: 'is_bonus_kelipatan', allowBlank: false, type: 'text'},
            {name: 'qty_agen', allowBlank: false, type: 'int'},
            {name: 'qty_beli_agen', allowBlank: false, type: 'int'},
            {name: 'kd_produk_agen', allowBlank: false, type: 'text'},
            {name: 'qty_bonus', allowBlank: false, type: 'int'},
            {name: 'is_agen_kelipatan', allowBlank: false, type: 'text'},
            {name: 'qty_modern_market', allowBlank: false, type: 'int'},
            {name: 'kd_produk_modern_market', allowBlank: false, type: 'text'},
            {name: 'qty_beli_modern_market', allowBlank: false, type: 'int'},
            {name: 'is_modern_market_kelipatan', allowBlank: false, type: 'text'},
            {name: 'keterangan', allowBlank: false, type: 'text'},
            {name: 'tanggal', allowBlank: false, type: 'text'},
            {name: 'status', allowBlank: false, type: 'text'},
            {name: 'tgl_start_diskon', allowBlank: false, type: 'text'},
            {name: 'tgl_end_diskon', allowBlank: false, type: 'text'}
        ],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("approval_harga_penjualan_distribusi/search_produk_by_no_bukti") ?>',
        method: 'POST'
    }),
    writer: new Ext.data.JsonWriter(
        {
            encode: true,
            writeAllFields: true
        })
});

strapprovalhargapenjualandist.on('update',function(){
    if(Ext.getCmp('eahjd_het_cogs').getValue() === 0){

        if(Ext.getCmp('eahjd_rp_cogs').getValue() > 0){
            if(Ext.getCmp('eahjd_rp_jual_toko_net').getValue() < Ext.getCmp('eahjd_rp_cogs').getValue()){
                Ext.getCmp('eahjd_rp_jual_toko_net').setValue('0');
                Ext.Msg.show({
                    title: 'Error',
                    msg: 'Net Price Jual tidak boleh lebih kecil dari HET COGS (inc PPN)',
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK,
                    fn: function(btn){
                        Ext.getCmp('eahjd_rp_jual_toko_net').focus();
                    }
                });
                Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
            }
        }else if(Ext.getCmp('eahjd_rp_jual_toko').getValue() < Ext.getCmp('eahjd_rp_het_harga_beli').getValue()){
            Ext.getCmp('eahjd_rp_jual_toko').setValue('0');
            Ext.Msg.show({
                title: 'Error',
                msg: 'Net Price Jual tidak boleh lebih kecil dari HET Net Price Beli (inc PPN)',
                modal: true,
                icon: Ext.Msg.ERROR,
                buttons: Ext.Msg.OK,
                fn: function(btn){
                    Ext.getCmp('eahjd_rp_jual_toko').focus();
                }
            });
            Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
        }
    }else{
        if(Ext.getCmp('eahjd_rp_jual_toko').getValue() < Ext.getCmp('eahjd_het_cogs').getValue()){
            Ext.getCmp('eahjd_rp_jual_toko').setValue('0');
            Ext.Msg.show({
                title: 'Error',
                msg: 'Net Price Jual tidak boleh lebih kecil dari HET COGS (inc PPN)',
                modal: true,
                icon: Ext.Msg.ERROR,
                buttons: Ext.Msg.OK,
                fn: function(btn){
                    Ext.getCmp('eahjd_rp_jual_toko').focus();
                }
            });
            Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
        }else if(Ext.getCmp('eahjd_rp_jual_toko_net').getValue() < Ext.getCmp('eahjd_rp_het_harga_beli').getValue()){
            Ext.getCmp('eahjd_rp_jual_toko_net').setValue('0');
            Ext.Msg.show({
                title: 'Error',
                msg: 'Net Price Jual tidak boleh lebih kecil dari HET Net Price Beli (inc PPN)',
                modal: true,
                icon: Ext.Msg.ERROR,
                buttons: Ext.Msg.OK,
                fn: function(btn){
                    Ext.getCmp('eahjd_rp_jual_toko_net').focus();
                }
            });
            Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
        }

    }
});

function HETChangeAHJ(){
    var hrg_beli = Ext.getCmp('eahjd_hrg_beli_satuan').getValue();
    var cogs = Ext.getCmp('eahjd_rp_cogs').getValue();
    var ongkos = Ext.getCmp('eahjd_rp_ongkos_kirim').getValue();
    var margin_op = Ext.getCmp('eahjd_margin_op').getValue();
    var margin = Ext.getCmp('eahjd_margin').getValue();
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
    var ongkos_cogs = Ext.getCmp('eahjd_rp_ongkos_kirim').getValue();
    var HETCOGS = (cogs + margin_rp + ongkos_cogs) * 1.1;
    if(cogs === 0){
        HETCOGS = 0;
    }
    Ext.getCmp('eahjd_rp_het_harga_beli').setValue(HET);
    Ext.getCmp('eahjd_het_cogs').setValue(HETCOGS);
    Ext.getCmp('eahjd_pct_margin').setValue(margin_pct);
    Ext.getCmp('eahjd_rp_margin').setValue(margin_rp);
    EditedAHJDis();
};

function EditedAHJDis(){
    Ext.getCmp('eahjd_edited').setValue('Y');
};

function HitungNetPJualAHJDis(){
    EditedAHJDis();
    var total_disk = 0;
    var rp_jual_toko = Ext.getCmp('eahjd_rp_jual_toko').getValue();
    var disk_kons1_op = Ext.getCmp('eahjd_disk_kons1_op').getValue();
    var disk_kons1 = Ext.getCmp('eahjd_disk_kons1').getValue();
    if (disk_kons1_op === '%'){
        // disk_kons1 = (disk_kons1*rp_jual_toko)/100;
        total_disk = rp_jual_toko-(rp_jual_toko*(disk_kons1/100));
    }else{
        total_disk = rp_jual_toko-disk_kons1;
    }

    var disk_kons2_op = Ext.getCmp('eahjd_disk_kons2_op').getValue();
    var disk_kons2 = Ext.getCmp('eahjd_disk_kons2').getValue();
    if (disk_kons2_op === '%'){
        // disk_kons2 = (disk_kons2*disk_kons1)/100;
        total_disk =  total_disk-(total_disk*(disk_kons2/100));
    }else{
        total_disk = total_disk-disk_kons2;
    }

    var disk_kons3_op = Ext.getCmp('eahjd_disk_kons3_op').getValue();
    var disk_kons3 = Ext.getCmp('eahjd_disk_kons3').getValue();
    if (disk_kons3_op === '%'){
        // disk_kons3 = (disk_kons3*disk_kons2)/100;
        total_disk = total_disk-(total_disk*(disk_kons3/100));
    }else{
        total_disk = total_disk-disk_kons3;
    }

    var disk_kons4_op = Ext.getCmp('eahjd_disk_kons4_op').getValue();
    var disk_kons4 = Ext.getCmp('eahjd_disk_kons4').getValue();
    if (disk_kons4_op === '%'){
        // disk_kons4 = (disk_kons4*disk_kons3)/100;
        total_disk = total_disk-(total_disk*(disk_kons4/100));
    }else{
        total_disk = total_disk-disk_kons4;
    }

    var total_disk = total_disk-Ext.getCmp('eahjd_disk_kons5').getValue();

    var net_jual_kons = total_disk;
    Ext.getCmp('eahjd_rp_jual_toko_net').setValue(net_jual_kons);


    var rp_jual_agen = Ext.getCmp('eahjd_rp_jual_agen').getValue();
    var disk_member1_op = Ext.getCmp('eahjd_disk_member1_op').getValue();
    var disk_member1 = Ext.getCmp('eahjd_disk_member1').getValue();
    if (disk_member1_op === '%'){
        // disk_member1 = (disk_member1*rp_jual_toko)/100;
        total_disk = rp_jual_agen-(rp_jual_toko*(disk_member1/100));
    }else{
        total_disk = rp_jual_agen-disk_member1;
    }

    var disk_member2_op = Ext.getCmp('eahjd_disk_member2_op').getValue();
    var disk_member2 = Ext.getCmp('eahjd_disk_member2').getValue();
    if (disk_member2_op === '%'){
        // disk_member2 = (disk_member2*disk_member1)/100;
        total_disk = total_disk-(total_disk*(disk_member2/100));
    }else{
        total_disk = total_disk-disk_member2;
    }

    var disk_member3_op = Ext.getCmp('eahjd_disk_member3_op').getValue();
    var disk_member3 = Ext.getCmp('eahjd_disk_member3').getValue();
    if (disk_member3_op === '%'){
        // disk_member3 = (disk_member3*disk_member2)/100;
        total_disk = total_disk-(total_disk*(disk_member3/100));
    }else{
        total_disk = total_disk-disk_member3;
    }

    var disk_member4_op = Ext.getCmp('eahjd_disk_member4_op').getValue();
    var disk_member4 = Ext.getCmp('eahjd_disk_member4').getValue();
    if (disk_member4_op === '%'){
        // disk_member4 = (disk_member4*disk_member3)/100;
        total_disk = total_disk-(total_disk*(disk_member4/100));
    }else{
        total_disk = total_disk-disk_member4;
    }

    var total_disk = total_disk - Ext.getCmp('eahjd_disk_amt_member5').getValue();

    var net_price_memb = total_disk;
    Ext.getCmp('eahjd_net_price_jual_member').setValue(net_price_memb);
    
    //Ext.getCmp('ehjd_rp_jual_modern_market').setValue(rp_jual_toko);
    var rp_jual_modern_market = Ext.getCmp('eahjd_rp_jual_modern_market').getValue();
    var disk_modern_market1_op = Ext.getCmp('eahjd_disk_modern_market1_op').getValue();
    var disk_modern_market1 = Ext.getCmp('eahjd_disk_modern_market1').getValue();
    if (disk_modern_market1_op === '%'){
        // disk_agen1 = (disk_agen1*rp_jual_toko)/100;
        total_disk = rp_jual_modern_market-(rp_jual_modern_market*(disk_modern_market1/100));
    }else{
        total_disk = rp_jual_modern_market-disk_modern_market1;
    }

    var disk_modern_market2_op = Ext.getCmp('eahjd_disk_modern_market2_op').getValue();
    var disk_modern_market2 = Ext.getCmp('eahjd_disk_modern_market2').getValue();
    if (disk_modern_market2_op === '%'){
        // disk_agen2 = (disk_agen2*disk_agen1)/100;
        total_disk = total_disk-(total_disk*(disk_modern_market2/100));
    }else{
        total_disk = total_disk-disk_modern_market2;
    }

    var disk_modern_market3_op = Ext.getCmp('eahjd_disk_modern_market3_op').getValue();
    var disk_modern_market3 = Ext.getCmp('eahjd_disk_modern_market3').getValue();
    if (disk_modern_market3_op === '%'){
        // disk_agen3 = (disk_agen3*disk_agen2)/100;
        total_disk = total_disk-(total_disk*(disk_modern_market3/100));
    }else{
        total_disk = total_disk-disk_modern_market3;
    }

    var disk_modern_market4_op = Ext.getCmp('eahjd_disk_modern_market4_op').getValue();
    var disk_modern_market4 = Ext.getCmp('eahjd_disk_modern_market4').getValue();
    if (disk_modern_market4_op === '%'){
        // disk_agen4 = (disk_agen4*disk_agen3)/100;
        total_disk = total_disk-(total_disk*(disk_modern_market4/100));
    }else{
        total_disk = total_disk-disk_modern_market4;
    }

    var total_disk = total_disk - Ext.getCmp('eahjd_disk_amt_modern_market5').getValue();

    var net_price_memb = total_disk;
    Ext.getCmp('eahjd_net_price_jual_modern_market').setValue(net_price_memb);
}

var editorapprovalhargapenjualandist = new Ext.ux.grid.RowEditor({
    saveText: 'Update'
});

var gridapprovalhargapenjualandist = new Ext.grid.GridPanel({
    store: strapprovalhargapenjualandist,
    stripeRows: true,
    height: 350,
    frame: true,
    border:true,
    plugins: [editorapprovalhargapenjualandist],
    columns: [ {
        dataIndex: 'kd_diskon_sales',
        hidden: true
    },{
        dataIndex: 'pct_margin',
        hidden: true,
        editor: new Ext.form.TextField({
            readOnly: true,
            id: 'eahjd_pct_margin'
        })
    },{
        dataIndex: 'rp_margin',
        hidden: true,
        editor: new Ext.form.TextField({
            readOnly: true,
            id: 'eahjd_rp_margin'
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
            id:           	'eahjd_status',
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
            id: 'eahjd_edited'
        })
    },{
        header: 'Tanggal',
        dataIndex: 'tanggal',
        width: 100,
        sortable: true,
        editor: new Ext.form.TextField({
            readOnly: true,
            fieldClass: 'readonly-input',
            id: 'eahjd_tanggal'
        })
    },{
        header: 'Kode Barang',
        dataIndex: 'kd_produk',
        width: 100,
        sortable: true,
        editor: new Ext.form.TextField({
            readOnly: true,
            fieldClass: 'readonly-input',
            id: 'eahjd_kd_produk'
        })
    },{
        header: 'Kode Brg Lama',
        dataIndex: 'kd_produk_lama',
        width: 100,
        sortable: true,
        editor: new Ext.form.TextField({
            readOnly: true,
            fieldClass: 'readonly-input',
            id: 'eahjd_kd_produk_lama'
        })
    },{
        header: 'Nama Barang',
        dataIndex: 'nama_produk',
        width: 300,
        sortable: true,
        editor: new Ext.form.TextField({
            readOnly: true,
            fieldClass: 'readonly-input',
            id: 'eahjd_nama_produk'
        })
    },{
        header: 'Satuan',
        dataIndex: 'nm_satuan',
        width: 80,
        editor: new Ext.form.TextField({
            readOnly: true,
            id: 'eahjd_satuan',
            fieldClass: 'readonly-input'
        })
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Net Price Beli (Inc.PPN)',
        dataIndex: 'net_hrg_supplier_dist_inc',
        width: 150,
        editor: {
            xtype: 'numberfield',
            id: 'eahjd_hrg_beli_satuan',
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
            id: 'eahjd_rp_cogs',
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
            id: 'eahjd_rp_ongkos_kirim',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        HETChangeAHJ();
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
            id:           	'eahjd_margin_op',
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
                    Ext.getCmp('eahjd_margin').setValue(0);
                    HETChangeAHJ();
                },
                select:function(){
                    HETChangeAHJ();
                    Ext.getCmp('eahjd_margin').setMaxValue(Number.MAX_VALUE);
                    if (this.getValue() === 'persen')
                        Ext.getCmp('eahjd_margin').maxValue = 100;
                    else
                        Ext.getCmp('eahjd_margin').maxLength = 11;
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
            id: 'eahjd_margin',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        HETChangeAHJ();
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
            id: 'eahjd_rp_het_harga_beli',
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
            id: 'eahjd_het_cogs',
            readOnly: true
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Harga Jual Toko',
        dataIndex: 'rp_jual_toko',
        width: 180,
        editor: {
            xtype: 'numberfield',
            id: 'eahjd_rp_jual_toko',
            listeners:{
                'change': function() {
                    if(Ext.getCmp('eahjd_rp_cogs').getValue() > 0){
                        if(this.getValue() < Ext.getCmp('eahjd_rp_cogs').getValue()){
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
                        if(this.getValue() < Ext.getCmp('eahjd_rp_het_harga_beli').getValue()){
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
                        HitungNetPJualAHJDis();
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
            id:           	'eahjd_disk_kons1_op',
            mode:           'local',
            name:           'disk_kons1_op',
            value:          '%',
            width:			50,
            editable:       false,
            hiddenName:     'disk_kons1_op',
            valueField:     'name',
            displayField:   'name',
            triggerAction:  'all',
            forceSelection: true,
            listeners:{
                'expand':function(){
                    Ext.getCmp('eahjd_disk_kons1').setValue(0);
                },
                select:function(){
                    Ext.getCmp('eahjd_disk_kons1').setMaxValue(Number.MAX_VALUE);
                    if (this.getValue() === 'persen')
                        Ext.getCmp('eahjd_disk_kons1').maxValue = 100;
                    else
                        Ext.getCmp('eahjd_disk_kons1').maxLength = 11;
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
            name : 'disk_kons1',
            id: 'eahjd_disk_kons1',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        HitungNetPJualAHJDis();
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
            id:           	'eahjd_disk_kons2_op',
            mode:           'local',
            name:           'disk_kons2_op',
            value:          '%',
            width:			50,
            editable:       false,
            hiddenName:     'disk_kons2_op',
            valueField:     'name',
            displayField:   'name',
            triggerAction:  'all',
            forceSelection: true,
            listeners:{
                'expand':function(){
                    Ext.getCmp('eahjd_disk_kons2').setValue(0);
                },
                select:function(){
                    Ext.getCmp('eahjd_disk_kons2').setMaxValue(Number.MAX_VALUE);
                    if (this.getValue() === 'persen')
                        Ext.getCmp('eahjd_disk_kons2').maxValue = 100;
                    else
                        Ext.getCmp('eahjd_disk_kons2').maxLength = 11;
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
            name : 'disk_kons2',
            id: 'eahjd_disk_kons2',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // EditedAHJ();
                        HitungNetPJualAHJDis();
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
            id:           	'eahjd_disk_kons3_op',
            mode:           'local',
            name:           'disk_kons3_op',
            value:          '%',
            width:			50,
            editable:       false,
            hiddenName:     'disk_kons3_op',
            valueField:     'name',
            displayField:   'name',
            triggerAction:  'all',
            forceSelection: true,
            listeners:{
                'expand':function(){
                    Ext.getCmp('eahjd_disk_kons3').setValue(0);
                },
                select:function(){
                    Ext.getCmp('eahjd_disk_kons3').setMaxValue(Number.MAX_VALUE);
                    if (this.getValue() === 'persen')
                        Ext.getCmp('eahjd_disk_kons3').maxValue = 100;
                    else
                        Ext.getCmp('eahjd_disk_kons3').maxLength = 11;
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
            name : 'disk_kons3',
            id: 'eahjd_disk_kons3',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // EditedAHJ();
                        HitungNetPJualAHJDis();
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
            id:           	'eahjd_disk_kons4_op',
            mode:           'local',
            name:           'disk_kons4_op',
            value:          '%',
            width:			50,
            editable:       false,
            hiddenName:     'disk_kons4_op',
            valueField:     'name',
            displayField:   'name',
            triggerAction:  'all',
            forceSelection: true,
            listeners:{
                'expand':function(){
                    Ext.getCmp('eahjd_disk_kons4').setValue(0);
                },
                select:function(){
                    Ext.getCmp('eahjd_disk_kons4').setMaxValue(Number.MAX_VALUE);
                    if (this.getValue() === 'persen')
                        Ext.getCmp('eahjd_disk_kons4').maxValue = 100;
                    else
                        Ext.getCmp('eahjd_disk_kons4').maxLength = 11;
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
            name : 'disk_kons4',
            id: 'eahjd_disk_kons4',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // EditedAHJ();
                        HitungNetPJualAHJDis();
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
            name : 'disk_kons5',
            id: 'eahjd_disk_kons5',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // EditedAHJ();
                        HitungNetPJualAHJDis();
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
            id: 'eahjd_rp_jual_toko_net',
            readOnly: true,
            fieldClass: 'readonly-input',
            listeners:{
                'change': function() {
                    if(Ext.getCmp('eahjd_rp_cogs').getValue() > 0){
                        if(Ext.getCmp('eahjd_rp_jual_toko_net').getValue() < Ext.getCmp('eahjd_rp_cogs').getValue()){
                            Ext.getCmp('eahjd_rp_jual_toko_net').setValue('0');
                            Ext.Msg.show({
                                title: 'Error',
                                msg: 'Net Price Jual tidak boleh lebih kecil dari HET COGS (inc PPN)',
                                modal: true,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OK,
                                fn: function(btn){
                                    Ext.getCmp('eahjd_rp_jual_toko_net').focus();
                                }
                            });
                            Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                        }
                    }else{
                        if(Ext.getCmp('eahjd_rp_jual_toko_net').getValue() < Ext.getCmp('eahjd_rp_het_harga_beli').getValue()){
                            Ext.getCmp('eahjd_rp_jual_toko_net').setValue('0');
                            Ext.Msg.show({
                                title: 'Error',
                                msg: 'Net Price Jual tidak boleh lebih kecil dari HET Net Price Beli (inc PPN)',
                                modal: true,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OK,
                                fn: function(btn){
                                    Ext.getCmp('eahjd_rp_jual_toko_net').focus();
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
        header: 'Rp Jual Agen',
        dataIndex: 'rp_jual_agen',
        width: 180,
        editor: {
            xtype: 'numberfield',
            id: 'eahjd_rp_jual_agen',
            listeners:{
                'change': function() {
                    if(Ext.getCmp('eahjd_rp_cogs').getValue() > 0){
                        if(this.getValue() < Ext.getCmp('eahjd_rp_cogs').getValue()){
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
                        if(this.getValue() < Ext.getCmp('eahjd_rp_het_harga_beli').getValue()){
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
                        HitungNetPJualAHJDis();
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
            id:           	'eahjd_disk_member1_op',
            mode:           'local',
            name:           'disk_member1_op',
            value:          '%',
            width:			50,
            editable:       false,
            hiddenName:     'disk_member1_op',
            valueField:     'name',
            displayField:   'name',
            triggerAction:  'all',
            forceSelection: true,
            listeners:{
                'expand':function(){
                    Ext.getCmp('eahjd_disk_member1').setValue(0);
                },
                select:function(){
                    Ext.getCmp('eahjd_disk_member1').setMaxValue(Number.MAX_VALUE);
                    if (this.getValue() === 'persen')
                        Ext.getCmp('eahjd_disk_member1').maxValue = 100;
                    else
                        Ext.getCmp('eahjd_disk_member1').maxLength = 11;
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
            name : 'disk_member1',
            id: 'eahjd_disk_member1',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // EditedAHJ();
                        HitungNetPJualAHJDis();
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
            id:           	'eahjd_disk_member2_op',
            mode:           'local',
            name:           'disk_member2_op',
            value:          '%',
            width:			50,
            editable:       false,
            hiddenName:     'disk_member2_op',
            valueField:     'name',
            displayField:   'name',
            triggerAction:  'all',
            forceSelection: true,
            listeners:{
                'expand':function(){
                    Ext.getCmp('eahjd_disk_member2').setValue(0);
                },
                select:function(){
                    Ext.getCmp('eahjd_disk_member2').setMaxValue(Number.MAX_VALUE);
                    if (this.getValue() === 'persen')
                        Ext.getCmp('eahjd_disk_member2').maxValue = 100;
                    else
                        Ext.getCmp('eahjd_disk_member2').maxLength = 11;
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
            name : 'disk_member2',
            id: 'eahjd_disk_member2',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // EditedAHJ();
                        HitungNetPJualAHJDis();
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
            id:           	'eahjd_disk_member3_op',
            mode:           'local',
            name:           'disk_member3_op',
            value:          '%',
            width:			50,
            editable:       false,
            hiddenName:     'disk_member3_op',
            valueField:     'name',
            displayField:   'name',
            triggerAction:  'all',
            forceSelection: true,
            listeners:{
                'expand':function(){
                    Ext.getCmp('eahjd_disk_member3').setValue(0);
                },
                select:function(){
                    Ext.getCmp('eahjd_disk_member3').setMaxValue(Number.MAX_VALUE);
                    if (this.getValue() === 'persen')
                        Ext.getCmp('eahjd_disk_member3').maxValue = 100;
                    else
                        Ext.getCmp('eahjd_disk_member3').maxLength = 11;
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
            name : 'disk_member3',
            id: 'eahjd_disk_member3',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // EditedAHJ();
                        HitungNetPJualAHJDis();
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
            id:           	'eahjd_disk_member4_op',
            mode:           'local',
            name:           'disk_member4_op',
            value:          '%',
            width:			50,
            editable:       false,
            hiddenName:     'disk_member4_op',
            valueField:     'name',
            displayField:   'name',
            triggerAction:  'all',
            forceSelection: true,
            listeners:{
                'expand':function(){
                    Ext.getCmp('eahjd_disk_member4').setValue(0);
                },
                select:function(){
                    Ext.getCmp('eahjd_disk_member4').setMaxValue(Number.MAX_VALUE);
                    if (this.getValue() === 'persen')
                        Ext.getCmp('eahjd_disk_member4').maxValue = 100;
                    else
                        Ext.getCmp('eahjd_disk_member4').maxLength = 11;
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
            name : 'disk_member4',
            id: 'eahjd_disk_member4',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // EditedAHJ();
                        HitungNetPJualAHJDis();
                    }, c);
                }
            }
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Diskon agen 5',
        dataIndex: 'disk_amt_agen5',
        width: 150,
        editor: {
            xtype: 'numberfield',
            msgTarget: 'under',
            flex:1,
            width:115,
            name : 'disk_amt_member5',
            id: 'eahjd_disk_amt_member5',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // EditedAHJ();
                        HitungNetPJualAHJDis();
                    }, c);
                }
            }
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Net Price Jual agen',
        dataIndex: 'rp_jual_agen_net',
        width: 180,
        editor: {
            xtype: 'numberfield',
            id: 'eahjd_net_price_jual_member',
            readOnly: true,
            fieldClass: 'readonly-input',
            listeners:{
                'change': function() {
                    if(Ext.getCmp('eahjd_rp_cogs').getValue() > 0){
                        if(this.getValue() < Ext.getCmp('eahjd_rp_cogs').getValue()){
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
                        if(this.getValue() < Ext.getCmp('eahjd_rp_het_harga_beli').getValue()){
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
                    }, c);
                }
            }
        }
    },{
        xtype: 'numbercolumn',
        align: 'right',
        format: '0,0',
        header: 'Rp Jual Modern Market',
        dataIndex: 'rp_jual_modern_market',
        width: 180,
        editor: {
            xtype: 'numberfield',
            id: 'eahjd_rp_jual_modern_market',
            listeners:{
                'change': function() {
                    if(Ext.getCmp('eahjd_rp_cogs').getValue() > 0){
                        if(this.getValue() < Ext.getCmp('eahjd_rp_cogs').getValue()){
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
                        if(this.getValue() < Ext.getCmp('eahjd_rp_het_harga_beli').getValue()){
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
                        HitungNetPJualAHJDis();
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
            id:           	'eahjd_disk_modern_market1_op',
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
                    Ext.getCmp('eahjd_disk_modern_market1').setValue(0);
                },
                select:function(){
                    Ext.getCmp('eahjd_disk_modern_market1').setMaxValue(Number.MAX_VALUE);
                    if (this.getValue() === 'persen')
                        Ext.getCmp('eahjd_disk_modern_market1').maxValue = 100;
                    else
                        Ext.getCmp('eahjd_disk_modern_market1').maxLength = 11;
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
            id: 'eahjd_disk_modern_market1',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // EditedAHJ();
                        HitungNetPJualAHJDis();
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
            id:           	'eahjd_disk_modern_market2_op',
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
                    Ext.getCmp('eahjd_disk_modern_market2').setValue(0);
                },
                select:function(){
                    Ext.getCmp('eahjd_disk_modern_market2').setMaxValue(Number.MAX_VALUE);
                    if (this.getValue() === 'persen')
                        Ext.getCmp('eahjd_disk_modern_market2').maxValue = 100;
                    else
                        Ext.getCmp('eahjd_disk_modern_market2').maxLength = 11;
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
            id: 'eahjd_disk_modern_market2',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // EditedAHJ();
                        HitungNetPJualAHJDis();
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
            id:           	'eahjd_disk_modern_market3_op',
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
                    Ext.getCmp('eahjd_disk_modern_market3').setValue(0);
                },
                select:function(){
                    Ext.getCmp('eahjd_disk_modern_market3').setMaxValue(Number.MAX_VALUE);
                    if (this.getValue() === 'persen')
                        Ext.getCmp('eahjd_disk_modern_market3').maxValue = 100;
                    else
                        Ext.getCmp('eahjd_disk_modern_market3').maxLength = 11;
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
            id: 'eahjd_disk_modern_market3',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // EditedAHJ();
                        HitungNetPJualAHJDis();
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
            id:           	'eahjd_disk_modern_market4_op',
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
                    Ext.getCmp('eahjd_disk_modern_market4').setValue(0);
                },
                select:function(){
                    Ext.getCmp('eahjd_disk_modern_market4').setMaxValue(Number.MAX_VALUE);
                    if (this.getValue() === 'persen')
                        Ext.getCmp('eahjd_disk_modern_market4').maxValue = 100;
                    else
                        Ext.getCmp('eahjd_disk_modern_market4').maxLength = 11;
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
            id: 'eahjd_disk_modern_market4',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // EditedAHJ();
                        HitungNetPJualAHJDis();
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
            id: 'eahjd_disk_amt_modern_market5',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {
                        // EditedAHJ();
                        HitungNetPJualAHJDis();
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
            id: 'eahjd_net_price_jual_modern_market',
            readOnly: true,
            fieldClass: 'readonly-input',
            listeners:{
                'change': function() {
                    if(Ext.getCmp('eahjd_rp_cogs').getValue() > 0){
                        if(this.getValue() < Ext.getCmp('eahjd_rp_cogs').getValue()){
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
                        if(this.getValue() < Ext.getCmp('eahjd_rp_het_harga_beli').getValue()){
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
                    }, c);
                }
            }
        }
    },{
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
            id: 'eahjd_qty_beli_bonus',
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
        header: 'Kd Produk |Toko|',
        dataIndex: 'kd_produk_bonus',
        width: 150,
        editor: new Ext.ux.TwinComboHj({
            id: 'eahjd_kd_produk_bonus',
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
        header: 'Qty Bonus |Toko|',
        dataIndex: 'qty_bonus',
        width: 150,
        editor: {
            xtype: 'numberfield',
            msgTarget: 'under',
            flex:1,
            width:115,
            name : 'qty_bonus',
            id: 'eahjd_qty_bonus',
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
            id:           	'eahjd_is_bonus_kelipatan',
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
            name : 'qty_beli_member',
            id: 'eahjd_qty_beli_member',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {}, c);
                }
            }
        }
    },{
        header: 'Kd Produk |Agen|',
        dataIndex: 'kd_produk_agen',
        width: 150,
        editor: new Ext.ux.TwinComboHj({
            id: 'eahjd_kd_produk_member',
            store: strcbkdprodukhj,
            valueField: 'kd_produk_member',
            displayField: 'kd_produk_member',
            typeAhead: true,
            editable: false,
            hiddenName: 'kd_produk_member',
            emptyText: 'Pilih Kode Produk',
            listeners:{
                'expand': function(){
                    strcbkdprodukhj.load();
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
            name : 'qty_member',
            id: 'eahjd_qty_member',
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
        header: 'Kelipatan ? |Agen|',
        dataIndex: 'is_agen_kelipatan',
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
            id:           	'eahjd_is_member_kelipatan',
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
                        // EditedAHJ();
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
            id: 'eahjd_qty_beli_modern_market',
            style: 'text-align:right;',
            listeners:{
                'render': function(c) {
                    c.getEl().on('keyup', function() {}, c);
                }
            }
        }
    },{
        header: 'Kd Produk |Modern Market|',
        dataIndex: 'kd_produk_modern_market',
        width: 150,
        editor: new Ext.ux.TwinComboHj({
            id: 'eahjd_kd_produk_modern_market',
            store: strcbkdprodukhj,
            valueField: 'kd_produk_modern_market',
            displayField: 'kd_produk_modern_market',
            typeAhead: true,
            editable: false,
            hiddenName: 'kd_produk_modern_market',
            emptyText: 'Pilih Kode Produk',
            listeners:{
                'expand': function(){
                    strcbkdprodukhj.load();
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
            name : 'qty_modern_market',
            id: 'eahjd_qty_modern_market',
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
            id:           	'eahjd_is_modern_market_kelipatan',
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
                        // EditedAHJ();
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
                id: 'eahpd_tgl_start_diskon',
                format: 'd/m/Y',
                listeners:{			
                    'change': function() {
                          Ext.getCmp('eahjd_edited').setValue('Y');
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
                id: 'eahpd_tgl_end_diskon',
                format: 'd/m/Y',
                minValue: (new Date()).clearTime(),
                listeners:{			
                    'change': function() {
                          Ext.getCmp('eahjd_edited').setValue('Y');
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
            id: 'eahjd_keterangan'
        })
    }]
});

/***/
var approvalhargapenjualandist = new Ext.FormPanel({
    id: 'approvalhpd',
    buttonAlign: 'left',
    border: false,
    frame: true,
    monitorValid: true,
    labelWidth: 130,
    items: [{
        bodyStyle: {
            margin: '0px 0px 15px 0px'
        },
        items: [headerapprovalhargapenjualandist,gridapprovalhargapenjualandist]
    }
    ],
    buttons: [{
        text: 'Reset',
        handler: function(){
            clearapprovalhargapenjualandist();
        }
    }]
});

function clearapprovalhargapenjualandist(){
    Ext.getCmp('approvalhpd').getForm().reset();
    strapprovalhargapenjualandist.removeAll();
}
</script>
