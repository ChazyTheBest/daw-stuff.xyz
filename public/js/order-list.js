$.get( window.location.pathname, function (data)
{
    texts = data.texts;
    query = data.query;
    language = data.language;
})

$(function()
{
    $( '#order-table' ).DataTable(
    {
        dom: "B<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        cache: true,
        bAutoWidth: false,
        responsive: true,
        columnDefs: [
            { targets: 0, data: 'id' },
            { targets: 1, data: 'reference' },
            { targets: 2, data: 'total', render: $.fn.dataTable.render.number(',', '.', 2, '', ' &euro;') },
            {
                targets: 3,
                data: null,
                render: function ( data ) {
                    let html = '<select class="custom-select" style="width:auto;">'
                    for (let i in texts.status)
                    {
                        html += '<option value="' + i + '"' + (data.status === parseInt(i) ? ' selected' : '') + '>' + texts.status[i] + '</option>';
                    }
                    return html + '</select>';
                },
                createdCell: function ( td, cellData, rowData, row, col ) {
                    $( td ).attr('data-filter', texts.status[cellData.status]);
                    $( td ).attr('data-search', texts.status[cellData.status]);
                }
            },
            { targets: 4, data: 'created_at', render: $.fn.dataTable.render.moment( 'X', 'DD/MM/YYYY', '$lang' ) },
            { targets: 5, data: null, render: function ( data ) { return '<a href="/user/view/' + data.created_by + '"><i class="fa fa-eye"></i></a>' } },
            {
                data: null,
                targets: 6,
                render: function ( data, type, row ) {
                    return '<a href="/order/view/' + data.id + '" title="' + texts.title.view + '"><i class="fa fa-eye"></i></a>' +
                           '&nbsp;' +
                           '<a href="/order/delete/' + data.id + '" title="' + texts.title.delete + '"><i class="fa fa-trash"></i></a>';
                }
            }
        ],
        data: query,
        buttons: getTableButtons(false, true),
        language:
        {
            url: language,
            processing: '<div class="lds-dual-ring"></div>'
        }
    })
})
