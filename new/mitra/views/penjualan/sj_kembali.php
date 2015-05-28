<?php if (!defined('BASEPATH')) exit('No direct script access allowed');?>

<script type="text/javascript">
    //twin no sj
    var strCbKembaliSj = new Ext.data.ArrayStore({
        fields: ['no_sj'],
        data : []
    });

    var strGridKembaliSj = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['no_do',
                'no_sj',
                'tanggal',
                'pic_penerima',
                'keterangan'
            ],
            root: 'data',
            totalProperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("penjualan_sj/search_sj") ?>',
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

    var searchGridKembaliSj = new Ext.app.SearchField({
        store: strGridKembaliSj,
        params: {
            start: STARTPAGE,
            limit: ENDPAGE
        },
        width: 350,
        id: 'id_searchgrid_kembali_sj'
    });

    var gridKembaliSj = new Ext.grid.GridPanel({
        store: strGridKembaliSj,
        stripeRows: true,
        frame: true,
        border:true,
        columns: [
            {header:'No. SJ',dataIndex:'no_sj',width: 80,sortable: true},
            {header:'No. DO',dataIndex:'no_do',width: 80,sortable: true},
            {header:'Tanggal',dataIndex:'tanggal',width: 80,sortable: true},
            {header:'PIC Penerima',dataIndex:'pic_penerima',width: 80,sortable: true},
            {header:'Keterangan',dataIndex:'keterangan',width: 80,sortable: true}
        ],
        tbar: new Ext.Toolbar({
            items: [searchGridKembaliSj]
        }),
        bbar: new Ext.PagingToolbar({
            pageSize: ENDPAGE,
            store: strGridKembaliSj,
            displayInfo: true
        }),
        listeners: {
            'rowdblclick': function(){
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_kembali_tanggal_sj').setValue(sel[0].get('tanggal'));
                    var tanggal_kembali = new Date(Date.parse(sel[0].get('tanggal')));
                    Ext.getCmp('id_kembali_sj_tanggal').setMinValue(tanggal_kembali);
                    Ext.getCmp('id_kembali_no_sj').setValue(sel[0].get('no_sj'));

                    menuKembaliSj.hide();
                }
            }
        }
    });

    var menuKembaliSj = new Ext.menu.Menu();
    menuKembaliSj.add(new Ext.Panel({
        title: 'Pilih No. SJ',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 400,
        height: 300,
        closeAction: 'hide',
        plain: true,
        items: [gridKembaliSj],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menuKembaliSj.hide();
                }
            }]
    }));

    Ext.ux.TwinComboReturBeliSupplier = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            strGridKembaliSj.load();
            menuKembaliSj.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    menuKembaliSj.on('hide', function(){
        var sf = Ext.getCmp('id_searchgrid_kembali_sj').getValue();
        if( sf != ''){
            Ext.getCmp('id_searchgrid_kembali_sj').setValue('');
            searchGridKembaliSj.onTrigger2Click();
        }
    });

    var comboKembaliSj = new Ext.ux.TwinComboReturBeliSupplier({
        fieldLabel: 'No. SJ<span class="asterix">*</span>',
        id: 'id_kembali_no_sj',
        store: strCbKembaliSj,
        mode: 'local',
        valueField: 'no_sj',
        displayField: 'no_sj',
        typeAhead: true,
        triggerAction: 'all',
        allowBlank: false,
        editable: false,
        anchor: '90%',
        hiddenName: 'no_sj',
        emptyText: 'Pilih No. SJ'
    });

    var headerSalesSj=
        {layout: 'column',
        border: false,
        items: [{columnWidth: .4,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [
                    comboKembaliSj,
                    {
                        xtype: 'datefield',
                        fieldLabel: 'Tanggal Kembali<span class="asterix">*</span>',
                        name: 'tanggal_kembali',
                        id:'id_kembali_sj_tanggal',
                        allowBlank:false,
                        maxValue: new Date(),
                        format:'d-M-Y',
                        editable:false,
                        anchor: '90%',
                        listeners: {
                            'focus': function() {
                                var no_sj = Ext.getCmp('id_kembali_no_sj').getValue();
                                if(no_sj == undefined || no_sj == '') {
                                    Ext.Msg.show({
                                        title: 'Error',
                                        msg: 'SJ belum dpilih!',
                                        modal: true,
                                        icon: Ext.Msg.ERROR,
                                        buttons: Ext.Msg.OK
                                    });
                                }
                            }
                        }
                    }, {
                        xtype: 'textfield',
                        fieldLabel: 'Penerima<span class="asterix">*</span>',
                        name: 'penerima',
                        allowBlank: false,
                        id: 'id_kembali_sj_penerima',
                        maxLength: 255,
                        anchor: '90%',
                        value:''
                    }


                ]

            },{columnWidth: .4,
                layout: 'form',
                border: false,
                labelWidth: 100,
                defaults: { labelSeparator: ''},
                items: [
                    {
                        xtype: 'hidden',
                        name: 'is_kembali',
                        id:'id_kembali_status',
                        value: 1
                    }, {
                        xtype: 'datefield',
                        fieldLabel: 'Tanggal SJ<span class="asterix">*</span>',
                        fieldClass:'readonly-input',
                        name: 'tgl_sj',
                        id:'id_kembali_tanggal_sj',
                        readOnly:true,
                        allowBlank:false,
                        format:'d-M-Y',
                        editable:false,
                        anchor: '90%'
                    }, {
                        xtype: 'textarea',
                        fieldLabel: 'Keterangan',
                        name: 'ket_pengembalian',
                        allowBlank:false,
                        id: 'id_kembali_sj_keterangan',
                        maxLength: 100,
                        anchor: '90%',
                        value:''
                    }
                ]
            }]
    };

    //twin produk
    var strCbKembaliProduk = new Ext.data.ArrayStore({
        fields: ['kd_produk'],
        data : []
    });

    var strGridKembaliProduk = new Ext.data.Store({
        reader: new Ext.data.JsonReader({
            fields: ['kd_produk', 'nama_produk', 'qty_do', 'qty_sj', 'nm_satuan', 'keterangan'],
            root: 'data',
            totalproperty: 'record'
        }),
        proxy: new Ext.data.HttpProxy({
            url: '<?= site_url("penjualan_sj/search_produk_nosj") ?>',
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

    var searchGridKembaliProduk = new Ext.app.SearchField({
        width: 220,
        id: 'sjk_search_query',
        store: strGridKembaliProduk
    });

    searchGridKembaliProduk.onTrigger1Click = function(evt) {
        if (this.hasSearch) {
            this.el.dom.value = '';

            // Get the value of search field
            var fid = Ext.getCmp('id_kembali_no_sj').getValue();
            var o = { start: 0, no_sj: fid };

            this.store.baseParams = this.store.baseParams || {};
            this.store.baseParams[this.paramName] = '';
            this.store.reload({
                params : o
            });
            this.triggers[0].hide();
            this.hasSearch = false;
        }
    };

    searchGridKembaliProduk.onTrigger2Click = function(evt) {
        var text = this.getRawValue();
        if (text.length < 1) {
            this.onTrigger1Click();
            return;
        }

        // Get the value of search field
        var fid = Ext.getCmp('id_kembali_no_sj').getValue();
        var o = { start: 0, no_sj: fid };

        this.store.baseParams = this.store.baseParams || {};
        this.store.baseParams[this.paramName] = text;
        this.store.reload({params:o});
        this.hasSearch = true;
        this.triggers[0].show();
    };

    // top toolbar
    var tbGridKembaliProduk = new Ext.Toolbar({
        items: [searchGridKembaliProduk]
    });

    var gridKembaliProduk = new Ext.grid.GridPanel({
        store: strGridKembaliProduk,
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
            header: 'Qty DO',
            dataIndex: 'qty_do',
            width: 80,
            sortable: true
        },{
            header: 'Qty SJ',
            dataIndex: 'qty_sj',
            width: 80,
            sortable: true
        }],
        tbar:tbGridKembaliProduk,
        listeners: {
            'rowdblclick': function(){
                var sm = this.getSelectionModel();
                var sel = sm.getSelections();
                if (sel.length > 0) {
                    Ext.getCmp('id_grid_sjk_kd_produk').setValue(sel[0].get('kd_produk'));
                    Ext.getCmp('id_grid_sjk_nama_produk').setValue(sel[0].get('nama_produk'));
                    Ext.getCmp('id_grid_sjk_qty_do').setValue(sel[0].get('qty_do'));
                    Ext.getCmp('id_grid_sjk_qty_sj').setValue(sel[0].get('qty_sj'));
                    Ext.getCmp('id_grid_sjk_qty').setValue('0');
                    Ext.getCmp('id_grid_sjk_nm_satuan').setValue(sel[0].get('nm_satuan'));
                    Ext.getCmp('id_grid_sjk_qty').focus();
                    menuproproduk_sj.hide();
                }
            }
        }
    });

    var menuproproduk_sj = new Ext.menu.Menu();
    menuproproduk_sj.add(new Ext.Panel({
        title: 'Pilih Barang',
        layout: 'fit',
        buttonAlign: 'left',
        modal: true,
        width: 600,
        height: 250,
        closeAction: 'hide',
        plain: true,
        items: [gridKembaliProduk],
        buttons: [{
                text: 'Close',
                handler: function(){
                    menuproproduk_sj.hide();
                }
            }]
    }));

    Ext.ux.TwinComboproproduk_sjk = Ext.extend(Ext.form.ComboBox, {
        initComponent: Ext.form.TwinTriggerField.prototype.initComponent,
        getTrigger: Ext.form.TwinTriggerField.prototype.getTrigger,
        initTrigger: Ext.form.TwinTriggerField.prototype.initTrigger,
        onTrigger1Click: Ext.form.ComboBox.prototype.onTriggerClick,
        trigger1Class: Ext.form.ComboBox.prototype.triggerClass,
        onTrigger2Click: function(){
            if(Ext.getCmp('id_kembali_no_sj').getValue() == ''){
                Ext.Msg.show({
                    title: 'Error',
                    msg: 'Silahkan pilih No SJ terlebih dulu',
                    modal: true,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK
                });
                return;
            }
            //load store grid
            strGridKembaliProduk.load({
                params: {
                    no_sj: Ext.getCmp('id_kembali_no_sj').getValue()
                }
            });
            menuproproduk_sj.showAt([this.getPosition()[0], this.getPosition()[1] + this.getHeight()]);
        },
        validationEvent: false,
        validateOnBlur: false,
        trigger1Class: 'x-form-clear-trigger',
        trigger2Class: 'x-form-search-trigger',
        hideTrigger1: true
    });

    //-------grid---------------------------------------------------
    var strGridSuratJalanKembali= new Ext.data.Store({
        autoSave:false,
        reader: new Ext.data.JsonReader({
            fields: [
                {name: 'kd_produk', allowBlank: false, type: 'text'},
                {name: 'nama_produk', allowBlank: false, type: 'text'},
                {name: 'qty_do', allowBlank: false, type: 'int'},
                {name: 'qty_sj', allowBlank: false, type: 'int'},
                {name: 'qty', allowBlank: false, type: 'int'},
                {name: 'satuan', allowBlank: false, type: 'text'},
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
    });

    var editorSuratJalanKembali = new Ext.ux.grid.RowEditor({
        saveText: 'Update'
    });

    var strCbSuratJalanKembali = new Ext.data.Store({
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
                if (err.errMsg == 'Session Expired') {
                    session_expired(err.errMsg);
                }
            }
        }
    });



    var gridSuratJalanKembali=new Ext.grid.GridPanel({
        store: strGridSuratJalanKembali,
        stripeRows: true,
        height: 200,
        frame: true,
        border:true,
        plugins:[editorSuratJalanKembali],
        columns: [{
            header: 'Kode produk',
            dataIndex: 'kd_produk',
            width: 110,
            editor: new Ext.ux.TwinComboproproduk_sjk({
                id: 'id_grid_sjk_kd_produk',
                store: strCbKembaliProduk,
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
                id: 'id_grid_sjk_nama_produk'
            })
        },{
            header: 'Satuan',
            dataIndex: 'nm_satuan',
            width: 80,
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'id_grid_sjk_nm_satuan'
            })
        },{
            header: 'Qty DO',
            dataIndex: 'qty_do',
            width: 60,
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'id_grid_sjk_qty_do'
            })
        },{
            header: 'Qty SJ',
            dataIndex: 'qty_sj',
            width: 60,
            editor: new Ext.form.TextField({
                readOnly: true,
                id: 'id_grid_sjk_qty_sj'
            })
        },{
            xtype: 'numbercolumn',
            header: 'Qty',
            dataIndex: 'qty_kembali',
            width: 60,
            align: 'right',
            sortable: true,
            format: '0,0',
            editor: {
                xtype: 'numberfield',
                id: 'id_grid_sjk_qty',
                allowBlank: false,
                selectOnFocus:true,
                listeners:{
                    'change': function(){
                        var qty_sj = Ext.getCmp('id_grid_sjk_qty_sj').getValue();
                        if(this.getValue() == '') this.setValue('0');
                        if(qty_sj < 1) Ext.getCmp('id_grid_sjk_qty_sj').setValue('0');

                        if(this.getValue() > qty_sj){
                            this.setValue('0');
                            Ext.Msg.show({
                                title: 'Error',
                                msg: 'Quantity Kembali Melebihi Quantity SJ !',
                                modal: true,
                                icon: Ext.Msg.ERROR,
                                buttons: Ext.Msg.OK
                            });
                            Ext.getCmp('id_grid_sjk_qty').focus();
                        } else {
                            Ext.getCmp('id_grid_sjk_keterangan').focus();
                        }

                    },
                    'specialKey': function( field, e ) {
                        Ext.getCmp('id_grid_sjk_qty').focus();
                        if ( e.getKey() == e.RETURN || e.getKey() == e.ENTER ) {
                            this.fireEvent('change');
                        }
                    }
                }
            }
        },{
            header: 'Keterangan',
            dataIndex: 'keterangan',
            width: 300,
            editor: new Ext.form.TextField({
                allowBlank: false,
                id: 'id_grid_sjk_keterangan'
            })
        }],
        tbar: [{
                icon: BASE_ICONS + 'add.png',
                text: 'Add',
                handler: function(){
                    if(Ext.getCmp('id_kembali_no_sj').getValue() == ''){
                        Ext.Msg.show({
                            title: 'Error',
                            msg: 'Silahkan pilih no faktur terlebih dulu',
                            modal: true,
                            icon: Ext.Msg.ERROR,
                            buttons: Ext.Msg.OK
                        });
                        return;
                    }
                    var rowSuratJalanKembaliEdit = new gridSuratJalanKembali.store.recordType({
                        no_sj:'',
                        kd_produk : '',
                        qty: ''
                    });
                    editorSuratJalanKembali.stopEditing();
                    strGridSuratJalanKembali.insert(0, rowSuratJalanKembaliEdit);
                    gridSuratJalanKembali.getView().refresh();
                    gridSuratJalanKembali.getSelectionModel().selectRow(0);
                    editorSuratJalanKembali.startEditing(0);
                }
            },{
                ref: '../removeBtn',
                icon: BASE_ICONS + 'delete.gif',
                text: 'Remove',
                disabled: true,
                handler: function(){
                    editorSuratJalanKembali.stopEditing();
                    var s = gridSuratJalanKembali.getSelectionModel().getSelections();
                    for(var i = 0, r; r = s[i]; i++){
                        strGridSuratJalanKembali.remove(r);
                    }
                }
            }]
    });

    gridSuratJalanKembali.getSelectionModel().on('selectionchange', function(sm){
        gridSuratJalanKembali.removeBtn.setDisabled(sm.getCount() < 1);
    });
    var penjualansj= new Ext.FormPanel({
        id: 'penjualan_sj_kembali',
        border: false,
        frame: true,
        autoScroll:true,
        monitorValid: true,
        labelWidth: 130,
        items:[
            {
                bodyStyle: {
                    margin: '0px 0px 15px 0px'
                },
                items: [headerSalesSj]
            },
            gridSuratJalanKembali
        ],
        buttons: [{
                text: 'Save',
                formBind: true,
                handler: function(){
                    var detailSj = new Array();
                    strGridSuratJalanKembali.each(function(node){
                        detailSj.push(node.data)
                    });
                    Ext.getCmp('penjualan_sj_kembali').getForm().submit({
                        url: '<?= site_url("penjualan_sj/proses_kembali") ?>',
                        scope: this,
                        params: {
                            data: Ext.util.JSON.encode(detailSj)
                        },
                        waitMsg: 'Saving Data...',
                        success: function(form, action){
                            var r = Ext.util.JSON.decode(action.response.responseText);
                            Ext.Msg.show({
                                title: 'Success',
                                msg: 'Form submitted successfully',
                                modal: true,
                                icon: Ext.Msg.INFO,
                                buttons: Ext.Msg.OK
                            });
                            clearsalessj();
                            return;
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
            { text: 'Reset', handler: function(){clearsalessj()}}
        ]
    });

    var winpenjualan_sj_kembali = new Ext.Window({
        id: 'id_winpenjualan_sj_kembali',
        title: 'Print Surat Jalan',
        closeAction: 'hide',
        width: 900,
        height: 450,
        layout: 'fit',
        border: false,
        html:'<iframe style="width:100%;height:100%;" id="penjualan_sj_kembaliprint" src=""></iframe>'
    });

    function clearsalessj(){
        Ext.getCmp('penjualan_sj_kembali').getForm().reset();
        strGridSuratJalanKembali.removeAll();
    }
</script>
