<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>

<script type="text/javascript"> 
    
 // twin lokasi
    var strcb_sj_dist_lokasi = new Ext.data.ArrayStore({
        fields: ['kd_lokasi','nama_lokasi'],
        data : []
    });

    var strgrid_sj_dist_lokasi = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [{name: 'kd_lokasi', allowBlank: false, type: 'text'},
                {name: 'nama_lokasi', allowBlank: false, type: 'text'}
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("penjualan_sj_distribusi/search_lokasi") ?>',
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

    var searchgrid_sj_dist_lokasi = new Ext.app.SearchField({
        store: strgrid_sj_dist_lokasi,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgrid_sj_dist_lokasi'
    });

    var grid_sj_dist_lokasi = new Ext.grid.GridPanel({
        store: strgrid_sj_dist_lokasi,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
            header: 'Kode Lokasi',
            dataIndex: 'kd_lokasi',
            width: 100,
            sortable: true

        },{
            header: 'Nama Lokasi',
            dataIndex: 'nama_lokasi',
            width: 400,
            sortable: true
        }],

        tbar: new Ext.Toolbar({
            items: [searchgrid_sj_dist_lokasi]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgrid_sj_dist_lokasi,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('psjdist_kd_lokasi').setValue(sel[0].get('kd_lokasi'));
                    Ext.getCmp('id_cb_sj_dist_lokasi').setValue(sel[0].get('nama_lokasi'));
                    menu_sj_dist_lokasi.hide();
                }
            }
        }
    });

    var menu_sj_dist_lokasi = new Ext.menu.Menu();
    menu_sj_dist_lokasi.add(new Ext.Panel({
        title: 'Pilih Lokasi',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 600,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [grid_sj_dist_lokasi],
        buttons: [{
            text: 'Close',
            handler: function(){
                menu_sj_dist_lokasi.hide();
            }
        }]
    }));

    Ext.ux.TwinComb_sj_dist_lokasi = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgrid_sj_dist_lokasi.load();
            menu_sj_dist_lokasi.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menu_sj_dist_lokasi.on('hide', function(){
        var sf = Ext.getCmp('id_searchgrid_sj_dist_lokasi').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgrid_sj_dist_lokasi').setValue('');
            searchgrid_sj_dist_lokasi.onTrigger2Click();
        }
    });

    //end twin lokasi
    var cb_sj_dist_lokasi = new Ext.ux.TwinComb_sj_dist_lokasi({
        fieldLabel: 'Lokasi Pengambilan <span class="asterix">*</span>',
        id: 'id_cb_sj_dist_lokasi',
        store: strcb_sj_dist_lokasi,
        mode: 'local',
        valueField: 'kd_lokasi',
        displayField: 'nama_lokasi',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_lokasi',
        emptyText: 'Pilih Lokasi'

    });
   //end twin lokasi
    //Start Combo Pelanggan
