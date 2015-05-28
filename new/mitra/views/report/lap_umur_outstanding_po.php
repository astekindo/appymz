<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
        var strcblumuroutpo = new Ext.data.ArrayStore({
        fields: ['kd_supplier'],
        data : []
    });
       var strgridlumutoutpo = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
        fields: ['kd_supplier', 'nama_supplier'],
        root: 'data',
        totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("laporan_umur_outstanding_po/search_supplier") ?>',
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
        var searchgridlumuroutpo = new Ext.app.SearchField({
        store: strgridlumutoutpo,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridlumuroutpo'
    });
        var gridlumuroutpo = new Ext.grid.GridPanel({
        store: strgridlumutoutpo,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
            header: 'Kode Supplier',
            dataIndex: 'kd_supplier',
            width: 80,
            sortable: true,

        },{
            header: 'Nama Supplier',
            dataIndex: 'nama_supplier',
            width: 300,
            sortable: true,
        }],
		tbar: new Ext.Toolbar({
	        items: [searchgridlumuroutpo]
	    }),
            bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridlumutoutpo,
            displayInfo: true
        }),
		listeners: {
		'rowdblclick': function(){
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
           // Ext.getCmp('loutpo_kd_supplier').setValue(sel[0].get('kd_supplier'));
            Ext.getCmp('id_umuroutstandingpo').setValue(sel[0].get('kd_supplier'));
            //Ext.getCmp('id_cblpbsuplier').setValue(sel[0].get('nama_supplier'));
            // strlaporanpenerimaanbarang.removeAll();
               menulumuroutpo.hide();
                        }
                }
		}
    });
        var menulumuroutpo = new Ext.menu.Menu();
        menulumuroutpo.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridlumuroutpo],
        buttons: [{
        text: 'Close',
        handler: function(){
            menulumuroutpo.hide();
            }
        }]
    }));

    Ext.ux.TwinComboSuplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
			//load store grid
            strgridlumutoutpo.load();
            menulumuroutpo.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

	menulumuroutpo.on('hide', function(){
		var sf = Ext.getCmp('id_searchgridlumuroutpo').getValue();
		if( sf != ''){
			Ext.getCmp('id_searchgridlumuroutpo').setValue('');
			searchgridlumuroutpo.onTrigger2Click();
		}
	});
        var cblumuroutpo = new Ext.ux.TwinComboSuplier({
        fieldLabel: 'Kode Supplier',
        id: 'id_umuroutstandingpo',
        store: strcblumuroutpo,
	mode: 'local',
        valueField: 'kd_supplier',
        displayField: 'kd_supplier',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
	anchor: '42%',
        hiddenName: 'kd_supplier',
        emptyText: 'Pilih Supplier'
    });
        var strcblumuroutpoproduk = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data : []
    });
        var strgridlumuroutpoproduk = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
        fields: ['kd_produk','nama_produk','min_stok','max_stok','jml_stok','nm_satuan'],
        root: 'data',
        totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("laporan_umur_outstanding_po/search_produk_by_supplier") ?>',
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
        var searchgridlumuroutpoproduk = new Ext.app.SearchField({
        store: strgridlumuroutpoproduk,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridlumuroutpoproduk'
    });

	searchgridlumuroutpoproduk.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
                this.el.dom.value = '';

                // Get the value of search field
                var fid = Ext.getCmp('loutpo_kd_supplier').getValue();
                var o = { start: 0, kd_supplier: fid };

                this.store.baseParams = this.store.baseParams || {};
                this.store.baseParams[this.paramName] = '';
                this.store.reload({
                                        params : o
                                });
                this.triggers[0].hide();
                this.hasSearch = false;
        }
	};

	searchgridlumuroutpoproduk.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
              this.onTrigger1Click();
              return;
	  }

	  // Get the value of search field
	  var fid = Ext.getCmp('loutpo_kd_supplier').getValue();
	  var o = { start: 0, kd_supplier: fid };

	  this.store.baseParams = this.store.baseParams || {};
	  this.store.baseParams[this.paramName] = text;
	  this.store.reload({params:o});
	  this.hasSearch = true;
	  this.triggers[0].show();
	};
        var gridlumuroutpoproduk = new Ext.grid.GridPanel({
        store: strgridlumuroutpoproduk,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
        header: 'Kode produk',
        dataIndex: 'kd_produk',
        width: 100,
        sortable: true
        },{
            header: 'Nama produk',
            dataIndex: 'nama_produk',
            width: 400,
            sortable: true
        },{
            header: 'Satuan',
            dataIndex: 'nm_satuan',
            width: 80
        },{
            header: 'Min.Stok',
            dataIndex: 'min_stok',
            width: 80,
            sortable: true
        },{
            header: 'Max.Stok',
            dataIndex: 'max_stok',
            width: 80,
            sortable: true
        },{
            header: 'Jml.Stok',
            dataIndex: 'jml_stok',
            width: 80,
            sortable: true
        }],
		tbar: new Ext.Toolbar({
	        items: [searchgridlumuroutpoproduk]
	    }),

		listeners: {
			'rowdblclick': function(){
                        var sm = this.getSelectionModel();
                        var sel = sm.getSelections();
                        if (sel.length > 0) {
                        strgridlumuroutpoproduk.load({
                        params:{
                                kd_supplier: Ext.getCmp('loutpo_kd_supplier').getValue(),
                                kd_produk: sel[0].get('kd_produk'),
                                action: 'validate'
                        },
                                scope: this,
                                callback: function(records, operation, success) {
                                if (!success) {
                                        Ext.getCmp('elumuroutpo_kd_produk').setValue('');
                                }else{
                                        Ext.getCmp('elumuroutpo_kd_produk').setValue(sel[0].get('kd_produk'));
                                }
                                }
                        });
					menulumuroutpoproduk.hide();
				}
			}
		}
    });

    var menulumuroutpoproduk = new Ext.menu.Menu();
    menulumuroutpoproduk.add(new Ext.Panel({
    title: 'Pilih Barang',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 600,
    height: 250,
    closeAction: 'hide',
    plain: true,
    items: [gridlumuroutpoproduk],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menulumuroutpoproduk.hide();
                }
        }]
    }));

    Ext.ux.TwinCombolumuroutpoProduk = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            strgridlumuroutpoproduk.load({
                        params: {
                	kd_supplier: Ext.getCmp('loutpo_kd_supplier').getValue()
                }
			});
            menulumuroutpoproduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

	menulumuroutpoproduk.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridlumuroutpoproduk').getValue();
        if( sf != ''){
                Ext.getCmp('id_searchgridlumuroutpoproduk').setValue('');
                searchgridlumuroutpoproduk.onTrigger2Click();
        }
});

            var headerlapumuroutstandingpo = {
            buttonAlign: 'left',
            layout: 'form',
            border: false,
            labelWidth: 100,
			defaults: { labelSeparator: ''},
            items: [{
                        xtype: 'hidden',
                        name: 'kd_supplier',
                        id: 'loutpo_kd_supplier',
                        value: ''
			},cblumuroutpo
			,new Ext.ux.TwinCombolumuroutpoProduk({
                        id: 'elumuroutpo_kd_produk',
                        fieldLabel: 'Kode Produk',
		        store: strcblumuroutpoproduk,
                        mode: 'local',
                        anchor: '42%',
		        valueField: 'kd_produk',
		        displayField: 'kd_produk',
		        typeAhead: true,
		        triggerAction: 'all',
		        allowBlank: true ,
		        editable: false,
		        hiddenName: 'kd_produk',
		        emptyText: 'Pilih Produk'
		    })],
            buttons: [{
            text: 'Print',
			formBind:true,
                        handler: function(){
				winlaporanumuroutstandingpoprint.show();
				Ext.getDom('laporanumuroutstandingpoprint').src = '<?= site_url("laporan_umur_outstanding_po/print_form") ?>';
			}
        },{
			text: 'Cancel',
			handler: function(){
				clearlaporanumuroutstandingpo();
			}
		}]
    }

        var winlaporanumuroutstandingpoprint = new Ext.Window({
        id: 'id_winlaporanumuroutstandingpoprint',
	title: 'Print Laporan Umur Outstanding PO',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:390px;" id="laporanumuroutstandingpoprint" src=""></iframe>'
    });

        var lapumuroutstandingpo = new Ext.FormPanel({
        id: 'rpt_umur_outstanding_po',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
                    bodyStyle: {
                        margin: '0px 0px 15px 0px'
                    },
                    items: [headerlapumuroutstandingpo]
                },
        ]
    });

	function clearlaporanumuroutstandingpo(){
		Ext.getCmp('rpt_umur_outstanding_po').getForm().reset();

	}
</script>