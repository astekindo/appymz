<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">
	var strsetparameter_margin = new Ext.data.Store({
		autoSave:false,
        reader: new Ext.data.JsonReader({
            fields: [
            {name: 'kd_produk', allowBlank: false, type: 'text'},
            {name: 'kd_produk_lama', allowBlank: false, type: 'text'},
            {name: 'nama_produk', allowBlank: false, type: 'text'},
            {name: 'nm_satuan', allowBlank: false, type: 'text'},
            {name: 'disk_persen_kons1', allowBlank: false, type: 'text'},
            {name: 'disk_persen_kons2', allowBlank: false, type: 'text'},
            {name: 'disk_persen_kons3', allowBlank: false, type: 'text'},
            {name: 'disk_persen_kons4', allowBlank: false, type: 'text'},
            {name: 'disk_amt_kons1', allowBlank: false, type: 'text'},
            {name: 'disk_amt_kons2', allowBlank: false, type: 'text'},
            {name: 'disk_amt_kons3', allowBlank: false, type: 'text'},
            {name: 'disk_amt_kons4', allowBlank: false, type: 'text'},
            {name: 'disk_amt_kons5', allowBlank: false, type: 'text'},
            {name: 'parameter_margin', allowBlank: false, type: 'text'},
            {name: 'parameter_margin_rp', allowBlank: false, type: 'text'},
            {name: 'parameter_markup', allowBlank: false, type: 'text'},
            {name: 'parameter_markup_rp', allowBlank: false, type: 'text'}
            ],
            root: 'data',
            totalproperty: 'record'
        }),
proxy: new Ext.data.HttpProxy({
    url: '<?= site_url("setparameter_margin/search_produk_by_kategori") ?>',
    method: 'POST'
}),
writer: new Ext.data.JsonWriter(
{
   encode: true,
   writeAllFields: true
})
});
/* START FORM */
var strsetparameter_margindetail = new Ext.data.Store({
  autoSave:false,
  reader: new Ext.data.JsonReader({
    fields: [
    {name: 'no_bpl', allowBlank: false, type: 'text'},
    {name: 'kd_produk', allowBlank: false, type: 'text'},
    {name: 'kd_lokasi', allowBlank: false, type: 'text'},
    {name: 'nama_lokasi', allowBlank: false, type: 'text'},
    {name: 'kd_blok', allowBlank: false, type: 'text'},
    {name: 'nama_blok', allowBlank: false, type: 'text'},
    {name: 'kd_sub_blok', allowBlank: false, type: 'text'},
    {name: 'nama_sub_blok', allowBlank: false, type: 'text'},
    {name: 'keterangan', allowBlank: false, type: 'text'},
    {name: 'kd_peruntukan', allowBlank: false, type: 'text'}
    ],
    root: 'data',
    totalproperty: 'record'
}),
  proxy: new Ext.data.HttpProxy({
    url: '<?= site_url("setparameter_margin/get_detail") ?>',
    method: 'POST'
}),
  writer: new Ext.data.JsonWriter(
  {
   encode: true,
   writeAllFields: true
})
});
Ext.ns('setparameter_marginform');
setparameter_marginform.Form = Ext.extend(Ext.form.FormPanel, {

    border: false,
    frame: true,
    labelWidth: 100,
    url: '<?= site_url("setparameter_margin/update_row") ?>',
    constructor: function(config){
        config = config || {};
        config.listeners = config.listeners || {};
        Ext.applyIf(config.listeners, {
            actioncomplete: function(){

            },
            actionfailed: function(){

            }
        });
        setparameter_marginform.Form.superclass.constructor.call(this, config);
    },
    initComponent: function(){

        var config = {
            defaultType: 'textfield',
            defaults: { labelSeparator: ''},
            monitorValid: true,
            autoScroll: false
            ,
            items: [{
                xtype: 'hidden',
                name: 'kd_produk',
                id: 'grid_kd_produk'
            },{
                xtype: 'hidden',
                name: 'kd_lokasi'
            },{
                xtype: 'hidden',
                name: 'kd_blok'
            },{
                xtype: 'hidden',
                name: 'kd_sub_blok'
            },{
                xtype: 'textarea',
                fieldLabel: 'Keterangan <span class="asterix">*</span>',
                name: 'keterangan',
                allowBlank: false,
                id: 'bpl_keterangan',
                style:'text-transform: uppercase',
                anchor: '90%'
            },{
             fieldLabel: 'Peruntukan <span class="asterix">*</span>',
             xtype: 'radiogroup',
             columnWidth: [.5, .5],
             allowBlank:false,
             items: [{
              boxLabel: 'Supermarket',
              name: 'kd_peruntukan',
              inputValue: '0',
              id: 'bpl_peruntukan_supermarket',
              checked:true
          }, {
              boxLabel: 'Distribusi',
              name: 'kd_peruntukan',
              inputValue: '1',
              id: 'bpl_peruntukan_distribusi'
          }]
      }],
      buttons: [{
        text: 'Submit',
        id: 'btnsubmitsetparameter_margin',
        formBind: true,
        scope: this,
        handler: this.submit
    }, {
        text: 'Reset',
        id: 'btnresetsetparameter_margin',
        scope: this,
        handler: this.reset
    }, {
        text: 'Close',
        id: 'btnClose',
        scope: this,
        handler: function(){
            winaddsetparameter_margin.hide();
        }
    }]
};
Ext.apply(this, Ext.apply(this.initialConfig, config));

setparameter_marginform.Form.superclass.initComponent.apply(this, arguments);
}
,
onRender: function(){

    setparameter_marginform.Form.superclass.onRender.apply(this, arguments);

    this.getForm().waitMsgTarget = this.getEl();


}
,
reset: function(){
    this.getForm().reset();
},
submit: function(){
    this.getForm().submit({
        url: this.url,
        scope: this,
        success: this.onSuccess,
        failure: this.onFailure,
        params: {
            cmd: 'save'
        },
        waitMsg: 'Saving Data...'
    });
}
,
onSuccess: function(form, action){
    Ext.Msg.show({
        title: 'Success',
        msg: 'Form submitted successfully',
        modal: true,
        icon: Ext.Msg.INFO,
        buttons: Ext.Msg.OK
    });

    strsetparameter_margindetail.load({
     params:{
      kd_produk: Ext.getCmp('grid_kd_produk').getValue()
  }
});
    Ext.getCmp('id_formaddsetparameter_margin').getForm().reset();
    winaddsetparameter_margin.hide();
}
,
onFailure: function(form, action){
    var fe = Ext.util.JSON.decode(action.response.responseText);
    this.showError(fe.errMsg || '');

}
,
showError: function(msg, title){
    title = title || 'Error';
    Ext.Msg.show({
        title: title,
        msg: msg,
        modal: true,
        icon: Ext.Msg.ERROR,
        buttons: Ext.Msg.OK,
        fn: function(btn){
            if (btn == 'ok' && msg == 'Session Expired') {
                window.location = '<?= site_url("auth/login") ?>';
            }
        }
    });
}
});
Ext.reg('formaddsetparameter_margin', setparameter_marginform.Form);
var winaddsetparameter_margin = new Ext.Window({
    id: 'id_winaddsetparameter_margin',
    closeAction: 'hide',
    width: 450,
    height: 350,
    layout: 'fit',
    border: false,
    items: {
        id: 'id_formaddsetparameter_margin',
        xtype: 'formaddsetparameter_margin'
    },
    onHide: function(){
        Ext.getCmp('id_formaddsetparameter_margin').getForm().reset();
    }
});

