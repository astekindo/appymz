?<php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
// Start Combo Pelanggan
    var strcbfjpelanggan = new Ext.data.ArrayStore({
        fields: ['nama_pelanggan'],
        data : []
    });
	
    var strgridfjpelanggan = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_pelanggan', 'nama_pelanggan', 'top_dist'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("penjualan_distribusi/search_pelanggan") ?>',
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
	
    strgridfjpelanggan.on('load', function(){
        Ext.getCmp('id_searchgridfjpelanggan').focus();
    });
	
    var searchgridfjpelanggan = new Ext.app.SearchField({
        store: strgridfjpelanggan,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridfjpelanggan'
    });
	
	
    var gridfjpelanggan = new Ext.grid.GridPanel({
        store: strgridfjpelanggan,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'Kode Pelanggan',
                dataIndex: 'kd_pelanggan',
                width: 120,
                sortable: true		
            
            },{
                header: 'Nama Pelanggan',
                dataIndex: 'nama_pelanggan',
                width: 300,
                sortable: true         
            },{
                dataIndex: 'top_dist',
                hidden: true         
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridfjpelanggan]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridfjpelanggan,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                    var top = sel[0].get('top_dist');
                    Ext.getCmp('fj_nama_pelanggan').setValue(sel[0].get('nama_pelanggan'));
                    Ext.getCmp('id_cbfjpelanggan').setValue(sel[0].get('kd_pelanggan'));
                    Ext.getCmp('fj_hari').setValue(top);
                    Ext.getCmp('fj_tgl_jth_tempo').setValue(new Date().add(Date.DAY, parseInt(top)));
                    
                    strfakturpenjualan.removeAll();       
                    menufjpelanggan.hide();
                   cleartotalfaktur();
                }
            }
        }
    });
	
    var menufjpelanggan = new Ext.menu.Menu();
    menufjpelanggan.add(new Ext.Panel({
        title: 'Pilih Pelanggan',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridfjpelanggan],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menufjpelanggan.hide();
                }
            }]
    }));
    
    Ext.ux.TwinComboPelanggan = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgridfjpelanggan.load();
            menufjpelanggan.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menufjpelanggan.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridfjpelanggan').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgridfjpelanggan').setValue('');
            searchgridfjpelanggan.onTrigger2Click();
        }
    });
	
    var cbfjpelanggan = new Ext.ux.TwinComboPelanggan({
        fieldLabel: 'Pelanggan <span class="asterix">*</span>',
        id: 'id_cbfjpelanggan',
        store: strcbfjpelanggan,
        mode: 'local',
        valueField: 'kd_pelanggan',
        displayField: 'kd_pelanggan',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_pelanggan',
        emptyText: 'Pilih pelanggan'
    });
    //End Combo Pelanggan
    
    // twin combo No Struk
    var strcb_fj_noso_dist = new Ext.data.ArrayStore({
        fields: ['no_so'],
        data : []
    });
	
    var strgrid_fj_noso_dist = new Ext.data.Store({
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
            url: '<?= site_url("faktur_penjualan/search_do") ?>',
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
	
    var searchgrid_fj_noso_dist = new Ext.app.SearchField({
        store: strgrid_fj_noso_dist,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgrid_fj_noso_dist'
    });
	
	
    var grid_fj_noso_dist = new Ext.grid.GridPanel({
        store: strgrid_fj_noso_dist,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{header:'No SO',dataIndex:'no_so',width: 120,sortable: true},
//            {header:'Rp Kurang Bayar',dataIndex:'rp_kurang_bayar',width: 100,sortable: true},
//            {header:'Keterangan',dataIndex:'keterangan',width: 200,sortable: true},
            {header:'Tgl So',dataIndex:'tgl_so',width: 80,sortable: true},
            {header:'Kirim',dataIndex:'kirim_so',width: 150,sortable: true},
            {header:'Alamat',dataIndex:'kirim_alamat_so',width: 200,sortable: true},
            {header:'Telp',dataIndex:'kirim_telp_so',width: 100,sortable: true}
            
            ],
        tbar: new Ext.Toolbar({
            items: [searchgrid_fj_noso_dist]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgrid_fj_noso_dist,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                    Ext.getCmp('id_fj_noso_dist').setValue(sel[0].get('no_so'));
                    var vno_so=sel[0].get('no_so');
                    //storesalesdo_dist.reload({params:{no_so:vno_so}});
                    strgridfpuangmuka.load({
                        params: {
                            no_so: sel[0].get('no_so')
                        }
                    });                     
                    menu_fj_noso_dist.hide();
                }
            }
        }
    });
	
    var menu_fj_noso_dist = new Ext.menu.Menu();
    menu_fj_noso_dist.add(new Ext.Panel({
        title: 'Pilih No SO',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [grid_fj_noso_dist],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menu_fj_noso_dist.hide();
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
            strgrid_fj_noso_dist.load({
                params: {
                    kd_pelanggan: Ext.getCmp('id_cbfjpelanggan').getValue(),
                    }
            });
            menu_fj_noso_dist.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menu_fj_noso_dist.on('hide', function(){
        var sf = Ext.getCmp('id_searchgrid_fj_noso_dist').getValue();
        if( sf !== ''){
            Ext.getCmp('id_searchgrid_fj_noso_dist').setValue('');
            searchgrid_fj_noso_dist.onTrigger2Click();
        }
    });
	
    var cb_fj_noso_dist = new Ext.ux.TwinComboNoSoDist({
        fieldLabel: 'No SO <span class="asterix">*</span>',
        id: 'id_fj_noso_dist',
        store: strcb_fj_noso_dist,
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
    // End Combo Struk
     //Start Combo Uang Muka
var strcbfpuangmuka = new Ext.data.ArrayStore({
        fields: ['no_bayar'],
        data: []
    });

    var strgridfpuangmuka = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [{name: 'no_bayar', allowBlank: false, type: 'text'},
                         {name: 'no_so', allowBlank: false, type: 'text'},
                        {name: 'rp_uang_muka', allowBlank: false, type: 'int'},
                        {name: 'rp_uang_muka_terpakai', allowBlank: false, type: 'int'},
                        {name: 'uang_muka_sisa', allowBlank: false, type: 'int'},],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("faktur_penjualan/search_uang_muka") ?>',
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

    var searchgridfpuangmuka = new Ext.app.SearchField({
        store: strgridfpuangmuka,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridfpuangmuka'
    });

     var editoruangmuka= new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });
    var gridfpuangmuka = new Ext.grid.GridPanel({
        store: strgridfpuangmuka,
        height: 200,   
        stripeRows: true,
        frame: true,
        border: true,
        plugins: [editoruangmuka],
        tbar :[
        {
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                disabled: false,
                handler: function(){
                    editoruangmuka.stopEditing();
                    var s = gridfpuangmuka.getSelectionModel().getSelections();
                    for(var i = 0, r; r = s[i]; i++){
                        strgridfpuangmuka.remove(r);
                    }
                    var uangmuka = 0;
                    strgridfpuangmuka.each(function(node){			
                        uangmuka += (node.data.uang_muka_sisa);
                    });
                    var jumlah = Ext.getCmp('pfj_rp_jumlah').getValue();
                    uangmuka = Math.round(uangmuka);
                    var jumlah_net = jumlah - uangmuka;
                    jumlah_net = Math.round(jumlah_net);
                    var tagihan = jumlah_net - Ext.getCmp('pfj_cash_diskon').getValue();
                    tagihan = Math.round(tagihan);
                    var dpp = jumlah_net / 1.1;
                    dpp = Math.round(dpp);
                    var ppn = dpp * 0.1;
                    ppn = Math.round(ppn);

                    Ext.getCmp('pfj_uang_muka').setValue(uangmuka);
                    Ext.getCmp('pfj_total').setValue(jumlah_net);
                    Ext.getCmp('pfj_tagihan').setValue(tagihan);
                    Ext.getCmp('pfj_rp_ppn').setValue(ppn);
                    Ext.getCmp('pfj_rp_dpp').setValue(dpp);
                    			
                }
            }
        ],
        columns: [{
                header: 'No Bayar',
                dataIndex: 'no_bayar',
                width: 120,
                sortable: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'pfp_no_bayar'
                })
            }, {
                header: 'No SO',
                dataIndex: 'no_so',
                width: 120,
                sortable: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'pfp_no_so'
                })
            }, {
                xtype: 'numbercolumn',
                header: 'Uang Muka Awal',
                dataIndex: 'rp_uang_muka',
                width: 100,
                sortable: true,
                format : '0,0',
                editor: {
                    xtype: 'numberfield',
                    id:'pfp_uang_muka_awal',
                     readOnly: true
                }
            },{
                xtype: 'numbercolumn',
                header: 'Uang Muka Terpakai',
                dataIndex: 'rp_uang_muka_terpakai',
                width: 100,
                sortable: true,
                format :'0,0',
                editor: {
                    xtype: 'numberfield',
                    id:'pfp_uang_muka_terpakai',
                     readOnly: true
                }
            },{
                xtype: 'numbercolumn',
                header: 'Uang Muka',
                dataIndex: 'uang_muka_sisa',
                width: 100,
                sortable: true,
                format :'0,0',
                editor: {
                    xtype: 'numberfield',
                    id:'pfp_uang_muka_sisa',
                      listeners:{
                        'change': function(){
                            if(this.getValue() > (Ext.getCmp('pfp_uang_muka_awal').getValue() - Ext.getCmp('pfp_uang_muka_terpakai').getValue())){
                                Ext.Msg.show({
                                    title: 'Error',
                                    msg: 'Uang muka + uang muka terpakai tidak boleh lebih besar dari uang muka awal !!',
                                    modal: true,
                                    icon: Ext.Msg.ERROR,
                                    buttons: Ext.Msg.OK			               
                                });
                                this.setValue('0');
                            }
//                             var uang_muka = Ext.getCmp('pfj_uang_muka').getValue();
//                             var total_uangmuka = uang_muka + this.getValue();
//                             Ext.getCmp('pfj_uang_muka').setValue(total_uangmuka);
                        }
                    }
                }
            }],
        
        
        listeners: {
//            'rowdblclick': function() {
//                var sm = this.getSelectionModel();
//                var sel = sm.getSelections();
//                if (sel.length > 0) {
//                    Ext.getCmp('id_cbfpuangmuka').setValue(sel[0].get('rp_uang_muka'));
//                    menufpuangmuka.hide();
//                }
//            }
        }
    });
 
