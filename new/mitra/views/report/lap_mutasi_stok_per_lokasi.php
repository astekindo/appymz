<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">


    var strcblmsplsuplier = new Ext.data.ArrayStore({
        fields: ['kd_supplier'],
        data : []
        });

        var strgridlmsplsuplier = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
        fields: ['kd_supplier', 'nama_supplier'],
        root: 'data',
        totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("laporan_purchase_order/search_supplier") ?>',
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

    var searchgridlmsplsuplier = new Ext.app.SearchField({
        store: strgridlmsplsuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridlmsplsuplier '
    });

        var gridlmsplsuplier = new Ext.grid.GridPanel({
        store: strgridlmsplsuplier,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
            header: 'Kode Supplier',
            dataIndex: 'kd_supplier',
            width: 80,
            sortable: true

        },{
            header: 'Nama Supplier',
            dataIndex: 'nama_supplier',
            width: 300,
            sortable: true
        }],
		tbar: new Ext.Toolbar({
	        items: [searchgridlmsplsuplier]
	    }),
            bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridlmsplsuplier,
            displayInfo: true
        }),
		listeners: {
			'rowdblclick': function(){
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {
                    Ext.getCmp('id_cblmsplsuplier').setValue(sel[0].get('kd_supplier'));
                    menulmsplsuplier.hide();
				}
			}
		}
    });

        var menulmsplsuplier = new Ext.menu.Menu();
        menulmsplsuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridlmsplsuplier],
        buttons: [{
            text: 'Close',
            handler: function(){
                menulmsplsuplier.hide();
            }
        }]
    }));

    Ext.ux.TwinCombolmsplSuplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            strgridlmsplsuplier.load();
            menulmsplsuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

        menulmsplsuplier.on('hide', function(){
            var sf = Ext.getCmp('id_searchgridlmsplsuplier').getValue();
            if( sf != ''){
                    Ext.getCmp('id_searchgridlmsplsuplier').setValue('');
                    searchgridlmsplsuplier.onTrigger2Click();
            }
	});

        var cblmsplsuplier = new Ext.ux.TwinCombolmsplSuplier({
        fieldLabel: 'Supplier',
        id: 'id_cblmsplsuplier',
        store: strcblmsplsuplier,
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

    var strcblmsplproduk = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data : []
        });

    var strgridlmsplproduk = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
        fields: ['kd_produk', 'nama_produk'],
        root: 'data',
        totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("laporan_purchase_order/search_produk") ?>',
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

        var searchgridlmsplproduk = new Ext.app.SearchField({
            store: strgridlmsplproduk,
            params: {
            start: STARTPAGE,
            limit: ENDPAGE
            },
            width: 350,
            id: 'id_searchgridlmsplproduk'
        });

        var gridlmsplproduk = new Ext.grid.GridPanel({
        store: strgridlmsplproduk,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
            header: 'Kode Produk',
            dataIndex: 'kd_produk',
            width: 80,
            sortable: true

        },{
            header: 'Nama Produk',
            dataIndex: 'nama_produk',
            width: 300,
            sortable: true
        }],
            tbar: new Ext.Toolbar({
            items: [searchgridlmsplproduk]
	    }),
            bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridlmsplproduk,
            displayInfo: true
        }),
		listeners: {
			'rowdblclick': function(){
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {
                    Ext.getCmp('id_cblmsplproduk').setValue(sel[0].get('kd_produk'));
                    menulmsplproduk.hide();
				}
			}
		}
    });
    Ext.ux.TwinCombolmsplProduk = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
           strgridlmsplproduk.load();
           menulmsplproduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

        var menulmsplproduk = new Ext.menu.Menu();
        menulmsplproduk.add(new Ext.Panel({
        title: 'Pilih Produk',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridlmsplproduk],
        buttons: [{
            text: 'Close',
            handler: function(){
                menulmsplproduk.hide();
            }
        }]
    }));



	menulmsplproduk.on('hide', function(){
		var sf = Ext.getCmp('id_searchgridlmsplproduk').getValue();
		if( sf != ''){
			Ext.getCmp('id_searchgridlmsplproduk').setValue('');
			searchgridlmsplproduk.onTrigger2Click();
		}
	});

    var cblmsplproduk = new Ext.ux.TwinCombolmsplProduk({
        id: 'id_cblmsplproduk',
        fieldLabel: 'Produk',
        store: strcblmsplproduk,
        mode: 'local',
        anchor: '90%',
        valueField: 'kd_produk',
        displayField: 'kd_produk',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: true ,
        editable: false,
        hiddenName: 'kd_produk',
        emptyText: 'Pilih Produk'
    });

       // combobox Ukuran

        var str_lmspl_cbukuran = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_ukuran', 'nama_ukuran'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_barang/get_ukuran") ?>',
            method: 'POST'
        }),
            listeners: {
            load: function() {
                var r = new (str_lmspl_cbukuran.recordType)({
                    'kd_ukuran': '',
                    'nama_ukuran': '-----'
                });
                str_lmspl_cbukuran.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
     var lmspl_cbukuran = new Ext.form.ComboBox({
        fieldLabel: 'Ukuran ',
        id: 'id_lmspl_cbukuran',
        store: str_lms_cbukuran,
        valueField: 'kd_ukuran',
        displayField: 'nama_ukuran',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_ukuran',
        emptyText: 'Pilih Ukuran'

    });

   // combobox Satuan
        var str_lmspl_cbsatuan = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_satuan', 'nm_satuan'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_barang/get_satuan") ?>',
            method: 'POST'
        }),
            listeners: {
            load: function() {
                var r = new (str_lmspl_cbsatuan.recordType)({
                    'kd_satuan': '',
                    'nm_satuan': '-----'
                });
                str_lmspl_cbsatuan.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

     var lmspl_cbsatuan = new Ext.form.ComboBox({
        fieldLabel: 'Satuan ',
        id: 'id_lmspl_cbsatuan',
        store: str_lmspl_cbsatuan,
        valueField: 'kd_satuan',
        displayField: 'nm_satuan',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_satuan',
        emptyText: 'Pilih Satuan'

    });
     // combobox Status
     /*
        var str_lms_cbsatuan = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_satuan', 'nm_satuan'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("master_barang/get_satuan_produk") ?>',
            method: 'POST'
        }),
            listeners: {
            load: function() {
                var r = new (str_lms_cbsatuan.recordType)({
                    'kd_satuan': '',
                    'nm_satuan': '-----'
                });
                str_lms_cbsatuan.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
*/
     var lmspl_cbstatus = new Ext.form.ComboBox({
        fieldLabel: 'Status ',
        id: 'id_lmspl_cbstatus',
      //  store: str_lms_cbsatuan,
      //   valueField: 'kd_satuan',
      //  displayField: 'nm_satuan',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
      //  hiddenName: 'kd_satuan',
        emptyText: 'Pilih Status'

    });


// cb kategori1

  var str_cblmsplkategori1 = new Ext.data.Store({
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
                var r = new (str_cblmsplkategori1.recordType)({
                    'kd_kategori1': '',
                    'nama_kategori1': '-----'
                });
                str_cblmsplkategori1.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

        var cblmsplkategori1 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 1',
        id: 'id_cblmsplkategori1',
        store: str_cblmsplkategori1,
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
                var kdhp_cbkategori1 = cblmsplkategori1.getValue();
                cblmsplkategori2.store.proxy.conn.url = '<?= site_url("kategori3/get_kategori2") ?>/' + kdhp_cbkategori1;
                cblmsplkategori2.store.reload();
            }
        }
    });

    // combobox kategori2

        var str_cblmsplkategori2 = new Ext.data.Store({
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
                var r = new (str_cblmsplkategori2.recordType)({
                    'kd_kategori2': '',
                    'nama_kategori2': '-----'
                });
                str_cblmsplkategori2.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

        var cblmsplkategori2 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 2',
        id: 'id_cblmsplkategori2',
        mode: 'local',
        store: str_cblmsplkategori2,
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
                var kd_hp_cbkategori1 = cblmsplkategori1.getValue();
                var kd_hp_cbkategori2 = this.getValue();
                cblmsplkategori3.setValue();
                cblmsplkategori3.store.proxy.conn.url = '<?= site_url("kategori4/get_kategori3") ?>/' + kd_hp_cbkategori1 +'/'+ kd_hp_cbkategori2;
                cblmsplkategori3.store.reload();
            }
        }
    });

     // combobox kategori3

    var str_cblmsplkategori3 = new Ext.data.Store({
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
                var r = new (str_cblmsplkategori3.recordType)({
                    'kd_kategori3': '',
                    'nama_kategori3': '-----'
                });
                str_cblmsplkategori3.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var cblmsplkategori3 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 3 ',
        id: 'id_cblmsplkategori3',
        mode: 'local',
        store: str_cblmsplkategori3,
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
                var kd_hp_cbkategori1 = cblmsplkategori1.getValue();
                var kd_hp_cbkategori2 = cblmsplkategori2.getValue();
                var kd_hp_cbkategori3 = this.getValue();
                cblmsplkategori4.setValue();
                cblmsplkategori4.store.proxy.conn.url = '<?= site_url("master_barang/get_kategori4") ?>/' + kd_hp_cbkategori1 +'/'+ kd_hp_cbkategori2 +'/'+ kd_hp_cbkategori3;
                cblmsplkategori4.store.reload();
            }
        }
    });

    // combobox kategori4

    var str_cblmsplkategori4 = new Ext.data.Store({
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
                var r = new (str_cblmsplkategori4.recordType)({
                    'kd_kategori4': '',
                    'nama_kategori4': '-----'
                });
                str_cblmsplkategori4.insert(0, r);
            },
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var cblmsplkategori4 = new Ext.form.ComboBox({
        fieldLabel: 'Kategori 4 ',
        id: 'id_cblmsplkategori4',
        mode: 'local',
        store: str_cblmsplkategori4,
        valueField: 'kd_kategori4',
        displayField: 'nama_kategori4',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
        width: 170,
        anchor: '90%',
        hiddenName: 'nama_kategori4',
        emptyText: 'Pilih kategori 4'
    });

        var strcblmsplgudang = new Ext.data.ArrayStore({
        fields: ['kd_lokasi'],
        data : []
        });

        var strgridlmsplgudang = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
        fields: ['kd_lokasi', 'nama_lokasi'],
        root: 'data',
        totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("laporan_mutasi_stok_lengkap/search_gudang") ?>',
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

        var searchgridlmsplgudang = new Ext.app.SearchField({
        store: strgridlmsplgudang,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridlmsplgudang'
    });

        var gridlmsplgudang = new Ext.grid.GridPanel({
        store: strgridlmsplgudang,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
            header: 'Kode Lokasi',
            dataIndex: 'kd_lokasi',
            width: 80,
            sortable: true

        },{
            header: 'Nama Lokasi',
            dataIndex: 'nama_lokasi',
            width: 300,
            sortable: true
        }],
            tbar: new Ext.Toolbar({
            items: [searchgridlmsplgudang]
	    }),
            bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridlmsplgudang,
            displayInfo: true
        }),
		listeners: {
			'rowdblclick': function(){
				var sm = this.getSelectionModel();
				var sel = sm.getSelections();
				if (sel.length > 0) {
                    Ext.getCmp('id_cblmsplgudang').setValue(sel[0].get('kd_lokasi'));
                    menulmsplgudang.hide();
				}
			}
		}
    });

        var menulmsplgudang = new Ext.menu.Menu();
        menulmsplgudang.add(new Ext.Panel({
        title: 'Pilih Gudang',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridlmsplgudang],
        buttons: [{
            text: 'Close',
            handler: function(){
                menulmsplgudang.hide();
            }
        }]
    }));

    Ext.ux.TwinCombolmsplGudang = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            strgridlmsplgudang.load();
            menulmsplgudang.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

	menulmsplgudang.on('hide', function(){
		var sf = Ext.getCmp('id_searchgridlmsplgudang').getValue();
		if( sf != ''){
			Ext.getCmp('id_searchgridlmsplgudang').setValue('');
			searchgridlmsplgudang.onTrigger2Click();
		}
	});

        var cblmsplgudang = new Ext.ux.TwinCombolmsplGudang({
        fieldLabel: 'Gudang',
        id: 'id_cblmsplgudang',
        store: strcblmsplgudang,
	mode: 'local',
        valueField: 'kd_lokasi',
        displayField: 'kd_lokasi',
        typeAhead: true,
        triggerAction: 'all',
        editable: false,
	anchor: '90%',
        hiddenName: 'kd_lokasi',
        emptyText: 'Pilih Gudang'
    });

        var headerlmspltanggal = {
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
                                                                            fieldLabel: 'Dari Tgl <span class="asterix">*</span>',
                                                                            name: 'dari_tgl',
                                                                            allowBlank:false,
                                                                            format:'d-m-Y',
                                                                            editable:false,
                                                                            id: 'id_dari_tgl_mspl',
                                                                            anchor: '90%',
                                                                            value: ''
                                                                        }
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
										id: 'id_smp_tgl_mspl',
										anchor: '90%',
										value: ''
									}
								]
							},

						]
					}
				]
			}]
        }
        ]
    }

    var headerlmsplkategori = {
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
								items:[
									cblmsplkategori1,
                                                                        cblmsplkategori2,
                                                                        cblmsplkategori3,
                                                                        cblmsplkategori4,
                                                                        lmspl_cbsatuan
                                                                ]},
                                         {


                                                    columnWidth: .5,
					            layout: 'form',
					            border: false,
					            labelWidth: 100,
								defaults: { labelSeparator: ''},
								items:[
                                                                        cblmsplproduk,
                                                                        cblmsplsuplier,
                                                                        lmspl_cbstatus,
                                                                        cblmsplgudang,
                                                                        lmspl_cbukuran
								]



                                            }
                                          ]
					}
                                    ]
                        }
                        ]
			}]

    }



    var headerlmspl = {
            buttonAlign: 'left',
            layout: 'form',
            border: false,
            labelWidth: 100,
			defaults: { labelSeparator: ''},
                items: [headerlmspltanggal,headerlmsplkategori

                ],
            buttons: [{
            text: 'Print',
			formBind:true,
            handler: function(){
				winlapmutasistokperlokasiprint.show();
				Ext.getDom('laporanmutasistokperlokasiprint').src = '<?= site_url("laporan_purchase_order/print_form") ?>';
			}
        },{
			text: 'Cancel',
			handler: function(){
				clearlapmutasistokperlokasi();
			}
		}]
    };
    var winlapmutasistokperlokasiprint = new Ext.Window({
        id: 'id_winlapmutasistokperlokasiprint',
	title: 'Print Laporan Mutasi Stok per Lokasi',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:390px;" id="laporanmutasistokperlokasiprint" src=""></iframe>'
    });

    var laporanmutasistokperlokasi = new Ext.FormPanel({
        id: 'rpt_mutasi_stok_per_lokasi',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
                    bodyStyle: {
                        margin: '0px 0px 15px 0px'
                    },
                    items: [headerlmspl]
                }
        ]
    });

	function clearlapmutasistokperlokasi(){
		Ext.getCmp('rpt_mutasi_stok_per_lokasi').getForm().reset();

	}
</script>