var str_spm_cbkategori1 = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: ['kd_kategori1', 'nama_kategori1'],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("kategori2/get_kategori1") ?>',
        method: 'POST'
    }),
    listeners: {
       load: function() {
        var r = new (str_spm_cbkategori1.recordType)({
         'kd_kategori1': '',
         'nama_kategori1': '-----'
     });
        str_spm_cbkategori1.insert(0, r);
    },
    loadexception: function(event, options, response, error){
        var err = Ext.util.JSON.decode(response.responseText);
        if (err.errMsg == 'Session Expired') {
            session_expired(err.errMsg);
        }
    }
}
});
var spm_cbkategori1 = new Ext.form.ComboBox({
    fieldLabel: 'Kategori 1 <span class="asterix">*</span>',
    id: 'spm_cbkategori1',
    store: str_spm_cbkategori1,
    valueField: 'kd_kategori1',
    displayField: 'nama_kategori1',
    typeAhead: true,
    triggerAction: 'all',
    editable: false,
    width: 170,
    anchor: '90%',
    hiddenName: 'kd_kategori1',
    emptyText: 'Pilih kategori 1',
    listeners: {
        'select': function(combo, records) {
            var kdspm_cbkategori1 = spm_cbkategori1.getValue();
            spm_cbkategori2.store.proxy.conn.url = '<?= site_url("kategori3/get_kategori2") ?>/' + kdspm_cbkategori1;
            spm_cbkategori2.store.reload();
            strsetparameter_margin.load({
             params:{
              kd_kategori1: Ext.getCmp('spm_cbkategori1').getValue(),
              kd_kategori2: Ext.getCmp('spm_cbkategori2').getValue(),
              kd_kategori3: Ext.getCmp('spm_cbkategori3').getValue(),
              kd_kategori4: Ext.getCmp('spm_cbkategori4').getValue()
          }
      });
        }
    }
});