strgridfpuangmuka.on('load', function(){
        var uangmuka = 0;
        strgridfpuangmuka.each(function(node){			
            uangmuka += parseFloat(node.data.uang_muka_sisa);
            
        });
        var jumlah = Ext.getCmp('pfj_rp_jumlah').getValue();
        uangmuka = Math.round(uangmuka);
        var jumlah_net = jumlah - uangmuka;
        jumlah_net = Math.round(jumlah_net);
        var tagihan = jumlah_net - Ext.getCmp('pfj_cash_diskon').getValue();
        tagihan = Math.round(tagihan);
        var dpp = jumlah_net / 1.1;
        dpp = Math.round(dpp);
        var ppn = dpp * 0.1;
        ppn = Math.round(ppn);
        
        Ext.getCmp('pfj_uang_muka').setValue(uangmuka);
        Ext.getCmp('pfj_total').setValue(jumlah_net);
        Ext.getCmp('pfj_tagihan').setValue(tagihan);
        Ext.getCmp('pfj_rp_ppn').setValue(ppn);
        Ext.getCmp('pfj_rp_dpp').setValue(dpp);
     
    });
 strgridfpuangmuka.on('Update', function(){
        var uangmuka = 0;
        strgridfpuangmuka.each(function(node){			
            uangmuka += parseFloat(node.data.uang_muka_sisa);
            
        });
        var jumlah = Ext.getCmp('pfj_rp_jumlah').getValue();
        uangmuka = Math.round(uangmuka);
        var jumlah_net = jumlah - uangmuka;
        jumlah_net = Math.round(jumlah_net);
        var tagihan = jumlah_net - Ext.getCmp('pfj_cash_diskon').getValue();
        tagihan = Math.round(tagihan);
        var dpp = jumlah_net / 1.1;
        dpp = Math.round(dpp);
        var ppn = dpp * 0.1;
        ppn = Math.round(ppn);
        
        Ext.getCmp('pfj_uang_muka').setValue(uangmuka);
        Ext.getCmp('pfj_total').setValue(jumlah_net);
        Ext.getCmp('pfj_tagihan').setValue(tagihan);
        Ext.getCmp('pfj_rp_ppn').setValue(ppn);
        Ext.getCmp('pfj_rp_dpp').setValue(dpp);
    });
    
    var menufpuangmuka = new Ext.menu.Menu();
    menufpuangmuka.add(new Ext.Panel({
        title: 'Pilih Uang Muka',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridfpuangmuka],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menufpuangmuka.hide();
                }
            }]
    }));

    Ext.ux.TwinCombofpuangmuka = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridfpuangmuka.load({
                params: {
                    no_so: Ext.getCmp('id_fj_noso_dist').getValue(),
                    }
            });
            menufpuangmuka.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menufpuangmuka.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridfpuangmuka').getValue();
        if (sf !== '') {
            Ext.getCmp('id_searchgridfpuangmuka').setValue('');
            searchgridfpuangmuka.onTrigger2Click();
        }
    });

    var cbfpuangmuka = new Ext.ux.TwinCombofpuangmuka({
        fieldLabel: 'Uang Muka Sisa ',
        id: 'id_cbfpuangmuka',
        store: strcbfpuangmuka,
        mode: 'local',
        valueField: 'rp_uang_muka',
        displayField: 'rp_uang_muka',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: true,
        editable: false,
        anchor: '90%',
        hiddenName: 'rp_uang_muka',
        emptyText: 'Pilih Uang Muka'
    });
    // End Combo Uang Muka 
     //PKP PIHAK KE 3
  var strcbfppkppihak = new Ext.data.ArrayStore({
        fields: ['kd_pelanggan'],
        data: []
    });

    var strgridfppkp = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_pelanggan', 'nama_npwp', 'alamat_npwp','no_npwp','nama_pelanggan','kd_npwp'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("faktur_pajak/search_pelanggan_npwp") ?>',
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

    var searchgridfppkp = new Ext.app.SearchField({
        store: strgridfppkp,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridfppkp'
    });


    var gridfppkp = new Ext.grid.GridPanel({
        store: strgridfppkp,
        stripeRows: true,
        frame: true,
        border: true,
        columns: [{
                header: 'Nama Pelanggan',
                dataIndex: 'nama_pelanggan',
                width: 120,
                sortable: true
            }, {
                header: 'Nama NPWP',
                dataIndex: 'nama_npwp',
                width: 120,
                sortable: true
            },{
                header: 'No NPWP',
                dataIndex: 'no_npwp',
                width: 100,
                sortable: true
            },{
                header: 'Alamat NPWP',
                dataIndex: 'alamat_npwp',
                width: 100,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridfppkp]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridfppkp,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function() {
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_cbfppkppihak').setValue(sel[0].get('nama_npwp'));
                    Ext.getCmp('fj_kd_npwp').setValue(sel[0].get('kd_npwp'));
                    menufppkp.hide();
                }
            }
        }
    });

    var menufppkp = new Ext.menu.Menu();
    menufppkp.add(new Ext.Panel({
        title: 'Pilih Nama NPWP',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 450,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridfppkp],
        buttons: [{
                text: 'Close',
                handler: function() {
                    menufppkp.hide();
                }
            }]
    }));

    Ext.ux.TwinComboFpPkp = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function() {
            //load store grid
            strgridfppkp.load({
                params: {
                    kd_pelanggan: Ext.getCmp('id_cbfjpelanggan').getValue(),
                    }
            });
            menufppkp.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
                trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menufppkp.on('hide', function() {
        var sf = Ext.getCmp('id_searchgridfppkp').getValue();
        if (sf !== '') {
            Ext.getCmp('id_searchgridfppkp').setValue('');
            searchgridfppkp.onTrigger2Click();
        }
    });

    var cbfppkppihak = new Ext.ux.TwinComboFpPkp({
        fieldLabel: 'PKP Pihak Ke 3',
        id: 'id_cbfppkppihak',
        store: strcbfppkppihak,
        mode: 'local',
        valueField: 'nama_npwp',
        displayField: 'nama_npwp',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: true,
        editable: false,
        anchor: '90%',
        hiddenName: 'nama_npwp',
        emptyText: 'Pilih Nama NPWP'
    });
    //End PKP PIHAK KE 3
    // Header Faktur Penjualan
var headerfakturpenjualan = {
        layout: 'column',
        border: false,
        items: [{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [{
                        xtype: 'textfield',
                        fieldLabel: 'No Faktur',
                        name: 'no_faktur',
                        readOnly:true,
                        fieldClass:'readonly-input',
                        id: 'fj_no_faktur',                
                        anchor: '90%',
                        value:''
                    },cbfjpelanggan,
                    {
                        xtype: 'textfield',
                        fieldLabel: 'Nama Pelanggan',
                        name: 'nama_pelanggan',
                        readOnly:true,
                        fieldClass:'readonly-input',
                        id: 'fj_nama_pelanggan',                
                        anchor: '90%',
                        value:''
                    },cb_fj_noso_dist,cbfppkppihak,{
                        xtype: 'textfield',
                        name: 'kd_npwp',
                        readOnly:true,
                        fieldClass:'readonly-input',
                        id: 'fj_kd_npwp',                
                        width:300,
                        hidden : true,
                        value:'' 
                        
                    }]
            }, {
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 120,
                defaults: { labelSeparator: ''},
                items: [{
                        xtype: 'datefield',
                        fieldLabel: 'Tgl Faktur',
                        name: 'tgl_faktur',
                        id: 'fj_tgl_faktur', 
                        format: 'd-m-Y',
                        emptyText: 'Tgl Faktur',
                        value: new Date(), 
                        maxValue: (new Date()).clearTime() ,   
                        editable: false,           
                        anchor: '90%',
                        listeners: {
                            'change':function(){
                                var tgl_inv = this.getValue();
                                var top = Ext.getCmp('fj_hari').getValue();
                                Ext.getCmp('fj_tgl_jth_tempo').setValue(new Date(tgl_inv).add(Date.DAY, parseInt(top)));
                            }
                        }
                    },{
                        xtype: 'compositefield',
                        fieldLabel: 'Top',
                        combineErrors: false,
                        items: [{
                                name : 'top',
                                xtype: 'numberfield',
                                id: 'fj_hari',
                                fieldClass:'number',
                                selectOnFocus: true,
                                width: 60,
                                value:'0',
                                listeners: {
                                    'change':function(){
                                        var top = this.getValue();
                                        var tgl_inv = Ext.getCmp('fj_tgl_faktur').getValue();
                                        Ext.getCmp('fj_tgl_jth_tempo').setValue(new Date(tgl_inv).add(Date.DAY, parseInt(top)));
                                    }
                                }
								   
                            },{
                                xtype: 'displayfield',
                                value: 'Hari'
                            }]
                    },{
                        xtype: 'datefield',
                        fieldLabel: 'Jatuh Tempo',
                        name: 'tgl_jth_tempo',
                        id: 'fj_tgl_jth_tempo', 
                        readOnly: true, 
                        format: 'd-m-Y',
                        fieldClass:'readonly-input',
                        anchor: '90%'
                    },{
                        xtype: 'textfield',
                        fieldLabel: 'No Urut',
                        name: 'no_urut',
                        id: 'fj_urut', 
                        readOnly: true, 
                        fieldClass:'readonly-input',
                        anchor: '90%'
                    }]
            }]
    };
    
// checkbox grid
    var cbGridfj = new Ext.grid.CheckboxSelectionModel();
	
    var strcbfjnosj = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_sj', 'tanggal'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("faktur_penjualan/search_no_sj_by_pelanggan") ?>',
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
     strcbfjnosj.on('load', function(){
        Ext.getCmp('fjsearchnosj').focus();
    });
    var searchfjnosj = new Ext.app.SearchField({
        store: strcbfjnosj,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
            },
        
        width: 220,
        id: 'fjsearchnosj'
    });
    
    var tbfjnosj = new Ext.Toolbar({
        items: [searchfjnosj]
    });

      searchfjnosj.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';
			
            // Get the value of search field
            var fid = Ext.getCmp('id_cbfjpelanggan').getValue();
            var o = { start: 0, kd_pelanggan: fid };
			
            this.store.baseParams = this.store.baseParams || {};
            this.store.baseParams[this.paramName] = '';
            this.store.reload({
                params : o
            });
            this.triggers[0].hide();
            this.hasSearch = false;
        }
    };
	
    searchfjnosj.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }
	 
        // Get the value of search field
        var fid = Ext.getCmp('id_cbfjpelanggan').getValue();
        var o = { start: 0, kd_pelanggan: fid };
	 
        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params:o});
        this.hasSearch = true;
        this.triggers[0].show();
    };
    
    var gridfjsearchnosj = new Ext.grid.GridPanel({
        store: strcbfjnosj,
        stripeRows: true,
        frame: true,
        sm: cbGridfj,
        border:true,
        columns: [cbGridfj,{
                header: 'No SJ',
                dataIndex: 'no_sj',
                width: 150,
                sortable: true			
            
            },{
                header: 'Tanggal',
                dataIndex: 'tanggal',
                width: 120,
                sortable: true         
            }],
        tbar:[tbfjnosj],
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strcbfjnosj,
            displayInfo: true
        })
    });

	
    var menufjnosj = new Ext.menu.Menu();
    menufjnosj.add(new Ext.Panel({
        title: 'Pilih No SJ',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 350,
        height: 350,
        closeAction: 'hide',
        plain: true,
        items: [gridfjsearchnosj],
        buttons: [{
                // icon: BASE_ICONS + 'add.png',
                text: 'Done',
                handler: function(){
                    if(Ext.getCmp('id_cbfjpelanggan').getValue() == ''){
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Silahkan pilih pelanggan terlebih dulu',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK			               
                        });
                        return;
                    }
					
                    var sm = gridfjsearchnosj.getSelectionModel();
                    var sel = sm.getSelections();
                    if (sel.length > 0) {
                        var data = '';
                        for (i = 0; i < sel.length; i++) {
                            data = data + sel[i].get('no_sj') + ';';
                        } 
					
                        strfakturpenjualan.load({
                            params: {
                                kd_pelanggan: Ext.getCmp('id_cbfjpelanggan').getValue(),
                                //pkp: Ext.getCmp('pci_status_pkp').getValue(),
                                no_sj: data
                            }
                        });
					
                        menufjnosj.hide();
                    }else{
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Silahkan pilih No DO',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK			               
                        });
                        return;
                    }
                }
            },{
                text: 'Close',
                handler: function(){
                    menufjnosj.hide();
                }
            }]
    }));

