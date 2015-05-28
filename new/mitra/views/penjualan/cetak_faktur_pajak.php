<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
 var strcbcfpajakpelanggan = new Ext.data.ArrayStore({
        fields: ['kd_pelanggan'],
        data: []
    });

    var strgridcfpajakpelanggan = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_pelanggan', 'nama_pelanggan', 'tipe','nama_tipe', 'alamat_kirim', 'no_telp', 'nama_sales', 'kd_sales'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("penjualan_distribusi/search_pelanggan") ?>',
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

    var searchgridcfpajakpelanggan = new Ext.app.SearchField({
        store: strgridcfpajakpelanggan,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridcfpajakpelanggan'
    });


    var gridcfpajakpelanggan = new Ext.grid.GridPanel({
        store: strgridcfpajakpelanggan,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'Kode Pelanggan',
                dataIndex: 'kd_pelanggan',
                width: 80,
                sortable: true
            }, {
                header: 'Nama Pelanggan',
                dataIndex: 'nama_pelanggan',
                width: 150,
                sortable: true
            },{
                header: 'Jenis Pelanggan',
                dataIndex: 'nama_tipe',
                width: 100,
                sortable: true
            }, {
                header: 'Kode tipe',
                dataIndex: 'tipe',
                width: 100,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridcfpajakpelanggan]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridcfpajakpelanggan,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cbcfpajakpelanggan').setValue(sel[0].get('kd_pelanggan'));
                    Ext.getCmp('id_nama_cbcfpajakpelanggan').setValue(sel[0].get('nama_pelanggan'));
                    menucfpajakpelanggan.hide();
                }
            }
        }
    });

    var menucfpajakpelanggan = new Ext.menu.Menu();
    menucfpajakpelanggan.add(new Ext.Panel({
        title: 'Pilih Pelanggan',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridcfpajakpelanggan],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menucfpajakpelanggan.hide();
                }
            }]
    }));

    Ext.ux.TwinCombocfpajakpelanggan = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridcfpajakpelanggan.load();
            menucfpajakpelanggan.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menucfpajakpelanggan.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridcfpajakpelanggan').getValue();
        if (sf !== '') {
            Ext.getCmp('id_searchgridcfpajakpelanggan').setValue('');
            searchgridcfpajakpelanggan.onTrigger2Click();
        }
    });

    var cbcfpajakpelanggan = new Ext.ux.TwinCombocfpajakpelanggan({
        fieldLabel: 'Nama Pelanggan <span class="asterix">*</span>',
        id: 'id_nama_cbcfpajakpelanggan',
        store: strcbcfpajakpelanggan,
        mode: 'local',
        valueField: 'nama_pelanggan',
        displayField: 'nama_pelanggan',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        width:180,
        hiddenName: 'nama_pelanggan',
        emptyText: 'Pilih Pelanggan'
    });
 // START GRID Cetak Faktur Penjualan
    var strcetakfakturpajak = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'no_faktur_pajak',
                'tgl_faktur_pajak',
                'kd_pelanggan', 
                'no_faktur',
                'no_bayar_uang_muka',
                'nama_pelanggan'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("cetak_faktur_pajak/get_rows") ?>',
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
    strcetakfakturpajak.on('load', function(){
        Ext.getCmp('idsearch_cetak_faktur_pajak').focus();
    });
    // search field
    var search_cetak_faktur_pajak = new Ext.app.SearchField({
        store: strcetakfakturpajak,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        emptyText : 'Cari No Faktur Pajak',
        id: 'idsearch_cetak_faktur_pajak'
    });

    // top toolbar
    var tb_cetak_faktur_pajak = new Ext.Toolbar({
        items: [search_cetak_faktur_pajak]
    });
     search_cetak_faktur_pajak.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';
			
            // Get the value of search field
            var fid = Ext.getCmp('iop_kd_supplier').getValue();
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
	
    search_cetak_faktur_pajak.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }
	 
        // Get the value of search field
        var fid = Ext.getCmp('iop_kd_supplier').getValue();
        var o = { start: 0, kd_supplier: fid };
	 
        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params:o});
        this.hasSearch = true;
        this.triggers[0].show();
    };
    // checkbox grid
    var smgridcetakfakturpajak = new Ext.grid.CheckboxSelectionModel();
   // var smgridDetailCFPprint = new Ext.grid.CheckboxSelectionModel();

   
    strcetakfakturpajak.on('load', function() {
       // strcetakfakturpajakdetail.removeAll();
    });

    var gridcetakfakturpajak = new Ext.grid.EditorGridPanel({
        id: 'gridcetakfakturpajak',
        frame: true,
        border: true,
        stripeRows: true,
        sm: smgridcetakfakturpajak,
        store: strcetakfakturpajak,
        loadMask: true,
        style: 'margin:0 auto;',
        height: 320,
        // width: 550,
        columns: [{
                header: "No Faktur Pajak",
                dataIndex: 'no_faktur_pajak',
                // hidden: true,
                sortable: true,
                width: 120
            }, {
                header: "Tanggal Faktur",
                dataIndex: 'tgl_faktur_pajak',
                sortable: true,
                width: 100
            },{
                header: "Pelanggan",
                dataIndex: 'nama_pelanggan',
                sortable: true,
                width: 150
            }, {
                header: "No Faktur",
                dataIndex: 'no_faktur',
                sortable: true,
                width: 120
            }, {
                header: "No Bayar Uang Muka",
                dataIndex: 'no_bayar_uang_muka',
                sortable: true,
                format :'0,0',
                width: 180
            }],
        listeners: {
            'rowclick': function() {
//                var sm = gridcetakfakturpajak.getSelectionModel();
//                var sel = sm.getSelections();
//                gridDetailCFPprint.store.proxy.conn.url = '<?= site_url("cetak_faktur_penjualan/get_rows_detail") ?>/' + sel[0].get('no_faktur');
//                gridDetailCFPprint.store.reload();
            }
        },
        tbar: [tb_cetak_faktur_pajak],
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strcetakfakturpajak,
            displayInfo: true
        })
    });

 // twin combo no sales order
   var storecombocfpUangMuka = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_bayar', 'kd_jenis_bayar', 'rp_bayar', 'jumlah_uang'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("cetak_faktur_pajak/search_uang_muka") ?>',
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
    /**
     * deklarasi search grid uang muka
     */
    var searchgridCfpUangMuka = new Ext.app.SearchField({
        store: storecombocfpUangMuka,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_search_grid_uang_muka'
    });
   
    var gridCfpUangMuka = new Ext.grid.GridPanel({
        store: storecombocfpUangMuka,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'No Bayar',
                dataIndex: 'no_bayar',
                width: 120,
                sortable: true
            },  {xtype:'numbercolumn',
                header: 'Rp Bayar',
                dataIndex: 'rp_bayar',
                width: 150,
                format:'0,0',
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridCfpUangMuka]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: storecombocfpUangMuka,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cfp_uang_muka').setValue(sel[0].get('no_bayar'));
                    menuCfpUangMuka.hide();
                }
            }
        }
    });
    /**
     * deklarasi menu uang muka
     */
    var menuCfpUangMuka = new Ext.menu.Menu();
    menuCfpUangMuka.add(new Ext.Panel({
        title: 'Pilih Uang Muka',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 350,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridCfpUangMuka],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menuCfpUangMuka.hide();
                }
            }]
    }));
    /**
     * deklarasi twin combo uang muka
     * @returns {undefined} */
    Ext.ux.TwincombocfpUangMuka = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            storecombocfpUangMuka.load({
                params: {
                    kd_pelanggan: Ext.getCmp('id_cbcfpajakpelanggan').getValue(),
                    }
            });
            menuCfpUangMuka.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    var combocfpUangMuka = new Ext.ux.TwincombocfpUangMuka({
        fieldLabel: 'Uang Muka <span class="asterix">*</span>',
        id: 'id_cfp_uang_muka',
        store: storecombocfpUangMuka,
        mode: 'local',
        valueField: 'no_bayar',
        displayField: 'no_bayar',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        width: 180,
        hiddenName: 'no_bayar',
        emptyText: 'Pilih Uang Muka'
    });
    //end twincombo uang muka
    // Start Combo Jual
    var strcbCfpfakturjual = new Ext.data.ArrayStore({
        fields: ['no_faktur'],
        data : []
    });
	
    var strgridCfpfakturjual = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_faktur', 
                     'tgl_faktur', 
                     'rp_faktur', 
                     'rp_potongan',
                     'rp_faktur_net',
                     'rp_ppn',
                     'rp_dpp',
                     'rp_uang_muka',
                     'nama_npwp',
                     'no_npwp',
                     'alamat_npwp',
                     'kd_npwp'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("cetak_faktur_pajak/search_faktur_jual") ?>',
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
	
    strgridCfpfakturjual.on('load', function(){
        Ext.getCmp('id_searchgridCfpfakturjual').focus();
    });
	
    var searchgridCfpfakturjual = new Ext.app.SearchField({
        store: strgridCfpfakturjual,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridCfpfakturjual'
    });
	
	
    var gridCfpfakturjual = new Ext.grid.GridPanel({
        store: strgridCfpfakturjual,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'No Faktur',
                dataIndex: 'no_faktur',
                width: 120,
                sortable: true		
            
            },{
                header: 'Tanggal',
                dataIndex: 'tgl_faktur',
                width: 80,
                sortable: true         
            },{xtype: 'numbercolumn',
                header: 'Jumlah Faktur',
                dataIndex: 'rp_faktur',
                width: 100,
                format: '0,0',
                sortable: true          
            },{xtype: 'numbercolumn',
                header: 'Uang Muka',
                dataIndex: 'rp_uang_muka',
                width: 100,
                format: '0,0',
                sortable: true          
            },{xtype: 'numbercolumn',
                header: 'Faktur Net',
                dataIndex: 'rp_faktur_net',
                width: 100,
                format: '0,0',
                sortable: true          
            },{xtype: 'numbercolumn',
                header: 'Rp DPP',
                dataIndex: 'rp_dpp',
                width: 100,
                format: '0,0',
                sortable: true          
            },{xtype: 'numbercolumn',
                header: 'PPN',
                dataIndex: 'rp_ppn',
                width: 100,
                format: '0,0',
                sortable: true          
            },{
                dataIndex: 'nama_pelanggan',
                hidden: true         
            },{
                dataIndex: 'nama_npwp',
                hidden: true         
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridCfpfakturjual]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridCfpfakturjual,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                    Ext.getCmp('id_cbCfpfakturjual').setValue(sel[0].get('no_faktur'));
                    menuCfpfakturjual.hide();
                   //cleartotalfaktur();
                }
            }
        }
    });
	
    var menuCfpfakturjual = new Ext.menu.Menu();
    menuCfpfakturjual.add(new Ext.Panel({
        title: 'Pilih Faktur',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridCfpfakturjual],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menuCfpfakturjual.hide();
                }
            }]
    }));
    
    Ext.ux.TwincbCfpfakturjual = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgridCfpfakturjual.load({
                params: {
                    kd_pelanggan: Ext.getCmp('id_cbcfpajakpelanggan').getValue(),
                    }
            });
            menuCfpfakturjual.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menuCfpfakturjual.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridCfpfakturjual').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgridCfpfakturjual').setValue('');
            searchgridCfpfakturjual.onTrigger2Click();
        }
    });
	
    var cbCfpfakturjual = new Ext.ux.TwincbCfpfakturjual({
        fieldLabel: 'Faktur Jual <span class="asterix">*</span>',
        id: 'id_cbCfpfakturjual',
        store: strcbCfpfakturjual,
        mode: 'local',
        valueField: 'no_faktur',
        displayField: 'no_faktur',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        width:180,
        hiddenName: 'no_faktur',
        emptyText: 'Pilih Faktur',
        readOnly: true,
        fieldClass: 'readonly-input'
    });
    //End Combo Faktur Jual
    
 //Header Faktur Pajak Print
    var headercetakfakturpajak = {
        layout: 'column',
        border: false,
        buttonAlign: 'left',
        style: 'padding:5px',
        frame: true,
        items: [{
                xtype: 'compositefield',
                msgTarget: 'side',
                style: 'padding-top:10px',
                width: 900,
                fieldLabel: 'Pelanggan <span class="asterix">*</span>',
                items: [{
                                xtype: 'displayfield',
                                value: 'Nama Pelanggan <span class="asterix">*</span>',
                                //style: 'padding-left:30px',
                                width: 130
                            },cbcfpajakpelanggan,
                                {       xtype: 'textfield',
					name: 'kd_pelanggan',
					id: 'id_cbcfpajakpelanggan',
					maxLength: 255,
					anchor: '90%',
                                        hidden: true
                                }
                       ]
                    },{
                        xtype: 'compositefield',
                        msgTarget: 'side',
                        style: 'padding-top:10px',
                        width: 900,
                        fieldLabel: 'Faktur Uang Muka <span class="asterix">*</span>',
                        items: [{
                                xtype: 'radio',
                                name: 'radio_jenis_bayar',
                                id: 'id_check_uang_muka',
                                checked: true,
                                anchor: '90%', listeners: {
                                    check: function() {
                                        if (this.getValue()) {
                                            //Ext.Msg.alert('faktur');

                                            Ext.getCmp('id_cbCfpfakturjual').setReadOnly(true);
                                            Ext.getCmp('id_cbCfpfakturjual').reset();
                                            Ext.getCmp('id_cbCfpfakturjual').addClass('readonly-input');
                                            Ext.getCmp('id_cfp_uang_muka').setReadOnly(false);
                                            Ext.getCmp('id_cfp_uang_muka').removeClass('readonly-input');
                                            Ext.getCmp('id_cetak_faktur_pajak').disable();
                                        }
                                        else {
                                            //Ext.Msg.alert('uang muka');
                                            Ext.getCmp('id_cfp_uang_muka').setReadOnly(true);
                                            Ext.getCmp('id_cfp_uang_muka').reset();
                                            Ext.getCmp('id_cfp_uang_muka').addClass('readonly-input');
                                            Ext.getCmp('id_cbCfpfakturjual').setReadOnly(false);
                                            Ext.getCmp('id_cbCfpfakturjual').removeClass('readonly-input');
                                            Ext.getCmp('id_cetak_faktur_pajak').disable();
                                        }
                                    }
                                }
                            }, {
                                xtype: 'displayfield',
                                value: 'Uang Muka <span class="asterix">*</span>',
                                width: 105
                            }, combocfpUangMuka
                        ]
                    },{
                        xtype: 'compositefield',
                        msgTarget: 'side',
                        style: 'padding-top:10px',
                        width: 900,
                        fieldLabel: 'Faktur <span class="asterix">*</span>',
                        items: [{
                                xtype: 'radio',
                                //fieldLabel: 'Aktif <span class="asterix">*</span>',
                                name: 'radio_jenis_bayar',
                                id: 'id_check_faktur_jual',
                                anchor: '90%',
                                listeners: {
                                    check: function() {
                                        if (this.getValue()) {
                                            //Ext.Msg.alert('faktur');
                                            Ext.getCmp('id_cfp_uang_muka').setReadOnly(true);
                                            Ext.getCmp('id_cfp_uang_muka').addClass('readonly-input');
                                            Ext.getCmp('id_cbCfpfakturjual').setReadOnly(false);
                                            Ext.getCmp('id_cbCfpfakturjual').removeClass('readonly-input');
                                            Ext.getCmp('id_cetak_faktur_pajak').disable();
                                        }
                                        else {
                                            //Ext.Msg.alert('uang muka');
                                            Ext.getCmp('id_cbCfpfakturjual').setReadOnly(true);
                                            Ext.getCmp('id_cbCfpfakturjual').addClass('readonly-input');
                                            Ext.getCmp('id_cfp_uang_muka').setReadOnly(false);
                                            Ext.getCmp('id_cfp_uang_muka').removeClass('readonly-input');
                                            Ext.getCmp('id_cetak_faktur_pajak').disable();
                                        }
                                    }
                                }
                            }, {
                                xtype: 'displayfield',
                                value: 'Faktur Penjualan <span class="asterix">*</span>',
                                //style: 'padding-left:30px',
                                width: 105
                            }, cbCfpfakturjual
                        ]
                    }],buttons: [{
			text: 'Filter',
			formBind: true,
			handler: function () {
				gridcetakfakturpajak.store.load({
					params: {
						no_faktur: Ext.getCmp('id_cbCfpfakturjual').getValue(),
						no_bayar_uang_muka: Ext.getCmp('id_cfp_uang_muka').getValue(),
                                                kd_pelanggan: Ext.getCmp('id_cbcfpajakpelanggan').getValue(),
					}
				});
			}
		}]
    }