var str_spm_cbkategori2 = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: ['kd_kategori2', 'nama_kategori2'],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("kategori3/get_kategori2") ?>',
        method: 'POST'
    }),
    listeners: {
       load: function() {
        var r = new (str_spm_cbkategori2.recordType)({
         'kd_kategori2': '',
         'nama_kategori2': '-----'
     });
        str_spm_cbkategori2.insert(0, r);
    },
    loadexception: function(event, options, response, error){
        var err = Ext.util.JSON.decode(response.responseText);
        if (err.errMsg == 'Session Expired') {
            session_expired(err.errMsg);
        }
    }
}
});
var spm_cbkategori2 = new Ext.form.ComboBox({
    fieldLabel: 'Kategori 2 <span class="asterix">*</span>',
    id: 'spm_cbkategori2',
    mode: 'local',
    store: str_spm_cbkategori2,
    valueField: 'kd_kategori2',
    displayField: 'nama_kategori2',
    typeAhead: true,
    triggerAction: 'all',
    editable: false,
    width: 170,
    anchor: '90%',
    hiddenName: 'kd_kategori2',
    emptyText: 'Pilih kategori 2',
    listeners: {
        select: function(combo, records) {
            var kd_spm_cbkategori1 = spm_cbkategori1.getValue();
            var kd_spm_cbkategori2 = this.getValue();
            spm_cbkategori3.setValue();
            spm_cbkategori3.store.proxy.conn.url = '<?= site_url("kategori4/get_kategori3") ?>/' + kd_spm_cbkategori1 +'/'+ kd_spm_cbkategori2;
            spm_cbkategori3.store.reload();
            strsetparameter_margin.load({
             params:{
              kd_kategori1: Ext.getCmp('spm_cbkategori1').getValue(),
              kd_kategori2: Ext.getCmp('spm_cbkategori2').getValue(),
              kd_kategori3: Ext.getCmp('spm_cbkategori3').getValue(),
              kd_kategori4: Ext.getCmp('spm_cbkategori4').getValue()
          }
      });
        }
    }
});

