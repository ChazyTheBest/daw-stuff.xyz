function success( data )
{
    if (data.message !== undefined && data.message !== '')
        alert(data.message)

    else if (data.redirect === 'home')
        window.location.replace('/');

    else if (data.redirect === 'back')
        window.history.back();

    else if (data.redirect === 'reload')
        window.location.reload(true)

    else if (data.redirect !== '')
        window.location.assign(data.redirect);
}

function error(jqXHR, errMsg)
{
    alert(errMsg);
}

jQuery.ajaxSetup({ async: false })

let texts = null,
    query = null,
    language = '';

function dtFilter(dt, column, term)
{
    dt.column(column).search(term, false, false, false).draw()
}

function getTableButtons(create, filter, misc = [])
{
    let buttons =
    {
        buttons: [],
        dom: { button: { className: 'btn my-3' }, buttonLiner: { tag: null } }
    }

    if (create)
    {
        buttons.buttons.push(
        {
            text: texts.buttons.names.create,
            className: 'btn-success',
            action: function ( e, dt, node, config ) {
                window.location = texts.buttons.create.url;
            }
        })
    }

    if (filter)
    {
        const names = texts.buttons.names,
              data = texts.buttons;

        buttons.buttons.push(
        {
            text: names.reset,
            className: 'btn-info',
            action: function( e, dt, node, config ) { dtFilter(dt, data.reset.columns, '') }
        })

        for (let i = 0; i < data.filter.length; i++)
        {
            let $class = i === 0 ? 'btn-primary' : 'btn-secondary'

            buttons.buttons.push(
            {
                text: names.filter[i],
                className: $class,
                action: function ( e, dt, node, config ) { dtFilter(dt, data.filter[i].columns, data.filter[i].term) }
            })
        }
    }

    if (misc.length !== 0)
    {
        // copy, excel, csv, pdf, print
    }

    return buttons;
}
