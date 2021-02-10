$.get( window.location.pathname, function (data)
{
    texts = data.texts;
    query = data.query;
    language = data.language;
})

$(function()
{
    $( '#category-table' ).DataTable(
    {
        dom: "B<'row'<'col-sm-12 col-md-6'l><'col-sm-12 col-md-6'f>><'row'<'col-sm-12'tr>><'row'<'col-sm-12 col-md-5'i><'col-sm-12 col-md-7'p>>",
        cache: true,
        bAutoWidth: false,
        responsive: true,
        columnDefs: [
            {
                targets: 0,
                data: null,
                render: function ( data ) {
                    return '<input type="checkbox" class="status form-check-input ml-3"' + (data.status === 1 ? ' checked' : '') + '>';
                },
                width: '12%'
            },
            {
                targets: 1,
                data: null,
                render: function ( data ) {
                    return '<img class="" src="/img/categories/' + data.image + '" alt="category">';
                }
            },
            { targets: 2, data: 'name' },
            { targets: 3, data: 'type' },
            {
                targets: 4,
                data: null,
                render: function ( data, type, row ) {
                    return '<a href="/category/update/' + data.id + '" title="' + texts.title.view + '"><i class="fa fa-eye"></i></a>' +
                           '&nbsp;' +
                           '<a href="/category/delete/' + data.id + '" title="' + texts.title.delete + '"><i class="fa fa-trash"></i></a>';
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
