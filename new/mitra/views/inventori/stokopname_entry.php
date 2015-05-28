<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">	
	//grid data store
	var strstokopname_entry = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
//				{name: 'kd_lokasi', allowBlank: false, type: 'text'},
//				{name: 'kd_blok', allowBlank: false, type: 'text'},
//				{name: 'kd_sub_blok', allowBlank: false, type: 'text'},
				{name: 'kd_produk', allowBlank: false, type: 'text'},
				{name: 'nama_produk', allowBlank: false, type: 'text'},
				{name: 'nm_satuan', allowBlank: false, type: 'text'},
				{name: 'qty', allowBlank: false, type: 'int'},
				{name: 'qty_adjust', allowBlank: false, type: 'int'},
				{name: 'penyesuaian', allowBlank: false, type: 'int'}			
			],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("stok_opname/get_barang_entry") ?>',
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
//	var strcblokasiopname_entry = new Ext.data.Store({
//        reader: new Ext.data.JsonReader({
//            fields: ['kd_lokasi', 'nama_lokasi'],
//            root: 'data',
//            totalProperty: 'record'
//        }),
//        proxy: new Ext.data.HttpProxy({
//            url: '<?= site_url("blok_lokasi/get_all") ?>',
//            method: 'POST'
//        }),
//		listeners: {
//            loadexception: function(event, options, response, error){
//                var err = Ext.util.JSON.decode(response.responseText);
//                if (err.errMsg == 'Session Expired') {
//                    session_expired(err.errMsg);
//                }
//            }
//        }
//    });
//    
//    var cblokasiopname_entry = new Ext.form.ComboBox({
//        fieldLabel: 'Nama Lokasi <span class="asterix">*</span>',
//        id: 'id_cblokasi_opname_entry',
//        store: strcblokasiopname_entry,
//        valueField: 'kd_lokasi',
//        displayField: 'nama_lokasi',
//        typeAhead: true,
//        triggerAction: 'all',
//        allowBlank: false,
//        editable: false,
//        anchor: '90%',
//        hiddenName: 'kd_lokasi',
//        emptyText: 'Pilih Lokasi',
//        listeners: {
//			expand: function(){
//					strstokopname_entry.load({
//						params: {
//							kdLokasi: '',
//							kdBlok: '',
//							kdSubBlok: ''
//						}
//					})
//			},
//            select: function(combo, records) {
//                var kd_cblokasiopname = this.getValue();
//                cbblokopname_entry.setValue();
//                cbblokopname_entry.store.proxy.conn.url = '<?= site_url("sub_blok_lokasi/get_blok") ?>/' + kd_cblokasiopname;
//                cbblokopname_entry.store.reload();
//                cbnoopname_entry.setValue();                    
//                    cbnoopname_entry.store.reload({params:{
//                            kdLokasi: kd_cblokasiopname,
//							kdBlok: '',
//							kdSubBlok: ''
//                    }});
//            }
//        }
//    });
//
//    // combobox blok
//    var strcbblokopname_entry = new Ext.data.Store({
//        reader: new Ext.data.JsonReader({
//            fields: ['kd_blok', 'nama_blok'],
//            root: 'data',
//            totalProperty: 'record'
//        }),
//        proxy: new Ext.data.HttpProxy({
//            url: '<?= site_url("sub_blok_lokasi/get_blok") ?>',
//            method: 'POST'
//        }),
//        listeners: {
//            loadexception: function(event, options, response, error){
//                var err = Ext.util.JSON.decode(response.responseText);
//                if (err.errMsg == 'Session Expired') {
//                    session_expired(err.errMsg);
//                }
//            }
//        }
//    });
//    
//    var cbblokopname_entry = new Ext.form.ComboBox({
//        fieldLabel: 'Nama Blok <span class="asterix">*</span>',
//        id: 'id_cbblok_opname_entry',
//        mode: 'local',
//        store: strcbblokopname_entry,
//        valueField: 'kd_blok',
//        displayField: 'nama_blok',
//        typeAhead: true,
//        triggerAction: 'all',
//        allowBlank: false,
//        editable: false,
//        anchor: '90%',
//        hiddenName: 'kd_blok',
//        emptyText: 'Pilih Blok',
//		listeners: {
//            select: function(combo, records) {
//                var kd_cblokasiopname = Ext.getCmp('id_cblokasi_opname_entry').getValue();
//                var kd_cbblokopname = this.getValue();
//		cbsubblokopname_entry.setValue();
//                cbsubblokopname_entry.store.proxy.conn.url = '<?= site_url("sub_blok_lokasi/get_sub_blok")?>/'+kd_cblokasiopname+'/'+kd_cbblokopname;
//                cbsubblokopname_entry.store.reload();
//                cbnoopname_entry.setValue();                    
//                    cbnoopname_entry.store.reload({params:{
//                            kdLokasi: kd_cblokasiopname,
//							kdBlok: kd_cbblokopname,
//							kdSubBlok: ''
//                    }});
//            }
//        }
//    });
//	
//    // combobox sub_blok
//    var strcbsubblokopname_entry = new Ext.data.Store({
//        reader: new Ext.data.JsonReader({
//            fields: ['kd_sub_blok', 'nama_sub_blok'],
//            root: 'data',
//            totalProperty: 'record'
//        }),
//        proxy: new Ext.data.HttpProxy({
//            url: '<?= site_url("sub_blok_lokasi/get_sub_blok") ?>',
//            method: 'POST'
//        })
//    });
//    
//    var cbsubblokopname_entry = new Ext.form.ComboBox({
//        fieldLabel: 'Nama Sub Blok <span class="asterix">*</span>',
//        id: 'id_cbsubblok_opname_entry',
//        mode: 'local',
//        store: strcbsubblokopname_entry,
//        valueField: 'kd_sub_blok',
//        displayField: 'nama_sub_blok',
//        typeAhead: true,
//        triggerAction: 'all',
//        allowBlank: false,
//        editable: false,
//        anchor: '90%',
//        hiddenName: 'kd_sub_blok',
//        emptyText: 'Pilih Sub Blok',
//		listeners: {
////			expand: function(){
////				strstokopname_entry.reload()
////			},			
//            select: function() {
//                    var kd_ccblokasiopname = Ext.getCmp('id_cblokasi_opname_entry').getValue();
//                    var kd_cbblokopname = Ext.getCmp('id_cbblok_opname_entry').getValue();     
//                    var kd_cbsubblokopname = this.getValue();
//                    cbnoopname_entry.setValue();                    
//                    cbnoopname_entry.store.reload({params:{
//                            kdLokasi: kd_ccblokasiopname,
//							kdBlok: kd_cbblokopname,
//							kdSubBlok: kd_cbsubblokopname
//                    }});
//                
//            }
//        }
//    });
	
        
//   var strcbnoopname_entry = new Ext.data.Store({
//        reader: new Ext.data.JsonReader({
//            fields: ['no_opname', 'tgl_opname','keterangan','nama_lokasi','nama_blok','nama_sub_blok'],
//            root: 'data',
//            totalProperty: 'record'
//        }),
//        proxy: new Ext.data.HttpProxy({
//            url: '<?= site_url("stok_opname/get_headentrystok") ?>',
//            method: 'POST'
//        })
//    });     
//    
//    var cbnoopname_entry = new Ext.form.ComboBox({
//        fieldLabel: 'No.Opname <span class="asterix">*</span>',
//        id: 'id_cbnoopname_entry',
////        mode: 'local',
//        store: strcbnoopname_entry,
//        valueField: 'no_opname',
//        displayField: 'no_opname',
//        typeAhead: true,
//        triggerAction: 'all',
//        allowBlank: false,
//        editable: false,
//        anchor: '90%',
//        hiddenName: 'no_opname',
//        emptyText: 'Pilih No Opname',
//		listeners: {
//			expand: function(){
//				strstokopname_entry.reload()
//			},			
//            select: function() {
//				var vno_opname = this.getValue();
//                                this.store.each(function(node){
//                    if(node.data.no_opname === vno_opname){
//                        Ext.getCmp('id_tanggal_opname_entry').setValue(node.data.tgl_opname);
//                        Ext.getCmp('so_keterangan_entry').setValue(node.data.keterangan); 
//                        var vlokasi=node.data.nama_lokasi + "-" + node.data.nama_blok + "-" + node.data.nama_sub_blok;
//                        Ext.getCmp('id_lokasi_entry').setValue(vlokasi);
//                    }
//                    
//                });
//                strstokopname_entry.load({
//                                params: {
//                                    no_opname: vno_opname
//                                },
//								callback: function(r, options, response) {
//									if(r.length == 0){
//										Ext.getCmp('so_kd_produk_entry').setValue("");
//										Ext.getCmp('so_nama_produk_entry').setValue("");
//										Ext.getCmp('so_nm_satuan_entry').setValue("");
//										Ext.getCmp('so_qty_entry').setValue("");
//										Ext.getCmp('so_qty_adjust_entry').setValue("");
//										Ext.getCmp('so_penyesuaian_entry').setValue("");
//									}else{
//										Ext.getCmp('so_kd_produk_entry').setValue(r[0].data.kd_produk);
//										Ext.getCmp('so_nama_produk_entry').setValue(r[0].data.nama_produk);
//										Ext.getCmp('so_nm_satuan_entry').setValue(r[0].data.nm_satuan);
//										Ext.getCmp('so_qty_entry').setValue(r[0].data.qty_oh);
//										Ext.getCmp('so_qty_adjust_entry').setValue(r[0].data.qty_adjust);
//										Ext.getCmp('so_penyesuaian_entry').setValue(r[0].data.penyesuaian);
//									}							    										
//							    }
//                            });
//            }
//        }
//    });

