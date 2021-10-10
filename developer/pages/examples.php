<h5><b>Examples</b></h5>
<h6><b>JS/AJAX</b></h6>
<pre>
var url = "https://api.smartlist.ga/v1/test-key";

var xhr = new XMLHttpRequest();
xhr.open("POST", url);

xhr.setRequestHeader("Authorization", "Bearer token_here");
xhr.setRequestHeader("Content-Type", "application/json");
xhr.setRequestHeader("Content-Length", "0");

xhr.onreadystatechange = function () {
   if (xhr.readyState === 4) {
      console.log(xhr.status);
      console.log(xhr.responseText);
   }};

xhr.send();

</pre>

<h6><b>PHP</b></h6>
<pre>
&lt;?php

$url = &quot;https://api.smartlist.ga/v1/test-key&quot;;

$curl = curl_init($url);
curl_setopt($curl, CURLOPT_URL, $url);
curl_setopt($curl, CURLOPT_POST, true);
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

$headers = array(
   &quot;Authorization: Bearer token_here&quot;,
   &quot;Content-Type: application/json&quot;,
   &quot;Content-Length: 0&quot;,
);
curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
//for debug only!
curl_setopt($curl, CURLOPT_SSL_VERIFYHOST, false);
curl_setopt($curl, CURLOPT_SSL_VERIFYPEER, false);

$resp = curl_exec($curl);
curl_close($curl);
var_dump($resp);

?&gt;


</pre>

<h6><b>Python</b></h6>
<pre>
import requests
from requests.structures import CaseInsensitiveDict

url = "https://api.smartlist.ga/v1/test-key"

headers = CaseInsensitiveDict()
headers["Authorization"] = "Bearer token_here"
headers["Content-Type"] = "application/json"
headers["Content-Length"] = "0"


resp = requests.post(url, headers=headers)

print(resp.status_code)


</pre>

<h6><b>Java</b></h6>
<pre>
URL url = new URL("https://api.smartlist.ga/v1/test-key");
HttpURLConnection http = (HttpURLConnection)url.openConnection();
http.setRequestMethod("POST");
http.setDoOutput(true);
http.setRequestProperty("Authorization", "Bearer token_here");
http.setRequestProperty("Content-Type", "application/json");
http.setRequestProperty("Content-Length", "0");

System.out.println(http.getResponseCode() + " " + http.getResponseMessage());
http.disconnect();

</pre>
<h6><b>C#/.NET</b></h6>
<pre>
var url = "https://api.smartlist.ga/v1/test-key";

var httpRequest = (HttpWebRequest)WebRequest.Create(url);
httpRequest.Method = "POST";

httpRequest.Headers["Authorization"] = "Bearer token_here";
httpRequest.ContentType = "application/json";
httpRequest.Headers["Content-Length"] = "0";


var httpResponse = (HttpWebResponse)httpRequest.GetResponse();
using (var streamReader = new StreamReader(httpResponse.GetResponseStream()))
{
   var result = streamReader.ReadToEnd();
}

Console.WriteLine(httpResponse.StatusCode);

</pre>

<h6><b>cURL/BASH</b></h6>
<pre>
#!/bin/bash

curl -X POST https://api.smartlist.ga/v1/test-key 
-H "Authorization: Bearer token_here" 
-H "Content-Type: application/json" 
-H "Content-Length: 0" 

</pre>
