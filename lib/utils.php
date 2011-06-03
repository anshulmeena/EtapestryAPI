<?php

/**
 * Utility method to determine if a NuSoap fault or error occurred.
 * If so, output any relevant info and stop the code from executing. 
 */
function checkStatus($nsc)
{
  if ($nsc->fault || $nsc->getError())
  {
    if (!$nsc->fault)
    {
      echo "Error: ".$nsc->getError();
    }
    else
    {
      echo "Fault Code: ".$nsc->faultcode."<br>";
      echo "Fault String: ".$nsc->faultstring."<br>";
    }
    exit;
  }
}

/**
 * Start an eTapestry API session by instantiating a
 * nusoap_client instance and calling the login method.
 */
function startEtapestrySession()
{
  // Set login details and initial endpoint
  $loginId = "INPUT_LOGIN_ID";
  $password = "INPUT_PASSWORD";
  $endpoint = "https://sna.etapestry.com/v2messaging/service?WSDL";

  // Instantiate nusoap_client
  echo "Establishing NuSoap Client...";
  $nsc = new nusoap_client($endpoint, true);
  echo "Done<br><br>";

  // Did an error occur?
  checkStatus($nsc);

  // Invoke login method
  echo "Calling login method...";
  $newEndpoint = $nsc->call("login", array($loginId, $password));
  echo "Done<br><br>";

  // Did a soap fault occur?
  checkStatus($nsc);

  // Determine if the login method returned a value...this will occur
  // when the database you are trying to access is located at a different
  // environment that can only be accessed using the provided endpoint
  if ($newEndpoint != "")
  {
    echo "New Endpoint: $newEndpoint<br><br>";

    // Instantiate nusoap_client with different endpoint
    echo "Establishing NuSoap Client with new endpoint...";
    $nsc = new nusoap_client($newEndpoint, true);
    echo "Done<br><br>";

    // Did an error occur?
    checkStatus($nsc);

    // Invoke login method
    echo "Calling login method...";
    $nsc->call("login", array($loginId, $password));
    echo "Done<br><br>";

    // Did a soap fault occur?
    checkStatus($nsc);
  }

  // Output results
  echo "Login Successful<br><br>";
  
  return $nsc;
}

/**
 * Start an eTapestry API session by calling the logout
 * method given a nusoap_client instance.
 */
function stopEtapestrySession($nsc)
{
  // Invoke logout method
  echo "Calling logout method...";
  $nsc->call("logout");
  echo "Done";
}

/**
 * Take a United States formatted date (mm/dd/yyyy) and
 * convert it into a date/time string that NuSoap requires.
 */
function formatDateAsDateTimeString($dateStr)
{
  if ($dateStr == null || $dateStr == "") return "";
  if (substr_count($dateStr, "/") != 2) return "[Invalid Date: $dateStr]";

  $separator1 = stripos($dateStr, "/");
  $separator2 = stripos($dateStr, "/", $separator1 + 1);

  $month = substr($dateStr, 0, $separator1);
  $day = substr($dateStr, $separator1 + 1, $separator2 - $separator1 - 1);
  $year = substr($dateStr, $separator2 + 1);

  return ($month > 0 && $day > 0 && $year > 0) ? date(DATE_ATOM, mktime(0, 0, 0, $month, $day, $year)) : "[Invalid Date: $dateStr]";
}

?>