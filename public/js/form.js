$(function ()
{
    const form = $( 'form:not(#search)' );

    form.on( 'submit', function (e)
    {
        if (dropzoneData)
        {
            dropzoneData(this);
        }

        else
        {
            $.ajax({
                url: $(this).attr('action'),
                type: 'POST',
                data: $(this).serialize(),
                success: success,
                error: error
            });
        }

        return false;
    });
});
