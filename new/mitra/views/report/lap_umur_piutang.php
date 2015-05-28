<?php if (!defined('BASEPATH')) exit('No direct script access allowed'); ?>
<script type="text/javascript">

// -------- COMBOBOX NO SO --------------------
    var smgridLUPNoStruk = new Ext.grid.CheckboxSelectionModel();

    var strCbLUPNoStruk = new Ext.data.ArrayStore({
        fields: ['no_so'],
        data : []
    });

    var strgridLUPNoStruk = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_so'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("report/get_no_so") ?>',
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

    var searchgridLUPNoStruk = new Ext.app.SearchField({
        store: strgridLUPNoStruk,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridLUPNoStruk'
    });

    var gridLUPNoStruk = new Ext.grid.GridPanel({
        store: strgridLUPNoStruk,
        stripeRows: true,
        frame: true,
        border:true,
        sm: smgridLUPNoStruk,
        columns: [
            smgridLUPNoStruk,
            {
                header: 'ID NoStruk',
                dataIndex: 'no_so',
                width: 200,
                sortable: true

            }
        ],
        tbar: new Ext.Toolbar({
            items: [searchgridLUPNoStruk]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridLUPNoStruk,
            displayInfo: true
        })
    });

    var menuLUPNoStruk = new Ext.menu.Menu();

    menuLUPNoStruk.add(new Ext.Panel({
        title: 'Pilih NoStruk',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridLUPNoStruk],
        buttons: [
            {
                text: 'Done',
                handler: function () {
                    var sm  = gridLUPNoStruk.getSelectionModel();
                    var sel = sm.getSelections();
                    if(sel.length > 0) {
                        addSelectedValue('id_lup_so_sel','no_so',sel);
                        sm.clearSelections();
                    }
                    menuLUPNoStruk.hide();
                }
            },
            {
                text: 'Add Selected',
                handler: function(){
                    var sm  = gridLUPNoStruk.getSelectionModel();
                    var sel = sm.getSelections();
                    if(sel.length > 0) {
                        addSelectedValue('id_lup_so_sel','no_so',sel);
                        sm.clearSelections();
                    }
                }
            },
            {
                text: 'Reset',
                handler: function(){
                    Ext.getCmp('id_lup_so_sel').setValue('');
                }
            },
            {
                text: 'Close',
                handler: function(){ menuLUPNoStruk.hide(); }
            }]
    }));

    Ext.ux.TwinComboLUPNoStruk = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            strgridLUPNoStruk.load();
            menuLUPNoStruk.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menuLUPNoStruk.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridLUPNoStruk').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgridLUPNoStruk').setValue('');
            searchgridLUPNoStruk.onTrigger2Click();
        }
    });

    var comboLUPNoStruk = new Ext.ux.TwinComboLUPNoStruk({
        fieldLabel: 'No Struk',
        id: 'id_cbLUPNoStruk',
        store: strCbLUPNoStruk,
        mode: 'local',
        valueField: 'no_so',
        displayField: 'no_so',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'no_so',
        emptyText: 'Pilih No Struk'
    });
// -------- COMBOBOX NO SO --------------------

// -------- COMBOBOX MEMBER -------------------
    var smgridLUPMember= new Ext.grid.CheckboxSelectionModel();

    var strCbLUPMember = new Ext.data.ArrayStore({
        fields: ['kd_member'],
        data : []
    });

    var strgridLUPMember = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_member', 'nmmember'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("report/get_member") ?>',
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

    var searchgridLUPmember = new Ext.app.SearchField({
        store: strgridLUPMember,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridLUPmember'
    });

    var gridLUPMember = new Ext.grid.GridPanel({
        store: strgridLUPMember,
        stripeRows: true,
        frame: true,
        border:true,
        sm: smgridLUPMember,
        columns: [
            smgridLUPMember,
            {
                header: 'ID Member',
                dataIndex: 'kd_member',
                width: 80,
                sortable: true

            },
            {
                header: 'Nama Member',
                dataIndex: 'nmmember',
                width: 300,
                sortable: true
            }
        ],
        tbar: new Ext.Toolbar({
            items: [searchgridLUPmember]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridLUPMember,
            displayInfo: true
        })
    });

    Ext.ux.TwinComboLUPMember = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            //load store grid
            strgridLUPMember.load();
            menuLUPMember.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    var menuLUPMember = new Ext.menu.Menu();

    menuLUPMember.add(new Ext.Panel({
        title: 'Pilih Member',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridLUPMember],
        buttons: [
            {
                text: 'Done',
                handler: function () {
                    var sm  = gridLUPMember.getSelectionModel();
                    var sel = sm.getSelections();
                    if(sel.length > 0) {
                        addSelectedValue('id_lup_member_sel','kd_member',sel);
                        sm.clearSelections();
                    }
                    menuLUPMember.hide();
                }
            },
            {
                text: 'Add Selected',
                handler: function(){
                    var sm  = gridLUPMember.getSelectionModel();
                    var sel = sm.getSelections();
                    if(sel.length > 0) {
                        addSelectedValue('id_lup_member_sel','kd_member',sel);
                        sm.clearSelections();
                    }
                }
            },
            {
                text: 'Reset',
                handler: function(){
                    Ext.getCmp('id_lup_member_sel').setValue('');
                }
            },
            {
                text: 'Close',
                handler: function(){ menuLUPMember.hide(); }
            }
        ]
    }));

    menuLUPMember.on('hide', function(){
        var sf = Ext.getCmp('id_searchgridLUPmember').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgridLUPmember').setValue('');
            searchgridLUPmember.onTrigger2Click();
        }
    });


    var comboLUPMember = new Ext.ux.TwinComboLUPMember({
        fieldLabel: 'Kode Member',
        id: 'id_cbLUPmember',
        store: strCbLUPMember,
        mode: 'local',
        valueField: 'kd_member',
        displayField: 'nmmember',
        typeAhead: true,
        triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_member',
        emptyText: 'Pilih member'
    });
