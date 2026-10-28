<?php defined('BASEPATH') OR exit('No direct script access allowed'); echo '
<table id="TblClients" class="table table-hover" style="width:100% !important;">
    <thead>
        <tr>
            <th>ID</th>
            <th>Client ID</th>
            <th>Nama</th>
            <th>Tgl. Lahir</th>
            <th>Gender</th>
            <th>Telp</th>
            <th width="120">#</th>
        </tr>
    </thead>
    <tbody>
    </tbody>
</table>';?>
<script src="<?php echo base_url('assets/plugins/jquery/jquery-3.4.1.min.js');?>"></script> 
<script src="<?php echo base_url('assets/plugins/datatable/datatables.min.js');?>"></script> 
<script> 
    $('#TblClients').dataTable({
        'processing'	: true,
        'serverSide'	: true,
        'paging'		: true,
        'info'			: true,
        'filter'		: true,
        'responsive'	: true,
        'ajax'			: {'url': '<?php echo site_url('dass/client_datatable');?>', 'method':'post'},
        'order'			: [[ 0, 'DESC' ]],
        'pageLength'	: 10,
        'lengthMenu'	: [[10,25,50,100,-1], [10,25,50,100,'All']], 
        'columns'		: [	 
                            { 'data': 'id' },
                            { 'data': 'client_no' },
                            { 'data': 'client_name' }, 
                            { 'data': 'client_birthday' }, 
                            { 'data': 'client_gender' },
                            { 'data': 'client_phone' }, 
                            { 'data': 'actions' },
                            ],
        'columnDefs'	: [ 
                            {
                                'targets': [0],
                                'visible': false,
                                'searchable': false,
                                'orderable': true,
                            },
                            {
                                'targets': [6],
                                'visible': true,
                                'searchable': false,
                                'orderable': false,
                            }
                            ],
        'createdRow': function (row, data) {
            $(row).attr('id', 'row_' + data.test_id); 
            $('td', row).eq(7).attr('class', 'td-action');
        },
        'language': {
            'search' : '',
            'searchPlaceholder': 'Search Clients...'
        }
    });
</script>