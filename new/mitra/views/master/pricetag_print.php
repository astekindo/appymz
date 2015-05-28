<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript"> 
	
    /** START TWIN COMBO PRODUK 1 **/
    var strcbptpproduk = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data : []
    });
	
    var strgridptpproduk = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_produk','nama_produk','net_harga_jual','rp_jual_supermarket', 'nm_satuan'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pricetag_print/search_produk") ?>',
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
	
    strgridptpproduk.on('load', function(){
        Ext.getCmp('id_searchgridptpproduk').focus();
    });
	
    var searchgridptpproduk = new Ext.app.SearchField({
        store: strgridptpproduk,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridptpproduk'
    });
	
    var gridptpproduk = new Ext.grid.GridPanel({
        store: strgridptpproduk,
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
                width: 250,
                sortable: true			
            
            },{
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 100,
                sortable: true		
            
            },{
                header: 'Harga Jual',
                dataIndex: 'rp_jual_supermarket',
                width: 130,
                sortable: true		
            
            },{
                header: 'Net Harga Jual',
                dataIndex: 'net_harga_jual',
                width: 130,
                sortable: true		
            
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridptpproduk]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridptpproduk,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                    Ext.getCmp('id_cbptpproduk').setValue(sel[0].get('kd_produk'));
                    Ext.getCmp('ptp_harga_jual').setValue(sel[0].get('rp_jual_supermarket'));
                    Ext.getCmp('ptp_nama_produk1').setValue(sel[0].get('nama_produk'));
                    Ext.getCmp('ptp_nm_satuan1').setValue(sel[0].get('nm_satuan'));
                    Ext.getCmp('ptp_rp_coret').setValue(sel[0].get('net_harga_jual'));
                    menuptpproduk.hide();
                }
            }
        }
    });
	
    var menuptpproduk = new Ext.menu.Menu();
    menuptpproduk.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 600,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridptpproduk],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menuptpproduk.hide();
                }
            }]
    }));
    
    Ext.ux.TwinComboptpproduk = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgridptpproduk.load();
            menuptpproduk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menuptpproduk.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridptpproduk').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgridptpproduk').setValue('');
            searchgridptpproduk.onTrigger2Click();
        }
    });
	
    var cbptpproduk = new Ext.ux.TwinComboptpproduk({
        fieldLabel: 'Kode Produk',
        id: 'id_cbptpproduk',
        store: strcbptpproduk,
        mode: 'local',
        valueField: 'kd_produk',
        displayField: 'kd_produk',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_produk',
        emptyText: 'Pilih Produk'
    });
	
    /** END TWIN COMBO PRODUK 1 **/
	
    /** START TWIN COMBO PRODUK 2 **/
    var strcbptpproduk2 = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data : []
    });
	
    var strgridptpproduk2 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_produk','nama_produk','net_harga_jual','rp_jual_supermarket', 'nm_satuan'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pricetag_print/search_produk") ?>',
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
	
    strgridptpproduk2.on('load', function(){
        Ext.getCmp('id_searchgridptpproduk2').focus();
    });
	
    var searchgridptpproduk2 = new Ext.app.SearchField({
        store: strgridptpproduk2,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridptpproduk2'
    });
	
    var gridptpproduk2 = new Ext.grid.GridPanel({
        store: strgridptpproduk2,
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
                width: 130,
                sortable: true		
            
            },{
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 100,
                sortable: true		
            
            },{
                header: 'Harga Jual',
                dataIndex: 'rp_jual_supermarket',
                width: 130,
                sortable: true		
            
            },{
                header: 'Net Harga Jual',
                dataIndex: 'net_harga_jual',
                width: 130,
                sortable: true		
            
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridptpproduk2]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridptpproduk2,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                    Ext.getCmp('id_cbptpproduk2').setValue(sel[0].get('kd_produk'));
                    Ext.getCmp('ptp_harga_jual2').setValue(sel[0].get('rp_jual_supermarket'));
                    Ext.getCmp('ptp_nama_produk2').setValue(sel[0].get('nama_produk'));
                    Ext.getCmp('ptp_nm_satuan2').setValue(sel[0].get('nm_satuan'));
                    Ext.getCmp('ptp_rp_coret2').setValue(sel[0].get('net_harga_jual'));     
                    menuptpproduk2.hide();
                }
            }
        }
    });
	
    var menuptpproduk2 = new Ext.menu.Menu();
    menuptpproduk2.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 600,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridptpproduk2],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menuptpproduk2.hide();
                }
            }]
    }));
    
    Ext.ux.TwinComboptpproduk2 = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgridptpproduk2.load();
            menuptpproduk2.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menuptpproduk2.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridptpproduk2').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgridptpproduk2').setValue('');
            searchgridptpproduk2.onTrigger2Click();
        }
    });
	
    var cbptpproduk2 = new Ext.ux.TwinComboptpproduk2({
        fieldLabel: 'Kode Produk',
        id: 'id_cbptpproduk2',
        store: strcbptpproduk2,
        mode: 'local',
        valueField: 'kd_produk',
        displayField: 'kd_produk',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_produk2',
        emptyText: 'Pilih Produk'
    });
	
    /** END TWIN COMBO PRODUK 2 **/
	
    /** START TWIN COMBO PRODUK 3 **/
    var strcbptpproduk3 = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data : []
    });
	
    var strgridptpproduk3 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_produk','nama_produk','net_harga_jual','rp_jual_supermarket', 'nm_satuan'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pricetag_print/search_produk") ?>',
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
	
    strgridptpproduk3.on('load', function(){
        Ext.getCmp('id_searchgridptpproduk3').focus();
    });
	
    var searchgridptpproduk3 = new Ext.app.SearchField({
        store: strgridptpproduk3,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridptpproduk3'
    });
	
    var gridptpproduk3 = new Ext.grid.GridPanel({
        store: strgridptpproduk3,
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
                width: 130,
                sortable: true			
            
            },{
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 100,
                sortable: true		
            
            },{
                header: 'Harga Jual',
                dataIndex: 'rp_jual_supermarket',
                width: 130,
                sortable: true			
            
            },{
                header: 'Net Harga Jual',
                dataIndex: 'net_harga_jual',
                width: 130,
                sortable: true		
            
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridptpproduk3]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridptpproduk3,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                    Ext.getCmp('id_cbptpproduk3').setValue(sel[0].get('kd_produk'));
                    Ext.getCmp('ptp_harga_jual3').setValue(sel[0].get('rp_jual_supermarket'));
                    Ext.getCmp('ptp_nama_produk3').setValue(sel[0].get('nama_produk'));
                    Ext.getCmp('ptp_nm_satuan3').setValue(sel[0].get('nm_satuan'));
                    Ext.getCmp('ptp_rp_coret3').setValue(sel[0].get('net_harga_jual'));     
                    menuptpproduk3.hide();
                }
            }
        }
    });
	
    var menuptpproduk3 = new Ext.menu.Menu();
    menuptpproduk3.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 600,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridptpproduk3],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menuptpproduk3.hide();
                }
            }]
    }));
    
    Ext.ux.TwinComboptpproduk3 = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgridptpproduk3.load();
            menuptpproduk3.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menuptpproduk3.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridptpproduk3').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgridptpproduk3').setValue('');
            searchgridptpproduk3.onTrigger2Click();
        }
    });
	
    var cbptpproduk3 = new Ext.ux.TwinComboptpproduk3({
        fieldLabel: 'Kode Produk',
        id: 'id_cbptpproduk3',
        store: strcbptpproduk3,
        mode: 'local',
        valueField: 'kd_produk',
        displayField: 'kd_produk',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_produk3',
        emptyText: 'Pilih Produk'
    });
	
    /** END TWIN COMBO PRODUK 3 **/
	
    /** START TWIN COMBO PRODUK 4 **/
    var strcbptpproduk4 = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data : []
    });
	
    var strgridptpproduk4 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_produk','nama_produk','net_harga_jual','rp_jual_supermarket', 'nm_satuan'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pricetag_print/search_produk") ?>',
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
	
    strgridptpproduk4.on('load', function(){
        Ext.getCmp('id_searchgridptpproduk4').focus();
    });
	
    var searchgridptpproduk4 = new Ext.app.SearchField({
        store: strgridptpproduk4,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridptpproduk4'
    });
	
    var gridptpproduk4 = new Ext.grid.GridPanel({
        store: strgridptpproduk4,
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
                width: 130,
                sortable: true			
            
            },{
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 100,
                sortable: true		
            
            },{
                header: 'Harga Jual',
                dataIndex: 'rp_jual_supermarket',
                width: 130,
                sortable: true			
            
            },{
                header: 'Net Harga Jual',
                dataIndex: 'net_harga_jual',
                width: 130,
                sortable: true		
            
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridptpproduk4]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridptpproduk4,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                    Ext.getCmp('id_cbptpproduk4').setValue(sel[0].get('kd_produk'));
                    Ext.getCmp('ptp_harga_jual4').setValue(sel[0].get('rp_jual_supermarket'));
                    Ext.getCmp('ptp_nama_produk4').setValue(sel[0].get('nama_produk'));
                    Ext.getCmp('ptp_nm_satuan4').setValue(sel[0].get('nm_satuan'));
                    Ext.getCmp('ptp_rp_coret4').setValue(sel[0].get('net_harga_jual'));     
                    menuptpproduk4.hide();
                }
            }
        }
    });
	
    var menuptpproduk4 = new Ext.menu.Menu();
    menuptpproduk4.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 600,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridptpproduk4],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menuptpproduk4.hide();
                }
            }]
    }));
    
    Ext.ux.TwinComboptpproduk4 = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgridptpproduk4.load();
            menuptpproduk4.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menuptpproduk4.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridptpproduk4').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgridptpproduk4').setValue('');
            searchgridptpproduk4.onTrigger2Click();
        }
    });
	
    var cbptpproduk4 = new Ext.ux.TwinComboptpproduk4({
        fieldLabel: 'Kode Produk',
        id: 'id_cbptpproduk4',
        store: strcbptpproduk4,
        mode: 'local',
        valueField: 'kd_produk',
        displayField: 'kd_produk',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_produk4',
        emptyText: 'Pilih Produk'
    });
	
    /** END TWIN COMBO PRODUK 4 **/
	
    /** START TWIN COMBO PRODUK 5 **/
    var strcbptpproduk5 = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data : []
    });
	
    var strgridptpproduk5 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_produk','nama_produk','net_harga_jual','rp_jual_supermarket', 'nm_satuan'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pricetag_print/search_produk") ?>',
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
	
    strgridptpproduk5.on('load', function(){
        Ext.getCmp('id_searchgridptpproduk5').focus();
    });
	
    var searchgridptpproduk5 = new Ext.app.SearchField({
        store: strgridptpproduk5,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridptpproduk5'
    });
	
    var gridptpproduk5 = new Ext.grid.GridPanel({
        store: strgridptpproduk5,
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
                width: 130,
                sortable: true			
            
            },{
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 100,
                sortable: true		
            
            },{
                header: 'Harga Jual',
                dataIndex: 'rp_jual_supermarket',
                width: 130,
                sortable: true			
            
            },{
                header: 'Net Harga Jual',
                dataIndex: 'net_harga_jual',
                width: 130,
                sortable: true		
            
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridptpproduk5]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridptpproduk5,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                    Ext.getCmp('id_cbptpproduk5').setValue(sel[0].get('kd_produk'));
                    Ext.getCmp('ptp_harga_jual5').setValue(sel[0].get('rp_jual_supermarket'));
                    Ext.getCmp('ptp_nama_produk5').setValue(sel[0].get('nama_produk'));
                    Ext.getCmp('ptp_nm_satuan5').setValue(sel[0].get('nm_satuan'));
                    Ext.getCmp('ptp_rp_coret5').setValue(sel[0].get('net_harga_jual'));     
                    menuptpproduk5.hide();
                }
            }
        }
    });
	
    var menuptpproduk5 = new Ext.menu.Menu();
    menuptpproduk5.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 600,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridptpproduk5],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menuptpproduk5.hide();
                }
            }]
    }));
    
    Ext.ux.TwinComboptpproduk5 = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgridptpproduk5.load();
            menuptpproduk5.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menuptpproduk5.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridptpproduk5').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgridptpproduk5').setValue('');
            searchgridptpproduk5.onTrigger2Click();
        }
    });
	
    var cbptpproduk5 = new Ext.ux.TwinComboptpproduk5({
        fieldLabel: 'Kode Produk',
        id: 'id_cbptpproduk5',
        store: strcbptpproduk5,
        mode: 'local',
        valueField: 'kd_produk',
        displayField: 'kd_produk',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_produk5',
        emptyText: 'Pilih Produk'
    });
	
    /** END TWIN COMBO PRODUK 5 **/
	
    /** START TWIN COMBO PRODUK 6 **/
    var strcbptpproduk6 = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data : []
    });
	
    var strgridptpproduk6 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_produk','nama_produk','net_harga_jual','rp_jual_supermarket', 'nm_satuan'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pricetag_print/search_produk") ?>',
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
	
    strgridptpproduk6.on('load', function(){
        Ext.getCmp('id_searchgridptpproduk6').focus();
    });
	
    var searchgridptpproduk6 = new Ext.app.SearchField({
        store: strgridptpproduk6,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridptpproduk6'
    });
	
    var gridptpproduk6 = new Ext.grid.GridPanel({
        store: strgridptpproduk6,
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
                width: 130,
                sortable: true			
            
            },{
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 100,
                sortable: true		
            
            },{
                header: 'Harga Jual',
                dataIndex: 'rp_jual_supermarket',
                width: 130,
                sortable: true			
            
            },{
                header: 'Net Harga Jual',
                dataIndex: 'net_harga_jual',
                width: 130,
                sortable: true		
            
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridptpproduk6]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridptpproduk6,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                    Ext.getCmp('id_cbptpproduk6').setValue(sel[0].get('kd_produk'));
                    Ext.getCmp('ptp_harga_jual6').setValue(sel[0].get('rp_jual_supermarket'));
                    Ext.getCmp('ptp_nama_produk6').setValue(sel[0].get('nama_produk'));
                    Ext.getCmp('ptp_nm_satuan6').setValue(sel[0].get('nm_satuan'));
                    Ext.getCmp('ptp_rp_coret6').setValue(sel[0].get('net_harga_jual'));     
                    menuptpproduk6.hide();
                }
            }
        }
    });
	
    var menuptpproduk6 = new Ext.menu.Menu();
    menuptpproduk6.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 600,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridptpproduk6],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menuptpproduk6.hide();
                }
            }]
    }));
    
    Ext.ux.TwinComboptpproduk6 = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgridptpproduk6.load();
            menuptpproduk6.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menuptpproduk6.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridptpproduk6').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgridptpproduk6').setValue('');
            searchgridptpproduk6.onTrigger2Click();
        }
    });
	
    var cbptpproduk6 = new Ext.ux.TwinComboptpproduk6({
        fieldLabel: 'Kode Produk',
        id: 'id_cbptpproduk6',
        store: strcbptpproduk6,
        mode: 'local',
        valueField: 'kd_produk',
        displayField: 'kd_produk',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_produk6',
        emptyText: 'Pilih Produk'
    });
	
    /** END TWIN COMBO PRODUK 6 **/
	
    /** START TWIN COMBO PRODUK 7 **/
    var strcbptpproduk7 = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data : []
    });
	
    var strgridptpproduk7 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_produk','nama_produk','net_harga_jual','rp_jual_supermarket', 'nm_satuan'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pricetag_print/search_produk") ?>',
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
	
    strgridptpproduk7.on('load', function(){
        Ext.getCmp('id_searchgridptpproduk7').focus();
    });
	
    var searchgridptpproduk7 = new Ext.app.SearchField({
        store: strgridptpproduk7,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridptpproduk7'
    });
	
    var gridptpproduk7 = new Ext.grid.GridPanel({
        store: strgridptpproduk7,
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
                width: 130,
                sortable: true			
            
            },{
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 100,
                sortable: true		
            
            },{
                header: 'Harga Jual',
                dataIndex: 'rp_jual_supermarket',
                width: 130,
                sortable: true			
            
            },{
                header: 'Net Harga Jual',
                dataIndex: 'net_harga_jual',
                width: 130,
                sortable: true		
            
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridptpproduk7]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridptpproduk7,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                    Ext.getCmp('id_cbptpproduk7').setValue(sel[0].get('kd_produk'));
                    Ext.getCmp('ptp_harga_jual7').setValue(sel[0].get('rp_jual_supermarket'));
                    Ext.getCmp('ptp_nama_produk7').setValue(sel[0].get('nama_produk'));
                    Ext.getCmp('ptp_nm_satuan7').setValue(sel[0].get('nm_satuan'));
                    Ext.getCmp('ptp_rp_coret7').setValue(sel[0].get('net_harga_jual'));     
                    menuptpproduk7.hide();
                }
            }
        }
    });
	
    var menuptpproduk7 = new Ext.menu.Menu();
    menuptpproduk7.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 600,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridptpproduk7],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menuptpproduk7.hide();
                }
            }]
    }));
    
    Ext.ux.TwinComboptpproduk7 = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgridptpproduk7.load();
            menuptpproduk7.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menuptpproduk7.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridptpproduk7').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgridptpproduk7').setValue('');
            searchgridptpproduk7.onTrigger2Click();
        }
    });
	
    var cbptpproduk7 = new Ext.ux.TwinComboptpproduk7({
        fieldLabel: 'Kode Produk',
        id: 'id_cbptpproduk7',
        store: strcbptpproduk7,
        mode: 'local',
        valueField: 'kd_produk',
        displayField: 'kd_produk',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_produk7',
        emptyText: 'Pilih Produk'
    });
	
    /** END TWIN COMBO PRODUK 7 **/
	
    /** START TWIN COMBO PRODUK 8 **/
    var strcbptpproduk8 = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data : []
    });
	
    var strgridptpproduk8 = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_produk','nama_produk','net_harga_jual','rp_jual_supermarket', 'nm_satuan'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("pricetag_print/search_produk") ?>',
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
	
    strgridptpproduk8.on('load', function(){
        Ext.getCmp('id_searchgridptpproduk8').focus();
    });
	
    var searchgridptpproduk8 = new Ext.app.SearchField({
        store: strgridptpproduk8,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE			
        },
        width: 350,
        id: 'id_searchgridptpproduk8'
    });
	
    var gridptpproduk8 = new Ext.grid.GridPanel({
        store: strgridptpproduk8,
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
                width: 130,
                sortable: true			
            
            },{
                header: 'Satuan',
                dataIndex: 'nm_satuan',
                width: 100,
                sortable: true		
            
            },{
                header: 'Harga Jual',
                dataIndex: 'rp_jual_supermarket',
                width: 130,
                sortable: true			
            
            },{
                header: 'Net Harga Jual',
                dataIndex: 'net_harga_jual',
                width: 130,
                sortable: true		
            
            }],
        tbar: new Ext.Toolbar({
            items: [searchgridptpproduk8]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridptpproduk8,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){			
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {				
                    Ext.getCmp('id_cbptpproduk8').setValue(sel[0].get('kd_produk'));
                    Ext.getCmp('ptp_harga_jual8').setValue(sel[0].get('rp_jual_supermarket'));
                    Ext.getCmp('ptp_nama_produk8').setValue(sel[0].get('nama_produk'));
                    Ext.getCmp('ptp_nm_satuan8').setValue(sel[0].get('nm_satuan'));
                    Ext.getCmp('ptp_rp_coret8').setValue(sel[0].get('net_harga_jual'));     
                    menuptpproduk8.hide();
                }
            }
        }
    });
	
    var menuptpproduk8 = new Ext.menu.Menu();
    menuptpproduk8.add(new Ext.Panel({
        title: 'Pilih Supplier',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 600,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridptpproduk8],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menuptpproduk8.hide();
                }
            }]
    }));
    
    Ext.ux.TwinComboptpproduk8 = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgridptpproduk8.load();
            menuptpproduk8.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });
	
    menuptpproduk8.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridptpproduk8').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgridptpproduk8').setValue('');
            searchgridptpproduk8.onTrigger2Click();
        }
    });
	
    var cbptpproduk8 = new Ext.ux.TwinComboptpproduk8({
        fieldLabel: 'Kode Produk',
        id: 'id_cbptpproduk8',
        store: strcbptpproduk8,
        mode: 'local',
        valueField: 'kd_produk',
        displayField: 'kd_produk',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_produk8',
        emptyText: 'Pilih Produk'
    });
	
    /** END TWIN COMBO PRODUK 8 **/
		   
    var headerpricetagprint = {
        layout: 'column',
        border: false,
        items: [{
                columnWidth: .25,
                layout: 'form',
                border: true,
                labelWidth: 70,
                defaults: { labelSeparator: ''},
                items: [cbptpproduk,cbptpproduk2,cbptpproduk3,cbptpproduk4,
                    cbptpproduk5,cbptpproduk6,cbptpproduk7,cbptpproduk8,
                    new Ext.form.Checkbox({
                        xtype: 'checkbox',
                        fieldLabel: 'Cetak Kecil',
                        boxLabel:'Ya',
                        name:'cetak_kecil',
                        id:'ptp_cetak_kecil',
                        // checked: true,
                        inputValue: '1',
                        autoLoad : true,
                        checked : true
                    }),new Ext.form.Checkbox({
                        xtype: 'checkbox',
                        fieldLabel: 'Cetak Besar',
                        boxLabel:'Ya',
                        name:'cetak_besar',
                        id:'ptp_cetak_besar',
                        // checked: true,
                        inputValue: '1',
                        autoLoad : true,
                        checked : true
                    }),new Ext.form.Checkbox({
                        xtype: 'checkbox',
                        fieldLabel: 'Rp Coret',
                        boxLabel:'Ya',
                        name:'chk_rp_coret',
                        id:'chk_rp_coret',
                        // checked: true,
                        inputValue: '1',
                        autoLoad : true,
                        checked : true
                    }),{
                            xtype: 'label',
                            html: ' <br/>' +
                                '<div style="margin-top: 15px">*) Kode Produk Tidak Muncul Apabila Status Barang Tidak Aktif<br/>' +
                                '</div>',
                            style: 'margin-left: 100px'
                        }
                ]
            },{
                columnWidth: .35,
                layout: 'form',
                border: false,
                defaults: { labelSeparator: ''},
                labelWidth: 0,
                items: [
                    {
                        xtype: 'textfield', 
                        id: 'ptp_nama_produk1', 
                        name: 'nama_produk1',
                        anchor: '90%',
                        fieldClass:'readonly-input',
                        readOnly: true
                    },
                    {
                        xtype: 'textfield',
                        id: 'ptp_nama_produk2',   
                        name: 'nama_produk2',
                        anchor: '90%',
                        fieldClass:'readonly-input',
                        readOnly: true
                    },
                    {
                        xtype: 'textfield',
                        id: 'ptp_nama_produk3',
                        name: 'nama_produk3',
                        anchor: '90%',
                        fieldClass:'readonly-input',
                        readOnly: true
                    },
                    {
                        xtype: 'textfield',
                        id: 'ptp_nama_produk4',  
                        name: 'nama_produk4',
                        anchor: '90%',
                        fieldClass:'readonly-input',
                        readOnly: true
                    },
                    {
                        xtype: 'textfield',
                        id: 'ptp_nama_produk5', 
                        name: 'nama_produk5',
                        anchor: '90%',
                        fieldClass:'readonly-input',
                        readOnly: true
                    },
                    {
                        xtype: 'textfield',
                        id: 'ptp_nama_produk6',  
                        name: 'nama_produk6',
                        anchor: '90%',
                        fieldClass:'readonly-input',
                        readOnly: true
                    },
                    {
                        xtype: 'textfield',
                        id: 'ptp_nama_produk7', 
                        name: 'nama_produk7',
                        anchor: '90%',
                        fieldClass:'readonly-input',
                        readOnly: true
                    },
                    {
                        xtype: 'textfield',
                        id: 'ptp_nama_produk8', 
                        name: 'nama_produk8',
                        anchor: '90%',
                        fieldClass:'readonly-input',
                        readOnly: true
                    }
                ]
            },{
                columnWidth: .2,
                layout: 'form',
                border: false,
                labelWidth: 70,
                defaults: { labelSeparator: ''},
                items: [{
                        xtype: 'numberfield',
                        fieldLabel: 'Rp Coret',
                        name: 'rp_coret',
                        id: 'ptp_rp_coret',                
                        anchor: '90%',
                        value:'0',
                        fieldClass:'readonly-input number',
                        readOnly: true
                    },{
                        xtype: 'numberfield',
                        fieldLabel: 'Rp Coret',
                        name: 'rp_coret2',
                        id: 'ptp_rp_coret2',                
                        anchor: '90%',
                        value:'0',
                        fieldClass:'readonly-input number',
                        readOnly: true
                    },{
                        xtype: 'numberfield',
                        fieldLabel: 'Rp Coret',
                        name: 'rp_coret3',
                        id: 'ptp_rp_coret3',                
                        anchor: '90%',
                        value:'0',
                        fieldClass:'readonly-input number',
                        readOnly: true
                    },{
                        xtype: 'numberfield',
                        fieldLabel: 'Rp Coret',
                        name: 'rp_coret4',
                        id: 'ptp_rp_coret4',                
                        anchor: '90%',
                        value:'0',
                        fieldClass:'readonly-input number',
                        readOnly: true
                    },{
                        xtype: 'numberfield',
                        fieldLabel: 'Rp Coret',
                        name: 'rp_coret5',
                        id: 'ptp_rp_coret5',                
                        anchor: '90%',
                        value:'0',
                        fieldClass:'readonly-input number',
                        readOnly: true
                    },{
                        xtype: 'numberfield',
                        fieldLabel: 'Rp Coret',
                        name: 'rp_coret6',
                        id: 'ptp_rp_coret6',                
                        anchor: '90%',
                        value:'0',
                        fieldClass:'readonly-input number',
                        readOnly: true
                    },{
                        xtype: 'numberfield',
                        fieldLabel: 'Rp Coret',
                        name: 'rp_coret7',
                        id: 'ptp_rp_coret7',                
                        anchor: '90%',
                        value:'0',
                        fieldClass:'readonly-input number',
                        readOnly: true
                    },{
                        xtype: 'numberfield',
                        fieldLabel: 'Rp Coret',
                        name: 'rp_coret8',
                        id: 'ptp_rp_coret8',                
                        anchor: '90%',
                        value:'0',
                        fieldClass:'readonly-input number',
                        readOnly: true
                    }
                ]
            },{
                columnWidth: .2,
                layout: 'form',
                border: false,
                labelWidth: 70,
                defaults: { labelSeparator: ''},
                items: [
                    {
                        xtype: 'numberfield',
                        name: 'harga_jual',
                        id: 'ptp_harga_jual',                
                        anchor: '90%',
                        value:'0',
                        fieldClass:'readonly-input number',
                        readOnly: true
                    },{
                        xtype: 'numberfield',
                        name: 'harga_jual2',
                        id: 'ptp_harga_jual2',                
                        anchor: '90%',
                        value:'0',
                        fieldClass:'readonly-input number',
                        readOnly: true
                    },{
                        xtype: 'numberfield',
                        name: 'harga_jual3',
                        id: 'ptp_harga_jual3',                
                        anchor: '90%',
                        value:'0',
                        fieldClass:'readonly-input number',
                        readOnly: true
                    },{
                        xtype: 'numberfield',
                        name: 'harga_jual4',
                        id: 'ptp_harga_jual4',                
                        anchor: '90%',
                        value:'0',
                        fieldClass:'readonly-input number',
                        readOnly: true
                    },{
                        xtype: 'numberfield',
                        name: 'harga_jual5',
                        id: 'ptp_harga_jual5',                
                        anchor: '90%',
                        value:'0',
                        fieldClass:'readonly-input number',
                        readOnly: true
                    },{
                        xtype: 'numberfield',
                        name: 'harga_jual6',
                        id: 'ptp_harga_jual6',                
                        anchor: '90%',
                        value:'0',
                        fieldClass:'readonly-input number',
                        readOnly: true
                    },{
                        xtype: 'numberfield',
                        name: 'harga_jual7',
                        id: 'ptp_harga_jual7',                
                        anchor: '90%',
                        value:'0',
                        fieldClass:'readonly-input number',
                        readOnly: true
                    },{
                        xtype: 'numberfield',
                        name: 'harga_jual8',
                        id: 'ptp_harga_jual8',                
                        anchor: '90%',
                        value:'0',
                        fieldClass:'readonly-input number',
                        readOnly: true
                    }
                ]
            },{
                columnWidth: .2,
                layout: 'form',
                border: false,
                labelWidth: 70,
                defaults: { labelSeparator: ''},
                items: [
                    {
                        xtype: 'textfield',
                        hidden: true,
                        name: 'nm_satuan1',
                        id: 'ptp_nm_satuan1',                
                        anchor: '90%',
                        fieldClass:'readonly-input number',
                        readOnly: true
                    },{
                        xtype: 'textfield',
                        hidden: true,
                        name: 'nm_satuan2',
                        id: 'ptp_nm_satuan2',                
                        anchor: '90%',
                        fieldClass:'readonly-input number',
                        readOnly: true
                    },{
                        xtype: 'textfield',
                        hidden: true,
                        name: 'nm_satuan3',
                        id: 'ptp_nm_satuan3',                
                        anchor: '90%',
                        fieldClass:'readonly-input number',
                        readOnly: true
                    },{
                        xtype: 'textfield',
                        hidden: true,
                        name: 'nm_satuan4',
                        id: 'ptp_nm_satuan4',                
                        anchor: '90%',
                        fieldClass:'readonly-input number',
                        readOnly: true
                    },{
                        xtype: 'textfield',
                        hidden: true,
                        name: 'nm_satuan5',
                        id: 'ptp_nm_satuan5',                
                        anchor: '90%',
                        fieldClass:'readonly-input number',
                        readOnly: true
                    },{
                        xtype: 'textfield',
                        hidden: true,
                        name: 'nm_satuan6',
                        id: 'ptp_nm_satuan6',                
                        anchor: '90%',
                        fieldClass:'readonly-input number',
                        readOnly: true
                    },{
                        xtype: 'textfield',
                        hidden: true,
                        name: 'nm_satuan7',
                        id: 'ptp_nm_satuan7',                
                        anchor: '90%',
                        fieldClass:'readonly-input number',
                        readOnly: true
                    },{
                        xtype: 'textfield',
                        hidden: true,
                        name: 'nm_satuan8',
                        id: 'ptp_nm_satuan8',                
                        anchor: '90%',
                        fieldClass:'readonly-input number',
                        readOnly: true
                    }
                ]
            }   
        ]
    }
	
	
    var winprintpricetagprint = new Ext.Window({
        id: 'id_winprintpricetagprint',
        title: 'Print Price Tag Form',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="printpricetagprint" src=""></iframe>'
    });
	
    var pricetagprint = new Ext.FormPanel({
        id: 'pricetagprint',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },					
                items: [headerpricetagprint]
            }
				
        ],
        buttons: [{
                text: 'Cetak',
                handler: function(){
                    Ext.getCmp('pricetagprint').getForm().submit({
                        url: '<?= site_url("pricetag_print/submit") ?>',
                        scope: this,
                        // waitMsg: 'Saving Data...',
                        success: function(form, action){
                            var r = Ext.util.JSON.decode(action.response.responseText);
                            winprintpricetagprint.show();
                            Ext.getDom('printpricetagprint').src = r.printUrl;                 
								
                            clearpricetagprint();                       
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
                            Ext.MessageBox.getDialog().getEl().setStyle('z-index','80000'); 
                        }                   
                    }); 
                }
            },{
                text: 'Reset',
                handler: function(){
                    clearpricetagprint(); 
                }
            }]
    });
		
    function clearpricetagprint(){
        Ext.getCmp('pricetagprint').getForm().reset();
    }
</script>