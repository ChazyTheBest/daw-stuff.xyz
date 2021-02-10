$(function ()
{
    $( 'a.cart_delete' ).on( 'click', function ()
    {
        $.ajax({
            url: this.href,
            type: 'GET',
            success: success,
            error: error
        })

        return false;
    })

    $( 'input.cart_update' ).on( 'change' , function (e)
    {
        const that = $( this ),
              oldVal = parseInt(that.data('old-value')),
              val = parseInt(that.val());

        if (val < 1 || val > 999 || val === oldVal)
            return;

        let url = that.data('url'),
            ajax = { url: url, type: 'POST', data: { quantity: val } };

        // up/down buttons
        if (val === (oldVal + 1))
        {
            ajax = { url: url + '?op=up', type: 'GET' }
        }

        else if (val === (oldVal - 1))
        {
            ajax = { url: url + '?op=down', type: 'GET' }
        }

        ajax.success = success;
        ajax.error = error;

        $.ajax(ajax);
    })
})