var strcbpsjdistpelanggan = new Ext.data.ArrayStore({
        fields: ['kd_pelanggan'],
        data: []
    });

    var strgridpsjdistpelanggan = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_pelanggan', 'nama_pelanggan', 'tipe','nama_tipe', 'alamat_kirim', 'no_telp'],
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

    var searchgridpsjdistpelanggan = new Ext.app.SearchField({
        store: strgridpsjdistpelanggan,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridpsjdistpelanggan'
    });


    var gridpsjdistpelanggan = new Ext.grid.GridPanel({
        store: strgridpsjdistpelanggan,
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
                width: 120,
                sortable: true
            }, {
                header: 'Jenis Pelanggan',
                dataIndex: 'nama_tipe',
                width: 100,
                sortable: true
            },{
                header: 'Kode Tipe',
                dataIndex: 'tipe',
                width: 100,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridpsjdistpelanggan]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridpsjdistpelanggan,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('psjdist_kd_pelanggan').setValue(sel[0].get('kd_pelanggan'));
                    Ext.getCmp('id_cbpsjdistpelanggan').setValue(sel[0].get('nama_pelanggan'));
                    menupsjdistpelanggan.hide();
                }
            }
        }
    });

    var menupsjdistpelanggan = new Ext.menu.Menu();
    menupsjdistpelanggan.add(new Ext.Panel({
        title: 'Pilih Pelanggan',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridpsjdistpelanggan],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menupsjdistpelanggan.hide();
                }
            }]
    }));

    Ext.ux.TwinCombofppelanggan = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridpsjdistpelanggan.load();
            menupsjdistpelanggan.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menupsjdistpelanggan.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridpsjdistpelanggan').getValue();
        if (sf !== '') {
            Ext.getCmp('id_searchgridpsjdistpelanggan').setValue('');
            searchgridpsjdistpelanggan.onTrigger2Click();
        }
    });

    var cbpsjdistpelanggan = new Ext.ux.TwinCombofppelanggan({
        fieldLabel: 'Pelanggan <span class="asterix">*</span>',
        id: 'id_cbpsjdistpelanggan',
        store: strcbpsjdistpelanggan,
        mode: 'local',
        valueField: 'nama_pelanggan',
        displayField: 'nama_pelanggan',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'nama_pelanggan',
        emptyText: 'Pilih Pelanggan'
    });
    // End Combo Pelanggan
    // twin combo No Struk
    var strcb_psj_noso_dist = new Ext.data.ArrayStore({
        fields: ['no_so'],
        data : []
    });
	
    var strgrid_psj_noso_dist = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_so',
                'kd_member',
                'tgl_so',
                'kirim_so',
                'kirim_alamat_so',
                'kirim_telp_so',
                'rp_total',
                'rp_diskon',
                'rp_bank_charge',
                'rp_ongkos_kirim',
                'rp_ongkos_pasang',
                'rp_total_bayar',
                'kd_voucher',
                'qty_voucher',
                'no_open_saldo',
                'rp_diskon_tambahan',
                'keterangan',
                'rp_kurang_bayar'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("penjualan_sj_distribusi/search_so") ?>',
            method: 'POST'
        }),
        listeners: {
            
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
	
    var searchgrid_psj_noso_dist = new Ext.app.SearchField({
        store: strgrid_psj_noso_dist,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgrid_psj_noso_dist'
    });
	
	
    var grid_psj_noso_dist = new Ext.grid.GridPanel({
        store: strgrid_psj_noso_dist,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{header:'No SO',dataIndex:'no_so',width: 120,sortable: true},
            {header:'Tgl So',dataIndex:'tgl_so',width: 80,sortable: true},
            {header:'Kirim',dataIndex:'kirim_so',width: 150,sortable: true},
            {header:'Alamat',dataIndex:'kirim_alamat_so',width: 200,sortable: true},
            {header:'Telp',dataIndex:'kirim_telp_so',width: 100,sortable: true}
            
            ],
        tbar: new Ext.Toolbar({
            items: [searchgrid_psj_noso_dist]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgrid_psj_noso_dist,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                    Ext.getCmp('id_psj_noso_dist').setValue(sel[0].get('no_so'));
                    Ext.getCmp('id_tglso_dist').setValue(sel[0].get('tgl_so'));
                    
                    Ext.getCmp('id_pic_sj_dist').setValue(sel[0].get('kirim_so'));
                    Ext.getCmp('id_alm_penerima_sj_dist').setValue(sel[0].get('kirim_alamat_so'));
                    Ext.getCmp('id_telp_sj_dist').setValue(sel[0].get('kirim_telp_so'));
                    Ext.getCmp('id_sj_keterangan_dist').setValue(sel[0].get('keterangan'));
                    var vno_so=sel[0].get('no_so');
                    //storesalessjdist.reload({params:{no_so:vno_so}});
                                           
                    menu_fsj_noso_dist.hide();
                }
            }
        }
    });
	
    var menu_fsj_noso_dist = new Ext.menu.Menu();
    menu_fsj_noso_dist.add(new Ext.Panel({
        title: 'Pilih No SO',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [grid_psj_noso_dist],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menu_fsj_noso_dist.hide();
                }
            }]
    }));
    
    Ext.ux.TwinComboNoSoDist = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgrid_psj_noso_dist.load({
                params: {
                    kd_pelanggan: Ext.getCmp('psjdist_kd_pelanggan').getValue(),
                    }
            });
            menu_fsj_noso_dist.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menu_fsj_noso_dist.on('hide', function(){
        var sf = Ext.getCmp('id_searchgrid_psj_noso_dist').getValue();
        if( sf !== ''){
            Ext.getCmp('id_searchgrid_psj_noso_dist').setValue('');
            searchgrid_psj_noso_dist.onTrigger2Click();
        }
    });
	
    var cb_psj_noso_dist = new Ext.ux.TwinComboNoSoDist({
        fieldLabel: 'No.SO <span class="asterix">*</span>',
        id: 'id_psj_noso_dist',
        store: strcb_psj_noso_dist,
        mode: 'local',
        valueField: 'no_so',
        displayField: 'no_so',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'no_so',
        emptyText: 'Pilih No SO'
    });
    // END combo SO
    // twin combo Ekspedisi
    var strcb_sj_ekspedisi_dist = new Ext.data.ArrayStore({
        fields: ['nama_ekspedisi'],
        data : []
    });
	
    var strgrid_sj_ekspedisi_dist = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_ekspedisi', 'nama_ekspedisi'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("penjualan_sj_distribusi/search_ekspedisi") ?>',
            method: 'POST'
        }),
        listeners: {
			
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
	
    var searchgrid_sj_ekspedisi_dist = new Ext.app.SearchField({
        store: strgrid_sj_ekspedisi_dist,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgrid_sj_ekspedisi_dist'
    });
	
	
    var grid_sj_ekspedisi_dist = new Ext.grid.GridPanel({
        store: strgrid_sj_ekspedisi_dist,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'Kode Ekspedisi',
                dataIndex: 'kd_ekspedisi',
                width: 80,
                sortable: true		
            
            },{
                header: 'Nama Ekspedisi',
                dataIndex: 'nama_ekspedisi',
                width: 300,
                sortable: true        
            }],
        tbar: new Ext.Toolbar({
            items: [searchgrid_sj_ekspedisi_dist]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgrid_sj_ekspedisi_dist,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                    Ext.getCmp('id_kd_ekspedisi_dist').setValue(sel[0].get('kd_ekspedisi'));
                    Ext.getCmp('id_cbsj_ekspedisi_dist').setValue(sel[0].get('nama_ekspedisi'));
                           
                    menu_sj_ekspedisi_dist.hide();
                }
            }
        }
    });
	
    var menu_sj_ekspedisi_dist = new Ext.menu.Menu();
    menu_sj_ekspedisi_dist.add(new Ext.Panel({
        title: 'Pilih Ekspedisi',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [grid_sj_ekspedisi_dist],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menu_sj_ekspedisi_dist.hide();
                }
            }]
    }));
    
    Ext.ux.TwinComboEkspedisiDist = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgrid_sj_ekspedisi_dist.load();
            menu_sj_ekspedisi_dist.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menu_sj_ekspedisi_dist.on('hide', function(){
        var sf = Ext.getCmp('id_searchgrid_sj_ekspedisi_dist').getValue();
        if( sf !== ''){
            Ext.getCmp('id_searchgrid_sj_ekspedisi_dist').setValue('');
            searchgrid_sj_ekspedisi_dist.onTrigger2Click();
        }
    });
	
    var cbsj_ekspedisi_dist = new Ext.ux.TwinComboEkspedisiDist({
        fieldLabel: 'Ekspedisi <span class="asterix">*</span>',
        id: 'id_cbsj_ekspedisi_dist',
        store: strcb_sj_ekspedisi_dist,
        mode: 'local',
        valueField: 'nama_ekspedisi',
        displayField: 'nama_ekspedisi',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'nama_ekspedisi',
        emptyText: 'Pilih Ekspedisi'
    });
    
    
    //twin nodo
    var strcb_nodo_dist = new Ext.data.ArrayStore({
        fields: ['no_do'],
        data : []
    });
	
    var strgrid_nodo_dist = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_do',
                'tanggal',
                'pic_penerima',
                'alamat_penerima',
                'no_telp_penerima',
                'keterangan'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("penjualan_sj_distribusi/search_do") ?>',
            method: 'POST'
        }),
        listeners: {
            
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
	
    var searchgrid_nodo_dist = new Ext.app.SearchField({
        store: strgrid_nodo_dist,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgrid_nodo_dist'
    });
	
	
    var grid_nodo_dist = new Ext.grid.GridPanel({
        store: strgrid_nodo_dist,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{header:'no do',dataIndex:'no_do',width: 110,sortable: true},
            {header:'tanggal',dataIndex:'tanggal',width: 80,sortable: true},
            {header:'pic_penerima',dataIndex:'pic_penerima',width: 80,sortable: true},
            {header:'alamat_penerima',dataIndex:'alamat_penerima',width: 80,sortable: true},
            {header:'no_telp_penerima',dataIndex:'no_telp_penerima',width: 80,sortable: true},
            {header:'keterangan',dataIndex:'keterangan',width: 80,sortable: true}],
        tbar: new Ext.Toolbar({
            items: [searchgrid_nodo_dist]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgrid_nodo_dist,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                    Ext.getCmp('epsj_tgl_do_dist').setValue(sel[0].get('tanggal'));
                    Ext.getCmp('id_nodo_dist').setValue(sel[0].get('no_do'));
                    
//                    Ext.getCmp('id_pic_sj_dist').setValue(sel[0].get('pic_penerima'));
//                    Ext.getCmp('id_alm_penerima_sj_dist').setValue(sel[0].get('alamat_penerima'));
//                    Ext.getCmp('id_telp_sj_dist').setValue(sel[0].get('no_telp_penerima'));
//                    Ext.getCmp('id_sj_keterangan_dist').setValue(sel[0].get('keterangan'));
                                       
                    menu_nodo_dist.hide();
                }
            }
        }
    });
	
    var menu_nodo_dist = new Ext.menu.Menu();
    menu_nodo_dist.add(new Ext.Panel({
        title: 'Pilih No.DO',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [grid_nodo_dist],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menu_nodo_dist.hide();
                }
            }]
    }));
    
    Ext.ux.TwinComboNoDODist = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgrid_nodo_dist.load({
                params: {
                    kd_pelanggan: Ext.getCmp('psjdist_kd_pelanggan').getValue(),
                    no_so: Ext.getCmp('id_psj_noso_dist').getValue(),
                    }
            });
            menu_nodo_dist.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
