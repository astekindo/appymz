<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">	
	var strbarangperlokasi = new Ext.data.Store({
		autoSave:false,
        reader: new Ext.data.JsonReader({
            fields: [
				{name: 'kd_produk', allowBlank: false, type: 'text'},
				{name: 'kd_produk_lama', allowBlank: false, type: 'text'},
				{name: 'nama_produk', allowBlank: false, type: 'text'},
				{name: 'nm_satuan', allowBlank: false, type: 'text'},			
			],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("barang_per_lokasi/search_produk_by_kategori") ?>',
            method: 'POST'
        }),
        writer: new Ext.data.JsonWriter(
        {
			encode: true,
			writeAllFields: true
        })
    });
	
	/* START FORM */
	
	var strbarangperlokasidetail = new Ext.data.Store({
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
            url: '<?= site_url("blok_lokasi/get_lokasi") ?>',
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
        id: 'bpl_cbbarangperlokasi',
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
                cbblokbrgperlks.setValue();
                cbblokbrgperlks.store.proxy.conn.url = '<?= site_url("sub_blok_lokasi/get_blok") ?>/' + kd_cblokasi;
                cbblokbrgperlks.store.reload();
                //strcbblokbrgperlks.load();
            }
        }
    });

    // combobox blok
    var strcbblokbrgperlks = new Ext.data.Store({
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
    
    var cbblokbrgperlks = new Ext.form.ComboBox({
        fieldLabel: 'Nama Blok <span class="asterix">*</span>',
        id: 'bpl_cbblok',
        mode: 'local',
        store: strcbblokbrgperlks,
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
                var kd_cblokasi = Ext.getCmp('bpl_cbbarangperlokasi').getValue();
                var kd_cbblok = this.getValue();
                cbsubblokbrgperlks.setValue();
                cbsubblokbrgperlks.store.proxy.conn.url = '<?= site_url("sub_blok_lokasi/get_sub_blok") ?>/' + kd_cblokasi + '/' + kd_cbblok;
                cbsubblokbrgperlks.store.reload();
            }
        }
    });
	
    // combobox subblok
    var strcbsubblokbrgperlks = new Ext.data.Store({
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
    
    var cbsubblokbrgperlks = new Ext.form.ComboBox({
        fieldLabel: 'Nama Sub Blok <span class="asterix">*</span>',
        id: 'bpl_cbsubblok',
        mode: 'local',
        store: strcbsubblokbrgperlks,
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
	 
    Ext.ns('barangperlokasiform');
    barangperlokasiform.Form = Ext.extend(Ext.form.FormPanel, {
    
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
            barangperlokasiform.Form.superclass.constructor.call(this, config);
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
                    xtype: 'textfield',
                    fieldLabel: 'Kode Produk <span class="asterix">*</span>',
                    name: 'kode_prod',
                    allowBlank: false,
                    id: 'grid_kd_produk_lks',
                    anchor: '90%',
                    readOnly : true,
                    fieldClass:'readonly-input'
                },{
                    xtype: 'hidden',
                    name: 'kd_lokasi'
                },{
                    xtype: 'hidden',
                    name: 'kd_blok'
                },{
                    xtype: 'hidden',
                    name: 'kd_sub_blok'
                }, cblokasibpl, cbblokbrgperlks, cbsubblokbrgperlks, {
                    xtype: 'textarea',
                    fieldLabel: 'Keterangan <span class="asterix">*</span>',
                    name: 'ket',
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
                    id: 'btnsubmitbarangperlokasi',
                    formBind: true,
                    scope: this,
                    handler: this.submit
                }, {
                    text: 'Reset',
                    id: 'btnresetbarangperlokasi',
                    scope: this,
                    handler: this.reset
                }, {
                    text: 'Close',
                    id: 'btnClose',
                    scope: this,
                    handler: function(){
                        winaddbarangperlokasi.hide();
                    }
                }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            
            // call parent
            barangperlokasiform.Form.superclass.initComponent.apply(this, arguments);
            
        } // eo function initComponent	
        ,
        onRender: function(){
        
            // call parent
            barangperlokasiform.Form.superclass.onRender.apply(this, arguments);
            
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
            
            
            strbarangperlokasidetail.load({
					params:{
						kd_produk: Ext.getCmp('grid_kd_produk_lks').getValue()
					}
				});
            Ext.getCmp('id_formaddbarangperlokasi').getForm().reset();
            winaddbarangperlokasi.hide();
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
    Ext.reg('formaddbarangperlokasi', barangperlokasiform.Form);
    
    var winaddbarangperlokasi = new Ext.Window({
        id: 'id_winaddbarangperlokasi',
        closeAction: 'hide',
        width: 450,
        height: 350,
        layout: 'fit',
        border: false,
        items: {
            id: 'id_formaddbarangperlokasi',
            xtype: 'formaddbarangperlokasi'
        },
        onHide: function(){
            Ext.getCmp('id_formaddbarangperlokasi').getForm().reset();
        }
    });
	// combobox kategori1
    var str_bpl_cbkategori1 = new Ext.data.Store({
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
				var r = new (str_bpl_cbkategori1.recordType)({
					'kd_kategori1': '',
					'nama_kategori1': '-----'
				});
				str_bpl_cbkategori1.insert(0, r);
			},
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var bpl_cbkategori1 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 1 <span class="asterix">*</span>',
        id: 'bpl_cbkategori1',
        store: str_bpl_cbkategori1,
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
                var kdbpl_cbkategori1 = bpl_cbkategori1.getValue();
                // bpl_cbkategori2.setValue();
                bpl_cbkategori2.store.proxy.conn.url = '<?= site_url("kategori3/get_kategori2") ?>/' + kdbpl_cbkategori1;
                bpl_cbkategori2.store.reload();
				strbarangperlokasi.load({
					params:{
						kd_kategori1: Ext.getCmp('bpl_cbkategori1').getValue(),
						kd_kategori2: Ext.getCmp('bpl_cbkategori2').getValue(),
						kd_kategori3: Ext.getCmp('bpl_cbkategori3').getValue(),
						kd_kategori4: Ext.getCmp('bpl_cbkategori4').getValue()
					}
				});             
            }
        }
    });
    // combobox kategori2
    var str_bpl_cbkategori2 = new Ext.data.Store({
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
				var r = new (str_bpl_cbkategori2.recordType)({
					'kd_kategori2': '',
					'nama_kategori2': '-----'
				});
				str_bpl_cbkategori2.insert(0, r);
			},
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var bpl_cbkategori2 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 2 <span class="asterix">*</span>',
        id: 'bpl_cbkategori2',
		mode: 'local',
        store: str_bpl_cbkategori2,
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
                var kd_bpl_cbkategori1 = bpl_cbkategori1.getValue();
                var kd_bpl_cbkategori2 = this.getValue();
                bpl_cbkategori3.setValue();
                bpl_cbkategori3.store.proxy.conn.url = '<?= site_url("kategori4/get_kategori3") ?>/' + kd_bpl_cbkategori1 +'/'+ kd_bpl_cbkategori2;
                bpl_cbkategori3.store.reload();
				strbarangperlokasi.load({
					params:{
						kd_kategori1: Ext.getCmp('bpl_cbkategori1').getValue(),
						kd_kategori2: Ext.getCmp('bpl_cbkategori2').getValue(),
						kd_kategori3: Ext.getCmp('bpl_cbkategori3').getValue(),
						kd_kategori4: Ext.getCmp('bpl_cbkategori4').getValue()
					}
				});             
            }
        }
	});
	
    // combobox kategori3
    var str_bpl_cbkategori3 = new Ext.data.Store({
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
				var r = new (str_bpl_cbkategori3.recordType)({
					'kd_kategori3': '',
					'nama_kategori3': '-----'
				});
				str_bpl_cbkategori3.insert(0, r);
			},
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
	
    var bpl_cbkategori3 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 3 <span class="asterix">*</span>',
        id: 'bpl_cbkategori3',
        mode: 'local',
        store: str_bpl_cbkategori3,
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
                var kd_bpl_cbkategori1 = bpl_cbkategori1.getValue();
                var kd_bpl_cbkategori2 = bpl_cbkategori2.getValue();
                var kd_bpl_cbkategori3 = this.getValue();
                bpl_cbkategori4.setValue();
                bpl_cbkategori4.store.proxy.conn.url = '<?= site_url("master_barang/get_kategori4") ?>/' + kd_bpl_cbkategori1 +'/'+ kd_bpl_cbkategori2 +'/'+ kd_bpl_cbkategori3;
                bpl_cbkategori4.store.reload();				
				strbarangperlokasi.load({
					params:{
						kd_kategori1: Ext.getCmp('bpl_cbkategori1').getValue(),
						kd_kategori2: Ext.getCmp('bpl_cbkategori2').getValue(),
						kd_kategori3: Ext.getCmp('bpl_cbkategori3').getValue(),
						kd_kategori4: Ext.getCmp('bpl_cbkategori4').getValue()
					}
				});             

            }
        }
    });
	
    // combobox kategori4
    var str_bpl_cbkategori4 = new Ext.data.Store({
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
				var r = new (str_bpl_cbkategori4.recordType)({
					'kd_kategori4': '',
					'nama_kategori4': '-----'
				});
				str_bpl_cbkategori4.insert(0, r);
			},
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var bpl_cbkategori4 = new Ext.form.ComboBox({   
        fieldLabel: 'Kategori 4 <span class="asterix">*</span>',
        id: 'bpl_cbkategori4',
        mode: 'local',
        store: str_bpl_cbkategori4,
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
				strbarangperlokasi.load({
					params:{
						kd_kategori1: Ext.getCmp('bpl_cbkategori1').getValue(),
						kd_kategori2: Ext.getCmp('bpl_cbkategori2').getValue(),
						kd_kategori3: Ext.getCmp('bpl_cbkategori3').getValue(),
						kd_kategori4: Ext.getCmp('bpl_cbkategori4').getValue()
					}
				});             

            }
        }
    });
	
	var searchgridbarangperlokasi = new Ext.app.SearchField({
        store: strbarangperlokasi,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
		emptyText: 'Kode Barang, Kode Barang Lama, Nama Barang',
		id: 'id_searchgridbarangperlokasi'
    });
	
	searchgridbarangperlokasi.onTrigger1Click = function(evt) {
		if (this.hasSearch) {
			this.el.dom.value = '';
			
			// Get the value of search field
			var kd_kategori1 = Ext.getCmp('bpl_cbkategori1').getValue();
			var kd_kategori2 = Ext.getCmp('bpl_cbkategori2').getValue();
			var kd_kategori3 = Ext.getCmp('bpl_cbkategori3').getValue();
			var kd_kategori4 = Ext.getCmp('bpl_cbkategori4').getValue();
			var o = { 	start: 0, 
						kd_kategori1: kd_kategori1,
						kd_kategori2: kd_kategori2,
						kd_kategori3: kd_kategori3,
						kd_kategori4: kd_kategori4,						
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
	
	searchgridbarangperlokasi.onTrigger2Click = function(evt) {
		var text = this.getRawValue();
		if (text.length < 1) {
		this.onTrigger1Click();
		return;
		}

		// Get the value of search field
		var kd_kategori1 = Ext.getCmp('bpl_cbkategori1').getValue();
		var kd_kategori2 = Ext.getCmp('bpl_cbkategori2').getValue();
		var kd_kategori3 = Ext.getCmp('bpl_cbkategori3').getValue();
		var kd_kategori4 = Ext.getCmp('bpl_cbkategori4').getValue();
		var o = { 	start: 0, 
					kd_kategori1: kd_kategori1,
					kd_kategori2: kd_kategori2,
					kd_kategori3: kd_kategori3,
					kd_kategori4: kd_kategori4,						
				};
	 
		this.store.baseParams = this.store.baseParams || {};
		this.store.baseParams[this.paramName] = text;
		this.store.reload({params:o});
		this.hasSearch = true;
		this.triggers[0].show();
	};
	
	var searchgridbarangperlokasidetail = new Ext.app.SearchField({
        store: strbarangperlokasidetail,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
		id: 'id_searchgridbarangperlokasidetail'
    });
	
	searchgridbarangperlokasidetail.onTrigger1Click = function(evt) {
		if (this.hasSearch) {
			this.el.dom.value = '';
			
			// Get the value of search field
			var kd_kategori1 = Ext.getCmp('bpl_cbkategori1').getValue();
			var kd_kategori2 = Ext.getCmp('bpl_cbkategori2').getValue();
			var kd_kategori3 = Ext.getCmp('bpl_cbkategori3').getValue();
			var kd_kategori4 = Ext.getCmp('bpl_cbkategori4').getValue();
			var o = { 	start: 0, 
						kd_kategori1: kd_kategori1,
						kd_kategori2: kd_kategori2,
						kd_kategori3: kd_kategori3,
						kd_kategori4: kd_kategori4,						
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
	
	searchgridbarangperlokasidetail.onTrigger2Click = function(evt) {
		var text = this.getRawValue();
		if (text.length < 1) {
		this.onTrigger1Click();
		return;
		}

		// Get the value of search field
		var kd_kategori1 = Ext.getCmp('bpl_cbkategori1').getValue();
		var kd_kategori2 = Ext.getCmp('bpl_cbkategori2').getValue();
		var kd_kategori3 = Ext.getCmp('bpl_cbkategori3').getValue();
		var kd_kategori4 = Ext.getCmp('bpl_cbkategori4').getValue();
		var o = { 	start: 0, 
					kd_kategori1: kd_kategori1,
					kd_kategori2: kd_kategori2,
					kd_kategori3: kd_kategori3,
					kd_kategori4: kd_kategori4,						
				};
	 
		this.store.baseParams = this.store.baseParams || {};
		this.store.baseParams[this.paramName] = text;
		this.store.reload({params:o});
		this.hasSearch = true;
		this.triggers[0].show();
	};
	
    var headerbarangperlokasi = {
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
					id: 'bpl_gridsender',
				},bpl_cbkategori1,bpl_cbkategori2
			]
        }, {
            columnWidth: .5,
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: { labelSeparator: ''},
            items: [bpl_cbkategori3,bpl_cbkategori4]
        }]
    }
   	
    var editorbarangperlokasi = new Ext.ux.grid.RowEditor({
        saveText: 'Update'		
    });

    var gridbarangperlokasi = new Ext.grid.GridPanel({
        store: strbarangperlokasi,
        stripeRows: true,
        height: 200,
        frame: true,
        border:true,
        columns: [{
            header: 'Kode Barang',
            dataIndex: 'kd_produk',
            width: 100,
            sortable: true,
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'bpl_kd_produk'
            })
        },{
            header: 'Kode Barang Lama',
            dataIndex: 'kd_produk_lama',
            width: 110,
            sortable: true,
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'bpl_kd_produk_lama'
            })
        },{
            header: 'Nama Barang',
            dataIndex: 'nama_produk',
            width: 300,
            sortable: true,
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'bpl_nama_produk'
            })
        },{
            header: 'Satuan',
            dataIndex: 'nm_satuan',
            width: 80,
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'bpl_satuan'
            })
        }],
		tbar: new Ext.Toolbar({
	        items: [searchgridbarangperlokasi]
	    }),
		listeners:{
		'rowclick': function(){              
                var sm = gridbarangperlokasi.getSelectionModel();                
                var sel = sm.getSelections(); 			
				Ext.getCmp('grid_kd_produk_lks').setValue(sel[0].get('kd_produk'));	
                                gridbarangperlokasidetail.store.load({
					params:{
						kd_produk:sel[0].get('kd_produk')
					}
				})
            }          
		}
    });
	
	 // row actions
    var actionbarangperlokasi = new Ext.ux.grid.RowActions({
		header :'Edit',
		autoWidth: false,
		locked: true,
		width: 30,
        actions:[{iconCls: 'icon-edit-record', qtip: 'Edit'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    }); 
    var actionbarangperlokasidel = new Ext.ux.grid.RowActions({
		header: 'Delete',
		autoWidth: false,
		width: 40,
        actions:[{iconCls: 'icon-delete-record', qtip: 'Delete'}],
        widthIntercept: Ext.isSafari ? 4 : 2
    }); 
    
    actionbarangperlokasidel.on('action', function(grid, record, action, row, col) {
        var kd_prod = record.get('kd_produk');
        var kd_lokasi = record.get('kd_lokasi');
        var kd_blok = record.get('kd_blok');
        var kd_sub_blok = record.get('kd_sub_blok');
        switch(action) {
            case 'icon-edit-record':                
                editbarangperlokasi(kd_prod,kd_lokasi,kd_blok,kd_sub_blok);
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
                                        strbarangperlokasidetail.reload();
                                        strbarangperlokasidetail.load({
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
	var gridbarangperlokasidetail = new Ext.grid.GridPanel({
        store: strbarangperlokasidetail,
        stripeRows: true,
        height: 190,
        frame: true,
        border:true,
	plugins: [actionbarangperlokasidel],
        columns: [actionbarangperlokasidel,
		{
            header: 'Nama Lokasi',
            dataIndex: 'nama_lokasi',
            width: 100,
            sortable: true
        },{
            header: 'Nama Blok',
            dataIndex: 'nama_blok',
            width: 150,
            sortable: true
        },{
            header: 'Nama Sub Blok',
            dataIndex: 'nama_sub_blok',
            width: 150,
            sortable: true
        },{
            header: 'Peruntukan',
            dataIndex: 'kd_peruntukan',
            width: 150,
            sortable: true
        },{
            header: 'Keterangan',
            dataIndex: 'keterangan',
            width: 250,
            sortable: true
        }],
	tbar: new Ext.Toolbar({
	    items: [{
            text: 'Add',
            icon: BASE_ICONS + 'add.png',
            onClick: function(){	
				strbarangperlokasidetail.load({
                                params:{
						kd_produk: Ext.getCmp('grid_kd_produk_lks').getValue(),
					}
				});
                                
                                var kd_produk = Ext.getCmp('grid_kd_produk_lks').getValue();
                                var sm = grid.getSelectionModel();                
                                var sel = sm.getSelections();
                                Ext.getCmp('grid_kd_produk_lks').setValue(kd_produk);
				Ext.getCmp('bpl_cbbarangperlokasi').setValue('');    
				Ext.getCmp('bpl_cbblok').setValue('');    
				Ext.getCmp('bpl_cbsubblok').setValue('');    
				Ext.getCmp('bpl_cbbarangperlokasi').setDisabled(false);			
                                Ext.getCmp('bpl_cbblok').setDisabled(false);   
                                Ext.getCmp('bpl_cbsubblok').setDisabled(false);   
                                Ext.getCmp('btnresetbarangperlokasi').show();
                                Ext.getCmp('btnsubmitbarangperlokasi').setText('Submit');
                                winaddbarangperlokasi.setTitle('Add Form');
                        Ext.getCmp('id_formaddbarangperlokasi').getForm().load({
                        url: '<?= site_url("master_lokasi/get_form") ?>',
                        success: function(form, action){
                            var r = Ext.util.JSON.decode(action.response.responseText);
                            if(r.data.user_peruntukan === "0"){
                                Ext.getCmp('bpl_peruntukan_supermarket').setValue(true);
                                Ext.getCmp('bpl_peruntukan_supermarket').show();
                                Ext.getCmp('bpl_peruntukan_distribusi').hide();
                            }else if(r.data.user_peruntukan === "1"){
                                Ext.getCmp('bpl_peruntukan_distribusi').setValue(true);
                                Ext.getCmp('bpl_peruntukan_supermarket').hide();
                                Ext.getCmp('bpl_peruntukan_distribusi').show();
                            }else{
                                Ext.getCmp('bpl_peruntukan_supermarket').setValue(true);
                                Ext.getCmp('bpl_peruntukan_supermarket').show();
                                Ext.getCmp('bpl_peruntukan_distribusi').show();
                            }
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
                                winaddbarangperlokasi.show();             
			}            
			}]
	    })
    });
	
    
    var barangperlokasi = new Ext.FormPanel({
        id: 'barangperlokasi',
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
                    items: [headerbarangperlokasi]
                },
                gridbarangperlokasi,gridbarangperlokasidetail,
        ],
        buttons: [{
            text: 'Reset',
            handler: function(){
                clearbarangperlokasi();
            }
        }]
    });
    
    barangperlokasi.on('afterrender', function(){
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
    
    function clearbarangperlokasi(){
        Ext.getCmp('barangperlokasi').getForm().reset();
        Ext.getCmp('barangperlokasi').getForm().load({
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
        strbarangperlokasi.removeAll();
        strbarangperlokasidetail.removeAll();
    }
	
	function editbarangperlokasi(kd_produk,kd_lokasi,kd_blok,kd_sub_blok){
        strcbkdprodukspb.load();
	Ext.getCmp('id_action').setValue('Update');
        Ext.getCmp('btnresetbarangperlokasi').hide();
        Ext.getCmp('btnsubmitbarangperlokasi').setText('Update');
        winaddbarangperlokasi.setTitle('Edit Form');
        Ext.getCmp('id_formaddbarangperlokasi').getForm().load({
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
        winaddbarangperlokasi.show();
    }
</script>
