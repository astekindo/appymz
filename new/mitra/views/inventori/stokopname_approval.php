<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">	
	//grid data store
	var strstokopname_approval = new Ext.data.Store({
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
				{name: 'qty_penyesuaian', allowBlank: false, type: 'int'}			
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
	


var strcb_opname_approval = new Ext.data.ArrayStore({
        fields: ['no_opname'],
        data : []
    });
	
    var strgrid_opname_approval = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_opname', 'tgl_opname','keterangan','kd_lokasi','nama_lokasi',
                'kd_blok','nama_blok','kd_sub_blok','nama_sub_blok'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("stok_opname/get_headapprovalstok") ?>',
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
	
    var searchgrid_opname_approval = new Ext.app.SearchField({
        store: strgrid_opname_approval,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgrid_opname_approval'
    });
	
	
    var grid_opname_approval = new Ext.grid.GridPanel({
        store: strgrid_opname_approval,
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
            },{
                header: 'Kode Lokasi',
                dataIndex: 'kd_lokasi',
                width: 100,
                sortable: true        
            },{
                header: 'Kode Blok',
                dataIndex: 'kd_blok',
                width: 100,
                sortable: true        
            },{
                header: 'Kode Sub Blok',
                dataIndex: 'kd_sub_blok',
                width: 100,
                sortable: true        
            }],
        tbar: new Ext.Toolbar({
            items: [searchgrid_opname_approval]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgrid_opname_approval,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {	
                    Ext.getCmp('id_tanggal_opname_approval').setValue(sel[0].get('tgl_opname'));
                    Ext.getCmp('so_keterangan_approval').setValue(sel[0].get('keterangan')); 
                    var vlokasi=sel[0].get('nama_lokasi') + "-" + sel[0].get('nama_blok')  + "-" + sel[0].get('nama_sub_blok') ;
                    var vkdlokasi=sel[0].get('kd_lokasi') + sel[0].get('kd_blok')  + sel[0].get('kd_sub_blok') ;
                    Ext.getCmp('id_lokasi_approval').setValue(vlokasi);	
                    Ext.getCmp('id_kdlokasi_approval').setValue(vkdlokasi);	
                    Ext.getCmp('id_cbnoopname_approval').setValue(sel[0].get('no_opname'));
                    
                    var vno_opname = sel[0].get('no_opname'); 
                    strstokopname_approval.load({
                                params: {
                                    no_opname: vno_opname
                                },
								callback: function(r, options, response) {
									if(r.length == 0){
										Ext.getCmp('so_kd_produk_approval').setValue("");
										Ext.getCmp('so_nama_produk_approval').setValue("");
										Ext.getCmp('so_nm_satuan_approval').setValue("");
										Ext.getCmp('so_qty_approval').setValue("");
										Ext.getCmp('so_qty_adjust_approval').setValue("");
										Ext.getCmp('so_penyesuaian_approval').setValue("");
									}else{
										Ext.getCmp('so_kd_produk_approval').setValue(r[0].data.kd_produk);
										Ext.getCmp('so_nama_produk_approval').setValue(r[0].data.nama_produk);
										Ext.getCmp('so_nm_satuan_approval').setValue(r[0].data.nm_satuan);
										Ext.getCmp('so_qty_approval').setValue(r[0].data.qty_oh);
										Ext.getCmp('so_qty_adjust_approval').setValue(r[0].data.qty_adjust);
										Ext.getCmp('so_penyesuaian_approval').setValue(r[0].data.penyesuaian);
									}							    										
							    }
                            });     
                    menu_opname_approval.hide();
                }
            }
        }
    });
	
    var menu_opname_approval = new Ext.menu.Menu();
    menu_opname_approval.add(new Ext.Panel({
        title: 'Pilih No Opname',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [grid_opname_approval],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menu_opname_approval.hide();
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
            strgrid_opname_approval.load();
            menu_opname_approval.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menu_opname_approval.on('hide', function(){
        var sf = Ext.getCmp('id_searchgrid_opname_approval').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgrid_opname_approval').setValue('');
            searchgrid_sj_ekspedisi.onTrigger2Click();
        }
    });
	
    var cbnoopname_approval = new Ext.ux.TwinComboOpnameEntry({
        fieldLabel: 'No. Opname <span class="asterix">*</span>',
        id: 'id_cbnoopname_approval',
        store: strcb_opname_approval,
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
    var headerstokopname_approval = {
        layout: 'column',
        border: false,
        items: [{
            columnWidth: .5,
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: { labelSeparator: ''},
            items: [
//                cblokasiopname_approval, cbblokopname_approval, cbsubblokopname_approval,
                cbnoopname_approval,
                 {
                xtype: 'textarea',
                fieldLabel: 'Lokasi',
                name: 'lokasi',
                readOnly:true,
			//	fieldClass:'readonly-input',
                id: 'id_lokasi_approval',                
                anchor: '90%',
                value:''
            }
            ,
                 {
                xtype: 'hidden',
//                fieldLabel: 'Lokasi',
                name: 'kdlokasi',
                readOnly:true,
			//	fieldClass:'readonly-input',
                id: 'id_kdlokasi_approval',                
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
                id: 'id_tanggal_opname_approval',                
                anchor: '90%',
                value: ''
			},{ xtype: 'textarea',
				fieldLabel: 'Keterangan',
				name: 'keterangan',                                    
				id: 'so_keterangan_approval',                                      
				anchor: '90%' 
                                ,readOnly:true
			}]
        }]
    }
    
    var editorstokopname_approval = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });

    
    var gridstokopname_approval = new Ext.grid.GridPanel({
        store: strstokopname_approval,
        stripeRows: true,
        height: 280,
        frame: true,
        border:true,
//        plugins: [editorstokopname_approval],
        columns: [{
            header: 'Kode Barang',
            dataIndex: 'kd_produk',
            width: 110,
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'so_kd_produk_approval'
            })
        },{
            header: 'Nama Barang',
            dataIndex: 'nama_produk',
            width: 400,
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'so_nama_produk_approval'
            })
        },{
            header: 'Satuan',
            dataIndex: 'nm_satuan',
            width: 80,
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'so_nm_satuan_approval'
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
                id: 'so_qty_approval'
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
                id: 'so_qty_adjust_approval',
                allowBlank: false,
				selectOnFocus: true,readOnly: true
//				listeners:{
//					'change':function(){
//						var qty_oh = Ext.getCmp('so_qty_approval').getValue();
//						var qty_adjust = this.getValue();
//						var penyesuaian = qty_oh-qty_adjust;
//						Ext.getCmp('so_penyesuaian_approval').setValue(penyesuaian);
//					}
//				}
				
            }			
        },{
            xtype: 'numbercolumn',
            header: 'Penyesuaian',
            dataIndex: 'qty_penyesuaian',           
            width: 90,
			align: 'center',
            sortable: true,
            format: '0,0',
            editor: {
				xtype: 'numberfield',
				readOnly: true,
                id: 'so_penyesuaian_approval'
            }
        }]
    });
	
    // combobox akun penyeesuaian
    var strcbakunopname_approval = new Ext.data.Store({
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
//    var cbakunopname_approval = new Ext.form.ComboBox({
//        fieldLabel: 'Akun Beban Penyusutan <span class="asterix">*</span>',
//        id: 'id_cbpenyesuaian_opname_approval',
//        mode: 'local',
//        store: strcbakunopname_approval,
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
	
	
    var stokopname_approval = new Ext.FormPanel({
        id: 'id-stokopname_approval',
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
                    items: [headerstokopname_approval]
                },
                gridstokopname_approval
                
//    {
//                    layout: 'column',
//                    border: false,
//                    items: [{
//							columnWidth: 1,
//							style:'margin:6px 3px 0 0;',
//							layout: 'form', 
//							labelWidth: 125,
//							buttonAlign: 'left',           
//							items: [cbakunopname_approval]
//						}]
//                }
        ],
        buttons: [{
            text: 'Save',
			formBind: true,
            handler: function(){
                
                var detailstokopname = new Array();              
                strstokopname_approval.each(function(node){
                    detailstokopname.push(node.data)
                });
                Ext.getCmp('id-stokopname_approval').getForm().submit({
                    url: '<?= site_url("stok_opname/update_approvalrow") ?>',
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
                        
                        clearstokopname_approval();                       
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
                clearstokopname_approval();
            }
        }]
    });
    
	var stokopnamepanel_approval = new Ext.Panel({
	 	id: 'stokopname_approval',
		border: false,
        frame: false,
		autoScroll:true,	
        items: [stokopname_approval]
	});
	
    stokopname_approval.on('afterrender', function(){
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
    
    function clearstokopname_approval(){
        Ext.getCmp('id-stokopname_approval').getForm().reset();
//        Ext.getCmp('id-stokopname_approval').getForm().load({
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
        strstokopname_approval.removeAll();
        strgrid_opname_approval.reload();
    }
</script>