//    menu_nodo_dist.on('hide', function(){
//        var sf = Ext.getCmp('id_searchgrid_nodo_dist').getValue();
//        if( sf !== ''){
//            Ext.getCmp('id_searchgrid_nodo_dist').setValue('');
//            searchgrid_nodo_dist.onTrigger2Click();
//        }
//    });
//	
//    var cb_nodo_sj_dist = new Ext.ux.TwinComboNoDODist({
//        fieldLabel: 'No.DO <span class="asterix">*</span>',
//        id: 'id_nodo_dist',
//        store: strcb_nodo_dist,
//        mode: 'local',
//        valueField: 'no_do',
//        displayField: 'no_do',
//        typeAhead: true,
//        triggerAction: 'all',
//        allowBlank: false,
//        editable: false,
//        anchor: '90%',
//        hiddenName: 'no_do',
//        emptyText: 'Pilih No.DO'
//    });
    
    var header_sj_dist=
        {layout: 'column',
        border: false,
        items: [{columnWidth: .4,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [{
                        xtype: 'textfield',
                        fieldLabel: 'No.SJ',
                        name: 'no_sj',
                        allowBlank: true,
                        readOnly:true,
                        fieldClass:'readonly-input',
                        id: 'id_no_sj_dist',
                        maxLength: 255,
                        anchor: '90%',
                        value:''
                    },cbpsjdistpelanggan,cb_psj_noso_dist,
                    //cb_nodo_sj_dist,
                    {xtype: 'datefield',
                        fieldLabel: 'Tanggal SO <span class="asterix">*</span>',
                        name: 'tgl_so',
                        id:'id_tglso_dist',
                        readOnly:true,
                        allowBlank:false,   
                        format:'d-M-Y',  
                        editable:false,                                         
                        anchor: '90%',
                         },cb_sj_dist_lokasi,
                          {
                        xtype:'hidden',
                        name:'kd_lokasi',
                        id:'psjdist_kd_lokasi'
                    },
                    {
                        xtype: 'textfield',
                        hidden : true,
                        name: 'kd_pelanggan',
                        readOnly:true,
                        fieldClass:'readonly-input',
                        id: 'psjdist_kd_pelanggan',                
                        anchor: '90%',
                        value:''
             }
                    
                ]
                
            },{columnWidth: .4,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [{
                        xtype: 'datefield',
                        fieldLabel: 'Tanggal <span class="asterix">*</span>',
                        name: 'tgl_sj',
                        id:'id_tglsj_dist',
                        allowBlank:false,   
                        format:'d-M-Y',  
                        editable:false,                                         
                        anchor: '90%',
                        value:new Date(),
                        maxValue: (new Date()).clearTime()
                    },
                    {
                        xtype:'hidden',
                        name:'kd_ekspedisi',
                        id:'id_kd_ekspedisi_dist'
                    },cbsj_ekspedisi_dist,
                    {
                        xtype: 'textfield',
                        fieldLabel: 'No.Kendaraan <span class="asterix">*</span>',
                        name: 'no_kendaraan',
                        allowBlank: false,
                        //                        readOnly:true,
                        //                        fieldClass:'readonly-input',
                        id: 'id_no_kendaraan_dist',
                        maxLength: 255,
                        anchor: '90%'
                        
                    },{
                        xtype: 'textfield',
                        fieldLabel: 'Sopir <span class="asterix">*</span>',
                        name: 'sopir',
                        allowBlank: false,
                        //                        readOnly:true,
                        //                        fieldClass:'readonly-input',
                        id: 'id_sopir_dist',
                        maxLength: 255,
                        anchor: '90%'
                        
                    }]
            }]
    };
    //twin produk
    var strcbproduksj_dist = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data : []
    });
	
    var strgridproduksj_dist = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_produk','nama_produk','qtydo','nm_satuan', 'qty_sj', 'qty_oh'],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("penjualan_sj_distribusi/search_produk_nodo") ?>',
            method: 'POST'
        }),
        listeners: {
			
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });	
	
    var searchFieldbarangsj_dist = new Ext.app.SearchField({
        width: 220,
        id: 'search_query',
        store: strgridproduksj_dist
    });
	
    searchFieldbarangsj_dist.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';
			
            // Get the value of search field
            var fid = Ext.getCmp('id_nodo_dist').getValue();
            var o = { start: 0, no_do: fid };
			
            this.store.baseParams = this.store.baseParams || {};
            this.store.baseParams[this.paramName] = '';
            this.store.reload({
                params : o
            });
            this.triggers[0].hide();
            this.hasSearch = false;
        }
    };
	
    searchFieldbarangsj_dist.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }
	 
        // Get the value of search field
        var fid = Ext.getCmp('id_nodo_dist').getValue();
        var o = { start: 0, no_do: fid };
	 
        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params:o});
        this.hasSearch = true;
        this.triggers[0].show();
    };
	
    // top toolbar
    var tbsearchbarangsj_dist = new Ext.Toolbar({
        items: [searchFieldbarangsj_dist]
    });
	
    var gridproduksj_dist = new Ext.grid.GridPanel({
        store: strgridproduksj_dist,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'Kode Produk',
                dataIndex: 'kd_produk',
                width: 100,
                sortable: true			
            
            },{
                header: 'Nama Produk',
                dataIndex: 'nama_produk',
                width: 400,
                sortable: true        
            },{
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 80			         
            },{
                header: 'Qty',
                dataIndex: 'qtydo',
                width: 80,
                sortable: true
            },{
                header: 'Stok OH',
                dataIndex: 'qty_oh',
                width: 80,
                sortable: true
            }],
        tbar:tbsearchbarangsj_dist,
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {		
                    
                    var _ada = false;
                                
                    storesalessjdist.each(function(record){
                        if((record.get('kd_produk') === sel[0].get('kd_produk')) && (record.get('no_do') === Ext.getCmp('id_nodo_dist').getValue())){
                            _ada = true;
                        }
                    });

                    if (_ada){
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Produk Berdasarkan No DO sudah pernah dipilih',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK,
                            fn: function(btn){
                                if (btn === 'ok') {
                                    Ext.getCmp('epsj_kd_produk_dist').reset();
                                }
                            }                            
                        });
                        Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                        Ext.getCmp('epsj_kd_produk_dist').focus();	
                        return;
                    }
                    
                    Ext.getCmp('epsj_kd_produk_dist').setValue(sel[0].get('kd_produk'));
                    Ext.getCmp('epsj_nama_produk_dist').setValue(sel[0].get('nama_produk'));
                    Ext.getCmp('epsj_total_qty_oh_dist').setValue(sel[0].get('qty_oh'));
                    Ext.getCmp('epsj_qty_do_dist').setValue(sel[0].get('qtydo')); 
                    Ext.getCmp('epsj_qty_sj_dist').setValue(sel[0].get('qty_sj'));
                    Ext.getCmp('epsj_qty_dist').setValue('0'); 
                    Ext.getCmp('epsj_nm_satuan_dist').setValue(sel[0].get('nm_satuan'));     
                    Ext.getCmp('epsj_sub_dist').focus(); 
                    menuproduksj_dist.hide();
                }
            }
        }
    });
	
    var menuproduksj_dist = new Ext.menu.Menu();
    menuproduksj_dist.add(new Ext.Panel({
        title: 'Pilih Barang',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 600,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridproduksj_dist],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menuproduksj_dist.hide();
                }
            }]
    }));
    
    Ext.ux.TwinComboproproduk_sjdist = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            if(Ext.getCmp('id_nodo_dist').getValue() === ''){
                Ext.Msg.show({
                    title: 'Error',
                    msg: 'Silahkan pilih No DO terlebih dulu',
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK			               
                });
                return;				
            }
            //load store grid
            strgridproduksj_dist.load({
                params: {
                    no_do: Ext.getCmp('id_nodo_dist').getValue()                                 
                }
            });
            menuproduksj_dist.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    // twin subblok-------------

    var strcbkdsubblokpro_dist_sj = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['sub', 'nama_sub'],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_receive_order/get_sub_blok") ?>',
            method: 'POST'
        }),
        listeners: {
			
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
	
    var strgridsubblok_sj_dist = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [
                'sub',
                'nama_sub',
                'kd_sub_blok', 
                'kd_blok',
                'kd_lokasi',
                'nama_lokasi',
                'nama_blok',
                'nama_sub_blok',
                'kapasitas',
                'qty_oh'
            ],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("penjualan_sj_distribusi/get_rows_lokasi") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
	
    // search field
    var searchgridprosubblok_sj_dist = new Ext.app.SearchField({
        store: strgridsubblok_sj_dist,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        id: 'idsearchgridprosubblok_sj_dist'
    });
	
    // top toolbar
    var tbgridprosubblok_sj_dist = new Ext.Toolbar({
        items: [searchgridprosubblok_sj_dist]
    });
	
    var gridprosubblok_sj_dist = new Ext.grid.GridPanel({
        store: strgridsubblok_sj_dist,
        stripeRows: true,
        frame: true,
        border:true,
        tbar: tbgridprosubblok_sj_dist,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridsubblok_sj_dist,
            displayInfo: true
        }),
        columns: [{
                dataIndex: 'kd_lokasi',
                hidden: true
            },{
                dataIndex: 'kd_blok',
                hidden: true
            },{
                dataIndex: 'kd_sub_blok',
                hidden: true
            },{
                header: 'Kode',
                dataIndex: 'sub',
                width: 90,
                sortable: true			
            
            },{
                header: 'Sub Blok Lokasi',
                dataIndex: 'nama_sub',
                width: 200,
                sortable: true         
            },{
                header: 'Qty OH',
                dataIndex: 'qty_oh',
                width: 80,
                sortable: true         
            }],
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('epsj_sub_dist').setValue(sel[0].get('sub'));
                    Ext.getCmp('epsj_nama_sub_dist').setValue(sel[0].get('nama_sub'));
                    Ext.getCmp('epsj_qty_oh_dist').setValue(sel[0].get('qty_oh'));
                    Ext.getCmp('epsj_qty_dist').focus();
                    menusubblok_sj_dist.hide();
                }
            }
        }
    });
	
    var menusubblok_sj_dist = new Ext.menu.Menu();
    menusubblok_sj_dist.add(new Ext.Panel({
        title: 'Pilih Sub Blok Lokasi',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 350,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridprosubblok_sj_dist],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menusubblok_sj_dist.hide();
                }
            }]
    }));
    
    Ext.ux.TwinComboSubBlokSjDist = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgridsubblok_sj_dist.setBaseParam('kd_produk',Ext.getCmp('epsj_kd_produk_dist').getValue());
            
            strgridsubblok_sj_dist.load({
                params:{
                    kd_produk: Ext.getCmp('epsj_kd_produk_dist').getValue(),
                    kd_lokasi: Ext.getCmp('psjdist_kd_lokasi').getValue()
                }
            });
            menusubblok_sj_dist.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
    //-------grid---------------------------------------------------	      
    var storesalessjdist= new Ext.data.Store({  
        autoSave:false,
        reader: new Ext.data.JsonReader({
            fields: [                
                {name: 'kd_produk', allowBlank: false, type: 'text'},
                {name: 'no_do', allowBlank: false, type: 'text'},
                {name: 'nama_produk', allowBlank: false, type: 'text'},  
                {name: 'qtydo', allowBlank: false, type: 'int'},
                {name: 'qty', allowBlank: false, type: 'int'},
                {name: 'satuan', allowBlank: false, type: 'text'},
                //                {name: 'sub', allowBlank: false, type: 'text'}	,
                //                {name: 'nama_sub', allowBlank: false, type: 'text'},
                //                {name: 'rp_satuan', allowBlank: false, type: 'int'},
                //                {name: 'rp_diskon', allowBlank: false, type: 'int'},
                //                {name: 'rp_total', allowBlank: false, type: 'int'},
                {name: 'keterangan', allowBlank: false, type: 'text'}
                
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        writer: new Ext.data.JsonWriter(
        {
            encode: true,
            writeAllFields: true
        })
//        proxy: new Ext.data.HttpProxy({
//            url: '<?= site_url("penjualan_sj_distribusi/search_produk_nofaktur") ?>',
//            method: 'POST'
//        }),
//        listeners: {
//			
//            loadexception: function(event, options, response, error){
//                var err = Ext.util.JSON.decode(response.responseText);
//                if (err.errMsg === 'Session Expired') {
//                    session_expired(err.errMsg);
//                }
//            }
//        }
    });       
    
    var editorsalessjdist = new Ext.ux.grid.RowEditor({
        saveText: 'Update'		
    });    
    
    var strcbkdsubblokpro_dist = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['sub', 'nama_sub'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_receive_order/get_sub_blok") ?>',
            method: 'POST'
        }),
        listeners: {
			
            loadexception: function(event, options, response, error){
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg === 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });
    
    
    
    var gridsalessjdist=new Ext.grid.GridPanel({
        store: storesalessjdist,
        stripeRows: true,
        height: 200,
        frame: true,        
        border:true,
        plugins:[editorsalessjdist],
        columns: [{
                header: 'NO DOD',
                dataIndex: 'no_do',
                width: 110,
                editor: new Ext.ux.TwinComboNoDODist({
                    id: 'id_nodo_dist',
                    store: strcb_nodo_dist,
                    mode: 'local',
                    valueField: 'no_do',
                    displayField: 'no_do',
                    typeAhead: true,
                    triggerAction: 'all',
                    // allowBlank: false,
                    editable: false,
                    hiddenName: 'no_do',
                    emptyText: 'Pilih No DO'
				
                })			
            
            },{
                header: 'Tanggal DO',
                dataIndex: 'tgl_do',
                width: 90,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epsj_tgl_do_dist',
                    fieldClass: 'readonly-text'
                })
            },{
                header: 'Kode produk',
                dataIndex: 'kd_produk',
                width: 110,
                editor: new Ext.ux.TwinComboproproduk_sjdist({
                    id: 'epsj_kd_produk_dist',
                    store: strcbproduksj_dist,
                    mode: 'local',
                    valueField: 'kd_produk',
                    displayField: 'kd_produk',
                    typeAhead: true,
                    triggerAction: 'all',
                    // allowBlank: false,
                    editable: false,
                    hiddenName: 'kd_produk',
                    emptyText: 'Pilih produk'
				
                })			
            
            },{
                header: 'Nama Barang',
                dataIndex: 'nama_produk',
                width: 320,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epsj_nama_produk_dist'
                })
            },{
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 80,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epsj_nm_satuan_dist'
                })
            },{
                header: 'Stok OH',
                dataIndex: 'total_qty_oh',
                width: 100,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epsj_total_qty_oh_dist'
                })
            },{
                header: 'Stok Di Sub Blok',
                dataIndex: 'qty_oh',
                width: 120,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epsj_qty_oh_dist'
                })
            },{
                header: 'Qty DO',
                dataIndex: 'qtydo',
                width: 60,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epsj_qty_do_dist'
                })
            },{
                header: 'Qty SJ',
                dataIndex: 'qty_sj',
                width: 60,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epsj_qty_sj_dist'
                })
            },{
                xtype: 'numbercolumn',
                header: 'Qty',
                dataIndex: 'qty',           
                width: 60,
                align: 'right',
                sortable: true,
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'epsj_qty_dist',
                    selectOnFocus:true,
                    listeners:{
                        'change': function(){
                            if(this.getValue() === ''){
                                this.setValue('0');
                                }
                            
                            if(Ext.getCmp('epsj_qty_do_dist').getValue() === ''){                                
                                Ext.getCmp('epsj_qty_do_dist').setValue('0');                                
                            }
                            
                            if(Ext.getCmp('epsj_qty_sj_dist').getValue() === ''){                                
                                Ext.getCmp('epsj_qty_sj_dist').setValue('0');                                
                            }
                            
                            if(this.getValue() > (Ext.getCmp('epsj_qty_do_dist').getValue() - Ext.getCmp('epsj_qty_sj_dist').getValue())){
                                Ext.Msg.show({
                                    title: 'Error',
                                    msg: 'Quantity Melebihi Quantity DO !!',
                                    modal: true,
                                    icon: Ext.Msg.ERROR,
                                    buttons: Ext.Msg.OK			               
                                });
                                this.setValue('0');
                            }
                            
                            
                            if((this.getValue() > Ext.getCmp('epsj_total_qty_oh_dist').getValue()) || (this.getValue() > Ext.getCmp('epsj_qty_oh_dist').getValue())){
                                Ext.Msg.show({
                                    title: 'Error',
                                    msg: 'Quantity Melebihi Quantity Stok !!',
                                    modal: true,
                                    icon: Ext.Msg.ERROR,
                                    buttons: Ext.Msg.OK			               
                                });
                                this.setValue('0');
                            }
                           
                        }
                    }
                }
            },{
                header: 'Kode Sub Blok',
                dataIndex: 'sub',
                width: 100,
                editor: new Ext.ux.TwinComboSubBlokSjDist({
                    id: 'epsj_sub_dist',
                    store: strcbkdsubblokpro_dist,
                    valueField: 'sub',
                    displayField: 'sub',
                    typeAhead: true,
                    triggerAction: 'all',
                    editable: false,
                    hiddenName: 'sub',
                    emptyText: 'Pilih Sub Blok',
                    listeners: {
                        'expand': function(){
                            strcbkdsubblokpro_dist.load();
                        }
                    }
                })			
            },{
                header: 'Sub Blok',
                dataIndex: 'nama_sub',
                width: 200,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'epsj_nama_sub_dist'
                })
            },{
                header: 'Keterangan',
                dataIndex: 'keterangan',
                width: 200,
                editor: new Ext.form.TextField({
                    //                readOnly: true,
                    id: 'epsj_keterangan_dist'
                })
            }],tbar: [{
                icon: BASE_ICONS + 'add.png',
                text: 'Add',
                handler: function(){
                    if(Ext.getCmp('id_psj_noso_dist').getValue() === ''){
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Silahkan pilih No SO terlebih dulu',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK			               
                        });
                        return;
                    }
                    var rowpembelianreceiveorder = new gridsalessjdist.store.recordType({
                        no_do:'',
                        kd_produk : '',
                        qty: ''
                    });                
                    editorsalessjdist.stopEditing();
                    storesalessjdist.insert(0, rowpembelianreceiveorder);
                    gridsalessjdist.getView().refresh();
                    gridsalessjdist.getSelectionModel().selectRow(0);
                    editorsalessjdist.startEditing(0);
                }
            },{
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                disabled: true,
                handler: function(){
                    editorsalessjdist.stopEditing();
                    var s = gridsalessjdist.getSelectionModel().getSelections();
                    for(var i = 0, r; r = s[i]; i++){
                        storesalessjdist.remove(r);
                    }
                }
            }]
    });

    gridsalessjdist.getSelectionModel().on('selectionchange', function(sm){
        gridsalessjdist.removeBtn.setDisabled(sm.getCount() < 1);
    });
    
    //FORM PANEL 
    var penjualansj= new Ext.FormPanel({
        id: 'suratjalandistribusi',
        border: false,
        frame: true,
        autoScroll:true,     
        monitorValid: true,       
        bodyStyle:'padding-right:20px;',        
        labelWidth: 130,
        items:[{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },                  
                items: [header_sj_dist]},
            gridsalessjdist,{
                layout: 'column',
                border: false,
                items: [{
                        columnWidth: .4,
                        style:'margin:6px 3px 0 0;',
                        layout: 'form', 
                        labelWidth: 120,                   
                        items: [{
                                xtype: 'textfield',
                                fieldLabel: 'PIC Penerima',
                                name: 'pic_terima',
                                //                                allowBlank: false,          
                                readOnly:true,
                                //                                fieldClass:'readonly-input',
                                id: 'id_pic_sj_dist',
                                maxLength: 255,
                                anchor: '90%',
                                value:''
                            },
                            {
                                xtype: 'textarea',
                                fieldLabel: 'Alamat Penerima',
                                name: 'alm_penerima',                                    
                                id: 'id_alm_penerima_sj_dist',   
                                readOnly:true,
                                //                                allowBlank: false,
                                width: 300,
                                anchor: '90%'
                            },
                            {
                                xtype: 'textfield',
                                fieldLabel: 'Telepon Penerima',
                                name: 'telp_terima',
                                //                                allowBlank: false,    
                                readOnly:true,
                                //                                fieldClass:'readonly-input',
                                id: 'id_telp_sj_dist',
                                maxLength: 255,
                                anchor: '90%',
                                value:''
                            }]
                    },{
                        columnWidth: .4,
                        style:'margin:6px 3px 0 0;',
                        layout: 'form', 
                        labelWidth: 120,                   
                        items: [{
                                xtype: 'textarea',
                                fieldLabel: 'Keterangan',
                                name: 'hketerangan',
                                allowBlank: false,
                                readOnly:false,
                                //                        fieldClass:'readonly-input',
                                id: 'id_sj_keterangan_dist',
                                maxLength: 255,
                                anchor: '90%',
                                value:''
                            }]}
                ]
            }
        ],
        buttons: [{
                text: 'Save',
                formBind: true, 
                handler: function(){
                    var qty_temp = 0;
                    var kd_produk_temp ='';
                    var sub_blok_temp	= '';
                    var is_validasi = true;
                    storesalessjdist.sort("kd_produk");
                    storesalessjdist.each(function(node){
                        var kd_produk = node.data.kd_produk;
                        var kd_sub_blok = node.data.sub;
                        var qty = parseInt(node.data.qty);
                        var qty_oh = parseInt(node.data.qty_oh);
                        if(kd_produk_temp === kd_produk &&  sub_blok_temp ===  kd_sub_blok ) {
        			var x = parseInt(qty_temp - qty);
                                qty_temp = parseInt(qty_temp - qty);
                                console.log(qty_temp);
                                console.log(x);
                             
                                if (qty_temp < 0){
                                    is_validasi= false;
                                   }
                        }else{
                                kd_produk_temp = kd_produk;
                                qty_temp = parseInt(qty_oh);
                                console.log('testtttt ='+ qty_temp);
                                sub_blok_temp = kd_sub_blok;	

                        }
                    });
                    
                   if(!is_validasi){
                        Ext.Msg.show({
                                        title: 'Error',
                                        msg: 'Qty tidak boleh melebihi Qty OH!',
                                        modal: true,
                                        icon: Ext.Msg.ERROR,
                                        buttons: Ext.Msg.OK
                                        });
                         Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000');
                         return;
                   }
                    var detaildo = new Array();              
                    storesalessjdist.each(function(node){
                        detaildo.push(node.data);
                    });
                    Ext.getCmp('suratjalandistribusi').getForm().submit({
                        url: '<?= site_url("penjualan_sj_distribusi/update_row") ?>',
                        scope: this,
                        params: {
                            data: Ext.util.JSON.encode(detaildo)
                        },
                        waitMsg: 'Saving Data...',
                        success: function(form, action){
                            var r = Ext.util.JSON.decode(action.response.responseText);
                            Ext.Msg.show({
                                title: 'Success',
                                msg: 'Form submitted successfully',
                                modal: true,
                                icon: Ext.Msg.INFO,
                                buttons: Ext.Msg.OK,
                                fn: function(btn){
                                    if (btn === 'ok') {
                                        winsuratjalandistribusi.show();
                                        Ext.getDom('suratjalandistribusiprint').src = r.printUrl;
                                    }
                                }
                            });                     
                        
                            clearsalessj_dist();                       
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
                                    if (btn === 'ok' && fe.errMsg === 'Session Expired') {
                                        window.location = '<?= site_url("auth/login") ?>';
                                    }
                                }
                            });
                        
                        }                   
                    }); 
                }
            },
            {
                text: 'Reset', handler: function(){clearsalessj_dist();}
            }],
        listeners:{
            afterrender:function(){
                
                this.getForm().load({
                    url: '<?= site_url("penjualan_sj_distribusi/get_form") ?>',
                    failure: function(form, action){
                        var de = Ext.util.JSON.decode(action.response.responseText);
                        Ext.Msg.show({
                            title: 'Error',
                            msg: de.errMsg,
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK,
                            fn: function(btn){
                                if (btn === 'ok' && de.errMsg === 'Session Expired') {
                                    window.location = '<?= site_url("auth/login") ?>';
                                }
                            }
                        });
                    }
                });
            }
        }
    });
    
    var winsuratjalandistribusi = new Ext.Window({
        id: 'id_winsuratjalandistribusi',
        title: 'Print Surat Jalan',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="suratjalandistribusiprint" src=""></iframe>'
    });

    function clearsalessj_dist(){
        Ext.getCmp('suratjalandistribusi').getForm().reset();
        Ext.getCmp('suratjalandistribusi').getForm().load({
            url: '<?= site_url("penjualan_sj_distibusi/get_form") ?>',
            failure: function(form, action){
                var de = Ext.util.JSON.decode(action.response.responseText);
                Ext.Msg.show({
                    title: 'Error',
                    msg: de.errMsg,
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK,
                    fn: function(btn){
                        if (btn === 'ok' && de.errMsg === 'Session Expired') {
                            window.location = '<?= site_url("auth/login") ?>';
                        }
                    }
                });
            }
        });
        storesalessjdist.removeAll();
    }
</script>
