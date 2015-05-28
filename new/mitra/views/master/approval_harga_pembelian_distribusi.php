<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript"> 
/*START TWIN NO BUKTI FILTER*/
	
    var strcbahpnobuktifilter_dist = new Ext.data.ArrayStore({
        fields: ['no_bukti_filter','keterangan','created_by','nama_supplier'],
        data : []
    });
	
    var strgridahpnobuktifilter_dist = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_bukti_filter','keterangan','created_by','nama_supplier'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("approval_harga_pembelian/get_no_bukti_filter_dist") ?>',
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
	
    var searchgridahpnobuktifilter_dist = new Ext.app.SearchField({
        store: strgridahpnobuktifilter_dist,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridahpnobuktifilter_dist'
    });
	
	
    var gridahpnobuktifilter_dist = new Ext.grid.GridPanel({
        store: strgridahpnobuktifilter_dist,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [{
                header: 'No Bukti',
                dataIndex: 'no_bukti_filter',
                width: 100,
                sortable: true,	
            },{
                header: 'Nama Supplier',
                dataIndex: 'nama_supplier',
                width: 125,
                sortable: true,	
            },{
                header: 'Request By',
                dataIndex: 'created_by',
                width: 80,
                sortable: true,	
            },{
                header: 'Ket. Perubahan',
                dataIndex: 'keterangan',
                width: 175,
                sortable: true,	
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridahpnobuktifilter_dist]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridahpnobuktifilter_dist,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
					
                    Ext.getCmp('id_cbahpnobuktifilter_dist').setValue(sel[0].get('no_bukti_filter'));
                    Ext.getCmp('ahpd_user').setValue(sel[0].get('created_by'));
                    Ext.getCmp('ahpd_keterangan').setValue(sel[0].get('keterangan'));
                    gridapprovalhargapembeliandistribusi.store.load({
                        params:{
                            no_bukti: Ext.getCmp('id_cbahpnobuktifilter_dist').getValue(),
                        }
                    });
                    menuahpnobuktifilter_dist.hide();
                }
            }
        }
    });
	
    var menuahpnobuktifilter_dist = new Ext.menu.Menu();
    menuahpnobuktifilter_dist.add(new Ext.Panel({
        title: 'Pilih No Bukti',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 500,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridahpnobuktifilter_dist],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menuahpnobuktifilter_dist.hide();
                }
            }]
    }));
    
    Ext.ux.TwinComboahpnobuktifilter = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgridahpnobuktifilter_dist.load();
            menuahpnobuktifilter_dist.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menuahpnobuktifilter_dist.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridahpnobuktifilter_dist').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgridahpnobuktifilter_dist').setValue('');
            searchgridahpnobuktifilter_dist.onTrigger2Click();
        }
    });
	
    var cbahpnobuktifilter_dist = new Ext.ux.TwinComboahpnobuktifilter({
        fieldLabel: 'No Bukti Filter',
        id: 'id_cbahpnobuktifilter_dist',
        store: strcbahpnobuktifilter_dist,
        mode: 'local',
        valueField: 'no_bukti_filter',
        displayField: 'no_bukti_filter',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'no_bukti_filter',
        emptyText: 'Pilih No Bukti'
    });
	
    /*END TWIN NO BUKTI FILTER*/
