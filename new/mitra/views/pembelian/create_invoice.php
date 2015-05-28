?<php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">

    // cari supplier
    var strcbpcisuplier = new Ext.data.ArrayStore({
        fields: ['nama_supplier'],
        data : []
    });
	
    var strgridpcisuplier = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_supplier', 'nama_supplier', 'top','pkp'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_create_invoice/search_supplier") ?>',
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
	
    strgridpcisuplier.on('load', function(){
        Ext.getCmp('id_searchgridpcisuplier').focus();
    });
	
    var searchgridpcisuplier = new Ext.app.SearchField({
        store: strgridpcisuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridpcisuplier'
    });
	
	
    var gridpcisuplier = new Ext.grid.GridPanel({
        store: strgridpcisuplier,
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
            },{
                dataIndex: 'top',
                hidden: true         
            },{
                header: 'Status PKP',
                dataIndex: 'pkp',
                width: 100,
                sortable: true
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridpcisuplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridpcisuplier,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                    var top = sel[0].get('top');
                    Ext.getCmp('pci_kd_supplier').setValue(sel[0].get('kd_supplier'));
                    Ext.getCmp('id_cbpcisuplier').setValue(sel[0].get('nama_supplier'));
                    Ext.getCmp('pci_hari').setValue(top);
                    Ext.getCmp('pci_tgl_jth_tempo').setValue(new Date().add(Date.DAY, parseInt(top)));
                    Ext.getCmp('pci_status_pkp').setValue(sel[0].get('pkp'));
                    var pkp = sel[0].get('pkp');
                    if (pkp === '1') {
                        Ext.getCmp('pci_status_pkp').setValue('YA');
                        Ext.getCmp('pcin_ppn').setValue('10');
                        Ext.getCmp('pci_no_faktur_pajak').setDisabled(false);
                    } else {
                        Ext.getCmp('pci_status_pkp').setValue('TIDAK');
                        Ext.getCmp('pcin_ppn').setValue('0');
                        Ext.getCmp('pci_no_faktur_pajak').setDisabled(true);
                    }
                    strpembeliancreateinvoice.removeAll();       
                    menupcisuplier.hide();
                   cleartotal();
                }
            }
        }
    });
	
    var menupcisuplier = new Ext.menu.Menu();
    menupcisuplier.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridpcisuplier],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menupcisuplier.hide();
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
            strgridpcisuplier.load();
            menupcisuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menupcisuplier.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridpcisuplier').getValue();
        if( sf !== ''){
            Ext.getCmp('id_searchgridpcisuplier').setValue('');
            searchgridpcisuplier.onTrigger2Click();
        }
    });
	
    var cbpcisuplier = new Ext.ux.TwinComboSuplier({
        fieldLabel: 'Supplier <span class="asterix">*</span>',
        id: 'id_cbpcisuplier',
        store: strcbpcisuplier,
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
    var cbGridpci = new Ext.grid.CheckboxSelectionModel();
	
    var strcbpcinodo = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_do', 'tanggal_terima'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_create_invoice/search_no_do_by_supplier") ?>',
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
     strcbpcinodo.on('load', function(){
        Ext.getCmp('pcisearchnodo').focus();
    });
    var searchpcinodo = new Ext.app.SearchField({
        store: strcbpcinodo,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
            },
        
        width: 220,
        id: 'pcisearchnodo'
    });
    
    var tbpcinodo = new Ext.Toolbar({
        items: [searchpcinodo]
    });

      searchpcinodo.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';
			
            // Get the value of search field
            var fid = Ext.getCmp('pci_kd_supplier').getValue();
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
	
    searchpcinodo.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }
	 
        // Get the value of search field
        var fid = Ext.getCmp('pci_kd_supplier').getValue();
        var o = { start: 0, kd_supplier: fid };
	 
        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params:o});
        this.hasSearch = true;
        this.triggers[0].show();
    };
    
    var gridpcisearchnodo = new Ext.grid.GridPanel({
        store: strcbpcinodo,
        stripeRows: true,
        frame: true,
        sm: cbGridpci,
        border:true,
        columns: [cbGridpci,{
                header: 'No RO',
                dataIndex: 'no_do',
                width: 150,
                sortable: true			
            
            },{
                header: 'Tanggal Terima',
                dataIndex: 'tanggal_terima',
                width: 150,
                sortable: true         
            }],
        tbar:[tbpcinodo]
    });

	
    var menupcinodo = new Ext.menu.Menu();
    menupcinodo.add(new Ext.Panel({
        title: 'Pilih No RO',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 350,
        height: 350,
        closeAction: 'hide',
        plain: true,
        items: [gridpcisearchnodo],
        buttons: [{
                // icon: BASE_ICONS + 'add.png',
                text: 'Done',
                handler: function(){
                    if(Ext.getCmp('pci_kd_supplier').getValue() === ''){
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Silahkan pilih supplier terlebih dulu',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK			               
                        });
                        return;
                    }
					
                    var sm = gridpcisearchnodo.getSelectionModel();
                    var sel = sm.getSelections();
                    if (sel.length > 0) {
                        var data = '';
                        for (i = 0; i < sel.length; i++) {
                            data = data + sel[i].get('no_do') + ';';
                        } 
					
                        strpembeliancreateinvoice.load({
                            params: {
                                kd_supplier: Ext.getCmp('pci_kd_supplier').getValue(),
                                pkp: Ext.getCmp('pci_status_pkp').getValue(),
                                no_do: data
                            }
                        });
					
                        menupcinodo.hide();
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
                    menupcinodo.hide();
                }
            }]
    }));
	
	
    var strpembeliancreateinvoice = new Ext.data.Store({
        autoSave: false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'no_do', allowBlank: false, type: 'text'},
                {name: 'kd_produk', allowBlank: false, type: 'text'},
                {name: 'tanggal', allowBlank: false, type: 'text'},
                {name: 'tanggal_terima', allowBlank: false, type: 'text'},
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
                {name: 'harga_net', allowBlank: false, type: 'float'},
                {name: 'rp_total', allowBlank: false, type: 'int'},
                {name: 'rp_disk_po', allowBlank: false, type: 'int'},
                {name: 'nm_satuan', allowBlank: false, type: 'text'},
                {name: 'nama_supplier', allowBlank: false, type: 'text'},
                {name: 'adjust', allowBlank: false, type: 'int'},
                {name: 'kd_supplier', allowBlank: false, type: 'text'},
                {name: 'nama_produk', allowBlank: false, type: 'text'},
                {name: 'no_po', allowBlank: false, type: 'text'}
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pembelian_create_invoice/search_no_do_by_supplier_no_do") ?>',
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
	
    var headerpembeliancreateinvoice = {
        layout: 'column',
        border: false,
        items: [{
                columnWidth: .4,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [{
                        xtype: 'textfield',
                        fieldLabel: 'No Invoice',
                        name: 'no_invoice',
                        readOnly:true,
                        fieldClass:'readonly-input',
                        id: 'pci_no_in',                
                        anchor: '90%',
                        value:''
                    },{
                        xtype: 'hidden',
                        name: 'kd_supplier',
                        id: 'pci_kd_supplier',
                        value: ''
                    },cbpcisuplier,{
                        xtype: 'textfield',
                        fieldLabel: 'Bukti Supplier <span class="asterix">*</span>',
                        name: 'no_bukti_supplier',
                        allowBlank: false,
                        id: 'pci_no_bukti_supplier',                
                        anchor: '90%'
                    },{
                        xtype: 'textfield',
                        fieldLabel: 'No Faktur Pajak',
                        name: 'no_faktur_pajak',
                        id: 'pci_no_faktur_pajak',                
                        anchor: '90%',
                        minLength: 16,
                        maxLength: 19,
                        listeners: {
                            'blur':function(){
                                var no_faktur = this.getValue();
                                if(no_faktur.length === 16){
                                    
                                    no_faktur = no_faktur.replace("-","");
                                    no_faktur = no_faktur.replace(".","");
                                    
                                    console.log(no_faktur);
                                    Ext.getCmp('pci_no_faktur_pajak').setValue(no_faktur.substring(0, 3) + '.' + 
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
                defaults: { labelSeparator: ''},
                items: [ {
                        xtype: 'hidden',
                        name: 'rp_diskon',
                        id: 'pcin_rp_diskon'
                    },{
                        xtype: 'datefield',
                        fieldLabel: 'Tgl Terima Invoice',
                        name: 'tgl_terima_invoice',
                        id: 'pci_tgl_terima_invoice', 
                        format: 'd-m-Y',
                        emptyText: 'Tgl Terima Invoice',
                        value: new Date(), 
                        maxValue: (new Date()).clearTime() ,   
                        editable: false,           
                        anchor: '90%'
                    },{
                        xtype: 'datefield',
                        fieldLabel: 'Jatuh Tempo',
                        name: 'tgl_jth_tempo',
                        id: 'pci_tgl_jth_tempo', 
                        readOnly: true, 
                        format: 'd-m-Y',
                        fieldClass:'readonly-input',
                        anchor: '90%'
                    },{
                        xtype: 'datefield',
                        fieldLabel: 'Tgl Faktur Pajak',
                        name: 'tgl_faktur_pajak',
                        id: 'pci_tgl_faktur_pajak', 
                        format: 'd-m-Y',
                        emptyText: 'Tgl Faktur Pajak',
                        value: new Date(),   
                        editable: false,           
                        anchor: '90%'
                    },{
                        xtype: 'textfield',
                        fieldLabel: 'Status PKP',
                        name: 'status_pkp',
                        fieldClass: 'readonly-input',
                        readOnly: true,
                        id: 'pci_status_pkp',
                        anchor: '90%',
                        value: ''
                    }]
            }, {
                columnWidth: .3,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [ {
                        xtype: 'datefield',
                        fieldLabel: 'Tgl. Invoice',
                        name: 'tgl_invoice',
                        id: 'pci_tgl_invoice', 
                        format: 'd-m-Y',
                        emptyText: 'Tgl Invoice',
                        editable: false,    
                        value: new Date(),       
                        anchor: '90%',
                        maxValue: (new Date()).clearTime() ,
                        listeners: {
                            'change':function(){
                                var top = Ext.getCmp('pci_hari').getValue();
                                var tgl_inv = this.getValue();
                                Ext.getCmp('pci_tgl_jth_tempo').setValue(new Date(tgl_inv).add(Date.DAY, parseInt(top)));
                            }
                        }
                    },{
                        xtype: 'compositefield',
                        fieldLabel: 'Top',
                        combineErrors: false,
                        items: [{
                                name : 'top',
                                xtype: 'numberfield',
                                id: 'pci_hari',
                                fieldClass:'number',
                                selectOnFocus: true,
                                width: 60,
                                value:'0',
                                listeners: {
                                    'change':function(){
                                        var top = this.getValue();
                                        var tgl_inv = Ext.getCmp('pci_tgl_invoice').getValue();
                                        Ext.getCmp('pci_tgl_jth_tempo').setValue(new Date(tgl_inv).add(Date.DAY, parseInt(top)));
                                    }
                                }
								   
                            },{
                                xtype: 'displayfield',
                                value: 'Hari'
                            }]
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
                                id: 'pcin_peruntukan_supermarket',
                                checked:true
                            }, {
                                boxLabel: 'Distribusi',
                                name: 'kd_peruntukan',
                                inputValue: '1',
                                id: 'pcin_peruntukan_distribusi'
                            }]
                    }]
            }]
    };
    
    
    strpembeliancreateinvoice.on('load', function(){
        var jumlah = 0;
        var jumlah_grid = 0;
        var dpp = 0;
        var ppn = 0;
        var grand_total = 0;
		
        strpembeliancreateinvoice.each(function(node){			
            jumlah += (node.data.rp_total_po);
            dpp += (node.data.dpp_po);
            //node.data.rp_total_po = (node.data.qty_terima)*(node.data.pricelist);
        });
        dpp = Math.round(dpp);
        var ppn = (parseInt(dpp)) * Ext.getCmp('pcin_ppn').getValue()/ 100;
        ppn = Math.round(ppn);
        var grand_total = parseInt(dpp) + parseInt(ppn);
        
        jumlah = Math.round(jumlah);
       
        grand_total = Math.round(grand_total);
	
        Ext.getCmp('pcin_rp_jumlah').setValue(jumlah);
        Ext.getCmp('pcin_dpp').setValue(dpp);
        Ext.getCmp('pcin_rp_diskon').setValue(0);
        Ext.getCmp('pcin_rp_ppn').setValue(ppn);
        Ext.getCmp('pcin_total_invoice').setValue(grand_total);
        Ext.getCmp('pcin_rp_total_grand').setValue(grand_total);
    });
	
    strpembeliancreateinvoice.on('update', function(){
        var jumlah = 0;
        var dpp = 0;
        var ppn = 0;
        var grand_total = 0;
        var extra_diskon = 0;
        var pembulatan = 0;
		
        strpembeliancreateinvoice.each(function(node){			
            jumlah += (node.data.rp_total_po);
            
        });
        jumlah = Math.round(jumlah);
        extra_diskon = Ext.getCmp('pcin_rp_diskon').getValue();
        dpp = jumlah - parseInt(extra_diskon);
        dpp = Math.round(dpp);
	var rp_ppn = (parseInt(dpp)) * Ext.getCmp('pcin_ppn').getValue() / 100;
        rp_ppn = Math.round(rp_ppn);
        var grand_total =  parseInt(dpp)  + parseInt(rp_ppn);
       
        pembulatan = Ext.getCmp('pcin_pembulatan').getValue();
	grand_total = Math.round(grand_total);
        
	Ext.getCmp('pcin_rp_jumlah').setValue(jumlah);
        Ext.getCmp('pcin_dpp').setValue(dpp);	
        Ext.getCmp('pcin_rp_ppn').setValue(rp_ppn);
        Ext.getCmp('pcin_total_invoice').setValue(grand_total);
        Ext.getCmp('pcin_rp_total_grand').setValue(grand_total+pembulatan);
    });
	
    var editorpembeliancreateinvoice = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });
    
    var gridpembeliancreateinvoice = new Ext.grid.GridPanel({
        store: strpembeliancreateinvoice,
        stripeRows: true,
        height: 250,
        frame: true,
        border:true,
        plugins: [editorpembeliancreateinvoice],
        tbar: [{
                icon: BASE_ICONS + 'add.png',
                text: 'Add No RO',
                handler: function(){
                    if(Ext.getCmp('pci_kd_supplier').getValue() === ''){
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Silahkan pilih supplier terlebih dulu',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK			               
                        });
                        return;
                    }
                    var supermarket = Ext.getCmp('pcin_peruntukan_supermarket').getValue();
                    var distribusi = Ext.getCmp('pcin_peruntukan_distribusi').getValue();

                    if (supermarket){
                        kd_peruntukkan = '0';
                    }else if (distribusi) {
                        kd_peruntukkan = '1';									
                    }
                    strcbpcinodo.load({
                        params: {
                            kd_supplier: Ext.getCmp('pci_kd_supplier').getValue()  ,
                            kd_peruntukkan: kd_peruntukkan
                        }
                    });
                    menupcinodo.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
                }
            },{
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                disabled: true,
                handler: function(){
                    editorpembeliancreateinvoice.stopEditing();
                    var s = gridpembeliancreateinvoice.getSelectionModel().getSelections();
                    for(var i = 0, r; r = s[i]; i++){
                        strpembeliancreateinvoice.remove(r);
                    }
                    var jumlah = 0;
                    var dpp = 0;
                    var ppn = 0;
                    var grand_total = 0;
                    var extra_diskon = 0;
                    var pembulatan = 0;
				
                    strpembeliancreateinvoice.each(function(node){			
                        jumlah += (node.data.rp_total_po);
                    });
                    jumlah = Math.round(jumlah);
                    extra_diskon = Ext.getCmp('pcin_rp_diskon').getValue();
                    pembulatan = Ext.getCmp('pcin_pembulatan').getValue();
				
                    dpp = jumlah - parseInt(extra_diskon);
		    dpp = Math.round(dpp);	
                    Ext.getCmp('pcin_rp_jumlah').setValue(jumlah);
                    Ext.getCmp('pcin_dpp').setValue(dpp);
				
                    var rp_ppn = (parseInt(dpp)) * Ext.getCmp('pcin_ppn').getValue() / 100;
                    rp_ppn = Math.round(rp_ppn);
                    var grand_total =  parseInt(dpp)  + parseInt(rp_ppn);
                    grand_total = Math.round(grand_total);
                    Ext.getCmp('pcin_rp_ppn').setValue(rp_ppn);
                    Ext.getCmp('pcin_total_invoice').setValue(grand_total);

                    Ext.getCmp('pcin_rp_total_grand').setValue(grand_total+pembulatan);
				
                }
            }],
        columns: [{
                header: 'No RO',
                dataIndex: 'no_do',
                width: 150,
                editor: new Ext.form.TextField({						
                    readOnly: true,
                    id: 'pcin_no_do'
                })					
            },{
                header: 'Tgl RO',
                dataIndex: 'tanggal_terima',
                width: 100,
                editor: new Ext.form.TextField({						
                    readOnly: true,
                    id: 'pcin_tgrl_ro',
                    fielClass: 'readonly-text'
                })					
            },{
                header: 'PO No',
                dataIndex: 'no_po',
                width: 150,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'pcin_pono'
                })
            },{
                header: 'Kode Barang',
                dataIndex: 'kd_produk',
                width: 200,
                sortable: true,
                editor: new Ext.form.TextField({
                    readOnly: true,
                    id: 'pcin_kd_produk'
                })
            
            },{
                header: 'Nama Barang',
                dataIndex: 'nama_produk',
                width: 300,
                editor: new Ext.form.TextField({                
                    readOnly: true,
                    id: 'pcin_nama_produk'
                })
            },{
                header: 'Qty',
                dataIndex: 'qty_terima',
                width: 70,
                editor: new Ext.form.TextField({                
                    readOnly: true,
                    id: 'pcin_qty_terima'
                })
            },{
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 70,
                editor: new Ext.form.TextField({                
                    readOnly: true,
                    id: 'pcin_nm_satuan'
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
                    id: 'pcin_plist'
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
                    id: 'pcin_disk_grid_supp1',
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
                    id: 'pcin_disk_grid_supp2',
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
                    id: 'pcin_disk_grid_supp3',
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
                    id: 'pcin_disk_grid_supp4',
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
                    id: 'pcin_disk_grid_supp5',
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
                    id: 'pcin_rp_disk_po',
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
                    id: 'pcin_harga_net_po',
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
                    id: 'pcin_harga_net_exc_po',
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
                    id: 'pcin_dpp_po',
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
                    id: 'pcin_adjust',
                    allowBlank: false,
                    listeners:{
                        'change': function(){
                            var dpp = Ext.getCmp('pcin_dpp_po').getValue();
                            var jumlah_gerid = dpp + this.getValue();
                            Ext.getCmp('pcin_rp_total_po').setValue(jumlah_gerid);
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
                    id: 'pcin_rp_total_po',
                    readOnly: true,
                    allowBlank: true
                }
            },{
                dataIndex: 'disk_persen_supp1_po',        
                hidden: true,
                editor: {
                    xtype: 'numberfield',
                    id: 'pcin_disk_persen_supp1_po',
                    readOnly: true,
                    allowBlank: true
                }
            },{
                dataIndex: 'disk_persen_supp2_po',        
                hidden: true,
                editor: {
                    xtype: 'numberfield',
                    id: 'pcin_disk_persen_supp2_po',
                    readOnly: true,
                    allowBlank: true
                }
            },{
                dataIndex: 'disk_persen_supp3_po',        
                hidden: true,
                editor: {
                    xtype: 'numberfield',
                    id: 'pcin_disk_persen_supp3_po',
                    readOnly: true,
                    allowBlank: true
                }
            },{
                dataIndex: 'disk_persen_supp4_po',        
                hidden: true,
                editor: {
                    xtype: 'numberfield',
                    id: 'pcin_disk_persen_supp4_po',
                    readOnly: true,
                    allowBlank: true
                }
            },{
                dataIndex: 'diskon_amt_supp1_po',        
                hidden: true,
                editor: {
                    xtype: 'numberfield',
                    id: 'pcin_diskon_amt_supp1_po',
                    readOnly: true,
                    allowBlank: true
                }
            },{
                dataIndex: 'diskon_amt_supp2_po',        
                hidden: true,
                editor: {
                    xtype: 'numberfield',
                    id: 'pcin_diskon_amt_supp2_po',
                    readOnly: true,
                    allowBlank: true
                }
            },{
                dataIndex: 'diskon_amt_supp3_po',        
                hidden: true,
                editor: {
                    xtype: 'numberfield',
                    id: 'pcin_diskon_amt_supp3_po',
                    readOnly: true,
                    allowBlank: true
                }
            },{
                dataIndex: 'diskon_amt_supp4_po',        
                hidden: true,
                editor: {
                    xtype: 'numberfield',
                    id: 'pcin_diskon_amt_supp4_po',
                    readOnly: true,
                    allowBlank: true
                }
            },{
                dataIndex: 'diskon_amt_supp5_po',        
                hidden: true,
                editor: {
                    xtype: 'numberfield',
                    id: 'pcin_diskon_amt_supp5_po',
                    readOnly: true,
                    allowBlank: true
                }
            }]
			
			
    });
    
    var winpembeliancreateinvoiceprint = new Ext.Window({
        id: 'id_winpembeliancreateinvoiceprint',
        title: 'Print Create Invoice',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="pembeliancreateinvoiceprint" src=""></iframe>'
    });
    
    gridpembeliancreateinvoice.getSelectionModel().on('selectionchange', function(sm){
        gridpembeliancreateinvoice.removeBtn.setDisabled(sm.getCount() < 1);
    });
	
    var pembeliancreateinvoice = new Ext.FormPanel({
        id: 'pembeliancreateinvoice',
        border: false,
        frame: true,
        autoScroll:true,        
        bodyStyle:'padding-right:20px;',
        labelWidth: 130,
        items: [{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },                  
                items: [headerpembeliancreateinvoice]
            },
            gridpembeliancreateinvoice,
            {
                layout: 'column',
                border: false,
                items: [{
                        columnWidth: .6,
                        style:'margin:6px 3px 0 0;',
                        layout: 'form', 
                        labelWidth: 110
                        
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
                                        fieldLabel: 'Total',
                                        name: 'rp_jumlah',
                                        readOnly: true,                                 
                                        id: 'pcin_rp_jumlah',                                      
                                        anchor: '90%',      
                                        fieldClass:'readonly-input number', 
                                        selectOnFocus: true,	
                                        value:'0'
                                    },{
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'Diskon Rp',
                                        name: 'persen_diskon',
                                        id: 'pcin_persen_diskon',                                      
                                        anchor: '90%',
                                        fieldClass: 'number',
                                        selectOnFocus: true,	
                                        value:'0',
                                        listeners:{
                                            change: function(){
                                                var total = Ext.getCmp('pcin_rp_jumlah').getValue();
                                                var diskon = Ext.getCmp('pcin_persen_diskon').getValue();
                                                var afterDiskon = total - diskon;
                                                var pembulatan = Ext.getCmp('pcin_pembulatan').getValue();
                                                var rp_ppn = afterDiskon * (Ext.getCmp('pcin_ppn').getValue() / 100);
                                                var total_invoice = afterDiskon + rp_ppn;
                                                var grand_total = afterDiskon + rp_ppn + pembulatan;
														
                                                Ext.getCmp('pcin_rp_diskon').setValue(diskon);
                                                Ext.getCmp('pcin_rp_ppn').setValue(rp_ppn);
                                                Ext.getCmp('pcin_rp_total_grand').setValue(grand_total);
                                                Ext.getCmp('pcin_total_invoice').setValue(total_invoice);
                                                Ext.getCmp('pcin_dpp').setValue(afterDiskon);	
                                            }
                                        }
                                    },{
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'DPP',
                                        name: 'rp_dpp',                                                                        
                                        id: 'pcin_dpp',                                       
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
                                                id: 'pcin_ppn',
                                                fieldClass:'number',
                                                width: 60,
                                                value: '0',
                                                maxValue:100,
                                                listeners: {
                                                    'change': function(){
                                                        var total = Ext.getCmp('pcin_rp_jumlah').getValue();
                                                        var diskon = Ext.getCmp('pcin_persen_diskon').getValue();
                                                        var afterDiskon = total - diskon;
                                                        var pembulatan = Ext.getCmp('pcin_pembulatan').getValue();
                                                        var rp_ppn = afterDiskon * (Ext.getCmp('pcin_ppn').getValue() / 100);
                                                       
                                                        var total_invoice = afterDiskon + rp_ppn;
                                                        var grand_total = afterDiskon + rp_ppn + pembulatan;
														
                                                        Ext.getCmp('pcin_rp_diskon').setValue(diskon);
                                                        Ext.getCmp('pcin_rp_ppn').setValue(rp_ppn);
                                                        Ext.getCmp('pcin_rp_total_grand').setValue(grand_total);
                                                        Ext.getCmp('pcin_total_invoice').setValue(total_invoice);
                                                        Ext.getCmp('pcin_dpp').setValue(afterDiskon);	
                                                    }
                                                }
											   
                                            },
                                            {
                                                xtype: 'displayfield',
                                                value: '%',
                                                width: 17.5
                                            },
                                            {
                                                xtype: 'numericfield',
                                                name : 'rp_ppn',
                                                id : 'pcin_rp_ppn',
                                                currencySymbol:'',
                                                fieldClass:'readonly-input number',
                                                readOnly: true, 
                                                width: 120,
                                                anchor: '90%'
                                               
                                            }
                                        ]
                                    },{
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'Total Invoice',
                                        name: 'total_invoice',                                                                        
                                        id: 'pcin_total_invoice',                                       
                                        anchor: '90%',  
                                        readOnly: true, 
                                        cls:'vertical-space',
                                        fieldClass:'readonly-input number',
                                        labelStyle:'margin-top:10px;',      
                                        value:'0'                                                                                      
                                    },{
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: 'Pembulatan',
                                        name: 'pembulatan',
                                        id: 'pcin_pembulatan',    
                                        fieldClass:'number',                                  
                                        anchor: '90%',      
                                        value:'0',
                                        listeners:{
                                            'change': function(){
                                                var total = Ext.getCmp('pcin_rp_jumlah').getValue();
                                                var diskon = Ext.getCmp('pcin_persen_diskon').getValue();
                                                var afterDiskon = total - diskon;
                                                var pembulatan = Ext.getCmp('pcin_pembulatan').getValue();
                                                var rp_ppn = afterDiskon * (Ext.getCmp('pcin_ppn').getValue() / 100);
                                                var total_invoice = afterDiskon + rp_ppn;
                                                var grand_total = afterDiskon + rp_ppn + pembulatan;
														
                                                Ext.getCmp('pcin_rp_diskon').setValue(diskon);
                                                Ext.getCmp('pcin_rp_ppn').setValue(rp_ppn);
                                                Ext.getCmp('pcin_rp_total_grand').setValue(grand_total);
                                                Ext.getCmp('pcin_total_invoice').setValue(total_invoice);
                                                Ext.getCmp('pcin_dpp').setValue(afterDiskon);		
                                            }
                                        }
                                    },{
                                        xtype: 'numericfield',
                                        currencySymbol: '',
                                        fieldLabel: '<b>Grand Total</b>',
                                        name: 'rp_total_grand',
                                        cls:'vertical-space',
                                        readOnly: true,                                 
                                        id: 'pcin_rp_total_grand',                                        
                                        anchor: '90%',  
                                        fieldClass:'readonly-input bold-input number',  
                                        labelStyle:'margin-top:10px;',  
                                        value:'0'                                                                                                                            
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
                    if (Ext.getCmp('pci_status_pkp').getValue() === 'YA' && Ext.getCmp('pci_no_faktur_pajak').getValue() === '' ) {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'No Faktur Pajak Harus Di Isi,Status Supplier PKP!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK

                        });
                        return;
                    }
                     if (Ext.getCmp('pci_no_bukti_supplier').getValue() === '' ) {
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'No Bukti Supplier Harus Diisi!',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK

                        });
                        return;
                    }
                    var pembeliancreateinvoice = new Array();				
                    strpembeliancreateinvoice.each(function(node){
                        pembeliancreateinvoice.push(node.data);
                    });
                    Ext.getCmp('pembeliancreateinvoice').getForm().submit({
                        url: '<?= site_url("pembelian_create_invoice/update_row") ?>',
                        scope: this,
                        params: {
                            detail: Ext.util.JSON.encode(pembeliancreateinvoice)
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
									
                                    winpembeliancreateinvoiceprint.show();
                                    Ext.getDom('pembeliancreateinvoiceprint').src = r.printUrl;
                                }
                            });			            
			            
                            clearpembeliancreateinvoice();						
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
            },{
                text: 'Reset',
                handler: function(){
                    clearpembeliancreateinvoice();
                }
            }]
    });
    
    pembeliancreateinvoice.on('afterrender', function(){
        this.getForm().load({
            url: '<?= site_url("pembelian_create_invoice/get_form") ?>',
            success: function(form, action){
                var r = Ext.util.JSON.decode(action.response.responseText);
                if(r.data.user_peruntukan === "0"){
                    Ext.getCmp('pcin_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('pcin_peruntukan_supermarket').show();
                    Ext.getCmp('pcin_peruntukan_distribusi').hide();
                }else if(r.data.user_peruntukan === "1"){
                    Ext.getCmp('pcin_peruntukan_distribusi').setValue(true);
                    Ext.getCmp('pcin_peruntukan_supermarket').hide();
                    Ext.getCmp('pcin_peruntukan_distribusi').show();
                }else{
                    Ext.getCmp('pcin_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('pcin_peruntukan_supermarket').show();
                    Ext.getCmp('pcin_peruntukan_distribusi').show();
                }
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
                        if (btn === 'ok' && de.errMsg === 'Session Expired') {
                            window.location = '<?= site_url("auth/login") ?>';
                        }
                    }
                });
            }
        });
    });
	
    function clearpembeliancreateinvoice(){
        Ext.getCmp('pembeliancreateinvoice').getForm().reset();
        Ext.getCmp('pembeliancreateinvoice').getForm().load({
            url: '<?= site_url("pembelian_create_invoice/get_form") ?>',
            success: function(form, action){
                var r = Ext.util.JSON.decode(action.response.responseText);
                if(r.data.user_peruntukan === "0"){
                    Ext.getCmp('pcin_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('pcin_peruntukan_supermarket').show();
                    Ext.getCmp('pcin_peruntukan_distribusi').hide();
                }else if(r.data.user_peruntukan === "1"){
                    Ext.getCmp('pcin_peruntukan_distribusi').setValue(true);
                    Ext.getCmp('pcin_peruntukan_supermarket').hide();
                    Ext.getCmp('pcin_peruntukan_distribusi').show();
                }else{
                    Ext.getCmp('pcin_peruntukan_supermarket').setValue(true);
                    Ext.getCmp('pcin_peruntukan_supermarket').show();
                    Ext.getCmp('pcin_peruntukan_distribusi').show();
                }
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
                        if (btn === 'ok' && de.errMsg === 'Session Expired') {
                            window.location = '<?= site_url("auth/login") ?>';
                        }
                    }
                });
            }
        });
        strpembeliancreateinvoice.removeAll();
    }
     function cleartotal(){
        Ext.getCmp('pcin_rp_jumlah').setValue('0');
        Ext.getCmp('pcin_persen_diskon').setValue('0');
        Ext.getCmp('pcin_dpp').setValue('0');
        Ext.getCmp('pcin_rp_ppn').setValue('0');
        Ext.getCmp('pcin_total_invoice').setValue('0');
        Ext.getCmp('pcin_pembulatan').setValue('0');
        Ext.getCmp('pcin_rp_total_grand').setValue('0');
        
    }
</script>
