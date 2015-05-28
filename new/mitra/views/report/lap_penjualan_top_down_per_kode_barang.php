<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">


        var strcbljualtopsuplier = new Ext.data.ArrayStore({
        fields: ['kd_supplier'],
        data : []
        });

        var strgridljualtopsuplier = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
        fields: ['kd_supplier', 'nama_supplier'],
        root: 'data',
        totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("laporan_rekap_po/search_supplier") ?>',
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

        var searchgridljualtopsuplier = new Ext.app.SearchField({
        store: strgridljualtopsuplier ,
        params: {
        start: STARTPAGE,
        limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridljualtopsuplier'
    });
        var gridljualtopsuplier = new Ext.grid.GridPanel({
        store: strgridljualtopsuplier ,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
            header: 'Kode Supplier',
            dataIndex: 'kd_supplier',
            width: 100,
            sortable: true

        },{
            header: 'Nama Supplier',
            dataIndex: 'nama_supplier',
            width: 300,
            sortable: true
        }],
		tbar: new Ext.Toolbar({
	        items: [searchgridljualtopsuplier]
	    }),
            bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridljualtopsuplier,
            displayInfo: true
        }),
		listeners: {
			'rowdblclick': function(){
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {
                    Ext.getCmp('id_cbljualtopsuplier').setValue(sel[0].get('kd_supplier'));
                    menuljualtopsuplier.hide();
				}
			}
		}
    });

        var menuljualtopsuplier = new Ext.menu.Menu();
        menuljualtopsuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridljualtopsuplier],
        buttons: [{
            text: 'Close',
            handler: function(){
                menuljualtopsuplier.hide();
            }
        }]
    }));

    Ext.ux.TwinComboSuplierTop = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            strgridljualtopsuplier .load();
            menuljualtopsuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

	menuljualtopsuplier.on('hide', function(){
		var sf = Ext.getCmp('id_searchgridljualtopsuplier').getValue();
		if( sf != ''){
			Ext.getCmp('id_searchgridljualtopsuplier').setValue('');
			searchgridljualtopsuplier.onTrigger2Click();
		}
	});

        var cbljualtopsuplier = new Ext.ux.TwinComboSuplierTop({
        fieldLabel: 'Supplier',
        id: 'id_cbljualtopsuplier',
        store: strcbljualtopsuplier,
	mode: 'local',
        valueField: 'kd_supplier',
        displayField: 'kd_supplier',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
	anchor: '90%',
        hiddenName: 'kd_supplier',
        emptyText: 'Pilih Supplier'
    });

