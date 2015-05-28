<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">

    /* START GRID */
    var strmasterpelanggan = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'kd_pelanggan',
                'nama_pelanggan',
                'tipe',
                'tipe_pelanggan',
                'alamat_kirim',
                'alamat_tagih',
                'is_pkp',
                'pkp',
                'npwp',
                'alamat_npwp',
                'kd_propinsi',
                'nama_propinsi',
                'kd_kota',
                'nama_kota',
                'kd_kecamatan',
                'nama_kecamatan',
                'kd_kalurahan',
                'nama_kalurahan',
                'kd_cabang',
                'nama_cabang',
                'kodepos',
                'no_telp',
                'no_fax',
                'email',
                'nama_pic',
                'no_telp_pic',
                'aktif',
                'top_dist',
                'limit_dist',
                'kd_area'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_pelanggan/get_rows") ?>',
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
    // Sales
    var strmaspelcbmaspelsales = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_sales', 'nama_sales'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_pelanggan/get_sales") ?>',
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

    // Area
    var storeMasterPelCombArea = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_area', 'nama_area'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_pelanggan/getArea") ?>',
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

    var cbmaspelsales = new Ext.form.ComboBox({
        fieldLabel: 'Sales <span class="asterix">*</span>',
        id: 'id_maspelcbmaspelsales',
        store: strmaspelcbmaspelsales,
        valueField: 'kd_sales',
        displayField: 'nama_sales',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_sales',
        emptyText: 'Sales',
        hideMode: 'Visibility',
        listeners: {
//            select: function(combo, records) {
//                var prop = this.getValue();
//                cbmaspelkot.setValue();
//                cbmaspelkot.store.proxy.conn.url = '<?= site_url("master_member/get_kota") ?>/' + prop;
//                cbmaspelkot.store.reload();
//            }
        }
    });

    var cboMasterPelArea = new Ext.form.ComboBox({
        fieldLabel: 'Area <span class="asterix">*</span>',
        id: 'id_mas_pel_cbo_area',
        store: storeMasterPelCombArea,
        valueField: 'kd_area',
        displayField: 'nama_area',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_area',
        emptyText: 'Area',
        hideMode: 'Visibility',
        listeners: {
//            select: function(combo, records) {
//                var prop = this.getValue();
//                cbmaspelkot.setValue();
//                cbmaspelkot.store.proxy.conn.url = '<?= site_url("master_member/get_kota") ?>/' + prop;
//                cbmaspelkot.store.reload();
//            }
        }
    });
    
    //Provinsi
    var strmaspelcbmaspelprop = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_propinsi', 'nama_propinsi'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_member/get_prop") ?>',
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

    var cbmaspelprop = new Ext.form.ComboBox({
        fieldLabel: 'Propinsi <span class="asterix">*</span>',
        id: 'id_maspelcbmaspelprop',
        store: strmaspelcbmaspelprop,
        valueField: 'kd_propinsi',
        displayField: 'nama_propinsi',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_propinsi',
        emptyText: 'Propinsi',
        hideMode: 'Visibility',
        listeners: {
            select: function(combo, records) {
                var prop = this.getValue();
                cbmaspelkot.setValue();
                cbmaspelkot.store.proxy.conn.url = '<?= site_url("master_member/get_kota") ?>/' + prop;
                cbmaspelkot.store.reload();
            }
        }
    });


    // combobox kategori2
    var strmaspelcbkota = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kota', 'nama_kota'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_member/get_kota") ?>',
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

    var cbmaspelkot = new Ext.form.ComboBox({
        fieldLabel: 'Kota <span class="asterix">*</span>',
        id: 'id_maspelcbmaspelkota',
        store: strmaspelcbkota,
        valueField: 'kd_kota',
        displayField: 'nama_kota',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        mode: 'local',
        hiddenName: 'kd_kota',
        emptyText: 'Kota',
        hideMode: 'Visibility',
        listeners: {
            select: function(combo, records) {
                var prop = cbmaspelprop.getValue();
                var kota = this.getValue();
                cbmaspelkec.setValue();
                cbmaspelkec.store.proxy.conn.url = '<?= site_url("master_member/get_kec") ?>/' + prop + '/' + kota;
                cbmaspelkec.store.reload();
            }
        }
    });

    var strmaspelcbkec = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kecamatan', 'nama_kecamatan'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_member/get_kec") ?>',
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

    var cbmaspelkec = new Ext.form.ComboBox({
        fieldLabel: 'Kecamatan',
        id: 'id_maspelcbmaspelkec',
        store: strmaspelcbkec,
        valueField: 'kd_kecamatan',
        displayField: 'nama_kecamatan',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        mode: 'local',
        hiddenName: 'kd_kecamatan',
        emptyText: 'Kecamatan',
        hideMode: 'Visibility',
        listeners: {
            select: function(combo, records) {
                var prop = cbmaspelprop.getValue();
                var kota = cbmaspelkot.getValue();
                var kec = cbmaspelkec.getValue();
                cbmaspelkel.setValue();
                cbmaspelkel.store.proxy.conn.url = '<?= site_url("master_member/get_kel") ?>/' + prop + '/' + kota + '/' + kec;
                cbmaspelkel.store.reload();
            }
        }
    });

    var strmaspelcbkel = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kalurahan', 'nama_kalurahan'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_member/get_kel") ?>',
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

    var cbmaspelkel = new Ext.form.ComboBox({
        fieldLabel: 'Kalurahan ',
        id: 'id_maspelcbmaspelkel',
        store: strmaspelcbkel,
        valueField: 'kd_kalurahan',
        displayField: 'nama_kalurahan',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        mode: 'local',
        hiddenName: 'kd_kalurahan',
        emptyText: 'Kalurahan',
        hideMode: 'Visibility'
    });

    Ext.ux.ComboBox = function(config) {
        if (Ext.isArray(config.store))
        {
            if (Ext.isArray(config.store[0]))
            {
                config.store = new Ext.data.SimpleStore({
                    fields: ['value', 'text'],
                    data: config.store
                });
                config.valueField = 'value';
                config.displayField = 'text';
            }
            else
            {
                var store = [];
                for (var i = 0, len = config.store.length; i < len; i++)
                    store[i] = [config.store[i]];
                config.store = new Ext.data.SimpleStore({
                    fields: ['text'],
                    data: store
                });
                config.valueField = 'text';
                config.displayField = 'text';
            }
            config.mode = 'local';
        }
        Ext.ux.ComboBox.superclass.constructor.call(this, config);
    };

    var str_kd_cabang = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd', 'nama'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_member/get_cab") ?>',
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

    var cb_kd_cabang = new Ext.form.ComboBox({
        fieldLabel: 'Cabang <span class="asterix">*</span>',
        id: 'id_cb_kdcab',
        store: str_kd_cabang,
        valueField: 'kd',
        displayField: 'nama',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_cabang',
        emptyText: 'Pilih Cabang',
        hideMode: 'Visibility'
    });


    Ext.extend(Ext.ux.ComboBox, Ext.form.ComboBox, {
    });
    Ext.reg('combo', Ext.ux.ComboBox);
    /* START FORM */
    Ext.ns('masterpelangganform');
    masterpelangganform.Form = Ext.extend(Ext.form.FormPanel, {
        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 120,
        url: '<?= site_url("master_pelanggan/update_row") ?>',
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
            masterpelangganform.Form.superclass.constructor.call(this, config);
        },
        initComponent: function() {

            // hard coded - cannot be changed from outsid
            var config = {
                layout: 'column',
                monitorValid: true,
                autoScroll: true,
                defaults: {
                    // implicitly create Container by specifying xtype
                    xtype: 'container',
                    autoEl: 'div', // This is the default.
                    layout: 'form',
                    columnWidth: 0.5,
                    defaultType: 'textfield',
                    style: {
                        padding: '10px'
                    }
                },
                //  The two items below will be Ext.Containers, each encapsulated by a <DIV> element.
                items: [{
                        items: [
                            {
                                xtype: 'hidden',
                                name: 'kd_pelanggan'
                            }, {
                                type: 'textfield',
                                fieldLabel: 'Nama Pelanggan <span class="asterix">*</span>',
                                name: 'nama_pelanggan',
                                allowBlank: false,
                                id: 'id_maspelmaspelnama_pelanggan',
                                maxLength: 25,
                                anchor: '90%'
                            }, {
                                xtype: 'numberfield',
                                fieldLabel: 'Telepon <span class="asterix">*</span>',
                                name: 'no_telp',
                                allowBlank: false,
                                id: 'id_maspelno_telp',
                                maxLength: 25,
                                anchor: '90%'
                            }, {
                                xtype: 'numberfield',
                                fieldLabel: 'No. Fax',
                                name: 'no_fax',
                                allowBlank: true,
                                id: 'id_maspelno_fax',
                                maxLength: 15,
                                anchor: '90%'
                            }, {
                                type: 'textfield',
                                fieldLabel: 'E-Mail',
                                name: 'email',
                                id: 'id_maspel_email',
                                maxLength: 100,
                                anchor: '90%'
                            }, cbmaspelprop, cbmaspelkot, cbmaspelkec, cbmaspelkel
                                    , {
                                        xtype: 'textarea',
                                        fieldLabel: 'Alamat Kirim  <span class="asterix">*</span>',
                                        name: 'alamat_kirim',
                                        allowBlank: false,
                                        id: 'id_maspelalamat_kirim',
                                        maxLength: 255,
                                        anchor: '90%'
                                    }, {
                                xtype: 'textarea',
                                fieldLabel: 'Alamat Tagih ',
                                name: 'alamat_tagih',
                                allowBlank: true,
                                id: 'id_maspelalamat_tagih',
                                maxLength: 255,
                                anchor: '90%'
                            }]}, {
                        items: [
                            cb_kd_cabang,
                            {
                                type: 'textfield',
                                fieldLabel: 'Nama PIC <span class="asterix">*</span>',
                                name: 'nama_pic',
                                allowBlank: false,
                                id: 'id_maspelnama_pic',
                                maxLength: 25,
                                anchor: '90%'
                            }, {
                                xtype: 'numberfield',
                                fieldLabel: 'Telepon PIC',
                                name: 'no_telp_pic',
                                allowBlank: true,
                                id: 'id_maspelno_telp_pic',
                                maxLength: 25,
                                anchor: '90%'
                            }, new Ext.form.Checkbox({
                                xtype: 'checkbox',
                                fieldLabel: 'PKP <span class="asterix">*</span>',
                                boxLabel: 'Ya',
                                name: 'is_pkp',
                                id: 'maspel_is_pkp',
                                inputValue: '1',
                                autoLoad: true
                            }), {
                                xtype: 'textfield',
                                fieldLabel: 'NPWP <span class="asterix">*</span>',
                                name: 'npwp',
                                id: 'id_maspel_npwp',
                                anchor: '90%',
                                minLength: 15,
                                maxLength: 20,
                                listeners: {
                                    'blur': function() {
                                        var no_npwp = Ext.getCmp('id_maspel_npwp').getValue();
                                        if (no_npwp.length == 15) {
                                            no_npwp = no_npwp.replace("-", "");
                                            no_npwp = no_npwp.replace(".", "");
                                            //format xx.xxx.xxx.x-xxx.xxx
                                            Ext.getCmp('id_maspel_npwp').setValue(
                                                    no_npwp.substr(0, 2) + '.' +
                                                    no_npwp.substr(2, 3) + '.' +
                                                    no_npwp.substr(5, 3) + '.' +
                                                    no_npwp.substr(8, 1) + '-' +
                                                    no_npwp.substr(9, 3) + '.' +
                                                    no_npwp.substr(12)
                                                    );
                                        }
                                    }
                                }
                            }, {
                                xtype: 'textarea',
                                fieldLabel: 'Alamat NPWP <span class="asterix">*</span>',
                                name: 'alamat_npwp',
                                id: 'id_maspelalamat_npwp',
                                maxLength: 255,
                                anchor: '90%'
                            }, {
                                xtype: 'numberfield',
                                fieldLabel: 'Kode Pos ',
                                name: 'kodepos',
                                allowBlank: true,
                                id: 'id_maspelkodepos',
                                maxLength: 6,
                                anchor: '90%'
                            }, {
                                xtype: 'numberfield',
                                fieldLabel: 'TOP Dist',
                                name: 'top_dist',
                                allowBlank: true,
                                id: 'id_maspeltop_dist',
                                style: 'text-align:right;',
                                maxLength: 25,
                                anchor: '90%'
                            }, {
                                xtype: 'numericfield',
                                currencySymbol: '',
                                fieldLabel: 'Limit Dist',
                                name: 'limit_dist',
                                allowBlank: true,
                                format: '0,0',
                                id: 'id_maspellimit_dist',
                                style: 'text-align:right;',
                                maxLength: 25,
                                anchor: '90%'
                            }, {
                                xtype: 'combo',
                                fieldLabel: 'Tipe Pelanggan',
                                mode: 'local',
                                value: '0',
                                triggerAction: 'all',
                                forceSelection: true,
                                editable: false,
                                name: 'tipe',
                                id: 'mp_tipe',
                                hiddenName: 'tipe',
                                displayField: 'name',
                                valueField: 'value',
                                anchor: '90%',
                                store: new Ext.data.JsonStore({
                                    fields: ['name', 'value'],
                                    data: [
                                        {name: 'Agen', value: '0'},
                                        {name: 'Toko', value: '1'},
                                        {name: 'Modern Market', value: '2'},
                                    ]
                                })
                            }, cboMasterPelArea, new Ext.form.Checkbox({
                                xtype: 'checkbox',
                                fieldLabel: 'Status Aktif <span class="asterix">*</span>',
                                boxLabel: 'Ya',
                                name: 'aktif',
                                id: 'maspel_aktif',
                                inputValue: '1',
                                autoLoad: true
                            }
                            )]
                    }],
                buttons: [{
                        text: 'Submit',
                        id: 'btnsubmitmasterpelanggan',
                        formBind: true,
                        scope: this,
                        handler: this.submit
                    }, {
                        text: 'Reset',
                        id: 'btnresetmasterpelanggan',
                        scope: this,
                        handler: this.reset
                    }, {
                        text: 'Close',
                        id: 'btnClose',
                        scope: this,
                        handler: function() {
                            winaddmasterpelanggan.hide();
                        }
                    }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));

            // call parent
            masterpelangganform.Form.superclass.initComponent.apply(this, arguments);

        } // eo function initComponent
        ,
        onRender: function() {

            // call parent
            masterpelangganform.Form.superclass.onRender.apply(this, arguments);

            // set wait message target
            this.getForm().waitMsgTarget = this.getEl();

            // loads form after initial layout
            // this.on('afterlayout', this.onLoadClick, this, {single:true});

        } // eo function onRender
        ,
        reset: function() {
            this.getForm().reset();
        },
        submit: function() {
            var text = Ext.getCmp('btnsubmitmasterpelanggan').getText();
            if (Ext.getCmp('maspel_is_pkp').getValue() == 1) {
                if (Ext.getCmp('id_maspel_npwp').getValue() == ''
                        || Ext.getCmp('id_maspelalamat_npwp').getValue() == '') {
                    Ext.Msg.show({
                        title: 'Error',
                        msg: 'Pelanggan PKP harus mencantumkan Nomor dan alamat NPWP!!',
                        modal: true,
                        icon: Ext.Msg.ERROR,
                        buttons: Ext.Msg.OK
                    });
                    return;
                }
            }
            if (text == 'Update') {
                Ext.Msg.show({
                    title: 'Confirm',
                    msg: 'Are you sure update selected row ?',
                    buttons: Ext.Msg.YESNO,
                    fn: function(btn) {
                        if (btn == 'yes') {
                            Ext.getCmp('id_formaddmasterpelanggan').getForm().submit({
                                url: Ext.getCmp('id_formaddmasterpelanggan').url,
                                scope: this,
                                success: Ext.getCmp('id_formaddmasterpelanggan').onSuccess,
                                failure: Ext.getCmp('id_formaddmasterpelanggan').onFailure,
                                params: {
                                    cmd: 'save'
                                },
                                waitMsg: 'Saving Data...'
                            });
                        }
                    }
                })
            } else {
                Ext.getCmp('id_formaddmasterpelanggan').getForm().submit({
                    url: Ext.getCmp('id_formaddmasterpelanggan').url,
                    scope: this,
                    success: Ext.getCmp('id_formaddmasterpelanggan').onSuccess,
                    failure: Ext.getCmp('id_formaddmasterpelanggan').onFailure,
                    params: {
                        cmd: 'save'
                    },
                    waitMsg: 'Saving Data...'
                });
            }
        } // eo function submit
        ,
        onSuccess: function(form, action) {
            Ext.Msg.show({
                title: 'Success',
                msg: 'Form submitted successfully',
                modal: true,
                icon: Ext.Msg.INFO,
                buttons: Ext.Msg.OK
            });


            strmasterpelanggan.reload();
            Ext.getCmp('id_formaddmasterpelanggan').getForm().reset();
            winaddmasterpelanggan.hide();
        } // eo function onSuccess
        ,
        onFailure: function(form, action) {

            var fe = Ext.util.JSON.decode(action.response.responseText);
            this.showError(fe.errMsg || '');


        } // eo function onFailure
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
    Ext.reg('formaddmasterpelanggan', masterpelangganform.Form);

    var winaddmasterpelanggan = new Ext.Window({
        id: 'id_maspelwinaddmasterpelanggan',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        autoScroll: true,
        items: {
            id: 'id_formaddmasterpelanggan',
            xtype: 'formaddmasterpelanggan'
        },
        onHide: function() {
            Ext.getCmp('id_formaddmasterpelanggan').getForm().reset();
        }
    });

    var searchmasterpelanggan = new Ext.app.SearchField({
        store: strmasterpelanggan,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchmasterpelanggan'
    });

    var tbmasterpelanggan = new Ext.Toolbar({
        items: [{
                text: 'Add',
                icon: BASE_ICONS + 'add.png',
                onClick: function() {
                    Ext.getCmp('btnresetmasterpelanggan').show();
                    Ext.getCmp('btnsubmitmasterpelanggan').setText('Submit');
                    winaddmasterpelanggan.setTitle('Add Form');
                    winaddmasterpelanggan.show();
                }
            }, '-', searchmasterpelanggan]
    });

    var cbGrid = new Ext.grid.CheckboxSelectionModel();

    // row actions
    var actionmasterpelanggan = new Ext.ux.grid.RowActions({
        header: 'Edit',
        autoWidth: false,
        locked: true,
        width: 30,
        actions: [{iconCls: 'icon-edit-record', qtip: 'Edit'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });
    var actionmasterpelanggandel = new Ext.ux.grid.RowActions({
        header: 'Delete',
        autoWidth: false,
        locked: true,
        width: 40,
        actions: [{iconCls: 'icon-delete-record', qtip: 'Delete'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });
    actionmasterpelanggan.on('action', function(grid, record, action, row, col) {
        var kd_pelanggan = record.get('kd_pelanggan');
        var kd_propinsi = record.get('kd_propinsi');
        var kd_kota = record.get('kd_kota');
        var kd_kecamatan = record.get('kd_kecamatan');
        var kd_kalurahan = record.get('kd_kalurahan');
        var kd_cabang = record.get('kd_cabang');

        switch (action) {
            case 'icon-edit-record':
                editmasterpelanggan(kd_pelanggan, kd_propinsi, kd_kota, kd_kecamatan, kd_kalurahan, kd_cabang);
                break;
            case 'icon-delete-record':
                Ext.Msg.show({
                    title: 'Confirm',
                    msg: 'Are you sure delete selected row ?',
                    buttons: Ext.Msg.YESNO,
                    fn: function(btn) {
                        if (btn == 'yes') {
                            Ext.Ajax.request({
                                url: '<?= site_url("master_pelanggan/delete_row") ?>',
                                method: 'POST',
                                params: {
                                    kd_pelanggan: kd_pelanggan
                                },
                                callback: function(opt, success, responseObj) {
                                    var de = Ext.util.JSON.decode(responseObj.responseText);
                                    if (de.success == true) {
                                        strmasterpelanggan.reload();
                                        strmasterpelanggan.load({
                                            params: {
                                                start: STARTPAGE,
                                                limit: ENDPAGE
                                            }
                                        });
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
                    }
                });
                break;

        }
    });

    //grid
    var masterpelanggan = new Ext.grid.EditorGridPanel({
        //id: 'masterpelanggan-gridpane;',
        id: 'masterpelanggan',
        frame: true,
        border: true,
        stripeRows: true,
        sm: cbGrid,
        store: strmasterpelanggan,
        loadMask: true,
        // title: 'Master Member',
        style: 'margin:0 auto;',
        height: 450,
        view: new Ext.ux.grid.LockingGridView(),
        colModel: new Ext.ux.grid.LockingColumnModel([actionmasterpelanggan, {
                header: "Kode Pelanggan",
                dataIndex: 'kd_pelanggan',
                locked: true,
                sortable: true,
                width: 90
            }, {
                header: "Nama Pelanggan",
                dataIndex: 'nama_pelanggan',
                sortable: true,
                locked: true,
                width: 150
            }, {
                header: "Aktif",
                dataIndex: 'aktif',
                sortable: true,
                width: 35
            }, {
                header: "Tipe",
                dataIndex: 'tipe_pelanggan',
                sortable: true,
                width: 80
            }, {
                header: "Telepon",
                dataIndex: 'no_telp',
                sortable: true,
                width: 100
            }, {
                header: "Cabang",
                dataIndex: 'nama_cabang',
                sortable: true,
                width: 150
            }, {
                header: "Nama PIC",
                dataIndex: 'nama_pic',
                sortable: true,
                width: 150
            }, {
                header: "Telepon PIC",
                dataIndex: 'no_telp_pic',
                sortable: true,
                width: 100
            }, {
                header: "Kalurahan",
                dataIndex: 'nama_kalurahan',
                sortable: true,
                width: 100
            }, {
                header: "Kecamatan",
                dataIndex: 'nama_kecamatan',
                sortable: true,
                width: 100
            }, {
                header: "Kota",
                dataIndex: 'nama_kota',
                sortable: true,
                width: 100
            }, {
                header: "Propinsi",
                dataIndex: 'nama_propinsi',
                sortable: true,
                width: 100
            }, {
                header: "NPWP",
                dataIndex: 'npwp',
                sortable: true,
                width: 100
            }, {
                header: "Alamat NPWP",
                dataIndex: 'alamat_npwp',
                sortable: true,
                width: 100
            }, {
                header: "PKP",
                dataIndex: 'pkp',
                sortable: true,
                width: 100
            }, {
                header: "Alamat Kirim",
                dataIndex: 'alamat_kirim',
                sortable: true,
                width: 100
            }, {
                header: "Alamat Tagih",
                dataIndex: 'alamat_tagih',
                sortable: true,
                width: 100
            }, {
                header: "No. Fax",
                dataIndex: 'no_fax',
                sortable: true,
                width: 100
            }, {
                header: "E-mail",
                dataIndex: 'email',
                sortable: true,
                width: 100
            }, {
                header: "TOP Dist",
                dataIndex: 'top_dist',
                sortable: true,
                width: 100
            }, {xtype: 'numbercolumn',
                header: "Limit Dist",
                dataIndex: 'limit_dist',
                sortable: true,
                format: '0,0',
                width: 100
            }, {
                header: "Kode Area",
                dataIndex: 'kd_area',
                sortable: true,
                width: 100
            }
        ]),
        plugins: [actionmasterpelanggan],
        listeners: {
            'rowdblclick': function() {
                var sm = masterpelanggan.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    editmasterpelanggan(sel[0].get('kd_pelanggan'), sel[0].get('kd_propinsi'), sel[0].get('kd_kota'),
                            sel[0].get('kd_kecamatan'));
                }
            }
        },
        tbar: tbmasterpelanggan,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strmasterpelanggan,
            displayInfo: true
        })
    });

    function editmasterpelanggan(kd_pelanggan, kd_propinsi, kd_kota, kd_kecamatan, kd_kalurahan, kd_cabang) {
        strmaspelcbmaspelprop.load();
        cbmaspelkel.store.proxy.conn.url = '<?= site_url("master_member/get_kel") ?>/' + kd_propinsi + '/' + kd_kota + '/' + kd_kecamatan;
        cbmaspelkel.store.reload();
        cbmaspelkec.store.proxy.conn.url = '<?= site_url("master_member/get_kec") ?>/' + kd_propinsi + '/' + kd_kota;
        cbmaspelkec.store.reload();
        cbmaspelkot.store.proxy.conn.url = '<?= site_url("master_member/get_kota") ?>/' + kd_propinsi;
        cbmaspelkot.store.reload();


        Ext.getCmp('btnresetmasterpelanggan').hide();
        Ext.getCmp('btnsubmitmasterpelanggan').setText('Update');
        winaddmasterpelanggan.setTitle('Edit Form');

        Ext.getCmp('id_formaddmasterpelanggan').getForm().load({
            url: '<?= site_url("master_pelanggan/get_row") ?>',
            params: {id: kd_pelanggan, cmd: 'get'},
            failure: function(form, action) {
                var de = Ext.util.JSON.decode(action.response.responseText);
                Ext.Msg.show({
                    title: 'Error', msg: de.errMsg, modal: true, icon: Ext.Msg.ERROR, buttons: Ext.Msg.OK,
                    fn: function(btn) {
                        if (btn == 'ok' && de.errMsg == 'Session Expired') {
                            window.location = '<?= site_url("auth/login") ?>';
                        }
                    }
                });
            }
        });
        winaddmasterpelanggan.show();
    }

    function deletemasterpelanggan() {
        var sm = masterpelanggan.getSelectionModel();
        var sel = sm.getSelections();
        if (sel.length > 0) {
            Ext.Msg.show({
                title: 'Confirm',
                msg: 'Are you sure delete selected row ?',
                buttons: Ext.Msg.YESNO,
                fn: function(btn) {
                    if (btn == 'yes') {

                        var data = '';
                        for (i = 0; i < sel.length; i++) {
                            data = data + sel[i].get('kd_pelanggan') + ';';
                        }

                        Ext.Ajax.request({
                            url: '<?= site_url("master_pelanggan/delete_rows") ?>',
                            method: 'POST',
                            params: {
                                postdata: data
                            },
                            callback: function(opt, success, responseObj) {
                                var de = Ext.util.JSON.decode(responseObj.responseText);
                                if (de.success == true) {
                                    strmasterpelanggan.reload();
                                    strmasterpelanggan.load({
                                        params: {
                                            start: STARTPAGE,
                                            limit: ENDPAGE
                                        }
                                    });
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
                }
            });
        }
        else {
            Ext.Msg.show({
                title: 'Info',
                msg: 'Please selected row',
                modal: true,
                icon: Ext.Msg.ERROR,
                buttons: Ext.Msg.OK
            });
        }

    }
</script>
