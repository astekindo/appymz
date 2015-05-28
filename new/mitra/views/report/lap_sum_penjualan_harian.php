<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<script type="text/javascript">
    //-------- COMBOBOX PERUNTUKAN -------------------
    var dsPeruntukanReportJualSH=[['D',"Distribusi"],['B',"Bazar"],['S',"Supermarket"]];

    var strReportJualSHPeruntukan = new Ext.data.ArrayStore({
        fields: [{name: 'key'},{name: 'value'}],
        data:dsPeruntukanReportJualSH
    });

    // COMBOBOX Peruntukan
    var cbReportJualSHPeruntukan = new Ext.form.ComboBox({
        fieldLabel: 'Peruntukkan',
        id: 'id_cbReportJualSHPeruntukan',
        name:'peruntukan',
        allowBlank:false,
        store: strReportJualSHPeruntukan,
        valueField:'key',
        displayField:'value',
        mode:'local',
        forceSelection: true,
        triggerAction: 'all',
        anchor: '90%'
    });
    //-------- COMBOBOX PERUNTUKAN -------------------


    var headerlsumjualhariantanggal = {
        layout: 'column',
        border: false,
        items: [{
            columnWidth: .6,
            layout: 'form',
            border: false,
            labelWidth: 100,
            defaults: { labelSeparator: ''},
            items: [{
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
                                fieldLabel: 'Dari Tgl ',
                                name: 'dari_tgl',
                                allowBlank:false,
                                format:'d-m-Y',
                                editable:false,
                                id: 'id_dari_tgl_jual_harian',
                                anchor: '90%',
                                value: ''
                            },
                            cbReportJualSHPeruntukan
                        ]
                    },{
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
                            id: 'id_smp_tgl_jual_harian',
                            anchor: '90%',
                            value: ''
                        }]
                    }]
                }]
            }]
        }]
    }


    var headerlsumjualharian = {
        buttonAlign: 'left',
        layout: 'form',
        border: false,
        labelWidth: 100,
        defaults: { labelSeparator: ''},
        items: [{ fieldLabel: 'Tanggal Input : '}, headerlsumjualhariantanggal],
        buttons: [
        {
            text: 'Print',
            formBind:true,
            handler: function () {
                Ext.getCmp('rpt_sum_penjualan_harian').getForm().submit({
                    url: '<?= site_url("laporan_sum_penjualan_harian/get_report") ?>',
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

                        clearform('rpt_sum_penjualan_harian');
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
            handler: function(){ clearform('rpt_sum_penjualan_harian');}
        }]
    };
    var lapsumjualharian = new Ext.FormPanel({
        id: 'rpt_sum_penjualan_harian',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
            bodyStyle: {
                margin: '0px 0px 15px 0px'
            },
            items: [headerlsumjualharian]
        }
        ]
    });

</script>