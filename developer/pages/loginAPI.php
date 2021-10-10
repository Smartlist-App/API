
<style>.collapsible-header {font-weight:bold!important;background:rgba(0,0,0,0.1)!important}</style>
<h5><b>Login API</b></h5>
<p>Smartlist provides an easy-to-use login API! Follow the instructions step-by-step to implement our API in your application!</p>

<ul class="collapsible">
  <li>
    <div class="collapsible-header waves-effect"><i class="material-icons">api</i>Step 1 - Creating an app</div>
    <div class="collapsible-body">
      <p>Fill out the form below to create an app! <b>Make sure to keep the secret in a safe place! This information won't be displayed again!</b></p>
      <form action="./addApp.php" method="POST" id="addForm">
        <div class="input-field">
          <label>App Name</label>
          <input type="text" name="name">
        </div>
        <div class="input-field">
          <label>Redirect URI</label>
          <input type="text" name="uri">
        </div>
        <button class="btn blue-grey">Add</button>
      </form>
    </div>
  </li>
  <li>
    <div class="collapsible-header waves-effect"><i class="material-icons">smart_button</i>Step 2 - Adding the button to your app</div>
    <div class="collapsible-body">
      Download the JS files from: <a target="_blank" href="https://github.com/Smartlist-App/Smartlist-Login-Button">https://github.com/Smartlist-App/Smartlist-Login-Button</a>
      <code class="prettyprint"><pre>
&lt;!DOCTYPE html&gt;
&lt;html&gt;
  &lt;head&gt;
  &lt;/head&gt;
  &lt;body&gt;
    &lt;div id=&quot;loginBTN&quot;&gt;&lt;/div&gt;
    &lt;script src=&quot;/path/to/app.min.js&quot;&gt;&lt;/script&gt;
    &lt;!-- If you want to be cool, use the &quot;obfuscated.min.js&quot; file instead --&gt;
    &lt;script&gt;
       window.addEventListener(&#39;load&#39;, () =&gt; {
        var smartlistLogin = new SmartlistApiButton(document.getElementById(&#39;loginBTN&#39;), {
          // Required
          // Get auth code from dashboard
          authURI: &quot;<span id="d" onclick="loadPage('./pages/apps.php')" class="grey lighten-1 waves-effect" style="color:white!important;font-size:13px;line-height:16px!important;padding: 0px;border-radius:9999px;">app_id_here</span>&quot;,
          // Optional styles
          iconColor: &quot;#000&quot;,
          backgroundColor: &quot;#fff&quot;,
          fontColor: &quot;#000&quot;,
        })
        // Automatically login when instance is created
        // smartlistLogin.login();

        // Delete a button (Index starts at 1)!
        // smartlistLogin.delete(1);
        })
    &lt;/script&gt;
  &lt;/body&gt;
&lt;/html&gt;

      </pre></code>

    </div>
  </li>
  <li>
    <div class="collapsible-header waves-effect"><i class="material-icons">admin_panel_settings</i>Step 3 - Adding an auth file to your app</div>
    <div class="collapsible-body">
      After the user clicks on the button, and logs in to their account, we'll automatically create a data retrieval token, and send it via a "token" parameter in your redirect URI.
      <br>
      <h6>Example</h6>
      <b>https://smartlist.ga/?token=<?=hash('sha512',rand(0,99999));?></b>
    </div>
  </li>

  <li>
    <div class="collapsible-header waves-effect"><i class="material-icons">account_circle</i>Step 4 - Getting user info</div>
    <div class="collapsible-body">
      To get user info, send a POST request to <b>https://api.smartlist.ga/v1/oauth/credentials</b> with the headers: 
      <br>
      <table>
        <tr>
          <td><b>Name</b></td>
          <td><b>Header</b></td>
        </tr>
        <tr>
          <td>Authorization</td>
          <td>Bearer [YOUR_KEY_HERE]</td>
        </tr>
        <tr><td>Content-Type</td><td>application/x-www-form-urlencoded</td></tr>
      </table>
      <b>Data</b>
      <table>
        <tr>
          <td><b>Name</b></td>
          <td><b>Header</b></td>
        </tr>
        <tr>
          <td>token</td>
          <td>token_here ($_GET['token'])</td>
        </tr>
      </table>
      <p class="red-text">Important: DO NOT load this file via JavaScript! Your app's secret is really sensitive information. We would recommend you to use Environment Variables (If your project's open source). If your project is open source, do not upload the key!</p>
      <b>Example</b>
      <code class="prettyprint"><pre>
&lt;?php

$url = &quot;https://api.smartlist.ga/v1/oauth/credentials/&quot;;

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$headers = array(
  &quot;Authorization: Bearer YOUR_SECRET_HERE&quot;,
  &quot;Content-Type: application/x-www-form-urlencoded&quot;,
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);

$data = &quot;token=&quot;.$_GET[&#39;token&#39;];

curl_setopt($curl, CURLOPT_POSTFIELDS, $data);

//for debug only!
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$resp = curl_exec($curl);
curl_close($curl);
$resp = json_decode($resp);
$name = $resp-&gt;name;
$email = $resp-&gt;email;
$avatar = $resp-&gt;user_avatar;
$id = $resp-&gt;id;
?&gt;
&lt;!DOCTYPE html&gt;
&lt;html&gt;
  &lt;head&gt;
    &lt;title&gt;Demo&lt;/title&gt;
  &lt;/head&gt;
  &lt;body style=&quot;font-family:arial&quot;&gt;
    &lt;center&gt;
      &lt;h5&gt;Success!&lt;/h5&gt;
      &lt;img src=&quot;&lt;?=$avatar;?&gt;&quot; style=&quot;width: 300px;border-radius:9999px;height:300px;object-fit:cover&quot;&gt;
      &lt;h1&gt;&lt;?=$name;?&gt;&lt;/h1&gt;
      &lt;h2&gt;&lt;?=$email;?&gt;&lt;/h2&gt;
      &lt;p&gt;User ID: &lt;?=$id;?&gt;&lt;/p&gt;
    &lt;/center&gt;
  &lt;/body&gt;
&lt;/html&gt;
</pre></code>
    </div>
  </li>
</ul>
<script>
  $(document).ready(function(){
    $('.collapsible').collapsible({
      accordion: false
    });
	document.querySelectorAll('.collapsible-header').forEach(el => el.click())
  });

  // this is the id of the form
  $("#addForm").submit(function(e) {

    e.preventDefault(); // avoid to execute the actual submit of the form.

    var form = $(this);
    var url = form.attr('action');

    $.ajax({
      type: "POST",
      url: url,
      data: form.serialize(), // serializes the form's elements.
      success: function(data)
      {
        document.getElementById('addForm').innerHTML = `Here's your app's ID: ${data.split("|")[0]}`;
        document.getElementById('addForm').innerHTML += `<br>Here's your app's Secret: ${data.split("|")[1]}`;
        document.getElementById('d').innerHTML = data.split("|")[0];
      }
    });
  });
  PR.prettyPrint();
</script>