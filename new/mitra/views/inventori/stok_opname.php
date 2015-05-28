<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">	
	//grid data store
	var strstokopname = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
				{name: 'kd_lokasi', allowBlank: false, type: 'text'},
				{name: 'kd_blok', allowBlank: false, type: 'text'},
				{name: 'kd_sub_blok', allowBlank: false, type: 'text'},
				{name: 'kd_produk', allowBlank: false, type: 'text'},
				{name: 'nama_produk', allowBlank: false, type: 'text'},
				{name: 'nm_satuan', allowBlank: false, type: 'text'},
				{name: 'qty_oh', allowBlank: false, type: 'int'},
				{name: 'qty_adjust', allowBlank: false, type: 'int'},
				{name: 'penyesuaian', allowBlank: false, type: 'int'}			
			],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("stok_opname/get_barang") ?>',
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
	
	// combobox lokasi
	var strcblokasiopname = new Ext.data.Store({
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
    
    var cblokasiopname = new Ext.form.ComboBox({
        fieldLabel: 'Nama Lokasi <span class="asterix">*</span>',
        id: 'id_cblokasi_opname',
        store: strcblokasiopname,
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
			expand: function(){
					strstokopname.load({
						params: {
							kdLokasi: '',
							kdBlok: '',
							kdSubBlok: ''
						}
					})
			},
            select: function(combo, records) {
                var kd_cblokasiopname = this.getValue();
                cbblokopname.setValue();
                cbblokopname.store.proxy.conn.url = '<?= site_url("sub_blok_lokasi/get_blok") ?>/' + kd_cblokasiopname;
                cbblokopname.store.reload();
            }
        }
    });

    // combobox blok
    var strcbblokopname = new Ext.data.Store({
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
    
    var cbblokopname = new Ext.form.ComboBox({
        fieldLabel: 'Nama Blok <span class="asterix">*</span>',
        id: 'id_cbblok_opname',
        mode: 'local',
        store: strcbblokopname,
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
                var kd_cblokasiopname = Ext.getCmp('id_cblokasi_opname').getValue();
                var kd_cbblokopname = this.getValue();
				cbsubblokopname.setValue();
                cbsubblokopname.store.proxy.conn.url = '<?= site_url("sub_blok_lokasi/get_sub_blok")?>/'+kd_cblokasiopname+'/'+kd_cbblokopname;
                cbsubblokopname.store.reload();
            }
        }
    });
	
    // combobox sub_blok
    var strcbsubblokopname = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_sub_blok', 'nama_sub_blok'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("sub_blok_lokasi/get_sub_blok") ?>',
            method: 'POST'
        })
    });
    
    var cbsubblokopname = new Ext.form.ComboBox({
        fieldLabel: 'Nama Sub Blok <span class="asterix">*</span>',
        id: 'id_cbsubblok_opname',
        mode: 'local',
        store: strcbsubblokopname,
        valueField: 'kd_sub_blok',
        displayField: 'nama_sub_blok',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_sub_blok',
        emptyText: 'Pilih Sub Blok',
		listeners: {
			expand: function(){
				strstokopname.reload()
			},			
            select: function() {
				var kd_ccblokasiopname = Ext.getCmp('id_cblokasi_opname').getValue();
				var kd_cbblokopname = Ext.getCmp('id_cbblok_opname').getValue();
                strstokopname.load({
                                params: {
                                    kdLokasi: kd_ccblokasiopname,
									kdBlok: kd_cbblokopname,
									kdSubBlok: this.getValue()
                                },
								callback: function(r, options, response) {
									if(r.length == 0){
										Ext.getCmp('so_kd_produk').setValue("");
										Ext.getCmp('so_nama_produk').setValue("");
										Ext.getCmp('so_nm_satuan').setValue("");
										Ext.getCmp('so_qty').setValue("");
										Ext.getCmp('so_qty_adjust').setValue("");
										Ext.getCmp('so_penyesuaian').setValue("");
									}else{
										Ext.getCmp('so_kd_produk').setValue(r[0].data.kd_produk);
										Ext.getCmp('so_nama_produk').setValue(r[0].data.nama_produk);
										Ext.getCmp('so_nm_satuan').setValue(r[0].data.nm_satuan);
										Ext.getCmp('so_qty').setValue(r[0].data.qty_oh);
										Ext.getCmp('so_qty_adjust').setValue(r[0].data.qty_adjust);
										Ext.getCmp('so_penyesuaian').setValue(r[0].data.penyesuaian);
									}							    										
							    }
                            });
            }
        }
    });
	
    var headerstokopname = {
        layout: 'column',
        border: false,
        items: [{
            columnWidth: .5,
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: { labelSeparator: ''},
            items: [cblokasiopname, cbblokopname, cbsubblokopname]
        }, {
            columnWidth: .5,
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: { labelSeparator: ''},
            items: [ {
                xtype: 'textfield',
                fieldLabel: 'No. Opname',
                name: 'no_opname',
                readOnly:true,
				fieldClass:'readonly-input',
                id: 'id_no_opname',                
                anchor: '90%',
                value:''
            }, {
                xtype: 'textfield',
                fieldLabel: 'Tanggal',
                name: 'tanggal_opname',
				fieldClass:'readonly-input',
                readOnly:true,
                id: 'id_tanggal_opname',                
                anchor: '90%',
                value: ''
			},{ xtype: 'textarea',
				fieldLabel: 'Keterangan',
				name: 'keterangan',                                    
				id: 'so_keterangan',                                      
				anchor: '90%' 
			}]
        }]
    }
    
    var editorstokopname = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });

    
    var gridstokopname = new Ext.grid.GridPanel({
        store: strstokopname,
        stripeRows: true,
        height: 280,
        frame: true,
        border:true,
        plugins: [editorstokopname],
        columns: [{
            header: 'Kode Barang',
            dataIndex: 'kd_produk',
            width: 110,
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'so_kd_produk'
            })
        },{
            header: 'Nama Barang',
            dataIndex: 'nama_produk',
            width: 400,
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'so_nama_produk'
            })
        },{
            header: 'Satuan',
            dataIndex: 'nm_satuan',
            width: 80,
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'so_nm_satuan'
            })
        },{
            xtype: 'numbercolumn',
            header: 'Qty',
            dataIndex: 'qty_oh',           
            width: 70,
			align: 'center',
            sortable: true,
            format: '0,0',
            editor: {
				xtype: 'numberfield',
				readOnly: true,
                id: 'so_qty',
            }
        },{
            xtype: 'numbercolumn',
            header: 'Qty Adjust',
            dataIndex: 'qty_adjust',           
            width: 70,
			align: 'center',
            sortable: true,
            format: '0,0',
            editor: {
                xtype: 'numberfield',
                id: 'so_qty_adjust',
                allowBlank: false,
				selectOnFocus: true,
				listeners:{
					'change':function(){
						var qty_oh = Ext.getCmp('so_qty').getValue();
						var qty_adjust = this.getValue();
						var penyesuaian = qty_oh-qty_adjust;
						Ext.getCmp('so_penyesuaian').setValue(penyesuaian);
					}
				}
				
            }			
        },{
            xtype: 'numbercolumn',
            header: 'Penyesuaian',
            dataIndex: 'penyesuaian',           
            width: 90,
			align: 'center',
            sortable: true,
            format: '0,0',
            editor: {
				xtype: 'numberfield',
				readOnly: true,
                id: 'so_penyesuaian',
            }
        }]
    });
	
    // combobox akun penyeesuaian
    var strcbakunopname = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['value', 'display'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("stok_opname/get_akun_penyesuaian") ?>',
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
    var cbakunopname = new Ext.form.ComboBox({
        fieldLabel: 'Akun Beban Penyusutan <span class="asterix">*</span>',
        id: 'id_cbpenyesuaian_opname',
        mode: 'local',
        store: strcbakunopname,
        valueField: 'value',
        displayField: 'display',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        anchor: '90%',
        hiddenName: 'value',
        emptyText: 'Pilih Akun'	
    });
	
	
    var stokopname = new Ext.FormPanel({
        id: 'id-stokopname-gridpanel',
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
                    items: [headerstokopname]
                },
                gridstokopname,{
                    layout: 'column',
                    border: false,
                    items: [{
							columnWidth: 1,
							style:'margin:6px 3px 0 0;',
							layout: 'form', 
							labelWidth: 125,
							buttonAlign: 'left',           
							items: [cbakunopname]
						}]
                }
        ],
        buttons: [{
            text: 'Save',
			formBind: true,
            handler: function(){
                
                var detailstokopname = new Array();              
                strstokopname.each(function(node){
                    detailstokopname.push(node.data)
                });
                Ext.getCmp('stokopname').getForm().submit({
                    url: '<?= site_url("stok_opname/update_row") ?>',
                    scope: this,
                    params: {
                      detail: Ext.util.JSON.encode(detailstokopname)
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
                        
                        clearstokopname();                       
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
                clearstokopname();
            }
        }]
    });
    
	var stokopnamepanel = new Ext.Panel({
	 	id: 'stokopname',
		border: false,
        frame: false,
		autoScroll:true,	
        items: [stokopname]
	});
	
    stokopname.on('afterrender', function(){
        this.getForm().load({
            url: '<?= site_url("stok_opname/get_form") ?>',
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
    
    function clearstokopname(){
        Ext.getCmp('stokopname').getForm().reset();
        Ext.getCmp('stokopname').getForm().load({
            url: '<?= site_url("stok_opname/get_form") ?>',
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
        strstokopname.removeAll();
    }
</script>