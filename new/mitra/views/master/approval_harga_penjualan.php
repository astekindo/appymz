<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    /*START TWIN NO BUKTI FILTER*/

    var strcbahjnobuktifilter = new Ext.data.ArrayStore({
        fields: ['no_bukti_filter'],
        data: []
    });

    var strgridahjnobuktifilter = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_bukti_filter', 'keterangan', 'created_by', 'nama_supplier'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("approval_harga_penjualan/get_no_bukti_filter") ?>',
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

    var searchgridahjnobuktifilter = new Ext.app.SearchField({
        store: strgridahjnobuktifilter,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridahjnobuktifilter'
    });


    var gridahjnobuktifilter = new Ext.grid.GridPanel({
        store: strgridahjnobuktifilter,
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
                width: 80,
                sortable: true,
            }, {
                header: 'Ket Perubahan',
                dataIndex: 'keterangan',
                width: 200,
                sortable: true

            }],
        tbar: new Ext.Toolbar({
            items: [searchgridahjnobuktifilter]
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('hp_user').setValue(sel[0].get('created_by'));
                    Ext.getCmp('id_cbahjnobuktifilter').setValue(sel[0].get('no_bukti_filter'));
                    gridapprovalhargapenjualan.store.load({
                        params: {
                            no_bukti: Ext.getCmp('id_cbahjnobuktifilter').getValue(),
                        }
                    });
                    menuahjnobuktifilter.hide();
                }
            }
        }
    });

    var menuahjnobuktifilter = new Ext.menu.Menu();
    menuahjnobuktifilter.add(new Ext.Panel({
        title: 'Pilih No Bukti',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 500,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridahjnobuktifilter],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menuahjnobuktifilter.hide();
                }
            }]
    }));

    Ext.ux.TwinComboahjnobuktifilter = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridahjnobuktifilter.load();
            menuahjnobuktifilter.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menuahjnobuktifilter.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridahjnobuktifilter').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgridahjnobuktifilter').setValue('');
            searchgridahjnobuktifilter.onTrigger2Click();
        }
    });

    var cbahjnobuktifilter = new Ext.ux.TwinComboahjnobuktifilter({
        fieldLabel: 'No Bukti Filter',
        id: 'id_cbahjnobuktifilter',
        store: strcbahjnobuktifilter,
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

    var headerapprovalhargapenjualan = {
        layout: 'form',
        border: false,
        labelWidth: 100,
        width: 500,
        buttonAlign: 'left',
        defaults: {labelSeparator: ''},
        items: [cbahjnobuktifilter, {
                xtype: 'datefield',
                fieldLabel: 'Tanggal',
                name: 'tanggal',
                // allowBlank:false,   
                format: 'd-m-Y',
                editable: false,
                id: 'ahj_tanggal',
                anchor: '90%',
                value: new Date().format('m/d/Y')
            }, {
                xtype: 'textfield',
                fieldLabel: 'Request By',
                name: 'user',
                readOnly: true,
                fieldClass: 'readonly-input',
                id: 'hp_user',
                anchor: '90%',
                value: ''
            }],
        buttons: [{
                text: 'Submit',
                formBind: true,
                handler: function() {
                        var validasi = true;
                        var kd_produk = '';
                        strapprovalhargapenjualan.each(function(node){
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
                    var detailapprovalhargapenjualan = new Array();
                    strapprovalhargapenjualan.each(function(node) {
                        detailapprovalhargapenjualan.push(node.data);
                    });

                    var no_bukti = Ext.getCmp('id_cbahjnobuktifilter').getValue();
                    if (no_bukti === '') {
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
                        url: '<?= site_url("approval_harga_penjualan/approval") ?>',
                        method: 'POST',
                        params: {
                            detail: Ext.util.JSON.encode(detailapprovalhargapenjualan),
                            no_bukti: Ext.getCmp('id_cbahjnobuktifilter').getValue(),
                            tanggal: Ext.getCmp('ahj_tanggal').getValue()
                        },
                        callback: function(opt, success, responseObj) {
                            var de = Ext.util.JSON.decode(responseObj.responseText);
                            if (de.success === true) {
                                Ext.Msg.show({
                                    title: 'Success',
                                    msg: 'Form submitted successfully',
                                    modal: true,
                                    icon: Ext.Msg.INFO,
                                    buttons: Ext.Msg.OK
                                });
                                clearapprovalhargapenjualan();
                            } else {
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
                        }
                    });
                }
            },
             {
             text: 'Save',
             formBind:true,
             handler: function(){
              var validasi = true;
                        var kd_produk = '';
                        strapprovalhargapenjualan.each(function(node){
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
             var detailapprovalhargapenjualan = new Array();              
                        strapprovalhargapenjualan.each(function(node){
                        detailapprovalhargapenjualan.push(node.data)
             });

             var no_bukti = Ext.getCmp('id_cbahjnobuktifilter').getValue();
             if (no_bukti == ''){					
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
                        url: '<?= site_url("approval_harga_penjualan/update_row") ?>',
                        method: 'POST',
                        params: {
                        detail: Ext.util.JSON.encode(detailapprovalhargapenjualan),
                        no_bukti: Ext.getCmp('id_cbahjnobuktifilter').getValue(),
                        tanggal: Ext.getCmp('ahj_tanggal').getValue(),
             },
             callback:function(opt,success,responseObj){
             var de = Ext.util.JSON.decode(responseObj.responseText);
             if(de.success==true){
                        Ext.Msg.show({
                        title: 'Success',
                        msg: 'Form submitted successfully',
                        modal: true,
                        icon: Ext.Msg.INFO,
                        buttons: Ext.Msg.OK
             });
             clearapprovalhargapenjualan();
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
             });
             }
             }
        ]
    }

    /***/
    var strapprovalhargapenjualan = new Ext.data.Store({
        autoSave: false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_diskon_sales', allowBlank: true, type: 'text'},
                {name: 'koreksi_ke', allowBlank: true, type: 'text'},
                {name: 'koreksi_produk', allowBlank: true, type: 'text'},
                {name: 'kd_produk', allowBlank: false, type: 'text'},
                {name: 'kd_produk_lama', allowBlank: false, type: 'text'},
                {name: 'nama_produk', allowBlank: false, type: 'text'},
                {name: 'nm_satuan', allowBlank: false, type: 'text'},
                {name: 'disk_kons1_op', allowBlank: false, type: 'text'},
                {name: 'disk_kons2_op', allowBlank: false, type: 'text'},
                {name: 'disk_kons3_op', allowBlank: false, type: 'text'},
                {name: 'disk_kons4_op', allowBlank: false, type: 'text'},
                {name: 'disk_member1_op', allowBlank: false, type: 'text'},
                {name: 'disk_member2_op', allowBlank: false, type: 'text'},
                {name: 'disk_member3_op', allowBlank: false, type: 'text'},
                {name: 'disk_member4_op', allowBlank: false, type: 'text'},
                {name: 'disk_kons1', allowBlank: false, type: 'float'},
                {name: 'disk_kons2', allowBlank: false, type: 'float'},
                {name: 'disk_kons3', allowBlank: false, type: 'float'},
                {name: 'disk_kons4', allowBlank: false, type: 'float'},
                {name: 'disk_amt_kons5', allowBlank: false, type: 'int'},
                {name: 'net_price_jual_kons', allowBlank: false, type: 'int'},
                {name: 'disk_member1', allowBlank: false, type: 'float'},
                {name: 'disk_member2', allowBlank: false, type: 'float'},
                {name: 'disk_member3', allowBlank: false, type: 'float'},
                {name: 'disk_member4', allowBlank: false, type: 'float'},
                {name: 'disk_amt_member5', allowBlank: false, type: 'int'},
                {name: 'net_price_jual_member', allowBlank: false, type: 'int'},
                {name: 'hrg_beli_satuan', allowBlank: false, type: 'int'},
                {name: 'rp_cogs', allowBlank: false, type: 'int'},
                {name: 'rp_het_cogs', allowBlank: false, type: 'int'},
                {name: 'hrg_supplier', allowBlank: false, type: 'int'},
                {name: 'net_hrg_supplier_sup_inc', allowBlank: false, type: 'int'},
                {name: 'rp_ongkos_kirim', allowBlank: false, type: 'int'},
                {name: 'margin_op', allowBlank: false, type: 'text'},
                {name: 'margin', allowBlank: false, type: 'int'},
                {name: 'pct_margin', allowBlank: false, type: 'int'},
                {name: 'rp_margin', allowBlank: false, type: 'int'},
                {name: 'rp_het_harga_beli', allowBlank: false, type: 'int'},
                {name: 'rp_jual_supermarket', allowBlank: false, type: 'int'},
                {name: 'rp_jual_distribusi', allowBlank: false, type: 'int'},
                {name: 'qty_beli_bonus', allowBlank: false, type: 'int'},
                {name: 'kd_produk_bonus', allowBlank: false, type: 'text'},
                {name: 'qty_bonus', allowBlank: false, type: 'int'},
                {name: 'is_bonus_kelipatan', allowBlank: false, type: 'text'},
                {name: 'qty_beli_member', allowBlank: false, type: 'int'},
                {name: 'kd_produk_member', allowBlank: false, type: 'text'},
                {name: 'qty_member', allowBlank: false, type: 'int'},
                {name: 'is_member_kelipatan', allowBlank: false, type: 'text'},
                {name: 'tanggal', allowBlank: false, type: 'text'},
                {name: 'keterangan', allowBlank: false, type: 'text'},
                {name: 'status', allowBlank: false, type: 'text'},
                {name: 'is_konsinyasi', allowBlank: false, type: 'text'},
                {name: 'tgl_start_diskon', allowBlank: false, type: 'text'},
                {name: 'tgl_end_diskon', allowBlank: false, type: 'text'}
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("approval_harga_penjualan/search_produk_by_no_bukti") ?>',
            method: 'POST'
        }),
        writer: new Ext.data.JsonWriter(
                {
                    encode: true,
                    writeAllFields: true
                })
    });

    strapprovalhargapenjualan.on('update', function() {
        if (Ext.getCmp('eahj_het_cogs').getValue() == 0) {

            if (Ext.getCmp('eahj_rp_cogs').getValue() > 0) {
                if (Ext.getCmp('eahj_net_price_jual_kons').getValue() < Ext.getCmp('eahj_rp_cogs').getValue()) {
                    Ext.getCmp('eahj_net_price_jual_kons').setValue('0');
                    Ext.Msg.show({
                        title: 'Error',
                        msg: 'Net Price Jual tidak boleh lebih kecil dari HET COGS (inc PPN)',
                        modal: true,
                        icon: Ext.Msg.ERROR,
                        buttons: Ext.Msg.OK,
                        fn: function(btn) {
                            Ext.getCmp('eahj_net_price_jual_kons').focus();
                        }
                    });
                    Ext.MessageBox.getDialog().getEl().setStyle('z-index', '80000');
                }
            } else if (Ext.getCmp('eahj_rp_jual_supermarket').getValue() < Ext.getCmp('eahj_rp_het_harga_beli').getValue()) {
                Ext.getCmp('eahj_rp_jual_supermarket').setValue('0');
                Ext.Msg.show({
                    title: 'Error',
                    msg: 'Net Price Jual tidak boleh lebih kecil dari HET Net Price Beli (inc PPN)',
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK,
                    fn: function(btn) {
                        Ext.getCmp('eahj_rp_jual_supermarket').focus();
                    }
                });
                Ext.MessageBox.getDialog().getEl().setStyle('z-index', '80000');
            }
        } else {
            if (Ext.getCmp('eahj_rp_jual_supermarket').getValue() < Ext.getCmp('eahj_het_cogs').getValue()) {
                Ext.getCmp('eahj_rp_jual_supermarket').setValue('0');
                Ext.Msg.show({
                    title: 'Error',
                    msg: 'Net Price Jual tidak boleh lebih kecil dari HET COGS (inc PPN)',
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK,
                    fn: function(btn) {
                        Ext.getCmp('eahj_rp_jual_supermarket').focus();
                    }
                });
                Ext.MessageBox.getDialog().getEl().setStyle('z-index', '80000');
            } else if (Ext.getCmp('eahj_net_price_jual_kons').getValue() < Ext.getCmp('eahj_rp_het_harga_beli').getValue()) {
                Ext.getCmp('eahj_net_price_jual_kons').setValue('0');
                Ext.Msg.show({
                    title: 'Error',
                    msg: 'Net Price Jual tidak boleh lebih kecil dari HET Net Price Beli (inc PPN)',
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK,
                    fn: function(btn) {
                        Ext.getCmp('eahj_net_price_jual_kons').focus();
                    }
                });
                Ext.MessageBox.getDialog().getEl().setStyle('z-index', '80000');
            }

        }
    });

    function HETChangeAHJ() {
        var hrg_beli = Ext.getCmp('eahj_hrg_beli_satuan').getValue();
        var cogs = Ext.getCmp('eahj_rp_cogs').getValue();
        var ongkos = Ext.getCmp('eahj_rp_ongkos_kirim').getValue();
        var margin_op = Ext.getCmp('eahj_margin_op').getValue()
        var margin = Ext.getCmp('eahj_margin').getValue();
        var margin_rp = 0;
        if (margin_op == "%") {
            margin_rp = (margin * hrg_beli) / 100;
            margin_pct = margin;
        } else {
            margin_rp = margin;
            margin_pct = (margin * 100) / hrg_beli;
        }
        ongkos = ongkos + (ongkos * 0.1);
        var HET = hrg_beli + ongkos + margin_rp;

        if (margin_op == "%") {
            margin_rp = (margin * cogs) / 100;
            margin_pct = margin;
        } else {
            margin_rp = margin;
            margin_pct = (margin * 100) / cogs;
        }
        var ongkos_cogs = Ext.getCmp('eahj_rp_ongkos_kirim').getValue();
        var HETCOGS = (cogs + margin_rp + ongkos_cogs) * 1.1;
        if (cogs == 0) {
            HETCOGS = 0;
        }
        Ext.getCmp('eahj_rp_het_harga_beli').setValue(HET);
        Ext.getCmp('eahj_het_cogs').setValue(HETCOGS);
        Ext.getCmp('eahj_pct_margin').setValue(margin_pct);
        Ext.getCmp('eahj_rp_margin').setValue(margin_rp);
        EditedAHJ();
    }
    ;

    function EditedAHJ() {
        Ext.getCmp('eahj_edited').setValue('Y');
    }
    ;

    function HitungNetPJualAHJ() {
        EditedAHJ();
        var total_disk = 0;
        var rp_jual_supermarket = Ext.getCmp('eahj_rp_jual_supermarket').getValue();
        var disk_kons1_op = Ext.getCmp('eahj_disk_kons1_op').getValue();
        var disk_kons1 = Ext.getCmp('eahj_disk_kons1').getValue();
        if (disk_kons1_op == '%') {
            // disk_kons1 = (disk_kons1*rp_jual_supermarket)/100;
            total_disk = rp_jual_supermarket - (rp_jual_supermarket * (disk_kons1 / 100));
        } else {
            total_disk = rp_jual_supermarket - disk_kons1;
        }

        var disk_kons2_op = Ext.getCmp('eahj_disk_kons2_op').getValue();
        var disk_kons2 = Ext.getCmp('eahj_disk_kons2').getValue();
        if (disk_kons2_op == '%') {
            // disk_kons2 = (disk_kons2*disk_kons1)/100;
            total_disk = total_disk - (total_disk * (disk_kons2 / 100));
        } else {
            total_disk = total_disk - disk_kons2;
        }

        var disk_kons3_op = Ext.getCmp('eahj_disk_kons3_op').getValue();
        var disk_kons3 = Ext.getCmp('eahj_disk_kons3').getValue();
        if (disk_kons3_op == '%') {
            // disk_kons3 = (disk_kons3*disk_kons2)/100;
            total_disk = total_disk - (total_disk * (disk_kons3 / 100));
        } else {
            total_disk = total_disk - disk_kons3;
        }

        var disk_kons4_op = Ext.getCmp('eahj_disk_kons4_op').getValue();
        var disk_kons4 = Ext.getCmp('eahj_disk_kons4').getValue();
        if (disk_kons4_op == '%') {
            // disk_kons4 = (disk_kons4*disk_kons3)/100;
            total_disk = total_disk - (total_disk * (disk_kons4 / 100));
        } else {
            total_disk = total_disk - disk_kons4;
        }

        var total_disk = total_disk - Ext.getCmp('eahj_disk_kons5').getValue();

        var net_jual_kons = total_disk;
        Ext.getCmp('eahj_net_price_jual_kons').setValue(net_jual_kons);


        var disk_member1_op = Ext.getCmp('eahj_disk_member1_op').getValue();
        var disk_member1 = Ext.getCmp('eahj_disk_member1').getValue();
        if (disk_member1_op == '%') {
            // disk_member1 = (disk_member1*rp_jual_supermarket)/100;
            total_disk = rp_jual_supermarket - (rp_jual_supermarket * (disk_member1 / 100));
        } else {
            total_disk = rp_jual_supermarket - disk_member1;
        }

        var disk_member2_op = Ext.getCmp('eahj_disk_member2_op').getValue();
        var disk_member2 = Ext.getCmp('eahj_disk_member2').getValue();
        if (disk_member2_op == '%') {
            // disk_member2 = (disk_member2*disk_member1)/100;
            total_disk = total_disk - (total_disk * (disk_member2 / 100));
        } else {
            total_disk = total_disk - disk_member2;
        }

        var disk_member3_op = Ext.getCmp('eahj_disk_member3_op').getValue();
        var disk_member3 = Ext.getCmp('eahj_disk_member3').getValue();
        if (disk_member3_op == '%') {
            // disk_member3 = (disk_member3*disk_member2)/100;
            total_disk = total_disk - (total_disk * (disk_member3 / 100));
        } else {
            total_disk = total_disk - disk_member3;
        }

        var disk_member4_op = Ext.getCmp('eahj_disk_member4_op').getValue();
        var disk_member4 = Ext.getCmp('eahj_disk_member4').getValue();
        if (disk_member4_op == '%') {
            // disk_member4 = (disk_member4*disk_member3)/100;
            total_disk = total_disk - (total_disk * (disk_member4 / 100));
        } else {
            total_disk = total_disk - disk_member4;
        }

        var total_disk = total_disk - Ext.getCmp('eahj_disk_amt_member5').getValue();

        var net_price_memb = total_disk;
        Ext.getCmp('eahj_net_price_jual_member').setValue(net_price_memb);
    }

    var editorapprovalhargapenjualan = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });

    var gridapprovalhargapenjualan = new Ext.grid.GridPanel({
        store: strapprovalhargapenjualan,
        stripeRows: true,
        height: 350,
        frame: true,
        border: true,
        plugins: [editorapprovalhargapenjualan],
        columns: [{
                dataIndex: 'kd_diskon_sales',
                hidden: true
            }, {
                dataIndex: 'pct_margin',
                hidden: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'eahj_pct_margin'
                })
            }, {
                dataIndex: 'rp_margin',
                hidden: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'eahj_rp_margin'
                })
            }, {
                dataIndex: 'koreksi_ke',
                hidden: true
            }, {
                dataIndex: 'koreksi_produk',
                hidden: true
            }, {
                header: 'Status',
                dataIndex: 'status',
                width: 80,
                sortable: true,
                editor: {
                    xtype: 'combo',
                    store: new Ext.data.JsonStore({
                        fields: ['name'],
                        data: [
                            {name: 'Approve'},
                            {name: 'Reject'}
                        ]
                    }),
                    id: 'eahj_status',
                    mode: 'local',
                    name: 'status',
                    value: 'Approve',
                    width: 80,
                    editable: false,
                    hiddenName: 'status',
                    valueField: 'name',
                    displayField: 'name',
                    triggerAction: 'all',
                    forceSelection: true
                }
            }, {
                header: 'Edited',
                dataIndex: 'edited',
                width: 50,
                sortable: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'eahj_edited'
                })
            }, {
                header: 'Tanggal',
                dataIndex: 'tanggal',
                width: 100,
                sortable: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'eahj_tanggal'
                })
            }, {
                header: 'Kode Barang',
                dataIndex: 'kd_produk',
                width: 100,
                sortable: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'eahj_kd_produk'
                })
            }, {
                header: 'Kode Brg Lama',
                dataIndex: 'kd_produk_lama',
                width: 100,
                sortable: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'eahj_kd_produk_lama'
                })
            }, {
                header: 'Nama Barang',
                dataIndex: 'nama_produk',
                width: 300,
                sortable: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'eahj_nama_produk'
                })
            }, {
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 80,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'eahj_satuan',
                    fieldClass: 'readonly-input'
                })
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Net Price Beli (Inc.PPN)',
                dataIndex: 'net_hrg_supplier_sup_inc',
                width: 150,
                editor: {
                    xtype: 'numberfield',
                    id: 'eahj_hrg_beli_satuan',
                    readOnly: true,
                    fieldClass: 'readonly-input'
                }
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'COGS',
                dataIndex: 'rp_cogs',
                width: 100,
                editor: {
                    xtype: 'numberfield',
                    id: 'eahj_rp_cogs',
                    readOnly: true,
                    fieldClass: 'readonly-input'
                }
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Ongkos Kirim',
                dataIndex: 'rp_ongkos_kirim',
                width: 140,
                editor: {
                    xtype: 'numberfield',
                    id: 'eahj_rp_ongkos_kirim',
                    listeners: {
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                HETChangeAHJ();
                            }, c);
                        }
                    }
                }
            }, {
                header: '% / Rp',
                dataIndex: 'margin_op',
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
                    id: 'eahj_margin_op',
                    mode: 'local',
                    name: 'margin_op',
                    value: '%',
                    width: 50,
                    editable: false,
                    hiddenName: 'margin_op',
                    valueField: 'name',
                    displayField: 'name',
                    triggerAction: 'all',
                    forceSelection: true,
                    listeners: {
                        'expand': function() {
                            Ext.getCmp('eahj_margin').setValue(0);
                            HETChangeAHJ();
                        },
                        select: function() {
                            HETChangeAHJ();
                            Ext.getCmp('eahj_margin').setMaxValue(Number.MAX_VALUE);
                            if (this.getValue() === 'persen')
                                Ext.getCmp('eahj_margin').maxValue = 100;
                            else
                                Ext.getCmp('eahj_margin').maxLength = 11;
                        }
                    }
                }
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Margin',
                dataIndex: 'margin',
                width: 100,
                editor: {
                    xtype: 'numberfield',
                    id: 'eahj_margin',
                    listeners: {
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                HETChangeAHJ();
                            }, c);
                        }
                    }
                }
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'HET Net Price Beli (Inc.PPN)',
                dataIndex: 'rp_het_harga_beli',
                width: 180,
                editor: {
                    xtype: 'numberfield',
                    id: 'eahj_rp_het_harga_beli',
                    readOnly: true
                }
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'HET COGS (Inc.PPN)',
                dataIndex: 'rp_het_cogs',
                width: 140,
                editor: {
                    xtype: 'numberfield',
                    id: 'eahj_het_cogs',
                    readOnly: true
                }
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Harga Jual Supermarket',
                dataIndex: 'rp_jual_supermarket',
                width: 180,
                editor: {
                    xtype: 'numberfield',
                    id: 'eahj_rp_jual_supermarket',
                    listeners: {
                        'change': function() {
                            if (Ext.getCmp('eahj_rp_cogs').getValue() > 0) {
                                if (this.getValue() < Ext.getCmp('eahj_rp_cogs').getValue()) {
                                    this.setValue('0');
                                    Ext.Msg.show({
                                        title: 'Error',
                                        msg: 'Net Price Jual tidak boleh lebih kecil dari HET COGS (inc PPN)',
                                        modal: true,
                                        icon: Ext.Msg.ERROR,
                                        buttons: Ext.Msg.OK,
                                        fn: function(btn) {
                                            this.focus();
                                        }
                                    });
                                    Ext.MessageBox.getDialog().getEl().setStyle('z-index', '80000');
                                }
                            } else {
                                if (this.getValue() < Ext.getCmp('eahj_rp_het_harga_beli').getValue()) {
                                    this.setValue('0');
                                    Ext.Msg.show({
                                        title: 'Error',
                                        msg: 'Net Price Jual tidak boleh lebih kecil dari HET Net Price Beli (inc PPN)',
                                        modal: true,
                                        icon: Ext.Msg.ERROR,
                                        buttons: Ext.Msg.OK,
                                        fn: function(btn) {
                                            this.focus();
                                        }
                                    });
                                    Ext.MessageBox.getDialog().getEl().setStyle('z-index', '80000');
                                }
                            }


                        }, 'render': function(c) {
                            c.getEl().on('keyup', function() {
                                // EditedAHJ();
                                HitungNetPJualAHJ();
                            }, c);
                        }
                    }
                }
            }, {
                header: '% / Rp',
                dataIndex: 'disk_kons1_op',
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
                    id: 'eahj_disk_kons1_op',
                    mode: 'local',
                    name: 'disk_kons1_op',
                    value: '%',
                    width: 50,
                    editable: false,
                    hiddenName: 'disk_kons1_op',
                    valueField: 'name',
                    displayField: 'name',
                    triggerAction: 'all',
                    forceSelection: true,
                    listeners: {
                        'expand': function() {
                            Ext.getCmp('eahj_disk_kons1').setValue(0);
                        },
                        select: function() {
                            Ext.getCmp('eahj_disk_kons1').setMaxValue(Number.MAX_VALUE);
                            if (this.getValue() === 'persen')
                                Ext.getCmp('eahj_disk_kons1').maxValue = 100;
                            else
                                Ext.getCmp('eahj_disk_kons1').maxLength = 11;
                        }
                    }
                }
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                // format: '0,0',
                header: 'Diskon Konsumen 1',
                dataIndex: 'disk_kons1',
                width: 150,
                editor: {
                    xtype: 'numberfield',
                    msgTarget: 'under',
                    flex: 1,
                    width: 115,
                    name: 'disk_kons1',
                    id: 'eahj_disk_kons1',
                    style: 'text-align:right;',
                    listeners: {
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                HitungNetPJualAHJ();
                            }, c);
                        }

                    }
                }
            }, {
                header: '% / Rp',
                dataIndex: 'disk_kons2_op',
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
                    id: 'eahj_disk_kons2_op',
                    mode: 'local',
                    name: 'disk_kons2_op',
                    value: '%',
                    width: 50,
                    editable: false,
                    hiddenName: 'disk_kons2_op',
                    valueField: 'name',
                    displayField: 'name',
                    triggerAction: 'all',
                    forceSelection: true,
                    listeners: {
                        'expand': function() {
                            Ext.getCmp('eahj_disk_kons2').setValue(0);
                        },
                        select: function() {
                            Ext.getCmp('eahj_disk_kons2').setMaxValue(Number.MAX_VALUE);
                            if (this.getValue() === 'persen')
                                Ext.getCmp('eahj_disk_kons2').maxValue = 100;
                            else
                                Ext.getCmp('eahj_disk_kons2').maxLength = 11;
                        }
                    }
                }
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                // format: '0,0',
                header: 'Diskon Konsumen 2',
                dataIndex: 'disk_kons2',
                width: 150,
                editor: {
                    xtype: 'numberfield',
                    msgTarget: 'under',
                    flex: 1,
                    width: 115,
                    name: 'disk_kons2',
                    id: 'eahj_disk_kons2',
                    style: 'text-align:right;',
                    listeners: {
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                // EditedAHJ();
                                HitungNetPJualAHJ();
                            }, c);
                        }
                    }
                }
            }, {
                header: '% / Rp',
                dataIndex: 'disk_kons3_op',
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
                    id: 'eahj_disk_kons3_op',
                    mode: 'local',
                    name: 'disk_kons3_op',
                    value: '%',
                    width: 50,
                    editable: false,
                    hiddenName: 'disk_kons3_op',
                    valueField: 'name',
                    displayField: 'name',
                    triggerAction: 'all',
                    forceSelection: true,
                    listeners: {
                        'expand': function() {
                            Ext.getCmp('eahj_disk_kons3').setValue(0);
                        },
                        select: function() {
                            Ext.getCmp('eahj_disk_kons3').setMaxValue(Number.MAX_VALUE);
                            if (this.getValue() === 'persen')
                                Ext.getCmp('eahj_disk_kons3').maxValue = 100;
                            else
                                Ext.getCmp('eahj_disk_kons3').maxLength = 11;
                        }
                    }
                }
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                // format: '0,0',
                header: 'Diskon Konsumen 3',
                dataIndex: 'disk_kons3',
                width: 150,
                editor: {
                    xtype: 'numberfield',
                    msgTarget: 'under',
                    flex: 1,
                    width: 115,
                    name: 'disk_kons3',
                    id: 'eahj_disk_kons3',
                    style: 'text-align:right;',
                    listeners: {
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                // EditedAHJ();
                                HitungNetPJualAHJ();
                            }, c);
                        }
                    }
                }
            }, {
                header: '% / Rp',
                dataIndex: 'disk_kons4_op',
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
                    id: 'eahj_disk_kons4_op',
                    mode: 'local',
                    name: 'disk_kons4_op',
                    value: '%',
                    width: 50,
                    editable: false,
                    hiddenName: 'disk_kons4_op',
                    valueField: 'name',
                    displayField: 'name',
                    triggerAction: 'all',
                    forceSelection: true,
                    listeners: {
                        'expand': function() {
                            Ext.getCmp('eahj_disk_kons4').setValue(0);
                        },
                        select: function() {
                            Ext.getCmp('eahj_disk_kons4').setMaxValue(Number.MAX_VALUE);
                            if (this.getValue() === 'persen')
                                Ext.getCmp('eahj_disk_kons4').maxValue = 100;
                            else
                                Ext.getCmp('eahj_disk_kons4').maxLength = 11;
                        }
                    }
                }
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                // format: '0,0',
                header: 'Diskon Konsumen 4',
                dataIndex: 'disk_kons4',
                width: 150,
                editor: {
                    xtype: 'numberfield',
                    msgTarget: 'under',
                    flex: 1,
                    width: 115,
                    name: 'disk_kons4',
                    id: 'eahj_disk_kons4',
                    style: 'text-align:right;',
                    listeners: {
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                // EditedAHJ();
                                HitungNetPJualAHJ();
                            }, c);
                        }
                    }
                }
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Diskon Konsumen 5',
                dataIndex: 'disk_amt_kons5',
                width: 150,
                editor: {
                    xtype: 'numberfield',
                    msgTarget: 'under',
                    flex: 1,
                    width: 115,
                    name: 'disk_kons5',
                    id: 'eahj_disk_kons5',
                    style: 'text-align:right;',
                    listeners: {
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                // EditedAHJ();
                                HitungNetPJualAHJ();
                            }, c);
                        }
                    }
                }
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Net Price Jual Konsumen',
                dataIndex: 'net_price_jual_kons',
                width: 180,
                editor: {
                    xtype: 'numberfield',
                    id: 'eahj_net_price_jual_kons',
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    listeners: {
                        'change': function() {
                            if (Ext.getCmp('eahj_rp_cogs').getValue() > 0) {
                                if (Ext.getCmp('eahj_net_price_jual_kons').getValue() < Ext.getCmp('eahj_rp_cogs').getValue()) {
                                    Ext.getCmp('eahj_net_price_jual_kons').setValue('0');
                                    Ext.Msg.show({
                                        title: 'Error',
                                        msg: 'Net Price Jual tidak boleh lebih kecil dari HET COGS (inc PPN)',
                                        modal: true,
                                        icon: Ext.Msg.ERROR,
                                        buttons: Ext.Msg.OK,
                                        fn: function(btn) {
                                            Ext.getCmp('eahj_net_price_jual_kons').focus();
                                        }
                                    });
                                    Ext.MessageBox.getDialog().getEl().setStyle('z-index', '80000');
                                }
                            } else {
                                if (Ext.getCmp('eahj_net_price_jual_kons').getValue() < Ext.getCmp('eahj_rp_het_harga_beli').getValue()) {
                                    Ext.getCmp('eahj_net_price_jual_kons').setValue('0');
                                    Ext.Msg.show({
                                        title: 'Error',
                                        msg: 'Net Price Jual tidak boleh lebih kecil dari HET Net Price Beli (inc PPN)',
                                        modal: true,
                                        icon: Ext.Msg.ERROR,
                                        buttons: Ext.Msg.OK,
                                        fn: function(btn) {
                                            Ext.getCmp('eahj_net_price_jual_kons').focus();
                                        }
                                    });
                                    Ext.MessageBox.getDialog().getEl().setStyle('z-index', '80000');
                                }
                            }


                        }, 'render': function(c) {
                            c.getEl().on('keyup', function() {
                                // EditedAHJ();
                            }, c);
                        }
                    }
                }
            }, {
                header: '% / Rp',
                dataIndex: 'disk_member1_op',
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
                    id: 'eahj_disk_member1_op',
                    mode: 'local',
                    name: 'disk_member1_op',
                    value: '%',
                    width: 50,
                    editable: false,
                    hiddenName: 'disk_member1_op',
                    valueField: 'name',
                    displayField: 'name',
                    triggerAction: 'all',
                    forceSelection: true,
                    listeners: {
                        'expand': function() {
                            Ext.getCmp('eahj_disk_member1').setValue(0);
                        },
                        select: function() {
                            Ext.getCmp('eahj_disk_member1').setMaxValue(Number.MAX_VALUE);
                            if (this.getValue() === 'persen')
                                Ext.getCmp('eahj_disk_member1').maxValue = 100;
                            else
                                Ext.getCmp('eahj_disk_member1').maxLength = 11;
                        }
                    }
                }
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                // format: '0,0',
                header: 'Diskon Member 1',
                dataIndex: 'disk_member1',
                width: 150,
                editor: {
                    xtype: 'numberfield',
                    msgTarget: 'under',
                    flex: 1,
                    width: 115,
                    name: 'disk_member1',
                    id: 'eahj_disk_member1',
                    style: 'text-align:right;',
                    listeners: {
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                // EditedAHJ();
                                HitungNetPJualAHJ();
                            }, c);
                        }
                    }
                }
            }, {
                header: '% / Rp',
                dataIndex: 'disk_member2_op',
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
                    id: 'eahj_disk_member2_op',
                    mode: 'local',
                    name: 'disk_member2_op',
                    value: '%',
                    width: 50,
                    editable: false,
                    hiddenName: 'disk_member2_op',
                    valueField: 'name',
                    displayField: 'name',
                    triggerAction: 'all',
                    forceSelection: true,
                    listeners: {
                        'expand': function() {
                            Ext.getCmp('eahj_disk_member2').setValue(0);
                        },
                        select: function() {
                            Ext.getCmp('eahj_disk_member2').setMaxValue(Number.MAX_VALUE);
                            if (this.getValue() === 'persen')
                                Ext.getCmp('eahj_disk_member2').maxValue = 100;
                            else
                                Ext.getCmp('eahj_disk_member2').maxLength = 11;
                        }
                    }
                }
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                // format: '0,0',
                header: 'Diskon Member 2',
                dataIndex: 'disk_member2',
                width: 150,
                editor: {
                    xtype: 'numberfield',
                    msgTarget: 'under',
                    flex: 1,
                    width: 115,
                    name: 'disk_member2',
                    id: 'eahj_disk_member2',
                    style: 'text-align:right;',
                    listeners: {
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                // EditedAHJ();
                                HitungNetPJualAHJ();
                            }, c);
                        }
                    }
                }
            }, {
                header: '% / Rp',
                dataIndex: 'disk_member3_op',
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
                    id: 'eahj_disk_member3_op',
                    mode: 'local',
                    name: 'disk_member3_op',
                    value: '%',
                    width: 50,
                    editable: false,
                    hiddenName: 'disk_member3_op',
                    valueField: 'name',
                    displayField: 'name',
                    triggerAction: 'all',
                    forceSelection: true,
                    listeners: {
                        'expand': function() {
                            Ext.getCmp('eahj_disk_member3').setValue(0);
                        },
                        select: function() {
                            Ext.getCmp('eahj_disk_member3').setMaxValue(Number.MAX_VALUE);
                            if (this.getValue() === 'persen')
                                Ext.getCmp('eahj_disk_member3').maxValue = 100;
                            else
                                Ext.getCmp('eahj_disk_member3').maxLength = 11;
                        }
                    }
                }
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                // format: '0,0',
                header: 'Diskon Member 3',
                dataIndex: 'disk_member3',
                width: 150,
                editor: {
                    xtype: 'numberfield',
                    msgTarget: 'under',
                    flex: 1,
                    width: 115,
                    name: 'disk_member3',
                    id: 'eahj_disk_member3',
                    style: 'text-align:right;',
                    listeners: {
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                // EditedAHJ();
                                HitungNetPJualAHJ();
                            }, c);
                        }
                    }
                }
            }, {
                header: '% / Rp',
                dataIndex: 'disk_member4_op',
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
                    id: 'eahj_disk_member4_op',
                    mode: 'local',
                    name: 'disk_member4_op',
                    value: '%',
                    width: 50,
                    editable: false,
                    hiddenName: 'disk_member4_op',
                    valueField: 'name',
                    displayField: 'name',
                    triggerAction: 'all',
                    forceSelection: true,
                    listeners: {
                        'expand': function() {
                            Ext.getCmp('eahj_disk_member4').setValue(0);
                        },
                        select: function() {
                            Ext.getCmp('eahj_disk_member4').setMaxValue(Number.MAX_VALUE);
                            if (this.getValue() === 'persen')
                                Ext.getCmp('eahj_disk_member4').maxValue = 100;
                            else
                                Ext.getCmp('eahj_disk_member4').maxLength = 11;
                        }
                    }
                }
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                // format: '0,0',
                header: 'Diskon Member 4',
                dataIndex: 'disk_member4',
                width: 150,
                editor: {
                    xtype: 'numberfield',
                    msgTarget: 'under',
                    flex: 1,
                    width: 115,
                    name: 'disk_member4',
                    id: 'eahj_disk_member4',
                    style: 'text-align:right;',
                    listeners: {
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                // EditedAHJ();
                                HitungNetPJualAHJ();
                            }, c);
                        }
                    }
                }
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Diskon Member 5',
                dataIndex: 'disk_amt_member5',
                width: 150,
                editor: {
                    xtype: 'numberfield',
                    msgTarget: 'under',
                    flex: 1,
                    width: 115,
                    name: 'disk_amt_member5',
                    id: 'eahj_disk_amt_member5',
                    style: 'text-align:right;',
                    listeners: {
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                // EditedAHJ();
                                HitungNetPJualAHJ();
                            }, c);
                        }
                    }
                }
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Net Price Jual Member',
                dataIndex: 'net_price_jual_member',
                width: 180,
                editor: {
                    xtype: 'numberfield',
                    id: 'eahj_net_price_jual_member',
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    listeners: {
                        'change': function() {
                            if (Ext.getCmp('eahj_rp_cogs').getValue() > 0) {
                                if (this.getValue() < Ext.getCmp('eahj_rp_cogs').getValue()) {
                                    this.setValue('0');
                                    Ext.Msg.show({
                                        title: 'Error',
                                        msg: 'Net Price Jual tidak boleh lebih kecil dari HET COGS (inc PPN)',
                                        modal: true,
                                        icon: Ext.Msg.ERROR,
                                        buttons: Ext.Msg.OK,
                                        fn: function(btn) {
                                            this.focus();
                                        }
                                    });
                                    Ext.MessageBox.getDialog().getEl().setStyle('z-index', '80000');
                                }
                            } else {
                                if (this.getValue() < Ext.getCmp('eahj_rp_het_harga_beli').getValue()) {
                                    this.setValue('0');
                                    Ext.Msg.show({
                                        title: 'Error',
                                        msg: 'Net Price Jual tidak boleh lebih kecil dari HET Net Price Beli (inc PPN)',
                                        modal: true,
                                        icon: Ext.Msg.ERROR,
                                        buttons: Ext.Msg.OK,
                                        fn: function(btn) {
                                            this.focus();
                                        }
                                    });
                                    Ext.MessageBox.getDialog().getEl().setStyle('z-index', '80000');
                                }
                            }


                        }, 'render': function(c) {
                            c.getEl().on('keyup', function() {
                                // EditedAHJ();
                            }, c);
                        }
                    }
                }
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Qty Beli |Konsumen|',
                dataIndex: 'qty_beli_bonus',
                width: 150,
                editor: {
                    xtype: 'numberfield',
                    msgTarget: 'under',
                    flex: 1,
                    width: 115,
                    name: 'qty_beli_bonus',
                    id: 'eahj_qty_beli_bonus',
                    style: 'text-align:right;',
                    listeners: {
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                // EditedAHJ();
                            }, c);
                        }
                    }
                }
            }, {
                header: 'Kd Produk |Konsumen|',
                dataIndex: 'kd_produk_bonus',
                width: 150,
                editor: new Ext.ux.TwinComboHj({
                    id: 'eahj_kd_produk_bonus',
                    store: strcbkdprodukhj,
                    valueField: 'kd_produk_bonus',
                    displayField: 'kd_produk_bonus',
                    typeAhead: true,
                    editable: false,
                    hiddenName: 'kd_produk_bonus',
                    emptyText: 'Pilih Kode Produk',
                    listeners: {
                        'expand': function() {
                            strcbkdprodukhj.load();
                            // EditedAHJ();
                        }
                    }
                })
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Qty Bonus |Konsumen|',
                dataIndex: 'qty_bonus',
                width: 150,
                editor: {
                    xtype: 'numberfield',
                    msgTarget: 'under',
                    flex: 1,
                    width: 115,
                    name: 'qty_bonus',
                    id: 'eahj_qty_bonus',
                    style: 'text-align:right;',
                    listeners: {
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                // EditedAHJ();
                            }, c);
                        }
                    }
                }
            }, {
                header: 'Kelipatan ? |Konsumen|',
                dataIndex: 'is_bonus_kelipatan',
                width: 150,
                editor: {
                    xtype: 'combo',
                    store: new Ext.data.JsonStore({
                        fields: ['name'],
                        data: [
                            {name: 'Ya'},
                            {name: 'Tidak'}
                        ]
                    }),
                    id: 'eahj_is_bonus_kelipatan',
                    mode: 'local',
                    name: 'is_bonus_kelipatan',
                    value: 'Ya',
                    width: 50,
                    editable: false,
                    hiddenName: 'is_bonus_kelipatan',
                    valueField: 'name',
                    displayField: 'name',
                    triggerAction: 'all',
                    forceSelection: true,
                    listeners: {
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                // EditedAHJ();
                            }, c);
                        }
                    }
                }
            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Qty Beli |Member|',
                dataIndex: 'qty_beli_member',
                width: 150,
                editor: {
                    xtype: 'numberfield',
                    msgTarget: 'under',
                    flex: 1,
                    width: 115,
                    name: 'qty_beli_member',
                    id: 'eahj_qty_beli_member',
                    style: 'text-align:right;',
                    listeners: {
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                // EditedAHJ();
                            }, c);
                        }
                    }
                }
            }, {
                header: 'Kd Produk |Member|',
                dataIndex: 'kd_produk_member',
                width: 150,
                editor: new Ext.ux.TwinComboHj({
                    id: 'eahj_kd_produk_member',
                    store: strcbkdprodukhj,
                    valueField: 'kd_produk_member',
                    displayField: 'kd_produk_member',
                    typeAhead: true,
                    editable: false,
                    hiddenName: 'kd_produk_member',
                    emptyText: 'Pilih Kode Produk',
                    listeners: {
                        'expand': function() {
                            strcbkdprodukhj.load();
                            // EditedAHJ();
                        }
                    }
                })

            }, {
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Qty Bonus |Member|',
                dataIndex: 'qty_member',
                width: 150,
                editor: {
                    xtype: 'numberfield',
                    msgTarget: 'under',
                    flex: 1,
                    width: 115,
                    name: 'qty_member',
                    id: 'eahj_qty_member',
                    style: 'text-align:right;',
                    listeners: {
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                // EditedAHJ();
                            }, c);
                        }
                    }
                }
            }, {
                header: 'Kelipatan ? |Member|',
                dataIndex: 'is_member_kelipatan',
                width: 150,
                editor: {
                    xtype: 'combo',
                    store: new Ext.data.JsonStore({
                        fields: ['name'],
                        data: [
                            {name: 'Ya'},
                            {name: 'Tidak'},
                        ]
                    }),
                    id: 'eahj_is_member_kelipatan',
                    mode: 'local',
                    name: 'is_member_kelipatan',
                    value: 'Ya',
                    width: 50,
                    editable: false,
                    hiddenName: 'is_member_kelipatan',
                    valueField: 'name',
                    displayField: 'name',
                    triggerAction: 'all',
                    forceSelection: true,
                    listeners: {
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                // EditedAHJ();
                            }, c);
                        }
                    }
                }

            }, {
                header: 'Is Konsinyasi',
                dataIndex: 'is_konsinyasi',
                width: 80,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'eahj_is_konsinyasi',
                    fieldClass: 'readonly-input'
                })
            }, {
                    xtype: 'datecolumn',
                    header: 'Efektif Diskon',
                    dataIndex: 'tgl_start_diskon',
                    format: 'd/m/Y',
                    width: 120,
                    editor: new Ext.form.DateField({
                        id: 'eappp_tgl_start_diskon',
                        format: 'd/m/Y',
                        //minValue: (new Date()).clearTime(),
                         listeners:{			
                            'change': function() {
                               	  Ext.getCmp('eahj_edited').setValue('Y');
                            }
                        }
                    })
                },