var str_spm_cbkategori3 = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: ['kd_kategori3', 'nama_kategori3'],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("kategori4/get_kategori3") ?>',
        method: 'POST'
    }),
    listeners: {
       load: function() {
        var r = new (str_spm_cbkategori3.recordType)({
         'kd_kategori3': '',
         'nama_kategori3': '-----'
     });
        str_spm_cbkategori3.insert(0, r);
    },
    loadexception: function(event, options, response, error){
        var err = Ext.util.JSON.decode(response.responseText);
        if (err.errMsg == 'Session Expired') {
            session_expired(err.errMsg);
        }
    }
}
});
var spm_cbkategori3 = new Ext.form.ComboBox({
    fieldLabel: 'Kategori 3 <span class="asterix">*</span>',
    id: 'spm_cbkategori3',
    mode: 'local',
    store: str_spm_cbkategori3,
    valueField: 'kd_kategori3',
    displayField: 'nama_kategori3',
    typeAhead: true,
    triggerAction: 'all',
    editable: false,
    width: 170,
    anchor: '90%',
    hiddenName: 'kd_kategori3',
    emptyText: 'Pilih kategori 3',
    listeners: {
        select: function(combo, records) {
            var kd_spm_cbkategori1 = spm_cbkategori1.getValue();
            var kd_spm_cbkategori2 = spm_cbkategori2.getValue();
            var kd_spm_cbkategori3 = this.getValue();
            spm_cbkategori4.setValue();
            spm_cbkategori4.store.proxy.conn.url = '<?= site_url("master_barang/get_kategori4") ?>/' + kd_spm_cbkategori1 +'/'+ kd_spm_cbkategori2 +'/'+ kd_spm_cbkategori3;
            spm_cbkategori4.store.reload();
            strsetparameter_margin.load({
             params:{
              kd_kategori1: Ext.getCmp('spm_cbkategori1').getValue(),
              kd_kategori2: Ext.getCmp('spm_cbkategori2').getValue(),
              kd_kategori3: Ext.getCmp('spm_cbkategori3').getValue(),
              kd_kategori4: Ext.getCmp('spm_cbkategori4').getValue()
          }
      });
        }
    }
});

var str_spm_cbkategori4 = new Ext.data.Store({
    reader: new Ext.data.JsonReader({
        fields: ['kd_kategori4', 'nama_kategori4'],
        root: 'data',
        totalProperty: 'record'
    }),
    proxy: new Ext.data.HttpProxy({
        url: '<?= site_url("master_barang/get_kategori4") ?>',
        method: 'POST'
    }),
    listeners: {
       load: function() {
        var r = new (str_spm_cbkategori4.recordType)({
         'kd_kategori4': '',
         'nama_kategori4': '-----'
     });
        str_spm_cbkategori4.insert(0, r);
    },
    loadexception: function(event, options, response, error){
        var err = Ext.util.JSON.decode(response.responseText);
        if (err.errMsg == 'Session Expired') {
            session_expired(err.errMsg);
        }
    }
}
});
var spm_cbkategori4 = new Ext.form.ComboBox({
    fieldLabel: 'Kategori 4 <span class="asterix">*</span>',
    id: 'spm_cbkategori4',
    mode: 'local',
    store: str_spm_cbkategori4,
    valueField: 'kd_kategori4',
    displayField: 'nama_kategori4',
    typeAhead: true,
    triggerAction: 'all',
    editable: false,
    width: 170,
    anchor: '90%',
    hiddenName: 'kd_kategori4',
    emptyText: 'Pilih kategori 4',
    listeners: {
        select: function(combo, records) {
            strsetparameter_margin.load({
             params:{
              kd_kategori1: Ext.getCmp('spm_cbkategori1').getValue(),
              kd_kategori2: Ext.getCmp('spm_cbkategori2').getValue(),
              kd_kategori3: Ext.getCmp('spm_cbkategori3').getValue(),
              kd_kategori4: Ext.getCmp('spm_cbkategori4').getValue()
          }
      });
        }
    }
});