var headerapprovalhargapembeliandistribusi = {
        layout: 'form',
        border: false,
        labelWidth: 100,
        width: 500,
        buttonAlign: 'left',
        defaults: { labelSeparator: ''},
        items: [cbahpnobuktifilter_dist,{
                xtype: 'datefield',
                fieldLabel: 'Tanggal',
                name: 'tanggal',				
                // allowBlank:false,   
                format:'d-m-Y',  
                editable:false,           
                id: 'ahpd_tanggal',                
                anchor: '90%',
                value: new Date().format('m/d/Y')
            },{
                xtype: 'textfield',
                fieldLabel: 'Request By',
                name: 'user',
                readOnly:true,
                fieldClass:'readonly-input',
                id: 'ahpd_user',                
                anchor: '90%',
                value:''
            },{
                xtype: 'textarea',
                hidden: true,
                name: 'keterangan',     
                // allowBlank: false,
                id: 'ahpd_keterangan',  
                fieldClass:'readonly-input',                                    
                // width: 300,            
                anchor: '90%',
            }],
        buttons: [{
                text: 'Submit',
                formBind:true,
                handler: function(){
			
                    var detailapprovalhargapembelian = new Array();              
                    strapprovalhargapembeliandistribusi.each(function(node){
                        detailapprovalhargapembelian.push(node.data)
                    });
				
                    var no_bukti = Ext.getCmp('id_cbahpnobuktifilter_dist').getValue();
                    if (no_bukti == ''){					
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Silahkan Pilih No Bukti Terlebih Dulu',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK			               
                        });
                        return;
                    }	
                    Ext.Ajax.request({
                        url: '<?= site_url("approval_harga_pembelian/approval_distribusi") ?>',
                        method: 'POST',
                        params: {
                            detail: Ext.util.JSON.encode(detailapprovalhargapembelian),
                            no_bukti: Ext.getCmp('id_cbahpnobuktifilter_dist').getValue(),
                            tanggal: Ext.getCmp('ahpd_tanggal').getValue(),
                            keterangan: Ext.getCmp('ahpd_keterangan').getValue(),
                        },
                        callback:function(opt,success,responseObj){
                            var de = Ext.util.JSON.decode(responseObj.responseText);
                            if(de.success==true){
                                Ext.Msg.show({
                                    title: 'Success',
                                    msg: 'Form submitted successfully',
                                    modal: true,
                                    icon: Ext.Msg.INFO,
                                    buttons: Ext.Msg.OK
                                });
                                clearapprovalhargapembeliandistribusi();
                            }else{
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
                        }
                    });  
                }
            },{
                text: 'Reset',
                handler: function(){
                    clearapprovalhargapembeliandistribusi();
                }
            }
          ]
    };
 var strapprovalhargapembeliandistribusi = new Ext.data.Store({
        autoSave:false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_supplier', allowBlank: false, type: 'text'},
                {name: 'kd_produk', allowBlank: false, type: 'text'},
                {name: 'kd_produk_lama', allowBlank: false, type: 'text'},
                {name: 'nama_produk', allowBlank: false, type: 'text'},
                {name: 'nm_satuan', allowBlank: false, type: 'text'},				
                {name: 'rp_het_harga_beli', allowBlank: false, type: 'int'},				
                {name: 'pct_margin', allowBlank: false, type: 'int'},				
                {name: 'rp_ongkos_kirim', allowBlank: false, type: 'int'},				
                {name: 'hrg_supplier', allowBlank: false, type: 'int'},
                {name: 'hrg_supplier_dist', allowBlank: false, type: 'int'},
                {name: 'disk_supp1_op', allowBlank: false, type: 'text'},
                {name: 'disk_supp2_op', allowBlank: false, type: 'text'},
                {name: 'disk_supp3_op', allowBlank: false, type: 'text'},
                {name: 'disk_supp4_op', allowBlank: false, type: 'text'},
                {name: 'disk_dist1_op', allowBlank: false, type: 'text'},
                {name: 'disk_dist2_op', allowBlank: false, type: 'text'},
                {name: 'disk_dist3_op', allowBlank: false, type: 'text'},
                {name: 'disk_dist4_op', allowBlank: false, type: 'text'},
                {name: 'disk_supp1', allowBlank: false, type: 'float'},
                {name: 'disk_supp2', allowBlank: false, type: 'float'},
                {name: 'disk_supp3', allowBlank: false, type: 'float'},
                {name: 'disk_supp4', allowBlank: false, type: 'float'},
                {name: 'disk_amt_supp5', allowBlank: false, type: 'int'},
                {name: 'disk_dist1', allowBlank: false, type: 'float'},
                {name: 'disk_dist2', allowBlank: false, type: 'float'},
                {name: 'disk_dist3', allowBlank: false, type: 'float'},
                {name: 'disk_dist4', allowBlank: false, type: 'float'},
                {name: 'disk_amt_dist5', allowBlank: false, type: 'int'},
                {name: 'net_hrg_supplier_dist', allowBlank: false, type: 'int'},				
                {name: 'net_hrg_supplier_sup', allowBlank: false, type: 'int'},	
                {name: 'net_hrg_supplier_dist_inc', allowBlank: false, type: 'int'},				
                {name: 'net_hrg_supplier_sup_inc', allowBlank: false, type: 'int'},				
                {name: 'waktu_top', allowBlank: false, type: 'text'},
                {name: 'keterangan', allowBlank: false, type: 'text'},
                {name: 'pkp', allowBlank: false, type: 'text'},
                {name: 'status', allowBlank: false, type: 'text'},
                {name: 'is_konsinyasi', allowBlank: false, type: 'text'},
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("approval_harga_pembelian/search_produk_by_no_bukti") ?>',
            method: 'POST'
        }),
        writer: new Ext.data.JsonWriter(
        {
            encode: true,
            writeAllFields: true
        })
    });
	
    function Editedahp_distribusi(){
        Ext.getCmp('eahpd_edited').setValue('Y');
    };
    function diskChangeAhp_distribusi(){
        Editedahp_distribusi();
        var pct_margin = Ext.getCmp('eahpd_pct_margin').getValue();
        var rp_ongkos_kirim = Ext.getCmp('eahpd_rp_ongkos_kirim').getValue();
			
        var hrg_supp = Ext.getCmp('eahpd_hrg_supplier').getValue();
        var rp_margin = (hrg_supp*pct_margin)/100;
        var het_harga_beli = parseInt(hrg_supp)+parseInt(rp_margin)+parseInt(rp_ongkos_kirim);
        Ext.getCmp('eahpd_het_harga_beli').setValue(parseInt(het_harga_beli));
			
        var total_disk = 0;
        			
        var disk_dist1_op = Ext.getCmp('eahpd_disk_dist1_op').getValue();
        var disk_dist1 = Ext.getCmp('eahpd_disk_dist1').getValue();
        if (disk_dist1_op == '%'){
            // disk_dist1 = (disk_dist1*hrg_supp)/100;
            total_disk = hrg_supp-(hrg_supp*(disk_dist1/100));
        }else{
            total_disk = hrg_supp-disk_dist1;				
        }
			
        var disk_dist2_op = Ext.getCmp('eahpd_disk_dist2_op').getValue();
        var disk_dist2 = Ext.getCmp('eahpd_disk_dist2').getValue();
        if (disk_dist2_op == '%'){
            // disk_dist2 = (disk_dist2*disk_dist1)/100;
            total_disk = total_disk-(total_disk*(disk_dist2/100));
        }else{
            total_disk = total_disk-disk_dist2;				
        }
			
        var disk_dist3_op = Ext.getCmp('eahpd_disk_dist3_op').getValue();
        var disk_dist3 = Ext.getCmp('eahpd_disk_dist3').getValue();
        if (disk_dist3_op == '%'){
            // disk_dist3 = (disk_dist3*disk_dist2)/100;
            total_disk = total_disk-(total_disk*(disk_dist3/100));
        }else{
            total_disk = total_disk-disk_dist3;				
        }
			
        var disk_dist4_op = Ext.getCmp('eahpd_disk_dist4_op').getValue();
        var disk_dist4 = Ext.getCmp('eahpd_disk_dist4').getValue();
        if (disk_dist4_op == '%'){
            // disk_dist4 = (disk_dist4*disk_dist3)/100;
            total_disk = total_disk-(total_disk*(disk_dist4/100));
        }else{
            total_disk = total_disk-disk_dist4;				
        }
			
        var total_disk = total_disk - Ext.getCmp('eahpd_disk_amt_dist5').getValue();

        var net_price_dist = total_disk;
        Ext.getCmp('eahpd_net_hrg_supplier_dist_inc').setValue(net_price_dist);
        if(Ext.getCmp('eahpd_pkp').getValue() == 'YA'){
            Ext.getCmp('eahpd_net_hrg_supplier_dist').setValue(net_price_dist/1.1);
        }else{
            Ext.getCmp('eahpd_net_hrg_supplier_dist').setValue(net_price_dist);
        }
    };
    var editorapprovalhargapembeliandist = new Ext.ux.grid.RowEditor({
        saveText: 'Update'		
    });
    var gridapprovalhargapembeliandistribusi = new Ext.grid.GridPanel({
        store: strapprovalhargapembeliandistribusi,
        stripeRows: true,
        height: 350,
        frame: true,
        border:true,
        plugins: [editorapprovalhargapembeliandist],
        columns: [ {
                header: 'Kd Supplier',
                dataIndex: 'kd_supplier',
                width: 100,
                hidden: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass:'readonly-input',
                    id: 'eahpd_hp_kd_supplier'
                })
            },{
                header: 'PKP',
                dataIndex: 'pkp',
                width: 100,
                hidden: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass:'readonly-input',
                    id: 'eahpd_pkp'
                })
            },{
                header: 'Status',
                dataIndex: 'status',
                width: 80,
                sortable: true,
                editor: {
                    xtype:          'combo',
                    store:          new Ext.data.JsonStore({
                        fields : ['name'],
                        data   : [
                            {name : 'Approve'},
                            {name : 'Reject'},
                        ]
                    }),
                    id:           	'eahpd_status',
                    mode:           'local',
                    name:           'status',
                    value:          'Approve',
                    width:			80,
                    editable:       false,
                    hiddenName:     'status',
                    valueField:     'name',
                    displayField:   'name',
                    triggerAction:  'all',
                    forceSelection: true,
                }
            },{
                header: 'Edited',
                dataIndex: 'edited',
                width: 50,
                sortable: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass:'readonly-input',
                    id: 'eahpd_edited'
                })
            },{
                header: 'Kode Barang',
                dataIndex: 'kd_produk',
                width: 100,
                sortable: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass:'readonly-input',
                    id: 'eahpd_kd_produk'
                })
            },{
                header: 'Kode Brg Lama',
                dataIndex: 'kd_produk_lama',
                width: 100,
                sortable: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                                    fieldClass: 'readonly-input',
                    id: 'eahpd_kd_produk_lama'
                })
            },{
                header: 'Nama Barang',
                dataIndex: 'nama_produk',
                width: 300,
                sortable: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass:'readonly-input',
                    id: 'eahpd_nama_produk'
                })
            },{
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 80,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass:'readonly-input',
                    id: 'eahpd_satuan'
                })
            },{
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'TOP',
                dataIndex: 'waktu_top',
                width: 60,
                editor: new Ext.form.TextField({
                    // readOnly: true,
                    id: 'eahpd_waktu_top'
                })
            },{
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Harga Supplier',
                dataIndex: 'hrg_supplier_dist',           
                width: 100,
                editor: {
                    xtype: 'numberfield',
                    id: 'eahpd_hrg_supplier',
                    // readOnly: true,
                    listeners:{			
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                diskChangeAhp_distribusi();
                            }, c);
                        }
                    }
                }
            },{
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: "% Margin",
                dataIndex: 'pct_margin',
                hidden: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass:'readonly-input',
                    id: 'eahpd_pct_margin'
                })
            },{
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: "Rp Ongkos Kirim",
                dataIndex: 'rp_ongkos_kirim',          
                width: 120,
                hidden: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    fieldClass:'readonly-input',
                    id: 'eahpd_rp_ongkos_kirim'
                })
            },{
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'HET Beli',
                hidden:true,
                dataIndex: 'rp_het_harga_beli',           
                width: 100,
                editor: {
                    xtype: 'numberfield',
                    id: 'eahpd_het_harga_beli',
                    readOnly: true,
                    fieldClass:'readonly-input',
                    // listeners:{
                    // 'render': function(c) {
                    // c.getEl().on('keyup', function() {
                    // diskChangeAhp_distribusi();
                    // }, c);
                    // }
                    // }
                }
            },{
                header: '% / Rp',
                dataIndex: 'disk_dist1_op',
                width: 50,
                editor: {
                    xtype:          'combo',
                    store:          new Ext.data.JsonStore({
                        fields : ['name'],
                        data   : [
                            {name : '%'},
                            {name : 'Rp'},
                        ]
                    }),
                    id:           	'eahpd_disk_dist1_op',
                    mode:           'local',
                    name:           'disk_dist1_op',
                    value:          '%',
                    width:			50,
                    editable:       false,
                    hiddenName:     'disk_dist1_op',
                    valueField:     'name',
                    displayField:   'name',
                    triggerAction:  'all',
                    forceSelection: true,
                    listeners:{
                        'expand':function(){
                            Ext.getCmp('eahpd_disk_dist1').setValue(0);
                        },
                        select:function(){
                            Ext.getCmp('eahpd_disk_dist1').setMaxValue(Number.MAX_VALUE);
                            if (this.getValue() === 'persen') 
                                Ext.getCmp('eahpd_disk_dist1').maxValue = 100;
                            else 
                                Ext.getCmp('eahpd_disk_dist1').maxLength = 11;
                            Editedahp_distribusi();
                        }
                    }
                }
            },{
                xtype: 'numbercolumn',
                align: 'right',
                // format: '0,0',
                header: 'Diskon Distribusi 1',
                dataIndex: 'disk_dist1',           
                width: 150,
                editor: {
                    xtype: 'numberfield',
                    msgTarget: 'under',
                    flex:1,
                    width:115,
                    name : 'disk_dist1',
                    id: 'eahpd_disk_dist1',
                    style: 'text-align:right;',
                    listeners:{
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                diskChangeAhp_distribusi();
                            }, c);
                        }
                    }
                }
            },{
                header: '% / Rp',
                dataIndex: 'disk_dist2_op',
                width: 50,
                editor: {
                    xtype:          'combo',
                    store:          new Ext.data.JsonStore({
                        fields : ['name'],
                        data   : [
                            {name : '%'},
                            {name : 'Rp'},
                        ]
                    }),
                    id:           	'eahpd_disk_dist2_op',
                    mode:           'local',
                    name:           'disk_dist2_op',
                    value:          '%',
                    width:			50,
                    editable:       false,
                    hiddenName:     'disk_dist2_op',
                    valueField:     'name',
                    displayField:   'name',
                    triggerAction:  'all',
                    forceSelection: true,
                    listeners:{
                        'expand':function(){
                            Ext.getCmp('eahpd_disk_dist2').setValue(0);
                        },
                        select:function(){
                            Ext.getCmp('eahpd_disk_dist2').setMaxValue(Number.MAX_VALUE);
                            if (this.getValue() === 'persen') 
                                Ext.getCmp('eahpd_disk_dist2').maxValue = 100;
                            else 
                                Ext.getCmp('eahpd_disk_dist2').maxLength = 11;
                            Editedahp_distribusi();
                        }
                    }
                }
            },{
                xtype: 'numbercolumn',
                align: 'right',
                // format: '0,0',
                header: 'Diskon Distribusi 2',
                dataIndex: 'disk_dist2',           
                width: 150,
                editor: {
                    xtype: 'numberfield',
                    msgTarget: 'under',
                    flex:1,
                    width:115,
                    name : 'disk_dist2',
                    id: 'eahpd_disk_dist2',
                    style: 'text-align:right;',
                    listeners:{
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                diskChangeAhp_distribusi();
                            }, c);
                        }
                    }
                }
            },{
                header: '% / Rp',
                dataIndex: 'disk_dist3_op',
                width: 50,
                editor: {
                    xtype:          'combo',
                    store:          new Ext.data.JsonStore({
                        fields : ['name'],
                        data   : [
                            {name : '%'},
                            {name : 'Rp'},
                        ]
                    }),
                    id:           	'eahpd_disk_dist3_op',
                    mode:           'local',
                    name:           'disk_dist3_op',
                    value:          '%',
                    width:			50,
                    editable:       false,
                    hiddenName:     'disk_dist3_op',
                    valueField:     'name',
                    displayField:   'name',
                    triggerAction:  'all',
                    forceSelection: true,
                    listeners:{
                        'expand':function(){
                            Ext.getCmp('eahpd_disk_dist3').setValue(0);
                        },
                        select:function(){
                            Ext.getCmp('eahpd_disk_dist3').setMaxValue(Number.MAX_VALUE);
                            if (this.getValue() === 'persen') 
                                Ext.getCmp('eahpd_disk_dist3').maxValue = 100;
                            else 
                                Ext.getCmp('eahpd_disk_dist3').maxLength = 11;
                            Editedahp_distribusi();
                        }
                    }
                }
            },{
                xtype: 'numbercolumn',
                align: 'right',
                // format: '0,0',
                header: 'Diskon Distribusi 3',
                dataIndex: 'disk_dist3',           
                width: 150,
                editor: {
                    xtype: 'numberfield',
                    msgTarget: 'under',
                    flex:1,
                    width:115,
                    name : 'disk_dist3',
                    id: 'eahpd_disk_dist3',
                    style: 'text-align:right;',
                    listeners:{
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                diskChangeAhp_distribusi();
                            }, c);
                        }
                    }
                }
            },{
                header: '% / Rp',
                dataIndex: 'disk_dist4_op',
                width: 50,
                editor: {
                    xtype:          'combo',
                    store:          new Ext.data.JsonStore({
                        fields : ['name'],
                        data   : [
                            {name : '%'},
                            {name : 'Rp'},
                        ]
                    }),
                    id:           	'eahpd_disk_dist4_op',
                    mode:           'local',
                    name:           'disk_dist4_op',
                    value:          '%',
                    width:			50,
                    editable:       false,
                    hiddenName:     'disk_dist4_op',
                    valueField:     'name',
                    displayField:   'name',
                    triggerAction:  'all',
                    forceSelection: true,
                    listeners:{
                        'expand':function(){
                            Ext.getCmp('eahpd_disk_dist4').setValue(0);
                        },
                        select:function(){
                            Ext.getCmp('eahpd_disk_dist4').setMaxValue(Number.MAX_VALUE);
                            if (this.getValue() === 'persen') 
                                Ext.getCmp('eahpd_disk_dist4').maxValue = 100;
                            else 
                                Ext.getCmp('eahpd_disk_dist4').maxLength = 11;
                            Editedahp_distribusi();
                        }
                    }
                }
            },{
                xtype: 'numbercolumn',
                align: 'right',
                // format: '0,0',
                header: 'Diskon Distribusi 4',
                dataIndex: 'disk_dist4',           
                width: 150,
                editor: {
                    xtype: 'numberfield',
                    msgTarget: 'under',
                    flex:1,
                    width:115,
                    name : 'disk_dist4',
                    id: 'eahpd_disk_dist4',
                    style: 'text-align:right;',
                    listeners:{
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                diskChangeAhp_distribusi();
                            }, c);
                        }
                    }
                }
            },{
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Diskon Distribusi 5',
                dataIndex: 'disk_amt_dist5',           
                width: 150,
                editor: {
                    xtype: 'numberfield',
                    msgTarget: 'under',
                    flex:1,
                    width:115,
                    name : 'disk_amt_dist5',
                    id: 'eahpd_disk_amt_dist5',
                    style: 'text-align:right;',
                    listeners:{
                        'render': function(c) {
                            c.getEl().on('keyup', function() {
                                diskChangeAhp_distribusi();
                            }, c);
                        }
                    }
                }
            },{
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Net Price Distribusi(Inc.PPN)',
                dataIndex: 'net_hrg_supplier_dist_inc',
                width: 190,
                editor: {
                    xtype: 'numberfield',
                    id: 'eahpd_net_hrg_supplier_dist_inc',
                    readOnly: true,
                    fieldClass:'readonly-input',
                    // style: 'text-align:right;'
                }
            },{
                xtype: 'numbercolumn',
                align: 'right',
                format: '0,0',
                header: 'Net Price Distribusi(Exc.PPN)',
                dataIndex: 'net_hrg_supplier_dist',
                width: 190,
                editor: {
                    xtype: 'numberfield',
                    id: 'eahpd_net_hrg_supplier_dist',
                    readOnly: true,
                    fieldClass:'readonly-input'
                    // style: 'text-align:right;'
                }
            },{
                
                header: 'Is Konsinyasi',
                dataIndex: 'is_konsinyasi',
                width: 190
            }]
    });
// Form Panel
var approvalhargapembeliandistribusi = new Ext.FormPanel({
        id: 'approvalhargapembeliandistribusi',
        buttonAlign: 'left',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },					
                items: [headerapprovalhargapembeliandistribusi,gridapprovalhargapembeliandistribusi]
            } 
        ]
//        buttons: [{
//                text: 'Reset',
//                handler: function(){
//                    clearapprovalhargapembeliandistribusi();
//                }
//            }]
    });
	
    function clearapprovalhargapembeliandistribusi(){
        Ext.getCmp('approvalhargapembeliandistribusi').getForm().reset();
        strapprovalhargapembeliandistribusi.removeAll();
    }

</script>
