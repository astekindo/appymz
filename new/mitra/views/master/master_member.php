<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">    

	/* START GRID */    
	var strmastermember = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
				  'kd_member',
					'nmmember',
					'jenis',
					'idtype',
					'idno',
					'jenis_kelamin',
				    'telepon',
				    'kd_propinsi',
				    'kd_kota',
				    'kd_kecamatan',
				    'kd_kelurahan',
				    'nama_propinsi',
				    'nama_kota',
				    'nama_kecamatan',
				    'nama_kalurahan',
				    'kodepos',
				    'alamat_pengiriman',
				    'tmplahir',
				    'tgllahir',
				    'agama',
				    'tgljoin',
				    'sdtgl',
				    'teleponkantor',
				    'hp',
				    'email',
				    'fax',
				    'status',
				    'alamat_penagihan',
				    'npwp',
				    'alamat_npwp',
				    'total_point',
				    'aktif'
			],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_member/get_rows") ?>',
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
	
    var strcbjenis = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['jenis', 'jenismember'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_member/get_jenis") ?>',
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
	
	var strcbagama = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['id', 'agama'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_member/get_agama") ?>',
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
	
	var strcbjeniscab = new Ext.data.Store({
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
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
	
	var cbkacab = new Ext.form.ComboBox({
        fieldLabel: 'KA Cabang <span class="asterix">*</span>',
        id: 'id_cbcab',
        store: strcbjeniscab,
        valueField: 'kd',
        displayField: 'nama',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_cabang',
        emptyText: 'KA Cabang',
		hideMode: 'Visibility'
    });
	
	var cbagama = new Ext.form.ComboBox({
        fieldLabel: 'Jenis Agama <span class="asterix">*</span>',
        id: 'id_cbagama',
        store: strcbagama,
        valueField: 'agama',
        displayField: 'agama',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'agama',
        emptyText: 'Jenis Agama',
		hideMode: 'Visibility'
    });
	
	var strcbjenisID = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['idtype', 'jenisid'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_member/get_jenisid") ?>',
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
	
	var strcbprop = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_propinsi','nama_propinsi'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_member/get_prop") ?>',
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
	
	var cbprop = new Ext.form.ComboBox({
        fieldLabel: 'Propinsi <span class="asterix">*</span>',
        id: 'id_cbprop',
        store: strcbprop,
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
                cbkakota.setValue();
                cbkakota.store.proxy.conn.url = '<?= site_url("master_member/get_kota") ?>/' + prop;
                cbkakota.store.reload();
            }
        }
    });
	
	
    // combobox kategori2
    var strcbkota = new Ext.data.Store({
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
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
	
	var cbkakota = new Ext.form.ComboBox({
        fieldLabel: 'Kota <span class="asterix">*</span>',
        id: 'id_cbkota',
        store: strcbkota,
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
                var prop = cbprop.getValue();
				var kota = this.getValue();
                cbkakec.setValue();
                cbkakec.store.proxy.conn.url = '<?= site_url("master_member/get_kec") ?>/' + prop + '/' + kota;
                cbkakec.store.reload();
            }
        }
    });
	
	var strcbkec = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kecamatan','nama_kecamatan'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_member/get_kec") ?>',
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
	
	var cbkakec = new Ext.form.ComboBox({
        fieldLabel: 'Kecamatan <span class="asterix">*</span>',
        id: 'id_cbkec',
        store: strcbkec,
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
                var prop = cbprop.getValue();
				var kota = cbkakota.getValue();
				var kec = cbkakec.getValue();
                cbkakel.setValue();
                cbkakel.store.proxy.conn.url = '<?= site_url("master_member/get_kel") ?>/' + prop + '/' + kota+ '/' + kec;
                cbkakel.store.reload();
            }
        }
    });
	
	var strcbkel = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_kalurahan','nama_kalurahan'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_member/get_kel") ?>',
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
	
	var cbkakel = new Ext.form.ComboBox({
        fieldLabel: 'Kelurahan ',
        id: 'id_cbkel',
        store: strcbkel,
        valueField: 'kd_kalurahan',
        displayField: 'nama_kalurahan',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        mode: 'local',
        hiddenName: 'kd_kelurahan',
        emptyText: 'Kelurahan',
		hideMode: 'Visibility'
    });
	
	var cbjenis = new Ext.form.ComboBox({
        fieldLabel: 'Jenis Member <span class="asterix">*</span>',
        id: 'id_cbjenis',
        store: strcbjenis,
        valueField: 'jenis',
        displayField: 'jenismember',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'jenis',
        emptyText: 'Pilih Jenis Member',
		hideMode: 'Visibility'
    });
	
	var cbjenisid = new Ext.form.ComboBox({
        fieldLabel: 'Jenis ID <span class="asterix">*</span>',
        id: 'id_cbjenisid',
        store: strcbjenisID,
        valueField: 'idtype',
        displayField: 'jenisid',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'idtype',
        emptyText: 'Pilih Jenis ID',
		hideMode: 'Visibility'
    });
	
	Ext.ux.ComboBox = function(config){
		if (Ext.isArray(config.store))
		{
			if (Ext.isArray(config.store[0]))
			{
				config.store = new Ext.data.SimpleStore({
					fields: ['value','text'],
					data : config.store
				});
				config.valueField = 'value';
				config.displayField = 'text';
			}
			else
			{
				var store=[];
				for (var i=0,len=config.store.length;i<len;i++)
					store[i]=[config.store[i]];
				config.store = new Ext.data.SimpleStore({
					fields: ['text'],
					data : store
				});
				config.valueField = 'text';
				config.displayField = 'text';
			}
			config.mode = 'local';
		}
		Ext.ux.ComboBox.superclass.constructor.call(this, config);
	}
	Ext.extend(Ext.ux.ComboBox,Ext.form.ComboBox,{
		
	});
	Ext.reg('combo',Ext.ux.ComboBox);
    
	
    /* START FORM */ 
    Ext.ns('mastermemberform');
    mastermemberform.Form = Ext.extend(Ext.form.FormPanel, {
    
        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 120,
        url: '<?= site_url("master_member/update_row") ?>',
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
            mastermemberform.Form.superclass.constructor.call(this, config);
        },
        initComponent: function(){
        
            // hard coded - cannot be changed from outsid
            var config = {
				layout: 'column',
				monitorValid:true,
				autoScroll:true,
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
							name: 'kd_member'
						}, cbkacab, {
							type: 'textfield',
							fieldLabel: 'Nama Member <span class="asterix">*</span>',
							name: 'nmmember',
							allowBlank: false,
							id: 'id_nmmember',
							maxLength: 255,
							anchor: '90%'                
						},cbjenis,cbjenisid, {
							type: 'textfield',
							fieldLabel: 'No ID <span class="asterix">*</span>',
							name: 'idno',
							allowBlank: false,
							id: 'id_idno',
							maxLength: 25,
							anchor: '90%'                
						},{
							xtype: 'radiogroup',                 
							fieldLabel: 'Jenis Kelamin <span class="asterix">*</span>',
							columnWidth: [.5, .5], 	                   
							name: 'jenis_kelamin',
							anchor: '90%',
							allowBlank:false,  
							items: [{                    
								boxLabel: 'Laki-Laki',                     
								name: 'jenis_kelamin',                     
								inputValue: 'L',                     
								id: 'id_kelaminL'					
							}, {                     
								boxLabel: 'Perempuan',                     
								name: 'jenis_kelamin',                     
								inputValue: 'P',                    
								id: 'id_kelaminP'                 
							}]             
						}, {
								xtype:          'combo',
								fieldLabel:		'Status',
								mode:           'local',
								value:          '0',
								triggerAction:  'all',
								forceSelection: true,
								editable:       false,
								name:           'status',
								id:           	'mm_status',
								hiddenName:     'status',
								displayField:   'name',
								valueField:     'value',
								anchor:			'90%',
								store:          new Ext.data.JsonStore({
									fields : ['name', 'value'],
									data   : [
										{name : 'Single',   value: '0'},
										{name : 'Menikah',  value: '1'},
										{name : 'Janda/Duda',  value: '2'},
										]
									})
						},{
							xtype: 'numberfield',
							fieldLabel: 'Telepon',
							name: 'telepon',
							allowBlank: true,
							id: 'id_telepon',
							maxLength: 25,
							anchor: '90%'                
						}, cbprop,cbkakota,cbkakec,cbkakel,{
							xtype: 'numberfield',
							fieldLabel: 'Kode Pos ',
							name: 'kodepos',
							allowBlank: true,
							id: 'id_kodepos',
							maxLength: 6,
							anchor: '90%'                
						}, {
							xtype: 'textarea',
							fieldLabel: 'Alamat Kirim ',
							name: 'alamat_pengiriman',
							allowBlank: true,
							id: 'id_alamat_kirim',
							maxLength: 255,
							anchor: '90%' 
						}, {
							xtype: 'textfield',
							fieldLabel: 'Total Point',
							name: 'total_point',
							allowBlank: true,
							id: 'id_total_point',
							anchor: '90%' 
						}]}, {
				items: [ {
							xtype: 'numberfield',
							fieldLabel: 'NPWP ',
							name: 'npwp',
							allowBlank: true,
							id: 'id_npwp',
							anchor: '90%'                
						}, {
							xtype: 'textarea',
							fieldLabel: 'Alamat NPWP ',
							name: 'alamat_npwp',
							allowBlank: true,
							id: 'id_alamat_npwp',
							maxLength: 255,
							anchor: '90%' 
						},{
							type: 'textfield',
							fieldLabel: 'Tempat Lahir <span class="asterix">*</span>',
							name: 'tmplahir',
							allowBlank: false,
							id: 'id_tmplahir',
							maxLength: 25,
							anchor: '90%'                
						}, {
							xtype: 'datefield',
							fieldLabel: 'Tanggal Lahir<span class="asterix">*</span>',
							name: 'tgllahir',
							id: 'id_tgllahir',
							format: 'Y-m-d',
							emptyText: 'Tanggal Lahir',
							editable: false,
							anchor: '90%'                  
						},cbagama, {
							xtype: 'datefield',
							fieldLabel: 'Tanggal Join <span class="asterix">*</span>',
							name: 'tgljoin',
							id: 'id_tgljoin',
							format: 'Y-m-d',
							value: new Date().format('m/d/Y'),
							readOnly:true,
							fieldClass:'readonly-input',
							editable: false,
							anchor: '90%'                  
						}, {
							xtype: 'datefield',
							fieldLabel: 'Masa Berlaku <span class="asterix">*</span>',
							name: 'sdtgl',
							id: 'id_sdtgl',
							format: 'Y-m-d',
							emptyText: 'Berlaku',
							editable: false,
							anchor: '90%'                  
						}, {
							xtype: 'numberfield',
							fieldLabel: 'Telepon Kantor',
							name: 'teleponkantor',
							allowBlank: true,
							id: 'id_teleponk',
							maxLength: 25,
							anchor: '90%'                
						}, {
							xtype: 'numberfield',
							fieldLabel: 'HP ',
							name: 'hp',
							allowBlank: true,
							id: 'id_hp',
							maxLength: 15,
							anchor: '90%'              
						}, {
							vtype: 'email',
							fieldLabel: 'Email ',
							name: 'email',
							allowBlank: true,
							id: 'id_email',
							maxLength: 100,
							anchor: '90%'    
						},{
							xtype: 'numberfield',
							fieldLabel: 'Fax ',
							name: 'fax',
							allowBlank: true,
							id: 'id_fax',
							maxLength: 25,
							anchor: '90%'                
						},{ 
							xtype: 'textarea',
							fieldLabel: 'Alamat Penagihan ',
							name: 'alamat_penagihan',
							allowBlank: true,
							id: 'id_alamat_penagihan',
							maxLength: 255,
							anchor: '90%'                
						},new Ext.form.Checkbox({
								xtype: 'checkbox',
								fieldLabel: 'Status Aktif <span class="asterix">*</span>',
								boxLabel:'Ya',
								name:'aktif',
								id:'mm_aktif',
								inputValue: '1',
								autoLoad : true
							})/*, {									
							xtype: 'datefield',
							fieldLabel: 'Tgl Aktivasi <span class="asterix">*</span>',
							name: 'sdtgl',
							id: 'id_sdtgl',
							format: 'Y-m-d',
							emptyText: 'sdTanggal',
							editable: false,
							anchor: '90%'                
						}*/]
				}],
                buttons: [{
                    text: 'Submit',
                    id: 'btnsubmitmastermember',
                    formBind: true,
                    scope: this,
                    handler: this.submit
                }, {
                    text: 'Reset',
                    id: 'btnresetmastermember',
                    scope: this,
                    handler: this.reset
                }, {
                    text: 'Close',
                    id: 'btnClose',
                    scope: this,
                    handler: function(){
                        winaddmastermember.hide();
                    }
                }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            
            // call parent
            mastermemberform.Form.superclass.initComponent.apply(this, arguments);
            
        } // eo function initComponent	
        ,
        onRender: function(){
        
            // call parent
            mastermemberform.Form.superclass.onRender.apply(this, arguments);
            
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
			var text = Ext.getCmp('btnsubmitmastermember').getText();
			if (text == 'Update'){
				Ext.Msg.show({
					title: 'Confirm',
					msg: 'Are you sure update selected row ?',
					buttons: Ext.Msg.YESNO,
					fn: function(btn){
						if (btn == 'yes') {
							Ext.getCmp('id_formaddmastermember').getForm().submit({
								url: Ext.getCmp('id_formaddmastermember').url,
								scope: this,
								success: Ext.getCmp('id_formaddmastermember').onSuccess,
								failure: Ext.getCmp('id_formaddmastermember').onFailure,
								params: {
									cmd: 'save'
								},
								waitMsg: 'Saving Data...'
							});
						}
					}
				})
			}else{
				Ext.getCmp('id_formaddmastermember').getForm().submit({
					url: Ext.getCmp('id_formaddmastermember').url,
					scope: this,
					success: Ext.getCmp('id_formaddmastermember').onSuccess,
					failure: Ext.getCmp('id_formaddmastermember').onFailure,
					params: {
						cmd: 'save'
					},
					waitMsg: 'Saving Data...'
				});
			}
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
            
            
            strmastermember.reload();
            Ext.getCmp('id_formaddmastermember').getForm().reset();
            winaddmastermember.hide();
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
    Ext.reg('formaddmastermember', mastermemberform.Form);
    
    var winaddmastermember = new Ext.Window({
        id: 'id_winaddmastermember',
        closeAction: 'hide',
        width: 900,
        height: 560,
        layout: 'fit',
        border: false,
		autoScroll:true,
        items: {
            id: 'id_formaddmastermember',
            xtype: 'formaddmastermember'
        },
        onHide: function(){
            Ext.getCmp('id_formaddmastermember').getForm().reset();
        }
    });
	
	var strmasmemhisto1 = new Ext.data.Store({
        autoLoad:false,
        reader: new Ext.data.JsonReader({
            fields: [
                'no_so',
                'tgl_so',
                'rp_total_bayar'				
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_member/get_histo1") ?>',
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

	var gridmastermemhisto1 = new Ext.grid.GridPanel({
        id: 'idgridmastermemhisto1',
        store: strmasmemhisto1,
		style:'margin-bottom:5px;',
        stripeRows: true,
        height: 100,		
        border:true,
        frame:true,
        columns: [{            
                header: 'No SO',
                dataIndex: 'no_so',
                width: 120
            },{            
                header: 'Tanggal SO',
                dataIndex: 'tgl_so',
                width: 100,
                sortable: true,
            },{
                header: 'Total Bayar',
                dataIndex: 'rp_total_bayar',
                width: 100,
            }
        ],
        listeners: {
            'rowclick': function(){              
                var sm = this.getSelectionModel();                
                var sel = sm.getSelections();
                gridmastermemhisto2.store.proxy.conn.url = '<?= site_url("master_member/get_histo2") ?>/' + sel[0].get('no_so');
                gridmastermemhisto2.store.reload();
            }}
    });
	
	var strmasmemhisto2 = new Ext.data.Store({
        autoLoad:false,
        reader: new Ext.data.JsonReader({
            fields: [
                'no_so',
				'kd_produk',
				'nama_produk',
				'nm_satuan',
				'qty',
				'rp_harga',
				'rp_diskon',
				'rp_ekstra_diskon',
				'rp_total'				
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_member/get_histo2") ?>',
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

	var gridmastermemhisto2 = new Ext.grid.GridPanel({
        id: 'idgridmastermemhisto2',
        store: strmasmemhisto2,
        stripeRows: true,
        height: 180,		
        border:true,
        frame:true,
        columns: [{            
                header: 'No SO',
                dataIndex: 'no_so',
                width: 120
            },{            
                header: 'Kode Produk',
                dataIndex: 'kd_produk',
                width: 100,
                sortable: true,
            },{
                header: 'Nama Produk',
                dataIndex: 'nama_produk',
                width: 250
            },{            
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 70
            },{            
                header: 'Qty',
                dataIndex: 'qty',
                width: 50
            },{
				xtype: 'numbercolumn',
				align: 'right',
				format: '0,0',
                header: 'Harga',
                dataIndex: 'rp_harga',
                width: 70
            },{        
				xtype: 'numbercolumn',
				align: 'right',
				format: '0,0',    
                header: 'Diskon',
                dataIndex: 'rp_diskon',
                width: 70
            },{        
				xtype: 'numbercolumn',
				align: 'right',
				format: '0,0',          
                header: 'Ektra Diskon',
                dataIndex: 'rp_ekstra_diskon',
                width: 100
            },{        
				xtype: 'numbercolumn',
				align: 'right',
				format: '0,0',
                header: 'Total',
                dataIndex: 'rp_total',
                width: 70
            }
        ]
    });
    
	var searchmastermember = new Ext.app.SearchField({
        store: strmastermember,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchmastermember'
    });
	
    var tbmastermember = new Ext.Toolbar({
        items: [{
            text: 'Show History',
            icon: BASE_ICONS + 'grid.png',
            onClick: function(){
				var sm = mastermember.getSelectionModel();
				var sel = sm.getSelections();
				if (sel[0] == undefined){					
					Ext.Msg.show({
			                title: 'Error',
			                msg: 'Silahkan klik salah satu data terlebih dulu',
			                modal: true,
			                icon: Ext.Msg.ERROR,
			                buttons: Ext.Msg.OK			               
			            });
					return;
				}
				gridmastermemhisto1.store.proxy.conn.url = '<?= site_url("master_member/get_histo1")?>/' + sel[0].get('kd_member');
				gridmastermemhisto1.store.reload();
                winaddmastermembergrid.setTitle('History');
				winaddmastermembergrid.show();				        
            }
        }, '-' ,{
            text: 'Add',
            icon: BASE_ICONS + 'add.png',
            onClick: function(){				
                Ext.getCmp('btnresetmastermember').show();
                Ext.getCmp('btnsubmitmastermember').setText('Submit');
                winaddmastermember.setTitle('Add Form');
                winaddmastermember.show();                
            }            
        }, '-', searchmastermember]
    });

    var cbGrid = new Ext.grid.CheckboxSelectionModel();
    
	// row actions
	var actionmastermember = new Ext.ux.grid.RowActions({
		header :'Edit',
		autoWidth: false,
		locked: true,
		width: 30,
	    actions:[{iconCls: 'icon-edit-record', qtip: 'Edit'}],
	    widthIntercept: Ext.isSafari ? 4 : 2
	});	
	var actionmastermemberhisto = new Ext.ux.grid.RowActions({
		header :'History',
		autoWidth: false,
		width: 30,
	    actions:[{iconCls: 'icon-histo-record', qtip: 'Histo'}],
	    widthIntercept: Ext.isSafari ? 4 : 2
	});	
	
	var actionmastermemberdel = new Ext.ux.grid.RowActions({
		header: 'Delete',
		autoWidth: false,
		width: 40,
	    actions:[{iconCls: 'icon-delete-record', qtip: 'Delete'}],
	    widthIntercept: Ext.isSafari ? 4 : 2
	});	
	
	actionmastermemberhisto.on('action', function(grid, record, action, row, col) {
		var kd_member = record.get('kd_member');
        var kd_prod = record.get('kd_produk');
        var nm_prod = record.get('nama_produk');
        switch(action) {
            case 'icon-history-record':
				gridmastermemhisto1.store.proxy.conn.url = '<?= site_url("master_member/get_histo1")?>/' +sel[0].get('kd_member');
				gridmastermemhisto1.store.reload();
                winaddmastermembergrid.setTitle('History');
				winaddmastermembergrid.show();	
                break;            
        }
    });
	
	
	actionmastermember.on('action', function(grid, record, action, row, col) {
		var kd_member = record.get('kd_member');
		var kd_propinsi = record.get('kd_propinsi');
		var kd_kota = record.get('kd_kota');
		var kd_kecamatan = record.get('kd_kecamatan');
		switch(action) {
			case 'icon-edit-record':	        	
				editmastermember(kd_member,kd_propinsi,kd_kota,kd_kecamatan);
	        	break;
	      	case 'icon-delete-record':
				Ext.Msg.show({
	                title: 'Confirm',
	                msg: 'Are you sure delete selected row ?',
	                buttons: Ext.Msg.YESNO,
	                fn: function(btn){
	                    if (btn == 'yes') {
	                        Ext.Ajax.request({
	                            url: '<?= site_url("master_member/delete_row") ?>',
	                            method: 'POST',
	                            params: {
	                                kd_member: kd_member
	                            },
								callback:function(opt,success,responseObj){
									var de = Ext.util.JSON.decode(responseObj.responseText);
									if(de.success==true){
		                                strmastermember.load({
		                                    params: {
		                                        start: STARTPAGE,
		                                        limit: ENDPAGE
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
	
	//grid
    var mastermember = new Ext.grid.EditorGridPanel({
        //id: 'mastermember-gridpane;',
        id: 'mastermember',
		frame: true,
        border: true,
        stripeRows: true,
        sm: cbGrid,
        store: strmastermember,
        loadMask: true,
        // title: 'Master Member',
        style: 'margin:0 auto;',
        height: 450,
		view: new Ext.ux.grid.LockingGridView(),
        colModel: new Ext.ux.grid.LockingColumnModel([actionmastermember,{
            header: "Kode Member",
            dataIndex: 'kd_member',
			locked: true,
            sortable: true,
            width: 80
        },{
            header: "Nama Member",
            dataIndex: 'nmmember',
			locked: true,
            sortable: true,
            width: 150
        },{
            header: "Alamat Rumah",
            dataIndex: 'alamat_penagihan',
            sortable: true,
            width: 150
        },{
            header: "Telepon",
            dataIndex: 'telepon',
            sortable: true,
            width: 100
        },{
            header: "HP",
            dataIndex: 'hp',
            sortable: true,
            width: 100
        },{
            header: "Jenis",
            dataIndex: 'jenis',
            sortable: true,
            width: 80
        },{
            header: "sd Tanggal",
            dataIndex: 'sdtgl',
            sortable: true,
            width: 100
        },{
            header: "Tanggal Join",
            dataIndex: 'tgljoin',
            sortable: true,
            width: 100
        },{
            header: "Tanggal Lahir",
            dataIndex: 'tgllahir',
            sortable: true,
            width: 100
        },{
            header: "No ID",
            dataIndex: 'idno',
            sortable: true,
            width: 100
        },{
            header: "Status",
            dataIndex: 'status',
            sortable: true,
            width: 75
        },{
            header: "NPWP",
            dataIndex: 'npwp',
            sortable: true,
            width: 100
        },{
            header: "Alamat NPWP",
            dataIndex: 'alamat_npwp',
            sortable: true,
            width: 100
        },{
            header: "Tempat Lahir",
            dataIndex: 'tmplahir',
            sortable: true,
            width: 100
        },{
            header: "Agama",
            dataIndex: 'agama',
            sortable: true,
            width: 100
        },{
            header: "Jenis Kelamin",
            dataIndex: 'jenis_kelamin',
            sortable: true,
            width: 100
        },{
            header: "Kelurahan",
            dataIndex: 'nama_kalurahan',
            sortable: true,
            width: 100
        },{
            header: "Kecamatan",
            dataIndex: 'nama_kecamatan',
            sortable: true,
            width: 100
        },{
            header: "Kota",
            dataIndex: 'nama_kota',
            sortable: true,
            width: 100
        },{
            header: "Propinsi",
            dataIndex: 'nama_propinsi',
            sortable: true,
            width: 100
        },{
            header: "Kode Pos",
            dataIndex: 'kodepos',
            sortable: true,
            width: 100
        },{
            header: "Fax",
            dataIndex: 'fax',
            sortable: true,
            width: 100
        },{
            header: "Email",
            dataIndex: 'email',
            sortable: true,
            width: 100
        },/*{
            header: "Profesi",
            dataIndex: 'profesi',
            sortable: true,
            width: 100
        },{
            header: "Nama Perusahaan",
            dataIndex: 'nmpersh',
            sortable: true,
            width: 150
        },*/{
            header: "Alamat Pengirim",
            dataIndex: 'alamat_pengiriman',
            sortable: true,
            width: 150
        },{
            header: "Telepon Kantor",
            dataIndex: 'teleponkantor',
            sortable: true,
            width: 100
        },{
            header: "Total Point",
            dataIndex: 'total_point',
            sortable: true,
            width: 100
        },{
            header: "Status Aktif",
            dataIndex: 'aktif',
            sortable: true,
            width: 100
        }/*,{
            header: "Fax Kantor",
            dataIndex: 'faxk',
            sortable: true,
            width: 100
        }*/]),
		plugins: [actionmastermember],
        listeners: {
            'rowdblclick': function(){				
                var sm = mastermember.getSelectionModel();                
                var sel = sm.getSelections();                
                if (sel.length > 0) {
					editmastermember(sel[0].get('kd_member'),sel[0].get('kd_propinsi'),sel[0].get('kd_kota'),
									sel[0].get('kd_kecamatan'));                    
                }                 
            }          
        },
        tbar: tbmastermember,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strmastermember,
            displayInfo: true
        })
    });
	// combobox kategori1
    var str_mm_cbkategori1 = new Ext.data.Store({
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
				var r = new (str_mm_cbkategori1.recordType)({
					'kd_kategori1': '',
					'nama_kategori1': '-----'
				});
				str_mm_cbkategori1.insert(0, r);
			},
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var mm_cbkategori1 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 1',
        id: 'mm_cbkategori1',
        store: str_mm_cbkategori1,
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
                var kdmm_cbkategori1 = mm_cbkategori1.getValue();
                // mm_cbkategori2.setValue();
                mm_cbkategori2.store.proxy.conn.url = '<?= site_url("kategori3/get_kategori2") ?>/' + kdmm_cbkategori1;
                mm_cbkategori2.store.reload();
				            
            }
        }
    });
    // combobox kategori2
    var str_mm_cbkategori2 = new Ext.data.Store({
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
				var r = new (str_mm_cbkategori2.recordType)({
					'kd_kategori2': '',
					'nama_kategori2': '-----'
				});
				str_mm_cbkategori2.insert(0, r);
			},
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var mm_cbkategori2 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 2 ',
        id: 'mm_cbkategori2',
		mode: 'local',
        store: str_mm_cbkategori2,
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
                var kd_mm_cbkategori1 = mm_cbkategori1.getValue();
                var kd_mm_cbkategori2 = this.getValue();
                mm_cbkategori3.setValue();
                mm_cbkategori3.store.proxy.conn.url = '<?= site_url("kategori4/get_kategori3") ?>/' + kd_mm_cbkategori1 +'/'+ kd_mm_cbkategori2;
                mm_cbkategori3.store.reload();
				          
            }
        }
	});
	
    // combobox kategori3
    var str_mm_cbkategori3 = new Ext.data.Store({
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
				var r = new (str_mm_cbkategori3.recordType)({
					'kd_kategori3': '',
					'nama_kategori3': '-----'
				});
				str_mm_cbkategori3.insert(0, r);
			},
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
	
    var mm_cbkategori3 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 3 ',
        id: 'mm_cbkategori3',
        mode: 'local',
        store: str_mm_cbkategori3,
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
                var kd_mm_cbkategori1 = mm_cbkategori1.getValue();
                var kd_mm_cbkategori2 = mm_cbkategori2.getValue();
                var kd_mm_cbkategori3 = this.getValue();
                mm_cbkategori4.setValue();
                mm_cbkategori4.store.proxy.conn.url = '<?= site_url("master_barang/get_kategori4") ?>/' + kd_mm_cbkategori1 +'/'+ kd_mm_cbkategori2 +'/'+ kd_mm_cbkategori3;
                mm_cbkategori4.store.reload();				
				           

            }
        }
    });
	
    // combobox kategori4
    var str_mm_cbkategori4 = new Ext.data.Store({
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
				var r = new (str_mm_cbkategori4.recordType)({
					'kd_kategori4': '',
					'nama_kategori4': '-----'
				});
				str_mm_cbkategori4.insert(0, r);
			},
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var mm_cbkategori4 = new Ext.form.ComboBox({   
        fieldLabel: 'Kategori 4 ',
        id: 'mm_cbkategori4',
        mode: 'local',
        store: str_mm_cbkategori4,
        valueField: 'kd_kategori4',
        displayField: 'nama_kategori4',
        typeAhead: true,
        triggerAction: 'all',
		style:'margin-bottom:5px;',
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
	
	var filterMasterMember = {
        layout: 'column',
        border: false,
		buttonAlign:'left',
        items: [{
            columnWidth: .5,
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: { labelSeparator: ''},
            items: [mm_cbkategori1,mm_cbkategori2,
					{xtype : 'compositefield',
						anchor: '100%',
						msgTarget: 'side',
						fieldLabel: 'Tanggal SO',
						items : [{
									xtype: 'datefield',
									name: 'tanggal',	 
									format:'d-m-Y',  
									editable:false,           
									id: 'mm_tanggal_dari',
									value: ''
								},{
									xtype: 'displayfield',
									value: 'Sampai',
									style: 'padding-left:49px;',
							   },{
									xtype: 'datefield',
									name: 'tanggal_sampai',	
									format:'d-m-Y',  
									editable:false,           
									id: 'mm_tanggal_sampai', 
									value: ''
								}]
					}]
        }, {
            columnWidth: .5,
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: { labelSeparator: ''},
            items: [ mm_cbkategori3,mm_cbkategori4,]
		}],
		buttons: [{
			text: 'Filter',
			formBind: true,
			handler: function(){
				var sm = gridmastermemhisto1.getSelectionModel();                
				var sel = sm.getSelections();
				if (sel.length > 0) {
					strmasmemhisto2.load({
					params:{  
						filter: '1',
						no_so: sel[0].get('no_so'),
						kd_kategori1: Ext.getCmp('mm_cbkategori1').getValue(),
						kd_kategori2: Ext.getCmp('mm_cbkategori2').getValue(),
						kd_kategori3: Ext.getCmp('mm_cbkategori3').getValue(),
						kd_kategori4: Ext.getCmp('mm_cbkategori4').getValue(),						
						dari: Ext.getCmp('mm_tanggal_dari').getRawValue(),
						sampai: Ext.getCmp('mm_tanggal_sampai').getRawValue(),
					}
				}); 
				}else{
					Ext.Msg.show({
								title: 'Error',
								msg: 'Silahkan Pilih No SO Terlebih Dahulu',
								modal: true,
								icon: Ext.Msg.ERROR,
								buttons: Ext.Msg.OK			               
							});
						return;
				}
				
			}
		}]
	}
	
	/* START FORM */ 
    Ext.ns('mastermembergridform');
    mastermembergridform.Form = Ext.extend(Ext.form.FormPanel, {
    
        // defaults - can be changed from outside
        border: false,
        frame: true,
        labelWidth: 120,
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
            mastermembergridform.Form.superclass.constructor.call(this, config);
        },
        initComponent: function(){
        
            // hard coded - cannot be changed from outsid
            var config = {
				layout: 'form',
				monitorValid:true,
				autoScroll:true,
				border: false,
				frame: true,
				autoScroll:true,		
				bodyStyle:'padding:5px;',
			//  The two items below will be Ext.Containers, each encapsulated by a <DIV> element.
				items: [gridmastermemhisto1,{ xtype:'fieldset',
							autoheight: true,
							anchor: '100%',
							items:[filterMasterMember]},
							gridmastermemhisto2],
                buttons: [{
                    text: 'Close',
                    id: 'btnClosehargapembelian',
                    scope: this,
                    handler: function(){
                        winaddmastermembergrid.hide();
                    }
                }]
            }; // eo config object
            // apply config
            Ext.apply(this, Ext.apply(this.initialConfig, config));
            
            // call parent
            mastermembergridform.Form.superclass.initComponent.apply(this, arguments);
            
        } // eo function initComponent	
        ,
        onRender: function(){
        
            // call parent
            mastermembergridform.Form.superclass.onRender.apply(this, arguments);
            
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
            
            
            strmasterpelanggan.reload();
            Ext.getCmp('id_formaddmastermembergrid').getForm().reset();
            winaddmastermembergrid.hide();
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
    Ext.reg('formaddmastermembergrid', mastermembergridform.Form);
    
    var winaddmastermembergrid = new Ext.Window({
        id: 'id_maspelwinaddmastermembergrid',
        closeAction: 'hide',
        width: 950,
        height: 535,
        layout: 'fit',
        border: false,
		autoScroll:true,
        items: {
            id: 'id_formaddmastermembergrid',
            xtype: 'formaddmastermembergrid'
        },
        onHide: function(){
            Ext.getCmp('id_formaddmastermembergrid').getForm().reset();
        }
    });
    /**
	var mastermemberpanel = new Ext.FormPanel({
	 	id: 'mastermember',
		border: false,
        frame: false,
		autoScroll:true,	
        items: [mastermember]
	});
	**/
	
	function editmastermember(kd_member,kd_propinsi,kd_kota,kd_kecamatan){
		strcbjenis.load();
		strcbjenisID.load();
		strcbprop.load();
		strcbjeniscab.load();
		cbkakel.store.proxy.conn.url = '<?= site_url("master_member/get_kel") ?>/' + kd_propinsi + '/' + kd_kota+ '/' + kd_kecamatan;
		cbkakel.store.reload();
		cbkakec.store.proxy.conn.url = '<?= site_url("master_member/get_kec") ?>/' + kd_propinsi + '/' + kd_kota;
		cbkakec.store.reload();
		cbkakota.store.proxy.conn.url = '<?= site_url("master_member/get_kota") ?>/' + kd_propinsi;
		cbkakota.store.reload();
		
		Ext.getCmp('btnresetmastermember').hide();
		Ext.getCmp('btnsubmitmastermember').setText('Update');
		
		winaddmastermember.setTitle('Edit Form');
		Ext.getCmp('id_formaddmastermember').getForm().load({
			url: '<?= site_url("master_member/get_row") ?>',
			params: {
				id: kd_member,
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
		winaddmastermember.show();
	}
	
    function deletemastermember(){		
        var sm = mastermember.getSelectionModel();
        var sel = sm.getSelections();
        if (sel.length > 0) {
            Ext.Msg.show({
                title: 'Confirm',
                msg: 'Are you sure delete selected row ?',
                buttons: Ext.Msg.YESNO,
                fn: function(btn){
                    if (btn == 'yes') {
                    
                        var data = '';
                        for (i = 0; i < sel.length; i++) {
                            data = data + sel[i].get('kd_member') + ';';
                        }
                        
                        Ext.Ajax.request({
                            url: '<?= site_url("master_member/delete_rows") ?>',
                            method: 'POST',
                            params: {
                                postdata: data
                            },
							callback:function(opt,success,responseObj){
								var de = Ext.util.JSON.decode(responseObj.responseText);
								if(de.success==true){
	                                strmastermember.load({
	                                    params: {
	                                        start: STARTPAGE,
	                                        limit: ENDPAGE
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
