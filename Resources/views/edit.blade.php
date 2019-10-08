@extends('header')

@section('content')

    {!! Former::open($url)
            ->addClass('col-md-10 col-md-offset-1 warn-on-exit')
            ->method($method)
            ->rules([]) !!}

    @if ($pointofsale)
      {!! Former::populate($pointofsale) !!}
      <div style="display:none">
          {!! Former::text('public_id') !!}
      </div>
    @endif

                <div class="panel panel-default">
                <div class="panel-body">
                    {!! Former::text('upc')
                        ->label(trans("pointofsale::texts.upc")) !!}
                </div>
    </div>

    <center class="buttons">

        {!! Button::normal(trans('texts.cancel'))
                ->large()
                ->asLinkTo(URL::to('/pointofsale'))
                ->appendIcon(Icon::create('remove-circle')) !!}

        {!! Button::success(trans('texts.save'))
                ->submit()
                ->large()
                ->appendIcon(Icon::create('floppy-disk')) !!}

    </center>

    {!! Former::close() !!}


    <script type="text/javascript">

        $(function() {
            $(".warn-on-exit input").first().focus();
        })

    </script>


@stop
