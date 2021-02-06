$(function ()
{
    $( 'a.delete' ).on( 'click', function ()
    {
        $.ajax({
            url: this.href,
            type: 'GET',
            success: reload,
            error: error
        })

        return false;
    })

    $( 'input.update' ).on( 'change' , function (e)
    {
        const that = $( this ),
              oldVal = parseInt(that.data('old-value')),
              val = parseInt(that.val());

        let url = that.data('url'),
            ajax = {};

        // up/down buttons
        if (val === (oldVal + 1))
        {
            ajax = { url: url + '?op=up', type: 'GET' }
        }

        else if (val === (oldVal - 1))
        {
            ajax = { url: url + '?op=down', type: 'GET' }
        }

        else if (val !== oldVal)
        {
            ajax = { url: url, type: 'POST', data: { quantity: val } }
        }

        else
        {
            return;
        }

        ajax.success = reload;
        ajax.error = error;

        $.ajax(ajax);
    })
})

function reload()
{
    window.location.reload(true);
}

function error(jqXHR, errMsg)
{
    alert(errMsg);
}
