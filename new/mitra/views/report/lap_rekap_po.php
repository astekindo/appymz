<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<script type="text/javascript">
    var strcblrposuplier = new Ext.data.ArrayStore({
        fields: ['kd_supplier'],
        data : []
    });

    var strgridlrposuplier = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_supplier', 'nama_supplier'],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("laporan_rekap_po/search_supplier") ?>',
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

    var searchgridlrposuplier = new Ext.app.SearchField({
        store: strgridlrposuplier,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgridlrposuplier'
    });

    var gridlrposuplier = new Ext.grid.GridPanel({
        store: strgridlrposuplier,
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
        }],
        tbar: new Ext.Toolbar({
            items: [searchgridlrposuplier]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strgridlrposuplier,
            displayInfo: true
        }),
        listeners: {
           'rowdblclick': function(){
            var sm = this.getSelectionModel();
            var sel = sm.getSelections();
            if (sel.length > 0) {
                   Ext.getCmp('id_cblrposuplier').setValue(sel[0].get('kd_supplier'));
                   menulrposuplier.hide();
               }
           }
       }
   });

var menulrposuplier = new Ext.menu.Menu();
menulrposuplier.add(new Ext.Panel({
    title: 'Pilih Supplier',
    layout: 'fit',
    buttonAlign: 'left',
    modal: true,
    width: 400,
    height: 300,
    closeAction: 'hide',
    plain: true,
    items: [gridlrposuplier],
    buttons: [{
        text: 'Close',
        handler: function(){
            menulrposuplier.hide();
        }
    }]
}));

Ext.ux.TwinComboSuplier = Ext.extend(Ext.form.ComboBox, {
    initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
    getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
    initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
    onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
    triggerldsClass: Ext.form.ComboBox.prototype.triggerClass,
    onTrigger2Click: function(){
            strgridlrposuplier.load();
            menulrposuplier.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

menulrposuplier.on('hide', function(){
    var sf = Ext.getCmp('id_searchgridlrposuplier').getValue();
    if( sf != ''){
        Ext.getCmp('id_searchgridlrposuplier').setValue('');
        searchgridlrposuplier.onTrigger2Click();
}
});


var cblrposuplier = new Ext.ux.TwinComboSuplier({
    fieldLabel: 'Supplier',
    id: 'id_cblrposuplier',
    store: strcblrposuplier,
    mode: 'local',
    valueField: 'kd_supplier',
    displayField: 'kd_supplier',
    typeAhead: true,
    triggerAction: 'all',
        // allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'kd_supplier',
        emptyText: 'Pilih Supplier'
    });
var headerlpotanggal = {
    layout: 'column',
    border: false,
    items: [{
        columnWidth: .6,
        layout: 'form',
        border: false,
        labelWidth: 100,
        defaults: { labelSeparator: ''},
        items: [{
            layout: 'column',
            items:[ {
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items:[{
                        xtype: 'datefield',
                        fieldLabel: 'Dari Tgl ',
                        name: 'dari_tgl',
                        allowBlank:false,
                        format:'d-m-Y',
                        editable:false,
                        id: 'id_dari_tgl',
                        anchor: '90%',
                        value: ''
                    },
                cblrposuplier]
            }, {
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items:[{
                    xtype: 'datefield',
                    fieldLabel: 'Sampai Tgl',
                    name: 'sampai_tgl',
                    allowBlank:false,
                    editable:false,
                    format:'d-m-Y',
                    id: 'id_smp_tgl',
                    anchor: '90%',
                    value: ''
                }]
            }]
        }]
    }]
}

var headerlaporanpurchaseorder = {
    buttonAlign: 'left',
    layout: 'form',
    border: false,
    labelWidth: 100,
    defaults: { labelSeparator: ''},
    items: [{
        fieldLabel: 'Tanggal Input : '},
        headerlpotanggal
    ],
    buttons: [{
        text: 'Print',
        formBind:true,
        handler: function(){
            Ext.getCmp('rpt_rekap_po').getForm().submit({
                url: '<?= site_url("laporan_rekap_po/get_report") ?>',
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

                    clearlaporanrekappo();
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
        text: 'Cancel',
        handler: function(){ clearlaporanrekappo(); }
    }]
};

var laporanrekappo = new Ext.FormPanel({
    id: 'rpt_rekap_po',
    border: false,
    frame: true,
    monitorValid: true,
    labelWidth: 130,
    items: [{
        bodyStyle: {
            margin: '0px 0px 15px 0px'
        },
        items: [headerlaporanpurchaseorder]
    }
    ]
});

function clearlaporanrekappo(){
  Ext.getCmp('rpt_rekap_po').getForm().reset();

}
</script>