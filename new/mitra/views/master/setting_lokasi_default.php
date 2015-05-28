<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
	var strsetlokasidefault = new Ext.data.Store({
		autoSave:false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_produk', allowBlank: false, type: 'text'},
                {name: 'kd_produk_lama', allowBlank: false, type: 'text'},
                {name: 'nama_produk', allowBlank: false, type: 'text'},
                {name: 'nm_satuan', allowBlank: false, type: 'text'},
                {name: 'kd_peruntukkan', allowBlank: true, type: 'text'},
                {name: 'kd_lokasi', allowBlank: true, type: 'text'},
                {name: 'kd_blok', allowBlank: true, type: 'text'},
                {name: 'kd_sub_blok', allowBlank: true, type: 'text'},
                {name: 'lokasi_default', allowBlank: true, type: 'text'},
                {name: 'flag_lokasi', allowBlank: true, type: 'text'}
            ],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("set_lokasi_default/search_produk_by_kategori") ?>',
            method: 'POST'
        }),
        writer: new Ext.data.JsonWriter(
        {
			encode: true,
			writeAllFields: true
        })
    });

	/* START FORM */

	var strsetlokasidefaultdetail = new Ext.data.Store({
		autoSave:false,
        reader: new Ext.data.JsonReader({
            fields: [
				{name: 'no_bpl', allowBlank: false, type: 'text'},
				{name: 'kd_produk', allowBlank: false, type: 'text'},
				{name: 'kd_lokasi', allowBlank: false, type: 'text'},
				{name: 'nama_lokasi', allowBlank: false, type: 'text'},
				{name: 'kd_blok', allowBlank: false, type: 'text'},
				{name: 'nama_blok', allowBlank: false, type: 'text'},
				{name: 'kd_sub_blok', allowBlank: false, type: 'text'},
				{name: 'nama_sub_blok', allowBlank: false, type: 'text'},
				{name: 'keterangan', allowBlank: false, type: 'text'},
				{name: 'kd_peruntukan', allowBlank: false, type: 'text'},
			],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("barang_per_lokasi/get_detail") ?>',
            method: 'POST'
        }),
        writer: new Ext.data.JsonWriter(
        {
			encode: true,
			writeAllFields: true
        })
    });

	// combobox lokasi
	var str_cblokasibpl = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_lokasi', 'nama_lokasi'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("blok_lokasi/get_all") ?>',
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

    var cblokasibpl = new Ext.form.ComboBox({
        fieldLabel: 'Nama Lokasi <span class="asterix">*</span>',
        id: 'bpl_cblokasi',
        store: str_cblokasibpl,
        valueField: 'kd_lokasi',
        displayField: 'nama_lokasi',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_lokasi',
        emptyText: 'Pilih Lokasi',
        listeners: {
            select: function(combo, records) {
                var kd_cblokasi = this.getValue();
                cbblokbpl.setValue();
                cbblokbpl.store.proxy.conn.url = '<?= site_url("sub_blok_lokasi/get_blok") ?>/' + kd_cblokasi;
                cbblokbpl.store.reload();
            }
        }
    });

    // combobox blok
    var strcbblokbpl = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_blok', 'nama_blok'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("sub_blok_lokasi/get_blok") ?>',
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

    var cbblokbpl = new Ext.form.ComboBox({
        fieldLabel: 'Nama Blok <span class="asterix">*</span>',
        id: 'bpl_cbblok',
        mode: 'local',
        store: strcbblokbpl,
        valueField: 'kd_blok',
        displayField: 'nama_blok',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_blok',
        emptyText: 'Pilih Blok',
        listeners: {
            select: function(combo, records) {
                var kd_cblokasi = Ext.getCmp('bpl_cblokasi').getValue();
                var kd_cbblok = this.getValue();
                cbsubblokbpl.setValue();
                cbsubblokbpl.store.proxy.conn.url = '<?= site_url("sub_blok_lokasi/get_sub_blok") ?>/' + kd_cblokasi + '/' + kd_cbblok;
                cbsubblokbpl.store.reload();
            }
        }
    });

    // combobox subblok
    var strcbsubblokbpl = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_sub_blok', 'nama_sub_blok'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("sub_blok_lokasi/get_sub_blok") ?>',
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

    var cbsubblokbpl = new Ext.form.ComboBox({
        fieldLabel: 'Nama Sub Blok <span class="asterix">*</span>',
        id: 'bpl_cbsubblok',
        mode: 'local',
        store: strcbsubblokbpl,
        valueField: 'kd_sub_blok',
        displayField: 'nama_sub_blok',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_sub_blok',
        emptyText: 'Pilih Sub Blok'
    });

    Ext.ns('setlokasidefaultform');
    setlokasidefaultform.Form = Ext.extend(Ext.form.FormPanel, {

        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 100,
        url: '<?= site_url("barang_per_lokasi/update_row") ?>',
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
            setlokasidefaultform.Form.superclass.constructor.call(this, config);
        },
        initComponent: function(){

            // hard coded - cannot be changed from outsid
            var config = {
                defaultType: 'textfield',
                defaults: { labelSeparator: ''},
                monitorValid: true,
                autoScroll: false // ,buttonAlign:'right'
                ,
                items: [{
                    xtype: 'hidden',
                    name: 'kd_produk',
					id: 'grid_kd_produk'
                },{
                    xtype: 'hidden',
                    name: 'kd_lokasi'
                },{
                    xtype: 'hidden',
                    name: 'kd_blok'
                },{
                    xtype: 'hidden',
                    name: 'kd_sub_blok'
                }, cblokasibpl, cbblokbpl, cbsubblokbpl, {
                    xtype: 'textarea',
                    fieldLabel: 'Keterangan <span class="asterix">*</span>',
                    name: 'keterangan',
                    allowBlank: false,
                    id: 'bpl_keterangan',
					style:'text-transform: uppercase',
                    anchor: '90%'
                },{
					fieldLabel: 'Peruntukan <span class="asterix">*</span>',
					xtype: 'radiogroup',
					columnWidth: [.5, .5],
					allowBlank:false,
					items: [{
						boxLabel: 'Supermarket',
						name: 'kd_peruntukan',
						inputValue: '0',
						id: 'bpl_peruntukan_supermarket',
						checked:true
					}, {
						boxLabel: 'Distribusi',
						name: 'kd_peruntukan',
						inputValue: '1',
						id: 'bpl_peruntukan_distribusi'
					}]
				}],
                buttons: [{
                    text: 'Submit',
                    id: 'btnsubmitsetlokasidefault',
                    formBind: true,
                    scope: this,
                    handler: this.submit
                }, {
                    text: 'Reset',
                    id: 'btnresetsetlokasidefault',
                    scope: this,
                    handler: this.reset
                }, {
                    text: 'Close',
                    id: 'btnClose',
                    scope: this,
                    handler: function(){
                        winaddsetlokasidefault.hide();
                    }
                }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));

            // call parent
            setlokasidefaultform.Form.superclass.initComponent.apply(this, arguments);

        } // eo function initComponent
        ,
        onRender: function(){

            // call parent
            setlokasidefaultform.Form.superclass.onRender.apply(this, arguments);

            // set wait message target
            this.getForm().waitMsgTarget = this.getEl();

            // loads form after initial layout
            // this.on('afterlayout', this.onLoadClick, this, {single:true});

        } // eo function onRender
        ,
        reset: function(){
            this.getForm().reset();
        },
        submit: function(){

            this.getForm().submit({
                url: this.url,
                scope: this,
                success: this.onSuccess,
                failure: this.onFailure,
                params: {
                    cmd: 'save'
                },
                waitMsg: 'Saving Data...'
            });
        } // eo function submit
        ,
        onSuccess: function(form, action){
            Ext.Msg.show({
                title: 'Success',
                msg: 'Form submitted successfully',
                modal: true,
                icon: Ext.Msg.INFO,
                buttons: Ext.Msg.OK
            });


            strsetlokasidefaultdetail.load({
					params:{
						kd_produk: Ext.getCmp('grid_kd_produk').getValue()
					}
				});
            Ext.getCmp('id_formaddsetlokasidefault').getForm().reset();
            winaddsetlokasidefault.hide();
        } // eo function onSuccess
        ,
        onFailure: function(form, action){

            var fe = Ext.util.JSON.decode(action.response.responseText);
            this.showError(fe.errMsg || '');


        } // eo function onFailure
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
                    if (btn == 'ok' && msg == 'Session Expired') {
                        window.location = '<?= site_url("auth/login") ?>';
                    }
                }
            });
        }
    }); // eo extend
    // register xtype
    Ext.reg('formaddsetlokasidefault', setlokasidefaultform.Form);

    var winaddsetlokasidefault = new Ext.Window({
        id: 'id_winaddsetlokasidefault',
        closeAction: 'hide',
        width: 450,
        height: 350,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_formaddsetlokasidefault',
            xtype: 'formaddsetlokasidefault'
        },
        onHide: function(){
            Ext.getCmp('id_formaddsetlokasidefault').getForm().reset();
        }
    });
	// combobox kategori1
    var str_sld_cbkategori1 = new Ext.data.Store({
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
				var r = new (str_sld_cbkategori1.recordType)({
					'kd_kategori1': '',
					'nama_kategori1': '-----'
				});
				str_sld_cbkategori1.insert(0, r);
			},
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var sld_cbkategori1 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 1 <span class="asterix">*</span>',
        id: 'sld_cbkategori1',
        store: str_sld_cbkategori1,
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
                var kdsld_cbkategori1 = sld_cbkategori1.getValue();
                // sld_cbkategori2.setValue();
                sld_cbkategori2.store.proxy.conn.url = '<?= site_url("kategori3/get_kategori2") ?>/' + kdsld_cbkategori1;
                sld_cbkategori2.store.reload();
				strsetlokasidefault.load({
					params:{
						kd_kategori1: Ext.getCmp('sld_cbkategori1').getValue(),
						kd_kategori2: Ext.getCmp('sld_cbkategori2').getValue(),
						kd_kategori3: Ext.getCmp('sld_cbkategori3').getValue(),
						kd_kategori4: Ext.getCmp('sld_cbkategori4').getValue()
					}
				});
            }
        }
    });
    // combobox kategori2
    var str_sld_cbkategori2 = new Ext.data.Store({
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
				var r = new (str_sld_cbkategori2.recordType)({
					'kd_kategori2': '',
					'nama_kategori2': '-----'
				});
				str_sld_cbkategori2.insert(0, r);
			},
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var sld_cbkategori2 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 2 <span class="asterix">*</span>',
        id: 'sld_cbkategori2',
	mode: 'local',
        store: str_sld_cbkategori2,
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
                var kd_sld_cbkategori1 = sld_cbkategori1.getValue();
                var kd_sld_cbkategori2 = this.getValue();
                sld_cbkategori3.setValue();
                sld_cbkategori3.store.proxy.conn.url = '<?= site_url("kategori4/get_kategori3") ?>/' + kd_sld_cbkategori1 +'/'+ kd_sld_cbkategori2;
                sld_cbkategori3.store.reload();
				strsetlokasidefault.load({
					params:{
						kd_kategori1: Ext.getCmp('sld_cbkategori1').getValue(),
						kd_kategori2: Ext.getCmp('sld_cbkategori2').getValue(),
						kd_kategori3: Ext.getCmp('sld_cbkategori3').getValue(),
						kd_kategori4: Ext.getCmp('sld_cbkategori4').getValue()
					}
				});
            }
        }
	});

    // combobox kategori3
    var str_sld_cbkategori3 = new Ext.data.Store({
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
				var r = new (str_sld_cbkategori3.recordType)({
					'kd_kategori3': '',
					'nama_kategori3': '-----'
				});
				str_sld_cbkategori3.insert(0, r);
			},
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var sld_cbkategori3 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 3 <span class="asterix">*</span>',
        id: 'sld_cbkategori3',
        mode: 'local',
        store: str_sld_cbkategori3,
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
                var kd_sld_cbkategori1 = sld_cbkategori1.getValue();
                var kd_sld_cbkategori2 = sld_cbkategori2.getValue();
                var kd_sld_cbkategori3 = this.getValue();
                sld_cbkategori4.setValue();
                sld_cbkategori4.store.proxy.conn.url = '<?= site_url("master_barang/get_kategori4") ?>/' + kd_sld_cbkategori1 +'/'+ kd_sld_cbkategori2 +'/'+ kd_sld_cbkategori3;
                sld_cbkategori4.store.reload();
				strsetlokasidefault.load({
					params:{
						kd_kategori1: Ext.getCmp('sld_cbkategori1').getValue(),
						kd_kategori2: Ext.getCmp('sld_cbkategori2').getValue(),
						kd_kategori3: Ext.getCmp('sld_cbkategori3').getValue(),
						kd_kategori4: Ext.getCmp('sld_cbkategori4').getValue()
					}
				});

            }
        }
    });

    // combobox kategori4
    var str_sld_cbkategori4 = new Ext.data.Store({
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
				var r = new (str_sld_cbkategori4.recordType)({
					'kd_kategori4': '',
					'nama_kategori4': '-----'
				});
				str_sld_cbkategori4.insert(0, r);
			},
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var sld_cbkategori4 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 4 <span class="asterix">*</span>',
        id: 'sld_cbkategori4',
        mode: 'local',
        store: str_sld_cbkategori4,
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
				strsetlokasidefault.load({
					params:{
						kd_kategori1: Ext.getCmp('sld_cbkategori1').getValue(),
						kd_kategori2: Ext.getCmp('sld_cbkategori2').getValue(),
						kd_kategori3: Ext.getCmp('sld_cbkategori3').getValue(),
						kd_kategori4: Ext.getCmp('sld_cbkategori4').getValue()
					}
				});

            }
        }
    });

    // twin combo lokasi
    var strcb_sld_cdlokasi = new Ext.data.ArrayStore({
        fields: ['kd_lokasi'],
        data : []
    });

    var strgrid_sld_cdlokasi = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_lokasi','nama_lokasi', 'kd_blok', 'kd_sub_blok', 'nama_lokasi', 'peruntukan', 'flag_default'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("set_lokasi_default/search_all_lokasi") ?>',
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

    var searchgrid_sld_cdlokasi = new Ext.app.SearchField({
        store: strgrid_sld_cdlokasi,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgrid_sld_cdlokasi'
    });


    var grid_sld_cdlokasi = new Ext.grid.GridPanel({
        store: strgrid_sld_cdlokasi,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'Kode Lokasi',
                dataIndex: 'kd_lokasi',
                width: 100,
                sortable: true
            },{
                header: 'Peruntukan',
                dataIndex: 'peruntukan',
                width: 100,
                sortable: true
            },{
                header: 'Nama Lokasi',
                dataIndex: 'nama_lokasi',
                width: 450,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchgrid_sld_cdlokasi]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgrid_sld_cdlokasi,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_sld_cdlokasi').setValue(sel[0].get('kd_lokasi'));
                    Ext.getCmp('id_sld_cdlokasi').setRawValue(sel[0].get('nama_lokasi'));
                    Ext.getCmp('kd_lokasi_default').setValue(sel[0].get('kd_lokasi'));
                    Ext.getCmp('kd_blok_lokasi_default').setValue(sel[0].get('kd_blok'));
                    Ext.getCmp('kd_subblok_lokasi_default').setValue(sel[0].get('kd_sub_blok'));

                    menu_sld_cdlokasi.hide();

                }
            }
        }
    });

    var menu_sld_cdlokasi = new Ext.menu.Menu();
    menu_sld_cdlokasi.add(new Ext.Panel({
        title: 'Pilih Nama Lokasi',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 700,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [grid_sld_cdlokasi],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menu_sld_cdlokasi.hide();
                }
            }]
    }));

    Ext.ux.TwinComboSldComboLokasi = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgrid_sld_cdlokasi.load();
            menu_sld_cdlokasi.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menu_sld_cdlokasi.on('hide', function(){
        var sf = Ext.getCmp('id_searchgrid_sld_cdlokasi').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgrid_sld_cdlokasi').setValue('');
            searchgrid_sld_cdlokasi.onTrigger2Click();
        }
    });


    var sld_cdlokasi = new Ext.ux.TwinComboSldComboLokasi({
        fieldLabel: 'Nama Lokasi',
        id: 'id_sld_cdlokasi',
        store: strcb_sld_cdlokasi,
        mode: 'local',
        valueField: 'kd_lokasi',
        displayField: 'nama_lokasi',
        width:300,
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        anchor: '100%',
        hiddenName: 'kd_lokasi',
        emptyText: 'Pilih Nama Lokasi'

    });
    //end twincombolokasi

    // twin combo lokasi grid
    var strcb_sld_cdlokasigrid = new Ext.data.ArrayStore({
        fields: ['kd_lokasi', 'nama_lokasi'],
        data : []
    });

    var strgrid_sld_cdlokasigrid = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_lokasi','nama_lokasi', 'kd_blok', 'kd_sub_blok', 'nama_lokasi', 'flag_default'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("set_lokasi_default/search_lokasi") ?>',
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

    var searchgrid_sld_cdlokasigrid = new Ext.app.SearchField({
        store: strgrid_sld_cdlokasigrid,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgrid_sld_cdlokasigrid'
    });


    var grid_sld_cdlokasigrid = new Ext.grid.GridPanel({
        store: strgrid_sld_cdlokasigrid,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'Kode Lokasi',
                dataIndex: 'kd_lokasi',
                width: 150,
                sortable: true
            },{
                header: 'Nama Lokasi',
                dataIndex: 'nama_lokasi',
                width: 300,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchgrid_sld_cdlokasigrid]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgrid_sld_cdlokasigrid,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_sld_cdlokasigrid').setValue(sel[0].get('nama_lokasi'));
                    Ext.getCmp('kd_lokasiGrid').setValue(sel[0].get('kd_lokasi'));
                    Ext.getCmp('kd_blokGrid').setValue(sel[0].get('kd_blok'));
                    Ext.getCmp('kd_sub_blokGrid').setValue(sel[0].get('kd_sub_blok'));
                    Ext.getCmp('editedGrid').setValue('Y');
                    menu_sld_cdlokasigrid.hide();
                }
            }
        }
    });

    var menu_sld_cdlokasigrid = new Ext.menu.Menu();
    menu_sld_cdlokasigrid.add(new Ext.Panel({
        title: 'Pilih Nama Lokasi',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 700,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [grid_sld_cdlokasigrid],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menu_sld_cdlokasigrid.hide();
                }
            }]
    }));

    Ext.ux.TwinComboSldComboLokasigrid = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgrid_sld_cdlokasigrid.load({
                params:{
                    kd_produk: Ext.getCmp('kd_produkGrid').getValue()
                }
            });
            menu_sld_cdlokasigrid.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menu_sld_cdlokasigrid.on('hide', function(){
        var sf = Ext.getCmp('id_searchgrid_sld_cdlokasigrid').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgrid_sld_cdlokasigrid').setValue('');
            searchgrid_sld_cdlokasigrid.onTrigger2Click();
        }
    });

    //end twincombolokasi grid

	var searchgridsetlokasidefault = new Ext.app.SearchField({
        store: strsetlokasidefault,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
		emptyText: 'Kode Barang, Kode Barang Lama, Nama Barang',
		id: 'id_searchgridsetlokasidefault'
    });

	searchgridsetlokasidefault.onTrigger1Click = function(evt) {
		if (this.hasSearch) {
			this.el.dom.value = '';

			// Get the value of search field
			var kd_kategori1 = Ext.getCmp('sld_cbkategori1').getValue();
			var kd_kategori2 = Ext.getCmp('sld_cbkategori2').getValue();
			var kd_kategori3 = Ext.getCmp('sld_cbkategori3').getValue();
			var kd_kategori4 = Ext.getCmp('sld_cbkategori4').getValue();
			var o = { 	start: 0,
						kd_kategori1: kd_kategori1,
						kd_kategori2: kd_kategori2,
						kd_kategori3: kd_kategori3,
						kd_kategori4: kd_kategori4
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

	searchgridsetlokasidefault.onTrigger2Click = function(evt) {
		var text = this.getRawValue();
		if (text.length < 1) {
		this.onTrigger1Click();
		return;
		}

		// Get the value of search field
		var kd_kategori1 = Ext.getCmp('sld_cbkategori1').getValue();
		var kd_kategori2 = Ext.getCmp('sld_cbkategori2').getValue();
		var kd_kategori3 = Ext.getCmp('sld_cbkategori3').getValue();
		var kd_kategori4 = Ext.getCmp('sld_cbkategori4').getValue();
		var o = { 	start: 0,
					kd_kategori1: kd_kategori1,
					kd_kategori2: kd_kategori2,
					kd_kategori3: kd_kategori3,
					kd_kategori4: kd_kategori4
				};

		this.store.baseParams = this.store.baseParams || {};
		this.store.baseParams[this.paramName] = text;
		this.store.reload({params:o});
		this.hasSearch = true;
		this.triggers[0].show();
	};

	var searchgridsetlokasidefaultdetail = new Ext.app.SearchField({
        store: strsetlokasidefaultdetail,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
		id: 'id_searchgridsetlokasidefaultdetail'
    });

	searchgridsetlokasidefaultdetail.onTrigger1Click = function(evt) {
		if (this.hasSearch) {
			this.el.dom.value = '';

			// Get the value of search field
			var kd_kategori1 = Ext.getCmp('sld_cbkategori1').getValue();
			var kd_kategori2 = Ext.getCmp('sld_cbkategori2').getValue();
			var kd_kategori3 = Ext.getCmp('sld_cbkategori3').getValue();
			var kd_kategori4 = Ext.getCmp('sld_cbkategori4').getValue();
			var o = { 	start: 0,
						kd_kategori1: kd_kategori1,
						kd_kategori2: kd_kategori2,
						kd_kategori3: kd_kategori3,
						kd_kategori4: kd_kategori4
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

	searchgridsetlokasidefaultdetail.onTrigger2Click = function(evt) {
		var text = this.getRawValue();
		if (text.length < 1) {
		this.onTrigger1Click();
		return;
		}

		// Get the value of search field
		var kd_kategori1 = Ext.getCmp('sld_cbkategori1').getValue();
		var kd_kategori2 = Ext.getCmp('sld_cbkategori2').getValue();
		var kd_kategori3 = Ext.getCmp('sld_cbkategori3').getValue();
		var kd_kategori4 = Ext.getCmp('sld_cbkategori4').getValue();
		var o = { 	start: 0,
					kd_kategori1: kd_kategori1,
					kd_kategori2: kd_kategori2,
					kd_kategori3: kd_kategori3,
					kd_kategori4: kd_kategori4
				};

		this.store.baseParams = this.store.baseParams || {};
		this.store.baseParams[this.paramName] = text;
		this.store.reload({params:o});
		this.hasSearch = true;
		this.triggers[0].show();
	};

    var headersetlokasidefault = {
        layout: 'column',
        border: false,
        items: [{
            columnWidth: .5,
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: { labelSeparator: ''},
            items: [{
					xtype: 'hidden',
					name: 'gridsender',
					id: 'bpl_gridsender'
				},sld_cbkategori1,sld_cbkategori2
			]
        }, {
            columnWidth: .5,
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: { labelSeparator: ''},
            items: [sld_cbkategori3,sld_cbkategori4]
        }]
    };

    var editorsetlokasidefault = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });

    var gridsetlokasidefault= new Ext.grid.GridPanel({
        store: strsetlokasidefault,
        stripeRows: true,
        height: 200,
        frame: true,
        border:true,
        plugins: [editorsetlokasidefault],
        columns: [{
            dataIndex: 'koreksi_lokasi',
            header: 'Edited',
            width: 50,
            sortable: true,
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'editedGrid'
            })
        },{
            header: 'Kode Barang',
            dataIndex: 'kd_produk',
            width: 100,
            sortable: true,
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'kd_produkGrid'
            })
        },{
            header: 'Kode Barang Lama',
            dataIndex: 'kd_produk_lama',
            width: 110,
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
            header: 'Type Lokasi',
            dataIndex: 'flag_lokasi',
            width: 120,
            editor: {
                        xtype:          'combo',
                        store:          new Ext.data.JsonStore({
                            fields : ['name'],
                            data   : [
                                {name : 'Supermarket'},
                                {name : 'Gudang'},
                            ]
                        }),
                        id:           	'id_typelokasi',
                        mode:           'local',
                        name:           'type_lokasi',
                        value:          '%',
                        width:			50,
                        editable:       false,
                        hiddenName:     'type_lokasi',
                        valueField:     'name',
                        displayField:   'name',
                        triggerAction:  'all',
                        forceSelection: true,
                        listeners: {
                            'change': function(field, selectedValue) {
                                Ext.getCmp('editedGrid').setValue('Y');
                            }
                        }
            }
        },{
            header: 'Lokasi Default',
            dataIndex: 'lokasi_default',
            width: 200,
            editor: new Ext.ux.TwinComboSldComboLokasigrid({
                    id: 'id_sld_cdlokasigrid',
                    store: strcb_sld_cdlokasigrid,
                    mode: 'local',
                    valueField: 'kd_lokasi',
                    displayField: 'nama_lokasi',
                    typeAhead: true,
                    triggerAction: 'all',
                    //allowBlank: false,
                    editable: false,
                    hiddenName: 'kd_lokasi',
                    emptyText: 'Pilih lokasi'

                })
        },{
            header: 'Kd. Lokasi',
            dataIndex: 'kd_lokasi',
            width: 80,
            sortable: true,
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'kd_lokasiGrid'
            })
        },{
            header: 'Kd. Blok',
            dataIndex: 'kd_blok',
            width: 70,
            sortable: true,
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'kd_blokGrid'
            })
        },{
            header: 'Kd. Sub Blok',
            dataIndex: 'kd_sub_blok',
            width: 50,
            sortable: true,
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'kd_sub_blokGrid'
            })
        }],
		tbar: new Ext.Toolbar({
	        items: [searchgridsetlokasidefault]
	    }),
		listeners:{
		'rowclick': function(){
                var sm = gridsetlokasidefault.getSelectionModel();
                var sel = sm.getSelections();
                Ext.getCmp('grid_kd_produk').setValue(sel[0].get('kd_produk'));

//                gridsetlokasidefaultdetail.store.load({
//					params:{
//						kd_produk:sel[0].get('kd_produk')
//					}
//				})
            }
		}
    });

	 // row actions
    var actionsetlokasidefault = new Ext.ux.grid.RowActions({
		header :'Edit',
		autoWidth: false,
		locked: true,
		width: 30,
        actions:[{iconCls: 'icon-edit-record', qtip: 'Edit'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });
    var actionsetlokasidefaultdel = new Ext.ux.grid.RowActions({
		header: 'Delete',
		autoWidth: false,
		width: 40,
        actions:[{iconCls: 'icon-delete-record', qtip: 'Delete'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    });

    actionsetlokasidefaultdel.on('action', function(grid, record, action, row, col) {
        var kd_prod = record.get('kd_produk');
        var kd_lokasi = record.get('kd_lokasi');
        var kd_blok = record.get('kd_blok');
        var kd_sub_blok = record.get('kd_sub_blok');
        switch(action) {
            case 'icon-edit-record':
                editsetlokasidefault(kd_prod,kd_lokasi,kd_blok,kd_sub_blok);
                break;
            case 'icon-delete-record':
                Ext.Msg.show({
                    title: 'Confirm',
                    msg: 'Are you sure delete selected row ?',
                    buttons: Ext.Msg.YESNO,
                    fn: function(btn){
                        if (btn == 'yes') {
                            Ext.Ajax.request({
                                url: '<?= site_url("barang_per_lokasi/delete_row") ?>',
                                method: 'POST',
                                params: {
                                    kd_produk: kd_prod,
									kd_lokasi: kd_lokasi,
									kd_blok: kd_blok,
									kd_sub_blok:kd_sub_blok
                                },
                                callback:function(opt,success,responseObj){
                                    var de = Ext.util.JSON.decode(responseObj.responseText);
                                    if(de.success==true){
                                        strsetlokasidefaultdetail.reload();
                                        strsetlokasidefaultdetail.load({
											params:{
												kd_produk: kd_prod,
											}
										});
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
                });
                break;

        }
    });

// start COMBOBOX TYPE LOKASI
	var valcbsldtypelokasi = [
		['S', "Supermarket"],
		['G', "Gudang"]
	];
	var strcbsldtypelokasi = new Ext.data.ArrayStore({
		fields: [{
			name: 'key'
		}, {
			name: 'value'
		}],
		data: valcbsldtypelokasi
	});
	var cbsldtypelokasi = new Ext.form.ComboBox({
		fieldLabel: 'Type Lokasi',
		id: 'cbsldtypelokasi',
		name: 'type_lokasi',
		// allowBlank:false,
		store: strcbsldtypelokasi,
		valueField: 'key',
		displayField: 'value',
		mode: 'local',
		forceSelection: true,
		triggerAction: 'all',
		anchor: '79%',
                emptyText: 'Type Lokasi'
	});
	// end COMBOBOX TYPE LOKASI

    var setlokasidefault = new Ext.FormPanel({
        id: 'setlokasidefault',
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
                    items: [headersetlokasidefault]
                },
                {
                xtype:'fieldset',
                autoheight: true,
                title: 'Lokasi',
                collapsed: false,
                collapsible: true,
                anchor: '60%',
                items:[ cbsldtypelokasi,{xtype : 'compositefield',
                        anchor: '90%',
                        msgTarget: 'side',
                        fieldLabel: 'Nama Lokasi',
                        items:[sld_cdlokasi

                        ]
                    }, {
                        xtype: 'textfield',
                        hidden: true,
                        name: 'kd_lokasi',
                        id: 'kd_lokasi_default'
                    },{
                        xtype: 'textfield',
                        hidden: true,
                        name: 'kd_blok',
                        id: 'kd_blok_lokasi_default'
                    }, {
                        xtype: 'textfield',
                        hidden: true,
                        name: 'kd_blok',
                        id: 'kd_subblok_lokasi_default'
                    }],
            buttons: [{
            text: 'Apply',
            handler: function(){
                var kd_lokasi =  Ext.getCmp('kd_lokasi_default').getValue();
                var kd_blok =  Ext.getCmp('kd_blok_lokasi_default').getValue();
                var kd_sub_blok =  Ext.getCmp('kd_subblok_lokasi_default').getValue();
                var nm_lokasi = Ext.getCmp('id_sld_cdlokasi').getRawValue();
                var type_lokasi = Ext.getCmp('cbsldtypelokasi').getRawValue();

//                if(!kd_lokasi){
//                    Ext.Msg.show({
//                        title: 'Error',
//                        msg: 'Silahkan Pilih Lokasi Terlebih Dahulu',
//                        modal: true,
//                        icon: Ext.Msg.ERROR,
//                        buttons: Ext.Msg.OK
//                    });
//                    return;
//                }

                strsetlokasidefault.each(function(record){
                    record.set('koreksi_lokasi', 'Y');
                    record.set('lokasi_default',nm_lokasi);
                    record.set('kd_lokasi',kd_lokasi);
                    record.set('kd_blok',kd_blok);
                    record.set('kd_sub_blok',kd_sub_blok);
                    record.set('flag_lokasi',type_lokasi);
                    record.commit();
                });

                }
            }]
        },
        gridsetlokasidefault
        ],
        buttons: [{
                    text: 'Save',
                    formBind: true,
                    handler: function () {
                    var lokasidefault = new Array();
                    strsetlokasidefault.each(function(node){
                        lokasidefault.push(node.data)
                    });

                    Ext.getCmp('setlokasidefault').getForm().submit({
                        url: '<?= site_url("set_lokasi_default/update_row") ?>',
                        scope: this,
                        params: {
                                        detail: Ext.util.JSON.encode(lokasidefault)

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
                              clearsetlokasidefault();
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
                clearsetlokasidefault();
            }
        }]
    });

    setlokasidefault.on('afterrender', function(){
        this.getForm().load({
            url: '<?= site_url("barang_per_lokasi/get_form") ?>',
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
    });

    function clearsetlokasidefault(){
        Ext.getCmp('setlokasidefault').getForm().reset();
<!--        Ext.getCmp('setlokasidefault').getForm().load({-->
<!--            url: '--><?//= site_url("barang_per_lokasi/get_form") ?><!--',-->
<!--            failure: function(form, action){-->
<!--                var de = Ext.util.JSON.decode(action.response.responseText);-->
<!--                Ext.Msg.show({-->
<!--                        title: 'Error',-->
<!--                        msg: de.errMsg,-->
<!--                        modal: true,-->
<!--                        icon: Ext.Msg.ERROR,-->
<!--                        buttons: Ext.Msg.OK,-->
<!--                        fn: function(btn){-->
<!--                            if (btn == 'ok' && de.errMsg == 'Session Expired') {-->
<!--                                window.location = '--><?//= site_url("auth/login") ?><!--';-->
<!--                            }-->
<!--                        }-->
<!--                    });-->
<!--            }-->
<!--        });-->
        strsetlokasidefault.removeAll();
    }

	function editsetlokasidefault(kd_produk,kd_lokasi,kd_blok,kd_sub_blok){
        strcbkdprodukspb.load();
		Ext.getCmp('id_action').setValue('Update');
        Ext.getCmp('btnresetsetlokasidefault').hide();
        Ext.getCmp('btnsubmitsetlokasidefault').setText('Update');
        winaddsetlokasidefault.setTitle('Edit Form');
        Ext.getCmp('id_formaddsetlokasidefault').getForm().load({
            url: '<?= site_url("barang_per_lokasi/get_row") ?>',
            params: {
                kd_produk: kd_produk,
                kd_lokasi: kd_lokasi,
                kd_blok: kd_blok,
                kd_sub_blok: kd_sub_blok,

                cmd: 'get'
            },
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
        winaddsetlokasidefault.show();
    }
</script>
