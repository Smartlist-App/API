<style>.e {float:none !important;color: white !important;vertical-align:middle; margin-right: 10px;padding: 1px 10px;border-radius: 999px} code {background: #eee;color: #303030;padding: 2px;display:block;width:auto} span {vertical-align:middle;}</style>
<h4><b>Dashboard</b></h4>
<p>Manage your APIs!</p>

<h6>API key reference</h6>

<div id="embed"></div>
<script>
  $("#embed").load('./pages/options.php', function(){
    $('.collapsible').collapsible({
      // specify options here
    });
  });
</script>