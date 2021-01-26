$(function ()
{
    const form = $( 'form' );

    form.on( 'submit', function ()
    {
        $.ajax({
            url: form.attr('action'),
            type: 'POST',
            data: form.serialize(),
            success: function (data)
            {
                // for colourness styled alert data.status === 'success
                console.log(data.message)
                if (data.message !== undefined && data.message !== '')
                    alert(data.message)

                if (data.redirect === 'home')
                    window.location.replace('/');

                else if (data.redirect === 'back')
                    window.history.back();

                else if (data.redirect === 'reload')
                    window.location.reload(true)

                else if (data.redirect !== '')
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