var searchgridsetparameter_margin = new Ext.app.SearchField({
    store: strsetparameter_margin,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    emptyText: 'Kode Barang, Kode Barang Lama, Nama Barang',
    id: 'id_searchgridsetparameter_margin'
});
searchgridsetparameter_margin.onTrigger1Click = function(evt) {
  if (this.hasSearch) {
   this.el.dom.value = '';

   var kd_kategori1 = Ext.getCmp('spm_cbkategori1').getValue();
   var kd_kategori2 = Ext.getCmp('spm_cbkategori2').getValue();
   var kd_kategori3 = Ext.getCmp('spm_cbkategori3').getValue();
   var kd_kategori4 = Ext.getCmp('spm_cbkategori4').getValue();
   var o = { 	start: 0,
      kd_kategori1: kd_kategori1,
      kd_kategori2: kd_kategori2,
      kd_kategori3: kd_kategori3,
      kd_kategori4: kd_kategori4,
  };
  this.store.baseParams = this.store.baseParams || {};
  this.store.baseParams[this.paramName] = '';
  this.store.reload({
      params : o
  });
  this.triggers[0].hide();
  this.hasSearch = false;
}
};
searchgridsetparameter_margin.onTrigger2Click = function(evt) {
  var text = this.getRawValue();
  if (text.length < 1) {
      this.onTrigger1Click();
      return;
  }

  var kd_kategori1 = Ext.getCmp('spm_cbkategori1').getValue();
  var kd_kategori2 = Ext.getCmp('spm_cbkategori2').getValue();
  var kd_kategori3 = Ext.getCmp('spm_cbkategori3').getValue();
  var kd_kategori4 = Ext.getCmp('spm_cbkategori4').getValue();
  var o = { 	start: 0,
     kd_kategori1: kd_kategori1,
     kd_kategori2: kd_kategori2,
     kd_kategori3: kd_kategori3,
     kd_kategori4: kd_kategori4,
 };
 this.store.baseParams = this.store.baseParams || {};
 this.store.baseParams[this.paramName] = text;
 this.store.reload({params:o});
 this.hasSearch = true;
 this.triggers[0].show();
};
var searchgridsetparameter_margindetail = new Ext.app.SearchField({
    store: strsetparameter_margindetail,
    params: {
        start: STARTPAGE,
        limit: ENDPAGE
    },
    width: 350,
    id: 'id_searchgridsetparameter_margindetail'
});

searchgridsetparameter_margindetail.onTrigger1Click = function(evt) {
  if (this.hasSearch) {
   this.el.dom.value = '';

   var kd_kategori1 = Ext.getCmp('spm_cbkategori1').getValue();
   var kd_kategori2 = Ext.getCmp('spm_cbkategori2').getValue();
   var kd_kategori3 = Ext.getCmp('spm_cbkategori3').getValue();
   var kd_kategori4 = Ext.getCmp('spm_cbkategori4').getValue();
   var o = { 	start: 0,
      kd_kategori1: kd_kategori1,
      kd_kategori2: kd_kategori2,
      kd_kategori3: kd_kategori3,
      kd_kategori4: kd_kategori4,
  };
  this.store.baseParams = this.store.baseParams || {};
  this.store.baseParams[this.paramName] = '';
  this.store.reload({
      params : o
  });
  this.triggers[0].hide();
  this.hasSearch = false;
}
};
searchgridsetparameter_margindetail.onTrigger2Click = function(evt) {
  var text = this.getRawValue();
  if (text.length < 1) {
      this.onTrigger1Click();
      return;
  }

  var kd_kategori1 = Ext.getCmp('spm_cbkategori1').getValue();
  var kd_kategori2 = Ext.getCmp('spm_cbkategori2').getValue();
  var kd_kategori3 = Ext.getCmp('spm_cbkategori3').getValue();
  var kd_kategori4 = Ext.getCmp('spm_cbkategori4').getValue();
  var o = { 	start: 0,
     kd_kategori1: kd_kategori1,
     kd_kategori2: kd_kategori2,
     kd_kategori3: kd_kategori3,
     kd_kategori4: kd_kategori4,
 };
 this.store.baseParams = this.store.baseParams || {};
 this.store.baseParams[this.paramName] = text;
 this.store.reload({params:o});
 this.hasSearch = true;
 this.triggers[0].show();
};

