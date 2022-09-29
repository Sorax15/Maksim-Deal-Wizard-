@php
if($p > $totalPages)$p=$totalPages;
$first_item = '';
$last_item = '';
$next = $p + 1;
$back = $p - 1;
$start = 1;
$end = 5;

if($p > 3){
    $start =  $p - 2;
    $end = $p + 2;
    if($end > $totalPages){
        $end = $totalPages;
    }
}else{
    if($end > $totalPages){
        $end = $totalPages;
    }
}


@endphp






<nav aria-label="Page navigation">

  <ul class="pagination justify-content-center">
  @if($p == 1)
        <li class="page-item disabled"><a data-page="false" class="page-link" tabindex="-1" href="javascript:void(0)">Previous</a></li>

    @else
        <li class="page-item"><a onclick="paginate({{$back}})" class="page-link" href="javascript:void(0)">Previous</a></li>
    @endif


    @for($i = $start; $i <= $end; $i++)
        @if($i == $p)
            <li class="page-item active"><a data-page="false" class="page-link" href="javascript:void(0)">{{$i}}</a></li>
        @else
            <li class="page-item"><a onclick="paginate({{$i}})"  class="page-link" href="javascript:void(0)">{{$i}}</a></li>
        @endif

    @endfor





    @if($p == $totalPages)
        <li class="page-item disabled"><a data-page="false" class="page-link" tabindex="-1" href="javascript:void(0)">Next</a></li>

    @else
        <li class="page-item"><a onclick="paginate({{$next}})" class="page-link" href="javascript:void(0)">Next</a></li>
    @endif
  </ul>
</nav>
