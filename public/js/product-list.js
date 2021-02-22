$.get( window.location.pathname, function (data)
{
    texts = data.texts;
    query = data.query;
    language = data.language;
})

$(function()
{
    const table = $( '#product-table' ).DataTable(
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
                    return '<input type="checkbox" class="status form-check-input ml-3"' + (data.status === 1 ? ' checked' : '') +
                                                 ' data-id="' + data.id + '">';
                },
                width: '12%'
            },
            {
                targets: 1,
                data: null,
                render: function ( data ) {
                    const img_url = data.image.startsWith('https') ? data.image : '/img/products/' + data.image;
                    return '<img class="" style="width: 100px; height: 65px" src="' + img_url + '" alt="product">';
                }
            },
            { targets: 2, data: 'name' },
            { targets: 3, data: 'price', render: $.fn.dataTable.render.number(',', '.', 2, '', ' &euro;') },
            { targets: 4, data: null, render: function ( data ) { return (data.discount === '0.000' ? 0 : data.discount * 100) + ' %' } },
            {
                targets: 5,
                data: null,
                render: function ( data ) {
                    let html = '<select class="cat custom-select" style="width:auto;" data-id="' + data.id + '">' +
                                   '<option value="null"' + (!data.category_id ? ' selected' : '') + '>' + texts.none + '</option>';

                    Object.keys(texts.categories).forEach(function (i) {
                        html += '<option value="' + i + '"' + (data.category_id === parseInt(i) ? ' selected' : '') + '>' + texts.categories[i].name + '</option>';
                    });

                    return html + '</select>';
                }
            },
            {
                targets: 6,
                data: null,
                render: function ( data ) {
                    const sub = texts.categories[data.category_id]?.subcategories;
                    let html = '<select class="sub custom-select" style="width:auto;" data-id="' + data.id + '">' +
                                   '<option value="null"' + (!data.subcategory_id ? ' selected' : '') + '>' + texts.none + '</option>';

                    if (sub)
                    {
                        Object.keys(sub).forEach(function (i) {
                            html += '<option value="' + sub[i].id + '"' + (data.subcategory_id === parseInt(sub[i].id) ? ' selected' : '') + '>' + sub[i].name + '</option>';
                        });
                    }

                    return html + '</select>';
                }
            },
            {
                targets: 7,
                data: null,
                render: function ( data, type, row ) {
                    return '<a href="/product/update/' + data.id + '" title="' + texts.title.view + '"><i class="fa fa-eye"></i></a>' +
                           '&nbsp;' +
                           '<a class="delete" href="/product/delete/' + data.id + '" title="' + texts.title.delete + '"><i class="fa fa-trash"></i></a>';
                }
            }
        ],
        data: query,
        buttons: getTableButtons(true, false),
        language:
        {
            url: language,
            processing: '<div class="lds-dual-ring"></div>'
        }
    });

    $( '#product-table tbody' )

        // enable/disable product
        .on( 'change', 'input.status', function ()
        {
            $.get('/product/update/' + $(this).data("id") + '?status='
                                     + ($(this).is(':checked') ? 'enable' : 'disable'), function (data)
            {
                if (data.redirect === 'reload')
                    window.location.reload(true);
            });
        })

        // change category and replace subcategories
        .on( 'change', 'select.cat', function ()
        {
            const select = $(this), subselect = $('select.sub[data-id=' + select.data('id') + ']');

            $.get('/product/update/' + select.data("id") + '?cat=' + $(this).val(), function (data)
            {
                if (data.message !== undefined && data.message !== '')
                    alert(data.message)

                let html = '<option value="null">' + texts.none + '</option>';

                if (data.subcategories.length === 0)
                {
                    subselect.empty().html(html);
                }

                else
                {
                    Object.keys(data.subcategories).forEach(function (i) {
                        html += '<option value="' + i + '">' + data.subcategories[i] + '</option>';
                    });

                    subselect.empty().html(html);
                }
            });
        })

        // change subcategory
        .on( 'change', 'select.sub', function ()
        {
            $.get('/product/update/' + $(this).data("id") + '?sub=' + $(this).val(), function (data)
            {
                if (data.message !== undefined && data.message !== '')
                    alert(data.message)
            });
        })

        // delete product
        .on( 'click', 'a.delete', function ()
        {
            $.get( $(this).attr('href'), function (data)
            {
                if (data.redirect === 'reload')
                    window.location.reload(true);
            });

            return false;
        })
});