var headersetparameter_margin = {
    layout: 'column',
    border: false,
    items: [{
        columnWidth: .5,
        layout: 'form',
        border: false,
        labelWidth: 100,
        defaults: { labelSeparator: ''},
        items: [
        spm_cbkategori1,spm_cbkategori2
        ]
    }, {
        columnWidth: .5,
        layout: 'form',
        border: false,
        labelWidth: 100,
        defaults: { labelSeparator: ''},
        items: [spm_cbkategori3,spm_cbkategori4]
    }]
}
var editorsetparameter_margin = new Ext.ux.grid.RowEditor({
    saveText: 'Update'
});

var gridsetparameter_margin= new Ext.grid.GridPanel({
    store: strsetparameter_margin,
    stripeRows: true,
    height: 400,
    frame: true,
    border:true,
    plugins: [editorsetparameter_margin],
    columns: [{
        dataIndex: 'koreksi_lokasi',
        header: 'Edited',
        width: 50,
        sortable: true,
        editor: {
            xtype:          'combo',
            store:          new Ext.data.JsonStore({
                fields : ['name'],
                data   : [
                {name : 'Y'},
                {name : 'No'}
                ]
            }),
            id: 'spm_edited',
            mode: 'local',
            name: 'edited',
            value: '%',
            width: 50,
            editable: false,
            hiddenName: 'edited',
            valueField: 'name',
            displayField: 'name',
            triggerAction: 'all',
            forceSelection: true
        }
    },{
        header: 'Kode Barang',
        dataIndex: 'kd_produk',
        width: 100,
        sortable: true,
        editor: new Ext.form.TextField({
            readOnly: true,
            id: 'kd_produkGrid'
        })
    },{
        header: 'kd_lokasi',
        dataIndex: 'kd_lokasi',
        width: 100,
        sortable: true,
        hidden: true
    },{
        header: 'Kode Barang Lama',
        dataIndex: 'kd_produk_lama',
        width: 110,
        sortable: true
    },{
        header: 'Nama Barang',
        dataIndex: 'nama_produk',
        width: 300,
        sortable: true
    },{
        header: 'Satuan',
        dataIndex: 'nm_satuan',
        width: 80
    },{
        header: 'Margin',
        dataIndex: 'parameter_margin',
        width: 80,
        editor: new Ext.form.TextField({
            readOnly: false,
            id: 'parameter_marginGrid'
        })
    },{
        header: '% / Rp',
        dataIndex: 'parameter_margin_rp',
        width: 50,
        editor: {
            xtype: 'combo',
            store: new Ext.data.JsonStore({
                fields : ['name'],
                data   : [
                {name : '%'},
                {name : 'Rp'}
                ]
            }),
            id:           	'parameter_margin_rpGrid',
            mode:           'local',
            name:           'edited',
            value:          '%',
            width:			50,
            editable:       false,
            hiddenName:     'edited',
            valueField:     'name',
            displayField:   'name',
            triggerAction:  'all',
            forceSelection: true
        }
    },{
        header: 'Markup',
        dataIndex: 'parameter_markup',
        width: 80,
        editor: new Ext.form.TextField({
            readOnly: false,
            id: 'parameter_markupGrid'
        })
    },{
        header: '% / Rp',
        dataIndex: 'parameter_markup_rp',
        width: 50,
        editor: {
            xtype: 'combo',
            store: new Ext.data.JsonStore({
                fields : ['name'],
                data   : [
                {name : '%'},
                {name : 'Rp'}
                ]
            }),
            id:           	'parameter_markup_rpGrid',
            mode:           'local',
            name:           'edited',
            value:          '%',
            width:			50,
            editable:       false,
            hiddenName:     'edited',
            valueField:     'name',
            displayField:   'name',
            triggerAction:  'all',
            forceSelection: true
        }
    }

    ],
    tbar: new Ext.Toolbar({
     items: [searchgridsetparameter_margin]
 }),
    listeners:{
      'rowclick': function(){
        var sm = gridsetparameter_margin.getSelectionModel();
        var sel = sm.getSelections();
        Ext.getCmp('grid_kd_produk').setValue(sel[0].get('kd_produk'));



    }
}
});