var strfakturpenjualan = new Ext.data.Store({
        autoSave: false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'no_sj', allowBlank: false, type: 'text'},
                {name: 'tanggal', allowBlank: false, type: 'text'},
                {name: 'no_do', allowBlank: false, type: 'text'},
                {name: 'kd_produk', allowBlank: false, type: 'text'},
                {name: 'nama_produk', allowBlank: false, type: 'text'},
                {name: 'qty_sj', allowBlank: false, type: 'int'},
                {name: 'rp_harga_jual', allowBlank: false, type: 'int'},
                {name: 'rp_net_harga_jual', allowBlank: false, type: 'int'},
                {name: 'rp_diskon_satuan', allowBlank: false, type: 'int'},
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
                {name: 'rp_jumlah', allowBlank: false, type: 'float'},
                {name: 'rp_total', allowBlank: false, type: 'float'},
                {name: 'rp_uang_muka', allowBlank: false, type: 'float'},
                {name: 'rp_dpp', allowBlank: false, type: 'float'},
                {name: 'harga_net', allowBlank: false, type: 'int'},
                {name: 'rp_total', allowBlank: false, type: 'int'},
                {name: 'rp_disk_po', allowBlank: false, type: 'int'},
                {name: 'nm_satuan', allowBlank: false, type: 'text'},
                {name: 'nama_supplier', allowBlank: false, type: 'text'},
                {name: 'rp_ekstra_diskon', allowBlank: false, type: 'int'},
                {name: 'kd_pelanggan', allowBlank: false, type: 'text'},
                
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("faktur_penjualan/search_no_do_by_pelanggan_no_sj") ?>',
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
 strfakturpenjualan.on('load', function(){
        var jumlah = 0;
        var jumlah_grid = 0;
        var jumlah_net = 0;
        var ppn = 0;
        var grand_total = 0;
        var tagihan = 0;
		
        strfakturpenjualan.each(function(node){			
            jumlah += parseFloat(node.data.rp_jumlah);
            
        });

        jumlah = Math.round(jumlah);
        jumlah_net = jumlah - Ext.getCmp('pfj_uang_muka').getValue();
        jumlah_net = Math.round(jumlah_net);
        tagihan = jumlah_net - Ext.getCmp('pfj_cash_diskon').getValue();
        tagihan = Math.round(tagihan);
        var dpp = jumlah_net / 1.1;
        dpp = Math.round(dpp);
        var ppn = dpp * 0.1;
        ppn = Math.round(ppn);
        var grand_total = parseInt(jumlah_net) + parseInt(ppn);
        grand_total = Math.round(grand_total);
	
        Ext.getCmp('pfj_rp_jumlah').setValue(jumlah);
        Ext.getCmp('pfj_total').setValue(jumlah_net);
        Ext.getCmp('pfj_tagihan').setValue(tagihan);
        Ext.getCmp('pfj_rp_ppn').setValue(ppn);
        Ext.getCmp('pfj_rp_dpp').setValue(dpp);
        
    });

var editorfakturpenjualan = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });
    
