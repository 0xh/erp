<!DOCTYPE html>
<html>
 <body>

  <div>{!! $chart->container() !!}</div>

{{-- <script src="path/to/Chart.js/2.7.1/Chart.min.js" charset="utf-8"></script>--}}
<script src=//cdnjs.cloudflare.com/ajax/libs/Chart.js/2.7.1/Chart.min.js charset=utf-8></script>
 {!! $chart->script() !!}
 </body>
</html>