var strcb_opname_entry = new Ext.data.ArrayStore({
        fields: ['no_opname'],
        data : []
    });
	
    var strgrid_opname_entry = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_opname', 'tgl_opname','keterangan','nama_lokasi','nama_blok','nama_sub_blok'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("stok_opname/get_headentrystok") ?>',
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
	
    var searchgrid_opname_entry = new Ext.app.SearchField({
        store: strgrid_opname_entry,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgrid_opname_entry'
    });
	
	
    var grid_opname_entry = new Ext.grid.GridPanel({
        store: strgrid_opname_entry,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'No Opname',
                dataIndex: 'no_opname',
                width: 80,
                sortable: true		
            
            },{
                header: 'Tanggal',
                dataIndex: 'tgl_opname',
                width: 100,
                sortable: true        
            },{
                header: 'Lokasi',
                dataIndex: 'nama_lokasi',
                width: 100,
                sortable: true        
            },{
                header: 'Blok',
                dataIndex: 'nama_blok',
                width: 100,
                sortable: true        
            },{
                header: 'Sub Blok',
                dataIndex: 'nama_sub_blok',
                width: 100,
                sortable: true        
            },{
                header: 'Keterangan',
                dataIndex: 'keterangan',
                width: 100,
                sortable: true        
            }],
        tbar: new Ext.Toolbar({
            items: [searchgrid_opname_entry]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgrid_opname_entry,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {	
                    Ext.getCmp('id_tanggal_opname_entry').setValue(sel[0].get('tgl_opname'));
                    Ext.getCmp('so_keterangan_entry').setValue(sel[0].get('keterangan')); 
                    var vlokasi=sel[0].get('nama_lokasi') + "-" + sel[0].get('nama_blok')  + "-" + sel[0].get('nama_sub_blok') ;
                    Ext.getCmp('id_lokasi_entry').setValue(vlokasi);	
                    Ext.getCmp('id_cbnoopname_entry').setValue(sel[0].get('no_opname'));
                    
                    var vno_opname = sel[0].get('no_opname'); 
                    strstokopname_entry.load({
                                params: {
                                    no_opname: vno_opname
                                },
								callback: function(r, options, response) {
									if(r.length == 0){
										Ext.getCmp('so_kd_produk_entry').setValue("");
										Ext.getCmp('so_nama_produk_entry').setValue("");
										Ext.getCmp('so_nm_satuan_entry').setValue("");
										Ext.getCmp('so_qty_entry').setValue("");
										Ext.getCmp('so_qty_adjust_entry').setValue("");
										Ext.getCmp('so_penyesuaian_entry').setValue("");
									}else{
										Ext.getCmp('so_kd_produk_entry').setValue(r[0].data.kd_produk);
										Ext.getCmp('so_nama_produk_entry').setValue(r[0].data.nama_produk);
										Ext.getCmp('so_nm_satuan_entry').setValue(r[0].data.nm_satuan);
										Ext.getCmp('so_qty_entry').setValue(r[0].data.qty_oh);
										Ext.getCmp('so_qty_adjust_entry').setValue(r[0].data.qty_adjust);
										Ext.getCmp('so_penyesuaian_entry').setValue(r[0].data.penyesuaian);
									}							    										
							    }
                            });     
                    menu_opname_entry.hide();
                }
            }
        }
    });
	
    var menu_opname_entry = new Ext.menu.Menu();
    menu_opname_entry.add(new Ext.Panel({
        title: 'Pilih No Opname',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [grid_opname_entry],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menu_opname_entry.hide();
                }
            }]
    }));
    
    Ext.ux.TwinComboOpnameEntry = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgrid_opname_entry.load();
            menu_opname_entry.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menu_opname_entry.on('hide', function(){
        var sf = Ext.getCmp('id_searchgrid_opname_entry').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgrid_opname_entry').setValue('');
            searchgrid_sj_ekspedisi.onTrigger2Click();
        }
    });
	
    var cbnoopname_entry = new Ext.ux.TwinComboOpnameEntry({
        fieldLabel: 'No. Opname <span class="asterix">*</span>',
        id: 'id_cbnoopname_entry',
        store: strcb_opname_entry,
        mode: 'local',
        valueField: 'no_opname',
        displayField: 'no_opname',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'no_opname',
        emptyText: 'Pilih No Opname'
    });
    var headerstokopname_entry = {
        layout: 'column',
        border: false,
        items: [{
            columnWidth: .5,
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: { labelSeparator: ''},
            items: [
//                cblokasiopname_entry, cbblokopname_entry, cbsubblokopname_entry,
                cbnoopname_entry,
                 {
                xtype: 'textarea',
                fieldLabel: 'Lokasi',
                name: 'lokasi',
                readOnly:true,
			//	fieldClass:'readonly-input',
                id: 'id_lokasi_entry',                
                anchor: '90%',
                value:''
            }
            ]
        }, {
            columnWidth: .5,
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: { labelSeparator: ''},
            items: [{
                xtype: 'textfield',
                fieldLabel: 'Tanggal',
                name: 'tanggal_opname',
				fieldClass:'readonly-input',
                readOnly:true,
                id: 'id_tanggal_opname_entry',                
                anchor: '90%',
                value: ''
			},{ xtype: 'textarea',
				fieldLabel: 'Keterangan',
				name: 'keterangan',                                    
				id: 'so_keterangan_entry',                                      
				anchor: '90%' 
                                ,readOnly:true
			}]
        }]
    }
    
    var editorstokopname_entry = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });

    
    var gridstokopname_entry = new Ext.grid.GridPanel({
        store: strstokopname_entry,
        stripeRows: true,
        height: 280,
        frame: true,
        border:true,
        plugins: [editorstokopname_entry],
        columns: [{
            header: 'Kode Barang',
            dataIndex: 'kd_produk',
            width: 110,
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'so_kd_produk_entry'
            })
        },{
            header: 'Nama Barang',
            dataIndex: 'nama_produk',
            width: 400,
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'so_nama_produk_entry'
            })
        },{
            header: 'Satuan',
            dataIndex: 'nm_satuan',
            width: 80,
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'so_nm_satuan_entry'
            })
        },{
            xtype: 'numbercolumn',
            header: 'Qty',
            dataIndex: 'qty',           
            width: 70,
			align: 'center',
            sortable: true,
            format: '0,0',
            editor: {
				xtype: 'numberfield',
				readOnly: true,
                id: 'so_qty_entry'
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
                id: 'so_qty_adjust_entry',
                allowBlank: false,
				selectOnFocus: true,
				listeners:{
					'change':function(){
						var qty_oh = Ext.getCmp('so_qty_entry').getValue();
						var qty_adjust = this.getValue();
						var penyesuaian = qty_oh-qty_adjust;
						Ext.getCmp('so_penyesuaian_entry').setValue(penyesuaian);
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
                id: 'so_penyesuaian_entry',
            }
        }]
    });
	
    // combobox akun penyeesuaian
    var strcbakunopname_entry = new Ext.data.Store({
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
//    var cbakunopname_entry = new Ext.form.ComboBox({
//        fieldLabel: 'Akun Beban Penyusutan <span class="asterix">*</span>',
//        id: 'id_cbpenyesuaian_opname_entry',
//        mode: 'local',
//        store: strcbakunopname_entry,
//        valueField: 'value',
//        displayField: 'display',
//        typeAhead: true,
//        triggerAction: 'all',
//        editable: false,
//        anchor: '90%',
//        hiddenName: 'value',
//        emptyText: 'Pilih Akun'	
//        ,readOnly:true
//    });
	
	
    var stokopname_entry = new Ext.FormPanel({
        id: 'id-stokopname_entry',
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
                    items: [headerstokopname_entry]
                },
                gridstokopname_entry,
                
//                {
//                    layout: 'column',
//                    border: false,
//                    items: [{
//							columnWidth: 1,
//							style:'margin:6px 3px 0 0;',
//							layout: 'form', 
//							labelWidth: 125,
//							buttonAlign: 'left',           
//							items: [cbakunopname_entry]
//						}]
//                }
        ],
        buttons: [{
            text: 'Save',
			formBind: true,
            handler: function(){
                
                var detailstokopname = new Array();              
                strstokopname_entry.each(function(node){
                    detailstokopname.push(node.data)
                });
                Ext.getCmp('id-stokopname_entry').getForm().submit({
                    url: '<?= site_url("stok_opname/update_entryrow") ?>',
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
                        
                        clearstokopname_entry();                       
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
                clearstokopname_entry();
            }
        }]
    });
    
	var stokopnamepanel_entry = new Ext.Panel({
	 	id: 'stokopname_entry',
		border: false,
        frame: false,
		autoScroll:true,	
        items: [stokopname_entry]
	});
	
    stokopname_entry.on('afterrender', function(){
//        this.getForm().load({
//            url: '<?= site_url("stok_opname/get_form") ?>',
//            failure: function(form, action){
//                var de = Ext.util.JSON.decode(action.response.responseText);
//                Ext.Msg.show({
//                        title: 'Error',
//                        msg: de.errMsg,
//                        modal: true,
//                        icon: Ext.Msg.ERROR,
//                        buttons: Ext.Msg.OK,
//                        fn: function(btn){
//                            if (btn == 'ok' && de.errMsg == 'Session Expired') {
//                                window.location = '<?= site_url("auth/login") ?>';
//                            }
//                        }
//                    });
//            }
//        });
    });
    
    function clearstokopname_entry(){
        Ext.getCmp('id-stokopname_entry').getForm().reset();
//        Ext.getCmp('id-stokopname_entry').getForm().load({
//            url: '<?= site_url("stok_opname/get_form") ?>',
//            failure: function(form, action){
//                var de = Ext.util.JSON.decode(action.response.responseText);
//                Ext.Msg.show({
//                        title: 'Error',
//                        msg: de.errMsg,
//                        modal: true,
//                        icon: Ext.Msg.ERROR,
//                        buttons: Ext.Msg.OK,
//                        fn: function(btn){
//                            if (btn == 'ok' && de.errMsg == 'Session Expired') {
//                                window.location = '<?= site_url("auth/login") ?>';
//                            }
//                        }
//                    });
//            }
//        });
        strstokopname_entry.removeAll();
        strgrid_opname_entry.reload();
    }
</script>