var gridfakturpenjualan = new Ext.grid.GridPanel({
        store: strfakturpenjualan,
        stripeRows: true,
        height: 250,
        frame: true,
        border:true,
        plugins: [editorfakturpenjualan],
        tbar: [{
                icon: BASE_ICONS + 'add.png',
                text: 'Add No SJ',
                handler: function(){
                    if(Ext.getCmp('id_fj_noso_dist').getValue() == ''){
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Silahkan pilih no so terlebih dulu',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK			               
                        });
                        return;
                    }
                    
                    strcbfjnosj.load({
                        params: {
                            kd_pelanggan: Ext.getCmp('id_cbfjpelanggan').getValue(),
                            no_so: Ext.getCmp('id_fj_noso_dist').getValue()
                            }
                    });
                    menufjnosj.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
                }
            },
//               {
//                ref: '../removeBtn',
//                icon: BASE_ICONS + 'delete.gif',
//                text: 'Remove',
//                disabled: true,
//                handler: function(){
//                    editorfakturpenjualan.stopEditing();
//                    var s = gridfakturpenjualan.getSelectionModel().getSelections();
//                    for(var i = 0, r; r = s[i]; i++){
//                        strfakturpenjualan.remove(r);
//                    }
//                    var jumlah = 0;
//                    var dpp = 0;
//                    var ppn = 0;
//                    var grand_total = 0;
//                    var extra_diskon = 0;
//                    var pembulatan = 0;
//				
//                    strfakturpenjualan.each(function(node){			
//                        jumlah += (node.data.rp_total_po);
//                    });
//                    jumlah = Math.round(jumlah);
//                    //extra_diskon = Ext.getCmp('pcin_rp_diskon').getValue();
//                    //pembulatan = Ext.getCmp('pcin_pembulatan').getValue();
//				
//                    dpp = jumlah - parseInt(extra_diskon);
//		    dpp = Math.round(dpp);	
//                    //Ext.getCmp('pcin_rp_jumlah').setValue(jumlah);
//                   // Ext.getCmp('pcin_dpp').setValue(dpp);
//				
//                    //var rp_ppn = (parseInt(dpp)) * Ext.getCmp('pcin_ppn').getValue() / 100;
//                    rp_ppn = Math.round(rp_ppn);
//                    //var grand_total =  parseInt(dpp)  + parseInt(rp_ppn);
//                    grand_total = Math.round(grand_total);
//                    //Ext.getCmp('pcin_rp_ppn').setValue(rp_ppn);
//                    //Ext.getCmp('pcin_total_invoice').setValue(grand_total);
//
//                    //Ext.getCmp('pcin_rp_total_grand').setValue(grand_total+pembulatan);
//				
//                }
//            }
            ],
        columns: [{
                header: 'NO SJ',
                dataIndex: 'no_sj',
                width: 150,
                editor: new Ext.form.TextField({						
                    readOnly: true,
                    id: 'fj_no_sj'
                })					
            },{
                header: 'Tanggal SJ',
                dataIndex: 'tanggal',
                width: 150,
                editor: new Ext.form.TextField({						
                    readOnly: true,
                    id: 'fj_tgl_sj'
                })					
            }
            ,{
                header: 'NO DO',
                dataIndex: 'no_do',
                width: 150,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'fj_pono'
                })
            }
            ,{
                header: 'Kode Barang',
                dataIndex: 'kd_produk',
                width: 200,
                sortable: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'fj_kd_produk'
                })
            
            },{
                header: 'Nama Barang',
                dataIndex: 'nama_produk',
                width: 300,
                editor: new Ext.form.TextField({                
                    readOnly: true,
                    id: 'fj_nama_produk'
                })
            },{
                header: 'Qty',
                dataIndex: 'qty_sj',
                width: 70,
                editor: new Ext.form.TextField({                
                    readOnly: true,
                    id: 'fj_qty_terima'
                })
            },{
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 70,
                editor: new Ext.form.TextField({                
                    readOnly: true,
                    id: 'fj_nm_satuan'
                })
            },{
                xtype: 'numbercolumn',
                header: 'Harga Jual',
                dataIndex: 'rp_harga_jual',
                width: 100,
                align: 'right',
                format: '0,0',
                editor: new Ext.form.TextField({                
                    xtype: 'numberfield',
                    readOnly: true,
                    id: 'fj_plist'
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
                    xtype: 'textfield',
                    id: 'fj_disk_grid_supp1',
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
                    xtype: 'textfield',
                    id: 'fj_disk_grid_supp2',
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
                    xtype: 'textfield',
                    id: 'fj_disk_grid_supp3',
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
                    xtype: 'textfield',
                    id: 'fj_disk_grid_supp4',
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
                    xtype: 'textfield',
                    id: 'fj_disk_grid_supp5',
                    readOnly: true,
                    allowBlank: true
                }
            },{
                xtype: 'numbercolumn',
                header: 'Ekstra Diskon Satuan',
                dataIndex: 'rp_diskon_satuan',           
                width: 130,
                sortable: true,
                align: 'right',
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'fj_rp_diskon_satuan',
                    readOnly: true,
                    allowBlank: true
                }
            },{
                xtype: 'numbercolumn',
                header: 'Total Diskon',
                dataIndex: 'rp_diskon',           
                width: 100,
                sortable: true,
                align: 'right',
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'fj_rp_disk_po',
                    readOnly: true,
                    allowBlank: true
                }
            },{
                xtype: 'numbercolumn',
                header: 'Harga NET',
                dataIndex: 'rp_net_harga_jual',           
                width: 100,
                sortable: true,
                align: 'right',
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'fj_harga_net_po',
                    readOnly: true,
                    allowBlank: true
                }
            },
