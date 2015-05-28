<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>
<script>

    var hdr_viewbyrpiutang = {
        layout: 'column',
        border: false,
        buttonAlign: 'left',
        items: [
            {
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [
                    //cb_no_faktur_vbp,
                    {
                        xtype: 'datefield',
                        fieldLabel: 'Tgl Awal',
                        emptyText: 'Tanggal Awal',
                        name: 'tgl_fltvbp_awal',
                        id: 'id_tgl_fltvbp_awal',
                        maxLength: 255,
                        anchor: '90%',
                        value: '',
                        format: 'd-M-Y'
                    }
                ]
            },{
                columnWidth: .5,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: {labelSeparator: ''},
                items: [
                    //cb_no_bukti_vbp,
                    {
                        xtype: 'datefield',
                        fieldLabel: 'Tgl Akhir',
                        emptyText: 'Tanggal Akhir',
                        name: 'tgl_fltvbp_akhir',
                        id: 'id_tgl_fltvbp_akhir',
                        maxLength: 255,
                        anchor: '90%',
                        value: '',
                        format: 'd-M-Y'
                    }
                ]
            }
        ],
        buttons: [{
            text: 'Filter',
            formBind: true,
            handler: function () {

            }
        },{
            text: 'Reset',
            formBind: true,
            handler: function () {
//                Ext.getCmp('id_cbvrbproduk').setValue('');
                Ext.getCmp('id_cb_no_faktur_vbp').setValue('');
                Ext.getCmp('id_cb_no_bukti_vbp').setValue('');
                Ext.getCmp('id_tgl_fltvbp_awal').setValue('');
                Ext.getCmp('id_tgl_fltvbp_akhir').setValue('');
//                Ext.getCmp('id_vin_kode_supplier').setValue('');
                grid_viewbyrpiutang.store.removeAll();
            }
        }]
    };

    /**
     * Store u/ grid pembayaran
     */
    var str_viewbyrpiutang = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: [],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("view_create_invoice/get_rows") ?>',
            method: 'POST'
        }),
        listeners: {
            loadexception: function (event, options, response, error) {
                var err = Ext.util.JSON.decode(response.responseText);
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });

    var search_viewbyrpiutang = new Ext.app.SearchField({
        id: 'id_search_viewbyrpiutang ',
        store: str_viewbyrpiutang,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 220,
        emptyText: 'Quick filter ...'
    });

    var tbviewinvoice = new Ext.Toolbar({ items: [search_viewbyrpiutang]});

    var cbSelMode = new Ext.grid.CheckboxSelectionModel();

    var grid_viewbyrpiutang = new Ext.grid.EditorGridPanel({
        id: 'id_grid_viewbyrpiutang',
        frame: true,
        border: true,
        stripeRows: true,
        sm: cbSelMode,
        store: str_viewbyrpiutang,
        loadMask: false,
        style: 'margin:0 auto;',
        height: 400,
        columns: [],
        listeners: {
            'rowdblclick': function() {
                var sm = grid_viewbyrpiutang.getSelectionModel();
                var sel = sm.getSelection();

                if(sel.length > 0) {
                    Ext.Ajax.request({
                        url: '<?= site_url("view_create_invoice/get_data_invoice") ?>/' + sel[0].get('no_invoice'),
                        method: 'POST',
                        params: {},
                        callback: function (opt, success, responseObj) {
                            var windowviewreturbeli = new Ext.Window({
                                title: 'View Create Invoice',
                                width: 850,
                                height: 500,
                                autoScroll: true,
                                html: responseObj.responseText
                            });
                            windowviewreturbeli.show();
                        }
                    });
                }
            }
        },
        tbar: tbviewinvoice,
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: str_viewbyrpiutang,
            displayInfo: true
        })
    })

    /**
     * panel utama
     * @type {Ext.FormPanel}
     */
    var viewbyrpiutang = new Ext.FormPanel({
        id: 'viewbyrpiutang',
        border: false,
        frame: true,
        //autoScroll:true,
        bodyStyle: 'padding-right:20px;',
        labelWidth: 130,
        items: [{
            bodyStyle: {
            margin: '10px 0px 15px 0px'
            },
            items: [hdr_viewbyrpiutang]
        }]
    })
</script>
