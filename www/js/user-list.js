$.get( window.location.pathname, function (data)
{
    texts = data.texts;
    query = data.query;
    language = data.language;
})

$(function()
{
    $( '#users-table' ).DataTable(
    {
        dom: "B<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        cache: true,
        bAutoWidth: false,
        responsive: true,
        columnDefs: [
            { targets: 0, data: 'id' },
            { targets: 1, data: 'email' },
            { targets: 2, data: 'name', type: 'natural' },
            { targets: 3, data: 'role' },
            {
                targets: 4,
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
            { targets: 5, data: 'created_at', render: $.fn.dataTable.render.moment( 'X', 'DD/MM/YYYY', '$lang' ) },
            {
                data: null,
                targets: 6,
                render: function ( data, type, row ) {
                    return '<a href="/user/view/' + data.id + '" title="' + texts.title.view + '"><i class="fa fa-eye"></i></a>' +
                           '&nbsp;' +
                           '<a href="/user/delete/' + data.id + '" title="' + texts.title.delete + '"><i class="fa fa-trash"></i></a>';
                }
            }
        ],
        data: query,
        buttons: getTableButtons(true, true),
        language:
        {
            url: language,
            processing: '<div class="lds-dual-ring"></div>'
        }
    })
})
