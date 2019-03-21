<script>
document.getElementById("main_content_header").innerHTML="Admininstration page";
</script>
<form method='post' action="?controller=account&action=parse_news" name="form"  class="form-horizontal">
  
  
  <div class="form-group">
    <div class="col-sm-offset-1 col-sm-4"> 
      <input type="checkbox" name="reddit" value="Reddit" checked> Reddit<br>
    </div>
  </div>
  
  <div class="form-group">
    <div class="col-sm-offset-1 col-sm-4"> 
      <input type="checkbox" name="computernews" value="Computernews" checked> Computernews<br>
    </div>
  </div>
  
  <div class="form-group"> 
    <div class="col-sm-offset-1 col-sm-10">
      <button type="submit" class="btn btn-default">Parse</button>
    </div>
  </div>
</form>