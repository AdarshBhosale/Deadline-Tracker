<?php
session_start();
session_unset();
session_destroy();

// Redirect to index.html with a query param
header("Location: index.html?logout=1");
exit;
?>