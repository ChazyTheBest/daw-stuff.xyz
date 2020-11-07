$(function ()
{
    $( 'a.buy' ).on( 'click', function ()
    {
        $.ajax({
            url: this.href,
            type: 'GET',
            success: function (data)
            {
                window.location.reload(true);
            },
            error: function(jqXHR, errMsg)
            {
                alert(errMsg);
            }
        })

        return false;
    })

    $( 'a#pay' ).on( 'click', function ()
    {
        $.ajax({
            url: this.href,
            type: 'GET',
            success: function (data)
            {
                if (data.msg)
                    alert(data.msg)

                else
                    window.location.replace(data.redirect);
            },
            error: function(jqXHR, errMsg)
            {
                alert(errMsg);
            }
        })

        return false;
    })
})
