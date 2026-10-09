

@if($paginator->hasPages())

<div class="influ-pagi">

    <ul>

    @if ($paginator->onFirstPage())

        <li><a href="javascript:void(0);" class="disabled"><i>Prev</i></a></li>

    @else

        <li><a href="{{ $paginator->previousPageUrl() }}"><i>Prev</i></a></li>

    @endif

    @foreach ($elements as $element)

        @if(is_string($element))

            <li class="disabled"><a href="#">....</a></li>

        @endif

        @if (is_array($element))

            @foreach ($element as $page => $url)

                @if ($page == $paginator->currentPage())

                    <li class="active"><a>{{ $page }}</a></li>

                @else

                    <li><a href="{{ $url }}">{{ $page }}</a></li>

                @endif

            @endforeach

        @endif

    @endforeach

    @if ($paginator->hasMorePages())

        <li><a href="{{ $paginator->nextPageUrl() }}" rel="next"><i>Next</i></a></li>

    @else

    <li><a href="javascript:void(0);"><i>Next</i></a></li>

    @endif

    </ul>
    @php
        $startIndex = ($paginator->currentPage() - 1) * $paginator->perPage() + 1;
        $endIndex = $startIndex + $paginator->count() - 1;
    @endphp

    <div class="count_show">
        <div class="showing-1">
        <p>Showing {{$startIndex}} to {{$endIndex }} of {{$paginator->total()}} results</p>
        </div>
</div>
</div>

@endif 



<!-- <div class="influ-pagi">

    <ul>

        <li><a href="#"><i>Prev</i></a></li>

        <li class="active"><a href="#">1</a></li>

        <li><a href="#">2</a></li>

        <li><a href="#">3</a></li>

        <li><a href="#">4</a></li>

        <li><a href="#">5</a></li>

        <li><a href="#"><i>Next</i></a></li>

    </ul>

    <form action="">

        <div class="influ-pagi-in">

            <p>Showing 50 of 170 results</p>

        </div> 

    </form>

</div> -->



