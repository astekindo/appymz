<?php
if (!defined('BASEPATH'))
    exit('No direct script access allowed');
?>
<script type="text/javascript">	
   
    function createStoreArray(mfields,mdata){
        return new Ext.data.ArrayStore({
            fields: mfields,
            data : mdata
        });
    }
    function createStoreData(mfields,murl){
        return new Ext.data.Store({
            reader: new Ext.data.JsonReader({
                fields: mfields,
                root: 'data',
                totalProperty: 'record'
            }),
            proxy: new Ext.data.HttpProxy({
                url: murl,
                method: 'POST'
            }),
            listeners: {			
                loadexception: function(event, options, response, error){
                    var err = Ext.util.JSON.decode(response.responseText);
                    //                    console.log(response);
                    if(!err){
                        err =response.statusText;
                        err =err + ' '+'Session Expired'
                        session_expired(response.statusText);
                    }else{
                        if (err.errMsg == 'Session Expired') {
                            session_expired(err.errMsg);
                        }
                    }
                    
                }
            }
        });
    }
    function createSearchField(mid,mstore,mwidth){
        return new Ext.app.SearchField({
            store: mstore,
            params: {
                start: STARTPAGE,
                limit: ENDPAGE			
            },
            width: mwidth,
            id: mid
        });
    }
    function setPanelMenu(menu,mtitle,mwidth,mheight,mgrid,funct_close,funct_hide){
        menu.add(
        new Ext.Panel({
            title: mtitle,
            layout: 'fit',
            buttonAlign: 'left',
            modal: true,
            width: mwidth,
            height: mheight,
            closeAction: 'hide',
            plain: true,
            items: [mgrid],
            buttons: [{
                    text: 'Close',
                    handler: funct_close
                }]
        })
    );
        menu.on('hide',funct_hide);
    } 
    
    function setPanelMenu2(menu,mtitle,mwidth,mheight,mgrid,txtvar,funct_var,funct_close,funct_hide){
        menu.add(
        new Ext.Panel({
            title: mtitle,
            layout: 'fit',
            buttonAlign: 'left',
            modal: true,
            width: mwidth,
            height: mheight,
            closeAction: 'hide',
            plain: true,
            items: [mgrid],
            buttons: [{
                    text: txtvar,
                    handler: funct_var
                },{
                    text: 'Close',
                    handler: funct_close
                }
            ]
        })
    );
        menu.on('hide',funct_hide);
    } 
   
    function createWinCetak(vid,vtitle,vidframe){ 
        return new Ext.Window({
            id: vid,
            title: vtitle,
            closeAction: 'hide',
            width: 900,
            height: 450,
            layout: 'fit',
            border: false,
            maximizable:true,
            html:'<iframe style="width:100%;height:100%;" id="'+vidframe+'" src=""></iframe>'
        });
    }
    var ret_DKvoucher='';
    function validateDKvoucher(mkode,mkdakun,ret,def){
        ret_DKvoucher='';
        Ext.Ajax.request({
            url: '<?= site_url("transaksi/get_dk_akunvoucher") ?>',
            method: 'POST',
            params: {
                kdvoucher: mkode,
                kdakun:mkdakun
            },
            callback:function(opt,success,responseObj){
                var de = Ext.util.JSON.decode(responseObj.responseText);
                if(de.success==true){
                    //                 console.log(de);
                    ret_DKvoucher=de.dk;
                    ret_DKvoucher=ret_DKvoucher.trim()
                    if (ret_DKvoucher=='d' || ret_DKvoucher=='k'){
                       
                        Ext.getCmp(ret).setValue(ret_DKvoucher.toUpperCase()); 
                    }else{
                        Ext.getCmp(ret).setValue(def); 
                    }
                   
                }
            }
        });        
    }
    var ret_kdcc='';
    function validateCostCenter(mkode,mkdakun,ret,ret2,def){
        ret_kdcc='';
        Ext.Ajax.request({
            url: '<?= site_url("transaksi/get_akun_costcenter") ?>',
            method: 'POST',
            params: {
                kdcc: mkode,
                kdakun:mkdakun
            },
            callback:function(opt,success,responseObj){
                var de = Ext.util.JSON.decode(responseObj.responseText);
                if(de.success==true){                    //                 console.log(de);
                    ret_kdcc=de.kdcc;                   
                    var nmcc=de.nmcc;
                    Ext.getCmp(ret).setValue(ret_kdcc); 
                    Ext.getCmp(ret2).setValue(nmcc); 
                }
                if(de.success==false){                    //                 console.log(de);
                    ret_kdcc=de.kdcc;                   
                    var nmcc=de.nmcc;
                    Ext.getCmp(ret).setValue(ret_kdcc); 
                    Ext.getCmp(ret2).setValue(nmcc); 
                }
                
            }
        });        
    }
    var autopos=false;
    var autopose=false;
    
    var str_limitapproval = createStoreData(['startapv1','endapv1','startapv2','endapv2','startapv3','endapv3'],'<?= site_url("account_limit_approval/get_row_data") ?>');
    var str_limitapprovale = createStoreData(['startapv1','endapv1','startapv2','endapv2','startapv3','endapv3'],'<?= site_url("account_limit_approval/get_row_data") ?>');
    
    function set_message(opt,vmsg,func){
        switch(opt) {
            case 0:
                Ext.Msg.show({
                    title: 'Message Info',
                    msg: vmsg,
                    modal: true,
                    closable: false,
                    icon: Ext.Msg.INFO,
                    buttons: Ext.Msg.OK,
                    fn: func
                });               
                break;
            case 1:
                Ext.Msg.show({
                    title: 'Message Error',
                    msg: vmsg,
                    modal: true,
                    closable: false,
                    icon: Ext.Msg.ERROR,
                    buttons: Ext.Msg.OK,
                    fn: func
                });  
                break;
            case 2:
                Ext.Msg.show({
                    title: 'Message Warning',
                    msg: vmsg,
                    modal: true,
                    closable: false,
                    icon: Ext.Msg.WARNING,
                    buttons: Ext.Msg.OK,
                    fn: func
                });  
                break;
                
            }			
    }
    Ext.apply(Ext.form.VTypes, {
        daterange : function(val, field) {
            var date = field.parseDate(val);

            if(!date){
                return;
            }
            if (field.startDateField && (!this.dateRangeMax || (date.getTime() != this.dateRangeMax.getTime()))) {
                var start = Ext.getCmp(field.startDateField);
                start.setMaxValue(date);
                start.validate();
                this.dateRangeMax = date;
            } 
            else if (field.endDateField && (!this.dateRangeMin || (date.getTime() != this.dateRangeMin.getTime()))) {
                var end = Ext.getCmp(field.endDateField);
                end.setMinValue(date);
                end.validate();
                this.dateRangeMin = date;
            }
            /*
             * Always return true since we're only using this vtype to set the
             * min/max allowed values (these are tested for after the vtype test)
             */
            return true;
        }
    });
    var keyStr = "ABCDEFGHIJKLMNOPQRSTUVWXYZabcdefghijklmnopqrstuvwxyz0123456789+/=";
    function utf8Encode(string) {
        string = string.replace(/\r\n/g,"\n");
        var utftext = "";
        for (var n = 0; n < string.length; n++) {
            var c = string.charCodeAt(n);
            if (c < 128) {
                utftext += String.fromCharCode(c);
            }
            else if((c > 127) && (c < 2048)) {
                utftext += String.fromCharCode((c >> 6) | 192);
                utftext += String.fromCharCode((c & 63) | 128);
            }
            else {
                utftext += String.fromCharCode((c >> 12) | 224);
                utftext += String.fromCharCode(((c >> 6) & 63) | 128);
                utftext += String.fromCharCode((c & 63) | 128);
            }
        }
        return utftext;
    }
    function encode_base64 (input) {
            var output = "";
            var chr1, chr2, chr3, enc1, enc2, enc3, enc4;
            var i = 0;
            input = utf8Encode(input);
            while (i < input.length) {
                chr1 = input.charCodeAt(i++);
                chr2 = input.charCodeAt(i++);
                chr3 = input.charCodeAt(i++);
                enc1 = chr1 >> 2;
                enc2 = ((chr1 & 3) << 4) | (chr2 >> 4);
                enc3 = ((chr2 & 15) << 2) | (chr3 >> 6);
                enc4 = chr3 & 63;
                if (isNaN(chr2)) {
                    enc3 = enc4 = 64;
                } else if (isNaN(chr3)) {
                    enc4 = 64;
                }
                output = output +
                keyStr.charAt(enc1) + keyStr.charAt(enc2) +
                keyStr.charAt(enc3) + keyStr.charAt(enc4);
            }
            return output;
        }
    

    function toCSV(grid) {
        var store_xls = grid.getStore(), xlCol, field, result = '', separator = '';
        for (xlCol = 0; xlCol < store_xls.fields.length; xlCol++) {
            field = store_xls.fields.itemAt(xlCol).name;
            result += separator+'"'+field+'"';
            separator = '\t';
        }
        result += '\r';
        store_xls.each(function(record) {
            separator = '';
            for (xlCol = 0; xlCol < record.fields.length; xlCol++) {
                var field = store_xls.fields.itemAt(xlCol).name;
                //you could make a difference for other types than text
                result += separator+'"'+record.get(field)+'"';
                separator = '\t';
            }
            result += '\r';
        });
        return result;
    }
    
     function createWorksheet(grid,includeHidden) {

//      Calculate cell data types and extra class names which affect formatting
        var cellType = [];
        var cellTypeClass = [];
        var cm = grid.getColumnModel();
        var totalWidthInPixels = 0;
        var colXml = '';
        var headerXml = '';
        for (var i = 0; i < cm.getColumnCount(); i++) {
            if (includeHidden || !cm.isHidden(i)) {
                var w = cm.getColumnWidth(i)
                totalWidthInPixels += w;
                colXml += '<ss:Column ss:AutoFitWidth="1" ss:Width="' + w + '" />';
                headerXml += '<ss:Cell ss:StyleID="headercell">' +
                    '<ss:Data ss:Type="String">' + cm.getColumnHeader(i) + '</ss:Data>' +
                    '<ss:NamedCell ss:Name="Print_Titles" /></ss:Cell>';
                var fld = grid.store.recordType.prototype.fields.get(cm.getDataIndex(i));
//                console.log(fld);
                switch(fld.type.type) {
                    case "int":
                        cellType.push("Number");
                        cellTypeClass.push("int");
                        break;
                    case "float":
                        cellType.push("Number");
                        cellTypeClass.push("float");
                        break;
                    case "bool":
                    case "boolean":
                        cellType.push("String");
                        cellTypeClass.push("");
                        break;
                    case "date":
                        cellType.push("DateTime");
                        cellTypeClass.push("date");
                        break;
                    case "string":
                        cellType.push("String");
                        cellTypeClass.push("");
                        break;
                    default:
                        cellType.push("String");
                        cellTypeClass.push("");
                        break;
                }
            }
        }
        var visibleColumnCount = cellType.length;

        var result = {
            height: 9000,
            width: Math.floor(totalWidthInPixels * 30) + 50
        };

//      Generate worksheet header details.
        var t = '<ss:Worksheet ss:Name="' + grid.title + '">' +
            '<ss:Names>' +
                '<ss:NamedRange ss:Name="Print_Titles" ss:RefersTo="=\'' + grid.title + '\'!R1:R2" />' +
            '</ss:Names>' +
            '<ss:Table x:FullRows="1" x:FullColumns="1"' +
                ' ss:ExpandedColumnCount="' + visibleColumnCount +
                '" ss:ExpandedRowCount="' + (grid.store.getCount() + 2) + '">' +
                colXml +
                '<ss:Row ss:Height="38">' +
                    '<ss:Cell ss:StyleID="title" ss:MergeAcross="' + (visibleColumnCount - 1) + '">' +
                      '<ss:Data xmlns:html="http://www.w3.org/TR/REC-html40" ss:Type="String">' +
                        '<html:B><html:U><html:Font html:Size="15">' + grid.title +
                        '</html:Font></html:U></html:B> Generated by ExtJs </ss:Data><ss:NamedCell ss:Name="Print_Titles" />' +
                    '</ss:Cell>' +
                '</ss:Row>' +
                '<ss:Row ss:AutoFitHeight="1">' +
                headerXml + 
                '</ss:Row>';

//      Generate the data rows from the data in the Store
       
        for (var i = 0, it = grid.store.data.items, l = it.length; i < l; i++) {
            t += '<ss:Row>';
            var cellClass = (i & 1) ? 'odd' : 'even';
            r = it[i].data;
            var k = 0;
            for (var j = 0; j < cm.getColumnCount(); j++) {
                if (includeHidden || !cm.isHidden(j)) {
                    var v = r[cm.getDataIndex(j)];
                    t += '<ss:Cell ss:StyleID="' + cellClass + cellTypeClass[k] + '"><ss:Data ss:Type="' + cellType[k] + '">';
                        if (cellType[k] == 'DateTime') {
                            t += v.format('Y-m-d');
                        } else {
                            t += v;
                        }
                    t +='</ss:Data></ss:Cell>';
                    k++;
                }
            }
            t += '</ss:Row>';
        }

        result.xml = t + '</ss:Table>' +
            '<x:WorksheetOptions>' +
                '<x:PageSetup>' +
                    '<x:Layout x:CenterHorizontal="1" x:Orientation="Landscape" />' +
                    '<x:Footer x:Data="Page &amp;P of &amp;N" x:Margin="0.5" />' +
                    '<x:PageMargins x:Top="0.5" x:Right="0.5" x:Left="0.5" x:Bottom="0.8" />' +
                '</x:PageSetup>' +
                '<x:FitToPage />' +
                '<x:Print>' +
                    '<x:PrintErrors>Blank</x:PrintErrors>' +
                    '<x:FitWidth>1</x:FitWidth>' +
                    '<x:FitHeight>32767</x:FitHeight>' +
                    '<x:ValidPrinterInfo />' +
                    '<x:VerticalResolution>600</x:VerticalResolution>' +
                '</x:Print>' +
                '<x:Selected />' +
                '<x:DoNotDisplayGridlines />' +
                '<x:ProtectObjects>False</x:ProtectObjects>' +
                '<x:ProtectScenarios>False</x:ProtectScenarios>' +
            '</x:WorksheetOptions>' +
        '</ss:Worksheet>';
        return result;
    };
    
    
    function getExcelXml(grid,includeHidden) {
        var worksheet = createWorksheet(grid,includeHidden);
        var totalWidth = grid.getColumnModel().getTotalWidth(includeHidden);
        return '';        
    };
    
    
    
    var tableToExcel = (function() {
  var uri = 'data:application/vnd.ms-excel;base64,'
    , template = '<html xmlns:o="urn:schemas-microsoft-com:office:office" xmlns:x="urn:schemas-microsoft-com:office:excel" xmlns="http://www.w3.org/TR/REC-html40"><head><!--[if gte mso 9]><xml><x:ExcelWorkbook><x:ExcelWorksheets><x:ExcelWorksheet><x:Name>{worksheet}</x:Name><x:WorksheetOptions><x:DisplayGridlines/></x:WorksheetOptions></x:ExcelWorksheet></x:ExcelWorksheets></x:ExcelWorkbook></xml><![endif]--></head><body><table>{table}</table></body></html>'
    , base64 = function(s) { return window.btoa(unescape(encodeURIComponent(s))) }
    , format = function(s, c) { return s.replace(/{(\w+)}/g, function(m, p) { return c[p]; }) }
  return function(table, name) {
//    if (!table.nodeType) table = document.getElementById(table)
//   var ctx = createWorksheet(Ext.getCmp('bukubesar'));
//    var ctx = {worksheet: name || 'Worksheet', table: table.innerHTML}
//    window.location.href = uri + base64(format(template, ctx))
  }
})();

    
</script>
<?php
//if (!defined('BASEPATH'))
//    exit('No direct script access allowed');

