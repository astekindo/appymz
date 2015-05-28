<?= $side_menu?>
<div class="grid_10">
    <div class="box round first">
        <h1 class="custom_reportheadline">Custom Report List</h1>
        <div class="block">
            <!-- Form -->
            <div class="addcustbutton">
                <a href="<?= site_url('custom_report_query/add') ?>" class="btn btn-small"><i class="icon-plus"></i>Add Custom Report</a>
            </div>
            <div class="dataTables_wrapper">
                <div class="dataTables_length">
                    <label>
                        Show 
                        <select size="1" id="length" name="length" onchange="load_datatable('<?= site_url('custom_report_query/load_datatable') ?>')">
                            <option value="10">10</option>
                            <option value="20">20</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                        entries
                    </label>
                </div>
                <div class="dataTables_filter">                	
                    <label>
                        Search: <input type="text" name="search" id="search" onkeypress="load_datatable('<?= site_url('custom_report_query/load_datatable/0') ?>')"/>
                    </label>					
                </div>
				<!-- DataTable -->
                <table class="data display datatable tabel" id="datatable">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th>Nama Report</th>
                            <th>Request By</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>              
                        <tr><td align="center" colspan="6">&nbsp;</td></tr>
                    </tbody>
                </table>
                <div class="dataTables_info" id="dataTables_info"></div>
                <div class="dataTables_paginate paging_two_button" id="dataTables_paginate"></div>
            </div>
        </div>
    </div>
</div>