// -------- COMBOBOX MEMBER -------------------

    /* MASIH KURANG BANYAK VROOOOH
     * - select biaya, lebih bayar
     * - select tampilan sisa piutang saja, yang sudah lunas saja atau semua piutang
     * - select tanggal jatuh tempo
     * - select status s, d, b, p, pb
     * - select nomor bukti bayar
     * */

// -------- MAIN FORM -------------------------
    var headerLUPtanggal = {
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
                items: [{
                    layout: 'column',
                    items:[{
                        columnWidth: .5,
                        layout: 'form',
                        border: false,
                        labelWidth: 100,
                        defaults: { labelSeparator: ''},
                        items:[
                            {
                                xtype: 'datefield',
                                fieldLabel: 'Tgl Piutang',
                                name: 'dari_tgl',
                                allowBlank:false,
                                format:'d-m-Y',
                                editable:false,
                                id: 'id_lup_tgl_awal',
                                anchor: '90%',
                                value: ''
                            },
                            {
                                xtype: 'datefield',
                                fieldLabel: 'Tgl Bayar',
                                name: 'byr_dari_tgl',
                                format:'d-m-Y',
                                editable:false,
                                id: 'id_lup_tgl_awal_byr',
                                anchor: '90%',
                                value: ''
                            },
                            {
                                xtype: 'datefield',
                                fieldLabel: 'Tgl Jatuh Tempo',
                                name: 'jt_dari_tgl',
                                format:'d-m-Y',
                                editable:false,
                                id: 'id_lup_tgl_awal_jt',
                                anchor: '90%',
                                value: ''
                            },
                                comboLUPNoStruk,
                                comboLUPMember
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
                                    id: 'id_lup_tgl_akhir',
                                    anchor: '90%',
                                    value: ''
                                },
                                {
                                    xtype: 'datefield',
                                    fieldLabel: 'Sampai Tgl',
                                    name: 'byr_sampai_tgl',
                                    format:'d-m-Y',
                                    editable:false,
                                    id: 'id_lup_tgl_akhir_byr',
                                    anchor: '90%',
                                    value: ''
                                },
                                {
                                    xtype: 'datefield',
                                    fieldLabel: 'Sampai Tgl',
                                    name: 'jt_sampai_tgl',
                                    format:'d-m-Y',
                                    editable:false,
                                    id: 'id_lup_tgl_akhir_jt',
                                    anchor: '90%',
                                    value: ''
                                },
                                {
                                    xtype: 'textfield',
                                    fieldLabel: 'No. Struk',
                                    name: 'no_so_sel',
                                    readOnly:true,
                                    fieldClass:'readonly-input',
                                    id: 'id_lup_so_sel',
                                    anchor: '90%',
                                    value:''
                                },
                                {
                                    xtype: 'textfield',
                                    fieldLabel: 'Kode Member',
                                    name: 'kd_member_sel',
                                    readOnly:true,
                                    fieldClass:'readonly-input',
                                    id: 'id_lup_member_sel',
                                    anchor: '90%',
                                    value:''
                                }
                            ]
                        }]
                }]
            }]
        }]
    }


    var laporanUmurPiutang = new Ext.FormPanel({
        id: 'rpt_umur_piutang',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
            bodyStyle: {
                margin: '0px 0px 15px 0px'
            },
            items: [{
                buttonAlign: 'left',
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [headerLUPtanggal],
                buttons: [
                    {
                        text: 'Print',
                        formBind:true,
                        handler: function () {
                            Ext.getCmp('rpt_umur_piutang').getForm().submit({
                                url: '<?= site_url("laporan_umur_piutang/get_report") ?>',
                                scope: this,
                                waitMsg: 'Preparing Data...',
                                success: function(form, action){
                                    var r = Ext.util.JSON.decode(action.response.responseText);
                                    Ext.Msg.show({
                                        title: 'Success',
                                        msg: r.successMsg,
                                        modal: true,
                                        icon: Ext.Msg.INFO,
                                        buttons: Ext.Msg.OK,
                                        fn: function(btn){
                                            window.open(r.printUrl, '_blank');
                                        }
                                    });

                                    clearform('rpt_umur_piutang');
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
                    },
                    {
                        text: 'Cancel',
                        handler: function(){clearform('rpt_umur_piutang');}
                    }
                ]
            }]
        }
        ]
    });

</script>