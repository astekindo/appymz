<?php if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">	
    var strinitstokopname = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
//				{name: 'kd_lokasi', allowBlank: false, type: 'text'},
//				{name: 'kd_blok', allowBlank: false, type: 'text'},
//				{name: 'kd_sub_blok', allowBlank: false, type: 'text'},
				{name: 'kd_produk', allowBlank: false, type: 'text'},
				{name: 'nama_produk', allowBlank: false, type: 'text'},
				{name: 'nm_satuan', allowBlank: false, type: 'text'},
				{name: 'qty_oh', allowBlank: false, type: 'int'}
//                                ,
//				{name: 'qty_adjust', allowBlank: false, type: 'int'},
//				{name: 'penyesuaian', allowBlank: false, type: 'int'}	
//                                ,{name: 'nama_lokasi', allowBlank: false, type: 'text'},
//				{name: 'nama_blok', allowBlank: false, type: 'text'},
//				{name: 'nama_sub_blok', allowBlank: false, type: 'text'}
			],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("stok_opname/get_initstok") ?>',
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
	var strinitcblokasiopname = new Ext.data.Store({
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
    
    var cbinitlokasiopname = new Ext.form.ComboBox({
        fieldLabel: 'Nama Lokasi',
        id: 'id_initcblokasi_opname',
        store: strinitcblokasiopname,
        valueField: 'kd_lokasi',
        displayField: 'nama_lokasi',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_lokasi',
        emptyText: 'Pilih Lokasi',
        listeners: {
//			expand: function(){
//					strinitstokopname.load({
//						params: {
//							kdLokasi: '',
//							kdBlok: '',
//							kdSubBlok: ''
//						}
//					})
//			},
            select: function(combo, records) {
                var kd_cblokasiopname = this.getValue();
                cbinitblokopname.setValue();
                cbinitblokopname.store.proxy.conn.url = '<?= site_url("sub_blok_lokasi/get_blok") ?>/' + kd_cblokasiopname;
                cbinitblokopname.store.reload();
            }
        }
    });

    // combobox blok
    var strinitcbblokopname = new Ext.data.Store({
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
    
    var cbinitblokopname = new Ext.form.ComboBox({
        fieldLabel: 'Nama Blok',
        id: 'id_initcbblok_opname',
        mode: 'local',
        store: strinitcbblokopname,
        valueField: 'kd_blok',
        displayField: 'nama_blok',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_blok',
        emptyText: 'Pilih Blok',
		listeners: {
            select: function(combo, records) {
                var kd_cblokasiopname = Ext.getCmp('id_initcblokasi_opname').getValue();
                var kd_cbblokopname = this.getValue();
				cbinitsubblokopname.setValue();
                cbinitsubblokopname.store.proxy.conn.url = '<?= site_url("sub_blok_lokasi/get_sub_blok")?>/'+kd_cblokasiopname+'/'+kd_cbblokopname;
                cbinitsubblokopname.store.reload();
            }
        }
    });
	
    // combobox sub_blok
    var strinitcbsubblokopname = new Ext.data.Store({
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
    
    var cbinitsubblokopname = new Ext.form.ComboBox({
        fieldLabel: 'Nama Sub Blok',
        id: 'id_initcbsubblok_opname',
        mode: 'local',
        store: strinitcbsubblokopname,
        valueField: 'kd_sub_blok',
        displayField: 'nama_sub_blok',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_sub_blok',
        emptyText: 'Pilih Sub Blok'
    });
	
    var headerinitstokopname = {
        layout: 'column',
        border: false,
        items: [
            
//    {
//            columnWidth: .5,
//            layout: 'form',
//            border: false,
//            labelWidth: 100,
//            defaults: { labelSeparator: ''},
//            items: [cbinitlokasiopname, cbinitblokopname, cbinitsubblokopname]
//        }, 
                
    {
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
                id: 'id_init_no_opname',                
                anchor: '90%',
                value:''
            }, {
                xtype: 'textfield',
                fieldLabel: 'Tanggal',
                name: 'tanggal_opname',
				fieldClass:'readonly-input',
                readOnly:true,
                id: 'id_init_tanggal_opname',                
                anchor: '90%',
                value: ''
			}]
        }]
    }
    
    var editorinitstokopname = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });

    
    var gridinitstokopname = new Ext.grid.GridPanel({
        store: strinitstokopname,
        stripeRows: true,
        height: 280,
        frame: true,
        border:true,
       // plugins: [editorinitstokopname],
        columns: [{
            header: 'Kode Barang',
            dataIndex: 'kd_produk',
            width: 110,
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'initso_kd_produk'
            })
        },{
            header: 'Nama Barang',
            dataIndex: 'nama_produk',
            width: 400,
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'initso_nama_produk'
            })
        },{
            header: 'Satuan',
            dataIndex: 'nm_satuan',
            width: 80,
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'initso_nm_satuan'
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
                id: 'initso_qty'
            }
        }
