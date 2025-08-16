<!DOCTYPE html>
<html lang="en">
<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <title>Document</title>
</head>
<body>
<div class="container">
    <div class="btn-group">
       <a href="{{route('pfmp.flash')}}"> <button type="button" class="btn btn-info">
           PfMP
        </button></a>
        
    </div>
    <div class="btn-group">
        <a href="{{route('pgmp.flash')}}"><button type="button" class="btn btn-info ">
           PgMP
        </button></a>
        
    </div>
    <div class="btn-group">
       <a href="{{route('pmp.flash')}}"> <button type="button" class="btn btn-info ">
           PMP
        </button></a>
       
    </div>
    <div class="btn-group">
       <a href="{{route('pmiacp.flash')}}"> <button type="button" class="btn btn-info " >
           PMI-ACP
        </button></a>
       
    </div>
    <a href="{{route('pmirmp.flash')}}"><div class="btn-group">
        <button type="button" class="btn btn-info ">
           PMI-RMP
        </button></a>
        
    </div>
</div>

</body>
</html> 