//                {
//                xtype: 'numbercolumn',
//                header: 'Harga NET (Exc.PPN)',
//                dataIndex: 'harga_net_ect',           
//                width: 130,
//                sortable: true,
//                align: 'right',
//                format: '0,0',
//                editor: {
//                    xtype: 'numberfield',
//                    id: 'fj_harga_net_exc_po',
//                    readOnly: true,
//                    allowBlank: true
//                }
//            },
                {
                xtype: 'numbercolumn',
                header: 'Jumlah',
                dataIndex: 'rp_jumlah',           
                width: 130,
                sortable: true,
                align: 'right',
                format: '0,0',
                editor: {
                    xtype: 'numberfield',
                    id: 'fj_dpp_po',
                    readOnly: true,
                    allowBlank: true
                }
            }
//            ,{
//                xtype: 'numbercolumn',
//                header: 'Ekstra Diskon',
//                dataIndex: 'rp_ekstra_diskon',           
//                width: 100,
//                sortable: true,
//                align: 'right',
//                format: '0,0',
//                editor: {
//                    xtype: 'numberfield',
//                    id: 'fj_ekstra_diskon',
//                    allowBlank: false,
//                    listeners:{
//                        'change': function(){
////                            var dpp = Ext.getCmp('pcin_dpp_po').getValue();
////                            var jumlah_gerid = dpp + this.getValue();
////                            Ext.getCmp('pcin_rp_total_po').setValue(jumlah_gerid);
//                        }
//                    }
//                }
//            },{
//                xtype: 'numbercolumn',
//                header: 'Total',
//                dataIndex: 'rp_total',           
//                width: 100,
//                sortable: true,
//                align: 'right',
//                format: '0,0',
//                editor: {
//                    xtype: 'numberfield',
//                    id: 'fj_rp_total',
//                    readOnly: true,
//                    allowBlank: true
//                }
//            }
            ,{
                dataIndex: 'disk_persen_supp1_po',        
                hidden: true,
                editor: {
                    xtype: 'numberfield',
                    id: 'fj_disk_persen_supp1_po',
                    readOnly: true,
                    allowBlank: true
                }
            },{
                dataIndex: 'disk_persen_supp2_po',        
                hidden: true,
                editor: {
                    xtype: 'numberfield',
                    id: 'fj_disk_persen_supp2_po',
                    readOnly: true,
                    allowBlank: true
                }
            },{
                dataIndex: 'disk_persen_supp3_po',        
                hidden: true,
                editor: {
                    xtype: 'numberfield',
                    id: 'fj_disk_persen_supp3_po',
                    readOnly: true,
                    allowBlank: true
                }
            },{
                dataIndex: 'disk_persen_supp4_po',        
                hidden: true,
                editor: {
                    xtype: 'numberfield',
                    id: 'fj_disk_persen_supp4_po',
                    readOnly: true,
                    allowBlank: true
                }
            },{
                dataIndex: 'diskon_amt_supp1_po',        
                hidden: true,
                editor: {
                    xtype: 'numberfield',
                    id: 'fj_diskon_amt_supp1_po',
                    readOnly: true,
                    allowBlank: true
                }
            },{
                dataIndex: 'diskon_amt_supp2_po',        
                hidden: true,
                editor: {
                    xtype: 'numberfield',
                    id: 'fj_diskon_amt_supp2_po',
                    readOnly: true,
                    allowBlank: true
                }
            },{
                dataIndex: 'diskon_amt_supp3_po',        
                hidden: true,
                editor: {
                    xtype: 'numberfield',
                    id: 'fj_diskon_amt_supp3_po',
                    readOnly: true,
                    allowBlank: true
                }
            },{
                dataIndex: 'diskon_amt_supp4_po',        
                hidden: true,
                editor: {
                    xtype: 'numberfield',
                    id: 'fj_diskon_amt_supp4_po',
                    readOnly: true,
                    allowBlank: true
                }
            },{
                dataIndex: 'diskon_amt_supp5_po',        
                hidden: true,
                editor: {
                    xtype: 'numberfield',
                    id: 'fj_diskon_amt_supp5_po',
                    readOnly: true,
                    allowBlank: true
                }
            }]
			
			
    });
