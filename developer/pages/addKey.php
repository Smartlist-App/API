<h5><b>Add a key</b></h5>
<form method="POST" action="./addKeyAction.php" id="form">
  <div class="row">
    <div class="col s12 m6">

      <div class="input-field">
        <label>API key name</label>
        <input type="text" name="name">
      </div>
      <button class="blue-grey darken-3 btn waves-effect waves-light">Create</button>

    </div>
  </div>
</form>
<script>
  // this is the id of the form
  $("#form").submit(function(e) {
    e.preventDefault(); // avoid to execute the actual submit of the form.
    var form = $(this);
    var url = form.attr('action');
    $.ajax({
      type: "POST",
      url: url,
      data: form.serialize(),
      success: function(data) {
        loadPage("./pages/keys.php")
      }
    });
  });
</script>