//                {
//                    xtype: 'datecolumn',
//                    header: 'Tgl Akhir Diskon',
//                    dataIndex: 'tgl_end_diskon',
//                    format: 'd/m/Y',
//                    width: 120,
//                    editor: new Ext.form.DateField({
//                        id: 'eappp_tgl_end_diskon',
//                        format: 'd/m/Y',
//                        minValue: (new Date()).clearTime(),
//                        listeners:{			
//                            'change': function() {
//                               	  Ext.getCmp('eahj_edited').setValue('Y');
//                            }
//                        }
//                    })
//                },
                {
                header: 'Ket. Perubahan',
                dataIndex: 'keterangan',
                width: 300,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'eahj_keterangan'
                })
            }]
    });

    /***/
    var approvalhargapenjualan = new Ext.FormPanel({
        id: 'approvalhargapenjualan',
        buttonAlign: 'left',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },
                items: [headerapprovalhargapenjualan, gridapprovalhargapenjualan]
            },
        ],
        buttons: [{
                text: 'Reset',
                handler: function() {
                    clearapprovalhargapenjualan();
                }
            }]
    });

    function clearapprovalhargapenjualan() {
        Ext.getCmp('approvalhargapenjualan').getForm().reset();
        strapprovalhargapenjualan.removeAll();
    }
</script>
