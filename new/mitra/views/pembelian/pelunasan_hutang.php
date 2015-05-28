<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
    var strcbpphsuplier = new Ext.data.ArrayStore({
        fields: ['kd_supplier'],
        data: []
    });

    var strgridpphsuplier = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_supplier', 'nama_supplier', 'pic', 'alamat'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_receive_order/search_supplier") ?>',
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

    strgridpphsuplier.on('load', function() {
        Ext.getCmp('id_searchgridpphsuplier').focus();
    });

    var searchgridpphsuplier = new Ext.app.SearchField({
        store: strgridpphsuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridpphsuplier'
    });


    var gridpphsuplier = new Ext.grid.GridPanel({
        store: strgridpphsuplier,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'Kode Supplier',
                dataIndex: 'kd_supplier',
                width: 80,
                sortable: true
            }, {
                header: 'Nama Supplier',
                dataIndex: 'nama_supplier',
                width: 300,
                sortable: true
            }, {
                header: 'PIC',
                dataIndex: 'pic',
                width: 100,
                sortable: true
            }, {
                header: 'Alamat',
                dataIndex: 'alamat',
                width: 200,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridpphsuplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridpphsuplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cbpphsuplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('pph_nama_supplier').setValue(sel[0].get('nama_supplier'));

                    menupphsuplier.hide();
                }
                strpembelianpelunasanhutang.removeAll();
                strpphjenispembayaran.removeAll();
            }
        }
    });

    var menupphsuplier = new Ext.menu.Menu();
    menupphsuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridpphsuplier],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menupphsuplier.hide();
                }
            }]
    }));

    Ext.ux.TwinCombopphSuplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridpphsuplier.load();
            menupphsuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menupphsuplier.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridpphsuplier').getValue();
        if (sf != '') {
            Ext.getCmp('id_searchgridpphsuplier').setValue('');
            searchgridpphsuplier.onTrigger2Click();
        }
    });

    var cbpphsuplier = new Ext.ux.TwinCombopphSuplier({
        fieldLabel: 'Supplier <span class="asterix">*</span>',
        id: 'id_cbpphsuplier',
        store: strcbpphsuplier,
        mode: 'local',
        valueField: 'kd_supplier',
        displayField: 'kd_supplier',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_supplier',
        emptyText: 'Pilih Supplier'
    });

    var headerpembelianpelunasanhutang = {
        layout: 'column',
        border: false,
        items: [{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [{
                        xtype: 'textfield',
                        fieldLabel: 'No Bukti',
                        name: 'no_bukti',
                        allowBlank: true,
                        readOnly: true,
                        fieldClass: 'readonly-input',
                        id: 'pph_no_bukti',
                        maxLength: 255,
                        anchor: '90%',
                        value: ''
                    },
                    cbpphsuplier,
                    {
                        xtype: 'textfield',
                        fieldLabel: 'Keterangan <span class="asterix">*</span>',
                        allowBlank: false,
                        name: 'keterangan',
                        id: 'pph_keterangan',
                        anchor: '90%'
                    }
                ]
            }, {
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [{
                        xtype: 'datefield',
                        fieldLabel: 'Tanggal',
                        name: 'tanggal',
                        allowBlank: false,
                        format: 'd-M-Y',
                        emptyText: 'Tgl Pelunasan',
                        value: new Date(),
                        id: 'pph_tanggal',
                        anchor: '90%',
                        maxValue: (new Date()).clearTime(),
                         editable: false          
                       
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'Nama Supplier',
                        name: 'nama_supplier',
                        fieldClass: 'readonly-input',
                        readOnly: true,
                        id: 'pph_nama_supplier',
                        anchor: '90%',
                        value: ''
                    },{
                        fieldLabel: 'Peruntukan <span class="asterix">*</span>',
                        xtype: 'radiogroup',
                        name: 'kd_peruntukan',
                        columnWidth: [.5, .5],
                        allowBlank:false,
                        items: [{
                                boxLabel: 'Supermarket',
                                name: 'kd_peruntukan',
                                inputValue: '0',
                                id: 'pph_peruntukan_supermarket',
                                checked:true
                            }, {
                                boxLabel: 'Distribusi',
                                name: 'kd_peruntukan',
                                inputValue: '1',
                                id: 'pph_peruntukan_distribusi'
                            }]
                    } ]
            }
        ]
    }

    var strcbpphnoinv = new Ext.data.ArrayStore({
        fields: ['no_invoice'],
        data: []
    });
 
    var strgridpphnoinv = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_invoice', 
                    'no_bukti_supplier', 
                    'tgl_invoice', 
                    'tgl_terima_invoice',
                    'rp_total', 
                    'rp_pelunasan_hutang',
                    'sisa_invoice',
                    'total_bayar'],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_pelunasan_hutang/get_all_invoice") ?>',
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
    
    strgridpphnoinv.on('load', function(){
        Ext.getCmp('idsearch_no_invoice').focus();
    });
    // search field
    var search_no_invoice = new Ext.app.SearchField({
        store: strgridpphnoinv,
        width: 220,
        emptyText: 'No Invoice',
        id: 'idsearch_no_invoice'
    });
    
    // top toolbar
    var tb_no_invoice = new Ext.Toolbar({
        items: [search_no_invoice]
    });
     search_no_invoice.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';
			
            // Get the value of search field
            var fid = Ext.getCmp('id_cbpphsuplier').getValue();
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
	
    search_no_invoice.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }
	 
        // Get the value of search field
        var fid = Ext.getCmp('id_cbpphsuplier').getValue();
        var o = { start: 0, kd_supplier: fid };
	 
        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params:o});
        this.hasSearch = true;
        this.triggers[0].show();
    };
    
    var gridpphnoinv = new Ext.grid.GridPanel({
        store: strgridpphnoinv,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'No Invoice',
                dataIndex: 'no_invoice',
                width: 120,
                sortable: true
            }, {
                header: 'No Bukti Supplier',
                dataIndex: 'no_bukti_supplier',
                width: 100,
                sortable: true
            }, {
                header: 'Tgl Invoice',
                dataIndex: 'tgl_invoice',
                width: 80,
                sortable: true
            },{
                header: 'Tgl Terima Invoice',
                dataIndex: 'tgl_terima_invoice',
                width: 80,
                sortable: true
            }, {
                xtype: 'numbercolumn',
                header: 'Total',
                dataIndex: 'rp_total',
                width: 100,
                align: 'right',
                format: '0,0',
                sortable: true
            }, {
                xtype: 'numbercolumn',
                header: 'Sisa',
                dataIndex: 'sisa_invoice',
                width: 100,
                align: 'right',
                format: '0,0',
                sortable: true
            }],
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('epph_no_invoice').setValue(sel[0].get('no_invoice'));
                    Ext.getCmp('epph_no_bukti_supplier').setValue(sel[0].get('no_bukti_supplier'));
                    Ext.getCmp('epph_tgl_terima_invoice').setValue(sel[0].get('tgl_terima_invoice'));
                    Ext.getCmp('epph_total_bayar').setValue(sel[0].get('total_bayar'));
                    Ext.getCmp('epph_rp_jumlah').setValue(sel[0].get('rp_total'));
                    var outstanding = sel[0].get('rp_total') - sel[0].get('rp_pelunasan_hutang');
                    Ext.getCmp('epph_rp_total').setValue(outstanding);
                    Ext.getCmp('epph_rp_bayar').setValue(outstanding);
                    Ext.getCmp('epph_rp_diskon').setValue(0);
                    Ext.getCmp('epph_rp_dibayar').setValue(outstanding);
                    Ext.getCmp('epph_rp_sisa_invoice').setValue(0);
                    Ext.getCmp('epph_rp_bayar').focus();
                    
                    var _ada = false;
                    strpembelianpelunasanhutang.each(function(record){
                    if(record.get('no_invoice') === sel[0].get('no_invoice')){
                             _ada = true;
                         }
                     });
                     
                     if (_ada){
                                    Ext.Msg.show({
                                        title: 'Error',
                                        msg: 'No Invoice yang sama tidak boleh dipilih lebih dari satu kali',
                                        modal: true,
                                        icon: Ext.Msg.ERROR,
                                        buttons: Ext.Msg.OK,
                                        fn: function(btn){
                                            if (btn == 'ok') {
                                                Ext.getCmp('epph_no_invoice').reset();
                                            }
                                        }                            
                                    });
                                    Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                                    Ext.getCmp('epph_no_invoice').focus();	
                                    return;
                                }
                    menupphnoinv.hide();
                }
            }
        },
          tbar: tb_no_invoice,
          bbar: new Ext.PagingToolbar({
          pageSize: ENDPAGE,
          store: strgridpphnoinv,
          displayInfo: true
        })
    });

    var menupphnoinv = new Ext.menu.Menu();
    menupphnoinv.add(new Ext.Panel({
        title: 'Pilih No Invoice',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 450,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridpphnoinv],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menupphnoinv.hide();
                }
            }]
    }));

    Ext.ux.TwinCombopphnoinv = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridpphnoinv.load({
                params: {
                    kd_supplier: Ext.getCmp('id_cbpphsuplier').getValue()
                }
            });
            menupphnoinv.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    var strpembelianpelunasanhutang = new Ext.data.Store({
        autoSave: false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'no_bukti', allowBlank: false, type: 'text'},
                {name: 'no_bukti_supplier', allowBlank: false, type: 'text'},
                {name: 'no_invoice', allowBlank: false, type: 'text'},
                {name: 'rp_total', allowBlank: false, type: 'int'},
                {name: 'rp_diskon', allowBlank: false, type: 'int'},
                {name: 'tgl_invoice', allowBlank: false, type: 'text'},
                {name: 'rp_bayar', allowBlank: false, type: 'int'},
                {name: 'rp_dibayar', allowBlank: false, type: 'int'},
                {name: 'rp_sisa_invoice', allowBlank: false, type: 'int'},
                {name: 'rp_jumlah', allowBlank: false, type: 'int'}
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        writer: new Ext.data.JsonWriter(
                {
                    encode: true,
                    writeAllFields: true
                })
    });

    strpembelianpelunasanhutang.on('update', function() {
        var total_invoice = 0;
        var total_potongan = 0;
        var total_dibayar = 0;
        var total_bayar = 0;//Ext.getCmp('pph_rp_total').getValue();
        strpembelianpelunasanhutang.each(function(node) {
            total_invoice += Math.round(node.data.rp_total);
            //total_bayar += parseInt(node.data.rp_bayar);
            total_potongan += parseInt(node.data.rp_diskon);
            total_dibayar += Math.round(node.data.rp_dibayar);
            //selisih += parseInt(node.data.rp_sisa_invoice);
        });

        strpphjenispembayaran.each(function(node) {
            total_bayar += Math.round(node.data.rp_bayar);
        });


        Ext.getCmp('pph_rp_total').setValue(total_bayar);

    Ext.getCmp('pph_rp_total_invoice').setValue(total_invoice);
        Ext.getCmp('pph_rp_total').setValue(total_bayar);
        Ext.getCmp('pph_rp_total_potongan').setValue(total_potongan);
        Ext.getCmp('pph_rp_total_dibayar').setValue(total_dibayar);
        Ext.getCmp('pph_rp_selisih').setValue(total_bayar - total_dibayar);
    });



    var editorpembelianpelunasanhutang = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });


    var gridpembelianpelunasanhutang = new Ext.grid.GridPanel({
        store: strpembelianpelunasanhutang,
        stripeRows: true,
        height: 200,
        frame: true,
        border: true,
        tbar: [{
                icon: BASE_ICONS + 'add.png',
                text: 'Add Invoice',
                handler: function() {

                    if (Ext.getCmp('id_cbpphsuplier').getValue() === '') {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Silahkan pilih supplier terlebih dulu',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                        });
                        return;
                    }

                    var rowpembelianpelunasanhutang = new gridpembelianpelunasanhutang.store.recordType({
                        no_invoice: '',
                        rp_total: '',
                        rp_bayar: '',
                        rp_diskon: ''
                    });
                    editorpembelianpelunasanhutang.stopEditing();
                    strpembelianpelunasanhutang.insert(0, rowpembelianpelunasanhutang);
                    gridpembelianpelunasanhutang.getView().refresh();
                    gridpembelianpelunasanhutang.getSelectionModel().selectRow(0);
                    editorpembelianpelunasanhutang.startEditing(0);
                }
            }, {
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                disabled: true,
                handler: function() {

                    editorpembelianpelunasanhutang.stopEditing();
                    var s = gridpembelianpelunasanhutang.getSelectionModel().getSelections();
                    for (var i = 0, r; r = s[i]; i++) {
                        strpembelianpelunasanhutang.remove(r);
                    }
                    var jumlah = 0;

                    strpembelianpelunasanhutang.each(function(node) {
                        jumlah += Math.round(node.data.rp_total);
                    });

                    Ext.getCmp('pph_rp_total_invoice').setValue(jumlah);
                }
            }],
        plugins: [editorpembelianpelunasanhutang],
        columns: [{
                header: 'No Invoice',
                dataIndex: 'no_invoice',
                width: 150,
                sortable: true,
                editor: new Ext.ux.TwinCombopphnoinv({
                    id: 'epph_no_invoice',
                    store: strcbpphnoinv,
                    mode: 'local',
                    valueField: 'no_invoice',
                    displayField: 'no_invoice',
                    typeAhead: true,
                    triggerAction: 'all',
                    editable: false,
                    hiddenName: 'no_invoice',
                    emptyText: 'Pilih No Invoice'

                })
            }, {
                header: 'Bukti Supplier',
                dataIndex: 'no_bukti_supplier',
                width: 100,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epph_no_bukti_supplier',
                    fieldClass: 'readonly-input number'
                })
            }, {
                header: 'Tanggal',
                dataIndex: 'tgl_terima_invoice',
                width: 100,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epph_tgl_terima_invoice',
                    fieldClass: 'readonly-input number'
                })
            },{
                xtype: 'numbercolumn',
                header: 'Rp Invoice',
                dataIndex: 'rp_jumlah',
                width: 150,
                align: 'right',
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'epph_rp_jumlah',
                    fieldClass: 'readonly-input number',
                    readOnly: true
                }
            },{
                xtype: 'numbercolumn',
                header: 'Rp Total Bayar',
                dataIndex: 'total_bayar',
                width: 150,
                align: 'right',
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'epph_total_bayar',
                    fieldClass: 'readonly-input number',
                    readOnly: true
                }
            }, {
                xtype: 'numbercolumn',
                header: 'Jml Outstanding Invoice',
                dataIndex: 'rp_total',
                width: 150,
                align: 'right',
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'epph_rp_total',
                    fieldClass: 'readonly-input number',
                    readOnly: true
                }
            }, {
                xtype: 'numbercolumn',
                header: 'Jumlah Bayar',
                dataIndex: 'rp_bayar',
                width: 110,
                sortable: true,
                align: 'right',
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'epph_rp_bayar',
                    listeners: {
                        'change': function() {

                            var sisa = Ext.getCmp('epph_rp_total').getValue() - this.getValue();
                            Ext.getCmp('epph_rp_sisa_invoice').setValue(sisa);

                            var dibayar = this.getValue() - Ext.getCmp('epph_rp_diskon').getValue();
                            Ext.getCmp('epph_rp_dibayar').setValue(dibayar);
                        }
                    }
                }
            }, {
                xtype: 'numbercolumn',
                header: 'Potongan',
                dataIndex: 'rp_diskon',
                width: 100,
                sortable: true,
                align: 'right',
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'epph_rp_diskon',
                    listeners: {
                        'change': function() {
                            var dibayar = Ext.getCmp('epph_rp_bayar').getValue() - this.getValue();
                            Ext.getCmp('epph_rp_dibayar').setValue(dibayar);
                        }
                    }
                }
            }, {
                xtype: 'numbercolumn',
                header: 'Jumlah Dibayar',
                dataIndex: 'rp_dibayar',
                width: 100,
                sortable: true,
                align: 'right',
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'epph_rp_dibayar',
                    fieldClass: 'readonly-input number',
                    readOnly: true
                }
            }, {
                xtype: 'numbercolumn',
                header: 'Sisa Invoice',
                dataIndex: 'rp_sisa_invoice',
                width: 100,
                sortable: true,
                align: 'right',
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'epph_rp_sisa_invoice',
                    fieldClass: 'readonly-input number',
                    readOnly: true
                }
            } ]
    });

    gridpembelianpelunasanhutang.getSelectionModel().on('selectionchange', function(sm) {
        gridpembelianpelunasanhutang.removeBtn.setDisabled(sm.getCount() < 1);
    });

    var _strcbpphjnspembayaran = new Ext.data.ArrayStore({
        fields: ['kd_jenis_bayar'],
        data: []
    });

    var _strgridpphjnspembayaran = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_jenis_bayar', 'nm_pembayaran', 'is_validasi_card'],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_pelunasan_hutang/get_all_jenis_pembayaran") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function(event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var _gridpphjnspembayaran = new Ext.grid.GridPanel({
        store: _strgridpphjnspembayaran,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'Kode',
                dataIndex: 'kd_jenis_bayar',
                width: 70,
                sortable: true
            }, {
                header: 'Jenis Pembayaran',
                dataIndex: 'nm_pembayaran',
                width: 200,
                sortable: true
            }, {
                header: 'Jenis Pembayaran',
                dataIndex: 'is_validasi_card',
                width: 200,
                sortable: true,
                hidden: true
            }],
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('epph_kd_jenis_bayar').setValue(sel[0].get('kd_jenis_bayar'));
                    Ext.getCmp('epph_nama_pembayaran').setValue(sel[0].get('nm_pembayaran'));
                    Ext.getCmp('epph_is_validasi_card').setValue(sel[0].get('is_validasi_card'));
                    menupphjnspembayaran.hide();
                }
            }
        }
    });

    var menupphjnspembayaran = new Ext.menu.Menu();
    menupphjnspembayaran.add(new Ext.Panel({
        title: 'Pilih Jenis Pembayaran',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 300,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [_gridpphjnspembayaran],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menupphjnspembayaran.hide();
                }
            }]
    }));

    Ext.ux.TwinCombopphjnspembayaran = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            _strgridpphjnspembayaran.load();
            menupphjnspembayaran.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    var strpphjenispembayaran = new Ext.data.Store({
        autoSave: false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_jenis_bayar', type: 'text'},
                {name: 'nm_pembayaran', type: 'text'},
                {name: 'rp_bayar', type: 'int'},
                {name: 'nomor_ref', type: 'text'},
                {name: 'nomor_bank', type: 'text'},
                {name: 'tgl_jth_tempo', type: 'text'},
                {name: 'is_validasi_card', type: 'int'}
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        writer: new Ext.data.JsonWriter(
                {
                    encode: true,
                    writeAllFields: true
                })
    });

    strpphjenispembayaran.on('update', function() {
        var total_bayar = 0;
        var total_dibayar = Ext.getCmp('pph_rp_total_dibayar').getValue();
        var biaya_lain_lain = Ext.getCmp('pph_rp_biaya_lain').getValue();
        var grand_total = Ext.getCmp('pph_grand_total').getValue();
        
        strpphjenispembayaran.each(function(node) {
            total_bayar += parseInt(node.data.rp_bayar);
           });
        
        
        total_bayar = +total_bayar || 0;
        Ext.getCmp('pph_rp_total').setValue(total_bayar);
        Ext.getCmp('pph_rp_selisih').setValue(total_bayar - total_dibayar);
        Ext.getCmp('pph_grand_total').setValue(total_bayar + biaya_lain_lain);

    });

    strpphjenispembayaran.on('remove', function() {
        var total_bayar = 0;
        var total_dibayar = Ext.getCmp('pph_rp_total_dibayar').getValue();

        strpphjenispembayaran.each(function(node) {
            total_bayar += parseInt(node.data.rp_bayar);
        });

        total_bayar = +total_bayar || 0;
        Ext.getCmp('pph_rp_total').setValue(total_bayar);
        Ext.getCmp('pph_rp_selisih').setValue(total_bayar - total_dibayar);

    });

    var editorpphjenispembayaran = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });

    var gridpphjenispembayaran = new Ext.grid.GridPanel({
        id: 'idgridpphjenispembayaran',
        title: 'JENIS PEMBAYARAN',
        store: strpphjenispembayaran,
        stripeRows: true,
        height: 200,
        border: true,
        frame: true,
        plugins: [editorpphjenispembayaran],
        tbar: [{
                icon: BASE_ICONS + 'add.png',
                text: 'Add Pembayaran',
                handler: function() {
                    if (Ext.getCmp('pph_rp_total_invoice').getValue() === 0) {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Total invoice masih kosong!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                        });
                        return;
                    }
                    var rowpphjenispembayaran = new gridpphjenispembayaran.store.recordType({
                        kd_jenis_bayar: '',
                        rp_bayar: '',
                        nomor_bank: '',
                        nomor_ref: '',
                        tgl_jth_tempo: '',
                        is_validasi_card:''
                    });
                    editorpphjenispembayaran.stopEditing();
                    strpphjenispembayaran.insert(0, rowpphjenispembayaran);
                    gridpphjenispembayaran.getView().refresh();
                    gridpphjenispembayaran.getSelectionModel().selectRow(0);
                    editorpphjenispembayaran.startEditing(0);
                }
            }, {
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                disabled: true,
                handler: function() {

                    editorpphjenispembayaran.stopEditing();
                    var s = gridpphjenispembayaran.getSelectionModel().getSelections();
                    for (var i = 0, r; r = s[i]; i++) {
                        strpphjenispembayaran.remove(r);
                    }

                }
            }],
        columns: [{
                header: 'Kode',
                dataIndex: 'kd_jenis_bayar',
                width: 100,
                editor: new Ext.ux.TwinCombopphjnspembayaran({
                    id: 'epph_kd_jenis_bayar',
                    store: _strcbpphjnspembayaran,
                    mode: 'local',
                    valueField: 'kd_jenis_bayar',
                    displayField: 'kd_jenis_bayar',
                    typeAhead: true,
                    triggerAction: 'all',
                    editable: false,
                    hiddenName: 'kd_jenis_bayar',
                    emptyText: 'Pilih Jenis Pembayaran'

                })
            }, {
                header: 'Jenis Pembayaran',
                dataIndex: 'nm_pembayaran',
                width: 200,
                sortable: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epph_nama_pembayaran',
                    fieldClass: 'readonly-input'
                })
            }, {
                xtype: 'numbercolumn',
                header: 'Jumlah Bayar',
                dataIndex: 'rp_bayar',
                width: 110,
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'epph_rp_bayar_jns',
                    selectOnFocus: true
                }
            }, {
                header: 'Nama Bank',
                dataIndex: 'nomor_bank',
                width: 100,
                editor: new Ext.form.TextField({
                    id: 'epph_nomor_bank'
                })
            }, {
                header: 'No Warkat',
                dataIndex: 'nomor_ref',
                width: 100,
                editor: new Ext.form.TextField({
                    id: 'epph_nomor_ref'
                })
            }, {
                xtype: 'datecolumn',
                header: 'Tgl Jatuh Tempo',
                dataIndex: 'tgl_jth_tempo',
                format: 'd/m/Y',
                width: 120,
                editor: new Ext.form.DateField({
                    id: 'epph_tgl_jth_tempo',
                    format: 'd/m/Y'
                })
            },{
                header: 'validasi card',
                dataIndex: 'is_validasi_card',
                width: 100,
                hidden : false,
                editor: new Ext.form.TextField({
                    id: 'epph_is_validasi_card',
                    readOnly : true,
                    fieldClass: 'readonly-input number'
                })
            } 
        ]
    });

    gridpphjenispembayaran.getSelectionModel().on('selectionchange', function(sm) {
        gridpphjenispembayaran.removeBtn.setDisabled(sm.getCount() < 1);
    });
    
   //Pembayaran Lain-Lain
    var _strcbpphpembayaranlain = new Ext.data.ArrayStore({
        fields: ['kd_jenis_bayar'],
        data: []
    });

    var _strgridpphpembayaranlain = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_jenis_bayar', 'nm_pembayaran'],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_pelunasan_hutang/get_all_jenis_pembayaran") ?>',
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

    var _gridpphpembayaranlain = new Ext.grid.GridPanel({
        store: _strgridpphpembayaranlain,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'Kode',
                dataIndex: 'kd_jenis_bayar',
                width: 70,
                sortable: true,
            }, {
                header: 'Jenis Pembayaran',
                dataIndex: 'nm_pembayaran',
                width: 200,
                sortable: true,
            }],
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('epph_kd_jenis_bayar_lain').setValue(sel[0].get('kd_jenis_bayar'));
                    Ext.getCmp('epph_nama_pembayaran_lain').setValue(sel[0].get('nm_pembayaran'));

                    menupphpembayaranlain.hide();
                }
            }
        }
    });

    var menupphpembayaranlain = new Ext.menu.Menu();
    menupphpembayaranlain.add(new Ext.Panel({
        title: 'Pilih Jenis Pembayaran',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 300,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [_gridpphpembayaranlain],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menupphpembayaranlain.hide();
                }
            }]
    }));

    Ext.ux.TwinCombopphpembayaranlain = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            _strgridpphpembayaranlain.load();
            menupphpembayaranlain.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    var strpphpembayaranlain2 = new Ext.data.Store({
        autoSave: false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_jenis_bayar', type: 'text'},
                {name: 'nm_pembayaran', type: 'text'},
                {name: 'rp_bayar_lain', type: 'int'},
                {name: 'nomor_ref', type: 'text'},
                {name: 'nomor_bank', type: 'text'},
                {name: 'tgl_jth_tempo', type: 'text'},
                {name: 'keterangan', type: 'text'}
                ],
            root: 'data',
            totalProperty: 'record'
        }),
        writer: new Ext.data.JsonWriter(
                {
                    encode: true,
                    writeAllFields: true
                })
    });



    strpphpembayaranlain2.on('update', function() {
        var total_bayar_lain = 0;
        var biaya_lain = Ext.getCmp('pph_rp_total_dibayar').getValue();
        var rp_total_bayar = Ext.getCmp('pph_rp_total').getValue();
        
        strpphpembayaranlain2.each(function(node) {
            total_bayar_lain += parseInt(node.data.rp_bayar_lain);
        });


        Ext.getCmp('pph_rp_biaya_lain').setValue(total_bayar_lain);
        Ext.getCmp('pph_grand_total').setValue(total_bayar_lain + rp_total_bayar);



    });

    var editorpphpembayaranlain = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });

    var gridpphpembayaranlain = new Ext.grid.GridPanel({
        id: 'idgridpphpembayaranlain',
        title: 'BIAYA LAIN-LAIN',
        store: strpphpembayaranlain2,
        stripeRows: true,
        height: 200,
        border: true,
        frame: true,
        plugins: [editorpphpembayaranlain],
        tbar: [{
                icon: BASE_ICONS + 'add.png',
                text: 'Add Pembayaran',
                handler: function() {
                    if (Ext.getCmp('pph_rp_total_invoice').getValue() == 0) {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Total Invoice masih kosong!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                        });
                        return;
                    }
                    var rowpphpembayaranlain = new gridpphpembayaranlain.store.recordType({
                        kd_jenis_bayar: '',
                        rp_bayar: '',
                        nomor_bank: '',
                        nomor_ref: '',
                        tgl_jth_tempo: ''
                    });
                    editorpphpembayaranlain.stopEditing();
                    strpphpembayaranlain2.insert(0, rowpphpembayaranlain);
                    gridpphpembayaranlain.getView().refresh();
                    gridpphpembayaranlain.getSelectionModel().selectRow(0);
                    editorpphpembayaranlain.startEditing(0);
                }
            }, {
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                disabled: true,
                handler: function() {

                    editorpphpembayaranlain.stopEditing();
                    var s = gridpphpembayaranlain.getSelectionModel().getSelections();
                    for (var i = 0, r; r = s[i]; i++) {
                        strpphpembayaranlain2.remove(r);
                    }

                }
            }],
        columns: [{
                header: 'Keterangan',
                dataIndex: 'keterangan',
                width: 200,
                sortable: true,
                editor: new Ext.form.TextField({
                    readOnly: false,
                    id: 'epph_keterangan_lain',
                })
            },{
                header: 'Kode',
                dataIndex: 'kd_jenis_bayar',
                width: 100,
                editor: new Ext.ux.TwinCombopphpembayaranlain({
                    id: 'epph_kd_jenis_bayar_lain',
                    store: _strcbpphpembayaranlain,
                    mode: 'local',
                    valueField: 'kd_jenis_bayar',
                    displayField: 'kd_jenis_bayar',
                    typeAhead: true,
                    triggerAction: 'all',
                    editable: false,
                    hiddenName: 'kd_jenis_bayar',
                    emptyText: 'Pilih Jenis Pembayaran Lain'

                })
            }, {
                header: 'Jenis Pembayaran',
                dataIndex: 'nm_pembayaran',
                width: 200,
                sortable: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epph_nama_pembayaran_lain',
                    fieldClass: 'readonly-input'
                })
            }, {
                xtype: 'numbercolumn',
                header: 'Jumlah Bayar',
                dataIndex: 'rp_bayar_lain',
                width: 100,
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'epph_rp_bayar_jns_lain',
                    selectOnFocus: true
                }
            }, {
                header: 'Nama Bank',
                dataIndex: 'nomor_bank',
                width: 100,
                editor: new Ext.form.TextField({
                    id: 'epph_nomor_bank_lain'
                })
            }, {
                header: 'No Warkat',
                dataIndex: 'nomor_ref',
                width: 100,
                editor: new Ext.form.TextField({
                    id: 'eppp_nomor_ref_lain'
                })
            }, {
                xtype: 'datecolumn',
                header: 'Tgl Jatuh Tempo',
                dataIndex: 'tgl_jth_tempo',
                format: 'd/m/Y',
                width: 120,
                editor: new Ext.form.DateField({
                    id: 'epph_tgl_jth_tempo_lain',
                    format: 'd/m/Y'
                })
            }
        ]
    });

    gridpphpembayaranlain.getSelectionModel().on('selectionchange', function(sm) {
        gridpphpembayaranlain.removeBtn.setDisabled(sm.getCount() < 1);
    });
   //End Pembayaran lain-lain

    var pembelianpelunasanhutang = new Ext.FormPanel({
        id: 'pembelianpelunasanhutang',
        border: false,
        frame: true,
        autoScroll: true,
        monitorValid: true,
        bodyStyle: 'padding-right:20px;',
        labelWidth: 130,
        items: [
            {
                bodyStyle: { margin: '0px 0px 15px 0px'},
                items: [headerpembelianpelunasanhutang]
            },
            gridpembelianpelunasanhutang,
            {
                layout: 'column',
                border: false,
                items: [
                    {
                        columnWidth: .7,
                        style: 'margin:6px 3px 0 0;',
                        layout: 'fit',
                        items: [{
                                xtype: 'tabpanel',
                                height: 200,
                                activeTab: 0,
                                deferredRender: false,
                                items: [gridpphjenispembayaran,gridpphpembayaranlain]
                            }
                        ]
                    }, {
                        columnWidth: .3,
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
                                        fieldLabel: 'Total Invoice',
                                        name: 'rp_total_invoice',
                                        readOnly: true,
                                        id: 'pph_rp_total_invoice',
                                        anchor: '90%',
                                        fieldClass: 'readonly-input number',
                                        value: '0'
                                    }, {
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'Total Potongan',
                                        name: 'rp_total_potongan',
                                        id: 'pph_rp_total_potongan',
                                        anchor: '90%',
                                        readOnly: true,
                                        fieldClass: 'readonly-input number',
                                        value: '0'
                                    }, {
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'Total Dibayar',
                                        name: 'rp_total_dibayar',
                                        id: 'pph_rp_total_dibayar',
                                        anchor: '90%',
                                        readOnly: true,
                                        fieldClass: 'readonly-input number',
                                        value: '0'
                                    }, {
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'Total Bayar',
                                        name: 'rp_total',
                                        id: 'pph_rp_total',
                                        anchor: '90%',
                                        readOnly: true,
                                        fieldClass: 'readonly-input number',
                                        value: '0'
                                    }, {
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'Selisih',
                                        name: 'rp_selisih',
                                        readOnly: true,
                                        fieldClass: 'readonly-input number',
                                        id: 'pph_rp_selisih',
                                        anchor: '90%',
                                        value: '0'
                                    },{
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'Biaya Lain',
                                        name: 'rp_biaya_lain',
                                        readOnly: true,
                                        fieldClass: 'readonly-input number',
                                        id: 'pph_rp_biaya_lain',
                                        anchor: '90%',
                                        value: '0'
                                    },{
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'Grand Total',
                                        name: 'rp_grand_total',
                                        readOnly: true,
                                        fieldClass: 'readonly-input number',
                                        id: 'pph_grand_total',
                                        anchor: '90%',
                                        value: '0'
                                    }
                                ]
                            }
                        ]
                    }]
            }

        ],
        buttons: [{
                text: 'Save',
                formBind: true,
                handler: function() {

                    if (Ext.getCmp('pph_rp_total_invoice').getValue() === 0) {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Total invoice kosong!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                        });
                        return;
                    }
                     if (Ext.getCmp('pph_rp_total').getValue() >  Ext.getCmp('pph_rp_total_dibayar').getValue()) {
                        if (Ext.getCmp('epph_rp_sisa_invoice').getValue() > '0' ){
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Sisa Invoice Tidak Boleh > 0, Apabila Total Bayar > Total dibayar',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK,
                        });
                        return;
                        }
                    }  
                    
                    var nama_pembayaran = "";
                    var no_bank = "";
                    var no_ref = "";
                    var tgl_jth_tempo = "";
                    var is_validasi_card = "";
                    
                    var is_true = true;
                    var is_validasi = true;
                    strpphjenispembayaran.each(function(node) {
                        nama_pembayaran = node.data.nm_pembayaran;
                        no_bank = node.data.nomor_bank;
                        no_ref = node.data.nomor_ref;
                        tgl_jth_tempo = node.data.tgl_jth_tempo;
                        is_validasi_card = node.data.is_validasi_card;
                        console.log(nama_pembayaran);
                        console.log(is_validasi_card);
                        console.log(no_bank.length);
                        if (is_validasi_card === '1'){
                            if (no_bank.length <= 0 || no_ref.length <= 0 || tgl_jth_tempo.length <= 0 ){
                                is_validasi = false;
                            }
                        }
                        if (nama_pembayaran === 'CEK' || nama_pembayaran === 'GIRO' ){
                            if (no_bank.length <= 0 || no_ref.length <= 0 || tgl_jth_tempo.length <= 0 ){
                                is_true = false;
                            }
                         }
                    });
                    console.log(is_true);
                    console.log(is_validasi);
                    if(!is_validasi){
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Pembayaran Lewat Bank,No Bank,Nomer Warkat dan Tanggal Jatuh Tempo Harus Diisi !!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK          
                        });
                        Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                        return;
                    }
                    if(!is_true){
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Pembayaran Cek atau Giro,No Bank,Nomer Warkat dan Tanggal Jatuh Tempo Harus Diisi !!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK          
                        });
                        Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                        return;
                    }
                    var is_true_lain = true;
                    var is_card_lain = false;
                    strpphpembayaranlain2.each(function(node) {
                        nama_pembayaran = node.data.nm_pembayaran;
                        no_bank = node.data.nomor_bank;
                        no_ref = node.data.nomor_ref;
                        tgl_jth_tempo = node.data.tgl_jth_tempo;
                        if (nama_pembayaran === 'CEK' || nama_pembayaran === 'GIRO' ){
                            if (no_bank.length <= 0 || no_ref.length <= 0 || tgl_jth_tempo.length <= 0 ){
                                is_true_lain = false;
                            }
                        }
                        if ( (nama_pembayaran.indexOf('DEBIT') >= 0 || nama_pembayaran.indexOf('KREDIT') >= 0) && no_ref.length<=0 ) {
                            is_card_lain = true;
                        }
                    });
                    if(!is_true_lain){
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Pembayaran Cek atau Giro,No Bank,Nomer Warkat dan Tanggal Jatuh Tempo Harus Diisi !!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                        });
                        Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                        return;
                    }
                    if(is_card_lain){
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Pembayaran Dengan Kartu Debit/Kredit, Nomor Warkat Harus Diisi !!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                        });
                        Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                        return;
                    }
                    
                    var detailpembelianpelunasanhutang = new Array();
                    var jumlah = 0;
                    var rp_sisa_invoice = 0;

                    strpembelianpelunasanhutang.each(function(node) {
                        detailpembelianpelunasanhutang.push(node.data);
                        jumlah += parseInt(node.data.rp_dibayar);
                        rp_sisa_invoice += parseInt(node.data.rp_sisa_invoice);
                    });

                    var detailbayarpembelianpelunasanhutang = new Array();
                    var jumlah_byr = 0;

                    function isEmpty(str) {
                        return (!str || 0 === str.length);
                    }

                    var nomor_bank = 0;
                    var nomor_ref = 0;
                    var tgl_jth_tempo = 0;

                    strpphjenispembayaran.each(function(node) {
                        detailbayarpembelianpelunasanhutang.push(node.data);
                        if (node.data.is_validasi_card === 1) {
                            if (node.data.nomor_bank === '') {
                                nomor_bank = nomor_bank + 1;
                            } else {
                                nomor_bank = nomor_bank - 1;
                            }

                            if (node.data.nomor_ref === '') {
                                nomor_ref = nomor_ref + 1;
                            } else {
                                nomor_ref = nomor_ref - 1;
                            }

                            if (node.data.tgl_jth_tempo === '') {
                                tgl_jth_tempo = tgl_jth_tempo + 1;
                            } else {
                                tgl_jth_tempo = tgl_jth_tempo - 1;
                            }
                        }
                        jumlah_byr += parseInt(node.data.rp_bayar);
                    });
                    
                    var detailpembayaranhutangbiayalain = new Array();

                    strpphpembayaranlain2.each(function(node) {
                        detailpembayaranhutangbiayalain.push(node.data);

                    });
                    
                    if (jumlah_byr < jumlah) {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Jumlah yg dibayar tidak boleh lebih besar dari Jumlah Bayar!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                        });
                        return;
                    }

                    if (rp_sisa_invoice < 0) {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Sisa invoice tidak boleh lebih kecil dari Nol',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                        });
                        return;
                    }
                     if (Ext.getCmp('pph_rp_selisih').getValue() < '0' || Ext.getCmp('pph_rp_selisih').getValue() > '0') {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Selisih Harus NOL (0)',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                        });
                        return;
                    }
                    Ext.getCmp('pembelianpelunasanhutang').getForm().submit({
                        url: '<?= site_url("pembelian_pelunasan_hutang/update_row") ?>',
                        scope: this,
                        params: {
                            detail: Ext.util.JSON.encode(detailpembelianpelunasanhutang),
                            detailbayar: Ext.util.JSON.encode(detailbayarpembelianpelunasanhutang),
                            detailbiayalain : Ext.util.JSON.encode(detailpembayaranhutangbiayalain),
                            _total_invoice: Ext.getCmp('pph_rp_total_invoice').getValue(),
                            _total_bayar: Ext.getCmp('pph_rp_total').getValue(),
                            _total_potongan: Ext.getCmp('pph_rp_total_potongan').getValue(),
                            _total_dibayar: Ext.getCmp('pph_rp_total_dibayar').getValue(),
                            _total_biaya_lain: Ext.getCmp('pph_rp_biaya_lain').getValue(),
                            _grand_total: Ext.getCmp('pph_grand_total').getValue(),
                            _selisih: Ext.getCmp('pph_rp_selisih').getValue()
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
                                fn: function(btn) {
                                    if (btn == 'ok') {
                                        winpelunasanhutangprint.show();
                                        Ext.getDom('pelunasanhutangprint').src = r.printUrl;
                                    }
                                }
                            });

                            clearpembelianpelunasanhutang();
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
                                    if (btn === 'ok' && fe.errMsg === 'Session Expired') {
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
                    clearpembelianpelunasanhutang();
                }
            }]
    });
 var winpelunasanhutangprint = new Ext.Window({
        id: 'id_winpelunasanhutangprint',
        title: 'Print Pembayaran Hutang',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="pelunasanhutangprint" src=""></iframe>'
    });
    
    pembelianpelunasanhutang.on('afterrender', function() {
        this.getForm().load({
            url: '<?= site_url("pembelian_pelunasan_hutang/get_form") ?>',
            success: function(form, action){
                var r = Ext.util.JSON.decode(action.response.responseText);
                if(r.data.user_peruntukan === "0"){
                    Ext.getCmp('pph_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('pph_peruntukan_supermarket').show();
                    Ext.getCmp('pph_peruntukan_distribusi').hide();
                }else if(r.data.user_peruntukan === "1"){
                    Ext.getCmp('pph_peruntukan_distribusi').setValue(true);
                    Ext.getCmp('pph_peruntukan_supermarket').hide();
                    Ext.getCmp('pph_peruntukan_distribusi').show();
                }else{
                    Ext.getCmp('pph_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('pph_peruntukan_supermarket').show();
                    Ext.getCmp('pph_peruntukan_distribusi').show();
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
                        if (btn === 'ok' && de.errMsg === 'Session Expired') {
                            window.location = '<?= site_url("auth/login") ?>';
                        }
                    }
                });
            }
        });
    });

    function clearpembelianpelunasanhutang() {
        Ext.getCmp('pembelianpelunasanhutang').getForm().reset();
        Ext.getCmp('pembelianpelunasanhutang').getForm().load({
            url: '<?= site_url("pembelian_pelunasan_hutang/get_form") ?>',
            success: function(form, action){
                var r = Ext.util.JSON.decode(action.response.responseText);
                if(r.data.user_peruntukan === "0"){
                    Ext.getCmp('pph_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('pph_peruntukan_supermarket').show();
                    Ext.getCmp('pph_peruntukan_distribusi').hide();
                }else if(r.data.user_peruntukan === "1"){
                    Ext.getCmp('pph_peruntukan_distribusi').setValue(true);
                    Ext.getCmp('pph_peruntukan_supermarket').hide();
                    Ext.getCmp('pph_peruntukan_distribusi').show();
                }else{
                    Ext.getCmp('pph_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('pph_peruntukan_supermarket').show();
                    Ext.getCmp('pph_peruntukan_distribusi').show();
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
                        if (btn === 'ok' && de.errMsg === 'Session Expired') {
                            window.location = '<?= site_url("auth/login") ?>';
                        }
                    }
                });
            }
        });
        strpembelianpelunasanhutang.removeAll();
        strpphjenispembayaran.removeAll();
        strpphpembayaranlain2.removeAll();
    }
</script>