var strfakturpajak = new Ext.data.Store({
        autoSave: false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'no_faktur', allowBlank: false, type: 'text'},
                {name: 'tgl_faktur', allowBlank: false, type: 'text'},
                {name: 'rp_faktur', allowBlank: false, type: 'int'},
                {name: 'rp_total_faktur', allowBlank: false, type: 'int'},
                {name: 'rp_potongan', allowBlank: false, type: 'int'},
                {name: 'rp_faktur_net', allowBlank: false, type: 'int'},
                {name: 'rp_ppn', allowBlank: false, type: 'int'},
                {name: 'rp_uang_muka', allowBlank: false, type: 'int'},
                {name: 'rp_dpp', allowBlank: false, type: 'int'}
                ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("faktur_pajak/search_faktur_jual_detail") ?>',
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
// strfakturpajak.on('load', function(){
//        var jumlah = 0;
//        var jumlah_grid = 0;
//        var jumlah_net = 0;
//        var ppn = 0;
//        var grand_total = 0;
//		
//        strfakturpajak.each(function(node){			
//            jumlah += (node.data.rp_total);
//            
//        });
//
//        jumlah = Math.round(jumlah);
//        jumlah_net = parseInt(jumlah) - Ext.getCmp('pfj_potongan').getValue();
//        jumlah_net = Math.round(jumlah_net);
//        var ppn = (parseInt(jumlah_net)) * Ext.getCmp('pfj_ppn').getValue()/ 100;
//        ppn = Math.round(ppn);
//        var grand_total = parseInt(jumlah_net) + parseInt(ppn);
//        grand_total = Math.round(grand_total);
//	
//        Ext.getCmp('pfj_rp_jumlah').setValue(jumlah);
//        Ext.getCmp('pfj_jumlah_net').setValue(jumlah_net);
//        Ext.getCmp('pfj_rp_ppn').setValue(ppn);
//        Ext.getCmp('pfj_total_faktur').setValue(grand_total);
//        
//    });

//var editorfakturpenjualan = new Ext.ux.grid.RowEditor({
//        saveText: 'Update'
//    });
    

var fakturpenjualan = new Ext.FormPanel({
        id: 'fakturpenjualan',
        border: false,
        frame: true,
        autoScroll:true,        
        bodyStyle:'padding-right:20px;',
        labelWidth: 130,
        items: [{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },                  
                items: [headerfakturpenjualan]
            },
            gridfakturpenjualan,
            {
                layout: 'column',
                border: false,
                items: [{
                        columnWidth: .6,
                        style: 'margin:6px 3px 0 0;',
                        layout: 'fit',
                        items: [
                            gridfpuangmuka
                        ]
                    },  {
                        columnWidth: .4,
                        layout: 'form',
                        style:'margin:6px 0 0 0;',
                        border: false,
                        labelWidth: 110,
                        defaults: { labelSeparator: ''},
                        items: [ 
                            {
                                xtype: 'fieldset',
                                autoHeight: true,                               
                                items: [
                                    {
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'Jumlah',
                                        name: 'rp_jumlah',
                                        readOnly: true,                                 
                                        id: 'pfj_rp_jumlah',                                      
                                        anchor: '90%',      
                                        fieldClass:'readonly-input number', 
                                        selectOnFocus: true,	
                                        value:'0'
                                    }
                                    ,{
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'Uang Muka',
                                        name: 'rp_uang_muka',
                                        id: 'pfj_uang_muka',                                      
                                        anchor: '90%',
                                        fieldClass: 'readonly-input number',
                                        selectOnFocus: true,
                                        readOnly: true,
                                        value:'0',
                                        listeners:{
                                            change: function(){
                                                var total = Ext.getCmp('pfj_rp_jumlah').getValue();
                                                var uang_muka = this.getValue();
                                                var afterUangmuka = total - uang_muka ;
                                                var dpp = Math.round(afterUangmuka / 1.1);
                                                var rp_ppn = Math.round(dpp * (Ext.getCmp('pfj_ppn').getValue() / 100));
                                                var tagihan = afterUangmuka - Ext.getCmp('pfj_cash_diskon').getValue();
                                                tagihan = Math.round(tagihan);
                                                Ext.getCmp('pfj_total').setValue(afterUangmuka);
                                                Ext.getCmp('pfj_tagihan').setValue(tagihan);
                                                Ext.getCmp('pfj_rp_dpp').setValue(dpp);
                                                Ext.getCmp('pfj_rp_ppn').setValue(rp_ppn);
                                                                                              
                                          }
                                        }
                                    }
                                    ,{
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'Total',
                                        name: 'total',                                                                        
                                        id: 'pfj_total',                                       
                                        anchor: '90%',  
                                        readOnly: true, 
                                        cls:'vertical-space',
                                        fieldClass:'readonly-input number',
                                        labelStyle:'margin-top:10px;',      
                                        value:'0'                                                                                 
                                    },{
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'Cash Diskon',
                                        name: 'cash_diskon',                                                                        
                                        id: 'pfj_cash_diskon',                                       
                                        anchor: '90%',  
                                        readOnly: false, 
                                        cls:'vertical-space',
                                        fieldClass:'number',
                                        labelStyle:'margin-top:10px;',      
                                        value:'0',
                                        listeners:{
                                            change: function(){
                                                var total = Ext.getCmp('pfj_total').getValue();
                                                var cash_diskon = this.getValue();
                                                var afterCashDiskon = total - cash_diskon ;
                                                Ext.getCmp('pfj_tagihan').setValue(afterCashDiskon);
                                                          
                                          }
                                        }
                                    },{
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                         fieldLabel: 'Total Tagihan',
                                        name: 'tagihan',                                                                        
                                        id: 'pfj_tagihan',                                       
                                        anchor: '90%',  
                                        readOnly: true, 
                                        cls:'vertical-space',
                                        fieldClass:'readonly-input number',
                                        labelStyle:'margin-top:10px;',      
                                        value:'0' 
                                        
                                    },{
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'Rp DPP',
                                        name: 'rp_dpp',                                                                        
                                        id: 'pfj_rp_dpp',                                       
                                        anchor: '90%',  
                                        readOnly: true, 
                                        cls:'vertical-space',
                                        fieldClass:'readonly-input number',
                                        labelStyle:'margin-top:10px;',      
                                        value:'0'                                                                                      
                                    },{
                                        xtype: 'compositefield',
                                        fieldLabel: 'PPN',
                                        combineErrors: false,
                                        items: [
                                            {
                                                xtype: 'numericfield',
                                                currencySymbol:'',
                                                format:'0',
                                                name : 'ppn',
                                                id: 'pfj_ppn',
                                                fieldClass:'readonly-input',
                                                width: 60,
                                                readOnly: true,
                                                value: '10',
                                                maxValue:100,
                                              			   
                                            },
                                            {
                                                xtype: 'displayfield',
                                                value: '%',
                                                width: 17.5
                                            },
                                            {
                                                xtype: 'numericfield',
                                                name : 'rp_ppn',
                                                id : 'pfj_rp_ppn',
                                                currencySymbol:'',
                                                fieldClass:'readonly-input number',
                                                readOnly: true, 
                                                width: 120,
                                                anchor: '90%'
                                               
                                            }
                                        ]
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
                handler: function(){
                    
                    var gridfakturpenjualan = new Array();				
                    strfakturpenjualan.each(function(node){
                        gridfakturpenjualan.push(node.data)
                    });
                    var gridfpuangmuka = new Array();				
                    strgridfpuangmuka.each(function(node){
                        gridfpuangmuka.push(node.data)
                    });
                    Ext.getCmp('fakturpenjualan').getForm().submit({
                        url: '<?= site_url("faktur_penjualan/update_row") ?>',
                        scope: this,
                        params: {
                            detail: Ext.util.JSON.encode(gridfakturpenjualan),
                            detail_dp: Ext.util.JSON.encode(gridfpuangmuka)
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
									
                                    winfakturpenjualanprint.show();
                                    Ext.getDom('fakturpenjualanprint').src = r.printUrl;
                                }
                            });			            
			            
                            clearfakturpenjualan();						
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
                    clearfakturpenjualan();
                }
            }]
    }); 
    
     var winfakturpenjualanprint = new Ext.Window({
        id: 'id_winfakturpenjualanprint',
        title: 'Print Faktur Penjualan',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="fakturpenjualanprint" src=""></iframe>'
    });
    
    function clearfakturpenjualan(){
        Ext.getCmp('fakturpenjualan').getForm().reset();
        Ext.getCmp('fakturpenjualan').getForm().load({
            
            success: function(form, action){
               
            },
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
        strfakturpenjualan.removeAll();
        strgridfpuangmuka.removeAll();
    }
     function cleartotalfaktur(){
        Ext.getCmp('pfj_rp_jumlah').setValue('0');
        Ext.getCmp('pfj_uang_muka').setValue('0');
        Ext.getCmp('pfj_total').setValue('0');
        Ext.getCmp('pfj_rp_ppn').setValue('0');
        Ext.getCmp('pfj_rp_dpp').setValue('0');
        Ext.getCmp('pfj_tagihan').setValue('0');
       
    }
</script>