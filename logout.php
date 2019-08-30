<?php 
session_start();

unset($_SESSION['voenlog']);
unset($_SESSION['voenkaf']); 
unset($_SESSION['testsrc']); 
unset($_SESSION['error']); 

session_destroy();

