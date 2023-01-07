@if ($email->html_body)
    {!! $email->html_body !!}
@elseif ($email->text_body)
    {{ $email->text_body }}
@else
    No body found!
@endif