//$this->load->view('account/paccount');
//$this->load->view('account/export_excel');
$this->load->view('account/master_account');
//	$this->load->view('account/buku');

$this->load->view('account/limit_approval');
$this->load->view('account/jenis_voucher');
$this->load->view('account/transaksi');
$this->load->view('account/entryvoucher');
//        $this->load->view('account/catatjurnal');
$this->load->view('account/approvalvoucher');
$this->load->view('account/approvalvoucher2');
$this->load->view('account/approvalvoucher3');
$this->load->view('account/posting_voucher');

$this->load->view('account/masterjurnalpenutup');
//        $this->load->view('account/postingjurnalpenutup');
//        $this->load->view('account/approvaljurnalpenutup');
$this->load->view('account/monitoringjurnal');
$this->load->view('account/monitoringapproval');
$this->load->view('account/cetakvoucher');
//$this->load->view('account/testexport');
//$this->load->view('account/bukutest');
$this->load->view('account/bukubesar');
$this->load->view('account/mastercostcenter');
$this->load->view('account/rugilaba');
$this->load->view('account/neraca');
$this->load->view('account/neracasaldo');
$this->load->view('account/master_closing_acc');
$this->load->view('account/closing_acc');
$this->load->view('account/rejectapproval');
$this->load->view('account/editvoucher');

?>
