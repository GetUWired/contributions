<!DOCTYPE html>
<html>
<body>

<h1>The form method="get" attribute</h1>

<form action="https://gstaging.getuwired.us/engconcepts/1.1_Code_School/Jon/formjs.php" method="get">
  <label for="fname">First name:</label>
  <input type="text" id="fname" name="fname"><br><br>
  <label for="lname">Last name:</label>
  <input type="text" id="lname" name="lname"><br><br>
  <input type="hidden" id="UTMCampaign" name="UTMCampaign"><br><br>
  <input type="submit" value="Submit">
</form>

<p>Click on the submit button, and the input will be sent to a page on the server called "action_page.php".</p>

<script>

    url_string = window.location.href;
    url = new URL(url_string);
    
    campaign = url.searchParams.get("utm_campaign");
    document.getElementById("UTMCampaign").value = campaign;

    console.log("Logging: ");
    console.log(campaign);

</script>

</body>
</html>
