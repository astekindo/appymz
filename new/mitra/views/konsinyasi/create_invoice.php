<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">

    // cari supplier
    var strcbkcisuplier = new Ext.data.ArrayStore({
        fields: ['nama_supplier'],
        data: []
    });

    var strgridkcisuplier = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_supplier', 'nama_supplier', 'top', 'pkp'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("konsinyasi_create_invoice/search_supplier") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var searchgridkcisuplier = new Ext.app.SearchField({
        store: strgridkcisuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridkcisuplier'
    });


    var gridkcisuplier = new Ext.grid.GridPanel({
        store: strgridkcisuplier,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'Kode Supplier',
                dataIndex: 'kd_supplier',
                width: 80,
                sortable: true,
            }, {
                header: 'Nama Supplier',
                dataIndex: 'nama_supplier',
                width: 300,
                sortable: true,
            }, {
                dataIndex: 'top',
                hidden: true,
            }, {
                header: 'Status PKP',
                dataIndex: 'pkp',
                width: 100,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridkcisuplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridkcisuplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    var top = sel[0].get('top');
                    Ext.getCmp('kci_kd_supplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_cbkcisuplier').setValue(sel[0].get('nama_supplier'));
                    Ext.getCmp('kci_hari').setValue(top);
                    Ext.getCmp('kci_tgl_jth_tempo').setValue(new Date().add(Date.DAY, parseInt(top)));
                    Ext.getCmp('kci_status_pkp').setValue(sel[0].get('pkp'));
                    var pkp = sel[0].get('pkp');
                    if (pkp === '1') {
                        Ext.getCmp('kci_status_pkp').setValue('YA');
                        Ext.getCmp('kcin_ppn').setValue('10');
                        Ext.getCmp('kci_no_faktur_pajak').setDisabled(false);
                    } else {
                        Ext.getCmp('kci_status_pkp').setValue('TIDAK');
                        Ext.getCmp('kcin_ppn').setValue('0');
                        Ext.getCmp('kci_no_faktur_pajak').setDisabled(true);
                    }
                    strkonsinyasicreaterequest.removeAll();
                    menukcisuplier.hide();
                }
            }
        }
    });

    var menukcisuplier = new Ext.menu.Menu();
    menukcisuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridkcisuplier],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menukcisuplier.hide();
                }
            }]
    }));

    Ext.ux.TwinComboSuplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridkcisuplier.load();
            menukcisuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menukcisuplier.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridkcisuplier').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgridkcisuplier').setValue('');
            searchgridkcisuplier.onTrigger2Click();
        }
    });

    var cbkcisuplier = new Ext.ux.TwinComboSuplier({
        fieldLabel: 'Supplier <span class="asterix">*</span>',
        id: 'id_cbkcisuplier',
        store: strcbkcisuplier,
        mode: 'local',
        valueField: 'nama_supplier',
        displayField: 'nama_supplier',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'nama_supplier',
        emptyText: 'Pilih Supplier'
    });

    // checkbox grid
    var cbGridkci = new Ext.grid.CheckboxSelectionModel();

    var strcbkcinodo = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_po', 'tanggal_po'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("konsinyasi_create_invoice/search_no_do_by_supplier") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var searchkcinodo = new Ext.app.SearchField({
        store: strcbkcinodo,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'kcisearchlistbarang'
    });

    var tbkcinodo = new Ext.Toolbar({
        items: [searchkcinodo]
    });
     searchkcinodo.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';
			
            // Get the value of search field
            var fid = Ext.getCmp('kci_kd_supplier').getValue();
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
	
    searchkcinodo.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }
	 
        // Get the value of search field
        var fid = Ext.getCmp('kci_kd_supplier').getValue();
        var o = { start: 0, kd_supplier: fid };
	 
        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params:o});
        this.hasSearch = true;
        this.triggers[0].show();
    };
    
    var gridkcisearchnodo = new Ext.grid.GridPanel({
        store: strcbkcinodo,
        stripeRows: true,
        frame: true,
        sm: cbGridkci,
        border: true,
        columns: [cbGridkci, {
                header: 'No PI',
                dataIndex: 'no_po',
                width: 150,
                sortable: true,
            }, {
                header: 'Tanggal',
                dataIndex: 'tanggal_po',
                width: 150,
                sortable: true,
            }],
        tbar: [tbkcinodo],
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strcbkcinodo,
            displayInfo: true
        })
    });


    var menukcinodo = new Ext.menu.Menu();
    menukcinodo.add(new Ext.Panel({
        title: 'Pilih No PI',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 350,
        height: 350,
        closeAction: 'hide',
        plain: true,
        items: [gridkcisearchnodo],
        buttons: [{
                // icon: BASE_ICONS + 'add.png',
                text: 'Done',
                handler: function() {
                    if (Ext.getCmp('kci_kd_supplier').getValue() == '') {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Silahkan pilih supplier terlebih dulu',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                        });
                        return;
                    }

                    var sm = gridkcisearchnodo.getSelectionModel();
                    var sel = sm.getSelections();
                    if (sel.length > 0) {
                        var data = '';
                        for (i = 0; i < sel.length; i++) {
                            data = data + sel[i].get('no_po') + ';';
                        }

                        strkonsinyasicreateinvoice.load({
                            params: {
                                kd_supplier: Ext.getCmp('kci_kd_supplier').getValue(),
                                pkp: Ext.getCmp('kci_status_pkp').getValue(),
                                no_do: data
                            }
                        });

                        menukcinodo.hide();
                    } else {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Silahkan pilih No PI',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                        });
                        return;
                    }
                }
            }, {
                text: 'Close',
                handler: function() {
                    menukcinodo.hide();
                }
            }]
    }));


    var strkonsinyasicreateinvoice = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'no_po', allowBlank: false, type: 'text'},
                {name: 'kd_produk', allowBlank: false, type: 'text'},
                {name: 'qty_terima', allowBlank: false, type: 'text'},
                {name: 'pricelist', allowBlank: false, type: 'int'},
                {name: 'disk_persen_supp1_po', allowBlank: false, type: 'int'},
                {name: 'disk_persen_supp2_po', allowBlank: false, type: 'int'},
                {name: 'disk_persen_supp3_po', allowBlank: false, type: 'int'},
                {name: 'disk_persen_supp4_po', allowBlank: false, type: 'int'},
                {name: 'disk_amt_supp1_po', allowBlank: false, type: 'int'},
                {name: 'disk_amt_supp2_po', allowBlank: false, type: 'int'},
                {name: 'disk_amt_supp3_po', allowBlank: false, type: 'int'},
                {name: 'disk_amt_supp4_po', allowBlank: false, type: 'int'},
                {name: 'disk_amt_supp5_po', allowBlank: false, type: 'int'},
                {name: 'disk_supp1_po', allowBlank: false, type: 'int'},
                {name: 'disk_supp2_po', allowBlank: false, type: 'int'},
                {name: 'disk_supp3_po', allowBlank: false, type: 'int'},
                {name: 'disk_supp4_po', allowBlank: false, type: 'int'},			
                {name: 'disk_grid_supp1', allowBlank: false, type: 'text'},
                {name: 'disk_grid_supp2', allowBlank: false, type: 'text'},
                {name: 'disk_grid_supp3', allowBlank: false, type: 'text'},
                {name: 'disk_grid_supp4', allowBlank: false, type: 'text'},
                {name: 'disk_grid_supp5', allowBlank: false, type: 'text'},
                {name: 'rp_diskon', allowBlank: false, type: 'int'},
                {name: 'dpp_po', allowBlank: false, type: 'float'},
                {name: 'rp_total_po', allowBlank: false, type: 'float'},
                {name: 'harga_net_ect', allowBlank: false, type: 'float'},
                {name: 'harga_net', allowBlank: false, type: 'int'},
                {name: 'rp_total', allowBlank: false, type: 'int'},
                {name: 'rp_disk_po', allowBlank: false, type: 'int'},
                {name: 'nm_satuan', allowBlank: false, type: 'text'},
                {name: 'nama_supplier', allowBlank: false, type: 'text'},
                {name: 'adjust', allowBlank: false, type: 'int'},
                {name: 'kd_supplier', allowBlank: false, type: 'text'},
                {name: 'nama_produk', allowBlank: false, type: 'text'},
                {name: 'no_po', allowBlank: false, type: 'text'},
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("konsinyasi_create_invoice/search_no_do_by_supplier_no_do") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var headerkonsinyasicreateinvoice = {
        layout: 'column',
        border: false,
        items: [{
                columnWidth: .4,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [{
                        xtype: 'textfield',
                        fieldLabel: 'No Invoice',
                        name: 'no_invoice',
                        readOnly: true,
                        fieldClass: 'readonly-input',
                        id: 'kci_no_in',
                        anchor: '90%',
                        value: ''
                    }, {
                        xtype: 'hidden',
                        name: 'kd_supplier',
                        id: 'kci_kd_supplier',
                        value: ''
                    }, cbkcisuplier, {
                        xtype: 'textfield',
                        fieldLabel: 'Bukti Supplier',
                        name: 'no_bukti_supplier',
                        id: 'kci_no_bukti_supplier',
                        anchor: '90%'
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'No Faktur Pajak',
                        name: 'no_faktur_pajak',
                        id: 'kci_no_faktur_pajak',
                        anchor: '90%',
                        minLength: 16,
                        maxLength: 19,
                        listeners: {
                            'blur': function() {
                                var no_faktur = this.getValue();
                                if (no_faktur.length == 16) {

                                    no_faktur = no_faktur.replace("-", "");
                                    no_faktur = no_faktur.replace(".", "");

                                    console.log(no_faktur);
                                    Ext.getCmp('kci_no_faktur_pajak').setValue(no_faktur.substring(0, 3) + '.' +
                                            no_faktur.substring(3, 6) + '-' + no_faktur.substring(6, 8) + '.' + no_faktur.substring(8, 16));
                                }
                            }
                        }

                    }]
            }, {
                columnWidth: .3,
                layout: 'form',
                border: false,
                labelWidth: 120,
                defaults: {labelSeparator: ''},
                items: [{
                        xtype: 'hidden',
                        name: 'rp_diskon',
                        id: 'kcin_rp_diskon'
                    }, {
                        xtype: 'hidden',
                        name: 'rp_total',
                        id: 'kcin_rp_total'
                    }, {
                        xtype: 'datefield',
                        fieldLabel: 'Tgl. Terima Invoice',
                        name: 'tgl_terima_invoice',
                        id: 'kci_tgl_terima_invoice',
                        format: 'Y-m-d',
                        emptyText: 'Tgl Terima Invoice',
                        value: new Date(),
                        editable: false,
                        anchor: '90%'
                    }, {
                        xtype: 'datefield',
                        fieldLabel: 'Jatuh Tempo',
                        name: 'tgl_jth_tempo',
                        id: 'kci_tgl_jth_tempo',
                        readOnly: true,
                        format: 'Y-m-d',
                        fieldClass: 'readonly-input',
                        anchor: '90%'
                    }, {
                        xtype: 'datefield',
                        fieldLabel: 'Tgl Faktur Pajak',
                        name: 'tgl_faktur_pajak',
                        id: 'kci_tgl_faktur_pajak',
                        format: 'Y-m-d',
                        emptyText: 'Tgl Faktur Pajak',
                        value: new Date(),
                        editable: false,
                        anchor: '90%'
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'Status PKP',
                        name: 'status_pkp',
                        fieldClass: 'readonly-input',
                        readOnly: true,
                        id: 'kci_status_pkp',
                        anchor: '90%',
                        value: ''
                    }]
            }, {
                columnWidth: .3,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [{
                        xtype: 'datefield',
                        fieldLabel: 'Tgl. Invoice',
                        name: 'tgl_invoice',
                        id: 'kci_tgl_invoice',
                        format: 'Y-m-d',
                        emptyText: 'Tgl Invoice',
                        editable: false,
                        value: new Date(),
                        anchor: '90%',
                        listeners: {
                            'change': function() {
                                var top = Ext.getCmp('kci_hari').getValue();
                                var tgl_inv = this.getValue();
                                Ext.getCmp('kci_tgl_jth_tempo').setValue(new Date(tgl_inv).add(Date.DAY, parseInt(top)));
                            }
                        }
                    }, {
                        xtype: 'compositefield',
                        fieldLabel: 'Top',
                        combineErrors: false,
                        items: [{
                                name: 'top',
                                xtype: 'numberfield',
                                id: 'kci_hari',
                                fieldClass: 'number',
                                selectOnFocus: true,
                                width: 60,
                                value: '0',
                                listeners: {
                                    'change': function() {
                                        var top = this.getValue();
                                        var tgl_inv = Ext.getCmp('kci_tgl_invoice').getValue();
                                        Ext.getCmp('kci_tgl_jth_tempo').setValue(new Date(tgl_inv).add(Date.DAY, parseInt(top)));
                                    }
                                }

                            }, {
                                xtype: 'displayfield',
                                value: 'Hari'
                            }]
                    }, {
                        fieldLabel: 'Peruntukan <span class="asterix">*</span>',
                        xtype: 'radiogroup',
                        name: 'kd_peruntukan',
                        columnWidth: [.5, .5],
                        allowBlank: false,
                        items: [{
                                boxLabel: 'Supermarket',
                                name: 'kd_peruntukan',
                                inputValue: '0',
                                id: 'kcin_peruntukan_supermarket',
                                checked: true
                            }, {
                                boxLabel: 'Distribusi',
                                name: 'kd_peruntukan',
                                inputValue: '1',
                                id: 'kcin_peruntukan_distribusi'
                            }]
                    }]
            }]
    }


    strkonsinyasicreateinvoice.on('load', function() {
       var jumlah = 0;
        var jumlah_grid = 0;
        var dpp = 0;
        var ppn = 0;
        var grand_total = 0;

        strkonsinyasicreateinvoice.each(function(node) {
            jumlah += (node.data.rp_total_po);
            dpp += (node.data.dpp_po);
        });
        Ext.getCmp('kcin_rp_jumlah').setValue(jumlah);

        dpp = Math.round(dpp);
        var ppn = (parseInt(dpp)) * Ext.getCmp('kcin_ppn').getValue()/ 100;
        ppn = Math.round(ppn);
        var grand_total = parseInt(dpp) + parseInt(ppn);
        
        jumlah = Math.round(jumlah);
       
        grand_total = Math.round(grand_total);

        Ext.getCmp('kcin_rp_jumlah').setValue(jumlah);
        Ext.getCmp('kcin_dpp').setValue(dpp);
        Ext.getCmp('kcin_rp_diskon').setValue(0);
        Ext.getCmp('kcin_rp_ppn').setValue(ppn);
        Ext.getCmp('kcin_total_invoice').setValue(grand_total);
        Ext.getCmp('kcin_rp_total_grand').setValue(grand_total);
    });

    strkonsinyasicreateinvoice.on('update', function() {
         var jumlah = 0;
        var dpp = 0;
        var ppn = 0;
        var grand_total = 0;
        var extra_diskon = 0;
        var pembulatan = 0;

        strkonsinyasicreateinvoice.each(function(node) {
            jumlah += (node.data.rp_total_po);
        });
        
        jumlah = Math.round(jumlah);
        extra_diskon = Ext.getCmp('kcin_rp_diskon').getValue();
        dpp = jumlah - parseInt(extra_diskon);
        dpp = Math.round(dpp);
	var rp_ppn = (parseInt(dpp)) * Ext.getCmp('kcin_ppn').getValue() / 100;
        rp_ppn = Math.round(rp_ppn);
        var grand_total =  parseInt(dpp)  + parseInt(rp_ppn);
       
        pembulatan = Ext.getCmp('kcin_pembulatan').getValue();
	grand_total = Math.round(grand_total);
        
	Ext.getCmp('kcin_rp_jumlah').setValue(jumlah);
        Ext.getCmp('kcin_dpp').setValue(dpp);	
        Ext.getCmp('kcin_rp_ppn').setValue(rp_ppn);
        Ext.getCmp('kcin_total_invoice').setValue(grand_total);
        Ext.getCmp('kcin_rp_total_grand').setValue(grand_total+pembulatan);
    });

    var editorkonsinyasicreateinvoice = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });

    var gridkonsinyasicreateinvoice = new Ext.grid.GridPanel({
        store: strkonsinyasicreateinvoice,
        stripeRows: true,
        height: 250,
        frame: true,
        border: true,
        plugins: [editorkonsinyasicreateinvoice],
        tbar: [{
                icon: BASE_ICONS + 'add.png',
                text: 'Add No PI',
                handler: function() {
                    if (Ext.getCmp('kci_kd_supplier').getValue() == '') {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Silahkan pilih supplier terlebih dulu',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                        });
                        return;
                    }
//                    var supermarket = Ext.getCmp('kcin_peruntukan_supermarket').getValue();
//                    var distribusi = Ext.getCmp('kcin_peruntukan_distribusi').getValue();
//
//                    if (supermarket){
//                        kd_peruntukkan = '0';
//                    }else if (distribusi) {
//                        kd_peruntukkan = '1';									
//                    }
                    strcbkcinodo.load({
                        params: {
                            kd_supplier: Ext.getCmp('kci_kd_supplier').getValue(),
                           // kd_peruntukkan: kd_peruntukkan
                        }
                    });
                    menukcinodo.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
                }
            }, {
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                disabled: true,
                handler: function() {
                    editorkonsinyasicreateinvoice.stopEditing();
                    var s = gridkonsinyasicreateinvoice.getSelectionModel().getSelections();
                    for (var i = 0, r; r = s[i]; i++) {
                        strkonsinyasicreateinvoice.remove(r);
                    }
                    var jumlah = 0;
                    var dpp = 0;
                    var ppn = 0;
                    var grand_total = 0;
                    var extra_diskon = 0;
                    var pembulatan = 0;
				
                    strkonsinyasicreateinvoice.each(function(node){			
                        jumlah += (node.data.rp_total_po);
                    });
                    jumlah = Math.round(jumlah);
                    extra_diskon = Ext.getCmp('kcin_rp_diskon').getValue();
                    pembulatan = Ext.getCmp('kcin_pembulatan').getValue();
				
                    dpp = jumlah - parseInt(extra_diskon);
		    dpp = Math.round(dpp);	
                    Ext.getCmp('kcin_rp_jumlah').setValue(jumlah);
                    Ext.getCmp('kcin_dpp').setValue(dpp);
				
                    var rp_ppn = (parseInt(dpp)) * Ext.getCmp('kcin_ppn').getValue() / 100;
                    rp_ppn = Math.round(rp_ppn);
                    var grand_total =  parseInt(dpp)  + parseInt(rp_ppn);
                    grand_total = Math.round(grand_total);
                    Ext.getCmp('kcin_rp_ppn').setValue(rp_ppn);
                    Ext.getCmp('kcin_total_invoice').setValue(grand_total);

                    Ext.getCmp('kcin_rp_total_grand').setValue(grand_total+pembulatan);
                }
            }],
         columns: [
         
//            {
//                header: 'RO No',
//                dataIndex: 'no_do',
//                width: 150,
//                editor: new Ext.form.TextField({						
//                    readOnly: true,
//                    id: 'kcin_no_do'
//                })					
//            },
//                
            {
                header: 'No PI',
                dataIndex: 'no_po',
                width: 150,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'kcin_pono'
                })
            },{
                header: 'Kode Barang',
                dataIndex: 'kd_produk',
                width: 200,
                sortable: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'kcin_kd_produk'
                })
            
            },{
                header: 'Nama Barang',
                dataIndex: 'nama_produk',
                width: 300,
                editor: new Ext.form.TextField({                
                    readOnly: true,
                    id: 'kcin_nama_produk'
                })
            },{
                header: 'Qty',
                dataIndex: 'qty_terima',
                width: 70,
                editor: new Ext.form.TextField({                
                    readOnly: true,
                    id: 'kcin_qty_terima'
                })
            },{
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 70,
                editor: new Ext.form.TextField({                
                    readOnly: true,
                    id: 'kcin_nm_satuan'
                })
            },{
                xtype: 'numbercolumn',
                header: 'Harga Beli',
                dataIndex: 'pricelist',
                width: 70,
                align: 'right',
                format: '0,0',
                editor: new Ext.form.TextField({                
                    xtype: 'numberfield',
                    readOnly: true,
                    id: 'kcin_plist'
                })
            },{
                // xtype: 'numbercolumn',
                header: 'Disk 1',
                dataIndex: 'disk_grid_supp1',           
                width: 60,
                sortable: true,
                align: 'right',
                // format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'kcin_disk_grid_supp1',
                    allowBlank: true,
                    readOnly: true
                }
            },{
                // xtype: 'numbercolumn',
                header: 'Disk 2',
                dataIndex: 'disk_grid_supp2',           
                width: 60,
                sortable: true,
                align: 'right',
                // format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'kcin_disk_grid_supp2',
                    allowBlank: true,
                    readOnly: true
                }
            },{
                // xtype: 'numbercolumn',
                header: 'Disk 3',
                dataIndex: 'disk_grid_supp3',           
                width: 60,
                sortable: true,
                align: 'right',
                // format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'kcin_disk_grid_supp3',
                    readOnly: true,
                    allowBlank: true
                }
            },{
                // xtype: 'numbercolumn',
                header: 'Disk 4',
                dataIndex: 'disk_grid_supp4',           
                width: 60,
                sortable: true,
                align: 'right',
                // format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'kcin_disk_grid_supp4',
                    readOnly: true,
                    allowBlank: true
                }
            },{
                // xtype: 'numbercolumn',
                header: 'Disk 5',
                dataIndex: 'disk_grid_supp5',           
                width: 60,
                sortable: true,
                align: 'right',
                // format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'kcin_disk_grid_supp5',
                    readOnly: true,
                    allowBlank: true
                }
            },{
                xtype: 'numbercolumn',
                header: 'Total Diskon',
                dataIndex: 'rp_disk_po',           
                width: 100,
                sortable: true,
                align: 'right',
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'kcin_rp_disk_po',
                    readOnly: true,
                    allowBlank: true
                }
            },{
                xtype: 'numbercolumn',
                header: 'Harga NET',
                dataIndex: 'harga_net',           
                width: 100,
                sortable: true,
                align: 'right',
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'kcin_harga_net_po',
                    readOnly: true,
                    allowBlank: true
                }
            },
                {
                xtype: 'numbercolumn',
                header: 'Harga NET (Exc.PPN)',
                dataIndex: 'harga_net_ect',           
                width: 130,
                sortable: true,
                align: 'right',
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'kcin_harga_net_exc_po',
                    readOnly: true,
                    allowBlank: true
                }
            },
                {
                xtype: 'numbercolumn',
                header: 'Jumlah (Exc.PPN)',
                dataIndex: 'dpp_po',           
                width: 130,
                sortable: true,
                align: 'right',
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'kcin_dpp_po',
                    readOnly: true,
                    allowBlank: true
                }
            },{
                xtype: 'numbercolumn',
                header: 'Adjustment',
                dataIndex: 'adjust',           
                width: 100,
                sortable: true,
                align: 'right',
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'kcin_adjust',
                    allowBlank: false,
                    listeners:{
                        'change': function(){
                            var dpp = Ext.getCmp('kcin_dpp_po').getValue();
                            var jumlah_gerid = dpp + this.getValue();
                            Ext.getCmp('kcin_rp_total_po').setValue(jumlah_gerid);
                        }
                    }
                }
            },{
                xtype: 'numbercolumn',
                header: 'Total',
                dataIndex: 'rp_total_po',           
                width: 100,
                sortable: true,
                align: 'right',
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'kcin_rp_total_po',
                    readOnly: true,
                    allowBlank: true
                }
            },{
                dataIndex: 'disk_persen_supp1_po',        
                hidden: true,
                editor: {
                    xtype: 'numberfield',
                    id: 'kcin_disk_persen_supp1_po',
                    readOnly: true,
                    allowBlank: true
                }
            },{
                dataIndex: 'disk_persen_supp2_po',        
                hidden: true,
                editor: {
                    xtype: 'numberfield',
                    id: 'kcin_disk_persen_supp2_po',
                    readOnly: true,
                    allowBlank: true
                }
            },{
                dataIndex: 'disk_persen_supp3_po',        
                hidden: true,
                editor: {
                    xtype: 'numberfield',
                    id: 'kcin_disk_persen_supp3_po',
                    readOnly: true,
                    allowBlank: true
                }
            },{
                dataIndex: 'disk_persen_supp4_po',        
                hidden: true,
                editor: {
                    xtype: 'numberfield',
                    id: 'kcin_disk_persen_supp4_po',
                    readOnly: true,
                    allowBlank: true
                }
            },{
                dataIndex: 'diskon_amt_supp1_po',        
                hidden: true,
                editor: {
                    xtype: 'numberfield',
                    id: 'kcin_diskon_amt_supp1_po',
                    readOnly: true,
                    allowBlank: true
                }
            },{
                dataIndex: 'diskon_amt_supp2_po',        
                hidden: true,
                editor: {
                    xtype: 'numberfield',
                    id: 'kcin_diskon_amt_supp2_po',
                    readOnly: true,
                    allowBlank: true
                }
            },{
                dataIndex: 'diskon_amt_supp3_po',        
                hidden: true,
                editor: {
                    xtype: 'numberfield',
                    id: 'kcin_diskon_amt_supp3_po',
                    readOnly: true,
                    allowBlank: true
                }
            },{
                dataIndex: 'diskon_amt_supp4_po',        
                hidden: true,
                editor: {
                    xtype: 'numberfield',
                    id: 'kcin_diskon_amt_supp4_po',
                    readOnly: true,
                    allowBlank: true
                }
            },{
                dataIndex: 'diskon_amt_supp5_po',        
                hidden: true,
                editor: {
                    xtype: 'numberfield',
                    id: 'kcin_diskon_amt_supp5_po',
                    readOnly: true,
                    allowBlank: true
                }
            }]
			
			
    });


    gridkonsinyasicreateinvoice.getSelectionModel().on('selectionchange', function(sm) {
        gridkonsinyasicreateinvoice.removeBtn.setDisabled(sm.getCount() < 1);
    });

    var konsinyasicreateinvoice = new Ext.FormPanel({
        id: 'konsinyasicreateinvoice',
        title: 'Create Invoice',
        border: false,
        frame: true,
        autoScroll: true,
        bodyStyle: 'padding-right:20px;',
        labelWidth: 130,
        items: [{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },
                items: [headerkonsinyasicreateinvoice]
            },
            gridkonsinyasicreateinvoice,
            {
                layout: 'column',
                border: false,
                items: [{
                        columnWidth: .6,
                        style: 'margin:6px 3px 0 0;',
                        layout: 'form',
                        labelWidth: 110,
                        buttonAlign: 'left',
//                        buttons: [{
//                                text: 'Cetak'
//                            }],
                        items: []
                    }, {
                        columnWidth: .4,
                        layout: 'form',
                        style: 'margin:6px 0 0 0;',
                        border: false,
                        labelWidth: 110,
                        defaults: {labelSeparator: ''},
                        items: [
                            {
                                xtype: 'fieldset',
                                autoHeight: true,
                                items: [
                                    {
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'Total',
                                        name: 'rp_jumlah',
                                        readOnly: true,
                                        id: 'kcin_rp_jumlah',
                                        anchor: '90%',
                                        fieldClass: 'readonly-input number',
                                        selectOnFocus: true,
                                        value: '0',
                                    }, {
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'Diskon',
                                        name: 'persen_diskon',
                                        id: 'kcin_persen_diskon',
                                        anchor: '90%',
                                        fieldClass: 'number',
                                        selectOnFocus: true,
                                        value: '0',
                                        listeners: {
                                            change: function() {
                                                var total = Ext.getCmp('kcin_rp_jumlah').getValue();
                                                var diskon = Ext.getCmp('kcin_persen_diskon').getValue();
                                                var afterDiskon = total - diskon ;
                                                var pembulatan = Ext.getCmp('kcin_pembulatan').getValue();
                                                var rp_ppn = afterDiskon * (Ext.getCmp('kcin_ppn').getValue() / 100);
                                                var total_invoice = afterDiskon + rp_ppn;
                                                var grand_total = afterDiskon + rp_ppn + pembulatan;
														
                                                Ext.getCmp('kcin_rp_diskon').setValue(diskon);
                                                Ext.getCmp('kcin_rp_ppn').setValue(rp_ppn);
                                                Ext.getCmp('kcin_rp_total_grand').setValue(grand_total);
                                                Ext.getCmp('kcin_total_invoice').setValue(total_invoice);
                                                Ext.getCmp('kcin_dpp').setValue(afterDiskon);
//                                                var total = Ext.getCmp('kcin_rp_jumlah').getValue();
//                                                var diskon = total * (this.getValue() / 100);
//                                                var afterDiskon = total - diskon;
//                                                var pembulatan = Ext.getCmp('kcin_pembulatan').getValue();
//                                                var rp_ppn = afterDiskon * (Ext.getCmp('kcin_ppn').getValue() / 100);
//                                                var grand_total = afterDiskon + rp_ppn + pembulatan;
//
//                                                Ext.getCmp('kcin_rp_diskon').setValue(diskon);
//                                                Ext.getCmp('kcin_rp_ppn').setValue(rp_ppn);
//                                                Ext.getCmp('kcin_rp_total_grand').setValue(grand_total);
//                                                Ext.getCmp('kcin_rp_total').setValue(grand_total);
                                            }
                                        }
                                    },{
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'DPP',
                                        name: 'rp_dpp',                                                                        
                                        id: 'kcin_dpp',                                       
                                        anchor: '90%',  
                                        readOnly: true, 
                                        cls:'vertical-space',
                                        fieldClass:'readonly-input number',
                                        labelStyle:'margin-top:10px;',      
                                        value:'0'                                                                                 
                                    }, {
                                        xtype: 'compositefield',
                                        fieldLabel: 'PPN',
                                        combineErrors: false,
                                        items: [
                                            {
                                                xtype: 'numericfield',
                                                currencySymbol: '',
                                                format: '0',
                                                name: 'ppn',
                                                id: 'kcin_ppn',
                                                fieldClass: 'number',
                                                width: 60,
                                                value: '0',
                                                maxValue: 100,
                                                listeners: {
                                                    'change': function() {
                                                        var total = Ext.getCmp('kcin_rp_jumlah').getValue();
                                                        var diskon = total * (Ext.getCmp('kcin_persen_diskon').getValue() / 100);
                                                        var afterDiskon = total - diskon;
                                                        var pembulatan = Ext.getCmp('kcin_pembulatan').getValue();
                                                        var rp_ppn = afterDiskon * (this.getValue() / 100);
                                                        var grand_total = afterDiskon + rp_ppn + pembulatan;

                                                        Ext.getCmp('kcin_rp_diskon').setValue(diskon);
                                                        Ext.getCmp('kcin_rp_ppn').setValue(rp_ppn);
                                                        Ext.getCmp('kcin_rp_total_grand').setValue(grand_total);
                                                        Ext.getCmp('kcin_rp_total').setValue(grand_total);
                                                    }
                                                }

                                            },
                                            {
                                                xtype: 'displayfield',
                                                value: '%',
                                                width: 17.5,
                                            },
                                            {
                                                xtype: 'numericfield',
                                                name: 'rp_ppn',
                                                id: 'kcin_rp_ppn',
                                                currencySymbol: '',
                                                fieldClass: 'readonly-input number',
                                                readOnly: true,
                                                anchor: '90%',
                                                 width: 120
                                            }
                                        ]
                                    },{
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'Total Invoice',
                                        name: 'total_invoice',                                                                        
                                        id: 'kcin_total_invoice',                                       
                                        anchor: '90%',  
                                        readOnly: true, 
                                        cls:'vertical-space',
                                        fieldClass:'readonly-input number',
                                        labelStyle:'margin-top:10px;',      
                                        value:'0'                                                                                      
                                    }, {
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'Pembulatan',
                                        name: 'pembulatan',
                                        id: 'kcin_pembulatan',
                                        fieldClass: 'number',
                                        anchor: '90%',
                                        value: '0',
                                        listeners: {
                                            'change': function() {
                                                var total = Ext.getCmp('kcin_rp_jumlah').getValue();
                                                var diskon = Ext.getCmp('kcin_persen_diskon').getValue();
                                                var afterDiskon = total - diskon ;
                                                var pembulatan = Ext.getCmp('kcin_pembulatan').getValue();
                                                var rp_ppn = afterDiskon * (Ext.getCmp('kcin_ppn').getValue() / 100);
                                                var total_invoice = afterDiskon + rp_ppn;
                                                var grand_total = afterDiskon + rp_ppn + pembulatan;
														
                                                Ext.getCmp('kcin_rp_diskon').setValue(diskon);
                                                Ext.getCmp('kcin_rp_ppn').setValue(rp_ppn);
                                                Ext.getCmp('kcin_rp_total_grand').setValue(grand_total);
                                                Ext.getCmp('kcin_total_invoice').setValue(total_invoice);
                                                Ext.getCmp('kcin_dpp').setValue(afterDiskon);
//                                                var total = Ext.getCmp('kcin_rp_jumlah').getValue();
//                                                var diskon = total * (Ext.getCmp('kcin_persen_diskon').getValue() / 100);
//                                                var afterDiskon = total - diskon;
//                                                var pembulatan = this.getValue();
//                                                var rp_ppn = afterDiskon * (Ext.getCmp('kcin_ppn').getValue() / 100);
//                                                var grand_total = afterDiskon + rp_ppn + pembulatan;
//
//                                                Ext.getCmp('kcin_rp_diskon').setValue(diskon);
//                                                Ext.getCmp('kcin_rp_total_grand').setValue(grand_total);
//                                                Ext.getCmp('kcin_rp_ppn').setValue(rp_ppn);
//                                                Ext.getCmp('kcin_rp_total').setValue(grand_total);
                                            }
                                        }
                                    }, {
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: '<b>Grand Total</b>',
                                        name: 'rp_total_grand',
                                        cls: 'vertical-space',
                                        readOnly: true,
                                        id: 'kcin_rp_total_grand',
                                        anchor: '90%',
                                        fieldClass: 'readonly-input bold-input number',
                                        labelStyle: 'margin-top:10px;',
                                        value: '0',
                                    },
                                ]
                            }
                        ]
                    }]
            }

        ],
        buttons: [{
                text: 'Save',
                handler: function() {
                    if (Ext.getCmp('kci_status_pkp').getValue() === 'YA' && Ext.getCmp('kci_no_faktur_pajak').getValue() === '' ) {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'No Faktur Pajak Harus Di Isi,Status Supplier PKP!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK

                        });
                        return;
                    }
                    if (Ext.getCmp('kci_no_bukti_supplier').getValue() === '' ) {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'No Bukti Supplier Harus Diisi!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK

                        });
                        return;
                    }
                    var konsinyasicreateinvoice = new Array();
                    strkonsinyasicreateinvoice.each(function(node) {
                        konsinyasicreateinvoice.push(node.data)
                    });

                    Ext.getCmp('konsinyasicreateinvoice').getForm().submit({
                        url: '<?= site_url("konsinyasi_create_invoice/update_row") ?>',
                        scope: this,
                        params: {
                            detail: Ext.util.JSON.encode(konsinyasicreateinvoice)
                        },
                        waitMsg: 'Saving Data...',
                        success: function(form, action) {
                            var r = Ext.util.JSON.decode(action.response.responseText);
                            Ext.Msg.show({
                                title: 'Success',
                                msg: 'Form submitted successfully',
                                modal: true,
                                icon: Ext.Msg.INFO,
                                buttons: Ext.Msg.OK,
                                fn: function(btn){
									
                                    winkonsinyasicreateinvoiceprint.show();
                                    Ext.getDom('konsinyasicreateinvoiceprint').src = r.printUrl;
                                }
                            });	

                            clearkonsinyasicreateinvoice();
                        },
                        failure: function(form, action) {
                            var fe = Ext.util.JSON.decode(action.response.responseText);
                            Ext.Msg.show({
                                title: 'Error',
                                msg: fe.errMsg,
                                modal: true,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OK,
                                fn: function(btn) {
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
                handler: function() {
                    clearkonsinyasicreateinvoice();
                }
            }]
    });
var winkonsinyasicreateinvoiceprint = new Ext.Window({
        id: 'id_winkonsinyasicreateinvoiceprint',
        title: 'Print Create Invoice',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="konsinyasicreateinvoiceprint" src=""></iframe>'
    });
    
    konsinyasicreateinvoice.on('afterrender', function() {
        this.getForm().load({
            url: '<?= site_url("konsinyasi_create_invoice/get_form") ?>',
            success: function(form, action) {
                var r = Ext.util.JSON.decode(action.response.responseText);
                if (r.data.user_peruntukan === "0") {
                    Ext.getCmp('kcin_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('kcin_peruntukan_supermarket').show();
                    Ext.getCmp('kcin_peruntukan_distribusi').hide();
                } else if (r.data.user_peruntukan === "1") {
                    Ext.getCmp('kcin_peruntukan_distribusi').setValue(true);
                    Ext.getCmp('kcin_peruntukan_supermarket').hide();
                    Ext.getCmp('kcin_peruntukan_distribusi').show();
                } else {
                    Ext.getCmp('kcin_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('kcin_peruntukan_supermarket').show();
                    Ext.getCmp('kcin_peruntukan_distribusi').show();
                }
            },
            failure: function(form, action) {
                var de = Ext.util.JSON.decode(action.response.responseText);
                Ext.Msg.show({
                    title: 'Error',
                    msg: de.errMsg,
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK,
                    fn: function(btn) {
                        if (btn == 'ok' && de.errMsg == 'Session Expired') {
                            window.location = '<?= site_url("auth/login") ?>';
                        }
                    }
                });
            }
        });
    });

    function clearkonsinyasicreateinvoice() {
        Ext.getCmp('konsinyasicreateinvoice').getForm().reset();
        Ext.getCmp('konsinyasicreateinvoice').getForm().load({
            url: '<?= site_url("konsinyasi_create_invoice/get_form") ?>',
            success: function(form, action) {
                var r = Ext.util.JSON.decode(action.response.responseText);
                if (r.data.user_peruntukan === "0") {
                    Ext.getCmp('kcin_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('kcin_peruntukan_supermarket').show();
                    Ext.getCmp('kcin_peruntukan_distribusi').hide();
                } else if (r.data.user_peruntukan === "1") {
                    Ext.getCmp('kcin_peruntukan_distribusi').setValue(true);
                    Ext.getCmp('kcin_peruntukan_supermarket').hide();
                    Ext.getCmp('kcin_peruntukan_distribusi').show();
                } else {
                    Ext.getCmp('kcin_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('kcin_peruntukan_supermarket').show();
                    Ext.getCmp('kcin_peruntukan_distribusi').show();
                }
            },
            failure: function(form, action) {
                var de = Ext.util.JSON.decode(action.response.responseText);
                Ext.Msg.show({
                    title: 'Error',
                    msg: de.errMsg,
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK,
                    fn: function(btn) {
                        if (btn == 'ok' && de.errMsg == 'Session Expired') {
                            window.location = '<?= site_url("auth/login") ?>';
                        }
                    }
                });
            }
        });
        strkonsinyasicreateinvoice.removeAll();
    }
</script>