// Form Panel
    var cetakfakturpajak = new Ext.FormPanel({
        id: 'cetakfakturpajak',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },
                items: [headercetakfakturpajak]
            },gridcetakfakturpajak,
            //gridDetailCFPprint

        ],
        buttons: [{
                text: 'Cetak Pajak Faktur',
                id:'id_cetak_faktur_pajak',
                disable:true,
                width:100,
                handler: function() {
                    var sm = gridcetakfakturpajak.getSelectionModel();                
                    var sel = sm.getSelections(); 	
                    wincetakfakturpajakprint.show();
                    Ext.getDom('cetakfakturpajakprint').src = '<?= site_url("faktur_pajak/print_form") ?>'+'/'+sel[0].get('no_faktur_pajak');
                }
            }, {
                text: 'Cetak Pajak Uang Muka',
                width:150,
                handler: function() {
                    var sm = gridcetakfakturpajak.getSelectionModel();                
                    var sel = sm.getSelections(); 	
                    wincetakfakturpajakprint.show();
                    Ext.getDom('cetakfakturpajakprint').src = '<?= site_url("faktur_pajak/print_form_uang_muka") ?>'+'/'+sel[0].get('no_faktur_pajak');
                }
            }, {
                text: 'Reset',
                handler: function() {
                    clearcetakfakturpajakprint(); 
                }
            }]
    });
     var wincetakfakturpajakprint = new Ext.Window({
        id: 'id_wincetakfakturpajakprint',
	title: 'Faktur Pajak Form',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="cetakfakturpajakprint" src=""></iframe>'
    });
    function clearcetakfakturpajakprint(){
		Ext.getCmp('cetakfakturpajak').getForm().reset();
		strcetakfakturpajak.removeAll();
		
            }
</script>
