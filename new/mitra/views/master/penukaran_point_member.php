<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">
    /**
     * deklarasi store combo member
     */
    var storeComboMember_ppm = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_member', 'nmmember', 'alamat_penagihan', 'telepon', 'hp', 'email', 'total_point', 'jenis'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("penukaran_point_member_controller/finalGetDataMember") ?>',
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

    /**
     * deklarasi store grid penukaran point / main grid
     */
    var storeGridPenukaranPoint_ppm = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_barang', 'nama_produk', 'qty', 'jumlah_point', 'qty_tukar', 'jumlah_point_tukar', 'no_bukti'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("penukaran_point_member_controller/finalGetDataPenukaranPoint") ?>',
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

    storeGridPenukaranPoint_ppm.load();

    var searchGridMember_ppm = new Ext.app.SearchField({
        store: storeComboMember_ppm,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_search_grid_member_ppm'
    });

    var searchGridPenukaranPointMember_ppm = new Ext.app.SearchField({
        store: storeGridPenukaranPoint_ppm,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        id: 'id_search_grid_penukaran_point_member'
    });

    var topToolbarGridPenukaranPoint_ppm = new Ext.Toolbar({
        items: [searchGridPenukaranPointMember_ppm]
    });

    /**
     * deklarasi grid member selection model
     */
    var smGridMember_ppm = new Ext.grid.CheckboxSelectionModel();

    /**
     * deklarasi grid penukaran point selection model
     */
    var smGridPenukaranPoint_ppm = new Ext.grid.CheckboxSelectionModel();


    /**
     * deklarasi grid member
     */
    var gridMember_ppm = new Ext.grid.GridPanel({
        store: storeComboMember_ppm,
        stripeRows: true,
        frame: true,
        border: true,
        sm: smGridMember_ppm,
        columns: [{
                header: 'Kode Member',
                dataIndex: 'kd_member',
                width: 120,
                sortable: true
            }, {
                header: 'Nama Member',
                dataIndex: 'nmmember',
                width: 120,
                sortable: true
            }, {
                header: 'Alamat Tagih',
                dataIndex: 'alamat_tagih',
                width: 150,
                sortable: true
            }, {
                header: 'Jenis Member',
                dataIndex: 'jenis',
                width: 120,
                sortable: true
            }, {
                header: 'Telepon',
                dataIndex: 'telepon',
                width: 150,
                sortable: true
            }, {
                header: 'Hp',
                dataIndex: 'hp',
                width: 150,
                sortable: true
            }, {
                header: 'Email',
                dataIndex: 'email',
                width: 150,
                sortable: true
            }, {
                header: 'Total Point',
                dataIndex: 'total_point',
                width: 150,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchGridMember_ppm]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: storeComboMember_ppm,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                Ext.getCmp('id_txt_nama_member_ppm').setValue(sel[0].get('nmmember'));
                Ext.getCmp('id_txt_point_member_ppm').setValue(sel[0].get('total_point'));
                Ext.getCmp('id_combo_member_ppm').setValue(sel[0].get('kd_member'));
                menuMember_ppm.hide();
            }
        }
    });

    /**
     * deklarasi menu combo member
     */
    var menuMember_ppm = new Ext.menu.Menu();
    menuMember_ppm.add(new Ext.Panel({
        title: 'Pilih Member',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridMember_ppm],
        buttons: [{
                text: 'Close',
                handler: function() {
                    //filter berdasar jumlah poin yang dimiliki member
                    storeGridPenukaranPoint_ppm.load();
                    menuMember_ppm.hide();
                }
            }]
    }));


    /**
     * deklarasi twin combo member
     * @returns {undefined} */
    Ext.ux.TwincomboMember_ppm = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            storeComboMember_ppm.load();
            menuMember_ppm.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    menuMember_ppm.on('hide', function() {
        var sf = Ext.getCmp('id_search_grid_member_ppm').getValue();
        if (sf !== '') {
            Ext.getCmp('id_search_grid_member_ppm').setValue('');
            searchGridMember_ppm.onTrigger2Click();
        }
    });
    var comboMember_ppm = new Ext.ux.TwincomboMember_ppm({
        fieldLabel: 'Member <span class="asterix">*</span>',
        id: 'id_combo_member_ppm',
        store: storeComboMember_ppm,
        mode: 'local',
        valueField: 'kd_member',
        displayField: 'kd_member',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        //anchor: '95%',
        //width: 200,
        hiddenName: 'kd_member',
        emptyText: 'Pilih Member'
    });

    /**
     * top  header penukaran point member
     */
    var headerPenukaranPointMember_ppm = {
        layout: 'column',
        border: false,
        buttonAlign: 'left',
        style: 'padding:10px',
        frame: false,
        items: [{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [
                    comboMember_ppm,
                    {
                        xtype: 'textfield',
                        fieldLabel: 'Nama',
                        name: 'txt_nama_member_ppm',
                        id: 'id_txt_nama_member_ppm',
                        readOnly: true,
                        fieldClass: 'readonly-input'
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'Point',
                        name: 'txt_point_member_ppm',
                        id: 'id_txt_point_member_ppm',
                        readOnly: true,
                        fieldClass: 'readonly-input'
                    }
                ]
            }]
    };

    var editorGridPointMember_ppm = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });

    /**
     * deklarasi grid penukaran point
     */
    var gridPenukaranPointMember_ppm = new Ext.grid.GridPanel({
        store: storeGridPenukaranPoint_ppm,
        stripeRows: true,
        frame: true,
        border: true,
        sm: smGridPenukaranPoint_ppm,
        height: 270,
        plugins: [editorGridPointMember_ppm],
        columns: [smGridPenukaranPoint_ppm, {
            }, {
                header: 'Kode Barang',
                dataIndex: 'kd_barang',
                width: 120,
                sortable: true
            }, {
                header: 'Nama Barang',
                dataIndex: 'nama_produk',
                width: 250,
                sortable: true
            }, {
                header: 'Qty',
                dataIndex: 'qty',
                width: 150,
                sortable: true
            }, {
                header: 'Jumlah Point',
                dataIndex: 'jumlah_point',
                width: 120,
                sortable: true,
                editor: new Ext.form.NumberField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'id_cel_grid_jml_point_ppm'
                })
            }, {
                header: 'Qty Tukar',
                dataIndex: 'qty_tukar',
                width: 150,
                sortable: true,
                editor: {
                    xtype: 'numberfield',
                    id: 'id_qty_tukar_value_ppm',
                    allowBlank: false,
                    selectOnFocus: true,
                    listeners: {
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                if (Ext.getCmp('id_txt_nama_member_ppm').getValue() == '') {
                                    Ext.Msg.show({
                                        title: 'Error',
                                        msg: 'Silahkan pilih member terlebih dulu',
                                        modal: true,
                                        icon: Ext.Msg.ERROR,
                                        buttons: Ext.Msg.OK
                                    });
                                    this.setValue('1');

                                    return;
                                }
                                var jumlah = this.getValue() * Ext.getCmp('id_cel_grid_jml_point_ppm').getRawValue();
                                Ext.getCmp('id_cel_grid_point_tukar_ppm').setValue(jumlah);

                                var sm = gridPenukaranPointMember_ppm.getSelectionModel();
                                var sel = sm.getSelections();
                                var i = 0;
                                var dataPointTukar = 0;
                                if (sel.length > 0) {
                                    while (i < sel.length) {
                                        dataPointTukar = Number(dataPointTukar) + Number(jumlah);
                                        i++;
                                    }
                                    Ext.getCmp('id_txt_total_point_tukar_ppm').setValue(dataPointTukar);
                                    Ext.getCmp('id_txt_sisa_point_ppm').setValue(Ext.getCmp('id_txt_point_member_ppm').getValue() - dataPointTukar);
                                }

                            }, c);
                        },
                    }
                }
            }, {
                header: 'Point Tukar',
                dataIndex: 'jumlah_point_tukar',
                width: 120,
                sortable: true,
                editor: new Ext.form.NumberField({
                    readOnly: true,
                    fieldClass: 'readonly-input',
                    id: 'id_cel_grid_point_tukar_ppm'
                })
            }],
        tbar: new Ext.Toolbar({
            items: [topToolbarGridPenukaranPoint_ppm]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: storeGridPenukaranPoint_ppm,
            displayInfo: true
        }),
        listeners: {
            'rowclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                var i = 0;
                var dataPointTukar = 0;
                if (sel.length > 0) {
                    while (i < sel.length) {
                        dataPointTukar += sel[i].get('jumlah_point_tukar');
                        i++;
                    }
                    Ext.getCmp('id_txt_total_point_tukar_ppm').setValue(dataPointTukar);
                    Ext.getCmp('id_txt_sisa_point_ppm').setValue(Ext.getCmp('id_txt_point_member_ppm').getValue() - dataPointTukar);
                }

            }
        }
    });

    /**
     * body header penukaran point member
     */
    var bodyPenukaranPointMember_ppm = {
        layout: 'form',
        border: false,
        labelWidth: 100,
        style: 'padding:10px',
        defaults: {labelSeparator: ''},
        items: [
            gridPenukaranPointMember_ppm
        ]
    };

    /**
     * footer header penukaran point member
     */
    var footerPenukaranPointMember_ppm = {
        layout: 'column',
        border: false, labelWidth: 100,
        frame: false,
        style: 'padding:10px',
        defaults: {labelSeparator: ''},
        items: [
            {
                border: false,
                frame: false,
                height: 80,
                items: [
                    {
                        xtype: 'compositefield',
                        style: 'margin-top:10px',
                        items: [
                            {
                                xtype: 'displayfield',
                                value: 'Jumlah Point Ditukar :',
                                width: 140
                            }, {
                                xtype: 'textfield',
                                fieldLabel: 'Jumlah Point Ditukar',
                                id: 'id_txt_total_point_tukar_ppm',
                                name: 'txt_total_point_tukar_ppm',
                                readOnly: true,
                                fieldClass: 'readonly-input'
                            }
                        ]
                    },
                    {
                        xtype: 'compositefield',
                        style: 'margin-top:10px',
                        items: [
                            {
                                xtype: 'displayfield',
                                value: 'Sisa Point :',
                                width: 140
                            }, {
                                xtype: 'textfield',
                                fieldLabel: 'Sisa Point',
                                id: 'id_txt_sisa_point_ppm',
                                name: 'txt_sisa_point_ppm',
                                readOnly: true,
                                fieldClass: 'readonly-input'
                            }
                        ]
                    }
                ]
            }
        ],
        buttons: [
            {
                text: 'Save',
                handler: function() {
                    collectionPenukaranPointProcessing_ppm();
                }
            },
            {
                text: 'Reset',
                handler: function() {
                    Ext.getCmp('penukaran_point_member').getForm().reset();
                    storeGridPenukaranPoint_ppm.load();
                }
            }
        ]
    };
    /**
     * declaration of the main panel of this form      */
    Ext.ns('penukaran_point_member');
    var penukaranPointMember_ppm = new Ext.FormPanel({
        id: 'penukaran_point_member',
        border: false,
        frame: true,
        autoScroll: true,
        items: [
            headerPenukaranPointMember_ppm, bodyPenukaranPointMember_ppm, footerPenukaranPointMember_ppm
        ]
    });

    /**
     * processing form method declaration
     */
    function collectionPenukaranPointProcessing_ppm() {
        var box = Ext.MessageBox.wait('saving ' + 'data', 'Please Wait.....');
        var src = smGridPenukaranPoint_ppm.getSelections();
        var data = [];
        for (var i = 0; i < src.length; i++) {
            data[i] = src[i].data;
        }
        Ext.Ajax.request({
            url: '<?= site_url("penukaran_point_member_controller/finalProcessing") ?>',
            method: 'POST',
            waitMsg: 'Processing Data...',
            params: {
                cmd: 'save',
                kd_member: Ext.getCmp('id_combo_member_ppm').getValue(),
                point_member: Ext.getCmp('id_txt_point_member_ppm').getValue(),
                jumlah_point_ditukar: Ext.getCmp('id_txt_total_point_tukar_ppm').getValue(),
                data: Ext.util.JSON.encode(data),
            },
            callback: function(opt, success, responseObj) {
                var de = Ext.util.JSON.decode(responseObj.responseText);
                if (de.success == true) {
                    box.hide();
                    Ext.Msg.show({
                        title: 'Success',
                        msg: 'Submitted Successfully',
                        modal: true,
                        icon: Ext.Msg.INFO,
                        buttons: Ext.Msg.OK,
                        fn: function(btn) {
                            if (btn == 'ok') {
                                winentrypenukaranpoint.show();
                                Ext.getDom('entrypenukaranpointprint').src = de.printUrl;
                            }
                        }
                    });
                    penukaranPointMember_ppm.getForm().reset();
                    storeComboMember_ppm.removeAll();
                    storeGridPenukaranPoint_ppm.load();   
                } else { 
                    box.hide();
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

    var winentrypenukaranpoint = new Ext.Window({
        id: 'id_winentrypenukaranpoint',
        title: 'Print Penukaran Point',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="entrypenukaranpointprint" src=""></iframe>'
    });
</script>