// cb kategori1
  var str_cbljualtopkategori1 = new Ext.data.Store({
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
                var r = new (str_cbljualtopkategori1.recordType)({
                    'kd_kategori1': '',
                    'nama_kategori1': '-----'
                });
                str_cbljualtopkategori1.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

        var cbljualtopkategori1 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 1 ',
        id: 'id_cbljualtopkategori1',
        store: str_cbljualtopkategori1,
        valueField: 'kd_kategori1',
        displayField: 'nama_kategori1',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori1',
        emptyText: 'Pilih kategori 1',
        listeners: {
            'select': function(combo, records) {
                var kdhp_cbkategori1 = cbljualtopkategori1.getValue();
                cbljualtopkategori2.store.proxy.conn.url = '<?= site_url("kategori3/get_kategori2") ?>/' + kdhp_cbkategori1;
                cbljualtopkategori2.store.reload();
            }
        }
    });

    // combobox kategori2

        var str_cbljualtopkategori2 = new Ext.data.Store({
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
                var r = new (str_cbljualtopkategori2.recordType)({
                    'kd_kategori2': '',
                    'nama_kategori2': '-----'
                });
                str_cbljualtopkategori2.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

        var cbljualtopkategori2 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 2 ',
        id: 'id_cbljualtopkategori2',
        mode: 'local',
        store: str_cbljualtopkategori2,
        valueField: 'kd_kategori2',
        displayField: 'nama_kategori2',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori2',
        emptyText: 'Pilih kategori 2',
        listeners: {
            select: function(combo, records) {
                var kd_hp_cbkategori1 = cbljualtopkategori1.getValue();
                var kd_hp_cbkategori2 = this.getValue();
                cbljualtopkategori3.setValue();
                cbljualtopkategori3.store.proxy.conn.url = '<?= site_url("kategori4/get_kategori3") ?>/' + kd_hp_cbkategori1 +'/'+ kd_hp_cbkategori2;
                cbljualtopkategori3.store.reload();
            }
        }
    });

     // combobox kategori3

    var str_cbljualtopkategori3 = new Ext.data.Store({
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
                var r = new (str_cbljualtopkategori3.recordType)({
                    'kd_kategori3': '',
                    'nama_kategori3': '-----'
                });
                str_cbljualtopkategori3.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var cbljualtopkategori3 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 3',
        id: 'id_cbljualtopkategori3',
        mode: 'local',
        store: str_cbljualtopkategori3,
        valueField: 'kd_kategori3',
        displayField: 'nama_kategori3',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori3',
        emptyText: 'Pilih kategori 3',
        listeners: {
            select: function(combo, records) {
                var kd_hp_cbkategori1 = cbljualtopkategori1.getValue();
                var kd_hp_cbkategori2 = cbljualtopkategori2.getValue();
                var kd_hp_cbkategori3 = this.getValue();
                cbljualtopkategori4.setValue();
                cbljualtopkategori4.store.proxy.conn.url = '<?= site_url("master_barang/get_kategori4") ?>/' + kd_hp_cbkategori1 +'/'+ kd_hp_cbkategori2 +'/'+ kd_hp_cbkategori3;
                cbljualtopkategori4.store.reload();
            }
        }
    });

    // combobox kategori4

    var str_cbljualtopkategori4 = new Ext.data.Store({
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
                var r = new (str_cbljualtopkategori4.recordType)({
                    'kd_kategori4': '',
                    'nama_kategori4': '-----'
                });
                str_cbljualtopkategori4.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var cbljualtopkategori4 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 4',
        id: 'id_cbljualtopkategori4',
        mode: 'local',
        store: str_cbljualtopkategori4,
        valueField: 'kd_kategori4',
        displayField: 'nama_kategori4',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori4',
        emptyText: 'Pilih kategori 4'
    });

    // CHECKBOX Sort Order
		var lptopsortorder = new Ext.form.Checkbox({
			xtype: 'checkbox',
			fieldLabel: 'Sort Order',
			boxLabel:'QTY',
			name:'sort_order',
			id:'id_lptopsortorder',
			checked: true,
			inputValue: '1',
			autoLoad : true
		});

        var headerlapjualtoptanggal = {
        layout: 'column',
        border: false,
        items: [{
            columnWidth: .8,
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: { labelSeparator: ''},
            items: [{
				xtype: 'fieldset',
				autoHeight: true,
				items: [
					{
						layout: 'column',
						items:[
							{
                                                    columnWidth: .5,
					            layout: 'form',
					            border: false,
					            labelWidth: 100,
								defaults: { labelSeparator: ''},
								items:[	{
                                                                            xtype: 'datefield',
                                                                            fieldLabel: 'Dari Tgl ',
                                                                            name: 'dari_tgl',
                                                                            allowBlank:false,
                                                                            format:'d-m-Y',
                                                                            editable:false,
                                                                            id: 'id_dari_tgl_jualkat4',
                                                                            anchor: '90%',
                                                                            value: ''
                                                                        }, cbljualtopkategori1,
                                                                           cbljualtopkategori2,
                                                                           cbljualtopsuplier

								]
							},
							{
                                                    columnWidth: .5,
					            layout: 'form',
					            border: false,
					            labelWidth: 100,
								defaults: { labelSeparator: ''},
								items:[
									{
										xtype: 'datefield',
										fieldLabel: 'Sampai Tgl',
										name: 'sampai_tgl',
										allowBlank:false,
										editable:false,
										format:'d-m-Y',
										id: 'id_smp_tgl_jualkat4',
										anchor: '90%',
										value: ''
									},cbljualtopkategori3,
                                                                          cbljualtopkategori4,
                                                                          lptopsortorder
								]
							}

						]
					}
				]
			}]
        }
        ]
    }


    var headerlapjualtopdownperkdbarang = {
            buttonAlign: 'left',
            layout: 'form',
            border: false,
            labelWidth: 100,
			defaults: { labelSeparator: ''},
                items: [headerlapjualtoptanggal,

                ],
            buttons: [{
            text: 'Print',
			formBind:true,
            handler: function(){
                                //var kd_user= Ext.getCmp('id_cblp1user').getValue();
                                //var kd_shift= Ext.getCmp('id_cblp1shift').getValue();
                                //var kd_member= Ext.getCmp('id_cblp1member').getValue();
                                //var dari_tgl= Ext.getCmp('id_dari_tgl_lp1').getRawValue();
                                //var sampai_tgl= Ext.getCmp('id_smp_tgl_lp1').getRawValue();
				winlapjualtopdownperkdbarangprint.show();
                               // Ext.getDom('lapjualtopdownperkdbarangprint').src = '<?= site_url("laporan_penjualan_per_kategori1/print_form") ?>' + '/' + kd_user + '/' + kd_shift + '/' + kd_member + '/' + dari_tgl + '/' + sampai_tgl;
				Ext.getDom('lapjualtopdownperkdbarangprint').src = '<?= site_url("laporan_penjualan_per_kategori1/print_form") ?>';
			}
        },{
			text: 'Cancel',
			handler: function(){
				clearlapjualtopdownperkdbarang();
			}
		}]
    };
        var winlapjualtopdownperkdbarangprint = new Ext.Window({
        id: 'id_winlapjualtopdownperkdbarangprint',
	Title: 'Print Penjualan Top Down Per Kode Barang',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:390px;" id="lapjualtopdownperkdbarangprint" src=""></iframe>'
    });


       var lapjualtopdownperkdbarang = new Ext.FormPanel({
	 	id: 'rpt_jual_top_down_perkdbrg',
		border: false,
		frame: true,
		monitorValid: true,
		labelWidth: 130,
        items: [{
                    bodyStyle: {
                        margin: '0px 0px 15px 0px'
                    },
                    items: [headerlapjualtopdownperkdbarang]
                }
        ]
    });

	function clearlapjualtopdownperkdbarang(){
		Ext.getCmp('rpt_jual_top_down_perkdbrg').getForm().reset();
	}
</script>