<?php
/**
 * Created by PhpStorm.
 * User: FIDZAL
 * Date: 5/22/14
 * Time: 5:03 PM
 */
if (!defined('BASEPATH')) exit('No direct script access allowed');
?>
<script type="text/javascript">
    //-------- COMBOBOX PERUNTUKAN -------------------
    var dsPeruntukanTPB=[['D',"Distribusi"],['B',"Bazar"],['S',"Supermarket"]];

    var strPeruntukanTPB = new Ext.data.ArrayStore({
        fields: [{name: 'key'},{name: 'value'}],
        data:dsPeruntukanTPB
    });

    // COMBOBOX Peruntukan
    var comboPeruntukanTPB = new Ext.form.ComboBox({
        fieldLabel: 'Peruntukkan',
        id: 'id_combo_peruntukan_tpb',
        name:'peruntukan',
        allowBlank:false,
        store: strPeruntukanTPB,
        valueField:'key',
        displayField:'value',
        mode:'local',
        forceSelection: true,
        triggerAction: 'all',
        anchor: '90%'
    });
    //-------- COMBOBOX PERUNTUKAN -------------------
    var headerTPB = {
        buttonAlign: 'left',
        layout: 'form',
        border: false,
        labelWidth: 100,
        defaults: { labelSeparator: ''},
        items: [
            { fieldLabel: 'Tanggal Input : '},
            {
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
                                        id: 'id_dari_tgl_tpb',
                                        anchor: '90%',
                                        value: ''
                                    },
                                    comboPeruntukanTPB
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
                                    id: 'id_smp_tgl_tpb',
                                    anchor: '90%',
                                    value: ''
                                }]
                            }]
                        }]
                    }]
                }]
            }
        ],
        buttons: [
            {
                text: 'Print',
                formBind:true,
                handler: function () {
                    Ext.getCmp('target_pencapaian_pembelian').getForm().submit({
                        url: '<?= site_url("target_pencapaian_pembelian/get_report") ?>',
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

                            clearform('target_pencapaian_pembelian');
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
                handler: function(){ clearform('target_pencapaian_pembelian');}
            }]
    };
    var laporanTPB = new Ext.FormPanel({
        id: 'target_pencapaian_pembelian',
        border: false,
        frame: true,
        monitorValid: true,
        labelWidth: 130,
        items: [{
            bodyStyle: {
                margin: '0px 0px 15px 0px'
            },
            items: [headerTPB]
        }
        ]
    });

</script>