var actionsetparameter_margin = new Ext.ux.grid.RowActions({
  header :'Edit',
  autoWidth: false,
  locked: true,
  width: 30,
  actions:[{iconCls: 'icon-edit-record', qtip: 'Edit'}],
  widthIntercept: Ext.isSafari ? 4 : 2
});
var actionsetparameter_margindel = new Ext.ux.grid.RowActions({
  header: 'Delete',
  autoWidth: false,
  width: 40,
  actions:[{iconCls: 'icon-delete-record', qtip: 'Delete'}],
  widthIntercept: Ext.isSafari ? 4 : 2
});
actionsetparameter_margindel.on('action', function(grid, record, action, row, col) {
    var kd_prod = record.get('kd_produk');
    var kd_lokasi = record.get('kd_lokasi');
    var kd_blok = record.get('kd_blok');
    var kd_sub_blok = record.get('kd_sub_blok');
    switch(action) {
        case 'icon-edit-record':
        editsetparameter_margin(kd_prod,kd_lokasi,kd_blok,kd_sub_blok);
        break;
        case 'icon-delete-record':
        Ext.Msg.show({
            title: 'Confirm',
            msg: 'Are you sure delete selected row ?',
            buttons: Ext.Msg.YESNO,
            fn: function(btn){
                if (btn == 'yes') {
                    Ext.Ajax.request({
                        url: '<?= site_url("setparameter_margin/delete_row") ?>',
                        method: 'POST',
                        params: {
                            kd_produk: kd_prod,
                            kd_lokasi: kd_lokasi,
                            kd_blok: kd_blok,
                            kd_sub_blok:kd_sub_blok
                        },
                        callback:function(opt,success,responseObj){
                            var de = Ext.util.JSON.decode(responseObj.responseText);
                            if(de.success==true){
                                strsetparameter_margindetail.reload();
                                strsetparameter_margindetail.load({
                                   params:{
                                    kd_produk: kd_prod,
                                }
                            });
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
}
});
break;
}
});

var setparameter_margin = new Ext.FormPanel({
    id: 'setparameter_margin',
    border: false,
    frame: true,
    autoScroll: true,
    monitorValid: true,
    bodyStyle: 'padding-right:20px;',
    labelWidth: 130,
    items: [{
        bodyStyle: { margin: '0px 0px 15px 0px' },
        items: [headersetparameter_margin]
    }, {
        xtype: 'fieldset',
        autoheight: true,
        title: 'Margin & Markup',
        collapsed: false,
        collapsible: true,
        anchor: '40%',
        items: [{
            xtype: 'compositefield',
            msgTarget: 'side',
            fieldLabel: 'Margin',
            width: 200,
            items: [{
                xtype: 'combo',
                mode: 'local',
                value: 'persen',
                triggerAction: 'all',
                forceSelection: true,
                editable: false,
                name: 'margin_persen',
                id: 'id_spm_margin_persen',
                hiddenName: 'margin_persen',
                displayField: 'name',
                valueField: 'value',
                width: 50,
                store: new Ext.data.JsonStore({
                    fields: ['name', 'value'],
                    data: [{name: '%', value: 'persen'}, {name: 'Rp', value: 'amount'}]
                })
            }, {
                xtype: 'numberfield',
                flex: 1,
                width: 115,
                name: 'margin',
                allowBlank: false,
                id: 'id_spm_margin',
                style: 'text-align:right;',
                value: 0,
            }]
        }, {
            xtype: 'compositefield',
            msgTarget: 'side',
            fieldLabel: 'Markup',
            width: 200,
            items: [{
                xtype: 'combo',
                mode: 'local',
                value: 'persen',
                triggerAction: 'all',
                forceSelection: true,
                editable: false,
                name: 'markup_persen',
                id: 'id_spm_markup_persen',
                hiddenName: 'markup_persen',
                displayField: 'name',
                valueField: 'value',
                width: 50,
                store: new Ext.data.JsonStore({
                    fields: ['name', 'value'],
                    data: [{name: '%',value: 'persen'}, {name: 'Rp',value: 'amount'}]
                })
            }, {
                xtype: 'numberfield',
                flex: 1,
                width: 115,
                name: 'margin',
                allowBlank: false,
                id: 'id_spm_markup',
                style: 'text-align:right;',
                value: 0,
            }]
        }],
        buttons: [{
            text: 'Apply',
            handler: function () {
                var xmargin =  Ext.getCmp('id_spm_margin').getRawValue();
                var xmargin_persen = Ext.getCmp('id_spm_margin_persen').getRawValue();
                var xmarkup =  Ext.getCmp('id_spm_markup').getRawValue();
                var xmarkup_persen =  Ext.getCmp('id_spm_markup_persen').getRawValue();
                strsetparameter_margin.each(function (record) {
                    record.set('koreksi_lokasi', 'Y');
                    record.set('parameter_margin', xmargin);
                    record.set('parameter_margin_rp', xmargin_persen);
                    record.set('parameter_markup', xmarkup);
                    record.set('parameter_markup_rp', xmarkup_persen);
                    record.commit();
                });
            }
        }]
    },
    gridsetparameter_margin
    ],
    buttons: [{
        text: 'Save',
        formBind: true,
        handler: function () {
            var detailmargin = new Array();
            strsetparameter_margin.each(function(node){
                detailmargin.push(node.data)
            });
            Ext.getCmp('setparameter_margin').getForm().submit({
                url: '<?= site_url("setparameter_margin/update_row") ?>',
                scope: this,
                params: {
                    detail: Ext.util.JSON.encode(detailmargin),
                },
                waitMsg: 'Saving Data...',
                success: function(form, action){
                    Ext.Msg.show({
                        title: 'Success',
                        msg: 'Form submitted successfully',
                        modal: true,
                        icon: Ext.Msg.INFO,
                        buttons: Ext.Msg.OK
                    });
                    clearsetparameter_margin();
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
    }, {
        text: 'Reset',
        handler: function () { clearsetparameter_margin(); }
    }]
});

setparameter_margin.on('afterrender', function(){
    this.getForm().load({
        url: '<?= site_url("setparameter_margin/get_form") ?>',
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
});

function clearsetparameter_margin(){
    Ext.getCmp('setparameter_margin').getForm().reset();
    Ext.getCmp('setparameter_margin').getForm().load({
        url: '<?= site_url("setparameter_margin/get_form") ?>',
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
    strsetparameter_margin.removeAll();
}

function editsetparameter_margin(kd_produk,kd_lokasi,kd_blok,kd_sub_blok){
    strcbkdprodukspb.load();
    Ext.getCmp('id_action').setValue('Update');
    Ext.getCmp('btnresetsetparameter_margin').hide();
    Ext.getCmp('btnsubmitsetparameter_margin').setText('Update');
    winaddsetparameter_margin.setTitle('Edit Form');
    Ext.getCmp('id_formaddsetparameter_margin').getForm().load({
        url: '<?= site_url("setparameter_margin/get_row") ?>',
        params: {
            kd_produk: kd_produk,
            kd_lokasi: kd_lokasi,
            kd_blok: kd_blok,
            kd_sub_blok: kd_sub_blok,
            cmd: 'get'
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
    winaddsetparameter_margin.show();
}
</script>