//        ,{
//            xtype: 'numbercolumn',
//            header: 'Qty Adjust',
//            dataIndex: 'qty_adjust',           
//            width: 70,
//			align: 'center',
//            sortable: true,
//            format: '0,0',
//            editor: {
//                xtype: 'numberfield',
//                id: 'initso_qty_adjust',
//                allowBlank: false,
//				selectOnFocus: true,
//				listeners:{
//					'change':function(){
//						var qty_oh = Ext.getCmp('initso_qty').getValue();
//						var qty_adjust = this.getValue();
//						var penyesuaian = qty_oh-qty_adjust;
//						Ext.getCmp('initso_penyesuaian').setValue(penyesuaian);
//					}
//				}
//				
//            }			
//        },{
//            xtype: 'numbercolumn',
//            header: 'Penyesuaian',
//            dataIndex: 'penyesuaian',           
//            width: 90,
//			align: 'center',
//            sortable: true,
//            format: '0,0',
//            editor: {
//				xtype: 'numberfield',
//				readOnly: true,
//                id: 'initso_penyesuaian',
//            }
//        }
]
//,tbar: [{
////                icon: BASE_ICONS + 'add.png',
//                text: 'Load',
//                handler: function(){
//                    var kd_ccblokasiopname = Ext.getCmp('id_cblokasi_opname').getValue();
//                    var kd_cbblokopname = Ext.getCmp('id_cbblok_opname').getValue();
//                    var kd_cbsubblokopname = Ext.getCmp('id_initcbsubblok_opname').getValue();
//                    strinitstokopname.reload({params:
//                            {kdLokasi:kd_ccblokasiopname,
//                            kdBlok:kd_cbblokopname,
//                            kdSubBlok:kd_cbsubblokopname
//                            }
//                        }
//                        );
//                }
//                }]
    });
	
    // combobox akun penyeesuaian
    var strinitcbakunopname = new Ext.data.Store({
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
//    var cbinitakunopname = new Ext.form.ComboBox({
//        fieldLabel: 'Akun Beban Penyusutan <span class="asterix">*</span>',
//        id: 'id_cbinitpenyesuaian_opname',
//        mode: 'local',
//        store: strinitcbakunopname,
//        valueField: 'value',
//        displayField: 'display',
//        typeAhead: true,
//        triggerAction: 'all',
//        editable: false,
//        anchor: '90%',
//        hiddenName: 'value',
//        emptyText: 'Pilih Akun'	
//    });

    // combobox kategori1
    var str_transbrg_cbkategori1 = new Ext.data.Store({
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
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var transbrg_cbkategori1 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 1',
        id: 'mb_transbrg_cbkategori1',
        store: str_transbrg_cbkategori1,
        valueField: 'kd_kategori1',
        displayField: 'nama_kategori1',
        typeAhead: true,
        triggerAction: 'all',
        //allowBlank: false,
        editable: false,
		width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori1',
        emptyText: 'Pilih kategori 1',
        listeners: {
            'select': function(combo, records) {
                var kdbrg_cbkategori1 = transbrg_cbkategori1.getValue();
                // brg_cbkategori2.setValue();
				transbrg_cbkategori2.setValue();
                transbrg_cbkategori2.store.proxy.conn.url = '<?= site_url("kategori3/get_kategori2") ?>/' + kdbrg_cbkategori1;
                transbrg_cbkategori2.store.reload();
            }
        }
    });
    // combobox kategori2
    var str_transbrg_cbkategori2 = new Ext.data.Store({
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
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var transbrg_cbkategori2 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 2',
        id: 'mb_transbrg_cbkategori2',
		mode: 'local',
        store: str_transbrg_cbkategori2,
        valueField: 'kd_kategori2',
        displayField: 'nama_kategori2',
        typeAhead: true,
        triggerAction: 'all',
        //allowBlank: false,
        editable: false,
		width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori2',
        emptyText: 'Pilih kategori 2',
        listeners: {
            select: function(combo, records) {
                var kd_brg_cbkategori1 = transbrg_cbkategori1.getValue();
                var kd_brg_cbkategori2 = this.getValue();
                transbrg_cbkategori3.setValue();
                transbrg_cbkategori3.store.proxy.conn.url = '<?= site_url("kategori4/get_kategori3") ?>/' + kd_brg_cbkategori1 +'/'+ kd_brg_cbkategori2;
                transbrg_cbkategori3.store.reload();
            }
        }
    });
	
    // combobox kategori3
    var str_transbrg_cbkategori3 = new Ext.data.Store({
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
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
	
    var transbrg_cbkategori3 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 3',
        id: 'mb_transbrg_cbkategori3',
        mode: 'local',
        store: str_transbrg_cbkategori3,
        valueField: 'kd_kategori3',
        displayField: 'nama_kategori3',
        typeAhead: true,
        triggerAction: 'all',
        //allowBlank: false,
        editable: false,
		width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori3',
        emptyText: 'Pilih kategori 3',
        listeners: {
            select: function(combo, records) {
                var kd_brg_cbkategori1 = transbrg_cbkategori1.getValue();
                var kd_brg_cbkategori2 = transbrg_cbkategori2.getValue();
                var kd_brg_cbkategori3 = this.getValue();
                transbrg_cbkategori4.setValue();
                transbrg_cbkategori4.store.proxy.conn.url = '<?= site_url("master_barang/get_kategori4") ?>/' + kd_brg_cbkategori1 +'/'+ kd_brg_cbkategori2 +'/'+ kd_brg_cbkategori3;
                transbrg_cbkategori4.store.reload();
            }
        }
    });
	
    // combobox kategori4
    var str_transbrg_cbkategori4 = new Ext.data.Store({
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
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var transbrg_cbkategori4 = new Ext.form.ComboBox({   
        fieldLabel: 'Kategori 4',
        id: 'mb_transbrg_cbkategori4',
        mode: 'local',
        store: str_transbrg_cbkategori4,
        valueField: 'kd_kategori4',
        displayField: 'nama_kategori4',
        typeAhead: true,
        triggerAction: 'all',
        //allowBlank: false,
        editable: false,
		width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori4',
        emptyText: 'Pilih kategori 4'
    });

    var groupfilterinitialso={
        xtype:'fieldset',
        id:'group_filter_initial_so',
        title: 'Filter',
        autoHeight:true,
        collapsed: true,
        collapsible: true,
        layout: 'column',
        items :[{columnWidth: .4,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [
                    transbrg_cbkategori1,transbrg_cbkategori2, transbrg_cbkategori3,transbrg_cbkategori4
                ]
            },{columnWidth: .4,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [
                    cbinitlokasiopname, cbinitblokopname, cbinitsubblokopname
                ]
            }
        ]
    }
	
    var headerbuttonFilter = {
        layout: {
            type: 'table',
            columns: 2
        },
        border: false,
        items: [{
                xtype: 'button',
                text: 'Filter',
                width: 75,
                handler: function(){
                    var kd_ccblokasiopname = Ext.getCmp('id_initcblokasi_opname').getValue();
                    var kd_cbblokopname = Ext.getCmp('id_initcbblok_opname').getValue();
                    var kd_cbsubblokopname = Ext.getCmp('id_initcbsubblok_opname').getValue();
                    var kd_kategori1 = Ext.getCmp('mb_transbrg_cbkategori1').getValue();
                    var kd_kategori2 = Ext.getCmp('mb_transbrg_cbkategori2').getValue();
                    var kd_kategori3 = Ext.getCmp('mb_transbrg_cbkategori3').getValue();
                    var kd_kategori4 = Ext.getCmp('mb_transbrg_cbkategori4').getValue();
                    strinitstokopname.reload({
                        params: {
                            kdLokasi: kd_ccblokasiopname,
                            kdBlok: kd_cbblokopname,
                            kdSubBlok: kd_cbsubblokopname,
                            kdKat1: kd_kategori1,
                            kdKat2: kd_kategori2,
                            kdKat3: kd_kategori3,
                            kdKat4: kd_kategori4
                        }
                    });
                }
            }, { 
                xtype: 'button',
                text: 'Reset',
                width: 75,
                handler: function(){
                    Ext.getCmp('id_initcblokasi_opname').setValue('');
                    Ext.getCmp('id_initcbblok_opname').setValue('');
                    Ext.getCmp('id_initcbsubblok_opname').setValue('');		        
                    Ext.getCmp('mb_transbrg_cbkategori1').setValue('');		        
                    Ext.getCmp('mb_transbrg_cbkategori2').setValue('');		        
                    Ext.getCmp('mb_transbrg_cbkategori3').setValue('');		        
                    Ext.getCmp('mb_transbrg_cbkategori4').setValue('');		        
                    strinitstokopname.removeAll();
                }
            }]
        } 
        
    var initstokopname = new Ext.FormPanel({
        id: 'id-initstokopname-gridpanel',
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
                    items: [headerinitstokopname]
                },
                {
                    bodyStyle: {
                        margin: '0px 0px 5px 0px'
                    },                  
                    items: [groupfilterinitialso]
                },
                {
                    bodyStyle: {
                        margin: '0px 0px 5px 0px'
                    },                  
                    items: [headerbuttonFilter]
                },
                gridinitstokopname,{
                    layout: 'column',
                    border: false,
                    items: [{
                                columnWidth: 1,
                                style:'margin:6px 3px 0 0;',
                                layout: 'form', 
                                labelWidth: 125,
                                buttonAlign: 'left',           
                                items: [{ xtype: 'textarea',
				fieldLabel: 'Keterangan <span class="asterix">*</span>',
				name: 'keterangan',                                    
				id: 'so_keterangan',
                                allowBlank: false,
				anchor: '90%' 
			}]
						}]
                }
        ],
        buttons: [{
            text: 'Save',
			formBind: true,
            handler: function(){
                
                var detailstokopname = new Array();              
                strinitstokopname.each(function(node){
                    detailstokopname.push(node.data)
                });
                Ext.getCmp('id-initstokopname-gridpanel').getForm().submit({
                    url: '<?= site_url("stok_opname/update_initialrow") ?>',
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
    
	var initstokopnamepanel = new Ext.Panel({
	 	id: 'initialstokopname',
		border: false,
        frame: false,
		autoScroll:true,	
        items: [initstokopname]
	});
	
    initstokopname.on('afterrender', function(){
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
        strinitcbakunopname.load();
    });
    
    function clearstokopname(){
        Ext.getCmp('id-initstokopname-gridpanel').getForm().reset();
        Ext.getCmp('id-initstokopname-gridpanel').getForm().load({
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
        strinitstokopname.removeAll();
    }
</script>
